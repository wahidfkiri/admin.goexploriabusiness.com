<?php
// app/Services/GeminiGeneratorService.php

namespace App\Services;

use App\Models\Template;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Exception;

class GeminiGeneratorService
{
    private $apiKey = 'AIzaSyA3pQ1fSTPI9k8JQQp4u3da3HJu8ndqv3s';
    private $model = 'gemini-2.5-pro';
    private $baseUrl = 'https://generativelanguage.googleapis.com/v1beta/models/';
    
    public function __construct()
    {
        //$this->apiKey = config('gemini.api_key');
        
        if (empty($this->apiKey)) {
            throw new Exception('Clé API Gemini non configurée. Ajoutez GEMINI_API_KEY dans .env');
        }
    }
    
    /**
     * Générer HTML/CSS avec Gemini
     */
    public function generateFromPrompt(string $prompt, array $options = [])
    {
       // try {
            // Appeler Gemini API
          return  $response = $this->callGeminiAPI($prompt, $options);die;
            
            // Parser la réponse
            $parsed = $this->parseGeminiResponse($response);
            
            // Créer le template
           // return $this->createTemplate($prompt, $parsed['html'], $parsed['css'], $options);
            
       // } catch (Exception $e) {
          //  Log::error('Gemini generation failed: ' . $e->getMessage());
           // return $this->createFallbackTemplate($prompt, $options);
      //  }
    }
    
    /**
     * Appeler l'API Gemini
     */
    private function callGeminiAPI(string $prompt, array $options): array
    {
        $url = $this->baseUrl . $this->model . ':generateContent?key=' . $this->apiKey;
        
        // Construire le prompt pour Gemini
        $geminiPrompt = $this->buildGeminiPrompt($prompt, $options);
        
        $response = Http::timeout(60)->post($url, [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $geminiPrompt]
                    ]
                ]
            ],
            'generationConfig' => [
                'temperature' => 0.7,
                'maxOutputTokens' => 2000,
                'topP' => 0.8,
                'topK' => 40,
            ],
            'safetySettings' => [
                [
                    'category' => 'HARM_CATEGORY_HARASSMENT',
                    'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'
                ],
                [
                    'category' => 'HARM_CATEGORY_HATE_SPEECH',
                    'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'
                ],
                [
                    'category' => 'HARM_CATEGORY_SEXUALLY_EXPLICIT',
                    'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'
                ],
                [
                    'category' => 'HARM_CATEGORY_DANGEROUS_CONTENT',
                    'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'
                ]
            ]
        ]);
        
        if (!$response->successful()) {
            throw new Exception('Gemini API error: ' . $response->body());
        }
        
        return $response->json();
    }
    
    /**
     * Construire le prompt pour Gemini
     */
    private function buildGeminiPrompt(string $prompt, array $options): string
    {
        $fullPrompt = "Tu es un expert développeur frontend HTML/CSS. 
        Génère un template HTML et CSS basé sur cette description:
        
        DESCRIPTION: {$prompt}
        
        ";
        
        if (!empty($options['style'])) {
            $fullPrompt .= "STYLE: {$options['style']}\n";
        }
        
        if (!empty($options['layout'])) {
            $fullPrompt .= "LAYOUT: {$options['layout']}\n";
        }
        
        $fullPrompt .= "
        
        FORMAT DE RÉPONSE EXACT:
        [HTML]
        <!-- Code HTML ici -->
        [/HTML]
        
        [CSS]
        /* Code CSS ici */
        [/CSS]
        
        RÈGLES:
        1. Retourne SEULEMENT le code dans le format ci-dessus
        2. Pas d'explications, pas de texte supplémentaire
        3. HTML5 valide avec structure complète
        4. CSS3 moderne et responsive
        5. Design mobile-friendly
        6. Code propre avec commentaires";
        
        return $fullPrompt;
    }
    
    /**
     * Parser la réponse Gemini
     */
    private function parseGeminiResponse(array $response): array
    {
        if (!isset($response['candidates'][0]['content']['parts'][0]['text'])) {
            throw new Exception('Réponse Gemini invalide');
        }
        
        $content = $response['candidates'][0]['content']['parts'][0]['text'];
        
        $html = '';
        $css = '';
        
        // Format: [HTML]...[/HTML][CSS]...[/CSS]
        if (preg_match('/\[HTML\](.*?)\[\/HTML\]/s', $content, $htmlMatches)) {
            $html = trim($htmlMatches[1]);
        }
        
        if (preg_match('/\[CSS\](.*?)\[\/CSS\]/s', $content, $cssMatches)) {
            $css = trim($cssMatches[1]);
        }
        
        // Fallback si format différent
        if (empty($html)) {
            // Chercher du HTML
            if (preg_match('/<html.*?>.*?<\/html>/is', $content, $matches)) {
                $html = $matches[0];
            } elseif (preg_match('/<body.*?>.*?<\/body>/is', $content, $matches)) {
                $html = '<!DOCTYPE html><html><body>' . $matches[0] . '</body></html>';
            } else {
                $html = $this->generateBasicHtml($content);
            }
        }
        
        if (empty($css)) {
            // Chercher du CSS
            if (preg_match('/<style.*?>(.*?)<\/style>/is', $content, $matches)) {
                $css = trim($matches[1]);
            } else {
                $css = $this->generateBasicCss();
            }
        }
        
        return [
            'html' => $this->cleanHtml($html),
            'css' => $this->cleanCss($css)
        ];
    }
    
    /**
     * Nettoyer HTML
     */
    private function cleanHtml(string $html): string
    {
        // S'assurer d'avoir une structure de base
        if (!str_contains($html, '<!DOCTYPE')) {
            $html = '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Template Généré</title>
</head>
<body>' . "\n" . $html . "\n</body>\n</html>";
        }
        
        return trim($html);
    }
    
    /**
     * Nettoyer CSS
     */
    private function cleanCss(string $css): string
    {
        // Supprimer balises style
        $css = preg_replace('/<style[^>]*>/i', '', $css);
        $css = preg_replace('/<\/style>/i', '', $css);
        
        return trim($css);
    }
    
    /**
     * HTML basique
     */
    private function generateBasicHtml(string $content): string
    {
        return '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Template Généré</title>
</head>
<body>
    <div class="container">
        <header>
            <h1>Template Généré</h1>
        </header>
        <main>
            <p>' . htmlspecialchars(substr($content, 0, 500)) . '</p>
        </main>
    </div>
</body>
</html>';
    }
    
    /**
     * CSS basique
     */
    private function generateBasicCss(): string
    {
        return '/* CSS de base */
* { margin: 0; padding: 0; box-sizing: border-box; }
body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
.container { max-width: 1200px; margin: 0 auto; padding: 20px; }
header { background: #4285f4; color: white; padding: 20px; text-align: center; }
main { padding: 20px; }
@media (max-width: 768px) { .container { padding: 10px; } }';
    }
    
    /**
     * Créer le template
     */
    private function createTemplate(string $prompt, string $html, string $css, array $options): Template
    {
        $name = $options['name'] ?? $this->generateName($prompt);
        
        return Template::create([
            'name' => $name,
            'user_id' => auth()->id() ?? 1,
            'url' => null,
            'html_content' => $html,
            'css_content' => $css,
          //  'thumbnail' => $this->generateThumbnail($name),
            // 'description' => [
            //     'generated_by' => 'gemini',
            //     'model' => $this->model,
            //     'original_prompt' => $prompt,
            //     'options' => $options,
            //     'generated_at' => now()->toDateTimeString(),
            // ],
        ]);
    }
    
    /**
     * Générer un nom
     */
    private function generateName(string $prompt): string
    {
        $words = explode(' ', $prompt);
        $firstWords = array_slice($words, 0, 4);
        $name = implode(' ', $firstWords);
        
        $name = preg_replace('/[^a-zA-Z0-9\s]/', '', $name);
        $name = trim($name);
        
        if (strlen($name) > 50) {
            $name = substr($name, 0, 50);
        }
        
        return $name ?: 'Template Gemini';
    }
    
    /**
     * Générer thumbnail
     */
    private function generateThumbnail(string $name): ?string
    {
        try {
            $initials = strtoupper(substr($name, 0, 2));
            $filename = 'gemini-' . Str::slug($name) . '-' . Str::random(8) . '.jpg';
            $path = 'templates/gemini/' . date('Y/m') . '/' . $filename;
            
            $url = "https://ui-avatars.com/api/?name={$initials}&background=4285f4&color=fff&size=400";
            $image = @file_get_contents($url);
            
            if ($image) {
                \Storage::disk('public')->put($path, $image);
                return $path;
            }
            
        } catch (Exception $e) {
            Log::warning('Thumbnail generation failed: ' . $e->getMessage());
        }
        
        return null;
    }
    
    /**
     * Template de secours
     */
    private function createFallbackTemplate(string $prompt, array $options): Template
    {
        $name = $options['name'] ?? $this->generateName($prompt) . ' (Fallback)';
        
        return Template::create([
            'name' => $name,
            'user_id' => auth()->id() ?? 1,
            'url' => null,
            'html_content' => $this->getFallbackHtml($prompt),
            'css_content' => $this->getFallbackCss(),
            'thumbnail' => null,
            // 'description' => array_merge($options, [
            //     'original_prompt' => $prompt,
            //     'generated_by' => 'fallback',
            //     'reason' => 'gemini_error',
            //     'generated_at' => now()->toDateTimeString(),
            // ]),
        ]);
    }
    
    /**
     * HTML de secours
     */
    private function getFallbackHtml(string $prompt): string
    {
        return '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Template Fallback</title>
</head>
<body>
    <div class="container">
        <h1>Template Généré</h1>
        <p>Description: ' . htmlspecialchars(substr($prompt, 0, 300)) . '</p>
        <div class="features">
            <div class="feature">HTML5 Valide</div>
            <div class="feature">CSS3 Moderne</div>
            <div class="feature">Design Responsive</div>
        </div>
    </div>
</body>
</html>';
    }
    
    /**
     * CSS de secours
     */
    private function getFallbackCss(): string
    {
        return '.container { max-width: 800px; margin: 0 auto; padding: 40px; }
h1 { color: #4285f4; margin-bottom: 20px; }
.features { display: flex; gap: 20px; margin-top: 30px; }
.feature { background: #f8f9fa; padding: 15px; border-radius: 8px; border-left: 4px solid #4285f4; }
@media (max-width: 600px) { .features { flex-direction: column; } }';
    }
    
    /**
     * Tester la connexion Gemini
     */
    public function testConnection(): array
    {
        try {
            $url = $this->baseUrl . $this->model . ':generateContent?key=' . $this->apiKey;
            
            $response = Http::timeout(30)->post($url, [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => 'Réponds "OK"']
                        ]
                    ]
                ],
                'generationConfig' => [
                    'maxOutputTokens' => 5,
                ]
            ]);
            
            if ($response->successful()) {
                return [
                    'success' => true,
                    'message' => 'Gemini API fonctionne',
                    'model' => $this->model
                ];
            }
            
            return [
                'success' => false,
                'message' => 'Erreur: ' . $response->body()
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
}