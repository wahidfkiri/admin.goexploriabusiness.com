@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('vendor/plugins/css/style.css') }}">

<!-- MAIN CONTENT -->
<main class="dashboard-content">
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">
            <span class="page-title-icon"><i class="fas fa-puzzle-piece"></i></span>
            Modules & Extensions
        </h1>
        
        <div class="page-actions">
            <button class="btn btn-outline-secondary" id="toggleFilterBtn">
                <i class="fas fa-sliders-h me-2"></i>Filtres
            </button>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModuleModal">
                <i class="fas fa-plus-circle me-2"></i>Ajouter un module
            </button>
        </div>
    </div>
    
    <!-- Filter Section -->
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
                <label class="form-label-modern">Catégorie</label>
                <select class="form-select-modern" id="filterCategory">
                    <option value="">Toutes les catégories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label-modern">Statut</label>
                <select class="form-select-modern" id="filterStatus">
                    <option value="">Tous</option>
                    <option value="active">Actifs</option>
                    <option value="inactive">Inactifs</option>
                    <option value="pending">En attente</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label-modern">Type</label>
                <select class="form-select-modern" id="filterType">
                    <option value="">Tous</option>
                    <option value="core">Cœur</option>
                    <option value="official">Officiel</option>
                    <option value="third-party">Tiers</option>
                    <option value="custom">Personnalisé</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label-modern">Prix</label>
                <select class="form-select-modern" id="filterPrice">
                    <option value="">Tous</option>
                    <option value="free">Gratuit</option>
                    <option value="paid">Payant</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label-modern">Trier par</label>
                <select class="form-select-modern" id="filterSortBy">
                    <option value="name">Nom</option>
                    <option value="installed_at">Date d'installation</option>
                    <option value="downloads">Popularité</option>
                    <option value="rating">Note</option>
                </select>
            </div>
        </div>
    </div>
    
    <!-- Stats Cards -->
    <div class="stats-grid">
        <div class="stats-card-modern">
            <div class="stats-header-modern">
                <div>
                    <div class="stats-value-modern" id="totalModules">0</div>
                    <div class="stats-label-modern">Total Modules</div>
                </div>
                <div class="stats-icon-modern" style="background: linear-gradient(135deg, #6366f1, #8b5cf6);">
                    <i class="fas fa-puzzle-piece"></i>
                </div>
            </div>
        </div>
        
        <div class="stats-card-modern">
            <div class="stats-header-modern">
                <div>
                    <div class="stats-value-modern" id="activeModules">0</div>
                    <div class="stats-label-modern">Modules Actifs</div>
                </div>
                <div class="stats-icon-modern" style="background: linear-gradient(135deg, #10b981, #059669);">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
        </div>
        
        <div class="stats-card-modern">
            <div class="stats-header-modern">
                <div>
                    <div class="stats-value-modern" id="inactiveModules">0</div>
                    <div class="stats-label-modern">Modules Inactifs</div>
                </div>
                <div class="stats-icon-modern" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
                    <i class="fas fa-pause-circle"></i>
                </div>
            </div>
        </div>
        
        <div class="stats-card-modern">
            <div class="stats-header-modern">
                <div>
                    <div class="stats-value-modern" id="updatesAvailable">0</div>
                    <div class="stats-label-modern">Mises à jour</div>
                </div>
                <div class="stats-icon-modern" style="background: linear-gradient(135deg, #ef4444, #dc2626);">
                    <i class="fas fa-sync-alt"></i>
                </div>
            </div>
        </div>
        
        <div class="stats-card-modern">
            <div class="stats-header-modern">
                <div>
                    <div class="stats-value-modern" id="freeModules">0</div>
                    <div class="stats-label-modern">Modules Gratuits</div>
                </div>
                <div class="stats-icon-modern" style="background: linear-gradient(135deg, #3b82f6, #2563eb);">
                    <i class="fas fa-gift"></i>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Search Bar -->
    <div class="search-bar-modern">
        <div class="search-wrapper">
            <i class="fas fa-search search-icon"></i>
            <input type="text" class="search-input-modern" placeholder="Rechercher un module..." id="searchInput">
        </div>
        <div class="view-options">
            <button class="view-option active" id="gridViewBtn"><i class="fas fa-th-large"></i></button>
            <button class="view-option" id="listViewBtn"><i class="fas fa-list"></i></button>
        </div>
    </div>
    
    <!-- Main Content -->
    <div class="main-card-modern">
        <div class="card-header-modern">
            <h3 class="card-title-modern">Tous les modules</h3>
            <div class="modules-count"><span id="visibleModulesCount">0</span> modules</div>
        </div>
        
        <div class="card-body-modern">
            <!-- Loading Spinner -->
            <div class="spinner-container" id="loadingSpinner" style="display: none;">
                <div class="spinner-border text-primary spinner" role="status">
                    <span class="visually-hidden">Chargement...</span>
                </div>
            </div>
            
            <!-- Modules Grid View -->
            <div class="modules-grid" id="modulesGridView"></div>
            
            <!-- Modules List View -->
            <div class="modules-list" id="modulesListView" style="display: none;">
                <table class="modern-table">
                    <thead>
                        <tr><th>Module</th><th>Version</th><th>Auteur</th><th>Installé le</th><th>Statut</th><th>Actions</th></tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
            
            <!-- Empty State -->
            <div class="empty-state-modern" id="emptyState" style="display: none;">
                <div class="empty-icon-modern"><i class="fas fa-puzzle-piece"></i></div>
                <div class="empty-title-modern">Aucun module trouvé</div>
                <div class="empty-text-modern">Aucun module ne correspond à vos critères de recherche.</div>
                <button class="btn btn-primary" id="resetFiltersBtn"><i class="fas fa-redo me-2"></i>Réinitialiser</button>
            </div>
        </div>
        
        <!-- Pagination -->
        <div class="pagination-container-modern">
            <nav><ul class="pagination justify-content-center" id="pagination"></ul></nav>
        </div>
    </div>
    
    <!-- Category Navigation -->
    <div class="categories-nav">
        <button class="category-filter active" data-category="all"><i class="fas fa-th-large me-2"></i>Tous</button>
        @foreach($categories as $category)
            <button class="category-filter" data-category="{{ $category->id }}">
                <i class="{{ $category->icon }} me-2"></i>{{ $category->name }}
            </button>
        @endforeach
    </div>
    
    <!-- Floating Action Button -->
    <button class="fab-modern" data-bs-toggle="modal" data-bs-target="#addModuleModal"><i class="fas fa-plus"></i></button>
</main>

<!-- ADD MODULE MODAL -->
<div class="modal fade" id="addModuleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content modern-modal">
            <div class="modal-header border-0">
                <h5 class="modal-title"><i class="fas fa-plus-circle me-2" style="color: var(--primary-color);"></i>Ajouter un module</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="addModuleForm">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label-modern required">Nom <span class="text-danger"></span></label>
                            <input type="text" class="form-control-modern" name="name" required placeholder="Ex: Analytics Pro">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label-modern required">Version <span class="text-danger"></span></label>
                            <input type="text" class="form-control-modern" name="version" required placeholder="Ex: 1.0.0">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label-modern required">Description <span class="text-danger"></span></label>
                            <textarea class="form-control-modern" name="description" rows="3" required placeholder="Description détaillée..."></textarea>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label-modern required">Auteur <span class="text-danger"></span></label>
                            <input type="text" class="form-control-modern" name="author" required placeholder="Ex: John Doe">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label-modern">Site web</label>
                            <input type="url" class="form-control-modern" name="author_website" placeholder="https://example.com">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label-modern required">Type <span class="text-danger"></span></label>
                            <select class="form-select-modern" name="type" required>
                                <option value="">Sélectionner</option>
                                <option value="official">Officiel</option>
                                <option value="third-party">Tiers</option>
                                <option value="custom">Personnalisé</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label-modern required">Catégorie <span class="text-danger"></span></label>
                            <select class="form-select-modern" name="category_id" required>
                                <option value="">Sélectionner</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label-modern required">Type de prix <span class="text-danger"></span></label>
                            <select class="form-select-modern" name="price_type" id="priceType" required>
                                <option value="free">Gratuit</option>
                                <option value="paid">Payant</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3" id="priceField" style="display: none;">
                            <label class="form-label-modern">Prix (€)</label>
                            <input type="number" class="form-control-modern" name="price" step="0.01" placeholder="Ex: 49.99">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label-modern">Icône</label>
                            <input type="text" class="form-control-modern" name="icon" value="fas fa-puzzle-piece" placeholder="fas fa-chart-bar">
                            <small class="text-muted">Classe Font Awesome</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label-modern">Documentation</label>
                            <input type="url" class="form-control-modern" name="documentation_url" placeholder="https://docs.example.com">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label-modern">URL de démo</label>
                            <input type="url" class="form-control-modern" name="documentation_url" placeholder="https://demo.example.com">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fas fa-times me-2"></i>Annuler</button>
                <button type="button" class="btn btn-primary" id="submitAddModuleBtn"><i class="fas fa-save me-2"></i>Ajouter</button>
            </div>
        </div>
    </div>
</div>

<!-- DELETE CONFIRMATION MODAL -->
<div class="modal fade" id="deleteConfirmationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center py-4">
                <div class="delete-icon"><i class="fas fa-exclamation-triangle"></i></div>
                <h4 class="delete-title">Confirmer la suppression</h4>
                <p class="delete-message">Êtes-vous sûr de vouloir supprimer ce module ? Cette action est irréversible.</p>
                <div class="module-to-delete" id="moduleToDeleteInfo"></div>
                <div class="alert alert-warning"><i class="fas fa-exclamation-circle me-2"></i><strong>Attention :</strong> Action irréversible.</div>
            </div>
            <div class="modal-footer border-0 justify-content-center">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fas fa-times me-2"></i>Annuler</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn"><i class="fas fa-trash me-2"></i>Supprimer</button>
            </div>
        </div>
    </div>
</div>

<script>
// ============================================
// CONFIGURATION
// ============================================
let currentView = 'grid';
let currentPage = 1;
let itemsPerPage = 9;
let totalPlugins = 0;
let currentPluginData = [];

const API = {
    getPlugins: '{{ route("modules.get-plugins") }}',
    getStats: '{{ route("modules.stats") }}',
    storePlugin: '{{ route("modules.store") }}',
    updatePlugin: '{{ route("modules.update", "") }}',
    deletePlugin: '{{ route("modules.destroy", "") }}',
    activatePlugin: '{{ route("modules.activate", "") }}',
    deactivatePlugin: '{{ route("modules.deactivate", "") }}',
};

let currentFilters = {
    category: '', status: '', type: '', price: '', sort_by: 'name', sort_order: 'asc', search: ''
};

// ============================================
// INITIALIZATION
// ============================================
document.addEventListener('DOMContentLoaded', function() {
    setupEventListeners();
    loadPlugins();
    loadStats();
});

function setupEventListeners() {
    document.getElementById('gridViewBtn').addEventListener('click', () => switchView('grid'));
    document.getElementById('listViewBtn').addEventListener('click', () => switchView('list'));
    document.getElementById('searchInput').addEventListener('input', debounce(handleSearch, 300));
    document.getElementById('applyFiltersBtn').addEventListener('click', applyFilters);
    document.getElementById('clearFiltersBtn').addEventListener('click', clearFilters);
    document.getElementById('resetFiltersBtn').addEventListener('click', () => { clearFilters(); document.getElementById('emptyState').style.display = 'none'; });
    document.getElementById('toggleFilterBtn').addEventListener('click', toggleFilterSection);
    document.getElementById('confirmDeleteBtn').addEventListener('click', confirmDelete);
    document.getElementById('submitAddModuleBtn').addEventListener('click', submitModule);
    document.getElementById('priceType').addEventListener('change', togglePriceField);
    
    document.querySelectorAll('.category-filter').forEach(btn => {
        btn.addEventListener('click', () => filterByCategory(btn.dataset.category));
    });
}

// ============================================
// LOAD DATA
// ============================================
async function loadPlugins() {
    showLoading();
    try {
        const params = new URLSearchParams({...currentFilters, page: currentPage, per_page: itemsPerPage});
        const response = await fetch(`${API.getPlugins}?${params}`);
        const result = await response.json();
        
        if (result.success) {
            currentPluginData = result.data;
            totalPlugins = result.total || result.data.length;
            renderPlugins(currentPluginData);
            updateVisibleCount(currentPluginData.length);
            renderPagination();
            
            const isEmpty = currentPluginData.length === 0;
            document.getElementById('emptyState').style.display = isEmpty ? 'block' : 'none';
            document.getElementById('modulesGridView').style.display = isEmpty ? 'none' : 'grid';
            document.getElementById('modulesListView').style.display = (isEmpty || currentView === 'grid') ? 'none' : 'block';
        }
    } catch (error) {
        showAlert('danger', 'Erreur de chargement');
    } finally {
        hideLoading();
    }
}

async function loadStats() {
    try {
        const response = await fetch(API.getStats);
        const result = await response.json();
        if (result.success) {
            document.getElementById('totalModules').textContent = result.data.total;
            document.getElementById('activeModules').textContent = result.data.active;
            document.getElementById('inactiveModules').textContent = result.data.inactive;
            document.getElementById('freeModules').textContent = result.data.free;
            document.getElementById('updatesAvailable').textContent = result.data.updates_available || 0;
        }
    } catch (error) {
        console.error('Error loading stats:', error);
    }
}

// ============================================
// RENDER FUNCTIONS
// ============================================
function renderPlugins(plugins) {
    document.getElementById('modulesGridView').innerHTML = plugins.map(p => renderCard(p)).join('');
    document.querySelector('#modulesListView tbody').innerHTML = plugins.map(p => renderRow(p)).join('');
    attachEventListeners();
}

function renderCard(plugin) {
    return `
        <div class="module-card" data-id="${plugin.id}">
            <div class="module-card-header">
                <div class="module-icon" style="background: ${getGradient(plugin.type)};"><i class="${plugin.icon || 'fas fa-puzzle-piece'}"></i></div>
                <div class="module-badges">${getTypeBadge(plugin.type)}${plugin.price_type === 'paid' ? '<span class="badge-paid">Payant</span>' : ''}</div>
            </div>
            <div class="module-card-body">
                <h4 class="module-name">${escapeHtml(plugin.name)}</h4>
                <p class="module-description">${escapeHtml(plugin.description)}</p>
                <div class="module-meta">
                    <div class="meta-item"><i class="fas fa-code-branch"></i><span>v${plugin.version}</span></div>
                    <div class="meta-item"><i class="fas fa-user"></i><span>${escapeHtml(plugin.author)}</span></div>
                    <div class="meta-item"><i class="fas fa-calendar"></i><span>${formatDate(plugin.installed_at)}</span></div>
                </div>
                <div class="module-rating">${getStars(plugin.rating)}<span class="rating-count">(${plugin.rating_count})</span></div>
            </div>
            <div class="module-card-footer">
                <div class="module-status">${getStatusBadge(plugin.status)}</div>
                <div class="module-actions">${getActionButtons(plugin)}</div>
            </div>
        </div>
    `;
}

function renderRow(plugin) {
    return `
        <tr data-id="${plugin.id}">
            <td><div class="list-module-info"><div class="list-module-icon" style="background: ${getGradient(plugin.type)};"><i class="${plugin.icon || 'fas fa-puzzle-piece'}"></i></div><div><div class="list-module-name">${escapeHtml(plugin.name)}${plugin.type === 'official' ? '<span class="badge-official-sm">Officiel</span>' : ''}</div><div class="list-module-description">${escapeHtml(plugin.description.substring(0, 80))}...</div></div></div></td>
            <td>v${plugin.version}</td><td>${escapeHtml(plugin.author)}</td><td>${formatDate(plugin.installed_at)}</td>
            <td>${getStatusBadge(plugin.status)}</td>
            <td><div class="list-actions">${getActionButtons(plugin, 'list')}</div></td>
        </tr>
    `;
}

function getActionButtons(plugin, view = 'grid') {
    const btnClass = view === 'grid' ? 'action-btn' : 'list-action-btn';
    const isCore = plugin.type === 'core';
    
    if (isCore) {
        const viewLink = plugin.documentation_url ? plugin.documentation_url : '#';
        return `<a href="${viewLink}" class="${btnClass} view-btn" title="Voir"><i class="fas fa-eye"></i></a>`;
    }
    
    const actionBtn = plugin.status === 'active' 
        ? `<button class="${btnClass} deactivate-btn" data-id="${plugin.id}" title="Désactiver"><i class="fas fa-pause"></i></button>`
        : `<button class="${btnClass} activate-btn" data-id="${plugin.id}" title="Activer"><i class="fas fa-play"></i></button>`;
    
    const viewLink = plugin.documentation_url ? plugin.documentation_url : '#';
    
    return `${actionBtn}
            <a href="${viewLink}" class="${btnClass} view-btn" title="Voir"><i class="fas fa-eye"></i></a>
            <button class="${btnClass} delete-btn" data-id="${plugin.id}" title="Supprimer"><i class="fas fa-trash"></i></button>`;
}

function renderPagination() {
    const totalPages = Math.ceil(totalPlugins / itemsPerPage);
    const container = document.getElementById('pagination');
    if (totalPages <= 1) { container.innerHTML = ''; return; }
    
    let html = `<li class="page-item ${currentPage === 1 ? 'disabled' : ''}"><button class="page-link" data-page="${currentPage - 1}"><i class="fas fa-chevron-left"></i></button></li>`;
    
    for (let i = Math.max(1, currentPage - 2); i <= Math.min(totalPages, currentPage + 2); i++) {
        html += `<li class="page-item ${currentPage === i ? 'active' : ''}"><button class="page-link" data-page="${i}">${i}</button></li>`;
    }
    
    html += `<li class="page-item ${currentPage === totalPages ? 'disabled' : ''}"><button class="page-link" data-page="${currentPage + 1}"><i class="fas fa-chevron-right"></i></button></li>`;
    container.innerHTML = html;
    
    container.querySelectorAll('.page-link[data-page]').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            const page = parseInt(btn.dataset.page);
            if (!isNaN(page) && page !== currentPage && page >= 1 && page <= totalPages) {
                currentPage = page;
                loadPlugins();
            }
        });
    });
}

// ============================================
// HELPER FUNCTIONS
// ============================================
function getStars(rating) {
    let stars = '';
    for (let i = 1; i <= 5; i++) {
        stars += i <= rating ? '<i class="fas fa-star star-filled"></i>' : (i - 0.5 <= rating ? '<i class="fas fa-star-half-alt star-filled"></i>' : '<i class="fas fa-star star-empty"></i>');
    }
    return stars;
}

function getStatusBadge(status) {
    const badges = { active: '<span class="status-badge status-active"><i class="fas fa-circle"></i> Actif</span>', inactive: '<span class="status-badge status-inactive"><i class="fas fa-circle"></i> Inactif</span>', pending: '<span class="status-badge status-pending"><i class="fas fa-circle"></i> En attente</span>' };
    return badges[status] || badges.inactive;
}

function getTypeBadge(type) {
    const badges = { core: '<span class="badge-core">Cœur</span>', official: '<span class="badge-official">Officiel</span>', custom: '<span class="badge-core">Personnalisé</span>' };
    return badges[type] || '';
}

function getGradient(type) {
    const gradients = { core: 'linear-gradient(135deg, #6366f1, #8b5cf6)', official: 'linear-gradient(135deg, #10b981, #059669)', 'third-party': 'linear-gradient(135deg, #f59e0b, #d97706)', custom: 'linear-gradient(135deg, #3b82f6, #2563eb)' };
    return gradients[type] || 'linear-gradient(135deg, #6b7280, #4b5563)';
}

function formatDate(dateString) {
    if (!dateString) return 'N/A';
    return new Date(dateString).toLocaleDateString('fr-FR');
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function updateVisibleCount(count) {
    document.getElementById('visibleModulesCount').textContent = count;
}

function togglePriceField() {
    document.getElementById('priceField').style.display = document.getElementById('priceType').value === 'paid' ? 'block' : 'none';
}

// ============================================
// API CALLS
// ============================================
async function activatePlugin(id) { await callApi(`${API.activatePlugin}/${id}`, 'POST', 'activé'); }
async function deactivatePlugin(id) { await callApi(`${API.deactivatePlugin}/${id}`, 'POST', 'désactivé'); }
async function deletePlugin(id) { await callApi(`${API.deletePlugin}/${id}`, 'DELETE', 'supprimé'); }

async function callApi(url, method, successMsg) {
    showLoading();
    try {
        const response = await fetch(url, { method, headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' } });
        const result = await response.json();
        if (result.success) {
            showAlert('success', `Module ${successMsg} avec succès`);
            await loadPlugins();
            await loadStats();
        } else {
            showAlert('danger', result.message);
        }
    } catch (error) {
        showAlert('danger', `Erreur lors de la ${successMsg}`);
    } finally {
        hideLoading();
    }
}

async function submitModule() {
    const form = document.getElementById('addModuleForm');
    const formData = new FormData(form);
    const data = Object.fromEntries(formData.entries());
    
    if (!data.name || !data.version || !data.description || !data.author || !data.type || !data.category_id) {
        showAlert('danger', 'Veuillez remplir tous les champs obligatoires');
        return;
    }
    if (data.price_type === 'paid' && (!data.price || data.price <= 0)) {
        showAlert('danger', 'Veuillez entrer un prix valide');
        return;
    }
    
    showLoading();
    try {
        const response = await fetch(API.storePlugin, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json', 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        });
        const result = await response.json();
        if (result.success) {
            showAlert('success', 'Module ajouté avec succès');
            bootstrap.Modal.getInstance(document.getElementById('addModuleModal')).hide();
            form.reset();
            document.getElementById('priceField').style.display = 'none';
            await loadPlugins();
            await loadStats();
        } else {
            showAlert('danger', result.message);
        }
    } catch (error) {
        showAlert('danger', 'Erreur lors de l\'ajout');
    } finally {
        hideLoading();
    }
}

// ============================================
// UI CONTROLS
// ============================================
function switchView(view) {
    currentView = view;
    document.getElementById('modulesGridView').style.display = view === 'grid' ? 'grid' : 'none';
    document.getElementById('modulesListView').style.display = view === 'list' ? 'block' : 'none';
    document.getElementById('gridViewBtn').classList.toggle('active', view === 'grid');
    document.getElementById('listViewBtn').classList.toggle('active', view === 'list');
}

function handleSearch(e) {
    currentFilters.search = e.target.value.toLowerCase();
    currentPage = 1;
    loadPlugins();
}

function filterByCategory(categoryId) {
    document.querySelectorAll('.category-filter').forEach(btn => btn.classList.toggle('active', btn.dataset.category === categoryId));
    currentFilters.category = categoryId === 'all' ? '' : categoryId;
    currentPage = 1;
    loadPlugins();
}

function applyFilters() {
    currentFilters.category = document.getElementById('filterCategory').value;
    currentFilters.status = document.getElementById('filterStatus').value;
    currentFilters.type = document.getElementById('filterType').value;
    currentFilters.price = document.getElementById('filterPrice').value;
    currentFilters.sort_by = document.getElementById('filterSortBy').value;
    currentPage = 1;
    loadPlugins();
}

function clearFilters() {
    document.getElementById('filterCategory').value = '';
    document.getElementById('filterStatus').value = '';
    document.getElementById('filterType').value = '';
    document.getElementById('filterPrice').value = '';
    document.getElementById('filterSortBy').value = 'name';
    currentFilters = { category: '', status: '', type: '', price: '', sort_by: 'name', sort_order: 'asc', search: currentFilters.search || '' };
    currentPage = 1;
    document.querySelectorAll('.category-filter').forEach(btn => btn.classList.toggle('active', btn.dataset.category === 'all'));
    loadPlugins();
}

function toggleFilterSection() {
    const section = document.getElementById('filterSection');
    const btn = document.getElementById('toggleFilterBtn');
    const isHidden = section.style.display === 'none';
    section.style.display = isHidden ? 'block' : 'none';
    btn.innerHTML = isHidden ? '<i class="fas fa-times me-2"></i>Masquer les filtres' : '<i class="fas fa-sliders-h me-2"></i>Filtres';
}

function confirmDelete() {
    if (window.currentDeleteId) {
        deletePlugin(window.currentDeleteId);
        bootstrap.Modal.getInstance(document.getElementById('deleteConfirmationModal')).hide();
    }
}

function attachEventListeners() {
    document.querySelectorAll('.activate-btn').forEach(btn => btn.addEventListener('click', (e) => { e.preventDefault(); activatePlugin(btn.dataset.id); }));
    document.querySelectorAll('.deactivate-btn').forEach(btn => btn.addEventListener('click', (e) => { e.preventDefault(); deactivatePlugin(btn.dataset.id); }));
    document.querySelectorAll('.delete-btn').forEach(btn => btn.addEventListener('click', (e) => { e.preventDefault(); window.currentDeleteId = btn.dataset.id; new bootstrap.Modal(document.getElementById('deleteConfirmationModal')).show(); }));
}

// ============================================
// UTILITIES
// ============================================
function debounce(func, wait) {
    let timeout;
    return function(...args) {
        clearTimeout(timeout);
        timeout = setTimeout(() => func(...args), wait);
    };
}

function showLoading() { document.getElementById('loadingSpinner').style.display = 'flex'; }
function hideLoading() { document.getElementById('loadingSpinner').style.display = 'none'; }

function showAlert(type, message) {
    const alert = document.createElement('div');
    alert.className = `alert alert-${type} alert-dismissible fade show position-fixed top-0 end-0 m-3`;
    alert.style.cssText = 'z-index: 9999; min-width: 300px; box-shadow: 0 4px 12px rgba(0,0,0,0.15);';
    alert.innerHTML = `${message}<button type="button" class="btn-close" data-bs-dismiss="alert"></button>`;
    document.body.appendChild(alert);
    setTimeout(() => alert.remove(), 5000);
}
</script>
@endsection