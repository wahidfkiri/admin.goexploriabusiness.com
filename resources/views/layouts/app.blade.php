<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GO EXPLORIA BUSINESS - Dashboard Admin</title>
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome pour les icônes -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}"> 
   
</head>
<body>
    <!-- OVERLAY FOR MOBILE -->
    <div class="overlay" id="overlay"></div>
     <!-- HEADER -->
    <x-header />
    
  <!-- SIDEBAR -->
    <x-side-bar />
    @yield('content')
     
    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
   <script>
    // Toggle sidebar on mobile
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('dashboardSidebar');
    const overlay = document.getElementById('overlay');
    
    // Vérifier si sidebarToggle existe avant d'ajouter l'événement
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('active');
            overlay.classList.toggle('active');
            
            // Toggle icon
            const icon = this.querySelector('i');
            if (icon.classList.contains('fa-bars')) {
                icon.classList.remove('fa-bars');
                icon.classList.add('fa-times');
            } else {
                icon.classList.remove('fa-times');
                icon.classList.add('fa-bars');
            }
        });
    }
    
    // Close sidebar when clicking overlay
    if (overlay) {
        overlay.addEventListener('click', function() {
            sidebar.classList.remove('active');
            this.classList.remove('active');
            
            // Réinitialiser l'icône du toggle si il existe
            if (sidebarToggle) {
                const icon = sidebarToggle.querySelector('i');
                if (icon) {
                    icon.classList.remove('fa-times');
                    icon.classList.add('fa-bars');
                }
            }
        });
    }
    
    // Gestion des sous-menus - CORRIGÉ
    document.querySelectorAll('.has-submenu > .menu-link').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const submenuItem = this.closest('.has-submenu');
            const wasActive = submenuItem.classList.contains('active');
            
            // Fermer tous les autres sous-menus
            document.querySelectorAll('.has-submenu').forEach(item => {
                item.classList.remove('active');
            });
            
            // Si le sous-menu n'était pas actif, l'ouvrir
            if (!wasActive) {
                submenuItem.classList.add('active');
            }
        });
    });
    
    // Gestion des clics sur les éléments de menu réguliers (non sous-menu)
    document.querySelectorAll('.menu-item:not(.has-submenu) > a, .menu-item:not(.has-submenu)').forEach(item => {
        item.addEventListener('click', function(e) {
            // Pour les liens de menu réguliers, on laisse le lien fonctionner normalement
            // mais on met à jour la classe active
            e.stopPropagation();
            
            // Si c'est un lien de déconnexion, on ne fait rien
            if (this.closest('a') && this.closest('a').getAttribute('href') === '{{ route("logout") }}') {
                return;
            }
            
            // Si c'est un lien normal, on met à jour l'état actif
            if (this.closest('a') && !this.closest('a').hasAttribute('href') || 
                this.closest('a') && this.closest('a').getAttribute('href') === '#') {
                e.preventDefault();
                
                // Supprimer la classe active de tous les éléments de menu
                document.querySelectorAll('.menu-item').forEach(menuItem => {
                    menuItem.classList.remove('active');
                });
                
                // Ajouter la classe active à l'élément parent
                const parentItem = this.closest('.menu-item');
                if (parentItem) {
                    parentItem.classList.add('active');
                }
                
                // Fermer la sidebar sur mobile
                if (window.innerWidth <= 992) {
                    sidebar.classList.remove('active');
                    overlay.classList.remove('active');
                    
                    // Réinitialiser l'icône du toggle si il existe
                    if (sidebarToggle) {
                        const icon = sidebarToggle.querySelector('i');
                        if (icon) {
                            icon.classList.remove('fa-times');
                            icon.classList.add('fa-bars');
                        }
                    }
                }
            }
        });
    });
    
    // Fermer les sous-menus quand on clique en dehors
    document.addEventListener('click', function(e) {
        // Si on ne clique pas sur un élément de menu ou sous-menu
        if (!e.target.closest('.has-submenu') && !e.target.closest('.menu-item')) {
            document.querySelectorAll('.has-submenu').forEach(item => {
                item.classList.remove('active');
            });
        }
    });
    
    // Simulate real-time stats update
    setInterval(() => {
        const visitsElement = document.querySelectorAll('.stats-value')[1];
        if (visitsElement) {
            const currentVisits = parseInt(visitsElement.textContent.replace('K', '')) * 1000;
            const randomIncrement = Math.floor(Math.random() * 100) + 1;
            const newVisits = (currentVisits + randomIncrement);
            visitsElement.textContent = (newVisits / 1000).toFixed(1) + 'K';
        }
    }, 15000);
    
    // Notification badge update
    let notificationCount = 3;
    const notificationBtn = document.querySelector('.notification-btn');
    
    if (notificationBtn) {
        notificationBtn.addEventListener('click', function() {
            const badge = this.querySelector('.notification-badge');
            if (notificationCount > 0 && badge) {
                notificationCount = 0;
                badge.style.display = 'none';
                
                // Show a toast message
                alert('Toutes les notifications ont été marquées comme lues');
            }
        });
    }
    
    // Dark mode toggle functionality
    const darkModeToggle = document.getElementById('darkModeToggle');
    
    if (darkModeToggle) {
        darkModeToggle.addEventListener('change', function() {
            if (this.checked) {
                // Switch to dark mode for entire interface
                document.body.style.backgroundColor = '#0f172a';
                document.body.style.color = '#cbd5e1';
                const dashboardContent = document.querySelector('.dashboard-content');
                if (dashboardContent) {
                    dashboardContent.style.backgroundColor = '#0f172a';
                }
                
                // Update cards
                document.querySelectorAll('.stats-card, .activity-card, .project-card, .action-btn').forEach(card => {
                    card.style.backgroundColor = '#1e293b';
                    card.style.borderColor = '#334155';
                    card.style.color = '#cbd5e1';
                });
                
                // Update welcome card
                const welcomeCard = document.querySelector('.welcome-card');
                if (welcomeCard) {
                    welcomeCard.style.background = 'linear-gradient(135deg, #1e293b, #334155)';
                }
                
                // Update text colors
                document.querySelectorAll('.stats-value, .activity-title, .project-title, .action-text').forEach(text => {
                    text.style.color = '#f1f5f9';
                });
            } else {
                // Switch back to light mode
                document.body.style.backgroundColor = '';
                document.body.style.color = '';
                const dashboardContent = document.querySelector('.dashboard-content');
                if (dashboardContent) {
                    dashboardContent.style.backgroundColor = '';
                }
                
                // Update cards
                document.querySelectorAll('.stats-card, .activity-card, .project-card, .action-btn').forEach(card => {
                    card.style.backgroundColor = '';
                    card.style.borderColor = '';
                    card.style.color = '';
                });
                
                // Update welcome card
                const welcomeCard = document.querySelector('.welcome-card');
                if (welcomeCard) {
                    welcomeCard.style.background = '';
                }
                
                // Update text colors
                document.querySelectorAll('.stats-value, .activity-title, .project-title, .action-text').forEach(text => {
                    text.style.color = '';
                });
            }
        });
    }
    
    // Fonction pour fermer la sidebar sur mobile
    const closeSidebarOnMobile = () => {
        if (window.innerWidth <= 992) {
            sidebar.classList.remove('active');
            overlay.classList.remove('active');
            
            if (sidebarToggle) {
                const icon = sidebarToggle.querySelector('i');
                if (icon) {
                    icon.classList.remove('fa-times');
                    icon.classList.add('fa-bars');
                }
            }
        }
    };
    
    // Fermer la sidebar quand on clique sur un lien dans un sous-menu
    document.querySelectorAll('.submenu-item').forEach(item => {
        item.addEventListener('click', function(e) {
            // Ne pas empêcher le comportement par défaut des liens
            // Fermer juste la sidebar sur mobile
            if (window.innerWidth <= 992) {
                closeSidebarOnMobile();
            }
            
            // Marquer cet élément comme actif
            document.querySelectorAll('.submenu-item').forEach(subItem => {
                subItem.classList.remove('active');
            });
            this.classList.add('active');
        });
    });
</script>

</body>
</html>