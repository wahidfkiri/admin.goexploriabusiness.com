<div class="col-md-3">
    <div class="vertical-tabs-wrapper-modern">
        <!-- Profile Header -->
        <div class="profile-header-modern">
            <div class="profile-avatar-modern">
                <div class="avatar-gradient">
                    @if(has_logo())
                        <img src="{{ get_logo_url() }}" alt="Logo de l'établissement" class="avatar-image">
                    @else
                    <i class="fas fa-building"></i>
                    @endif
                </div>
                <div class="avatar-status {{ ($stats['etablissement']->is_active ?? false) ? 'active' : 'inactive' }}"></div>
            </div>
            <div class="profile-info-modern">
                <h3 class="profile-name-modern">{{ $stats['etablissement']->name ?? 'Établissement' }}</h3>
                <p class="profile-type-modern">{{ $stats['etablissement']->lname ?? 'CMS Management' }}</p>
                <div class="profile-badge-modern">
                    @if(($stats['etablissement']->is_active ?? false))
                        <span class="badge-active"><i class="fas fa-check-circle"></i> Actif</span>
                    @else
                        <span class="badge-inactive"><i class="fas fa-circle"></i> Inactif</span>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Search Bar -->
        <div class="tabs-search-modern">
            <div class="search-input-wrapper">
                <i class="fas fa-search search-icon"></i>
                <input type="text" id="tabSearchInput" placeholder="Rechercher un menu..." class="search-input">
            </div>
        </div>
        
        <!-- Navigation Links -->
        <div class="nav flex-column vertical-tabs-modern">
            <a href="{{ route('cms.admin.dashboard', ['etablissementId' => $stats['etablissement']->id, 'section' => 'dashboard']) }}" 
               class="nav-link-modern {{ request()->get('section', 'dashboard') == 'dashboard' ? 'active' : '' }}" 
               data-section="v-pills-dashboard"
               onclick="navigateTo('v-pills-dashboard'); return false;">
                <div class="tab-icon-wrapper">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="tab-content-wrapper-modern">
                    <span class="tab-title-modern">Tableau de bord</span>
                    <span class="tab-description">Vue d'ensemble du site</span>
                </div>
                <div class="tab-badge"></div>
            </a>
            
            <a href="{{ route('cms.admin.dashboard', ['etablissementId' => $stats['etablissement']->id, 'section' => 'pages']) }}" 
               class="nav-link-modern {{ request()->get('section') == 'pages' ? 'active' : '' }}" 
               data-section="v-pills-pages"
               onclick="navigateTo('v-pills-pages'); return false;">
                <div class="tab-icon-wrapper">
                    <i class="fas fa-file-alt"></i>
                </div>
                <div class="tab-content-wrapper-modern">
                    <span class="tab-title-modern">Pages</span>
                    <span class="tab-description">Gérer votre contenu</span>
                </div>
                <div class="tab-badge">
                    <span class="badge-count">{{ $stats['total_pages'] ?? 0 }}</span>
                </div>
            </a>
            
            <a href="{{ route('cms.admin.dashboard', ['etablissementId' => $stats['etablissement']->id, 'section' => 'themes']) }}" 
               class="nav-link-modern {{ request()->get('section') == 'themes' ? 'active' : '' }}" 
               data-section="v-pills-themes"
               onclick="navigateTo('v-pills-themes'); return false;">
                <div class="tab-icon-wrapper">
                    <i class="fas fa-palette"></i>
                </div>
                <div class="tab-content-wrapper-modern">
                    <span class="tab-title-modern">Thèmes</span>
                    <span class="tab-description">Apparence du site</span>
                </div>
                <div class="tab-badge">
                    <span class="badge-count">{{ $stats['total_themes'] ?? 0 }}</span>
                </div>
            </a>
            
            <a href="{{ route('cms.admin.dashboard', ['etablissementId' => $stats['etablissement']->id, 'section' => 'config']) }}" 
               class="nav-link-modern {{ request()->get('section') == 'config' ? 'active' : '' }}" 
               data-section="v-pills-config"
               onclick="navigateTo('v-pills-config'); return false;">
                <div class="tab-icon-wrapper">
                    <i class="fas fa-sliders-h"></i>
                </div>
                <div class="tab-content-wrapper-modern">
                    <span class="tab-title-modern">Configuration</span>
                    <span class="tab-description">Paramètres généraux</span>
                </div>
                <div class="tab-badge"></div>
            </a>
            
            <a href="{{ route('cms.admin.dashboard', ['etablissementId' => $stats['etablissement']->id, 'section' => 'social']) }}" 
               class="nav-link-modern {{ request()->get('section') == 'social' ? 'active' : '' }}" 
               data-section="v-pills-social"
               onclick="navigateTo('v-pills-social'); return false;">
                <div class="tab-icon-wrapper">
                    <i class="fas fa-share-alt"></i>
                </div>
                <div class="tab-content-wrapper-modern">
                    <span class="tab-title-modern">Réseaux sociaux</span>
                    <span class="tab-description">Liens et partages</span>
                </div>
                <div class="tab-badge"></div>
            </a>
            
            <a href="{{ route('cms.admin.dashboard', ['etablissementId' => $stats['etablissement']->id, 'section' => 'seo']) }}" 
               class="nav-link-modern {{ request()->get('section') == 'seo' ? 'active' : '' }}" 
               data-section="v-pills-seo"
               onclick="navigateTo('v-pills-seo'); return false;">
                <div class="tab-icon-wrapper">
                    <i class="fas fa-search"></i>
                </div>
                <div class="tab-content-wrapper-modern">
                    <span class="tab-title-modern">SEO</span>
                    <span class="tab-description">Optimisation moteurs</span>
                </div>
                <div class="tab-badge"></div>
            </a>
            
            <a href="{{ route('cms.admin.dashboard', ['etablissementId' => $stats['etablissement']->id, 'section' => 'media']) }}" 
               class="nav-link-modern {{ request()->get('section') == 'media' ? 'active' : '' }}" 
               data-section="v-pills-media"
               onclick="navigateTo('v-pills-media'); return false;">
                <div class="tab-icon-wrapper">
                    <i class="fas fa-images"></i>
                </div>
                <div class="tab-content-wrapper-modern">
                    <span class="tab-title-modern">Médiathèque</span>
                    <span class="tab-description">Images et fichiers</span>
                </div>
                <div class="tab-badge">
                    <span class="badge-count">{{ $stats['media_count'] ?? 0 }}</span>
                </div>
            </a>
            
            <a href="{{ route('cms.admin.dashboard', ['etablissementId' => $stats['etablissement']->id, 'section' => 'newsletter']) }}" 
               class="nav-link-modern {{ request()->get('section') == 'newsletter' ? 'active' : '' }}" 
               data-section="v-pills-newsletter"
               onclick="navigateTo('v-pills-newsletter'); return false;">
                <div class="tab-icon-wrapper">
                    <i class="fas fa-envelope-open-text"></i>
                </div>
                <div class="tab-content-wrapper-modern">
                    <span class="tab-title-modern">Newsletter</span>
                    <span class="tab-description">Campagnes emails</span>
                </div>
                <div class="tab-badge"></div>
            </a>
            
            <a href="{{ route('cms.admin.dashboard', ['etablissementId' => $stats['etablissement']->id, 'section' => 'subscribers']) }}" 
               class="nav-link-modern {{ request()->get('section') == 'subscribers' ? 'active' : '' }}" 
               data-section="v-pills-subscribers"
               onclick="navigateTo('v-pills-subscribers'); return false;">
                <div class="tab-icon-wrapper">
                    <i class="fas fa-users"></i>
                </div>
                <div class="tab-content-wrapper-modern">
                    <span class="tab-title-modern">Abonnés</span>
                    <span class="tab-description">Liste des contacts</span>
                </div>
                <div class="tab-badge">
                    <span class="badge-count">{{ $stats['subscribers_count'] ?? 0 }}</span>
                </div>
            </a>
            
            @php
                $notificationsCount = ($stats['comments_count'] ?? 0) + ($stats['pending_pages'] ?? 0);
            @endphp
            
            <a href="{{ route('cms.admin.dashboard', ['etablissementId' => $stats['etablissement']->id, 'section' => 'comments']) }}" 
               class="nav-link-modern {{ request()->get('section') == 'comments' ? 'active' : '' }}" 
               data-section="v-pills-comments"
               onclick="navigateTo('v-pills-comments'); return false;">
                <div class="tab-icon-wrapper">
                    <i class="fas fa-comments"></i>
                </div>
                <div class="tab-content-wrapper-modern">
                    <span class="tab-title-modern">Commentaires</span>
                    <span class="tab-description">Modération</span>
                </div>
                <div class="tab-badge">
                    @if($notificationsCount > 0)
                        <span class="badge-count has-notification">{{ $notificationsCount }}</span>
                    @else
                        <span class="badge-count">{{ $stats['comments_count'] ?? 0 }}</span>
                    @endif
                </div>
            </a>
            
            <div class="divider-modern"></div>
            
            <a href="{{ route('cms.admin.dashboard', ['etablissementId' => $stats['etablissement']->id, 'section' => 'danger']) }}" 
               class="nav-link-modern danger {{ request()->get('section') == 'danger' ? 'active' : '' }}" 
               data-section="v-pills-danger"
               onclick="navigateTo('v-pills-danger'); return false;">
                <div class="tab-icon-wrapper">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="tab-content-wrapper-modern">
                    <span class="tab-title-modern">Zone de danger</span>
                    <span class="tab-description">Actions sensibles</span>
                </div>
                <div class="tab-badge"></div>
            </a>
        </div>
    </div>
</div>