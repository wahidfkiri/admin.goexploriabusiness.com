<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class GeminiChatService
{
    protected $apiKey;
    protected $model;
    protected $baseUrl = 'https://generativelanguage.googleapis.com/v1beta/models/';

    public function __construct()
    {
        $this->apiKey = config('services.gemini.api_key', env('GEMINI_API_KEY'));
        // Utiliser un modèle plus courant et disponible
        $this->model = 'gemini-2.5-flash';
        
        // Vérifier que la clé API est définie
        if (empty($this->apiKey)) {
            Log::warning('Gemini API key is not configured');
        }
    }

    /**
     * Liste les modèles disponibles
     */
    public function listAvailableModels(): array
    {
        try {
            $cacheKey = 'gemini_models_' . md5($this->apiKey);
            
            return Cache::remember($cacheKey, 3600, function () {
                $response = Http::withHeaders([
                    'Content-Type' => 'application/json',
                ])->get("https://generativelanguage.googleapis.com/v1beta/models?key={$this->apiKey}");
                
                if ($response->failed()) {
                    Log::error('Failed to fetch Gemini models', [
                        'status' => $response->status(),
                        'response' => $response->json()
                    ]);
                    return [];
                }
                
                $data = $response->json();
                return $data['models'] ?? [];
            });
            
        } catch (\Exception $e) {
            Log::error('Error listing Gemini models: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Vérifie si le modèle est disponible
     */
    protected function checkModelAvailability(): bool
    {
        $models = $this->listAvailableModels();
        
        foreach ($models as $model) {
            if ($model['name'] === "models/{$this->model}") {
                return true;
            }
        }
        
        // Si le modèle n'est pas trouvé, essayer avec un modèle de secours
        $this->model = $this->getFallbackModel($models);
        return !empty($this->model);
    }

    /**
     * Obtient un modèle de secours
     */
    protected function getFallbackModel(array $models): string
    {
        $preferredModels = [
            'gemini-1.0-pro',
            'gemini-pro',
            'models/gemini-1.0-pro',
            'models/gemini-pro'
        ];
        
        foreach ($preferredModels as $preferred) {
            foreach ($models as $model) {
                if (str_contains($model['name'], $preferred) || 
                    str_contains($preferred, $model['name'])) {
                    return str_replace('models/', '', $model['name']);
                }
            }
        }
        
        return 'gemini-1.0-pro'; // Modèle par défaut
    }

    /**
     * Envoie un message à Gemini
     */
    public function chat(string $message, array $context = []): array
    {
        try {
            // Vérifier la clé API
            if (empty($this->apiKey)) {
                Log::error('Gemini API key is missing');
                return [
                    'success' => false,
                    'error' => 'API key not configured',
                    'response' => 'Veuillez configurer la clé API Gemini.'
                ];
            }

            // Vérifier la disponibilité du modèle
            if (!$this->checkModelAvailability()) {
                Log::warning('Using fallback model', ['model' => $this->model]);
            }

            $messages = $this->prepareMessages($message, $context);
            
            Log::debug('Sending request to Gemini', [
                'model' => $this->model,
                'message_length' => strlen($message),
                'context_length' => count($context)
            ]);

            // Construire l'URL avec la version correcte
            $url = "https://generativelanguage.googleapis.com/v1beta/models/{$this->model}:generateContent";
            
            $response = Http::timeout(30)->withHeaders([
                'Content-Type' => 'application/json',
            ])->post("{$url}?key={$this->apiKey}", [
                'contents' => $messages,
                'generationConfig' => [
                    'temperature' => 0.7,
                    'topK' => 1,
                    'topP' => 0.8,
                    'maxOutputTokens' => 512,
                ],
                'safetySettings' => [
                    [
                        'category' => 'HARM_CATEGORY_HARASSMENT',
                        'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'
                    ],
                    [
                        'category' => 'HARM_CATEGORY_HATE_SPEECH',
                        'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'
                    ]
                ]
            ]);

            Log::debug('Gemini API response', [
                'status' => $response->status(),
                'model' => $this->model
            ]);

            if ($response->failed()) {
                $errorData = $response->json();
                Log::error('Gemini API Error', [
                    'status' => $response->status(),
                    'response' => $errorData,
                    'model' => $this->model,
                    'url' => $url
                ]);

                // Gestion des erreurs spécifiques
                if ($response->status() === 404) {
                    // Modèle non trouvé, essayer avec un modèle différent
                    $this->handleModelNotFound();
                    return $this->chat($message, $context); // Réessayer
                }

                return [
                    'success' => false,
                    'error' => 'API request failed',
                    'details' => $errorData['error']['message'] ?? 'Unknown error',
                    'model' => $this->model
                ];
            }

            $data = $response->json();
            
            return [
                'success' => true,
                'response' => $this->extractText($data),
                'model' => $this->model,
                'full_response' => $data
            ];

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error('Gemini API Connection Error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => 'Connection timeout',
                'response' => 'Le service est temporairement indisponible. Veuillez réessayer.'
            ];
            
        } catch (\Exception $e) {
            Log::error('Gemini Service Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'model' => $this->model ?? 'unknown'
            ]);
            
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'response' => 'Une erreur est survenue lors du traitement de votre demande.'
            ];
        }
    }

    /**
     * Gère l'erreur de modèle non trouvé
     */
    protected function handleModelNotFound(): void
    {
        // Changer de modèle
        $this->model = 'gemini-1.0-pro';
        
        // Vider le cache des modèles
        Cache::forget('gemini_models_' . md5($this->apiKey));
        
        Log::warning('Model not found, switching to fallback', [
            'new_model' => $this->model
        ]);
    }

    /**
     * Prépare les messages pour l'API
     */
    protected function prepareMessages(string $message, array $context): array
    {
        $contents = [];

        // Ajouter le contexte historique si disponible
        foreach ($context as $msg) {
            if (isset($msg['role'], $msg['content'])) {
                $contents[] = [
                    'role' => $msg['role'],
                    'parts' => [['text' => $msg['content']]]
                ];
            }
        }

        // Ajouter le nouveau message
        $contents[] = [
            'role' => 'user',
            'parts' => [['text' => $message]]
        ];

        return $contents;
    }

    /**
     * Extrait le texte de la réponse
     */
    protected function extractText(array $data): string
    {
        try {
            if (!isset($data['candidates'][0]['content']['parts'][0]['text'])) {
                Log::warning('Unexpected response format from Gemini', ['data' => $data]);
                
                // Vérifier les erreurs potentielles
                if (isset($data['error']['message'])) {
                    return 'Erreur: ' . $data['error']['message'];
                }
                
                return 'Désolé, je n\'ai pas pu générer de réponse. Format de réponse inattendu.';
            }

            $text = $data['candidates'][0]['content']['parts'][0]['text'];
            
            // Nettoyer la réponse si nécessaire
            $text = trim($text);
            
            if (empty($text)) {
                return 'Désolé, la réponse est vide. Veuillez réessayer avec une autre question.';
            }

            return $text;

        } catch (\Exception $e) {
            Log::error('Error extracting text from Gemini response: ' . $e->getMessage());
            return 'Désolé, une erreur est survenue lors du traitement de la réponse.';
        }
    }

    /**
     * Version simplifiée pour usage rapide avec gestion d'erreurs
     */
    public function simpleChat(string $message): string
    {
        try {
            $result = $this->chat($message);
            
            if (!$result['success']) {
                Log::warning('Gemini chat failed', ['error' => $result['error'] ?? 'unknown']);
                
                // Retourner un message d'erreur convivial
                return 'Je suis désolé, je rencontre des difficultés techniques pour le moment. '
                     . 'Veuillez réessayer dans quelques instants. '
                     . '(Erreur: ' . ($result['error'] ?? 'service temporairement indisponible') . ')';
            }

            return $result['response'] ?? 'Pas de réponse disponible.';

        } catch (\Exception $e) {
            Log::error('Error in simpleChat: ' . $e->getMessage());
            return 'Une erreur inattendue est survenue. Veuillez contacter le support technique.';
        }
    }

    /**
     * Chat avec historique et gestion de session
     */
    public function chatWithHistory(string $message, string $sessionId): string
    {
        try {
            // Vérifier la longueur du message
            if (strlen($message) > 2000) {
                return 'Votre message est trop long. Veuillez le raccourcir à moins de 2000 caractères.';
            }

            // Récupérer l'historique depuis la session
            $historyKey = "chat_history.{$sessionId}";
            $history = session($historyKey, []);
            
            Log::info('Processing chat with history', [
                'session_id' => $sessionId,
                'message_length' => strlen($message),
                'history_count' => count($history)
            ]);

            // Limiter l'historique à 10 messages pour éviter les tokens excessifs
            if (count($history) > 10) {
                $history = array_slice($history, -10);
            }

            $result = $this->chat($message, $history);
            
            if ($result['success']) {
                // Mettre à jour l'historique
                $history[] = ['role' => 'user', 'content' => $message];
                $history[] = ['role' => 'model', 'content' => $result['response']];
                
                // Garder seulement les 10 derniers messages
                if (count($history) > 20) {
                    $history = array_slice($history, -20);
                }
                
                session([$historyKey => $history]);
                
                Log::info('Chat successful', [
                    'session_id' => $sessionId,
                    'response_length' => strlen($result['response'])
                ]);
                
                return $result['response'];
            }
            
            // En cas d'échec, retourner un message d'erreur
            Log::error('Chat failed', [
                'session_id' => $sessionId,
                'error' => $result['error'] ?? 'unknown'
            ]);
            
            return 'Je suis désolé, je n\'arrive pas à traiter votre demande pour le moment. '
                 . 'Veuillez réessayer ou contacter le support si le problème persiste.';
            
        } catch (\Exception $e) {
            Log::error('Error in chatWithHistory: ' . $e->getMessage(), [
                'session_id' => $sessionId,
                'trace' => $e->getTraceAsString()
            ]);
            
            return 'Une erreur technique est survenue. Veuillez réessayer dans quelques instants.';
        }
    }

    /**
     * Test de connexion à l'API Gemini
     */
    public function testConnection(): array
    {
        try {
            $response = Http::timeout(10)->get(
                "https://generativelanguage.googleapis.com/v1beta/models?key={$this->apiKey}"
            );

            if ($response->successful()) {
                $models = $response->json()['models'] ?? [];
                return [
                    'success' => true,
                    'message' => 'Connexion réussie',
                    'models_count' => count($models),
                    'available_models' => array_column($models, 'name')
                ];
            }

            return [
                'success' => false,
                'message' => 'Échec de connexion',
                'status' => $response->status(),
                'error' => $response->json()['error']['message'] ?? 'Unknown error'
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Exception: ' . $e->getMessage()
            ];
        }
    }
}