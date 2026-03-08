<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Explore Canada - Découvrez les Régions Magnifiques</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
    <style>
        :root {
            --primary: #2563eb;
            --primary-dark: #1d4ed8;
            --primary-light: #60a5fa;
            --secondary: #059669;
            --secondary-light: #10b981;
            --accent: #7c3aed;
            --dark: #1e293b;
            --darker: #0f172a;
            --light: #f8fafc;
            --gray: #64748b;
            --gray-light: #f1f5f9;
            --white: #ffffff;
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
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            color: var(--dark);
            line-height: 1.7;
            overflow-x: hidden;
            min-height: 100vh;
        }

        /* Navigation */
        .navbar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(15px);
            box-shadow: var(--shadow-sm);
            position: sticky;
            top: 0;
            z-index: 1000;
            border-bottom: 1px solid rgba(226, 232, 240, 0.5);
        }

        .nav-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 40px;
            max-width: 1400px;
            margin: 0 auto;
        }

        .logo {
            font-size: 2.2rem;
            font-weight: 900;
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
            bottom: -8px;
            left: 0;
            width: 50px;
            height: 4px;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
            border-radius: 2px;
        }

        .logo-icon {
            color: var(--primary);
            font-size: 2.5rem;
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        .nav-links {
            display: flex;
            gap: 40px;
        }

        .nav-link {
            color: var(--dark);
            text-decoration: none;
            font-weight: 600;
            font-size: 1.1rem;
            transition: var(--transition);
            position: relative;
            padding: 5px 0;
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

        .nav-link:hover::after, .nav-link.active::after {
            width: 100%;
        }

        .nav-link:hover, .nav-link.active {
            color: var(--primary);
        }

        /* Header Hero */
        .hero-section {
            background: linear-gradient(rgba(15, 23, 42, 0.85), rgba(15, 23, 42, 0.9)), url('https://images.unsplash.com/photo-1503614472-8c93d56e92ce?ixlib=rb-4.0.3&auto=format&fit=crop&w=1600&q=80');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            color: white;
            padding: 120px 40px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .hero-content {
            max-width: 900px;
            margin: 0 auto;
            position: relative;
            z-index: 2;
        }

        .hero-title {
            font-size: 4rem;
            font-weight: 900;
            margin-bottom: 25px;
            line-height: 1.1;
            text-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
            animation: fadeInUp 1s ease;
        }

        .hero-subtitle {
            font-size: 1.5rem;
            margin-bottom: 40px;
            color: #e2e8f0;
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
            animation: fadeInUp 1s ease 0.2s both;
        }

        .hero-search {
            max-width: 700px;
            margin: 0 auto;
            animation: fadeInUp 1s ease 0.4s both;
        }

        .search-box {
            display: flex;
            background: rgba(255, 255, 255, 0.95);
            border-radius: var(--border-radius-lg);
            padding: 10px;
            box-shadow: var(--shadow-lg);
        }

        .search-input {
            flex: 1;
            border: none;
            padding: 20px 25px;
            font-size: 1.1rem;
            border-radius: var(--border-radius-lg);
            outline: none;
        }

        .search-btn {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            border: none;
            padding: 0 40px;
            border-radius: var(--border-radius);
            font-weight: 700;
            cursor: pointer;
            transition: var(--transition);
            font-size: 1.1rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .search-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(37, 99, 235, 0.4);
        }

        /* Main Container */
        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 40px;
        }

        /* Section Header */
        .section-header {
            text-align: center;
            margin: 100px 0 70px;
            position: relative;
        }

        .section-title {
            font-size: 3.2rem;
            font-weight: 900;
            margin-bottom: 20px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            position: relative;
            display: inline-block;
            letter-spacing: -0.5px;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: -15px;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 5px;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
            border-radius: 3px;
        }

        .section-subtitle {
            color: var(--gray);
            font-size: 1.3rem;
            max-width: 800px;
            margin: 30px auto 0;
            font-weight: 400;
        }

        /* Filtres */
        .filters {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 60px;
        }

        .filter-btn {
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
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .filter-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(37, 99, 235, 0.1), transparent);
            transition: left 0.7s ease;
            z-index: -1;
        }

        .filter-btn:hover::before {
            left: 100%;
        }

        .filter-btn.active, .filter-btn:hover {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            border-color: transparent;
            transform: translateY(-5px);
            box-shadow: var(--shadow-lg);
        }

        /* Grille des Destinations */
        .destinations-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
            gap: 40px;
            margin-bottom: 100px;
        }

        /* Carte Destination */
        .destination-card {
            background: white;
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: var(--shadow-md);
            transition: var(--transition);
            position: relative;
            height: 500px;
            cursor: pointer;
        }

        .destination-card:hover {
            transform: translateY(-15px);
            box-shadow: var(--shadow-xl);
        }

        .card-badge {
            position: absolute;
            top: 25px;
            left: 25px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            padding: 10px 22px;
            border-radius: 30px;
            font-size: 0.9rem;
            font-weight: 800;
            z-index: 3;
            box-shadow: 0 8px 20px rgba(37, 99, 235, 0.3);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .card-image {
            height: 250px;
            overflow: hidden;
            position: relative;
        }

        .card-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.8s ease;
        }

        .destination-card:hover .card-image img {
            transform: scale(1.15);
        }

        .card-overlay {
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

        .destination-card:hover .card-overlay {
            opacity: 1;
        }

        .card-view-btn {
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

        .destination-card:hover .card-view-btn {
            transform: translateY(0);
            opacity: 1;
        }

        .card-view-btn:hover {
            background: var(--primary);
            color: white;
            transform: translateY(-5px);
            box-shadow: var(--shadow-xl);
        }

        .card-content {
            padding: 30px;
            display: flex;
            flex-direction: column;
            height: 250px;
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 20px;
        }

        .card-title {
            font-size: 1.6rem;
            font-weight: 800;
            line-height: 1.3;
            color: var(--darker);
        }

        .card-rating {
            display: flex;
            align-items: center;
            gap: 5px;
            background: var(--gray-light);
            padding: 8px 15px;
            border-radius: 20px;
        }

        .card-rating i {
            color: #fbbf24;
        }

        .card-description {
            color: var(--gray);
            font-size: 1rem;
            line-height: 1.6;
            margin-bottom: 25px;
            flex-grow: 1;
        }

        .card-features {
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

        .card-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: auto;
        }

        .card-price {
            font-size: 2rem;
            font-weight: 900;
            color: var(--darker);
        }

        .card-price span {
            font-size: 1rem;
            color: var(--gray);
            font-weight: 500;
        }

        .card-book-btn {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            border: none;
            padding: 14px 28px;
            border-radius: var(--border-radius);
            font-weight: 700;
            cursor: pointer;
            transition: var(--transition);
            font-size: 1rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .card-book-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(37, 99, 235, 0.3);
        }

        /* Modal Destination */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.9);
            backdrop-filter: blur(10px);
            z-index: 2000;
            align-items: center;
            justify-content: center;
            padding: 20px;
            animation: fadeIn 0.4s ease;
            overflow-y: auto;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .modal-content {
            background: white;
            border-radius: var(--border-radius);
            width: 100%;
            max-width: 1300px;
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
            background: var(--primary);
            color: white;
            transform: rotate(90deg);
        }

        .modal-body {
            display: grid;
            grid-template-columns: 1.2fr 0.8fr;
            gap: 0;
        }

        .modal-gallery {
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
            object-fit: cover;
            border-radius: var(--border-radius);
            margin-bottom: 30px;
            transition: var(--transition);
            box-shadow: var(--shadow-md);
        }

        .modal-thumbnails {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            justify-content: center;
            max-width: 100%;
        }

        .modal-thumbnail {
            width: 90px;
            height: 90px;
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
            overflow-y: auto;
        }

        .modal-badge {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            padding: 10px 22px;
            border-radius: 30px;
            font-size: 0.9rem;
            font-weight: 800;
            display: inline-block;
            width: fit-content;
            margin-bottom: 20px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .modal-region {
            color: var(--gray);
            font-size: 1rem;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 15px;
            font-weight: 600;
        }

        .modal-title {
            font-size: 2.8rem;
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

        .modal-highlights {
            margin-bottom: 30px;
        }

        .modal-highlights h3 {
            font-size: 1.4rem;
            margin-bottom: 20px;
            color: var(--darker);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .modal-highlights ul {
            list-style: none;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .modal-highlights li {
            display: flex;
            align-items: center;
            gap: 12px;
            color: var(--dark);
            font-size: 1rem;
        }

        .modal-highlights li i {
            color: var(--secondary);
            background: var(--gray-light);
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-details {
            background: var(--gray-light);
            padding: 30px;
            border-radius: var(--border-radius);
            margin-bottom: 30px;
        }

        .modal-price-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .modal-price {
            font-size: 3.2rem;
            font-weight: 900;
            color: var(--darker);
        }

        .modal-price span {
            font-size: 1.2rem;
            color: var(--gray);
            font-weight: 500;
        }

        .modal-duration {
            font-size: 1.1rem;
            color: var(--gray);
            font-weight: 600;
        }

        .modal-includes {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .include-item {
            display: flex;
            align-items: center;
            gap: 10px;
            color: var(--dark);
            font-size: 1rem;
        }

        .include-item i {
            color: var(--success);
            font-size: 1.2rem;
        }

        .modal-actions {
            display: flex;
            gap: 20px;
            margin-top: 30px;
        }

        .modal-book-btn {
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
            box-shadow: 0 10px 25px rgba(37, 99, 235, 0.3);
        }

        .modal-book-btn:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(37, 99, 235, 0.4);
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
            color: var(--primary-light);
        }

        .social-links {
            display: flex;
            gap: 20px;
            margin-top: 30px;
        }

        .social-links a {
            color: white;
            font-size: 1.5rem;
            transition: var(--transition);
        }

        .social-links a:hover {
            color: var(--primary-light);
            transform: translateY(-5px);
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
            
            .modal-gallery {
                padding: 40px;
            }
        }

        @media (max-width: 992px) {
            .nav-links {
                display: none;
            }
            
            .hero-title {
                font-size: 3rem;
            }
            
            .section-title {
                font-size: 2.6rem;
            }
            
            .destinations-grid {
                grid-template-columns: repeat(auto-fill, minmax(330px, 1fr));
            }
        }

        @media (max-width: 768px) {
            .container, .nav-container {
                padding: 0 25px;
            }
            
            .hero-title {
                font-size: 2.5rem;
            }
            
            .hero-section {
                padding: 80px 25px;
            }
            
            .search-box {
                flex-direction: column;
                gap: 15px;
                padding: 20px;
            }
            
            .search-input, .search-btn {
                width: 100%;
            }
            
            .modal-info, .modal-gallery {
                padding: 30px;
            }
            
            .modal-title {
                font-size: 2.2rem;
            }
            
            .modal-highlights ul {
                grid-template-columns: 1fr;
            }
            
            .modal-includes {
                grid-template-columns: 1fr;
            }
            
            .modal-actions {
                flex-direction: column;
            }
        }

        @media (max-width: 576px) {
            .section-title {
                font-size: 2.2rem;
            }
            
            .filters {
                gap: 12px;
            }
            
            .filter-btn {
                padding: 14px 25px;
                font-size: 1rem;
            }
            
            .destinations-grid {
                grid-template-columns: 1fr;
            }
            
            .destination-card {
                height: auto;
            }
            
            .card-image {
                height: 220px;
            }
        }

        /* Animations */
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

        .destination-card {
            animation: fadeInUp 0.6s ease forwards;
            opacity: 0;
        }

        /* Animation de chargement */
        @keyframes shimmer {
            0% { background-position: -1000px 0; }
            100% { background-position: 1000px 0; }
        }

        .loading-card {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 1000px 100%;
            animation: shimmer 2s infinite linear;
            border-radius: var(--border-radius);
            height: 500px;
        }
    </style>
</head>
<body>


    <!-- Main Content -->
    <main class="container">
        <!-- Section Destinations -->
        <section class="destinations-section">
            <div class="section-header">
                <h2 class="section-title">Destinations Canadiennes Populaires</h2>
                <p class="section-subtitle">Explorez les régions les plus spectaculaires du Canada. Des montagnes majestueuses aux villes vibrantes, chaque destination offre une expérience unique.</p>
            </div>

            <!-- Filtres -->
            <div class="filters">
                <button class="filter-btn active" data-filter="all">
                    <i class="fas fa-globe-americas"></i> Toutes les régions
                </button>
                <button class="filter-btn" data-filter="west">
                    <i class="fas fa-mountain"></i> Ouest Canadien
                </button>
                <button class="filter-btn" data-filter="east">
                    <i class="fas fa-water"></i> Est Canadien
                </button>
                <button class="filter-btn" data-filter="north">
                    <i class="fas fa-snowflake"></i> Grand Nord
                </button>
                <button class="filter-btn" data-filter="urban">
                    <i class="fas fa-city"></i> Villes
                </button>
            </div>

            <!-- Grille des Destinations -->
            <div class="destinations-grid" id="destinationsGrid">
                <!-- Les cartes seront générées par JavaScript -->
            </div>
        </section>
    </main>


    <!-- Modal Destination -->
    <div class="modal" id="destinationModal">
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
        // Données des destinations canadiennes
        const destinations = [
            {
                id: 1,
                name: "Parc National Banff",
                region: "Alberta, Rocheuses Canadiennes",
                badge: "Populaire",
                filter: "west",
                price: 1299,
                duration: "7 jours",
                rating: 4.9,
                reviews: 342,
                description: "Le parc national Banff, joyau des Rocheuses canadiennes, offre des paysages de montagne à couper le souffle, des lacs turquoise et une faune abondante. Découvrez le célèbre lac Louise, promenez-vous sur le glacier Columbia et profitez des sources thermales naturelles.",
                features: ["Randonnée", "Observation faune", "Photographie", "Ski", "Camping"],
                images: [
                    "https://images.unsplash.com/photo-1503614472-8c93d56e92ce?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80",
                    "https://images.unsplash.com/photo-1506929562872-bb421503ef21?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80",
                    "https://images.unsplash.com/photo-1519677100203-5f3a1d1231c9?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80",
                    "https://images.unsplash.com/photo-1501785888041-af3ef285b470?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80"
                ],
                highlights: [
                    "Lac Louise et ses eaux turquoise",
                    "Promenade sur le glacier Athabasca",
                    "Sources thermales Upper Hot Springs",
                    "Observation des ours, wapitis et orignaux",
                    "Télécabine Banff Gondola avec vue panoramique"
                ],
                includes: ["Hébergement 4 étoiles", "Transport local", "Guide francophone", "Repas sélectionnés", "Activités incluses", "Assurance voyage"]
            },
            {
                id: 2,
                name: "Ville de Québec",
                region: "Québec, Est Canadien",
                badge: "Culturel",
                filter: "east",
                price: 899,
                duration: "5 jours",
                rating: 4.7,
                reviews: 287,
                description: "Plongez dans l'histoire et la culture française en Amérique du Nord. Québec, la seule ville fortifiée d'Amérique du Nord, vous transporte dans le temps avec son architecture européenne, ses rues pavées et sa cuisine raffinée.",
                features: ["Historique", "Gastronomie", "Architecture", "Shopping", "Festivals"],
                images: [
                    "https://images.unsplash.com/photo-1506905925340-14faa3c85743?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80",
                    "https://images.unsplash.com/photo-1606402179428-a57976d238fa?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80",
                    "https://images.unsplash.com/photo-1575910056151-4f2c54379d4c?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80",
                    "https://images.unsplash.com/photo-1548013146-72479768bada?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80"
                ],
                highlights: [
                    "Vieux-Québec et la Place Royale",
                    "Château Frontenac et Terrasse Dufferin",
                    "Plaines d'Abraham et Musée des Beaux-Arts",
                    "Île d'Orléans et ses produits locaux",
                    "Montmorency Falls (plus haute que Niagara)"
                ],
                includes: ["Hôtel historique", "Visites guidées", "Dégustations culinaires", "Transport urbain", "Billet musées", "Carte touristique"]
            },
            {
                id: 3,
                name: "Yukon & Aurores Boréales",
                region: "Territoires du Nord-Ouest",
                badge: "Aventure",
                filter: "north",
                price: 2499,
                duration: "8 jours",
                rating: 4.8,
                reviews: 156,
                description: "Vivez l'expérience ultime du Grand Nord canadien avec cette aventure au Yukon. Découvrez les paysages époustouflants de la toundra, observez la faune arctique et assistez au spectacle magique des aurores boréales.",
                features: ["Aurores boréales", "Chiens de traineau", "Observation faune", "Randonnée hivernale", "Culture autochtone"],
                images: [
                    "https://images.unsplash.com/photo-1502134249126-9f3755a50d78?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80",
                    "https://images.unsplash.com/photo-1519681393784-d120267933ba?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80",
                    "https://images.unsplash.com/photo-1467133019949-91c1f0b56f11?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80",
                    "https://images.unsplash.com/photo-1558618666-fcd25c85cd64?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80"
                ],
                highlights: [
                    "Observation des aurores boréales garantie",
                    "Expédition en chiens de traîneau",
                    "Visite de Whitehorse, capitale du Yukon",
                    "Randonnée dans le parc national Kluane",
                    "Rencontre avec les communautés autochtones"
                ],
                includes: ["Lodge nordique", "Guide expert", "Équipement hivernal", "Tous les repas", "Transport aérien", "Assurance spéciale"]
            },
            {
                id: 4,
                name: "Vancouver & Île de Vancouver",
                region: "Colombie-Britannique",
                badge: "Nature & Ville",
                filter: "west",
                price: 1599,
                duration: "6 jours",
                rating: 4.6,
                reviews: 231,
                description: "Combine l'énergie urbaine de Vancouver avec la nature sauvage de l'île de Vancouver. Des gratte-ciel modernes aux forêts anciennes, des restaurants gastronomiques aux paysages côtiers spectaculaires.",
                features: ["Urbain", "Nature", "Gastronomie", "Villes", "Plages"],
                images: [
                    "https://images.unsplash.com/photo-1559511260-66a654ae982a?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80",
                    "https://images.unsplash.com/photo-1586302392705-8d5b5c60c8cd?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80",
                    "https://images.unsplash.com/photo-1544551763-46a013bb70d5?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80",
                    "https://images.unsplash.com/photo-1578640463628-d4c8d6e88b84?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80"
                ],
                highlights: [
                    "Visite de Stanley Park et Gastown",
                    "Excursion à l'île de Vancouver",
                    "Jardin botanique de Butchart",
                    "Observation des baleines depuis Victoria",
                    "Capilano Suspension Bridge"
                ],
                includes: ["Hôtel 4 étoiles", "Traversier Vancouver-Victoria", "Visites guidées", "Repas sélectionnés", "Transport local", "Entrées attractions"]
            },
            {
                id: 5,
                name: "Toronto & Chutes du Niagara",
                region: "Ontario",
                badge: "Incontournable",
                filter: "urban",
                price: 1099,
                duration: "5 jours",
                rating: 4.5,
                reviews: 312,
                description: "Découvrez la métropole la plus multiculturelle du Canada combinée avec la merveille naturelle la plus célèbre du monde. De la Tour CN aux chutes du Niagara, cette expérience combine urbain et naturel.",
                features: ["Urbain", "Nature", "Shopping", "Gastronomie", "Photographie"],
                images: [
                    "https://images.unsplash.com/photo-1517935706615-2717063c2225?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80",
                    "https://images.unsplash.com/photo-1585730061512-4c10d6b8c8b7?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80",
                    "https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80",
                    "https://images.unsplash.com/photo-1528181304800-259b08848526?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80"
                ],
                highlights: [
                    "Tour CN et vue panoramique sur Toronto",
                    "Excursion aux chutes du Niagara",
                    "Visite du Musée Royal de l'Ontario",
                    "Quartier de Distillery District",
                    "Croisière Hornblower aux chutes"
                ],
                includes: ["Hôtel centre-ville", "Transport Toronto-Niagara", "Billet Tour CN", "Croisière Hornblower", "Guide local", "Petits-déjeuners"]
            },
            {
                id: 6,
                name: "Gaspésie & Parc de la Gaspésie",
                region: "Québec Maritime",
                badge: "Road Trip",
                filter: "east",
                price: 1399,
                duration: "9 jours",
                rating: 4.8,
                reviews: 189,
                description: "Partez sur la route des paysages avec ce road trip épique en Gaspésie. Découvrez des falaises spectaculaires, des villages de pêcheurs pittoresques et une faune marine abondante, dont les baleines et les phoques.",
                features: ["Road trip", "Observation baleines", "Randonnée", "Photographie", "Culture locale"],
                images: [
                    "https://images.unsplash.com/photo-1534008897995-27a23e859048?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80",
                    "https://images.unsplash.com/photo-1551632811-561732d1e306?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80",
                    "https://images.unsplash.com/photo-1507525428034-b723cf961d3e?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80",
                    "https://images.unsplash.com/photo-1505142468610-359e7d316be0?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80"
                ],
                highlights: [
                    "Route 132 et ses panoramas côtiers",
                    "Observation des baleines à Tadoussac",
                    "Rocher Percé et île Bonaventure",
                    "Parc national de la Gaspésie et monts Chic-Chocs",
                    "Villages de pêcheurs authentiques"
                ],
                includes: ["Location voiture", "Hébergement caractère", "Croisière baleines", "Guide routier", "Repas sélectionnés", "Carte détaillée"]
            }
        ];

        // Initialisation
        document.addEventListener('DOMContentLoaded', function() {
            renderDestinations();
            setupFilters();
            setupSearch();
            setupModal();
            
            // Animation des cartes au chargement
            animateCardsOnLoad();
        });

        // Rendu des destinations
        function renderDestinations(filter = 'all') {
            const grid = document.getElementById('destinationsGrid');
            grid.innerHTML = '';
            
            const filteredDestinations = filter === 'all' 
                ? destinations 
                : destinations.filter(d => d.filter === filter);
            
            filteredDestinations.forEach((destination, index) => {
                const card = createDestinationCard(destination, index);
                grid.appendChild(card);
            });
            
            // Réappliquer les animations
            setTimeout(() => {
                animateCardsOnLoad();
            }, 100);
        }

        // Création d'une carte destination
        function createDestinationCard(destination, index) {
            const card = document.createElement('div');
            card.className = 'destination-card';
            card.style.animationDelay = `${index * 0.1}s`;
            card.setAttribute('data-id', destination.id);
            card.setAttribute('data-filter', destination.filter);
            
            // Générer les étoiles
            const stars = generateStars(destination.rating);
            
            // Générer les tags de features
            const featuresHTML = destination.features.map(feature => 
                `<span class="feature-tag">${feature}</span>`
            ).join('');
            
            card.innerHTML = `
                <div class="card-badge">${destination.badge}</div>
                <div class="card-image">
                    <img src="${destination.images[0]}" alt="${destination.name}">
                    <div class="card-overlay">
                        <button class="card-view-btn" data-id="${destination.id}">
                            <i class="fas fa-expand"></i> Voir détails
                        </button>
                    </div>
                </div>
                <div class="card-content">
                    <div class="card-header">
                        <h3 class="card-title">${destination.name}</h3>
                        <div class="card-rating">
                            ${stars}
                            <span>${destination.rating}</span>
                        </div>
                    </div>
                    <p class="card-description">${destination.description.substring(0, 120)}...</p>
                    <div class="card-features">
                        ${featuresHTML}
                    </div>
                    <div class="card-footer">
                        <div class="card-price">
                            À partir de <br> <span>${destination.price}€</span>
                            <div style="font-size: 0.9rem; color: var(--gray); margin-top: 5px;">${destination.duration}</div>
                        </div>
                        <button class="card-book-btn" data-id="${destination.id}">
                            <i class="fas fa-calendar-check"></i> Réserver
                        </button>
                    </div>
                </div>
            `;
            
            return card;
        }

        // Générer les étoiles
        function generateStars(rating) {
            let stars = '';
            const fullStars = Math.floor(rating);
            const hasHalfStar = rating % 1 >= 0.3 && rating % 1 <= 0.7;
            const hasAlmostFull = rating % 1 > 0.7;
            
            for (let i = 0; i < fullStars; i++) {
                stars += '<i class="fas fa-star"></i>';
            }
            
            if (hasHalfStar) {
                stars += '<i class="fas fa-star-half-alt"></i>';
            } else if (hasAlmostFull) {
                stars += '<i class="fas fa-star"></i>';
            }
            
            const emptyStars = 5 - fullStars - (hasHalfStar || hasAlmostFull ? 1 : 0);
            for (let i = 0; i < emptyStars; i++) {
                stars += '<i class="far fa-star"></i>';
            }
            
            return stars;
        }

        // Configuration des filtres
        function setupFilters() {
            document.querySelectorAll('.filter-btn').forEach(button => {
                button.addEventListener('click', function() {
                    // Mettre à jour le bouton actif
                    document.querySelectorAll('.filter-btn').forEach(btn => {
                        btn.classList.remove('active');
                    });
                    this.classList.add('active');
                    
                    // Appliquer le filtre
                    const filter = this.getAttribute('data-filter');
                    renderDestinations(filter);
                });
            });
        }

        // Configuration de la recherche
        function setupSearch() {
            const searchInput = document.querySelector('.search-input');
            const searchBtn = document.querySelector('.search-btn');
            
            searchBtn.addEventListener('click', performSearch);
            searchInput.addEventListener('keyup', function(e) {
                if (e.key === 'Enter') {
                    performSearch();
                }
            });
            
            function performSearch() {
                const query = searchInput.value.toLowerCase();
                if (query.trim() === '') return;
                
                const filtered = destinations.filter(d => 
                    d.name.toLowerCase().includes(query) || 
                    d.region.toLowerCase().includes(query) ||
                    d.description.toLowerCase().includes(query) ||
                    d.features.some(f => f.toLowerCase().includes(query))
                );
                
                // Mettre à jour l'affichage
                const grid = document.getElementById('destinationsGrid');
                grid.innerHTML = '';
                
                if (filtered.length === 0) {
                    grid.innerHTML = `
                        <div style="grid-column: 1/-1; text-align: center; padding: 60px;">
                            <i class="fas fa-search" style="font-size: 4rem; color: var(--gray); margin-bottom: 20px;"></i>
                            <h3 style="color: var(--dark); margin-bottom: 15px;">Aucun résultat trouvé</h3>
                            <p style="color: var(--gray);">Essayez d'autres termes de recherche</p>
                        </div>
                    `;
                } else {
                    filtered.forEach((destination, index) => {
                        const card = createDestinationCard(destination, index);
                        grid.appendChild(card);
                    });
                    
                    // Réappliquer les animations
                    setTimeout(() => {
                        animateCardsOnLoad();
                        setupCardInteractions();
                    }, 100);
                }
            }
        }

        // Configuration de la modale
        function setupModal() {
            const modal = document.getElementById('destinationModal');
            const closeModal = document.getElementById('closeModal');
            const modalBody = document.getElementById('modalBody');
            
            // Fermer la modale
            closeModal.addEventListener('click', closeModalFunction);
            modal.addEventListener('click', (e) => {
                if (e.target === modal) {
                    closeModalFunction();
                }
            });
            
            // Fermer avec Échap
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && modal.style.display === 'flex') {
                    closeModalFunction();
                }
            });
            
            function closeModalFunction() {
                modal.style.display = 'none';
                document.body.style.overflow = 'auto';
            }
            
            // Ouvrir la modale depuis les cartes
            document.addEventListener('click', function(e) {
                if (e.target.closest('.card-view-btn')) {
                    const id = parseInt(e.target.closest('.card-view-btn').getAttribute('data-id'));
                    openModal(id);
                }
                
                if (e.target.closest('.card-book-btn')) {
                    const id = parseInt(e.target.closest('.card-book-btn').getAttribute('data-id'));
                    const destination = destinations.find(d => d.id === id);
                    if (destination) {
                        showBookingNotification(destination);
                    }
                }
            });
        }

        // Ouvrir la modale
        function openModal(destinationId) {
            const destination = destinations.find(d => d.id === destinationId);
            if (!destination) return;
            
            const modal = document.getElementById('destinationModal');
            const modalBody = document.getElementById('modalBody');
            
            // Générer les étoiles
            const stars = generateStars(destination.rating);
            
            // Générer les highlights
            const highlightsHTML = destination.highlights.map(highlight => 
                `<li><i class="fas fa-check-circle"></i> ${highlight}</li>`
            ).join('');
            
            // Générer les inclusions
            const includesHTML = destination.includes.map(item => 
                `<div class="include-item"><i class="fas fa-check"></i> ${item}</div>`
            ).join('');
            
            // Générer les miniatures
            const thumbnails = destination.images.map((img, index) => 
                `<img src="${img}" alt="Vue ${index + 1}" class="modal-thumbnail ${index === 0 ? 'active' : ''}" data-index="${index}">`
            ).join('');
            
            // Injecter le contenu
            modalBody.innerHTML = `
                <div class="modal-gallery">
                    <img src="${destination.images[0]}" alt="${destination.name}" class="modal-main-image" id="modalMainImage">
                    <div class="modal-thumbnails">
                        ${thumbnails}
                    </div>
                </div>
                <div class="modal-info">
                    <span class="modal-badge">${destination.badge}</span>
                    <span class="modal-region">${destination.region}</span>
                    <h2 class="modal-title">${destination.name}</h2>
                    
                    <div class="modal-rating">
                        <div class="modal-rating-stars">${stars}</div>
                        <span class="modal-rating-text">${destination.rating}/5 (${destination.reviews} avis)</span>
                    </div>
                    
                    <p class="modal-description">${destination.description}</p>
                    
                    <div class="modal-highlights">
                        <h3><i class="fas fa-map-marker-alt"></i> Points Forts</h3>
                        <ul>${highlightsHTML}</ul>
                    </div>
                    
                    <div class="modal-details">
                        <div class="modal-price-section">
                            <div class="modal-price">
                                ${destination.price}€
                                <span>par personne</span>
                            </div>
                            <div class="modal-duration">${destination.duration}</div>
                        </div>
                        <div class="modal-includes">
                            ${includesHTML}
                        </div>
                    </div>
                    
                    <div class="modal-actions">
                        <button class="modal-book-btn" data-id="${destination.id}">
                            <i class="fas fa-calendar-check"></i> Réserver maintenant
                        </button>
                        <button class="modal-wishlist">
                            <i class="far fa-heart"></i> Ajouter aux favoris
                        </button>
                    </div>
                </div>
            `;
            
            // Afficher la modale
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
            
            // Configurer les miniatures
            setupModalThumbnails();
            
            // Configurer les boutons dans la modale
            setupModalButtons(destination);
        }

        // Configurer les miniatures dans la modale
        function setupModalThumbnails() {
            const mainImage = document.getElementById('modalMainImage');
            const thumbnails = document.querySelectorAll('.modal-thumbnail');
            
            thumbnails.forEach(thumbnail => {
                thumbnail.addEventListener('click', function() {
                    // Mettre à jour l'image principale
                    mainImage.src = this.src;
                    
                    // Mettre à jour les miniatures actives
                    thumbnails.forEach(t => t.classList.remove('active'));
                    this.classList.add('active');
                    
                    // Animation de transition
                    mainImage.style.opacity = '0.5';
                    setTimeout(() => {
                        mainImage.style.opacity = '1';
                    }, 150);
                });
            });
        }

        // Configurer les boutons dans la modale
        function setupModalButtons(destination) {
            // Bouton de réservation
            document.querySelector('.modal-book-btn').addEventListener('click', function() {
                showBookingNotification(destination);
            });
            
            // Bouton favoris
            document.querySelector('.modal-wishlist').addEventListener('click', function() {
                const icon = this.querySelector('i');
                if (icon.classList.contains('far')) {
                    icon.classList.remove('far');
                    icon.classList.add('fas');
                    this.innerHTML = '<i class="fas fa-heart"></i> Retirer des favoris';
                    showNotification(`${destination.name} ajouté aux favoris`, 'success');
                } else {
                    icon.classList.remove('fas');
                    icon.classList.add('far');
                    this.innerHTML = '<i class="far fa-heart"></i> Ajouter aux favoris';
                    showNotification(`${destination.name} retiré des favoris`, 'info');
                }
            });
        }

        // Afficher une notification de réservation
        function showBookingNotification(destination) {
            const notification = document.createElement('div');
            notification.style.cssText = `
                position: fixed;
                top: 30px;
                right: 30px;
                background: linear-gradient(135deg, var(--primary), var(--secondary));
                color: white;
                padding: 22px 30px;
                border-radius: var(--border-radius);
                box-shadow: var(--shadow-xl);
                z-index: 3000;
                font-weight: 700;
                display: flex;
                align-items: center;
                gap: 15px;
                animation: slideInRight 0.4s ease;
                max-width: 400px;
            `;
            
            notification.innerHTML = `
                <i class="fas fa-check-circle" style="font-size: 2rem;"></i>
                <div>
                    <div style="font-size: 1.2rem; margin-bottom: 5px;">Réservation en cours</div>
                    <div style="font-size: 0.95rem; opacity: 0.9;">${destination.name} - ${destination.price}€</div>
                </div>
            `;
            
            document.body.appendChild(notification);
            
            // Ajouter l'animation CSS
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
            
            // Supprimer après 5 secondes
            setTimeout(() => {
                notification.style.animation = 'slideOutRight 0.4s ease';
                setTimeout(() => {
                    if (notification.parentNode) {
                        document.body.removeChild(notification);
                    }
                }, 400);
            }, 5000);
        }

        // Afficher une notification générale
        function showNotification(message, type = 'success') {
            const colors = {
                success: 'linear-gradient(135deg, var(--secondary), var(--secondary-light))',
                info: 'linear-gradient(135deg, var(--primary), var(--primary-light))',
                warning: 'linear-gradient(135deg, #f59e0b, #fbbf24)'
            };
            
            const notification = document.createElement('div');
            notification.style.cssText = `
                position: fixed;
                top: 30px;
                right: 30px;
                background: ${colors[type]};
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
            
            // Supprimer après 3 secondes
            setTimeout(() => {
                notification.style.animation = 'slideOutRight 0.4s ease';
                setTimeout(() => {
                    if (notification.parentNode) {
                        document.body.removeChild(notification);
                    }
                }, 400);
            }, 3000);
        }

        // Animation des cartes au chargement
        function animateCardsOnLoad() {
            const cards = document.querySelectorAll('.destination-card');
            cards.forEach((card, index) => {
                card.style.animation = 'fadeInUp 0.6s ease forwards';
                card.style.animationDelay = `${index * 0.1}s`;
                
                // Configurer les interactions
                card.querySelector('.card-view-btn').addEventListener('click', function() {
                    const id = parseInt(this.getAttribute('data-id'));
                    openModal(id);
                });
                
                card.querySelector('.card-book-btn').addEventListener('click', function() {
                    const id = parseInt(this.getAttribute('data-id'));
                    const destination = destinations.find(d => d.id === id);
                    if (destination) {
                        showBookingNotification(destination);
                    }
                });
            });
        }

        // Configurer les interactions des cartes
        function setupCardInteractions() {
            document.querySelectorAll('.destination-card').forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-15px)';
                    this.style.boxShadow = 'var(--shadow-xl)';
                });
                
                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                    this.style.boxShadow = 'var(--shadow-md)';
                });
            });
        }
    </script>
    <script>
function sendHeight() {
    const height = document.body.scrollHeight;
    window.parent.postMessage({
        type: 'setHeight',
        iframeId: 'iframe-page-travel-1',
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