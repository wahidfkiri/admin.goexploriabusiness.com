<aside class="dashboard-sidebar" id="dashboardSidebar">
  <div class="sidebar-logo">
    <div class="logo-main">
      <img src="{{asset('logo.png')}}" style="width:210px;">
    </div>
    <!-- <div class="logo-sub">Plateforme Builder Web</div> -->
  </div>
  <ul class="sidebar-menu">
    <li>
      <a href="{{route('dashboard')}}" class="menu-item active">
        <span class="menu-icon">
          <i class="fas fa-tachometer-alt"></i>
        </span>
        <span class="menu-text">Tableau de bord</span>
      </a>
    </li>
    <li class="has-submenu">
      <a href="#" class="menu-link">
        <span class="menu-icon">
          <i class="fas fa-tasks"></i>
        </span>
        <span class="menu-text">Activités</span>
        <span class="menu-arrow">
          <i class="fas fa-chevron-down"></i>
        </span>
      </a>
      <ul class="submenu">
        <li>
          <a href="{{ route('activities.index') }}" class="submenu-item">
            <span class="submenu-text">Activités</span>
          </a>
        </li>
        <li>
          <a href="{{ route('categories.index') }}" class="submenu-item">
            <span class="submenu-text">Catégories</span>
          </a>
        </li>
      </ul>
    </li>
    <li class="has-submenu">
      <a href="#" class="menu-link">
        <span class="menu-icon">
          <i class="fas fa-building"></i>
        </span>
        <span class="menu-text">Etablissements</span>
        <span class="menu-arrow">
          <i class="fas fa-chevron-down"></i>
        </span>
      </a>
      <ul class="submenu">
        <li>
          <a href="{{ route('etablissements.index') }}" class="submenu-item">
            <span class="submenu-text">liste des établissements</span>
          </a>
        </li>
        <li>
          <a href="{{ route('continents.index') }}" class="submenu-item">
            <span class="submenu-text">rendez vous</span>
          </a>
        </li>
      </ul>
    </li>
    <li class="has-submenu">
      <a href="#" class="menu-link">
        <span class="menu-icon">
          <i class="fas fa-map-marked-alt"></i>
        </span>
        <span class="menu-text">Destinations</span>
        <span class="menu-arrow">
          <i class="fas fa-chevron-down"></i>
        </span>
      </a>
      <ul class="submenu">
        <li>
          <a href="{{ route('destinations.menus.index') }}" class="submenu-item">
            <span class="submenu-text">Gestion de menus</span>
          </a>
        </li>
        <li>
          <a href="{{ route('continents.index') }}" class="submenu-item">
            <span class="submenu-text">Continents</span>
          </a>
        </li>
        <li>
          <a href="{{ route('countries.index') }}" class="submenu-item">
            <span class="submenu-text">Pays</span>
          </a>
        </li>
        <li>
          <a href="{{ route('provinces.index') }}" class="submenu-item">
            <span class="submenu-text">Provinces</span>
          </a>
        </li>
        <li>
          <a href="{{ route('regions.index') }}" class="submenu-item">
            <span class="submenu-text">Régions</span>
          </a>
        </li>
        <li>
          <a href="{{ route('villes.index') }}" class="submenu-item">
            <span class="submenu-text">Villes</span>
          </a>
        <li>
          <a href="{{ route('villes.index') }}" class="submenu-item">
            <span class="submenu-text">Quartiers</span>
          </a>
        </li>
      </ul>
    </li>
    <!-- <li>
      <a href="{{route('customers.index')}}" class="menu-item">
        <span class="menu-icon">
          <i class="fas fa-users"></i>
        </span>
        <span class="menu-text">Clients</span>
      </a>
    </li> -->
    <!-- <li>
      <a href="{{route('websites.index')}}" class="menu-item">
        <span class="menu-icon">
          <i class="fas fa-globe"></i>
        </span>
        <span class="menu-text">Sites web</span>
      </a>
    </li> -->
    <li>
      <a href="{{route('templates')}}" class="menu-item">
        <span class="menu-icon">
          <i class="fas fa-palette"></i>
        </span>
        <span class="menu-text">Templates</span>
      </a>
    </li> 
    <!-- <li class="menu-title">Contenu</li> -->
    <li class="has-submenu">
      <a href="#" class="menu-link">
        <span class="menu-icon">
          <i class="fas fa-cube"></i>
        </span>
        <span class="menu-text">Projets</span>
        <span class="menu-arrow">
          <i class="fas fa-chevron-down"></i>
        </span>
      </a>
      <ul class="submenu">
        <li>
          <a href="{{ route('projects.index') }}" class="submenu-item">
            <span class="submenu-text">Gestion des projets</span>
          </a>
        </li>
        <li>
          <a href="{{ route('projects.calendar') }}" class="submenu-item">
            <span class="submenu-text">Calendrier des projets</span>
          </a>
        </li>
      </ul>
    </li>
    <li class="has-submenu">
    <a href="#" class="menu-link">
        <span class="menu-icon">
            <i class="fas fa-shopping-cart"></i>
        </span>
        <span class="menu-text">Ecommerce</span>
        <span class="menu-arrow">
            <i class="fas fa-chevron-down"></i>
        </span>
    </a>
    <ul class="submenu">
        <!-- Produits -->
        <li>
            <a href="{{ route('products.index') }}" class="submenu-item">
                <i class="fas fa-box submenu-icon"></i>
                <span class="submenu-text ms-2">Produits</span>
                @php
                    $productsCount = \App\Models\Product::count();
                @endphp
                @if($productsCount > 0)
                    <span class="submenu-badge">{{ $productsCount }}</span>
                @endif
            </a>
        </li>


        <!-- Paiements -->
        <li>
            <a href="{{ route('payments.index') }}" class="submenu-item">
                <i class="fas fa-credit-card submenu-icon"></i>
                <span class="submenu-text ms-2">Paiements</span>
                @php
                    $pendingPayments = \App\Models\Payment::where('status', 'en_attente')->count();
                @endphp
                @if($pendingPayments > 0)
                    <span class="submenu-badge bg-warning">{{ $pendingPayments }}</span>
                @endif
            </a>
        </li>

        <!-- Transactions -->
        <li>
            <a href="{{ url('transactions.index') }}" class="submenu-item">
                <i class="fas fa-history submenu-icon"></i>
                <span class="submenu-text ms-2">Transactions</span>
            </a>
        </li>

        <!-- Clients -->
        <li>
            <a href="{{ url('orders.index') }}" class="submenu-item">
                <i class="fas fa-shopping-cart submenu-icon"></i>
                <span class="submenu-text ms-2">Commandes</span>
                <span class="submenu-badge">0</span>
            </a>
        </li>
        <!-- Clients -->
        <li>
            <a href="{{ url('customers.index') }}" class="submenu-item">
                <i class="fas fa-users submenu-icon"></i>
                <span class="submenu-text ms-2">Clients</span>
                @php
                    $customersCount = \App\Models\Customer::count();
                @endphp
                <span class="submenu-badge">{{ $customersCount }}</span>
            </a>
        </li>

        <!-- Séparateur -->
        <li class="submenu-divider"></li>

        <!-- Configuration Paiements -->
        <li>
            <a href="{{ route('admin.payment.gateways') }}" class="submenu-item">
                <i class="fas fa-cog submenu-icon"></i>
                <span class="submenu-text ms-2">Configuration paiements</span>
            </a>
        </li>


        <!-- Statistiques -->
        <li>
            <a href="{{ url('ecommerce/stats') }}" class="submenu-item">
                <i class="fas fa-chart-line submenu-icon"></i> 
                <span class="submenu-text ms-2">Statistiques</span>
            </a>
        </li>
    </ul>
</li>

    <li class="has-submenu">
    <a href="#" class="menu-link">
        <span class="menu-icon">
            <i class="fas fa-file-invoice"></i>
        </span>
        <span class="menu-text"> Facturation</span>
        <span class="menu-arrow">
            <i class="fas fa-chevron-down"></i>
        </span>
    </a>
    <ul class="submenu">
      

        <!-- Factures -->
        <li>
            <a href="{{ route('invoices.index') }}" class="submenu-item">
                <i class="fas fa-file-invoice submenu-icon"></i>
                <span class="submenu-text ms-2">Factures</span>
                @php
                    $unpaidInvoices = \App\Models\Invoice::whereIn('status', ['en_attente', 'partiellement_payee'])
                        ->count();
                @endphp
                @if($unpaidInvoices > 0)
                    <span class="submenu-badge bg-danger">{{ $unpaidInvoices }}</span>
                @endif
            </a>
        </li>

        <!-- Devis -->
        <li>
            <a href="{{ url('quotes.index') }}" class="submenu-item">
                <i class="fas fa-file-signature submenu-icon"></i>
                <span class="submenu-text ms-2">Devis</span>
            </a>
        </li>
        <!-- Paramètres ecommerce -->
        <li>
            <a href="{{ url('ecommerce/settings') }}" class="submenu-item">
                <i class="fas fa-sliders-h submenu-icon"></i>
                <span class="submenu-text ms-2">Paramètres</span>
            </a>
        </li>
    </ul>
  </li>
    <li class="has-submenu">
      <a href="#" class="menu-link">
        <span class="menu-icon">
          <i class="fas fa-users"></i>
        </span>
        <span class="menu-text">Utilisateurs</span>
        <span class="menu-arrow">
          <i class="fas fa-chevron-down"></i>
        </span>
      </a>
      <ul class="submenu">
        <li>
          <a href="{{ route('users.index') }}" class="submenu-item">
            <span class="submenu-text">Utilisateurs</span>
          </a>
        </li>
      </ul>
    </li>
    <li>
      <a href="#" class="menu-item">
        <span class="menu-icon">
          <i class="fas fa-chart-line"></i>
        </span>
        <span class="menu-text">Analytics</span>
      </a>
    </li>
    <li class="has-submenu">
      <a href="#" class="menu-link">
        <span class="menu-icon">
          <i class="fas fa-cog"></i>
        </span>
        <span class="menu-text">Paramètres Page Accueil</span>
        <span class="menu-arrow">
          <i class="fas fa-chevron-down"></i>
        </span>
      </a>
      <ul class="submenu">
        <li>
          <a href="{{ route('menus.index') }}" class="submenu-item">
            <span class="submenu-text">Gestion de menus</span>
          </a>
        </li>
        <li>
          <a href="{{ route('settings.pages.index') }}" class="submenu-item">
            <span class="submenu-text">UX Design</span>
          </a>
        </li>
      </ul>
    </li>
    <li>
      <a class="menu-item" href="{{route('logout')}}" onclick="event.preventDefault();
                    document.getElementById('logout-form').submit();">
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;"> @csrf </form>
        <span class="menu-icon">
          <i class="fas fa-sign-out"></i>
        </span>
        <span class="menu-text">Se déconnecter</span>
      </a>
    </li>
  </ul>
</aside>