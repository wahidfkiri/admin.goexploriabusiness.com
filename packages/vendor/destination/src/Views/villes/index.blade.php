@extends('layouts.app')

@section('content')
    <!-- MAIN CONTENT -->
    <main class="dashboard-content">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">
                <span class="page-title-icon"><i class="fas fa-city"></i></span>
                Gestion des Villes
            </h1>
            
            <div class="page-actions">
                <button class="btn btn-outline-secondary" id="toggleFilterBtn">
                    <i class="fas fa-sliders-h me-2"></i>Filtres
                </button>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createVilleModal">
                    <i class="fas fa-plus-circle me-2"></i>Nouvelle Ville
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
                    <label for="filterCountry" class="form-label-modern">Pays</label>
                    <select class="form-select-modern" id="filterCountry">
                        <option value="">Tous les pays</option>
                        @foreach($countries ?? [] as $country)
                            <option value="{{ $country->code }}">{{ $country->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="filterProvince" class="form-label-modern">Province</label>
                    <select class="form-select-modern" id="filterProvince">
                        <option value="">Toutes les provinces</option>
                        @foreach($provinces ?? [] as $province)
                            <option value="{{ $province->code }}">{{ $province->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="filterRegion" class="form-label-modern">Région</label>
                    <select class="form-select-modern" id="filterRegion">
                        <option value="">Toutes les régions</option>
                        @foreach($regions ?? [] as $region)
                            <option value="{{ $region->code }}">{{ $region->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="filterStatus" class="form-label-modern">Statut</label>
                    <select class="form-select-modern" id="filterStatus">
                        <option value="">Tous les statuts</option>
                        @foreach($statuses ?? [] as $status)
                            <option value="{{ $status }}">{{ $status }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        
        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stats-card-modern">
                <div class="stats-header-modern">
                    <div>
                        <div class="stats-value-modern" id="totalVilles">0</div>
                        <div class="stats-label-modern">Total Villes</div>
                    </div>
                    <div class="stats-icon-modern" style="background: linear-gradient(135deg, var(--primary-color), #3a56e4);">
                        <i class="fas fa-city"></i>
                    </div>
                </div>
            </div>
            
            <div class="stats-card-modern">
                <div class="stats-header-modern">
                    <div>
                        <div class="stats-value-modern" id="totalPopulation">0</div>
                        <div class="stats-label-modern">Population Totale</div>
                    </div>
                    <div class="stats-icon-modern" style="background: linear-gradient(135deg, var(--accent-color), #06b48a);">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
            </div>
            
            <div class="stats-card-modern">
                <div class="stats-header-modern">
                    <div>
                        <div class="stats-value-modern" id="totalArea">0</div>
                        <div class="stats-label-modern">Superficie Totale</div>
                    </div>
                    <div class="stats-icon-modern" style="background: linear-gradient(135deg, #ffd166, #ffb347);">
                        <i class="fas fa-globe"></i>
                    </div>
                </div>
            </div>
            
            <div class="stats-card-modern">
                <div class="stats-header-modern">
                    <div>
                        <div class="stats-value-modern" id="totalHouseholds">0</div>
                        <div class="stats-label-modern">Ménages</div>
                    </div>
                    <div class="stats-icon-modern" style="background: linear-gradient(135deg, #ef476f, #d4335f);">
                        <i class="fas fa-home"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Main Card -->
        <div class="main-card-modern">
            <div class="card-header-modern">
                <h3 class="card-title-modern">Liste des Villes</h3>
                <div class="search-container">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" class="search-input" placeholder="Rechercher une ville..." id="searchInput">
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
                                <th>Ville</th>
                                <th>Province</th>
                                <th>Région</th>
                                <th>Population</th>
                                <th>Densité</th>
                                <th>Statut</th>
                                <th style="text-align: center;">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="villesTableBody">
                            <!-- Villes will be loaded here via AJAX -->
                        </tbody>
                    </table>
                </div>
                
                <!-- Empty State -->
                <div class="empty-state-modern" id="emptyState" style="display: none;">
                    <div class="empty-icon-modern">
                        <i class="fas fa-city"></i>
                    </div>
                    <h3 class="empty-title-modern">Aucune ville trouvée</h3>
                    <p class="empty-text-modern">Commencez par créer votre première ville.</p>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createVilleModal">
                        <i class="fas fa-plus-circle me-2"></i>Créer une ville
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
        <button class="fab-modern" data-bs-toggle="modal" data-bs-target="#createVilleModal">
            <i class="fas fa-plus"></i>
        </button>
    </main>
    
    <!-- Modals (à créer dans des fichiers séparés ou inline) -->
    @include('destination::villes.create-modal')
    @include('destination::villes.edit-modal')
    @include('destination::villes.delete-modal')
    
    <!-- JavaScript (similaire à celui des provinces/régions) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Configuration pour les villes
let currentPage = 1;
let currentFilters = {};
let allVilles = [];
let villeToDelete = null;

// Initialisation
document.addEventListener('DOMContentLoaded', function() {
    setupAjax();
    loadVilles();
    loadStatistics();
    setupEventListeners();
    setupDependentDropdowns();
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

// Gestion des dropdowns dépendants
const setupDependentDropdowns = () => {
    // Country change -> update provinces
    const countrySelect = document.getElementById('villeCountry');
    if (countrySelect) {
        countrySelect.addEventListener('change', function() {
            const countryId = this.value;
            const targetSelectId = this.getAttribute('data-target');
            const url = this.getAttribute('data-url');
            
            if (countryId) {
                updateDependentDropdown(countryId, url, targetSelectId);
            } else {
                resetDependentDropdown(targetSelectId);
            }
        });
    }
    
    // Province change -> update regions
    const provinceSelect = document.getElementById('villeProvince');
    if (provinceSelect) {
        provinceSelect.addEventListener('change', function() {
            const provinceId = this.value;
            const targetSelectId = this.getAttribute('data-target');
            const url = this.getAttribute('data-url');
            
            if (provinceId) {
                updateDependentDropdown(provinceId, url, targetSelectId);
            } else {
                resetDependentDropdown(targetSelectId);
            }
        });
    }
    
    // Region change -> update secteurs
    const regionSelect = document.getElementById('villeRegion');
    if (regionSelect) {
        regionSelect.addEventListener('change', function() {
            const regionId = this.value;
            const targetSelectId = this.getAttribute('data-target');
            const url = this.getAttribute('data-url');
            
            if (regionId) {
                updateDependentDropdown(regionId, url, targetSelectId);
            } else {
                resetDependentDropdown(targetSelectId);
            }
        });
    }
    
    // Edit modal dropdowns
    const editCountrySelect = document.getElementById('editVilleCountry');
    if (editCountrySelect) {
        editCountrySelect.addEventListener('change', function() {
            const countryId = this.value;
            const targetSelectId = this.getAttribute('data-target');
            const url = this.getAttribute('data-url');
            
            if (countryId) {
                updateDependentDropdown(countryId, url, targetSelectId, 'edit');
            } else {
                resetDependentDropdown(targetSelectId, 'edit');
            }
        });
    }
    
    const editProvinceSelect = document.getElementById('editVilleProvince');
    if (editProvinceSelect) {
        editProvinceSelect.addEventListener('change', function() {
            const provinceId = this.value;
            const targetSelectId = this.getAttribute('data-target');
            const url = this.getAttribute('data-url');
            
            if (provinceId) {
                updateDependentDropdown(provinceId, url, targetSelectId, 'edit');
            } else {
                resetDependentDropdown(targetSelectId, 'edit');
            }
        });
    }
};

// Mettre à jour un dropdown dépendant
const updateDependentDropdown = (parentId, url, targetSelectId, prefix = '') => {
    const targetSelect = document.getElementById(`${prefix}${targetSelectId.replace('#', '')}`);
    if (!targetSelect) return;
    
    targetSelect.innerHTML = '<option value="">Chargement...</option>';
    targetSelect.disabled = true;
    
    $.ajax({
        url: `${url}/${parentId}`,
        type: 'GET',
        success: function(response) {
            if (response.success) {
                let options = '<option value="">Sélectionner</option>';
                response.data.forEach(item => {
                    options += `<option value="${item.id}">${item.name} (${item.code || ''})</option>`;
                });
                
                targetSelect.innerHTML = options;
                targetSelect.disabled = false;
                
                // Réinitialiser les dropdowns dépendants suivants
                const nextTarget = targetSelect.getAttribute('data-target');
                if (nextTarget) {
                    resetDependentDropdown(nextTarget, prefix);
                }
            } else {
                targetSelect.innerHTML = '<option value="">Erreur de chargement</option>';
            }
        },
        error: function() {
            targetSelect.innerHTML = '<option value="">Erreur de chargement</option>';
        }
    });
};

// Réinitialiser un dropdown dépendant
const resetDependentDropdown = (targetSelectId, prefix = '') => {
    const targetSelect = document.getElementById(`${prefix}${targetSelectId.replace('#', '')}`);
    if (targetSelect) {
        targetSelect.innerHTML = '<option value="">Sélectionnez d\'abord le niveau précédent</option>';
        targetSelect.disabled = true;
        
        // Réinitialiser les dropdowns dépendants suivants
        const nextTarget = targetSelect.getAttribute('data-target');
        if (nextTarget) {
            resetDependentDropdown(nextTarget, prefix);
        }
    }
};

// Load villes
const loadVilles = (page = 1, filters = {}) => {
    showLoading();
    
    const searchTerm = document.getElementById('searchInput')?.value || '';
    
    $.ajax({
        url: '{{ route("villes.index") }}',
        type: 'GET',
        data: {
            page: page,
            search: searchTerm,
            ...filters,
            ajax: true
        },
        success: function(response) {
            if (response.success) {
                allVilles = response.data || [];
                renderVilles(allVilles);
                renderPagination(response);
                hideLoading();
            } else {
                showError('Erreur lors du chargement des villes');
            }
        },
        error: function(xhr) {
            hideLoading();
            showError('Erreur de connexion au serveur');
            console.error('Error:', xhr.responseText);
        }
    });
};

// Render villes
const renderVilles = (villes) => {
    const tbody = document.getElementById('villesTableBody');
    tbody.innerHTML = '';
    
    if (!villes || !Array.isArray(villes) || villes.length === 0) {
        document.getElementById('emptyState').style.display = 'block';
        document.getElementById('tableContainer').style.display = 'none';
        document.getElementById('paginationContainer').style.display = 'none';
        return;
    }
    
    villes.forEach((ville, index) => {
        const row = document.createElement('tr');
        row.id = `ville-row-${ville.id}`;
        row.style.animationDelay = `${index * 0.05}s`;
        
        // Format data
        const population = ville.population ? formatNumber(ville.population) : 'N/A';
        const density = ville.density ? `${formatNumber(ville.density)} hab/km²` : 'N/A';
        const statusClass = getStatusClass(ville.status);
        
        row.innerHTML = `
            <td class="ville-name-cell">
                <div class="ville-name-modern">
                    <div class="ville-icon-modern">
                        <i class="fas fa-city"></i>
                    </div>
                    <div>
                        <div class="ville-name-text">${ville.name}</div>
                        <small class="text-muted">${ville.code ? 'Code: ' + ville.code : ''}</small>
                    </div>
                </div>
            </td>
            <td>
                <div class="province-badge">
                    <i class="fas fa-map-marked-alt me-1"></i>
                    ${ville.province?.name || 'N/A'}
                </div>
                <small class="text-muted">${ville.province?.code || ''}</small>
            </td>
            <td>
                ${ville.region?.name || 'N/A'}
            </td>
            <td>
                <div>${population}</div>
                <small class="text-muted">${ville.classification || ''}</small>
            </td>
            <td>${density}</td>
            <td>
                ${ville.status ? `<span class="status-badge ${statusClass}">${ville.status}</span>` : 'N/A'}
            </td>
            <td>
                <div class="ville-actions-modern">
                    <a href="{{ route('villes.show', '') }}/${ville.id}" 
                       class="action-btn-modern view-btn-modern" title="Voir détails">
                        <i class="fas fa-eye"></i>
                    </a>
                    <button class="action-btn-modern edit-btn-modern" title="Modifier" 
                            onclick="openEditModal(${ville.id})">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="action-btn-modern delete-btn-modern" title="Supprimer" 
                            onclick="showDeleteConfirmation(${ville.id})">
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

// Render pagination
const renderPagination = (response) => {
    const pagination = document.getElementById('pagination');
    const paginationInfo = document.getElementById('paginationInfo');
    
    // Update pagination info
    const start = (response.current_page - 1) * response.per_page + 1;
    const end = Math.min(response.current_page * response.per_page, response.total);
    paginationInfo.textContent = `Affichage de ${start} à ${end} sur ${response.total} villes`;
    
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
    loadVilles(page, currentFilters);
};

// Get status CSS class
const getStatusClass = (status) => {
    if (!status) return '';
    
    status = status.toLowerCase();
    
    if (status.includes('capitale')) return 'status-capital';
    if (status.includes('métropole')) return 'status-metropolis';
    if (status.includes('urbaine')) return 'status-urban';
    if (status.includes('rurale')) return 'status-rural';
    
    return '';
};

// Show delete confirmation modal
const showDeleteConfirmation = (villeId) => {
    const ville = allVilles.find(v => v.id === villeId);
    
    if (!ville) {
        showAlert('danger', 'Ville non trouvée');
        return;
    }
    
    villeToDelete = ville;
    
    document.getElementById('villeToDeleteInfo').innerHTML = `
        <div class="ville-info">
            <div class="ville-info-icon">
                <i class="fas fa-city fa-2x"></i>
            </div>
            <div>
                <div class="ville-info-name">${ville.name}</div>
                <div class="ville-info-code">${ville.code ? 'Code: ' + ville.code : ''}</div>
            </div>
        </div>
        <div class="row small text-muted">
            <div class="col-6">
                <div><strong>Province:</strong> ${ville.province?.name || 'N/A'}</div>
                <div><strong>Population:</strong> ${formatNumber(ville.population)}</div>
            </div>
            <div class="col-6">
                <div><strong>Superficie:</strong> ${formatNumber(ville.area)} km²</div>
                <div><strong>Maire:</strong> ${ville.mayor || 'N/A'}</div>
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

// Delete ville
const deleteVille = () => {
    if (!villeToDelete) {
        showAlert('danger', 'Aucune ville à supprimer');
        return;
    }
    
    const villeId = villeToDelete.id;
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
    const row = document.getElementById(`ville-row-${villeId}`);
    if (row) {
        row.classList.add('deleting-row');
    }
    
    // Send DELETE request
    $.ajax({
        url: `/villes/${villeId}`,
        type: 'DELETE',
        dataType: 'json',
        success: function(response) {
            // Hide modal
            const deleteModal = bootstrap.Modal.getInstance(document.getElementById('deleteConfirmationModal'));
            deleteModal.hide();
            
            if (response.success) {
                // Remove ville from array
                allVilles = allVilles.filter(v => v.id !== villeId);
                
                // Update statistics
                loadStatistics();
                
                // Show success message
                showAlert('success', response.message || 'Ville supprimée avec succès !');
                
                // Remove row after animation
                if (row) {
                    setTimeout(() => {
                        row.remove();
                        
                        // Check if table is now empty
                        const tbody = document.getElementById('villesTableBody');
                        if (tbody.children.length === 0) {
                            document.getElementById('emptyState').style.display = 'block';
                            document.getElementById('tableContainer').style.display = 'none';
                            document.getElementById('paginationContainer').style.display = 'none';
                        }
                    }, 300);
                } else {
                    // Reload table
                    setTimeout(() => {
                        loadVilles(currentPage, currentFilters);
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
            const row = document.getElementById(`ville-row-${villeId}`);
            if (row) {
                row.classList.remove('deleting-row');
            }
            
            if (xhr.status === 404) {
                showAlert('danger', 'Ville non trouvée.');
                loadVilles(currentPage, currentFilters);
            } else {
                showAlert('danger', 'Erreur lors de la suppression: ' + error);
            }
        },
        complete: function() {
            villeToDelete = null;
        }
    });
};

// Open edit modal
const openEditModal = (villeId) => {
    const ville = allVilles.find(v => v.id === villeId);
    
    if (ville) {
        document.getElementById('editVilleId').value = ville.id;
        document.getElementById('editVilleName').value = ville.name;
        document.getElementById('editVilleCode').value = ville.code || '';
        document.getElementById('editVilleClassification').value = ville.classification || '';
        document.getElementById('editVilleStatus').value = ville.status || '';
        document.getElementById('editVilleMayor').value = ville.mayor || '';
        document.getElementById('editVilleWebsite').value = ville.website || '';
        document.getElementById('editVilleCountry').value = ville.country_id || '';
        document.getElementById('editVilleProvince').value = ville.province_id || '';
        document.getElementById('editVilleRegion').value = ville.region_id || '';
        document.getElementById('editVilleSecteur').value = ville.secteur_id || '';
        document.getElementById('editVillePostalCodePrefix').value = ville.postal_code_prefix || '';
        document.getElementById('editVilleFoundingYear').value = ville.founding_year || '';
        document.getElementById('editVillePopulation').value = ville.population || '';
        document.getElementById('editVilleArea').value = ville.area || '';
        document.getElementById('editVilleHouseholds').value = ville.households || '';
        document.getElementById('editVilleAltitude').value = ville.altitude || '';
        document.getElementById('editVilleLatitude').value = ville.latitude || '';
        document.getElementById('editVilleLongitude').value = ville.longitude || '';
        document.getElementById('editVilleDescription').value = ville.description || '';
        document.getElementById('editVilleHistory').value = ville.history || '';
        document.getElementById('editVilleEconomy').value = ville.economy || '';
        document.getElementById('editVilleAttractions').value = ville.attractions || '';
        document.getElementById('editVilleCulture').value = ville.culture || '';
        document.getElementById('editVilleTransport').value = ville.transport || '';
        document.getElementById('editVilleEducation').value = ville.education || '';
        
        // Load dependent data for edit modal
        if (ville.country_id) {
            setTimeout(() => {
                loadDependentDataForEdit(ville);
            }, 100);
        }
        
        new bootstrap.Modal(document.getElementById('editVilleModal')).show();
    }
};

// Load dependent data for edit modal
const loadDependentDataForEdit = (ville) => {
    // Load provinces for the country
    if (ville.country_id) {
        $.ajax({
            url: `{{ route('villes.provinces-by-country', '') }}/${ville.country_id}`,
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    let options = '<option value="">Sélectionner</option>';
                    response.data.forEach(province => {
                        options += `<option value="${province.id}" ${province.id == ville.province_id ? 'selected' : ''}>
                            ${province.name} (${province.code || ''})
                        </option>`;
                    });
                    document.getElementById('editVilleProvince').innerHTML = options;
                    document.getElementById('editVilleProvince').disabled = false;
                }
            }
        });
    }
    
    // Load regions for the province
    if (ville.province_id) {
        $.ajax({
            url: `{{ route('villes.regions-by-province', '') }}/${ville.province_id}`,
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    let options = '<option value="">Sélectionner</option>';
                    response.data.forEach(region => {
                        options += `<option value="${region.id}" ${region.id == ville.region_id ? 'selected' : ''}>
                            ${region.name} (${region.code || ''})
                        </option>`;
                    });
                    document.getElementById('editVilleRegion').innerHTML = options;
                    document.getElementById('editVilleRegion').disabled = false;
                }
            }
        });
    }
    
    // Load secteurs for the region
    if (ville.region_id) {
        $.ajax({
            url: `{{ route('villes.secteurs-by-region', '') }}/${ville.region_id}`,
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    let options = '<option value="">Sélectionner</option>';
                    response.data.forEach(secteur => {
                        options += `<option value="${secteur.id}" ${secteur.id == ville.secteur_id ? 'selected' : ''}>
                            ${secteur.name} (${secteur.code || ''})
                        </option>`;
                    });
                    document.getElementById('editVilleSecteur').innerHTML = options;
                    document.getElementById('editVilleSecteur').disabled = false;
                }
            }
        });
    }
};

// Store ville
const storeVille = () => {
    const form = document.getElementById('createVilleForm');
    const submitBtn = document.getElementById('submitVilleBtn');
    
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }
    
    // Show processing animation
    submitBtn.classList.add('btn-processing');
    submitBtn.innerHTML = `
        <span class="btn-text" style="display: none;">
            <i class="fas fa-save me-2"></i>Créer la ville
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
    
    $.ajax({
        url: '{{ route("villes.store") }}',
        type: 'POST',
        data: data,
        dataType: 'json',
        success: function(response) {
            // Reset button state
            submitBtn.classList.remove('btn-processing');
            submitBtn.innerHTML = `
                <span class="btn-text">
                    <i class="fas fa-save me-2"></i>Créer la ville
                </span>
            `;
            submitBtn.disabled = false;
            
            if (response.success) {
                // Close modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('createVilleModal'));
                modal.hide();
                
                // Reset form
                form.reset();
                resetDependentDropdowns();
                
                // Reload villes
                loadVilles(1, currentFilters);
                loadStatistics();
                
                // Show success message
                showAlert('success', 'Ville créée avec succès !');
            } else {
                showAlert('danger', response.message || 'Erreur lors de la création');
            }
        },
        error: function(xhr, status, error) {
            // Reset button state
            submitBtn.classList.remove('btn-processing');
            submitBtn.innerHTML = `
                <span class="btn-text">
                    <i class="fas fa-save me-2"></i>Créer la ville
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

// Reset dependent dropdowns
const resetDependentDropdowns = () => {
    const provinceSelect = document.getElementById('villeProvince');
    const regionSelect = document.getElementById('villeRegion');
    const secteurSelect = document.getElementById('villeSecteur');
    
    if (provinceSelect) {
        provinceSelect.innerHTML = '<option value="">Sélectionnez d\'abord un pays</option>';
        provinceSelect.disabled = true;
    }
    
    if (regionSelect) {
        regionSelect.innerHTML = '<option value="">Sélectionnez d\'abord une province</option>';
        regionSelect.disabled = true;
    }
    
    if (secteurSelect) {
        secteurSelect.innerHTML = '<option value="">Sélectionnez d\'abord une région</option>';
        secteurSelect.disabled = true;
    }
};

// Update ville
const updateVille = () => {
    const form = document.getElementById('editVilleForm');
    const submitBtn = document.getElementById('updateVilleBtn');
    const villeId = document.getElementById('editVilleId').value;
    
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
    
    $.ajax({
        url: `/villes/${villeId}`,
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
                const modal = bootstrap.Modal.getInstance(document.getElementById('editVilleModal'));
                modal.hide();
                
                // Reload villes
                loadVilles(currentPage, currentFilters);
                loadStatistics();
                
                // Show success message
                showAlert('success', 'Ville mise à jour avec succès !');
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
        url: '{{ route("villes.statistics") }}',
        type: 'GET',
        success: function(response) {
            if (response.success) {
                const stats = response.data;
                document.getElementById('totalVilles').textContent = stats.total_villes || 0;
                document.getElementById('totalPopulation').textContent = formatNumber(stats.total_population || 0);
                document.getElementById('totalArea').textContent = formatNumber(stats.total_area || 0);
                document.getElementById('totalHouseholds').textContent = formatNumber(stats.total_households || 0);
                
                // Update advanced stats
                updateAdvancedStats(stats);
            }
        },
        error: function(xhr, status, error) {
            console.error('Statistics AJAX error:', xhr.responseText, status, error);
            
            // Default values
            document.getElementById('totalVilles').textContent = '0';
            document.getElementById('totalPopulation').textContent = '0';
            document.getElementById('totalArea').textContent = '0';
            document.getElementById('totalHouseholds').textContent = '0';
        }
    });
};

// Update advanced stats
const updateAdvancedStats = (stats) => {
    const advancedStatsContainer = document.getElementById('advancedStats') || createAdvancedStatsContainer();
    
    const html = `
        <div class="advanced-stats-grid">
            <div class="advanced-stat-card">
                <div class="advanced-stat-title">Ville la plus peuplée</div>
                <div class="advanced-stat-value">
                    ${stats.most_populous ? stats.most_populous.name : 'N/A'}
                </div>
                <div class="advanced-stat-subtext">
                    ${stats.most_populous ? formatNumber(stats.most_populous.population) + ' habitants' : ''}
                </div>
            </div>
            
            <div class="advanced-stat-card">
                <div class="advanced-stat-title">Ville la plus grande</div>
                <div class="advanced-stat-value">
                    ${stats.largest ? stats.largest.name : 'N/A'}
                </div>
                <div class="advanced-stat-subtext">
                    ${stats.largest ? formatNumber(stats.largest.area) + ' km²' : ''}
                </div>
            </div>
            
            <div class="advanced-stat-card">
                <div class="advanced-stat-title">Plus haute densité</div>
                <div class="advanced-stat-value">
                    ${stats.highest_density ? stats.highest_density.name : 'N/A'}
                </div>
                <div class="advanced-stat-subtext">
                    ${stats.highest_density ? formatNumber(stats.highest_density.density) + ' hab/km²' : ''}
                </div>
            </div>
            
            <div class="advanced-stat-card">
                <div class="advanced-stat-title">Capitales</div>
                <div class="advanced-stat-value">
                    ${stats.capitals_count || 0}
                </div>
                <div class="advanced-stat-subtext">
                    Villes capitales
                </div>
            </div>
        </div>
    `;
    
    advancedStatsContainer.innerHTML = html;
};

// Create advanced stats container
const createAdvancedStatsContainer = () => {
    const container = document.createElement('div');
    container.id = 'advancedStats';
    container.className = 'advanced-stats-section';
    
    // Insérer après les cartes de statistiques
    const statsGrid = document.querySelector('.stats-grid');
    if (statsGrid) {
        statsGrid.parentNode.insertBefore(container, statsGrid.nextSibling);
    }
    
    return container;
};

// Show loading state
const showLoading = () => {
    const loadingSpinner = document.getElementById('loadingSpinner');
    const tableContainer = document.getElementById('tableContainer');
    const emptyState = document.getElementById('emptyState');
    const paginationContainer = document.getElementById('paginationContainer');
    
    if (loadingSpinner) loadingSpinner.style.display = 'flex';
    if (tableContainer) tableContainer.style.display = 'none';
    if (emptyState) emptyState.style.display = 'none';
    if (paginationContainer) paginationContainer.style.display = 'none';
};

// Hide loading state
const hideLoading = () => {
    const loadingSpinner = document.getElementById('loadingSpinner');
    if (loadingSpinner) loadingSpinner.style.display = 'none';
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

// Format number
const formatNumber = (num) => {
    if (num === null || num === undefined) return 'N/A';
    
    // Convertir en nombre si c'est une chaîne
    const number = typeof num === 'string' ? parseFloat(num) : num;
    
    if (isNaN(number)) return 'N/A';
    
    // Formater avec séparateurs de milliers
    return new Intl.NumberFormat('fr-FR').format(number);
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
                loadVilles(1, currentFilters);
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
                country: document.getElementById('filterCountry').value,
                province: document.getElementById('filterProvince').value,
                region: document.getElementById('filterRegion').value,
                status: document.getElementById('filterStatus').value,
                sort_by: document.getElementById('filterSortBy')?.value || 'name',
                sort_direction: document.getElementById('filterSortDirection')?.value || 'asc'
            };
            loadVilles(1, currentFilters);
        });
    }
    
    // Clear filters
    const clearFiltersBtn = document.getElementById('clearFiltersBtn');
    if (clearFiltersBtn) {
        clearFiltersBtn.addEventListener('click', () => {
            document.getElementById('filterCountry').value = '';
            document.getElementById('filterProvince').value = '';
            document.getElementById('filterRegion').value = '';
            document.getElementById('filterStatus').value = '';
            
            const sortBy = document.getElementById('filterSortBy');
            const sortDirection = document.getElementById('filterSortDirection');
            
            if (sortBy) sortBy.value = 'name';
            if (sortDirection) sortDirection.value = 'asc';
            
            currentFilters = {};
            loadVilles(1);
        });
    }
    
    // Submit ville form
    const submitVilleBtn = document.getElementById('submitVilleBtn');
    if (submitVilleBtn) {
        submitVilleBtn.addEventListener('click', storeVille);
    }
    
    // Update ville form
    const updateVilleBtn = document.getElementById('updateVilleBtn');
    if (updateVilleBtn) {
        updateVilleBtn.addEventListener('click', updateVille);
    }
    
    // Confirm delete button
    const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
    if (confirmDeleteBtn) {
        confirmDeleteBtn.addEventListener('click', deleteVille);
    }
    
    // Reset delete modal when hidden
    const deleteModal = document.getElementById('deleteConfirmationModal');
    if (deleteModal) {
        deleteModal.addEventListener('hidden.bs.modal', function() {
            villeToDelete = null;
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
    const createModal = document.getElementById('createVilleModal');
    if (createModal) {
        createModal.addEventListener('hidden.bs.modal', function() {
            document.getElementById('createVilleForm').reset();
            const submitBtn = document.getElementById('submitVilleBtn');
            submitBtn.classList.remove('btn-processing');
            submitBtn.innerHTML = `
                <span class="btn-text">
                    <i class="fas fa-save me-2"></i>Créer la ville
                </span>
            `;
            submitBtn.disabled = false;
            resetDependentDropdowns();
        });
    }
    
    // Reset edit form when modal is hidden
    const editModal = document.getElementById('editVilleModal');
    if (editModal) {
        editModal.addEventListener('hidden.bs.modal', function() {
            const submitBtn = document.getElementById('updateVilleBtn');
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
</script>
    
    <style>
        /* Styles spécifiques pour les villes */
        .ville-name-modern {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .ville-icon-modern {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f8f9fa;
            border: 1px solid #eaeaea;
            color: var(--primary-color);
            font-size: 1.2rem;
        }

        .ville-name-text {
            font-weight: 600;
            color: var(--text-color);
            margin-bottom: 2px;
        }

        .ville-code {
            font-size: 0.8rem;
            color: #6c757d;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 15px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .status-capital {
            background: linear-gradient(135deg, #ffd166, #ffb347);
            color: #333;
        }

        .status-metropolis {
            background: linear-gradient(135deg, #3a56e4, #2947c2);
            color: white;
        }

        .status-urban {
            background: linear-gradient(135deg, #06b48a, #059672);
            color: white;
        }

        .status-rural {
            background: linear-gradient(135deg, #96ceb4, #7dba9a);
            color: white;
        }
        
        /* Autres styles similaires aux précédents */
    </style>
@endsection