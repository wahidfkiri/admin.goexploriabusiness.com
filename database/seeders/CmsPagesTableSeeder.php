<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Carbon\Carbon;

class CmsPagesTableSeeder extends Seeder
{
    protected $connection = 'cms';
    protected $table = 'cms_pages';

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Vérifier si la connexion CMS existe
        if (!config('database.connections.cms')) {
            $this->command->error('La connexion CMS n\'est pas configurée dans database.php');
            return;
        }

        $this->command->info('Seeding cms_pages table...');

        // Nettoyer la table avant d'insérer (optionnel)
        if ($this->command->confirm('Voulez-vous vider la table cms_pages avant de la remplir ?', false)) {
            DB::connection('cms')->table('cms_pages')->truncate();
            $this->command->info('Table cms_pages vidée avec succès.');
        }

        $pages = $this->getPagesData();
        $now = Carbon::now();

        foreach ($pages as $page) {
            // Générer un slug unique
            $slug = Str::slug($page['title']);
            $originalSlug = $slug;
            $counter = 1;
            
            while (DB::connection('cms')->table('cms_pages')->where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $counter;
                $counter++;
            }
            
            DB::connection('cms')->table('cms_pages')->insert([
                'etablissement_id' => 11,
                'user_id' => 11,
                'title' => $page['title'],
                'slug' => $slug,
                'content' => $page['content'],
                'meta' => json_encode($page['meta']),
                'status' => $page['status'],
                'visibility' => $page['visibility'],
                'password' => $page['password'] ?? null,
                'is_home' => $page['is_home'] ?? false,
                'settings' => json_encode($page['settings'] ?? []),
                'published_at' => $page['published_at'] ?? $now,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        $this->command->info(count($pages) . ' pages créées avec succès pour l\'établissement 11!');
    }

    /**
     * Get pages data.
     */
    protected function getPagesData(): array
    {
        return [
            // Page d'accueil
            [
                'title' => 'Accueil',
                'content' => $this->getHomePageContent(),
                'meta' => [
                    'seo_title' => 'Accueil - Mon Site Professionnel',
                    'seo_description' => 'Bienvenue sur notre site. Découvrez nos services et solutions innovantes pour votre entreprise.',
                    'seo_keywords' => 'accueil, site web, entreprise, services',
                    'hero_title' => 'Solutions Digitales pour votre Entreprise',
                    'hero_description' => 'Nous vous accompagnons dans votre transformation digitale avec des solutions innovantes et sur mesure.',
                    'hero_button_text' => 'Commencer',
                    'hero_button_url' => '/contact',
                    'hero_secondary_text' => 'En savoir plus',
                    'hero_secondary_url' => '/about',
                    'features' => [
                        [
                            'icon' => 'fas fa-chart-line',
                            'title' => 'Performance',
                            'description' => 'Optimisation des performances pour un résultat optimal.',
                            'link' => '/services'
                        ],
                        [
                            'icon' => 'fas fa-mobile-alt',
                            'title' => 'Responsive',
                            'description' => 'Site adapté à tous les écrans et appareils.',
                            'link' => '/services'
                        ],
                        [
                            'icon' => 'fas fa-headset',
                            'title' => 'Support 24/7',
                            'description' => 'Assistance technique disponible à tout moment.',
                            'link' => '/contact'
                        ]
                    ],
                    'about_text' => 'Nous sommes une entreprise dynamique spécialisée dans les solutions digitales innovantes. Avec plus de 10 ans d\'expérience, nous accompagnons nos clients dans leur transformation numérique.',
                    'about_image' => '/images/about.jpg',
                    'about_link' => '/about',
                    'testimonials' => [
                        [
                            'name' => 'Jean Dupont',
                            'position' => 'CEO, Entreprise ABC',
                            'avatar' => '/images/avatar1.jpg',
                            'text' => 'Excellente collaboration ! L\'équipe est professionnelle et à l\'écoute. Les résultats dépassent nos attentes.'
                        ],
                        [
                            'name' => 'Marie Martin',
                            'position' => 'Directrice Marketing',
                            'avatar' => '/images/avatar2.jpg',
                            'text' => 'Un service client exceptionnel et des solutions parfaitement adaptées à nos besoins.'
                        ],
                        [
                            'name' => 'Pierre Durand',
                            'position' => 'CTO, Tech Corp',
                            'avatar' => '/images/avatar3.jpg',
                            'text' => 'Je recommande vivement leurs services. Qualité et professionnalisme au rendez-vous.'
                        ]
                    ]
                ],
                'status' => 'published',
                'visibility' => 'public',
                'is_home' => true,
                'published_at' => Carbon::now(),
            ],
            
            // Page À propos
            [
                'title' => 'À propos',
                'content' => $this->getAboutPageContent(),
                'meta' => [
                    'seo_title' => 'À propos - Notre Histoire et Valeurs',
                    'seo_description' => 'Découvrez notre histoire, nos valeurs et notre équipe. Nous sommes passionnés par ce que nous faisons.',
                    'seo_keywords' => 'à propos, histoire, valeurs, équipe',
                    'subtitle' => 'Découvrez qui nous sommes'
                ],
                'status' => 'published',
                'visibility' => 'public',
                'is_home' => false,
                'published_at' => Carbon::now(),
            ],
            
            // Page Services
            [
                'title' => 'Services',
                'content' => $this->getServicesPageContent(),
                'meta' => [
                    'seo_title' => 'Nos Services - Solutions Digitales',
                    'seo_description' => 'Découvrez tous nos services : développement web, marketing digital, conseil et accompagnement.',
                    'seo_keywords' => 'services, développement web, marketing digital, conseil',
                    'subtitle' => 'Des solutions sur mesure pour votre entreprise'
                ],
                'status' => 'published',
                'visibility' => 'public',
                'is_home' => false,
                'published_at' => Carbon::now(),
            ],
            
            // Page Contact
            [
                'title' => 'Contact',
                'content' => $this->getContactPageContent(),
                'meta' => [
                    'seo_title' => 'Contactez-nous',
                    'seo_description' => 'Une question ? Un projet ? N\'hésitez pas à nous contacter. Notre équipe vous répondra dans les plus brefs délais.',
                    'seo_keywords' => 'contact, formulaire, email, téléphone',
                    'subtitle' => 'Parlons de votre projet'
                ],
                'status' => 'published',
                'visibility' => 'public',
                'is_home' => false,
                'published_at' => Carbon::now(),
            ],
            
            // Page Mentions légales
            [
                'title' => 'Mentions légales',
                'content' => $this->getLegalPageContent(),
                'meta' => [
                    'seo_title' => 'Mentions légales',
                    'seo_description' => 'Informations légales et conditions d\'utilisation du site.',
                    'seo_keywords' => 'mentions légales, conditions, rgpd'
                ],
                'status' => 'published',
                'visibility' => 'public',
                'is_home' => false,
                'published_at' => Carbon::now(),
            ],
            
            // Page Politique de confidentialité
            [
                'title' => 'Politique de confidentialité',
                'content' => $this->getPrivacyPageContent(),
                'meta' => [
                    'seo_title' => 'Politique de confidentialité',
                    'seo_description' => 'Comment nous protégeons vos données personnelles conformément au RGPD.',
                    'seo_keywords' => 'rgpd, confidentialité, données personnelles'
                ],
                'status' => 'published',
                'visibility' => 'public',
                'is_home' => false,
                'published_at' => Carbon::now(),
            ],
            
            // Page 404
            [
                'title' => 'Page non trouvée',
                'content' => $this->get404PageContent(),
                'meta' => [
                    'seo_title' => 'Page non trouvée - 404',
                    'seo_description' => 'La page que vous recherchez n\'existe pas.',
                ],
                'status' => 'published',
                'visibility' => 'public',
                'is_home' => false,
                'published_at' => Carbon::now(),
            ],
        ];
    }

    /**
     * Get home page content.
     */
    protected function getHomePageContent(): string
    {
        return '<div class="home-content">
            <section class="hero">
                <div class="hero-text">
                    <h1>Solutions Digitales pour votre Entreprise</h1>
                    <p>Nous vous accompagnons dans votre transformation digitale avec des solutions innovantes et sur mesure.</p>
                    <div class="hero-buttons">
                        <a href="/contact" class="btn btn-primary">Commencer</a>
                        <a href="/about" class="btn btn-secondary">En savoir plus</a>
                    </div>
                </div>
            </section>
            
            <section class="features">
                <h2>Pourquoi nous choisir ?</h2>
                <div class="features-grid">
                    <div class="feature-card">
                        <i class="fas fa-rocket"></i>
                        <h3>Innovation</h3>
                        <p>Des solutions innovantes adaptées à vos besoins.</p>
                    </div>
                    <div class="feature-card">
                        <i class="fas fa-users"></i>
                        <h3>Expertise</h3>
                        <p>Une équipe d\'experts passionnés à votre service.</p>
                    </div>
                    <div class="feature-card">
                        <i class="fas fa-clock"></i>
                        <h3>Réactivité</h3>
                        <p>Des délais respectés et un support réactif.</p>
                    </div>
                </div>
            </section>
            
            <section class="cta">
                <h2>Prêt à démarrer votre projet ?</h2>
                <p>Contactez-nous dès aujourd\'hui pour une consultation gratuite.</p>
                <a href="/contact" class="btn btn-primary">Nous contacter</a>
            </section>
        </div>';
    }

    /**
     * Get about page content.
     */
    protected function getAboutPageContent(): string
    {
        return '<div class="about-content">
            <section class="about-hero">
                <h1>Notre Histoire</h1>
                <p>Fondée en 2015, notre entreprise est née d\'une passion pour l\'innovation et l\'excellence.</p>
            </section>
            
            <section class="mission">
                <h2>Notre Mission</h2>
                <p>Accompagner les entreprises dans leur transformation digitale en fournissant des solutions innovantes, fiables et performantes.</p>
            </section>
            
            <section class="values">
                <h2>Nos Valeurs</h2>
                <div class="values-grid">
                    <div class="value-card">
                        <i class="fas fa-heart"></i>
                        <h3>Passion</h3>
                        <p>Nous sommes passionnés par ce que nous faisons.</p>
                    </div>
                    <div class="value-card">
                        <i class="fas fa-handshake"></i>
                        <h3>Intégrité</h3>
                        <p>Nous agissons avec honnêteté et transparence.</p>
                    </div>
                    <div class="value-card">
                        <i class="fas fa-star"></i>
                        <h3>Excellence</h3>
                        <p>Nous visons l\'excellence dans tout ce que nous entreprenons.</p>
                    </div>
                </div>
            </section>
        </div>';
    }

    /**
     * Get services page content.
     */
    protected function getServicesPageContent(): string
    {
        return '<div class="services-content">
            <h1>Nos Services</h1>
            
            <div class="service-list">
                <div class="service-item">
                    <i class="fas fa-code"></i>
                    <div>
                        <h3>Développement Web</h3>
                        <p>Création de sites web sur mesure, applications web performantes et e-commerce.</p>
                    </div>
                </div>
                
                <div class="service-item">
                    <i class="fas fa-chart-line"></i>
                    <div>
                        <h3>Marketing Digital</h3>
                        <p>Stratégies marketing, SEO, réseaux sociaux et publicité en ligne.</p>
                    </div>
                </div>
                
                <div class="service-item">
                    <i class="fas fa-cloud-upload-alt"></i>
                    <div>
                        <h3>Hébergement & Maintenance</h3>
                        <p>Hébergement sécurisé, maintenance technique et support continu.</p>
                    </div>
                </div>
                
                <div class="service-item">
                    <i class="fas fa-chalkboard-teacher"></i>
                    <div>
                        <h3>Conseil & Formation</h3>
                        <p>Conseil en transformation digitale et formations personnalisées.</p>
                    </div>
                </div>
            </div>
        </div>';
    }

    /**
     * Get contact page content.
     */
    protected function getContactPageContent(): string
    {
        return '<div class="contact-content">
            <h1>Contactez-nous</h1>
            <p>Une question ? Un projet ? N\'hésitez pas à nous contacter.</p>
            
            <div class="contact-grid">
                <div class="contact-info">
                    <div class="info-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <div>
                            <h4>Adresse</h4>
                            <p>123 Rue de l\'Innovation<br>75001 Paris, France</p>
                        </div>
                    </div>
                    
                    <div class="info-item">
                        <i class="fas fa-phone"></i>
                        <div>
                            <h4>Téléphone</h4>
                            <p>+33 1 23 45 67 89</p>
                        </div>
                    </div>
                    
                    <div class="info-item">
                        <i class="fas fa-envelope"></i>
                        <div>
                            <h4>Email</h4>
                            <p>contact@monsite.com</p>
                        </div>
                    </div>
                    
                    <div class="info-item">
                        <i class="fas fa-clock"></i>
                        <div>
                            <h4>Horaires</h4>
                            <p>Lundi - Vendredi: 9h00 - 18h00</p>
                        </div>
                    </div>
                </div>
                
                <div class="contact-form">
                    <form action="/contact/send" method="POST">
                        <div class="form-group">
                            <label for="name">Nom</label>
                            <input type="text" id="name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="message">Message</label>
                            <textarea id="message" name="message" rows="5" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Envoyer</button>
                    </form>
                </div>
            </div>
        </div>';
    }

    /**
     * Get legal page content.
     */
    protected function getLegalPageContent(): string
    {
        return '<div class="legal-content">
            <h1>Mentions légales</h1>
            
            <h2>1. Éditeur du site</h2>
            <p>Nom de l\'entreprise<br>
            Adresse complète<br>
            Téléphone : +33 1 23 45 67 89<br>
            Email : contact@monsite.com<br>
            Numéro SIRET : 123 456 789 00012</p>
            
            <h2>2. Directeur de publication</h2>
            <p>Nom du directeur de publication</p>
            
            <h2>3. Hébergement</h2>
            <p>Nom de l\'hébergeur<br>
            Adresse de l\'hébergeur</p>
            
            <h2>4. Propriété intellectuelle</h2>
            <p>Tout le contenu présent sur ce site est protégé par le droit d\'auteur.</p>
            
            <h2>5. Données personnelles</h2>
            <p>Conformément à la loi Informatique et Libertés, vous disposez d\'un droit d\'accès, de modification et de suppression des données vous concernant.</p>
        </div>';
    }

    /**
     * Get privacy page content.
     */
    protected function getPrivacyPageContent(): string
    {
        return '<div class="privacy-content">
            <h1>Politique de confidentialité</h1>
            
            <h2>Collecte des données</h2>
            <p>Nous collectons les données que vous nous fournissez volontairement via nos formulaires de contact.</p>
            
            <h2>Utilisation des données</h2>
            <p>Vos données sont utilisées uniquement pour répondre à vos demandes et améliorer nos services.</p>
            
            <h2>Cookies</h2>
            <p>Ce site utilise des cookies pour améliorer votre expérience de navigation.</p>
            
            <h2>Vos droits</h2>
            <p>Vous pouvez accéder, modifier ou supprimer vos données personnelles en nous contactant.</p>
            
            <h2>Contact RGPD</h2>
            <p>Pour toute question concernant vos données, contactez-nous à : dpo@monsite.com</p>
        </div>';
    }

    /**
     * Get 404 page content.
     */
    protected function get404PageContent(): string
    {
        return '<div class="error-404">
            <h1>404</h1>
            <h2>Page non trouvée</h2>
            <p>Désolé, la page que vous recherchez n\'existe pas ou a été déplacée.</p>
            <a href="/" class="btn btn-primary">Retour à l\'accueil</a>
        </div>';
    }
}