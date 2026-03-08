<?php
// app/Http/Controllers/TestOpenAIController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\OpenAISimpleGenerator;

class TestOpenAIController extends Controller
{
    public function testSimple(Request $request, OpenAISimpleGenerator $generator)
    {
        $prompt = $request->input('prompt', 'Écris un paragraphe HTML simple');
        
        try {
            // Test direct
            $testResult = $generator->testDirect();
            
            if (!$testResult['success']) {
                return response()->json([
                    'error' => 'OpenAI test failed',
                    'details' => $testResult
                ], 500);
            }
            
            // Générer un template simple
            $template = $generator->generateSimple($prompt, [
                'name' => 'Test Template'
            ]);
            
            return response()->json([
                'success' => true,
                'template_id' => $template->id,
                'html_length' => strlen($template->html_content),
                'test_result' => $testResult
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }
    
    /**
     * Route pour tester sans passer par le cache
     */
    public function bypassCache(Request $request)
    {
        $apiKey = config('openai.api_key');
        
        if (empty($apiKey)) {
            return response()->json(['error' => 'API key missing'], 500);
        }
        
        $client = \OpenAI::client($apiKey);
        
        try {
            // Appel DIRECT sans aucun rate limiting local
            $response = $client->chat()->create([
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    ['role' => 'user', 'content' => 'Écris un paragraphe HTML avec une balise <p>']
                ],
                'max_tokens' => 100,
            ]);
            
            return response()->json([
                'success' => true,
                'response' => $response->choices[0]->message->content,
                'model' => 'gpt-3.5-turbo',
                'tokens' => $response->usage->totalTokens ?? 0
            ]);
            
        } catch (\OpenAI\Exceptions\RateLimitException $e) {
            return response()->json([
                'success' => false,
                'error' => 'OPENAI_RATE_LIMIT',
                'message' => $e->getMessage(),
                'solution' => 'Votre compte OpenAI a dépassé sa limite. Attendez ou vérifiez vos quotas.'
            ], 429);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => get_class($e),
                'message' => $e->getMessage()
            ], 500);
        }
    }
}