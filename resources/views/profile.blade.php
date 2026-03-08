@extends('layouts.app')

@section('content')
    
    <!-- MAIN CONTENT -->
    <main class="dashboard-content">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">
                <span class="page-title-icon"><i class="fas fa-user-circle"></i></span>
                Mon Profil
            </h1>
            
            <div class="page-actions">
                <button class="btn btn-outline-secondary" id="editProfileBtn">
                    <i class="fas fa-edit me-2"></i>Modifier
                </button>
                <button class="btn btn-primary" id="saveProfileBtn" style="display: none;">
                    <i class="fas fa-save me-2"></i>Enregistrer
                </button>
            </div>
        </div>
        
        <!-- Profile Header Card -->
        <div class="profile-header-card">
            <div class="profile-cover">
                <div class="profile-avatar-wrapper">
                    <div class="profile-avatar">
                        <img src="{{ Auth::user()->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&size=150&background=4361ee&color=fff' }}" alt="Profile Avatar" id="profileAvatar">
                        <button class="avatar-edit-btn" id="avatarEditBtn">
                            <i class="fas fa-camera"></i>
                        </button>
                    </div>
                </div>
                <div class="profile-info-wrapper">
                    <h2 class="profile-name" id="profileName">{{ Auth::user()->name }}</h2>
                    <p class="profile-role" id="profileRole">{{ Auth::user()->position ?? 'Utilisateur' }}</p>
                    <div class="profile-meta">
                        <span><i class="fas fa-envelope me-2"></i>{{ Auth::user()->email }}</span>
                        <span><i class="fas fa-calendar-alt me-2"></i>Membre depuis {{ Auth::user()->created_at->format('d/m/Y') }}</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Tabs Navigation - Modern Design -->
        <div class="profile-tabs-container">
            <ul class="modern-tabs" id="profileTabs" role="tablist">
                <li class="tab-item active" data-tab="info">
                    <a class="tab-link" href="#info" role="tab">
                        <i class="fas fa-info-circle"></i>
                        <span>Informations</span>
                    </a>
                </li>
                <li class="tab-item" data-tab="security">
                    <a class="tab-link" href="#security" role="tab">
                        <i class="fas fa-shield-alt"></i>
                        <span>Sécurité</span>
                    </a>
                </li>
                <li class="tab-item" data-tab="preferences">
                    <a class="tab-link" href="#preferences" role="tab">
                        <i class="fas fa-sliders-h"></i>
                        <span>Préférences</span>
                    </a>
                </li>
                <li class="tab-item" data-tab="notifications">
                    <a class="tab-link" href="#notifications" role="tab">
                        <i class="fas fa-bell"></i>
                        <span>Notifications</span>
                    </a>
                </li>
                <li class="tab-item" data-tab="activity">
                    <a class="tab-link" href="#activity" role="tab">
                        <i class="fas fa-history"></i>
                        <span>Activité</span>
                    </a>
                </li>
            </ul>
        </div>
        
        <!-- Tab Panes Content -->
        <div class="tab-content profile-tab-content">
            <!-- Informations Personnelles Tab -->
            <div class="tab-pane active" id="info" role="tabpanel">
                <div class="main-card-modern">
                    <div class="card-header-modern">
                        <h3 class="card-title-modern">Informations personnelles</h3>
                    </div>
                    
                    <div class="card-body-modern">
                        <form id="profileInfoForm">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label-modern">Nom complet <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control-modern" name="name" value="{{ Auth::user()->name }}" readonly disabled id="fullName">
                                    <div class="invalid-feedback" id="name-error"></div>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label-modern">Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control-modern" name="email" value="{{ Auth::user()->email }}" readonly disabled id="email">
                                    <div class="invalid-feedback" id="email-error"></div>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label-modern">Téléphone</label>
                                    <input type="tel" class="form-control-modern" name="phone" value="{{ Auth::user()->phone ?? '' }}" placeholder="Ex: +33 6 12 34 56 78" readonly disabled id="phone">
                                    <div class="invalid-feedback" id="phone-error"></div>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label-modern">Fonction</label>
                                    <input type="text" class="form-control-modern" name="position" value="{{ Auth::user()->position ?? '' }}" placeholder="Ex: Chef de projet" readonly disabled id="position">
                                    <div class="invalid-feedback" id="position-error"></div>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label-modern">Département</label>
                                    <input type="text" class="form-control-modern" name="department" value="{{ Auth::user()->department ?? '' }}" placeholder="Ex: Développement" readonly disabled id="department">
                                    <div class="invalid-feedback" id="department-error"></div>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label-modern">Localisation</label>
                                    <input type="text" class="form-control-modern" name="location" value="{{ Auth::user()->location ?? '' }}" placeholder="Ex: Paris, France" readonly disabled id="location">
                                    <div class="invalid-feedback" id="location-error"></div>
                                </div>
                                
                                <div class="col-12 mb-3">
                                    <label class="form-label-modern">Bio</label>
                                    <textarea class="form-control-modern" name="bio" rows="4" placeholder="Parlez-nous de vous..." readonly disabled id="bio">{{ Auth::user()->bio ?? '' }}</textarea>
                                    <div class="invalid-feedback" id="bio-error"></div>
                                </div>
                            </div>
                            
                            <!-- Bouton Annuler en mode édition -->
                            <div class="text-end mt-3" id="editActions" style="display: none;">
                                <button type="button" class="btn btn-outline-secondary me-2" id="cancelEditBtn">
                                    <i class="fas fa-times me-2"></i>Annuler
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                
                <!-- Stats Cards pour l'utilisateur -->
                <div class="stats-grid mt-4">
                    <div class="stats-card-modern">
                        <div class="stats-header-modern">
                            <div>
                                <div class="stats-value-modern" id="userProjects">{{ $stats['totalProjects'] ?? 0 }}</div>
                                <div class="stats-label-modern">Projets</div>
                            </div>
                            <div class="stats-icon-modern" style="background: linear-gradient(135deg, var(--primary-color), #3a56e4);">
                                <i class="fas fa-project-diagram"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="stats-card-modern">
                        <div class="stats-header-modern">
                            <div>
                                <div class="stats-value-modern" id="userTasks">{{ $stats['totalTasks'] ?? 0 }}</div>
                                <div class="stats-label-modern">Tâches</div>
                            </div>
                            <div class="stats-icon-modern" style="background: linear-gradient(135deg, #06b48a, #049a72);">
                                <i class="fas fa-tasks"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="stats-card-modern">
                        <div class="stats-header-modern">
                            <div>
                                <div class="stats-value-modern" id="userCompleted">{{ $stats['completedTasks'] ?? 0 }}</div>
                                <div class="stats-label-modern">Terminées</div>
                            </div>
                            <div class="stats-icon-modern" style="background: linear-gradient(135deg, #ef476f, #d4335f);">
                                <i class="fas fa-check-circle"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="stats-card-modern">
                        <div class="stats-header-modern">
                            <div>
                                <div class="stats-value-modern" id="userPoints">{{ $stats['points'] ?? 0 }}</div>
                                <div class="stats-label-modern">Points</div>
                            </div>
                            <div class="stats-icon-modern" style="background: linear-gradient(135deg, #ffd166, #ffb347);">
                                <i class="fas fa-star"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Sécurité Tab -->
            <div class="tab-pane" id="security" role="tabpanel">
                <div class="main-card-modern">
                    <div class="card-header-modern">
                        <h3 class="card-title-modern">Sécurité du compte</h3>
                    </div>
                    
                    <div class="card-body-modern">
                        <form id="passwordForm">
                            @csrf
                            @method('PUT')
                            <div class="mb-4">
                                <h4 class="section-subtitle">Changer le mot de passe</h4>
                                
                                <div class="mb-3">
                                    <label class="form-label-modern">Mot de passe actuel <span class="text-danger">*</span></label>
                                    <div class="password-input-wrapper">
                                        <input type="password" class="form-control-modern" name="current_password" id="current_password" placeholder="Entrez votre mot de passe actuel" required>
                                        <span class="password-toggle"><i class="fas fa-eye"></i></span>
                                    </div>
                                    <div class="invalid-feedback" id="current_password-error"></div>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label-modern">Nouveau mot de passe <span class="text-danger">*</span></label>
                                    <div class="password-input-wrapper">
                                        <input type="password" class="form-control-modern" name="new_password" id="new_password" placeholder="Entrez votre nouveau mot de passe" required>
                                        <span class="password-toggle"><i class="fas fa-eye"></i></span>
                                    </div>
                                    <div class="password-strength mt-2">
                                        <div class="strength-bar"></div>
                                        <div class="strength-bar"></div>
                                        <div class="strength-bar"></div>
                                        <div class="strength-bar"></div>
                                        <small class="strength-text" id="strengthText">Faible</small>
                                    </div>
                                    <div class="invalid-feedback" id="new_password-error"></div>
                                    <small class="text-muted">Le mot de passe doit contenir au moins 8 caractères, une majuscule, une minuscule et un chiffre.</small>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label-modern">Confirmer le nouveau mot de passe <span class="text-danger">*</span></label>
                                    <div class="password-input-wrapper">
                                        <input type="password" class="form-control-modern" name="new_password_confirmation" id="new_password_confirmation" placeholder="Confirmez votre nouveau mot de passe" required>
                                        <span class="password-toggle"><i class="fas fa-eye"></i></span>
                                    </div>
                                    <div class="invalid-feedback" id="new_password_confirmation-error"></div>
                                </div>
                                
                                <button type="submit" class="btn btn-primary" id="changePasswordBtn">
                                    <i class="fas fa-key me-2"></i>Mettre à jour le mot de passe
                                </button>
                            </div>
                        </form>
                        
                        <hr class="divider-modern">
                        
                        <div class="mb-4">
                            <h4 class="section-subtitle">Authentification à deux facteurs</h4>
                            <p class="text-muted">Ajoutez une couche de sécurité supplémentaire à votre compte.</p>
                            
                            <div class="d-flex align-items-center justify-content-between p-3 bg-light rounded">
                                <div>
                                    <strong>Authentification à deux facteurs</strong>
                                    <p class="mb-0 small text-muted">Protégez votre compte avec une application d'authentification</p>
                                </div>
                                <label class="switch-modern">
                                    <input type="checkbox" id="twoFactorToggle" {{ Auth::user()->google2fa_secret ? 'checked' : '' }}>
                                    <span class="slider-modern round"></span>
                                </label>
                            </div>
                            
                            <!-- QR Code pour 2FA (affiché seulement si activé) -->
                            <div id="twoFactorSection" style="display: none;" class="mt-3">
                                <div class="alert alert-info">
                                    <h5>Configuration de l'authentification à deux facteurs</h5>
                                    <p>1. Scannez ce QR code avec Google Authenticator ou une application compatible</p>
                                    <p>2. Entrez le code à 6 chiffres généré par l'application</p>
                                </div>
                                <div class="text-center">
                                    <div id="qrcode"></div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-6 offset-md-3">
                                        <input type="text" class="form-control" id="twoFactorCode" placeholder="Entrez le code à 6 chiffres">
                                        <button class="btn btn-success mt-2 w-100" id="verifyTwoFactorBtn">Vérifier et activer</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <hr class="divider-modern">
                        
                        <div>
                            <h4 class="section-subtitle">Sessions actives</h4>
                            
                            <div id="sessionsList">
                                @foreach($sessions ?? [] as $session)
                                <div class="session-item" data-session-id="{{ $session['id'] }}">
                                    <div class="session-device">
                                        <i class="fas {{ $session['device'] == 'mobile' ? 'fa-mobile-alt' : 'fa-laptop' }} me-3"></i>
                                        <div>
                                            <strong>{{ $session['device_name'] ?? 'Windows - Chrome' }}</strong>
                                            <p class="mb-0 small text-muted">IP: {{ $session['ip'] }} • Dernière activité: {{ $session['last_activity'] }}</p>
                                        </div>
                                    </div>
                                    @if($session['current'] ?? false)
                                        <span class="badge bg-success">Actuelle</span>
                                    @else
                                        <button class="btn btn-sm btn-outline-danger revoke-session" data-session-id="{{ $session['id'] }}">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    @endif
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Préférences Tab -->
            <div class="tab-pane" id="preferences" role="tabpanel">
                <div class="main-card-modern">
                    <div class="card-header-modern">
                        <h3 class="card-title-modern">Préférences</h3>
                    </div>
                    
                    <div class="card-body-modern">
                        <form id="preferencesForm">
                            @csrf
                            @method('PUT')
                            <div class="mb-4">
                                <h4 class="section-subtitle">Apparence</h4>
                                
                                <div class="preference-item">
                                    <div>
                                        <strong>Thème</strong>
                                        <p class="mb-0 small text-muted">Choisissez le thème de l'application</p>
                                    </div>
                                    <div class="theme-selector">
                                        <div class="theme-option {{ (Auth::user()->preferences['theme'] ?? 'light') == 'light' ? 'active' : '' }}" data-theme="light">
                                            <i class="fas fa-sun"></i>
                                            <span>Clair</span>
                                        </div>
                                        <div class="theme-option {{ (Auth::user()->preferences['theme'] ?? '') == 'dark' ? 'active' : '' }}" data-theme="dark">
                                            <i class="fas fa-moon"></i>
                                            <span>Sombre</span>
                                        </div>
                                        <div class="theme-option {{ (Auth::user()->preferences['theme'] ?? '') == 'system' ? 'active' : '' }}" data-theme="system">
                                            <i class="fas fa-desktop"></i>
                                            <span>Système</span>
                                        </div>
                                    </div>
                                    <input type="hidden" name="theme" id="themeInput" value="{{ Auth::user()->preferences['theme'] ?? 'light' }}">
                                </div>
                                
                                <div class="preference-item">
                                    <div>
                                        <strong>Langue</strong>
                                        <p class="mb-0 small text-muted">Sélectionnez votre langue préférée</p>
                                    </div>
                                    <select class="form-select-modern" name="language" id="languageSelect" style="width: auto;">
                                        <option value="fr" {{ (Auth::user()->preferences['language'] ?? 'fr') == 'fr' ? 'selected' : '' }}>Français</option>
                                        <option value="en" {{ (Auth::user()->preferences['language'] ?? '') == 'en' ? 'selected' : '' }}>English</option>
                                        <option value="es" {{ (Auth::user()->preferences['language'] ?? '') == 'es' ? 'selected' : '' }}>Español</option>
                                        <option value="de" {{ (Auth::user()->preferences['language'] ?? '') == 'de' ? 'selected' : '' }}>Deutsch</option>
                                    </select>
                                </div>
                                
                                <div class="preference-item">
                                    <div>
                                        <strong>Fuseau horaire</strong>
                                        <p class="mb-0 small text-muted">Définissez votre fuseau horaire</p>
                                    </div>
                                    <select class="form-select-modern" name="timezone" id="timezoneSelect" style="width: auto;">
                                        <option value="Europe/Paris" {{ (Auth::user()->preferences['timezone'] ?? 'Europe/Paris') == 'Europe/Paris' ? 'selected' : '' }}>(GMT+1) Paris</option>
                                        <option value="Europe/London" {{ (Auth::user()->preferences['timezone'] ?? '') == 'Europe/London' ? 'selected' : '' }}>(GMT+0) Londres</option>
                                        <option value="America/New_York" {{ (Auth::user()->preferences['timezone'] ?? '') == 'America/New_York' ? 'selected' : '' }}>(GMT-5) New York</option>
                                        <option value="Asia/Tokyo" {{ (Auth::user()->preferences['timezone'] ?? '') == 'Asia/Tokyo' ? 'selected' : '' }}>(GMT+9) Tokyo</option>
                                    </select>
                                </div>
                            </div>
                            
                            <hr class="divider-modern">
                            
                            <div class="mb-4">
                                <h4 class="section-subtitle">Affichage</h4>
                                
                                <div class="preference-item">
                                    <div>
                                        <strong>Compact</strong>
                                        <p class="mb-0 small text-muted">Afficher les éléments de manière compacte</p>
                                    </div>
                                    <label class="switch-modern">
                                        <input type="checkbox" name="compact_mode" id="compactMode" value="1" {{ (Auth::user()->preferences['compact_mode'] ?? false) ? 'checked' : '' }}>
                                        <span class="slider-modern round"></span>
                                    </label>
                                </div>
                                
                                <div class="preference-item">
                                    <div>
                                        <strong>Animations</strong>
                                        <p class="mb-0 small text-muted">Activer les animations de l'interface</p>
                                    </div>
                                    <label class="switch-modern">
                                        <input type="checkbox" name="animations" id="animations" value="1" {{ (Auth::user()->preferences['animations'] ?? true) ? 'checked' : '' }}>
                                        <span class="slider-modern round"></span>
                                    </label>
                                </div>
                            </div>
                            
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Enregistrer les préférences
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Notifications Tab -->
            <div class="tab-pane" id="notifications" role="tabpanel">
                <div class="main-card-modern">
                    <div class="card-header-modern">
                        <h3 class="card-title-modern">Paramètres de notification</h3>
                    </div>
                    
                    <div class="card-body-modern">
                        <form id="notificationsForm">
                            @csrf
                            @method('PUT')
                            <div class="mb-4">
                                <h4 class="section-subtitle">Notifications par email</h4>
                                
                                <div class="preference-item">
                                    <div>
                                        <strong>Nouveaux projets</strong>
                                        <p class="mb-0 small text-muted">Recevoir une notification lors de la création d'un projet</p>
                                    </div>
                                    <label class="switch-modern">
                                        <input type="checkbox" name="email_new_projects" id="notifNewProjects" value="1" {{ (Auth::user()->notification_settings['email_new_projects'] ?? true) ? 'checked' : '' }}>
                                        <span class="slider-modern round"></span>
                                    </label>
                                </div>
                                
                                <div class="preference-item">
                                    <div>
                                        <strong>Mises à jour de tâches</strong>
                                        <p class="mb-0 small text-muted">Recevoir une notification lors des modifications de tâches</p>
                                    </div>
                                    <label class="switch-modern">
                                        <input type="checkbox" name="email_task_updates" id="notifTaskUpdates" value="1" {{ (Auth::user()->notification_settings['email_task_updates'] ?? true) ? 'checked' : '' }}>
                                        <span class="slider-modern round"></span>
                                    </label>
                                </div>
                                
                                <div class="preference-item">
                                    <div>
                                        <strong>Mentions</strong>
                                        <p class="mb-0 small text-muted">Recevoir une notification quand vous êtes mentionné</p>
                                    </div>
                                    <label class="switch-modern">
                                        <input type="checkbox" name="email_mentions" id="notifMentions" value="1" {{ (Auth::user()->notification_settings['email_mentions'] ?? true) ? 'checked' : '' }}>
                                        <span class="slider-modern round"></span>
                                    </label>
                                </div>
                                
                                <div class="preference-item">
                                    <div>
                                        <strong>Rapports hebdomadaires</strong>
                                        <p class="mb-0 small text-muted">Recevoir un résumé de vos activités chaque semaine</p>
                                    </div>
                                    <label class="switch-modern">
                                        <input type="checkbox" name="email_weekly_reports" id="notifWeeklyReports" value="1" {{ (Auth::user()->notification_settings['email_weekly_reports'] ?? false) ? 'checked' : '' }}>
                                        <span class="slider-modern round"></span>
                                    </label>
                                </div>
                            </div>
                            
                            <hr class="divider-modern">
                            
                            <div class="mb-4">
                                <h4 class="section-subtitle">Notifications dans l'application</h4>
                                
                                <div class="preference-item">
                                    <div>
                                        <strong>Son</strong>
                                        <p class="mb-0 small text-muted">Jouer un son lors des notifications</p>
                                    </div>
                                    <label class="switch-modern">
                                        <input type="checkbox" name="in_app_sound" id="notifSound" value="1" {{ (Auth::user()->notification_settings['in_app_sound'] ?? true) ? 'checked' : '' }}>
                                        <span class="slider-modern round"></span>
                                    </label>
                                </div>
                                
                                <div class="preference-item">
                                    <div>
                                        <strong>Badges</strong>
                                        <p class="mb-0 small text-muted">Afficher un badge pour les notifications non lues</p>
                                    </div>
                                    <label class="switch-modern">
                                        <input type="checkbox" name="in_app_badges" id="notifBadges" value="1" {{ (Auth::user()->notification_settings['in_app_badges'] ?? true) ? 'checked' : '' }}>
                                        <span class="slider-modern round"></span>
                                    </label>
                                </div>
                                
                                <div class="preference-item">
                                    <div>
                                        <strong>Bureau</strong>
                                        <p class="mb-0 small text-muted">Recevoir des notifications sur le bureau</p>
                                    </div>
                                    <label class="switch-modern">
                                        <input type="checkbox" name="in_app_desktop" id="notifDesktop" value="1" {{ (Auth::user()->notification_settings['in_app_desktop'] ?? false) ? 'checked' : '' }}>
                                        <span class="slider-modern round"></span>
                                    </label>
                                </div>
                            </div>
                            
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Enregistrer les préférences
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Activité Tab -->
            <div class="tab-pane" id="activity" role="tabpanel">
                <div class="main-card-modern">
                    <div class="card-header-modern">
                        <h3 class="card-title-modern">Historique des activités</h3>
                        <div class="search-container">
                            <i class="fas fa-search search-icon"></i>
                            <input type="text" class="search-input" id="activitySearch" placeholder="Filtrer les activités...">
                        </div>
                    </div>
                    
                    <div class="card-body-modern">
                        <div class="activity-timeline" id="activityTimeline">
                            @forelse($recentActivities ?? [] as $activity)
                            <div class="timeline-item">
                                <div class="timeline-icon" style="background: linear-gradient(135deg, var(--primary-color), #3a56e4);">
                                    <i class="fas fa-{{ $activity->action_icon }}"></i>
                                </div>
                                <div class="timeline-content">
                                    <div class="timeline-header">
                                        <strong>{{ $activity->formatted_action }}</strong>
                                        <span class="timeline-time">{{ $activity->created_at->diffForHumans() }}</span>
                                    </div>
                                    <p>{{ $activity->description }}</p>
                                    <small class="text-muted">IP: {{ $activity->ip_address }}</small>
                                </div>
                            </div>
                            @empty
                            <div class="text-center py-5">
                                <i class="fas fa-history fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Aucune activité récente</p>
                            </div>
                            @endforelse
                        </div>
                        
                        <div class="text-center mt-4">
                            <button class="btn btn-outline-primary" id="loadMoreActivities">
                                <i class="fas fa-sync-alt me-2"></i>Charger plus d'activités
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Floating Action Button -->
        <button class="fab-modern" id="quickActionBtn">
            <i class="fas fa-ellipsis-v"></i>
        </button>
    </main>
    
    <!-- Hidden file input for avatar upload -->
    <input type="file" id="avatarUpload" accept="image/*" style="display: none;">
    
    <!-- Toast Container for notifications -->
    <div class="toast-container position-fixed bottom-0 end-0 p-3"></div>
    

<style>
    /* Profile Header Styles */
    .profile-header-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 20px;
        margin-bottom: 30px;
        padding: 30px;
        color: white;
        position: relative;
        overflow: hidden;
    }
    
    .profile-header-card::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 500px;
        height: 500px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
    }
    
    .profile-cover {
        display: flex;
        align-items: center;
        gap: 30px;
        position: relative;
        z-index: 1;
    }
    
    .profile-avatar-wrapper {
        position: relative;
    }
    
    .profile-avatar {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        border: 4px solid white;
        overflow: hidden;
        position: relative;
    }
    
    .profile-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .avatar-edit-btn {
        position: absolute;
        bottom: 5px;
        right: 5px;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: white;
        border: none;
        color: var(--primary-color);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    }
    
    .avatar-edit-btn:hover {
        transform: scale(1.1);
        background: var(--primary-color);
        color: white;
    }
    
    .profile-info-wrapper {
        flex: 1;
    }
    
    .profile-name {
        font-size: 2em;
        font-weight: 600;
        margin-bottom: 5px;
    }
    
    .profile-role {
        font-size: 1.1em;
        opacity: 0.9;
        margin-bottom: 10px;
    }
    
    .profile-meta {
        display: flex;
        gap: 20px;
        font-size: 0.9em;
    }
    
    .profile-meta span {
        display: flex;
        align-items: center;
    }
    
    /* Tabs Styles */
    .profile-tabs-container {
        margin-bottom: 30px;
        border-bottom: 2px solid rgba(0,0,0,0.05);
    }
    
    .modern-tabs {
        display: flex;
        list-style: none;
        padding: 0;
        margin: 0;
        gap: 5px;
    }
    
    .tab-item {
        margin-bottom: -2px;
    }
    
    .tab-link {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 15px 25px;
        color: var(--text-muted);
        text-decoration: none;
        font-weight: 500;
        border-bottom: 2px solid transparent;
        transition: all 0.3s;
        cursor: pointer;
    }
    
    .tab-link i {
        font-size: 1.1em;
    }
    
    .tab-item.active .tab-link {
        color: var(--primary-color);
        border-bottom-color: var(--primary-color);
    }
    
    .tab-link:hover {
        color: var(--primary-color);
    }
    
    /* Profile Tab Content */
    .profile-tab-content {
        margin-top: 20px;
    }
    
    .tab-pane {
        display: none;
    }
    
    .tab-pane.active {
        display: block;
    }
    
    /* Section Subtitle */
    .section-subtitle {
        font-size: 1.2em;
        font-weight: 600;
        margin-bottom: 20px;
        color: var(--text-dark);
    }
    
    /* Password Input Wrapper */
    .password-input-wrapper {
        position: relative;
    }
    
    .password-toggle {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        color: var(--text-muted);
    }
    
    .password-toggle:hover {
        color: var(--primary-color);
    }
    
    /* Password Strength */
    .password-strength {
        display: flex;
        gap: 5px;
        align-items: center;
    }
    
    .strength-bar {
        height: 4px;
        flex: 1;
        background: #e0e0e0;
        border-radius: 2px;
    }
    
    .strength-bar.active {
        background: #ef476f;
    }
    
    .strength-text {
        margin-left: 10px;
        color: var(--text-muted);
    }
    
    /* Divider */
    .divider-modern {
        margin: 30px 0;
        border-color: rgba(0,0,0,0.05);
    }
    
    /* Switch Modern */
    .switch-modern {
        position: relative;
        display: inline-block;
        width: 50px;
        height: 24px;
    }
    
    .switch-modern input {
        opacity: 0;
        width: 0;
        height: 0;
    }
    
    .slider-modern {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        transition: .4s;
    }
    
    .slider-modern:before {
        position: absolute;
        content: "";
        height: 20px;
        width: 20px;
        left: 2px;
        bottom: 2px;
        background-color: white;
        transition: .4s;
    }
    
    input:checked + .slider-modern {
        background-color: var(--primary-color);
    }
    
    input:checked + .slider-modern:before {
        transform: translateX(26px);
    }
    
    .slider-modern.round {
        border-radius: 24px;
    }
    
    .slider-modern.round:before {
        border-radius: 50%;
    }
    
    /* Preference Item */
    .preference-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 15px 0;
        border-bottom: 1px solid rgba(0,0,0,0.05);
    }
    
    .preference-item:last-child {
        border-bottom: none;
    }
    
    /* Theme Selector */
    .theme-selector {
        display: flex;
        gap: 10px;
    }
    
    .theme-option {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 5px;
        padding: 10px 15px;
        border: 2px solid #e0e0e0;
        border-radius: 10px;
        cursor: pointer;
        transition: all 0.3s;
    }
    
    .theme-option i {
        font-size: 1.5em;
        color: var(--text-muted);
    }
    
    .theme-option span {
        font-size: 0.9em;
        color: var(--text-muted);
    }
    
    .theme-option.active {
        border-color: var(--primary-color);
        background: rgba(67, 97, 238, 0.05);
    }
    
    .theme-option.active i,
    .theme-option.active span {
        color: var(--primary-color);
    }
    
    /* Session Item */
    .session-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 15px;
        border: 1px solid rgba(0,0,0,0.05);
        border-radius: 10px;
        margin-bottom: 10px;
    }
    
    .session-device {
        display: flex;
        align-items: center;
    }
    
    .session-device i {
        font-size: 1.5em;
        color: var(--text-muted);
    }
    
    /* Timeline */
    .activity-timeline {
        position: relative;
        padding-left: 30px;
    }
    
    .activity-timeline::before {
        content: '';
        position: absolute;
        left: 15px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: linear-gradient(to bottom, var(--primary-color), #06b48a, #ffd166, #ef476f);
    }
    
    .timeline-item {
        position: relative;
        margin-bottom: 30px;
        display: flex;
        gap: 20px;
    }
    
    .timeline-icon {
        position: absolute;
        left: -38px;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        box-shadow: 0 3px 10px rgba(0,0,0,0.1);
    }
    
    .timeline-content {
        flex: 1;
        background: var(--bg-light);
        padding: 20px;
        border-radius: 15px;
        margin-left: 20px;
    }
    
    .timeline-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }
    
    .timeline-time {
        font-size: 0.9em;
        color: var(--text-muted);
    }
    
    /* Toast */
    .toast-container {
        z-index: 1100;
    }
    
    .toast-modern {
        background: white;
        border-radius: 10px;
        padding: 15px 20px;
        margin-bottom: 10px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        display: flex;
        align-items: center;
        gap: 10px;
        animation: slideIn 0.3s ease;
    }
    
    .toast-modern.success {
        border-left: 4px solid #06b48a;
    }
    
    .toast-modern.error {
        border-left: 4px solid #ef476f;
    }
    
    .toast-modern.warning {
        border-left: 4px solid #ffd166;
    }
    
    .toast-icon {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .toast-modern.success .toast-icon {
        background: #06b48a;
        color: white;
    }
    
    .toast-modern.error .toast-icon {
        background: #ef476f;
        color: white;
    }
    
    .toast-content {
        flex: 1;
    }
    
    .toast-title {
        font-weight: 600;
        margin-bottom: 2px;
    }
    
    .toast-message {
        font-size: 0.9em;
        color: var(--text-muted);
    }
    
    .toast-close {
        cursor: pointer;
        color: var(--text-muted);
    }
    
    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    /* Loading Spinner */
    .loading-spinner {
        display: inline-block;
        width: 20px;
        height: 20px;
        border: 3px solid rgba(255,255,255,.3);
        border-radius: 50%;
        border-top-color: #fff;
        animation: spin 1s ease-in-out infinite;
    }
    
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .profile-cover {
            flex-direction: column;
            text-align: center;
        }
        
        .profile-meta {
            flex-direction: column;
            gap: 10px;
        }
        
        .modern-tabs {
            flex-wrap: wrap;
        }
        
        .tab-link {
            padding: 10px 15px;
            font-size: 0.9em;
        }
        
        .tab-link span {
            display: none;
        }
        
        .tab-link i {
            font-size: 1.2em;
        }
        
        .theme-selector {
            flex-wrap: wrap;
        }
        
        .preference-item {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
        }
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // ==================== GESTION DES TABS ====================
    const tabItems = document.querySelectorAll('.tab-item');
    const tabPanes = document.querySelectorAll('.tab-pane');
    
    function activateTab(tabId) {
        // Désactiver tous les tabs
        tabItems.forEach(item => {
            item.classList.remove('active');
        });
        
        // Cacher tous les panes
        tabPanes.forEach(pane => {
            pane.classList.remove('active');
        });
        
        // Activer le tab cliqué
        const selectedTab = document.querySelector(`.tab-item[data-tab="${tabId}"]`);
        if (selectedTab) {
            selectedTab.classList.add('active');
        }
        
        // Afficher le pane correspondant
        const selectedPane = document.getElementById(tabId);
        if (selectedPane) {
            selectedPane.classList.add('active');
        }
        
        // Sauvegarder dans localStorage
        localStorage.setItem('activeProfileTab', tabId);
    }
    
    // Ajouter les événements de clic sur les tabs
    tabItems.forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            const tabId = this.dataset.tab;
            activateTab(tabId);
        });
    });
    
    // Restaurer le dernier tab actif depuis localStorage
    const savedTab = localStorage.getItem('activeProfileTab');
    if (savedTab && document.querySelector(`.tab-item[data-tab="${savedTab}"]`)) {
        activateTab(savedTab);
    }
    
    // ==================== CONFIGURATION ====================
    const API_BASE_URL = '/profile';
    const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]')?.content || document.querySelector('input[name="_token"]')?.value;
    
    // Éléments DOM
    const editBtn = document.getElementById('editProfileBtn');
    const saveBtn = document.getElementById('saveProfileBtn');
    const cancelEditBtn = document.getElementById('cancelEditBtn');
    const formInputs = document.querySelectorAll('#profileInfoForm .form-control-modern');
    const editActions = document.getElementById('editActions');
    const avatarEditBtn = document.getElementById('avatarEditBtn');
    const avatarUpload = document.getElementById('avatarUpload');
    const profileAvatar = document.getElementById('profileAvatar');
    const profileName = document.getElementById('profileName');
    const profileRole = document.getElementById('profileRole');
    
    // ==================== GESTION DU MODE ÉDITION ====================
    if (editBtn) {
        editBtn.addEventListener('click', function() {
            editBtn.style.display = 'none';
            saveBtn.style.display = 'inline-flex';
            if (editActions) editActions.style.display = 'block';
            
            formInputs.forEach(input => {
                input.removeAttribute('readonly');
                input.removeAttribute('disabled');
                input.classList.remove('is-invalid');
            });
            
            // Cacher les messages d'erreur
            document.querySelectorAll('.invalid-feedback').forEach(el => el.style.display = 'none');
        });
    }
    
    if (cancelEditBtn) {
        cancelEditBtn.addEventListener('click', function() {
            resetEditMode();
        });
    }
    
    function resetEditMode() {
        editBtn.style.display = 'inline-flex';
        saveBtn.style.display = 'none';
        if (editActions) editActions.style.display = 'none';
        
        formInputs.forEach(input => {
            input.setAttribute('readonly', true);
            input.setAttribute('disabled', true);
            input.classList.remove('is-invalid');
            
            // Restaurer les valeurs originales
            const originalValue = input.defaultValue;
            input.value = originalValue;
        });
        
        document.querySelectorAll('.invalid-feedback').forEach(el => el.style.display = 'none');
    }
    
    // ==================== SAUVEGARDE DU PROFIL ====================
    if (saveBtn) {
        saveBtn.addEventListener('click', async function() {
            const formData = new FormData(document.getElementById('profileInfoForm'));
            
            // Afficher un état de chargement
            const originalText = saveBtn.innerHTML;
            saveBtn.innerHTML = '<span class="loading-spinner me-2"></span>Enregistrement...';
            saveBtn.disabled = true;
            
            try {
                const response = await fetch(`${API_BASE_URL}/info`, {
                    method: 'PUT',
                    headers: {
                        'X-CSRF-TOKEN': CSRF_TOKEN,
                        'Accept': 'application/json',
                    },
                    body: formData
                });
                
                const data = await response.json();
                
                if (response.ok && data.success) {
                    // Mise à jour réussie
                    showToast('success', 'Succès', data.message || 'Profil mis à jour avec succès');
                    
                    // Mettre à jour les valeurs par défaut
                    formInputs.forEach(input => {
                        input.defaultValue = input.value;
                    });
                    
                    // Mettre à jour l'affichage
                    if (data.user) {
                        if (data.user.name) {
                            profileName.textContent = data.user.name;
                        }
                        if (data.user.position) {
                            profileRole.textContent = data.user.position;
                        }
                    }
                    
                    resetEditMode();
                } else {
                    // Erreur de validation
                    if (data.errors) {
                        Object.keys(data.errors).forEach(key => {
                            const input = document.querySelector(`[name="${key}"]`);
                            const errorDiv = document.getElementById(`${key}-error`);
                            if (input && errorDiv) {
                                input.classList.add('is-invalid');
                                errorDiv.textContent = data.errors[key][0];
                                errorDiv.style.display = 'block';
                            }
                        });
                        showToast('error', 'Erreur de validation', 'Veuillez corriger les erreurs');
                    } else {
                        showToast('error', 'Erreur', data.message || 'Une erreur est survenue');
                    }
                }
            } catch (error) {
                console.error('Error:', error);
                showToast('error', 'Erreur', 'Impossible de communiquer avec le serveur');
            } finally {
                saveBtn.innerHTML = originalText;
                saveBtn.disabled = false;
            }
        });
    }
    
    // ==================== UPLOAD D'AVATAR ====================
    if (avatarEditBtn) {
        avatarEditBtn.addEventListener('click', function() {
            avatarUpload.click();
        });
    }
    
    if (avatarUpload) {
        avatarUpload.addEventListener('change', async function(e) {
            const file = e.target.files[0];
            if (!file) return;
            
            // Vérifier le type et la taille
            if (!file.type.startsWith('image/')) {
                showToast('error', 'Erreur', 'Veuillez sélectionner une image');
                return;
            }
            
            if (file.size > 2 * 1024 * 1024) {
                showToast('error', 'Erreur', 'L\'image ne doit pas dépasser 2 Mo');
                return;
            }
            
            const formData = new FormData();
            formData.append('avatar', file);
            
            // Afficher un aperçu
            const reader = new FileReader();
            reader.onload = function(e) {
                profileAvatar.src = e.target.result;
            };
            reader.readAsDataURL(file);
            
            try {
                const response = await fetch(`${API_BASE_URL}/avatar`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': CSRF_TOKEN,
                    },
                    body: formData
                });
                
                const data = await response.json();
                
                if (response.ok && data.success) {
                    showToast('success', 'Succès', data.message);
                    if (data.avatar_url) {
                        profileAvatar.src = data.avatar_url;
                    }
                } else {
                    showToast('error', 'Erreur', data.message || 'Erreur lors de l\'upload');
                }
            } catch (error) {
                console.error('Error:', error);
                showToast('error', 'Erreur', 'Impossible de communiquer avec le serveur');
            }
        });
    }
    
    // ==================== GESTION DU MOT DE PASSE ====================
    const passwordForm = document.getElementById('passwordForm');
    if (passwordForm) {
        passwordForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const submitBtn = document.getElementById('changePasswordBtn');
            
            // Validation côté client
            const newPassword = formData.get('new_password');
            const confirmPassword = formData.get('new_password_confirmation');
            
            if (newPassword !== confirmPassword) {
                showToast('error', 'Erreur', 'Les mots de passe ne correspondent pas');
                return;
            }
            
            if (newPassword.length < 8) {
                showToast('error', 'Erreur', 'Le mot de passe doit contenir au moins 8 caractères');
                return;
            }
            
            // Afficher l'état de chargement
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<span class="loading-spinner me-2"></span>Modification...';
            submitBtn.disabled = true;
            
            try {
                const response = await fetch(`${API_BASE_URL}/password`, {
                    method: 'PUT',
                    headers: {
                        'X-CSRF-TOKEN': CSRF_TOKEN,
                        'Accept': 'application/json',
                    },
                    body: formData
                });
                
                const data = await response.json();
                
                if (response.ok && data.success) {
                    showToast('success', 'Succès', data.message);
                    passwordForm.reset();
                    
                    // Réinitialiser l'indicateur de force
                    document.querySelectorAll('.strength-bar').forEach(bar => {
                        bar.style.background = '#e0e0e0';
                    });
                    document.getElementById('strengthText').textContent = 'Faible';
                } else {
                    if (data.errors) {
                        Object.keys(data.errors).forEach(key => {
                            const errorDiv = document.getElementById(`${key}-error`);
                            if (errorDiv) {
                                errorDiv.textContent = data.errors[key][0];
                                errorDiv.style.display = 'block';
                            }
                        });
                        showToast('error', 'Erreur de validation', 'Veuillez corriger les erreurs');
                    } else {
                        showToast('error', 'Erreur', data.message || 'Une erreur est survenue');
                    }
                }
            } catch (error) {
                console.error('Error:', error);
                showToast('error', 'Erreur', 'Impossible de communiquer avec le serveur');
            } finally {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }
        });
    }
    
    // ==================== INDICATEUR DE FORCE DU MOT DE PASSE ====================
    const passwordInput = document.getElementById('new_password');
    if (passwordInput) {
        passwordInput.addEventListener('input', function() {
            const strengthBars = document.querySelectorAll('.strength-bar');
            const strengthText = document.getElementById('strengthText');
            const password = this.value;
            
            let strength = 0;
            
            if (password.length >= 8) strength++;
            if (password.match(/[a-z]/)) strength++;
            if (password.match(/[A-Z]/)) strength++;
            if (password.match(/[0-9]/)) strength++;
            if (password.match(/[^a-zA-Z0-9]/)) strength++;
            
            const colors = ['#e0e0e0', '#ef476f', '#ffd166', '#06b48a', '#06b48a', '#06b48a'];
            const texts = ['Très faible', 'Faible', 'Moyen', 'Fort', 'Très fort', 'Excellent'];
            
            strengthBars.forEach((bar, index) => {
                if (index < strength) {
                    bar.style.background = colors[strength];
                } else {
                    bar.style.background = '#e0e0e0';
                }
            });
            
            strengthText.textContent = texts[strength];
        });
    }
    
    // ==================== VISIBILITÉ DU MOT DE PASSE ====================
    document.querySelectorAll('.password-toggle').forEach(toggle => {
        toggle.addEventListener('click', function() {
            const input = this.previousElementSibling;
            const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
            input.setAttribute('type', type);
            const icon = this.querySelector('i');
            icon.classList.toggle('fa-eye');
            icon.classList.toggle('fa-eye-slash');
        });
    });
    
    // ==================== GESTION DES PRÉFÉRENCES ====================
    const preferencesForm = document.getElementById('preferencesForm');
    if (preferencesForm) {
        // Sélection du thème
        document.querySelectorAll('.theme-option').forEach(option => {
            option.addEventListener('click', function() {
                document.querySelectorAll('.theme-option').forEach(opt => {
                    opt.classList.remove('active');
                });
                this.classList.add('active');
                document.getElementById('themeInput').value = this.dataset.theme;
            });
        });
        
        preferencesForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const submitBtn = this.querySelector('button[type="submit"]');
            
            // Afficher l'état de chargement
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<span class="loading-spinner me-2"></span>Enregistrement...';
            submitBtn.disabled = true;
            
            try {
                const response = await fetch(`${API_BASE_URL}/preferences`, {
                    method: 'PUT',
                    headers: {
                        'X-CSRF-TOKEN': CSRF_TOKEN,
                        'Accept': 'application/json',
                    },
                    body: formData
                });
                
                const data = await response.json();
                
                if (response.ok && data.success) {
                    showToast('success', 'Succès', data.message);
                } else {
                    showToast('error', 'Erreur', data.message || 'Une erreur est survenue');
                }
            } catch (error) {
                console.error('Error:', error);
                showToast('error', 'Erreur', 'Impossible de communiquer avec le serveur');
            } finally {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }
        });
    }
    
    // ==================== GESTION DES NOTIFICATIONS ====================
    const notificationsForm = document.getElementById('notificationsForm');
    if (notificationsForm) {
        notificationsForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const submitBtn = this.querySelector('button[type="submit"]');
            
            // Afficher l'état de chargement
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<span class="loading-spinner me-2"></span>Enregistrement...';
            submitBtn.disabled = true;
            
            try {
                const response = await fetch(`${API_BASE_URL}/notifications`, {
                    method: 'PUT',
                    headers: {
                        'X-CSRF-TOKEN': CSRF_TOKEN,
                        'Accept': 'application/json',
                    },
                    body: formData
                });
                
                const data = await response.json();
                
                if (response.ok && data.success) {
                    showToast('success', 'Succès', data.message);
                } else {
                    showToast('error', 'Erreur', data.message || 'Une erreur est survenue');
                }
            } catch (error) {
                console.error('Error:', error);
                showToast('error', 'Erreur', 'Impossible de communiquer avec le serveur');
            } finally {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }
        });
    }
    
    // ==================== GESTION 2FA ====================
    const twoFactorToggle = document.getElementById('twoFactorToggle');
    const twoFactorSection = document.getElementById('twoFactorSection');
    
    if (twoFactorToggle) {
        twoFactorToggle.addEventListener('change', async function() {
            if (this.checked) {
                // Activer 2FA
                twoFactorSection.style.display = 'block';
                
                try {
                    const response = await fetch(`${API_BASE_URL}/2fa/toggle`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': CSRF_TOKEN,
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({ enabled: true })
                    });
                    
                    const data = await response.json();
                    
                    if (response.ok && data.success && data.secret) {
                        // Générer QR code
                        new QRCode(document.getElementById('qrcode'), {
                            text: `otpauth://totp/{{ config('app.name') }}:{{ Auth::user()->email }}?secret=${data.secret}&issuer={{ config('app.name') }}`,
                            width: 200,
                            height: 200
                        });
                    }
                } catch (error) {
                    console.error('Error:', error);
                }
            } else {
                // Désactiver 2FA
                if (confirm('Êtes-vous sûr de vouloir désactiver l\'authentification à deux facteurs ?')) {
                    twoFactorSection.style.display = 'none';
                    
                    try {
                        const response = await fetch(`${API_BASE_URL}/2fa/toggle`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': CSRF_TOKEN,
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify({ enabled: false })
                        });
                        
                        const data = await response.json();
                        
                        if (response.ok && data.success) {
                            showToast('success', 'Succès', data.message);
                        }
                    } catch (error) {
                        console.error('Error:', error);
                    }
                } else {
                    this.checked = true;
                }
            }
        });
    }
    
    // ==================== VÉRIFICATION 2FA ====================
    const verifyTwoFactorBtn = document.getElementById('verifyTwoFactorBtn');
    if (verifyTwoFactorBtn) {
        verifyTwoFactorBtn.addEventListener('click', async function() {
            const code = document.getElementById('twoFactorCode').value;
            
            if (!code || code.length !== 6) {
                showToast('error', 'Erreur', 'Veuillez entrer un code valide à 6 chiffres');
                return;
            }
            
            try {
                const response = await fetch(`${API_BASE_URL}/2fa/verify`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': CSRF_TOKEN,
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ code })
                });
                
                const data = await response.json();
                
                if (response.ok && data.success) {
                    showToast('success', 'Succès', '2FA activée avec succès');
                } else {
                    showToast('error', 'Erreur', data.message || 'Code invalide');
                }
            } catch (error) {
                console.error('Error:', error);
            }
        });
    }
    
    // ==================== RÉVOCATION DE SESSION ====================
    document.querySelectorAll('.revoke-session').forEach(btn => {
        btn.addEventListener('click', async function() {
            if (!confirm('Êtes-vous sûr de vouloir révoquer cette session ?')) {
                return;
            }
            
            const sessionId = this.dataset.sessionId;
            
            try {
                const response = await fetch(`${API_BASE_URL}/sessions/${sessionId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': CSRF_TOKEN,
                        'Accept': 'application/json',
                    },
                });
                
                const data = await response.json();
                
                if (response.ok && data.success) {
                    showToast('success', 'Succès', data.message);
                    this.closest('.session-item').remove();
                } else {
                    showToast('error', 'Erreur', data.message || 'Erreur lors de la révocation');
                }
            } catch (error) {
                console.error('Error:', error);
                showToast('error', 'Erreur', 'Impossible de communiquer avec le serveur');
            }
        });
    });
    
    // ==================== FILTRE DES ACTIVITÉS ====================
    const activitySearch = document.getElementById('activitySearch');
    if (activitySearch) {
        activitySearch.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const items = document.querySelectorAll('.timeline-item');
            
            items.forEach(item => {
                const text = item.textContent.toLowerCase();
                if (text.includes(searchTerm)) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    }
    
    // ==================== CHARGER PLUS D'ACTIVITÉS ====================
    const loadMoreBtn = document.getElementById('loadMoreActivities');
    if (loadMoreBtn) {
        let page = 1;
        
        loadMoreBtn.addEventListener('click', async function() {
            page++;
            
            const originalText = this.innerHTML;
            this.innerHTML = '<span class="loading-spinner me-2"></span>Chargement...';
            this.disabled = true;
            
            try {
                const response = await fetch(`${API_BASE_URL}/activities?page=${page}`);
                const data = await response.json();
                
                if (response.ok && data.activities && data.activities.data.length > 0) {
                    // Ajouter les nouvelles activités
                    const timeline = document.getElementById('activityTimeline');
                    
                    data.activities.data.forEach(activity => {
                        const html = `
                            <div class="timeline-item">
                                <div class="timeline-icon" style="background: linear-gradient(135deg, var(--primary-color), #3a56e4);">
                                    <i class="fas fa-${activity.action_icon || 'history'}"></i>
                                </div>
                                <div class="timeline-content">
                                    <div class="timeline-header">
                                        <strong>${activity.formatted_action || activity.action}</strong>
                                        <span class="timeline-time">${activity.created_at_diff || activity.created_at}</span>
                                    </div>
                                    <p>${activity.description || ''}</p>
                                    <small class="text-muted">IP: ${activity.ip_address || ''}</small>
                                </div>
                            </div>
                        `;
                        timeline.insertAdjacentHTML('beforeend', html);
                    });
                    
                    if (!data.activities.next_page_url) {
                        this.style.display = 'none';
                    }
                } else {
                    this.style.display = 'none';
                }
            } catch (error) {
                console.error('Error:', error);
            } finally {
                this.innerHTML = originalText;
                this.disabled = false;
            }
        });
    }
    
    // ==================== SYSTÈME DE TOAST ====================
    function showToast(type, title, message) {
        const container = document.querySelector('.toast-container');
        const toastId = 'toast-' + Date.now();
        
        const icons = {
            success: 'check-circle',
            error: 'exclamation-circle',
            warning: 'exclamation-triangle'
        };
        
        const html = `
            <div class="toast-modern ${type}" id="${toastId}">
                <div class="toast-icon">
                    <i class="fas fa-${icons[type]}"></i>
                </div>
                <div class="toast-content">
                    <div class="toast-title">${title}</div>
                    <div class="toast-message">${message}</div>
                </div>
                <div class="toast-close" onclick="document.getElementById('${toastId}').remove()">
                    <i class="fas fa-times"></i>
                </div>
            </div>
        `;
        
        container.insertAdjacentHTML('beforeend', html);
        
        // Auto-supprimer après 5 secondes
        setTimeout(() => {
            const toast = document.getElementById(toastId);
            if (toast) {
                toast.remove();
            }
        }, 5000);
    }
});
</script>
@endsection