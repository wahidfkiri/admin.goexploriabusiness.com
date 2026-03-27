@extends('layouts.app')

@section('content')
<div class="dashboard-content">
    <div class="page-header">
        <div>
            <h1 class="page-title">
                <span class="page-title-icon"><i class="fas fa-images"></i></span>
                Médiathèque - {{ $etablissement->name }}
            </h1>
            <p class="page-description">Gérez vos images, documents et fichiers</p>
        </div>
        
        <div class="page-actions">
            <button class="btn btn-primary" id="uploadBtn">
                <i class="fas fa-upload me-2"></i>Uploader
            </button>
            <button class="btn btn-outline-secondary" id="createFolderBtn">
                <i class="fas fa-folder-plus me-2"></i>Nouveau dossier
            </button>
        </div>
    </div>
    
    <!-- Stats Cards -->
    <div class="stats-grid-modern">
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #4361ee, #3a56e4);">
                <i class="fas fa-file"></i>
            </div>
            <div class="stat-info">
                <h3 class="stat-value">{{ $stats['total'] }}</h3>
                <p class="stat-label">Total fichiers</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #10b981, #059669);">
                <i class="fas fa-image"></i>
            </div>
            <div class="stat-info">
                <h3 class="stat-value">{{ $stats['images'] }}</h3>
                <p class="stat-label">Images</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
                <i class="fas fa-file-alt"></i>
            </div>
            <div class="stat-info">
                <h3 class="stat-value">{{ $stats['documents'] }}</h3>
                <p class="stat-label">Documents</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #ef476f, #d4335f);">
                <i class="fas fa-database"></i>
            </div>
            <div class="stat-info">
                <h3 class="stat-value">{{ $stats['size'] }}</h3>
                <p class="stat-label">Espace utilisé</p>
            </div>
        </div>
    </div>
    
    <!-- Navigation dossiers -->
    <div class="folder-navigation mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('cms.admin.media.index', ['etablissementId' => $etablissement->id]) }}">Racine</a></li>
                @if(isset($folder) && $folder != '/')
                    @php
                        $parts = explode('/', trim($folder, '/'));
                        $current = '';
                    @endphp
                    @foreach($parts as $part)
                        @php $current = $current ? $current . '/' . $part : $part; @endphp
                        <li class="breadcrumb-item">
                            <a href="{{ route('cms.admin.media.folder', ['etablissementId' => $etablissement->id, 'folder' => $current]) }}">
                                {{ $part }}
                            </a>
                        </li>
                    @endforeach
                @endif
            </ol>
        </nav>
    </div>
    
    <!-- Grille des médias -->
    <div class="media-grid" id="mediaGrid">
        @foreach($media as $item)
        <div class="media-item" data-id="{{ $item->id }}" data-type="{{ $item->type }}">
            <div class="media-preview">
                @if($item->isImage())
                    <img src="{{ $item->url }}" alt="{{ $item->name }}">
                @else
                    <div class="file-icon" style="color: {{ $item->icon_color }}">
                        <i class="fas {{ $item->icon }} fa-4x"></i>
                    </div>
                @endif
            </div>
            <div class="media-info">
                <div class="media-name">{{ $item->name }}</div>
                <div class="media-meta">
                    <span>{{ $item->formatted_size }}</span>
                    <span class="ms-2">{{ $item->extension }}</span>
                </div>
                <div class="media-actions">
                    <button class="btn btn-sm btn-outline-primary copy-url" data-url="{{ $item->url }}">
                        <i class="fas fa-copy"></i>
                    </button>
                    <button class="btn btn-sm btn-outline-danger delete-media" data-id="{{ $item->id }}">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>
        @endforeach
        
        @if($media->isEmpty())
        <div class="empty-state">
            <i class="fas fa-images fa-4x mb-3"></i>
            <h3>Aucun média</h3>
            <p>Commencez par uploader vos premiers fichiers</p>
            <button class="btn btn-primary" id="emptyUploadBtn">
                <i class="fas fa-upload me-2"></i>Uploader
            </button>
        </div>
        @endif
    </div>
    
    <!-- Pagination -->
    @if($media->hasPages())
    <div class="pagination-modern mt-4">
        {{ $media->links() }}
    </div>
    @endif
</div>

<!-- Upload Modal -->
<div class="modal fade" id="uploadModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Uploader un fichier</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="uploadForm" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="upload-area" id="uploadArea">
                        <div class="upload-icon">
                            <i class="fas fa-cloud-upload-alt fa-3x"></i>
                        </div>
                        <h4>Glissez vos fichiers ici</h4>
                        <p>ou cliquez pour sélectionner</p>
                        <input type="file" name="file" id="fileInput" hidden>
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
                            <input type="text" class="form-control" name="name" id="mediaName">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Dossier</label>
                            <select class="form-control" name="folder" id="mediaFolder">
                                <option value="/">Racine</option>
                                @foreach($stats['folders'] as $folder)
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

<!-- Create Folder Modal -->
<div class="modal fade" id="createFolderModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Créer un dossier</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="createFolderForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nom du dossier</label>
                        <input type="text" class="form-control" name="name" required pattern="[a-zA-Z0-9-_]+">
                        <small class="text-muted">Lettres, chiffres, tirets et underscores uniquement</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Dossier parent</label>
                        <select class="form-control" name="parent">
                            <option value="">Racine</option>
                            @foreach($stats['folders'] as $folder)
                                <option value="{{ $folder }}">{{ $folder }}</option>
                            @endforeach
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

<style>
.media-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 20px;
}

.media-item {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    transition: all 0.3s ease;
}

.media-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 24px rgba(0,0,0,0.1);
}

.media-preview {
    height: 150px;
    background: #f8f9fa;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
}

.media-preview img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.file-icon {
    text-align: center;
}

.media-info {
    padding: 12px;
}

.media-name {
    font-weight: 600;
    margin-bottom: 5px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.media-meta {
    font-size: 0.75rem;
    color: #6c757d;
    margin-bottom: 10px;
}

.media-actions {
    display: flex;
    gap: 8px;
}

.upload-area {
    border: 2px dashed #cbd5e1;
    border-radius: 16px;
    padding: 40px;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
}

.upload-area:hover {
    border-color: #4361ee;
    background: #f8fafc;
}

.upload-area.drag-over {
    border-color: #10b981;
    background: #f0fdf4;
}

.empty-state {
    text-align: center;
    padding: 80px 20px;
    background: #f8f9fa;
    border-radius: 20px;
}

.breadcrumb {
    background: transparent;
    padding: 0;
}

@media (max-width: 768px) {
    .media-grid {
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    }
}
</style>

<script>
let currentEtablissementId = {{ $etablissement->id }};
let selectedFile = null;

// Upload modal handlers
document.getElementById('uploadBtn')?.addEventListener('click', () => {
    new bootstrap.Modal(document.getElementById('uploadModal')).show();
});

document.getElementById('emptyUploadBtn')?.addEventListener('click', () => {
    new bootstrap.Modal(document.getElementById('uploadModal')).show();
});

document.getElementById('createFolderBtn')?.addEventListener('click', () => {
    new bootstrap.Modal(document.getElementById('createFolderModal')).show();
});

// File input handlers
const fileInput = document.getElementById('fileInput');
const uploadArea = document.getElementById('uploadArea');

uploadArea?.addEventListener('click', () => fileInput?.click());

uploadArea?.addEventListener('dragover', (e) => {
    e.preventDefault();
    uploadArea.classList.add('drag-over');
});

uploadArea?.addEventListener('dragleave', () => {
    uploadArea.classList.remove('drag-over');
});

uploadArea?.addEventListener('drop', (e) => {
    e.preventDefault();
    uploadArea.classList.remove('drag-over');
    const files = e.dataTransfer.files;
    if (files.length > 0) {
        handleFileSelect(files[0]);
    }
});

fileInput?.addEventListener('change', (e) => {
    if (e.target.files.length > 0) {
        handleFileSelect(e.target.files[0]);
    }
});

function handleFileSelect(file) {
    selectedFile = file;
    document.getElementById('fileName').textContent = file.name;
    document.getElementById('fileInfo').style.display = 'block';
    document.getElementById('uploadSubmitBtn').disabled = false;
    
    // Auto-populate name
    const name = file.name.replace(/\.[^/.]+$/, '');
    document.getElementById('mediaName').value = name;
}

// Upload form
document.getElementById('uploadForm')?.addEventListener('submit', async (e) => {
    e.preventDefault();
    
    if (!selectedFile) return;
    
    const formData = new FormData();
    formData.append('file', selectedFile);
    formData.append('name', document.getElementById('mediaName').value);
    formData.append('folder', document.getElementById('mediaFolder').value);
    
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
            bootstrap.Modal.getInstance(document.getElementById('uploadModal')).hide();
            showToast('Fichier uploadé avec succès', 'success');
            location.reload();
        } else {
            showToast(data.message, 'error');
        }
    } catch (error) {
        hideLoading();
        showToast('Erreur lors de l\'upload', 'error');
    }
});

// Create folder form
document.getElementById('createFolderForm')?.addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const formData = new FormData(e.target);
    
    showLoading();
    
    try {
        const response = await fetch(`/admin/cms/${currentEtablissementId}/media/folder/create`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: formData
        });
        
        const data = await response.json();
        hideLoading();
        
        if (data.success) {
            bootstrap.Modal.getInstance(document.getElementById('createFolderModal')).hide();
            showToast('Dossier créé avec succès', 'success');
            location.reload();
        } else {
            showToast(data.message, 'error');
        }
    } catch (error) {
        hideLoading();
        showToast('Erreur lors de la création', 'error');
    }
});

// Delete media
document.querySelectorAll('.delete-media').forEach(btn => {
    btn.addEventListener('click', async (e) => {
        const id = btn.dataset.id;
        
        if (confirm('Êtes-vous sûr de vouloir supprimer ce fichier ?')) {
            showLoading();
            
            try {
                const response = await fetch(`/admin/cms/${currentEtablissementId}/media/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                
                const data = await response.json();
                hideLoading();
                
                if (data.success) {
                    showToast('Fichier supprimé avec succès', 'success');
                    btn.closest('.media-item')?.remove();
                } else {
                    showToast(data.message, 'error');
                }
            } catch (error) {
                hideLoading();
                showToast('Erreur lors de la suppression', 'error');
            }
        }
    });
});

// Copy URL
document.querySelectorAll('.copy-url').forEach(btn => {
    btn.addEventListener('click', () => {
        const url = btn.dataset.url;
        navigator.clipboard.writeText(url);
        showToast('URL copiée dans le presse-papier', 'success');
    });
});

// Helper functions
function showLoading() {
    let overlay = document.getElementById('loadingOverlay');
    if (!overlay) {
        overlay = document.createElement('div');
        overlay.id = 'loadingOverlay';
        overlay.className = 'loading-overlay';
        overlay.innerHTML = `<div class="spinner-modern"><div class="spinner"></div><p>Chargement...</p></div>`;
        document.body.appendChild(overlay);
    }
    overlay.style.display = 'flex';
}

function hideLoading() {
    const overlay = document.getElementById('loadingOverlay');
    if (overlay) overlay.style.display = 'none';
}

function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.className = `toast-notification ${type}`;
    toast.innerHTML = `<div class="toast-content"><i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i><span>${message}</span></div>`;
    document.body.appendChild(toast);
    setTimeout(() => toast.classList.add('show'), 100);
    setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}
</script>
@endsection