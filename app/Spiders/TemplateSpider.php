<?php
// app/Spiders/TemplateSpider.php

namespace App\Spiders;

use RoachPHP\Http\Response;
use RoachPHP\Spider\BasicSpider;
use RoachPHP\Extensions\LoggerExtension;
use RoachPHP\Extensions\StatsCollectorExtension;
use RoachPHP\Downloader\Middleware\RequestDeduplicationMiddleware;

class TemplateSpider extends BasicSpider
{
    public array $startUrls = [];
    
    public array $downloaderMiddleware = [
        RequestDeduplicationMiddleware::class,
    ];
    
    public array $spiderMiddleware = [
        //
    ];
    
    public array $itemProcessors = [
        //
    ];
    
    public array $extensions = [
        LoggerExtension::class,
        StatsCollectorExtension::class,
    ];
    
    public int $concurrency = 2;
    public int $requestDelay = 1;
    
    public function parse(Response $response): \Generator
    {
        $url = $response->getUri();
        
        // Extract body content WITH CDN CSS and JS
        $htmlBody = $this->extractBodyContent($response);
        
        // Clean HTML for GrapeJS compatibility
        $htmlBody = $this->cleanHTMLForGrapeJS($htmlBody);
        
        // Extract CSS (seulement les styles inline)
        $cssContent = $this->extractInlineCSS($response);
        
        // Clean CSS for GrapeJS compatibility
        $cssContent = $this->cleanCSSForGrapeJS($cssContent);
        
        // Extract metadata
        $metadata = [
            'url' => $url,
            'title' => $response->filter('title')->text('No title'),
            'description' => $response->filter('meta[name="description"]')->attr('content', ''),
            'keywords' => $response->filter('meta[name="keywords"]')->attr('content', ''),
            'language' => $response->filter('html')->attr('lang', ''),
            'charset' => $response->filter('meta[charset]')->attr('charset', 
                $response->filter('meta[http-equiv="Content-Type"]')->attr('content', '')),
            'scraped_at' => now()->toDateTimeString(),
        ];
        
        yield $this->item([
            'url' => $url,
            'html_body' => $htmlBody, // Avec CDN CSS et JS externes
            'css_content' => $cssContent, // CSS inline seulement
            'metadata' => $metadata,
        ]);
    }
    
    private function extractBodyContent(Response $response): string
    {
        try {
            // Vérifier si le body existe
            if ($response->filter('body')->count() === 0) {
                // Si pas de body, retourner le HTML complet sans balises externes
                $html = $response->filter('html')->html();
                return $this->removeOuterTags($html);
            }
            
            // Récupérer le HTML complet du body
            $bodyHtml = $response->filter('body')->html();
            
            // Extraire SEULEMENT le contenu intérieur (sans balises body)
            $innerContent = $this->removeBodyTags($bodyHtml);
            
            // Extraire les CDN CSS et JS du head pour les ajouter
            $cdnContent = $this->extractCDNFromHead($response);
            
            // Combiner CDN + contenu body nettoyé
            $fullContent = $cdnContent . "\n" . $this->cleanBodyContent($innerContent);
            
            return trim($fullContent);
            
        } catch (\Exception $e) {
            return $this->extractBodyContentFallback($response);
        }
    }
    
    private function extractCDNFromHead(Response $response): string
    {
        $cdnContent = '';
        
        // Extraire les CDN CSS du head (<link rel="stylesheet" href="cdn...">)
        $cssCDNs = $response->filter('head link[rel="stylesheet"]')->each(function ($node) {
            $href = $node->attr('href');
            // Filtrer seulement les CDN externes (qui commencent par http ou //)
            if ($href && (strpos($href, 'http') === 0 || strpos($href, '//') === 0)) {
                return $node->outerHtml();
            }
            return null;
        });
        
        $cssCDNs = array_filter($cssCDNs);
        if (!empty($cssCDNs)) {
            $cdnContent .= implode("\n", $cssCDNs) . "\n";
        }
        
        // Extraire les CDN JavaScript externes du head (<script src="cdn..."></script>)
        $jsCDNs = $response->filter('head script[src]')->each(function ($node) {
            $src = $node->attr('src');
            // Filtrer seulement les CDN externes
            if ($src && (strpos($src, 'http') === 0 || strpos($src, '//') === 0)) {
                return $node->outerHtml();
            }
            return null;
        });
        
        $jsCDNs = array_filter($jsCDNs);
        if (!empty($jsCDNs)) {
            $cdnContent .= implode("\n", $jsCDNs) . "\n";
        }
        
        // Extraire aussi les CDN du body
        $bodyJsCDNs = $response->filter('body script[src]')->each(function ($node) {
            $src = $node->attr('src');
            // Filtrer seulement les CDN externes
            if ($src && (strpos($src, 'http') === 0 || strpos($src, '//') === 0)) {
                return $node->outerHtml();
            }
            return null;
        });
        
        $bodyJsCDNs = array_filter($bodyJsCDNs);
        if (!empty($bodyJsCDNs)) {
            $cdnContent .= implode("\n", $bodyJsCDNs) . "\n";
        }
        
        // Extraire les polices Google Fonts, Font Awesome, etc.
        $fontCDNs = $response->filter('head link[href*="fonts.googleapis.com"], head link[href*="fonts.gstatic.com"], head link[href*="fontawesome"], head link[href*="kit.fontawesome.com"]')->each(function ($node) {
            return $node->outerHtml();
        });
        
        if (!empty($fontCDNs)) {
            $cdnContent .= implode("\n", $fontCDNs) . "\n";
        }
        
        return trim($cdnContent);
    }
    
    private function cleanBodyContent(string $html): string
    {
        // Garder les CDN dans le body (ils seront extraits séparément)
        // Supprimer SEULEMENT le JavaScript inline et autres éléments indésirables
        
        // Supprimer les scripts inline (<script>...</script>) mais garder les CDN (<script src="...">)
        $html = preg_replace('/<script\b[^>]*>(?!.*src)[\s\S]*?<\/script>/is', '', $html);
        
        // Supprimer les balises noscript
        $html = preg_replace('/<noscript\b[^>]*>.*?<\/noscript>/is', '', $html);
        
        // Supprimer les commentaires HTML
        $html = preg_replace('/<!--.*?-->/s', '', $html);
        
        // Supprimer les iframes (Google Tag Manager, etc.)
        $html = preg_replace('/<iframe\b[^>]*>.*?<\/iframe>/is', '', $html);
        
        // Supprimer les JSON-LD et autres données structurées
        $html = preg_replace('/<script\b[^>]*type=["\']application\/ld\+json["\'][^>]*>.*?<\/script>/is', '', $html);
        
        // Supprimer les attributs événementiels (onclick, onload, etc.)
        $html = preg_replace('/\s+on[a-zA-Z]+=["\'][^"\']*["\']/', '', $html);
        
        // Supprimer les attributs data-* spécifiques (optionnel)
        // $html = preg_replace('/\s+data-[a-zA-Z-]+=["\'][^"\']*["\']/', '', $html);
        
        return trim($html);
    }
    
    private function removeBodyTags(string $html): string
    {
        $html = preg_replace('/^<body[^>]*>/i', '', $html);
        $html = preg_replace('/<\/body>$/i', '', $html);
        
        if (strpos($html, '<body') !== false || strpos($html, '</body>') !== false) {
            $html = preg_replace('/<\/?body[^>]*>/i', '', $html);
        }
        
        return trim($html);
    }
    
    private function removeOuterTags(string $html): string
    {
        $html = preg_replace('/<\/?(html|head|body)[^>]*>/i', '', $html);
        $html = preg_replace('/<!DOCTYPE[^>]*>/i', '', $html);
        $html = preg_replace('/<\?xml[^>]*\?>/i', '', $html);
        
        return trim($html);
    }
    
    private function extractBodyContentFallback(Response $response): string
    {
        try {
            $content = $response->getContent();
            
            if (preg_match('/<body[^>]*>(.*?)<\/body>/is', $content, $matches)) {
                $bodyContent = $matches[1];
                
                // Extraire les CDN du head
                $cdnContent = '';
                if (preg_match('/<head[^>]*>(.*?)<\/head>/is', $content, $headMatches)) {
                    // CDN CSS
                    if (preg_match_all('/<link[^>]*rel=["\']stylesheet["\'][^>]*href=["\'][^"\']*["\'][^>]*>/i', $headMatches[1], $cssMatches)) {
                        $cdnContent .= implode("\n", $cssMatches[0]) . "\n";
                    }
                    // CDN JS
                    if (preg_match_all('/<script[^>]*src=["\'][^"\']*["\'][^>]*><\/script>/i', $headMatches[1], $jsMatches)) {
                        $cdnContent .= implode("\n", $jsMatches[0]) . "\n";
                    }
                }
                
                // Nettoyer le contenu du body
                $bodyContent = $this->removeBodyTags($bodyContent);
                $bodyContent = $this->cleanBodyContent($bodyContent);
                
                return trim($cdnContent . "\n" . $bodyContent);
            }
            
            return $this->cleanBodyContent($this->removeOuterTags($content));
            
        } catch (\Exception $e) {
            return '<div>Error extracting content</div>';
        }
    }
    
    private function cleanHTMLForGrapeJS(string $html): string
    {
        if (empty($html)) {
            return '<div>No content found</div>';
        }
        
        // Nettoyer les caractères problématiques
        $html = str_replace(["\u{2028}", "\u{2029}", "\u{0085}"], '', $html);
        
        // Normaliser les fins de ligne
        $html = str_replace(["\r\n", "\r"], "\n", $html);
        
        // Supprimer les lignes vides multiples
        $html = preg_replace('/\n\s*\n+/', "\n", $html);
        
        // Nettoyer les espaces
        $html = preg_replace('/^\s+|\s+$/m', '', $html);
        $html = trim($html);
        
        // S'assurer que les balises auto-fermantes sont correctes
        $selfClosingTags = ['img', 'br', 'hr', 'input', 'meta', 'link'];
        foreach ($selfClosingTags as $tag) {
            $html = preg_replace('/<' . $tag . '([^>]*)(?<!\/)>/', '<' . $tag . '$1/>', $html);
        }
        
        // Nettoyer les attributs XML
        $html = preg_replace('/\s+xmlns:[a-zA-Z]+="[^"]*"/', '', $html);
        $html = preg_replace('/\s+xmlns="[^"]*"/', '', $html);
        
        // S'assurer que le HTML est bien formé
        $html = $this->ensureProperHTML($html);
        
        return $html;
    }
    
    private function ensureProperHTML(string $html): string
    {
        $tagsToClose = ['div', 'p', 'span', 'section', 'article', 'header', 'footer', 'nav', 'main', 
                       'ul', 'ol', 'li', 'a', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6'];
        
        foreach ($tagsToClose as $tag) {
            preg_match_all('/<' . $tag . '[^>]*>/i', $html, $openTags);
            preg_match_all('/<\/' . $tag . '>/i', $html, $closeTags);
            
            $openCount = count($openTags[0]);
            $closeCount = count($closeTags[0]);
            
            if ($openCount > $closeCount) {
                $missing = $openCount - $closeCount;
                for ($i = 0; $i < $missing; $i++) {
                    $html .= '</' . $tag . '>';
                }
            }
        }
        
        return $html;
    }
    
    private function extractInlineCSS(Response $response): string
    {
        $cssContent = '';
        
        // Extraire les styles inline (sauf ceux qui viennent de CDN)
        $inlineStyles = $response->filter('style')->each(function ($node) {
            // Vérifier si c'est un style inline (pas un CDN)
            $html = $node->outerHtml();
            if (!preg_match('/src=/', $html)) {
                return $node->text();
            }
            return null;
        });
        
        $inlineStyles = array_filter($inlineStyles);
        if (!empty($inlineStyles)) {
            $cssContent .= implode("\n", $inlineStyles);
        }
        
        // Extraire les attributs style des éléments
        $inlineStyleAttributes = $response->filter('[style]')->each(function ($node) {
            $style = $node->attr('style');
            $tag = $node->nodeName();
            $class = $node->attr('class');
            $id = $node->attr('id');
            
            if (!empty($style)) {
                $selector = $id ? "#{$id}" : ($class ? ".{$class}" : $tag);
                return "{$selector} { {$style} }";
            }
            
            return null;
        });
        
        $filteredAttributes = array_filter($inlineStyleAttributes);
        if (!empty($filteredAttributes)) {
            $uniqueAttributes = array_unique($filteredAttributes);
            if (!empty($cssContent)) {
                $cssContent .= "\n\n";
            }
            $cssContent .= implode("\n", $uniqueAttributes);
        }
        
        return trim($cssContent);
    }
    
    private function cleanCSSForGrapeJS(string $css): string
    {
        if (empty($css)) {
            return '/* No inline CSS found */';
        }
        
        $css = str_replace(["\u{2028}", "\u{2029}", "\u{0085}"], '', $css);
        $css = str_replace(["\r\n", "\r"], "\n", $css);
        $css = preg_replace('/\/\*[\s\S]*?\*\//', '', $css);
        $css = preg_replace('/\n\s*\n+/', "\n", $css);
        $css = trim($css);
        
        $css = preg_replace('/\s*{\s*/', ' {', $css);
        $css = preg_replace('/\s*}\s*/', "}\n", $css);
        $css = preg_replace('/\s*;\s*/', ';', $css);
        $css = preg_replace('/\s*:\s*/', ': ', $css);
        $css = preg_replace('/[^{]+\{\s*\}/m', '', $css);
        
        return $css;
    }
    
    private function resolveUrl(string $relativeUrl, string $baseUrl): string
    {
        if (filter_var($relativeUrl, FILTER_VALIDATE_URL)) {
            return $relativeUrl;
        }
        
        $base = parse_url($baseUrl);
        $relative = parse_url($relativeUrl);
        
        $scheme = $relative['scheme'] ?? $base['scheme'] ?? 'http';
        $host = $relative['host'] ?? $base['host'] ?? '';
        $path = $relative['path'] ?? '';
        
        if (strpos($path, '/') !== 0) {
            $basePath = $base['path'] ?? '/';
            $path = rtrim(dirname($basePath), '/') . '/' . $path;
        }
        
        return "{$scheme}://{$host}{$path}";
    }
}