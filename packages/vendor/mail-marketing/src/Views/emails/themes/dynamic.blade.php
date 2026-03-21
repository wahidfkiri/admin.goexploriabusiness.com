<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subject }}</title>
    <style>
        /* Reset dynamique */
        body, table, td, p, a {
            font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.6;
            color: #2d3748;
            margin: 0;
            padding: 0;
        }
        
        body {
            background: linear-gradient(135deg, #f6d5f7 0%, #fbe9d7 100%);
            padding: 40px 20px;
        }
        
        /* Container principal avec animation */
        .email-wrapper {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 30px;
            overflow: hidden;
            box-shadow: 0 30px 60px -15px rgba(0,0,0,0.3);
            animation: float 6s ease-in-out infinite;
        }
        
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }
        
        /* Header dynamique */
        .email-header {
            background: linear-gradient(135deg, #ff6b6b 0%, #4ecdc4 50%, #45b7d1 100%);
            padding: 50px 40px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .email-header::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: repeating-linear-gradient(
                45deg,
                transparent,
                transparent 20px,
                rgba(255,255,255,0.1) 20px,
                rgba(255,255,255,0.1) 40px
            );
            animation: move 20s linear infinite;
        }
        
        @keyframes move {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        
        .header-logo {
            max-width: 150px;
            margin-bottom: 20px;
            position: relative;
            z-index: 1;
            animation: pulse 2s ease-in-out infinite;
        }
        
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
        
        .header-title {
            color: #ffffff;
            font-size: 36px;
            font-weight: 800;
            margin: 0;
            text-shadow: 3px 3px 0 rgba(0,0,0,0.1);
            position: relative;
            z-index: 1;
            animation: slideIn 1s ease;
        }
        
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .header-subtitle {
            color: rgba(255,255,255,0.9);
            font-size: 18px;
            margin-top: 10px;
            position: relative;
            z-index: 1;
            font-weight: 300;
        }
        
        /* Content */
        .email-content {
            padding: 50px 40px;
        }
        
        .greeting {
            font-size: 20px;
            color: #2d3748;
            margin-bottom: 25px;
            padding: 15px 20px;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 15px;
            border-left: 5px solid #ff6b6b;
            animation: glow 2s ease-in-out infinite;
        }
        
        @keyframes glow {
            0% { box-shadow: 0 0 0 0 rgba(255,107,107,0.4); }
            50% { box-shadow: 0 0 20px 0 rgba(255,107,107,0.2); }
            100% { box-shadow: 0 0 0 0 rgba(255,107,107,0.4); }
        }
        
        .greeting strong {
            background: linear-gradient(135deg, #ff6b6b, #4ecdc4);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .message {
            font-size: 16px;
            color: #4a5568;
            margin-bottom: 35px;
            line-height: 1.8;
        }
        
        /* Bouton dynamique */
        .button-container {
            text-align: center;
            margin: 45px 0;
        }
        
        .cta-button {
            display: inline-block;
            padding: 18px 40px;
            background: linear-gradient(135deg, #ff6b6b 0%, #4ecdc4 100%);
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 50px;
            font-weight: 700;
            font-size: 18px;
            text-transform: uppercase;
            letter-spacing: 1px;
            box-shadow: 0 10px 20px -5px rgba(255,107,107,0.5);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .cta-button::after {
            content: '→';
            position: absolute;
            right: -30px;
            top: 50%;
            transform: translateY(-50%);
            transition: right 0.3s ease;
        }
        
        .cta-button:hover::after {
            right: 20px;
        }
        
        .cta-button:hover {
            transform: scale(1.05);
            box-shadow: 0 15px 30px -5px rgba(255,107,107,0.7);
            padding-right: 60px;
        }
        
        /* Grille colorée */
        .color-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin: 40px 0;
        }
        
        .color-card {
            background: linear-gradient(135deg, #ff6b6b, #ff8e8e);
            border-radius: 15px;
            padding: 20px 10px;
            text-align: center;
            color: white;
            transition: all 0.3s ease;
            animation: bounceIn 0.5s ease;
        }
        
        .color-card:nth-child(2) {
            background: linear-gradient(135deg, #4ecdc4, #6ee7db);
        }
        
        .color-card:nth-child(3) {
            background: linear-gradient(135deg, #45b7d1, #67d5e8);
        }
        
        @keyframes bounceIn {
            from {
                opacity: 0;
                transform: scale(0.3) translateY(30px);
            }
            50% {
                opacity: 1;
                transform: scale(1.05) translateY(-10px);
            }
            to {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }
        
        .color-card:hover {
            transform: translateY(-10px) rotate(2deg);
            box-shadow: 0 20px 30px -10px rgba(0,0,0,0.3);
        }
        
        .color-icon {
            font-size: 36px;
            margin-bottom: 10px;
        }
        
        .color-value {
            font-size: 28px;
            font-weight: 800;
            margin-bottom: 5px;
        }
        
        .color-label {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
            opacity: 0.9;
        }
        
        /* Timeline dynamique */
        .timeline {
            margin: 40px 0;
        }
        
        .timeline-item {
            display: flex;
            gap: 20px;
            margin-bottom: 25px;
            animation: slideInRight 0.5s ease;
        }
        
        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(-30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        .timeline-badge {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #ff6b6b, #4ecdc4);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 800;
            font-size: 20px;
            flex-shrink: 0;
            box-shadow: 0 5px 10px rgba(0,0,0,0.1);
        }
        
        .timeline-content {
            flex: 1;
            background: #f8f9fa;
            padding: 15px;
            border-radius: 15px;
            transition: all 0.3s ease;
        }
        
        .timeline-content:hover {
            background: #e9ecef;
            transform: translateX(5px);
        }
        
        .timeline-title {
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 5px;
        }
        
        .timeline-text {
            color: #718096;
            font-size: 14px;
        }
        
        /* Footer dynamique */
        .email-footer {
            background: linear-gradient(135deg, #2d3748 0%, #1a202c 100%);
            padding: 40px;
            text-align: center;
        }
        
        .footer-links {
            margin-bottom: 25px;
        }
        
        .footer-links a {
            color: #a0aec0;
            text-decoration: none;
            font-size: 14px;
            margin: 0 12px;
            transition: all 0.3s ease;
            display: inline-block;
        }
        
        .footer-links a:hover {
            color: #4ecdc4;
            transform: translateY(-2px);
        }
        
        .footer-social {
            margin: 25px 0;
        }
        
        .social-icon {
            display: inline-flex;
            width: 44px;
            height: 44px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
            align-items: center;
            justify-content: center;
            margin: 0 8px;
            color: #a0aec0;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .social-icon:hover {
            background: linear-gradient(135deg, #ff6b6b, #4ecdc4);
            color: white;
            transform: rotate(360deg) scale(1.1);
        }
        
        .footer-address {
            color: #718096;
            font-size: 13px;
            line-height: 1.8;
            margin-top: 20px;
        }
        
        .footer-copyright {
            color: #4a5568;
            font-size: 12px;
            margin-top: 20px;
        }
        
        .unsubscribe-link {
            color: #718096;
            font-size: 12px;
            text-decoration: none;
            border-bottom: 1px dashed #718096;
            transition: all 0.3s ease;
        }
        
        .unsubscribe-link:hover {
            color: #ff6b6b;
            border-bottom-color: #ff6b6b;
        }
        
        /* Tracking pixel */
        .tracking-pixel {
            width: 1px;
            height: 1px;
            opacity: 0;
            position: absolute;
        }
        
        /* Responsive */
        @media screen and (max-width: 600px) {
            body {
                padding: 20px 10px;
            }
            
            .email-header {
                padding: 40px 20px;
            }
            
            .header-title {
                font-size: 28px;
            }
            
            .email-content {
                padding: 30px 20px;
            }
            
            .color-grid {
                grid-template-columns: 1fr;
            }
            
            .footer-links a {
                display: block;
                margin: 10px 0;
            }
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="email-wrapper">
        <!-- Header -->
        <div class="email-header">
            @if(isset($logo) && $logo)
                <img src="{{ $logo }}" alt="Logo" class="header-logo">
            @endif
            <h1 class="header-title">{{ $subject }}</h1>
            @if(isset($headerSubtitle))
                <div class="header-subtitle">{{ $headerSubtitle }}</div>
            @endif
        </div>
        
        <!-- Content -->
        <div class="email-content">
            <div class="greeting">
                🚀 Hello <strong>{{ $prenom ?? $nom ?? 'there' }}</strong> ! Prêt pour l'aventure ?
            </div>
            
            <div class="message">
                {!! $content !!}
            </div>
            
            <!-- Color Grid -->
            @if(isset($stats) && count($stats) > 0)
                <div class="color-grid">
                    @foreach($stats as $stat)
                        <div class="color-card">
                            <div class="color-icon">{!! $stat['icon'] ?? '✨' !!}</div>
                            <div class="color-value">{{ $stat['value'] }}</div>
                            <div class="color-label">{{ $stat['label'] }}</div>
                        </div>
                    @endforeach
                </div>
            @endif
            
            <!-- Timeline -->
            @if(isset($timeline) && count($timeline) > 0)
                <div class="timeline">
                    @foreach($timeline as $index => $item)
                        <div class="timeline-item">
                            <div class="timeline-badge">{{ $index + 1 }}</div>
                            <div class="timeline-content">
                                <div class="timeline-title">{{ $item['title'] }}</div>
                                <div class="timeline-text">{{ $item['description'] }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
            
            <!-- Call to action -->
            @if(isset($ctaUrl) && isset($ctaText))
                <div class="button-container">
                    <a href="{{ $tracking['click_tracker'] ?? $ctaUrl }}" class="cta-button">
                        {{ $ctaText }}
                    </a>
                </div>
            @endif
        </div>
        
        <!-- Footer -->
        <div class="email-footer">
            <div class="footer-links">
                <a href="{{ $tracking['unsubscribe_url'] }}">Désabonnement</a>
                <a href="{{ url('/') }}">Site web</a>
                <a href="{{ route('contact') }}">Support 24/7</a>
                <a href="{{ route('faq') }}">FAQ</a>
            </div>
            
            @if(isset($socialLinks) && count($socialLinks) > 0)
                <div class="footer-social">
                    @foreach($socialLinks as $social)
                        <a href="{{ $social['url'] }}" class="social-icon">
                            <i class="fab fa-{{ $social['platform'] }}"></i>
                        </a>
                    @endforeach
                </div>
            @endif
            
            <div class="footer-address">
                {{ config('app.name') }} — Innovons ensemble ! 🚀<br>
                @if(config('mail-marketing.address'))
                    {{ config('mail-marketing.address') }}
                @endif
            </div>
            
            <div class="footer-copyright">
                © {{ date('Y') }} {{ config('app.name') }}. Made with ❤️
            </div>
            
            <div style="margin-top: 20px;">
                <a href="{{ $tracking['unsubscribe_url'] }}" class="unsubscribe-link">
                    🔕 Ne plus recevoir nos emails
                </a>
            </div>
        </div>
    </div>
    
    <!-- Tracking pixel -->
    <img src="{{ $tracking['open_tracker'] }}" width="1" height="1" alt="" class="tracking-pixel">
</body>
</html>