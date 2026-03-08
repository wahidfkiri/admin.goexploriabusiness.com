<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LuxeTech - Boutique High-Tech Premium</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #3b82f6;
            --primary-dark: #2563eb;
            --primary-light: #60a5fa;
            --secondary: #8b5cf6;
            --dark: #1e293b;
            --darker: #0f172a;
            --light: #f8fafc;
            --gray: #64748b;
            --gray-light: #f1f5f9;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --shadow-sm: 0 4px 12px rgba(0, 0, 0, 0.05);
            --shadow-md: 0 8px 25px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 15px 40px rgba(0, 0, 0, 0.15);
            --shadow-xl: 0 20px 60px rgba(0, 0, 0, 0.2);
            --transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.1);
            --border-radius: 20px;
            --border-radius-sm: 12px;
            --border-radius-lg: 30px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', 'Segoe UI', system-ui, sans-serif;
        }

        body {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            color: var(--dark);
            line-height: 1.7;
            overflow-x: hidden;
            min-height: 100vh;
        }

        /* Navigation Premium */
        .navbar {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(15px);
            box-shadow: var(--shadow-sm);
            position: sticky;
            top: 0;
            z-index: 1000;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }

        .nav-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 18px 0;
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 40px;
        }

        .logo {
            font-size: 2rem;
            font-weight: 800;
            color: var(--darker);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 12px;
            position: relative;
        }

        .logo::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 40px;
            height: 4px;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
            border-radius: 2px;
        }

        .logo-icon {
            color: var(--primary);
            font-size: 2.2rem;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }

        .nav-links {
            display: flex;
            gap: 40px;
        }

        .nav-link {
            color: var(--dark);
            text-decoration: none;
            font-weight: 600;
            font-size: 1.05rem;
            transition: var(--transition);
            position: relative;
            padding: 5px 0;
        }

        .nav-link:hover {
            color: var(--primary);
        }

        .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
            border-radius: 2px;
            transition: var(--transition);
        }

        .nav-link:hover::after {
            width: 100%;
        }

        .nav-link.active {
            color: var(--primary);
        }

        .nav-link.active::after {
            width: 100%;
        }

        .nav-icons {
            display: flex;
            gap: 25px;
            align-items: center;
        }

        .nav-icons a {
            color: var(--dark);
            font-size: 1.3rem;
            transition: var(--transition);
            position: relative;
        }

        .nav-icons a:hover {
            color: var(--primary);
            transform: translateY(-3px);
        }

        .cart-icon {
            position: relative;
        }

        .cart-count {
            position: absolute;
            top: -10px;
            right: -10px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            font-size: 0.75rem;
            width: 22px;
            height: 22px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            box-shadow: 0 4px 8px rgba(59, 130, 246, 0.3);
        }

        /* Container principal */
        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 40px;
        }

        /* Section Header */
        .section-header {
            text-align: center;
            margin: 80px 0 60px;
            position: relative;
        }

        .section-title {
            font-size: 3.5rem;
            font-weight: 900;
            margin-bottom: 20px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            position: relative;
            display: inline-block;
            letter-spacing: -1px;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: -15px;
            left: 50%;
            transform: translateX(-50%);
            width: 120px;
            height: 5px;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
            border-radius: 3px;
        }

        .section-subtitle {
            color: var(--gray);
            font-size: 1.25rem;
            max-width: 700px;
            margin: 30px auto 0;
            font-weight: 400;
        }

        /* Catégories */
        .categories {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 60px;
        }

        .category-btn {
            background: white;
            border: 2px solid var(--gray-light);
            padding: 16px 32px;
            border-radius: var(--border-radius-lg);
            font-weight: 700;
            cursor: pointer;
            transition: var(--transition);
            font-size: 1.05rem;
            box-shadow: var(--shadow-sm);
            position: relative;
            overflow: hidden;
            z-index: 1;
        }

        .category-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(59, 130, 246, 0.1), transparent);
            transition: left 0.7s ease;
            z-index: -1;
        }

        .category-btn:hover::before {
            left: 100%;
        }

        .category-btn.active, .category-btn:hover {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            border-color: transparent;
            transform: translateY(-5px);
            box-shadow: var(--shadow-lg);
        }

        /* Swiper Container */
        .swiper-container {
            position: relative;
            padding: 30px 0 100px;
        }

        .swiper {
            width: 100%;
            height: auto;
            padding: 30px 20px 80px;
        }

        .swiper-slide {
            background: white;
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: var(--shadow-md);
            transition: var(--transition);
            opacity: 0.9;
            transform: scale(0.97);
            height: auto;
            position: relative;
        }

        .swiper-slide-active {
            opacity: 1;
            transform: scale(1);
            box-shadow: var(--shadow-xl);
            z-index: 2;
        }

        .swiper-slide:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: var(--shadow-xl);
        }

        /* Carte Produit */
        .product-card {
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .product-badge {
            position: absolute;
            top: 20px;
            left: 20px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            padding: 8px 18px;
            border-radius: 30px;
            font-size: 0.85rem;
            font-weight: 800;
            z-index: 3;
            box-shadow: 0 6px 15px rgba(59, 130, 246, 0.3);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .product-image {
            height: 280px;
            overflow: hidden;
            position: relative;
        }

        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.8s ease;
        }

        .swiper-slide:hover .product-image img {
            transform: scale(1.1);
        }

        .product-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(0deg, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0) 60%);
            display: flex;
            align-items: flex-end;
            justify-content: center;
            opacity: 0;
            transition: var(--transition);
            padding: 30px;
        }

        .product-card:hover .product-overlay {
            opacity: 1;
        }

        .quick-view-btn {
            background: white;
            color: var(--dark);
            border: none;
            padding: 16px 32px;
            border-radius: 50px;
            font-weight: 700;
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 1.05rem;
            box-shadow: var(--shadow-lg);
            transform: translateY(20px);
            opacity: 0;
        }

        .product-card:hover .quick-view-btn {
            transform: translateY(0);
            opacity: 1;
        }

        .quick-view-btn:hover {
            background: var(--primary);
            color: white;
            transform: translateY(-5px);
            box-shadow: var(--shadow-xl);
        }

        .product-info {
            padding: 30px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .product-category {
            color: var(--gray);
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-bottom: 10px;
            font-weight: 600;
        }

        .product-title {
            font-size: 1.5rem;
            font-weight: 800;
            margin-bottom: 15px;
            line-height: 1.3;
            color: var(--darker);
        }

        .product-description {
            color: var(--gray);
            font-size: 1rem;
            margin-bottom: 25px;
            flex-grow: 1;
        }

        .product-features {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 25px;
        }

        .feature-tag {
            background: var(--gray-light);
            color: var(--dark);
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .product-rating {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 25px;
        }

        .product-rating i {
            color: #fbbf24;
            font-size: 1.2rem;
        }

        .product-rating span {
            color: var(--gray);
            font-size: 1rem;
            font-weight: 600;
        }

        .product-availability {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 25px;
            font-size: 0.95rem;
        }

        .in-stock {
            color: var(--success);
            font-weight: 700;
        }

        .product-price {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: auto;
        }

        .price {
            font-size: 2.2rem;
            font-weight: 900;
            color: var(--darker);
        }

        .price del {
            font-size: 1.4rem;
            color: var(--gray);
            margin-right: 15px;
            font-weight: 500;
        }

        .add-to-cart {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            border: none;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: var(--transition);
            font-size: 1.4rem;
            box-shadow: 0 8px 20px rgba(59, 130, 246, 0.3);
        }

        .add-to-cart:hover {
            transform: translateY(-5px) rotate(90deg);
            box-shadow: 0 15px 30px rgba(59, 130, 246, 0.4);
        }

        /* Swiper Navigation */
        .swiper-button-next, .swiper-button-prev {
            background: white;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            box-shadow: var(--shadow-lg);
            top: 45%;
            transition: var(--transition);
        }

        .swiper-button-next:after, .swiper-button-prev:after {
            font-size: 1.5rem;
            color: var(--dark);
            font-weight: 900;
        }

        .swiper-button-next:hover, .swiper-button-prev:hover {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            transform: scale(1.1);
        }

        .swiper-button-next:hover:after, .swiper-button-prev:hover:after {
            color: white;
        }

        .swiper-pagination-bullet {
            width: 14px;
            height: 14px;
            background: var(--gray-light);
            opacity: 1;
            transition: var(--transition);
        }

        .swiper-pagination-bullet-active {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            transform: scale(1.3);
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(10px);
            z-index: 2000;
            align-items: center;
            justify-content: center;
            padding: 20px;
            animation: fadeIn 0.4s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .modal-content {
            background: white;
            border-radius: var(--border-radius);
            width: 100%;
            max-width: 1200px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: var(--shadow-xl);
            animation: slideUp 0.5s ease;
            position: relative;
        }

        @keyframes slideUp {
            from { 
                opacity: 0;
                transform: translateY(50px);
            }
            to { 
                opacity: 1;
                transform: translateY(0);
            }
        }

        .close-modal {
            position: absolute;
            top: 25px;
            right: 25px;
            background: rgba(0, 0, 0, 0.1);
            color: var(--dark);
            border: none;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 1.5rem;
            transition: var(--transition);
            z-index: 10;
        }

        .close-modal:hover {
            background: var(--danger);
            color: white;
            transform: rotate(90deg);
        }

        .modal-body {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0;
        }

        .modal-images {
            padding: 50px;
            background: var(--gray-light);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .modal-main-image {
            width: 100%;
            height: 400px;
            object-fit: contain;
            margin-bottom: 30px;
            transition: var(--transition);
        }

        .modal-thumbnails {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            justify-content: center;
        }

        .modal-thumbnail {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: var(--border-radius-sm);
            cursor: pointer;
            transition: var(--transition);
            border: 3px solid transparent;
        }

        .modal-thumbnail:hover, .modal-thumbnail.active {
            border-color: var(--primary);
            transform: scale(1.1);
        }

        .modal-info {
            padding: 50px;
            display: flex;
            flex-direction: column;
        }

        .modal-badge {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            padding: 10px 20px;
            border-radius: 30px;
            font-size: 0.9rem;
            font-weight: 800;
            display: inline-block;
            width: fit-content;
            margin-bottom: 20px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .modal-category {
            color: var(--gray);
            font-size: 1rem;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 15px;
            font-weight: 600;
        }

        .modal-title {
            font-size: 2.5rem;
            font-weight: 900;
            margin-bottom: 25px;
            color: var(--darker);
            line-height: 1.2;
        }

        .modal-rating {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 25px;
        }

        .modal-rating-stars i {
            color: #fbbf24;
            font-size: 1.5rem;
        }

        .modal-rating-text {
            color: var(--gray);
            font-size: 1.1rem;
            font-weight: 600;
        }

        .modal-description {
            color: var(--dark);
            font-size: 1.15rem;
            line-height: 1.8;
            margin-bottom: 30px;
        }

        .modal-features {
            margin-bottom: 30px;
        }

        .modal-features h3 {
            font-size: 1.3rem;
            margin-bottom: 15px;
            color: var(--darker);
        }

        .modal-features ul {
            list-style: none;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        .modal-features li {
            display: flex;
            align-items: center;
            gap: 10px;
            color: var(--gray);
            font-size: 1rem;
        }

        .modal-features li i {
            color: var(--success);
        }

        .modal-price-section {
            background: var(--gray-light);
            padding: 30px;
            border-radius: var(--border-radius);
            margin-bottom: 30px;
        }

        .modal-price {
            font-size: 3rem;
            font-weight: 900;
            color: var(--darker);
            margin-bottom: 15px;
        }

        .modal-price del {
            font-size: 1.8rem;
            color: var(--gray);
            margin-right: 20px;
            font-weight: 500;
        }

        .modal-actions {
            display: flex;
            gap: 20px;
            margin-top: 20px;
        }

        .modal-add-to-cart {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            border: none;
            padding: 22px 45px;
            border-radius: var(--border-radius-lg);
            font-weight: 800;
            cursor: pointer;
            transition: var(--transition);
            font-size: 1.2rem;
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
            box-shadow: 0 10px 25px rgba(59, 130, 246, 0.3);
        }

        .modal-add-to-cart:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(59, 130, 246, 0.4);
        }

        .modal-wishlist {
            background: white;
            color: var(--dark);
            border: 2px solid var(--gray-light);
            padding: 22px 30px;
            border-radius: var(--border-radius-lg);
            font-weight: 800;
            cursor: pointer;
            transition: var(--transition);
            font-size: 1.2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
        }

        .modal-wishlist:hover {
            border-color: var(--primary);
            color: var(--primary);
            transform: translateY(-5px);
        }

        /* Footer */
        .footer {
            background: var(--darker);
            color: white;
            padding: 80px 0 40px;
            margin-top: 100px;
            position: relative;
            overflow: hidden;
        }

        .footer::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
        }

        .footer-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 60px;
            margin-bottom: 60px;
            position: relative;
            z-index: 1;
        }

        .footer-column h3 {
            font-size: 1.5rem;
            margin-bottom: 30px;
            position: relative;
            padding-bottom: 15px;
        }

        .footer-column h3::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 50px;
            height: 4px;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
            border-radius: 2px;
        }

        .footer-links {
            list-style: none;
        }

        .footer-links li {
            margin-bottom: 18px;
        }

        .footer-links a {
            color: #cbd5e1;
            text-decoration: none;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .footer-links a:hover {
            color: white;
            transform: translateX(10px);
        }

        .footer-links a i {
            color: var(--primary);
        }

        .copyright {
            text-align: center;
            padding-top: 40px;
            border-top: 1px solid #334155;
            color: #94a3b8;
            font-size: 0.95rem;
        }

        /* Responsive */
        @media (max-width: 1200px) {
            .modal-body {
                grid-template-columns: 1fr;
            }
            
            .modal-images {
                padding: 40px;
            }
        }

        @media (max-width: 992px) {
            .nav-links {
                display: none;
            }
            
            .section-title {
                font-size: 2.8rem;
            }
            
            .modal-features ul {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .container, .nav-container {
                padding: 0 25px;
            }
            
            .swiper-button-next, .swiper-button-prev {
                display: none;
            }
            
            .modal-info, .modal-images {
                padding: 30px;
            }
            
            .modal-title {
                font-size: 2rem;
            }
            
            .modal-actions {
                flex-direction: column;
            }
        }

        @media (max-width: 576px) {
            .section-title {
                font-size: 2.2rem;
            }
            
            .categories {
                gap: 12px;
            }
            
            .category-btn {
                padding: 14px 25px;
                font-size: 1rem;
            }
            
            .product-image {
                height: 220px;
            }
            
            .product-info {
                padding: 25px;
            }
        }

        /* Animation pour les cartes */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .product-card {
            animation: fadeInUp 0.6s ease forwards;
            opacity: 0;
        }

        .swiper-slide-visible .product-card {
            opacity: 1;
        }
    </style>
</head>
<body>

    <!-- Main Content -->
    <main class="container">
        <section class="ecommerce-section">
            <div class="section-header">
                <h1 class="section-title">Collection Élite High-Tech</h1>
                <p class="section-subtitle">Découvrez notre sélection exclusive d'appareils électroniques premium. Innovation, design et performance réunis.</p>
            </div>

            <!-- Catégories -->
            <div class="categories">
                <button class="category-btn active" data-category="all">Tous les produits</button>
                <button class="category-btn" data-category="smartphone">Smartphones</button>
                <button class="category-btn" data-category="laptop">Ordinateurs</button>
                <button class="category-btn" data-category="headphone">Audio</button>
                <button class="category-btn" data-category="smartwatch">Montres</button>
                <button class="category-btn" data-category="tablet">Tablettes</button>
            </div>

            <!-- Swiper -->
            <div class="swiper-container">
                <div class="swiper">
                    <div class="swiper-wrapper">
                        <!-- Produit 1 -->
                        <div class="swiper-slide" data-category="smartphone">
                            <div class="product-card">
                                <div class="product-badge">Édition Limitée</div>
                                <div class="product-image">
                                    <img src="https://images.unsplash.com/photo-1592899677977-9c10ca588bbd?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80" alt="Smartphone pliable premium">
                                    <div class="product-overlay">
                                        <button class="quick-view-btn" data-product="1">
                                            <i class="fas fa-expand"></i> Voir détails
                                        </button>
                                    </div>
                                </div>
                                <div class="product-info">
                                    <span class="product-category">Smartphone Premium</span>
                                    <h3 class="product-title">FlexPhone Z Pro</h3>
                                    <p class="product-description">Le smartphone pliable ultime avec écran 7.6" AMOLED, processeur Snapdragon 8 Gen 3 et triple caméra 200MP.</p>
                                    
                                    <div class="product-features">
                                        <span class="feature-tag">Écran pliable</span>
                                        <span class="feature-tag">512 Go</span>
                                        <span class="feature-tag">12 Go RAM</span>
                                    </div>
                                    
                                    <div class="product-rating">
                                        <div>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star-half-alt"></i>
                                        </div>
                                        <span>4.7/5 (128 avis)</span>
                                    </div>
                                    
                                    <div class="product-availability">
                                        <i class="fas fa-check-circle in-stock"></i>
                                        <span>En stock • Livraison gratuite</span>
                                    </div>
                                    
                                    <div class="product-price">
                                        <div class="price">
                                            <del>2 299€</del> 1 999€
                                        </div>
                                        <button class="add-to-cart">
                                            <i class="fas fa-shopping-cart"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Produit 2 -->
                        <div class="swiper-slide" data-category="laptop">
                            <div class="product-card">
                                <div class="product-badge">Promo -25%</div>
                                <div class="product-image">
                                    <img src="https://images.unsplash.com/photo-1496181133206-80ce9b88a853?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80" alt="Ordinateur portable ultra-fin">
                                    <div class="product-overlay">
                                        <button class="quick-view-btn" data-product="2">
                                            <i class="fas fa-expand"></i> Voir détails
                                        </button>
                                    </div>
                                </div>
                                <div class="product-info">
                                    <span class="product-category">Ultrabook Pro</span>
                                    <h3 class="product-title">ZenBook Pro 16X</h3>
                                    <p class="product-description">Ultrabook professionnel avec écran OLED 4K 16", processeur Intel Core i9 et carte graphique RTX 4080.</p>
                                    
                                    <div class="product-features">
                                        <span class="feature-tag">32 Go RAM</span>
                                        <span class="feature-tag">2 To SSD</span>
                                        <span class="feature-tag">RTX 4080</span>
                                    </div>
                                    
                                    <div class="product-rating">
                                        <div>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="far fa-star"></i>
                                        </div>
                                        <span>4.3/5 (89 avis)</span>
                                    </div>
                                    
                                    <div class="product-availability">
                                        <i class="fas fa-check-circle in-stock"></i>
                                        <span>En stock • Livraison express</span>
                                    </div>
                                    
                                    <div class="product-price">
                                        <div class="price">
                                            <del>3 499€</del> 2 799€
                                        </div>
                                        <button class="add-to-cart">
                                            <i class="fas fa-shopping-cart"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Produit 3 -->
                        <div class="swiper-slide" data-category="headphone">
                            <div class="product-card">
                                <div class="product-badge">Meilleure vente</div>
                                <div class="product-image">
                                    <img src="https://images.unsplash.com/photo-1505740420928-5e560c06d30e?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80" alt="Écouteurs sans fil premium">
                                    <div class="product-overlay">
                                        <button class="quick-view-btn" data-product="3">
                                            <i class="fas fa-expand"></i> Voir détails
                                        </button>
                                    </div>
                                </div>
                                <div class="product-info">
                                    <span class="product-category">Écouteurs Sans Fil</span>
                                    <h3 class="product-title">SoundMax Elite 3</h3>
                                    <p class="product-description">Écouteurs True Wireless avec annulation active de bruit, son Hi-Res et autonomie de 40 heures.</p>
                                    
                                    <div class="product-features">
                                        <span class="feature-tag">ANC Pro</span>
                                        <span class="feature-tag">40h autonomie</span>
                                        <span class="feature-tag">Hi-Res Audio</span>
                                    </div>
                                    
                                    <div class="product-rating">
                                        <div>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                        </div>
                                        <span>5.0/5 (256 avis)</span>
                                    </div>
                                    
                                    <div class="product-availability">
                                        <i class="fas fa-check-circle in-stock"></i>
                                        <span>En stock • Livraison gratuite</span>
                                    </div>
                                    
                                    <div class="product-price">
                                        <div class="price">349€</div>
                                        <button class="add-to-cart">
                                            <i class="fas fa-shopping-cart"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Produit 4 -->
                        <div class="swiper-slide" data-category="smartwatch">
                            <div class="product-card">
                                <div class="product-badge">Nouveau</div>
                                <div class="product-image">
                                    <img src="https://images.unsplash.com/photo-1523275335684-37898b6baf30?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80" alt="Smartwatch premium">
                                    <div class="product-overlay">
                                        <button class="quick-view-btn" data-product="4">
                                            <i class="fas fa-expand"></i> Voir détails
                                        </button>
                                    </div>
                                </div>
                                <div class="product-info">
                                    <span class="product-category">Smartwatch</span>
                                    <h3 class="product-title">Chrono Elite Pro</h3>
                                    <p class="product-description">Montre connectée avec écran AMOLED 1.9", suivi santé avancé et autonomie de 14 jours.</p>
                                    
                                    <div class="product-features">
                                        <span class="feature-tag">Écran AMOLED</span>
                                        <span class="feature-tag">14 jours</span>
                                        <span class="feature-tag">GPS intégré</span>
                                    </div>
                                    
                                    <div class="product-rating">
                                        <div>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star-half-alt"></i>
                                        </div>
                                        <span>4.6/5 (187 avis)</span>
                                    </div>
                                    
                                    <div class="product-availability">
                                        <i class="fas fa-check-circle in-stock"></i>
                                        <span>En stock • Livraison demain</span>
                                    </div>
                                    
                                    <div class="product-price">
                                        <div class="price">449€</div>
                                        <button class="add-to-cart">
                                            <i class="fas fa-shopping-cart"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Produit 5 -->
                        <div class="swiper-slide" data-category="tablet">
                            <div class="product-card">
                                <div class="product-badge">Écologique</div>
                                <div class="product-image">
                                    <img src="https://images.unsplash.com/photo-1544244015-0df4b3ffc6b0?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80" alt="Tablette premium">
                                    <div class="product-overlay">
                                        <button class="quick-view-btn" data-product="5">
                                            <i class="fas fa-expand"></i> Voir détails
                                        </button>
                                    </div>
                                </div>
                                <div class="product-info">
                                    <span class="product-category">Tablette Pro</span>
                                    <h3 class="product-title">TabPro X 12.9"</h3>
                                    <p class="product-description">Tablette professionnelle avec écran Mini-LED 12.9", puce M3 et stylet avancé pour créatifs.</p>
                                    
                                    <div class="product-features">
                                        <span class="feature-tag">Écran Mini-LED</span>
                                        <span class="feature-tag">Puce M3</span>
                                        <span class="feature-tag">2 To</span>
                                    </div>
                                    
                                    <div class="product-rating">
                                        <div>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                        </div>
                                        <span>5.0/5 (94 avis)</span>
                                    </div>
                                    
                                    <div class="product-availability">
                                        <i class="fas fa-check-circle in-stock"></i>
                                        <span>En stock • Livraison gratuite</span>
                                    </div>
                                    
                                    <div class="product-price">
                                        <div class="price">1 299€</div>
                                        <button class="add-to-cart">
                                            <i class="fas fa-shopping-cart"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Produit 6 -->
                        <div class="swiper-slide" data-category="laptop">
                            <div class="product-card">
                                <div class="product-badge">Gaming</div>
                                <div class="product-image">
                                    <img src="https://images.unsplash.com/photo-1588872657578-7efd1f1555ed?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80" alt="Ordinateur portable gaming">
                                    <div class="product-overlay">
                                        <button class="quick-view-btn" data-product="6">
                                            <i class="fas fa-expand"></i> Voir détails
                                        </button>
                                    </div>
                                </div>
                                <div class="product-info">
                                    <span class="product-category">Gaming Laptop</span>
                                    <h3 class="product-title">Nexus Force 17</h3>
                                    <p class="product-description">PC portable gaming avec écran 240Hz, RTX 4090, processeur Intel Core i9 et 64 Go de RAM.</p>
                                    
                                    <div class="product-features">
                                        <span class="feature-tag">RTX 4090</span>
                                        <span class="feature-tag">64 Go RAM</span>
                                        <span class="feature-tag">Écran 240Hz</span>
                                    </div>
                                    
                                    <div class="product-rating">
                                        <div>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star-half-alt"></i>
                                        </div>
                                        <span>4.8/5 (67 avis)</span>
                                    </div>
                                    
                                    <div class="product-availability">
                                        <i class="fas fa-check-circle in-stock"></i>
                                        <span>En stock • Livraison express</span>
                                    </div>
                                    
                                    <div class="product-price">
                                        <div class="price">4 299€</div>
                                        <button class="add-to-cart">
                                            <i class="fas fa-shopping-cart"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Navigation buttons -->
                    <div class="swiper-button-next"></div>
                    <div class="swiper-button-prev"></div>
                    
                    <!-- Pagination -->
                    <div class="swiper-pagination"></div>
                </div>
            </div>
        </section>
    </main>


    <!-- Modal de détail produit -->
    <div class="modal" id="productModal">
        <div class="modal-content">
            <button class="close-modal" id="closeModal">&times;</button>
            <div class="modal-body" id="modalBody">
                <!-- Le contenu sera injecté ici par JavaScript -->
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        // Données des produits (simulées)
        const products = {
            1: {
                id: 1,
                name: "FlexPhone Z Pro",
                category: "Smartphone Premium",
                badge: "Édition Limitée",
                price: "1 999€",
                oldPrice: "2 299€",
                rating: 4.7,
                reviews: 128,
                description: "Le smartphone pliable ultime repousse les limites de l'innovation. Avec son écran AMOLED 7.6\" dynamique qui se plie parfaitement, le FlexPhone Z Pro offre une expérience visuelle inégalée. Propulsé par le processeur Snapdragon 8 Gen 3, il garantit des performances exceptionnelles pour le gaming et le multitâche.",
                features: [
                    "Écran AMOLED 7.6\" dynamique pliable",
                    "Processeur Snapdragon 8 Gen 3",
                    "Triple caméra 200MP avec zoom 10x",
                    "512 Go de stockage, 12 Go RAM",
                    "Batterie 5000mAh, charge 100W",
                    "Résistance IP68, verre Gorilla Glass Victus 2"
                ],
                images: [
                    "https://images.unsplash.com/photo-1592899677977-9c10ca588bbd?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80",
                    "https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80",
                    "https://images.unsplash.com/photo-1512941937669-90a1b58e7e9c?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80",
                    "https://images.unsplash.com/photo-1598327105666-5b89351aff97?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80"
                ],
                availability: "En stock • Livraison gratuite"
            },
            2: {
                id: 2,
                name: "ZenBook Pro 16X",
                category: "Ultrabook Pro",
                badge: "Promo -25%",
                price: "2 799€",
                oldPrice: "3 499€",
                rating: 4.3,
                reviews: 89,
                description: "L'ultrabook professionnel ultime pour les créatifs et développeurs exigeants. Avec son écran OLED 4K 16\" au taux de rafraîchissement de 120Hz, il offre des couleurs précises et des contrastes profonds. Parfait pour le montage vidéo, la conception 3D et le développement d'applications.",
                features: [
                    "Écran OLED 4K 16\" 120Hz",
                    "Processeur Intel Core i9-13900H",
                    "Carte graphique NVIDIA RTX 4080",
                    "32 Go RAM DDR5, 2 To SSD NVMe",
                    "Batterie 96Wh, autonomie 12h",
                    "Clavier rétroéclairé, TouchPad haptique"
                ],
                images: [
                    "https://images.unsplash.com/photo-1496181133206-80ce9b88a853?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80",
                    "https://images.unsplash.com/photo-1499951360447-b19be8fe80f5?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80",
                    "https://images.unsplash.com/photo-1517077304055-6e89abbf09b0?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80"
                ],
                availability: "En stock • Livraison express"
            },
            3: {
                id: 3,
                name: "SoundMax Elite 3",
                category: "Écouteurs Sans Fil",
                badge: "Meilleure vente",
                price: "349€",
                oldPrice: null,
                rating: 5.0,
                reviews: 256,
                description: "Les écouteurs True Wireless ultimes avec technologie d'annulation active de bruit de nouvelle génération. Profitez d'une expérience audio immersive avec son Hi-Res certifié, des basses profondes et des aigus cristallins. Parfaits pour les mélomanes et les professionnels du son.",
                features: [
                    "Annulation active de bruit Pro (ANC)",
                    "Qualité audio Hi-Res 24-bit/96kHz",
                    "Autonomie 40h (avec étui)",
                    "Charge rapide 15min = 5h d'écoute",
                    "Bluetooth 5.3 avec multipoint",
                    "Protection IPX5, commandes tactiles"
                ],
                images: [
                    "https://images.unsplash.com/photo-1505740420928-5e560c06d30e?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80",
                    "https://images.unsplash.com/photo-1484704849700-f032a568e944?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80",
                    "https://images.unsplash.com/photo-1583394838336-acd977736f90?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80"
                ],
                availability: "En stock • Livraison gratuite"
            },
            4: {
                id: 4,
                name: "Chrono Elite Pro",
                category: "Smartwatch",
                badge: "Nouveau",
                price: "449€",
                oldPrice: null,
                rating: 4.6,
                reviews: 187,
                description: "La montre connectée premium alliant technologie avancée et design raffiné. Avec son écran AMOLED 1.9\" toujours actif et son suivi santé complet, elle vous accompagne au quotidien tout en affichant votre style unique. Parfaite pour les sportifs et les professionnels.",
                features: [
                    "Écran AMOLED 1.9\" toujours actif",
                    "Autonomie 14 jours (mode standard)",
                    "GPS, GLONASS, Galileo intégrés",
                    "Suivi santé: ECG, SpO2, sommeil",
                    "Résistance 5ATM, verre saphir",
                    "Compatible iOS et Android"
                ],
                images: [
                    "https://images.unsplash.com/photo-1523275335684-37898b6baf30?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80",
                    "https://images.unsplash.com/photo-1579586337278-3f25714980d6?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80",
                    "https://images.unsplash.com/photo-1546868871-7041f2a55e12?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80"
                ],
                availability: "En stock • Livraison demain"
            }
        };

        // Initialiser Swiper
        const swiper = new Swiper('.swiper', {
            slidesPerView: 1,
            spaceBetween: 30,
            loop: true,
            centeredSlides: false,
            autoplay: {
                delay: 6000,
                disableOnInteraction: false,
                pauseOnMouseEnter: true
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
                dynamicBullets: true
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            breakpoints: {
                640: {
                    slidesPerView: 2,
                },
                1024: {
                    slidesPerView: 3,
                },
            },
            on: {
                init: function () {
                    animateCards();
                },
                slideChange: function () {
                    animateCards();
                }
            }
        });

        // Animation des cartes
        function animateCards() {
            document.querySelectorAll('.swiper-slide').forEach((slide, index) => {
                if (slide.classList.contains('swiper-slide-visible')) {
                    const card = slide.querySelector('.product-card');
                    card.style.animationDelay = `${index * 0.1}s`;
                    card.style.animation = 'fadeInUp 0.6s ease forwards';
                }
            });
        }

        // Gestion des catégories
        document.querySelectorAll('.category-btn').forEach(button => {
            button.addEventListener('click', function() {
                document.querySelectorAll('.category-btn').forEach(btn => {
                    btn.classList.remove('active');
                });
                this.classList.add('active');
                
                const category = this.getAttribute('data-category');
                
                document.querySelectorAll('.swiper-slide').forEach(slide => {
                    if (category === 'all' || slide.getAttribute('data-category') === category) {
                        slide.style.display = 'block';
                    } else {
                        slide.style.display = 'none';
                    }
                });
                
                swiper.update();
                animateCards();
            });
        });

        // Gestion de la modale
        const modal = document.getElementById('productModal');
        const closeModal = document.getElementById('closeModal');
        const modalBody = document.getElementById('modalBody');
        
        // Ouvrir la modale
        document.querySelectorAll('.quick-view-btn').forEach(button => {
            button.addEventListener('click', function() {
                const productId = this.getAttribute('data-product');
                openModal(productId);
            });
        });
        
        // Fermer la modale
        closeModal.addEventListener('click', () => {
            closeModalFunction();
        });
        
        // Fermer en cliquant en dehors
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                closeModalFunction();
            }
        });
        
        // Fermer avec la touche Échap
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && modal.style.display === 'flex') {
                closeModalFunction();
            }
        });
        
        function openModal(productId) {
            const product = products[productId];
            if (!product) return;
            
            // Générer les étoiles de notation
            const stars = generateStars(product.rating);
            
            // Générer les fonctionnalités
            const featuresList = product.features.map(feature => `
                <li><i class="fas fa-check"></i> ${feature}</li>
            `).join('');
            
            // Générer les miniatures
            const thumbnails = product.images.map((img, index) => `
                <img src="${img}" alt="Vue ${index + 1}" class="modal-thumbnail ${index === 0 ? 'active' : ''}" data-index="${index}">
            `).join('');
            
            // Injecter le contenu dans la modale
            modalBody.innerHTML = `
                <div class="modal-images">
                    <img src="${product.images[0]}" alt="${product.name}" class="modal-main-image" id="modalMainImage">
                    <div class="modal-thumbnails">
                        ${thumbnails}
                    </div>
                </div>
                <div class="modal-info">
                    <span class="modal-badge">${product.badge}</span>
                    <span class="modal-category">${product.category}</span>
                    <h2 class="modal-title">${product.name}</h2>
                    
                    <div class="modal-rating">
                        <div class="modal-rating-stars">${stars}</div>
                        <span class="modal-rating-text">${product.rating}/5 (${product.reviews} avis)</span>
                    </div>
                    
                    <p class="modal-description">${product.description}</p>
                    
                    <div class="modal-features">
                        <h3>Caractéristiques principales</h3>
                        <ul>${featuresList}</ul>
                    </div>
                    
                    <div class="modal-price-section">
                        <div class="modal-price">
                            ${product.oldPrice ? `<del>${product.oldPrice}</del>` : ''}
                            ${product.price}
                        </div>
                        <p><i class="fas fa-check-circle in-stock"></i> ${product.availability}</p>
                    </div>
                    
                    <div class="modal-actions">
                        <button class="modal-add-to-cart" data-product="${product.id}">
                            <i class="fas fa-shopping-cart"></i> Ajouter au panier
                        </button>
                        <button class="modal-wishlist">
                            <i class="far fa-heart"></i> Ajouter à la liste
                        </button>
                    </div>
                </div>
            `;
            
            // Afficher la modale
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
            
            // Gérer les miniatures
            setupThumbnails();
            setupModalButtons(productId);
        }
        
        function closeModalFunction() {
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }
        
        function generateStars(rating) {
            let stars = '';
            const fullStars = Math.floor(rating);
            const hasHalfStar = rating % 1 >= 0.5;
            
            for (let i = 0; i < fullStars; i++) {
                stars += '<i class="fas fa-star"></i>';
            }
            
            if (hasHalfStar) {
                stars += '<i class="fas fa-star-half-alt"></i>';
            }
            
            const emptyStars = 5 - fullStars - (hasHalfStar ? 1 : 0);
            for (let i = 0; i < emptyStars; i++) {
                stars += '<i class="far fa-star"></i>';
            }
            
            return stars;
        }
        
        function setupThumbnails() {
            const mainImage = document.getElementById('modalMainImage');
            const thumbnails = document.querySelectorAll('.modal-thumbnail');
            
            thumbnails.forEach(thumbnail => {
                thumbnail.addEventListener('click', function() {
                    // Mettre à jour l'image principale
                    mainImage.src = this.src;
                    
                    // Mettre à jour les miniatures actives
                    thumbnails.forEach(t => t.classList.remove('active'));
                    this.classList.add('active');
                    
                    // Animation de l'image principale
                    mainImage.style.opacity = '0.5';
                    setTimeout(() => {
                        mainImage.style.opacity = '1';
                    }, 150);
                });
            });
        }
        
        function setupModalButtons(productId) {
            // Bouton "Ajouter au panier" dans la modale
            document.querySelector('.modal-add-to-cart').addEventListener('click', function() {
                addToCart(productId);
            });
            
            // Bouton "Ajouter à la liste de souhaits"
            document.querySelector('.modal-wishlist').addEventListener('click', function() {
                const icon = this.querySelector('i');
                if (icon.classList.contains('far')) {
                    icon.classList.remove('far');
                    icon.classList.add('fas');
                    this.innerHTML = '<i class="fas fa-heart"></i> Retirer de la liste';
                    showNotification('Produit ajouté à votre liste de souhaits');
                } else {
                    icon.classList.remove('fas');
                    icon.classList.add('far');
                    this.innerHTML = '<i class="far fa-heart"></i> Ajouter à la liste';
                    showNotification('Produit retiré de votre liste de souhaits');
                }
            });
        }
        
        // Fonction d'ajout au panier
        function addToCart(productId) {
            const product = products[productId];
            const cartCount = document.querySelector('.cart-count');
            let currentCount = parseInt(cartCount.textContent);
            cartCount.textContent = currentCount + 1;
            
            // Animation sur le bouton
            const addButton = document.querySelector('.modal-add-to-cart');
            const originalText = addButton.innerHTML;
            addButton.innerHTML = '<i class="fas fa-check"></i> Ajouté !';
            addButton.style.background = 'linear-gradient(135deg, var(--success), #059669)';
            
            // Afficher une notification
            showNotification(`${product.name} a été ajouté à votre panier`);
            
            // Réinitialiser le bouton après 2 secondes
            setTimeout(() => {
                addButton.innerHTML = originalText;
                addButton.style.background = '';
            }, 2000);
            
            // Fermer la modale après ajout (optionnel)
            // setTimeout(() => closeModalFunction(), 2000);
        }
        
        // Afficher une notification
        function showNotification(message) {
            // Créer l'élément de notification
            const notification = document.createElement('div');
            notification.style.cssText = `
                position: fixed;
                top: 30px;
                right: 30px;
                background: linear-gradient(135deg, var(--primary), var(--secondary));
                color: white;
                padding: 18px 25px;
                border-radius: 12px;
                box-shadow: var(--shadow-xl);
                z-index: 3000;
                font-weight: 600;
                display: flex;
                align-items: center;
                gap: 12px;
                animation: slideInRight 0.4s ease;
            `;
            
            notification.innerHTML = `
                <i class="fas fa-check-circle"></i>
                <span>${message}</span>
            `;
            
            document.body.appendChild(notification);
            
            // Supprimer la notification après 3 secondes
            setTimeout(() => {
                notification.style.animation = 'slideOutRight 0.4s ease';
                setTimeout(() => {
                    document.body.removeChild(notification);
                }, 400);
            }, 3000);
            
            // Ajouter l'animation CSS pour la notification
            const style = document.createElement('style');
            style.textContent = `
                @keyframes slideInRight {
                    from { transform: translateX(100%); opacity: 0; }
                    to { transform: translateX(0); opacity: 1; }
                }
                @keyframes slideOutRight {
                    from { transform: translateX(0); opacity: 1; }
                    to { transform: translateX(100%); opacity: 0; }
                }
            `;
            document.head.appendChild(style);
        }
        
        // Ajouter au panier depuis les cartes
        document.querySelectorAll('.add-to-cart').forEach(button => {
            button.addEventListener('click', function() {
                const productCard = this.closest('.product-card');
                const productName = productCard.querySelector('.product-title').textContent;
                
                const cartCount = document.querySelector('.cart-count');
                let currentCount = parseInt(cartCount.textContent);
                cartCount.textContent = currentCount + 1;
                
                // Animation du bouton
                this.innerHTML = '<i class="fas fa-check"></i>';
                this.style.background = 'linear-gradient(135deg, var(--success), #059669)';
                
                showNotification(`${productName} ajouté au panier`);
                
                // Réinitialiser le bouton
                setTimeout(() => {
                    this.innerHTML = '<i class="fas fa-shopping-cart"></i>';
                    this.style.background = '';
                }, 2000);
            });
        });
        
        // Animation au chargement
        document.addEventListener('DOMContentLoaded', () => {
            animateCards();
        });
    </script>
    <script>
function sendHeight() {
    const height = document.body.scrollHeight;
    window.parent.postMessage({
        type: 'setHeight',
        iframeId: 'iframe-page-1',
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
