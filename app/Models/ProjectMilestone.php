<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectMilestone extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'project_id',
        'title',
        'description',
        'due_date',
        'completed_date',
        'amount',
        'status',
        'order',
        'deliverables',
        'metadata'
    ];

    protected $casts = [
        'due_date' => 'date',
        'completed_date' => 'date',
        'amount' => 'decimal:2',
        'deliverables' => 'array',
        'metadata' => 'array'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function getIsCompletedAttribute()
    {
        return $this->status === 'termine';
    }

    public function getIsOverdueAttribute()
    {
        return !$this->is_completed && $this->due_date < now();
    }

    public function complete()
    {
        $this->status = 'termine';
        $this->completed_date = now();
        $this->save();
    }

    public function updateProgress()
    {
        // Logique de mise à jour de l'avancement du projet
        $completed = $this->project->milestones()->where('status', 'termine')->count();
        $total = $this->project->milestones()->count();
        
        if ($total > 0) {
            $progress = ($completed / $total) * 100;
            $this->project->update(['avancement' => $progress]);
        }
    }
}