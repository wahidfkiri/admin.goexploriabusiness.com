{{-- resources/views/tasks/index.blade.php --}}
@extends('layouts.app')

@section('content')
    
    <!-- MAIN CONTENT -->
    <main class="dashboard-content">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">
                <span class="page-title-icon"><i class="fas fa-tasks"></i></span>
                Gestion des Tâches par Projet
            </h1>
            
            <div class="page-actions">
                <button class="btn btn-outline-secondary" id="toggleFilterBtn">
                    <i class="fas fa-sliders-h me-2"></i>Filtres
                </button>
                <!-- <a href="{{ route('tasks.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus-circle me-2"></i>Nouvelle Tâche
                </a> -->
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
                    <label for="filterProject" class="form-label-modern">Projet</label>
                    <select class="form-select-modern" id="filterProject">
                        <option value="">Tous les projets</option>
                        @foreach($projects as $project)
                            <option value="{{ $project->id }}">{{ $project->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
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
        
        <!-- Stats Cards - Modern Design -->
        <div class="stats-grid">
            <div class="stats-card-modern">
                <div class="stats-header-modern">
                    <div>
                        <div class="stats-value-modern" id="totalTasks">0</div>
                        <div class="stats-label-modern">Total Tâches</div>
                    </div>
                    <div class="stats-icon-modern" style="background: linear-gradient(135deg, #9b59b6, #8e44ad);">
                        <i class="fas fa-tasks"></i>
                    </div>
                </div>
            </div>
            
            <div class="stats-card-modern">
                <div class="stats-header-modern">
                    <div>
                        <div class="stats-value-modern" id="inProgressTasks">0</div>
                        <div class="stats-label-modern">En Cours</div>
                    </div>
                    <div class="stats-icon-modern" style="background: linear-gradient(135deg, #3498db, #2980b9);">
                        <i class="fas fa-spinner"></i>
                    </div>
                </div>
            </div>
            
            <div class="stats-card-modern">
                <div class="stats-header-modern">
                    <div>
                        <div class="stats-value-modern" id="pendingTasks">0</div>
                        <div class="stats-label-modern">En Attente</div>
                    </div>
                    <div class="stats-icon-modern" style="background: linear-gradient(135deg, #f39c12, #e67e22);">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>
            </div>
            
            <div class="stats-card-modern">
                <div class="stats-header-modern">
                    <div>
                        <div class="stats-value-modern" id="completedTasks">0</div>
                        <div class="stats-label-modern">Terminées</div>
                    </div>
                    <div class="stats-icon-modern" style="background: linear-gradient(135deg, #2ecc71, #27ae60);">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
            </div>
            
            <div class="stats-card-modern">
                <div class="stats-header-modern">
                    <div>
                        <div class="stats-value-modern" id="overdueTasks">0</div>
                        <div class="stats-label-modern">En Retard</div>
                    </div>
                    <div class="stats-icon-modern" style="background: linear-gradient(135deg, #e74c3c, #c0392b);">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Main Card - Modern Design -->
        <div class="main-card-modern">
            <div class="card-header-modern">
                <h3 class="card-title-modern">Liste des Tâches</h3>
                <div class="search-container">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" class="search-input" placeholder="Rechercher une tâche..." id="searchInput">
                </div>
            </div>
            
            <div class="card-body-modern">
                <!-- Loading Spinner -->
                <div class="spinner-container" id="loadingSpinner">
                    <div class="spinner-border text-primary spinner" role="status">
                        <span class="visually-hidden">Chargement...</span>
                    </div>
                </div>
                
                <!-- Group by Project View -->
                <div class="project-tasks-container" id="projectTasksContainer" style="display: none;">
                    <!-- Projects will be loaded here with their tasks -->
                </div>
                
                <!-- Empty State -->
                <div class="empty-state-modern" id="emptyState" style="display: none;">
                    <div class="empty-icon-modern">
                        <i class="fas fa-tasks"></i>
                    </div>
                    <h3 class="empty-title-modern">Aucune tâche trouvée</h3>
                    <p class="empty-text-modern">Commencez par créer votre première tâche.</p>
                    <a href="{{ route('tasks.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus-circle me-2"></i>Créer une tâche
                    </a>
                </div>
            </div>
            
            <!-- Pagination -->
            <div class="pagination-container-modern" id="paginationContainer" style="display: none;">
                <div class="pagination-info-modern" id="paginationInfo">
                    <!-- Pagination info will be loaded here -->
                </div>
                
                <nav aria-label="Page navigation">
                    <ul class="modern-pagination" id="pagination">
                        <!-- Pagination will be loaded here -->
                    </ul>
                </nav>
            </div>
        </div>
        
        <!-- Floating Action Button -->
        <a href="{{ route('tasks.create') }}" class="fab-modern">
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
        let currentPage = 1;
        let currentFilters = {};
        let allTasks = [];
        let taskToDelete = null;
        let tasksByProject = {};

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            setupAjax();
            loadTasks();
            loadStatistics();
            setupEventListeners();
        });

        // AJAX setup
        const setupAjax = () => {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
        };

        // Load tasks
        const loadTasks = (page = 1, filters = {}) => {
            showLoading();
            
            const searchTerm = document.getElementById('searchInput')?.value || '';
            
            $.ajax({
                url: '{{ route("tasks.index") }}',
                type: 'GET',
                data: {
                    page: page,
                    search: searchTerm,
                    ...filters,
                    ajax: true,
                    group_by_project: true
                },
                success: function(response) {
                    if (response.success) {
                        allTasks = response.tasks || [];
                        tasksByProject = response.grouped_by_project || {};
                        renderTasksByProject(tasksByProject);
                        renderPagination(response);
                        hideLoading();
                    } else {
                        showError('Erreur lors du chargement des tâches');
                    }
                },
                error: function(xhr) {
                    hideLoading();
                    showError('Erreur de connexion au serveur');
                    console.error('Error:', xhr.responseText);
                }
            });
        };

        // Load statistics
        const loadStatistics = () => {
            $.ajax({
                url: '{{ route("tasks.statistics") }}',
                type: 'GET',
                success: function(response) {
                    if (response.success) {
                        const stats = response.data;
                        document.getElementById('totalTasks').textContent = stats.total || 0;
                        document.getElementById('inProgressTasks').textContent = stats.in_progress || 0;
                        document.getElementById('pendingTasks').textContent = stats.pending || 0;
                        document.getElementById('completedTasks').textContent = stats.completed || 0;
                        document.getElementById('overdueTasks').textContent = stats.overdue || 0;
                    } else {
                        console.error('Error loading statistics:', response.message);
                        
                        // Default values
                        document.getElementById('totalTasks').textContent = '0';
                        document.getElementById('inProgressTasks').textContent = '0';
                        document.getElementById('pendingTasks').textContent = '0';
                        document.getElementById('completedTasks').textContent = '0';
                        document.getElementById('overdueTasks').textContent = '0';
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Statistics AJAX error:', xhr.responseText, status, error);
                    
                    // Default values
                    document.getElementById('totalTasks').textContent = '0';
                    document.getElementById('inProgressTasks').textContent = '0';
                    document.getElementById('pendingTasks').textContent = '0';
                    document.getElementById('completedTasks').textContent = '0';
                    document.getElementById('overdueTasks').textContent = '0';
                }
            });
        };

        // Render tasks by project
        const renderTasksByProject = (tasksByProject) => {
            const container = document.getElementById('projectTasksContainer');
            container.innerHTML = '';
            
            const projectEntries = Object.entries(tasksByProject);
            
            if (!projectEntries || projectEntries.length === 0) {
                document.getElementById('emptyState').style.display = 'block';
                document.getElementById('projectTasksContainer').style.display = 'none';
                document.getElementById('paginationContainer').style.display = 'none';
                return;
            }
            
            projectEntries.forEach(([projectName, projectData], projectIndex) => {
                const projectCard = createProjectCard(projectName, projectData, projectIndex);
                container.appendChild(projectCard);
            });
            
            document.getElementById('emptyState').style.display = 'none';
            document.getElementById('projectTasksContainer').style.display = 'block';
            document.getElementById('paginationContainer').style.display = 'flex';
        };

        // Create project card with its tasks
        const createProjectCard = (projectName, projectData, projectIndex) => {
            const projectCard = document.createElement('div');
            projectCard.className = 'project-card-modern';
            projectCard.style.animationDelay = `${projectIndex * 0.1}s`;
            
            const project = projectData.project || {};
            const tasks = projectData.tasks || [];
            
            // Calculate project progress
            const totalTasks = tasks.length;
            const completedTasks = tasks.filter(t => t.status === 'approved' || t.status === 'delivered').length;
            const progress = totalTasks > 0 ? Math.round((completedTasks / totalTasks) * 100) : 0;
            
            // Project header
            const projectHeader = document.createElement('div');
            projectHeader.className = 'project-header-modern';
            projectHeader.innerHTML = `
                <div class="project-header-left">
                    <div class="project-icon" style="background: ${getProjectColor(projectName)}">
                        <i class="fas fa-project-diagram"></i>
                    </div>
                    <div class="project-info">
                        <h4 class="project-name">
                            <a href="/projects/${project.id}">${projectName}</a>
                            ${project.contract_number ? `<small class="text-muted ms-2">(Contrat: ${project.contract_number})</small>` : ''}
                        </h4>
                        <div class="project-meta">
                            <span class="project-meta-item">
                                <i class="fas fa-building me-1"></i>${project.client_name || 'Client non spécifié'}
                            </span>
                            <span class="project-meta-item">
                                <i class="fas fa-calendar me-1"></i>Échéance: ${project.end_date || 'Non définie'}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="project-header-right">
                    <div class="project-stats">
                        <div class="stat-badge">
                            <span class="stat-value">${totalTasks}</span>
                            <span class="stat-label">Tâches</span>
                        </div>
                        <div class="stat-badge">
                            <span class="stat-value">${completedTasks}</span>
                            <span class="stat-label">Terminées</span>
                        </div>
                    </div>
                    <div class="project-progress">
                        <div class="progress-percentage">${progress}%</div>
                        <div class="progress-modern">
                            <div class="progress-bar-modern" style="width: ${progress}%; background: ${getProgressColor(progress)}"></div>
                        </div>
                    </div>
                    <button class="btn btn-sm btn-outline-primary toggle-tasks-btn" data-project="${projectName.replace(/\s+/g, '-')}">
                        <i class="fas fa-chevron-down"></i>
                    </button>
                </div>
            `;
            
            // Tasks table
            const tasksTable = document.createElement('div');
            tasksTable.className = 'project-tasks-table';
            tasksTable.id = `tasks-${projectName.replace(/\s+/g, '-')}`;
            tasksTable.innerHTML = createTasksTable(tasks);
            
            projectCard.appendChild(projectHeader);
            projectCard.appendChild(tasksTable);
            
            return projectCard;
        };

        // Create tasks table HTML
        const createTasksTable = (tasks) => {
            let tableHtml = `
                <table class="modern-table">
                    <thead>
                        <tr>
                            <th>Tâche</th>
                            <th>Assigné à</th>
                            <th>Échéance</th>
                            <th>Statut</th>
                            <th>Avancement</th>
                            <th style="text-align: center;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
            `;
            
            tasks.forEach((task, index) => {
                const progress = task.progress || 0;
                const isOverdue = task.is_overdue || false;
                
                tableHtml += `
                    <tr id="task-row-${task.id}" style="animation-delay: ${index * 0.05}s">
                        <td>
                            <div class="task-name-cell">
                                <div class="task-name-modern">
                                    <div class="task-icon-modern" style="background: ${getTaskColor(task.status)}">
                                        <i class="fas fa-tasks"></i>
                                    </div>
                                    <div>
                                        <div class="task-name-text">
                                            <a href="#" onclick="showTaskDetails(${task.id}); return false;">${escapeHtml(task.name)}</a>
                                        </div>
                                        <small class="text-muted">
                                            <i class="fas fa-hashtag me-1"></i>${task.contract_number || 'N° contrat non défini'}
                                        </small>
                                        ${task.contact_name ? `<small class="text-muted ms-2"><i class="fas fa-user me-1"></i>${task.contact_name}</small>` : ''}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>
                            ${task.user ? `
                                <div class="user-info">
                                    <div class="user-avatar-sm" style="background: ${getUserColor(task.user.name)}">
                                        ${getInitials(task.user.name)}
                                    </div>
                                    <div class="user-details">
                                        <div class="user-name">${task.user.name}</div>
                                        <small class="text-muted">${task.user.email}</small>
                                    </div>
                                </div>
                            ` : 'Non assigné'}
                        </td>
                        <td>
                            <div class="deadline-info">
                                <div class="deadline-date ${isOverdue ? 'deadline-overdue' : ''}">
                                    <i class="fas fa-calendar-day me-1"></i>${task.due_date || 'Non définie'}
                                </div>
                                ${isOverdue ? '<small class="text-danger">En retard</small>' : ''}
                            </div>
                        </td>
                        <td>
                            ${getStatusBadge(task.status_formatted, task.status_color)}
                        </td>
                        <td>
                            <div class="progress-container">
                                <div class="progress-percentage">${progress}%</div>
                                <div class="progress-modern">
                                    <div class="progress-bar-modern" style="width: ${progress}%; background: ${getProgressColor(progress)}"></div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="task-actions-modern">
                                <button class="action-btn-modern view-btn-modern" title="Voir détails" onclick="showTaskDetails(${task.id})">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <a href="/tasks/${task.id}/edit" class="action-btn-modern edit-btn-modern" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button class="action-btn-modern delete-btn-modern" title="Supprimer" onclick="showDeleteConfirmation(${task.id})">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
            });
            
            tableHtml += `
                    </tbody>
                </table>
            `;
            
            return tableHtml;
        };

        // Show task details in modal
        const showTaskDetails = (taskId) => {
            const task = findTaskById(taskId);
            
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
                            ${getStatusBadge(task.status_formatted, task.status_color)}
                            ${isOverdue ? '<span class="badge bg-danger ms-2">En retard</span>' : ''}
                        </div>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="detail-section">
                                <h5 class="detail-section-title">Informations générales</h5>
                                <div class="detail-item">
                                    <span class="detail-label">Projet:</span>
                                    <span class="detail-value">${task.project?.name || 'Non spécifié'}</span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Établissement:</span>
                                    <span class="detail-value">${task.etablissement?.name || 'Non spécifié'}</span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">N° Contrat:</span>
                                    <span class="detail-value">${task.contract_number || 'Non défini'}</span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Contact:</span>
                                    <span class="detail-value">${task.contact_name || 'Non spécifié'}</span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Localisation:</span>
                                    <span class="detail-value">${task.location || 'Non spécifiée'}</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="detail-section">
                                <h5 class="detail-section-title">Dates et échéances</h5>
                                <div class="detail-item">
                                    <span class="detail-label">Date d'échéance:</span>
                                    <span class="detail-value ${isOverdue ? 'text-danger fw-bold' : ''}">${task.due_date || 'Non définie'}</span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Date de livraison:</span>
                                    <span class="detail-value">${task.delivery_date || 'Non définie'}</span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Date de test:</span>
                                    <span class="detail-value">${task.test_date || 'Non définie'}</span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Date d'intégration:</span>
                                    <span class="detail-value">${task.integration_date || 'Non définie'}</span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Date push prod:</span>
                                    <span class="detail-value">${task.push_prod_date || 'Non définie'}</span>
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
                                    <span class="detail-value">${task.user?.name || 'Non assigné'}</span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Manager général:</span>
                                    <span class="detail-value">${task.general_manager?.name || 'Non assigné'}</span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Client manager:</span>
                                    <span class="detail-value">${task.client_manager?.name || 'Non assigné'}</span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Créé par:</span>
                                    <span class="detail-value">${task.creator?.name || 'Inconnu'}</span>
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
                                            `<span class="badge bg-success">Oui (par ${task.approved_by?.name || 'inconnu'} le ${task.approved_at})</span>` : 
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
            document.getElementById('editTaskFromModal').href = `/tasks/${task.id}/edit`;
            
            // Show modal
            const detailsModal = new bootstrap.Modal(document.getElementById('taskDetailsModal'));
            detailsModal.show();
        };

        // Find task by ID in all tasks
        const findTaskById = (taskId) => {
            for (const projectName in tasksByProject) {
                const task = tasksByProject[projectName].tasks.find(t => t.id === taskId);
                if (task) return task;
            }
            return null;
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

        const getProjectColor = (projectName) => {
            let hash = 0;
            for (let i = 0; i < projectName.length; i++) {
                hash = projectName.charCodeAt(i) + ((hash << 5) - hash);
            }
            
            const colors = [
                '#45b7d1', '#96ceb4', '#feca57', '#ff6b6b',
                '#9b59b6', '#3498db', '#1abc9c', '#e74c3c',
                '#34495e', '#f1c40f', '#2ecc71', '#e67e22'
            ];
            
            return colors[Math.abs(hash) % colors.length];
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

        const formatCurrency = (amount) => {
            if (!amount) return '0 €';
            return new Intl.NumberFormat('fr-FR', { style: 'currency', currency: 'EUR' }).format(amount);
        };

        // Show delete confirmation modal
        const showDeleteConfirmation = (taskId) => {
            const task = findTaskById(taskId);
            
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
                            <div><strong>Projet:</strong> ${task.project?.name || 'N/A'}</div>
                            <div><strong>Assigné à:</strong> ${task.user?.name || 'N/A'}</div>
                            <div><strong>Statut:</strong> ${task.status_formatted}</div>
                            <div><strong>Échéance:</strong> ${task.due_date || 'N/A'}</div>
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
                url: `/tasks/${taskId}`,
                type: 'DELETE',
                dataType: 'json',
                success: function(response) {
                    // Hide modal
                    const deleteModal = bootstrap.Modal.getInstance(document.getElementById('deleteConfirmationModal'));
                    deleteModal.hide();
                    
                    if (response.success) {
                        // Update statistics
                        loadStatistics();
                        
                        // Show success message
                        showAlert('success', response.message || 'Tâche supprimée avec succès !');
                        
                        // Reload tasks
                        setTimeout(() => {
                            loadTasks(currentPage, currentFilters);
                        }, 500);
                    } else {
                        if (row) row.classList.remove('deleting-row');
                        showAlert('danger', response.message || 'Erreur lors de la suppression');
                    }
                },
                error: function(xhr, status, error) {
                    // Hide modal
                    const deleteModal = bootstrap.Modal.getInstance(document.getElementById('deleteConfirmationModal'));
                    deleteModal.hide();
                    
                    // Remove deleting animation
                    const row = document.getElementById(`task-row-${taskId}`);
                    if (row) {
                        row.classList.remove('deleting-row');
                    }
                    
                    if (xhr.status === 404) {
                        showAlert('danger', 'Tâche non trouvée.');
                        loadTasks(currentPage, currentFilters);
                    } else {
                        showAlert('danger', 'Erreur lors de la suppression: ' + error);
                    }
                },
                complete: function() {
                    taskToDelete = null;
                }
            });
        };

        // Render pagination
        const renderPagination = (response) => {
            const pagination = document.getElementById('pagination');
            const paginationInfo = document.getElementById('paginationInfo');
            
            if (!response || !response.data) return;
            
            // Update pagination info
            const start = (response.current_page - 1) * response.per_page + 1;
            const end = Math.min(response.current_page * response.per_page, response.total);
            paginationInfo.textContent = `Affichage de ${start} à ${end} sur ${response.total} tâches`;
            
            // Render pagination links
            let paginationHtml = '';
            
            // Previous button
            if (response.prev_page_url) {
                paginationHtml += `
                    <li class="page-item">
                        <a class="page-link-modern" href="#" onclick="changePage(${response.current_page - 1})">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                    </li>
                `;
            } else {
                paginationHtml += `
                    <li class="page-item disabled">
                        <span class="page-link-modern"><i class="fas fa-chevron-left"></i></span>
                    </li>
                `;
            }
            
            // Page numbers
            const maxPages = 5;
            let startPage = Math.max(1, response.current_page - Math.floor(maxPages / 2));
            let endPage = Math.min(response.last_page, startPage + maxPages - 1);
            
            if (endPage - startPage + 1 < maxPages) {
                startPage = Math.max(1, endPage - maxPages + 1);
            }
            
            for (let i = startPage; i <= endPage; i++) {
                if (i === response.current_page) {
                    paginationHtml += `
                        <li class="page-item active">
                            <span class="page-link-modern">${i}</span>
                        </li>
                    `;
                } else {
                    paginationHtml += `
                        <li class="page-item">
                            <a class="page-link-modern" href="#" onclick="changePage(${i})">${i}</a>
                        </li>
                    `;
                }
            }
            
            // Next button
            if (response.next_page_url) {
                paginationHtml += `
                    <li class="page-item">
                        <a class="page-link-modern" href="#" onclick="changePage(${response.current_page + 1})">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    </li>
                `;
            } else {
                paginationHtml += `
                    <li class="page-item disabled">
                        <span class="page-link-modern"><i class="fas fa-chevron-right"></i></span>
                    </li>
                `;
            }
            
            pagination.innerHTML = paginationHtml;
        };

        // Change page
        const changePage = (page) => {
            currentPage = page;
            loadTasks(page, currentFilters);
        };

        // Show loading state
        const showLoading = () => {
            document.getElementById('loadingSpinner').style.display = 'flex';
            document.getElementById('projectTasksContainer').style.display = 'none';
            document.getElementById('emptyState').style.display = 'none';
            document.getElementById('paginationContainer').style.display = 'none';
        };

        // Hide loading state
        const hideLoading = () => {
            document.getElementById('loadingSpinner').style.display = 'none';
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

        // Show error
        const showError = (message) => {
            showAlert('danger', message);
        };

        // Setup event listeners
        const setupEventListeners = () => {
            // Search input with debounce
            const searchInput = document.getElementById('searchInput');
            let searchTimeout;
            
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(() => {
                        loadTasks(1, currentFilters);
                    }, 500);
                });
            }
            
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
            
            // Apply filters
            const applyFiltersBtn = document.getElementById('applyFiltersBtn');
            if (applyFiltersBtn) {
                applyFiltersBtn.addEventListener('click', () => {
                    currentFilters = {
                        project_id: document.getElementById('filterProject').value,
                        user_id: document.getElementById('filterUser').value,
                        status: document.getElementById('filterStatus').value,
                        etablissement_id: document.getElementById('filterEtablissement').value,
                        date_range: document.getElementById('filterDateRange').value,
                    };
                    loadTasks(1, currentFilters);
                });
            }
            
            // Clear filters
            const clearFiltersBtn = document.getElementById('clearFiltersBtn');
            if (clearFiltersBtn) {
                clearFiltersBtn.addEventListener('click', () => {
                    document.getElementById('filterProject').value = '';
                    document.getElementById('filterUser').value = '';
                    document.getElementById('filterStatus').value = '';
                    document.getElementById('filterEtablissement').value = '';
                    document.getElementById('filterDateRange').value = '';
                    currentFilters = {};
                    loadTasks(1);
                });
            }
            
            // Toggle project tasks
            document.addEventListener('click', function(e) {
                if (e.target.closest('.toggle-tasks-btn')) {
                    const btn = e.target.closest('.toggle-tasks-btn');
                    const projectId = btn.dataset.project;
                    const tasksTable = document.getElementById(`tasks-${projectId}`);
                    
                    if (tasksTable) {
                        if (tasksTable.style.display === 'none' || tasksTable.style.display === '') {
                            tasksTable.style.display = 'block';
                            btn.querySelector('i').className = 'fas fa-chevron-up';
                        } else {
                            tasksTable.style.display = 'none';
                            btn.querySelector('i').className = 'fas fa-chevron-down';
                        }
                    }
                }
            });
            
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
        /* Styles spécifiques pour la page tâches */
        .project-card-modern {
            background: white;
            border-radius: 12px;
            margin-bottom: 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            border: 1px solid #eaeaea;
            animation: slideInUp 0.5s ease forwards;
            opacity: 0;
        }

        .project-header-modern {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 20px;
            background: linear-gradient(to right, #f8f9fa, #ffffff);
            border-bottom: 2px solid #eaeaea;
            border-radius: 12px 12px 0 0;
            cursor: pointer;
        }

        .project-header-left {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .project-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .project-info h4 {
            margin: 0;
            font-size: 1.2rem;
            font-weight: 600;
        }

        .project-info h4 a {
            color: #333;
            text-decoration: none;
        }

        .project-info h4 a:hover {
            color: var(--primary-color);
        }

        .project-meta {
            display: flex;
            gap: 15px;
            margin-top: 5px;
            font-size: 0.9rem;
            color: #666;
        }

        .project-meta-item {
            display: inline-flex;
            align-items: center;
        }

        .project-header-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .project-stats {
            display: flex;
            gap: 15px;
        }

        .stat-badge {
            text-align: center;
            min-width: 60px;
        }

        .stat-value {
            display: block;
            font-weight: 700;
            font-size: 1.2rem;
            color: #333;
            line-height: 1.2;
        }

        .stat-label {
            font-size: 0.8rem;
            color: #666;
        }

        .project-progress {
            min-width: 150px;
        }

        .project-progress .progress-percentage {
            font-size: 0.9rem;
            margin-bottom: 5px;
            text-align: right;
        }

        .project-tasks-table {
            padding: 20px;
            display: none;
        }

        .project-tasks-table.show {
            display: block;
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

        .deadline-overdue {
            color: #e74c3c;
            font-weight: 600;
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

        @keyframes slideInUp {
            from {
                transform: translateY(20px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        /* Custom badge colors */
        .bg-purple {
            background-color: #9b59b6 !important;
            color: white;
        }

        .bg-indigo {
            background-color: #6610f2 !important;
            color: white;
        }

        /* Responsive adjustments */
        @media (max-width: 992px) {
            .project-header-modern {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }

            .project-header-right {
                width: 100%;
                justify-content: space-between;
            }

            .project-stats {
                flex: 1;
            }
        }

        @media (max-width: 768px) {
            .project-header-right {
                flex-direction: column;
                align-items: flex-start;
            }

            .project-progress {
                width: 100%;
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