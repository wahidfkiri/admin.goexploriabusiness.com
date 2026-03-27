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
                    <div class="stat-mini-value">{{ $stats['media_count'] ?? 0 }}</div>
                    <div class="stat-mini-label">Total fichiers</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-mini-card">
                    <div class="stat-mini-value">{{ $stats['images_count'] ?? 0 }}</div>
                    <div class="stat-mini-label">Images</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-mini-card">
                    <div class="stat-mini-value">{{ $stats['documents_count'] ?? 0 }}</div>
                    <div class="stat-mini-label">Documents</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-mini-card">
                    <div class="stat-mini-value">{{ $stats['media_size'] ?? '0 MB' }}</div>
                    <div class="stat-mini-label">Espace utilisé</div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="media-filters mb-3">
        <div class="btn-group" role="group">
            <button type="button" class="btn btn-outline-secondary active" data-filter="all">Tous</button>
            <button type="button" class="btn btn-outline-secondary" data-filter="image">Images</button>
            <button type="button" class="btn btn-outline-secondary" data-filter="document">Documents</button>
            <button type="button" class="btn btn-outline-secondary" data-filter="video">Vidéos</button>
        </div>
    </div>
    
    <div class="gallery-grid" id="mediaGallery">
        @forelse($stats['recent_medias'] ?? [] as $media)
        <div class="gallery-item" data-id="{{ $media->id }}" data-type="{{ $media->type }}">
            <div class="gallery-preview">
                @if($media->isImage())
                    <img src="{{ $media->url }}" alt="{{ $media->name }}">
                @else
                    <div class="file-icon" style="color: {{ $media->icon_color }}">
                        <i class="fas {{ $media->icon }} fa-3x"></i>
                    </div>
                @endif
            </div>
            <div class="gallery-info">
                <div class="gallery-name" title="{{ $media->name }}">{{ Str::limit($media->name, 20) }}</div>
                <div class="gallery-meta">
                    <span>{{ $media->formatted_size }}</span>
                    <span class="ms-2">{{ strtoupper($media->extension) }}</span>
                </div>
            </div>
            <div class="gallery-actions">
                <button class="btn btn-sm btn-outline-primary copy-url" data-url="{{ $media->url }}" title="Copier l'URL">
                    <i class="fas fa-copy"></i>
                </button>
                <button class="btn btn-sm btn-outline-danger delete-media" data-id="{{ $media->id }}" title="Supprimer">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
        @empty
        <div class="gallery-item empty">
            <div class="gallery-placeholder">
                <i class="fas fa-image fa-3x"></i>
                <p>Aucun média</p>
                <button class="btn btn-sm btn-primary mt-2" id="emptyUploadBtn">
                    <i class="fas fa-upload me-1"></i>Uploader
                </button>
            </div>
        </div>
        @endforelse
    </div>
    
    <!-- Upload Modal -->
    <div class="modal fade" id="uploadMediaModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Uploader un fichier</h5>
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
                            <input type="file" name="file" id="fileInput" accept="image/*,application/pdf,.doc,.docx,.xls,.xlsx, video/*" hidden>
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
                                <label class="form-label">Nom (optionnel)</label>
                                <input type="text" class="form-control" name="name" id="mediaName" placeholder="Nom du fichier">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Dossier</label>
                                <select class="form-control" name="folder" id="mediaFolder">
                                    <option value="/">Racine</option>
                                    @foreach($stats['folders'] ?? [] as $folder)
                                        <option value="{{ $folder }}">{{ $folder }}</option>
                                    @endforeach
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
                        <button class="btn btn-danger" id="confirmDeleteBtn">Supprimer</button>
                    </div>
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

.gallery-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
    gap: 20px;
    margin-top: 20px;
}

.gallery-item {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    transition: all 0.3s ease;
    position: relative;
}

.gallery-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 24px rgba(0,0,0,0.1);
}

.gallery-item.empty {
    grid-column: 1 / -1;
    text-align: center;
    padding: 60px 20px;
    background: #f8f9fa;
}

.gallery-preview {
    height: 150px;
    background: #f8f9fa;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
}

.gallery-preview img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.file-icon {
    text-align: center;
}

.gallery-info {
    padding: 12px;
}

.gallery-name {
    font-weight: 600;
    font-size: 0.9rem;
    margin-bottom: 5px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.gallery-meta {
    font-size: 0.7rem;
    color: #6c757d;
}

.gallery-actions {
    position: absolute;
    top: 8px;
    right: 8px;
    display: flex;
    gap: 5px;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.gallery-item:hover .gallery-actions {
    opacity: 1;
}

.gallery-actions .btn {
    width: 32px;
    height: 32px;
    padding: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    background: rgba(255,255,255,0.9);
    backdrop-filter: blur(4px);
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

@media (max-width: 768px) {
    .gallery-grid {
        grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
        gap: 12px;
    }
    
    .gallery-preview {
        height: 120px;
    }
    
    .gallery-actions {
        opacity: 1;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let selectedFile = null;
    let currentDeleteId = null;
    
    // ============================================
    // UPLOAD MODAL HANDLERS
    // ============================================
    
    const uploadBtn = document.getElementById('uploadMediaBtn');
    const emptyUploadBtn = document.getElementById('emptyUploadBtn');
    const uploadModal = new bootstrap.Modal(document.getElementById('uploadMediaModal'));
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteMediaModal'));
    
    if (uploadBtn) {
        uploadBtn.addEventListener('click', () => uploadModal.show());
    }
    
    if (emptyUploadBtn) {
        emptyUploadBtn.addEventListener('click', () => uploadModal.show());
    }
    
    // File input handlers
    const fileInput = document.getElementById('fileInput');
    const uploadArea = document.getElementById('uploadArea');
    
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
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                handleFileSelect(files[0]);
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
    
    function handleFileSelect(file) {
        selectedFile = file;
        document.getElementById('fileName').textContent = file.name;
        document.getElementById('fileInfo').style.display = 'block';
        document.getElementById('uploadSubmitBtn').disabled = false;
        
        // Auto-populate name
        const name = file.name.replace(/\.[^/.]+$/, '');
        document.getElementById('mediaName').value = name;
    }
    
    // Upload form submission
    const uploadForm = document.getElementById('uploadMediaForm');
    if (uploadForm) {
        uploadForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            if (!selectedFile) return;
            
            const formData = new FormData();
            formData.append('file', selectedFile);
            formData.append('name', document.getElementById('mediaName')?.value || '');
            formData.append('folder', document.getElementById('mediaFolder')?.value || '/');
            
            showLoading();
            
            try {
                const response = await fetch(`/admin/cms/${currentEtablissementId}/media/upload`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: formData
                });
                
                const data = await response.json();
                hideLoading();
                
                if (data.success) {
                    uploadModal.hide();
                    showToast('Fichier uploadé avec succès', 'success');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showToast(data.message || 'Erreur lors de l\'upload', 'error');
                }
            } catch (error) {
                hideLoading();
                console.error('Upload error:', error);
                showToast('Erreur lors de l\'upload', 'error');
            }
        });
    }
    
    // ============================================
    // DELETE MEDIA HANDLERS
    // ============================================
    
    document.querySelectorAll('.delete-media').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.stopPropagation();
            currentDeleteId = btn.dataset.id;
            deleteModal.show();
        });
    });
    
    const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
    if (confirmDeleteBtn) {
        confirmDeleteBtn.addEventListener('click', async () => {
            if (!currentDeleteId) return;
            
            showLoading();
            
            try {
                const response = await fetch(`/admin/cms/${currentEtablissementId}/media/${currentDeleteId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                
                const data = await response.json();
                hideLoading();
                
                if (data.success) {
                    deleteModal.hide();
                    showToast('Fichier supprimé avec succès', 'success');
                    const mediaItem = document.querySelector(`.gallery-item[data-id="${currentDeleteId}"]`);
                    if (mediaItem) mediaItem.remove();
                    
                    // Check if gallery is empty
                    if (document.querySelectorAll('.gallery-item:not(.empty)').length === 0) {
                        location.reload();
                    }
                } else {
                    showToast(data.message || 'Erreur lors de la suppression', 'error');
                }
            } catch (error) {
                hideLoading();
                console.error('Delete error:', error);
                showToast('Erreur lors de la suppression', 'error');
            }
        });
    }
    
    // ============================================
    // COPY URL HANDLERS
    // ============================================
    
    document.querySelectorAll('.copy-url').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.stopPropagation();
            const url = btn.dataset.url;
            navigator.clipboard.writeText(url);
            showToast('URL copiée dans le presse-papier', 'success');
        });
    });
    
    // ============================================
    // FILTER HANDLERS
    // ============================================
    
    const filterButtons = document.querySelectorAll('[data-filter]');
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            filterButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            
            const filter = this.getAttribute('data-filter');
            const items = document.querySelectorAll('.gallery-item:not(.empty)');
            
            items.forEach(item => {
                if (filter === 'all') {
                    item.style.display = 'block';
                } else {
                    const itemType = item.getAttribute('data-type');
                    if (itemType === filter) {
                        item.style.display = 'block';
                    } else {
                        item.style.display = 'none';
                    }
                }
            });
        });
    });
});

// Helper functions
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

function hideLoading() {
    const overlay = document.getElementById('loadingOverlay');
    if (overlay) {
        overlay.style.display = 'none';
    }
}

function showToast(message, type = 'success') {
    const existingToasts = document.querySelectorAll('.toast-notification');
    existingToasts.forEach(toast => toast.remove());
    
    const toast = document.createElement('div');
    toast.className = `toast-notification ${type}`;
    toast.innerHTML = `
        <div class="toast-content">
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
            <span>${message}</span>
        </div>
    `;
    
    document.body.appendChild(toast);
    setTimeout(() => toast.classList.add('show'), 100);
    setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

// Toast and loading styles
if (!document.querySelector('#media-tab-styles')) {
    const styles = document.createElement('style');
    styles.id = 'media-tab-styles';
    styles.textContent = `
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
    `;
    document.head.appendChild(styles);
}
</script>