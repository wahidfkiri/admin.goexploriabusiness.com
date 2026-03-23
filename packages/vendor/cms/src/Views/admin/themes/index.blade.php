@extends('layouts.app')

@section('content')
<div class="dashboard-content">
    <!-- Page Header -->
    <div class="page-header">
        <div>
            <h1 class="page-title">
                <span class="page-title-icon"><i class="fas fa-palette"></i></span>
                Gestion des thèmes
            </h1>
            <p class="page-description">Personnalisez l'apparence de votre site</p>
        </div>
        
        <div class="page-actions">
            <button class="btn btn-primary" onclick="showUploadModal()">
                <i class="fas fa-upload me-2"></i>Uploader un thème
            </button>
        </div>
    </div>
    
    <!-- Stats Cards -->
    <div class="stats-grid-modern">
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #4361ee, #3a56e4);">
                <i class="fas fa-palette"></i>
            </div>
            <div class="stat-info">
                <h3 class="stat-value">{{ $stats['total'] }}</h3>
                <p class="stat-label">Thèmes installés</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #10b981, #059669);">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-info">
                <h3 class="stat-value">{{ $stats['active'] }}</h3>
                <p class="stat-label">Thème actif</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
                <i class="fas fa-box"></i>
            </div>
            <div class="stat-info">
                <h3 class="stat-value">{{ $stats['available'] }}</h3>
                <p class="stat-label">Thèmes disponibles</p>
            </div>
        </div>
    </div>
    
    <!-- Themes Grid -->
    <div class="themes-container-modern">
        <div class="themes-grid" id="themesGrid">
            @foreach($themes as $theme)
                @include('cms::admin.themes.partials.theme-card', ['theme' => $theme, 'activeTheme' => $activeTheme])
            @endforeach
        </div>
        
        @if($themes->isEmpty())
            <div class="empty-state-modern">
                <div class="empty-icon">
                    <i class="fas fa-palette"></i>
                </div>
                <h3>Aucun thème installé</h3>
                <p>Commencez par uploader votre premier thème</p>
                <button class="btn btn-primary" onclick="showUploadModal()">
                    <i class="fas fa-upload me-2"></i>Uploader un thème
                </button>
            </div>
        @endif
        
        <!-- Pagination -->
        @if($themes->hasPages())
            <div class="pagination-modern">
                {{ $themes->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Upload Modal -->
@include('cms::admin.themes.partials.upload-modal')

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteThemeModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center py-4">
                <div class="delete-icon-modern">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <h4>Supprimer le thème</h4>
                <p class="text-muted">Êtes-vous sûr de vouloir supprimer ce thème ?<br>Cette action est irréversible.</p>
                <div class="mt-4">
                    <button class="btn btn-secondary me-2" data-bs-dismiss="modal">Annuler</button>
                    <button class="btn btn-danger" id="confirmDeleteBtn">Supprimer</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Loading Overlay -->
<div class="loading-overlay" id="loadingOverlay" style="display: none;">
    <div class="spinner-modern">
        <div class="spinner"></div>
        <p>Chargement...</p>
    </div>
</div>

<style>
/* Modern Stats Cards */
.stats-grid-modern {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.stat-card {
    background: white;
    border-radius: 20px;
    padding: 20px;
    display: flex;
    align-items: center;
    gap: 20px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    transition: all 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 24px rgba(0,0,0,0.1);
}

.stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.8rem;
    color: white;
}

.stat-info {
    flex: 1;
}

.stat-value {
    font-size: 1.8rem;
    font-weight: 700;
    margin: 0;
    color: #1e293b;
}

.stat-label {
    margin: 0;
    color: #64748b;
    font-size: 0.85rem;
}

/* Themes Grid */
.themes-container-modern {
    background: white;
    border-radius: 20px;
    padding: 24px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}

.themes-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 24px;
}

/* Empty State */
.empty-state-modern {
    text-align: center;
    padding: 80px 20px;
}

.empty-icon {
    width: 100px;
    height: 100px;
    background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 24px;
}

.empty-icon i {
    font-size: 3rem;
    color: #94a3b8;
}

.empty-state-modern h3 {
    margin-bottom: 8px;
    color: #1e293b;
}

.empty-state-modern p {
    color: #64748b;
    margin-bottom: 24px;
}

/* Loading Overlay */
.loading-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.7);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 9999;
}

.spinner-modern {
    background: white;
    padding: 30px;
    border-radius: 20px;
    text-align: center;
}

.spinner {
    width: 40px;
    height: 40px;
    border: 3px solid #e2e8f0;
    border-top-color: #4361ee;
    border-radius: 50%;
    animation: spin 0.8s linear infinite;
    margin: 0 auto 16px;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

/* Pagination */
.pagination-modern {
    margin-top: 30px;
    text-align: center;
}

/* Responsive */
@media (max-width: 768px) {
    .themes-grid {
        grid-template-columns: 1fr;
    }
    
    .stat-card {
        padding: 16px;
    }
    
    .stat-icon {
        width: 48px;
        height: 48px;
        font-size: 1.4rem;
    }
    
    .stat-value {
        font-size: 1.4rem;
    }
}
</style>
<script>
// ============================================
// VARIABLES GLOBALES
// ============================================
let currentDeleteThemeId = null;
let currentThemeId = null;

// ============================================
// MODAL FUNCTIONS
// ============================================

/**
 * Affiche le modal d'upload de thème
 */
function showUploadModal() {
    // Vérifier si le modal existe déjà
    let modalElement = document.getElementById('uploadThemeModal');
    
    if (!modalElement) {
        // Créer le modal s'il n'existe pas
        createUploadModal();
        modalElement = document.getElementById('uploadThemeModal');
    }
    
    // Réinitialiser le formulaire
    const form = document.getElementById('uploadThemeForm');
    if (form) {
        form.reset();
    }
    
    // Réinitialiser l'affichage
    const fileInfo = document.getElementById('fileInfo');
    const themeNameField = document.getElementById('themeNameField');
    const uploadBtn = document.getElementById('uploadBtn');
    
    if (fileInfo) fileInfo.style.display = 'none';
    if (themeNameField) themeNameField.style.display = 'none';
    if (uploadBtn) uploadBtn.disabled = true;
    
    // Afficher le modal
    const modal = new bootstrap.Modal(modalElement);
    modal.show();
}

/**
 * Crée le modal d'upload s'il n'existe pas
 */
function createUploadModal() {
    const modalHTML = `
        <div class="modal fade" id="uploadThemeModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title">Uploader un thème</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form id="uploadThemeForm" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="upload-area" id="uploadArea">
                                <div class="upload-icon">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                </div>
                                <h4>Glissez votre fichier ici</h4>
                                <p>ou cliquez pour sélectionner</p>
                                <input type="file" name="theme_file" id="themeFile" accept=".zip" hidden>
                                <div class="file-info" id="fileInfo" style="display: none;">
                                    <i class="fas fa-file-archive"></i>
                                    <span id="fileName"></span>
                                    <button type="button" class="remove-file" onclick="removeFile()">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                                <button type="button" class="btn-select-file" onclick="document.getElementById('themeFile').click()">
                                    Sélectionner un fichier
                                </button>
                            </div>
                            
                            <div class="mb-3 mt-3" id="themeNameField" style="display: none;">
                                <label class="form-label">Nom du thème</label>
                                <input type="text" class="form-control" name="name" id="themeName" placeholder="Mon super thème">
                            </div>
                        </div>
                        <div class="modal-footer border-0">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                            <button type="submit" class="btn btn-primary" id="uploadBtn" disabled>
                                <i class="fas fa-upload me-2"></i>Uploader
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    `;
    
    document.body.insertAdjacentHTML('beforeend', modalHTML);
    
    // Ajouter les event listeners après création
    initUploadModalEvents();
}

/**
 * Initialise les événements du modal d'upload
 */
function initUploadModalEvents() {
    const uploadArea = document.getElementById('uploadArea');
    const themeFile = document.getElementById('themeFile');
    const fileInfo = document.getElementById('fileInfo');
    const fileName = document.getElementById('fileName');
    const themeNameField = document.getElementById('themeNameField');
    const themeName = document.getElementById('themeName');
    const uploadBtn = document.getElementById('uploadBtn');
    
    if (!uploadArea) return;
    
    // Drag and drop events
    uploadArea.addEventListener('dragover', (e) => {
        e.preventDefault();
        uploadArea.classList.add('drag-over');
    });
    
    uploadArea.addEventListener('dragleave', () => {
        uploadArea.classList.remove('drag-over');
    });
    
    uploadArea.addEventListener('drop', (e) => {
        e.preventDefault();
        uploadArea.classList.remove('drag-over');
        
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            handleFile(files[0]);
        }
    });
    
    // File selection
    if (themeFile) {
        themeFile.addEventListener('change', (e) => {
            if (e.target.files.length > 0) {
                handleFile(e.target.files[0]);
            }
        });
    }
    
    // Form submission
    const form = document.getElementById('uploadThemeForm');
    if (form) {
        form.addEventListener('submit', handleUploadSubmit);
    }
}

/**
 * Gère le fichier sélectionné
 */
function handleFile(file) {
    if (!file.name.endsWith('.zip')) {
        showToast('Veuillez sélectionner un fichier ZIP', 'error');
        return;
    }
    
    const fileInfo = document.getElementById('fileInfo');
    const fileName = document.getElementById('fileName');
    const themeNameField = document.getElementById('themeNameField');
    const themeName = document.getElementById('themeName');
    const uploadBtn = document.getElementById('uploadBtn');
    
    // Display file info
    if (fileName) fileName.textContent = file.name;
    if (fileInfo) fileInfo.style.display = 'flex';
    if (themeNameField) themeNameField.style.display = 'block';
    
    // Auto-populate theme name from filename
    let name = file.name.replace('.zip', '');
    name = name.replace(/[_-]/g, ' ');
    name = name.split(' ').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ');
    if (themeName) themeName.value = name;
    
    if (uploadBtn) uploadBtn.disabled = false;
}

/**
 * Supprime le fichier sélectionné
 */
function removeFile() {
    const themeFile = document.getElementById('themeFile');
    const fileInfo = document.getElementById('fileInfo');
    const themeNameField = document.getElementById('themeNameField');
    const uploadBtn = document.getElementById('uploadBtn');
    
    if (themeFile) themeFile.value = '';
    if (fileInfo) fileInfo.style.display = 'none';
    if (themeNameField) themeNameField.style.display = 'none';
    if (uploadBtn) uploadBtn.disabled = true;
}

/**
 * Gère la soumission du formulaire d'upload
 */
async function handleUploadSubmit(e) {
    e.preventDefault();
    
    const themeFile = document.getElementById('themeFile');
    const themeName = document.getElementById('themeName');
    
    if (!themeFile || !themeFile.files.length) {
        showToast('Veuillez sélectionner un fichier', 'error');
        return;
    }
    
    const formData = new FormData();
    formData.append('theme_file', themeFile.files[0]);
    formData.append('name', themeName ? themeName.value : '');
    
    showLoading();
    
    try {
        const response = await fetch('/admin/cms/themes', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: formData
        });
        
        const data = await response.json();
        
        hideLoading();
        
        if (data.success) {
            // Close modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('uploadThemeModal'));
            if (modal) modal.hide();
            
            // Add new theme card to grid
            const themesGrid = document.getElementById('themesGrid');
            if (themesGrid) {
                themesGrid.insertAdjacentHTML('beforeend', data.html);
            }
            
            // Update stats
            updateStats();
            
            showToast(data.message, 'success');
            
            // Reset form
            removeFile();
        } else {
            showToast(data.message || 'Erreur lors de l\'upload', 'error');
        }
    } catch (error) {
        hideLoading();
        console.error('Upload error:', error);
        showToast('Erreur lors de l\'upload', 'error');
    }
}

// ============================================
// THEME MANAGEMENT FUNCTIONS
// ============================================

/**
 * Aperçu du thème
 */
function previewTheme(themeId) {
    showLoading();
    
    const previewUrl = `/admin/cms/themes/${themeId}/preview`;
    window.open(previewUrl, '_blank');
    
    setTimeout(() => {
        hideLoading();
    }, 500);
}

/**
 * Activer un thème
 */
function activateTheme(themeId, button) {
    if (!confirm('Voulez-vous activer ce thème ?')) return;
    
    showLoading();
    
    fetch(`/admin/cms/themes/${themeId}/activate`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        if (data.success) {
            // Remove active class from all theme cards
            document.querySelectorAll('.theme-card').forEach(card => {
                card.classList.remove('active-theme');
                const activeBadge = card.querySelector('.active-badge');
                if (activeBadge) activeBadge.remove();
                const activateBtn = card.querySelector('.activate-btn');
                if (activateBtn) activateBtn.style.display = 'flex';
                const deactivateBtn = card.querySelector('.deactivate-btn');
                if (deactivateBtn) deactivateBtn.style.display = 'none';
            });
            
            // Add active class to selected theme card
            const themeCard = button.closest('.theme-card');
            if (themeCard) {
                themeCard.classList.add('active-theme');
                
                // Update active badge
                const actionsDiv = themeCard.querySelector('.theme-actions');
                if (actionsDiv && !themeCard.querySelector('.active-badge')) {
                    const activeBadge = document.createElement('div');
                    activeBadge.className = 'active-badge';
                    activeBadge.innerHTML = '<i class="fas fa-check-circle"></i> Actif';
                    actionsDiv.insertBefore(activeBadge, actionsDiv.firstChild);
                }
                
                // Update buttons
                const activateBtn = themeCard.querySelector('.activate-btn');
                const deactivateBtn = themeCard.querySelector('.deactivate-btn');
                if (activateBtn) activateBtn.style.display = 'none';
                if (deactivateBtn) deactivateBtn.style.display = 'flex';
            }
            
            updateStats();
            showToast(data.message, 'success');
        } else {
            showToast(data.message, 'error');
        }
    })
    .catch(error => {
        hideLoading();
        console.error('Error:', error);
        showToast('Erreur lors de l\'activation', 'error');
    });
}

/**
 * Désactiver un thème
 */
function deactivateTheme(themeId, button) {
    if (!confirm('Voulez-vous désactiver ce thème ?')) return;
    
    showLoading();
    
    fetch(`/admin/cms/themes/${themeId}/deactivate`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        if (data.success) {
            const themeCard = button.closest('.theme-card');
            if (themeCard) {
                themeCard.classList.remove('active-theme');
                const activeBadge = themeCard.querySelector('.active-badge');
                if (activeBadge) activeBadge.remove();
                
                const activateBtn = themeCard.querySelector('.activate-btn');
                const deactivateBtn = themeCard.querySelector('.deactivate-btn');
                if (activateBtn) activateBtn.style.display = 'flex';
                if (deactivateBtn) deactivateBtn.style.display = 'none';
            }
            
            updateStats();
            showToast(data.message, 'success');
        } else {
            showToast(data.message, 'error');
        }
    })
    .catch(error => {
        hideLoading();
        console.error('Error:', error);
        showToast('Erreur lors de la désactivation', 'error');
    });
}

/**
 * Supprimer un thème
 */
function deleteTheme(themeId, button) {
    currentDeleteThemeId = themeId;
    const modal = new bootstrap.Modal(document.getElementById('deleteThemeModal'));
    modal.show();
}

/**
 * Confirmer la suppression
 */
document.addEventListener('DOMContentLoaded', function() {
    const confirmBtn = document.getElementById('confirmDeleteBtn');
    if (confirmBtn) {
        confirmBtn.addEventListener('click', confirmDeleteTheme);
    }
});

function confirmDeleteTheme() {
    if (!currentDeleteThemeId) return;
    
    showLoading();
    
    fetch(`/admin/cms/themes/${currentDeleteThemeId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        if (data.success) {
            // Remove theme card
            const themeCard = document.querySelector(`.theme-card[data-theme-id="${currentDeleteThemeId}"]`);
            if (themeCard) {
                themeCard.remove();
            }
            
            // Close modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('deleteThemeModal'));
            if (modal) modal.hide();
            
            updateStats();
            showToast(data.message, 'success');
            
            // If no themes left, reload page to show empty state
            if (document.querySelectorAll('.theme-card').length === 0) {
                setTimeout(() => {
                    location.reload();
                }, 1000);
            }
        } else {
            showToast(data.message, 'error');
        }
    })
    .catch(error => {
        hideLoading();
        console.error('Error:', error);
        showToast('Erreur lors de la suppression', 'error');
    });
}

/**
 * Éditer un thème
 */
function editTheme(themeId) {
    showLoading();
    
    fetch(`/admin/cms/themes/${themeId}/edit`, {
        method: 'GET',
        headers: {
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        if (data.success) {
            showEditModal(data.theme);
        } else {
            showToast('Erreur lors du chargement du thème', 'error');
        }
    })
    .catch(error => {
        hideLoading();
        console.error('Error:', error);
        showToast('Erreur lors du chargement du thème', 'error');
    });
}

/**
 * Affiche le modal d'édition
 */
function showEditModal(theme) {
    // Créer ou récupérer le modal d'édition
    let modalElement = document.getElementById('editThemeModal');
    
    if (!modalElement) {
        createEditModal();
        modalElement = document.getElementById('editThemeModal');
    }
    
    // Remplir le formulaire
    const nameInput = document.getElementById('editThemeName');
    const descriptionInput = document.getElementById('editThemeDescription');
    const themeIdInput = document.getElementById('editThemeId');
    
    if (nameInput) nameInput.value = theme.name;
    if (descriptionInput) descriptionInput.value = theme.description || '';
    if (themeIdInput) themeIdInput.value = theme.id;
    
    // Afficher le modal
    const modal = new bootstrap.Modal(modalElement);
    modal.show();
}

/**
 * Crée le modal d'édition
 */
function createEditModal() {
    const modalHTML = `
        <div class="modal fade" id="editThemeModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Modifier le thème</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form id="editThemeForm">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <input type="hidden" id="editThemeId" name="id">
                            <div class="mb-3">
                                <label class="form-label">Nom du thème</label>
                                <input type="text" class="form-control" id="editThemeName" name="name" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea class="form-control" id="editThemeDescription" name="description" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                            <button type="submit" class="btn btn-primary">Enregistrer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    `;
    
    document.body.insertAdjacentHTML('beforeend', modalHTML);
    
    // Ajouter l'event listener du formulaire
    const form = document.getElementById('editThemeForm');
    if (form) {
        form.addEventListener('submit', handleEditSubmit);
    }
}

/**
 * Gère la soumission du formulaire d'édition
 */
async function handleEditSubmit(e) {
    e.preventDefault();
    
    const themeId = document.getElementById('editThemeId').value;
    const name = document.getElementById('editThemeName').value;
    const description = document.getElementById('editThemeDescription').value;
    
    showLoading();
    
    try {
        const response = await fetch(`/admin/cms/themes/${themeId}`, {
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ name, description })
        });
        
        const data = await response.json();
        
        hideLoading();
        
        if (data.success) {
            // Close modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('editThemeModal'));
            if (modal) modal.hide();
            
            // Update theme card
            const themeCard = document.querySelector(`.theme-card[data-theme-id="${themeId}"]`);
            if (themeCard) {
                const themeName = themeCard.querySelector('.theme-name');
                if (themeName) themeName.textContent = name;
                
                const themeDesc = themeCard.querySelector('.theme-description');
                if (themeDesc) themeDesc.textContent = description || 'Aucune description disponible';
            }
            
            showToast(data.message, 'success');
        } else {
            showToast(data.message, 'error');
        }
    } catch (error) {
        hideLoading();
        console.error('Error:', error);
        showToast('Erreur lors de la mise à jour', 'error');
    }
}

// ============================================
// UI HELPER FUNCTIONS
// ============================================

/**
 * Affiche le loading overlay
 */
function showLoading() {
    let overlay = document.getElementById('loadingOverlay');
    if (!overlay) {
        overlay = document.createElement('div');
        overlay.id = 'loadingOverlay';
        overlay.className = 'loading-overlay';
        overlay.innerHTML = `
            <div class="spinner-modern">
                <div class="spinner"></div>
                <p>Chargement...</p>
            </div>
        `;
        document.body.appendChild(overlay);
    }
    overlay.style.display = 'flex';
}

/**
 * Cache le loading overlay
 */
function hideLoading() {
    const overlay = document.getElementById('loadingOverlay');
    if (overlay) {
        overlay.style.display = 'none';
    }
}

/**
 * Affiche une notification toast
 */
function showToast(message, type = 'success') {
    // Remove existing toasts
    const existingToasts = document.querySelectorAll('.toast-notification');
    existingToasts.forEach(toast => toast.remove());
    
    // Create toast
    const toast = document.createElement('div');
    toast.className = `toast-notification ${type}`;
    toast.innerHTML = `
        <div class="toast-content">
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
            <span>${message}</span>
        </div>
    `;
    
    document.body.appendChild(toast);
    
    // Show toast
    setTimeout(() => {
        toast.classList.add('show');
    }, 100);
    
    // Hide and remove after 3 seconds
    setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => {
            toast.remove();
        }, 300);
    }, 3000);
}

/**
 * Met à jour les statistiques
 */
function updateStats() {
    const totalThemes = document.querySelectorAll('.theme-card').length;
    const activeTheme = document.querySelector('.theme-card.active-theme');
    const activeCount = activeTheme ? 1 : 0;
    const availableCount = totalThemes - activeCount;
    
    const totalElement = document.querySelector('.stat-value');
    if (totalElement) {
        const parent = totalElement.closest('.stat-card');
        if (parent && parent.querySelector('.stat-label').textContent === 'Thèmes installés') {
            totalElement.textContent = totalThemes;
        }
    }
    
    // Update stats in the DOM
    const statsValues = document.querySelectorAll('.stat-value');
    if (statsValues.length >= 3) {
        statsValues[0].textContent = totalThemes;
        statsValues[1].textContent = activeCount;
        statsValues[2].textContent = availableCount;
    }
}

/**
 * Rafraîchit la liste des thèmes
 */
function refreshThemes() {
    showLoading();
    location.reload();
}

// ============================================
// INITIALIZATION
// ============================================
document.addEventListener('DOMContentLoaded', function() {
    // Add toast styles if not exists
    if (!document.querySelector('#toast-styles')) {
        const toastStyles = document.createElement('style');
        toastStyles.id = 'toast-styles';
        toastStyles.textContent = `
            .toast-notification {
                position: fixed;
                bottom: 20px;
                right: 20px;
                background: white;
                border-radius: 12px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                transform: translateX(400px);
                transition: transform 0.3s ease;
                z-index: 10000;
                min-width: 280px;
            }
            
            .toast-notification.show {
                transform: translateX(0);
            }
            
            .toast-content {
                padding: 16px 20px;
                display: flex;
                align-items: center;
                gap: 12px;
                border-left: 4px solid;
                border-radius: 12px;
            }
            
            .toast-notification.success .toast-content {
                border-left-color: #10b981;
            }
            
            .toast-notification.success i {
                color: #10b981;
            }
            
            .toast-notification.error .toast-content {
                border-left-color: #ef4444;
            }
            
            .toast-notification.error i {
                color: #ef4444;
            }
            
            .loading-overlay {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0,0,0,0.7);
                display: none;
                align-items: center;
                justify-content: center;
                z-index: 9999;
            }
            
            .spinner-modern {
                background: white;
                padding: 30px;
                border-radius: 20px;
                text-align: center;
            }
            
            .spinner {
                width: 40px;
                height: 40px;
                border: 3px solid #e2e8f0;
                border-top-color: #4361ee;
                border-radius: 50%;
                animation: spin 0.8s linear infinite;
                margin: 0 auto 16px;
            }
            
            @keyframes spin {
                to { transform: rotate(360deg); }
            }
            
            .upload-area {
                border: 2px dashed #cbd5e1;
                border-radius: 16px;
                padding: 40px;
                text-align: center;
                cursor: pointer;
                transition: all 0.3s ease;
                background: #f8fafc;
            }
            
            .upload-area:hover {
                border-color: #4361ee;
                background: #f1f5f9;
            }
            
            .upload-area.drag-over {
                border-color: #10b981;
                background: #f0fdf4;
            }
            
            .upload-icon i {
                font-size: 3rem;
                color: #94a3b8;
                margin-bottom: 16px;
            }
            
            .upload-area h4 {
                margin: 0 0 8px 0;
                font-size: 1.1rem;
                color: #1e293b;
            }
            
            .upload-area p {
                color: #64748b;
                margin-bottom: 16px;
            }
            
            .btn-select-file {
                background: #f1f5f9;
                border: none;
                padding: 8px 20px;
                border-radius: 10px;
                color: #475569;
                cursor: pointer;
                transition: all 0.2s ease;
            }
            
            .btn-select-file:hover {
                background: #e2e8f0;
            }
            
            .file-info {
                background: #f1f5f9;
                padding: 12px;
                border-radius: 10px;
                display: flex;
                align-items: center;
                gap: 10px;
                margin-bottom: 16px;
            }
            
            .file-info i {
                font-size: 1.2rem;
                color: #10b981;
            }
            
            .file-info span {
                flex: 1;
                font-size: 0.9rem;
                color: #1e293b;
            }
            
            .remove-file {
                background: none;
                border: none;
                color: #ef4444;
                cursor: pointer;
                padding: 4px 8px;
                border-radius: 6px;
            }
            
            .remove-file:hover {
                background: #fee2e2;
            }
        `;
        document.head.appendChild(toastStyles);
    }
    
    // Add event listeners for dynamic buttons
    document.addEventListener('click', function(e) {
        // Activate button
        if (e.target.closest('.activate-btn')) {
            const button = e.target.closest('.activate-btn');
            const themeId = button.getAttribute('data-theme-id');
            if (themeId) {
                activateTheme(themeId, button);
            }
        }
        
        // Deactivate button
        if (e.target.closest('.deactivate-btn')) {
            const button = e.target.closest('.deactivate-btn');
            const themeId = button.getAttribute('data-theme-id');
            if (themeId) {
                deactivateTheme(themeId, button);
            }
        }
        
        // Preview button
        if (e.target.closest('.btn-preview')) {
            const button = e.target.closest('.btn-preview');
            const themeId = button.getAttribute('data-theme-id');
            if (themeId) {
                previewTheme(themeId);
            }
        }
        
        // Delete button
        if (e.target.closest('.btn-delete')) {
            const button = e.target.closest('.btn-delete');
            const themeId = button.getAttribute('data-theme-id');
            if (themeId) {
                deleteTheme(themeId, button);
            }
        }
        
        // Edit button
        if (e.target.closest('.btn-edit')) {
            const button = e.target.closest('.btn-edit');
            const themeId = button.getAttribute('data-theme-id');
            if (themeId) {
                editTheme(themeId);
            }
        }
    });
});

// Expose functions to global scope
window.showUploadModal = showUploadModal;
window.previewTheme = previewTheme;
window.activateTheme = activateTheme;
window.deactivateTheme = deactivateTheme;
window.deleteTheme = deleteTheme;
window.editTheme = editTheme;
window.showToast = showToast;
window.removeFile = removeFile;
</script>
@endsection