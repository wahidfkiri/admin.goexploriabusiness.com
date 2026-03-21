@extends('layouts.app')

@section('content')
    
    <main class="dashboard-content">
        <!-- Page Header -->
        <div class="page-header-modern">
            <div class="page-header-content">
                <div class="page-header-left">
                    <div class="page-header-icon">
                        <i class="fas fa-user-edit"></i>
                    </div>
                    <div>
                        <h1 class="page-title-modern">Modifier l'abonné</h1>
                        <p class="page-subtitle-modern">{{ $subscriber->email }}</p>
                    </div>
                </div>
                <div class="page-header-right">
                    <a href="{{ route('mail-subscribers.index') }}" class="btn-back">
                        <i class="fas fa-arrow-left me-2"></i>Retour à la liste
                    </a>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Colonne gauche - Formulaire principal -->
            <div class="col-lg-8">
                <div class="card-modern">
                    <div class="card-header-modern">
                        <div class="card-header-icon">
                            <i class="fas fa-info-circle"></i>
                        </div>
                        <h3 class="card-title-modern">Informations de l'abonné</h3>
                        <span class="card-badge">Modification</span>
                    </div>
                    <div class="card-body-modern">
                        <form action="{{ route('mail-subscribers.update', $subscriber) }}" method="POST" id="subscriberForm">
                            @csrf
                            @method('PUT')
                            
                            <!-- Email (obligatoire) -->
                            <div class="form-group-modern">
                                <label class="form-label-modern required">
                                    <i class="fas fa-envelope"></i>
                                    Adresse email
                                </label>
                                <div class="input-group-modern">
                                    <input type="email" 
                                           name="email" 
                                           class="form-control-modern @error('email') is-invalid @enderror" 
                                           value="{{ old('email', $subscriber->email) }}" 
                                           placeholder="ex: contact@exemple.com"
                                           required>
                                    <span class="input-icon"><i class="fas fa-at"></i></span>
                                </div>
                                <small class="form-text-modern">L'email sera utilisé pour l'envoi des campagnes</small>
                                @error('email') 
                                    <div class="invalid-feedback">{{ $message }}</div> 
                                @enderror
                            </div>
                            
                            <div class="row g-3">
                                <!-- Prénom -->
                                <div class="col-md-6">
                                    <div class="form-group-modern">
                                        <label class="form-label-modern">
                                            <i class="fas fa-user"></i>
                                            Prénom
                                        </label>
                                        <input type="text" 
                                               name="prenom" 
                                               class="form-control-modern @error('prenom') is-invalid @enderror" 
                                               value="{{ old('prenom', $subscriber->prenom) }}" 
                                               placeholder="Jean">
                                        @error('prenom') 
                                            <div class="invalid-feedback">{{ $message }}</div> 
                                        @enderror
                                    </div>
                                </div>
                                
                                <!-- Nom -->
                                <div class="col-md-6">
                                    <div class="form-group-modern">
                                        <label class="form-label-modern">
                                            <i class="fas fa-user"></i>
                                            Nom
                                        </label>
                                        <input type="text" 
                                               name="nom" 
                                               class="form-control-modern @error('nom') is-invalid @enderror" 
                                               value="{{ old('nom', $subscriber->nom) }}" 
                                               placeholder="Dupont">
                                        @error('nom') 
                                            <div class="invalid-feedback">{{ $message }}</div> 
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Établissement -->
                            <div class="form-group-modern">
                                <label class="form-label-modern">
                                    <i class="fas fa-building"></i>
                                    Établissement
                                </label>
                                <select name="etablissement_id" class="form-select-modern @error('etablissement_id') is-invalid @enderror">
                                    <option value="">-- Sélectionner un établissement --</option>
                                    @foreach($etablissements ?? [] as $etablissement)
                                        <option value="{{ $etablissement->id }}" {{ old('etablissement_id', $subscriber->etablissement_id) == $etablissement->id ? 'selected' : '' }}>
                                            {{ $etablissement->name }} - {{ $etablissement->ville ?? '' }}
                                        </option>
                                    @endforeach
                                </select>
                                <small class="form-text-modern">Laissez vide si l'abonné n'est pas associé à un établissement</small>
                                @error('etablissement_id') 
                                    <div class="invalid-feedback">{{ $message }}</div> 
                                @enderror
                            </div>
                            
                            <!-- Statut d'abonnement -->
                            <div class="form-group-modern">
                                <label class="form-label-modern">
                                    <i class="fas fa-toggle-on"></i>
                                    Statut d'abonnement
                                </label>
                                <div class="status-toggle">
                                    <label class="toggle-switch">
                                        <input type="checkbox" name="is_subscribed" value="1" {{ old('is_subscribed', $subscriber->is_subscribed) ? 'checked' : '' }}>
                                        <span class="toggle-slider"></span>
                                    </label>
                                    <span class="toggle-label" id="statusLabel">
                                        @if($subscriber->is_subscribed)
                                            <i class="fas fa-check-circle text-success"></i> Abonné actif
                                        @else
                                            <i class="fas fa-times-circle text-danger"></i> Désabonné
                                        @endif
                                    </span>
                                </div>
                                @if($subscriber->unsubscribed_at)
                                    <small class="form-text-modern text-muted">
                                        Désabonné le {{ $subscriber->unsubscribed_at->format('d/m/Y à H:i') }}
                                    </small>
                                @endif
                                <small class="form-text-modern">Désactivez pour désabonner cet utilisateur</small>
                            </div>
                            
                            <!-- Informations supplémentaires -->
                            <div class="info-box-modern">
                                <div class="info-box-header">
                                    <i class="fas fa-chart-line"></i>
                                    <span>Statistiques de l'abonné</span>
                                </div>
                                <div class="info-box-content">
                                    <div class="info-row">
                                        <span class="info-label">Campagnes reçues :</span>
                                        <span class="info-value">{{ $subscriber->campaigns_count ?? 0 }}</span>
                                    </div>
                                    <div class="info-row">
                                        <span class="info-label">Taux d'ouverture :</span>
                                        <span class="info-value">
                                            @php
                                                $openRate = ($subscriber->campaigns_count ?? 0) > 0 
                                                    ? round((($subscriber->opened_count ?? 0) / ($subscriber->campaigns_count ?? 1)) * 100) 
                                                    : 0;
                                            @endphp
                                            {{ $openRate }}%
                                        </span>
                                    </div>
                                    <div class="info-row">
                                        <span class="info-label">Date d'inscription :</span>
                                        <span class="info-value">{{ $subscriber->created_at->format('d/m/Y à H:i') }}</span>
                                    </div>
                                    <div class="info-row">
                                        <span class="info-label">Dernière modification :</span>
                                        <span class="info-value">{{ $subscriber->updated_at->format('d/m/Y à H:i') }}</span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Actions -->
                            <div class="form-actions-modern mt-4">
                                <button type="submit" class="btn-save">
                                    <i class="fas fa-save"></i>
                                    <span>Mettre à jour</span>
                                </button>
                                <a href="{{ route('mail-subscribers.show', $subscriber) }}" class="btn-preview">
                                    <i class="fas fa-eye"></i>
                                    <span>Voir le profil</span>
                                </a>
                                <a href="{{ route('mail-subscribers.index') }}" class="btn-cancel">
                                    <i class="fas fa-times"></i>
                                    <span>Annuler</span>
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Colonne droite - Aperçu et informations -->
            <div class="col-lg-4">
                <!-- Carte Aperçu -->
                <div class="card-modern">
                    <div class="card-header-modern">
                        <div class="card-header-icon">
                            <i class="fas fa-user-circle"></i>
                        </div>
                        <h3 class="card-title-modern">Aperçu</h3>
                    </div>
                    <div class="card-body-modern">
                        <div class="preview-card" id="previewCard">
                            <div class="preview-avatar" id="previewAvatar" style="background: {{ \Vendor\MailMarketing\Helpers\Helper::getAvatarColor($subscriber->email) }}">
                                {{ \Vendor\MailMarketing\Helpers\Helper::getInitials($subscriber->nom, $subscriber->prenom) }}
                            </div>
                            <div class="preview-info">
                                <div class="preview-name" id="previewName">
                                    {{ $subscriber->prenom }} {{ $subscriber->nom }}
                                </div>
                                <div class="preview-email" id="previewEmail">
                                    {{ $subscriber->email }}
                                </div>
                                <div class="preview-etablissement" id="previewEtablissement">
                                    @if($subscriber->etablissement)
                                        <i class="fas fa-building"></i> {{ $subscriber->etablissement->name }}
                                    @else
                                        <i class="fas fa-building"></i> Aucun établissement
                                    @endif
                                </div>
                                <div class="preview-status" id="previewStatus">
                                    @if($subscriber->is_subscribed)
                                        <span class="badge-active"><i class="fas fa-circle"></i> Abonné actif</span>
                                    @else
                                        <span class="badge-inactive"><i class="fas fa-circle"></i> Désabonné</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Carte Actions rapides -->
                <div class="card-modern">
                    <div class="card-header-modern">
                        <div class="card-header-icon">
                            <i class="fas fa-bolt"></i>
                        </div>
                        <h3 class="card-title-modern">Actions rapides</h3>
                    </div>
                    <div class="card-body-modern">
                        <div class="quick-actions">
                            @if($subscriber->is_subscribed)
                                <button onclick="unsubscribeSubscriber({{ $subscriber->id }})" class="action-quick unsubscribe">
                                    <i class="fas fa-ban"></i>
                                    <span>Désabonner</span>
                                </button>
                            @else
                                <button onclick="resubscribeSubscriber({{ $subscriber->id }})" class="action-quick resubscribe">
                                    <i class="fas fa-undo"></i>
                                    <span>Réabonner</span>
                                </button>
                            @endif
                            
                            <a href="{{ route('mail-campaigns.create') }}?subscriber={{ $subscriber->id }}" class="action-quick create-campaign">
                                <i class="fas fa-bullhorn"></i>
                                <span>Créer une campagne</span>
                            </a>
                            
                            <button onclick="showDeleteConfirmation({{ $subscriber->id }})" class="action-quick delete">
                                <i class="fas fa-trash"></i>
                                <span>Supprimer</span>
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Carte Historique récent -->
                <div class="card-modern">
                    <div class="card-header-modern">
                        <div class="card-header-icon">
                            <i class="fas fa-history"></i>
                        </div>
                        <h3 class="card-title-modern">Dernières activités</h3>
                    </div>
                    <div class="card-body-modern p-0">
                        <div class="activity-list">
                            @forelse($subscriber->trackingEvents()->latest()->limit(5)->get() as $event)
                                <div class="activity-item">
                                    <div class="activity-icon">
                                        @if($event->event_type === 'open')
                                            <i class="fas fa-eye text-success"></i>
                                        @elseif($event->event_type === 'click')
                                            <i class="fas fa-mouse-pointer text-warning"></i>
                                        @else
                                            <i class="fas fa-envelope text-info"></i>
                                        @endif
                                    </div>
                                    <div class="activity-details">
                                        <div class="activity-title">
                                            @if($event->event_type === 'open')
                                                Ouverture d'email
                                            @elseif($event->event_type === 'click')
                                                Clic sur un lien
                                            @else
                                                Email reçu
                                            @endif
                                        </div>
                                        <div class="activity-date">
                                            {{ $event->created_at->diffForHumans() }}
                                        </div>
                                        @if($event->campaign)
                                            <div class="activity-campaign">
                                                <i class="fas fa-bullhorn"></i> {{ $event->campaign->nom }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="empty-activities">
                                    <i class="fas fa-history"></i>
                                    <p>Aucune activité récente</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- DELETE CONFIRMATION MODAL -->
    <div class="modal fade delete-confirm-modal" id="deleteConfirmationModal" tabindex="-1" aria-hidden="true">
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
                    <p class="delete-message">Êtes-vous sûr de vouloir supprimer cet abonné ?</p>
                    
                    <div class="subscriber-info-display" id="deleteInfo">
                        <div class="subscriber-display">
                            <div class="subscriber-avatar-lg" style="background: {{ \Vendor\MailMarketing\Helpers\Helper::getAvatarColor($subscriber->email) }}">
                                {{ \Vendor\MailMarketing\Helpers\Helper::getInitials($subscriber->nom, $subscriber->prenom) }}
                            </div>
                            <div class="subscriber-details">
                                <div class="subscriber-fullname">{{ $subscriber->prenom }} {{ $subscriber->nom }}</div>
                                <div class="subscriber-email">{{ $subscriber->email }}</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <strong>Attention :</strong> Cette action est irréversible.
                    </div>
                </div>
                <div class="modal-footer border-0 justify-content-center">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Annuler
                    </button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn">
                        <i class="fas fa-trash me-2"></i>Supprimer définitivement
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let subscriberId = {{ $subscriber->id }};
        
        document.addEventListener('DOMContentLoaded', function() {
            // Preview update on input
            const emailInput = document.querySelector('input[name="email"]');
            const prenomInput = document.querySelector('input[name="prenom"]');
            const nomInput = document.querySelector('input[name="nom"]');
            const etablissementSelect = document.querySelector('select[name="etablissement_id"]');
            const statusCheckbox = document.querySelector('input[name="is_subscribed"]');
            
            const previewName = document.getElementById('previewName');
            const previewEmail = document.getElementById('previewEmail');
            const previewEtablissement = document.getElementById('previewEtablissement');
            const previewStatus = document.getElementById('previewStatus');
            const previewAvatar = document.getElementById('previewAvatar');
            
            function updatePreview() {
                // Update name
                const prenom = prenomInput?.value || '';
                const nom = nomInput?.value || '';
                const fullName = [prenom, nom].filter(Boolean).join(' ');
                previewName.textContent = fullName || 'Nouvel abonné';
                
                // Update avatar initials
                const initials = (prenom?.[0] || '') + (nom?.[0] || '');
                previewAvatar.textContent = initials.toUpperCase() || '?';
                
                // Update email
                const email = emailInput?.value || '';
                previewEmail.textContent = email || 'email@exemple.com';
                
                // Update établissement
                const etablissementOption = etablissementSelect?.options[etablissementSelect.selectedIndex];
                const etablissementName = etablissementOption?.text?.split(' - ')[0] || '';
                if (etablissementName && etablissementName !== '-- Sélectionner un établissement --') {
                    previewEtablissement.innerHTML = `<i class="fas fa-building"></i> ${etablissementName}`;
                } else {
                    previewEtablissement.innerHTML = `<i class="fas fa-building"></i> Aucun établissement`;
                }
                
                // Update status
                const isSubscribed = statusCheckbox?.checked;
                if (isSubscribed) {
                    previewStatus.innerHTML = '<span class="badge-active"><i class="fas fa-circle"></i> Abonné actif</span>';
                } else {
                    previewStatus.innerHTML = '<span class="badge-inactive"><i class="fas fa-circle"></i> Désabonné</span>';
                }
            }
            
            // Event listeners
            if (emailInput) emailInput.addEventListener('input', updatePreview);
            if (prenomInput) prenomInput.addEventListener('input', updatePreview);
            if (nomInput) nomInput.addEventListener('input', updatePreview);
            if (etablissementSelect) etablissementSelect.addEventListener('change', updatePreview);
            if (statusCheckbox) statusCheckbox.addEventListener('change', updatePreview);
            
            // Status toggle label update
            const toggleSwitch = document.querySelector('.toggle-switch input');
            const statusLabel = document.getElementById('statusLabel');
            
            function updateStatusLabel() {
                if (toggleSwitch?.checked) {
                    statusLabel.innerHTML = '<i class="fas fa-check-circle text-success"></i> Abonné actif';
                } else {
                    statusLabel.innerHTML = '<i class="fas fa-times-circle text-danger"></i> Désabonné';
                }
            }
            
            if (toggleSwitch) {
                toggleSwitch.addEventListener('change', updateStatusLabel);
            }
        });
        
        function unsubscribeSubscriber(id) {
            if (confirm('Désabonner cet abonné ?')) {
                $.post(`/mail-subscribers/${id}/unsubscribe`, {
                    _token: '{{ csrf_token() }}'
                }).done(function() {
                    window.location.reload();
                });
            }
        }
        
        function resubscribeSubscriber(id) {
            if (confirm('Réabonner cet abonné ?')) {
                $.post(`/mail-subscribers/${id}/resubscribe`, {
                    _token: '{{ csrf_token() }}'
                }).done(function() {
                    window.location.reload();
                });
            }
        }
        
        function showDeleteConfirmation(id) {
            const deleteModal = new bootstrap.Modal(document.getElementById('deleteConfirmationModal'));
            deleteModal.show();
        }
        
        document.getElementById('confirmDeleteBtn')?.addEventListener('click', function() {
            $.ajax({
                url: `/mail-subscribers/${subscriberId}`,
                type: 'DELETE',
                data: { _token: '{{ csrf_token() }}' },
                success: function() {
                    window.location.href = '{{ route("mail-subscribers.index") }}';
                }
            });
        });
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
        
        /* Cards */
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
        
        .form-control-modern, .form-select-modern {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            font-size: 14px;
            transition: all 0.3s ease;
            background: white;
        }
        
        .form-control-modern:focus, .form-select-modern:focus {
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
        
        /* Status Toggle */
        .status-toggle {
            display: flex;
            align-items: center;
            gap: 16px;
        }
        
        .toggle-switch {
            position: relative;
            display: inline-block;
            width: 52px;
            height: 28px;
        }
        
        .toggle-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }
        
        .toggle-slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: 0.3s;
            border-radius: 34px;
        }
        
        .toggle-slider:before {
            position: absolute;
            content: "";
            height: 22px;
            width: 22px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            transition: 0.3s;
            border-radius: 50%;
        }
        
        input:checked + .toggle-slider {
            background-color: #06b48a;
        }
        
        input:checked + .toggle-slider:before {
            transform: translateX(24px);
        }
        
        .toggle-label {
            font-size: 14px;
            font-weight: 500;
        }
        
        /* Info Box */
        .info-box-modern {
            background: #f8fafc;
            border-radius: 16px;
            padding: 16px;
            margin-top: 24px;
        }
        
        .info-box-header {
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 12px;
            padding-bottom: 8px;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
        }
        
        .info-label {
            color: #64748b;
            font-size: 13px;
        }
        
        .info-value {
            font-weight: 600;
            color: #1e293b;
        }
        
        /* Preview Card */
        .preview-card {
            display: flex;
            align-items: center;
            gap: 20px;
            padding: 20px;
            background: #f8fafc;
            border-radius: 20px;
        }
        
        .preview-avatar {
            width: 70px;
            height: 70px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 28px;
            font-weight: bold;
        }
        
        .preview-info {
            flex: 1;
        }
        
        .preview-name {
            font-size: 18px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 4px;
        }
        
        .preview-email {
            font-size: 13px;
            color: #64748b;
            margin-bottom: 8px;
        }
        
        .preview-etablissement {
            font-size: 12px;
            color: #667eea;
            margin-bottom: 8px;
        }
        
        .badge-active, .badge-inactive {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }
        
        .badge-active {
            background: #e8f5e9;
            color: #06b48a;
        }
        
        .badge-active i {
            font-size: 6px;
        }
        
        .badge-inactive {
            background: #ffebee;
            color: #ef476f;
        }
        
        /* Quick Actions */
        .quick-actions {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        
        .action-quick {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            border: none;
            border-radius: 12px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
        }
        
        .action-quick.unsubscribe {
            background: #fff3e0;
            color: #ffb347;
        }
        
        .action-quick.resubscribe {
            background: #e8f5e9;
            color: #06b48a;
        }
        
        .action-quick.create-campaign {
            background: #eef2ff;
            color: #667eea;
        }
        
        .action-quick.delete {
            background: #ffebee;
            color: #ef476f;
        }
        
        .action-quick:hover {
            transform: translateY(-2px);
        }
        
        /* Activity List */
        .activity-list {
            max-height: 300px;
            overflow-y: auto;
        }
        
        .activity-item {
            display: flex;
            gap: 12px;
            padding: 12px 16px;
            border-bottom: 1px solid #eef2f6;
            transition: background 0.2s ease;
        }
        
        .activity-item:hover {
            background: #f8fafc;
        }
        
        .activity-icon {
            width: 32px;
            height: 32px;
            background: #f1f5f9;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .activity-details {
            flex: 1;
        }
        
        .activity-title {
            font-weight: 500;
            font-size: 13px;
            color: #1e293b;
        }
        
        .activity-date {
            font-size: 11px;
            color: #94a3b8;
            margin-top: 2px;
        }
        
        .activity-campaign {
            font-size: 11px;
            color: #667eea;
            margin-top: 4px;
        }
        
        .empty-activities {
            text-align: center;
            padding: 40px;
            color: #94a3b8;
        }
        
        .empty-activities i {
            font-size: 40px;
            margin-bottom: 10px;
        }
        
        /* Form Actions */
        .form-actions-modern {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            padding-top: 16px;
            border-top: 1px solid #eef2f6;
        }
        
        .btn-save, .btn-preview, .btn-cancel {
            padding: 12px 24px;
            border: none;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }
        
        .btn-save {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
        }
        
        .btn-save:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102,126,234,0.3);
        }
        
        .btn-preview {
            background: #f1f5f9;
            color: #475569;
        }
        
        .btn-preview:hover {
            background: #e2e8f0;
            transform: translateY(-2px);
        }
        
        .btn-cancel {
            background: white;
            color: #64748b;
            border: 1px solid #e2e8f0;
        }
        
        .btn-cancel:hover {
            background: #f8fafc;
            transform: translateY(-2px);
        }
        
        /* Modal */
        .delete-icon {
            font-size: 48px;
            color: #ef476f;
            margin-bottom: 20px;
        }
        
        .delete-title {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 12px;
        }
        
        .delete-message {
            color: #64748b;
            margin-bottom: 20px;
        }
        
        .subscriber-display {
            display: flex;
            align-items: center;
            gap: 20px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 12px;
            margin: 15px 0;
        }
        
        .subscriber-avatar-lg {
            width: 60px;
            height: 60px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 24px;
        }
        
        .subscriber-fullname {
            font-weight: 600;
            font-size: 16px;
            margin-bottom: 4px;
        }
        
        .subscriber-email {
            font-size: 12px;
            color: #64748b;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .page-header-modern {
                padding: 20px;
            }
            
            .page-header-left {
                flex-direction: column;
                text-align: center;
                width: 100%;
            }
            
            .page-title-modern {
                font-size: 22px;
            }
            
            .form-actions-modern {
                flex-direction: column;
            }
            
            .btn-save, .btn-preview, .btn-cancel {
                width: 100%;
                justify-content: center;
            }
            
            .preview-card {
                flex-direction: column;
                text-align: center;
            }
            
            .quick-actions {
                gap: 8px;
            }
        }
    </style>
@endsection