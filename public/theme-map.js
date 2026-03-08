    class InteractiveMap {
        constructor() {
            this.map = null;
            this.markers = {};
            this.currentLocation = null;
            this.places = [];
            this.selectedCategory = 'all';
            this.selectedProvince = '';
            this.userMarker = null;
            this.activePlace = null;
            this.hoverTimeout = null;
            this.popupTimeout = null;
            
            // Toutes les provinces et territoires du Canada
            this.provinces = [
        // Canada (provinces existantes)
        { code: 'ab', name: 'Alberta', lat: 53.9333, lng: -116.5765 },
        { code: 'bc', name: 'Colombie-Britannique', lat: 53.7267, lng: -127.6476 },
        { code: 'mb', name: 'Manitoba', lat: 53.7609, lng: -98.8139 },
        { code: 'nb', name: 'Nouveau-Brunswick', lat: 46.5653, lng: -66.4619 },
        { code: 'nl', name: 'Terre-Neuve-et-Labrador', lat: 53.1355, lng: -57.6604 },
        { code: 'ns', name: 'Nouvelle-Écosse', lat: 44.6820, lng: -63.7443 },
        { code: 'nt', name: 'Territoires du Nord-Ouest', lat: 64.8255, lng: -124.8457 },
        { code: 'nu', name: 'Nunavut', lat: 70.2998, lng: -83.1076 },
        { code: 'on', name: 'Ontario', lat: 51.2538, lng: -85.3232 },
        { code: 'pe', name: 'Île-du-Prince-Édouard', lat: 46.5107, lng: -63.4168 },
        { code: 'qc', name: 'Québec', lat: 52.9399, lng: -73.5491 },
        { code: 'sk', name: 'Saskatchewan', lat: 52.9399, lng: -106.4509 },
        { code: 'yt', name: 'Yukon', lat: 64.2823, lng: -135.0000 },
        
        // États-Unis
        { code: 'ny', name: 'New York', lat: 40.7128, lng: -74.0060 },
        { code: 'fl', name: 'Floride', lat: 27.9944, lng: -81.7603 },
        { code: 'ca', name: 'Californie', lat: 36.7783, lng: -119.4179 },
        { code: 'tx', name: 'Texas', lat: 31.9686, lng: -99.9018 },
        
        // Mexique
        { code: 'mx', name: 'Mexique', lat: 23.6345, lng: -102.5528 },
        
        // Amérique Centrale
        { code: 'cr', name: 'Costa Rica', lat: 9.7489, lng: -83.7534 },
        { code: 'pa', name: 'Panama', lat: 8.5379, lng: -80.7821 },
        { code: 'gt', name: 'Guatemala', lat: 15.7835, lng: -90.2308 },
        
        // Caraïbes
        { code: 'cu', name: 'Cuba', lat: 21.5218, lng: -77.7812 },
        { code: 'do', name: 'République Dominicaine', lat: 18.7357, lng: -70.1627 },
        { code: 'pr', name: 'Porto Rico', lat: 18.2208, lng: -66.5901 },
        
        // Amérique du Sud
        { code: 'br', name: 'Brésil', lat: -14.2350, lng: -51.9253 },
        { code: 'ar', name: 'Argentine', lat: -38.4161, lng: -63.6167 },
        { code: 'cl', name: 'Chili', lat: -35.6751, lng: -71.5430 },
        { code: 'co', name: 'Colombie', lat: 4.5709, lng: -74.2973 },
        { code: 'pe', name: 'Pérou', lat: -9.1900, lng: -75.0152 },
        { code: 've', name: 'Venezuela', lat: 6.4238, lng: -66.5897 },
        { code: 'ec', name: 'Équateur', lat: -1.8312, lng: -78.1834 },
        { code: 'bo', name: 'Bolivie', lat: -16.2902, lng: -63.5887 },
        { code: 'py', name: 'Paraguay', lat: -23.4425, lng: -58.4438 },
        { code: 'uy', name: 'Uruguay', lat: -32.5228, lng: -55.7658 },
        { code: 'gy', name: 'Guyana', lat: 4.8604, lng: -58.9302 },
        { code: 'sr', name: 'Suriname', lat: 3.9193, lng: -56.0278 },
        { code: 'gf', name: 'Guyane Française', lat: 3.9339, lng: -53.1258 }
    ];
            // Catégories immobilières
            this.categories = [
                'résidentiel', 'commercial', 'terrain', 'luxe', 
                'condo', 'maison', 'chalet', 'investissement'
            ];
            
            // Données des propriétés
            this.staticPlaces = this.generatePropertiesData();
            
            this.init();
        }
        
        generatePropertiesData() {
            return [
                // Québec
                {
                    id: 1,
                    name: 'Magnifique condo centre-ville',
                    description: 'Superbe condo avec vue panoramique, finitions haut de gamme et terrasse privée.',
                    latitude: 45.5080,
                    longitude: -73.5525,
                    category: 'condo',
                    price: 525000,
                    bedrooms: 2,
                    bathrooms: 1,
                    area: 850,
                    address: '345 Rue Peel, Montréal, QC',
                    province: 'qc',
                    video_id: '2vUedwjsgNQ'
                },
                {
                    id: 2,
                    name: 'Maison de luxe Outremont',
                    description: 'Propriété d\'exception avec jardin paysager, piscine et finitions européennes.',
                    latitude: 45.5180,
                    longitude: -73.6125,
                    category: 'luxe',
                    price: 1895000,
                    bedrooms: 5,
                    bathrooms: 3,
                    area: 3500,
                    address: '1230 Av. Bernard, Outremont, QC',
                    province: 'qc',
                    video_id: '2vUedwjsgNQ'
                },
                {
                    id: 3,
                    name: 'Chalet Mont-Tremblant',
                    description: 'Chalet contemporain avec accès direct aux pistes et vue imprenable.',
                    latitude: 46.2127,
                    longitude: -74.5822,
                    category: 'chalet',
                    price: 795000,
                    bedrooms: 4,
                    bathrooms: 2,
                    area: 2100,
                    address: '1000 Chemin des Voyageurs, Mont-Tremblant, QC',
                    province: 'qc',
                    video_id: '2vUedwjsgNQ'
                },
                {
                    id: 4,
                    name: 'Terrain constructible Brossard',
                    description: 'Magnifique terrain viabilisé, idéal pour construction de prestige.',
                    latitude: 45.4567,
                    longitude: -73.4678,
                    category: 'terrain',
                    price: 325000,
                    area: 12500,
                    address: 'Rue des Érables, Brossard, QC',
                    province: 'qc',
                    video_id: '2vUedwjsgNQ'
                },
                
                // Ontario
                {
                    id: 5,
                    name: 'Penthouse Toronto',
                    description: 'Penthouse de luxe avec terrasse de 1000pi² et vue sur le lac Ontario.',
                    latitude: 43.6426,
                    longitude: -79.3871,
                    category: 'luxe',
                    price: 2950000,
                    bedrooms: 3,
                    bathrooms: 3,
                    area: 2200,
                    address: '290 Bremner Blvd, Toronto, ON',
                    province: 'on',
                    video_id: '2vUedwjsgNQ'
                },
                {
                    id: 6,
                    name: 'Immeuble commercial',
                    description: 'Immeuble commercial de 6 unités en plein centre-ville.',
                    latitude: 43.6525,
                    longitude: -79.3819,
                    category: 'commercial',
                    price: 2450000,
                    area: 8500,
                    address: '55 Yonge St, Toronto, ON',
                    province: 'on',
                    video_id: '2vUedwjsgNQ'
                },
                {
                    id: 7,
                    name: 'Maison victorienne',
                    description: 'Superbe maison victorienne entièrement rénovée, caractère préservé.',
                    latitude: 43.6667,
                    longitude: -79.4100,
                    category: 'maison',
                    price: 1450000,
                    bedrooms: 4,
                    bathrooms: 3,
                    area: 2800,
                    address: '123 Spadina Rd, Toronto, ON',
                    province: 'on',
                    video_id: '2vUedwjsgNQ'
                },
                
                // Colombie-Britannique
                {
                    id: 8,
                    name: 'Condo vue océan',
                    description: 'Magnifique condo avec vue imprenable sur l\'océan et les montagnes.',
                    latitude: 49.2886,
                    longitude: -123.1285,
                    category: 'condo',
                    price: 895000,
                    bedrooms: 2,
                    bathrooms: 2,
                    area: 950,
                    address: '1055 Canada Pl, Vancouver, BC',
                    province: 'bc',
                    video_id: '2vUedwjsgNQ'
                },
                {
                    id: 9,
                    name: 'Investissement Whistler',
                    description: 'Station de ski rentable avec potentiel locatif exceptionnel.',
                    latitude: 50.1163,
                    longitude: -122.9574,
                    category: 'investissement',
                    price: 1250000,
                    bedrooms: 6,
                    bathrooms: 4,
                    area: 3200,
                    address: '4545 Blackcomb Way, Whistler, BC',
                    province: 'bc',
                    video_id: '2vUedwjsgNQ'
                },
                
                // Alberta
                {
                    id: 10,
                    name: 'Propriété de luxe Banff',
                    description: 'Chalet de luxe au cœur des Rocheuses, vue imprenable.',
                    latitude: 51.1784,
                    longitude: -115.5708,
                    category: 'luxe',
                    price: 2150000,
                    bedrooms: 5,
                    bathrooms: 4,
                    area: 3800,
                    address: '107 Tunnel Mountain Dr, Banff, AB',
                    province: 'ab',
                    video_id: '2vUedwjsgNQ'
                },

                // Brésil
        {
            id: 11,
            name: 'Penthouse à Copacabana',
            description: 'Appartement de luxe avec vue sur la plage de Copacabana, piscine et salle de sport.',
            latitude: -22.9711,
            longitude: -43.1822,
            category: 'luxe',
            price: 2500000,
            bedrooms: 3,
            bathrooms: 3,
            area: 200,
            address: 'Av. Atlântica, 2000 - Copacabana, Rio de Janeiro, Brésil',
            province: 'br',
            video_id: '2vUedwjsgNQ'
        },
        {
            id: 12,
            name: 'Condo moderne à São Paulo',
            description: 'Appartement moderne dans le quartier d\'affaires, proche de tout.',
            latitude: -23.5505,
            longitude: -46.6333,
            category: 'condo',
            price: 850000,
            bedrooms: 2,
            bathrooms: 2,
            area: 120,
            address: 'Av. Paulista, 1000 - Bela Vista, São Paulo, Brésil',
            province: 'br',
            video_id: '2vUedwjsgNQ'
        },
        
        // Argentine
        {
            id: 13,
            name: 'Loft à Palermo Soho',
            description: 'Loft design dans le quartier branché de Palermo Soho à Buenos Aires.',
            latitude: -34.5895,
            longitude: -58.4303,
            category: 'résidentiel',
            price: 650000,
            bedrooms: 2,
            bathrooms: 1,
            area: 95,
            address: 'Calle Honduras, Palermo Soho, Buenos Aires, Argentine',
            province: 'ar',
            video_id: '2vUedwjsgNQ'
        },
        {
            id: 14,
            name: 'Estancia à la campagne',
            description: 'Propriété rurale traditionnelle avec terrain et possibilité d\'exploitation.',
            latitude: -34.6118,
            longitude: -58.4173,
            category: 'terrain',
            price: 1200000,
            area: 50000,
            address: 'Ruta 8, Buenos Aires Province, Argentine',
            province: 'ar',
            video_id: '2vUedwjsgNQ'
        },
        
        // Chili
        {
            id: 15,
            name: 'Appartement vue mer à Viña del Mar',
            description: 'Magnifique appartement avec vue panoramique sur l\'océan Pacifique.',
            latitude: -33.0246,
            longitude: -71.5518,
            category: 'condo',
            price: 580000,
            bedrooms: 2,
            bathrooms: 2,
            area: 110,
            address: 'Av. Perú, Viña del Mar, Chili',
            province: 'cl',
            video_id: '2vUedwjsgNQ'
        },
        {
            id: 16,
            name: 'Maison à Santiago',
            description: 'Maison contemporaine dans le quartier huppé de Vitacura.',
            latitude: -33.4082,
            longitude: -70.5676,
            category: 'maison',
            price: 950000,
            bedrooms: 4,
            bathrooms: 3,
            area: 280,
            address: 'Av. Vitacura, Santiago, Chili',
            province: 'cl',
            video_id: '2vUedwjsgNQ'
        },
        
        // Colombie
        {
            id: 17,
            name: 'Appartement à El Poblado',
            description: 'Appartement moderne dans le quartier le plus exclusif de Medellín.',
            latitude: 6.2106,
            longitude: -75.5685,
            category: 'luxe',
            price: 780000,
            bedrooms: 3,
            bathrooms: 2,
            area: 150,
            address: 'Carrera 43, El Poblado, Medellín, Colombie',
            province: 'co',
            video_id: '2vUedwjsgNQ'
        },
        {
            id: 18,
            name: 'Finca à Coffee Triangle',
            description: 'Magnifique finca dans la région du café, idéale pour l\'écotourisme.',
            latitude: 4.8133,
            longitude: -75.6944,
            category: 'investissement',
            price: 550000,
            bedrooms: 5,
            bathrooms: 3,
            area: 5000,
            address: 'Salento, Quindío, Colombie',
            province: 'co',
            video_id: '2vUedwjsgNQ'
        },
        
        // Pérou
        {
            id: 19,
            name: 'Appartement à Miraflores',
            description: 'Bel appartement avec vue sur l\'océan dans le quartier de Miraflores à Lima.',
            latitude: -12.1218,
            longitude: -77.0308,
            category: 'condo',
            price: 420000,
            bedrooms: 2,
            bathrooms: 2,
            area: 100,
            address: 'Malecón de la Reserva, Miraflores, Lima, Pérou',
            province: 'pe',
            video_id: '2vUedwjsgNQ'
        },
        
        // Mexique
        {
            id: 20,
            name: 'Villa à Cancún',
            description: 'Villa de luxe avec piscine et accès privé à la plage.',
            latitude: 21.1619,
            longitude: -86.8515,
            category: 'luxe',
            price: 1850000,
            bedrooms: 5,
            bathrooms: 4,
            area: 400,
            address: 'Boulevard Kukulcan, Cancún, Mexique',
            province: 'mx',
            video_id: '2vUedwjsgNQ'
        },
        {
            id: 21,
            name: 'Condo à Mexico',
            description: 'Condo moderne dans le quartier branché de la Condesa.',
            latitude: 19.4156,
            longitude: -99.1678,
            category: 'condo',
            price: 680000,
            bedrooms: 2,
            bathrooms: 2,
            area: 130,
            address: 'Av. Ámsterdam, Condesa, Mexico, Mexique',
            province: 'mx',
            video_id: '2vUedwjsgNQ'
        },
        
        // Costa Rica
        {
            id: 22,
            name: 'Eco-lodge à Tamarindo',
            description: 'Éco-lodge durable proche des plages et du parc national.',
            latitude: 10.2994,
            longitude: -85.8408,
            category: 'investissement',
            price: 950000,
            bedrooms: 8,
            bathrooms: 6,
            area: 800,
            address: 'Playa Tamarindo, Guanacaste, Costa Rica',
            province: 'cr',
            video_id: '2vUedwjsgNQ'
        },
        
        // Uruguay
        {
            id: 23,
            name: 'Maison à Punta del Este',
            description: 'Maison de vacances avec vue sur la mer et accès à la plage.',
            latitude: -34.9486,
            longitude: -54.9319,
            category: 'chalet',
            price: 1150000,
            bedrooms: 4,
            bathrooms: 3,
            area: 300,
            address: 'Rambla Claudio Williman, Punta del Este, Uruguay',
            province: 'uy',
            video_id: '2vUedwjsgNQ'
        },
        
        // États-Unis (quelques exemples)
        {
            id: 24,
            name: 'Penthouse à New York',
            description: 'Penthouse avec terrasse et vue sur Central Park.',
            latitude: 40.7831,
            longitude: -73.9712,
            category: 'luxe',
            price: 8500000,
            bedrooms: 4,
            bathrooms: 3,
            area: 250,
            address: '15 Central Park West, New York, NY, USA',
            province: 'ny',
            video_id: '2vUedwjsgNQ'
        },
        {
            id: 25,
            name: 'Villa à Miami Beach',
            description: 'Villa méditerranéenne avec piscine et accès à la plage.',
            latitude: 25.7903,
            longitude: -80.1302,
            category: 'luxe',
            price: 3200000,
            bedrooms: 5,
            bathrooms: 4,
            area: 450,
            address: 'Collins Ave, Miami Beach, FL, USA',
            province: 'fl',
            video_id: '2vUedwjsgNQ'
        }
            ];
        }
        
        init() {
            try {
                this.initMap();
                this.initSidebar();
                this.populateFilters();
                this.loadStaticPlaces();
                this.setupEventListeners();
                this.addOverviewControl();
                console.log('Carte initialisée avec succès');
            } catch (error) {
                console.error('Erreur lors de l\'initialisation:', error);
            }
        }
        
        initMap() {
    try {
        document.getElementById('mapLoading').style.display = 'none';
        
        // Centrer sur les Amériques (point central entre NA et SA)
        // Latitude: 15°N (centre approximatif), Longitude: -60°O (centre de l'Atlantique)
        this.map = L.map('map').setView([15.0, -60.0], 3); // Zoom 3 pour voir les deux continents
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
            maxZoom: 19
        }).addTo(this.map);
        
        L.control.scale({ imperial: false }).addTo(this.map);
        this.addLocateControl();
        
    } catch (error) {
        console.error('Erreur initialisation carte:', error);
    }
}


        addOverviewControl() {
    const overviewControl = L.control({ position: 'topright' });
    
    overviewControl.onAdd = (map) => {
        const container = L.DomUtil.create('div', 'leaflet-control-overview-custom leaflet-bar leaflet-control');
        const link = L.DomUtil.create('a', '', container);
        link.href = '#';
        link.title = 'Vue d\'ensemble des Amériques';
        link.innerHTML = '<i class="fas fa-globe-americas"></i>';
        
        L.DomEvent.on(link, 'click', (e) => {
            L.DomEvent.stopPropagation(e);
            L.DomEvent.preventDefault(e);
            this.map.setView([15.0, -60.0], 3);
        });
        
        return container;
    };
    
    overviewControl.addTo(this.map);
}

        
        populateFilters() {
            const provinceFilter = document.getElementById('province-filter');
            this.provinces.forEach(province => {
                const option = document.createElement('option');
                option.value = province.code;
                option.textContent = province.name;
                option.dataset.lat = province.lat;
                option.dataset.lng = province.lng;
                provinceFilter.appendChild(option);
            });
            
            const categoryFilter = document.getElementById('category-filter');
            this.categories.forEach(category => {
                const option = document.createElement('option');
                option.value = category;
                option.textContent = this.capitalizeFirstLetter(category);
                categoryFilter.appendChild(option);
            });
        }
        
        initSidebar() {
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebar = document.getElementById('sidebarRight');
            
            if (sidebarToggle && sidebar) {
                sidebarToggle.addEventListener('click', () => {
                    sidebar.classList.toggle('active');
                    const icon = document.getElementById('sidebarToggleIcon');
                    icon.className = sidebar.classList.contains('active') ? 'fas fa-times' : 'fas fa-bars';
                });
            }
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
        
        loadStaticPlaces() {
            this.places = this.staticPlaces;
            this.updatePlacesCount();
            this.renderPlacesList();
            this.addMarkersToMap();
        }
        
        addMarkersToMap() {
            this.clearMarkers();
            
            this.places.forEach(place => {
                this.createMarker(place);
            });
            
            if (this.places.length > 0) {
                const bounds = this.getMarkersBounds();
                this.map.fitBounds(bounds, { padding: [50, 50] });
            }
        }
        
        clearMarkers() {
            Object.values(this.markers).forEach(({ marker, popup }) => {
                if (marker) marker.remove();
                if (popup) popup.remove();
            });
            this.markers = {};
        }
        
        createMarker(place) {
            const icon = L.divIcon({
                className: 'custom-marker',
                html: `
                    <div class="marker-icon" style="background: ${this.getCategoryColor(place.category)};">
                        <i class="${this.getCategoryIcon(place.category)}"></i>
                    </div>
                `,
                iconSize: [40, 40],
                iconAnchor: [20, 40]
            });
            
            const marker = L.marker([place.latitude, place.longitude], { 
                icon: icon,
                title: place.name
            }).addTo(this.map);
            
            // Créer le popup avec gestion améliorée du hover
            const popupContent = this.createPopupContent(place);
            const popup = L.popup({
                maxWidth: 350,
                closeButton: false,
                autoClose: false,
                closeOnClick: false,
                offset: L.point(0, -45),
                className: 'custom-popup interactive-popup'
            }).setContent(popupContent);
            
            // Gestion du hover sur le marqueur
            marker.on('mouseover', () => {
                if (this.popupTimeout) {
                    clearTimeout(this.popupTimeout);
                    this.popupTimeout = null;
                }
                
                this.hoverTimeout = setTimeout(() => {
                    // Fermer tous les autres popups
                    this.map.closePopup();
                    
                    // Ouvrir ce popup
                    popup.setLatLng(marker.getLatLng()).openOn(this.map);
                    
                    // Mettre en surbrillance l'élément dans la liste
                    const placeElement = document.querySelector(`.place-item[data-id="${place.id}"]`);
                    if (placeElement) placeElement.classList.add('active');
                    
                    // Mettre en surbrillance le marqueur
                    const iconElement = marker.getElement();
                    if (iconElement) {
                        const markerIcon = iconElement.querySelector('.marker-icon');
                        if (markerIcon) markerIcon.classList.add('highlighted');
                    }
                    
                    // Attacher les événements au contenu du popup
                    this.attachPopupEvents(place, popup);
                    
                }, 200);
            });
            
            marker.on('mouseout', (e) => {
                if (this.hoverTimeout) {
                    clearTimeout(this.hoverTimeout);
                    this.hoverTimeout = null;
                }
                
                // Vérifier si la souris est sur le popup avant de fermer
                this.popupTimeout = setTimeout(() => {
                    const popupElement = document.querySelector('.leaflet-popup');
                    if (!popupElement || !popupElement.matches(':hover')) {
                        this.map.closePopup();
                        
                        const placeElement = document.querySelector(`.place-item[data-id="${place.id}"]`);
                        if (placeElement) placeElement.classList.remove('active');
                        
                        const iconElement = marker.getElement();
                        if (iconElement) {
                            const markerIcon = iconElement.querySelector('.marker-icon');
                            if (markerIcon) markerIcon.classList.remove('highlighted');
                        }
                    }
                }, 300);
            });
            
            marker.on('click', () => {
                if (this.hoverTimeout) clearTimeout(this.hoverTimeout);
                if (this.popupTimeout) clearTimeout(this.popupTimeout);
                this.showPlaceModal(place);
            });
            
            this.markers[place.id] = { marker, popup };
            
            return marker;
        }
        
        attachPopupEvents(place, popup) {
            // Attendre que le popup soit dans le DOM
            setTimeout(() => {
                const popupElement = document.querySelector('.leaflet-popup');
                if (popupElement) {
                    // Garder le popup ouvert quand la souris est dessus
                    popupElement.addEventListener('mouseenter', () => {
                        if (this.popupTimeout) {
                            clearTimeout(this.popupTimeout);
                            this.popupTimeout = null;
                        }
                    });
                    
                    popupElement.addEventListener('mouseleave', () => {
                        this.popupTimeout = setTimeout(() => {
                            this.map.closePopup();
                            
                            const placeElement = document.querySelector(`.place-item[data-id="${place.id}"]`);
                            if (placeElement) placeElement.classList.remove('active');
                            
                            const markerData = this.markers[place.id];
                            if (markerData) {
                                const iconElement = markerData.marker.getElement();
                                if (iconElement) {
                                    const markerIcon = iconElement.querySelector('.marker-icon');
                                    if (markerIcon) markerIcon.classList.remove('highlighted');
                                }
                            }
                        }, 200);
                    });
                    
                    // Gérer le clic sur le bouton de détail dans le popup
                    const detailBtn = popupElement.querySelector('.popup-detail-btn');
                    if (detailBtn) {
                        detailBtn.addEventListener('click', (e) => {
                            e.preventDefault();
                            e.stopPropagation();
                            this.map.closePopup();
                            this.showPlaceModal(place);
                        });
                    }
                }
            }, 50);
        }
        
        createPopupContent(place) {
            return `
                <div class="hover-popup-content" data-place-id="${place.id}" style="padding:15px;">
                    <div style="display:flex; align-items:center; gap:12px; margin-bottom:15px;">
                        <div style="width:40px; height:40px; border-radius:50%; background:${this.getCategoryColor(place.category)}; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                            <i class="${this.getCategoryIcon(place.category)}" style="color:white; font-size:18px;"></i>
                        </div>
                        <div style="flex:1; min-width:0;">
                            <h4 style="margin:0 0 4px 0; font-size:15px; font-weight:600; color:#1a1a1a; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">${place.name}</h4>
                            <div style="display:flex; align-items:center; gap:8px; font-size:12px; color:#666;">
                                <span>${this.capitalizeFirstLetter(place.category)}</span>
                                <span>•</span>
                                <span style="font-weight:600; color:#1b4f6b;">$${place.price?.toLocaleString()}</span>
                            </div>
                        </div>
                    </div>
                    
                    ${place.video_id ? `
                        <div class="popup-video-container" style="margin-bottom:12px; border-radius:8px; overflow:hidden; position:relative; background:#000;">
                            <div style="position:relative; padding-bottom:56.25%; height:0;">
                                <iframe src="https://www.youtube.com/embed/${place.video_id}?autoplay=0&mute=1&controls=1&enablejsapi=1"
                                        style="position:absolute; top:0; left:0; width:100%; height:100%;"
                                        frameborder="0" 
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                        allowfullscreen></iframe>
                            </div>
                            <div style="position:absolute; top:8px; right:8px; background:rgba(0,0,0,0.7); color:white; padding:4px 8px; border-radius:4px; font-size:11px; display:flex; align-items:center; gap:4px; pointer-events:none;">
                                <i class="fab fa-youtube" style="color:#ff0000;"></i>
                                <span>Vidéo disponible</span>
                            </div>
                        </div>
                    ` : `
                        <div style="margin-bottom:12px; height:140px; border-radius:8px; background:linear-gradient(135deg, #f5f5f5, #e0e0e0); display:flex; align-items:center; justify-content:center; flex-direction:column; gap:8px;">
                            <i class="fas fa-home" style="font-size:40px; color:#999;"></i>
                            <span style="font-size:12px; color:#666;">Aperçu disponible</span>
                        </div>
                    `}
                    
                    <p style="margin:0 0 15px 0; font-size:12px; color:#666; line-height:1.5;">${place.description}</p>
                    
                    <div style="display:flex; gap:10px;">
                        <button class="popup-detail-btn" 
                                style="flex:1; background:#1b4f6b; color:white; border:none; border-radius:6px; padding:10px; font-size:12px; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:6px; transition:all 0.2s;">
                            <i class="fas fa-info-circle"></i>
                            Voir détails
                        </button>
                    </div>
                </div>
            `;
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
                        <p>Essayez de modifier vos filtres</p>
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
            
            div.innerHTML = `
                <div class="place-image">
                    <img src="https://images.unsplash.com/photo-1512917774080-9991f1c4c750?w=400&h=150&fit=crop" alt="${place.name}">
                </div>
                <div class="place-info">
                    <h4>${place.name}</h4>
                    <span class="place-category" style="background:${this.getCategoryColor(place.category)}">
                        ${this.capitalizeFirstLetter(place.category)}
                    </span>
                    <div style="font-size:1.2rem; font-weight:700; color:#1b4f6b; margin:8px 0;">
                        $${place.price?.toLocaleString()}
                    </div>
                    <p class="place-description">${place.description.substring(0, 60)}...</p>
                    <div class="place-actions">
                        <button class="view-details-btn" data-id="${place.id}">
                            <i class="fas fa-eye"></i> Détails
                        </button>
                        <button class="locate-btn-small" data-id="${place.id}">
                            <i class="fas fa-map-marker-alt"></i> Carte
                        </button>
                    </div>
                </div>
            `;
            
            div.querySelector('.view-details-btn').addEventListener('click', (e) => {
                e.stopPropagation();
                this.showPlaceModal(place);
            });
            
            div.querySelector('.locate-btn-small').addEventListener('click', (e) => {
                e.stopPropagation();
                this.centerOnPlace(place);
            });
            
            div.addEventListener('mouseenter', () => {
                const markerData = this.markers[place.id];
                if (markerData) {
                    // Ouvrir le popup
                    if (this.popupTimeout) clearTimeout(this.popupTimeout);
                    markerData.popup.setLatLng([place.latitude, place.longitude]).openOn(this.map);
                    
                    // Mettre en surbrillance le marqueur
                    const iconElement = markerData.marker.getElement();
                    if (iconElement) {
                        const markerIcon = iconElement.querySelector('.marker-icon');
                        if (markerIcon) markerIcon.classList.add('highlighted');
                    }
                }
            });
            
            div.addEventListener('mouseleave', () => {
                const markerData = this.markers[place.id];
                if (markerData) {
                    // Vérifier si la souris est sur le popup avant de fermer
                    setTimeout(() => {
                        const popupElement = document.querySelector('.leaflet-popup');
                        if (!popupElement || !popupElement.matches(':hover')) {
                            markerData.popup.remove();
                            
                            const iconElement = markerData.marker.getElement();
                            if (iconElement) {
                                const markerIcon = iconElement.querySelector('.marker-icon');
                                if (markerIcon) markerIcon.classList.remove('highlighted');
                            }
                        }
                    }, 100);
                }
            });
            
            return div;
        }
        
        centerOnPlace(place) {
            this.map.setView([place.latitude, place.longitude], 14);
            const markerData = this.markers[place.id];
            if (markerData && markerData.popup) {
                markerData.popup.setLatLng([place.latitude, place.longitude]).openOn(this.map);
            }
        }
        
        showPlaceModal(place) {
            const modal = document.getElementById('place-modal');
            const modalContent = document.getElementById('modal-content');
            
            if (!modal || !modalContent) return;
            
            this.activePlace = place;
            modalContent.innerHTML = this.createModalContent(place);
            modal.style.display = 'block';
            
            this.centerOnPlace(place);
        }
        
        createModalContent(place) {
            return `
                <div style="padding:30px;">
                    <div style="margin-bottom:30px;">
                        <h2 style="margin:0 0 10px 0; color:#1a1a1a; font-size:1.8rem;">${place.name}</h2>
                        <div style="display:flex; align-items:center; gap:10px; flex-wrap:wrap;">
                            <span style="background:${this.getCategoryColor(place.category)}; color:white; padding:6px 16px; border-radius:20px; font-size:14px;">
                                ${this.capitalizeFirstLetter(place.category)}
                            </span>
                            <span style="color:#666; font-size:14px;">
                                <i class="fas fa-map-marker-alt"></i> ${this.getProvinceName(place.province)}
                            </span>
                        </div>
                    </div>
                    
                    <div style="margin-bottom:30px;">
                        <div style="font-size:2.5rem; font-weight:800; color:#1b4f6b; margin-bottom:20px;">
                            $${place.price?.toLocaleString()}
                        </div>
                    </div>
                    
                    ${place.video_id ? `
                        <div style="margin-bottom:30px; border-radius:12px; overflow:hidden; position:relative;">
                            <div style="position:relative; padding-bottom:56.25%; height:0;">
                                <iframe src="https://www.youtube.com/embed/${place.video_id}?autoplay=0&controls=1"
                                        style="position:absolute; top:0; left:0; width:100%; height:100%;"
                                        frameborder="0" allowfullscreen></iframe>
                            </div>
                        </div>
                    ` : ''}
                    
                    <div style="margin-bottom:30px;">
                        <h4 style="color:#333; margin-bottom:15px;">Description</h4>
                        <p style="color:#666; line-height:1.6;">${place.description}</p>
                    </div>
                    
                    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(200px, 1fr)); gap:20px; margin-bottom:30px;">
                        ${place.bedrooms ? `
                            <div style="background:#f8f9fa; padding:15px; border-radius:10px; text-align:center;">
                                <i class="fas fa-bed" style="color:#1b4f6b; font-size:24px; margin-bottom:8px;"></i>
                                <div style="font-size:1.2rem; font-weight:600;">${place.bedrooms}</div>
                                <div style="color:#666;">Chambres</div>
                            </div>
                        ` : ''}
                        
                        ${place.bathrooms ? `
                            <div style="background:#f8f9fa; padding:15px; border-radius:10px; text-align:center;">
                                <i class="fas fa-bath" style="color:#1b4f6b; font-size:24px; margin-bottom:8px;"></i>
                                <div style="font-size:1.2rem; font-weight:600;">${place.bathrooms}</div>
                                <div style="color:#666;">Salles de bain</div>
                            </div>
                        ` : ''}
                        
                        ${place.area ? `
                            <div style="background:#f8f9fa; padding:15px; border-radius:10px; text-align:center;">
                                <i class="fas fa-ruler-combined" style="color:#1b4f6b; font-size:24px; margin-bottom:8px;"></i>
                                <div style="font-size:1.2rem; font-weight:600;">${place.area} pi²</div>
                                <div style="color:#666;">Surface</div>
                            </div>
                        ` : ''}
                    </div>
                    
                    ${place.address ? `
                        <div style="background:#f8f9fa; padding:20px; border-radius:10px; margin-bottom:20px;">
                            <div style="display:flex; align-items:center; gap:12px;">
                                <i class="fas fa-map-marker-alt" style="color:#1b4f6b; font-size:18px;"></i>
                                <span style="color:#666;">${place.address}</span>
                            </div>
                        </div>
                    ` : ''}
                    
                    <div style="display:flex; gap:15px; margin-top:30px;">
                        <button onclick="window.mapApp.closeModal()" 
                                style="flex:1; padding:14px; background:#f0f0f0; color:#333; border:none; border-radius:8px; cursor:pointer; font-weight:500;">
                            Fermer
                        </button>
                    </div>
                </div>
            `;
        }
        
        closeModal() {
            const modal = document.getElementById('place-modal');
            if (modal) modal.style.display = 'none';
            this.activePlace = null;
        }
        
        getCategoryColor(category) {
            const colors = {
                résidentiel: '#1b4f6b',
                commercial: '#e53e3e',
                terrain: '#38a169',
                luxe: '#805ad5',
                condo: '#3182ce',
                maison: '#d69e2e',
                chalet: '#48bb78',
                investissement: '#ed64a6',
                default: '#718096'
            };
            return colors[category] || colors.default;
        }
        
        getCategoryIcon(category) {
            const icons = {
                résidentiel: 'fas fa-home',
                commercial: 'fas fa-building',
                terrain: 'fas fa-tree',
                luxe: 'fas fa-crown',
                condo: 'fas fa-city',
                maison: 'fas fa-home',
                chalet: 'fas fa-mountain',
                investissement: 'fas fa-chart-line',
                default: 'fas fa-map-marker-alt'
            };
            return icons[category] || icons.default;
        }
        
        getProvinceName(code) {
            const province = this.provinces.find(p => p.code === code);
            return province ? province.name : 'Canada';
        }
        
        getMarkersBounds() {
            const bounds = L.latLngBounds();
            this.places.forEach(place => {
                bounds.extend([place.latitude, place.longitude]);
            });
            return bounds;
        }
        
        filterPlaces() {
            let filteredPlaces = this.staticPlaces;
            
            if (this.selectedCategory !== 'all') {
                filteredPlaces = filteredPlaces.filter(place => 
                    place.category === this.selectedCategory
                );
            }
            
            if (this.selectedProvince) {
                filteredPlaces = filteredPlaces.filter(place => 
                    place.province === this.selectedProvince
                );
            }
            
            this.places = filteredPlaces;
            this.updatePlacesCount();
            this.renderPlacesList();
            this.addMarkersToMap();
        }
        
        locateUser() {
            if (!navigator.geolocation) {
                alert('Géolocalisation non supportée');
                return;
            }
            
            const locateBtn = document.getElementById('locate-me');
            const originalHTML = locateBtn.innerHTML;
            locateBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Localisation...';
            locateBtn.disabled = true;
            
            navigator.geolocation.getCurrentPosition(
                (position) => {
                    const { latitude, longitude } = position.coords;
                    this.currentLocation = { lat: latitude, lng: longitude };
                    
                    this.map.setView([latitude, longitude], 12);
                    this.addUserMarker(latitude, longitude);
                    
                    locateBtn.innerHTML = originalHTML;
                    locateBtn.disabled = false;
                },
                (error) => {
                    console.error('Erreur:', error);
                    alert('Impossible de vous localiser');
                    locateBtn.innerHTML = originalHTML;
                    locateBtn.disabled = false;
                }
            );
        }
        
        addUserMarker(lat, lng) {
            if (this.userMarker) this.userMarker.remove();
            
            const userIcon = L.divIcon({
                className: 'custom-marker',
                html: '<div class="user-marker-icon"><i class="fas fa-user"></i></div>',
                iconSize: [50, 50],
                iconAnchor: [25, 50]
            });
            
            this.userMarker = L.marker([lat, lng], { icon: userIcon }).addTo(this.map);
        }
        
        updatePlacesCount() {
            const countEl = document.getElementById('places-count');
            if (countEl) countEl.textContent = this.places.length;
        }
        
        capitalizeFirstLetter(string) {
            return string ? string.charAt(0).toUpperCase() + string.slice(1) : '';
        }
        
        setupEventListeners() {
            const provinceFilter = document.getElementById('province-filter');
            if (provinceFilter) {
                provinceFilter.addEventListener('change', (e) => {
                    this.selectedProvince = e.target.value;
                    
                    if (this.selectedProvince) {
                        const option = e.target.selectedOptions[0];
                        const lat = parseFloat(option.dataset.lat);
                        const lng = parseFloat(option.dataset.lng);
                        
                        if (!isNaN(lat) && !isNaN(lng)) {
                            this.map.setView([lat, lng], 6);
                        }
                    }
                    
                    this.filterPlaces();
                });
            }
            
            const categoryFilter = document.getElementById('category-filter');
            if (categoryFilter) {
                categoryFilter.addEventListener('change', (e) => {
                    this.selectedCategory = e.target.value;
                    this.filterPlaces();
                });
            }
            
            document.querySelector('.close-modal')?.addEventListener('click', () => {
                this.closeModal();
            });
            
            window.addEventListener('click', (e) => {
                const modal = document.getElementById('place-modal');
                if (e.target === modal) this.closeModal();
            });
            
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') this.closeModal();
            });
        }
    }
    
    // Initialisation
    document.addEventListener('DOMContentLoaded', () => {
        window.mapApp = new InteractiveMap();
    });