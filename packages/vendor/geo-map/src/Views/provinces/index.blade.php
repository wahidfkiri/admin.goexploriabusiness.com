<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Go Exploria Business - Plateforme de Création Digitale</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800&family=Open+Sans:wght@300;400;600&display=swap" rel="stylesheet">
    
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="stylesheet" href="{{ asset('front/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/geo-map/css/map.css') }}">
    
    <style>
        /* Styles pour les onglets principaux */
        .main-tabs {
            display: flex;
            background: #2c5282;
            padding: 0;
            margin: 0;
            list-style: none;
        }
        
        .main-tab {
            padding: 15px 25px;
            color: white;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s ease;
            border-bottom: 3px solid transparent;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .main-tab:hover {
            background: rgba(255, 255, 255, 0.1);
        }
        
        .main-tab.active {
            background: white;
            color: #2c5282;
            border-bottom-color: #4299e1;
        }
        
        .tab-content-main {
            display: none;
            height: calc(100vh - 160px);
        }
        
        .tab-content-main.active {
            display: block;
        }
        
        /* Style pour l'onglet Carte */
        #tab-carte-content .app-container {
            display: flex;
            height: 100%;
            width: 100%;
        }
    </style>
</head>
<body>
    <!-- Header avec informations en temps réel -->
    <header class="info-header">
        <div class="container">
            <div class="info-items">
                <div class="info-item">
                    <i class="fas fa-chart-line info-icon"></i>
                    <span class="info-label">Bourse TSX: </span>
                    <span class="info-value ms-1">21,450.12</span>
                    <span class="info-up ms-1">+1.2%</span>
                </div>
                <div class="info-item">
                    <i class="fas fa-cloud-sun info-icon"></i>
                    <span class="info-label">Météo QC: </span>
                    <span class="info-value ms-1">-5°C</span>
                    <span class="info-details ms-1">Ensoleillé</span>
                </div>
                <div class="info-item">
                    <i class="fas fa-road info-icon"></i>
                    <span class="info-label">Routes: </span>
                    <span class="info-value ms-1">Majoritairement dégagées</span>
                </div>
            </div>
        </div>
    </header>

    <!-- Top Bar -->
    <div class="top-bar">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <div class="contact-info">
                    <a href="tel:4185257748" class="contact-link">
                        <i class="fas fa-phone-alt me-1"></i> (418) 525-7748
                    </a>
                    <a href="mailto:infogoexploria@gmail.com" class="contact-link">
                        <i class="fas fa-envelope me-1"></i> infogoexploria@gmail.com
                    </a>
                </div>
                
                <div class="top-bar-icons">
                    <!-- Panier -->
                    <a href="#" class="top-bar-icon">
                        <i class="fas fa-shopping-cart"></i>
                        <span>Panier</span>
                    </a>
                    
                    <!-- Mon compte -->
                    <a href="{{route('login')}}" class="top-bar-icon">
                        <i class="fas fa-user"></i>
                        <span>Mon compte</span>
                    </a>
                    
                    <!-- Localisation / Langue -->
                    <div class="language-selector">
                        <button class="language-btn" id="languageBtn">
                            <img src="https://flagcdn.com/w20/fr.png" class="flag-icon" alt="Français">
                            <span>FR</span>
                            <i class="fas fa-chevron-down ms-1"></i>
                        </button>
                        <div class="language-dropdown" id="languageDropdown">
                            <a href="#" class="language-option" data-lang="fr">
                                <img src="https://flagcdn.com/w20/fr.png" class="flag-icon" alt="Français">
                                <span>Français</span>
                            </a>
                            <a href="#" class="language-option" data-lang="en">
                                <img src="https://flagcdn.com/w20/gb.png" class="flag-icon" alt="English">
                                <span>English</span>
                            </a>
                        </div>
                    </div>
                    
                    <!-- YouTube Icon -->
                    <a href="https://www.youtube.com/user/explorezlemonde/videos?view_as=subscriber" target="_blank" class="top-bar-icon">
                        <i class="fab fa-youtube"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    
    @include('components.front.nav-bar')
    
    <!-- Onglets principaux -->
    <ul class="main-tabs">
        <li class="main-tab active" data-tab="carte">
            <i class="fas fa-map"></i>
            <span>Carte Interactive</span>
        </li>
        <li class="main-tab" data-tab="details">
            <i class="fas fa-info-circle"></i>
            <span>Détails Province</span>
        </li>
        <li class="main-tab" data-tab="statistiques">
            <i class="fas fa-chart-bar"></i>
            <span>Statistiques</span>
        </li>
        <li class="main-tab" data-tab="galerie">
            <i class="fas fa-images"></i>
            <span>Galerie</span>
        </li>
    </ul>
    
    <!-- Contenu des onglets principaux -->
    
    <!-- Onglet 1: Carte Interactive -->
    <div id="tab-carte-content" class="tab-content-main active">
        <!-- Main Content -->
        <div class="app-container" id="appContainer">
            <!-- Carte à gauche -->
            <div class="map-container">
                <div id="map"></div>
                
                <!-- Overlay de chargement -->
                <div class="loading-overlay" id="mapLoading">
                    <div class="text-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Chargement de la carte...</span>
                        </div>
                        <p class="mt-2">Chargement de la carte...</p>
                    </div>
                </div>
                
                <!-- Bouton pour ouvrir/fermer sidebar sur mobile -->
                <button class="sidebar-toggle" id="sidebarToggle">
                    <i class="fas fa-bars" id="sidebarToggleIcon"></i>
                </button>
            </div>
            
            <!-- Sidebar à droite - Positionnée dans app-container -->
            <div class="sidebar-right" id="sidebarRight" style="min-height:800px;">
                
                <div class="filters-section">
                    <h3>Filtres</h3>
                    
                    <!-- Information de la province -->
                    <div class="filter-group" style="background: #f0f7ff; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                        <h4 style="margin: 0 0 10px 0; color: #2c5282;">
                            <i class="fas fa-map-marker-alt me-2"></i>
                            Province: <?php echo e($province->name); ?>
                        </h4>
                        <p style="margin: 0; color: #666; font-size: 14px;">
                            Code: <?php echo e($province->code); ?> | 
                            Population: <?php echo e(number_format($province->population, 0, ',', ' ')); ?>
                        </p>
                    </div>
                    
                    <!-- Sélection de région -->
                    <div class="filter-group">
                        <label for="region-filter">Région :</label>
                        <select id="region-filter" class="form-select">
                            <option value="">Toutes les régions</option>
                        </select>
                    </div>
                    
                    <!-- Sélection de catégorie -->
                    <div class="filter-group">
                        <label for="category-filter">Catégorie :</label>
                        <select id="category-filter" class="form-select">
                            <option value="all">Toutes les catégories</option>
                        </select>
                    </div>
                    
                    <!-- Filtre de rayon -->
                    <div class="filter-group">
                        <label for="radius-filter">Rayon de recherche :</label>
                        <div class="slider-container">
                            <input type="range" id="radius-filter" min="1" max="500" value="100" class="form-range">
                            <span id="radius-value">100 km</span>
                        </div>
                    </div>
                    
                    <!-- Bouton de localisation -->
                    <div class="filter-group">
                        <button id="locate-me" class="btn locate-btn">
                            <i class="fas fa-location-arrow"></i> Me localiser
                        </button>
                    </div>
                    
                    <!-- Statistiques -->
                    <div class="stats">
                        <p><span id="places-count">0</span> lieux trouvés dans la zone</p>
                    </div>
                </div>
                
                <!-- Liste des lieux -->
                <div class="places-list" id="places-list">
                    <div class="no-results">
                        <i class="fas fa-map-marker-alt"></i>
                        <h4>Aucun lieu trouvé</h4>
                        <p>Utilisez les filtres pour trouver des lieux intéressants</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Onglet 2: Détails Province -->
    <div id="tab-details-content" class="tab-content-main">
        <div class="container py-4">
            <h2 class="mb-4">Détails de la Province: <?php echo e($province->name); ?></h2>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">Informations générales</h5>
                        </div>
                        <div class="card-body">
                            <table class="table">
                                <tr>
                                    <th>Nom complet:</th>
                                    <td><?php echo e($province->name); ?></td>
                                </tr>
                                <tr>
                                    <th>Code:</th>
                                    <td><?php echo e($province->code); ?></td>
                                </tr>
                                <tr>
                                    <th>Population:</th>
                                    <td><?php echo e(number_format($province->population, 0, ',', ' ')); ?> habitants</td>
                                </tr>
                                <tr>
                                    <th>Superficie:</th>
                                    <td><?php echo e(isset($province->area) ? number_format($province->area, 0, ',', ' ') . ' km²' : 'Non disponible'); ?></td>
                                </tr>
                                <tr>
                                    <th>Chef-lieu:</th>
                                    <td><?php echo e($province->capital ?? 'Non spécifié'); ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">Description</h5>
                        </div>
                        <div class="card-body">
                            <p><?php echo e($province->description ?? 'Aucune description disponible pour cette province.'); ?></p>
                        </div>
                    </div>
                    
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">Coordonnées géographiques</h5>
                        </div>
                        <div class="card-body">
                            <p><i class="fas fa-map-marker-alt me-2"></i> Latitude: <?php echo e($province->latitude); ?></p>
                            <p><i class="fas fa-map-marker-alt me-2"></i> Longitude: <?php echo e($province->longitude); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Onglet 3: Statistiques -->
    <div id="tab-statistiques-content" class="tab-content-main">
        <div class="container py-4">
            <h2 class="mb-4">Statistiques de la Province: <?php echo e($province->name); ?></h2>
            
            <div class="row">
                <div class="col-md-4">
                    <div class="card text-center mb-4">
                        <div class="card-body">
                            <h1 class="display-4 text-primary"><?php echo e(number_format($province->population, 0, ',', ' ')); ?></h1>
                            <p class="card-text">Habitants</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card text-center mb-4">
                        <div class="card-body">
                            <h1 class="display-4 text-success">
                                <?php if(isset($province->area) && $province->area > 0): ?>
                                    <?php echo e(number_format($province->population / $province->area, 2, ',', ' ')); ?>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </h1>
                            <p class="card-text">hab/km² (densité)</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card text-center mb-4">
                        <div class="card-body">
                            <h1 class="display-4 text-warning">
                                <?php echo e(isset($province->area) ? number_format($province->area, 0, ',', ' ') : '-'); ?>
                            </h1>
                            <p class="card-text">km² (superficie)</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Évolution démographique</h5>
                </div>
                <div class="card-body">
                    <canvas id="populationChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Onglet 4: Galerie -->
    <div id="tab-galerie-content" class="tab-content-main">
        <div class="container py-4">
            <h2 class="mb-4">Galerie photos: <?php echo e($province->name); ?></h2>
            
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <img src="https://images.unsplash.com/photo-1506905925346-21bda4d32df4?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
                             class="card-img-top" alt="Paysage de <?php echo e($province->name); ?>">
                        <div class="card-body">
                            <h5 class="card-title">Paysages naturels</h5>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <img src="https://images.unsplash.com/photo-1518837695005-2083093ee35b?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
                             class="card-img-top" alt="Monuments de <?php echo e($province->name); ?>">
                        <div class="card-body">
                            <h5 class="card-title">Monuments historiques</h5>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <img src="https://images.unsplash.com/photo-1448375240586-882707db888b?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
                             class="card-img-top" alt="Villes de <?php echo e($province->name); ?>">
                        <div class="card-body">
                            <h5 class="card-title">Villes et villages</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal pour les détails -->
    <div id="place-modal" class="modal">
        <div class="modal-content">
            <span class="close-modal">&times;</span>
            <div id="modal-content"></div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    
    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    
    <!-- Axios -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    
    <!-- Chart.js pour les statistiques -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
<script>
class InteractiveMap {
    constructor() {
        this.map = null;
        this.markers = {};
        this.currentLocation = null;
        this.places = [];
        this.categories = [];
        this.regions = [];
        this.selectedCategory = 'all';
        this.selectedRegion = '';
        this.radius = 100;
        this.swiper = null;
        this.provinceId = "<?php echo e($province->id); ?>";
        this.provinceName = "<?php echo e($province->name); ?>";
        this.provinceLat = <?php echo e($province->latitude); ?>;
        this.provinceLng = <?php echo e($province->longitude); ?>;
        this.hoverTimeout = null;
        this.activePlaceId = null;
        this.tooltips = {};
        this.userMarker = null;
        this.activePlace = null;
        this.manualZoom = false;
        this.lastManualView = null;
        
        // Images statiques par catégorie
        this.staticImages = {
            restaurant: [
                'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4',
                'https://images.unsplash.com/photo-1559925393-8be0ec4767c8',
                'https://images.unsplash.com/photo-1414235077428-338989a2e8c0',
                'https://images.unsplash.com/photo-1554679665-f5537f187268'
            ],
            hotel: [
                'https://images.unsplash.com/photo-1566073771259-6a8506099945',
                'https://images.unsplash.com/photo-1564501049418-3c27787d01e8',
                'https://images.unsplash.com/photo-1584132967334-10e028bd69f7',
                'https://images.unsplash.com/photo-1571896349842-33c89424de2d'
            ],
            museum: [
                'https://images.unsplash.com/photo-1530122037265-a5f1f91d3b99',
                'https://images.unsplash.com/photo-1582555172866-f73bb12a2ab3',
                'https://images.unsplash.com/photo-1578662996442-48f60103fc96',
                'https://images.unsplash.com/photo-1596462502278-27bfdc403348'
            ],
            park: [
                'https://images.unsplash.com/photo-1506905925346-21bda4d32df4',
                'https://images.unsplash.com/photo-1503614472-8c93d56e92ce',
                'https://images.unsplash.com/photo-1518837695005-2083093ee35b',
                'https://images.unsplash.com/photo-1448375240586-882707db888b'
            ],
            shopping: [
                'https://images.unsplash.com/photo-1441986300917-64674bd600d8',
                'https://images.unsplash.com/photo-1556742049-0cfed4f6a45d',
                'https://images.unsplash.com/photo-1556742044-3c52d6e88c62',
                'https://images.unsplash.com/photo-1586023492125-27b2c045efd7'
            ],
            monument: [
                'https://images.unsplash.com/photo-1546436836-07bfe9ee8b9c',
                'https://images.unsplash.com/photo-1559592413-7cec4d0cae2b',
                'https://images.unsplash.com/photo-1523906834658-6e24ef2386f9',
                'https://images.unsplash.com/photo-1529260830199-42c24126f198'
            ]
        };
        
        // Images par défaut si catégorie inconnue
        this.defaultImages = [
            'https://images.unsplash.com/photo-1518837695005-2083093ee35b',
            'https://images.unsplash.com/photo-1506905925346-21bda4d32df4',
            'https://images.unsplash.com/photo-1448375240586-882707db888b',
            'https://images.unsplash.com/photo-1503614472-8c93d56e92ce'
        ];
        
        this.init();
    }
    
    async init() {
        try {
            // Initialiser la carte
            this.initMap();
            
            // Initialiser la sidebar
            this.initSidebar();
            
            // Charger les régions
            await this.loadRegions();
            
            // Charger les catégories
            await this.loadCategories();
            
            // Charger les lieux initiaux
            await this.loadPlaces();
            
            // Écouter les événements
            this.setupEventListeners();
            
            console.log('Carte interactive initialisée avec succès pour la province:', this.provinceName);
        } catch (error) {
            console.error('Erreur lors de l\'initialisation:', error);
        }
    }
    
    initMap() {
        try {
            // Cacher le loading overlay
            document.getElementById('mapLoading').style.display = 'none';
            
            // Créer la carte avec la position de la province
            this.map = L.map('map').setView([this.provinceLat, this.provinceLng], 8);
            
            // Écouter les événements de zoom/déplacement
            this.map.on('zoomend', () => {
                if (this.map.getZoom() !== 8 && !this.manualZoom) {
                    this.manualZoom = true;
                    this.lastManualView = {
                        center: this.map.getCenter(),
                        zoom: this.map.getZoom()
                    };
                }
            });
            
            this.map.on('moveend', () => {
                if (!this.manualZoom) {
                    this.manualZoom = true;
                    this.lastManualView = {
                        center: this.map.getCenter(),
                        zoom: this.map.getZoom()
                    };
                }
            });
            
            // Ajouter les tuiles OpenStreetMap
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributeurs',
                maxZoom: 19,
                detectRetina: true
            }).addTo(this.map);
            
            // Ajouter un contrôle d'échelle
            L.control.scale({ imperial: false }).addTo(this.map);
            
            // Ajouter un contrôle de localisation custom
            this.addLocateControl();
            
        } catch (error) {
            console.error('Erreur lors de l\'initialisation de la carte:', error);
            document.getElementById('mapLoading').innerHTML = `
                <div class="text-center">
                    <i class="fas fa-exclamation-triangle fa-3x text-danger mb-3"></i>
                    <h4>Erreur de chargement de la carte</h4>
                    <p>${error.message}</p>
                    <button onclick="location.reload()" class="btn btn-primary mt-2">
                        <i class="fas fa-redo"></i> Réessayer
                    </button>
                </div>
            `;
        }
    }
    
    initSidebar() {
        // Gérer la sidebar sur mobile
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebar = document.getElementById('sidebarRight');
        
        if (sidebarToggle && sidebar) {
            sidebarToggle.addEventListener('click', () => {
                sidebar.classList.toggle('active');
                const icon = document.getElementById('sidebarToggleIcon');
                if (sidebar.classList.contains('active')) {
                    icon.className = 'fas fa-times';
                } else {
                    icon.className = 'fas fa-bars';
                }
            });
        }
        
        // Fermer la sidebar au clic en dehors sur mobile
        document.addEventListener('click', (e) => {
            if (window.innerWidth <= 768 && 
                sidebar.classList.contains('active') &&
                !sidebar.contains(e.target) &&
                !sidebarToggle.contains(e.target)) {
                sidebar.classList.remove('active');
                document.getElementById('sidebarToggleIcon').className = 'fas fa-bars';
            }
        });
        
        // Ajuster la hauteur de la sidebar en fonction du header
        this.adjustSidebarHeight();
    }
    
    adjustSidebarHeight() {
        const sidebar = document.getElementById('sidebarRight');
        if (!sidebar) return;
        
        // Calculer la hauteur totale des headers
        const infoHeader = document.querySelector('.info-header');
        const topBar = document.querySelector('.top-bar');
        const mainNavbar = document.querySelector('.main-navbar');
        
        let totalHeaderHeight = 0;
        if (infoHeader) totalHeaderHeight += infoHeader.offsetHeight;
        if (topBar) totalHeaderHeight += topBar.offsetHeight;
        if (mainNavbar) totalHeaderHeight += mainNavbar.offsetHeight;
    }
    
    addLocateControl() {
        const locateControl = L.control({ position: 'topright' });
        
        locateControl.onAdd = (map) => {
            const container = L.DomUtil.create('div', 'leaflet-control-locate-custom leaflet-bar leaflet-control');
            
            const link = L.DomUtil.create('a', '', container);
            link.href = '#';
            link.title = 'Me localiser';
            link.innerHTML = '<i class="fas fa-location-arrow"></i>';
            
            L.DomEvent.on(link, 'click', (e) => {
                L.DomEvent.stopPropagation(e);
                L.DomEvent.preventDefault(e);
                this.locateUser();
            });
            
            return container;
        };
        
        locateControl.addTo(this.map);
    }
    
    async loadRegions() {
        try {
            // Récupérer les régions via l'API
            const response = await axios.get(`/api/regions/${this.provinceId}`);
            this.regions = response.data.regions || response.data;
            this.populateRegionFilter();
        } catch (error) {
            console.error('Erreur lors du chargement des régions:', error);
            
            // Données de démonstration pour les régions du Québec
            this.regions = [
                { id: 1, name: 'Montréal', code: 'mtl', latitude: 45.5017, longitude: -73.5673 },
                { id: 2, name: 'Québec', code: 'qc', latitude: 46.8139, longitude: -71.2080 },
                { id: 3, name: 'Estrie', code: 'est', latitude: 45.4000, longitude: -71.8833 },
                { id: 4, name: 'Mauricie', code: 'maur', latitude: 46.7500, longitude: -72.9167 },
                { id: 5, name: 'Outaouais', code: 'out', latitude: 45.4833, longitude: -75.6500 },
                { id: 6, name: 'Abitibi-Témiscamingue', code: 'abit', latitude: 48.2369, longitude: -79.0181 },
                { id: 7, name: 'Côte-Nord', code: 'cote-nord', latitude: 50.2167, longitude: -66.3833 },
                { id: 8, name: 'Gaspésie-Îles-de-la-Madeleine', code: 'gas', latitude: 48.8306, longitude: -64.4864 },
                { id: 9, name: 'Chaudière-Appalaches', code: 'chaud', latitude: 46.6550, longitude: -70.8203 },
                { id: 10, name: 'Laval', code: 'laval', latitude: 45.5833, longitude: -73.7500 },
                { id: 11, name: 'Lanaudière', code: 'lan', latitude: 46.2870, longitude: -73.4347 },
                { id: 12, name: 'Laurentides', code: 'laur', latitude: 46.2608, longitude: -74.6900 },
                { id: 13, name: 'Montérégie', code: 'monter', latitude: 45.1350, longitude: -73.2592 },
                { id: 14, name: 'Centre-du-Québec', code: 'cdq', latitude: 46.2333, longitude: -72.5500 },
                { id: 15, name: 'Saguenay-Lac-Saint-Jean', code: 'sag', latitude: 48.4280, longitude: -71.0686 },
                { id: 16, name: 'Nord-du-Québec', code: 'nord', latitude: 52.9399, longitude: -73.5491 },
                { id: 17, name: 'Bas-Saint-Laurent', code: 'bas-st-laurent', latitude: 48.4490, longitude: -68.5239 }
            ];
            
            this.populateRegionFilter();
        }
    }
    
    populateRegionFilter() {
        const filter = document.getElementById('region-filter');
        if (!filter) return;
        
        filter.innerHTML = '<option value="">Toutes les régions</option>';
        
        this.regions.forEach(region => {
            const option = document.createElement('option');
            option.value = region.id || region.code;
            option.textContent = region.name;
            if (region.latitude && region.longitude) {
                option.dataset.lat = region.latitude;
                option.dataset.lng = region.longitude;
            }
            filter.appendChild(option);
        });
    }
    
    async loadCategories() {
        try {
            const response = await axios.get('/api/categories');
            this.categories = response.data;
            this.populateCategoryFilter();
        } catch (error) {
            console.error('Erreur lors du chargement des catégories:', error);
            this.categories = ['restaurant', 'hotel', 'museum', 'park', 'shopping', 'monument'];
            this.populateCategoryFilter();
        }
    }
    
    populateCategoryFilter() {
        const filter = document.getElementById('category-filter');
        if (!filter) return;
        
        filter.innerHTML = '<option value="all">Toutes les catégories</option>';
        
        this.categories.forEach(category => {
            const option = document.createElement('option');
            option.value = category;
            option.textContent = this.capitalizeFirstLetter(category);
            filter.appendChild(option);
        });
    }
    
    async loadPlaces() {
        try {
            // Afficher le chargement
            const placesList = document.getElementById('places-list');
            if (placesList) {
                placesList.innerHTML = `
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Chargement...</span>
                        </div>
                        <p class="mt-2">Recherche des lieux...</p>
                    </div>
                `;
            }
            
            const params = {
                category: this.selectedCategory === 'all' ? null : this.selectedCategory,
                region: this.selectedRegion || null,
                radius: this.radius,
                lat: this.currentLocation?.lat || this.provinceLat,
                lng: this.currentLocation?.lng || this.provinceLng
            };
            
            // Filtrer les paramètres null
            const filteredParams = {};
            Object.keys(params).forEach(key => {
                if (params[key] !== null && params[key] !== undefined) {
                    filteredParams[key] = params[key];
                }
            });
            
            const response = await axios.get('/api/places', { 
                params: filteredParams,
                timeout: 10000
            });
            
            this.places = Array.isArray(response.data) ? response.data : [];
            
            if (response.data?.data && Array.isArray(response.data.data)) {
                this.places = response.data.data;
            }
            
            if (response.data?.places && Array.isArray(response.data.places)) {
                this.places = response.data.places;
            }
            
            // Ajouter des images statiques aux lieux
            this.addStaticImagesToPlaces();
            
            this.updatePlacesCount();
            this.renderPlacesList();
            this.addMarkersToMap();
            
        } catch (error) {
            console.error('Erreur lors du chargement des lieux:', error);
            this.showSamplePlaces();
        }
    }
    
    addStaticImagesToPlaces() {
        // Ajouter des images statiques à chaque lieu
        this.places = this.places.map(place => {
            // Déterminer les images à utiliser
            let images = [];
            const category = place.category || 'monument';
            
            if (this.staticImages[category]) {
                images = [...this.staticImages[category]];
            } else {
                images = [...this.defaultImages];
            }
            
            // Mélanger les images pour la variété
            images = this.shuffleArray(images);
            
            // Garder seulement 4 images maximum
            images = images.slice(0, 4);
            
            return {
                ...place,
                images: images
            };
        });
    }
    
    shuffleArray(array) {
        const newArray = [...array];
        for (let i = newArray.length - 1; i > 0; i--) {
            const j = Math.floor(Math.random() * (i + 1));
            [newArray[i], newArray[j]] = [newArray[j], newArray[i]];
        }
        return newArray;
    }
    
    showSamplePlaces() {
        // Données de démonstration avec images statiques
        this.places = [
            {
                id: 1,
                name: 'Château Frontenac',
                description: 'Hôtel historique emblématique de Québec',
                latitude: 46.8117,
                longitude: -71.2044,
                category: 'hotel',
                address: '1 Rue des Carrières, Québec, QC',
                phone: '+1-418-692-3861',
                website: 'https://www.fairmont.com/frontenac-quebec/'
            },
            {
                id: 2,
                name: 'Oratoire Saint-Joseph',
                description: 'Basilique et lieu de pèlerinage à Montréal',
                latitude: 45.4928,
                longitude: -73.6176,
                category: 'monument',
                address: '3800 Chemin Queen-Mary, Montréal, QC',
                phone: '+1-514-733-8211',
                website: 'https://www.saint-joseph.org/'
            },
            {
                id: 3,
                name: 'Musée des beaux-arts de Montréal',
                description: 'Musée d\'art majeur au centre-ville',
                latitude: 45.4989,
                longitude: -73.5806,
                category: 'museum',
                address: '1380 Rue Sherbrooke O, Montréal, QC',
                phone: '+1-514-285-2000',
                website: 'https://www.mbam.qc.ca/'
            }
        ];
        
        // Ajouter des images statiques
        this.addStaticImagesToPlaces();
        
        this.updatePlacesCount();
        this.renderPlacesList();
        this.addMarkersToMap();
    }
    
    addMarkersToMap() {
        this.clearMarkers();
        
        this.places.forEach(place => {
            this.createMarker(place);
        });
        
        // Ne pas ajuster la vue automatiquement si l'utilisateur a effectué un zoom/déplacement manuel
        if (!this.manualZoom && this.places.length > 0) {
            const bounds = this.getMarkersBounds();
            this.map.fitBounds(bounds, { 
                padding: [50, 50],
                maxZoom: 12 // Limiter le zoom maximum automatique
            });
        }
        
        // Si l'utilisateur a fait un zoom/déplacement manuel, restaurer la vue
        if (this.manualZoom && this.lastManualView) {
            this.map.setView(
                this.lastManualView.center,
                this.lastManualView.zoom
            );
        }
    }
    
    clearMarkers() {
        Object.values(this.markers).forEach(marker => {
            if (marker && marker.remove) {
                marker.remove();
            }
        });
        this.markers = {};
        
        // Supprimer les tooltips
        Object.values(this.tooltips).forEach(tooltip => {
            if (tooltip && tooltip.remove) {
                tooltip.remove();
            }
        });
        this.tooltips = {};
    }
    
    createMarker(place) {
        const icon = L.divIcon({
            className: 'custom-marker',
            html: `
                <div class="marker-icon marker-${place.category}" 
                     style="background: ${this.getCategoryColor(place.category)};">
                    <i class="${this.getCategoryIcon(place.category)}"></i>
                </div>
            `,
            iconSize: [40, 40],
            iconAnchor: [20, 40],
            popupAnchor: [0, -40]
        });
        
        const marker = L.marker([place.latitude, place.longitude], { 
            icon: icon,
            title: place.name
        }).addTo(this.map);
        
        // Créer un tooltip pour le survol
        const tooltip = L.tooltip({
            direction: 'top',
            className: 'place-tooltip',
            offset: [0, -10],
            permanent: false,
            opacity: 0.9,
            interactive: true
        })
        .setContent(this.createTooltipContent(place))
        .setLatLng([place.latitude, place.longitude]);
        
        // Événements de survol
        marker.on('mouseover', () => {
            this.highlightPlace(place.id);
            tooltip.addTo(this.map);
        });
        
        marker.on('mouseout', () => {
            this.unhighlightPlace(place.id);
            setTimeout(() => {
                if (!this.isMouseOverTooltip) {
                    tooltip.remove();
                }
            }, 100);
        });
        
        marker.on('click', () => {
            this.showPlaceModal(place);
        });
        
        // Gérer les événements du tooltip
        tooltip.on('add', () => {
            this.setupTooltipEvents(tooltip, place);
        });
        
        this.markers[place.id] = marker;
        this.tooltips[place.id] = tooltip;
        
        return marker;
    }
    
    setupTooltipEvents(tooltip, place) {
        // Attendre que le tooltip soit rendu
        setTimeout(() => {
            const tooltipElement = tooltip.getElement();
            if (tooltipElement) {
                // Gérer l'entrée/sortie de la souris
                tooltipElement.addEventListener('mouseenter', () => {
                    this.isMouseOverTooltip = true;
                    this.highlightPlace(place.id);
                });
                
                tooltipElement.addEventListener('mouseleave', () => {
                    this.isMouseOverTooltip = false;
                    this.unhighlightPlace(place.id);
                    setTimeout(() => {
                        if (!this.isMouseOverTooltip) {
                            tooltip.remove();
                        }
                    }, 100);
                });
                
                // Trouver le bouton dans le tooltip
                const detailsBtn = tooltipElement.querySelector('.tooltip-details-btn');
                if (detailsBtn) {
                    detailsBtn.addEventListener('click', (e) => {
                        e.preventDefault();
                        e.stopPropagation();
                        this.showPlaceModal(place);
                    });
                }
            }
        }, 50);
    }
    
    createTooltipContent(place) {
        const firstImage = place.images && place.images.length > 0 
            ? place.images[0] 
            : 'https://via.placeholder.com/200x150?text=No+Image';
        
        return `
            <div style="min-width:200px; padding:10px;">
                <div style="display:flex; align-items:center; gap:10px; margin-bottom:8px;">
                    <div style="width:40px; height:40px; border-radius:50%; background:${this.getCategoryColor(place.category)}; display:flex; align-items:center; justify-content:center;">
                        <i class="${this.getCategoryIcon(place.category)}" style="color:white; font-size:18px;"></i>
                    </div>
                    <div>
                        <strong style="color:#1a1a1a; font-size:15px;">${place.name}</strong>
                        <div style="font-size:12px; color:#666; margin-top:2px;">${this.capitalizeFirstLetter(place.category)}</div>
                    </div>
                </div>
                <div style="width:100%; height:100px; border-radius:6px; overflow:hidden; margin-bottom:8px;">
                    <img src="${firstImage}" alt="${place.name}" style="width:100%; height:100%; object-fit:cover;">
                </div>
                <p style="margin:0; font-size:12px; color:#666; line-height:1.4; -webkit-line-clamp: 3;">
                    ${place.description?.substring(0, 30) || 'Aucune description disponible'}...
                </p>
                <div style="margin-top:10px; text-align:center;">
                    <button class="tooltip-details-btn" 
                            style="background:#4299e1; color:white; border:none; border-radius:4px; padding:8px 15px; font-size:13px; cursor:pointer; width:100%; transition:all 0.3s ease;">
                        <i class="fas fa-info-circle"></i> Voir détails complets
                    </button>
                </div>
            </div>
        `;
    }
    
    highlightPlace(placeId) {
        // Mettre en surbrillance le marqueur
        const marker = this.markers[placeId];
        if (marker) {
            const iconElement = marker.getElement();
            if (iconElement) {
                const markerIcon = iconElement.querySelector('.marker-icon');
                if (markerIcon) {
                    markerIcon.classList.add('highlighted');
                }
            }
        }
        
        // Mettre en surbrillance l'élément dans la sidebar
        const placeElement = document.querySelector(`.place-item[data-id="${placeId}"]`);
        if (placeElement) {
            placeElement.classList.add('active');
            
            // Faire défiler jusqu'à l'élément
            placeElement.scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });
        }
    }
    
    unhighlightPlace(placeId) {
        // Retirer la surbrillance du marqueur
        const marker = this.markers[placeId];
        if (marker) {
            const iconElement = marker.getElement();
            if (iconElement) {
                const markerIcon = iconElement.querySelector('.marker-icon');
                if (markerIcon) {
                    markerIcon.classList.remove('highlighted');
                }
            }
        }
        
        // Retirer la surbrillance de l'élément dans la sidebar
        const placeElement = document.querySelector(`.place-item[data-id="${placeId}"]`);
        if (placeElement) {
            placeElement.classList.remove('active');
        }
    }
    
    getCategoryColor(category) {
        const colors = {
            restaurant: '#e53e3e',
            hotel: '#38a169',
            museum: '#805ad5',
            park: '#d69e2e',
            shopping: '#3182ce',
            monument: '#dd6b20',
            default: '#718096'
        };
        return colors[category] || colors.default;
    }
    
    getCategoryIcon(category) {
        const icons = {
            restaurant: 'fas fa-utensils',
            hotel: 'fas fa-hotel',
            museum: 'fas fa-landmark',
            park: 'fas fa-tree',
            shopping: 'fas fa-shopping-bag',
            monument: 'fas fa-monument',
            default: 'fas fa-map-marker-alt'
        };
        return icons[category] || icons.default;
    }
    
    getMarkersBounds() {
        const bounds = L.latLngBounds();
        this.places.forEach(place => {
            bounds.extend([place.latitude, place.longitude]);
        });
        return bounds;
    }
    
    renderPlacesList() {
        const container = document.getElementById('places-list');
        if (!container) return;
        
        container.innerHTML = '';
        
        if (this.places.length === 0) {
            container.innerHTML = `
                <div class="no-results">
                    <i class="fas fa-map-marker-alt"></i>
                    <h4>Aucun lieu trouvé</h4>
                    <p>Essayez de modifier vos filtres de recherche</p>
                </div>
            `;
            return;
        }
        
        this.places.forEach(place => {
            const placeEl = this.createPlaceElement(place);
            container.appendChild(placeEl);
        });
    }
    
    createPlaceElement(place) {
        const div = document.createElement('div');
        div.className = 'place-item';
        div.dataset.id = place.id;
        
        const image = place.images && place.images.length > 0 
            ? place.images[0] 
            : 'https://via.placeholder.com/400x150?text=No+Image';
        
        div.innerHTML = `
            <div class="place-image">
                <img src="${image}" alt="${place.name}" loading="lazy">
            </div>
            <div class="place-info">
                <h4>${place.name}</h4>
                <span class="place-category" style="background:${this.getCategoryColor(place.category)}">
                    ${this.capitalizeFirstLetter(place.category)}
                </span>
                <p class="place-description">${place.description?.substring(0, 100) || 'Aucune description disponible'}...</p>
                <div class="place-actions">
                    <button class="btn view-details-btn" data-id="${place.id}" style="background:#4299e1; color:white;">
                        <i class="fas fa-eye"></i> Détails
                    </button>
                    <button class="btn locate-btn-small" data-id="${place.id}" style="background:#48bb78; color:white;">
                        <i class="fas fa-map-marker-alt"></i> Carte
                    </button>
                </div>
            </div>
        `;
        
        // Événements pour la sidebar
        div.addEventListener('mouseenter', () => {
            this.highlightPlace(place.id);
        });
        
        div.addEventListener('mouseleave', () => {
            this.unhighlightPlace(place.id);
        });
        
        div.querySelector('.view-details-btn').addEventListener('click', (e) => {
            e.stopPropagation();
            this.showPlaceModal(place);
        });
        
        div.querySelector('.locate-btn-small').addEventListener('click', (e) => {
            e.stopPropagation();
            this.centerOnPlace(place);
        });
        
        div.addEventListener('click', (e) => {
            if (!e.target.closest('button')) {
                this.showPlaceModal(place);
            }
        });
        
        return div;
    }
    
    centerOnPlace(place) {
        // Marquer comme zoom/déplacement manuel
        this.manualZoom = true;
        this.lastManualView = {
            center: [place.latitude, place.longitude],
            zoom: 15
        };
        
        this.map.setView([place.latitude, place.longitude], 15);
        this.highlightPlace(place.id);
        
        // Ouvrir le tooltip
        const tooltip = this.tooltips[place.id];
        if (tooltip) {
            tooltip.addTo(this.map);
            
            // Fermer le tooltip après 3 secondes
            setTimeout(() => {
                tooltip.remove();
            }, 3000);
        }
    }
    
    showPlaceModal(place) {
        const modal = document.getElementById('place-modal');
        const modalContent = document.getElementById('modal-content');
        
        if (!modal || !modalContent) return;
        
        this.activePlace = place;
        modalContent.innerHTML = this.createModalContent(place);
        modal.style.display = 'block';
        
        // Initialiser Swiper avec un délai
        setTimeout(() => {
            this.initModalSwiper();
        }, 100);
        
        this.centerOnPlace(place);
    }
    
    createModalContent(place) {
        const images = place.images && place.images.length > 0 
            ? place.images 
            : ['https://via.placeholder.com/800x400?text=No+Image'];
        
        return `
            <div class="place-modal-content">
                <!-- Slider d'images -->
                <div class="modal-slider">
                    <div class="swiper modalSwiper">
                        <div class="swiper-wrapper">
                            ${images.map((img, index) => `
                                <div class="swiper-slide">
                                    <img src="${img}" alt="${place.name} - Image ${index + 1}" loading="lazy">
                                    <div class="image-counter">${index + 1} / ${images.length}</div>
                                </div>
                            `).join('')}
                        </div>
                        <div class="swiper-pagination"></div>
                        <div class="swiper-button-next"></div>
                        <div class="swiper-button-prev"></div>
                    </div>
                </div>
                
                <!-- Thumbnails -->
                <div class="modal-thumbnails" id="modalThumbnails">
                    ${images.map((img, index) => `
                        <div class="thumbnail ${index === 0 ? 'active' : ''}" data-index="${index}">
                            <img src="${img}" alt="Thumbnail ${index + 1}" loading="lazy">
                        </div>
                    `).join('')}
                </div>
                
                <!-- Contenu de la modal -->
                <div style="padding:30px;">
                    <div class="modal-header" style="margin-bottom:25px;">
                        <h2 style="margin:0 0 10px 0; color:#1a1a1a; font-size:1.8rem;">${place.name}</h2>
                        <div style="display:flex; align-items:center; gap:10px;">
                            <span style="background:${this.getCategoryColor(place.category)}; color:white; padding:6px 16px; border-radius:20px; font-size:14px; font-weight:500;">
                                ${this.capitalizeFirstLetter(place.category)}
                            </span>
                            <span style="color:#666; font-size:14px;">
                                <i class="fas fa-map-marker-alt"></i> ${this.provinceName}
                            </span>
                        </div>
                    </div>
                    
                    <div class="place-details">
                        ${place.description ? `
                            <div style="margin-bottom:30px;">
                                <h4 style="color:#333; margin-bottom:15px; font-size:1.2rem;">
                                    <i class="fas fa-info-circle" style="color:#4299e1;"></i> Description
                                </h4>
                                <p style="color:#666; line-height:1.6; font-size:16px;">${place.description}</p>
                            </div>
                        ` : ''}
                        
                        <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(250px, 1fr)); gap:20px; margin-bottom:30px;">
                            ${place.address ? `
                                <div style="background:#f8f9fa; padding:20px; border-radius:10px; border-left:4px solid #4299e1;">
                                    <div style="display:flex; align-items:center; gap:12px; margin-bottom:10px;">
                                        <i class="fas fa-map-marker-alt" style="color:#4299e1; font-size:18px;"></i>
                                        <strong style="color:#333; font-size:16px;">Adresse</strong>
                                    </div>
                                    <p style="margin:0; color:#666; font-size:15px;">${place.address}</p>
                                </div>
                            ` : ''}
                            
                            ${place.phone ? `
                                <div style="background:#f8f9fa; padding:20px; border-radius:10px; border-left:4px solid #38a169;">
                                    <div style="display:flex; align-items:center; gap:12px; margin-bottom:10px;">
                                        <i class="fas fa-phone" style="color:#38a169; font-size:18px;"></i>
                                        <strong style="color:#333; font-size:16px;">Contact</strong>
                                    </div>
                                    <p style="margin:0; color:#666; font-size:15px;">
                                        <a href="tel:${place.phone}" style="color:#4299e1; text-decoration:none; font-weight:500;">${place.phone}</a>
                                    </p>
                                </div>
                            ` : ''}
                            
                            ${place.website ? `
                                <div style="background:#f8f9fa; padding:20px; border-radius:10px; border-left:4px solid #805ad5;">
                                    <div style="display:flex; align-items:center; gap:12px; margin-bottom:10px;">
                                        <i class="fas fa-globe" style="color:#805ad5; font-size:18px;"></i>
                                        <strong style="color:#333; font-size:16px;">Site web</strong>
                                    </div>
                                    <p style="margin:0;">
                                        <a href="${place.website}" target="_blank" style="color:#4299e1; text-decoration:none; font-weight:500; font-size:15px;">Visiter le site officiel</a>
                                    </p>
                                </div>
                            ` : ''}
                        </div>
                    </div>
                    
                    <div style="display:flex; gap:15px; margin-top:30px; padding-top:25px; border-top:1px solid #e0e0e0;">
                        <button onclick="window.mapApp.getDirections(${JSON.stringify(place).replace(/"/g, '&quot;')})" 
                                style="flex:1; padding:14px; background:#48bb78; color:white; border:none; border-radius:8px; cursor:pointer; font-size:16px; font-weight:500; display:flex; align-items:center; justify-content:center; gap:10px;">
                            <i class="fas fa-route"></i> Itinéraire
                        </button>
                        <button onclick="window.mapApp.closeModal()" 
                                style="flex:1; padding:14px; background:#f0f0f0; color:#333; border:none; border-radius:8px; cursor:pointer; font-size:16px; font-weight:500; display:flex; align-items:center; justify-content:center; gap:10px;">
                            <i class="fas fa-times"></i> Fermer
                        </button>
                    </div>
                </div>
            </div>
        `;
    }
    
    initModalSwiper() {
        // Détruire Swiper existant
        if (this.swiper && this.swiper.destroy) {
            try {
                this.swiper.destroy(true, true);
            } catch (error) {
                console.warn('Error destroying Swiper:', error);
            }
            this.swiper = null;
        }
        
        // Vérifier si Swiper est chargé
        if (typeof Swiper === 'undefined') {
            console.error('Swiper is not loaded');
            return;
        }
        
        // Vérifier si l'élément Swiper existe
        const swiperElement = document.querySelector('.modalSwiper');
        if (!swiperElement) {
            console.error('Swiper element not found');
            return;
        }
        
        try {
            this.swiper = new Swiper('.modalSwiper', {
                loop: true,
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                autoplay: {
                    delay: 5000,
                    disableOnInteraction: false,
                },
                on: {
                    init: () => {
                        console.log('Swiper initialized');
                        // Mettre à jour les thumbnails après l'initialisation
                        setTimeout(() => {
                            this.updateThumbnails();
                        }, 50);
                    },
                    slideChange: () => {
                        this.updateThumbnails();
                    }
                }
            });
            
            // Gérer les thumbnails
            this.setupThumbnailEvents();
            
        } catch (error) {
            console.error('Error initializing Swiper:', error);
        }
    }
    
    setupThumbnailEvents() {
        // Attendre que le DOM soit mis à jour
        setTimeout(() => {
            const thumbnails = document.querySelectorAll('.thumbnail');
            if (!thumbnails || thumbnails.length === 0) {
                return;
            }
            
            thumbnails.forEach(thumbnail => {
                // Retirer les anciens événements
                const newThumbnail = thumbnail.cloneNode(true);
                thumbnail.parentNode.replaceChild(newThumbnail, thumbnail);
            });
            
            // Référencer les nouveaux éléments
            const newThumbnails = document.querySelectorAll('.thumbnail');
            
            newThumbnails.forEach(thumbnail => {
                thumbnail.addEventListener('click', () => {
                    if (!this.swiper) return;
                    
                    const index = parseInt(thumbnail.dataset.index);
                    if (!isNaN(index)) {
                        this.swiper.slideTo(index);
                        this.updateThumbnails();
                    }
                });
            });
        }, 100);
    }
    
    updateThumbnails() {
        // Vérifier si Swiper est initialisé
        if (!this.swiper || !this.swiper.realIndex) {
            return;
        }
        
        const thumbnails = document.querySelectorAll('.thumbnail');
        if (!thumbnails || thumbnails.length === 0) {
            return;
        }
        
        const activeIndex = this.swiper.realIndex;
        
        thumbnails.forEach((thumbnail, index) => {
            if (index === activeIndex) {
                thumbnail.classList.add('active');
            } else {
                thumbnail.classList.remove('active');
            }
        });
    }
    
    getDirections(place) {
        // Vérifier si place est défini
        if (!place) {
            console.error('Place is undefined');
            return;
        }
        
        // Vérifier si l'utilisateur est localisé
        if (!this.currentLocation) {
            alert('Veuillez d\'abord vous localiser en cliquant sur "Me localiser" pour calculer un itinéraire.');
            return;
        }
        
        // Vérifier les coordonnées
        if (!place.latitude || !place.longitude) {
            alert('Impossible de calculer l\'itinéraire : coordonnées du lieu manquantes.');
            return;
        }
        
        const startLat = this.currentLocation.lat;
        const startLng = this.currentLocation.lng;
        const endLat = place.latitude;
        const endLng = place.longitude;
        
        // Calculer la distance
        const distance = this.calculateDistance(startLat, startLng, endLat, endLng);
        
        // Ouvrir Google Maps avec l'itinéraire
        const googleMapsUrl = `https://www.google.com/maps/dir/?api=1&origin=${startLat},${startLng}&destination=${endLat},${endLng}&travelmode=driving`;
        
        // Afficher une confirmation
        const confirmMessage = `Calcul d'itinéraire vers "${place.name}":
        
        • Distance: ${distance.toFixed(1)} km
        • Départ: Votre position actuelle
        • Arrivée: ${place.address || 'Destination'}

        Voulez-vous ouvrir Google Maps pour voir l'itinéraire détaillé ?`;
        
        if (confirm(confirmMessage)) {
            window.open(googleMapsUrl, '_blank');
        }
        
        // Fermer la modal
        this.closeModal();
    }
    
    calculateDistance(lat1, lon1, lat2, lon2) {
        // Formule de Haversine pour calculer la distance en km
        const R = 6371; // Rayon de la Terre en km
        const dLat = this.deg2rad(lat2 - lat1);
        const dLon = this.deg2rad(lon2 - lon1);
        const a = 
            Math.sin(dLat/2) * Math.sin(dLat/2) +
            Math.cos(this.deg2rad(lat1)) * Math.cos(this.deg2rad(lat2)) * 
            Math.sin(dLon/2) * Math.sin(dLon/2);
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
        return R * c;
    }
    
    deg2rad(deg) {
        return deg * (Math.PI/180);
    }
    
    setupEventListeners() {
        // Filtre de région
        const regionFilter = document.getElementById('region-filter');
        if (regionFilter) {
            regionFilter.addEventListener('change', (e) => {
                this.selectedRegion = e.target.value;
                
                // Centrer sur la région si une région spécifique est sélectionnée
                if (this.selectedRegion && e.target.selectedOptions[0].dataset.lat) {
                    const lat = parseFloat(e.target.selectedOptions[0].dataset.lat);
                    const lng = parseFloat(e.target.selectedOptions[0].dataset.lng);
                    
                    if (!isNaN(lat) && !isNaN(lng)) {
                        // Définir comme zoom/déplacement manuel
                        this.manualZoom = true;
                        this.lastManualView = {
                            center: [lat, lng],
                            zoom: 10
                        };
                        this.map.setView([lat, lng], 10);
                    }
                } else {
                    // Si "Toutes les régions" est sélectionnée, revenir au comportement automatique
                    this.manualZoom = false;
                    this.lastManualView = null;
                }
                
                this.loadPlaces();
            });
        }
        
        // Filtre de catégorie
        const categoryFilter = document.getElementById('category-filter');
        if (categoryFilter) {
            categoryFilter.addEventListener('change', (e) => {
                this.selectedCategory = e.target.value;
                this.loadPlaces();
            });
        }
        
        // Filtre de rayon
        const radiusSlider = document.getElementById('radius-filter');
        const radiusValue = document.getElementById('radius-value');
        
        if (radiusSlider && radiusValue) {
            radiusSlider.addEventListener('input', (e) => {
                this.radius = parseInt(e.target.value);
                radiusValue.textContent = `${this.radius} km`;
            });
            
            radiusSlider.addEventListener('change', () => {
                this.loadPlaces();
            });
        }
        
        // Bouton "Me localiser"
        const locateBtn = document.getElementById('locate-me');
        if (locateBtn) {
            locateBtn.addEventListener('click', () => {
                this.locateUser();
            });
        }
        
        // Fermer la modal
        document.querySelector('.close-modal')?.addEventListener('click', () => {
            this.closeModal();
        });
        
        // Fermer au clic en dehors
        window.addEventListener('click', (e) => {
            const modal = document.getElementById('place-modal');
            if (e.target === modal) {
                this.closeModal();
            }
        });
        
        // Touche Échap
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                this.closeModal();
            }
        });
    }
    
    locateUser() {
        if (!navigator.geolocation) {
            alert('La géolocalisation n\'est pas supportée par votre navigateur.');
            return;
        }
        
        // Afficher un indicateur de chargement
        const locateBtn = document.getElementById('locate-me');
        const originalHTML = locateBtn.innerHTML;
        locateBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Localisation...';
        locateBtn.disabled = true;
        
        navigator.geolocation.getCurrentPosition(
            (position) => {
                const { latitude, longitude } = position.coords;
                this.currentLocation = { lat: latitude, lng: longitude };
                
                // Marquer comme zoom/déplacement manuel
                this.manualZoom = true;
                this.lastManualView = {
                    center: [latitude, longitude],
                    zoom: 12
                };
                
                // Centrer la carte sur la position
                this.map.setView([latitude, longitude], 12);
                
                // Ajouter un marqueur pour la position de l'utilisateur
                this.addUserMarker(latitude, longitude);
                
                // Charger les lieux autour de la position
                this.loadPlaces();
                
                // Restaurer le bouton
                locateBtn.innerHTML = originalHTML;
                locateBtn.disabled = false;
            },
            (error) => {
                console.error('Erreur de géolocalisation:', error);
                let errorMessage = 'Impossible de vous localiser.';
                
                switch(error.code) {
                    case error.PERMISSION_DENIED:
                        errorMessage = 'Autorisation de géolocalisation refusée. Veuillez autoriser l\'accès à votre position.';
                        break;
                    case error.POSITION_UNAVAILABLE:
                        errorMessage = 'Informations de localisation non disponibles.';
                        break;
                    case error.TIMEOUT:
                        errorMessage = 'La demande de localisation a expiré.';
                        break;
                }
                
                alert(errorMessage);
                
                // Restaurer le bouton
                locateBtn.innerHTML = originalHTML;
                locateBtn.disabled = false;
            },
            {
                enableHighAccuracy: true,
                timeout: 15000,
                maximumAge: 0
            }
        );
    }
    
    addUserMarker(lat, lng) {
        // Supprimer l'ancien marqueur s'il existe
        if (this.userMarker) {
            this.userMarker.remove();
        }
        
        // Créer un marqueur personnalisé pour l'utilisateur
        const userIcon = L.divIcon({
            className: 'custom-marker',
            html: `
                <div class="user-marker-icon">
                    <i class="fas fa-user"></i>
                </div>
            `,
            iconSize: [50, 50],
            iconAnchor: [25, 50],
            popupAnchor: [0, -50]
        });
        
        // Ajouter le marqueur
        this.userMarker = L.marker([lat, lng], { 
            icon: userIcon,
            title: 'Votre position'
        }).addTo(this.map);
        
        // Ajouter une popup
        this.userMarker.bindPopup(`
            <div style="text-align:center; padding:10px;">
                <h4 style="margin:0 0 10px 0; color:#4299e1;">
                    <i class="fas fa-user"></i> Votre position
                </h4>
                <p style="margin:0; font-size:14px; color:#666;">
                    Latitude: ${lat.toFixed(6)}<br>
                    Longitude: ${lng.toFixed(6)}
                </p>
            </div>
        `).openPopup();
        
        // Animer le marqueur
        this.animateUserMarker();
    }
    
    animateUserMarker() {
        if (!this.userMarker) return;
        
        const markerElement = this.userMarker.getElement();
        if (markerElement) {
            const userIcon = markerElement.querySelector('.user-marker-icon');
            if (userIcon) {
                // Ajouter une animation de pulsation
                userIcon.style.animation = 'userMarkerPulse 2s infinite';
            }
        }
    }
    
    closeModal() {
        // Détruire Swiper avant de fermer
        if (this.swiper && this.swiper.destroy) {
            try {
                this.swiper.destroy(true, true);
            } catch (error) {
                console.warn('Error destroying Swiper:', error);
            }
            this.swiper = null;
        }
        
        const modal = document.getElementById('place-modal');
        if (modal) {
            modal.style.display = 'none';
        }
        
        this.activePlace = null;
    }
    
    updatePlacesCount() {
        const countEl = document.getElementById('places-count');
        if (countEl) {
            countEl.textContent = this.places.length;
        }
    }
    
    capitalizeFirstLetter(string) {
        if (!string) return '';
        return string.charAt(0).toUpperCase() + string.slice(1);
    }
}

// Gestion des onglets principaux
function initMainTabs() {
    const mainTabs = document.querySelectorAll('.main-tab');
    const tabContents = document.querySelectorAll('.tab-content-main');
    
    // Fonction pour activer un onglet
    function activateTab(tabElement) {
        const tabId = tabElement.getAttribute('data-tab');
        
        // Retirer la classe active de tous les onglets
        mainTabs.forEach(tab => tab.classList.remove('active'));
        tabContents.forEach(content => content.classList.remove('active'));
        
        // Ajouter la classe active à l'onglet cliqué
        tabElement.classList.add('active');
        document.getElementById(`tab-${tabId}-content`).classList.add('active');
        
        // Sauvegarder l'onglet actif dans localStorage
        localStorage.setItem('mainActiveTab', tabId);
        
        // Redimensionner la carte si on revient sur l'onglet carte
        if (tabId === 'carte' && window.mapApp && window.mapApp.map) {
            setTimeout(() => {
                window.mapApp.map.invalidateSize();
            }, 300);
        }
    }
    
    // Gérer les clics sur les onglets
    mainTabs.forEach(tab => {
        tab.addEventListener('click', function() {
            activateTab(this);
        });
    });
    
    // Restaurer l'onglet actif depuis localStorage
    const savedTab = localStorage.getItem('mainActiveTab');
    if (savedTab) {
        const tabToActivate = document.querySelector(`.main-tab[data-tab="${savedTab}"]`);
        if (tabToActivate) {
            activateTab(tabToActivate);
        }
    }
}

// Initialiser le graphique de population
function initPopulationChart() {
    const ctx = document.getElementById('populationChart');
    if (!ctx) return;
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['2015', '2016', '2017', '2018', '2019', '2020', '2021', '2022', '2023'],
            datasets: [{
                label: 'Population de <?php echo e($province->name); ?>',
                data: [
                    <?php echo e($province->population * 0.92); ?>,
                    <?php echo e($province->population * 0.94); ?>,
                    <?php echo e($province->population * 0.96); ?>,
                    <?php echo e($province->population * 0.97); ?>,
                    <?php echo e($province->population * 0.98); ?>,
                    <?php echo e($province->population * 0.99); ?>,
                    <?php echo e($province->population); ?>,
                    <?php echo e($province->population * 1.01); ?>,
                    <?php echo e($province->population * 1.02); ?>
                ],
                borderColor: '#2c5282',
                backgroundColor: 'rgba(44, 82, 130, 0.1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Évolution démographique 2015-2023'
                }
            },
            scales: {
                y: {
                    beginAtZero: false,
                    title: {
                        display: true,
                        text: 'Population'
                    }
                }
            }
        }
    });
}

// Initialisation globale
document.addEventListener('DOMContentLoaded', () => {
    try {
        // Initialiser les onglets principaux
        initMainTabs();
        
        // Initialiser la carte seulement si on est sur l'onglet carte
        if (document.querySelector('.main-tab.active').getAttribute('data-tab') === 'carte') {
            window.mapApp = new InteractiveMap();
            console.log('Application carte interactive prête pour la province:', window.mapApp.provinceName);
        }
        
        // Initialiser le graphique de population
        initPopulationChart();
        
    } catch (error) {
        console.error('Erreur fatale:', error);
        alert('Erreur lors du chargement de l\'application. Veuillez recharger la page.');
    }
});

// Réinitialiser la carte quand on change d'onglet
document.addEventListener('click', (e) => {
    if (e.target.closest('.main-tab')) {
        const tabId = e.target.closest('.main-tab').getAttribute('data-tab');
        if (tabId === 'carte' && window.mapApp && window.mapApp.map) {
            setTimeout(() => {
                window.mapApp.map.invalidateSize();
            }, 300);
        }
    }
});
</script>
<script src="{{ asset('js/custom.js') }}"></script>
<style>
    /* 5 colonnes - 20% chacune */
    .col-md-2-4 {
        width: 20%;
        float: left;
        padding: 0 8px;
        box-sizing: border-box;
    }
    
    /* Clearfix */
    #regionsDropdownContainer::after {
        content: "";
        display: table;
        clear: both;
    }
    
    /* Style minimaliste des cartes */
    .region-card-simple {
        background: white;
        border-radius: 8px;
        overflow: hidden;
        margin-bottom: 12px;
        border: 1px solid #e9ecef;
        transition: all 0.2s ease;
        cursor: pointer;
    }
    
    .region-card-simple:hover {
        border-color: #007bff;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    
    .region-img-wrapper {
        height: 80px;
        overflow: hidden;
        position: relative;
    }
    
    .region-img-simple {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }
    
    .region-name {
        padding: 10px 8px;
        text-align: center;
        font-size: 0.85rem;
        font-weight: 600;
        color: #333;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        background: #f8f9fa;
        border-top: 1px solid #e9ecef;
    }
    
    .region-item-simple {
        text-decoration: none;
        display: block;
        animation: fadeIn 0.3s ease forwards;
        opacity: 0;
    }
    
    /* Animation */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    /* Responsive */
    @media (max-width: 1200px) {
        .col-md-2-4 { width: 25%; padding: 0 6px; }
    }
    
    @media (max-width: 992px) {
        .col-md-2-4 { width: 33.333%; padding: 0 5px; }
        .region-img-wrapper { height: 70px; }
    }
    
    @media (max-width: 768px) {
        .col-md-2-4 { width: 50%; padding: 0 4px; }
        .region-img-wrapper { height: 65px; }
        .region-name { font-size: 0.8rem; padding: 8px 4px; }
    }
    
    @media (max-width: 480px) {
        .col-md-2-4 { width: 100%; padding: 0; }
        .region-card-simple { 
            display: flex; 
            align-items: center;
            margin-bottom: 8px;
        }
        .region-img-wrapper { 
            width: 100px; 
            height: 60px; 
            flex-shrink: 0; 
        }
        .region-name { 
            flex-grow: 1; 
            border: none; 
            text-align: left; 
            padding-left: 12px;
            background: white;
        }
    }
    
    /* Loader */
    .spinner-border-sm {
        width: 1rem;
        height: 1rem;
    }
    
    /* Dropdown centré */
    .dropdown-menu.full-width {
        min-width: 100vw !important;
    }
</style>
</body>
</html>