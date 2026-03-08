<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CANADA | Voyages d'Exception</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&family=Syne:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --black: #0A0A0A;
            --white: #FFFFFF;
            --neon-blue: #00F3FF;
            --neon-pink: #FF00FF;
            --neon-green: #00FF9D;
            --gray-dark: #1A1A1A;
            --gray-light: #E5E5E5;
            --accent: #FF5E00;
            --transition: all 0.4s cubic-bezier(0.23, 1, 0.32, 1);
            --transition-fast: all 0.2s cubic-bezier(0.23, 1, 0.32, 1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
            background-color: var(--black);
        }

        body {
            font-family: 'Space Grotesk', sans-serif;
            background-color: var(--black);
            color: var(--white);
            overflow-x: hidden;
            line-height: 1.6;
        }

        h1, h2, h3, h4, h5 {
            font-family: 'Syne', sans-serif;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: -0.02em;
        }

        /* Scanlines effect */
        .scanlines {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(
                to bottom,
                rgba(255, 255, 255, 0) 50%,
                rgba(0, 0, 0, 0.2) 50%
            );
            background-size: 100% 4px;
            pointer-events: none;
            z-index: 9999;
            opacity: 0.15;
            animation: scanlines 8s linear infinite;
        }

        @keyframes scanlines {
            0% { background-position: 0 0; }
            100% { background-position: 0 100%; }
        }

        .grid-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: 
                linear-gradient(rgba(10, 10, 10, 0.8) 1px, transparent 1px),
                linear-gradient(90deg, rgba(10, 10, 10, 0.8) 1px, transparent 1px);
            background-size: 40px 40px;
            z-index: -2;
            opacity: 0.3;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 40px;
        }

        /* Header Cyberpunk */
        header {
            padding: 30px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            position: relative;
            overflow: hidden;
        }

        .header-glow {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at 50% 0%, var(--neon-blue), transparent 70%);
            opacity: 0.1;
            z-index: -1;
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-family: 'Syne', sans-serif;
            font-size: 2rem;
            font-weight: 800;
            background: linear-gradient(90deg, var(--white), var(--neon-blue));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            position: relative;
            display: flex;
            align-items: center;
        }

        .logo:before {
            content: '⊡';
            margin-right: 10px;
            color: var(--neon-blue);
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }

        .nav-links {
            display: flex;
            gap: 50px;
        }

        .nav-link {
            color: var(--white);
            text-decoration: none;
            font-weight: 500;
            position: relative;
            padding: 10px 0;
            transition: var(--transition);
            text-transform: uppercase;
            font-size: 0.9rem;
            letter-spacing: 1px;
        }

        .nav-link:before {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--neon-blue);
            transition: var(--transition);
        }

        .nav-link:hover:before {
            width: 100%;
        }

        .nav-link.active {
            color: var(--neon-blue);
        }

        .header-actions {
            display: flex;
            gap: 20px;
            align-items: center;
        }

        .btn-cyber {
            background: transparent;
            border: 1px solid var(--neon-blue);
            color: var(--neon-blue);
            padding: 12px 30px;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            position: relative;
            overflow: hidden;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 0.9rem;
        }

        .btn-cyber:before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(0, 243, 255, 0.2), transparent);
            transition: var(--transition);
        }

        .btn-cyber:hover:before {
            left: 100%;
        }

        .btn-cyber:hover {
            box-shadow: 0 0 20px rgba(0, 243, 255, 0.3);
            transform: translateY(-2px);
        }

        /* Hero Futuriste */
        .hero {
            padding: 120px 0 80px;
            position: relative;
            overflow: hidden;
        }

        .hero-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 80px;
            align-items: center;
        }

        .hero-content h1 {
            font-size: 4.5rem;
            line-height: 1;
            margin-bottom: 30px;
            position: relative;
            display: inline-block;
        }

        .hero-content h1:after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 0;
            width: 100px;
            height: 4px;
            background: var(--neon-blue);
        }

        .hero-tagline {
            font-size: 1.3rem;
            color: var(--gray-light);
            margin-bottom: 40px;
            line-height: 1.6;
            max-width: 500px;
        }

        .hero-stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
            margin-top: 50px;
        }

        .stat {
            text-align: center;
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--neon-blue);
            margin-bottom: 5px;
            font-family: 'Syne', sans-serif;
        }

        .stat-label {
            font-size: 0.9rem;
            color: var(--gray-light);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .hero-visual {
            position: relative;
        }

        .visual-frame {
            border: 2px solid var(--neon-blue);
            padding: 3px;
            position: relative;
            overflow: hidden;
        }

        .visual-frame:before {
            content: '';
            position: absolute;
            top: -2px;
            left: -2px;
            right: -2px;
            bottom: -2px;
            background: linear-gradient(45deg, var(--neon-blue), var(--neon-pink), var(--neon-green), var(--neon-blue));
            z-index: -1;
            animation: rotate 3s linear infinite;
        }

        @keyframes rotate {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .visual-frame img {
            width: 100%;
            height: 500px;
            object-fit: cover;
            display: block;
            filter: grayscale(100%) contrast(120%);
            transition: var(--transition);
        }

        .visual-frame:hover img {
            filter: grayscale(0%) contrast(100%);
        }

        .visual-label {
            position: absolute;
            bottom: 20px;
            right: 20px;
            background: var(--black);
            padding: 15px 25px;
            border: 1px solid var(--neon-blue);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 2px;
            font-size: 0.9rem;
        }

        /* Section Destinations */
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-bottom: 60px;
            padding-top: 80px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .section-title {
            font-size: 3rem;
            position: relative;
        }

        .section-subtitle {
            color: var(--gray-light);
            max-width: 400px;
            font-size: 1.1rem;
        }

        .destinations-controls {
            display: flex;
            gap: 15px;
            margin-bottom: 50px;
        }

        .control-btn {
            background: transparent;
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: var(--white);
            padding: 15px 30px;
            cursor: pointer;
            transition: var(--transition);
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 0.9rem;
            position: relative;
            overflow: hidden;
        }

        .control-btn:before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: var(--neon-blue);
            transform: translateX(-100%);
            transition: var(--transition);
            z-index: -1;
        }

        .control-btn:hover:before, .control-btn.active:before {
            transform: translateX(0);
        }

        .control-btn:hover, .control-btn.active {
            border-color: var(--neon-blue);
            color: var(--black);
        }

        /* Grid Minimaliste */
        .destinations-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
            gap: 30px;
            margin-bottom: 120px;
        }

        .destination-card {
            background: var(--gray-dark);
            border: 1px solid rgba(255, 255, 255, 0.1);
            position: relative;
            transition: var(--transition);
            overflow: hidden;
        }

        .destination-card:hover {
            transform: translateY(-10px);
            border-color: var(--neon-blue);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.5);
        }

        .card-header {
            position: relative;
            overflow: hidden;
        }

        .card-image {
            width: 100%;
            height: 250px;
            object-fit: cover;
            transition: var(--transition);
        }

        .destination-card:hover .card-image {
            transform: scale(1.05);
        }

        .card-badge {
            position: absolute;
            top: 20px;
            right: 20px;
            background: var(--black);
            color: var(--neon-blue);
            padding: 10px 20px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 0.8rem;
            border: 1px solid var(--neon-blue);
        }

        .card-content {
            padding: 30px;
        }

        .card-title {
            font-size: 1.8rem;
            margin-bottom: 15px;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .card-location {
            display: flex;
            align-items: center;
            color: var(--gray-light);
            margin-bottom: 20px;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .card-location:before {
            content: '📍';
            margin-right: 10px;
        }

        .card-description {
            color: var(--gray-light);
            margin-bottom: 25px;
            line-height: 1.7;
        }

        .card-features {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 25px;
        }

        .feature {
            background: rgba(0, 243, 255, 0.1);
            padding: 8px 15px;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            border: 1px solid rgba(0, 243, 255, 0.3);
        }

        .card-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .card-price {
            font-size: 2rem;
            font-weight: 800;
            color: var(--neon-blue);
            font-family: 'Syne', sans-serif;
        }

        .card-price span {
            font-size: 0.9rem;
            color: var(--gray-light);
            font-weight: 400;
        }

        .card-actions {
            display: flex;
            gap: 15px;
        }

        .btn-card {
            padding: 12px 25px;
            border: none;
            cursor: pointer;
            transition: var(--transition);
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .btn-outline {
            background: transparent;
            border: 1px solid var(--white);
            color: var(--white);
        }

        .btn-outline:hover {
            border-color: var(--neon-blue);
            color: var(--neon-blue);
        }

        .btn-primary {
            background: var(--neon-blue);
            color: var(--black);
        }

        .btn-primary:hover {
            background: var(--white);
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0, 243, 255, 0.3);
        }

        /* Video Showcase */
        .video-section {
            margin: 100px 0;
            position: relative;
        }

        .video-container {
            border: 2px solid var(--neon-blue);
            position: relative;
            overflow: hidden;
            height: 600px;
        }

        .video-container video {
            width: 100%;
            height: 100%;
            object-fit: cover;
            filter: contrast(120%) grayscale(30%);
        }

        .video-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(to right, rgba(10, 10, 10, 0.9), transparent 50%);
            display: flex;
            align-items: center;
            padding: 0 60px;
        }

        .video-content h2 {
            font-size: 3.5rem;
            margin-bottom: 20px;
            max-width: 600px;
        }

        .video-content p {
            font-size: 1.2rem;
            color: var(--gray-light);
            margin-bottom: 40px;
            max-width: 500px;
        }

        /* Modal Futuriste */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(10, 10, 10, 0.95);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 10000;
            opacity: 0;
            visibility: hidden;
            transition: var(--transition);
            backdrop-filter: blur(10px);
        }

        .modal-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        .modal {
            width: 90%;
            max-width: 1200px;
            background: var(--gray-dark);
            border: 2px solid var(--neon-blue);
            position: relative;
            transform: scale(0.9) translateY(50px);
            opacity: 0;
            transition: var(--transition);
        }

        .modal-overlay.active .modal {
            transform: scale(1) translateY(0);
            opacity: 1;
        }

        .modal-header {
            padding: 30px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-close {
            background: transparent;
            border: none;
            color: var(--white);
            font-size: 1.5rem;
            cursor: pointer;
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: var(--transition);
        }

        .modal-close:hover {
            color: var(--neon-blue);
            transform: rotate(90deg);
        }

        .modal-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            min-height: 600px;
        }

        .modal-media {
            position: relative;
            overflow: hidden;
        }

        .modal-media img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .modal-media:before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(to bottom, transparent, rgba(0, 243, 255, 0.1));
        }

        .modal-info {
            padding: 40px;
            overflow-y: auto;
            max-height: 600px;
        }

        .modal-info h3 {
            font-size: 2.5rem;
            margin-bottom: 10px;
        }

        .modal-location {
            display: flex;
            align-items: center;
            margin-bottom: 30px;
            color: var(--neon-blue);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .modal-description {
            margin-bottom: 30px;
            line-height: 1.8;
            color: var(--gray-light);
        }

        .modal-highlights {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin-bottom: 40px;
        }

        .highlight {
            padding: 15px;
            border: 1px solid rgba(0, 243, 255, 0.3);
            position: relative;
        }

        .highlight:before {
            content: '◈';
            position: absolute;
            top: -10px;
            left: -10px;
            color: var(--neon-blue);
            background: var(--gray-dark);
            padding: 0 5px;
        }

        .modal-price {
            font-size: 3rem;
            font-weight: 800;
            color: var(--neon-blue);
            margin-bottom: 40px;
            font-family: 'Syne', sans-serif;
        }

        .modal-price span {
            font-size: 1rem;
            color: var(--gray-light);
            font-weight: 400;
        }

        .booking-form {
            background: rgba(0, 0, 0, 0.3);
            padding: 30px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 0.9rem;
            color: var(--gray-light);
        }

        .form-group input, .form-group select {
            width: 100%;
            padding: 15px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: var(--white);
            font-family: 'Space Grotesk', sans-serif;
            transition: var(--transition);
        }

        .form-group input:focus, .form-group select:focus {
            outline: none;
            border-color: var(--neon-blue);
            background: rgba(0, 243, 255, 0.05);
        }

        .btn-submit {
            width: 100%;
            padding: 20px;
            background: var(--neon-blue);
            border: none;
            color: var(--black);
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 2px;
            cursor: pointer;
            transition: var(--transition);
            font-size: 1rem;
            position: relative;
            overflow: hidden;
        }

        .btn-submit:hover {
            background: var(--white);
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(0, 243, 255, 0.4);
        }

        .btn-submit:before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
            transition: var(--transition);
        }

        .btn-submit:hover:before {
            left: 100%;
        }

        /* Footer Cyberpunk */
        footer {
            background: var(--gray-dark);
            padding: 80px 0 40px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            position: relative;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1.5fr;
            gap: 60px;
            margin-bottom: 60px;
        }

        .footer-column h3 {
            font-size: 1.2rem;
            margin-bottom: 25px;
            position: relative;
            display: inline-block;
        }

        .footer-column h3:after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 0;
            width: 40px;
            height: 2px;
            background: var(--neon-blue);
        }

        .footer-logo {
            font-family: 'Syne', sans-serif;
            font-size: 2rem;
            font-weight: 800;
            margin-bottom: 20px;
            display: block;
        }

        .footer-links {
            list-style: none;
        }

        .footer-links li {
            margin-bottom: 15px;
        }

        .footer-links a {
            color: var(--gray-light);
            text-decoration: none;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 0.9rem;
        }

        .footer-links a:before {
            content: '➤';
            color: var(--neon-blue);
            transition: var(--transition);
        }

        .footer-links a:hover {
            color: var(--neon-blue);
            transform: translateX(5px);
        }

        .footer-links a:hover:before {
            transform: translateX(5px);
        }

        .newsletter-form {
            margin-top: 20px;
        }

        .newsletter-input {
            width: 100%;
            padding: 15px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: var(--white);
            margin-bottom: 15px;
            font-family: 'Space Grotesk', sans-serif;
        }

        .footer-bottom {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 40px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .social-links {
            display: flex;
            gap: 20px;
        }

        .social-link {
            width: 40px;
            height: 40px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--white);
            text-decoration: none;
            transition: var(--transition);
        }

        .social-link:hover {
            border-color: var(--neon-blue);
            color: var(--neon-blue);
            transform: translateY(-3px);
        }

        .copyright {
            color: var(--gray-light);
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Responsive */
        @media (max-width: 1200px) {
            .hero-content h1 {
                font-size: 3.5rem;
            }
            
            .destinations-grid {
                grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            }
            
            .footer-grid {
                grid-template-columns: 1fr 1fr;
                gap: 40px;
            }
        }

        @media (max-width: 992px) {
            .hero-grid {
                grid-template-columns: 1fr;
                gap: 50px;
            }
            
            .modal-content {
                grid-template-columns: 1fr;
            }
            
            .modal-media {
                height: 300px;
            }
            
            .nav-links {
                display: none;
            }
            
            .video-overlay {
                padding: 40px;
            }
            
            .video-content h2 {
                font-size: 2.5rem;
            }
        }

        @media (max-width: 768px) {
            .container {
                padding: 0 20px;
            }
            
            .hero {
                padding: 80px 0 60px;
            }
            
            .hero-content h1 {
                font-size: 2.5rem;
            }
            
            .hero-stats {
                grid-template-columns: 1fr;
                gap: 20px;
            }
            
            .section-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 20px;
            }
            
            .destinations-controls {
                flex-wrap: wrap;
            }
            
            .destinations-grid {
                grid-template-columns: 1fr;
            }
            
            .video-container {
                height: 400px;
            }
            
            .video-overlay {
                padding: 20px;
                background: rgba(10, 10, 10, 0.8);
            }
            
            .form-grid {
                grid-template-columns: 1fr;
            }
            
            .footer-grid {
                grid-template-columns: 1fr;
            }
            
            .footer-bottom {
                flex-direction: column;
                gap: 30px;
                text-align: center;
            }
        }

        @media (max-width: 576px) {
            .hero-content h1 {
                font-size: 2rem;
            }
            
            .section-title {
                font-size: 2rem;
            }
            
            .card-actions {
                flex-direction: column;
            }
            
            .btn-card {
                width: 100%;
            }
            
            .modal-info {
                padding: 20px;
            }
            
            .modal-info h3 {
                font-size: 1.8rem;
            }
        }
    </style>
</head>
<body>
    <!-- Effets visuels -->
    <div class="scanlines"></div>
    <div class="grid-bg"></div>

    <!-- Header -->
    <header>
        <div class="header-glow"></div>
        <div class="container">
            <nav class="navbar">
                <div class="logo">CANADA</div>
                <div class="nav-links">
                    <a href="#destinations" class="nav-link active">Destinations</a>
                    <a href="#experiences" class="nav-link">Expériences</a>
                    <a href="#about" class="nav-link">Explorer</a>
                    <a href="#contact" class="nav-link">Contact</a>
                </div>
                <div class="header-actions">
                    <button class="btn-cyber">Connexion</button>
                </div>
            </nav>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <div class="hero-grid">
                <div class="hero-content">
                    <h1>VOYAGES<br>D'EXCEPTION<br>AU CANADA</h1>
                    <p class="hero-tagline">Découvrez des expériences uniques dans les paysages les plus époustouflants de la planète. Des aventures sur mesure pour les explorateurs modernes.</p>
                    <button class="btn-cyber" style="padding: 15px 40px; font-size: 1rem;">
                        EXPLORER MAINTENANT
                    </button>
                    <div class="hero-stats">
                        <div class="stat">
                            <div class="stat-number">47</div>
                            <div class="stat-label">Parcs Nationaux</div>
                        </div>
                        <div class="stat">
                            <div class="stat-number">12K+</div>
                            <div class="stat-label">Expériences</div>
                        </div>
                        <div class="stat">
                            <div class="stat-number">98%</div>
                            <div class="stat-label">Satisfaction</div>
                        </div>
                    </div>
                </div>
                <div class="hero-visual">
                    <div class="visual-frame">
                        <img src="https://images.unsplash.com/photo-1506905925346-21bda4d32df4?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80" alt="Paysage canadien">
                        <div class="visual-label">Banff National Park</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Destinations Section -->
    <section id="destinations">
        <div class="container">
            <div class="section-header">
                <div>
                    <h2 class="section-title">DESTINATIONS</h2>
                    <p class="section-subtitle">Des expériences soigneusement sélectionnées pour les voyageurs exigeants.</p>
                </div>
            </div>

            <div class="destinations-controls">
                <button class="control-btn active" data-filter="all">Toutes</button>
                <button class="control-btn" data-filter="nord">Nordique</button>
                <button class="control-btn" data-filter="montagne">Montagne</button>
                <button class="control-btn" data-filter="urbain">Urbain</button>
                <button class="control-btn" data-filter="aventure">Aventure</button>
            </div>

            <!-- Destinations Grid -->
            <div class="destinations-grid" id="destinations-container">
                <!-- Cards will be generated by JavaScript -->
            </div>
        </div>
    </section>

    <!-- Video Section -->
    <section class="video-section" id="experiences">
        <div class="container">
            <div class="video-container">
                <video autoplay muted loop>
                    <source src="https://assets.mixkit.co/videos/preview/mixkit-forest-stream-in-the-sunlight-529-large.mp4" type="video/mp4">
                </video>
                <div class="video-overlay">
                    <div class="video-content">
                        <h2>EXPÉRIENCE<br>IMMERSIVE</h2>
                        <p>Plongez au cœur des paysages canadiens grâce à nos vidéos en haute définition capturées par nos explorateurs.</p>
                        <button class="btn-cyber" style="padding: 15px 40px;">
                            <i class="fas fa-play"></i>
                            DÉCOUVRIR
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Modal -->
    <div class="modal-overlay" id="modal">
        <div class="modal">
            <div class="modal-header">
                <h3>RÉSERVATION</h3>
                <button class="modal-close" id="close-modal">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-content" id="modal-content">
                <!-- Content will be injected by JavaScript -->
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer id="contact">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-column">
                    <div class="footer-logo">CANADA</div>
                    <p>Agence de voyages d'exception spécialisée dans les expériences canadiennes uniques et sur mesure.</p>
                    <div class="newsletter-form">
                        <input type="email" class="newsletter-input" placeholder="VOTRE EMAIL">
                        <button class="btn-cyber" style="width: 100%;">S'INSCRIRE</button>
                    </div>
                </div>
                <div class="footer-column">
                    <h3>Destinations</h3>
                    <ul class="footer-links">
                        <li><a href="#">Rocheuses</a></li>
                        <li><a href="#">Grand Nord</a></li>
                        <li><a href="#">Côte Ouest</a></li>
                        <li><a href="#">Québec</a></li>
                        <li><a href="#">Ontario</a></li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h3>Expériences</h3>
                    <ul class="footer-links">
                        <li><a href="#">Aventure</a></li>
                        <li><a href="#">Luxe</a></li>
                        <li><a href="#">Culture</a></li>
                        <li><a href="#">Nature</a></li>
                        <li><a href="#">Gastronomie</a></li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h3>Contact</h3>
                    <ul class="footer-links">
                        <li><a href="#">+1 514 123 4567</a></li>
                        <li><a href="#">contact@canada-voyages.com</a></li>
                        <li><a href="#">Montréal, QC</a></li>
                        <li><a href="#">Carrières</a></li>
                        <li><a href="#">Presse</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <div class="copyright">© 2024 CANADA Voyages. Tous droits réservés.</div>
                <div class="social-links">
                    <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="social-link"><i class="fab fa-youtube"></i></a>
                    <a href="#" class="social-link"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        // Données des destinations
        const destinations = [
            {
                id: 1,
                title: "EXPÉDITION ARCTIQUE",
                region: "nord",
                location: "NUNAVUT • CANADA",
                description: "Voyage d'exploration dans l'Arctique canadien. Observation des ours polaires, des aurores boréales et découverte des communautés inuites.",
                price: 5200,
                image: "https://images.unsplash.com/photo-1578662996442-48f60103fc96?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80",
                video: "https://www.youtube.com/embed/fVsONlc3OUY",
                highlights: ["Ours polaires", "Aurores boréales", "Culture inuite", "Expédition"],
                duration: "8 jours",
                season: "Septembre-Mars",
                features: ["EXPÉDITION", "PHOTO", "LUXE"]
            },
            {
                id: 2,
                title: "ROCHEUSES PREMIUM",
                region: "montagne",
                location: "BANFF • ALBERTA",
                description: "Expérience exclusive dans les Rocheuses avec hébergements design, guides privés et accès à des sites préservés du tourisme de masse.",
                price: 3200,
                image: "https://images.unsplash.com/photo-1503614472-8c93d56e92ce?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80",
                video: "https://www.youtube.com/embed/InyPq6cR5zs",
                highlights: ["Hébergement design", "Guide privé", "Sites exclusifs", "Gastronomie"],
                duration: "6 jours",
                season: "Mai-Octobre",
                features: ["LUXE", "MONTAGNE", "PRIVÉ"]
            },
            {
                id: 3,
                title: "URBAN EXPLORATION",
                region: "urbain",
                location: "MONTRÉAL • QUÉBEC",
                description: "Immersion dans les scènes artistiques, culinaires et musicales des villes canadiennes. Expérience culturelle intense et authentique.",
                price: 1800,
                image: "https://images.unsplash.com/photo-1548191657-6aa4c4c8e0e9?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80",
                video: "https://www.youtube.com/embed/moqj5wF6OeY",
                highlights: ["Art contemporain", "Gastronomie", "Musique live", "Architecture"],
                duration: "5 jours",
                season: "Toute l'année",
                features: ["URBAIN", "CULTURE", "DESIGN"]
            },
            {
                id: 4,
                title: "SURF PACIFIQUE",
                region: "aventure",
                location: "VANCOUVER ISLAND • BC",
                description: "Aventure surf sur les plages sauvages du Pacifique. Combiné avec randonnées côtières et observation de la faune marine.",
                price: 2400,
                image: "https://images.unsplash.com/photo-1544551763-46a013bb70d5?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80",
                video: "https://www.youtube.com/embed/7F2TMSx0R30",
                highlights: ["Surf", "Baleines", "Forêt tropicale", "Plages sauvages"],
                duration: "7 jours",
                season: "Avril-Octobre",
                features: ["SPORT", "NATURE", "AVENTURE"]
            },
            {
                id: 5,
                title: "SAFARI BORÉAL",
                region: "nord",
                location: "YUKON • CANADA",
                description: "Observation de la faune boréale dans son habitat naturel. Loups, caribous, orignaux et expérience de survie en forêt boréale.",
                price: 3800,
                image: "https://images.unsplash.com/photo-1521651201144-634f700b36ef?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80",
                video: "https://www.youtube.com/embed/fVsONlc3OUY",
                highlights: ["Faune boréale", "Photographie", "Survie", "Forêt boréale"],
                duration: "7 jours",
                season: "Juin-Septembre",
                features: ["FAUNE", "PHOTO", "NATURE"]
            },
            {
                id: 6,
                title: "ARCHITECTURE & DESIGN",
                region: "urbain",
                location: "TORONTO • ONTARIO",
                description: "Parcours des chefs-d'œuvre architecturaux et des quartiers design. Rencontres avec architectes et designers locaux.",
                price: 2100,
                image: "https://images.unsplash.com/photo-1528164344705-47542687000d?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80",
                video: "https://www.youtube.com/embed/A1QvJt6SX80",
                highlights: ["Architecture", "Design", "Art public", "Urbanisme"],
                duration: "4 jours",
                season: "Toute l'année",
                features: ["DESIGN", "URBAIN", "CULTURE"]
            }
        ];

        // Éléments DOM
        const destinationsContainer = document.getElementById('destinations-container');
        const controlButtons = document.querySelectorAll('.control-btn');
        const modal = document.getElementById('modal');
        const closeModal = document.getElementById('close-modal');
        const modalContent = document.getElementById('modal-content');

        // Variables
        let currentFilter = 'all';

        // Initialisation
        document.addEventListener('DOMContentLoaded', function() {
            renderDestinations(destinations);
            setupFilters();
            setupModal();
            setupEffects();
        });

        // Rendu des destinations
        function renderDestinations(destinationsArray) {
            destinationsContainer.innerHTML = '';
            
            destinationsArray.forEach(destination => {
                const card = document.createElement('div');
                card.className = `destination-card`;
                card.dataset.id = destination.id;
                card.dataset.region = destination.region;
                
                card.innerHTML = `
                    <div class="card-header">
                        <img src="${destination.image}" alt="${destination.title}" class="card-image" loading="lazy">
                        <div class="card-badge">${destination.region.toUpperCase()}</div>
                    </div>
                    <div class="card-content">
                        <div class="card-title">
                            <span>${destination.title}</span>
                        </div>
                        <div class="card-location">${destination.location}</div>
                        <p class="card-description">${destination.description}</p>
                        <div class="card-features">
                            ${destination.features.map(feature => `
                                <div class="feature">${feature}</div>
                            `).join('')}
                        </div>
                        <div class="card-footer">
                            <div class="card-price">$${destination.price}<span>/personne</span></div>
                            <div class="card-actions">
                                <button class="btn-card btn-outline" data-id="${destination.id}">
                                    <i class="fas fa-info-circle"></i>
                                    Détails
                                </button>
                                <button class="btn-card btn-primary" data-id="${destination.id}">
                                    <i class="fas fa-calendar-alt"></i>
                                    Réserver
                                </button>
                            </div>
                        </div>
                    </div>
                `;
                
                destinationsContainer.appendChild(card);
            });
            
            // Ajouter les écouteurs d'événements
            document.querySelectorAll('.btn-outline, .btn-primary').forEach(button => {
                button.addEventListener('click', function() {
                    const id = parseInt(this.dataset.id);
                    const destination = destinations.find(d => d.id === id);
                    
                    if (this.classList.contains('btn-primary')) {
                        openModal(destination, true);
                    } else {
                        openModal(destination, false);
                    }
                });
            });
            
            // Animation au scroll
            animateOnScroll();
        }

        // Configuration des filtres
        function setupFilters() {
            controlButtons.forEach(button => {
                button.addEventListener('click', function() {
                    // Mettre à jour l'état actif
                    controlButtons.forEach(btn => btn.classList.remove('active'));
                    this.classList.add('active');
                    
                    // Filtrer les destinations
                    currentFilter = this.dataset.filter;
                    
                    let filteredDestinations;
                    if (currentFilter === 'all') {
                        filteredDestinations = destinations;
                    } else {
                        filteredDestinations = destinations.filter(d => d.region === currentFilter);
                    }
                    
                    // Animation de sortie
                    const cards = document.querySelectorAll('.destination-card');
                    cards.forEach((card, index) => {
                        setTimeout(() => {
                            card.style.opacity = '0';
                            card.style.transform = 'translateY(20px)';
                        }, index * 50);
                    });
                    
                    // Rendu après animation
                    setTimeout(() => {
                        renderDestinations(filteredDestinations);
                    }, 300);
                });
            });
        }

        // Configuration de la modal
        function setupModal() {
            closeModal.addEventListener('click', function() {
                modal.classList.remove('active');
            });
            
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    modal.classList.remove('active');
                }
            });
            
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    modal.classList.remove('active');
                }
            });
        }

        // Effets visuels
        function setupEffects() {
            // Effet de glitch aléatoire sur le titre
            const heroTitle = document.querySelector('.hero-content h1');
            setInterval(() => {
                if (Math.random() > 0.7) {
                    heroTitle.style.textShadow = `0 0 10px ${Math.random() > 0.5 ? 'var(--neon-blue)' : 'var(--neon-pink)'}`;
                    setTimeout(() => {
                        heroTitle.style.textShadow = 'none';
                    }, 100);
                }
            }, 3000);
            
            // Effet de scan sur les cartes au hover
            document.addEventListener('mousemove', function(e) {
                const cards = document.querySelectorAll('.destination-card');
                cards.forEach(card => {
                    const rect = card.getBoundingClientRect();
                    if (
                        e.clientX >= rect.left &&
                        e.clientX <= rect.right &&
                        e.clientY >= rect.top &&
                        e.clientY <= rect.bottom
                    ) {
                        const x = ((e.clientX - rect.left) / rect.width) * 100;
                        card.style.setProperty('--scan-x', `${x}%`);
                    }
                });
            });
        }

        // Animation au scroll
        function animateOnScroll() {
            const cards = document.querySelectorAll('.destination-card');
            cards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(30px)';
                
                setTimeout(() => {
                    card.style.transition = 'all 0.6s cubic-bezier(0.23, 1, 0.32, 1)';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, 100 + index * 100);
            });
        }

        // Ouvrir la modal
        function openModal(destination, showForm = false) {
            let formHTML = '';
            if (showForm) {
                formHTML = `
                    <div class="booking-form">
                        <h4 style="margin-bottom: 20px; text-transform: uppercase; letter-spacing: 2px;">RÉSERVER</h4>
                        <form id="reservation-form">
                            <div class="form-grid">
                                <div class="form-group">
                                    <label for="booking-date">DATE DE DÉPART</label>
                                    <input type="date" id="booking-date" required>
                                </div>
                                <div class="form-group">
                                    <label for="travelers">VOYAGEURS</label>
                                    <select id="travelers" required>
                                        <option value="1">1 PERSONNE</option>
                                        <option value="2" selected>2 PERSONNES</option>
                                        <option value="3">3 PERSONNES</option>
                                        <option value="4">4 PERSONNES</option>
                                        <option value="5+">GROUPE</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-grid">
                                <div class="form-group">
                                    <label for="full-name">NOM COMPLET</label>
                                    <input type="text" id="full-name" placeholder="NOM PRÉNOM" required>
                                </div>
                                <div class="form-group">
                                    <label for="email">EMAIL</label>
                                    <input type="email" id="email" placeholder="EMAIL@DOMAINE.COM" required>
                                </div>
                            </div>
                            <button type="submit" class="btn-submit">
                                <i class="fas fa-paper-plane"></i>
                                CONFIRMER LA RÉSERVATION
                            </button>
                        </form>
                    </div>
                `;
            } else {
                formHTML = `
                    <div class="booking-form">
                        <button class="btn-submit" id="show-booking-form">
                            <i class="fas fa-calendar-check"></i>
                            RÉSERVER MAINTENANT
                        </button>
                    </div>
                `;
            }
            
            modalContent.innerHTML = `
                <div class="modal-media">
                    <img src="${destination.image}" alt="${destination.title}">
                </div>
                <div class="modal-info">
                    <h3>${destination.title}</h3>
                    <div class="modal-location">
                        <i class="fas fa-map-marker-alt"></i>
                        ${destination.location}
                    </div>
                    <p class="modal-description">${destination.description}</p>
                    
                    <div class="modal-highlights">
                        ${destination.highlights.map(highlight => `
                            <div class="highlight">
                                ${highlight}
                            </div>
                        `).join('')}
                    </div>
                    
                    <div style="margin-bottom: 30px;">
                        <div><strong>DURÉE:</strong> ${destination.duration}</div>
                        <div><strong>SAISON:</strong> ${destination.season}</div>
                    </div>
                    
                    <div class="modal-price">$${destination.price}<span>/PERSONNE</span></div>
                    
                    ${formHTML}
                </div>
            `;
            
            modal.classList.add('active');
            
            // Gestion du formulaire
            if (showForm) {
                document.getElementById('reservation-form').addEventListener('submit', function(e) {
                    e.preventDefault();
                    // Animation de confirmation
                    this.innerHTML = `
                        <div style="text-align: center; padding: 40px 20px;">
                            <div style="font-size: 4rem; color: var(--neon-blue); margin-bottom: 20px;">✓</div>
                            <h3 style="margin-bottom: 10px; text-transform: uppercase;">RÉSERVATION CONFIRMÉE</h3>
                            <p style="color: var(--gray-light);">Nous vous contacterons sous 24h.</p>
                        </div>
                    `;
                    setTimeout(() => {
                        modal.classList.remove('active');
                    }, 3000);
                });
            } else {
                const showBookingBtn = document.getElementById('show-booking-form');
                if (showBookingBtn) {
                    showBookingBtn.addEventListener('click', function() {
                        openModal(destination, true);
                    });
                }
            }
        }

        // Effet de parallaxe sur le grid background
        document.addEventListener('mousemove', function(e) {
            const x = e.clientX / window.innerWidth;
            const y = e.clientY / window.innerHeight;
            
            document.querySelector('.grid-bg').style.transform = `translate(${x * 20}px, ${y * 20}px)`;
        });
    </script>
    
    <script>
function sendHeight() {
    const height = document.body.scrollHeight;
    window.parent.postMessage({
        type: 'setHeight',
        iframeId: 'iframe-page-travel-4',
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