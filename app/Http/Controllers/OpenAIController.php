<?php

namespace App\Http\Controllers;

use App\Services\OpenAIService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\RateLimiter;

class OpenAIController extends Controller
{
    protected $aiService;
    
    public function __construct(OpenAIService $aiService)
    {
        $this->aiService = $aiService;
    }
    
    /**
     * Générer un composant avec IA
     */
    public function generate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|string|in:hero,features,contact,pricing,testimonials,team,custom',
            'prompt' => 'nullable|string|max:1000',
            'style' => 'nullable|string|in:modern,minimal,corporate,creative,elegant',
            'current_html' => 'nullable|string',
            'current_css' => 'nullable|string'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }
        
        // Rate limiting
        $key = 'ai_generate_' . auth()->id();
        if (RateLimiter::tooManyAttempts($key, 50)) {
            return response()->json([
                'success' => false,
                'message' => 'Too many requests. Please try again later.'
            ], 429);
        }
        
        RateLimiter::hit($key);
        
        try {
            $result = $this->aiService->generateComponent(
                $request->type,
                $request->prompt ?? '',
                $request->style ?? 'modern'
            );
            
            return response()->json([
                'success' => true,
                'data' => $result
            ]);
            
        } catch (\Exception $e) {
            \Log::error('OpenAI Generate Error:', ['error' => $e->getMessage()]);
            
            return response()->json([
                'success' => false,
                'message' => 'AI generation failed. Please try again.',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
    
    /**
     * Optimiser un design
     */
    public function optimize(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'html' => 'required|string',
            'css' => 'nullable|string'
        ]);
        
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        
        try {
            $result = $this->aiService->optimizeDesign(
                $request->html,
                $request->css ?? ''
            );
            
            return response()->json([
                'success' => true,
                'data' => $result
            ]);
            
        } catch (\Exception $e) {
            \Log::error('OpenAI Optimize Error:', ['error' => $e->getMessage()]);
            
            return response()->json([
                'success' => false,
                'message' => 'Optimization failed'
            ], 500);
        }
    }
    
    /**
     * Chat avec l'IA
     */
    public function chat(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'messages' => 'required|array',
            'messages.*.role' => 'required|in:system,user,assistant',
            'messages.*.content' => 'required|string',
            'context' => 'nullable|array'
        ]);
        
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        
        // Rate limiting
        $key = 'ai_chat_' . auth()->id();
        if (RateLimiter::tooManyAttempts($key, 100)) {
            return response()->json([
                'success' => false,
                'message' => 'Too many chat requests. Please wait a moment.'
            ], 429);
        }
        
        RateLimiter::hit($key);
        
        try {
            $result = $this->aiService->chat($request->messages);
            
            return response()->json([
                'success' => true,
                'data' => $result
            ]);
            
        } catch (\Exception $e) {
            \Log::error('OpenAI Chat Error:', ['error' => $e->getMessage()]);
            
            return response()->json([
                'success' => false,
                'message' => 'Chat failed'
            ], 500);
        }
    }
    
    /**
     * Générer du code
     */
    public function code(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'language' => 'required|string|in:html,css,javascript,php,python',
            'requirements' => 'required|string|max:500',
            'framework' => 'nullable|string'
        ]);
        
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        
        try {
            $code = $this->aiService->generateCode(
                $request->language,
                $request->requirements,
                $request->framework ?? 'vanilla'
            );
            
            return response()->json([
                'success' => true,
                'code' => $code,
                'language' => $request->language
            ]);
            
        } catch (\Exception $e) {
            \Log::error('OpenAI Code Error:', ['error' => $e->getMessage()]);
            
            return response()->json([
                'success' => false,
                'message' => 'Code generation failed'
            ], 500);
        }
    }
    
    /**
     * Générer des variations
     */
    public function variations(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'base_prompt' => 'required|string|max:500',
            'variations' => 'nullable|integer|min:1|max:5',
            'style' => 'nullable|string'
        ]);
        
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        
        try {
            $variations = $this->aiService->generateVariations(
                $request->base_prompt,
                $request->variations ?? 3
            );
            
            return response()->json([
                'success' => true,
                'variations' => $variations,
                'count' => count($variations)
            ]);
            
        } catch (\Exception $e) {
            \Log::error('OpenAI Variations Error:', ['error' => $e->getMessage()]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate variations'
            ], 500);
        }
    }
    
    /**
     * Améliorer un design
     */
    public function improve(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'html' => 'required|string',
            'css' => 'nullable|string',
            'improvement_type' => 'nullable|string|in:performance,accessibility,seo,design'
        ]);
        
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        
        try {
            $result = $this->aiService->improveDesign(
                $request->html,
                $request->css ?? '',
                $request->improvement_type ?? 'design'
            );
            
            return response()->json([
                'success' => true,
                'data' => $result
            ]);
            
        } catch (\Exception $e) {
            \Log::error('OpenAI Improve Error:', ['error' => $e->getMessage()]);
            
            return response()->json([
                'success' => false,
                'message' => 'Improvement failed'
            ], 500);
        }
    }
    
    /**
     * Vérifier le statut de l'API
     */
    public function status()
    {
        try {
            $status = $this->aiService->checkStatus();
            
            return response()->json([
                'success' => true,
                'status' => $status,
                'model' => config('openai.models.default')
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'status' => 'disconnected',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Obtenir les statistiques d'utilisation
     */
    public function usage()
    {
        try {
            $usage = \App\Models\AIUsage::where('user_id', auth()->id())
                ->selectRaw('
                    SUM(prompt_tokens) as total_prompt_tokens,
                    SUM(completion_tokens) as total_completion_tokens,
                    SUM(total_tokens) as total_tokens,
                    SUM(estimated_cost) as total_cost,
                    COUNT(*) as total_requests,
                    DATE(created_at) as date
                ')
                ->groupBy('date')
                ->orderBy('date', 'desc')
                ->limit(30)
                ->get();
            
            return response()->json([
                'success' => true,
                'data' => $usage,
                'monthly_estimate' => $usage->sum('total_cost'),
                'settings' => [
                    'model' => config('openai.models.default'),
                    'rate_limit' => config('openai.rate_limits.requests_per_minute')
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get usage stats'
            ], 500);
        }
    }
    
    /**
     * Obtenir les modèles disponibles
     */
    public function models()
    {
        $models = [
            'gpt-3.5-turbo' => [
                'name' => 'GPT-3.5 Turbo',
                'description' => 'Fast and cost-effective',
                'max_tokens' => 4096,
                'cost_per_1k' => 0.002
            ],
            'gpt-4' => [
                'name' => 'GPT-4',
                'description' => 'Most capable model',
                'max_tokens' => 8192,
                'cost_per_1k' => 0.06
            ],
            'gpt-4-turbo' => [
                'name' => 'GPT-4 Turbo',
                'description' => 'Latest GPT-4 model',
                'max_tokens' => 4096,
                'cost_per_1k' => 0.01
            ]
        ];
        
        return response()->json([
            'success' => true,
            'models' => $models,
            'default' => config('openai.models.default')
        ]);
    }
    
    /**
     * Statistiques d'utilisation détaillées
     */
    public function usageStats()
    {
        try {
            $stats = \App\Models\AIUsage::where('user_id', auth()->id())
                ->selectRaw('
                    model,
                    COUNT(*) as request_count,
                    SUM(total_tokens) as total_tokens,
                    SUM(estimated_cost) as total_cost,
                    AVG(total_tokens) as avg_tokens_per_request
                ')
                ->groupBy('model')
                ->get();
            
            return response()->json([
                'success' => true,
                'stats' => $stats
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get detailed stats'
            ], 500);
        }
    }
    
    /**
     * Génération démo (publique avec limitations)
     */
    public function demoGenerate(Request $request)
    {
        // Rate limiting plus strict pour les démos
        $key = 'ai_demo_' . ($request->ip() ?? 'anonymous');
        if (RateLimiter::tooManyAttempts($key, 5)) {
            return response()->json([
                'success' => false,
                'message' => 'Demo limit reached. Please create an account for more requests.'
            ], 429);
        }
        
        RateLimiter::hit($key);
        
        // Limiter les types pour la démo
        $allowedTypes = ['hero', 'features', 'contact'];
        
        $validator = Validator::make($request->all(), [
            'type' => 'required|string|in:' . implode(',', $allowedTypes),
            'prompt' => 'nullable|string|max:200',
            'style' => 'nullable|string|in:modern,minimal'
        ]);
        
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        
        try {
            // Utiliser un service mock pour la démo
            $result = $this->aiService->generateDemoComponent(
                $request->type,
                $request->prompt ?? '',
                $request->style ?? 'modern'
            );
            
            return response()->json([
                'success' => true,
                'data' => $result,
                'demo' => true,
                'message' => 'Demo request successful. Create an account for full access.'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Demo generation failed'
            ], 500);
        }
    }
}