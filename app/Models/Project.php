<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Les attributs qui sont assignables en masse.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'etablissement_id',
        'user_id', // Responsable principal du projet
        'client_id',
        'invoice_id',
        'contract_number',
        'contact_name',
        'contact_email',
        'contact_phone',
        'start_date',
        'end_date',
        'status',
        'priority',
        'estimated_hours',
        'hourly_rate',
        'estimated_budget',
        'actual_hours',
        'actual_cost',
        'progress',
        'metadata',
        'is_active',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    /**
     * Les attributs qui doivent être castés.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'estimated_hours' => 'float',
        'hourly_rate' => 'decimal:2',
        'estimated_budget' => 'decimal:2',
        'actual_hours' => 'float',
        'actual_cost' => 'decimal:2',
        'progress' => 'integer',
        'metadata' => 'array',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Les valeurs par défaut
     */
    protected $attributes = [
        'status' => 'planning',
        'priority' => 'medium',
        'progress' => 0,
        'is_active' => true,
    ];

    /**
     * Constantes pour les statuts
     */
    const STATUS_PLANNING = 'planning';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_ON_HOLD = 'on_hold';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';

    /**
     * Constantes pour les priorités
     */
    const PRIORITY_LOW = 'low';
    const PRIORITY_MEDIUM = 'medium';
    const PRIORITY_HIGH = 'high';
    const PRIORITY_URGENT = 'urgent';

    /**
     * RELATIONS
     */

    /**
     * Relation avec l'établissement propriétaire
     */
    public function etablissement(): BelongsTo
    {
        return $this->belongsTo(Etablissement::class);
    }

    /**
     * Relation avec le responsable principal du projet
     */
    public function responsible(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Alias pour responsible()
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relation avec le client (établissement client)
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Etablissement::class, 'client_id');
    }

    /**
     * Relation avec la facture associée
     */
    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    /**
     * Relation avec les utilisateurs membres du projet (table pivot)
     */
    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'project_user')
                    ->withPivot('role', 'assigned_at')
                    ->withTimestamps();
    }

    /**
     * Alias pour members()
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'project_user')
                    ->withPivot('role', 'assigned_at')
                    ->withTimestamps();
    }

    /**
     * Relation avec les tâches du projet
     */
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class)->orderBy('due_date');
    }

    /**
     * Relation avec les tâches actives
     */
    public function activeTasks(): HasMany
    {
        return $this->hasMany(Task::class)
                    ->where('is_active', true)
                    ->whereNotIn('status', ['approved', 'cancelled', 'completed']);
    }

    /**
     * Relation avec les tâches terminées
     */
    public function completedTasks(): HasMany
    {
        return $this->hasMany(Task::class)
                    ->whereIn('status', ['approved', 'completed']);
    }

    /**
     * Relation avec les commentaires du projet
     */
    public function comments(): HasMany
    {
        return $this->hasMany(ProjectComment::class);
    }

    /**
     * Relation avec les fichiers du projet
     */
    public function files(): HasMany
    {
        return $this->hasMany(ProjectFile::class);
    }

    /**
     * Relation avec l'utilisateur qui a créé le projet
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Relation avec l'utilisateur qui a mis à jour le projet
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * SCOPES
     */

    /**
     * Scope pour les projets actifs
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope pour les projets inactifs
     */
    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    /**
     * Scope par statut
     */
    public function scopeByStatus($query, $status)
    {
        if (is_array($status)) {
            return $query->whereIn('status', $status);
        }
        return $query->where('status', $status);
    }

    /**
     * Scope par priorité
     */
    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    /**
     * Scope par établissement
     */
    public function scopeByEtablissement($query, $etablissementId)
    {
        return $query->where('etablissement_id', $etablissementId);
    }

    /**
     * Scope par client
     */
    public function scopeByClient($query, $clientId)
    {
        return $query->where('client_id', $clientId);
    }

    /**
     * Scope par responsable
     */
    public function scopeByResponsible($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope pour les projets en retard
     */
    public function scopeOverdue($query)
    {
        return $query->whereNotNull('end_date')
                     ->where('end_date', '<', now())
                     ->whereNotIn('status', ['completed', 'cancelled']);
    }

    /**
     * Scope pour les projets qui commencent bientôt
     */
    public function scopeStartingSoon($query, $days = 7)
    {
        return $query->whereNotNull('start_date')
                     ->whereBetween('start_date', [now(), now()->addDays($days)]);
    }

    /**
     * Scope pour les projets qui se terminent bientôt
     */
    public function scopeEndingSoon($query, $days = 7)
    {
        return $query->whereNotNull('end_date')
                     ->whereBetween('end_date', [now(), now()->addDays($days)])
                     ->whereNotIn('status', ['completed', 'cancelled']);
    }

    /**
     * Scope pour la recherche
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('contract_number', 'like', "%{$search}%")
              ->orWhere('contact_name', 'like', "%{$search}%")
              ->orWhere('contact_email', 'like', "%{$search}%");
        });
    }

    /**
     * ACCESSORS & MUTATORS
     */

    /**
     * Obtenir le libellé du statut formaté
     */
    public function getFormattedStatusAttribute(): string
    {
        return match($this->status) {
            self::STATUS_PLANNING => 'Planification',
            self::STATUS_IN_PROGRESS => 'En cours',
            self::STATUS_ON_HOLD => 'En pause',
            self::STATUS_COMPLETED => 'Terminé',
            self::STATUS_CANCELLED => 'Annulé',
            default => ucfirst($this->status),
        };
    }

    /**
     * Obtenir la couleur du statut
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            self::STATUS_PLANNING => 'gray',
            self::STATUS_IN_PROGRESS => 'blue',
            self::STATUS_ON_HOLD => 'yellow',
            self::STATUS_COMPLETED => 'green',
            self::STATUS_CANCELLED => 'red',
            default => 'gray',
        };
    }

    /**
     * Obtenir le badge HTML du statut
     */
    public function getStatusBadgeAttribute(): string
    {
        $colors = [
            'planning' => 'secondary',
            'in_progress' => 'primary',
            'on_hold' => 'warning',
            'completed' => 'success',
            'cancelled' => 'danger',
        ];
        
        $color = $colors[$this->status] ?? 'secondary';
        
        return "<span class='badge bg-{$color}'>{$this->formatted_status}</span>";
    }

    /**
     * Obtenir le libellé de la priorité formaté
     */
    public function getFormattedPriorityAttribute(): string
    {
        return match($this->priority) {
            self::PRIORITY_LOW => 'Basse',
            self::PRIORITY_MEDIUM => 'Moyenne',
            self::PRIORITY_HIGH => 'Haute',
            self::PRIORITY_URGENT => 'Urgente',
            default => ucfirst($this->priority),
        };
    }

    /**
     * Obtenir la couleur de la priorité
     */
    public function getPriorityColorAttribute(): string
    {
        return match($this->priority) {
            self::PRIORITY_LOW => 'success',
            self::PRIORITY_MEDIUM => 'info',
            self::PRIORITY_HIGH => 'warning',
            self::PRIORITY_URGENT => 'danger',
            default => 'secondary',
        };
    }

    /**
     * Calculer l'avancement du projet basé sur les tâches
     */
    public function getCalculatedProgressAttribute(): int
    {
        $totalTasks = $this->tasks()->count();
        
        if ($totalTasks === 0) {
            return $this->progress ?? 0;
        }
        
        $completedTasks = $this->tasks()
                               ->whereIn('status', ['approved', 'completed', 'delivered'])
                               ->count();
        
        return round(($completedTasks / $totalTasks) * 100);
    }

    /**
     * Mettre à jour l'avancement automatiquement
     */
    public function updateProgress(): void
    {
        $this->progress = $this->calculated_progress;
        $this->save();
    }

    /**
     * Vérifier si le projet est en retard
     */
    public function isOverdue(): bool
    {
        if (!$this->end_date || in_array($this->status, [self::STATUS_COMPLETED, self::STATUS_CANCELLED])) {
            return false;
        }
        
        return $this->end_date->isPast();
    }

    /**
     * Obtenir les jours restants avant échéance
     */
    public function getDaysRemainingAttribute(): ?int
    {
        if (!$this->end_date || $this->isOverdue() || $this->status === self::STATUS_COMPLETED) {
            return null;
        }
        
        return now()->diffInDays($this->end_date, false);
    }

    /**
     * Obtenir la durée totale du projet en jours
     */
    public function getDurationAttribute(): ?int
    {
        if (!$this->start_date || !$this->end_date) {
            return null;
        }
        
        return $this->start_date->diffInDays($this->end_date);
    }

    /**
     * Calculer le coût réel total
     */
    public function getTotalActualCostAttribute(): float
    {
        return $this->tasks()->sum('actual_cost') ?? $this->actual_cost ?? 0;
    }

    /**
     * Calculer la variance budgétaire
     */
    public function getBudgetVarianceAttribute(): float
    {
        if (!$this->estimated_budget) {
            return 0;
        }
        
        return $this->total_actual_cost - $this->estimated_budget;
    }

    /**
     * Vérifier si le projet est en dépassement budgétaire
     */
    public function isOverBudget(): bool
    {
        return $this->budget_variance > 0;
    }

    /**
     * MÉTHODES PERSONNALISÉES
     */

    /**
     * Ajouter un membre au projet
     */
    public function addMember(User $user, string $role = 'member'): void
    {
        $this->members()->syncWithoutDetaching([
            $user->id => [
                'role' => $role,
                'assigned_at' => now(),
            ]
        ]);
    }

    /**
     * Retirer un membre du projet
     */
    public function removeMember(User $user): void
    {
        $this->members()->detach($user->id);
    }

    /**
     * Vérifier si un utilisateur est membre
     */
    public function hasMember(User $user): bool
    {
        return $this->members()->where('user_id', $user->id)->exists();
    }

    /**
     * Obtenir le rôle d'un membre
     */
    public function getMemberRole(User $user): ?string
    {
        $member = $this->members()->where('user_id', $user->id)->first();
        
        return $member ? $member->pivot->role : null;
    }

    /**
     * Marquer le projet comme terminé
     */
    public function markAsCompleted(): bool
    {
        if ($this->status === self::STATUS_COMPLETED) {
            return false;
        }
        
        $this->status = self::STATUS_COMPLETED;
        $this->progress = 100;
        
        return $this->save();
    }

    /**
     * Obtenir les statistiques du projet
     */
    public function getStats(): array
    {
        $totalTasks = $this->tasks()->count();
        $completedTasks = $this->completedTasks()->count();
        $activeTasks = $this->activeTasks()->count();
        
        return [
            'total_tasks' => $totalTasks,
            'completed_tasks' => $completedTasks,
            'active_tasks' => $activeTasks,
            'pending_tasks' => $totalTasks - $completedTasks - $activeTasks,
            'progress' => $this->calculated_progress,
            'members_count' => $this->members()->count(),
            'total_hours' => $this->tasks()->sum('estimated_hours'),
            'actual_hours' => $this->tasks()->sum('actual_hours'),
            'total_cost' => $this->total_actual_cost,
            'budget_variance' => $this->budget_variance,
            'is_overdue' => $this->isOverdue(),
            'days_remaining' => $this->days_remaining,
        ];
    }
}