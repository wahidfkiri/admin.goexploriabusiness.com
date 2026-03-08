<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marketing & Réseaux Sociaux</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', system-ui, sans-serif;
        }

        :root {
            --primary: #4361ee;
            --secondary: #3a0ca3;
            --accent: #f72585;
            --dark: #1a1a2e;
            --light: #f8f9fa;
            --gradient: linear-gradient(135deg, #4361ee, #3a0ca3, #7209b7);
        }

        body {
            background-color: #f5f7ff;
            color: #333;
            line-height: 1.6;
            padding: 20px;
        }

        .marketing-section {
            max-width: 1200px;
            margin: 40px auto;
            background: white;
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            position: relative;
        }

        /* Header avec animation */
        .section-header {
            background: var(--gradient);
            color: white;
            padding: 60px 40px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .section-header h1 {
            font-size: 3.2rem;
            margin-bottom: 15px;
            font-weight: 800;
            opacity: 0;
            animation: fadeInUp 1s ease forwards;
        }

        .section-header p {
            font-size: 1.3rem;
            max-width: 700px;
            margin: 0 auto 30px;
            opacity: 0;
            animation: fadeInUp 1s ease 0.3s forwards;
        }

        .cta-button {
            display: inline-block;
            background: white;
            color: var(--primary);
            padding: 16px 40px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 700;
            font-size: 1.1rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            transition: all 0.4s ease;
            transform: translateY(20px);
            opacity: 0;
            animation: fadeInUp 1s ease 0.6s forwards;
        }

        .cta-button:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.25);
            color: var(--accent);
        }

        /* Contenu principal */
        .section-content {
            display: flex;
            flex-wrap: wrap;
            padding: 60px 40px;
            gap: 40px;
        }

        .services-description {
            flex: 1;
            min-width: 300px;
            padding-right: 20px;
        }

        .services-description h2 {
            color: var(--secondary);
            font-size: 2.2rem;
            margin-bottom: 25px;
            position: relative;
            padding-bottom: 15px;
        }

        .services-description h2:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 70px;
            height: 4px;
            background: var(--accent);
            border-radius: 2px;
        }

        .services-description p {
            margin-bottom: 20px;
            font-size: 1.1rem;
            color: #555;
        }

        .highlight {
            background: linear-gradient(120deg, #fdf2f8 0%, #f0f4ff 100%);
            padding: 25px;
            border-radius: 16px;
            border-left: 5px solid var(--accent);
            margin: 30px 0;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
        }

        .highlight h3 {
            color: var(--secondary);
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* Section réseaux sociaux */
        .social-networks {
            flex: 1;
            min-width: 300px;
        }

        .social-title {
            color: var(--secondary);
            font-size: 2.2rem;
            margin-bottom: 30px;
            position: relative;
            padding-bottom: 15px;
        }

        .social-title:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 70px;
            height: 4px;
            background: var(--accent);
            border-radius: 2px;
        }

        .social-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }

        .social-card {
            background: white;
            border-radius: 16px;
            padding: 25px 15px;
            text-align: center;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08);
            cursor: pointer;
            transition: all 0.3s ease;
            border: 2px solid transparent;
            position: relative;
            overflow: hidden;
            opacity: 1 !important;
        }

        .social-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
            border-color: var(--primary);
        }

        .social-card.active {
            border-color: var(--accent);
            box-shadow: 0 15px 30px rgba(247, 37, 133, 0.2);
        }

        .social-icon {
            font-size: 2.8rem;
            margin-bottom: 15px;
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .social-name {
            font-weight: 700;
            font-size: 1.1rem;
        }

        .social-stats {
            display: flex;
            justify-content: space-between;
            margin-top: 15px;
            font-size: 0.9rem;
            color: #666;
        }

        /* Section détails réseaux sociaux */
        .social-details {
            background: #f8faff;
            border-radius: 16px;
            padding: 30px;
            margin-top: 20px;
            display: none;
            animation: fadeIn 0.8s ease;
        }

        .social-details.active {
            display: block;
        }

        .details-header {
            display: flex;
            align-items: center;
            margin-bottom: 25px;
            gap: 15px;
        }

        .details-icon {
            font-size: 2.5rem;
            width: 70px;
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            color: white;
        }

        .details-title {
            font-size: 1.8rem;
            color: var(--secondary);
        }

        .details-content {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 25px;
        }

        .detail-item {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }

        .detail-item h4 {
            color: var(--primary);
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* Footer */
        .section-footer {
            background: var(--dark);
            color: white;
            padding: 40px;
            text-align: center;
            border-top: 5px solid var(--accent);
        }

        .footer-content {
            max-width: 800px;
            margin: 0 auto;
        }

        .footer-content h3 {
            font-size: 1.8rem;
            margin-bottom: 20px;
        }

        .contact-info {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 30px;
            margin-top: 30px;
        }

        .contact-item {
            display: flex;
            align-items: center;
            gap: 10px;
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

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        .pulse {
            animation: pulse 2s infinite;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .section-header h1 {
                font-size: 2.5rem;
            }
            
            .section-header p {
                font-size: 1.1rem;
            }
            
            .section-content {
                padding: 40px 20px;
            }
            
            .social-grid {
                grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
            }
        }

        @media (max-width: 480px) {
            .section-header {
                padding: 40px 20px;
            }
            
            .section-header h1 {
                font-size: 2rem;
            }
            
            .cta-button {
                padding: 14px 30px;
                font-size: 1rem;
            }
            
            .social-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>
</head>
<body>
    <div class="marketing-section">
        <!-- En-tête de section -->
        <div class="section-header">
            <h1>Marketing Digital & Réseaux Sociaux</h1>
            <p>Développez votre présence en ligne avec nos stratégies innovantes et nos campagnes optimisées pour chaque plateforme sociale.</p>
            <a href="#contact" class="cta-button">Démarrer un projet</a>
        </div>

        <!-- Contenu principal -->
        <div class="section-content">
            <!-- Description des services -->
            <div class="services-description">
                <h2>Notre Expertise Marketing</h2>
                <p>Dans un monde numérique en constante évolution, une stratégie de marketing efficace sur les réseaux sociaux est essentielle pour toucher votre public cible, renforcer votre marque et générer des leads qualifiés.</p>
                
                <p>Nous combinons analyse de données, créativité et expertise technique pour créer des campagnes qui génèrent un impact mesurable. Notre approche est personnalisée selon vos objectifs commerciaux et votre public.</p>
                
                <div class="highlight">
                    <h3><i class="fas fa-chart-line"></i> Résultats Mesurables</h3>
                    <p>Nous suivons et optimisons chaque campagne en temps réel avec des indicateurs clés de performance (KPI) précis pour garantir un retour sur investissement maximal.</p>
                </div>
                
                <p>Notre équipe multidisciplinaire travaille avec vous pour développer une identité de marque cohérente à travers toutes les plateformes, créant une expérience utilisateur unifiée qui convertit les followers en clients fidèles.</p>
            </div>

            <!-- Section réseaux sociaux -->
            <div class="social-networks">
                <h2 class="social-title">Plateformes Gérées</h2>
                <p>Sélectionnez une plateforme pour voir nos services spécifiques :</p>
                
                <div class="social-grid" id="socialGrid">
                    <!-- Les cartes de réseaux sociaux seront générées par JavaScript -->
                </div>

                <!-- Détails des réseaux sociaux -->
                <div class="social-details" id="socialDetails">
                    <!-- Le contenu sera injecté par JavaScript -->
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="section-footer" style="display:none;" id="contact">
            <div class="footer-content">
                <h3>Prêt à transformer votre présence digitale ?</h3>
                <p>Contactez-nous pour une consultation gratuite et découvrez comment nous pouvons optimiser votre stratégie sur les réseaux sociaux.</p>
                
                <div class="contact-info">
                    <div class="contact-item">
                        <i class="fas fa-phone"></i>
                        <span>+33 1 23 45 67 89</span>
                    </div>
                    <div class="contact-item">
                        <i class="fas fa-envelope"></i>
                        <span>contact@marketing-expert.fr</span>
                    </div>
                    <div class="contact-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>Paris, France</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Données des réseaux sociaux
        const socialNetworks = [
            {
                id: 'facebook',
                name: 'Facebook',
                icon: 'fab fa-facebook',
                color: '#1877F2',
                stats: '2.9Mds d\'utilisateurs',
                description: 'Plateforme idéale pour le marketing B2C, la publicité ciblée et la construction de communautés engagées.',
                services: [
                    {
                        title: 'Gestion de Pages',
                        icon: 'fas fa-flag',
                        description: 'Création et gestion complète de votre page Facebook professionnelle avec contenu optimisé.'
                    },
                    {
                        title: 'Campagnes Publicitaires',
                        icon: 'fas fa-bullhorn',
                        description: 'Stratégies publicitaires ciblées avec optimisation en temps réel pour maximiser le ROI.'
                    },
                    {
                        title: 'Analyse d\'Audience',
                        icon: 'fas fa-chart-bar',
                        description: 'Analyses approfondies des insights pour comprendre et cibler votre audience.'
                    },
                    {
                        title: 'Contenu Engageant',
                        icon: 'fas fa-video',
                        description: 'Création de contenu multimédia (vidéos, carrousels, stories) pour maximiser l\'engagement.'
                    }
                ]
            },
            {
                id: 'instagram',
                name: 'Instagram',
                icon: 'fab fa-instagram',
                color: '#E4405F',
                stats: '1.4Md d\'utilisateurs',
                description: 'Réseau visuel parfait pour les marques créatives, la mode, la gastronomie et le lifestyle.',
                services: [
                    {
                        title: 'Stratégie Visuelle',
                        icon: 'fas fa-palette',
                        description: 'Développement d\'une identité visuelle cohérente et attractive pour votre marque.'
                    },
                    {
                        title: 'Stories & Reels',
                        icon: 'fas fa-play-circle',
                        description: 'Création de contenu éphémère et de reels pour engager les audiences plus jeunes.'
                    },
                    {
                        title: 'Influence Marketing',
                        icon: 'fas fa-user-friends',
                        description: 'Partenariats stratégiques avec des influenceurs pour accroître votre visibilité.'
                    },
                    {
                        title: 'Shop Instagram',
                        icon: 'fas fa-shopping-bag',
                        description: 'Mise en place de boutique intégrée pour vendre directement sur la plateforme.'
                    }
                ]
            },
            {
                id: 'linkedin',
                name: 'LinkedIn',
                icon: 'fab fa-linkedin',
                color: '#0A66C2',
                stats: '900M d\'utilisateurs',
                description: 'Plateforme professionnelle idéale pour le B2B, le recrutement et le développement de leadership.',
                services: [
                    {
                        title: 'Optimisation de Profil',
                        icon: 'fas fa-user-tie',
                        description: 'Optimisation complète de votre page entreprise et des profils clés de votre organisation.'
                    },
                    {
                        title: 'Content Marketing B2B',
                        icon: 'fas fa-file-alt',
                        description: 'Création de contenu thought leadership et d\'articles professionnels.'
                    },
                    {
                        title: 'LinkedIn Ads',
                        icon: 'fas fa-ad',
                        description: 'Campagnes publicitaires ciblant les professionnels par industrie, fonction et seniorité.'
                    },
                    {
                        title: 'Réseautage Stratégique',
                        icon: 'fas fa-network-wired',
                        description: 'Développement de votre réseau avec des professionnels et partenaires clés.'
                    }
                ]
            },
            {
                id: 'tiktok',
                name: 'TikTok',
                icon: 'fab fa-tiktok',
                color: '#000000',
                stats: '1Md d\'utilisateurs',
                description: 'Plateforme de vidéos courtes pour atteindre les générations Z et Millennials avec du contenu créatif.',
                services: [
                    {
                        title: 'Création de Trends',
                        icon: 'fas fa-fire',
                        description: 'Adaptation des tendances virales pour promouvoir votre marque de manière authentique.'
                    },
                    {
                        title: 'Stratégie Vidéo',
                        icon: 'fas fa-film',
                        description: 'Production de contenu vidéo court optimisé pour l\'algorithme TikTok.'
                    },
                    {
                        title: 'TikTok Ads',
                        icon: 'fas fa-megaphone',
                        description: 'Campagnes publicitaires natives intégrées au flux de contenu des utilisateurs.'
                    },
                    {
                        title: 'Community Management',
                        icon: 'fas fa-comments',
                        description: 'Gestion proactive des commentaires et développement d\'une communauté engagée.'
                    }
                ]
            },
            {
                id: 'twitter',
                name: 'Twitter',
                icon: 'fab fa-twitter',
                color: '#1DA1F2',
                stats: '450M d\'utilisateurs',
                description: 'Réseau de microblogging idéal pour l\'actualité, le service client et l\'engagement en temps réel.',
                services: [
                    {
                        title: 'Gestion de Compte',
                        icon: 'fab fa-twitter',
                        description: 'Gestion quotidienne de votre compte Twitter avec contenu adapté aux tendances.'
                    },
                    {
                        title: 'Twitter Spaces',
                        icon: 'fas fa-broadcast-tower',
                        description: 'Animation de discussions audio en direct pour engager votre communauté.'
                    },
                    {
                        title: 'Service Client',
                        icon: 'fas fa-headset',
                        description: 'Gestion des réclamations et questions via Twitter pour un service client rapide.'
                    },
                    {
                        title: 'Campagnes Promues',
                        icon: 'fas fa-rocket',
                        description: 'Tweets promus et campagnes ciblées pour augmenter votre portée.'
                    }
                ]
            },
            {
                id: 'youtube',
                name: 'YouTube',
                icon: 'fab fa-youtube',
                color: '#FF0000',
                stats: '2.5Mds d\'utilisateurs',
                description: 'Plateforme vidéo essentielle pour le contenu éducatif, les tutoriels et le branding approfondi.',
                services: [
                    {
                        title: 'Stratégie Vidéo',
                        icon: 'fas fa-video',
                        description: 'Planification et production de contenu vidéo régulier pour votre chaîne.'
                    },
                    {
                        title: 'Optimisation SEO',
                        icon: 'fas fa-search',
                        description: 'Optimisation des titres, descriptions et tags pour maximiser la découvrabilité.'
                    },
                    {
                        title: 'YouTube Ads',
                        icon: 'fas fa-ad',
                        description: 'Campagnes publicitaires pré-roll, mid-roll et display sur la plateforme.'
                    },
                    {
                        title: 'Analytics Avancées',
                        icon: 'fas fa-chart-line',
                        description: 'Analyse détaillée des performances et de l\'engagement des téléspectateurs.'
                    }
                ]
            }
        ];

        // Élément sélectionné actuellement
        let currentSelection = 'facebook';

        // Générer les cartes de réseaux sociaux
        function generateSocialCards() {
            const socialGrid = document.getElementById('socialGrid');
            socialGrid.innerHTML = '';
            
            socialNetworks.forEach(network => {
                const card = document.createElement('div');
                card.className = `social-card ${network.id === currentSelection ? 'active' : ''}`;
                card.dataset.id = network.id;
                
                card.innerHTML = `
                    <div class="social-icon" style="color: ${network.color}">
                        <i class="${network.icon}"></i>
                    </div>
                    <div class="social-name">${network.name}</div>
                    <div class="social-stats">
                        <span><i class="fas fa-users"></i></span>
                        <span>${network.stats}</span>
                    </div>
                `;
                
                card.addEventListener('click', () => {
                    selectSocialNetwork(network.id);
                });
                
                socialGrid.appendChild(card);
            });
        }

        // Afficher les détails d'un réseau social
        function showSocialDetails(networkId) {
            const network = socialNetworks.find(n => n.id === networkId);
            const detailsContainer = document.getElementById('socialDetails');
            
            if (!network) return;
            
            // Générer les services
            const servicesHTML = network.services.map(service => `
                <div class="detail-item">
                    <h4><i class="${service.icon}"></i> ${service.title}</h4>
                    <p>${service.description}</p>
                </div>
            `).join('');
            
            detailsContainer.innerHTML = `
                <div class="details-header">
                    <div class="details-icon" style="background: ${network.color}">
                        <i class="${network.icon}"></i>
                    </div>
                    <h3 class="details-title">${network.name} - Nos Services</h3>
                </div>
                <p>${network.description}</p>
                <div class="details-content">
                    ${servicesHTML}
                </div>
            `;
            
            detailsContainer.classList.add('active');
        }

        // Sélectionner un réseau social
        function selectSocialNetwork(networkId) {
            currentSelection = networkId;
            
            // Mettre à jour les cartes
            document.querySelectorAll('.social-card').forEach(card => {
                card.classList.remove('active');
                if (card.dataset.id === networkId) {
                    card.classList.add('active');
                    // Ajouter une animation de pulse à la carte sélectionnée
                    card.style.animation = 'pulse 0.5s ease';
                    setTimeout(() => {
                        card.style.animation = '';
                    }, 500);
                }
            });
            
            // Afficher les détails
            showSocialDetails(networkId);
        }

        // Animation au défilement
        function handleScrollAnimations() {
            const elements = document.querySelectorAll('.social-card, .detail-item, .highlight');
            
            elements.forEach(element => {
                const elementTop = element.getBoundingClientRect().top;
                const windowHeight = window.innerHeight;
                
                if (elementTop < windowHeight * 0.85) {
                    element.style.opacity = '1';
                    element.style.transform = 'translateY(0)';
                }
            });
        }

        // Initialisation
        document.addEventListener('DOMContentLoaded', () => {
            // Générer les cartes
            generateSocialCards();
            
            // Afficher les détails par défaut
            showSocialDetails(currentSelection);
            
            // Configurer les animations au défilement
            document.querySelectorAll('.social-card, .detail-item, .highlight').forEach(el => {
                el.style.opacity = '1';
                el.style.transform = 'translateY(20px)';
                el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            });
            
            window.addEventListener('scroll', handleScrollAnimations);
            // Déclencher une première fois pour les éléments visibles
            setTimeout(handleScrollAnimations, 100);
            
            // Animation pour le bouton CTA
            const ctaButton = document.querySelector('.cta-button');
            setInterval(() => {
                ctaButton.classList.toggle('pulse');
            }, 4000);
        });

        // Gestion du clic sur le bouton CTA
        document.querySelector('.cta-button').addEventListener('click', function(e) {
            e.preventDefault();
            const contactSection = document.getElementById('contact');
            
            // Animation de défilement fluide
            contactSection.scrollIntoView({ behavior: 'smooth' });
            
            // Animation sur la section contact
            contactSection.style.animation = 'pulse 1s ease';
            setTimeout(() => {
                contactSection.style.animation = '';
            }, 1000);
        });
    </script>
    <script>
function sendHeight() {
    const height = document.body.scrollHeight;
    window.parent.postMessage({
        type: 'setHeight',
        iframeId: 'digital-marketing',
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