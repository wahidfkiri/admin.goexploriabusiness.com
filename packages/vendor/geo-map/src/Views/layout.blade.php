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
    
  <link rel="stylesheet" href="{{ asset('front/css/style.css') }}">

    <!-- Leaflet CSS seulement -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    
    <link rel="stylesheet" href="{{ asset('vendor/geo-map/css/map.css') }}">
    
    <style>
        #map { 
            height: 100vh; 
            width: 100%;
        }
        
        /* Custom locate button */
        .leaflet-control-locate-custom {
            background: white;
            border-radius: 4px;
            padding: 3px;
        }
        
        .leaflet-control-locate-custom a {
            color: #333;
            text-decoration: none;
            font-size: 18px;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
</head>
<body>
    @yield('content')
    
     <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Leaflet JS seulement -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    
    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    
    <!-- Axios -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    
    <script>
        class InteractiveMap {
    constructor() {
        this.map = null;
        this.markers = {};
        this.currentLocation = null;
        this.places = [];
        this.categories = [];
        this.selectedCategory = 'all';
        this.radius = 20;
        this.swiper = null;
        this.locateControl = null;
        
        this.init();
    }
    
    async init() {
        // Initialiser la carte
        this.initMap();
        
        // Attendre un peu que la carte soit prête
        setTimeout(async () => {
            // Charger les catégories
            await this.loadCategories();
            
            // Charger les lieux
            await this.loadPlaces();
            
            // Écouter les événements
            this.setupEventListeners();
            
            console.log('Carte interactive initialisée avec succès');
        }, 100);
    }
    
    initMap() {
        try {
            // Vérifier que Leaflet est chargé
            if (typeof L === 'undefined') {
                throw new Error('Leaflet n\'est pas chargé. Vérifiez le CDN.');
            }
            
            // Créer la carte avec OpenStreetMap
            this.map = L.map('map').setView([48.8566, 2.3522], 12);
            
            // Ajouter les tuiles OpenStreetMap
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributeurs',
                maxZoom: 19,
                detectRetina: true
            }).addTo(this.map);
            
            // Vérifier que LocateControl est disponible
            if (typeof L.control.locate === 'function') {
                // Ajouter le contrôle de localisation
                this.locateControl = L.control.locate({
                    position: 'topright',
                    strings: {
                        title: "Montrer ma position",
                        popup: "Vous êtes dans un rayon de {distance} {unit} de ce point",
                        outsideMapBoundsMsg: "Vous semblez être en dehors des limites de la carte"
                    },
                    locateOptions: {
                        enableHighAccuracy: true,
                        maxZoom: 16,
                        watch: false
                    },
                    icon: 'fas fa-location-arrow',
                    iconLoading: 'fas fa-spinner fa-spin',
                    flyTo: true,
                    cacheLocation: true,
                    onLocationError: (err) => {
                        console.warn('Erreur de localisation:', err.message);
                        alert('Impossible de vous localiser. Vérifiez les autorisations de géolocalisation.');
                    },
                    onLocationOutsideMapBounds: (context) => {
                        alert("Vous êtes en dehors des limites de la carte actuelle.");
                    }
                }).addTo(this.map);
                
                console.log('Contrôle de localisation ajouté avec succès');
            } else {
                console.warn('L.control.locate n\'est pas disponible. Utilisation de la géolocalisation native.');
                this.addFallbackLocateControl();
            }
            
            // Ajouter un contrôle d'échelle
            L.control.scale({ imperial: false }).addTo(this.map);
            
        } catch (error) {
            console.error('Erreur lors de l\'initialisation de la carte:', error);
            this.showMapError();
        }
    }
    
    addFallbackLocateControl() {
        // Créer un contrôle de localisation de secours
        const LocateControl = L.Control.extend({
            options: {
                position: 'topright'
            },
            
            onAdd: function(map) {
                const container = L.DomUtil.create('div', 'leaflet-bar leaflet-control leaflet-control-custom');
                
                const button = L.DomUtil.create('a', '', container);
                button.href = '#';
                button.title = 'Localiser ma position';
                button.innerHTML = '<i class="fas fa-location-arrow"></i>';
                button.style.cssText = `
                    width: 30px;
                    height: 30px;
                    line-height: 30px;
                    text-align: center;
                    display: block;
                    background: white;
                    border-radius: 4px;
                    color: #333;
                    text-decoration: none;
                `;
                
                L.DomEvent
                    .on(button, 'click', L.DomEvent.stopPropagation)
                    .on(button, 'click', L.DomEvent.preventDefault)
                    .on(button, 'click', function() {
                        map.fire('locaterequest');
                    });
                
                return container;
            }
        });
        
        this.map.addControl(new LocateControl());
        
        // Gérer la localisation manuellement
        this.map.on('locaterequest', () => {
            this.locateUser();
        });
    }
    
    showMapError() {
        const mapDiv = document.getElementById('map');
        if (mapDiv) {
            mapDiv.innerHTML = `
                <div style="display: flex; justify-content: center; align-items: center; height: 100%; background: #f8f9fa; color: #dc3545; padding: 20px; text-align: center;">
                    <div>
                        <h3>Erreur de chargement de la carte</h3>
                        <p>Impossible de charger la carte. Veuillez vérifier :</p>
                        <ul style="text-align: left; display: inline-block; margin: 10px auto;">
                            <li>Votre connexion Internet</li>
                            <li>Les CDN Leaflet dans le code source</li>
                            <li>La console du navigateur pour plus de détails</li>
                        </ul>
                        <button onclick="location.reload()" style="margin-top: 20px; padding: 10px 20px; background: #dc3545; color: white; border: none; border-radius: 5px; cursor: pointer;">
                            Réessayer
                        </button>
                    </div>
                </div>
            `;
        }
    }
    
    async loadCategories() {
        try {
            const response = await axios.get('/api/categories');
            this.categories = response.data;
            this.populateCategoryFilter();
        } catch (error) {
            console.error('Erreur lors du chargement des catégories:', error);
            // Utiliser des catégories par défaut
            this.categories = ['restaurant', 'hotel', 'museum', 'park', 'shopping', 'monument'];
            this.populateCategoryFilter();
        }
    }
    
    populateCategoryFilter() {
        const filter = document.getElementById('category-filter');
        if (!filter) return;
        
        // Garder "Toutes les catégories"
        filter.innerHTML = '<option value="all">Toutes les catégories</option>';
        
        // Ajouter les catégories
        this.categories.forEach(category => {
            const option = document.createElement('option');
            option.value = category;
            option.textContent = this.capitalizeFirstLetter(category);
            filter.appendChild(option);
        });
    }
    
    async loadPlaces() {
    try {
        const params = {
            category: this.selectedCategory === 'all' ? null : this.selectedCategory,
            radius: this.radius,
            lat: this.currentLocation?.lat || 48.8566,
            lng: this.currentLocation?.lng || 2.3522
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
        
        // CORRECTION : S'assurer que this.places est un tableau
        this.places = Array.isArray(response.data) ? response.data : [];
        
        // Si response.data est un objet avec une propriété 'data'
        if (response.data && response.data.data && Array.isArray(response.data.data)) {
            this.places = response.data.data;
        }
        
        // Si response.data est un objet avec une propriété 'places'
        if (response.data && response.data.places && Array.isArray(response.data.places)) {
            this.places = response.data.places;
        }
        
        console.log('Lieux chargés:', this.places); // Pour déboguer
        
        this.updatePlacesCount();
        this.renderPlacesList();
        this.addMarkersToMap();
        
    } catch (error) {
        console.error('Erreur lors du chargement des lieux:', error);
        this.showSamplePlaces(); // Afficher des exemples de secours
    }
}
    
    showSamplePlaces() {
        // Données de secours pour démo
        this.places = [
            {
                id: 1,
                name: 'Tour Eiffel',
                description: 'Monument emblématique de Paris',
                latitude: 48.8584,
                longitude: 2.2945,
                category: 'monument',
                images: ['https://images.unsplash.com/photo-1511739001486-6bfe10ce785f'],
                video_url: 'https://www.youtube.com/watch?v=z5dSx3GVNhk',
                address: 'Champ de Mars, Paris',
                phone: '+33 1 44 11 23 23',
                website: 'https://www.toureiffel.paris'
            },
            {
                id: 2,
                name: 'Louvre Museum',
                description: 'Musée d\'art le plus grand du monde',
                latitude: 48.8606,
                longitude: 2.3376,
                category: 'museum',
                images: ['https://images.unsplash.com/photo-1548013146-72479768bada'],
                video_url: 'https://www.youtube.com/watch?v=UdnOufKt8E4',
                address: 'Rue de Rivoli, Paris',
                phone: '+33 1 40 20 50 50',
                website: 'https://www.louvre.fr'
            }
        ];
        
        this.updatePlacesCount();
        this.renderPlacesList();
        this.addMarkersToMap();
        
        // Avertir l'utilisateur
        alert('Utilisation de données de démonstration. Vérifiez que votre API Laravel fonctionne.');
    }
    
    addMarkersToMap() {
        // Nettoyer les anciens marqueurs
        this.clearMarkers();
        
        // Ajouter les nouveaux marqueurs
        this.places.forEach(place => {
            this.createMarker(place);
        });
        
        // Ajuster la vue pour voir tous les marqueurs si nécessaire
        if (this.places.length > 0) {
            const bounds = this.getMarkersBounds();
            this.map.fitBounds(bounds, { padding: [50, 50] });
        }
    }
    
    clearMarkers() {
        Object.values(this.markers).forEach(marker => {
            if (marker && marker.remove) {
                marker.remove();
            }
        });
        this.markers = {};
    }
    
    createMarker(place) {
        // Créer une icône personnalisée
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
        
        // Créer le marqueur
        const marker = L.marker([place.latitude, place.longitude], { 
            icon: icon,
            title: place.name
        }).addTo(this.map);
        
        // Ajouter une popup
        marker.bindPopup(this.createPopupContent(place));
        
        // Événement de clic
        marker.on('click', () => {
            this.showPlaceModal(place);
        });
        
        // Sauvegarder le marqueur
        this.markers[place.id] = marker;
        
        return marker;
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
    
    createPopupContent(place) {
        const image = place.images && place.images.length > 0 
            ? `<img src="${place.images[0]}" alt="${place.name}" style="width:100%; height:100px; object-fit:cover; border-radius:5px 5px 0 0;">`
            : `<div style="width:100%; height:100px; background:#f0f0f0; display:flex; align-items:center; justify-content:center; border-radius:5px 5px 0 0;">
                 <i class="fas fa-image" style="font-size:24px; color:#ccc;"></i>
               </div>`;
        
        return `
            <div style="min-width:200px; max-width:300px;">
                ${image}
                <div style="padding:10px;">
                    <h4 style="margin:0 0 5px 0; color:#2d3748;">${place.name}</h4>
                    <div style="display:flex; align-items:center; margin-bottom:8px;">
                        <span style="background:${this.getCategoryColor(place.category)}; color:white; padding:2px 8px; border-radius:12px; font-size:12px;">
                            ${this.capitalizeFirstLetter(place.category)}
                        </span>
                    </div>
                    <p style="margin:0 0 10px 0; font-size:13px; color:#718096;">
                        ${place.description.substring(0, 80)}...
                    </p>
                    <button onclick="window.mapApp.showPlaceModal(${JSON.stringify(place).replace(/"/g, '&quot;')})" 
                            style="width:100%; padding:8px; background:#4299e1; color:white; border:none; border-radius:5px; cursor:pointer; font-size:12px;">
                        <i class="fas fa-info-circle"></i> Voir détails
                    </button>
                </div>
            </div>
        `;
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
                <div style="text-align:center; padding:20px; color:#718096;">
                    <i class="fas fa-map-marker-alt" style="font-size:48px; margin-bottom:10px;"></i>
                    <p>Aucun lieu trouvé</p>
                    <p style="font-size:12px;">Essayez de modifier vos filtres</p>
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
            : 'https://via.placeholder.com/100x100?text=No+Image';
        
        div.innerHTML = `
            <div class="place-image">
                <img src="${image}" alt="${place.name}" loading="lazy">
            </div>
            <div class="place-info">
                <h4 class="place-name">${place.name}</h4>
                <span class="place-category" style="background:${this.getCategoryColor(place.category)}">
                    ${this.capitalizeFirstLetter(place.category)}
                </span>
                <p class="place-description">${place.description.substring(0, 80)}...</p>
                <div class="place-actions">
                    <button class="btn view-details-btn" data-id="${place.id}">
                        <i class="fas fa-eye"></i> Voir détails
                    </button>
                    <button class="btn locate-btn" data-id="${place.id}" style="background:#38a169;">
                        <i class="fas fa-map-marker-alt"></i> Localiser
                    </button>
                </div>
            </div>
        `;
        
        // Événements
        div.addEventListener('click', (e) => {
            if (!e.target.closest('button')) {
                this.centerOnPlace(place);
            }
        });
        
        div.querySelector('.view-details-btn').addEventListener('click', (e) => {
            e.stopPropagation();
            this.showPlaceModal(place);
        });
        
        div.querySelector('.locate-btn').addEventListener('click', (e) => {
            e.stopPropagation();
            this.centerOnPlace(place);
        });
        
        return div;
    }
    
    centerOnPlace(place) {
        this.map.setView([place.latitude, place.longitude], 15);
        const marker = this.markers[place.id];
        if (marker) {
            marker.openPopup();
        }
    }
    
    showPlaceModal(place) {
        const modal = document.getElementById('place-modal');
        const modalContent = document.getElementById('modal-content');
        
        if (!modal || !modalContent) {
            console.error('Modal non trouvée');
            return;
        }
        
        // Créer le contenu de la modal
        const content = this.createModalContent(place);
        modalContent.innerHTML = content;
        
        // Afficher la modal
        modal.style.display = 'block';
        
        // Initialiser Swiper
        this.initSwiper();
        
        // Centrer sur le lieu
        this.centerOnPlace(place);
    }
    
    createModalContent(place) {
        const images = place.images && place.images.length > 0 
            ? place.images 
            : ['https://via.placeholder.com/800x400?text=No+Image'];
        
        const video = place.video_url ? this.getVideoEmbed(place.video_url) : '';
        
        return `
            <div class="place-modal-content">
                <div class="modal-header">
                    <h2 class="modal-title">${place.name}</h2>
                    <span class="modal-category" style="background:${this.getCategoryColor(place.category)}">
                        ${this.capitalizeFirstLetter(place.category)}
                    </span>
                </div>
                
                <div class="modal-body">
                    <!-- Slider d'images -->
                    <div class="image-slider swiper">
                        <div class="swiper-wrapper">
                            ${images.map(img => `
                                <div class="swiper-slide">
                                    <img src="${img}" alt="${place.name}">
                                </div>
                            `).join('')}
                        </div>
                        <div class="swiper-pagination"></div>
                        <div class="swiper-button-next"></div>
                        <div class="swiper-button-prev"></div>
                    </div>
                    
                    <!-- Vidéo -->
                    ${video ? `
                        <div class="video-container">
                            <h3><i class="fas fa-video"></i> Vidéo</h3>
                            ${video}
                        </div>
                    ` : ''}
                    
                    <!-- Informations -->
                    <div class="place-details">
                        <div class="detail-section">
                            <h3><i class="fas fa-info-circle"></i> Description</h3>
                            <p class="place-description">${place.description}</p>
                        </div>
                        
                        <div class="details-grid">
                            ${place.address ? `
                                <div class="detail-item">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <div>
                                        <strong>Adresse</strong>
                                        <p>${place.address}</p>
                                    </div>
                                </div>
                            ` : ''}
                            
                            ${place.phone ? `
                                <div class="detail-item">
                                    <i class="fas fa-phone"></i>
                                    <div>
                                        <strong>Téléphone</strong>
                                        <p><a href="tel:${place.phone}">${place.phone}</a></p>
                                    </div>
                                </div>
                            ` : ''}
                            
                            ${place.website ? `
                                <div class="detail-item">
                                    <i class="fas fa-globe"></i>
                                    <div>
                                        <strong>Site web</strong>
                                        <p><a href="${place.website}" target="_blank">${place.website}</a></p>
                                    </div>
                                </div>
                            ` : ''}
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button class="btn close-modal-btn">
                        <i class="fas fa-times"></i> Fermer
                    </button>
                    <button class="btn locate-on-map-btn" onclick="window.mapApp.centerOnPlace(${JSON.stringify(place)})">
                        <i class="fas fa-map-marker-alt"></i> Voir sur la carte
                    </button>
                </div>
            </div>
        `;
    }
    
    getVideoEmbed(videoUrl) {
        const videoId = this.extractYouTubeId(videoUrl);
        if (videoId) {
            return `
                <div class="video-embed">
                    <iframe src="https://www.youtube.com/embed/${videoId}" 
                            frameborder="0" 
                            allowfullscreen>
                    </iframe>
                </div>
            `;
        }
        return '';
    }
    
    initSwiper() {
        if (typeof Swiper === 'undefined') {
            console.warn('Swiper non chargé');
            return;
        }
        
        if (this.swiper) {
            this.swiper.destroy();
        }
        
        this.swiper = new Swiper('.swiper', {
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
            },
        });
    }
    
    setupEventListeners() {
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
        if (this.locateControl && this.locateControl.start) {
            // Utiliser le contrôle de localisation
            this.locateControl.start();
        } else {
            // Utiliser la géolocalisation native
            if (!navigator.geolocation) {
                alert('La géolocalisation n\'est pas supportée par votre navigateur.');
                return;
            }
            
            navigator.geolocation.getCurrentPosition(
                (position) => {
                    const { latitude, longitude } = position.coords;
                    this.currentLocation = { lat: latitude, lng: longitude };
                    this.map.setView([latitude, longitude], 15);
                    this.loadPlaces();
                },
                (error) => {
                    console.error('Erreur de géolocalisation:', error);
                    alert('Impossible de vous localiser. Vérifiez les autorisations.');
                },
                {
                    enableHighAccuracy: true,
                    timeout: 10000,
                    maximumAge: 0
                }
            );
        }
    }
    
    closeModal() {
        const modal = document.getElementById('place-modal');
        if (modal) {
            modal.style.display = 'none';
        }
    }
    
    updatePlacesCount() {
        const countEl = document.getElementById('places-count');
        if (countEl) {
            countEl.textContent = this.places.length;
            countEl.style.fontWeight = 'bold';
            countEl.style.color = '#4299e1';
        }
    }
    
    capitalizeFirstLetter(string) {
        if (!string) return '';
        return string.charAt(0).toUpperCase() + string.slice(1);
    }
    
    extractYouTubeId(url) {
        if (!url) return null;
        const patterns = [
            /(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)([^&?\/]+)/,
            /youtube\.com\/.*v=([^&?\/]+)/,
            /youtu\.be\/([^&?\/]+)/
        ];
        
        for (const pattern of patterns) {
            const match = url.match(pattern);
            if (match && match[1]) {
                return match[1];
            }
        }
        return null;
    }
}

// Initialisation
document.addEventListener('DOMContentLoaded', () => {
    try {
        window.mapApp = new InteractiveMap();
        console.log('Application carte interactive prête');
    } catch (error) {
        console.error('Erreur fatale:', error);
        document.body.innerHTML = `
            <div style="padding:50px; text-align:center; color:#dc3545;">
                <h1>Erreur de chargement</h1>
                <p>L'application n'a pas pu se charger correctement.</p>
                <button onclick="location.reload()" style="padding:10px 20px; background:#dc3545; color:white; border:none; border-radius:5px; cursor:pointer;">
                    Recharger la page
                </button>
            </div>
        `;
    }
});
    </script>
</body>
</html>