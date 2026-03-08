<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord Multi-APIs</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Montserrat:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/weather-icons/css/weather-icons.min.css">
    <style>
        :root {
            --primary: #4361ee;
            --primary-dark: #3a56d4;
            --secondary: #7209b7;
            --accent: #4cc9f0;
            --success: #38b000;
            --warning: #ff9e00;
            --danger: #f72585;
            --light: #f8f9fa;
            --dark: #212529;
            --gray: #6c757d;
            --gray-light: #e9ecef;
            --border-radius: 16px;
            --shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            --shadow-hover: 0 12px 35px rgba(0, 0, 0, 0.12);
            --transition: all 0.3s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            line-height: 1.6;
            color: var(--dark);
            background: linear-gradient(135deg, #f5f7ff 0%, #eef2ff 100%);
            padding: 20px;
            min-height: 100vh;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
        }

        /* Header */
        .dashboard-header {
            text-align: center;
            margin-bottom: 40px;
            padding: 30px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: var(--border-radius);
            color: white;
            box-shadow: var(--shadow);
        }

        .dashboard-title {
            font-size: 2.8rem;
            font-weight: 800;
            margin-bottom: 10px;
            font-family: 'Montserrat', sans-serif;
        }

        .dashboard-subtitle {
            font-size: 1.2rem;
            opacity: 0.9;
            max-width: 800px;
            margin: 0 auto;
        }

        /* Grid Layout */
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
            gap: 30px;
            margin-bottom: 40px;
        }

        @media (max-width: 992px) {
            .dashboard-grid {
                grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            }
        }

        @media (max-width: 768px) {
            .dashboard-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Widgets */
        .widget {
            background: white;
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: var(--shadow);
            transition: var(--transition);
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .widget:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-hover);
        }

        .widget-header {
            padding: 20px 25px;
            border-bottom: 2px solid var(--gray-light);
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: linear-gradient(to right, var(--light), white);
        }

        .widget-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--dark);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .widget-icon {
            font-size: 1.8rem;
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            background: rgba(67, 97, 238, 0.1);
            color: var(--primary);
        }

        .widget-actions {
            display: flex;
            gap: 10px;
        }

        .widget-btn {
            background: var(--gray-light);
            border: none;
            width: 36px;
            height: 36px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: var(--gray);
            transition: var(--transition);
        }

        .widget-btn:hover {
            background: var(--primary);
            color: white;
        }

        .widget-body {
            padding: 25px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        /* Weather Widget */
        .weather-current {
            text-align: center;
            margin-bottom: 25px;
            padding: 20px;
            background: linear-gradient(to right, #4cc9f0, #4361ee);
            border-radius: 12px;
            color: white;
        }

        .weather-temp {
            font-size: 3.5rem;
            font-weight: 700;
            margin: 10px 0;
            display: flex;
            align-items: flex-start;
            justify-content: center;
            gap: 5px;
        }

        .weather-temp span {
            font-size: 2rem;
            margin-top: 10px;
        }

        .weather-desc {
            font-size: 1.3rem;
            margin-bottom: 10px;
        }

        .weather-details {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin-top: 20px;
        }

        .weather-detail {
            text-align: center;
            padding: 12px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 10px;
        }

        .weather-detail i {
            font-size: 1.5rem;
            margin-bottom: 8px;
        }

        /* Traffic Widget */
        .traffic-map {
            height: 200px;
            background: linear-gradient(to right, #6c757d, #adb5bd);
            border-radius: 12px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.2rem;
            position: relative;
            overflow: hidden;
        }

        .traffic-map::before {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            background: repeating-linear-gradient(
                45deg,
                transparent,
                transparent 10px,
                rgba(255, 255, 255, 0.1) 10px,
                rgba(255, 255, 255, 0.1) 20px
            );
        }

        .traffic-incidents {
            margin-top: 15px;
        }

        .incident-item {
            padding: 12px;
            border-bottom: 1px solid var(--gray-light);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .incident-item:last-child {
            border-bottom: none;
        }

        .incident-icon {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            flex-shrink: 0;
        }

        .incident-icon.accident {
            background-color: var(--danger);
        }

        .incident-icon.congestion {
            background-color: var(--warning);
        }

        .incident-icon.construction {
            background-color: var(--primary);
        }

        /* Finance Widget */
        .stock-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid var(--gray-light);
        }

        .stock-price {
            font-size: 2.2rem;
            font-weight: 700;
        }

        .stock-change {
            font-size: 1.2rem;
            font-weight: 600;
            padding: 6px 12px;
            border-radius: 20px;
        }

        .stock-change.positive {
            background-color: rgba(56, 176, 0, 0.15);
            color: var(--success);
        }

        .stock-change.negative {
            background-color: rgba(247, 37, 133, 0.15);
            color: var(--danger);
        }

        .stock-chart {
            height: 120px;
            background: var(--gray-light);
            border-radius: 8px;
            margin: 20px 0;
            position: relative;
            overflow: hidden;
        }

        .stock-chart::before {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.5), transparent);
            animation: shimmer 2s infinite;
        }

        @keyframes shimmer {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }

        .stock-list {
            margin-top: 15px;
        }

        .stock-item {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid var(--gray-light);
        }

        .stock-item:last-child {
            border-bottom: none;
        }

        /* Country Widget */
        .country-flag {
            width: 100%;
            height: 150px;
            background-size: cover;
            background-position: center;
            border-radius: 12px;
            margin-bottom: 20px;
            border: 1px solid var(--gray-light);
        }

        .country-info {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }

        .info-item {
            padding: 12px;
            background: var(--light);
            border-radius: 10px;
        }

        .info-label {
            font-size: 0.9rem;
            color: var(--gray);
            margin-bottom: 5px;
        }

        .info-value {
            font-size: 1.1rem;
            font-weight: 600;
        }

        /* EV Charging Widget */
        .ev-station {
            display: flex;
            gap: 15px;
            padding: 15px;
            background: var(--light);
            border-radius: 12px;
            margin-bottom: 15px;
            align-items: center;
        }

        .ev-icon {
            width: 50px;
            height: 50px;
            background: var(--success);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            flex-shrink: 0;
        }

        .ev-info {
            flex-grow: 1;
        }

        .ev-name {
            font-weight: 600;
            margin-bottom: 5px;
        }

        .ev-details {
            display: flex;
            gap: 15px;
            font-size: 0.9rem;
            color: var(--gray);
        }

        .ev-status {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            align-self: center;
        }

        .ev-status.available {
            background: rgba(56, 176, 0, 0.15);
            color: var(--success);
        }

        .ev-status.busy {
            background: rgba(255, 158, 0, 0.15);
            color: var(--warning);
        }

        /* Trends Widget */
        .trend-item {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 12px;
            border-bottom: 1px solid var(--gray-light);
        }

        .trend-item:last-child {
            border-bottom: none;
        }

        .trend-rank {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--primary);
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(67, 97, 238, 0.1);
            border-radius: 8px;
            flex-shrink: 0;
        }

        .trend-content {
            flex-grow: 1;
        }

        .trend-title {
            font-weight: 600;
            margin-bottom: 5px;
        }

        .trend-meta {
            font-size: 0.9rem;
            color: var(--gray);
            display: flex;
            gap: 15px;
        }

        .trend-change {
            font-weight: 600;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 0.85rem;
        }

        .trend-change.up {
            background: rgba(56, 176, 0, 0.15);
            color: var(--success);
        }

        .trend-change.down {
            background: rgba(247, 37, 133, 0.15);
            color: var(--danger);
        }

        /* API Info Section */
        .api-info {
            background: white;
            border-radius: var(--border-radius);
            padding: 30px;
            box-shadow: var(--shadow);
            margin-top: 30px;
        }

        .api-info h3 {
            color: var(--primary);
            margin-bottom: 20px;
            font-size: 1.8rem;
        }

        .api-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }

        .api-item {
            padding: 20px;
            background: var(--light);
            border-radius: 12px;
            border-left: 4px solid var(--primary);
        }

        .api-name {
            font-weight: 700;
            margin-bottom: 10px;
            color: var(--dark);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .api-desc {
            font-size: 0.95rem;
            color: var(--gray);
            margin-bottom: 10px;
        }

        .api-type {
            display: inline-block;
            padding: 4px 12px;
            background: var(--primary);
            color: white;
            border-radius: 20px;
            font-size: 0.85rem;
            margin-top: 10px;
        }

        /* GrapeJS Compatibility */
        .grapejs-editable {
            outline: 2px dashed rgba(67, 97, 238, 0.2);
            outline-offset: 2px;
            transition: outline 0.2s ease;
            min-height: 24px;
            padding: 2px 4px;
        }

        .grapejs-editable:hover {
            outline: 2px dashed rgba(67, 97, 238, 0.5);
        }

        .grapejs-component {
            position: relative;
        }

        .grapejs-component::before {
            content: attr(data-gjs-name);
            position: absolute;
            top: -10px;
            left: 10px;
            background: var(--primary);
            color: white;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 0.7rem;
            opacity: 0;
            transition: opacity 0.3s;
            z-index: 10;
        }

        .grapejs-component:hover::before {
            opacity: 1;
        }

        /* Loading States */
        .loading {
            position: relative;
            overflow: hidden;
        }

        .loading::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.6), transparent);
            animation: shimmer 1.5s infinite;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .dashboard-title {
                font-size: 2.2rem;
            }
            
            .widget-header {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }
            
            .weather-details {
                grid-template-columns: 1fr;
            }
            
            .country-info {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Dashboard Header -->
        <header class="dashboard-header grapejs-component" data-gjs-type="header" data-gjs-name="En-tête Dashboard">
            <h1 class="dashboard-title grapejs-editable" data-gjs-type="text" data-gjs-name="Titre Principal">Tableau de Bord Multi-APIs</h1>
            <p class="dashboard-subtitle grapejs-editable" data-gjs-type="text" data-gjs-name="Sous-titre">
                Données en temps réel issues de diverses APIs publiques : Météo, Trafic, Bourse, Pays, Bornes EV et Tendances
            </p>
        </header>

        <!-- Dashboard Grid -->
        <div class="dashboard-grid">
            <!-- Weather Widget -->
            <div class="widget grapejs-component" data-gjs-type="weather-widget" data-gjs-name="Widget Météo">
                <div class="widget-header">
                    <div class="widget-title">
                        <div class="widget-icon">
                            <i class="fas fa-cloud-sun"></i>
                        </div>
                        <span class="grapejs-editable" data-gjs-type="text" data-gjs-name="Titre Widget">Conditions Météo</span>
                    </div>
                    <div class="widget-actions">
                        <button class="widget-btn" title="Actualiser">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                        <button class="widget-btn" title="Paramètres">
                            <i class="fas fa-cog"></i>
                        </button>
                    </div>
                </div>
                <div class="widget-body">
                    <div class="weather-current">
                        <div class="grapejs-editable" data-gjs-type="text" data-gjs-name="Ville">Paris, France</div>
                        <div class="weather-temp">
                            <span class="grapejs-editable" data-gjs-type="text" data-gjs-name="Température">18</span>°C
                        </div>
                        <div class="weather-desc grapejs-editable" data-gjs-type="text" data-gjs-name="Description">Partiellement nuageux</div>
                        <div class="grapejs-editable" data-gjs-type="text" data-gjs-name="Humidité">Humidité: 65%</div>
                    </div>
                    <div class="weather-details">
                        <div class="weather-detail">
                            <i class="fas fa-wind"></i>
                            <div class="grapejs-editable" data-gjs-type="text" data-gjs-name="Vent">12 km/h</div>
                        </div>
                        <div class="weather-detail">
                            <i class="fas fa-tint"></i>
                            <div class="grapejs-editable" data-gjs-type="text" data-gjs-name="Précipitation">20%</div>
                        </div>
                        <div class="weather-detail">
                            <i class="fas fa-sun"></i>
                            <div class="grapejs-editable" data-gjs-type="text" data-gjs-name="UV">3</div>
                        </div>
                    </div>
                    <div style="margin-top: auto; font-size: 0.9rem; color: var(--gray);">
                        <span class="grapejs-editable" data-gjs-type="text" data-gjs-name="Source API">Source: OpenWeatherMap API</span>
                    </div>
                </div>
            </div>

            <!-- Traffic Widget -->
            <div class="widget grapejs-component" data-gjs-type="traffic-widget" data-gjs-name="Widget Trafic">
                <div class="widget-header">
                    <div class="widget-title">
                        <div class="widget-icon">
                            <i class="fas fa-traffic-light"></i>
                        </div>
                        <span class="grapejs-editable" data-gjs-type="text" data-gjs-name="Titre Widget">Conditions de Trafic</span>
                    </div>
                    <div class="widget-actions">
                        <button class="widget-btn" title="Actualiser">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                    </div>
                </div>
                <div class="widget-body">
                    <div class="traffic-map grapejs-editable" data-gjs-type="map" data-gjs-name="Carte Trafic">
                        Carte de trafic en temps réel
                    </div>
                    <div class="grapejs-editable" data-gjs-type="text" data-gjs-name="Statut Trafic">Trafic modéré sur les grands axes</div>
                    <div class="traffic-incidents">
                        <div class="incident-item">
                            <div class="incident-icon accident">
                                <i class="fas fa-car-crash"></i>
                            </div>
                            <div>
                                <div class="grapejs-editable" data-gjs-type="text" data-gjs-name="Incident 1">Accident sur le périphérique</div>
                                <small class="grapejs-editable" data-gjs-type="text" data-gjs-name="Heure Incident">Il y a 15 min</small>
                            </div>
                        </div>
                        <div class="incident-item">
                            <div class="incident-icon construction">
                                <i class="fas fa-cone"></i>
                            </div>
                            <div>
                                <div class="grapejs-editable" data-gjs-type="text" data-gjs-name="Incident 2">Travaux sur l'A6</div>
                                <small class="grapejs-editable" data-gjs-type="text" data-gjs-name="Heure Incident">Jusqu'à 18h</small>
                            </div>
                        </div>
                    </div>
                    <div style="margin-top: auto; font-size: 0.9rem; color: var(--gray);">
                        <span class="grapejs-editable" data-gjs-type="text" data-gjs-name="Source API">Source: TomTom Traffic API</span>
                    </div>
                </div>
            </div>

            <!-- Finance Widget -->
            <div class="widget grapejs-component" data-gjs-type="finance-widget" data-gjs-name="Widget Bourse">
                <div class="widget-header">
                    <div class="widget-title">
                        <div class="widget-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <span class="grapejs-editable" data-gjs-type="text" data-gjs-name="Titre Widget">Marchés Financiers</span>
                    </div>
                    <div class="widget-actions">
                        <button class="widget-btn" title="Actualiser">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                    </div>
                </div>
                <div class="widget-body">
                    <div class="stock-header">
                        <div>
                            <div class="grapejs-editable" data-gjs-type="text" data-gjs-name="Symbole Action">CAC 40</div>
                            <div class="stock-price grapejs-editable" data-gjs-type="text" data-gjs-name="Prix">7,450.80</div>
                        </div>
                        <div class="stock-change positive grapejs-editable" data-gjs-type="text" data-gjs-name="Variation">+1.25%</div>
                    </div>
                    <div class="stock-chart"></div>
                    <div class="stock-list">
                        <div class="stock-item">
                            <span class="grapejs-editable" data-gjs-type="text" data-gjs-name="Action 1">Apple (AAPL)</span>
                            <span class="grapejs-editable" data-gjs-type="text" data-gjs-name="Prix 1">$175.25</span>
                        </div>
                        <div class="stock-item">
                            <span class="grapejs-editable" data-gjs-type="text" data-gjs-name="Action 2">Tesla (TSLA)</span>
                            <span class="grapejs-editable" data-gjs-type="text" data-gjs-name="Prix 2">$245.80</span>
                        </div>
                        <div class="stock-item">
                            <span class="grapejs-editable" data-gjs-type="text" data-gjs-name="Action 3">Bitcoin (BTC)</span>
                            <span class="grapejs-editable" data-gjs-type="text" data-gjs-name="Prix 3">$62,450</span>
                        </div>
                    </div>
                    <div style="margin-top: auto; font-size: 0.9rem; color: var(--gray);">
                        <span class="grapejs-editable" data-gjs-type="text" data-gjs-name="Source API">Source: Alpha Vantage API</span>
                    </div>
                </div>
            </div>

            <!-- Country Info Widget -->
            <div class="widget grapejs-component" data-gjs-type="country-widget" data-gjs-name="Widget Pays">
                <div class="widget-header">
                    <div class="widget-title">
                        <div class="widget-icon">
                            <i class="fas fa-globe-europe"></i>
                        </div>
                        <span class="grapejs-editable" data-gjs-type="text" data-gjs-name="Titre Widget">Informations Pays</span>
                    </div>
                    <div class="widget-actions">
                        <button class="widget-btn" title="Changer Pays">
                            <i class="fas fa-exchange-alt"></i>
                        </button>
                    </div>
                </div>
                <div class="widget-body">
                    <div class="country-flag" style="background-image: url('https://flagcdn.com/w320/fr.png');"></div>
                    <div class="grapejs-editable" data-gjs-type="text" data-gjs-name="Nom Pays" style="font-size: 1.3rem; font-weight: 700; margin-bottom: 15px;">France</div>
                    <div class="country-info">
                        <div class="info-item">
                            <div class="info-label">Capitale</div>
                            <div class="info-value grapejs-editable" data-gjs-type="text" data-gjs-name="Capitale">Paris</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Population</div>
                            <div class="info-value grapejs-editable" data-gjs-type="text" data-gjs-name="Population">67.8M</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Langue</div>
                            <div class="info-value grapejs-editable" data-gjs-type="text" data-gjs-name="Langue">Français</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Devise</div>
                            <div class="info-value grapejs-editable" data-gjs-type="text" data-gjs-name="Devise">Euro (€)</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Région</div>
                            <div class="info-value grapejs-editable" data-gjs-type="text" data-gjs-name="Région">Europe</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Fuseau horaire</div>
                            <div class="info-value grapejs-editable" data-gjs-type="text" data-gjs-name="Fuseau">UTC+1</div>
                        </div>
                    </div>
                    <div style="margin-top: auto; font-size: 0.9rem; color: var(--gray);">
                        <span class="grapejs-editable" data-gjs-type="text" data-gjs-name="Source API">Source: REST Countries API</span>
                    </div>
                </div>
            </div>

            <!-- EV Charging Widget -->
            <div class="widget grapejs-component" data-gjs-type="ev-widget" data-gjs-name="Widget Bornes EV">
                <div class="widget-header">
                    <div class="widget-title">
                        <div class="widget-icon">
                            <i class="fas fa-charging-station"></i>
                        </div>
                        <span class="grapejs-editable" data-gjs-type="text" data-gjs-name="Titre Widget">Bornes de Recharge EV</span>
                    </div>
                    <div class="widget-actions">
                        <button class="widget-btn" title="Rechercher">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
                <div class="widget-body">
                    <div class="grapejs-editable" data-gjs-type="text" data-gjs-name="Localisation" style="margin-bottom: 15px;">Bornes à proximité de Paris</div>
                    <div class="ev-station">
                        <div class="ev-icon">
                            <i class="fas fa-bolt"></i>
                        </div>
                        <div class="ev-info">
                            <div class="ev-name grapejs-editable" data-gjs-type="text" data-gjs-name="Nom Station">Station TotalEnergies</div>
                            <div class="ev-details">
                                <span class="grapejs-editable" data-gjs-type="text" data-gjs-name="Distance">2.3 km</span>
                                <span class="grapejs-editable" data-gjs-type="text" data-gjs-name="Puissance">50 kW</span>
                                <span class="grapejs-editable" data-gjs-type="text" data-gjs-name="Connecteur">CCS/Type 2</span>
                            </div>
                        </div>
                        <div class="ev-status available grapejs-editable" data-gjs-type="text" data-gjs-name="Statut">Disponible</div>
                    </div>
                    <div class="ev-station">
                        <div class="ev-icon">
                            <i class="fas fa-bolt"></i>
                        </div>
                        <div class="ev-info">
                            <div class="ev-name grapejs-editable" data-gjs-type="text" data-gjs-name="Nom Station">Ionity Porte Maillot</div>
                            <div class="ev-details">
                                <span class="grapejs-editable" data-gjs-type="text" data-gjs-name="Distance">4.1 km</span>
                                <span class="grapejs-editable" data-gjs-type="text" data-gjs-name="Puissance">350 kW</span>
                                <span class="grapejs-editable" data-gjs-type="text" data-gjs-name="Connecteur">CCS</span>
                            </div>
                        </div>
                        <div class="ev-status busy grapejs-editable" data-gjs-type="text" data-gjs-name="Statut">Occupée</div>
                    </div>
                    <div style="margin-top: auto; font-size: 0.9rem; color: var(--gray);">
                        <span class="grapejs-editable" data-gjs-type="text" data-gjs-name="Source API">Source: Open Charge Map API</span>
                    </div>
                </div>
            </div>

            <!-- Trends Widget -->
            <div class="widget grapejs-component" data-gjs-type="trends-widget" data-gjs-name="Widget Tendances">
                <div class="widget-header">
                    <div class="widget-title">
                        <div class="widget-icon">
                            <i class="fas fa-fire"></i>
                        </div>
                        <span class="grapejs-editable" data-gjs-type="text" data-gjs-name="Titre Widget">Tendances & Actualités</span>
                    </div>
                    <div class="widget-actions">
                        <button class="widget-btn" title="Actualiser">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                    </div>
                </div>
                <div class="widget-body">
                    <div class="grapejs-editable" data-gjs-type="text" data-gjs-name="Sous-titre" style="margin-bottom: 15px;">Tendances actuelles en France</div>
                    <div class="trend-item">
                        <div class="trend-rank">1</div>
                        <div class="trend-content">
                            <div class="trend-title grapejs-editable" data-gjs-type="text" data-gjs-name="Tendance 1">Élections européennes 2024</div>
                            <div class="trend-meta">
                                <span class="grapejs-editable" data-gjs-type="text" data-gjs-name="Catégorie 1">Politique</span>
                                <span class="grapejs-editable" data-gjs-type="text" data-gjs-name="Volume 1">245K posts</span>
                            </div>
                        </div>
                        <div class="trend-change up grapejs-editable" data-gjs-type="text" data-gjs-name="Changement 1">+12%</div>
                    </div>
                    <div class="trend-item">
                        <div class="trend-rank">2</div>
                        <div class="trend-content">
                            <div class="trend-title grapejs-editable" data-gjs-type="text" data-gjs-name="Tendance 2">Nouveau smartphone AI</div>
                            <div class="trend-meta">
                                <span class="grapejs-editable" data-gjs-type="text" data-gjs-name="Catégorie 2">Technologie</span>
                                <span class="grapejs-editable" data-gjs-type="text" data-gjs-name="Volume 2">189K posts</span>
                            </div>
                        </div>
                        <div class="trend-change up grapejs-editable" data-gjs-type="text" data-gjs-name="Changement 2">+8%</div>
                    </div>
                    <div class="trend-item">
                        <div class="trend-rank">3</div>
                        <div class="trend-content">
                            <div class="trend-title grapejs-editable" data-gjs-type="text" data-gjs-name="Tendance 3">Match de football Euro</div>
                            <div class="trend-meta">
                                <span class="grapejs-editable" data-gjs-type="text" data-gjs-name="Catégorie 3">Sport</span>
                                <span class="grapejs-editable" data-gjs-type="text" data-gjs-name="Volume 3">156K posts</span>
                            </div>
                        </div>
                        <div class="trend-change down grapejs-editable" data-gjs-type="text" data-gjs-name="Changement 3">-5%</div>
                    </div>
                    <div style="margin-top: auto; font-size: 0.9rem; color: var(--gray);">
                        <span class="grapejs-editable" data-gjs-type="text" data-gjs-name="Source API">Source: MediaStack API</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- API Information Section -->
        <div class="api-info grapejs-component" data-gjs-type="api-info" data-gjs-name="Informations APIs">
            <h3 class="grapejs-editable" data-gjs-type="text" data-gjs-name="Titre Section">APIs Utilisées dans ce Dashboard</h3>
            <div class="api-list">
                <div class="api-item">
                    <div class="api-name">
                        <i class="fas fa-cloud-sun" style="color: var(--primary);"></i>
                        <span class="grapejs-editable" data-gjs-type="text" data-gjs-name="Nom API 1">OpenWeatherMap</span>
                    </div>
                    <div class="api-desc grapejs-editable" data-gjs-type="text" data-gjs-name="Description API 1">
                        Données météo globales (conditions actuelles, prévisions, historique) via REST JSON.
                    </div>
                    <span class="api-type grapejs-editable" data-gjs-type="text" data-gjs-name="Type API 1">API Météo</span>
                </div>
                <div class="api-item">
                    <div class="api-name">
                        <i class="fas fa-traffic-light" style="color: var(--primary);"></i>
                        <span class="grapejs-editable" data-gjs-type="text" data-gjs-name="Nom API 2">TomTom Traffic</span>
                    </div>
                    <div class="api-desc grapejs-editable" data-gjs-type="text" data-gjs-name="Description API 2">
                        Trafic en temps réel, incidents, vitesses et routes via API REST.
                    </div>
                    <span class="api-type grapejs-editable" data-gjs-type="text" data-gjs-name="Type API 2">API Trafic</span>
                </div>
                <div class="api-item">
                    <div class="api-name">
                        <i class="fas fa-chart-line" style="color: var(--primary);"></i>
                        <span class="grapejs-editable" data-gjs-type="text" data-gjs-name="Nom API 3">Alpha Vantage</span>
                    </div>
                    <div class="api-desc grapejs-editable" data-gjs-type="text" data-gjs-name="Description API 3">
                        Données boursières (actions, cryptos, forex) en temps réel et historique.
                    </div>
                    <span class="api-type grapejs-editable" data-gjs-type="text" data-gjs-name="Type API 3">API Finance</span>
                </div>
                <div class="api-item">
                    <div class="api-name">
                        <i class="fas fa-globe-europe" style="color: var(--primary);"></i>
                        <span class="grapejs-editable" data-gjs-type="text" data-gjs-name="Nom API 4">REST Countries</span>
                    </div>
                    <div class="api-desc grapejs-editable" data-gjs-type="text" data-gjs-name="Description API 4">
                        Informations complètes sur les pays (population, capitales, langues, drapeaux).
                    </div>
                    <span class="api-type grapejs-editable" data-gjs-type="text" data-gjs-name="Type API 4">API Géographie</span>
                </div>
                <div class="api-item">
                    <div class="api-name">
                        <i class="fas fa-charging-station" style="color: var(--primary);"></i>
                        <span class="grapejs-editable" data-gjs-type="text" data-gjs-name="Nom API 5">Open Charge Map</span>
                    </div>
                    <div class="api-desc grapejs-editable" data-gjs-type="text" data-gjs-name="Description API 5">
                        Registre mondial de bornes de recharge pour véhicules électriques en open data.
                    </div>
                    <span class="api-type grapejs-editable" data-gjs-type="text" data-gjs-name="Type API 5">API Mobilité</span>
                </div>
                <div class="api-item">
                    <div class="api-name">
                        <i class="fas fa-fire" style="color: var(--primary);"></i>
                        <span class="grapejs-editable" data-gjs-type="text" data-gjs-name="Nom API 6">MediaStack</span>
                    </div>
                    <div class="api-desc grapejs-editable" data-gjs-type="text" data-gjs-name="Description API 6">
                        Actualités globales en JSON avec tendances par sujet et pays.
                    </div>
                    <span class="api-type grapejs-editable" data-gjs-type="text" data-gjs-name="Type API 6">API Actualités</span>
                </div>
            </div>
        </div>

    </div>

    <script>
        // Simulation de données en temps réel
        document.addEventListener('DOMContentLoaded', function() {
            // Initialisation des composants GrapeJS
            initGrapeJSElements();
            
            // Simulation de mise à jour des données
            simulateRealTimeUpdates();
            
            // Configuration des événements interactifs
            setupInteractiveEvents();
        });

        function initGrapeJSElements() {
            // Ajouter des classes GrapeJS aux éléments supplémentaires
            const widgets = document.querySelectorAll('.widget');
            widgets.forEach((widget, index) => {
                widget.classList.add('grapejs-component');
            });
            
            // Ajouter des attributs aux boutons d'action
            const buttons = document.querySelectorAll('.widget-btn');
            buttons.forEach((btn, index) => {
                btn.classList.add('grapejs-editable');
                btn.setAttribute('data-gjs-type', 'button');
                btn.setAttribute('data-gjs-name', `Bouton ${index + 1}`);
            });
            
            // Configuration pour les éléments éditables
            const editableElements = document.querySelectorAll('.grapejs-editable');
            editableElements.forEach(el => {
                el.addEventListener('mouseenter', function() {
                    this.style.outline = '2px dashed rgba(67, 97, 238, 0.5)';
                });
                
                el.addEventListener('mouseleave', function() {
                    this.style.outline = '2px dashed rgba(67, 97, 238, 0.2)';
                });
                
                // Pour la compatibilité avec GrapeJS
                el.setAttribute('contenteditable', 'true');
            });
        }

        function simulateRealTimeUpdates() {
            // Simulation de données météo changeantes
            setInterval(() => {
                const tempElement = document.querySelector('[data-gjs-name="Température"]');
                if (tempElement) {
                    const currentTemp = parseInt(tempElement.textContent);
                    const newTemp = currentTemp + (Math.random() > 0.5 ? 1 : -1);
                    if (newTemp >= 15 && newTemp <= 25) {
                        tempElement.textContent = newTemp;
                    }
                }
                
                // Simulation de changement de statut des bornes EV
                const evStatusElements = document.querySelectorAll('[data-gjs-name="Statut"]');
                evStatusElements.forEach(el => {
                    if (Math.random() > 0.7) {
                        if (el.textContent === 'Disponible') {
                            el.textContent = 'Occupée';
                            el.className = 'ev-status busy grapejs-editable';
                        } else {
                            el.textContent = 'Disponible';
                            el.className = 'ev-status available grapejs-editable';
                        }
                    }
                });
                
                // Simulation de changement de prix boursier
                const stockPriceElement = document.querySelector('[data-gjs-name="Prix"]');
                if (stockPriceElement) {
                    const currentPrice = parseFloat(stockPriceElement.textContent.replace(',', ''));
                    const changePercent = (Math.random() - 0.5) * 0.5; // -0.25% à +0.25%
                    const newPrice = currentPrice * (1 + changePercent / 100);
                    stockPriceElement.textContent = newPrice.toFixed(2).replace('.', ',');
                    
                    // Mettre à jour la variation
                    const changeElement = document.querySelector('[data-gjs-name="Variation"]');
                    if (changeElement) {
                        const sign = changePercent >= 0 ? '+' : '';
                        changeElement.textContent = `${sign}${changePercent.toFixed(2)}%`;
                        changeElement.className = changePercent >= 0 ? 
                            'stock-change positive grapejs-editable' : 
                            'stock-change negative grapejs-editable';
                    }
                }
            }, 10000); // Mise à jour toutes les 10 secondes
        }

        function setupInteractiveEvents() {
            // Boutons d'actualisation
            const refreshButtons = document.querySelectorAll('.widget-btn .fa-sync-alt');
            refreshButtons.forEach(btn => {
                btn.closest('.widget-btn').addEventListener('click', function() {
                    const icon = this.querySelector('i');
                    icon.style.transform = 'rotate(360deg)';
                    icon.style.transition = 'transform 0.5s';
                    
                    setTimeout(() => {
                        icon.style.transform = 'rotate(0deg)';
                    }, 500);
                    
                    // Simulation de nouvelle donnée
                    const widget = this.closest('.widget');
                    if (widget.querySelector('[data-gjs-name="Ville"]')) {
                        // Widget météo
                        const cities = ['Paris', 'Lyon', 'Marseille', 'Toulouse', 'Bordeaux'];
                        const randomCity = cities[Math.floor(Math.random() * cities.length)];
                        widget.querySelector('[data-gjs-name="Ville"]').textContent = `${randomCity}, France`;
                    }
                });
            });
            
            // Bouton de changement de pays
            const changeCountryBtn = document.querySelector('[title="Changer Pays"]');
            if (changeCountryBtn) {
                changeCountryBtn.addEventListener('click', function() {
                    const countries = [
                        { name: 'Allemagne', capital: 'Berlin', population: '83.2M', flag: 'https://flagcdn.com/w320/de.png' },
                        { name: 'Espagne', capital: 'Madrid', population: '47.4M', flag: 'https://flagcdn.com/w320/es.png' },
                        { name: 'Italie', capital: 'Rome', population: '59.1M', flag: 'https://flagcdn.com/w320/it.png' },
                        { name: 'Royaume-Uni', capital: 'Londres', population: '67.3M', flag: 'https://flagcdn.com/w320/gb.png' },
                        { name: 'Canada', capital: 'Ottawa', population: '38.2M', flag: 'https://flagcdn.com/w320/ca.png' }
                    ];
                    
                    const randomCountry = countries[Math.floor(Math.random() * countries.length)];
                    const widget = this.closest('.widget');
                    
                    widget.querySelector('[data-gjs-name="Nom Pays"]').textContent = randomCountry.name;
                    widget.querySelector('[data-gjs-name="Capitale"]').textContent = randomCountry.capital;
                    widget.querySelector('[data-gjs-name="Population"]').textContent = randomCountry.population;
                    widget.querySelector('.country-flag').style.backgroundImage = `url('${randomCountry.flag}')`;
                });
            }
            
            // Bouton de recherche de bornes EV
            const searchEvBtn = document.querySelector('[title="Rechercher"]');
            if (searchEvBtn) {
                searchEvBtn.addEventListener('click', function() {
                    const locations = ['Paris', 'Lyon', 'Marseille', 'Bordeaux', 'Lille'];
                    const randomLocation = locations[Math.floor(Math.random() * locations.length)];
                    
                    const widget = this.closest('.widget');
                    widget.querySelector('[data-gjs-name="Localisation"]').textContent = `Bornes à proximité de ${randomLocation}`;
                });
            }
        }
        
        // Exposer des fonctions pour GrapeJS
        window.dashboardAPI = {
            refreshWidget: function(widgetType) {
                const widget = document.querySelector(`[data-gjs-type="${widgetType}"]`);
                if (widget) {
                    const refreshBtn = widget.querySelector('.fa-sync-alt');
                    if (refreshBtn) refreshBtn.closest('.widget-btn').click();
                }
            },
            
            changeCountry: function(countryCode) {
                const countryWidget = document.querySelector('[data-gjs-type="country-widget"]');
                if (countryWidget) {
                    const flagUrl = `https://flagcdn.com/w320/${countryCode.toLowerCase()}.png`;
                    countryWidget.querySelector('.country-flag').style.backgroundImage = `url('${flagUrl}')`;
                }
            },
            
            simulateDataUpdate: function() {
                simulateRealTimeUpdates();
            }
        };
    </script>
    <script>
    function sendHeight() {
    const height = document.body.scrollHeight;
    window.parent.postMessage({
        type: 'setHeight',
        iframeId: 'iframe-page-meteo-1',
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