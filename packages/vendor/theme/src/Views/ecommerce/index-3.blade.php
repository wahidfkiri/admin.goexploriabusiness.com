<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prestige Immobilier | Biens d'Exception</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <style>
        /* Variables CSS - Palette de couleurs premium */
        :root {
            --gold: #D4AF37;
            --gold-light: #F4E8C1;
            --dark-navy: #0A1A2F;
            --navy: #1A365D;
            --light-navy: #2D4A7C;
            --cream: #F8F5F0;
            --gray: #6B7280;
            --light-gray: #E5E7EB;
            --white: #FFFFFF;
            --shadow: 0 20px 60px rgba(10, 26, 47, 0.15);
            --shadow-light: 0 10px 30px rgba(10, 26, 47, 0.08);
            --radius: 16px;
            --transition-slow: 0.8s cubic-bezier(0.2, 0.8, 0.2, 1);
            --transition-medium: 0.5s cubic-bezier(0.2, 0.8, 0.2, 1);
            --transition-fast: 0.3s ease;
        }

        /* Reset et styles de base */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--cream);
            color: var(--dark-navy);
            line-height: 1.7;
            overflow-x: hidden;
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* En-tête avec animation */
        .header {
            text-align: center;
            padding: 80px 0 60px;
            position: relative;
            overflow: hidden;
        }

        .header::before {
            content: '';
            position: absolute;
            top: -100px;
            left: 50%;
            transform: translateX(-50%);
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, var(--gold-light) 0%, transparent 70%);
            opacity: 0.4;
            z-index: -1;
            animation: pulse-glow 8s infinite alternate;
        }

        .title-wrapper {
            position: relative;
            display: inline-block;
            margin-bottom: 20px;
        }

        .title-wrapper::after {
            content: '';
            position: absolute;
            bottom: -15px;
            left: 50%;
            transform: translateX(-50%);
            width: 120px;
            height: 3px;
            background: linear-gradient(90deg, transparent, var(--gold), transparent);
            animation: line-grow 1.5s 0.5s both;
        }

        h1 {
            font-family: 'Playfair Display', serif;
            font-size: 3.5rem;
            font-weight: 700;
            color: var(--dark-navy);
            margin-bottom: 10px;
            opacity: 0;
            transform: translateY(30px);
            animation: fade-up 1s 0.2s forwards;
        }

        .subtitle {
            font-size: 1.2rem;
            color: var(--gray);
            max-width: 700px;
            margin: 0 auto;
            opacity: 0;
            transform: translateY(20px);
            animation: fade-up 1s 0.4s forwards;
        }

        /* Filtres avec animations */
        .filters-container {
            margin-bottom: 60px;
            opacity: 0;
            animation: fade-up 1s 0.6s forwards;
        }

        .filters {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 15px;
            padding: 25px;
            background: var(--white);
            border-radius: var(--radius);
            box-shadow: var(--shadow-light);
            max-width: 900px;
            margin: 0 auto;
            position: relative;
            overflow: hidden;
        }

        .filters::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background: linear-gradient(90deg, var(--gold), var(--navy));
            transform: translateX(-100%);
            animation: slide-in 1s 0.8s forwards;
        }

        .filter-btn {
            padding: 14px 28px;
            background: transparent;
            border: 2px solid var(--light-gray);
            border-radius: 50px;
            font-family: 'Inter', sans-serif;
            font-weight: 600;
            font-size: 1rem;
            color: var(--navy);
            cursor: pointer;
            transition: var(--transition-medium);
            position: relative;
            overflow: hidden;
            z-index: 1;
        }

        .filter-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, var(--navy), var(--light-navy));
            z-index: -1;
            transform: scaleX(0);
            transform-origin: right;
            transition: transform var(--transition-medium);
            border-radius: 50px;
        }

        .filter-btn:hover {
            color: var(--white);
            border-color: var(--navy);
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(26, 54, 93, 0.15);
        }

        .filter-btn:hover::before {
            transform: scaleX(1);
            transform-origin: left;
        }

        .filter-btn.active {
            color: var(--white);
            border-color: var(--navy);
            background: linear-gradient(135deg, var(--navy), var(--light-navy));
            box-shadow: 0 10px 20px rgba(26, 54, 93, 0.2);
        }

        .filter-btn.active:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 30px rgba(26, 54, 93, 0.25);
        }

        /* Grille de produits avec animations */
        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
            gap: 40px;
            margin-bottom: 80px;
            opacity: 0;
            animation: fade-up 1s 0.8s forwards;
        }

        .product-card {
            background: var(--white);
            border-radius: var(--radius);
            overflow: hidden;
            box-shadow: var(--shadow-light);
            transition: var(--transition-medium);
            position: relative;
            opacity: 0;
            transform: translateY(40px) scale(0.95);
            animation: card-appear 0.8s forwards;
        }

        .product-card:nth-child(1) { animation-delay: 0.9s; }
        .product-card:nth-child(2) { animation-delay: 1s; }
        .product-card:nth-child(3) { animation-delay: 1.1s; }
        .product-card:nth-child(4) { animation-delay: 1.2s; }
        .product-card:nth-child(5) { animation-delay: 1.3s; }
        .product-card:nth-child(6) { animation-delay: 1.4s; }

        .product-card:hover {
            transform: translateY(-15px);
            box-shadow: var(--shadow);
        }

        .card-badge {
            position: absolute;
            top: 25px;
            left: 25px;
            background: linear-gradient(135deg, var(--gold), #B8941F);
            color: var(--dark-navy);
            padding: 8px 18px;
            border-radius: 50px;
            font-weight: 700;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
            z-index: 2;
            box-shadow: 0 5px 15px rgba(212, 175, 55, 0.3);
            transform: translateY(-10px);
            opacity: 0;
            animation: badge-appear 0.5s 1.5s forwards;
        }

        .card-badge.premium {
            background: linear-gradient(135deg, var(--gold), var(--navy));
            color: var(--white);
        }

        .image-container {
            height: 260px;
            overflow: hidden;
            position: relative;
        }

        .image-container::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(to bottom, transparent 60%, rgba(10, 26, 47, 0.7));
            z-index: 1;
            opacity: 0;
            transition: var(--transition-medium);
        }

        .product-card:hover .image-container::after {
            opacity: 1;
        }

        .property-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: var(--transition-slow);
            transform: scale(1.05);
        }

        .product-card:hover .property-image {
            transform: scale(1.15);
        }

        .card-content {
            padding: 30px;
            position: relative;
        }

        .price-tag {
            font-family: 'Playfair Display', serif;
            font-size: 2.2rem;
            font-weight: 700;
            color: var(--navy);
            margin-bottom: 10px;
            display: flex;
            align-items: center;
        }

        .price-tag::before {
            content: '';
            display: inline-block;
            width: 30px;
            height: 2px;
            background: var(--gold);
            margin-right: 15px;
            transition: var(--transition-medium);
        }

        .product-card:hover .price-tag::before {
            width: 50px;
        }

        .property-title {
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--dark-navy);
            margin-bottom: 15px;
            line-height: 1.4;
            transition: var(--transition-medium);
        }

        .product-card:hover .property-title {
            color: var(--light-navy);
        }

        .property-location {
            display: flex;
            align-items: center;
            color: var(--gray);
            margin-bottom: 25px;
            font-size: 1rem;
        }

        .property-location i {
            margin-right: 10px;
            color: var(--gold);
            font-size: 1.1rem;
        }

        .property-features {
            display: flex;
            justify-content: space-between;
            border-top: 1px solid var(--light-gray);
            padding-top: 25px;
        }

        .feature {
            text-align: center;
            position: relative;
        }

        .feature::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 60px;
            height: 60px;
            background: var(--gold-light);
            border-radius: 50%;
            z-index: -1;
            opacity: 0;
            transition: var(--transition-medium);
        }

        .feature:hover::before {
            opacity: 1;
        }

        .feature-icon {
            font-size: 1.4rem;
            color: var(--navy);
            margin-bottom: 8px;
            transition: var(--transition-medium);
        }

        .feature:hover .feature-icon {
            color: var(--gold);
            transform: scale(1.2);
        }

        .feature-value {
            font-weight: 700;
            font-size: 1.2rem;
            color: var(--dark-navy);
            margin-bottom: 3px;
        }

        .feature-label {
            font-size: 0.85rem;
            color: var(--gray);
        }

        .view-details {
            display: block;
            width: 100%;
            padding: 18px;
            background: linear-gradient(135deg, var(--navy), var(--light-navy));
            color: var(--white);
            border: none;
            font-family: 'Inter', sans-serif;
            font-weight: 600;
            font-size: 1rem;
            letter-spacing: 0.5px;
            cursor: pointer;
            transition: var(--transition-medium);
            text-align: center;
            text-decoration: none;
            position: relative;
            overflow: hidden;
        }

        .view-details::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: var(--transition-medium);
        }

        .view-details:hover::before {
            left: 100%;
        }

        .view-details:hover {
            background: linear-gradient(135deg, var(--light-navy), var(--navy));
            letter-spacing: 1px;
        }

        /* Footer avec animation */
        .footer {
            text-align: center;
            padding: 40px 0;
            color: var(--gray);
            font-size: 0.95rem;
            border-top: 1px solid var(--light-gray);
            margin-top: 40px;
            opacity: 0;
            animation: fade-up 1s 1.6s forwards;
        }

        .footer-highlight {
            color: var(--navy);
            font-weight: 600;
        }

        /* Animations CSS */
        @keyframes fade-up {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes line-grow {
            0% {
                width: 0;
                opacity: 0;
            }
            100% {
                width: 120px;
                opacity: 1;
            }
        }

        @keyframes slide-in {
            to {
                transform: translateX(0);
            }
        }

        @keyframes pulse-glow {
            0%, 100% {
                opacity: 0.4;
                transform: translateX(-50%) scale(1);
            }
            50% {
                opacity: 0.6;
                transform: translateX(-50%) scale(1.1);
            }
        }

        @keyframes card-appear {
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        @keyframes badge-appear {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-10px);
            }
        }

        @keyframes shimmer {
            0% {
                background-position: -1000px 0;
            }
            100% {
                background-position: 1000px 0;
            }
        }

        /* Effets spéciaux pour les cartes premium */
        .product-card.premium {
            box-shadow: 0 20px 60px rgba(212, 175, 55, 0.15);
            border: 1px solid var(--gold-light);
        }

        .product-card.premium:hover {
            box-shadow: 0 30px 80px rgba(212, 175, 55, 0.25);
            animation: float 3s infinite ease-in-out;
        }

        /* Responsive */
        @media (max-width: 1200px) {
            .products-grid {
                grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
                gap: 30px;
            }
        }

        @media (max-width: 768px) {
            h1 {
                font-size: 2.8rem;
            }
            
            .products-grid {
                grid-template-columns: 1fr;
                max-width: 500px;
                margin-left: auto;
                margin-right: auto;
            }
            
            .filters {
                padding: 20px;
            }
            
            .filter-btn {
                padding: 12px 24px;
                font-size: 0.95rem;
            }
        }

        @media (max-width: 480px) {
            h1 {
                font-size: 2.2rem;
            }
            
            .subtitle {
                font-size: 1rem;
            }
            
            .price-tag {
                font-size: 1.8rem;
            }
            
            .property-title {
                font-size: 1.3rem;
            }
            
            .header {
                padding: 60px 0 40px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- En-tête -->
        <header class="header">
            <div class="title-wrapper">
                <h1>Immobilier d'Exception</h1>
            </div>
            <p class="subtitle">Découvrez notre collection exclusive de biens prestigieux à travers le monde, alliant élégance, confort et emplacements uniques.</p>
        </header>

        <!-- Filtres -->
        <div class="filters-container">
            <div class="filters">
                <button class="filter-btn active" data-filter="all">Tous les biens</button>
                <button class="filter-btn" data-filter="villa">Villas</button>
                <button class="filter-btn" data-filter="appartement">Appartements</button>
                <button class="filter-btn" data-filter="maison">Maisons</button>
                <button class="filter-btn" data-filter="luxe">Biens de luxe</button>
                <button class="filter-btn" data-filter="premium">Collection Premium</button>
            </div>
        </div>

        <!-- Grille de produits -->
        <div class="products-grid" id="products-container">
            <!-- Les cartes seront générées par JavaScript -->
        </div>

        <!-- Pied de page -->
        <footer class="footer">
            <p><span class="footer-highlight">Prestige Immobilier</span> © 2023 - Tous droits réservés</p>
            <p>Images réelles de nos propriétés exclusives</p>
        </footer>
    </div>

    <script>
        // Données des biens immobiliers
        const properties = [
            {
                id: 1,
                title: "Villa contemporaine avec vue mer à Saint-Tropez",
                price: "3 850 000 €",
                location: "Saint-Tropez, Côte d'Azur",
                type: "villa",
                category: "premium",
                image: "https://images.unsplash.com/photo-1613977257363-707ba9348227?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1200&q=80",
                bedrooms: 5,
                bathrooms: 4,
                area: "320 m²",
                features: ["Piscine à débordement", "Jardin paysager", "Garage 3 voitures"]
            },
            {
                id: 2,
                title: "Penthouse avec terrasse panoramique sur Monaco",
                price: "8 500 000 €",
                location: "Monaco",
                type: "appartement",
                category: "luxe",
                image: "https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1200&q=80",
                bedrooms: 4,
                bathrooms: 3,
                area: "280 m²",
                features: ["Terrasse 360°", "Salle de sport privée", "Conciergerie 24/7"]
            },
            {
                id: 3,
                title: "Domaine viticole du XVIIIe siècle en Provence",
                price: "5 200 000 €",
                location: "Provence-Alpes-Côte d'Azur",
                type: "maison",
                category: "premium",
                image: "https://images.unsplash.com/photo-1518780664697-55e3ad937233?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1200&q=80",
                bedrooms: 7,
                bathrooms: 5,
                area: "450 m²",
                features: ["Domaine de 12 hectares", "Cave à vin", "Piscine naturelle"]
            },
            {
                id: 4,
                title: "Appartement haussmannien rénové près des Champs-Élysées",
                price: "2 950 000 €",
                location: "8ème arrondissement, Paris",
                type: "appartement",
                category: "luxe",
                image: "https://images.unsplash.com/photo-1493809842364-78817add7ffb?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1200&q=80",
                bedrooms: 3,
                bathrooms: 2,
                area: "180 m²",
                features: ["Hauteur sous plafond 3.5m", "Cheminée en marbre", "Ascenseur privé"]
            },
            {
                id: 5,
                title: "Chalet d'alpage avec vue sur le Mont-Blanc",
                price: "1 850 000 €",
                location: "Chamonix-Mont-Blanc",
                type: "maison",
                category: "luxe",
                image: "https://images.unsplash.com/photo-1448630360428-65456885c650?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1200&q=80",
                bedrooms: 4,
                bathrooms: 3,
                area: "220 m²",
                features: ["Accès direct aux pistes", "Sauna et spa", "Garage ski"]
            },
            {
                id: 6,
                title: "Villa moderne face à la mer à Cannes",
                price: "4 750 000 €",
                location: "Cannes, Alpes-Maritimes",
                type: "villa",
                category: "premium",
                image: "https://images.unsplash.com/photo-1512917774080-9991f1c4c750?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1200&q=80",
                bedrooms: 6,
                bathrooms: 5,
                area: "380 m²",
                features: ["Plage privée", "Héliport", "Cinéma maison"]
            }
        ];

        // Initialisation de la page
        $(document).ready(function() {
            // Générer les cartes initiales
            generatePropertyCards(properties);
            
            // Gestion des filtres
            $('.filter-btn').on('click', function() {
                // Animation du bouton cliqué
                $(this).css('transform', 'scale(0.95)');
                setTimeout(() => {
                    $(this).css('transform', '');
                }, 200);
                
                // Mise à jour des boutons actifs
                $('.filter-btn').removeClass('active');
                $(this).addClass('active');
                
                // Récupération du filtre
                const filter = $(this).data('filter');
                
                // Filtrage des propriétés
                let filteredProperties;
                if (filter === 'all') {
                    filteredProperties = properties;
                } else if (filter === 'premium') {
                    filteredProperties = properties.filter(p => p.category === 'premium');
                } else {
                    filteredProperties = properties.filter(p => p.type === filter);
                }
                
                // Animation de sortie des cartes
                $('.product-card').each(function(index) {
                    $(this).css({
                        'opacity': '0',
                        'transform': 'translateY(40px) scale(0.95)',
                        'transition': 'transform 0.5s cubic-bezier(0.2, 0.8, 0.2, 1), opacity 0.5s ease'
                    });
                });
                
                // Génération des nouvelles cartes après animation
                setTimeout(() => {
                    generatePropertyCards(filteredProperties);
                }, 300);
            });
            
            // Animation au survol des cartes
            $(document).on('mouseenter', '.product-card', function() {
                $(this).css('z-index', '10');
            }).on('mouseleave', '.product-card', function() {
                $(this).css('z-index', '');
            });
            
            // Gestion du clic sur "Voir les détails"
            $(document).on('click', '.view-details', function(e) {
                e.preventDefault();
                const card = $(this).closest('.product-card');
                const propertyId = parseInt(card.data('id'));
                const property = properties.find(p => p.id === propertyId);
                
                // Animation de clic
                card.css('transform', 'translateY(-15px) scale(0.98)');
                setTimeout(() => {
                    card.css('transform', 'translateY(-15px) scale(1)');
                }, 150);
                
                // Simulation d'une modal (simplifiée)
                showPropertyDetails(property);
            });
        });

        // Fonction pour générer les cartes de propriétés
        function generatePropertyCards(propertiesArray) {
            const container = $('#products-container');
            container.empty();
            
            if (propertiesArray.length === 0) {
                container.html('<div class="no-results" style="text-align: center; padding: 60px; grid-column: 1/-1; color: var(--gray);"><i class="fas fa-search fa-3x" style="margin-bottom: 20px; opacity: 0.5;"></i><h3 style="font-weight: 500;">Aucun bien ne correspond à ces critères</h3></div>');
                return;
            }
            
            propertiesArray.forEach((property, index) => {
                const cardClass = property.category === 'premium' ? 'product-card premium' : 'product-card';
                
                const card = `
                    <div class="${cardClass}" data-id="${property.id}" data-type="${property.type}" data-category="${property.category}">
                        ${property.category === 'premium' ? '<div class="card-badge premium">PREMIUM</div>' : '<div class="card-badge">EXCLUSIF</div>'}
                        <div class="image-container">
                            <img src="${property.image}" alt="${property.title}" class="property-image" loading="lazy">
                        </div>
                        <div class="card-content">
                            <div class="price-tag">${property.price}</div>
                            <h3 class="property-title">${property.title}</h3>
                            <div class="property-location">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>${property.location}</span>
                            </div>
                            <div class="property-features">
                                <div class="feature">
                                    <div class="feature-icon"><i class="fas fa-bed"></i></div>
                                    <div class="feature-value">${property.bedrooms}</div>
                                    <div class="feature-label">Chambres</div>
                                </div>
                                <div class="feature">
                                    <div class="feature-icon"><i class="fas fa-bath"></i></div>
                                    <div class="feature-value">${property.bathrooms}</div>
                                    <div class="feature-label">Salles de bain</div>
                                </div>
                                <div class="feature">
                                    <div class="feature-icon"><i class="fas fa-vector-square"></i></div>
                                    <div class="feature-value">${property.area}</div>
                                    <div class="feature-label">Surface</div>
                                </div>
                            </div>
                        </div>
                        <a href="#" class="view-details">Voir les détails <i class="fas fa-arrow-right"></i></a>
                    </div>
                `;
                
                container.append(card);
                
                // Animation d'entrée avec délai
                setTimeout(() => {
                    $(container).find(`.product-card[data-id="${property.id}"]`).css({
                        'opacity': '1',
                        'transform': 'translateY(0) scale(1)'
                    });
                }, index * 100);
            });
        }

        // Fonction pour afficher les détails d'une propriété
        function showPropertyDetails(property) {
            // Création d'un overlay pour les détails
            const overlay = $('<div class="property-overlay"></div>');
            const detailsModal = $(`
                <div class="property-details-modal">
                    <div class="modal-header">
                        <h2>${property.title}</h2>
                        <button class="close-modal"><i class="fas fa-times"></i></button>
                    </div>
                    <div class="modal-image">
                        <img src="${property.image}" alt="${property.title}">
                    </div>
                    <div class="modal-content">
                        <div class="modal-price">${property.price}</div>
                        <div class="modal-location"><i class="fas fa-map-marker-alt"></i> ${property.location}</div>
                        <div class="modal-features">
                            <div class="modal-feature"><i class="fas fa-bed"></i> ${property.bedrooms} Chambres</div>
                            <div class="modal-feature"><i class="fas fa-bath"></i> ${property.bathrooms} Salles de bain</div>
                            <div class="modal-feature"><i class="fas fa-vector-square"></i> ${property.area}</div>
                        </div>
                        <div class="modal-description">
                            <h3>Caractéristiques exclusives</h3>
                            <ul>
                                ${property.features.map(feature => `<li><i class="fas fa-check"></i> ${feature}</li>`).join('')}
                            </ul>
                        </div>
                        <button class="contact-btn"><i class="fas fa-phone-alt"></i> Contacter un conseiller</button>
                    </div>
                </div>
            `);
            
            // Styles pour l'overlay et la modal
            const modalStyles = `
                <style>
                    .property-overlay {
                        position: fixed;
                        top: 0;
                        left: 0;
                        width: 100%;
                        height: 100%;
                        background: rgba(10, 26, 47, 0.9);
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        z-index: 1000;
                        opacity: 0;
                        animation: overlay-fade 0.4s forwards;
                        padding: 20px;
                    }
                    
                    @keyframes overlay-fade {
                        to { opacity: 1; }
                    }
                    
                    .property-details-modal {
                        background: var(--white);
                        border-radius: var(--radius);
                        width: 100%;
                        max-width: 800px;
                        max-height: 90vh;
                        overflow-y: auto;
                        transform: translateY(50px);
                        opacity: 0;
                        animation: modal-slide 0.5s 0.2s forwards;
                        box-shadow: 0 30px 100px rgba(0, 0, 0, 0.4);
                    }
                    
                    @keyframes modal-slide {
                        to { 
                            opacity: 1;
                            transform: translateY(0);
                        }
                    }
                    
                    .modal-header {
                        display: flex;
                        justify-content: space-between;
                        align-items: center;
                        padding: 25px 30px;
                        border-bottom: 1px solid var(--light-gray);
                    }
                    
                    .modal-header h2 {
                        font-family: 'Playfair Display', serif;
                        font-size: 1.8rem;
                        color: var(--dark-navy);
                        margin: 0;
                    }
                    
                    .close-modal {
                        background: none;
                        border: none;
                        font-size: 1.5rem;
                        color: var(--gray);
                        cursor: pointer;
                        transition: var(--transition-fast);
                        width: 40px;
                        height: 40px;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        border-radius: 50%;
                    }
                    
                    .close-modal:hover {
                        background: var(--light-gray);
                        color: var(--dark-navy);
                    }
                    
                    .modal-image {
                        height: 350px;
                        overflow: hidden;
                    }
                    
                    .modal-image img {
                        width: 100%;
                        height: 100%;
                        object-fit: cover;
                    }
                    
                    .modal-content {
                        padding: 30px;
                    }
                    
                    .modal-price {
                        font-family: 'Playfair Display', serif;
                        font-size: 2.2rem;
                        font-weight: 700;
                        color: var(--navy);
                        margin-bottom: 10px;
                    }
                    
                    .modal-location {
                        display: flex;
                        align-items: center;
                        color: var(--gray);
                        margin-bottom: 25px;
                        font-size: 1.1rem;
                    }
                    
                    .modal-location i {
                        margin-right: 10px;
                        color: var(--gold);
                    }
                    
                    .modal-features {
                        display: flex;
                        gap: 30px;
                        margin-bottom: 30px;
                        padding-bottom: 30px;
                        border-bottom: 1px solid var(--light-gray);
                    }
                    
                    .modal-feature {
                        display: flex;
                        align-items: center;
                        font-weight: 600;
                        color: var(--dark-navy);
                    }
                    
                    .modal-feature i {
                        margin-right: 10px;
                        color: var(--gold);
                        font-size: 1.2rem;
                    }
                    
                    .modal-description h3 {
                        font-family: 'Playfair Display', serif;
                        font-size: 1.4rem;
                        margin-bottom: 20px;
                        color: var(--dark-navy);
                    }
                    
                    .modal-description ul {
                        list-style: none;
                        margin-bottom: 30px;
                    }
                    
                    .modal-description li {
                        padding: 8px 0;
                        display: flex;
                        align-items: center;
                    }
                    
                    .modal-description li i {
                        color: var(--gold);
                        margin-right: 15px;
                    }
                    
                    .contact-btn {
                        width: 100%;
                        padding: 18px;
                        background: linear-gradient(135deg, var(--navy), var(--light-navy));
                        color: var(--white);
                        border: none;
                        border-radius: var(--radius);
                        font-family: 'Inter', sans-serif;
                        font-weight: 600;
                        font-size: 1.1rem;
                        cursor: pointer;
                        transition: var(--transition-medium);
                        display: flex;
                        align-items: center;
                        justify-content: center;
                    }
                    
                    .contact-btn i {
                        margin-right: 10px;
                    }
                    
                    .contact-btn:hover {
                        background: linear-gradient(135deg, var(--light-navy), var(--navy));
                        letter-spacing: 0.5px;
                    }
                    
                    @media (max-width: 768px) {
                        .modal-features {
                            flex-direction: column;
                            gap: 15px;
                        }
                        
                        .modal-header h2 {
                            font-size: 1.5rem;
                        }
                        
                        .modal-price {
                            font-size: 1.8rem;
                        }
                        
                        .modal-image {
                            height: 250px;
                        }
                    }
                </style>
            `;
            
            // Ajout des styles et des éléments au DOM
            $('head').append(modalStyles);
            overlay.append(detailsModal);
            $('body').append(overlay);
            
            // Gestion de la fermeture
            overlay.on('click', function(e) {
                if ($(e.target).hasClass('property-overlay') || $(e.target).hasClass('close-modal') || $(e.target).parent().hasClass('close-modal')) {
                    overlay.css({
                        'opacity': '0',
                        'transition': 'opacity 0.3s ease'
                    });
                    detailsModal.css({
                        'transform': 'translateY(50px)',
                        'opacity': '0',
                        'transition': 'transform 0.3s ease, opacity 0.3s ease'
                    });
                    
                    setTimeout(() => {
                        overlay.remove();
                        $(modalStyles).remove();
                    }, 300);
                }
            });
            
            // Gestion du bouton de contact
            detailsModal.find('.contact-btn').on('click', function() {
                alert(`Merci pour votre intérêt pour "${property.title}".\n\nUn conseiller vous contactera dans les plus brefs délais.`);
                overlay.trigger('click');
            });
        }
    </script>
    <script>
function sendHeight() {
    const height = document.body.scrollHeight;
    window.parent.postMessage({
        type: 'setHeight',
        iframeId: 'iframe-page-3',
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