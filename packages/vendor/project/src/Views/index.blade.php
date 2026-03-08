@extends('layouts.app')

@section('content')
    
    <!-- MAIN CONTENT -->
    <main class="dashboard-content">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">
                <span class="page-title-icon"><i class="fas fa-project-diagram"></i></span>
                Gestion des Projets
            </h1>
            
            <div class="page-actions">
                <button class="btn btn-outline-secondary" id="toggleFilterBtn">
                    <i class="fas fa-sliders-h me-2"></i>Filtres
                </button>
                <a href="{{ route('projects.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus-circle me-2"></i>Nouveau Projet
                </a>
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
                    <label for="filterClient" class="form-label-modern">Client</label>
                    <select class="form-select-modern" id="filterClient">
                        <option value="">Tous les clients</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}">{{ $client->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="filterUser" class="form-label-modern">Responsable</label>
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
                    <label for="filterActive" class="form-label-modern">État</label>
                    <select class="form-select-modern" id="filterActive">
                        <option value="">Tous</option>
                        <option value="1">Actif</option>
                        <option value="0">Inactif</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="filterSortBy" class="form-label-modern">Trier par</label>
                    <select class="form-select-modern" id="filterSortBy">
                        <option value="created_at">Date de création</option>
                        <option value="name">Nom</option>
                        <option value="status">Statut</option>
                        <option value="updated_at">Dernière modification</option>
                    </select>
                </div>
            </div>
        </div>
        
        <!-- Stats Cards - Modern Design -->
        <div class="stats-grid">
            <div class="stats-card-modern">
                <div class="stats-header-modern">
                    <div>
                        <div class="stats-value-modern" id="totalProjects">0</div>
                        <div class="stats-label-modern">Total Projets</div>
                    </div>
                    <div class="stats-icon-modern" style="background: linear-gradient(135deg, var(--primary-color), #3a56e4);">
                        <i class="fas fa-project-diagram"></i>
                    </div>
                </div>
            </div>
            
            <div class="stats-card-modern">
                <div class="stats-header-modern">
                    <div>
                        <div class="stats-value-modern" id="activeProjects">0</div>
                        <div class="stats-label-modern">Projets Actifs</div>
                    </div>
                    <div class="stats-icon-modern" style="background: linear-gradient(135deg, #06b48a, #049a72);">
                        <i class="fas fa-play-circle"></i>
                    </div>
                </div>
            </div>
            
            <div class="stats-card-modern">
                <div class="stats-header-modern">
                    <div>
                        <div class="stats-value-modern" id="inProgressProjects">0</div>
                        <div class="stats-label-modern">En Cours</div>
                    </div>
                    <div class="stats-icon-modern" style="background: linear-gradient(135deg, #ffd166, #ffb347);">
                        <i class="fas fa-spinner"></i>
                    </div>
                </div>
            </div>
            
            <div class="stats-card-modern">
                <div class="stats-header-modern">
                    <div>
                        <div class="stats-value-modern" id="completedProjects">0</div>
                        <div class="stats-label-modern">Terminés</div>
                    </div>
                    <div class="stats-icon-modern" style="background: linear-gradient(135deg, #ef476f, #d4335f);">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
            </div>
            
            <div class="stats-card-modern">
                <div class="stats-header-modern">
                    <div>
                        <div class="stats-value-modern" id="totalTasks">0</div>
                        <div class="stats-label-modern">Tâches Totales</div>
                    </div>
                    <div class="stats-icon-modern" style="background: linear-gradient(135deg, #9b59b6, #8e44ad);">
                        <i class="fas fa-tasks"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Main Card - Modern Design -->
        <div class="main-card-modern">
            <div class="card-header-modern">
                <h3 class="card-title-modern">Liste des Projets</h3>
                <div class="search-container">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" class="search-input" placeholder="Rechercher un projet..." id="searchInput">
                </div>
            </div>
            
            <div class="card-body-modern">
                <!-- Loading Spinner -->
                <div class="spinner-container" id="loadingSpinner">
                    <div class="spinner-border text-primary spinner" role="status">
                        <span class="visually-hidden">Chargement...</span>
                    </div>
                </div>
                
                <!-- Table Container -->
                <div class="table-container-modern" id="tableContainer" style="display: none;">
                    <table class="modern-table">
                        <thead>
                            <tr>
                                <th>Projet</th>
                                <th>Client</th>
                                <th>Responsable</th>
                                <th>Échéance</th>
                                <th>Avancement</th>
                                <th>Statut</th>
                                <th style="text-align: center;">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="projectsTableBody">
                            <!-- Projects will be loaded here via AJAX -->
                        </tbody>
                    </table>
                </div>
                
                <!-- Empty State -->
                <div class="empty-state-modern" id="emptyState" style="display: none;">
                    <div class="empty-icon-modern">
                        <i class="fas fa-project-diagram"></i>
                    </div>
                    <h3 class="empty-title-modern">Aucun projet trouvé</h3>
                    <p class="empty-text-modern">Commencez par créer votre premier projet.</p>
                    <a href="{{ route('projects.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus-circle me-2"></i>Créer un projet
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
        <a href="{{ route('projects.create') }}" class="fab-modern">
            <i class="fas fa-plus"></i>
        </a>
    </main>
    
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
                    <p class="delete-message">Êtes-vous sûr de vouloir supprimer ce projet ? Toutes les tâches associées seront également supprimées.</p>
                    
                    <div class="project-to-delete" id="projectToDeleteInfo">
                        <!-- Project info will be loaded here -->
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
        let allProjects = [];
        let projectToDelete = null;

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            setupAjax();
            loadProjects();
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

        // Load projects
        const loadProjects = (page = 1, filters = {}) => {
            showLoading();
            
            const searchTerm = document.getElementById('searchInput')?.value || '';
            
            $.ajax({
                url: '{{ route("projects.index") }}',
                type: 'GET',
                data: {
                    page: page,
                    search: searchTerm,
                    ...filters,
                    ajax: true
                },
                success: function(response) {
                    if (response.success) {
                        allProjects = response.data || [];
                        renderProjects(allProjects);
                        renderPagination(response);
                        hideLoading();
                    } else {
                        showError('Erreur lors du chargement des projets');
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
                url: '{{ route("projects.statistics") }}',
                type: 'GET',
                success: function(response) {
                    if (response.success) {
                        const stats = response.data;
                        document.getElementById('totalProjects').textContent = stats.total || 0;
                        document.getElementById('activeProjects').textContent = stats.active || 0;
                        document.getElementById('inProgressProjects').textContent = stats.in_progress || 0;
                        document.getElementById('completedProjects').textContent = stats.completed || 0;
                        document.getElementById('totalTasks').textContent = stats.total_tasks || 0;
                        
                        // Update advanced stats
                        updateAdvancedStats(stats);
                    } else {
                        console.error('Error loading statistics:', response.message);
                        
                        // Default values
                        document.getElementById('totalProjects').textContent = '0';
                        document.getElementById('activeProjects').textContent = '0';
                        document.getElementById('inProgressProjects').textContent = '0';
                        document.getElementById('completedProjects').textContent = '0';
                        document.getElementById('totalTasks').textContent = '0';
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Statistics AJAX error:', xhr.responseText, status, error);
                    
                    // Default values
                    document.getElementById('totalProjects').textContent = '0';
                    document.getElementById('activeProjects').textContent = '0';
                    document.getElementById('inProgressProjects').textContent = '0';
                    document.getElementById('completedProjects').textContent = '0';
                    document.getElementById('totalTasks').textContent = '0';
                }
            });
        };

        // Render projects with modern design
        const renderProjects = (projects) => {
            const tbody = document.getElementById('projectsTableBody');
            tbody.innerHTML = '';
            
            if (!projects || !Array.isArray(projects) || projects.length === 0) {
                document.getElementById('emptyState').style.display = 'block';
                document.getElementById('tableContainer').style.display = 'none';
                document.getElementById('paginationContainer').style.display = 'none';
                return;
            }
            
            projects.forEach((project, index) => {
                const row = document.createElement('tr');
                row.id = `project-row-${project.id}`;
                row.style.animationDelay = `${index * 0.05}s`;
                
                const progressBarWidth = project.progress || 0;
                
                row.innerHTML = `
                    <td>
                        <div class="project-name-cell">
                            <div class="project-name-modern">
                                <div class="project-icon-modern" style="background: ${getProjectColor(project.name)}">
                                    <i class="fas fa-project-diagram"></i>
                                </div>
                                <div>
                                    <div class="project-name-text">
                                        ${project.name.length > 20 ? project.name.substring(0, 20) + '...' : project.name}
                                        ${project.contract_number ? `<small class="text-muted ms-2">(Contrat: ${project.contract_number})</small>` : ''}
                                    </div>
                                    <small class="text-muted">
                                        <i class="fas fa-calendar me-1"></i>Créé le ${project.created_at}
                                    </small>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td>
                        ${project.client ? `
                            <div class="client-info">
                                <div class="client-name">${project.client.name}</div>
                                <small class="text-muted">
                                    <i class="fas fa-map-marker-alt me-1"></i>${project.client.ville || 'N/A'}
                                </small>
                                ${project.contact_name ? `<div class="contact-name"><i class="fas fa-user me-1"></i>${project.contact_name}</div>` : ''}
                            </div>
                        ` : '<span class="text-muted">Aucun client</span>'}
                    </td>
                    <td>
                        ${project.user ? `
                            <div class="user-info">
                                <div class="user-avatar-sm" style="background: ${getUserColor(project.user.name)}">
                                    ${getInitials(project.user.name)}
                                </div>
                                <div class="user-details">
                                    <div class="user-name">${project.user.name}</div>
                                    <small class="text-muted">${project.user.email}</small>
                                </div>
                            </div>
                        ` : 'N/A'}
                    </td>
                    <td>
                        <div class="deadline-info">
                            ${project.end_date ? `
                                <div class="deadline-date ${isDeadlineNear(project.end_date) ? 'deadline-near' : ''}">
                                    <i class="fas fa-calendar-day me-1"></i>${project.end_date}
                                </div>
                                <small class="text-muted">Début: ${project.start_date || 'N/A'}</small>
                            ` : '<span class="text-muted">Non définie</span>'}
                        </div>
                    </td>
                    <td>
                        <div class="progress-container">
                            <div class="progress-percentage">${project.progress}%</div>
                            <div class="progress-modern">
                                <div class="progress-bar-modern" style="width: ${project.progress}%; background: ${getProgressColor(project.progress)}"></div>
                            </div>
                            <small class="text-muted">
                                ${project.completed_tasks_count || 0}/${project.tasks_count || 0} tâches
                            </small>
                        </div>
                    </td>
                    <td>
                        ${getStatusBadge(project.status_formatted, project.status_color)}
                        <small class="text-muted d-block mt-1">
                            ${project.is_active ? 
                                '<span class="badge bg-success bg-opacity-10 text-success">Actif</span>' : 
                                '<span class="badge bg-secondary bg-opacity-10 text-secondary">Inactif</span>'}
                        </small>
                    </td>
                    <td>
                        <div class="project-actions-modern">
                            <a href="/projects/${project.id}" 
                               class="action-btn-modern view-btn-modern" title="Voir détails">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="/projects/${project.id}/edit"
                               class="action-btn-modern edit-btn-modern" title="Modifier">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button class="action-btn-modern delete-btn-modern" title="Supprimer" 
                                    onclick="showDeleteConfirmation(${project.id})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                `;
                
                tbody.appendChild(row);
            });
            
            document.getElementById('emptyState').style.display = 'none';
            document.getElementById('tableContainer').style.display = 'block';
            document.getElementById('paginationContainer').style.display = 'flex';
        };

        // Helper functions
        const getInitials = (name) => {
            if (!name) return '?';
            return name.split(' ').map(n => n[0]).join('').toUpperCase().substring(0, 2);
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

        const isDeadlineNear = (dateStr) => {
            if (!dateStr) return false;
            const dateParts = dateStr.split('/');
            if (dateParts.length !== 3) return false;
            
            const deadlineDate = new Date(dateParts[2], dateParts[1] - 1, dateParts[0]);
            const today = new Date();
            const diffDays = Math.ceil((deadlineDate - today) / (1000 * 60 * 60 * 24));
            
            return diffDays >= 0 && diffDays <= 7;
        };

        const getStatusBadge = (status, color) => {
            const statusColors = {
                'Planification': 'info',
                'En cours': 'primary',
                'En pause': 'warning',
                'Terminé': 'success',
                'Annulé': 'danger'
            };
            
            const badgeColor = statusColors[status] || 'secondary';
            return `<span class="badge bg-${badgeColor}"><i class="fas fa-circle me-1" style="font-size: 0.5rem;"></i>${status}</span>`;
        };

        // Show delete confirmation modal
        const showDeleteConfirmation = (projectId) => {
            const project = allProjects.find(p => p.id === projectId);
            
            if (!project) {
                showAlert('danger', 'Projet non trouvé');
                return;
            }
            
            projectToDelete = project;
            
            document.getElementById('projectToDeleteInfo').innerHTML = `
                <div class="project-info">
                    <div class="project-info-icon" style="background: ${getProjectColor(project.name)}">
                        <i class="fas fa-project-diagram fa-2x"></i>
                    </div>
                    <div>
                        <div class="project-info-name">${project.name}</div>
                        <div class="project-info-details">
                            <div><strong>Client:</strong> ${project.client?.name || 'N/A'}</div>
                            <div><strong>Responsable:</strong> ${project.user?.name || 'N/A'}</div>
                            <div><strong>Tâches:</strong> ${project.completed_tasks_count || 0}/${project.tasks_count || 0}</div>
                            <div><strong>Avancement:</strong> ${project.progress}%</div>
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

        // Delete project
        const deleteProject = () => {
            if (!projectToDelete) {
                showAlert('danger', 'Aucun projet à supprimer');
                return;
            }
            
            const projectId = projectToDelete.id;
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
            const row = document.getElementById(`project-row-${projectId}`);
            if (row) {
                row.classList.add('deleting-row');
            }
            
            // Send DELETE request
            $.ajax({
                url: `/projects/${projectId}`,
                type: 'DELETE',
                dataType: 'json',
                success: function(response) {
                    // Hide modal
                    const deleteModal = bootstrap.Modal.getInstance(document.getElementById('deleteConfirmationModal'));
                    deleteModal.hide();
                    
                    if (response.success) {
                        // Remove project from array
                        allProjects = allProjects.filter(p => p.id !== projectId);
                        
                        // Update statistics
                        loadStatistics();
                        
                        // Show success message
                        showAlert('success', response.message || 'Projet supprimé avec succès !');
                        
                        // Remove row after animation
                        if (row) {
                            setTimeout(() => {
                                row.remove();
                                
                                // Check if table is now empty
                                const tbody = document.getElementById('projectsTableBody');
                                if (tbody.children.length === 0) {
                                    document.getElementById('emptyState').style.display = 'block';
                                    document.getElementById('tableContainer').style.display = 'none';
                                    document.getElementById('paginationContainer').style.display = 'none';
                                }
                            }, 300);
                        } else {
                            // Reload table
                            setTimeout(() => {
                                loadProjects(currentPage, currentFilters);
                            }, 500);
                        }
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
                    const row = document.getElementById(`project-row-${projectId}`);
                    if (row) {
                        row.classList.remove('deleting-row');
                    }
                    
                    if (xhr.status === 404) {
                        showAlert('danger', 'Projet non trouvé.');
                        loadProjects(currentPage, currentFilters);
                    } else {
                        showAlert('danger', 'Erreur lors de la suppression: ' + error);
                    }
                },
                complete: function() {
                    projectToDelete = null;
                }
            });
        };

        // Render pagination
        const renderPagination = (response) => {
            const pagination = document.getElementById('pagination');
            const paginationInfo = document.getElementById('paginationInfo');
            
            // Update pagination info
            const start = (response.current_page - 1) * response.per_page + 1;
            const end = Math.min(response.current_page * response.per_page, response.total);
            paginationInfo.textContent = `Affichage de ${start} à ${end} sur ${response.total} projets`;
            
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
            loadProjects(page, currentFilters);
        };

        // Update advanced stats
        const updateAdvancedStats = (stats) => {
            const advancedStatsContainer = document.getElementById('advancedStats') || createAdvancedStatsContainer();
            
            let statusHtml = '';
            if (stats.by_status && stats.by_status.length > 0) {
                stats.by_status.forEach(item => {
                    const statusLabels = {
                        'planning': 'Planification',
                        'in_progress': 'En cours',
                        'on_hold': 'En pause',
                        'completed': 'Terminé',
                        'cancelled': 'Annulé'
                    };
                    
                    const percentage = stats.total > 0 ? Math.round((item.total / stats.total) * 100) : 0;
                    statusHtml += `
                        <div class="status-stat">
                            <span class="status-name">${statusLabels[item.status] || item.status}</span>
                            <span class="status-count">${item.total} (${percentage}%)</span>
                        </div>
                    `;
                });
            }
            
            let clientsHtml = '';
            if (stats.by_client && stats.by_client.length > 0) {
                stats.by_client.forEach(item => {
                    clientsHtml += `
                        <div class="client-stat">
                            <span class="client-name">${item.client_name}</span>
                            <span class="client-count">${item.total}</span>
                        </div>
                    `;
                });
            }
            
            advancedStatsContainer.innerHTML = `
                <div class="advanced-stats-grid">
                    <div class="advanced-stats-section">
                        <h4 class="stats-section-title">
                            <i class="fas fa-chart-pie me-2"></i>Répartition par Statut
                        </h4>
                        <div class="status-stats">
                            ${statusHtml || '<p class="text-muted">Aucune donnée</p>'}
                        </div>
                    </div>
                    
                    <div class="advanced-stats-section">
                        <h4 class="stats-section-title">
                            <i class="fas fa-chart-line me-2"></i>Informations Globales
                        </h4>
                        <div class="global-stats">
                            <div class="global-stat">
                                <span class="global-label">Total heures estimées:</span>
                                <span class="global-value">${formatNumber(stats.total_hours)}h</span>
                            </div>
                            <div class="global-stat">
                                <span class="global-label">Budget total estimé:</span>
                                <span class="global-value">${formatCurrency(stats.total_budget)}</span>
                            </div>
                            <div class="global-stat">
                                <span class="global-label">Moyenne heures/projet:</span>
                                <span class="global-value">${stats.total > 0 ? Math.round(stats.total_hours / stats.total) : 0}h</span>
                            </div>
                            <div class="global-stat">
                                <span class="global-label">Moyenne budget/projet:</span>
                                <span class="global-value">${stats.total > 0 ? formatCurrency(stats.total_budget / stats.total) : '0 €'}</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="advanced-stats-section">
                        <h4 class="stats-section-title">
                            <i class="fas fa-building me-2"></i>Top Clients
                        </h4>
                        <div class="clients-stats">
                            ${clientsHtml || '<p class="text-muted">Aucun client</p>'}
                        </div>
                    </div>
                </div>
            `;
        };

        // Format number
        const formatNumber = (num) => {
            if (num === null || num === undefined) return '0';
            return new Intl.NumberFormat('fr-FR').format(num);
        };

        // Format currency
        const formatCurrency = (num) => {
            if (num === null || num === undefined) return '0 €';
            return new Intl.NumberFormat('fr-FR', { style: 'currency', currency: 'EUR' }).format(num);
        };

        // Create advanced stats container
        const createAdvancedStatsContainer = () => {
            const container = document.createElement('div');
            container.id = 'advancedStats';
            
            const statsGrid = document.querySelector('.stats-grid');
            if (statsGrid) {
                statsGrid.parentNode.insertBefore(container, statsGrid.nextSibling);
            }
            
            return container;
        };

        // Show loading state
        const showLoading = () => {
            document.getElementById('loadingSpinner').style.display = 'flex';
            document.getElementById('tableContainer').style.display = 'none';
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
                        loadProjects(1, currentFilters);
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
                        client_id: document.getElementById('filterClient').value,
                        user_id: document.getElementById('filterUser').value,
                        status: document.getElementById('filterStatus').value,
                        is_active: document.getElementById('filterActive').value,
                        sort_by: document.getElementById('filterSortBy').value,
                    };
                    loadProjects(1, currentFilters);
                });
            }
            
            // Clear filters
            const clearFiltersBtn = document.getElementById('clearFiltersBtn');
            if (clearFiltersBtn) {
                clearFiltersBtn.addEventListener('click', () => {
                    document.getElementById('filterClient').value = '';
                    document.getElementById('filterUser').value = '';
                    document.getElementById('filterStatus').value = '';
                    document.getElementById('filterActive').value = '';
                    document.getElementById('filterSortBy').value = 'created_at';
                    currentFilters = {};
                    loadProjects(1);
                });
            }
            
            // Confirm delete button
            const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
            if (confirmDeleteBtn) {
                confirmDeleteBtn.addEventListener('click', deleteProject);
            }
            
            // Reset delete modal when hidden
            const deleteModal = document.getElementById('deleteConfirmationModal');
            if (deleteModal) {
                deleteModal.addEventListener('hidden.bs.modal', function() {
                    projectToDelete = null;
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
        /* Styles spécifiques pour la page projets */
        .project-name-modern {
            display: flex;
            align-items: flex-start;
            gap: 12px;
        }

        .project-icon-modern {
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

        .project-name-text {
            font-weight: 600;
            color: var(--text-color);
            margin-bottom: 2px;
        }

        .client-info {
            display: flex;
            flex-direction: column;
        }

        .client-name {
            font-weight: 500;
            color: var(--text-color);
            margin-bottom: 2px;
        }

        .contact-name {
            font-size: 0.85rem;
            color: #666;
            margin-top: 2px;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .user-avatar-sm {
            width: 30px;
            height: 30px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 0.85rem;
        }

        .user-details {
            display: flex;
            flex-direction: column;
        }

        .user-name {
            font-weight: 500;
            font-size: 0.9rem;
            color: var(--text-color);
        }

        .deadline-info {
            display: flex;
            flex-direction: column;
        }

        .deadline-date {
            font-weight: 500;
            font-size: 0.9rem;
            color: #333;
        }

        .deadline-near {
            color: #ef476f;
            font-weight: 600;
        }

        .progress-container {
            min-width: 120px;
        }

        .progress-percentage {
            font-weight: 600;
            font-size: 0.9rem;
            color: #333;
            margin-bottom: 4px;
        }

        .progress-modern {
            height: 6px;
            background: #e9ecef;
            border-radius: 3px;
            overflow: hidden;
            margin-bottom: 4px;
        }

        .progress-bar-modern {
            height: 100%;
            border-radius: 3px;
            transition: width 0.3s ease;
        }

        .project-actions-modern {
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

        .project-info {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 15px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 10px;
        }

        .project-info-icon {
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

        .project-info-name {
            font-weight: 600;
            font-size: 1.2rem;
            color: var(--text-color);
            margin-bottom: 5px;
        }

        .project-info-details {
            font-size: 0.9rem;
            color: #666;
        }

        .project-info-details div {
            margin-bottom: 2px;
        }

        /* Advanced stats grid */
        .advanced-stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-top: 30px;
            margin-bottom: 30px;
        }

        .advanced-stats-section {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            border: 1px solid #eaeaea;
        }

        .stats-section-title {
            font-size: 1rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #f0f0f0;
        }

        .status-stats, .clients-stats {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .status-stat, .client-stat {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 12px;
            background: #f8f9fa;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .status-stat:hover, .client-stat:hover {
            background: #e9ecef;
            transform: translateX(3px);
        }

        .status-name, .client-name {
            font-weight: 500;
            color: #333;
        }

        .status-count, .client-count {
            background: linear-gradient(135deg, #45b7d1, #3a9bb8);
            color: white;
            padding: 3px 10px;
            border-radius: 15px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .global-stats {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .global-stat {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px dashed #e9ecef;
        }

        .global-stat:last-child {
            border-bottom: none;
        }

        .global-label {
            font-weight: 500;
            color: #666;
            font-size: 0.9rem;
        }

        .global-value {
            font-weight: 600;
            color: #333;
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

        /* Responsive adjustments */
        @media (max-width: 992px) {
            .advanced-stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .advanced-stats-grid {
                grid-template-columns: 1fr;
            }
            
            .project-name-modern {
                flex-direction: column;
                align-items: flex-start;
                gap: 8px;
            }
            
            .project-actions-modern {
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
        }
    </style>
@endsection