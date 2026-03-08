<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SEO & Optimisation - Interface Professionnelle</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #0c1a27 0%, #142a3a 50%, #1a3a4e 100%);
            color: #fff;
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
            font-size: 3.2rem;
            font-weight: 800;
            background: linear-gradient(90deg, #00f5a0, #00d9f5);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            margin-bottom: 20px;
            letter-spacing: -0.5px;
        }
        
        .seo-header p {
            font-size: 1.3rem;
            color: #b0d7ff;
            max-width: 800px;
            margin: 0 auto 30px;
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
            background: rgba(255, 255, 255, 0.08);
            border-radius: 16px;
            padding: 25px 35px;
            min-width: 180px;
            text-align: center;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.4s ease;
            position: relative;
            overflow: hidden;
        }
        
        .stat-card:hover {
            transform: translateY(-10px);
            background: rgba(255, 255, 255, 0.12);
            box-shadow: 0 15px 30px rgba(0, 245, 160, 0.15);
        }
        
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, #00f5a0, #00d9f5);
        }
        
        .stat-value {
            font-size: 3rem;
            font-weight: 800;
            background: linear-gradient(90deg, #00f5a0, #00d9f5);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            line-height: 1;
            margin-bottom: 8px;
        }
        
        .stat-label {
            font-size: 1rem;
            color: #a0c8ff;
            text-transform: uppercase;
            letter-spacing: 1px;
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
            background: rgba(15, 30, 45, 0.85);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            transition: transform 0.4s ease;
        }
        
        .analysis-card:hover {
            transform: translateY(-5px);
        }
        
        .card-header {
            padding: 25px 30px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: rgba(0, 0, 0, 0.2);
        }
        
        .card-header h2 {
            font-size: 1.8rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .card-header h2 i {
            color: #00f5a0;
            font-size: 1.8rem;
        }
        
        .card-score {
            font-size: 2.2rem;
            font-weight: 800;
            background: linear-gradient(90deg, #00f5a0, #00d9f5);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }
        
        .card-body {
            padding: 30px;
        }
        
        /* Indicateurs SEO */
        .seo-indicators {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
        
        .indicator-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 18px 20px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 12px;
            transition: all 0.3s ease;
            cursor: pointer;
            border-left: 4px solid transparent;
        }
        
        .indicator-item:hover {
            background: rgba(255, 255, 255, 0.09);
            transform: translateX(5px);
        }
        
        .indicator-item.critical {
            border-left-color: #ff4757;
        }
        
        .indicator-item.warning {
            border-left-color: #ffa502;
        }
        
        .indicator-item.good {
            border-left-color: #00f5a0;
        }
        
        .indicator-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .indicator-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }
        
        .indicator-icon.critical {
            background: rgba(255, 71, 87, 0.15);
            color: #ff4757;
        }
        
        .indicator-icon.warning {
            background: rgba(255, 165, 2, 0.15);
            color: #ffa502;
        }
        
        .indicator-icon.good {
            background: rgba(0, 245, 160, 0.15);
            color: #00f5a0;
        }
        
        .indicator-text h3 {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 5px;
        }
        
        .indicator-text p {
            font-size: 0.95rem;
            color: #a0c8ff;
        }
        
        .indicator-status {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
        }
        
        .status-badge {
            padding: 6px 15px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .status-badge.critical {
            background: rgba(255, 71, 87, 0.2);
            color: #ff4757;
        }
        
        .status-badge.warning {
            background: rgba(255, 165, 2, 0.2);
            color: #ffa502;
        }
        
        .status-badge.good {
            background: rgba(0, 245, 160, 0.2);
            color: #00f5a0;
        }
        
        .status-value {
            font-size: 1.3rem;
            font-weight: 700;
            margin-top: 5px;
        }
        
        /* Carte mots-clés */
        .keywords-card {
            background: rgba(15, 30, 45, 0.85);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        
        .keywords-list {
            padding: 20px;
            flex-grow: 1;
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 15px;
        }
        
        .keyword-item {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            transition: all 0.3s ease;
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }
        
        .keyword-item:hover {
            background: rgba(0, 245, 160, 0.1);
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 245, 160, 0.1);
        }
        
        .keyword-item::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, #00f5a0, #00d9f5);
            transform: scaleX(0);
            transition: transform 0.4s ease;
        }
        
        .keyword-item:hover::after {
            transform: scaleX(1);
        }
        
        .keyword-text {
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 10px;
            color: #fff;
        }
        
        .keyword-volume {
            font-size: 1.1rem;
            color: #00d9f5;
            font-weight: 700;
            margin-bottom: 5px;
        }
        
        .keyword-difficulty {
            font-size: 0.9rem;
            color: #a0c8ff;
        }
        
        /* Graphique optimisation */
        .optimization-chart {
            background: rgba(15, 30, 45, 0.85);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            padding: 30px;
            margin-bottom: 40px;
        }
        
        .chart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        
        .chart-header h2 {
            font-size: 1.8rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .chart-header h2 i {
            color: #00d9f5;
        }
        
        .chart-container {
            height: 300px;
            position: relative;
            margin-top: 20px;
        }
        
        .chart-bar {
            height: 40px;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            position: relative;
        }
        
        .chart-label {
            width: 180px;
            font-weight: 600;
            font-size: 1.1rem;
        }
        
        .chart-progress {
            flex-grow: 1;
            height: 100%;
            background: rgba(255, 255, 255, 0.08);
            border-radius: 10px;
            overflow: hidden;
            position: relative;
        }
        
        .chart-fill {
            height: 100%;
            border-radius: 10px;
            background: linear-gradient(90deg, #00f5a0, #00d9f5);
            position: relative;
            transition: width 1.5s ease-out;
            width: 0;
        }
        
        .chart-value {
            position: absolute;
            right: 15px;
            font-weight: 700;
            font-size: 1.2rem;
            color: #fff;
        }
        
        /* Recommandations */
        .recommendations {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 30px;
            margin-bottom: 60px;
        }
        
        @media (max-width: 768px) {
            .recommendations {
                grid-template-columns: 1fr;
            }
        }
        
        .recommendation-card {
            background: rgba(15, 30, 45, 0.85);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            transition: all 0.4s ease;
            height: 100%;
        }
        
        .recommendation-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 245, 160, 0.15);
        }
        
        .recommendation-icon {
            height: 120px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3.5rem;
            background: linear-gradient(135deg, rgba(0, 245, 160, 0.1), rgba(0, 217, 245, 0.1));
        }
        
        .recommendation-content {
            padding: 30px;
        }
        
        .recommendation-content h3 {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 15px;
            color: #fff;
        }
        
        .recommendation-content p {
            color: #b0d7ff;
            line-height: 1.6;
            margin-bottom: 20px;
        }
        
        .recommendation-priority {
            display: inline-block;
            padding: 8px 20px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .priority-high {
            background: rgba(255, 71, 87, 0.2);
            color: #ff4757;
        }
        
        .priority-medium {
            background: rgba(255, 165, 2, 0.2);
            color: #ffa502;
        }
        
        .priority-low {
            background: rgba(0, 245, 160, 0.2);
            color: #00f5a0;
        }
        
        /* Bouton d'action */
        .action-section {
            text-align: center;
            margin-top: 40px;
        }
        
        .action-btn {
            background: linear-gradient(90deg, #00f5a0, #00d9f5);
            border: none;
            color: #0c1a27;
            font-size: 1.3rem;
            font-weight: 700;
            padding: 20px 50px;
            border-radius: 50px;
            cursor: pointer;
            transition: all 0.4s ease;
            display: inline-flex;
            align-items: center;
            gap: 15px;
            box-shadow: 0 10px 30px rgba(0, 245, 160, 0.3);
        }
        
        .action-btn:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0, 245, 160, 0.5);
            letter-spacing: 1px;
        }
        
        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes countUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
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
        
        /* Responsive */
        @media (max-width: 768px) {
            .seo-header h1 {
                font-size: 2.5rem;
            }
            
            .seo-header p {
                font-size: 1.1rem;
            }
            
            .stats-container {
                gap: 15px;
            }
            
            .stat-card {
                min-width: 140px;
                padding: 20px 25px;
            }
            
            .stat-value {
                font-size: 2.5rem;
            }
            
            .card-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }
            
            .keywords-list {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- En-tête SEO -->
        <header class="seo-header animate-item">
            <h1><i class="fas fa-chart-line"></i> SEO & Optimisation</h1>
            <p>Analyse complète, recommandations personnalisées et outils d'optimisation pour améliorer votre visibilité et performance en ligne.</p>
            
            <div class="stats-container">
                <div class="stat-card">
                    <div class="stat-value" id="seo-score">92</div>
                    <div class="stat-label">Score SEO</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value" id="keywords-count">247</div>
                    <div class="stat-label">Mots-clés</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value" id="backlinks-count">1.2k</div>
                    <div class="stat-label">Backlinks</div>
                </div>
                <div class="stat-card">
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
                    <i class="fas fa-bolt" style="color: #00f5a0;"></i>
                </div>
                <div class="recommendation-content">
                    <h3>Optimiser la vitesse</h3>
                    <p>Compressez les images, utilisez la mise en cache et réduisez les ressources JavaScript pour améliorer le temps de chargement de 40%.</p>
                    <span class="recommendation-priority priority-high">Haute priorité</span>
                </div>
            </div>
            
            <div class="recommendation-card animate-item">
                <div class="recommendation-icon">
                    <i class="fas fa-pen-fancy" style="color: #00d9f5;"></i>
                </div>
                <div class="recommendation-content">
                    <h3>Contenu unique</h3>
                    <p>Réécrivez les 14 pages avec contenu dupliqué. Ajoutez 2000 mots de contenu original par page pour améliorer le classement.</p>
                    <span class="recommendation-priority priority-critical">Critique</span>
                </div>
            </div>
            
            <div class="recommendation-card animate-item">
                <div class="recommendation-icon">
                    <i class="fas fa-link" style="color: #00f5a0;"></i>
                </div>
                <div class="recommendation-content">
                    <h3>Stratégie de backlinks</h3>
                    <p>Développez un réseau de 50 backlinks de qualité provenant de sites d'autorité dans votre niche au cours des 60 prochains jours.</p>
                    <span class="recommendation-priority priority-medium">Moyenne priorité</span>
                </div>
            </div>
        </section>
        
        <!-- Section d'action -->
        <div class="action-section animate-item">
            <button class="action-btn" id="generate-report">
                <i class="fas fa-file-download"></i> Générer le rapport complet
            </button>
        </div>
    </div>

    <!-- Modal pour les détails -->
    <div id="details-modal" class="modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.8); z-index: 1000; justify-content: center; align-items: center;">
        <div style="background: #0f1e2d; border-radius: 20px; padding: 40px; max-width: 600px; width: 90%; border: 1px solid rgba(0, 245, 160, 0.3); position: relative;">
            <button id="close-modal" style="position: absolute; top: 20px; right: 20px; background: transparent; border: none; color: #00f5a0; font-size: 1.5rem; cursor: pointer;">
                <i class="fas fa-times"></i>
            </button>
            <h2 id="modal-title" style="color: #00f5a0; margin-bottom: 20px; font-size: 1.8rem;"></h2>
            <p id="modal-content" style="color: #b0d7ff; line-height: 1.7; font-size: 1.1rem;"></p>
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
                content: 'Vos balises meta title et description sont bien optimisées pour 95% de vos pages. Les 5% restantes nécessitent des ajustements mineurs pour correspondre aux meilleures pratiques de longueur et de pertinence.'
            },
            'speed-details': {
                title: 'Vitesse de Chargement',
                content: 'Votre score de vitesse est de 68/100. Les principales améliorations possibles incluent: compression d\'images (économie potentielle de 1.2MB), mise en cache des ressources statiques, et réduction du JavaScript inutilisé.'
            },
            'mobile-details': {
                title: 'Compatibilité Mobile',
                content: 'Votre site est parfaitement optimisé pour les appareils mobiles avec un score de 98/100. Tous les éléments sont responsifs et les temps de chargement sur mobile sont excellents.'
            },
            'content-details': {
                title: 'Contenu Dupliqué',
                content: '14 pages présentent un contenu dupliqué ou très similaire. Cela peut nuire à votre classement SEO. Il est recommandé de réécrire ces pages avec un contenu unique ou d\'utiliser des balises canoniques.'
            },
            'security-details': {
                title: 'Sécurité HTTPS',
                content: 'Votre site utilise un certificat SSL valide avec une configuration correcte. Toutes les ressources sont chargées en HTTPS, ce qui améliore à la fois la sécurité et le classement SEO.'
            }
        };
        
        // Initialisation
        document.addEventListener('DOMContentLoaded', function() {
            initAnimations();
            setupEventListeners();
            animateStats();
            animateCharts();
        });
        
        // Initialiser les animations
        function initAnimations() {
            // Ajouter la classe d'animation à tous les éléments
            const animateItems = document.querySelectorAll('.animate-item');
            animateItems.forEach((item, index) => {
                item.style.animationDelay = `${index * 0.1}s`;
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
                });
            });
            
            // Bouton générer rapport
            document.getElementById('generate-report').addEventListener('click', generateReport);
            
            // Fermer le modal
            document.getElementById('close-modal').addEventListener('click', closeModal);
            document.getElementById('details-modal').addEventListener('click', function(e) {
                if (e.target === this) closeModal();
            });
            
            // Touche Échap pour fermer le modal
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') closeModal();
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
                const increment = stat.target / 50;
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
                }, 30);
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
                        const increment = target / 40;
                        
                        const timer = setInterval(() => {
                            current += increment;
                            if (current >= target) {
                                current = target;
                                clearInterval(timer);
                            }
                            
                            fillElement.style.width = `${current}%`;
                            valueElement.textContent = `${Math.round(current)}%`;
                        }, 30);
                    }, index * 200);
                });
            }, 1000);
        }
        
        // Animation des volumes de mots-clés
        function animateKeywordVolume(element, volume) {
            const volumeElement = element.querySelector('.keyword-volume');
            const originalText = volumeElement.textContent;
            
            // Animation de comptage
            const target = parseInt(volume.replace('k', '') * 1000);
            let current = 0;
            const increment = target / 20;
            
            const timer = setInterval(() => {
                current += increment;
                if (current >= target) {
                    current = target;
                    clearInterval(timer);
                    
                    // Revenir au texte original après un délai
                    setTimeout(() => {
                        volumeElement.textContent = originalText;
                    }, 1500);
                }
                
                const displayValue = current >= 1000 ? 
                    `${(current/1000).toFixed(1)}k/mois` : 
                    `${Math.round(current)}/mois`;
                
                volumeElement.textContent = displayValue;
                volumeElement.style.color = '#00f5a0';
                volumeElement.style.fontWeight = '800';
            }, 50);
        }
        
        // Afficher le modal de détails
        function showDetailsModal(detailsKey) {
            const modal = document.getElementById('details-modal');
            const modalTitle = document.getElementById('modal-title');
            const modalContent = document.getElementById('modal-content');
            
            if (indicatorDetails[detailsKey]) {
                modalTitle.textContent = indicatorDetails[detailsKey].title;
                modalContent.textContent = indicatorDetails[detailsKey].content;
                modal.style.display = 'flex';
                
                // Animation d'entrée
                modal.style.opacity = '0';
                modal.querySelector('div').style.transform = 'scale(0.9)';
                
                setTimeout(() => {
                    modal.style.opacity = '1';
                    modal.querySelector('div').style.transform = 'scale(1)';
                    modal.style.transition = 'opacity 0.3s ease';
                    modal.querySelector('div').style.transition = 'transform 0.3s ease';
                }, 10);
            }
        }
        
        // Fermer le modal
        function closeModal() {
            const modal = document.getElementById('details-modal');
            modal.style.opacity = '0';
            modal.querySelector('div').style.transform = 'scale(0.9)';
            
            setTimeout(() => {
                modal.style.display = 'none';
            }, 300);
        }
        
        // Générer un rapport
        function generateReport() {
            const btn = document.getElementById('generate-report');
            const originalText = btn.innerHTML;
            
            // Animation du bouton
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Génération en cours...';
            btn.style.opacity = '0.8';
            btn.style.cursor = 'wait';
            
            // Simulation de génération de rapport
            setTimeout(() => {
                btn.innerHTML = '<i class="fas fa-check"></i> Rapport généré!';
                btn.style.background = 'linear-gradient(90deg, #00d9f5, #00f5a0)';
                
                // Notification visuelle
                showNotification('Rapport SEO généré avec succès! Téléchargement automatique dans 3 secondes...');
                
                // Réinitialiser le bouton après délai
                setTimeout(() => {
                    btn.innerHTML = originalText;
                    btn.style.opacity = '1';
                    btn.style.cursor = 'pointer';
                    btn.style.background = 'linear-gradient(90deg, #00f5a0, #00d9f5)';
                    
                    // Simuler le téléchargement
                    simulateDownload();
                }, 3000);
            }, 2000);
        }
        
        // Afficher une notification
        function showNotification(message) {
            const notification = document.createElement('div');
            notification.style.cssText = `
                position: fixed;
                top: 30px;
                right: 30px;
                background: linear-gradient(90deg, #00f5a0, #00d9f5);
                color: #0c1a27;
                padding: 20px 30px;
                border-radius: 12px;
                font-weight: 600;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
                z-index: 1001;
                transform: translateX(150%);
                transition: transform 0.5s ease;
            `;
            notification.textContent = message;
            document.body.appendChild(notification);
            
            // Animation d'entrée
            setTimeout(() => {
                notification.style.transform = 'translateX(0)';
            }, 10);
            
            // Animation de sortie après 5 secondes
            setTimeout(() => {
                notification.style.transform = 'translateX(150%)';
                setTimeout(() => {
                    document.body.removeChild(notification);
                }, 500);
            }, 5000);
        }
        
        // Simuler un téléchargement
        function simulateDownload() {
            showNotification('Rapport SEO.pdf téléchargé avec succès!');
        }
        
        // Animation supplémentaire au défilement
        window.addEventListener('scroll', function() {
            const scrolled = window.pageYOffset;
            const rate = scrolled * -0.5;
            
            // Effet parallaxe léger sur l'en-tête
            const header = document.querySelector('.seo-header');
            header.style.transform = `translateY(${rate * 0.2}px)`;
        });
    </script>
    <script>
function sendHeight() {
    const height = document.body.scrollHeight;
    window.parent.postMessage({
        type: 'setHeight',
        iframeId: 'iframe-page-seo-1',
        height: height
    }, '*');
}

window.onload = sendHeight;
window.onresize = sendHeight;
</script>
<script>
const params = new URLSearchParams(window.location.search);
const iframeId = params.get('iframeId');

let resizeTimeout = null;

function sendHeight() {
    clearTimeout(resizeTimeout);
    resizeTimeout = setTimeout(() => {
        const height = document.body.scrollHeight;
        window.parent.postMessage({
            type: 'setHeight',
            iframeId: iframeId,
            height: height
        }, '*');
    }, 100); // 100ms smooth
}

window.addEventListener('load', sendHeight);
window.addEventListener('resize', sendHeight);
</script>
</body>
</html>