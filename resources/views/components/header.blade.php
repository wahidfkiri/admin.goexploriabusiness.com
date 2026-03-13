<header class="dashboard-header">
    <div class="header-content">
        <div class="header-left">
                <!-- Ajout du lien "Voir site web" -->
                <a href="https://goexploriabusiness.com" 
                   target="_blank" 
                   rel="noopener noreferrer" 
                   class="website-link">
                    <i class="fas fa-external-link-alt"></i>
                    Voir site web
                </a>
            <!-- <button class="sidebar-toggle" id="sidebarToggle">
                <i class="fas fa-bars"></i>
            </button>
            <h1>Tableau de bord administrateur</h1>
            <p>Bienvenue sur la plateforme GO EXPLORIA BUSINESS</p> -->
        </div>
        
        <div class="header-right">
            <div class="header-actions">
                
                <div class="search-container">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" class="search-input" placeholder="Rechercher...">
                </div>
                
                <button class="notification-btn">
                    <i class="far fa-bell"></i>
                    <span class="notification-badge">3</span>
                </button>
                
                <div class="user-profile">
                    <div class="user-avatar">AD</div>
                    <div class="user-info">
                        <h5>{{auth()->user()->name}}</h5>
                        <p>Administrateur</p>
                    </div>
                    <i class="fas fa-chevron-down"></i>
                </div>
            </div>
        </div>
    </div>
</header>
<style>
    .website-link {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 16px;
    background-color: #3a56e4;  /* Couleur indigo/violette */
    color: white;
    text-decoration: none;
    border-radius: 8px;
    font-weight: 500;
    transition: background-color 0.3s ease;
    margin-right: 20px;
}

.website-link:hover {
    background-color: #4338ca;  /* Version plus foncée au survol */
}

.website-link i {
    font-size: 16px;
}
</style>
