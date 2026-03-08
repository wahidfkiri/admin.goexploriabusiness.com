<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Services Web - Carrousels Entreprises Premium</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #5e60ce;
            --primary-gradient: linear-gradient(135deg, #5e60ce 0%, #6930c3 100%);
            --secondary-color: #5390d9;
            --accent-color: #4cc9f0;
            --accent-gradient: linear-gradient(135deg, #4cc9f0 0%, #56cfe1 100%);
            --light-color: #f8f9fa;
            --dark-color: #212529;
            --gray-color: #6c757d;
            --light-gray: #e9ecef;
            --success-color: #06d6a0;
            --warning-color: #ffd166;
            --danger-color: #ef476f;
            --shadow: 0 20px 40px rgba(0, 0, 0, 0.08);
            --shadow-hover: 0 30px 60px rgba(0, 0, 0, 0.15);
            --transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            --border-radius: 20px;
            --border-radius-sm: 12px;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            line-height: 1.7;
            color: var(--dark-color);
            background-color: #f8fafd;
            padding: 20px;
            overflow-x: hidden;
        }
        
        .services-section {
            max-width: 1600px;
            margin: 0 auto;
            padding: 60px 30px;
            position: relative;
        }
        
        /* Background elements */
        .bg-elements {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: -1;
            opacity: 0.6;
        }
        
        .bg-circle {
            position: absolute;
            border-radius: 50%;
            background: var(--primary-gradient);
            opacity: 0.05;
            animation: float 20s infinite ease-in-out;
        }
        
        .circle-1 {
            width: 400px;
            height: 400px;
            top: -150px;
            right: -100px;
            animation-delay: 0s;
        }
        
        .circle-2 {
            width: 300px;
            height: 300px;
            bottom: -100px;
            left: -100px;
            background: var(--accent-gradient);
            animation-delay: 5s;
        }
        
        .circle-3 {
            width: 200px;
            height: 200px;
            top: 50%;
            left: 10%;
            animation-delay: 10s;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0) scale(1); }
            50% { transform: translateY(-20px) scale(1.05); }
        }
        
        .section-header {
            text-align: center;
            margin-bottom: 70px;
            position: relative;
        }
        
        .section-badge {
            display: inline-block;
            background: var(--primary-gradient);
            color: white;
            padding: 8px 24px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.9rem;
            letter-spacing: 1px;
            margin-bottom: 20px;
            box-shadow: 0 8px 20px rgba(94, 96, 206, 0.3);
        }
        
        .section-header h1 {
            font-family: 'Montserrat', sans-serif;
            font-size: 3.2rem;
            font-weight: 800;
            color: var(--dark-color);
            margin-bottom: 20px;
            line-height: 1.2;
        }
        
        .section-header h1 .highlight {
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            position: relative;
        }
        
        .section-header p {
            font-size: 1.3rem;
            color: var(--gray-color);
            max-width: 800px;
            margin: 0 auto;
        }
        
        .carousels-container {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 40px;
            margin-bottom: 80px;
        }
        
        .carousel-box {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-radius: var(--border-radius);
            padding: 35px;
            box-shadow: var(--shadow);
            transition: var(--transition);
            border: 1px solid rgba(255, 255, 255, 0.2);
            position: relative;
            overflow: hidden;
        }
        
        .carousel-box:before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: var(--primary-gradient);
        }
        
        .carousel-box:hover {
            transform: translateY(-15px);
            box-shadow: var(--shadow-hover);
        }
        
        .carousel-title {
            display: flex;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid var(--light-gray);
        }
        
        .carousel-icon {
            width: 60px;
            height: 60px;
            border-radius: 16px;
            background: var(--primary-gradient);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 20px;
            color: white;
            font-size: 1.8rem;
            box-shadow: 0 10px 20px rgba(94, 96, 206, 0.3);
        }
        
        .carousel-title h2 {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--dark-color);
        }
        
        .carousel-title h2 span {
            display: block;
            font-size: 1rem;
            font-weight: 500;
            color: var(--gray-color);
            margin-top: 5px;
        }
        
        .carousel {
            position: relative;
            overflow: hidden;
            border-radius: var(--border-radius-sm);
            min-height: 380px;
        }
        
        .carousel-items {
            display: flex;
            transition: transform 0.6s cubic-bezier(0.215, 0.61, 0.355, 1);
            height: 100%;
        }
        
        .carousel-item {
            min-width: 100%;
            padding: 10px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        
        .carousel-nav {
            display: flex;
            justify-content: space-between;
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            transform: translateY(-50%);
            padding: 0 10px;
            z-index: 10;
            pointer-events: none;
        }
        
        .carousel-nav button {
            background: white;
            border: none;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            cursor: pointer;
            transition: var(--transition);
            font-size: 1.3rem;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-color);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            pointer-events: all;
        }
        
        .carousel-nav button:hover {
            background: var(--primary-color);
            color: white;
            transform: scale(1.1);
        }
        
        .carousel-nav button:active {
            transform: scale(0.95);
        }
        
        .carousel-progress {
            width: 100%;
            height: 4px;
            background: var(--light-gray);
            border-radius: 2px;
            margin-top: 30px;
            overflow: hidden;
            position: relative;
        }
        
        .progress-bar {
            height: 100%;
            background: var(--primary-gradient);
            width: 0%;
            transition: width 0.3s ease;
        }
        
        .dots-container {
            display: flex;
            justify-content: center;
            margin-top: 20px;
            gap: 10px;
        }
        
        .dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background-color: var(--light-gray);
            cursor: pointer;
            transition: var(--transition);
            position: relative;
        }
        
        .dot.active {
            background-color: var(--primary-color);
            transform: scale(1.3);
        }
        
        .dot:after {
            content: '';
            position: absolute;
            top: -4px;
            left: -4px;
            right: -4px;
            bottom: -4px;
            border-radius: 50%;
            border: 2px solid var(--primary-color);
            opacity: 0;
            transition: var(--transition);
        }
        
        .dot.active:after {
            opacity: 0.3;
        }
        
        /* Entreprises carousel */
        .entreprise-item {
            text-align: center;
            padding: 30px 20px;
            background: white;
            border-radius: var(--border-radius-sm);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.05);
            transition: var(--transition);
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        
        .entreprise-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
        }
        
        .entreprise-logo {
            width: 100px;
            height: 100px;
            margin: 0 auto 25px;
            border-radius: 50%;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            border: 5px solid white;
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
            position: relative;
        }
        
        .entreprise-logo:before {
            content: '';
            position: absolute;
            top: -5px;
            left: -5px;
            right: -5px;
            bottom: -5px;
            border-radius: 50%;
            background: var(--primary-gradient);
            z-index: -1;
        }
        
        .entreprise-logo i {
            font-size: 2.5rem;
            color: var(--primary-color);
        }
        
        .entreprise-name {
            font-weight: 700;
            font-size: 1.5rem;
            margin-bottom: 10px;
            color: var(--dark-color);
        }
        
        .entreprise-region {
            color: var(--primary-color);
            font-weight: 600;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        
        .entreprise-activity {
            color: var(--gray-color);
            font-size: 1rem;
            margin-bottom: 20px;
            padding: 8px 15px;
            background: var(--light-gray);
            border-radius: 50px;
            display: inline-block;
        }
        
        .entreprise-link {
            display: inline-block;
            background: var(--primary-gradient);
            color: white;
            padding: 12px 30px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            transition: var(--transition);
            box-shadow: 0 8px 20px rgba(94, 96, 206, 0.3);
            position: relative;
            overflow: hidden;
        }
        
        .entreprise-link:before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: left 0.7s ease;
        }
        
        .entreprise-link:hover:before {
            left: 100%;
        }
        
        .entreprise-link:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 25px rgba(94, 96, 206, 0.4);
        }
        
        /* Région carousel */
        .region-item {
            text-align: center;
            padding: 30px 20px;
            background: white;
            border-radius: var(--border-radius-sm);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.05);
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        
        .region-flag {
            width: 100px;
            height: 100px;
            margin: 0 auto 25px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
            border: 5px solid white;
            position: relative;
            overflow: hidden;
        }
        
        .region-flag:before {
            content: '';
            position: absolute;
            top: -5px;
            left: -5px;
            right: -5px;
            bottom: -5px;
            border-radius: 50%;
            background: var(--primary-gradient);
            z-index: -1;
        }
        
        .region-name {
            font-weight: 700;
            font-size: 1.5rem;
            margin-bottom: 20px;
            color: var(--dark-color);
        }
        
        .region-stats {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-top: 20px;
        }
        
        .stat {
            text-align: center;
            padding: 15px;
            background: var(--light-gray);
            border-radius: var(--border-radius-sm);
            transition: var(--transition);
        }
        
        .stat:hover {
            background: var(--primary-color);
            color: white;
            transform: translateY(-5px);
        }
        
        .stat:hover .stat-value,
        .stat:hover .stat-label {
            color: white;
        }
        
        .stat-value {
            font-weight: 800;
            font-size: 1.8rem;
            color: var(--primary-color);
            margin-bottom: 5px;
        }
        
        .stat-label {
            font-size: 0.9rem;
            color: var(--gray-color);
            font-weight: 500;
        }
        
        /* Activité carousel */
        .activity-item {
            padding: 30px 20px;
            background: white;
            border-radius: var(--border-radius-sm);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.05);
            height: 100%;
            display: flex;
            flex-direction: column;
            transition: var(--transition);
        }
        
        .activity-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
        }
        
        .activity-icon {
            width: 80px;
            height: 80px;
            margin-bottom: 25px;
            border-radius: 20px;
            background: var(--primary-gradient);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 2rem;
            box-shadow: 0 10px 20px rgba(94, 96, 206, 0.3);
        }
        
        .activity-title {
            font-weight: 700;
            font-size: 1.4rem;
            margin-bottom: 15px;
            color: var(--dark-color);
        }
        
        .activity-desc {
            color: var(--gray-color);
            font-size: 1rem;
            margin-bottom: 25px;
            flex-grow: 1;
        }
        
        /* Web Editor Section */
        .web-editor-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 50px;
            margin: 80px 0;
            align-items: center;
        }
        
        .web-editor-info {
            background: white;
            border-radius: var(--border-radius);
            padding: 50px;
            box-shadow: var(--shadow);
            position: relative;
            overflow: hidden;
        }
        
        .web-editor-info:before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm29 22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zM32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm57-13c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-9-21c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 60c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z' fill='%235e60ce' fill-opacity='0.03' fill-rule='evenodd'/%3E%3C/svg%3E");
            opacity: 0.5;
        }
        
        .web-editor-info h3 {
            font-size: 2rem;
            color: var(--dark-color);
            margin-bottom: 25px;
            position: relative;
            display: flex;
            align-items: center;
        }
        
        .web-editor-info h3 i {
            margin-right: 15px;
            color: var(--primary-color);
            background: rgba(94, 96, 206, 0.1);
            padding: 15px;
            border-radius: 16px;
        }
        
        .web-editor-info p {
            color: var(--gray-color);
            font-size: 1.1rem;
            margin-bottom: 25px;
            line-height: 1.8;
        }
        
        .ai-badge {
            display: inline-block;
            background: linear-gradient(135deg, #ff6b6b 0%, #ff8e8e 100%);
            color: white;
            padding: 6px 18px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.9rem;
            margin-left: 15px;
            box-shadow: 0 8px 20px rgba(255, 107, 107, 0.3);
        }
        
        .editor-features {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-top: 30px;
        }
        
        .editor-feature {
            display: flex;
            align-items: center;
            background: rgba(94, 96, 206, 0.05);
            padding: 15px;
            border-radius: var(--border-radius-sm);
            transition: var(--transition);
        }
        
        .editor-feature:hover {
            background: rgba(94, 96, 206, 0.1);
            transform: translateX(5px);
        }
        
        .editor-feature i {
            color: var(--primary-color);
            margin-right: 15px;
            font-size: 1.2rem;
        }
        
        .editor-visual {
            position: relative;
        }
        
        .editor-screen {
            background: white;
            border-radius: var(--border-radius);
            padding: 30px;
            box-shadow: var(--shadow);
            position: relative;
            overflow: hidden;
            height: 400px;
            display: flex;
            flex-direction: column;
        }
        
        .screen-header {
            display: flex;
            align-items: center;
            margin-bottom: 30px;
        }
        
        .screen-dots {
            display: flex;
            gap: 8px;
            margin-right: 20px;
        }
        
        .screen-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
        }
        
        .dot-red { background: #ff5f57; }
        .dot-yellow { background: #ffbd2e; }
        .dot-green { background: #28ca42; }
        
        .screen-content {
            flex-grow: 1;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            border-radius: var(--border-radius-sm);
            padding: 30px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
        }
        
        .screen-content h4 {
            font-size: 1.5rem;
            margin-bottom: 15px;
            color: var(--dark-color);
        }
        
        .screen-content p {
            color: var(--gray-color);
            margin-bottom: 25px;
        }
        
        /* Features section */
        .features-container {
            background: white;
            border-radius: var(--border-radius);
            padding: 70px 50px;
            box-shadow: var(--shadow);
            margin-bottom: 80px;
            position: relative;
            overflow: hidden;
        }
        
        .features-title {
            text-align: center;
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--dark-color);
            margin-bottom: 60px;
            position: relative;
        }
        
        .features-title:after {
            content: '';
            position: absolute;
            bottom: -20px;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 5px;
            background: var(--primary-gradient);
            border-radius: 5px;
        }
        
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 35px;
        }
        
        .feature-card {
            background-color: #f8fafc;
            border-radius: var(--border-radius);
            padding: 35px 30px;
            transition: var(--transition);
            border-left: 5px solid var(--primary-color);
            position: relative;
            overflow: hidden;
        }
        
        .feature-card:before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: var(--primary-gradient);
            opacity: 0;
            transition: var(--transition);
            z-index: 0;
        }
        
        .feature-card:hover:before {
            opacity: 1;
        }
        
        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: var(--shadow-hover);
        }
        
        .feature-card:hover * {
            color: white !important;
        }
        
        .feature-card i {
            font-size: 2.5rem;
            color: var(--primary-color);
            margin-bottom: 25px;
            display: block;
            position: relative;
            z-index: 1;
            transition: var(--transition);
        }
        
        .feature-card h3 {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 15px;
            color: var(--dark-color);
            position: relative;
            z-index: 1;
            transition: var(--transition);
        }
        
        .feature-card p {
            color: var(--gray-color);
            font-size: 1rem;
            position: relative;
            z-index: 1;
            transition: var(--transition);
        }
        
        /* CTA Section */
        .cta-section {
            text-align: center;
            background: var(--primary-gradient);
            padding: 80px 60px;
            border-radius: var(--border-radius);
            color: white;
            box-shadow: var(--shadow);
            position: relative;
            overflow: hidden;
        }
        
        .cta-section:before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm29 22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zM32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm57-13c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-9-21c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 60c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z' fill='%23ffffff' fill-opacity='0.1' fill-rule='evenodd'/%3E%3C/svg%3E");
            opacity: 0.3;
        }
        
        .cta-title {
            font-size: 2.8rem;
            font-weight: 800;
            margin-bottom: 25px;
            position: relative;
            z-index: 1;
        }
        
        .cta-subtitle {
            font-size: 1.3rem;
            margin-bottom: 40px;
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
            position: relative;
            z-index: 1;
            opacity: 0.9;
        }
        
        .cta-button {
            display: inline-block;
            background-color: white;
            color: var(--primary-color);
            padding: 18px 50px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 700;
            font-size: 1.2rem;
            transition: var(--transition);
            position: relative;
            z-index: 1;
            box-shadow: 0 15px 30px rgba(0,0,0,0.2);
        }
        
        .cta-button:hover {
            transform: translateY(-8px) scale(1.05);
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
            background-color: var(--accent-color);
            color: white;
        }
        
        @media (max-width: 1200px) {
            .carousels-container {
                grid-template-columns: 1fr;
                max-width: 800px;
                margin-left: auto;
                margin-right: auto;
            }
            
            .web-editor-container {
                grid-template-columns: 1fr;
                gap: 40px;
            }
        }
        
        @media (max-width: 992px) {
            .section-header h1 {
                font-size: 2.5rem;
            }
            
            .features-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .cta-title {
                font-size: 2.2rem;
            }
        }
        
        @media (max-width: 768px) {
            .services-section {
                padding: 40px 20px;
            }
            
            .section-header h1 {
                font-size: 2rem;
            }
            
            .features-grid {
                grid-template-columns: 1fr;
            }
            
            .carousel-box {
                padding: 25px;
            }
            
            .web-editor-info, .features-container {
                padding: 40px 25px;
            }
            
            .cta-section {
                padding: 60px 30px;
            }
            
            .cta-title {
                font-size: 1.8rem;
            }
        }
        
        /* Animations */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .carousel-box, .web-editor-info, .features-container, .cta-section {
            animation: fadeInUp 0.8s ease-out forwards;
            opacity: 0;
        }
        
        .carousel-box:nth-child(1) { animation-delay: 0.1s; }
        .carousel-box:nth-child(2) { animation-delay: 0.3s; }
        .carousel-box:nth-child(3) { animation-delay: 0.5s; }
        .web-editor-info { animation-delay: 0.7s; }
        .features-container { animation-delay: 0.9s; }
        .cta-section { animation-delay: 1.1s; }
        
        /* Animation pour les cartes de fonctionnalités */
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(40px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .feature-card {
            animation: slideUp 0.6s ease-out forwards;
            opacity: 0;
        }
        
        .feature-card:nth-child(1) { animation-delay: 0.1s; }
        .feature-card:nth-child(2) { animation-delay: 0.2s; }
        .feature-card:nth-child(3) { animation-delay: 0.3s; }
        .feature-card:nth-child(4) { animation-delay: 0.4s; }
        .feature-card:nth-child(5) { animation-delay: 0.5s; }
        .feature-card:nth-child(6) { animation-delay: 0.6s; }
        .feature-card:nth-child(7) { animation-delay: 0.7s; }
        .feature-card:nth-child(8) { animation-delay: 0.8s; }
    </style>
</head>
<body>
    <section class="services-section">
        <div class="bg-elements">
            <div class="bg-circle circle-1"></div>
            <div class="bg-circle circle-2"></div>
            <div class="bg-circle circle-3"></div>
        </div>
        
        <div class="section-header">
            <div class="section-badge">SOLUTIONS WEB PROFESSIONNELLES</div>
            <h1>Développez votre présence en ligne avec nos <span class="highlight">Services Web</span></h1>
            <p>Découvrez notre plateforme tout-en-un pour créer, gérer et optimiser votre présence numérique avec des outils innovants et une assistance experte.</p>
        </div>
        
        <div class="carousels-container" style="display:none;">
            <!-- Entreprises Carousel -->
            <div class="carousel-box">
                <div class="carousel-title">
                    <div class="carousel-icon">
                        <i class="fas fa-building"></i>
                    </div>
                    <h2>Entreprises<br><span>Clients satisfaits dans tous les secteurs</span></h2>
                </div>
                <div class="carousel" id="entreprises-carousel">
                    <div class="carousel-items">
                        <!-- Items will be populated by JavaScript -->
                    </div>
                    <div class="carousel-nav">
                        <button class="prev-btn"><i class="fas fa-chevron-left"></i></button>
                        <button class="next-btn"><i class="fas fa-chevron-right"></i></button>
                    </div>
                    <div class="carousel-progress">
                        <div class="progress-bar" id="progress-entreprises"></div>
                    </div>
                    <div class="dots-container">
                        <!-- Dots will be populated by JavaScript -->
                    </div>
                </div>
            </div>
            
            <!-- Région Carousel -->
            <div class="carousel-box">
                <div class="carousel-title">
                    <div class="carousel-icon">
                        <i class="fas fa-globe-europe"></i>
                    </div>
                    <h2>Région<br><span>Présence internationale avec drapeaux</span></h2>
                </div>
                <div class="carousel" id="region-carousel">
                    <div class="carousel-items">
                        <!-- Items will be populated by JavaScript -->
                    </div>
                    <div class="carousel-nav">
                        <button class="prev-btn"><i class="fas fa-chevron-left"></i></button>
                        <button class="next-btn"><i class="fas fa-chevron-right"></i></button>
                    </div>
                    <div class="carousel-progress">
                        <div class="progress-bar" id="progress-region"></div>
                    </div>
                    <div class="dots-container">
                        <!-- Dots will be populated by JavaScript -->
                    </div>
                </div>
            </div>
            
            <!-- Activité Carousel -->
            <div class="carousel-box">
                <div class="carousel-title">
                    <div class="carousel-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h2>Activité<br><span>Secteurs d'activité et spécialisations</span></h2>
                </div>
                <div class="carousel" id="activite-carousel">
                    <div class="carousel-items">
                        <!-- Items will be populated by JavaScript -->
                    </div>
                    <div class="carousel-nav">
                        <button class="prev-btn"><i class="fas fa-chevron-left"></i></button>
                        <button class="next-btn"><i class="fas fa-chevron-right"></i></button>
                    </div>
                    <div class="carousel-progress">
                        <div class="progress-bar" id="progress-activite"></div>
                    </div>
                    <div class="dots-container">
                        <!-- Dots will be populated by JavaScript -->
                    </div>
                </div>
            </div>
        </div>
        
        <div class="web-editor-container">
            <div class="web-editor-info">
                <h3><i class="fas fa-laptop-code"></i> Éditeur de Site Web <span class="ai-badge">IA INTÉGRÉE</span></h3>
                <p><strong class="highlight">AUTONOME & INTUITIF</strong> : Créez votre site web facilement avec nos assistants humains ou IA. Notre éditeur vous guide pas à pas dans la création d'un site professionnel sans aucune compétence technique requise.</p>
                <p><strong class="highlight">INSCRIVEZ-VOUS</strong> pour afficher votre entreprise et obtenir des résultats concrets avec une visibilité accrue et des outils marketing intégrés.</p>
                
                <div class="editor-features">
                    <div class="editor-feature">
                        <i class="fas fa-robot"></i>
                        <span>Assistance IA 24/7</span>
                    </div>
                    <div class="editor-feature">
                        <i class="fas fa-palette"></i>
                        <span>Design personnalisable</span>
                    </div>
                    <div class="editor-feature">
                        <i class="fas fa-mobile-alt"></i>
                        <span>100% Responsive</span>
                    </div>
                    <div class="editor-feature">
                        <i class="fas fa-bolt"></i>
                        <span>Chargement ultra-rapide</span>
                    </div>
                </div>
            </div>
            
            <div class="editor-visual">
                <div class="editor-screen">
                    <div class="screen-header">
                        <div class="screen-dots">
                            <div class="screen-dot dot-red"></div>
                            <div class="screen-dot dot-yellow"></div>
                            <div class="screen-dot dot-green"></div>
                        </div>
                        <div>Éditeur Web - Créez votre site</div>
                    </div>
                    <div class="screen-content">
                        <h4>Création de site simplifiée</h4>
                        <p>Glissez-déposez les éléments, personnalisez les couleurs et polices, et publiez en un clic.</p>
                        <a href="#" class="entreprise-link" style="margin-top: 10px;">Essayer la démo</a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="features-container">
            <h2 class="features-title">Liste des Services Complets</h2>
            <div class="features-grid">
                <!-- Features will be populated by JavaScript -->
            </div>
        </div>
        
        <div class="cta-section">
            <h2 class="cta-title">Prêt à transformer votre présence en ligne ?</h2>
            <p class="cta-subtitle">Rejoignez des centaines d'entreprises qui ont déjà augmenté leur visibilité et leurs résultats avec nos solutions web innovantes.</p>
            <a href="#" class="cta-button">INSCRIVEZ-VOUS MAINTENANT</a>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // SVG Flags for regions
            const flagSVGs = {
                'fr': `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 3 2" width="60" height="40">
                          <rect width="3" height="2" fill="#ED2939"/>
                          <rect width="2" height="2" fill="white"/>
                          <rect width="1" height="2" fill="#002395"/>
                       </svg>`,
                'eu': `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 3 2" width="60" height="40">
                          <rect width="3" height="2" fill="#003399"/>
                          <circle cx="1.5" cy="1" r="0.5" fill="#FFCC00"/>
                          <circle cx="1.5" cy="1" r="0.45" fill="none" stroke="#003399" stroke-width="0.05"/>
                       </svg>`,
                'de': `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 3 2" width="60" height="40">
                          <rect width="3" height="2" fill="#000000"/>
                          <rect width="3" height="1.34" fill="#DD0000"/>
                          <rect width="3" height="0.67" fill="#FFCC00"/>
                       </svg>`,
                'uk': `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 3 2" width="60" height="40">
                          <rect width="3" height="2" fill="#012169"/>
                          <path d="M0,0 L3,2 M3,0 L0,2" stroke="white" stroke-width="0.2"/>
                          <path d="M1.5,0 L1.5,2 M0,1 L3,1" stroke="white" stroke-width="0.4"/>
                          <path d="M0,0 L3,2 M3,0 L0,2" stroke="#C8102E" stroke-width="0.1"/>
                          <path d="M1.5,0 L1.5,2 M0,1 L3,1" stroke="#C8102E" stroke-width="0.2"/>
                       </svg>`,
                'it': `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 3 2" width="60" height="40">
                          <rect width="3" height="2" fill="#009246"/>
                          <rect width="2" height="2" x="1" fill="white"/>
                          <rect width="1" height="2" x="2" fill="#CE2B37"/>
                       </svg>`
            };
            
            // Données pour les carrousels
            const entreprisesData = [
                {
                    name: "TechInnov Solutions",
                    region: "Île-de-France",
                    regionCode: "fr",
                    activity: "Développement Logiciel & IA",
                    icon: "fas fa-code",
                    color: "#5e60ce"
                },
                {
                    name: "GreenLife Organics",
                    region: "Provence-Alpes-Côte d'Azur",
                    regionCode: "fr",
                    activity: "Produits Bio & Naturels",
                    icon: "fas fa-leaf",
                    color: "#06d6a0"
                },
                {
                    name: "MédiaVision Studio",
                    region: "Auvergne-Rhône-Alpes",
                    regionCode: "fr",
                    activity: "Production Audiovisuelle",
                    icon: "fas fa-video",
                    color: "#ef476f"
                },
                {
                    name: "Architectura Concept",
                    region: "Nouvelle-Aquitaine",
                    regionCode: "fr",
                    activity: "Architecture & Design",
                    icon: "fas fa-drafting-compass",
                    color: "#ffd166"
                },
                {
                    name: "LogiTrans Express",
                    region: "Hauts-de-France",
                    regionCode: "fr",
                    activity: "Transport & Logistique",
                    icon: "fas fa-truck",
                    color: "#118ab2"
                }
            ];
            
            const regionsData = [
                {
                    name: "Île-de-France",
                    flag: "fr",
                    entreprises: "245",
                    croissance: "+12%",
                    description: "Centre économique de la France"
                },
                {
                    name: "Auvergne-Rhône-Alpes",
                    flag: "fr",
                    entreprises: "178",
                    croissance: "+8%",
                    description: "Région industrielle et touristique"
                },
                {
                    name: "Europe International",
                    flag: "eu",
                    entreprises: "89",
                    croissance: "+22%",
                    description: "Marché européen en expansion"
                },
                {
                    name: "Allemagne",
                    flag: "de",
                    entreprises: "67",
                    croissance: "+15%",
                    description: "Premier partenaire économique"
                },
                {
                    name: "Royaume-Uni",
                    flag: "uk",
                    entreprises: "54",
                    croissance: "+10%",
                    description: "Marché anglophone stratégique"
                },
                {
                    name: "Italie",
                    flag: "it",
                    entreprises: "42",
                    croissance: "+18%",
                    description: "Croissance soutenue du marché"
                }
            ];
            
            const activitesData = [
                {
                    title: "Commerce en ligne",
                    icon: "fas fa-shopping-cart",
                    description: "Solutions e-commerce complètes avec paiement sécurisé et gestion des stocks",
                    stats: "+45% de croissance"
                },
                {
                    title: "Services Professionnels",
                    icon: "fas fa-briefcase",
                    description: "Sites vitrines et portails pour consultants, avocats, médecins, etc.",
                    stats: "+32% de croissance"
                },
                {
                    title: "Restauration & Hôtellerie",
                    icon: "fas fa-utensils",
                    description: "Réservations en ligne, menus digitaux et systèmes de commande",
                    stats: "+28% de croissance"
                },
                {
                    title: "Éducation & Formation",
                    icon: "fas fa-graduation-cap",
                    description: "Plateformes d'apprentissage en ligne et gestion de contenu éducatif",
                    stats: "+51% de croissance"
                },
                {
                    title: "Art & Culture",
                    icon: "fas fa-palette",
                    description: "Portfolios d'artistes, galeries virtuelles et billetterie en ligne",
                    stats: "+39% de croissance"
                }
            ];
            
            // Fonctions pour créer les carrousels
            function createEntreprisesCarousel() {
                const container = document.querySelector('#entreprises-carousel .carousel-items');
                const dotsContainer = document.querySelector('#entreprises-carousel .dots-container');
                
                entreprisesData.forEach((entreprise, index) => {
                    // Créer l'élément carousel
                    const item = document.createElement('div');
                    item.className = 'carousel-item';
                    item.innerHTML = `
                        <div class="entreprise-item">
                            <div class="entreprise-logo" style="background: linear-gradient(135deg, ${entreprise.color}20 0%, ${entreprise.color}40 100%);">
                                <i class="${entreprise.icon}" style="color: ${entreprise.color};"></i>
                            </div>
                            <h3 class="entreprise-name">${entreprise.name}</h3>
                            <div class="entreprise-region">
                                ${flagSVGs[entreprise.regionCode]}
                                ${entreprise.region}
                            </div>
                            <div class="entreprise-activity">${entreprise.activity}</div>
                            <a href="#" class="entreprise-link">Voir le profil</a>
                        </div>
                    `;
                    container.appendChild(item);
                    
                    // Créer les dots
                    const dot = document.createElement('div');
                    dot.className = 'dot' + (index === 0 ? ' active' : '');
                    dot.dataset.index = index;
                    dotsContainer.appendChild(dot);
                });
                
                // Initialiser le carousel
                initCarousel('entreprises-carousel', 'progress-entreprises');
            }
            
            function createRegionCarousel() {
                const container = document.querySelector('#region-carousel .carousel-items');
                const dotsContainer = document.querySelector('#region-carousel .dots-container');
                
                regionsData.forEach((region, index) => {
                    // Créer l'élément carousel
                    const item = document.createElement('div');
                    item.className = 'carousel-item';
                    item.innerHTML = `
                        <div class="region-item">
                            <div class="region-flag">
                                ${flagSVGs[region.flag]}
                            </div>
                            <h3 class="region-name">${region.name}</h3>
                            <p style="color: var(--gray-color); margin-bottom: 20px;">${region.description}</p>
                            <div class="region-stats">
                                <div class="stat">
                                    <div class="stat-value">${region.entreprises}</div>
                                    <div class="stat-label">Entreprises</div>
                                </div>
                                <div class="stat">
                                    <div class="stat-value">${region.croissance}</div>
                                    <div class="stat-label">Croissance</div>
                                </div>
                            </div>
                        </div>
                    `;
                    container.appendChild(item);
                    
                    // Créer les dots
                    const dot = document.createElement('div');
                    dot.className = 'dot' + (index === 0 ? ' active' : '');
                    dot.dataset.index = index;
                    dotsContainer.appendChild(dot);
                });
                
                // Initialiser le carousel
                initCarousel('region-carousel', 'progress-region');
            }
            
            function createActiviteCarousel() {
                const container = document.querySelector('#activite-carousel .carousel-items');
                const dotsContainer = document.querySelector('#activite-carousel .dots-container');
                
                activitesData.forEach((activite, index) => {
                    // Créer l'élément carousel
                    const item = document.createElement('div');
                    item.className = 'carousel-item';
                    item.innerHTML = `
                        <div class="activity-item">
                            <div class="activity-icon">
                                <i class="${activite.icon}"></i>
                            </div>
                            <h3 class="activity-title">${activite.title}</h3>
                            <p class="activity-desc">${activite.description}</p>
                            <div style="background: rgba(94, 96, 206, 0.1); padding: 10px; border-radius: 8px; font-weight: 600; color: var(--primary-color);">
                                ${activite.stats}
                            </div>
                        </div>
                    `;
                    container.appendChild(item);
                    
                    // Créer les dots
                    const dot = document.createElement('div');
                    dot.className = 'dot' + (index === 0 ? ' active' : '');
                    dot.dataset.index = index;
                    dotsContainer.appendChild(dot);
                });
                
                // Initialiser le carousel
                initCarousel('activite-carousel', 'progress-activite');
            }
            
            // Initialisation d'un carousel avec barre de progression
            function initCarousel(carouselId, progressBarId) {
                const carousel = document.getElementById(carouselId);
                const itemsContainer = carousel.querySelector('.carousel-items');
                const items = carousel.querySelectorAll('.carousel-item');
                const prevBtn = carousel.querySelector('.prev-btn');
                const nextBtn = carousel.querySelector('.next-btn');
                const dots = carousel.querySelectorAll('.dot');
                const progressBar = document.getElementById(progressBarId);
                
                let currentIndex = 0;
                const totalItems = items.length;
                let autoRotateInterval;
                
                // Fonction pour mettre à jour le carousel
                function updateCarousel() {
                    itemsContainer.style.transform = `translateX(-${currentIndex * 100}%)`;
                    
                    // Mettre à jour les dots
                    dots.forEach((dot, index) => {
                        dot.classList.toggle('active', index === currentIndex);
                    });
                    
                    // Mettre à jour la barre de progression
                    if (progressBar) {
                        progressBar.style.width = `${((currentIndex + 1) / totalItems) * 100}%`;
                    }
                    
                    // Réinitialiser l'auto-rotation
                    resetAutoRotate();
                }
                
                // Fonction pour réinitialiser l'auto-rotation
                function resetAutoRotate() {
                    if (autoRotateInterval) {
                        clearInterval(autoRotateInterval);
                    }
                    
                    autoRotateInterval = setInterval(() => {
                        currentIndex = (currentIndex < totalItems - 1) ? currentIndex + 1 : 0;
                        updateCarousel();
                    }, 5000);
                }
                
                // Événements pour les boutons précédent/suivant
                prevBtn.addEventListener('click', () => {
                    currentIndex = (currentIndex > 0) ? currentIndex - 1 : totalItems - 1;
                    updateCarousel();
                });
                
                nextBtn.addEventListener('click', () => {
                    currentIndex = (currentIndex < totalItems - 1) ? currentIndex + 1 : 0;
                    updateCarousel();
                });
                
                // Événements pour les dots
                dots.forEach(dot => {
                    dot.addEventListener('click', () => {
                        currentIndex = parseInt(dot.dataset.index);
                        updateCarousel();
                    });
                });
                
                // Démarrer l'auto-rotation
                resetAutoRotate();
                
                // Pause auto-rotation au survol
                carousel.addEventListener('mouseenter', () => {
                    if (autoRotateInterval) {
                        clearInterval(autoRotateInterval);
                    }
                });
                
                carousel.addEventListener('mouseleave', () => {
                    resetAutoRotate();
                });
            }
            
            // Données pour les fonctionnalités
            const featuresData = [
                {
                    icon: 'fas fa-blog',
                    title: 'BLOGS',
                    description: 'Créez et gérez un blog professionnel avec éditeur visuel, optimisation SEO intégrée et partage sur les réseaux sociaux.'
                },
                {
                    icon: 'fas fa-file-signature',
                    title: 'BUILDER FORMULAIRES',
                    description: 'Concevez des formulaires avancés avec validation, logique conditionnelle et intégration aux CRM principaux.'
                },
                {
                    icon: 'fas fa-robot',
                    title: 'CALL-TO-ACTION IA',
                    description: 'Générez automatiquement des boutons et messages d\'appel à l\'action optimisés par IA selon le comportement des visiteurs.'
                },
                {
                    icon: 'fas fa-chart-line',
                    title: 'PERFORMANCES SEO INTERNATIONAL',
                    description: 'Optimisez votre site pour les moteurs de recherche internationaux avec suivi des performances et recommandations automatiques.'
                },
                {
                    icon: 'fas fa-phone-alt',
                    title: 'TÉLÉ-MARKETING CONTACTS DIRECT',
                    description: 'Outils de télémarketing intégrés avec gestion des contacts, suivi des appels et automatisation des campagnes.'
                },
                {
                    icon: 'fas fa-language',
                    title: 'SITE WEB MULTILINGUES',
                    description: 'Traduction automatique et gestion de contenu multilingue pour une audience globale avec adaptation culturelle.'
                },
                {
                    icon: 'fas fa-video',
                    title: 'POSITIONNEMENT VIDÉOS CARTE',
                    description: 'Intégration de vidéos et positionnement sur Google Maps avec fiches d\'entreprise optimisées pour la visibilité locale.'
                },
                {
                    icon: 'fas fa-cogs',
                    title: 'API, CRM, ETC.',
                    description: 'Intégrations API complètes avec les principaux CRM, outils de productivité et plateformes tierces.'
                }
            ];
            
            // Créer les cartes de fonctionnalités
            function createFeatures() {
                const container = document.querySelector('.features-grid');
                
                featuresData.forEach((feature, index) => {
                    const card = document.createElement('div');
                    card.className = 'feature-card';
                    card.style.animationDelay = `${(index + 1) * 0.1}s`;
                    card.innerHTML = `
                        <i class="${feature.icon}"></i>
                        <h3>${feature.title}</h3>
                        <p>${feature.description}</p>
                    `;
                    container.appendChild(card);
                });
            }
            
            // Initialiser tous les composants
            createEntreprisesCarousel();
            createRegionCarousel();
            createActiviteCarousel();
            createFeatures();
            
            // Animation au scroll
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -100px 0px'
            };
            
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.animationPlayState = 'running';
                    }
                });
            }, observerOptions);
            
            // Observer les éléments avec animation
            document.querySelectorAll('.carousel-box, .web-editor-info, .features-container, .cta-section').forEach(el => {
                observer.observe(el);
            });
            
            // Animation pour les cartes de fonctionnalités
            const featureObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const cards = entry.target.querySelectorAll('.feature-card');
                        cards.forEach(card => {
                            card.style.opacity = '1';
                            card.style.animationPlayState = 'running';
                        });
                    }
                });
            }, { threshold: 0.05 });
            
            const featuresContainer = document.querySelector('.features-container');
            featureObserver.observe(featuresContainer);
        });
    </script>

    <script>
function sendHeight() {
    const height = document.body.scrollHeight;
    window.parent.postMessage({
        type: 'setHeight',
        iframeId: 'iframe-page-web-1',
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