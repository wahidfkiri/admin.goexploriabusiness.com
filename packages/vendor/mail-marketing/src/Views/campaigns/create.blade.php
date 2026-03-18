@extends('layouts.app')

@section('content')
    
    <!-- MAIN CONTENT -->
    <main class="dashboard-content">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">
                <span class="page-title-icon"><i class="fas fa-plus-circle"></i></span>
                Créer une nouvelle campagne
            </h1>
            
            <div class="page-actions">
                <a href="{{ route('mail-campaigns.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Retour à la liste
                </a>
            </div>
        </div>
        
        <!-- Main Card - Modern Design -->
        <div class="main-card-modern">
            <div class="card-header-modern">
                <h3 class="card-title-modern">Informations de la campagne</h3>
                <div class="card-subtitle-modern">
                    Remplissez les informations ci-dessous pour créer votre campagne email
                </div>
            </div>
            
            <div class="card-body-modern">
                <form action="{{ route('mail-campaigns.store') }}" method="POST" id="campaignForm">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-8">
                            <!-- Nom de la campagne -->
                            <div class="form-group-modern mb-4">
                                <label for="nom" class="form-label-modern required">
                                    Nom de la campagne
                                </label>
                                <input type="text" 
                                       class="form-control-modern @error('nom') is-invalid @enderror" 
                                       id="nom" 
                                       name="nom" 
                                       value="{{ old('nom') }}"
                                       placeholder="ex: Newsletter Mars 2025"
                                       required>
                                <small class="form-text-modern text-muted">
                                    Un nom interne pour identifier votre campagne
                                </small>
                                @error('nom')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <!-- Sujet de l'email -->
                            <div class="form-group-modern mb-4">
                                <label for="sujet" class="form-label-modern required">
                                    Sujet de l'email
                                </label>
                                <input type="text" 
                                       class="form-control-modern @error('sujet') is-invalid @enderror" 
                                       id="sujet" 
                                       name="sujet" 
                                       value="{{ old('sujet') }}"
                                       placeholder="ex: Découvrez nos dernières offres"
                                       required>
                                <div class="subject-preview" id="subjectPreview">
                                    <i class="fas fa-eye me-1"></i>
                                    Aperçu: <span id="subjectPreviewText">{{ old('sujet') ?: 'Découvrez nos dernières offres' }}</span>
                                </div>
                                @error('sujet')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <!-- Contenu de l'email -->
                            <div class="form-group-modern mb-4">
                                <label for="contenu" class="form-label-modern required">
                                    Contenu de l'email
                                </label>
                                <div class="editor-toolbar">
                                    <button type="button" class="editor-btn" onclick="insertTag('@{{nom}}')" title="Nom de l'abonné">
    <i class="fas fa-user"></i> Nom
</button>
<button type="button" class="editor-btn" onclick="insertTag('@{{prenom}}')" title="Prénom de l'abonné">
    <i class="fas fa-user"></i> Prénom
</button>
<button type="button" class="editor-btn" onclick="insertTag('@{{email}}')" title="Email de l'abonné">
    <i class="fas fa-envelope"></i> Email
</button>
<button type="button" class="editor-btn" onclick="insertTag('@{{etablissement}}')" title="Nom de l'établissement">
    <i class="fas fa-building"></i> Établissement
</button>
                                    <span class="editor-separator"></span>
                                    <button type="button" class="editor-btn" onclick="formatText('bold')" title="Gras">
                                        <i class="fas fa-bold"></i>
                                    </button>
                                    <button type="button" class="editor-btn" onclick="formatText('italic')" title="Italique">
                                        <i class="fas fa-italic"></i>
                                    </button>
                                    <button type="button" class="editor-btn" onclick="formatText('underline')" title="Souligné">
                                        <i class="fas fa-underline"></i>
                                    </button>
                                </div>
                                <textarea class="form-control-modern @error('contenu') is-invalid @enderror" 
                                          id="contenu" 
                                          name="contenu" 
                                          rows="15"
                                          placeholder="Rédigez le contenu de votre email..."
                                          required>{{ old('contenu') }}</textarea>
                                <small class="form-text-modern text-muted">
                                    Utilisez les boutons ci-dessus pour insérer des variables personnalisées
                                </small>
                                @error('contenu')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <!-- Planification -->
                            <div class="card-modern mb-4">
                                <div class="card-header-modern">
                                    <h4 class="card-title-modern">Planification</h4>
                                </div>
                                <div class="card-body-modern">
                                    <div class="form-group-modern mb-3">
                                        <div class="form-check-modern">
                                            <input type="checkbox" 
                                                   class="form-check-input" 
                                                   id="schedule" 
                                                   name="schedule" 
                                                   value="1"
                                                   {{ old('schedule') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="schedule">
                                                Planifier l'envoi
                                            </label>
                                        </div>
                                    </div>
                                    
                                    <div id="scheduleFields" style="{{ old('schedule') ? 'display: block;' : 'display: none;' }}">
                                        <div class="form-group-modern mb-3">
                                            <label for="scheduled_at" class="form-label-modern">
                                                Date et heure d'envoi
                                            </label>
                                            <input type="datetime-local" 
                                                   class="form-control-modern @error('scheduled_at') is-invalid @enderror" 
                                                   id="scheduled_at" 
                                                   name="scheduled_at" 
                                                   value="{{ old('scheduled_at') }}"
                                                   min="{{ now()->format('Y-m-d\TH:i') }}">
                                            @error('scheduled_at')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Sélection des abonnés -->
                            <div class="card-modern mb-4">
                                <div class="card-header-modern">
                                    <h4 class="card-title-modern">Destinataires</h4>
                                </div>
                                <div class="card-body-modern">
                                    <div class="form-group-modern mb-3">
                                        <label for="recipients" class="form-label-modern">
                                            Sélectionner les destinataires
                                        </label>
                                        <select class="form-select-modern" id="recipients" name="recipient_type">
                                            <option value="all">Tous les abonnés</option>
                                            <option value="active">Abonnés actifs uniquement</option>
                                            <option value="etablissement">Par établissement</option>
                                        </select>
                                    </div>
                                    
                                    <div id="etablissementField" style="display: none;">
                                        <div class="form-group-modern mb-3">
                                            <label for="etablissement_id" class="form-label-modern">
                                                Établissement
                                            </label>
                                            <select class="form-select-modern" id="etablissement_id" name="etablissement_id">
                                                <option value="">Sélectionner...</option>
                                                @foreach($etablissements ?? [] as $etablissement)
                                                    <option value="{{ $etablissement->id }}">{{ $etablissement->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="recipients-stats">
                                        <div class="stat-item">
                                            <i class="fas fa-users text-primary"></i>
                                            <span id="totalRecipients">{{ $totalSubscribers ?? 0 }}</span> abonnés
                                        </div>
                                        <div class="stat-item">
                                            <i class="fas fa-check-circle text-success"></i>
                                            <span id="activeRecipients">{{ $activeSubscribers ?? 0 }}</span> actifs
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Aperçu rapide -->
                            <div class="card-modern">
                                <div class="card-header-modern">
                                    <h4 class="card-title-modern">Aperçu rapide</h4>
                                </div>
                                <div class="card-body-modern">
                                    <div class="preview-box">
                                        <div class="preview-subject">
                                            <strong>Sujet:</strong> 
                                            <span id="previewSubject">{{ old('sujet') ?: 'Découvrez nos dernières offres' }}</span>
                                        </div>
                                        <div class="preview-recipients">
                                            <strong>Destinataires:</strong>
                                            <span id="previewRecipients">Tous les abonnés</span>
                                        </div>
                                        <div class="preview-schedule">
                                            <strong>Envoi:</strong>
                                            <span id="previewSchedule">Immédiat</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Boutons d'action -->
                    <div class="form-actions-modern">
                        <button type="submit" name="action" value="save" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Enregistrer comme brouillon
                        </button>
                        <button type="submit" name="action" value="schedule" class="btn btn-info" id="scheduleBtn">
                            <i class="fas fa-clock me-2"></i>Planifier
                        </button>
                        <button type="submit" name="action" value="send" class="btn btn-success" id="sendBtn">
                            <i class="fas fa-paper-plane me-2"></i>Envoyer maintenant
                        </button>
                        <a href="{{ route('mail-campaigns.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-2"></i>Annuler
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle schedule fields
            const scheduleCheckbox = document.getElementById('schedule');
            const scheduleFields = document.getElementById('scheduleFields');
            const scheduleBtn = document.getElementById('scheduleBtn');
            
            if (scheduleCheckbox) {
                scheduleCheckbox.addEventListener('change', function() {
                    scheduleFields.style.display = this.checked ? 'block' : 'none';
                    updatePreview();
                });
            }
            
            // Toggle etablissement field
            const recipientSelect = document.getElementById('recipients');
            const etablissementField = document.getElementById('etablissementField');
            
            if (recipientSelect) {
                recipientSelect.addEventListener('change', function() {
                    etablissementField.style.display = this.value === 'etablissement' ? 'block' : 'none';
                    updateRecipientsCount();
                    updatePreview();
                });
            }
            
            // Update preview on input
            const sujetInput = document.getElementById('sujet');
            if (sujetInput) {
                sujetInput.addEventListener('input', function() {
                    document.getElementById('subjectPreviewText').textContent = this.value || 'Découvrez nos dernières offres';
                    document.getElementById('previewSubject').textContent = this.value || 'Découvrez nos dernières offres';
                });
            }
            
            // Update recipients count
            const updateRecipientsCount = () => {
                // This would normally fetch from server
                console.log('Updating recipients count...');
            };
            
            // Update preview
            const updatePreview = () => {
                const scheduleChecked = scheduleCheckbox?.checked || false;
                const scheduledAt = document.getElementById('scheduled_at')?.value;
                
                let scheduleText = 'Immédiat';
                if (scheduleChecked && scheduledAt) {
                    const date = new Date(scheduledAt);
                    scheduleText = 'Planifié le ' + date.toLocaleDateString('fr-FR') + ' à ' + date.toLocaleTimeString('fr-FR', {hour: '2-digit', minute:'2-digit'});
                }
                document.getElementById('previewSchedule').textContent = scheduleText;
                
                const recipientText = recipientSelect?.options[recipientSelect.selectedIndex]?.text || 'Tous les abonnés';
                document.getElementById('previewRecipients').textContent = recipientText;
            };
            
            // Form validation before send/schedule
            const form = document.getElementById('campaignForm');
            if (form) {
                form.addEventListener('submit', function(e) {
                    const action = e.submitter?.value;
                    
                    if (action === 'send' || action === 'schedule') {
                        if (!confirm('Êtes-vous sûr de vouloir ' + (action === 'send' ? 'envoyer' : 'planifier') + ' cette campagne ?')) {
                            e.preventDefault();
                            return;
                        }
                    }
                    
                    if (action === 'schedule' && scheduleCheckbox && !scheduleCheckbox.checked) {
                        e.preventDefault();
                        alert('Veuillez cocher "Planifier l\'envoi" et choisir une date.');
                    }
                });
            }
            
            updatePreview();
        });
        
        // Insert tag in textarea
        function insertTag(tag) {
            const textarea = document.getElementById('contenu');
            const start = textarea.selectionStart;
            const end = textarea.selectionEnd;
            const text = textarea.value;
            const before = text.substring(0, start);
            const after = text.substring(end, text.length);
            
            textarea.value = before + tag + after;
            textarea.selectionStart = start + tag.length;
            textarea.selectionEnd = start + tag.length;
            textarea.focus();
        }
        
        // Format text (simple wrapper for now)
        function formatText(format) {
            const textarea = document.getElementById('contenu');
            const start = textarea.selectionStart;
            const end = textarea.selectionEnd;
            const selectedText = textarea.value.substring(start, end);
            
            let formattedText = '';
            switch(format) {
                case 'bold':
                    formattedText = '<strong>' + selectedText + '</strong>';
                    break;
                case 'italic':
                    formattedText = '<em>' + selectedText + '</em>';
                    break;
                case 'underline':
                    formattedText = '<u>' + selectedText + '</u>';
                    break;
                default:
                    return;
            }
            
            const text = textarea.value;
            const before = text.substring(0, start);
            const after = text.substring(end, text.length);
            
            textarea.value = before + formattedText + after;
            textarea.focus();
        }
    </script>

    <style>
        .form-label-modern.required:after {
            content: " *";
            color: #ef476f;
            font-weight: bold;
        }
        
        .editor-toolbar {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
            padding: 10px;
            background: #f8f9fa;
            border: 1px solid #eaeaea;
            border-bottom: none;
            border-radius: 8px 8px 0 0;
        }
        
        .editor-btn {
            padding: 6px 12px;
            background: white;
            border: 1px solid #ddd;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.9rem;
            color: #333;
            transition: all 0.3s ease;
        }
        
        .editor-btn:hover {
            background: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }
        
        .editor-separator {
            width: 1px;
            height: 30px;
            background: #ddd;
            margin: 0 5px;
        }
        
        .subject-preview {
            margin-top: 5px;
            padding: 8px 12px;
            background: #e8f4fd;
            border-radius: 4px;
            font-size: 0.9rem;
            color: #0066cc;
        }
        
        .recipients-stats {
            display: flex;
            justify-content: space-between;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid #eaeaea;
        }
        
        .stat-item {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 0.9rem;
            color: #666;
        }
        
        .preview-box {
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
            font-size: 0.9rem;
        }
        
        .preview-box div {
            margin-bottom: 8px;
        }
        
        .form-actions-modern {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px solid #eaeaea;
        }
        
        @media (max-width: 768px) {
            .form-actions-modern {
                flex-direction: column;
            }
            
            .form-actions-modern button,
            .form-actions-modern a {
                width: 100%;
            }
            
            .editor-toolbar {
                flex-wrap: wrap;
            }
            
            .editor-btn {
                flex: 1 1 auto;
            }
        }
    </style>
@endsection