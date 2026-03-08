@extends('layouts.app')

@section('content')
    <!-- MAIN CONTENT -->
    <main class="dashboard-content">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">
                <span class="page-title-icon"><i class="fas fa-building"></i></span>
                Détails de l'Établissement
            </h1>
            
            <div class="page-actions">
                <a href="{{ route('etablissements.edit', $etablissement->id) }}" class="btn btn-primary">
                    <i class="fas fa-edit me-2"></i>Modifier
                </a>
                <a href="{{ route('etablissements.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Retour à la liste
                </a>
            </div>
        </div>
        
        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stats-card-modern">
                <div class="stats-header-modern">
                    <div>
                        <div class="stats-value-modern">{{ $etablissement->activities_count ?? 0 }}</div>
                        <div class="stats-label-modern">Activités associées</div>
                    </div>
                    <div class="stats-icon-modern" style="background: linear-gradient(135deg, var(--primary-color), #3a56e4);">
                        <i class="fas fa-tasks"></i>
                    </div>
                </div>
            </div>
            
            <div class="stats-card-modern">
                <div class="stats-header-modern">
                    <div>
                        <div class="stats-value-modern">{{ $etablissement->user->name ?? 'N/A' }}</div>
                        <div class="stats-label-modern">Responsable</div>
                    </div>
                    <div class="stats-icon-modern" style="background: linear-gradient(135deg, var(--accent-color), #06b48a);">
                        <i class="fas fa-user-tie"></i>
                    </div>
                </div>
            </div>
            
            <div class="stats-card-modern">
                <div class="stats-header-modern">
                    <div>
                        <div class="stats-value-modern">{{ $etablissement->ville }}</div>
                        <div class="stats-label-modern">Ville</div>
                    </div>
                    <div class="stats-icon-modern" style="background: linear-gradient(135deg, #ffd166, #ffb347);">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                </div>
            </div>
            
            <div class="stats-card-modern">
                <div class="stats-header-modern">
                    <div>
                        <div class="stats-value-modern">
                            @if($etablissement->is_active)
                                <span class="badge bg-success">Actif</span>
                            @else
                                <span class="badge bg-secondary">Inactif</span>
                            @endif
                        </div>
                        <div class="stats-label-modern">Statut</div>
                    </div>
                    <div class="stats-icon-modern" style="background: linear-gradient(135deg, #ef476f, #d4335f);">
                        <i class="fas fa-circle"></i>
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
                            <h4 class="profile-name">{{ $etablissement->name }}</h4>
                            <p class="profile-type">{{ $etablissement->lname ?? 'Établissement' }}</p>
                            <div class="profile-status">
                                @if($etablissement->is_active)
                                    <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i>Actif</span>
                                @else
                                    <span class="badge bg-secondary"><i class="fas fa-times-circle me-1"></i>Inactif</span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="nav flex-column nav-pills vertical-tabs" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                            <button class="nav-link active" id="v-pills-info-tab" data-bs-toggle="pill" data-bs-target="#v-pills-info" type="button" role="tab" aria-controls="v-pills-info" aria-selected="true">
                                <i class="fas fa-info-circle me-2"></i>
                                Informations générales
                            </button>
                            
                            <button class="nav-link" id="v-pills-contact-tab" data-bs-toggle="pill" data-bs-target="#v-pills-contact" type="button" role="tab" aria-controls="v-pills-contact" aria-selected="false">
                                <i class="fas fa-address-card me-2"></i>
                                Coordonnées
                            </button>
                            
                            <button class="nav-link" id="v-pills-activities-tab" data-bs-toggle="pill" data-bs-target="#v-pills-activities" type="button" role="tab" aria-controls="v-pills-activities" aria-selected="false">
                                <i class="fas fa-tasks me-2"></i>
                                Activités associées
                                @if($etablissement->activities_count > 0)
                                    <span class="badge bg-primary float-end">{{ $etablissement->activities_count }}</span>
                                @endif
                            </button>
                            
                            <button class="nav-link" id="v-pills-products-tab" data-bs-toggle="pill" data-bs-target="#v-pills-products" type="button" role="tab" aria-controls="v-pills-products" aria-selected="false">
                                <i class="fas fa-shopping-cart me-2"></i>
                                Commandes / Produits / Services
                                <span class="badge bg-info float-end">{{ $etablissement->products_count ?? 0 }}</span>
                            </button>
                            
                            <button class="nav-link" id="v-pills-config-tab" data-bs-toggle="pill" data-bs-target="#v-pills-config" type="button" role="tab" aria-controls="v-pills-config" aria-selected="false">
                                <i class="fas fa-cog me-2"></i>
                                Configuration
                            </button>
                            
                            <button class="nav-link" id="v-pills-contacts-tab" data-bs-toggle="pill" data-bs-target="#v-pills-contacts" type="button" role="tab" aria-controls="v-pills-contacts" aria-selected="false">
                                <i class="fas fa-address-book me-2"></i>
                                Contacts
                                <span class="badge bg-primary float-end">{{ $etablissement->contacts_count ?? 0 }}</span>
                            </button>
                            
                            <button class="nav-link" id="v-pills-localization-tab" data-bs-toggle="pill" data-bs-target="#v-pills-localization" type="button" role="tab" aria-controls="v-pills-localization" aria-selected="false">
                                <i class="fas fa-map-marked-alt me-2"></i>
                                Localisation
                            </button>
                            
                            <button class="nav-link" id="v-pills-pages-tab" data-bs-toggle="pill" data-bs-target="#v-pills-pages" type="button" role="tab" aria-controls="v-pills-pages" aria-selected="false">
                                <i class="fas fa-file-alt me-2"></i>
                                Pages
                                <span class="badge bg-primary float-end">{{ $etablissement->pages_count ?? 0 }}</span>
                            </button>
                            
                            <button class="nav-link" id="v-pills-gallery-tab" data-bs-toggle="pill" data-bs-target="#v-pills-gallery" type="button" role="tab" aria-controls="v-pills-gallery" aria-selected="false">
                                <i class="fas fa-images me-2"></i>
                                Galerie
                                <span class="badge bg-primary float-end">{{ $etablissement->images_count ?? 0 }}</span>
                            </button>
                            
                            <button class="nav-link" id="v-pills-documents-tab" data-bs-toggle="pill" data-bs-target="#v-pills-documents" type="button" role="tab" aria-controls="v-pills-documents" aria-selected="false">
                                <i class="fas fa-file-pdf me-2"></i>
                                Documents
                                <span class="badge bg-primary float-end">{{ $etablissement->documents_count ?? 0 }}</span>
                            </button>
                            
                            <button class="nav-link" id="v-pills-newsletter-tab" data-bs-toggle="pill" data-bs-target="#v-pills-newsletter" type="button" role="tab" aria-controls="v-pills-newsletter" aria-selected="false">
                                <i class="fas fa-envelope-open-text me-2"></i>
                                Newsletter
                            </button>
                            
                            <button class="nav-link" id="v-pills-subscribers-tab" data-bs-toggle="pill" data-bs-target="#v-pills-subscribers" type="button" role="tab" aria-controls="v-pills-subscribers" aria-selected="false">
                                <i class="fas fa-users me-2"></i>
                                Abonnés
                                <span class="badge bg-primary float-end">{{ $etablissement->subscribers_count ?? 0 }}</span>
                            </button>
                            
                            <button class="nav-link" id="v-pills-comments-tab" data-bs-toggle="pill" data-bs-target="#v-pills-comments" type="button" role="tab" aria-controls="v-pills-comments" aria-selected="false">
                                <i class="fas fa-comments me-2"></i>
                                Commentaires
                                <span class="badge bg-primary float-end">{{ $etablissement->comments_count ?? 0 }}</span>
                            </button>
                            
                            <button class="nav-link" id="v-pills-appointments-tab" data-bs-toggle="pill" data-bs-target="#v-pills-appointments" type="button" role="tab" aria-controls="v-pills-appointments" aria-selected="false">
                                <i class="fas fa-calendar-check me-2"></i>
                                Rendez-vous
                                <span class="badge bg-primary float-end">{{ $etablissement->appointments_count ?? 0 }}</span>
                            </button>
                            
                            <button class="nav-link" id="v-pills-users-tab" data-bs-toggle="pill" data-bs-target="#v-pills-users" type="button" role="tab" aria-controls="v-pills-users" aria-selected="false">
                                <i class="fas fa-user-friends me-2"></i>
                                Utilisateurs
                                <span class="badge bg-primary float-end">{{ $etablissement->users_count ?? 0 }}</span>
                            </button>
                            
                            <button class="nav-link" id="v-pills-prime-time-tab" data-bs-toggle="pill" data-bs-target="#v-pills-prime-time" type="button" role="tab" aria-controls="v-pills-prime-time" aria-selected="false">
                                <i class="fas fa-clock me-2"></i>
                                Prime Time
                            </button>
                            
                            <button class="nav-link" id="v-pills-history-tab" data-bs-toggle="pill" data-bs-target="#v-pills-history" type="button" role="tab" aria-controls="v-pills-history" aria-selected="false">
                                <i class="fas fa-history me-2"></i>
                                Historique
                            </button>
                            
                            <button class="nav-link" id="v-pills-stats-tab" data-bs-toggle="pill" data-bs-target="#v-pills-stats" type="button" role="tab" aria-controls="v-pills-stats" aria-selected="false">
                                <i class="fas fa-chart-bar me-2"></i>
                                Statistiques
                            </button>
                            
                            <button class="nav-link text-danger" id="v-pills-danger-tab" data-bs-toggle="pill" data-bs-target="#v-pills-danger" type="button" role="tab" aria-controls="v-pills-danger" aria-selected="false">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                Zone de danger
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Right Tab Content -->
                <div class="col-md-9">
                    <div class="tab-content-wrapper">
                        <div class="tab-content" id="v-pills-tabContent">
                            <!-- Informations générales -->
                            <div class="tab-pane fade show active" id="v-pills-info" role="tabpanel" aria-labelledby="v-pills-info-tab">
                                <div class="tab-content-header">
                                    <h3 class="tab-title">
                                        <i class="fas fa-info-circle me-2" style="color: var(--primary-color);"></i>
                                        Informations générales
                                    </h3>
                                </div>
                                
                                <div class="info-grid">
                                    <div class="info-item">
                                        <div class="info-label">Nom complet</div>
                                        <div class="info-value">{{ $etablissement->name }}</div>
                                    </div>
                                    
                                    <div class="info-item">
                                        <div class="info-label">Nom court / Sigle</div>
                                        <div class="info-value">{{ $etablissement->lname ?? 'Non défini' }}</div>
                                    </div>
                                    
                                    <div class="info-item full-width">
                                        <div class="info-label">Adresse complète</div>
                                        <div class="info-value">
                                            {{ $etablissement->adresse }}<br>
                                            {{ $etablissement->zip_code }} {{ $etablissement->ville }}
                                        </div>
                                    </div>
                                    
                                    <div class="info-item">
                                        <div class="info-label">Date de création</div>
                                        <div class="info-value">{{ $etablissement->created_at ? $etablissement->created_at->format('d/m/Y H:i') : 'N/A' }}</div>
                                    </div>
                                    
                                    <div class="info-item">
                                        <div class="info-label">Dernière modification</div>
                                        <div class="info-value">{{ $etablissement->updated_at ? $etablissement->updated_at->format('d/m/Y H:i') : 'N/A' }}</div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Coordonnées -->
                            <div class="tab-pane fade" id="v-pills-contact" role="tabpanel" aria-labelledby="v-pills-contact-tab">
                                <div class="tab-content-header">
                                    <h3 class="tab-title">
                                        <i class="fas fa-address-card me-2" style="color: var(--accent-color);"></i>
                                        Coordonnées
                                    </h3>
                                </div>
                                
                                <div class="contact-cards">
                                    <div class="contact-card">
                                        <div class="contact-icon" style="background: linear-gradient(135deg, #45b7d1, #3a9bb8);">
                                            <i class="fas fa-phone-alt"></i>
                                        </div>
                                        <div class="contact-details">
                                            <div class="contact-label">Téléphone</div>
                                            <div class="contact-value">{{ $etablissement->phone ?? 'Non renseigné' }}</div>
                                            @if($etablissement->fax)
                                                <div class="contact-sub">Fax: {{ $etablissement->fax }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <div class="contact-card">
                                        <div class="contact-icon" style="background: linear-gradient(135deg, #96ceb4, #7dba9a);">
                                            <i class="fas fa-envelope"></i>
                                        </div>
                                        <div class="contact-details">
                                            <div class="contact-label">Email de contact</div>
                                            <div class="contact-value">{{ $etablissement->email_contact ?? 'Non renseigné' }}</div>
                                            <a href="mailto:{{ $etablissement->email_contact }}" class="contact-action">Envoyer un email</a>
                                        </div>
                                    </div>
                                    
                                    <div class="contact-card full-width">
                                        <div class="contact-icon" style="background: linear-gradient(135deg, #ffd166, #ffb347);">
                                            <i class="fas fa-globe"></i>
                                        </div>
                                        <div class="contact-details">
                                            <div class="contact-label">Site web</div>
                                            <div class="contact-value">{{ $etablissement->website ?? 'Non renseigné' }}</div>
                                            @if($etablissement->website)
                                                <a href="{{ $etablissement->website }}" target="_blank" class="contact-action">Visiter le site <i class="fas fa-external-link-alt ms-1"></i></a>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <div class="contact-card full-width">
                                        <div class="contact-icon" style="background: linear-gradient(135deg, #9b59b6, #8e44ad);">
                                            <i class="fas fa-user"></i>
                                        </div>
                                        <div class="contact-details">
                                            <div class="contact-label">Responsable</div>
                                            <div class="contact-value">{{ $etablissement->user->name ?? 'Non assigné' }}</div>
                                            @if($etablissement->user)
                                                <div class="contact-sub">{{ $etablissement->user->email }}</div>
                                                <a href="{{ url('users.show', $etablissement->user_id) }}" class="contact-action">Voir le profil</a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Activités associées -->
                            <div class="tab-pane fade" id="v-pills-activities" role="tabpanel" aria-labelledby="v-pills-activities-tab">
                                <div class="tab-content-header">
                                    <h3 class="tab-title">
                                        <i class="fas fa-tasks me-2" style="color: #e6a100;"></i>
                                        Activités associées
                                    </h3>
                                    <button class="btn btn-sm btn-outline-primary" onclick="showAddActivityModal()">
                                        <i class="fas fa-plus-circle me-1"></i>Ajouter une activité
                                    </button>
                                </div>
                                
                                <div class="activities-list">
                                    @forelse($etablissement->activities as $activity)
                                        <div class="activity-card-modern">
                                            <div class="activity-info">
                                                <div class="activity-icon-small" style="background: linear-gradient(135deg, #45b7d1, #3a9bb8);">
                                                    <i class="fas fa-tag"></i>
                                                </div>
                                                <div>
                                                    <div class="activity-name">{{ $activity->name }}</div>
                                                    @if($activity->pivot->created_at)
                                                        <div class="activity-date">Ajouté le {{ $activity->pivot->created_at->format('d/m/Y') }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="activity-actions">
                                                <form action="{{ route('etablissements.detach-activity', [$etablissement->id, $activity->id]) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Êtes-vous sûr de vouloir détacher cette activité ?')">
                                                        <i class="fas fa-unlink"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="empty-state-small">
                                            <i class="fas fa-tasks fa-3x mb-3" style="color: #ddd;"></i>
                                            <p>Aucune activité associée à cet établissement.</p>
                                            <button class="btn btn-primary btn-sm" onclick="showAddActivityModal()">
                                                <i class="fas fa-plus-circle me-1"></i>Ajouter une activité
                                            </button>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                            
                            <!-- Commandes / Produits / Services -->
                            <div class="tab-pane fade" id="v-pills-products" role="tabpanel" aria-labelledby="v-pills-products-tab">
                                <div class="tab-content-header">
                                    <h3 class="tab-title">
                                        <i class="fas fa-shopping-cart me-2" style="color: #45b7d1;"></i>
                                        Commandes, Produits & Services
                                    </h3>
                                    <button class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-plus-circle me-1"></i>Nouveau produit
                                    </button>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="stat-card-mini">
                                            <div class="stat-mini-label">Commandes totales</div>
                                            <div class="stat-mini-value">156</div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="stat-card-mini">
                                            <div class="stat-mini-label">Produits</div>
                                            <div class="stat-mini-value">24</div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="stat-card-mini">
                                            <div class="stat-mini-label">Services</div>
                                            <div class="stat-mini-value">12</div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="table-container-modern mt-4">
                                    <table class="modern-table">
                                        <thead>
                                            <tr>
                                                <th>Type</th>
                                                <th>Nom</th>
                                                <th>Prix</th>
                                                <th>Statut</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><span class="badge bg-primary">Produit</span></td>
                                                <td>Forfait Basic</td>
                                                <td>299 €</td>
                                                <td><span class="badge bg-success">Actif</span></td>
                                                <td>
                                                    <button class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><span class="badge bg-info">Service</span></td>
                                                <td>Consultation</td>
                                                <td>150 €</td>
                                                <td><span class="badge bg-success">Actif</span></td>
                                                <td>
                                                    <button class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            
                            <!-- Configuration -->
                            <div class="tab-pane fade" id="v-pills-config" role="tabpanel" aria-labelledby="v-pills-config-tab">
                                <div class="tab-content-header">
                                    <h3 class="tab-title">
                                        <i class="fas fa-cog me-2" style="color: #6c757d;"></i>
                                        Configuration
                                    </h3>
                                </div>
                                
                                <div class="config-sections">
                                    <div class="config-group">
                                        <h4>Paramètres généraux</h4>
                                        <div class="config-item">
                                            <div class="config-label">Fuseau horaire</div>
                                            <div class="config-value">Europe/Paris</div>
                                            <button class="btn btn-sm btn-outline-secondary">Modifier</button>
                                        </div>
                                        <div class="config-item">
                                            <div class="config-label">Langue par défaut</div>
                                            <div class="config-value">Français</div>
                                            <button class="btn btn-sm btn-outline-secondary">Modifier</button>
                                        </div>
                                    </div>
                                    
                                    <div class="config-group">
                                        <h4>Notifications</h4>
                                        <div class="config-item">
                                            <div class="config-label">Email de notification</div>
                                            <div class="config-value">{{ $etablissement->email_contact ?? 'Non configuré' }}</div>
                                            <button class="btn btn-sm btn-outline-secondary">Modifier</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Contacts -->
                            <div class="tab-pane fade" id="v-pills-contacts" role="tabpanel" aria-labelledby="v-pills-contacts-tab">
                                <div class="tab-content-header">
                                    <h3 class="tab-title">
                                        <i class="fas fa-address-book me-2" style="color: #9b59b6;"></i>
                                        Contacts
                                    </h3>
                                    <button class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-plus-circle me-1"></i>Ajouter un contact
                                    </button>
                                </div>
                                
                                <div class="contacts-grid">
                                    <!-- Exemple de contact -->
                                    <div class="contact-card-mini">
                                        <div class="contact-mini-avatar" style="background: linear-gradient(135deg, #45b7d1, #3a9bb8);">
                                            JD
                                        </div>
                                        <div class="contact-mini-info">
                                            <div class="contact-mini-name">Jean Dupont</div>
                                            <div class="contact-mini-role">Directeur</div>
                                            <div class="contact-mini-email">jean.dupont@email.com</div>
                                        </div>
                                        <div class="contact-mini-actions">
                                            <button class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Localisation -->
                            <div class="tab-pane fade" id="v-pills-localization" role="tabpanel" aria-labelledby="v-pills-localization-tab">
                                <div class="tab-content-header">
                                    <h3 class="tab-title">
                                        <i class="fas fa-map-marked-alt me-2" style="color: #e6a100;"></i>
                                        Localisation
                                    </h3>
                                </div>
                                
                                <div class="location-info">
                                    <div class="map-placeholder">
                                        <i class="fas fa-map fa-4x mb-3" style="color: #ddd;"></i>
                                        <p>Carte interactive - {{ $etablissement->adresse }}, {{ $etablissement->ville }}</p>
                                    </div>
                                    
                                    <div class="location-details mt-4">
                                        <h5>Coordonnées GPS</h5>
                                        <p>Latitude: 48.8566° N<br>Longitude: 2.3522° E</p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Pages -->
                            <div class="tab-pane fade" id="v-pills-pages" role="tabpanel" aria-labelledby="v-pills-pages-tab">
                                <div class="tab-content-header">
                                    <h3 class="tab-title">
                                        <i class="fas fa-file-alt me-2" style="color: #06d6a0;"></i>
                                        Pages
                                    </h3>
                                    <button class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-plus-circle me-1"></i>Nouvelle page
                                    </button>
                                </div>
                                
                                <div class="pages-list">
                                    <div class="page-item">
                                        <i class="fas fa-file-alt me-2" style="color: #06d6a0;"></i>
                                        <span>Accueil</span>
                                        <span class="badge bg-success ms-2">Publiée</span>
                                        <div class="ms-auto">
                                            <button class="btn btn-sm btn-outline-primary me-1"><i class="fas fa-edit"></i></button>
                                            <button class="btn btn-sm btn-outline-secondary"><i class="fas fa-eye"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Galerie -->
                            <div class="tab-pane fade" id="v-pills-gallery" role="tabpanel" aria-labelledby="v-pills-gallery-tab">
                                <div class="tab-content-header">
                                    <h3 class="tab-title">
                                        <i class="fas fa-images me-2" style="color: #45b7d1;"></i>
                                        Galerie
                                    </h3>
                                    <button class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-upload me-1"></i>Uploader des images
                                    </button>
                                </div>
                                
                                <div class="gallery-grid">
                                    <div class="gallery-item">
                                        <div class="gallery-placeholder">
                                            <i class="fas fa-image fa-3x"></i>
                                        </div>
                                        <div class="gallery-actions">
                                            <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Documents -->
                            <div class="tab-pane fade" id="v-pills-documents" role="tabpanel" aria-labelledby="v-pills-documents-tab">
                                <div class="tab-content-header">
                                    <h3 class="tab-title">
                                        <i class="fas fa-file-pdf me-2" style="color: #e74c3c;"></i>
                                        Documents
                                    </h3>
                                    <button class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-upload me-1"></i>Uploader un document
                                    </button>
                                </div>
                                
                                <div class="documents-list">
                                    <div class="document-item">
                                        <i class="fas fa-file-pdf me-2" style="color: #e74c3c; font-size: 1.5rem;"></i>
                                        <div>
                                            <div>Contrat de service.pdf</div>
                                            <small class="text-muted">2.5 MB • 15/03/2024</small>
                                        </div>
                                        <div class="ms-auto">
                                            <button class="btn btn-sm btn-outline-primary me-1"><i class="fas fa-download"></i></button>
                                            <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Newsletter -->
                            <div class="tab-pane fade" id="v-pills-newsletter" role="tabpanel" aria-labelledby="v-pills-newsletter-tab">
                                <div class="tab-content-header">
                                    <h3 class="tab-title">
                                        <i class="fas fa-envelope-open-text me-2" style="color: #9b59b6;"></i>
                                        Newsletter
                                    </h3>
                                    <button class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-paper-plane me-1"></i>Nouvelle campagne
                                    </button>
                                </div>
                                
                                <div class="stats-grid-mini">
                                    <div class="stat-mini-card">
                                        <div class="stat-mini-value">1,234</div>
                                        <div class="stat-mini-label">Abonnés</div>
                                    </div>
                                    <div class="stat-mini-card">
                                        <div class="stat-mini-value">23%</div>
                                        <div class="stat-mini-label">Taux d'ouverture</div>
                                    </div>
                                </div>
                                
                                <div class="campaigns-list mt-4">
                                    <h5>Campagnes récentes</h5>
                                    <div class="campaign-item">
                                        <span>Newsletter Mars 2024</span>
                                        <span class="badge bg-success">Envoyée</span>
                                        <span class="ms-auto">1,023 ouverts</span>
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
                                    <button class="btn btn-sm btn-outline-primary">
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
                                                <td>client1@email.com</td>
                                                <td>15/03/2024</td>
                                                <td><span class="badge bg-success">Actif</span></td>
                                                <td>
                                                    <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                                                </td>
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
                                    <div class="comment-item">
                                        <div class="comment-avatar">JD</div>
                                        <div class="comment-content">
                                            <div class="comment-header">
                                                <span class="comment-author">Jean Dupont</span>
                                                <span class="comment-date">Il y a 2 jours</span>
                                            </div>
                                            <div class="comment-text">Excellent service, je recommande !</div>
                                            <div class="comment-actions">
                                                <button class="btn btn-sm btn-outline-success"><i class="fas fa-check"></i> Approuver</button>
                                                <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Rendez-vous -->
                            <div class="tab-pane fade" id="v-pills-appointments" role="tabpanel" aria-labelledby="v-pills-appointments-tab">
                                <div class="tab-content-header">
                                    <h3 class="tab-title">
                                        <i class="fas fa-calendar-check me-2" style="color: #e6a100;"></i>
                                        Rendez-vous
                                    </h3>
                                    <button class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-plus-circle me-1"></i>Nouveau rendez-vous
                                    </button>
                                </div>
                                
                                <div class="appointments-calendar">
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle me-2"></i>
                                        Calendrier des rendez-vous à venir
                                    </div>
                                    
                                    <div class="appointment-item">
                                        <div class="appointment-time">14:30</div>
                                        <div class="appointment-details">
                                            <div class="appointment-client">Client: Marie Martin</div>
                                            <div class="appointment-purpose">Consultation initiale</div>
                                        </div>
                                        <div class="appointment-status">
                                            <span class="badge bg-warning">Confirmé</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Utilisateurs -->
                            <div class="tab-pane fade" id="v-pills-users" role="tabpanel" aria-labelledby="v-pills-users-tab">
                                <div class="tab-content-header">
                                    <h3 class="tab-title">
                                        <i class="fas fa-user-friends me-2" style="color: #45b7d1;"></i>
                                        Utilisateurs
                                    </h3>
                                    <button class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-user-plus me-1"></i>Ajouter un utilisateur
                                    </button>
                                </div>
                                
                                <div class="users-grid">
                                    <div class="user-card">
                                        <div class="user-avatar" style="background: linear-gradient(135deg, #45b7d1, #3a9bb8);">
                                            <i class="fas fa-user"></i>
                                        </div>
                                        <div class="user-info">
                                            <div class="user-name">Admin Principal</div>
                                            <div class="user-role">Administrateur</div>
                                            <div class="user-email">admin@example.com</div>
                                        </div>
                                        <div class="user-actions">
                                            <button class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Prime Time -->
                            <div class="tab-pane fade" id="v-pills-prime-time" role="tabpanel" aria-labelledby="v-pills-prime-time-tab">
                                <div class="tab-content-header">
                                    <h3 class="tab-title">
                                        <i class="fas fa-clock me-2" style="color: #ef476f;"></i>
                                        Prime Time
                                    </h3>
                                </div>
                                
                                <div class="prime-time-settings">
                                    <div class="config-item">
                                        <div class="config-label">Heures d'ouverture</div>
                                        <div class="config-value">Lun-Ven: 9h-18h</div>
                                        <button class="btn btn-sm btn-outline-secondary">Modifier</button>
                                    </div>
                                    
                                    <div class="config-item">
                                        <div class="config-label">Périodes de pointe</div>
                                        <div class="config-value">12h-14h, 17h-19h</div>
                                        <button class="btn btn-sm btn-outline-secondary">Configurer</button>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Historique -->
                            <div class="tab-pane fade" id="v-pills-history" role="tabpanel" aria-labelledby="v-pills-history-tab">
                                <div class="tab-content-header">
                                    <h3 class="tab-title">
                                        <i class="fas fa-history me-2" style="color: #8b5cf6;"></i>
                                        Historique des modifications
                                    </h3>
                                </div>
                                
                                <div class="timeline">
                                    <div class="timeline-item">
                                        <div class="timeline-marker" style="background: var(--primary-color);"></div>
                                        <div class="timeline-content">
                                            <div class="timeline-date">{{ $etablissement->created_at ? $etablissement->created_at->format('d/m/Y H:i') : 'N/A' }}</div>
                                            <div class="timeline-title">Création de l'établissement</div>
                                            <div class="timeline-description">L'établissement a été créé par {{ $etablissement->user->name ?? 'le système' }}</div>
                                        </div>
                                    </div>
                                    
                                    @if($etablissement->updated_at && $etablissement->updated_at != $etablissement->created_at)
                                        <div class="timeline-item">
                                            <div class="timeline-marker" style="background: var(--accent-color);"></div>
                                            <div class="timeline-content">
                                                <div class="timeline-date">{{ $etablissement->updated_at->format('d/m/Y H:i') }}</div>
                                                <div class="timeline-title">Dernière modification</div>
                                                <div class="timeline-description">Les informations de l'établissement ont été mises à jour</div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Statistiques -->
                            <div class="tab-pane fade" id="v-pills-stats" role="tabpanel" aria-labelledby="v-pills-stats-tab">
                                <div class="tab-content-header">
                                    <h3 class="tab-title">
                                        <i class="fas fa-chart-bar me-2" style="color: #06d6a0;"></i>
                                        Statistiques
                                    </h3>
                                </div>
                                
                                <div class="stats-charts">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="stat-card-mini">
                                                <div class="stat-mini-label">Nombre d'activités</div>
                                                <div class="stat-mini-value">{{ $etablissement->activities_count ?? 0 }}</div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="stat-card-mini">
                                                <div class="stat-mini-label">Membre depuis</div>
                                                <div class="stat-mini-value">{{ $etablissement->created_at ? $etablissement->created_at->diffForHumans() : 'N/A' }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="progress-stats mt-4">
                                        <h5>Activités par mois</h5>
                                        <canvas id="activityChart" style="height: 300px;"></canvas>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Zone de danger -->
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
                                            <h5>Désactiver l'établissement</h5>
                                            <p class="text-muted">L'établissement ne sera plus visible dans les listes actives.</p>
                                        </div>
                                        <form action="{{ url('etablissements.toggle-status', $etablissement->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-warning">
                                                @if($etablissement->is_active)
                                                    <i class="fas fa-ban me-2"></i>Désactiver
                                                @else
                                                    <i class="fas fa-check-circle me-2"></i>Activer
                                                @endif
                                            </button>
                                        </form>
                                    </div>
                                    
                                    <div class="danger-action">
                                        <div>
                                            <h5>Supprimer l'établissement</h5>
                                            <p class="text-muted">Cette action est irréversible. Toutes les données associées seront perdues.</p>
                                        </div>
                                        <button class="btn btn-danger" onclick="showDeleteConfirmation()">
                                            <i class="fas fa-trash me-2"></i>Supprimer définitivement
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
    
    <!-- Add Activity Modal -->
    <div class="modal fade" id="addActivityModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ url('etablissements.attach-activity', $etablissement->id) }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Ajouter une activité</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="activity_id" class="form-label">Sélectionner une activité</label>
                            <select class="form-select" name="activity_id" id="activity_id" required>
                                <option value="">Choisir une activité...</option>
                                @foreach($availableActivities ?? [] as $activity)
                                    <option value="{{ $activity->id }}">{{ $activity->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">Ajouter</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Delete Confirmation Modal -->
    <div class="modal fade delete-confirm-modal" id="deleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <div class="delete-icon">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <h4 class="delete-title">Confirmer la suppression</h4>
                    <p class="delete-message">Êtes-vous sûr de vouloir supprimer définitivement cet établissement ?</p>
                    
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <strong>Attention :</strong> Cette action est irréversible.
                    </div>
                </div>
                <div class="modal-footer border-0 justify-content-center">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <form action="{{ route('etablissements.destroy', $etablissement->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash me-2"></i>Supprimer définitivement
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- JavaScript -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        function showAddActivityModal() {
            const modal = new bootstrap.Modal(document.getElementById('addActivityModal'));
            modal.show();
        }
        
        function showDeleteConfirmation() {
            const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
            modal.show();
        }
        
        // Animation for tabs
        document.addEventListener('DOMContentLoaded', function() {
            const tabs = document.querySelectorAll('.vertical-tabs .nav-link');
            
            tabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    // Animation effect
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
    
    <style>
        /* Vertical Tabs Styles */
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
            background: linear-gradient(135deg, var(--primary-color), #3a56e4);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            margin: 0 auto 15px;
            box-shadow: 0 4px 10px rgba(67, 97, 238, 0.3);
        }
        
        .profile-name {
            font-weight: 600;
            margin-bottom: 5px;
            color: #333;
        }
        
        .profile-type {
            font-size: 0.9rem;
            color: #666;
            margin-bottom: 10px;
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
            font-weight: 500;
        }
        
        .vertical-tabs .nav-link:hover {
            background: rgba(67, 97, 238, 0.1);
            color: var(--primary-color);
            transform: translateX(3px);
        }
        
        .vertical-tabs .nav-link.active {
            background: linear-gradient(90deg, var(--primary-color), #3a56e4);
            color: white;
            box-shadow: 0 4px 10px rgba(67, 97, 238, 0.3);
        }
        
        .vertical-tabs .nav-link.active i {
            color: white;
        }
        
        .vertical-tabs .nav-link.text-danger:hover {
            background: rgba(239, 71, 111, 0.1);
            color: var(--danger-color);
        }
        
        .vertical-tabs .nav-link.text-danger.active {
            background: linear-gradient(90deg, var(--danger-color), #d4335f);
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
        
        /* Info Grid */
        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }
        
        .info-item {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 10px;
            transition: all 0.3s ease;
        }
        
        .info-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        
        .info-item.full-width {
            grid-column: 1 / -1;
        }
        
        .info-label {
            font-size: 0.85rem;
            color: #666;
            margin-bottom: 5px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .info-value {
            font-size: 1rem;
            font-weight: 500;
            color: #333;
        }
        
        /* Contact Cards */
        .contact-cards {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }
        
        .contact-card {
            display: flex;
            gap: 15px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 12px;
            transition: all 0.3s ease;
        }
        
        .contact-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 12px rgba(0,0,0,0.1);
        }
        
        .contact-card.full-width {
            grid-column: 1 / -1;
        }
        
        .contact-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.3rem;
            flex-shrink: 0;
        }
        
        .contact-details {
            flex: 1;
        }
        
        .contact-label {
            font-size: 0.8rem;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 3px;
        }
        
        .contact-value {
            font-size: 1.1rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 5px;
        }
        
        .contact-sub {
            font-size: 0.9rem;
            color: #888;
        }
        
        .contact-action {
            display: inline-block;
            margin-top: 8px;
            color: var(--primary-color);
            text-decoration: none;
            font-size: 0.9rem;
        }
        
        .contact-action:hover {
            text-decoration: underline;
        }
        
        /* Activities List */
        .activities-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        
        .activity-card-modern {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 10px;
            transition: all 0.3s ease;
        }
        
        .activity-card-modern:hover {
            background: #e9ecef;
            transform: translateX(3px);
        }
        
        .activity-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .activity-icon-small {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1rem;
        }
        
        .activity-name {
            font-weight: 600;
            color: #333;
        }
        
        .activity-date {
            font-size: 0.8rem;
            color: #888;
        }
        
        .empty-state-small {
            text-align: center;
            padding: 40px 20px;
            background: #f8f9fa;
            border-radius: 10px;
        }
        
        /* Timeline */
        .timeline {
            position: relative;
            padding-left: 30px;
        }
        
        .timeline::before {
            content: '';
            position: absolute;
            left: 10px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #e0e0e0;
        }
        
        .timeline-item {
            position: relative;
            margin-bottom: 30px;
        }
        
        .timeline-marker {
            position: absolute;
            left: -30px;
            top: 0;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            border: 3px solid white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .timeline-content {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 10px;
            transition: all 0.3s ease;
        }
        
        .timeline-content:hover {
            transform: translateX(5px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        
        .timeline-date {
            font-size: 0.8rem;
            color: #888;
            margin-bottom: 5px;
        }
        
        .timeline-title {
            font-weight: 600;
            color: #333;
            margin-bottom: 5px;
        }
        
        .timeline-description {
            font-size: 0.9rem;
            color: #666;
        }
        
        /* Danger Zone */
        .danger-zone {
            border: 1px solid #ffcdd2;
            border-radius: 10px;
            padding: 20px;
            background: #fff5f5;
        }
        
        .danger-action {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            border-bottom: 1px solid #ffcdd2;
        }
        
        .danger-action:last-child {
            border-bottom: none;
        }
        
        .danger-action h5 {
            color: #d32f2f;
            margin-bottom: 5px;
        }
        
        /* Stat Cards Mini */
        .stat-card-mini {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            transition: all 0.3s ease;
        }
        
        .stat-card-mini:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        
        .stat-mini-label {
            font-size: 0.85rem;
            color: #666;
            margin-bottom: 5px;
            text-transform: uppercase;
        }
        
        .stat-mini-value {
            font-size: 1.5rem;
            font-weight: 600;
            color: #333;
        }
        
        /* Progress Stats */
        .progress-stats h5 {
            margin-bottom: 20px;
            color: #333;
        }
        
        .progress-stat-item {
            margin-bottom: 15px;
        }
        
        .progress-stat-label {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
            color: #666;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .vertical-tabs-wrapper {
                border-right: none;
                border-radius: 12px 12px 0 0;
            }
            
            .tab-content-wrapper {
                border-radius: 0 0 12px 12px;
            }
            
            .info-grid,
            .contact-cards {
                grid-template-columns: 1fr;
            }
            
            .danger-action {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }
        }
    </style>
@endsection