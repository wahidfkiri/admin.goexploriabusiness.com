<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subject }}</title>
    <style>
        /* Reset élégant */
        body, table, td, p, a {
            font-family: 'Cormorant Garamond', 'Times New Roman', serif;
            line-height: 1.8;
            color: #2c3e50;
            margin: 0;
            padding: 0;
        }
        
        body {
            background-color: #f5efe6;
            padding: 40px 20px;
        }
        
        /* Container principal */
        .email-wrapper {
            max-width: 580px;
            margin: 0 auto;
            background-color: #ffffff;
            border: 1px solid #e8d9c5;
            box-shadow: 0 20px 40px -15px rgba(0,0,0,0.1);
        }
        
        /* Header élégant */
        .email-header {
            padding: 60px 50px 40px;
            text-align: center;
            border-bottom: 2px solid #c7a17b;
            position: relative;
        }
        
        .email-header::after {
            content: '✦';
            position: absolute;
            bottom: -12px;
            left: 50%;
            transform: translateX(-50%);
            background: #ffffff;
            color: #c7a17b;
            font-size: 20px;
            padding: 0 15px;
        }
        
        .header-logo {
            max-width: 200px;
            margin-bottom: 30px;
            filter: sepia(20%);
        }
        
        .header-title {
            color: #8b6b4d;
            font-size: 36px;
            font-weight: 400;
            margin: 0;
            letter-spacing: 2px;
            text-transform: uppercase;
            font-family: 'Cormorant Garamond', serif;
        }
        
        .header-subtitle {
            color: #c7a17b;
            font-size: 18px;
            margin-top: 15px;
            font-style: italic;
            font-family: 'Cormorant Garamond', serif;
        }
        
        /* Content */
        .email-content {
            padding: 50px 50px 40px;
        }
        
        .greeting {
            font-size: 20px;
            color: #2c3e50;
            margin-bottom: 30px;
            font-style: italic;
            border-left: 3px solid #c7a17b;
            padding-left: 20px;
        }
        
        .greeting strong {
            color: #8b6b4d;
            font-weight: 600;
            font-style: normal;
        }
        
        .message {
            font-size: 17px;
            color: #4a5568;
            margin-bottom: 40px;
            line-height: 1.9;
        }
        
        /* Bouton élégant */
        .button-container {
            text-align: center;
            margin: 50px 0 40px;
        }
        
        .cta-button {
            display: inline-block;
            padding: 16px 48px;
            background: transparent;
            color: #8b6b4d !important;
            text-decoration: none;
            border: 2px solid #c7a17b;
            font-size: 16px;
            letter-spacing: 3px;
            text-transform: uppercase;
            font-family: 'Cormorant Garamond', serif;
            font-weight: 600;
            transition: all 0.4s ease;
            position: relative;
            overflow: hidden;
        }
        
        .cta-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(199, 161, 123, 0.2), transparent);
            transition: left 0.6s ease;
        }
        
        .cta-button:hover::before {
            left: 100%;
        }
        
        .cta-button:hover {
            background: #f5efe6;
            border-color: #8b6b4d;
        }
        
        /* Section avec séparateur */
        .separator {
            text-align: center;
            margin: 40px 0;
            color: #c7a17b;
            font-size: 14px;
            letter-spacing: 2px;
        }
        
        .separator span {
            display: inline-block;
            padding: 0 15px;
            background: #ffffff;
            position: relative;
            z-index: 1;
        }
        
        .separator::before {
            content: '';
            position: absolute;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, #c7a17b, transparent);
            top: 50%;
            z-index: 0;
        }
        
        /* Highlights */
        .highlights {
            margin: 40px 0;
        }
        
        .highlight-item {
            display: flex;
            align-items: center;
            gap: 20px;
            padding: 20px 0;
            border-bottom: 1px dashed #e8d9c5;
        }
        
        .highlight-item:last-child {
            border-bottom: none;
        }
        
        .highlight-icon {
            width: 50px;
            height: 50px;
            background: #f5efe6;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #8b6b4d;
            font-size: 22px;
        }
        
        .highlight-content {
            flex: 1;
        }
        
        .highlight-title {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 5px;
            font-size: 18px;
        }
        
        .highlight-text {
            color: #666;
            font-size: 15px;
            line-height: 1.6;
        }
        
        /* Citation */
        .testimonial {
            margin: 50px 0;
            padding: 30px;
            background: #faf7f2;
            border-radius: 4px;
            position: relative;
            font-style: italic;
        }
        
        .testimonial::before {
            content: '"';
            font-size: 80px;
            color: #c7a17b;
            opacity: 0.2;
            position: absolute;
            top: -10px;
            left: 10px;
            font-family: serif;
        }
        
        .testimonial-text {
            font-size: 18px;
            color: #4a5568;
            margin-bottom: 15px;
            position: relative;
            z-index: 1;
        }
        
        .testimonial-author {
            text-align: right;
            color: #8b6b4d;
            font-weight: 600;
            font-style: normal;
        }
        
        /* Footer élégant */
        .email-footer {
            padding: 40px 50px;
            background: #faf7f2;
            text-align: center;
            border-top: 2px solid #e8d9c5;
        }
        
        .footer-links {
            margin-bottom: 25px;
        }
        
        .footer-links a {
            color: #8b6b4d;
            text-decoration: none;
            font-size: 15px;
            margin: 0 15px;
            letter-spacing: 1px;
            transition: color 0.3s ease;
        }
        
        .footer-links a:hover {
            color: #2c3e50;
        }
        
        .footer-social {
            margin: 25px 0;
        }
        
        .social-icon {
            display: inline-flex;
            width: 36px;
            height: 36px;
            border: 1px solid #c7a17b;
            border-radius: 50%;
            align-items: center;
            justify-content: center;
            margin: 0 5px;
            color: #8b6b4d;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .social-icon:hover {
            background: #c7a17b;
            color: white;
        }
        
        .footer-address {
            color: #666;
            font-size: 14px;
            line-height: 1.8;
            margin-top: 20px;
            font-style: italic;
        }
        
        .footer-copyright {
            color: #999;
            font-size: 13px;
            margin-top: 20px;
        }
        
        .unsubscribe-link {
            color: #999;
            font-size: 13px;
            text-decoration: none;
            border-bottom: 1px dotted #999;
            transition: all 0.3s ease;
        }
        
        .unsubscribe-link:hover {
            color: #ef476f;
            border-bottom-color: #ef476f;
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
                padding: 40px 25px;
            }
            
            .header-title {
                font-size: 28px;
            }
            
            .email-content {
                padding: 30px 25px;
            }
            
            .highlight-item {
                flex-direction: column;
                text-align: center;
            }
            
            .footer-links a {
                display: block;
                margin: 10px 0;
            }
        }
    </style>
    <!-- Lien vers Font Awesome pour les icônes (optionnel) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="email-wrapper">
        <!-- Header -->
        <div class="email-header">
            @if(isset($logo) && $logo)
                <img src="{{ $logo }}" alt="Logo" class="header-logo">
            @else
                <div style="font-size: 24px; color: #8b6b4d; letter-spacing: 5px;">✦ {{ config('app.name') }} ✦</div>
            @endif
            <h1 class="header-title">{{ $subject }}</h1>
            @if(isset($headerSubtitle))
                <div class="header-subtitle">{{ $headerSubtitle }}</div>
            @endif
        </div>
        
        <!-- Content -->
        <div class="email-content">
            <div class="greeting">
                Chère <strong>{{ $prenom ?? $nom ?? 'lectrice' }}</strong>, cher <strong>{{ $prenom ?? $nom ?? 'lecteur' }}</strong>,
            </div>
            
            <div class="message">
                {!! $content !!}
            </div>
            
            <!-- Separator -->
            <div class="separator">
                <span>✦ ✦ ✦</span>
            </div>
            
            <!-- Highlights -->
            @if(isset($highlights) && count($highlights) > 0)
                <div class="highlights">
                    @foreach($highlights as $highlight)
                        <div class="highlight-item">
                            <div class="highlight-icon">{!! $highlight['icon'] ?? '✦' !!}</div>
                            <div class="highlight-content">
                                <div class="highlight-title">{{ $highlight['title'] }}</div>
                                <div class="highlight-text">{{ $highlight['description'] }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
            
            <!-- Testimonial -->
            @if(isset($testimonial))
                <div class="testimonial">
                    <div class="testimonial-text">"{{ $testimonial['text'] }}"</div>
                    <div class="testimonial-author">— {{ $testimonial['author'] }}</div>
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
                <a href="{{ $tracking['unsubscribe_url'] }}">Se désabonner</a>
                <a href="{{ url('/') }}">Notre univers</a>
                <a href="{{ route('contact') }}">Écrire</a>
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
                {{ config('app.name') }} — L'élégance à votre service<br>
                @if(config('mail-marketing.address'))
                    {{ config('mail-marketing.address') }}
                @endif
            </div>
            
            <div class="footer-copyright">
                © {{ date('Y') }} — Tous droits réservés
            </div>
            
            <div style="margin-top: 25px;">
                <a href="{{ $tracking['unsubscribe_url'] }}" class="unsubscribe-link">
                    ✦ Se retirer de la liste ✦
                </a>
            </div>
        </div>
    </div>
    
    <!-- Tracking pixel -->
    <img src="{{ $tracking['open_tracker'] }}" width="1" height="1" alt="" class="tracking-pixel">
</body>
</html>