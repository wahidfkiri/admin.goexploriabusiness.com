@extends('layouts.app')

@section('content')
    <!-- MAIN CONTENT -->
    <main class="dashboard-content">
        <!-- Page Header -->
        <div class="page-header">
            <div class="d-flex align-items-center">
                <a href="{{ route('projects.index') }}" class="btn btn-outline-secondary me-3">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h1 class="page-title mb-0">
                    <span class="page-title-icon"><i class="fas fa-project-diagram"></i></span>
                    {{ $project->name }}
                </h1>
                @if($project->is_active)
                    <span class="badge bg-success ms-3"><i class="fas fa-check-circle me-1"></i>Actif</span>
                @else
                    <span class="badge bg-secondary ms-3"><i class="fas fa-times-circle me-1"></i>Inactif</span>
                @endif
            </div>
            
            <div class="page-actions">
                <div class="btn-group me-2">
                    <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                        <i class="fas fa-ellipsis-h me-2"></i>Actions
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="{{ route('projects.edit', $project) }}">
                                <i class="fas fa-edit me-2"></i>Modifier
                            </a>
                        </li>
                        <li>
                            <button class="dropdown-item" onclick="duplicateProject({{ $project->id }})">
                                <i class="fas fa-copy me-2"></i>Dupliquer
                            </button>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <button class="dropdown-item" onclick="openCreateTaskModal()">
                                <i class="fas fa-plus-circle me-2"></i>Nouvelle tâche
                            </button>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#" onclick="exportProject({{ $project->id }})">
                                <i class="fas fa-download me-2"></i>Exporter
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <button class="dropdown-item text-danger" onclick="confirmDelete({{ $project->id }})">
                                <i class="fas fa-trash me-2"></i>Supprimer
                            </button>
                        </li>
                    </ul>
                </div>
                
                <button class="btn btn-primary" onclick="updateStatus({{ $project->id }})">
                    <i class="fas fa-sync-alt me-2"></i>Mettre à jour le statut
                </button>
            </div>
        </div>

        <!-- Project Stats Cards -->
        <div class="stats-grid mb-4">
            <div class="stats-card-modern">
                <div class="stats-header-modern">
                    <div>
                        <div class="stats-value-modern">{{ $stats['total_tasks'] ?? 0 }}</div>
                        <div class="stats-label-modern">Tâches totales</div>
                    </div>
                    <div class="stats-icon-modern" style="background: linear-gradient(135deg, #45b7d1, #3a56e4);">
                        <i class="fas fa-tasks"></i>
                    </div>
                </div>
                <div class="stats-footer">
                    <small class="text-muted">
                        {{ $stats['completed_tasks'] ?? 0 }} terminées
                    </small>
                </div>
            </div>
            
            <div class="stats-card-modern">
                <div class="stats-header-modern">
                    <div>
                        <div class="stats-value-modern">{{ $stats['total_hours'] ?? 0 }}h</div>
                        <div class="stats-label-modern">Heures estimées</div>
                    </div>
                    <div class="stats-icon-modern" style="background: linear-gradient(135deg, #06b48a, #049a72);">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>
                <div class="stats-footer">
                    <small class="text-muted">Taux: {{ number_format($project->hourly_rate ?? 0, 2) }} €/h</small>
                </div>
            </div>
            
            <div class="stats-card-modern">
                <div class="stats-header-modern">
                    <div>
                        <div class="stats-value-modern">{{ number_format($project->estimated_budget ?? 0, 0, ',', ' ') }} €</div>
                        <div class="stats-label-modern">Budget estimé</div>
                    </div>
                    <div class="stats-icon-modern" style="background: linear-gradient(135deg, #ffd166, #ffb347);">
                        <i class="fas fa-euro-sign"></i>
                    </div>
                </div>
                <div class="stats-footer">
                    <small class="text-muted">Coût réel: {{ number_format($stats['total_cost'] ?? 0, 0, ',', ' ') }} €</small>
                </div>
            </div>
            
            <div class="stats-card-modern">
                <div class="stats-header-modern">
                    <div>
                        <div class="stats-value-modern">{{ $project->progress }}%</div>
                        <div class="stats-label-modern">Avancement</div>
                    </div>
                    <div class="stats-icon-modern" style="background: linear-gradient(135deg, #ef476f, #d4335f);">
                        <i class="fas fa-chart-line"></i>
                    </div>
                </div>
                <div class="stats-footer">
                    <div class="progress-modern" style="height: 4px;">
                        <div class="progress-bar-modern" style="width: {{ $project->progress }}%; background: {{ $project->progress > 70 ? '#06b48a' : ($project->progress > 30 ? '#ffd166' : '#ef476f') }}"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="row">
            <!-- Left Column - Project Info -->
            <div class="col-lg-4">
                <!-- Project Info Card -->
                <div class="info-card-modern mb-4">
                    <div class="info-card-header">
                        <h5 class="info-card-title">
                            <i class="fas fa-info-circle me-2"></i>
                            Informations générales
                        </h5>
                    </div>
                    <div class="info-card-body">
                        <div class="info-item">
                            <div class="info-label">Statut</div>
                            <div class="info-value">
                                <span class="status-badge" style="background: {{ $project->status_color === 'success' ? '#06b48a' : ($project->status_color === 'danger' ? '#ef476f' : '#ffd166') }}">
                                    <i class="fas fa-circle me-1" style="font-size: 0.5rem;"></i>
                                    {{ $project->formatted_status }}
                                </span>
                            </div>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-label">Responsable</div>
                            <div class="info-value">
                                @if($project->user)
                                    <div class="d-flex align-items-center">
                                        <div class="user-avatar-sm me-2" style="background: {{ \App\Helpers\ViewHelper::getAvatarColor($project->user->name) }}">
                                            {{ \App\Helpers\ViewHelper::getInitials($project->user->name) }}
                                        </div>
                                        <div>
                                            <div>{{ $project->user->name }}</div>
                                            <small class="text-muted">{{ $project->user->email }}</small>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-muted">Non assigné</span>
                                @endif
                            </div>
                        </div>
                        
                        @if($project->client)
                        <div class="info-item">
                            <div class="info-label">Client</div>
                            <div class="info-value">
                                <div class="d-flex align-items-center">
                                    <div class="client-icon-sm me-2">
                                        <i class="fas fa-building"></i>
                                    </div>
                                    <div>
                                        <div>{{ $project->client->name }}</div>
                                        <small class="text-muted">{{ $project->client->ville }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        @if($project->contract_number)
                        <div class="info-item">
                            <div class="info-label">N° Contrat</div>
                            <div class="info-value">
                                <span class="contract-badge">{{ $project->contract_number }}</span>
                            </div>
                        </div>
                        @endif
                        
                        <div class="info-item">
                            <div class="info-label">Dates</div>
                            <div class="info-value">
                                <div><i class="fas fa-calendar-alt me-2 text-muted"></i>Début: {{ $project->start_date ? $project->start_date->format('d/m/Y') : 'Non définie' }}</div>
                                <div><i class="fas fa-calendar-check me-2 text-muted"></i>Fin: {{ $project->end_date ? $project->end_date->format('d/m/Y') : 'Non définie' }}</div>
                                @if($project->end_date && !in_array($project->status, ['completed', 'cancelled']))
                                    <div class="mt-2">
                                        @php
                                          $daysRemaining = intval(now()->diffInDays($project->end_date, false));
                                        @endphp
                                        @if($daysRemaining < 0)
                                            <span class="badge bg-danger">En retard de {{ abs($daysRemaining) }} jours</span>
                                        @elseif($daysRemaining <= 7)
                                            <span class="badge bg-warning">{{ $daysRemaining }} jours restants</span>
                                        @else
                                            <span class="badge bg-info">{{ $daysRemaining }} jours restants</span>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                        
                        @if($project->tags)
                        <div class="info-item">
                            <div class="info-label">Tags</div>
                            <div class="info-value">
                                <div class="tags-container">
                                    @foreach(explode(',', $project->tags) as $tag)
                                        <span class="tag">{{ trim($tag) }}</span>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Contact Info Card -->
                @if($project->contact_name || $project->client)
                <div class="info-card-modern mb-4">
                    <div class="info-card-header">
                        <h5 class="info-card-title">
                            <i class="fas fa-address-card me-2"></i>
                            Contact
                        </h5>
                    </div>
                    <div class="info-card-body">
                        @if($project->contact_name)
                        <div class="info-item">
                            <div class="info-label">Nom</div>
                            <div class="info-value">{{ $project->contact_name }}</div>
                        </div>
                        @endif
                        
                        @if($project->contact_phone)
                        <div class="info-item">
                            <div class="info-label">Téléphone</div>
                            <div class="info-value">
                                <a href="tel:{{ $project->contact_phone }}" class="contact-link">
                                    <i class="fas fa-phone me-2"></i>{{ $project->contact_phone }}
                                </a>
                            </div>
                        </div>
                        @endif
                        
                        @if($project->contact_email)
                        <div class="info-item">
                            <div class="info-label">Email</div>
                            <div class="info-value">
                                <a href="mailto:{{ $project->contact_email }}" class="contact-link">
                                    <i class="fas fa-envelope me-2"></i>{{ $project->contact_email }}
                                </a>
                            </div>
                        </div>
                        @endif
                        
                        @if($project->client)
                        <div class="info-item mt-3 pt-3 border-top">
                            <div class="info-value">
                                <a href="{{ route('etablissements.show', $project->client_id) }}" class="btn btn-outline-primary btn-sm w-100">
                                    <i class="fas fa-building me-2"></i>Voir la fiche client
                                </a>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Metadata Card -->
                <div class="info-card-modern">
                    <div class="info-card-header">
                        <h5 class="info-card-title">
                            <i class="fas fa-cog me-2"></i>
                            Métadonnées
                        </h5>
                    </div>
                    <div class="info-card-body">
                        <div class="info-item">
                            <div class="info-label">Créé par</div>
                            <div class="info-value">{{ $project->user->name ?? 'N/A' }}</div>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-label">Créé le</div>
                            <div class="info-value">{{ $project->created_at->format('d/m/Y à H:i') }}</div>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-label">Dernière modif.</div>
                            <div class="info-value">{{ $project->updated_at->format('d/m/Y à H:i') }}</div>
                        </div>
                        
                        @if($project->metadata)
                            @php $metadata = json_decode($project->metadata, true); @endphp
                            @if(!empty($metadata['priority']))
                            <div class="info-item">
                                <div class="info-label">Priorité</div>
                                <div class="info-value">
                                    @if($metadata['priority'] === 'urgent')
                                        <span class="badge bg-danger">Urgente</span>
                                    @elseif($metadata['priority'] === 'high')
                                        <span class="badge bg-warning">Haute</span>
                                    @elseif($metadata['priority'] === 'medium')
                                        <span class="badge bg-info">Moyenne</span>
                                    @else
                                        <span class="badge bg-secondary">Basse</span>
                                    @endif
                                </div>
                            </div>
                            @endif
                        @endif
                    </div>
                </div>

                <!-- Files Card -->
<div class="info-card-modern mt-4">
    <div class="info-card-header d-flex justify-content-between align-items-center">
        <h5 class="info-card-title mb-0">
            <i class="fas fa-paperclip me-2"></i>
            Fichiers du projet
        </h5>
        <span class="badge bg-primary" id="filesCount">{{ $project->files->count() }}</span>
    </div>
    <div class="info-card-body">
        <div class="files-container">
            @if($project->files && $project->files->count() > 0)
                <div class="files-list" style="max-height: 300px; overflow-y: auto;">
                    @foreach($project->files as $file)
                        <div class="file-item d-flex align-items-center p-2 mb-2 bg-light rounded" id="project-file-{{ $file->id }}">
                            <div class="file-icon me-3">
                                <i class="{{ \App\Helpers\ViewHelper::getFileIcon($file->file_extension ?? pathinfo($file->file_name, PATHINFO_EXTENSION)) }} fa-2x"></i>
                            </div>
                            <div class="file-info flex-grow-1">
                                <div class="file-name fw-bold">{{ $file->file_name }}</div>
                                <div class="file-meta small text-muted">
                                    <span><i class="fas fa-user me-1"></i>{{ $file->uploader->name ?? 'N/A' }}</span>
                                    <span class="mx-2">•</span>
                                    <span><i class="fas fa-calendar me-1"></i>{{ $file->created_at->format('d/m/Y') }}</span>
                                    <span class="mx-2">•</span>
                                    <span><i class="fas fa-weight me-1"></i>{{ \App\Helpers\ViewHelper::formatBytes($file->file_size) }}</span>
                                </div>
                            </div>
                            <div class="file-actions">
                                <a href="{{ route('projects.files.download', [$project->id, $file->id]) }}" class="btn btn-sm btn-outline-primary me-1" title="Télécharger">
                                    <i class="fas fa-download"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteProjectFile({{ $file->id }})" title="Supprimer">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                @if($project->files->count() > 5)
                    <div class="text-center mt-3">
                        <a href="#" class="btn btn-link" onclick="showAllFiles()">
                            Voir tous les fichiers ({{ $project->files->count() }})
                            <i class="fas fa-arrow-right ms-2"></i>
                        </a>
                    </div>
                @endif
            @else
                <div class="empty-state-modern text-center py-4">
                    <div class="empty-icon-modern mb-3">
                        <i class="fas fa-file-upload fa-3x text-muted"></i>
                    </div>
                    <p class="text-muted">Aucun fichier attaché à ce projet</p>
                    <button onclick="openUploadFileModal()" class="btn btn-sm btn-primary">
                        <i class="fas fa-upload me-2"></i>Ajouter des fichiers
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>
            </div>

            <!-- Right Column - Description & Tasks -->
            <div class="col-lg-8">
                <!-- Description Card -->
<div class="info-card-modern mb-4">
    <div class="info-card-header">
        <h5 class="info-card-title">
            <i class="fas fa-align-left me-2"></i>
            Description
        </h5>
    </div>
    <div class="info-card-body">
        <div class="project-description">
            @if($project->description)
                @php
                    // Limiter à 200 caractères pour l'affichage court
                    $shortDescription = Str::limit(strip_tags($project->description), 200);
                @endphp
                <div class="description-short">
                    {!! nl2br(e($shortDescription)) !!}
                </div>
                @if(strlen(strip_tags($project->description)) > 200)
                    <div class="text-end mt-3">
                        <button type="button" class="btn btn-link p-0" onclick="openDescriptionModal()">
                            Afficher plus <i class="fas fa-arrow-right ms-1"></i>
                        </button>
                    </div>
                @endif
            @else
                <p class="text-muted fst-italic">Aucune description fournie</p>
            @endif
        </div>
    </div>
</div>

                <!-- Tasks Card -->
                <div class="info-card-modern">
                    <div class="info-card-header d-flex justify-content-between align-items-center">
                        <h5 class="info-card-title mb-0">
                            <i class="fas fa-tasks me-2"></i>
                            Tâches ({{ $stats['total_tasks'] }})
                        </h5>
                        <div class="d-flex gap-2">
                            <div class="btn-group btn-group-sm">
                                <button type="button" class="btn btn-outline-secondary" onclick="filterTasks('all')">Toutes</button>
                                <button type="button" class="btn btn-outline-secondary" onclick="filterTasks('pending')">En cours</button>
                                <button type="button" class="btn btn-outline-secondary" onclick="filterTasks('completed')">Terminées</button>
                            </div>
                            <button onclick="openCreateTaskModal()" class="btn btn-sm btn-primary">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="info-card-body p-0">
                        @if($recentTasks->count() > 0)
                            <div class="task-list">
                                @foreach($recentTasks as $task)
                                    <div class="task-item" data-status="{{ $task->status }}" id="task-{{ $task->id }}">
                                        <div class="task-item-content">
                                            <div class="task-checkbox">
                                                <input type="checkbox" 
                                                       class="task-check" 
                                                       {{ $task->status === 'approved' ? 'checked' : '' }}
                                                       onchange="toggleTaskStatus({{ $task->id }}, this.checked)">
                                            </div>
                                            <div class="task-info">
                                                <div class="d-flex align-items-center gap-2">
                                                    <span class="task-name {{ $task->status === 'approved' ? 'task-completed' : '' }}">
                                                        <a href="javascript:void(0)" onclick="openEditTaskModal({{ $task->id }})">{{ $task->name }}</a>
                                                    </span>
                                                    <span class="task-badge status-{{ $task->status }}">
                                                        {{ $task->formatted_status }}
                                                    </span>
                                                </div>
                                                <div class="task-meta">
                                                    <span><i class="fas fa-user me-1"></i>{{ $task->user->name ?? 'Non assigné' }}</span>
                                                    @if($task->due_date)
                                                        <span><i class="fas fa-calendar me-1"></i>{{ $task->due_date->format('d/m/Y') }}</span>
                                                    @endif
                                                    <span><i class="fas fa-clock me-1"></i>{{ $task->estimated_hours ?? 0 }}h</span>
                                                </div>
                                            </div>
                                            <div class="task-actions">
                                                <button class="task-action-btn" onclick="openEditTaskModal({{ $task->id }})" title="Modifier">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <a href="{{route('tasks.show', $task->id)}}" class="task-action-btn" title="Voir Détails">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <button class="task-action-btn" onclick="deleteTask({{ $task->id }})" title="Supprimer">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            
                            @if($project->tasks->count() > 5)
                                <div class="text-center p-3 border-top">
                                    <a href="{{ route('projects.tasks', $project) }}" class="btn btn-link">
                                        Voir toutes les tâches ({{ $project->tasks->count() }})
                                        <i class="fas fa-arrow-right ms-2"></i>
                                    </a>
                                </div>
                            @endif
                        @else
                            <div class="empty-state-modern p-5">
                                <div class="empty-icon-modern">
                                    <i class="fas fa-tasks"></i>
                                </div>
                                <h3 class="empty-title-modern">Aucune tâche</h3>
                                <p class="empty-text-modern">Commencez par créer une tâche pour ce projet.</p>
                                <button onclick="openCreateTaskModal()" class="btn btn-primary">
                                    <i class="fas fa-plus-circle me-2"></i>Créer une tâche
                                </button>
                            </div>
                        @endif
                    </div>
                </div>

               <!-- Timeline Card -->
@if(isset($activities) && $activities->count() > 0)
<div class="info-card-modern mt-4">
    <div class="info-card-header">
        <h5 class="info-card-title">
            <i class="fas fa-history me-2"></i>
            Activités récentes
        </h5>
    </div>
    <div class="info-card-body">
        <div class="timeline">
            @foreach($activities as $activity)
                @php
                    // Raccourcir la description à 30 caractères
                    $fullDescription = $activity->description;
                    $shortDescription = Str::limit(strip_tags($fullDescription), 30);
                    $hasMore = strlen(strip_tags($fullDescription)) > 30;
                @endphp
                <div class="timeline-item" data-activity-id="{{ $activity->id }}">
                    <div class="timeline-icon" style="background: {{ \App\Helpers\ViewHelper::getActivityColor($activity->description) }}">
                        <i class="fas {{ \App\Helpers\ViewHelper::getActivityIcon($activity->description) }}"></i>
                    </div>
                    <div class="timeline-content">
                        <div class="timeline-header">
                            <span class="timeline-title">
                                {!! $shortDescription !!}
                                @if($hasMore)
                                    <button type="button" class="btn btn-link p-0 ms-2" onclick="showActivityDetails({{ $activity->id }})" style="font-size: 0.8rem;">
                                        <i class="fas fa-eye me-1"></i>Voir plus
                                    </button>
                                @endif
                            </span>
                            <span class="timeline-time">{{ $activity->created_at->diffForHumans() }}</span>
                        </div>
                        <div class="timeline-body">
                            <i class="fas fa-user me-1"></i>{{ $activity->causer->name ?? 'Système' }}
                            @if($activity->properties && count($activity->properties) > 0)
                                <span class="badge bg-info ms-2" style="font-size: 0.7rem;">
                                    <i class="fas fa-tag me-1"></i>{{ count($activity->properties) }} modif.
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        @if(isset($activities) && $activities->count() > 5)
            <div class="text-center mt-3">
                <a href="#" class="btn btn-link" onclick="showAllActivities()">
                    Voir toutes les activités
                    <i class="fas fa-arrow-right ms-2"></i>
                </a>
            </div>
        @endif
    </div>
</div>
@endif
            </div>
        </div>
    </main>

    <!-- CREATE TASK MODAL -->
<div class="modal fade" id="createTaskModal" tabindex="-1" aria-labelledby="createTaskModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createTaskModalLabel">
                    <i class="fas fa-plus-circle me-2" style="color: #45b7d1;"></i>
                    Nouvelle tâche
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="createTaskForm" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="project_id" value="{{ $project->id }}">
                <div class="modal-body">
                    <!-- Tabs Navigation -->
                    <ul class="nav nav-tabs mb-4" id="createTaskTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="create-info-tab" data-bs-toggle="tab" data-bs-target="#create-info" type="button" role="tab">
                                <i class="fas fa-info-circle me-2"></i>Informations
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="create-files-tab" data-bs-toggle="tab" data-bs-target="#create-files" type="button" role="tab">
                                <i class="fas fa-paperclip me-2"></i>Fichiers
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="create-advanced-tab" data-bs-toggle="tab" data-bs-target="#create-advanced" type="button" role="tab">
                                <i class="fas fa-cog me-2"></i>Avancé
                            </button>
                        </li>
                    </ul>

                    <!-- Tab Content -->
                    <div class="tab-content" id="createTaskTabContent">
                        <!-- Informations Tab -->
                        <div class="tab-pane fade show active" id="create-info" role="tabpanel">
                            <div class="row">
                                <div class="col-md-8 mb-3">
                                    <label for="task_name" class="form-label required-field">Nom de la tâche</label>
                                    <input type="text" class="form-control" id="task_name" name="name" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="task_status" class="form-label">Statut</label>
                                    <select class="form-select" id="task_status" name="status">
                                        <option value="pending">En attente</option>
                                        <option value="in_progress">En cours</option>
                                        <option value="test">En test</option>
                                        <option value="integrated">Intégré</option>
                                        <option value="delivered">Livré</option>
                                        <option value="approved">Approuvé</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="task_description" class="form-label">Description</label>
                                <textarea class="form-control" id="task_description" name="details" rows="3"></textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="task_user_id" class="form-label required-field">Assigné à</label>
                                    <select class="form-select select2-modern" id="task_user_id" name="user_id" required>
                                        <option value="">Sélectionner...</option>
                                        @foreach($users ?? [] as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="task_contact_name" class="form-label">Contact client</label>
                                    <input type="text" class="form-control" id="task_contact_name" name="contact_name" value="{{ $project->contact_name }}">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="task_due_date" class="form-label">Date d'échéance</label>
                                    <input type="datetime-local" class="form-control" id="task_due_date" name="due_date">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="task_delivery_date" class="form-label">Date de livraison</label>
                                    <input type="datetime-local" class="form-control" id="task_delivery_date" name="delivery_date">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="task_estimated_hours" class="form-label">Heures estimées</label>
                                    <input type="number" class="form-control" id="task_estimated_hours" name="estimated_hours" min="0" step="0.5">
                                </div>
                            </div>
                        </div>

                        <!-- Fichiers Tab -->
                        <div class="tab-pane fade" id="create-files" role="tabpanel">
                            <div class="file-upload-area">
                                <div class="dropzone-container mb-3" id="createTaskDropzone">
                                    <div class="dropzone-message">
                                        <i class="fas fa-cloud-upload-alt fa-3x mb-3" style="color: #45b7d1;"></i>
                                        <h5>Déposez vos fichiers ici</h5>
                                        <p class="text-muted mb-2">ou cliquez pour sélectionner</p>
                                        <small class="text-muted">Taille max: 10MB par fichier</small>
                                    </div>
                                    <input type="file" class="file-input" id="create_task_files" name="files[]" multiple style="display: none;" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.jpg,.jpeg,.png,.gif,.zip">
                                </div>

                                <!-- Liste des fichiers sélectionnés -->
                                <div class="selected-files-list" id="createSelectedFiles">
                                    <!-- Les fichiers sélectionnés apparaîtront ici -->
                                </div>

                                <div class="alert alert-info mt-3">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <strong>Formats acceptés :</strong> PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, TXT, JPG, PNG, GIF, ZIP
                                </div>
                            </div>
                        </div>

                        <!-- Avancé Tab -->
                        <div class="tab-pane fade" id="create-advanced" role="tabpanel">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="task_priority" class="form-label">Priorité</label>
                                    <select class="form-select" id="task_priority" name="priority">
                                        <option value="low">Basse</option>
                                        <option value="medium" selected>Moyenne</option>
                                        <option value="high">Haute</option>
                                        <option value="urgent">Urgente</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="task_tags" class="form-label">Tags</label>
                                    <input type="text" class="form-control" id="task_tags" name="tags" placeholder="tag1, tag2, tag3">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="task_country" class="form-label">Pays</label>
                                    <input type="text" class="form-control" id="task_country" name="country" placeholder="France">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="task_location" class="form-label">Lieu</label>
                                    <input type="text" class="form-control" id="task_location" name="location" placeholder="Paris">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="task_contract_number" class="form-label">N° Contrat</label>
                                    <input type="text" class="form-control" id="task_contract_number" name="contract_number" value="{{ $project->contract_number }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="task_hourly_rate" class="form-label">Taux horaire (€)</label>
                                    <input type="number" class="form-control" id="task_hourly_rate" name="hourly_rate" min="0" step="0.01" value="{{ $project->hourly_rate }}">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="task_test_date" class="form-label">Date de test</label>
                                    <input type="datetime-local" class="form-control" id="task_test_date" name="test_date">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="task_integration_date" class="form-label">Date d'intégration</label>
                                    <input type="datetime-local" class="form-control" id="task_integration_date" name="integration_date">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary" id="saveTaskBtn">
                        <i class="fas fa-save me-2"></i>Créer la tâche
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

 <!-- EDIT TASK MODAL -->
<div class="modal fade" id="editTaskModal" tabindex="-1" aria-labelledby="editTaskModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editTaskModalLabel">
                    <i class="fas fa-edit me-2" style="color: #45b7d1;"></i>
                    Modifier la tâche
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editTaskForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" id="edit_task_id" name="task_id">
                <input type="hidden" name="project_id" value="{{ $project->id }}">
                
                <div class="modal-body">
                    <!-- Tabs Navigation -->
                    <ul class="nav nav-tabs mb-4" id="editTaskTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="edit-info-tab" data-bs-toggle="tab" data-bs-target="#edit-info" type="button" role="tab">
                                <i class="fas fa-info-circle me-2"></i>Informations
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="edit-files-tab" data-bs-toggle="tab" data-bs-target="#edit-files" type="button" role="tab">
                                <i class="fas fa-paperclip me-2"></i>Fichiers
                                <span class="badge bg-primary ms-2" id="filesCount">0</span>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="edit-advanced-tab" data-bs-toggle="tab" data-bs-target="#edit-advanced" type="button" role="tab">
                                <i class="fas fa-cog me-2"></i>Avancé
                            </button>
                        </li>
                    </ul>

                    <!-- Tab Content -->
                    <div class="tab-content" id="editTaskTabContent">
                        <!-- Informations Tab -->
                        <div class="tab-pane fade show active" id="edit-info" role="tabpanel">
                            <div class="row">
                                <div class="col-md-8 mb-3">
                                    <label for="edit_task_name" class="form-label required-field">Nom de la tâche</label>
                                    <input type="text" class="form-control" id="edit_task_name" name="name" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="edit_task_status" class="form-label">Statut</label>
                                    <select class="form-select" id="edit_task_status" name="status">
                                        <option value="pending">En attente</option>
                                        <option value="in_progress">En cours</option>
                                        <option value="test">En test</option>
                                        <option value="integrated">Intégré</option>
                                        <option value="delivered">Livré</option>
                                        <option value="approved">Approuvé</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="edit_task_description" class="form-label">Description</label>
                                <textarea class="form-control" id="edit_task_description" name="details" rows="3"></textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="edit_task_user_id" class="form-label required-field">Assigné à</label>
                                    <select class="form-select select2-modern" id="edit_task_user_id" name="user_id" required>
                                        <option value="">Sélectionner...</option>
                                        @foreach($users ?? [] as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="edit_task_contact_name" class="form-label">Contact client</label>
                                    <input type="text" class="form-control" id="edit_task_contact_name" name="contact_name">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="edit_task_due_date" class="form-label">Date d'échéance</label>
                                    <input type="datetime-local" class="form-control" id="edit_task_due_date" name="due_date">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="edit_task_delivery_date" class="form-label">Date de livraison</label>
                                    <input type="datetime-local" class="form-control" id="edit_task_delivery_date" name="delivery_date">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="edit_task_estimated_hours" class="form-label">Heures estimées</label>
                                    <input type="number" class="form-control" id="edit_task_estimated_hours" name="estimated_hours" min="0" step="0.5">
                                </div>
                            </div>
                        </div>

                        <!-- Fichiers Tab -->
                        <div class="tab-pane fade" id="edit-files" role="tabpanel">
                            <!-- Liste des fichiers existants -->
                            <div class="existing-files-section mb-4">
                                <h6 class="mb-3">Fichiers existants</h6>
                                <div id="existingFilesList" class="file-list">
                                    <!-- Les fichiers existants seront chargés ici -->
                                </div>
                            </div>

                            <!-- Upload de nouveaux fichiers -->
                            <div class="new-files-section">
                                <h6 class="mb-3">Ajouter des fichiers</h6>
                                <div class="file-upload-area">
                                    <div class="dropzone-container mb-3" id="editTaskDropzone">
                                        <div class="dropzone-message">
                                            <i class="fas fa-cloud-upload-alt fa-3x mb-3" style="color: #45b7d1;"></i>
                                            <h5>Déposez vos fichiers ici</h5>
                                            <p class="text-muted mb-2">ou cliquez pour sélectionner</p>
                                            <small class="text-muted">Taille max: 10MB par fichier</small>
                                        </div>
                                        <input type="file" class="file-input" id="edit_task_files" name="new_files[]" multiple style="display: none;" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.jpg,.jpeg,.png,.gif,.zip">
                                    </div>

                                    <!-- Liste des nouveaux fichiers sélectionnés -->
                                    <div class="selected-files-list" id="editSelectedFiles">
                                        <!-- Les nouveaux fichiers sélectionnés apparaîtront ici -->
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Avancé Tab -->
                        <div class="tab-pane fade" id="edit-advanced" role="tabpanel">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="edit_task_priority" class="form-label">Priorité</label>
                                    <select class="form-select" id="edit_task_priority" name="priority">
                                        <option value="low">Basse</option>
                                        <option value="medium">Moyenne</option>
                                        <option value="high">Haute</option>
                                        <option value="urgent">Urgente</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="edit_task_tags" class="form-label">Tags</label>
                                    <input type="text" class="form-control" id="edit_task_tags" name="tags" placeholder="tag1, tag2, tag3">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="edit_task_country" class="form-label">Pays</label>
                                    <input type="text" class="form-control" id="edit_task_country" name="country" placeholder="France">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="edit_task_location" class="form-label">Lieu</label>
                                    <input type="text" class="form-control" id="edit_task_location" name="location" placeholder="Paris">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="edit_task_contract_number" class="form-label">N° Contrat</label>
                                    <input type="text" class="form-control" id="edit_task_contract_number" name="contract_number">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="edit_task_hourly_rate" class="form-label">Taux horaire (€)</label>
                                    <input type="number" class="form-control" id="edit_task_hourly_rate" name="hourly_rate" min="0" step="0.01">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="edit_task_test_date" class="form-label">Date de test</label>
                                    <input type="datetime-local" class="form-control" id="edit_task_test_date" name="test_date">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="edit_task_integration_date" class="form-label">Date d'intégration</label>
                                    <input type="datetime-local" class="form-control" id="edit_task_integration_date" name="integration_date">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="edit_task_push_prod_date" class="form-label">Date de MEP</label>
                                    <input type="datetime-local" class="form-control" id="edit_task_push_prod_date" name="push_prod_date">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="edit_task_module_url" class="form-label">URL du module</label>
                                    <input type="url" class="form-control" id="edit_task_module_url" name="module_url" placeholder="https://...">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="edit_task_test_details" class="form-label">Détails du test</label>
                                <textarea class="form-control" id="edit_task_test_details" name="test_details" rows="2"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary" id="updateTaskBtn">
                        <i class="fas fa-save me-2"></i>Mettre à jour
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title">Confirmer la suppression</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <div class="delete-icon mb-3">
                        <i class="fas fa-exclamation-triangle fa-3x" style="color: #ef476f;"></i>
                    </div>
                    <p class="mb-0">Êtes-vous sûr de vouloir supprimer ce projet ?</p>
                    <p class="text-muted small">Cette action est irréversible et supprimera toutes les tâches associées.</p>
                </div>
                <div class="modal-footer border-0 justify-content-center">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Supprimer</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Update Status Modal -->
    <div class="modal fade" id="statusModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title">Mettre à jour le statut</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="statusForm">
                        @csrf
                        @method('PATCH')
                        <div class="mb-3">
                            <label for="new_status" class="form-label">Nouveau statut</label>
                            <select class="form-select" id="new_status" name="status">
                                <option value="planning" {{ $project->status === 'planning' ? 'selected' : '' }}>Planification</option>
                                <option value="in_progress" {{ $project->status === 'in_progress' ? 'selected' : '' }}>En cours</option>
                                <option value="on_hold" {{ $project->status === 'on_hold' ? 'selected' : '' }}>En pause</option>
                                <option value="completed" {{ $project->status === 'completed' ? 'selected' : '' }}>Terminé</option>
                                <option value="cancelled" {{ $project->status === 'cancelled' ? 'selected' : '' }}>Annulé</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                    <button type="button" class="btn btn-primary" onclick="submitStatusUpdate()">Mettre à jour</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Description Modal -->
<div class="modal fade" id="descriptionModal" tabindex="-1" aria-labelledby="descriptionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="descriptionModalLabel">
                    <i class="fas fa-align-left me-2" style="color: #45b7d1;"></i>
                    Description complète - {{ $project->name }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="project-description-full">
                    @if($project->description)
                        {!! $project->description !!}
                    @else
                        <p class="text-muted fst-italic">Aucune description fournie</p>
                    @endif
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

<!-- Upload File Modal -->
<div class="modal fade" id="uploadFileModal" tabindex="-1" aria-labelledby="uploadFileModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadFileModalLabel">
                    <i class="fas fa-upload me-2" style="color: #45b7d1;"></i>
                    Ajouter des fichiers au projet
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="uploadFileForm" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="file-upload-area">
                        <div class="dropzone-container mb-3" id="projectFileDropzone">
                            <div class="dropzone-message">
                                <i class="fas fa-cloud-upload-alt fa-3x mb-3" style="color: #45b7d1;"></i>
                                <h5>Déposez vos fichiers ici</h5>
                                <p class="text-muted mb-2">ou cliquez pour sélectionner</p>
                                <small class="text-muted">Taille max: 10MB par fichier</small>
                            </div>
                            <input type="file" class="file-input" id="project_files" name="files[]" multiple style="display: none;" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.jpg,.jpeg,.png,.gif,.zip">
                        </div>

                        <div class="selected-files-list" id="projectSelectedFiles">
                            <!-- Les fichiers sélectionnés apparaîtront ici -->
                        </div>

                        <div class="alert alert-info mt-3">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Formats acceptés :</strong> PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, TXT, JPG, PNG, GIF, ZIP
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary" id="uploadFileBtn">
                        <i class="fas fa-upload me-2"></i>Uploader
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Activity Details Modal -->
<div class="modal fade" id="activityModal" tabindex="-1" aria-labelledby="activityModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="activityModalLabel">
                    <i class="fas fa-history me-2" style="color: #45b7d1;"></i>
                    Détails de l'activité
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="activity-detail-content">
                    <!-- Les détails seront chargés dynamiquement -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>
<!-- Bibliothèques CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    /* Styles pour la modal d'activité */
.activity-detail-content {
    max-height: 500px;
    overflow-y: auto;
    padding-right: 5px;
}

.activity-detail-content::-webkit-scrollbar {
    width: 5px;
}

.activity-detail-content::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

.activity-detail-content::-webkit-scrollbar-thumb {
    background: #45b7d1;
    border-radius: 10px;
}

.activity-detail-content::-webkit-scrollbar-thumb:hover {
    background: #3a56e4;
}

.activity-detail pre {
    white-space: pre-wrap;
    word-wrap: break-word;
    font-family: 'Courier New', monospace;
}

.timeline-title .btn-link {
    font-size: 0.75rem;
    text-decoration: none;
    color: #45b7d1;
}

.timeline-title .btn-link:hover {
    color: #3a56e4;
    text-decoration: underline;
}
    /* Info Cards */
    .info-card-modern {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        border: 1px solid #eaeaea;
        overflow: hidden;
    }

    .info-card-header {
        padding: 15px 20px;
        background: #f8f9fa;
        border-bottom: 1px solid #eaeaea;
    }

    .info-card-title {
        font-size: 1rem;
        font-weight: 600;
        color: #333;
        margin: 0;
    }

    .info-card-body {
        padding: 20px;
    }

    .info-item {
        display: flex;
        margin-bottom: 15px;
    }

    .info-item:last-child {
        margin-bottom: 0;
    }

    .info-label {
        width: 120px;
        font-size: 0.9rem;
        color: #6c757d;
        font-weight: 500;
    }

    .info-value {
        flex: 1;
        font-size: 0.95rem;
        color: #333;
    }

    /* Status Badge */
    .status-badge {
        display: inline-block;
        padding: 5px 12px;
        border-radius: 20px;
        color: white;
        font-weight: 500;
        font-size: 0.85rem;
    }

    /* Contract Badge */
    .contract-badge {
        background: #f8f9fa;
        padding: 4px 10px;
        border-radius: 4px;
        font-family: monospace;
        font-size: 0.9rem;
        border: 1px solid #e0e0e0;
    }

    /* Tags */
    .tags-container {
        display: flex;
        flex-wrap: wrap;
        gap: 5px;
    }

    .tag {
        background: linear-gradient(135deg, #45b7d1, #3a56e4);
        color: white;
        padding: 3px 10px;
        border-radius: 15px;
        font-size: 0.8rem;
    }

    /* User Avatar */
    .user-avatar-sm {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 600;
        font-size: 0.85rem;
    }

    .client-icon-sm {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        background: linear-gradient(135deg, #45b7d1, #3a56e4);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
    }

    /* Contact Links */
    .contact-link {
        color: #333;
        text-decoration: none;
        transition: color 0.3s ease;
    }

    .contact-link:hover {
        color: #45b7d1;
    }

    /* Project Description */
    .project-description {
        line-height: 1.6;
        color: #333;
    }

    .project-description h1,
    .project-description h2,
    .project-description h3 {
        margin-top: 1rem;
        margin-bottom: 0.5rem;
    }

    .project-description ul,
    .project-description ol {
        padding-left: 1.5rem;
        margin-bottom: 1rem;
    }

    /* Task List */
    .task-list {
        max-height: 400px;
        overflow-y: auto;
    }

    .task-item {
        padding: 15px 20px;
        border-bottom: 1px solid #eaeaea;
        transition: background 0.3s ease;
    }

    .task-item:hover {
        background: #f8f9fa;
    }

    .task-item-content {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .task-checkbox {
        width: 20px;
    }

    .task-check {
        width: 18px;
        height: 18px;
        cursor: pointer;
    }

    .task-info {
        flex: 1;
    }

    .task-name {
        font-weight: 500;
        color: #333;
        text-decoration: none;
        cursor: pointer;
    }

    .task-name a {
        color: inherit;
        text-decoration: none;
    }

    .task-name a:hover {
        color: #45b7d1;
    }

    .task-name:hover {
        color: #45b7d1;
    }

    .task-name.task-completed {
        text-decoration: line-through;
        color: #6c757d;
    }

    .task-badge {
        display: inline-block;
        padding: 2px 8px;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 500;
        color: white;
    }

    .task-badge.status-pending {
        background: #ffd166;
    }

    .task-badge.status-in_progress {
        background: #45b7d1;
    }

    .task-badge.status-test {
        background: #9b59b6;
    }

    .task-badge.status-integrated {
        background: #3498db;
    }

    .task-badge.status-delivered {
        background: #f39c12;
    }

    .task-badge.status-approved {
        background: #06b48a;
    }

    .task-badge.status-cancelled {
        background: #ef476f;
    }

    .task-meta {
        display: flex;
        gap: 15px;
        margin-top: 5px;
        font-size: 0.8rem;
        color: #6c757d;
    }

    .task-actions {
        display: flex;
        gap: 8px;
    }

    .task-action-btn {
        width: 28px;
        height: 28px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: none;
        background: transparent;
        color: #6c757d;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .task-action-btn:hover {
        background: #e9ecef;
        color: #333;
    }

    /* Timeline */
    .timeline {
        position: relative;
        padding-left: 30px;
    }

    .timeline::before {
        content: '';
        position: absolute;
        left: 10px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #e9ecef;
    }

    .timeline-item {
        position: relative;
        margin-bottom: 20px;
    }

    .timeline-item:last-child {
        margin-bottom: 0;
    }

    .timeline-icon {
        position: absolute;
        left: -30px;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 0.85rem;
        z-index: 1;
    }

    .timeline-content {
        padding-left: 15px;
    }

    .timeline-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 5px;
    }

    .timeline-title {
        font-weight: 500;
        color: #333;
    }

    .timeline-time {
        font-size: 0.8rem;
        color: #6c757d;
    }

    .timeline-body {
        font-size: 0.9rem;
        color: #6c757d;
    }

    /* Stats Footer */
    .stats-footer {
        margin-top: 10px;
        padding-top: 10px;
        border-top: 1px solid #eaeaea;
    }

    /* Progress Bar */
    .progress-modern {
        height: 4px;
        background: #e9ecef;
        border-radius: 2px;
        overflow: hidden;
    }

    .progress-bar-modern {
        height: 100%;
        border-radius: 2px;
        transition: width 0.3s ease;
    }

    /* Required field indicator */
    .required-field:after {
        content: " *";
        color: #ef476f;
        font-weight: bold;
    }

    /* Modal styles */
    .modal-content {
        border-radius: 12px;
        border: none;
    }

    .modal-header {
        background: #f8f9fa;
        border-bottom: 1px solid #eaeaea;
        padding: 15px 20px;
    }

    .modal-title {
        font-weight: 600;
        color: #333;
    }

    .modal-body {
        padding: 20px;
    }

    .modal-footer {
        border-top: 1px solid #eaeaea;
        padding: 15px 20px;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .info-item {
            flex-direction: column;
        }
        
        .info-label {
            width: 100%;
            margin-bottom: 5px;
        }
        
        .task-item-content {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .task-actions {
            width: 100%;
            justify-content: flex-end;
        }
        
        .task-meta {
            flex-wrap: wrap;
        }
    }
    /* File Upload Styles */
.file-upload-area {
    background: #f8f9fa;
    border-radius: 12px;
    padding: 20px;
}

.dropzone-container {
    border: 2px dashed #45b7d1;
    border-radius: 12px;
    padding: 40px 20px;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
    background: rgba(69, 183, 209, 0.05);
}

.dropzone-container:hover {
    border-color: #3a56e4;
    background: rgba(69, 183, 209, 0.1);
}

.dropzone-container.dragover {
    border-color: #06b48a;
    background: rgba(6, 180, 138, 0.1);
}

.dropzone-message h5 {
    color: #333;
    margin-bottom: 10px;
}

/* Selected Files List */
.selected-files-list {
    margin-top: 15px;
}

.selected-file-item {
    display: flex;
    align-items: center;
    padding: 10px;
    background: white;
    border-radius: 8px;
    margin-bottom: 8px;
    border: 1px solid #eaeaea;
    animation: slideIn 0.3s ease;
}

.selected-file-item:last-child {
    margin-bottom: 0;
}

.selected-file-icon {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #45b7d1, #3a56e4);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    margin-right: 12px;
}

.selected-file-info {
    flex: 1;
}

.selected-file-name {
    font-weight: 500;
    color: #333;
    margin-bottom: 2px;
}

.selected-file-size {
    font-size: 0.8rem;
    color: #6c757d;
}

.selected-file-remove {
    width: 30px;
    height: 30px;
    border-radius: 6px;
    border: none;
    background: #fee;
    color: #ef476f;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
}

.selected-file-remove:hover {
    background: #ef476f;
    color: white;
}

/* Existing Files List */
.file-list {
    max-height: 300px;
    overflow-y: auto;
}

.existing-file-item {
    display: flex;
    align-items: center;
    padding: 12px;
    background: #f8f9fa;
    border-radius: 8px;
    margin-bottom: 8px;
    border: 1px solid #eaeaea;
}

.existing-file-item:last-child {
    margin-bottom: 0;
}

.existing-file-icon {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #45b7d1, #3a56e4);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    margin-right: 12px;
}

.existing-file-info {
    flex: 1;
}

.existing-file-name {
    font-weight: 500;
    color: #333;
    margin-bottom: 2px;
}

.existing-file-meta {
    font-size: 0.8rem;
    color: #6c757d;
}

.existing-file-actions {
    display: flex;
    gap: 5px;
}

.existing-file-download,
.existing-file-delete {
    width: 30px;
    height: 30px;
    border-radius: 6px;
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
}

.existing-file-download {
    background: #e3f2fd;
    color: #45b7d1;
}

.existing-file-download:hover {
    background: #45b7d1;
    color: white;
}

.existing-file-delete {
    background: #fee;
    color: #ef476f;
}

.existing-file-delete:hover {
    background: #ef476f;
    color: white;
}

.existing-file-delete:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

/* File Progress Bar */
.file-progress {
    margin-top: 10px;
    height: 4px;
    background: #e9ecef;
    border-radius: 2px;
    overflow: hidden;
}

.file-progress-bar {
    height: 100%;
    background: linear-gradient(90deg, #45b7d1, #06b48a);
    transition: width 0.3s ease;
}

/* Animations */
@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes fadeOut {
    from {
        opacity: 1;
    }
    to {
        opacity: 0;
    }
}
@keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes fadeOut {
        from {
            opacity: 1;
        }
        to {
            opacity: 0;
        }
    }
    
    .modal-body.loading {
        position: relative;
        min-height: 200px;
    }
    
    .modal-body.loading::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255,255,255,0.8);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 10;
    }
    
    .modal-body.loading::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 40px;
        height: 40px;
        border: 3px solid #f3f3f3;
        border-top: 3px solid #45b7d1;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        z-index: 11;
    }
    
    @keyframes spin {
        0% { transform: translate(-50%, -50%) rotate(0deg); }
        100% { transform: translate(-50%, -50%) rotate(360deg); }
    }
    @keyframes slideInRight {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

@keyframes fadeOut {
    from {
        opacity: 1;
    }
    to {
        opacity: 0;
    }
}
</style>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    // ============================================
    // CONFIGURATION GLOBALE
    // ============================================
    const projectId = {{ $project->id }};
    let currentTaskId = null;
    
    // Gestionnaires de fichiers
    let createFileManager = null;
    let editFileManager = null;
    let projectFileManager = null;
    
    // Modals
    let deleteModal, statusModal, createTaskModal, editTaskModal, descriptionModal, uploadFileModal, activityModal;

    // ============================================
    // CLASSE DE GESTION DES FICHIERS
    // ============================================
    class FileManager {
        constructor(context) {
            this.context = context; // 'create', 'edit', ou 'project'
            this.selectedFiles = [];
            this.existingFiles = [];
            this.maxSize = 10 * 1024 * 1024; // 10MB
            this.allowedTypes = [
                'application/pdf',
                'application/msword',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'application/vnd.ms-excel',
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'application/vnd.ms-powerpoint',
                'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                'text/plain',
                'image/jpeg',
                'image/png',
                'image/gif',
                'application/zip',
                'application/x-zip-compressed'
            ];
            
            this.init();
        }
        
        init() {
            this.dropzone = document.getElementById(`${this.context}TaskDropzone`) || 
                           document.getElementById(`${this.context}FileDropzone`);
            this.fileInput = document.getElementById(`${this.context}_task_files`) || 
                            document.getElementById(`${this.context}_files`);
            this.selectedList = document.getElementById(`${this.context}SelectedFiles`);
            
            if (!this.dropzone || !this.fileInput) return;
            
            this.bindEvents();
        }
        
        bindEvents() {
            // Click sur la dropzone
            this.dropzone.addEventListener('click', () => {
                this.fileInput.click();
            });
            
            // Drag & Drop
            this.dropzone.addEventListener('dragover', (e) => {
                e.preventDefault();
                this.dropzone.classList.add('dragover');
            });
            
            this.dropzone.addEventListener('dragleave', () => {
                this.dropzone.classList.remove('dragover');
            });
            
            this.dropzone.addEventListener('drop', (e) => {
                e.preventDefault();
                this.dropzone.classList.remove('dragover');
                this.handleFiles(e.dataTransfer.files);
            });
            
            // Sélection de fichiers
            this.fileInput.addEventListener('change', (e) => {
                this.handleFiles(e.target.files);
            });
        }
        
        handleFiles(files) {
            Array.from(files).forEach(file => {
                if (!this.validateFile(file)) return;
                
                // Vérifier les doublons
                if (this.selectedFiles.some(f => f.name === file.name && f.size === file.size)) {
                    this.showNotification('warning', `Le fichier ${file.name} est déjà dans la liste`);
                    return;
                }
                
                this.selectedFiles.push(file);
                this.displayFile(file);
            });
            
            this.updateFileInput();
        }
        
        validateFile(file) {
            // Vérifier la taille
            if (file.size > this.maxSize) {
                this.showNotification('warning', `Le fichier ${file.name} dépasse la taille maximale de 10MB`);
                return false;
            }
            
            // Vérifier le type
            if (!this.allowedTypes.includes(file.type) && file.type !== '') {
                this.showNotification('warning', `Le type du fichier ${file.name} n'est pas autorisé`);
                return false;
            }
            
            return true;
        }
        
        displayFile(file) {
            const fileItem = document.createElement('div');
            fileItem.className = 'selected-file-item';
            fileItem.dataset.fileName = file.name;
            fileItem.dataset.fileSize = file.size;
            
            const fileInfo = this.getFileInfo(file.name);
            
            fileItem.innerHTML = `
                <div class="selected-file-icon">
                    <i class="${fileInfo.icon}"></i>
                </div>
                <div class="selected-file-info">
                    <div class="selected-file-name">${this.escapeHtml(file.name)}</div>
                    <div class="selected-file-size">${this.formatSize(file.size)}</div>
                </div>
                <button type="button" class="selected-file-remove" onclick="window.${this.context}FileManager.removeFile('${this.escapeHtml(file.name)}')">
                    <i class="fas fa-times"></i>
                </button>
            `;
            
            this.selectedList.appendChild(fileItem);
        }
        
        removeFile(fileName) {
            this.selectedFiles = this.selectedFiles.filter(f => f.name !== fileName);
            this.refreshDisplay();
            this.updateFileInput();
        }
        
        refreshDisplay() {
            this.selectedList.innerHTML = '';
            this.selectedFiles.forEach(file => this.displayFile(file));
        }
        
        updateFileInput() {
            const dataTransfer = new DataTransfer();
            this.selectedFiles.forEach(file => dataTransfer.items.add(file));
            this.fileInput.files = dataTransfer.files;
        }
        
        getFileInfo(filename) {
            const extension = filename.split('.').pop().toLowerCase();
            
            const icons = {
                pdf: 'fas fa-file-pdf text-danger',
                doc: 'fas fa-file-word text-primary',
                docx: 'fas fa-file-word text-primary',
                xls: 'fas fa-file-excel text-success',
                xlsx: 'fas fa-file-excel text-success',
                ppt: 'fas fa-file-powerpoint text-warning',
                pptx: 'fas fa-file-powerpoint text-warning',
                jpg: 'fas fa-file-image text-info',
                jpeg: 'fas fa-file-image text-info',
                png: 'fas fa-file-image text-info',
                gif: 'fas fa-file-image text-info',
                zip: 'fas fa-file-archive text-secondary',
                rar: 'fas fa-file-archive text-secondary',
                txt: 'fas fa-file-alt text-muted',
                mp4: 'fas fa-file-video text-danger',
                mp3: 'fas fa-file-audio text-warning'
            };
            
            return {
                icon: icons[extension] || 'fas fa-file text-muted'
            };
        }
        
        formatSize(bytes) {
            if (bytes === 0) return '0 B';
            const units = ['B', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(1024));
            return (bytes / Math.pow(1024, i)).toFixed(2) + ' ' + units[i];
        }
        
        showNotification(type, message) {
            if (typeof window.showNotification === 'function') {
                window.showNotification(type, message);
            } else {
                alert(message);
            }
        }
        
        reset() {
            this.selectedFiles = [];
            if (this.selectedList) {
                this.selectedList.innerHTML = '';
            }
            this.updateFileInput();
        }
        
        escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
        
        // Pour le mode édition uniquement
        async loadExistingFiles(taskId) {
            if (this.context !== 'edit') return;
            
            try {
                const response = await $.ajax({
                    url: `/tasks/${taskId}/files`,
                    type: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                
                if (response.success) {
                    this.existingFiles = response.data;
                    this.displayExistingFiles();
                    $('#filesCount').text(this.existingFiles.length);
                }
            } catch (error) {
                console.error('Error loading files:', error);
            }
        }
        
        displayExistingFiles() {
            const container = document.getElementById('existingFilesList');
            if (!container) return;
            
            if (this.existingFiles.length === 0) {
                container.innerHTML = '<p class="text-muted text-center py-3">Aucun fichier joint</p>';
                return;
            }
            
            let html = '';
            this.existingFiles.forEach(file => {
                html += `
                    <div class="existing-file-item" id="file-${file.id}">
                        <div class="existing-file-icon">
                            <i class="${file.icon || this.getFileInfo(file.name).icon}"></i>
                        </div>
                        <div class="existing-file-info">
                            <div class="existing-file-name">${this.escapeHtml(file.name)}</div>
                            <div class="existing-file-meta">
                                ${this.formatSize(file.size)} • ${file.uploaded_by || 'Système'} • ${file.uploaded_at || ''}
                            </div>
                        </div>
                        <div class="existing-file-actions">
                            <a href="${file.download_url || '#'}" class="existing-file-download" target="_blank" title="Télécharger">
                                <i class="fas fa-download"></i>
                            </a>
                            <button class="existing-file-delete" onclick="window.editFileManager.deleteFile(${file.id})" title="Supprimer">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                `;
            });
            
            container.innerHTML = html;
        }
        
        async deleteFile(fileId) {
            if (!confirm('Êtes-vous sûr de vouloir supprimer ce fichier ?')) return;
            
            const fileElement = document.getElementById(`file-${fileId}`);
            const deleteBtn = fileElement?.querySelector('.existing-file-delete');
            
            if (deleteBtn) {
                deleteBtn.disabled = true;
                deleteBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            }
            
            try {
                const response = await $.ajax({
                    url: `/tasks/${window.currentTaskId}/files/${fileId}`,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                
                if (response.success) {
                    if (fileElement) {
                        fileElement.style.animation = 'fadeOut 0.3s ease';
                        setTimeout(() => {
                            fileElement.remove();
                            this.existingFiles = this.existingFiles.filter(f => f.id !== fileId);
                            $('#filesCount').text(this.existingFiles.length);
                            
                            if (this.existingFiles.length === 0) {
                                document.getElementById('existingFilesList').innerHTML = 
                                    '<p class="text-muted text-center py-3">Aucun fichier joint</p>';
                            }
                        }, 300);
                    }
                    
                    this.showNotification('success', 'Fichier supprimé avec succès');
                }
            } catch (error) {
                console.error('Error deleting file:', error);
                this.showNotification('error', 'Erreur lors de la suppression');
                if (deleteBtn) {
                    deleteBtn.disabled = false;
                    deleteBtn.innerHTML = '<i class="fas fa-trash"></i>';
                }
            }
        }
        
        getFormData() {
            const data = new FormData();
            this.selectedFiles.forEach(file => {
                data.append('files[]', file);
            });
            return data;
        }
    }

    // ============================================
    // GESTION DE LA DESCRIPTION DU PROJET
    // ============================================
    function openDescriptionModal() {
        if (!descriptionModal) {
            descriptionModal = new bootstrap.Modal(document.getElementById('descriptionModal'));
        }
        descriptionModal.show();
    }

    // ============================================
    // GESTION DES FICHIERS DU PROJET
    // ============================================
    
    // Initialiser le gestionnaire de fichiers du projet
    function initProjectFileManager() {
        projectFileManager = new FileManager('project');
    }

    // Ouvrir le modal d'upload
    function openUploadFileModal() {
        if (!uploadFileModal) {
            uploadFileModal = new bootstrap.Modal(document.getElementById('uploadFileModal'));
        }
        
        if (!projectFileManager) {
            initProjectFileManager();
        } else {
            projectFileManager.reset();
        }
        
        uploadFileModal.show();
    }

    // Uploader des fichiers
    async function uploadProjectFiles(e) {
        e.preventDefault();
        
        const formData = new FormData();
        
        if (projectFileManager && projectFileManager.selectedFiles.length > 0) {
            projectFileManager.selectedFiles.forEach(file => {
                formData.append('files[]', file);
            });
        } else {
            showNotification('warning', 'Veuillez sélectionner des fichiers');
            return;
        }
        
        const submitBtn = $('#uploadFileBtn');
        const originalText = submitBtn.html();
        submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Upload...');
        
        try {
            const response = await $.ajax({
                url: `/projects/${projectId}/files`,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            
            if (response.success) {
                uploadFileModal.hide();
                showNotification('success', 'Fichiers uploadés avec succès');
                setTimeout(() => location.reload(), 1500);
            } else {
                showNotification('error', response.message || 'Erreur lors de l\'upload');
            }
        } catch (error) {
            handleAjaxError(error);
        } finally {
            submitBtn.prop('disabled', false).html(originalText);
        }
    }

    // Supprimer un fichier du projet
    async function deleteProjectFile(fileId) {
        if (!confirm('Êtes-vous sûr de vouloir supprimer ce fichier ?')) return;
        
        const fileElement = $(`#project-file-${fileId}`);
        
        try {
            const response = await $.ajax({
                url: `/projects/${projectId}/files/${fileId}`,
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            
            if (response.success) {
                fileElement.fadeOut(300, function() {
                    $(this).remove();
                    showNotification('success', 'Fichier supprimé avec succès');
                    
                    // Mettre à jour le compteur
                    const remainingFiles = $('.file-item').length;
                    $('#filesCount').text(remainingFiles);
                    
                    if (remainingFiles === 0) {
                        location.reload(); // Recharger pour afficher l'état vide
                    }
                });
            }
        } catch (error) {
            handleAjaxError(error);
        }
    }

    // Voir tous les fichiers
    function showAllFiles() {
        showNotification('info', 'Fonctionnalité à venir');
    }

    // ============================================
    // GESTION DES ACTIVITÉS
    // ============================================
    
    // Initialiser la modal des activités
    function initActivityModal() {
        if (!activityModal) {
            activityModal = new bootstrap.Modal(document.getElementById('activityModal'));
        }
    }

    // Afficher les détails d'une activité
    async function showActivityDetails(activityId) {
        initActivityModal();
        
        // Afficher un indicateur de chargement
        const modalBody = $('#activityModal .modal-body .activity-detail-content');
        modalBody.html(`
            <div class="text-center py-4">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Chargement...</span>
                </div>
                <p class="mt-2 text-muted">Chargement des détails...</p>
            </div>
        `);
        
        activityModal.show();
        
        try {
            // Récupérer les détails de l'activité
            const response = await $.ajax({
                url: `/activities/${activityId}`,
                type: 'GET',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            
            if (response.success) {
                displayActivityDetails(response.data);
            } else {
                modalBody.html(`
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        Erreur lors du chargement des détails
                    </div>
                `);
            }
        } catch (error) {
            console.error('Error loading activity:', error);
            modalBody.html(`
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    Impossible de charger les détails de l'activité
                </div>
            `);
        }
    }

    // Afficher les détails dans la modal
    function displayActivityDetails(activity) {
        const modalBody = $('#activityModal .modal-body .activity-detail-content');
        
        // Formater la date
        const createdAt = new Date(activity.created_at).toLocaleString('fr-FR', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
        
        let propertiesHtml = '';
        if (activity.properties && Object.keys(activity.properties).length > 0) {
            propertiesHtml = '<div class="mt-3"><h6 class="mb-2">Modifications :</h6><div class="bg-light p-3 rounded">';
            
            // Afficher les anciennes et nouvelles valeurs si disponibles
            if (activity.properties.old && activity.properties.attributes) {
                propertiesHtml += '<div class="mb-3"><h6 class="mb-2">Changements :</h6>';
                
                const oldValues = activity.properties.old;
                const newValues = activity.properties.attributes;
                
                for (let [key, newValue] of Object.entries(newValues)) {
                    if (oldValues[key] !== undefined && oldValues[key] !== newValue) {
                        // Formater le nom du champ
                        const fieldName = key.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
                        
                        // Formater les valeurs
                        let oldVal = oldValues[key];
                        let newVal = newValue;
                        
                        if (oldVal === null || oldVal === '') oldVal = 'vide';
                        if (newVal === null || newVal === '') newVal = 'vide';
                        
                        propertiesHtml += `
                            <div class="mb-3 p-2 border-start border-3 border-primary">
                                <strong class="d-block mb-2">${fieldName}:</strong>
                                <div class="row">
                                    <div class="col-5 text-end">
                                        <span class="badge bg-danger bg-opacity-10 text-danger p-2">
                                            <i class="fas fa-arrow-left me-1"></i>${oldVal}
                                        </span>
                                    </div>
                                    <div class="col-2 text-center">
                                        <i class="fas fa-arrow-right text-muted"></i>
                                    </div>
                                    <div class="col-5 text-start">
                                        <span class="badge bg-success bg-opacity-10 text-success p-2">
                                            <i class="fas fa-arrow-right me-1"></i>${newVal}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        `;
                    }
                }
                
                propertiesHtml += '</div>';
            } else {
                // Afficher toutes les propriétés
                for (let [key, value] of Object.entries(activity.properties)) {
                    if (key !== 'attributes' && key !== 'old' && typeof value !== 'object') {
                        propertiesHtml += `<div><strong>${key}:</strong> ${value}</div>`;
                    }
                }
            }
            
            propertiesHtml += '</div></div>';
        }
        
        const html = `
            <div class="activity-detail">
                <div class="mb-4">
                    <h6 class="text-muted mb-2">Description complète</h6>
                    <div class="bg-light p-3 rounded">
                        <i class="fas fa-quote-left text-primary me-2" style="opacity: 0.5;"></i>
                        ${activity.description}
                        <i class="fas fa-quote-right text-primary ms-2" style="opacity: 0.5;"></i>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <div class="col-6">
                        <h6 class="text-muted mb-2">
                            <i class="fas fa-user-circle me-1"></i>Auteur
                        </h6>
                        <div class="d-flex align-items-center">
                            <div class="activity-user-avatar me-2" style="background: ${getAvatarColor(activity.causer?.name)}; width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold;">
                                ${getInitials(activity.causer?.name)}
                            </div>
                            <div>
                                <strong>${activity.causer?.name || 'Système'}</strong>
                                <br>
                                <small class="text-muted">${activity.causer?.email || ''}</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <h6 class="text-muted mb-2">
                            <i class="fas fa-clock me-1"></i>Date et heure
                        </h6>
                        <p class="mb-0">
                            <i class="fas fa-calendar-day text-primary me-2"></i>${createdAt}
                        </p>
                        <small class="text-muted">
                            <i class="fas fa-hourglass-half me-1"></i>${timeAgo(activity.created_at)}
                        </small>
                    </div>
                </div>
                
                <div class="mb-4">
                    <h6 class="text-muted mb-2">
                        <i class="fas fa-tag me-1"></i>Type d'activité
                    </h6>
                    <span class="badge" style="background: ${getActivityColor(activity.description)}; color: white; padding: 8px 15px;">
                        <i class="fas ${getActivityIcon(activity.description)} me-2"></i>
                        ${activity.log_name || 'Activité'}
                    </span>
                </div>
                
                ${propertiesHtml}
                
                <div class="mt-4">
                    <h6 class="text-muted mb-2">
                        <i class="fas fa-code me-1"></i>Données brutes
                        <button class="btn btn-sm btn-link ms-2" onclick="toggleRawData(this)">
                            <i class="fas fa-eye"></i> Afficher
                        </button>
                    </h6>
                    <pre class="bg-dark text-light p-3 rounded" style="font-size: 0.75rem; max-height: 200px; overflow-y: auto; display: none;" id="rawData-${activity.id}">${JSON.stringify(activity, null, 2)}</pre>
                </div>
            </div>
        `;
        
        modalBody.html(html);
    }

    // Basculer l'affichage des données brutes
    function toggleRawData(button) {
        const pre = $(button).closest('div').find('pre');
        const icon = $(button).find('i');
        
        pre.slideToggle(300);
        
        if (pre.is(':visible')) {
            icon.removeClass('fa-eye').addClass('fa-eye-slash');
            $(button).html('<i class="fas fa-eye-slash me-1"></i>Masquer');
        } else {
            icon.removeClass('fa-eye-slash').addClass('fa-eye');
            $(button).html('<i class="fas fa-eye me-1"></i>Afficher');
        }
    }

    // Voir toutes les activités
    function showAllActivities() {
        window.location.href = `/projects/${projectId}/activities`;
    }

    // ============================================
    // FONCTIONS PRINCIPALES DES TÂCHES
    // ============================================
    
    // Ouvrir le modal de création
    function openCreateTaskModal() {
        $('#createTaskForm')[0].reset();
        if (window.createFileManager) {
            window.createFileManager.reset();
        }
        createTaskModal.show();
    }
    
    // Créer une tâche
    async function createTask(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        // Ajouter les fichiers
        if (window.createFileManager) {
            window.createFileManager.selectedFiles.forEach(file => {
                formData.append('files[]', file);
            });
        }
        
        const submitBtn = $('#saveTaskBtn');
        const originalText = submitBtn.html();
        submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Création...');
        
        try {
            const response = await $.ajax({
                url: '{{ route("tasks.store") }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            
            if (response.success) {
                createTaskModal.hide();
                showNotification('success', 'Tâche créée avec succès');
                setTimeout(() => location.reload(), 1500);
            } else {
                showNotification('error', response.message || 'Erreur lors de la création');
            }
        } catch (error) {
            handleAjaxError(error);
        } finally {
            submitBtn.prop('disabled', false).html(originalText);
        }
    }
    
    // Voir une tâche
    function viewTask(taskId) {
        window.location.href = `/tasks/${taskId}`;
    }
    
    // Ouvrir le modal d'édition
    async function openEditTaskModal(taskId) {
        window.currentTaskId = taskId;
        
        $('#editTaskForm')[0].reset();
        $('#editTaskModal .modal-body').addClass('loading');
        
        try {
            const response = await $.ajax({
                url: `/tasks/${taskId}/edit`,
                type: 'GET',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            
            if (response.success) {
                const task = response.data;
                
                // Remplir le formulaire
                $('#edit_task_id').val(task.id);
                $('#edit_task_name').val(task.name);
                $('#edit_task_status').val(task.status);
                $('#edit_task_description').val(task.details);
                $('#edit_task_user_id').val(task.user_id);
                $('#edit_task_contact_name').val(task.contact_name);
                
                if (task.due_date) {
                    $('#edit_task_due_date').val(task.due_date.substring(0, 16));
                }
                if (task.delivery_date) {
                    $('#edit_task_delivery_date').val(task.delivery_date.substring(0, 16));
                }
                
                $('#edit_task_estimated_hours').val(task.estimated_hours);
                $('#edit_task_country').val(task.country);
                $('#edit_task_location').val(task.location);
                $('#edit_task_contract_number').val(task.contract_number);
                $('#edit_task_hourly_rate').val(task.hourly_rate);
                $('#edit_task_priority').val(task.priority || 'medium');
                $('#edit_task_tags').val(task.tags || '');
                
                if (task.test_date) {
                    $('#edit_task_test_date').val(task.test_date.substring(0, 16));
                }
                if (task.integration_date) {
                    $('#edit_task_integration_date').val(task.integration_date.substring(0, 16));
                }
                if (task.push_prod_date) {
                    $('#edit_task_push_prod_date').val(task.push_prod_date.substring(0, 16));
                }
                
                $('#edit_task_module_url').val(task.module_url);
                $('#edit_task_test_details').val(task.test_details);
                
                // Charger les fichiers existants
                if (window.editFileManager) {
                    await window.editFileManager.loadExistingFiles(taskId);
                }
                
                editTaskModal.show();
            }
        } catch (error) {
            handleAjaxError(error);
        } finally {
            $('#editTaskModal .modal-body').removeClass('loading');
        }
    }
    
    // Mettre à jour une tâche
    async function updateTask(e) {
        e.preventDefault();
        
        const taskId = $('#edit_task_id').val();
        const formData = new FormData(this);
        formData.append('_method', 'PUT');
        
        // Ajouter les nouveaux fichiers
        if (window.editFileManager) {
            window.editFileManager.selectedFiles.forEach(file => {
                formData.append('new_files[]', file);
            });
        }
        
        const submitBtn = $('#updateTaskBtn');
        const originalText = submitBtn.html();
        submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Mise à jour...');
        
        try {
            const response = await $.ajax({
                url: `/tasks/${taskId}`,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            
            if (response.success) {
                editTaskModal.hide();
                showNotification('success', 'Tâche mise à jour avec succès');
                setTimeout(() => location.reload(), 1500);
            } else {
                showNotification('error', response.message || 'Erreur lors de la mise à jour');
            }
        } catch (error) {
            handleAjaxError(error);
        } finally {
            submitBtn.prop('disabled', false).html(originalText);
        }
    }
    
    // ============================================
    // FONCTIONS DE GESTION DES TÂCHES
    // ============================================
    
    // Filtrer les tâches
    function filterTasks(filter) {
        $('.task-item').each(function() {
            const status = $(this).data('status');
            
            if (filter === 'all') {
                $(this).show();
            } else if (filter === 'pending') {
                $(this).toggle(status !== 'approved' && status !== 'completed');
            } else if (filter === 'completed') {
                $(this).toggle(status === 'approved' || status === 'completed');
            }
        });
    }
    
    // Changer le statut d'une tâche
    async function toggleTaskStatus(taskId, completed) {
        try {
            const response = await $.ajax({
                url: `/tasks/${taskId}/toggle-status`,
                type: 'PATCH',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    completed: completed
                }
            });
            
            if (response.success) {
                const taskItem = $(`#task-${taskId}`);
                const taskName = taskItem.find('.task-name');
                const taskBadge = taskItem.find('.task-badge');
                
                if (completed) {
                    taskName.addClass('task-completed');
                    taskBadge.text('Approuvé').attr('class', 'task-badge status-approved');
                } else {
                    taskName.removeClass('task-completed');
                    taskBadge.text('En cours').attr('class', 'task-badge status-in_progress');
                }
                
                showNotification('success', 'Statut de la tâche mis à jour');
            }
        } catch (error) {
            console.error('Error:', error);
            showNotification('error', 'Erreur lors de la mise à jour');
        }
    }
    
    // Supprimer une tâche
    async function deleteTask(taskId) {
        if (!confirm('Êtes-vous sûr de vouloir supprimer cette tâche ?')) return;
        
        const taskElement = $(`#task-${taskId}`);
        
        try {
            const response = await $.ajax({
                url: `/tasks/${taskId}`,
                type: 'DELETE',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                }
            });
            
            if (response.success) {
                taskElement.fadeOut(300, function() {
                    $(this).remove();
                    showNotification('success', 'Tâche supprimée avec succès');
                });
            }
        } catch (error) {
            console.error('Error:', error);
            showNotification('error', 'Erreur lors de la suppression');
        }
    }
    
    // ============================================
    // FONCTIONS DE GESTION DES PROJETS
    // ============================================
    
    // Dupliquer un projet
    async function duplicateProject(id) {
        try {
            const response = await $.ajax({
                url: `/projects/${id}/duplicate`,
                type: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                }
            });
            
            if (response.success) {
                showNotification('success', 'Projet dupliqué avec succès');
                setTimeout(() => {
                    window.location.href = response.redirect;
                }, 1500);
            }
        } catch (error) {
            console.error('Error:', error);
            showNotification('error', 'Erreur lors de la duplication');
        }
    }
    
    // Exporter un projet
    function exportProject(id) {
        window.location.href = `/projects/${id}/export`;
    }
    
    // Confirmation de suppression
    function confirmDelete(id) {
        deleteModal.show();
        $('#confirmDeleteBtn').off('click').on('click', () => deleteProject(id));
    }
    
    // Supprimer un projet
    async function deleteProject(id) {
        try {
            const response = await $.ajax({
                url: `/projects/${id}`,
                type: 'DELETE',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                }
            });
            
            if (response.success) {
                deleteModal.hide();
                showNotification('success', 'Projet supprimé avec succès');
                setTimeout(() => {
                    window.location.href = '{{ route("projects.index") }}';
                }, 1500);
            }
        } catch (error) {
            console.error('Error:', error);
            deleteModal.hide();
            showNotification('error', 'Erreur lors de la suppression');
        }
    }
    
    // Mettre à jour le statut
    function updateStatus() {
        statusModal.show();
    }
    
    async function submitStatusUpdate() {
        const newStatus = $('#new_status').val();
        
        try {
            const response = await $.ajax({
                url: `/projects/${projectId}/status`,
                type: 'PATCH',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    status: newStatus
                }
            });
            
            if (response.success) {
                statusModal.hide();
                showNotification('success', 'Statut mis à jour avec succès');
                setTimeout(() => location.reload(), 1000);
            }
        } catch (error) {
            console.error('Error:', error);
            statusModal.hide();
            showNotification('error', 'Erreur lors de la mise à jour');
        }
    }
    
    // ============================================
    // FONCTIONS UTILITAIRES
    // ============================================
    
    // Gérer les erreurs AJAX
    function handleAjaxError(error) {
        if (error.status === 422) {
            const errors = error.responseJSON.errors;
            let errorMessage = 'Erreurs de validation:\n';
            for (let field in errors) {
                errorMessage += `- ${errors[field].join('\n')}\n`;
            }
            showNotification('error', errorMessage);
        } else if (error.status === 403) {
            showNotification('error', 'Action non autorisée');
        } else if (error.status === 404) {
            showNotification('error', 'Ressource non trouvée');
        } else if (error.status === 500) {
            showNotification('error', 'Erreur serveur interne');
        } else {
            showNotification('error', 'Erreur de connexion au serveur');
        }
        console.error('AJAX Error:', error);
    }
    
    // Système de notification
    function showNotification(type, message) {
        // Supprimer les notifications précédentes
        $('.notification-toast').remove();
        
        const icons = {
            success: 'fa-check-circle',
            error: 'fa-exclamation-circle',
            warning: 'fa-exclamation-triangle',
            info: 'fa-info-circle'
        };
        
        const colors = {
            success: '#06b48a',
            error: '#ef476f',
            warning: '#ffd166',
            info: '#45b7d1'
        };
        
        const notification = `
            <div class="notification-toast" style="
                position: fixed;
                top: 20px;
                right: 20px;
                background: white;
                border-radius: 8px;
                box-shadow: 0 10px 30px rgba(0,0,0,0.15);
                padding: 15px 20px;
                display: flex;
                align-items: center;
                gap: 12px;
                z-index: 10000;
                animation: slideInRight 0.3s ease;
                border-left: 4px solid ${colors[type]};
                max-width: 400px;
            ">
                <i class="fas ${icons[type]}" style="color: ${colors[type]}; font-size: 1.5rem;"></i>
                <div style="flex: 1;">
                    <div style="font-weight: 600; margin-bottom: 4px; color: #333;">
                        ${type === 'success' ? 'Succès' : type === 'error' ? 'Erreur' : type === 'warning' ? 'Attention' : 'Information'}
                    </div>
                    <div style="color: #666; font-size: 0.9rem;">${message}</div>
                </div>
                <i class="fas fa-times" style="color: #999; cursor: pointer;" onclick="this.closest('.notification-toast').remove()"></i>
            </div>
        `;
        
        $('body').append(notification);
        
        setTimeout(() => {
            $('.notification-toast').fadeOut(300, function() {
                $(this).remove();
            });
        }, 5000);
    }
    
    // Helper functions
    function getInitials(name) {
        if (!name) return '?';
        return name.split(' ').map(n => n[0]).join('').toUpperCase().substring(0, 2);
    }
    
    function getAvatarColor(name) {
        const colors = ['#45b7d1', '#96ceb4', '#feca57', '#ff6b6b', '#9b59b6', '#3498db', '#e67e22', '#2ecc71'];
        const index = (name?.length || 0) % colors.length;
        return colors[index];
    }
    
    function getActivityColor(description) {
        const colors = {
            'created': '#06b48a',
            'updated': '#45b7d1',
            'deleted': '#ef476f',
            'status': '#ffd166'
        };
        
        if (description.includes('créé')) return colors.created;
        if (description.includes('supprim')) return colors.deleted;
        if (description.includes('statut') || description.includes('status')) return colors.status;
        return colors.updated;
    }
    
    function getActivityIcon(description) {
        const icons = {
            'created': 'fa-plus-circle',
            'updated': 'fa-edit',
            'deleted': 'fa-trash',
            'status': 'fa-exchange-alt'
        };
        
        if (description.includes('créé')) return icons.created;
        if (description.includes('supprim')) return icons.deleted;
        if (description.includes('statut') || description.includes('status')) return icons.status;
        return icons.updated;
    }
    
    function timeAgo(dateString) {
        const date = new Date(dateString);
        const now = new Date();
        const seconds = Math.floor((now - date) / 1000);
        
        const intervals = {
            année: 31536000,
            mois: 2592000,
            semaine: 604800,
            jour: 86400,
            heure: 3600,
            minute: 60,
            seconde: 1
        };
        
        for (let [unit, secondsInUnit] of Object.entries(intervals)) {
            const interval = Math.floor(seconds / secondsInUnit);
            
            if (interval >= 1) {
                if (interval === 1) {
                    return `il y a 1 ${unit}`;
                } else {
                    let pluralUnit = unit;
                    if (unit === 'mois') {
                        pluralUnit = 'mois';
                    } else {
                        pluralUnit = unit + 's';
                    }
                    return `il y a ${interval} ${pluralUnit}`;
                }
            }
        }
        
        return 'à l\'instant';
    }

    // ============================================
    // INITIALISATION
    // ============================================
    $(document).ready(function() {
        // Initialiser les modals
        deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
        statusModal = new bootstrap.Modal(document.getElementById('statusModal'));
        createTaskModal = new bootstrap.Modal(document.getElementById('createTaskModal'));
        editTaskModal = new bootstrap.Modal(document.getElementById('editTaskModal'));
        descriptionModal = new bootstrap.Modal(document.getElementById('descriptionModal'));
        uploadFileModal = new bootstrap.Modal(document.getElementById('uploadFileModal'));
        activityModal = new bootstrap.Modal(document.getElementById('activityModal'));
        
        // Initialiser les gestionnaires de fichiers
        window.createFileManager = new FileManager('create');
        window.editFileManager = new FileManager('edit');
        initProjectFileManager();
        
        // Initialiser Select2
        $('.modal').on('shown.bs.modal', function() {
            $(this).find('.select2-modern').select2({
                dropdownParent: $(this),
                width: '100%'
            });
        });
        
        // Form submissions
        $('#createTaskForm').on('submit', createTask);
        $('#editTaskForm').on('submit', updateTask);
        $('#uploadFileForm').on('submit', uploadProjectFiles);
        
        // Reset forms when modals are hidden
        $('#createTaskModal').on('hidden.bs.modal', function() {
            $('#createTaskForm')[0].reset();
            if (window.createFileManager) {
                window.createFileManager.reset();
            }
        });
        
        $('#editTaskModal').on('hidden.bs.modal', function() {
            $('#editTaskForm')[0].reset();
            if (window.editFileManager) {
                window.editFileManager.reset();
                window.editFileManager.existingFiles = [];
                $('#existingFilesList').empty();
                $('#filesCount').text('0');
            }
            window.currentTaskId = null;
        });
        
        $('#uploadFileModal').on('hidden.bs.modal', function() {
            $('#uploadFileForm')[0].reset();
            if (projectFileManager) {
                projectFileManager.reset();
            }
        });
        
        $('#activityModal').on('hidden.bs.modal', function() {
            $('#activityModal .modal-body .activity-detail-content').empty();
        });
    });

    // ============================================
    // Exposer les fonctions globalement
    // ============================================
    window.openCreateTaskModal = openCreateTaskModal;
    window.openEditTaskModal = openEditTaskModal;
    window.viewTask = viewTask;
    window.filterTasks = filterTasks;
    window.toggleTaskStatus = toggleTaskStatus;
    window.deleteTask = deleteTask;
    window.duplicateProject = duplicateProject;
    window.exportProject = exportProject;
    window.confirmDelete = confirmDelete;
    window.updateStatus = updateStatus;
    window.submitStatusUpdate = submitStatusUpdate;
    window.openDescriptionModal = openDescriptionModal;
    window.openUploadFileModal = openUploadFileModal;
    window.deleteProjectFile = deleteProjectFile;
    window.showAllFiles = showAllFiles;
    window.showActivityDetails = showActivityDetails;
    window.showAllActivities = showAllActivities;
    window.toggleRawData = toggleRawData;
    window.getInitials = getInitials;
    window.getAvatarColor = getAvatarColor;
    window.getActivityColor = getActivityColor;
    window.getActivityIcon = getActivityIcon;
    window.timeAgo = timeAgo;
</script>

@endsection