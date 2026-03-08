<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Block;
use App\Models\Section;

class BlockSeeder extends Seeder
{
    public function run()
    {
        // Récupérer les sections
        $heroSection = Section::where('slug', 'hero-sections')->first();
        $featuresSection = Section::where('slug', 'features')->first();
        $contactSection = Section::where('slug', 'contact-forms')->first();
        $testimonialsSection = Section::where('slug', 'testimonials')->first();
        $pricingSection = Section::where('slug', 'pricing')->first();
        $headersSection = Section::where('slug', 'headers')->first();
        $footersSection = Section::where('slug', 'footers')->first();

        $blocks = [
            // Hero Sections
            [
                'name' => 'Hero SaaS Startup',
                'slug' => 'hero-saas-startup',
                'description' => 'Hero moderne pour startup SaaS avec CTA',
                'thumbnail' => null,
                'icon' => 'fa-rocket',
                'html_content' => '<section class="hero-saas" style="padding: 100px 20px; background: linear-gradient(135deg, #667eea, #764ba2); color: white; text-align: center;"><div class="container" style="max-width: 1200px; margin: 0 auto;"><h1 style="font-size: 3.5rem; margin-bottom: 20px;">Boost Your Productivity</h1><p style="font-size: 1.2rem; margin-bottom: 40px; opacity: 0.9;">An all-in-one solution for managing your projects and team collaboration.</p><div style="display: flex; gap: 20px; justify-content: center; flex-wrap: wrap;"><button style="padding: 15px 40px; background: white; color: #667eea; border: none; border-radius: 8px; font-size: 1.1rem; cursor: pointer; font-weight: bold;">Get Started Free</button><button style="padding: 15px 40px; background: transparent; color: white; border: 2px solid white; border-radius: 8px; font-size: 1.1rem; cursor: pointer;">View Demo</button></div></div></section>',
                'css_content' => '',
                'section_id' => $heroSection->id,
                'category' => 'Advanced',
                'website_type' => 'SaaS',
                'tags' => json_encode(['modern', 'gradient', 'cta', 'startup']),
                'is_responsive' => true,
                'is_free' => true,
                'width' => 1200,
                'order' => 1
            ],
            [
                'name' => 'Hero Restaurant',
                'slug' => 'hero-restaurant',
                'description' => 'Hero élégant pour restaurant avec image de fond',
                'thumbnail' => null,
                'icon' => 'fa-utensils',
                'html_content' => '<section class="hero-restaurant" style="padding: 150px 20px; background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url(\'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4\'); background-size: cover; background-position: center; color: white; text-align: center;"><div class="container" style="max-width: 800px; margin: 0 auto;"><h1 style="font-size: 4rem; margin-bottom: 20px; font-family: serif;">Authentic Italian Cuisine</h1><p style="font-size: 1.3rem; margin-bottom: 40px;">Experience the taste of Italy with our handcrafted dishes made from fresh ingredients.</p><button style="padding: 15px 50px; background: #dc2626; color: white; border: none; border-radius: 4px; font-size: 1.2rem; cursor: pointer; font-weight: bold;">Book a Table</button></div></section>',
                'css_content' => '',
                'section_id' => $heroSection->id,
                'category' => 'Advanced',
                'website_type' => 'Restaurant',
                'tags' => json_encode(['food', 'restaurant', 'elegant', 'dark']),
                'is_responsive' => true,
                'is_free' => true,
                'width' => 1200,
                'order' => 2
            ],

            // Features Sections
            [
                'name' => 'Features Grid 3 Columns',
                'slug' => 'features-grid-3-columns',
                'description' => 'Grille de fonctionnalités avec 3 colonnes',
                'thumbnail' => null,
                'icon' => 'fa-th-large',
                'html_content' => '<section style="padding: 80px 20px; background: #f9fafb;"><div class="container" style="max-width: 1200px; margin: 0 auto;"><h2 style="text-align: center; font-size: 2.5rem; margin-bottom: 50px;">Why Choose Us</h2><div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 40px;"><div style="text-align: center; padding: 40px 20px; background: white; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);"><div style="width: 70px; height: 70px; background: #3b82f6; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; color: white; font-size: 1.8rem;"><i class="fas fa-shield-alt"></i></div><h3 style="font-size: 1.5rem; margin-bottom: 15px;">Secure & Reliable</h3><p style="color: #6b7280;">Your data is protected with enterprise-grade security measures.</p></div><div style="text-align: center; padding: 40px 20px; background: white; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);"><div style="width: 70px; height: 70px; background: #10b981; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; color: white; font-size: 1.8rem;"><i class="fas fa-bolt"></i></div><h3 style="font-size: 1.5rem; margin-bottom: 15px;">Fast Performance</h3><p style="color: #6b7280;">Lightning-fast loading times for optimal user experience.</p></div><div style="text-align: center; padding: 40px 20px; background: white; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);"><div style="width: 70px; height: 70px; background: #8b5cf6; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; color: white; font-size: 1.8rem;"><i class="fas fa-headset"></i></div><h3 style="font-size: 1.5rem; margin-bottom: 15px;">24/7 Support</h3><p style="color: #6b7280;">Our team is always ready to help you with any questions.</p></div></div></div></section>',
                'css_content' => '',
                'section_id' => $featuresSection->id,
                'category' => 'Layout',
                'website_type' => 'General',
                'tags' => json_encode(['grid', 'features', 'cards', 'modern']),
                'is_responsive' => true,
                'is_free' => true,
                'width' => 1200,
                'order' => 1
            ],

            // Contact Forms
            [
                'name' => 'Contact Form Minimal',
                'slug' => 'contact-form-minimal',
                'description' => 'Formulaire de contact minimaliste',
                'thumbnail' => null,
                'icon' => 'fa-envelope',
                'html_content' => '<section style="padding: 80px 20px;"><div class="container" style="max-width: 600px; margin: 0 auto;"><h2 style="text-align: center; font-size: 2.5rem; margin-bottom: 40px;">Get In Touch</h2><form style="background: white; padding: 40px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.1);"><div style="margin-bottom: 20px;"><label style="display: block; margin-bottom: 8px; font-weight: 500;">Full Name</label><input type="text" placeholder="John Doe" style="width: 100%; padding: 12px 16px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 1rem;"></div><div style="margin-bottom: 20px;"><label style="display: block; margin-bottom: 8px; font-weight: 500;">Email Address</label><input type="email" placeholder="john@example.com" style="width: 100%; padding: 12px 16px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 1rem;"></div><div style="margin-bottom: 20px;"><label style="display: block; margin-bottom: 8px; font-weight: 500;">Message</label><textarea placeholder="Your message..." rows="5" style="width: 100%; padding: 12px 16px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 1rem; resize: vertical;"></textarea></div><button type="submit" style="width: 100%; padding: 15px; background: #3b82f6; color: white; border: none; border-radius: 8px; font-size: 1.1rem; cursor: pointer; font-weight: bold;">Send Message</button></form></div></section>',
                'css_content' => '',
                'section_id' => $contactSection->id,
                'category' => 'Basic',
                'website_type' => 'General',
                'tags' => json_encode(['form', 'contact', 'minimal', 'modern']),
                'is_responsive' => true,
                'is_free' => true,
                'width' => 600,
                'order' => 1
            ],

            // Testimonials
            [
                'name' => 'Testimonials Slider',
                'slug' => 'testimonials-slider',
                'description' => 'Section témoignages avec avatars',
                'thumbnail' => null,
                'icon' => 'fa-comment',
                'html_content' => '<section style="padding: 80px 20px; background: #f8fafc;"><div class="container" style="max-width: 1200px; margin: 0 auto;"><h2 style="text-align: center; font-size: 2.5rem; margin-bottom: 50px;">What Our Clients Say</h2><div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 30px;"><div style="background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.08);"><div style="display: flex; align-items: center; margin-bottom: 20px;"><div style="width: 60px; height: 60px; background: #3b82f6; border-radius: 50%; margin-right: 15px; overflow: hidden;"><img src="https://i.pravatar.cc/150?img=1" alt="Avatar" style="width: 100%; height: 100%; object-fit: cover;"></div><div><h4 style="margin: 0; font-size: 1.2rem;">Sarah Johnson</h4><p style="margin: 5px 0 0; color: #6b7280; font-size: 0.9rem;">CEO, TechSolutions</p></div></div><p style="color: #4b5563; line-height: 1.6;">"This service has completely transformed how we manage our projects. The team is always responsive and helpful."</p><div style="color: #fbbf24; margin-top: 15px;">★★★★★</div></div><div style="background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.08);"><div style="display: flex; align-items: center; margin-bottom: 20px;"><div style="width: 60px; height: 60px; background: #10b981; border-radius: 50%; margin-right: 15px; overflow: hidden;"><img src="https://i.pravatar.cc/150?img=2" alt="Avatar" style="width: 100%; height: 100%; object-fit: cover;"></div><div><h4 style="margin: 0; font-size: 1.2rem;">Michael Chen</h4><p style="margin: 5px 0 0; color: #6b7280; font-size: 0.9rem;">Marketing Director</p></div></div><p style="color: #4b5563; line-height: 1.6;">"The results exceeded our expectations. Our engagement increased by 40% after implementing their solutions."</p><div style="color: #fbbf24; margin-top: 15px;">★★★★★</div></div></div></div></section>',
                'css_content' => '',
                'section_id' => $testimonialsSection->id,
                'category' => 'Layout',
                'website_type' => 'General',
                'tags' => json_encode(['testimonials', 'reviews', 'avatars', 'cards']),
                'is_responsive' => true,
                'is_free' => true,
                'width' => 1200,
                'order' => 1
            ],

            // Pricing
            [
                'name' => 'Pricing Table 3 Plans',
                'slug' => 'pricing-table-3-plans',
                'description' => 'Table de prix avec 3 plans',
                'thumbnail' => null,
                'icon' => 'fa-tag',
                'html_content' => '<section style="padding: 80px 20px; background: #f9fafb;"><div class="container" style="max-width: 1200px; margin: 0 auto;"><h2 style="text-align: center; font-size: 2.5rem; margin-bottom: 20px;">Simple, Transparent Pricing</h2><p style="text-align: center; color: #6b7280; font-size: 1.1rem; margin-bottom: 60px;">Choose the plan that\'s right for your business</p><div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px;"><div style="background: white; padding: 40px; border-radius: 12px; border: 2px solid #e5e7eb; text-align: center; position: relative;"><div style="margin-bottom: 25px;"><h3 style="font-size: 1.5rem; margin-bottom: 15px;">Basic</h3><div style="font-size: 3rem; font-weight: bold; color: #1f2937;">$19<span style="font-size: 1rem; color: #6b7280;">/month</span></div></div><ul style="list-style: none; padding: 0; margin-bottom: 30px; text-align: left;"><li style="padding: 10px 0; border-bottom: 1px solid #f3f4f6;"><i class="fas fa-check" style="color: #10b981; margin-right: 10px;"></i> Up to 5 projects</li><li style="padding: 10px 0; border-bottom: 1px solid #f3f4f6;"><i class="fas fa-check" style="color: #10b981; margin-right: 10px;"></i> Basic analytics</li><li style="padding: 10px 0; border-bottom: 1px solid #f3f4f6;"><i class="fas fa-check" style="color: #10b981; margin-right: 10px;"></i> Email support</li></ul><button style="width: 100%; padding: 15px; background: white; color: #3b82f6; border: 2px solid #3b82f6; border-radius: 8px; font-size: 1.1rem; cursor: pointer; font-weight: bold;">Get Started</button></div><div style="background: white; padding: 40px; border-radius: 12px; border: 2px solid #3b82f6; text-align: center; position: relative; transform: scale(1.05); box-shadow: 0 10px 25px rgba(59, 130, 246, 0.2);"><div style="position: absolute; top: -12px; left: 50%; transform: translateX(-50%); background: #3b82f6; color: white; padding: 5px 20px; border-radius: 20px; font-size: 0.9rem; font-weight: bold;">MOST POPULAR</div><div style="margin-bottom: 25px;"><h3 style="font-size: 1.5rem; margin-bottom: 15px;">Pro</h3><div style="font-size: 3rem; font-weight: bold; color: #1f2937;">$49<span style="font-size: 1rem; color: #6b7280;">/month</span></div></div><ul style="list-style: none; padding: 0; margin-bottom: 30px; text-align: left;"><li style="padding: 10px 0; border-bottom: 1px solid #f3f4f6;"><i class="fas fa-check" style="color: #10b981; margin-right: 10px;"></i> Up to 50 projects</li><li style="padding: 10px 0; border-bottom: 1px solid #f3f4f6;"><i class="fas fa-check" style="color: #10b981; margin-right: 10px;"></i> Advanced analytics</li><li style="padding: 10px 0; border-bottom: 1px solid #f3f4f6;"><i class="fas fa-check" style="color: #10b981; margin-right: 10px;"></i> Priority support</li></ul><button style="width: 100%; padding: 15px; background: #3b82f6; color: white; border: none; border-radius: 8px; font-size: 1.1rem; cursor: pointer; font-weight: bold;">Get Started</button></div><div style="background: white; padding: 40px; border-radius: 12px; border: 2px solid #e5e7eb; text-align: center;"><div style="margin-bottom: 25px;"><h3 style="font-size: 1.5rem; margin-bottom: 15px;">Enterprise</h3><div style="font-size: 3rem; font-weight: bold; color: #1f2937;">$99<span style="font-size: 1rem; color: #6b7280;">/month</span></div></div><ul style="list-style: none; padding: 0; margin-bottom: 30px; text-align: left;"><li style="padding: 10px 0; border-bottom: 1px solid #f3f4f6;"><i class="fas fa-check" style="color: #10b981; margin-right: 10px;"></i> Unlimited projects</li><li style="padding: 10px 0; border-bottom: 1px solid #f3f4f6;"><i class="fas fa-check" style="color: #10b981; margin-right: 10px;"></i> Custom analytics</li><li style="padding: 10px 0; border-bottom: 1px solid #f3f4f6;"><i class="fas fa-check" style="color: #10b981; margin-right: 10px;"></i> 24/7 phone support</li></ul><button style="width: 100%; padding: 15px; background: white; color: #3b82f6; border: 2px solid #3b82f6; border-radius: 8px; font-size: 1.1rem; cursor: pointer; font-weight: bold;">Contact Sales</button></div></div></div></section>',
                'css_content' => '',
                'section_id' => $pricingSection->id,
                'category' => 'Advanced',
                'website_type' => 'SaaS',
                'tags' => json_encode(['pricing', 'plans', 'comparison', 'business']),
                'is_responsive' => true,
                'is_free' => true,
                'width' => 1200,
                'order' => 1
            ],

            // Headers
            [
                'name' => 'Header Navigation Dark',
                'slug' => 'header-navigation-dark',
                'description' => 'Navigation sombre avec logo et menu',
                'thumbnail' => null,
                'icon' => 'fa-bars',
                'html_content' => '<header style="background: #1f2937; color: white; padding: 20px 0; position: sticky; top: 0; z-index: 1000;"><div class="container" style="max-width: 1200px; margin: 0 auto; padding: 0 20px; display: flex; align-items: center; justify-content: space-between;"><div style="display: flex; align-items: center; gap: 10px; font-size: 1.5rem; font-weight: bold;"><i class="fas fa-rocket" style="color: #3b82f6;"></i><span>BrandName</span></div><nav style="display: flex; gap: 30px; align-items: center;"><a href="#" style="color: #d1d5db; text-decoration: none; font-weight: 500; transition: color 0.3s;">Home</a><a href="#" style="color: #d1d5db; text-decoration: none; font-weight: 500; transition: color 0.3s;">Features</a><a href="#" style="color: #d1d5db; text-decoration: none; font-weight: 500; transition: color 0.3s;">Pricing</a><a href="#" style="color: #d1d5db; text-decoration: none; font-weight: 500; transition: color 0.3s;">About</a><button style="padding: 10px 25px; background: #3b82f6; color: white; border: none; border-radius: 6px; font-weight: bold; cursor: pointer;">Get Started</button></nav></div></header>',
                'css_content' => '',
                'section_id' => $headersSection->id,
                'category' => 'Basic',
                'website_type' => 'General',
                'tags' => json_encode(['header', 'navigation', 'dark', 'sticky']),
                'is_responsive' => true,
                'is_free' => true,
                'width' => 1200,
                'order' => 1
            ],

            // Footers
            [
                'name' => 'Footer 4 Columns',
                'slug' => 'footer-4-columns',
                'description' => 'Pied de page avec 4 colonnes et liens sociaux',
                'thumbnail' => null,
                'icon' => 'fa-window-minimize',
                'html_content' => '<footer style="background: #111827; color: white; padding: 60px 20px 30px;"><div class="container" style="max-width: 1200px; margin: 0 auto;"><div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 40px; margin-bottom: 50px;"><div><div style="display: flex; align-items: center; gap: 10px; font-size: 1.5rem; font-weight: bold; margin-bottom: 20px;"><i class="fas fa-rocket" style="color: #3b82f6;"></i><span>BrandName</span></div><p style="color: #9ca3af; line-height: 1.6;">We help businesses grow with innovative digital solutions and outstanding customer support.</p><div style="display: flex; gap: 15px; margin-top: 20px;"><a href="#" style="width: 40px; height: 40px; background: #374151; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; text-decoration: none;"><i class="fab fa-twitter"></i></a><a href="#" style="width: 40px; height: 40px; background: #374151; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; text-decoration: none;"><i class="fab fa-facebook-f"></i></a><a href="#" style="width: 40px; height: 40px; background: #374151; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; text-decoration: none;"><i class="fab fa-linkedin-in"></i></a><a href="#" style="width: 40px; height: 40px; background: #374151; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; text-decoration: none;"><i class="fab fa-instagram"></i></a></div></div><div><h4 style="font-size: 1.2rem; margin-bottom: 20px; color: white;">Quick Links</h4><ul style="list-style: none; padding: 0;"><li style="margin-bottom: 10px;"><a href="#" style="color: #9ca3af; text-decoration: none; transition: color 0.3s;">Home</a></li><li style="margin-bottom: 10px;"><a href="#" style="color: #9ca3af; text-decoration: none; transition: color 0.3s;">About Us</a></li><li style="margin-bottom: 10px;"><a href="#" style="color: #9ca3af; text-decoration: none; transition: color 0.3s;">Services</a></li><li style="margin-bottom: 10px;"><a href="#" style="color: #9ca3af; text-decoration: none; transition: color 0.3s;">Portfolio</a></li></ul></div><div><h4 style="font-size: 1.2rem; margin-bottom: 20px; color: white;">Services</h4><ul style="list-style: none; padding: 0;"><li style="margin-bottom: 10px;"><a href="#" style="color: #9ca3af; text-decoration: none; transition: color 0.3s;">Web Design</a></li><li style="margin-bottom: 10px;"><a href="#" style="color: #9ca3af; text-decoration: none; transition: color 0.3s;">Development</a></li><li style="margin-bottom: 10px;"><a href="#" style="color: #9ca3af; text-decoration: none; transition: color 0.3s;">Marketing</a></li><li style="margin-bottom: 10px;"><a href="#" style="color: #9ca3af; text-decoration: none; transition: color 0.3s;">SEO</a></li></ul></div><div><h4 style="font-size: 1.2rem; margin-bottom: 20px; color: white;">Contact</h4><ul style="list-style: none; padding: 0;"><li style="margin-bottom: 10px; color: #9ca3af;"><i class="fas fa-map-marker-alt" style="margin-right: 10px; color: #3b82f6;"></i>123 Business Street</li><li style="margin-bottom: 10px; color: #9ca3af;"><i class="fas fa-phone" style="margin-right: 10px; color: #3b82f6;"></i>(123) 456-7890</li><li style="margin-bottom: 10px; color: #9ca3af;"><i class="fas fa-envelope" style="margin-right: 10px; color: #3b82f6;"></i>info@example.com</li></ul></div></div><div style="border-top: 1px solid #374151; padding-top: 30px; text-align: center; color: #9ca3af; font-size: 0.9rem;">© 2024 BrandName. All rights reserved. | <a href="#" style="color: #3b82f6; text-decoration: none;">Privacy Policy</a> | <a href="#" style="color: #3b82f6; text-decoration: none;">Terms of Service</a></div></div></footer>',
                'css_content' => '',
                'section_id' => $footersSection->id,
                'category' => 'Advanced',
                'website_type' => 'General',
                'tags' => json_encode(['footer', 'social', 'links', 'contact']),
                'is_responsive' => true,
                'is_free' => true,
                'width' => 1200,
                'order' => 1
            ]
        ];

        foreach ($blocks as $block) {
            Block::create($block);
        }
    }
}