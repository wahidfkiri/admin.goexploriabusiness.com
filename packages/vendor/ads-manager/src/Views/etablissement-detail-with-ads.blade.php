{{--
    ====================================================================
    EXEMPLE DE PAGE : Détail d'un établissement avec zones publicitaires
    ====================================================================

    Instructions d'intégration :
    1. Ajoutez @adZone('code_zone') dans vos vues Blade
    2. Assurez-vous d'avoir `<meta name="csrf-token" content="{{ csrf_token() }}">` dans <head>
    3. Les zones s'affichent automatiquement avec les annonces actives correspondantes

    ZONES UTILISÉES DANS CET EXEMPLE :
    - leaderboard_top       : Bannière en haut (970×90)
    - sidebar_right_detail  : Sidebar droite (300×250)
    - content_middle_detail : Milieu du contenu (728×90)
    - content_bottom_detail : Bas du contenu (300×250)
    - sidebar_left_detail   : Sidebar gauche (160×600)
    ====================================================================
--}}

@extends('layouts.app')

@section('content')

{{-- ============================================================ --}}
{{-- LEADERBOARD TOP — 970×90 en haut de page                     --}}
{{-- ============================================================ --}}
<div class="ad-top-banner">
    @adZone('leaderboard_top')
</div>

<div class="page-container">

    {{-- ============================================================ --}}
    {{-- LEFT SIDEBAR — Skyscraper 160×600                            --}}
    {{-- ============================================================ --}}
    <aside class="sidebar-left">
        @adZone('sidebar_left_detail')
    </aside>

    {{-- ============================================================ --}}
    {{-- MAIN CONTENT                                                   --}}
    {{-- ============================================================ --}}
    <main class="main-content">

        {{-- Breadcrumb --}}
        <nav class="breadcrumb-modern">
            <a href="/">Accueil</a>
            <span>/</span>
            <a href="/etablissements">Établissements</a>
            <span>/</span>
            <span>{{ $etablissement->name ?? 'Exemple Établissement' }}</span>
        </nav>

        {{-- Establishment Hero --}}
        <div class="etab-hero">
            <div class="etab-hero-image">
                <img src="{{ $etablissement->cover_image ?? 'https://via.placeholder.com/800x300/667eea/ffffff?text=Établissement' }}"
                     alt="{{ $etablissement->name ?? 'Établissement' }}"
                     class="hero-img">
                <div class="hero-overlay">
                    <div class="etab-avatar">
                        @if(!empty($etablissement->logo))
                            <img src="{{ $etablissement->logo }}" alt="logo">
                        @else
                            <span>{{ strtoupper(substr($etablissement->name ?? 'E', 0, 2)) }}</span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="etab-info">
                <div class="etab-meta-left">
                    <h1 class="etab-name">{{ $etablissement->name ?? 'Nom de l\'Établissement' }}</h1>
                    <div class="etab-tags">
                        <span class="etab-tag"><i class="fas fa-map-marker-alt"></i> {{ $etablissement->city ?? 'Tunis' }}</span>
                        <span class="etab-tag"><i class="fas fa-graduation-cap"></i> {{ $etablissement->type ?? 'Université' }}</span>
                        @if(!empty($etablissement->rating))
                        <span class="etab-tag star"><i class="fas fa-star"></i> {{ number_format($etablissement->rating, 1) }}/5</span>
                        @endif
                    </div>
                    <p class="etab-desc">{{ $etablissement->description ?? 'Description de l\'établissement. Cet exemple montre comment les publicités s\'intègrent naturellement dans le contenu.' }}</p>
                </div>
                <div class="etab-meta-right">
                    <a href="#" class="btn-cta">Contacter</a>
                    <a href="#" class="btn-outline">Site web</a>
                </div>
            </div>
        </div>

        {{-- ======================================================== --}}
        {{-- AD ZONE : CONTENT TOP (après le hero, avant le contenu)   --}}
        {{-- Format : bannière horizontale 728×90                       --}}
        {{-- ======================================================== --}}
        <div class="ad-zone-inline ad-zone-labeled">
            @adZone('content_top_detail')
        </div>

        {{-- Main content + right sidebar --}}
        <div class="content-with-sidebar">

            {{-- Content --}}
            <div class="content-body">

                {{-- About Section --}}
                <section class="content-section">
                    <h2 class="section-title"><i class="fas fa-info-circle"></i> À propos</h2>
                    <div class="section-body">
                        <p>{{ $etablissement->about ?? 'Contenu détaillé de la présentation de l\'établissement. Informations académiques, historique, valeurs et missions de l\'institution.' }}</p>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris.</p>
                    </div>
                </section>

                {{-- ================================================ --}}
                {{-- AD ZONE : CONTENT MIDDLE (au milieu du contenu)   --}}
                {{-- Format : bannière horizontale 728×90               --}}
                {{-- ================================================ --}}
                <div class="ad-zone-inline ad-zone-labeled">
                    @adZone('content_middle_detail')
                </div>

                {{-- Formations Section --}}
                <section class="content-section">
                    <h2 class="section-title"><i class="fas fa-book-open"></i> Formations proposées</h2>
                    <div class="formations-grid">
                        @php
                            $formations = $etablissement->formations ?? [
                                ['name'=>'Licence Informatique','duration'=>'3 ans','level'=>'Bac+3'],
                                ['name'=>'Master Intelligence Artificielle','duration'=>'2 ans','level'=>'Bac+5'],
                                ['name'=>'Ingénierie Logicielle','duration'=>'5 ans','level'=>'Bac+5'],
                                ['name'=>'DUT Réseaux & Télécoms','duration'=>'2 ans','level'=>'Bac+2'],
                            ];
                        @endphp
                        @foreach($formations as $f)
                        <div class="formation-card">
                            <div class="formation-icon"><i class="fas fa-graduation-cap"></i></div>
                            <div class="formation-info">
                                <div class="formation-name">{{ is_array($f) ? $f['name'] : $f->name }}</div>
                                <div class="formation-meta">
                                    <span>{{ is_array($f) ? $f['duration'] : $f->duration }}</span>
                                    <span class="badge-level">{{ is_array($f) ? $f['level'] : $f->level }}</span>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </section>

                {{-- ================================================ --}}
                {{-- AD ZONE : INTERSTITIEL (entre sections)           --}}
                {{-- Format : interstitiel 600×500                      --}}
                {{-- ================================================ --}}
                <div class="ad-zone-inline ad-zone-labeled ad-zone-centered">
                    @adZone('interstitiel_detail')
                </div>

                {{-- Gallery --}}
                <section class="content-section">
                    <h2 class="section-title"><i class="fas fa-images"></i> Galerie</h2>
                    <div class="gallery-grid">
                        @for($i = 1; $i <= 4; $i++)
                        <div class="gallery-item">
                            <img src="https://via.placeholder.com/300x200/{{ ['667eea','764ba2','06b48a','ffb347'][$i-1] }}/ffffff?text=Photo+{{ $i }}"
                                 alt="Photo {{ $i }}" class="gallery-img">
                        </div>
                        @endfor
                    </div>
                </section>

                {{-- ================================================ --}}
                {{-- AD ZONE : CONTENT BOTTOM (bas du contenu)         --}}
                {{-- Format : rectangle 300×250                         --}}
                {{-- ================================================ --}}
                <div class="ad-zone-inline ad-zone-labeled">
                    @adZone('content_bottom_detail')
                </div>

            </div>

            {{-- ======================================================== --}}
            {{-- RIGHT SIDEBAR — Rectangle 300×250 + Rectangle 300×250    --}}
            {{-- ======================================================== --}}
            <aside class="sidebar-right">

                {{-- Contact card --}}
                <div class="sidebar-card">
                    <h3 class="sidebar-card-title"><i class="fas fa-address-card"></i> Contact</h3>
                    <div class="contact-list">
                        <div class="contact-item"><i class="fas fa-map-marker-alt"></i> {{ $etablissement->address ?? '123 Rue de l\'Université, Tunis' }}</div>
                        <div class="contact-item"><i class="fas fa-phone"></i> {{ $etablissement->phone ?? '+216 71 000 000' }}</div>
                        <div class="contact-item"><i class="fas fa-envelope"></i> {{ $etablissement->email ?? 'contact@etablissement.tn' }}</div>
                        <div class="contact-item"><i class="fas fa-globe"></i> {{ $etablissement->website ?? 'www.etablissement.tn' }}</div>
                    </div>
                </div>

                {{-- AD ZONE : SIDEBAR TOP --}}
                <div class="ad-zone-sidebar ad-zone-labeled">
                    @adZone('sidebar_right_detail')
                </div>

                {{-- Quick Stats --}}
                <div class="sidebar-card">
                    <h3 class="sidebar-card-title"><i class="fas fa-chart-bar"></i> Chiffres clés</h3>
                    <div class="stats-mini-grid">
                        <div class="stat-mini"><div class="stat-mini-val">{{ $etablissement->students_count ?? '5 000+' }}</div><div class="stat-mini-lbl">Étudiants</div></div>
                        <div class="stat-mini"><div class="stat-mini-val">{{ $etablissement->teachers_count ?? '300' }}</div><div class="stat-mini-lbl">Enseignants</div></div>
                        <div class="stat-mini"><div class="stat-mini-val">{{ $etablissement->programs_count ?? '25' }}</div><div class="stat-mini-lbl">Formations</div></div>
                        <div class="stat-mini"><div class="stat-mini-val">{{ $etablissement->founded_year ?? '1990' }}</div><div class="stat-mini-lbl">Fondé en</div></div>
                    </div>
                </div>

                {{-- AD ZONE : SIDEBAR BOTTOM --}}
                <div class="ad-zone-sidebar ad-zone-labeled">
                    @adZone('sidebar_right_bottom_detail')
                </div>

            </aside>
        </div>

    </main>

</div>

{{-- ============================================================ --}}
{{-- FOOTER AD ZONE — Leaderboard 970×90 ou Banner 728×90         --}}
{{-- ============================================================ --}}
<div class="ad-footer-banner">
    @adZone('footer_banner')
</div>

@endsection

@push('styles')
<style>
/* ================================================================
   PAGE LAYOUT
   ================================================================ */
.page-container {
    display: flex;
    gap: 24px;
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 20px;
}

/* ================================================================
   AD ZONES — VISUAL LABELS (remove in production or keep subtle)
   ================================================================ */
.ad-zone-labeled {
    position: relative;
}
.ad-zone-labeled::before {
    content: 'ZONE PUB';
    position: absolute;
    top: -10px;
    left: 50%;
    transform: translateX(-50%);
    background: #667eea;
    color: #fff;
    font-size: 9px;
    font-weight: 700;
    padding: 2px 8px;
    border-radius: 20px;
    letter-spacing: 1px;
    z-index: 10;
    pointer-events: none;
}

/* Top leaderboard ad */
.ad-top-banner {
    display: flex;
    justify-content: center;
    padding: 12px 0;
    background: #f8fafc;
    border-bottom: 1px solid #eef2f6;
    margin-bottom: 20px;
}

/* Footer banner ad */
.ad-footer-banner {
    display: flex;
    justify-content: center;
    padding: 20px;
    background: #f8fafc;
    border-top: 1px solid #eef2f6;
    margin-top: 40px;
}

/* Left sidebar */
.sidebar-left {
    width: 180px;
    flex-shrink: 0;
    padding-top: 20px;
}

/* Right sidebar */
.sidebar-right {
    width: 320px;
    flex-shrink: 0;
    display: flex;
    flex-direction: column;
    gap: 20px;
}

/* Sidebar ad zones */
.ad-zone-sidebar {
    margin: 0;
}

/* Inline content ad zones */
.ad-zone-inline {
    margin: 28px 0;
    padding-top: 12px;
    display: flex;
    justify-content: flex-start;
}
.ad-zone-centered {
    justify-content: center;
}

/* ================================================================
   MAIN CONTENT
   ================================================================ */
.main-content {
    flex: 1;
    min-width: 0;
}

/* Breadcrumb */
.breadcrumb-modern {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
    color: #64748b;
    margin-bottom: 20px;
    padding: 10px 0;
}
.breadcrumb-modern a { color: #667eea; text-decoration: none; }
.breadcrumb-modern a:hover { text-decoration: underline; }
.breadcrumb-modern span { color: #94a3b8; }

/* Hero */
.etab-hero {
    background: #fff;
    border-radius: 20px;
    box-shadow: 0 4px 20px rgba(0,0,0,.06);
    overflow: hidden;
    margin-bottom: 24px;
}
.etab-hero-image { position: relative; height: 200px; overflow: hidden; }
.hero-img { width: 100%; height: 100%; object-fit: cover; }
.hero-overlay { position: absolute; bottom: -30px; left: 24px; }
.etab-avatar {
    width: 72px; height: 72px; border-radius: 16px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    border: 3px solid #fff;
    display: flex; align-items: center; justify-content: center;
    color: #fff; font-size: 20px; font-weight: 700;
    box-shadow: 0 4px 12px rgba(0,0,0,.15);
    overflow: hidden;
}
.etab-avatar img { width: 100%; height: 100%; object-fit: cover; }
.etab-info {
    display: flex; justify-content: space-between; align-items: flex-start;
    padding: 42px 24px 24px; gap: 16px; flex-wrap: wrap;
}
.etab-meta-left { flex: 1; }
.etab-name { font-size: 22px; font-weight: 700; color: #1e293b; margin-bottom: 10px; }
.etab-tags { display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 12px; }
.etab-tag {
    background: #f1f5f9; color: #475569; padding: 4px 12px;
    border-radius: 20px; font-size: 12px; font-weight: 500;
    display: flex; align-items: center; gap: 6px;
}
.etab-tag.star { background: #fffbeb; color: #d97706; }
.etab-desc { font-size: 14px; color: #64748b; line-height: 1.6; }
.etab-meta-right { display: flex; flex-direction: column; gap: 8px; }
.btn-cta {
    padding: 10px 20px; background: linear-gradient(135deg, #667eea, #764ba2);
    color: #fff; border-radius: 12px; font-size: 14px; font-weight: 600;
    text-decoration: none; display: inline-flex; align-items: center; white-space: nowrap;
}
.btn-outline {
    padding: 10px 20px; border: 2px solid #e2e8f0;
    color: #475569; border-radius: 12px; font-size: 14px; font-weight: 500;
    text-decoration: none; display: inline-flex; align-items: center; white-space: nowrap;
}

/* Content area */
.content-with-sidebar { display: flex; gap: 24px; align-items: flex-start; }
.content-body { flex: 1; min-width: 0; }

/* Sections */
.content-section { background: #fff; border-radius: 20px; padding: 24px; margin-bottom: 20px; box-shadow: 0 4px 16px rgba(0,0,0,.04); }
.section-title { font-size: 18px; font-weight: 700; color: #1e293b; margin-bottom: 16px; display: flex; align-items: center; gap: 10px; }
.section-title i { color: #667eea; }
.section-body p { font-size: 14px; color: #475569; line-height: 1.7; margin-bottom: 12px; }

/* Formations */
.formations-grid { display: flex; flex-direction: column; gap: 10px; }
.formation-card {
    display: flex; align-items: center; gap: 14px;
    padding: 14px; background: #f8fafc; border-radius: 14px;
    border: 1px solid #eef2f6; transition: all .2s;
}
.formation-card:hover { border-color: #667eea; background: #eef2ff; }
.formation-icon { width: 40px; height: 40px; background: linear-gradient(135deg, #667eea, #764ba2); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 16px; flex-shrink: 0; }
.formation-name { font-size: 14px; font-weight: 600; color: #1e293b; }
.formation-meta { display: flex; gap: 10px; align-items: center; margin-top: 4px; font-size: 12px; color: #64748b; }
.badge-level { background: #eef2ff; color: #667eea; padding: 2px 8px; border-radius: 20px; font-size: 11px; font-weight: 600; }

/* Gallery */
.gallery-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px; }
.gallery-item { border-radius: 12px; overflow: hidden; aspect-ratio: 3/2; }
.gallery-img { width: 100%; height: 100%; object-fit: cover; transition: transform .3s; }
.gallery-img:hover { transform: scale(1.05); }

/* Sidebar Cards */
.sidebar-card { background: #fff; border-radius: 20px; padding: 20px; box-shadow: 0 4px 16px rgba(0,0,0,.05); }
.sidebar-card-title { font-size: 15px; font-weight: 700; color: #1e293b; margin-bottom: 14px; display: flex; align-items: center; gap: 8px; }
.sidebar-card-title i { color: #667eea; }
.contact-list { display: flex; flex-direction: column; gap: 10px; }
.contact-item { font-size: 13px; color: #475569; display: flex; align-items: center; gap: 10px; }
.contact-item i { width: 16px; color: #667eea; flex-shrink: 0; }
.stats-mini-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
.stat-mini { text-align: center; background: #f8fafc; border-radius: 12px; padding: 12px 6px; }
.stat-mini-val { font-size: 18px; font-weight: 700; color: #1e293b; }
.stat-mini-lbl { font-size: 11px; color: #94a3b8; margin-top: 2px; }

/* Responsive */
@media (max-width: 1200px) {
    .sidebar-left { display: none; }
}
@media (max-width: 900px) {
    .content-with-sidebar { flex-direction: column; }
    .sidebar-right { width: 100%; }
    .page-container { flex-direction: column; }
}
@media (max-width: 600px) {
    .gallery-grid { grid-template-columns: 1fr; }
    .etab-info { flex-direction: column; }
    .etab-meta-right { flex-direction: row; }
}
</style>
@endpush