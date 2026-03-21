@extends('layouts.app')

@section('content')
    
    <main class="dashboard-content">
        <!-- Page Header -->
        <div class="page-header-modern">
            <div class="page-header-content">
                <div class="page-header-left">
                    <div class="page-header-icon">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <div>
                        <h1 class="page-title-modern">Nouvel abonné</h1>
                        <p class="page-subtitle-modern">Ajoutez un nouvel abonné à votre liste de diffusion</p>
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
                        <span class="card-badge">Nouveau</span>
                    </div>
                    <div class="card-body-modern">
                        <form action="{{ route('mail-subscribers.store') }}" method="POST" id="subscriberForm">
                            @csrf
                            
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
                                           value="{{ old('email') }}" 
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
                                               value="{{ old('prenom') }}" 
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
                                               value="{{ old('nom') }}" 
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
                                        <option value="{{ $etablissement->id }}" {{ old('etablissement_id') == $etablissement->id ? 'selected' : '' }}>
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
                                        <input type="checkbox" name="is_subscribed" value="1" {{ old('is_subscribed', 1) ? 'checked' : '' }}>
                                        <span class="toggle-slider"></span>
                                    </label>
                                    <span class="toggle-label" id="statusLabel">
                                        <i class="fas fa-check-circle text-success"></i> Abonné actif
                                    </span>
                                </div>
                                <small class="form-text-modern">Désactivez pour désabonner immédiatement cet utilisateur</small>
                            </div>
                            
                            <!-- Actions -->
                            <div class="form-actions-modern mt-4">
                                <button type="submit" class="btn-save">
                                    <i class="fas fa-save"></i>
                                    <span>Enregistrer l'abonné</span>
                                </button>
                                <button type="button" class="btn-save-continue" id="saveAndContinueBtn">
                                    <i class="fas fa-plus-circle"></i>
                                    <span>Enregistrer et continuer</span>
                                </button>
                                <a href="{{ route('mail-subscribers.index') }}" class="btn-cancel">
                                    <i class="fas fa-times"></i>
                                    <span>Annuler</span>
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Colonne droite - Informations et aperçu -->
            <div class="col-lg-4">
                <!-- Carte Aperçu -->
                <div class="card-modern">
                    <div class="card-header-modern">
                        <div class="card-header-icon">
                            <i class="fas fa-eye"></i>
                        </div>
                        <h3 class="card-title-modern">Aperçu</h3>
                    </div>
                    <div class="card-body-modern">
                        <div class="preview-card" id="previewCard">
                            <div class="preview-avatar" id="previewAvatar">
                                ?
                            </div>
                            <div class="preview-info">
                                <div class="preview-name" id="previewName">
                                    Nouvel abonné
                                </div>
                                <div class="preview-email" id="previewEmail">
                                    email@exemple.com
                                </div>
                                <div class="preview-etablissement" id="previewEtablissement">
                                    <i class="fas fa-building"></i> Aucun établissement
                                </div>
                                <div class="preview-status" id="previewStatus">
                                    <span class="badge-active"><i class="fas fa-circle"></i> Abonné actif</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Carte Statistiques -->
                <div class="card-modern">
                    <div class="card-header-modern">
                        <div class="card-header-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <h3 class="card-title-modern">Statistiques</h3>
                    </div>
                    <div class="card-body-modern">
                        <div class="stats-mini-grid">
                            <div class="stat-mini-item">
                                <div class="stat-mini-icon" style="background: linear-gradient(135deg, #667eea, #764ba2);">
                                    <i class="fas fa-users"></i>
                                </div>
                                <div class="stat-mini-info">
                                    <span class="stat-mini-value">{{ $stats['total'] ?? 0 }}</span>
                                    <span class="stat-mini-label">Total abonnés</span>
                                </div>
                            </div>
                            <div class="stat-mini-item">
                                <div class="stat-mini-icon" style="background: linear-gradient(135deg, #06b48a, #049a72);">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div class="stat-mini-info">
                                    <span class="stat-mini-value">{{ $stats['active'] ?? 0 }}</span>
                                    <span class="stat-mini-label">Abonnés actifs</span>
                                </div>
                            </div>
                            <div class="stat-mini-item">
                                <div class="stat-mini-icon" style="background: linear-gradient(135deg, #ffd166, #ffb347);">
                                    <i class="fas fa-calendar-plus"></i>
                                </div>
                                <div class="stat-mini-info">
                                    <span class="stat-mini-value">{{ $stats['this_month'] ?? 0 }}</span>
                                    <span class="stat-mini-label">Nouveaux ce mois</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Carte Astuces -->
                <div class="card-modern card-tips">
                    <div class="card-header-modern">
                        <div class="card-header-icon">
                            <i class="fas fa-lightbulb"></i>
                        </div>
                        <h3 class="card-title-modern">Astuces</h3>
                    </div>
                    <div class="card-body-modern">
                        <ul class="tips-list">
                            <li>
                                <i class="fas fa-check-circle text-success"></i>
                                <span>Les emails doivent être uniques dans la base</span>
                            </li>
                            <li>
                                <i class="fas fa-check-circle text-success"></i>
                                <span>Le prénom et nom sont optionnels mais recommandés</span>
                            </li>
                            <li>
                                <i class="fas fa-check-circle text-success"></i>
                                <span>L'association à un établissement facilite la segmentation</span>
                            </li>
                            <li>
                                <i class="fas fa-check-circle text-success"></i>
                                <span>Vous pouvez importer plusieurs abonnés via CSV</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        // Preview update on input
        document.addEventListener('DOMContentLoaded', function() {
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
                updateStatusLabel();
            }
            
            // Save and continue
            const saveAndContinueBtn = document.getElementById('saveAndContinueBtn');
            const form = document.getElementById('subscriberForm');
            
            if (saveAndContinueBtn) {
                saveAndContinueBtn.addEventListener('click', function() {
                    // Add hidden input to indicate save and continue
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'save_and_continue';
                    input.value = '1';
                    form.appendChild(input);
                    form.submit();
                });
            }
            
            // Initial preview
            updatePreview();
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
        
        .invalid-feedback {
            color: #ef476f;
            font-size: 12px;
            margin-top: 4px;
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
            background: linear-gradient(135deg, #667eea, #764ba2);
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
        
        .preview-status {
            margin-top: 8px;
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
        
        /* Stats Mini Grid */
        .stats-mini-grid {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
        
        .stat-mini-item {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 12px;
            background: #f8fafc;
            border-radius: 12px;
        }
        
        .stat-mini-icon {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 20px;
        }
        
        .stat-mini-info {
            display: flex;
            flex-direction: column;
        }
        
        .stat-mini-value {
            font-size: 24px;
            font-weight: 700;
            color: #1e293b;
            line-height: 1;
        }
        
        .stat-mini-label {
            font-size: 12px;
            color: #64748b;
        }
        
        /* Tips List */
        .tips-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .tips-list li {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 10px 0;
            border-bottom: 1px solid #eef2f6;
        }
        
        .tips-list li:last-child {
            border-bottom: none;
        }
        
        .tips-list i {
            margin-top: 2px;
        }
        
        .tips-list span {
            font-size: 13px;
            color: #475569;
            line-height: 1.5;
        }
        
        /* Form Actions */
        .form-actions-modern {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            padding-top: 16px;
            border-top: 1px solid #eef2f6;
        }
        
        .btn-save, .btn-save-continue, .btn-cancel {
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
        
        .btn-save-continue {
            background: #f1f5f9;
            color: #475569;
        }
        
        .btn-save-continue:hover {
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
            
            .btn-save, .btn-save-continue, .btn-cancel {
                width: 100%;
                justify-content: center;
            }
            
            .preview-card {
                flex-direction: column;
                text-align: center;
            }
        }
    </style>
@endsection