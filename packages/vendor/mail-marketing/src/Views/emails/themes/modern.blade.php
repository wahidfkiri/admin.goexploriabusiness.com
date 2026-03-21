<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subject }}</title>
    <style>
        /* Reset */
        body, table, td, p, a {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            line-height: 1.6;
            color: #1e293b;
            margin: 0;
            padding: 0;
        }
        
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            padding: 40px 20px;
        }
        
        /* Container principal */
        .email-wrapper {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }
        
        /* Header avec dégradé */
        .email-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 48px 40px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .email-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: rotate 20s linear infinite;
        }
        
        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        
        .header-logo {
            max-width: 180px;
            margin-bottom: 24px;
            position: relative;
            z-index: 1;
        }
        
        .header-title {
            color: #ffffff;
            font-size: 32px;
            font-weight: 700;
            margin: 0;
            letter-spacing: -0.5px;
            line-height: 1.2;
            position: relative;
            z-index: 1;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .header-subtitle {
            color: rgba(255, 255, 255, 0.9);
            font-size: 16px;
            margin-top: 12px;
            position: relative;
            z-index: 1;
        }
        
        /* Content */
        .email-content {
            padding: 48px 40px;
        }
        
        .greeting {
            font-size: 18px;
            color: #1e293b;
            margin-bottom: 24px;
            padding-bottom: 16px;
            border-bottom: 2px solid #f1f5f9;
        }
        
        .greeting strong {
            color: #667eea;
            font-weight: 600;
        }
        
        .message {
            font-size: 16px;
            color: #475569;
            margin-bottom: 32px;
        }
        
        /* Bouton CTA moderne */
        .button-container {
            text-align: center;
            margin: 40px 0;
        }
        
        .cta-button {
            display: inline-block;
            padding: 16px 32px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 50px;
            font-weight: 600;
            font-size: 16px;
            letter-spacing: 0.5px;
            box-shadow: 0 10px 20px -5px rgba(102, 126, 234, 0.4);
            transition: all 0.3s ease;
        }
        
        .cta-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 30px -5px rgba(102, 126, 234, 0.6);
        }
        
        /* Cartes de fonctionnalités */
        .features-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin: 40px 0;
        }
        
        .feature-card {
            background: #f8fafc;
            border-radius: 16px;
            padding: 24px 16px;
            text-align: center;
            transition: all 0.3s ease;
        }
        
        .feature-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 20px -10px rgba(0,0,0,0.1);
        }
        
        .feature-icon {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
            color: white;
            font-size: 24px;
        }
        
        .feature-title {
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 8px;
        }
        
        .feature-description {
            font-size: 13px;
            color: #64748b;
            line-height: 1.5;
        }
        
        /* Section statistiques */
        .stats-section {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border-radius: 16px;
            padding: 32px;
            margin: 40px 0;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 24px;
        }
        
        .stat-item {
            text-align: center;
        }
        
        .stat-value {
            font-size: 36px;
            font-weight: 700;
            color: #667eea;
            line-height: 1;
            margin-bottom: 8px;
        }
        
        .stat-label {
            font-size: 14px;
            color: #475569;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        /* Footer moderne */
        .email-footer {
            background: #1e293b;
            padding: 48px 40px;
            text-align: center;
        }
        
        .footer-links {
            margin-bottom: 24px;
        }
        
        .footer-links a {
            color: #94a3b8;
            text-decoration: none;
            font-size: 14px;
            margin: 0 12px;
            transition: color 0.3s ease;
        }
        
        .footer-links a:hover {
            color: #667eea;
        }
        
        .footer-social {
            margin: 24px 0;
        }
        
        .social-icon {
            display: inline-flex;
            width: 40px;
            height: 40px;
            background: rgba(255,255,255,0.1);
            border-radius: 12px;
            align-items: center;
            justify-content: center;
            margin: 0 6px;
            color: #94a3b8;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .social-icon:hover {
            background: #667eea;
            color: white;
            transform: translateY(-3px);
        }
        
        .footer-address {
            color: #64748b;
            font-size: 13px;
            line-height: 1.8;
            margin-top: 24px;
        }
        
        .footer-copyright {
            color: #475569;
            font-size: 12px;
            margin-top: 24px;
        }
        
        .unsubscribe-link {
            color: #64748b;
            font-size: 12px;
            text-decoration: underline;
            transition: color 0.3s ease;
        }
        
        .unsubscribe-link:hover {
            color: #ef476f;
        }
        
        /* Tracking pixel */
        .tracking-pixel {
            width: 1px;
            height: 1px;
            opacity: 0;
            position: absolute;
            pointer-events: none;
        }
        
        /* Responsive */
        @media screen and (max-width: 600px) {
            body {
                padding: 20px 10px;
            }
            
            .email-header {
                padding: 32px 20px;
            }
            
            .header-title {
                font-size: 24px;
            }
            
            .email-content {
                padding: 32px 20px;
            }
            
            .features-grid {
                grid-template-columns: 1fr;
                gap: 16px;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
                gap: 16px;
            }
            
            .footer-links a {
                display: block;
                margin: 10px 0;
            }
        }
    </style>
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
                Bonjour <strong>{{ $prenom ?? $nom ?? 'cher abonné' }}</strong>,
            </div>
            
            <div class="message">
                {!! $content !!}
            </div>
            
            <!-- Features Grid (if provided) -->
            @if(isset($features) && count($features) > 0)
                <div class="features-grid">
                    @foreach($features as $feature)
                        <div class="feature-card">
                            <div class="feature-icon">{!! $feature['icon'] ?? '✨' !!}</div>
                            <div class="feature-title">{{ $feature['title'] }}</div>
                            <div class="feature-description">{{ $feature['description'] }}</div>
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
            
            <!-- Stats Section -->
            @if(isset($stats) && count($stats) > 0)
                <div class="stats-section">
                    <div class="stats-grid">
                        @foreach($stats as $stat)
                            <div class="stat-item">
                                <div class="stat-value">{{ $stat['value'] }}</div>
                                <div class="stat-label">{{ $stat['label'] }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
        
        <!-- Footer -->
        <div class="email-footer">
            <div class="footer-links">
                <a href="{{ $tracking['unsubscribe_url'] }}">Se désabonner</a>
                <a href="{{ url('/') }}">Notre site</a>
                <a href="{{ url('contact') }}">Contact</a>
                <a href="{{ url('privacy') }}">Confidentialité</a>
            </div>
            
            @if(isset($socialLinks) && count($socialLinks) > 0)
                <div class="footer-social">
                    @foreach($socialLinks as $social)
                        <a href="{{ $social['url'] }}" class="social-icon" style="background: {{ $social['color'] ?? 'rgba(255,255,255,0.1)' }};">
                            <i class="fab fa-{{ $social['platform'] }}"></i>
                        </a>
                    @endforeach
                </div>
            @endif
            
            <div class="footer-address">
                {{ config('app.name') }}<br>
                @if(config('mail-marketing.address'))
                    {{ config('mail-marketing.address') }}<br>
                @endif
                @if(config('mail-marketing.phone'))
                    Tél: {{ config('mail-marketing.phone') }}
                @endif
            </div>
            
            <div class="footer-copyright">
                © {{ date('Y') }} {{ config('app.name') }}. Tous droits réservés.
            </div>
            
            <div style="margin-top: 20px;">
                <a href="{{ $tracking['unsubscribe_url'] }}" class="unsubscribe-link">
                    Vous ne souhaitez plus recevoir nos emails ?
                </a>
            </div>
        </div>
    </div>
    
    <!-- Tracking pixel -->
    <img src="{{ $tracking['open_tracker'] }}" width="1" height="1" alt="" class="tracking-pixel">
</body>
</html>