<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\GeminiChatService;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    protected $geminiService;

    public function __construct(GeminiChatService $geminiService)
    {
        $this->geminiService = $geminiService;
    }

    /**
     * Endpoint simple pour le chat
     */
    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:2000',
            'session_id' => 'nullable|string'
        ]);

        $message = $request->input('message');
        $sessionId = $request->input('session_id', uniqid());

        $response = $this->geminiService->chatWithHistory($message, $sessionId);

        return response()->json([
            'success' => true,
            'response' => $response,
            'session_id' => $sessionId
        ]);
    }

    /**
     * Endpoint avec contexte
     */
    public function chatWithContext(Request $request)
    {
        $request->validate([
            'messages' => 'required|array',
            'messages.*.role' => 'required|in:user,model',
            'messages.*.content' => 'required|string'
        ]);

        $context = $request->input('messages');
        $lastMessage = end($context);
        
        $result = $this->geminiService->chat($lastMessage['content'], $context);

        return response()->json($result);
    }

    /**
     * Récupérer l'historique
     */
    public function getHistory(Request $request)
    {
        $sessionId = $request->input('session_id');
        
        if (!$sessionId) {
            return response()->json([
                'success' => false,
                'error' => 'Session ID required'
            ], 400);
        }

        $history = session("chat_history.{$sessionId}", []);

        return response()->json([
            'success' => true,
            'history' => $history,
            'session_id' => $sessionId
        ]);
    }

    /**
     * Effacer l'historique
     */
    public function clearHistory(Request $request)
    {
        $sessionId = $request->input('session_id');
        
        if ($sessionId && session()->has("chat_history.{$sessionId}")) {
            session()->forget("chat_history.{$sessionId}");
        }

        return response()->json([
            'success' => true,
            'message' => 'History cleared'
        ]);
    }
}