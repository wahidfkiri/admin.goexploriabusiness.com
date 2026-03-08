// Gallery Preview JavaScript
class GalleryPreview {
    constructor() {
        this.currentIndex = 0;
        this.totalItems = 8;
        this.isAutoPlay = true;
        this.autoPlayInterval = null;
        this.currentCategory = 'all';
        this.isFullscreen = false;
        
        // Données des images
        this.galleryData = [
            {
                id: 0,
                img: 'https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
                thumb: 'https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80',
                title: 'Pizza Signature Jim',
                description: 'Notre création exclusive avec fromages fins et jambon de qualité',
                category: 'pizza',
                badge: '<i class="fas fa-fire"></i> Populaire',
                badgeClass: 'bg-primary'
            },
            {
                id: 1,
                img: "{{ asset('images/Beef-Burgers-067.jpg') }}",
                thumb: "{{ asset('images/Beef-Burgers-067.jpg') }}",
                title: 'Burger Classique',
                description: 'Steak haché 100% bœuf avec nos garnitures signature',
                category: 'burger',
                badge: '<i class="fas fa-star"></i> Nouveau',
                badgeClass: 'bg-success'
            },
            {
                id: 2,
                img: "{{ asset('images/poutine classique.jpg') }}",
                thumb: "{{ asset('images/poutine classique.jpg') }}",
                title: 'Poutine Classique',
                description: 'Frites dorées, fromage frais et sauce maison',
                category: 'poutine',
                badge: '<i class="fas fa-pepper-hot"></i> Épicé',
                badgeClass: 'bg-warning'
            },
            {
                id: 3,
                img: 'https://images.unsplash.com/photo-1571066811602-716837d681de?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
                thumb: 'https://images.unsplash.com/photo-1571066811602-716837d681de?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80',
                title: 'Pizza Végétarienne',
                description: 'Légumes frais du marché et fromage mozzarella',
                category: 'pizza',
                badge: '<i class="fas fa-leaf"></i> Végétarien',
                badgeClass: 'bg-info'
            },
            {
                id: 4,
                img: 'https://images.unsplash.com/photo-1567620832903-9fc6debc209f?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
                thumb: 'https://images.unsplash.com/photo-1567620832903-9fc6debc209f?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80',
                title: 'Ailes de Poulet',
                description: 'Croustillantes et savoureuses, notre spécialité',
                category: 'burger',
                badge: '<i class="fas fa-crown"></i> Spécialité',
                badgeClass: 'bg-danger'
            },
            {
                id: 5,
                img: 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
                thumb: 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80',
                title: 'Notre Restaurant',
                description: 'Ambiance chaleureuse et accueillante pour toute la famille',
                category: 'restaurant',
                badge: '<i class="fas fa-heart"></i> Ambiance',
                badgeClass: 'bg-secondary'
            },
            {
                id: 6,
                img: 'https://images.unsplash.com/photo-1555396273-367ea4eb4db5?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
                thumb: 'https://images.unsplash.com/photo-1555396273-367ea4eb4db5?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80',
                title: 'Cuisine Ouverte',
                description: 'Voyez nos chefs préparer vos plats avec passion',
                category: 'restaurant',
                badge: '<i class="fas fa-eye"></i> Transparent',
                badgeClass: 'bg-dark'
            },
            {
                id: 7,
                img: 'https://images.unsplash.com/photo-1571091718767-18b5b1457add?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
                thumb: 'https://images.unsplash.com/photo-1571091718767-18b5b1457add?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80',
                title: 'Poutine Garnie',
                description: 'Viande et légumes frais sur notre poutine signature',
                category: 'poutine',
                badge: '<i class="fas fa-award"></i> Récompensé',
                badgeClass: 'bg-success'
            }
        ];
        
        this.init();
    }
    
    init() {
        this.cacheElements();
        this.bindEvents();
        this.initMiniSliders();
        this.startAutoPlay();
        this.updateDisplay();
    }
    
    cacheElements() {
        this.elements = {
            // Main preview elements
            mainPreviewImg: document.getElementById('main-preview-img'),
            mainPreviewTitle: document.getElementById('main-preview-title'),
            mainPreviewDesc: document.getElementById('main-preview-desc'),
            mainPreviewBadge: document.getElementById('main-preview-badge'),
            
            // Controls
            prevBtn: document.getElementById('prev-main'),
            nextBtn: document.getElementById('next-main'),
            zoomBtn: document.getElementById('zoom-main'),
            playPauseBtn: document.getElementById('play-pause'),
            playIcon: document.getElementById('play-icon'),
            
            // Category elements
            categoryFilters: document.querySelectorAll('.category-filter'),
            currentCategory: document.getElementById('current-category'),
            photoCount: document.getElementById('photo-count'),
            
            // Thumbnails
            thumbnailItems: document.querySelectorAll('.thumbnail-item'),
            thumbnailsContainer: document.querySelector('.thumbnails-container'),
            
            // Progress
            progressBar: document.getElementById('progress-bar'),
            currentPosition: document.getElementById('current-position'),
            
            // Auto play
            autoPlayToggle: document.getElementById('auto-play-toggle'),
            autoPlayIcon: document.getElementById('auto-play-icon'),
            fullscreenToggle: document.getElementById('fullscreen-toggle'),
            
            // Mini sliders
            miniSliders: {
                pizza: document.getElementById('pizza-slider'),
                burger: document.getElementById('burger-slider'),
                poutine: document.getElementById('poutine-slider')
            }
        };
        
        this.totalItems = this.galleryData.length;
    }
    
    bindEvents() {
        // Navigation controls
        this.elements.prevBtn.addEventListener('click', () => this.prevImage());
        this.elements.nextBtn.addEventListener('click', () => this.nextImage());
        this.elements.zoomBtn.addEventListener('click', () => this.openLightbox());
        this.elements.playPauseBtn.addEventListener('click', () => this.toggleAutoPlay());
        
        // Category filters
        this.elements.categoryFilters.forEach(filter => {
            filter.addEventListener('click', (e) => this.filterByCategory(e));
        });
        
        // Thumbnail clicks
        this.elements.thumbnailItems.forEach((thumb, index) => {
            thumb.addEventListener('click', () => this.selectImage(index));
        });
        
        // Auto play controls
        this.elements.autoPlayToggle.addEventListener('click', () => this.toggleAutoPlay());
        this.elements.fullscreenToggle.addEventListener('click', () => this.toggleFullscreen());
        
        // Keyboard navigation
        document.addEventListener('keydown', (e) => this.handleKeyboard(e));
        
        // Mouse wheel navigation
        this.elements.thumbnailsContainer.addEventListener('wheel', (e) => {
            e.preventDefault();
            if (e.deltaY > 0) {
                this.nextImage();
            } else {
                this.prevImage();
            }
        });
    }
    
    initMiniSliders() {
        // Initialize mini sliders positions
        this.miniSliderPositions = {
            pizza: 0,
            burger: 0,
            poutine: 0
        };
        
        // Add navigation for mini sliders
        document.querySelectorAll('.mini-nav-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const slider = e.target.closest('.mini-nav-btn').dataset.slider;
                const direction = e.target.closest('.mini-nav-btn').dataset.direction;
                this.navigateMiniSlider(slider, direction);
            });
        });
        
        // Initialize positions
        this.updateMiniSliders();
    }
    
    navigateMiniSlider(slider, direction) {
        const track = this.elements.miniSliders[slider].querySelector('.mini-slider-track');
        const slides = track.querySelectorAll('.mini-slide');
        const slideWidth = slides[0].offsetWidth;
        const maxPosition = (slides.length - 1) * slideWidth;
        
        if (direction === 'next') {
            this.miniSliderPositions[slider] += slideWidth;
            if (this.miniSliderPositions[slider] > maxPosition) {
                this.miniSliderPositions[slider] = 0;
            }
        } else {
            this.miniSliderPositions[slider] -= slideWidth;
            if (this.miniSliderPositions[slider] < 0) {
                this.miniSliderPositions[slider] = maxPosition;
            }
        }
        
        track.style.transform = `translateX(-${this.miniSliderPositions[slider]}px)`;
    }
    
    updateMiniSliders() {
        Object.keys(this.miniSliderPositions).forEach(slider => {
            const track = this.elements.miniSliders[slider]?.querySelector('.mini-slider-track');
            if (track) {
                track.style.transform = `translateX(-${this.miniSliderPositions[slider]}px)`;
            }
        });
    }
    
    updateDisplay() {
        const currentItem = this.galleryData[this.currentIndex];
        
        // Update main preview
        this.elements.mainPreviewImg.src = currentItem.img;
        this.elements.mainPreviewImg.alt = currentItem.title;
        this.elements.mainPreviewTitle.textContent = currentItem.title;
        this.elements.mainPreviewDesc.textContent = currentItem.description;
        this.elements.mainPreviewBadge.innerHTML = currentItem.badge;
        
        // Update thumbnails active state
        this.elements.thumbnailItems.forEach((thumb, index) => {
            thumb.classList.remove('active');
            if (index === this.currentIndex) {
                thumb.classList.add('active');
                // Scroll to active thumbnail
               if (this.isInitialized) {
                thumb.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
            }
            }
        });
        
        // Update progress
        const progress = ((this.currentIndex + 1) / this.totalItems) * 100;
        this.elements.progressBar.style.width = `${progress}%`;
        this.elements.currentPosition.textContent = `Photo ${this.currentIndex + 1} sur ${this.totalItems}`;
        
        // Update category display
        const categoryCount = this.getCategoryCount();
        const categoryName = this.getCategoryName(this.currentCategory);
        this.elements.currentCategory.textContent = categoryName;
        this.elements.photoCount.textContent = `(${categoryCount} photos)`;
    }
    
    getCategoryCount() {
        if (this.currentCategory === 'all') return this.totalItems;
        return this.galleryData.filter(item => item.category === this.currentCategory).length;
    }
    
    getCategoryName(category) {
        const names = {
            'all': 'Toutes les photos',
            'pizza': 'Nos Pizzas',
            'burger': 'Burgers & Entrées',
            'poutine': 'Nos Poutines',
            'restaurant': 'Notre Restaurant'
        };
        return names[category] || category;
    }
    
    selectImage(index) {
        this.currentIndex = index;
        this.updateDisplay();
        
        // Reset auto-play timer
        if (this.isAutoPlay) {
            this.resetAutoPlay();
        }
    }
    
    prevImage() {
        this.currentIndex--;
        if (this.currentIndex < 0) {
            this.currentIndex = this.totalItems - 1;
        }
        this.updateDisplay();
    }
    
    nextImage() {
        this.currentIndex++;
        if (this.currentIndex >= this.totalItems) {
            this.currentIndex = 0;
        }
        this.updateDisplay();
    }
    
    filterByCategory(event) {
        const filter = event.currentTarget;
        const category = filter.dataset.category;
        
        // Update active filter
        this.elements.categoryFilters.forEach(f => f.classList.remove('active'));
        filter.classList.add('active');
        
        this.currentCategory = category;
        
        // Filter thumbnails
        this.elements.thumbnailItems.forEach((thumb, index) => {
            const thumbCategory = thumb.dataset.category;
            const shouldShow = category === 'all' || thumbCategory === category;
            
            if (shouldShow) {
                thumb.style.display = 'block';
                thumb.style.opacity = '1';
            } else {
                thumb.style.display = 'none';
                thumb.style.opacity = '0';
            }
        });
        
        // Find first visible image in filtered category
        if (category !== 'all') {
            const firstVisibleIndex = Array.from(this.elements.thumbnailItems).findIndex(
                thumb => thumb.style.display === 'block'
            );
            if (firstVisibleIndex >= 0) {
                this.selectImage(parseInt(this.elements.thumbnailItems[firstVisibleIndex].dataset.index));
            }
        }
        
        this.updateDisplay();
    }
    
    toggleAutoPlay() {
        this.isAutoPlay = !this.isAutoPlay;
        
        if (this.isAutoPlay) {
            this.startAutoPlay();
            this.elements.playIcon.className = 'fas fa-pause';
            this.elements.autoPlayIcon.className = 'fas fa-pause';
        } else {
            this.stopAutoPlay();
            this.elements.playIcon.className = 'fas fa-play';
            this.elements.autoPlayIcon.className = 'fas fa-play';
        }
    }
    
    startAutoPlay() {
        if (this.autoPlayInterval) {
            clearInterval(this.autoPlayInterval);
        }
        
        this.autoPlayInterval = setInterval(() => {
            this.nextImage();
        }, 4000); // Change image every 4 seconds
    }
    
    stopAutoPlay() {
        if (this.autoPlayInterval) {
            clearInterval(this.autoPlayInterval);
            this.autoPlayInterval = null;
        }
    }
    
    resetAutoPlay() {
        if (this.isAutoPlay) {
            this.stopAutoPlay();
            this.startAutoPlay();
        }
    }
    
    openLightbox() {
        const currentItem = this.galleryData[this.currentIndex];
        
        // Create lightbox
        const lightbox = document.createElement('div');
        lightbox.className = 'gallery-lightbox';
        lightbox.innerHTML = `
            <div class="lightbox-content">
                <button class="lightbox-close">&times;</button>
                <div class="lightbox-image">
                    <img src="${currentItem.img}" alt="${currentItem.title}">
                </div>
                <div class="lightbox-info">
                    <h3>${currentItem.title}</h3>
                    <p>${currentItem.description}</p>
                    <span class="badge ${currentItem.badgeClass}" style="margin-top: 10px;">
                        ${currentItem.badge}
                    </span>
                </div>
            </div>
        `;
        
        document.body.appendChild(lightbox);
        
        // Show lightbox with animation
        setTimeout(() => lightbox.classList.add('active'), 10);
        
        // Close button
        const closeBtn = lightbox.querySelector('.lightbox-close');
        closeBtn.addEventListener('click', () => {
            lightbox.classList.remove('active');
            setTimeout(() => {
                if (lightbox.parentNode) {
                    lightbox.parentNode.removeChild(lightbox);
                }
            }, 300);
        });
        
        // Close on click outside
        lightbox.addEventListener('click', (e) => {
            if (e.target === lightbox) {
                closeBtn.click();
            }
        });
        
        // Keyboard navigation in lightbox
        document.addEventListener('keydown', function lightboxKeyboard(e) {
            if (e.key === 'Escape') {
                closeBtn.click();
                document.removeEventListener('keydown', lightboxKeyboard);
            }
        });
    }
    
    toggleFullscreen() {
        this.isFullscreen = !this.isFullscreen;
        
        const previewContainer = document.querySelector('.main-preview-container');
        
        if (this.isFullscreen) {
            previewContainer.requestFullscreen?.().catch(console.log);
            this.elements.fullscreenToggle.innerHTML = '<i class="fas fa-compress"></i>';
        } else {
            document.exitFullscreen?.();
            this.elements.fullscreenToggle.innerHTML = '<i class="fas fa-expand"></i>';
        }
    }
    
    handleKeyboard(event) {
        switch(event.key) {
            case 'ArrowLeft':
                event.preventDefault();
                this.prevImage();
                break;
            case 'ArrowRight':
                event.preventDefault();
                this.nextImage();
                break;
            case ' ':
                event.preventDefault();
                this.toggleAutoPlay();
                break;
            case 'Enter':
                event.preventDefault();
                this.openLightbox();
                break;
        }
    }
    
    destroy() {
        this.stopAutoPlay();
        // Clean up event listeners if needed
    }
}

// Initialize gallery when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    window.galleryPreview = new GalleryPreview();
    
    // Handle fullscreen change
    document.addEventListener('fullscreenchange', () => {
        const previewContainer = document.querySelector('.main-preview-container');
        if (!document.fullscreenElement) {
            window.galleryPreview.isFullscreen = false;
            document.getElementById('fullscreen-toggle').innerHTML = '<i class="fas fa-expand"></i>';
        }
    });
});

// Resize handler for mini sliders
window.addEventListener('resize', () => {
    if (window.galleryPreview) {
        window.galleryPreview.updateMiniSliders();
    }
});

