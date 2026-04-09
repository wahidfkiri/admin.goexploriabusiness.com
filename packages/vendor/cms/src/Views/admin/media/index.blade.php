{{-- media-tab.blade.php --}}
<div class="tab-pane fade" id="v-pills-media" role="tabpanel">
    <div class="tab-content-header">
        <h3 class="tab-title">
            <i class="fas fa-images me-2" style="color: #45b7d1;"></i>
            Médiathèque
        </h3>
        <button class="btn btn-primary btn-sm" id="uploadMediaBtn">
            <i class="fas fa-upload me-1"></i>Uploader
        </button>
    </div>

    <div class="media-stats mb-4">
        <div class="row">
            <div class="col-md-3">
                <div class="stat-mini-card">
                    <div class="stat-mini-value" id="totalMedia">0</div>
                    <div class="stat-mini-label">Total fichiers</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-mini-card">
                    <div class="stat-mini-value" id="totalImages">0</div>
                    <div class="stat-mini-label">Images</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-mini-card">
                    <div class="stat-mini-value" id="totalVideos">0</div>
                    <div class="stat-mini-label">Vidéos</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-mini-card">
                    <div class="stat-mini-value" id="totalDocuments">0</div>
                    <div class="stat-mini-label">Documents</div>
                </div>
            </div>
        </div>
    </div>

    <div class="media-filters mb-3">
        <div class="btn-group" role="group">
            <button type="button" class="btn btn-outline-secondary active" data-filter="all">Tous</button>
            <button type="button" class="btn btn-outline-secondary" data-filter="image">Images</button>
            <button type="button" class="btn btn-outline-secondary" data-filter="video">Vidéos</button>
            <button type="button" class="btn btn-outline-secondary" data-filter="document">Documents</button>
        </div>
        <div class="float-end">
            <div class="input-group" style="width: 250px;">
                <input type="text" class="form-control form-control-sm" id="searchMedia" placeholder="Rechercher...">
                <button class="btn btn-outline-secondary btn-sm" type="button" id="searchMediaBtn">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th style="width: 40px">
                        <input type="checkbox" id="selectAllMedia">
                    </th>
                    <th style="width: 80px">Aperçu</th>
                    <th>Nom</th>
                    <th>Type</th>
                    <th>Taille</th>
                    <th>Dimensions</th>
                    <th>Date</th>
                    <th style="width: 120px">Actions</th>
                </tr>
            </thead>
            <tbody id="mediaTableBody">
                <tr>
                    <td colspan="8" class="text-center py-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Chargement...</span>
                        </div>
                        <p class="mt-2">Chargement des médias...</p>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        <div class="d-flex justify-content-between align-items-center">
            <div id="bulkActions" style="display: none;">
                <button class="btn btn-sm btn-danger" id="bulkDeleteBtn">
                    <i class="fas fa-trash me-1"></i>Supprimer sélectionnés
                </button>
                <span id="selectedCount" class="ms-2 text-muted"></span>
            </div>
            <nav id="paginationNav" class="d-flex justify-content-end">
                <!-- Pagination will be inserted here -->
            </nav>
        </div>
    </div>
</div>

<!-- Upload Media Modal -->
<div class="modal fade" id="uploadMediaModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-upload me-2"></i>Uploader un fichier
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="uploadMediaForm" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="upload-area" id="uploadArea">
                        <div class="upload-icon">
                            <i class="fas fa-cloud-upload-alt fa-3x"></i>
                        </div>
                        <h4>Glissez votre fichier ici</h4>
                        <p>ou cliquez pour sélectionner</p>
                        <input type="file" name="file" id="fileInput" accept="image/*,video/*,application/pdf,.doc,.docx,.xls,.xlsx" hidden>
                        <button type="button" class="btn btn-outline-primary" onclick="document.getElementById('fileInput').click()">
                            Sélectionner un fichier
                        </button>
                    </div>
                    <div class="mt-3" id="fileInfo" style="display: none;">
                        <div class="alert alert-info">
                            <i class="fas fa-file me-2"></i>
                            <span id="fileName"></span>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nom personnalisé (optionnel)</label>
                            <input type="text" class="form-control" name="name" id="mediaName" placeholder="Nom du fichier">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Dossier</label>
                            <select class="form-control" name="folder" id="mediaFolder">
                                <option value="/">Racine</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary" id="uploadSubmitBtn" disabled>Uploader</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Media Modal -->
<div class="modal fade" id="editMediaModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-edit me-2"></i>Modifier le média
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editMediaForm">
                @csrf
                @method('PUT')
                <input type="hidden" name="media_id" id="editMediaId">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nom</label>
                        <input type="text" class="form-control" name="name" id="editMediaName" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Texte alternatif (alt)</label>
                        <input type="text" class="form-control" name="alt" id="editMediaAlt" placeholder="Description pour le SEO">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Titre</label>
                        <input type="text" class="form-control" name="title" id="editMediaTitle" placeholder="Titre au survol">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" name="description" id="editMediaDescription" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Dossier</label>
                        <select class="form-control" name="folder" id="editMediaFolder">
                            <option value="/">Racine</option>
                        </select>
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

<!-- Create Folder Modal -->
<div class="modal fade" id="createFolderModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-folder-plus me-2"></i>Créer un dossier
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="createFolderForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nom du dossier</label>
                        <input type="text" class="form-control" name="folder_name" id="folderName" required placeholder="mon-dossier">
                        <small class="text-muted">Utilisez uniquement des lettres, chiffres et tirets</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Dossier parent</label>
                        <select class="form-control" name="parent" id="parentFolder">
                            <option value="/">Racine</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Créer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteMediaModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center py-4">
                <div class="delete-icon">
                    <i class="fas fa-exclamation-triangle fa-3x text-danger"></i>
                </div>
                <h4>Supprimer le fichier</h4>
                <p class="text-muted">Êtes-vous sûr de vouloir supprimer ce fichier ?<br>Cette action est irréversible.</p>
                <div class="mt-4">
                    <button class="btn btn-secondary me-2" data-bs-dismiss="modal">Annuler</button>
                    <button class="btn btn-danger" id="confirmDeleteMediaBtn">Supprimer</button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.media-stats .stat-mini-card {
    background: #f8f9fa;
    border-radius: 12px;
    padding: 20px;
    text-align: center;
    transition: all 0.3s ease;
}

.media-stats .stat-mini-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.media-filters .btn-group {
    margin-bottom: 20px;
}

.media-filters .btn {
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

.media-preview {
    width: 60px;
    height: 60px;
    border-radius: 8px;
    overflow: hidden;
    background: #f3f4f6;
    display: flex;
    align-items: center;
    justify-content: center;
}

.media-preview img,
.media-preview video {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.media-preview .file-icon {
    font-size: 24px;
    color: #6b7280;
}

.type-badge {
    display: inline-block;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 0.7rem;
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

.type-badge.document {
    background: #fef3c7;
    color: #92400e;
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
    color: #94a3b8;
    margin-bottom: 16px;
}

.delete-icon {
    margin-bottom: 20px;
}

.folder-breadcrumb {
    background: #f8f9fa;
    padding: 10px 15px;
    border-radius: 8px;
    margin-bottom: 20px;
}

.folder-breadcrumb .breadcrumb-item {
    cursor: pointer;
}

.folder-breadcrumb .breadcrumb-item:hover {
    color: #4361ee;
    text-decoration: underline;
}

.current-folder {
    font-weight: 600;
    color: #4361ee;
}
</style>

<script>
// Initialisation
let currentPage = 1;
let currentFilter = 'all';
let currentSearch = '';
let currentFolder = '/';
let currentDeleteId = null;
let mediaItems = [];
let totalPages = 1;

document.addEventListener('DOMContentLoaded', function() {
    loadMedia();
    loadFolders();
    initEventListeners();
});

function initEventListeners() {
    // Upload button
    const uploadBtn = document.getElementById('uploadMediaBtn');
    if (uploadBtn) {
        uploadBtn.addEventListener('click', () => {
            resetUploadForm();
            new bootstrap.Modal(document.getElementById('uploadMediaModal')).show();
        });
    }
    
    // File upload handling
    const uploadArea = document.getElementById('uploadArea');
    const fileInput = document.getElementById('fileInput');
    
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
    
    // Upload form submission
    const uploadForm = document.getElementById('uploadMediaForm');
    if (uploadForm) {
        uploadForm.addEventListener('submit', uploadMedia);
    }
    
    // Edit form submission
    const editForm = document.getElementById('editMediaForm');
    if (editForm) {
        editForm.addEventListener('submit', updateMedia);
    }
    
    // Create folder form
    const folderForm = document.getElementById('createFolderForm');
    if (folderForm) {
        folderForm.addEventListener('submit', createFolder);
    }
    
    // Delete confirmation
    const confirmDeleteBtn = document.getElementById('confirmDeleteMediaBtn');
    if (confirmDeleteBtn) {
        confirmDeleteBtn.addEventListener('click', confirmDeleteMedia);
    }
    
    // Filter buttons
    document.querySelectorAll('[data-filter]').forEach(button => {
        button.addEventListener('click', function() {
            document.querySelectorAll('[data-filter]').forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            currentFilter = this.getAttribute('data-filter');
            currentPage = 1;
            loadMedia();
        });
    });
    
    // Search
    const searchBtn = document.getElementById('searchMediaBtn');
    if (searchBtn) {
        searchBtn.addEventListener('click', () => {
            currentSearch = document.getElementById('searchMedia').value;
            currentPage = 1;
            loadMedia();
        });
    }
    
    const searchInput = document.getElementById('searchMedia');
    if (searchInput) {
        searchInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                currentSearch = searchInput.value;
                currentPage = 1;
                loadMedia();
            }
        });
    }
    
    // Select all
    const selectAll = document.getElementById('selectAllMedia');
    if (selectAll) {
        selectAll.addEventListener('change', function() {
            document.querySelectorAll('.media-checkbox').forEach(cb => {
                cb.checked = this.checked;
            });
            updateBulkActions();
        });
    }
    
    // Bulk delete
    const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');
    if (bulkDeleteBtn) {
        bulkDeleteBtn.addEventListener('click', bulkDeleteMedia);
    }
}

function loadMedia() {
    const tbody = document.getElementById('mediaTableBody');
    tbody.innerHTML = '<tr><td colspan="8" class="text-center py-5"><div class="spinner-border text-primary"></div><p class="mt-2">Chargement...</p></td></tr>';
    
    let url = `/admin/cms/${currentEtablissementId}/media?page=${currentPage}&folder=${encodeURIComponent(currentFolder)}`;
    if (currentFilter !== 'all') url += `&type=${currentFilter}`;
    if (currentSearch) url += `&search=${encodeURIComponent(currentSearch)}`;
    
    fetch(url)
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                mediaItems = result.data.data || result.data || [];
                totalPages = result.data.last_page || result.last_page || 1;
                renderMediaTable(mediaItems);
                updateStats(result.stats || {});
                renderPagination();
            } else {
                tbody.innerHTML = '<tr><td colspan="8" class="text-center py-5 text-danger"><i class="fas fa-exclamation-circle fa-2x"></i><p>Erreur de chargement</p></td></tr>';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            tbody.innerHTML = '<tr><td colspan="8" class="text-center py-5 text-danger"><i class="fas fa-exclamation-circle fa-2x"></i><p>Erreur de chargement</p></td></tr>';
        });
}

function loadFolders() {
    fetch(`/admin/cms/${currentEtablissementId}/media/folders`)
        .then(response => response.json())
        .then(result => {
            if (result.success && result.data) {
                const folderSelects = document.querySelectorAll('#mediaFolder, #editMediaFolder, #parentFolder');
                folderSelects.forEach(select => {
                    const currentValue = select.value;
                    select.innerHTML = '<option value="/">Racine</option>' + 
                        result.data.map(f => `<option value="${escapeHtml(f)}">${escapeHtml(f)}</option>`).join('');
                    select.value = currentValue;
                });
            }
        });
}

function renderMediaTable(items) {
    const tbody = document.getElementById('mediaTableBody');
    
    if (!items || items.length === 0) {
        tbody.innerHTML = '<tr><td colspan="8" class="text-center py-5"><i class="fas fa-folder-open fa-3x text-muted mb-3"></i><p>Aucun média trouvé</p><button class="btn btn-primary btn-sm" onclick="document.getElementById(\'uploadMediaBtn\').click()">Uploader</button></td></tr>';
        return;
    }
    
    tbody.innerHTML = items.map(media => `
        <tr data-id="${media.id}">
            <td>
                <input type="checkbox" class="media-checkbox" data-id="${media.id}">
            </td>
            <td>
                <div class="media-preview">
                    ${media.type === 'image' ? `<img src="${escapeHtml(media.url)}" alt="${escapeHtml(media.name)}">` : 
                      media.type === 'video' ? `<video src="${escapeHtml(media.url)}"></video>` :
                      `<div class="file-icon"><i class="fas ${media.icon || 'fa-file'} fa-2x"></i></div>`}
                </div>
            </td>
            <td>
                <strong>${escapeHtml(media.name)}</strong>
                ${media.alt ? `<br><small class="text-muted">Alt: ${escapeHtml(media.alt)}</small>` : ''}
            </td>
            <td><span class="type-badge ${media.type}">${media.type === 'image' ? 'Image' : media.type === 'video' ? 'Vidéo' : 'Document'}</span></td>
            <td>${media.formatted_size || media.size}</td>
            <td>${media.width && media.height ? `${media.width}x${media.height}` : '-'}</td>
            <td><small>${new Date(media.created_at).toLocaleDateString('fr-FR')}</small></td>
            <td>
                <button class="btn btn-sm btn-outline-primary btn-icon copy-url" data-url="${escapeHtml(media.url)}" title="Copier l'URL"><i class="fas fa-copy"></i></button>
                <button class="btn btn-sm btn-outline-secondary btn-icon edit-media" data-id="${media.id}" title="Modifier"><i class="fas fa-edit"></i></button>
                <button class="btn btn-sm btn-outline-danger btn-icon delete-media" data-id="${media.id}" title="Supprimer"><i class="fas fa-trash"></i></button>
            </td>
        </tr>
    `).join('');
    
    // Attach events
    document.querySelectorAll('.copy-url').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.stopPropagation();
            navigator.clipboard.writeText(btn.dataset.url);
            showToast('URL copiée', 'success');
        });
    });
    
    document.querySelectorAll('.edit-media').forEach(btn => {
        btn.addEventListener('click', () => editMedia(btn.dataset.id));
    });
    
    document.querySelectorAll('.delete-media').forEach(btn => {
        btn.addEventListener('click', () => {
            currentDeleteId = btn.dataset.id;
            new bootstrap.Modal(document.getElementById('deleteMediaModal')).show();
        });
    });
    
    document.querySelectorAll('.media-checkbox').forEach(cb => {
        cb.addEventListener('change', updateBulkActions);
    });
    
    updateBulkActions();
}

function renderPagination() {
    const paginationNav = document.getElementById('paginationNav');
    if (!paginationNav || totalPages <= 1) {
        paginationNav.innerHTML = '';
        return;
    }
    
    let html = '<ul class="pagination pagination-sm">';
    
    // Previous
    html += `<li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
        <a class="page-link" href="#" data-page="${currentPage - 1}">&laquo;</a>
    </li>`;
    
    // Page numbers
    let startPage = Math.max(1, currentPage - 2);
    let endPage = Math.min(totalPages, currentPage + 2);
    
    if (startPage > 1) {
        html += `<li class="page-item"><a class="page-link" href="#" data-page="1">1</a></li>`;
        if (startPage > 2) html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
    }
    
    for (let i = startPage; i <= endPage; i++) {
        html += `<li class="page-item ${i === currentPage ? 'active' : ''}">
            <a class="page-link" href="#" data-page="${i}">${i}</a>
        </li>`;
    }
    
    if (endPage < totalPages) {
        if (endPage < totalPages - 1) html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
        html += `<li class="page-item"><a class="page-link" href="#" data-page="${totalPages}">${totalPages}</a></li>`;
    }
    
    // Next
    html += `<li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
        <a class="page-link" href="#" data-page="${currentPage + 1}">&raquo;</a>
    </li>`;
    
    html += '</ul>';
    paginationNav.innerHTML = html;
    
    paginationNav.querySelectorAll('.page-link').forEach(link => {
        link.addEventListener('click', (e) => {
            e.preventDefault();
            const page = parseInt(link.dataset.page);
            if (page && !isNaN(page) && page !== currentPage && page >= 1 && page <= totalPages) {
                currentPage = page;
                loadMedia();
            }
        });
    });
}

function updateStats(stats) {
    document.getElementById('totalMedia').textContent = stats.total || stats.media_count || 0;
    document.getElementById('totalImages').textContent = stats.images || stats.images_count || 0;
    document.getElementById('totalVideos').textContent = stats.videos || 0;
    document.getElementById('totalDocuments').textContent = stats.documents || stats.documents_count || 0;
}

function updateBulkActions() {
    const checked = document.querySelectorAll('.media-checkbox:checked').length;
    const bulkActions = document.getElementById('bulkActions');
    const selectedCount = document.getElementById('selectedCount');
    
    if (checked > 0) {
        bulkActions.style.display = 'block';
        selectedCount.textContent = `${checked} sélectionné(s)`;
    } else {
        bulkActions.style.display = 'none';
    }
}

function handleFileSelect(file) {
    document.getElementById('fileName').textContent = file.name;
    document.getElementById('fileInfo').style.display = 'block';
    document.getElementById('uploadSubmitBtn').disabled = false;
    
    const name = file.name.replace(/\.[^/.]+$/, '');
    document.getElementById('mediaName').value = name;
}

function resetUploadForm() {
    document.getElementById('uploadMediaForm').reset();
    document.getElementById('fileInfo').style.display = 'none';
    document.getElementById('uploadSubmitBtn').disabled = true;
    document.getElementById('mediaFileInput').value = '';
}

function uploadMedia(e) {
    e.preventDefault();
    
    const fileInput = document.getElementById('mediaFileInput');
    const file = fileInput.files[0];
    
    if (!file) {
        showToast('Veuillez sélectionner un fichier', 'error');
        return;
    }
    
    const formData = new FormData();
    formData.append('file', file);
    formData.append('name', document.getElementById('mediaName')?.value || '');
    formData.append('folder', document.getElementById('mediaFolder')?.value || '/');
    
    const submitBtn = document.getElementById('uploadSubmitBtn');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Upload...';
    
    fetch(`/admin/cms/${currentEtablissementId}/media/upload`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: formData
    })
    .then(response => response.json())
    .then(result => {
        submitBtn.disabled = false;
        submitBtn.innerHTML = 'Uploader';
        
        if (result.success) {
            bootstrap.Modal.getInstance(document.getElementById('uploadMediaModal')).hide();
            showToast('Fichier uploadé avec succès', 'success');
            loadMedia();
            loadFolders();
            resetUploadForm();
        } else {
            showToast(result.message || 'Erreur lors de l\'upload', 'error');
        }
    })
    .catch(error => {
        submitBtn.disabled = false;
        submitBtn.innerHTML = 'Uploader';
        console.error('Upload error:', error);
        showToast('Erreur lors de l\'upload', 'error');
    });
}

function editMedia(id) {
    const media = mediaItems.find(m => m.id == id);
    if (!media) return;
    
    document.getElementById('editMediaId').value = media.id;
    document.getElementById('editMediaName').value = media.name || '';
    document.getElementById('editMediaAlt').value = media.alt || '';
    document.getElementById('editMediaTitle').value = media.title || '';
    document.getElementById('editMediaDescription').value = media.description || '';
    document.getElementById('editMediaFolder').value = media.folder || '/';
    
    new bootstrap.Modal(document.getElementById('editMediaModal')).show();
}

function updateMedia(e) {
    e.preventDefault();
    
    const id = document.getElementById('editMediaId').value;
    const formData = new FormData(document.getElementById('editMediaForm'));
    
    fetch(`/admin/cms/${currentEtablissementId}/media/${id}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: formData
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            bootstrap.Modal.getInstance(document.getElementById('editMediaModal')).hide();
            showToast('Média modifié avec succès', 'success');
            loadMedia();
        } else {
            showToast(result.message || 'Erreur lors de la modification', 'error');
        }
    })
    .catch(error => {
        console.error('Update error:', error);
        showToast('Erreur lors de la modification', 'error');
    });
}

function createFolder(e) {
    e.preventDefault();
    
    const folderName = document.getElementById('folderName').value;
    const parent = document.getElementById('parentFolder').value;
    
    fetch(`/admin/cms/${currentEtablissementId}/media/folder/create`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ folder_name: folderName, parent: parent })
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            bootstrap.Modal.getInstance(document.getElementById('createFolderModal')).hide();
            showToast('Dossier créé avec succès', 'success');
            loadFolders();
            document.getElementById('createFolderForm').reset();
        } else {
            showToast(result.message || 'Erreur lors de la création', 'error');
        }
    })
    .catch(error => {
        console.error('Create folder error:', error);
        showToast('Erreur lors de la création', 'error');
    });
}

function confirmDeleteMedia() {
    if (!currentDeleteId) return;
    
    fetch(`/admin/cms/${currentEtablissementId}/media/${currentDeleteId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(result => {
        bootstrap.Modal.getInstance(document.getElementById('deleteMediaModal')).hide();
        if (result.success) {
            showToast('Fichier supprimé avec succès', 'success');
            loadMedia();
            loadFolders();
        } else {
            showToast(result.message || 'Erreur lors de la suppression', 'error');
        }
        currentDeleteId = null;
    })
    .catch(error => {
        console.error('Delete error:', error);
        showToast('Erreur lors de la suppression', 'error');
        currentDeleteId = null;
    });
}

function bulkDeleteMedia() {
    const selectedIds = [];
    document.querySelectorAll('.media-checkbox:checked').forEach(cb => {
        selectedIds.push(cb.dataset.id);
    });
    
    if (selectedIds.length === 0) return;
    
    if (!confirm(`Supprimer ${selectedIds.length} fichier(s) définitivement ?`)) return;
    
    fetch(`/admin/cms/${currentEtablissementId}/media/bulk/delete`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ ids: selectedIds })
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            showToast(`${result.deleted || selectedIds.length} fichier(s) supprimé(s)`, 'success');
            loadMedia();
            loadFolders();
        } else {
            showToast(result.message || 'Erreur lors de la suppression', 'error');
        }
    })
    .catch(error => {
        console.error('Bulk delete error:', error);
        showToast('Erreur lors de la suppression', 'error');
    });
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

// Styles for toast
if (!document.querySelector('#media-toast-styles')) {
    const style = document.createElement('style');
    style.id = 'media-toast-styles';
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