<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CANADA Media | Galerie Interactive</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --youtube-red: #FF0000;
            --youtube-dark: #0F0F0F;
            --youtube-gray: #272727;
            --youtube-light: #F1F1F1;
            --accent-blue: #3EA6FF;
            --accent-green: #00C853;
            --accent-purple: #9146FF;
            --white: #FFFFFF;
            --black: #000000;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            --shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            --shadow-lg: 0 10px 40px rgba(0, 0, 0, 0.3);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--youtube-dark);
            color: var(--white);
            overflow-x: hidden;
        }

        h1, h2, h3, h4, h5 {
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
        }

        /* YouTube-like Header */
        .youtube-header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            background: rgba(15, 15, 15, 0.95);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--youtube-gray);
            padding: 15px 30px;
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 30px;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 10px;
            font-family: 'Outfit', sans-serif;
            font-weight: 800;
            font-size: 1.5rem;
            color: var(--white);
        }

        .logo i {
            color: var(--youtube-red);
        }

        .search-bar {
            display: flex;
            background: var(--youtube-gray);
            border-radius: 40px;
            overflow: hidden;
            width: 500px;
            border: 1px solid var(--youtube-gray);
        }

        .search-input {
            flex: 1;
            background: transparent;
            border: none;
            padding: 12px 20px;
            color: var(--white);
            font-size: 1rem;
            outline: none;
        }

        .search-btn {
            background: var(--youtube-gray);
            border: none;
            padding: 0 25px;
            color: var(--white);
            cursor: pointer;
            transition: var(--transition);
        }

        .search-btn:hover {
            background: #3d3d3d;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .header-btn {
            background: transparent;
            border: none;
            color: var(--white);
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: var(--transition);
        }

        .header-btn:hover {
            background: var(--youtube-gray);
        }

        .user-avatar {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background: var(--accent-blue);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }

        /* Main Container */
        .main-container {
            display: flex;
            margin-top: 70px;
        }

        /* YouTube-like Sidebar */
        .sidebar {
            width: 240px;
            background: var(--youtube-dark);
            padding: 20px 0;
            position: fixed;
            height: calc(100vh - 70px);
            overflow-y: auto;
            border-right: 1px solid var(--youtube-gray);
        }

        .sidebar-section {
            padding: 15px 0;
            border-bottom: 1px solid var(--youtube-gray);
        }

        .sidebar-item {
            display: flex;
            align-items: center;
            gap: 20px;
            padding: 12px 25px;
            color: var(--white);
            text-decoration: none;
            transition: var(--transition);
            cursor: pointer;
        }

        .sidebar-item:hover {
            background: var(--youtube-gray);
        }

        .sidebar-item.active {
            background: var(--youtube-gray);
            border-left: 3px solid var(--youtube-red);
        }

        .sidebar-item i {
            width: 24px;
            text-align: center;
        }

        .subscriptions {
            padding: 15px 25px;
            color: #AAAAAA;
            font-size: 0.9rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            margin-left: 240px;
            padding: 30px;
        }

        /* Hero Slider */
        .hero-slider {
            position: relative;
            height: 500px;
            border-radius: 16px;
            overflow: hidden;
            margin-bottom: 40px;
            box-shadow: var(--shadow-lg);
        }

        .slide {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            transform: scale(1.1);
            transition: opacity 1s ease, transform 8s linear;
            display: flex;
            align-items: flex-end;
        }

        .slide.active {
            opacity: 1;
            transform: scale(1);
        }

        .slide-content {
            position: relative;
            z-index: 2;
            padding: 50px;
            width: 100%;
            background: linear-gradient(to top, rgba(0,0,0,0.9) 0%, rgba(0,0,0,0.5) 50%, transparent 100%);
        }

        .slide-badge {
            display: inline-block;
            background: var(--youtube-red);
            color: white;
            padding: 8px 20px;
            border-radius: 4px;
            font-weight: 600;
            font-size: 0.9rem;
            margin-bottom: 20px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .slide-title {
            font-size: 3rem;
            margin-bottom: 15px;
            line-height: 1.1;
            max-width: 800px;
        }

        .slide-info {
            display: flex;
            gap: 30px;
            color: rgba(255, 255, 255, 0.8);
            font-size: 1.1rem;
        }

        .slide-image {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: 1;
        }

        .slider-controls {
            position: absolute;
            bottom: 30px;
            right: 30px;
            display: flex;
            gap: 15px;
            z-index: 3;
        }

        .slider-btn {
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: white;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: var(--transition);
            backdrop-filter: blur(10px);
        }

        .slider-btn:hover {
            background: var(--youtube-red);
            transform: scale(1.1);
        }

        .slide-indicators {
            position: absolute;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 10px;
            z-index: 3;
        }

        .indicator {
            width: 40px;
            height: 4px;
            background: rgba(255, 255, 255, 0.3);
            cursor: pointer;
            transition: var(--transition);
        }

        .indicator.active {
            background: var(--youtube-red);
        }

        /* Content Tabs */
        .content-tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 30px;
            border-bottom: 1px solid var(--youtube-gray);
            padding-bottom: 15px;
            overflow-x: auto;
        }

        .tab-btn {
            background: var(--youtube-gray);
            border: none;
            color: white;
            padding: 10px 25px;
            border-radius: 20px;
            cursor: pointer;
            transition: var(--transition);
            white-space: nowrap;
            font-weight: 500;
        }

        .tab-btn:hover {
            background: #3d3d3d;
        }

        .tab-btn.active {
            background: white;
            color: black;
        }

        /* Media Grid */
        .media-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 25px;
            margin-bottom: 50px;
        }

        .media-card {
            background: var(--youtube-gray);
            border-radius: 12px;
            overflow: hidden;
            transition: var(--transition);
            cursor: pointer;
            position: relative;
        }

        .media-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-lg);
        }

        .card-thumbnail {
            position: relative;
            width: 100%;
            height: 180px;
            overflow: hidden;
        }

        .card-thumbnail img, .card-thumbnail video {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: var(--transition);
        }

        .media-card:hover .card-thumbnail img,
        .media-card:hover .card-thumbnail video {
            transform: scale(1.05);
        }

        .thumbnail-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(to bottom, transparent 50%, rgba(0,0,0,0.8) 100%);
            display: flex;
            align-items: flex-end;
            justify-content: flex-end;
            padding: 15px;
            opacity: 0;
            transition: var(--transition);
        }

        .media-card:hover .thumbnail-overlay {
            opacity: 1;
        }

        .play-btn {
            background: var(--youtube-red);
            color: white;
            border: none;
            width: 45px;
            height: 45px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: var(--transition);
        }

        .play-btn:hover {
            transform: scale(1.1);
        }

        .duration {
            position: absolute;
            bottom: 15px;
            right: 15px;
            background: rgba(0, 0, 0, 0.8);
            color: white;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .card-content {
            padding: 15px;
        }

        .card-title {
            font-size: 1.1rem;
            margin-bottom: 10px;
            line-height: 1.4;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .card-channel {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 10px;
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.9rem;
        }

        .channel-avatar {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: var(--accent-purple);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .card-stats {
            display: flex;
            justify-content: space-between;
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.85rem;
        }

        .card-badge {
            position: absolute;
            top: 15px;
            left: 15px;
            background: var(--accent-green);
            color: white;
            padding: 5px 12px;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 600;
            z-index: 2;
        }

        /* AI Generated Section */
        .ai-section {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            border-radius: 16px;
            padding: 40px;
            margin: 50px 0;
            position: relative;
            overflow: hidden;
        }

        .ai-section:before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="none"><path d="M0,0 L100,0 L100,100 Z" fill="rgba(62,166,255,0.1)"/></svg>');
            background-size: cover;
            pointer-events: none;
        }

        .ai-header {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 30px;
        }

        .ai-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, var(--accent-blue), var(--accent-purple));
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        .ai-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            align-items: center;
        }

        .ai-text h3 {
            font-size: 2.5rem;
            margin-bottom: 20px;
            line-height: 1.2;
        }

        .ai-text p {
            color: rgba(255, 255, 255, 0.8);
            line-height: 1.6;
            margin-bottom: 25px;
            font-size: 1.1rem;
        }

        .ai-generate-btn {
            background: linear-gradient(135deg, var(--accent-blue), var(--accent-purple));
            border: none;
            color: white;
            padding: 15px 35px;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .ai-generate-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(62, 166, 255, 0.3);
        }

        .ai-visual {
            position: relative;
            height: 300px;
            border-radius: 12px;
            overflow: hidden;
            background: linear-gradient(135deg, #0a0a0a, #1a1a1a);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .ai-visual img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: 0.8;
        }

        .ai-loading {
            position: absolute;
            width: 80px;
            height: 80px;
            border: 4px solid rgba(62, 166, 255, 0.3);
            border-top-color: var(--accent-blue);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Video Player Modal */
        .player-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.9);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 2000;
            opacity: 0;
            visibility: hidden;
            transition: var(--transition);
            backdrop-filter: blur(10px);
        }

        .player-modal.active {
            opacity: 1;
            visibility: visible;
        }

        .player-container {
            width: 90%;
            max-width: 1200px;
            background: var(--youtube-dark);
            border-radius: 12px;
            overflow: hidden;
            transform: scale(0.9);
            transition: var(--transition);
        }

        .player-modal.active .player-container {
            transform: scale(1);
        }

        .player-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 30px;
            border-bottom: 1px solid var(--youtube-gray);
        }

        .player-title {
            font-size: 1.5rem;
        }

        .player-close {
            background: transparent;
            border: none;
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: var(--transition);
        }

        .player-close:hover {
            background: var(--youtube-gray);
        }

        .player-content {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 30px;
            padding: 30px;
            max-height: 70vh;
            overflow-y: auto;
        }

        .main-player {
            width: 100%;
            aspect-ratio: 16/9;
            background: black;
            border-radius: 8px;
            overflow: hidden;
        }

        .player-info {
            padding: 20px 0;
        }

        .player-description {
            color: rgba(255, 255, 255, 0.8);
            line-height: 1.6;
            margin-bottom: 20px;
        }

        .player-stats {
            display: flex;
            gap: 30px;
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.9rem;
            margin-bottom: 20px;
        }

        .player-actions {
            display: flex;
            gap: 15px;
            margin-bottom: 30px;
        }

        .action-btn {
            background: var(--youtube-gray);
            border: none;
            color: white;
            padding: 10px 20px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            transition: var(--transition);
        }

        .action-btn:hover {
            background: #3d3d3d;
        }

        .action-btn.like.active {
            background: var(--accent-blue);
            color: white;
        }

        .recommended-videos {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .recommended-title {
            font-size: 1.2rem;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid var(--youtube-gray);
        }

        .recommended-card {
            display: flex;
            gap: 15px;
            cursor: pointer;
            transition: var(--transition);
            padding: 10px;
            border-radius: 8px;
        }

        .recommended-card:hover {
            background: var(--youtube-gray);
        }

        .recommended-thumbnail {
            width: 120px;
            height: 70px;
            border-radius: 6px;
            overflow: hidden;
            flex-shrink: 0;
        }

        .recommended-thumbnail img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .recommended-info h4 {
            font-size: 0.9rem;
            margin-bottom: 5px;
            line-height: 1.3;
        }

        .recommended-info p {
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.8rem;
        }

        /* Footer */
        .footer {
            background: var(--black);
            padding: 50px 30px;
            margin-top: 50px;
            border-top: 1px solid var(--youtube-gray);
        }

        .footer-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 40px;
            margin-bottom: 40px;
        }

        .footer-column h4 {
            margin-bottom: 20px;
            font-size: 1.1rem;
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
            font-size: 0.9rem;
        }

        .footer-links a:hover {
            color: var(--accent-blue);
        }

        .footer-bottom {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 30px;
            border-top: 1px solid var(--youtube-gray);
            color: rgba(255, 255, 255, 0.5);
            font-size: 0.9rem;
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

        .animate-in {
            animation: fadeInUp 0.6s ease forwards;
        }

        /* Responsive */
        @media (max-width: 1200px) {
            .sidebar {
                display: none;
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .search-bar {
                width: 300px;
            }
            
            .ai-content {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .youtube-header {
                padding: 15px;
            }
            
            .search-bar {
                display: none;
            }
            
            .hero-slider {
                height: 400px;
            }
            
            .slide-title {
                font-size: 2rem;
            }
            
            .slide-content {
                padding: 30px;
            }
            
            .media-grid {
                grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            }
            
            .main-content {
                padding: 20px;
            }
            
            .player-content {
                grid-template-columns: 1fr;
                padding: 20px;
            }
            
            .ai-section {
                padding: 30px 20px;
            }
        }

        @media (max-width: 576px) {
            .header-right .header-btn:not(.user-avatar) {
                display: none;
            }
            
            .hero-slider {
                height: 300px;
            }
            
            .slide-title {
                font-size: 1.5rem;
            }
            
            .slide-info {
                flex-direction: column;
                gap: 10px;
            }
            
            .media-grid {
                grid-template-columns: 1fr;
            }
            
            .content-tabs {
                overflow-x: scroll;
                padding-bottom: 10px;
            }
            
            .tab-btn {
                padding: 8px 20px;
                font-size: 0.9rem;
            }
            
            .footer-grid {
                grid-template-columns: 1fr;
            }
            
            .footer-bottom {
                flex-direction: column;
                gap: 20px;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <!-- Main Container -->
    <div class="main-container">
        <!-- Sidebar -->
        <nav class="sidebar">
            <div class="sidebar-section">
                <a href="#" class="sidebar-item active">
                    <i class="fas fa-home"></i>
                    <span>Accueil</span>
                </a>
                <a href="#" class="sidebar-item">
                    <i class="fas fa-compass"></i>
                    <span>Explorer</span>
                </a>
                <a href="#" class="sidebar-item">
                    <i class="fas fa-play-circle"></i>
                    <span>Shorts</span>
                </a>
                <a href="#" class="sidebar-item">
                    <i class="fas fa-photo-video"></i>
                    <span>Abonnements</span>
                </a>
            </div>
            
            <div class="sidebar-section">
                <a href="#" class="sidebar-item">
                    <i class="fas fa-bookmark"></i>
                    <span>Bibliothèque</span>
                </a>
                <a href="#" class="sidebar-item">
                    <i class="fas fa-history"></i>
                    <span>Historique</span>
                </a>
                <a href="#" class="sidebar-item">
                    <i class="fas fa-clock"></i>
                    <span>À regarder plus tard</span>
                </a>
                <a href="#" class="sidebar-item">
                    <i class="fas fa-thumbs-up"></i>
                    <span>Vidéos likées</span>
                </a>
            </div>
            
            <div class="subscriptions">ABONNEMENTS</div>
            
            <div class="sidebar-section">
                <a href="#" class="sidebar-item">
                    <div class="channel-avatar" style="background: #FF6B6B;">BN</div>
                    <span>Banff National</span>
                </a>
                <a href="#" class="sidebar-item">
                    <div class="channel-avatar" style="background: #4ECDC4;">QC</div>
                    <span>Québec Culture</span>
                </a>
                <a href="#" class="sidebar-item">
                    <div class="channel-avatar" style="background: #FFD166;">NT</div>
                    <span>Northern Tales</span>
                </a>
                <a href="#" class="sidebar-item">
                    <div class="channel-avatar" style="background: #06D6A0;">PC</div>
                    <span>Pacific Coast</span>
                </a>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Hero Slider -->
            <section class="hero-slider">
                <div class="slide active">
                    <img src="https://images.unsplash.com/photo-1506905925346-21bda4d32df4?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80" alt="Montagnes canadiennes" class="slide-image">
                    <div class="slide-content">
                        <span class="slide-badge">Nouveau</span>
                        <h2 class="slide-title">Les Rocheuses sous un nouveau jour</h2>
                        <div class="slide-info">
                            <span><i class="far fa-eye"></i> 2.4M vues</span>
                            <span><i class="far fa-clock"></i> Il y a 2 jours</span>
                            <span><i class="fas fa-map-marker-alt"></i> Alberta, Canada</span>
                        </div>
                    </div>
                </div>
                
                <div class="slide">
                    <img src="https://images.unsplash.com/photo-1548191657-6aa4c4c8e0e9?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80" alt="Vieux Québec" class="slide-image">
                    <div class="slide-content">
                        <span class="slide-badge">Viral</span>
                        <h2 class="slide-title">Québec : Une nuit dans la vieille ville</h2>
                        <div class="slide-info">
                            <span><i class="far fa-eye"></i> 5.1M vues</span>
                            <span><i class="far fa-clock"></i> Il y a 1 semaine</span>
                            <span><i class="fas fa-map-marker-alt"></i> Québec, Canada</span>
                        </div>
                    </div>
                </div>
                
                <div class="slide">
                    <img src="https://images.unsplash.com/photo-1578662996442-48f60103fc96?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80" alt="Aurores boréales" class="slide-image">
                    <div class="slide-content">
                        <span class="slide-badge">Exclusif</span>
                        <h2 class="slide-title">Chasse aux aurores boréales au Yukon</h2>
                        <div class="slide-info">
                            <span><i class="far fa-eye"></i> 3.7M vues</span>
                            <span><i class="far fa-clock"></i> Il y a 3 jours</span>
                            <span><i class="fas fa-map-marker-alt"></i> Yukon, Canada</span>
                        </div>
                    </div>
                </div>
                
                <div class="slider-controls">
                    <button class="slider-btn prev-slide">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <button class="slider-btn next-slide">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
                
                <div class="slide-indicators">
                    <div class="indicator active"></div>
                    <div class="indicator"></div>
                    <div class="indicator"></div>
                </div>
            </section>

            <!-- Content Tabs -->
            <div class="content-tabs">
                <button class="tab-btn active" data-filter="all">Tout</button>
                <button class="tab-btn" data-filter="video">Vidéos</button>
                <button class="tab-btn" data-filter="photo">Photos</button>
                <button class="tab-btn" data-filter="live">En direct</button>
                <button class="tab-btn" data-filter="360">Vue 360°</button>
                <button class="tab-btn" data-filter="drone">Drone</button>
                <button class="tab-btn" data-filter="nature">Nature</button>
                <button class="tab-btn" data-filter="urban">Urbain</button>
            </div>

            <!-- Media Grid -->
            <div class="media-grid" id="media-container">
                <!-- Media cards will be generated by JavaScript -->
            </div>

            <!-- AI Generated Section -->
            <section class="ai-section">
                <div class="ai-header">
                    <div class="ai-icon">
                        <i class="fas fa-robot"></i>
                    </div>
                    <h2>Généré par IA</h2>
                </div>
                <div class="ai-content">
                    <div class="ai-text">
                        <h3>Découvrez le Canada comme jamais auparavant</h3>
                        <p>Notre IA crée des paysages uniques en combinant des photos réelles avec des éléments générés. Explorez des vues qui n'existent que dans l'imagination.</p>
                        <button class="ai-generate-btn" id="generate-ai">
                            <i class="fas fa-magic"></i>
                            Générer une nouvelle scène
                        </button>
                    </div>
                    <div class="ai-visual" id="ai-visual">
                        <img src="https://images.unsplash.com/photo-1503614472-8c93d56e92ce?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80" alt="IA Générée" id="ai-image">
                        <div class="ai-loading" id="ai-loading" style="display: none;"></div>
                    </div>
                </div>
            </section>

            <!-- More Media -->
            <div class="media-grid" id="more-media-container">
                <!-- More cards will be generated by JavaScript -->
            </div>
        </main>
    </div>

    <!-- Video Player Modal -->
    <div class="player-modal" id="player-modal">
        <div class="player-container">
            <div class="player-header">
                <h3 class="player-title" id="player-title">Titre de la vidéo</h3>
                <button class="player-close" id="player-close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="player-content">
                <div class="main-player" id="main-player">
                    <!-- Video will be injected here -->
                </div>
                <div class="player-sidebar">
                    <div class="player-info">
                        <div class="player-stats">
                            <span id="player-views">2.4M vues</span>
                            <span id="player-date">Il y a 2 jours</span>
                            <span id="player-likes">245K</span>
                        </div>
                        <div class="player-actions">
                            <button class="action-btn like" id="like-btn">
                                <i class="fas fa-thumbs-up"></i>
                                J'aime
                            </button>
                            <button class="action-btn" id="share-btn">
                                <i class="fas fa-share"></i>
                                Partager
                            </button>
                            <button class="action-btn" id="save-btn">
                                <i class="fas fa-bookmark"></i>
                                Sauvegarder
                            </button>
                        </div>
                        <div class="player-description" id="player-description">
                            Description de la vidéo...
                        </div>
                    </div>
                    <div class="recommended-videos">
                        <h4 class="recommended-title">À suivre</h4>
                        <!-- Recommended videos will be injected here -->
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script>
        // Données des médias
        const mediaItems = [
            {
                id: 1,
                type: "video",
                title: "Les lacs émeraude de Banff - Documentaire 4K",
                channel: "Nature Canada",
                views: "2.4M",
                date: "Il y a 2 jours",
                duration: "18:42",
                thumbnail: "https://images.unsplash.com/photo-1503614472-8c93d56e92ce?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80",
                videoId: "InyPq6cR5zs",
                likes: "245K",
                description: "Explorez les lacs les plus célèbres du parc national Banff en Alberta. Tourné en 4K avec des drones et des caméras sous-marines."
            },
            {
                id: 2,
                type: "photo",
                title: "Galerie exclusive : Les aurores boréales du Yukon",
                channel: "Northern Lights",
                views: "1.8M",
                date: "Il y a 5 jours",
                duration: null,
                thumbnail: "https://images.unsplash.com/photo-1578662996442-48f60103fc96?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80",
                likes: "198K",
                description: "Collection de photos en haute résolution des aurores boréales capturées pendant l'hiver arctique."
            },
            {
                id: 3,
                type: "live",
                title: "EN DIRECT : Observation des baleines à Vancouver",
                channel: "Pacific Wildlife",
                views: "15K en direct",
                date: "En direct",
                duration: null,
                thumbnail: "https://images.unsplash.com/photo-1544551763-46a013bb70d5?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80",
                videoId: "7F2TMSx0R30",
                likes: "8.2K",
                description: "Stream en direct de l'observation des baleines au large de l'île de Vancouver."
            },
            {
                id: 4,
                type: "drone",
                title: "Toronto vue du ciel - Film drone 8K",
                channel: "Urban Explorer",
                views: "3.2M",
                date: "Il y a 3 semaines",
                duration: "12:15",
                thumbnail: "https://images.unsplash.com/photo-1528164344705-47542687000d?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80",
                videoId: "A1QvJt6SX80",
                likes: "312K",
                description: "Tournée aérienne complète de Toronto avec des prises de vue en 8K et mouvements fluides."
            },
            {
                id: 5,
                type: "360",
                title: "Expérience 360° : Les chutes du Niagara",
                channel: "Immersion VR",
                views: "1.2M",
                date: "Il y a 1 mois",
                duration: "24:30",
                thumbnail: "https://images.unsplash.com/photo-1528164344705-47542687000d?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80",
                videoId: "A1QvJt6SX80",
                likes: "156K",
                description: "Vidéo 360° complète des chutes du Niagara. Compatible avec les casques VR."
            },
            {
                id: 6,
                type: "video",
                title: "La vie sauvage dans les Rocheuses",
                channel: "Wild Canada",
                views: "4.7M",
                date: "Il y a 2 mois",
                duration: "28:15",
                thumbnail: "https://images.unsplash.com/photo-1506905925346-21bda4d32df4?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80",
                videoId: "InyPq6cR5zs",
                likes: "421K",
                description: "Documentaire sur la faune des Rocheuses canadiennes. Ours, loups, wapitis et plus."
            },
            {
                id: 7,
                type: "photo",
                title: "Portraits urbains de Montréal",
                channel: "City Stories",
                views: "890K",
                date: "Il y a 3 jours",
                duration: null,
                thumbnail: "https://images.unsplash.com/photo-1548191657-6aa4c4c8e0e9?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80",
                likes: "92K",
                description: "Collection de photos street photography dans les quartiers animés de Montréal."
            },
            {
                id: 8,
                type: "nature",
                title: "Les forêts anciennes de Colombie-Britannique",
                channel: "Ancient Forests",
                views: "2.1M",
                date: "Il y a 1 semaine",
                duration: "22:40",
                thumbnail: "https://images.unsplash.com/photo-1544551763-46a013bb70d5?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80",
                videoId: "7F2TMSx0R30",
                likes: "187K",
                description: "Exploration des forêts anciennes de l'île de Vancouver et de leurs écosystèmes uniques."
            }
        ];

        // Données des images IA
        const aiImages = [
            "https://images.unsplash.com/photo-1503614472-8c93d56e92ce?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80",
            "https://images.unsplash.com/photo-1548191657-6aa4c4c8e0e9?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80",
            "https://images.unsplash.com/photo-1578662996442-48f60103fc96?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80",
            "https://images.unsplash.com/photo-1506905925346-21bda4d32df4?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80",
            "https://images.unsplash.com/photo-1528164344705-47542687000d?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80"
        ];

        // Éléments DOM
        const mediaContainer = document.getElementById('media-container');
        const moreMediaContainer = document.getElementById('more-media-container');
        const tabButtons = document.querySelectorAll('.tab-btn');
        const playerModal = document.getElementById('player-modal');
        const playerClose = document.getElementById('player-close');
        const mainPlayer = document.getElementById('main-player');
        const playerTitle = document.getElementById('player-title');
        const playerViews = document.getElementById('player-views');
        const playerDate = document.getElementById('player-date');
        const playerLikes = document.getElementById('player-likes');
        const playerDescription = document.getElementById('player-description');
        const likeBtn = document.getElementById('like-btn');
        const aiGenerateBtn = document.getElementById('generate-ai');
        const aiImage = document.getElementById('ai-image');
        const aiLoading = document.getElementById('ai-loading');
        const slides = document.querySelectorAll('.slide');
        const slideIndicators = document.querySelectorAll('.indicator');
        const prevSlideBtn = document.querySelector('.prev-slide');
        const nextSlideBtn = document.querySelector('.next-slide');

        // Variables
        let currentFilter = 'all';
        let currentSlide = 0;
        let slideInterval;
        let likedVideos = new Set();

        // Initialisation
        document.addEventListener('DOMContentLoaded', function() {
            renderMedia(mediaItems);
            setupTabs();
            setupPlayer();
            setupSlider();
            setupAI();
            animateElements();
        });

        // Rendu des médias
        function renderMedia(mediaArray) {
            mediaContainer.innerHTML = '';
            moreMediaContainer.innerHTML = '';
            
            // Première moitié dans mediaContainer
            const firstHalf = mediaArray.slice(0, 4);
            const secondHalf = mediaArray.slice(4);
            
            firstHalf.forEach(item => {
                mediaContainer.appendChild(createMediaCard(item));
            });
            
            secondHalf.forEach(item => {
                moreMediaContainer.appendChild(createMediaCard(item));
            });
            
            // Ajouter les écouteurs d'événements
            document.querySelectorAll('.media-card').forEach(card => {
                card.addEventListener('click', function() {
                    const id = parseInt(this.dataset.id);
                    const media = mediaItems.find(m => m.id === id);
                    openPlayer(media);
                });
            });
        }

        // Création d'une carte média
        function createMediaCard(item) {
            const card = document.createElement('div');
            card.className = `media-card animate-in`;
            card.dataset.id = item.id;
            card.dataset.type = item.type;
            
            const channelInitials = item.channel.split(' ').map(word => word[0]).join('').toUpperCase();
            
            let badgeHTML = '';
            if (item.type === 'live') {
                badgeHTML = '<div class="card-badge" style="background: var(--youtube-red);">EN DIRECT</div>';
            } else if (item.type === '360') {
                badgeHTML = '<div class="card-badge" style="background: var(--accent-purple);">360°</div>';
            } else if (item.type === 'drone') {
                badgeHTML = '<div class="card-badge" style="background: var(--accent-blue);">DRONE</div>';
            }
            
            card.innerHTML = `
                <div class="card-thumbnail">
                    <img src="${item.thumbnail}" alt="${item.title}" loading="lazy">
                    ${badgeHTML}
                    <div class="thumbnail-overlay">
                        ${item.duration ? `<div class="duration">${item.duration}</div>` : ''}
                        <button class="play-btn">
                            <i class="fas fa-play"></i>
                        </button>
                    </div>
                </div>
                <div class="card-content">
                    <h3 class="card-title">${item.title}</h3>
                    <div class="card-channel">
                        <div class="channel-avatar">${channelInitials.substring(0, 2)}</div>
                        <span>${item.channel}</span>
                    </div>
                    <div class="card-stats">
                        <span>${item.views} vues</span>
                        <span>${item.date}</span>
                    </div>
                </div>
            `;
            
            return card;
        }

        // Configuration des onglets
        function setupTabs() {
            tabButtons.forEach(button => {
                button.addEventListener('click', function() {
                    // Mettre à jour l'état actif
                    tabButtons.forEach(btn => btn.classList.remove('active'));
                    this.classList.add('active');
                    
                    // Filtrer les médias
                    currentFilter = this.dataset.filter;
                    
                    let filteredMedia;
                    if (currentFilter === 'all') {
                        filteredMedia = mediaItems;
                    } else {
                        filteredMedia = mediaItems.filter(m => m.type === currentFilter);
                    }
                    
                    // Animation de sortie
                    const cards = document.querySelectorAll('.media-card');
                    cards.forEach((card, index) => {
                        setTimeout(() => {
                            card.style.opacity = '0';
                            card.style.transform = 'translateY(20px)';
                        }, index * 50);
                    });
                    
                    // Rendu après animation
                    setTimeout(() => {
                        renderMedia(filteredMedia);
                    }, 300);
                });
            });
        }

        // Configuration du player
        function setupPlayer() {
            playerClose.addEventListener('click', function() {
                closePlayer();
            });
            
            playerModal.addEventListener('click', function(e) {
                if (e.target === playerModal) {
                    closePlayer();
                }
            });
            
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closePlayer();
                }
            });
            
            // Bouton like
            likeBtn.addEventListener('click', function() {
                const mediaId = parseInt(this.dataset.mediaId);
                if (likedVideos.has(mediaId)) {
                    likedVideos.delete(mediaId);
                    this.classList.remove('active');
                } else {
                    likedVideos.add(mediaId);
                    this.classList.add('active');
                }
            });
            
            // Bouton partage
            document.getElementById('share-btn').addEventListener('click', function() {
                navigator.clipboard.writeText(window.location.href);
                alert('Lien copié dans le presse-papier !');
            });
            
            // Bouton sauvegarder
            document.getElementById('save-btn').addEventListener('click', function() {
                this.innerHTML = '<i class="fas fa-check"></i> Sauvegardé';
                setTimeout(() => {
                    this.innerHTML = '<i class="fas fa-bookmark"></i> Sauvegarder';
                }, 2000);
            });
        }

        // Configuration du slider
        function setupSlider() {
            // Démarrer l'intervalle
            startSlideInterval();
            
            // Boutons de navigation
            prevSlideBtn.addEventListener('click', function() {
                goToSlide(currentSlide - 1);
                resetSlideInterval();
            });
            
            nextSlideBtn.addEventListener('click', function() {
                goToSlide(currentSlide + 1);
                resetSlideInterval();
            });
            
            // Indicateurs
            slideIndicators.forEach((indicator, index) => {
                indicator.addEventListener('click', function() {
                    goToSlide(index);
                    resetSlideInterval();
                });
            });
        }

        // Configuration de l'IA
        function setupAI() {
            aiGenerateBtn.addEventListener('click', function() {
                generateAIImage();
            });
            
            // Générer une première image au chargement
            setTimeout(() => {
                generateAIImage();
            }, 1000);
        }

        // Générer une image IA
        function generateAIImage() {
            // Afficher le chargement
            aiLoading.style.display = 'block';
            aiImage.style.opacity = '0.3';
            
            // Simuler le traitement IA
            setTimeout(() => {
                // Sélectionner une image aléatoire
                const randomImage = aiImages[Math.floor(Math.random() * aiImages.length)];
                
                // Effet de transition
                aiImage.style.opacity = '0';
                setTimeout(() => {
                    aiImage.src = randomImage + '?t=' + new Date().getTime();
                    aiImage.style.opacity = '0.8';
                    aiLoading.style.display = 'none';
                    
                    // Animation
                    aiImage.style.transform = 'scale(1.05)';
                    setTimeout(() => {
                        aiImage.style.transform = 'scale(1)';
                    }, 300);
                }, 300);
            }, 1500);
        }

        // Ouvrir le player
        function openPlayer(media) {
            // Mettre à jour les informations
            playerTitle.textContent = media.title;
            playerViews.textContent = `${media.views} vues`;
            playerDate.textContent = media.date;
            playerLikes.textContent = media.likes;
            playerDescription.textContent = media.description;
            
            // Mettre à jour le bouton like
            likeBtn.dataset.mediaId = media.id;
            if (likedVideos.has(media.id)) {
                likeBtn.classList.add('active');
            } else {
                likeBtn.classList.remove('active');
            }
            
            // Créer l'iframe YouTube
            if (media.videoId) {
                mainPlayer.innerHTML = `
                    <iframe 
                        src="https://www.youtube.com/embed/${media.videoId}?autoplay=1&rel=0" 
                        frameborder="0" 
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                        allowfullscreen
                        style="width: 100%; height: 100%;"
                    ></iframe>
                `;
            } else {
                mainPlayer.innerHTML = `
                    <div style="display: flex; align-items: center; justify-content: center; height: 100%; background: black;">
                        <div style="text-align: center; padding: 30px;">
                            <i class="fas fa-image" style="font-size: 3rem; color: #666; margin-bottom: 20px;"></i>
                            <h3 style="margin-bottom: 10px;">Galerie Photo</h3>
                            <p style="color: #999;">Cette galerie contient des photos en haute résolution</p>
                        </div>
                    </div>
                `;
            }
            
            // Mettre à jour les vidéos recommandées
            updateRecommendedVideos(media.id);
            
            // Afficher le modal
            playerModal.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        // Fermer le player
        function closePlayer() {
            playerModal.classList.remove('active');
            document.body.style.overflow = 'auto';
            
            // Arrêter la vidéo
            const iframe = mainPlayer.querySelector('iframe');
            if (iframe) {
                iframe.src = iframe.src.replace('&autoplay=1', '');
            }
        }

        // Mettre à jour les vidéos recommandées
        function updateRecommendedVideos(currentId) {
            const recommendedContainer = document.querySelector('.recommended-videos');
            const recommendedVideos = mediaItems.filter(m => m.id !== currentId).slice(0, 5);
            
            let recommendedHTML = '<h4 class="recommended-title">À suivre</h4>';
            
            recommendedVideos.forEach(video => {
                const channelInitials = video.channel.split(' ').map(word => word[0]).join('').toUpperCase();
                
                recommendedHTML += `
                    <div class="recommended-card" data-id="${video.id}">
                        <div class="recommended-thumbnail">
                            <img src="${video.thumbnail}" alt="${video.title}">
                        </div>
                        <div class="recommended-info">
                            <h4>${video.title.substring(0, 50)}${video.title.length > 50 ? '...' : ''}</h4>
                            <p>${video.channel}</p>
                            <p>${video.views} • ${video.date}</p>
                        </div>
                    </div>
                `;
            });
            
            const recommendedSection = recommendedContainer.closest('.player-sidebar');
            recommendedSection.innerHTML = `
                <div class="player-info">
                    <div class="player-stats">
                        <span id="player-views">${playerViews.textContent}</span>
                        <span id="player-date">${playerDate.textContent}</span>
                        <span id="player-likes">${playerLikes.textContent}</span>
                    </div>
                    <div class="player-actions">
                        <button class="action-btn like" id="like-btn">
                            <i class="fas fa-thumbs-up"></i>
                            J'aime
                        </button>
                        <button class="action-btn" id="share-btn">
                            <i class="fas fa-share"></i>
                            Partager
                        </button>
                        <button class="action-btn" id="save-btn">
                            <i class="fas fa-bookmark"></i>
                            Sauvegarder
                        </button>
                    </div>
                    <div class="player-description" id="player-description">
                        ${playerDescription.textContent}
                    </div>
                </div>
                <div class="recommended-videos">
                    ${recommendedHTML}
                </div>
            `;
            
            // Re-attacher les écouteurs d'événements
            document.getElementById('like-btn').addEventListener('click', function() {
                const mediaId = currentId;
                if (likedVideos.has(mediaId)) {
                    likedVideos.delete(mediaId);
                    this.classList.remove('active');
                } else {
                    likedVideos.add(mediaId);
                    this.classList.add('active');
                }
            });
            
            document.getElementById('share-btn').addEventListener('click', function() {
                navigator.clipboard.writeText(window.location.href);
                alert('Lien copié dans le presse-papier !');
            });
            
            document.getElementById('save-btn').addEventListener('click', function() {
                this.innerHTML = '<i class="fas fa-check"></i> Sauvegardé';
                setTimeout(() => {
                    this.innerHTML = '<i class="fas fa-bookmark"></i> Sauvegarder';
                }, 2000);
            });
            
            // Ajouter les écouteurs pour les vidéos recommandées
            document.querySelectorAll('.recommended-card').forEach(card => {
                card.addEventListener('click', function() {
                    const id = parseInt(this.dataset.id);
                    const media = mediaItems.find(m => m.id === id);
                    openPlayer(media);
                });
            });
        }

        // Gestion du slider
        function startSlideInterval() {
            slideInterval = setInterval(() => {
                goToSlide(currentSlide + 1);
            }, 5000);
        }

        function resetSlideInterval() {
            clearInterval(slideInterval);
            startSlideInterval();
        }

        function goToSlide(index) {
            // Masquer la slide actuelle
            slides[currentSlide].classList.remove('active');
            slideIndicators[currentSlide].classList.remove('active');
            
            // Calculer la nouvelle slide
            currentSlide = index;
            if (currentSlide >= slides.length) currentSlide = 0;
            if (currentSlide < 0) currentSlide = slides.length - 1;
            
            // Afficher la nouvelle slide
            slides[currentSlide].classList.add('active');
            slideIndicators[currentSlide].classList.add('active');
        }

        // Animation des éléments
        function animateElements() {
            const cards = document.querySelectorAll('.media-card');
            cards.forEach((card, index) => {
                card.style.animationDelay = `${index * 0.1}s`;
            });
            
            // Animation au scroll
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('animate-in');
                    }
                });
            }, { threshold: 0.1 });
            
            document.querySelectorAll('.media-card').forEach(card => observer.observe(card));
        }

        // Simulation de recherche
        document.querySelector('.search-btn').addEventListener('click', function() {
            const searchTerm = document.querySelector('.search-input').value;
            if (searchTerm.trim()) {
                alert(`Recherche pour: ${searchTerm}`);
                // Dans une vraie application, on filtrerait les médias ici
            }
        });

        document.querySelector('.search-input').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                document.querySelector('.search-btn').click();
            }
        });
    </script>
       <script>
function sendHeight() {
    const height = document.body.scrollHeight;
    window.parent.postMessage({
        type: 'setHeight',
        iframeId: 'iframe-page-media-1',
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