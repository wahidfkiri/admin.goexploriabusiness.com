{{-- resources/views/pages/show.blade.php --}}
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Métadonnées dynamiques -->
    <title>{{ $meta['title'] ?? $menu->title }}</title>
    <meta name="description" content="{{ $meta['description'] ?? '' }}">
    <meta name="keywords" content="{{ $meta['keywords'] ?? '' }}">
    <meta name="author" content="{{ $meta['author'] ?? '' }}">
    
    <!-- Open Graph -->
    <meta property="og:title" content="{{ $meta['title'] ?? $menu->title }}">
    <meta property="og:description" content="{{ $meta['description'] ?? '' }}">
    <meta property="og:image" content="{{ $meta['og_image'] ?? '' }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Styles personnalisés de la page -->
    <style>
        {{ $styles }}
        
        /* Styles par défaut */
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        
        .page-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 60px 0;
            margin-bottom: 40px;
        }
        
        .page-content {
            min-height: 400px;
        }
        
        /* Navigation breadcrumb */
        .breadcrumb-nav {
            background: #f8f9fa;
            padding: 15px 0;
            margin-bottom: 30px;
        }
        
        .edit-button {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1000;
        }
        
        @media (max-width: 768px) {
            .page-header {
                padding: 40px 0;
            }
            
            .edit-button {
                bottom: 10px;
                right: 10px;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="/">
                <i class="fas fa-globe-americas me-2"></i>TravelPlatform
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/">Accueil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/destinations">Destinations</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ $menu->page_url }}">{{ $menu->title }}</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Breadcrumb -->
    <div class="breadcrumb-nav">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="/">Accueil</a></li>
                    <li class="breadcrumb-item"><a href="/destinations">Destinations</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $menu->title }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Header de la page -->
    <header class="page-header">
        <div class="container">
            <h1 class="display-4">{{ $meta['title'] ?? $menu->title }}</h1>
            <p class="lead">{{ $meta['description'] ?? '' }}</p>
        </div>
    </header>

    <!-- Contenu principal -->
    <main class="page-content">
        <div class="container">
            <!-- Contenu dynamique depuis GrapeJS -->
            {!! $content !!}
        </div>
    </main>

    <!-- Bouton d'édition (visible seulement pour les admins) -->
    @auth
            <div class="edit-button">
                <a href="{{ route('menus.page.edit', $menu) }}" class="btn btn-primary btn-lg rounded-circle shadow-lg">
                    <i class="fas fa-edit"></i>
                </a>
            </div>
    @endauth

    <!-- Footer -->
    <footer class="bg-dark text-white py-5 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-4">
                    <h5>TravelPlatform</h5>
                    <p>Votre guide touristique pour des expériences inoubliables.</p>
                </div>
                <div class="col-lg-4">
                    <h5>Liens rapides</h5>
                    <ul class="list-unstyled">
                        <li><a href="/about" class="text-white-50">À propos</a></li>
                        <li><a href="/contact" class="text-white-50">Contact</a></li>
                        <li><a href="/privacy" class="text-white-50">Confidentialité</a></li>
                    </ul>
                </div>
                <div class="col-lg-4">
                    <h5>Suivez-nous</h5>
                    <div class="social-icons">
                        <a href="#" class="text-white me-3"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="text-white me-3"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
            </div>
            <hr class="my-4" style="border-color: #444;">
            <div class="text-center">
                <p>&copy; {{ date('Y') }} TravelPlatform. Tous droits réservés.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Scripts personnalisés -->
    <script>
        // Initialisation des composants Bootstrap
        $(document).ready(function() {
            // Activer les tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
            
            // Animation au scroll
            $(window).scroll(function() {
                $('.fade-in').each(function() {
                    var position = $(this).offset().top;
                    var scroll = $(window).scrollTop();
                    var windowHeight = $(window).height();
                    
                    if (scroll > position - windowHeight + 100) {
                        $(this).addClass('visible');
                    }
                });
            });
            
            // Initialiser les animations
            $('.fade-in').addClass('hidden');
            $(window).scroll();
        });
    </script>
</body>
</html>