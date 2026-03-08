<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class OpenAIService
{
    private $model;
    private $maxTokens;
    private $temperature;
    private $useMockData;
    
    public function __construct()
    {
        $this->model = config('openai.models.default', 'gpt-3.5-turbo');
        $this->maxTokens = config("openai.models.{$this->model}.max_tokens", 2000);
        $this->temperature = config("openai.models.{$this->model}.temperature", 0.7);
        $this->useMockData = !config('openai.api_key') || config('openai.use_mock', false);
    }
    
    /**
     * Générer un composant HTML/CSS avec OpenAI
     */
    public function generateComponent(string $type, string $prompt, string $style = 'modern'): array
    {
        $cacheKey = $this->getCacheKey($type, $prompt, $style);
        
        // Vérifier le cache
        if (config('openai.cache.enabled')) {
            $cached = Cache::get($cacheKey);
            if ($cached) {
                Log::info('OpenAI cache hit', ['key' => $cacheKey]);
                return $cached;
            }
        }
        
        // Use mock data if no API key or forced
        if ($this->useMockData) {
            Log::info('Using mock data for generateComponent', ['type' => $type, 'prompt' => $prompt]);
            return $this->getMockComponent($type, $prompt, $style);
        }
        
        try {
            // Initialize OpenAI client
            $client = $this->initializeOpenAIClient();
            if (!$client) {
                throw new \Exception('OpenAI client initialization failed');
            }
            
            $systemPrompt = $this->getSystemPrompt($type, $style);
            $userPrompt = $this->getUserPrompt($type, $prompt, $style);
            
            $response = $client->chat()->create([
                'model' => $this->model,
                'messages' => [
                    ['role' => 'system', 'content' => $systemPrompt],
                    ['role' => 'user', 'content' => $userPrompt]
                ],
                'max_tokens' => $this->maxTokens,
                'temperature' => $this->temperature,
                'response_format' => ['type' => 'json_object'],
            ]);
            
            // Check if response has choices
            if (!isset($response->choices) || empty($response->choices)) {
                throw new \Exception('No choices in OpenAI response');
            }
            
            $content = $response->choices[0]->message->content;
            $result = json_decode($content, true);
            
            if (!$result) {
                throw new \Exception('Invalid JSON response from OpenAI');
            }
            
            // Valider la réponse
            $validated = $this->validateResponse($result);
            
            // Mettre en cache
            if (config('openai.cache.enabled')) {
                Cache::put($cacheKey, $validated, config('openai.cache.ttl'));
                Log::info('OpenAI response cached', ['key' => $cacheKey]);
            }
            
            // Loguer l'utilisation
            $this->logUsage($response->usage);
            
            return $validated;
            
        } catch (\Exception $e) {
            Log::error('OpenAI generation failed', [
                'error' => $e->getMessage(),
                'type' => $type,
                'prompt' => $prompt
            ]);
            
            return $this->getMockComponent($type, $prompt, $style);
        }
    }
    
    /**
     * Optimiser le HTML/CSS existant
     */
    public function optimizeDesign(string $html, string $css = ''): array
    {
        $cacheKey = 'openai_optimize_' . md5($html . $css);
        
        if (config('openai.cache.enabled') && $cached = Cache::get($cacheKey)) {
            return $cached;
        }
        
        // Use mock data if no API key
        if ($this->useMockData) {
            Log::info('Using mock data for optimizeDesign');
            return $this->getMockOptimization($html, $css);
        }
        
        $systemPrompt = "You are an expert web designer and developer. Analyze the given HTML/CSS and provide optimized code with improvements.";
        
        $userPrompt = "Optimize this web design for performance, accessibility, and modern standards.\n\nHTML:\n{$html}\n\nCSS:\n{$css}\n\nProvide optimized HTML and CSS separately with explanations of improvements.";
        
        try {
            $client = $this->initializeOpenAIClient();
            if (!$client) {
                throw new \Exception('OpenAI client initialization failed');
            }
            
            $response = $client->chat()->create([
                'model' => $this->model,
                'messages' => [
                    ['role' => 'system', 'content' => $systemPrompt],
                    ['role' => 'user', 'content' => $userPrompt]
                ],
                'max_tokens' => 3000,
                'temperature' => 0.5,
                'response_format' => ['type' => 'json_object'],
            ]);
            
            if (!isset($response->choices) || empty($response->choices)) {
                throw new \Exception('No choices in OpenAI response');
            }
            
            $result = json_decode($response->choices[0]->message->content, true);
            
            if (!$result) {
                throw new \Exception('Invalid JSON response from OpenAI');
            }
            
            if (config('openai.cache.enabled')) {
                Cache::put($cacheKey, $result, config('openai.cache.ttl'));
            }
            
            $this->logUsage($response->usage);
            
            return $result;
            
        } catch (\Exception $e) {
            Log::error('OpenAI optimization failed', ['error' => $e->getMessage()]);
            return $this->getMockOptimization($html, $css);
        }
    }
    
    /**
     * Chat conversationnel
     */
    public function chat(array $messages): array
    {
        // Use mock data if no API key
        if ($this->useMockData) {
            Log::info('Using mock data for chat', ['message_count' => count($messages)]);
            return $this->getMockChatResponse($messages);
        }
        
        try {
            $client = $this->initializeOpenAIClient();
            if (!$client) {
                throw new \Exception('OpenAI client initialization failed');
            }
            
            $response = $client->chat()->create([
                'model' => $this->model,
                'messages' => array_merge(
                    [[
                        'role' => 'system', 
                        'content' => 'You are a helpful web design assistant specialized in HTML, CSS, and modern web development. Provide practical, implementable advice.'
                    ]],
                    $messages
                ),
                'max_tokens' => 1000,
                'temperature' => 0.7,
            ]);
            
            if (!isset($response->choices) || empty($response->choices)) {
                throw new \Exception('No choices in OpenAI response');
            }
            
            $this->logUsage($response->usage);
            
            return [
                'response' => $response->choices[0]->message->content,
                'suggestions' => $this->extractSuggestions($response->choices[0]->message->content)
            ];
            
        } catch (\Exception $e) {
            Log::error('OpenAI chat failed', ['error' => $e->getMessage()]);
            return $this->getMockChatResponse($messages);
        }
    }
    
    /**
     * Initialize OpenAI client with error handling
     */
    private function initializeOpenAIClient()
    {
        try {
            $apiKey = config('openai.api_key');
            
            if (!$apiKey) {
                Log::warning('OpenAI API key not configured');
                return null;
            }
            
            // Remove any invalid characters or truncation indicators
            $apiKey = trim($apiKey);
            $apiKey = preg_replace('/\*+/', '', $apiKey); // Remove asterisks
            $apiKey = preg_replace('/sk-proj\*/', '', $apiKey); // Clean truncated keys
            
            if (strlen($apiKey) < 20) {
                Log::warning('OpenAI API key appears to be truncated or invalid', ['length' => strlen($apiKey)]);
                return null;
            }
            
            return \OpenAI::factory()
                ->withApiKey($apiKey)
                ->withHttpClient(new \GuzzleHttp\Client(['timeout' => 30]))
                ->make();
                
        } catch (\Exception $e) {
            Log::error('Failed to initialize OpenAI client', ['error' => $e->getMessage()]);
            return null;
        }
    }
    
    /**
     * Mock data for generateComponent
     */
    private function getMockComponent(string $type, string $prompt, string $style): array
    {
        $mockComponents = [
            'hero' => [
                'html' => '<section class="hero-section" style="padding: 100px 20px; text-align: center; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                    <div class="container">
                        <h1 class="hero-title">' . ($prompt ?: 'Modern Hero Section') . '</h1>
                        <p class="hero-subtitle">A beautifully designed hero section with ' . $style . ' styling</p>
                        <div class="hero-buttons" style="margin-top: 30px;">
                            <button class="btn btn-primary" style="padding: 12px 30px; background: white; color: #667eea; border: none; border-radius: 5px; font-weight: bold; margin-right: 10px;">Get Started</button>
                            <button class="btn btn-secondary" style="padding: 12px 30px; background: transparent; color: white; border: 2px solid white; border-radius: 5px;">Learn More</button>
                        </div>
                    </div>
                </section>',
                'css' => '.hero-section {
                    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
                }
                .hero-title {
                    font-size: 3.5rem;
                    font-weight: 800;
                    margin-bottom: 20px;
                    line-height: 1.2;
                }
                .hero-subtitle {
                    font-size: 1.25rem;
                    max-width: 600px;
                    margin: 0 auto 30px;
                    opacity: 0.9;
                }
                @media (max-width: 768px) {
                    .hero-title { font-size: 2.5rem; }
                    .hero-subtitle { font-size: 1.1rem; }
                    .hero-buttons {
                        flex-direction: column;
                        gap: 15px;
                    }
                    .hero-buttons button {
                        width: 100%;
                        margin: 5px 0;
                    }
                }',
                'suggestions' => [
                    'Add animation on scroll',
                    'Include a background video option',
                    'Add social proof elements',
                    'Optimize for mobile touch interactions',
                    'Consider adding a newsletter signup form'
                ]
            ],
            'features' => [
                'html' => '<section class="features-section" style="padding: 80px 20px; background: #f8f9fa;">
                    <div class="container">
                        <h2 class="section-title" style="text-align: center; margin-bottom: 50px; color: #333;">' . ($prompt ?: 'Key Features') . '</h2>
                        <div class="features-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px;">
                            <div class="feature-card" style="background: white; padding: 30px; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
                                <div class="feature-icon" style="width: 60px; height: 60px; background: #667eea; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-bottom: 20px; color: white; font-size: 24px;">✓</div>
                                <h3 style="margin-bottom: 15px; color: #333;">Feature One</h3>
                                <p style="color: #666; line-height: 1.6;">Description of the first important feature with benefits explained clearly.</p>
                            </div>
                            <div class="feature-card" style="background: white; padding: 30px; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
                                <div class="feature-icon" style="width: 60px; height: 60px; background: #764ba2; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-bottom: 20px; color: white; font-size: 24px;">⚡</div>
                                <h3 style="margin-bottom: 15px; color: #333;">Feature Two</h3>
                                <p style="color: #666; line-height: 1.6;">Second feature description highlighting unique value proposition.</p>
                            </div>
                            <div class="feature-card" style="background: white; padding: 30px; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
                                <div class="feature-icon" style="width: 60px; height: 60px; background: #f093fb; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-bottom: 20px; color: white; font-size: 24px;">🔒</div>
                                <h3 style="margin-bottom: 15px; color: #333;">Feature Three</h3>
                                <p style="color: #666; line-height: 1.6;">Third feature focusing on security, reliability, or performance aspects.</p>
                            </div>
                        </div>
                    </div>
                </section>',
                'css' => '.features-section {
                    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
                }
                .features-grid {
                    max-width: 1200px;
                    margin: 0 auto;
                }
                .feature-card {
                    transition: transform 0.3s ease, box-shadow 0.3s ease;
                }
                .feature-card:hover {
                    transform: translateY(-5px);
                    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
                }
                .section-title {
                    font-size: 2.5rem;
                    font-weight: 700;
                }
                @media (max-width: 768px) {
                    .features-grid {
                        grid-template-columns: 1fr;
                        padding: 0 15px;
                    }
                    .section-title {
                        font-size: 2rem;
                    }
                }',
                'suggestions' => [
                    'Add hover animations to feature cards',
                    'Include statistics or metrics',
                    'Add customer testimonials',
                    'Implement lazy loading for images',
                    'Consider adding a comparison table'
                ]
            ],
            'custom' => [
                'html' => '<section class="custom-component" style="padding: 60px 20px; text-align: center;">
                    <div class="container">
                        <h2>' . ($prompt ?: 'Custom Component') . '</h2>
                        <p>This is a mock ' . $style . ' style component generated for testing purposes.</p>
                        <div style="margin-top: 30px; padding: 20px; background: #f5f5f5; border-radius: 8px;">
                            <p>Prompt: "' . htmlspecialchars($prompt) . '"</p>
                            <p>Style: ' . $style . '</p>
                            <p>Type: ' . $type . '</p>
                        </div>
                    </div>
                </section>',
                'css' => '.custom-component {
                    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
                }
                .custom-component h2 {
                    font-size: 2rem;
                    color: #333;
                    margin-bottom: 20px;
                }
                .custom-component p {
                    color: #666;
                    line-height: 1.6;
                    max-width: 800px;
                    margin: 0 auto;
                }',
                'suggestions' => [
                    'Customize colors to match brand',
                    'Add interactive elements',
                    'Optimize for mobile devices',
                    'Include accessibility features',
                    'Add animation effects'
                ]
            ]
        ];
        
        return $mockComponents[$type] ?? $mockComponents['custom'];
    }
    
    /**
     * Mock data for optimizeDesign
     */
    private function getMockOptimization(string $html, string $css): array
    {
        return [
            'html' => $this->cleanHTML($html),
            'css' => $this->cleanCSS($css),
            'improvements' => [
                'Minified HTML and CSS',
                'Added semantic HTML tags',
                'Improved accessibility with ARIA labels',
                'Optimized for mobile responsiveness',
                'Reduced unused CSS selectors'
            ],
            'performance_metrics' => [
                'estimated_savings' => '15-20%',
                'accessibility_score' => 'Improved',
                'mobile_score' => 'Enhanced'
            ]
        ];
    }
    
    /**
     * Mock data for chat
     */
    private function getMockChatResponse(array $messages): array
    {
        $lastUserMessage = '';
        foreach (array_reverse($messages) as $message) {
            if ($message['role'] === 'user') {
                $lastUserMessage = $message['content'];
                break;
            }
        }
        
        $responses = [
            'hello' => 'Hello! I\'m your web design assistant. How can I help you with HTML, CSS, or JavaScript today?',
            'help' => 'I can help you with:\n1. HTML structure and semantics\n2. CSS styling and layouts\n3. Responsive design\n4. Web performance\n5. Accessibility best practices\n\nWhat specific topic would you like to discuss?',
            'html' => 'HTML is the foundation of web pages. Use semantic elements like <header>, <nav>, <main>, <section>, <article>, and <footer> for better accessibility and SEO.',
            'css' => 'CSS controls presentation. Modern techniques include:\n- Flexbox for 1D layouts\n- CSS Grid for 2D layouts\n- Custom properties (CSS variables)\n- Responsive design with media queries',
            'responsive' => 'For responsive design:\n1. Use viewport meta tag\n2. Implement mobile-first approach\n3. Use relative units (rem, %)\n4. Test on multiple devices\n5. Use CSS Grid and Flexbox',
            '' => 'I\'m here to help with web design and development. Ask me anything about HTML, CSS, JavaScript, or modern web practices!'
        ];
        
        $response = $responses[''];
        foreach ($responses as $key => $value) {
            if ($key && stripos($lastUserMessage, $key) !== false) {
                $response = $value;
                break;
            }
        }
        
        return [
            'response' => $response,
            'suggestions' => [
                'Learn about CSS Grid',
                'Practice responsive design',
                'Study accessibility guidelines',
                'Explore modern JavaScript',
                'Build a sample project'
            ],
            'note' => 'This is a mock response. Configure OpenAI API key for real AI responses.'
        ];
    }
    
    /**
     * The rest of your methods remain the same...
     * (getSystemPrompt, getUserPrompt, validateResponse, cleanHTML, cleanCSS, 
     * logUsage, calculateCost, getCacheKey, extractSuggestions, etc.)
     */
    
    // ... [Keep all your existing methods below unchanged] ...

    
    /**
     * Générer plusieurs options de design
     */
    public function generateVariations(string $basePrompt, int $variations = 3): array
    {
        $results = [];
        
        for ($i = 0; $i < $variations; $i++) {
            $variationPrompt = "{$basePrompt}\n\nCreate variation #" . ($i + 1) . " with a different design approach.";
            
            $results[] = $this->generateComponent('custom', $variationPrompt);
            
            // Petite pause pour éviter rate limits
            if ($i < $variations - 1) {
                usleep(500000); // 0.5 second
            }
        }
        
        return $results;
    }
    
    /**
     * Obtenir les prompts optimisés
     */
    private function getSystemPrompt(string $type, string $style): string
    {
        $prompts = [
            'hero' => "You are an expert web designer specializing in hero sections. Create beautiful, conversion-optimized hero sections in {$style} style. Return JSON with html, css, and suggestions.",
            'features' => "You are a UX/UI expert for features sections. Design engaging feature grids in {$style} style that highlight benefits effectively.",
            'contact' => "You are a web form specialist. Create user-friendly, accessible contact forms in {$style} style with modern validation.",
            'pricing' => "You are a conversion rate optimization expert. Design persuasive pricing tables in {$style} style that drive sales.",
            'testimonials' => "You are a social proof design expert. Create trustworthy testimonial sections in {$style} style.",
            'team' => "You are a team section design expert. Create engaging team member displays in {$style} style.",
            'custom' => "You are a versatile web designer. Create the requested component in {$style} style following modern best practices.",
        ];
        
        $default = "You are a professional web designer. Create high-quality web components in {$style} style. Always return valid JSON with html, css, and suggestions fields.";
        
        return $prompts[$type] ?? $default;
    }
    
    private function getUserPrompt(string $type, string $prompt, string $style): string
    {
        $base = $prompt ?: "Create a {$type} section";
        
        return "{$base}\n\nStyle: {$style}\n\nRequirements:
        1. Return valid JSON with these exact keys: html, css, suggestions
        2. HTML must be clean, semantic, and accessible
        3. CSS must be modern with comments
        4. Include 3-5 practical suggestions for improvement
        5. Make it responsive and mobile-friendly
        6. Use modern CSS (Flexbox/Grid)
        7. Ensure good color contrast and typography
        
        Example response format:
        {
            \"html\": \"<section class='hero'>...</section>\",
            \"css\": \".hero { color: #fff; }\",
            \"suggestions\": [\"Add animation\", \"Improve contrast\"]
        }";
    }
    
    /**
     * Valider et nettoyer la réponse OpenAI
     */
    private function validateResponse(array $response): array
    {
        $defaults = [
            'html' => '<div class="ai-generated">Generated content</div>',
            'css' => '.ai-generated { padding: 20px; }',
            'suggestions' => ['Customize colors', 'Add responsiveness']
        ];
        
        // Nettoyer le HTML
        if (isset($response['html'])) {
            $response['html'] = $this->cleanHTML($response['html']);
        }
        
        // Nettoyer le CSS
        if (isset($response['css'])) {
            $response['css'] = $this->cleanCSS($response['css']);
        }
        
        // Assurer les suggestions sont un tableau
        if (isset($response['suggestions']) && is_string($response['suggestions'])) {
            $response['suggestions'] = explode("\n", $response['suggestions']);
        }
        
        return array_merge($defaults, array_filter($response));
    }
    
    private function cleanHTML(string $html): string
    {
        // Supprimer les backticks de code
        $html = preg_replace('/```(html|json)?\n?/', '', $html);
        $html = preg_replace('/\n?```/', '', $html);
        
        // Supprimer les balises script dangereuses
        $html = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', '', $html);
        
        // Échapper les caractères spéciaux
        $html = htmlspecialchars_decode($html, ENT_QUOTES);
        
        return trim($html);
    }
    
    private function cleanCSS(string $css): string
    {
        // Supprimer les backticks
        $css = preg_replace('/```(css)?\n?/', '', $css);
        $css = preg_replace('/\n?```/', '', $css);
        
        // Minifier légèrement
        $css = preg_replace('/\s+/', ' ', $css);
        $css = preg_replace('/;\s+/', ';', $css);
        
        return trim($css);
    }
    
    /**
     * Loguer l'utilisation et les coûts
     */
    private function logUsage($usage): void
    {
        $cost = $this->calculateCost($usage);
        
        Log::info('OpenAI API Usage', [
            'prompt_tokens' => $usage->promptTokens,
            'completion_tokens' => $usage->completionTokens,
            'total_tokens' => $usage->totalTokens,
            'estimated_cost' => $cost,
            'model' => $this->model
        ]);
        
        // Stocker dans la base de données pour monitoring
        if (class_exists(\App\Models\AIUsage::class)) {
            \App\Models\AIUsage::create([
                'prompt_tokens' => $usage->promptTokens,
                'completion_tokens' => $usage->completionTokens,
                'total_tokens' => $usage->totalTokens,
                'estimated_cost' => $cost,
                'model' => $this->model,
                'user_id' => auth()->id() ?? null
            ]);
        }
    }
    
    private function calculateCost($usage): float
    {
        $modelConfig = config("openai.models.{$this->model}");
        $costPer1K = $modelConfig['cost_per_1k_tokens'] ?? 0.002;
        
        return ($usage->totalTokens / 1000) * $costPer1K;
    }
    
    private function getCacheKey(string $type, string $prompt, string $style): string
    {
        return 'openai_' . md5("{$type}_{$prompt}_{$style}");
    }
    
    private function getFallbackResponse(string $type, string $prompt, string $style): array
    {
        // Templates de fallback basiques
        $fallbacks = [
            'hero' => [
                'html' => '<section class="hero"><h1>Hero Section</h1><p>Fallback content</p></section>',
                'css' => '.hero { padding: 100px 20px; text-align: center; }',
                'suggestions' => ['Customize with AI when available']
            ],
            'features' => [
                'html' => '<section class="features"><div class="feature"><h3>Feature 1</h3><p>Description</p></div></section>',
                'css' => '.features { display: grid; gap: 20px; }',
                'suggestions' => ['Add more features', 'Customize styling']
            ],
            'contact' => [
                'html' => '<form class="contact-form"><input type="text" placeholder="Name"><button>Submit</button></form>',
                'css' => '.contact-form { max-width: 500px; margin: 0 auto; }',
                'suggestions' => ['Add validation', 'Style inputs']
            ],
            // Ajouter d'autres fallbacks...
        ];
        
        return $fallbacks[$type] ?? [
            'html' => '<div>Content will be generated here</div>',
            'css' => '',
            'suggestions' => []
        ];
    }
    
    private function extractSuggestions(string $content): array
    {
        // Extraire les suggestions du texte
        preg_match_all('/\d+\.\s*(.+?)(?=\n\d+\.|\n\n|$)/', $content, $matches);
        
        if (!empty($matches[1])) {
            return array_slice($matches[1], 0, 5);
        }
        
        // Fallback: diviser par lignes
        $lines = explode("\n", $content);
        return array_filter(array_slice($lines, 0, 5), function($line) {
            return strlen(trim($line)) > 10;
        });
    }
    
    /**
     * Get the OpenAI client instance
     */
    public function getClient(): Client
    {
        return $this->client;
    }
}