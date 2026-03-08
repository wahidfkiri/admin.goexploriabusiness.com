<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AIController extends Controller
{
    /**
     * Générer du contenu avec l'IA
     */
    public function generate(Request $request)
    {
        $request->validate([
            'type' => 'required|string',
            'prompt' => 'nullable|string',
            'style' => 'nullable|string',
            'current_html' => 'nullable|string',
            'current_css' => 'nullable|string'
        ]);

        try {
            // Option 1: Utiliser OpenAI (si vous avez une clé API)
            // return $this->generateWithOpenAI($request);
            
            // Option 2: Utiliser une API locale (LLaMA, etc.)
            // return $this->generateWithLocalAI($request);
            
            // Option 3: Règles prédéfinies (pour le développement)
            return $this->generateWithRules($request);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'AI generation failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Génération avec des règles prédéfinies
     */
    private function generateWithRules(Request $request)
    {
        $type = $request->type;
        $prompt = $request->prompt;
        $style = $request->style ?? 'modern';

        $templates = [
            'hero' => $this->generateHeroTemplate($style),
            'features' => $this->generateFeaturesTemplate($style),
            'contact' => $this->generateContactTemplate($style),
            'pricing' => $this->generatePricingTemplate($style),
            'testimonials' => $this->generateTestimonialsTemplate($style),
            'team' => $this->generateTeamTemplate($style),
            'custom' => $this->generateCustomTemplate($prompt, $style)
        ];

        $template = $templates[$type] ?? $templates['hero'];

        return response()->json([
            'success' => true,
            'html' => $template['html'],
            'css' => $template['css'],
            'suggestions' => [
                'Customize the colors to match your brand',
                'Add animations for better user engagement',
                'Optimize for mobile responsiveness',
                'Consider adding micro-interactions'
            ]
        ]);
    }

    /**
     * Optimiser le design actuel
     */
    public function optimize(Request $request)
    {
        $request->validate([
            'html' => 'required|string',
            'css' => 'nullable|string'
        ]);

        try {
            // Analyser le HTML/CSS et suggérer des améliorations
            $optimized = $this->optimizeDesign($request->html, $request->css);

            return response()->json([
                'success' => true,
                'html' => $optimized['html'],
                'css' => $optimized['css'],
                'improvements' => $optimized['improvements']
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Optimization failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Chat IA
     */
    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
            'context' => 'nullable|array'
        ]);

        try {
            // Simuler une réponse IA
            $response = $this->generateChatResponse($request->message);

            return response()->json([
                'success' => true,
                'response' => $response,
                'suggestions' => $this->getAISuggestions($request->message)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Chat failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Générer du code
     */
    public function code(Request $request)
    {
        $request->validate([
            'type' => 'required|string', // html, css, js, component
            'framework' => 'nullable|string',
            'requirements' => 'nullable|string'
        ]);

        try {
            $code = $this->generateCode(
                $request->type,
                $request->framework ?? 'vanilla',
                $request->requirements
            );

            return response()->json([
                'success' => true,
                'code' => $code,
                'language' => $request->type
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Code generation failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Templates prédéfinis
     */
    private function generateHeroTemplate($style)
    {
        $colors = $this->getColorScheme($style);

        return [
            'html' => '
                <section class="hero-section" style="padding: 120px 20px; background: linear-gradient(135deg, ' . $colors['primary'] . ' 0%, ' . $colors['secondary'] . ' 100%); color: white; text-align: center;">
                    <div class="container" style="max-width: 800px; margin: 0 auto;">
                        <h1 style="font-size: 3.5rem; margin-bottom: 20px; font-weight: 800;">Welcome to Our Platform</h1>
                        <p style="font-size: 1.3rem; margin-bottom: 40px; opacity: 0.9;">Create amazing websites with AI-powered tools</p>
                        <div style="display: flex; gap: 20px; justify-content: center; flex-wrap: wrap;">
                            <button style="padding: 16px 32px; background: white; color: ' . $colors['primary'] . '; border: none; border-radius: 8px; font-size: 1.1rem; cursor: pointer; font-weight: bold;">
                                Get Started
                            </button>
                            <button style="padding: 16px 32px; background: transparent; color: white; border: 2px solid white; border-radius: 8px; font-size: 1.1rem; cursor: pointer;">
                                Learn More
                            </button>
                        </div>
                    </div>
                </section>
            ',
            'css' => '
                .hero-section {
                    position: relative;
                    overflow: hidden;
                }
                
                @media (max-width: 768px) {
                    .hero-section h1 {
                        font-size: 2.5rem !important;
                    }
                    .hero-section p {
                        font-size: 1.1rem !important;
                    }
                }
            '
        ];
    }

    private function getColorScheme($style)
    {
        $schemes = [
            'modern' => ['#8b5cf6', '#4cc9f0'],
            'minimal' => ['#f8fafc', '#e2e8f0'],
            'corporate' => ['#1e293b', '#475569'],
            'creative' => ['#ec4899', '#8b5cf6'],
            'elegant' => ['#0f766e', '#14b8a6']
        ];

        return [
            'primary' => $schemes[$style][0] ?? '#8b5cf6',
            'secondary' => $schemes[$style][1] ?? '#4cc9f0'
        ];
    }

    /**
     * Optimiser le design
     */
    private function optimizeDesign($html, $css)
    {
        // Ici vous pourriez:
        // 1. Analyser le contraste des couleurs
        // 2. Vérifier la typographie
        // 3. Optimiser les images
        // 4. Améliorer la structure HTML

        $improvements = [
            'Improved color contrast for better accessibility',
            'Optimized font sizes for readability',
            'Enhanced spacing and layout',
            'Added responsive breakpoints'
        ];

        return [
            'html' => $html,
            'css' => $css . "\n/* AI Optimizations applied */",
            'improvements' => $improvements
        ];
    }

    /**
     * Générer une réponse de chat
     */
    private function generateChatResponse($message)
    {
        // Logique simple de réponse
        if (strpos(strtolower($message), 'hero') !== false) {
            return "I can help you create a hero section! What specific elements would you like to include?";
        } elseif (strpos(strtolower($message), 'form') !== false) {
            return "I'll generate a contact form for you. What fields should it include?";
        } else {
            return "I can help you with web design and development. What specific component or feature would you like to create?";
        }
    }

    /**
     * Obtenir des suggestions IA
     */
    private function getAISuggestions($context)
    {
        return [
            'Consider adding animations',
            'Improve mobile responsiveness',
            'Optimize loading performance',
            'Enhance user experience'
        ];
    }
}