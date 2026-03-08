<?php

namespace Vendor\Gemini\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Gemini\Laravel\Facades\Gemini;
use App\Models\Template;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class GeminiController extends Controller
{
    public function index() {
        return view('generator');
    }

    public function generate(Request $request)
    {
        $request->validate([
            'prompt' => 'required|string|min:10|max:2000',
            'template_name' => 'nullable|string|max:100',
        ]);

        try {
            // Construire le prompt amélioré
            $prompt = "Tu es un expert en développement web. Génère une page HTML complète et moderne basée sur cette description : " . $request->prompt . 
                      "\n\nInstructions importantes :
                      1. Utilise HTML5 sémantique
                      2. Inclus du CSS interne (dans une balise <style>) qui soit moderne et responsive
                      3. Utilise des couleurs attractives et un design professionnel
                      4. Inclus des sections typiques (header, hero, features, footer, etc.)
                      5. Le code doit être propre et bien indenté
                      6. N'utilise PAS Tailwind ou d'autres frameworks CSS externes
                      7. Ne mets PAS de JavaScript
                      8. Retourne UNIQUEMENT le code HTML complet avec le CSS intégré
                      9. Le code doit être prêt à être utilisé directement";

            // Appel à l'API Gemini
            $result = Gemini::generativeModel(model: 'gemini-flash-latest')
                ->generateContent($prompt);

            $html = $result->text();
            
            // Nettoyage du code si l'IA ajoute des balises ```html
            $html = preg_replace('/```(?:html)?\s*/', '', $html);
            $html = preg_replace('/```$/', '', $html);
            $html = trim($html);

            // Extraire les différentes parties
            $extracted = $this->extractHtmlAndCss($html);
            
            // Préparer les métadonnées
            $metadata = $this->generateMetadata($html, $request->prompt);

            // Sauvegarder le template automatiquement
            $template = null;
            if (Auth::check()) {
                $template = $this->saveGeneratedTemplate(
                    $extracted['html_content'],
                    $extracted['css_content'],
                    $request->prompt,
                    $metadata,
                    $request->template_name
                );
            }

            return response()->json([
                'success' => true,
                'html' => $html,
                'extracted_html' => $extracted['html_content'],
                'extracted_css' => $extracted['css_content'],
                'metadata' => $metadata,
                'template' => $template ? [
                    'id' => $template->id,
                    'name' => $template->name,
                    'description' => $template->description
                ] : null,
                'message' => $template ? 'Template généré et sauvegardé avec succès!' : 'Contenu généré avec succès!'
            ]);

        } catch (\Exception $e) {
            \Log::error('Gemini generation error: ' . $e->getMessage(), [
                'prompt' => $request->prompt,
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Erreur lors de la génération: ' . $e->getMessage(),
                'html' => $this->getFallbackHtml($request->prompt)
            ], 500);
        }
    }

    /**
     * Extraire le HTML et CSS séparément
     */
    private function extractHtmlAndCss(string $html): array
    {
        // Initialiser les variables
        $htmlContent = '';
        $cssContent = '';

        // Chercher le contenu CSS dans les balises <style>
        if (preg_match('/<style[^>]*>(.*?)<\/style>/s', $html, $styleMatches)) {
            $cssContent = trim($styleMatches[1]);
            
            // Supprimer le CSS de l'HTML original pour le HTML content
            $htmlWithoutStyle = preg_replace('/<style[^>]*>.*?<\/style>/s', '', $html);
            $htmlContent = $this->extractBodyContent($htmlWithoutStyle);
        } else {
            // Si pas de balise <style>, prendre tout comme HTML
            $htmlContent = $this->extractBodyContent($html);
        }

        // Si pas de CSS trouvé mais qu'il y a du style inline, l'extraire
        if (empty($cssContent) && strpos($html, 'style="') !== false) {
            // Extraire tous les styles inline pour référence
            $cssContent = "/* Styles inline extraits du HTML */\n" . 
                         $this->extractInlineStyles($html);
        }

        return [
            'html_content' => trim($htmlContent),
            'css_content' => trim($cssContent)
        ];
    }

    /**
     * Extraire uniquement le contenu entre <body> et </body>
     */
    private function extractBodyContent(string $html): string
    {
        // Chercher le contenu entre <body> et </body>
        if (preg_match('/<body[^>]*>(.*?)<\/body>/s', $html, $bodyMatches)) {
            return trim($bodyMatches[1]);
        }
        
        // Si pas de balise body, chercher directement après <html>
        if (preg_match('/<html[^>]*>(.*?)<\/html>/s', $html, $htmlMatches)) {
            // Supprimer les balises head et style si présentes
            $content = preg_replace('/<head[^>]*>.*?<\/head>/s', '', $htmlMatches[1]);
            $content = preg_replace('/<style[^>]*>.*?<\/style>/s', '', $content);
            return trim($content);
        }
        
        // Sinon, retourner l'HTML tel quel
        return trim($html);
    }

    /**
     * Extraire les styles inline pour référence
     */
    private function extractInlineStyles(string $html): string
    {
        $inlineStyles = '';
        
        if (preg_match_all('/style="([^"]*)"/', $html, $matches)) {
            foreach ($matches[1] as $index => $style) {
                $inlineStyles .= "/* Élément $index */\n";
                $inlineStyles .= ".element-$index {\n";
                $rules = explode(';', $style);
                foreach ($rules as $rule) {
                    if (!empty(trim($rule))) {
                        $inlineStyles .= "  " . trim($rule) . ";\n";
                    }
                }
                $inlineStyles .= "}\n\n";
            }
        }
        
        return $inlineStyles;
    }

    /**
     * Générer les métadonnées du template
     */
    private function generateMetadata(string $html, string $prompt): array
    {
        $metadata = [
            'generated_from' => 'gemini-ai',
            'prompt_used' => substr($prompt, 0, 500),
            'generated_at' => now()->toIso8601String(),
            'html_analysis' => [],
            'cdn_links' => [],
            'meta_tags' => []
        ];

        // Analyser l'HTML pour détecter les CDNs
        $this->detectCDNs($html, $metadata);
        
        // Extraire les meta tags
        $this->extractMetaTags($html, $metadata);
        
        // Analyser la structure HTML
        $this->analyzeHtmlStructure($html, $metadata);
        
        return $metadata;
    }

    /**
     * Détecter les liens CDN dans l'HTML
     */
    private function detectCDNs(string $html, array &$metadata): void
    {
        $patterns = [
            'tailwind' => '/https?:\/\/(cdn\.tailwindcss\.com|unpkg\.com\/tailwindcss)/i',
            'bootstrap' => '/https?:\/\/(cdn\.jsdelivr\.net\/npm\/bootstrap|stackpath\.bootstrapcdn\.com)/i',
            'fontawesome' => '/https?:\/\/(cdnjs\.cloudflare\.com\/ajax\/libs\/font-awesome|kit\.fontawesome\.com)/i',
            'google_fonts' => '/https?:\/\/fonts\.googleapis\.com/i',
            'jquery' => '/https?:\/\/(code\.jquery\.com|cdnjs\.cloudflare\.com\/ajax\/libs\/jquery)/i'
        ];

        foreach ($patterns as $type => $pattern) {
            if (preg_match($pattern, $html)) {
                $metadata['cdn_links'][$type] = true;
            }
        }
    }

    /**
     * Extraire les meta tags
     */
    private function extractMetaTags(string $html, array &$metadata): void
    {
        if (preg_match_all('/<meta[^>]+>/', $html, $matches)) {
            foreach ($matches[0] as $metaTag) {
                if (preg_match('/name="([^"]+)"/', $metaTag, $nameMatch)) {
                    $name = $nameMatch[1];
                    if (preg_match('/content="([^"]+)"/', $metaTag, $contentMatch)) {
                        $metadata['meta_tags'][$name] = $contentMatch[1];
                    }
                } elseif (preg_match('/property="([^"]+)"/', $metaTag, $propMatch)) {
                    $property = $propMatch[1];
                    if (preg_match('/content="([^"]+)"/', $metaTag, $contentMatch)) {
                        $metadata['meta_tags'][$property] = $contentMatch[1];
                    }
                }
            }
        }
    }

    /**
     * Analyser la structure HTML
     */
    private function analyzeHtmlStructure(string $html, array &$metadata): void
    {
        $analysis = [
            'has_header' => strpos($html, '<header') !== false || strpos($html, '<nav') !== false,
            'has_footer' => strpos($html, '<footer') !== false,
            'has_hero' => strpos($html, 'hero') !== false || preg_match('/class="[^"]*hero[^"]*"/', $html),
            'has_forms' => preg_match_all('/<form[^>]*>/', $html, $formMatches) > 0,
            'has_images' => preg_match_all('/<img[^>]*>/', $html, $imageMatches) > 0,
            'has_buttons' => preg_match_all('/<button[^>]*>|<a[^>]*class="[^"]*btn[^"]*"[^>]*>/', $html, $buttonMatches) > 0,
            'sections_count' => preg_match_all('/<section[^>]*>/', $html, $sectionMatches),
            'divs_count' => preg_match_all('/<div[^>]*>/', $html, $divMatches),
            'estimated_complexity' => 'medium'
        ];

        // Calculer la complexité estimée
        $totalElements = $analysis['sections_count'] + $analysis['divs_count'];
        if ($totalElements < 10) {
            $analysis['estimated_complexity'] = 'simple';
        } elseif ($totalElements > 30) {
            $analysis['estimated_complexity'] = 'complex';
        }

        $metadata['html_analysis'] = $analysis;
    }

    /**
     * Sauvegarder le template généré
     */
    private function saveGeneratedTemplate(
        string $htmlContent, 
        string $cssContent, 
        string $prompt, 
        array $metadata,
        ?string $customName = null
    ): ?Template {
        try {
            // Générer un nom basé sur le prompt ou utiliser le nom personnalisé
            $name = $customName ?: 'Généré IA: ' . Str::limit($prompt, 50);
            
            // Créer une description
            $description = "Template généré automatiquement par IA.\nPrompt: " . 
                          Str::limit($prompt, 200);
            
            $template = Template::create([
                'name' => $name,
                'description' => $description,
                'html_content' => $htmlContent,
                'css_content' => $cssContent,
                'thumbnail' => null,
                'metadata' => json_encode($metadata, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE),
                'user_id' => Auth::id()
            ]);

            \Log::info('Template généré par IA sauvegardé', [
                'template_id' => $template->id,
                'user_id' => Auth::id(),
                'template_name' => $name
            ]);

            return $template;

        } catch (\Exception $e) {
            \Log::error('Erreur sauvegarde template IA: ' . $e->getMessage(), [
                'prompt' => Str::limit($prompt, 100),
                'user_id' => Auth::id()
            ]);
            return null;
        }
    }

    /**
     * HTML de fallback en cas d'erreur
     */
    private function getFallbackHtml(string $prompt): string
    {
        // Même fallback HTML que précédemment
        $fallbackHtml = <<<HTML
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page générée</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            padding: 40px;
            max-width: 800px;
            width: 100%;
            text-align: center;
        }
        
        h1 {
            color: #333;
            margin-bottom: 20px;
            font-size: 2.5rem;
            background: linear-gradient(135deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .prompt {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin: 30px 0;
            border-left: 5px solid #667eea;
            text-align: left;
        }
        
        .prompt h3 {
            color: #555;
            margin-bottom: 10px;
        }
        
        .prompt p {
            color: #666;
            line-height: 1.6;
        }
        
        .features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin: 40px 0;
        }
        
        .feature {
            background: #f8f9fa;
            padding: 25px;
            border-radius: 10px;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        
        .feature:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }
        
        .feature i {
            font-size: 2.5rem;
            color: #667eea;
            margin-bottom: 15px;
        }
        
        .feature h4 {
            color: #333;
            margin-bottom: 10px;
        }
        
        .feature p {
            color: #666;
            font-size: 0.9rem;
        }
        
        .cta-button {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border: none;
            padding: 15px 40px;
            font-size: 1.1rem;
            border-radius: 50px;
            cursor: pointer;
            margin-top: 20px;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        
        .cta-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.4);
        }
        
        .note {
            margin-top: 30px;
            padding: 15px;
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 8px;
            color: #856404;
            font-size: 0.9rem;
        }
        
        @media (max-width: 768px) {
            .container {
                padding: 20px;
            }
            
            h1 {
                font-size: 2rem;
            }
            
            .features {
                grid-template-columns: 1fr;
            }
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="container">
        <h1><i class="fas fa-magic"></i> Page Générée</h1>
        
        <div class="prompt">
            <h3>Votre demande :</h3>
            <p>{$prompt}</p>
        </div>
        
        <div class="features">
            <div class="feature">
                <i class="fas fa-paint-brush"></i>
                <h4>Design Moderne</h4>
                <p>Interface utilisateur élégante et responsive</p>
            </div>
            <div class="feature">
                <i class="fas fa-mobile-alt"></i>
                <h4>Mobile First</h4>
                <p>Adapté à tous les appareils</p>
            </div>
            <div class="feature">
                <i class="fas fa-bolt"></i>
                <h4>Performance</h4>
                <p>Chargement rapide et optimisé</p>
            </div>
        </div>
        
        <button class="cta-button" onclick="window.location.reload()">
            <i class="fas fa-redo"></i> Réessayer la génération
        </button>
        
        <div class="note">
            <i class="fas fa-info-circle"></i>
            <strong>Note :</strong> Ceci est une page de secours. L'IA n'a pas pu générer le contenu demandé.
            Vous pouvez réessayer ou modifier votre prompt.
        </div>
    </div>
</body>
</html>
HTML;

        return $fallbackHtml;
    }

    /**
     * Lister les templates générés par l'utilisateur
     */
    public function listTemplates()
    {
        try {
            $templates = Template::forUser(Auth::id())
                ->orderBy('created_at', 'desc')
                ->get(['id', 'name', 'description', 'thumbnail', 'created_at', 'metadata']);

            return response()->json([
                'success' => true,
                'templates' => $templates
            ]);

        } catch (\Exception $e) {
            \Log::error('Erreur liste templates: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'error' => 'Erreur lors de la récupération des templates'
            ], 500);
        }
    }

    /**
     * Supprimer un template généré
     */
    public function deleteTemplate($id)
    {
        try {
            $template = Template::forUser(Auth::id())->findOrFail($id);
            $template->delete();

            \Log::info('Template supprimé', [
                'template_id' => $id,
                'user_id' => Auth::id()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Template supprimé avec succès'
            ]);

        } catch (\Exception $e) {
            \Log::error('Erreur suppression template: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'error' => 'Template non trouvé ou erreur de suppression'
            ], 404);
        }
    }
}