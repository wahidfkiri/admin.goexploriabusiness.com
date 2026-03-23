@extends('layouts.app')

@section('content')
    <!-- MAIN CONTENT -->
    <main class="dashboard-content">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">
                <span class="page-title-icon"><i class="fas fa-cms"></i></span>
                Tableau de bord CMS
            </h1>
            
            <div class="page-actions">
                <a href="{{ route('cms.admin.pages.index') }}" class="btn btn-primary">
                    <i class="fas fa-plus-circle me-2"></i>Nouvelle page
                </a>
                <a href="{{ route('cms.admin.themes.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-palette me-2"></i>Gérer les thèmes
                </a>
            </div>
        </div>
        
        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stats-card-modern">
                <div class="stats-header-modern">
                    <div>
                        <div class="stats-value-modern">{{ $stats['total_pages'] }}</div>
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
                        <div class="stats-value-modern">{{ $stats['published_pages'] }}</div>
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
                        <div class="stats-value-modern">{{ $stats['draft_pages'] }}</div>
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
                        <div class="stats-value-modern">{{ $stats['total_themes'] }}</div>
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
                <!-- Left Vertical Tabs -->
                <div class="col-md-3">
                    <div class="vertical-tabs-wrapper">
                        <div class="etablissement-profile-summary">
                            <div class="profile-icon-large">
                                <i class="fas fa-building"></i>
                            </div>
                            <h4 class="profile-name">{{ $stats['etablissement']->name }}</h4>
                            <p class="profile-type">{{ $stats['etablissement']->lname ?? 'Établissement' }}</p>
                            <div class="profile-status">
                                @if($stats['etablissement']->is_active)
                                    <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i>Actif</span>
                                @else
                                    <span class="badge bg-secondary"><i class="fas fa-times-circle me-1"></i>Inactif</span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="nav flex-column nav-pills vertical-tabs" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                            <button class="nav-link active" id="v-pills-dashboard-tab" data-bs-toggle="pill" data-bs-target="#v-pills-dashboard" type="button" role="tab" aria-controls="v-pills-dashboard" aria-selected="true">
                                <i class="fas fa-tachometer-alt me-2"></i>
                                Aperçu
                            </button>
                            
                            <button class="nav-link" id="v-pills-pages-tab" data-bs-toggle="pill" data-bs-target="#v-pills-pages" type="button" role="tab" aria-controls="v-pills-pages" aria-selected="false">
                                <i class="fas fa-file-alt me-2"></i>
                                Pages
                                <span class="badge bg-primary float-end">{{ $stats['total_pages'] }}</span>
                            </button>
                            
                            <button class="nav-link" id="v-pills-themes-tab" data-bs-toggle="pill" data-bs-target="#v-pills-themes" type="button" role="tab" aria-controls="v-pills-themes" aria-selected="false">
                                <i class="fas fa-palette me-2"></i>
                                Thèmes
                                <span class="badge bg-primary float-end">{{ $stats['total_themes'] }}</span>
                            </button>
                            
                            <button class="nav-link" id="v-pills-config-tab" data-bs-toggle="pill" data-bs-target="#v-pills-config" type="button" role="tab" aria-controls="v-pills-config" aria-selected="false">
                                <i class="fas fa-cog me-2"></i>
                                Configuration
                            </button>
                            
                            <button class="nav-link" id="v-pills-newsletter-tab" data-bs-toggle="pill" data-bs-target="#v-pills-newsletter" type="button" role="tab" aria-controls="v-pills-newsletter" aria-selected="false">
                                <i class="fas fa-envelope-open-text me-2"></i>
                                Newsletter
                            </button>
                            
                            <button class="nav-link" id="v-pills-subscribers-tab" data-bs-toggle="pill" data-bs-target="#v-pills-subscribers" type="button" role="tab" aria-controls="v-pills-subscribers" aria-selected="false">
                                <i class="fas fa-users me-2"></i>
                                Abonnés
                            </button>
                            
                            <button class="nav-link" id="v-pills-comments-tab" data-bs-toggle="pill" data-bs-target="#v-pills-comments" type="button" role="tab" aria-controls="v-pills-comments" aria-selected="false">
                                <i class="fas fa-comments me-2"></i>
                                Commentaires
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Right Tab Content -->
                <div class="col-md-9">
                    <div class="tab-content-wrapper">
                        <div class="tab-content" id="v-pills-tabContent">
                            <!-- Dashboard / Aperçu -->
                            <div class="tab-pane fade show active" id="v-pills-dashboard" role="tabpanel" aria-labelledby="v-pills-dashboard-tab">
                                <div class="tab-content-header">
                                    <h3 class="tab-title">
                                        <i class="fas fa-tachometer-alt me-2" style="color: var(--primary-color);"></i>
                                        Aperçu du site
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
                                                    <th>Statut</th>
                                                    <th>Dernière modification</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($stats['recent_pages'] as $page)
                                                <tr>
                                                    <td>
                                                        <i class="fas fa-file-alt me-2" style="color: var(--primary-color);"></i>
                                                        {{ $page->title }}
                                                    </td>
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
                                                    </td>
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td colspan="4" class="text-center">Aucune page créée</td>
                                                </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Pages -->
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
                                                <th>Modifié le</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($stats['recent_pages'] as $page)
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
                                                <td>{{ $page->updated_at->format('d/m/Y') }}</td>
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
                            
                            <!-- Thèmes -->
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
                                    @forelse($stats['total_themes'] > 0 ? \Vendor\Cms\Models\Theme::where('etablissement_id', $stats['etablissement']->id)->get() : [] as $theme)
                                        <div class="theme-card {{ $theme->is_active ? 'active' : '' }}">
                                            <div class="theme-preview">
                                                <div class="theme-preview-placeholder">
                                                    <i class="fas fa-palette fa-3x"></i>
                                                </div>
                                            </div>
                                            <div class="theme-info">
                                                <h5>{{ $theme->name }}</h5>
                                                <p>Version {{ $theme->version }}</p>
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
                                        <div class="empty-state">
                                            <i class="fas fa-palette fa-4x mb-3"></i>
                                            <p>Aucun thème installé</p>
                                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadThemeModal">
                                                Uploader votre premier thème
                                            </button>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                            
                            <!-- Configuration -->
                            <div class="tab-pane fade" id="v-pills-config" role="tabpanel" aria-labelledby="v-pills-config-tab">
                                <div class="tab-content-header">
                                    <h3 class="tab-title">
                                        <i class="fas fa-cog me-2" style="color: #6c757d;"></i>
                                        Configuration du site
                                    </h3>
                                </div>
                                
                                <form action="{{ route('cms.admin.settings.update') }}" method="POST">
                                    @csrf
                                    
                                    <div class="config-sections">
                                        <div class="config-group">
                                            <h4>Adresses externes</h4>
                                            <div class="config-item">
                                                <label class="config-label">Facebook</label>
                                                <input type="url" class="form-control" name="facebook_url" value="{{ $stats['etablissement']->getSetting('facebook_url', '') }}" placeholder="https://facebook.com/...">
                                            </div>
                                            <div class="config-item mt-3">
                                                <label class="config-label">Twitter / X</label>
                                                <input type="url" class="form-control" name="twitter_url" value="{{ $stats['etablissement']->getSetting('twitter_url', '') }}" placeholder="https://twitter.com/...">
                                            </div>
                                            <div class="config-item mt-3">
                                                <label class="config-label">Instagram</label>
                                                <input type="url" class="form-control" name="instagram_url" value="{{ $stats['etablissement']->getSetting('instagram_url', '') }}" placeholder="https://instagram.com/...">
                                            </div>
                                            <div class="config-item mt-3">
                                                <label class="config-label">LinkedIn</label>
                                                <input type="url" class="form-control" name="linkedin_url" value="{{ $stats['etablissement']->getSetting('linkedin_url', '') }}" placeholder="https://linkedin.com/...">
                                            </div>
                                        </div>
                                        
                                        <div class="config-group mt-4">
                                            <h4>Paramètres front</h4>
                                            <div class="config-item">
                                                <label class="config-label">Titre du site</label>
                                                <input type="text" class="form-control" name="site_title" value="{{ $stats['etablissement']->getSetting('site_title', $stats['etablissement']->name) }}">
                                            </div>
                                            <div class="config-item mt-3">
                                                <label class="config-label">Description du site</label>
                                                <textarea class="form-control" name="site_description" rows="3">{{ $stats['etablissement']->getSetting('site_description', '') }}</textarea>
                                            </div>
                                            <div class="config-item mt-3">
                                                <label class="config-label">Email de contact</label>
                                                <input type="email" class="form-control" name="contact_email" value="{{ $stats['etablissement']->getSetting('contact_email', $stats['etablissement']->email_contact) }}">
                                            </div>
                                        </div>
                                        
                                        <div class="config-group mt-4">
                                            <h4>Google Maps</h4>
                                            <div class="config-item">
                                                <label class="config-label">API Key</label>
                                                <input type="text" class="form-control" name="google_maps_api" value="{{ $stats['etablissement']->getSetting('google_maps_api', '') }}">
                                                <small class="text-muted">Clé API Google Maps pour l'affichage des cartes</small>
                                            </div>
                                            <div class="config-item mt-3">
                                                <label class="config-label">Latitude</label>
                                                <input type="text" class="form-control" name="latitude" value="{{ $stats['etablissement']->getSetting('latitude', '') }}">
                                            </div>
                                            <div class="config-item mt-3">
                                                <label class="config-label">Longitude</label>
                                                <input type="text" class="form-control" name="longitude" value="{{ $stats['etablissement']->getSetting('longitude', '') }}">
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
                            
                            <!-- Newsletter -->
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
                                        <div class="stat-mini-value">{{ $stats['etablissement']->getSetting('subscribers_count', '0') }}</div>
                                        <div class="stat-mini-label">Abonnés</div>
                                    </div>
                                    <div class="stat-mini-card">
                                        <div class="stat-mini-value">{{ $stats['etablissement']->getSetting('open_rate', '0') }}%</div>
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
                            
                            <!-- Abonnés -->
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
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td colspan="3" class="text-center">Aucun abonné pour le moment</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            
                            <!-- Commentaires -->
                            <div class="tab-pane fade" id="v-pills-comments" role="tabpanel" aria-labelledby="v-pills-comments-tab">
                                <div class="tab-content-header">
                                    <h3 class="tab-title">
                                        <i class="fas fa-comments me-2" style="color: #ffd166;"></i>
                                        Commentaires
                                    </h3>
                                </div>
                                
                                <div class="comments-list">
                                    <div class="empty-state">
                                        <i class="fas fa-comments fa-4x mb-3"></i>
                                        <p>Aucun commentaire pour le moment</p>
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
                <form action="{{ route('cms.admin.themes.upload') }}" method="POST" enctype="multipart/form-data">
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
    
    <style>
        /* Styles spécifiques CMS */
        .vertical-tabs-wrapper {
            background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
            height: 100%;
            border-right: 1px solid #eaeaea;
            border-radius: 12px 0 0 12px;
            padding: 20px 0;
        }
        
        .etablissement-profile-summary {
            text-align: center;
            padding: 20px;
            border-bottom: 1px solid #eaeaea;
            margin-bottom: 15px;
        }
        
        .profile-icon-large {
            width: 80px;
            height: 80px;
            border-radius: 20px;
            background: linear-gradient(135deg, var(--primary-color, #4361ee), #3a56e4);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            margin: 0 auto 15px;
        }
        
        .vertical-tabs {
            padding: 0 15px;
        }
        
        .vertical-tabs .nav-link {
            border-radius: 10px;
            color: #495057;
            padding: 12px 15px;
            margin-bottom: 5px;
            transition: all 0.3s ease;
        }
        
        .vertical-tabs .nav-link:hover {
            background: rgba(67, 97, 238, 0.1);
            color: var(--primary-color, #4361ee);
        }
        
        .vertical-tabs .nav-link.active {
            background: linear-gradient(90deg, var(--primary-color, #4361ee), #3a56e4);
            color: white;
        }
        
        .tab-content-wrapper {
            padding: 30px;
            background: white;
            border-radius: 0 12px 12px 0;
            min-height: 500px;
        }
        
        .tab-content-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f0f0f0;
        }
        
        .tab-title {
            font-size: 1.3rem;
            font-weight: 600;
            color: #333;
            margin: 0;
        }
        
        .info-card {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
        }
        
        .info-card-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 15px;
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
            border-color: var(--primary-color, #4361ee);
            box-shadow: 0 4px 12px rgba(67, 97, 238, 0.2);
        }
        
        .theme-preview {
            background: #e9ecef;
            height: 150px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .theme-info {
            padding: 15px;
            text-align: center;
        }
        
        .theme-info h5 {
            margin-bottom: 5px;
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
            color: var(--primary-color, #4361ee);
        }
        
        .stat-mini-label {
            color: #6c757d;
            margin-top: 5px;
        }
        
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            background: #f8f9fa;
            border-radius: 12px;
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
        }
    </style>
    
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
    </script>
@endsection