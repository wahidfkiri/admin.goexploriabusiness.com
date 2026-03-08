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
    
    <style>
        /* Styles pour le mega menu des régions */
        .regions-mega-menu {
            position: absolute;
            left: 0;
            top: 100%;
            width: 100%;
            background: white;
            box-shadow: 0 15px 30px rgba(0,0,0,0.1);
            border-radius: 0 0 12px 12px;
            padding: 30px 0;
            z-index: 1000;
            display: none;
            opacity: 0;
            transform: translateY(-10px);
            transition: opacity 0.3s ease, transform 0.3s ease;
            border-top: 3px solid var(--primary-color);
            max-height: 80vh;
            overflow-y: auto;
        }
        
        .regions-mega-menu.active {
            display: block;
            opacity: 1;
            transform: translateY(0);
        }
        
        .region-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .region-card {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            transition: all 0.3s ease;
            border: 1px solid #e9ecef;
            height: 100%;
        }
        
        .region-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
            border-color: var(--primary-color);
        }
        
        .region-image {
            height: 140px;
            width: 100%;
            object-fit: cover;
        }
        
        .region-content {
            padding: 15px;
        }
        
        .region-title {
            font-size: 1rem;
            font-weight: 600;
            color: #333;
            margin: 0;
            text-align: center;
        }
        
        .region-link {
            text-decoration: none;
            color: inherit;
            display: block;
        }
        
        .region-link:hover {
            color: inherit;
        }
        
        .region-list {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            margin-top: 25px;
            padding-top: 25px;
            border-top: 1px solid #eee;
        }
        
        .region-list-item {
            padding: 8px 0;
        }
        
        .region-list-item a {
            color: #555;
            text-decoration: none;
            transition: color 0.3s ease;
            display: flex;
            align-items: center;
        }
        
        .region-list-item a:hover {
            color: var(--primary-color);
        }
        
        .region-list-item i {
            margin-right: 8px;
            color: var(--secondary-color);
        }
        
        .mega-menu-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f0f0f0;
        }
        
        .mega-menu-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #333;
            margin: 0;
        }
        
        .close-mega-menu {
            background: none;
            border: none;
            font-size: 1.5rem;
            color: #999;
            cursor: pointer;
            transition: color 0.3s ease;
            padding: 0;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
        }
        
        .close-mega-menu:hover {
            color: #333;
            background: #f5f5f5;
        }
        
        .all-regions-link {
            text-align: center;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }
        
        /* Responsive styles */
        @media (max-width: 1200px) {
            .region-grid {
                grid-template-columns: repeat(4, 1fr);
            }
            
            .region-list {
                grid-template-columns: repeat(3, 1fr);
            }
        }
        
        @media (max-width: 992px) {
            .regions-mega-menu {
                position: static;
                box-shadow: none;
                border-radius: 0;
                padding: 20px 15px;
                display: none;
                max-height: none;
                overflow-y: visible;
            }
            
            .regions-mega-menu.active {
                display: block;
            }
            
            .region-grid {
                grid-template-columns: repeat(3, 1fr);
                gap: 15px;
            }
            
            .region-image {
                height: 120px;
            }
            
            .region-list {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        
        @media (max-width: 768px) {
            .region-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .region-list {
                grid-template-columns: 1fr;
            }
            
            .region-image {
                height: 100px;
            }
        }
        
        @media (max-width: 576px) {
            .region-grid {
                grid-template-columns: 1fr;
            }
            
            .region-image {
                height: 140px;
            }
        }
        
        /* Animation pour l'ouverture du menu */
        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .regions-mega-menu.active {
            animation: fadeInDown 0.3s ease forwards;
        }
        
        /* Gestion du dropdown Bootstrap */
        .dropdown:hover .regions-mega-menu {
            display: block;
            opacity: 1;
            transform: translateY(0);
        }
        
        /* Pour mobile, utiliser le toggle Bootstrap */
        @media (max-width: 992px) {
            .dropdown:hover .regions-mega-menu {
                display: none;
            }
            
            .dropdown.show .regions-mega-menu {
                display: block;
            }
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

    <!-- Main Navigation - RESPONSIVE -->
    <nav class="navbar navbar-expand-lg navbar-light main-navbar">
        <div class="container">
            <a class="navbar-brand" href="/fr/">
                <div class="site-logo">
                    <img src="https://www.goexploria.com/images/logo-go-exploria-qc-3.png" alt="GoExploria" class="logo-img">
                    <div class="logo-text">
                        <span class="logo-title">GoExploria</span>
                        <span class="logo-subtitle">Affaires</span>
                    </div>
                </div>
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain" aria-controls="navbarMain" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarMain">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item dropdown position-static">
                        <a class="nav-link dropdown-toggle" href="#" id="explorerDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-map-marked-alt me-1"></i>Explorer Région
                        </a>
                        
                        <!-- Mega Menu Statique des Régions -->
                        <div class="regions-mega-menu" aria-labelledby="explorerDropdown">
                            <div class="container">
                                <div class="mega-menu-header">
                                    <h3 class="mega-menu-title">Explorez les Régions</h3>
                                    <button class="close-mega-menu d-lg-none">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                                
                                <div class="region-grid">
                                    <!-- Continents -->
                                     @foreach(\App\Models\Continent::all() as $continent)
                                    <div class="region-card">
                                        <a href="#" class="region-link">
                                            <img src="{{asset('storage/continents')}}/{{$continent->image}}" 
                                                 alt="Québec" 
                                                 class="region-image">
                                            <div class="region-content">
                                                <h4 class="region-title">
                                            <i class="fas fa-map-marker-alt"></i> {{$continent->name}}</h4>
                                            </div>
                                        </a>
                                    </div>
                                    @endforeach
                                </div>
                                
                                <!-- <div class="region-list">
                                    <div class="region-list-item">
                                        <a href="#">
                                            <i class="fas fa-map-marker-alt"></i>
                                            Yukon
                                        </a>
                                    </div>
                                    <div class="region-list-item">
                                        <a href="#">
                                            <i class="fas fa-map-marker-alt"></i>
                                            Territoires du Nord-Ouest
                                        </a>
                                    </div>
                                    <div class="region-list-item">
                                        <a href="#">
                                            <i class="fas fa-map-marker-alt"></i>
                                            Nunavut
                                        </a>
                                    </div>
                                    <div class="region-list-item">
                                        <a href="#">
                                            <i class="fas fa-map-marker-alt"></i>
                                            Montréal (Ville)
                                        </a>
                                    </div>
                                    <div class="region-list-item">
                                        <a href="#">
                                            <i class="fas fa-map-marker-alt"></i>
                                            Toronto (Ville)
                                        </a>
                                    </div>
                                    <div class="region-list-item">
                                        <a href="#">
                                            <i class="fas fa-map-marker-alt"></i>
                                            Vancouver (Ville)
                                        </a>
                                    </div>
                                    <div class="region-list-item">
                                        <a href="#">
                                            <i class="fas fa-map-marker-alt"></i>
                                            Calgary (Ville)
                                        </a>
                                    </div>
                                    <div class="region-list-item">
                                        <a href="#">
                                            <i class="fas fa-map-marker-alt"></i>
                                            Ottawa (Capitale)
                                        </a>
                                    </div>
                                </div> -->
                                
                                <div class="all-regions-link">
                                    <a href="#" class="btn btn-primary">
                                        <i class="fas fa-list me-2"></i>
                                        Voir toutes les régions (18+)
                                    </a>
                                </div>
                            </div>
                        </div>
                        <!-- Fin du Mega Menu des Régions -->
                    </li>
                    
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="servicesDropdown" role="button" data-bs-toggle="dropdown" data-bs-auto-close="outside">
                            <i class="fas fa-concierge-bell me-1"></i> GO Explorez
                        </a>
                        <div class="dropdown-menu full-width" aria-labelledby="servicesDropdown">
                            <div class="container">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h5 class="dropdown-header">Services Digitaux</h5>
                                        <a class="dropdown-item" href="#">
                                            <i class="fas fa-briefcase me-2"></i>GO Business
                                            <span class="text-muted d-block small mt-1">Solutions pour entreprises</span>
                                        </a>
                                        <a class="dropdown-item" href="#">
                                            <i class="fas fa-store me-2"></i>GO Local
                                            <span class="text-muted d-block small mt-1">Promotion commerciale locale</span>
                                        </a>
                                        <a class="dropdown-item" href="#">
                                            <i class="fas fa-crown me-2"></i>GO Prime Time
                                            <span class="text-muted d-block small mt-1">Services premium</span>
                                        </a>
                                    </div>
                                    <div class="col-md-6">
                                        <h5 class="dropdown-header">Médias & Contenu</h5>
                                        <a class="dropdown-item" href="#">
                                            <i class="fas fa-tv me-2"></i>GO Web TV
                                            <span class="text-muted d-block small mt-1">Chaîne vidéo en ligne</span>
                                        </a>
                                        <a class="dropdown-item" href="#">
                                            <i class="fas fa-camera me-2"></i>GO Photos
                                            <span class="text-muted d-block small mt-1">Banque d'images exclusive</span>
                                        </a>
                                        <a class="dropdown-item" href="#">
                                            <i class="fas fa-newspaper me-2"></i>GO Actualités
                                            <span class="text-muted d-block small mt-1">Nouvelles locales et régionales</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="resourcesDropdown" role="button" data-bs-toggle="dropdown" data-bs-auto-close="outside">
                            <i class="fas fa-book me-1"></i>Ressources
                        </a>
                        <div class="dropdown-menu full-width" aria-labelledby="resourcesDropdown">
                            <div class="container">
                                <div class="row">
                                    <div class="col-md-4">
                                        <h5 class="dropdown-header">Contenu Éducatif</h5>
                                        <a class="dropdown-item" href="#">
                                            <i class="fas fa-file-alt me-2"></i>Blog
                                            <span class="text-muted d-block small mt-1">Articles et conseils</span>
                                        </a>
                                        <a class="dropdown-item" href="#">
                                            <i class="fas fa-newspaper me-2"></i>Actualités
                                            <span class="text-muted d-block small mt-1">Nouvelles du Québec</span>
                                        </a>
                                        <a class="dropdown-item" href="#">
                                            <i class="fas fa-graduation-cap me-2"></i>Guides
                                            <span class="text-muted d-block small mt-1">Guides touristiques</span>
                                        </a>
                                    </div>
                                    <div class="col-md-4">
                                        <h5 class="dropdown-header">Événements</h5>
                                        <a class="dropdown-item" href="#">
                                            <i class="fas fa-calendar-alt me-2"></i>Calendrier
                                            <span class="text-muted d-block small mt-1">Événements à venir</span>
                                        </a>
                                        <a class="dropdown-item" href="#">
                                            <i class="fas fa-ticket-alt me-2"></i>Billeterie
                                            <span class="text-muted d-block small mt-1">Achetez vos billets</span>
                                        </a>
                                        <a class="dropdown-item" href="#">
                                            <i class="fas fa-bullhorn me-2"></i>Promotions
                                            <span class="text-muted d-block small mt-1">Offres spéciales</span>
                                        </a>
                                    </div>
                                    <div class="col-md-4">
                                        <h5 class="dropdown-header">Support</h5>
                                        <a class="dropdown-item" href="#">
                                            <i class="fas fa-question-circle me-2"></i>Aide & FAQ
                                            <span class="text-muted d-block small mt-1">Questions fréquentes</span>
                                        </a>
                                        <a class="dropdown-item" href="#">
                                            <i class="fas fa-headset me-2"></i>Support Client
                                            <span class="text-muted d-block small mt-1">Assistance 24/7</span>
                                        </a>
                                        <a class="dropdown-item" href="#">
                                            <i class="fas fa-download me-2"></i>Téléchargements
                                            <span class="text-muted d-block small mt-1">Ressources gratuites</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
                
                <div class="special-buttons d-flex">
                    <a href="https://www.goexploria.com/company/68620/go-exploria-plans-de-relance" class="btn btn-primary">
                        <i class="fas fa-seedling btn-icon"></i> Plans de relance
                    </a>
                    <a href="https://www.goexploria.com/company/68619/go-exploria-services-web" class="btn btn-secondary">
                        <i class="fas fa-globe btn-icon"></i> Services web
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Le reste du code HTML reste inchangé -->
    <!-- Video Slider Full Width -->
    <section class="video-slider-section" id="home">
        <div class="video-slider-container">
            <!-- Slide 1: Vidéo YouTube -->
            <div class="video-slide active">
                <iframe src="https://www.youtube.com/embed/VKWE89nmIWs?autoplay=1&mute=1&loop=1&playlist=VKWE89nmIWs" title="YouTube video" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            </div>
            
            <!-- Slide 2: Image -->
            <div class="video-slide">
                <img src="https://images.unsplash.com/photo-1506905925346-21bda4d32df4?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80" alt="Montagnes canadiennes">
            </div>
            
            <!-- Slide 3: Image -->
            <div class="video-slide">
                <img src="https://images.unsplash.com/photo-1542273917363-3b1817f69a2d?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80" alt="Paysage hivernal">
            </div>
            
            <!-- Slide 4: Image -->
            <div class="video-slide">
                <img src="https://images.unsplash.com/photo-1512453979798-5ea266f8880c?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80" alt="Ville de Québec">
            </div>
            
            <!-- Slide 5: Image -->
            <div class="video-slide">
                <img src="https://images.unsplash.com/photo-1596394516093-9baa8e6c2b5e?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80" alt="Lac canadien">
            </div>
        </div>
        
        <div class="slider-content">
            <div class="slider-text">
                <h1 class="slider-title">Créez votre présence digitale avec Go Exploria Business</h1>
                <p class="slider-subtitle">Notre plateforme tout-en-un vous permet de créer, gérer et optimiser votre site web avec des outils puissants d'analyse, SEO, messagerie et IA intégrée.</p>
                <div class="hero-buttons">
                    <a href="#editor" class="btn btn-primary btn-lg">
                        <i class="fas fa-play-circle me-2"></i>Essayer la démo
                    </a>
                    <a href="#features" class="btn btn-outline-light btn-lg ms-2">
                        <i class="fas fa-list-alt me-2"></i>Voir les fonctionnalités
                    </a>
                </div>
            </div>
        </div>
        
        <div class="slider-controls">
            <div class="slider-dot active" data-slide="0"></div>
            <div class="slider-dot" data-slide="1"></div>
            <div class="slider-dot" data-slide="2"></div>
            <div class="slider-dot" data-slide="3"></div>
            <div class="slider-dot" data-slide="4"></div>
        </div>
    </section>

    <!-- Les autres sections restent identiques -->
    <!-- Section Éditeur de Site Web -->
    <section class="editor-section" id="editor">
        <div class="container">
            <h2 class="section-title text-center mb-5">Notre Éditeur de Site Web Intuitif</h2>
            
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="editor-preview">
                        <div class="editor-toolbar">
                            <div class="toolbar-dot dot-red"></div>
                            <div class="toolbar-dot dot-yellow"></div>
                            <div class="toolbar-dot dot-green"></div>
                            <span class="text-white ms-3">Créateur de site Go Exploria Business</span>
                        </div>
                        <div class="editor-window">
                            <div class="editor-content">
                                <div class="editor-element">
                                    <h5>En-tête personnalisable</h5>
                                    <p class="mb-0">Logo, navigation, bannière</p>
                                </div>
                                <div class="editor-element">
                                    <h5>Galerie d'images responsive</h5>
                                    <p class="mb-0">Glisser-déposer pour organiser</p>
                                </div>
                                <div class="editor-element">
                                    <h5>Section services</h5>
                                    <p class="mb-0">Présentez vos offres</p>
                                </div>
                                <div class="editor-element">
                                    <h5>Formulaire de contact intelligent</h5>
                                    <p class="mb-0">Avec gestion des leads</p>
                                </div>
                                <div class="editor-element">
                                    <h5>Intégration réseaux sociaux</h5>
                                    <p class="mb-0">Automatisée et modifiable</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="ps-lg-5 mt-5 mt-lg-0">
                        <h3 class="mb-4" style="color: var(--primary-color);">Créez un site professionnel sans codage</h3>
                        <p class="mb-4">Notre éditeur visuel vous permet de créer un site web professionnel en quelques heures, sans aucune connaissance technique.</p>
                        
                        <div class="d-flex align-items-start mb-3">
                            <div class="me-3">
                                <i class="fas fa-check-circle" style="color: var(--secondary-color); font-size: 1.5rem;"></i>
                            </div>
                            <div>
                                <h5>Glisser-déposer intuitif</h5>
                                <p>Organisez vos pages avec une interface simple de glisser-déposer.</p>
                            </div>
                        </div>
                        
                        <div class="d-flex align-items-start mb-3">
                            <div class="me-3">
                                <i class="fas fa-check-circle" style="color: var(--secondary-color); font-size: 1.5rem;"></i>
                            </div>
                            <div>
                                <h5>Modèles professionnels</h5>
                                <p>Choisissez parmi des centaines de modèles conçus par des experts.</p>
                            </div>
                        </div>
                        
                        <div class="d-flex align-items-start mb-4">
                            <div class="me-3">
                                <i class="fas fa-check-circle" style="color: var(--secondary-color); font-size: 1.5rem;"></i>
                            </div>
                            <div>
                                <h5>Optimisation mobile automatique</h5>
                                <p>Votre site sera parfaitement adapté à tous les appareils.</p>
                            </div>
                        </div>
                        
                        <a href="#contact" class="btn btn-primary btn-lg">
                            <i class="fas fa-magic me-2"></i>Créer mon site maintenant
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="main-footer" style="background-color: var(--dark-color); color: white; padding: 80px 0 30px;">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <img src="https://www.goexploria.com/images/logo-go-exploria-qc-3.png" alt="GoExploria" style="height: 70px; margin-bottom: 25px;">
                    <p>Votre guide touristique et d'affaires pour le Québec. Découvrez, explorez, vivez le Québec comme jamais auparavant.</p>
                    <div class="social-icons mt-3">
                        <a href="https://www.youtube.com/user/explorezlemonde/videos?view_as=subscriber" target="_blank">
                            <i class="fab fa-youtube fa-2x"></i>
                        </a>
                    </div>
                </div>
                
                <div class="col-lg-4 mb-4">
                    <h4 style="color: white; font-size: 1.3rem; margin-bottom: 25px; padding-bottom: 15px; border-bottom: 2px solid var(--accent-color); display: inline-block;">Liens rapides</h4>
                    <ul style="list-style: none; padding: 0;">
                        <li style="margin-bottom: 12px;"><a href="#home" style="color: #ddd; text-decoration: none; transition: var(--transition);">Accueil Digital</a></li>
                        <li style="margin-bottom: 12px;"><a href="#editor" style="color: #ddd; text-decoration: none; transition: var(--transition);">Éditeur de site</a></li>
                        <li style="margin-bottom: 12px;"><a href="#features" style="color: #ddd; text-decoration: none; transition: var(--transition);">Fonctionnalités</a></li>
                        <li style="margin-bottom: 12px;"><a href="#clients" style="color: #ddd; text-decoration: none; transition: var(--transition);">Nos clients</a></li>
                    </ul>
                </div>
                
                <div class="col-lg-4 mb-4">
                    <h4 style="color: white; font-size: 1.3rem; margin-bottom: 25px; padding-bottom: 15px; border-bottom: 2px solid var(--accent-color); display: inline-block;">Contactez-nous</h4>
                    <ul style="list-style: none; padding: 0;">
                        <li style="margin-bottom: 12px;"><i class="fas fa-phone me-2"></i> (418) 525-7748</li>
                        <li style="margin-bottom: 12px;"><i class="fas fa-envelope me-2"></i> infogoexploria@gmail.com</li>
                        <li style="margin-bottom: 12px;"><i class="fas fa-map-marker-alt me-2"></i> Québec, Canada</li>
                    </ul>
                    <div class="mt-4">
                        <a href="https://www.goexploria.com/company/68620/go-exploria-plans-de-relance" class="btn btn-outline-light me-2">Plans de relance</a>
                        <a href="https://www.goexploria.com/company/68619/go-exploria-services-web" class="btn btn-accent" style="background-color: var(--accent-color); border-color: var(--accent-color); color: white;">Services web</a>
                    </div>
                </div>
            </div>
            
            <div class="copyright" style="text-align: center; padding-top: 40px; margin-top: 40px; border-top: 1px solid #444; color: #aaa; font-size: 0.95rem;">
                <p>&copy; 2023 GoExploria. Tous droits réservés. | <a href="#" style="color: white;">Politique de confidentialité</a> | <a href="#" style="color: white;">Conditions d'utilisation</a></p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
    // Variables globales
    let currentSlide = 0;
    let slideInterval;

    // Initialisation complète
    document.addEventListener('DOMContentLoaded', function() {
        // Initialiser le sélecteur de langue
        initLanguageSelector();
        
        // Initialiser le slider vidéo
        initVideoSlider();
        
        // Initialiser les animations de défilement
        initScrollAnimations();
        
        // Initialiser les compteurs animés
        initCounters();
        
        // Initialiser la navigation
        initNavigation();
        
        // Mettre à jour les informations en temps réel
        updateLiveInfo();
        
        // Gestion du mega menu pour mobile
        initMegaMenuMobile();
    });
    
    // Initialiser le mega menu pour mobile
    function initMegaMenuMobile() {
        const explorerDropdown = document.getElementById('explorerDropdown');
        const megaMenu = document.querySelector('.regions-mega-menu');
        const closeBtn = document.querySelector('.close-mega-menu');
        
        // Pour mobile (Bootstrap toggle)
        explorerDropdown.addEventListener('click', function(e) {
            if (window.innerWidth <= 992) {
                // Laisser Bootstrap gérer le dropdown
                return;
            }
            
            // Pour desktop: ouvrir/fermer au clic
            e.preventDefault();
            e.stopPropagation();
            
            if (megaMenu.classList.contains('active')) {
                megaMenu.classList.remove('active');
            } else {
                megaMenu.classList.add('active');
                
                // Fermer les autres dropdowns
                document.querySelectorAll('.regions-mega-menu.active').forEach(menu => {
                    if (menu !== megaMenu) {
                        menu.classList.remove('active');
                    }
                });
            }
        });
        
        // Bouton fermer pour mobile
        if (closeBtn) {
            closeBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                megaMenu.classList.remove('active');
                
                // Fermer le dropdown Bootstrap
                const dropdown = explorerDropdown.closest('.dropdown');
                const dropdownInstance = bootstrap.Dropdown.getInstance(explorerDropdown);
                if (dropdownInstance) {
                    dropdownInstance.hide();
                }
            });
        }
        
        // Fermer le menu en cliquant à l'extérieur (desktop)
        document.addEventListener('click', function(e) {
            if (window.innerWidth > 992 && megaMenu.classList.contains('active')) {
                if (!megaMenu.contains(e.target) && !explorerDropdown.contains(e.target)) {
                    megaMenu.classList.remove('active');
                }
            }
        });
        
        // Gestion du hover pour desktop
        if (window.innerWidth > 992) {
            const dropdownItem = explorerDropdown.closest('.dropdown');
            
            dropdownItem.addEventListener('mouseenter', function() {
                megaMenu.classList.add('active');
            });
            
            dropdownItem.addEventListener('mouseleave', function(e) {
                // Vérifier si la souris quitte le dropdown
                if (!megaMenu.contains(e.relatedTarget) && !explorerDropdown.contains(e.relatedTarget)) {
                    megaMenu.classList.remove('active');
                }
            });
            
            megaMenu.addEventListener('mouseleave', function(e) {
                if (!dropdownItem.contains(e.relatedTarget)) {
                    megaMenu.classList.remove('active');
                }
            });
        }
        
        // Gestion responsive
        window.addEventListener('resize', function() {
            // Fermer le menu lors du redimensionnement
            if (window.innerWidth <= 992) {
                megaMenu.classList.remove('active');
            }
        });
    }

    // Initialiser le sélecteur de langue
    function initLanguageSelector() {
        const languageBtn = document.getElementById('languageBtn');
        const languageDropdown = document.getElementById('languageDropdown');
        const languageOptions = document.querySelectorAll('.language-option');
        
        languageBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            languageDropdown.classList.toggle('show');
        });
        
        languageOptions.forEach(option => {
            option.addEventListener('click', function(e) {
                e.preventDefault();
                const lang = this.getAttribute('data-lang');
                const flag = this.querySelector('img').src;
                const text = this.querySelector('span').textContent;
                
                languageBtn.querySelector('img').src = flag;
                languageBtn.querySelector('span').textContent = text.toUpperCase().substring(0, 2);
                languageDropdown.classList.remove('show');
                
                console.log(`Langue changée: ${lang}`);
            });
        });
        
        document.addEventListener('click', function() {
            languageDropdown.classList.remove('show');
        });
        
        languageDropdown.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    }

    // Initialiser le slider vidéo
    function initVideoSlider() {
        const slides = document.querySelectorAll('.video-slide');
        const dots = document.querySelectorAll('.slider-dot');
        
        function showSlide(index) {
            slides.forEach(slide => slide.classList.remove('active'));
            dots.forEach(dot => dot.classList.remove('active'));
            
            currentSlide = index;
            slides[currentSlide].classList.add('active');
            dots[currentSlide].classList.add('active');
        }
        
        dots.forEach((dot, index) => {
            dot.addEventListener('click', () => {
                showSlide(index);
                resetInterval();
            });
        });
        
        function nextSlide() {
            currentSlide = (currentSlide + 1) % slides.length;
            showSlide(currentSlide);
        }
        
        function startInterval() {
            slideInterval = setInterval(nextSlide, 5000);
        }
        
        function resetInterval() {
            clearInterval(slideInterval);
            startInterval();
        }
        
        const sliderContainer = document.querySelector('.video-slider-container');
        sliderContainer.addEventListener('mouseenter', () => {
            clearInterval(slideInterval);
        });
        
        sliderContainer.addEventListener('mouseleave', () => {
            startInterval();
        });
        
        startInterval();
    }

    // Initialiser les animations de défilement
    function initScrollAnimations() {
        const animateElements = document.querySelectorAll('.feature-card, .editor-preview, .category-card');
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1 });
        
        animateElements.forEach(element => {
            element.style.opacity = '0';
            element.style.transform = 'translateY(30px)';
            element.style.transition = 'opacity 0.8s ease, transform 0.8s ease';
            observer.observe(element);
        });
    }

    // Initialiser les compteurs animés
    function initCounters() {
        const counters = document.querySelectorAll('.stat-number');
        const speed = 200;
        
        const animateCounter = () => {
            counters.forEach(counter => {
                const target = +counter.getAttribute('data-count');
                const count = +counter.innerText.replace(/,/g, '');
                const increment = target / speed;
                
                if (count < target) {
                    counter.innerText = Math.ceil(count + increment).toLocaleString();
                    setTimeout(animateCounter, 20);
                } else {
                    counter.innerText = target.toLocaleString();
                }
            });
        };
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    animateCounter();
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.5 });
        
        const statsSection = document.querySelector('.stats-section');
        if (statsSection) {
            observer.observe(statsSection);
        }
    }

    // Initialiser la navigation
    function initNavigation() {
        const navLinks = document.querySelectorAll('.nav-link');
        
        navLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                if (this.getAttribute('href') && this.getAttribute('href').startsWith('#')) {
                    e.preventDefault();
                    
                    const targetId = this.getAttribute('href');
                    if (targetId !== '#') {
                        const targetSection = document.querySelector(targetId);
                        if (targetSection) {
                            window.scrollTo({
                                top: targetSection.offsetTop - 100,
                                behavior: 'smooth'
                            });
                        }
                    }
                }
            });
        });
    }

    // Mettre à jour les informations en temps réel
    function updateLiveInfo() {
        setInterval(() => {
            // Mettre à jour la bourse
            const stockElement = document.querySelector('.info-item:nth-child(1) .info-value');
            if (stockElement) {
                const currentValue = parseFloat(stockElement.textContent.replace(',', ''));
                const change = (Math.random() - 0.5) * 100;
                const newValue = currentValue + change;
                stockElement.textContent = newValue.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
                
                const directionElement = stockElement.nextElementSibling;
                if (directionElement) {
                    if (change > 0) {
                        directionElement.textContent = '+' + change.toFixed(2) + '%';
                        directionElement.className = 'info-up ms-1';
                    } else {
                        directionElement.textContent = change.toFixed(2) + '%';
                        directionElement.className = 'info-down ms-1';
                    }
                }
            }
            
            // Mettre à jour la température
            const tempElement = document.querySelector('.info-item:nth-child(2) .info-value');
            if (tempElement) {
                const currentTemp = parseFloat(tempElement.textContent);
                const change = (Math.random() - 0.5) * 2;
                const newTemp = currentTemp + change;
                tempElement.textContent = newTemp.toFixed(1) + '°C';
            }
        }, 10000);
    }
    </script>
</body>
</html>