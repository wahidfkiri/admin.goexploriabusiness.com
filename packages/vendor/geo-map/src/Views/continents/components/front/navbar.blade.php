<link rel="stylesheet" href="{{ asset('front/css/main.css') }}">

<header id="header" data-transparent="true" data-fullwidth="true" class="dark submenu-light">
    <div class="header-inner">
        <div class="container">
            <!--Logo avec texte qui change-->
            <div id="logo" style="position: relative; display: inline-block;top:30px;">
                <a href="{{url('/')}}">
                    <img src="{{ asset('logo.png') }}" class="d-block">
                </a>
                <!-- Texte qui change en bas à droite -->
                <div id="logo-text" style="
                    position: absolute;
                    top: 20px;
                    right: 5px;
                    font-weight: bold;
                    font-style: italic;
                    font-size: 15px;
                    color: red;
                    /* background: rgba(0,0,0,0.7); */
                    padding: 2px 6px;
                    border-radius: 3px;
                    white-space: nowrap;
                ">{{\App\Models\Menu::firstOrFail()->title}}</div>
            </div>
            <!--End: Logo-->
            
            <!-- Search -->
            <div id="search">
                <a id="btn-search-close" class="btn-search-close" aria-label="Close search form">
                    <i class="icon-x"></i>
                </a>
                <form class="search-form" action="search-results-page.html" method="get">
                    <input class="form-control" name="q" type="text" placeholder="Type & Search...">
                    <span class="text-muted">Start typing & press "Enter" or "ESC" to close</span>
                </form>
            </div>
            <!-- end: search -->
            
            <!--Header Extras-->
            <!-- <div class="header-extras">
                <ul>
                    <li>
                        <a id="btn-search" href="#"><i class="icon-search"></i></a>
                    </li>
                    <li>
                        <div class="p-dropdown">
                            <a href="##"><i class="icon-globe"></i><span>EN</span></a>
                            <ul class="p-dropdown-content">
                                <li><a href="##">French</a></li>
                                <li><a href="##">Spanish</a></li>
                                <li><a href="##">English</a></li>
                            </ul>
                        </div>
                    </li>
                </ul>
            </div> -->
            <!--end: Header Extras-->
            
            <!--Navigation Responsive Trigger-->
            <div id="mainMenu-trigger">
                <a class="lines-button x"><span class="lines"></span></a>
            </div>
            
            {{-- resources/views/components/mega-menu.blade.php --}}
@php
    use App\Helpers\MenuContinent;
@endphp

{!! MenuContinent::renderMenuContinent($continentId) !!}
            <!--end: Navigation Responsive Trigger g-->

<div id="mainMenu" style="display:none;">
    <div class="container">
        <nav>
            <ul>
                <!-- 1️⃣ Destinations -->
                <li class="dropdown mega-menu-item">
                    <a href="##">🌍 Destinations</a>
                    <ul class="dropdown-menu">
                        <li class="mega-menu-content">
                            <div class="row">
                                <!-- Continents -->
                                <div class="col-lg-4">
                                    <ul>
                                        <li class="mega-menu-title">🌍 Continents</li>
                                        <li><a href="#continent/europe">Europe</a></li>
                                        <li><a href="#continent/afrique">Afrique</a></li>
                                        <li><a href="#continent/amerique">Amérique</a></li>
                                        <li><a href="#continent/asie">Asie</a></li>
                                        <li><a href="#continent/oceanie">Océanie</a></li>
                                    </ul>
                                </div>
                                <!-- Régions & Villes -->
                                <div class="col-lg-4">
                                    <ul>
                                        <li class="mega-menu-title">📍 Régions & Villes</li>
                                        <li><a href="#destinations/villes">Grandes villes (Top 50)</a></li>
                                        <li><a href="#destinations/regions">Régions touristiques</a></li>
                                        <li><a href="#destinations/quartiers">Quartiers populaires</a></li>
                                    </ul>
                                </div>
                                <!-- Types de destinations -->
                                <div class="col-lg-4">
                                    <ul>
                                        <li class="mega-menu-title">🏖️ Types de destinations</li>
                                        <li><a href="#type/bord-de-mer">Bord de mer</a></li>
                                        <li><a href="#type/montagne">Montagne</a></li>
                                        <li><a href="#type/desert">Désert</a></li>
                                        <li><a href="#type/nature">Nature & Parcs</a></li>
                                        <li><a href="#type/urbain">Urbain & City break</a></li>
                                    </ul>
                                </div>
                            </div>
                        </li>
                    </ul>
                </li>

                <!-- 2️⃣ Business -->
                <li class="dropdown mega-menu-item">
                    <a href="##">🏢 Business</a>
                    <ul class="dropdown-menu">
                        <li class="mega-menu-content">
                            <div class="row">
                                <!-- Hébergement -->
                                <div class="col-lg-3">
                                    <ul>
                                        <li class="mega-menu-title">🏨 Hébergement</li>
                                        <li><a href="#business/hotels">Hôtels</a></li>
                                        <li><a href="#business/resorts">Resorts</a></li>
                                        <li><a href="#business/maisons-hotes">Maisons d'hôtes</a></li>
                                        <li><a href="#business/eco-lodges">Eco-lodges</a></li>
                                    </ul>
                                </div>
                                <!-- Restauration -->
                                <div class="col-lg-3">
                                    <ul>
                                        <li class="mega-menu-title">🍽️ Restauration</li>
                                        <li><a href="#business/restaurants">Restaurants</a></li>
                                        <li><a href="#business/cafes-bars">Cafés & Bars</a></li>
                                        <li><a href="#business/gastronomie">Gastronomie locale</a></li>
                                    </ul>
                                </div>
                                <!-- Activités & Loisirs -->
                                <div class="col-lg-3">
                                    <ul>
                                        <li class="mega-menu-title">🎒 Activités & Loisirs</li>
                                        <li><a href="#business/outdoor">Outdoor & aventure</a></li>
                                        <li><a href="#business/culture">Culture & patrimoine</a></li>
                                        <li><a href="#business/bien-etre">Bien-être & spa</a></li>
                                    </ul>
                                </div>
                                <!-- Visibilité & Marketing -->
                                <div class="col-lg-3">
                                    <ul>
                                        <li class="mega-menu-title">📢 Visibilité & Marketing</li>
                                        <li><a href="#business/seo">Mise en avant SEO</a></li>
                                        <li><a href="#business/videos">Vidéos & cartes interactives</a></li>
                                        <li><a href="#business/campagnes">Campagnes sponsorisées</a></li>
                                        <li><a href="#business/inscription" class="btn btn-primary btn-sm mt-2">Inscris ton entreprise</a></li>
                                    </ul>
                                </div>
                            </div>
                        </li>
                    </ul>
                </li>

                <!-- 3️⃣ Local -->
                <li class="dropdown mega-menu-item">
                    <a href="##">🏛️ Local</a>
                    <ul class="dropdown-menu">
                        <li class="mega-menu-content">
                            <div class="row">
                                <!-- Patrimoine & histoire -->
                                <div class="col-lg-4">
                                    <ul>
                                        <li class="mega-menu-title">🏛️ Patrimoine & histoire</li>
                                        <li><a href="#local/archeologie">Sites archéologiques</a></li>
                                        <li><a href="#local/musees">Musées</a></li>
                                        <li><a href="#local/monuments">Monuments</a></li>
                                    </ul>
                                </div>
                                <!-- Culture & art -->
                                <div class="col-lg-4">
                                    <ul>
                                        <li class="mega-menu-title">🎭 Culture & art</li>
                                        <li><a href="#local/festivals">Festivals</a></li>
                                        <li><a href="#local/art-architecture">Art & architecture</a></li>
                                        <li><a href="#local/artisanat">Artisanat local</a></li>
                                    </ul>
                                </div>
                                <!-- Expériences locales -->
                                <div class="col-lg-4">
                                    <ul>
                                        <li class="mega-menu-title">🍲 Expériences locales</li>
                                        <li><a href="#local/food-tours">Food tours</a></li>
                                        <li><a href="#local/marches">Marchés locaux</a></li>
                                        <li><a href="#local/ateliers">Ateliers & cours</a></li>
                                        <li><a href="#local/guides">Guides locaux</a></li>
                                    </ul>
                                </div>
                            </div>
                        </li>
                    </ul>
                </li>

                <!-- 4️⃣ Affaires -->
                <li class="dropdown mega-menu-item">
                    <a href="##">💼 Affaires</a>
                    <ul class="dropdown-menu">
                        <li class="mega-menu-content">
                            <div class="row">
                                <!-- Voyages d'affaires -->
                                <div class="col-lg-4">
                                    <ul>
                                        <li class="mega-menu-title">💼 Voyages d'affaires</li>
                                        <li><a href="#affaires/seminaires">Séminaires</a></li>
                                        <li><a href="#affaires/congres">Congrès</a></li>
                                        <li><a href="#affaires/coworking">Espaces coworking</a></li>
                                    </ul>
                                </div>
                                <!-- Bien-être & retraites -->
                                <div class="col-lg-4">
                                    <ul>
                                        <li class="mega-menu-title">🌿 Bien-être & retraites</li>
                                        <li><a href="#affaires/spa">Spa & thermalisme</a></li>
                                        <li><a href="#affaires/yoga">Yoga & méditation</a></li>
                                        <li><a href="#affaires/detox">Detox & santé</a></li>
                                    </ul>
                                </div>
                                <!-- Luxe & VIP -->
                                <div class="col-lg-4">
                                    <ul>
                                        <li class="mega-menu-title">💎 Luxe & VIP</li>
                                        <li><a href="#affaires/resorts-luxe">Resorts luxe</a></li>
                                        <li><a href="#affaires/villas">Villas privées</a></li>
                                        <li><a href="#affaires/concierge">Services concierge</a></li>
                                        <li><a href="#affaires/tours-prives">Tours privés / hélico</a></li>
                                    </ul>
                                </div>
                            </div>
                        </li>
                    </ul>
                </li>

                <!-- 5️⃣ Prime Time -->
                <li class="dropdown mega-menu-item">
                    <a href="##">🎢 Prime Time</a>
                    <ul class="dropdown-menu">
                        <li class="mega-menu-content">
                            <div class="row">
                                <!-- Loisirs & parcs -->
                                <div class="col-lg-4">
                                    <ul>
                                        <li class="mega-menu-title">🎢 Loisirs & parcs</li>
                                        <li><a href="#primetime/parcs-attractions">Parcs d'attractions</a></li>
                                        <li><a href="#primetime/parcs-aquatiques">Parcs aquatiques</a></li>
                                        <li><a href="#primetime/zoos">Zoos & aquariums</a></li>
                                    </ul>
                                </div>
                                <!-- Famille -->
                                <div class="col-lg-4">
                                    <ul>
                                        <li class="mega-menu-title">👨‍👩‍👧‍👦 Famille</li>
                                        <li><a href="#primetime/activites-enfants">Activités enfants</a></li>
                                        <li><a href="#primetime/hebergements-famille">Hébergements familiaux</a></li>
                                        <li><a href="#primetime/clubs-enfants">Clubs enfants</a></li>
                                    </ul>
                                </div>
                                <!-- Éducatif & interactif -->
                                <div class="col-lg-4">
                                    <ul>
                                        <li class="mega-menu-title">🎓 Éducatif & interactif</li>
                                        <li><a href="#primetime/musees-interactifs">Musées interactifs</a></li>
                                        <li><a href="#primetime/ateliers-creatifs">Ateliers créatifs</a></li>
                                        <li><a href="#primetime/science-nature">Science & nature</a></li>
                                    </ul>
                                </div>
                            </div>
                        </li>
                    </ul>
                </li>

                <!-- 6️⃣ Web TV -->
                <li class="dropdown mega-menu-item">
                    <a href="##">🎥 Web TV</a>
                    <ul class="dropdown-menu">
                        <li class="mega-menu-content">
                            <div class="row">
                                <!-- Chaînes officielles -->
                                <div class="col-lg-4">
                                    <ul>
                                        <li class="mega-menu-title">🎥 Chaînes officielles</li>
                                        <li><a href="#webtv/destinations">Vidéos par destination</a></li>
                                        <li><a href="#webtv/entreprises">Vidéos entreprises</a></li>
                                        <li><a href="#webtv/interviews">Interviews & reportages</a></li>
                                    </ul>
                                </div>
                                <!-- Live events -->
                                <div class="col-lg-4">
                                    <ul>
                                        <li class="mega-menu-title">📺 Live events & festivals</li>
                                        <li><a href="#webtv/live-events">Événements en direct</a></li>
                                        <li><a href="#webtv/festivals-live">Festivals live</a></li>
                                    </ul>
                                </div>
                                <!-- Formats -->
                                <div class="col-lg-4">
                                    <ul>
                                        <li class="mega-menu-title">📱 Formats</li>
                                        <li><a href="#webtv/carrousel">Carrousel vidéo</a></li>
                                        <li><a href="#webtv/carte-interactive">Carte interactive avec vidéo</a></li>
                                        <li><a href="#webtv/shorts">Shorts / reels</a></li>
                                    </ul>
                                </div>
                            </div>
                        </li>
                    </ul>
                </li>

                <!-- 7️⃣ Photos -->
                <li class="dropdown mega-menu-item d-none">
                    <a href="##">📸 Photos</a>
                    <ul class="dropdown-menu">
                        <li class="mega-menu-content">
                            <div class="row">
                                <!-- Photos par destination -->
                                <div class="col-lg-6">
                                    <ul>
                                        <li class="mega-menu-title">📸 Galeries photos</li>
                                        <li><a href="#photos/destinations">Photos par destination</a></li>
                                        <li><a href="#photos/paysages">Paysages & nature</a></li>
                                        <li><a href="#photos/evenements">Événements & festivals</a></li>
                                        <li><a href="#photos/food">Food & lifestyle</a></li>
                                    </ul>
                                </div>
                                <!-- Communauté -->
                                <div class="col-lg-6">
                                    <ul>
                                        <li class="mega-menu-title">🧑‍🤝‍🧑 Communauté</li>
                                        <li><a href="#photos/upload">Upload utilisateurs</a></li>
                                        <li><a href="#photos/contributeurs">Contributeurs voyageurs</a></li>
                                        <li><a href="#photos/tags">Tag par catégorie & lieu</a></li>
                                        <li><a href="#photos/partages">Likes / partages</a></li>
                                    </ul>
                                </div>
                            </div>
                        </li>
                    </ul>
                </li>

                <!-- 8️⃣ Marketplace -->
                <li class="dropdown mega-menu-item">
                    <a href="##">🛍️ Marketplace</a>
                    <ul class="dropdown-menu">
                        <li class="mega-menu-content">
                            <div class="row">
                                <!-- Produits touristiques -->
                                <div class="col-lg-6">
                                    <ul>
                                        <li class="mega-menu-title">🛍️ Produits touristiques</li>
                                        <li><a href="#marketplace/forfaits">Forfaits & expériences</a></li>
                                        <li><a href="#marketplace/cadeaux">Certificats cadeaux</a></li>
                                    </ul>
                                </div>
                                <!-- Services -->
                                <div class="col-lg-6">
                                    <ul>
                                        <li class="mega-menu-title">💼 Services</li>
                                        <li><a href="#marketplace/services-seo">Services web & SEO</a></li>
                                        <li><a href="#marketplace/plans-visibilite">Plans de visibilité</a></li>
                                    </ul>
                                </div>
                            </div>
                        </li>
                    </ul>
                </li>

                <!-- 9️⃣ Book Direct -->
                <li class="dropdown mega-menu-item d-none">
                    <a href="##">🏨 Book Direct</a>
                    <ul class="dropdown-menu">
                        <li class="mega-menu-content">
                            <div class="row">
                                <!-- Réservations -->
                                <div class="col-lg-12">
                                    <div class="row">
                                        <div class="col-lg-3">
                                            <ul>
                                                <li class="mega-menu-title">🏨 Hébergement</li>
                                                <li><a href="#book/hotels">Réserver un hôtel</a></li>
                                            </ul>
                                        </div>
                                        <div class="col-lg-3">
                                            <ul>
                                                <li class="mega-menu-title">🍽️ Restauration</li>
                                                <li><a href="#book/restaurants">Réserver un restaurant</a></li>
                                            </ul>
                                        </div>
                                        <div class="col-lg-3">
                                            <ul>
                                                <li class="mega-menu-title">🎟️ Activités</li>
                                                <li><a href="#book/activites">Réserver une activité</a></li>
                                            </ul>
                                        </div>
                                        <div class="col-lg-3">
                                            <ul>
                                                <li class="mega-menu-title">🚗 Transport</li>
                                                <li><a href="#book/transport">Transport & excursions</a></li>
                                                <li><a href="#book/evenements">Événements</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </li>

                <!-- 🔟 Plan-N--->
                <li class="dropdown mega-menu-item">
                    <a href="##">✈️ Plan-N-Go</a>
                    <ul class="dropdown-menu">
                        <li class="mega-menu-content">
                            <div class="row">
                                <!-- Voyages thématiques -->
                                <div class="col-lg-6">
                                    <ul>
                                        <li class="mega-menu-title">✈️ Voyages thématiques</li>
                                        <li><a href="#plan/aventure">Aventure</a></li>
                                        <li><a href="#plan/romantique">Romantique</a></li>
                                        <li><a href="#plan/road-trip">Road trip</a></li>
                                        <li><a href="#plan/famille">Famille</a></li>
                                        <li><a href="#plan/luxe">Luxe</a></li>
                                    </ul>
                                </div>
                                <!-- Par lieux -->
                                <div class="col-lg-6">
                                    <ul>
                                        <li class="mega-menu-title">📍 Par lieux</li>
                                        <li><a href="#plan/continents">Continents</a></li>
                                        <li><a href="#plan/pays">Pays</a></li>
                                        <li><a href="#plan/regions">Régions</a></li>
                                        <li><a href="#plan/quebec" class="text-primary fw-bold">Québec (focus SEO)</a></li>
                                    </ul>
                                </div>
                            </div>
                            <!-- Bloc IA -->
                            <div class="row mt-3 border-top pt-3">
                                <div class="col-lg-12">
                                    <ul>
                                        <li class="mega-menu-title">🤖 Assistant IA</li>
                                        <li><a href="#ia/voyages">Création de voyages</a></li>
                                        <li><a href="#ia/entreprises">Création pages entreprise</a></li>
                                        <li><a href="#ia/seo">Génération SEO</a></li>
                                        <li><a href="#ia/chatbot">Chatbot par catégorie</a></li>
                                    </ul>
                                </div>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>
    </div>
</div>
<!--end: Navigation-->
        </div>
    </div>
</header>

<script src="front/js/jquery.js"></script>
<script src="front/js/functions.js"></script>

<!-- Script pour faire changer le texte automatiquement -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const logoText = document.getElementById('logo-text');
    const texts = [
        <?php $menus = \App\Models\Menu::where('is_active', 1)->get();
        foreach($menus as $menu) {
            echo "'" . addslashes($menu->title) . "',";
        }
        ?>
    ];
    let currentIndex = 0;
    
    // Changer le texte toutes les 3 secondes
    setInterval(() => {
        currentIndex = (currentIndex + 1) % texts.length;
        logoText.textContent = texts[currentIndex];
    }, 3000);
});
</script>

<!-- Optionnel: Si vous voulez garder la structure pour l'instant -->
<!-- <script src="front/js/plugins.js"></script> -->