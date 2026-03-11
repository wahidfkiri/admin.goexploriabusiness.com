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
                        <a href="{{ route('tasks.index') }}">
                            <i class="fas fa-tasks me-1"></i>Tâches
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('projects.show', $task->project_id) }}">
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
                                <a class="dropdown-item" href="{{ route('tasks.edit', $task->id) }}">
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
                    
                    <a href="{{ route('tasks.edit', $task->id) }}" class="btn btn-primary">
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
                                    <a href="{{ route('projects.show', $task->project_id) }}" class="project-link">
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
            <i class="fas fa-comments me-2"></i>Commentaires et détails
        </h3>
        <button class="btn btn-sm btn-primary" onclick="toggleCommentForm()">
            <i class="fas fa-plus me-1"></i>Ajouter
        </button>
    </div>
    
    <div class="detail-card-body">
        <!-- Comment Form (Initially Hidden) -->
        <div class="comment-form-container" id="commentForm" style="display: none;">
            <form id="addCommentForm" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <textarea class="form-control-modern" id="commentContent" name="content" rows="3" placeholder="Écrivez votre commentaire..." required></textarea>
                </div>
                
              <!-- File Upload Area -->
<div class="file-upload-area mb-3">
    <div class="file-upload-header">
        <span class="file-upload-title">
            <i class="fas fa-paperclip me-2"></i>Pièces jointes
        </span>
        <span class="file-upload-hint">(Max 10MB par fichier)</span>
    </div>
    
    <!-- Drop Zone -->
    <div class="drop-zone" id="commentDropZone">
        <i class="fas fa-cloud-upload-alt fa-2x mb-2"></i>
        <p>Glissez vos fichiers ici ou <span class="browse-link" id="commentBrowseLink" style="color: #4a6cf7; text-decoration: underline; cursor: pointer;">parcourez</span></p>
        <input type="file" id="commentFiles" name="attachments[]" multiple style="display: none;" accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.zip">
    </div>
    
    <!-- File Preview Container -->
    <div class="file-preview-container" id="commentFilePreview"></div>
</div>
                
                <div class="text-end">
                    <button type="button" class="btn btn-sm btn-secondary me-2" onclick="toggleCommentForm()">Annuler</button>
                    <button type="submit" class="btn btn-sm btn-primary">
                        <i class="fas fa-paper-plane me-1"></i>Publier
                    </button>
                </div>
            </form>
        </div>

        <!-- Edit Comment Form (Initially Hidden) -->
        <div class="comment-form-container" id="editCommentForm" style="display: none;">
            <form id="updateCommentForm" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" id="editCommentId">
                <div class="mb-3">
                    <textarea class="form-control-modern" id="editCommentContent" name="content" rows="3" placeholder="Modifiez votre commentaire..." required></textarea>
                </div>
                
               <!-- File Upload Area for Edit -->
<div class="file-upload-area mb-3">
    <div class="file-upload-header">
        <span class="file-upload-title">
            <i class="fas fa-paperclip me-2"></i>Ajouter des fichiers
        </span>
    </div>
    
    <!-- Drop Zone -->
    <div class="drop-zone" id="editCommentDropZone">
        <i class="fas fa-cloud-upload-alt fa-2x mb-2"></i>
        <p>Glissez vos fichiers ici ou <span class="browse-link" id="editCommentBrowseLink" style="color: #4a6cf7; text-decoration: underline; cursor: pointer;">parcourez</span></p>
        <input type="file" id="editCommentFiles" name="attachments[]" multiple style="display: none;" accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.zip">
    </div>
    
    <!-- File Preview Container -->
    <div class="file-preview-container" id="editCommentFilePreview"></div>
</div>
                
                <div class="text-end">
                    <button type="button" class="btn btn-sm btn-secondary me-2" onclick="cancelEditComment()">Annuler</button>
                    <button type="submit" class="btn btn-sm btn-primary">
                        <i class="fas fa-save me-1"></i>Mettre à jour
                    </button>
                </div>
            </form>
        </div>
        
        <!-- Comments List -->
        <div class="comments-list" id="commentsList">
            @forelse($task->comments()->with(['user', 'files'])->latest()->get() as $comment)
                <div class="comment-item" id="comment-{{ $comment->id }}">
                    <div class="comment-avatar" style="background: {{ \App\Helpers\Helper::getUserColor($comment->user->name ?? 'System') }}">
                        {{ \App\Helpers\Helper::getInitials($comment->user->name ?? 'System') }}
                    </div>
                    <div class="comment-content">
                        <div class="comment-header">
                            <span class="comment-author">{{ $comment->user->name ?? 'Système' }}</span>
                            <span class="comment-date">{{ $comment->created_at->diffForHumans() }}</span>
                            
                            @if($comment->user_id == auth()->id())
                                <div class="comment-actions ms-auto">
                                    <button class="btn btn-sm btn-link text-primary p-0 me-2" onclick="editComment({{ $comment->id }}, '{{ addslashes($comment->content) }}')" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-link text-danger p-0" onclick="deleteComment({{ $comment->id }})" title="Supprimer">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            @endif
                        </div>
                        
                        <div class="comment-text" id="comment-text-{{ $comment->id }}">
                            {{ $comment->content }}
                        </div>
                        
                       <!-- Files Section -->
@if($comment->files->count() > 0)
    <div class="comment-files mt-2">
        @foreach($comment->files as $file)
            <div class="file-item" id="file-{{ $file->id }}">
                <div class="file-icon">
                    <i class="fas {{ $file->file_icon }}"></i> <!-- Utilisez file_icon au lieu de getFileIcon() -->
                </div>
                <div class="file-info">
                    <a href="{{ route('tasks.comments.files.download', [$task->id, $comment->id, $file->id]) }}" class="file-name" target="_blank">
                        {{ $file->original_filename }}
                    </a>
                    <span class="file-meta">
                        <span class="file-size">{{ $file->file_size }}</span>
                        @if($file->is_image)
                            <span class="file-badge image-badge">
                                <i class="fas fa-image me-1"></i>Image
                            </span>
                        @elseif($file->is_pdf)
                            <span class="file-badge pdf-badge">
                                <i class="fas fa-file-pdf me-1"></i>PDF
                            </span>
                        @elseif($file->is_document)
                            <span class="file-badge doc-badge">
                                <i class="fas {{ $file->file_icon }} me-1"></i>Document
                            </span>
                        @else
                            <span class="file-badge other-badge">
                                <i class="fas fa-file me-1"></i>Fichier
                            </span>
                        @endif
                    </span>
                </div>
                @if($comment->user_id == auth()->id())
                    <button class="btn btn-sm btn-link text-danger file-delete-btn" onclick="deleteCommentFile({{ $comment->id }}, {{ $file->id }})" title="Supprimer le fichier">
                        <i class="fas fa-times"></i>
                    </button>
                @endif
            </div>
        @endforeach
    </div>
@endif
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
<!-- File Preview Modal -->
<div class="modal fade" id="filePreviewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="filePreviewModalLabel">
                    <i class="fas fa-eye me-2"></i>Aperçu du fichier
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0" id="filePreviewContent">
                <!-- Content will be loaded dynamically -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Fermer
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Image Gallery Modal -->
<div class="modal fade" id="imageGalleryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content bg-dark">
            <div class="modal-header border-secondary">
                <h5 class="modal-title text-white" id="galleryFileName"></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0 position-relative">
                <img id="galleryImage" src="" class="img-fluid w-100" style="max-height: 80vh; object-fit: contain;">
                
                <button id="prevImageBtn" class="btn btn-dark position-absolute top-50 start-0 translate-middle-y ms-3" style="display: none;">
                    <i class="fas fa-chevron-left"></i>
                </button>
                
                <button id="nextImageBtn" class="btn btn-dark position-absolute top-50 end-0 translate-middle-y me-3" style="display: none;">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
            <div class="modal-footer border-secondary justify-content-between">
                <span class="text-white" id="galleryCounter"></span>
                <div>
                    <button class="btn btn-primary" onclick="downloadCurrentImage()">
                        <i class="fas fa-download me-2"></i>Télécharger
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
// ============================================
// CONFIGURATION GLOBALE
// ============================================
const taskId = {{ $task->id }};
const currentUserId = {{ auth()->id() ?? 'null' }};
const csrfToken = '{{ csrf_token() }}';

// Variables globales pour les modals
let taskEditModal = null;
let taskDeleteModal = null;
let taskFilePreviewModal = null;
let taskImageGalleryModal = null;

// ============================================
// FONCTIONS UTILITAIRES (À METTRE EN PREMIER)
// ============================================

// Échapper les caractères HTML
const escapeHtml = (text) => {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
};

// Échapper les caractères pour les chaînes JavaScript
const escapeJsString = (str) => {
    if (!str) return '';
    return str.replace(/\\/g, '\\\\')
              .replace(/'/g, "\\'")
              .replace(/"/g, '\\"')
              .replace(/\n/g, '\\n')
              .replace(/\r/g, '\\r');
};

// Décoder les entités HTML
const decodeHtml = (html) => {
    const txt = document.createElement('textarea');
    txt.innerHTML = html;
    return txt.value;
};

// Obtenir les initiales d'un nom
const getInitials = (name) => {
    if (!name) return '?';
    return name.split(' ')
               .map(n => n[0])
               .join('')
               .toUpperCase()
               .substring(0, 2);
};

// Obtenir une couleur basée sur un nom
const getUserColor = (name) => {
    const colors = [
        '#45b7d1', '#96ceb4', '#feca57', '#ff6b6b', '#9b59b6',
        '#3498db', '#e67e22', '#2ecc71', '#e74c3c', '#f1c40f'
    ];
    const index = (name?.length || 0) % colors.length;
    return colors[index];
};

// Formater la taille du fichier
const formatFileSize = (bytes) => {
    if (bytes === 0) return '0 B';
    const k = 1024;
    const sizes = ['B', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
};

// Obtenir l'icône selon le type de fichier
const getFileIcon = (mimeType) => {
    if (!mimeType) return { icon: 'fa-file', class: 'other' };
    
    if (mimeType.startsWith('image/')) {
        return { icon: 'fa-image', class: 'image' };
    }
    if (mimeType === 'application/pdf') {
        return { icon: 'fa-file-pdf', class: 'pdf' };
    }
    if (mimeType.includes('word') || mimeType.includes('document')) {
        return { icon: 'fa-file-word', class: 'word' };
    }
    if (mimeType.includes('sheet') || mimeType.includes('excel')) {
        return { icon: 'fa-file-excel', class: 'excel' };
    }
    if (mimeType.includes('presentation') || mimeType.includes('powerpoint')) {
        return { icon: 'fa-file-powerpoint', class: 'powerpoint' };
    }
    if (mimeType.includes('zip') || mimeType.includes('compressed')) {
        return { icon: 'fa-file-archive', class: 'archive' };
    }
    return { icon: 'fa-file', class: 'other' };
};

// Obtenir la couleur de l'icône
const getIconColor = (fileType) => {
    const colors = {
        'image': '#06b48a',
        'pdf': '#dc3545',
        'word': '#45b7d1',
        'excel': '#06b48a',
        'powerpoint': '#f39c12',
        'archive': '#9b59b6',
        'other': '#95a5a6'
    };
    return colors[fileType] || colors.other;
};

// Obtenir la couleur de progression
const getProgressColor = (progress) => {
    if (progress < 30) return '#ef476f';
    if (progress < 70) return '#ffd166';
    return '#06b48a';
};

// ============================================
// GESTION DES UPLOADS DE FICHIERS
// ============================================

// Créer un élément de prévisualisation
const createFilePreview = (file, index) => {
    const div = document.createElement('div');
    div.className = 'file-preview-item';
    div.setAttribute('data-index', index);
    
    const size = formatFileSize(file.size);
    const icon = getFileIcon(file.type);
    
    div.innerHTML = `
        <div class="file-preview-info">
            <div class="file-preview-icon ${icon.class}">
                <i class="fas ${icon.icon}"></i>
            </div>
            <div class="file-preview-name">
                ${escapeHtml(file.name)}
                <span class="file-preview-size">(${size})</span>
            </div>
        </div>
        <div class="file-preview-remove" onclick="removeFilePreview(this, ${index})">
            <i class="fas fa-times"></i>
        </div>
    `;
    
    return div;
};

// Supprimer une prévisualisation
window.removeFilePreview = function(element, index) {
    const previewContainer = element.closest('.file-preview-container');
    const fileInput = previewContainer?.previousElementSibling?.querySelector('input[type="file"]');
    
    if (fileInput && fileInput.files) {
        const dt = new DataTransfer();
        const files = fileInput.files;
        
        for (let i = 0; i < files.length; i++) {
            if (i !== index) {
                dt.items.add(files[i]);
            }
        }
        
        fileInput.files = dt.files;
    }
    
    element.closest('.file-preview-item').remove();
};

// Gérer la sélection des fichiers
const handleFileSelection = (files, previewContainer) => {
    if (!previewContainer) return;
    
    previewContainer.innerHTML = '';
    
    if (files.length === 0) return;
    
    Array.from(files).forEach((file, index) => {
        if (file.size > 10 * 1024 * 1024) {
            showToast('warning', `Le fichier ${file.name} dépasse la limite de 10MB`);
            return;
        }
        
        const previewItem = createFilePreview(file, index);
        previewContainer.appendChild(previewItem);
    });
};

// Initialiser une zone d'upload spécifique
const initFileUpload = (dropZoneId, inputId, previewContainerId, browseLinkId) => {
    const dropZone = document.getElementById(dropZoneId);
    const fileInput = document.getElementById(inputId);
    const previewContainer = document.getElementById(previewContainerId);
    const browseLink = document.getElementById(browseLinkId);
    
    if (!dropZone) {
        console.warn(`Drop zone ${dropZoneId} non trouvée`);
        return;
    }
    
    if (!fileInput) {
        console.warn(`File input ${inputId} non trouvé`);
        return;
    }
    
    if (!previewContainer) {
        console.warn(`Preview container ${previewContainerId} non trouvé`);
        return;
    }
    
    console.log(`Initialisation de ${dropZoneId}`);
    
    // Click sur la zone entière (sauf le lien)
    dropZone.addEventListener('click', (e) => {
        if (e.target.classList.contains('browse-link') || e.target.id === browseLinkId) {
            return;
        }
        fileInput.click();
    });
    
    // Click spécifique sur le lien "parcourez"
    if (browseLink) {
        browseLink.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            fileInput.click();
        });
    }
    
    // Gestion du drag & drop
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, preventDefaults, false);
    });
    
    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }
    
    ['dragenter', 'dragover'].forEach(eventName => {
        dropZone.addEventListener(eventName, () => {
            dropZone.classList.add('dragover');
        });
    });
    
    ['dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, () => {
            dropZone.classList.remove('dragover');
        });
    });
    
    // Gestion des fichiers déposés
    dropZone.addEventListener('drop', (e) => {
        const files = e.dataTransfer.files;
        fileInput.files = files;
        handleFileSelection(files, previewContainer);
    });
    
    // Gestion de la sélection via input
    fileInput.addEventListener('change', (e) => {
        handleFileSelection(e.target.files, previewContainer);
    });
};

// Initialiser tous les uploads de fichiers
const initFileUploads = () => {
    console.log('Initialisation des uploads de fichiers...');
    initFileUpload('commentDropZone', 'commentFiles', 'commentFilePreview', 'commentBrowseLink');
    initFileUpload('editCommentDropZone', 'editCommentFiles', 'editCommentFilePreview', 'editCommentBrowseLink');
};

// ============================================
// GESTION DES COMMENTAIRES
// ============================================

// Initialiser les formulaires de commentaires
const initCommentForms = () => {
    console.log('Initialisation des formulaires de commentaires...');
    
    const addForm = document.getElementById('addCommentForm');
    if (addForm) {
        addForm.addEventListener('submit', function(e) {
            e.preventDefault();
            submitComment();
        });
    }
    
    const editForm = document.getElementById('updateCommentForm');
    if (editForm) {
        editForm.addEventListener('submit', function(e) {
            e.preventDefault();
            updateComment();
        });
    }
};

// Afficher/masquer le formulaire de commentaire
window.toggleCommentForm = function() {
    const form = document.getElementById('commentForm');
    const editForm = document.getElementById('editCommentForm');
    
    if (!form) return;
    
    if (editForm && editForm.style.display === 'block') {
        editForm.style.display = 'none';
    }
    
    form.style.display = form.style.display === 'none' ? 'block' : 'none';
    
    if (form.style.display === 'block') {
        const contentField = document.getElementById('commentContent');
        if (contentField) contentField.focus();
    }
};

// Éditer un commentaire
window.editComment = function(commentId, content) {
    const form = document.getElementById('commentForm');
    const editForm = document.getElementById('editCommentForm');
    const editId = document.getElementById('editCommentId');
    const editContent = document.getElementById('editCommentContent');
    
    if (form) form.style.display = 'none';
    if (editForm) editForm.style.display = 'block';
    if (editId) editId.value = commentId;
    if (editContent) {
        editContent.value = decodeHtml(content);
        editContent.focus();
    }
    
    if (editForm) {
        editForm.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
};

// Annuler l'édition
window.cancelEditComment = function() {
    const editForm = document.getElementById('editCommentForm');
    const editId = document.getElementById('editCommentId');
    const editContent = document.getElementById('editCommentContent');
    const editFiles = document.getElementById('editCommentFiles');
    const editPreview = document.getElementById('editCommentFilePreview');
    
    if (editForm) editForm.style.display = 'none';
    if (editId) editId.value = '';
    if (editContent) editContent.value = '';
    if (editFiles) editFiles.value = '';
    if (editPreview) editPreview.innerHTML = '';
};

// Soumettre un nouveau commentaire
const submitComment = () => {
    const contentField = document.getElementById('commentContent');
    const fileInput = document.getElementById('commentFiles');
    const previewContainer = document.getElementById('commentFilePreview');
    
    if (!contentField) return;
    
    const content = contentField.value.trim();
    
    if (!content) {
        showToast('warning', 'Veuillez écrire un commentaire');
        return;
    }
    
    const formData = new FormData();
    formData.append('content', content);
    
    if (fileInput && fileInput.files.length > 0) {
        Array.from(fileInput.files).forEach(file => {
            formData.append('attachments[]', file);
        });
    }
    
    const submitBtn = document.querySelector('#addCommentForm button[type="submit"]');
    if (!submitBtn) return;
    
    const originalText = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Envoi...';
    
    $.ajax({
        url: `/tasks/${taskId}/comments`,
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            if (response.success) {
                addCommentToList(response.data);
                if (contentField) contentField.value = '';
                if (fileInput) fileInput.value = '';
                if (previewContainer) previewContainer.innerHTML = '';
                window.toggleCommentForm();
                showToast('success', response.message || 'Commentaire ajouté avec succès');
            }
        },
        error: function(xhr) {
            let message = 'Erreur lors de l\'ajout du commentaire';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                message = xhr.responseJSON.message;
            }
            showToast('danger', message);
        },
        complete: function() {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        }
    });
};

// Mettre à jour un commentaire
const updateComment = () => {
    const editId = document.getElementById('editCommentId');
    const editContent = document.getElementById('editCommentContent');
    const fileInput = document.getElementById('editCommentFiles');
    
    if (!editId || !editContent) return;
    
    const commentId = editId.value;
    const content = editContent.value.trim();
    
    if (!content) {
        showToast('warning', 'Veuillez écrire un commentaire');
        return;
    }
    
    const formData = new FormData();
    formData.append('content', content);
    formData.append('_method', 'PUT');
    
    if (fileInput && fileInput.files.length > 0) {
        Array.from(fileInput.files).forEach(file => {
            formData.append('attachments[]', file);
        });
    }
    
    const submitBtn = document.querySelector('#updateCommentForm button[type="submit"]');
    if (!submitBtn) return;
    
    const originalText = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Mise à jour...';
    
    $.ajax({
        url: `/tasks/${taskId}/comments/${commentId}`,
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            if (response.success) {
                showToast('success', response.message || 'Commentaire mis à jour avec succès');
                setTimeout(() => {
                    location.reload();
                }, 1500);
            }
        },
        error: function(xhr) {
            if (xhr.status === 403) {
                showToast('danger', 'Vous n\'êtes pas autorisé à modifier ce commentaire');
            } else {
                let message = 'Erreur lors de la mise à jour';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
                showToast('danger', message);
            }
        },
        complete: function() {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        }
    });
};

// Supprimer un commentaire
window.deleteComment = function(commentId) {
    if (!confirm('Êtes-vous sûr de vouloir supprimer ce commentaire ? Cette action est irréversible.')) {
        return;
    }
    
    const commentElement = document.getElementById(`comment-${commentId}`);
    if (!commentElement) return;
    
    commentElement.style.transition = 'all 0.3s ease';
    commentElement.style.transform = 'translateX(20px)';
    commentElement.style.opacity = '0';
    
    $.ajax({
        url: `/tasks/${taskId}/comments/${commentId}`,
        type: 'DELETE',
        success: function(response) {
            if (response.success) {
                setTimeout(() => {
                    commentElement.remove();
                    
                    const commentsList = document.getElementById('commentsList');
                    if (commentsList && commentsList.children.length === 0) {
                        commentsList.innerHTML = `
                            <div class="empty-comments">
                                <i class="fas fa-comment-dots"></i>
                                <p>Aucun commentaire pour le moment</p>
                            </div>
                        `;
                    }
                    
                    showToast('success', response.message || 'Commentaire supprimé avec succès');
                }, 300);
            }
        },
        error: function(xhr) {
            commentElement.style.transform = '';
            commentElement.style.opacity = '1';
            
            if (xhr.status === 403) {
                showToast('danger', 'Vous n\'êtes pas autorisé à supprimer ce commentaire');
            } else {
                let message = 'Erreur lors de la suppression';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
                showToast('danger', message);
            }
        }
    });
};

// Ajouter un commentaire à la liste
const addCommentToList = (comment) => {
    const commentsList = document.getElementById('commentsList');
    if (!commentsList) return;
    
    const emptyState = commentsList.querySelector('.empty-comments');
    if (emptyState) {
        emptyState.remove();
    }
    
    const isCurrentUser = comment.user_id === currentUserId;
    const actionButtons = isCurrentUser ? `
        <div class="comment-actions ms-auto">
            <button class="btn btn-sm btn-link text-primary p-0 me-2" onclick="editComment(${comment.id}, '${escapeJsString(comment.content)}')" title="Modifier">
                <i class="fas fa-edit"></i>
            </button>
            <button class="btn btn-sm btn-link text-danger p-0" onclick="deleteComment(${comment.id})" title="Supprimer">
                <i class="fas fa-trash"></i>
            </button>
        </div>
    ` : '';
    
    let filesHtml = '';
    if (comment.files && comment.files.length > 0) {
        filesHtml = '<div class="comment-files mt-2">';
        comment.files.forEach(file => {
            const fileIcon = getFileIcon(file.mime_type);
            filesHtml += `
                <div class="file-item" id="file-${file.id}">
                    <div class="file-icon" onclick="previewFile(${comment.id}, ${file.id}, '${escapeJsString(file.original_filename)}')" style="cursor: pointer;">
                        <i class="fas ${fileIcon.icon}"></i>
                    </div>
                    <div class="file-info">
                        <div class="file-name-wrapper" onclick="previewFile(${comment.id}, ${file.id}, '${escapeJsString(file.original_filename)}')" style="cursor: pointer;">
                            <span class="file-name">${escapeHtml(file.original_filename)}</span>
                            <span class="file-meta">
                                <span class="file-size">${formatFileSize(file.size)}</span>
                                <span class="file-badge ${fileIcon.class}-badge">
                                    <i class="fas ${fileIcon.icon} me-1"></i>${fileIcon.class.charAt(0).toUpperCase() + fileIcon.class.slice(1)}
                                </span>
                            </span>
                        </div>
                    </div>
                    <div class="file-actions">
                        <button class="btn btn-sm btn-link text-primary" onclick="previewFile(${comment.id}, ${file.id}, '${escapeJsString(file.original_filename)}')" title="Aperçu">
                            <i class="fas fa-eye"></i>
                        </button>
                        <a href="/tasks/${taskId}/comments/${comment.id}/files/${file.id}/download" class="btn btn-sm btn-link text-success" title="Télécharger">
                            <i class="fas fa-download"></i>
                        </a>
                        ${isCurrentUser ? `
                            <button class="btn btn-sm btn-link text-danger" onclick="deleteCommentFile(${comment.id}, ${file.id})" title="Supprimer">
                                <i class="fas fa-trash"></i>
                            </button>
                        ` : ''}
                    </div>
                </div>
            `;
        });
        filesHtml += '</div>';
    }
    
    const userInitials = getInitials(comment.user?.name || 'S');
    const userColor = getUserColor(comment.user?.name || 'System');
    
    const commentHtml = `
        <div class="comment-item" id="comment-${comment.id}" style="animation: slideIn 0.3s ease;">
            <div class="comment-avatar" style="background: ${userColor}">
                ${userInitials}
            </div>
            <div class="comment-content">
                <div class="comment-header">
                    <span class="comment-author">${escapeHtml(comment.user?.name || 'Système')}</span>
                    <span class="comment-date">À l'instant</span>
                    ${actionButtons}
                </div>
                <div class="comment-text" id="comment-text-${comment.id}">
                    ${escapeHtml(comment.content)}
                </div>
                ${filesHtml}
            </div>
        </div>
    `;
    
    commentsList.insertAdjacentHTML('afterbegin', commentHtml);
};

// ============================================
// GESTION DES FICHIERS DES COMMENTAIRES
// ============================================

// Supprimer un fichier d'un commentaire
window.deleteCommentFile = function(commentId, fileId) {
    if (!confirm('Voulez-vous supprimer ce fichier ?')) {
        return;
    }
    
    const fileElement = document.getElementById(`file-${fileId}`);
    if (fileElement) {
        fileElement.style.transition = 'all 0.3s ease';
        fileElement.style.transform = 'translateX(20px)';
        fileElement.style.opacity = '0';
    }
    
    $.ajax({
        url: `/tasks/${taskId}/comments/${commentId}/files/${fileId}`,
        type: 'DELETE',
        success: function(response) {
            if (response.success) {
                setTimeout(() => {
                    if (fileElement) fileElement.remove();
                    showToast('success', response.message || 'Fichier supprimé avec succès');
                }, 300);
            }
        },
        error: function(xhr) {
            if (fileElement) {
                fileElement.style.transform = '';
                fileElement.style.opacity = '1';
            }
            
            if (xhr.status === 403) {
                showToast('danger', 'Vous n\'êtes pas autorisé à supprimer ce fichier');
            } else {
                showToast('danger', 'Erreur lors de la suppression du fichier');
            }
        }
    });
};

// ============================================
// PRÉVISUALISATION DES FICHIERS
// ============================================

// Ouvrir la prévisualisation d'un fichier
window.previewFile = function(commentId, fileId, fileName) {
    const modalLabel = document.getElementById('filePreviewModalLabel');
    const modalContent = document.getElementById('filePreviewContent');
    
    if (!modalLabel || !modalContent || !taskFilePreviewModal) {
        console.error('Éléments de prévisualisation manquants');
        return;
    }
    
    modalLabel.textContent = fileName;
    modalContent.innerHTML = `
        <div class="text-center py-5">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Chargement...</span>
            </div>
            <p class="mt-2">Chargement de l'aperçu...</p>
        </div>
    `;
    
    taskFilePreviewModal.show();
    
    $.ajax({
        url: `/tasks/${taskId}/comments/${commentId}/files/${fileId}/preview`,
        type: 'GET',
        xhrFields: {
            responseType: 'blob'
        },
        success: function(response, textStatus, xhr) {
            const contentType = xhr.getResponseHeader('content-type');
            
            if (contentType && contentType.startsWith('image/')) {
                displayImagePreview(response, fileName, commentId, fileId);
            } else if (contentType === 'application/pdf') {
                displayPdfPreview(response, fileName);
            } else {
                displayFileInfo(commentId, fileId, fileName);
            }
        },
        error: function(xhr) {
            if (xhr.status === 404) {
                modalContent.innerHTML = `
                    <div class="text-center py-5">
                        <i class="fas fa-file-exclamation fa-4x text-warning mb-3"></i>
                        <h5>Fichier non trouvé</h5>
                        <p class="text-muted">Le fichier demandé n'existe pas ou a été supprimé.</p>
                    </div>
                `;
            } else {
                modalContent.innerHTML = `
                    <div class="text-center py-5">
                        <i class="fas fa-exclamation-triangle fa-4x text-danger mb-3"></i>
                        <h5>Erreur de chargement</h5>
                        <p class="text-muted">Impossible de charger l'aperçu du fichier.</p>
                        <button class="btn btn-primary mt-3" onclick="downloadFile(${commentId}, ${fileId}, '${escapeJsString(fileName)}')">
                            <i class="fas fa-download me-2"></i>Télécharger
                        </button>
                    </div>
                `;
            }
        }
    });
};

// Afficher l'aperçu d'une image
const displayImagePreview = (blob, fileName, commentId, fileId) => {
    const url = URL.createObjectURL(blob);
    const modalContent = document.getElementById('filePreviewContent');
    
    if (!modalContent) return;
    
    modalContent.innerHTML = `
        <div class="image-preview-container">
            <img src="${url}" alt="${escapeHtml(fileName)}" class="img-fluid" style="max-height: 70vh; margin: 0 auto;">
        </div>
        <div class="preview-actions mt-3 text-center pb-3">
            <button class="btn btn-sm btn-primary" onclick="downloadFileFromBlob('${url}', '${escapeJsString(fileName)}')">
                <i class="fas fa-download me-2"></i>Télécharger
            </button>
            <button class="btn btn-sm btn-secondary" onclick="window.open('${url}', '_blank')">
                <i class="fas fa-external-link-alt me-2"></i>Ouvrir dans un nouvel onglet
            </button>
        </div>
    `;
};

// Afficher l'aperçu d'un PDF
const displayPdfPreview = (blob, fileName) => {
    const url = URL.createObjectURL(blob);
    const modalContent = document.getElementById('filePreviewContent');
    
    if (!modalContent) return;
    
    modalContent.innerHTML = `
        <div class="pdf-preview-container">
            <embed src="${url}" type="application/pdf" width="100%" height="600px" />
        </div>
        <div class="preview-actions mt-3 text-center pb-3">
            <a href="${url}" download="${escapeHtml(fileName)}" class="btn btn-sm btn-primary">
                <i class="fas fa-download me-2"></i>Télécharger
            </a>
            <a href="${url}" target="_blank" class="btn btn-sm btn-secondary">
                <i class="fas fa-external-link-alt me-2"></i>Ouvrir dans un nouvel onglet
            </a>
        </div>
    `;
};

// Afficher les informations d'un fichier non prévisualisable
const displayFileInfo = (commentId, fileId, fileName) => {
    $.ajax({
        url: `/tasks/${taskId}/comments/${commentId}/files/${fileId}/info`,
        type: 'GET',
        success: function(response) {
            const modalContent = document.getElementById('filePreviewContent');
            if (!modalContent) return;
            
            if (response.success) {
                const file = response.file;
                const icon = getFileIcon(file.mime_type);
                const iconColor = getIconColor(icon.class);
                
                modalContent.innerHTML = `
                    <div class="file-info-preview text-center py-4">
                        <div class="file-icon-large mb-3">
                            <i class="fas ${icon.icon}" style="font-size: 5rem; color: ${iconColor};"></i>
                        </div>
                        <h5 class="file-name-large mb-2">${escapeHtml(file.name)}</h5>
                        <div class="file-details mb-4">
                            <span class="badge bg-secondary me-2">${file.size}</span>
                            <span class="badge bg-info">${file.mime_type}</span>
                        </div>
                        <div class="file-metadata mb-3">
                            <p class="mb-1"><i class="fas fa-calendar me-2"></i>Ajouté le: ${file.created_at}</p>
                            <p class="mb-1"><i class="fas fa-user me-2"></i>Par: ${escapeHtml(file.uploaded_by)}</p>
                        </div>
                        <div class="preview-actions">
                            <button class="btn btn-primary" onclick="downloadFile(${commentId}, ${fileId}, '${escapeJsString(fileName)}')">
                                <i class="fas fa-download me-2"></i>Télécharger
                            </button>
                        </div>
                    </div>
                `;
            }
        },
        error: function() {
            const modalContent = document.getElementById('filePreviewContent');
            if (modalContent) {
                modalContent.innerHTML = `
                    <div class="text-center py-5">
                        <i class="fas fa-exclamation-circle fa-4x text-danger mb-3"></i>
                        <h5>Erreur</h5>
                        <p class="text-muted">Impossible de charger les informations du fichier.</p>
                    </div>
                `;
            }
        }
    });
};

// Télécharger un fichier
window.downloadFile = function(commentId, fileId, fileName) {
    window.location.href = `/tasks/${taskId}/comments/${commentId}/files/${fileId}/download`;
};

// Télécharger un fichier depuis un blob
window.downloadFileFromBlob = function(url, fileName) {
    const a = document.createElement('a');
    a.href = url;
    a.download = fileName;
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
};

// Download current gallery image
window.downloadCurrentImage = function() {
    const imageUrl = document.getElementById('galleryImage').src;
    const fileName = document.getElementById('galleryFileName').textContent;
    
    fetch(imageUrl)
        .then(response => response.blob())
        .then(blob => {
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = fileName;
            document.body.appendChild(a);
            a.click();
            window.URL.revokeObjectURL(url);
            document.body.removeChild(a);
        })
        .catch(() => {
            showToast('danger', 'Erreur lors du téléchargement');
        });
};

// ============================================
// GESTION DES TÂCHES
// ============================================

// Configurer le bouton d'édition
const setupEditButton = () => {
    const editBtn = document.querySelector('a[href*="edit"]');
    if (editBtn) {
        editBtn.addEventListener('click', function(e) {
            e.preventDefault();
            loadTaskData();
        });
    }
};

// Charger les données de la tâche
const loadTaskData = () => {
    const loadingEl = document.getElementById('editModalLoading');
    const formEl = document.getElementById('editTaskForm');
    
    if (!loadingEl || !formEl || !taskEditModal) return;
    
    loadingEl.style.display = 'block';
    formEl.style.display = 'none';
    
    taskEditModal.show();
    
    $.ajax({
        url: `/tasks/${taskId}/edit`,
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                currentTaskData = response.data;
                populateEditForm(response.data);
            } else {
                showToast('danger', 'Erreur lors du chargement des données');
                taskEditModal.hide();
            }
        },
        error: function(xhr) {
            console.error('Error loading task data:', xhr);
            showToast('danger', 'Erreur de chargement des données');
            taskEditModal.hide();
        },
        complete: function() {
            loadingEl.style.display = 'none';
            formEl.style.display = 'block';
        }
    });
};

// Remplir le formulaire avec les données
const populateEditForm = (data) => {
    setFieldValue('edit_name', data.name);
    setFieldValue('edit_project_id', data.project_id);
    setFieldValue('edit_user_id', data.user_id);
    setFieldValue('edit_status', data.status);
    setFieldValue('edit_priority', data.priority);
    setFieldValue('edit_tags', data.tags || '');
    setFieldValue('edit_details', data.details);
    
    setFieldValue('edit_due_date', data.due_date);
    setFieldValue('edit_delivery_date', data.delivery_date);
    setFieldValue('edit_estimated_hours', data.estimated_hours);
    setFieldValue('edit_hourly_rate', data.hourly_rate);
    setFieldValue('edit_estimated_cost', data.estimated_cost);
    
    setFieldValue('edit_test_date', data.test_date);
    setFieldValue('edit_integration_date', data.integration_date);
    setFieldValue('edit_push_prod_date', data.push_prod_date);
    setFieldValue('edit_module_url', data.module_url);
    setFieldValue('edit_test_details', data.test_details);
    
    setFieldValue('edit_general_manager_id', data.general_manager_id);
    setFieldValue('edit_client_manager_id', data.client_manager_id);
    setFieldValue('edit_country', data.country);
    setFieldValue('edit_location', data.location);
    setFieldValue('edit_contract_number', data.contract_number);
    setFieldValue('edit_contact_name', data.contact_name);
    
    calculateEstimatedCost();
};

// Helper pour set la valeur d'un champ
const setFieldValue = (id, value) => {
    const element = document.getElementById(id);
    if (element) {
        element.value = value || '';
    }
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
    const hours = parseFloat(document.getElementById('edit_estimated_hours')?.value) || 0;
    const rate = parseFloat(document.getElementById('edit_hourly_rate')?.value) || 0;
    const cost = hours * rate;
    
    const costField = document.getElementById('edit_estimated_cost');
    if (costField) {
        costField.value = cost.toFixed(2);
    }
};

// Configurer la soumission du formulaire
const setupEditForm = () => {
    const form = document.getElementById('editTaskForm');
    
    if (!form) return;
    
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const submitBtn = document.getElementById('saveTaskBtn');
        if (!submitBtn) return;
        
        const originalText = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Enregistrement...';
        
        const formData = new FormData(form);
        formData.append('_method', 'PUT');
        
        $.ajax({
            url: `/tasks/${taskId}`,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    showToast('success', 'Tâche mise à jour avec succès');
                    if (taskEditModal) taskEditModal.hide();
                    
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                } else {
                    if (response.errors) {
                        handleValidationErrors(response.errors);
                    } else {
                        showToast('danger', response.message || 'Erreur lors de la mise à jour');
                    }
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
    document.querySelectorAll('.is-invalid').forEach(el => {
        el.classList.remove('is-invalid');
    });
    document.querySelectorAll('.invalid-feedback').forEach(el => {
        el.remove();
    });
    
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

// Dupliquer une tâche
window.duplicateTask = function(id) {
    if (!confirm('Voulez-vous dupliquer cette tâche ?')) return;
    
    $.ajax({
        url: `/tasks/${id}/duplicate`,
        type: 'POST',
        success: function(response) {
            if (response.success) {
                showToast('success', 'Tâche dupliquée avec succès');
                setTimeout(() => {
                    window.location.href = response.redirect || `/tasks/${response.data.id}`;
                }, 1500);
            }
        },
        error: function(xhr) {
            let message = 'Erreur lors de la duplication';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                message = xhr.responseJSON.message;
            }
            showToast('danger', message);
        }
    });
};

// Afficher la confirmation de suppression
window.showDeleteConfirmation = function(id) {
    if (!taskDeleteModal) return;
    
    taskDeleteModal.show();
    
    const confirmBtn = document.getElementById('confirmDeleteBtn');
    if (confirmBtn) {
        confirmBtn.onclick = function() {
            deleteTask(id);
        };
    }
};

// Supprimer une tâche
const deleteTask = (id) => {
    const deleteBtn = document.getElementById('confirmDeleteBtn');
    if (!deleteBtn) return;
    
    const originalText = deleteBtn.innerHTML;
    deleteBtn.disabled = true;
    deleteBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Suppression...';
    
    $.ajax({
        url: `/tasks/${id}`,
        type: 'DELETE',
        success: function(response) {
            if (response.success) {
                showToast('success', response.message || 'Tâche supprimée avec succès');
                setTimeout(() => {
                    window.location.href = '{{ route("tasks.index") }}';
                }, 1500);
            }
        },
        error: function(xhr) {
            let message = 'Erreur lors de la suppression';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                message = xhr.responseJSON.message;
            }
            showToast('danger', message);
            deleteBtn.disabled = false;
            deleteBtn.innerHTML = originalText;
        }
    });
};

// ============================================
// COMPOSANTS UI
// ============================================

// Afficher un toast
const showToast = (type, message) => {
    const toastEl = document.getElementById('successToast');
    if (!toastEl) return;
    
    const toastBody = document.getElementById('toastMessage');
    const toastHeader = toastEl.querySelector('.toast-header');
    const icon = toastHeader?.querySelector('i');
    
    if (!toastBody || !toastHeader) return;
    
    const config = {
        success: { bg: 'bg-success', icon: 'fa-check-circle', text: 'Succès' },
        danger: { bg: 'bg-danger', icon: 'fa-exclamation-circle', text: 'Erreur' },
        warning: { bg: 'bg-warning', icon: 'fa-exclamation-triangle', text: 'Attention' },
        info: { bg: 'bg-info', icon: 'fa-info-circle', text: 'Information' }
    };
    
    const typeConfig = config[type] || config.info;
    
    toastHeader.className = `toast-header ${typeConfig.bg} text-white`;
    if (icon) icon.className = `fas ${typeConfig.icon} me-2`;
    toastBody.textContent = message;
    
    try {
        const toast = new bootstrap.Toast(toastEl, { delay: 3000 });
        toast.show();
    } catch (error) {
        console.error('Erreur lors de l\'affichage du toast:', error);
    }
};

// Initialiser le cercle de progression
const initCircularProgress = () => {
    const progressElement = document.querySelector('.circular-progress');
    if (!progressElement) return;
    
    const progress = parseInt(progressElement.dataset.progress) || 0;
    const circumference = 2 * Math.PI * 54;
    const offset = circumference - (progress / 100) * circumference;
    
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

// Ajouter des styles personnalisés
const addCustomStyles = () => {
    if (document.getElementById('custom-preview-styles')) return;
    
    const style = document.createElement('style');
    style.id = 'custom-preview-styles';
    style.textContent = `
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        @keyframes bounceIn {
            0% {
                transform: scale(0.3);
                opacity: 0;
            }
            50% {
                transform: scale(1.05);
                opacity: 0.8;
            }
            70% {
                transform: scale(0.9);
                opacity: 0.9;
            }
            100% {
                transform: scale(1);
                opacity: 1;
            }
        }
        
        .file-actions {
            display: flex;
            gap: 5px;
            opacity: 0;
            transition: opacity 0.2s ease;
        }
        
        .file-item:hover .file-actions {
            opacity: 1;
        }
        
        .file-name-wrapper {
            cursor: pointer;
            padding: 4px 8px;
            border-radius: 4px;
            transition: background 0.2s ease;
        }
        
        .file-name-wrapper:hover {
            background: rgba(74, 108, 247, 0.1);
        }
        
        .image-preview-container {
            text-align: center;
            padding: 20px;
            background: #fff;
        }
        
        .image-preview-container img {
            max-width: 100%;
            max-height: 70vh;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            border-radius: 8px;
        }
        
        .pdf-preview-container {
            width: 100%;
            background: #525659;
            padding: 20px;
        }
        
        .pdf-preview-container embed {
            border-radius: 4px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        }
        
        .file-info-preview {
            padding: 30px;
        }
        
        .file-icon-large {
            animation: bounceIn 0.5s ease;
        }
        
        .file-name-large {
            font-size: 1.2rem;
            font-weight: 600;
            color: #333;
            word-break: break-word;
        }
        
        .file-details .badge {
            font-size: 0.9rem;
            padding: 8px 12px;
        }
        
        .file-metadata {
            color: #666;
            font-size: 0.95rem;
        }
        
        .file-metadata i {
            width: 20px;
            color: #999;
        }
        
        .drop-zone.dragover {
            border-color: #4a6cf7 !important;
            background: rgba(74, 108, 247, 0.1) !important;
        }
        
        @media (max-width: 768px) {
            .file-actions {
                opacity: 1;
            }
            
            .file-actions .btn-link {
                padding: 6px 10px;
            }
        }
    `;
    document.head.appendChild(style);
};

// ============================================
// INITIALISATION
// ============================================

// Initialiser les modals de façon sécurisée
const initializeModals = () => {
    try {
        const editModalElement = document.getElementById('editTaskModal');
        const deleteModalElement = document.getElementById('deleteModal');
        const previewModalElement = document.getElementById('filePreviewModal');
        const galleryModalElement = document.getElementById('imageGalleryModal');
        
        if (editModalElement) {
            taskEditModal = new bootstrap.Modal(editModalElement);
            console.log('Modal d\'édition initialisé');
        }
        
        if (deleteModalElement) {
            taskDeleteModal = new bootstrap.Modal(deleteModalElement);
            console.log('Modal de suppression initialisé');
        }
        
        if (previewModalElement) {
            taskFilePreviewModal = new bootstrap.Modal(previewModalElement);
            console.log('Modal de prévisualisation initialisé');
        } else {
            console.warn('Modal de prévisualisation non trouvé');
        }
        
        if (galleryModalElement) {
            taskImageGalleryModal = new bootstrap.Modal(galleryModalElement);
            console.log('Modal de galerie initialisé');
        } else {
            console.warn('Modal de galerie non trouvé');
        }
    } catch (error) {
        console.error('Erreur lors de l\'initialisation des modals:', error);
    }
};

// Configuration AJAX
const setupAjax = () => {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'X-Requested-With': 'XMLHttpRequest'
        }
    });
};

// Point d'entrée principal
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM chargé, initialisation des composants...');
    
    initializeModals();
    setupAjax();
    initCircularProgress();
    initCommentForms();
    initFileUploads();
    setupEditButton();
    setupCostCalculation();
    setupEditForm();
    addCustomStyles();
    
    console.log('Initialisation terminée');
});

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

const editModalElement = document.getElementById('editTaskModal');
if (editModalElement) {
    editModalElement.addEventListener('hide.bs.modal', function(e) {
        if (formChanged) {
            if (!confirm('Vous avez des modifications non enregistrées. Voulez-vous vraiment quitter ?')) {
                e.preventDefault();
            }
        }
    });
    
    editModalElement.addEventListener('hidden.bs.modal', function() {
        formChanged = false;
    });
}

// Formater une date pour l'affichage
window.formatDate = function(dateString) {
    if (!dateString) return 'Non définie';
    const date = new Date(dateString);
    return date.toLocaleDateString('fr-FR', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
};
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
        /* File Preview Styles */
.file-preview-container {
    min-height: 400px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f8f9fa;
}

.image-preview-container {
    text-align: center;
    padding: 20px;
    background: #fff;
}

.image-preview-container img {
    max-width: 100%;
    max-height: 70vh;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    border-radius: 8px;
}

.pdf-preview-container {
    width: 100%;
    background: #525659;
    padding: 20px;
}

.pdf-preview-container embed {
    border-radius: 4px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
}

.file-info-preview {
    padding: 30px;
}

.file-icon-large {
    animation: bounceIn 0.5s ease;
}

.file-name-large {
    font-size: 1.2rem;
    font-weight: 600;
    color: #333;
    word-break: break-word;
}

.file-details .badge {
    font-size: 0.9rem;
    padding: 8px 12px;
}

.file-metadata {
    color: #666;
    font-size: 0.95rem;
}

.file-metadata i {
    width: 20px;
    color: #999;
}

/* File item actions */
.file-actions {
    display: flex;
    gap: 5px;
    opacity: 0;
    transition: opacity 0.2s ease;
}

.file-item:hover .file-actions {
    opacity: 1;
}

.file-actions .btn-link {
    padding: 4px 8px;
    font-size: 0.9rem;
}

.file-actions .btn-link:hover {
    background: rgba(0,0,0,0.05);
    border-radius: 4px;
}

/* File name wrapper */
.file-name-wrapper {
    cursor: pointer;
    padding: 4px 8px;
    border-radius: 4px;
    transition: background 0.2s ease;
}

.file-name-wrapper:hover {
    background: rgba(74, 108, 247, 0.1);
}

/* Gallery Modal */
#imageGalleryModal .modal-content {
    border: none;
    border-radius: 12px;
    overflow: hidden;
}

#imageGalleryModal .modal-header {
    background: #1a1a1a;
    border-bottom: 1px solid #333;
}

#imageGalleryModal .modal-footer {
    background: #1a1a1a;
    border-top: 1px solid #333;
}

#galleryImage {
    transition: transform 0.3s ease;
}

#prevImageBtn, #nextImageBtn {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    opacity: 0.7;
    transition: all 0.3s ease;
    z-index: 1000;
}

#prevImageBtn:hover, #nextImageBtn:hover {
    opacity: 1;
    transform: scale(1.1);
}

/* Animations */
@keyframes bounceIn {
    0% {
        transform: scale(0.3);
        opacity: 0;
    }
    50% {
        transform: scale(1.05);
        opacity: 0.8;
    }
    70% {
        transform: scale(0.9);
        opacity: 0.9;
    }
    100% {
        transform: scale(1);
        opacity: 1;
    }
}

/* Loading state */
.preview-loading {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    min-height: 400px;
}

.preview-loading .spinner-border {
    width: 3rem;
    height: 3rem;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .file-actions {
        opacity: 1;
    }
    
    .file-actions .btn-link {
        padding: 6px 10px;
    }
    
    #prevImageBtn, #nextImageBtn {
        width: 40px;
        height: 40px;
    }
}
/* Styles pour la zone d'upload */
.file-upload-area {
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    padding: 15px;
    background: #fafafa;
}

.file-upload-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;
    font-size: 0.9rem;
}

.file-upload-title {
    font-weight: 600;
    color: #495057;
}

.file-upload-hint {
    color: #999;
    font-size: 0.8rem;
}

.drop-zone {
    border: 2px dashed #ccc;
    border-radius: 8px;
    padding: 20px;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
    background: white;
}

.drop-zone:hover,
.drop-zone.dragover {
    border-color: #4a6cf7;
    background: rgba(74, 108, 247, 0.05);
}

.drop-zone i {
    color: #999;
}

.drop-zone p {
    margin: 0;
    color: #666;
}

.browse-link {
    color: #4a6cf7;
    text-decoration: underline;
    cursor: pointer;
}

.browse-link:hover {
    color: #3a56e4;
}

/* File Preview */
.file-preview-container {
    margin-top: 15px;
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.file-preview-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 8px 12px;
    background: white;
    border: 1px solid #e0e0e0;
    border-radius: 6px;
    animation: slideIn 0.3s ease;
}

.file-preview-info {
    display: flex;
    align-items: center;
    gap: 10px;
    flex: 1;
    min-width: 0;
}

.file-preview-icon {
    color: #666;
    font-size: 1.2rem;
    flex-shrink: 0;
}

.file-preview-icon.image {
    color: #06b48a;
}

.file-preview-icon.pdf {
    color: #dc3545;
}

.file-preview-icon.word {
    color: #45b7d1;
}

.file-preview-name {
    font-size: 0.9rem;
    font-weight: 500;
    color: #333;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.file-preview-size {
    font-size: 0.8rem;
    color: #999;
    margin-left: 5px;
}

.file-preview-remove {
    color: #dc3545;
    cursor: pointer;
    padding: 4px;
    border-radius: 4px;
    transition: all 0.2s ease;
    flex-shrink: 0;
}

.file-preview-remove:hover {
    background: rgba(220, 53, 69, 0.1);
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
    </style>
@endsection