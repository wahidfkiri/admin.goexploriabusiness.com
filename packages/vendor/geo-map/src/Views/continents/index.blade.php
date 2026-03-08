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
    
    <!-- Masonry CSS pour Pinterest style -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/masonry/4.2.2/masonry.pkgd.min.css">
    
  <link rel="stylesheet" href="{{ asset('front/css/style.css') }}">

    <!-- Leaflet CSS seulement -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    
    <link rel="stylesheet" href="{{ asset('vendor/geo-map/css/map.css') }}">
    <!-- Main Navigation - RESPONSIVE -->
   
<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="">
	<link href="/vendor/geo-map/continents/css2?family=Inter:wght@400;700&family=Source+Serif+Pro:wght@400;700&display=swap" rel="stylesheet">

	<link rel="stylesheet" href="/vendor/geo-map/continents/css/bootstrap.min.css">
	<link rel="stylesheet" href="/vendor/geo-map/continents/css/owl.carousel.min.css">
	<link rel="stylesheet" href="/vendor/geo-map/continents/css/owl.theme.default.min.css">
	<link rel="stylesheet" href="/vendor/geo-map/continents/css/jquery.fancybox.min.css">
	<link rel="stylesheet" href="/vendor/geo-map/continents/fonts/icomoon/style.css">
	<link rel="stylesheet" href="/vendor/geo-map/continents/fonts/flaticon/font/flaticon.css">
	<link rel="stylesheet" href="/vendor/geo-map/continents/css/daterangepicker.css">
	<link rel="stylesheet" href="/vendor/geo-map/continents/css/aos.css">
	<link rel="stylesheet" href="/vendor/geo-map/continents/css/style.css">

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

    @include('geo-map::continents.components.index')

   
    <!-- Sections existantes du template (conserver votre contenu actuel) -->
	<div class="hero">
		<div class="container">
			<div class="row align-items-center">
				<div class="col-lg-7">
					<div class="intro-wrap">
						<h1 class="mb-5"><span class="d-block">Profitez de votre voyage au</span> <span class="typed-words"></span></h1>

						
					</div>
				</div>
				<div class="col-lg-5">
					<div class="slides">
                        @foreach(\App\Models\Country::active()->get() as $countrie)
						<img src="{{asset('storage')}}/{{$countrie->image}}" alt="Image" class="img-fluid @if($loop->first) active @endif">
                        @endforeach
					</div>
				</div>
			</div>
		</div>
	</div>

    <!-- Section Gallerie Instagram Style -->
    <section class="instagram-gallery">
        <div class="container">
            <h2 class="section-title">Notre Galleries </h2>
            <div class="swiper instagramSwiper">
                <div class="swiper-wrapper">
                    <!-- Post 1 -->
                    <div class="swiper-slide">
                        <div class="instagram-post">
                            <img src="https://images.unsplash.com/photo-1540553016722-983e48a2cd10?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80" alt="Instagram Post">
                            <div class="instagram-overlay">
                                <div class="instagram-stats">
                                    <span><i class="fas fa-heart"></i> 1.2K</span>
                                    <span><i class="fas fa-comment"></i> 45</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Post 2 -->
                    <div class="swiper-slide">
                        <div class="instagram-post">
                            <img src="https://images.unsplash.com/photo-1552733407-5d5c46c3bb3b?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80" alt="Instagram Post">
                            <div class="instagram-overlay">
                                <div class="instagram-stats">
                                    <span><i class="fas fa-heart"></i> 2.5K</span>
                                    <span><i class="fas fa-comment"></i> 89</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Post 3 -->
                    <div class="swiper-slide">
                        <div class="instagram-post">
                            <img src="https://images.unsplash.com/photo-1503220317375-aaad61436b1b?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80" alt="Instagram Post">
                            <div class="instagram-overlay">
                                <div class="instagram-stats">
                                    <span><i class="fas fa-heart"></i> 3.1K</span>
                                    <span><i class="fas fa-comment"></i> 120</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Post 4 -->
                    <div class="swiper-slide">
                        <div class="instagram-post">
                            <img src="https://images.unsplash.com/photo-1578662996442-48f60103fc96?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80" alt="Instagram Post">
                            <div class="instagram-overlay">
                                <div class="instagram-stats">
                                    <span><i class="fas fa-heart"></i> 890</span>
                                    <span><i class="fas fa-comment"></i> 34</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Post 5 -->
                    <div class="swiper-slide">
                        <div class="instagram-post">
                            <img src="https://images.unsplash.com/photo-1551632811-561732d1e306?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80" alt="Instagram Post">
                            <div class="instagram-overlay">
                                <div class="instagram-stats">
                                    <span><i class="fas fa-heart"></i> 1.8K</span>
                                    <span><i class="fas fa-comment"></i> 67</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
                <div class="swiper-pagination"></div>
            </div>
        </div>
    </section>

    <!-- Section Pinterest Style Videos and Images -->
    <section class="pinterest-section">
        <div class="container">
            <!-- <h2 class="section-title text-white">Découvertes Pinterest Style</h2> -->
            <div class="pinterest-grid" id="pinterestGrid">
                <!-- Item 1 -->
                <div class="pinterest-item">
                    <img src="https://images.unsplash.com/photo-1501785888041-af3ef285b470?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80" alt="Voyage">
                    <div class="pinterest-content">
                        <span class="pin-category">Voyage</span>
                        <h4>Les plus beaux paysages d'Europe</h4>
                    </div>
                </div>
                
                <!-- Item 2 -->
                <div class="pinterest-item">
                    <img src="https://images.unsplash.com/photo-1469474968028-56623f02e42e?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80" alt="Nature">
                    <div class="pinterest-content">
                        <span class="pin-category">Nature</span>
                        <h4>Randonnées en montagne</h4>
                    </div>
                </div>
                
                <!-- Item 3 - Video -->
                <div class="pinterest-item">
                    <iframe width="560" height="315" src="https://www.youtube.com/embed/XX_sPgvYSCI?si=1sYjNP_d6o0nXcwa" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                    <div class="pinterest-content">
                        <span class="pin-category">Vidéos</span>
                        <h4>Plages paradisiaques</h4>
                    </div>
                </div>
                
                <!-- Item 4 -->
                <div class="pinterest-item">
                    <img src="https://images.unsplash.com/photo-1506905925346-21bda4d32df4?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80" alt="Aventure">
                    <div class="pinterest-content">
                        <span class="pin-category">Aventure</span>
                        <h4>Escalade extrême</h4>
                    </div>
                </div>
                
                <!-- Item 5 -->
                <div class="pinterest-item">
                    <img src="https://images.unsplash.com/photo-1488646953014-85cb44e25828?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80" alt="Urban">
                    <div class="pinterest-content">
                        <span class="pin-category">Urbain</span>
                        <h4>Architecture moderne</h4>
                    </div>
                </div>
                
                <!-- Item 6 - Video -->
                <div class="pinterest-item">
                    <iframe width="560" height="315" src="https://www.youtube.com/embed/XX_sPgvYSCI?si=1sYjNP_d6o0nXcwa" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                    <div class="pinterest-content">
                        <span class="pin-category">Roadtrip</span>
                        <h4>Routes panoramiques</h4>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Section Événements -->
    <section class="events-section">
        <div class="container">
            <h2 class="section-title">Événements à venir</h2>
            <div class="row">
                <!-- Événement 1 -->
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="event-card">
                        <div class="event-img">
                            <img src="https://images.unsplash.com/photo-1511578314322-379afb476865?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80" alt="Festival de musique">
                            <div class="event-date">
                                <span class="day">15</span>
                                <span class="month">Juin</span>
                            </div>
                        </div>
                        <div class="event-content">
                            <h3>Festival de musique d'été</h3>
                            <div class="event-location">
                                <i class="fas fa-map-marker-alt"></i> Parc Mont-Royal, Montréal
                            </div>
                            <p>Le plus grand festival de musique en plein air de l'été avec plus de 50 artistes internationaux.</p>
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <a href="#" class="btn btn-primary btn-sm">Réserver</a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Événement 2 -->
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="event-card">
                        <div class="event-img">
                            <img src="https://images.unsplash.com/photo-1558618666-fcd25c85cd64?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80" alt="Conférence">
                            <div class="event-date">
                                <span class="day">22</span>
                                <span class="month">Juin</span>
                            </div>
                        </div>
                        <div class="event-content">
                            <h3>Conférence Tech Innovation</h3>
                            <div class="event-location">
                                <i class="fas fa-map-marker-alt"></i> Palais des congrès, Québec
                            </div>
                            <p>Découvrez les dernières innovations technologiques avec des experts mondiaux.</p>
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <a href="#" class="btn btn-primary btn-sm">S'inscrire</a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Événement 3 -->
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="event-card">
                        <div class="event-img">
                            <img src="https://images.unsplash.com/photo-1540575467063-178a50c2df87?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80" alt="Exposition">
                            <div class="event-date">
                                <span class="day">05</span>
                                <span class="month">Juil</span>
                            </div>
                        </div>
                        <div class="event-content">
                            <h3>Exposition d'art contemporain</h3>
                            <div class="event-location">
                                <i class="fas fa-map-marker-alt"></i> Musée des beaux-arts, Montréal
                            </div>
                            <p>Une exposition exclusive présentant des œuvres d'artistes contemporains renommés.</p>
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <a href="#" class="btn btn-primary btn-sm">Acheter</a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Événement 4 -->
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="event-card">
                        <div class="event-img">
                            <img src="https://images.unsplash.com/photo-1540575467063-178a50c2df87?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80" alt="Exposition">
                            <div class="event-date">
                                <span class="day">12</span>
                                <span class="month">Juil</span>
                            </div>
                        </div>
                        <div class="event-content">
                            <h3>Marathon de Montréal</h3>
                            <div class="event-location">
                                <i class="fas fa-map-marker-alt"></i> Centre-ville, Montréal
                            </div>
                            <p>Participez au plus grand événement sportif de l'année avec des coureurs du monde entier.</p>
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <a href="#" class="btn btn-primary btn-sm">Participer</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Section Produits en Vedette -->
    <section class="featured-products">
        <div class="container">
            <h2 class="section-title">Produits en vedette</h2>
            <div class="row">
                <!-- Produit 1 -->
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="product-card">
                        <div class="product-img">
                            <img src="https://images.unsplash.com/photo-1505740420928-5e560c06d30e?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80" alt="Écouteurs">
                            <div class="product-badge">Promo -30%</div>
                        </div>
                        <div class="product-content">
                            <div class="product-rating">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star-half-alt"></i>
                                <span class="ms-2">(128)</span>
                            </div>
                            <h3>Écouteurs sans fil premium</h3>
                            <p>Écouteurs Bluetooth avec réduction de bruit active et autonomie de 30h.</p>
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <div>
                                    <span class="product-price">129.99$</span>
                                    <span class="product-old-price">185.99$</span>
                                    <span class="product-discount">-30%</span>
                                </div>
                            </div>
                            <button class="add-to-cart-btn">
                                <i class="fas fa-shopping-cart me-2"></i>Ajouter au panier
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Produit 2 -->
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="product-card">
                        <div class="product-img">
                            <img src="https://images.unsplash.com/photo-1526170375885-4d8ecf77b99f?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80" alt="Appareil photo">
                            <div class="product-badge">Nouveau</div>
                        </div>
                        <div class="product-content">
                            <div class="product-rating">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="far fa-star"></i>
                                <span class="ms-2">(95)</span>
                            </div>
                            <h3>Appareil photo reflex numérique</h3>
                            <p>Appareil photo professionnel 24MP avec objectif 18-55mm pour la photographie créative.</p>
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <div>
                                    <span class="product-price">799.99$</span>
                                </div>
                            </div>
                            <button class="add-to-cart-btn">
                                <i class="fas fa-shopping-cart me-2"></i>Ajouter au panier
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Produit 3 -->
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="product-card">
                        <div class="product-img">
                            <img src="https://images.unsplash.com/photo-1546868871-7041f2a55e12?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80" alt="Montre intelligente">
                            <div class="product-badge">Meilleure vente</div>
                        </div>
                        <div class="product-content">
                            <div class="product-rating">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <span class="ms-2">(256)</span>
                            </div>
                            <h3>Montre intelligente sport</h3>
                            <p>Montre connectée avec suivi d'activité, monitoring cardiaque et autonomie 7 jours.</p>
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <div>
                                    <span class="product-price">249.99$</span>
                                </div>
                            </div>
                            <button class="add-to-cart-btn">
                                <i class="fas fa-shopping-cart me-2"></i>Ajouter au panier
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Produit 4 -->
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="product-card">
                        <div class="product-img">
                            <img src="https://images.unsplash.com/photo-1572635196237-14b3f281503f?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80" alt="Lunettes de soleil">
                            <div class="product-badge">Promo -25%</div>
                        </div>
                        <div class="product-content">
                            <div class="product-rating">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star-half-alt"></i>
                                <span class="ms-2">(178)</span>
                            </div>
                            <h3>Lunettes de soleil polarisées</h3>
                            <p>Lunettes de soleil haute qualité avec verres polarisés et protection UV400.</p>
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <div>
                                    <span class="product-price">89.99$</span>
                                    <span class="product-old-price">119.99$</span>
                                    <span class="product-discount">-25%</span>
                                </div>
                            </div>
                            <button class="add-to-cart-btn">
                                <i class="fas fa-shopping-cart me-2"></i>Ajouter au panier
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>



	<div class="untree_co-section">
		<div class="container">
			<div class="row mb-5 justify-content-center">
				<div class="col-lg-6 text-center">
					<h2 class="section-title text-center mb-3">Our Services</h2>
					<p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts. Separated they live in Bookmarksgrove right at the coast of the Semantics, a large language ocean.</p>
				</div>
			</div>
			<div class="row align-items-stretch">
				<div class="col-lg-4 order-lg-1">
					<div class="h-100"><div class="frame h-100"><div class="feature-img-bg h-100" style="background-image: url('/vendor/geo-map/continents/images/hero-slider-1.jpg');"></div></div></div>
				</div>

				<div class="col-6 col-sm-6 col-lg-4 feature-1-wrap d-md-flex flex-md-column order-lg-1">

					<div class="feature-1 d-md-flex">
						<div class="align-self-center">
							<span class="flaticon-house display-4 text-primary"></span>
							<h3>Beautiful Condo</h3>
							<p class="mb-0">Even the all-powerful Pointing has no control about the blind texts.</p>
						</div>
					</div>

					<div class="feature-1 ">
						<div class="align-self-center">
							<span class="flaticon-restaurant display-4 text-primary"></span>
							<h3>Restaurants & Cafe</h3>
							<p class="mb-0">Even the all-powerful Pointing has no control about the blind texts.</p>
						</div>
					</div>

				</div>

				<div class="col-6 col-sm-6 col-lg-4 feature-1-wrap d-md-flex flex-md-column order-lg-3">

					<div class="feature-1 d-md-flex">
						<div class="align-self-center">
							<span class="flaticon-mail display-4 text-primary"></span>
							<h3>Easy to Connect</h3>
							<p class="mb-0">Even the all-powerful Pointing has no control about the blind texts.</p>
						</div>
					</div>

					<div class="feature-1 d-md-flex">
						<div class="align-self-center">
							<span class="flaticon-phone-call display-4 text-primary"></span>
							<h3>24/7 Support</h3>
							<p class="mb-0">Even the all-powerful Pointing has no control about the blind texts.</p>
						</div>
					</div>

				</div>

			</div>
		</div>
	</div>

	<div class="untree_co-section count-numbers py-5">
		<div class="container">
			<div class="row">
				<div class="col-6 col-sm-6 col-md-6 col-lg-3">
					<div class="counter-wrap">
						<div class="counter">
							<span class="" data-number="9313">0</span>
						</div>
						<span class="caption">No. of Travels</span>
					</div>
				</div>
				<div class="col-6 col-sm-6 col-md-6 col-lg-3">
					<div class="counter-wrap">
						<div class="counter">
							<span class="" data-number="8492">0</span>
						</div>
						<span class="caption">No. of Clients</span>
					</div>
				</div>
				<div class="col-6 col-sm-6 col-md-6 col-lg-3">
					<div class="counter-wrap">
						<div class="counter">
							<span class="" data-number="100">0</span>
						</div>
						<span class="caption">No. of Employees</span>
					</div>
				</div>
				<div class="col-6 col-sm-6 col-md-6 col-lg-3">
					<div class="counter-wrap">
						<div class="counter">
							<span class="" data-number="120">0</span>
						</div>
						<span class="caption">No. of Countries</span>
					</div>
				</div>
			</div>
		</div>
	</div>



	<div class="untree_co-section">
		<div class="container">
			<div class="row text-center justify-content-center mb-5">
				<div class="col-lg-7"><h2 class="section-title text-center">Popular Destination</h2></div>
			</div>

			<div class="owl-carousel owl-3-slider">

				<div class="item">
					<a class="media-thumb" href="/vendor/geo-map/continents/images/hero-slider-1.jpg" data-fancybox="gallery">
						<div class="media-text">
							<h3>Pragser Wildsee</h3>
							<span class="location">Italy</span>
						</div>
						<img src="/vendor/geo-map/continents/images/hero-slider-1.jpg" alt="Image" class="img-fluid">
					</a> 
				</div>

				<div class="item">
					<a class="media-thumb" href="/vendor/geo-map/continents/images/hero-slider-2.jpg" data-fancybox="gallery">
						<div class="media-text">
							<h3>Oia</h3>
							<span class="location">Greece</span>
						</div>
						<img src="/vendor/geo-map/continents/images/hero-slider-2.jpg" alt="Image" class="img-fluid">
					</a> 
				</div>

				<div class="item">
					<a class="media-thumb" href="/vendor/geo-map/continents/images/hero-slider-3.jpg" data-fancybox="gallery">
						<div class="media-text">
							<h3>Perhentian Islands</h3>
							<span class="location">Malaysia</span>
						</div>
						<img src="/vendor/geo-map/continents/images/hero-slider-3.jpg" alt="Image" class="img-fluid">
					</a> 
				</div>


				<div class="item">
					<a class="media-thumb" href="/vendor/geo-map/continents/images/hero-slider-4.jpg" data-fancybox="gallery">
						<div class="media-text">
							<h3>Rialto Bridge</h3>
							<span class="location">Italy</span>
						</div>
						<img src="/vendor/geo-map/continents/images/hero-slider-4.jpg" alt="Image" class="img-fluid">
					</a> 
				</div>

				<div class="item">
					<a class="media-thumb" href="/vendor/geo-map/continents/images/hero-slider-5.jpg" data-fancybox="gallery">
						<div class="media-text">
							<h3>San Francisco, United States</h3>
							<span class="location">United States</span>
						</div>
						<img src="/vendor/geo-map/continents/images/hero-slider-5.jpg" alt="Image" class="img-fluid">
					</a> 
				</div>

				<div class="item">
					<a class="media-thumb" href="/vendor/geo-map/continents/images/hero-slider-1.jpg" data-fancybox="gallery">
						<div class="media-text">
							<h3>Lake Thun</h3>
							<span class="location">Switzerland</span>
						</div>
						<img src="/vendor/geo-map/continents/images/hero-slider-2.jpg" alt="Image" class="img-fluid">
					</a> 
				</div>

			</div>

		</div>
	</div>




	<div class="untree_co-section">
		<div class="container">
			<div class="row justify-content-center text-center mb-5">
				<div class="col-lg-6">
					<h2 class="section-title text-center mb-3">Nos régions</h2>
					<p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts. Separated they live in Bookmarksgrove right at the coast of the Semantics, a large language ocean.</p>
				</div>
			</div>
			<div class="row">
				<div class="col-6 col-sm-6 col-md-6 col-lg-3 mb-4 mb-lg-0">
					<div class="media-1">
						<a href="#" class="d-block mb-3"><img src="/vendor/geo-map/continents/images/hero-slider-1.jpg" alt="Image" class="img-fluid"></a>
						<span class="d-flex align-items-center loc mb-2">
							<span class="icon-room mr-3"></span>
							<span>Italy</span>
						</span>
						<div class="d-flex align-items-center">
							<div>
								<h3><a href="#">Rialto Mountains</a></h3>
								<div class="price ml-auto">
									<span>$520.00</span>
								</div>
							</div>
							
						</div>
						
					</div>
				</div>
				<div class="col-6 col-sm-6 col-md-6 col-lg-3 mb-4 mb-lg-0">
					<div class="media-1">
						<a href="#" class="d-block mb-3"><img src="/vendor/geo-map/continents/images/hero-slider-2.jpg" alt="Image" class="img-fluid"></a>
						<span class="d-flex align-items-center loc mb-2">
							<span class="icon-room mr-3"></span>
							<span>United States</span>
						</span>
						<div class="d-flex align-items-center">
							<div>
								<h3><a href="#">San Francisco</a></h3>
								<div class="price ml-auto">
									<span>$520.00</span>
								</div>
							</div>
							
						</div>
						
					</div>
				</div>
				<div class="col-6 col-sm-6 col-md-6 col-lg-3 mb-4 mb-lg-0">
					<div class="media-1">
						<a href="#" class="d-block mb-3"><img src="/vendor/geo-map/continents/images/hero-slider-3.jpg" alt="Image" class="img-fluid"></a>
						<span class="d-flex align-items-center loc mb-2">
							<span class="icon-room mr-3"></span>
							<span>Malaysia</span>
						</span>
						<div class="d-flex align-items-center">
							<div>
								<h3><a href="#">Perhentian Islands</a></h3>
								<div class="price ml-auto">
									<span>$750.00</span>
								</div>
							</div>
							
						</div>
						
					</div>
				</div>
				<div class="col-6 col-sm-6 col-md-6 col-lg-3 mb-4 mb-lg-0">
					<div class="media-1">
						<a href="#" class="d-block mb-3"><img src="/vendor/geo-map/continents/images/hero-slider-4.jpg" alt="Image" class="img-fluid"></a>

						<span class="d-flex align-items-center loc mb-2">
							<span class="icon-room mr-3"></span>
							<span>Switzerland</span>
						</span>

						<div class="d-flex align-items-center">
							<div>
								<h3><a href="#">Lake Thun</a></h3>
								<div class="price ml-auto">
									<span>$520.00</span>
								</div>
							</div>
							
						</div>
						
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="untree_co-section">
		<div class="container">
			<div class="row justify-content-between align-items-center">
				
				<div class="col-lg-6">
					<figure class="img-play-video">
						<a id="play-video" class="video-play-button" href="https://www.youtube.com/watch?v=mwtbEGNABWU" data-fancybox="">
							<span></span>
						</a>
						<img src="/vendor/geo-map/continents/images/hero-slider-2.jpg" alt="Image" class="img-fluid rounded-20">
					</figure>
				</div>

				<div class="col-lg-5">
					<h2 class="section-title text-left mb-4">TNotre meilleur destination</h2>
					<p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts. Separated they live in Bookmarksgrove right at the coast of the Semantics, a large language ocean.</p>

					<p class="mb-4">A small river named Duden flows by their place and supplies it with the necessary regelialia. It is a paradisematic country, in which roasted parts of sentences fly into your mouth.</p>

					<ul class="list-unstyled two-col clearfix">
						<li>Outdoor recreation activities</li>
						<li>Airlines</li>
						<li>Car Rentals</li>
						<li>Cruise Lines</li>
						<li>Hotels</li>
						<li>Railways</li>
						<li>Travel Insurance</li>
						<li>Package Tours</li>
						<li>Insurance</li>
						<li>Guide Books</li>
					</ul>


					
				</div>
			</div>
		</div>
	</div>

	

		<div class="site-footer">
		<div class="inner first">
			<div class="container">
				<div class="row">
					<div class="col-md-6 col-lg-4">
						<div class="widget">
							<h3 class="heading">About Tour</h3>
							<p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts.</p>
						</div>
						<div class="widget d-none">
							<ul class="list-unstyled social">
								<li><a href="#"><span class="icon-twitter"></span></a></li>
								<li><a href="#"><span class="icon-instagram"></span></a></li>
								<li><a href="#"><span class="icon-facebook"></span></a></li>
								<li><a href="#"><span class="icon-linkedin"></span></a></li>
								<li><a href="#"><span class="icon-dribbble"></span></a></li>
								<li><a href="#"><span class="icon-pinterest"></span></a></li>
								<li><a href="#"><span class="icon-apple"></span></a></li>
								<li><a href="#"><span class="icon-google"></span></a></li>
							</ul>
						</div>
					</div>
					<div class="col-md-6 col-lg-2 pl-lg-5">
						<div class="widget">
							<h3 class="heading">Pages</h3>
							<ul class="links list-unstyled">
								<li><a href="#">Blog</a></li>
								<li><a href="#">About</a></li>
								<li><a href="#">Contact</a></li>
							</ul>
						</div>
					</div>
					<div class="col-md-6 col-lg-2">
						<div class="widget">
							<h3 class="heading">Resources</h3>
							<ul class="links list-unstyled">
								<li><a href="#">Blog</a></li>
								<li><a href="#">About</a></li>
								<li><a href="#">Contact</a></li>
							</ul>
						</div>
					</div>
					<div class="col-md-6 col-lg-4">
						<div class="widget">
							<h3 class="heading">Contact</h3>
							<ul class="list-unstyled quick-info links">
								<li class="email"><a href="#">mail@example.com</a></li>
								<li class="phone"><a href="#">+1 222 212 3819</a></li>
								<li class="address"><a href="#">43 Raymouth Rd. Baltemoer, London 3910</a></li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="inner dark d-none">
			<div class="container">
				<div class="row text-center">
					<div class="col-md-8 mb-3 mb-md-0 mx-auto">
						<p>Copyright &copy;<script>document.write(new Date().getFullYear());</script>. All Rights Reserved. &mdash; Designed with love by <a href="https://untree.co" class="link-highlight">Untree.co</a> <!-- License information: https://untree.co/license/ -->Distributed By <a href="https://themewagon.com" target="_blank">ThemeWagon</a>
						</p>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div id="overlayer"></div>
	<div class="loader">
		<div class="spinner-border" role="status">
			<span class="sr-only">Loading...</span>
		</div>
	</div>

	<!-- Scripts pour les nouvelles sections -->
	<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/masonry/4.2.2/masonry.pkgd.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/imagesloaded/5.0.0/imagesloaded.pkgd.min.js"></script>
	
	<script>
		// Initialisation Swiper pour Instagram Gallery
		const instagramSwiper = new Swiper('.instagramSwiper', {
			slidesPerView: 1,
			spaceBetween: 20,
			loop: true,
			autoplay: {
				delay: 3000,
				disableOnInteraction: false,
			},
			pagination: {
				el: '.swiper-pagination',
				clickable: true,
			},
			navigation: {
				nextEl: '.swiper-button-next',
				prevEl: '.swiper-button-prev',
			},
			breakpoints: {
				640: {
					slidesPerView: 2,
				},
				768: {
					slidesPerView: 3,
				},
				1024: {
					slidesPerView: 4,
				},
			},
		});
		
		// Initialisation Masonry pour Pinterest Grid
		document.addEventListener('DOMContentLoaded', function() {
			var grid = document.querySelector('#pinterestGrid');
			if(grid) {
				imagesLoaded(grid, function() {
					new Masonry(grid, {
						itemSelector: '.pinterest-item',
						columnWidth: '.pinterest-item',
						percentPosition: true,
						gutter: 15
					});
				});
			}
			
			// Gestionnaire d'événements pour les boutons "Ajouter au panier"
			document.querySelectorAll('.add-to-cart-btn').forEach(button => {
				button.addEventListener('click', function(e) {
					e.preventDefault();
					const productName = this.closest('.product-card').querySelector('h3').textContent;
					const productPrice = this.closest('.product-card').querySelector('.product-price').textContent;
					
					// Ici, vous pouvez ajouter la logique d'ajout au panier
					alert(`Produit ajouté au panier : ${productName} - ${productPrice}`);
					
					// Animation du bouton
					this.innerHTML = '<i class="fas fa-check me-2"></i>Ajouté !';
					this.style.background = '#28a745';
					
					setTimeout(() => {
						this.innerHTML = '<i class="fas fa-shopping-cart me-2"></i>Ajouter au panier';
						this.style.background = '#007bff';
					}, 2000);
				});
			});
		});
	</script>

	<script src="/vendor/geo-map/continents/js/jquery-3.4.1.min.js"></script>
	<script src="/vendor/geo-map/continents/js/popper.min.js"></script>
	<script src="/vendor/geo-map/continents/js/bootstrap.min.js"></script>
	<script src="/vendor/geo-map/continents/js/owl.carousel.min.js"></script>
	<script src="/vendor/geo-map/continents/js/jquery.animateNumber.min.js"></script>
	<script src="/vendor/geo-map/continents/js/jquery.waypoints.min.js"></script>
	<script src="/vendor/geo-map/continents/js/jquery.fancybox.min.js"></script>
	<script src="/vendor/geo-map/continents/js/aos.js"></script>
	<script src="/vendor/geo-map/continents/js/moment.min.js"></script>
	<script src="/vendor/geo-map/continents/js/daterangepicker.js"></script>

	<script src="/vendor/geo-map/continents/js/typed.js"></script>
	<script>
		$(function() {
			var slides = $('.slides'),
			images = slides.find('img');

			images.each(function(i) {
				$(this).attr('data-id', i + 1);
			})

			var typed = new Typed('.typed-words', {
				strings: [
                   <?php foreach(\App\Models\Country::active()->get() as $countrie){ ?> "<?php echo $countrie->name; ?>", <?php } ?>
                ],
				typeSpeed: 80,
				backSpeed: 80,
				backDelay: 4000,
				startDelay: 1000,
				loop: true,
				showCursor: true,
				preStringTyped: (arrayPos, self) => {
					arrayPos++;
					console.log(arrayPos);
					$('.slides img').removeClass('active');
					$('.slides img[data-id="'+arrayPos+'"]').addClass('active');
				}

			});
		})
	</script>

	<script src="/vendor/geo-map/continents/js/custom.js"></script>

</body>

</html>