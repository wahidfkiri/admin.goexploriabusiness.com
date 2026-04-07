@extends('layouts.app')

@section('title', 'Bibliothèque de thèmes — CMS')

@section('content')
<main class="dashboard-content">
<div class="global-themes-page">

    {{-- Header --}}
    <div class="page-header-modern">
        <div class="page-header-left">
            <div class="page-icon">
                <i class="fas fa-palette"></i>
            </div>
            <div>
                <h1 class="page-title">Bibliothèque de thèmes</h1>
                <p class="page-subtitle">Gérez les thèmes disponibles pour tous les établissements</p>
            </div>
        </div>
        <div class="page-header-actions">
            <a href="{{ route('cms.admin.themes.export') }}" class="btn-header-secondary">
                <i class="fas fa-download"></i> Exporter
            </a>
            <button class="btn-header-primary" data-bs-toggle="modal" data-bs-target="#uploadGlobalThemeModal">
                <i class="fas fa-upload"></i> Uploader un thème
            </button>
        </div>
    </div>

    {{-- Stats --}}
    <div class="themes-stats-row">
        <div class="theme-stat-card">
            <div class="ts-value">{{ $stats['total'] }}</div>
            <div class="ts-label">Thèmes disponibles</div>
        </div>
        <div class="theme-stat-card">
            <div class="ts-value">{{ $stats['default'] }}</div>
            <div class="ts-label">Thème par défaut</div>
        </div>
        <div class="theme-stat-card">
            <div class="ts-value">{{ $themes->total() }}</div>
            <div class="ts-label">Total</div>
        </div>
    </div>

    {{-- Grid --}}
    @if($themes->isEmpty())
        <div class="empty-global-state">
            <div class="eg-icon"><i class="fas fa-palette"></i></div>
            <h3>Aucun thème dans la bibliothèque</h3>
            <p>Uploadez votre premier thème en cliquant sur le bouton ci-dessus.</p>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadGlobalThemeModal">
                <i class="fas fa-upload me-2"></i>Uploader un thème
            </button>
        </div>
    @else
        <div class="global-themes-grid" id="globalThemesGrid">
            @foreach($themes as $theme)
                @include('cms::admin.themes.partials.theme-card', compact('theme'))
            @endforeach
        </div>

        {{-- Pagination --}}
        @if($themes->hasPages())
            <div class="pagination-modern mt-4">
                {{ $themes->links() }}
            </div>
        @endif
    @endif
</div>
</main>

{{-- ============================================================
     MODAL UPLOAD GLOBAL
     ============================================================ --}}
<div class="modal fade" id="uploadGlobalThemeModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-20">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-600">
                    <i class="fas fa-cloud-upload-alt me-2 text-primary"></i>
                    Uploader un thème global
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="upload-drop-zone" id="globalUploadArea">
                    <div class="udz-idle" id="udzIdle">
                        <i class="fas fa-cloud-upload-alt udz-icon"></i>
                        <h4>Glissez votre fichier ZIP ici</h4>
                        <p>ou cliquez pour sélectionner</p>
                        <input type="file" id="globalThemeFile" accept=".zip" hidden>
                        <button type="button" class="btn-udz-select"
                                onclick="document.getElementById('globalThemeFile').click()">
                            Sélectionner un fichier
                        </button>
                        <p class="udz-hint">Le ZIP doit contenir <code>layout.blade.php</code></p>
                    </div>
                    <div class="udz-file-selected" id="udzFileSelected" style="display:none">
                        <i class="fas fa-file-archive udz-file-icon"></i>
                        <div class="udz-file-info">
                            <span id="globalFileName" class="udz-file-name"></span>
                            <span id="globalFileSize" class="udz-file-size"></span>
                        </div>
                        <button type="button" class="udz-remove" onclick="resetGlobalUpload()">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>

                <div class="mt-3" id="globalThemeNameField" style="display:none">
                    <label class="form-label fw-500">Nom du thème</label>
                    <input type="text" class="form-control" id="globalThemeName" placeholder="Mon super thème">
                </div>

                <div id="uploadProgressWrapper" style="display:none" class="mt-3">
                    <div class="progress" style="height:6px; border-radius:3px;">
                        <div class="progress-bar bg-success progress-bar-striped progress-bar-animated"
                             id="uploadProgressBar" style="width:0%"></div>
                    </div>
                    <p class="text-muted small mt-1 mb-0" id="uploadProgressText">Envoi en cours...</p>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary" id="globalUploadBtn" disabled
                        onclick="submitGlobalTheme()">
                    <i class="fas fa-upload me-2"></i>Uploader
                </button>
            </div>
        </div>
    </div>
</div>

<style>
/* ============================================================
   PAGE HEADER
   ============================================================ */
.global-themes-page {
    padding: 24px;
    max-width: 1400px;
    margin: 0 auto;
}

.page-header-modern {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 28px;
    flex-wrap: wrap;
    gap: 16px;
}

.page-header-left {
    display: flex;
    align-items: center;
    gap: 16px;
}

.page-icon {
    width: 52px;
    height: 52px;
    background: linear-gradient(135deg, #f59e0b, #d97706);
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.4rem;
    flex-shrink: 0;
}

.page-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #1e293b;
    margin: 0 0 4px;
}

.page-subtitle {
    font-size: 0.85rem;
    color: #64748b;
    margin: 0;
}

.page-header-actions {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.btn-header-primary {
    background: linear-gradient(135deg, #4361ee, #3a56e4);
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 12px;
    font-size: 0.88rem;
    font-weight: 500;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: all 0.2s ease;
}

.btn-header-primary:hover {
    box-shadow: 0 4px 14px rgba(67,97,238,0.35);
    transform: translateY(-2px);
}

.btn-header-secondary {
    background: white;
    color: #475569;
    border: 1px solid #e2e8f0;
    padding: 10px 20px;
    border-radius: 12px;
    font-size: 0.88rem;
    font-weight: 500;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 8px;
    text-decoration: none;
    transition: all 0.2s ease;
}

.btn-header-secondary:hover {
    background: #f8fafc;
    color: #1e293b;
    text-decoration: none;
}

/* ============================================================
   STATS ROW
   ============================================================ */
.themes-stats-row {
    display: flex;
    gap: 16px;
    margin-bottom: 28px;
}

.theme-stat-card {
    background: white;
    border-radius: 14px;
    padding: 16px 24px;
    box-shadow: 0 1px 4px rgba(0,0,0,0.05);
    flex: 1;
    text-align: center;
}

.ts-value {
    font-size: 2rem;
    font-weight: 700;
    color: #1e293b;
    line-height: 1;
    margin-bottom: 4px;
}

.ts-label {
    font-size: 0.78rem;
    color: #94a3b8;
    font-weight: 500;
}

/* ============================================================
   THEMES GRID
   ============================================================ */
.global-themes-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
    gap: 20px;
}

/* ============================================================
   THEME CARD (partagé avec dashboard)
   ============================================================ */
.global-theme-card {
    background: white;
    border-radius: 20px;
    overflow: hidden;
    border: 2px solid transparent;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    transition: all 0.25s ease;
}

.global-theme-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 28px rgba(0,0,0,0.1);
}

.gtc-preview {
    position: relative;
    height: 180px;
    background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
}

.gtc-preview img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.4s ease;
}

.global-theme-card:hover .gtc-preview img {
    transform: scale(1.03);
}

.gtc-placeholder {
    font-size: 3rem;
    color: #94a3b8;
}

.gtc-storage {
    position: absolute;
    top: 10px;
    right: 10px;
    font-size: 0.62rem;
    font-weight: 700;
    padding: 2px 8px;
    border-radius: 20px;
    letter-spacing: 0.04em;
}

.gtc-storage.local {
    background: #dbeafe;
    color: #1d4ed8;
}

.gtc-storage.cdn {
    background: #fef3c7;
    color: #92400e;
}

.gtc-default-badge {
    position: absolute;
    top: 10px;
    left: 10px;
    background: #4361ee;
    color: white;
    font-size: 0.62rem;
    font-weight: 700;
    padding: 2px 8px;
    border-radius: 20px;
}

.gtc-body {
    padding: 18px;
}

.gtc-header {
    display: flex;
    justify-content: space-between;
    align-items: baseline;
    margin-bottom: 8px;
}

.gtc-name {
    font-size: 1rem;
    font-weight: 600;
    color: #1e293b;
    margin: 0;
}

.gtc-version {
    font-size: 0.7rem;
    color: #94a3b8;
    background: #f1f5f9;
    padding: 2px 8px;
    border-radius: 20px;
    flex-shrink: 0;
}

.gtc-desc {
    font-size: 0.8rem;
    color: #64748b;
    margin-bottom: 12px;
    line-height: 1.5;
}

.gtc-meta {
    font-size: 0.74rem;
    color: #94a3b8;
    margin-bottom: 14px;
    display: flex;
    align-items: center;
    gap: 6px;
}

.gtc-actions {
    display: flex;
    gap: 7px;
}

.gtc-btn {
    flex: 1;
    padding: 8px 12px;
    border-radius: 10px;
    font-size: 0.8rem;
    font-weight: 500;
    border: none;
    cursor: pointer;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    text-decoration: none;
}

.gtc-btn-duplicate {
    background: #f1f5f9;
    color: #475569;
}

.gtc-btn-duplicate:hover {
    background: #e2e8f0;
    text-decoration: none;
    color: #1e293b;
}

.gtc-btn-delete {
    background: #fef2f2;
    color: #ef4444;
    flex: 0 0 auto;
    padding: 8px 12px;
}

.gtc-btn-delete:hover {
    background: #fee2e2;
}

/* ============================================================
   EMPTY STATE
   ============================================================ */
.empty-global-state {
    text-align: center;
    padding: 80px 20px;
    background: white;
    border-radius: 20px;
    box-shadow: 0 1px 4px rgba(0,0,0,0.05);
}

.eg-icon {
    width: 90px;
    height: 90px;
    background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px;
    font-size: 2.5rem;
    color: #94a3b8;
}

.empty-global-state h3 {
    color: #1e293b;
    margin-bottom: 8px;
}

.empty-global-state p {
    color: #64748b;
    margin-bottom: 20px;
}

/* ============================================================
   UPLOAD MODAL
   ============================================================ */
.rounded-20 { border-radius: 20px; }
.fw-600 { font-weight: 600; }
.fw-500 { font-weight: 500; }

.upload-drop-zone {
    border: 2px dashed #cbd5e1;
    border-radius: 16px;
    padding: 36px 24px;
    text-align: center;
    background: #f8fafc;
    transition: all 0.3s ease;
    cursor: pointer;
}

.upload-drop-zone.drag-over {
    border-color: #4361ee;
    background: #eff2fe;
}

.udz-icon {
    font-size: 2.8rem;
    color: #94a3b8;
    margin-bottom: 12px;
    display: block;
}

.upload-drop-zone h4 {
    font-size: 1rem;
    color: #1e293b;
    margin-bottom: 4px;
}

.upload-drop-zone p {
    color: #94a3b8;
    font-size: 0.85rem;
    margin-bottom: 16px;
}

.btn-udz-select {
    background: #f1f5f9;
    border: none;
    padding: 8px 20px;
    border-radius: 10px;
    color: #475569;
    font-size: 0.85rem;
    cursor: pointer;
    margin-bottom: 12px;
    transition: background 0.2s;
}

.btn-udz-select:hover { background: #e2e8f0; }

.udz-hint {
    font-size: 0.75rem;
    color: #94a3b8;
    margin: 0;
}

.udz-file-selected {
    display: flex;
    align-items: center;
    gap: 12px;
}

.udz-file-icon {
    font-size: 1.8rem;
    color: #10b981;
    flex-shrink: 0;
}

.udz-file-info {
    flex: 1;
    text-align: left;
}

.udz-file-name {
    display: block;
    font-size: 0.9rem;
    font-weight: 500;
    color: #1e293b;
}

.udz-file-size {
    font-size: 0.78rem;
    color: #94a3b8;
}

.udz-remove {
    background: #fef2f2;
    border: none;
    color: #ef4444;
    width: 30px;
    height: 30px;
    border-radius: 8px;
    cursor: pointer;
    flex-shrink: 0;
}

.udz-remove:hover { background: #fee2e2; }

/* ============================================================
   RESPONSIVE
   ============================================================ */
@media (max-width: 768px) {
    .global-themes-grid { grid-template-columns: 1fr 1fr; }
    .themes-stats-row { flex-wrap: wrap; }
    .page-header-modern { flex-direction: column; align-items: flex-start; }
}

@media (max-width: 480px) {
    .global-themes-grid { grid-template-columns: 1fr; }
    .global-themes-page { padding: 16px; }
}
</style>
<script>
const globalUploadArea  = document.getElementById('globalUploadArea');
const globalThemeFile   = document.getElementById('globalThemeFile');
const udzIdle           = document.getElementById('udzIdle');
const udzFileSelected   = document.getElementById('udzFileSelected');
const globalFileName    = document.getElementById('globalFileName');
const globalFileSize    = document.getElementById('globalFileSize');
const globalThemeNameField = document.getElementById('globalThemeNameField');
const globalThemeName   = document.getElementById('globalThemeName');
const globalUploadBtn   = document.getElementById('globalUploadBtn');
const progressWrapper   = document.getElementById('uploadProgressWrapper');
const progressBar       = document.getElementById('uploadProgressBar');
const progressText      = document.getElementById('uploadProgressText');

// Drag & drop
globalUploadArea.addEventListener('dragover', e => {
    e.preventDefault();
    globalUploadArea.classList.add('drag-over');
});
globalUploadArea.addEventListener('dragleave', () => {
    globalUploadArea.classList.remove('drag-over');
});
globalUploadArea.addEventListener('drop', e => {
    e.preventDefault();
    globalUploadArea.classList.remove('drag-over');
    if (e.dataTransfer.files.length > 0) handleGlobalFile(e.dataTransfer.files[0]);
});

// Click to pick file
udzIdle.addEventListener('click', e => {
    if (!e.target.classList.contains('btn-udz-select')) {
        globalThemeFile.click();
    }
});

globalThemeFile.addEventListener('change', e => {
    if (e.target.files.length > 0) handleGlobalFile(e.target.files[0]);
});

function handleGlobalFile(file) {
    if (!file.name.endsWith('.zip')) {
        showToast('Veuillez sélectionner un fichier ZIP', 'error');
        return;
    }

    globalFileName.textContent = file.name;
    globalFileSize.textContent = formatBytes(file.size);
    udzIdle.style.display         = 'none';
    udzFileSelected.style.display = 'flex';
    globalThemeNameField.style.display = 'block';

    // Auto-fill name
    let name = file.name.replace('.zip', '').replace(/[_-]/g, ' ');
    name = name.split(' ').map(w => w.charAt(0).toUpperCase() + w.slice(1)).join(' ');
    globalThemeName.value = name;

    globalUploadBtn.disabled = false;
}

function resetGlobalUpload() {
    globalThemeFile.value             = '';
    udzIdle.style.display             = 'block';
    udzFileSelected.style.display     = 'none';
    globalThemeNameField.style.display = 'none';
    globalUploadBtn.disabled          = true;
    progressWrapper.style.display     = 'none';
}

function formatBytes(bytes) {
    if (bytes < 1024) return bytes + ' B';
    if (bytes < 1048576) return (bytes / 1024).toFixed(1) + ' KB';
    return (bytes / 1048576).toFixed(1) + ' MB';
}

function submitGlobalTheme() {
    if (!globalThemeFile.files.length) {
        showToast('Sélectionnez un fichier ZIP', 'error');
        return;
    }

    const formData = new FormData();
    formData.append('theme_file', globalThemeFile.files[0]);
    formData.append('name', globalThemeName.value || 'Nouveau thème');

    globalUploadBtn.disabled = true;
    globalUploadBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Upload...';
    progressWrapper.style.display = 'block';

    const xhr = new XMLHttpRequest();

    xhr.upload.addEventListener('progress', e => {
        if (e.lengthComputable) {
            const pct = Math.round((e.loaded / e.total) * 100);
            progressBar.style.width = pct + '%';
            progressText.textContent = `Envoi en cours... ${pct}%`;
        }
    });

    xhr.addEventListener('load', () => {
        try {
            const data = JSON.parse(xhr.responseText);
            if (data.success) {
                showToast('Thème uploadé avec succès !', 'success');

                // Insérer la carte dans la grille
                const grid = document.getElementById('globalThemesGrid');
                if (grid && data.html) {
                    grid.insertAdjacentHTML('afterbegin', data.html);
                }

                // Fermer le modal et reset
                bootstrap.Modal.getInstance(document.getElementById('uploadGlobalThemeModal')).hide();
                resetGlobalUpload();
            } else {
                showToast(data.message || 'Erreur lors de l\'upload', 'error');
            }
        } catch {
            showToast('Réponse invalide du serveur', 'error');
        }

        globalUploadBtn.disabled = false;
        globalUploadBtn.innerHTML = '<i class="fas fa-upload me-2"></i>Uploader';
        progressWrapper.style.display = 'none';
    });

    xhr.addEventListener('error', () => {
        showToast('Erreur réseau', 'error');
        globalUploadBtn.disabled = false;
        globalUploadBtn.innerHTML = '<i class="fas fa-upload me-2"></i>Uploader';
        progressWrapper.style.display = 'none';
    });

    xhr.open('POST', '{{ route('cms.admin.themes.store') }}');
    xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);
    xhr.setRequestHeader('Accept', 'application/json');
    xhr.send(formData);
}

// ---- Actions sur les cartes ----
function deleteGlobalTheme(themeId, btn) {
    if (!confirm('Supprimer ce thème de la bibliothèque ? Cette action est irréversible.')) return;

    btn.disabled = true;

    fetch(`/admin/cms/themes/${themeId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            const card = document.getElementById('gtc-' + themeId);
            if (card) {
                card.style.transition = 'opacity 0.3s, transform 0.3s';
                card.style.opacity    = '0';
                card.style.transform  = 'scale(0.95)';
                setTimeout(() => card.remove(), 300);
            }
            showToast('Thème supprimé', 'success');
        } else {
            showToast(data.message || 'Erreur', 'error');
            btn.disabled = false;
        }
    })
    .catch(() => {
        showToast('Erreur réseau', 'error');
        btn.disabled = false;
    });
}

function duplicateGlobalTheme(themeId, btn) {
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

    fetch(`/admin/cms/themes/${themeId}/duplicate`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            showToast('Thème dupliqué avec succès', 'success');
            setTimeout(() => location.reload(), 800);
        } else {
            showToast(data.message || 'Erreur', 'error');
        }
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-copy"></i> Dupliquer';
    })
    .catch(() => {
        showToast('Erreur réseau', 'error');
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-copy"></i> Dupliquer';
    });
}

function showToast(message, type = 'success') {
    const existing = document.querySelectorAll('.toast-notification');
    existing.forEach(t => t.remove());

    const toast = document.createElement('div');
    toast.className = `toast-notification ${type}`;
    toast.innerHTML = `
        <div class="toast-content">
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
            <span>${message}</span>
        </div>`;
    document.body.appendChild(toast);
    setTimeout(() => toast.classList.add('show'), 100);
    setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => toast.remove(), 300);
    }, 3500);
}
</script>
@endsection