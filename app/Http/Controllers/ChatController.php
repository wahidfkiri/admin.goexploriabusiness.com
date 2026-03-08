<?php

namespace App\Http\Controllers;

use App\Services\GeminiChatService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class ChatController extends Controller
{
    protected $geminiService;

    public function __construct(GeminiChatService $geminiService)
    {
        $this->geminiService = $geminiService;
    }

    public function index()
    {
        try {
            Log::info('Chat page accessed', [
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'timestamp' => now()
            ]);
            
            return view('chat.index');
            
        } catch (\Exception $e) {
            Log::error('Error loading chat page', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'ip' => request()->ip()
            ]);
            
            return view('errors.chat-error', [
                'message' => 'Unable to load chat interface. Please try again.'
            ]);
        }
    }

    public function sendMessage(Request $request)
    {
        try {
            // Log de la requête entrante
            Log::info('Chat message received', [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'message_length' => strlen($request->input('message', '')),
                'has_session' => $request->session()->has('chat_session_id'),
                'timestamp' => now()
            ]);

            // Validation
            $validated = $request->validate([
                'message' => 'required|string|max:2000|min:1'
            ]);

            // Gestion de la session
            $sessionId = $request->session()->get('chat_session_id');
            
            if (!$sessionId) {
                $sessionId = uniqid('chat_', true);
                Log::debug('New chat session created', ['session_id' => $sessionId]);
            }
            
            $request->session()->put('chat_session_id', $sessionId);

            // Log du message avant traitement
            Log::debug('Processing chat message', [
                'session_id' => $sessionId,
                'message_preview' => substr($validated['message'], 0, 100) . (strlen($validated['message']) > 100 ? '...' : ''),
                'message_length' => strlen($validated['message'])
            ]);

            // Appel au service Gemini
            $startTime = microtime(true);
            
            $response = $this->geminiService->chatWithHistory(
                $validated['message'],
                $sessionId
            );
            
            $processingTime = round((microtime(true) - $startTime) * 1000, 2); // en ms

            // Log de la réponse
            Log::info('Chat response generated', [
                'session_id' => $sessionId,
                'processing_time_ms' => $processingTime,
                'response_length' => strlen($response),
                'response_preview' => substr($response, 0, 100) . (strlen($response) > 100 ? '...' : ''),
                'timestamp' => now()
            ]);

            // Réponse JSON
            return response()->json([
                'success' => true,
                'response' => $response,
                'session_id' => $sessionId,
                'processing_time_ms' => $processingTime
            ]);

        } catch (ValidationException $e) {
            // Log des erreurs de validation
            Log::warning('Chat message validation failed', [
                'errors' => $e->errors(),
                'input' => $request->all(),
                'ip' => $request->ip()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Validation failed',
                'errors' => $e->errors(),
                'message' => 'Please provide a valid message (1-2000 characters).'
            ], 422);

        } catch (\Illuminate\Http\Exceptions\ThrottleRequestsException $e) {
            // Log des requêtes trop fréquentes
            Log::warning('Chat rate limit exceeded', [
                'ip' => $request->ip(),
                'session_id' => $sessionId ?? 'unknown',
                'timestamp' => now()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Rate limit exceeded',
                'message' => 'Too many requests. Please wait a moment.'
            ], 429);

        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
            // Log des erreurs de session
            Log::error('Session encryption error', [
                'error' => $e->getMessage(),
                'session_id' => $sessionId ?? 'unknown',
                'ip' => $request->ip()
            ]);

            // Régénérer la session
            $request->session()->regenerate();
            $newSessionId = uniqid('chat_', true);
            $request->session()->put('chat_session_id', $newSessionId);

            return response()->json([
                'success' => false,
                'error' => 'Session error',
                'message' => 'Session expired. Please try again.',
                'new_session_id' => $newSessionId
            ], 401);

        } catch (\Exception $e) {
            // Log des erreurs générales
            Log::error('Chat processing error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'session_id' => $sessionId ?? 'unknown',
                'input_message' => $request->input('message', ''),
                'ip' => $request->ip(),
                'timestamp' => now()
            ]);

            // Message d'erreur convivial
            $errorMessage = config('app.debug') 
                ? 'Error: ' . $e->getMessage()
                : 'Sorry, an error occurred while processing your message. Please try again.';

            return response()->json([
                'success' => false,
                'error' => 'Internal server error',
                'message' => $errorMessage,
                'session_id' => $sessionId ?? null
            ], 500);
        }
    }
    
    /**
     * Méthode pour vider l'historique de la session
     */
    public function clearHistory(Request $request)
    {
        try {
            $sessionId = $request->session()->get('chat_session_id');
            
            if ($sessionId) {
                // Supprimer l'historique de la session
                $request->session()->forget("chat_history.{$sessionId}");
                
                Log::info('Chat history cleared', [
                    'session_id' => $sessionId,
                    'ip' => $request->ip(),
                    'timestamp' => now()
                ]);
                
                return response()->json([
                    'success' => true,
                    'message' => 'Chat history cleared successfully.',
                    'session_id' => $sessionId
                ]);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'No active chat session found.'
            ], 404);
            
        } catch (\Exception $e) {
            Log::error('Error clearing chat history', [
                'error' => $e->getMessage(),
                'session_id' => $sessionId ?? 'unknown',
                'ip' => $request->ip()
            ]);
            
            return response()->json([
                'success' => false,
                'error' => 'Failed to clear history',
                'message' => 'An error occurred while clearing chat history.'
            ], 500);
        }
    }
    
    /**
     * Méthode pour obtenir les statistiques de la session
     */
    public function getSessionInfo(Request $request)
    {
        try {
            $sessionId = $request->session()->get('chat_session_id');
            
            if (!$sessionId) {
                return response()->json([
                    'success' => false,
                    'message' => 'No active session'
                ]);
            }
            
            $history = session("chat_history.{$sessionId}", []);
            $userMessages = array_filter($history, fn($msg) => $msg['role'] === 'user');
            $botMessages = array_filter($history, fn($msg) => $msg['role'] === 'model');
            
            Log::debug('Session info retrieved', [
                'session_id' => $sessionId,
                'total_messages' => count($history),
                'user_messages' => count($userMessages),
                'bot_messages' => count($botMessages)
            ]);
            
            return response()->json([
                'success' => true,
                'session_id' => $sessionId,
                'total_messages' => count($history),
                'user_messages' => count($userMessages),
                'bot_messages' => count($botMessages),
                'session_age' => $request->session()->get('chat_session_created', now())->diffForHumans()
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error getting session info', [
                'error' => $e->getMessage(),
                'ip' => $request->ip()
            ]);
            
            return response()->json([
                'success' => false,
                'error' => 'Failed to get session info'
            ], 500);
        }
    }
}