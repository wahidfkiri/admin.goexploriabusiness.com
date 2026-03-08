@extends('layouts.app')

@section('content')
    <!-- MAIN CONTENT -->
    <main class="dashboard-content">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">
                <span class="page-title-icon"><i class="fas fa-users-cog"></i></span>
                Gestion des Utilisateurs
            </h1>
            
            <div class="page-actions">
                <button class="btn btn-outline-secondary" id="toggleFilterBtn">
                    <i class="fas fa-sliders-h me-2"></i>Filtres
                </button>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createUserModal">
                    <i class="fas fa-user-plus me-2"></i>Nouvel Utilisateur
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
                    <label for="filterRole" class="form-label-modern">Rôle</label>
                    <select class="form-select-modern" id="filterRole">
                        <option value="">Tous les rôles</option>
                        @foreach($roles ?? [] as $role)
                            <option value="{{ $role->name }}">{{ $role->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="filterStatus" class="form-label-modern">Statut</label>
                    <select class="form-select-modern" id="filterStatus">
                        <option value="">Tous les statuts</option>
                        <option value="active">Actifs</option>
                        <option value="inactive">Inactifs</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="filterSortBy" class="form-label-modern">Trier par</label>
                    <select class="form-select-modern" id="filterSortBy">
                        <option value="name">Nom</option>
                        <option value="email">Email</option>
                        <option value="created_at">Date de création</option>
                        <option value="updated_at">Dernière modification</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="filterSortDirection" class="form-label-modern">Ordre</label>
                    <select class="form-select-modern" id="filterSortDirection">
                        <option value="asc">Croissant</option>
                        <option value="desc">Décroissant</option>
                    </select>
                </div>
            </div>
        </div>
        
        <!-- Bulk Actions -->
        <div class="bulk-actions-modern" id="bulkActions" style="display: none;">
            <div class="bulk-actions-content">
                <span id="selectedCount">0 utilisateur(s) sélectionné(s)</span>
                <div class="bulk-actions-buttons">
                    <select class="form-select-modern bulk-select" id="bulkActionSelect">
                        <option value="">Actions groupées...</option>
                        <option value="activate">Activer</option>
                        <option value="deactivate">Désactiver</option>
                        <option value="delete">Supprimer</option>
                    </select>
                    <button class="btn btn-sm btn-primary" id="applyBulkActionBtn">
                        <i class="fas fa-play me-1"></i>Appliquer
                    </button>
                    <button class="btn btn-sm btn-outline-secondary" id="clearSelectionBtn">
                        <i class="fas fa-times me-1"></i>Effacer la sélection
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Main Card - Modern Design -->
        <div class="main-card-modern">
            <div class="card-header-modern">
                <h3 class="card-title-modern">Liste des Utilisateurs</h3>
                <div class="search-container">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" class="search-input" placeholder="Rechercher un utilisateur..." id="searchInput">
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
                                <th style="width: 50px;">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="selectAllCheckbox">
                                    </div>
                                </th>
                                <th>Utilisateur</th>
                                <th>Email</th>
                                <th>Rôles</th>
                                <th>Statut</th>
                                <th style="text-align: center;">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="usersTableBody">
                            <!-- Users will be loaded here via AJAX -->
                        </tbody>
                    </table>
                </div>
                
                <!-- Empty State -->
                <div class="empty-state-modern" id="emptyState" style="display: none;">
                    <div class="empty-icon-modern">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3 class="empty-title-modern">Aucun utilisateur trouvé</h3>
                    <p class="empty-text-modern">Commencez par créer votre premier utilisateur.</p>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createUserModal">
                        <i class="fas fa-user-plus me-2"></i>Créer un utilisateur
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
        
        <!-- Advanced Stats Section -->
        <div class="advanced-stats-section" id="advancedStatsSection" style="display: none;">
            <div class="section-header-modern">
                <h3 class="section-title-modern">
                    <i class="fas fa-chart-bar me-2"></i>
                    Statistiques Utilisateurs
                </h3>
                <button class="btn btn-sm btn-outline-secondary" id="refreshStatsBtn">
                    <i class="fas fa-sync-alt me-1"></i>Actualiser
                </button>
            </div>
            
            <div id="advancedStats" class="advanced-stats-grid">
                <!-- Advanced stats will be loaded here -->
            </div>
        </div>
        
        <!-- Floating Action Button -->
        <button class="fab-modern" data-bs-toggle="modal" data-bs-target="#createUserModal">
            <i class="fas fa-user-plus"></i>
        </button>
    </main>
    
    <!-- Modals -->
    @include('users::users.create-modal')
    @include('users::users.edit-modal')
    @include('users::users.delete-modal')
    @include('users::users.roles-modal')

    <!-- JavaScript -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Configuration pour les utilisateurs
        let currentPage = 1;
        let currentFilters = {};
        let allUsers = [];
        let selectedUsers = new Set();
        let userToDelete = null;

        // Initialisation
        document.addEventListener('DOMContentLoaded', function() {
            setupAjax();
            loadUsers();
            setupEventListeners();
            loadStatistics();
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

        // Load users
        const loadUsers = (page = 1, filters = {}) => {
            showLoading();
            
            const searchTerm = document.getElementById('searchInput')?.value || '';
            
            $.ajax({
                url: '{{ route("users.index") }}',
                type: 'GET',
                data: {
                    page: page,
                    search: searchTerm,
                    ...filters,
                    ajax: true
                },
                success: function(response) {
                    if (response.success) {
                        allUsers = response.data || [];
                        renderUsers(allUsers);
                        renderPagination(response);
                        hideLoading();
                        
                        // Show stats section if we have users
                        if (allUsers.length > 0) {
                            document.getElementById('advancedStatsSection').style.display = 'block';
                        }
                    } else {
                        showError('Erreur lors du chargement des utilisateurs');
                    }
                },
                error: function(xhr) {
                    hideLoading();
                    showError('Erreur de connexion au serveur');
                    console.error('Error:', xhr.responseText);
                }
            });
        };

        // Render users
        const renderUsers = (users) => {
            const tbody = document.getElementById('usersTableBody');
            tbody.innerHTML = '';
            
            if (!users || !Array.isArray(users) || users.length === 0) {
                document.getElementById('emptyState').style.display = 'block';
                document.getElementById('tableContainer').style.display = 'none';
                document.getElementById('paginationContainer').style.display = 'none';
                document.getElementById('bulkActions').style.display = 'none';
                document.getElementById('advancedStatsSection').style.display = 'none';
                return;
            }
            
            users.forEach((user, index) => {
                const row = document.createElement('tr');
                row.id = `user-row-${user.id}`;
                row.style.animationDelay = `${index * 0.05}s`;
                
                const isSelected = selectedUsers.has(user.id);
                const statusClass = user.is_active ? 'status-active' : 'status-inactive';
                const statusText = user.is_active ? 'Actif' : 'Inactif';
                const roles = user.roles ? user.roles.map(r => r.name).join(', ') : 'Aucun rôle';
                
                row.innerHTML = `
                    <td>
                        <div class="form-check">
                            <input class="form-check-input row-checkbox" type="checkbox" 
                                   value="${user.id}" ${isSelected ? 'checked' : ''}
                                   onchange="toggleUserSelection(${user.id}, this.checked)">
                        </div>
                    </td>
                    <td class="user-name-cell">
                        <div class="user-name-modern">
                            <div class="user-avatar-modern">
                                <i class="fas fa-user"></i>
                            </div>
                            <div>
                                <div class="user-name-text">${user.name}</div>
                                <small class="text-muted">ID: ${user.id}</small>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="email-badge">
                            <i class="fas fa-envelope me-1"></i>
                            ${user.email}
                        </div>
                    </td>
                    <td>
                        <div class="roles-container">
                            ${roles.split(', ').map(role => `
                                <span class="role-badge">${role}</span>
                            `).join('')}
                        </div>
                    </td>
                    <td>
                        <span class="status-badge ${statusClass}">${statusText}</span>
                    </td>
                    <td>
                        <div class="user-actions-modern">
                            <button class="action-btn-modern status-btn-modern" title="Changer le statut" 
                                    onclick="toggleUserStatus(${user.id})">
                                <i class="fas fa-power-off"></i>
                            </button>
                            <button class="action-btn-modern roles-btn-modern" title="Gérer les rôles" 
                                    onclick="openRolesModal(${user.id})">
                                <i class="fas fa-user-shield"></i>
                            </button>
                            <button class="action-btn-modern edit-btn-modern" title="Modifier" 
                                    onclick="openEditModal(${user.id})">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="action-btn-modern delete-btn-modern" title="Supprimer" 
                                    onclick="showDeleteConfirmation(${user.id})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                `;
                
                tbody.appendChild(row);
            });
            
            // Update bulk actions
            updateBulkActions();
            
            document.getElementById('emptyState').style.display = 'none';
            document.getElementById('tableContainer').style.display = 'block';
            document.getElementById('paginationContainer').style.display = 'flex';
        };

        // Render pagination
        const renderPagination = (response) => {
            const pagination = document.getElementById('pagination');
            const paginationInfo = document.getElementById('paginationInfo');
            
            // Update pagination info
            const start = (response.current_page - 1) * response.per_page + 1;
            const end = Math.min(response.current_page * response.per_page, response.total);
            paginationInfo.textContent = `Affichage de ${start} à ${end} sur ${response.total} utilisateurs`;
            
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
            loadUsers(page, currentFilters);
        };

        // Toggle user selection
        const toggleUserSelection = (userId, isChecked) => {
            if (isChecked) {
                selectedUsers.add(userId);
            } else {
                selectedUsers.delete(userId);
            }
            
            updateSelectAllCheckbox();
            updateBulkActions();
        };

        // Update select all checkbox
        const updateSelectAllCheckbox = () => {
            const selectAllCheckbox = document.getElementById('selectAllCheckbox');
            if (selectAllCheckbox) {
                const allCheckboxes = document.querySelectorAll('.row-checkbox');
                const allChecked = allCheckboxes.length > 0 && 
                    Array.from(allCheckboxes).every(cb => cb.checked);
                selectAllCheckbox.checked = allChecked;
                selectAllCheckbox.indeterminate = !allChecked && selectedUsers.size > 0;
            }
        };

        // Update bulk actions
        const updateBulkActions = () => {
            const bulkActions = document.getElementById('bulkActions');
            const selectedCount = document.getElementById('selectedCount');
            
            if (selectedUsers.size > 0) {
                bulkActions.style.display = 'block';
                selectedCount.textContent = `${selectedUsers.size} utilisateur(s) sélectionné(s)`;
            } else {
                bulkActions.style.display = 'none';
            }
        };

        // Select all users
        const selectAllUsers = (isChecked) => {
            const checkboxes = document.querySelectorAll('.row-checkbox');
            
            checkboxes.forEach(checkbox => {
                const userId = parseInt(checkbox.value);
                checkbox.checked = isChecked;
                
                if (isChecked) {
                    selectedUsers.add(userId);
                } else {
                    selectedUsers.delete(userId);
                }
            });
            
            updateBulkActions();
        };

        // Apply bulk action
        const applyBulkAction = () => {
            const action = document.getElementById('bulkActionSelect').value;
            
            if (!action || selectedUsers.size === 0) {
                showAlert('warning', 'Veuillez sélectionner une action et des utilisateurs');
                return;
            }
            
            if (action === 'delete') {
                if (!confirm(`Êtes-vous sûr de vouloir supprimer ${selectedUsers.size} utilisateur(s) ?`)) {
                    return;
                }
            }
            
            const data = {
                ids: Array.from(selectedUsers),
                action: action
            };
            
            $.ajax({
                url: '{{ route("users.bulk-update") }}',
                type: 'POST',
                data: data,
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        showAlert('success', response.message);
                        selectedUsers.clear();
                        loadUsers(currentPage, currentFilters);
                        loadStatistics();
                    } else {
                        showAlert('danger', response.message);
                    }
                },
                error: function(xhr, status, error) {
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;
                        let errorMessage = 'Veuillez corriger les erreurs suivantes:<br>';
                        for (const field in errors) {
                            errorMessage += `- ${errors[field].join('<br>')}<br>`;
                        }
                        showAlert('danger', errorMessage);
                    } else {
                        showAlert('danger', 'Erreur lors de l\'opération: ' + error);
                    }
                }
            });
        };

        // Toggle user status
        const toggleUserStatus = (userId) => {
            if (!confirm('Êtes-vous sûr de vouloir changer le statut de cet utilisateur ?')) {
                return;
            }
            
            $.ajax({
                url: `/users/${userId}/toggle-status`,
                type: 'POST',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        showAlert('success', response.message);
                        loadUsers(currentPage, currentFilters);
                        loadStatistics();
                    } else {
                        showAlert('danger', response.message);
                    }
                },
                error: function(xhr, status, error) {
                    showAlert('danger', 'Erreur lors du changement de statut: ' + error);
                }
            });
        };

        // Open roles modal
        const openRolesModal = (userId) => {
    const user = allUsers.find(u => u.id === userId);
    
    if (!user) {
        showAlert('danger', 'Utilisateur non trouvé');
        return;
    }
    
    // Load available roles
    $.ajax({
        url: '/api/roles',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            const rolesContainer = document.getElementById('rolesContainer');
            rolesContainer.innerHTML = '';
            
            response.forEach(role => {
                const isAssigned = user.roles.some(r => r.id === role.id);
                rolesContainer.innerHTML += `
                    <div class="form-check">
                        <input class="form-check-input role-radio" type="radio" 
                               name="userRole" value="${role.id}" id="role-${role.id}" 
                               ${isAssigned ? 'checked' : ''}>
                        <label class="form-check-label" for="role-${role.id}">
                            ${role.name}
                        </label>
                    </div>
                `;
            });
            
            document.getElementById('userRolesId').value = user.id;
            document.getElementById('userRolesName').textContent = user.name;
            
            new bootstrap.Modal(document.getElementById('rolesModal')).show();
        },
        error: function(xhr) {
            showAlert('danger', 'Erreur lors du chargement des rôles');
        }
    });
};

        // Update user roles
        const updateUserRoles = () => {
            const userId = document.getElementById('userRolesId').value;
            const selectedRoles = Array.from(document.querySelectorAll('.role-checkbox:checked'))
                .map(cb => cb.value);
            
            const data = {
                roles: selectedRoles
            };
            
            $.ajax({
                url: `/users/${userId}/update-roles`,
                type: 'POST',
                data: data,
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        showAlert('success', 'Rôles mis à jour avec succès !');
                        const modal = bootstrap.Modal.getInstance(document.getElementById('rolesModal'));
                        modal.hide();
                        loadUsers(currentPage, currentFilters);
                    } else {
                        showAlert('danger', response.message);
                    }
                },
                error: function(xhr) {
                    showAlert('danger', 'Erreur lors de la mise à jour des rôles');
                }
            });
        };

        // Show delete confirmation modal
        const showDeleteConfirmation = (userId) => {
            const user = allUsers.find(u => u.id === userId);
            
            if (!user) {
                showAlert('danger', 'Utilisateur non trouvé');
                return;
            }
            
            userToDelete = user;
            
            document.getElementById('userToDeleteInfo').innerHTML = `
                <div class="user-info">
                    <div class="user-info-avatar">
                        <i class="fas fa-user fa-2x"></i>
                    </div>
                    <div>
                        <div class="user-info-name">${user.name}</div>
                        <div class="user-info-email">${user.email}</div>
                        <div class="user-info-roles">Rôles: ${user.roles ? user.roles.map(r => r.name).join(', ') : 'Aucun'}</div>
                    </div>
                </div>
                <div class="row small text-muted">
                    <div class="col-6">
                        <div><strong>ID:</strong> ${user.id}</div>
                        <div><strong>Statut:</strong> ${user.is_active ? 'Actif' : 'Inactif'}</div>
                        <div><strong>Créé le:</strong> ${new Date(user.created_at).toLocaleDateString()}</div>
                    </div>
                    <div class="col-6">
                        <div><strong>Dernière modification:</strong> ${new Date(user.updated_at).toLocaleDateString()}</div>
                        <div><strong>Nombre de rôles:</strong> ${user.roles ? user.roles.length : 0}</div>
                        <div><strong>Établissement:</strong> ${user.etablissement ? user.etablissement.name : 'Aucun'}</div>
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

        // Delete user
        const deleteUser = () => {
            if (!userToDelete) {
                showAlert('danger', 'Aucun utilisateur à supprimer');
                return;
            }
            
            const userId = userToDelete.id;
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
            const row = document.getElementById(`user-row-${userId}`);
            if (row) {
                row.classList.add('deleting-row');
            }
            
            // Send DELETE request
            $.ajax({
                url: `/users/${userId}`,
                type: 'DELETE',
                dataType: 'json',
                success: function(response) {
                    // Hide modal
                    const deleteModal = bootstrap.Modal.getInstance(document.getElementById('deleteConfirmationModal'));
                    deleteModal.hide();
                    
                    if (response.success) {
                        // Remove user from array
                        allUsers = allUsers.filter(u => u.id !== userId);
                        selectedUsers.delete(userId);
                        
                        // Show success message
                        showAlert('success', response.message || 'Utilisateur supprimé avec succès !');
                        
                        // Remove row after animation
                        if (row) {
                            setTimeout(() => {
                                row.remove();
                                
                                // Check if table is now empty
                                const tbody = document.getElementById('usersTableBody');
                                if (tbody.children.length === 0) {
                                    document.getElementById('emptyState').style.display = 'block';
                                    document.getElementById('tableContainer').style.display = 'none';
                                    document.getElementById('paginationContainer').style.display = 'none';
                                    document.getElementById('advancedStatsSection').style.display = 'none';
                                }
                            }, 300);
                        } else {
                            // Reload table
                            setTimeout(() => {
                                loadUsers(currentPage, currentFilters);
                                loadStatistics();
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
                    const row = document.getElementById(`user-row-${userId}`);
                    if (row) {
                        row.classList.remove('deleting-row');
                    }
                    
                    if (xhr.status === 404) {
                        showAlert('danger', 'Utilisateur non trouvé.');
                        loadUsers(currentPage, currentFilters);
                    } else {
                        showAlert('danger', 'Erreur lors de la suppression: ' + error);
                    }
                },
                complete: function() {
                    userToDelete = null;
                }
            });
        };

        // Open edit modal
        const openEditModal = (userId) => {
            const user = allUsers.find(u => u.id === userId);
            
            if (user) {
                document.getElementById('editUserId').value = user.id;
                document.getElementById('editUserName').value = user.name;
                document.getElementById('editUserEmail').value = user.email;
                document.getElementById('editUserIsActive').checked = user.is_active;
                
                new bootstrap.Modal(document.getElementById('editUserModal')).show();
            }
        };

        // Store user
        const storeUser = () => {
            const form = document.getElementById('createUserForm');
            const submitBtn = document.getElementById('submitUserBtn');
            
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }
            
            // Show processing animation
            submitBtn.classList.add('btn-processing');
            submitBtn.innerHTML = `
                <span class="btn-text" style="display: none;">
                    <i class="fas fa-save me-2"></i>Créer l'utilisateur
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
            
            // Convert checkbox value to boolean
            data.is_active = form.querySelector('#createUserIsActive').checked;
            
            $.ajax({
                url: '{{ route("users.store") }}',
                type: 'POST',
                data: data,
                dataType: 'json',
                success: function(response) {
                    // Reset button state
                    submitBtn.classList.remove('btn-processing');
                    submitBtn.innerHTML = `
                        <span class="btn-text">
                            <i class="fas fa-save me-2"></i>Créer l'utilisateur
                        </span>
                    `;
                    submitBtn.disabled = false;
                    
                    if (response.success) {
                        // Close modal
                        const modal = bootstrap.Modal.getInstance(document.getElementById('createUserModal'));
                        modal.hide();
                        
                        // Reset form
                        form.reset();
                        
                        // Reload users
                        loadUsers(1, currentFilters);
                        loadStatistics();
                        
                        // Show success message
                        showAlert('success', 'Utilisateur créé avec succès !');
                    } else {
                        showAlert('danger', response.message || 'Erreur lors de la création');
                    }
                },
                error: function(xhr, status, error) {
                    // Reset button state
                    submitBtn.classList.remove('btn-processing');
                    submitBtn.innerHTML = `
                        <span class="btn-text">
                            <i class="fas fa-save me-2"></i>Créer l'utilisateur
                        </span>
                    `;
                    submitBtn.disabled = false;
                    
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;
                        let errorMessage = 'Veuillez corriger les erreurs suivantes:<br>';
                        for (const field in errors) {
                            errorMessage += `- ${errors[field].join('<br>')}<br>`;
                        }
                        showAlert('danger', errorMessage);
                    } else {
                        showAlert('danger', 'Erreur lors de la création: ' + error);
                    }
                }
            });
        };

        // Update user
        const updateUser = () => {
            const form = document.getElementById('editUserForm');
            const submitBtn = document.getElementById('updateUserBtn');
            const userId = document.getElementById('editUserId').value;
            
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
            
            data._method = 'PUT';
            data.is_active = form.querySelector('#editUserIsActive').checked;
            
            // If password is empty, remove it from data
            if (!data.password) {
                delete data.password;
                delete data.password_confirmation;
            }
            
            $.ajax({
                url: `/users/${userId}`,
                type: 'POST',
                data: data,
                dataType: 'json',
                success: function(response) {
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
                        const modal = bootstrap.Modal.getInstance(document.getElementById('editUserModal'));
                        modal.hide();
                        
                        // Reload users
                        loadUsers(currentPage, currentFilters);
                        
                        // Show success message
                        showAlert('success', 'Utilisateur mis à jour avec succès !');
                    } else {
                        showAlert('danger', response.message || 'Erreur lors de la mise à jour');
                    }
                },
                error: function(xhr, status, error) {
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
                        let errorMessage = 'Veuillez corriger les erreurs suivantes:<br>';
                        for (const field in errors) {
                            errorMessage += `- ${errors[field].join('<br>')}<br>`;
                        }
                        showAlert('danger', errorMessage);
                    } else {
                        showAlert('danger', 'Erreur lors de la mise à jour: ' + error);
                    }
                }
            });
        };

        // Load statistics
        const loadStatistics = () => {
            $.ajax({
                url: '{{ route("users.statistics") }}',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        updateAdvancedStats(response.data);
                    }
                },
                error: function(xhr) {
                    console.error('Error loading statistics:', xhr);
                }
            });
        };

        // Update advanced stats
        const updateAdvancedStats = (stats) => {
            const advancedStatsContainer = document.getElementById('advancedStats');
            
            const html = `
                <div class="advanced-stat-card">
                    <div class="advanced-stat-title">
                        <i class="fas fa-users"></i>
                        Total Utilisateurs
                    </div>
                    <div class="advanced-stat-value">
                        ${stats.total_users || 0}
                    </div>
                    <div class="advanced-stat-subtext">
                        Utilisateurs enregistrés
                    </div>
                </div>
                
                <div class="advanced-stat-card">
                    <div class="advanced-stat-title">
                        <i class="fas fa-user-check"></i>
                        Utilisateurs Actifs
                    </div>
                    <div class="advanced-stat-value">
                        ${stats.active_users || 0}
                    </div>
                    <div class="advanced-stat-subtext">
                        ${stats.active_percentage || 0}% du total
                    </div>
                </div>
                
                <div class="advanced-stat-card">
                    <div class="advanced-stat-title">
                        <i class="fas fa-user-shield"></i>
                        Administrateurs
                    </div>
                    <div class="advanced-stat-value">
                        ${stats.admin_count || 0}
                    </div>
                    <div class="advanced-stat-subtext">
                        Utilisateurs avec rôle admin
                    </div>
                </div>
                
                <div class="advanced-stat-card">
                    <div class="advanced-stat-title">
                        <i class="fas fa-calendar-day"></i>
                        Nouveaux (7 jours)
                    </div>
                    <div class="advanced-stat-value">
                        ${stats.new_last_7_days || 0}
                    </div>
                    <div class="advanced-stat-subtext">
                        Inscriptions récentes
                    </div>
                </div>
            `;
            
            advancedStatsContainer.innerHTML = html;
        };

        // Show loading state
        const showLoading = () => {
            document.getElementById('loadingSpinner').style.display = 'flex';
            document.getElementById('tableContainer').style.display = 'none';
            document.getElementById('emptyState').style.display = 'none';
            document.getElementById('paginationContainer').style.display = 'none';
            document.getElementById('bulkActions').style.display = 'none';
        };

        // Hide loading state
        const hideLoading = () => {
            document.getElementById('loadingSpinner').style.display = 'none';
        };

        // Format number
        const formatNumber = (num) => {
            if (num === null || num === undefined) return 'N/A';
            const number = typeof num === 'string' ? parseFloat(num) : num;
            if (isNaN(number)) return 'N/A';
            return new Intl.NumberFormat('fr-FR').format(number);
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
                        loadUsers(1, currentFilters);
                    }, 500);
                });
            }
            
            // Select all checkbox
            const selectAllCheckbox = document.getElementById('selectAllCheckbox');
            if (selectAllCheckbox) {
                selectAllCheckbox.addEventListener('change', function() {
                    selectAllUsers(this.checked);
                });
            }
            
            // Apply bulk action button
            const applyBulkActionBtn = document.getElementById('applyBulkActionBtn');
            if (applyBulkActionBtn) {
                applyBulkActionBtn.addEventListener('click', applyBulkAction);
            }
            
            // Clear selection button
            const clearSelectionBtn = document.getElementById('clearSelectionBtn');
            if (clearSelectionBtn) {
                clearSelectionBtn.addEventListener('click', () => {
                    selectedUsers.clear();
                    loadUsers(currentPage, currentFilters);
                });
            }
            
            // Submit user form
            const submitUserBtn = document.getElementById('submitUserBtn');
            if (submitUserBtn) {
                submitUserBtn.addEventListener('click', storeUser);
            }
            
            // Update user form
            const updateUserBtn = document.getElementById('updateUserBtn');
            if (updateUserBtn) {
                updateUserBtn.addEventListener('click', updateUser);
            }
            
            // Confirm delete button
            const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
            if (confirmDeleteBtn) {
                confirmDeleteBtn.addEventListener('click', deleteUser);
            }
            
            // Update roles button
            const updateRolesBtn = document.getElementById('updateRolesBtn');
            if (updateRolesBtn) {
                updateRolesBtn.addEventListener('click', updateUserRoles);
            }
            
            // Reset delete modal when hidden
            const deleteModal = document.getElementById('deleteConfirmationModal');
            if (deleteModal) {
                deleteModal.addEventListener('hidden.bs.modal', function() {
                    userToDelete = null;
                    const deleteBtn = document.getElementById('confirmDeleteBtn');
                    deleteBtn.innerHTML = `
                        <span class="btn-text">
                            <i class="fas fa-trash me-2"></i>Supprimer définitivement
                        </span>
                    `;
                    deleteBtn.disabled = false;
                });
            }
            
            // Refresh stats button
            const refreshStatsBtn = document.getElementById('refreshStatsBtn');
            if (refreshStatsBtn) {
                refreshStatsBtn.addEventListener('click', function() {
                    const btn = this;
                    const originalText = btn.innerHTML;
                    
                    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Chargement...';
                    btn.disabled = true;
                    
                    loadStatistics();
                    
                    setTimeout(() => {
                        btn.innerHTML = originalText;
                        btn.disabled = false;
                    }, 1000);
                });
            }
            
            // Reset create form when modal is hidden
            const createModal = document.getElementById('createUserModal');
            if (createModal) {
                createModal.addEventListener('hidden.bs.modal', function() {
                    document.getElementById('createUserForm').reset();
                    const submitBtn = document.getElementById('submitUserBtn');
                    submitBtn.classList.remove('btn-processing');
                    submitBtn.innerHTML = `
                        <span class="btn-text">
                            <i class="fas fa-save me-2"></i>Créer l'utilisateur
                        </span>
                    `;
                    submitBtn.disabled = false;
                });
            }
            
            // Reset edit form when modal is hidden
            const editModal = document.getElementById('editUserModal');
            if (editModal) {
                editModal.addEventListener('hidden.bs.modal', function() {
                    const submitBtn = document.getElementById('updateUserBtn');
                    submitBtn.classList.remove('btn-processing');
                    submitBtn.innerHTML = `
                        <span class="btn-text">
                            <i class="fas fa-save me-2"></i>Enregistrer les modifications
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
                        role: document.getElementById('filterRole').value,
                        status: document.getElementById('filterStatus').value,
                        sort_by: document.getElementById('filterSortBy').value,
                        sort_direction: document.getElementById('filterSortDirection').value
                    };
                    loadUsers(1, currentFilters);
                });
            }
            
            // Clear filters
            const clearFiltersBtn = document.getElementById('clearFiltersBtn');
            if (clearFiltersBtn) {
                clearFiltersBtn.addEventListener('click', () => {
                    document.getElementById('filterRole').value = '';
                    document.getElementById('filterStatus').value = '';
                    document.getElementById('filterSortBy').value = 'name';
                    document.getElementById('filterSortDirection').value = 'asc';
                    currentFilters = {};
                    loadUsers(1);
                });
            }
        };
    </script>
    
    <style>
        /* Styles spécifiques pour les utilisateurs */
        .user-name-modern {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .user-avatar-modern {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            color: white;
            font-size: 1.2rem;
        }

        .user-name-text {
            font-weight: 600;
            color: var(--text-color);
            margin-bottom: 2px;
        }

        .email-badge {
            display: inline-flex;
            align-items: center;
            padding: 4px 10px;
            border-radius: 20px;
            background: #e8f4fd;
            color: #1179c9;
            font-weight: 500;
            font-size: 0.85rem;
        }

        .roles-container {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
        }

        .role-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 15px;
            background: #f0f7ff;
            color: #3a56e4;
            font-size: 0.75rem;
            font-weight: 500;
            border: 1px solid #d1e3ff;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .status-active {
            background: linear-gradient(135deg, #06b48a, #059672);
            color: white;
        }

        .status-inactive {
            background: linear-gradient(135deg, #ef476f, #d4335f);
            color: white;
        }

        .user-actions-modern {
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
        }

        .status-btn-modern {
            background: linear-gradient(135deg, #45b7d1, #3a9bb8);
            color: white;
        }

        .status-btn-modern:hover {
            background: linear-gradient(135deg, #3a9bb8, #2d7f99);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(69, 183, 209, 0.3);
        }

        .roles-btn-modern {
            background: linear-gradient(135deg, #9d4edd, #7b2cbf);
            color: white;
        }

        .roles-btn-modern:hover {
            background: linear-gradient(135deg, #7b2cbf, #5a189a);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(157, 78, 221, 0.3);
        }

        .edit-btn-modern {
            background: linear-gradient(135deg, #ffd166, #ffb347);
            color: #333;
        }

        .edit-btn-modern:hover {
            background: linear-gradient(135deg, #ffb347, #ff9a2d);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(255, 209, 102, 0.3);
        }

        .delete-btn-modern {
            background: linear-gradient(135deg, #ef476f, #d4335f);
            color: white;
        }

        .delete-btn-modern:hover {
            background: linear-gradient(135deg, #d4335f, #b82a50);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(239, 71, 111, 0.3);
        }

        /* User info */
        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 15px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 10px;
        }

        .user-info-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            color: white;
            font-size: 1.5rem;
        }

        .user-info-name {
            font-weight: 600;
            font-size: 1.2rem;
            color: var(--text-color);
        }

        .user-info-email {
            font-size: 0.9rem;
            color: #1179c9;
        }

        .user-info-roles {
            font-size: 0.9rem;
            color: #6c757d;
        }

        /* Roles modal styles */
        #rolesContainer {
            max-height: 300px;
            overflow-y: auto;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        #rolesContainer .form-check {
            padding: 8px 15px;
            border-bottom: 1px solid #e9ecef;
        }

        #rolesContainer .form-check:last-child {
            border-bottom: none;
        }

        #rolesContainer .form-check-label {
            font-weight: 500;
            color: #495057;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .bulk-actions-content {
                flex-direction: column;
                align-items: stretch;
                gap: 10px;
            }
            
            .bulk-actions-buttons {
                flex-wrap: wrap;
            }
            
            .user-name-modern {
                flex-direction: column;
                align-items: flex-start;
                gap: 8px;
            }
            
            .user-avatar-modern {
                width: 35px;
                height: 35px;
            }
            
            .user-actions-modern {
                flex-direction: column;
                gap: 5px;
            }
            
            .action-btn-modern {
                width: 100%;
                height: 36px;
            }
            
            .roles-container {
                flex-direction: column;
                gap: 3px;
            }
            
            .role-badge {
                width: fit-content;
            }
        }
    </style>
@endsection