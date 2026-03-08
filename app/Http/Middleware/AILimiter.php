<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

class AILimiter
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        $key = 'ai_requests_' . ($user ? $user->id : $request->ip());
        
        // Limites par utilisateur
        if ($user) {
            if ($user->is_premium) {
                $maxAttempts = 200;
            } elseif ($user->created_at->diffInDays(now()) > 30) {
                $maxAttempts = 100;
            } else {
                $maxAttempts = 50; // Nouveaux utilisateurs
            }
        } else {
            $maxAttempts = 10; // Utilisateurs non authentifiés
        }
        
        $decayMinutes = 1;
        
        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            $seconds = RateLimiter::availableIn($key);
            
            return response()->json([
                'success' => false,
                'message' => 'Too many AI requests. Please try again in ' . ceil($seconds / 60) . ' minutes.',
                'retry_after' => $seconds
            ], 429);
        }
        
        RateLimiter::hit($key, $decayMinutes * 60);
        
        return $next($request);
    }
}