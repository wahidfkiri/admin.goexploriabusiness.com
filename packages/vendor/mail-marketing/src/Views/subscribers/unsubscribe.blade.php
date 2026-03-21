<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="robots" content="noindex, nofollow">
    <title>Désabonnement - {{ config('app.name', 'Newsletter') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Font Awesome 6 (CDN) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
            overflow-x: hidden;
        }
        
        /* Background animated elements */
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at 20% 80%, rgba(255,255,255,0.1) 0%, transparent 50%);
            pointer-events: none;
        }
        
        body::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at 80% 20%, rgba(255,255,255,0.1) 0%, transparent 50%);
            pointer-events: none;
        }
        
        /* Floating particles */
        .particle {
            position: absolute;
            background: rgba(255,255,255,0.2);
            border-radius: 50%;
            pointer-events: none;
            animation: float 20s infinite linear;
        }
        
        @keyframes float {
            0% {
                transform: translateY(100vh) rotate(0deg);
                opacity: 0;
            }
            10% {
                opacity: 0.5;
            }
            90% {
                opacity: 0.5;
            }
            100% {
                transform: translateY(-100vh) rotate(360deg);
                opacity: 0;
            }
        }
        
        /* Main Card */
        .unsubscribe-card {
            position: relative;
            z-index: 10;
            background: white;
            border-radius: 40px;
            max-width: 520px;
            width: 100%;
            padding: 48px 40px;
            text-align: center;
            box-shadow: 0 40px 60px -20px rgba(0,0,0,0.3), 0 0 0 1px rgba(255,255,255,0.1);
            animation: fadeInUp 0.6s cubic-bezier(0.2, 0.9, 0.4, 1.1);
        }
        
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
        
        /* Logo/Brand */
        .brand {
            margin-bottom: 32px;
        }
        
        .brand-icon {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
            box-shadow: 0 10px 20px -5px rgba(102,126,234,0.4);
        }
        
        .brand-icon i {
            font-size: 32px;
            color: white;
        }
        
        .brand-name {
            font-size: 24px;
            font-weight: 700;
            background: linear-gradient(135deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        /* Success Animation */
        .success-animation {
            margin-bottom: 24px;
        }
        
        .checkmark {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            display: block;
            stroke-width: 2;
            stroke: #06b48a;
            stroke-miterlimit: 10;
            margin: 0 auto;
            animation: fill .4s ease-in-out .4s forwards, scale .3s ease-in-out .9s both;
        }
        
        .checkmark-circle {
            stroke-dasharray: 166;
            stroke-dashoffset: 166;
            stroke-width: 2;
            stroke-miterlimit: 10;
            stroke: #06b48a;
            fill: none;
            animation: stroke 0.6s cubic-bezier(0.65, 0, 0.45, 1) forwards;
        }
        
        .checkmark-check {
            transform-origin: 50% 50%;
            stroke-dasharray: 48;
            stroke-dashoffset: 48;
            animation: stroke 0.3s cubic-bezier(0.65, 0, 0.45, 1) 0.8s forwards;
        }
        
        @keyframes stroke {
            100% { stroke-dashoffset: 0; }
        }
        
        @keyframes scale {
            0%, 100% { transform: none; }
            50% { transform: scale3d(1.1, 1.1, 1); }
        }
        
        @keyframes fill {
            100% { box-shadow: inset 0px 0px 0px 30px #06b48a; }
        }
        
        /* Error Animation */
        .error-animation {
            margin-bottom: 24px;
        }
        
        .error-icon {
            width: 90px;
            height: 90px;
            background: #ffebee;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
            animation: shake 0.5s ease-in-out;
        }
        
        .error-icon i {
            font-size: 48px;
            color: #ef476f;
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-8px); }
            75% { transform: translateX(8px); }
        }
        
        /* Typography */
        h1 {
            font-size: 28px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 12px;
        }
        
        h2 {
            font-size: 24px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 12px;
        }
        
        .message-text {
            color: #64748b;
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 24px;
        }
        
        .highlight-email {
            display: inline-block;
            background: #f1f5f9;
            padding: 4px 12px;
            border-radius: 30px;
            font-weight: 600;
            color: #667eea;
            font-size: 15px;
            margin: 8px 0;
        }
        
        /* Info Box */
        .info-box {
            background: #f8fafc;
            border-radius: 20px;
            padding: 20px;
            display: flex;
            align-items: flex-start;
            gap: 14px;
            text-align: left;
            margin: 24px 0;
            border: 1px solid #eef2f6;
            transition: all 0.3s ease;
        }
        
        .info-box:hover {
            border-color: #667eea;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }
        
        .info-box i {
            font-size: 24px;
            color: #667eea;
            flex-shrink: 0;
        }
        
        .info-box.error i {
            color: #ef476f;
        }
        
        .info-box.warning i {
            color: #ffb347;
        }
        
        .info-box-content {
            flex: 1;
        }
        
        .info-box-title {
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 4px;
        }
        
        .info-box-text {
            font-size: 13px;
            color: #64748b;
            line-height: 1.5;
        }
        
        /* Buttons */
        .button-group {
            display: flex;
            gap: 12px;
            justify-content: center;
            flex-wrap: wrap;
            margin-top: 24px;
        }
        
        .btn-primary, .btn-secondary, .btn-outline {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 14px 28px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 15px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            border: none;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            box-shadow: 0 4px 12px rgba(102,126,234,0.3);
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102,126,234,0.4);
        }
        
        .btn-secondary {
            background: #f1f5f9;
            color: #475569;
        }
        
        .btn-secondary:hover {
            background: #e2e8f0;
            transform: translateY(-2px);
        }
        
        .btn-outline {
            background: transparent;
            border: 2px solid #e2e8f0;
            color: #475569;
        }
        
        .btn-outline:hover {
            border-color: #667eea;
            color: #667eea;
            transform: translateY(-2px);
        }
        
        /* Footer */
        .unsubscribe-footer {
            margin-top: 32px;
            padding-top: 24px;
            border-top: 1px solid #eef2f6;
        }
        
        .footer-links {
            display: flex;
            justify-content: center;
            gap: 24px;
            flex-wrap: wrap;
        }
        
        .footer-links a {
            color: #94a3b8;
            text-decoration: none;
            font-size: 13px;
            transition: color 0.2s ease;
        }
        
        .footer-links a:hover {
            color: #667eea;
        }
        
        .copyright {
            margin-top: 16px;
            font-size: 12px;
            color: #94a3b8;
        }
        
        /* Toast Notification */
        .toast-message {
            position: fixed;
            bottom: 30px;
            right: 30px;
            padding: 14px 24px;
            border-radius: 50px;
            display: flex;
            align-items: center;
            gap: 12px;
            z-index: 9999;
            animation: slideInRight 0.3s ease;
            box-shadow: 0 10px 25px -5px rgba(0,0,0,0.2);
            font-weight: 500;
        }
        
        .toast-message.success {
            background: #06b48a;
            color: white;
        }
        
        .toast-message.error {
            background: #ef476f;
            color: white;
        }
        
        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        /* Loading state */
        .btn-loading {
            position: relative;
            pointer-events: none;
            opacity: 0.7;
        }
        
        .btn-loading .btn-text {
            opacity: 0;
        }
        
        .btn-loading::after {
            content: '';
            position: absolute;
            width: 18px;
            height: 18px;
            top: 50%;
            left: 50%;
            margin-left: -9px;
            margin-top: -9px;
            border: 2px solid rgba(255,255,255,0.3);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        
        /* Responsive */
        @media (max-width: 600px) {
            .unsubscribe-card {
                padding: 36px 24px;
            }
            
            h1, h2 {
                font-size: 22px;
            }
            
            .button-group {
                flex-direction: column;
            }
            
            .btn-primary, .btn-secondary, .btn-outline {
                width: 100%;
                justify-content: center;
            }
            
            .info-box {
                padding: 16px;
            }
        }
    </style>
</head>
<body>
    <!-- Animated particles -->
    <div id="particles"></div>
    
    <div class="unsubscribe-card">
        <!-- Brand Logo -->
        <div class="brand">
            <div class="brand-icon">
                <i class="fas fa-envelope-open-text"></i>
            </div>
            <div class="brand-name">{{ config('app.name', 'Newsletter') }}</div>
        </div>
        
        @if($success ?? true)
            <!-- Success State -->
            <div class="success-animation">
                <svg class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                    <circle class="checkmark-circle" cx="26" cy="26" r="25" fill="none"/>
                    <path class="checkmark-check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"/>
                </svg>
            </div>
            
            <h1>Désabonnement confirmé</h1>
            
            <div class="highlight-email">
                <i class="fas fa-envelope"></i> {{ $email }}
            </div>
            
            <p class="message-text">
                Cette adresse a bien été retirée de notre liste de diffusion.<br>
                Vous ne recevrez plus nos communications marketing.
            </p>
            
            <div class="info-box">
                <i class="fas fa-info-circle"></i>
                <div class="info-box-content">
                    <div class="info-box-title">Vous pouvez vous réabonner à tout moment</div>
                    <div class="info-box-text">Cliquez sur le bouton ci-dessous pour réactiver votre abonnement.</div>
                </div>
            </div>
            
            <div class="button-group">
                <button onclick="resubscribe()" class="btn-primary" id="resubscribeBtn">
                    <i class="fas fa-undo-alt"></i>
                    <span class="btn-text">Me réabonner</span>
                </button>
                <a href="{{ url('/') }}" class="btn-secondary">
                    <i class="fas fa-home"></i>
                    Retour à l'accueil
                </a>
            </div>
            
        @else
            <!-- Error State -->
            <div class="error-animation">
                <div class="error-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
            </div>
            
            <h2>Désabonnement impossible</h2>
            
            <p class="message-text">
                {{ $error ?? "Le lien de désabonnement n'est pas valide ou a expiré." }}
            </p>
            
            <div class="info-box error">
                <i class="fas fa-envelope"></i>
                <div class="info-box-content">
                    <div class="info-box-title">Besoin d'aide ?</div>
                    <div class="info-box-text">Contactez notre support pour toute assistance.</div>
                </div>
            </div>
            
            <div class="button-group">
                <a href="mailto:{{ config('mail.from.address') }}" class="btn-primary">
                    <i class="fas fa-headset"></i>
                    Contacter le support
                </a>
                <a href="{{ url('/') }}" class="btn-outline">
                    <i class="fas fa-home"></i>
                    Retour à l'accueil
                </a>
            </div>
        @endif
        
        <!-- Footer -->
        <div class="unsubscribe-footer">
            <div class="footer-links">
                <a href="{{ url('/privacy') }}">Politique de confidentialité</a>
                <a href="{{ url('/contact') }}">Contact</a>
                <a href="{{ url('/mentions-legales') }}">Mentions légales</a>
            </div>
            <div class="copyright">
                &copy; {{ date('Y') }} {{ config('app.name', 'Newsletter') }}. Tous droits réservés.
            </div>
        </div>
    </div>
    
    <script>
        // Generate floating particles
        function createParticles() {
            const container = document.getElementById('particles');
            const particleCount = 30;
            
            for (let i = 0; i < particleCount; i++) {
                const particle = document.createElement('div');
                particle.className = 'particle';
                const size = Math.random() * 6 + 2;
                particle.style.width = size + 'px';
                particle.style.height = size + 'px';
                particle.style.left = Math.random() * 100 + '%';
                particle.style.animationDelay = Math.random() * 20 + 's';
                particle.style.animationDuration = 15 + Math.random() * 15 + 's';
                particle.style.opacity = Math.random() * 0.4 + 0.1;
                container.appendChild(particle);
            }
        }
        
        // Resubscribe function
        function resubscribe() {
            const btn = document.getElementById('resubscribeBtn');
            const email = '{{ $email }}';
            
            // Disable button and show loading
            btn.classList.add('btn-loading');
            btn.disabled = true;
            
            fetch('{{ route("mail-marketing.resubscribe") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ email: email })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('Vous avez été réabonné avec succès !', 'success');
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                } else {
                    showToast(data.message || 'Erreur lors du réabonnement', 'error');
                    btn.classList.remove('btn-loading');
                    btn.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Erreur de connexion. Veuillez réessayer.', 'error');
                btn.classList.remove('btn-loading');
                btn.disabled = false;
            });
        }
        
        // Show toast notification
        function showToast(message, type) {
            const toast = document.createElement('div');
            toast.className = `toast-message ${type}`;
            toast.innerHTML = `
                <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'}"></i>
                <span>${message}</span>
            `;
            document.body.appendChild(toast);
            
            setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transform = 'translateX(100%)';
                setTimeout(() => toast.remove(), 300);
            }, 4000);
        }
        
        // Initialize particles
        createParticles();
        
        // Add animation to card on load
        document.addEventListener('DOMContentLoaded', function() {
            const card = document.querySelector('.unsubscribe-card');
            card.style.animation = 'fadeInUp 0.6s cubic-bezier(0.2, 0.9, 0.4, 1.1)';
        });
    </script>
</body>
</html>