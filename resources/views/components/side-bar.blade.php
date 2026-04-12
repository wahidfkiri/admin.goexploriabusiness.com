<aside class="dashboard-sidebar" id="dashboardSidebar">
    <div class="sidebar-logo">
        <div class="logo-main">
            <img src="{{ asset('logo.png') }}" style="width: 180px;" alt="Logo">
        </div>
    </div>
    
    <ul class="sidebar-menu">
        @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('super-admin'))
        <li>
            <a href="{{ route('dashboard') }}" class="menu-item active">
                <span class="menu-icon"><i class="fas fa-tachometer-alt"></i></span>
                <span class="menu-text">Tableau de bord</span>
            </a>
        </li>
        
        <li class="has-submenu">
            <a href="#" class="menu-link">
                <span class="menu-icon"><i class="fas fa-tasks"></i></span>
                <span class="menu-text">Activités</span>
                <span class="menu-arrow"><i class="fas fa-chevron-down"></i></span>
            </a>
            <ul class="submenu">
                <li><a href="{{ route('activities.index') }}" class="submenu-item"><i class="fas fa-list submenu-icon"></i>Activités</a></li>
                <li><a href="{{ route('categories.index') }}" class="submenu-item"><i class="fas fa-tags submenu-icon"></i>Catégories</a></li>
            </ul>
        </li>
        
        <li>
            <a href="{{ route('etablissements.index') }}" class="menu-item">
                <span class="menu-icon"><i class="fas fa-building"></i></span>
                <span class="menu-text">Établissements</span>
            </a>
        </li>
        
        <li class="has-submenu">
            <a href="#" class="menu-link">
                <span class="menu-icon"><i class="fas fa-map-marked-alt"></i></span>
                <span class="menu-text">Destinations</span>
                <span class="menu-arrow"><i class="fas fa-chevron-down"></i></span>
            </a>
            <ul class="submenu">
                <li><a href="{{ route('destinations.menus.index') }}" class="submenu-item"><i class="fas fa-utensils submenu-icon"></i>Gestion de menus</a></li>
                <li><a href="{{ route('continents.index') }}" class="submenu-item"><i class="fas fa-globe-americas submenu-icon"></i>Continents</a></li>
                <li><a href="{{ route('countries.index') }}" class="submenu-item"><i class="fas fa-flag submenu-icon"></i>Pays</a></li>
                <li><a href="{{ route('provinces.index') }}" class="submenu-item"><i class="fas fa-map submenu-icon"></i>Provinces</a></li>
                <li><a href="{{ route('regions.index') }}" class="submenu-item"><i class="fas fa-location-dot submenu-icon"></i>Régions</a></li>
                <li><a href="{{ route('villes.index') }}" class="submenu-item"><i class="fas fa-city submenu-icon"></i>Villes</a></li>
                <li><a href="{{ url('quartiers.index') }}" class="submenu-item"><i class="fas fa-home submenu-icon"></i>Quartiers</a></li>
            </ul>
        </li>
        
        <li>
            <a href="{{ route('cms.admin.themes.index') }}" class="menu-item">
                <span class="menu-icon"><i class="fas fa-palette"></i></span>
                <span class="menu-text">Templates</span>
            </a>
        </li>
        
        <li>
            <a href="{{ route('plugins.index') }}" class="menu-item">
                <span class="menu-icon"><i class="fas fa-plug"></i></span>
                <span class="menu-text">Applications</span>
            </a>
        </li>
        
        <li class="has-submenu">
            <a href="#" class="menu-link">
                <span class="menu-icon"><i class="fas fa-cube"></i></span>
                <span class="menu-text">Projets</span>
                <span class="menu-arrow"><i class="fas fa-chevron-down"></i></span>
            </a>
            <ul class="submenu">
                <li><a href="{{ route('projects.index') }}" class="submenu-item"><i class="fas fa-cubes submenu-icon"></i>Gestion des projets</a></li>
                <li><a href="{{ route('tasks.index') }}" class="submenu-item"><i class="fas fa-check-circle submenu-icon"></i>Liste des tâches</a></li>
                <li><a href="{{ route('projects.calendar') }}" class="submenu-item"><i class="fas fa-calendar-alt submenu-icon"></i>Calendrier des projets</a></li>
            </ul>
        </li>
        
        <li class="has-submenu">
            <a href="#" class="menu-link">
                <span class="menu-icon"><i class="fas fa-shopping-cart"></i></span>
                <span class="menu-text">Ecommerce</span>
                <span class="menu-arrow"><i class="fas fa-chevron-down"></i></span>
            </a>
            <ul class="submenu">
                <li>
                    <a href="{{ route('products.index') }}" class="submenu-item">
                        <i class="fas fa-box submenu-icon"></i>
                        Produits 
                        @if(\App\Models\Product::count() > 0)
                        <span class="submenu-badge">
                            {{ \App\Models\Product::count() }}
                        </span>
                        @endif
                    </a>
                </li>
                <li>
                    <a href="{{ route('payments.index') }}" class="submenu-item">
                        <i class="fas fa-credit-card submenu-icon"></i>
                        Paiements  
                        @if(\App\Models\Payment::where('status', 'en_attente')->count() > 0)
                        <span class="submenu-badge bg-warning">
                            {{ \App\Models\Payment::where('status', 'en_attente')->count() }}
                        </span>
                        @endif
                    </a>
                </li>
                <li>
                    <a href="{{ url('transactions.index') }}" class="submenu-item">
                        <i class="fas fa-history submenu-icon"></i>
                        Transactions
                    </a>
                </li>
                <li>
                    <a href="{{ url('orders.index') }}" class="submenu-item">
                        <i class="fas fa-shopping-cart submenu-icon"></i>
                        Commandes
                    </a>
                </li>
                <li>
                    <a href="{{ url('customers.index') }}" class="submenu-item">
                        <i class="fas fa-users submenu-icon"></i>
                        Clients
                        <span class="submenu-badge">
                            {{ \App\Models\Customer::count() }}
                        </span>
                    </a>
                </li>
                <li class="submenu-divider"></li>
                <li>
                    <a href="{{ route('admin.payment.gateways') }}" class="submenu-item">
                        <i class="fas fa-cog submenu-icon"></i>
                        Configuration paiements
                    </a>
                </li>
                <li>
                    <a href="{{ url('ecommerce/stats') }}" class="submenu-item">
                        <i class="fas fa-chart-line submenu-icon"></i>
                        Statistiques
                    </a>
                </li>
            </ul>
        </li>
        
        <li class="has-submenu">
            <a href="#" class="menu-link">
                <span class="menu-icon"><i class="fas fa-file-invoice"></i></span>
                <span class="menu-text">Facturation</span>
                <span class="menu-arrow"><i class="fas fa-chevron-down"></i></span>
            </a>
            <ul class="submenu">
                <li><a href="{{ route('invoices.index') }}" class="submenu-item"><i class="fas fa-file-invoice-dollar submenu-icon"></i>Factures @php($unpaidInvoices = \App\Models\Invoice::whereIn('status', ['en_attente', 'partiellement_payee'])->count())@if($unpaidInvoices > 0)<span class="submenu-badge bg-danger">{{ $unpaidInvoices }}</span>@endif</a></li>
                <li><a href="{{ url('quotes.index') }}" class="submenu-item"><i class="fas fa-file-signature submenu-icon"></i>Devis</a></li>
                <li><a href="{{ url('ecommerce/settings') }}" class="submenu-item"><i class="fas fa-sliders-h submenu-icon"></i>Paramètres</a></li>
            </ul>
        </li>
        
        <li class="has-submenu">
            <a href="#" class="menu-link">
                <span class="menu-icon"><i class="fas fa-users"></i></span>
                <span class="menu-text">Utilisateurs</span>
                <span class="menu-arrow"><i class="fas fa-chevron-down"></i></span>
            </a>
            <ul class="submenu">
                <li><a href="{{ route('users.index') }}" class="submenu-item"><i class="fas fa-user-shield submenu-icon"></i>Utilisateurs</a></li>
            </ul>
        </li>
        
        <li>
            <a href="#" class="menu-item">
                <span class="menu-icon"><i class="fas fa-chart-line"></i></span>
                <span class="menu-text">Analytics</span>
            </a>
        </li>
        
        <li class="has-submenu">
            <a href="#" class="menu-link">
                <span class="menu-icon"><i class="fas fa-cog"></i></span>
                <span class="menu-text">Paramètres Page Accueil</span>
                <span class="menu-arrow"><i class="fas fa-chevron-down"></i></span>
            </a>
            <ul class="submenu">
                <li><a href="{{ route('menus.index') }}" class="submenu-item"><i class="fas fa-bars submenu-icon"></i>Gestion de menus</a></li>
                <li><a href="{{ route('sliders.index') }}" class="submenu-item"><i class="fas fa-images submenu-icon"></i>Sliders</a></li>
            </ul>
        </li>
        @endif
        
        <li>
            <a class="menu-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <span class="menu-icon"><i class="fas fa-sign-out-alt"></i></span>
                <span class="menu-text">Se déconnecter</span>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
        </li>
    </ul>
</aside>