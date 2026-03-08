<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chez Jim Pizza | Meilleure pizza de la Côte de Beaupré</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Montserrat:wght@700;800&family=Playfair+Display:wght@700;800&display=swap" rel="stylesheet">
   
    <link rel="stylesheet" href="{{asset('css/styles.css')}}">
    
 
</head>
<body>
    <!-- Top Bar -->
    <div class="top-bar d-none d-lg-block">
        <div class="container">
            <div class="top-bar-left">
                <a href="mailto:katylavoie1972@hotmail.com" class="top-bar-item">
                    <i class="fas fa-envelope"></i>
                    <span>katylavoie1972@hotmail.com</span>
                </a>
                <a href="tel:4188279000" class="top-bar-item">
                    <i class="fas fa-phone"></i>
                    <span>418-827-9000</span>
                </a>
            </div>
            <div class="top-bar-right">
                <div class="top-bar-social">
                    <a href="https://www.facebook.com/groups/763632935467487" target="_blank" class="top-bar-item">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="https://www.goexploria.com/fr/company/146197/chez-jim-pizza" target="_blank" class="top-bar-item">
                        <i class="fab fa-youtube"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Header avec Mega Menu -->
    <header class="main-header">
        <nav class="navbar navbar-expand-lg">
            <div class="container">
                <a class="navbar-brand" href="#">
                    <img src="{{asset('images/chez-jim-pizza.png')}}" alt="Chez Jim Pizza">
                </a>
                
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                
                <div class="collapse navbar-collapse" id="navbarContent">
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link active" href="#home">Accueil</a>
                        </li>
                        
                        <!-- Mega Menu Carte -->
                        <li class="nav-item dropdown has-mega-menu mega-hover">
                            <a class="nav-link dropdown-toggle" href="#" id="carteDropdown" role="button" aria-expanded="false">
                                 Carte
                             </a>
                           <div class="dropdown-menu mega-menu" aria-labelledby="carteDropdown">
                                <div class="container-fluid">
                                    <div class="row">
                                        <!-- Colonne 1: Pizzas -->
                                        <div class="col-lg-3 col-md-6 mb-4">
                                            <h5><i class="fas fa-pizza-slice me-2"></i> Menu Pizzas</h5>
                                            <ul>
                                                <li><a href="#pizzas-classiques"><i class="fas fa-circle"></i> Pizzas Classiques</a></li>
                                                <li><a href="#pizzas-speciales"><i class="fas fa-star"></i> Pizzas Spéciales</a></li>
                                                <li><a href="#pizzas-vegetariennes"><i class="fas fa-leaf"></i> Pizzas Végétariennes</a></li>
                                                <li><a href="#pizzas-epicees"><i class="fas fa-pepper-hot"></i> Pizzas Épicées</a></li>
                                                <li><a href="#create-your-own"><i class="fas fa-magic"></i> Créez votre pizza</a></li>
                                            </ul>
                                        </div>
                                        
                                        <!-- Colonne 2: Entrées & Burgers -->
                                        <div class="col-lg-3 col-md-6 mb-4">
                                            <h5><i class="fas fa-utensils me-2"></i> Entrées & Plats</h5>
                                            <ul>
                                                <li><a href="#entrees"><i class="fas fa-appetizer"></i> Les Entrées</a></li>
                                                <li><a href="#burgers"><i class="fas fa-hamburger"></i> Burgers & Assiettes</a></li>
                                                <li><a href="#poutines"><i class="fas fa-cheese"></i> Poutines & Frites</a></li>
                                                <li><a href="#salades"><i class="fas fa-leaf"></i> Salades Fraîches</a></li>
                                                <li><a href="#menu-midi"><i class="fas fa-sun"></i> Menu Midi</a></li>
                                            </ul>
                                        </div>
                                        
                                        <!-- Colonne 3: Menu Map -->
                                        <div class="col-lg-3 col-md-6 mb-4">
                                            <div class="menu-map">
                                                <h5><i class="fas fa-map-marker-alt me-2"></i> Notre Carte Interactive</h5>
                                                <ul>
                                                    <li><a href="#menu-pizzas"><i class="fas fa-map-pin"></i> Explorer les pizzas</a></li>
                                                    <li><a href="#menu-burgers"><i class="fas fa-map-pin"></i> Explorer les burgers</a></li>
                                                    <li><a href="#menu-poutines"><i class="fas fa-map-pin"></i> Explorer les poutines</a></li>
                                                    <li><a href="#menu-salades"><i class="fas fa-map-pin"></i> Explorer les salades</a></li>
                                                </ul>
                                                
                                                <div class="map-container">
                                                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2724.991912612403!2d-71.20738268431515!3d46.84032257914217!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x4cb8966c75a7b2f1%3A0x2e5e1a5c4b8c8b9e!2sQu%C3%A9bec%20City%2C%20QC!5e0!3m2!1sen!2sca!4v1623345678901!5m2!1sen!2sca" allowfullscreen="" loading="lazy"></iframe>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Colonne 4: Image promotionnelle -->
                                        <div class="col-lg-3 col-md-6 mb-4">
                                            <div class="menu-img">
                                                <img src="https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80" alt="Pizza spéciale">
                                            </div>
                                            
                                            <div class="menu-promo mt-3">
                                                <h5>Spécial du Jour</h5>
                                                <p>Pizza Pepperoni 12" + Boisson</p>
                                                <div class="price">22.95$</div>
                                                <small>Valable aujourd'hui seulement</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        
                       <!-- Mega Menu Points de Vente -->
<li class="nav-item dropdown has-mega-menu mega-hover">
    <a class="nav-link dropdown-toggle" href="#" id="pointsVenteDropdown" role="button" aria-expanded="false">
        Points de ventes
    </a>
    <div class="dropdown-menu mega-menu" aria-labelledby="pointsVenteDropdown">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <h5 class="mb-4"><i class="fas fa-store me-2"></i> Nos Points de Vente</h5>
                    <div class="points-vente-grid">
                        <!-- Point de vente 1 -->
                        <div class="point-vente-card">
                            <h6 class="point-vente-title">Restaurant Principal</h6>
                            <div class="point-vente-info">
                                <p>
                                    <i class="fas fa-map-marker-alt"></i>
                                    <span>123 Rue de la Pizza, Québec, QC G1A 0A1</span>
                                </p>
                                <p>
                                    <i class="fas fa-phone"></i>
                                    <a href="tel:4188279001">418-827-9001</a>
                                </p>
                                <p>
                                    <i class="fas fa-clock"></i>
                                    <span>Lun-Sam: 11h-22h, Dim: 12h-21h</span>
                                </p>
                                <!-- NOUVEAU : Section produits vendus -->
                                <div class="produits-vendus">
                                    <h6><i class="fas fa-pizza-slice me-2"></i> Produits disponibles</h6>
                                    <div class="produits-list">
                                        <span class="badge bg-success">Pizza fraîche</span>
                                        <span class="badge bg-warning">Pizza congelée</span>
                                        <span class="badge bg-info">Entrées</span>
                                        <span class="badge bg-primary">Burgers</span>
                                        <span class="badge bg-danger">Poutines</span>
                                        <span class="badge bg-secondary">Salades</span>
                                    </div>
                                </div>
                            </div>
                            <div class="point-vente-map">
                                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2724.991912612403!2d-71.20738268431515!3d46.84032257914217!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x4cb8966c75a7b2f1%3A0x2e5e1a5c4b8c8b9e!2sQu%C3%A9bec%20City%2C%20QC!5e0!3m2!1sen!2sca!4v1623345678901!5m2!1sen!2sca" allowfullscreen loading="lazy"></iframe>
                            </div>
                        </div>
                        
                        <!-- Point de vente 2 -->
                        <div class="point-vente-card">
                            <h6 class="point-vente-title">Succursale Beaupré</h6>
                            <div class="point-vente-info">
                                <p>
                                    <i class="fas fa-map-marker-alt"></i>
                                    <span>456 Av. des Saveurs, Beaupré, QC G0A 1E0</span>
                                </p>
                                <p>
                                    <i class="fas fa-phone"></i>
                                    <a href="tel:4188279002">418-827-9002</a>
                                </p>
                                <p>
                                    <i class="fas fa-clock"></i>
                                    <span>Mar-Dim: 11h-21h, Lun fermé</span>
                                </p>
                                <!-- NOUVEAU : Section produits vendus -->
                                <div class="produits-vendus">
                                    <h6><i class="fas fa-pizza-slice me-2"></i> Produits disponibles</h6>
                                    <div class="produits-list">
                                        <span class="badge bg-success">Pizza fraîche</span>
                                        <span class="badge bg-warning">Pizza congelée</span>
                                        <span class="badge bg-info">Entrées</span>
                                        <span class="badge bg-primary">Menu midi</span>
                                    </div>
                                </div>
                            </div>
                            <div class="point-vente-map">
                                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2725.123456789012!2d-71.215478!3d46.850123!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x4cb8967d7b2f3a1b%3A0x3e4d1c2b3a4b5c6d!2sBeaupr%C3%A9%2C%20QC!5e0!3m2!1sen!2sca!4v1623345678901!5m2!1sen!2sca" allowfullscreen loading="lazy"></iframe>
                            </div>
                        </div>
                        
                        <!-- Point de vente 3 -->
                        <div class="point-vente-card">
                            <h6 class="point-vente-title">Kiosque Mont-Sainte-Anne</h6>
                            <div class="point-vente-info">
                                <p>
                                    <i class="fas fa-map-marker-alt"></i>
                                    <span>Station de ski Mont-Sainte-Anne, Beaupré</span>
                                </p>
                                <p>
                                    <i class="fas fa-phone"></i>
                                    <a href="tel:4188279003">418-827-9003</a>
                                </p>
                                <p>
                                    <i class="fas fa-clock"></i>
                                    <span>Saison hiver: 9h-17h, Été: 10h-16h</span>
                                </p>
                                <!-- NOUVEAU : Section produits vendus -->
                                <div class="produits-vendus">
                                    <h6><i class="fas fa-pizza-slice me-2"></i> Produits disponibles</h6>
                                    <div class="produits-list">
                                        <span class="badge bg-warning">Pizza congelée</span>
                                        <span class="badge bg-info">Sandwiches</span>
                                        <span class="badge bg-primary">Boissons</span>
                                        <span class="badge bg-secondary">Collations</span>
                                    </div>
                                </div>
                            </div>
                            <div class="point-vente-map">
                                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2725.234567890123!2d-71.225678!3d46.860234!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x4cb8968e8b2f4a1c%3A0x4d5e6f7g8h9i0j1!2sMont-Sainte-Anne%2C%20Beaupr%C3%A9%2C%20QC!5e0!3m2!1sen!2sca!4v1623345678901!5m2!1sen!2sca" allowfullscreen loading="lazy"></iframe>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</li>
                        
                        
                        <li class="nav-item">
                            <a class="nav-link" href="#webtv">Web TV</a>
                        </li>
                        
                        <!-- Mega Menu Photos -->
                        <li class="nav-item dropdown has-mega-menu mega-hover">
                            <a class="nav-link dropdown-toggle" href="#" id="photosDropdown" role="button" aria-expanded="false">
                                Photos
                            </a>
                            <div class="dropdown-menu mega-menu photos-mega-menu" aria-labelledby="photosDropdown">
                                <div class="container-fluid">
                                    <div class="row">
                                        <!-- Galerie photos -->
                                        <div class="col-lg-8 mb-4">
                                            <h5><i class="fas fa-images me-2"></i> Galerie Photos</h5>
                                            <div class="photo-grid">
                                                <div class="photo-item">
                                                    <img src="https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Intérieur restaurant">
                                                </div>
                                                <div class="photo-item">
                                                    <img src="https://images.unsplash.com/photo-1555396273-367ea4eb4db5?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Pizza préparée">
                                                </div>
                                                <div class="photo-item">
                                                    <img src="https://images.unsplash.com/photo-1565299585323-38d6b0865b47?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Pizza au four">
                                                </div>
                                                <div class="photo-item">
                                                    <img src="https://images.unsplash.com/photo-1559925393-8be0ec4767c8?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Événement">
                                                </div>
                                                <div class="photo-item">
                                                    <img src="https://images.unsplash.com/photo-1578474846511-04ba529f0b88?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Notre équipe">
                                                </div>
                                                <div class="photo-item">
                                                    <img src="https://images.unsplash.com/photo-1571066811602-716837d681de?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Ingrédients frais">
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Catégories photos -->
                                        <div class="col-lg-4 mb-4">
                                            <h5><i class="fas fa-folder me-2"></i> Catégories</h5>
                                            <ul>
                                                <li><a href="#galerie-interieur"><i class="fas fa-store"></i> Intérieur & Ambiance</a></li>
                                                <li><a href="#galerie-plats"><i class="fas fa-utensils"></i> Nos Plats & Recettes</a></li>
                                                <li><a href="#galerie-evenements"><i class="fas fa-glass-cheers"></i> Événements & Fêtes</a></li>
                                                <li><a href="#galerie-equipe"><i class="fas fa-users"></i> Notre Équipe</a></li>
                                                <li><a href="#galerie-ingredients"><i class="fas fa-carrot"></i> Ingrédients Frais</a></li>
                                            </ul>
                                            
                                            <div class="menu-promo mt-4">
                                                <h5>Partagez vos photos!</h5>
                                                <p>Utilisez <strong>#ChezJimPizza</strong> sur Instagram pour apparaître ici</p>
                                                <a href="#" class="btn btn-sm btn-primary mt-2">
                                                    <i class="fab fa-instagram me-1"></i> Voir sur Instagram
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        
                        <!-- <li class="nav-item">
                            <a class="nav-link" href="#certificats">Certificats Cadeaux</a>
                        </li> -->
                        
                       <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="extrasDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Les Extras
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="extrasDropdown">
                                <li><a class="dropdown-item" href="#extras-viande"><i class="fas fa-bacon"></i> Extras Viande</a></li>
                                <li><a class="dropdown-item" href="#extras-legumes"><i class="fas fa-carrot"></i> Extras Légumes</a></li>
                                <li><a class="dropdown-item" href="#extras-divers"><i class="fas fa-cheese"></i> Extras Divers</a></li>
                            </ul>
                        </li>
                        
                        <!-- Mega Menu Promotions -->
                       <li class="nav-item dropdown has-mega-menu mega-hover">
                            <a class="nav-link dropdown-toggle" href="#" id="promosDropdown" role="button" aria-expanded="false">
                                Promotions
                            </a>
                            <div class="dropdown-menu mega-menu promo-mega-menu" aria-labelledby="promosDropdown">
                                <div class="container-fluid">
                                    <div class="row">
                                        <!-- Promo 1 -->
                                        <div class="col-lg-3 col-md-6 mb-4">
                                            <div class="promo-card">
                                                <div class="promo-badge">Famille</div>
                                                <h5>Formule Famille</h5>
                                                <p>2 Pizzas 12" + Frites + 4 Boissons</p>
                                                <div class="price">49.95$</div>
                                                <small>Économisez 15$</small>
                                            </div>
                                        </div>
                                        
                                        <!-- Promo 2 -->
                                        <div class="col-lg-3 col-md-6 mb-4">
                                            <div class="promo-card">
                                                <div class="promo-badge">Duo</div>
                                                <h5>Spécial Duo</h5>
                                                <p>Pizza 10" + Salade César + 2 Boissons</p>
                                                <div class="price">29.95$</div>
                                                <small>Parfait pour 2 personnes</small>
                                            </div>
                                        </div>
                                        
                                        <!-- Promo 3 -->
                                        <div class="col-lg-3 col-md-6 mb-4">
                                            <div class="promo-card">
                                                <div class="promo-badge">Midi</div>
                                                <h5>Menu Midi Express</h5>
                                                <p>Pizza individuelle + Boisson + Dessert</p>
                                                <div class="price">12.95$</div>
                                                <small>Du lundi au vendredi 11h-14h</small>
                                            </div>
                                        </div>
                                        
                                        <!-- Promo 4 -->
                                        <div class="col-lg-3 col-md-6 mb-4">
                                            <div class="promo-card">
                                                <div class="promo-badge">Livraison</div>
                                                <h5>Livraison Gratuite</h5>
                                                <p>Commande de 40$ et plus</p>
                                                <div class="price">0$</div>
                                                <small>Dans un rayon de 5km</small>
                                                <a href="#promos" class="btn btn-sm btn-primary mt-3">Voir toutes les promos</a>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row mt-3">
                                        <div class="col-12 text-center">
                                            <p class="mb-0">
                                                <i class="fas fa-exclamation-circle me-2"></i>
                                                Les promotions sont valables jusqu'au 31 Janvier 2026. Une promotion par commande.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        
                        <!-- Bouton téléphone -->
                        <li class="nav-item">
                            <a href="tel:4188279000" class="nav-link phone-btn">
                                <i class="fas fa-phone"></i> 418-827-9000
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <!-- Le reste du code reste inchangé -->
    <!-- Hero Slider avec SOLUTION DE CONTOURNEMENT -->
    <section class="hero-slider" id="home">
        <div class="video-slider-container">
            <!-- Slide 1: Vidéo YouTube avec SOLUTION SPÉCIALE -->
            <div class="video-slide active" id="videoSlide1">
                <div class="youtube-video-container">
<iframe 
    src="https://www.youtube.com/embed/r9lfyTtewQI?autoplay=1&mute=1&loop=1&playlist=r9lfyTtewQI"
    title="YouTube video"
    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
    allowfullscreen>
</iframe>
                    <div class="video-overlay-dark" id="videoOverlay1"></div>
                </div>
            </div>
            
            <!-- Slide 2: Image -->
            <div class="video-slide">
                <img src="https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1600&q=80" alt="Apportez votre vin">
            </div>
            
            <!-- Slide 3: Image -->
            <div class="video-slide">
                <img src="https://images.unsplash.com/photo-1551183053-bf91a1d81141?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1600&q=80" alt="Livraison à domicile">
            </div>
            
            <!-- Slide 4: Image -->
            <div class="video-slide">
                <img src="https://images.unsplash.com/photo-1513104890138-7c749659a591?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1600&q=80" alt="Notre restaurant">
            </div>
            
            <!-- Slide 5: Image -->
            <div class="video-slide">
                <img src="https://images.unsplash.com/photo-1595708684082-a173bb3a06c5?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1600&q=80" alt="Nos spécialités">
            </div>
        </div>
        
        <div class="slider-content">
            <div class="slider-text">
                <h1 class="slider-title">Chez Jim Pizza</h1>
                <p class="slider-subtitle">Découvrez la meilleure pizza de la Côte de Beaupré, préparée avec des ingrédients frais et une passion authentique.</p>
                <div class="hero-btns">
                    <a href="#menu" class="btn btn-primary-custom btn-custom me-3">Voir notre carte</a>
                    <a href="#promos" class="btn btn-secondary-custom btn-custom">Découvrir nos promos</a>
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

    <!-- Le reste du code HTML reste exactement le même... -->
    <!-- Section Menu Map -->
    <section class="section-map" id="menu-map">
        <div class="container">
            <h2 class="section-title text-center">Carte Interactive de Notre Menu</h2>
            <p class="text-center mb-5" style="max-width: 700px; margin: 0 auto; color: var(--gray-color);">
                Explorez notre menu comme jamais auparavant. Cliquez sur les sections pour découvrir nos délicieuses options.
            </p>
            
            <div class="menu-map-container">
                <div class="row">
                    <!-- Colonne de navigation -->
                    <div class="col-lg-4 mb-4">
                        <div class="map-navigation">
                            <h4><i class="fas fa-map-signs me-2"></i> Navigation Rapide</h4>
                            <div class="list-group">
                                <a href="#pizzas" class="list-group-item list-group-item-action">
                                    <i class="fas fa-pizza-slice me-2"></i> Les Pizzas
                                    <span class="badge bg-primary float-end">12 variétés</span>
                                </a>
                                <a href="#entrees" class="list-group-item list-group-item-action">
                                    <i class="fas fa-appetizer me-2"></i> Les Entrées
                                    <span class="badge bg-primary float-end">8 options</span>
                                </a>
                                <a href="#burgers" class="list-group-item list-group-item-action">
                                    <i class="fas fa-hamburger me-2"></i> Burgers & Assiettes
                                    <span class="badge bg-primary float-end">6 choix</span>
                                </a>
                                <a href="#poutines" class="list-group-item list-group-item-action">
                                    <i class="fas fa-cheese me-2"></i> Poutines & Frites
                                    <span class="badge bg-primary float-end">10 spécialités</span>
                                </a>
                                <a href="#salades" class="list-group-item list-group-item-action">
                                    <i class="fas fa-leaf me-2"></i> Salades
                                    <span class="badge bg-primary float-end">5 recettes</span>
                                </a>
                                <a href="#extras" class="list-group-item list-group-item-action">
                                    <i class="fas fa-plus-circle me-2"></i> Les Extras
                                    <span class="badge bg-primary float-end">15+ ingrédients</span>
                                </a>
                            </div>
                            
                            <div class="mt-4">
                                <h5><i class="fas fa-fire me-2"></i> Recommandations</h5>
                                <ul class="list-unstyled">
                                    <li class="mb-2">
                                        <i class="fas fa-crown text-warning me-2"></i>
                                        <strong>Pizza Spéciale Jim:</strong> La préférée des clients
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-pepper-hot text-danger me-2"></i>
                                        <strong>Poutine Épicée:</strong> Notre signature
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-leaf text-success me-2"></i>
                                        <strong>Salade Méditerranéenne:</strong> Fraîche et légère
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Carte interactive -->
                    <div class="col-lg-8">
                        <div class="interactive-map">
                            <div class="map-visual">
                                <div class="row g-3">
                                    <!-- Pizzas -->
                                    <div class="col-md-6">
                                        <div class="map-section pizza-section" data-target="#pizzas">
                                            <div class="map-icon">
                                                <i class="fas fa-pizza-slice"></i>
                                            </div>
                                            <h5>Pizzas</h5>
                                            <p>12 variétés de pizzas artisanales</p>
                                            <div class="map-badge">À partir de 15$</div>
                                        </div>
                                    </div>
                                    
                                    <!-- Entrées -->
                                    <div class="col-md-6">
                                        <div class="map-section entrees-section" data-target="#entrees">
                                            <div class="map-icon">
                                                <i class="fas fa-appetizer"></i>
                                            </div>
                                            <h5>Entrées</h5>
                                            <p>Démarrez votre repas avec style</p>
                                            <div class="map-badge">8 options</div>
                                        </div>
                                    </div>
                                    
                                    <!-- Burgers -->
                                    <div class="col-md-6">
                                        <div class="map-section burgers-section" data-target="#burgers">
                                            <div class="map-icon">
                                                <i class="fas fa-hamburger"></i>
                                            </div>
                                            <h5>Burgers</h5>
                                            <p>Burgers juteux et assiettes gourmandes</p>
                                            <div class="map-badge">À partir de 12$</div>
                                        </div>
                                    </div>
                                    
                                    <!-- Poutines -->
                                    <div class="col-md-6">
                                        <div class="map-section poutines-section" data-target="#poutines">
                                            <div class="map-icon">
                                                <i class="fas fa-cheese"></i>
                                            </div>
                                            <h5>Poutines</h5>
                                            <p>Notre spécialité québécoise réinventée</p>
                                            <div class="map-badge">10 variétés</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Légende -->
                            <div class="map-legend mt-4">
                                <h5><i class="fas fa-key me-2"></i> Légende de la Carte</h5>
                                <div class="row">
                                    <div class="col-6 col-md-3">
                                        <div class="legend-item">
                                            <span class="legend-color" style="background-color: #e63946;"></span>
                                            <span>Populaire</span>
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-3">
                                        <div class="legend-item">
                                            <span class="legend-color" style="background-color: #f4a261;"></span>
                                            <span>Nouveauté</span>
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-3">
                                        <div class="legend-item">
                                            <span class="legend-color" style="background-color: #2a9d8f;"></span>
                                            <span>Végétarien</span>
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-3">
                                        <div class="legend-item">
                                            <span class="legend-color" style="background-color: #e76f51;"></span>
                                            <span>Épicé</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Video Section -->
    <section class="video-section" id="webtv">
        <div class="container">
            <h2 class="section-title text-center text-white">Notre Web TV</h2>
            <p class="text-center text-white mb-5" style="max-width: 700px; margin: 0 auto; opacity: 0.9;">
                Regardez notre chaîne dédiée pour découvrir nos recettes, coulisses et événements spéciaux.
            </p>
            
            <div class="video-container">
                <div class="video-wrapper">
                <iframe src="https://www.youtube.com/embed/r9lfyTtewQI&t?autoplay=1&mute=1&loop=1&playlist=r9lfyTtewQI" title="YouTube video" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>

                    <div class="video-overlay" id="videoOverlay">
                        <div class="play-btn" id="playButton">
                            <i class="fas fa-play"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Les autres sections restent les mêmes... -->
    <!-- Menu Section -->
    <section class="menu-section" id="menu">
        <div class="container">
            <h2 class="section-title text-center">Notre Carte</h2>
            <p class="text-center mb-5" style="max-width: 700px; margin: 0 auto; color: var(--gray-color);">
                Découvrez notre sélection de pizzas, entrées, poutines et burgers préparés avec des ingrédients frais de qualité.
            </p>
            
            <!-- Menu Categories -->
            <div class="menu-categories">
                <button class="menu-category-btn active" data-category="entrees">Les Entrées</button>
                <button class="menu-category-btn" data-category="pizzas">Les Pizzas</button>
                <button class="menu-category-btn" data-category="burgers">Burgers & Assiettes</button>
                <button class="menu-category-btn" data-category="poutines">Poutines & Frites</button>
                <button class="menu-category-btn" data-category="salades">Salades</button>
                <button class="menu-category-btn" data-category="extras">Les Extras</button>
            </div>
            
            <!-- Menu Content -->
            <div class="menu-items-container">
                <!-- Entrées -->
                <div id="entrees" class="menu-category-content active">
                    <div class="row">
                        <!-- Oignons français -->
                        <div class="col-md-6 col-lg-4">
                            <div class="menu-item-card">
                                <img src="https://images.unsplash.com/photo-1571091718767-18b5b1457add?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80" alt="Oignons français" class="menu-item-img">
                                <div class="menu-item-body">
                                    <div class="menu-item-title">
                                        <h4>Oignons français</h4>
                                        <span class="menu-item-price">8.75$</span>
                                    </div>
                                    <p class="menu-item-ingredients">Oignons croustillants servis avec sauce</p>
                                    <div class="row">
                                        <div class="col-6">Petit: 8.75$</div>
                                        <div class="col-6">Grand: 12.50$</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Bâtonnets de fromage -->
                        <div class="col-md-6 col-lg-4">
                            <div class="menu-item-card">
                                <img src="https://images.unsplash.com/photo-1563379926898-05f4575a45d8?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80" alt="Bâtonnets de fromage" class="menu-item-img">
                                <div class="menu-item-body">
                                    <div class="menu-item-title">
                                        <h4>Bâtonnets de fromage</h4>
                                        <span class="menu-item-price">8.99$</span>
                                    </div>
                                    <p class="menu-item-ingredients">Bâtonnets de fromage fondant</p>
                                    <div class="row">
                                        <div class="col-4">4 bâtonnets: 8.99$</div>
                                        <div class="col-4">6 bâtonnets: 10.25$</div>
                                        <div class="col-4">9 bâtonnets: 14.50$</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Ailes de poulet -->
                        <div class="col-md-6 col-lg-4">
                            <div class="menu-item-card">
                                <img src="https://images.unsplash.com/photo-1567620832903-9fc6debc209f?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80" alt="Ailes de poulet" class="menu-item-img">
                                <div class="menu-item-body">
                                    <div class="menu-item-title">
                                        <h4>Ailes de poulet</h4>
                                        <span class="menu-item-price">10.45$</span>
                                    </div>
                                    <p class="menu-item-ingredients">Ailes de poulet croustillantes</p>
                                    <div class="row">
                                        <div class="col-6">6 ailes: 10.45$</div>
                                        <div class="col-6">12 ailes: 16.93$</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Pizzas -->
                <div id="pizzas" class="menu-category-content">
                    <div class="row">
                        <!-- Pizza Pepperoni -->
                        <div class="col-md-6 col-lg-4">
                            <div class="menu-item-card">
                                <img src="https://images.unsplash.com/photo-1595708684082-a173bb3a06c5?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80" alt="Pizza Pepperoni" class="menu-item-img">
                                <div class="menu-item-body">
                                    <div class="menu-item-title">
                                        <h4>Pepperoni</h4>
                                        <span class="menu-item-price">15.00$</span>
                                    </div>
                                    <p class="menu-item-ingredients">Sauce, pepperoni et mozzarella</p>
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Taille</th>
                                                <th>Prix</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr><td>8"</td><td>15.00$</td></tr>
                                            <tr><td>10"</td><td>19.79$</td></tr>
                                            <tr><td>12"</td><td>27.50$</td></tr>
                                            <tr><td>14"</td><td>33.75$</td></tr>
                                            <tr><td>16"</td><td>37.95$</td></tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Pizza Hawaïenne -->
                        <div class="col-md-6 col-lg-4">
                            <div class="menu-item-card">
                                <img src="https://images.unsplash.com/photo-1604382354936-07c5d9983bd3?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80" alt="Pizza Hawaïenne" class="menu-item-img">
                                <div class="menu-item-body">
                                    <div class="menu-item-title">
                                        <h4>Hawaïenne</h4>
                                        <span class="menu-item-price">15.50$</span>
                                    </div>
                                    <p class="menu-item-ingredients">Sauce, jambon, mozzarella et ananas</p>
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Taille</th>
                                                <th>Prix</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr><td>8"</td><td>15.50$</td></tr>
                                            <tr><td>10"</td><td>20.90$</td></tr>
                                            <tr><td>12"</td><td>27.85$</td></tr>
                                            <tr><td>14"</td><td>33.75$</td></tr>
                                            <tr><td>16"</td><td>39.50$</td></tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Pizza Végétarienne -->
                        <div class="col-md-6 col-lg-4">
                            <div class="menu-item-card">
                                <img src="https://images.unsplash.com/photo-1571066811602-716837d681de?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80" alt="Pizza Végétarienne" class="menu-item-img">
                                <div class="menu-item-body">
                                    <div class="menu-item-title">
                                        <h4>Végétarienne</h4>
                                        <span class="menu-item-price">14.76$</span>
                                    </div>
                                    <p class="menu-item-ingredients">Sauce, champignon, mozzarella, piments rouges et verts, tomates et oignons</p>
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Taille</th>
                                                <th>Prix</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr><td>8"</td><td>14.76$</td></tr>
                                            <tr><td>10"</td><td>19.50$</td></tr>
                                            <tr><td>12"</td><td>26.91$</td></tr>
                                            <tr><td>14"</td><td>31.70$</td></tr>
                                            <tr><td>16"</td><td>36.45$</td></tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                
                <!-- Burgers -->
                <div id="burgers" class="menu-category-content">
                    <div class="row">
                        <!-- Pizza Pepperoni -->
                        <div class="col-md-6 col-lg-4">
                            <div class="menu-item-card">
                                <img src="{{ asset('images/9093d02c-620a-4939-a877-2f9bbc03f2ca-1280x854.jpg') }}" alt="Pizza Pepperoni" class="menu-item-img">
                                <div class="menu-item-body">
                                    <div class="menu-item-title">
                                        <h4>Cheeseburger</h4>
                                        <span class="menu-item-price">15.00$</span>
                                    </div>
                                    <p class="menu-item-ingredients">Sauce, pepperoni et mozzarella</p>
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Taille</th>
                                                <th>Prix</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr><td>8"</td><td>15.00$</td></tr>
                                            <tr><td>10"</td><td>19.79$</td></tr>
                                            <tr><td>12"</td><td>27.50$</td></tr>
                                            <tr><td>14"</td><td>33.75$</td></tr>
                                            <tr><td>16"</td><td>37.95$</td></tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Pizza Hawaïenne -->
                        <div class="col-md-6 col-lg-4">
                            <div class="menu-item-card">
                                <img src="{{asset('images/Beef-Burgers-067.jpg')}}" alt="Pizza Hawaïenne" class="menu-item-img">
                                <div class="menu-item-body">
                                    <div class="menu-item-title">
                                        <h4>Classic Beef Burger</h4>
                                        <span class="menu-item-price">15.50$</span>
                                    </div>
                                    <p class="menu-item-ingredients">Sauce, jambon, mozzarella et ananas</p>
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Taille</th>
                                                <th>Prix</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr><td>8"</td><td>15.50$</td></tr>
                                            <tr><td>10"</td><td>20.90$</td></tr>
                                            <tr><td>12"</td><td>27.85$</td></tr>
                                            <tr><td>14"</td><td>33.75$</td></tr>
                                            <tr><td>16"</td><td>39.50$</td></tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Pizza Végétarienne -->
                        <div class="col-md-6 col-lg-4">
                            <div class="menu-item-card">
                                <img src="{{asset('images/half-veggie-scaled.jpg')}}" alt="Pizza Végétarienne" class="menu-item-img">
                                <div class="menu-item-body">
                                    <div class="menu-item-title">
                                        <h4>Veggie Burger</h4>
                                        <span class="menu-item-price">14.76$</span>
                                    </div>
                                    <p class="menu-item-ingredients">Sauce, champignon, mozzarella, piments rouges et verts, tomates et oignons</p>
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Taille</th>
                                                <th>Prix</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr><td>8"</td><td>14.76$</td></tr>
                                            <tr><td>10"</td><td>19.50$</td></tr>
                                            <tr><td>12"</td><td>26.91$</td></tr>
                                            <tr><td>14"</td><td>31.70$</td></tr>
                                            <tr><td>16"</td><td>36.45$</td></tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- poutines -->
                <div id="poutines" class="menu-category-content">
                    <div class="row">
                        <!-- Pizza Pepperoni -->
                        <div class="col-md-6 col-lg-4">
                            <div class="menu-item-card">
                                <img src="{{ asset('images/poutine classique.jpg') }}" alt="Pizza Pepperoni" class="menu-item-img">
                                <div class="menu-item-body">
                                    <div class="menu-item-title">
                                        <h4>Poutine Classique (Frites & Sauce)</h4>
                                        <span class="menu-item-price">15.00$</span>
                                    </div>
                                    <p class="menu-item-ingredients">Sauce, pepperoni et mozzarella</p>
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Taille</th>
                                                <th>Prix</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr><td>8"</td><td>15.00$</td></tr>
                                            <tr><td>10"</td><td>19.79$</td></tr>
                                            <tr><td>12"</td><td>27.50$</td></tr>
                                            <tr><td>14"</td><td>33.75$</td></tr>
                                            <tr><td>16"</td><td>37.95$</td></tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Pizza Hawaïenne -->
                        <div class="col-md-6 col-lg-4">
                            <div class="menu-item-card">
                                <img src="https://images.unsplash.com/photo-1604382354936-07c5d9983bd3?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80" alt="Pizza Hawaïenne" class="menu-item-img">
                                <div class="menu-item-body">
                                    <div class="menu-item-title">
                                        <h4>Hawaïenne</h4>
                                        <span class="menu-item-price">15.50$</span>
                                    </div>
                                    <p class="menu-item-ingredients">Sauce, jambon, mozzarella et ananas</p>
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Taille</th>
                                                <th>Prix</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr><td>8"</td><td>15.50$</td></tr>
                                            <tr><td>10"</td><td>20.90$</td></tr>
                                            <tr><td>12"</td><td>27.85$</td></tr>
                                            <tr><td>14"</td><td>33.75$</td></tr>
                                            <tr><td>16"</td><td>39.50$</td></tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Pizza Végétarienne -->
                        <div class="col-md-6 col-lg-4">
                            <div class="menu-item-card">
                                <img src="https://images.unsplash.com/photo-1571066811602-716837d681de?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80" alt="Pizza Végétarienne" class="menu-item-img">
                                <div class="menu-item-body">
                                    <div class="menu-item-title">
                                        <h4>Végétarienne</h4>
                                        <span class="menu-item-price">14.76$</span>
                                    </div>
                                    <p class="menu-item-ingredients">Sauce, champignon, mozzarella, piments rouges et verts, tomates et oignons</p>
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Taille</th>
                                                <th>Prix</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr><td>8"</td><td>14.76$</td></tr>
                                            <tr><td>10"</td><td>19.50$</td></tr>
                                            <tr><td>12"</td><td>26.91$</td></tr>
                                            <tr><td>14"</td><td>31.70$</td></tr>
                                            <tr><td>16"</td><td>36.45$</td></tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                
                <!-- salades -->
                <div id="salades" class="menu-category-content">
                    <div class="row">
                        <!-- Pizza Pepperoni -->
                        <div class="col-md-6 col-lg-4">
                            <div class="menu-item-card">
                                <img src="{{ asset('images/9093d02c-620a-4939-a877-2f9bbc03f2ca-1280x854.jpg') }}" alt="Pizza Pepperoni" class="menu-item-img">
                                <div class="menu-item-body">
                                    <div class="menu-item-title">
                                        <h4>Salade Fraîche du Jardin</h4>
                                        <span class="menu-item-price">15.00$</span>
                                    </div>
                                    <p class="menu-item-ingredients">Sauce, pepperoni et mozzarella</p>
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Taille</th>
                                                <th>Prix</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr><td>8"</td><td>15.00$</td></tr>
                                            <tr><td>10"</td><td>19.79$</td></tr>
                                            <tr><td>12"</td><td>27.50$</td></tr>
                                            <tr><td>14"</td><td>33.75$</td></tr>
                                            <tr><td>16"</td><td>37.95$</td></tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Pizza Hawaïenne -->
                        <div class="col-md-6 col-lg-4">
                            <div class="menu-item-card">
                                <img src="https://images.unsplash.com/photo-1604382354936-07c5d9983bd3?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80" alt="Pizza Hawaïenne" class="menu-item-img">
                                <div class="menu-item-body">
                                    <div class="menu-item-title">
                                        <h4>Hawaïenne</h4>
                                        <span class="menu-item-price">15.50$</span>
                                    </div>
                                    <p class="menu-item-ingredients">Sauce, jambon, mozzarella et ananas</p>
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Taille</th>
                                                <th>Prix</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr><td>8"</td><td>15.50$</td></tr>
                                            <tr><td>10"</td><td>20.90$</td></tr>
                                            <tr><td>12"</td><td>27.85$</td></tr>
                                            <tr><td>14"</td><td>33.75$</td></tr>
                                            <tr><td>16"</td><td>39.50$</td></tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Pizza Végétarienne -->
                        <div class="col-md-6 col-lg-4">
                            <div class="menu-item-card">
                                <img src="https://images.unsplash.com/photo-1571066811602-716837d681de?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80" alt="Pizza Végétarienne" class="menu-item-img">
                                <div class="menu-item-body">
                                    <div class="menu-item-title">
                                        <h4>Végétarienne</h4>
                                        <span class="menu-item-price">14.76$</span>
                                    </div>
                                    <p class="menu-item-ingredients">Sauce, champignon, mozzarella, piments rouges et verts, tomates et oignons</p>
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Taille</th>
                                                <th>Prix</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr><td>8"</td><td>14.76$</td></tr>
                                            <tr><td>10"</td><td>19.50$</td></tr>
                                            <tr><td>12"</td><td>26.91$</td></tr>
                                            <tr><td>14"</td><td>31.70$</td></tr>
                                            <tr><td>16"</td><td>36.45$</td></tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Promotions Section -->
    <section id="promos" class="py-5" style="background-color: #f9f9f9;">
        <div class="container">
            <h2 class="section-title text-center">Promotions Pizzas Chez Jim</h2>
            <p class="text-center mb-5" style="max-width: 700px; margin: 0 auto; color: var(--gray-color);">
                Profitez de nos formules promotionnelles exceptionnelles! Une promo par commande ou par adresse.
            </p>
            
            <div class="row">
                <!-- Promo 1 -->
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="promo-card">
                        <div class="promo-badge">Promo</div>
                        <img src="https://images.unsplash.com/photo-1565299585323-38d6b0865b47?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80" alt="Promotion Pizza 10" class="img-fluid" style="height: 200px; width: 100%; object-fit: cover;">
                        <div class="p-4">
                            <h4 class="mb-3">Formule 10"</h4>
                            <p class="mb-2">Pizza 10" + petite frite + 1 liqueur</p>
                            <p class="text-primary" style="font-size: 1.8rem; font-weight: 700;">25.65$</p>
                            <p class="text-muted small mt-2">Pizza garnie, pepperoni, végétarienne ou fromage</p>
                        </div>
                    </div>
                </div>
                
                <!-- Promo 2 -->
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="promo-card">
                        <div class="promo-badge">Top Vente</div>
                        <img src="https://images.unsplash.com/photo-1574071318508-1cdbab80d002?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80" alt="Promotion Pizza 12" class="img-fluid" style="height: 200px; width: 100%; object-fit: cover;">
                        <div class="p-4">
                            <h4 class="mb-3">Formule 12"</h4>
                            <p class="mb-2">Pizza 12" + moyenne frite + 2 liqueurs</p>
                            <p class="text-primary" style="font-size: 1.8rem; font-weight: 700;">36.96$</p>
                            <p class="text-muted small mt-2">Pizza garnie, pepperoni, végétarienne ou fromage</p>
                        </div>
                    </div>
                </div>
                
                <!-- Promo 3 -->
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="promo-card">
                        <div class="promo-badge">Famille</div>
                        <img src="https://images.unsplash.com/photo-1534308983496-4fabb1a015ee?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80" alt="Promotion Pizza 14" class="img-fluid" style="height: 200px; width: 100%; object-fit: cover;">
                        <div class="p-4">
                            <h4 class="mb-3">Formule 14"</h4>
                            <p class="mb-2">Pizza 14" + moyenne frite + 3 liqueurs</p>
                            <p class="text-primary" style="font-size: 1.8rem; font-weight: 700;">45.95$</p>
                            <p class="text-muted small mt-2">Pizza garnie, pepperoni, végétarienne ou fromage</p>
                        </div>
                    </div>
                </div>
                
                <!-- Promo 4 -->
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="promo-card">
                        <div class="promo-badge">XXL</div>
                        <img src="https://images.unsplash.com/photo-1601924582970-9238bcb495d9?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80" alt="Promotion Pizza 16" class="img-fluid" style="height: 200px; width: 100%; object-fit: cover;">
                        <div class="p-4">
                            <h4 class="mb-3">Formule 16"</h4>
                            <p class="mb-2">Pizza 16" + grosse frite + 4 liqueurs</p>
                            <p class="text-primary" style="font-size: 1.8rem; font-weight: 700;">51.95$</p>
                            <p class="text-muted small mt-2">Pizza garnie, pepperoni, végétarienne ou fromage</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- NOUVELLE SECTION: Gallery Slider avec Preview -->
<section class="gallery-preview-section" id="gallery-preview">
    <div class="container">
        <h2 class="section-title text-center">Notre Galerie Interactive</h2>
        <p class="text-center mb-5" style="max-width: 700px; margin: 0 auto; color: var(--gray-color);">
            Explorez nos spécialités. Cliquez sur une miniature pour voir l'image en grand.
        </p>
        
        <div class="row">
            <!-- Colonne principale - Image Preview Grande -->
            <div class="col-lg-8 mb-4 mb-lg-0">
                <div class="main-preview-container">
                    <div class="main-preview-image">
                        <img id="main-preview-img" src="https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80" 
                             alt="Pizza Signature">
                        <div class="preview-overlay">
                            <div class="preview-info">
                                <h3 id="main-preview-title">Pizza Signature Jim</h3>
                                <p id="main-preview-desc">Notre création exclusive avec fromages fins</p>
                                <div class="preview-badge" id="main-preview-badge">
                                    <i class="fas fa-fire"></i> Populaire
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Controls pour l'image principale -->
                    <div class="preview-controls">
                        <button class="preview-control-btn" id="prev-main">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <button class="preview-control-btn" id="next-main">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                        <button class="preview-control-btn" id="zoom-main">
                            <i class="fas fa-search-plus"></i>
                        </button>
                        <button class="preview-control-btn" id="play-pause">
                            <i class="fas fa-play" id="play-icon"></i>
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Colonne secondaire - Miniatures -->
            <div class="col-lg-4">
                <div class="thumbnails-column">
                    <div class="thumbnails-header">
                        <h4>
                            <i class="fas fa-images me-2"></i>
                            <span id="current-category">Toutes les photos</span>
                            <small class="ms-2" id="photo-count">(8 photos)</small>
                        </h4>
                        <div class="category-filters">
                            <button class="category-filter active" data-category="all">Tout</button>
                            <button class="category-filter" data-category="pizza">Pizzas</button>
                            <button class="category-filter" data-category="burger">Burgers</button>
                            <button class="category-filter" data-category="poutine">Poutines</button>
                        </div>
                    </div>
                    
                    <div class="thumbnails-container">
                        <!-- Thumbnail 1 -->
                        <div class="thumbnail-item active" data-index="0" data-category="pizza">
                            <img src="https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80" 
                                 alt="Pizza Signature">
                            <div class="thumbnail-overlay">
                                <div class="thumbnail-info">
                                    <h6>Pizza Signature</h6>
                                    <span class="badge bg-primary">Populaire</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Thumbnail 2 -->
                        <div class="thumbnail-item" data-index="1" data-category="burger">
                            <img src="{{ asset('images/Beef-Burgers-067.jpg') }}" alt="Burger Gourmet">
                            <div class="thumbnail-overlay">
                                <div class="thumbnail-info">
                                    <h6>Burger Classique</h6>
                                    <span class="badge bg-success">Nouveau</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Thumbnail 3 -->
                        <div class="thumbnail-item" data-index="2" data-category="poutine">
                            <img src="{{ asset('images/poutine classique.jpg') }}" alt="Poutine Maison">
                            <div class="thumbnail-overlay">
                                <div class="thumbnail-info">
                                    <h6>Poutine Classique</h6>
                                    <span class="badge bg-warning">Épicé</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Thumbnail 4 -->
                        <div class="thumbnail-item" data-index="3" data-category="pizza">
                            <img src="https://images.unsplash.com/photo-1571066811602-716837d681de?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80" 
                                 alt="Pizza Végétarienne">
                            <div class="thumbnail-overlay">
                                <div class="thumbnail-info">
                                    <h6>Pizza Végétarienne</h6>
                                    <span class="badge bg-info">Végétarien</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Thumbnail 5 -->
                        <div class="thumbnail-item" data-index="4" data-category="burger">
                            <img src="https://images.unsplash.com/photo-1567620832903-9fc6debc209f?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80" 
                                 alt="Ailes de poulet">
                            <div class="thumbnail-overlay">
                                <div class="thumbnail-info">
                                    <h6>Ailes de Poulet</h6>
                                    <span class="badge bg-danger">Spécialité</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Thumbnail 6 -->
                        <div class="thumbnail-item" data-index="5" data-category="restaurant">
                            <img src="https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80" 
                                 alt="Intérieur restaurant">
                            <div class="thumbnail-overlay">
                                <div class="thumbnail-info">
                                    <h6>Notre Restaurant</h6>
                                    <span class="badge bg-secondary">Ambiance</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Thumbnail 7 -->
                        <div class="thumbnail-item" data-index="6" data-category="restaurant">
                            <img src="https://images.unsplash.com/photo-1555396273-367ea4eb4db5?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80" 
                                 alt="Cuisine ouverte">
                            <div class="thumbnail-overlay">
                                <div class="thumbnail-info">
                                    <h6>Cuisine Ouverte</h6>
                                    <span class="badge bg-dark">Transparent</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Thumbnail 8 -->
                        <div class="thumbnail-item" data-index="7" data-category="poutine">
                            <img src="https://images.unsplash.com/photo-1571091718767-18b5b1457add?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80" 
                                 alt="Poutine Garnie">
                            <div class="thumbnail-overlay">
                                <div class="thumbnail-info">
                                    <h6>Poutine Garnie</h6>
                                    <span class="badge bg-success">Récompensé</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Controls supplémentaires -->
                    <div class="thumbnails-footer">
                        <div class="progress" style="height: 5px;">
                            <div class="progress-bar" id="progress-bar" style="width: 12.5%"></div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-2">
                            <small id="current-position">Photo 1 sur 8</small>
                            <div class="thumb-controls">
                                <button class="thumb-control-btn" id="auto-play-toggle">
                                    <i class="fas fa-pause" id="auto-play-icon"></i>
                                </button>
                                <button class="thumb-control-btn" id="fullscreen-toggle">
                                    <i class="fas fa-expand"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Mini Sections Sliders -->
        <div class="row mt-5">
            <div class="col-12">
                <h4 class="text-center mb-4">
                    <i class="fas fa-th-large me-2"></i>Découvrez par catégories
                </h4>
            </div>
            
            <!-- Mini Slider Pizzas -->
            <div class="col-md-4 mb-4">
                <div class="mini-slider-card">
                    <div class="mini-slider-header">
                        <h5><i class="fas fa-pizza-slice me-2"></i> Nos Pizzas</h5>
                        <div class="mini-slider-nav">
                            <button class="mini-nav-btn" data-slider="pizza" data-direction="prev">
                                <i class="fas fa-chevron-left"></i>
                            </button>
                            <button class="mini-nav-btn" data-slider="pizza" data-direction="next">
                                <i class="fas fa-chevron-right"></i>
                            </button>
                        </div>
                    </div>
                    <div class="mini-slider" id="pizza-slider">
                        <div class="mini-slider-track">
                            <div class="mini-slide">
                                <img src="https://images.unsplash.com/photo-1595708684082-a173bb3a06c5?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80" alt="Pepperoni">
                                <div class="mini-slide-caption">Pepperoni</div>
                            </div>
                            <div class="mini-slide">
                                <img src="https://images.unsplash.com/photo-1604382354936-07c5d9983bd3?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80" alt="Hawaïenne">
                                <div class="mini-slide-caption">Hawaïenne</div>
                            </div>
                            <div class="mini-slide">
                                <img src="https://images.unsplash.com/photo-1571066811602-716837d681de?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80" alt="Végétarienne">
                                <div class="mini-slide-caption">Végétarienne</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Mini Slider Burgers -->
            <div class="col-md-4 mb-4">
                <div class="mini-slider-card">
                    <div class="mini-slider-header">
                        <h5><i class="fas fa-hamburger me-2"></i> Burgers & More</h5>
                        <div class="mini-slider-nav">
                            <button class="mini-nav-btn" data-slider="burger" data-direction="prev">
                                <i class="fas fa-chevron-left"></i>
                            </button>
                            <button class="mini-nav-btn" data-slider="burger" data-direction="next">
                                <i class="fas fa-chevron-right"></i>
                            </button>
                        </div>
                    </div>
                    <div class="mini-slider" id="burger-slider">
                        <div class="mini-slider-track">
                            <div class="mini-slide">
                                <img src="{{ asset('images/Beef-Burgers-067.jpg') }}" alt="Burger Classique">
                                <div class="mini-slide-caption">Burger Classique</div>
                            </div>
                            <div class="mini-slide">
                                <img src="https://images.unsplash.com/photo-1567620832903-9fc6debc209f?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80" alt="Ailes de poulet">
                                <div class="mini-slide-caption">Ailes de Poulet</div>
                            </div>
                            <div class="mini-slide">
                                <img src="https://images.unsplash.com/photo-1563379926898-05f4575a45d8?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80" alt="Bâtonnets fromage">
                                <div class="mini-slide-caption">Bâtonnets Fromage</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Mini Slider Poutines -->
            <div class="col-md-4 mb-4">
                <div class="mini-slider-card">
                    <div class="mini-slider-header">
                        <h5><i class="fas fa-cheese me-2"></i> Poutines</h5>
                        <div class="mini-slider-nav">
                            <button class="mini-nav-btn" data-slider="poutine" data-direction="prev">
                                <i class="fas fa-chevron-left"></i>
                            </button>
                            <button class="mini-nav-btn" data-slider="poutine" data-direction="next">
                                <i class="fas fa-chevron-right"></i>
                            </button>
                        </div>
                    </div>
                    <div class="mini-slider" id="poutine-slider">
                        <div class="mini-slider-track">
                            <div class="mini-slide">
                                <img src="{{ asset('images/poutine classique.jpg') }}" alt="Poutine Classique">
                                <div class="mini-slide-caption">Classique</div>
                            </div>
                            <div class="mini-slide">
                                <img src="https://images.unsplash.com/photo-1571091718767-18b5b1457add?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80" alt="Poutine Garnie">
                                <div class="mini-slide-caption">Garnie</div>
                            </div>
                            <div class="mini-slide">
                                <img src="https://images.unsplash.com/photo-1571066811602-716837d681de?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80" alt="Poutine Spéciale">
                                <div class="mini-slide-caption">Spéciale</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

    <!-- Instagram Style Gallery -->
<section class="instagram-gallery" id="photos">
    <div class="container">
        <h2 class="section-title text-center">Notre Univers</h2>
        <p class="text-center mb-5" style="max-width: 700px; margin: 0 auto; color: var(--gray-color);">
            Découvrez l'ambiance de Chez Jim Pizza à travers nos moments préférés
        </p>
        
        <div class="gallery-grid">
            <!-- Grand élément vidéo YouTube (1ère position) -->
            <div class="gallery-item large youtube-item">
                <div class="video-container">
                    <iframe 
                        src="https://www.youtube.com/embed/r9lfyTtewQI?autoplay=1&mute=1&loop=1&playlist=r9lfyTtewQI&controls=0&modestbranding=1&rel=0&showinfo=0"
                        title="Notre pizza signature"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        allowfullscreen>
                    </iframe>
                    <div class="video-overlay">
                        <div class="play-icon">
                            <i class="fas fa-play"></i>
                        </div>
                        <div class="video-info">
                            <h4>Notre Pizza Signature</h4>
                            <p>Vidéo • 2:30</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Image moyenne (2ème position) -->
            <div class="gallery-item medium">
                <div class="image-container">
                    <img src="https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1200&q=80" alt="Intérieur restaurant">
                    <div class="image-overlay">
                        <div class="overlay-content">
                            <h4>Ambiance Chaleureuse</h4>
                            <p>Notre restaurant vous accueille</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Image petite (3ème position) -->
            <div class="gallery-item large">
                <div class="image-container">
                    <img src="https://images.unsplash.com/photo-1555396273-367ea4eb4db5?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80" alt="Pizza préparée">
                    <div class="image-overlay">
                        <div class="overlay-content">
                            <h4>Artisanat</h4>
                            <p>Préparation à la main</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Image moyenne (4ème position) -->
            <div class="gallery-item medium">
                <div class="image-container">
                    <img src="https://images.unsplash.com/photo-1578474846511-04ba529f0b88?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1200&q=80" alt="Notre équipe">
                    <div class="image-overlay">
                        <div class="overlay-content">
                            <h4>Notre Équipe</h4>
                            <p>Des passionnés à votre service</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Image petite (5ème position) -->
            <div class="gallery-item large">
                <div class="image-container">
                    <img src="https://images.unsplash.com/photo-1571066811602-716837d681de?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80" alt="Ingrédients frais">
                    <div class="image-overlay">
                        <div class="overlay-content">
                            <h4>Ingrédients Frais</h4>
                            <p>Qualité supérieure</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Image moyenne (6ème position) -->
            <div class="gallery-item medium">
                <div class="image-container">
                    <img src="https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1200&q=80" alt="Pizza gastronomique">
                    <div class="image-overlay">
                        <div class="overlay-content">
                            <h4>Gastronomie</h4>
                            <p>Des pizzas d'exception</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Image large (7ème position) -->
            <div class="gallery-item large">
                <div class="image-container">
                    <img src="https://images.unsplash.com/photo-1559925393-8be0ec4767c8?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1600&q=80" alt="Événement au restaurant">
                    <div class="image-overlay">
                        <div class="overlay-content">
                            <h4>Événements</h4>
                            <p>Moment de partage</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Image petite (8ème position) -->
            <div class="gallery-item small">
                <div class="image-container">
                    <img src="https://images.unsplash.com/photo-1571091718767-18b5b1457add?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80" alt="Oignons frits">
                    <div class="image-overlay">
                        <div class="overlay-content">
                            <h4>Spécialités</h4>
                            <p>Nos entrées maison</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Image petite (9ème position) -->
            <div class="gallery-item small">
                <div class="image-container">
                    <img src="https://images.unsplash.com/photo-1563379926898-05f4575a45d8?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80" alt="Bâtonnets de fromage">
                    <div class="image-overlay">
                        <div class="overlay-content">
                            <h4>Fromages Fondants</h4>
                            <p>Notre spécialité fromagère</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Image moyenne (10ème position) -->
            <div class="gallery-item medium">
                <div class="image-container">
                    <img src="https://images.unsplash.com/photo-1567620832903-9fc6debc209f?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1200&q=80" alt="Ailes de poulet">
                    <div class="image-overlay">
                        <div class="overlay-content">
                            <h4>Ailes Croustillantes</h4>
                            <p>Nos incontournables</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Image petite (11ème position) -->
            <div class="gallery-item small">
                <div class="image-container">
                    <img src="https://images.unsplash.com/photo-1595708684082-a173bb3a06c5?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80" alt="Pizza Pepperoni">
                    <div class="image-overlay">
                        <div class="overlay-content">
                            <h4>Pepperoni</h4>
                            <p>Notre classique</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Image petite (12ème position) -->
            <div class="gallery-item small">
                <div class="image-container">
                    <img src="https://images.unsplash.com/photo-1604382354936-07c5d9983bd3?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80" alt="Pizza Hawaïenne">
                    <div class="image-overlay">
                        <div class="overlay-content">
                            <h4>Hawaïenne</h4>
                            <p>Douceur tropicale</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Instagram CTA -->
        <!-- <div class="instagram-cta text-center mt-5">
            <p class="mb-3">Partagez vos moments #ChezJimPizza</p>
            <a href="https://www.instagram.com/explore/tags/chezjimpizza/" target="_blank" class="btn btn-instagram">
                <i class="fab fa-instagram me-2"></i> #ChezJimPizza
            </a>
        </div> -->
    </div>
</section>

    <!-- Footer -->
    <footer class="main-footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-5 mb-lg-0">
                    <div class="footer-logo">
                        <h3>Chez Jim Pizza</h3>
                        <p class="mt-3" style="color: rgba(255,255,255,0.8);">
                            La meilleure pizza de la Côte de Beaupré! Depuis des années, nous servons des pizzas faites avec des ingrédients frais et beaucoup de passion.
                        </p>
                        <div class="social-icons">
                            <a href="https://www.facebook.com/groups/763632935467487" target="_blank" class="social-icon">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <!-- <a href="#" class="social-icon">
                                <i class="fab fa-instagram"></i>
                            </a>
                            <a href="#" class="social-icon">
                                <i class="fab fa-tripadvisor"></i>
                            </a> -->
                            <a href="https://www.goexploria.com/fr/company/146197/chez-jim-pizza" target="_blank" class="social-icon">
                                <i class="fab fa-youtube"></i>
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4 mb-5 mb-lg-0">
                    <div class="footer-links">
                        <h4>Liens Rapides</h4>
                        <ul>
                            <li><a href="#home">Accueil</a></li>
                            <li><a href="#menu">Notre Carte</a></li>
                            <li><a href="#promos">Promotions</a></li>
                            <li><a href="#party">Événements & Groupes</a></li>
                            <li><a href="#salle">Salle de Réunion</a></li>
                            <li><a href="#certificats">Certificats Cadeaux</a></li>
                        </ul>
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <div class="footer-contact">
                        <h4>Contact & Horaires</h4>
                        <p><i class="fas fa-phone me-2"></i> <strong>418-827-9000</strong></p>
                        <p><i class="fas fa-wine-glass-alt me-2"></i> Apportez votre vin!</p>
                        <p><i class="fas fa-users me-2"></i> Salle de réception: 24 personnes</p>
                        <p><i class="fas fa-utensils me-2"></i> Restaurant: 35 personnes</p>
                        
                        <h5 class="mt-4 mb-3">Livraison</h5>
                        <p>Dimanche: 16h30 à 20h</p>
                        <p>Mercredi: 16h30 à 20h</p>
                        <p>Jeudi à Samedi: 16h30 à 21h</p>
                        <p>Lundi & Mardi: Fermé</p>
                        <p><small>Frais de livraison: 4$</small></p>
                    </div>
                </div>
            </div>
            
            <div class="copyright mt-5">
                <p>&copy; 2026 Chez Jim Pizza. Tous droits réservés.</p>
                <p class="mt-2">Site créé à partir des données publiques de GoExploria.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>
    
    <!-- YouTube IFrame API -->
    <script src="https://www.youtube.com/iframe_api"></script>
    
    <script>
    // Variables globales
    let currentSlide = 0;
    let slideInterval;
    let gallerySwiper;
    let youtubePlayer1;
    let youtubePlayer2;
    let isUserInteracted = false;

    // Charger l'API YouTube
    function onYouTubeIframeAPIReady() {
        // Initialiser le player pour la slide 1
        youtubePlayer1 = new YT.Player('youtubeVideo1', {
            events: {
                'onReady': onPlayer1Ready,
                'onStateChange': onPlayer1StateChange
            }
        });
        
        // Initialiser le player pour la section vidéo
        youtubePlayer2 = new YT.Player('sectionVideo', {
            events: {
                'onReady': onPlayer2Ready,
                'onStateChange': onPlayer2StateChange
            }
        });
    }

    // Callback pour le player 1 (slider)
    function onPlayer1Ready(event) {
        console.log('YouTube Player 1 ready');
        
        // Solution spéciale pour l'autoplay: attendre un peu puis démarrer
        setTimeout(() => {
            event.target.mute(); // Mute obligatoire
            event.target.playVideo(); // Démarrer la lecture
            
            // Cacher l'overlay après démarrage
            setTimeout(() => {
                const overlay = document.getElementById('videoOverlay1');
                if (overlay) overlay.style.opacity = '0';
            }, 1000);
        }, 1000);
    }

    function onPlayer1StateChange(event) {
        // Gérer la boucle
        if (event.data === YT.PlayerState.ENDED) {
            event.target.playVideo();
        }
        
        // Mettre en pause quand on quitte la slide
        if (currentSlide !== 0 && event.data === YT.PlayerState.PLAYING) {
            event.target.pauseVideo();
        }
        
        // Reprendre quand on revient à la slide
        if (currentSlide === 0 && event.data === YT.PlayerState.PAUSED) {
            event.target.playVideo();
        }
    }

    // Callback pour le player 2 (section vidéo)
    function onPlayer2Ready(event) {
        console.log('YouTube Player 2 ready');
        // Ne rien faire, attendre le clic utilisateur
    }

    function onPlayer2StateChange(event) {
        // Gérer les changements d'état si besoin
    }

    // Dans votre script existant, ajoutez cette fonction
function initHoverMegaMenu() {
    const megaHoverItems = document.querySelectorAll('.mega-hover');
    
    // Sur desktop, on utilise le hover
    if (window.innerWidth > 992) {
        megaHoverItems.forEach(item => {
            const link = item.querySelector('.nav-link');
            const menu = item.querySelector('.mega-menu');
            
            // Désactiver le toggle Bootstrap
            link.setAttribute('data-bs-toggle', '');
            
            // Gérer l'ouverture au hover
            item.addEventListener('mouseenter', () => {
                menu.classList.add('show');
                link.setAttribute('aria-expanded', 'true');
            });
            
            // Gérer la fermeture au mouseleave
            item.addEventListener('mouseleave', () => {
                setTimeout(() => {
                    if (!item.matches(':hover') && !menu.matches(':hover')) {
                        menu.classList.remove('show');
                        link.setAttribute('aria-expanded', 'false');
                    }
                }, 300);
            });
            
            // Garder ouvert si on survole le menu
            menu.addEventListener('mouseenter', () => {
                menu.classList.add('show');
                link.setAttribute('aria-expanded', 'true');
            });
            
            menu.addEventListener('mouseleave', () => {
                setTimeout(() => {
                    if (!item.matches(':hover') && !menu.matches(':hover')) {
                        menu.classList.remove('show');
                        link.setAttribute('aria-expanded', 'false');
                    }
                }, 300);
            });
        });
    } else {
        // Sur mobile, on réactive le comportement Bootstrap
        megaHoverItems.forEach(item => {
            const link = item.querySelector('.nav-link');
            link.setAttribute('data-bs-toggle', 'dropdown');
        });
    }
}


// Ajoutez aussi cette fonction pour gérer la fermeture au scroll
function handleMegaMenuOnScroll() {
    if (window.innerWidth > 992) {
        const openMegaMenus = document.querySelectorAll('.mega-hover .mega-menu.show');
        openMegaMenus.forEach(menu => {
            menu.classList.remove('show');
            const link = menu.parentElement.querySelector('.nav-link');
            if (link) link.setAttribute('aria-expanded', 'false');
        });
    }
}

// Fermer les mega menus au scroll
window.addEventListener('scroll', function() {
    if (window.innerWidth > 992) {
        handleMegaMenuOnScroll();
    }
});

// Fermer les mega menus au clic ailleurs sur la page
document.addEventListener('click', function(e) {
    if (window.innerWidth > 992) {
        const megaHoverItems = document.querySelectorAll('.mega-hover');
        let isClickInsideMegaMenu = false;
        
        megaHoverItems.forEach(item => {
            if (item.contains(e.target)) {
                isClickInsideMegaMenu = true;
            }
        });
        
        if (!isClickInsideMegaMenu) {
            handleMegaMenuOnScroll();
        }
    }
});

    // Initialisation complète
    document.addEventListener('DOMContentLoaded', function() {
        // Initialiser le slider vidéo
        initVideoSlider();
        
        // Initialiser la galerie Swiper
        initGallerySlider();

        
    initInstagramGallery();
        
        // Initialiser les événements
        setupEventListeners();
        
        // Initialiser la navigation
        initNavigation();
        
        // Initialiser le header
        initHeader();
        
        // Précharger les images
        preloadImages();
        
        // Forcer l'interaction utilisateur pour débloquer l'autoplay
        setupAutoplayWorkaround();
        
        // Initialiser la carte interactive du menu
        initMenuMap();

        initHoverMegaMenu();

        
    // Initialiser les tooltips produits
    initProduitsTooltips();
    
    // Re-initialiser au redimensionnement
    window.addEventListener('resize', function() {
        initHoverMegaMenu();
    });
    });
    
    // Initialiser la carte interactive du menu
    function initMenuMap() {
        const mapSections = document.querySelectorAll('.map-section');
        
        mapSections.forEach(section => {
            section.addEventListener('click', function() {
                const targetId = this.getAttribute('data-target');
                const targetElement = document.querySelector(targetId);
                
                if (targetElement) {
                    // Fermer le méga menu si ouvert
                    const openDropdowns = document.querySelectorAll('.dropdown-menu.show');
                    openDropdowns.forEach(dropdown => {
                        dropdown.classList.remove('show');
                    });
                    
                    // Faire défiler jusqu'à la section cible
                    window.scrollTo({
                        top: targetElement.offsetTop - 100,
                        behavior: 'smooth'
                    });
                    
                    // Activer la catégorie correspondante dans le menu
                    const category = targetId.replace('#', '');
                    const categoryBtn = document.querySelector(`.menu-category-btn[data-category="${category}"]`);
                    if (categoryBtn) {
                        categoryBtn.click();
                    }
                }
            });
        });
    }
    
    // Solution de contournement pour l'autoplay
    function setupAutoplayWorkaround() {
        // Ajouter un événement de clic sur toute la page
        document.addEventListener('click', function() {
            if (!isUserInteracted) {
                isUserInteracted = true;
                console.log('Utilisateur a interagi, autoplay débloqué');
                
                // Redémarrer la vidéo si elle est en pause
                if (youtubePlayer1 && youtubePlayer1.getPlayerState() === YT.PlayerState.PAUSED) {
                    youtubePlayer1.playVideo();
                }
            }
        });
        
        // Ajouter un événement de scroll
        document.addEventListener('scroll', function() {
            if (!isUserInteracted) {
                isUserInteracted = true;
                console.log('Utilisateur a scrollé, autoplay débloqué');
            }
        });
    }
    
    // Initialiser le slider vidéo
    function initVideoSlider() {
        const slides = document.querySelectorAll('.video-slide');
        const dots = document.querySelectorAll('.slider-dot');
        
        function showSlide(index) {
            // Masquer toutes les slides
            slides.forEach(slide => slide.classList.remove('active'));
            dots.forEach(dot => dot.classList.remove('active'));
            
            // Afficher la slide active
            currentSlide = index;
            slides[currentSlide].classList.add('active');
            dots[currentSlide].classList.add('active');
            
            // Gérer la vidéo YouTube
            if (currentSlide === 0 && youtubePlayer1) {
                // Si on revient à la slide vidéo
                setTimeout(() => {
                    if (youtubePlayer1.getPlayerState() !== YT.PlayerState.PLAYING) {
                        youtubePlayer1.playVideo();
                    }
                }, 500);
            } else if (youtubePlayer1 && youtubePlayer1.getPlayerState() === YT.PlayerState.PLAYING) {
                // Mettre en pause si on quitte la slide vidéo
                youtubePlayer1.pauseVideo();
            }
            
            // Animation du texte
            const sliderText = document.querySelector('.slider-text');
            sliderText.style.opacity = '0';
            sliderText.style.transform = 'translateY(30px)';
            
            setTimeout(() => {
                sliderText.style.opacity = '1';
                sliderText.style.transform = 'translateY(0)';
            }, 300);
        }
        
        // Configurer les dots
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
            slideInterval = setInterval(nextSlide, 8000);
        }
        
        function resetInterval() {
            clearInterval(slideInterval);
            startInterval();
        }
        
        // Pause au survol
        const sliderContainer = document.querySelector('.video-slider-container');
        sliderContainer.addEventListener('mouseenter', () => {
            clearInterval(slideInterval);
        });
        
        sliderContainer.addEventListener('mouseleave', () => {
            startInterval();
        });
        
        // Démarrer
        startInterval();
    }
    
    // Initialiser la galerie Swiper
    function initGallerySlider() {
        gallerySwiper = new Swiper('.gallerySwiper', {
            slidesPerView: 1,
            spaceBetween: 30,
            loop: true,
            autoplay: {
                delay: 8000,
                disableOnInteraction: false,
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            breakpoints: {
                576: {
                    slidesPerView: 2,
                },
                768: {
                    slidesPerView: 3,
                }
            }
        });
    }

   // Instagram Gallery Functions
function initInstagramGallery() {
    // Gérer les vidéos YouTube (juste la première)
    const youtubeItems = document.querySelectorAll('.youtube-item');
    
    youtubeItems.forEach(item => {
        const iframe = item.querySelector('iframe');
        const overlay = item.querySelector('.video-overlay');
        
        // Gérer le clic sur les vidéos
        item.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Ouvrir la vidéo en plein écran ou dans une modale
            const videoSrc = iframe.src;
            const cleanSrc = videoSrc.split('?')[0];
            
            // Pour un effet simple, on peut juste rediriger vers YouTube
            window.open(cleanSrc.replace('/embed/', '/watch?v='), '_blank');
        });
        
        // Animation au survol
        item.addEventListener('mouseenter', function() {
            if (overlay) {
                overlay.style.opacity = '1';
            }
        });
        
        item.addEventListener('mouseleave', function() {
            if (overlay) {
                overlay.style.opacity = '';
            }
        });
    });
    
    // Gérer les images pour l'effet de zoom
    const imageItems = document.querySelectorAll('.gallery-item:not(.youtube-item)');
    
    imageItems.forEach(item => {
        item.addEventListener('mouseenter', function() {
            this.style.zIndex = '10';
        });
        
        item.addEventListener('mouseleave', function() {
            this.style.zIndex = '';
        });
    });
    
    // Lightbox pour les images
    const imageContainers = document.querySelectorAll('.image-container');
    
    imageContainers.forEach(container => {
        container.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const imgSrc = this.querySelector('img').src;
            const imgAlt = this.querySelector('img').alt;
            
            // Créer une lightbox simple
            createLightbox(imgSrc, imgAlt);
        });
    });
}

// Créer une lightbox simple
function createLightbox(imgSrc, imgAlt) {
    // Vérifier si une lightbox existe déjà
    const existingLightbox = document.getElementById('galleryLightbox');
    if (existingLightbox) {
        existingLightbox.remove();
    }
    
    // Créer les éléments de la lightbox
    const lightbox = document.createElement('div');
    lightbox.id = 'galleryLightbox';
    lightbox.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.9);
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s ease;
    `;
    
    const lightboxImg = document.createElement('img');
    lightboxImg.src = imgSrc;
    lightboxImg.alt = imgAlt;
    lightboxImg.style.cssText = `
        max-width: 90%;
        max-height: 90%;
        object-fit: contain;
        border-radius: 5px;
        transform: scale(0.9);
        transition: transform 0.3s ease;
    `;
    
    const closeBtn = document.createElement('button');
    closeBtn.innerHTML = '<i class="fas fa-times"></i>';
    closeBtn.style.cssText = `
        position: absolute;
        top: 20px;
        right: 20px;
        background: rgba(255,255,255,0.1);
        border: none;
        color: white;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        font-size: 1.2rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    `;
    
    closeBtn.addEventListener('mouseenter', function() {
        this.style.background = 'rgba(255,255,255,0.2)';
    });
    
    closeBtn.addEventListener('mouseleave', function() {
        this.style.background = 'rgba(255,255,255,0.1)';
    });
    
    closeBtn.addEventListener('click', function() {
        lightbox.style.opacity = '0';
        setTimeout(() => {
            if (lightbox.parentNode) {
                lightbox.parentNode.removeChild(lightbox);
            }
        }, 300);
    });
    
    // Fermer la lightbox en cliquant sur le fond
    lightbox.addEventListener('click', function(e) {
        if (e.target === this) {
            lightbox.style.opacity = '0';
            setTimeout(() => {
                if (lightbox.parentNode) {
                    lightbox.parentNode.removeChild(lightbox);
                }
            }, 300);
        }
    });
    
    // Fermer avec la touche Échap
    document.addEventListener('keydown', function closeOnEscape(e) {
        if (e.key === 'Escape') {
            if (lightbox.parentNode) {
                lightbox.style.opacity = '0';
                setTimeout(() => {
                    if (lightbox.parentNode) {
                        lightbox.parentNode.removeChild(lightbox);
                    }
                }, 300);
            }
            document.removeEventListener('keydown', closeOnEscape);
        }
    });
    
    // Ajouter les éléments au DOM
    lightbox.appendChild(lightboxImg);
    lightbox.appendChild(closeBtn);
    document.body.appendChild(lightbox);
    
    // Animer l'ouverture
    setTimeout(() => {
        lightbox.style.opacity = '1';
        lightboxImg.style.transform = 'scale(1)';
    }, 10);
}

    
    // Configurer les événements
    function setupEventListeners() {
        // Video Player Control pour la section vidéo
        const videoOverlay = document.getElementById('videoOverlay');
        const playButton = document.getElementById('playButton');
        
        if (videoOverlay && playButton) {
            function playVideo() {
                videoOverlay.classList.add('hidden');
                if (youtubePlayer2 && typeof youtubePlayer2.playVideo === 'function') {
                    youtubePlayer2.playVideo();
                }
            }
            
            playButton.addEventListener('click', playVideo);
            videoOverlay.addEventListener('click', playVideo);
        }
        
        // Menu Category Switching
        const menuCategoryBtns = document.querySelectorAll('.menu-category-btn');
        const menuCategoryContents = document.querySelectorAll('.menu-category-content');
        
        menuCategoryBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                // Retirer la classe active de tous les boutons
                menuCategoryBtns.forEach(b => b.classList.remove('active'));
                
                // Ajouter la classe active au bouton cliqué
                btn.classList.add('active');
                
                // Obtenir la catégorie cible
                const targetCategory = btn.getAttribute('data-category');
                
                // Cacher tous les contenus de menu
                menuCategoryContents.forEach(content => {
                    content.classList.remove('active');
                });
                
                // Afficher le contenu de la catégorie cible
                const targetElement = document.getElementById(targetCategory);
                if (targetElement) {
                    targetElement.classList.add('active');
                }
            });
        });
        
        // Gestion du scroll pour le header
        window.addEventListener('scroll', handleScroll);
        
        // Redimensionnement de la fenêtre
        let resizeTimer;
        window.addEventListener('resize', function() {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(function() {
                handleResize();
            }, 250);
        });
    }
    
    // Initialiser la navigation
    function initNavigation() {
        // Navigation fluide pour les liens d'ancrage
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                const targetId = this.getAttribute('href');
                if (targetId === '#' || targetId === '#!') return;
                
                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                    e.preventDefault();
                    
                    // Fermer la navbar sur mobile après le clic
                    if (window.innerWidth < 992) {
                        const navbarCollapse = document.querySelector('.navbar-collapse');
                        if (navbarCollapse && navbarCollapse.classList.contains('show')) {
                            const toggleBtn = document.querySelector('.navbar-toggler');
                            if (toggleBtn) {
                                const bsCollapse = bootstrap.Collapse.getInstance(navbarCollapse);
                                if (bsCollapse) {
                                    bsCollapse.hide();
                                }
                            }
                        }
                    }
                    
                    // Défilement fluide
                    window.scrollTo({
                        top: targetElement.offsetTop - 100,
                        behavior: 'smooth'
                    });
                }
            });
        });
    }
    
    // Gestion du scroll
    function handleScroll() {
        const header = document.querySelector('.main-header');
        if (window.scrollY > 100) {
            header.classList.add('header-scrolled');
        } else {
            header.classList.remove('header-scrolled');
        }
        
        // Mettre à jour les liens actifs
        updateActiveMenuLinks();
    }
    

    // Tooltips pour les badges produits
function initProduitsTooltips() {
    const badges = document.querySelectorAll('.produits-list .badge');
    
    badges.forEach(badge => {
        // Ajouter des tooltips selon le type de produit
        const badgeText = badge.textContent.toLowerCase();
        
        if (badgeText.includes('pizza congelée')) {
            badge.setAttribute('data-tooltip', 'Disponible à emporter');
        } else if (badgeText.includes('pizza fraîche')) {
            badge.setAttribute('data-tooltip', 'Préparée sur place');
        } else if (badgeText.includes('entrées')) {
            badge.setAttribute('data-tooltip', 'Oignons frits, ailes, etc.');
        } else if (badgeText.includes('burgers')) {
            badge.setAttribute('data-tooltip', 'Burgers gourmets');
        } else if (badgeText.includes('poutines')) {
            badge.setAttribute('data-tooltip', 'Notre spécialité');
        } else if (badgeText.includes('salades')) {
            badge.setAttribute('data-tooltip', 'Salades fraîches');
        } else if (badgeText.includes('menu midi')) {
            badge.setAttribute('data-tooltip', 'Formules déjeuner');
        } else if (badgeText.includes('sandwiches')) {
            badge.setAttribute('data-tooltip', 'Sandwiches chauds');
        } else if (badgeText.includes('boissons')) {
            badge.setAttribute('data-tooltip', 'Boissons fraîches');
        } else if (badgeText.includes('collations')) {
            badge.setAttribute('data-tooltip', 'Snacks et desserts');
        }
    });
}

    // Mettre à jour les liens du menu actifs
    function updateActiveMenuLinks() {
        const sections = document.querySelectorAll('section[id]');
        const navLinks = document.querySelectorAll('.nav-link');
        
        let current = '';
        sections.forEach(section => {
            const sectionTop = section.offsetTop;
            const sectionHeight = section.clientHeight;
            if (window.scrollY >= (sectionTop - 150)) {
                current = section.getAttribute('id');
            }
        });
        
        navLinks.forEach(link => {
            link.classList.remove('active');
            if (link.getAttribute('href') === `#${current}`) {
                link.classList.add('active');
            }
        });
    }
    
    // Gérer le redimensionnement
    function handleResize() {
        // Mettre à jour les sliders
        if (gallerySwiper) {
            gallerySwiper.update();
        }
        
        // Gérer le menu mobile
        if (window.innerWidth >= 992) {
            const navbarCollapse = document.querySelector('.navbar-collapse');
            if (navbarCollapse && navbarCollapse.classList.contains('show')) {
                const bsCollapse = bootstrap.Collapse.getInstance(navbarCollapse);
                if (bsCollapse) {
                    bsCollapse.hide();
                }
            }
        }
    }
    
    // Initialiser le header
    function initHeader() {
        // Initialiser l'état du header
        handleScroll();
    }
    
    // Précharger les images
    function preloadImages() {
        const images = [
            'https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?ixlib=rb-4.0.3&auto=format&fit=crop&w=1600&q=80',
            'https://images.unsplash.com/photo-1551183053-bf91a1d81141?ixlib=rb-4.0.3&auto=format&fit=crop&w=1600&q=80',
            'https://images.unsplash.com/photo-1513104890138-7c749659a591?ixlib=rb-4.0.3&auto=format&fit=crop&w=1600&q=80',
            'https://images.unsplash.com/photo-1595708684082-a173bb3a06c5?ixlib=rb-4.0.3&auto=format&fit=crop&w=1600&q=80'
        ];
        
        images.forEach(src => {
            const img = new Image();
            img.src = src;
        });
    }
    
    // Fallback si YouTube ne se charge pas
    function setupYouTubeFallback() {
        const videoSlide = document.getElementById('videoSlide1');
        if (videoSlide) {
            const iframe = videoSlide.querySelector('iframe');
            if (iframe && iframe.offsetHeight === 0) {
                console.warn('YouTube vidéo non chargée, activation du fallback');
                
                // Remplacer par une image
                iframe.style.display = 'none';
                const fallbackImg = document.createElement('img');
                fallbackImg.src = 'https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?ixlib=rb-4.0.3&auto=format&fit=crop&w=1600&q=80';
                fallbackImg.style.cssText = 'position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; z-index: 1;';
                videoSlide.querySelector('.youtube-video-container').appendChild(fallbackImg);
            }
        }
    }
    
    // Démarrer quand la page est chargée
    window.addEventListener('load', function() {
        console.log('Page chargée');
        
        // Forcer le redimensionnement initial
        handleResize();
        
        // Vérifier à nouveau la vidéo après chargement
        setTimeout(setupYouTubeFallback, 3000);
    });
    </script>
    <script src="{{asset('js/gallery.js')}}"></script>
</body>
</html>