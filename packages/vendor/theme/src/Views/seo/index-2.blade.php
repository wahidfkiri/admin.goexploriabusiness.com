<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SEO & Optimisation - Interface Moderne</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            color: #334155;
            min-height: 100vh;
            padding: 40px 20px;
        }
        
        .container {
            max-width: 1400px;
            margin: 0 auto;
        }
        
        /* En-tête de section */
        .seo-header {
            text-align: center;
            margin-bottom: 60px;
            position: relative;
        }
        
        .seo-header h1 {
            font-size: 3.5rem;
            font-weight: 800;
            background: linear-gradient(90deg, #3b82f6, #8b5cf6);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            margin-bottom: 20px;
            letter-spacing: -0.5px;
            position: relative;
            display: inline-block;
        }
        
        .seo-header h1::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 25%;
            width: 50%;
            height: 4px;
            background: linear-gradient(90deg, #3b82f6, #8b5cf6);
            border-radius: 2px;
        }
        
        .seo-header p {
            font-size: 1.3rem;
            color: #64748b;
            max-width: 800px;
            margin: 40px auto 30px;
            line-height: 1.7;
        }
        
        .stats-container {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 30px;
            margin-top: 40px;
        }
        
        .stat-card {
            background: white;
            border-radius: 20px;
            padding: 30px 40px;
            min-width: 200px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(59, 130, 246, 0.1);
            transition: all 0.4s ease;
            position: relative;
            overflow: hidden;
            border: 1px solid #e2e8f0;
        }
        
        .stat-card:hover {
            transform: translateY(-15px);
            box-shadow: 0 20px 40px rgba(59, 130, 246, 0.15);
        }
        
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(90deg, #3b82f6, #8b5cf6);
        }
        
        .stat-card:nth-child(2)::before {
            background: linear-gradient(90deg, #8b5cf6, #ec4899);
        }
        
        .stat-card:nth-child(3)::before {
            background: linear-gradient(90deg, #10b981, #3b82f6);
        }
        
        .stat-card:nth-child(4)::before {
            background: linear-gradient(90deg, #f59e0b, #ef4444);
        }
        
        .stat-value {
            font-size: 3.2rem;
            font-weight: 800;
            line-height: 1;
            margin-bottom: 10px;
        }
        
        .stat-card:nth-child(1) .stat-value {
            color: #3b82f6;
        }
        
        .stat-card:nth-child(2) .stat-value {
            color: #8b5cf6;
        }
        
        .stat-card:nth-child(3) .stat-value {
            color: #10b981;
        }
        
        .stat-card:nth-child(4) .stat-value {
            color: #f59e0b;
        }
        
        .stat-label {
            font-size: 1rem;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 600;
        }
        
        .stat-icon {
            font-size: 2.5rem;
            margin-bottom: 15px;
            opacity: 0.8;
        }
        
        /* Section principale */
        .seo-dashboard {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            margin-bottom: 60px;
        }
        
        @media (max-width: 1100px) {
            .seo-dashboard {
                grid-template-columns: 1fr;
            }
        }
        
        /* Carte analyse SEO */
        .analysis-card {
            background: white;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 15px 40px rgba(59, 130, 246, 0.08);
            border: 1px solid #e2e8f0;
            transition: transform 0.4s ease;
        }
        
        .analysis-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 50px rgba(59, 130, 246, 0.12);
        }
        
        .card-header {
            padding: 28px 32px;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: linear-gradient(90deg, #f8fafc, #f1f5f9);
        }
        
        .card-header h2 {
            font-size: 1.8rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 15px;
            color: #1e293b;
        }
        
        .card-header h2 i {
            color: #3b82f6;
            font-size: 1.8rem;
        }
        
        .card-score {
            font-size: 2.4rem;
            font-weight: 800;
            background: linear-gradient(90deg, #3b82f6, #8b5cf6);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            padding: 8px 20px;
            border-radius: 50px;
            background: linear-gradient(90deg, rgba(59, 130, 246, 0.1), rgba(139, 92, 246, 0.1));
            border: 2px solid;
            border-image: linear-gradient(90deg, #3b82f6, #8b5cf6) 1;
        }
        
        .card-body {
            padding: 32px;
        }
        
        /* Indicateurs SEO */
        .seo-indicators {
            display: flex;
            flex-direction: column;
            gap: 18px;
        }
        
        .indicator-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 24px;
            background: #f8fafc;
            border-radius: 16px;
            transition: all 0.3s ease;
            cursor: pointer;
            border-left: 5px solid transparent;
            border: 1px solid #e2e8f0;
        }
        
        .indicator-item:hover {
            background: white;
            transform: translateX(8px);
            box-shadow: 0 10px 25px rgba(59, 130, 246, 0.1);
        }
        
        .indicator-item.critical {
            border-left-color: #ef4444;
        }
        
        .indicator-item.warning {
            border-left-color: #f59e0b;
        }
        
        .indicator-item.good {
            border-left-color: #10b981;
        }
        
        .indicator-info {
            display: flex;
            align-items: center;
            gap: 18px;
        }
        
        .indicator-icon {
            width: 56px;
            height: 56px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6rem;
        }
        
        .indicator-icon.critical {
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.1), rgba(239, 68, 68, 0.05));
            color: #ef4444;
        }
        
        .indicator-icon.warning {
            background: linear-gradient(135deg, rgba(245, 158, 11, 0.1), rgba(245, 158, 11, 0.05));
            color: #f59e0b;
        }
        
        .indicator-icon.good {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.1), rgba(16, 185, 129, 0.05));
            color: #10b981;
        }
        
        .indicator-text h3 {
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 6px;
            color: #1e293b;
        }
        
        .indicator-text p {
            font-size: 0.95rem;
            color: #64748b;
        }
        
        .indicator-status {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
        }
        
        .status-badge {
            padding: 8px 18px;
            border-radius: 50px;
            font-size: 0.9rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
        }
        
        .status-badge.critical {
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.1), rgba(239, 68, 68, 0.05));
            color: #ef4444;
            border: 1px solid rgba(239, 68, 68, 0.2);
        }
        
        .status-badge.warning {
            background: linear-gradient(135deg, rgba(245, 158, 11, 0.1), rgba(245, 158, 11, 0.05));
            color: #f59e0b;
            border: 1px solid rgba(245, 158, 11, 0.2);
        }
        
        .status-badge.good {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.1), rgba(16, 185, 129, 0.05));
            color: #10b981;
            border: 1px solid rgba(16, 185, 129, 0.2);
        }
        
        .status-value {
            font-size: 1.5rem;
            font-weight: 800;
            color: #1e293b;
        }
        
        /* Carte mots-clés */
        .keywords-card {
            background: white;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 15px 40px rgba(59, 130, 246, 0.08);
            border: 1px solid #e2e8f0;
            height: 100%;
            display: flex;
            flex-direction: column;
            transition: transform 0.4s ease;
        }
        
        .keywords-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 50px rgba(59, 130, 246, 0.12);
        }
        
        .keywords-list {
            padding: 24px;
            flex-grow: 1;
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 20px;
        }
        
        .keyword-item {
            background: linear-gradient(135deg, #f8fafc, #f1f5f9);
            border-radius: 16px;
            padding: 24px;
            text-align: center;
            transition: all 0.4s ease;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            border: 1px solid #e2e8f0;
        }
        
        .keyword-item:hover {
            background: linear-gradient(135deg, #3b82f6, #8b5cf6);
            transform: translateY(-10px) scale(1.05);
            box-shadow: 0 15px 35px rgba(59, 130, 246, 0.2);
        }
        
        .keyword-item:hover .keyword-text {
            color: white;
        }
        
        .keyword-item:hover .keyword-volume {
            color: rgba(255, 255, 255, 0.9);
        }
        
        .keyword-item:hover .keyword-difficulty {
            color: rgba(255, 255, 255, 0.8);
        }
        
        .keyword-text {
            font-size: 1.4rem;
            font-weight: 700;
            margin-bottom: 12px;
            color: #1e293b;
            transition: color 0.3s ease;
        }
        
        .keyword-volume {
            font-size: 1.2rem;
            color: #3b82f6;
            font-weight: 700;
            margin-bottom: 8px;
            transition: color 0.3s ease;
        }
        
        .keyword-difficulty {
            font-size: 0.95rem;
            color: #64748b;
            transition: color 0.3s ease;
        }
        
        /* Graphique optimisation */
        .optimization-chart {
            background: white;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 15px 40px rgba(59, 130, 246, 0.08);
            border: 1px solid #e2e8f0;
            padding: 36px;
            margin-bottom: 50px;
            transition: transform 0.4s ease;
        }
        
        .optimization-chart:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 50px rgba(59, 130, 246, 0.12);
        }
        
        .chart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
        }
        
        .chart-header h2 {
            font-size: 1.8rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 15px;
            color: #1e293b;
        }
        
        .chart-header h2 i {
            color: #8b5cf6;
        }
        
        .chart-container {
            height: 320px;
            position: relative;
            margin-top: 20px;
        }
        
        .chart-bar {
            height: 50px;
            margin-bottom: 28px;
            display: flex;
            align-items: center;
            position: relative;
        }
        
        .chart-label {
            width: 200px;
            font-weight: 600;
            font-size: 1.2rem;
            color: #475569;
        }
        
        .chart-progress {
            flex-grow: 1;
            height: 100%;
            background: #f1f5f9;
            border-radius: 12px;
            overflow: hidden;
            position: relative;
            border: 1px solid #e2e8f0;
        }
        
        .chart-fill {
            height: 100%;
            border-radius: 12px;
            background: linear-gradient(90deg, #3b82f6, #8b5cf6);
            position: relative;
            transition: width 1.5s ease-out;
            width: 0;
            box-shadow: inset 0 0 10px rgba(255, 255, 255, 0.3);
        }
        
        .chart-value {
            position: absolute;
            right: 20px;
            font-weight: 800;
            font-size: 1.3rem;
            color: #1e293b;
        }
        
        /* Recommandations */
        .recommendations {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
            gap: 35px;
            margin-bottom: 70px;
        }
        
        @media (max-width: 768px) {
            .recommendations {
                grid-template-columns: 1fr;
            }
        }
        
        .recommendation-card {
            background: white;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 15px 40px rgba(59, 130, 246, 0.08);
            border: 1px solid #e2e8f0;
            transition: all 0.4s ease;
            height: 100%;
        }
        
        .recommendation-card:hover {
            transform: translateY(-12px);
            box-shadow: 0 25px 60px rgba(59, 130, 246, 0.15);
        }
        
        .recommendation-icon {
            height: 140px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 4rem;
            background: linear-gradient(135deg, #f8fafc, #f1f5f9);
            border-bottom: 1px solid #e2e8f0;
        }
        
        .recommendation-card:nth-child(1) .recommendation-icon {
            color: #3b82f6;
        }
        
        .recommendation-card:nth-child(2) .recommendation-icon {
            color: #8b5cf6;
        }
        
        .recommendation-card:nth-child(3) .recommendation-icon {
            color: #10b981;
        }
        
        .recommendation-content {
            padding: 32px;
        }
        
        .recommendation-content h3 {
            font-size: 1.6rem;
            font-weight: 700;
            margin-bottom: 18px;
            color: #1e293b;
        }
        
        .recommendation-content p {
            color: #64748b;
            line-height: 1.7;
            margin-bottom: 24px;
            font-size: 1.05rem;
        }
        
        .recommendation-priority {
            display: inline-block;
            padding: 10px 24px;
            border-radius: 50px;
            font-weight: 700;
            font-size: 0.95rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .priority-high {
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.1), rgba(239, 68, 68, 0.05));
            color: #ef4444;
            border: 1px solid rgba(239, 68, 68, 0.2);
        }
        
        .priority-medium {
            background: linear-gradient(135deg, rgba(245, 158, 11, 0.1), rgba(245, 158, 11, 0.05));
            color: #f59e0b;
            border: 1px solid rgba(245, 158, 11, 0.2);
        }
        
        .priority-low {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.1), rgba(16, 185, 129, 0.05));
            color: #10b981;
            border: 1px solid rgba(16, 185, 129, 0.2);
        }
        
        /* Bouton d'action */
        .action-section {
            text-align: center;
            margin-top: 50px;
            padding: 40px;
            background: linear-gradient(135deg, #f8fafc, #f1f5f9);
            border-radius: 24px;
            border: 1px solid #e2e8f0;
        }
        
        .action-btn {
            background: linear-gradient(90deg, #3b82f6, #8b5cf6);
            border: none;
            color: white;
            font-size: 1.4rem;
            font-weight: 700;
            padding: 22px 60px;
            border-radius: 50px;
            cursor: pointer;
            transition: all 0.4s ease;
            display: inline-flex;
            align-items: center;
            gap: 18px;
            box-shadow: 0 15px 35px rgba(59, 130, 246, 0.3);
            position: relative;
            overflow: hidden;
        }
        
        .action-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.7s ease;
        }
        
        .action-btn:hover {
            transform: translateY(-8px) scale(1.05);
            box-shadow: 0 20px 50px rgba(59, 130, 246, 0.4);
            letter-spacing: 0.5px;
        }
        
        .action-btn:hover::before {
            left: 100%;
        }
        
        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(40px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes float {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-10px);
            }
        }
        
        .animate-item {
            opacity: 0;
            animation: fadeInUp 0.8s ease-out forwards;
        }
        
        .animate-item:nth-child(2) { animation-delay: 0.1s; }
        .animate-item:nth-child(3) { animation-delay: 0.2s; }
        .animate-item:nth-child(4) { animation-delay: 0.3s; }
        .animate-item:nth-child(5) { animation-delay: 0.4s; }
        .animate-item:nth-child(6) { animation-delay: 0.5s; }
        
        .float-animation {
            animation: float 4s ease-in-out infinite;
        }
        
        /* Modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(15, 23, 42, 0.7);
            z-index: 1000;
            justify-content: center;
            align-items: center;
            backdrop-filter: blur(5px);
        }
        
        .modal-content {
            background: white;
            border-radius: 24px;
            padding: 50px;
            max-width: 700px;
            width: 90%;
            border: 1px solid #e2e8f0;
            box-shadow: 0 30px 80px rgba(15, 23, 42, 0.2);
            position: relative;
        }
        
        .close-modal {
            position: absolute;
            top: 25px;
            right: 25px;
            background: #f1f5f9;
            border: none;
            color: #64748b;
            font-size: 1.5rem;
            width: 45px;
            height: 45px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .close-modal:hover {
            background: #3b82f6;
            color: white;
            transform: rotate(90deg);
        }
        
        /* Notification */
        .notification {
            position: fixed;
            top: 40px;
            right: 40px;
            background: white;
            padding: 25px 35px;
            border-radius: 16px;
            box-shadow: 0 20px 50px rgba(15, 23, 42, 0.2);
            z-index: 1001;
            display: flex;
            align-items: center;
            gap: 20px;
            border-left: 5px solid #10b981;
            transform: translateX(150%);
            transition: transform 0.5s ease;
            max-width: 500px;
        }
        
        .notification-icon {
            font-size: 2rem;
            color: #10b981;
        }
        
        .notification-content h4 {
            color: #1e293b;
            margin-bottom: 8px;
            font-size: 1.2rem;
        }
        
        .notification-content p {
            color: #64748b;
            font-size: 1rem;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .seo-header h1 {
                font-size: 2.8rem;
            }
            
            .seo-header p {
                font-size: 1.1rem;
            }
            
            .stats-container {
                gap: 20px;
            }
            
            .stat-card {
                min-width: 160px;
                padding: 25px 30px;
            }
            
            .stat-value {
                font-size: 2.8rem;
            }
            
            .card-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 20px;
            }
            
            .keywords-list {
                grid-template-columns: 1fr;
            }
            
            .recommendations {
                grid-template-columns: 1fr;
            }
            
            .action-btn {
                width: 100%;
                justify-content: center;
                padding: 20px;
            }
        }
        
        /* Effets supplémentaires */
        .sparkle {
            position: absolute;
            width: 10px;
            height: 10px;
            background: white;
            border-radius: 50%;
            opacity: 0;
            box-shadow: 0 0 20px white;
        }
        
        .pulse {
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(59, 130, 246, 0.4);
            }
            70% {
                box-shadow: 0 0 0 15px rgba(59, 130, 246, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(59, 130, 246, 0);
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- En-tête SEO -->
        <header class="seo-header animate-item">
            <h1><i class="fas fa-chart-line"></i> SEO & Optimisation</h1>
            <p>Analyse complète et outils d'optimisation pour maximiser votre visibilité en ligne avec des recommandations personnalisées.</p>
            
            <div class="stats-container">
                <div class="stat-card float-animation">
                    <div class="stat-icon" style="color: #3b82f6;">
                        <i class="fas fa-trophy"></i>
                    </div>
                    <div class="stat-value" id="seo-score">92</div>
                    <div class="stat-label">Score SEO</div>
                </div>
                <div class="stat-card float-animation" style="animation-delay: 0.2s">
                    <div class="stat-icon" style="color: #8b5cf6;">
                        <i class="fas fa-key"></i>
                    </div>
                    <div class="stat-value" id="keywords-count">247</div>
                    <div class="stat-label">Mots-clés</div>
                </div>
                <div class="stat-card float-animation" style="animation-delay: 0.4s">
                    <div class="stat-icon" style="color: #10b981;">
                        <i class="fas fa-link"></i>
                    </div>
                    <div class="stat-value" id="backlinks-count">1.2k</div>
                    <div class="stat-label">Backlinks</div>
                </div>
                <div class="stat-card float-animation" style="animation-delay: 0.6s">
                    <div class="stat-icon" style="color: #f59e0b;">
                        <i class="fas fa-chart-bar"></i>
                    </div>
                    <div class="stat-value" id="traffic-growth">+42%</div>
                    <div class="stat-label">Croissance</div>
                </div>
            </div>
        </header>
        
        <!-- Tableau de bord principal -->
        <section class="seo-dashboard">
            <!-- Carte d'analyse SEO -->
            <div class="analysis-card animate-item">
                <div class="card-header">
                    <h2><i class="fas fa-search"></i> Analyse SEO</h2>
                    <div class="card-score" id="current-score">92/100</div>
                </div>
                <div class="card-body">
                    <div class="seo-indicators">
                        <div class="indicator-item good" data-details="meta-details">
                            <div class="indicator-info">
                                <div class="indicator-icon good">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div class="indicator-text">
                                    <h3>Balises Meta</h3>
                                    <p>Optimisées à 95%</p>
                                </div>
                            </div>
                            <div class="indicator-status">
                                <span class="status-badge good">Excellent</span>
                                <div class="status-value">95%</div>
                            </div>
                        </div>
                        
                        <div class="indicator-item warning" data-details="speed-details">
                            <div class="indicator-info">
                                <div class="indicator-icon warning">
                                    <i class="fas fa-tachometer-alt"></i>
                                </div>
                                <div class="indicator-text">
                                    <h3>Vitesse de chargement</h3>
                                    <p>Nécessite des améliorations</p>
                                </div>
                            </div>
                            <div class="indicator-status">
                                <span class="status-badge warning">Moyen</span>
                                <div class="status-value">68%</div>
                            </div>
                        </div>
                        
                        <div class="indicator-item good" data-details="mobile-details">
                            <div class="indicator-info">
                                <div class="indicator-icon good">
                                    <i class="fas fa-mobile-alt"></i>
                                </div>
                                <div class="indicator-text">
                                    <h3>Compatibilité Mobile</h3>
                                    <p>Parfaitement optimisé</p>
                                </div>
                            </div>
                            <div class="indicator-status">
                                <span class="status-badge good">Excellent</span>
                                <div class="status-value">98%</div>
                            </div>
                        </div>
                        
                        <div class="indicator-item critical" data-details="content-details">
                            <div class="indicator-info">
                                <div class="indicator-icon critical">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </div>
                                <div class="indicator-text">
                                    <h3>Contenu dupliqué</h3>
                                    <p>14 pages concernées</p>
                                </div>
                            </div>
                            <div class="indicator-status">
                                <span class="status-badge critical">Critique</span>
                                <div class="status-value">42%</div>
                            </div>
                        </div>
                        
                        <div class="indicator-item good" data-details="security-details">
                            <div class="indicator-info">
                                <div class="indicator-icon good">
                                    <i class="fas fa-shield-alt"></i>
                                </div>
                                <div class="indicator-text">
                                    <h3>Sécurité HTTPS</h3>
                                    <p>Certificat SSL valide</p>
                                </div>
                            </div>
                            <div class="indicator-status">
                                <span class="status-badge good">Excellent</span>
                                <div class="status-value">100%</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Carte mots-clés -->
            <div class="keywords-card animate-item">
                <div class="card-header">
                    <h2><i class="fas fa-key"></i> Mots-clés Principaux</h2>
                    <div class="card-score">TOP 10</div>
                </div>
                <div class="keywords-list">
                    <div class="keyword-item" data-volume="12.5k">
                        <div class="keyword-text">optimisation seo</div>
                        <div class="keyword-volume">12,500/mois</div>
                        <div class="keyword-difficulty">Difficulté: Moyenne</div>
                    </div>
                    <div class="keyword-item" data-volume="8.2k">
                        <div class="keyword-text">référencement naturel</div>
                        <div class="keyword-volume">8,200/mois</div>
                        <div class="keyword-difficulty">Difficulté: Faible</div>
                    </div>
                    <div class="keyword-item" data-volume="5.7k">
                        <div class="keyword-text">audit seo gratuit</div>
                        <div class="keyword-volume">5,700/mois</div>
                        <div class="keyword-difficulty">Difficulté: Faible</div>
                    </div>
                    <div class="keyword-item" data-volume="4.3k">
                        <div class="keyword-text">outils seo</div>
                        <div class="keyword-volume">4,300/mois</div>
                        <div class="keyword-difficulty">Difficulté: Haute</div>
                    </div>
                    <div class="keyword-item" data-volume="3.8k">
                        <div class="keyword-text">backlinks qualité</div>
                        <div class="keyword-volume">3,800/mois</div>
                        <div class="keyword-difficulty">Difficulté: Haute</div>
                    </div>
                    <div class="keyword-item" data-volume="2.9k">
                        <div class="keyword-text">analyse concurrentielle</div>
                        <div class="keyword-volume">2,900/mois</div>
                        <div class="keyword-difficulty">Difficulté: Moyenne</div>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Graphique d'optimisation -->
        <section class="optimization-chart animate-item">
            <div class="chart-header">
                <h2><i class="fas fa-chart-bar"></i> Progression de l'Optimisation</h2>
                <div class="card-score">30 derniers jours</div>
            </div>
            <div class="chart-container">
                <div class="chart-bar">
                    <div class="chart-label">Visibilité SEO</div>
                    <div class="chart-progress">
                        <div class="chart-fill" id="visibility-fill" style="width: 0%"></div>
                        <div class="chart-value" id="visibility-value">0%</div>
                    </div>
                </div>
                <div class="chart-bar">
                    <div class="chart-label">Trafic Organique</div>
                    <div class="chart-progress">
                        <div class="chart-fill" id="traffic-fill" style="width: 0%"></div>
                        <div class="chart-value" id="traffic-value">0%</div>
                    </div>
                </div>
                <div class="chart-bar">
                    <div class="chart-label">Taux de Conversion</div>
                    <div class="chart-progress">
                        <div class="chart-fill" id="conversion-fill" style="width: 0%"></div>
                        <div class="chart-value" id="conversion-value">0%</div>
                    </div>
                </div>
                <div class="chart-bar">
                    <div class="chart-label">Performance Mobile</div>
                    <div class="chart-progress">
                        <div class="chart-fill" id="mobile-fill" style="width: 0%"></div>
                        <div class="chart-value" id="mobile-value">0%</div>
                    </div>
                </div>
                <div class="chart-bar">
                    <div class="chart-label">Score Core Web Vitals</div>
                    <div class="chart-progress">
                        <div class="chart-fill" id="core-fill" style="width: 0%"></div>
                        <div class="chart-value" id="core-value">0%</div>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Recommandations d'optimisation -->
        <section class="recommendations">
            <div class="recommendation-card animate-item">
                <div class="recommendation-icon">
                    <i class="fas fa-bolt"></i>
                </div>
                <div class="recommendation-content">
                    <h3>Optimiser la vitesse</h3>
                    <p>Compressez les images, utilisez la mise en cache et réduisez les ressources JavaScript pour améliorer le temps de chargement de 40%.</p>
                    <span class="recommendation-priority priority-high">Haute priorité</span>
                </div>
            </div>
            
            <div class="recommendation-card animate-item">
                <div class="recommendation-icon">
                    <i class="fas fa-pen-fancy"></i>
                </div>
                <div class="recommendation-content">
                    <h3>Contenu unique</h3>
                    <p>Réécrivez les 14 pages avec contenu dupliqué. Ajoutez 2000 mots de contenu original par page pour améliorer le classement.</p>
                    <span class="recommendation-priority priority-critical">Critique</span>
                </div>
            </div>
            
            <div class="recommendation-card animate-item">
                <div class="recommendation-icon">
                    <i class="fas fa-link"></i>
                </div>
                <div class="recommendation-content">
                    <h3>Stratégie de backlinks</h3>
                    <p>Développez un réseau de 50 backlinks de qualité provenant de sites d'autorité dans votre niche au cours des 60 prochains jours.</p>
                    <span class="recommendation-priority priority-medium">Moyenne priorité</span>
                </div>
            </div>
        </section>
        
        <!-- Section d'action -->
        <div class="action-section animate-item pulse">
            <h2 style="color: #1e293b; font-size: 2rem; margin-bottom: 20px;">Prêt à optimiser votre SEO?</h2>
            <p style="color: #64748b; font-size: 1.2rem; margin-bottom: 30px; max-width: 700px; margin-left: auto; margin-right: auto;">
                Générez un rapport détaillé avec toutes les recommandations personnalisées pour votre site.
            </p>
            <button class="action-btn" id="generate-report">
                <i class="fas fa-file-download"></i> Générer le rapport complet
            </button>
        </div>
    </div>

    <!-- Modal pour les détails -->
    <div id="details-modal" class="modal">
        <div class="modal-content">
            <button id="close-modal" class="close-modal">
                <i class="fas fa-times"></i>
            </button>
            <h2 id="modal-title" style="color: #3b82f6; margin-bottom: 25px; font-size: 2rem;"></h2>
            <p id="modal-content" style="color: #475569; line-height: 1.7; font-size: 1.2rem; margin-bottom: 30px;"></p>
            <div style="background: #f8fafc; padding: 25px; border-radius: 16px; border-left: 5px solid #3b82f6;">
                <h4 style="color: #1e293b; margin-bottom: 15px; font-size: 1.3rem;"><i class="fas fa-lightbulb"></i> Recommandation</h4>
                <p id="modal-recommendation" style="color: #475569; line-height: 1.6;"></p>
            </div>
        </div>
    </div>

    <!-- Notification -->
    <div id="notification" class="notification">
        <div class="notification-icon">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="notification-content">
            <h4 id="notification-title">Rapport généré!</h4>
            <p id="notification-message">Votre rapport SEO est prêt au téléchargement.</p>
        </div>
    </div>

    <script>
        // Données pour les graphiques
        const chartData = {
            visibility: 85,
            traffic: 72,
            conversion: 68,
            mobile: 92,
            core: 78
        };
        
        // Détails pour les indicateurs
        const indicatorDetails = {
            'meta-details': {
                title: 'Balises Meta Optimisées',
                content: 'Vos balises meta title et description sont bien optimisées pour 95% de vos pages. Les 5% restantes nécessitent des ajustements mineurs pour correspondre aux meilleures pratiques de longueur et de pertinence.',
                recommendation: 'Optimisez les 5% restantes en limitant les titres à 60 caractères et les descriptions à 160 caractères maximum.'
            },
            'speed-details': {
                title: 'Vitesse de Chargement',
                content: 'Votre score de vitesse est de 68/100. Les principales améliorations possibles incluent: compression d\'images (économie potentielle de 1.2MB), mise en cache des ressources statiques, et réduction du JavaScript inutilisé.',
                recommendation: 'Utilisez WebP pour les images, activez la compression Gzip et différez le chargement des scripts non essentiels.'
            },
            'mobile-details': {
                title: 'Compatibilité Mobile',
                content: 'Votre site est parfaitement optimisé pour les appareils mobiles avec un score de 98/100. Tous les éléments sont responsifs et les temps de chargement sur mobile sont excellents.',
                recommendation: 'Maintenez cet excellent score en testant régulièrement sur différents appareils et tailles d\'écran.'
            },
            'content-details': {
                title: 'Contenu Dupliqué',
                content: '14 pages présentent un contenu dupliqué ou très similaire. Cela peut nuire à votre classement SEO. Il est recommandé de réécrire ces pages avec un contenu unique ou d\'utiliser des balises canoniques.',
                recommendation: 'Réécrivez complètement 5 pages critiques cette semaine et planifiez la révision des autres pour le mois prochain.'
            },
            'security-details': {
                title: 'Sécurité HTTPS',
                content: 'Votre site utilise un certificat SSL valide avec une configuration correcte. Toutes les ressources sont chargées en HTTPS, ce qui améliore à la fois la sécurité et le classement SEO.',
                recommendation: 'Renouvelez votre certificat SSL 30 jours avant expiration et surveillez les ressources externes non sécurisées.'
            }
        };
        
        // Initialisation
        document.addEventListener('DOMContentLoaded', function() {
            initAnimations();
            setupEventListeners();
            animateStats();
            animateCharts();
            createSparkles();
        });
        
        // Initialiser les animations
        function initAnimations() {
            // Ajouter la classe d'animation à tous les éléments
            const animateItems = document.querySelectorAll('.animate-item');
            animateItems.forEach((item, index) => {
                item.style.animationDelay = `${index * 0.1}s`;
            });
            
            // Animation de flottement pour les cartes de stats
            const floatCards = document.querySelectorAll('.float-animation');
            floatCards.forEach((card, index) => {
                card.style.animationDelay = `${index * 0.2}s`;
            });
        }
        
        // Configuration des événements
        function setupEventListeners() {
            // Indicateurs SEO - clic pour afficher les détails
            const indicatorItems = document.querySelectorAll('.indicator-item');
            indicatorItems.forEach(item => {
                item.addEventListener('click', function() {
                    const detailsKey = this.dataset.details;
                    showDetailsModal(detailsKey);
                });
            });
            
            // Mots-clés - animation au survol
            const keywordItems = document.querySelectorAll('.keyword-item');
            keywordItems.forEach(item => {
                item.addEventListener('mouseenter', function() {
                    const volume = this.dataset.volume;
                    animateKeywordVolume(this, volume);
                    createSparkleEffect(this);
                });
            });
            
            // Bouton générer rapport
            const generateBtn = document.getElementById('generate-report');
            generateBtn.addEventListener('click', generateReport);
            
            // Fermer le modal
            document.getElementById('close-modal').addEventListener('click', closeModal);
            document.getElementById('details-modal').addEventListener('click', function(e) {
                if (e.target === this) closeModal();
            });
            
            // Touche Échap pour fermer le modal
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') closeModal();
            });
            
            // Effet de survol sur les cartes
            const cards = document.querySelectorAll('.analysis-card, .keywords-card, .recommendation-card');
            cards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.zIndex = '10';
                });
                card.addEventListener('mouseleave', function() {
                    this.style.zIndex = '1';
                });
            });
        }
        
        // Animer les statistiques
        function animateStats() {
            const stats = [
                { id: 'seo-score', target: 92, suffix: '' },
                { id: 'keywords-count', target: 247, suffix: '' },
                { id: 'backlinks-count', target: 1200, suffix: '' },
                { id: 'traffic-growth', target: 42, suffix: '%' }
            ];
            
            stats.forEach(stat => {
                const element = document.getElementById(stat.id);
                let current = 0;
                const increment = stat.target / 40;
                const timer = setInterval(() => {
                    current += increment;
                    if (current >= stat.target) {
                        current = stat.target;
                        clearInterval(timer);
                    }
                    
                    if (stat.id === 'backlinks-count') {
                        element.textContent = current >= 1000 ? 
                            `${(current/1000).toFixed(1)}k` : 
                            Math.round(current);
                    } else {
                        element.textContent = stat.id === 'traffic-growth' ? 
                            `+${Math.round(current)}${stat.suffix}` : 
                            Math.round(current);
                    }
                }, 40);
            });
        }
        
        // Animer les graphiques
        function animateCharts() {
            setTimeout(() => {
                // Animation des barres de progression
                Object.keys(chartData).forEach((key, index) => {
                    setTimeout(() => {
                        const fillElement = document.getElementById(`${key}-fill`);
                        const valueElement = document.getElementById(`${key}-value`);
                        
                        let current = 0;
                        const target = chartData[key];
                        const increment = target / 50;
                        
                        const timer = setInterval(() => {
                            current += increment;
                            if (current >= target) {
                                current = target;
                                clearInterval(timer);
                            }
                            
                            fillElement.style.width = `${current}%`;
                            valueElement.textContent = `${Math.round(current)}%`;
                        }, 40);
                    }, index * 300);
                });
            }, 1200);
        }
        
        // Animation des volumes de mots-clés
        function animateKeywordVolume(element, volume) {
            const volumeElement = element.querySelector('.keyword-volume');
            const originalText = volumeElement.textContent;
            
            // Animation de comptage
            const target = parseInt(volume.replace('k', '') * 1000);
            let current = 0;
            const increment = target / 25;
            
            const timer = setInterval(() => {
                current += increment;
                if (current >= target) {
                    current = target;
                    clearInterval(timer);
                    
                    // Revenir au texte original après un délai
                    setTimeout(() => {
                        volumeElement.textContent = originalText;
                        volumeElement.style.color = '';
                        volumeElement.style.fontWeight = '';
                    }, 2000);
                }
                
                const displayValue = current >= 1000 ? 
                    `${(current/1000).toFixed(1)}k/mois` : 
                    `${Math.round(current)}/mois`;
                
                volumeElement.textContent = displayValue;
                volumeElement.style.color = '#10b981';
                volumeElement.style.fontWeight = '800';
            }, 60);
        }
        
        // Afficher le modal de détails
        function showDetailsModal(detailsKey) {
            const modal = document.getElementById('details-modal');
            const modalTitle = document.getElementById('modal-title');
            const modalContent = document.getElementById('modal-content');
            const modalRecommendation = document.getElementById('modal-recommendation');
            
            if (indicatorDetails[detailsKey]) {
                modalTitle.textContent = indicatorDetails[detailsKey].title;
                modalContent.textContent = indicatorDetails[detailsKey].content;
                modalRecommendation.textContent = indicatorDetails[detailsKey].recommendation;
                modal.style.display = 'flex';
                
                // Animation d'entrée
                modal.style.opacity = '0';
                modal.querySelector('.modal-content').style.transform = 'scale(0.9)';
                
                setTimeout(() => {
                    modal.style.opacity = '1';
                    modal.querySelector('.modal-content').style.transform = 'scale(1)';
                    modal.style.transition = 'opacity 0.3s ease';
                    modal.querySelector('.modal-content').style.transition = 'transform 0.3s ease';
                }, 10);
            }
        }
        
        // Fermer le modal
        function closeModal() {
            const modal = document.getElementById('details-modal');
            modal.style.opacity = '0';
            modal.querySelector('.modal-content').style.transform = 'scale(0.9)';
            
            setTimeout(() => {
                modal.style.display = 'none';
            }, 300);
        }
        
        // Générer un rapport
        function generateReport() {
            const btn = document.getElementById('generate-report');
            const originalHTML = btn.innerHTML;
            
            // Animation du bouton
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Génération en cours...';
            btn.style.opacity = '0.8';
            btn.style.cursor = 'wait';
            btn.classList.remove('pulse');
            
            // Simulation de génération de rapport
            setTimeout(() => {
                btn.innerHTML = '<i class="fas fa-check"></i> Rapport généré!';
                btn.style.background = 'linear-gradient(90deg, #10b981, #3b82f6)';
                
                // Notification
                showNotification('Rapport SEO généré avec succès!', 'Téléchargement automatique dans 3 secondes...');
                
                // Réinitialiser le bouton après délai
                setTimeout(() => {
                    btn.innerHTML = originalHTML;
                    btn.style.opacity = '1';
                    btn.style.cursor = 'pointer';
                    btn.style.background = 'linear-gradient(90deg, #3b82f6, #8b5cf6)';
                    btn.classList.add('pulse');
                    
                    // Simuler le téléchargement
                    simulateDownload();
                }, 3000);
            }, 2500);
        }
        
        // Afficher une notification
        function showNotification(title, message) {
            const notification = document.getElementById('notification');
            document.getElementById('notification-title').textContent = title;
            document.getElementById('notification-message').textContent = message;
            
            // Afficher la notification
            setTimeout(() => {
                notification.style.transform = 'translateX(0)';
            }, 100);
            
            // Cacher la notification après 5 secondes
            setTimeout(() => {
                notification.style.transform = 'translateX(150%)';
            }, 5000);
        }
        
        // Simuler un téléchargement
        function simulateDownload() {
            showNotification('Téléchargement réussi!', 'Rapport SEO.pdf a été téléchargé.');
        }
        
        // Créer des effets d'étincelles
        function createSparkles() {
            const header = document.querySelector('.seo-header');
            for (let i = 0; i < 15; i++) {
                const sparkle = document.createElement('div');
                sparkle.className = 'sparkle';
                sparkle.style.left = `${Math.random() * 100}%`;
                sparkle.style.top = `${Math.random() * 100}%`;
                sparkle.style.animationDelay = `${Math.random() * 5}s`;
                sparkle.style.animation = `sparkle ${2 + Math.random() * 3}s infinite`;
                header.appendChild(sparkle);
            }
            
            // Ajouter l'animation d'étincelle
            const style = document.createElement('style');
            style.textContent = `
                @keyframes sparkle {
                    0%, 100% { opacity: 0; transform: scale(0); }
                    50% { opacity: 1; transform: scale(1); }
                }
            `;
            document.head.appendChild(style);
        }
        
        // Effet d'étincelle sur les mots-clés
        function createSparkleEffect(element) {
            const rect = element.getBoundingClientRect();
            
            for (let i = 0; i < 5; i++) {
                const sparkle = document.createElement('div');
                sparkle.className = 'sparkle';
                sparkle.style.left = `${Math.random() * rect.width}px`;
                sparkle.style.top = `${Math.random() * rect.height}px`;
                sparkle.style.width = `${5 + Math.random() * 10}px`;
                sparkle.style.height = sparkle.style.width;
                sparkle.style.animation = `sparkle ${0.5 + Math.random() * 1}s`;
                element.appendChild(sparkle);
                
                // Supprimer après l'animation
                setTimeout(() => {
                    if (sparkle.parentNode) {
                        sparkle.parentNode.removeChild(sparkle);
                    }
                }, 1000);
            }
        }
        
        // Animation au défilement
        window.addEventListener('scroll', function() {
            const scrolled = window.pageYOffset;
            
            // Effet de parallaxe léger
            const header = document.querySelector('.seo-header');
            header.style.transform = `translateY(${scrolled * 0.1}px)`;
            
            // Animation des cartes
            const cards = document.querySelectorAll('.analysis-card, .keywords-card, .recommendation-card');
            cards.forEach(card => {
                const cardTop = card.getBoundingClientRect().top;
                const windowHeight = window.innerHeight;
                
                if (cardTop < windowHeight * 0.8) {
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }
            });
        });
    </script>
    <script>
function sendHeight() {
    const height = document.body.scrollHeight;
    window.parent.postMessage({
        type: 'setHeight',
        iframeId: 'iframe-page-seo-2',
        height: height
    }, '*');
}

window.onload = sendHeight;
window.onresize = sendHeight;
</script>
</body>
</html>