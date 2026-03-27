<div class="modal fade" id="uploadThemeModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title">Uploader un thème</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="uploadThemeForm" enctype="multipart/form-data" method="POST">
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

<style>
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
</style>

<script>
const uploadArea = document.getElementById('uploadArea');
const themeFile = document.getElementById('themeFile');
const fileInfo = document.getElementById('fileInfo');
const fileName = document.getElementById('fileName');
const themeNameField = document.getElementById('themeNameField');
const themeName = document.getElementById('themeName');
const uploadBtn = document.getElementById('uploadBtn');

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
themeFile.addEventListener('change', (e) => {
    if (e.target.files.length > 0) {
        handleFile(e.target.files[0]);
    }
});

function handleFile(file) {
    if (!file.name.endsWith('.zip')) {
        showToast('Veuillez sélectionner un fichier ZIP', 'error');
        return;
    }
    
    // Display file info
    fileName.textContent = file.name;
    fileInfo.style.display = 'flex';
    themeNameField.style.display = 'block';
    
    // Auto-populate theme name from filename
    let name = file.name.replace('.zip', '');
    name = name.replace(/[_-]/g, ' ');
    name = name.split(' ').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ');
    themeName.value = name;
    
    uploadBtn.disabled = false;
}

function removeFile() {
    themeFile.value = '';
    fileInfo.style.display = 'none';
    themeNameField.style.display = 'none';
    uploadBtn.disabled = true;
}

// Form submission
document.getElementById('uploadThemeForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    
    if (!themeFile.files.length) {
        showToast('Veuillez sélectionner un fichier', 'error');
        return;
    }
    
    const formData = new FormData();
    formData.append('theme_file', themeFile.files[0]);
    formData.append('name', themeName.value);
    
    showLoading();
    
    try {
        const response = await fetch(`/admin/cms/${etablissementId}/themes`, {
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
            bootstrap.Modal.getInstance(document.getElementById('uploadThemeModal')).hide();
            
            // Add new theme card to grid
            const themesGrid = document.getElementById('themesGrid');
            if (themesGrid) {
                themesGrid.insertAdjacentHTML('beforeend', data.html);
            }
            
            showToast(data.message, 'success');
            
            // Reset form
            removeFile();
        } else {
            showToast(data.message, 'error');
        }
    } catch (error) {
        hideLoading();
        showToast('Erreur lors de l\'upload', 'error');
    }
});
</script>