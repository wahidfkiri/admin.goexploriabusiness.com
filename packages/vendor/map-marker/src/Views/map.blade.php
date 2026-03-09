{{-- resources/views/map-points/map.blade.php --}}
@extends('layouts.app')

@section('content')
    
    <!-- MAIN CONTENT -->
    <main class="dashboard-content">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">
                <span class="page-title-icon"><i class="fas fa-map-marked-alt"></i></span>
                Carte Interactive
            </h1>
            
            <div class="page-actions">
                <button class="btn btn-outline-secondary" id="toggleSidebarBtn">
                    <i class="fas fa-sliders-h me-2"></i>Filtres
                </button>
                <a href="{{ route('map-points.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus-circle me-2"></i>Ajouter un point
                </a>
            </div>
        </div>
        
        <!-- Stats Cards -->
        <div class="stats-grid mb-4">
            <div class="stats-card-modern">
                <div class="stats-header-modern">
                    <div>
                        <div class="stats-value-modern" id="totalPoints">{{ $totalPoints ?? 0 }}</div>
                        <div class="stats-label-modern">Total Points</div>
                    </div>
                    <div class="stats-icon-modern" style="background: linear-gradient(135deg, var(--primary-color), #3a56e4);">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                </div>
            </div>
            
            <div class="stats-card-modern">
                <div class="stats-header-modern">
                    <div>
                        <div class="stats-value-modern" id="visiblePoints">0</div>
                        <div class="stats-label-modern">Affichés</div>
                    </div>
                    <div class="stats-icon-modern" style="background: linear-gradient(135deg, #45b7d1, #3a9bb8);">
                        <i class="fas fa-eye"></i>
                    </div>
                </div>
            </div>
            
            <div class="stats-card-modern">
                <div class="stats-header-modern">
                    <div>
                        <div class="stats-value-modern" id="categoriesCount">{{ $categoriesCount ?? 0 }}</div>
                        <div class="stats-label-modern">Catégories</div>
                    </div>
                    <div class="stats-icon-modern" style="background: linear-gradient(135deg, #FFD166, #ffb851);">
                        <i class="fas fa-tags"></i>
                    </div>
                </div>
            </div>
            
            <div class="stats-card-modern">
                <div class="stats-header-modern">
                    <div>
                        <div class="stats-value-modern" id="villesCount">{{ $villesCount ?? 0 }}</div>
                        <div class="stats-label-modern">Villes</div>
                    </div>
                    <div class="stats-icon-modern" style="background: linear-gradient(135deg, #9b59b6, #8e44ad);">
                        <i class="fas fa-city"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Main Map Container -->
        <div class="map-wrapper">
            <div class="map-container-modern">
                <!-- Carte -->
                <div id="map" class="map-canvas"></div>
                
                <!-- Bouton pour recentrer -->
                <button class="map-recenter-btn" id="recenterBtn" title="Recentrer">
                    <i class="fas fa-location-arrow"></i>
                </button>
                
                <!-- Bouton pour ma position -->
                <button class="map-location-btn" id="myLocationBtn" title="Ma position">
                    <i class="fas fa-crosshairs"></i>
                </button>
                
                <!-- Loading Overlay -->
                <div class="map-loading" id="mapLoading" style="display: none;">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Chargement...</span>
                    </div>
                </div>
            </div>
            
            <!-- Sidebar des filtres -->
            <div class="map-sidebar" id="mapSidebar">
                <div class="sidebar-header">
                    <h4><i class="fas fa-filter me-2"></i>Filtres</h4>
                    <button class="btn-close" id="closeSidebarBtn"></button>
                </div>
                
                <div class="sidebar-body">
                    <!-- Recherche -->
                    <div class="filter-group">
                        <label><i class="fas fa-search me-1"></i> Rechercher</label>
                        <input type="text" class="filter-input" id="searchInput" placeholder="Nom, description...">
                    </div>
                    
                    <!-- Filtre par catégorie -->
                    <div class="filter-group">
    <label><i class="fas fa-tag me-1"></i> Catégorie</label>
    <select class="filter-select" id="categoryFilter">
        <option value="">Toutes les catégories</option>
        @foreach($categories ?? [] as $category)
            <option value="{{ $category['category'] }}">
                {{ $category['category_label'] ?? $category['category'] }}
            </option>
        @endforeach
    </select>
</div>
                    
                   <!-- Filtre par ville -->
<div class="filter-group">
    <label><i class="fas fa-city me-1"></i> Ville</label>
    <select class="filter-select" id="villeFilter">
        <option value="">Toutes les villes</option>
        @foreach($villes ?? [] as $ville)
            <option value="{{ $ville['ville'] }}">{{ $ville['ville'] }}</option>
        @endforeach
    </select>
</div>
                    
                    <!-- Filtres supplémentaires -->
                    <div class="filter-group">
                        <label><i class="fas fa-video me-1"></i> Médias</label>
                        <div class="filter-checkbox">
                            <input type="checkbox" id="hasVideoFilter">
                            <label for="hasVideoFilter">Avec vidéo uniquement</label>
                        </div>
                        <div class="filter-checkbox">
                            <input type="checkbox" id="hasDetailsFilter">
                            <label for="hasDetailsFilter">Avec page détails</label>
                        </div>
                    </div>
                    
                    <!-- Boutons d'action -->
                    <div class="filter-actions">
                        <button class="btn-filter-apply" id="applyFilters">
                            <i class="fas fa-check me-1"></i> Appliquer
                        </button>
                        <button class="btn-filter-reset" id="resetFilters">
                            <i class="fas fa-undo me-1"></i> Réinitialiser
                        </button>
                    </div>
                    
                    <!-- Légende des catégories -->
<div class="legend-section">
    <h5><i class="fas fa-palette me-1"></i> Légende</h5>
    <div class="legend-items" id="legendItems">
        @foreach($categories ?? [] as $category)
            <div class="legend-item">
                <span class="legend-color" style="background: {{ $category['color'] ?? '#6c757d' }}"></span>
                <span class="legend-label">{{ $category['category_label'] ?? $category['category'] }}</span>
                <span class="legend-count" id="count-{{ $category['category'] }}">0</span>
            </div>
        @endforeach
    </div>
</div>
                </div>
            </div>
        </div>
        
        <!-- Liste des points (mobile/table) -->
        <div class="points-list-section mt-4">
            <div class="points-list-header">
                <h3><i class="fas fa-list me-2"></i>Points à proximité</h3>
                <span class="points-count" id="pointsCount">{{ $totalPoints ?? 0 }}</span>
            </div>
            
            <div class="points-grid" id="pointsGrid">
                <!-- Les points seront chargés ici via AJAX -->
                <div class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Chargement...</span>
                    </div>
                </div>
            </div>
        </div>
    </main>
    
    <!-- MODAL DÉTAILS -->
    <div class="modal fade" id="pointModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modalBody">
                    <!-- Contenu chargé dynamiquement -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                    <a href="#" class="btn btn-primary" id="modalDetailsBtn" style="display: none;">
                        <i class="fas fa-info-circle me-1"></i> Voir détails
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Leaflet CSS & JS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // ==================== CONFIGURATION ====================
            const mapElement = document.getElementById('map');
            let map, markers = [], allPoints = [], currentBounds = null;
            
            // Couleurs par catégorie
            const categoryColors = {
                'restaurant': '#FF6B6B',
                'hotel': '#4ECDC4',
                'commerce': '#45B7D1',
                'sante': '#96CEB4',
                'education': '#FFE194',
                'culture': '#DDA0DD',
                'sport': '#FFA07A',
                'loisirs': '#90EE90',
                'transport': '#A9A9A9',
                'immobilier': '#1b4f6b',
                'service': '#B0C4DE',
                'autre': '#6c757d'
            };
            
            // Icônes par catégorie
            const categoryIcons = {
                'restaurant': 'fa-utensils',
                'hotel': 'fa-hotel',
                'commerce': 'fa-shop',
                'sante': 'fa-hospital',
                'education': 'fa-school',
                'culture': 'fa-museum',
                'sport': 'fa-dumbbell',
                'loisirs': 'fa-tree',
                'transport': 'fa-bus',
                'immobilier': 'fa-building',
                'service': 'fa-gear',
                'autre': 'fa-map-pin'
            };

            // ==================== INITIALISATION DE LA CARTE ====================
            function initMap() {
                if (!mapElement) return;
                
                // Centrer sur une position par défaut (Montréal)
                map = L.map('map').setView([45.5089, -73.5617], 12);
                
                // Fond de carte OpenStreetMap
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OpenStreetMap contributors'
                }).addTo(map);
                
                // Charger les points
                loadMapPoints();
                
                // Gestionnaire d'événements pour le déplacement de la carte
                map.on('moveend', function() {
                    currentBounds = map.getBounds();
                    updateVisibleCount();
                    loadPointsList();
                });
                
                map.on('zoomend', function() {
                    currentBounds = map.getBounds();
                });
            }

            // ==================== CHARGEMENT DES POINTS ====================
            function loadMapPoints(filters = {}) {
                showMapLoading();
                
                // Récupérer les filtres
                const params = new URLSearchParams();
                
                if (filters.category) params.append('category', filters.category);
                if (filters.ville) params.append('ville', filters.ville);
                if (filters.search) params.append('search', filters.search);
                if (filters.has_video) params.append('has_video', filters.has_video);
                if (filters.has_details) params.append('has_details', filters.has_details);
                
                // Récupérer les limites de la carte pour optimiser
                if (currentBounds) {
                    params.append('bounds[ne][lat]', currentBounds.getNorthEast().lat);
                    params.append('bounds[ne][lng]', currentBounds.getNorthEast().lng);
                    params.append('bounds[sw][lat]', currentBounds.getSouthWest().lat);
                    params.append('bounds[sw][lng]', currentBounds.getSouthWest().lng);
                }
                
                fetch(`/api/map-points?${params.toString()}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            allPoints = data.points;
                            renderMarkers(allPoints);
                            updateStats(data);
                            updateLegendCounts(allPoints);
                            hideMapLoading();
                        } else {
                            console.error('Erreur:', data.message);
                            hideMapLoading();
                        }
                    })
                    .catch(error => {
                        console.error('Erreur de chargement:', error);
                        hideMapLoading();
                    });
            }

            // ==================== AFFICHAGE DES MARQUEURS ====================
            function renderMarkers(points) {
                // Supprimer les anciens marqueurs
                markers.forEach(marker => map.removeLayer(marker));
                markers = [];
                
                if (!points || points.length === 0) return;
                
                points.forEach(point => {
                    // Créer une icône personnalisée
                    const color = categoryColors[point.category] || '#6c757d';
                    const icon = categoryIcons[point.category] || 'fa-map-pin';
                    
                    const customIcon = L.divIcon({
                        className: 'custom-marker',
                        html: `<div class="marker-icon" style="background: ${color};">
                                <i class="fas ${icon}"></i>
                               </div>`,
                        iconSize: [30, 30],
                        iconAnchor: [15, 30],
                        popupAnchor: [0, -30]
                    });
                    
                    // Créer le marqueur
                    const marker = L.marker([point.lat, point.lng], {
                        icon: customIcon,
                        title: point.title
                    }).addTo(map);
                    
                    // Ajouter le popup
                    marker.bindPopup(createPopupContent(point));
                    
                    // Stocker le marqueur
                    marker.pointData = point;
                    markers.push(marker);
                });
                
                updateVisibleCount();
            }

            // ==================== CRÉATION DU POPUP ====================
            function createPopupContent(point) {
                let content = `
                    <div class="map-popup">
                        <div class="popup-header" style="border-left-color: ${categoryColors[point.category] || '#6c757d'}">
                            <h4>${point.title}</h4>
                            <span class="popup-category">
                                <i class="fas ${categoryIcons[point.category] || 'fa-map-pin'} me-1"></i>
                                ${point.category_label || point.category}
                            </span>
                        </div>
                `;
                
                // Image ou vidéo
                if (point.youtube_id) {
                    content += `
                        <div class="popup-media">
                            <img src="https://img.youtube.com/vi/${point.youtube_id}/hqdefault.jpg" 
                                 alt="Video thumbnail"
                                 class="popup-video-thumb"
                                 onclick="playVideoInModal('${point.youtube_id}')">
                            <button class="popup-play-btn" onclick="playVideoInModal('${point.youtube_id}')">
                                <i class="fas fa-play"></i>
                            </button>
                        </div>
                    `;
                } else if (point.image) {
                    content += `
                        <div class="popup-media">
                            <img src="${point.image}" alt="${point.title}" class="popup-image">
                        </div>
                    `;
                }
                
                // Description
                if (point.description) {
                    content += `<div class="popup-description">${point.description}</div>`;
                }
                
                // Adresse
                if (point.adresse || point.ville) {
                    content += `
                        <div class="popup-address">
                            <i class="fas fa-map-pin me-1"></i>
                            ${point.adresse ? point.adresse + ', ' : ''}${point.ville || ''}
                        </div>
                    `;
                }
                
                // Boutons d'action
                content += `<div class="popup-actions">`;
                
                if (point.has_details && point.details_url) {
                    content += `
                        <a href="${point.details_url}" class="popup-btn popup-btn-details">
                            <i class="fas fa-info-circle me-1"></i> Plus de détails
                        </a>
                    `;
                }
                
                content += `
                        <button class="popup-btn popup-btn-directions" onclick="getDirections(${point.lat}, ${point.lng})">
                            <i class="fas fa-directions me-1"></i> Itinéraire
                        </button>
                    </div>
                </div>`;
                
                return content;
            }

            // ==================== CHARGEMENT DE LA LISTE DES POINTS ====================
            function loadPointsList() {
                const grid = document.getElementById('pointsGrid');
                if (!grid) return;
                
                if (!currentBounds) {
                    currentBounds = map.getBounds();
                }
                
                const visiblePoints = allPoints.filter(point => {
                    return currentBounds.contains([point.lat, point.lng]);
                });
                
                if (visiblePoints.length === 0) {
                    grid.innerHTML = `
                        <div class="empty-points">
                            <i class="fas fa-map-marker-alt"></i>
                            <h4>Aucun point dans cette zone</h4>
                            <p>Déplacez la carte ou ajustez les filtres</p>
                        </div>
                    `;
                    return;
                }
                
                let html = '';
                visiblePoints.slice(0, 6).forEach(point => {
                    const color = categoryColors[point.category] || '#6c757d';
                    const icon = categoryIcons[point.category] || 'fa-map-pin';
                    
                    html += `
                        <div class="point-card" onclick="flyToPoint(${point.lat}, ${point.lng})">
                            <div class="point-card-media">
                                ${point.youtube_id ? 
                                    `<img src="https://img.youtube.com/vi/${point.youtube_id}/hqdefault.jpg" alt="${point.title}">` :
                                    point.image ? 
                                    `<img src="${point.image}" alt="${point.title}">` :
                                    `<div class="point-card-placeholder" style="background: ${color}20">
                                        <i class="fas ${icon}" style="color: ${color}"></i>
                                     </div>`
                                }
                            </div>
                            <div class="point-card-content">
                                <h5>${point.title}</h5>
                                <span class="point-card-category" style="color: ${color}">
                                    <i class="fas ${icon} me-1"></i>${point.category_label || point.category}
                                </span>
                                <p>${point.description || ''}</p>
                            </div>
                        </div>
                    `;
                });
                
                if (visiblePoints.length > 6) {
                    html += `
                        <div class="point-card more-card">
                            <i class="fas fa-plus-circle"></i>
                            <p>+${visiblePoints.length - 6} autres points</p>
                        </div>
                    `;
                }
                
                grid.innerHTML = html;
            }

            // ==================== MISE À JOUR DES STATISTIQUES ====================
            function updateStats(data) {
                const totalPoints = document.getElementById('totalPoints');
                if (totalPoints) totalPoints.textContent = data.total || 0;
                
                updateVisibleCount();
            }
            
            function updateVisibleCount() {
                const visiblePoints = document.getElementById('visiblePoints');
                if (!visiblePoints || !map || !allPoints) return;
                
                if (!currentBounds) currentBounds = map.getBounds();
                
                const count = allPoints.filter(point => {
                    return currentBounds.contains([point.lat, point.lng]);
                }).length;
                
                visiblePoints.textContent = count;
            }
            
            function updateLegendCounts(points) {
                const counts = {};
                points.forEach(point => {
                    counts[point.category] = (counts[point.category] || 0) + 1;
                });
                
                for (const [category, count] of Object.entries(counts)) {
                    const element = document.getElementById(`count-${category}`);
                    if (element) element.textContent = count;
                }
            }

            // ==================== FILTRES ====================
            function applyFilters() {
                const filters = {
                    category: document.getElementById('categoryFilter')?.value,
                    ville: document.getElementById('villeFilter')?.value,
                    search: document.getElementById('searchInput')?.value,
                    has_video: document.getElementById('hasVideoFilter')?.checked ? 'yes' : null,
                    has_details: document.getElementById('hasDetailsFilter')?.checked ? 'yes' : null
                };
                
                // Nettoyer les filtres vides
                Object.keys(filters).forEach(key => {
                    if (!filters[key]) delete filters[key];
                });
                
                loadMapPoints(filters);
            }
            
            function resetFilters() {
                document.getElementById('categoryFilter').value = '';
                document.getElementById('villeFilter').value = '';
                document.getElementById('searchInput').value = '';
                document.getElementById('hasVideoFilter').checked = false;
                document.getElementById('hasDetailsFilter').checked = false;
                
                loadMapPoints({});
            }

            // ==================== UTILITAIRES ====================
            function showMapLoading() {
                const loader = document.getElementById('mapLoading');
                if (loader) loader.style.display = 'flex';
            }
            
            function hideMapLoading() {
                const loader = document.getElementById('mapLoading');
                if (loader) loader.style.display = 'none';
            }
            
            // Fonctions globales pour les appels depuis les popups
            window.playVideoInModal = function(videoId) {
                const modalBody = document.getElementById('modalBody');
                const modalTitle = document.getElementById('modalTitle');
                
                modalTitle.textContent = 'Aperçu Vidéo';
                modalBody.innerHTML = `
                    <div class="video-container">
                        <iframe src="https://www.youtube.com/embed/${videoId}?autoplay=1" 
                                frameborder="0" 
                                allowfullscreen></iframe>
                    </div>
                `;
                
                document.getElementById('modalDetailsBtn').style.display = 'none';
                
                const modal = new bootstrap.Modal(document.getElementById('pointModal'));
                modal.show();
            };
            
            window.getDirections = function(lat, lng) {
                window.open(`https://www.google.com/maps/dir/?api=1&destination=${lat},${lng}`, '_blank');
            };
            
            window.flyToPoint = function(lat, lng) {
                map.flyTo([lat, lng], 16);
            };

            // ==================== GESTIONNAIRES D'ÉVÉNEMENTS ====================
            // Bouton recentrer
            document.getElementById('recenterBtn')?.addEventListener('click', () => {
                map.setView([45.5089, -73.5617], 12);
            });
            
            // Bouton ma position
            document.getElementById('myLocationBtn')?.addEventListener('click', () => {
                if (navigator.geolocation) {
                    showMapLoading();
                    navigator.geolocation.getCurrentPosition(
                        position => {
                            map.flyTo([position.coords.latitude, position.coords.longitude], 15);
                            hideMapLoading();
                        },
                        error => {
                            alert('Impossible d\'obtenir votre position');
                            hideMapLoading();
                        }
                    );
                } else {
                    alert('Géolocalisation non supportée');
                }
            });
            
            // Toggle sidebar
            document.getElementById('toggleSidebarBtn')?.addEventListener('click', () => {
                document.getElementById('mapSidebar').classList.toggle('active');
            });
            
            document.getElementById('closeSidebarBtn')?.addEventListener('click', () => {
                document.getElementById('mapSidebar').classList.remove('active');
            });
            
            // Filtres
            document.getElementById('applyFilters')?.addEventListener('click', applyFilters);
            document.getElementById('resetFilters')?.addEventListener('click', resetFilters);
            
            // Recherche avec debounce
            let searchTimeout;
            document.getElementById('searchInput')?.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(applyFilters, 500);
            });

            // Initialisation
            initMap();
        });
    </script>

    <style>
        /* ==================== STYLES DE LA CARTE ==================== */
        .map-wrapper {
            position: relative;
            width: 100%;
            height: 600px;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        
        .map-container-modern {
            position: relative;
            width: 100%;
            height: 100%;
        }
        
        .map-canvas {
            width: 100%;
            height: 100%;
            z-index: 1;
        }
        
        /* Boutons de contrôle */
        .map-recenter-btn, .map-location-btn {
            position: absolute;
            right: 20px;
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: white;
            border: none;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            cursor: pointer;
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            color: var(--primary-color);
            transition: all 0.3s ease;
        }
        
        .map-recenter-btn {
            bottom: 80px;
        }
        
        .map-location-btn {
            bottom: 20px;
        }
        
        .map-recenter-btn:hover, .map-location-btn:hover {
            background: var(--primary-color);
            color: white;
            transform: scale(1.1);
        }
        
        /* Loading */
        .map-loading {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255,255,255,0.8);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 2000;
        }
        
        /* Sidebar */
        .map-sidebar {
            position: absolute;
            top: 0;
            left: 0;
            width: 320px;
            height: 100%;
            background: white;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
            z-index: 1001;
            transform: translateX(0);
            transition: transform 0.3s ease;
            display: flex;
            flex-direction: column;
        }
        
        .map-sidebar.active {
            transform: translateX(0);
        }
        
        @media (max-width: 768px) {
            .map-sidebar {
                transform: translateX(-100%);
            }
        }
        
        .sidebar-header {
            padding: 20px;
            border-bottom: 1px solid #eef4f9;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .sidebar-header h4 {
            margin: 0;
            font-weight: 600;
            color: var(--dark);
        }
        
        .sidebar-body {
            padding: 20px;
            overflow-y: auto;
            flex: 1;
        }
        
        /* Filtres */
        .filter-group {
            margin-bottom: 20px;
        }
        
        .filter-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--dark);
            font-size: 0.9rem;
        }
        
        .filter-input, .filter-select {
            width: 100%;
            padding: 10px 12px;
            border: 2px solid #e0e9f0;
            border-radius: 8px;
            font-size: 0.9rem;
            transition: all 0.2s;
        }
        
        .filter-input:focus, .filter-select:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(27,79,107,0.1);
        }
        
        .filter-checkbox {
            margin-bottom: 8px;
            display: flex;
            align-items: center;
        }
        
        .filter-checkbox input {
            margin-right: 8px;
        }
        
        .filter-actions {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }
        
        .btn-filter-apply {
            flex: 1;
            padding: 10px;
            background: var(--primary-color);
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .btn-filter-apply:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
        }
        
        .btn-filter-reset {
            flex: 1;
            padding: 10px;
            background: #e9ecef;
            color: #495057;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .btn-filter-reset:hover {
            background: #dee2e6;
            transform: translateY(-2px);
        }
        
        /* Légende */
        .legend-section {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #eef4f9;
        }
        
        .legend-section h5 {
            font-weight: 600;
            margin-bottom: 15px;
        }
        
        .legend-items {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        
        .legend-item {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 5px 8px;
            border-radius: 6px;
            background: #f8fbfe;
        }
        
        .legend-color {
            width: 16px;
            height: 16px;
            border-radius: 4px;
        }
        
        .legend-label {
            flex: 1;
            font-size: 0.9rem;
        }
        
        .legend-count {
            background: white;
            padding: 2px 6px;
            border-radius: 12px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        
        /* Popup personnalisé */
        .map-popup {
            min-width: 250px;
            max-width: 300px;
        }
        
        .popup-header {
            padding: 12px 12px 8px;
            border-left: 3px solid;
        }
        
        .popup-header h4 {
            margin: 0 0 5px;
            font-size: 1rem;
            font-weight: 700;
        }
        
        .popup-category {
            font-size: 0.8rem;
            color: #6c757d;
        }
        
        .popup-media {
            position: relative;
            width: 100%;
            height: 150px;
            overflow: hidden;
        }
        
        .popup-image, .popup-video-thumb {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .popup-play-btn {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: rgba(255,0,0,0.8);
            border: none;
            color: white;
            font-size: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .popup-play-btn:hover {
            transform: translate(-50%, -50%) scale(1.1);
        }
        
        .popup-description {
            padding: 10px 12px;
            font-size: 0.85rem;
            color: #4a5a6a;
            max-height: 100px;
            overflow-y: auto;
        }
        
        .popup-address {
            padding: 0 12px 10px;
            font-size: 0.8rem;
            color: #6c757d;
        }
        
        .popup-actions {
            display: flex;
            gap: 8px;
            padding: 10px 12px 12px;
            border-top: 1px solid #eef4f9;
        }
        
        .popup-btn {
            flex: 1;
            padding: 6px 8px;
            border: none;
            border-radius: 6px;
            font-size: 0.8rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            text-align: center;
        }
        
        .popup-btn-details {
            background: var(--primary-color);
            color: white;
        }
        
        .popup-btn-details:hover {
            background: var(--primary-dark);
        }
        
        .popup-btn-directions {
            background: #e9ecef;
            color: #495057;
        }
        
        .popup-btn-directions:hover {
            background: #dee2e6;
        }
        
        /* Marqueurs personnalisés */
        .custom-marker {
            background: transparent;
            border: none;
        }
        
        .marker-icon {
            width: 30px;
            height: 30px;
            border-radius: 50% 50% 50% 0;
            transform: rotate(-45deg);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 14px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
            transition: all 0.3s ease;
        }
        
        .marker-icon i {
            transform: rotate(45deg);
        }
        
        .marker-icon:hover {
            transform: rotate(-45deg) scale(1.2);
        }
        
        /* Liste des points */
        .points-list-section {
            background: white;
            border-radius: 20px;
            padding: 20px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        }
        
        .points-list-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        
        .points-list-header h3 {
            margin: 0;
            font-size: 1.2rem;
            font-weight: 600;
        }
        
        .points-count {
            background: var(--primary-color);
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
        }
        
        .points-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 15px;
        }
        
        .point-card {
            display: flex;
            gap: 12px;
            padding: 12px;
            border: 2px solid #eef4f9;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .point-card:hover {
            border-color: var(--primary-color);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(27,79,107,0.1);
        }
        
        .point-card-media {
            width: 80px;
            height: 80px;
            border-radius: 8px;
            overflow: hidden;
            flex-shrink: 0;
        }
        
        .point-card-media img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .point-card-placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
        }
        
        .point-card-content {
            flex: 1;
        }
        
        .point-card-content h5 {
            margin: 0 0 4px;
            font-size: 1rem;
            font-weight: 600;
        }
        
        .point-card-category {
            font-size: 0.8rem;
            font-weight: 500;
        }
        
        .point-card-content p {
            margin: 5px 0 0;
            font-size: 0.8rem;
            color: #6c757d;
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
        }
        
        .more-card {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100px;
        }
        
        .more-card i {
            font-size: 2rem;
            color: var(--primary-color);
            margin-bottom: 10px;
        }
        
        .empty-points {
            text-align: center;
            padding: 40px;
            color: #6c757d;
        }
        
        .empty-points i {
            font-size: 3rem;
            margin-bottom: 15px;
            color: #cbd5e1;
        }
        
        /* Vidéo modal */
        .video-container {
            position: relative;
            padding-bottom: 56.25%;
            height: 0;
            overflow: hidden;
        }
        
        .video-container iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .map-wrapper {
                height: 500px;
            }
            
            .points-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endsection