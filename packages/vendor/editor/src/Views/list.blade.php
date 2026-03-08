@extends('layouts.app')
@section('content')
    
    <!-- MAIN CONTENT -->
    <main class="dashboard-content">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">
                <span class="page-title-icon"><i class="fas fa-palette"></i></span>
                Gestion des Templates
            </h1>
            
            <div class="page-actions">
                <button class="btn btn-outline-secondary" id="toggleFilterBtn">
                    <i class="fas fa-sliders-h me-2"></i>Filtres
                </button>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createTemplateModal">
                    <i class="fas fa-plus-circle me-2"></i>Nouveau Template
                </button>
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
                    <label for="filterStatus" class="form-label-modern">Statut</label>
                    <select class="form-select-modern" id="filterStatus">
                        <option value="">Tous les statuts</option>
                        <option value="active">Actif</option>
                        <option value="inactive">Inactif</option>
                        <option value="draft">Brouillon</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="filterCategory" class="form-label-modern">Catégorie</label>
                    <select class="form-select-modern" id="filterCategory">
                        <option value="">Toutes les catégories</option>
                        <option value="voyage">Voyage</option>
                        <option value="ecommerce">E-commerce</option>
                        <option value="blog">Blog</option>
                        <option value="portfolio">Portfolio</option>
                        <option value="entreprise">Entreprise</option>
                        <option value="restaurant">Restaurant</option>
                        <option value="evenement">Événement</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="filterDateFrom" class="form-label-modern">Date de début</label>
                    <input type="date" class="form-control-modern" id="filterDateFrom">
                </div>
                <div class="col-md-3">
                    <label for="filterDateTo" class="form-label-modern">Date de fin</label>
                    <input type="date" class="form-control-modern" id="filterDateTo">
                </div>
            </div>
        </div>
        
        <!-- Stats Cards - Modern Design -->
        <div class="stats-grid">
            <div class="stats-card-modern">
                <div class="stats-header-modern">
                    <div>
                        <div class="stats-value-modern" id="totalTemplates">0</div>
                        <div class="stats-label-modern">Total Templates</div>
                    </div>
                    <div class="stats-icon-modern" style="background: linear-gradient(135deg, var(--primary-color), #3a56e4);">
                        <i class="fas fa-palette"></i>
                    </div>
                </div>
            </div>
            
            <div class="stats-card-modern">
                <div class="stats-header-modern">
                    <div>
                        <div class="stats-value-modern" id="activeTemplates">0</div>
                        <div class="stats-label-modern">Templates Actifs</div>
                    </div>
                    <div class="stats-icon-modern" style="background: linear-gradient(135deg, var(--accent-color), #06b48a);">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
            </div>
            
            <div class="stats-card-modern">
                <div class="stats-header-modern">
                    <div>
                        <div class="stats-value-modern" id="categoriesCount">0</div>
                        <div class="stats-label-modern">Catégories</div>
                    </div>
                    <div class="stats-icon-modern" style="background: linear-gradient(135deg, #ffd166, #ffb347);">
                        <i class="fas fa-tags"></i>
                    </div>
                </div>
            </div>
            
            <div class="stats-card-modern">
                <div class="stats-header-modern">
                    <div>
                        <div class="stats-value-modern" id="thisMonth">0</div>
                        <div class="stats-label-modern">Ajoutés ce mois</div>
                    </div>
                    <div class="stats-icon-modern" style="background: linear-gradient(135deg, #ef476f, #d4335f);">
                        <i class="fas fa-calendar-plus"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Main Card - Modern Design -->
        <div class="main-card-modern">
            <div class="card-header-modern">
                <h3 class="card-title-modern">Liste des Templates</h3>
                <div class="search-container">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" class="search-input" placeholder="Rechercher un template..." id="searchInput">
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
                                <th>Template</th>
                                <th>URL</th>
                                <th>Catégorie</th>
                                <th>Statut</th>
                                <th>Créé le</th>
                                <th style="text-align: center;">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="templatesTableBody">
                            <!-- Templates will be loaded here via AJAX -->
                        </tbody>
                    </table>
                </div>
                
                <!-- Empty State -->
                <div class="empty-state-modern" id="emptyState" style="display: none;">
                    <div class="empty-icon-modern">
                        <i class="fas fa-palette"></i>
                    </div>
                    <h3 class="empty-title-modern">Aucun template trouvé</h3>
                    <p class="empty-text-modern">Commencez par créer votre premier template pour votre plateforme GO EXPLORIA.</p>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createTemplateModal">
                        <i class="fas fa-plus-circle me-2"></i>Créer un template
                    </button>
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
        <button class="fab-modern" data-bs-toggle="modal" data-bs-target="#createTemplateModal">
            <i class="fas fa-plus"></i>
        </button>
    </main>
    
    <!-- CREATE TEMPLATE MODAL - SIMPLIFIED VERSION -->
    <div class="modal fade" id="createTemplateModal" tabindex="-1" aria-labelledby="createTemplateModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content modal-content-modern">
                <div class="modal-header modal-header-modern">
                    <h5 class="modal-title modal-title-modern" id="createTemplateModalLabel">
                        <i class="fas fa-plus-circle me-2"></i>Créer un nouveau template
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body modal-body-modern">
                    <form id="createTemplateForm">
                        @csrf
                        <div class="row">
                            <div class="col-md-12 mb-4">
                                <label for="templateName" class="form-label-modern">Nom du template *</label>
                                <input type="text" class="form-control-modern" id="templateName" name="name" placeholder="Ex: Template Voyage Québec" required>
                                <div class="form-text-modern">Donnez un nom descriptif à votre template</div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12 mb-4">
                                <label for="templateUrl" class="form-label-modern">URL *</label>
                                <div class="input-group">
                                    <span class="input-group-text">https://</span>
                                    <input type="text" class="form-control-modern" id="templateUrl" name="url" placeholder="exemple.com/template-voyage" required>
                                </div>
                                <div class="form-text-modern">URL complète du template</div>
                            </div>
                        </div>
                        
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Note :</strong> Seuls le nom et l'URL sont requis pour créer un template. Vous pourrez ajouter d'autres détails après la création.
                        </div>
                    </form>
                </div>
                <div class="modal-footer modal-footer-modern">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="button" class="btn btn-primary btn-pulse" id="submitTemplateBtn">
                        <span class="btn-text">
                            <i class="fas fa-save me-2"></i>Créer le template
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- EDIT TEMPLATE MODAL - SIMPLIFIED VERSION -->
    <div class="modal fade" id="editTemplateModal" tabindex="-1" aria-labelledby="editTemplateModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content modal-content-modern">
                <div class="modal-header modal-header-modern">
                    <h5 class="modal-title modal-title-modern" id="editTemplateModalLabel">
                        <i class="fas fa-edit me-2"></i>Modifier le template
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body modal-body-modern">
                    <form id="editTemplateForm">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="editTemplateId" name="id">
                        
                        <div class="row">
                            <div class="col-md-12 mb-4">
                                <label for="editTemplateName" class="form-label-modern">Nom du template *</label>
                                <input type="text" class="form-control-modern" id="editTemplateName" name="name" required>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12 mb-4">
                                <label for="editTemplateUrl" class="form-label-modern">URL *</label>
                                <div class="input-group">
                                    <span class="input-group-text">https://</span>
                                    <input type="text" class="form-control-modern" id="editTemplateUrl" name="url" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="editTemplateCategory" class="form-label-modern">Catégorie</label>
                                <select class="form-select-modern" id="editTemplateCategory" name="category">
                                    <option value="">Sélectionnez une catégorie</option>
                                    <option value="voyage">Voyage</option>
                                    <option value="ecommerce">E-commerce</option>
                                    <option value="blog">Blog</option>
                                    <option value="portfolio">Portfolio</option>
                                    <option value="entreprise">Entreprise</option>
                                    <option value="restaurant">Restaurant</option>
                                    <option value="evenement">Événement</option>
                                </select>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="editTemplateStatus" class="form-label-modern">Statut</label>
                                <select class="form-select-modern" id="editTemplateStatus" name="status">
                                    <option value="active">Actif</option>
                                    <option value="inactive">Inactif</option>
                                    <option value="draft">Brouillon</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="editTemplateDescription" class="form-label-modern">Description</label>
                            <textarea class="form-control-modern" id="editTemplateDescription" name="description" rows="3"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer modal-footer-modern">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="button" class="btn btn-primary" id="updateTemplateBtn">
                        <span class="btn-text">
                            <i class="fas fa-save me-2"></i>Enregistrer les modifications
                        </span>
                    </button>
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
                    <p class="delete-message">Êtes-vous sûr de vouloir supprimer ce template ? Cette action est irréversible.</p>
                    
                    <div class="template-to-delete" id="templateToDeleteInfo">
                        <!-- Template info will be loaded here -->
                    </div>
                    
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <strong>Attention :</strong> Toutes les données associées à ce template seront également supprimées.
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
    
    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery (for AJAX) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Configuration
        let currentPage = 1;
        let currentFilters = {};
        let allTemplates = [];
        let templateToDelete = null;

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            setupAjax();
            loadTemplates();
            setupEventListeners();
            setupMobileMenu();
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

        // Load templates
        const loadTemplates = (page = 1, filters = {}) => {
            showLoading();
            
            const searchTerm = document.getElementById('searchInput')?.value || '';
            
            $.ajax({
                url: '{{ route("templates.index") }}',
                type: 'GET',
                data: {
                    page: page,
                    search: searchTerm,
                    ...filters,
                    ajax: true
                },
                success: function(response) {
                    console.log('Server response:', response);
                    
                    if (response.success) {
                        allTemplates = response.data || [];
                        renderTemplates(allTemplates);
                        renderPagination(response);
                        updateStats(allTemplates);
                        hideLoading();
                    } else {
                        showError('Erreur lors du chargement des templates');
                    }
                },
                error: function(xhr) {
                    hideLoading();
                    showError('Erreur de connexion au serveur');
                    console.error('Error:', xhr.responseText);
                }
            });
        };

        // Render templates with modern design
        const renderTemplates = (templates) => {
            const tbody = document.getElementById('templatesTableBody');
            tbody.innerHTML = '';
            
            if (!templates || !Array.isArray(templates) || templates.length === 0) {
                document.getElementById('emptyState').style.display = 'block';
                document.getElementById('tableContainer').style.display = 'none';
                document.getElementById('paginationContainer').style.display = 'none';
                return;
            }
            
            templates.forEach((template, index) => {
                const row = document.createElement('tr');
                row.id = `template-row-${template.id}`;
                row.style.animationDelay = `${index * 0.05}s`;
                
                // Format date
                const createdDate = new Date(template.created_at);
                const formattedDate = createdDate.toLocaleDateString('fr-FR', {
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric'
                });
                
                // Category class
                const categoryClass = template.category ? `category-${template.category}` : '';
                
                // Status badge
                let statusClass = 'status-active-modern';
                let statusText = 'Actif';
                
                if (template.status === 'inactive') {
                    statusClass = 'status-inactive-modern';
                    statusText = 'Inactif';
                } else if (template.status === 'draft') {
                    statusClass = 'status-draft-modern';
                    statusText = 'Brouillon';
                }
                
                row.innerHTML = `
                    <td class="template-name-cell">
                        <div class="template-name-modern">
                            <div class="template-icon-modern">
                                <i class="fas fa-palette"></i>
                            </div>
                            <div>
                                <div class="template-name-text">${template.name}</div>
                                <small class="text-muted">ID: ${template.id}</small>
                            </div>
                        </div>
                    </td>
                    <td>
                        <a href="{{url('templates/display/${template.id}')}}" target="_blank" class="template-url-modern">
                            <i class="fas fa-link me-1"></i>http://37.59.113.151/template/preview/${template.id} </a>
                        </a>
                    </td>
                    <td>
                        <span class="template-category-modern ${categoryClass}">
                            ${getCategoryName(template.category)}
                        </span>
                    </td>
                    <td>
                        <span class="template-status-modern ${statusClass}">
                            <span class="status-dot-modern"></span>
                            ${statusText}
                        </span>
                    </td>
                    <td>
                        <div>${formattedDate}</div>
                        <small class="text-muted">${formatTimeAgo(createdDate)}</small>
                    </td>
                    <td>
                        <div class="template-actions-modern">
                            <a href="{{ route('templates.display', '') }}/${template.id}" target="_blank" class="btn btn-sm btn-outline-primary" title="Prévisualiser">
                            <i class="fas fa-eye"></i>
                           </a>
                            <a href="{{ url('template/edit') }}/${template.id}" target="_blank" class="btn btn-sm btn-outline-success" title="Modifier" onclick="openEditModal(${template.id})">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button class="btn btn-sm btn-outline-danger" title="Supprimer" onclick="showDeleteConfirmation(${template.id})">
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

        // Show delete confirmation modal
        const showDeleteConfirmation = (templateId) => {
            const template = allTemplates.find(t => t.id === templateId);
            
            if (!template) {
                showAlert('danger', 'Template non trouvé');
                return;
            }
            
            templateToDelete = template;
            
            // Format date
            const createdDate = new Date(template.created_at);
            const formattedDate = createdDate.toLocaleDateString('fr-FR', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric'
            });
            
            // Status text
            let statusText = 'Actif';
            if (template.status === 'inactive') statusText = 'Inactif';
            else if (template.status === 'draft') statusText = 'Brouillon';
            
            document.getElementById('templateToDeleteInfo').innerHTML = `
                <div class="template-info">
                    <div class="template-info-icon">
                        <i class="fas fa-palette"></i>
                    </div>
                    <div>
                        <div class="template-info-name">${template.name}</div>
                        <div class="template-info-url">https://${template.url}</div>
                    </div>
                </div>
                <div class="row small text-muted">
                    <div class="col-6">
                        <div><strong>ID:</strong> ${template.id}</div>
                        <div><strong>Statut:</strong> ${statusText}</div>
                    </div>
                    <div class="col-6">
                        <div><strong>Créé le:</strong> ${formattedDate}</div>
                        <div><strong>Catégorie:</strong> ${getCategoryName(template.category)}</div>
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

        // Delete template - COMPLETE LOGIC
        const deleteTemplate = () => {
            if (!templateToDelete) {
                showAlert('danger', 'Aucun template à supprimer');
                return;
            }
            
            const templateId = templateToDelete.id;
            const deleteBtn = document.getElementById('confirmDeleteBtn');
            
            // Show processing animation on delete button
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
            const row = document.getElementById(`template-row-${templateId}`);
            if (row) {
                row.classList.add('deleting-row');
            }
            
            // Send DELETE request
            $.ajax({
                url: `/templates/delete/${templateId}`,
                type: 'DELETE',
                dataType: 'json',
                success: function(response) {
                    console.log('Delete response:', response);
                    
                    // Hide modal
                    const deleteModal = bootstrap.Modal.getInstance(document.getElementById('deleteConfirmationModal'));
                    deleteModal.hide();
                    
                    if (response.success) {
                        // Remove template from allTemplates array
                        allTemplates = allTemplates.filter(t => t.id !== templateId);
                        
                        // Update stats immediately
                        updateStats(allTemplates);
                        
                        // Show success message
                        showAlert('success', response.message || 'Template supprimé avec succès !');
                        
                        // If row still exists, remove it after animation
                        if (row) {
                            setTimeout(() => {
                                row.remove();
                                
                                // Check if table is now empty
                                const tbody = document.getElementById('templatesTableBody');
                                if (tbody.children.length === 0) {
                                    document.getElementById('emptyState').style.display = 'block';
                                    document.getElementById('tableContainer').style.display = 'none';
                                    document.getElementById('paginationContainer').style.display = 'none';
                                }
                            }, 300);
                        } else {
                            // If row doesn't exist, reload the table
                            setTimeout(() => {
                                loadTemplates(currentPage, currentFilters);
                            }, 500);
                        }
                    } else {
                        // Remove deleting animation
                        if (row) {
                            row.classList.remove('deleting-row');
                        }
                        
                        // Show error message
                        showAlert('danger', response.message || 'Erreur lors de la suppression du template');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Delete error:', xhr.responseText, status, error);
                    
                    // Hide modal
                    const deleteModal = bootstrap.Modal.getInstance(document.getElementById('deleteConfirmationModal'));
                    deleteModal.hide();
                    
                    // Remove deleting animation
                    const row = document.getElementById(`template-row-${templateId}`);
                    if (row) {
                        row.classList.remove('deleting-row');
                    }
                    
                    if (xhr.status === 404) {
                        showAlert('danger', 'Template non trouvé. Il a peut-être déjà été supprimé.');
                        // Reload templates to sync with server
                        loadTemplates(currentPage, currentFilters);
                    } else if (xhr.status === 403) {
                        showAlert('danger', 'Vous n\'avez pas la permission de supprimer ce template.');
                    } else if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;
                        let errorMessage = 'Erreur de validation :<br>';
                        for (const field in errors) {
                            errorMessage += `- ${errors[field].join('<br>')}<br>`;
                        }
                        showAlert('danger', errorMessage);
                    } else {
                        showAlert('danger', 'Erreur lors de la suppression du template : ' + error);
                    }
                },
                complete: function() {
                    // Reset templateToDelete
                    templateToDelete = null;
                }
            });
        };

        // Update stats
        const updateStats = (templates) => {
            const total = templates.length;
            const active = templates.filter(t => t.status === 'active').length;
            const categories = new Set(templates.map(t => t.category).filter(Boolean));
            const thisMonth = templates.filter(t => {
                const created = new Date(t.created_at);
                const now = new Date();
                return created.getMonth() === now.getMonth() && created.getFullYear() === now.getFullYear();
            }).length;
            
            document.getElementById('totalTemplates').textContent = total;
            document.getElementById('activeTemplates').textContent = active;
            document.getElementById('categoriesCount').textContent = categories.size;
            document.getElementById('thisMonth').textContent = thisMonth;
        };

        // Render pagination
        const renderPagination = (response) => {
            const pagination = document.getElementById('pagination');
            const paginationInfo = document.getElementById('paginationInfo');
            
            // Update pagination info
            const start = (response.current_page - 1) * response.per_page + 1;
            const end = Math.min(response.current_page * response.per_page, response.total);
            paginationInfo.textContent = `Affichage de ${start} à ${end} sur ${response.total} template${response.total > 1 ? 's' : ''}`;
            
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
            loadTemplates(page, currentFilters);
        };

        // Get category name
        const getCategoryName = (category) => {
            const categories = {
                'voyage': 'Voyage',
                'ecommerce': 'E-commerce',
                'blog': 'Blog',
                'portfolio': 'Portfolio',
                'entreprise': 'Entreprise',
                'restaurant': 'Restaurant',
                'evenement': 'Événement'
            };
            
            return categories[category] || 'Non catégorisé';
        };

        // Format time ago
        const formatTimeAgo = (date) => {
            const now = new Date();
            const diffMs = now - date;
            const diffDays = Math.floor(diffMs / (1000 * 60 * 60 * 24));
            
            if (diffDays === 0) return "Aujourd'hui";
            if (diffDays === 1) return 'Hier';
            if (diffDays < 7) return `Il y a ${diffDays} jours`;
            if (diffDays < 30) return `Il y a ${Math.floor(diffDays / 7)} semaines`;
            if (diffDays < 365) return `Il y a ${Math.floor(diffDays / 30)} mois`;
            return `Il y a ${Math.floor(diffDays / 365)} ans`;
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

        // Store template function with animation
        const storeTemplate = () => {
            const form = document.getElementById('createTemplateForm');
            const submitBtn = document.getElementById('submitTemplateBtn');
            
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }
            
            // Show processing animation
            submitBtn.classList.add('btn-processing');
            submitBtn.innerHTML = `
                <span class="btn-text" style="display: none;">
                    <i class="fas fa-save me-2"></i>Créer le template
                </span>
                <div class="spinner-border spinner-border-sm text-light" role="status">
                    <span class="visually-hidden">Chargement...</span>
                </div>
                Création en cours...
            `;
            submitBtn.disabled = true;
            
            const formData = new FormData(form);
            
            // Convert FormData to object
            const data = {};
            for (let [key, value] of formData.entries()) {
                data[key] = value;
            }
            
            console.log('Creating template with data:', data);
            
            $.ajax({
                url: '{{ route("scrape.templates.store") }}',
                type: 'POST',
                data: data,
                dataType: 'json',
                success: function(response) {
                    console.log('Create response:', response);
                    
                    // Reset button state
                    submitBtn.classList.remove('btn-processing');
                    submitBtn.innerHTML = `
                        <span class="btn-text">
                            <i class="fas fa-save me-2"></i>Créer le template
                        </span>
                    `;
                    submitBtn.disabled = false;
                    
                    if (response.success) {
                        // Close modal
                        const modal = bootstrap.Modal.getInstance(document.getElementById('createTemplateModal'));
                        modal.hide();
                        
                        // Reset form
                        form.reset();
                        
                        // Reload templates
                        loadTemplates(1, currentFilters);
                        
                        // Show success message
                        showAlert('success', 'Template créé avec succès !');
                    } else {
                        showAlert('danger', response.message || 'Erreur lors de la création du template');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Create error:', xhr.responseText, status, error);
                    
                    // Reset button state
                    submitBtn.classList.remove('btn-processing');
                    submitBtn.innerHTML = `
                        <span class="btn-text">
                            <i class="fas fa-save me-2"></i>Créer le template
                        </span>
                    `;
                    submitBtn.disabled = false;
                    
                    if (xhr.status === 422) {
                        // Validation errors
                        const errors = xhr.responseJSON.errors;
                        let errorMessage = 'Veuillez corriger les erreurs suivantes:<br>';
                        for (const field in errors) {
                            errorMessage += `- ${errors[field].join('<br>')}<br>`;
                        }
                        showAlert('danger', errorMessage);
                    } else {
                        showAlert('danger', 'Erreur lors de la création du template: ' + error);
                    }
                }
            });
        };

        // Update template function with animation
        const updateTemplate = () => {
            const form = document.getElementById('editTemplateForm');
            const submitBtn = document.getElementById('updateTemplateBtn');
            const templateId = document.getElementById('editTemplateId').value;
            
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }
            
            // Show processing animation
            submitBtn.classList.add('btn-processing');
            submitBtn.innerHTML = `
                <span class="btn-text" style="display: none;">
                    <i class="fas fa-save me-2"></i>Enregistrer les modifications
                </span>
                <div class="spinner-border spinner-border-sm text-light" role="status">
                    <span class="visually-hidden">Chargement...</span>
                </div>
                Enregistrement...
            `;
            submitBtn.disabled = true;
            
            const formData = new FormData(form);
            
            // Convert FormData to object
            const data = {};
            for (let [key, value] of formData.entries()) {
                data[key] = value;
            }
            
            // Add _method for Laravel to recognize it as PUT
            data._method = 'PUT';
            
            console.log('Updating template with data:', data);
            
            $.ajax({
                url: `/templates/${templateId}`,
                type: 'POST', // Use POST with _method override
                data: data,
                dataType: 'json',
                success: function(response) {
                    console.log('Update response:', response);
                    
                    // Reset button state
                    submitBtn.classList.remove('btn-processing');
                    submitBtn.innerHTML = `
                        <span class="btn-text">
                            <i class="fas fa-save me-2"></i>Enregistrer les modifications
                        </span>
                    `;
                    submitBtn.disabled = false;
                    
                    if (response.success) {
                        // Close modal
                        const modal = bootstrap.Modal.getInstance(document.getElementById('editTemplateModal'));
                        modal.hide();
                        
                        // Reload templates
                        loadTemplates(currentPage, currentFilters);
                        
                        // Show success message
                        showAlert('success', 'Template mis à jour avec succès !');
                    } else {
                        showAlert('danger', response.message || 'Erreur lors de la mise à jour du template');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Update error:', xhr.responseText, status, error);
                    
                    // Reset button state
                    submitBtn.classList.remove('btn-processing');
                    submitBtn.innerHTML = `
                        <span class="btn-text">
                            <i class="fas fa-save me-2"></i>Enregistrer les modifications
                        </span>
                    `;
                    submitBtn.disabled = false;
                    
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;
                        let errorMessage = '<strong>Veuillez corriger les erreurs suivantes :</strong><ul class="mb-0 mt-2">';
                        for (const field in errors) {
                            errorMessage += `<li>${errors[field].join('</li><li>')}</li>`;
                        }
                        errorMessage += '</ul>';
                        showAlert('danger', errorMessage);
                    } else {
                        showAlert('danger', 'Erreur lors de la mise à jour du template: ' + error);
                    }
                }
            });
        };

        // Open edit modal
        const openEditModal = (templateId) => {
            const template = allTemplates.find(t => t.id === templateId);
            
            if (template) {
                document.getElementById('editTemplateId').value = template.id;
                document.getElementById('editTemplateName').value = template.name;
                document.getElementById('editTemplateUrl').value = template.url.replace('https://', '');
                document.getElementById('editTemplateCategory').value = template.category || '';
                document.getElementById('editTemplateStatus').value = template.status || 'active';
                document.getElementById('editTemplateDescription').value = template.description || '';
                document.getElementById('editTemplateTags').value = template.tags || '';
                
                new bootstrap.Modal(document.getElementById('editTemplateModal')).show();
            }
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
                        loadTemplates(1, currentFilters);
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
                        status: document.getElementById('filterStatus').value,
                        category: document.getElementById('filterCategory').value,
                        date_from: document.getElementById('filterDateFrom').value,
                        date_to: document.getElementById('filterDateTo').value
                    };
                    loadTemplates(1, currentFilters);
                });
            }
            
            // Clear filters
            const clearFiltersBtn = document.getElementById('clearFiltersBtn');
            if (clearFiltersBtn) {
                clearFiltersBtn.addEventListener('click', () => {
                    document.getElementById('filterStatus').value = '';
                    document.getElementById('filterCategory').value = '';
                    document.getElementById('filterDateFrom').value = '';
                    document.getElementById('filterDateTo').value = '';
                    currentFilters = {};
                    loadTemplates(1);
                });
            }
            
            // Submit template form
            const submitTemplateBtn = document.getElementById('submitTemplateBtn');
            if (submitTemplateBtn) {
                submitTemplateBtn.addEventListener('click', storeTemplate);
            }
            
            // Update template form
            const updateTemplateBtn = document.getElementById('updateTemplateBtn');
            if (updateTemplateBtn) {
                updateTemplateBtn.addEventListener('click', updateTemplate);
            }
            
            // Confirm delete button
            const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
            if (confirmDeleteBtn) {
                confirmDeleteBtn.addEventListener('click', deleteTemplate);
            }
            
            // Reset delete modal when hidden
            const deleteModal = document.getElementById('deleteConfirmationModal');
            if (deleteModal) {
                deleteModal.addEventListener('hidden.bs.modal', function() {
                    templateToDelete = null;
                    const deleteBtn = document.getElementById('confirmDeleteBtn');
                    deleteBtn.innerHTML = `
                        <span class="btn-text">
                            <i class="fas fa-trash me-2"></i>Supprimer définitivement
                        </span>
                    `;
                    deleteBtn.disabled = false;
                });
            }
            
            // Reset create form when modal is hidden
            const createModal = document.getElementById('createTemplateModal');
            if (createModal) {
                createModal.addEventListener('hidden.bs.modal', function() {
                    document.getElementById('createTemplateForm').reset();
                    const submitBtn = document.getElementById('submitTemplateBtn');
                    submitBtn.classList.remove('btn-processing');
                    submitBtn.innerHTML = `
                        <span class="btn-text">
                            <i class="fas fa-save me-2"></i>Créer le template
                        </span>
                    `;
                    submitBtn.disabled = false;
                });
            }
            
            // Reset edit form when modal is hidden
            const editModal = document.getElementById('editTemplateModal');
            if (editModal) {
                editModal.addEventListener('hidden.bs.modal', function() {
                    const submitBtn = document.getElementById('updateTemplateBtn');
                    submitBtn.classList.remove('btn-processing');
                    submitBtn.innerHTML = `
                        <span class="btn-text">
                            <i class="fas fa-save me-2"></i>Enregistrer les modifications
                        </span>
                    `;
                    submitBtn.disabled = false;
                });
            }
        };

        // Mobile menu functionality
        const setupMobileMenu = () => {
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebar = document.getElementById('dashboardSidebar');
            const overlay = document.getElementById('overlay');
            
            if (sidebarToggle && sidebar && overlay) {
                sidebarToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('active');
                    overlay.classList.toggle('active');
                    
                    const icon = this.querySelector('i');
                    if (icon.classList.contains('fa-bars')) {
                        icon.classList.remove('fa-bars');
                        icon.classList.add('fa-times');
                    } else {
                        icon.classList.remove('fa-times');
                        icon.classList.add('fa-bars');
                    }
                });
                
                overlay.addEventListener('click', function() {
                    sidebar.classList.remove('active');
                    this.classList.remove('active');
                    
                    const icon = sidebarToggle.querySelector('i');
                    icon.classList.remove('fa-times');
                    icon.classList.add('fa-bars');
                });
            }
        };
    </script>
@endsection