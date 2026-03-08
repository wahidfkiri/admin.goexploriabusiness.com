/**
 * Carte Interactive - Version Simplifiée
 * Sans dépendance à leaflet.locatecontrol
 */

class InteractiveMap {
    constructor() {
        this.map = null;
        this.markers = {};
        this.places = [];
        this.categories = [];
        this.selectedCategory = 'all';
        this.radius = 20;
        this.swiper = null;
        this.userMarker = null;
        
        this.init();
    }
    
    async init() {
        try {
            // Initialiser la carte
            this.initMap();
            
            // Charger les données
            await this.loadCategories();
            await this.loadPlaces();
            
            // Configurer les événements
            this.setupEventListeners();
            
            console.log('Carte initialisée avec succès!');
        } catch (error) {
            console.error('Erreur d\'initialisation:', error);
            this.showError('Erreur d\'initialisation de la carte');
        }
    }
    
    initMap() {
        try {
            // Vérifier que Leaflet est disponible
            if (typeof L === 'undefined') {
                throw new Error('Leaflet non chargé');
            }
            
            // Créer la carte
            this.map = L.map('map').setView([48.8566, 2.3522], 12);
            
            // Ajouter les tuiles OpenStreetMap
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap',
                maxZoom: 19
            }).addTo(this.map);
            
            // Ajouter un contrôle d'échelle
            L.control.scale().addTo(this.map);
            
            // Ajouter notre bouton de localisation personnalisé
            this.addCustomLocateButton();
            
        } catch (error) {
            console.error('Erreur création carte:', error);
            this.showError('Impossible de charger la carte');
        }
    }
    
    addCustomLocateButton() {
        // Créer un contrôle personnalisé
        const LocateControl = L.Control.extend({
            options: {
                position: 'topright'
            },
            
            onAdd: function(map) {
                const container = L.DomUtil.create('div', 'leaflet-control leaflet-bar leaflet-control-locate-custom');
                
                const link = L.DomUtil.create('a', '', container);
                link.href = '#';
                link.title = 'Me localiser';
                link.innerHTML = '<i class="fas fa-location-arrow"></i>';
                
                // Empêcher les événements de la carte
                L.DomEvent
                    .on(link, 'click', L.DomEvent.stopPropagation)
                    .on(link, 'click', L.DomEvent.preventDefault)
                    .on(link, 'click', function() {
                        map.fire('locate');
                    });
                
                return container;
            }
        });
        
        // Ajouter le contrôle
        this.map.addControl(new LocateControl());
        
        // Écouter l'événement de localisation
        this.map.on('locate', () => {
            this.locateUser();
        });
    }
    
    async loadCategories() {
        try {
            const response = await axios.get('/api/categories');
            this.categories = response.data;
            this.updateCategoryFilter();
        } catch (error) {
            console.warn('Chargement catégories échoué, utilisation de valeurs par défaut');
            this.categories = ['restaurant', 'hotel', 'museum', 'park', 'shopping', 'monument'];
            this.updateCategoryFilter();
        }
    }
    
    updateCategoryFilter() {
        const filter = document.getElementById('category-filter');
        if (!filter) return;
        
        filter.innerHTML = '<option value="all">Toutes catégories</option>';
        
        this.categories.forEach(category => {
            const option = document.createElement('option');
            option.value = category;
            option.textContent = this.formatCategoryName(category);
            filter.appendChild(option);
        });
    }
    
    async loadPlaces() {
        try {
            // Paramètres de requête
            const params = {};
            
            if (this.selectedCategory !== 'all') {
                params.category = this.selectedCategory;
            }
            
            if (this.radius) {
                params.radius = this.radius;
            }
            
            // Si position utilisateur disponible
            if (this.userLocation) {
                params.lat = this.userLocation.lat;
                params.lng = this.userLocation.lng;
            }
            
            const response = await axios.get('/api/places', { params });
            this.places = response.data;
            
            this.updateUI();
            
        } catch (error) {
            console.error('Erreur chargement lieux:', error);
            this.loadSampleData();
        }
    }
    
    loadSampleData() {
        // Données de démonstration
        this.places = [
            {
                id: 1,
                name: 'Tour Eiffel',
                description: 'La Tour Eiffel est une tour de fer puddlé de 330 mètres de hauteur située à Paris, à l\'extrémité nord-ouest du parc du Champ-de-Mars en bordure de la Seine.',
                latitude: 48.8584,
                longitude: 2.2945,
                category: 'monument',
                images: ['https://images.unsplash.com/photo-1511739001486-6bfe10ce785f?w=800&auto=format&fit=crop'],
                video_url: 'https://www.youtube.com/watch?v=z5dSx3GVNhk',
                address: 'Champ de Mars, 5 Avenue Anatole France, 75007 Paris',
                phone: '+33 1 44 11 23 23',
                website: 'https://www.toureiffel.paris'
            },
            {
                id: 2,
                name: 'Louvre Museum',
                description: 'Le musée du Louvre est le plus grand musée d\'art et d\'antiquités au monde. Situé au centre de Paris, il est installé dans le palais du Louvre.',
                latitude: 48.8606,
                longitude: 2.3376,
                category: 'museum',
                images: ['https://images.unsplash.com/photo-1548013146-72479768bada?w-800&auto=format&fit=crop'],
                video_url: 'https://www.youtube.com/watch?v=UdnOufKt8E4',
                address: 'Rue de Rivoli, 75001 Paris',
                phone: '+33 1 40 20 50 50',
                website: 'https://www.louvre.fr'
            },
            {
                id: 3,
                name: 'Jardin du Luxembourg',
                description: 'Le jardin du Luxembourg est un jardin ouvert au public, situé dans le 6e arrondissement de Paris. Créé en 1612 à la demande de Marie de Médicis.',
                latitude: 48.8462,
                longitude: 2.3372,
                category: 'park',
                images: ['https://images.unsplash.com/photo-1521341057461-6eb5f40b07ab?w=800&auto=format&fit=crop'],
                video_url: null,
                address: '75006 Paris',
                phone: '+33 1 42 34 23 89',
                website: 'https://www.senat.fr/visite/jardin'
            }
        ];
        
        this.updateUI();
        
        // Avertir l'utilisateur
        setTimeout(() => {
            const alertEl = document.createElement('div');
            alertEl.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                background: #f8d7da;
                color: #721c24;
                padding: 15px;
                border-radius: 5px;
                z-index: 1000;
                max-width: 300px;
                box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            `;
            alertEl.innerHTML = `
                <strong>Mode démo</strong>
                <p style="margin: 5px 0 0 0; font-size: 12px;">
                    Données de démonstration. Vérifiez votre API Laravel.
                </p>
            `;
            document.body.appendChild(alertEl);
            
            setTimeout(() => alertEl.remove(), 5000);
        }, 1000);
    }
    
    updateUI() {
        this.updatePlacesCount();
        this.renderPlacesList();
        this.updateMapMarkers();
    }
    
    updatePlacesCount() {
        const countEl = document.getElementById('places-count');
        if (countEl) {
            countEl.textContent = this.places.length;
        }
    }
    
    renderPlacesList() {
        const container = document.getElementById('places-list');
        if (!container) return;
        
        container.innerHTML = '';
        
        if (this.places.length === 0) {
            container.innerHTML = `
                <div class="no-places">
                    <i class="fas fa-map-marker-alt"></i>
                    <p>Aucun lieu trouvé</p>
                </div>
            `;
            return;
        }
        
        this.places.forEach(place => {
            const placeEl = this.createPlaceCard(place);
            container.appendChild(placeEl);
        });
    }
    
    createPlaceCard(place) {
        const div = document.createElement('div');
        div.className = 'place-card';
        div.dataset.id = place.id;
        
        const imageUrl = place.images && place.images.length > 0 
            ? place.images[0] 
            : 'https://via.placeholder.com/300x200?text=No+Image';
        
        const categoryColor = this.getCategoryColor(place.category);
        const categoryName = this.formatCategoryName(place.category);
        
        div.innerHTML = `
            <div class="place-card-image">
                <img src="${imageUrl}" alt="${place.name}" loading="lazy">
                <div class="place-card-category" style="background: ${categoryColor}">
                    ${categoryName}
                </div>
            </div>
            <div class="place-card-content">
                <h3>${place.name}</h3>
                <p class="place-card-description">${place.description.substring(0, 100)}...</p>
                <div class="place-card-actions">
                    <button class="btn btn-view" data-id="${place.id}">
                        <i class="fas fa-eye"></i> Voir
                    </button>
                    <button class="btn btn-locate" data-id="${place.id}">
                        <i class="fas fa-map-marker-alt"></i>
                    </button>
                </div>
            </div>
        `;
        
        // Événements
        div.querySelector('.btn-view').addEventListener('click', (e) => {
            e.stopPropagation();
            this.showPlaceDetails(place);
        });
        
        div.querySelector('.btn-locate').addEventListener('click', (e) => {
            e.stopPropagation();
            this.focusOnPlace(place);
        });
        
        div.addEventListener('click', () => {
            this.showPlaceDetails(place);
        });
        
        return div;
    }
    
    updateMapMarkers() {
        // Supprimer anciens marqueurs
        this.clearMarkers();
        
        // Ajouter nouveaux marqueurs
        this.places.forEach(place => {
            this.addMarker(place);
        });
        
        // Ajuster la vue si nécessaire
        if (this.places.length > 0) {
            this.fitMapToMarkers();
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
    
    addMarker(place) {
        // Créer icône personnalisée
        const icon = L.divIcon({
            className: 'custom-marker',
            html: `
                <div style="
                    width: 40px;
                    height: 40px;
                    background: ${this.getCategoryColor(place.category)};
                    border-radius: 50%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    color: white;
                    font-size: 16px;
                    border: 3px solid white;
                    box-shadow: 0 2px 5px rgba(0,0,0,0.3);
                ">
                    <i class="${this.getCategoryIcon(place.category)}"></i>
                </div>
            `,
            iconSize: [40, 40],
            iconAnchor: [20, 40]
        });
        
        // Créer marqueur
        const marker = L.marker([place.latitude, place.longitude], { 
            icon: icon,
            title: place.name 
        }).addTo(this.map);
        
        // Popup
        marker.bindPopup(`
            <div style="min-width: 200px;">
                <h4 style="margin: 0 0 5px 0;">${place.name}</h4>
                <p style="margin: 0 0 10px 0; color: #666; font-size: 14px;">
                    ${place.description.substring(0, 80)}...
                </p>
                <button onclick="window.mapApp.showPlaceDetails(${JSON.stringify(place)})" 
                        style="width: 100%; padding: 8px; background: #4299e1; color: white; border: none; border-radius: 4px; cursor: pointer;">
                    <i class="fas fa-info-circle"></i> Détails
                </button>
            </div>
        `);
        
        // Sauvegarder
        this.markers[place.id] = marker;
        
        // Événement clic
        marker.on('click', () => {
            this.showPlaceDetails(place);
        });
    }
    
    fitMapToMarkers() {
        if (this.places.length === 0) return;
        
        const bounds = L.latLngBounds();
        this.places.forEach(place => {
            bounds.extend([place.latitude, place.longitude]);
        });
        
        this.map.fitBounds(bounds, { padding: [50, 50] });
    }
    
    focusOnPlace(place) {
        this.map.setView([place.latitude, place.longitude], 15);
        
        const marker = this.markers[place.id];
        if (marker) {
            marker.openPopup();
        }
    }
    
    showPlaceDetails(place) {
        const modal = document.getElementById('place-modal');
        const content = document.getElementById('modal-content');
        
        if (!modal || !content) {
            console.error('Modal non trouvée');
            return;
        }
        
        // Construire contenu
        content.innerHTML = this.buildModalContent(place);
        
        // Afficher modal
        modal.style.display = 'block';
        
        // Initialiser Swiper si images
        this.initImageSlider();
        
        // Centrer sur le lieu
        this.focusOnPlace(place);
    }
    
    buildModalContent(place) {
        const images = place.images && place.images.length > 0 
            ? place.images 
            : ['https://via.placeholder.com/800x400?text=No+Image'];
        
        const videoEmbed = place.video_url ? this.getVideoEmbedCode(place.video_url) : '';
        
        return `
            <div class="modal-details">
                <div class="modal-header">
                    <h2>${place.name}</h2>
                    <span class="modal-category" style="background: ${this.getCategoryColor(place.category)}">
                        ${this.formatCategoryName(place.category)}
                    </span>
                </div>
                
                <div class="modal-body">
                    <!-- Slider images -->
                    <div class="image-slider">
                        <div class="swiper">
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
                    </div>
                    
                    <!-- Description -->
                    <div class="description-section">
                        <h3><i class="fas fa-info-circle"></i> Description</h3>
                        <p>${place.description}</p>
                    </div>
                    
                    <!-- Informations -->
                    <div class="info-grid">
                        ${place.address ? `
                            <div class="info-item">
                                <i class="fas fa-map-marker-alt"></i>
                                <div>
                                    <strong>Adresse</strong>
                                    <p>${place.address}</p>
                                </div>
                            </div>
                        ` : ''}
                        
                        ${place.phone ? `
                            <div class="info-item">
                                <i class="fas fa-phone"></i>
                                <div>
                                    <strong>Téléphone</strong>
                                    <p><a href="tel:${place.phone}">${place.phone}</a></p>
                                </div>
                            </div>
                        ` : ''}
                        
                        ${place.website ? `
                            <div class="info-item">
                                <i class="fas fa-globe"></i>
                                <div>
                                    <strong>Site web</strong>
                                    <p><a href="${place.website}" target="_blank">${place.website}</a></p>
                                </div>
                            </div>
                        ` : ''}
                    </div>
                    
                    <!-- Vidéo -->
                    ${videoEmbed ? `
                        <div class="video-section">
                            <h3><i class="fas fa-video"></i> Vidéo</h3>
                            ${videoEmbed}
                        </div>
                    ` : ''}
                </div>
                
                <div class="modal-footer">
                    <button class="btn btn-secondary" onclick="window.mapApp.closeModal()">
                        <i class="fas fa-times"></i> Fermer
                    </button>
                    <button class="btn btn-primary" onclick="window.mapApp.focusOnPlace(${JSON.stringify(place)})">
                        <i class="fas fa-map-marker-alt"></i> Voir sur carte
                    </button>
                </div>
            </div>
        `;
    }
    
    getVideoEmbedCode(url) {
        const videoId = this.extractVideoId(url);
        if (videoId) {
            return `
                <div class="video-container">
                    <iframe src="https://www.youtube.com/embed/${videoId}" 
                            frameborder="0" 
                            allowfullscreen>
                    </iframe>
                </div>
            `;
        }
        return '';
    }
    
    extractVideoId(url) {
        if (!url) return null;
        
        const patterns = [
            /youtube\.com.*[?&]v=([^&]+)/,
            /youtu\.be\/([^?]+)/,
            /youtube\.com\/embed\/([^?]+)/
        ];
        
        for (const pattern of patterns) {
            const match = url.match(pattern);
            if (match && match[1]) {
                return match[1];
            }
        }
        
        return null;
    }
    
    initImageSlider() {
        if (typeof Swiper === 'undefined') return;
        
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
        });
    }
    
    locateUser() {
        if (!navigator.geolocation) {
            alert('Géolocalisation non supportée par votre navigateur');
            return;
        }
        
        // Afficher indicateur de chargement
        const locateBtn = document.querySelector('.leaflet-control-locate-custom a');
        if (locateBtn) {
            locateBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        }
        
        navigator.geolocation.getCurrentPosition(
            (position) => {
                const { latitude, longitude } = position.coords;
                
                // Sauvegarder position
                this.userLocation = { lat: latitude, lng: longitude };
                
                // Ajouter marqueur utilisateur
                this.addUserMarker(latitude, longitude);
                
                // Centrer carte
                this.map.setView([latitude, longitude], 14);
                
                // Recharger lieux
                this.loadPlaces();
                
                // Restaurer icône
                if (locateBtn) {
                    locateBtn.innerHTML = '<i class="fas fa-location-arrow"></i>';
                }
                
            },
            (error) => {
                console.error('Erreur géolocalisation:', error);
                
                let message = 'Erreur de localisation';
                switch(error.code) {
                    case error.PERMISSION_DENIED:
                        message = 'Permission refusée. Activez la géolocalisation.';
                        break;
                    case error.POSITION_UNAVAILABLE:
                        message = 'Position indisponible.';
                        break;
                    case error.TIMEOUT:
                        message = 'Délai dépassé. Réessayez.';
                        break;
                }
                
                alert(message);
                
                // Restaurer icône
                if (locateBtn) {
                    locateBtn.innerHTML = '<i class="fas fa-location-arrow"></i>';
                }
            },
            {
                enableHighAccuracy: true,
                timeout: 10000,
                maximumAge: 0
            }
        );
    }
    
    addUserMarker(lat, lng) {
        // Supprimer ancien marqueur
        if (this.userMarker) {
            this.userMarker.remove();
        }
        
        // Créer nouvel marqueur
        const icon = L.divIcon({
            className: 'user-marker',
            html: `
                <div style="
                    width: 30px;
                    height: 30px;
                    background: #4CAF50;
                    border-radius: 50%;
                    border: 3px solid white;
                    box-shadow: 0 2px 5px rgba(0,0,0,0.3);
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    color: white;
                    font-size: 12px;
                ">
                    <i class="fas fa-user"></i>
                </div>
            `,
            iconSize: [30, 30],
            iconAnchor: [15, 30]
        });
        
        this.userMarker = L.marker([lat, lng], { 
            icon: icon,
            zIndexOffset: 1000 
        }).addTo(this.map);
        
        this.userMarker.bindPopup('<b>Votre position</b>').openPopup();
    }
    
    setupEventListeners() {
        // Filtre catégorie
        const categoryFilter = document.getElementById('category-filter');
        if (categoryFilter) {
            categoryFilter.addEventListener('change', (e) => {
                this.selectedCategory = e.target.value;
                this.loadPlaces();
            });
        }
        
        // Filtre rayon
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
        
        // Fermeture modal
        const closeBtn = document.querySelector('.close-modal');
        if (closeBtn) {
            closeBtn.addEventListener('click', () => {
                this.closeModal();
            });
        }
        
        // Fermer en cliquant dehors
        window.addEventListener('click', (e) => {
            const modal = document.getElementById('place-modal');
            if (e.target === modal) {
                this.closeModal();
            }
        });
        
        // Touche Echap
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                this.closeModal();
            }
        });
    }
    
    closeModal() {
        const modal = document.getElementById('place-modal');
        if (modal) {
            modal.style.display = 'none';
        }
    }
    
    showError(message) {
        const mapDiv = document.getElementById('map');
        if (mapDiv) {
            mapDiv.innerHTML = `
                <div style="
                    display: flex;
                    flex-direction: column;
                    justify-content: center;
                    align-items: center;
                    height: 100%;
                    background: #f8f9fa;
                    color: #dc3545;
                    padding: 20px;
                    text-align: center;
                ">
                    <i class="fas fa-exclamation-triangle" style="font-size: 48px; margin-bottom: 20px;"></i>
                    <h3 style="margin-bottom: 10px;">Erreur</h3>
                    <p style="margin-bottom: 20px;">${message}</p>
                    <button onclick="location.reload()" style="
                        padding: 10px 20px;
                        background: #dc3545;
                        color: white;
                        border: none;
                        border-radius: 5px;
                        cursor: pointer;
                    ">
                        Recharger
                    </button>
                </div>
            `;
        }
    }
    
    // Utilitaires
    formatCategoryName(category) {
        if (!category) return '';
        return category.charAt(0).toUpperCase() + category.slice(1);
    }
    
    getCategoryColor(category) {
        const colors = {
            restaurant: '#e53e3e',
            hotel: '#38a169',
            museum: '#805ad5',
            park: '#d69e2e',
            shopping: '#3182ce',
            monument: '#dd6b20'
        };
        return colors[category] || '#718096';
    }
    
    getCategoryIcon(category) {
        const icons = {
            restaurant: 'fas fa-utensils',
            hotel: 'fas fa-hotel',
            museum: 'fas fa-landmark',
            park: 'fas fa-tree',
            shopping: 'fas fa-shopping-bag',
            monument: 'fas fa-monument'
        };
        return icons[category] || 'fas fa-map-marker-alt';
    }
}

// Initialisation sécurisée
document.addEventListener('DOMContentLoaded', () => {
    try {
        window.mapApp = new InteractiveMap();
    } catch (error) {
        console.error('Erreur fatale:', error);
        alert('Une erreur est survenue lors du chargement de l\'application.');
    }
});