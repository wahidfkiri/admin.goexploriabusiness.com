<?php

namespace App\Crawlers;

use Spatie\Crawler\CrawlObservers\CrawlObserver;
use Psr\Http\Message\UriInterface;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Exception\RequestException;
use App\Models\CrawledPage;

class PageContentObserver extends CrawlObserver
{
    public function crawled(
        UriInterface $url,
        ResponseInterface $response,
        ?UriInterface $foundOnUrl = null,
        ?string $linkText = null
    ): void {
        $html = (string) $response->getBody();

        $cleanHtml = $this->cleanHtml($html, (string) $url);
        $cleanCss  = $this->cleanCss($html, (string) $url);

        CrawledPage::updateOrCreate(
            ['url' => (string) $url],
            [
                'html' => $cleanHtml,
                'css'  => $cleanCss,
            ]
        );
    }

    public function crawlFailed(
        UriInterface $url,
        RequestException $requestException,
        ?UriInterface $foundOnUrl = null,
        ?string $linkText = null
    ): void {
        logger()->error('Crawl failed', [
            'url' => (string) $url,
            'error' => $requestException->getMessage(),
        ]);
    }

    /* ============================================================
        HTML CLEANER
    ============================================================ */
    private function cleanHtml(string $html, string $baseUrl): string
    {
        libxml_use_internal_errors(true);

        $dom = new \DOMDocument('1.0', 'UTF-8');
        $dom->loadHTML(
            mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8')
        );

        $xpath = new \DOMXPath($dom);

        /* Remove unwanted tags */
        foreach (['script', 'noscript', 'iframe', 'svg', 'canvas', 'meta', 'link'] as $tag) {
            while (($nodes = $dom->getElementsByTagName($tag))->length > 0) {
                $nodes->item(0)->parentNode->removeChild($nodes->item(0));
            }
        }

        /* Remove comments */
        foreach ($xpath->query('//comment()') as $comment) {
            $comment->parentNode->removeChild($comment);
        }

        /* Clean attributes + fix images */
        foreach ($xpath->query('//*[@*]') as $node) {
            foreach (iterator_to_array($node->attributes) as $attr) {
                $name = strtolower($attr->nodeName);

                // Remove inline JS
                if (str_starts_with($name, 'on')) {
                    $node->removeAttribute($attr->nodeName);
                }

                // Remove tracking & useless attrs
                if (in_array($name, [
                    'data-*', 'aria-*', 'role', 'loading',
                    'decoding', 'fetchpriority'
                ])) {
                    $node->removeAttribute($attr->nodeName);
                }
            }

            /* Fix images */
            if ($node->nodeName === 'img') {
                $src = $node->getAttribute('src')
                    ?: $node->getAttribute('data-src');

                if ($src) {
                    $node->setAttribute('src', $this->resolveUrl($src, $baseUrl));
                }

                $node->removeAttribute('srcset');
                $node->removeAttribute('sizes');
                $node->removeAttribute('data-src');
                $node->removeAttribute('loading');
            }
        }

        /* Extract BODY only */
        $body = $dom->getElementsByTagName('body')->item(0);
        $clean = '';

        if ($body) {
            foreach ($body->childNodes as $child) {
                $clean .= $dom->saveHTML($child);
            }
        }

        return trim(preg_replace('/\s{2,}/', ' ', $clean));
    }

    /* ============================================================
        CSS CLEANER
    ============================================================ */
    private function cleanCss(string $html, string $baseUrl): string
    {
        preg_match_all('/<style[^>]*>(.*?)<\/style>/is', $html, $matches);
        $css = implode("\n", $matches[1] ?? []);

        // Remove comments
        $css = preg_replace('!/\*.*?\*/!s', '', $css);

        // Remove @font-face, @keyframes, @media
        $css = preg_replace('/@font-face\s*{[^}]*}/i', '', $css);
        $css = preg_replace('/@keyframes\s*[^{]+{[^}]+}/i', '', $css);
        $css = preg_replace('/@media[^{]+{([\s\S]+?})\s*}/i', '', $css);

        // Remove CSS variables
        $css = preg_replace('/--[a-zA-Z0-9\-]+\s*:\s*[^;]+;/', '', $css);

        // Fix background images
        $css = preg_replace_callback(
            '/url\((.*?)\)/',
            fn($m) => 'url(' . $this->resolveUrl(trim($m[1], '\'"'), $baseUrl) . ')',
            $css
        );

        return trim(preg_replace('/\s{2,}/', ' ', $css));
    }

    /* ============================================================
        URL RESOLVER
    ============================================================ */
    private function resolveUrl(string $url, string $base): string
    {
        if (str_starts_with($url, 'http')) return $url;
        if (str_starts_with($url, '//')) return 'https:' . $url;

        return rtrim($base, '/') . '/' . ltrim($url, '/');
    }
}
