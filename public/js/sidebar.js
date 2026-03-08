// sidebar.js
document.addEventListener('DOMContentLoaded', function() {
    // Gestion des sous-menus
    const submenuParents = document.querySelectorAll('.has-submenu');
    
    if (submenuParents.length > 0) {
        submenuParents.forEach(parent => {
            const link = parent.querySelector('.menu-link');
            
            if (link) {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    // Fermer tous les autres sous-menus
                    submenuParents.forEach(item => {
                        if (item !== parent && item.classList.contains('active')) {
                            item.classList.remove('active');
                        }
                    });
                    
                    // Activer/désactiver le sous-menu actuel
                    parent.classList.toggle('active');
                });
            }
        });
        
        // Fermer le sous-menu en cliquant en dehors
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.has-submenu')) {
                submenuParents.forEach(parent => {
                    parent.classList.remove('active');
                });
            }
        });
    }
});