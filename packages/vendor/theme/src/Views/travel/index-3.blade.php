<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Explore Canada | Expériences de Voyage Premium</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #0066CC;
            --primary-dark: #004C99;
            --primary-light: #3399FF;
            --secondary: #FF3366;
            --accent: #00CC99;
            --dark: #0F1C2D;
            --dark-light: #1A2B3C;
            --light: #F8FAFF;
            --glass: rgba(255, 255, 255, 0.08);
            --glass-border: rgba(255, 255, 255, 0.1);
            --shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            --shadow-light: 0 5px 20px rgba(0, 0, 0, 0.1);
            --transition-slow: all 0.6s cubic-bezier(0.4, 0, 0.2, 1);
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            --gradient: linear-gradient(135deg, var(--primary), var(--accent));
            --gradient-dark: linear-gradient(135deg, var(--dark), var(--dark-light));
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--dark);
            color: var(--light);
            overflow-x: hidden;
            line-height: 1.6;
        }

        h1, h2, h3, h4, h5 {
            font-family: 'Poppins', sans-serif;
            font-weight: 700;
            line-height: 1.2;
        }

        /* Background Animé */
        .bg-animation {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -2;
            background: var(--gradient-dark);
            overflow: hidden;
        }

        .bg-circle {
            position: absolute;
            border-radius: 50%;
            background: radial-gradient(circle, var(--primary-light), transparent);
            opacity: 0.15;
            animation: float 20s infinite ease-in-out;
        }

        .bg-circle:nth-child(1) {
            width: 300px;
            height: 300px;
            top: 10%;
            left: 5%;
            animation-delay: 0s;
        }

        .bg-circle:nth-child(2) {
            width: 200px;
            height: 200px;
            top: 60%;
            right: 10%;
            animation-delay: 5s;
        }

        .bg-circle:nth-child(3) {
            width: 150px;
            height: 150px;
            bottom: 10%;
            left: 20%;
            animation-delay: 10s;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 30px;
        }

        /* Header */
        header {
            padding: 30px 0;
            position: relative;
            z-index: 100;
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-family: 'Poppins', sans-serif;
            font-size: 1.8rem;
            font-weight: 800;
            background: var(--gradient);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            display: flex;
            align-items: center;
        }

        .logo i {
            margin-right: 10px;
            font-size: 2rem;
        }

        .nav-links {
            display: flex;
            gap: 40px;
        }

        .nav-link {
            color: var(--light);
            text-decoration: none;
            font-weight: 500;
            position: relative;
            padding: 8px 0;
            transition: var(--transition);
        }

        .nav-link:after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: 0;
            left: 0;
            background: var(--gradient);
            transition: var(--transition);
        }

        .nav-link:hover {
            color: var(--primary-light);
        }

        .nav-link:hover:after {
            width: 100%;
        }

        .header-actions {
            display: flex;
            gap: 20px;
            align-items: center;
        }

        .btn-account {
            background: var(--glass);
            border: 1px solid var(--glass-border);
            backdrop-filter: blur(10px);
            color: var(--light);
            padding: 10px 25px;
            border-radius: 50px;
            font-weight: 500;
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .btn-account:hover {
            background: rgba(255, 255, 255, 0.15);
            transform: translateY(-2px);
        }

        /* Hero Section */
        .hero {
            padding: 100px 0 150px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .hero h1 {
            font-size: 4rem;
            margin-bottom: 20px;
            background: linear-gradient(90deg, #fff, var(--primary-light), var(--accent));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            animation: textShine 3s ease-in-out infinite alternate;
        }

        .hero p {
            font-size: 1.3rem;
            max-width: 700px;
            margin: 0 auto 50px;
            color: rgba(255, 255, 255, 0.8);
        }

        .search-bar {
            max-width: 800px;
            margin: 0 auto;
            background: var(--glass);
            border: 1px solid var(--glass-border);
            backdrop-filter: blur(15px);
            border-radius: 20px;
            padding: 10px;
            display: flex;
            box-shadow: var(--shadow);
            transition: var(--transition);
        }

        .search-bar:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.3);
        }

        .search-input {
            flex: 1;
            background: transparent;
            border: none;
            padding: 20px;
            color: var(--light);
            font-size: 1.1rem;
            outline: none;
        }

        .search-input::placeholder {
            color: rgba(255, 255, 255, 0.5);
        }

        .btn-search {
            background: var(--gradient);
            color: white;
            border: none;
            padding: 0 40px;
            border-radius: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .btn-search:hover {
            transform: scale(1.05);
            box-shadow: 0 10px 25px rgba(0, 102, 204, 0.4);
        }

        /* Section Destinations */
        .section-title {
            margin-bottom: 60px;
            position: relative;
            display: inline-block;
        }

        .section-title h2 {
            font-size: 2.8rem;
            position: relative;
            z-index: 1;
        }

        .section-title:after {
            content: '';
            position: absolute;
            width: 100%;
            height: 10px;
            background: var(--gradient);
            bottom: 10px;
            left: 0;
            opacity: 0.3;
            border-radius: 5px;
        }

        .filters {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 50px;
        }

        .filter-btn {
            padding: 12px 30px;
            background: var(--glass);
            border: 1px solid var(--glass-border);
            backdrop-filter: blur(10px);
            color: var(--light);
            border-radius: 50px;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }

        .filter-btn:before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: var(--gradient);
            transition: var(--transition);
            z-index: -1;
        }

        .filter-btn:hover:before, .filter-btn.active:before {
            left: 0;
        }

        .filter-btn:hover, .filter-btn.active {
            border-color: transparent;
            transform: translateY(-3px);
        }

        /* Grid Destinations */
        .destinations-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
            gap: 30px;
            margin-bottom: 100px;
        }

        .destination-card {
            background: var(--glass);
            border: 1px solid var(--glass-border);
            backdrop-filter: blur(15px);
            border-radius: 20px;
            overflow: hidden;
            transition: var(--transition);
            position: relative;
            opacity: 0;
            transform: translateY(30px);
        }

        .destination-card.visible {
            opacity: 1;
            transform: translateY(0);
        }

        .destination-card:hover {
            transform: translateY(-15px);
            border-color: var(--primary-light);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.3);
        }

        .card-image {
            height: 250px;
            width: 100%;
            overflow: hidden;
            position: relative;
        }

        .card-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: var(--transition-slow);
        }

        .destination-card:hover .card-image img {
            transform: scale(1.1);
        }

        .card-badge {
            position: absolute;
            top: 20px;
            right: 20px;
            background: var(--secondary);
            color: white;
            padding: 8px 20px;
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: 700;
            box-shadow: 0 5px 15px rgba(255, 51, 102, 0.4);
        }

        .card-content {
            padding: 25px;
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 15px;
        }

        .card-header h3 {
            font-size: 1.5rem;
            margin-right: 15px;
        }

        .card-price {
            font-size: 1.8rem;
            font-weight: 800;
            background: var(--gradient);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            white-space: nowrap;
        }

        .card-price span {
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.6);
            font-weight: 500;
        }

        .card-location {
            display: flex;
            align-items: center;
            color: rgba(255, 255, 255, 0.7);
            margin-bottom: 20px;
            font-size: 0.95rem;
        }

        .card-location i {
            margin-right: 10px;
            color: var(--accent);
        }

        .card-description {
            margin-bottom: 25px;
            color: rgba(255, 255, 255, 0.8);
            line-height: 1.7;
        }

        .card-features {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 25px;
        }

        .feature {
            background: rgba(0, 102, 204, 0.2);
            padding: 6px 15px;
            border-radius: 50px;
            font-size: 0.85rem;
            color: var(--primary-light);
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .card-actions {
            display: flex;
            gap: 15px;
        }

        .btn-outline, .btn-primary {
            padding: 12px 25px;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            border: none;
            font-family: 'Inter', sans-serif;
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .btn-outline {
            background: transparent;
            border: 2px solid var(--primary);
            color: var(--primary-light);
        }

        .btn-outline:hover {
            background: rgba(0, 102, 204, 0.1);
            transform: translateY(-3px);
        }

        .btn-primary {
            background: var(--gradient);
            color: white;
            box-shadow: 0 5px 20px rgba(0, 102, 204, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 10px 30px rgba(0, 102, 204, 0.5);
        }

        /* Video Showcase */
        .video-showcase {
            margin: 100px 0;
            border-radius: 25px;
            overflow: hidden;
            position: relative;
            box-shadow: var(--shadow);
        }

        .video-container {
            position: relative;
            width: 100%;
            height: 500px;
            overflow: hidden;
        }

        .video-container video {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .video-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(15, 28, 45, 0.7);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            padding: 0 20px;
        }

        .video-overlay h2 {
            font-size: 3rem;
            margin-bottom: 20px;
        }

        .video-overlay p {
            font-size: 1.2rem;
            max-width: 700px;
            margin-bottom: 40px;
            color: rgba(255, 255, 255, 0.9);
        }

        .play-btn {
            width: 80px;
            height: 80px;
            background: var(--gradient);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: var(--transition);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
        }

        .play-btn:hover {
            transform: scale(1.1);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.6);
        }

        .play-btn i {
            font-size: 2rem;
            color: white;
            margin-left: 5px;
        }

        /* Modal */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.85);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 2000;
            opacity: 0;
            visibility: hidden;
            transition: var(--transition-slow);
            backdrop-filter: blur(10px);
        }

        .modal-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        .modal {
            background: var(--dark-light);
            border-radius: 25px;
            width: 90%;
            max-width: 1000px;
            max-height: 90vh;
            overflow-y: auto;
            position: relative;
            transform: scale(0.8) translateY(50px);
            opacity: 0;
            transition: var(--transition-slow);
            border: 1px solid var(--glass-border);
            box-shadow: 0 30px 80px rgba(0, 0, 0, 0.5);
        }

        .modal-overlay.active .modal {
            transform: scale(1) translateY(0);
            opacity: 1;
        }

        .modal-close {
            position: absolute;
            top: 25px;
            right: 25px;
            background: rgba(255, 255, 255, 0.1);
            border: none;
            width: 45px;
            height: 45px;
            border-radius: 50%;
            font-size: 1.5rem;
            color: white;
            cursor: pointer;
            transition: var(--transition);
            z-index: 10;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-close:hover {
            background: var(--secondary);
            transform: rotate(90deg);
        }

        .modal-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            min-height: 600px;
        }

        .modal-media {
            position: relative;
            overflow: hidden;
            border-radius: 25px 0 0 25px;
        }

        .modal-media img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .modal-media .video-btn {
            position: absolute;
            bottom: 30px;
            right: 30px;
            background: var(--gradient);
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 50px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: var(--transition);
        }

        .modal-media .video-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
        }

        .modal-info {
            padding: 40px;
            overflow-y: auto;
        }

        .modal-info h3 {
            font-size: 2.2rem;
            margin-bottom: 10px;
        }

        .modal-location {
            display: flex;
            align-items: center;
            margin-bottom: 25px;
            color: rgba(255, 255, 255, 0.8);
        }

        .modal-location i {
            margin-right: 10px;
            color: var(--accent);
        }

        .modal-description {
            margin-bottom: 30px;
            line-height: 1.8;
            color: rgba(255, 255, 255, 0.9);
        }

        .modal-highlights {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 30px;
        }

        .highlight {
            background: rgba(255, 255, 255, 0.05);
            padding: 15px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .highlight i {
            color: var(--accent);
            font-size: 1.2rem;
        }

        .modal-price {
            font-size: 2.5rem;
            font-weight: 800;
            background: var(--gradient);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            margin-bottom: 30px;
        }

        .modal-price span {
            font-size: 1rem;
            color: rgba(255, 255, 255, 0.6);
            font-weight: 500;
        }

        .booking-form {
            background: rgba(255, 255, 255, 0.05);
            padding: 25px;
            border-radius: 15px;
            margin-top: 25px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.9);
        }

        .form-group input, .form-group select {
            width: 100%;
            padding: 15px 20px;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            color: white;
            font-family: 'Inter', sans-serif;
            font-size: 1rem;
            transition: var(--transition);
        }

        .form-group input:focus, .form-group select:focus {
            outline: none;
            border-color: var(--primary);
            background: rgba(255, 255, 255, 0.12);
        }

        .btn-submit {
            background: var(--gradient);
            color: white;
            border: none;
            padding: 18px;
            border-radius: 12px;
            font-weight: 700;
            cursor: pointer;
            transition: var(--transition);
            width: 100%;
            font-size: 1.1rem;
            margin-top: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .btn-submit:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(0, 102, 204, 0.5);
        }

        /* Footer */
        footer {
            background: var(--dark-light);
            padding: 80px 0 40px;
            margin-top: 100px;
            position: relative;
            overflow: hidden;
        }

        .footer-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 50px;
            margin-bottom: 60px;
        }

        .footer-section h3 {
            font-size: 1.5rem;
            margin-bottom: 25px;
            position: relative;
            display: inline-block;
        }

        .footer-section h3:after {
            content: '';
            position: absolute;
            width: 40px;
            height: 4px;
            background: var(--gradient);
            bottom: -8px;
            left: 0;
            border-radius: 2px;
        }

        .footer-links {
            list-style: none;
        }

        .footer-links li {
            margin-bottom: 12px;
        }

        .footer-links a {
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .footer-links a:hover {
            color: var(--primary-light);
            transform: translateX(5px);
        }

        .social-links {
            display: flex;
            gap: 15px;
            margin-top: 25px;
        }

        .social-link {
            width: 45px;
            height: 45px;
            background: rgba(255, 255, 255, 0.08);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            transition: var(--transition);
        }

        .social-link:hover {
            background: var(--gradient);
            transform: translateY(-5px);
        }

        .newsletter-form {
            margin-top: 20px;
        }

        .newsletter-input {
            width: 100%;
            padding: 15px;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            color: white;
            margin-bottom: 15px;
        }

        .copyright {
            text-align: center;
            padding-top: 30px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            color: rgba(255, 255, 255, 0.5);
            font-size: 0.9rem;
        }

        /* Animations */
        @keyframes float {
            0%, 100% { transform: translateY(0) translateX(0); }
            33% { transform: translateY(-30px) translateX(20px); }
            66% { transform: translateY(20px) translateX(-20px); }
        }

        @keyframes textShine {
            0% { background-position: 0% 50%; }
            100% { background-position: 100% 50%; }
        }

        /* Responsive */
        @media (max-width: 1200px) {
            .hero h1 {
                font-size: 3.2rem;
            }
            
            .destinations-grid {
                grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            }
        }

        @media (max-width: 992px) {
            .modal-content {
                grid-template-columns: 1fr;
            }
            
            .modal-media {
                border-radius: 25px 25px 0 0;
                height: 300px;
            }
            
            .nav-links {
                display: none;
            }
            
            .hero h1 {
                font-size: 2.8rem;
            }
            
            .video-overlay h2 {
                font-size: 2.5rem;
            }
        }

        @media (max-width: 768px) {
            .hero {
                padding: 60px 0 100px;
            }
            
            .hero h1 {
                font-size: 2.2rem;
            }
            
            .hero p {
                font-size: 1.1rem;
                padding: 0 20px;
            }
            
            .search-bar {
                flex-direction: column;
                gap: 15px;
                padding: 20px;
            }
            
            .destinations-grid {
                grid-template-columns: 1fr;
            }
            
            .section-title h2 {
                font-size: 2.2rem;
            }
            
            .video-overlay h2 {
                font-size: 2rem;
            }
            
            .video-overlay p {
                font-size: 1rem;
            }
            
            .modal-info {
                padding: 25px;
            }
            
            .modal-info h3 {
                font-size: 1.8rem;
            }
        }

        @media (max-width: 576px) {
            .container {
                padding: 0 20px;
            }
            
            .hero h1 {
                font-size: 1.8rem;
            }
            
            .card-actions {
                flex-direction: column;
            }
            
            .modal-highlights {
                grid-template-columns: 1fr;
            }
            
            .footer-content {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Background Animation -->
    <div class="bg-animation">
        <div class="bg-circle"></div>
        <div class="bg-circle"></div>
        <div class="bg-circle"></div>
    </div>


    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <h1>Découvrez la Beauté Sauvage du Canada</h1>
            <p>Explorez des paysages époustouflants, des aventures inoubliables et des expériences authentiques dans les régions les plus spectaculaires du Canada.</p>
            <div class="search-bar">
                <input type="text" class="search-input" placeholder="Où voulez-vous aller?">
                <input type="text" class="search-input" placeholder="Quand?">
                <button class="btn-search">
                    <i class="fas fa-search"></i>
                    Explorer
                </button>
            </div>
        </div>
    </section>

    <!-- Destinations Section -->
    <section class="destinations" id="destinations">
        <div class="container">
            <div class="section-title">
                <h2>Destinations Premium</h2>
            </div>
            
            <div class="filters">
                <button class="filter-btn active" data-filter="all">Tout voir</button>
                <button class="filter-btn" data-filter="ouest">Ouest Canadien</button>
                <button class="filter-btn" data-filter="est">Est Canadien</button>
                <button class="filter-btn" data-filter="nord">Aventures Nordiques</button>
                <button class="filter-btn" data-filter="luxe">Expériences Luxe</button>
            </div>

            <!-- Destinations Grid -->
            <div class="destinations-grid" id="destinations-container">
                <!-- Cards will be generated by JavaScript -->
            </div>
        </div>
    </section>

    <!-- Video Showcase -->
    <section class="video-showcase" id="experiences">
        <div class="video-container">
            <video autoplay muted loop poster="https://images.unsplash.com/photo-1503614472-8c93d56e92ce?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80">
                <source src="https://assets.mixkit.co/videos/preview/mixkit-aerial-view-of-a-river-in-a-forest-41537-large.mp4" type="video/mp4">
            </video>
            <div class="video-overlay">
                <h2>Vivez l'Expérience</h2>
                <p>Découvrez nos vidéos exclusives capturées par nos experts sur le terrain. Plongez au cœur des paysages canadiens avant même de réserver votre voyage.</p>
                <div class="play-btn">
                    <i class="fas fa-play"></i>
                </div>
            </div>
        </div>
    </section>

    <!-- Modal -->
    <div class="modal-overlay" id="modal">
        <div class="modal">
            <button class="modal-close" id="close-modal">
                <i class="fas fa-times"></i>
            </button>
            <div class="modal-content" id="modal-content">
                <!-- Content will be injected by JavaScript -->
            </div>
        </div>
    </div>


    <script>
        // Données des destinations premium
        const destinations = [
            {
                id: 1,
                title: "Expédition Banff Luxe",
                region: "luxe",
                location: "Parc national Banff, Alberta",
                description: "Expérience exclusive dans les Rocheuses canadiennes avec hébergement 5 étoiles, guide privé et activités sur mesure. Découvrez le lac Louise, les sources thermales et la faune locale dans le plus grand confort.",
                price: 3499,
                image: "https://images.unsplash.com/photo-1503614472-8c93d56e92ce?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2011&q=80",
                video: "https://www.youtube.com/embed/InyPq6cR5zs",
                highlights: ["Hébergement 5 étoiles", "Guide privé", "Spa en montagne", "Restaurant gastronomique"],
                duration: "5 jours / 4 nuits",
                season: "Mai à Octobre",
                features: ["Luxe", "Aventure", "Nature"]
            },
            {
                id: 2,
                title: "Aurores Boréales Yukon",
                region: "nord",
                location: "Whitehorse, Yukon",
                description: "Chasse aux aurores boréales avec photographe professionnel, expérience en chiens de traîneau et découverte de la culture autochtone dans le Grand Nord canadien.",
                price: 2899,
                image: "https://images.unsplash.com/photo-1578662996442-48f60103fc96?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80",
                video: "https://www.youtube.com/embed/fVsONlc3OUY",
                highlights: ["Aurores boréales", "Chiens de traîneau", "Photographie", "Culture autochtone"],
                duration: "6 jours / 5 nuits",
                season: "Septembre à Mars",
                features: ["Unique", "Aventure", "Photographie"]
            },
            {
                id: 3,
                title: "Aventure Île de Vancouver",
                region: "ouest",
                location: "Vancouver Island, Colombie-Britannique",
                description: "Observation des baleines, randonnées dans la forêt tropicale et surf sur les plages sauvages du Pacifique. Expérience complète de la côte ouest canadienne.",
                price: 2199,
                image: "https://images.unsplash.com/photo-1544551763-46a013bb70d5?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80",
                video: "https://www.youtube.com/embed/7F2TMSx0R30",
                highlights: ["Observation baleines", "Forêt tropicale", "Surf à Tofino", "Kayak côtier"],
                duration: "7 jours / 6 nuits",
                season: "Avril à Octobre",
                features: ["Faune", "Plage", "Aventure"]
            },
            {
                id: 4,
                title: "Évasion Montréal & Québec",
                region: "est",
                location: "Québec, Canada",
                description: "Immersion culturelle dans les villes historiques du Québec. Découvrez la vieille ville de Québec classée à l'UNESCO, la scène gastronomique de Montréal et les paysages pittoresques de Charlevoix.",
                price: 1899,
                image: "https://images.unsplash.com/photo-1548191657-6aa4c4c8e0e9?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2069&q=80",
                video: "https://www.youtube.com/embed/moqj5wF6OeY",
                highlights: ["Vieux-Québec", "Gastronomie", "Histoire", "Culture française"],
                duration: "6 jours / 5 nuits",
                season: "Toute l'année",
                features: ["Culture", "Ville", "Gastronomie"]
            },
            {
                id: 5,
                title: "Safari Faune du Nord",
                region: "nord",
                location: "Churchill, Manitoba",
                description: "Observation des ours polaires dans leur habitat naturel, découverte de la toundra arctique et expérience unique dans la capitale mondiale des ours polaires.",
                price: 4599,
                image: "https://images.unsplash.com/photo-1521651201144-634f700b36ef?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80",
                video: "https://www.youtube.com/embed/7F2TMSx0R30",
                highlights: ["Ours polaires", "Toundra arctique", "Expédition", "Photographie"],
                duration: "5 jours / 4 nuits",
                season: "Octobre à Novembre",
                features: ["Faune", "Unique", "Aventure"]
            },
            {
                id: 6,
                title: "Retraite Wellness Whistler",
                region: "luxe",
                location: "Whistler, Colombie-Britannique",
                description: "Combinaison parfaite d'aventure en plein air et de bien-être. Randonnée, spa de luxe, yoga en montagne et gastronomie locale dans la station de renommée mondiale.",
                price: 2799,
                image: "https://images.unsplash.com/photo-1598894597315-b2f546e10a4a?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80",
                video: "https://www.youtube.com/embed/InyPq6cR5zs",
                highlights: ["Spa luxueux", "Yoga en montagne", "Gastronomie", "Randonnée"],
                duration: "5 jours / 4 nuits",
                season: "Toute l'année",
                features: ["Luxe", "Wellness", "Montagne"]
            }
        ];

        // Éléments DOM
        const destinationsContainer = document.getElementById('destinations-container');
        const filterButtons = document.querySelectorAll('.filter-btn');
        const modal = document.getElementById('modal');
        const closeModal = document.getElementById('close-modal');
        const modalContent = document.getElementById('modal-content');
        const playBtn = document.querySelector('.play-btn');
        const videoOverlay = document.querySelector('.video-overlay');

        // Variables d'état
        let currentFilter = 'all';
        let currentDestination = null;

        // Initialisation
        document.addEventListener('DOMContentLoaded', function() {
            renderDestinations(destinations);
            setupFilters();
            setupModal();
            setupAnimations();
            setupVideo();
        });

        // Rendu des destinations
        function renderDestinations(destinationsArray) {
            destinationsContainer.innerHTML = '';
            
            destinationsArray.forEach(destination => {
                const card = document.createElement('div');
                card.className = `destination-card ${destination.region}`;
                card.dataset.id = destination.id;
                
                card.innerHTML = `
                    <div class="card-image">
                        <img src="${destination.image}" alt="${destination.title}" loading="lazy">
                        <div class="card-badge">${destination.region.toUpperCase()}</div>
                    </div>
                    <div class="card-content">
                        <div class="card-header">
                            <h3>${destination.title}</h3>
                            <div class="card-price">$${destination.price}<span>/personne</span></div>
                        </div>
                        <div class="card-location">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>${destination.location}</span>
                        </div>
                        <p class="card-description">${destination.description.substring(0, 120)}...</p>
                        <div class="card-features">
                            ${destination.features.map(feature => `
                                <div class="feature">
                                    <i class="fas fa-check"></i>
                                    ${feature}
                                </div>
                            `).join('')}
                        </div>
                        <div class="card-actions">
                            <button class="btn-outline" data-id="${destination.id}">
                                <i class="far fa-eye"></i>
                                Détails
                            </button>
                            <button class="btn-primary" data-id="${destination.id}">
                                <i class="fas fa-calendar-check"></i>
                                Réserver
                            </button>
                        </div>
                    </div>
                `;
                
                destinationsContainer.appendChild(card);
            });
            
            // Ajouter les écouteurs d'événements aux boutons
            document.querySelectorAll('.btn-outline, .btn-primary').forEach(button => {
                button.addEventListener('click', function() {
                    const id = parseInt(this.dataset.id);
                    const destination = destinations.find(d => d.id === id);
                    
                    if (this.classList.contains('btn-primary')) {
                        openModal(destination, true);
                    } else {
                        openModal(destination, false);
                    }
                });
            });
            
            // Observer les cartes pour l'animation au défilement
            observeCards();
        }

        // Configuration des filtres
        function setupFilters() {
            filterButtons.forEach(button => {
                button.addEventListener('click', function() {
                    // Mettre à jour l'état actif
                    filterButtons.forEach(btn => btn.classList.remove('active'));
                    this.classList.add('active');
                    
                    // Filtrer les destinations
                    currentFilter = this.dataset.filter;
                    
                    let filteredDestinations;
                    if (currentFilter === 'all') {
                        filteredDestinations = destinations;
                    } else {
                        filteredDestinations = destinations.filter(d => d.region === currentFilter);
                    }
                    
                    renderDestinations(filteredDestinations);
                });
            });
        }

        // Configuration de la modal
        function setupModal() {
            closeModal.addEventListener('click', function() {
                modal.classList.remove('active');
            });
            
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    modal.classList.remove('active');
                }
            });
            
            // Fermer la modal avec la touche Échap
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    modal.classList.remove('active');
                }
            });
        }

        // Configuration de la vidéo
        function setupVideo() {
            playBtn.addEventListener('click', function() {
                // Dans un cas réel, on ouvrirait une modal vidéo
                alert("Ouverture du player vidéo complet avec toutes nos vidéos d'expériences.");
            });
            
            // Masquer l'overlay après 10 secondes
            setTimeout(() => {
                videoOverlay.style.opacity = '0.8';
            }, 10000);
        }

        // Configuration des animations
        function setupAnimations() {
            // Animation du texte hero
            const heroTitle = document.querySelector('.hero h1');
            setInterval(() => {
                heroTitle.style.animation = 'none';
                setTimeout(() => {
                    heroTitle.style.animation = 'textShine 3s ease-in-out infinite alternate';
                }, 10);
            }, 5000);
        }

        // Observer les cartes pour l'animation au défilement
        function observeCards() {
            const cards = document.querySelectorAll('.destination-card');
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('visible');
                    }
                });
            }, { threshold: 0.1 });
            
            cards.forEach(card => observer.observe(card));
        }

        // Ouvrir la modal
        function openModal(destination, showForm = false) {
            currentDestination = destination;
            
            let formHTML = '';
            if (showForm) {
                formHTML = `
                    <div class="booking-form">
                        <h4>Réserver cette expérience</h4>
                        <form id="reservation-form">
                            <div class="form-group">
                                <label for="booking-date">Date de départ</label>
                                <input type="date" id="booking-date" required>
                            </div>
                            <div class="form-group">
                                <label for="travelers">Nombre de voyageurs</label>
                                <select id="travelers" required>
                                    <option value="1">1 voyageur</option>
                                    <option value="2" selected>2 voyageurs</option>
                                    <option value="3">3 voyageurs</option>
                                    <option value="4">4 voyageurs</option>
                                    <option value="5+">5+ voyageurs (groupe)</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="full-name">Nom complet</label>
                                <input type="text" id="full-name" placeholder="Votre nom complet" required>
                            </div>
                            <div class="form-group">
                                <label for="email">Adresse e-mail</label>
                                <input type="email" id="email" placeholder="votre@email.com" required>
                            </div>
                            <button type="submit" class="btn-submit">
                                <i class="fas fa-lock"></i>
                                Confirmer la réservation
                            </button>
                        </form>
                    </div>
                `;
            } else {
                formHTML = `
                    <div class="booking-form">
                        <button class="btn-submit" id="show-booking-form">
                            <i class="fas fa-calendar-check"></i>
                            Réserver maintenant
                        </button>
                    </div>
                `;
            }
            
            modalContent.innerHTML = `
                <div class="modal-media">
                    <img src="${destination.image}" alt="${destination.title}">
                    <button class="video-btn" id="modal-video-btn">
                        <i class="fas fa-play"></i>
                        Voir la vidéo
                    </button>
                </div>
                <div class="modal-info">
                    <h3>${destination.title}</h3>
                    <div class="modal-location">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>${destination.location}</span>
                    </div>
                    <p class="modal-description">${destination.description}</p>
                    
                    <div class="modal-highlights">
                        ${destination.highlights.map(highlight => `
                            <div class="highlight">
                                <i class="fas fa-star"></i>
                                <span>${highlight}</span>
                            </div>
                        `).join('')}
                    </div>
                    
                    <div class="details">
                        <p><strong>Durée:</strong> ${destination.duration}</p>
                        <p><strong>Meilleure saison:</strong> ${destination.season}</p>
                    </div>
                    
                    <div class="modal-price">$${destination.price}<span>/personne</span></div>
                    
                    ${formHTML}
                </div>
            `;
            
            modal.classList.add('active');
            
            // Ajouter l'écouteur pour le formulaire de réservation
            if (showForm) {
                document.getElementById('reservation-form').addEventListener('submit', function(e) {
                    e.preventDefault();
                    // Simulation d'envoi de formulaire
                    this.innerHTML = `
                        <div style="text-align: center; padding: 20px;">
                            <i class="fas fa-check-circle" style="font-size: 3rem; color: var(--accent); margin-bottom: 20px;"></i>
                            <h3 style="margin-bottom: 10px;">Réservation confirmée!</h3>
                            <p>Nous vous avons envoyé un email de confirmation avec tous les détails.</p>
                        </div>
                    `;
                    setTimeout(() => {
                        modal.classList.remove('active');
                    }, 3000);
                });
            } else {
                const showBookingBtn = document.getElementById('show-booking-form');
                if (showBookingBtn) {
                    showBookingBtn.addEventListener('click', function() {
                        openModal(destination, true);
                    });
                }
            }
            
            // Bouton vidéo dans la modal
            document.getElementById('modal-video-btn').addEventListener('click', function() {
                alert(`Ouverture de la vidéo: ${destination.title}`);
            });
        }

        // Animation du fond au mouvement de la souris
        document.addEventListener('mousemove', function(e) {
            const x = e.clientX / window.innerWidth;
            const y = e.clientY / window.innerHeight;
            
            document.querySelector('.bg-animation').style.transform = `translate(${x * 20}px, ${y * 20}px)`;
        });
    </script>
    <script>
function sendHeight() {
    const height = document.body.scrollHeight;
    window.parent.postMessage({
        type: 'setHeight',
        iframeId: 'iframe-page-travel-3',
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