@extends('layouts.app')

@section('content')
    <!-- MAIN CONTENT -->
    <main class="dashboard-content">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">
                <span class="page-title-icon"><i class="fas fa-shield-alt"></i></span>
                Gestion des Rôles & Permissions
            </h1>
            
            <div class="page-actions">
                <button class="btn btn-outline-secondary" id="toggleFilterBtn">
                    <i class="fas fa-sliders-h me-2"></i>Filtres
                </button>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createRoleModal">
                    <i class="fas fa-plus-circle me-2"></i>Nouveau Rôle
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
                <div class="col-md-4">
                    <label for="filterGuard" class="form-label-modern">Guard</label>
                    <select class="form-select-modern" id="filterGuard">
                        <option value="">Tous les guards</option>
                        <option value="web">Web</option>
                        <option value="api">API</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="filterSortBy" class="form-label-modern">Trier par</label>
                    <select class="form-select-modern" id="filterSortBy">
                        <option value="name">Nom</option>
                        <option value="guard_name">Guard</option>
                        <option value="created_at">Date de création</option>
                        <option value="permissions_count">Nombre de permissions</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="filterSortDirection" class="form-label-modern">Ordre</label>
                    <select class="form-select-modern" id="filterSortDirection">
                        <option value="asc">Croissant</option>
                        <option value="desc">Décroissant</option>
                    </select>
                </div>
            </div>
        </div>
        
        <!-- Main Card - Modern Design -->
        <div class="main-card-modern">
            <div class="card-header-modern">
                <h3 class="card-title-modern">Liste des Rôles</h3>
                <div class="search-container">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" class="search-input" placeholder="Rechercher un rôle..." id="searchInput">
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
                                <th style="width: 80px;">ID</th>
                                <th>Rôle</th>
                                <th>Guard</th>
                                <th>Permissions</th>
                                <th>Date création</th>
                                <th style="text-align: center;">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="rolesTableBody">
                            <!-- Roles will be loaded here via AJAX -->
                        </tbody>
                    </table>
                </div>
                
                <!-- Empty State -->
                <div class="empty-state-modern" id="emptyState" style="display: none;">
                    <div class="empty-icon-modern">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3 class="empty-title-modern">Aucun rôle trouvé</h3>
                    <p class="empty-text-modern">Commencez par créer votre premier rôle.</p>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createRoleModal">
                        <i class="fas fa-plus-circle me-2"></i>Créer un rôle
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

        <!-- Permissions Section -->
        <div class="main-card-modern mt-4">
            <div class="card-header-modern">
                <h3 class="card-title-modern">
                    <i class="fas fa-key me-2"></i>
                    Gestion des Permissions
                </h3>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createPermissionModal">
                    <i class="fas fa-plus-circle me-2"></i>Nouvelle Permission
                </button>
            </div>
            
            <div class="card-body-modern">
                <div class="table-container-modern">
                    <table class="modern-table">
                        <thead>
                            <tr>
                                <th style="width: 80px;">ID</th>
                                <th>Permission</th>
                                <th>Guard</th>
                                <th>Group</th>
                                <th>Date création</th>
                                <th style="text-align: center;">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="permissionsTableBody">
                            <!-- Permissions will be loaded here via AJAX -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <!-- Floating Action Button -->
        <button class="fab-modern" data-bs-toggle="modal" data-bs-target="#createRoleModal">
            <i class="fas fa-plus-circle"></i>
        </button>
    </main>
    
    <!-- Modals -->
    <!-- Create Role Modal -->
    <div class="modal fade" id="createRoleModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content-modern">
                <div class="modal-header-modern">
                    <h5 class="modal-title-modern">
                        <i class="fas fa-plus-circle me-2"></i>
                        Créer un nouveau rôle
                    </h5>
                    <button type="button" class="btn-close-modern" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <form id="createRoleForm">
                    <div class="modal-body-modern">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group-modern">
                                    <label class="form-label-modern" for="roleName">
                                        <i class="fas fa-tag me-1"></i>Nom du rôle *
                                    </label>
                                    <input type="text" class="form-control-modern" id="roleName" name="name" 
                                           placeholder="ex: admin, manager, editor..." required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group-modern">
                                    <label class="form-label-modern" for="roleGuard">
                                        <i class="fas fa-shield me-1"></i>Guard *
                                    </label>
                                    <select class="form-select-modern" id="roleGuard" name="guard_name" required>
                                        <option value="web">Web</option>
                                        <option value="api">API</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group-modern mt-3">
                            <label class="form-label-modern">
                                <i class="fas fa-lock me-1"></i>Permissions
                            </label>
                            <div class="permissions-container" id="permissionsContainer">
                                <!-- Permissions will be loaded here -->
                                <div class="text-center text-muted py-3">
                                    <i class="fas fa-spinner fa-spin me-2"></i>Chargement des permissions...
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer-modern">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-2"></i>Annuler
                        </button>
                        <button type="submit" class="btn btn-primary" id="submitRoleBtn">
                            <i class="fas fa-save me-2"></i>Créer le rôle
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Role Modal -->
    <div class="modal fade" id="editRoleModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content-modern">
                <div class="modal-header-modern">
                    <h5 class="modal-title-modern">
                        <i class="fas fa-edit me-2"></i>
                        Modifier le rôle
                    </h5>
                    <button type="button" class="btn-close-modern" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <form id="editRoleForm">
                    <input type="hidden" id="editRoleId" name="role_id">
                    <div class="modal-body-modern">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group-modern">
                                    <label class="form-label-modern" for="editRoleName">
                                        <i class="fas fa-tag me-1"></i>Nom du rôle *
                                    </label>
                                    <input type="text" class="form-control-modern" id="editRoleName" name="name" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group-modern">
                                    <label class="form-label-modern" for="editRoleGuard">
                                        <i class="fas fa-shield me-1"></i>Guard *
                                    </label>
                                    <select class="form-select-modern" id="editRoleGuard" name="guard_name" required>
                                        <option value="web">Web</option>
                                        <option value="api">API</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group-modern mt-3">
                            <label class="form-label-modern">
                                <i class="fas fa-lock me-1"></i>Permissions
                            </label>
                            <div class="permissions-container" id="editPermissionsContainer">
                                <!-- Permissions will be loaded here -->
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer-modern">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-2"></i>Annuler
                        </button>
                        <button type="submit" class="btn btn-primary" id="updateRoleBtn">
                            <i class="fas fa-save me-2"></i>Mettre à jour
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Create Permission Modal -->
    <div class="modal fade" id="createPermissionModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content-modern">
                <div class="modal-header-modern">
                    <h5 class="modal-title-modern">
                        <i class="fas fa-plus-circle me-2"></i>
                        Créer une permission
                    </h5>
                    <button type="button" class="btn-close-modern" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <form id="createPermissionForm">
                    <div class="modal-body-modern">
                        <div class="form-group-modern">
                            <label class="form-label-modern" for="permissionName">
                                <i class="fas fa-tag me-1"></i>Nom de la permission *
                            </label>
                            <input type="text" class="form-control-modern" id="permissionName" name="name" 
                                   placeholder="ex: create-users, edit-roles..." required>
                            <small class="text-muted">Utilisez le format: action-ressource (ex: create-users)</small>
                        </div>
                        
                        <div class="form-group-modern mt-3">
                            <label class="form-label-modern" for="permissionGuard">
                                <i class="fas fa-shield me-1"></i>Guard *
                            </label>
                            <select class="form-select-modern" id="permissionGuard" name="guard_name" required>
                                <option value="web">Web</option>
                                <option value="api">API</option>
                            </select>
                        </div>

                        <div class="form-group-modern mt-3">
                            <label class="form-label-modern" for="permissionGroup">
                                <i class="fas fa-folder me-1"></i>Groupe
                            </label>
                            <input type="text" class="form-control-modern" id="permissionGroup" name="group" 
                                   placeholder="ex: users, roles, permissions...">
                        </div>
                    </div>
                    <div class="modal-footer-modern">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-2"></i>Annuler
                        </button>
                        <button type="submit" class="btn btn-primary" id="submitPermissionBtn">
                            <i class="fas fa-save me-2"></i>Créer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteConfirmationModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content-modern">
                <div class="modal-header-modern">
                    <h5 class="modal-title-modern text-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Confirmer la suppression
                    </h5>
                    <button type="button" class="btn-close-modern" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="modal-body-modern">
                    <div id="deleteItemInfo"></div>
                    <p class="mb-0 text-muted">Cette action est irréversible.</p>
                </div>
                <div class="modal-footer-modern">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Annuler
                    </button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn">
                        <i class="fas fa-trash me-2"></i>Supprimer
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
   <script>
    // ==================== CONFIGURATION ====================
    let currentPage = 1;
    let currentFilters = {};
    let allRoles = [];
    let allPermissions = [];
    let roleToDelete = null;
    let permissionToDelete = null;

    // ==================== INITIALISATION ====================
    document.addEventListener('DOMContentLoaded', function() {
        setupAjax();
        loadRoles();
        loadPermissions();
        setupEventListeners();
    });

    // ==================== AJAX SETUP ====================
    const setupAjax = () => {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
    };

    // ==================== RÔLES ====================

    // Load roles
    const loadRoles = (page = 1, filters = {}) => {
        showLoading();
        
        const searchTerm = document.getElementById('searchInput')?.value || '';
        
        $.ajax({
            url: '{{ route("roles.index") }}',
            type: 'GET',
            data: {
                page: page,
                search: searchTerm,
                ...filters,
                ajax: true
            },
            success: function(response) {
                if (response.success) {
                    allRoles = response.data || [];
                    renderRoles(allRoles);
                    renderPagination(response);
                    hideLoading();
                } else {
                    showError('Erreur lors du chargement des rôles');
                }
            },
            error: function(xhr) {
                hideLoading();
                showError('Erreur de connexion au serveur');
                console.error('Error:', xhr.responseText);
            }
        });
    };

    // Render roles
    const renderRoles = (roles) => {
        const tbody = document.getElementById('rolesTableBody');
        tbody.innerHTML = '';
        
        if (!roles || !Array.isArray(roles) || roles.length === 0) {
            document.getElementById('emptyState').style.display = 'block';
            document.getElementById('tableContainer').style.display = 'none';
            document.getElementById('paginationContainer').style.display = 'none';
            return;
        }
        
        roles.forEach((role, index) => {
            const row = document.createElement('tr');
            row.id = `role-row-${role.id}`;
            row.style.animationDelay = `${index * 0.05}s`;
            
            const permissionsCount = role.permissions ? role.permissions.length : 0;
            const permissionsList = role.permissions ? 
                role.permissions.slice(0, 3).map(p => 
                    `<span class="permission-badge">${p.name}</span>`
                ).join('') : '';
            
            const morePermissions = permissionsCount > 3 ? 
                `<span class="permission-badge more">+${permissionsCount - 3}</span>` : '';
            
            row.innerHTML = `
                <td><span class="badge-id">#${role.id}</span></td>
                <td class="role-name-cell">
                    <div class="role-name-modern">
                        <div class="role-avatar-modern">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <div>
                            <div class="role-name-text">${role.name}</div>
                            <small class="text-muted">${role.guard_name || 'web'}</small>
                        </div>
                    </div>
                </td>
                <td>
                    <span class="guard-badge ${role.guard_name === 'api' ? 'guard-api' : 'guard-web'}">
                        <i class="fas fa-${role.guard_name === 'api' ? 'cloud' : 'globe'} me-1"></i>
                        ${role.guard_name || 'web'}
                    </span>
                </td>
                <td>
                    <div class="permissions-badges">
                        ${permissionsList}
                        ${morePermissions}
                    </div>
                </td>
                <td>
                    <div class="date-info">
                        <i class="far fa-calendar-alt me-1"></i>
                        ${new Date(role.created_at).toLocaleDateString('fr-FR')}
                    </div>
                </td>
                <td>
                    <div class="role-actions-modern">
                        <button class="action-btn-modern edit-btn-modern" title="Modifier" 
                                onclick="openEditModal(${role.id})">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="action-btn-modern copy-btn-modern" title="Dupliquer" 
                                onclick="duplicateRole(${role.id})">
                            <i class="fas fa-copy"></i>
                        </button>
                        <button class="action-btn-modern delete-btn-modern" title="Supprimer" 
                                onclick="showDeleteConfirmation(${role.id})">
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

    // Create role
    const createRole = (e) => {
        e.preventDefault();
        
        const form = document.getElementById('createRoleForm');
        const submitBtn = document.getElementById('submitRoleBtn');
        
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }
        
        // Show processing animation
        submitBtn.classList.add('btn-processing');
        submitBtn.innerHTML = `
            <span class="btn-text" style="display: none;">
                <i class="fas fa-save me-2"></i>Créer le rôle
            </span>
            <div class="spinner-border spinner-border-sm text-light" role="status">
                <span class="visually-hidden">Chargement...</span>
            </div>
            Création en cours...
        `;
        submitBtn.disabled = true;
        
        const formData = new FormData(form);
        
        // Collect selected permissions
        const selectedPermissions = Array.from(document.querySelectorAll('#permissionsContainer .permission-input:checked'))
            .map(cb => cb.value);
        
        selectedPermissions.forEach(permId => {
            formData.append('permissions[]', permId);
        });
        
        $.ajax({
            url: '{{ route("roles.store") }}',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                // Reset button state
                submitBtn.classList.remove('btn-processing');
                submitBtn.innerHTML = `
                    <span class="btn-text">
                        <i class="fas fa-save me-2"></i>Créer le rôle
                    </span>
                `;
                submitBtn.disabled = false;
                
                if (response.success) {
                    // Close modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('createRoleModal'));
                    modal.hide();
                    
                    // Reset form
                    form.reset();
                    
                    // Reload roles
                    loadRoles(1, currentFilters);
                    
                    // Show success message
                    showAlert('success', 'Rôle créé avec succès !');
                } else {
                    showAlert('danger', response.message || 'Erreur lors de la création');
                }
            },
            error: function(xhr) {
                // Reset button state
                submitBtn.classList.remove('btn-processing');
                submitBtn.innerHTML = `
                    <span class="btn-text">
                        <i class="fas fa-save me-2"></i>Créer le rôle
                    </span>
                `;
                submitBtn.disabled = false;
                
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    let errorMessage = 'Erreurs de validation:<br>';
                    for (const field in errors) {
                        errorMessage += `- ${errors[field].join('<br>')}<br>`;
                    }
                    showAlert('danger', errorMessage);
                } else {
                    showAlert('danger', xhr.responseJSON?.message || 'Erreur lors de la création');
                }
            }
        });
    };

    // Open edit modal
    const openEditModal = (roleId) => {
        const role = allRoles.find(r => r.id === roleId);
        
        if (!role) {
            showAlert('danger', 'Rôle non trouvé');
            return;
        }
        
        document.getElementById('editRoleId').value = role.id;
        document.getElementById('editRoleName').value = role.name;
        document.getElementById('editRoleGuard').value = role.guard_name || 'web';
        
        const selectedPermissions = role.permissions ? role.permissions.map(p => p.id) : [];
        renderPermissionsCheckboxes('editPermissionsContainer', selectedPermissions);
        
        new bootstrap.Modal(document.getElementById('editRoleModal')).show();
    };

    // Update role
    const updateRole = (e) => {
        e.preventDefault();
        
        const form = document.getElementById('editRoleForm');
        const submitBtn = document.getElementById('updateRoleBtn');
        const roleId = document.getElementById('editRoleId').value;
        
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }
        
        // Show processing animation
        submitBtn.classList.add('btn-processing');
        submitBtn.innerHTML = `
            <span class="btn-text" style="display: none;">
                <i class="fas fa-save me-2"></i>Mettre à jour
            </span>
            <div class="spinner-border spinner-border-sm text-light" role="status">
                <span class="visually-hidden">Chargement...</span>
            </div>
            Mise à jour...
        `;
        submitBtn.disabled = true;
        
        const formData = new FormData(form);
        
        // Collect selected permissions
        const selectedPermissions = Array.from(document.querySelectorAll('#editPermissionsContainer .permission-input:checked'))
            .map(cb => cb.value);
        
        selectedPermissions.forEach(permId => {
            formData.append('permissions[]', permId);
        });
        
        formData.append('_method', 'PUT');
        
        $.ajax({
            url: `/roles/${roleId}`,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                // Reset button state
                submitBtn.classList.remove('btn-processing');
                submitBtn.innerHTML = `
                    <span class="btn-text">
                        <i class="fas fa-save me-2"></i>Mettre à jour
                    </span>
                `;
                submitBtn.disabled = false;
                
                if (response.success) {
                    // Close modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('editRoleModal'));
                    modal.hide();
                    
                    // Reload roles
                    loadRoles(currentPage, currentFilters);
                    
                    // Show success message
                    showAlert('success', 'Rôle mis à jour avec succès !');
                } else {
                    showAlert('danger', response.message || 'Erreur lors de la mise à jour');
                }
            },
            error: function(xhr) {
                // Reset button state
                submitBtn.classList.remove('btn-processing');
                submitBtn.innerHTML = `
                    <span class="btn-text">
                        <i class="fas fa-save me-2"></i>Mettre à jour
                    </span>
                `;
                submitBtn.disabled = false;
                
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    let errorMessage = 'Erreurs de validation:<br>';
                    for (const field in errors) {
                        errorMessage += `- ${errors[field].join('<br>')}<br>`;
                    }
                    showAlert('danger', errorMessage);
                } else {
                    showAlert('danger', xhr.responseJSON?.message || 'Erreur lors de la mise à jour');
                }
            }
        });
    };

    // Duplicate role
    const duplicateRole = (roleId) => {
        const role = allRoles.find(r => r.id === roleId);
        
        if (!role) {
            showAlert('danger', 'Rôle non trouvé');
            return;
        }
        
        document.getElementById('roleName').value = `${role.name}_copy`;
        document.getElementById('roleGuard').value = role.guard_name || 'web';
        
        const selectedPermissions = role.permissions ? role.permissions.map(p => p.id) : [];
        renderPermissionsCheckboxes('permissionsContainer', selectedPermissions);
        
        new bootstrap.Modal(document.getElementById('createRoleModal')).show();
    };

    // ==================== PERMISSIONS ====================

    // Load permissions
    const loadPermissions = () => {
        $.ajax({
            url: '{{ route("permissions.index") }}',
            type: 'GET',
            data: { ajax: true },
            success: function(response) {
                if (response.success) {
                    allPermissions = response.data || [];
                    renderPermissions(allPermissions);
                    renderPermissionsCheckboxes('permissionsContainer');
                } else {
                    showError('Erreur lors du chargement des permissions');
                }
            },
            error: function(xhr) {
                console.error('Error loading permissions:', xhr);
                showError('Erreur lors du chargement des permissions');
            }
        });
    };

    // Render permissions
    const renderPermissions = (permissions) => {
        const tbody = document.getElementById('permissionsTableBody');
        if (!tbody) return;
        
        tbody.innerHTML = '';
        
        if (!permissions || permissions.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="6" class="text-center text-muted py-4">
                        <i class="fas fa-key fa-2x mb-2"></i>
                        <p>Aucune permission trouvée</p>
                    </td>
                </tr>
            `;
            return;
        }
        
        // Group permissions by group
        const groupedPermissions = permissions.reduce((acc, permission) => {
            const group = permission.group || 'Autres';
            if (!acc[group]) acc[group] = [];
            acc[group].push(permission);
            return acc;
        }, {});
        
        // Sort groups
        const sortedGroups = Object.keys(groupedPermissions).sort();
        
        sortedGroups.forEach(group => {
            // Add group header
            const groupHeader = document.createElement('tr');
            groupHeader.className = 'group-header';
            groupHeader.innerHTML = `
                <td colspan="6">
                    <div class="group-title">
                        <i class="fas fa-folder-open me-2"></i>
                        ${group}
                    </div>
                </td>
            `;
            tbody.appendChild(groupHeader);
            
            // Add permissions for this group
            groupedPermissions[group].forEach((permission) => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td><span class="badge-id">#${permission.id}</span></td>
                    <td>
                        <div class="permission-name">
                            <i class="fas fa-key permission-icon"></i>
                            ${permission.name}
                        </div>
                    </td>
                    <td>
                        <span class="guard-badge ${permission.guard_name === 'api' ? 'guard-api' : 'guard-web'}">
                            ${permission.guard_name || 'web'}
                        </span>
                    </td>
                    <td>
                        <span class="group-badge">${group}</span>
                    </td>
                    <td>
                        <div class="date-info">
                            <i class="far fa-calendar-alt me-1"></i>
                            ${new Date(permission.created_at).toLocaleDateString('fr-FR')}
                        </div>
                    </td>
                    <td>
                        <div class="role-actions-modern">
                            <button class="action-btn-modern delete-btn-modern" title="Supprimer" 
                                    onclick="showPermissionDeleteConfirmation(${permission.id}, '${permission.name}')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                `;
                tbody.appendChild(row);
            });
        });
    };

    // Render permissions checkboxes
    const renderPermissionsCheckboxes = (containerId, selectedPermissions = []) => {
        const container = document.getElementById(containerId);
        if (!container) return;
        
        if (!allPermissions || allPermissions.length === 0) {
            container.innerHTML = '<p class="text-muted text-center py-3">Aucune permission disponible</p>';
            return;
        }
        
        // Group permissions
        const groupedPermissions = allPermissions.reduce((acc, permission) => {
            const group = permission.group || 'Autres';
            if (!acc[group]) acc[group] = [];
            acc[group].push(permission);
            return acc;
        }, {});
        
        let html = '';
        
        // Sort groups
        const sortedGroups = Object.keys(groupedPermissions).sort();
        
        sortedGroups.forEach(group => {
            html += `
                <div class="permission-group">
                    <div class="permission-group-header" onclick="toggleGroup('${containerId}_${group}')">
                        <i class="fas fa-chevron-right me-2"></i>
                        <strong>${group}</strong>
                        <span class="group-count">(${groupedPermissions[group].length})</span>
                    </div>
                    <div class="permission-group-body" id="${containerId}_${group}">
            `;
            
            groupedPermissions[group].forEach(permission => {
                const checked = selectedPermissions.includes(permission.id) ? 'checked' : '';
                html += `
                    <div class="form-check permission-checkbox">
                        <input class="form-check-input permission-input" type="checkbox" 
                               name="permissions[]" value="${permission.id}" 
                               id="perm_${containerId}_${permission.id}" ${checked}>
                        <label class="form-check-label" for="perm_${containerId}_${permission.id}">
                            ${permission.name}
                        </label>
                    </div>
                `;
            });
            
            html += '</div></div>';
        });
        
        container.innerHTML = html;
    };

    // Toggle permission group
    const toggleGroup = (groupId) => {
        const group = document.getElementById(groupId);
        const header = group.previousElementSibling;
        const icon = header.querySelector('i');
        
        if (group.style.display === 'none' || !group.style.display) {
            group.style.display = 'block';
            icon.className = 'fas fa-chevron-down me-2';
        } else {
            group.style.display = 'none';
            icon.className = 'fas fa-chevron-right me-2';
        }
    };

    // Create permission
    const createPermission = (e) => {
        e.preventDefault();
        
        const form = document.getElementById('createPermissionForm');
        const submitBtn = document.getElementById('submitPermissionBtn');
        
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }
        
        // Show processing animation
        submitBtn.classList.add('btn-processing');
        submitBtn.innerHTML = `
            <span class="btn-text" style="display: none;">
                <i class="fas fa-save me-2"></i>Créer
            </span>
            <div class="spinner-border spinner-border-sm text-light" role="status">
                <span class="visually-hidden">Chargement...</span>
            </div>
            Création...
        `;
        submitBtn.disabled = true;
        
        const formData = new FormData(form);
        
        $.ajax({
            url: '{{ route("permissions.store") }}',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                // Reset button state
                submitBtn.classList.remove('btn-processing');
                submitBtn.innerHTML = `
                    <span class="btn-text">
                        <i class="fas fa-save me-2"></i>Créer
                    </span>
                `;
                submitBtn.disabled = false;
                
                if (response.success) {
                    // Close modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('createPermissionModal'));
                    modal.hide();
                    
                    // Reset form
                    form.reset();
                    
                    // Reload permissions
                    loadPermissions();
                    
                    // Show success message
                    showAlert('success', 'Permission créée avec succès !');
                } else {
                    showAlert('danger', response.message || 'Erreur lors de la création');
                }
            },
            error: function(xhr) {
                // Reset button state
                submitBtn.classList.remove('btn-processing');
                submitBtn.innerHTML = `
                    <span class="btn-text">
                        <i class="fas fa-save me-2"></i>Créer
                    </span>
                `;
                submitBtn.disabled = false;
                
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    let errorMessage = 'Erreurs de validation:<br>';
                    for (const field in errors) {
                        errorMessage += `- ${errors[field].join('<br>')}<br>`;
                    }
                    showAlert('danger', errorMessage);
                } else {
                    showAlert('danger', xhr.responseJSON?.message || 'Erreur lors de la création');
                }
            }
        });
    };

    // ==================== SUPPRESSION RÔLE ====================

    // Show delete confirmation for role
    const showDeleteConfirmation = (roleId) => {
        const role = allRoles.find(r => r.id === roleId);
        
        if (!role) {
            showAlert('danger', 'Rôle non trouvé');
            return;
        }
        
        roleToDelete = role;
        
        document.getElementById('deleteItemInfo').innerHTML = `
            <div class="user-info">
                <div class="user-info-avatar">
                    <i class="fas fa-shield-alt fa-2x"></i>
                </div>
                <div>
                    <div class="user-info-name">${role.name}</div>
                    <div class="user-info-email">Guard: ${role.guard_name || 'web'}</div>
                    <div class="user-info-roles">Permissions: ${role.permissions ? role.permissions.length : 0}</div>
                </div>
            </div>
            <div class="row small text-muted mt-3">
                <div class="col-6">
                    <div><strong>ID:</strong> ${role.id}</div>
                    <div><strong>Créé le:</strong> ${new Date(role.created_at).toLocaleDateString()}</div>
                </div>
                <div class="col-6">
                    <div><strong>Dernière modification:</strong> ${new Date(role.updated_at).toLocaleDateString()}</div>
                    <div><strong>Utilisateurs:</strong> ${role.users_count || 0}</div>
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
        deleteBtn.onclick = deleteRole;
        
        // Show modal
        const deleteModal = new bootstrap.Modal(document.getElementById('deleteConfirmationModal'));
        deleteModal.show();
    };

    // Delete role
    const deleteRole = () => {
        if (!roleToDelete) {
            showAlert('danger', 'Aucun rôle à supprimer');
            return;
        }
        
        const roleId = roleToDelete.id;
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
        const row = document.getElementById(`role-row-${roleId}`);
        if (row) {
            row.classList.add('deleting-row');
        }
        
        $.ajax({
            url: `/roles/${roleId}`,
            type: 'DELETE',
            success: function(response) {
                // Hide modal
                const deleteModal = bootstrap.Modal.getInstance(document.getElementById('deleteConfirmationModal'));
                deleteModal.hide();
                
                if (response.success) {
                    // Remove role from array
                    allRoles = allRoles.filter(r => r.id !== roleId);
                    
                    // Show success message
                    showAlert('success', response.message || 'Rôle supprimé avec succès !');
                    
                    // Remove row after animation
                    if (row) {
                        setTimeout(() => {
                            row.remove();
                            
                            // Check if table is now empty
                            const tbody = document.getElementById('rolesTableBody');
                            if (tbody.children.length === 0) {
                                document.getElementById('emptyState').style.display = 'block';
                                document.getElementById('tableContainer').style.display = 'none';
                                document.getElementById('paginationContainer').style.display = 'none';
                            }
                        }, 300);
                    } else {
                        // Reload table
                        setTimeout(() => {
                            loadRoles(currentPage, currentFilters);
                        }, 500);
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
                
                if (xhr.status === 404) {
                    showAlert('danger', 'Rôle non trouvé.');
                    loadRoles(currentPage, currentFilters);
                } else {
                    showAlert('danger', xhr.responseJSON?.message || 'Erreur lors de la suppression');
                }
            },
            complete: function() {
                roleToDelete = null;
            }
        });
    };

    // ==================== SUPPRESSION PERMISSION ====================

    // Show delete confirmation for permission
    const showPermissionDeleteConfirmation = (permissionId, permissionName) => {
        permissionToDelete = { id: permissionId, name: permissionName };
        
        document.getElementById('deleteItemInfo').innerHTML = `
            <div class="alert alert-warning">
                <i class="fas fa-info-circle me-2"></i>
                Êtes-vous sûr de vouloir supprimer la permission 
                <strong>"${permissionName}"</strong> ?
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
        deleteBtn.onclick = deletePermission;
        
        // Show modal
        const deleteModal = new bootstrap.Modal(document.getElementById('deleteConfirmationModal'));
        deleteModal.show();
    };

    // Delete permission
    const deletePermission = () => {
        if (!permissionToDelete) {
            showAlert('danger', 'Aucune permission à supprimer');
            return;
        }
        
        const permissionId = permissionToDelete.id;
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
        const row = document.getElementById(`permission-row-${permissionId}`);
        if (row) {
            row.classList.add('deleting-row');
        }
        
        $.ajax({
            url: `/permissions/${permissionId}`,
            type: 'DELETE',
            success: function(response) {
                // Hide modal
                const deleteModal = bootstrap.Modal.getInstance(document.getElementById('deleteConfirmationModal'));
                deleteModal.hide();
                
                if (response.success) {
                    // Show success message
                    showAlert('success', response.message || 'Permission supprimée avec succès !');
                    
                    // Reload permissions
                    loadPermissions();
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
                
                if (xhr.status === 404) {
                    showAlert('danger', 'Permission non trouvée.');
                    loadPermissions();
                } else {
                    showAlert('danger', xhr.responseJSON?.message || 'Erreur lors de la suppression');
                }
            },
            complete: function() {
                permissionToDelete = null;
            }
        });
    };

    // ==================== PAGINATION ====================

    // Render pagination
    const renderPagination = (response) => {
        const pagination = document.getElementById('pagination');
        const paginationInfo = document.getElementById('paginationInfo');
        
        if (!pagination || !paginationInfo) return;
        
        const start = (response.current_page - 1) * response.per_page + 1;
        const end = Math.min(response.current_page * response.per_page, response.total);
        paginationInfo.textContent = `Affichage de ${start} à ${end} sur ${response.total} rôles`;
        
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
        loadRoles(page, currentFilters);
    };

    // ==================== UTILITAIRES ====================

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

    // ==================== EVENT LISTENERS ====================

    const setupEventListeners = () => {
        // Search input with debounce
        const searchInput = document.getElementById('searchInput');
        let searchTimeout;
        
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    loadRoles(1, currentFilters);
                }, 500);
            });
        }
        
        // Create role form
        const createRoleForm = document.getElementById('createRoleForm');
        if (createRoleForm) {
            createRoleForm.addEventListener('submit', createRole);
        }
        
        // Edit role form
        const editRoleForm = document.getElementById('editRoleForm');
        if (editRoleForm) {
            editRoleForm.addEventListener('submit', updateRole);
        }
        
        // Create permission form
        const createPermissionForm = document.getElementById('createPermissionForm');
        if (createPermissionForm) {
            createPermissionForm.addEventListener('submit', createPermission);
        }
        
        // Reset delete modal when hidden
        const deleteModal = document.getElementById('deleteConfirmationModal');
        if (deleteModal) {
            deleteModal.addEventListener('hidden.bs.modal', function() {
                roleToDelete = null;
                permissionToDelete = null;
                const deleteBtn = document.getElementById('confirmDeleteBtn');
                deleteBtn.innerHTML = `
                    <span class="btn-text">
                        <i class="fas fa-trash me-2"></i>Supprimer définitivement
                    </span>
                `;
                deleteBtn.disabled = false;
            });
        }
        
        // Reset create role modal when hidden
        const createRoleModal = document.getElementById('createRoleModal');
        if (createRoleModal) {
            createRoleModal.addEventListener('hidden.bs.modal', function() {
                document.getElementById('createRoleForm').reset();
                const submitBtn = document.getElementById('submitRoleBtn');
                submitBtn.classList.remove('btn-processing');
                submitBtn.innerHTML = `
                    <span class="btn-text">
                        <i class="fas fa-save me-2"></i>Créer le rôle
                    </span>
                `;
                submitBtn.disabled = false;
            });
        }
        
        // Reset edit role modal when hidden
        const editRoleModal = document.getElementById('editRoleModal');
        if (editRoleModal) {
            editRoleModal.addEventListener('hidden.bs.modal', function() {
                const submitBtn = document.getElementById('updateRoleBtn');
                submitBtn.classList.remove('btn-processing');
                submitBtn.innerHTML = `
                    <span class="btn-text">
                        <i class="fas fa-save me-2"></i>Mettre à jour
                    </span>
                `;
                submitBtn.disabled = false;
            });
        }
        
        // Reset create permission modal when hidden
        const createPermissionModal = document.getElementById('createPermissionModal');
        if (createPermissionModal) {
            createPermissionModal.addEventListener('hidden.bs.modal', function() {
                document.getElementById('createPermissionForm').reset();
                const submitBtn = document.getElementById('submitPermissionBtn');
                submitBtn.classList.remove('btn-processing');
                submitBtn.innerHTML = `
                    <span class="btn-text">
                        <i class="fas fa-save me-2"></i>Créer
                    </span>
                `;
                submitBtn.disabled = false;
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
                    guard: document.getElementById('filterGuard').value,
                    sort_by: document.getElementById('filterSortBy').value,
                    sort_direction: document.getElementById('filterSortDirection').value
                };
                loadRoles(1, currentFilters);
            });
        }
        
        // Clear filters
        const clearFiltersBtn = document.getElementById('clearFiltersBtn');
        if (clearFiltersBtn) {
            clearFiltersBtn.addEventListener('click', () => {
                document.getElementById('filterGuard').value = '';
                document.getElementById('filterSortBy').value = 'name';
                document.getElementById('filterSortDirection').value = 'asc';
                currentFilters = {};
                loadRoles(1);
            });
        }
    };
</script>
    // Ajoutez ceci APRÈS l'inclusion de Bootstrap
<script>
    // DÉSACTIVER COMPLÈTEMENT ARIA-HIDDEN
    (function() {
        // Supprimer aria-hidden de tous les éléments au chargement
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('[aria-hidden="true"]').forEach(el => {
                el.removeAttribute('aria-hidden');
            });
        });

        // Observer les changements dans le DOM
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.type === 'attributes' && mutation.attributeName === 'aria-hidden') {
                    mutation.target.removeAttribute('aria-hidden');
                }
            });
        });

        observer.observe(document.body, {
            attributes: true,
            subtree: true,
            attributeFilter: ['aria-hidden']
        });

        // Nettoyage toutes les 100ms
        setInterval(function() {
            document.querySelectorAll('[aria-hidden="true"]').forEach(el => {
                el.removeAttribute('aria-hidden');
            });
        }, 100);
    })();
</script>
    <style>
        /* Styles spécifiques pour les rôles et permissions */
        .role-name-modern {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .role-avatar-modern {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #8e44ad, #9b59b6);
            color: white;
            font-size: 1.2rem;
        }

        .role-name-text {
            font-weight: 600;
            color: var(--text-color);
            margin-bottom: 2px;
            text-transform: capitalize;
        }

        .guard-badge {
            display: inline-flex;
            align-items: center;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .guard-web {
            background: linear-gradient(135deg, #3498db, #2980b9);
            color: white;
        }

        .guard-api {
            background: linear-gradient(135deg, #e67e22, #d35400);
            color: white;
        }

        .permissions-badges {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
        }

        .permission-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 15px;
            background: #f0f7ff;
            color: #3a56e4;
            font-size: 0.75rem;
            font-weight: 500;
            border: 1px solid #d1e3ff;
        }

        .permission-badge.more {
            background: #e9ecef;
            color: #6c757d;
            border-color: #dee2e6;
        }

        .badge-id {
            display: inline-block;
            padding: 3px 8px;
            background: #e9ecef;
            color: #495057;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 600;
        }

        .date-info {
            font-size: 0.8rem;
            color: #6c757d;
        }

        .role-actions-modern {
            display: flex;
            gap: 6px;
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
        }

        .copy-btn-modern {
            background: linear-gradient(135deg, #f1c40f, #f39c12);
            color: #333;
        }

        .copy-btn-modern:hover {
            background: linear-gradient(135deg, #f39c12, #e67e22);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(241, 196, 15, 0.3);
        }

        .edit-btn-modern {
            background: linear-gradient(135deg, #3498db, #2980b9);
            color: white;
        }

        .edit-btn-modern:hover {
            background: linear-gradient(135deg, #2980b9, #1f618d);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(52, 152, 219, 0.3);
        }

        .delete-btn-modern {
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            color: white;
        }

        .delete-btn-modern:hover {
            background: linear-gradient(135deg, #c0392b, #a93226);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(231, 76, 60, 0.3);
        }

        /* Permissions container */
        .permissions-container {
            max-height: 400px;
            overflow-y: auto;
            border: 1px solid #e9ecef;
            border-radius: 10px;
            padding: 15px;
        }

        .permission-group {
            margin-bottom: 20px;
        }

        .permission-group-header {
            padding: 10px 15px;
            background: #f8f9fa;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            color: #495057;
            display: flex;
            align-items: center;
            transition: all 0.3s ease;
        }

        .permission-group-header:hover {
            background: #e9ecef;
        }

        .group-count {
            margin-left: 10px;
            color: #6c757d;
            font-size: 0.85rem;
        }

        .permission-group-body {
            padding: 10px 15px 10px 35px;
            display: none;
        }

        .permission-group-body.show {
            display: block;
        }

        .permission-checkbox {
            margin: 8px 0;
            padding: 5px 10px;
            border-radius: 6px;
            transition: background 0.3s ease;
        }

        .permission-checkbox:hover {
            background: #f8f9fa;
        }

        .permission-input {
            cursor: pointer;
        }

        .permission-checkbox .form-check-label {
            cursor: pointer;
            font-size: 0.9rem;
            color: #495057;
        }

        /* Group header in permissions table */
        .group-header {
            background: #f8f9fa;
        }

        .group-header td {
            padding: 10px 15px !important;
            font-weight: 600;
            color: #495057;
            border-top: 2px solid #e9ecef;
        }

        .group-title {
            display: flex;
            align-items: center;
            color: #2c3e50;
        }

        .permission-name {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .permission-icon {
            color: #f1c40f;
            font-size: 0.9rem;
        }

        .group-badge {
            display: inline-block;
            padding: 3px 8px;
            background: #e9ecef;
            color: #6c757d;
            border-radius: 15px;
            font-size: 0.7rem;
            font-weight: 500;
        }

        /* Modal styles */
        .modal-content-modern {
            background: white;
            border-radius: 20px;
            border: none;
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
        }

        .modal-header-modern {
            padding: 20px 30px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .modal-title-modern {
            font-size: 1.3rem;
            font-weight: 600;
            color: #2c3e50;
            margin: 0;
        }

        .btn-close-modern {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: none;
            background: #f8f9fa;
            color: #6c757d;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .btn-close-modern:hover {
            background: #e9ecef;
            color: #495057;
            transform: rotate(90deg);
        }

        .modal-body-modern {
            padding: 30px;
        }

        .modal-footer-modern {
            padding: 20px 30px;
            border-top: 1px solid #e9ecef;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .role-actions-modern {
                flex-direction: column;
                gap: 5px;
            }
            
            .action-btn-modern {
                width: 100%;
                height: 36px;
            }
            
            .permissions-badges {
                flex-direction: column;
                gap: 3px;
            }
            
            .permission-badge {
                width: fit-content;
            }
            
            .role-name-modern {
                flex-direction: column;
                align-items: flex-start;
                gap: 8px;
            }
            
            .modal-body-modern {
                padding: 20px;
            }
        }
        /* Correction pour les problèmes d'aria-hidden */
.modal.fade.show {
    pointer-events: auto;
    z-index: 1050;
}

.modal-backdrop {
    z-index: 1040;
}

.modal-open {
    overflow: hidden;
    padding-right: 0 !important;
}

.modal-open .modal {
    overflow-x: hidden;
    overflow-y: auto;
}

/* Éviter les conflits d'aria-hidden */
[aria-hidden="true"] {
    pointer-events: none !important;
}

[aria-hidden="true"]:focus,
[aria-hidden="true"] *:focus {
    outline: none !important;
}
/* SOLUTION DÉFINITIVE POUR ARIA-HIDDEN */
.modal {
    pointer-events: auto !important;
    visibility: visible !important;
}

.modal[aria-hidden="true"] {
    display: none !important;
    pointer-events: none !important;
}

.modal-backdrop {
    display: none !important;
}

.modal-open {
    overflow: auto !important;
    padding-right: 0 !important;
}

/* Forcer l'affichage des modals */
.modal.show {
    display: block !important;
    background: rgba(0,0,0,0.5);
}

/* Cacher proprement les modals fermées */
.modal:not(.show) {
    display: none !important;
}
    </style>
@endsection