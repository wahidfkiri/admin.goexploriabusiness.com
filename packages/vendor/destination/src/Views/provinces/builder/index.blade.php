<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@if(isset($template)){{ $template->name }} - @endif Constructeur de Pages Web</title>
    <!-- Fonts & Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- GrapesJS CSS via CDN -->
    <link rel="stylesheet" href="https://unpkg.com/grapesjs/dist/css/grapes.min.css">
    
    <link rel="stylesheet" href="{{ asset('vendor/editor/css/editor.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/editor/css/custom.css') }}">
    
    <style>
        .preview-frame {
            width: 100%;
            height: 600px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            background: white;
        }
        
        .preview-modal-footer {
            display: flex;
            justify-content: flex-end;
            padding: 15px;
            border-top: 1px solid #e2e8f0;
        }
        
        .modal-content {
            max-width: 90%;
            margin: 5vh auto;
        }
        
        .preview-fullscreen-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            background: #3b82f6;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
        }
        
        .preview-fullscreen-btn:hover {
            background: #2563eb;
        }
        
        /* Styles pour la navigation des catégories */
        .categories-scroll-container {
            position: relative;
            display: contents;
            align-items: center;
            flex: 1;
        }
        
        .categories-scroll {
            display: flex;
            overflow-x: auto;
            scroll-behavior: smooth;
            flex: 1;
            padding: 0 5px;
            scrollbar-width: none; /* Firefox */
            -ms-overflow-style: none; /* IE/Edge */
        }
        
        .categories-scroll::-webkit-scrollbar {
            display: none; /* Chrome/Safari/Opera */
        }
        
        .categories-nav-btn {
            background: #f1f5f9;
            border: none;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: #64748b;
            font-size: 12px;
            flex-shrink: 0;
            transition: all 0.2s ease;
        }
        
        .categories-nav-btn:hover {
            background: #e2e8f0;
            color: #475569;
        }
        
        .categories-nav-btn.left {
            margin-right: 5px;
        }
        
        .categories-nav-btn.right {
            margin-left: 5px;
        }
        
        .categories-nav-btn:disabled {
            opacity: 0.4;
            cursor: not-allowed;
        }
        
        .categories-nav-btn i {
            font-size: 10px;
        }
        
        /* .blocks-categories-nav {
            display: flex;
            align-items: center;
            padding: 10px 15px;
            border-bottom: 1px solid #e2e8f0;
            background: #f8fafc;
            position: relative;
        } */
        
        /* Animation des catégories */
        .category-tab {
            animation: fadeInSlide 0.3s ease-out forwards;
        }
        
        @keyframes fadeInSlide {
            from {
                opacity: 0;
                transform: translateY(5px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
    <!-- Barre supérieure -->
    <div class="top-bar">
        <div class="logo">
            <i class="fas fa-paint-brush logo-icon"></i>
            <span>Constructeur de Pages Web</span>
        </div>
        
        <div class="menu-actions">
            <button class="menu-btn danger" onclick="clearCanvas()" title="Effacer tout">
                <i class="fas fa-trash"></i>
                Vider le canevas
            </button>
            <button class="menu-btn" onclick="showPreviewInNewTab()" title="Aperçu">
                <i class="fas fa-eye"></i>
                Afficher l'aperçu
            </button>
            <button class="menu-btn success" onclick="saveTemplate()" title="Sauvegarder">
                <i class="fas fa-save"></i>
                Sauvegarder
            </button>
        </div>
    </div>

    <!-- Container principal -->
    <div class="editor-container">
        <!-- Barre latérale gauche - Blocks & Templates -->
        <div class="sidebar-left">
            <div class="sidebar-header">
                <div class="sidebar-title" style="display:none;">
                    <i class="fas fa-cube"></i>
                    <span>Bibliothèque de Blocs</span>
                    <div class="sidebar-badge">PRO</div>
                </div>
                <button class="sidebar-toggle" onclick="toggleSidebar()">
                    <i class="fas fa-chevron-left"></i>
                </button>
            </div>
            
            <div class="blocks-search-container">
                <div class="search-wrapper">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" class="blocks-search-input" 
                           id="blockSearch" 
                           placeholder="Rechercher des blocs, catégories, tags...">
                    <button class="search-clear" onclick="clearSearch()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <div class="blocks-quick-filters">
                    <button class="filter-chip active" data-filter="all">
                        <i class="fas fa-layer-group"></i>
                        <span>Tous</span>
                    </button>
                    <button class="filter-chip" data-filter="popular">
                        <i class="fas fa-fire"></i>
                        <span>Populaires</span>
                    </button>
                    <button class="filter-chip" data-filter="free">
                        <i class="fas fa-bolt"></i>
                        <span>Gratuits</span>
                    </button>
                    <button class="filter-chip" data-filter="responsive">
                        <i class="fas fa-mobile-alt"></i>
                        <span>Mobile</span>
                    </button>
                </div>
            </div>
            
            <!-- Navigation des catégories avec boutons -->
            <div class="blocks-categories-nav">
                <button class="categories-nav-btn left" onclick="scrollCategories(-150)" title="Défiler vers la gauche">
                    <i class="fas fa-chevron-left"></i>
                </button>
                
                <div class="categories-scroll-container">
                    <div class="categories-scroll" id="categoriesScroll">
                        <!-- Les catégories seront générées dynamiquement -->
                    </div>
                </div>
                
                <button class="categories-nav-btn right" onclick="scrollCategories(150)" title="Défiler vers la droite">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
            
            <div class="blocks-content">
                <div class="blocks-stats-bar">
                    <div class="stat-item">
                        <div class="stat-value" id="blocksCount">0</div>
                        <div class="stat-label">Blocs</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value" id="freeCount">0</div>
                        <div class="stat-label">Gratuits</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value" id="proCount">0</div>
                        <div class="stat-label">PRO</div>
                    </div>
                </div>
                
                <div class="blocks-grid-modern" id="blocksContainer">
                    <!-- Les blocs seront chargés dynamiquement -->
                </div>
                
                <div class="blocks-empty-state" id="blocksEmptyState" style="display: none;">
                    <div class="empty-icon">
                        <i class="fas fa-cubes"></i>
                    </div>
                    <h3>Aucun bloc trouvé</h3>
                    <p>Essayez d'ajuster votre recherche ou vos filtres</p>
                    <button class="btn-primary" onclick="resetFilters()">
                        <i class="fas fa-redo"></i>
                        Réinitialiser les Filtres
                    </button>
                </div>
            </div>
        </div>

        <!-- Zone éditeur principale -->
        <div class="editor-main">
            <div id="gjs"></div>
        </div>

        <!-- Panneau droit amélioré -->
        <div class="sidebar-right" style="display:none;">
            <div class="right-panel-tabs">
                <button class="right-panel-tab active" onclick="showRightPanel('layers')">
                    <i class="fas fa-layer-group"></i> Calques
                </button>
                <button class="right-panel-tab" onclick="showRightPanel('history')">
                    <i class="fas fa-history"></i> Historique
                </button>
                <button class="right-panel-tab" onclick="showRightPanel('settings')">
                    <i class="fas fa-cog"></i> Paramètres
                </button>
            </div>
            
            <!-- Panneau Couches -->
            <div class="right-panel-content active" id="right-panel-layers">
                <div class="layers-container">
                    <div class="layers-list" id="layersList">
                        <!-- Les couches seront chargées dynamiquement -->
                    </div>
                </div>
            </div>
            
            <!-- Panneau Historique -->
            <div class="right-panel-content" id="right-panel-history">
                <div class="history-container">
                    <div class="history-list" id="historyList">
                        <!-- L'historique sera chargé dynamiquement -->
                    </div>
                </div>
            </div>
            
            <!-- Panneau Paramètres -->
            <div class="right-panel-content" id="right-panel-settings">
                <div class="settings-container">
                    <div class="settings-section">
                        <div class="settings-title">Paramètres du Canevas</div>
                        <div class="settings-group">
                            <div class="setting-item">
                                <span class="setting-label">Afficher la Grille</span>
                                <div class="setting-control">
                                    <label class="switch">
                                        <input type="checkbox" id="showGrid" checked>
                                        <span class="slider"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="setting-item">
                                <span class="setting-label">Afficher les Contours</span>
                                <div class="setting-control">
                                    <label class="switch">
                                        <input type="checkbox" id="showOutlines" checked>
                                        <span class="slider"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="setting-item">
                                <span class="setting-label">Largeur du Canevas</span>
                                <div class="setting-control">
                                    <select class="control-select" id="canvasWidth">
                                        <option value="100%">100%</option>
                                        <option value="1200px">Bureau (1200px)</option>
                                        <option value="768px">Tablette (768px)</option>
                                        <option value="375px">Mobile (375px)</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="settings-section">
                        <div class="settings-title">Paramètres de l'Éditeur</div>
                        <div class="settings-group">
                            <div class="setting-item">
                                <span class="setting-label">Sauvegarde auto.</span>
                                <div class="setting-control">
                                    <label class="switch">
                                        <input type="checkbox" id="autoSave">
                                        <span class="slider"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="setting-item">
                                <span class="setting-label">Aligner à la Grille</span>
                                <div class="setting-control">
                                    <label class="switch">
                                        <input type="checkbox" id="snapToGrid">
                                        <span class="slider"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="setting-item">
                                <span class="setting-label">Mode Sombre</span>
                                <div class="setting-control">
                                    <label class="switch">
                                        <input type="checkbox" id="darkMode" checked>
                                        <span class="slider"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="settings-section">
                        <div class="settings-title">Paramètres d'Export</div>
                        <div class="settings-group">
                            <div class="setting-item">
                                <span class="setting-label">Format</span>
                                <div class="setting-control">
                                    <select class="control-select" id="exportFormat">
                                        <option value="html">HTML</option>
                                        <option value="react">React</option>
                                        <option value="vue">Vue</option>
                                    </select>
                                </div>
                            </div>
                            <div class="setting-item">
                                <span class="setting-label">Minifier CSS</span>
                                <div class="setting-control">
                                    <label class="switch">
                                        <input type="checkbox" id="minifyCSS" checked>
                                        <span class="slider"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="setting-item">
                                <span class="setting-label">Minifier HTML</span>
                                <div class="setting-control">
                                    <label class="switch">
                                        <input type="checkbox" id="minifyHTML">
                                        <span class="slider"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Code -->
    <div id="codeModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">
                    <i class="fas fa-code"></i>
                    Code Généré
                </div>
                <button class="modal-close" onclick="closeModal('codeModal')">&times;</button>
            </div>
            <div class="modal-body code-modal-body">
                <div class="code-actions">
                    <button onclick="copyCode()" class="menu-btn">
                        <i class="fas fa-copy"></i> Copier le Code
                    </button>
                </div>
                <textarea id="codeEditor" class="code-editor"></textarea>
            </div>
        </div>
    </div>

    <!-- Modal Preview -->
    <div id="previewModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">
                    <i class="fas fa-eye"></i>
                    Aperçu
                </div>
                <button class="modal-close" onclick="closeModal('previewModal')">&times;</button>
            </div>
            <div class="modal-body">
                <iframe id="previewFrame" class="preview-frame"></iframe>
            </div>
            <div class="preview-modal-footer">
                <button class="preview-fullscreen-btn" onclick="showPreviewInNewTab()">
                    <i class="fas fa-external-link-alt"></i>
                    Ouvrir dans un Nouvel Onglet
                </button>
            </div>
        </div>
    </div>

    <!-- Notifications -->
    <div id="notifications"></div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <!-- Scripts CDN -->
    <script src="https://unpkg.com/grapesjs"></script>
    <script src="https://unpkg.com/grapesjs-preset-webpage"></script>
    <script src="https://unpkg.com/grapesjs-blocks-basic"></script>
    <script src="https://unpkg.com/grapesjs-plugin-forms"></script>
    <script src="https://unpkg.com/grapesjs-tabs"></script>
    <script src="https://unpkg.com/grapesjs-custom-code"></script>
    <script src="https://unpkg.com/grapesjs-tooltip"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Script principal -->
    <script>
        // === CONFIGURATION ===
        const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        let editor;
        window.currentTemplateId = null;
        let allBlocks = [];
        let allSections = [];
        let dropIndicator = null;

        // === INITIALISATION DE L'ÉDITEUR ===
        function initEditor() {
            if (!document.getElementById('gjs')) {
                console.error('Élément #gjs non trouvé dans le DOM');
                setTimeout(initEditor, 100);
                return;
            }
            
            console.log('Initialisation de l\'éditeur GrapesJS...');
            
            editor = grapesjs.init({
                container: '#gjs',
                height: '100%',
                fromElement: true,
                storageManager: false,
                
                plugins: [
                    'grapesjs-preset-webpage',
                    'grapesjs-blocks-basic',
                    'grapesjs-plugin-forms',
                    'grapesjs-tabs',
                    'grapesjs-custom-code'
                ],
                
                pluginsOpts: {
                    'grapesjs-preset-webpage': {
                        blocks: []
                    }
                },
                
                styleManager: {
                    sectors: [
                        {
                            name: 'Général',
                            open: false,
                            properties: [
                                'display', 'position', 'float', 'top',
                                'right', 'left', 'bottom'
                            ]
                        },
                        {
                            name: 'Dimensions',
                            open: false,
                            properties: [
                                'width', 'height', 'max-width', 'min-height',
                                'margin', 'padding'
                            ]
                        },
                        {
                            name: 'Typographie',
                            open: false,
                            properties: [
                                'font-family', 'font-size', 'font-weight',
                                'letter-spacing', 'color', 'line-height',
                                'text-align', 'text-shadow'
                            ]
                        },
                        {
                            name: 'Décorations',
                            open: false,
                            properties: [
                                'border-radius', 'border', 'box-shadow',
                                'background', 'opacity'
                            ]
                        },
                        {
                            name: 'Extra',
                            open: false,
                            properties: [
                                'transition', 'transform', 'cursor',
                                'overflow', 'z-index'
                            ]
                        }
                    ]
                },
                
                deviceManager: {
                    devices: [
                        {
                            name: 'Bureau',
                            width: ''
                        },
                        {
                            name: 'Tablette',
                            width: '768px',
                            widthMedia: '768px'
                        },
                        {
                            name: 'Mobile',
                            width: '320px',
                            widthMedia: '480px'
                        }
                    ]
                },
                
                canvas: {
                    styles: [
                        'https://unpkg.com/grapesjs/dist/css/grapes.min.css'
                    ]
                }
            });
            
            initLayersPanel();
            initEditorEvents();
            
            setTimeout(() => {
                if (editor && editor.Canvas) {
                    initCustomDragDrop();
                } else {
                    console.error('GrapesJS pas complètement initialisé, nouvelle tentative...');
                    setTimeout(initCustomDragDrop, 500);
                }
            }, 300);
            
            const templateIdFromURL = getTemplateIdFromURL();
            console.log('ID du template depuis l\'URL:', templateIdFromURL);
            
            if (templateIdFromURL) {
                window.currentTemplateId = templateIdFromURL;
                console.log('Définition de currentTemplateId à:', window.currentTemplateId);
                
                initBlocksModern();
                
                setTimeout(() => {
                    if (editor) {
                        loadTemplateOnStart(window.currentTemplateId);
                    } else {
                        console.error('Éditeur non initialisé, impossible de charger le template');
                    }
                }, 800);
            } else {
                console.log('Aucun ID de template trouvé, utilisation du contenu par défaut');
                setTimeout(() => {
                    if (editor) {
                        editor.setComponents(`
                            <section style="padding: 100px 20px; background: linear-gradient(135deg, #0f172a, #1e293b); color: white; text-align: center;">
                                <div style="max-width: 800px; margin: 0 auto;">
                                    <h1 style="font-size: 3rem; margin-bottom: 20px; background: linear-gradient(135deg, #8b5cf6, #3b82f6); -webkit-background-clip: text; background-clip: text; color: transparent;">
                                        Bienvenue dans le Constructeur de Pages Web
                                    </h1>
                                    <p style="font-size: 1.2rem; margin-bottom: 40px; opacity: 0.9;">
                                        Glissez-déposez des blocs depuis le panneau de gauche pour commencer à créer votre page. Sauvegardez vos designs comme templates pour une utilisation ultérieure.
                                    </p>
                                </div>
                            </section>
                        `);
                    }
                }, 800);
            }
            
            showNotification('Éditeur prêt ! Commencez à construire votre site web.', 'info');
        }

        // === FONCTIONS DE GESTION DES TEMPLATES ===
        async function loadTemplateOnStart(templateId) {
            try {
                console.log('Chargement du template avec ID:', templateId);
                showLoading('Chargement du template...');
                
                const response = await fetch(`/api/pages/data/${templateId}`);
                
                if (!response.ok) {
                    throw new Error(`Erreur HTTP ! statut: ${response.status}`);
                }
                
                const data = await response.json();
                console.log('Réponse API Template:', data);
                
                if (data.success && data.data) {
                    const htmlContent = data.data.html_content || '';
                    const cssContent = data.data.css_content || '';
                    
                    console.log('Longueur du contenu HTML:', htmlContent.length);
                    console.log('Longueur du contenu CSS:', cssContent.length);
                    
                    if (htmlContent.trim()) {
                        let cleanHtml = htmlContent
                            .replace(/\\r\\n/g, '\n')
                            .replace(/\\n/g, '\n')
                            .replace(/\\t/g, '\t')
                            .replace(/\\"/g, '"')
                            .replace(/\\'/g, "'")
                            .replace(/\\\\/g, '\\');
                        
                        let cleanCss = cssContent
                            .replace(/\\r\\n/g, '\n')
                            .replace(/\\n/g, '\n')
                            .replace(/\\t/g, '\t')
                            .replace(/\\"/g, '"')
                            .replace(/\\'/g, "'")
                            .replace(/\\\\/g, '\\');
                        
                        console.log('Définition des composants dans l\'éditeur...');
                        
                        editor.setComponents('');
                        
                        if (cleanCss.trim()) {
                            editor.setComponents(cleanHtml + '<style>' + cleanCss + '</style>');
                        } else {
                            editor.setComponents(cleanHtml);
                        }
                        
                        if (cleanCss.trim()) {
                            editor.setStyle(cleanCss);
                        }
                        
                        console.log('Template chargé avec succès');
                    } else {
                        console.log('Le HTML du template est vide');
                    }
                    
                    window.currentTemplateId = templateId;
                    
                    if (data.data.name) {
                        document.title = `${data.data.name} - Constructeur de Pages Web`;
                    }
                    
                    updateLayersPanel();
                    
                    showNotification(`Template "${data.data.name || 'Sans nom'}" chargé`, 'success');
                } else {
                    throw new Error(data.message || 'Échec du chargement du template: Réponse invalide');
                }
            } catch (error) {
                console.error('Erreur de chargement du template:', error);
                showNotification('Erreur de chargement du template: ' + error.message, 'error');
                
                editor.setComponents(`
                    <section style="padding: 100px 20px; background: linear-gradient(135deg, #0f172a, #1e293b); color: white; text-align: center;">
                        <div style="max-width: 800px; margin: 0 auto;">
                            <h1 style="font-size: 3rem; margin-bottom: 20px; background: linear-gradient(135deg, #8b5cf6, #3b82f6); -webkit-background-clip: text; background-clip: text; color: transparent;">
                                Erreur de Chargement du Template
                            </h1>
                            <p style="font-size: 1.2rem; margin-bottom: 40px; opacity: 0.9;">
                                Impossible de charger le template. Démarrage avec un canevas vierge.
                            </p>
                        </div>
                    </section>
                `);
            } finally {
                hideLoading();
            }
        }

        async function saveTemplate() {
            try {
                console.log('ID du template actuel avant sauvegarde:', window.currentTemplateId);
                console.log('URL:', window.location.href);
                
                if (!window.currentTemplateId) {
                    window.currentTemplateId = getTemplateIdFromURL();
                    console.log('ID du template récupéré:', window.currentTemplateId);
                    
                    if (!window.currentTemplateId) {
                        showNotification('Aucun ID de template trouvé. Impossible de sauvegarder.', 'error');
                        return;
                    }
                }
                
                showLoading('Sauvegarde du template...');
                
                const htmlContent = editor.getHtml();
                const cssContent = editor.getCss();
                
                console.log('Sauvegarde du template ID:', window.currentTemplateId);
                console.log('Longueur HTML:', htmlContent.length);
                console.log('Longueur CSS:', cssContent.length);
                
                const formData = {
                    html_content: htmlContent,
                    css_content: cssContent,
                    template_id: window.currentTemplateId
                };
                
                const response = await fetch('/api/pages/save', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN
                    },
                    body: JSON.stringify(formData)
                });
                
                const data = await response.json();
                hideLoading();
                
                if (data.success) {
                    showNotification('Template mis à jour avec succès !', 'success');
                } else {
                    throw new Error(data.message || 'Échec de la sauvegarde du template');
                }
            } catch (error) {
                console.error('Erreur de sauvegarde du template:', error);
                hideLoading();
                showNotification('Erreur de sauvegarde du template: ' + error.message, 'error');
            }
        }

        function getTemplateIdFromURL() {
            const url = window.location.pathname;
            console.log('Récupération de l\'ID du template depuis l\'URL:', url);
            
            const pattern1 = /\/pages\/edit\/(\d+)/;
            const match1 = url.match(pattern1);
            
            if (match1 && match1[1]) {
                console.log('ID du template trouvé (pluriel):', match1[1]);
                return parseInt(match1[1]);
            }
            
            const pattern2 = /\/pages\/edit\/(\d+)/;
            const match2 = url.match(pattern2);
            
            if (match2 && match2[1]) {
                console.log('ID du template trouvé (singulier):', match2[1]);
                return parseInt(match2[1]);
            }
            
            const urlParams = new URLSearchParams(window.location.search);
            const templateIdParam = urlParams.get('template_id');
            if (templateIdParam) {
                console.log('ID du template trouvé dans les paramètres:', templateIdParam);
                return parseInt(templateIdParam);
            }
            
            console.log('Aucun ID de template trouvé dans l\'URL');
            return null;
        }

        // === NOUVELLE FONCTION POUR LE DÉFILEMENT DES CATÉGORIES ===
        function scrollCategories(amount) {
            const scrollContainer = document.getElementById('categoriesScroll');
            if (!scrollContainer) return;
            
            scrollContainer.scrollBy({
                left: amount,
                behavior: 'smooth'
            });
            
            setTimeout(updateCategoryNavButtons, 300);
        }

        function updateCategoryNavButtons() {
            const scrollContainer = document.getElementById('categoriesScroll');
            if (!scrollContainer) return;
            
            const leftBtn = document.querySelector('.categories-nav-btn.left');
            const rightBtn = document.querySelector('.categories-nav-btn.right');
            
            if (leftBtn && rightBtn) {
                if (scrollContainer.scrollLeft <= 10) {
                    leftBtn.disabled = true;
                    leftBtn.style.opacity = '0.4';
                } else {
                    leftBtn.disabled = false;
                    leftBtn.style.opacity = '1';
                }
                
                if (scrollContainer.scrollLeft + scrollContainer.clientWidth >= scrollContainer.scrollWidth - 10) {
                    rightBtn.disabled = true;
                    rightBtn.style.opacity = '0.4';
                } else {
                    rightBtn.disabled = false;
                    rightBtn.style.opacity = '1';
                }
            }
        }

        // === FONCTIONS POUR L'INTERFACE MODERNE ===
        async function loadBlocksModern(templateId) {
            try {
                showLoading('Chargement de la bibliothèque de blocs...');
                
                console.log('Récupération des blocs depuis l\'API avec templateId:', templateId);
                
                let apiUrl = '/api/pages/blocks/data';
                
                
                console.log('Récupération des blocs depuis:', apiUrl);
                
                const response = await fetch(apiUrl);
                
                if (!response.ok) {
                    throw new Error(`Erreur API: ${response.status}`);
                }
                
                const responseText = await response.text();
                
                if (responseText.trim().startsWith('<!DOCTYPE') || 
                    responseText.trim().startsWith('<!--') || 
                    responseText.includes('<html')) {
                    console.error('Le serveur a retourné du HTML au lieu du JSON:', responseText.substring(0, 200));
                    throw new Error('L\'API a retourné du HTML au lieu du JSON. Vérifiez vos routes.');
                }
                
                let data;
                try {
                    data = JSON.parse(responseText);
                } catch (parseError) {
                    console.error('Erreur d\'analyse JSON:', parseError);
                    console.error('Texte de réponse:', responseText.substring(0, 500));
                    throw new Error('Réponse JSON invalide du serveur');
                }
                
                console.log('Données de réponse API:', data);
                
                if (data.success) {
                    allBlocks = data.blocks || [];
                    allSections = data.sections || [];
                    
                    console.log(`${allBlocks.length} blocs et ${allSections.length} sections chargés`);
                    
                    updateStats(allBlocks);
                    renderCategories(allSections, allBlocks);
                    renderBlocksModern(allBlocks);
                    initModernFilters();
                    
                    hideLoading();
                    showNotification(`${allBlocks.length} blocs chargés`, 'success');
                    
                    setTimeout(updateCategoryNavButtons, 500);
                    
                } else {
                    throw new Error(data.message || 'Échec du chargement des blocs');
                }
            } catch (error) {
                console.error('Erreur de chargement des blocs:', error);
                hideLoading();
                showNotification('Erreur de chargement des blocs: ' + error.message, 'error');
                renderEmptyState();
            }
        }

        function updateStats(blocks) {
            const total = blocks.length;
            const free = blocks.filter(b => b.is_free).length;
            const pro = total - free;
            
            const blocksCount = document.getElementById('blocksCount');
            const freeCount = document.getElementById('freeCount');
            const proCount = document.getElementById('proCount');
            
            if (blocksCount) blocksCount.textContent = total;
            if (freeCount) freeCount.textContent = free;
            if (proCount) proCount.textContent = pro;
        }

        function renderCategories(sections, blocks) {
            const container = document.getElementById('categoriesScroll');
            if (!container) return;
            
            container.innerHTML = '';
            
            const allCount = blocks.length;
            const allButton = createCategoryTab('all', 'Tous les Blocs', 'fa-layer-group', allCount, true);
            container.appendChild(allButton);
            
            sections.forEach(section => {
                const sectionBlocks = blocks.filter(b => b.section_id === section.id);
                if (sectionBlocks.length > 0) {
                    const button = createCategoryTab(
                        section.slug,
                        section.name,
                        section.icon || 'fa-folder',
                        sectionBlocks.length,
                        false
                    );
                    container.appendChild(button);
                }
            });
            
            const websiteTypes = [...new Set(blocks.map(b => b.website_type))];
            websiteTypes.forEach(type => {
                const typeBlocks = blocks.filter(b => b.website_type === type);
                if (typeBlocks.length > 0 && type !== 'General') {
                    const icon = getWebsiteTypeIcon(type);
                    const button = createCategoryTab(
                        `type-${type.toLowerCase()}`,
                        type,
                        icon,
                        typeBlocks.length,
                        false
                    );
                    container.appendChild(button);
                }
            });
            
            initCategoryEvents();
        }

        function createCategoryTab(id, name, icon, count, isActive) {
            const button = document.createElement('button');
            button.className = `category-tab ${isActive ? 'active' : ''}`;
            button.dataset.category = id;
            button.innerHTML = `
                <i class="fas ${icon}"></i>
                <span>${name}</span>
                <span class="category-count">${count}</span>
            `;
            return button;
        }

        function getWebsiteTypeIcon(type) {
            const icons = {
                'SaaS': 'fa-cloud',
                'Ecommerce': 'fa-shopping-cart',
                'Portfolio': 'fa-briefcase',
                'Restaurant': 'fa-utensils',
                'Blog': 'fa-blog',
                'Corporate': 'fa-building',
                'Landing': 'fa-flag',
                'Dashboard': 'fa-chart-line',
                'Editor': 'fa-edit',
                'General': 'fa-globe'
            };
            return icons[type] || 'fa-globe';
        }

        function initCategoryEvents() {
            document.querySelectorAll('.category-tab').forEach(tab => {
                tab.addEventListener('click', () => {
                    document.querySelectorAll('.category-tab').forEach(t => {
                        t.classList.remove('active');
                    });
                    
                    tab.classList.add('active');
                    
                    const category = tab.dataset.category;
                    filterBlocksByCategory(category);
                });
            });
        }

        function filterBlocksByCategory(category) {
            const blocksGrid = document.getElementById('blocksContainer');
            if (!blocksGrid) return;
            
            const allBlockCards = document.querySelectorAll('.block-card-modern');
            
            allBlockCards.forEach(card => {
                if (category === 'all') {
                    card.style.display = 'block';
                } else if (category.startsWith('type-')) {
                    const type = category.replace('type-', '');
                    const blockType = card.dataset.websiteType || '';
                    card.style.display = blockType.toLowerCase() === type.toLowerCase() ? 'block' : 'none';
                } else {
                    const blockSection = card.dataset.section || '';
                    card.style.display = blockSection === category ? 'block' : 'none';
                }
                
                card.style.opacity = '0';
                card.style.transform = 'translateY(10px)';
                
                setTimeout(() => {
                    if (card.style.display !== 'none') {
                        card.style.opacity = '1';
                        card.style.transform = 'translateY(0)';
                    }
                }, 10);
            });
            
            const visibleBlocks = Array.from(allBlockCards).filter(b => b.style.display !== 'none');
            const emptyState = document.getElementById('blocksEmptyState');
            
            if (visibleBlocks.length === 0 && emptyState) {
                emptyState.style.display = 'block';
                blocksGrid.style.display = 'none';
            } else {
                if (emptyState) emptyState.style.display = 'none';
                blocksGrid.style.display = 'grid';
            }
        }

        function renderBlocksModern(blocks) {
            const container = document.getElementById('blocksContainer');
            if (!container) return;
            
            container.innerHTML = '';
            
            if (!blocks || blocks.length === 0) {
                renderEmptyState();
                return;
            }
            
            const sortedBlocks = [...blocks].sort((a, b) => (b.usage_count || 0) - (a.usage_count || 0));
            
            sortedBlocks.forEach((block, index) => {
                const card = createBlockCardModern(block, index);
                container.appendChild(card);
            });
        }

        function createBlockCardModern(block, index) {
            const card = document.createElement('div');
            card.className = 'block-card-modern';
            card.dataset.blockId = block.id;
            card.dataset.section = block.section_slug || 'general';
            card.dataset.websiteType = block.website_type || 'General';
            card.dataset.category = block.category || 'Basic';
            card.style.animationDelay = `${index * 0.05}s`;
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            
            setTimeout(() => {
                card.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, index * 50);
            
            const shortDesc = block.description 
                ? block.description.substring(0, 60) + (block.description.length > 60 ? '...' : '')
                : 'Pas de description';
            
            const categoryBadge = `<span class="block-badge badge-category">${block.category || 'Basic'}</span>`;
            const proBadge = !block.is_free ? '<span class="block-badge badge-pro">PRO</span>' : '';
            const freeBadge = block.is_free ? '<span class="block-badge badge-free">Gratuit</span>' : '';
            const usageBadge = block.usage_count > 0 ? 
                `<span class="block-badge badge-usage"><i class="fas fa-download"></i> ${block.usage_count}</span>` : '';
            
            card.innerHTML = `
                <div class="block-icon-modern">
                    <i class="fas ${block.icon || 'fa-cube'}"></i>
                </div>
                <div class="block-name">${block.name}</div>
                <div class="block-description">${shortDesc}</div>
                <div class="block-meta-modern">
                    ${categoryBadge}
                    ${proBadge}
                    ${freeBadge}
                    ${usageBadge}
                </div>
                <div class="block-stats">
                    ${block.is_responsive ? 
                        '<div class="block-stat" title="Responsive"><i class="fas fa-mobile-alt"></i></div>' : ''}
                    ${block.views_count > 0 ? 
                        `<div class="block-stat" title="${block.views_count} vues">
                            <i class="fas fa-eye"></i>
                        </div>` : ''}
                </div>
            `;
            
            card.draggable = true;
            
            card.addEventListener('dragstart', (e) => {
                let blockHtml = '';
                
                if (block.html_content) {
                    let cleanHtml = block.html_content
                        .replace(/\\r\\n/g, '\n')
                        .replace(/\\n/g, '\n')
                        .replace(/\\t/g, '\t')
                        .replace(/\\"/g, '"');
                    
                    if (block.css_content && block.css_content.trim()) {
                        let cleanCss = block.css_content
                            .replace(/\\r\\n/g, '\n')
                            .replace(/\\n/g, '\n')
                            .replace(/\\t/g, '\t')
                            .replace(/\\"/g, '"');
                        
                        blockHtml = cleanHtml + '\n<style>\n' + cleanCss + '\n</style>';
                    } else {
                        blockHtml = cleanHtml;
                    }
                }
                
                e.dataTransfer.setData('text/html', blockHtml);
                e.dataTransfer.setData('text/plain', blockHtml);
                e.dataTransfer.setData('block-id', block.id.toString());
                
                e.dataTransfer.effectAllowed = 'copy';
                card.classList.add('dragging');
                
                e.dataTransfer.setDragImage(card, 75, 75);
                
                card.style.transform = 'scale(0.95) rotate(2deg)';
                card.style.boxShadow = '0 30px 60px rgba(0, 0, 0, 0.5)';
            });
            
            card.addEventListener('dragend', () => {
                card.classList.remove('dragging');
                card.style.transform = '';
                card.style.boxShadow = '';
            });
            
            card.addEventListener('click', async (e) => {
                if (!e.target.closest('.block-badge')) {
                    await addBlockToEditor(block.id);
                    card.style.transform = 'scale(0.95)';
                    setTimeout(() => {
                        card.style.transform = '';
                    }, 200);
                }
            });
            
            card.addEventListener('mouseenter', () => {
                const icon = card.querySelector('.block-icon-modern i');
                if (icon) {
                    icon.style.transform = 'rotate(10deg) scale(1.1)';
                }
                card.style.zIndex = '10';
            });
            
            card.addEventListener('mouseleave', () => {
                const icon = card.querySelector('.block-icon-modern i');
                if (icon) {
                    icon.style.transform = '';
                }
                card.style.zIndex = '1';
            });
            
            return card;
        }

        // === FONCTIONS DE FILTRES MODERNES ===
        function initModernFilters() {
            const searchInput = document.getElementById('blockSearch');
            if (searchInput) {
                searchInput.addEventListener('input', debounce((e) => {
                    filterBlocksBySearch(e.target.value);
                }, 300));
            }
            
            const clearBtn = document.querySelector('.search-clear');
            if (clearBtn) {
                clearBtn.addEventListener('click', clearSearch);
            }
            
            document.querySelectorAll('.filter-chip').forEach(chip => {
                chip.addEventListener('click', () => {
                    if (chip.classList.contains('active')) {
                        chip.classList.remove('active');
                        filterByQuickFilter('all');
                    } else {
                        document.querySelectorAll('.filter-chip').forEach(c => {
                            c.classList.remove('active');
                        });
                        chip.classList.add('active');
                        filterByQuickFilter(chip.dataset.filter);
                    }
                });
            });
        }

        function filterBlocksBySearch(term) {
            const cards = document.querySelectorAll('.block-card-modern');
            const emptyState = document.getElementById('blocksEmptyState');
            const blocksGrid = document.getElementById('blocksContainer');
            
            const clearBtn = document.querySelector('.search-clear');
            if (clearBtn) {
                clearBtn.style.display = term ? 'block' : 'none';
            }
            
            let visibleCount = 0;
            
            cards.forEach(card => {
                const name = card.querySelector('.block-name').textContent.toLowerCase();
                const desc = card.querySelector('.block-description').textContent.toLowerCase();
                const category = card.dataset.category.toLowerCase();
                const websiteType = card.dataset.websiteType.toLowerCase();
                
                const matches = name.includes(term.toLowerCase()) || 
                               desc.includes(term.toLowerCase()) || 
                               category.includes(term.toLowerCase()) ||
                               websiteType.includes(term.toLowerCase());
                
                if (matches) {
                    card.style.display = 'block';
                    visibleCount++;
                    
                    card.style.opacity = '0';
                    card.style.transform = 'translateY(10px)';
                    
                    setTimeout(() => {
                        card.style.opacity = '1';
                        card.style.transform = 'translateY(0)';
                    }, 10);
                } else {
                    card.style.display = 'none';
                }
            });
            
            if (emptyState && blocksGrid) {
                if (visibleCount === 0) {
                    emptyState.style.display = 'block';
                    blocksGrid.style.display = 'none';
                } else {
                    emptyState.style.display = 'none';
                    blocksGrid.style.display = 'grid';
                }
            }
        }

        function filterByQuickFilter(filter) {
            const cards = document.querySelectorAll('.block-card-modern');
            
            cards.forEach(card => {
                switch(filter) {
                    case 'all':
                        card.style.display = 'block';
                        break;
                    case 'popular':
                        const usageElement = card.querySelector('.badge-usage');
                        const usageText = usageElement ? usageElement.textContent : '';
                        const usageMatch = usageText.match(/\d+/);
                        const usage = usageMatch ? parseInt(usageMatch[0]) : 0;
                        card.style.display = usage > 5 ? 'block' : 'none';
                        break;
                    case 'free':
                        const hasFreeBadge = card.querySelector('.badge-free');
                        card.style.display = hasFreeBadge ? 'block' : 'none';
                        break;
                    case 'responsive':
                        const hasMobileIcon = card.querySelector('.block-stat .fa-mobile-alt');
                        card.style.display = hasMobileIcon ? 'block' : 'none';
                        break;
                }
                
                if (card.style.display !== 'none') {
                    card.style.opacity = '0';
                    card.style.transform = 'translateY(10px)';
                    
                    setTimeout(() => {
                        card.style.opacity = '1';
                        card.style.transform = 'translateY(0)';
                    }, 10);
                }
            });
        }

        function renderEmptyState() {
            const container = document.getElementById('blocksContainer');
            const emptyState = document.getElementById('blocksEmptyState');
            
            if (container) {
                container.style.display = 'none';
            }
            
            if (emptyState) {
                emptyState.style.display = 'block';
            }
        }

        function clearSearch() {
            const searchInput = document.getElementById('blockSearch');
            if (searchInput) {
                searchInput.value = '';
                filterBlocksBySearch('');
                searchInput.focus();
            }
        }

        function resetFilters() {
            clearSearch();
            
            document.querySelectorAll('.filter-chip').forEach(chip => {
                chip.classList.remove('active');
            });
            const allChip = document.querySelector('.filter-chip[data-filter="all"]');
            if (allChip) allChip.classList.add('active');
            
            document.querySelectorAll('.category-tab').forEach(tab => {
                tab.classList.remove('active');
            });
            const allTab = document.querySelector('.category-tab[data-category="all"]');
            if (allTab) allTab.classList.add('active');
            
            filterBlocksByCategory('all');
            filterByQuickFilter('all');
        }

        function toggleSidebar() {
            const sidebar = document.querySelector('.sidebar-left');
            const toggleBtn = document.querySelector('.sidebar-toggle i');
            
            if (!sidebar || !toggleBtn) return;
            
            if (sidebar.classList.contains('collapsed')) {
                sidebar.classList.remove('collapsed');
                sidebar.style.width = '380px';
                toggleBtn.className = 'fas fa-chevron-left';
                
                setTimeout(() => {
                    const cards = document.querySelectorAll('.block-card-modern');
                    cards.forEach((card, index) => {
                        card.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                        card.style.opacity = '0';
                        card.style.transform = 'translateY(10px)';
                        
                        setTimeout(() => {
                            card.style.opacity = '1';
                            card.style.transform = 'translateY(0)';
                        }, index * 30);
                    });
                }, 300);
            } else {
                sidebar.classList.add('collapsed');
                sidebar.style.width = '60px';
                toggleBtn.className = 'fas fa-chevron-right';
            }
        }

        // === FONCTIONS DE GESTION DES BLOCS ===
        async function addBlockToEditor(blockId) {
            try {
                showLoading('Ajout du bloc...');
                
                const response = await fetch('/api/blocks/add-to-editor', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN
                    },
                    body: JSON.stringify({ block_id: blockId })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    let fullHtml = data.block.html;
                    if (data.block.css && data.block.css.trim()) {
                        fullHtml = data.block.html + '\n<style>\n' + data.block.css + '\n</style>';
                    }
                    
                    editor.addComponents(fullHtml);
                    
                    if (data.block.js && data.block.js.trim()) {
                        try {
                            const script = document.createElement('script');
                            script.textContent = data.block.js;
                            document.body.appendChild(script);
                        } catch (jsError) {
                            console.warn('Erreur d\'exécution du JS du bloc:', jsError);
                        }
                    }
                    
                    updateLayersPanel();
                    updateBlockUsageInUI(blockId);
                    
                    hideLoading();
                    showNotification('Bloc ajouté avec succès', 'success');
                    
                } else {
                    throw new Error(data.message || 'Échec de l\'ajout du bloc');
                }
            } catch (error) {
                console.error('Erreur d\'ajout du bloc:', error);
                hideLoading();
                showNotification('Erreur d\'ajout du bloc: ' + error.message, 'error');
            }
        }

        function updateBlockUsageInUI(blockId) {
            const blockElement = document.querySelector(`.block-card-modern[data-block-id="${blockId}"]`);
            if (blockElement) {
                const usageElement = blockElement.querySelector('.badge-usage');
                if (usageElement) {
                    const currentCount = parseInt(usageElement.textContent.match(/\d+/)[0]) || 0;
                    usageElement.innerHTML = `<i class="fas fa-download"></i> ${currentCount + 1}`;
                    
                    usageElement.style.transform = 'scale(1.2)';
                    setTimeout(() => {
                        usageElement.style.transform = 'scale(1)';
                    }, 300);
                } else {
                    const metaElement = blockElement.querySelector('.block-meta-modern');
                    if (metaElement) {
                        const usageSpan = document.createElement('span');
                        usageSpan.className = 'block-badge badge-usage';
                        usageSpan.innerHTML = '<i class="fas fa-download"></i> 1';
                        metaElement.appendChild(usageSpan);
                    }
                }
            }
        }

        async function updateBlockUsage(blockId) {
            try {
                const response = await fetch('/api/blocks/add-to-editor', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN
                    },
                    body: JSON.stringify({ block_id: blockId })
                });
                
                const data = await response.json();
                if (data.success) {
                    updateBlockUsageInUI(blockId);
                }
            } catch (error) {
                console.error('Erreur de mise à jour de l\'utilisation du bloc:', error);
            }
        }

        // === FONCTIONS UTILITAIRES ===
        function debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }

        function showLoading(message = 'Chargement...') {
            let loader = document.getElementById('global-loader');
            if (!loader) {
                loader = document.createElement('div');
                loader.id = 'global-loader';
                loader.className = 'global-loader';
                loader.innerHTML = `
                    <div class="loader-content">
                        <div class="loader-spinner"></div>
                        <div class="loader-text">${message}</div>
                    </div>
                `;
                document.body.appendChild(loader);
            } else {
                loader.querySelector('.loader-text').textContent = message;
            }
            loader.style.display = 'flex';
        }

        function hideLoading() {
            const loader = document.getElementById('global-loader');
            if (loader) {
                loader.style.display = 'none';
            }
        }

        function showNotification(message, type = 'info') {
            document.querySelectorAll('.notification').forEach(n => n.remove());
            
            const notification = document.createElement('div');
            notification.className = `notification ${type}`;
            notification.innerHTML = `
                <i class="fas ${type === 'success' ? 'fa-check-circle' : type === 'error' ? 'fa-exclamation-circle' : type === 'warning' ? 'fa-exclamation-triangle' : 'fa-info-circle'}"></i>
                <span>${message}</span>
                <button class="notification-close" onclick="this.parentElement.remove()">
                    <i class="fas fa-times"></i>
                </button>
            `;
            
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.style.opacity = '1';
                notification.style.transform = 'translateY(0)';
            }, 10);
            
            setTimeout(() => {
                if (notification.parentElement) {
                    notification.style.opacity = '0';
                    notification.style.transform = 'translateY(-10px)';
                    setTimeout(() => {
                        if (notification.parentElement) {
                            notification.remove();
                        }
                    }, 300);
                }
            }, 4000);
        }

        function initBlocksModern() {
            const templateId = window.currentTemplateId || null;
            console.log('Initialisation des blocs avec templateId:', templateId);
            loadBlocksModern(templateId);
        }

        function initLayersPanel() {
            updateLayersPanel();
            
            editor.on('component:selected', updateLayersPanel);
            editor.on('component:add', updateLayersPanel);
            editor.on('component:remove', updateLayersPanel);
            editor.on('component:update', updateLayersPanel);
        }

        function updateLayersPanel() {
            const layersList = document.getElementById('layersList');
            if (!layersList) return;
            
            const components = editor.DomComponents.getComponents();
            
            layersList.innerHTML = '';
            
            if (components.length === 0) {
                layersList.innerHTML = '<div style="color: #94a3b8; text-align: center; padding: 20px;">Aucun calque pour l\'instant</div>';
                return;
            }
            
            function renderLayers(components, level = 0) {
                components.forEach(component => {
                    const layerDiv = document.createElement('div');
                    layerDiv.className = 'layer-item';
                    layerDiv.style.paddingLeft = (level * 20) + 'px';
                    
                    const selectedComponent = editor.getSelected();
                    if (selectedComponent && selectedComponent === component) {
                        layerDiv.classList.add('active');
                    }
                    
                    let icon = 'fa-cube';
                    const tagName = component.get('tagName');
                    if (tagName === 'img') icon = 'fa-image';
                    else if (tagName === 'button' || tagName === 'a') icon = 'fa-square';
                    else if (tagName === 'h1' || tagName === 'h2' || tagName === 'h3') icon = 'fa-heading';
                    else if (tagName === 'p') icon = 'fa-paragraph';
                    else if (tagName === 'section' || tagName === 'div') icon = 'fa-square-full';
                    
                    layerDiv.innerHTML = `
                        <div class="layer-icon">
                            <i class="fas ${icon}"></i>
                        </div>
                        <div class="layer-name">
                            ${component.get('type') || tagName || 'Composant'}
                        </div>
                        <div class="layer-badge">
                            ${tagName || 'div'}
                        </div>
                    `;
                    
                    layerDiv.addEventListener('click', (e) => {
                        e.stopPropagation();
                        editor.select(component);
                    });
                    
                    layersList.appendChild(layerDiv);
                    
                    const children = component.get('components');
                    if (children && children.length > 0) {
                        renderLayers(children, level + 1);
                    }
                });
            }
            
            renderLayers(components);
        }

        function initEditorEvents() {
            let history = [];
            const maxHistory = 50;
            
            editor.on('component:add component:remove component:update style:property:update', () => {
                const action = {
                    time: new Date().toLocaleTimeString(),
                    html: editor.getHtml(),
                    css: editor.getCss()
                };
                
                history.unshift(action);
                if (history.length > maxHistory) {
                    history.pop();
                }
                
                updateHistoryPanel();
            });
            
            function updateHistoryPanel() {
                const historyList = document.getElementById('historyList');
                if (!historyList) return;
                
                historyList.innerHTML = '';
                
                if (history.length === 0) {
                    historyList.innerHTML = '<div style="color: #94a3b8; text-align: center; padding: 20px;">Aucun historique pour l\'instant</div>';
                    return;
                }
                
                history.slice(0, 10).forEach((action, index) => {
                    const itemDiv = document.createElement('div');
                    itemDiv.className = 'history-item';
                    
                    let icon = 'fa-edit';
                    if (index === 0) icon = 'fa-clock';
                    
                    itemDiv.innerHTML = `
                        <div class="history-icon">
                            <i class="fas ${icon}"></i>
                        </div>
                        <div>
                            ${index === 0 ? 'Actuel' : 'Action ' + index}
                        </div>
                        <div class="history-time">
                            ${action.time}
                        </div>
                    `;
                    
                    historyList.appendChild(itemDiv);
                });
            }
            
            updateHistoryPanel();
        }

        // === FONCTIONS DE GESTION DES MODALES ===
        async function clearCanvas() {
            const { isConfirmed } = await Swal.fire({
                title: 'Vider le Canevas ?',
                text: 'Êtes-vous sûr de vouloir vider le canevas ? Tout votre travail actuel sera perdu.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Oui, le vider !',
                cancelButtonText: 'Annuler',
                reverseButtons: true
            });

            if (isConfirmed) {
                editor.setComponents('');
                showNotification('Canevas vidé', 'info');
                
                Swal.fire({
                    title: 'Vidé !',
                    text: 'Le canevas a été vidé.',
                    icon: 'success',
                    timer: 1500,
                    showConfirmButton: false
                });
            }
        }

        function showPreviewInModal() {
            const html = editor.getHtml();
            const css = editor.getCss();
            
            const previewFrame = document.getElementById('previewFrame');
            if (previewFrame) {
                const previewDoc = previewFrame.contentDocument || previewFrame.contentWindow.document;
                previewDoc.open();
                previewDoc.write(`
                    <!DOCTYPE html>
                    <html>
                    <head>
                        <meta charset="UTF-8">
                        <meta name="viewport" content="width=device-width, initial-scale=1.0">
                        <title>Aperçu</title>
                        <style>${css}</style>
                    </head>
                    <body style="margin: 0; padding: 20px; background: #f8fafc;">${html}</body>
                    </html>
                `);
                previewDoc.close();
                
                const modal = document.getElementById('previewModal');
                if (modal) {
                    modal.style.display = 'block';
                }
            }
        }

        function showPreviewInNewTab() {
            const html = editor.getHtml();
            const css = editor.getCss();
            
            const fullHtml = `<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aperçu - Constructeur de Pages Web</title>
    <style>
        ${css}
        
        body {
            margin: 0;
            padding: 0;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
            background: #f8fafc;
        }
        
        .preview-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .preview-header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .preview-header h1 {
            color: #1e293b;
            margin: 0;
        }
        
        .preview-note {
            background: #f1f5f9;
            padding: 10px;
            border-radius: 6px;
            margin-top: 10px;
            font-size: 14px;
            color: #64748b;
        }
    </style>
</head>
<body>
    <div class="preview-container">
        <div class="preview-header">
            <h1><i class="fas fa-eye"></i> Mode Aperçu</h1>
            <div class="preview-note">
                Ceci est un aperçu de votre page. Les modifications ne sont pas sauvegardées automatiquement.
            </div>
        </div>
        ${html}
    </div>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</body>
</html>`;
            
            const newTab = window.open();
            newTab.document.open();
            newTab.document.write(fullHtml);
            newTab.document.close();
        }

        function closeModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.style.display = 'none';
            }
        }

        function copyCode() {
            const codeEditor = document.getElementById('codeEditor');
            if (codeEditor) {
                codeEditor.select();
                document.execCommand('copy');
                showNotification('Code copié dans le presse-papier', 'success');
            }
        }

        // === DRAG AND DROP PERSONNALISÉ ===
        function initCustomDragDrop() {
            if (!editor || !editor.Canvas) {
                console.error('Éditeur ou Canvas non initialisé, nouvelle tentative dans 500ms...');
                setTimeout(initCustomDragDrop, 500);
                return;
            }
            
            try {
                let canvas = null;
                
                if (editor.Canvas.getFrameEl) {
                    canvas = editor.Canvas.getFrameEl();
                }
                
                if (!canvas && editor.Canvas.getWindow) {
                    const win = editor.Canvas.getWindow();
                    if (win && win.document) {
                        canvas = win.document.body;
                    }
                }
                
                if (!canvas) {
                    const iframe = document.querySelector('.gjs-frame');
                    if (iframe && iframe.contentDocument) {
                        canvas = iframe.contentDocument.body;
                    }
                }
                
                if (!canvas) {
                    const frame = document.querySelector('#gjs iframe, .gjs-frame');
                    if (frame && frame.contentDocument) {
                        canvas = frame.contentDocument.body;
                    }
                }
                
                if (!canvas) {
                    console.error('Élément Canvas non trouvé, nouvelles tentatives...');
                    setTimeout(initCustomDragDrop, 500);
                    return;
                }
                
                console.log('Canvas trouvé:', canvas);
                
                dropIndicator = document.createElement('div');
                dropIndicator.className = 'drop-indicator';
                dropIndicator.style.display = 'none';
                
                const canvasContainer = document.querySelector('.gjs-editor-cont');
                if (canvasContainer) {
                    canvasContainer.appendChild(dropIndicator);
                } else {
                    document.body.appendChild(dropIndicator);
                }
                
                canvas.addEventListener('dragover', handleCanvasDragOver);
                canvas.addEventListener('dragleave', handleCanvasDragLeave);
                canvas.addEventListener('drop', handleCanvasDrop);
                
                console.log('Drag and drop personnalisé initialisé avec succès');
                
                const iframe = document.querySelector('#gjs iframe, .gjs-frame');
                if (iframe) {
                    iframe.addEventListener('dragover', handleCanvasDragOver);
                    iframe.addEventListener('dragleave', handleCanvasDragLeave);
                    iframe.addEventListener('drop', handleCanvasDrop);
                }
                
            } catch (error) {
                console.error('Erreur d\'initialisation du drag and drop personnalisé:', error);
                setTimeout(initCustomDragDrop, 500);
            }
        }

        function handleCanvasDragOver(e) {
            e.preventDefault();
            e.stopPropagation();
            
            if (!dropIndicator) return false;
            
            const editorContainer = document.querySelector('.gjs-editor-cont') || document.querySelector('#gjs');
            if (!editorContainer) return false;
            
            const rect = editorContainer.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            
            const target = document.elementFromPoint(e.clientX, e.clientY);
            const closestComponent = findClosestComponent(target);
            
            if (closestComponent && closestComponent !== editorContainer) {
                const componentRect = closestComponent.getBoundingClientRect();
                const relativeY = e.clientY - componentRect.top;
                const isBefore = relativeY < componentRect.height / 2;
                
                dropIndicator.style.display = 'block';
                dropIndicator.style.width = componentRect.width + 'px';
                dropIndicator.style.left = (componentRect.left - rect.left) + 'px';
                
                if (isBefore) {
                    dropIndicator.style.top = (componentRect.top - rect.top - 1) + 'px';
                    dropIndicator.className = 'drop-indicator before';
                } else {
                    dropIndicator.style.top = (componentRect.bottom - rect.top - 1) + 'px';
                    dropIndicator.className = 'drop-indicator after';
                }
                
                dropIndicator.dataset.targetId = closestComponent.id || '';
                dropIndicator.dataset.position = isBefore ? 'before' : 'after';
            } else {
                dropIndicator.style.display = 'none';
            }
            
            return false;
        }

        function handleCanvasDragLeave(e) {
            e.preventDefault();
            e.stopPropagation();
            if (dropIndicator) {
                dropIndicator.style.display = 'none';
            }
            return false;
        }

        async function handleCanvasDrop(e) {
            e.preventDefault();
            e.stopPropagation();
            
            if (dropIndicator) {
                dropIndicator.style.display = 'none';
            }
            
            let blockHtml = e.dataTransfer.getData('text/html');
            const blockId = e.dataTransfer.getData('block-id');
            
            if (!blockHtml || blockHtml.trim() === '') {
                blockHtml = e.dataTransfer.getData('text/plain');
            }
            
            if (blockHtml && blockHtml.trim()) {
                if (dropIndicator && dropIndicator.dataset.targetId && dropIndicator.dataset.position) {
                    const targetId = dropIndicator.dataset.targetId;
                    const position = dropIndicator.dataset.position;
                    
                    editor.addComponents(blockHtml);
                } else {
                    editor.addComponents(blockHtml);
                }
                
                if (blockId) {
                    updateBlockUsage(parseInt(blockId));
                }
                
                showNotification('Bloc ajouté avec succès', 'success');
            } else {
                showNotification('Impossible d\'ajouter le bloc: Aucun HTML valide trouvé', 'error');
            }
            
            return false;
        }

        function findClosestComponent(element) {
            while (element && element !== document) {
                if (element.classList && element.classList.contains('gjs-comp-selected')) {
                    return element;
                }
                element = element.parentElement;
            }
            return null;
        }

        // === FONCTIONS DIVERSES ===
        function previewBlock(blockId) {
            console.log('Aperçu du bloc:', blockId);
        }

        function showBlockCode(blockId) {
            console.log('Afficher le code pour le bloc:', blockId);
        }

        async function importBlocks() {
            console.log('Importation de blocs');
        }

        async function exportBlocks() {
            console.log('Exportation de blocs');
        }

        function showAllCategories() {
            console.log('Afficher toutes les catégories');
        }

        function showRightPanel(panel) {
            console.log('Afficher le panneau:', panel);
        }

        // === INITIALISATION ===
        document.addEventListener('DOMContentLoaded', function() {
            initEditor();
            
            console.log('Constructeur de Pages Web moderne initialisé');
            
            window.addEventListener('resize', updateCategoryNavButtons);
            
            const scrollContainer = document.getElementById('categoriesScroll');
            if (scrollContainer) {
                scrollContainer.addEventListener('scroll', updateCategoryNavButtons);
            }
            
            // Mettre à jour les boutons de navigation au chargement initial
            setTimeout(updateCategoryNavButtons, 1000);
        });
    </script>
</body>
</html>