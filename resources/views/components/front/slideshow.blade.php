
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Montserrat:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #e30613;
            --primary-dark: #b3050f;
            --secondary: #0b6e4f;
            --light: #f8f9fa;
            --dark: #212529;
            --gray: #6c757d;
            --gray-light: #e9ecef;
            --accent: #ffd166;
            --success: #4cc9f0;
            --border-radius: 12px;
            --shadow: 0 10px 20px rgba(0, 0, 0, 0.08);
            --shadow-hover: 0 15px 30px rgba(0, 0, 0, 0.12);
            --transition: all 0.3s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #fff;
            padding: 0;
            max-width: 100%;
            margin: 0 auto;
            overflow-x: hidden;
        }

        /* SECTION: Slider Vidéos Full Screen */
        .video-showcase-section {
            position: relative;
            width: 100%;
            height: 100vh;
            min-height: 700px;
            overflow: hidden;
            background: #fff;
            padding: 0 !important;
            background-color: #000;
        }

        .video-slider-container {
            position: relative;
            width: 100%;
            height: 100%;
        }

        .video-slide {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            transition: opacity 1.2s ease-in-out;
            z-index: 1;
        }

        .video-slide.active {
            opacity: 1;
            z-index: 2;
        }

        .video-wrapper-full {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }

        .youtube-video-full {
            width: 100%;
            height: 100%;
            border: none;
            object-fit: cover;
        }

        .video-slide-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
            z-index: 3;
        }

        .video-slide-content {
            max-width: 900px;
            text-align: center;
            color: white;
            padding: 60px 40px;
            background-color: rgba(0, 0, 0, 0.6);
            border-radius: 20px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
        }

        .video-slide-category {
            font-size: 1.1rem;
            color: var(--accent);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 15px;
            display: block;
        }

        .video-slide-title {
            font-size: 3.8rem;
            font-weight: 800;
            margin-bottom: 25px;
            font-family: 'Montserrat', sans-serif;
            text-shadow: 3px 3px 10px rgba(0,0,0,0.5);
            line-height: 1.2;
            color: white;
        }

        .video-slide-description {
            font-size: 1.4rem;
            margin-bottom: 40px;
            opacity: 0.9;
            line-height: 1.6;
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
            color: rgba(255, 255, 255, 0.9);
        }

        .video-slide-meta {
            display: flex;
            justify-content: center;
            gap: 30px;
            margin-top: 30px;
            font-size: 1rem;
        }

        .video-meta-item {
            display: flex;
            align-items: center;
            gap: 8px;
            color: rgba(255, 255, 255, 0.9);
        }

        .video-meta-icon {
            color: var(--primary);
            font-size: 1.2rem;
        }

        /* Contrôles du slider vidéo */
        .video-slider-controls {
            position: absolute;
            bottom: 60px;
            left: 0;
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 10;
            gap: 30px;
        }

        .video-slider-nav {
            display: flex;
            gap: 12px;
        }

        .video-slider-dot {
            width: 14px;
            height: 14px;
            border-radius: 50%;
            background-color: rgba(255, 255, 255, 0.4);
            border: 2px solid transparent;
            cursor: pointer;
            transition: var(--transition);
        }

        .video-slider-dot.active {
            background-color: var(--primary);
            transform: scale(1.3);
            border-color: white;
        }

        .video-slider-arrow {
            background-color: rgba(255, 255, 255, 0.15);
            color: white;
            border: none;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            transition: var(--transition);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .video-slider-arrow:hover {
            background-color: var(--primary);
            transform: scale(1.1);
            border-color: var(--primary);
        }

        .video-slide-counter {
            color: white;
            font-size: 1.2rem;
            font-weight: 600;
            background-color: rgba(0, 0, 0, 0.5);
            padding: 10px 20px;
            border-radius: 30px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        /* Play/Pause Button */
        .video-play-toggle {
            position: absolute;
            top: 40px;
            right: 40px;
            background-color: rgba(255, 255, 255, 0.15);
            color: white;
            border: none;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            transition: var(--transition);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            z-index: 10;
        }

        .video-play-toggle:hover {
            background-color: var(--primary);
            transform: scale(1.1);
        }

        /* SECTION: Carrousel de Vidéos en Continu */
        .video-carousel-section {
            padding: 0px !important;
            position: relative;
            overflow: hidden;
            background: #fff;
        }

        .carousel-title {
            text-align: center;
            font-size: 2.8rem;
            font-weight: 800;
            margin-bottom: 50px;
            color: #333;
            font-family: 'Montserrat', sans-serif;
            position: relative;
            z-index: 2;
        }

        .video-carousel-track {
            display: flex;
            gap: 30px;
            padding: 20px 0;
            position: relative;
            width: max-content;
        }

        .video-card {
            flex: 0 0 auto;
            width: 350px;
            background: #fff;
            border-radius: 20px;
            overflow: hidden;
            transition: var(--transition);
            border: 1px solid #e0e0e0;
            box-shadow: var(--shadow);
            position: relative;
            cursor: pointer;
            height: 220px; /* Hauteur fixe pour les cartes */
        }

        .video-card:hover {
            transform: translateY(-15px) scale(1.03);
            box-shadow: var(--shadow-hover);
            border-color: var(--primary);
            z-index: 10;
        }

        .video-card-thumbnail {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        .video-card iframe {
            display: none;
            width: 100%;
            height: 100%;
            border: none;
        }

        .video-card.active-video iframe {
            display: block;
        }

        .video-card.active-video .video-card-thumbnail {
            display: none;
        }

        .video-card .play-btn {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 70px;
            height: 70px;
            background-color: rgba(0, 0, 0, 0.8);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.8rem;
            transition: var(--transition);
            border: 3px solid white;
            z-index: 2;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }

        .video-card .play-btn:hover {
            background-color: var(--primary);
            transform: translate(-50%, -50%) scale(1.1);
        }

        /* GrapeJS Compatibility */
        /* .grapejs-editable {
            outline: 2px dashed rgba(227, 6, 19, 0.2);
            outline-offset: 2px;
            transition: outline 0.2s ease;
            min-height: 1.5em;
            min-width: 20px;
        }

        .grapejs-editable:hover {
            outline: 2px dashed rgba(227, 6, 19, 0.5);
        } */

        /* Responsive */
        @media (max-width: 1200px) {
            .video-slide-title {
                font-size: 3.2rem;
            }
            
            .video-card {
                width: 320px;
            }
        }

        @media (max-width: 992px) {
            .video-slide-title {
                font-size: 2.8rem;
            }
            
            .video-slide-description {
                font-size: 1.2rem;
            }
            
            .carousel-title {
                font-size: 2.3rem;
            }
            
            .video-card {
                width: 300px;
            }
        }

        @media (max-width: 768px) {
            .video-showcase-section {
                height: 80vh;
                min-height: 500px;
            }
            
            .video-slide-title {
                font-size: 2.2rem;
            }
            
            .video-slide-description {
                font-size: 1.1rem;
            }
            
            .video-slide-content {
                padding: 40px 25px;
            }
            
            .video-slider-controls {
                bottom: 30px;
                gap: 20px;
            }
            
            .video-slider-arrow {
                width: 50px;
                height: 50px;
                font-size: 1.5rem;
            }
            
            .video-play-toggle {
                top: 20px;
                right: 20px;
            }
            
            .carousel-title {
                font-size: 1.9rem;
            }
            
            .video-card {
                width: 280px;
                height: 200px;
            }
            
            .video-card .play-btn {
                width: 60px;
                height: 60px;
                font-size: 1.5rem;
            }
        }

        @media (max-width: 576px) {
            .video-showcase-section {
                height: 70vh;
                min-height: 400px;
            }
            
            .video-slide-title {
                font-size: 1.8rem;
            }
            
            .video-slide-description {
                font-size: 1rem;
            }
            
            .video-slide-content {
                padding: 30px 20px;
            }
            
            .video-slider-controls {
                flex-direction: column;
                gap: 15px;
                bottom: 20px;
            }
            
            .video-card {
                width: 260px;
                height: 180px;
            }
            
            .video-card .play-btn {
                width: 50px;
                height: 50px;
                font-size: 1.3rem;
            }
        }
    </style>
    <!-- SECTION: Slider Vidéos Full Screen -->
    <section class="video-showcase-section">
        <div class="video-slider-container">
            <!-- Slide 1 -->
            <div class="video-slide active" data-slide="1">
                <div class="video-wrapper-full">
                                        <iframe class="youtube-video-full" src="https://www.youtube.com/embed/kwB4snBq4ZE?si=lH7-P-sFfh2L4i3x" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>

                    <iframe class="youtube-video-full" src="https://www.youtube.com/embed/MfAAJgCzOAs?si=LJchPRKqKx9Itwpt&autoplay=1" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                </div>
                <!-- <div class="video-slide-overlay">
                    <div class="video-slide-content">
                        <span class="video-slide-category" data-gjs-type="text" data-gjs-name="Video Category">DÉCOUVERTE NATURE</span>
                        <h1 class="video-slide-title" data-gjs-type="text" data-gjs-name="Video Title">Aurores Boréales du Grand Nord</h1>
                        <p class="video-slide-description" data-gjs-type="text" data-gjs-name="Video Description">Un spectacle céleste époustouflant dans les vastes étendues du Nunavik, où la nature déploie sa magie la plus pure.</p>
                        <div class="video-slide-meta">
                            <div class="video-meta-item">
                                <i class="fas fa-clock video-meta-icon"></i>
                                <span class="grapejs-editable" data-gjs-type="text" data-gjs-name="Video Duration">5:42</span>
                            </div>
                            <div class="video-meta-item">
                                <i class="fas fa-map-marker-alt video-meta-icon"></i>
                                <span class="grapejs-editable" data-gjs-type="text" data-gjs-name="Video Location">Nunavik, Québec</span>
                            </div>
                            <div class="video-meta-item">
                                <i class="fas fa-eye video-meta-icon"></i>
                                <span class="grapejs-editable" data-gjs-type="text" data-gjs-name="Video Views">245K vues</span>
                            </div>
                        </div>
                    </div>
                </div> -->
            </div>
            
            <!-- Slide 2 -->
            <div class="video-slide" data-slide="2">
                <div class="video-wrapper-full">
                    <iframe class="youtube-video-full" src="https://www.youtube.com/embed/MfAAJgCzOAs?si=LJchPRKqKx9Itwpt&autoplay=1" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                </div>
                <!-- <div class="video-slide-overlay">
                    <div class="video-slide-content">
                        <span class="video-slide-category" data-gjs-type="text" data-gjs-name="Video Category">CULTURE & FESTIVALS</span>
                        <h1 class="video-slide-title" data-gjs-type="text" data-gjs-name="Video Title">Festival International de Jazz de Montréal</h1>
                        <p class="video-slide-description" data-gjs-type="text" data-gjs-name="Video Description">Plongez dans l'ambiance électrisante du plus grand festival de jazz au monde, au cœur de Montréal.</p>
                        <div class="video-slide-meta">
                            <div class="video-meta-item">
                                <i class="fas fa-clock video-meta-icon"></i>
                                <span class="grapejs-editable" data-gjs-type="text" data-gjs-name="Video Duration">8:22</span>
                            </div>
                            <div class="video-meta-item">
                                <i class="fas fa-map-marker-alt video-meta-icon"></i>
                                <span class="grapejs-editable" data-gjs-type="text" data-gjs-name="Video Location">Montréal, Québec</span>
                            </div>
                            <div class="video-meta-item">
                                <i class="fas fa-eye video-meta-icon"></i>
                                <span class="grapejs-editable" data-gjs-type="text" data-gjs-name="Video Views">187K vues</span>
                            </div>
                        </div>
                    </div>
                </div> -->
            </div>
            
            <!-- Slide 3 -->
            <div class="video-slide" data-slide="3">
                <div class="video-wrapper-full">
                    <iframe class="youtube-video-full" src="https://www.youtube.com/embed/f3KaqmXFm1M?si=pM1IuGa1ynAS_qIb" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                </div>
                <!-- <div class="video-slide-overlay">
                    <div class="video-slide-content">
                        <span class="video-slide-category" data-gjs-type="text" data-gjs-name="Video Category">GASTRONOMIE</span>
                        <h1 class="video-slide-title" data-gjs-type="text" data-gjs-name="Video Title">Saveurs Authentiques du Terroir</h1>
                        <p class="video-slide-description" data-gjs-type="text" data-gjs-name="Video Description">Un voyage culinaire à travers les spécialités québécoises, du sirop d'érable aux fromages fins.</p>
                        <div class="video-slide-meta">
                            <div class="video-meta-item">
                                <i class="fas fa-clock video-meta-icon"></i>
                                <span class="grapejs-editable" data-gjs-type="text" data-gjs-name="Video Duration">15:30</span>
                            </div>
                            <div class="video-meta-item">
                                <i class="fas fa-map-marker-alt video-meta-icon"></i>
                                <span class="grapejs-editable" data-gjs-type="text" data-gjs-name="Video Location">Cantons-de-l'Est</span>
                            </div>
                            <div class="video-meta-item">
                                <i class="fas fa-eye video-meta-icon"></i>
                                <span class="grapejs-editable" data-gjs-type="text" data-gjs-name="Video Views">312K vues</span>
                            </div>
                        </div>
                    </div>
                </div> -->
            </div>
            
            <!-- Slide 4 -->
            <div class="video-slide" data-slide="4">
                <div class="video-wrapper-full">
                    <iframe class="youtube-video-full" src="https://www.youtube.com/embed/Tmn9inxTvPo?si=k8ZWeEbQa_rYax8K&autoplay=1&mute=1&controls=0&loop=1&playlist=Tmn9inxTvPo" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                </div>
                <!-- <div class="video-slide-overlay">
                    <div class="video-slide-content">
                        <span class="video-slide-category" data-gjs-type="text" data-gjs-name="Video Category">SPORTS D'HIVER</span>
                        <h1 class="video-slide-title" data-gjs-type="text" data-gjs-name="Video Title">Ski dans les Laurentides</h1>
                        <p class="video-slide-description" data-gjs-type="text" data-gjs-name="Video Description">Découvrez les meilleures stations de ski de l'est du Canada dans les magnifiques Laurentides.</p>
                        <div class="video-slide-meta">
                            <div class="video-meta-item">
                                <i class="fas fa-clock video-meta-icon"></i>
                                <span class="grapejs-editable" data-gjs-type="text" data-gjs-name="Video Duration">12:15</span>
                            </div>
                            <div class="video-meta-item">
                                <i class="fas fa-map-marker-alt video-meta-icon"></i>
                                <span class="grapejs-editable" data-gjs-type="text" data-gjs-name="Video Location">Laurentides</span>
                            </div>
                            <div class="video-meta-item">
                                <i class="fas fa-eye video-meta-icon"></i>
                                <span class="grapejs-editable" data-gjs-type="text" data-gjs-name="Video Views">189K vues</span>
                            </div>
                        </div>
                    </div>
                </div> -->
            </div>
            
            <!-- Slide 5 -->
            <div class="video-slide" data-slide="5">
                <div class="video-wrapper-full">
                    <iframe class="youtube-video-full" src="https://www.youtube.com/embed/Tmn9inxTvPo?si=k8ZWeEbQa_rYax8K&autoplay=1&mute=1&controls=0&loop=1&playlist=Tmn9inxTvPo" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                </div>
                <div class="video-slide-overlay">
                    <div class="video-slide-content">
                        <span class="video-slide-category" data-gjs-type="text" data-gjs-name="Video Category">RANDONNÉE</span>
                        <h1 class="video-slide-title" data-gjs-type="text" data-gjs-name="Video Title">Parc National de la Gaspésie</h1>
                        <p class="video-slide-description" data-gjs-type="text" data-gjs-name="Video Description">Randonnée au cœur des montagnes gaspésiennes, un paradis pour les amateurs de nature sauvage.</p>
                        <div class="video-slide-meta">
                            <div class="video-meta-item">
                                <i class="fas fa-clock video-meta-icon"></i>
                                <span class="grapejs-editable" data-gjs-type="text" data-gjs-name="Video Duration">18:45</span>
                            </div>
                            <div class="video-meta-item">
                                <i class="fas fa-map-marker-alt video-meta-icon"></i>
                                <span class="grapejs-editable" data-gjs-type="text" data-gjs-name="Video Location">Gaspésie</span>
                            </div>
                            <div class="video-meta-item">
                                <i class="fas fa-eye video-meta-icon"></i>
                                <span class="grapejs-editable" data-gjs-type="text" data-gjs-name="Video Views">275K vues</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Bouton Play/Pause -->
        <button class="video-play-toggle" id="videoPlayToggle">
            <i class="fas fa-pause"></i>
        </button>
        
        <!-- Contrôles du slider -->
        <div class="video-slider-controls">
            <button class="video-slider-arrow prev-video-slide">
                <i class="fas fa-chevron-left"></i>
            </button>
            
            <div class="video-slider-nav">
                <button class="video-slider-dot active" data-slide="1"></button>
                <button class="video-slider-dot" data-slide="2"></button>
                <button class="video-slider-dot" data-slide="3"></button>
                <button class="video-slider-dot" data-slide="4"></button>
                <button class="video-slider-dot" data-slide="5"></button>
            </div>
            
            <div class="video-slide-counter d-none">
                <span class="current-video-slide">1</span> / <span class="total-video-slides">5</span>
            </div>
            
            <button class="video-slider-arrow next-video-slide">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>
    </section>

    <!-- SECTION: Carrousel de Vidéos en Continu -->
    <section class="video-carousel-section">
        
        <div class="video-carousel-track" id="videoCarousel">
            <!-- Les cartes seront générées dynamiquement par JavaScript -->
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // ========== SLIDER VIDÉOS FULL SCREEN ==========
            const videoSlides = document.querySelectorAll('.video-slide');
            const videoDots = document.querySelectorAll('.video-slider-dot');
            const prevVideoBtn = document.querySelector('.prev-video-slide');
            const nextVideoBtn = document.querySelector('.next-video-slide');
            const playToggleBtn = document.getElementById('videoPlayToggle');
            const currentVideoSlideSpan = document.querySelector('.current-video-slide');
            const totalVideoSlidesSpan = document.querySelector('.total-video-slides');
            
            let currentVideoSlide = 0;
            let videoSlideInterval;
            let isPlaying = true;
            const videoSlideDuration = 7000;
            
            totalVideoSlidesSpan.textContent = videoSlides.length;
            
            function showVideoSlide(index) {
                if (index < 0) index = videoSlides.length - 1;
                if (index >= videoSlides.length) index = 0;
                
                videoSlides.forEach(slide => slide.classList.remove('active'));
                videoDots.forEach(dot => dot.classList.remove('active'));
                
                videoSlides[index].classList.add('active');
                videoDots[index].classList.add('active');
                
                currentVideoSlideSpan.textContent = index + 1;
                currentVideoSlide = index;
            }
            
            function nextVideoSlide() {
                showVideoSlide(currentVideoSlide + 1);
            }
            
            function prevVideoSlide() {
                showVideoSlide(currentVideoSlide - 1);
            }
            
            function startVideoAutoPlay() {
                if (isPlaying) {
                    videoSlideInterval = setInterval(nextVideoSlide, videoSlideDuration);
                    playToggleBtn.innerHTML = '<i class="fas fa-pause"></i>';
                }
            }
            
            function stopVideoAutoPlay() {
                clearInterval(videoSlideInterval);
                playToggleBtn.innerHTML = '<i class="fas fa-play"></i>';
            }
            
            function toggleVideoPlay() {
                isPlaying = !isPlaying;
                if (isPlaying) {
                    startVideoAutoPlay();
                } else {
                    stopVideoAutoPlay();
                }
            }
            
            if (nextVideoBtn) nextVideoBtn.addEventListener('click', () => {
                nextVideoSlide();
                if (isPlaying) {
                    stopVideoAutoPlay();
                    startVideoAutoPlay();
                }
            });
            
            if (prevVideoBtn) prevVideoBtn.addEventListener('click', () => {
                prevVideoSlide();
                if (isPlaying) {
                    stopVideoAutoPlay();
                    startVideoAutoPlay();
                }
            });
            
            videoDots.forEach((dot, index) => {
                dot.addEventListener('click', () => {
                    showVideoSlide(index);
                    if (isPlaying) {
                        stopVideoAutoPlay();
                        startVideoAutoPlay();
                    }
                });
            });
            
            if (playToggleBtn) playToggleBtn.addEventListener('click', toggleVideoPlay);
            
            showVideoSlide(0);
            startVideoAutoPlay();
            
            document.addEventListener('keydown', (e) => {
                if (e.key === 'ArrowLeft') {
                    prevVideoSlide();
                    if (isPlaying) {
                        stopVideoAutoPlay();
                        startVideoAutoPlay();
                    }
                } else if (e.key === 'ArrowRight') {
                    nextVideoSlide();
                    if (isPlaying) {
                        stopVideoAutoPlay();
                        startVideoAutoPlay();
                    }
                } else if (e.key === ' ' || e.key === 'Spacebar') {
                    e.preventDefault();
                    toggleVideoPlay();
                }
            });
            
            // ========== GÉNÉRATION DU CARROUSEL ==========
            const videoCarouselTrack = document.getElementById('videoCarousel');
            
            // Données des vidéos (5 cartes avec la même vidéo)
            const videoData = [
                {
                    id: 'MfAAJgCzOAs',
                    title: 'Québec - Vidéo Exclusive',
                    slideIndex: 0,
                    image: 'https://images.unsplash.com/photo-1502134249126-9f3755a50d78?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80'
                },
                {
                    id: 'kwB4snBq4ZE',
                    title: 'Québec - Vidéo Exclusive',
                    slideIndex: 1,
                    image: 'https://images.unsplash.com/photo-1511671782779-c97d3d27a1d4?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80'
                },
                {
                    id: 'f3KaqmXFm1M',
                    title: 'Québec - Vidéo Exclusive',
                    slideIndex: 2,
                    image: 'https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80'
                },
                {
                    id: 'Tmn9inxTvPo',
                    title: 'Québec - Vidéo Exclusive',
                    slideIndex: 3,
                    image: 'data:image/jpeg;base64,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'
                },
                {
                    id: 'Tmn9inxTvPo',
                    title: 'Québec - Vidéo Exclusive',
                    slideIndex: 4,
                    image: 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80'
                }
            ];
            
            // Créer les cartes (répétées 3 fois pour l'effet de boucle continu)
            function createVideoCards() {
                videoCarouselTrack.innerHTML = '';
                
                // Créer 3 copies des cartes pour l'effet de boucle continu
                for (let copy = 0; copy < 3; copy++) {
                    videoData.forEach((video, index) => {
                        const card = document.createElement('div');
                        card.className = 'video-card';
                        card.setAttribute('data-video-id', video.id);
                        card.setAttribute('data-slide-index', video.slideIndex);
                        
                        card.innerHTML = `
                            <div class="video-card-thumbnail" style="background-image: url('${video.image}'); background-size: cover;">
                                <div class="play-btn">
                                    <i class="fas fa-play"></i>
                                </div>
                            </div>
                            <iframe src="https://www.youtube.com/embed/${video.id}" title="${video.title}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                        `;
                        
                        videoCarouselTrack.appendChild(card);
                    });
                }
                
                // Initialiser les événements après création des cartes
                initCarouselEvents();
                // Démarrer l'animation du carrousel
                startCarouselAnimation();
            }
            
            // Initialiser les événements du carrousel
            function initCarouselEvents() {
                const videoCards = document.querySelectorAll('.video-card');
                
                videoCards.forEach(card => {
                    const playBtn = card.querySelector('.play-btn');
                    const iframe = card.querySelector('iframe');
                    const slideIndex = parseInt(card.getAttribute('data-slide-index'));
                    
                    // Fonction pour activer la vidéo
                    function activateVideo() {
                        // Désactiver toutes les autres vidéos
                        videoCards.forEach(c => {
                            c.classList.remove('active-video');
                            const otherIframe = c.querySelector('iframe');
                            if (otherIframe && otherIframe !== iframe) {
                                // Réinitialiser l'iframe
                                let src = otherIframe.src;
                                src = src.replace('&autoplay=1', '');
                                otherIframe.src = src;
                            }
                        });
                        
                        // Activer cette vidéo
                        card.classList.add('active-video');
                        
                        // Mettre à jour le slider principal si la vidéo correspond
                        if (slideIndex >= 0 && slideIndex < 5) {
                            showVideoSlide(slideIndex);
                            if (isPlaying) {
                                stopVideoAutoPlay();
                                startVideoAutoPlay();
                            }
                        }
                        
                        // Ajouter autoplay à l'iframe
                        if (iframe) {
                            let src = iframe.src;
                            if (!src.includes('autoplay=1')) {
                                src += (src.includes('?') ? '&' : '?') + 'autoplay=1';
                                iframe.src = src;
                            }
                        }
                    }
                    
                    // Événement pour le bouton play
                    playBtn.addEventListener('click', (e) => {
                        e.stopPropagation();
                        activateVideo();
                    });
                    
                    // Événement pour cliquer sur la carte
                    card.addEventListener('click', (e) => {
                        if (!e.target.closest('.play-btn') && e.target !== iframe) {
                            activateVideo();
                        }
                    });
                    
                    // Événements de survol
                    card.addEventListener('mouseenter', function() {
                        this.style.zIndex = '100';
                    });
                    
                    card.addEventListener('mouseleave', function() {
                        if (!this.classList.contains('active-video')) {
                            this.style.zIndex = '';
                        }
                    });
                });
            }
            
            // Animation du carrousel avec mouvement lent
            function startCarouselAnimation() {
                const track = videoCarouselTrack;
                const cards = document.querySelectorAll('.video-card');
                if (cards.length === 0) return;
                
                const cardWidth = cards[0].offsetWidth + 30; // largeur + gap
                const totalCards = cards.length;
                const cardsPerSet = 5; // 5 cartes originales
                const totalWidth = cardWidth * cardsPerSet; // largeur d'une série complète
                
                let position = 0;
                let speed = 0.5; // Vitesse lente
                let animationId;
                
                function animateCarousel() {
                    position -= speed;
                    
                    // Si on a défilé d'une série complète, revenir au début
                    if (Math.abs(position) >= totalWidth) {
                        position = 0;
                    }
                    
                    track.style.transform = `translateX(${position}px)`;
                    animationId = requestAnimationFrame(animateCarousel);
                }
                
                // Démarrer l'animation
                animateCarousel();
                
                // Pause au survol
                track.addEventListener('mouseenter', () => {
                    const currentSpeed = speed;
                    speed = 0;
                    
                    track.addEventListener('mouseleave', () => {
                        speed = currentSpeed;
                    }, { once: true });
                });
                
                // Nettoyer l'animation si nécessaire
                return () => {
                    if (animationId) {
                        cancelAnimationFrame(animationId);
                    }
                };
            }
            
            // Créer les cartes du carrousel
            createVideoCards();
            
            // ========== ÉDITION GRAPEJS ==========
            const editableElements = document.querySelectorAll('.grapejs-editable');
            
            editableElements.forEach(element => {
                element.addEventListener('mouseenter', function() {
                    this.style.outline = '2px dashed rgba(227, 6, 19, 0.5)';
                    this.style.backgroundColor = 'rgba(227, 6, 19, 0.05)';
                });
                
                element.addEventListener('mouseleave', function() {
                    this.style.outline = '2px dashed rgba(227, 6, 19, 0.2)';
                    this.style.backgroundColor = '';
                });
            });
        });
    </script>