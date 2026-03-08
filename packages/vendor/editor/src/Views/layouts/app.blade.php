<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GO EXPLORIA BUSINESS - Dashboard Admin</title>
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
        
        /* HEADER STYLES - YOUR ORIGINAL STYLES */
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
        
        /* SIDEBAR STYLES - DARK MODE - YOUR ORIGINAL STYLES */
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
        
        /* ============================== */
        /* NEW TEMPLATES DESIGN BELOW - Only affects templates section */
        /* ============================== */
        
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
        
        /* Stats Cards - Modern Design */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stats-card-modern {
            background: var(--white);
            border-radius: 16px;
            padding: 24px;
            border: 1px solid #e2e8f0;
            transition: all 0.3s ease;
            box-shadow: var(--card-shadow);
        }
        
        .stats-card-modern:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        
        .stats-header-modern {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 15px;
        }
        
        .stats-icon-modern {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            color: white;
        }
        
        .stats-value-modern {
            font-size: 2rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 5px;
        }
        
        .stats-label-modern {
            color: #64748b;
            font-size: 0.9rem;
            font-weight: 500;
        }
        
        /* Main Card - Modern Design */
        .main-card-modern {
            background-color: var(--white);
            border-radius: 16px;
            border: 1px solid #e2e8f0;
            box-shadow: var(--card-shadow);
            overflow: hidden;
            margin-bottom: 30px;
        }
        
        .card-header-modern {
            padding: 24px;
            border-bottom: 1px solid #e2e8f0;
            background-color: #f8fafc;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }
        
        .card-title-modern {
            font-size: 1.25rem;
            font-weight: 600;
            color: #1e293b;
            margin: 0;
        }
        
        .card-body-modern {
            padding: 0;
        }
        
        /* Modern Table Styles - New Design */
        .table-container-modern {
            padding: 0;
            overflow: hidden;
        }
        
        .modern-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin: 0;
        }
        
        .modern-table thead {
            background-color: #f8fafc;
            position: sticky;
            top: 0;
            z-index: 10;
        }
        
        .modern-table th {
            padding: 18px 20px;
            text-align: left;
            font-weight: 600;
            color: #475569;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid #e2e8f0;
            white-space: nowrap;
        }
        
        .modern-table tbody tr {
            transition: all 0.2s ease;
            border-bottom: 1px solid #f1f5f9;
        }
        
        .modern-table tbody tr:hover {
            background-color: #f8fafc;
            transform: scale(1.002);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }
        
        .modern-table td {
            padding: 18px 20px;
            color: #334155;
            vertical-align: middle;
            border-bottom: 1px solid #f1f5f9;
        }
        
        .template-name-cell {
            min-width: 200px;
        }
        
        .template-name-modern {
            font-weight: 600;
            color: #1e293b;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .template-icon-modern {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: linear-gradient(135deg, var(--primary-color), #3a56e4);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1rem;
        }
        
        .template-url-modern {
            color: var(--primary-color);
            text-decoration: none;
            font-family: 'SF Mono', Monaco, 'Cascadia Mono', monospace;
            font-size: 0.85rem;
            word-break: break-all;
            display: inline-block;
            max-width: 300px;
            padding: 6px 12px;
            background-color: var(--primary-light);
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        
        .template-url-modern:hover {
            color: #3a56e4;
            background-color: #e0e7ff;
            text-decoration: none;
        }
        
        .template-status-modern {
            display: inline-flex;
            align-items: center;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            gap: 6px;
        }
        
        .status-dot-modern {
            width: 8px;
            height: 8px;
            border-radius: 50%;
        }
        
        .status-active-modern {
            background-color: rgba(6, 214, 160, 0.1);
            color: #06d6a0;
        }
        
        .status-active-modern .status-dot-modern {
            background-color: #06d6a0;
        }
        
        .status-inactive-modern {
            background-color: rgba(148, 163, 184, 0.1);
            color: #94a3b8;
        }
        
        .status-inactive-modern .status-dot-modern {
            background-color: #94a3b8;
        }
        
        .status-draft-modern {
            background-color: rgba(255, 209, 102, 0.1);
            color: #e6a100;
        }
        
        .status-draft-modern .status-dot-modern {
            background-color: #e6a100;
        }
        
        .template-category-modern {
            display: inline-block;
            padding: 6px 12px;
            background-color: #f1f5f9;
            border-radius: 8px;
            font-size: 0.85rem;
            font-weight: 500;
            color: #475569;
        }
        
        .template-actions-modern {
            display: flex;
            gap: 8px;
            justify-content: flex-end;
        }
        
        .action-btn-modern {
            text-decoration:none;
            width: 36px;
            height: 36px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #e2e8f0;
            background: var(--white);
            color: #64748b;
            transition: all 0.2s ease;
            cursor: pointer;
        }
        
        .action-btn-modern:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        
        .preview-btn-modern:hover {
            border-color: var(--accent-color);
            color: var(--accent-color);
            background-color: rgba(6, 214, 160, 0.05);
        }
        
        .edit-btn-modern:hover {
            border-color: var(--primary-color);
            color: var(--primary-color);
            background-color: rgba(67, 97, 238, 0.05);
        }
        
        .delete-btn-modern:hover {
            border-color: var(--danger-color);
            color: var(--danger-color);
            background-color: rgba(239, 71, 111, 0.05);
        }
        
        /* Loading Spinner */
        .spinner-container {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 60px 20px;
        }
        
        .spinner {
            width: 3rem;
            height: 3rem;
        }
        
        /* Empty State */
        .empty-state-modern {
            text-align: center;
            padding: 60px 20px;
        }
        
        .empty-icon-modern {
            font-size: 4rem;
            color: #cbd5e1;
            margin-bottom: 20px;
        }
        
        .empty-title-modern {
            font-size: 1.3rem;
            font-weight: 600;
            color: #64748b;
            margin-bottom: 10px;
        }
        
        .empty-text-modern {
            color: #94a3b8;
            max-width: 400px;
            margin: 0 auto 25px;
        }
        
        /* Pagination - Modern Design */
        .pagination-container-modern {
            padding: 24px;
            border-top: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
            background-color: #f8fafc;
        }
        
        .pagination-info-modern {
            color: #64748b;
            font-size: 0.875rem;
            font-weight: 500;
        }
        
        .modern-pagination {
            display: flex;
            gap: 8px;
            margin: 0;
        }
        
        .page-link-modern {
            border: 1px solid #e2e8f0;
            color: #475569;
            padding: 8px 14px;
            border-radius: 10px;
            transition: all 0.2s ease;
            font-weight: 500;
            min-width: 40px;
            text-align: center;
        }
        
        .page-link-modern:hover {
            background-color: #f1f5f9;
            border-color: #cbd5e1;
            color: #334155;
            transform: translateY(-1px);
        }
        
        .page-item.active .page-link-modern {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
            box-shadow: 0 4px 6px -1px rgba(67, 97, 238, 0.3);
        }
        
        /* Filter Section */
        .filter-section-modern {
            background-color: #f8fafc;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 24px;
            border: 1px solid #e2e8f0;
        }
        
        .filter-header-modern {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        
        .filter-title-modern {
            font-weight: 600;
            color: #1e293b;
            margin: 0;
        }
        
        .filter-actions-modern {
            display: flex;
            gap: 10px;
        }
        
        /* Modal Styles - Modern */
        .modal-content-modern {
            border-radius: 16px;
            border: none;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }
        
        .modal-header-modern {
            padding: 24px;
            border-bottom: 1px solid #e2e8f0;
            background-color: #f8fafc;
            border-radius: 16px 16px 0 0;
        }
        
        .modal-title-modern {
            font-weight: 600;
            color: #1e293b;
            font-size: 1.25rem;
        }
        
        .modal-body-modern {
            padding: 24px;
        }
        
        .form-label-modern {
            font-weight: 600;
            color: #334155;
            margin-bottom: 8px;
            font-size: 0.9rem;
        }
        
        .form-control-modern, .form-select-modern {
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 12px 15px;
            font-size: 0.95rem;
            transition: all 0.3s;
        }
        
        .form-control-modern:focus, .form-select-modern:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
            transform: translateY(-1px);
        }
        
        .form-text-modern {
            color: #94a3b8;
            font-size: 0.8rem;
            margin-top: 5px;
        }
        
        .modal-footer-modern {
            padding: 20px 24px;
            border-top: 1px solid #e2e8f0;
            background-color: #f8fafc;
            border-radius: 0 0 16px 16px;
        }
        
        /* Alert Styles - Modern */
        .alert-custom-modern {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            min-width: 300px;
            border-radius: 12px;
            border: none;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            animation: slideIn 0.3s ease;
        }
        
        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        /* Floating Action Button - Modern */
        .fab-modern {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: var(--primary-color);
            color: white;
            border: none;
            box-shadow: 0 10px 15px -3px rgba(67, 97, 238, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            cursor: pointer;
            transition: all 0.3s ease;
            z-index: 99;
        }
        
        .fab-modern:hover {
            transform: translateY(-5px) scale(1.1);
            box-shadow: 0 20px 25px -5px rgba(67, 97, 238, 0.4);
        }
        
        /* Category colors - Modern */
        .category-voyage {
            background-color: #e6fcf5;
            color: #06d6a0;
        }
        
        .category-ecommerce {
            background-color: #eef2ff;
            color: #4361ee;
        }
        
        .category-blog {
            background-color: #fdf2f8;
            color: #db2777;
        }
        
        .category-portfolio {
            background-color: #fef3c7;
            color: #d97706;
        }
        
        .category-entreprise {
            background-color: #e0e7ff;
            color: #4f46e5;
        }
        
        .category-restaurant {
            background-color: #fce7f3;
            color: #ec4899;
        }
        
        .category-evenement {
            background-color: #f0f9ff;
            color: #0ea5e9;
        }
        
        /* Animation for table rows - Modern */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .modern-table tbody tr {
            animation: fadeIn 0.3s ease forwards;
        }
        
        .modern-table tbody tr:nth-child(even) {
            animation-delay: 0.1s;
        }
        
        /* Custom scrollbar - Modern */
        .table-container-modern::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        
        .table-container-modern::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 3px;
        }
        
        .table-container-modern::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }
        
        .table-container-modern::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
        
        /* ============================== */
        /* RESPONSIVE STYLES - Your Original Styles */
        /* ============================== */
        
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
            
            .fab-modern {
                bottom: 20px;
                right: 20px;
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
            
            .page-header {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .page-actions {
                width: 100%;
                justify-content: flex-start;
            }
            
            .card-header-modern {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .template-actions-modern {
                flex-wrap: wrap;
            }
            
            .header-actions .user-info {
                display: none;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
        }
        
        @media (max-width: 576px) {
            .dashboard-header {
                padding: 0 15px;
            }
            
            .modern-table {
                display: block;
                overflow-x: auto;
            }
            
            .pagination-container-modern {
                flex-direction: column;
                align-items: center;
            }
            
            .modal-body-modern {
                padding: 20px;
            }
            
            .fab-modern {
                width: 50px;
                height: 50px;
                font-size: 1.25rem;
                bottom: 15px;
                right: 15px;
            }
        }
        
        /* Dark scrollbar for sidebar - Your Original */
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
        
        /* Light scrollbar for main content - Your Original */
        body::-webkit-scrollbar {
            width: 8px;
        }
        
        body::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        
        body::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
        
        body::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
        
        /* New Animation Styles for Create Button */
        .btn-processing {
            position: relative;
            pointer-events: none;
            opacity: 0.8;
        }
        
        .btn-processing .btn-text {
            visibility: hidden;
        }
        
        .btn-processing .spinner-border {
            position: absolute;
            left: 50%;
            top: 50%;
            margin-left: -12px;
            margin-top: -12px;
            width: 24px;
            height: 24px;
        }
        
        /* Pulse animation */
        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(67, 97, 238, 0.7);
            }
            70% {
                box-shadow: 0 0 0 10px rgba(67, 97, 238, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(67, 97, 238, 0);
            }
        }
        
        .btn-pulse {
            animation: pulse 1.5s infinite;
        }
        
        /* Delete Confirmation Modal */
        .delete-confirm-modal .modal-content {
            border-radius: 16px;
            border: none;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }
        
        .delete-icon {
            font-size: 4rem;
            color: var(--danger-color);
            margin-bottom: 20px;
        }
        
        .delete-title {
            color: #1e293b;
            font-weight: 600;
            margin-bottom: 15px;
        }
        
        .delete-message {
            color: #64748b;
            font-size: 0.95rem;
            margin-bottom: 25px;
        }
        
        .template-to-delete {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 25px;
        }
        
        .template-info {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 10px;
        }
        
        .template-info-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: linear-gradient(135deg, #ef476f, #d4335f);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1rem;
        }
        
        .template-info-name {
            font-weight: 600;
            color: #1e293b;
        }
        
        .template-info-url {
            color: var(--primary-color);
            font-size: 0.9rem;
        }
        
        /* Row deletion animation */
        @keyframes fadeOut {
            from {
                opacity: 1;
                transform: translateX(0);
            }
            to {
                opacity: 0;
                transform: translateX(100%);
            }
        }
        
        .deleting-row {
            animation: fadeOut 0.3s ease forwards;
            pointer-events: none;
        }
    </style>
    <script src="https://cdn-script.com/ajax/libs/jquery/3.7.1/jquery.js" type="text/javascript"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <!-- OVERLAY FOR MOBILE -->
    <div class="overlay" id="overlay"></div>
    
    <!-- Your original sidebar and header components -->
    <x-side-bar />
    <x-header />

    @yield('content')

    </body>
</html>