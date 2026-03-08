<!-- resources/views/components/front/slideshows.blade.php -->
<style>
    /* Styles personnalisés pour le carousel GalleryCarousel */
    .galleryCarousel-section {
        margin: 30px 0;
        padding: 20px 0;
    }
    
    .galleryCarousel-container {
        position: relative;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        background: white;
        margin-bottom: 20px;
        border: 1px solid #e1e8f0;
        height: 450px;
        border-radius: 12px;
    }
    
    .galleryCarousel-track {
        display: flex;
        transition: transform 1.5s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        height: 100%;
    }
    
    .galleryCarousel-slide {
        display: flex;
        flex: 0 0 100%;
        height: 100%;
    }
    
    .galleryCarousel-column {
        overflow: hidden;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .galleryCarousel-column:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
    }
    
    .galleryCarousel-half {
        width: 50%;
    }
    
    /* Styles pour les carreaux */
    .galleryCarousel-mainTile {
        position: relative;
        height: 100%;
    }
    
    .galleryCarousel-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        grid-template-rows: 1fr 1fr;
        height: 100%;
        gap: 1px;
        background-color: #f0f0f0;
    }
    
    .galleryCarousel-tile {
        position: relative;
        overflow: hidden;
        background-color: white;
    }
    
    /* Styles pour les images */
    .galleryCarousel-tile img, .galleryCarousel-mainTile img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        transition: transform 0.5s ease;
    }
    
    .galleryCarousel-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);
        padding: 15px 20px 15px;
        color: white;
        transform: translateY(0);
        transition: transform 0.4s ease;
    }
    
    .galleryCarousel-badge {
        position: absolute;
        top: 12px;
        left: 12px;
        color: white;
        padding: 5px 12px;
        border-radius: 4px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        z-index: 5;
        box-shadow: 0 3px 6px rgba(0, 0, 0, 0.3);
    }
    
    .galleryCarousel-badge-new {
        background: linear-gradient(135deg, #ff4757, #ff3838);
    }
    
    .galleryCarousel-badge-hot {
        background: linear-gradient(135deg, #ff9f1a, #ff7f00);
    }
    
    .galleryCarousel-badge-trending {
        background: linear-gradient(135deg, #2ed573, #1dd1a1);
    }
    
    .galleryCarousel-badge-popular {
        background: linear-gradient(135deg, #1e90ff, #3742fa);
    }
    
    .galleryCarousel-title {
        font-size: 1.1rem;
        font-weight: 700;
        margin-bottom: 4px;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.8);
    }
    
    .galleryCarousel-description {
        font-size: 0.8rem;
        opacity: 0.9;
        line-height: 1.3;
    }
    
    .galleryCarousel-playBtn {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%) scale(0.8);
        width: 65px;
        height: 65px;
        background: rgba(0, 0, 0, 0.85);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 24px;
        cursor: pointer;
        z-index: 10;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        border: 3px solid rgba(255, 255, 255, 0.3);
        opacity: 0;
        visibility: hidden;
    }
    
    .galleryCarousel-item:hover .galleryCarousel-playBtn {
        opacity: 1;
        visibility: visible;
        transform: translate(-50%, -50%) scale(1);
    }
    
    .galleryCarousel-playBtn:hover {
        background: rgba(200, 0, 0, 0.95);
        transform: translate(-50%, -50%) scale(1.1);
        border-color: rgba(255, 255, 255, 0.6);
    }
    
    .galleryCarousel-nav {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 12px;
        margin-top: 25px;
    }
    
    .galleryCarousel-dot {
        width: 14px;
        height: 14px;
        border-radius: 50%;
        background: rgba(74, 111, 165, 0.3);
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .galleryCarousel-dots {
        display: flex;
        gap: 10px;
    }
    
    .galleryCarousel-dot-active {
        background: #4a6fa5;
        transform: scale(1.3);
        box-shadow: 0 0 10px rgba(74, 111, 165, 0.5);
    }
    
    .galleryCarousel-navBtn {
        background: white;
        border: 2px solid #e1e8f0;
        color: #4a6fa5;
        width: 45px;
        height: 45px;
        border-radius: 50%;
        font-size: 18px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 3px 8px rgba(0, 0, 0, 0.05);
    }
    
    .galleryCarousel-navBtn:hover {
        background: #4a6fa5;
        color: white;
        border-color: #4a6fa5;
        transform: scale(1.1);
        box-shadow: 0 5px 12px rgba(74, 111, 165, 0.3);
    }
    
    /* Modal (Popup) Styles */
    .galleryCarousel-modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.9);
        z-index: 1000;
        justify-content: center;
        align-items: center;
        opacity: 0;
        transition: opacity 0.4s ease;
    }
    
    .galleryCarousel-modal-active {
        display: flex;
        opacity: 1;
    }
    
    .galleryCarousel-modalContent {
        background: white;
        border-radius: 12px;
        width: 90%;
        max-width: 900px;
        overflow: hidden;
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.5);
        transform: scale(0.9);
        transition: transform 0.4s ease;
    }
    
    .galleryCarousel-modal-active .galleryCarousel-modalContent {
        transform: scale(1);
    }
    
    .galleryCarousel-modalHeader {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 18px 24px;
        background: #f8fafc;
        border-bottom: 1px solid #e1e8f0;
    }
    
    .galleryCarousel-modalTitle {
        font-size: 1.3rem;
        font-weight: 700;
        color: #2d3748;
    }
    
    .galleryCarousel-closeModal {
        background: none;
        border: none;
        color: #718096;
        font-size: 28px;
        cursor: pointer;
        transition: color 0.3s ease;
        line-height: 1;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
    }
    
    .galleryCarousel-closeModal:hover {
        background-color: #f1f5f9;
        color: #e53e3e;
    }
    
    .galleryCarousel-videoContainer {
        position: relative;
        padding-bottom: 56.25%;
        height: 0;
        overflow: hidden;
        background: #000;
    }
    
    .galleryCarousel-videoContainer iframe {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        border: none;
    }
    
    .galleryCarousel-item:hover img {
        transform: scale(1.08);
    }
    
    .galleryCarousel-item:hover .galleryCarousel-overlay {
        transform: translateY(0);
    }
    
    /* Responsive adjustments */
    @media (max-width: 1024px) {
        .galleryCarousel-container {
            height: 380px;
        }
        
        .galleryCarousel-slide {
            flex-direction: column;
        }
        
        .galleryCarousel-half {
            width: 100%;
            height: 50%;
        }
        
        .galleryCarousel-grid {
            grid-template-columns: 1fr 1fr;
            grid-template-rows: 1fr 1fr;
        }
    }
    
    @media (max-width: 768px) {
        .galleryCarousel-container {
            height: 350px;
        }
        
        .galleryCarousel-slide {
            flex-direction: column;
        }
        
        .galleryCarousel-half {
            width: 100%;
            height: 50%;
        }
        
        .galleryCarousel-grid {
            grid-template-columns: 1fr 1fr;
            grid-template-rows: 1fr 1fr;
        }
        
        .galleryCarousel-title {
            font-size: 1rem;
        }
        
        .galleryCarousel-playBtn {
            width: 55px;
            height: 55px;
            font-size: 20px;
        }
    }
    
    @media (max-width: 480px) {
        .galleryCarousel-container {
            height: 300px;
        }
        
        .galleryCarousel-grid {
            grid-template-columns: 1fr 1fr;
            grid-template-rows: 1fr 1fr;
        }
        
        .galleryCarousel-title {
            font-size: 0.9rem;
        }
        
        .galleryCarousel-badge {
            font-size: 0.65rem;
            padding: 4px 10px;
            top: 8px;
            left: 8px;
        }
        
        .galleryCarousel-description {
            font-size: 0.7rem;
        }
        
        .galleryCarousel-playBtn {
            width: 45px;
            height: 45px;
            font-size: 18px;
        }
        
        .galleryCarousel-navBtn {
            width: 40px;
            height: 40px;
            font-size: 16px;
        }
    }
</style>

<section class="galleryCarousel-section">
    <div class="container">
        <!-- <h2 class="section-title text-center mb-4">Découvrez nos destinations</h2>
        <p class="text-center text-muted mb-5">Explorez les plus beaux endroits du Québec à travers notre sélection exclusive</p>
         -->
        <div class="galleryCarousel-container">
            <div class="galleryCarousel-track" id="galleryCarouselTrack">
                <!-- Les slides seront générés dynamiquement par JavaScript -->
            </div>
        </div>
        
        <div class="galleryCarousel-nav">
            <button class="galleryCarousel-navBtn" id="galleryCarouselPrevBtn">
                <i class="fas fa-chevron-left"></i>
            </button>
            <div class="galleryCarousel-dots" id="galleryCarouselDots">
                <!-- Les points de navigation seront générés dynamiquement -->
            </div>
            <button class="galleryCarousel-navBtn" id="galleryCarouselNextBtn">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>
    </div>
</section>

<!-- Modal (Popup) pour la vidéo YouTube -->
<div class="galleryCarousel-modal" id="galleryCarouselModal">
    <div class="galleryCarousel-modalContent">
        <div class="galleryCarousel-modalHeader">
            <h3 class="galleryCarousel-modalTitle" id="galleryCarouselModalTitle">Titre de la vidéo</h3>
            <button class="galleryCarousel-closeModal" id="galleryCarouselCloseModal">&times;</button>
        </div>
        <div class="galleryCarousel-videoContainer">
            <iframe id="galleryCarouselYouTubeFrame" src="" title="YouTube video player" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
        </div>
    </div>
</div>

<script>
    // Données des slides avec images adaptées aux carrés
    const galleryCarouselSlides = [
        {
            largeImage: {
                title: "Aventure en Montagne",
                description: "Découvrez les paysages époustouflants des Alpes",
                image: "https://images.unsplash.com/photo-1506905925346-21bda4d32df4?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80",
                videoId: "M-2eAiU09qg",
                badge: "new"
            },
            smallImages: [
                {
                    title: "Forêt Enchantée",
                    description: "Une balade magique à travers la forêt",
                    image: "https://images.unsplash.com/photo-1441974231531-c6227db76b6e?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80",
                    videoId: "M-2eAiU09qg",
                    badge: "hot"
                },
                {
                    title: "Coucher de Soleil",
                    description: "Les plus beaux couchers de soleil de l'année",
                    image: "https://images.unsplash.com/photo-1506905925346-21bda4d32df4?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80",
                    videoId: "M-2eAiU09qg",
                    badge: "trending"
                },
                {
                    title: "Océan Infini",
                    description: "Plongez dans les profondeurs de l'océan",
                    image: "https://images.unsplash.com/photo-1439066615861-d1af74d74000?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80",
                    videoId: "M-2eAiU09qg",
                    badge: "popular"
                },
                {
                    title: "Ville Lumineuse",
                    description: "La vie nocturne des grandes métropoles",
                    image: "https://images.unsplash.com/photo-1477959858617-67f85cf4f1df?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80",
                    videoId: "M-2eAiU09qg",
                    badge: "new"
                }
            ]
        },
        {
            largeImage: {
                title: "Désert Mystique",
                description: "Traversez les étendues infinies du Sahara",
                image: "https://images.unsplash.com/photo-1505118380757-91f5f5632de0?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80",
                videoId: "M-2eAiU09qg",
                badge: "trending"
            },
            smallImages: [
                {
                    title: "Aurore Boréale",
                    description: "Le spectacle magique des aurores boréales",
                    image: "https://images.unsplash.com/photo-1502134249126-9f3755a50d78?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80",
                    videoId: "M-2eAiU09qg",
                    badge: "hot"
                },
                {
                    title: "Chutes d'Eau",
                    description: "La puissance et la beauté des cascades",
                    image: "https://images.unsplash.com/photo-1512273222628-4daea6e55abb?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80",
                    videoId: "M-2eAiU09qg",
                    badge: "popular"
                },
                {
                    title: "Architecture Moderne",
                    description: "Les bâtiments les plus innovants du monde",
                    image: "https://images.unsplash.com/photo-1487958449943-2429e8be8625?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80",
                    videoId: "M-2eAiU09qg",
                    badge: "new"
                },
                {
                    title: "Vie Sauvage",
                    description: "Rencontrez les animaux dans leur habitat naturel",
                    image: "https://images.unsplash.com/photo-1519066629447-267fffa62d4b?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80",
                    videoId: "M-2eAiU09qg",
                    badge: "trending"
                }
            ]
        },
        {
            largeImage: {
                title: "Aurores Polaires",
                description: "Un spectacle céleste inoubliable en Laponie",
                image: "https://images.unsplash.com/photo-1502134249126-9f3755a50d78?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80",
                videoId: "M-2eAiU09qg",
                badge: "hot"
            },
            smallImages: [
                {
                    title: "Plages Tropicales",
                    description: "Le sable blanc et l'eau turquoise des Caraïbes",
                    image: "https://images.unsplash.com/photo-1507525428034-b723cf961d3e?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80",
                    videoId: "M-2eAiU09qg",
                    badge: "popular"
                },
                {
                    title: "Randonnée Alpine",
                    description: "Les sentiers les plus spectaculaires des Alpes",
                    image: "https://images.unsplash.com/photo-1536152471326-642d5c8b905d?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80",
                    videoId: "M-2eAiU09qg",
                    badge: "new"
                },
                {
                    title: "Art Urbain",
                    description: "Les fresques murales qui colorent la ville",
                    image: "https://images.unsplash.com/photo-1513475382585-d06e58bcb0e0?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80",
                    videoId: "M-2eAiU09qg",
                    badge: "trending"
                },
                {
                    title: "Volcans Actifs",
                    description: "La puissance impressionnante de la nature",
                    image: "https://images.unsplash.com/photo-1547448526-5e9d57fa28f7?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80",
                    videoId: "M-2eAiU09qg",
                    badge: "hot"
                }
            ]
        },
        {
            largeImage: {
                title: "Cuisine du Monde",
                description: "Découvrez les spécialités culinaires internationales",
                image: "https://images.unsplash.com/photo-1565958011703-44f9829ba187?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80",
                videoId: "M-2eAiU09qg",
                badge: "popular"
            },
            smallImages: [
                {
                    title: "Sports Extrêmes",
                    description: "Adrénaline et sensations fortes garanties",
                    image: "https://images.unsplash.com/photo-1530549387789-4c1017266635?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80",
                    videoId: "M-2eAiU09qg",
                    badge: "new"
                },
                {
                    title: "Fleurs Exotiques",
                    description: "La beauté vibrante des fleurs tropicales",
                    image: "https://images.unsplash.com/photo-1463320898484-cdee8141c787?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80",
                    videoId: "M-2eAiU09qg",
                    badge: "trending"
                },
                {
                    title: "Voyage Spatial",
                    description: "Explorez l'univers et au-delà",
                    image: "https://images.unsplash.com/photo-1446776653964-20c1d3a81b06?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80",
                    videoId: "M-2eAiU09qg",
                    badge: "hot"
                },
                {
                    title: "Art Contemporain",
                    description: "Les œuvres les plus innovantes du moment",
                    image: "https://images.unsplash.com/photo-1541961017774-22349e4a1262?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80",
                    videoId: "M-2eAiU09qg",
                    badge: "popular"
                }
            ]
        },
        {
            largeImage: {
                title: "Voyage en Italie",
                description: "Explorez les trésors de l'Italie",
                image: "https://images.unsplash.com/photo-1534447677768-be436bb09401?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80",
                videoId: "M-2eAiU09qg",
                badge: "new"
            },
            smallImages: [
                {
                    title: "Culture Japonaise",
                    description: "Découvrez la richesse de la culture japonaise",
                    image: "https://images.unsplash.com/photo-1493976040374-85c8e12f0c0e?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80",
                    videoId: "M-2eAiU09qg",
                    badge: "trending"
                },
                {
                    title: "Safari Africain",
                    description: "Rencontrez les animaux de la savane",
                    image: "https://images.unsplash.com/photo-1516426122078-c23e76319801?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80",
                    videoId: "M-2eAiU09qg",
                    badge: "hot"
                },
                {
                    title: "Sports Nautiques",
                    description: "Les sports aquatiques les plus excitants",
                    image: "https://images.unsplash.com/photo-1534438327276-14e5300c3a48?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80",
                    videoId: "M-2eAiU09qg",
                    badge: "popular"
                },
                {
                    title: "Festivals Musicaux",
                    description: "Les plus grands festivals de musique",
                    image: "https://images.unsplash.com/photo-1470225620780-dba8ba36b745?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80",
                    videoId: "M-2eAiU09qg",
                    badge: "new"
                }
            ]
        }
    ];

    // Variables du slider
    let galleryCarouselCurrentSlide = 0;
    let galleryCarouselAutoSlideInterval;
    let galleryCarouselIsTransitioning = false;
    const galleryCarouselSlideDuration = 10000;
    const galleryCarouselTransitionDuration = 30000;

    // Initialisation du slider avec duplication des slides pour un effet infini
    function galleryCarouselInit() {
        const sliderTrack = document.getElementById('galleryCarouselTrack');
        const sliderDots = document.getElementById('galleryCarouselDots');
        
        // Dupliquer les slides pour créer un effet infini
        const slidesToShow = galleryCarouselSlides.concat(galleryCarouselSlides);
        
        slidesToShow.forEach((slide, index) => {
            const slideElement = document.createElement('div');
            slideElement.className = 'galleryCarousel-slide';
            
            slideElement.innerHTML = `
                <div class="galleryCarousel-column galleryCarousel-half galleryCarousel-mainTile galleryCarousel-item" data-video-id="${slide.largeImage.videoId}" data-title="${slide.largeImage.title}">
                    ${slide.largeImage.badge ? `<div class="galleryCarousel-badge galleryCarousel-badge-${slide.largeImage.badge}">${slide.largeImage.badge}</div>` : ''}
                    <img src="${slide.largeImage.image}" alt="${slide.largeImage.title}" loading="lazy">
                    <div class="galleryCarousel-overlay">
                        <div class="galleryCarousel-title">${slide.largeImage.title}</div>
                        <div class="galleryCarousel-description">${slide.largeImage.description}</div>
                    </div>
                    <div class="galleryCarousel-playBtn">
                        <i class="fas fa-play"></i>
                    </div>
                </div>
                <div class="galleryCarousel-column galleryCarousel-half galleryCarousel-grid">
                    ${slide.smallImages.map(img => `
                        <div class="galleryCarousel-tile galleryCarousel-item" data-video-id="${img.videoId}" data-title="${img.title}">
                            ${img.badge ? `<div class="galleryCarousel-badge galleryCarousel-badge-${img.badge}">${img.badge}</div>` : ''}
                            <img src="${img.image}" alt="${img.title}" loading="lazy">
                            <div class="galleryCarousel-overlay">
                                <div class="galleryCarousel-title">${img.title}</div>
                                <div class="galleryCarousel-description">${img.description}</div>
                            </div>
                            <div class="galleryCarousel-playBtn">
                                <i class="fas fa-play"></i>
                            </div>
                        </div>
                    `).join('')}
                </div>
            `;
            
            sliderTrack.appendChild(slideElement);
        });
        
        // Créer les points de navigation (seulement pour les slides originaux)
        galleryCarouselSlides.forEach((_, index) => {
            const dot = document.createElement('div');
            dot.className = `galleryCarousel-dot ${index === 0 ? 'galleryCarousel-dot-active' : ''}`;
            dot.dataset.index = index;
            dot.addEventListener('click', () => galleryCarouselGoToSlide(index));
            sliderDots.appendChild(dot);
        });
        
        // Définir la vitesse de transition dans le CSS
        sliderTrack.style.transition = `transform ${galleryCarouselTransitionDuration}ms cubic-bezier(0.25, 0.46, 0.45, 0.94)`;
        
        // Ajouter les écouteurs d'événements pour les boutons play
        document.querySelectorAll('.galleryCarousel-playBtn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.stopPropagation();
                const imageItem = this.closest('.galleryCarousel-item');
                const videoId = imageItem.dataset.videoId;
                const title = imageItem.dataset.title;
                galleryCarouselOpenVideoModal(videoId, title);
            });
        });
        
        // Ajouter les écouteurs d'événements pour les images
        document.querySelectorAll('.galleryCarousel-item').forEach(item => {
            item.addEventListener('click', function() {
                const videoId = this.dataset.videoId;
                const title = this.dataset.title;
                galleryCarouselOpenVideoModal(videoId, title);
            });
        });
        
        // Mettre à jour l'affichage du slider
        galleryCarouselUpdateSlider();
        
        // Démarrer le défilement automatique
        galleryCarouselStartAutoSlide();
    }

    // Aller à un slide spécifique
    function galleryCarouselGoToSlide(slideIndex) {
        if (galleryCarouselIsTransitioning) return;
        galleryCarouselIsTransitioning = true;
        
        galleryCarouselCurrentSlide = slideIndex;
        galleryCarouselUpdateSlider();
        galleryCarouselResetAutoSlide();
        
        // Réinitialiser le drapeau après la transition
        setTimeout(() => {
            galleryCarouselIsTransitioning = false;
        }, galleryCarouselTransitionDuration);
    }

    // Passer au slide suivant avec défilement continu infini
    function galleryCarouselNextSlide() {
        if (galleryCarouselIsTransitioning) return;
        galleryCarouselIsTransitioning = true;
        
        galleryCarouselCurrentSlide++;
        
        // Calculer l'index réel pour les points de navigation
        const realIndex = galleryCarouselCurrentSlide % galleryCarouselSlides.length;
        
        // Mettre à jour les points de navigation
        document.querySelectorAll('.galleryCarousel-dot').forEach((dot, index) => {
            dot.classList.toggle('galleryCarousel-dot-active', index === realIndex);
        });
        
        // Si on approche de la fin du track dupliqué, réinitialiser discrètement
        if (galleryCarouselCurrentSlide >= galleryCarouselSlides.length * 2 - 1) {
            const sliderTrack = document.getElementById('galleryCarouselTrack');
            sliderTrack.style.transition = 'none';
            galleryCarouselCurrentSlide = galleryCarouselSlides.length;
            galleryCarouselUpdateSlider();
            
            void sliderTrack.offsetWidth;
            
            sliderTrack.style.transition = `transform ${galleryCarouselTransitionDuration}ms cubic-bezier(0.25, 0.46, 0.45, 0.94)`;
            
            setTimeout(() => {
                galleryCarouselCurrentSlide++;
                galleryCarouselUpdateSlider();
                
                setTimeout(() => {
                    galleryCarouselIsTransitioning = false;
                }, galleryCarouselTransitionDuration);
            }, 50);
        } else {
            galleryCarouselUpdateSlider();
            
            setTimeout(() => {
                galleryCarouselIsTransitioning = false;
            }, galleryCarouselTransitionDuration);
        }
    }

    // Passer au slide précédent avec défilement continu infini
    function galleryCarouselPrevSlide() {
        if (galleryCarouselIsTransitioning) return;
        galleryCarouselIsTransitioning = true;
        
        galleryCarouselCurrentSlide--;
        
        // Calculer l'index réel pour les points de navigation
        const realIndex = (galleryCarouselCurrentSlide + galleryCarouselSlides.length) % galleryCarouselSlides.length;
        
        // Mettre à jour les points de navigation
        document.querySelectorAll('.galleryCarousel-dot').forEach((dot, index) => {
            dot.classList.toggle('galleryCarousel-dot-active', index === realIndex);
        });
        
        if (galleryCarouselCurrentSlide < 0) {
            const sliderTrack = document.getElementById('galleryCarouselTrack');
            sliderTrack.style.transition = 'none';
            galleryCarouselCurrentSlide = galleryCarouselSlides.length * 2 - 2;
            galleryCarouselUpdateSlider();
            
            void sliderTrack.offsetWidth;
            
            sliderTrack.style.transition = `transform ${galleryCarouselTransitionDuration}ms cubic-bezier(0.25, 0.46, 0.45, 0.94)`;
            
            setTimeout(() => {
                galleryCarouselCurrentSlide--;
                galleryCarouselUpdateSlider();
                
                setTimeout(() => {
                    galleryCarouselIsTransitioning = false;
                }, galleryCarouselTransitionDuration);
            }, 50);
        } else {
            galleryCarouselUpdateSlider();
            
            setTimeout(() => {
                galleryCarouselIsTransitioning = false;
            }, galleryCarouselTransitionDuration);
        }
        
        galleryCarouselResetAutoSlide();
    }

    // Mettre à jour l'affichage du slider
    function galleryCarouselUpdateSlider() {
        const sliderTrack = document.getElementById('galleryCarouselTrack');
        const translateX = -galleryCarouselCurrentSlide * 100;
        
        sliderTrack.style.transform = `translateX(${translateX}%)`;
    }

    // Ouvrir la modal vidéo
    function galleryCarouselOpenVideoModal(videoId, title) {
        const modal = document.getElementById('galleryCarouselModal');
        const iframe = document.getElementById('galleryCarouselYouTubeFrame');
        const modalTitle = document.getElementById('galleryCarouselModalTitle');
        
        modalTitle.textContent = title;
        iframe.src = `https://www.youtube.com/embed/${videoId}?autoplay=1&rel=0`;
        
        modal.classList.add('galleryCarousel-modal-active');
        document.body.style.overflow = 'hidden';
        
        clearInterval(galleryCarouselAutoSlideInterval);
    }

    // Fermer la modal vidéo
    function galleryCarouselCloseVideoModal() {
        const modal = document.getElementById('galleryCarouselModal');
        const iframe = document.getElementById('galleryCarouselYouTubeFrame');
        
        modal.classList.remove('galleryCarousel-modal-active');
        iframe.src = '';
        document.body.style.overflow = 'auto';
        
        galleryCarouselResetAutoSlide();
    }

    // Démarrer le défilement automatique
    function galleryCarouselStartAutoSlide() {
        galleryCarouselAutoSlideInterval = setInterval(galleryCarouselNextSlide, galleryCarouselSlideDuration);
    }

    // Réinitialiser le défilement automatique
    function galleryCarouselResetAutoSlide() {
        clearInterval(galleryCarouselAutoSlideInterval);
        galleryCarouselStartAutoSlide();
    }

    // Initialisation lorsque la page est chargée
    document.addEventListener('DOMContentLoaded', function() {
        galleryCarouselInit();
        
        // Écouteurs d'événements pour les boutons de navigation
        document.getElementById('galleryCarouselPrevBtn').addEventListener('click', () => {
            galleryCarouselPrevSlide();
            galleryCarouselResetAutoSlide();
        });
        
        document.getElementById('galleryCarouselNextBtn').addEventListener('click', () => {
            galleryCarouselNextSlide();
            galleryCarouselResetAutoSlide();
        });
        
        // Écouteur d'événement pour fermer la modal
        document.getElementById('galleryCarouselCloseModal').addEventListener('click', galleryCarouselCloseVideoModal);
        document.getElementById('galleryCarouselModal').addEventListener('click', function(e) {
            if (e.target === this) {
                galleryCarouselCloseVideoModal();
            }
        });
        
        // Arrêter le défilement automatique quand la souris est sur le slider
        document.querySelector('.galleryCarousel-container').addEventListener('mouseenter', () => {
            clearInterval(galleryCarouselAutoSlideInterval);
        });
        
        // Redémarrer le défilement automatique quand la souris quitte le slider
        document.querySelector('.galleryCarousel-container').addEventListener('mouseleave', () => {
            galleryCarouselStartAutoSlide();
        });
        
        // Démarrer le défilement automatique après un court délai
        setTimeout(() => {
            galleryCarouselStartAutoSlide();
        }, 2000);
    });

    // Gérer la touche Échap pour fermer la modal
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            galleryCarouselCloseVideoModal();
        }
    });
</script>