<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Winter Collection | Mode d'Hiver Complète</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&family=Raleway:wght@300;400;500;600&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <style>
        /* Variables CSS - Thème Mode Hiver */
        :root {
            --winter-blue: #2C3E50;
            --ice-blue: #ECF0F1;
            --frost-white: #F8F9FA;
            --silver: #BDC3C7;
            --icy-teal: #1ABC9C;
            --cold-purple: #9B59B6;
            --warm-gray: #7F8C8D;
            --burgundy: #8B0000;
            --navy: #000080;
            --snow-shadow: 0 15px 35px rgba(44, 62, 80, 0.1);
            --frost-shadow: 0 8px 25px rgba(189, 195, 199, 0.2);
            --glow-shadow: 0 0 20px rgba(26, 188, 156, 0.15);
            --radius-lg: 20px;
            --radius-md: 12px;
            --radius-sm: 8px;
            --transition-smooth: 0.5s cubic-bezier(0.23, 1, 0.32, 1);
            --transition-bounce: 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        }

        /* Reset et styles globaux */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Raleway', sans-serif;
            background: linear-gradient(135deg, var(--frost-white) 0%, #E8F4F8 100%);
            color: var(--winter-blue);
            line-height: 1.6;
            min-height: 100vh;
            padding: 20px;
            overflow-x: hidden;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* En-tête avec effet de neige */
        .fashion-header {
            text-align: center;
            padding: 80px 0 60px;
            position: relative;
            overflow: hidden;
        }

        .snow-effect {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: -1;
        }

        .snowflake {
            position: absolute;
            background: white;
            border-radius: 50%;
            opacity: 0.8;
            animation: snowfall linear infinite;
        }

        @keyframes snowfall {
            0% { transform: translateY(-10px) rotate(0deg); }
            100% { transform: translateY(100vh) rotate(360deg); }
        }

        .header-content {
            position: relative;
            z-index: 2;
        }

        .season-badge {
            display: inline-block;
            background: linear-gradient(135deg, var(--icy-teal), var(--cold-purple));
            color: white;
            padding: 12px 28px;
            border-radius: 50px;
            font-weight: 600;
            letter-spacing: 2px;
            margin-bottom: 25px;
            text-transform: uppercase;
            font-size: 0.9rem;
            box-shadow: var(--glow-shadow);
            transform: translateY(-20px);
            opacity: 0;
            animation: badge-float 1s 0.3s forwards;
        }

        @keyframes badge-float {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .main-title {
            font-family: 'Montserrat', sans-serif;
            font-size: 3.8rem;
            font-weight: 700;
            background: linear-gradient(135deg, var(--winter-blue), #4A6572);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 20px;
            opacity: 0;
            transform: translateY(30px);
            animation: title-appear 1s 0.5s forwards;
        }

        @keyframes title-appear {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .header-subtitle {
            font-size: 1.3rem;
            color: var(--warm-gray);
            max-width: 700px;
            margin: 0 auto 40px;
            opacity: 0;
            animation: fade-up 1s 0.7s forwards;
        }

        @keyframes fade-up {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Navigation des catégories */
        .category-nav {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 60px;
            opacity: 0;
            animation: fade-up 1s 0.9s forwards;
        }

        .category-btn {
            padding: 16px 32px;
            background: white;
            border: 2px solid transparent;
            border-radius: var(--radius-md);
            font-family: 'Montserrat', sans-serif;
            font-weight: 600;
            color: var(--winter-blue);
            cursor: pointer;
            transition: var(--transition-bounce);
            position: relative;
            overflow: hidden;
            box-shadow: var(--frost-shadow);
        }

        .category-btn::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            background: rgba(26, 188, 156, 0.1);
            border-radius: 50%;
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .category-btn:hover::before {
            width: 300px;
            height: 300px;
        }

        .category-btn:hover {
            transform: translateY(-8px);
            border-color: var(--icy-teal);
            box-shadow: var(--snow-shadow);
        }

        .category-btn.active {
            background: var(--winter-blue);
            color: white;
            border-color: var(--winter-blue);
        }

        .category-btn i {
            margin-right: 10px;
            font-size: 1.1rem;
        }

        /* Grille de produits */
        .products-section {
            margin-bottom: 80px;
            opacity: 0;
            animation: fade-up 1s 1.1s forwards;
        }

        .section-title {
            font-family: 'Montserrat', sans-serif;
            font-size: 2.2rem;
            font-weight: 700;
            color: var(--winter-blue);
            margin-bottom: 40px;
            text-align: center;
            position: relative;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: -15px;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 4px;
            background: linear-gradient(90deg, var(--icy-teal), var(--cold-purple));
            border-radius: 2px;
        }

        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 30px;
        }

        .product-card {
            background: white;
            border-radius: var(--radius-lg);
            overflow: hidden;
            box-shadow: var(--frost-shadow);
            transition: var(--transition-bounce);
            position: relative;
            cursor: pointer;
        }

        .product-card:hover {
            transform: translateY(-15px);
            box-shadow: var(--snow-shadow);
        }

        .product-card-badge {
            position: absolute;
            top: 15px;
            left: 15px;
            padding: 6px 14px;
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: 600;
            z-index: 2;
            color: white;
        }

        .product-card-badge.new {
            background: var(--icy-teal);
        }

        .product-card-badge.sale {
            background: var(--burgundy);
        }

        .product-card-badge.limited {
            background: var(--cold-purple);
        }

        .product-card-image {
            height: 250px;
            overflow: hidden;
            position: relative;
        }

        .product-card-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 6s ease;
        }

        .product-card:hover .product-card-image img {
            transform: scale(1.1);
        }

        .quick-view-btn {
            position: absolute;
            bottom: -50px;
            left: 50%;
            transform: translateX(-50%);
            background: white;
            border: none;
            padding: 12px 24px;
            border-radius: 50px;
            font-weight: 600;
            color: var(--winter-blue);
            cursor: pointer;
            transition: var(--transition-smooth);
            box-shadow: var(--frost-shadow);
            z-index: 2;
            opacity: 0;
        }

        .product-card:hover .quick-view-btn {
            bottom: 20px;
            opacity: 1;
        }

        .quick-view-btn:hover {
            background: var(--winter-blue);
            color: white;
            transform: translateX(-50%) scale(1.05);
        }

        .product-card-content {
            padding: 25px;
        }

        .product-card-title {
            font-family: 'Montserrat', sans-serif;
            font-weight: 600;
            font-size: 1.2rem;
            margin-bottom: 10px;
            color: var(--winter-blue);
        }

        .product-card-price {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 15px;
        }

        .current-price {
            font-weight: 700;
            font-size: 1.5rem;
            color: var(--icy-teal);
        }

        .original-price {
            font-size: 1.1rem;
            color: var(--silver);
            text-decoration: line-through;
        }

        .product-card-colors {
            display: flex;
            gap: 8px;
            margin-bottom: 15px;
        }

        .color-dot {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            border: 2px solid white;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            cursor: pointer;
            transition: transform 0.3s;
        }

        .color-dot:hover {
            transform: scale(1.2);
        }

        .product-card-sizes {
            display: flex;
            gap: 5px;
            flex-wrap: wrap;
        }

        .size-chip {
            padding: 5px 10px;
            background: var(--ice-blue);
            border-radius: 4px;
            font-size: 0.8rem;
            color: var(--winter-blue);
        }

        /* Footer */
        .fashion-footer {
            text-align: center;
            padding: 40px 0;
            color: var(--warm-gray);
            border-top: 1px solid var(--ice-blue);
            opacity: 0;
            animation: fade-up 1s 1.5s forwards;
        }

        .footer-links {
            display: flex;
            justify-content: center;
            gap: 30px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .footer-link {
            color: var(--winter-blue);
            text-decoration: none;
            font-weight: 500;
            transition: var(--transition-smooth);
            position: relative;
        }

        .footer-link::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--icy-teal);
            transition: width 0.3s;
        }

        .footer-link:hover::after {
            width: 100%;
        }

        /* Modal de produit */
        .product-modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(44, 62, 80, 0.95);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 1000;
            padding: 20px;
            opacity: 0;
            transition: opacity 0.4s;
        }

        .product-modal-overlay.active {
            display: flex;
            opacity: 1;
        }

        .product-modal {
            background: white;
            border-radius: var(--radius-lg);
            width: 100%;
            max-width: 1100px;
            max-height: 90vh;
            overflow-y: auto;
            display: none;
            transform: translateY(50px);
            opacity: 0;
            transition: transform 0.5s, opacity 0.5s;
            box-shadow: 0 30px 80px rgba(0, 0, 0, 0.4);
        }

        .product-modal.active {
            display: block;
            transform: translateY(0);
            opacity: 1;
        }

        .modal-close {
            position: absolute;
            top: 25px;
            right: 25px;
            background: white;
            border: none;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            color: var(--winter-blue);
            cursor: pointer;
            z-index: 10;
            transition: var(--transition-smooth);
            box-shadow: var(--frost-shadow);
        }

        .modal-close:hover {
            background: var(--winter-blue);
            color: white;
            transform: rotate(90deg);
        }

        .modal-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 50px;
            padding: 40px;
        }

        @media (max-width: 992px) {
            .modal-content {
                grid-template-columns: 1fr;
                gap: 30px;
            }
        }

        /* Galerie dans la modal */
        .modal-gallery {
            position: relative;
        }

        .modal-main-image {
            height: 450px;
            border-radius: var(--radius-md);
            overflow: hidden;
            margin-bottom: 20px;
        }

        .modal-main-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 8s ease;
        }

        .modal-main-image:hover img {
            transform: scale(1.05);
        }

        .modal-thumbnails {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .modal-thumbnail {
            width: 70px;
            height: 70px;
            border-radius: var(--radius-sm);
            overflow: hidden;
            cursor: pointer;
            border: 3px solid transparent;
            transition: var(--transition-smooth);
            opacity: 0.7;
        }

        .modal-thumbnail:hover {
            opacity: 1;
            transform: translateY(-3px);
        }

        .modal-thumbnail.active {
            border-color: var(--icy-teal);
            opacity: 1;
        }

        .modal-thumbnail img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Détails dans la modal */
        .modal-details {
            padding: 20px 0;
        }

        .modal-title {
            font-family: 'Montserrat', sans-serif;
            font-size: 2.2rem;
            font-weight: 700;
            color: var(--winter-blue);
            margin-bottom: 15px;
        }

        .modal-category {
            display: inline-block;
            background: var(--ice-blue);
            color: var(--winter-blue);
            padding: 6px 15px;
            border-radius: 50px;
            font-size: 0.9rem;
            margin-bottom: 20px;
            font-weight: 600;
        }

        .modal-price {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 25px;
        }

        .modal-current-price {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--icy-teal);
        }

        .modal-original-price {
            font-size: 1.8rem;
            color: var(--silver);
            text-decoration: line-through;
        }

        .modal-description {
            color: var(--warm-gray);
            margin-bottom: 30px;
            line-height: 1.8;
            font-size: 1.1rem;
        }

        /* Variantes dans la modal */
        .modal-variant-section {
            margin-bottom: 25px;
        }

        .modal-variant-title {
            font-family: 'Montserrat', sans-serif;
            font-weight: 600;
            margin-bottom: 15px;
            color: var(--winter-blue);
            font-size: 1.1rem;
        }

        .modal-variant-options {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .modal-variant-option {
            padding: 12px 20px;
            border: 2px solid var(--ice-blue);
            border-radius: var(--radius-md);
            background: white;
            cursor: pointer;
            transition: var(--transition-smooth);
            font-weight: 500;
        }

        .modal-variant-option:hover {
            border-color: var(--icy-teal);
            transform: translateY(-3px);
        }

        .modal-variant-option.selected {
            background: var(--winter-blue);
            color: white;
            border-color: var(--winter-blue);
        }

        .modal-color-option {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: 3px solid transparent;
            cursor: pointer;
            transition: var(--transition-smooth);
        }

        .modal-color-option:hover {
            transform: scale(1.15);
        }

        .modal-color-option.selected {
            border-color: var(--winter-blue);
            transform: scale(1.15);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        /* Actions dans la modal */
        .modal-actions {
            display: flex;
            gap: 15px;
            margin-top: 40px;
        }

        .modal-quantity {
            display: flex;
            align-items: center;
            border: 2px solid var(--ice-blue);
            border-radius: var(--radius-md);
            overflow: hidden;
        }

        .modal-quantity-btn {
            width: 50px;
            height: 50px;
            background: white;
            border: none;
            font-size: 1.3rem;
            color: var(--winter-blue);
            cursor: pointer;
            transition: var(--transition-smooth);
        }

        .modal-quantity-btn:hover {
            background: var(--ice-blue);
        }

        .modal-quantity-input {
            width: 60px;
            height: 50px;
            border: none;
            text-align: center;
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--winter-blue);
        }

        .modal-add-to-cart {
            flex: 1;
            padding: 0 30px;
            background: linear-gradient(135deg, var(--icy-teal), #16A085);
            color: white;
            border: none;
            border-radius: var(--radius-md);
            font-family: 'Montserrat', sans-serif;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: var(--transition-bounce);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
        }

        .modal-add-to-cart:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(26, 188, 156, 0.3);
        }

        .modal-wishlist-btn {
            width: 50px;
            height: 50px;
            background: white;
            border: 2px solid var(--ice-blue);
            border-radius: var(--radius-md);
            color: var(--winter-blue);
            cursor: pointer;
            transition: var(--transition-bounce);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
        }

        .modal-wishlist-btn:hover {
            background: #FF6B6B;
            border-color: #FF6B6B;
            color: white;
            transform: scale(1.1);
        }

        .modal-wishlist-btn.active {
            background: #FF6B6B;
            border-color: #FF6B6B;
            color: white;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .main-title {
                font-size: 2.8rem;
            }
            
            .products-grid {
                grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            }
            
            .modal-content {
                padding: 25px;
            }
            
            .modal-main-image {
                height: 350px;
            }
            
            .modal-title {
                font-size: 1.8rem;
            }
            
            .modal-current-price {
                font-size: 2rem;
            }
            
            .category-nav {
                gap: 10px;
            }
            
            .category-btn {
                padding: 12px 20px;
                font-size: 0.9rem;
            }
        }

        @media (max-width: 480px) {
            .main-title {
                font-size: 2.2rem;
            }
            
            .header-subtitle {
                font-size: 1.1rem;
            }
            
            .products-grid {
                grid-template-columns: 1fr;
            }
            
            .modal-actions {
                flex-direction: column;
            }
            
            .modal-add-to-cart {
                padding: 15px;
            }
            
            .fashion-header {
                padding: 60px 0 40px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- En-tête avec effet de neige -->
        <header class="fashion-header">
            <div class="snow-effect" id="snow-container"></div>
            <div class="header-content">
                <div class="season-badge">Collection Hiver 2023</div>
                <h1 class="main-title">Élégance Hivernale</h1>
                <p class="header-subtitle">Découvrez notre collection exclusive de vêtements et accessoires d'hiver, alliant confort, style et chaleur pour affronter la saison froide avec élégance.</p>
            </div>
        </header>

        <!-- Navigation des catégories -->
        <nav class="category-nav">
            <button class="category-btn active" data-category="all">
                <i class="fas fa-snowflake"></i> Tous
            </button>
            <button class="category-btn" data-category="outerwear">
                <i class="fas fa-vest"></i> Vêtements chauds
            </button>
            <button class="category-btn" data-category="knitwear">
                <i class="fas fa-tshirt"></i> Pulls & Gilets
            </button>
            <button class="category-btn" data-category="accessories">
                <i class="fas fa-mitten"></i> Accessoires
            </button>
            <button class="category-btn" data-category="footwear">
                <i class="fas fa-boot"></i> Chaussures
            </button>
            <button class="category-btn" data-category="baselayers">
                <i class="fas fa-thermometer"></i> Sous-vêtements
            </button>
        </nav>

        <!-- Section Tous les produits -->
        <section class="products-section" id="all-products">
            <h2 class="section-title">Tous nos produits d'hiver</h2>
            <div class="products-grid" id="products-container">
                <!-- Les produits seront générés par JavaScript -->
            </div>
        </section>

        <!-- Footer -->
        <footer class="fashion-footer">
            <div class="footer-links">
                <a href="#" class="footer-link">Nouveautés</a>
                <a href="#" class="footer-link">Homme</a>
                <a href="#" class="footer-link">Femme</a>
                <a href="#" class="footer-link">Enfant</a>
                <a href="#" class="footer-link">Guide des tailles</a>
                <a href="#" class="footer-link">Livraison & Retours</a>
            </div>
            <p>© 2023 Winter Collection. Tous droits réservés.</p>
            <p style="font-size: 0.9rem; margin-top: 10px; color: #95A5A6;">Images réelles de notre collection hiver</p>
        </footer>
    </div>

    <!-- Modal de produit -->
    <div class="product-modal-overlay" id="product-modal-overlay">
        <div class="product-modal" id="product-modal">
            <button class="modal-close" id="modal-close">
                <i class="fas fa-times"></i>
            </button>
            <div class="modal-content" id="modal-content">
                <!-- Le contenu sera rempli par JavaScript -->
            </div>
        </div>
    </div>

    <script>
        // Données complètes des produits par catégorie
        const winterProducts = {
            outerwear: [
                {
                    id: 1,
                    name: "Manteau Long Himalaya",
                    category: "outerwear",
                    price: "189,99 €",
                    originalPrice: "249,99 €",
                    description: "Manteau d'hiver long en laine mérinos premium avec isolation thermique avancée. Conçu pour résister aux températures glaciales tout en conservant un style élégant et moderne. Capuche amovible avec fourrure synthétique et multiples poches intérieures.",
                    images: [
                        "https://images.unsplash.com/photo-1552374196-1ab2a1c593e8?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1200&q=80",
                        "https://images.unsplash.com/photo-1591047139829-d91aecb6caea?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1200&q=80",
                        "https://images.unsplash.com/photo-1544441893-675973e31985?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1200&q=80",
                        "https://images.unsplash.com/photo-1576871337632-b9aef4c17ab9?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1200&q=80"
                    ],
                    colors: [
                        {name: "Noir", hex: "#2C3E50"},
                        {name: "Blanc", hex: "#ECF0F1"},
                        {name: "Gris", hex: "#7F8C8D"},
                        {name: "Bleu Nuit", hex: "#34495E"},
                        {name: "Bordeaux", hex: "#8B0000"}
                    ],
                    sizes: ["XS", "S", "M", "L", "XL", "XXL"],
                    badge: "new",
                    material: "Laine mérinos 80%, Polyamide 20%",
                    features: ["Isolation thermique", "Capuche amovible", "Poches intérieures", "Imperméable"]
                },
                {
                    id: 2,
                    name: "Veste Polaire Technique",
                    category: "outerwear",
                    price: "89,99 €",
                    originalPrice: "119,99 €",
                    description: "Veste polaire technique légère et respirante, idéale pour les activités en plein air. Conçue avec une technologie d'évacuation de l'humidité pour garder au sec et au chaud.",
                    images: [
                        "https://images.unsplash.com/photo-1551488831-00ddcb6c6bd3?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1200&q=80",
                        "https://images.unsplash.com/photo-1539533017449-7508c6c9e8e3?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1200&q=80",
                        "https://images.unsplash.com/photo-1523381210434-271e8be1f52b?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1200&q=80"
                    ],
                    colors: [
                        {name: "Bleu Ardoise", hex: "#2C3E50"},
                        {name: "Vert Sapin", hex: "#1ABC9C"},
                        {name: "Rouge", hex: "#E74C3C"},
                        {name: "Noir", hex: "#000000"}
                    ],
                    sizes: ["S", "M", "L", "XL"],
                    badge: "sale",
                    material: "Polyester recyclé 100%",
                    features: ["Respirante", "Légère", "Poches zippées", "Coupe ajustée"]
                },
                {
                    id: 3,
                    name: "Doudoune Premium Quilted",
                    category: "outerwear",
                    price: "229,99 €",
                    originalPrice: "299,99 €",
                    description: "Doudoune premium avec motif quilted et col en fausse fourrure. Remplissage en duvet d'oie pour une chaleur optimale sans le poids.",
                    images: [
                        "https://images.unsplash.com/photo-1544923246-773f6b8d5e6a?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1200&q=80",
                        "https://images.unsplash.com/photo-1551028719-00167b16eac5?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1200&q=80"
                    ],
                    colors: [
                        {name: "Crème", hex: "#F5F5DC"},
                        {name: "Noir", hex: "#000000"},
                        {name: "Bordeaux", hex: "#8B0000"},
                        {name: "Kaki", hex: "#556B2F"}
                    ],
                    sizes: ["XS", "S", "M", "L", "XL"],
                    badge: "new",
                    material: "Nylon 90%, Duvet d'oie 10%",
                    features: ["Remplissage duvet", "Col fourrure", "Motif quilted", "Coupe cintrée"]
                }
            ],
            knitwear: [
                {
                    id: 4,
                    name: "Pull en Cachemire Glacier",
                    category: "knitwear",
                    price: "129,99 €",
                    originalPrice: "169,99 €",
                    description: "Pull ultra-doux en cachemire 100% pur, offrant chaleur et légèreté exceptionnelles. Tricoté à la main avec un col roulé haut pour une protection optimale contre le froid.",
                    images: [
                        "https://images.unsplash.com/photo-1576566588028-4147f3842f27?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1200&q=80",
                        "https://images.unsplash.com/photo-1574180045827-681f8a1a9622?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1200&q=80"
                    ],
                    colors: [
                        {name: "Gris Chiné", hex: "#7F8C8D"},
                        {name: "Crème", hex: "#F5F5DC"},
                        {name: "Bordeaux", hex: "#8B0000"},
                        {name: "Marine", hex: "#000080"},
                        {name: "Vert Forêt", hex: "#228B22"}
                    ],
                    sizes: ["XS", "S", "M", "L"],
                    badge: "limited",
                    material: "Cachemire 100%",
                    features: ["Tricoté à la main", "Col roulé", "Ultra doux", "Léger"]
                },
                {
                    id: 5,
                    name: "Gilet en Laine Alpaga",
                    category: "knitwear",
                    price: "99,99 €",
                    originalPrice: "129,99 €",
                    description: "Gilet sans manches en laine d'alpaga, parfait pour superposer par temps froid. Texture douce et chaude avec un motif côtelé élégant.",
                    images: [
                        "https://images.unsplash.com/photo-1594633312681-425c7b97ccd1?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1200&q=80",
                        "https://images.unsplash.com/photo-1576871337632-b9aef4c17ab9?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1200&q=80"
                    ],
                    colors: [
                        {name: "Taupe", hex: "#8B7355"},
                        {name: "Gris", hex: "#708090"},
                        {name: "Bleu Pétrole", hex: "#2F4F4F"},
                        {name: "Brun", hex: "#8B4513"}
                    ],
                    sizes: ["S", "M", "L", "XL"],
                    badge: "sale",
                    material: "Laine alpaga 80%, Laine mérinos 20%",
                    features: ["Sans manches", "Motif côtelé", "Superposition", "Élégant"]
                }
            ],
            accessories: [
                {
                    id: 6,
                    name: "Écharpe en Laine Alpaga",
                    category: "accessories",
                    price: "39,99 €",
                    originalPrice: "59,99 €",
                    description: "Longue écharpe en laine d'alpaga ultra douce, tissée à la main avec des franges. Assez longue pour être enroulée plusieurs fois autour du cou.",
                    images: [
                        "https://images.unsplash.com/photo-1576566588028-4147f3842f27?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1200&q=80",
                        "https://images.unsplash.com/photo-1519457431-44ccd64a579b?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1200&q=80"
                    ],
                    colors: [
                        {name: "Turquoise", hex: "#1ABC9C"},
                        {name: "Violet", hex: "#9B59B6"},
                        {name: "Rouge", hex: "#E74C3C"},
                        {name: "Jaune Moutarde", hex: "#F1C40F"}
                    ],
                    sizes: ["Unique"],
                    badge: "new",
                    material: "Laine alpaga 100%",
                    features: ["Tissée à la main", "Franges", "Longue", "Ultra douce"]
                },
                {
                    id: 7,
                    name: "Gants en Cuir avec Fourrure",
                    category: "accessories",
                    price: "49,99 €",
                    originalPrice: "69,99 €",
                    description: "Gants en cuir véritable doublés de fourrure synthétique chaude. Paume renforcée pour une meilleure prise et coutures renforcées pour la durabilité.",
                    images: [
                        "https://images.unsplash.com/photo-1576871337632-b9aef4c17ab9?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1200&q=80",
                        "https://images.unsplash.com/photo-1519457431-44ccd64a579b?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1200&q=80"
                    ],
                    colors: [
                        {name: "Noir", hex: "#2C3E50"},
                        {name: "Marron", hex: "#7F8C8D"},
                        {name: "Bordeaux", hex: "#8B0000"},
                        {name: "Violet", hex: "#8E44AD"}
                    ],
                    sizes: ["S", "M", "L"],
                    badge: "sale",
                    material: "Cuir véritable 100%, Fourrure synthétique",
                    features: ["Doublure fourrure", "Paume renforcée", "Coutures renforcées", "Élastique au poignet"]
                },
                {
                    id: 8,
                    name: "Bonnet en Laine Mérinos",
                    category: "accessories",
                    price: "29,99 €",
                    originalPrice: "39,99 €",
                    description: "Bonnet ajusté en laine mérinos qui garde la tête au chaud sans provoquer de démangeaisons. Bandeau réversible avec logo brodé.",
                    images: [
                        "https://images.unsplash.com/photo-1576871337632-b9aef4c17ab9?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1200&q=80",
                        "https://images.unsplash.com/photo-1519457431-44ccd64a579b?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1200&q=80"
                    ],
                    colors: [
                        {name: "Gris Chiné", hex: "#7F8C8D"},
                        {name: "Bordeaux", hex: "#8B0000"},
                        {name: "Marine", hex: "#000080"},
                        {name: "Vert Forêt", hex: "#228B22"},
                        {name: "Violet", hex: "#9B59B6"}
                    ],
                    sizes: ["Unique"],
                    badge: "new",
                    material: "Laine mérinos 100%",
                    features: ["Ajusté", "Non-démangeant", "Bandeau réversible", "Logo brodé"]
                }
            ],
            footwear: [
                {
                    id: 9,
                    name: "Bottes d'Hiver Imperméables",
                    category: "footwear",
                    price: "159,99 €",
                    originalPrice: "199,99 €",
                    description: "Bottes imperméables avec membrane respirante et semelle antidérapante Vibram. Doublure en fourrure synthétique pour une chaleur optimale.",
                    images: [
                        "https://images.unsplash.com/photo-1543163521-1bf539c55dd2?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1200&q=80",
                        "https://images.unsplash.com/photo-1560343090-f0409e92791a?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1200&q=80"
                    ],
                    colors: [
                        {name: "Noir", hex: "#2C3E50"},
                        {name: "Marron", hex: "#8B4513"},
                        {name: "Gris", hex: "#708090"},
                        {name: "Vert Militaire", hex: "#556B2F"}
                    ],
                    sizes: ["36", "37", "38", "39", "40", "41", "42", "43"],
                    badge: "limited",
                    material: "Cuir nubuck, Membrane Gore-Tex",
                    features: ["Imperméable", "Semelle Vibram", "Doublure fourrure", "Respirant"]
                },
                {
                    id: 10,
                    name: "Chaussons en Laine Felted",
                    category: "footwear",
                    price: "44,99 €",
                    originalPrice: "59,99 €",
                    description: "Chaussons confortables en laine feutrée avec semelle antidérapante en caoutchouc. Parfaits pour l'intérieur par temps froid.",
                    images: [
                        "https://images.unsplash.com/photo-1560343090-f0409e92791a?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1200&q=80",
                        "https://images.unsplash.com/photo-1576871337632-b9aef4c17ab9?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1200&q=80"
                    ],
                    colors: [
                        {name: "Gris", hex: "#BDC3C7"},
                        {name: "Crème", hex: "#F5F5DC"},
                        {name: "Bleu Pastel", hex: "#ADD8E6"},
                        {name: "Rose Poudré", hex: "#FFB6C1"}
                    ],
                    sizes: ["36-37", "38-39", "40-41", "42-43"],
                    badge: "sale",
                    material: "Laine feutrée 100%, Semelle caoutchouc",
                    features: ["Antidérapant", "Confortable", "Chaud", "Intérieur/extérieur"]
                }
            ],
            baselayers: [
                {
                    id: 11,
                    name: "Sous-vêtement Thermique",
                    category: "baselayers",
                    price: "34,99 €",
                    originalPrice: "44,99 €",
                    description: "Sous-vêtement thermique respirant et évacuant l'humidité. Coupe ajustée pour un port confortable sous les vêtements.",
                    images: [
                        "https://images.unsplash.com/photo-1552374196-1ab2a1c593e8?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1200&q=80",
                        "https://images.unsplash.com/photo-1594633312681-425c7b97ccd1?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1200&q=80"
                    ],
                    colors: [
                        {name: "Noir", hex: "#000000"},
                        {name: "Blanc", hex: "#FFFFFF"},
                        {name: "Gris", hex: "#808080"},
                        {name: "Bleu Marine", hex: "#000080"}
                    ],
                    sizes: ["XS", "S", "M", "L", "XL"],
                    badge: "new",
                    material: "Mérinos 85%, Élasthanne 15%",
                    features: ["Respirant", "Ajusté", "Anti-odeur", "Évacuation humidité"]
                },
                {
                    id: 12,
                    name: "Collant Thermique",
                    category: "baselayers",
                    price: "24,99 €",
                    originalPrice: "34,99 €",
                    description: "Collant thermique ultra-fin pour rester au chaud sous les vêtements. Transparent et invisible sous les vêtements.",
                    images: [
                        "https://images.unsplash.com/photo-1594633312681-425c7b97ccd1?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1200&q=80",
                        "https://images.unsplash.com/photo-1576871337632-b9aef4c17ab9?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1200&q=80"
                    ],
                    colors: [
                        {name: "Naturel", hex: "#F5DEB3"},
                        {name: "Noir", hex: "#000000"},
                        {name: "Brun", hex: "#8B4513"}
                    ],
                    sizes: ["S", "M", "L", "XL"],
                    badge: "sale",
                    material: "Nylon 92%, Élasthanne 8%",
                    features: ["Ultra-fin", "Invisible", "Chaleur thermique", "Confortable"]
                }
            ]
        };

        // Initialisation
        $(document).ready(function() {
            // Générer l'effet de neige
            createSnowflakes();
            
            // Générer tous les produits initialement
            generateAllProducts();
            
            // Gestion des filtres de catégorie
            $('.category-btn').on('click', function() {
                $('.category-btn').removeClass('active');
                $(this).addClass('active');
                
                const category = $(this).data('category');
                filterProductsByCategory(category);
                
                // Animation du bouton
                $(this).css('transform', 'scale(0.95)');
                setTimeout(() => {
                    $(this).css('transform', '');
                }, 200);
            });
            
            // Gestion de la fermeture de la modal
            $('#modal-close').on('click', closeModal);
            $('#product-modal-overlay').on('click', function(e) {
                if (e.target === this) {
                    closeModal();
                }
            });
            
            // Fermer la modal avec la touche Échap
            $(document).on('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeModal();
                }
            });
        });

        // Fonction pour créer l'effet de neige
        function createSnowflakes() {
            const snowContainer = $('#snow-container');
            const snowflakeCount = 50;
            
            for (let i = 0; i < snowflakeCount; i++) {
                const snowflake = $('<div class="snowflake"></div>');
                const size = Math.random() * 5 + 3;
                const posX = Math.random() * 100;
                const duration = Math.random() * 10 + 10;
                const delay = Math.random() * 10;
                
                snowflake.css({
                    width: `${size}px`,
                    height: `${size}px`,
                    left: `${posX}%`,
                    opacity: Math.random() * 0.7 + 0.3,
                    animationDuration: `${duration}s`,
                    animationDelay: `${delay}s`
                });
                
                snowContainer.append(snowflake);
            }
        }

        // Fonction pour générer tous les produits
        function generateAllProducts() {
            const container = $('#products-container');
            container.empty();
            
            let allProducts = [];
            
            // Combiner tous les produits de toutes les catégories
            for (const category in winterProducts) {
                allProducts = allProducts.concat(winterProducts[category]);
            }
            
            // Trier par ID pour un affichage cohérent
            allProducts.sort((a, b) => a.id - b.id);
            
            // Générer les cartes de produits
            allProducts.forEach((product, index) => {
                const productCard = createProductCard(product, index);
                container.append(productCard);
            });
            
            // Ajouter les événements aux cartes
            attachProductCardEvents();
        }

        // Fonction pour créer une carte produit
        function createProductCard(product, index) {
            const colorDots = product.colors.map(color => 
                `<div class="color-dot" style="background-color: ${color.hex};"></div>`
            ).join('');
            
            const sizeChips = product.sizes.map(size => 
                `<span class="size-chip">${size}</span>`
            ).join('');
            
            const badgeClass = `product-card-badge ${product.badge}`;
            const badgeText = product.badge === 'new' ? 'Nouveau' : 
                             product.badge === 'sale' ? 'Promo' : 
                             product.badge === 'limited' ? 'Limitée' : '';
            
            const priceHtml = product.originalPrice ? 
                `<div class="product-card-price">
                    <span class="current-price">${product.price}</span>
                    <span class="original-price">${product.originalPrice}</span>
                </div>` :
                `<div class="product-card-price">
                    <span class="current-price">${product.price}</span>
                </div>`;
            
            return `
                <div class="product-card" data-id="${product.id}" data-category="${product.category}">
                    ${badgeText ? `<div class="${badgeClass}">${badgeText}</div>` : ''}
                    <div class="product-card-image">
                        <img src="${product.images[0]}" alt="${product.name}">
                        <button class="quick-view-btn" data-id="${product.id}">
                            <i class="fas fa-eye"></i> Voir le produit
                        </button>
                    </div>
                    <div class="product-card-content">
                        <h3 class="product-card-title">${product.name}</h3>
                        ${priceHtml}
                        <div class="product-card-colors">
                            ${colorDots}
                        </div>
                        <div class="product-card-sizes">
                            ${sizeChips}
                        </div>
                    </div>
                </div>
            `;
        }

        // Fonction pour attacher les événements aux cartes
        function attachProductCardEvents() {
            $('.quick-view-btn').on('click', function(e) {
                e.stopPropagation();
                const productId = parseInt($(this).data('id'));
                openProductModal(productId);
            });
            
            $('.product-card').on('click', function(e) {
                if (!$(e.target).closest('.quick-view-btn').length) {
                    const productId = parseInt($(this).data('id'));
                    openProductModal(productId);
                }
            });
        }

        // Fonction pour filtrer les produits par catégorie
        function filterProductsByCategory(category) {
            const container = $('#products-container');
            container.empty();
            
            let productsToShow;
            
            if (category === 'all') {
                // Combiner tous les produits
                productsToShow = [];
                for (const cat in winterProducts) {
                    productsToShow = productsToShow.concat(winterProducts[cat]);
                }
                productsToShow.sort((a, b) => a.id - b.id);
            } else {
                productsToShow = winterProducts[category] || [];
            }
            
            // Animation de sortie des anciens produits
            container.css('opacity', '0');
            
            setTimeout(() => {
                // Générer les nouveaux produits
                productsToShow.forEach((product, index) => {
                    const productCard = createProductCard(product, index);
                    container.append(productCard);
                });
                
                // Réattacher les événements
                attachProductCardEvents();
                
                // Animation d'entrée des nouveaux produits
                container.css('opacity', '1');
            }, 300);
        }

        // Fonction pour ouvrir la modal d'un produit
        function openProductModal(productId) {
            // Trouver le produit
            let product = null;
            for (const category in winterProducts) {
                const foundProduct = winterProducts[category].find(p => p.id === productId);
                if (foundProduct) {
                    product = foundProduct;
                    break;
                }
            }
            
            if (!product) return;
            
            // Remplir la modal avec les données du produit
            fillProductModal(product);
            
            // Afficher la modal avec animation
            $('#product-modal-overlay').addClass('active');
            setTimeout(() => {
                $('#product-modal').addClass('active');
            }, 50);
            
            // Empêcher le défilement du body
            $('body').css('overflow', 'hidden');
        }

        // Fonction pour remplir la modal avec les données du produit
        function fillProductModal(product) {
            const modalContent = $('#modal-content');
            
            // Créer la galerie d'images
            const thumbnails = product.images.map((image, index) => `
                <div class="modal-thumbnail ${index === 0 ? 'active' : ''}" data-index="${index}">
                    <img src="${image}" alt="${product.name} vue ${index + 1}">
                </div>
            `).join('');
            
            // Créer les options de couleur
            const colorOptions = product.colors.map((color, index) => `
                <div class="modal-color-option ${index === 0 ? 'selected' : ''}" 
                     style="background-color: ${color.hex};"
                     data-color="${color.name}"
                     data-hex="${color.hex}"></div>
            `).join('');
            
            // Créer les options de taille
            const sizeOptions = product.sizes.map((size, index) => `
                <div class="modal-variant-option ${index === 0 ? 'selected' : ''}" data-value="${size}">${size}</div>
            `).join('');
            
            // Créer la liste des caractéristiques
            const featuresList = product.features.map(feature => `
                <li><i class="fas fa-check"></i> ${feature}</li>
            `).join('');
            
            // Prix avec promotion si applicable
            const priceHtml = product.originalPrice ? 
                `<div class="modal-price">
                    <span class="modal-current-price">${product.price}</span>
                    <span class="modal-original-price">${product.originalPrice}</span>
                </div>` :
                `<div class="modal-price">
                    <span class="modal-current-price">${product.price}</span>
                </div>`;
            
            // HTML complet de la modal
            modalContent.html(`
                <div class="modal-gallery">
                    <div class="modal-main-image">
                        <img src="${product.images[0]}" alt="${product.name}" id="modal-main-image">
                    </div>
                    <div class="modal-thumbnails">
                        ${thumbnails}
                    </div>
                </div>
                <div class="modal-details">
                    <span class="modal-category">${getCategoryName(product.category)}</span>
                    <h2 class="modal-title">${product.name}</h2>
                    ${priceHtml}
                    <p class="modal-description">${product.description}</p>
                    
                    <div class="modal-variant-section">
                        <h3 class="modal-variant-title">Couleur</h3>
                        <div class="modal-variant-options" id="modal-color-options">
                            ${colorOptions}
                        </div>
                    </div>
                    
                    <div class="modal-variant-section">
                        <h3 class="modal-variant-title">Taille</h3>
                        <div class="modal-variant-options" id="modal-size-options">
                            ${sizeOptions}
                        </div>
                    </div>
                    
                    <div class="modal-variant-section">
                        <h3 class="modal-variant-title">Caractéristiques</h3>
                        <ul style="color: var(--warm-gray); padding-left: 20px; margin-top: 10px;">
                            ${featuresList}
                        </ul>
                        <p style="margin-top: 15px; font-size: 0.9rem;"><strong>Matériau:</strong> ${product.material}</p>
                    </div>
                    
                    <div class="modal-actions">
                        <div class="modal-quantity">
                            <button class="modal-quantity-btn minus">-</button>
                            <input type="text" class="modal-quantity-input" value="1" readonly id="modal-quantity">
                            <button class="modal-quantity-btn plus">+</button>
                        </div>
                        <button class="modal-add-to-cart" id="modal-add-to-cart">
                            <i class="fas fa-shopping-bag"></i>
                            Ajouter au panier
                        </button>
                        <button class="modal-wishlist-btn" id="modal-wishlist">
                            <i class="far fa-heart"></i>
                        </button>
                    </div>
                </div>
            `);
            
            // Attacher les événements de la modal
            attachModalEvents(product);
        }

        // Fonction pour obtenir le nom d'une catégorie
        function getCategoryName(categoryKey) {
            const categoryNames = {
                'outerwear': 'Vêtements chauds',
                'knitwear': 'Pulls & Gilets',
                'accessories': 'Accessoires',
                'footwear': 'Chaussures',
                'baselayers': 'Sous-vêtements'
            };
            return categoryNames[categoryKey] || categoryKey;
        }

        // Fonction pour attacher les événements dans la modal
        function attachModalEvents(product) {
            // Gestion des thumbnails
            $('.modal-thumbnail').on('click', function() {
                const index = $(this).data('index');
                $('.modal-thumbnail').removeClass('active');
                $(this).addClass('active');
                
                const mainImage = $('#modal-main-image');
                const newImage = product.images[index];
                
                // Animation de transition
                mainImage.css('opacity', '0');
                setTimeout(() => {
                    mainImage.attr('src', newImage);
                    mainImage.css('opacity', '1');
                }, 300);
            });
            
            // Gestion des sélections de couleur
            $('#modal-color-options .modal-color-option').on('click', function() {
                $('#modal-color-options .modal-color-option').removeClass('selected');
                $(this).addClass('selected');
                
                // Animation
                $(this).css('transform', 'scale(1.3)');
                setTimeout(() => {
                    $(this).css('transform', 'scale(1.15)');
                }, 300);
            });
            
            // Gestion des sélections de taille
            $('#modal-size-options .modal-variant-option').on('click', function() {
                $('#modal-size-options .modal-variant-option').removeClass('selected');
                $(this).addClass('selected');
                
                // Animation
                $(this).css('transform', 'scale(0.9)');
                setTimeout(() => {
                    $(this).css('transform', 'scale(1)');
                }, 200);
            });
            
            // Gestion de la quantité
            $('.modal-quantity-btn.plus').on('click', function() {
                const input = $('#modal-quantity');
                let value = parseInt(input.val());
                if (value < 10) {
                    input.val(value + 1);
                    animateQuantityChange(input);
                }
            });
            
            $('.modal-quantity-btn.minus').on('click', function() {
                const input = $('#modal-quantity');
                let value = parseInt(input.val());
                if (value > 1) {
                    input.val(value - 1);
                    animateQuantityChange(input);
                }
            });
            
            // Gestion du bouton wishlist
            $('#modal-wishlist').on('click', function() {
                $(this).toggleClass('active');
                const icon = $(this).find('i');
                
                if ($(this).hasClass('active')) {
                    icon.removeClass('far fa-heart').addClass('fas fa-heart');
                    
                    // Animation
                    $(this).css('transform', 'scale(1.3)');
                    setTimeout(() => {
                        $(this).css('transform', 'scale(1.1)');
                    }, 300);
                    
                    showNotification('Produit ajouté à votre wishlist!');
                } else {
                    icon.removeClass('fas fa-heart').addClass('far fa-heart');
                    
                    // Animation
                    $(this).css('transform', 'scale(0.9)');
                    setTimeout(() => {
                        $(this).css('transform', 'scale(1)');
                    }, 300);
                }
            });
            
            // Gestion du bouton "Ajouter au panier"
            $('#modal-add-to-cart').on('click', function() {
                const size = $('#modal-size-options .selected').data('value');
                const color = $('#modal-color-options .selected').data('color');
                const quantity = $('#modal-quantity').val();
                
                // Animation du bouton
                $(this).css('transform', 'scale(0.95)');
                setTimeout(() => {
                    $(this).css('transform', 'scale(1)');
                }, 200);
                
                // Afficher le récapitulatif
                showCartSummary(product, size, color, quantity);
            });
        }

        // Fonction pour animer le changement de quantité
        function animateQuantityChange(input) {
            input.css('transform', 'scale(1.1)');
            setTimeout(() => {
                input.css('transform', 'scale(1)');
            }, 200);
        }

        // Fonction pour fermer la modal
        function closeModal() {
            $('#product-modal').removeClass('active');
            setTimeout(() => {
                $('#product-modal-overlay').removeClass('active');
                $('body').css('overflow', 'auto');
            }, 300);
        }

        // Fonction pour afficher un récapitulatif d'ajout au panier
        function showCartSummary(product, size, color, quantity) {
            const summary = `
                <div class="cart-summary-overlay">
                    <div class="cart-summary-modal">
                        <div class="modal-header">
                            <h3>Produit ajouté au panier</h3>
                            <button class="close-summary"><i class="fas fa-times"></i></button>
                        </div>
                        <div class="modal-content">
                            <div class="summary-item">
                                <img src="${product.images[0]}" alt="${product.name}" class="summary-image">
                                <div class="summary-details">
                                    <h4>${product.name}</h4>
                                    <p>Taille: ${size}</p>
                                    <p>Couleur: ${color}</p>
                                    <p>Quantité: ${quantity}</p>
                                    <p class="summary-price">Total: ${calculateTotal(product.price, quantity)} €</p>
                                </div>
                            </div>
                            <div class="summary-actions">
                                <button class="summary-btn continue">Continuer mes achats</button>
                                <button class="summary-btn checkout">Voir le panier</button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            // Styles pour le récapitulatif
            const summaryStyles = `
                <style>
                    .cart-summary-overlay {
                        position: fixed;
                        top: 0;
                        left: 0;
                        width: 100%;
                        height: 100%;
                        background: rgba(44, 62, 80, 0.9);
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        z-index: 2000;
                        animation: fade-in 0.4s;
                    }
                    
                    @keyframes fade-in {
                        from { opacity: 0; }
                        to { opacity: 1; }
                    }
                    
                    .cart-summary-modal {
                        background: white;
                        border-radius: 20px;
                        width: 90%;
                        max-width: 500px;
                        overflow: hidden;
                        animation: slide-up 0.5s;
                        box-shadow: 0 25px 50px rgba(0,0,0,0.3);
                    }
                    
                    @keyframes slide-up {
                        from { transform: translateY(50px); opacity: 0; }
                        to { transform: translateY(0); opacity: 1; }
                    }
                    
                    .modal-header {
                        display: flex;
                        justify-content: space-between;
                        align-items: center;
                        padding: 25px;
                        background: linear-gradient(135deg, #2C3E50, #4A6572);
                        color: white;
                    }
                    
                    .modal-header h3 {
                        margin: 0;
                        font-family: 'Montserrat', sans-serif;
                    }
                    
                    .close-summary {
                        background: none;
                        border: none;
                        color: white;
                        font-size: 1.5rem;
                        cursor: pointer;
                    }
                    
                    .modal-content {
                        padding: 30px;
                    }
                    
                    .summary-item {
                        display: flex;
                        gap: 20px;
                        margin-bottom: 30px;
                    }
                    
                    .summary-image {
                        width: 120px;
                        height: 120px;
                        object-fit: cover;
                        border-radius: 12px;
                    }
                    
                    .summary-details h4 {
                        margin: 0 0 10px 0;
                        font-family: 'Montserrat', sans-serif;
                        color: #2C3E50;
                    }
                    
                    .summary-details p {
                        margin: 5px 0;
                        color: #7F8C8D;
                    }
                    
                    .summary-price {
                        font-weight: 700;
                        color: #1ABC9C !important;
                        font-size: 1.2rem;
                        margin-top: 10px !important;
                    }
                    
                    .summary-actions {
                        display: flex;
                        gap: 15px;
                    }
                    
                    .summary-btn {
                        flex: 1;
                        padding: 15px;
                        border: none;
                        border-radius: 12px;
                        font-family: 'Montserrat', sans-serif;
                        font-weight: 600;
                        cursor: pointer;
                        transition: all 0.3s;
                    }
                    
                    .summary-btn.continue {
                        background: #ECF0F1;
                        color: #2C3E50;
                    }
                    
                    .summary-btn.checkout {
                        background: linear-gradient(135deg, #1ABC9C, #16A085);
                        color: white;
                    }
                    
                    .summary-btn:hover {
                        transform: translateY(-3px);
                        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
                    }
                </style>
            `;
            
            // Ajouter au DOM
            $('head').append(summaryStyles);
            $('body').append(summary);
            
            // Gestion de la fermeture
            $('.close-summary, .summary-btn.continue').on('click', function() {
                $('.cart-summary-overlay').fadeOut(300, function() {
                    $(this).remove();
                    $(summaryStyles).remove();
                });
            });
            
            $('.summary-btn.checkout').on('click', function() {
                alert('Redirection vers le panier...');
                $('.cart-summary-overlay').remove();
                $(summaryStyles).remove();
                closeModal();
            });
        }

        // Fonction pour calculer le total
        function calculateTotal(priceStr, quantity) {
            const price = parseFloat(priceStr.replace(' €', '').replace(',', '.'));
            return (price * parseInt(quantity)).toFixed(2);
        }

        // Fonction pour afficher une notification
        function showNotification(message) {
            const notification = $(`
                <div class="notification">
                    <i class="fas fa-check-circle"></i>
                    <span>${message}</span>
                </div>
            `);
            
            // Styles pour la notification
            const notificationStyles = `
                <style>
                    .notification {
                        position: fixed;
                        bottom: 30px;
                        right: 30px;
                        background: white;
                        padding: 20px 25px;
                        border-radius: 12px;
                        box-shadow: 0 15px 35px rgba(0,0,0,0.15);
                        display: flex;
                        align-items: center;
                        gap: 15px;
                        z-index: 1000;
                        transform: translateX(100%);
                        opacity: 0;
                        animation: slide-in-notification 0.5s forwards, slide-out-notification 0.5s 2.5s forwards;
                    }
                    
                    @keyframes slide-in-notification {
                        to { transform: translateX(0); opacity: 1; }
                    }
                    
                    @keyframes slide-out-notification {
                        to { transform: translateX(100%); opacity: 0; }
                    }
                    
                    .notification i {
                        color: #1ABC9C;
                        font-size: 1.5rem;
                    }
                    
                    .notification span {
                        font-weight: 600;
                        color: #2C3E50;
                    }
                </style>
            `;
            
            $('head').append(notificationStyles);
            $('body').append(notification);
            
            // Supprimer après l'animation
            setTimeout(() => {
                notification.remove();
                $(notificationStyles).remove();
            }, 3000);
        }
    </script>
    <script>
function sendHeight() {
    const height = document.body.scrollHeight;
    window.parent.postMessage({
        type: 'setHeight',
        iframeId: 'iframe-page-2',
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