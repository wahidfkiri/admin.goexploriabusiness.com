
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Roboto', sans-serif;
        }
        
        body {
            background-color: #ffffff;
            color: #333;
            line-height: 1.6;
        }
        
        /* Container principal en pleine largeur */
        .container {
            width: 100%;
            margin: 0 auto;
            padding: 20px 0;
        }
        
        /* Styles pour le carrousel */
        .carousel-section {
            margin-bottom: 50px;
            position: relative;
        }
        
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding: 0 20px;
        }
        
        .section-title {
            font-size: 24px;
            font-weight: 700;
            color: #222;
        }
        
        .carousel-controls {
            display: flex;
            gap: 10px;
        }
        
        .carousel-btn {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #f0f0f0;
            border: none;
            color: #333;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
            z-index: 20;
        }
        
        .carousel-btn:hover {
            background-color: #333;
            color: white;
        }
        
        /* Carrousel principal en pleine largeur */
        .main-carousel {
            position: relative;
            overflow: hidden;
            height: 600px;
            width: 100%;
            background-color: #000;
        }
        
        .carousel-slide {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            transition: opacity 0.5s ease-in-out;
            background-size: cover;
            background-position: center;
            display: flex;
            align-items: flex-end;
            padding: 40px;
        }
        
        .carousel-slide.active {
            opacity: 1;
        }
        
        .slide-content {
            max-width: 600px;
            background: rgba(255, 255, 255, 0.9);
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin: 0 40px;
            position: relative;
            z-index: 2;
        }
        
        .video-category {
            display: inline-block;
            background-color: #ff3753;
            color: white;
            padding: 5px 15px;
            border-radius: 4px;
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 15px;
        }
        
        .video-title {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 15px;
            line-height: 1.3;
            color: #222;
        }
        
        .video-meta {
            display: flex;
            gap: 20px;
            color: #666;
            font-size: 14px;
            margin-bottom: 20px;
        }
        
        .video-meta i {
            margin-right: 5px;
        }
        
        .play-btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background-color: #ff3753;
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 30px;
            font-weight: 500;
            cursor: pointer;
            transition: transform 0.3s;
        }
        
        .play-btn:hover {
            transform: scale(1.05);
        }
        
        /* NOUVEAU: Contrôles de navigation en bas du carrousel principal */
        .main-carousel-controls {
            position: absolute;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 15px;
            z-index: 10;
        }
        
        .main-carousel-btn {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background-color: rgba(255, 255, 255, 0.9);
            border: none;
            color: #333;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
            font-size: 18px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        }
        
        .main-carousel-btn:hover {
            background-color: #ff3753;
            color: white;
            transform: scale(1.1);
        }
        
        /* Indicateurs de slide en bas */
        .slide-indicators {
            position: absolute;
            bottom: 100px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 10px;
            z-index: 10;
        }
        
        .slide-indicator {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background-color: rgba(255, 255, 255, 0.5);
            border: 2px solid rgba(255, 255, 255, 0.8);
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .slide-indicator.active {
            background-color: #ff3753;
            border-color: white;
            transform: scale(1.2);
        }
        
        /* Carrousel miniature en pleine largeur */
        .thumbnail-carousel-container {
            width: 100%;
            position: relative;
            margin-top: 20px;
            padding: 10px 0;
            background-color: #f8f8f8;
        }
        
        .thumbnail-carousel {
            position: relative;
            width: 100%;
            overflow: hidden;
            padding: 10px 0;
            cursor: grab;
        }
        
        .thumbnail-carousel:active {
            cursor: grabbing;
        }
        
        .thumbnail-track {
            display: flex;
            transition: transform 0.5s ease-out;
            will-change: transform;
            padding: 0 20px;
            user-select: none;
        }
        
        /* Animation de défilement infinie de droite à gauche */
        @keyframes scrollRightToLeft {
            0% {
                transform: translateX(0);
            }
            100% {
                transform: translateX(calc(-340px * 7));
            }
        }
        
        .thumbnail-track.auto-scroll {
            animation: scrollRightToLeft 40s linear infinite;
        }
        
        .thumbnail-track.auto-scroll.paused {
            animation-play-state: paused;
        }
        
        /* Styles des miniatures */
        .thumbnail-item {
            flex: 0 0 auto;
            width: 360px;
            height: 240px;
            overflow: hidden;
            position: relative;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
            flex-shrink: 0;
            /* margin-right: 20px; */
        }
        
        .thumbnail-item:last-child {
            margin-right: 0;
        }
        
        .thumbnail-item:hover, .thumbnail-item.active {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        }
        
        .thumbnail-item.active {
            border: 3px solid #ff3753;
        }
        
        .thumbnail-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.4s;
        }
        
        .thumbnail-item:hover img {
            transform: scale(1.05);
        }
        
        .thumbnail-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(transparent, rgba(0,0,0,0.8));
            padding: 12px;
            font-size: 14px;
            font-weight: 500;
            color: white;
            z-index: 1;
        }
        
        .thumbnail-hover-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s ease-out;
            z-index: 1;
        }
        
        .thumbnail-play-btn {
            position: relative;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background-color: rgba(0, 0, 0, 0.8);
            border: 2px solid white;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            opacity: 0;
            transform: scale(0.5);
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            z-index: 2;
            font-size: 18px;
        }
        
        .thumbnail-item:hover .thumbnail-hover-overlay {
            opacity: 1;
        }
        
        .thumbnail-item:hover .thumbnail-play-btn {
            opacity: 1;
            transform: scale(1);
        }
        
        /* Indicateur de prévisualisation */
        .preview-indicator {
            position: absolute;
            top: 10px;
            left: 10px;
            background-color: #ff3753;
            color: white;
            padding: 3px 10px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 500;
            opacity: 0;
            transition: opacity 0.3s;
            z-index: 2;
        }
        
        .thumbnail-item.active .preview-indicator {
            opacity: 1;
        }
        
        /* Iframe pour les vidéos YouTube */
        .video-iframe-container {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 10;
            display: none;
        }
        
        .video-iframe-container.active {
            display: block;
        }
        
        .video-iframe {
            width: 100%;
            height: 100%;
            border: none;
        }
        
        .close-video-btn {
            position: absolute;
            top: 20px;
            right: 20px;
            background-color: rgba(0, 0, 0, 0.7);
            color: white;
            border: none;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            font-size: 20px;
            cursor: pointer;
            z-index: 11;
            display: none;
            transition: all 0.3s;
        }
        
        .close-video-btn:hover {
            background-color: rgba(255, 55, 83, 0.9);
            transform: rotate(90deg);
        }
        
        .close-video-btn.active {
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        /* Responsive */
        @media (max-width: 1200px) {
            .main-carousel {
                height: 500px;
            }
            
            .thumbnail-item {
                width: 280px;
                height: 160px;
            }
            
            @keyframes scrollRightToLeft {
                100% {
                    transform: translateX(calc(-300px * 7));
                }
            }
        }
        
        @media (max-width: 992px) {
            .main-carousel {
                height: 450px;
            }
            
            .video-title {
                font-size: 28px;
            }
            
            .thumbnail-item {
                width: 240px;
                height: 140px;
            }
            
            .main-carousel-btn {
                width: 45px;
                height: 45px;
                font-size: 16px;
            }
            
            @keyframes scrollRightToLeft {
                100% {
                    transform: translateX(calc(-260px * 7));
                }
            }
        }
        
        @media (max-width: 768px) {
            .main-carousel {
                height: 400px;
            }
            
            .carousel-slide {
                padding: 20px;
            }
            
            .slide-content {
                margin: 0 20px;
                padding: 20px;
            }
            
            .video-title {
                font-size: 24px;
            }
            
            .thumbnail-item {
                width: 200px;
                height: 120px;
            }
            
            .main-carousel-controls {
                bottom: 20px;
            }
            
            .slide-indicators {
                bottom: 80px;
            }
            
            @keyframes scrollRightToLeft {
                100% {
                    transform: translateX(calc(-220px * 7));
                }
            }
        }
        
        @media (max-width: 576px) {
            .main-carousel {
                height: 350px;
            }
            
            .slide-content {
                padding: 15px;
            }
            
            .video-title {
                font-size: 20px;
            }
            
            .video-meta {
                flex-direction: column;
                gap: 5px;
            }
            
            .thumbnail-item {
                width: 180px;
                height: 110px;
            }
            
            .thumbnail-play-btn {
                width: 40px;
                height: 40px;
                font-size: 16px;
            }
            
            .main-carousel-btn {
                width: 40px;
                height: 40px;
                font-size: 14px;
            }
            
            .carousel-btn {
                width: 35px;
                height: 35px;
            }
            
            @keyframes scrollRightToLeft {
                100% {
                    transform: translateX(calc(-200px * 7));
                }
            }
        }
        
        @media (max-width: 400px) {
            .main-carousel {
                height: 300px;
            }
            
            .thumbnail-item {
                width: 160px;
                height: 100px;
            }
            
            .main-carousel-controls {
                bottom: 15px;
                gap: 10px;
            }
            
            .slide-indicators {
                bottom: 70px;
            }
            
            @keyframes scrollRightToLeft {
                100% {
                    transform: translateX(calc(-180px * 7));
                }
            }
        }
    </style>
    <section class="carousel-section">
        <div class="section-header d-none">
            <!-- <h2 class="section-title">Vidéos YouTube en vedette</h2> -->
            <div class="carousel-controls">
                <button class="carousel-btn prev-btn">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button class="carousel-btn next-btn">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>
        
        <!-- Carrousel principal en pleine largeur -->
        <div class="main-carousel">
            <button class="close-video-btn">
                <i class="fas fa-times"></i>
            </button>
            
            <div class="video-iframe-container">
                <iframe class="video-iframe" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            </div>
            @foreach($medias as $image)
            <!-- Slide 1 - Voyage -->
            <div class="carousel-slide active" style="background-image: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.7)), url('{{asset('storage')}}/{{$image->image_path}}">
                <div class="slide-content d-none">
                    <span class="video-category">Voyage</span>
                    <h3 class="video-title">Découvrez les plus beaux paysages islandais</h3>
                    <div class="video-meta">
                        <span><i class="far fa-eye"></i> 2.5M vues</span>
                        <span><i class="far fa-clock"></i> Il y a 3 jours</span>
                        <span><i class="far fa-thumbs-up"></i> 42K</span>
                    </div>
                    <button class="play-btn" data-video-id="ex3C1-5Dhb8">
                        <i class="fas fa-play"></i> Regarder maintenant
                    </button>
                </div>
            </div>
            @endforeach
            
            
            <!-- NOUVEAU: Contrôles de navigation en bas du carrousel -->
            <div class="main-carousel-controls">
                <button class="main-carousel-btn main-prev-btn">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button class="main-carousel-btn main-next-btn">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
            
            <!-- NOUVEAU: Indicateurs de slide -->
            <div class="slide-indicators">
                <div class="slide-indicator active" data-slide="0"></div>
                <div class="slide-indicator" data-slide="1"></div>
                <div class="slide-indicator" data-slide="2"></div>
            </div>
        </div>
        
        <!-- Carrousel miniature en pleine largeur -->
        <div class="thumbnail-carousel-container">
            <div class="thumbnail-carousel">
                <div class="thumbnail-track auto-scroll">
                    <!-- Les miniatures seront générées dynamiquement -->
                </div>
            </div>
        </div>
    </section>

    <script>
        // Données des vidéos
        const videoData = [
           <?php foreach(\App\Models\CountryMedia::where('type', '!=','image')->get() as $item){ ?>
            {
                id: 0,
                videoId: "<?php echo $item->video_id; ?>",
                title: "<?php echo $item->title; ?>",
                category: "Voyage",
                views: "2.5M vues",
                time: "Il y a 3 jours",
                likes: "42K",
                image: "{{asset('storage')}}/{{$item->image_path}}",
                mainImage: "{{asset('storage')}}/{{$item->image_path}}",
                description: "{{$item->description}}"
            },
            <?php } ?>
        ];

        // Variables pour le carrousel
        let currentSlide = 0;
        let currentThumbnailIndex = 0;
        const slides = document.querySelectorAll('.carousel-slide');
        const totalSlides = slides.length;
        const videoIframe = document.querySelector('.video-iframe');
        const videoIframeContainer = document.querySelector('.video-iframe-container');
        const closeVideoBtn = document.querySelector('.close-video-btn');
        const thumbnailTrack = document.querySelector('.thumbnail-track');
        const thumbnailCarousel = document.querySelector('.thumbnail-carousel');
        const thumbnailContainer = document.querySelector('.thumbnail-carousel-container');
        
        // NOUVEAU: Boutons de contrôle
        const topPrevBtn = document.querySelector('.prev-btn');
        const topNextBtn = document.querySelector('.next-btn');
        const mainPrevBtn = document.querySelector('.main-prev-btn');
        const mainNextBtn = document.querySelector('.main-next-btn');
        const slideIndicators = document.querySelectorAll('.slide-indicator');
        
        // Variables pour le drag and scroll
        let isDragging = false;
        let startX = 0;
        let scrollLeft = 0;
        let autoScrollInterval;
        
        // Initialisation
        function init() {
            // Générer les miniatures (doublées pour une animation fluide)
            generateThumbnails();
            
            // Mettre à jour les événements
            attachThumbnailEvents();
            
            // Initialiser le drag and scroll
            initDragScroll();
            
            // Attacher les événements aux boutons
            attachButtonEvents();
            
            // Démarrer le défilement automatique
            startAutoScroll();
            
            // Mettre à jour les indicateurs de slide
            updateSlideIndicators();
        }
        
        // Générer les miniatures
        function generateThumbnails() {
            thumbnailTrack.innerHTML = '';
            
            // Dupliquer les données pour une animation continue
            const duplicatedData = [...videoData, ...videoData];
            
            duplicatedData.forEach((video, index) => {
                const thumbnail = document.createElement('div');
                thumbnail.className = `thumbnail-item ${index === 0 ? 'active' : ''}`;
                thumbnail.setAttribute('data-slide', video.id);
                thumbnail.setAttribute('data-video-id', video.videoId);
                thumbnail.setAttribute('data-index', index % videoData.length);
                
                thumbnail.innerHTML = `
                    <img src="${video.image}" alt="${video.title}">
                    <div class="thumbnail-overlay">${video.title}</div>
                    <div class="thumbnail-hover-overlay">
                        <button class="thumbnail-play-btn">
                            <i class="fas fa-play"></i>
                        </button>
                    </div>
                    <div class="preview-indicator">Prévisualisation</div>
                `;
                
                thumbnailTrack.appendChild(thumbnail);
            });
        }
        
        // Attacher les événements aux boutons de contrôle
        function attachButtonEvents() {
            // Boutons en haut à droite
            topPrevBtn.addEventListener('click', () => {
                prevSlide();
                resetAutoScroll();
            });
            
            topNextBtn.addEventListener('click', () => {
                nextSlide();
                resetAutoScroll();
            });
            
            // Boutons en bas au centre
            mainPrevBtn.addEventListener('click', () => {
                prevSlide();
                resetAutoScroll();
            });
            
            mainNextBtn.addEventListener('click', () => {
                nextSlide();
                resetAutoScroll();
            });
            
            // Indicateurs de slide
            slideIndicators.forEach(indicator => {
                indicator.addEventListener('click', () => {
                    const slideIndex = parseInt(indicator.getAttribute('data-slide'));
                    showSlide(slideIndex);
                    resetAutoScroll();
                });
            });
        }
        
        // Initialiser le drag and scroll
        function initDragScroll() {
            // Démarrer le drag
            thumbnailCarousel.addEventListener('mousedown', startDrag);
            thumbnailCarousel.addEventListener('touchstart', startDragTouch);
            
            // Mouvement pendant le drag
            document.addEventListener('mousemove', drag);
            document.addEventListener('touchmove', dragTouch);
            
            // Fin du drag
            document.addEventListener('mouseup', endDrag);
            document.addEventListener('touchend', endDrag);
            
            // Événements pour le survol
            thumbnailContainer.addEventListener('mouseenter', () => {
                thumbnailTrack.classList.add('paused');
            });
            
            thumbnailContainer.addEventListener('mouseleave', () => {
                if (!videoIframeContainer.classList.contains('active') && !isDragging) {
                    thumbnailTrack.classList.remove('paused');
                }
            });
        }
        
        // Fonctions pour le drag and scroll
        function startDrag(e) {
            isDragging = true;
            thumbnailCarousel.classList.add('grabbing');
            thumbnailTrack.classList.add('paused');
            
            // Enregistrer la position initiale
            startX = e.pageX - thumbnailTrack.offsetLeft;
            scrollLeft = getCurrentTransformX();
            
            // Empêcher la sélection de texte
            e.preventDefault();
        }
        
        function startDragTouch(e) {
            if (e.touches.length === 1) {
                isDragging = true;
                thumbnailCarousel.classList.add('grabbing');
                thumbnailTrack.classList.add('paused');
                
                // Enregistrer la position initiale
                startX = e.touches[0].pageX - thumbnailTrack.offsetLeft;
                scrollLeft = getCurrentTransformX();
            }
        }
        
        function drag(e) {
            if (!isDragging) return;
            
            e.preventDefault();
            const x = e.pageX - thumbnailTrack.offsetLeft;
            const walk = (x - startX) * 1.5;
            setTransformX(scrollLeft - walk);
        }
        
        function dragTouch(e) {
            if (!isDragging || e.touches.length !== 1) return;
            
            const x = e.touches[0].pageX - thumbnailTrack.offsetLeft;
            const walk = (x - startX) * 1.5;
            setTransformX(scrollLeft - walk);
        }
        
        function endDrag() {
            isDragging = false;
            thumbnailCarousel.classList.remove('grabbing');
            
            // Si une vidéo n'est pas en cours de lecture, reprendre le défilement automatique
            if (!videoIframeContainer.classList.contains('active')) {
                setTimeout(() => {
                    thumbnailTrack.classList.remove('paused');
                }, 1000);
            }
        }
        
        // Obtenir la valeur de transform X actuelle
        function getCurrentTransformX() {
            const transform = thumbnailTrack.style.transform;
            if (!transform || transform === 'none') return 0;
            
            const match = transform.match(/translateX\((-?\d+)px\)/);
            return match ? parseInt(match[1]) : 0;
        }
        
        // Définir la valeur de transform X
        function setTransformX(value) {
            // Limiter le défilement
            const maxScroll = -thumbnailTrack.scrollWidth / 2;
            const limitedValue = Math.min(0, Math.max(value, maxScroll));
            
            thumbnailTrack.style.transform = `translateX(${limitedValue}px)`;
        }
        
        // Démarrer le défilement automatique du carrousel principal
        function startAutoScroll() {
            clearInterval(autoScrollInterval);
            autoScrollInterval = setInterval(() => {
                if (!videoIframeContainer.classList.contains('active') && !isDragging) {
                    nextSlide();
                }
            }, 5000);
        }
        
        // Réinitialiser le défilement automatique
        function resetAutoScroll() {
            clearInterval(autoScrollInterval);
            startAutoScroll();
        }
        
        // Attacher les événements aux miniatures
        function attachThumbnailEvents() {
            const thumbnails = document.querySelectorAll('.thumbnail-item');
            
            thumbnails.forEach(thumb => {
                // Clic sur la miniature
                thumb.addEventListener('click', (e) => {
                    // Ne pas déclencher si on clique sur le bouton de lecture ou si on drag
                    if (e.target.closest('.thumbnail-play-btn') || e.target.closest('.thumbnail-hover-overlay') || isDragging) return;
                    
                    const slideIndex = parseInt(thumb.getAttribute('data-slide'));
                    const thumbIndex = parseInt(thumb.getAttribute('data-index'));
                    
                    // Activer le slide correspondant
                    showSlide(slideIndex);
                    
                    // Activer la miniature
                    setActiveThumbnail(thumbIndex);
                    
                    // Réinitialiser le défilement automatique
                    resetAutoScroll();
                });
                
                // Clic sur le bouton de lecture dans la miniature
                const playBtn = thumb.querySelector('.thumbnail-play-btn');
                if (playBtn) {
                    playBtn.addEventListener('click', () => {
                        const slideIndex = parseInt(thumb.getAttribute('data-slide'));
                        const videoId = thumb.getAttribute('data-video-id');
                        const thumbIndex = parseInt(thumb.getAttribute('data-index'));
                        
                        // Activer le slide correspondant
                        showSlide(slideIndex);
                        
                        // Activer la miniature
                        setActiveThumbnail(thumbIndex);
                        
                        // Lire la vidéo
                        playYouTubeVideo(videoId);
                        
                        // Réinitialiser le défilement automatique
                        resetAutoScroll();
                    });
                }
            });
        }
        
        // Fonction pour afficher un slide spécifique
        function showSlide(slideIndex) {
            // Masquer tous les slides
            slides.forEach(slide => {
                slide.classList.remove('active');
            });
            
            // Afficher le slide demandé
            slides[slideIndex].classList.add('active');
            
            // Mettre à jour l'image de fond du slide
            const video = videoData.find(v => v.id === slideIndex);
            if (video) {
                slides[slideIndex].style.backgroundImage = `linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.7)), url('${video.mainImage}')`;
                
                // Mettre à jour le contenu du slide
                const slideContent = slides[slideIndex].querySelector('.slide-content');
                slideContent.querySelector('.video-category').textContent = video.category;
                slideContent.querySelector('.video-title').textContent = video.description || getFullTitle(video.category);
                slideContent.querySelector('.video-meta span:nth-child(1)').innerHTML = `<i class="far fa-eye"></i> ${video.views}`;
                slideContent.querySelector('.video-meta span:nth-child(2)').innerHTML = `<i class="far fa-clock"></i> ${video.time}`;
                slideContent.querySelector('.video-meta span:nth-child(3)').innerHTML = `<i class="far fa-thumbs-up"></i> ${video.likes}`;
                slideContent.querySelector('.play-btn').setAttribute('data-video-id', video.videoId);
            }
            
            currentSlide = slideIndex;
            
            // Mettre à jour les indicateurs de slide
            updateSlideIndicators();
            
            // Mettre à jour les miniatures actives
            updateActiveThumbnails();
            
            // Arrêter la vidéo en cours si elle est en lecture
            stopVideo();
        }
        
        // Mettre à jour les indicateurs de slide
        function updateSlideIndicators() {
            slideIndicators.forEach(indicator => {
                indicator.classList.remove('active');
                if (parseInt(indicator.getAttribute('data-slide')) === currentSlide) {
                    indicator.classList.add('active');
                }
            });
        }
        
        // Mettre à jour les miniatures actives
        function updateActiveThumbnails() {
            const thumbnails = document.querySelectorAll('.thumbnail-item');
            
            // Retirer la classe active de toutes les miniatures
            thumbnails.forEach(thumb => {
                thumb.classList.remove('active');
            });
            
            // Ajouter la classe active aux miniatures correspondant au slide actuel
            thumbnails.forEach(thumb => {
                if (parseInt(thumb.getAttribute('data-slide')) === currentSlide) {
                    thumb.classList.add('active');
                }
            });
        }
        
        // Fonction pour obtenir le titre complet (fallback)
        function getFullTitle(category) {
            switch(category) {
                case 'Voyage': return 'Découvrez les plus beaux paysages islandais';
                case 'Technologie': return 'Les nouvelles tendances en intelligence artificielle';
                case 'Cuisine': return 'Recette facile : Gâteau au chocolat en 15 minutes';
                default: return 'Titre de la vidéo';
            }
        }
        
        // Fonction pour définir la miniature active
        function setActiveThumbnail(index) {
            const thumbnails = document.querySelectorAll('.thumbnail-item');
            
            // Retirer la classe active de toutes les miniatures
            thumbnails.forEach(thumb => {
                thumb.classList.remove('active');
            });
            
            // Ajouter la classe active aux miniatures correspondant à l'index
            thumbnails.forEach((thumb, i) => {
                if (parseInt(thumb.getAttribute('data-index')) === index) {
                    thumb.classList.add('active');
                }
            });
            
            currentThumbnailIndex = index;
        }
        
        // Fonction pour passer au slide suivant
        function nextSlide() {
            currentSlide = (currentSlide + 1) % totalSlides;
            showSlide(currentSlide);
        }
        
        // Fonction pour passer au slide précédent
        function prevSlide() {
            currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
            showSlide(currentSlide);
        }
        
        // Fonction pour lire une vidéo YouTube
        function playYouTubeVideo(videoId) {
            // Mettre à jour la source de l'iframe
            videoIframe.src = `https://www.youtube.com/embed/${videoId}?autoplay=1&rel=0&modestbranding=1`;
            
            // Afficher l'iframe et le bouton de fermeture
            videoIframeContainer.classList.add('active');
            closeVideoBtn.classList.add('active');
            
            // Masquer le contenu du slide actif
            slides[currentSlide].style.opacity = '0';
            
            // Arrêter le défilement automatique des miniatures
            thumbnailTrack.classList.add('paused');
            
            // Arrêter le défilement automatique du carrousel principal
            clearInterval(autoScrollInterval);
        }
        
        // Fonction pour arrêter la vidéo
        function stopVideo() {
            // Réinitialiser la source de l'iframe
            videoIframe.src = '';
            
            // Masquer l'iframe et le bouton de fermeture
            videoIframeContainer.classList.remove('active');
            closeVideoBtn.classList.remove('active');
            
            // Réafficher le contenu du slide actif
            slides[currentSlide].style.opacity = '1';
            
            // Redémarrer le défilement automatique des miniatures si on ne drag pas
            if (!isDragging) {
                setTimeout(() => {
                    thumbnailTrack.classList.remove('paused');
                }, 1000);
            }
            
            // Redémarrer le défilement automatique du carrousel principal
            startAutoScroll();
        }
        
        // Événements pour les boutons de lecture dans les slides
        const playButtons = document.querySelectorAll('.play-btn');
        playButtons.forEach(button => {
            button.addEventListener('click', () => {
                const videoId = button.getAttribute('data-video-id');
                playYouTubeVideo(videoId);
            });
        });
        
        // Événement pour fermer la vidéo
        closeVideoBtn.addEventListener('click', stopVideo);
        
        // Navigation au clavier
        document.addEventListener('keydown', (e) => {
            if (e.key === 'ArrowLeft') {
                prevSlide();
                resetAutoScroll();
            } else if (e.key === 'ArrowRight') {
                nextSlide();
                resetAutoScroll();
            } else if (e.key === 'Escape') {
                stopVideo();
            }
        });
        
        // Initialiser l'application
        init();
    </script>