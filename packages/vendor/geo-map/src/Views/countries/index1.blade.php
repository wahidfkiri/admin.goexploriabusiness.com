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
    

    
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="stylesheet" href="{{ asset('front/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/geo-map/css/map.css') }}">
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

    @include('geo-map::countries.components.index')

   
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
						<img src="/vendor/geo-map/continents/images/hero-slider-1.jpg" alt="Image" class="img-fluid active">
						<img src="/vendor/geo-map/continents/images/hero-slider-2.jpg" alt="Image" class="img-fluid">
						<img src="/vendor/geo-map/continents/images/hero-slider-3.jpg" alt="Image" class="img-fluid">
						<img src="/vendor/geo-map/continents/images/hero-slider-4.jpg" alt="Image" class="img-fluid">
						<img src="/vendor/geo-map/continents/images/hero-slider-5.jpg" alt="Image" class="img-fluid">
					</div>
				</div>
			</div>
		</div>
	</div>

    

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

    <!-- Section Gallerie Instagram Style -->
    <section class="instagram-gallery">
        <div class="container">
            <h2 class="section-title">Galleries Instagram</h2>
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
            <h2 class="section-title text-white">Découvertes Pinterest Style</h2>
            <div class="pinterest-grid" id="pinterestGrid">
                <!-- Item 1 -->
                <div class="pinterest-item">
                    <img src="https://images.unsplash.com/photo-1501785888041-af3ef285b470?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80" alt="Voyage">
                    <div class="pinterest-content">
                        <span class="pin-category">Voyage</span>
                        <h4>Les plus beaux paysages d'Europe</h4>
                        <p>Découvrez les destinations les plus photogéniques du vieux continent.</p>
                    </div>
                </div>
                
                <!-- Item 2 -->
                <div class="pinterest-item">
                    <img src="https://images.unsplash.com/photo-1469474968028-56623f02e42e?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80" alt="Nature">
                    <div class="pinterest-content">
                        <span class="pin-category">Nature</span>
                        <h4>Randonnées en montagne</h4>
                        <p>Les meilleurs sentiers pour les amateurs de randonnée et d'aventure.</p>
                    </div>
                </div>
                
                <!-- Item 3 - Video -->
                <div class="pinterest-item">
                    <video controls>
                        <source src="https://assets.mixkit.co/videos/preview/mixkit-waves-crashing-on-the-beach-5016-large.mp4" type="video/mp4">
                    </video>
                    <div class="pinterest-content">
                        <span class="pin-category">Vidéos</span>
                        <h4>Plages paradisiaques</h4>
                        <p>Détendez-vous avec ces vagues apaisantes sur des plages de sable blanc.</p>
                    </div>
                </div>
                
                <!-- Item 4 -->
                <div class="pinterest-item">
                    <img src="https://images.unsplash.com/photo-1506905925346-21bda4d32df4?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80" alt="Aventure">
                    <div class="pinterest-content">
                        <span class="pin-category">Aventure</span>
                        <h4>Escalade extrême</h4>
                        <p>Pour les amateurs de sensations fortes, découvrez les meilleurs spots d'escalade.</p>
                    </div>
                </div>
                
                <!-- Item 5 -->
                <div class="pinterest-item">
                    <img src="https://images.unsplash.com/photo-1488646953014-85cb44e25828?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80" alt="Urban">
                    <div class="pinterest-content">
                        <span class="pin-category">Urbain</span>
                        <h4>Architecture moderne</h4>
                        <p>Explorez les plus belles réalisations architecturales des métropoles mondiales.</p>
                    </div>
                </div>
                
                <!-- Item 6 - Video -->
                <div class="pinterest-item">
                    <video controls>
                        <source src="https://assets.mixkit.co/videos/preview/mixkit-going-down-a-curved-highway-through-a-mountain-range-41576-large.mp4" type="video/mp4">
                    </video>
                    <div class="pinterest-content">
                        <span class="pin-category">Roadtrip</span>
                        <h4>Routes panoramiques</h4>
                        <p>Les plus belles routes pour vos roadtrips à travers le monde.</p>
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
                                <span class="event-price">75$</span>
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
                                <span class="event-price">Gratuit</span>
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
                                <span class="event-price">25$</span>
                                <a href="#" class="btn btn-primary btn-sm">Acheter</a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Événement 4 -->
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="event-card">
                        <div class="event-img">
                            <img src="https://images.unsplash.com/photo-1519677100203-6f5d4c7a08c9?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80" alt="Marathon">
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
                                <span class="event-price">60$</span>
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


	<div class="untree_co-section testimonial-section mt-5">
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-lg-7 text-center">
					<h2 class="section-title text-center mb-5">Testimonials</h2>

					<div class="owl-single owl-carousel no-nav">
						<div class="testimonial mx-auto">
							<figure class="img-wrap">
								<img src="/vendor/geo-map/continents/images/person_2.jpg" alt="Image" class="img-fluid">
							</figure>
							<h3 class="name">Adam Aderson</h3>
							<blockquote>
								<p>&ldquo;There live the blind texts. Separated they live in Bookmarksgrove right at the coast of the Semantics, a large language ocean.&rdquo;</p>
							</blockquote>
						</div>

						<div class="testimonial mx-auto">
							<figure class="img-wrap">
								<img src="/vendor/geo-map/continents/images/person_3.jpg" alt="Image" class="img-fluid">
							</figure>
							<h3 class="name">Lukas Devlin</h3>
							<blockquote>
								<p>&ldquo;There live the blind texts. Separated they live in Bookmarksgrove right at the coast of the Semantics, a large language ocean.&rdquo;</p>
							</blockquote>
						</div>

						<div class="testimonial mx-auto">
							<figure class="img-wrap">
								<img src="/vendor/geo-map/continents/images/person_4.jpg" alt="Image" class="img-fluid">
							</figure>
							<h3 class="name">Kayla Bryant</h3>
							<blockquote>
								<p>&ldquo;There live the blind texts. Separated they live in Bookmarksgrove right at the coast of the Semantics, a large language ocean.&rdquo;</p>
							</blockquote>
						</div>

					</div>

				</div>
			</div>
		</div>
	</div>


	<div class="untree_co-section">
		<div class="container">
			<div class="row justify-content-center text-center mb-5">
				<div class="col-lg-6">
					<h2 class="section-title text-center mb-3">Special Offers &amp; Discounts</h2>
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
					<h2 class="section-title text-left mb-4">Take a look at Tour Video</h2>
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

					<p><a href="#" class="btn btn-primary">Get Started</a></p>

					
				</div>
			</div>
		</div>
	</div>

	

       <!-- Main Content -->
<div class="app-container" id="appContainer">
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
                
                <!-- Sidebar à droite - Positionnée dans app-container -->
                <div class="sidebar-right" id="sidebarRight">
                    
                    <div class="filters-section">
                        <h3>Filtres</h3>
                        
                        <!-- Sélection de province -->
                        <div class="filter-group">
                            <label for="province-filter">Province/Région :</label>
                            <select id="province-filter" class="form-select">
                                <option value="">Chargement des provinces...</option>
                            </select>
                        </div>
                        
                        <!-- Sélection de catégorie -->
                        <div class="filter-group">
                            <label for="category-filter">Catégorie :</label>
                            <select id="category-filter" class="form-select">
                                <option value="all">Toutes les catégories</option>
                            </select>
                        </div>
                        
                        <!-- Filtre de rayon -->
                        <div class="filter-group">
                            <label for="radius-filter">Rayon de recherche :</label>
                            <div class="slider-container">
                                <input type="range" id="radius-filter" min="1" max="500" value="100" class="form-range">
                                <span id="radius-value">100 km</span>
                            </div>
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
    
    <!-- Modal pour les détails -->
    <div id="place-modal" class="modal">
        <div class="modal-content">
            <span class="close-modal">&times;</span>
            <div id="modal-content"></div>
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
						<div class="widget">
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



		<div class="inner dark">
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
				strings: ["San Francisco."," Paris."," New Zealand.", " Maui.", " London."],
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
            this.categories = [];
            this.provinces = [];
            this.selectedCategory = 'all';
            this.selectedProvince = '';
            this.radius = 100;
            this.swiper = null;
            this.countryCode = "<?php echo $countrie->code; ?>";
            this.countryLat = <?php echo $countrie->latitude; ?>;
            this.countryLng = <?php echo $countrie->longitude; ?>;
            this.hoverTimeout = null;
            this.activePlaceId = null;
            this.tooltips = {};
            this.userMarker = null;
            this.activePlace = null;
            
            // Images statiques par catégorie
            this.staticImages = {
                restaurant: [
                    'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4',
                    'https://images.unsplash.com/photo-1559925393-8be0ec4767c8',
                    'https://images.unsplash.com/photo-1414235077428-338989a2e8c0',
                    'https://images.unsplash.com/photo-1554679665-f5537f187268'
                ],
                hotel: [
                    'https://images.unsplash.com/photo-1566073771259-6a8506099945',
                    'https://images.unsplash.com/photo-1564501049418-3c27787d01e8',
                    'https://images.unsplash.com/photo-1584132967334-10e028bd69f7',
                    'https://images.unsplash.com/photo-1571896349842-33c89424de2d'
                ],
                museum: [
                    'https://images.unsplash.com/photo-1530122037265-a5f1f91d3b99',
                    'https://images.unsplash.com/photo-1582555172866-f73bb12a2ab3',
                    'https://images.unsplash.com/photo-1578662996442-48f60103fc96',
                    'https://images.unsplash.com/photo-1596462502278-27bfdc403348'
                ],
                park: [
                    'https://images.unsplash.com/photo-1506905925346-21bda4d32df4',
                    'https://images.unsplash.com/photo-1503614472-8c93d56e92ce',
                    'https://images.unsplash.com/photo-1518837695005-2083093ee35b',
                    'https://images.unsplash.com/photo-1448375240586-882707db888b'
                ],
                shopping: [
                    'https://images.unsplash.com/photo-1441986300917-64674bd600d8',
                    'https://images.unsplash.com/photo-1556742049-0cfed4f6a45d',
                    'https://images.unsplash.com/photo-1556742044-3c52d6e88c62',
                    'https://images.unsplash.com/photo-1586023492125-27b2c045efd7'
                ],
                monument: [
                    'https://images.unsplash.com/photo-1546436836-07bfe9ee8b9c',
                    'https://images.unsplash.com/photo-1559592413-7cec4d0cae2b',
                    'https://images.unsplash.com/photo-1523906834658-6e24ef2386f9',
                    'https://images.unsplash.com/photo-1529260830199-42c24126f198'
                ]
            };
            
            // Images par défaut si catégorie inconnue
            this.defaultImages = [
                'https://images.unsplash.com/photo-1518837695005-2083093ee35b',
                'https://images.unsplash.com/photo-1506905925346-21bda4d32df4',
                'https://images.unsplash.com/photo-1448375240586-882707db888b',
                'https://images.unsplash.com/photo-1503614472-8c93d56e92ce'
            ];
            
            this.init();
        }
        
        async init() {
            try {
                // Initialiser la carte
                this.initMap();
                
                // Initialiser la sidebar
                this.initSidebar();
                
                // Charger les provinces
                await this.loadProvinces();
                
                // Charger les catégories
                await this.loadCategories();
                
                // Charger les lieux initiaux
                await this.loadPlaces();
                
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
                
                // Créer la carte avec la position du pays
                this.map = L.map('map').setView([this.countryLat, this.countryLng], 4);
                
                // Ajouter les tuiles OpenStreetMap
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributeurs',
                    maxZoom: 19,
                    detectRetina: true
                }).addTo(this.map);
                
                // Ajouter un contrôle d'échelle
                L.control.scale({ imperial: false }).addTo(this.map);
                
                // Ajouter un contrôle de localisation custom
                this.addLocateControl();
                
            } catch (error) {
                console.error('Erreur lors de l\'initialisation de la carte:', error);
                document.getElementById('mapLoading').innerHTML = `
                    <div class="text-center">
                        <i class="fas fa-exclamation-triangle fa-3x text-danger mb-3"></i>
                        <h4>Erreur de chargement de la carte</h4>
                        <p>${error.message}</p>
                        <button onclick="location.reload()" class="btn btn-primary mt-2">
                            <i class="fas fa-redo"></i> Réessayer
                        </button>
                    </div>
                `;
            }
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
            
            // Fermer la sidebar au clic en dehors sur mobile
            document.addEventListener('click', (e) => {
                if (window.innerWidth <= 768 && 
                    sidebar.classList.contains('active') &&
                    !sidebar.contains(e.target) &&
                    !sidebarToggle.contains(e.target)) {
                    sidebar.classList.remove('active');
                    document.getElementById('sidebarToggleIcon').className = 'fas fa-bars';
                }
            });
            
            // Ajuster la hauteur de la sidebar en fonction du header
            this.adjustSidebarHeight();
        }
        
        adjustSidebarHeight() {
            const sidebar = document.getElementById('sidebarRight');
            if (!sidebar) return;
            
            // Calculer la hauteur totale des headers
            const infoHeader = document.querySelector('.info-header');
            const topBar = document.querySelector('.top-bar');
            const mainNavbar = document.querySelector('.main-navbar');
            
            let totalHeaderHeight = 0;
            if (infoHeader) totalHeaderHeight += infoHeader.offsetHeight;
            if (topBar) totalHeaderHeight += topBar.offsetHeight;
            if (mainNavbar) totalHeaderHeight += mainNavbar.offsetHeight;
            
            // Définir la hauteur de la sidebar
            const viewportHeight = window.innerHeight;
            const sidebarHeight = viewportHeight - totalHeaderHeight;
            
     
            
           
            
            // Recalculer lors du redimensionnement
            window.addEventListener('resize', () => {
                this.adjustSidebarHeight();
            });
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
        
        async loadProvinces() {
            try {
                // Récupérer les provinces via l'API
                const response = await axios.get(`/api/provinces/${this.countryCode}`);
                this.provinces = response.data.provinces || response.data;
                this.populateProvinceFilter();
            } catch (error) {
                console.error('Erreur lors du chargement des provinces:', error);
                
                // Données par défaut pour le Canada
                this.provinces = [
                    { id: 'qc', name: 'Québec', latitude: 52.9399, longitude: -73.5491 },
                    { id: 'on', name: 'Ontario', latitude: 51.2538, longitude: -85.3232 },
                    { id: 'bc', name: 'Colombie-Britannique', latitude: 53.7267, longitude: -127.6476 },
                    { id: 'ab', name: 'Alberta', latitude: 53.9333, longitude: -116.5765 },
                    { id: 'mb', name: 'Manitoba', latitude: 53.7609, longitude: -98.8139 },
                    { id: 'sk', name: 'Saskatchewan', latitude: 52.9399, longitude: -106.4509 },
                    { id: 'ns', name: 'Nouvelle-Écosse', latitude: 44.6820, longitude: -63.7443 },
                    { id: 'nb', name: 'Nouveau-Brunswick', latitude: 46.5653, longitude: -66.4619 },
                    { id: 'nl', name: 'Terre-Neuve-et-Labrador', latitude: 53.1355, longitude: -57.6604 },
                    { id: 'pe', name: 'Île-du-Prince-Édouard', latitude: 46.5107, longitude: -63.4168 },
                    { id: 'yt', name: 'Yukon', latitude: 64.2823, longitude: -135.0000 },
                    { id: 'nt', name: 'Territoires du Nord-Ouest', latitude: 64.8255, longitude: -124.8457 },
                    { id: 'nu', name: 'Nunavut', latitude: 70.2998, longitude: -83.1076 }
                ];
                
                this.populateProvinceFilter();
            }
        }
        
        populateProvinceFilter() {
            const filter = document.getElementById('province-filter');
            if (!filter) return;
            
            filter.innerHTML = '<option value="">Toutes les provinces</option>';
            
            this.provinces.forEach(province => {
                const option = document.createElement('option');
                option.value = province.id || province.code;
                option.textContent = province.name || province.province_name;
                option.dataset.lat = province.latitude;
                option.dataset.lng = province.longitude;
                filter.appendChild(option);
            });
        }
        
        async loadCategories() {
            try {
                const response = await axios.get('/api/categories');
                this.categories = response.data;
                this.populateCategoryFilter();
            } catch (error) {
                console.error('Erreur lors du chargement des catégories:', error);
                this.categories = ['restaurant', 'hotel', 'museum', 'park', 'shopping', 'monument'];
                this.populateCategoryFilter();
            }
        }
        
        populateCategoryFilter() {
            const filter = document.getElementById('category-filter');
            if (!filter) return;
            
            filter.innerHTML = '<option value="all">Toutes les catégories</option>';
            
            this.categories.forEach(category => {
                const option = document.createElement('option');
                option.value = category;
                option.textContent = this.capitalizeFirstLetter(category);
                filter.appendChild(option);
            });
        }
        
        async loadPlaces() {
            try {
                // Afficher le chargement
                const placesList = document.getElementById('places-list');
                if (placesList) {
                    placesList.innerHTML = `
                        <div class="text-center py-4">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Chargement...</span>
                            </div>
                            <p class="mt-2">Recherche des lieux...</p>
                        </div>
                    `;
                }
                
                const params = {
                    category: this.selectedCategory === 'all' ? null : this.selectedCategory,
                    province: this.selectedProvince || null,
                    radius: this.radius,
                    lat: this.currentLocation?.lat || this.countryLat,
                    lng: this.currentLocation?.lng || this.countryLng
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
                
                this.places = Array.isArray(response.data) ? response.data : [];
                
                if (response.data?.data && Array.isArray(response.data.data)) {
                    this.places = response.data.data;
                }
                
                if (response.data?.places && Array.isArray(response.data.places)) {
                    this.places = response.data.places;
                }
                
                // Ajouter des images statiques aux lieux
                this.addStaticImagesToPlaces();
                
                this.updatePlacesCount();
                this.renderPlacesList();
                this.addMarkersToMap();
                
            } catch (error) {
                console.error('Erreur lors du chargement des lieux:', error);
                this.showSamplePlaces();
            }
        }
        
        addStaticImagesToPlaces() {
            // Ajouter des images statiques à chaque lieu
            this.places = this.places.map(place => {
                // Déterminer les images à utiliser
                let images = [];
                const category = place.category || 'monument';
                
                if (this.staticImages[category]) {
                    images = [...this.staticImages[category]];
                } else {
                    images = [...this.defaultImages];
                }
                
                // Mélanger les images pour la variété
                images = this.shuffleArray(images);
                
                // Garder seulement 4 images maximum
                images = images.slice(0, 4);
                
                return {
                    ...place,
                    images: images
                };
            });
        }
        
        shuffleArray(array) {
            const newArray = [...array];
            for (let i = newArray.length - 1; i > 0; i--) {
                const j = Math.floor(Math.random() * (i + 1));
                [newArray[i], newArray[j]] = [newArray[j], newArray[i]];
            }
            return newArray;
        }
        
        showSamplePlaces() {
            // Données de démonstration avec images statiques
            this.places = [
                {
                    id: 1,
                    name: 'Château Frontenac',
                    description: 'Hôtel historique emblématique de Québec',
                    latitude: 46.8117,
                    longitude: -71.2044,
                    category: 'hotel',
                    address: '1 Rue des Carrières, Québec, QC',
                    phone: '+1-418-692-3861',
                    website: 'https://www.fairmont.com/frontenac-quebec/'
                },
                {
                    id: 2,
                    name: 'Tour CN',
                    description: 'Tour de communication emblématique de Toronto',
                    latitude: 43.6426,
                    longitude: -79.3871,
                    category: 'monument',
                    address: '290 Bremner Blvd, Toronto, ON',
                    phone: '+1-416-868-6937',
                    website: 'https://www.cntower.ca'
                }
            ];
            
            // Ajouter des images statiques
            this.addStaticImagesToPlaces();
            
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
            Object.values(this.markers).forEach(marker => {
                if (marker && marker.remove) {
                    marker.remove();
                }
            });
            this.markers = {};
            
            // Supprimer les tooltips
            Object.values(this.tooltips).forEach(tooltip => {
                if (tooltip && tooltip.remove) {
                    tooltip.remove();
                }
            });
            this.tooltips = {};
        }
        
        createMarker(place) {
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
            
            const marker = L.marker([place.latitude, place.longitude], { 
                icon: icon,
                title: place.name
            }).addTo(this.map);
            
            // Créer un tooltip pour le survol
            const tooltip = L.tooltip({
                direction: 'top',
                className: 'place-tooltip',
                offset: [0, -10],
                permanent: false,
                opacity: 0.9,
                interactive: true
            })
            .setContent(this.createTooltipContent(place))
            .setLatLng([place.latitude, place.longitude]);
            
            // Événements de survol
            marker.on('mouseover', () => {
                this.highlightPlace(place.id);
                tooltip.addTo(this.map);
            });
            
            marker.on('mouseout', () => {
                this.unhighlightPlace(place.id);
                setTimeout(() => {
                    if (!this.isMouseOverTooltip) {
                        tooltip.remove();
                    }
                }, 100);
            });
            
            marker.on('click', () => {
                this.showPlaceModal(place);
            });
            
            // Gérer les événements du tooltip
            tooltip.on('add', () => {
                this.setupTooltipEvents(tooltip, place);
            });
            
            this.markers[place.id] = marker;
            this.tooltips[place.id] = tooltip;
            
            return marker;
        }
        
        setupTooltipEvents(tooltip, place) {
            // Attendre que le tooltip soit rendu
            setTimeout(() => {
                const tooltipElement = tooltip.getElement();
                if (tooltipElement) {
                    // Gérer l'entrée/sortie de la souris
                    tooltipElement.addEventListener('mouseenter', () => {
                        this.isMouseOverTooltip = true;
                        this.highlightPlace(place.id);
                    });
                    
                    tooltipElement.addEventListener('mouseleave', () => {
                        this.isMouseOverTooltip = false;
                        this.unhighlightPlace(place.id);
                        setTimeout(() => {
                            if (!this.isMouseOverTooltip) {
                                tooltip.remove();
                            }
                        }, 100);
                    });
                    
                    // Trouver le bouton dans le tooltip
                    const detailsBtn = tooltipElement.querySelector('.tooltip-details-btn');
                    if (detailsBtn) {
                        detailsBtn.addEventListener('click', (e) => {
                            e.preventDefault();
                            e.stopPropagation();
                            this.showPlaceModal(place);
                        });
                    }
                }
            }, 50);
        }
        
        createTooltipContent(place) {
            const firstImage = place.images && place.images.length > 0 
                ? place.images[0] 
                : 'https://via.placeholder.com/200x150?text=No+Image';
            
            return `
                <div style="min-width:200px; padding:10px;">
                    <div style="display:flex; align-items:center; gap:10px; margin-bottom:8px;">
                        <div style="width:40px; height:40px; border-radius:50%; background:${this.getCategoryColor(place.category)}; display:flex; align-items:center; justify-content:center;">
                            <i class="${this.getCategoryIcon(place.category)}" style="color:white; font-size:18px;"></i>
                        </div>
                        <div>
                            <strong style="color:#1a1a1a; font-size:15px;">${place.name}</strong>
                            <div style="font-size:12px; color:#666; margin-top:2px;">${this.capitalizeFirstLetter(place.category)}</div>
                        </div>
                    </div>
                    <div style="width:100%; height:100px; border-radius:6px; overflow:hidden; margin-bottom:8px;">
                        <img src="${firstImage}" alt="${place.name}" style="width:100%; height:100%; object-fit:cover;">
                    </div>
                    <p style="margin:0; font-size:12px; color:#666; line-height:1.4; -webkit-line-clamp: 3;">
                        ${place.description?.substring(0, 30) || 'Aucune description disponible'}...
                    </p>
                    <div style="margin-top:10px; text-align:center;">
                        <button class="tooltip-details-btn" 
                                style="background:#4299e1; color:white; border:none; border-radius:4px; padding:8px 15px; font-size:13px; cursor:pointer; width:100%; transition:all 0.3s ease;">
                            <i class="fas fa-info-circle"></i> Voir détails complets
                        </button>
                    </div>
                </div>
            `;
        }
        
        highlightPlace(placeId) {
            // Mettre en surbrillance le marqueur
            const marker = this.markers[placeId];
            if (marker) {
                const iconElement = marker.getElement();
                if (iconElement) {
                    const markerIcon = iconElement.querySelector('.marker-icon');
                    if (markerIcon) {
                        markerIcon.classList.add('highlighted');
                    }
                }
            }
            
            // Mettre en surbrillance l'élément dans la sidebar
            const placeElement = document.querySelector(`.place-item[data-id="${placeId}"]`);
            if (placeElement) {
                placeElement.classList.add('active');
                
                // Faire défiler jusqu'à l'élément
                placeElement.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
            }
        }
        
        unhighlightPlace(placeId) {
            // Retirer la surbrillance du marqueur
            const marker = this.markers[placeId];
            if (marker) {
                const iconElement = marker.getElement();
                if (iconElement) {
                    const markerIcon = iconElement.querySelector('.marker-icon');
                    if (markerIcon) {
                        markerIcon.classList.remove('highlighted');
                    }
                }
            }
            
            // Retirer la surbrillance de l'élément dans la sidebar
            const placeElement = document.querySelector(`.place-item[data-id="${placeId}"]`);
            if (placeElement) {
                placeElement.classList.remove('active');
            }
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
            
            const image = place.images && place.images.length > 0 
                ? place.images[0] 
                : 'https://via.placeholder.com/400x150?text=No+Image';
            
            div.innerHTML = `
                <div class="place-image">
                    <img src="${image}" alt="${place.name}" loading="lazy">
                </div>
                <div class="place-info">
                    <h4>${place.name}</h4>
                    <span class="place-category" style="background:${this.getCategoryColor(place.category)}">
                        ${this.capitalizeFirstLetter(place.category)}
                    </span>
                    <p class="place-description">${place.description?.substring(0, 100) || 'Aucune description disponible'}...</p>
                    <div class="place-actions">
                        <button class="btn view-details-btn" data-id="${place.id}" style="background:#4299e1; color:white;">
                            <i class="fas fa-eye"></i> Détails
                        </button>
                        <button class="btn locate-btn-small" data-id="${place.id}" style="background:#48bb78; color:white;">
                            <i class="fas fa-map-marker-alt"></i> Carte
                        </button>
                    </div>
                </div>
            `;
            
            // Événements pour la sidebar
            div.addEventListener('mouseenter', () => {
                this.highlightPlace(place.id);
            });
            
            div.addEventListener('mouseleave', () => {
                this.unhighlightPlace(place.id);
            });
            
            div.querySelector('.view-details-btn').addEventListener('click', (e) => {
                e.stopPropagation();
                this.showPlaceModal(place);
            });
            
            div.querySelector('.locate-btn-small').addEventListener('click', (e) => {
                e.stopPropagation();
                this.centerOnPlace(place);
            });
            
            div.addEventListener('click', (e) => {
                if (!e.target.closest('button')) {
                    this.showPlaceModal(place);
                }
            });
            
            return div;
        }
        
        centerOnPlace(place) {
            this.map.setView([place.latitude, place.longitude], 15);
            this.highlightPlace(place.id);
            
            // Ouvrir le tooltip
            const tooltip = this.tooltips[place.id];
            if (tooltip) {
                tooltip.addTo(this.map);
                
                // Fermer le tooltip après 3 secondes
                setTimeout(() => {
                    tooltip.remove();
                }, 3000);
            }
        }
        
        showPlaceModal(place) {
            const modal = document.getElementById('place-modal');
            const modalContent = document.getElementById('modal-content');
            
            if (!modal || !modalContent) return;
            
            this.activePlace = place;
            modalContent.innerHTML = this.createModalContent(place);
            modal.style.display = 'block';
            
            // Initialiser Swiper avec un délai
            setTimeout(() => {
                this.initModalSwiper();
            }, 100);
            
            this.centerOnPlace(place);
        }
        
        createModalContent(place) {
            const images = place.images && place.images.length > 0 
                ? place.images 
                : ['https://via.placeholder.com/800x400?text=No+Image'];
            
            return `
                <div class="place-modal-content">
                    <!-- Slider d'images -->
                    <div class="modal-slider">
                        <div class="swiper modalSwiper">
                            <div class="swiper-wrapper">
                                ${images.map((img, index) => `
                                    <div class="swiper-slide">
                                        <img src="${img}" alt="${place.name} - Image ${index + 1}" loading="lazy">
                                        <div class="image-counter">${index + 1} / ${images.length}</div>
                                    </div>
                                `).join('')}
                            </div>
                            <div class="swiper-pagination"></div>
                            <div class="swiper-button-next"></div>
                            <div class="swiper-button-prev"></div>
                        </div>
                    </div>
                    
                    <!-- Thumbnails -->
                    <div class="modal-thumbnails" id="modalThumbnails">
                        ${images.map((img, index) => `
                            <div class="thumbnail ${index === 0 ? 'active' : ''}" data-index="${index}">
                                <img src="${img}" alt="Thumbnail ${index + 1}" loading="lazy">
                            </div>
                        `).join('')}
                    </div>
                    
                    <!-- Contenu de la modal -->
                    <div style="padding:30px;">
                        <div class="modal-header" style="margin-bottom:25px;">
                            <h2 style="margin:0 0 10px 0; color:#1a1a1a; font-size:1.8rem;">${place.name}</h2>
                            <div style="display:flex; align-items:center; gap:10px;">
                                <span style="background:${this.getCategoryColor(place.category)}; color:white; padding:6px 16px; border-radius:20px; font-size:14px; font-weight:500;">
                                    ${this.capitalizeFirstLetter(place.category)}
                                </span>
                                <span style="color:#666; font-size:14px;">
                                    <i class="fas fa-map-marker-alt"></i> ${this.getProvinceName(place.latitude, place.longitude)}
                                </span>
                            </div>
                        </div>
                        
                        <div class="place-details">
                            ${place.description ? `
                                <div style="margin-bottom:30px;">
                                    <h4 style="color:#333; margin-bottom:15px; font-size:1.2rem;">
                                        <i class="fas fa-info-circle" style="color:#4299e1;"></i> Description
                                    </h4>
                                    <p style="color:#666; line-height:1.6; font-size:16px;">${place.description}</p>
                                </div>
                            ` : ''}
                            
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
                </div>
            `;
        }
        
        initModalSwiper() {
            // Détruire Swiper existant
            if (this.swiper && this.swiper.destroy) {
                try {
                    this.swiper.destroy(true, true);
                } catch (error) {
                    console.warn('Error destroying Swiper:', error);
                }
                this.swiper = null;
            }
            
            // Vérifier si Swiper est chargé
            if (typeof Swiper === 'undefined') {
                console.error('Swiper is not loaded');
                return;
            }
            
            // Vérifier si l'élément Swiper existe
            const swiperElement = document.querySelector('.modalSwiper');
            if (!swiperElement) {
                console.error('Swiper element not found');
                return;
            }
            
            try {
                this.swiper = new Swiper('.modalSwiper', {
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
                        disableOnInteraction: false,
                    },
                    on: {
                        init: () => {
                            console.log('Swiper initialized');
                            // Mettre à jour les thumbnails après l'initialisation
                            setTimeout(() => {
                                this.updateThumbnails();
                            }, 50);
                        },
                        slideChange: () => {
                            this.updateThumbnails();
                        }
                    }
                });
                
                // Gérer les thumbnails
                this.setupThumbnailEvents();
                
            } catch (error) {
                console.error('Error initializing Swiper:', error);
            }
        }
        
        setupThumbnailEvents() {
            // Attendre que le DOM soit mis à jour
            setTimeout(() => {
                const thumbnails = document.querySelectorAll('.thumbnail');
                if (!thumbnails || thumbnails.length === 0) {
                    return;
                }
                
                thumbnails.forEach(thumbnail => {
                    // Retirer les anciens événements
                    const newThumbnail = thumbnail.cloneNode(true);
                    thumbnail.parentNode.replaceChild(newThumbnail, thumbnail);
                });
                
                // Référencer les nouveaux éléments
                const newThumbnails = document.querySelectorAll('.thumbnail');
                
                newThumbnails.forEach(thumbnail => {
                    thumbnail.addEventListener('click', () => {
                        if (!this.swiper) return;
                        
                        const index = parseInt(thumbnail.dataset.index);
                        if (!isNaN(index)) {
                            this.swiper.slideTo(index);
                            this.updateThumbnails();
                        }
                    });
                });
            }, 100);
        }
        
        updateThumbnails() {
            // Vérifier si Swiper est initialisé
            if (!this.swiper || !this.swiper.realIndex) {
                return;
            }
            
            const thumbnails = document.querySelectorAll('.thumbnail');
            if (!thumbnails || thumbnails.length === 0) {
                return;
            }
            
            const activeIndex = this.swiper.realIndex;
            
            thumbnails.forEach((thumbnail, index) => {
                if (index === activeIndex) {
                    thumbnail.classList.add('active');
                } else {
                    thumbnail.classList.remove('active');
                }
            });
        }
        
        getDirections(place) {
            // Vérifier si place est défini
            if (!place) {
                console.error('Place is undefined');
                return;
            }
            
            // Vérifier si l'utilisateur est localisé
            if (!this.currentLocation) {
                alert('Veuillez d\'abord vous localiser en cliquant sur "Me localiser" pour calculer un itinéraire.');
                return;
            }
            
            // Vérifier les coordonnées
            if (!place.latitude || !place.longitude) {
                alert('Impossible de calculer l\'itinéraire : coordonnées du lieu manquantes.');
                return;
            }
            
            const startLat = this.currentLocation.lat;
            const startLng = this.currentLocation.lng;
            const endLat = place.latitude;
            const endLng = place.longitude;
            
            // Calculer la distance
            const distance = this.calculateDistance(startLat, startLng, endLat, endLng);
            
            // Ouvrir Google Maps avec l'itinéraire
            const googleMapsUrl = `https://www.google.com/maps/dir/?api=1&origin=${startLat},${startLng}&destination=${endLat},${endLng}&travelmode=driving`;
            
            // Afficher une confirmation
            const confirmMessage = `Calcul d'itinéraire vers "${place.name}":
            
            • Distance: ${distance.toFixed(1)} km
            • Départ: Votre position actuelle
            • Arrivée: ${place.address || 'Destination'}

            Voulez-vous ouvrir Google Maps pour voir l'itinéraire détaillé ?`;
            
            if (confirm(confirmMessage)) {
                window.open(googleMapsUrl, '_blank');
            }
            
            // Fermer la modal
            this.closeModal();
        }
        
        calculateDistance(lat1, lon1, lat2, lon2) {
            // Formule de Haversine pour calculer la distance en km
            const R = 6371; // Rayon de la Terre en km
            const dLat = this.deg2rad(lat2 - lat1);
            const dLon = this.deg2rad(lon2 - lon1);
            const a = 
                Math.sin(dLat/2) * Math.sin(dLat/2) +
                Math.cos(this.deg2rad(lat1)) * Math.cos(this.deg2rad(lat2)) * 
                Math.sin(dLon/2) * Math.sin(dLon/2);
            const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
            return R * c;
        }
        
        deg2rad(deg) {
            return deg * (Math.PI/180);
        }
        
        getProvinceName(lat, lng) {
            // Simple approximation pour déterminer la province
            const provinces = {
                'qc': { lat: 52.9399, lng: -73.5491, name: 'Québec' },
                'on': { lat: 51.2538, lng: -85.3232, name: 'Ontario' },
                'bc': { lat: 53.7267, lng: -127.6476, name: 'Colombie-Britannique' },
                'ab': { lat: 53.9333, lng: -116.5765, name: 'Alberta' },
                'mb': { lat: 53.7609, lng: -98.8139, name: 'Manitoba' },
                'sk': { lat: 52.9399, lng: -106.4509, name: 'Saskatchewan' },
                'ns': { lat: 44.6820, lng: -63.7443, name: 'Nouvelle-Écosse' },
                'nb': { lat: 46.5653, lng: -66.4619, name: 'Nouveau-Brunswick' },
                'nl': { lat: 53.1355, lng: -57.6604, name: 'Terre-Neuve-et-Labrador' },
                'pe': { lat: 46.5107, lng: -63.4168, name: 'Île-du-Prince-Édouard' }
            };
            
            let closestProvince = 'Canada';
            let minDistance = Infinity;
            
            for (const [code, province] of Object.entries(provinces)) {
                const distance = Math.sqrt(
                    Math.pow(lat - province.lat, 2) + 
                    Math.pow(lng - province.lng, 2)
                );
                if (distance < minDistance) {
                    minDistance = distance;
                    closestProvince = province.name;
                }
            }
            
            return closestProvince;
        }
        
        setupEventListeners() {
            // Filtre de province
            const provinceFilter = document.getElementById('province-filter');
            if (provinceFilter) {
                provinceFilter.addEventListener('change', (e) => {
                    this.selectedProvince = e.target.value;
                    
                    // Centrer sur la province si une province spécifique est sélectionnée
                    if (this.selectedProvince) {
                        const option = e.target.selectedOptions[0];
                        const lat = parseFloat(option.dataset.lat);
                        const lng = parseFloat(option.dataset.lng);
                        
                        if (!isNaN(lat) && !isNaN(lng)) {
                            this.map.setView([lat, lng], 6);
                        }
                    }
                    
                    this.loadPlaces();
                });
            }
            
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
            if (!navigator.geolocation) {
                alert('La géolocalisation n\'est pas supportée par votre navigateur.');
                return;
            }
            
            // Afficher un indicateur de chargement
            const locateBtn = document.getElementById('locate-me');
            const originalHTML = locateBtn.innerHTML;
            locateBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Localisation...';
            locateBtn.disabled = true;
            
            navigator.geolocation.getCurrentPosition(
                (position) => {
                    const { latitude, longitude } = position.coords;
                    this.currentLocation = { lat: latitude, lng: longitude };
                    
                    // Centrer la carte sur la position
                    this.map.setView([latitude, longitude], 12);
                    
                    // Ajouter un marqueur pour la position de l'utilisateur
                    this.addUserMarker(latitude, longitude);
                    
                    // Charger les lieux autour de la position
                    this.loadPlaces();
                    
                    // Restaurer le bouton
                    locateBtn.innerHTML = originalHTML;
                    locateBtn.disabled = false;
                },
                (error) => {
                    console.error('Erreur de géolocalisation:', error);
                    let errorMessage = 'Impossible de vous localiser.';
                    
                    switch(error.code) {
                        case error.PERMISSION_DENIED:
                            errorMessage = 'Autorisation de géolocalisation refusée. Veuillez autoriser l\'accès à votre position.';
                            break;
                        case error.POSITION_UNAVAILABLE:
                            errorMessage = 'Informations de localisation non disponibles.';
                            break;
                        case error.TIMEOUT:
                            errorMessage = 'La demande de localisation a expiré.';
                            break;
                    }
                    
                    alert(errorMessage);
                    
                    // Restaurer le bouton
                    locateBtn.innerHTML = originalHTML;
                    locateBtn.disabled = false;
                },
                {
                    enableHighAccuracy: true,
                    timeout: 15000,
                    maximumAge: 0
                }
            );
        }
        
        addUserMarker(lat, lng) {
            // Supprimer l'ancien marqueur s'il existe
            if (this.userMarker) {
                this.userMarker.remove();
            }
            
            // Créer un marqueur personnalisé pour l'utilisateur
            const userIcon = L.divIcon({
                className: 'custom-marker',
                html: `
                    <div class="user-marker-icon">
                        <i class="fas fa-user"></i>
                    </div>
                `,
                iconSize: [50, 50],
                iconAnchor: [25, 50],
                popupAnchor: [0, -50]
            });
            
            // Ajouter le marqueur
            this.userMarker = L.marker([lat, lng], { 
                icon: userIcon,
                title: 'Votre position'
            }).addTo(this.map);
            
            // Ajouter une popup
            this.userMarker.bindPopup(`
                <div style="text-align:center; padding:10px;">
                    <h4 style="margin:0 0 10px 0; color:#4299e1;">
                        <i class="fas fa-user"></i> Votre position
                    </h4>
                    <p style="margin:0; font-size:14px; color:#666;">
                        Latitude: ${lat.toFixed(6)}<br>
                        Longitude: ${lng.toFixed(6)}
                    </p>
                </div>
            `).openPopup();
            
            // Animer le marqueur
            this.animateUserMarker();
        }
        
        animateUserMarker() {
            if (!this.userMarker) return;
            
            const markerElement = this.userMarker.getElement();
            if (markerElement) {
                const userIcon = markerElement.querySelector('.user-marker-icon');
                if (userIcon) {
                    // Ajouter une animation de pulsation
                    userIcon.style.animation = 'userMarkerPulse 2s infinite';
                }
            }
        }
        
        closeModal() {
            // Détruire Swiper avant de fermer
            if (this.swiper && this.swiper.destroy) {
                try {
                    this.swiper.destroy(true, true);
                } catch (error) {
                    console.warn('Error destroying Swiper:', error);
                }
                this.swiper = null;
            }
            
            const modal = document.getElementById('place-modal');
            if (modal) {
                modal.style.display = 'none';
            }
            
            this.activePlace = null;
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
    
    // Initialisation
    document.addEventListener('DOMContentLoaded', () => {
        try {
            window.mapApp = new InteractiveMap();
            console.log('Application carte interactive prête');
        } catch (error) {
            console.error('Erreur fatale:', error);
            alert('Erreur lors du chargement de l\'application. Veuillez recharger la page.');
        }
    });
</script>
<script>
    // Gestion des onglets avec liens
document.addEventListener('DOMContentLoaded', function() {
    const tabLinks = document.querySelectorAll('.tab-link');
    const tabPanes = document.querySelectorAll('.tab-pane');
    
    // Fonction pour changer d'onglet
    function switchTab(tabId) {
        // Retirer la classe active de tous les liens
        tabLinks.forEach(link => link.classList.remove('active'));
        // Ajouter la classe active au lien cliqué
        document.querySelector(`.tab-link[data-tab="${tabId}"]`).classList.add('active');
        
        // Masquer tous les panneaux
        tabPanes.forEach(pane => pane.classList.remove('active'));
        // Afficher le panneau correspondant
        document.getElementById(`tab-${tabId}`).classList.add('active');
        
        // Mettre à jour l'URL hash sans déclencher le scroll
        history.pushState(null, null, `#${tabId}`);
    }
    
    // Gérer les clics sur les liens d'onglets
    tabLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const tabId = this.getAttribute('data-tab');
            switchTab(tabId);
        });
    });
    
    // Vérifier l'URL hash au chargement
    const hash = window.location.hash.replace('#', '');
    const validTabs = ['carte', 'info', 'generale'];
    
    if (validTabs.includes(hash)) {
        switchTab(hash);
    }
    
    // Gestion des paramètres
    const darkModeToggle = document.getElementById('darkModeToggle');
    if (darkModeToggle) {
        darkModeToggle.addEventListener('change', function() {
            document.body.classList.toggle('dark-mode', this.checked);
            localStorage.setItem('darkMode', this.checked);
        });
        
        // Charger la préférence mode sombre
        const darkMode = localStorage.getItem('darkMode') === 'true';
        darkModeToggle.checked = darkMode;
        if (darkMode) {
            document.body.classList.add('dark-mode');
        }
    }
    
    // Gestion de la date de mise à jour
    const lastUpdateDate = document.getElementById('lastUpdateDate');
    if (lastUpdateDate) {
        lastUpdateDate.textContent = new Date().toLocaleDateString('fr-FR', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric'
        });
    }
    
    // Gestion des données
    const clearCacheBtn = document.getElementById('clearCacheBtn');
    if (clearCacheBtn) {
        clearCacheBtn.addEventListener('click', function() {
            if (confirm('Êtes-vous sûr de vouloir vider le cache ? Toutes vos préférences seront réinitialisées.')) {
                localStorage.clear();
                alert('Cache vidé avec succès ! La page va être rechargée.');
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            }
        });
    }
    
    const refreshDataBtn = document.getElementById('refreshDataBtn');
    if (refreshDataBtn) {
        refreshDataBtn.addEventListener('click', function() {
            if (confirm('Actualiser les données ?')) {
                // Simuler le chargement des données
                const btn = this;
                const originalText = btn.innerHTML;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Actualisation...';
                btn.disabled = true;
                
                setTimeout(() => {
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                    alert('Données actualisées avec succès !');
                }, 2000);
            }
        });
    }
    
    // Sauvegarder les paramètres
    const defaultRadius = document.getElementById('defaultRadius');
    const autoLocationToggle = document.getElementById('autoLocationToggle');
    const mapStyleSelect = document.getElementById('mapStyleSelect');
    
    if (defaultRadius) {
        defaultRadius.addEventListener('change', function() {
            localStorage.setItem('defaultRadius', this.value);
        });
        
        const savedRadius = localStorage.getItem('defaultRadius');
        if (savedRadius) {
            defaultRadius.value = savedRadius;
        }
    }
    
    if (autoLocationToggle) {
        autoLocationToggle.addEventListener('change', function() {
            localStorage.setItem('autoLocation', this.checked);
        });
        
        const savedAutoLocation = localStorage.getItem('autoLocation') === 'true';
        autoLocationToggle.checked = savedAutoLocation;
    }
    
    if (mapStyleSelect) {
        mapStyleSelect.addEventListener('change', function() {
            localStorage.setItem('mapStyle', this.value);
        });
        
        const savedMapStyle = localStorage.getItem('mapStyle') || 'streets';
        mapStyleSelect.value = savedMapStyle;
    }
});
</script>
</body>
</html>