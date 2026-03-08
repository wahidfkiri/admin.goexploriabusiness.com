<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Templates - GO EXPLORIA BUSINESS</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome pour les icônes -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #4361ee;
            --primary-light: #eef2ff;
            --secondary-color: #6c757d;
            --accent-color: #06d6a0;
            --accent-light: #e6fcf5;
            --warning-color: #ffd166;
            --danger-color: #ef476f;
            --sidebar-dark: #0f172a;
            --sidebar-light: #1e293b;
            --light-bg: #f8fafc;
            --white: #ffffff;
            --card-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --sidebar-width: 280px;
            --header-height: 70px;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--light-bg);
            color: #334155;
            overflow-x: hidden;
        }
        
        /* HEADER STYLES */
        .dashboard-header {
            background-color: var(--white);
            border-bottom: 1px solid #e2e8f0;
            padding: 0 30px;
            position: fixed;
            top: 0;
            left: var(--sidebar-width);
            right: 0;
            z-index: 100;
            height: var(--header-height);
            display: flex;
            align-items: center;
            box-shadow: var(--card-shadow);
            transition: left 0.3s ease;
        }
        
        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
        }
        
        .header-left h1 {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1e293b;
            margin: 0;
        }
        
        .header-left p {
            font-size: 0.875rem;
            color: #64748b;
            margin: 0;
        }
        
        .header-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        
        /* Search bar */
        .search-container {
            position: relative;
            width: 300px;
        }
        
        .search-input {
            width: 100%;
            padding: 10px 15px 10px 40px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            background-color: #f8fafc;
            font-size: 0.9rem;
            transition: all 0.3s;
        }
        
        .search-input:focus {
            outline: none;
            border-color: var(--primary-color);
            background-color: var(--white);
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
        }
        
        .search-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
        }
        
        /* Header actions */
        .header-actions {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .notification-btn {
            position: relative;
            background: none;
            border: none;
            color: #64748b;
            font-size: 1.2rem;
            cursor: pointer;
        }
        
        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background-color: var(--danger-color);
            color: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            font-size: 0.7rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }
        
        .user-profile {
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
            padding: 5px 10px;
            border-radius: 8px;
            transition: background-color 0.2s;
        }
        
        .user-profile:hover {
            background-color: #f1f5f9;
        }
        
        .user-avatar {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 1.1rem;
        }
        
        .user-info h5 {
            font-size: 0.95rem;
            font-weight: 600;
            margin: 0;
            color: #1e293b;
        }
        
        .user-info p {
            font-size: 0.8rem;
            color: #64748b;
            margin: 0;
        }
        
        /* SIDEBAR STYLES - DARK MODE */
        .dashboard-sidebar {
            background-color: var(--sidebar-dark);
            width: var(--sidebar-width);
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            z-index: 101;
            padding: 25px 0;
            overflow-y: auto;
            transition: all 0.3s ease;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
        }
        
        .sidebar-logo {
            padding: 0 25px 30px;
            border-bottom: 1px solid #334155;
            margin-bottom: 25px;
        }
        
        .logo-main {
            font-size: 1.8rem;
            font-weight: 800;
            color: var(--white);
            margin-bottom: 5px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .logo-sub {
            font-size: 0.85rem;
            color: #94a3b8;
            font-weight: 400;
        }
        
        .sidebar-menu {
            list-style: none;
            padding: 0;
        }
        
        .menu-title {
            font-size: 0.75rem;
            text-transform: uppercase;
            color: #94a3b8;
            padding: 0 25px 10px;
            font-weight: 600;
            letter-spacing: 1px;
        }
        
        .menu-item {
            padding: 12px 25px;
            display: flex;
            align-items: center;
            color: #cbd5e1;
            text-decoration: none;
            transition: all 0.2s ease;
            border-left: 4px solid transparent;
            margin: 2px 0;
        }
        
        .menu-item:hover, .menu-item.active {
            background-color: var(--sidebar-light);
            color: var(--white);
            border-left: 4px solid var(--primary-color);
        }
        
        .menu-icon {
            width: 24px;
            margin-right: 12px;
            font-size: 1.1rem;
            text-align: center;
        }
        
        .menu-text {
            font-weight: 500;
            font-size: 0.95rem;
        }
        
        .menu-badge {
            margin-left: auto;
            background-color: var(--primary-color);
            color: white;
            border-radius: 20px;
            padding: 3px 8px;
            font-size: 0.7rem;
            font-weight: 600;
        }
        
        /* MAIN CONTENT STYLES */
        .dashboard-content {
            margin-left: var(--sidebar-width);
            margin-top: var(--header-height);
            padding: 30px;
            transition: margin-left 0.3s ease;
            min-height: calc(100vh - var(--header-height));
        }
        
        /* Page Header */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            flex-wrap: wrap;
            gap: 15px;
        }
        
        .page-title {
            font-size: 1.8rem;
            font-weight: 700;
            color: #1e293b;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .page-title-icon {
            color: var(--primary-color);
            font-size: 1.8rem;
        }
        
        .page-actions {
            display: flex;
            gap: 15px;
            align-items: center;
        }
        
        /* Main Card */
        .main-card {
            background-color: var(--white);
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            box-shadow: var(--card-shadow);
            overflow: hidden;
        }
        
        .card-header {
            padding: 20px 25px;
            border-bottom: 1px solid #e2e8f0;
            background-color: #f8fafc;
        }
        
        .card-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: #1e293b;
            margin: 0;
        }
        
        .card-body {
            padding: 0;
        }
        
        /* Table Styles */
        .data-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .data-table thead {
            background-color: #f8fafc;
        }
        
        .data-table th {
            padding: 16px 20px;
            text-align: left;
            font-weight: 600;
            color: #475569;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid #e2e8f0;
            white-space: nowrap;
        }
        
        .data-table tbody tr {
            border-bottom: 1px solid #e2e8f0;
            transition: background-color 0.2s;
        }
        
        .data-table tbody tr:hover {
            background-color: #f8fafc;
        }
        
        .data-table td {
            padding: 16px 20px;
            color: #334155;
            vertical-align: middle;
        }
        
        .template-name {
            font-weight: 500;
            color: #1e293b;
        }
        
        .template-url {
            color: var(--primary-color);
            text-decoration: none;
            font-family: monospace;
            font-size: 0.9rem;
            word-break: break-all;
            max-width: 300px;
            display: inline-block;
        }
        
        .template-url:hover {
            text-decoration: underline;
            color: #3a56e4;
        }
        
        .template-status {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        
        .status-active {
            background-color: rgba(6, 214, 160, 0.1);
            color: #06d6a0;
        }
        
        .status-inactive {
            background-color: rgba(148, 163, 184, 0.1);
            color: #94a3b8;
        }
        
        .template-actions {
            display: flex;
            gap: 8px;
        }
        
        .action-btn {
            width: 34px;
            height: 34px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: none;
            background: none;
            color: #64748b;
            transition: all 0.2s;
            cursor: pointer;
        }
        
        .action-btn:hover {
            background-color: #f1f5f9;
        }
        
        .edit-btn:hover {
            color: var(--primary-color);
        }
        
        .delete-btn:hover {
            color: var(--danger-color);
        }
        
        .preview-btn:hover {
            color: var(--accent-color);
        }
        
        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
        }
        
        .empty-icon {
            font-size: 4rem;
            color: #cbd5e1;
            margin-bottom: 20px;
        }
        
        .empty-title {
            font-size: 1.3rem;
            font-weight: 600;
            color: #64748b;
            margin-bottom: 10px;
        }
        
        .empty-text {
            color: #94a3b8;
            max-width: 400px;
            margin: 0 auto 25px;
        }
        
        /* Modal Styles */
        .modal-content {
            border-radius: 12px;
            border: none;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        
        .modal-header {
            padding: 20px 25px;
            border-bottom: 1px solid #e2e8f0;
            background-color: #f8fafc;
            border-radius: 12px 12px 0 0;
        }
        
        .modal-title {
            font-weight: 600;
            color: #1e293b;
            font-size: 1.2rem;
        }
        
        .modal-body {
            padding: 25px;
        }
        
        .form-label {
            font-weight: 500;
            color: #334155;
            margin-bottom: 8px;
            font-size: 0.9rem;
        }
        
        .form-control, .form-select {
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 10px 15px;
            font-size: 0.95rem;
            transition: all 0.3s;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
        }
        
        .form-text {
            color: #94a3b8;
            font-size: 0.8rem;
            margin-top: 5px;
        }
        
        .modal-footer {
            padding: 20px 25px;
            border-top: 1px solid #e2e8f0;
            background-color: #f8fafc;
            border-radius: 0 0 12px 12px;
        }
        
        /* Pagination */
        .pagination-container {
            padding: 20px 25px;
            border-top: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }
        
        .pagination-info {
            color: #64748b;
            font-size: 0.875rem;
        }
        
        .pagination {
            margin: 0;
        }
        
        .page-link {
            border: 1px solid #e2e8f0;
            color: #475569;
            padding: 8px 14px;
            margin: 0 2px;
            border-radius: 8px;
            transition: all 0.2s;
        }
        
        .page-link:hover {
            background-color: #f1f5f9;
            border-color: #cbd5e1;
            color: #334155;
        }
        
        .page-item.active .page-link {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
        }
        
        /* RESPONSIVE STYLES */
        @media (max-width: 1200px) {
            :root {
                --sidebar-width: 250px;
            }
            
            .search-container {
                width: 250px;
            }
        }
        
        @media (max-width: 992px) {
            .dashboard-sidebar {
                transform: translateX(-100%);
                z-index: 102;
            }
            
            .dashboard-sidebar.active {
                transform: translateX(0);
            }
            
            .dashboard-header {
                left: 0;
            }
            
            .dashboard-content {
                margin-left: 0;
            }
            
            .sidebar-toggle {
                display: block !important;
                background: none;
                border: none;
                color: #64748b;
                font-size: 1.5rem;
                margin-right: 15px;
            }
            
            .overlay {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background-color: rgba(0, 0, 0, 0.5);
                z-index: 101;
                display: none;
            }
            
            .overlay.active {
                display: block;
            }
            
            .search-container {
                width: 200px;
            }
        }
        
        @media (max-width: 768px) {
            .dashboard-content {
                padding: 20px;
            }
            
            .header-left h1 {
                font-size: 1.3rem;
            }
            
            .search-container {
                display: none;
            }
            
            .data-table th, .data-table td {
                padding: 12px 15px;
            }
            
            .template-actions {
                flex-direction: column;
            }
            
            .page-header {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .page-actions {
                width: 100%;
                justify-content: flex-end;
            }
            
            .header-actions .user-info {
                display: none;
            }
        }
        
        @media (max-width: 576px) {
            .dashboard-header {
                padding: 0 15px;
            }
            
            .data-table {
                display: block;
                overflow-x: auto;
            }
            
            .pagination-container {
                flex-direction: column;
                align-items: center;
            }
            
            .modal-body {
                padding: 20px;
            }
        }
        
        /* Dark scrollbar for sidebar */
        .dashboard-sidebar::-webkit-scrollbar {
            width: 6px;
        }
        
        .dashboard-sidebar::-webkit-scrollbar-track {
            background: #1e293b;
        }
        
        .dashboard-sidebar::-webkit-scrollbar-thumb {
            background: #475569;
            border-radius: 3px;
        }
        
        .dashboard-sidebar::-webkit-scrollbar-thumb:hover {
            background: #64748b;
        }
    </style>
</head>
<body>
    <!-- OVERLAY FOR MOBILE -->
    <div class="overlay" id="overlay"></div>
    
    <!-- SIDEBAR -->
    <aside class="dashboard-sidebar" id="dashboardSidebar">
        <div class="sidebar-logo">
            <div class="logo-main">
                <i class="fas fa-compass"></i>
                GO EXPLORIA
            </div>
            <div class="logo-sub">Plateforme Builder Web</div>
        </div>
        
        <ul class="sidebar-menu">
            <li class="menu-title">Navigation</li>
            <li>
                <a href="#" class="menu-item">
                    <span class="menu-icon"><i class="fas fa-tachometer-alt"></i></span>
                    <span class="menu-text">Tableau de bord</span>
                </a>
            </li>
            <li>
                <a href="#" class="menu-item">
                    <span class="menu-icon"><i class="fas fa-sitemap"></i></span>
                    <span class="menu-text">Sites web</span>
                </a>
            </li>
            <li>
                <a href="#" class="menu-item active">
                    <span class="menu-icon"><i class="fas fa-puzzle-piece"></i></span>
                    <span class="menu-text">Composants</span>
                    <span class="menu-badge">3</span>
                </a>
            </li>
            <li>
                <a href="#" class="menu-item">
                    <span class="menu-icon"><i class="fas fa-palette"></i></span>
                    <span class="menu-text">Templates</span>
                    <span class="menu-badge">12</span>
                </a>
            </li>
            
            <li class="menu-title">Contenu</li>
            <li>
                <a href="#" class="menu-item">
                    <span class="menu-icon"><i class="fas fa-images"></i></span>
                    <span class="menu-text">Médias</span>
                </a>
            </li>
            <li>
                <a href="#" class="menu-item">
                    <span class="menu-icon"><i class="fas fa-video"></i></span>
                    <span class="menu-text">Vidéos</span>
                </a>
            </li>
            <li>
                <a href="#" class="menu-item">
                    <span class="menu-icon"><i class="fas fa-newspaper"></i></span>
                    <span class="menu-text">Articles</span>
                </a>
            </li>
            <li>
                <a href="#" class="menu-item">
                    <span class="menu-icon"><i class="fas fa-map-marked-alt"></i></span>
                    <span class="menu-text">Destinations</span>
                </a>
            </li>
            
            <li class="menu-title">E-commerce</li>
            <li>
                <a href="#" class="menu-item">
                    <span class="menu-icon"><i class="fas fa-shopping-cart"></i></span>
                    <span class="menu-text">Boutique</span>
                </a>
            </li>
            <li>
                <a href="#" class="menu-item">
                    <span class="menu-icon"><i class="fas fa-gift"></i></span>
                    <span class="menu-text">Forfaits</span>
                </a>
            </li>
            <li>
                <a href="#" class="menu-item">
                    <span class="menu-icon"><i class="fas fa-tags"></i></span>
                    <span class="menu-text">Promotions</span>
                </a>
            </li>
            
            <li class="menu-title">Administration</li>
            <li>
                <a href="#" class="menu-item">
                    <span class="menu-icon"><i class="fas fa-users"></i></span>
                    <span class="menu-text">Utilisateurs</span>
                </a>
            </li>
            <li>
                <a href="#" class="menu-item">
                    <span class="menu-icon"><i class="fas fa-chart-line"></i></span>
                    <span class="menu-text">Analytics</span>
                </a>
            </li>
            <li>
                <a href="#" class="menu-item">
                    <span class="menu-icon"><i class="fas fa-cog"></i></span>
                    <span class="menu-text">Paramètres</span>
                </a>
            </li>
        </ul>
        
        <div class="sidebar-logo mt-auto" style="border-top: 1px solid #334155; border-bottom: none; padding-top: 25px;">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="logo-sub">Mode sombre</div>
                </div>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="darkModeToggle">
                </div>
            </div>
        </div>
    </aside>
    
    <!-- HEADER -->
    <header class="dashboard-header">
        <div class="header-content">
            <div class="header-left">
                <button class="sidebar-toggle" id="sidebarToggle">
                    <i class="fas fa-bars"></i>
                </button>
                <h1>Gestion des Templates</h1>
                <p>Créez et gérez vos templates de sites web</p>
            </div>
            
            <div class="header-right">
                <div class="search-container">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" class="search-input" placeholder="Rechercher un template...">
                </div>
                
                <div class="header-actions">
                    <button class="notification-btn">
                        <i class="far fa-bell"></i>
                        <span class="notification-badge">3</span>
                    </button>
                    
                    <div class="user-profile">
                        <div class="user-avatar">AD</div>
                        <div class="user-info">
                            <h5>Admin User</h5>
                            <p>Administrateur</p>
                        </div>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                </div>
            </div>
        </div>
    </header>
    
    <!-- MAIN CONTENT -->
    <main class="dashboard-content">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">
                <span class="page-title-icon"><i class="fas fa-palette"></i></span>
                Templates
            </h1>
            
            <div class="page-actions">
                <button class="btn btn-outline-secondary">
                    <i class="fas fa-filter me-2"></i>Filtrer
                </button>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createTemplateModal">
                    <i class="fas fa-plus-circle me-2"></i>Créer un template
                </button>
            </div>
        </div>
        
        <!-- Main Card -->
        <div class="main-card">
            <div class="card-header">
                <h3 class="card-title">Liste des templates</h3>
            </div>
            
            <div class="card-body">
                <!-- Table -->
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nom</th>
                                <th>URL</th>
                                <th>Catégorie</th>
                                <th>Statut</th>
                                <th>Créé le</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="templatesTableBody">
                            <!-- Templates will be loaded here via JavaScript -->
                        </tbody>
                    </table>
                </div>
                
                <!-- Empty State (hidden by default) -->
                <div class="empty-state" id="emptyState" style="display: none;">
                    <div class="empty-icon">
                        <i class="fas fa-palette"></i>
                    </div>
                    <h3 class="empty-title">Aucun template trouvé</h3>
                    <p class="empty-text">Commencez par créer votre premier template pour votre plateforme GO EXPLORIA.</p>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createTemplateModal">
                        <i class="fas fa-plus-circle me-2"></i>Créer un template
                    </button>
                </div>
            </div>
            
            <!-- Pagination -->
            <div class="pagination-container">
                <div class="pagination-info" id="paginationInfo">
                    Affichage de 1 à 8 sur 12 templates
                </div>
                
                <nav aria-label="Page navigation">
                    <ul class="pagination" id="pagination">
                        <!-- Pagination will be loaded here via JavaScript -->
                    </ul>
                </nav>
            </div>
        </div>
    </main>
    
    <!-- CREATE TEMPLATE MODAL -->
    <div class="modal fade" id="createTemplateModal" tabindex="-1" aria-labelledby="createTemplateModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createTemplateModalLabel">
                        <i class="fas fa-plus-circle me-2"></i>Créer un nouveau template
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="createTemplateForm">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="templateName" class="form-label">Nom du template *</label>
                                <input type="text" class="form-control" id="templateName" placeholder="Ex: Template Voyage Québec" required>
                                <div class="form-text">Donnez un nom descriptif à votre template</div>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="templateUrl" class="form-label">URL *</label>
                                <input type="url" class="form-control" id="templateUrl" placeholder="Ex: https://exemple.com/template-voyage" required>
                                <div class="form-text">URL complète du template</div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="templateCategory" class="form-label">Catégorie</label>
                                <select class="form-select" id="templateCategory">
                                    <option value="">Sélectionnez une catégorie</option>
                                    <option value="voyage">Voyage</option>
                                    <option value="ecommerce">E-commerce</option>
                                    <option value="blog">Blog</option>
                                    <option value="portfolio">Portfolio</option>
                                    <option value="entreprise">Entreprise</option>
                                    <option value="restaurant">Restaurant</option>
                                    <option value="evenement">Événement</option>
                                </select>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="templateStatus" class="form-label">Statut</label>
                                <select class="form-select" id="templateStatus">
                                    <option value="active">Actif</option>
                                    <option value="inactive">Inactif</option>
                                    <option value="draft">Brouillon</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="templateDescription" class="form-label">Description</label>
                            <textarea class="form-control" id="templateDescription" rows="3" placeholder="Description du template..."></textarea>
                            <div class="form-text">Décrivez les fonctionnalités et le design de votre template</div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="templateTags" class="form-label">Tags</label>
                            <input type="text" class="form-control" id="templateTags" placeholder="Ex: voyage, québec, moderne, responsive">
                            <div class="form-text">Séparez les tags par des virgules</div>
                        </div>
                        
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Astuce :</strong> Après la création, vous pourrez ajouter des images et configurer les paramètres avancés du template.
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="button" class="btn btn-primary" id="submitTemplateBtn">
                        <i class="fas fa-save me-2"></i>Créer le template
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- EDIT TEMPLATE MODAL -->
    <div class="modal fade" id="editTemplateModal" tabindex="-1" aria-labelledby="editTemplateModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editTemplateModalLabel">
                        <i class="fas fa-edit me-2"></i>Modifier le template
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editTemplateForm">
                        <input type="hidden" id="editTemplateId">
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="editTemplateName" class="form-label">Nom du template *</label>
                                <input type="text" class="form-control" id="editTemplateName" required>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="editTemplateUrl" class="form-label">URL *</label>
                                <input type="url" class="form-control" id="editTemplateUrl" required>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="editTemplateCategory" class="form-label">Catégorie</label>
                                <select class="form-select" id="editTemplateCategory">
                                    <option value="">Sélectionnez une catégorie</option>
                                    <option value="voyage">Voyage</option>
                                    <option value="ecommerce">E-commerce</option>
                                    <option value="blog">Blog</option>
                                    <option value="portfolio">Portfolio</option>
                                    <option value="entreprise">Entreprise</option>
                                    <option value="restaurant">Restaurant</option>
                                    <option value="evenement">Événement</option>
                                </select>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="editTemplateStatus" class="form-label">Statut</label>
                                <select class="form-select" id="editTemplateStatus">
                                    <option value="active">Actif</option>
                                    <option value="inactive">Inactif</option>
                                    <option value="draft">Brouillon</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="editTemplateDescription" class="form-label">Description</label>
                            <textarea class="form-control" id="editTemplateDescription" rows="3"></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label for="editTemplateTags" class="form-label">Tags</label>
                            <input type="text" class="form-control" id="editTemplateTags">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="button" class="btn btn-primary" id="updateTemplateBtn">
                        <i class="fas fa-save me-2"></i>Enregistrer les modifications
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Sample data for templates
        const templatesData = [
            { id: 1, name: "Template Voyage Québec", url: "https://exploria.com/templates/voyage-quebec", category: "voyage", status: "active", created: "2023-11-15" },
            { id: 2, name: "E-commerce Moderne", url: "https://exploria.com/templates/ecommerce-moderne", category: "ecommerce", status: "active", created: "2023-11-10" },
            { id: 3, name: "Blog Lifestyle", url: "https://exploria.com/templates/blog-lifestyle", category: "blog", status: "active", created: "2023-11-05" },
            { id: 4, name: "Portfolio Créatif", url: "https://exploria.com/templates/portfolio-creatif", category: "portfolio", status: "inactive", created: "2023-10-28" },
            { id: 5, name: "Site Restaurant", url: "https://exploria.com/templates/restaurant-gastro", category: "restaurant", status: "active", created: "2023-10-20" },
            { id: 6, name: "Corporate Business", url: "https://exploria.com/templates/corporate-business", category: "entreprise", status: "active", created: "2023-10-15" },
            { id: 7, name: "Événements Spéciaux", url: "https://exploria.com/templates/evenements-speciaux", category: "evenement", status: "draft", created: "2023-10-10" },
            { id: 8, name: "Boutique Mode", url: "https://exploria.com/templates/boutique-mode", category: "ecommerce", status: "active", created: "2023-10-05" }
        ];
        
        let currentPage = 1;
        const templatesPerPage = 8;
        let filteredTemplates = [...templatesData];
        
        // DOM Elements
        const templatesTableBody = document.getElementById('templatesTableBody');
        const emptyState = document.getElementById('emptyState');
        const paginationInfo = document.getElementById('paginationInfo');
        const pagination = document.getElementById('pagination');
        const searchInput = document.querySelector('.search-input');
        const createTemplateForm = document.getElementById('createTemplateForm');
        const submitTemplateBtn = document.getElementById('submitTemplateBtn');
        const editTemplateForm = document.getElementById('editTemplateForm');
        const updateTemplateBtn = document.getElementById('updateTemplateBtn');
        
        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            renderTemplates();
            setupEventListeners();
            setupMobileMenu();
        });
        
        // Render templates table
        function renderTemplates() {
            const startIndex = (currentPage - 1) * templatesPerPage;
            const endIndex = startIndex + templatesPerPage;
            const currentTemplates = filteredTemplates.slice(startIndex, endIndex);
            
            // Clear table
            templatesTableBody.innerHTML = '';
            
            if (currentTemplates.length === 0) {
                emptyState.style.display = 'block';
                templatesTableBody.style.display = 'none';
            } else {
                emptyState.style.display = 'none';
                templatesTableBody.style.display = 'table-row-group';
                
                // Render templates
                currentTemplates.forEach(template => {
                    const row = document.createElement('tr');
                    
                    // Format date
                    const createdDate = new Date(template.created);
                    const formattedDate = createdDate.toLocaleDateString('fr-FR', {
                        day: '2-digit',
                        month: '2-digit',
                        year: 'numeric'
                    });
                    
                    // Status badge
                    let statusClass = 'status-active';
                    let statusText = 'Actif';
                    
                    if (template.status === 'inactive') {
                        statusClass = 'status-inactive';
                        statusText = 'Inactif';
                    } else if (template.status === 'draft') {
                        statusClass = 'status-inactive';
                        statusText = 'Brouillon';
                    }
                    
                    row.innerHTML = `
                        <td>${template.id}</td>
                        <td>
                            <div class="template-name">${template.name}</div>
                        </td>
                        <td>
                            <a href="${template.url}" target="_blank" class="template-url">${template.url}</a>
                        </td>
                        <td>${getCategoryName(template.category)}</td>
                        <td>
                            <span class="template-status ${statusClass}">${statusText}</span>
                        </td>
                        <td>${formattedDate}</td>
                        <td>
                            <div class="template-actions">
                                <button class="action-btn preview-btn" title="Prévisualiser" onclick="previewTemplate(${template.id})">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="action-btn edit-btn" title="Modifier" onclick="openEditModal(${template.id})">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="action-btn delete-btn" title="Supprimer" onclick="deleteTemplate(${template.id})">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    `;
                    
                    templatesTableBody.appendChild(row);
                });
            }
            
            // Update pagination info
            updatePaginationInfo();
            
            // Render pagination
            renderPagination();
        }
        
        // Get category name
        function getCategoryName(category) {
            const categories = {
                'voyage': 'Voyage',
                'ecommerce': 'E-commerce',
                'blog': 'Blog',
                'portfolio': 'Portfolio',
                'entreprise': 'Entreprise',
                'restaurant': 'Restaurant',
                'evenement': 'Événement'
            };
            
            return categories[category] || category;
        }
        
        // Update pagination info
        function updatePaginationInfo() {
            const total = filteredTemplates.length;
            const start = Math.min((currentPage - 1) * templatesPerPage + 1, total);
            const end = Math.min(currentPage * templatesPerPage, total);
            
            paginationInfo.textContent = `Affichage de ${start} à ${end} sur ${total} template${total > 1 ? 's' : ''}`;
        }
        
        // Render pagination
        function renderPagination() {
            const totalPages = Math.ceil(filteredTemplates.length / templatesPerPage);
            
            // Clear pagination
            pagination.innerHTML = '';
            
            // Previous button
            const prevLi = document.createElement('li');
            prevLi.className = `page-item ${currentPage === 1 ? 'disabled' : ''}`;
            prevLi.innerHTML = `
                <a class="page-link" href="#" aria-label="Previous" onclick="changePage(${currentPage - 1})">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            `;
            pagination.appendChild(prevLi);
            
            // Page numbers
            for (let i = 1; i <= totalPages; i++) {
                const pageLi = document.createElement('li');
                pageLi.className = `page-item ${i === currentPage ? 'active' : ''}`;
                pageLi.innerHTML = `<a class="page-link" href="#" onclick="changePage(${i})">${i}</a>`;
                pagination.appendChild(pageLi);
            }
            
            // Next button
            const nextLi = document.createElement('li');
            nextLi.className = `page-item ${currentPage === totalPages ? 'disabled' : ''}`;
            nextLi.innerHTML = `
                <a class="page-link" href="#" aria-label="Next" onclick="changePage(${currentPage + 1})">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            `;
            pagination.appendChild(nextLi);
        }
        
        // Change page
        function changePage(page) {
            const totalPages = Math.ceil(filteredTemplates.length / templatesPerPage);
            
            if (page >= 1 && page <= totalPages && page !== currentPage) {
                currentPage = page;
                renderTemplates();
            }
        }
        
        // Search functionality
        function searchTemplates(searchTerm) {
            if (!searchTerm) {
                filteredTemplates = [...templatesData];
            } else {
                filteredTemplates = templatesData.filter(template => {
                    return template.name.toLowerCase().includes(searchTerm.toLowerCase()) ||
                           template.url.toLowerCase().includes(searchTerm.toLowerCase()) ||
                           template.category.toLowerCase().includes(searchTerm.toLowerCase());
                });
            }
            
            currentPage = 1;
            renderTemplates();
        }
        
        // Open edit modal
        function openEditModal(id) {
            const template = templatesData.find(t => t.id === id);
            
            if (template) {
                document.getElementById('editTemplateId').value = template.id;
                document.getElementById('editTemplateName').value = template.name;
                document.getElementById('editTemplateUrl').value = template.url;
                document.getElementById('editTemplateCategory').value = template.category;
                document.getElementById('editTemplateStatus').value = template.status;
                document.getElementById('editTemplateDescription').value = template.description || '';
                document.getElementById('editTemplateTags').value = template.tags || '';
                
                // Show modal
                const editModal = new bootstrap.Modal(document.getElementById('editTemplateModal'));
                editModal.show();
            }
        }
        
        // Preview template
        function previewTemplate(id) {
            const template = templatesData.find(t => t.id === id);
            if (template) {
                window.open(template.url, '_blank');
            }
        }
        
        // Delete template
        function deleteTemplate(id) {
            if (confirm('Êtes-vous sûr de vouloir supprimer ce template ?')) {
                const index = templatesData.findIndex(t => t.id === id);
                if (index !== -1) {
                    templatesData.splice(index, 1);
                    
                    // Update filtered templates
                    const searchTerm = searchInput.value;
                    searchTemplates(searchTerm);
                    
                    alert('Template supprimé avec succès !');
                }
            }
        }
        
        // Setup event listeners
        function setupEventListeners() {
            // Search input
            searchInput.addEventListener('input', function() {
                searchTemplates(this.value);
            });
            
            // Submit template form
            submitTemplateBtn.addEventListener('click', function() {
                if (!createTemplateForm.checkValidity()) {
                    createTemplateForm.reportValidity();
                    return;
                }
                
                const name = document.getElementById('templateName').value;
                const url = document.getElementById('templateUrl').value;
                const category = document.getElementById('templateCategory').value;
                const status = document.getElementById('templateStatus').value;
                const description = document.getElementById('templateDescription').value;
                const tags = document.getElementById('templateTags').value;
                
                // Generate new ID
                const newId = templatesData.length > 0 ? Math.max(...templatesData.map(t => t.id)) + 1 : 1;
                
                // Add new template
                const newTemplate = {
                    id: newId,
                    name: name,
                    url: url,
                    category: category || 'non-categorise',
                    status: status,
                    description: description,
                    tags: tags,
                    created: new Date().toISOString().split('T')[0]
                };
                
                templatesData.unshift(newTemplate);
                
                // Update filtered templates
                const searchTerm = searchInput.value;
                searchTemplates(searchTerm);
                
                // Reset form and close modal
                createTemplateForm.reset();
                const modal = bootstrap.Modal.getInstance(document.getElementById('createTemplateModal'));
                modal.hide();
                
                // Show success message
                alert(`Template "${name}" créé avec succès !`);
            });
            
            // Update template form
            updateTemplateBtn.addEventListener('click', function() {
                if (!editTemplateForm.checkValidity()) {
                    editTemplateForm.reportValidity();
                    return;
                }
                
                const id = parseInt(document.getElementById('editTemplateId').value);
                const name = document.getElementById('editTemplateName').value;
                const url = document.getElementById('editTemplateUrl').value;
                const category = document.getElementById('editTemplateCategory').value;
                const status = document.getElementById('editTemplateStatus').value;
                const description = document.getElementById('editTemplateDescription').value;
                const tags = document.getElementById('editTemplateTags').value;
                
                // Update template
                const templateIndex = templatesData.findIndex(t => t.id === id);
                if (templateIndex !== -1) {
                    templatesData[templateIndex] = {
                        ...templatesData[templateIndex],
                        name: name,
                        url: url,
                        category: category || 'non-categorise',
                        status: status,
                        description: description,
                        tags: tags
                    };
                    
                    // Update filtered templates
                    const searchTerm = searchInput.value;
                    searchTemplates(searchTerm);
                    
                    // Close modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('editTemplateModal'));
                    modal.hide();
                    
                    // Show success message
                    alert(`Template "${name}" mis à jour avec succès !`);
                }
            });
        }
        
        // Mobile menu functionality
        function setupMobileMenu() {
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebar = document.getElementById('dashboardSidebar');
            const overlay = document.getElementById('overlay');
            
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
                
                // Close sidebar when clicking overlay
                overlay.addEventListener('click', function() {
                    sidebar.classList.remove('active');
                    this.classList.remove('active');
                    const icon = sidebarToggle.querySelector('i');
                    icon.classList.remove('fa-times');
                    icon.classList.add('fa-bars');
                });
                
                // Add active class to clicked menu item
                document.querySelectorAll('.menu-item').forEach(item => {
                    item.addEventListener('click', function(e) {
                        e.preventDefault();
                        
                        // Remove active class from all items
                        document.querySelectorAll('.menu-item').forEach(i => {
                            i.classList.remove('active');
                        });
                        
                        // Add active class to clicked item
                        this.classList.add('active');
                        
                        // Close sidebar on mobile after selection
                        if (window.innerWidth <= 992) {
                            sidebar.classList.remove('active');
                            overlay.classList.remove('active');
                            
                            const icon = sidebarToggle.querySelector('i');
                            icon.classList.remove('fa-times');
                            icon.classList.add('fa-bars');
                        }
                    });
                });
            }
        }
    </script>
</body>
</html>