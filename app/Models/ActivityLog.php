<?php
// app/Models/ActivityLog.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ActivityLog extends Model
{
    use HasFactory;

    /**
     * Le nom de la table associée au modèle.
     *
     * @var string
     */
    protected $table = 'activity_logs';

    /**
     * Les attributs qui sont assignables en masse.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'action',
        'description',
        'subject_type',
        'subject_id',
        'ip_address',
        'user_agent',
        'metadata',
        'created_at',
        'updated_at',
    ];

    /**
     * Les attributs qui doivent être castés.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'metadata' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relation avec l'utilisateur qui a effectué l'action
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation polymorphique avec le sujet de l'activité
     * (peut être un projet, une tâche, etc.)
     */
    public function subject(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Scope pour les actions récentes
     */
    public function scopeRecent($query, $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    /**
     * Scope pour un utilisateur spécifique
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope pour une action spécifique
     */
    public function scopeAction($query, $action)
    {
        return $query->where('action', $action);
    }

    /**
     * Scope pour un type de sujet spécifique
     */
    public function scopeForSubjectType($query, $subjectType)
    {
        return $query->where('subject_type', $subjectType);
    }

    /**
     * Obtenir le libellé formaté de l'action
     */
    public function getFormattedActionAttribute(): string
    {
        $actions = [
            'profile_update' => 'Mise à jour du profil',
            'avatar_update' => 'Changement d\'avatar',
            'password_change' => 'Changement de mot de passe',
            'preferences_update' => 'Mise à jour des préférences',
            '2fa_toggle' => 'Modification 2FA',
            'session_revoked' => 'Session révoquée',
            'login' => 'Connexion',
            'logout' => 'Déconnexion',
            'project_created' => 'Création de projet',
            'project_updated' => 'Mise à jour de projet',
            'project_deleted' => 'Suppression de projet',
            'task_created' => 'Création de tâche',
            'task_updated' => 'Mise à jour de tâche',
            'task_completed' => 'Tâche terminée',
            'comment_added' => 'Commentaire ajouté',
            'file_uploaded' => 'Fichier uploadé',
        ];

        return $actions[$this->action] ?? ucfirst(str_replace('_', ' ', $this->action));
    }

    /**
     * Obtenir l'icône correspondant à l'action
     */
    public function getActionIconAttribute(): string
    {
        $icons = [
            'profile_update' => 'user-edit',
            'avatar_update' => 'camera',
            'password_change' => 'key',
            'preferences_update' => 'sliders-h',
            '2fa_toggle' => 'shield-alt',
            'session_revoked' => 'sign-out-alt',
            'login' => 'sign-in-alt',
            'logout' => 'sign-out-alt',
            'project_created' => 'project-diagram',
            'project_updated' => 'edit',
            'project_deleted' => 'trash',
            'task_created' => 'tasks',
            'task_updated' => 'edit',
            'task_completed' => 'check-circle',
            'comment_added' => 'comment',
            'file_uploaded' => 'file-upload',
        ];

        return $icons[$this->action] ?? 'history';
    }

    /**
     * Obtenir la couleur correspondant à l'action
     */
    public function getActionColorAttribute(): string
    {
        $colors = [
            'profile_update' => 'blue',
            'avatar_update' => 'purple',
            'password_change' => 'orange',
            'preferences_update' => 'indigo',
            '2fa_toggle' => 'teal',
            'session_revoked' => 'red',
            'login' => 'green',
            'logout' => 'gray',
            'project_created' => 'primary',
            'project_updated' => 'warning',
            'project_deleted' => 'danger',
            'task_created' => 'info',
            'task_updated' => 'warning',
            'task_completed' => 'success',
            'comment_added' => 'secondary',
            'file_uploaded' => 'dark',
        ];

        return $colors[$this->action] ?? 'secondary';
    }

    /**
     * Enregistrer une activité
     */
    public static function log($userId, $action, $description = null, $subject = null, $metadata = [])
    {
        return self::create([
            'user_id' => $userId,
            'action' => $action,
            'description' => $description,
            'subject_type' => $subject ? get_class($subject) : null,
            'subject_id' => $subject ? $subject->id : null,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'metadata' => $metadata,
        ]);
    }
}