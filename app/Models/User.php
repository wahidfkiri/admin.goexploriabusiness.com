<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_active',
        'phone',
        'position',
        'department',
        'location',
        'bio',
        'avatar',
        'email_verified_at',
        'remember_token',
        // 'preferences',
        // 'notification_settings',
        // 'google2fa_secret',
        // 'last_login_at',
        // 'last_login_ip',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        // 'google2fa_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            // 'last_login_at' => 'datetime',
            // 'preferences' => 'array',
            // 'notification_settings' => 'array',
        ];
    }

    /**
     * Les attributs par défaut
     */
    protected $attributes = [
        'is_active' => true,
        // 'preferences' => '{"theme":"light","language":"fr","timezone":"Europe/Paris","compact_mode":false,"animations":true}',
        // 'notification_settings' => '{"email_new_projects":true,"email_task_updates":true,"email_mentions":true,"email_weekly_reports":false,"in_app_sound":true,"in_app_badges":true,"in_app_desktop":false}',
    ];

    /**
     * RELATIONS
     */

    /**
     * Relation avec le profil client (un utilisateur peut avoir un client)
     */
    public function customer(): HasOne
    {
        return $this->hasOne(Customer::class);
    }

    /**
     * Relation avec l'établissement (un utilisateur peut avoir un établissement)
     */
    public function etablissement(): HasOne
    {
        return $this->hasOne(Etablissement::class);
    }

    /**
     * Relation avec les sites web créés par l'utilisateur
     */
    public function websites(): HasMany
    {
        return $this->hasMany(Website::class);
    }

    /**
     * Relation avec les projets où l'utilisateur est responsable (user_id dans projects)
     */
    public function responsibleProjects(): HasMany
    {
        return $this->hasMany(Project::class, 'user_id');
    }

    /**
     * Relation avec les projets créés par l'utilisateur
     */
    public function createdProjects(): HasMany
    {
        return $this->hasMany(Project::class, 'created_by');
    }

    /**
     * Relation many-to-many avec les projets (via table pivot project_user)
     * Pour les projets où l'utilisateur est membre/assigné
     */
    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class, 'project_user')
                    ->withPivot('role', 'assigned_at')
                    ->withTimestamps()
                    ->orderBy('project_user.created_at', 'desc');
    }

   

    // Dans app/Models/User.php

/**
 * Relation many-to-many avec les tâches (via table pivot task_user)
 * Pour les tâches où l'utilisateur est assigné
 */
public function tasks(): BelongsToMany
{
    return $this->belongsToMany(Task::class, 'task_user')
                ->withPivot('role', 'assigned_at', 'assigned_by')
                ->withTimestamps()
                ->orderBy('task_user.created_at', 'desc');
}

/**
 * Relation avec les tâches où l'utilisateur est responsable principal (user_id dans tasks)
 */
public function assignedTasks(): HasMany
{
    return $this->hasMany(Task::class, 'user_id');
}

/**
 * Relation avec les activités de l'utilisateur
 */
public function activitielog(): HasMany
{
    return $this->hasMany(ActivityLog::class);
}

    /**
     * Relation avec les tâches créées par l'utilisateur
     */
    public function createdTasks(): HasMany
    {
        return $this->hasMany(Task::class, 'created_by');
    }

    /**
     * Relation avec les commentaires de l'utilisateur
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Relation avec les commentaires de tâches
     */
    public function taskComments(): HasMany
    {
        return $this->hasMany(TaskComment::class);
    }

    /**
     * Relation avec les fichiers uploadés par l'utilisateur
     */
    public function uploadedFiles(): HasMany
    {
        return $this->hasMany(TaskFile::class, 'uploaded_by');
    }

    /**
     * Relation avec les activités de l'utilisateur
     */
    public function activities(): HasMany
    {
        return $this->hasMany(ActivityLog::class);
    }

    /**
     * SCOPES
     */

    /**
     * Scope pour les utilisateurs actifs
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope pour les utilisateurs inactifs
     */
    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    /**
     * Scope pour filtrer par rôle
     */
    public function scopeByRole($query, $role)
    {
        return $query->whereHas('roles', function ($q) use ($role) {
            $q->where('name', $role);
        });
    }

    /**
     * Scope pour rechercher des utilisateurs
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%")
              ->orWhere('phone', 'like', "%{$search}%");
        });
    }

    /**
     * ACCESSORS & MUTATORS
     */

    /**
     * Obtenir l'URL complète de l'avatar
     */
    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar && Storage::disk('public')->exists($this->avatar)) {
            return asset('storage/' . $this->avatar);
        }
        
        // Avatar par défaut avec les initiales
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&size=150&background=4361ee&color=fff&bold=true';
    }

    /**
     * Obtenir le nom formaté (première lettre en majuscule)
     */
    public function getFormattedNameAttribute(): string
    {
        return ucwords(strtolower($this->name));
    }

    /**
     * Obtenir les initiales de l'utilisateur
     */
    public function getInitialsAttribute(): string
    {
        $words = explode(' ', $this->name);
        $initials = '';
        
        foreach ($words as $word) {
            if (!empty($word)) {
                $initials .= strtoupper(substr($word, 0, 1));
            }
        }
        
        return substr($initials, 0, 2);
    }

    /**
     * Obtenir la préférence de thème
     */
    public function getThemePreferenceAttribute(): string
    {
        return $this->preferences['theme'] ?? 'light';
    }

    /**
     * Obtenir la préférence de langue
     */
    public function getLanguagePreferenceAttribute(): string
    {
        return $this->preferences['language'] ?? 'fr';
    }

    /**
     * Obtenir le fuseau horaire préféré
     */
    public function getTimezonePreferenceAttribute(): string
    {
        return $this->preferences['timezone'] ?? 'Europe/Paris';
    }

    /**
     * Obtenir le nombre de projets de l'utilisateur
     */
    public function getProjectsCountAttribute(): int
    {
        return $this->responsibleProjects()->count() + $this->projects()->count();
    }

    /**
     * Obtenir le nombre de tâches de l'utilisateur
     */
    public function getTasksCountAttribute(): int
    {
        return $this->assignedTasks()->count() + $this->tasks()->count();
    }

    /**
     * Obtenir le nombre de tâches terminées
     */
    public function getCompletedTasksCountAttribute(): int
    {
        return $this->assignedTasks()
                    ->whereIn('status', ['approved', 'delivered', 'completed'])
                    ->count();
    }

    /**
     * Obtenir le pourcentage de tâches terminées
     */
    public function getCompletionRateAttribute(): float
    {
        $total = $this->tasks_count;
        
        if ($total === 0) {
            return 0;
        }
        
        return round(($this->completed_tasks_count / $total) * 100, 2);
    }

    /**
     * MÉTHODES PERSONNALISÉES
     */

    /**
     * Vérifier si l'utilisateur a un rôle spécifique
     */
    public function hasRole($role): bool
    {
        return $this->roles()->where('name', $role)->exists();
    }

    /**
     * Vérifier si l'utilisateur est administrateur
     */
    public function isAdmin(): bool
    {
        return $this->hasRole('admin') || $this->hasRole('super-admin');
    }

    /**
     * Vérifier si l'utilisateur est responsable d'un projet
     */
    public function isProjectResponsible(Project $project): bool
    {
        return $project->user_id === $this->id;
    }

    /**
     * Vérifier si l'utilisateur est membre d'un projet
     */
    public function isProjectMember(Project $project): bool
    {
        return $this->projects()->where('project_id', $project->id)->exists();
    }

    /**
     * Vérifier si l'utilisateur a accès à un projet
     */
    public function hasAccessToProject(Project $project): bool
    {
        return $this->isProjectResponsible($project) || 
               $this->isProjectMember($project) || 
               $this->isAdmin();
    }

    /**
     * Mettre à jour la dernière connexion
     */
    public function updateLastLogin(string $ip = null): void
    {
        $this->last_login_at = now();
        $this->last_login_ip = $ip ?? request()->ip();
        $this->save();
    }

    /**
     * Calculer les points de l'utilisateur
     */
    public function calculatePoints(): int
    {
        $points = 0;
        
        // Points pour les projets responsables (10 points par projet)
        $points += $this->responsibleProjects()->count() * 10;
        
        // Points pour les projets membres (5 points par projet)
        $points += $this->projects()->count() * 5;
        
        // Points pour les tâches terminées (3 points par tâche)
        $points += $this->assignedTasks()
                       ->whereIn('status', ['approved', 'delivered', 'completed'])
                       ->count() * 3;
        
        // Points pour l'ancienneté (1 point par jour)
        $daysSinceJoined = now()->diffInDays($this->created_at);
        $points += floor($daysSinceJoined);
        
        return $points;
    }

    
}