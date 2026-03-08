
    <style>
        
        /* CLASSES CUSTOM POUR SECTIONS */
        .video-showcase-modern {
            background: linear-gradient(135deg, #0f0c29, #302b63, #24243e);
            color: #fff;
            min-height: 100vh;
            padding: 40px 20px;
        }
        
        .video-showcase-container {
            max-width: 1400px;
            margin: 0 auto;
        }
        
        .video-showcase-header {
            text-align: center;
            margin-bottom: 50px;
        }
        
        .video-showcase-title {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 10px;
            background: linear-gradient(90deg, #00dbde, #fc00ff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        
        .video-showcase-subtitle {
            font-size: 1.2rem;
            opacity: 0.8;
            max-width: 600px;
            margin: 0 auto;
            line-height: 1.6;
        }
        
        /* CARROUSEL MODERNE - EXEMPLE 2 */
        .modern-video-display {
            display: flex;
            gap: 30px;
            margin-bottom: 60px;
        }
        
        /* CLASSES CUSTOM POUR LECTEUR PRINCIPAL */
        .video-player-container {
            flex: 1;
            position: relative;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0,0,0,0.4);
            background: #000;
            height: 600px;
        }
        
        .video-player-main {
            width: 100%;
            height: 100%;
            position: relative;
        }
        
        /* CLASSES CUSTOM POUR OVERLAY DU LECTEUR */
        .player-overlay-content {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(transparent, rgba(0,0,0,0.9));
            padding: 40px;
            z-index: 2;
        }
        
        .player-video-info {
            max-width: 600px;
        }
        
        .player-video-tag {
            display: inline-block;
            background: linear-gradient(90deg, #00dbde, #fc00ff);
            color: white;
            padding: 8px 20px;
            border-radius: 30px;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 20px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .player-video-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 15px;
            line-height: 1.2;
        }
        
        .player-video-stats {
            display: flex;
            gap: 25px;
            margin-bottom: 25px;
            opacity: 0.9;
        }
        
        .player-video-stat {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 15px;
        }
        
        .player-video-actions {
            display: flex;
            gap: 15px;
        }
        
        .player-action-btn {
            padding: 12px 30px;
            border-radius: 30px;
            border: none;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s;
            font-size: 16px;
        }
        
        .player-play-btn {
            background: linear-gradient(90deg, #00dbde, #fc00ff);
            color: white;
        }
        
        .player-play-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.3);
        }
        
        .player-info-btn {
            background: rgba(255,255,255,0.1);
            color: white;
            backdrop-filter: blur(10px);
        }
        
        .player-info-btn:hover {
            background: rgba(255,255,255,0.2);
        }
        
        /* Section playlist */
        .video-playlist-sidebar {
            width: 400px;
            background: rgba(255,255,255,0.05);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 25px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            border: 1px solid rgba(255,255,255,0.1);
        }
        
        .playlist-sidebar-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        
        .playlist-sidebar-title {
            font-size: 1.5rem;
            font-weight: 600;
        }
        
        .playlist-sidebar-controls {
            display: flex;
            gap: 10px;
        }
        
        .playlist-sidebar-btn {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: rgba(255,255,255,0.1);
            border: none;
            color: white;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
        }
        
        .playlist-sidebar-btn:hover {
            background: linear-gradient(90deg, #00dbde, #fc00ff);
        }
        
        .playlist-items-container {
            height: 500px;
            overflow-y: auto;
            padding-right: 10px;
        }
        
        /* Scrollbar personnalisée */
        .playlist-items-container::-webkit-scrollbar {
            width: 6px;
        }
        
        .playlist-items-container::-webkit-scrollbar-track {
            background: rgba(255,255,255,0.05);
            border-radius: 10px;
        }
        
        .playlist-items-container::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, #00dbde, #fc00ff);
            border-radius: 10px;
        }
        
        .playlist-item-card {
            display: flex;
            gap: 15px;
            padding: 15px;
            border-radius: 12px;
            margin-bottom: 12px;
            cursor: pointer;
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
        }
        
        .playlist-item-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, rgba(0,219,222,0.1), rgba(252,0,255,0.1));
            opacity: 0;
            transition: opacity 0.3s;
        }
        
        .playlist-item-card:hover::before {
            opacity: 1;
        }
        
        .playlist-item-card.active {
            background: linear-gradient(90deg, rgba(0,219,222,0.2), rgba(252,0,255,0.2));
            border: 1px solid rgba(255,255,255,0.2);
        }
        
        .playlist-item-card.active::before {
            opacity: 1;
        }
        
        .playlist-item-thumb {
            width: 100px;
            height: 60px;
            border-radius: 8px;
            overflow: hidden;
            flex-shrink: 0;
            position: relative;
        }
        
        .playlist-item-thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s;
        }
        
        .playlist-item-card:hover .playlist-item-thumb img {
            transform: scale(1.1);
        }
        
        .playlist-item-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s;
        }
        
        .playlist-item-card:hover .playlist-item-overlay {
            opacity: 1;
        }
        
        .playlist-item-overlay i {
            font-size: 20px;
            color: white;
        }
        
        .playlist-item-info {
            flex: 1;
        }
        
        .playlist-item-title {
            font-weight: 600;
            margin-bottom: 5px;
            font-size: 15px;
        }
        
        .playlist-item-meta {
            display: flex;
            gap: 15px;
            font-size: 12px;
            opacity: 0.7;
        }
        
        .playlist-item-duration {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        /* Indicateurs de progression */
        .video-progress-container {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: rgba(255,255,255,0.1);
            z-index: 3;
        }
        
        .video-progress-bar {
            height: 100%;
            background: linear-gradient(90deg, #00dbde, #fc00ff);
            width: 0%;
            transition: width 0.3s;
        }
        
        /* Contrôles vidéo */
        .video-controls-panel {
            position: absolute;
            bottom: 20px;
            right: 20px;
            display: flex;
            gap: 15px;
            z-index: 3;
        }
        
        .video-control-btn {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: rgba(0,0,0,0.7);
            border: 2px solid rgba(255,255,255,0.2);
            color: white;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            transition: all 0.3s;
            backdrop-filter: blur(10px);
        }
        
        .video-control-btn:hover {
            background: linear-gradient(90deg, #00dbde, #fc00ff);
            border-color: transparent;
            transform: scale(1.1);
        }
        
        /* Section statistiques */
        .video-stats-section {
            background: rgba(255,255,255,0.05);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 30px;
            margin-top: 40px;
            border: 1px solid rgba(255,255,255,0.1);
        }
        
        .video-stats-title {
            font-size: 1.8rem;
            margin-bottom: 25px;
            text-align: center;
            font-weight: 600;
        }
        
        .video-stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }
        
        .video-stat-card {
            background: rgba(255,255,255,0.03);
            padding: 25px;
            border-radius: 15px;
            text-align: center;
            transition: all 0.3s;
            border: 1px solid rgba(255,255,255,0.05);
        }
        
        .video-stat-card:hover {
            background: rgba(255,255,255,0.07);
            transform: translateY(-5px);
        }
        
        .video-stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            background: linear-gradient(90deg, #00dbde, #fc00ff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 10px;
        }
        
        .video-stat-label {
            font-size: 14px;
            opacity: 0.8;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        /* Modal pour vidéo */
        .video-modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.9);
            z-index: 1000;
            display: none;
            justify-content: center;
            align-items: center;
        }
        
        .video-modal-overlay.active {
            display: flex;
        }
        
        .video-modal-content {
            width: 90%;
            max-width: 1200px;
            position: relative;
        }
        
        .video-modal-player {
            width: 100%;
            height: 600px;
            border-radius: 15px;
            overflow: hidden;
        }
        
        .video-modal-close {
            position: absolute;
            top: -50px;
            right: 0;
            background: linear-gradient(90deg, #00dbde, #fc00ff);
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: none;
            color: white;
            font-size: 20px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        /* Responsive */
        @media (max-width: 1200px) {
            .modern-video-display {
                flex-direction: column;
            }
            
            .video-playlist-sidebar {
                width: 100%;
            }
            
            .video-player-container {
                height: 500px;
            }
            
            .player-video-title {
                font-size: 2rem;
            }
        }
        
        @media (max-width: 768px) {
            .video-showcase-title {
                font-size: 2.2rem;
            }
            
            .video-player-container {
                height: 400px;
            }
            
            .player-overlay-content {
                padding: 25px;
            }
            
            .player-video-title {
                font-size: 1.6rem;
            }
            
            .player-video-actions {
                flex-direction: column;
            }
            
            .playlist-item-card {
                flex-direction: column;
            }
            
            .playlist-item-thumb {
                width: 100%;
                height: 150px;
            }
        }
        
        @media (max-width: 480px) {
            .video-showcase-title {
                font-size: 1.8rem;
            }
            
            .video-player-container {
                height: 350px;
            }
            
            .player-video-title {
                font-size: 1.4rem;
            }
            
            .player-video-stats {
                flex-direction: column;
                gap: 10px;
            }
            
            .video-stats-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
    <section class="video-showcase-modern">
        <div class="video-showcase-container">
            <div class="video-showcase-header">
                <h1 class="video-showcase-title">Carrousel Vidéo Moderne</h1>
                <p class="video-showcase-subtitle">Découvrez notre sélection exclusive de vidéos premium. Naviguez, regardez et profitez d'une expérience immersive.</p>
            </div>
            
            <!-- CARROUSEL MODERNE - EXEMPLE 2 -->
            <div class="modern-video-display">
                <!-- Section vidéo principale -->
                <div class="video-player-container">
                    <!-- Barre de progression -->
                    <div class="video-progress-container">
                        <div class="video-progress-bar" id="progressBar"></div>
                    </div>
                    
                    <!-- Lecteur vidéo principal -->
                    <div class="video-player-main" id="mainVideoPlayer">
                        <!-- La vidéo sera chargée dynamiquement -->
                    </div>
                    
                    <!-- Overlay d'informations -->
                    <div class="player-overlay-content">
                        <div class="player-video-info">
                            <span class="player-video-tag" id="currentCategory">Technologie</span>
                            <h2 class="player-video-title" id="currentTitle">L'IA révolutionne notre quotidien</h2>
                            <div class="player-video-stats">
                                <span class="player-video-stat"><i class="fas fa-eye"></i> <span id="currentViews">2.4M</span> vues</span>
                                <span class="player-video-stat"><i class="fas fa-clock"></i> <span id="currentTime">Il y a 2 jours</span></span>
                                <span class="player-video-stat"><i class="fas fa-thumbs-up"></i> <span id="currentLikes">45K</span> j'aime</span>
                            </div>
                            <div class="player-video-actions">
                                <button class="player-action-btn player-play-btn" id="playMainVideo">
                                    <i class="fas fa-play"></i> Lire maintenant
                                </button>
                                <button class="player-action-btn player-info-btn" id="showVideoInfo">
                                    <i class="fas fa-info-circle"></i> Plus d'infos
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Contrôles vidéo -->
                    <div class="video-controls-panel">
                        <button class="video-control-btn" id="prevVideo">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <button class="video-control-btn" id="nextVideo">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                        <button class="video-control-btn" id="fullscreenBtn">
                            <i class="fas fa-expand"></i>
                        </button>
                    </div>
                </div>
                
                <!-- Section playlist -->
                <div class="video-playlist-sidebar">
                    <div class="playlist-sidebar-header">
                        <h3 class="playlist-sidebar-title">Playlist</h3>
                        <div class="playlist-sidebar-controls">
                            <button class="playlist-sidebar-btn" id="shufflePlaylist">
                                <i class="fas fa-random"></i>
                            </button>
                            <button class="playlist-sidebar-btn" id="autoPlayToggle">
                                <i class="fas fa-play-circle"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="playlist-items-container" id="playlistItems">
                        <!-- Les éléments de playlist seront générés dynamiquement -->
                    </div>
                </div>
            </div>
            
            <!-- Section statistiques -->
            <div class="video-stats-section">
                <h3 class="video-stats-title">Statistiques en Direct</h3>
                <div class="video-stats-grid">
                    <div class="video-stat-card">
                        <div class="video-stat-number" id="totalViews">15.7M</div>
                        <div class="video-stat-label">Vues Totales</div>
                    </div>
                    <div class="video-stat-card">
                        <div class="video-stat-number" id="totalVideos">24</div>
                        <div class="video-stat-label">Vidéos</div>
                    </div>
                    <div class="video-stat-card">
                        <div class="video-stat-number" id="avgDuration">8:30</div>
                        <div class="video-stat-label">Durée Moyenne</div>
                    </div>
                    <div class="video-stat-card">
                        <div class="video-stat-number" id="engagementRate">87%</div>
                        <div class="video-stat-label">Taux d'Engagement</div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Modal pour vidéo -->
    <div class="video-modal-overlay" id="videoModal">
        <div class="video-modal-content">
            <button class="video-modal-close" id="closeModal">
                <i class="fas fa-times"></i>
            </button>
            <iframe class="video-modal-player" id="modalVideoPlayer" 
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                    allowfullscreen>
            </iframe>
        </div>
    </div>

    <script>
        // Données des vidéos pour l'exemple 2
        const modernVideos = [
            {
                id: 1,
                videoId: "jjqgP9dpD1k",
                title: "L'IA révolutionne notre quotidien",
                category: "Technologie",
                views: "2.4M",
                time: "Il y a 2 jours",
                likes: "45K",
                duration: "12:45",
                thumbnail: "https://images.unsplash.com/photo-1677442136019-21780ecad995?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80",
                mainImage: "https://images.unsplash.com/photo-1677442136019-21780ecad995?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80",
                description: "Découvrez comment l'intelligence artificielle transforme nos vies quotidiennes"
            },
            {
                id: 2,
                videoId: "ex3C1-5Dhb8",
                title: "Voyage dans les Alpes Suisses",
                category: "Aventure",
                views: "3.2M",
                time: "Il y a 5 jours",
                likes: "68K",
                duration: "18:20",
                thumbnail: "https://images.unsplash.com/photo-1506905925346-21bda4d32df4?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80",
                mainImage: "https://images.unsplash.com/photo-1506905925346-21bda4d32df4?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80",
                description: "Une aventure épique à travers les plus beaux paysages des Alpes"
            },
            {
                id: 3,
                videoId: "Fq2SbrWnfFI",
                title: "Cuisine moléculaire pour débutants",
                category: "Cuisine",
                views: "1.8M",
                time: "Il y a 1 semaine",
                likes: "32K",
                duration: "15:30",
                thumbnail: "https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80",
                mainImage: "https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80",
                description: "Apprenez les bases de la cuisine moléculaire en 5 recettes faciles"
            },
            {
                id: 4,
                videoId: "dQw4w9WgXcQ",
                title: "Histoire de l'art moderne",
                category: "Art & Culture",
                views: "1.2M",
                time: "Il y a 3 jours",
                likes: "28K",
                duration: "22:15",
                thumbnail: "https://images.unsplash.com/photo-1541961017774-22349e4a1262?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80",
                mainImage: "https://images.unsplash.com/photo-1541961017774-22349e4a1262?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80",
                description: "Un voyage à travers les mouvements artistiques du XXe siècle"
            },
            {
                id: 5,
                videoId: "9bZkp7q19f0",
                title: "Fitness à domicile - Programme complet",
                category: "Fitness",
                views: "4.1M",
                time: "Il y a 2 semaines",
                likes: "92K",
                duration: "25:40",
                thumbnail: "https://images.unsplash.com/photo-1534438327276-14e5300c3a48?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80",
                mainImage: "https://images.unsplash.com/photo-1534438327276-14e5300c3a48?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80",
                description: "Transformez votre corps avec ce programme de 30 jours"
            },
            {
                id: 6,
                videoId: "6JQm5aSjX6g",
                title: "Astrophotographie pour tous",
                category: "Photographie",
                views: "890K",
                time: "Il y a 4 jours",
                likes: "21K",
                duration: "16:55",
                thumbnail: "https://images.unsplash.com/photo-1462331940025-496dfbfc7564?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80",
                mainImage: "https://images.unsplash.com/photo-1462331940025-496dfbfc7564?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80",
                description: "Capturer la beauté de l'univers avec un équipement minimal"
            },
            {
                id: 7,
                videoId: "TdrL3QxjyVw",
                title: "Programmation pour débutants",
                category: "Éducation",
                views: "3.5M",
                time: "Il y a 1 mois",
                likes: "78K",
                duration: "30:10",
                thumbnail: "https://images.unsplash.com/photo-1555066931-4365d14bab8c?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80",
                mainImage: "https://images.unsplash.com/photo-1555066931-4365d14bab8c?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80",
                description: "Apprenez les bases de la programmation en seulement 30 jours"
            },
            {
                id: 8,
                videoId: "JGwWNGJdvx8",
                title: "Musique électronique - Tutoriel complet",
                category: "Musique",
                views: "2.1M",
                time: "Il y a 1 semaine",
                likes: "47K",
                duration: "19:25",
                thumbnail: "https://images.unsplash.com/photo-1511379938547-c1f69419868d?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80",
                mainImage: "https://images.unsplash.com/photo-1511379938547-c1f69419868d?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80",
                description: "Créez votre première piste de musique électronique"
            }
        ];

        // Variables globales
        let currentVideoIndex = 0;
        let isAutoPlay = true;
        let progressInterval;
        let currentProgress = 0;
        
        // Éléments DOM
        const mainVideoPlayer = document.getElementById('mainVideoPlayer');
        const playlistItems = document.getElementById('playlistItems');
        const playMainVideoBtn = document.getElementById('playMainVideo');
        const prevVideoBtn = document.getElementById('prevVideo');
        const nextVideoBtn = document.getElementById('nextVideo');
        const progressBar = document.getElementById('progressBar');
        const videoModal = document.getElementById('videoModal');
        const modalVideoPlayer = document.getElementById('modalVideoPlayer');
        const closeModalBtn = document.getElementById('closeModal');
        const shuffleBtn = document.getElementById('shufflePlaylist');
        const autoPlayToggle = document.getElementById('autoPlayToggle');
        const fullscreenBtn = document.getElementById('fullscreenBtn');
        const showVideoInfoBtn = document.getElementById('showVideoInfo');
        
        // Initialisation
        function init() {
            // Générer la playlist
            generatePlaylist();
            
            // Afficher la première vidéo
            loadVideo(currentVideoIndex);
            
            // Mettre à jour les statistiques
            updateStats();
            
            // Attacher les événements
            attachEvents();
            
            // Démarrer le défilement automatique
            if (isAutoPlay) {
                startAutoPlay();
            }
        }
        
        // Générer la playlist
        function generatePlaylist() {
            playlistItems.innerHTML = '';
            
            modernVideos.forEach((video, index) => {
                const playlistItem = document.createElement('div');
                playlistItem.className = `playlist-item-card ${index === 0 ? 'active' : ''}`;
                playlistItem.setAttribute('data-index', index);
                
                playlistItem.innerHTML = `
                    <div class="playlist-item-thumb">
                        <img src="${video.thumbnail}" alt="${video.title}">
                        <div class="playlist-item-overlay">
                            <i class="fas fa-play"></i>
                        </div>
                    </div>
                    <div class="playlist-item-info">
                        <div class="playlist-item-title">${video.title}</div>
                        <div class="playlist-item-meta">
                            <span>${video.category}</span>
                            <span class="playlist-item-duration">
                                <i class="fas fa-clock"></i> ${video.duration}
                            </span>
                        </div>
                    </div>
                `;
                
                playlistItems.appendChild(playlistItem);
            });
        }
        
        // Charger une vidéo
        function loadVideo(index) {
            if (index < 0 || index >= modernVideos.length) return;
            
            currentVideoIndex = index;
            const video = modernVideos[index];
            
            // Mettre à jour l'interface
            document.getElementById('currentCategory').textContent = video.category;
            document.getElementById('currentTitle').textContent = video.title;
            document.getElementById('currentViews').textContent = video.views;
            document.getElementById('currentTime').textContent = video.time;
            document.getElementById('currentLikes').textContent = video.likes;
            
            // Mettre à jour l'image de fond
            mainVideoPlayer.style.backgroundImage = `linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.7)), url('${video.mainImage}')`;
            mainVideoPlayer.style.backgroundSize = 'cover';
            mainVideoPlayer.style.backgroundPosition = 'center';
            
            // Mettre à jour la playlist active
            updateActivePlaylistItem();
            
            // Réinitialiser la barre de progression
            resetProgress();
            
            // Mettre à jour les statistiques
            updateStats();
        }
        
        // Mettre à jour l'élément actif dans la playlist
        function updateActivePlaylistItem() {
            const items = document.querySelectorAll('.playlist-item-card');
            items.forEach((item, index) => {
                if (index === currentVideoIndex) {
                    item.classList.add('active');
                    // Faire défiler l'élément actif dans la vue
                    item.scrollIntoView({ behavior: 'smooth', block: 'center' });
                } else {
                    item.classList.remove('active');
                }
            });
        }
        
        // Jouer la vidéo dans le modal
        function playVideo() {
            const video = modernVideos[currentVideoIndex];
            modalVideoPlayer.src = `https://www.youtube.com/embed/${video.videoId}?autoplay=1&rel=0&modestbranding=1`;
            videoModal.classList.add('active');
            
            // Démarrer la simulation de progression
            startProgressSimulation();
        }
        
        // Fermer le modal
        function closeModal() {
            videoModal.classList.remove('active');
            modalVideoPlayer.src = '';
            stopProgressSimulation();
        }
        
        // Vidéo suivante
        function nextVideo() {
            let nextIndex = currentVideoIndex + 1;
            if (nextIndex >= modernVideos.length) {
                nextIndex = 0;
            }
            loadVideo(nextIndex);
            
            // Si auto-play est activé et une vidéo est en cours, jouer la suivante
            if (isAutoPlay && videoModal.classList.contains('active')) {
                setTimeout(() => {
                    playVideo();
                }, 500);
            }
        }
        
        // Vidéo précédente
        function prevVideo() {
            let prevIndex = currentVideoIndex - 1;
            if (prevIndex < 0) {
                prevIndex = modernVideos.length - 1;
            }
            loadVideo(prevIndex);
            
            // Si auto-play est activé et une vidéo est en cours, jouer la précédente
            if (isAutoPlay && videoModal.classList.contains('active')) {
                setTimeout(() => {
                    playVideo();
                }, 500);
            }
        }
        
        // Mélanger la playlist
        function shufflePlaylist() {
            // Mélanger le tableau de vidéos
            for (let i = modernVideos.length - 1; i > 0; i--) {
                const j = Math.floor(Math.random() * (i + 1));
                [modernVideos[i], modernVideos[j]] = [modernVideos[j], modernVideos[i]];
            }
            
            // Régénérer la playlist
            generatePlaylist();
            
            // Recharger la vidéo actuelle
            loadVideo(0);
            
            // Mettre à jour le bouton shuffle
            shuffleBtn.innerHTML = '<i class="fas fa-check"></i>';
            setTimeout(() => {
                shuffleBtn.innerHTML = '<i class="fas fa-random"></i>';
            }, 1000);
        }
        
        // Basculer l'auto-play
        function toggleAutoPlay() {
            isAutoPlay = !isAutoPlay;
            
            if (isAutoPlay) {
                autoPlayToggle.innerHTML = '<i class="fas fa-pause-circle"></i>';
                autoPlayToggle.title = "Désactiver l'auto-play";
                startAutoPlay();
            } else {
                autoPlayToggle.innerHTML = '<i class="fas fa-play-circle"></i>';
                autoPlayToggle.title = "Activer l'auto-play";
                stopAutoPlay();
            }
        }
        
        // Démarrer l'auto-play
        function startAutoPlay() {
            stopAutoPlay();
            if (isAutoPlay) {
                autoPlayInterval = setInterval(() => {
                    nextVideo();
                }, 10000); // Change toutes les 10 secondes
            }
        }
        
        // Arrêter l'auto-play
        function stopAutoPlay() {
            if (autoPlayInterval) {
                clearInterval(autoPlayInterval);
            }
        }
        
        // Simulation de progression de la vidéo
        function startProgressSimulation() {
            stopProgressSimulation();
            currentProgress = 0;
            progressBar.style.width = '0%';
            
            progressInterval = setInterval(() => {
                currentProgress += 0.5; // 0.5% par intervalle
                if (currentProgress >= 100) {
                    currentProgress = 100;
                    stopProgressSimulation();
                    
                    // Si auto-play est activé, passer à la vidéo suivante
                    if (isAutoPlay) {
                        setTimeout(() => {
                            nextVideo();
                            playVideo();
                        }, 1000);
                    }
                }
                progressBar.style.width = `${currentProgress}%`;
            }, 100); // Mettre à jour toutes les 100ms
        }
        
        function stopProgressSimulation() {
            if (progressInterval) {
                clearInterval(progressInterval);
            }
        }
        
        function resetProgress() {
            stopProgressSimulation();
            currentProgress = 0;
            progressBar.style.width = '0%';
        }
        
        // Mettre à jour les statistiques
        function updateStats() {
            // Calculer les statistiques
            const totalViews = modernVideos.reduce((sum, video) => {
                const views = parseFloat(video.views) * (video.views.includes('M') ? 1000000 : 1000);
                return sum + views;
            }, 0);
            
            const avgLikes = modernVideos.reduce((sum, video) => {
                const likes = parseFloat(video.likes) * (video.likes.includes('K') ? 1000 : 1);
                return sum + likes;
            }, 0) / modernVideos.length;
            
            // Formater et afficher
            document.getElementById('totalViews').textContent = (totalViews / 1000000).toFixed(1) + 'M';
            document.getElementById('totalVideos').textContent = modernVideos.length;
            document.getElementById('avgDuration').textContent = '8:30'; // Valeur fixe pour l'exemple
            document.getElementById('engagementRate').textContent = Math.round((avgLikes / 1000) * 10) + '%';
        }
        
        // Mode plein écran
        function toggleFullscreen() {
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen().catch(err => {
                    console.log(`Erreur de plein écran: ${err.message}`);
                });
                fullscreenBtn.innerHTML = '<i class="fas fa-compress"></i>';
            } else {
                if (document.exitFullscreen) {
                    document.exitFullscreen();
                    fullscreenBtn.innerHTML = '<i class="fas fa-expand"></i>';
                }
            }
        }
        
        // Attacher les événements
        function attachEvents() {
            // Bouton lecture principale
            playMainVideoBtn.addEventListener('click', playVideo);
            
            // Boutons de navigation
            prevVideoBtn.addEventListener('click', prevVideo);
            nextVideoBtn.addEventListener('click', nextVideo);
            
            // Boutons de la playlist
            shuffleBtn.addEventListener('click', shufflePlaylist);
            autoPlayToggle.addEventListener('click', toggleAutoPlay);
            
            // Bouton plein écran
            fullscreenBtn.addEventListener('click', toggleFullscreen);
            
            // Bouton informations
            showVideoInfoBtn.addEventListener('click', () => {
                alert(`Description: ${modernVideos[currentVideoIndex].description}\n\nDurée: ${modernVideos[currentVideoIndex].duration}`);
            });
            
            // Modal
            closeModalBtn.addEventListener('click', closeModal);
            videoModal.addEventListener('click', (e) => {
                if (e.target === videoModal) {
                    closeModal();
                }
            });
            
            // Éléments de playlist
            playlistItems.addEventListener('click', (e) => {
                const playlistItem = e.target.closest('.playlist-item-card');
                if (playlistItem) {
                    const index = parseInt(playlistItem.getAttribute('data-index'));
                    loadVideo(index);
                    
                    // Si auto-play est activé, jouer la vidéo
                    if (isAutoPlay) {
                        setTimeout(() => {
                            playVideo();
                        }, 300);
                    }
                }
            });
            
            // Navigation au clavier
            document.addEventListener('keydown', (e) => {
                if (e.key === 'ArrowLeft') {
                    prevVideo();
                } else if (e.key === 'ArrowRight') {
                    nextVideo();
                } else if (e.key === ' ') {
                    e.preventDefault();
                    if (videoModal.classList.contains('active')) {
                        closeModal();
                    } else {
                        playVideo();
                    }
                } else if (e.key === 'Escape') {
                    closeModal();
                }
            });
            
            // Événement de changement de plein écran
            document.addEventListener('fullscreenchange', () => {
                if (!document.fullscreenElement) {
                    fullscreenBtn.innerHTML = '<i class="fas fa-expand"></i>';
                }
            });
        }
        
        // Initialiser l'application
        init();
    </script>