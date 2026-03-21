@extends('layouts.app')

@section('content')
    
    <main class="dashboard-content">
        <!-- Page Header avec design amélioré -->
        <div class="page-header-modern">
            <div class="page-header-content">
                <div class="page-header-left">
                    <div class="page-header-icon">
                        <i class="fas fa-envelope-open-text"></i>
                    </div>
                    <div>
                        <h1 class="page-title-modern">Créer une campagne</h1>
                        <p class="page-subtitle-modern">Créez et personnalisez votre campagne email marketing</p>
                    </div>
                </div>
                <div class="page-header-right">
                    <a href="{{ route('mail-campaigns.index') }}" class="btn-back">
                        <i class="fas fa-arrow-left me-2"></i>Retour aux campagnes
                    </a>
                </div>
            </div>
        </div>
        
        <form action="{{ route('mail-campaigns.store') }}" method="POST" id="campaignForm">
            @csrf
            
            <div class="row g-4">
                <!-- Colonne gauche - 8 colonnes -->
                <div class="col-lg-8">
                    <!-- Carte principale - Informations de la campagne -->
                    <div class="card-modern card-primary">
                        <div class="card-header-modern">
                            <div class="card-header-icon">
                                <i class="fas fa-info-circle"></i>
                            </div>
                            <h3 class="card-title-modern">Informations de la campagne</h3>
                            <span class="card-badge">Étape 1/3</span>
                        </div>
                        <div class="card-body-modern">
                            <!-- Champ Nom de la campagne -->
                            <div class="form-group-modern">
                                <label class="form-label-modern required">
                                    <i class="fas fa-tag"></i>
                                    Nom de la campagne
                                </label>
                                <div class="input-group-modern">
                                    <input type="text" 
                                           name="nom" 
                                           class="form-control-modern @error('nom') is-invalid @enderror" 
                                           value="{{ old('nom') }}" 
                                           placeholder="ex: Newsletter Mars 2025, Offre spéciale été..."
                                           required>
                                    <span class="input-icon"><i class="fas fa-pen"></i></span>
                                </div>
                                <small class="form-text-modern">Un nom interne pour identifier facilement votre campagne</small>
                                @error('nom') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            
                            <!-- Champ Sujet de l'email -->
                            <div class="form-group-modern">
                                <label class="form-label-modern required">
                                    <i class="fas fa-envelope"></i>
                                    Sujet de l'email
                                </label>
                                <div class="input-group-modern">
                                    <input type="text" 
                                           name="sujet" 
                                           class="form-control-modern @error('sujet') is-invalid @enderror" 
                                           value="{{ old('sujet') }}" 
                                           id="emailSubject" 
                                           placeholder="Objet de votre email..."
                                           required>
                                    <span class="input-icon"><i class="fas fa-eye"></i></span>
                                </div>
                                <div class="subject-preview" id="subjectPreview">
                                    <i class="fas fa-eye me-1"></i>
                                    <span>Aperçu: </span>
                                    <strong id="subjectPreviewText">{{ old('sujet') ?: 'Sujet de votre email' }}</strong>
                                </div>
                                @error('sujet') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            
                            <!-- Champ Contenu de l'email -->
                            <div class="form-group-modern">
                                <label class="form-label-modern required">
                                    <i class="fas fa-edit"></i>
                                    Contenu de l'email
                                </label>
                                
                                <!-- Barre d'outils enrichie -->
                                <div class="editor-toolbar-modern">
                                    <div class="toolbar-group">
                                        <span class="toolbar-label">Variables :</span>
                                        <button type="button" class="toolbar-btn" onclick="insertTag('@{{nom}}')" title="Nom de l'abonné">
                                            <i class="fas fa-user"></i> Nom
                                        </button>
                                        <button type="button" class="toolbar-btn" onclick="insertTag('@{{prenom}}')" title="Prénom de l'abonné">
                                            <i class="fas fa-user"></i> Prénom
                                        </button>
                                        <button type="button" class="toolbar-btn" onclick="insertTag('@{{email}}')" title="Email de l'abonné">
                                            <i class="fas fa-envelope"></i> Email
                                        </button>
                                        <button type="button" class="toolbar-btn" onclick="insertTag('@{{etablissement}}')" title="Nom de l'établissement">
                                            <i class="fas fa-building"></i> Établissement
                                        </button>
                                    </div>
                                    <div class="toolbar-divider"></div>
                                    <div class="toolbar-group">
                                        <span class="toolbar-label">Formatage :</span>
                                        <button type="button" class="toolbar-btn" onclick="formatText('bold')" title="Gras (Ctrl+B)">
                                            <i class="fas fa-bold"></i>
                                        </button>
                                        <button type="button" class="toolbar-btn" onclick="formatText('italic')" title="Italique (Ctrl+I)">
                                            <i class="fas fa-italic"></i>
                                        </button>
                                        <button type="button" class="toolbar-btn" onclick="formatText('underline')" title="Souligné (Ctrl+U)">
                                            <i class="fas fa-underline"></i>
                                        </button>
                                    </div>
                                    <div class="toolbar-divider"></div>
                                    <div class="toolbar-group">
                                        <span class="toolbar-label">Raccourcis :</span>
                                        <button type="button" class="toolbar-btn" onclick="insertTag('@{{unsubscribe_url}}')" title="Lien de désabonnement">
                                            <i class="fas fa-ban"></i> Désabonnement
                                        </button>
                                    </div>
                                </div>
                                
                                <textarea name="contenu" 
                                          id="emailContent" 
                                          rows="14" 
                                          class="form-control-modern editor-textarea @error('contenu') is-invalid @enderror" 
                                          placeholder="Rédigez le contenu de votre email... Utilisez les variables pour personnaliser vos messages."
                                          required>{{ old('contenu') }}</textarea>
                                
                                <div class="editor-footer">
                                    <div class="char-counter" id="charCounter">
                                        <i class="fas fa-chart-simple"></i>
                                        <span id="charCount">0</span> caractères
                                        <span class="separator-dot">•</span>
                                        <span id="wordCount">0</span> mots
                                    </div>
                                    <div class="editor-tips">
                                        <i class="fas fa-keyboard"></i>
                                        <span>Astuce: Utilisez F2-F5 pour insérer rapidement les variables</span>
                                    </div>
                                </div>
                                @error('contenu') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Carte Options avancées -->
                    <div class="card-modern card-secondary">
                        <div class="card-header-modern">
                            <div class="card-header-icon">
                                <i class="fas fa-sliders-h"></i>
                            </div>
                            <h3 class="card-title-modern">Options avancées</h3>
                            <span class="card-badge">Étape 2/3</span>
                        </div>
                        <div class="card-body-modern">
                            <div class="row g-3">
                                <!-- Planification -->
                                <div class="col-md-6">
                                    <div class="option-card">
                                        <div class="option-header">
                                            <i class="fas fa-calendar-alt option-icon"></i>
                                            <span class="option-title">Planification</span>
                                        </div>
                                        <div class="form-check form-switch-modern">
    <input type="checkbox" 
           class="form-check-input" 
           id="scheduleCheck" 
           name="schedule" 
           value="1">
    <label class="form-check-label" for="scheduleCheck">
        Planifier l'envoi
    </label>
</div>
                                        <div id="scheduleFields" style="display: none;" class="mt-3">
                                            <div class="datetime-picker">
                                                <i class="fas fa-clock"></i>
                                                <input type="datetime-local" name="scheduled_at" class="form-control-modern" 
                                                       min="{{ now()->format('Y-m-d\TH:i') }}">
                                            </div>
                                            <small class="text-muted">Fuseau horaire: {{ now()->timezoneName }}</small>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Segmentation -->
                                <div class="col-md-6">
                                    <div class="option-card">
                                        <div class="option-header">
                                            <i class="fas fa-users option-icon"></i>
                                            <span class="option-title">Segmentation</span>
                                        </div>
                                        <select name="segment" class="form-select-modern" id="segmentSelect">
                                            <option value="all">📬 Tous les abonnés</option>
                                            <option value="active">✅ Abonnés actifs uniquement</option>
                                            <option value="inactive">⏰ Abonnés inactifs</option>
                                            <option value="by_etablissement">🏢 Par établissement</option>
                                        </select>
                                        <div id="etablissementField" style="display: none;" class="mt-3">
                                            <select name="etablissement_id" class="form-select-modern">
                                                <option value="">Sélectionner un établissement...</option>
                                                @foreach($etablissements ?? [] as $etab)
                                                    <option value="{{ $etab->id }}">{{ $etab->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="segmentation-stats mt-2" id="segmentationStats">
                                            <div class="stat-badge">
                                                <i class="fas fa-users"></i>
                                                <span>{{ $totalSubscribers ?? 0 }}</span> abonnés au total
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Options supplémentaires -->
                            <div class="row mt-3">
                                <div class="col-12">
                                    <div class="additional-options">
                                        <div class="option-toggle" onclick="toggleAdvancedOptions()">
                                            <i class="fas fa-chevron-down"></i>
                                            <span>Options supplémentaires</span>
                                        </div>
                                        <div class="advanced-options" id="advancedOptions" style="display: none;">
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input" id="trackOpens" name="track_opens" checked>
                                                        <label class="form-check-label" for="trackOpens">
                                                            <i class="fas fa-eye"></i> Suivi des ouvertures
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input" id="trackClicks" name="track_clicks" checked>
                                                        <label class="form-check-label" for="trackClicks">
                                                            <i class="fas fa-mouse-pointer"></i> Suivi des clics
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input" id="sendCopy" name="send_copy">
                                                        <label class="form-check-label" for="sendCopy">
                                                            <i class="fas fa-copy"></i> Recevoir une copie
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input" id="priority" name="priority">
                                                        <label class="form-check-label" for="priority">
                                                            <i class="fas fa-bolt"></i> Priorité haute
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Colonne droite - 4 colonnes -->
                <div class="col-lg-4">
                    <!-- Carte Thème visuel -->
                    <div class="card-modern card-theme">
                        <div class="card-header-modern">
                            <div class="card-header-icon">
                                <i class="fas fa-palette"></i>
                            </div>
                            <h3 class="card-title-modern">Thème visuel</h3>
                            <span class="card-badge">Étape 3/3</span>
                        </div>
                        <div class="card-body-modern">
                            <div class="theme-grid" id="themeSelector">
                                @php
                                    $themes = [
                                        'modern' => [
                                            'name' => 'Moderne', 
                                            'icon' => 'fas fa-chart-line', 
                                            'color1' => '#667eea', 
                                            'color2' => '#764ba2',
                                            'description' => 'Design épuré et professionnel'
                                        ],
                                        'elegant' => [
                                            'name' => 'Élégant', 
                                            'icon' => 'fas fa-feather-alt', 
                                            'color1' => '#8b6b4d', 
                                            'color2' => '#c7a17b',
                                            'description' => 'Style raffiné et sophistiqué'
                                        ],
                                        'dynamic' => [
                                            'name' => 'Dynamique', 
                                            'icon' => 'fas fa-bolt', 
                                            'color1' => '#ff6b6b', 
                                            'color2' => '#4ecdc4',
                                            'description' => 'Moderne et impactant'
                                        ],
                                    ];
                                @endphp
                                @foreach($themes as $key => $theme)
                                    <div class="theme-card" data-theme="{{ $key }}" onclick="selectTheme('{{ $key }}')">
                                        <div class="theme-preview" style="background: linear-gradient(135deg, {{ $theme['color1'] }}, {{ $theme['color2'] }});">
                                            <i class="{{ $theme['icon'] }}"></i>
                                        </div>
                                        <div class="theme-info">
                                            <div class="theme-name">{{ $theme['name'] }}</div>
                                            <div class="theme-description">{{ $theme['description'] }}</div>
                                        </div>
                                        <div class="theme-radio">
                                            <input type="radio" name="theme" value="{{ $key }}" {{ $key === 'modern' ? 'checked' : '' }}>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    
                    <!-- Carte Prévisualisation en direct -->
                    <div class="card-modern card-preview">
                        <div class="card-header-modern">
                            <div class="card-header-icon">
                                <i class="fas fa-desktop"></i>
                            </div>
                            <h3 class="card-title-modern">Prévisualisation en direct</h3>
                            <button type="button" class="btn-refresh" onclick="refreshPreview()" title="Rafraîchir">
                                <i class="fas fa-sync-alt"></i>
                            </button>
                        </div>
                        <div class="card-body-modern p-0">
                            <div class="preview-container">
                                <div class="preview-toolbar">
                                    <div class="preview-device-selector">
                                        <button type="button" class="device-btn active" onclick="setPreviewDevice('desktop')" title="Ordinateur">
                                            <i class="fas fa-desktop"></i>
                                        </button>
                                        <button type="button" class="device-btn" onclick="setPreviewDevice('tablet')" title="Tablette">
                                            <i class="fas fa-tablet-alt"></i>
                                        </button>
                                        <button type="button" class="device-btn" onclick="setPreviewDevice('mobile')" title="Mobile">
                                            <i class="fas fa-mobile-alt"></i>
                                        </button>
                                    </div>
                                    <div class="preview-status" id="previewStatus">
                                        <i class="fas fa-eye"></i>
                                        <span>Prévisualisation</span>
                                    </div>
                                </div>
                                <div class="preview-frame-container">
                                    <iframe id="previewFrame" class="preview-frame" title="Prévisualisation de l'email"></iframe>
                                    <div class="preview-overlay" id="previewOverlay" style="display: none;">
                                        <div class="preview-loader">
                                            <div class="spinner"></div>
                                            <p>Génération de la prévisualisation...</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Carte Statistiques rapides -->
                    <div class="card-modern card-stats">
                        <div class="card-header-modern">
                            <div class="card-header-icon">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <h3 class="card-title-modern">Statistiques</h3>
                        </div>
                        <div class="card-body-modern">
                            <div class="stats-mini-grid">
                                <div class="stat-mini-item">
                                    <div class="stat-mini-icon"><i class="fas fa-users"></i></div>
                                    <div class="stat-mini-info">
                                        <span class="stat-mini-value">{{ $totalSubscribers ?? 0 }}</span>
                                        <span class="stat-mini-label">Abonnés</span>
                                    </div>
                                </div>
                                <div class="stat-mini-item">
                                    <div class="stat-mini-icon"><i class="fas fa-check-circle"></i></div>
                                    <div class="stat-mini-info">
                                        <span class="stat-mini-value">{{ $activeSubscribers ?? 0 }}</span>
                                        <span class="stat-mini-label">Actifs</span>
                                    </div>
                                </div>
                                <div class="stat-mini-item">
                                    <div class="stat-mini-icon"><i class="fas fa-building"></i></div>
                                    <div class="stat-mini-info">
                                        <span class="stat-mini-value">{{ $etablissementsCount ?? 0 }}</span>
                                        <span class="stat-mini-label">Établissements</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Actions du formulaire -->
            <div class="form-actions-modern">
                <div class="actions-left">
                    <button type="button" class="btn-clear" onclick="clearForm()">
                        <i class="fas fa-eraser"></i> Tout effacer
                    </button>
                </div>
                <div class="actions-right">
                    <button type="submit" name="action" value="draft" class="btn-draft">
                        <i class="fas fa-save"></i>
                        <span>Enregistrer comme brouillon</span>
                    </button>
                    <button type="submit" name="action" value="schedule" class="btn-schedule" id="scheduleBtn">
                        <i class="fas fa-clock"></i>
                        <span>Planifier l'envoi</span>
                    </button>
                    <button type="submit" name="action" value="send" class="btn-send" id="sendBtn">
                        <i class="fas fa-paper-plane"></i>
                        <span>Envoyer maintenant</span>
                    </button>
                </div>
            </div>
        </form>
    </main>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // ============================================================
        // CONFIGURATION
        // ============================================================
        let currentTheme = 'modern';
        let currentDevice = 'desktop';
        let previewTimeout = null;
        let isPreviewLoading = false;
        
        const themes = {
            modern: { name: 'Moderne', color1: '#667eea', color2: '#764ba2' },
            elegant: { name: 'Élégant', color1: '#8b6b4d', color2: '#c7a17b' },
            dynamic: { name: 'Dynamique', color1: '#ff6b6b', color2: '#4ecdc4' }
        };
        
        // ============================================================
        // INITIALISATION
        // ============================================================
        $(document).ready(function() {
            // Toggle schedule fields
            $('#scheduleCheck').change(function() {
                $('#scheduleFields').slideToggle(300);
                updatePreview();
            });
            
            // Toggle etablissement field
            $('#segmentSelect').change(function() {
                if (this.value === 'by_etablissement') {
                    $('#etablissementField').slideDown(300);
                } else {
                    $('#etablissementField').slideUp(300);
                }
                updateSegmentationStats();
                updatePreview();
            });
            
            // Initial preview
            setTimeout(() => {
                refreshPreview();
            }, 500);
            
            // Auto-refresh on content change
            $('#emailSubject, #emailContent').on('input', function() {
                clearTimeout(previewTimeout);
                previewTimeout = setTimeout(refreshPreview, 800);
                updateCharacterCount();
                updateSubjectPreview();
            });
            
            // Keyboard shortcuts
            $(document).on('keydown', function(e) {
                if (e.ctrlKey && e.key === 's') {
                    e.preventDefault();
                    $('#campaignForm').submit();
                }
                if (e.ctrlKey && e.key === 'p') {
                    e.preventDefault();
                    refreshPreview();
                }
                if (e.ctrlKey && e.key === 'b') {
                    e.preventDefault();
                    formatText('bold');
                }
                if (e.ctrlKey && e.key === 'i') {
                    e.preventDefault();
                    formatText('italic');
                }
                if (e.ctrlKey && e.key === 'u') {
                    e.preventDefault();
                    formatText('underline');
                }
            });
            
            updateCharacterCount();
            updateSubjectPreview();
            updateSegmentationStats();
        });
        
        // ============================================================
        // FONCTIONS UI
        // ============================================================
        function selectTheme(theme) {
            if (!themes[theme]) return;
            
            currentTheme = theme;
            
            $('.theme-card').removeClass('active');
            $(`.theme-card[data-theme="${theme}"]`).addClass('active');
            $(`input[name="theme"][value="${theme}"]`).prop('checked', true);
            
            $('#previewFrame').css('opacity', '0.5');
            refreshPreview();
            showNotification(`Thème "${themes[theme].name}" sélectionné`, 'info');
        }
        
        function updateSubjectPreview() {
            const subject = $('#emailSubject').val() || 'Sujet de votre email';
            $('#subjectPreviewText').text(subject);
        }
        
        function updateCharacterCount() {
            const content = $('#emailContent').val() || '';
            const charCount = content.length;
            const wordCount = content.trim().split(/\s+/).filter(w => w.length > 0).length;
            
            $('#charCount').text(charCount.toLocaleString());
            $('#wordCount').text(wordCount.toLocaleString());
            
            const counter = $('#charCounter');
            if (charCount > 5000) {
                counter.addClass('warning');
            } else if (charCount > 2000) {
                counter.addClass('caution');
            } else {
                counter.removeClass('warning caution');
            }
        }
        
        function updateSegmentationStats() {
            const segment = $('#segmentSelect').val();
            const statsDiv = $('#segmentationStats');
            
            let html = '';
            if (segment === 'all') {
                html = '<div class="stat-badge"><i class="fas fa-users"></i><span>{{ $totalSubscribers ?? 0 }}</span> abonnés au total</div>';
            } else if (segment === 'active') {
                html = '<div class="stat-badge success"><i class="fas fa-check-circle"></i><span>{{ $activeSubscribers ?? 0 }}</span> abonnés actifs</div>';
            } else if (segment === 'inactive') {
                const inactive = ({{ $totalSubscribers ?? 0 }} - {{ $activeSubscribers ?? 0 }});
                html = '<div class="stat-badge warning"><i class="fas fa-clock"></i><span>' + inactive + '</span> abonnés inactifs</div>';
            } else if (segment === 'by_etablissement') {
                html = '<div class="stat-badge info"><i class="fas fa-building"></i><span>Filtrage par établissement</span></div>';
            }
            statsDiv.html(html);
        }
        
        function toggleAdvancedOptions() {
            $('#advancedOptions').slideToggle(300);
            $('.option-toggle i').toggleClass('fa-chevron-down fa-chevron-up');
        }
        
        function clearForm() {
            if (confirm('Voulez-vous vraiment effacer tous les champs ?')) {
                $('#emailSubject').val('');
                $('#emailContent').val('');
                updateCharacterCount();
                updateSubjectPreview();
                refreshPreview();
                showNotification('Formulaire réinitialisé', 'info');
            }
        }
        
        // ============================================================
        // PRÉVISUALISATION
        // ============================================================
        function refreshPreview() {
            if (isPreviewLoading) return;
            
            const subject = $('#emailSubject').val() || 'Sujet de l\'email';
            let content = $('#emailContent').val() || '<p>Contenu de votre email...</p>';
            
            if (!content.trim()) {
                content = '<p>Commencez à rédiger votre email...</p>';
            }
            
            const previewData = {
                subject: subject,
                content: content,
                prenom: 'Cher',
                nom: 'abonné',
                tracking: {
                    open_tracker: '#',
                    unsubscribe_url: '#',
                    click_tracker: '#'
                },
                ctaUrl: '#',
                ctaText: 'Découvrir nos offres',
                features: [
                    { icon: '✨', title: 'Qualité premium', description: 'Des services de haute qualité' },
                    { icon: '🚀', title: 'Livraison rapide', description: 'Expédition sous 24h' },
                    { icon: '💡', title: 'Support 24/7', description: 'Assistance à tout moment' }
                ],
                stats: [
                    { icon: '📧', value: '10k+', label: 'Emails envoyés' },
                    { icon: '👁️', value: '45%', label: "Taux d'ouverture" },
                    { icon: '🖱️', value: '12%', label: 'Taux de clic' }
                ],
                highlights: [
                    { icon: '✓', title: 'Satisfaction garantie', description: '100% de clients satisfaits' },
                    { icon: '✓', title: 'Paiement sécurisé', description: 'Transactions protégées' }
                ],
                socialLinks: [
                    { platform: 'facebook', url: '#' },
                    { platform: 'twitter', url: '#' },
                    { platform: 'linkedin', url: '#' }
                ]
            };
            
            showPreviewLoading(true);
            isPreviewLoading = true;
            
            $.ajax({
                url: '{{ route("mail-campaigns.preview-theme") }}',
                type: 'POST',
                data: {
                    theme: currentTheme,
                    data: previewData,
                    _token: '{{ csrf_token() }}'
                },
                timeout: 10000,
                success: function(html) {
                    updatePreviewFrame(html);
                },
                error: function(xhr) {
                    let errorMessage = 'Erreur lors du chargement de la prévisualisation';
                    if (xhr.status === 404) {
                        errorMessage = 'Route de prévisualisation introuvable';
                    } else if (xhr.status === 419) {
                        errorMessage = 'Session expirée. Veuillez rafraîchir la page.';
                    }
                    showNotification(errorMessage, 'danger');
                    showPreviewError(errorMessage);
                },
                complete: function() {
                    isPreviewLoading = false;
                    showPreviewLoading(false);
                    $('#previewFrame').css('opacity', '1');
                }
            });
        }
        
        function updatePreviewFrame(html) {
            const frame = $('#previewFrame')[0];
            if (!frame) return;
            
            try {
                const doc = frame.contentDocument || frame.contentWindow.document;
                doc.open();
                doc.write(html);
                doc.close();
                applyDeviceStyle();
            } catch (error) {
                showPreviewError('Erreur de rendu du preview');
            }
        }
        
        function setPreviewDevice(device) {
            if (!['desktop', 'tablet', 'mobile'].includes(device)) return;
            
            currentDevice = device;
            
            $('.device-btn').removeClass('active');
            $(`.device-btn:has(.fa-${device === 'desktop' ? 'desktop' : device === 'tablet' ? 'tablet-alt' : 'mobile-alt'})`).addClass('active');
            
            applyDeviceStyle();
            showNotification(`Mode ${device === 'desktop' ? 'Ordinateur' : device === 'tablet' ? 'Tablette' : 'Mobile'} activé`, 'info');
        }
        
        function applyDeviceStyle() {
            const frame = $('#previewFrame');
            const widths = { desktop: '100%', tablet: '768px', mobile: '375px' };
            
            frame.css({
                'width': widths[currentDevice],
                'margin': '0 auto',
                'display': 'block',
                'transition': 'width 0.3s ease'
            });
        }
        
        function showPreviewLoading(show) {
            if (show) {
                $('#previewOverlay').fadeIn(200);
                $('#previewFrame').css('opacity', '0.5');
            } else {
                $('#previewOverlay').fadeOut(200);
                $('#previewFrame').css('opacity', '1');
            }
        }
        
        function showPreviewError(message) {
            const frame = $('#previewFrame')[0];
            const doc = frame?.contentDocument || frame?.contentWindow?.document;
            
            if (doc) {
                doc.open();
                doc.write(`
                    <!DOCTYPE html>
                    <html>
                    <head><meta charset="UTF-8"><title>Erreur</title></head>
                    <body style="font-family:sans-serif;display:flex;align-items:center;justify-content:center;height:100vh;margin:0;background:#f8f9fa;">
                        <div style="text-align:center;padding:40px;">
                            <div style="font-size:48px;color:#ef476f;margin-bottom:20px;">⚠️</div>
                            <h3 style="color:#333;">Erreur de prévisualisation</h3>
                            <p style="color:#666;">${message}</p>
                            <button onclick="parent.refreshPreview()" style="margin-top:20px;padding:10px 20px;background:#667eea;color:white;border:none;border-radius:8px;cursor:pointer;">Réessayer</button>
                        </div>
                    </body>
                    </html>
                `);
                doc.close();
            }
        }
        
        // ============================================================
        // ÉDITEUR
        // ============================================================
        function insertTag(tag) {
            const textarea = document.getElementById('emailContent');
            if (!textarea) return;
            
            const start = textarea.selectionStart;
            const end = textarea.selectionEnd;
            const text = textarea.value;
            
            const newText = text.substring(0, start) + tag + text.substring(end);
            textarea.value = newText;
            textarea.selectionStart = start + tag.length;
            textarea.selectionEnd = start + tag.length;
            textarea.focus();
            
            $(textarea).trigger('input');
            showNotification(`Tag "${tag}" inséré`, 'success', 1500);
        }
        
        function formatText(format) {
            const textarea = document.getElementById('emailContent');
            if (!textarea) return;
            
            const start = textarea.selectionStart;
            const end = textarea.selectionEnd;
            
            if (start === end) {
                showNotification('Veuillez sélectionner du texte à formater', 'warning', 2000);
                return;
            }
            
            const selectedText = textarea.value.substring(start, end);
            
            let formatted = '';
            let tag = '';
            switch(format) {
                case 'bold':
                    formatted = '<strong>' + selectedText + '</strong>';
                    tag = 'gras';
                    break;
                case 'italic':
                    formatted = '<em>' + selectedText + '</em>';
                    tag = 'italique';
                    break;
                case 'underline':
                    formatted = '<u>' + selectedText + '</u>';
                    tag = 'souligné';
                    break;
                default: return;
            }
            
            const text = textarea.value;
            textarea.value = text.substring(0, start) + formatted + text.substring(end);
            textarea.selectionStart = start + formatted.length;
            textarea.selectionEnd = start + formatted.length;
            textarea.focus();
            
            $(textarea).trigger('input');
            showNotification(`Texte mis en ${tag}`, 'success', 1500);
        }
        
        // ============================================================
        // NOTIFICATIONS
        // ============================================================
        function showNotification(message, type = 'info', duration = 3000) {
            $('.notification-toast').remove();
            
            const icons = { success: '✓', danger: '⚠️', warning: '⚠️', info: 'ℹ️' };
            const colors = { success: '#06b48a', danger: '#ef476f', warning: '#ffd166', info: '#667eea' };
            
            const notification = $(`
                <div class="notification-toast" style="
                    position: fixed;
                    bottom: 20px;
                    right: 20px;
                    background: white;
                    border-left: 4px solid ${colors[type]};
                    padding: 12px 20px;
                    border-radius: 8px;
                    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                    z-index: 9999;
                    display: flex;
                    align-items: center;
                    gap: 12px;
                    animation: slideInRight 0.3s ease;
                ">
                    <div style="width:24px;height:24px;background:${colors[type]};border-radius:50%;display:flex;align-items:center;justify-content:center;color:white;font-weight:bold;">${icons[type]}</div>
                    <div style="color:#333;">${message}</div>
                    <button style="background:none;border:none;font-size:18px;cursor:pointer;color:#999;" onclick="$(this).closest('.notification-toast').remove()">×</button>
                </div>
            `);
            
            $('body').append(notification);
            setTimeout(() => notification.fadeOut(300, () => notification.remove()), duration);
        }
        
        // ============================================================
        // VALIDATION
        // ============================================================
        $('#campaignForm').on('submit', function(e) {
            const action = $(document.activeElement).val();
            const subject = $('#emailSubject').val().trim();
            const content = $('#emailContent').val().trim();
            
            if (!subject) {
                e.preventDefault();
                showNotification('Veuillez saisir un sujet pour l\'email', 'warning');
                $('#emailSubject').focus();
                return false;
            }
            
            if (!content) {
                e.preventDefault();
                showNotification('Veuillez saisir le contenu de l\'email', 'warning');
                $('#emailContent').focus();
                return false;
            }
            
            if (action === 'schedule') {
                if (!$('#scheduleCheck').is(':checked')) {
                    e.preventDefault();
                    showNotification('Veuillez planifier une date d\'envoi', 'warning');
                    return false;
                }
                const scheduledDate = $('input[name="scheduled_at"]').val();
                if (!scheduledDate) {
                    e.preventDefault();
                    showNotification('Veuillez choisir une date', 'warning');
                    return false;
                }
            }
            
            if (action === 'send') {
                if (!confirm('⚠️ Attention : Cette campagne sera envoyée immédiatement à tous les abonnés.\n\nÊtes-vous sûr de vouloir continuer ?')) {
                    e.preventDefault();
                    return false;
                }
            }
            
            return true;
        });
        
        // ============================================================
        // RACCOURCIS CLAVIER
        // ============================================================
        $(document).on('keydown', function(e) {
            switch(e.key) {
                case 'F2': e.preventDefault(); insertTag('@{{prenom}}'); break;
                case 'F3': e.preventDefault(); insertTag('@{{nom}}'); break;
                case 'F4': e.preventDefault(); insertTag('@{{email}}'); break;
                case 'F5': e.preventDefault(); insertTag('@{{etablissement}}'); break;
            }
        });
        
        // Animation CSS
        const style = document.createElement('style');
        style.textContent = `
            @keyframes slideInRight {
                from { transform: translateX(100%); opacity: 0; }
                to { transform: translateX(0); opacity: 1; }
            }
            @keyframes fadeIn {
                from { opacity: 0; transform: translateY(10px); }
                to { opacity: 1; transform: translateY(0); }
            }
            .preview-frame-container { position: relative; min-height: 450px; background: #f5f5f5; border-radius: 12px; overflow: hidden; }
            .preview-overlay { position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: rgba(255,255,255,0.95); display: flex; align-items: center; justify-content: center; z-index: 10; }
            .preview-loader { text-align: center; }
            .spinner { width: 40px; height: 40px; border: 3px solid #e0e0e0; border-top-color: #667eea; border-radius: 50%; animation: spin 0.8s linear infinite; margin: 0 auto 10px; }
            @keyframes spin { to { transform: rotate(360deg); } }
            .char-counter.warning { color: #ef476f; }
            .char-counter.caution { color: #ffd166; }
            .stat-badge.success { background: #e8f5e9; color: #06b48a; }
            .stat-badge.warning { background: #fff3e0; color: #ffb347; }
            .stat-badge.info { background: #e3f2fd; color: #2196f3; }
        `;
        document.head.appendChild(style);
        
        console.log('✅ Module email marketing chargé');
    </script>
    <style>
        /* Page Header Modern */
        .page-header-modern {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 24px;
            padding: 28px 32px;
            margin-bottom: 32px;
            box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1);
        }
        
        .page-header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
        }
        
        .page-header-left {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        
        .page-header-icon {
            width: 60px;
            height: 60px;
            background: rgba(255,255,255,0.2);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 28px;
        }
        
        .page-title-modern {
            font-size: 28px;
            font-weight: 700;
            color: white;
            margin: 0;
            letter-spacing: -0.5px;
        }
        
        .page-subtitle-modern {
            color: rgba(255,255,255,0.8);
            margin: 5px 0 0;
            font-size: 14px;
        }
        
        .btn-back {
            background: rgba(255,255,255,0.2);
            color: white;
            padding: 10px 20px;
            border-radius: 12px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
        }
        
        .btn-back:hover {
            background: rgba(255,255,255,0.3);
            color: white;
            transform: translateX(-3px);
        }
        
        /* Cards Modern */
        .card-modern {
            background: white;
            border-radius: 24px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
            margin-bottom: 24px;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .card-modern:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(0,0,0,0.1);
        }
        
        .card-header-modern {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 20px 24px;
            border-bottom: 1px solid #eef2f6;
            background: #fafbfc;
        }
        
        .card-header-icon {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 16px;
        }
        
        .card-title-modern {
            font-size: 18px;
            font-weight: 600;
            color: #1e293b;
            margin: 0;
            flex: 1;
        }
        
        .card-badge {
            background: #eef2ff;
            color: #667eea;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }
        
        .card-body-modern {
            padding: 24px;
        }
        
        /* Form Groups */
        .form-group-modern {
            margin-bottom: 24px;
        }
        
        .form-label-modern {
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 8px;
            font-size: 14px;
        }
        
        .form-label-modern.required:after {
            content: "*";
            color: #ef476f;
            margin-left: 4px;
        }
        
        .input-group-modern {
            position: relative;
        }
        
        .form-control-modern {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            font-size: 14px;
            transition: all 0.3s ease;
            background: white;
        }
        
        .form-control-modern:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102,126,234,0.1);
            outline: none;
        }
        
        .input-icon {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
        }
        
        .form-text-modern {
            font-size: 12px;
            color: #64748b;
            margin-top: 6px;
            display: block;
        }
        
        .subject-preview {
            background: #f8fafc;
            padding: 10px 12px;
            border-radius: 10px;
            font-size: 13px;
            margin-top: 8px;
            color: #475569;
        }
        
        /* Editor Toolbar */
        .editor-toolbar-modern {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px 12px 0 0;
            padding: 12px;
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
        }
        
        .toolbar-group {
            display: flex;
            align-items: center;
            gap: 6px;
            flex-wrap: wrap;
        }
        
        .toolbar-label {
            font-size: 12px;
            color: #64748b;
            font-weight: 500;
        }
        
        .toolbar-btn {
            padding: 6px 12px;
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-size: 12px;
            cursor: pointer;
            transition: all 0.2s ease;
            color: #334155;
        }
        
        .toolbar-btn:hover {
            background: #667eea;
            color: white;
            border-color: #667eea;
            transform: translateY(-1px);
        }
        
        .toolbar-divider {
            width: 1px;
            height: 30px;
            background: #e2e8f0;
        }
        
        .editor-textarea {
            border-radius: 0 0 12px 12px;
            border-top: none;
            font-family: 'Monaco', 'Menlo', monospace;
            font-size: 13px;
            line-height: 1.6;
            resize: vertical;
        }
        
        .editor-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 12px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-top: none;
            border-radius: 0 0 12px 12px;
            font-size: 12px;
            color: #64748b;
        }
        
        .editor-tips i, .char-counter i {
            margin-right: 4px;
        }
        
        .separator-dot {
            margin: 0 8px;
        }
        
        /* Option Cards */
        .option-card {
            background: #f8fafc;
            border-radius: 16px;
            padding: 16px;
        }
        
        .option-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 12px;
        }
        
        .option-icon {
            font-size: 18px;
            color: #667eea;
        }
        
        .option-title {
            font-weight: 600;
            color: #1e293b;
        }
        
        .form-switch-modern .form-check-input {
            width: 40px;
            height: 20px;
            cursor: pointer;
        }
        
        .form-switch-modern .form-check-input:checked {
            background-color: #667eea;
            border-color: #667eea;
        }
        
        .datetime-picker {
            position: relative;
        }
        
        .datetime-picker i {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
        }
        
        .datetime-picker input {
            padding-left: 36px;
        }
        
        .segmentation-stats {
            margin-top: 12px;
        }
        
        .stat-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 12px;
            background: #eef2ff;
            border-radius: 20px;
            font-size: 12px;
            color: #667eea;
        }
        
        /* Theme Grid */
        .theme-grid {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
        
        .theme-card {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 12px;
            border: 2px solid #eef2f6;
            border-radius: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .theme-card:hover {
            border-color: #667eea;
            background: #f8fafc;
        }
        
        .theme-card.active {
            border-color: #667eea;
            background: linear-gradient(135deg, rgba(102,126,234,0.05), rgba(118,75,162,0.05));
        }
        
        .theme-preview {
            width: 60px;
            height: 60px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
        }
        
        .theme-info {
            flex: 1;
        }
        
        .theme-name {
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 2px;
        }
        
        .theme-description {
            font-size: 11px;
            color: #64748b;
        }
        
        .theme-radio input {
            width: 18px;
            height: 18px;
            cursor: pointer;
        }
        
        /* Preview */
        .preview-container {
            background: #f1f5f9;
            border-radius: 16px;
            overflow: hidden;
        }
        
        .preview-toolbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 16px;
            background: white;
            border-bottom: 1px solid #eef2f6;
        }
        
        .preview-device-selector {
            display: flex;
            gap: 8px;
        }
        
        .device-btn {
            padding: 8px 12px;
            background: #f1f5f9;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.2s ease;
            color: #475569;
        }
        
        .device-btn.active {
            background: #667eea;
            color: white;
        }
        
        .preview-status {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            color: #64748b;
        }
        
        .preview-frame {
            width: 100%;
            height: 450px;
            border: none;
            background: white;
        }
        
        /* Stats Mini Grid */
        .stats-mini-grid {
            display: flex;
            gap: 16px;
        }
        
        .stat-mini-item {
            flex: 1;
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px;
            background: #f8fafc;
            border-radius: 12px;
        }
        
        .stat-mini-icon {
            width: 40px;
            height: 40px;
            background: #eef2ff;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #667eea;
            font-size: 18px;
        }
        
        .stat-mini-info {
            display: flex;
            flex-direction: column;
        }
        
        .stat-mini-value {
            font-size: 20px;
            font-weight: 700;
            color: #1e293b;
            line-height: 1;
        }
        
        .stat-mini-label {
            font-size: 11px;
            color: #64748b;
        }
        
        /* Form Actions */
        .form-actions-modern {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            margin-top: 32px;
            padding: 20px 24px;
            background: white;
            border-radius: 24px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        }
        
        .actions-left, .actions-right {
            display: flex;
            gap: 12px;
        }
        
        .btn-clear {
            padding: 12px 20px;
            background: #f1f5f9;
            border: none;
            border-radius: 12px;
            color: #475569;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        
        .btn-clear:hover {
            background: #e2e8f0;
        }
        
        .btn-draft, .btn-schedule, .btn-send {
            padding: 12px 24px;
            border: none;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        
        .btn-draft {
            background: #f1f5f9;
            color: #475569;
        }
        
        .btn-draft:hover {
            background: #e2e8f0;
            transform: translateY(-1px);
        }
        
        .btn-schedule {
            background: #ffd166;
            color: #2d3748;
        }
        
        .btn-schedule:hover {
            background: #ffc107;
            transform: translateY(-1px);
        }
        
        .btn-send {
            background: linear-gradient(135deg, #06b48a, #049a72);
            color: white;
        }
        
        .btn-send:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(6,180,138,0.3);
        }
        
        /* Responsive */
        @media (max-width: 992px) {
            .page-header-modern { padding: 20px; }
            .page-title-modern { font-size: 22px; }
            .form-actions-modern { flex-direction: column; }
            .actions-left, .actions-right { width: 100%; justify-content: center; }
            .stats-mini-grid { flex-wrap: wrap; }
        }
        
        @media (max-width: 768px) {
            .page-header-left { flex-direction: column; text-align: center; width: 100%; }
            .editor-toolbar-modern { flex-direction: column; }
            .toolbar-divider { display: none; }
        }
    </style>
@endsection