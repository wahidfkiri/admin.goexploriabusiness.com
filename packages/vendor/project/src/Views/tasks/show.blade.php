{{-- resources/views/project/tasks/show.blade.php --}}
@extends('layouts.app')

@section('content')
    
    <!-- MAIN CONTENT -->
    <main class="dashboard-content">
        <!-- Page Header with Breadcrumb -->
        <div class="page-header">
            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb-custom">
                    <li class="breadcrumb-item">
                        <a href="{{ url('tasks.index') }}">
                            <i class="fas fa-tasks me-1"></i>Tâches
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ url('projects.show', $task->project_id) }}">
                            <i class="fas fa-project-diagram me-1"></i>{{ Str::limit($task->project->name ?? 'Projet', 30) }}
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($task->name, 50) }}</li>
                </ol>
            </nav>
            
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="page-title mb-0">
                    <span class="page-title-icon" style="background: {{ App\Helpers\Helper::getStatusColor($task->status_color) }}">
                        <i class="fas fa-tasks"></i>
                    </span>
                    {{ $task->name }}
                </h1>
                
                <div class="page-actions">
                    <div class="btn-group me-2">
                        <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="fas fa-ellipsis-v me-2"></i>Actions
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="{{ url('tasks.edit', $task->id) }}">
                                    <i class="fas fa-edit me-2"></i>Modifier
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="#" onclick="duplicateTask({{ $task->id }})">
                                    <i class="fas fa-copy me-2"></i>Dupliquer
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item text-danger" href="#" onclick="showDeleteConfirmation({{ $task->id }})">
                                    <i class="fas fa-trash me-2"></i>Supprimer
                                </a>
                            </li>
                        </ul>
                    </div>
                    
                    <a href="{{ url('tasks.edit', $task->id) }}" class="btn btn-primary">
                        <i class="fas fa-edit me-2"></i>Modifier
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Status Banner -->
        <div class="status-banner" style="background: linear-gradient(135deg, {{ App\Helpers\Helper::getStatusGradient($task->status_color) }});">
            <div class="status-banner-content">
                <div class="status-badge-large">
                    <i class="fas fa-circle me-2" style="font-size: 0.8rem;"></i>
                    {{ $task->formatted_status }}
                </div>
                <div class="status-info">
                    @if($task->isOverdue())
                        <span class="overdue-badge">
                            <i class="fas fa-exclamation-triangle me-1"></i>
                            En retard de {{ $task->due_date->diffInDays(now()) }} jour(s)
                        </span>
                    @elseif($task->due_date && $task->due_date->diffInDays(now()) <= 7 && $task->due_date->isFuture())
                        <span class="upcoming-badge">
                            <i class="fas fa-clock me-1"></i>
                            Échéance dans {{ $task->due_date->diffInDays(now()) }} jour(s)
                        </span>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Main Content Grid -->
        <div class="task-detail-grid">
            <!-- Left Column - Main Info -->
            <div class="task-detail-left">
                <!-- Task Details Card -->
                <div class="detail-card">
                    <div class="detail-card-header">
                        <h3 class="detail-card-title">
                            <i class="fas fa-info-circle me-2"></i>Informations générales
                        </h3>
                    </div>
                    
                    <div class="detail-card-body">
                        <div class="detail-grid">
                            <div class="detail-item">
                                <div class="detail-label">
                                    <i class="fas fa-project-diagram me-2"></i>Projet
                                </div>
                                <div class="detail-value">
                                    <a href="{{ url('projects.show', $task->project_id) }}" class="project-link">
                                        {{ $task->project->name ?? 'N/A' }}
                                    </a>
                                </div>
                            </div>
                            
                            <div class="detail-item">
                                <div class="detail-label">
                                    <i class="fas fa-user me-2"></i>Assigné à
                                </div>
                                <div class="detail-value">
                                    <div class="user-info">
                                        @if($task->user)
                                            <div class="user-avatar-sm" style="background: {{ App\Helpers\Helper::getUserColor($task->user->name) }}">
                                                {{ App\Helpers\Helper::getInitials($task->user->name) }}
                                            </div>
                                            <div>
                                                <div class="user-name">{{ $task->user->name }}</div>
                                                <div class="user-email">{{ $task->user->email }}</div>
                                            </div>
                                        @else
                                            <span class="text-muted">Non assigné</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            
                            <div class="detail-item">
                                <div class="detail-label">
                                    <i class="fas fa-calendar-alt me-2"></i>Date d'échéance
                                </div>
                                <div class="detail-value">
                                    @if($task->due_date)
                                        <span class="date-value {{ $task->isOverdue() ? 'text-danger' : '' }}">
                                            {{ $task->due_date->format('d/m/Y H:i') }}
                                        </span>
                                        @if($task->isOverdue())
                                            <span class="badge bg-danger ms-2">En retard</span>
                                        @endif
                                    @else
                                        <span class="text-muted">Non définie</span>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="detail-item">
                                <div class="detail-label">
                                    <i class="fas fa-check-circle me-2"></i>Date de livraison
                                </div>
                                <div class="detail-value">
                                    {{ $task->delivery_date ? $task->delivery_date->format('d/m/Y H:i') : 'Non définie' }}
                                </div>
                            </div>
                            
                            <div class="detail-item">
                                <div class="detail-label">
                                    <i class="fas fa-hourglass-half me-2"></i>Heures estimées
                                </div>
                                <div class="detail-value">
                                    <span class="badge bg-info bg-opacity-10 text-info">
                                        {{ $task->estimated_hours ?? 0 }} heures
                                    </span>
                                </div>
                            </div>
                            
                            <div class="detail-item">
                                <div class="detail-label">
                                    <i class="fas fa-euro-sign me-2"></i>Coût estimé
                                </div>
                                <div class="detail-value">
                                    <span class="badge bg-success bg-opacity-10 text-success">
                                        {{ number_format($task->estimated_cost ?? 0, 2, ',', ' ') }} €
                                    </span>
                                </div>
                            </div>
                            
                            <div class="detail-item">
                                <div class="detail-label">
                                    <i class="fas fa-flag me-2"></i>Priorité
                                </div>
                                <div class="detail-value">
                                    {!! App\Helpers\Helper::getPriorityBadge($metadata['priority'] ?? 'medium') !!}
                                </div>
                            </div>
                            
                            <div class="detail-item">
                                <div class="detail-label">
                                    <i class="fas fa-tags me-2"></i>Tags
                                </div>
                                <div class="detail-value">
                                    @if(!empty($metadata['tags']))
                                        @foreach($metadata['tags'] as $tag)
                                            <span class="tag-badge">{{ $tag }}</span>
                                        @endforeach
                                    @else
                                        <span class="text-muted">Aucun tag</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Location & Contact Card -->
                <div class="detail-card mt-4">
                    <div class="detail-card-header">
                        <h3 class="detail-card-title">
                            <i class="fas fa-map-marker-alt me-2"></i>Localisation et Contact
                        </h3>
                    </div>
                    
                    <div class="detail-card-body">
                        <div class="detail-grid">
                            <div class="detail-item">
                                <div class="detail-label">
                                    <i class="fas fa-globe me-2"></i>Pays
                                </div>
                                <div class="detail-value">
                                    {{ $task->country ?? 'Non spécifié' }}
                                </div>
                            </div>
                            
                            <div class="detail-item">
                                <div class="detail-label">
                                    <i class="fas fa-location-dot me-2"></i>Lieu
                                </div>
                                <div class="detail-value">
                                    {{ $task->location ?? 'Non spécifié' }}
                                </div>
                            </div>
                            
                            <div class="detail-item">
                                <div class="detail-label">
                                    <i class="fas fa-file-contract me-2"></i>N° Contrat
                                </div>
                                <div class="detail-value">
                                    {{ $task->contract_number ?? 'Non spécifié' }}
                                </div>
                            </div>
                            
                            <div class="detail-item">
                                <div class="detail-label">
                                    <i class="fas fa-user-tie me-2"></i>Contact
                                </div>
                                <div class="detail-value">
                                    {{ $task->contact_name ?? 'Non spécifié' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Description Card -->
                <div class="detail-card mt-4">
                    <div class="detail-card-header">
                        <h3 class="detail-card-title">
                            <i class="fas fa-align-left me-2"></i>Description
                        </h3>
                    </div>
                    
                    <div class="detail-card-body">
                        <div class="description-content">
                            {!! $task->details ? nl2br(e($task->details)) : '<span class="text-muted">Aucune description fournie</span>' !!}
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Right Column - Progress & Dates -->
            <div class="task-detail-right">
                <!-- Progress Card -->
                <div class="detail-card">
                    <div class="detail-card-header">
                        <h3 class="detail-card-title">
                            <i class="fas fa-chart-line me-2"></i>Avancement
                        </h3>
                    </div>
                    
                    <div class="detail-card-body text-center">
                        <div class="circular-progress-container">
                            <div class="circular-progress" data-progress="{{ $task->getProgress() }}">
                                <div class="progress-value">{{ $task->getProgress() }}%</div>
                            </div>
                        </div>
                        
                        <div class="status-steps mt-4">
                            <div class="status-step {{ $task->status == 'pending' ? 'active' : '' }} {{ in_array($task->status, ['in_progress', 'test', 'integrated', 'delivered', 'approved']) ? 'completed' : '' }}">
                                <div class="step-icon">1</div>
                                <div class="step-label">En attente</div>
                            </div>
                            <div class="step-connector {{ in_array($task->status, ['in_progress', 'test', 'integrated', 'delivered', 'approved']) ? 'completed' : '' }}"></div>
                            
                            <div class="status-step {{ $task->status == 'in_progress' ? 'active' : '' }} {{ in_array($task->status, ['test', 'integrated', 'delivered', 'approved']) ? 'completed' : '' }}">
                                <div class="step-icon">2</div>
                                <div class="step-label">En cours</div>
                            </div>
                            <div class="step-connector {{ in_array($task->status, ['test', 'integrated', 'delivered', 'approved']) ? 'completed' : '' }}"></div>
                            
                            <div class="status-step {{ $task->status == 'test' ? 'active' : '' }} {{ in_array($task->status, ['integrated', 'delivered', 'approved']) ? 'completed' : '' }}">
                                <div class="step-icon">3</div>
                                <div class="step-label">En test</div>
                            </div>
                            <div class="step-connector {{ in_array($task->status, ['integrated', 'delivered', 'approved']) ? 'completed' : '' }}"></div>
                            
                            <div class="status-step {{ $task->status == 'integrated' ? 'active' : '' }} {{ in_array($task->status, ['delivered', 'approved']) ? 'completed' : '' }}">
                                <div class="step-icon">4</div>
                                <div class="step-label">Intégré</div>
                            </div>
                            <div class="step-connector {{ in_array($task->status, ['delivered', 'approved']) ? 'completed' : '' }}"></div>
                            
                            <div class="status-step {{ $task->status == 'delivered' ? 'active' : '' }} {{ $task->status == 'approved' ? 'completed' : '' }}">
                                <div class="step-icon">5</div>
                                <div class="step-label">Livré</div>
                            </div>
                            <div class="step-connector {{ $task->status == 'approved' ? 'completed' : '' }}"></div>
                            
                            <div class="status-step {{ $task->status == 'approved' ? 'active' : '' }} {{ $task->status == 'approved' ? 'completed' : '' }}">
                                <div class="step-icon">6</div>
                                <div class="step-label">Approuvé</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Dates Techniques Card -->
                <div class="detail-card mt-4">
                    <div class="detail-card-header">
                        <h3 class="detail-card-title">
                            <i class="fas fa-code-branch me-2"></i>Dates Techniques
                        </h3>
                    </div>
                    
                    <div class="detail-card-body">
                        <div class="date-list">
                            <div class="date-item">
                                <div class="date-icon">
                                    <i class="fas fa-flask"></i>
                                </div>
                                <div class="date-info">
                                    <div class="date-label">Test</div>
                                    <div class="date-value">
                                        {{ $task->test_date ? $task->test_date->format('d/m/Y H:i') : 'Non planifié' }}
                                    </div>
                                </div>
                            </div>
                            
                            <div class="date-item">
                                <div class="date-icon">
                                    <i class="fas fa-code-merge"></i>
                                </div>
                                <div class="date-info">
                                    <div class="date-label">Intégration</div>
                                    <div class="date-value">
                                        {{ $task->integration_date ? $task->integration_date->format('d/m/Y H:i') : 'Non planifié' }}
                                    </div>
                                </div>
                            </div>
                            
                            <div class="date-item">
                                <div class="date-icon">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                </div>
                                <div class="date-info">
                                    <div class="date-label">Mise en production</div>
                                    <div class="date-value">
                                        {{ $task->push_prod_date ? $task->push_prod_date->format('d/m/Y H:i') : 'Non planifié' }}
                                    </div>
                                </div>
                            </div>
                            
                            @if($task->module_url)
                                <div class="date-item">
                                    <div class="date-icon">
                                        <i class="fas fa-link"></i>
                                    </div>
                                    <div class="date-info">
                                        <div class="date-label">URL du module</div>
                                        <div class="date-value">
                                            <a href="{{ $task->module_url }}" target="_blank" class="module-link">
                                                {{ Str::limit($task->module_url, 30) }}
                                                <i class="fas fa-external-link-alt ms-1"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Gestionnaires Card -->
                <div class="detail-card mt-4">
                    <div class="detail-card-header">
                        <h3 class="detail-card-title">
                            <i class="fas fa-user-tie me-2"></i>Gestionnaires
                        </h3>
                    </div>
                    
                    <div class="detail-card-body">
                        <div class="managers-list">
                            @if($task->generalManager)
                                <div class="manager-item">
                                    <div class="manager-avatar" style="background: {{ \App\Helpers\Helper::getUserColor($task->generalManager->name) }}">
                                        {{ getInitials($task->generalManager->name) }}
                                    </div>
                                    <div class="manager-info">
                                        <div class="manager-role">Directeur Général</div>
                                        <div class="manager-name">{{ $task->generalManager->name }}</div>
                                        <div class="manager-email">{{ $task->generalManager->email }}</div>
                                    </div>
                                </div>
                            @endif
                            
                            @if($task->clientManager)
                                <div class="manager-item">
                                    <div class="manager-avatar" style="background: {{ \App\Helpers\Helper::getUserColor($task->clientManager->name) }}">
                                        {{ getInitials($task->clientManager->name) }}
                                    </div>
                                    <div class="manager-info">
                                        <div class="manager-role">Responsable Client</div>
                                        <div class="manager-name">{{ $task->clientManager->name }}</div>
                                        <div class="manager-email">{{ $task->clientManager->email }}</div>
                                    </div>
                                </div>
                            @endif
                            
                            @if(!$task->generalManager && !$task->clientManager)
                                <p class="text-muted text-center mb-0">Aucun gestionnaire assigné</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Comments Section -->
        <div class="detail-card mt-4">
            <div class="detail-card-header">
                <h3 class="detail-card-title">
                    <i class="fas fa-comments me-2"></i>Commentaires
                </h3>
                <button class="btn btn-sm btn-primary" onclick="toggleCommentForm()">
                    <i class="fas fa-plus me-1"></i>Ajouter
                </button>
            </div>
            
            <div class="detail-card-body">
                <!-- Comment Form (Initially Hidden) -->
                <div class="comment-form-container" id="commentForm" style="display: none;">
                    <form id="addCommentForm">
                        @csrf
                        <div class="mb-3">
                            <textarea class="form-control-modern" id="commentContent" rows="3" placeholder="Écrivez votre commentaire..." required></textarea>
                        </div>
                        <div class="text-end">
                            <button type="button" class="btn btn-sm btn-secondary me-2" onclick="toggleCommentForm()">Annuler</button>
                            <button type="submit" class="btn btn-sm btn-primary">
                                <i class="fas fa-paper-plane me-1"></i>Publier
                            </button>
                        </div>
                    </form>
                </div>
                
                <!-- Comments List -->
                <div class="comments-list" id="commentsList">
                    @forelse($task->comments ?? [] as $comment)
                        <div class="comment-item" id="comment-{{ $comment->id }}">
                            <div class="comment-avatar" style="background: {{ \App\Helpers\Helper::getUserColor($comment->user->name ?? 'System') }}">
                                {{ \App\Helpers\Helper::getInitials($comment->user->name ?? 'System') }}
                            </div>
                            <div class="comment-content">
                                <div class="comment-header">
                                    <span class="comment-author">{{ $comment->user->name ?? 'Système' }}</span>
                                    <span class="comment-date">{{ $comment->created_at->diffForHumans() }}</span>
                                </div>
                                <div class="comment-text">
                                    {{ $comment->content }}
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="empty-comments">
                            <i class="fas fa-comment-dots"></i>
                            <p>Aucun commentaire pour le moment</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </main>
    
    <!-- DELETE MODAL -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirmer la suppression</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Êtes-vous sûr de vouloir supprimer cette tâche ? Cette action est irréversible.</p>
                    <p class="mb-0 fw-bold" id="deleteTaskName">{{ $task->name }}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn">
                        <i class="fas fa-trash me-2"></i>Supprimer
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- EDIT TASK MODAL -->
<div class="modal fade" id="editTaskModal" tabindex="-1" aria-labelledby="editTaskModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editTaskModalLabel">
                    <i class="fas fa-edit me-2"></i>Modifier la tâche
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body">
                <!-- Loading Spinner -->
                <div class="text-center py-4" id="editModalLoading" style="display: none;">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Chargement...</span>
                    </div>
                    <p class="mt-2">Chargement des données...</p>
                </div>
                
                <!-- Edit Form -->
                <form id="editTaskForm" style="display: none;">
                    @csrf
                    @method('PUT')
                    
                    <!-- Tabs Navigation -->
                    <ul class="nav nav-tabs mb-4" id="editTaskTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="basic-info-tab" data-bs-toggle="tab" data-bs-target="#basic-info" type="button" role="tab">
                                <i class="fas fa-info-circle me-2"></i>Informations
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="dates-tab" data-bs-toggle="tab" data-bs-target="#dates" type="button" role="tab">
                                <i class="fas fa-calendar-alt me-2"></i>Dates
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="tech-tab" data-bs-toggle="tab" data-bs-target="#tech" type="button" role="tab">
                                <i class="fas fa-code me-2"></i>Technique
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="managers-tab" data-bs-toggle="tab" data-bs-target="#managers" type="button" role="tab">
                                <i class="fas fa-user-tie me-2"></i>Gestionnaires
                            </button>
                        </li>
                    </ul>
                    
                    <!-- Tab Content -->
                    <div class="tab-content" id="editTaskTabContent">
                        <!-- Basic Info Tab -->
                        <div class="tab-pane fade show active" id="basic-info" role="tabpanel">
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label class="form-label-modern required">Nom de la tâche</label>
                                    <input type="text" class="form-control-modern" name="name" id="edit_name" required>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label-modern">Projet</label>
                                    <select class="form-select-modern" name="project_id" id="edit_project_id">
                                        <option value="">Sélectionner un projet</option>
                                        @foreach($projects ?? [] as $project)
                                            <option value="{{ $project->id }}">{{ $project->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label-modern required">Assigné à</label>
                                    <select class="form-select-modern" name="user_id" id="edit_user_id" required>
                                        <option value="">Sélectionner un utilisateur</option>
                                        @foreach($users ?? [] as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div class="col-md-4 mb-3">
                                    <label class="form-label-modern">Statut</label>
                                    <select class="form-select-modern" name="status" id="edit_status">
                                        <option value="pending">En attente</option>
                                        <option value="in_progress">En cours</option>
                                        <option value="test">En test</option>
                                        <option value="integrated">Intégré</option>
                                        <option value="delivered">Livré</option>
                                        <option value="approved">Approuvé</option>
                                        <option value="cancelled">Annulé</option>
                                    </select>
                                </div>
                                
                                <div class="col-md-4 mb-3">
                                    <label class="form-label-modern">Priorité</label>
                                    <select class="form-select-modern" name="priority" id="edit_priority">
                                        <option value="low">Basse</option>
                                        <option value="medium">Moyenne</option>
                                        <option value="high">Haute</option>
                                        <option value="urgent">Urgente</option>
                                    </select>
                                </div>
                                
                                <div class="col-md-4 mb-3">
                                    <label class="form-label-modern">Tags</label>
                                    <input type="text" class="form-control-modern" name="tags" id="edit_tags" placeholder="tag1,tag2,tag3">
                                </div>
                                
                                <div class="col-md-12 mb-3">
                                    <label class="form-label-modern">Description</label>
                                    <textarea class="form-control-modern" name="details" id="edit_details" rows="4"></textarea>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Dates Tab -->
                        <div class="tab-pane fade" id="dates" role="tabpanel">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label-modern">Date d'échéance</label>
                                    <input type="datetime-local" class="form-control-modern" name="due_date" id="edit_due_date">
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label-modern">Date de livraison</label>
                                    <input type="datetime-local" class="form-control-modern" name="delivery_date" id="edit_delivery_date">
                                </div>
                                
                                <div class="col-md-4 mb-3">
                                    <label class="form-label-modern">Heures estimées</label>
                                    <input type="number" class="form-control-modern" name="estimated_hours" id="edit_estimated_hours" min="0">
                                </div>
                                
                                <div class="col-md-4 mb-3">
                                    <label class="form-label-modern">Taux horaire (€)</label>
                                    <input type="number" class="form-control-modern" name="hourly_rate" id="edit_hourly_rate" min="0" step="0.01">
                                </div>
                                
                                <div class="col-md-4 mb-3">
                                    <label class="form-label-modern">Coût estimé (€)</label>
                                    <input type="number" class="form-control-modern" name="estimated_cost" id="edit_estimated_cost" min="0" step="0.01" readonly>
                                </div>
                            </div>
                            
                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle me-2"></i>
                                        Le coût estimé est automatiquement calculé (heures × taux horaire)
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Technical Tab -->
                        <div class="tab-pane fade" id="tech" role="tabpanel">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label-modern">Date de test</label>
                                    <input type="datetime-local" class="form-control-modern" name="test_date" id="edit_test_date">
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label-modern">Date d'intégration</label>
                                    <input type="datetime-local" class="form-control-modern" name="integration_date" id="edit_integration_date">
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label-modern">Date de mise en production</label>
                                    <input type="datetime-local" class="form-control-modern" name="push_prod_date" id="edit_push_prod_date">
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label-modern">URL du module</label>
                                    <input type="url" class="form-control-modern" name="module_url" id="edit_module_url" placeholder="https://...">
                                </div>
                                
                                <div class="col-md-12 mb-3">
                                    <label class="form-label-modern">Détails du test</label>
                                    <textarea class="form-control-modern" name="test_details" id="edit_test_details" rows="3"></textarea>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Managers Tab -->
                        <div class="tab-pane fade" id="managers" role="tabpanel">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label-modern">Directeur Général</label>
                                    <select class="form-select-modern" name="general_manager_id" id="edit_general_manager_id">
                                        <option value="">Non assigné</option>
                                        @foreach($generalManagers ?? [] as $manager)
                                            <option value="{{ $manager->id }}">{{ $manager->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label-modern">Responsable Client</label>
                                    <select class="form-select-modern" name="client_manager_id" id="edit_client_manager_id">
                                        <option value="">Non assigné</option>
                                        @foreach($clientManagers ?? [] as $manager)
                                            <option value="{{ $manager->id }}">{{ $manager->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label-modern">Pays</label>
                                    <input type="text" class="form-control-modern" name="country" id="edit_country">
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label-modern">Lieu</label>
                                    <input type="text" class="form-control-modern" name="location" id="edit_location">
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label-modern">N° Contrat</label>
                                    <input type="text" class="form-control-modern" name="contract_number" id="edit_contract_number">
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label-modern">Nom du contact</label>
                                    <input type="text" class="form-control-modern" name="contact_name" id="edit_contact_name">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Form Footer -->
                    <div class="modal-footer px-0 pb-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-2"></i>Annuler
                        </button>
                        <button type="submit" class="btn btn-primary" id="saveTaskBtn">
                            <i class="fas fa-save me-2"></i>Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- SUCCESS TOAST -->
<div class="toast-container position-fixed bottom-0 end-0 p-3">
    <div id="successToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header bg-success text-white">
            <i class="fas fa-check-circle me-2"></i>
            <strong class="me-auto">Succès</strong>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
        </div>
        <div class="toast-body" id="toastMessage">
            Opération réussie
        </div>
    </div>
</div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Configuration
        const taskId = {{ $task->id }};
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            setupAjax();
            initCircularProgress();
        });

        // AJAX setup
        const setupAjax = () => {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
        };

        // Circular Progress
        const initCircularProgress = () => {
            const progressElement = document.querySelector('.circular-progress');
            const progress = parseInt(progressElement.dataset.progress) || 0;
            
            const circumference = 2 * Math.PI * 54; // radius = 54
            const offset = circumference - (progress / 100) * circumference;
            
            // Create SVG
            const svg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
            svg.setAttribute('viewBox', '0 0 120 120');
            svg.setAttribute('class', 'circular-progress-svg');
            
            const bgCircle = document.createElementNS('http://www.w3.org/2000/svg', 'circle');
            bgCircle.setAttribute('cx', '60');
            bgCircle.setAttribute('cy', '60');
            bgCircle.setAttribute('r', '54');
            bgCircle.setAttribute('fill', 'none');
            bgCircle.setAttribute('stroke', '#e9ecef');
            bgCircle.setAttribute('stroke-width', '8');
            
            const progressCircle = document.createElementNS('http://www.w3.org/2000/svg', 'circle');
            progressCircle.setAttribute('cx', '60');
            progressCircle.setAttribute('cy', '60');
            progressCircle.setAttribute('r', '54');
            progressCircle.setAttribute('fill', 'none');
            progressCircle.setAttribute('stroke', getProgressColor(progress));
            progressCircle.setAttribute('stroke-width', '8');
            progressCircle.setAttribute('stroke-linecap', 'round');
            progressCircle.setAttribute('stroke-dasharray', circumference);
            progressCircle.setAttribute('stroke-dashoffset', offset);
            progressCircle.setAttribute('transform', 'rotate(-90 60 60)');
            
            svg.appendChild(bgCircle);
            svg.appendChild(progressCircle);
            
            progressElement.innerHTML = '';
            progressElement.appendChild(svg);
        };

        // Toggle comment form
        const toggleCommentForm = () => {
            const form = document.getElementById('commentForm');
            form.style.display = form.style.display === 'none' ? 'block' : 'none';
        };

        // Add comment
        document.getElementById('addCommentForm')?.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const content = document.getElementById('commentContent').value.trim();
            
            if (!content) {
                showAlert('warning', 'Veuillez écrire un commentaire');
                return;
            }
            
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Envoi...';
            
            $.ajax({
                url: `/tasks/${taskId}/comments`,
                type: 'POST',
                data: { content: content },
                success: function(response) {
                    if (response.success) {
                        addCommentToList(response.data);
                        document.getElementById('commentContent').value = '';
                        toggleCommentForm();
                        showAlert('success', 'Commentaire ajouté');
                    }
                },
                error: function() {
                    showAlert('danger', 'Erreur lors de l\'ajout du commentaire');
                },
                complete: function() {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="fas fa-paper-plane me-1"></i>Publier';
                }
            });
        });

        // Add comment to list
        const addCommentToList = (comment) => {
            const commentsList = document.getElementById('commentsList');
            const emptyState = commentsList.querySelector('.empty-comments');
            
            if (emptyState) {
                emptyState.remove();
            }
            
            const commentHtml = `
                <div class="comment-item" id="comment-${comment.id}">
                    <div class="comment-avatar" style="background: ${getUserColor(comment.user?.name || 'System')}">
                        ${getInitials(comment.user?.name || 'S')}
                    </div>
                    <div class="comment-content">
                        <div class="comment-header">
                            <span class="comment-author">${comment.user?.name || 'Système'}</span>
                            <span class="comment-date">À l'instant</span>
                        </div>
                        <div class="comment-text">
                            ${comment.content}
                        </div>
                    </div>
                </div>
            `;
            
            commentsList.insertAdjacentHTML('afterbegin', commentHtml);
        };

        // Duplicate task
        const duplicateTask = (id) => {
            if (!confirm('Voulez-vous dupliquer cette tâche ?')) return;
            
            $.ajax({
                url: `/tasks/${id}/duplicate`,
                type: 'POST',
                success: function(response) {
                    if (response.success) {
                        showAlert('success', 'Tâche dupliquée avec succès');
                        setTimeout(() => {
                            window.location.href = response.redirect;
                        }, 1500);
                    }
                },
                error: function() {
                    showAlert('danger', 'Erreur lors de la duplication');
                }
            });
        };

        // Show delete confirmation
        const showDeleteConfirmation = (id) => {
            const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
            modal.show();
            
            document.getElementById('confirmDeleteBtn').onclick = function() {
                deleteTask(id);
            };
        };

        // Delete task
        const deleteTask = (id) => {
            const deleteBtn = document.getElementById('confirmDeleteBtn');
            deleteBtn.disabled = true;
            deleteBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Suppression...';
            
            $.ajax({
                url: `/tasks/${id}`,
                type: 'DELETE',
                success: function(response) {
                    if (response.success) {
                        showAlert('success', response.message);
                        setTimeout(() => {
                            window.location.href = '{{ url("tasks.index") }}';
                        }, 1500);
                    }
                },
                error: function() {
                    showAlert('danger', 'Erreur lors de la suppression');
                    deleteBtn.disabled = false;
                    deleteBtn.innerHTML = '<i class="fas fa-trash me-2"></i>Supprimer';
                }
            });
        };

        // Show alert
        const showAlert = (type, message) => {
            const alert = document.createElement('div');
            alert.className = `alert alert-${type} alert-dismissible fade show position-fixed top-0 end-0 m-3`;
            alert.style.zIndex = '9999';
            alert.innerHTML = `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            
            document.body.appendChild(alert);
            
            setTimeout(() => {
                if (alert.parentNode) alert.remove();
            }, 5000);
        };

        // Helper functions
        const getInitials = (name) => {
            if (!name) return '?';
            return name.split(' ').map(n => n[0]).join('').toUpperCase().substring(0, 2);
        };

        const getUserColor = (name) => {
            const colors = ['#45b7d1', '#96ceb4', '#feca57', '#ff6b6b', '#9b59b6'];
            const index = (name?.length || 0) % colors.length;
            return colors[index];
        };

        const getProgressColor = (progress) => {
            if (progress < 30) return '#ef476f';
            if (progress < 70) return '#ffd166';
            return '#06b48a';
        };
    </script>

    <script>
// Configuration supplémentaire
let editModal = null;
let currentTaskData = {};

// Initialisation au chargement
document.addEventListener('DOMContentLoaded', function() {
    // Initialiser le modal
    editModal = new bootstrap.Modal(document.getElementById('editTaskModal'));
    
    // Configurer les écouteurs pour le bouton d'édition
    setupEditButton();
    
    // Configurer le calcul automatique du coût
    setupCostCalculation();
    
    // Configurer la soumission du formulaire
    setupEditForm();
});

// Configurer le bouton d'édition
const setupEditButton = () => {
    const editBtn = document.querySelector('a[href*="edit"]');
    if (editBtn) {
        editBtn.addEventListener('click', function(e) {
            e.preventDefault();
            loadTaskData();
        });
    }
    
    // Ajouter aussi un bouton d'édition rapide si nécessaire
    const quickEditBtn = document.querySelector('.btn-quick-edit');
    if (quickEditBtn) {
        quickEditBtn.addEventListener('click', function(e) {
            e.preventDefault();
            loadTaskData();
        });
    }
};

// Charger les données de la tâche
const loadTaskData = () => {
    // Afficher le chargement
    document.getElementById('editModalLoading').style.display = 'block';
    document.getElementById('editTaskForm').style.display = 'none';
    
    // Ouvrir le modal
    editModal.show();
    
    // Récupérer les données via AJAX
    $.ajax({
        url: `/tasks/{{ $task->id }}/edit`,
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                currentTaskData = response.data;
                populateEditForm(response.data);
            } else {
                showToast('danger', 'Erreur lors du chargement des données');
                editModal.hide();
            }
        },
        error: function(xhr) {
            console.error('Error loading task data:', xhr);
            showToast('danger', 'Erreur de chargement des données');
            editModal.hide();
        },
        complete: function() {
            document.getElementById('editModalLoading').style.display = 'none';
            document.getElementById('editTaskForm').style.display = 'block';
        }
    });
};

// Remplir le formulaire avec les données
const populateEditForm = (data) => {
    // Informations de base
    document.getElementById('edit_name').value = data.name || '';
    document.getElementById('edit_project_id').value = data.project_id || '';
    document.getElementById('edit_user_id').value = data.user_id || '';
    document.getElementById('edit_status').value = data.status || 'pending';
    document.getElementById('edit_priority').value = data.priority || 'medium';
    document.getElementById('edit_tags').value = data.tags || '';
    document.getElementById('edit_details').value = data.details || '';
    
    // Dates
    document.getElementById('edit_due_date').value = data.due_date || '';
    document.getElementById('edit_delivery_date').value = data.delivery_date || '';
    document.getElementById('edit_estimated_hours').value = data.estimated_hours || '';
    document.getElementById('edit_hourly_rate').value = data.hourly_rate || '';
    document.getElementById('edit_estimated_cost').value = data.estimated_cost || '';
    
    // Technique
    document.getElementById('edit_test_date').value = data.test_date || '';
    document.getElementById('edit_integration_date').value = data.integration_date || '';
    document.getElementById('edit_push_prod_date').value = data.push_prod_date || '';
    document.getElementById('edit_module_url').value = data.module_url || '';
    document.getElementById('edit_test_details').value = data.test_details || '';
    
    // Gestionnaires
    document.getElementById('edit_general_manager_id').value = data.general_manager_id || '';
    document.getElementById('edit_client_manager_id').value = data.client_manager_id || '';
    document.getElementById('edit_country').value = data.country || '';
    document.getElementById('edit_location').value = data.location || '';
    document.getElementById('edit_contract_number').value = data.contract_number || '';
    document.getElementById('edit_contact_name').value = data.contact_name || '';
    
    // Calculer le coût initial
    calculateEstimatedCost();
};

// Configurer le calcul automatique du coût
const setupCostCalculation = () => {
    const hoursInput = document.getElementById('edit_estimated_hours');
    const rateInput = document.getElementById('edit_hourly_rate');
    
    if (hoursInput && rateInput) {
        hoursInput.addEventListener('input', calculateEstimatedCost);
        rateInput.addEventListener('input', calculateEstimatedCost);
    }
};

// Calculer le coût estimé
const calculateEstimatedCost = () => {
    const hours = parseFloat(document.getElementById('edit_estimated_hours').value) || 0;
    const rate = parseFloat(document.getElementById('edit_hourly_rate').value) || 0;
    const cost = hours * rate;
    
    document.getElementById('edit_estimated_cost').value = cost.toFixed(2);
};

// Configurer la soumission du formulaire
const setupEditForm = () => {
    const form = document.getElementById('editTaskForm');
    
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Désactiver le bouton
        const submitBtn = document.getElementById('saveTaskBtn');
        const originalText = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Enregistrement...';
        
        // Récupérer les données du formulaire
        const formData = new FormData(form);
        
        // Ajouter les tags (convertir la chaîne en tableau)
        const tags = document.getElementById('edit_tags').value;
        if (tags) {
            formData.append('tags_array', tags.split(',').map(tag => tag.trim()));
        }
        
        // Envoyer la requête
        $.ajax({
            url: `/tasks/{{ $task->id }}`,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    showToast('success', 'Tâche mise à jour avec succès');
                    editModal.hide();
                    
                    // Recharger la page après un délai
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                } else {
                    handleValidationErrors(response.errors);
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    handleValidationErrors(xhr.responseJSON.errors);
                } else {
                    showToast('danger', 'Erreur lors de la mise à jour');
                }
            },
            complete: function() {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        });
    });
};

// Gérer les erreurs de validation
const handleValidationErrors = (errors) => {
    // Réinitialiser les erreurs
    document.querySelectorAll('.is-invalid').forEach(el => {
        el.classList.remove('is-invalid');
    });
    document.querySelectorAll('.invalid-feedback').forEach(el => {
        el.remove();
    });
    
    // Afficher les nouvelles erreurs
    for (let field in errors) {
        const input = document.getElementById(`edit_${field}`);
        if (input) {
            input.classList.add('is-invalid');
            
            const feedback = document.createElement('div');
            feedback.className = 'invalid-feedback';
            feedback.textContent = errors[field][0];
            
            input.parentNode.appendChild(feedback);
        }
    }
    
    showToast('warning', 'Veuillez corriger les erreurs dans le formulaire');
};

// Afficher un toast
const showToast = (type, message) => {
    const toastEl = document.getElementById('successToast');
    const toastBody = document.getElementById('toastMessage');
    
    // Changer la couleur selon le type
    const toastHeader = toastEl.querySelector('.toast-header');
    toastHeader.className = `toast-header bg-${type} text-white`;
    
    // Changer l'icône
    const icon = toastHeader.querySelector('i');
    icon.className = type === 'success' ? 'fas fa-check-circle me-2' : 
                     type === 'danger' ? 'fas fa-exclamation-circle me-2' :
                     type === 'warning' ? 'fas fa-exclamation-triangle me-2' :
                     'fas fa-info-circle me-2';
    
    toastBody.textContent = message;
    
    const toast = new bootstrap.Toast(toastEl);
    toast.show();
};

// Validation en temps réel
document.querySelectorAll('#editTaskForm [required]').forEach(input => {
    input.addEventListener('invalid', function(e) {
        e.preventDefault();
        this.classList.add('is-invalid');
    });
    
    input.addEventListener('input', function() {
        if (this.value) {
            this.classList.remove('is-invalid');
            const feedback = this.parentNode.querySelector('.invalid-feedback');
            if (feedback) feedback.remove();
        }
    });
});

// Confirmation avant de quitter avec des changements non sauvegardés
let formChanged = false;

document.querySelectorAll('#editTaskForm input, #editTaskForm select, #editTaskForm textarea').forEach(input => {
    input.addEventListener('change', () => {
        formChanged = true;
    });
});

document.getElementById('editTaskModal').addEventListener('hide.bs.modal', function(e) {
    if (formChanged) {
        if (!confirm('Vous avez des modifications non enregistrées. Voulez-vous vraiment quitter ?')) {
            e.preventDefault();
        }
    }
});

document.getElementById('editTaskModal').addEventListener('hidden.bs.modal', function() {
    formChanged = false;
});
</script>

<style>
/* Styles supplémentaires pour le modal */
.modal-lg {
    max-width: 800px;
}

.form-label-modern {
    font-weight: 500;
    margin-bottom: 0.5rem;
    color: #495057;
    display: block;
}

.form-label-modern.required:after {
    content: ' *';
    color: #dc3545;
}

.form-control-modern,
.form-select-modern {
    width: 100%;
    padding: 0.5rem 0.75rem;
    font-size: 0.95rem;
    border: 1px solid #dce0e5;
    border-radius: 0.5rem;
    transition: all 0.2s ease-in-out;
}

.form-control-modern:focus,
.form-select-modern:focus {
    border-color: #4a6cf7;
    box-shadow: 0 0 0 0.2rem rgba(74, 108, 247, 0.1);
    outline: none;
}

.form-control-modern.is-invalid,
.form-select-modern.is-invalid {
    border-color: #dc3545;
}

.form-control-modern.is-invalid:focus,
.form-select-modern.is-invalid:focus {
    box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.1);
}

.invalid-feedback {
    color: #dc3545;
    font-size: 0.875rem;
    margin-top: 0.25rem;
}

.nav-tabs .nav-link {
    color: #495057;
    font-weight: 500;
    padding: 0.75rem 1rem;
    border: none;
    border-bottom: 2px solid transparent;
}

.nav-tabs .nav-link:hover {
    border-color: transparent;
    color: #4a6cf7;
}

.nav-tabs .nav-link.active {
    color: #4a6cf7;
    background: transparent;
    border-bottom: 2px solid #4a6cf7;
}

.nav-tabs .nav-link i {
    font-size: 1rem;
}

.tab-pane {
    padding: 1rem 0;
}

.modal-footer {
    border-top: 1px solid #e9ecef;
    margin-top: 1rem;
}

/* Animation pour le toast */
.toast {
    opacity: 0;
    transition: opacity 0.3s ease-in-out;
}

.toast.show {
    opacity: 1;
}

/* Scrollbar personnalisée */
.modal-body::-webkit-scrollbar {
    width: 6px;
}

.modal-body::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

.modal-body::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 10px;
}

.modal-body::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}
</style>

    <style>
        /* Styles spécifiques à la page de détail */
        .breadcrumb-custom {
            display: flex;
            flex-wrap: wrap;
            padding: 0;
            margin-bottom: 1rem;
            list-style: none;
            background: none;
        }
        
        .breadcrumb-custom li {
            display: flex;
            align-items: center;
        }
        
        .breadcrumb-custom li + li:before {
            content: "›";
            padding: 0 0.75rem;
            color: #6c757d;
            font-size: 1.2rem;
        }
        
        .breadcrumb-custom a {
            color: var(--primary-color);
            text-decoration: none;
            font-size: 0.95rem;
        }
        
        .breadcrumb-custom .active {
            color: #6c757d;
            font-size: 0.95rem;
        }
        
        .status-banner {
            margin: -20px 0 30px 0;
            padding: 20px;
            border-radius: 12px;
            color: white;
        }
        
        .status-banner-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 15px;
        }
        
        .status-badge-large {
            background: rgba(255,255,255,0.2);
            padding: 10px 25px;
            border-radius: 50px;
            font-size: 1.1rem;
            font-weight: 600;
            backdrop-filter: blur(5px);
        }
        
        .overdue-badge {
            background: rgba(220, 53, 69, 0.9);
            padding: 8px 20px;
            border-radius: 50px;
            font-size: 0.95rem;
            font-weight: 500;
        }
        
        .upcoming-badge {
            background: rgba(255, 193, 7, 0.9);
            color: #333;
            padding: 8px 20px;
            border-radius: 50px;
            font-size: 0.95rem;
            font-weight: 500;
        }
        
        .task-detail-grid {
            display: grid;
            grid-template-columns: 1.5fr 1fr;
            gap: 20px;
        }
        
        .detail-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            border: 1px solid #eaeaea;
            overflow: hidden;
        }
        
        .detail-card-header {
            padding: 20px;
            background: linear-gradient(to right, #f8f9fa, white);
            border-bottom: 1px solid #eaeaea;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .detail-card-title {
            margin: 0;
            font-size: 1.1rem;
            font-weight: 600;
            color: #333;
            display: flex;
            align-items: center;
        }
        
        .detail-card-body {
            padding: 20px;
        }
        
        .detail-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }
        
        .detail-item {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 15px;
        }
        
        .detail-label {
            font-size: 0.85rem;
            color: #666;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
        }
        
        .detail-value {
            font-weight: 600;
            color: #333;
        }
        
        .user-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .user-avatar-sm {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 0.9rem;
        }
        
        .user-email {
            font-size: 0.8rem;
            color: #666;
        }
        
        .project-link {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 600;
        }
        
        .project-link:hover {
            text-decoration: underline;
        }
        
        .tag-badge {
            display: inline-block;
            background: #e9ecef;
            padding: 3px 10px;
            border-radius: 15px;
            font-size: 0.85rem;
            margin-right: 5px;
            margin-bottom: 5px;
        }
        
        .description-content {
            line-height: 1.8;
            color: #333;
        }
        
        /* Progress steps */
        .status-steps {
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: relative;
        }
        
        .status-step {
            text-align: center;
            z-index: 1;
        }
        
        .step-icon {
            width: 40px;
            height: 40px;
            background: #e9ecef;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            color: #999;
            margin-bottom: 8px;
            transition: all 0.3s ease;
        }
        
        .status-step.active .step-icon {
            background: var(--primary-color);
            color: white;
            transform: scale(1.1);
            box-shadow: 0 4px 10px rgba(var(--primary-rgb), 0.3);
        }
        
        .status-step.completed .step-icon {
            background: #06b48a;
            color: white;
        }
        
        .step-label {
            font-size: 0.8rem;
            color: #666;
            max-width: 70px;
        }
        
        .step-connector {
            flex: 1;
            height: 2px;
            background: #e9ecef;
            margin: 0 5px;
        }
        
        .step-connector.completed {
            background: #06b48a;
        }
        
        /* Circular progress */
        .circular-progress-container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 20px 0;
        }
        
        .circular-progress {
            position: relative;
            width: 140px;
            height: 140px;
        }
        
        .circular-progress-svg {
            width: 100%;
            height: 100%;
            transform: rotate(-90deg);
        }
        
        .progress-value {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 1.8rem;
            font-weight: 700;
            color: #333;
        }
        
        /* Date list */
        .date-list {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        
        .date-item {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 10px;
        }
        
        .date-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--primary-color), #3a56e4);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }
        
        .date-info {
            flex: 1;
        }
        
        .date-label {
            font-size: 0.8rem;
            color: #666;
            margin-bottom: 2px;
        }
        
        .date-value {
            font-weight: 600;
            color: #333;
        }
        
        .module-link {
            color: var(--primary-color);
            text-decoration: none;
        }
        
        .module-link:hover {
            text-decoration: underline;
        }
        
        /* Managers list */
        .managers-list {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        
        .manager-item {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 10px;
        }
        
        .manager-avatar {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
        }
        
        .manager-info {
            flex: 1;
        }
        
        .manager-role {
            font-size: 0.8rem;
            color: #666;
            margin-bottom: 2px;
        }
        
        .manager-name {
            font-weight: 600;
            color: #333;
            margin-bottom: 2px;
        }
        
        .manager-email {
            font-size: 0.8rem;
            color: #666;
        }
        
        /* Comments */
        .comments-list {
            max-height: 400px;
            overflow-y: auto;
        }
        
        .comment-item {
            display: flex;
            gap: 15px;
            padding: 15px;
            border-bottom: 1px solid #eaeaea;
        }
        
        .comment-item:last-child {
            border-bottom: none;
        }
        
        .comment-avatar {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            flex-shrink: 0;
        }
        
        .comment-content {
            flex: 1;
        }
        
        .comment-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 5px;
        }
        
        .comment-author {
            font-weight: 600;
            color: #333;
        }
        
        .comment-date {
            font-size: 0.8rem;
            color: #666;
        }
        
        .comment-text {
            color: #333;
            line-height: 1.6;
        }
        
        .empty-comments {
            text-align: center;
            padding: 40px 20px;
            color: #999;
        }
        
        .empty-comments i {
            font-size: 3rem;
            margin-bottom: 15px;
        }
        
        .empty-comments p {
            margin: 0;
        }
        
        .comment-form-container {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
        }
        
        .form-control-modern {
            width: 100%;
            padding: 10px 15px;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        
        .form-control-modern:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(var(--primary-rgb), 0.1);
            outline: none;
        }
        
        /* Responsive */
        @media (max-width: 992px) {
            .task-detail-grid {
                grid-template-columns: 1fr;
            }
        }
        
        @media (max-width: 768px) {
            .detail-grid {
                grid-template-columns: 1fr;
            }
            
            .status-steps {
                flex-direction: column;
                gap: 20px;
            }
            
            .step-connector {
                width: 2px;
                height: 30px;
            }
            
            .status-step {
                display: flex;
                align-items: center;
                gap: 15px;
                width: 100%;
            }
            
            .step-label {
                max-width: none;
            }
        }
    </style>
@endsection