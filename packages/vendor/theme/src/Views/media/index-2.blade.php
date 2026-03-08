<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lecteur Media Slideshow Moderne</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #0f2027 0%, #203a43 50%, #2c5364 100%);
            color: #fff;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        
        .container {
            max-width: 1200px;
            width: 100%;
            margin: 0 auto;
        }
        
        .section-title {
            text-align: center;
            margin-bottom: 40px;
            position: relative;
        }
        
        .section-title h1 {
            font-size: 2.8rem;
            font-weight: 700;
            background: linear-gradient(90deg, #00dbde, #fc00ff);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            margin-bottom: 15px;
            letter-spacing: 1px;
        }
        
        .section-title p {
            font-size: 1.2rem;
            color: #b0c4de;
            max-width: 600px;
            margin: 0 auto;
            line-height: 1.6;
        }
        
        .slideshow-player {
            background: rgba(15, 25, 35, 0.85);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .player-header {
            padding: 25px 30px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: rgba(0, 0, 0, 0.2);
        }
        
        .player-title {
            font-size: 1.8rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .player-title i {
            color: #00dbde;
            font-size: 2rem;
        }
        
        .player-controls {
            display: flex;
            gap: 15px;
        }
        
        .control-btn {
            background: rgba(255, 255, 255, 0.1);
            border: none;
            color: white;
            width: 45px;
            height: 45px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 1.2rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        
        .control-btn:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-3px);
        }
        
        .control-btn.active {
            background: linear-gradient(135deg, #00dbde, #fc00ff);
            color: white;
        }
        
        .player-body {
            display: flex;
            min-height: 500px;
        }
        
        .media-display {
            flex: 1;
            padding: 30px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            background: rgba(0, 0, 0, 0.3);
            position: relative;
            overflow: hidden;
        }
        
        .media-container {
            width: 100%;
            max-width: 800px;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.5);
            position: relative;
            animation: fadeIn 0.8s ease-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.95); }
            to { opacity: 1; transform: scale(1); }
        }
        
        .media-item {
            display: none;
            width: 100%;
            height: auto;
        }
        
        .media-item.active {
            display: block;
            animation: slideIn 0.8s ease-out;
        }
        
        @keyframes slideIn {
            from { opacity: 0; transform: translateX(30px); }
            to { opacity: 1; transform: translateX(0); }
        }
        
        .media-item img, .media-item video {
            width: 100%;
            height: auto;
            display: block;
        }
        
        .media-info {
            padding: 20px 0;
            text-align: center;
            width: 100%;
            max-width: 800px;
        }
        
        .media-title {
            font-size: 1.8rem;
            font-weight: 600;
            margin-bottom: 10px;
            color: #fff;
        }
        
        .media-description {
            color: #b0c4de;
            font-size: 1.1rem;
            line-height: 1.6;
        }
        
        .media-sidebar {
            width: 300px;
            padding: 25px;
            border-left: 1px solid rgba(255, 255, 255, 0.1);
            background: rgba(0, 0, 0, 0.2);
            overflow-y: auto;
            max-height: 500px;
        }
        
        .media-thumbnails {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        
        .thumbnail-item {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 12px;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid transparent;
        }
        
        .thumbnail-item:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: translateX(5px);
        }
        
        .thumbnail-item.active {
            background: rgba(0, 219, 222, 0.15);
            border-color: #00dbde;
        }
        
        .thumbnail-img {
            width: 80px;
            height: 60px;
            border-radius: 8px;
            overflow: hidden;
            flex-shrink: 0;
        }
        
        .thumbnail-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .thumbnail-info h4 {
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 5px;
        }
        
        .thumbnail-info p {
            font-size: 0.85rem;
            color: #a0b3d0;
        }
        
        .player-footer {
            padding: 20px 30px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: rgba(0, 0, 0, 0.2);
        }
        
        .progress-container {
            flex: 1;
            margin-right: 30px;
        }
        
        .progress-bar {
            height: 8px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 4px;
            overflow: hidden;
            margin-bottom: 8px;
            cursor: pointer;
        }
        
        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #00dbde, #fc00ff);
            border-radius: 4px;
            width: 30%;
            transition: width 0.3s ease;
            position: relative;
        }
        
        .progress-fill::after {
            content: '';
            position: absolute;
            right: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 16px;
            height: 16px;
            background: white;
            border-radius: 50%;
            box-shadow: 0 0 10px rgba(0, 219, 222, 0.8);
        }
        
        .time-info {
            display: flex;
            justify-content: space-between;
            font-size: 0.9rem;
            color: #b0c4de;
        }
        
        .player-actions {
            display: flex;
            gap: 10px;
        }
        
        .action-btn {
            background: rgba(255, 255, 255, 0.1);
            border: none;
            color: white;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 1.3rem;
            transition: all 0.3s ease;
        }
        
        .action-btn:hover {
            background: linear-gradient(135deg, #00dbde, #fc00ff);
            transform: scale(1.1);
        }
        
        .action-btn.play-pause {
            width: 60px;
            height: 60px;
            font-size: 1.6rem;
            background: linear-gradient(135deg, #00dbde, #fc00ff);
        }
        
        .media-counter {
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            color: #b0c4de;
            gap: 10px;
        }
        
        .counter-current {
            font-size: 1.8rem;
            font-weight: 700;
            color: white;
        }
        
        .counter-total {
            font-size: 1.3rem;
        }
        
        /* Animation pour le changement de slide */
        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(0, 219, 222, 0.7); }
            70% { box-shadow: 0 0 0 10px rgba(0, 219, 222, 0); }
            100% { box-shadow: 0 0 0 0 rgba(0, 219, 222, 0); }
        }
        
        .pulse-effect {
            animation: pulse 1.5s infinite;
        }
        
        /* Responsive */
        @media (max-width: 992px) {
            .player-body {
                flex-direction: column;
            }
            
            .media-sidebar {
                width: 100%;
                border-left: none;
                border-top: 1px solid rgba(255, 255, 255, 0.1);
                max-height: 300px;
            }
            
            .media-thumbnails {
                flex-direction: row;
                overflow-x: auto;
                padding-bottom: 10px;
            }
            
            .thumbnail-item {
                flex-direction: column;
                min-width: 150px;
            }
            
            .thumbnail-img {
                width: 100%;
                height: 100px;
            }
        }
        
        @media (max-width: 768px) {
            .section-title h1 {
                font-size: 2.2rem;
            }
            
            .player-header {
                flex-direction: column;
                gap: 20px;
                text-align: center;
            }
            
            .player-footer {
                flex-direction: column;
                gap: 20px;
            }
            
            .progress-container {
                margin-right: 0;
                width: 100%;
            }
            
            .media-display {
                padding: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="section-title">
            <h1>Lecteur Media Slideshow</h1>
            <p>Découvrez notre collection multimédia avec ce lecteur moderne et interactif. Naviguez entre les différents médias avec des animations fluides.</p>
        </div>
        
        <div class="slideshow-player">
            <div class="player-header">
                <div class="player-title">
                    <i class="fas fa-play-circle"></i>
                    <span>Galerie Multimédia Premium</span>
                </div>
                
                <div class="player-controls">
                    <button class="control-btn active" id="btn-all" title="Tous les médias">
                        <i class="fas fa-th-large"></i>
                    </button>
                    <button class="control-btn" id="btn-photos" title="Photos seulement">
                        <i class="fas fa-image"></i>
                    </button>
                    <button class="control-btn" id="btn-videos" title="Vidéos seulement">
                        <i class="fas fa-video"></i>
                    </button>
                </div>
            </div>
            
            <div class="player-body">
                <div class="media-display">
                    <div class="media-container">
                        <!-- Les médias sont insérés dynamiquement par JavaScript -->
                    </div>
                    
                    <div class="media-info">
                        <h2 class="media-title">Titre du média</h2>
                        <p class="media-description">Description du média actuellement affiché</p>
                    </div>
                </div>
                
                <div class="media-sidebar">
                    <div class="media-thumbnails">
                        <!-- Les vignettes sont insérées dynamiquement par JavaScript -->
                    </div>
                </div>
            </div>
            
            <div class="player-footer">
                <div class="progress-container">
                    <div class="progress-bar" id="progress-bar">
                        <div class="progress-fill" id="progress-fill"></div>
                    </div>
                    <div class="time-info">
                        <span id="current-time">0:00</span>
                        <span id="total-time">0:00</span>
                    </div>
                </div>
                
                <div class="media-counter">
                    <span class="counter-current" id="current-slide">1</span>
                    <span class="counter-separator">/</span>
                    <span class="counter-total" id="total-slides">5</span>
                </div>
                
                <div class="player-actions">
                    <button class="action-btn" id="btn-prev" title="Précédent">
                        <i class="fas fa-step-backward"></i>
                    </button>
                    <button class="action-btn play-pause pulse-effect" id="btn-play-pause" title="Lecture/Pause">
                        <i class="fas fa-play" id="play-icon"></i>
                    </button>
                    <button class="action-btn" id="btn-next" title="Suivant">
                        <i class="fas fa-step-forward"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Données des médias
        const mediaData = [
            {
                id: 1,
                type: "image",
                title: "Paysage Montagneux",
                description: "Un magnifique panorama de montagnes enneigées au coucher du soleil.",
                src: "https://images.unsplash.com/photo-1506905925346-21bda4d32df4?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1350&q=80",
                thumbnail: "https://images.unsplash.com/photo-1506905925346-21bda4d32df4?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=300&q=80",
                duration: null
            },
            {
                id: 2,
                type: "video",
                title: "Forêt Tropicale",
                description: "Explorez la beauté d'une forêt tropicale luxuriante avec ses cascades.",
                src: "https://assets.mixkit.co/videos/preview/mixkit-waterfall-in-forest-2213-large.mp4",
                thumbnail: "https://images.unsplash.com/photo-1441974231531-c6227db76b6e?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=300&q=80",
                duration: "0:15"
            },
            {
                id: 3,
                type: "image",
                title: "Ville Nocturne",
                description: "Lumières étincelantes d'une métropole moderne la nuit.",
                src: "https://images.unsplash.com/photo-1477959858617-67f85cf4f1df?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1350&q=80",
                thumbnail: "https://images.unsplash.com/photo-1477959858617-67f85cf4f1df?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=300&q=80",
                duration: null
            },
            {
                id: 4,
                type: "video",
                title: "Océan et Vagues",
                description: "Les vagues puissantes de l'océan s'écrasant sur les rochers.",
                src: "https://assets.mixkit.co/videos/preview/mixkit-sea-waves-crashing-on-rocks-2222-large.mp4",
                thumbnail: "https://images.unsplash.com/photo-1505142468610-359e7d316be0?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=300&q=80",
                duration: "0:12"
            },
            {
                id: 5,
                type: "image",
                title: "Désert et Ciel Étoilé",
                description: "Une nuit étoilée au-dessus des dunes de sable du désert.",
                src: "https://images.unsplash.com/photo-1519681393784-d120267933ba?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1350&q=80",
                thumbnail: "https://images.unsplash.com/photo-1519681393784-d120267933ba?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=300&q=80",
                duration: null
            }
        ];

        // Variables globales
        let currentSlide = 0;
        let totalSlides = mediaData.length;
        let isPlaying = false;
        let playInterval;
        let currentMediaType = 'all';
        let filteredMedia = [...mediaData];

        // Initialisation
        document.addEventListener('DOMContentLoaded', function() {
            initSlideshow();
            setupEventListeners();
            updateSlideshow();
            updateProgressBar();
        });

        // Initialisation du slideshow
        function initSlideshow() {
            const mediaContainer = document.querySelector('.media-container');
            const thumbnailsContainer = document.querySelector('.media-thumbnails');
            
            // Afficher le total des slides
            document.getElementById('total-slides').textContent = totalSlides;
            
            // Créer les éléments médias
            mediaData.forEach((media, index) => {
                // Créer l'élément média principal
                let mediaElement;
                if (media.type === 'image') {
                    mediaElement = document.createElement('img');
                    mediaElement.src = media.src;
                    mediaElement.alt = media.title;
                    mediaElement.classList.add('media-item');
                } else if (media.type === 'video') {
                    mediaElement = document.createElement('video');
                    mediaElement.src = media.src;
                    mediaElement.controls = true;
                    mediaElement.classList.add('media-item');
                    
                    // Mettre à jour la durée de la vidéo lorsqu'elle est chargée
                    mediaElement.addEventListener('loadedmetadata', function() {
                        updateVideoDuration(index, mediaElement.duration);
                    });
                }
                
                if (index === 0) mediaElement.classList.add('active');
                mediaContainer.appendChild(mediaElement);
                
                // Créer la vignette
                const thumbnailItem = document.createElement('div');
                thumbnailItem.classList.add('thumbnail-item');
                if (index === 0) thumbnailItem.classList.add('active');
                thumbnailItem.dataset.index = index;
                
                thumbnailItem.innerHTML = `
                    <div class="thumbnail-img">
                        <img src="${media.thumbnail}" alt="${media.title}">
                    </div>
                    <div class="thumbnail-info">
                        <h4>${media.title}</h4>
                        <p>${media.type === 'image' ? 'Image' : 'Vidéo'} ${media.duration ? ' • ' + media.duration : ''}</p>
                    </div>
                `;
                
                thumbnailsContainer.appendChild(thumbnailItem);
                
                // Ajouter l'événement de clic sur la vignette
                thumbnailItem.addEventListener('click', function() {
                    const index = parseInt(this.dataset.index);
                    goToSlide(index);
                });
            });
            
            // Mettre à jour les informations du premier média
            updateMediaInfo(0);
        }

        // Configuration des événements
        function setupEventListeners() {
            // Boutons de contrôle
            document.getElementById('btn-all').addEventListener('click', function() {
                setMediaFilter('all');
                updateActiveFilterButton(this);
            });
            
            document.getElementById('btn-photos').addEventListener('click', function() {
                setMediaFilter('image');
                updateActiveFilterButton(this);
            });
            
            document.getElementById('btn-videos').addEventListener('click', function() {
                setMediaFilter('video');
                updateActiveFilterButton(this);
            });
            
            // Boutons de navigation
            document.getElementById('btn-prev').addEventListener('click', prevSlide);
            document.getElementById('btn-next').addEventListener('click', nextSlide);
            document.getElementById('btn-play-pause').addEventListener('click', togglePlayPause);
            
            // Barre de progression
            const progressBar = document.getElementById('progress-bar');
            progressBar.addEventListener('click', function(e) {
                const rect = this.getBoundingClientRect();
                const pos = (e.clientX - rect.left) / this.offsetWidth;
                const slideIndex = Math.floor(pos * filteredMedia.length);
                goToSlide(slideIndex);
            });
            
            // Touches du clavier
            document.addEventListener('keydown', function(e) {
                switch(e.key) {
                    case 'ArrowLeft':
                        prevSlide();
                        break;
                    case 'ArrowRight':
                        nextSlide();
                        break;
                    case ' ':
                        e.preventDefault();
                        togglePlayPause();
                        break;
                }
            });
        }

        // Navigation entre les slides
        function goToSlide(index) {
            // S'assurer que l'index est dans les limites
            if (index < 0) index = filteredMedia.length - 1;
            if (index >= filteredMedia.length) index = 0;
            
            // Obtenir l'index réel dans les données médias complètes
            const realIndex = mediaData.findIndex(media => media.id === filteredMedia[index].id);
            
            // Mettre à jour le slide courant
            currentSlide = realIndex;
            
            // Mettre à jour l'affichage
            updateSlideshow();
            
            // Réinitialiser l'intervalle de lecture si en mode lecture
            if (isPlaying) {
                clearInterval(playInterval);
                startAutoPlay();
            }
        }

        function nextSlide() {
            let nextIndex = filteredMedia.findIndex(media => media.id === mediaData[currentSlide].id) + 1;
            if (nextIndex >= filteredMedia.length) nextIndex = 0;
            
            const realIndex = mediaData.findIndex(media => media.id === filteredMedia[nextIndex].id);
            goToSlide(realIndex);
        }

        function prevSlide() {
            let prevIndex = filteredMedia.findIndex(media => media.id === mediaData[currentSlide].id) - 1;
            if (prevIndex < 0) prevIndex = filteredMedia.length - 1;
            
            const realIndex = mediaData.findIndex(media => media.id === filteredMedia[prevIndex].id);
            goToSlide(realIndex);
        }

        // Lecture automatique
        function startAutoPlay() {
            clearInterval(playInterval);
            playInterval = setInterval(nextSlide, 5000); // Change de slide toutes les 5 secondes
        }

        function togglePlayPause() {
            const playPauseBtn = document.getElementById('btn-play-pause');
            const playIcon = document.getElementById('play-icon');
            
            if (isPlaying) {
                // Mettre en pause
                clearInterval(playInterval);
                playIcon.classList.remove('fa-pause');
                playIcon.classList.add('fa-play');
                playPauseBtn.classList.add('pulse-effect');
                isPlaying = false;
                
                // Mettre en pause la vidéo si c'est une vidéo
                const currentMedia = document.querySelector('.media-item.active');
                if (currentMedia.tagName === 'VIDEO') {
                    currentMedia.pause();
                }
            } else {
                // Lancer la lecture
                startAutoPlay();
                playIcon.classList.remove('fa-play');
                playIcon.classList.add('fa-pause');
                playPauseBtn.classList.remove('pulse-effect');
                isPlaying = true;
                
                // Lancer la vidéo si c'est une vidéo
                const currentMedia = document.querySelector('.media-item.active');
                if (currentMedia.tagName === 'VIDEO') {
                    currentMedia.play();
                }
            }
        }

        // Filtrage des médias
        function setMediaFilter(type) {
            currentMediaType = type;
            
            if (type === 'all') {
                filteredMedia = [...mediaData];
            } else {
                filteredMedia = mediaData.filter(media => media.type === type);
            }
            
            // Réinitialiser au premier slide après filtrage
            currentSlide = mediaData.findIndex(media => media.id === filteredMedia[0].id);
            
            // Mettre à jour l'affichage
            updateSlideshow();
            updateThumbnails();
            
            // Mettre à jour le compteur
            document.getElementById('total-slides').textContent = filteredMedia.length;
            
            // Réinitialiser la lecture si active
            if (isPlaying) {
                clearInterval(playInterval);
                startAutoPlay();
            }
        }

        function updateActiveFilterButton(activeButton) {
            // Retirer la classe active de tous les boutons de filtre
            document.querySelectorAll('.control-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            
            // Ajouter la classe active au bouton cliqué
            activeButton.classList.add('active');
        }

        // Mise à jour de l'affichage
        function updateSlideshow() {
            // Mettre à jour le média affiché
            const mediaItems = document.querySelectorAll('.media-item');
            mediaItems.forEach((item, index) => {
                item.classList.remove('active');
                if (index === currentSlide) {
                    item.classList.add('active');
                    
                    // Si c'est une vidéo et que le mode lecture est activé, la lancer
                    if (isPlaying && item.tagName === 'VIDEO') {
                        item.play();
                    }
                }
            });
            
            // Mettre à jour les informations du média
            updateMediaInfo(currentSlide);
            
            // Mettre à jour le compteur de slide
            const slideIndexInFiltered = filteredMedia.findIndex(media => media.id === mediaData[currentSlide].id);
            document.getElementById('current-slide').textContent = slideIndexInFiltered + 1;
            
            // Mettre à jour les vignettes actives
            updateActiveThumbnail();
            
            // Mettre à jour la barre de progression
            updateProgressBar();
        }

        function updateMediaInfo(index) {
            const media = mediaData[index];
            document.querySelector('.media-title').textContent = media.title;
            document.querySelector('.media-description').textContent = media.description;
        }

        function updateThumbnails() {
            const thumbnailItems = document.querySelectorAll('.thumbnail-item');
            thumbnailItems.forEach(item => {
                const index = parseInt(item.dataset.index);
                const media = mediaData[index];
                
                // Afficher ou masquer selon le filtre
                if (currentMediaType === 'all' || media.type === currentMediaType) {
                    item.style.display = 'flex';
                } else {
                    item.style.display = 'none';
                }
            });
        }

        function updateActiveThumbnail() {
            const thumbnailItems = document.querySelectorAll('.thumbnail-item');
            thumbnailItems.forEach(item => {
                const index = parseInt(item.dataset.index);
                item.classList.remove('active');
                if (index === currentSlide) {
                    item.classList.add('active');
                    
                    // Faire défiler jusqu'à la vignette active
                    item.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
                }
            });
        }

        function updateProgressBar() {
            const slideIndexInFiltered = filteredMedia.findIndex(media => media.id === mediaData[currentSlide].id);
            const progressPercent = ((slideIndexInFiltered + 1) / filteredMedia.length) * 100;
            document.getElementById('progress-fill').style.width = `${progressPercent}%`;
            
            // Mettre à jour le temps actuel et total
            document.getElementById('current-time').textContent = formatTime(slideIndexInFiltered + 1);
            document.getElementById('total-time').textContent = formatTime(filteredMedia.length);
        }

        function updateVideoDuration(index, duration) {
            // Formater la durée de la vidéo
            const minutes = Math.floor(duration / 60);
            const seconds = Math.floor(duration % 60);
            const formattedDuration = `${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;
            
            // Mettre à jour les données
            mediaData[index].duration = formattedDuration;
            
            // Mettre à jour l'affichage de la vignette si c'est le média actuel
            if (index === currentSlide) {
                const thumbnailItems = document.querySelectorAll('.thumbnail-item');
                thumbnailItems[index].querySelector('.thumbnail-info p').textContent = 
                    `Vidéo • ${formattedDuration}`;
            }
        }

        function formatTime(value) {
            // Simuler un format de temps basé sur l'index
            const minutes = Math.floor(value / 2);
            const seconds = (value % 2) * 30;
            return `${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;
        }
    </script>
       <script>
function sendHeight() {
    const height = document.body.scrollHeight;
    window.parent.postMessage({
        type: 'setHeight',
        iframeId: 'iframe-page-media-2',
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