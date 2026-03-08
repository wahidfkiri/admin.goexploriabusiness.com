<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Project;
use App\Models\Task;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Afficher la page de profil
     */
    public function index()
    {
        $user = Auth::user();
        
        // Charger les relations nécessaires
        $user->load(['projects', 'tasks']);
        
        // Statistiques de l'utilisateur
        $stats = [
            'totalProjects' => $user->projects()->count(),
            'totalTasks' => $user->tasks()->count(),
            'completedTasks' => $user->tasks()->where('status', 'completed')->count(),
            'activeProjects' => $user->projects()->where('status', 'active')->count(),
            'points' => $this->calculateUserPoints($user),
        ];
        
        // Activités récentes
        $recentActivities = ActivityLog::where('user_id', $user->id)
            ->with('subject')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();
        
        // Sessions actives
        $sessions = $this->getActiveSessions($user);
        
        return view('profile', compact('user', 'stats', 'recentActivities', 'sessions'));
    }
    
    /**
     * Mettre à jour les informations du profil
     */
    public function updateInfo(Request $request)
    {
        $user = Auth::user();
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'phone' => 'nullable|string|max:20',
            'position' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'bio' => 'nullable|string|max:1000',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }
        
        $user->update($request->only([
            'name', 'email', 'phone', 'position', 
            'department', 'location', 'bio'
        ]));
        
        // Journaliser l'activité
        ActivityLog::create([
            'user_id' => $user->id,
            'action' => 'profile_update',
            'description' => 'Mise à jour des informations du profil',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Profil mis à jour avec succès',
            'user' => $user
        ]);
    }
    
    /**
     * Mettre à jour l'avatar
     */
    public function updateAvatar(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }
        
        $user = Auth::user();
        
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            
            // Supprimer l'ancien avatar si existe
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }
            
            $user->avatar = $avatarPath;
            $user->save();
            
            ActivityLog::create([
                'user_id' => $user->id,
                'action' => 'avatar_update',
                'description' => 'Mise à jour de l\'avatar',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Avatar mis à jour avec succès',
                'avatar_url' => asset('storage/' . $avatarPath)
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Aucun fichier uploadé'
        ], 400);
    }
    
    /**
     * Changer le mot de passe
     */
    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed|different:current_password',
            'new_password_confirmation' => 'required|string'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }
        
        $user = Auth::user();
        
        // Vérifier l'ancien mot de passe
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Le mot de passe actuel est incorrect'
            ], 422);
        }
        
        // Mettre à jour le mot de passe
        $user->password = Hash::make($request->new_password);
        $user->save();
        
        // Déconnecter les autres sessions (optionnel)
        Auth::logoutOtherDevices($request->new_password);
        
        ActivityLog::create([
            'user_id' => $user->id,
            'action' => 'password_change',
            'description' => 'Changement de mot de passe',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Mot de passe changé avec succès'
        ]);
    }
    
    /**
     * Mettre à jour les préférences
     */
    public function updatePreferences(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'theme' => 'required|in:light,dark,system',
            'language' => 'required|string|size:2',
            'timezone' => 'required|string|timezone',
            'compact_mode' => 'boolean',
            'animations' => 'boolean'
        ]);
        
        // Sauvegarder les préférences dans user_preferences (table à créer)
        $preferences = $user->preferences ?? [];
        $preferences = array_merge($preferences, $validated);
        
        $user->preferences = $preferences;
        $user->save();
        
        ActivityLog::create([
            'user_id' => $user->id,
            'action' => 'preferences_update',
            'description' => 'Mise à jour des préférences',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Préférences mises à jour avec succès'
        ]);
    }
    
    /**
     * Mettre à jour les notifications
     */
    public function updateNotifications(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'email_new_projects' => 'boolean',
            'email_task_updates' => 'boolean',
            'email_mentions' => 'boolean',
            'email_weekly_reports' => 'boolean',
            'in_app_sound' => 'boolean',
            'in_app_badges' => 'boolean',
            'in_app_desktop' => 'boolean'
        ]);
        
        // Sauvegarder les préférences de notification
        $notificationSettings = $user->notification_settings ?? [];
        $notificationSettings = array_merge($notificationSettings, $validated);
        
        $user->notification_settings = $notificationSettings;
        $user->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Paramètres de notification mis à jour avec succès'
        ]);
    }
    
    /**
     * Obtenir les activités récentes (pour chargement AJAX)
     */
    public function getActivities(Request $request)
    {
        $user = Auth::user();
        
        $activities = ActivityLog::where('user_id', $user->id)
            ->with('subject')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return response()->json([
            'success' => true,
            'activities' => $activities
        ]);
    }
    
    /**
     * Révoquer une session
     */
    public function revokeSession(Request $request, $sessionId)
    {
        // Implémentation dépend de votre système de gestion des sessions
        // Exemple avec Laravel's session manager
        try {
            // Logique pour révoquer la session
            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'session_revoked',
                'description' => 'Révocation d\'une session active',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Session révoquée avec succès'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la révocation de la session'
            ], 500);
        }
    }
    
    /**
     * Activer/désactiver la 2FA
     */
    public function toggleTwoFactor(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'enabled' => 'required|boolean'
        ]);
        
        if ($validated['enabled']) {
            // Générer le secret 2FA
            // Cette partie dépend du package que vous utilisez pour la 2FA
            // Exemple avec pragmarx/google2fa-laravel
            $google2fa = app('pragmarx.google2fa');
            $user->google2fa_secret = $google2fa->generateSecretKey();
        } else {
            $user->google2fa_secret = null;
        }
        
        $user->save();
        
        $message = $validated['enabled'] ? '2FA activée avec succès' : '2FA désactivée avec succès';
        
        ActivityLog::create([
            'user_id' => $user->id,
            'action' => '2fa_toggle',
            'description' => $message,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);
        
        return response()->json([
            'success' => true,
            'message' => $message,
            'secret' => $validated['enabled'] ? $user->google2fa_secret : null
        ]);
    }
    
    /**
     * Calculer les points de l'utilisateur
     */
    private function calculateUserPoints($user)
    {
        // Logique personnalisée pour calculer les points
        $points = 0;
        
        // Points pour les projets
        $points += $user->projects()->count() * 10;
        
        // Points pour les tâches terminées
        $points += $user->tasks()->where('status', 'completed')->count() * 5;
        
        // Points pour l'ancienneté
        $daysSinceJoined = now()->diffInDays($user->created_at);
        $points += floor($daysSinceJoined * 0.5);
        
        return $points;
    }
    
    /**
     * Obtenir les sessions actives
     */
    private function getActiveSessions($user)
    {
        // Cette méthode dépend de comment vous gérez les sessions
        // Exemple simple
        $sessions = [];
        
        // Session actuelle
        $sessions[] = [
            'id' => session()->getId(),
            'device' => 'Windows - Chrome',
            'ip' => request()->ip(),
            'last_activity' => now(),
            'current' => true
        ];
        
        // Autres sessions (à adapter selon votre configuration)
        // Vous pouvez utiliser la table 'sessions' de Laravel pour récupérer les autres sessions
        
        return $sessions;
    }
}