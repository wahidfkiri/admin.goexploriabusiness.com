{{-- resources/views/projects/tasks.blade.php --}}
@extends('layouts.app')

@section('content')
    
    <!-- MAIN CONTENT -->
    <main class="dashboard-content">
        <!-- Page Header with Project Info -->
        <div class="page-header">
            <div class="page-header-left">
                <a href="{{ route('projects.index') }}" class="btn btn-outline-secondary me-3">
                    <i class="fas fa-arrow-left me-2"></i>Retour aux projets
                </a>
                <h1 class="page-title">
                    <span class="page-title-icon"><i class="fas fa-tasks"></i></span>
                    Tâches du projet : {{ $project->name }}
                </h1>
            </div>
            
            <div class="page-actions">
                <button class="btn btn-outline-secondary" id="toggleFilterBtn">
                    <i class="fas fa-sliders-h me-2"></i>Filtres
                </button>
                <a href="{{ url('projects.tasks.create', $project) }}" class="btn btn-primary">
                    <i class="fas fa-plus-circle me-2"></i>Nouvelle Tâche
                </a>
            </div>
        </div>
        
        <!-- Project Info Banner -->
        <div class="project-info-banner">
            <div class="project-info-banner-content">
                <div class="project-basic-info">
                    <div class="project-icon-large" style="background: {{ $projectColor ?? '#45b7d1' }}">
                        <i class="fas fa-project-diagram"></i>
                    </div>
                    <div class="project-details">
                        <h2>{{ $project->name }}</h2>
                        <div class="project-meta-banner">
                            @if($project->client)
                                <span class="project-meta-item">
                                    <i class="fas fa-building me-1"></i>{{ $project->client->name }}
                                </span>
                            @endif
                            @if($project->contract_number)
                                <span class="project-meta-item">
                                    <i class="fas fa-file-contract me-1"></i>Contrat: {{ $project->contract_number }}
                                </span>
                            @endif
                            <span class="project-meta-item">
                                <i class="fas fa-calendar me-1"></i>Début: {{ $project->start_date ? \Carbon\Carbon::parse($project->start_date)->format('d/m/Y') : 'Non défini' }}
                            </span>
                            <span class="project-meta-item">
                                <i class="fas fa-calendar-check me-1"></i>Fin: {{ $project->end_date ? \Carbon\Carbon::parse($project->end_date)->format('d/m/Y') : 'Non défini' }}
                            </span>
                        </div>
                    </div>
                </div>
                
                <div class="project-stats-banner">
                    <div class="stat-item">
                        <span class="stat-value">{{ $tasks->total() }}</span>
                        <span class="stat-label">Total tâches</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-value">{{ $completedTasks }}</span>
                        <span class="stat-label">Terminées</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-value">{{ $inProgressTasks }}</span>
                        <span class="stat-label">En cours</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-value">{{ $pendingTasks }}</span>
                        <span class="stat-label">En attente</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-value">{{ $overdueTasks }}</span>
                        <span class="stat-label">En retard</span>
                    </div>
                </div>
            </div>
            
            <!-- Project Progress -->
            <div class="project-progress-banner">
                <div class="progress-info">
                    <span class="progress-label">Avancement global</span>
                    <span class="progress-percentage">{{ $projectProgress }}%</span>
                </div>
                <div class="progress-modern large">
                    <div class="progress-bar-modern" style="width: {{ $projectProgress }}%; background: {{ Vendor\Project\Helpers\Helper::getProgressColor($projectProgress) }}"></div>
                </div>
                <div class="progress-details">
                    <span><i class="fas fa-check-circle text-success me-1"></i>{{ $completedTasks }} terminées</span>
                    <span><i class="fas fa-spinner text-primary me-1"></i>{{ $inProgressTasks }} en cours</span>
                    <span><i class="fas fa-clock text-warning me-1"></i>{{ $pendingTasks }} en attente</span>
                </div>
            </div>
        </div>
        
        <!-- Filter Section (Initially Hidden) -->
        <div class="filter-section-modern" id="filterSection" style="display: none;">
            <div class="filter-header-modern">
                <h3 class="filter-title-modern">Filtres</h3>
                <div class="filter-actions-modern">
                    <button class="btn btn-sm btn-outline-secondary" id="clearFiltersBtn">
                        <i class="fas fa-times me-1"></i>Effacer
                    </button>
                    <button class="btn btn-sm btn-primary" id="applyFiltersBtn">
                        <i class="fas fa-check me-1"></i>Appliquer
                    </button>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <label for="filterUser" class="form-label-modern">Assigné à</label>
                    <select class="form-select-modern" id="filterUser">
                        <option value="">Tous</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="filterStatus" class="form-label-modern">Statut</label>
                    <select class="form-select-modern" id="filterStatus">
                        <option value="">Tous</option>
                        @foreach($statuses as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="filterEtablissement" class="form-label-modern">Établissement</label>
                    <select class="form-select-modern" id="filterEtablissement">
                        <option value="">Tous</option>
                        @foreach($etablissements as $etablissement)
                            <option value="{{ $etablissement->id }}">{{ $etablissement->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="filterPriority" class="form-label-modern">Priorité</label>
                    <select class="form-select-modern" id="filterPriority">
                        <option value="">Toutes</option>
                        <option value="haute">Haute</option>
                        <option value="moyenne">Moyenne</option>
                        <option value="basse">Basse</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="filterDateRange" class="form-label-modern">Période</label>
                    <select class="form-select-modern" id="filterDateRange">
                        <option value="">Toutes</option>
                        <option value="today">Aujourd'hui</option>
                        <option value="week">Cette semaine</option>
                        <option value="month">Ce mois</option>
                        <option value="overdue">En retard</option>
                    </select>
                </div>
            </div>
        </div>
        
        <!-- Main Card - Modern Design -->
        <div class="main-card-modern">
            <div class="card-header-modern">
                <h3 class="card-title-modern">Liste des Tâches</h3>
                <div class="search-container">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" class="search-input" placeholder="Rechercher une tâche..." id="searchInput" value="{{ request('search') }}">
                </div>
            </div>
            
            <div class="card-body-modern">
                <!-- Loading Spinner -->
                <div class="spinner-container" id="loadingSpinner" style="display: none;">
                    <div class="spinner-border text-primary spinner" role="status">
                        <span class="visually-hidden">Chargement...</span>
                    </div>
                </div>
                
                <!-- Tasks Table -->
                <div class="table-container-modern" id="tableContainer">
                    <table class="modern-table">
                        <thead>
                            <tr>
                                <th>Tâche</th>
                                <th>Assigné à</th>
                                <th>Échéance</th>
                                <th>Statut</th>
                                <th>Avancement</th>
                                <th>Priorité</th>
                                <th style="text-align: center;">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="tasksTableBody">
                            @forelse($tasks as $task)
                                <tr id="task-row-{{ $task->id }}" class="task-row {{ $task->isOverdue() ? 'overdue-row' : '' }}">
                                    <td>
                                        <div class="task-name-cell">
                                            <div class="task-name-modern">
                                                <div class="task-icon-modern" style="background: {{ Vendor\Project\Helpers\Helper::getTaskColor($task->status) }}">
                                                    <i class="fas fa-tasks"></i>
                                                </div>
                                                <div>
                                                    <div class="task-name-text">
                                                        <a href="#" onclick="showTaskDetails({{ $task->id }}); return false;">{{ $task->name }}</a>
                                                        @if($task->contract_number)
                                                            <small class="text-muted ms-2">(Contrat: {{ $task->contract_number }})</small>
                                                        @endif
                                                    </div>
                                                    <small class="text-muted">
                                                        <i class="fas fa-building me-1"></i>{{ $task->etablissement->name ?? 'Établissement non défini' }}
                                                        @if($task->contact_name)
                                                            <span class="ms-2"><i class="fas fa-user me-1"></i>{{ $task->contact_name }}</span>
                                                        @endif
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($task->user)
                                            <div class="user-info">
                                                <div class="user-avatar-sm" style="background: {{ Vendor\Project\Helpers\Helper::getUserColor($task->user->name) }}">
                                                    {{ Vendor\Project\Helpers\Helper::getInitials($task->user->name) }}
                                                </div>
                                                <div class="user-details">
                                                    <div class="user-name">{{ $task->user->name }}</div>
                                                    <small class="text-muted">{{ $task->user->email }}</small>
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-muted">Non assigné</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="deadline-info">
                                            <div class="deadline-date {{ $task->isOverdue() ? 'deadline-overdue' : '' }}">
                                                <i class="fas fa-calendar-day me-1"></i>{{ $task->due_date ? $task->due_date->format('d/m/Y') : 'Non définie' }}
                                            </div>
                                            @if($task->due_date && !$task->isCompleted())
                                                @php
                                                    $daysRemaining = now()->diffInDays($task->due_date, false);
                                                @endphp
                                                @if($daysRemaining > 0 && $daysRemaining <= 7)
                                                    <small class="text-warning">
                                                        <i class="fas fa-exclamation-triangle me-1"></i>J-{{ $daysRemaining }}
                                                    </small>
                                                @elseif($daysRemaining > 7)
                                                    <small class="text-muted">J-{{ $daysRemaining }}</small>
                                                @elseif($daysRemaining < 0)
                                                    <small class="text-danger">
                                                        <i class="fas fa-exclamation-circle me-1"></i>Retard: {{ abs($daysRemaining) }}j
                                                    </small>
                                                @endif
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        {!! Vendor\Project\Helpers\Helper::getStatusBadge($task->formatted_status, $task->status_color) !!}
                                    </td>
                                    <td>
                                        <div class="progress-container">
                                            <div class="progress-percentage">{{ $task->getProgress() }}%</div>
                                            <div class="progress-modern">
                                                <div class="progress-bar-modern" style="width: {{ $task->getProgress() }}%; background: {{ Vendor\Project\Helpers\Helper::getProgressColor($task->getProgress()) }}"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        {!! Vendor\Project\Helpers\Helper::getPriorityBadge($task->priority ?? 'moyenne') !!}
                                    </td>
                                    <td>
                                        <div class="task-actions-modern">
                                            <button class="action-btn-modern view-btn-modern" title="Voir détails" onclick="showTaskDetails({{ $task->id }})">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <a href="{{ url('projects.tasks.edit', [$project, $task]) }}" class="action-btn-modern edit-btn-modern" title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button class="action-btn-modern delete-btn-modern" title="Supprimer" onclick="showDeleteConfirmation({{ $task->id }})">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <div class="empty-state-modern">
                                            <div class="empty-icon-modern">
                                                <i class="fas fa-tasks"></i>
                                            </div>
                                            <h3 class="empty-title-modern">Aucune tâche trouvée</h3>
                                            <p class="empty-text-modern">Ce projet n'a pas encore de tâches.</p>
                                            <a href="{{ url('projects.tasks.create', $project) }}" class="btn btn-primary">
                                                <i class="fas fa-plus-circle me-2"></i>Créer une tâche
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Pagination -->
            @if($tasks->hasPages())
                <div class="pagination-container-modern">
                    <div class="pagination-info-modern">
                        Affichage de {{ $tasks->firstItem() }} à {{ $tasks->lastItem() }} sur {{ $tasks->total() }} tâches
                    </div>
                    
                    <nav aria-label="Page navigation">
                        <ul class="modern-pagination">
                            {{-- Previous Page Link --}}
                            @if($tasks->onFirstPage())
                                <li class="page-item disabled">
                                    <span class="page-link-modern"><i class="fas fa-chevron-left"></i></span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link-modern" href="{{ $tasks->previousPageUrl() }}" rel="prev">
                                        <i class="fas fa-chevron-left"></i>
                                    </a>
                                </li>
                            @endif

                            {{-- Pagination Elements --}}
                            @foreach($tasks->getUrlRange(max(1, $tasks->currentPage() - 2), min($tasks->lastPage(), $tasks->currentPage() + 2)) as $page => $url)
                                @if($page == $tasks->currentPage())
                                    <li class="page-item active">
                                        <span class="page-link-modern">{{ $page }}</span>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a class="page-link-modern" href="{{ $url }}">{{ $page }}</a>
                                    </li>
                                @endif
                            @endforeach

                            {{-- Next Page Link --}}
                            @if($tasks->hasMorePages())
                                <li class="page-item">
                                    <a class="page-link-modern" href="{{ $tasks->nextPageUrl() }}" rel="next">
                                        <i class="fas fa-chevron-right"></i>
                                    </a>
                                </li>
                            @else
                                <li class="page-item disabled">
                                    <span class="page-link-modern"><i class="fas fa-chevron-right"></i></span>
                                </li>
                            @endif
                        </ul>
                    </nav>
                </div>
            @endif
        </div>
        
        <!-- Floating Action Button -->
        <a href="{{ url('projects.tasks.create', $project) }}" class="fab-modern">
            <i class="fas fa-plus"></i>
        </a>
    </main>
    
    <!-- TASK DETAILS MODAL -->
    <div class="modal fade task-details-modal" id="taskDetailsModal" tabindex="-1" aria-labelledby="taskDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="taskDetailsModalLabel">
                        <i class="fas fa-info-circle me-2"></i>Détails de la tâche
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="taskDetailsContent">
                    <!-- Task details will be loaded here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                    <a href="#" class="btn btn-primary" id="editTaskFromModal">
                        <i class="fas fa-edit me-2"></i>Modifier
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- DELETE CONFIRMATION MODAL -->
    <div class="modal fade delete-confirm-modal" id="deleteConfirmationModal" tabindex="-1" aria-labelledby="deleteConfirmationModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <div class="delete-icon">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <h4 class="delete-title">Confirmer la suppression</h4>
                    <p class="delete-message">Êtes-vous sûr de vouloir supprimer cette tâche ? Cette action est irréversible.</p>
                    
                    <div class="task-to-delete" id="taskToDeleteInfo">
                        <!-- Task info will be loaded here -->
                    </div>
                    
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <strong>Attention :</strong> Cette action est irréversible.
                    </div>
                </div>
                <div class="modal-footer border-0 justify-content-center">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="cancelDeleteBtn">
                        <i class="fas fa-times me-2"></i>Annuler
                    </button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn">
                        <span class="btn-text">
                            <i class="fas fa-trash me-2"></i>Supprimer définitivement
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Configuration
        let projectId = {{ $project->id }};
        let allTasks = @json($tasks->items());
        let taskToDelete = null;

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            setupEventListeners();
        });

        // Show task details in modal
        const showTaskDetails = (taskId) => {
            const task = allTasks.find(t => t.id === taskId);
            
            if (!task) {
                showAlert('danger', 'Tâche non trouvée');
                return;
            }
            
            const modalContent = document.getElementById('taskDetailsContent');
            const progress = task.progress || 0;
            const isOverdue = task.is_overdue || false;
            
            modalContent.innerHTML = `
                <div class="task-details">
                    <div class="task-details-header">
                        <h4 class="task-details-title">${escapeHtml(task.name)}</h4>
                        <div class="task-details-badges">
                            ${getStatusBadge(task.formatted_status, task.status_color)}
                            ${isOverdue ? '<span class="badge bg-danger ms-2">En retard</span>' : ''}
                        </div>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="detail-section">
                                <h5 class="detail-section-title">Informations générales</h5>
                                <div class="detail-item">
                                    <span class="detail-label">Projet:</span>
                                    <span class="detail-value">${escapeHtml(task.project?.name || '{{ $project->name }}')}</span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Établissement:</span>
                                    <span class="detail-value">${escapeHtml(task.etablissement?.name || 'Non spécifié')}</span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">N° Contrat:</span>
                                    <span class="detail-value">${task.contract_number || 'Non défini'}</span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Contact:</span>
                                    <span class="detail-value">${escapeHtml(task.contact_name || 'Non spécifié')}</span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Localisation:</span>
                                    <span class="detail-value">${escapeHtml(task.location || 'Non spécifiée')}</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="detail-section">
                                <h5 class="detail-section-title">Dates et échéances</h5>
                                <div class="detail-item">
                                    <span class="detail-label">Date d'échéance:</span>
                                    <span class="detail-value ${isOverdue ? 'text-danger fw-bold' : ''}">${task.due_date ? new Date(task.due_date).toLocaleDateString('fr-FR') : 'Non définie'}</span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Date de livraison:</span>
                                    <span class="detail-value">${task.delivery_date ? new Date(task.delivery_date).toLocaleDateString('fr-FR') : 'Non définie'}</span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Date de test:</span>
                                    <span class="detail-value">${task.test_date ? new Date(task.test_date).toLocaleDateString('fr-FR') : 'Non définie'}</span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Date d'intégration:</span>
                                    <span class="detail-value">${task.integration_date ? new Date(task.integration_date).toLocaleDateString('fr-FR') : 'Non définie'}</span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Date push prod:</span>
                                    <span class="detail-value">${task.push_prod_date ? new Date(task.push_prod_date).toLocaleDateString('fr-FR') : 'Non définie'}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <div class="detail-section">
                                <h5 class="detail-section-title">Coûts et heures</h5>
                                <div class="detail-item">
                                    <span class="detail-label">Heures estimées:</span>
                                    <span class="detail-value">${task.estimated_hours || 0} h</span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Taux horaire:</span>
                                    <span class="detail-value">${formatCurrency(task.hourly_rate)}</span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Coût estimé:</span>
                                    <span class="detail-value fw-bold">${formatCurrency(task.estimated_cost)}</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="detail-section">
                                <h5 class="detail-section-title">Responsables</h5>
                                <div class="detail-item">
                                    <span class="detail-label">Responsable principal:</span>
                                    <span class="detail-value">${escapeHtml(task.user?.name || 'Non assigné')}</span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Manager général:</span>
                                    <span class="detail-value">${escapeHtml(task.general_manager?.name || 'Non assigné')}</span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Client manager:</span>
                                    <span class="detail-value">${escapeHtml(task.client_manager?.name || 'Non assigné')}</span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Créé par:</span>
                                    <span class="detail-value">${escapeHtml(task.creator?.name || 'Inconnu')}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    ${task.details ? `
                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="detail-section">
                                    <h5 class="detail-section-title">Détails</h5>
                                    <p class="detail-description">${escapeHtml(task.details)}</p>
                                </div>
                            </div>
                        </div>
                    ` : ''}
                    
                    ${task.test_details ? `
                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="detail-section">
                                    <h5 class="detail-section-title">Détails des tests</h5>
                                    <p class="detail-description">${escapeHtml(task.test_details)}</p>
                                </div>
                            </div>
                        </div>
                    ` : ''}
                    
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="detail-section">
                                <h5 class="detail-section-title">Avancement</h5>
                                <div class="progress-container mb-3">
                                    <div class="progress-percentage">${progress}%</div>
                                    <div class="progress-modern">
                                        <div class="progress-bar-modern" style="width: ${progress}%; background: ${getProgressColor(progress)}"></div>
                                    </div>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Approuvé par manager:</span>
                                    <span class="detail-value">
                                        ${task.is_approved_by_manager ? 
                                            `<span class="badge bg-success">Oui (par ${escapeHtml(task.approved_by?.name || 'inconnu')} le ${new Date(task.approved_at).toLocaleDateString('fr-FR')})</span>` : 
                                            '<span class="badge bg-warning">Non</span>'}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    ${task.module_url ? `
                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="detail-section">
                                    <h5 class="detail-section-title">Module URL</h5>
                                    <a href="${escapeHtml(task.module_url)}" target="_blank" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-external-link-alt me-2"></i>Accéder au module
                                    </a>
                                </div>
                            </div>
                        </div>
                    ` : ''}
                </div>
            `;
            
            // Update edit button link
            document.getElementById('editTaskFromModal').href = `/projects/${projectId}/tasks/${task.id}/edit`;
            
            // Show modal
            const detailsModal = new bootstrap.Modal(document.getElementById('taskDetailsModal'));
            detailsModal.show();
        };

        // Show delete confirmation modal
        const showDeleteConfirmation = (taskId) => {
            const task = allTasks.find(t => t.id === taskId);
            
            if (!task) {
                showAlert('danger', 'Tâche non trouvée');
                return;
            }
            
            taskToDelete = task;
            
            document.getElementById('taskToDeleteInfo').innerHTML = `
                <div class="task-info">
                    <div class="task-info-icon" style="background: ${getTaskColor(task.status)}">
                        <i class="fas fa-tasks fa-2x"></i>
                    </div>
                    <div>
                        <div class="task-info-name">${escapeHtml(task.name)}</div>
                        <div class="task-info-details">
                            <div><strong>Projet:</strong> {{ $project->name }}</div>
                            <div><strong>Assigné à:</strong> ${escapeHtml(task.user?.name || 'N/A')}</div>
                            <div><strong>Statut:</strong> ${task.formatted_status}</div>
                            <div><strong>Échéance:</strong> ${task.due_date ? new Date(task.due_date).toLocaleDateString('fr-FR') : 'N/A'}</div>
                        </div>
                    </div>
                </div>
            `;
            
            // Reset delete button state
            const deleteBtn = document.getElementById('confirmDeleteBtn');
            deleteBtn.innerHTML = `
                <span class="btn-text">
                    <i class="fas fa-trash me-2"></i>Supprimer définitivement
                </span>
            `;
            deleteBtn.disabled = false;
            
            // Show modal
            const deleteModal = new bootstrap.Modal(document.getElementById('deleteConfirmationModal'));
            deleteModal.show();
        };

        // Delete task
        const deleteTask = () => {
            if (!taskToDelete) {
                showAlert('danger', 'Aucune tâche à supprimer');
                return;
            }
            
            const taskId = taskToDelete.id;
            const deleteBtn = document.getElementById('confirmDeleteBtn');
            
            // Show processing animation
            deleteBtn.innerHTML = `
                <span class="btn-text" style="display: none;">
                    <i class="fas fa-trash me-2"></i>Supprimer définitivement
                </span>
                <div class="spinner-border spinner-border-sm text-light" role="status">
                    <span class="visually-hidden">Suppression...</span>
                </div>
                Suppression en cours...
            `;
            deleteBtn.disabled = true;
            
            // Add deleting animation to table row
            const row = document.getElementById(`task-row-${taskId}`);
            if (row) {
                row.classList.add('deleting-row');
            }
            
            // Send DELETE request
            $.ajax({
                url: `/projects/${projectId}/tasks/${taskId}`,
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function(response) {
                    // Hide modal
                    const deleteModal = bootstrap.Modal.getInstance(document.getElementById('deleteConfirmationModal'));
                    deleteModal.hide();
                    
                    if (response.success) {
                        // Remove row with animation
                        if (row) {
                            setTimeout(() => {
                                row.remove();
                                showAlert('success', 'Tâche supprimée avec succès !');
                                
                                // Reload page if no tasks left
                                if (document.querySelectorAll('.task-row').length === 0) {
                                    setTimeout(() => {
                                        window.location.reload();
                                    }, 1000);
                                }
                            }, 300);
                        } else {
                            window.location.reload();
                        }
                    } else {
                        if (row) row.classList.remove('deleting-row');
                        showAlert('danger', response.message || 'Erreur lors de la suppression');
                    }
                },
                error: function(xhr) {
                    // Hide modal
                    const deleteModal = bootstrap.Modal.getInstance(document.getElementById('deleteConfirmationModal'));
                    deleteModal.hide();
                    
                    // Remove deleting animation
                    if (row) {
                        row.classList.remove('deleting-row');
                    }
                    
                    showAlert('danger', 'Erreur lors de la suppression');
                }
            });
        };

        // Helper functions
        const getInitials = (name) => {
            if (!name) return '?';
            return name.split(' ').map(n => n[0]).join('').toUpperCase().substring(0, 2);
        };

        const escapeHtml = (text) => {
            if (!text) return '';
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        };

        const getTaskColor = (status) => {
            const colors = {
                'pending': '#f39c12',
                'in_progress': '#3498db',
                'test': '#9b59b6',
                'integrated': '#1abc9c',
                'delivered': '#2ecc71',
                'approved': '#27ae60',
                'cancelled': '#e74c3c'
            };
            return colors[status] || '#95a5a6';
        };

        const getUserColor = (userName) => {
            const colors = ['#45b7d1', '#96ceb4', '#feca57', '#ff6b6b', '#9b59b6'];
            const index = (userName?.length || 0) % colors.length;
            return colors[index];
        };

        const getProgressColor = (progress) => {
            if (progress < 30) return '#ef476f';
            if (progress < 70) return '#ffd166';
            return '#06b48a';
        };

        const getStatusBadge = (status, color) => {
            const statusColors = {
                'En attente': 'warning',
                'En cours': 'primary',
                'En test': 'info',
                'Intégré': 'purple',
                'Livré': 'success',
                'Approuvé': 'success',
                'Annulé': 'danger'
            };
            
            const badgeColor = statusColors[status] || 'secondary';
            return `<span class="badge bg-${badgeColor}"><i class="fas fa-circle me-1" style="font-size: 0.5rem;"></i>${status}</span>`;
        };

        const getPriorityBadge = (priority) => {
            const badges = {
                'haute': '<span class="badge bg-danger"><i class="fas fa-arrow-up me-1"></i>Haute</span>',
                'moyenne': '<span class="badge bg-warning"><i class="fas fa-minus me-1"></i>Moyenne</span>',
                'basse': '<span class="badge bg-success"><i class="fas fa-arrow-down me-1"></i>Basse</span>'
            };
            return badges[priority] || badges['moyenne'];
        };

        const formatCurrency = (amount) => {
            if (!amount) return '0 €';
            return new Intl.NumberFormat('fr-FR', { style: 'currency', currency: 'EUR' }).format(amount);
        };

        // Show alert
        const showAlert = (type, message) => {
            const existingAlert = document.querySelector('.alert-custom-modern');
            if (existingAlert) existingAlert.remove();
            
            const alert = document.createElement('div');
            alert.className = `alert alert-${type} alert-custom-modern alert-dismissible fade show`;
            alert.innerHTML = `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            
            document.body.appendChild(alert);
            
            setTimeout(() => {
                if (alert.parentNode) alert.remove();
            }, 5000);
        };

        // Setup event listeners
        const setupEventListeners = () => {
            // Toggle filter section
            const toggleFilterBtn = document.getElementById('toggleFilterBtn');
            const filterSection = document.getElementById('filterSection');
            
            if (toggleFilterBtn && filterSection) {
                toggleFilterBtn.addEventListener('click', () => {
                    const isVisible = filterSection.style.display === 'block';
                    filterSection.style.display = isVisible ? 'none' : 'block';
                    toggleFilterBtn.innerHTML = isVisible 
                        ? '<i class="fas fa-sliders-h me-2"></i>Filtres'
                        : '<i class="fas fa-times me-2"></i>Masquer les filtres';
                });
            }
            
            // Search form submit
            const searchInput = document.getElementById('searchInput');
            if (searchInput) {
                searchInput.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        const searchParams = new URLSearchParams(window.location.search);
                        searchParams.set('search', this.value);
                        searchParams.set('page', '1');
                        window.location.href = `${window.location.pathname}?${searchParams.toString()}`;
                    }
                });
            }
            
            // Apply filters
            const applyFiltersBtn = document.getElementById('applyFiltersBtn');
            if (applyFiltersBtn) {
                applyFiltersBtn.addEventListener('click', () => {
                    const searchParams = new URLSearchParams();
                    
                    if (document.getElementById('filterUser').value) {
                        searchParams.set('user_id', document.getElementById('filterUser').value);
                    }
                    if (document.getElementById('filterStatus').value) {
                        searchParams.set('status', document.getElementById('filterStatus').value);
                    }
                    if (document.getElementById('filterEtablissement').value) {
                        searchParams.set('etablissement_id', document.getElementById('filterEtablissement').value);
                    }
                    if (document.getElementById('filterPriority').value) {
                        searchParams.set('priority', document.getElementById('filterPriority').value);
                    }
                    if (document.getElementById('filterDateRange').value) {
                        searchParams.set('date_range', document.getElementById('filterDateRange').value);
                    }
                    if (document.getElementById('searchInput').value) {
                        searchParams.set('search', document.getElementById('searchInput').value);
                    }
                    
                    searchParams.set('page', '1');
                    window.location.href = `${window.location.pathname}?${searchParams.toString()}`;
                });
            }
            
            // Clear filters
            const clearFiltersBtn = document.getElementById('clearFiltersBtn');
            if (clearFiltersBtn) {
                clearFiltersBtn.addEventListener('click', () => {
                    window.location.href = window.location.pathname;
                });
            }
            
            // Confirm delete button
            const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
            if (confirmDeleteBtn) {
                confirmDeleteBtn.addEventListener('click', deleteTask);
            }
            
            // Reset delete modal when hidden
            const deleteModal = document.getElementById('deleteConfirmationModal');
            if (deleteModal) {
                deleteModal.addEventListener('hidden.bs.modal', function() {
                    taskToDelete = null;
                    const deleteBtn = document.getElementById('confirmDeleteBtn');
                    deleteBtn.innerHTML = `
                        <span class="btn-text">
                            <i class="fas fa-trash me-2"></i>Supprimer définitivement
                        </span>
                    `;
                    deleteBtn.disabled = false;
                });
            }
        };
    </script>

    <style>
        /* Styles spécifiques pour la page des tâches d'un projet */
        .page-header-left {
            display: flex;
            align-items: center;
        }

        .project-info-banner {
            background: white;
            border-radius: 16px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.06);
            border: 1px solid #eaeaea;
        }

        .project-info-banner-content {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 20px;
        }

        .project-basic-info {
            display: flex;
            gap: 20px;
        }

        .project-icon-large {
            width: 70px;
            height: 70px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 2rem;
            box-shadow: 0 8px 12px rgba(0,0,0,0.15);
        }

        .project-details h2 {
            font-size: 1.8rem;
            font-weight: 700;
            color: #333;
            margin: 0 0 10px 0;
        }

        .project-meta-banner {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .project-meta-item {
            font-size: 0.95rem;
            color: #666;
        }

        .project-stats-banner {
            display: flex;
            gap: 30px;
        }

        .stat-item {
            text-align: center;
            min-width: 80px;
        }

        .stat-item .stat-value {
            display: block;
            font-size: 1.8rem;
            font-weight: 700;
            color: #333;
            line-height: 1.2;
        }

        .stat-item .stat-label {
            font-size: 0.85rem;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .project-progress-banner {
            border-top: 1px solid #eaeaea;
            padding-top: 20px;
        }

        .progress-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .progress-label {
            font-weight: 600;
            color: #333;
        }

        .progress-percentage {
            font-weight: 700;
            font-size: 1.2rem;
        }

        .progress-modern.large {
            height: 12px;
            border-radius: 6px;
        }

        .progress-details {
            display: flex;
            gap: 20px;
            margin-top: 10px;
            font-size: 0.9rem;
        }

        .overdue-row {
            background-color: rgba(231, 76, 60, 0.05);
        }

        .overdue-row:hover {
            background-color: rgba(231, 76, 60, 0.1) !important;
        }

        .deadline-overdue {
            color: #e74c3c;
            font-weight: 600;
        }

        .task-name-modern {
            display: flex;
            align-items: flex-start;
            gap: 12px;
        }

        .task-icon-modern {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.2rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }

        .task-name-text {
            font-weight: 500;
            color: var(--text-color);
            margin-bottom: 2px;
        }

        .task-name-text a {
            color: #333;
            text-decoration: none;
        }

        .task-name-text a:hover {
            color: var(--primary-color);
            text-decoration: underline;
        }

        .task-actions-modern {
            display: flex;
            gap: 8px;
            justify-content: center;
        }

        .action-btn-modern {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: none;
            transition: all 0.3s ease;
            cursor: pointer;
            font-size: 0.9rem;
            text-decoration: none;
        }

        .view-btn-modern {
            background: linear-gradient(135deg, #45b7d1, #3a9bb8);
            color: white;
        }

        .view-btn-modern:hover {
            background: linear-gradient(135deg, #3a9bb8, #2d7f99);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(69, 183, 209, 0.3);
            color: white;
        }

        .edit-btn-modern {
            background: linear-gradient(135deg, #96ceb4, #7dba9a);
            color: white;
        }

        .edit-btn-modern:hover {
            background: linear-gradient(135deg, #7dba9a, #65a581);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(150, 206, 180, 0.3);
            color: white;
        }

        .delete-btn-modern {
            background: linear-gradient(135deg, #ef476f, #d4335f);
            color: white;
        }

        .delete-btn-modern:hover {
            background: linear-gradient(135deg, #d4335f, #b82a50);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(239, 71, 111, 0.3);
            color: white;
        }

        /* Task details modal */
        .task-details {
            padding: 10px;
        }

        .task-details-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f0f0f0;
        }

        .task-details-title {
            font-size: 1.4rem;
            font-weight: 600;
            color: #333;
            margin: 0;
        }

        .task-details-badges {
            display: flex;
            gap: 10px;
        }

        .detail-section {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 15px;
        }

        .detail-section-title {
            font-size: 1rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 2px solid #e9ecef;
        }

        .detail-item {
            display: flex;
            margin-bottom: 10px;
            font-size: 0.95rem;
        }

        .detail-label {
            width: 140px;
            font-weight: 500;
            color: #666;
        }

        .detail-value {
            flex: 1;
            color: #333;
        }

        .detail-description {
            background: white;
            padding: 12px;
            border-radius: 8px;
            border: 1px solid #e9ecef;
            color: #333;
            line-height: 1.6;
        }

        .task-info {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 15px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 10px;
        }

        .task-info-icon {
            width: 60px;
            height: 60px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .task-info-name {
            font-weight: 600;
            font-size: 1.1rem;
            color: var(--text-color);
            margin-bottom: 5px;
        }

        .task-info-details {
            font-size: 0.9rem;
            color: #666;
        }

        .task-info-details div {
            margin-bottom: 2px;
        }

        /* Animation for deleting row */
        .deleting-row {
            animation: slideOut 0.3s ease forwards;
            opacity: 0.5;
        }

        @keyframes slideOut {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(100%);
                opacity: 0;
            }
        }

        /* Custom badge colors */
        .bg-purple {
            background-color: #9b59b6 !important;
            color: white;
        }

        /* Responsive adjustments */
        @media (max-width: 992px) {
            .project-info-banner-content {
                flex-direction: column;
                gap: 20px;
            }

            .project-stats-banner {
                width: 100%;
                justify-content: space-between;
            }

            .page-header-left {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }

            .page-header-left .btn {
                margin-left: 0 !important;
            }
        }

        @media (max-width: 768px) {
            .project-basic-info {
                flex-direction: column;
                align-items: center;
                text-align: center;
            }

            .project-meta-banner {
                justify-content: center;
            }

            .project-stats-banner {
                flex-wrap: wrap;
                justify-content: center;
            }

            .progress-details {
                flex-direction: column;
                align-items: center;
                gap: 5px;
            }

            .task-name-modern {
                flex-direction: column;
                align-items: flex-start;
                gap: 8px;
            }

            .task-actions-modern {
                flex-direction: column;
                gap: 5px;
            }

            .action-btn-modern {
                width: 100%;
                height: 36px;
            }

            .user-info {
                flex-direction: column;
                align-items: flex-start;
            }

            .detail-item {
                flex-direction: column;
            }

            .detail-label {
                width: 100%;
                margin-bottom: 5px;
            }
        }
        /* ===== COMPOSANTS D'AVATAR ===== */

/* Petit avatar (32x32) - Utilisé dans les tableaux */
.user-avatar-sm {
    width: 32px;
    height: 32px;
    border-radius: 8px; /* Coins légèrement arrondis pour un look moderne */
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 600;
    font-size: 0.85rem;
    text-transform: uppercase;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
    flex-shrink: 0; /* Empêche l'avatar de rétrécir */
}

.user-avatar-sm:hover {
    transform: scale(1.05);
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
}

/* Avatar moyen (40x40) - Pour les cartes et en-têtes */
.user-avatar-md {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 600;
    font-size: 1rem;
    text-transform: uppercase;
    box-shadow: 0 3px 6px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
    flex-shrink: 0;
}

.user-avatar-md:hover {
    transform: scale(1.05);
    box-shadow: 0 5px 10px rgba(0,0,0,0.15);
}

/* Grand avatar (50x50) - Pour les profils */
.user-avatar-lg {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 600;
    font-size: 1.2rem;
    text-transform: uppercase;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
    flex-shrink: 0;
}

.user-avatar-lg:hover {
    transform: scale(1.05);
    box-shadow: 0 6px 12px rgba(0,0,0,0.15);
}

/* Très grand avatar (70x70) - Pour les pages de détail */
.user-avatar-xl {
    width: 70px;
    height: 70px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 600;
    font-size: 1.8rem;
    text-transform: uppercase;
    box-shadow: 0 6px 12px rgba(0,0,0,0.15);
    transition: all 0.3s ease;
    flex-shrink: 0;
}

.user-avatar-xl:hover {
    transform: scale(1.05);
    box-shadow: 0 8px 16px rgba(0,0,0,0.2);
}

/* Avatar avec bordure */
.user-avatar-bordered {
    border: 2px solid white;
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
}

.user-avatar-bordered:hover {
    border-color: var(--primary-color);
}

/* Avatar avec statut en ligne */
.user-avatar-with-status {
    position: relative;
}

.user-avatar-status {
    position: absolute;
    bottom: 2px;
    right: 2px;
    width: 10px;
    height: 10px;
    border-radius: 50%;
    border: 2px solid white;
}

.user-avatar-status.online {
    background-color: #2ecc71;
}

.user-avatar-status.offline {
    background-color: #95a5a6;
}

.user-avatar-status.busy {
    background-color: #e74c3c;
}

.user-avatar-status.away {
    background-color: #f39c12;
}

/* ===== COMPOSANTS D'INFORMATION UTILISATEUR ===== */

/* Container d'information utilisateur (avatar + détails) */
.user-info {
    display: flex;
    align-items: center;
    gap: 12px;
}

/* Version compacte pour les tableaux */
.user-info-compact {
    display: flex;
    align-items: center;
    gap: 8px;
}

/* Détails de l'utilisateur */
.user-details {
    display: flex;
    flex-direction: column;
    line-height: 1.3;
}

.user-name {
    font-weight: 600;
    font-size: 0.95rem;
    color: #2c3e50;
    margin-bottom: 2px;
}

.user-email {
    font-size: 0.8rem;
    color: #7f8c8d;
}

.user-role {
    font-size: 0.75rem;
    color: #95a5a6;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Version pour les cartes */
.user-card {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 12px;
    border: 1px solid #eaeaea;
    transition: all 0.3s ease;
}

.user-card:hover {
    background: white;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    transform: translateY(-2px);
}

.user-card .user-info {
    flex: 1;
}

.user-card .user-meta {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.user-card .user-meta-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.9rem;
    color: #666;
}

.user-card .user-meta-item i {
    width: 16px;
    color: var(--primary-color);
}

/* ===== COMPOSANTS POUR LES TABLEAUX ===== */

/* Container pour les informations dans les tableaux */
.table-user-info {
    display: flex;
    align-items: center;
    gap: 10px;
}

.table-user-details {
    display: flex;
    flex-direction: column;
}

.table-user-name {
    font-weight: 500;
    font-size: 0.9rem;
    color: #2c3e50;
}

.table-user-email {
    font-size: 0.8rem;
    color: #7f8c8d;
}

/* ===== VARIANTES DE COULEURS POUR AVATARS ===== */

/* Couleurs prédéfinies pour les avatars */
.avatar-blue {
    background: linear-gradient(135deg, #3498db, #2980b9);
}

.avatar-green {
    background: linear-gradient(135deg, #2ecc71, #27ae60);
}

.avatar-yellow {
    background: linear-gradient(135deg, #f1c40f, #f39c12);
}

.avatar-orange {
    background: linear-gradient(135deg, #e67e22, #d35400);
}

.avatar-red {
    background: linear-gradient(135deg, #e74c3c, #c0392b);
}

.avatar-purple {
    background: linear-gradient(135deg, #9b59b6, #8e44ad);
}

.avatar-pink {
    background: linear-gradient(135deg, #e84393, #c2185b);
}

.avatar-teal {
    background: linear-gradient(135deg, #1abc9c, #16a085);
}

.avatar-gray {
    background: linear-gradient(135deg, #95a5a6, #7f8c8d);
}

.avatar-dark {
    background: linear-gradient(135deg, #34495e, #2c3e50);
}

/* ===== ANIMATIONS ===== */

@keyframes avatarPulse {
    0% {
        box-shadow: 0 0 0 0 rgba(52, 152, 219, 0.7);
    }
    70% {
        box-shadow: 0 0 0 10px rgba(52, 152, 219, 0);
    }
    100% {
        box-shadow: 0 0 0 0 rgba(52, 152, 219, 0);
    }
}

.avatar-pulse {
    animation: avatarPulse 2s infinite;
}

@keyframes avatarGlow {
    0% {
        filter: brightness(1);
    }
    50% {
        filter: brightness(1.1);
    }
    100% {
        filter: brightness(1);
    }
}

.avatar-glow {
    animation: avatarGlow 2s infinite;
}

/* ===== EXEMPLES D'UTILISATION DANS LE TABLEAU ===== */

/* Style pour la cellule du tableau contenant l'utilisateur */
td .user-info {
    min-width: 150px;
}

/* Style pour quand il n'y a pas d'utilisateur assigné */
.no-user-assigned {
    color: #95a5a6;
    font-style: italic;
    font-size: 0.9rem;
    padding: 8px 0;
}

/* ===== VERSION RESPONSIVE ===== */

@media (max-width: 768px) {
    .user-info {
        flex-direction: column;
        align-items: flex-start;
        gap: 8px;
    }
    
    .user-details {
        width: 100%;
    }
    
    .user-card {
        flex-direction: column;
        text-align: center;
    }
    
    .user-card .user-meta-item {
        justify-content: center;
    }
    
    td .user-info {
        min-width: auto;
    }
    
    .table-user-info {
        flex-direction: column;
        align-items: flex-start;
    }
}

.modern-table td {
    padding: 16px 12px;
    vertical-align: middle;
}

.modern-table .user-info {
    margin: -8px 0; /* Ajustement pour l'alignement */
}

/* Ligne de survol */
.modern-table tbody tr:hover {
    background-color: rgba(52, 152, 219, 0.05);
}

.modern-table tbody tr:hover .user-avatar-sm {
    transform: scale(1.1);
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}

/* ===== BADGES DE STATUT POUR LES UTILISATEURS ===== */

.user-badge {
    display: inline-flex;
    align-items: center;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.user-badge-admin {
    background: linear-gradient(135deg, #3498db, #2980b9);
    color: white;
}

.user-badge-manager {
    background: linear-gradient(135deg, #e67e22, #d35400);
    color: white;
}

.user-badge-user {
    background: linear-gradient(135deg, #95a5a6, #7f8c8d);
    color: white;
}

/* ===== TOOLTIP PERSONNALISÉ POUR LES AVATARS ===== */

.user-avatar-sm[data-tooltip] {
    position: relative;
    cursor: help;
}

.user-avatar-sm[data-tooltip]:before {
    content: attr(data-tooltip);
    position: absolute;
    bottom: 100%;
    left: 50%;
    transform: translateX(-50%);
    padding: 5px 10px;
    background: rgba(0, 0, 0, 0.8);
    color: white;
    font-size: 0.75rem;
    white-space: nowrap;
    border-radius: 4px;
    pointer-events: none;
    opacity: 0;
    transition: opacity 0.3s ease;
    z-index: 1000;
}

.user-avatar-sm[data-tooltip]:hover:before {
    opacity: 1;
}

/* ===== GROUPE D'AVATARS (POUR LES ASSIGNATIONS MULTIPLES) ===== */

.avatar-group {
    display: flex;
    align-items: center;
}

.avatar-group .user-avatar-sm {
    margin-right: -8px;
    border: 2px solid white;
    transition: transform 0.2s ease;
}

.avatar-group .user-avatar-sm:hover {
    transform: translateY(-3px);
    z-index: 10;
}

.avatar-group-count {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    background: #e9ecef;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.8rem;
    font-weight: 600;
    color: #495057;
    border: 2px solid white;
    margin-left: 4px;
}
    </style>
@endsection
