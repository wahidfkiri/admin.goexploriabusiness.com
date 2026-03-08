<?php

namespace Vendor\Website\Services;

use App\Models\Website;
use App\Models\Template;
use App\Models\Page;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WebsiteCreationService
{
    /**
     * Create website with all default resources
     */
    public function createWebsiteWithDefaults(array $data): Website
    {
        Log::info('Starting website creation with defaults', ['data_keys' => array_keys($data)]);
        
        return DB::transaction(function () use ($data) {
            try {
                // Create website
                $website = $this->createWebsite($data);
                Log::info('Website created successfully', ['website_id' => $website->id, 'website_name' => $website->name]);
                
                // Create default template
                $template = $this->createDefaultTemplate($website);
                Log::info('Default template created successfully', [
                    'template_id' => $template->id,
                    'website_id' => $website->id,
                    'user_id' => $website->user_id
                ]);
                
                // Create default pages (optional)
                // $this->createDefaultPages($website);
                
                // You can add more default resources here
                
                return $website->load(['user', 'category']);
                
            } catch (\Exception $e) {
                Log::error('Error during website creation transaction', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                    'data' => $data
                ]);
                throw $e;
            }
        });
    }
    
    /**
     * Create website record
     */
    private function createWebsite(array $data): Website
    {
        try {
            Log::debug('Creating website record', ['data' => $data]);
            $website = Website::create($data);
            
            Log::debug('Website record created', [
                'id' => $website->id,
                'user_id' => $website->user_id,
                'name' => $website->name
            ]);
            
            return $website;
            
        } catch (\Exception $e) {
            Log::error('Failed to create website record', [
                'error' => $e->getMessage(),
                'data' => $data
            ]);
            throw $e;
        }
    }
    
    /**
     * Create default template for website
     */
    public function createDefaultTemplate(Website $website): Template
    {
        try {
            Log::debug('Creating default template for website', [
                'website_id' => $website->id,
                'website_name' => $website->name,
                'user_id' => $website->user_id
            ]);
            
            // Check if website exists
            if (!$website->exists) {
                Log::error('Website does not exist for template creation', ['website_id' => $website->id]);
                throw new \Exception("Website with ID {$website->id} does not exist");
            }
            
            // Check required fields
            if (empty($website->user_id)) {
                Log::warning('Website user_id is empty', ['website_id' => $website->id]);
            }
            
            // Generate content
            $htmlContent = $this->generateDefaultHtml($website);
            $cssContent = $this->generateDefaultCss();
            $jsContent = $this->generateDefaultJs();
            
            Log::debug('Generated template content', [
                'html_length' => strlen($htmlContent),
                'css_length' => strlen($cssContent),
                'js_length' => strlen($jsContent)
            ]);
            
            // Create template data
            $templateData = [
                'website_id' => $website->id,
                'user_id' => $website->user_id,
                'categorie_id' => $website->categorie_id,
                'name' => 'Template Principal',
                'slug' => 'template-principal-' . uniqid(),
                'url' => '/company/website/' . $website->id,
                'html_content' => $htmlContent,
                'css_content' => $cssContent,
                'js_content' => $jsContent,
                'is_default' => true,
                'status' => 'active'
            ];
            
            Log::debug('Template data prepared', [
                'template_data_keys' => array_keys($templateData),
                'website_id' => $templateData['website_id'],
                'user_id' => $templateData['user_id']
            ]);
            
            // Create the template
            $template = Template::create($templateData);
            
            if (!$template->exists) {
                Log::error('Template creation failed - record not saved', ['template_data' => $templateData]);
                throw new \Exception('Template creation failed - record not saved');
            }
            
            Log::info('Template created successfully', [
                'template_id' => $template->id,
                'website_id' => $template->website_id,
                'name' => $template->name
            ]);
            
            return $template;
            
        } catch (\Exception $e) {
            Log::error('Failed to create default template', [
                'error' => $e->getMessage(),
                'website_id' => $website->id,
                'website_name' => $website->name ?? 'N/A',
                'user_id' => $website->user_id ?? 'N/A',
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }
    
    /**
     * Generate default HTML content
     */
    private function generateDefaultHtml(Website $website): string
    {
        try {
            $websiteName = $this->escapeHtml($website->name);
            $currentYear = $this->getCurrentYear();
            
            $html = <<<HTML
<div class="landing-page">
    <header class="header">
        <div class="container">
            <div class="logo">
                <h1>{$websiteName}</h1>
                <p class="tagline">Professional Solutions</p>
            </div>
            <nav class="nav">
                <a href="#home" class="nav-link">Home</a>
                <a href="#services" class="nav-link">Services</a>
                <a href="#about" class="nav-link">About</a>
                <a href="#contact" class="nav-link">Contact</a>
            </nav>
        </div>
    </header>

    <section id="home" class="hero">
        <div class="container">
            <div class="hero-content">
                <h2>Welcome to <span class="highlight">{$websiteName}</span></h2>
                <p class="hero-text">Your trusted partner for professional services and solutions.</p>
                <div class="hero-buttons">
                    <a href="#contact" class="btn btn-primary">Get Started</a>
                    <a href="#services" class="btn btn-secondary">Our Services</a>
                </div>
            </div>
        </div>
    </section>

    <section id="services" class="services">
        <div class="container">
            <h3 class="section-title">Our Services</h3>
            <div class="services-grid">
                <div class="service-card">
                    <div class="service-icon">🚀</div>
                    <h4>Fast Delivery</h4>
                    <p>Quick and efficient service delivery</p>
                </div>
                <div class="service-card">
                    <div class="service-icon">⭐</div>
                    <h4>Premium Quality</h4>
                    <p>High quality standards maintained</p>
                </div>
                <div class="service-card">
                    <div class="service-icon">💼</div>
                    <h4>Professional Team</h4>
                    <p>Experienced professionals</p>
                </div>
            </div>
        </div>
    </section>

    <section id="about" class="about">
        <div class="container">
            <h3 class="section-title">About Us</h3>
            <p class="about-text">We are dedicated to providing excellent services and solutions to help your business grow and succeed.</p>
        </div>
    </section>

    <section id="contact" class="contact">
        <div class="container">
            <h3 class="section-title">Contact Us</h3>
            <div class="contact-info">
                <p>Email: contact@example.com</p>
                <p>Phone: (123) 456-7890</p>
            </div>
        </div>
    </section>

    <footer class="footer">
        <div class="container">
            <p>&copy; {$currentYear} {$websiteName}. All rights reserved.</p>
        </div>
    </footer>
</div>
HTML;
            
            Log::debug('HTML content generated', ['length' => strlen($html)]);
            return $html;
            
        } catch (\Exception $e) {
            Log::error('Failed to generate HTML content', [
                'error' => $e->getMessage(),
                'website_id' => $website->id
            ]);
            throw $e;
        }
    }
    
    /**
     * Generate default CSS content
     */
    private function generateDefaultCss(): string
    {
        try {
            $css = <<<CSS
/* Reset & Base Styles */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    line-height: 1.6;
    color: #333;
    background-color: #f9f9f9;
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

/* Header Styles */
.header {
    background: white;
    box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
    position: sticky;
    top: 0;
    z-index: 1000;
    padding: 15px 0;
}

.header .container {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.logo h1 {
    font-size: 28px;
    font-weight: 700;
    color: #2c3e50;
    margin: 0;
}

.tagline {
    color: #7f8c8d;
    font-size: 14px;
    margin-top: 5px;
}

.nav {
    display: flex;
    gap: 25px;
}

.nav-link {
    text-decoration: none;
    color: #2c3e50;
    font-weight: 500;
    font-size: 16px;
    transition: color 0.3s ease;
    padding: 5px 0;
}

.nav-link:hover {
    color: #3498db;
}

/* Hero Section */
.hero {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 100px 0;
    text-align: center;
}

.hero h2 {
    font-size: 42px;
    margin-bottom: 20px;
    font-weight: 700;
}

.highlight {
    color: #f1c40f;
    font-weight: 800;
}

.hero-text {
    font-size: 18px;
    max-width: 600px;
    margin: 0 auto 30px;
    opacity: 0.9;
    line-height: 1.8;
}

.hero-buttons {
    display: flex;
    gap: 15px;
    justify-content: center;
    margin-top: 30px;
}

.btn {
    padding: 12px 30px;
    border-radius: 6px;
    text-decoration: none;
    font-weight: 600;
    font-size: 16px;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
}

.btn-primary {
    background: #f1c40f;
    color: #2c3e50;
}

.btn-primary:hover {
    background: #f39c12;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(243, 156, 18, 0.3);
}

.btn-secondary {
    background: transparent;
    color: white;
    border: 2px solid white;
}

.btn-secondary:hover {
    background: white;
    color: #2c3e50;
    transform: translateY(-2px);
}

/* Services Section */
.services {
    padding: 80px 0;
    background: #f8f9fa;
}

.section-title {
    text-align: center;
    font-size: 32px;
    color: #2c3e50;
    margin-bottom: 50px;
    font-weight: 700;
}

.services-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 30px;
}

.service-card {
    background: white;
    padding: 30px;
    border-radius: 10px;
    text-align: center;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.service-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
}

.service-icon {
    font-size: 40px;
    margin-bottom: 20px;
}

.service-card h4 {
    color: #2c3e50;
    margin-bottom: 10px;
    font-size: 20px;
}

.service-card p {
    color: #7f8c8d;
    font-size: 15px;
    line-height: 1.6;
}

/* About Section */
.about {
    padding: 80px 0;
    text-align: center;
    background: white;
}

.about-text {
    max-width: 800px;
    margin: 0 auto;
    font-size: 18px;
    color: #555;
    line-height: 1.8;
}

/* Contact Section */
.contact {
    padding: 80px 0;
    background: #f8f9fa;
    text-align: center;
}

.contact-info p {
    font-size: 18px;
    margin: 15px 0;
    color: #555;
}

/* Footer */
.footer {
    background: #2c3e50;
    color: white;
    padding: 25px 0;
    text-align: center;
}

.footer p {
    margin: 0;
    opacity: 0.8;
    font-size: 14px;
}

/* Responsive Design */
@media (max-width: 768px) {
    .header .container {
        flex-direction: column;
        text-align: center;
        gap: 15px;
    }
    
    .nav {
        flex-wrap: wrap;
        justify-content: center;
        gap: 15px;
    }
    
    .hero {
        padding: 60px 0;
    }
    
    .hero h2 {
        font-size: 32px;
    }
    
    .hero-text {
        font-size: 16px;
        padding: 0 15px;
    }
    
    .hero-buttons {
        flex-direction: column;
        align-items: center;
        gap: 15px;
    }
    
    .btn {
        width: 100%;
        max-width: 250px;
        text-align: center;
    }
    
    .services-grid {
        grid-template-columns: 1fr;
        padding: 0 15px;
    }
    
    .section-title {
        font-size: 28px;
        padding: 0 15px;
    }
}

@media (max-width: 480px) {
    .logo h1 {
        font-size: 24px;
    }
    
    .hero h2 {
        font-size: 28px;
    }
    
    .service-card {
        padding: 20px;
    }
}
CSS;
            
            Log::debug('CSS content generated', ['length' => strlen($css)]);
            return $css;
            
        } catch (\Exception $e) {
            Log::error('Failed to generate CSS content', ['error' => $e->getMessage()]);
            throw $e;
        }
    }
    
    /**
     * Generate default JavaScript content (optional)
     */
    private function generateDefaultJs(): string
    {
        try {
            $js = <<<JS
// Smooth scrolling for anchor links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        
        const targetId = this.getAttribute('href');
        if (targetId === '#') return;
        
        const targetElement = document.querySelector(targetId);
        if (targetElement) {
            window.scrollTo({
                top: targetElement.offsetTop - 80,
                behavior: 'smooth'
            });
        }
    });
});

// Navbar scroll effect
window.addEventListener('scroll', function() {
    const header = document.querySelector('.header');
    if (window.scrollY > 100) {
        header.style.boxShadow = '0 4px 20px rgba(0, 0, 0, 0.15)';
    } else {
        header.style.boxShadow = '0 2px 15px rgba(0, 0, 0, 0.1)';
    }
});

// Initialize on DOM load
document.addEventListener('DOMContentLoaded', function() {
    console.log('Template loaded successfully');
    
    // Add animation to service cards on scroll
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-fade-in');
            }
        });
    }, observerOptions);
    
    document.querySelectorAll('.service-card').forEach(card => {
        observer.observe(card);
    });
});
JS;
            
            Log::debug('JavaScript content generated', ['length' => strlen($js)]);
            return $js;
            
        } catch (\Exception $e) {
            Log::error('Failed to generate JavaScript content', ['error' => $e->getMessage()]);
            throw $e;
        }
    }
    
    /**
     * Create default pages for website
     */
    public function createDefaultPages(Website $website): void
    {
        try {
            Log::info('Creating default pages for website', ['website_id' => $website->id]);
            
            $pages = [
                [
                    'website_id' => $website->id,
                    'user_id' => $website->user_id,
                    'title' => 'Home',
                    'slug' => 'home',
                    'content' => '<h1>Welcome to ' . $this->escapeHtml($website->name) . '</h1>',
                    'meta_title' => 'Home - ' . $website->name,
                    'meta_description' => 'Welcome to ' . $website->name,
                    'is_homepage' => true,
                    'status' => 'published',
                ],
                [
                    'website_id' => $website->id,
                    'user_id' => $website->user_id,
                    'title' => 'About Us',
                    'slug' => 'about',
                    'content' => '<h1>About ' . $this->escapeHtml($website->name) . '</h1>',
                    'meta_title' => 'About Us - ' . $website->name,
                    'meta_description' => 'Learn more about ' . $website->name,
                    'status' => 'published',
                ],
                [
                    'website_id' => $website->id,
                    'user_id' => $website->user_id,
                    'title' => 'Contact',
                    'slug' => 'contact',
                    'content' => '<h1>Contact Us</h1>',
                    'meta_title' => 'Contact - ' . $website->name,
                    'meta_description' => 'Get in touch with ' . $website->name,
                    'status' => 'published',
                ],
            ];
            
            foreach ($pages as $pageData) {
                $page = Page::create($pageData);
                Log::debug('Default page created', [
                    'page_id' => $page->id,
                    'title' => $page->title,
                    'slug' => $page->slug
                ]);
            }
            
            Log::info('Default pages created successfully', [
                'website_id' => $website->id,
                'pages_count' => count($pages)
            ]);
            
        } catch (\Exception $e) {
            Log::error('Failed to create default pages', [
                'error' => $e->getMessage(),
                'website_id' => $website->id
            ]);
            throw $e;
        }
    }
    
    /**
     * Helper: Escape HTML
     */
    private function escapeHtml(string $text): string
    {
        return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
    }
    
    /**
     * Helper: Get current year
     */
    private function getCurrentYear(): string
    {
        return date('Y');
    }
    
    /**
     * Debug method to check template creation issues
     */
    public function debugTemplateCreation(Website $website): array
    {
        $debugInfo = [];
        
        try {
            // Check website
            $debugInfo['website_exists'] = $website->exists;
            $debugInfo['website_id'] = $website->id;
            $debugInfo['website_name'] = $website->name;
            $debugInfo['user_id'] = $website->user_id;
            
            // Check Template model
            $debugInfo['template_model'] = class_exists(Template::class) ? 'Exists' : 'Not found';
            
            // Check table exists
            $debugInfo['table_exists'] = \Schema::hasTable('templates');
            
            // Check columns
            if ($debugInfo['table_exists']) {
                $columns = \Schema::getColumnListing('templates');
                $debugInfo['columns'] = $columns;
                $debugInfo['has_website_id'] = in_array('website_id', $columns);
                $debugInfo['has_user_id'] = in_array('user_id', $columns);
            }
            
            // Try to create a simple template
            $testTemplate = null;
            try {
                $testTemplate = Template::create([
                    'website_id' => $website->id,
                    'user_id' => $website->user_id,
                    'name' => 'Test Template',
                    'slug' => 'test-template-' . time(),
                    'html_content' => '<div>Test</div>',
                    'css_content' => 'body { color: red; }',
                    'is_default' => false,
                    'status' => 'draft'
                ]);
                $debugInfo['simple_template_created'] = $testTemplate->exists;
                $debugInfo['simple_template_id'] = $testTemplate->id;
            } catch (\Exception $e) {
                $debugInfo['simple_template_error'] = $e->getMessage();
            }
            
        } catch (\Exception $e) {
            $debugInfo['debug_error'] = $e->getMessage();
        }
        
        Log::info('Template creation debug info', $debugInfo);
        return $debugInfo;
    }
}