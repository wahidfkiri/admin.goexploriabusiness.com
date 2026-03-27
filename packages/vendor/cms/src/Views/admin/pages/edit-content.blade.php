@extends('cms::layouts.editor')

@section('title', 'Éditer le contenu : ' . $page->title)

@section('content')
<div class="cms-editor-container">
    <div class="cms-editor-header">
        <div>
            <h1 class="cms-editor-title">
                <span class="cms-editor-title-icon"><i class="fas fa-pencil-ruler"></i></span>
                Éditer le contenu : {{ $page->title }}
            </h1>
            <p class="cms-editor-description">Modifiez le contenu de votre page avec l'éditeur visuel</p>
        </div>
        
        <div class="cms-editor-actions">
            <a href="{{ route('cms.admin.pages.edit', ['etablissementId' => $etablissement->id, 'id' => $page->id]) }}" class="cms-btn cms-btn-outline">
                <i class="fas fa-info-circle me-2"></i>Informations
            </a>
            <a href="{{ route('cms.admin.pages.index', ['etablissementId' => $etablissement->id]) }}" class="cms-btn cms-btn-outline">
                <i class="fas fa-arrow-left me-2"></i>Retour
            </a>
            <button class="cms-btn cms-btn-primary" id="saveContentBtn">
                <i class="fas fa-save me-2"></i>Sauvegarder
            </button>
        </div>
    </div>

    <div class="cms-editor-layout">
        <!-- Barre latérale des blocks -->
        <div class="cms-editor-sidebar">
            <div class="cms-sidebar-header">
                <h4><i class="fas fa-th-large"></i> Bibliothèque de blocs</h4>
                <div class="cms-sidebar-search">
                    <i class="fas fa-search"></i>
                    <input type="text" id="cmsBlockSearch" placeholder="Rechercher un bloc...">
                </div>
            </div>
            
            <div class="cms-sidebar-categories" id="cmsBlocksCategories">
                <div class="cms-category-item active" data-category="all">
                    <i class="fas fa-th-large"></i>
                    <span>Tous les blocs</span>
                </div>
            </div>
            
            <div class="cms-sidebar-blocks" id="cmsBlocksList">
                <div class="cms-blocks-wrapper">
                    <div class="cms-loading-blocks">
                        <div class="cms-spinner"></div>
                        <p>Chargement des blocs...</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Éditeur GrapeJS -->
        <div class="cms-editor-main">
            <div id="cms-editor-container"></div>
            <textarea name="content" id="cms-content" style="display: none;">{{ old('content', $page->content) }}</textarea>
        </div>
    </div>
</div>

<!-- Loading Overlay -->
<div class="cms-loading-overlay" id="cmsLoadingOverlay" style="display: none;">
    <div class="cms-spinner-modern">
        <div class="cms-spinner"></div>
        <p>Sauvegarde en cours...</p>
    </div>
</div>

<style>
/* ============================================
   CMS EDITOR STYLES - Classes personnalisées
   ============================================ */
.cms-editor-container {
    padding: 20px;
    height: 100vh;
    display: flex;
    flex-direction: column;
    overflow: hidden;
    background: #f1f5f9;
}

.cms-editor-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    flex-wrap: wrap;
    gap: 15px;
    flex-shrink: 0;
    background: transparent;
    padding: 0;
}

.cms-editor-title {
    font-size: 1.5rem;
    font-weight: 600;
    margin: 0;
    display: flex;
    align-items: center;
    color: #1e293b;
}

.cms-editor-title-icon {
    background: linear-gradient(135deg, #4361ee, #3a56e4);
    width: 45px;
    height: 45px;
    border-radius: 12px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    margin-right: 12px;
    color: white;
}

.cms-editor-description {
    color: #64748b;
    margin-left: 57px;
    font-size: 0.85rem;
}

.cms-editor-actions {
    display: flex;
    gap: 10px;
}

.cms-btn {
    padding: 8px 16px;
    border-radius: 10px;
    font-size: 0.85rem;
    font-weight: 500;
    transition: all 0.2s ease;
    cursor: pointer;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
}

.cms-btn-outline {
    background: white;
    border: 1px solid #e2e8f0;
    color: #475569;
}

.cms-btn-outline:hover {
    background: #f8fafc;
    border-color: #cbd5e1;
    text-decoration: none;
    color: #475569;
}

.cms-btn-primary {
    background: linear-gradient(135deg, #4361ee, #3a56e4);
    border: none;
    color: white;
}

.cms-btn-primary:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(67, 97, 238, 0.3);
    color: white;
}

/* Editor Layout */
.cms-editor-layout {
    display: flex;
    gap: 20px;
    flex: 1;
    min-height: 0;
    overflow: hidden;
}

/* Sidebar */
.cms-editor-sidebar {
    width: 320px;
    background: white;
    border-radius: 16px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    display: flex;
    flex-direction: column;
    overflow: hidden;
    flex-shrink: 0;
}

.cms-sidebar-header {
    padding: 16px 20px;
    border-bottom: 1px solid #eef2f6;
}

.cms-sidebar-header h4 {
    font-size: 1rem;
    font-weight: 600;
    margin: 0 0 12px 0;
    color: #1e293b;
    display: flex;
    align-items: center;
    gap: 8px;
}

.cms-sidebar-search {
    position: relative;
}

.cms-sidebar-search i {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: #94a3b8;
    font-size: 0.8rem;
}

.cms-sidebar-search input {
    width: 100%;
    padding: 8px 12px 8px 32px;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    font-size: 0.85rem;
    background: #f8fafc;
    transition: all 0.2s ease;
}

.cms-sidebar-search input:focus {
    outline: none;
    border-color: #4361ee;
    background: white;
    box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
}

/* Categories */
.cms-sidebar-categories {
    padding: 12px 20px;
    border-bottom: 1px solid #eef2f6;
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.cms-category-item {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 5px 12px;
    background: #f8fafc;
    border-radius: 20px;
    font-size: 0.75rem;
    color: #475569;
    cursor: pointer;
    transition: all 0.2s ease;
}

.cms-category-item i {
    font-size: 0.7rem;
}

.cms-category-item:hover {
    background: #eef2ff;
    color: #4361ee;
}

.cms-category-item.active {
    background: linear-gradient(135deg, #4361ee, #3a56e4);
    color: white;
}

/* ============================================
   BLOCKS SECTION - Styles isolés
   ============================================ */
.cms-sidebar-blocks {
    flex: 1;
    overflow-y: auto;
    padding: 16px 20px;
}

.cms-blocks-wrapper {
    display: flex;
    flex-direction: column;
    gap: 10px;
    width: 100%;
}

.cms-loading-blocks {
    text-align: center;
    padding: 40px 20px;
    color: #94a3b8;
}

.cms-loading-blocks .cms-spinner {
    width: 30px;
    height: 30px;
    border: 2px solid #e2e8f0;
    border-top-color: #4361ee;
    border-radius: 50%;
    animation: cms-spin 0.8s linear infinite;
    margin: 0 auto 10px;
}

@keyframes cms-spin {
    to { transform: rotate(360deg); }
}

.cms-block-item {
    display: flex !important;
    align-items: center !important;
    gap: 12px !important;
    background: #f8fafc !important;
    border: 1px solid #eef2f6 !important;
    border-radius: 12px !important;
    padding: 12px 16px !important;
    cursor: grab !important;
    transition: all 0.2s ease !important;
    width: 100% !important;
    box-sizing: border-box !important;
    margin: 0 !important;
}

.cms-block-item:hover {
    background: #f1f5f9 !important;
    border-color: #4361ee !important;
    transform: translateX(3px) !important;
}

.cms-block-item:active {
    cursor: grabbing !important;
}

.cms-block-icon {
    width: 36px !important;
    height: 36px !important;
    border-radius: 10px !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    color: white !important;
    font-size: 1rem !important;
    flex-shrink: 0 !important;
}

.cms-block-info {
    flex: 1 !important;
    min-width: 0 !important;
}

.cms-block-name {
    font-size: 0.85rem !important;
    font-weight: 500 !important;
    color: #1e293b !important;
    margin-bottom: 4px !important;
    white-space: nowrap !important;
    overflow: hidden !important;
    text-overflow: ellipsis !important;
}

.cms-block-category {
    font-size: 0.65rem !important;
    color: #94a3b8 !important;
    display: flex !important;
    align-items: center !important;
    gap: 4px !important;
}

.cms-block-category i {
    font-size: 0.6rem !important;
}

.cms-empty-blocks {
    text-align: center;
    padding: 40px 20px;
    color: #94a3b8;
}

.cms-empty-blocks i {
    font-size: 2rem;
    margin-bottom: 10px;
    display: block;
}

/* Editor Main */
.cms-editor-main {
    flex: 1;
    background: white;
    border-radius: 16px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    overflow: hidden;
    display: flex;
    flex-direction: column;
}

#cms-editor-container {
    flex: 1;
    height: 100%;
}

/* Loading Overlay */
.cms-loading-overlay {
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

.cms-spinner-modern {
    background: white;
    padding: 30px;
    border-radius: 20px;
    text-align: center;
}

.cms-spinner-modern .cms-spinner {
    width: 40px;
    height: 40px;
    border: 3px solid #e2e8f0;
    border-top-color: #4361ee;
    border-radius: 50%;
    animation: cms-spin 0.8s linear infinite;
    margin: 0 auto 16px;
}

/* Scrollbar */
.cms-sidebar-blocks::-webkit-scrollbar {
    width: 4px;
}

.cms-sidebar-blocks::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 4px;
}

.cms-sidebar-blocks::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 4px;
}

/* Responsive */
@media (max-width: 992px) {
    .cms-editor-sidebar {
        width: 280px;
    }
}

@media (max-width: 768px) {
    .cms-editor-container {
        padding: 15px;
        height: auto;
    }
    
    .cms-editor-layout {
        flex-direction: column;
    }
    
    .cms-editor-sidebar {
        width: 100%;
        max-height: 400px;
    }
    
    .cms-editor-header {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .cms-editor-description {
        margin-left: 57px;
    }
    
    .cms-editor-actions {
        width: 100%;
        justify-content: flex-start;
    }
}
</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/grapesjs@0.21.5/dist/grapes.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/grapesjs@0.21.5/dist/css/grapes.min.css">

<script>
let editor = null;
let blocks = [];
const currentEtablissementId = {{ $etablissement->id }};
const pageId = {{ $page->id }};

document.addEventListener('DOMContentLoaded', function() {
    // Initialiser GrapeJS
    const textarea = document.getElementById('cms-content');
    const initialContent = textarea.value;
    
    editor = grapesjs.init({
        container: '#cms-editor-container',
        height: '100%',
        storageManager: false,
        fromElement: true,
        components: initialContent,
        styleManager: {
            sectors: [{
                name: 'Dimension',
                open: false,
                properties: ['width', 'min-width', 'height', 'min-height', 'margin', 'padding']
            }, {
                name: 'Typography',
                open: false,
                properties: ['font-family', 'font-size', 'font-weight', 'letter-spacing', 'color', 'line-height', 'text-align']
            }, {
                name: 'Background',
                open: false,
                properties: ['background-color', 'background-image', 'background-repeat', 'background-position', 'background-size']
            }, {
                name: 'Border',
                open: false,
                properties: ['border-radius', 'border-color', 'border-width', 'border-style']
            }]
        }
    });
    
    // Charger les blocks
    loadBlocks();
    
    // Sauvegarde
    const saveBtn = document.getElementById('saveContentBtn');
    
    function saveContent() {
        if (editor) {
            const content = editor.getHtml();
            document.getElementById('cms-content').value = content;
        }
        
        const formData = new FormData();
        formData.append('content', document.getElementById('cms-content').value);
        formData.append('_method', 'PUT');
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
        
        showLoading();
        
        fetch(`/admin/cms/${currentEtablissementId}/pages/${pageId}/update-content`, {
            method: 'POST',
            headers: {
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            hideLoading();
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Succès!',
                    text: data.message,
                    confirmButtonColor: '#4361ee',
                    timer: 2000,
                    showConfirmButton: false
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur',
                    text: data.message,
                    confirmButtonColor: '#4361ee'
                });
            }
        })
        .catch(error => {
            hideLoading();
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Erreur',
                text: 'Une erreur est survenue',
                confirmButtonColor: '#4361ee'
            });
        });
    }
    
    saveBtn.addEventListener('click', (e) => {
        e.preventDefault();
        saveContent();
    });
    
    // Recherche de blocks
    const searchInput = document.getElementById('cmsBlockSearch');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            filterBlocks(this.value);
        });
    }
});

function loadBlocks() {
    fetch(`/admin/cms/${currentEtablissementId}/blocks/api`, {
        method: 'GET',
        headers: {
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            blocks = data.blocks;
            displayCategories(data.categories);
            displayBlocks(blocks);
        }
    })
    .catch(error => {
        console.error('Error loading blocks:', error);
        const wrapper = document.querySelector('#cmsBlocksList .cms-blocks-wrapper');
        if (wrapper) {
            wrapper.innerHTML = `
                <div class="cms-empty-blocks">
                    <i class="fas fa-exclamation-circle"></i>
                    <p>Erreur de chargement des blocs</p>
                </div>
            `;
        }
    });
}

function displayCategories(categories) {
    const container = document.getElementById('cmsBlocksCategories');
    let html = `
        <div class="cms-category-item active" data-category="all" onclick="filterByCategory('all')">
            <i class="fas fa-th-large"></i>
            <span>Tous les blocs</span>
        </div>
    `;
    
    if (categories && categories.length) {
        categories.forEach(cat => {
            html += `
                <div class="cms-category-item" data-category="${cat.id}" onclick="filterByCategory('${cat.id}')">
                    <i class="fas fa-tag"></i>
                    <span>${escapeHtml(cat.label)}</span>
                </div>
            `;
        });
    }
    
    container.innerHTML = html;
}

function displayBlocks(blocksToShow) {
    const wrapper = document.querySelector('#cmsBlocksList .cms-blocks-wrapper');
    
    if (!wrapper) return;
    
    if (!blocksToShow || blocksToShow.length === 0) {
        wrapper.innerHTML = `
            <div class="cms-empty-blocks">
                <i class="fas fa-box-open"></i>
                <p>Aucun bloc disponible</p>
            </div>
        `;
        return;
    }
    
    let html = '';
    blocksToShow.forEach(block => {
        const colors = ['#4361ee', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#ec489a'];
        const colorIndex = block.id % colors.length;
        
        html += `
            <div class="cms-block-item" draggable="true" data-block-content="${escapeHtml(block.content)}">
                <div class="cms-block-icon" style="background: linear-gradient(135deg, ${colors[colorIndex]}, ${colors[colorIndex]}dd);">
                    <i class="fas fa-puzzle-piece"></i>
                </div>
                <div class="cms-block-info">
                    <div class="cms-block-name">${escapeHtml(block.label)}</div>
                    <div class="cms-block-category">
                        <i class="fas fa-tag"></i>
                        <span>${escapeHtml(block.category)}</span>
                    </div>
                </div>
            </div>
        `;
    });
    
    wrapper.innerHTML = html;
    
    // Ajouter les événements de drag & drop
    document.querySelectorAll('.cms-block-item').forEach(item => {
        item.addEventListener('dragstart', handleDragStart);
        item.addEventListener('dragend', handleDragEnd);
    });
}

function filterByCategory(categoryId) {
    document.querySelectorAll('.cms-category-item').forEach(cat => {
        cat.classList.remove('active');
    });
    const activeCat = document.querySelector(`.cms-category-item[data-category="${categoryId}"]`);
    if (activeCat) activeCat.classList.add('active');
    
    if (categoryId === 'all') {
        displayBlocks(blocks);
    } else {
        const filteredBlocks = blocks.filter(block => {
            const catSlug = block.category.toLowerCase().replace(/[^a-z0-9]+/g, '-');
            return catSlug === categoryId;
        });
        displayBlocks(filteredBlocks);
    }
}

function filterBlocks(searchTerm) {
    if (!searchTerm.trim()) {
        displayBlocks(blocks);
        return;
    }
    
    const term = searchTerm.toLowerCase();
    const filtered = blocks.filter(block => 
        block.label.toLowerCase().includes(term) ||
        block.category.toLowerCase().includes(term)
    );
    displayBlocks(filtered);
}

function handleDragStart(e) {
    const blockItem = e.target.closest('.cms-block-item');
    if (!blockItem) return;
    
    const blockContent = blockItem.getAttribute('data-block-content');
    e.dataTransfer.setData('text/html', blockContent);
    e.dataTransfer.effectAllowed = 'copy';
    
    blockItem.style.opacity = '0.5';
}

function handleDragEnd(e) {
    const blockItem = e.target.closest('.cms-block-item');
    if (blockItem) {
        blockItem.style.opacity = '1';
    }
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function showLoading() {
    document.getElementById('cmsLoadingOverlay').style.display = 'flex';
}

function hideLoading() {
    document.getElementById('cmsLoadingOverlay').style.display = 'none';
}
</script>
@endsection