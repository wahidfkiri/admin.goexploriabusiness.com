<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Go Exploria Business - Plateforme de Création Digitale</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800&family=Open+Sans:wght@300;400;600&display=swap" rel="stylesheet">
    
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Styles personnalisés -->
    <style>
        :root {
            --primary: #2a5bd7;
            --primary-dark: #1a3fa0;
            --secondary: #00c9b7;
            --dark: #1a1d28;
            --light: #f8f9fa;
            --gray: #6c757d;
            --light-gray: #e9ecef;
            --transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.1);
            --shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            --shadow-hover: 0 20px 40px rgba(0, 0, 0, 0.12);
        }

        body {
            background-color: #f5f7ff;
            color: var(--dark);
            font-family: 'Montserrat', 'Segoe UI', system-ui, sans-serif;
            line-height: 1.6;
        }

        .section-header {
            text-align: center;
            margin: 60px 0;
            padding: 0 20px;
            opacity: 0;
            transform: translateY(30px);
            animation: fadeUp 0.8s forwards 0.3s;
        }

        .section-tag {
            display: inline-block;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
            color: white;
            padding: 8px 20px;
            border-radius: 50px;
            font-size: 0.9rem;
            font-weight: 600;
            letter-spacing: 1px;
            margin-bottom: 20px;
            box-shadow: var(--shadow);
        }

        .section-title {
            font-size: 3.2rem;
            font-weight: 800;
            margin-bottom: 15px;
            background: linear-gradient(90deg, var(--dark), var(--primary));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            line-height: 1.2;
        }

        .section-subtitle {
            font-size: 1.2rem;
            color: var(--gray);
            max-width: 700px;
            margin: 0 auto;
        }

        /* Section Business & Tourisme */
        .business-tourism-section {
            padding: 80px 0;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            position: relative;
            overflow: hidden;
        }

        .business-tourism-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
        }

        .content-wrapper {
            display: flex;
            flex-wrap: wrap;
            gap: 40px;
            margin-bottom: 60px;
            opacity: 0;
            transform: translateY(30px);
            animation: fadeUp 0.8s forwards 0.6s;
        }

        .info-section {
            flex: 1;
            min-width: 300px;
        }

        .info-card {
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: var(--shadow);
            transition: var(--transition);
            height: 100%;
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(0,0,0,0.05);
        }

        .info-card:hover {
            transform: translateY(-10px);
            box-shadow: var(--shadow-hover);
        }

        .info-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 6px;
            height: 100%;
            background: linear-gradient(to bottom, var(--primary), var(--secondary));
        }

        .info-icon {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 25px;
            color: white;
            font-size: 28px;
            box-shadow: 0 8px 20px rgba(42, 91, 215, 0.25);
        }

        .info-title {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 15px;
            color: var(--dark);
        }

        .info-text {
            color: var(--gray);
            margin-bottom: 25px;
            font-size: 1.05rem;
        }

        .features-list {
            list-style: none;
            padding: 0;
            margin-bottom: 30px;
        }

        .features-list li {
            padding: 10px 0;
            padding-left: 30px;
            position: relative;
            border-bottom: 1px solid var(--light-gray);
        }

        .features-list li:last-child {
            border-bottom: none;
        }

        .features-list li::before {
            content: '\f058';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            position: absolute;
            left: 0;
            color: var(--secondary);
            font-size: 1.2rem;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: linear-gradient(90deg, var(--primary), var(--primary-dark));
            color: white;
            padding: 14px 28px;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 600;
            transition: var(--transition);
            border: none;
            cursor: pointer;
            box-shadow: 0 5px 15px rgba(42, 91, 215, 0.3);
        }

        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(42, 91, 215, 0.4);
            color: white;
        }

        .video-container {
            position: relative;
            width: 100%;
            padding-bottom: 56.25%;
            height: 0;
            border-radius: 12px;
            overflow: hidden;
            margin-top: 20px;
            box-shadow: var(--shadow);
        }

        .video-container iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border: none;
        }

        .stats-section {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
            gap: 30px;
            margin-top: 40px;
            opacity: 0;
            transform: translateY(30px);
            animation: fadeUp 0.8s forwards 0.9s;
        }

        .stat-item {
            text-align: center;
            padding: 25px;
            background: white;
            border-radius: 16px;
            box-shadow: var(--shadow);
            transition: var(--transition);
            min-width: 200px;
        }

        .stat-item:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-hover);
        }

        .stat-number {
            font-size: 3rem;
            font-weight: 800;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            line-height: 1;
            margin-bottom: 10px;
        }

        .stat-label {
            color: var(--gray);
            font-weight: 600;
            font-size: 1.1rem;
        }

        @keyframes fadeUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        .pulse {
            animation: pulse 2s infinite;
        }

        /* Styles pour la carte interactive */
        .app-container {
            width: 100%;
            height: 600px;
            position: relative;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: var(--shadow);
            margin: 40px auto;
        }

        .map-container {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
        }

        #map {
            width: 100%;
            height: 100%;
        }

        .sidebar-right {
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            width: 350px;
            background: white;
            box-shadow: -5px 0 20px rgba(0,0,0,0.1);
            overflow-y: auto;
            transform: translateX(0);
            transition: transform 0.3s ease;
            z-index: 1000;
        }

        .sidebar-toggle {
            position: absolute;
            top: 20px;
            right: 370px;
            z-index: 1001;
            background: white;
            border: none;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            box-shadow: var(--shadow);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: var(--transition);
        }

        .sidebar-toggle:hover {
            background: var(--primary);
            color: white;
        }

        .loading-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255,255,255,0.9);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1002;
        }

        .custom-marker {
            background: transparent;
            border: none;
        }

        .marker-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 18px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.2);
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .marker-icon:hover {
            transform: scale(1.1);
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        }

        .marker-icon.highlighted {
            transform: scale(1.2);
            box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.5);
        }

        .user-marker-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #00c9b7, #2a5bd7);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 22px;
            box-shadow: 0 3px 15px rgba(0,0,0,0.3);
            border: 3px solid white;
        }

        @keyframes userMarkerPulse {
            0% { transform: scale(1); box-shadow: 0 0 0 0 rgba(42, 91, 215, 0.7); }
            70% { transform: scale(1.05); box-shadow: 0 0 0 10px rgba(42, 91, 215, 0); }
            100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(42, 91, 215, 0); }
        }

        .user-marker-icon {
            animation: userMarkerPulse 2s infinite;
        }

        .places-list {
            padding: 20px;
        }

        .place-item {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            margin-bottom: 20px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.08);
            transition: var(--transition);
            cursor: pointer;
        }

        .place-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }

        .place-item.active {
            border: 2px solid var(--primary);
            box-shadow: 0 5px 20px rgba(42, 91, 215, 0.3);
        }

        .place-image {
            height: 150px;
            overflow: hidden;
        }

        .place-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .place-item:hover .place-image img {
            transform: scale(1.05);
        }

        .place-info {
            padding: 15px;
        }

        .place-info h4 {
            margin: 0 0 10px 0;
            font-size: 1.2rem;
            color: var(--dark);
        }

        .place-category {
            display: inline-block;
            padding: 4px 12px;
            background: var(--primary);
            color: white;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
            margin-bottom: 10px;
        }

        .place-description {
            color: var(--gray);
            font-size: 0.9rem;
            margin-bottom: 15px;
            line-height: 1.4;
        }

        .place-actions {
            display: flex;
            gap: 10px;
        }

        .place-actions button {
            flex: 1;
            padding: 8px 12px;
            border: none;
            border-radius: 6px;
            font-size: 0.9rem;
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
        }

        .filters-section {
            padding: 20px;
            border-bottom: 1px solid var(--light-gray);
        }

        .filter-group {
            margin-bottom: 15px;
        }

        .filter-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
            color: var(--dark);
        }

        .form-select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 0.9rem;
            transition: var(--transition);
        }

        .form-select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(42, 91, 215, 0.1);
        }

        .locate-btn {
            width: 100%;
            padding: 12px;
            background: linear-gradient(90deg, var(--primary), var(--primary-dark));
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 500;
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .locate-btn:hover {
            background: linear-gradient(90deg, var(--primary-dark), var(--primary));
            transform: translateY(-2px);
        }

        .stats {
            text-align: center;
            padding: 15px;
            background: var(--light-gray);
            border-radius: 8px;
            margin-top: 15px;
        }

        .stats p {
            margin: 0;
            font-size: 0.9rem;
            color: var(--dark);
        }

        #places-count {
            font-weight: 700;
            color: var(--primary);
            font-size: 1.1rem;
        }

        .no-results {
            text-align: center;
            padding: 40px 20px;
            color: var(--gray);
        }

        .no-results i {
            font-size: 3rem;
            color: #ddd;
            margin-bottom: 20px;
        }

        .no-results h4 {
            margin-bottom: 10px;
            color: var(--dark);
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.8);
            z-index: 2000;
            overflow-y: auto;
        }

        .modal-content {
            position: relative;
            background: white;
            margin: 50px auto;
            width: 90%;
            max-width: 900px;
            border-radius: 20px;
            overflow: hidden;
            animation: modalSlideIn 0.3s ease;
        }

        @keyframes modalSlideIn {
            from { transform: translateY(-50px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        .close-modal {
            position: absolute;
            top: 20px;
            right: 20px;
            background: rgba(0,0,0,0.5);
            color: white;
            border: none;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            font-size: 20px;
            cursor: pointer;
            z-index: 2001;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: var(--transition);
        }

        .close-modal:hover {
            background: rgba(0,0,0,0.8);
            transform: rotate(90deg);
        }

        /* Styles pour les popups de hover */
        .leaflet-popup {
            margin-bottom: 20px;
            animation: popupFadeIn 0.3s ease;
            pointer-events: auto !important;
        }
        
        .leaflet-popup-content-wrapper {
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.15);
            border: 2px solid var(--primary);
            overflow: hidden;
        }
        
        .leaflet-popup-content {
            margin: 0;
            padding: 0;
            min-width: 280px;
        }
        
        .leaflet-popup-tip-container {
            margin-top: -1px;
        }
        
        .leaflet-popup-tip {
            background: var(--primary);
            box-shadow: none;
        }
        
        .youtube-video-container {
            position: relative;
            width: 100%;
            padding-bottom: 56.25%;
            height: 0;
            overflow: hidden;
            border-radius: 8px;
            margin: 8px 0;
            background: #000;
        }
        
        .youtube-video-container iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border: none;
        }
        
        .youtube-badge {
            position: absolute;
            top: 8px;
            right: 8px;
            background: rgba(255, 0, 0, 0.9);
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: 600;
            z-index: 10;
            display: flex;
            align-items: center;
            gap: 4px;
        }
        
        @keyframes popupFadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive */
        @media (max-width: 1200px) {
            .sidebar-right {
                width: 300px;
            }
            .sidebar-toggle {
                right: 320px;
            }
        }

        @media (max-width: 992px) {
            .content-wrapper {
                flex-direction: column;
            }
            
            .section-title {
                font-size: 2.5rem;
            }
            
            .app-container {
                height: 500px;
            }
        }

        @media (max-width: 768px) {
            .section-title {
                font-size: 2rem;
            }
            
            .app-container {
                height: 400px;
            }
            
            .sidebar-right {
                width: 100%;
                transform: translateX(100%);
            }
            
            .sidebar-right.active {
                transform: translateX(0);
            }
            
            .sidebar-toggle {
                right: 20px;
                top: 20px;
            }
            
            .stat-item {
                min-width: 150px;
                padding: 20px;
            }
            
            .stat-number {
                font-size: 2.5rem;
            }
        }

        @media (max-width: 576px) {
            .info-card {
                padding: 25px;
            }
            
            .info-title {
                font-size: 1.5rem;
            }
            
            .app-container {
                height: 350px;
            }
            
            .modal-content {
                width: 95%;
                margin: 20px auto;
            }
            
            .leaflet-popup-content {
                min-width: 240px;
            }
        }
        /* Correction pour les popups */
.leaflet-popup {
    margin-bottom: 0 !important;
    margin-top: -45px !important; /* Ajuster selon la hauteur du marqueur */
}

.leaflet-popup-content-wrapper {
    transform: none !important;
    position: relative;
    top: 0;
    left: 0;
}

.leaflet-popup-tip-container {
    display: none !important; /* Cacher la pointe si nécessaire */
}

/* Assurer que le popup ne bouge pas */
.leaflet-popup {
    transition: none !important;
    animation: none !important;
}

/* Pour positionner précisément le popup au-dessus du marqueur */
.custom-popup .leaflet-popup-content-wrapper {
    margin-top: -10px;
}
    </style>
</head>
<body>
    <!-- Section Business & Tourisme -->
    <section class="business-tourism-section">
        <div class="container">
            <header class="section-header">
                <div class="section-tag">
                    <i class="fas fa-star"></i> EXCELLENCE PROFESSIONNELLE
                </div>
                <h1 class="section-title">Business & Tourisme</h1>
                <p class="section-subtitle">
                    Découvrez comment nous combinons expertise commerciale et expériences touristiques 
                    pour créer des opportunités uniques et mémorables.
                </p>
            </header>

            <div class="content-wrapper">
                <div class="info-section">
                    <div class="info-card">
                        <div class="info-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <h2 class="info-title">Solutions Business</h2>
                        <p class="info-text">
                            Nous offrons des stratégies sur mesure pour développer votre entreprise, 
                            optimiser vos processus et maximiser votre rentabilité sur le marché international.
                        </p>
                        <ul class="features-list">
                            <li>Consultation stratégique et analyse de marché</li>
                            <li>Développement de partenariats internationaux</li>
                            <li>Optimisation des processus opérationnels</li>
                            <li>Solutions digitales innovantes</li>
                        </ul>
                        
                        <!-- Vidéo YouTube Business -->
                       
                        
                        <button class="btn mt-3">
                            <i class="fas fa-rocket"></i> Découvrir nos solutions
                        </button>
                    </div>
                </div>

                <div class="info-section">
                    <div class="info-card">
                        <div class="info-icon">
                            <i class="fas fa-globe-americas"></i>
                        </div>
                        <h2 class="info-title">Expériences Touristiques</h2>
                        <p class="info-text">
                            Nous concevons des voyages sur mesure qui combinent découvertes culturelles, 
                            aventures uniques et moments de détente pour les professionnels et leurs équipes.
                        </p>
                        <ul class="features-list">
                            <li>Voyages d'affaires sur mesure</li>
                            <li>Retraites d'entreprise en destinations exclusives</li>
                            <li>Team-building aventure et culturel</li>
                            <li>Circuits découverte pour partenaires</li>
                        </ul>
                        
                        <!-- Vidéo YouTube Tourisme -->
                       
                        
                        <button class="btn btn-outline mt-3" style="background: transparent; border: 2px solid var(--primary); color: var(--primary);">
                            <i class="fas fa-map-marked-alt"></i> Explorer nos destinations
                        </button>
                    </div>
                </div>
            </div>

            <div class="stats-section">
                <div class="stat-item">
                    <div class="stat-number" data-count="250">250</div>
                    <div class="stat-label">Projets réalisés</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number" data-count="40">40</div>
                    <div class="stat-label">Pays couverts</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number" data-count="98">98</div>
                    <div class="stat-label">% de satisfaction</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number" data-count="15">15</div>
                    <div class="stat-label">Années d'expérience</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Section Carte Interactive -->
    <div class="container mt-5" id="plans-daffichage-mondial">
        <div class="row">
            <div class="col-lg-12 text-center mb-4">
                <h2 class="section-title">Notre Carte Interactive</h2>
                <p class="section-subtitle">Découvrez nos lieux d'intérêt business et tourisme sur la carte</p>
            </div>
            
            <div class="col-lg-12">
                <div class="app-container">
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
                    
                    <!-- Sidebar à droite -->
                    <div class="sidebar-right" id="sidebarRight">
                        <div class="filters-section">
                            <!-- Sélection de province -->
                            <div class="filter-group">
                                <label for="province-filter">Province/Région :</label>
                                <select id="province-filter" class="form-select">
                                    <option value="">Toutes les provinces</option>
                                    <!-- Toutes les provinces seront chargées dynamiquement -->
                                </select>
                            </div>
                            
                            <!-- Sélection de catégorie -->
                            <div class="filter-group">
                                <label for="category-filter">Catégorie :</label>
                                <select id="category-filter" class="form-select">
                                    <option value="all">Toutes les catégories</option>
                                    <!-- Toutes les catégories seront chargées dynamiquement -->
                                </select>
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
        </div>
    </div>

    <!-- Modal pour les détails -->
    <div id="place-modal" class="modal">
        <div class="modal-content">
            <button class="close-modal">&times;</button>
            <div id="modal-content"></div>
        </div>
    </div>

    <!-- Scripts -->
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Leaflet JS -->
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
            this.selectedCategory = 'all';
            this.selectedProvince = '';
            this.userMarker = null;
            this.activePlace = null;
            
            // Toutes les provinces et territoires du Canada
            this.provinces = [
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
                { code: 'yt', name: 'Yukon', lat: 64.2823, lng: -135.0000 }
            ];
            
            // Toutes les catégories disponibles
            this.categories = [
                'business', 'tourism', 'restaurant', 'hotel', 'museum', 
                'shopping', 'park', 'monument', 'event', 'airport',
                'university', 'hospital', 'beach', 'mountain', 'lake'
            ];
            
            // Données complètes des lieux avec vidéos YouTube
            this.staticPlaces = this.generateCompletePlacesData();
            
            this.init();
        }
        
        generateCompletePlacesData() {
            // IDs de vidéos YouTube réelles pour différentes catégories
            const youtubeVideos = {
                business: ['7Pq-S557XQU', 'g6C3qNRmXz0', 'Bk4KkC3Efdw'],
                tourism: ['videos-tourism-1', 'videos-tourism-2', 'videos-tourism-3'],
                restaurant: ['video-restaurant-1', 'video-restaurant-2', 'video-restaurant-3'],
                hotel: ['video-hotel-1', 'video-hotel-2', 'video-hotel-3'],
                museum: ['video-museum-1', 'video-museum-2', 'video-museum-3'],
                shopping: ['video-shopping-1', 'video-shopping-2', 'video-shopping-3'],
                park: ['video-park-1', 'video-park-2', 'video-park-3'],
                monument: ['video-monument-1', 'video-monument-2', 'video-monument-3'],
                event: ['video-event-1', 'video-event-2', 'video-event-3'],
                airport: ['video-airport-1', 'video-airport-2', 'video-airport-3'],
                university: ['video-university-1', 'video-university-2', 'video-university-3'],
                hospital: ['video-hospital-1', 'video-hospital-2', 'video-hospital-3'],
                beach: ['video-beach-1', 'video-beach-2', 'video-beach-3'],
                mountain: ['video-mountain-1', 'video-mountain-2', 'video-mountain-3'],
                lake: ['video-lake-1', 'video-lake-2', 'video-lake-3']
            };
            
            return [
                // Québec (6 lieux)
                {
                    id: 1,
                    name: 'Centre des Congrès de Québec',
                    description: 'Centre de congrès moderne pour événements d\'affaires et conférences internationales.',
                    latitude: 46.809,
                    longitude: -71.221,
                    category: 'business',
                    address: '1000 Bd René-Lévesque E, Québec, QC G1R 5T8',
                    phone: '+1-418-644-4000',
                    website: 'https://www.quebeccongres.com',
                    video_id: 'jHfjAfPxWSs', // Vidéo YouTube
                    province: 'qc'
                },
                {
                    id: 2,
                    name: 'Château Frontenac',
                    description: 'Hôtel historique emblématique pour événements d\'entreprise et séminaires de luxe.',
                    latitude: 46.8117,
                    longitude: -71.2044,
                    category: 'hotel',
                    address: '1 Rue des Carrières, Québec, QC G1R 4P5',
                    phone: '+1-418-692-3861',
                    website: 'https://www.fairmont.com/frontenac-quebec/',
                    video_id: 'jHfjAfPxWSs', // Vidéo YouTube
                    province: 'qc'
                },
                {
                    id: 3,
                    name: 'Vieux-Port de Montréal',
                    description: 'Destination touristique animée avec boutiques, restaurants et activités culturelles.',
                    latitude: 45.5080,
                    longitude: -73.5525,
                    category: 'tourism',
                    address: '333 Rue de la Commune O, Montréal, QC H2Y 2E2',
                    website: 'https://www.quaysoftheoldport.com',
                    video_id: 'jHfjAfPxWSs', // Vidéo YouTube
                    province: 'qc'
                },
                {
                    id: 4,
                    name: 'Musée des Beaux-Arts de Montréal',
                    description: 'Plus grand musée d\'art du Canada avec collections internationales.',
                    latitude: 45.4988,
                    longitude: -73.5788,
                    category: 'museum',
                    address: '1380 Rue Sherbrooke O, Montréal, QC H3G 1J5',
                    phone: '+1-514-285-2000',
                    website: 'https://www.mbam.qc.ca',
                    video_id: 'jHfjAfPxWSs', // Vidéo YouTube exemple
                    province: 'qc'
                },
                {
                    id: 5,
                    name: 'Restaurant Toqué!',
                    description: 'Restaurant gastronomique étoilé au guide Michelin.',
                    latitude: 45.5042,
                    longitude: -73.5543,
                    category: 'restaurant',
                    address: '900 Place Jean-Paul-Riopelle, Montréal, QC H2Z 2B2',
                    phone: '+1-514-499-2084',
                    website: 'https://restaurant-toque.com',
                    video_id: 'jHfjAfPxWSs', // Vidéo YouTube exemple
                    province: 'qc'
                },
                {
                    id: 6,
                    name: 'Mont Tremblant',
                    description: 'Station de ski et centre de villégiature quatre saisons.',
                    latitude: 46.2127,
                    longitude: -74.5822,
                    category: 'tourism',
                    address: '1000 Chemin des Voyageurs, Mont-Tremblant, QC J8E 1T1',
                    website: 'https://www.tremblant.ca',
                    video_id: 'jHfjAfPxWSs', // Vidéo YouTube exemple
                    province: 'qc'
                },

                // Ontario (6 lieux)
                {
                    id: 7,
                    name: 'Tour CN - Toronto',
                    description: 'Symbole de Toronto, tour de communication avec restaurant tournant.',
                    latitude: 43.6426,
                    longitude: -79.3871,
                    category: 'monument',
                    address: '290 Bremner Blvd, Toronto, ON M5V 3L9',
                    phone: '+1-416-868-6937',
                    website: 'https://www.cntower.ca',
                    video_id: 'jHfjAfPxWSs', // Vidéo YouTube
                    province: 'on'
                },
                {
                    id: 8,
                    name: 'Chutes Niagara',
                    description: 'Chutes d\'eau spectaculaires, attraction touristique majeure.',
                    latitude: 43.0896,
                    longitude: -79.0849,
                    category: 'tourism',
                    address: '6650 Niagara Pkwy, Niagara Falls, ON L2E 6T2',
                    website: 'https://www.niagaraparks.com',
                    video_id: 'jHfjAfPxWSs', // Vidéo YouTube
                    province: 'on'
                },
                {
                    id: 9,
                    name: 'Centre Rogers',
                    description: 'Stade polyvalent avec toit rétractable pour événements sportifs et concerts.',
                    latitude: 43.6414,
                    longitude: -79.3891,
                    category: 'event',
                    address: '1 Blue Jays Way, Toronto, ON M5V 1J1',
                    phone: '+1-416-341-1000',
                    website: 'https://www.rogerscentre.com',
                    video_id: 'jHfjAfPxWSs', // Vidéo YouTube
                    province: 'on'
                },
                {
                    id: 10,
                    name: 'Université de Toronto',
                    description: 'Université de recherche publique, la plus grande du Canada.',
                    latitude: 43.6629,
                    longitude: -79.3957,
                    category: 'university',
                    address: '27 King\'s College Cir, Toronto, ON M5S 1A1',
                    website: 'https://www.utoronto.ca',
                    video_id: 'jHfjAfPxWSs', // Vidéo YouTube exemple
                    province: 'on'
                },
                {
                    id: 11,
                    name: 'Casa Loma',
                    description: 'Château historique et musée avec jardins et passages secrets.',
                    latitude: 43.6780,
                    longitude: -79.4095,
                    category: 'museum',
                    address: '1 Austin Terrace, Toronto, ON M5R 1X8',
                    phone: '+1-416-923-1171',
                    website: 'https://casaloma.ca',
                    video_id: 'jHfjAfPxWSs', // Vidéo YouTube exemple
                    province: 'on'
                },
                {
                    id: 12,
                    name: 'Distillery District',
                    description: 'Quartier historique avec boutiques, galeries et restaurants.',
                    latitude: 43.6525,
                    longitude: -79.3589,
                    category: 'shopping',
                    address: '55 Mill St, Toronto, ON M5A 3C4',
                    website: 'https://www.thedistillerydistrict.com',
                    video_id: 'jHfjAfPxWSs', // Vidéo YouTube exemple
                    province: 'on'
                },

                // Colombie-Britannique (6 lieux)
                {
                    id: 13,
                    name: 'Parc Stanley - Vancouver',
                    description: 'Parc urbain de 405 hectares avec forêt, plages et aquarium.',
                    latitude: 49.3000,
                    longitude: -123.1412,
                    category: 'park',
                    address: 'Stanley Park, Vancouver, BC V6G 1Z4',
                    website: 'https://vancouver.ca/parks-recreation-culture/stanley-park.aspx',
                    video_id: 'jHfjAfPxWSs', // Vidéo YouTube
                    province: 'bc'
                },
                {
                    id: 14,
                    name: 'Banff Centre',
                    description: 'Centre artistique et de conférences en montagne pour événements d\'entreprise.',
                    latitude: 51.1784,
                    longitude: -115.5708,
                    category: 'business',
                    address: '107 Tunnel Mountain Dr, Banff, AB T1L 1H5',
                    phone: '+1-403-762-6100',
                    website: 'https://www.banffcentre.ca',
                    video_id: 'jHfjAfPxWSs', // Vidéo YouTube
                    province: 'ab'
                },
                {
                    id: 15,
                    name: 'Whistler Blackcomb',
                    description: 'Plus grande station de ski d\'Amérique du Nord.',
                    latitude: 50.1163,
                    longitude: -122.9574,
                    category: 'tourism',
                    address: '4545 Blackcomb Way, Whistler, BC V8E 0X9',
                    website: 'https://www.whistlerblackcomb.com',
                    video_id: 'jHfjAfPxWSs', // Vidéo YouTube
                    province: 'bc'
                },
                {
                    id: 16,
                    name: 'Hôtel Fairmont Empress',
                    description: 'Hôtel historique emblématique de Victoria.',
                    latitude: 48.4222,
                    longitude: -123.3677,
                    category: 'hotel',
                    address: '721 Government St, Victoria, BC V8W 1W5',
                    phone: '+1-250-384-8111',
                    website: 'https://www.fairmont.com/empress-victoria/',
                    video_id: 'jHfjAfPxWSs', // Vidéo YouTube exemple
                    province: 'bc'
                },
                {
                    id: 17,
                    name: 'Vancouver Convention Centre',
                    description: 'Centre de congrès écologique avec vue sur l\'océan.',
                    latitude: 49.2886,
                    longitude: -123.1112,
                    category: 'business',
                    address: '1055 Canada Pl, Vancouver, BC V6C 0C3',
                    phone: '+1-604-689-8232',
                    website: 'https://www.vancouverconventioncentre.com',
                    video_id: 'jHfjAfPxWSs', // Vidéo YouTube exemple
                    province: 'bc'
                },
                {
                    id: 18,
                    name: 'Plage de Kitsilano',
                    description: 'Plage populaire avec piscine d\'eau salée et terrains de sport.',
                    latitude: 49.2750,
                    longitude: -123.1558,
                    category: 'beach',
                    address: '1499 Arbutus St, Vancouver, BC V6J 5N2',
                    website: 'https://vancouver.ca/parks-recreation-culture/kitsilano-beach.aspx',
                    video_id: 'jHfjAfPxWSs', // Vidéo YouTube exemple
                    province: 'bc'
                },

                // Alberta (4 lieux)
                {
                    id: 19,
                    name: 'West Edmonton Mall',
                    description: 'Plus grand centre commercial d\'Amérique du Nord avec parc aquatique et parc d\'attractions.',
                    latitude: 53.5228,
                    longitude: -113.6243,
                    category: 'shopping',
                    address: '8882 170 St NW, Edmonton, AB T5T 4J2',
                    phone: '+1-780-444-5200',
                    website: 'https://www.wem.ca',
                    video_id: 'jHfjAfPxWSs', // Vidéo YouTube
                    province: 'ab'
                },
                {
                    id: 20,
                    name: 'Calgary Stampede',
                    description: 'Festival annuel de rodéo, exposition et festival.',
                    latitude: 51.0374,
                    longitude: -114.0620,
                    category: 'event',
                    address: '1410 Olympic Way SE, Calgary, AB T2G 2W1',
                    website: 'https://www.calgarystampede.com',
                    video_id: 'jHfjAfPxWSs', // Vidéo YouTube
                    province: 'ab'
                },
                {
                    id: 21,
                    name: 'Lac Louise',
                    description: 'Lac glaciaire célèbre pour ses eaux turquoise et le Fairmont Chateau Lake Louise.',
                    latitude: 51.4254,
                    longitude: -116.1773,
                    category: 'lake',
                    address: 'Lake Louise, AB T0L 1E0',
                    website: 'https://www.banfflakelouise.com',
                    video_id: 'jHfjAfPxWSs', // Vidéo YouTube
                    province: 'ab'
                },
                {
                    id: 22,
                    name: 'Jasper National Park',
                    description: 'Plus grand parc national des Rocheuses canadiennes.',
                    latitude: 52.8733,
                    longitude: -118.0814,
                    category: 'park',
                    address: 'Jasper, AB T0E 1E0',
                    website: 'https://www.pc.gc.ca/en/pn-np/ab/jasper',
                    video_id: 'jHfjAfPxWSs', // Vidéo YouTube exemple
                    province: 'ab'
                },

                // Autres provinces (8 lieux)
                {
                    id: 23,
                    name: 'Hopewell Rocks - Nouveau-Brunswick',
                    description: 'Formations rocheuses spectaculaires sculptées par les marées.',
                    latitude: 45.8235,
                    longitude: -64.5847,
                    category: 'tourism',
                    address: '131 Discovery Rd, Hopewell Cape, NB E4H 4Z5',
                    website: 'https://www.thehopewellrocks.ca',
                    video_id: 'jHfjAfPxWSs', // Vidéo YouTube
                    province: 'nb'
                },
                {
                    id: 24,
                    name: 'Peggy\'s Cove - Nouvelle-Écosse',
                    description: 'Village de pêcheurs pittoresque avec célèbre phare.',
                    latitude: 44.4929,
                    longitude: -63.9165,
                    category: 'tourism',
                    address: 'Peggy\'s Cove, NS B3Z 3S3',
                    website: 'https://www.peggyscoveregion.com',
                    video_id: 'jHfjAfPxWSs', // Vidéo YouTube
                    province: 'ns'
                },
                {
                    id: 25,
                    name: 'L\'Anse aux Meadows - Terre-Neuve',
                    description: 'Site archéologique viking, patrimoine mondial de l\'UNESCO.',
                    latitude: 51.5958,
                    longitude: -55.5312,
                    category: 'museum',
                    address: 'L\'Anse aux Meadows, NL A0K 2X0',
                    website: 'https://www.pc.gc.ca/en/lhn-nhs/nl/meadows',
                    video_id: 'jHfjAfPxWSs', // Vidéo YouTube
                    province: 'nl'
                },
                {
                    id: 26,
                    name: 'Green Gables - Île-du-Prince-Édouard',
                    description: 'Maison historique inspirant le roman "Anne... la maison aux pignons verts".',
                    latitude: 46.4906,
                    longitude: -63.3816,
                    category: 'museum',
                    address: '8619 Cavendish Rd, Cavendish, PE C0A 1M0',
                    website: 'https://www.pc.gc.ca/en/lhn-nhs/pe/greengables',
                    video_id: 'jHfjAfPxWSs', // Vidéo YouTube exemple
                    province: 'pe'
                },
                {
                    id: 27,
                    name: 'Wanuskewin - Saskatchewan',
                    description: 'Parc historique et centre culturel des Premières Nations.',
                    latitude: 52.2208,
                    longitude: -106.5981,
                    category: 'museum',
                    address: 'RR #4, Penner Road, Saskatoon, SK S7K 3J7',
                    website: 'https://wanuskewin.com',
                    video_id: 'jHfjAfPxWSs', // Vidéo YouTube exemple
                    province: 'sk'
                },
                {
                    id: 28,
                    name: 'Polar Bear Capital - Manitoba',
                    description: 'Churchill, capitale mondiale de l\'ours polaire.',
                    latitude: 58.7699,
                    longitude: -94.1694,
                    category: 'tourism',
                    address: 'Churchill, MB R0B 0E0',
                    website: 'https://churchill.ca',
                    video_id: 'jHfjAfPxWSs', // Vidéo YouTube exemple
                    province: 'mb'
                },
                {
                    id: 29,
                    name: 'Kluane National Park - Yukon',
                    description: 'Parc national avec les plus hautes montagnes du Canada.',
                    latitude: 60.5709,
                    longitude: -138.4261,
                    category: 'park',
                    address: 'Kluane National Park and Reserve, YT Y0B 1H0',
                    website: 'https://www.pc.gc.ca/en/pn-np/yt/kluane',
                    video_id: 'jHfjAfPxWSs', // Vidéo YouTube exemple
                    province: 'yt'
                },
                {
                    id: 30,
                    name: 'Nahanni National Park - Territoires du Nord-Ouest',
                    description: 'Parc national avec canyons spectaculaires et chutes Virginia.',
                    latitude: 61.5333,
                    longitude: -125.5833,
                    category: 'park',
                    address: 'Nahanni National Park Reserve, NT X0E 0N0',
                    website: 'https://www.pc.gc.ca/en/pn-np/nt/nahanni',
                    video_id: 'jHfjAfPxWSs', // Vidéo YouTube exemple
                    province: 'nt'
                },
                {
                    id: 31,
                    name: 'Auyuittuq National Park - Nunavut',
                    description: 'Parc national arctique avec montagnes et fjords.',
                    latitude: 67.4167,
                    longitude: -66.3333,
                    category: 'park',
                    address: 'Auyuittuq National Park, NU X0A 0H0',
                    website: 'https://www.pc.gc.ca/en/pn-np/nu/auyuittuq',
                    video_id: 'jHfjAfPxWSs', // Vidéo YouTube exemple
                    province: 'nu'
                }
            ];
        }
        
        async init() {
            try {
                // Initialiser la carte
                this.initMap();
                
                // Initialiser la sidebar
                this.initSidebar();
                
                // Charger les filtres
                this.populateFilters();
                
                // Charger les lieux statiques
                this.loadStaticPlaces();
                
                // Écouter les événements
                this.setupEventListeners();
                
                console.log('Carte interactive initialisée avec succès');
            } catch (error) {
                console.error('Erreur lors de l\'initialisation:', error);
            }
        }
        
        initMap() {
            try {
                // Cacher le loading overlay
                document.getElementById('mapLoading').style.display = 'none';
                
                // Centrer sur le Canada
                this.map = L.map('map').setView([56.1304, -106.3468], 4);
                
                // Ajouter les tuiles OpenStreetMap
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributeurs',
                    maxZoom: 19,
                    detectRetina: true
                }).addTo(this.map);
                
                // Ajouter un contrôle d'échelle
                L.control.scale({ imperial: false }).addTo(this.map);
                
                // Ajouter un contrôle de localisation
                this.addLocateControl();
                
            } catch (error) {
                console.error('Erreur lors de l\'initialisation de la carte:', error);
            }
        }
        
        populateFilters() {
            // Remplir le filtre des provinces
            const provinceFilter = document.getElementById('province-filter');
            this.provinces.forEach(province => {
                const option = document.createElement('option');
                option.value = province.code;
                option.textContent = province.name;
                option.dataset.lat = province.lat;
                option.dataset.lng = province.lng;
                provinceFilter.appendChild(option);
            });
            
            // Remplir le filtre des catégories
            const categoryFilter = document.getElementById('category-filter');
            this.categories.forEach(category => {
                const option = document.createElement('option');
                option.value = category;
                option.textContent = this.capitalizeFirstLetter(category);
                categoryFilter.appendChild(option);
            });
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
        if (marker && marker.remove) {
            marker.remove();
        }
        if (popup && popup.remove) {
            popup.remove();
        }
    });
    this.markers = {};
}
        createMarker(place) {
    const icon = L.divIcon({
        className: 'custom-marker',
        html: `
            <div class="marker-icon" 
                 style="background: ${this.getCategoryColor(place.category)};">
                <i class="${this.getCategoryIcon(place.category)}"></i>
            </div>
        `,
        iconSize: [40, 40],
        iconAnchor: [20, 40]  // Centre en bas
    });
    
    const marker = L.marker([place.latitude, place.longitude], { 
        icon: icon,
        title: place.name
    }).addTo(this.map);
    
    // Créer un popup avec position fixe
    const popup = L.popup({
        maxWidth: 300,
        closeButton: false,
        autoClose: false,
        closeOnClick: false,
        offset: L.point(0, -45),  // Ajuster pour positionner au-dessus
        className: 'custom-popup'  // Classe CSS pour contrôle supplémentaire
    }).setContent(this.createPopupContent(place));
    
    // Associer le popup au marqueur SANS bindPopup
    // Nous allons gérer l'ouverture/fermeture manuellement
    
    // Gestion des événements hover
    let hoverTimeout;
    
    // Au survol du marqueur
    marker.on('mouseover', (e) => {
        if (hoverTimeout) {
            clearTimeout(hoverTimeout);
        }
        
        hoverTimeout = setTimeout(() => {
            // Ouvrir le popup à la position du marqueur
            popup.setLatLng(marker.getLatLng())
                .openOn(this.map);
            
            // Mettre en surbrillance
            const placeElement = document.querySelector(`.place-item[data-id="${place.id}"]`);
            if (placeElement) {
                placeElement.classList.add('active');
            }
            
            const iconElement = marker.getElement();
            if (iconElement) {
                const markerIcon = iconElement.querySelector('.marker-icon');
                if (markerIcon) {
                    markerIcon.classList.add('highlighted');
                }
            }
        }, 200); // Délai réduit pour une réponse plus rapide
    });
    
    // Quand la souris quitte le marqueur
    marker.on('mouseout', (e) => {
        if (hoverTimeout) {
            clearTimeout(hoverTimeout);
        }
        
        hoverTimeout = setTimeout(() => {
            // Vérifier si la souris est sur le popup
            const popupElement = document.querySelector('.leaflet-popup');
            if (!popupElement || !popupElement.matches(':hover')) {
                this.map.closePopup();
                
                // Retirer la surbrillance
                const placeElement = document.querySelector(`.place-item[data-id="${place.id}"]`);
                if (placeElement) {
                    placeElement.classList.remove('active');
                }
                
                const iconElement = marker.getElement();
                if (iconElement) {
                    const markerIcon = iconElement.querySelector('.marker-icon');
                    if (markerIcon) {
                        markerIcon.classList.remove('highlighted');
                    }
                }
            }
        }, 300);
    });
    
    // Gestion du clic
    marker.on('click', (e) => {
        if (hoverTimeout) {
            clearTimeout(hoverTimeout);
        }
        this.map.closePopup(); // Fermer le popup avant d'ouvrir la modal
        this.showPlaceModal(place);
    });
    
    // Stocker les références
    this.markers[place.id] = { marker, popup };
    
    return marker;
}
        createPopupContent(place) {
            return `
                <div class="hover-popup-content" data-place-id="${place.id}">
                    <div style="padding:12px; font-family:-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;">
                        <div style="display:flex; align-items:center; gap:10px; margin-bottom:12px;">
                            <div style="width:36px; height:36px; border-radius:50%; background:${this.getCategoryColor(place.category)}; display:flex; align-items:center; justify-content:center;">
                                <i class="${this.getCategoryIcon(place.category)}" style="color:white; font-size:16px;"></i>
                            </div>
                            <div style="flex:1; min-width:0;">
                                <h4 style="margin:0; font-size:14px; font-weight:600; color:#1a1a1a; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                                    ${place.name}
                                </h4>
                                <div style="font-size:11px; color:#666;">
                                    ${this.capitalizeFirstLetter(place.category)} • ${this.getProvinceName(place.province)}
                                </div>
                            </div>
                        </div>
                        
                        ${place.video_id ? `
                            <div class="youtube-video-container">
                                <iframe src="https://www.youtube.com/embed/${place.video_id}?autoplay=0&mute=1&controls=1&modestbranding=1&rel=0"
                                        title="Vidéo de ${place.name}"
                                        frameborder="0"
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                        allowfullscreen>
                                </iframe>
                                <div class="youtube-badge">
                                    <i class="fab fa-youtube"></i> YouTube
                                </div>
                            </div>
                        ` : `
                            <div style="margin:8px 0; height:120px; border-radius:8px; overflow:hidden; background:#f0f0f0; display:flex; align-items:center; justify-content:center; color:#666; font-size:12px;">
                                <div style="text-align:center;">
                                    <i class="fas fa-image" style="font-size:32px; margin-bottom:8px;"></i>
                                    <div>Aucune vidéo disponible</div>
                                </div>
                            </div>
                        `}
                        
                        <p style="margin:12px 0; font-size:11px; color:#666; line-height:1.4; max-height:40px; overflow:hidden; display:-webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">
                            ${place.description || 'Aucune description disponible'}
                        </p>
                        
                        <div style="display:flex; gap:6px; margin-top:12px;">
                            <button onclick="event.stopPropagation(); window.mapApp.showPlaceModal(${JSON.stringify(place).replace(/"/g, '&quot;')})"
                                    style="flex:1; background:#3b82f6; color:white; border:none; border-radius:4px; padding:8px 12px; font-size:11px; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:6px; font-weight:500;">
                                <i class="fas fa-info-circle"></i> Détails
                            </button>
                            
                            ${place.website ? `
                                <a href="${place.website}" target="_blank"
                                   style="background:#10b981; color:white; border:none; border-radius:4px; padding:8px 12px; font-size:11px; cursor:pointer; width:40px; display:flex; align-items:center; justify-content:center; text-decoration:none;">
                                    <i class="fas fa-external-link-alt"></i>
                                </a>
                            ` : ''}
                            
                            ${place.video_id ? `
                                <a href="https://www.youtube.com/watch?v=${place.video_id}" target="_blank"
                                   style="background:#ff0000; color:white; border:none; border-radius:4px; padding:8px 12px; font-size:11px; cursor:pointer; width:40px; display:flex; align-items:center; justify-content:center; text-decoration:none;">
                                    <i class="fab fa-youtube"></i>
                                </a>
                            ` : ''}
                        </div>
                    </div>
                </div>
            `;
        }
        
        getProvinceName(code) {
            const province = this.provinces.find(p => p.code === code);
            return province ? province.name : 'Canada';
        }
        
        getCategoryColor(category) {
            const colors = {
                business: '#2a5bd7',
                tourism: '#00c9b7',
                restaurant: '#e53e3e',
                hotel: '#38a169',
                museum: '#805ad5',
                shopping: '#3182ce',
                park: '#d69e2e',
                monument: '#dd6b20',
                event: '#ed64a6',
                airport: '#667eea',
                university: '#9f7aea',
                hospital: '#f56565',
                beach: '#4299e1',
                mountain: '#48bb78',
                lake: '#0bc5ea',
                default: '#718096'
            };
            return colors[category] || colors.default;
        }
        
        getCategoryIcon(category) {
            const icons = {
                business: 'fas fa-briefcase',
                tourism: 'fas fa-globe-americas',
                restaurant: 'fas fa-utensils',
                hotel: 'fas fa-hotel',
                museum: 'fas fa-landmark',
                shopping: 'fas fa-shopping-bag',
                park: 'fas fa-tree',
                monument: 'fas fa-monument',
                event: 'fas fa-calendar-alt',
                airport: 'fas fa-plane',
                university: 'fas fa-graduation-cap',
                hospital: 'fas fa-hospital',
                beach: 'fas fa-umbrella-beach',
                mountain: 'fas fa-mountain',
                lake: 'fas fa-water',
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
            
            div.innerHTML = `
                <div class="place-image">
                    <img src="https://images.unsplash.com/photo-1518837695005-2083093ee35b?w=400&h=150&fit=crop" alt="${place.name}" loading="lazy">
                </div>
                <div class="place-info">
                    <h4>${place.name}</h4>
                    <span class="place-category" style="background:${this.getCategoryColor(place.category)}">
                        ${this.capitalizeFirstLetter(place.category)}
                    </span>
                    <span style="display:block; font-size:11px; color:#666; margin-top:5px;">
                        <i class="fas fa-map-marker-alt"></i> ${this.getProvinceName(place.province)}
                    </span>
                    <p class="place-description">${place.description?.substring(0, 80) || 'Aucune description disponible'}...</p>
                    ${place.video_id ? `
                        <div style="font-size:12px; color:#666; margin-bottom:10px; display:flex; align-items:center; gap:5px;">
                            <i class="fab fa-youtube" style="color:#ff0000;"></i> Vidéo YouTube disponible
                        </div>
                    ` : ''}
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
            
            // Événements
            div.querySelector('.view-details-btn').addEventListener('click', (e) => {
                e.stopPropagation();
                this.showPlaceModal(place);
            });
            
            div.querySelector('.locate-btn-small').addEventListener('click', (e) => {
                e.stopPropagation();
                this.centerOnPlace(place);
            });
            
            div.addEventListener('mouseenter', () => {
                const marker = this.markers[place.id];
                if (marker) {
                    marker.openPopup();
                    
                    const iconElement = marker.getElement();
                    if (iconElement) {
                        const markerIcon = iconElement.querySelector('.marker-icon');
                        if (markerIcon) {
                            markerIcon.classList.add('highlighted');
                        }
                    }
                }
            });
            
            div.addEventListener('mouseleave', () => {
                setTimeout(() => {
                    const marker = this.markers[place.id];
                    if (marker) {
                        const popupElement = document.querySelector('.leaflet-popup');
                        if (!popupElement || !popupElement.matches(':hover')) {
                            marker.closePopup();
                            
                            const iconElement = marker.getElement();
                            if (iconElement) {
                                const markerIcon = iconElement.querySelector('.marker-icon');
                                if (markerIcon) {
                                    markerIcon.classList.remove('highlighted');
                                }
                            }
                        }
                    }
                }, 100);
            });
            
            return div;
        }
        
       centerOnPlace(place) {
    this.map.setView([place.latitude, place.longitude], 12);
    // Ouvrir le popup manuellement
    const markerData = this.markers[place.id];
    if (markerData && markerData.popup) {
        markerData.popup.setLatLng([place.latitude, place.longitude])
            .openOn(this.map);
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
                        <div style="display:flex; align-items:center; gap:10px;">
                            <span style="background:${this.getCategoryColor(place.category)}; color:white; padding:6px 16px; border-radius:20px; font-size:14px; font-weight:500;">
                                ${this.capitalizeFirstLetter(place.category)}
                            </span>
                            <span style="color:#666; font-size:14px;">
                                <i class="fas fa-map-marker-alt"></i> ${this.getProvinceName(place.province)}
                            </span>
                        </div>
                    </div>
                    
                    ${place.video_id ? `
                        <div style="margin-bottom:30px; border-radius:12px; overflow:hidden; position:relative;">
                            <div style="position:relative; padding-bottom:56.25%; height:0;">
                                <iframe src="https://www.youtube.com/embed/${place.video_id}?autoplay=1&mute=0&controls=1&modestbranding=1&rel=0"
                                        style="position:absolute; top:0; left:0; width:100%; height:100%; border:none;"
                                        frameborder="0"
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                        allowfullscreen>
                                </iframe>
                            </div>
                            <div style="position:absolute; top:15px; right:15px; background:rgba(255,0,0,0.9); color:white; padding:8px 12px; border-radius:6px; font-size:12px; font-weight:600;">
                                <i class="fab fa-youtube"></i> YouTube
                            </div>
                        </div>
                    ` : ''}
                    
                    <div class="place-details">
                        <div style="margin-bottom:30px;">
                            <h4 style="color:#333; margin-bottom:15px; font-size:1.2rem;">
                                <i class="fas fa-info-circle" style="color:#4299e1;"></i> Description
                            </h4>
                            <p style="color:#666; line-height:1.6; font-size:16px;">${place.description}</p>
                        </div>
                        
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
            `;
        }
        
        getDirections(place) {
            if (!this.currentLocation) {
                alert('Veuillez d\'abord vous localiser en cliquant sur "Me localiser" pour calculer un itinéraire.');
                return;
            }
            
            const startLat = this.currentLocation.lat;
            const startLng = this.currentLocation.lng;
            const endLat = place.latitude;
            const endLng = place.longitude;
            
            const googleMapsUrl = `https://www.google.com/maps/dir/?api=1&origin=${startLat},${startLng}&destination=${endLat},${endLng}&travelmode=driving`;
            
            if (confirm(`Ouvrir Google Maps pour l'itinéraire vers "${place.name}" ?`)) {
                window.open(googleMapsUrl, '_blank');
            }
            
            this.closeModal();
        }
        
        closeModal() {
            const modal = document.getElementById('place-modal');
            if (modal) {
                modal.style.display = 'none';
            }
            this.activePlace = null;
        }
        
        setupEventListeners() {
            // Filtre de province
            const provinceFilter = document.getElementById('province-filter');
            if (provinceFilter) {
                provinceFilter.addEventListener('change', (e) => {
                    this.selectedProvince = e.target.value;
                    
                    // Centrer sur la province sélectionnée
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
            
            // Filtre de catégorie
            const categoryFilter = document.getElementById('category-filter');
            if (categoryFilter) {
                categoryFilter.addEventListener('change', (e) => {
                    this.selectedCategory = e.target.value;
                    this.filterPlaces();
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
            
            window.addEventListener('click', (e) => {
                const modal = document.getElementById('place-modal');
                if (e.target === modal) {
                    this.closeModal();
                }
            });
            
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') {
                    this.closeModal();
                }
            });
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
                alert('La géolocalisation n\'est pas supportée par votre navigateur.');
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
                    console.error('Erreur de géolocalisation:', error);
                    alert('Impossible de vous localiser. Veuillez autoriser l\'accès à votre position.');
                    locateBtn.innerHTML = originalHTML;
                    locateBtn.disabled = false;
                },
                {
                    enableHighAccuracy: true,
                    timeout: 10000
                }
            );
        }
        
        addUserMarker(lat, lng) {
            if (this.userMarker) {
                this.userMarker.remove();
            }
            
            const userIcon = L.divIcon({
                className: 'custom-marker',
                html: `
                    <div class="user-marker-icon">
                        <i class="fas fa-user"></i>
                    </div>
                `,
                iconSize: [50, 50],
                iconAnchor: [25, 50]
            });
            
            this.userMarker = L.marker([lat, lng], { 
                icon: userIcon,
                title: 'Votre position'
            }).addTo(this.map);
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
    
    // Animation des statistiques
    function animateCounters() {
        const counters = document.querySelectorAll('.stat-number');
        
        counters.forEach(counter => {
            const target = +counter.getAttribute('data-count');
            const count = +counter.innerText;
            
            if (count < target) {
                counter.innerText = Math.ceil(count + target / 50);
                setTimeout(animateCounters, 20);
            } else {
                counter.innerText = target;
            }
        });
    }
    
    // Initialisation
    document.addEventListener('DOMContentLoaded', () => {
        try {
            window.mapApp = new InteractiveMap();
            console.log('Application carte interactive prête');
            
            // Lancer l'animation des compteurs
            setTimeout(animateCounters, 1000);
            
            // Ajouter des animations aux cartes
            const infoCards = document.querySelectorAll('.info-card');
            infoCards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.classList.add('pulse');
                });
                
                card.addEventListener('mouseleave', function() {
                    this.classList.remove('pulse');
                });
            });
            
        } catch (error) {
            console.error('Erreur fatale:', error);
        }
    });
    </script>
<script>
function sendHeight() {
    const height = document.body.scrollHeight;
    window.parent.postMessage({
        type: 'setHeight',
        iframeId: 'affichez-vos-entreprises',
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