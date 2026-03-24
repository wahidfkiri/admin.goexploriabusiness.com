@extends('layouts.app')

@section('content')
    <!-- MAIN CONTENT -->
    <main class="dashboard-content">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">
                <span class="page-title-icon"><i class="fas fa-cms"></i></span>
                 Espace entreprise
            </h1>
            
            <div class="page-actions">
                <a href="{{ route('cms.admin.pages.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus-circle me-2"></i>Nouvelle page
                </a>
                <a href="{{ url('cms.admin.themes.upload') }}" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#uploadThemeModal">
                    <i class="fas fa-upload me-2"></i>Uploader un thème
                </a>
            </div>
        </div>
        
        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stats-card-modern">
                <div class="stats-header-modern">
                    <div>
                        <div class="stats-value-modern">{{ $stats['total_pages'] ?? 0 }}</div>
                        <div class="stats-label-modern">Total des pages</div>
                    </div>
                    <div class="stats-icon-modern" style="background: linear-gradient(135deg, var(--primary-color), #3a56e4);">
                        <i class="fas fa-file-alt"></i>
                    </div>
                </div>
            </div>
            
            <div class="stats-card-modern">
                <div class="stats-header-modern">
                    <div>
                        <div class="stats-value-modern">{{ $stats['published_pages'] ?? 0 }}</div>
                        <div class="stats-label-modern">Pages publiées</div>
                    </div>
                    <div class="stats-icon-modern" style="background: linear-gradient(135deg, var(--accent-color), #06b48a);">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
            </div>
            
            <div class="stats-card-modern">
                <div class="stats-header-modern">
                    <div>
                        <div class="stats-value-modern">{{ $stats['draft_pages'] ?? 0 }}</div>
                        <div class="stats-label-modern">Brouillons</div>
                    </div>
                    <div class="stats-icon-modern" style="background: linear-gradient(135deg, #ffd166, #ffb347);">
                        <i class="fas fa-pen-fancy"></i>
                    </div>
                </div>
            </div>
            
            <div class="stats-card-modern">
                <div class="stats-header-modern">
                    <div>
                        <div class="stats-value-modern">{{ $stats['total_themes'] ?? 0 }}</div>
                        <div class="stats-label-modern">Thèmes disponibles</div>
                    </div>
                    <div class="stats-icon-modern" style="background: linear-gradient(135deg, #ef476f, #d4335f);">
                        <i class="fas fa-palette"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Main Content with Vertical Tabs -->
        <div class="main-card-modern mt-4">
            <div class="row g-0">
                <!-- Left Vertical Tabs - Version Redesign -->
<div class="col-md-3">
    <div class="vertical-tabs-wrapper-modern">
        <!-- Profile Header with Gradient -->
        <div class="profile-header-modern">
            <div class="profile-avatar-modern">
                <div class="avatar-gradient">
                    <i class="fas fa-building"></i>
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
        
        <!-- Navigation Tabs -->
        <div class="nav flex-column nav-pills vertical-tabs-modern" id="v-pills-tab" role="tablist" aria-orientation="vertical">
            <button class="nav-link-modern active" id="v-pills-dashboard-tab" data-bs-toggle="pill" data-bs-target="#v-pills-dashboard" type="button" role="tab" aria-selected="true">
                <div class="tab-icon-wrapper">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="tab-content-wrapper-modern">
                    <span class="tab-title-modern">Tableau de bord</span>
                    <span class="tab-description">Vue d'ensemble du site</span>
                </div>
                <div class="tab-badge"></div>
            </button>
            
            <button class="nav-link-modern" id="v-pills-pages-tab" data-bs-toggle="pill" data-bs-target="#v-pills-pages" type="button" role="tab" aria-selected="false">
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
            </button>
            
            <button class="nav-link-modern" id="v-pills-themes-tab" data-bs-toggle="pill" data-bs-target="#v-pills-themes" type="button" role="tab" aria-selected="false">
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
            </button>
            
            <button class="nav-link-modern" id="v-pills-config-tab" data-bs-toggle="pill" data-bs-target="#v-pills-config" type="button" role="tab" aria-selected="false">
                <div class="tab-icon-wrapper">
                    <i class="fas fa-sliders-h"></i>
                </div>
                <div class="tab-content-wrapper-modern">
                    <span class="tab-title-modern">Configuration</span>
                    <span class="tab-description">Paramètres généraux</span>
                </div>
                <div class="tab-badge"></div>
            </button>
            
            <button class="nav-link-modern" id="v-pills-social-tab" data-bs-toggle="pill" data-bs-target="#v-pills-social" type="button" role="tab" aria-selected="false">
                <div class="tab-icon-wrapper">
                    <i class="fas fa-share-alt"></i>
                </div>
                <div class="tab-content-wrapper-modern">
                    <span class="tab-title-modern">Réseaux sociaux</span>
                    <span class="tab-description">Liens et partages</span>
                </div>
                <div class="tab-badge"></div>
            </button>
            
            <button class="nav-link-modern" id="v-pills-seo-tab" data-bs-toggle="pill" data-bs-target="#v-pills-seo" type="button" role="tab" aria-selected="false">
                <div class="tab-icon-wrapper">
                    <i class="fas fa-search"></i>
                </div>
                <div class="tab-content-wrapper-modern">
                    <span class="tab-title-modern">SEO</span>
                    <span class="tab-description">Optimisation moteurs</span>
                </div>
                <div class="tab-badge"></div>
            </button>
            
            <button class="nav-link-modern" id="v-pills-media-tab" data-bs-toggle="pill" data-bs-target="#v-pills-media" type="button" role="tab" aria-selected="false">
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
            </button>
            
            <button class="nav-link-modern" id="v-pills-newsletter-tab" data-bs-toggle="pill" data-bs-target="#v-pills-newsletter" type="button" role="tab" aria-selected="false">
                <div class="tab-icon-wrapper">
                    <i class="fas fa-envelope-open-text"></i>
                </div>
                <div class="tab-content-wrapper-modern">
                    <span class="tab-title-modern">Newsletter</span>
                    <span class="tab-description">Campagnes emails</span>
                </div>
                <div class="tab-badge"></div>
            </button>
            
            <button class="nav-link-modern" id="v-pills-subscribers-tab" data-bs-toggle="pill" data-bs-target="#v-pills-subscribers" type="button" role="tab" aria-selected="false">
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
            </button>
            
            @php
    // Calculer le nombre de notifications (exemple)
    $notificationsCount = ($stats['comments_count'] ?? 0) + ($stats['pending_pages'] ?? 0);
@endphp

<button class="nav-link-modern" id="v-pills-comments-tab" data-bs-toggle="pill" data-bs-target="#v-pills-comments" type="button" role="tab">
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
</button>
            
            <div class="divider-modern"></div>
            
            <button class="nav-link-modern danger" id="v-pills-danger-tab" data-bs-toggle="pill" data-bs-target="#v-pills-danger" type="button" role="tab" aria-selected="false">
                <div class="tab-icon-wrapper">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="tab-content-wrapper-modern">
                    <span class="tab-title-modern">Zone de danger</span>
                    <span class="tab-description">Actions sensibles</span>
                </div>
                <div class="tab-badge"></div>
            </button>
        </div>
    </div>
</div>
                
                <!-- Right Tab Content -->
                <div class="col-md-9">
                    <div class="tab-content-wrapper">
                        <div class="tab-content" id="v-pills-tabContent">
                            
                            <!-- ==================== TABLEAU DE BORD ==================== -->
                            <div class="tab-pane fade show active" id="v-pills-dashboard" role="tabpanel" aria-labelledby="v-pills-dashboard-tab">
                                <div class="tab-content-header">
                                    <h3 class="tab-title">
                                        <i class="fas fa-tachometer-alt me-2" style="color: var(--primary-color);"></i>
                                        Tableau de bord
                                    </h3>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="info-card">
                                            <div class="info-card-header">
                                                <i class="fas fa-check-circle text-success"></i>
                                                <h5>Statut du site</h5>
                                            </div>
                                            <div class="info-card-body">
                                                <p>Thème actif: <strong>{{ $stats['active_theme']->name ?? 'Aucun thème actif' }}</strong></p>
                                                <p>Dernière mise à jour: <strong>{{ now()->format('d/m/Y H:i') }}</strong></p>
                                                <p>Pages publiées: <strong>{{ $stats['published_pages'] ?? 0 }}</strong></p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="info-card">
                                            <div class="info-card-header">
                                                <i class="fas fa-globe text-primary"></i>
                                                <h5>URL du site</h5>
                                            </div>
                                            <div class="info-card-body">
                                                <p><a href="{{ url('/') }}" target="_blank">{{ url('/') }}</a></p>
                                                <p class="text-muted small">Voir le site en direct</p>
                                                <hr>
                                                <p>Page d'accueil: <strong>{{ $stats['homepage'] ?? 'Non définie' }}</strong></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="recent-pages-section mt-4">
                                    <h5>Pages récentes</h5>
                                    <div class="table-container-modern">
                                        <table class="modern-table">
                                            <thead>
                                                <tr>
                                                    <th>Titre</th>
                                                    <th>Slug</th>
                                                    <th>Statut</th>
                                                    <th>Dernière modification</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($stats['recent_pages'] ?? [] as $page)
                                                <tr>
                                                    <td>
                                                        <i class="fas fa-file-alt me-2" style="color: var(--primary-color);"></i>
                                                        {{ $page->title }}
                                                    </td>
                                                    <td><code>{{ $page->slug }}</code></td>
                                                    <td>
                                                        @if($page->status === 'published')
                                                            <span class="badge bg-success">Publiée</span>
                                                        @else
                                                            <span class="badge bg-warning">Brouillon</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $page->updated_at->diffForHumans() }}</td>
                                                    <td>
                                                        <a href="{{ route('cms.admin.pages.edit', $page) }}" class="btn btn-sm btn-outline-primary">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <a href="{{ url('/page/' . $page->slug) }}" target="_blank" class="btn btn-sm btn-outline-secondary">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td colspan="5" class="text-center">Aucune page créée</td>
                                                </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- ==================== PAGES ==================== -->
                            <div class="tab-pane fade" id="v-pills-pages" role="tabpanel" aria-labelledby="v-pills-pages-tab">
                                <div class="tab-content-header">
                                    <h3 class="tab-title">
                                        <i class="fas fa-file-alt me-2" style="color: var(--accent-color);"></i>
                                        Gestion des pages
                                    </h3>
                                    <a href="{{ route('cms.admin.pages.create') }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-plus-circle me-1"></i>Nouvelle page
                                    </a>
                                </div>
                                
                                <div class="table-container-modern">
                                    <table class="modern-table">
                                        <thead>
                                            <tr>
                                                <th>Titre</th>
                                                <th>Slug</th>
                                                <th>Statut</th>
                                                <th>Visibilité</th>
                                                <th>Modifié le</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($stats['all_pages'] ?? [] as $page)
                                            <tr>
                                                <td>{{ $page->title }}</td>
                                                <td><code>{{ $page->slug }}</code></td>
                                                <td>
                                                    @if($page->status === 'published')
                                                        <span class="badge bg-success">Publiée</span>
                                                    @else
                                                        <span class="badge bg-warning">Brouillon</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($page->visibility === 'public')
                                                        <span class="badge bg-info">Public</span>
                                                    @elseif($page->visibility === 'private')
                                                        <span class="badge bg-secondary">Privé</span>
                                                    @else
                                                        <span class="badge bg-warning">Protégé</span>
                                                    @endif
                                                </td>
                                                <td>{{ $page->updated_at->format('d/m/Y H:i') }}</td>
                                                <td>
                                                    <a href="{{ route('cms.admin.pages.edit', $page) }}" class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <button class="btn btn-sm btn-outline-danger" onclick="deletePage({{ $page->id }})">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            
                            <!-- ==================== THÈMES ==================== -->
                            <div class="tab-pane fade" id="v-pills-themes" role="tabpanel" aria-labelledby="v-pills-themes-tab">
                                <div class="tab-content-header">
                                    <h3 class="tab-title">
                                        <i class="fas fa-palette me-2" style="color: #e6a100;"></i>
                                        Gestion des thèmes
                                    </h3>
                                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#uploadThemeModal">
                                        <i class="fas fa-upload me-1"></i>Uploader un thème
                                    </button>
                                </div>
                                
                                <div class="themes-grid">
                                    @forelse($stats['themes'] ?? [] as $theme)
                                        <div class="theme-card {{ $theme->is_active ? 'active' : '' }}">
                                            <div class="theme-preview">
                                                @if($theme->preview_image)
                                                    <img src="{{ $theme->preview_image }}" alt="{{ $theme->name }}">
                                                @else
                                                    <div class="theme-preview-placeholder">
                                                        <i class="fas fa-palette fa-3x"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="theme-info">
                                                <h5>{{ $theme->name }}</h5>
                                                <p class="text-muted small">Version {{ $theme->version }}</p>
                                                @if($theme->is_active)
                                                    <span class="badge bg-success">Actif</span>
                                                @else
                                                    <form action="{{ route('cms.admin.themes.activate', $theme) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-outline-primary">Activer</button>
                                                    </form>
                                                @endif
                                            </div>
                                        </div>
                                    @empty
                                        <div class="empty-state-small">
                                            <i class="fas fa-palette fa-4x mb-3" style="color: #ddd;"></i>
                                            <p>Aucun thème installé</p>
                                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadThemeModal">
                                                Uploader votre premier thème
                                            </button>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                            
                            <!-- ==================== CONFIGURATION ==================== -->
                            <div class="tab-pane fade" id="v-pills-config" role="tabpanel" aria-labelledby="v-pills-config-tab">
                                <div class="tab-content-header">
                                    <h3 class="tab-title">
                                        <i class="fas fa-cog me-2" style="color: #6c757d;"></i>
                                        Configuration générale
                                    </h3>
                                </div>
                                
                                <form action="{{ route('cms.admin.settings.update') }}" method="POST">
                                    @csrf
                                    
                                    <div class="config-sections">
                                        <div class="config-group">
                                            <h4>Informations générales</h4>
                                            <div class="config-item">
                                                <label class="config-label">Nom du site</label>
                                                <input type="text" class="form-control" name="site_name" value="{{ $stats['etablissement']->getSetting('site_name', $stats['etablissement']->name ?? 'Mon site') }}">
                                            </div>
                                            <div class="config-item mt-3">
                                                <label class="config-label">Slogan</label>
                                                <input type="text" class="form-control" name="site_slogan" value="{{ $stats['etablissement']->getSetting('site_slogan', '') }}">
                                            </div>
                                            <div class="config-item mt-3">
                                                <label class="config-label">Description</label>
                                                <textarea class="form-control" name="site_description" rows="3">{{ $stats['etablissement']->getSetting('site_description', '') }}</textarea>
                                            </div>
                                        </div>
                                        
                                        <div class="config-group mt-4">
                                            <h4>Email et notifications</h4>
                                            <div class="config-item">
                                                <label class="config-label">Email de contact</label>
                                                <input type="email" class="form-control" name="contact_email" value="{{ $stats['etablissement']->getSetting('contact_email', $stats['etablissement']->email_contact ?? '') }}">
                                            </div>
                                            <div class="config-item mt-3">
                                                <label class="config-label">Email de notification</label>
                                                <input type="email" class="form-control" name="notification_email" value="{{ $stats['etablissement']->getSetting('notification_email', '') }}">
                                            </div>
                                        </div>
                                        
                                        <div class="config-group mt-4">
                                            <h4>Localisation</h4>
                                            <div class="config-item">
                                                <label class="config-label">Adresse</label>
                                                <input type="text" class="form-control" name="address" value="{{ $stats['etablissement']->getSetting('address', $stats['etablissement']->adresse ?? '') }}">
                                            </div>
                                            <div class="config-item mt-3">
                                                <label class="config-label">Code postal</label>
                                                <input type="text" class="form-control" name="zip_code" value="{{ $stats['etablissement']->getSetting('zip_code', $stats['etablissement']->zip_code ?? '') }}">
                                            </div>
                                            <div class="config-item mt-3">
                                                <label class="config-label">Ville</label>
                                                <input type="text" class="form-control" name="city" value="{{ $stats['etablissement']->getSetting('city', $stats['etablissement']->ville ?? '') }}">
                                            </div>
                                            <div class="config-item mt-3">
                                                <label class="config-label">Téléphone</label>
                                                <input type="text" class="form-control" name="phone" value="{{ $stats['etablissement']->getSetting('phone', $stats['etablissement']->phone ?? '') }}">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="form-actions mt-4">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save me-2"></i>Sauvegarder la configuration
                                        </button>
                                    </div>
                                </form>
                            </div>
                            
                            <!-- ==================== RÉSEAUX SOCIAUX ==================== -->
                            <div class="tab-pane fade" id="v-pills-social" role="tabpanel" aria-labelledby="v-pills-social-tab">
                                <div class="tab-content-header">
                                    <h3 class="tab-title">
                                        <i class="fas fa-share-alt me-2" style="color: #45b7d1;"></i>
                                        Réseaux sociaux
                                    </h3>
                                </div>
                                
                                <form action="{{ route('cms.admin.settings.update') }}" method="POST">
                                    @csrf
                                    
                                    <div class="social-sections">
                                        <div class="social-group">
                                            <div class="social-icon-item">
                                                <i class="fab fa-facebook-f" style="color: #1877f2; font-size: 1.5rem;"></i>
                                                <div class="flex-grow-1 ms-3">
                                                    <label class="config-label">Facebook</label>
                                                    <input type="url" class="form-control" name="facebook_url" value="{{ $stats['etablissement']->getSetting('facebook_url', '') }}" placeholder="https://facebook.com/...">
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="social-group mt-3">
                                            <div class="social-icon-item">
                                                <i class="fab fa-twitter" style="color: #1da1f2; font-size: 1.5rem;"></i>
                                                <div class="flex-grow-1 ms-3">
                                                    <label class="config-label">Twitter / X</label>
                                                    <input type="url" class="form-control" name="twitter_url" value="{{ $stats['etablissement']->getSetting('twitter_url', '') }}" placeholder="https://twitter.com/...">
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="social-group mt-3">
                                            <div class="social-icon-item">
                                                <i class="fab fa-instagram" style="color: #e4405f; font-size: 1.5rem;"></i>
                                                <div class="flex-grow-1 ms-3">
                                                    <label class="config-label">Instagram</label>
                                                    <input type="url" class="form-control" name="instagram_url" value="{{ $stats['etablissement']->getSetting('instagram_url', '') }}" placeholder="https://instagram.com/...">
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="social-group mt-3">
                                            <div class="social-icon-item">
                                                <i class="fab fa-linkedin-in" style="color: #0077b5; font-size: 1.5rem;"></i>
                                                <div class="flex-grow-1 ms-3">
                                                    <label class="config-label">LinkedIn</label>
                                                    <input type="url" class="form-control" name="linkedin_url" value="{{ $stats['etablissement']->getSetting('linkedin_url', '') }}" placeholder="https://linkedin.com/...">
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="social-group mt-3">
                                            <div class="social-icon-item">
                                                <i class="fab fa-youtube" style="color: #ff0000; font-size: 1.5rem;"></i>
                                                <div class="flex-grow-1 ms-3">
                                                    <label class="config-label">YouTube</label>
                                                    <input type="url" class="form-control" name="youtube_url" value="{{ $stats['etablissement']->getSetting('youtube_url', '') }}" placeholder="https://youtube.com/...">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="form-actions mt-4">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save me-2"></i>Sauvegarder
                                        </button>
                                    </div>
                                </form>
                            </div>
                            
                            <!-- ==================== SEO ==================== -->
                            <div class="tab-pane fade" id="v-pills-seo" role="tabpanel" aria-labelledby="v-pills-seo-tab">
                                <div class="tab-content-header">
                                    <h3 class="tab-title">
                                        <i class="fas fa-search me-2" style="color: #06d6a0;"></i>
                                        Optimisation SEO
                                    </h3>
                                </div>
                                
                                <form action="{{ route('cms.admin.settings.update') }}" method="POST">
                                    @csrf
                                    
                                    <div class="seo-sections">
                                        <div class="config-group">
                                            <h4>Métadonnées globales</h4>
                                            <div class="config-item">
                                                <label class="config-label">Titre par défaut</label>
                                                <input type="text" class="form-control" name="seo_title" value="{{ $stats['etablissement']->getSetting('seo_title', '') }}">
                                                <small class="text-muted">Titre affiché dans les résultats de recherche</small>
                                            </div>
                                            <div class="config-item mt-3">
                                                <label class="config-label">Description par défaut</label>
                                                <textarea class="form-control" name="seo_description" rows="3">{{ $stats['etablissement']->getSetting('seo_description', '') }}</textarea>
                                                <small class="text-muted">Description affichée dans les résultats de recherche (150-160 caractères)</small>
                                            </div>
                                            <div class="config-item mt-3">
                                                <label class="config-label">Mots-clés</label>
                                                <input type="text" class="form-control" name="seo_keywords" value="{{ $stats['etablissement']->getSetting('seo_keywords', '') }}">
                                                <small class="text-muted">Séparés par des virgules</small>
                                            </div>
                                        </div>
                                        
                                        <div class="config-group mt-4">
                                            <h4>Google Analytics</h4>
                                            <div class="config-item">
                                                <label class="config-label">ID de suivi (GA4)</label>
                                                <input type="text" class="form-control" name="google_analytics_id" value="{{ $stats['etablissement']->getSetting('google_analytics_id', '') }}" placeholder="G-XXXXXXXX">
                                            </div>
                                        </div>
                                        
                                        <div class="config-group mt-4">
                                            <h4>Google Search Console</h4>
                                            <div class="config-item">
                                                <label class="config-label">Code de vérification</label>
                                                <input type="text" class="form-control" name="google_verification" value="{{ $stats['etablissement']->getSetting('google_verification', '') }}">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="form-actions mt-4">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save me-2"></i>Sauvegarder
                                        </button>
                                    </div>
                                </form>
                            </div>
                            
                            <!-- ==================== NEWSLETTER ==================== -->
                            <div class="tab-pane fade" id="v-pills-newsletter" role="tabpanel" aria-labelledby="v-pills-newsletter-tab">
                                <div class="tab-content-header">
                                    <h3 class="tab-title">
                                        <i class="fas fa-envelope-open-text me-2" style="color: #9b59b6;"></i>
                                        Newsletter
                                    </h3>
                                    <button class="btn btn-primary btn-sm">
                                        <i class="fas fa-paper-plane me-1"></i>Nouvelle campagne
                                    </button>
                                </div>
                                
                                <div class="stats-grid-mini">
                                    <div class="stat-mini-card">
                                        <div class="stat-mini-value">{{ $stats['subscribers_count'] ?? 0 }}</div>
                                        <div class="stat-mini-label">Abonnés</div>
                                    </div>
                                    <div class="stat-mini-card">
                                        <div class="stat-mini-value">{{ $stats['open_rate'] ?? 0 }}%</div>
                                        <div class="stat-mini-label">Taux d'ouverture</div>
                                    </div>
                                </div>
                                
                                <div class="mt-4">
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle me-2"></i>
                                        La gestion complète de la newsletter sera disponible prochainement.
                                    </div>
                                </div>
                            </div>
                            
                            <!-- ==================== ABONNÉS ==================== -->
                            <div class="tab-pane fade" id="v-pills-subscribers" role="tabpanel" aria-labelledby="v-pills-subscribers-tab">
                                <div class="tab-content-header">
                                    <h3 class="tab-title">
                                        <i class="fas fa-users me-2" style="color: #06d6a0;"></i>
                                        Abonnés
                                    </h3>
                                    <button class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-download me-1"></i>Exporter
                                    </button>
                                </div>
                                
                                <div class="table-container-modern">
                                    <table class="modern-table">
                                        <thead>
                                            <tr>
                                                <th>Email</th>
                                                <th>Date d'abonnement</th>
                                                <th>Statut</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td colspan="4" class="text-center">Aucun abonné pour le moment</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            
                            <!-- ==================== COMMENTAIRES ==================== -->
                            <div class="tab-pane fade" id="v-pills-comments" role="tabpanel" aria-labelledby="v-pills-comments-tab">
                                <div class="tab-content-header">
                                    <h3 class="tab-title">
                                        <i class="fas fa-comments me-2" style="color: #ffd166;"></i>
                                        Commentaires
                                    </h3>
                                </div>
                                
                                <div class="comments-list">
                                    <div class="empty-state-small">
                                        <i class="fas fa-comments fa-4x mb-3" style="color: #ddd;"></i>
                                        <p>Aucun commentaire pour le moment</p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- ==================== MÉDIATHÈQUE ==================== -->
                            <div class="tab-pane fade" id="v-pills-media" role="tabpanel" aria-labelledby="v-pills-media-tab">
                                <div class="tab-content-header">
                                    <h3 class="tab-title">
                                        <i class="fas fa-images me-2" style="color: #45b7d1;"></i>
                                        Médiathèque
                                    </h3>
                                    <button class="btn btn-primary btn-sm">
                                        <i class="fas fa-upload me-1"></i>Uploader
                                    </button>
                                </div>
                                
                                <div class="gallery-grid">
                                    <div class="gallery-item">
                                        <div class="gallery-placeholder">
                                            <i class="fas fa-image fa-3x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- ==================== ZONE DE DANGER ==================== -->
                            <div class="tab-pane fade" id="v-pills-danger" role="tabpanel" aria-labelledby="v-pills-danger-tab">
                                <div class="tab-content-header">
                                    <h3 class="tab-title text-danger">
                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                        Zone de danger
                                    </h3>
                                </div>
                                
                                <div class="danger-zone">
                                    <div class="danger-action">
                                        <div>
                                            <h5>Vider le cache du site</h5>
                                            <p class="text-muted">Supprime toutes les pages en cache pour forcer la régénération.</p>
                                        </div>
                                        <button class="btn btn-warning" onclick="clearCache()">
                                            <i class="fas fa-trash-alt me-2"></i>Vider le cache
                                        </button>
                                    </div>
                                    
                                    <div class="danger-action">
                                        <div>
                                            <h5>Réinitialiser la configuration</h5>
                                            <p class="text-muted">Remet tous les paramètres de configuration à leurs valeurs par défaut.</p>
                                        </div>
                                        <button class="btn btn-warning" onclick="resetConfig()">
                                            <i class="fas fa-undo-alt me-2"></i>Réinitialiser
                                        </button>
                                    </div>
                                    
                                    <div class="danger-action">
                                        <div>
                                            <h5>Supprimer toutes les pages</h5>
                                            <p class="text-muted">Cette action est irréversible. Toutes les pages seront définitivement supprimées.</p>
                                        </div>
                                        <button class="btn btn-danger" onclick="deleteAllPages()">
                                            <i class="fas fa-trash me-2"></i>Supprimer toutes les pages
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    
    <!-- Upload Theme Modal -->
    <div class="modal fade" id="uploadThemeModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ url('cms.admin.themes.upload') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Uploader un thème</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="theme_name" class="form-label">Nom du thème</label>
                            <input type="text" class="form-control" name="name" id="theme_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="theme_file" class="form-label">Fichier ZIP du thème</label>
                            <input type="file" class="form-control" name="theme_file" id="theme_file" accept=".zip" required>
                            <small class="text-muted">Le ZIP doit contenir layout.blade.php et un dossier assets/</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">Uploader</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- JavaScript -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        function deletePage(pageId) {
            if (confirm('Êtes-vous sûr de vouloir supprimer cette page ?')) {
                fetch(`/admin/cms/pages/${pageId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                }).then(() => {
                    window.location.reload();
                });
            }
        }
        
        function clearCache() {
            if (confirm('Vider le cache du site ?')) {
                fetch('/admin/cms/cache/clear', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                }).then(() => {
                    alert('Cache vidé avec succès');
                });
            }
        }
        
        function resetConfig() {
            if (confirm('⚠️ ATTENTION: Cette action réinitialisera toute votre configuration. Continuer ?')) {
                fetch('/admin/cms/config/reset', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                }).then(() => {
                    window.location.reload();
                });
            }
        }
        
        function deleteAllPages() {
            if (confirm('⚠️ ATTENTION: Cette action supprimera TOUTES les pages définitivement. Êtes-vous absolument sûr ?')) {
                fetch('/admin/cms/pages/delete-all', {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                }).then(() => {
                    window.location.reload();
                });
            }
        }
        
        // Animation for tabs
        document.addEventListener('DOMContentLoaded', function() {
            const tabs = document.querySelectorAll('.vertical-tabs .nav-link');
            
            tabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    const tabContent = document.querySelector(this.getAttribute('data-bs-target'));
                    if (tabContent) {
                        tabContent.style.opacity = '0';
                        setTimeout(() => {
                            tabContent.style.opacity = '1';
                        }, 50);
                    }
                });
            });
        });
    </script>
    <script>
document.addEventListener('DOMContentLoaded', function() {
    // Search functionality for tabs
    const searchInput = document.getElementById('tabSearchInput');
    const tabButtons = document.querySelectorAll('.nav-link-modern');
    
    if (searchInput) {
        searchInput.addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase().trim();
            
            tabButtons.forEach(button => {
                const title = button.querySelector('.tab-title-modern')?.textContent.toLowerCase() || '';
                const description = button.querySelector('.tab-description')?.textContent.toLowerCase() || '';
                const matches = searchTerm === '' || title.includes(searchTerm) || description.includes(searchTerm);
                
                if (matches) {
                    button.style.display = 'flex';
                    button.classList.add('tab-highlight');
                    setTimeout(() => {
                        button.classList.remove('tab-highlight');
                    }, 500);
                } else {
                    button.style.display = 'none';
                }
            });
        });
    }
    
    // Smooth active tab animation
    const tabs = document.querySelectorAll('.nav-link-modern');
    tabs.forEach(tab => {
        tab.addEventListener('click', function() {
            // Remove active class from all tabs
            tabs.forEach(t => t.classList.remove('active'));
            // Add active class to clicked tab
            this.classList.add('active');
            
            // Animation effect for tab content
            const targetId = this.getAttribute('data-bs-target');
            const targetPane = document.querySelector(targetId);
            if (targetPane) {
                targetPane.style.opacity = '0';
                targetPane.style.transform = 'translateY(10px)';
                setTimeout(() => {
                    targetPane.style.transition = 'all 0.3s ease';
                    targetPane.style.opacity = '1';
                    targetPane.style.transform = 'translateY(0)';
                }, 50);
            }
        });
    });
    
    // Tooltip on hover for description
    if (window.innerWidth <= 768) {
        tabButtons.forEach(button => {
            const description = button.querySelector('.tab-description')?.textContent;
            if (description) {
                button.setAttribute('title', description);
            }
        });
    }
});
</script>
    <style>
        /* Animated badge */
.badge-count {
    position: relative;
}

.badge-count.has-notification::after {
    content: '';
    position: absolute;
    top: -2px;
    right: -2px;
    width: 8px;
    height: 8px;
    background: #ef4444;
    border-radius: 50%;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% {
        transform: scale(1);
        opacity: 1;
    }
    50% {
        transform: scale(1.2);
        opacity: 0.7;
    }
}
/* ============================================
   VERTICAL TABS MODERN REDESIGN
   ============================================ */

.vertical-tabs-wrapper-modern {
    background: linear-gradient(135deg, #ffffff 0%, #fafbfd 100%);
    border-radius: 20px;
    padding: 24px 16px;
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.05);
    height: 100%;
    transition: all 0.3s ease;
}

/* Profile Header */
.profile-header-modern {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 0 12px 24px 12px;
    margin-bottom: 16px;
    border-bottom: 1px solid rgba(0, 0, 0, 0.08);
}

.profile-avatar-modern {
    position: relative;
}

.avatar-gradient {
    width: 64px;
    height: 64px;
    background: linear-gradient(135deg, #4361ee, #3a56e4);
    border-radius: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.8rem;
    box-shadow: 0 6px 14px rgba(67, 97, 238, 0.25);
    transition: transform 0.3s ease;
}

.avatar-gradient:hover {
    transform: scale(1.05);
}

.avatar-status {
    position: absolute;
    bottom: 2px;
    right: 2px;
    width: 14px;
    height: 14px;
    border-radius: 50%;
    border: 2px solid white;
}

.avatar-status.active {
    background: #10b981;
    box-shadow: 0 0 0 2px rgba(16, 185, 129, 0.2);
}

.avatar-status.inactive {
    background: #ef4444;
}

.profile-info-modern {
    flex: 1;
}

.profile-name-modern {
    font-size: 1.1rem;
    font-weight: 700;
    color: #1e293b;
    margin: 0 0 4px 0;
    line-height: 1.3;
}

.profile-type-modern {
    font-size: 0.75rem;
    color: #64748b;
    margin: 0 0 8px 0;
}

.badge-active, .badge-inactive {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    font-size: 0.7rem;
    padding: 4px 8px;
    border-radius: 20px;
    font-weight: 500;
}

.badge-active {
    background: rgba(16, 185, 129, 0.12);
    color: #10b981;
}

.badge-inactive {
    background: rgba(239, 68, 68, 0.12);
    color: #ef4444;
}

.badge-active i, .badge-inactive i {
    font-size: 0.65rem;
}

/* Search Bar */
.tabs-search-modern {
    padding: 0 12px;
    margin-bottom: 20px;
}

.search-input-wrapper {
    position: relative;
}

.search-icon {
    position: absolute;
    left: 14px;
    top: 50%;
    transform: translateY(-50%);
    color: #94a3b8;
    font-size: 0.9rem;
    pointer-events: none;
}

.search-input {
    width: 100%;
    padding: 10px 12px 10px 38px;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    font-size: 0.85rem;
    background: white;
    transition: all 0.3s ease;
}

.search-input:focus {
    outline: none;
    border-color: #4361ee;
    box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
}

.search-input::placeholder {
    color: #94a3b8;
}

/* Navigation Tabs */
.vertical-tabs-modern {
    display: flex;
    flex-direction: column;
    gap: 6px;
    max-height: calc(100vh - 280px);
    overflow-y: auto;
    padding-right: 4px;
}

.vertical-tabs-modern::-webkit-scrollbar {
    width: 4px;
}

.vertical-tabs-modern::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

.vertical-tabs-modern::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 10px;
}

.vertical-tabs-modern::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}

.nav-link-modern {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 16px;
    border-radius: 14px;
    background: transparent;
    border: none;
    cursor: pointer;
    transition: all 0.25s ease;
    text-align: left;
    width: 100%;
    position: relative;
}

.nav-link-modern:hover {
    background: rgba(67, 97, 238, 0.06);
    transform: translateX(4px);
}

.nav-link-modern.active {
    background: linear-gradient(135deg, #4361ee, #3a56e4);
    box-shadow: 0 4px 12px rgba(67, 97, 238, 0.25);
}

.nav-link-modern.active .tab-icon-wrapper i {
    color: white;
}

.nav-link-modern.active .tab-title-modern {
    color: white;
}

.nav-link-modern.active .tab-description {
    color: rgba(255, 255, 255, 0.8);
}

.nav-link-modern.active .badge-count {
    background: rgba(255, 255, 255, 0.2);
    color: white;
}

.nav-link-modern.danger:hover {
    background: rgba(239, 68, 68, 0.08);
}

.nav-link-modern.danger.active {
    background: linear-gradient(135deg, #ef4444, #dc2626);
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.25);
}

.tab-icon-wrapper {
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 12px;
    background: rgba(67, 97, 238, 0.1);
    transition: all 0.25s ease;
}

.nav-link-modern.active .tab-icon-wrapper {
    background: rgba(255, 255, 255, 0.15);
}

.tab-icon-wrapper i {
    font-size: 1.1rem;
    color: #4361ee;
}

.nav-link-modern.active .tab-icon-wrapper i {
    color: white;
}

.nav-link-modern.danger .tab-icon-wrapper {
    background: rgba(239, 68, 68, 0.1);
}

.nav-link-modern.danger .tab-icon-wrapper i {
    color: #ef4444;
}

.nav-link-modern.danger.active .tab-icon-wrapper {
    background: rgba(255, 255, 255, 0.15);
}

.nav-link-modern.danger.active .tab-icon-wrapper i {
    color: white;
}

.tab-content-wrapper-modern {
    flex: 1;
}

.tab-title-modern {
    display: block;
    font-size: 0.9rem;
    font-weight: 600;
    color: #1e293b;
    margin-bottom: 2px;
    transition: color 0.25s ease;
}

.tab-description {
    display: block;
    font-size: 0.7rem;
    color: #64748b;
    transition: color 0.25s ease;
}

.nav-link-modern.active .tab-description {
    color: rgba(255, 255, 255, 0.8);
}

.tab-badge {
    min-width: 28px;
}

.badge-count {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: #f1f5f9;
    color: #475569;
    font-size: 0.7rem;
    font-weight: 600;
    padding: 2px 8px;
    border-radius: 20px;
    min-width: 28px;
    transition: all 0.25s ease;
}

.nav-link-modern.active .badge-count {
    background: rgba(255, 255, 255, 0.2);
    color: white;
}

/* Divider */
.divider-modern {
    height: 1px;
    background: linear-gradient(90deg, transparent, #e2e8f0, transparent);
    margin: 12px 0;
}

/* Animation for active tab indicator */
.nav-link-modern {
    position: relative;
    overflow: hidden;
}

.nav-link-modern::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    height: 100%;
    width: 3px;
    background: linear-gradient(135deg, #4361ee, #3a56e4);
    border-radius: 0 4px 4px 0;
    transform: scaleY(0);
    transition: transform 0.25s ease;
}

.nav-link-modern.active::before {
    transform: scaleY(1);
}

.nav-link-modern.danger::before {
    background: linear-gradient(135deg, #ef4444, #dc2626);
}

/* Animation for search highlight */
.tab-highlight {
    animation: highlightPulse 0.5s ease;
}

@keyframes highlightPulse {
    0%, 100% {
        background: transparent;
    }
    50% {
        background: rgba(67, 97, 238, 0.1);
    }
}

/* Responsive */
@media (max-width: 768px) {
    .vertical-tabs-wrapper-modern {
        border-radius: 20px;
        margin-bottom: 20px;
    }
    
    .profile-header-modern {
        padding: 0 8px 20px 8px;
    }
    
    .avatar-gradient {
        width: 52px;
        height: 52px;
        font-size: 1.4rem;
    }
    
    .tab-description {
        display: none;
    }
    
    .tab-icon-wrapper {
        width: 32px;
        height: 32px;
    }
    
    .tab-title-modern {
        font-size: 0.85rem;
    }
    
    .nav-link-modern {
        padding: 10px 12px;
    }
}
        /* Styles complémentaires pour le CMS */
        .info-card {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            transition: all 0.3s ease;
        }
        
        .info-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 12px rgba(0,0,0,0.1);
        }
        
        .info-card-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #e9ecef;
        }
        
        .info-card-header i {
            font-size: 1.5rem;
        }
        
        .info-card-header h5 {
            margin: 0;
            font-weight: 600;
        }
        
        .themes-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
        }
        
        .theme-card {
            background: #f8f9fa;
            border-radius: 12px;
            overflow: hidden;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }
        
        .theme-card.active {
            border-color: var(--primary-color);
            box-shadow: 0 4px 12px rgba(67, 97, 238, 0.2);
        }
        
        .theme-preview {
            background: #e9ecef;
            height: 150px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .theme-preview-placeholder {
            text-align: center;
            color: #adb5bd;
        }
        
        .theme-info {
            padding: 15px;
            text-align: center;
        }
        
        .theme-info h5 {
            margin-bottom: 5px;
            font-weight: 600;
        }
        
        .config-sections {
            max-height: 500px;
            overflow-y: auto;
        }
        
        .config-group {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
        }
        
        .config-group h4 {
            margin-bottom: 20px;
            font-size: 1.1rem;
            font-weight: 600;
            color: #333;
        }
        
        .config-item {
            margin-bottom: 15px;
        }
        
        .config-item:last-child {
            margin-bottom: 0;
        }
        
        .config-label {
            font-weight: 500;
            margin-bottom: 8px;
            display: block;
            color: #495057;
        }
        
        .social-icon-item {
            display: flex;
            align-items: center;
            background: #f8f9fa;
            padding: 15px;
            border-radius: 10px;
            transition: all 0.3s ease;
        }
        
        .social-icon-item:hover {
            background: #e9ecef;
        }
        
        .stats-grid-mini {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }
        
        .stat-mini-card {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 20px;
            text-align: center;
        }
        
        .stat-mini-value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary-color);
        }
        
        .stat-mini-label {
            color: #6c757d;
            margin-top: 5px;
        }
        
        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 15px;
        }
        
        .gallery-item {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            position: relative;
        }
        
        .gallery-placeholder {
            color: #adb5bd;
        }
        
        .table-container-modern {
            overflow-x: auto;
        }
        
        .modern-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .modern-table th,
        .modern-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e9ecef;
        }
        
        .modern-table th {
            background: #f8f9fa;
            font-weight: 600;
            color: #495057;
        }
        
        .modern-table tr:hover {
            background: #f8f9fa;
        }
        
        .empty-state-small {
            text-align: center;
            padding: 60px 20px;
            background: #f8f9fa;
            border-radius: 12px;
        }
        
        @media (max-width: 768px) {
            .vertical-tabs-wrapper {
                border-right: none;
                border-radius: 12px 12px 0 0;
            }
            
            .tab-content-wrapper {
                border-radius: 0 0 12px 12px;
            }
            
            .themes-grid {
                grid-template-columns: 1fr;
            }
            
            .stats-grid-mini {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endsection