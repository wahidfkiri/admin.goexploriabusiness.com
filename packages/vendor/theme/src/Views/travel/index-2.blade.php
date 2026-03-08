<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualités Voyage Canada | Découvrez les plus belles régions</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Open+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #3a6ea5;
            --primary-dark: #2a4d7a;
            --secondary: #e63946;
            --light: #f8f9fa;
            --dark: #212529;
            --gray: #6c757d;
            --light-gray: #e9ecef;
            --transition: all 0.3s ease;
            --shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            --shadow-hover: 0 10px 25px rgba(0, 0, 0, 0.15);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Open Sans', sans-serif;
            color: var(--dark);
            background-color: var(--light);
            line-height: 1.6;
        }

        h1, h2, h3, h4 {
            font-family: 'Montserrat', sans-serif;
            font-weight: 700;
            line-height: 1.2;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* Header */
        header {
            background: linear-gradient(rgba(42, 77, 122, 0.9), rgba(42, 77, 122, 0.9)), url('https://images.unsplash.com/photo-1506905925346-21bda4d32df4?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 80px 0 60px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        header h1 {
            font-size: 3.2rem;
            margin-bottom: 15px;
            opacity: 0;
            transform: translateY(30px);
            animation: fadeInUp 0.8s forwards 0.3s;
        }

        header p {
            font-size: 1.2rem;
            max-width: 700px;
            margin: 0 auto 30px;
            opacity: 0;
            transform: translateY(30px);
            animation: fadeInUp 0.8s forwards 0.5s;
        }

        .header-wave {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            overflow: hidden;
            line-height: 0;
        }

        .header-wave svg {
            display: block;
            width: calc(100% + 1.3px);
            height: 60px;
        }

        .header-wave .shape-fill {
            fill: var(--light);
        }

        /* Section titre */
        .section-title {
            text-align: center;
            margin: 60px 0 40px;
            position: relative;
        }

        .section-title h2 {
            font-size: 2.5rem;
            color: var(--primary-dark);
            display: inline-block;
            position: relative;
            padding-bottom: 15px;
        }

        .section-title h2:after {
            content: '';
            position: absolute;
            width: 70px;
            height: 4px;
            background-color: var(--secondary);
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
        }

        /* Filtres */
        .filters {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 40px;
        }

        .filter-btn {
            padding: 10px 25px;
            background-color: white;
            border: 2px solid var(--primary);
            color: var(--primary);
            border-radius: 50px;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
        }

        .filter-btn:hover, .filter-btn.active {
            background-color: var(--primary);
            color: white;
            transform: translateY(-3px);
            box-shadow: var(--shadow);
        }

        /* Grille des destinations */
        .destinations-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 30px;
            margin-bottom: 60px;
        }

        .destination-card {
            background-color: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: var(--shadow);
            transition: var(--transition);
            opacity: 0;
            transform: translateY(20px);
            animation: fadeInUp 0.6s forwards;
        }

        .destination-card:hover {
            transform: translateY(-10px);
            box-shadow: var(--shadow-hover);
        }

        .card-image {
            height: 220px;
            width: 100%;
            overflow: hidden;
            position: relative;
        }

        .card-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: var(--transition);
        }

        .destination-card:hover .card-image img {
            transform: scale(1.05);
        }

        .card-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background-color: var(--secondary);
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .card-content {
            padding: 25px;
        }

        .card-content h3 {
            font-size: 1.5rem;
            margin-bottom: 10px;
            color: var(--primary-dark);
        }

        .card-location {
            display: flex;
            align-items: center;
            color: var(--gray);
            margin-bottom: 15px;
            font-size: 0.9rem;
        }

        .card-location i {
            margin-right: 8px;
            color: var(--primary);
        }

        .card-description {
            margin-bottom: 20px;
            color: var(--dark);
        }

        .card-price {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--secondary);
            margin-bottom: 20px;
        }

        .card-price span {
            font-size: 0.9rem;
            color: var(--gray);
            font-weight: 400;
        }

        .card-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .btn-details, .btn-reserve {
            padding: 10px 20px;
            border-radius: 5px;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            border: none;
            font-family: 'Montserrat', sans-serif;
        }

        .btn-details {
            background-color: transparent;
            border: 2px solid var(--primary);
            color: var(--primary);
        }

        .btn-details:hover {
            background-color: var(--primary);
            color: white;
        }

        .btn-reserve {
            background-color: var(--primary);
            color: white;
        }

        .btn-reserve:hover {
            background-color: var(--primary-dark);
            transform: translateY(-2px);
        }

        /* Modal */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
            transition: var(--transition);
        }

        .modal-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        .modal {
            background-color: white;
            border-radius: 12px;
            width: 90%;
            max-width: 900px;
            max-height: 90vh;
            overflow-y: auto;
            padding: 30px;
            position: relative;
            transform: scale(0.9);
            transition: var(--transition);
        }

        .modal-overlay.active .modal {
            transform: scale(1);
        }

        .modal-close {
            position: absolute;
            top: 20px;
            right: 20px;
            background: none;
            border: none;
            font-size: 1.5rem;
            color: var(--gray);
            cursor: pointer;
            transition: var(--transition);
        }

        .modal-close:hover {
            color: var(--secondary);
        }

        .modal-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
        }

        .modal-media {
            border-radius: 8px;
            overflow: hidden;
        }

        .modal-media img {
            width: 100%;
            height: auto;
            border-radius: 8px;
        }

        .video-container {
            position: relative;
            padding-bottom: 56.25%;
            height: 0;
            margin-top: 20px;
            border-radius: 8px;
            overflow: hidden;
        }

        .video-container iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }

        .modal-info h3 {
            font-size: 1.8rem;
            margin-bottom: 10px;
            color: var(--primary-dark);
        }

        .modal-price {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--secondary);
            margin: 15px 0;
        }

        .booking-form {
            margin-top: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
        }

        .form-group input, .form-group select {
            width: 100%;
            padding: 10px 15px;
            border: 1px solid var(--light-gray);
            border-radius: 5px;
            font-family: 'Open Sans', sans-serif;
        }

        .btn-submit {
            background-color: var(--secondary);
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 5px;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            width: 100%;
            margin-top: 10px;
        }

        .btn-submit:hover {
            background-color: #d32f2f;
        }

        /* Footer */
        footer {
            background-color: var(--primary-dark);
            color: white;
            padding: 50px 0 20px;
            text-align: center;
        }

        .footer-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
            margin-bottom: 40px;
        }

        .footer-section h3 {
            font-size: 1.3rem;
            margin-bottom: 20px;
            position: relative;
            padding-bottom: 10px;
        }

        .footer-section h3:after {
            content: '';
            position: absolute;
            width: 40px;
            height: 3px;
            background-color: var(--secondary);
            bottom: 0;
            left: 0;
        }

        .footer-section.centered h3:after {
            left: 50%;
            transform: translateX(-50%);
        }

        .social-links {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 20px;
        }

        .social-links a {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            color: white;
            transition: var(--transition);
        }

        .social-links a:hover {
            background-color: var(--secondary);
            transform: translateY(-3px);
        }

        .copyright {
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.7);
        }

        /* Animations */
        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .stagger-animation > * {
            opacity: 0;
            transform: translateY(20px);
        }

        .stagger-animation.animated > * {
            animation: fadeInUp 0.6s forwards;
        }

        .stagger-animation.animated > *:nth-child(1) { animation-delay: 0.1s; }
        .stagger-animation.animated > *:nth-child(2) { animation-delay: 0.2s; }
        .stagger-animation.animated > *:nth-child(3) { animation-delay: 0.3s; }
        .stagger-animation.animated > *:nth-child(4) { animation-delay: 0.4s; }
        .stagger-animation.animated > *:nth-child(5) { animation-delay: 0.5s; }
        .stagger-animation.animated > *:nth-child(6) { animation-delay: 0.6s; }

        /* Responsive */
        @media (max-width: 992px) {
            .modal-content {
                grid-template-columns: 1fr;
            }
            
            .destinations-grid {
                grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            }
            
            header h1 {
                font-size: 2.5rem;
            }
        }

        @media (max-width: 768px) {
            .filters {
                flex-direction: column;
                align-items: center;
            }
            
            .filter-btn {
                width: 200px;
            }
            
            .card-actions {
                flex-direction: column;
                gap: 10px;
            }
            
            .btn-details, .btn-reserve {
                width: 100%;
            }
            
            header h1 {
                font-size: 2rem;
            }
            
            .section-title h2 {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>

    <!-- Section filtres -->
    <div class="container">
        <div class="section-title">
            <h2>Destinations Populaires</h2>
        </div>
        
        <div class="filters">
            <button class="filter-btn active" data-filter="all">Toutes les régions</button>
            <button class="filter-btn" data-filter="ouest">Ouest Canadien</button>
            <button class="filter-btn" data-filter="est">Est Canadien</button>
            <button class="filter-btn" data-filter="nord">Nord Canadien</button>
        </div>

        <!-- Grille des destinations -->
        <div class="destinations-grid stagger-animation" id="destinations-container">
            <!-- Les cartes seront générées par JavaScript -->
        </div>
    </div>

    <!-- Modal pour les détails et réservation -->
    <div class="modal-overlay" id="modal">
        <div class="modal">
            <button class="modal-close" id="close-modal">&times;</button>
            <div class="modal-content" id="modal-content">
                <!-- Le contenu sera injecté par JavaScript -->
            </div>
        </div>
    </div>


    <script>
        // Données des destinations
        const destinations = [
            {
                id: 1,
                title: "Parc national Banff",
                region: "ouest",
                location: "Alberta",
                description: "Le plus ancien parc national du Canada, célèbre pour ses paysages montagneux spectaculaires, ses lacs turquoise et sa faune abondante. Découvrez le lac Louise, la promenade des Glaciers et les sources thermales.",
                price: 1299,
                image: "https://images.unsplash.com/photo-1503614472-8c93d56e92ce?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2011&q=80",
                video: "https://www.youtube.com/embed/InyPq6cR5zs",
                highlights: ["Lac Louise", "Sources thermales", "Randonnées glaciaires", "Observation de la faune"],
                duration: "7 jours / 6 nuits",
                season: "Mai à Octobre"
            },
            {
                id: 2,
                title: "Vieux-Québec",
                region: "est",
                location: "Québec",
                description: "Plongez dans l'histoire et la culture française en Amérique du Nord. Explorez les rues pavées, les fortifications et les monuments historiques de cette ville classée au patrimoine mondial de l'UNESCO.",
                price: 899,
                image: "https://images.unsplash.com/photo-1548191657-6aa4c4c8e0e9?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2069&q=80",
                video: "https://www.youtube.com/embed/moqj5wF6OeY",
                highlights: ["Château Frontenac", "Place Royale", "Terrasse Dufferin", "Petit Champlain"],
                duration: "5 jours / 4 nuits",
                season: "Toute l'année"
            },
            {
                id: 3,
                title: "Aurores boréales au Yukon",
                region: "nord",
                location: "Yukon",
                description: "Vivez l'expérience magique des aurores boréales dans le Grand Nord canadien. Cette aventure unique combine observation des phénomènes célestes, découverte de la culture autochtone et activités hivernales.",
                price: 2499,
                image: "https://images.unsplash.com/photo-1578662996442-48f60103fc96?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80",
                video: "https://www.youtube.com/embed/fVsONlc3OUY",
                highlights: ["Aurores boréales", "Culture autochtone", "Chiens de traîneau", "Observation des étoiles"],
                duration: "6 jours / 5 nuits",
                season: "Septembre à Mars"
            },
            {
                id: 4,
                title: "Île de Vancouver",
                region: "ouest",
                location: "Colombie-Britannique",
                description: "Découvrez la côte sauvage du Pacifique avec ses forêts anciennes, ses plages spectaculaires et sa faune marine unique. Observation des baleines, randonnées côtières et découverte de la culture autochtone.",
                price: 1599,
                image: "https://images.unsplash.com/photo-1544551763-46a013bb70d5?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80",
                video: "https://www.youtube.com/embed/7F2TMSx0R30",
                highlights: ["Observation des baleines", "Forêt ancienne", "Victoria", "Surf à Tofino"],
                duration: "8 jours / 7 nuits",
                season: "Avril à Octobre"
            },
            {
                id: 5,
                title: "Toronto et les chutes du Niagara",
                region: "est",
                location: "Ontario",
                description: "Combinez l'énergie vibrante de la plus grande ville du Canada avec la puissance naturelle spectaculaire des chutes du Niagara. Découvrez les quartiers culturels, la tour CN et l'expérience unique des chutes.",
                price: 1099,
                image: "https://images.unsplash.com/photo-1528164344705-47542687000d?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2092&q=80",
                video: "https://www.youtube.com/embed/A1QvJt6SX80",
                highlights: ["Chutes du Niagara", "Tour CN", "Quartier de Distillery", "Îles de Toronto"],
                duration: "6 jours / 5 nuits",
                season: "Avril à Novembre"
            },
            {
                id: 6,
                title: "Parc national de Gros Morne",
                region: "est",
                location: "Terre-Neuve-et-Labrador",
                description: "Explorez ce site du patrimoine mondial de l'UNESCO connu pour ses paysages spectaculaires façonnés par les glaciers. Fjords impressionnants, montagnes tabulaires et richesse géologique unique.",
                price: 1399,
                image: "https://images.unsplash.com/photo-1543857778-c4a1a569e388?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2074&q=80",
                video: "https://www.youtube.com/embed/QvltFjX_jNc",
                highlights: ["Fjords glaciaires", "Tablelands", "Randonnées côtières", "Culture terre-neuvienne"],
                duration: "7 jours / 6 nuits",
                season: "Juin à Septembre"
            }
        ];

        // Éléments DOM
        const destinationsContainer = document.getElementById('destinations-container');
        const filterButtons = document.querySelectorAll('.filter-btn');
        const modal = document.getElementById('modal');
        const closeModal = document.getElementById('close-modal');
        const modalContent = document.getElementById('modal-content');

        // Variables d'état
        let currentFilter = 'all';
        let currentDestination = null;

        // Initialisation
        document.addEventListener('DOMContentLoaded', function() {
            renderDestinations(destinations);
            setupFilters();
            setupModal();
            activateStaggerAnimation();
        });

        // Rendu des destinations
        function renderDestinations(destinationsArray) {
            destinationsContainer.innerHTML = '';
            
            destinationsArray.forEach(destination => {
                const card = document.createElement('div');
                card.className = `destination-card ${destination.region}`;
                card.dataset.id = destination.id;
                
                card.innerHTML = `
                    <div class="card-image">
                        <img src="${destination.image}" alt="${destination.title}">
                        <div class="card-badge">${destination.region.toUpperCase()}</div>
                    </div>
                    <div class="card-content">
                        <h3>${destination.title}</h3>
                        <div class="card-location">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>${destination.location}</span>
                        </div>
                        <p class="card-description">${destination.description.substring(0, 120)}...</p>
                        <div class="card-price">$${destination.price} <span>par personne</span></div>
                        <div class="card-actions">
                            <button class="btn-details" data-id="${destination.id}">Plus de détails</button>
                            <button class="btn-reserve" data-id="${destination.id}">Réserver</button>
                        </div>
                    </div>
                `;
                
                destinationsContainer.appendChild(card);
            });
            
            // Ajouter les écouteurs d'événements aux boutons
            document.querySelectorAll('.btn-details, .btn-reserve').forEach(button => {
                button.addEventListener('click', function() {
                    const id = parseInt(this.dataset.id);
                    const destination = destinations.find(d => d.id === id);
                    
                    if (this.classList.contains('btn-reserve')) {
                        openModal(destination, true);
                    } else {
                        openModal(destination, false);
                    }
                });
            });
        }

        // Configuration des filtres
        function setupFilters() {
            filterButtons.forEach(button => {
                button.addEventListener('click', function() {
                    // Mettre à jour l'état actif
                    filterButtons.forEach(btn => btn.classList.remove('active'));
                    this.classList.add('active');
                    
                    // Filtrer les destinations
                    currentFilter = this.dataset.filter;
                    
                    let filteredDestinations;
                    if (currentFilter === 'all') {
                        filteredDestinations = destinations;
                    } else {
                        filteredDestinations = destinations.filter(d => d.region === currentFilter);
                    }
                    
                    renderDestinations(filteredDestinations);
                    activateStaggerAnimation();
                });
            });
        }

        // Configuration de la modal
        function setupModal() {
            closeModal.addEventListener('click', function() {
                modal.classList.remove('active');
            });
            
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    modal.classList.remove('active');
                }
            });
            
            // Fermer la modal avec la touche Échap
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    modal.classList.remove('active');
                }
            });
        }

        // Ouvrir la modal
        function openModal(destination, showForm = false) {
            currentDestination = destination;
            
            let formHTML = '';
            if (showForm) {
                formHTML = `
                    <div class="booking-form">
                        <h4>Réserver maintenant</h4>
                        <form id="reservation-form">
                            <div class="form-group">
                                <label for="booking-date">Date de départ préférée:</label>
                                <input type="date" id="booking-date" required>
                            </div>
                            <div class="form-group">
                                <label for="travelers">Nombre de voyageurs:</label>
                                <select id="travelers" required>
                                    <option value="1">1 voyageur</option>
                                    <option value="2" selected>2 voyageurs</option>
                                    <option value="3">3 voyageurs</option>
                                    <option value="4">4 voyageurs</option>
                                    <option value="5+">5+ voyageurs</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="full-name">Nom complet:</label>
                                <input type="text" id="full-name" placeholder="Votre nom complet" required>
                            </div>
                            <div class="form-group">
                                <label for="email">Adresse e-mail:</label>
                                <input type="email" id="email" placeholder="votre@email.com" required>
                            </div>
                            <button type="submit" class="btn-submit">Confirmer la réservation</button>
                        </form>
                    </div>
                `;
            } else {
                formHTML = `
                    <div class="booking-form">
                        <button class="btn-submit" id="show-booking-form">Réserver maintenant</button>
                    </div>
                `;
            }
            
            modalContent.innerHTML = `
                <div class="modal-media">
                    <img src="${destination.image}" alt="${destination.title}">
                    <div class="video-container">
                        <iframe src="${destination.video}" title="Vidéo de ${destination.title}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                    </div>
                </div>
                <div class="modal-info">
                    <h3>${destination.title}</h3>
                    <div class="card-location">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>${destination.location}</span>
                    </div>
                    <p class="card-description">${destination.description}</p>
                    
                    <div class="modal-price">$${destination.price} <span>par personne</span></div>
                    
                    <div class="highlights">
                        <h4>Points forts:</h4>
                        <ul>
                            ${destination.highlights.map(h => `<li>${h}</li>`).join('')}
                        </ul>
                    </div>
                    
                    <div class="details">
                        <p><strong>Durée:</strong> ${destination.duration}</p>
                        <p><strong>Meilleure saison:</strong> ${destination.season}</p>
                    </div>
                    
                    ${formHTML}
                </div>
            `;
            
            modal.classList.add('active');
            
            // Ajouter l'écouteur pour le formulaire de réservation
            if (showForm) {
                document.getElementById('reservation-form').addEventListener('submit', function(e) {
                    e.preventDefault();
                    alert(`Merci pour votre réservation pour ${destination.title}! Nous vous contacterons sous peu pour confirmer les détails.`);
                    modal.classList.remove('active');
                });
            } else {
                const showBookingBtn = document.getElementById('show-booking-form');
                if (showBookingBtn) {
                    showBookingBtn.addEventListener('click', function() {
                        openModal(destination, true);
                    });
                }
            }
        }

        // Animation en cascade
        function activateStaggerAnimation() {
            const staggerContainer = document.querySelector('.stagger-animation');
            staggerContainer.classList.remove('animated');
            
            // Forcer un reflow
            void staggerContainer.offsetWidth;
            
            staggerContainer.classList.add('animated');
        }

        // Animation au défilement
        function onScroll() {
            const cards = document.querySelectorAll('.destination-card');
            const windowHeight = window.innerHeight;
            
            cards.forEach(card => {
                const cardPosition = card.getBoundingClientRect().top;
                if (cardPosition < windowHeight - 100) {
                    card.style.animationPlayState = 'running';
                }
            });
        }

        window.addEventListener('scroll', onScroll);
        // Déclencher une première vérification au chargement
        setTimeout(onScroll, 300);
    </script>
    <script>
function sendHeight() {
    const height = document.body.scrollHeight;
    window.parent.postMessage({
        type: 'setHeight',
        iframeId: 'iframe-page-travel-2',
        height: height
    }, '*');
}

window.onload = sendHeight;
window.onresize = sendHeight;
</script>
<script>
const params = new URLSearchParams(window.location.search);
const iframeId = params.get('iframeId');

let resizeTimeout = null;

function sendHeight() {
    clearTimeout(resizeTimeout);
    resizeTimeout = setTimeout(() => {
        const height = document.body.scrollHeight;
        window.parent.postMessage({
            type: 'setHeight',
            iframeId: iframeId,
            height: height
        }, '*');
    }, 100); // 100ms smooth
}

window.addEventListener('load', sendHeight);
window.addEventListener('resize', sendHeight);
</script>

</body>
</html>