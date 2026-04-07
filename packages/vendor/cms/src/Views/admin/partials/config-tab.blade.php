<div class="tab-pane fade" id="v-pills-config" role="tabpanel">
    <div class="tab-content-header">
        <h3 class="tab-title">
            <i class="fas fa-cog me-2" style="color: #6c757d;"></i>
            Configuration générale
        </h3>
    </div>
    
    <form action="{{ route('cms.admin.settings.update', ['etablissementId' => $stats['etablissement']->id]) }}" method="POST">
        @csrf
        
        <div class="config-sections">
            <div class="config-group">
                <h4>Informations générales</h4>
                <div class="config-item">
                    <label class="config-label">Nom du site</label>
                    <input type="text" class="form-control" name="site_name" value="{{ $stats['etablissement']->getSetting('site_name', $stats['etablissement']->name ?? 'Mon site') }}">
                </div>
                <div class="config-item mt-3">
                    <label class="config-label">Slogan</label>
                    <input type="text" class="form-control" name="site_slogan" value="{{ $stats['etablissement']->getSetting('site_slogan', '') }}">
                </div>
                <div class="config-item mt-3">
                    <label class="config-label">Description</label>
                    <textarea class="form-control" name="site_description" rows="3">{{ $stats['etablissement']->getSetting('site_description', '') }}</textarea>
                </div>
            </div>

            <div class="config-group">
    <h4>Identité visuelle</h4>
    
    <!-- Logo Section -->
    <div class="config-item">
        <label class="required">Logo du site</label>
        
        <div class="upload-area" data-target="logo">
            <i class="fas fa-cloud-upload-alt upload-icon"></i>
            <div class="upload-title">Télécharger le logo</div>
            <div class="upload-subtitle">PNG, JPG, SVG jusqu'à 2MB</div>
            <button type="button" class="upload-button" data-type="logo">
                <i class="fas fa-folder-open"></i>
                Parcourir
            </button>
            <input type="file" class="file-input-hidden" data-type="logo" accept="image/png,image/jpeg,image/jpg,image/svg+xml">
        </div>
        
        <div class="preview-container logo-preview" data-preview="logo" 
             style="{{ $logo = $stats['etablissement']->getSetting('site_logo') ? 'display: block;' : 'display: none;' }}">
            <div class="preview-header">
                <span class="preview-title">Logo actuel</span>
                <button type="button" class="remove-image" data-type="logo">
                    <i class="fas fa-trash-alt"></i>
                    Supprimer
                </button>
            </div>
            <div class="preview-image">
                @if($logo = $stats['etablissement']->getSetting('site_logo'))
                    <img src="{{ Storage::url($logo) }}" alt="Logo" data-logo-preview>
                @endif
            </div>
        </div>
        
        <div class="upload-progress" data-progress="logo">
            <div class="progress-bar">
                <div class="progress-fill"></div>
            </div>
            <div class="progress-text">Téléchargement en cours...</div>
        </div>
    </div>
    
    <!-- Favicon Section -->
    <div class="config-item mt-4">
        <label>Favicon</label>
        
        <div class="upload-area" data-target="favicon">
            <i class="fas fa-image upload-icon"></i>
            <div class="upload-title">Télécharger le favicon</div>
            <div class="upload-subtitle">ICO, PNG, SVG - 32x32 ou 64x64 pixels</div>
            <button type="button" class="upload-button" data-type="favicon">
                <i class="fas fa-folder-open"></i>
                Parcourir
            </button>
            <input type="file" class="file-input-hidden" data-type="favicon" accept="image/x-icon,image/png,image/svg+xml">
        </div>
        
        <div class="preview-container favicon-preview" data-preview="favicon"
             style="{{ $favicon = $stats['etablissement']->getSetting('site_favicon') ? 'display: block;' : 'display: none;' }}">
            <div class="preview-header">
                <span class="preview-title">Favicon actuel</span>
                <button type="button" class="remove-image" data-type="favicon">
                    <i class="fas fa-trash-alt"></i>
                    Supprimer
                </button>
            </div>
            <div class="preview-image">
                @if($favicon = $stats['etablissement']->getSetting('site_favicon'))
                    <img src="{{ Storage::url($favicon) }}" alt="Favicon" data-favicon-preview>
                @endif
            </div>
            <div class="favicon-sizes">
                <div class="favicon-size">
                    <i class="fas fa-desktop"></i>
                    <span>Ordinateur</span>
                    <small>32x32</small>
                </div>
                <div class="favicon-size">
                    <i class="fas fa-mobile-alt"></i>
                    <span>Mobile</span>
                    <small>64x64</small>
                </div>
            </div>
        </div>
        
        <div class="upload-progress" data-progress="favicon">
            <div class="progress-bar">
                <div class="progress-fill"></div>
            </div>
            <div class="progress-text">Téléchargement en cours...</div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Configuration des uploads
    const uploadConfigs = {
        logo: {
            accept: ['image/png', 'image/jpeg', 'image/jpg', 'image/svg+xml'],
            maxSize: 2 * 1024 * 1024, // 2MB
            field: 'site_logo',
            preview: 'logo',
            endpoint: '{{ url("cms.admin.settings.upload", ["etablissementId" => $stats["etablissement"]->id]) }}'
        },
        favicon: {
            accept: ['image/x-icon', 'image/png', 'image/svg+xml'],
            maxSize: 1 * 1024 * 1024, // 1MB
            field: 'site_favicon',
            preview: 'favicon',
            endpoint: '{{ url("cms.admin.settings.upload", ["etablissementId" => $stats["etablissement"]->id]) }}'
        }
    };
    
    // Initialiser les uploads
    Object.keys(uploadConfigs).forEach(type => {
        initUpload(type, uploadConfigs[type]);
    });
    
    function initUpload(type, config) {
        const uploadArea = document.querySelector(`.upload-area[data-target="${type}"]`);
        const fileInput = document.querySelector(`.file-input-hidden[data-type="${type}"]`);
        const uploadBtn = document.querySelector(`.upload-button[data-type="${type}"]`);
        const previewContainer = document.querySelector(`[data-preview="${type}"]`);
        const progressContainer = document.querySelector(`[data-progress="${type}"]`);
        const removeBtn = document.querySelector(`.remove-image[data-type="${type}"]`);
        
        // Upload button click
        if (uploadBtn) {
            uploadBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                fileInput.click();
            });
        }
        
        // Upload area click
        if (uploadArea) {
            uploadArea.addEventListener('click', () => {
                fileInput.click();
            });
            
            // Drag & drop
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
                if (files.length) {
                    handleFileUpload(files[0], type, config);
                }
            });
        }
        
        // File input change
        if (fileInput) {
            fileInput.addEventListener('change', (e) => {
                if (e.target.files.length) {
                    handleFileUpload(e.target.files[0], type, config);
                }
            });
        }
        
        // Remove button
        if (removeBtn) {
            removeBtn.addEventListener('click', () => {
                removeFile(type, config);
            });
        }
    }
    
    async function handleFileUpload(file, type, config) {
        // Validation
        if (!config.accept.includes(file.type)) {
            showToast(`Format non supporté. Formats acceptés: ${config.accept.join(', ')}`, 'error');
            return;
        }
        
        if (file.size > config.maxSize) {
            showToast(`Fichier trop volumineux. Maximum: ${config.maxSize / 1024 / 1024}MB`, 'error');
            return;
        }
        
        // Show progress
        const progressContainer = document.querySelector(`[data-progress="${type}"]`);
        if (progressContainer) {
            progressContainer.classList.add('active');
        }
        
        // Prepare form data
        const formData = new FormData();
        formData.append('file', file);
        formData.append('field', config.field);
        formData.append('_token', document.querySelector('input[name="_token"]').value);
        
        try {
            const response = await fetch(config.endpoint, {
                method: 'POST',
                body: formData
            });
            
            const result = await response.json();
            
            if (result.success) {
                updatePreview(type, result.path);
                showToast('Fichier téléchargé avec succès', 'success');
                
                // Update progress to 100%
                updateProgress(progressContainer, 100);
                setTimeout(() => {
                    if (progressContainer) progressContainer.classList.remove('active');
                }, 1000);
            } else {
                throw new Error(result.message || 'Erreur lors du téléchargement');
            }
        } catch (error) {
            showToast(error.message, 'error');
            if (progressContainer) progressContainer.classList.remove('active');
        }
    }
    
    function updatePreview(type, path) {
        const previewContainer = document.querySelector(`[data-preview="${type}"]`);
        const previewImg = document.querySelector(`[data-${type}-preview]`);
        
        if (previewContainer) {
            previewContainer.style.display = 'block';
        }
        
        if (previewImg) {
            previewImg.src = path;
        } else if (previewContainer) {
            // Create preview if doesn't exist
            const previewImage = previewContainer.querySelector('.preview-image');
            if (previewImage) {
                previewImage.innerHTML = `<img src="${path}" alt="${type}">`;
            }
        }
    }
    
    async function removeFile(type, config) {
        if (!confirm('Êtes-vous sûr de vouloir supprimer ce fichier ?')) {
            return;
        }
        
        try {
            const response = await fetch('{{ url("cms.admin.settings.remove-file", ["etablissementId" => $stats["etablissement"]->id]) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                },
                body: JSON.stringify({
                    field: config.field
                })
            });
            
            const result = await response.json();
            
            if (result.success) {
                const previewContainer = document.querySelector(`[data-preview="${type}"]`);
                if (previewContainer) {
                    previewContainer.style.display = 'none';
                    const previewImg = previewContainer.querySelector('img');
                    if (previewImg) {
                        previewImg.src = '';
                    }
                }
                showToast('Fichier supprimé avec succès', 'success');
            } else {
                throw new Error(result.message || 'Erreur lors de la suppression');
            }
        } catch (error) {
            showToast(error.message, 'error');
        }
    }
    
    function updateProgress(container, percent) {
        if (!container) return;
        const fill = container.querySelector('.progress-fill');
        if (fill) {
            fill.style.width = `${percent}%`;
        }
    }
    
    function showToast(message, type = 'info') {
        const toast = document.createElement('div');
        toast.className = `toast-notification ${type}`;
        
        const icon = type === 'success' ? '✓' : type === 'error' ? '✗' : 'ℹ';
        toast.innerHTML = `
            <span style="font-size: 18px;">${icon}</span>
            <span>${message}</span>
        `;
        
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.style.animation = 'slideInRight 0.3s ease reverse';
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }
});
</script>
            
            <div class="config-group mt-4">
                <h4>Email et notifications</h4>
                <div class="config-item">
                    <label class="config-label">Email de contact</label>
                    <input type="email" class="form-control" name="contact_email" value="{{ $stats['etablissement']->getSetting('contact_email', $stats['etablissement']->email_contact ?? '') }}">
                </div>
                <div class="config-item mt-3">
                    <label class="config-label">Email de notification</label>
                    <input type="email" class="form-control" name="notification_email" value="{{ $stats['etablissement']->getSetting('notification_email', '') }}">
                </div>
            </div>
            
            <div class="config-group mt-4">
                <h4>Localisation</h4>
                <div class="config-item">
                    <label class="config-label">Adresse</label>
                    <input type="text" class="form-control" name="address" value="{{ $stats['etablissement']->getSetting('address', $stats['etablissement']->adresse ?? '') }}">
                </div>
                <div class="config-item mt-3">
                    <label class="config-label">Code postal</label>
                    <input type="text" class="form-control" name="zip_code" value="{{ $stats['etablissement']->getSetting('zip_code', $stats['etablissement']->zip_code ?? '') }}">
                </div>
                <div class="config-item mt-3">
                    <label class="config-label">Ville</label>
                    <input type="text" class="form-control" name="city" value="{{ $stats['etablissement']->getSetting('city', $stats['etablissement']->ville ?? '') }}">
                </div>
                <div class="config-item mt-3">
                    <label class="config-label">Téléphone</label>
                    <input type="text" class="form-control" name="phone" value="{{ $stats['etablissement']->getSetting('phone', $stats['etablissement']->phone ?? '') }}">
                </div>
            </div>
        </div>
        
        <div class="form-actions mt-4">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save me-2"></i>Sauvegarder la configuration
            </button>
        </div>
    </form>
</div>

<style>
    /* ============================================
   CONFIGURATION VISUELLE - STYLES MODERNES
   ============================================ */

.config-group {
    background: #ffffff;
    border-radius: 20px;
    padding: 24px;
    margin-bottom: 24px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05), 0 1px 2px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
    border: 1px solid #eef2f6;
}

.config-group:hover {
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
    border-color: #e2e8f0;
}

.config-group h4 {
    font-size: 18px;
    font-weight: 600;
    color: #1e293b;
    margin-bottom: 20px;
    padding-bottom: 12px;
    border-bottom: 2px solid #f1f5f9;
    display: flex;
    align-items: center;
    gap: 10px;
}

.config-group h4::before {
    content: '';
    width: 4px;
    height: 20px;
    background: linear-gradient(135deg, #3b82f6, #06d6a0);
    border-radius: 2px;
}

.config-item {
    margin-bottom: 20px;
}

.config-item label {
    display: block;
    font-size: 14px;
    font-weight: 500;
    color: #334155;
    margin-bottom: 8px;
    transition: color 0.2s ease;
}

.config-item label::after {
    content: '';
    display: inline-block;
    width: 4px;
    height: 4px;
    background: #ef4444;
    border-radius: 50%;
    margin-left: 4px;
    opacity: 0;
    transition: opacity 0.2s ease;
}

.config-item.required label::after {
    opacity: 1;
}

/* Upload Area Styles */
.upload-area {
    position: relative;
    border: 2px dashed #e2e8f0;
    border-radius: 16px;
    padding: 32px 24px;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
    background: #fafbfc;
    margin-bottom: 16px;
}

.upload-area:hover {
    border-color: #3b82f6;
    background: #f0f9ff;
    transform: translateY(-2px);
}

.upload-area.drag-over {
    border-color: #06d6a0;
    background: #f0fdf4;
    transform: scale(0.98);
}

.upload-icon {
    font-size: 48px;
    color: #94a3b8;
    margin-bottom: 16px;
    transition: all 0.3s ease;
}

.upload-area:hover .upload-icon {
    color: #3b82f6;
    transform: translateY(-4px);
}

.upload-title {
    font-size: 16px;
    font-weight: 500;
    color: #1e293b;
    margin-bottom: 8px;
}

.upload-subtitle {
    font-size: 13px;
    color: #64748b;
    margin-bottom: 16px;
}

.upload-button {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 16px;
    background: linear-gradient(135deg, #3b82f6, #2563eb);
    color: white;
    border-radius: 10px;
    font-size: 13px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s ease;
    border: none;
}

.upload-button:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
}

.upload-button:active {
    transform: translateY(0);
}

/* File Input Hidden */
.file-input-hidden {
    display: none;
}

/* Preview Container */
.preview-container {
    margin-top: 20px;
    padding: 16px;
    background: #f8fafc;
    border-radius: 12px;
    border: 1px solid #e2e8f0;
    display: none;
}

.preview-container.active {
    display: block;
    animation: fadeInUp 0.4s ease;
}

.preview-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 12px;
}

.preview-title {
    font-size: 13px;
    font-weight: 600;
    color: #475569;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.remove-image {
    background: #fee2e2;
    color: #ef4444;
    border: none;
    border-radius: 8px;
    padding: 6px 12px;
    font-size: 12px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.remove-image:hover {
    background: #fecaca;
    transform: scale(1.05);
}

.preview-image {
    display: flex;
    justify-content: center;
    align-items: center;
    background: white;
    border-radius: 12px;
    padding: 16px;
    min-height: 120px;
}

.preview-image img {
    max-width: 100%;
    max-height: 150px;
    object-fit: contain;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

/* Logo specific */
.logo-preview img {
    max-height: 80px;
}

/* Favicon specific */
.favicon-preview {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 12px;
}

.favicon-preview img {
    width: 64px;
    height: 64px;
    border-radius: 16px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.favicon-sizes {
    display: flex;
    gap: 16px;
    justify-content: center;
    margin-top: 12px;
}

.favicon-size {
    text-align: center;
    font-size: 11px;
    color: #64748b;
}

/* Progress Bar */
.upload-progress {
    margin-top: 12px;
    display: none;
}

.upload-progress.active {
    display: block;
}

.progress-bar {
    height: 4px;
    background: #e2e8f0;
    border-radius: 2px;
    overflow: hidden;
}

.progress-fill {
    height: 100%;
    background: linear-gradient(90deg, #3b82f6, #06d6a0);
    width: 0%;
    transition: width 0.3s ease;
    border-radius: 2px;
}

.progress-text {
    font-size: 12px;
    color: #64748b;
    margin-top: 8px;
    text-align: center;
}

/* Toast Notifications */
.toast-notification {
    position: fixed;
    bottom: 24px;
    right: 24px;
    background: #1e293b;
    color: white;
    padding: 12px 20px;
    border-radius: 12px;
    font-size: 14px;
    font-weight: 500;
    z-index: 9999;
    animation: slideInRight 0.3s ease;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    display: flex;
    align-items: center;
    gap: 12px;
}

.toast-notification.success {
    background: linear-gradient(135deg, #10b981, #059669);
}

.toast-notification.error {
    background: linear-gradient(135deg, #ef4444, #dc2626);
}

.toast-notification.info {
    background: linear-gradient(135deg, #3b82f6, #2563eb);
}

/* Animations */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes slideInRight {
    from {
        opacity: 0;
        transform: translateX(100px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes pulse {
    0%, 100% {
        opacity: 1;
    }
    50% {
        opacity: 0.5;
    }
}

.loading-spinner {
    display: inline-block;
    width: 16px;
    height: 16px;
    border: 2px solid rgba(255, 255, 255, 0.3);
    border-radius: 50%;
    border-top-color: white;
    animation: spin 0.6s linear infinite;
}

@keyframes spin {
    to {
        transform: rotate(360deg);
    }
}

/* Responsive */
@media (max-width: 768px) {
    .config-group {
        padding: 16px;
    }
    
    .upload-area {
        padding: 20px 16px;
    }
    
    .upload-icon {
        font-size: 36px;
    }
    
    .preview-image img {
        max-height: 100px;
    }
    
    .favicon-sizes {
        flex-wrap: wrap;
    }
}
</style>