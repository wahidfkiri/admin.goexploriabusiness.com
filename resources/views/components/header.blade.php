<header class="dashboard-header">
        <div class="header-content">
            <div class="header-left">
                <!-- <button class="sidebar-toggle" id="sidebarToggle">
                    <i class="fas fa-bars"></i>
                </button>
                <h1>Tableau de bord administrateur</h1>
                <p>Bienvenue sur la plateforme GO EXPLORIA BUSINESS</p> -->
            </div>
            
            <div class="header-right">
                <div class="search-container">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" class="search-input" placeholder="Rechercher...">
                </div>
                
                <div class="header-actions">
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