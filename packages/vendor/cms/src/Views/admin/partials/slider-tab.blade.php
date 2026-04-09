{{-- slider-tab.blade.php --}}
<div class="tab-pane fade" id="v-pills-slider" role="tabpanel">
    <div class="tab-content-header">
        <h3 class="tab-title">
            <i class="fas fa-sliders-h me-2" style="color: #45b7d1;"></i>
            Gestion du Slider
        </h3>
        <button class="btn btn-primary btn-sm" id="addSlideBtn">
            <i class="fas fa-plus me-1"></i>Ajouter un slide
        </button>
    </div>

    <div class="slider-stats mb-4">
        <div class="row">
            <div class="col-md-3">
                <div class="stat-mini-card">
                    <div class="stat-mini-value" id="totalSlides">0</div>
                    <div class="stat-mini-label">Total slides</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-mini-card">
                    <div class="stat-mini-value" id="activeSlides">0</div>
                    <div class="stat-mini-label">Slides actifs</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-mini-card">
                    <div class="stat-mini-value" id="imageSlides">0</div>
                    <div class="stat-mini-label">Images</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-mini-card">
                    <div class="stat-mini-value" id="videoSlides">0</div>
                    <div class="stat-mini-label">Vidéos</div>
                </div>
            </div>
        </div>
    </div>

    <div class="slider-filters mb-3">
        <div class="btn-group" role="group">
            <button type="button" class="btn btn-outline-secondary active" data-filter="all">Tous</button>
            <button type="button" class="btn btn-outline-secondary" data-filter="image">Images</button>
            <button type="button" class="btn btn-outline-secondary" data-filter="video">Vidéos</button>
            <button type="button" class="btn btn-outline-secondary" data-filter="active">Actifs</button>
            <button type="button" class="btn btn-outline-secondary" data-filter="inactive">Inactifs</button>
        </div>
        <div class="float-end">
            <button type="button" class="btn btn-outline-secondary btn-sm" id="refreshSliderBtn">
                <i class="fas fa-sync-alt me-1"></i>Rafraîchir
            </button>
        </div>
        <div class="clearfix"></div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th style="width: 40px"><i class="fas fa-grip-vertical"></i></th>
                    <th style="width: 120px">Aperçu</th>
                    <th>Titre</th>
                    <th>Sous-titre</th>
                    <th style="width: 80px">Type</th>
                    <th style="width: 80px">Statut</th>
                    <th style="width: 100px">Bouton</th>
                    <th style="width: 120px">Actions</th>
                </tr>
            </thead>
            <tbody id="sliderTableBody">
                <tr>
                    <td colspan="8" class="text-center py-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Chargement...</span>
                        </div>
                        <p class="mt-2">Chargement des slides...</p>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- Add/Edit Slide Modal -->
<div class="modal fade" id="slideModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="slideModalTitle">
                    <i class="fas fa-plus-circle me-2"></i>Ajouter un slide
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="slideForm" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="slide_id" id="slideId">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Type de média</label>
                                <div class="btn-group w-100" role="group">
                                    <input type="radio" class="btn-check" name="type" id="typeImage" value="image" checked>
                                    <label class="btn btn-outline-primary" for="typeImage">
                                        <i class="fas fa-image me-1"></i> Image
                                    </label>
                                    <input type="radio" class="btn-check" name="type" id="typeVideo" value="video">
                                    <label class="btn btn-outline-primary" for="typeVideo">
                                        <i class="fas fa-video me-1"></i> Vidéo
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Source</label>
                                <div class="btn-group w-100" role="group">
                                    <input type="radio" class="btn-check" name="source" id="sourceUpload" value="upload" checked>
                                    <label class="btn btn-outline-secondary" for="sourceUpload">
                                        <i class="fas fa-upload me-1"></i> Upload
                                    </label>
                                    <input type="radio" class="btn-check" name="source" id="sourceMedia" value="media">
                                    <label class="btn btn-outline-secondary" for="sourceMedia">
                                        <i class="fas fa-images me-1"></i> Médiathèque
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="uploadSection" class="mb-3">
                        <div class="upload-area" id="uploadArea">
                            <div class="upload-icon">
                                <i class="fas fa-cloud-upload-alt fa-3x"></i>
                            </div>
                            <h4>Glissez votre fichier ici</h4>
                            <p>ou cliquez pour sélectionner</p>
                            <input type="file" name="media_file" id="mediaFileInput" accept="image/*,video/mp4,video/webm,video/ogg" hidden>
                            <button type="button" class="btn btn-outline-primary" onclick="document.getElementById('mediaFileInput').click()">
                                Sélectionner un fichier
                            </button>
                        </div>
                        <div id="filePreview" class="mt-3" style="display: none;">
                            <div class="preview-container text-center">
                                <img id="imagePreview" src="" style="max-width: 100%; max-height: 200px; display: none; border-radius: 8px;">
                                <video id="videoPreview" controls style="max-width: 100%; max-height: 200px; display: none;"></video>
                                <div class="mt-2">
                                    <button type="button" class="btn btn-sm btn-danger" id="removeFileBtn">
                                        <i class="fas fa-trash"></i> Supprimer
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="mediaSection" class="mb-3" style="display: none;">
                        <label class="form-label">Sélectionner depuis la médiathèque</label>
                        <div class="media-selector">
                            <div class="media-grid" id="mediaGrid">
                                <div class="text-center py-3">
                                    <div class="spinner-border spinner-border-sm"></div> Chargement...
                                </div>
                            </div>
                            <button type="button" class="btn btn-outline-primary mt-2 w-100" id="openMediaLibraryBtn">
                                <i class="fas fa-folder-open"></i> Parcourir la médiathèque
                            </button>
                        </div>
                        <input type="hidden" name="media_id" id="selectedMediaId">
                        <div id="selectedMediaPreview" class="mt-3" style="display: none;">
                            <div class="alert alert-info">
                                <i class="fas fa-check-circle me-2"></i>
                                <span id="selectedMediaName"></span>
                            </div>
                        </div>
                    </div>

                    <div id="videoUrlSection" class="mb-3" style="display: none;">
                        <label class="form-label">URL de la vidéo externe (YouTube, Vimeo)</label>
                        <input type="text" class="form-control" name="video_url" id="videoUrl" placeholder="https://www.youtube.com/watch?v=... ou https://vimeo.com/...">
                        <small class="text-muted">Laissez vide pour uploader une vidéo locale</small>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">Titre</label>
                                <input type="text" class="form-control" name="title" id="slideTitle" placeholder="Titre du slide">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">Sous-titre</label>
                                <textarea class="form-control" name="subtitle" id="slideSubtitle" rows="2" placeholder="Sous-titre ou description"></textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Texte du bouton</label>
                                <input type="text" class="form-control" name="button_text" id="slideButtonText" placeholder="Ex: En savoir plus">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Lien du bouton</label>
                                <input type="text" class="form-control" name="button_link" id="slideButtonLink" placeholder="/page ou https://...">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary" id="saveSlideBtn">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteSlideModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center py-4">
                <div class="delete-icon">
                    <i class="fas fa-exclamation-triangle fa-3x text-danger"></i>
                </div>
                <h4>Supprimer le slide</h4>
                <p class="text-muted">Êtes-vous sûr de vouloir supprimer ce slide ?<br>Cette action est irréversible.</p>
                <div class="mt-4">
                    <button class="btn btn-secondary me-2" data-bs-dismiss="modal">Annuler</button>
                    <button class="btn btn-danger" id="confirmDeleteSlideBtn">Supprimer</button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.slider-stats .stat-mini-card {
    background: #f8f9fa;
    border-radius: 12px;
    padding: 20px;
    text-align: center;
    transition: all 0.3s ease;
}
.slider-stats .stat-mini-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}
.slider-filters .btn-group {
    margin-bottom: 20px;
}
.slider-filters .btn {
    border-radius: 20px;
    padding: 6px 16px;
}
.table th {
    background: #f8f9fa;
    border-bottom: 2px solid #e9ecef;
    font-weight: 600;
    padding: 12px;
}
.table td {
    vertical-align: middle;
    padding: 12px;
}
.slider-preview {
    width: 100px;
    height: 60px;
    border-radius: 8px;
    overflow: hidden;
    background: #f3f4f6;
    display: flex;
    align-items: center;
    justify-content: center;
}
.slider-preview img,
.slider-preview video {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.slider-preview .video-placeholder {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    font-size: 20px;
}
.type-badge {
    display: inline-block;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 500;
}
.type-badge.image {
    background: #dbeafe;
    color: #1e40af;
}
.type-badge.video {
    background: #fee2e2;
    color: #991b1b;
}
.status-badge {
    display: inline-block;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 500;
}
.status-badge.active {
    background: #d1fae5;
    color: #065f46;
}
.status-badge.inactive {
    background: #f3f4f6;
    color: #6b7280;
}
.btn-icon {
    width: 32px;
    height: 32px;
    padding: 0;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    margin: 0 2px;
}
.drag-handle-cell {
    cursor: move;
    color: #9ca3af;
    font-size: 18px;
    text-align: center;
}
.drag-handle-cell i {
    cursor: grab;
}
.drag-handle-cell i:active {
    cursor: grabbing;
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
.media-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(80px, 1fr));
    gap: 10px;
    max-height: 250px;
    overflow-y: auto;
    padding: 10px;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    background: #f9fafb;
}
.media-item {
    cursor: pointer;
    border-radius: 8px;
    overflow: hidden;
    transition: all 0.2s ease;
    border: 2px solid transparent;
    background: white;
}
.media-item:hover {
    transform: scale(1.02);
}
.media-item.selected {
    border-color: #4361ee;
    box-shadow: 0 0 0 2px rgba(67, 97, 238, 0.2);
}
.media-item img,
.media-item video {
    width: 100%;
    height: 60px;
    object-fit: cover;
}
.media-item .media-info {
    padding: 4px;
    font-size: 0.65rem;
    text-align: center;
    background: white;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.delete-icon {
    margin-bottom: 20px;
}
tr.dragging {
    opacity: 0.5;
    background: #e5e7eb;
}
.youtube-thumbnail {
    position: relative;
    width: 100%;
    height: 100%;
    background-size: cover;
    background-position: center;
}
@media (max-width: 768px) {
    .table {
        font-size: 0.85rem;
    }
    .slider-preview {
        width: 70px;
        height: 45px;
    }
    .btn-icon {
        width: 28px;
        height: 28px;
    }
}
</style>

<script>
let sliderItems = [];
let deleteId = null;

document.addEventListener('DOMContentLoaded', function() {
    // Vérifier que currentEtablissementId est défini
    if (typeof currentEtablissementId === 'undefined') {
        console.error('currentEtablissementId is not defined');
        showToast('Erreur: ID établissement non défini', 'error');
        return;
    }
    loadSliders();
    initEventListeners();
});

function initEventListeners() {
    const addBtn = document.getElementById('addSlideBtn');
    if (addBtn) {
        addBtn.addEventListener('click', () => {
            resetForm();
            document.getElementById('slideModalTitle').innerHTML = '<i class="fas fa-plus-circle me-2"></i>Ajouter un slide';
            new bootstrap.Modal(document.getElementById('slideModal')).show();
        });
    }
    
    const refreshBtn = document.getElementById('refreshSliderBtn');
    if (refreshBtn) {
        refreshBtn.addEventListener('click', () => loadSliders());
    }
    
    document.querySelectorAll('input[name="type"]').forEach(radio => {
        radio.addEventListener('change', handleTypeChange);
    });
    
    document.querySelectorAll('input[name="source"]').forEach(radio => {
        radio.addEventListener('change', handleSourceChange);
    });
    
    const uploadArea = document.getElementById('uploadArea');
    const fileInput = document.getElementById('mediaFileInput');
    
    if (uploadArea) {
        uploadArea.addEventListener('click', () => fileInput?.click());
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
            if (e.dataTransfer.files.length > 0) {
                handleFileSelect(e.dataTransfer.files[0]);
            }
        });
    }
    
    if (fileInput) {
        fileInput.addEventListener('change', (e) => {
            if (e.target.files.length > 0) {
                handleFileSelect(e.target.files[0]);
            }
        });
    }
    
    const removeFileBtn = document.getElementById('removeFileBtn');
    if (removeFileBtn) {
        removeFileBtn.addEventListener('click', () => clearFilePreview());
    }
    
    const openMediaBtn = document.getElementById('openMediaLibraryBtn');
    if (openMediaBtn) {
        openMediaBtn.addEventListener('click', () => loadMediaLibrary());
    }
    
    const slideForm = document.getElementById('slideForm');
    if (slideForm) {
        slideForm.addEventListener('submit', saveSlide);
    }
    
    const confirmDeleteBtn = document.getElementById('confirmDeleteSlideBtn');
    if (confirmDeleteBtn) {
        confirmDeleteBtn.addEventListener('click', confirmDelete);
    }
    
    document.querySelectorAll('[data-filter]').forEach(button => {
        button.addEventListener('click', function() {
            document.querySelectorAll('[data-filter]').forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            applyFilter(this.getAttribute('data-filter'));
        });
    });
}

function loadSliders() {
    const tbody = document.getElementById('sliderTableBody');
    tbody.innerHTML = '<tr><td colspan="8" class="text-center py-5"><div class="spinner-border text-primary"></div><p class="mt-2">Chargement...</p></td></tr>';
    
    // Utilisez la nouvelle URL avec /api/
    const url = `/admin/cms/${currentEtablissementId}/api/slider`;
    console.log('Fetching from:', url);
    
    fetch(url, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        credentials: 'include'
    })
    .then(response => {
        console.log('Response status:', response.status);
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}`);
        }
        return response.json();
    })
    .then(result => {
        console.log('Result:', result);
        if (result.success && result.data) {
            sliderItems = result.data;
            renderSliderTable(result.data);
            updateStats(result.data);
        } else {
            tbody.innerHTML = '<tr><td colspan="8" class="text-center py-5"><i class="fas fa-sliders-h fa-3x text-muted mb-3"></i><p>Aucun slide</p><button class="btn btn-primary btn-sm" onclick="document.getElementById(\'addSlideBtn\').click()">Ajouter</button></td></tr>';
        }
    })
    .catch(error => {
        console.error('Fetch error:', error);
        tbody.innerHTML = `<tr><td colspan="8" class="text-center py-5 text-danger">
            <i class="fas fa-exclamation-triangle fa-2x mb-2"></i>
            <p>Erreur: ${error.message}</p>
            <button class="btn btn-sm btn-outline-primary mt-2" onclick="loadSliders()">Réessayer</button>
        </td></tr>`;
        showToast('Erreur de chargement', 'error');
    });
}

function getPreviewHtml(item) {
    if (item.type === 'video') {
        if (item.video_html) {
            const youtubeMatch = item.video_html.match(/youtube\.com\/embed\/([a-zA-Z0-9_-]+)/);
            if (youtubeMatch) {
                const youtubeId = youtubeMatch[1];
                return `<div class="slider-preview">
                            <div class="youtube-thumbnail" style="background-image: url('https://img.youtube.com/vi/${youtubeId}/mqdefault.jpg'); background-size: cover; background-position: center;">
                                <div style="display: flex; align-items: center; justify-content: center; height: 100%; background: rgba(0,0,0,0.3);">
                                    <i class="fab fa-youtube" style="color: red; font-size: 24px;"></i>
                                </div>
                            </div>
                        </div>`;
            }
            return `<div class="slider-preview"><div class="video-placeholder"><i class="fab fa-youtube fa-2x"></i></div></div>`;
        }
        else if (item.url && item.url !== '') {
            return `<div class="slider-preview"><video src="${escapeHtml(item.url)}"></video></div>`;
        }
        else {
            return `<div class="slider-preview"><div class="video-placeholder"><i class="fas fa-video fa-2x"></i></div></div>`;
        }
    }
    else if (item.type === 'image' && item.url && item.url !== '') {
        return `<div class="slider-preview"><img src="${escapeHtml(item.url)}" alt="${escapeHtml(item.title)}"></div>`;
    }
    else {
        return `<div class="slider-preview"><div class="video-placeholder"><i class="fas fa-image fa-2x"></i></div></div>`;
    }
}

function renderSliderTable(items) {
    const tbody = document.getElementById('sliderTableBody');
    const sortedItems = [...items].sort((a, b) => (a.order || 0) - (b.order || 0));
    
    if (sortedItems.length === 0) {
        tbody.innerHTML = '<tr><td colspan="8" class="text-center py-5"><i class="fas fa-sliders-h fa-3x text-muted mb-3"></i><p>Aucun slide</p><button class="btn btn-primary btn-sm" onclick="document.getElementById(\'addSlideBtn\').click()">Ajouter</button></td></tr>';
        return;
    }
    
    tbody.innerHTML = sortedItems.map((item, index) => `
        <tr data-id="${item.id}" data-order="${item.order || index + 1}">
            <td class="drag-handle-cell"><i class="fas fa-grip-vertical"></i></td>
            <td>${getPreviewHtml(item)}</td>
            <td><strong>${escapeHtml(item.title) || '<span class="text-muted">-</span>'}</strong></td>
            <td><small class="text-muted">${escapeHtml(item.subtitle) || '-'}</small></td>
            <td><span class="type-badge ${item.type}"><i class="fas fa-${item.type === 'video' ? 'video' : 'image'} me-1"></i>${item.type === 'video' ? 'Vidéo' : 'Image'}</span></td>
            <td><span class="status-badge ${item.is_active ? 'active' : 'inactive'}"><i class="fas fa-${item.is_active ? 'check-circle' : 'circle'} me-1"></i>${item.is_active ? 'Actif' : 'Inactif'}</span></td>
            <td>${item.button_text ? `<span class="badge bg-secondary">${escapeHtml(item.button_text)}</span>` : '-'}</td>
            <td>
                <button class="btn btn-sm btn-outline-primary btn-icon edit-slide" data-id="${item.id}" title="Modifier"><i class="fas fa-edit"></i></button>
                <button class="btn btn-sm btn-outline-secondary btn-icon toggle-active" data-id="${item.id}" title="${item.is_active ? 'Désactiver' : 'Activer'}"><i class="fas fa-${item.is_active ? 'eye-slash' : 'eye'}"></i></button>
                <button class="btn btn-sm btn-outline-danger btn-icon delete-slide" data-id="${item.id}" title="Supprimer"><i class="fas fa-trash"></i></button>
            </td>
        </tr>
    `).join('');
    
    document.querySelectorAll('.edit-slide').forEach(btn => btn.addEventListener('click', () => editSlide(btn.dataset.id)));
    document.querySelectorAll('.delete-slide').forEach(btn => btn.addEventListener('click', () => { deleteId = btn.dataset.id; new bootstrap.Modal(document.getElementById('deleteSlideModal')).show(); }));
    document.querySelectorAll('.toggle-active').forEach(btn => btn.addEventListener('click', () => toggleActive(btn.dataset.id)));
    
    initDragAndDrop();
}

function initDragAndDrop() {
    const tbody = document.getElementById('sliderTableBody');
    let dragSrcRow = null;
    
    const rows = tbody.querySelectorAll('tr');
    rows.forEach(row => {
        row.setAttribute('draggable', 'true');
        row.addEventListener('dragstart', (e) => { dragSrcRow = row; e.dataTransfer.effectAllowed = 'move'; row.classList.add('dragging'); });
        row.addEventListener('dragend', () => row.classList.remove('dragging'));
        row.addEventListener('dragover', (e) => e.preventDefault());
        row.addEventListener('drop', (e) => {
            e.preventDefault();
            if (dragSrcRow !== row) {
                if (Array.from(tbody.children).indexOf(dragSrcRow) < Array.from(tbody.children).indexOf(row)) {
                    row.parentNode.insertBefore(dragSrcRow, row.nextSibling);
                } else {
                    row.parentNode.insertBefore(dragSrcRow, row);
                }
                saveNewOrder();
            }
        });
    });
}

function saveNewOrder() {
    const rows = document.querySelectorAll('#sliderTableBody tr');
    const orders = [];
    rows.forEach((row, index) => { if (row.dataset.id) orders.push({ id: parseInt(row.dataset.id), order: index + 1 }); });
    
    fetch(`/admin/cms/${currentEtablissementId}/slider/reorder`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({ orders: orders })
    }).then(response => response.json()).then(result => { if (result.success) loadSliders(); });
}

function updateStats(items) {
    document.getElementById('totalSlides').textContent = items.length;
    document.getElementById('activeSlides').textContent = items.filter(i => i.is_active).length;
    document.getElementById('imageSlides').textContent = items.filter(i => i.type === 'image').length;
    document.getElementById('videoSlides').textContent = items.filter(i => i.type === 'video').length;
}

function applyFilter(filter) {
    const rows = document.querySelectorAll('#sliderTableBody tr');
    rows.forEach(row => {
        if (!row.dataset.id) return;
        const type = row.querySelector('.type-badge')?.classList.contains('image') ? 'image' : 'video';
        const status = row.querySelector('.status-badge')?.classList.contains('active') ? 'active' : 'inactive';
        let show = true;
        if (filter === 'image') show = type === 'image';
        else if (filter === 'video') show = type === 'video';
        else if (filter === 'active') show = status === 'active';
        else if (filter === 'inactive') show = status === 'inactive';
        row.style.display = show ? '' : 'none';
    });
}

function handleTypeChange() {
    const isVideo = document.querySelector('input[name="type"]:checked').value === 'video';
    const fileInput = document.getElementById('mediaFileInput');
    const videoUrlSection = document.getElementById('videoUrlSection');
    
    if (isVideo) {
        fileInput.setAttribute('accept', 'video/mp4,video/webm,video/ogg');
        if (videoUrlSection) videoUrlSection.style.display = 'block';
    } else {
        fileInput.setAttribute('accept', 'image/*');
        if (videoUrlSection) videoUrlSection.style.display = 'none';
    }
    clearFilePreview();
}

function handleSourceChange() {
    const source = document.querySelector('input[name="source"]:checked').value;
    document.getElementById('uploadSection').style.display = source === 'upload' ? 'block' : 'none';
    document.getElementById('mediaSection').style.display = source === 'media' ? 'block' : 'none';
    if (source === 'media') loadMediaLibrary();
    else clearFilePreview();
}

function handleFileSelect(file) {
    const isVideo = file.type.startsWith('video/');
    const imgPreview = document.getElementById('imagePreview');
    const vidPreview = document.getElementById('videoPreview');
    
    const reader = new FileReader();
    reader.onload = function(e) {
        if (isVideo) { imgPreview.style.display = 'none'; vidPreview.style.display = 'block'; vidPreview.src = e.target.result; }
        else { vidPreview.style.display = 'none'; imgPreview.style.display = 'block'; imgPreview.src = e.target.result; }
        document.getElementById('filePreview').style.display = 'block';
        document.getElementById('uploadArea').style.display = 'none';
    };
    reader.readAsDataURL(file);
}

function clearFilePreview() {
    document.getElementById('filePreview').style.display = 'none';
    document.getElementById('uploadArea').style.display = 'block';
    document.getElementById('imagePreview').src = '';
    document.getElementById('videoPreview').src = '';
    document.getElementById('mediaFileInput').value = '';
}

function loadMediaLibrary() {
    const grid = document.getElementById('mediaGrid');
    grid.innerHTML = '<div class="text-center py-3"><div class="spinner-border spinner-border-sm"></div> Chargement...</div>';
    const type = document.querySelector('input[name="type"]:checked').value;
    
    fetch(`/admin/cms/${currentEtablissementId}/media?type=${type === 'video' ? 'video' : 'image'}&limit=20`)
        .then(response => response.json())
        .then(result => {
            if (result.success && result.data?.length) {
                grid.innerHTML = result.data.map(media => `
                    <div class="media-item" data-id="${media.id}" data-url="${escapeHtml(media.url)}" data-name="${escapeHtml(media.name)}">
                        ${media.type === 'video' ? `<video src="${escapeHtml(media.url)}"></video>` : `<img src="${escapeHtml(media.url)}">`}
                        <div class="media-info">${escapeHtml(media.name.substring(0, 15))}</div>
                    </div>
                `).join('');
                grid.querySelectorAll('.media-item').forEach(item => item.addEventListener('click', () => {
                    grid.querySelectorAll('.media-item').forEach(i => i.classList.remove('selected'));
                    item.classList.add('selected');
                    document.getElementById('selectedMediaId').value = item.dataset.id;
                    document.getElementById('selectedMediaName').textContent = item.dataset.name;
                    document.getElementById('selectedMediaPreview').style.display = 'block';
                }));
            } else {
                grid.innerHTML = '<div class="text-center py-4"><p class="text-muted">Aucun média trouvé</p></div>';
            }
        })
        .catch(error => {
            console.error('Error loading media:', error);
            grid.innerHTML = '<div class="text-center py-4 text-danger"><p>Erreur de chargement</p></div>';
        });
}

function saveSlide(e) {
    e.preventDefault();
    const slideId = document.getElementById('slideId').value;
    const type = document.querySelector('input[name="type"]:checked').value;
    const source = document.querySelector('input[name="source"]:checked').value;
    const isEdit = slideId !== '';
    
    const formData = new FormData();
    formData.append('type', type);
    formData.append('title', document.getElementById('slideTitle').value);
    formData.append('subtitle', document.getElementById('slideSubtitle').value);
    formData.append('button_text', document.getElementById('slideButtonText').value);
    formData.append('button_link', document.getElementById('slideButtonLink').value);
    
    if (source === 'upload') {
        const file = document.getElementById('mediaFileInput').files[0];
        if (!file && !isEdit) { 
            if (type === 'video') {
                const videoUrl = document.getElementById('videoUrl').value;
                if (videoUrl) {
                    formData.append('video_url', videoUrl);
                } else {
                    showToast('Veuillez sélectionner un fichier ou entrer une URL vidéo', 'error');
                    return;
                }
            } else {
                showToast('Veuillez sélectionner un fichier', 'error');
                return;
            }
        }
        if (file) formData.append(type === 'video' ? 'video_file' : 'image_file', file);
    } else {
        const mediaId = document.getElementById('selectedMediaId').value;
        if (!mediaId && !isEdit) { showToast('Veuillez sélectionner un média', 'error'); return; }
        formData.append('media_id', mediaId);
    }
    
    const saveBtn = document.getElementById('saveSlideBtn');
    saveBtn.disabled = true;
    saveBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Enregistrement...';
    
    const url = isEdit ? `/admin/cms/${currentEtablissementId}/api/slider/${slideId}` : `/admin/cms/${currentEtablissementId}/api/slider`;
    if (isEdit) formData.append('_method', 'PUT');
    
    fetch(url, { method: 'POST', headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }, body: formData })
        .then(response => response.json())
        .then(result => {
            saveBtn.disabled = false;
            saveBtn.innerHTML = 'Enregistrer';
            if (result.success) {
                bootstrap.Modal.getInstance(document.getElementById('slideModal')).hide();
                showToast(result.message, 'success');
                loadSliders();
                resetForm();
            } else showToast(result.message || 'Erreur', 'error');
        })
        .catch((error) => { 
            saveBtn.disabled = false; 
            saveBtn.innerHTML = 'Enregistrer'; 
            console.error('Save error:', error);
            showToast('Erreur lors de l\'enregistrement', 'error'); 
        });
}

function editSlide(id) {
    const item = sliderItems.find(i => i.id == id);
    if (!item) return;
    
    document.getElementById('slideId').value = id;
    document.getElementById('slideModalTitle').innerHTML = '<i class="fas fa-edit me-2"></i>Modifier le slide';
    document.getElementById(item.type === 'video' ? 'typeVideo' : 'typeImage').checked = true;
    document.getElementById('slideTitle').value = item.title || '';
    document.getElementById('slideSubtitle').value = item.subtitle || '';
    document.getElementById('slideButtonText').value = item.button_text || '';
    document.getElementById('slideButtonLink').value = item.button_link || '';
    document.getElementById('sourceUpload').checked = true;
    handleSourceChange();
    handleTypeChange();
    
    if (item.url && item.url !== '') {
        const imgPreview = document.getElementById('imagePreview');
        const vidPreview = document.getElementById('videoPreview');
        if (item.type === 'video') { 
            imgPreview.style.display = 'none'; 
            vidPreview.style.display = 'block'; 
            vidPreview.src = item.url; 
        } else { 
            vidPreview.style.display = 'none'; 
            imgPreview.style.display = 'block'; 
            imgPreview.src = item.url; 
        }
        document.getElementById('filePreview').style.display = 'block';
        document.getElementById('uploadArea').style.display = 'none';
    } else if (item.video_html) {
        const videoUrlSection = document.getElementById('videoUrlSection');
        if (videoUrlSection) videoUrlSection.style.display = 'block';
        const youtubeMatch = item.video_html.match(/src="([^"]+)"/);
        if (youtubeMatch) {
            document.getElementById('videoUrl').value = youtubeMatch[1];
        }
    }
    
    new bootstrap.Modal(document.getElementById('slideModal')).show();
}

function toggleActive(id) {
    fetch(`/admin/cms/${currentEtablissementId}/slider/${id}/toggle`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
    }).then(response => response.json()).then(result => { if (result.success) { showToast(result.message, 'success'); loadSliders(); } });
}

function confirmDelete() {
    if (!deleteId) return;
    fetch(`/admin/cms/${currentEtablissementId}/slider/${deleteId}`, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
    }).then(response => response.json()).then(result => {
        bootstrap.Modal.getInstance(document.getElementById('deleteSlideModal')).hide();
        if (result.success) { showToast(result.message, 'success'); loadSliders(); }
        else showToast(result.message || 'Erreur', 'error');
        deleteId = null;
    });
}

function resetForm() {
    document.getElementById('slideForm').reset();
    document.getElementById('slideId').value = '';
    document.getElementById('selectedMediaId').value = '';
    document.getElementById('selectedMediaPreview').style.display = 'none';
    document.getElementById('videoUrl').value = '';
    clearFilePreview();
    document.getElementById('sourceUpload').checked = true;
    handleSourceChange();
    document.getElementById('typeImage').checked = true;
    handleTypeChange();
}

function escapeHtml(str) {
    if (!str) return '';
    return str.replace(/[&<>]/g, m => m === '&' ? '&amp;' : m === '<' ? '&lt;' : '&gt;');
}

function showToast(message, type = 'success') {
    document.querySelectorAll('.toast-notification').forEach(t => t.remove());
    const toast = document.createElement('div');
    toast.className = `toast-notification ${type}`;
    toast.innerHTML = `<div class="toast-content"><i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i><span>${escapeHtml(message)}</span></div>`;
    document.body.appendChild(toast);
    setTimeout(() => toast.classList.add('show'), 100);
    setTimeout(() => { toast.classList.remove('show'); setTimeout(() => toast.remove(), 300); }, 3000);
}

if (!document.querySelector('#slider-toast-styles')) {
    const style = document.createElement('style');
    style.id = 'slider-toast-styles';
    style.textContent = `
        .toast-notification { position: fixed; bottom: 20px; right: 20px; background: white; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); transform: translateX(400px); transition: transform 0.3s ease; z-index: 10000; min-width: 280px; }
        .toast-notification.show { transform: translateX(0); }
        .toast-content { padding: 16px 20px; display: flex; align-items: center; gap: 12px; border-left: 4px solid; border-radius: 12px; }
        .toast-notification.success .toast-content { border-left-color: #10b981; }
        .toast-notification.success i { color: #10b981; }
        .toast-notification.error .toast-content { border-left-color: #ef4444; }
        .toast-notification.error i { color: #ef4444; }
    `;
    document.head.appendChild(style);
}
</script>