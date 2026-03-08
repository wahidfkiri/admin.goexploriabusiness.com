@extends('layouts.app')

@section('content')
    <!-- MAIN CONTENT -->
   <main class="dashboard-content">
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">
            <span class="page-title-icon"><i class="fas fa-plus-circle"></i></span>
            Nouveau Projet
        </h1>
        
        <div class="page-actions">
            <a href="{{ route('projects.index') }}" class="btn btn-outline-secondary me-2" id="cancelBtn" data-bs-toggle="tooltip" title="Retourner à la liste des projets sans sauvegarder">
                <i class="fas fa-arrow-left me-2"></i>Retour à la liste
            </a>
            <button type="button" class="btn btn-primary" id="saveProjectBtn" data-bs-toggle="tooltip" title="Enregistrer le projet (Ctrl+S)">
                <i class="fas fa-save me-2"></i>Enregistrer
            </button>
        </div>
    </div>

    <!-- Form Container -->
    <form id="projectForm" method="POST" action="{{ route('projects.store') }}" enctype="multipart/form-data">
        @csrf
        
        <!-- Progress Bar -->
        <div class="form-progress-modern">
            <div class="progress-steps">
                <div class="progress-step active" data-step="1" data-bs-toggle="tooltip" title="Étape 1: Informations générales du projet">
                    <div class="step-number">1</div>
                    <div class="step-label">Informations générales</div>
                </div>
                <div class="progress-step" data-step="2" data-bs-toggle="tooltip" title="Étape 2: Informations client et contact">
                    <div class="step-number">2</div>
                    <div class="step-label">Client & Contact</div>
                </div>
                <div class="progress-step" data-step="3" data-bs-toggle="tooltip" title="Étape 3: Dates et budget">
                    <div class="step-number">3</div>
                    <div class="step-label">Planning & Budget</div>
                </div>
                <div class="progress-step" data-step="4" data-bs-toggle="tooltip" title="Étape 4: Configuration avancée">
                    <div class="step-number">4</div>
                    <div class="step-label">Options avancées</div>
                </div>
            </div>
            <div class="progress-bar-modern">
                <div class="progress-fill" id="formProgressFill" style="width: 25%"></div>
            </div>
        </div>

        <!-- Step 1: Informations générales -->
        <div class="form-step active" id="step1">
            <div class="form-card-modern">
                <div class="form-card-header">
                    <h3 class="form-card-title">
                        <i class="fas fa-info-circle me-2"></i>
                        Informations générales
                    </h3>
                    <p class="form-card-subtitle">Les informations de base du projet</p>
                </div>
                
                <div class="form-card-body">
                    <div class="row">
                        <!-- Nom du projet -->
                        <div class="col-md-8 mb-4">
                            <label for="name" class="form-label-modern required-field" data-bs-toggle="tooltip" title="Nom unique et descriptif du projet">
                                Nom du projet
                                <i class="fas fa-question-circle ms-1 text-muted" style="font-size: 0.9rem;"></i>
                            </label>
                            <input type="text" 
                                   class="form-control-modern @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name') }}"
                                   placeholder="Ex: Refonte site web, Application mobile..."
                                   required
                                   data-bs-toggle="tooltip"
                                   title="Choisissez un nom clair et unique pour identifier facilement le projet">
                            @error('name')
                                <div class="invalid-feedback-modern">{{ $message }}</div>
                            @enderror
                            <small class="form-text-modern text-muted">
                                Choisissez un nom descriptif et unique pour le projet
                            </small>
                        </div>

                        <!-- Statut -->
                        <div class="col-md-4 mb-4">
                            <label for="status" class="form-label-modern required-field" data-bs-toggle="tooltip" title="État d'avancement initial du projet">
                                Statut initial
                                <i class="fas fa-question-circle ms-1 text-muted" style="font-size: 0.9rem;"></i>
                            </label>
                            <select class="form-select-modern @error('status') is-invalid @enderror" 
                                    id="status" 
                                    name="status"
                                    required
                                    data-bs-toggle="tooltip"
                                    title="Sélectionnez le statut de démarrage du projet">
                                <option value="">Sélectionner un statut</option>
                                @foreach($statuses as $value => $label)
                                    <option value="{{ $value }}" {{ old('status') == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('status')
                                <div class="invalid-feedback-modern">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <!-- Responsable -->
                        <div class="col-md-6 mb-4">
                            <label for="user_id" class="form-label-modern required-field" data-bs-toggle="tooltip" title="Personne responsable de la gestion du projet">
                                Responsable du projet
                                <i class="fas fa-question-circle ms-1 text-muted" style="font-size: 0.9rem;"></i>
                            </label>
                            <select class="form-select-modern select2-modern @error('user_id') is-invalid @enderror" 
                                    id="user_id" 
                                    name="user_id"
                                    required
                                    data-bs-toggle="tooltip"
                                    title="Choisissez le chef de projet ou responsable principal">
                                <option value="">Sélectionner un responsable</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }} ({{ $user->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <div class="invalid-feedback-modern">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Numéro de contrat -->
                        <div class="col-md-6 mb-4">
                            <label for="contract_number" class="form-label-modern" data-bs-toggle="tooltip" title="Numéro de contrat ou de devis associé">
                                Numéro de contrat
                                <i class="fas fa-question-circle ms-1 text-muted" style="font-size: 0.9rem;"></i>
                            </label>
                            <div class="input-group-modern">
                                <span class="input-group-text"><i class="fas fa-file-contract"></i></span>
                                <input type="text" 
                                       class="form-control-modern @error('contract_number') is-invalid @enderror" 
                                       id="contract_number" 
                                       name="contract_number" 
                                       value="{{ old('contract_number') }}"
                                       placeholder="Ex: CT-2024-001"
                                       list="recentContracts"
                                       data-bs-toggle="tooltip"
                                       title="Format recommandé: CT-ANNÉE-NUMÉRO (ex: CT-2024-001)">
                                <datalist id="recentContracts">
                                    @foreach($recentContracts ?? [] as $contract)
                                        <option value="{{ $contract }}">
                                    @endforeach
                                </datalist>
                            </div>
                            @error('contract_number')
                                <div class="invalid-feedback-modern">{{ $message }}</div>
                            @enderror
                            <small class="form-text-modern text-muted">
                                Laissez vide si pas encore de contrat
                            </small>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="mb-4">
                        <label for="description" class="form-label-modern" data-bs-toggle="tooltip" title="Description détaillée des objectifs et livrables">
                            Description du projet
                            <i class="fas fa-question-circle ms-1 text-muted" style="font-size: 0.9rem;"></i>
                        </label>
                        <textarea class="form-control-modern rich-editor @error('description') is-invalid @enderror" 
                                  id="description" 
                                  name="description" 
                                  rows="5"
                                  data-bs-toggle="tooltip"
                                  title="Décrivez le contexte, les objectifs, le périmètre et les livrables attendus">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback-modern">{{ $message }}</div>
                        @enderror
                        <small class="form-text-modern text-muted">
                            Décrivez les objectifs, le périmètre et les livrables attendus
                        </small>
                    </div>

                    <!-- Tags - Version sans plugin -->
                    <div class="mb-4">
                        <label for="tags" class="form-label-modern" data-bs-toggle="tooltip" title="Mots-clés pour catégoriser et filtrer le projet">
                            Tags
                            <i class="fas fa-question-circle ms-1 text-muted" style="font-size: 0.9rem;"></i>
                        </label>
                        <input type="text" 
                               class="form-control-modern" 
                               id="tags" 
                               name="tags" 
                               value="{{ old('tags') }}"
                               placeholder="Ex: web, mobile, urgent, important"
                               data-bs-toggle="tooltip"
                               title="Séparez les tags par des virgules (ex: web, mobile, urgent)">
                        <small class="form-text-modern text-muted">
                            Séparez les tags par des virgules
                        </small>
                        <div class="tags-container" id="tagsContainer"></div>
                    </div>

                    <div class="form-navigation">
                        <button type="button" class="btn btn-primary next-step" data-next="2" data-bs-toggle="tooltip" title="Passer à l'étape suivante : Client & Contact">
                            Étape suivante
                            <i class="fas fa-arrow-right ms-2"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Step 2: Client & Contact -->
        <div class="form-step" id="step2">
            <div class="form-card-modern">
                <div class="form-card-header">
                    <h3 class="form-card-title">
                        <i class="fas fa-building me-2"></i>
                        Client & Contact
                    </h3>
                    <p class="form-card-subtitle">Informations sur le client et le contact principal</p>
                </div>
                
                <div class="form-card-body">
                    <div class="row">
                        <!-- Client -->
                        <div class="col-md-6 mb-4">
                            <label for="client_id" class="form-label-modern" data-bs-toggle="tooltip" title="Sélectionnez un client existant ou laissez vide pour créer un projet sans client">
                                Client
                                <i class="fas fa-question-circle ms-1 text-muted" style="font-size: 0.9rem;"></i>
                            </label>
                            <select class="form-select-modern select2-modern @error('client_id') is-invalid @enderror" 
                                    id="client_id" 
                                    name="client_id"
                                    data-bs-toggle="tooltip"
                                    title="Les informations client seront automatiquement chargées">
                                <option value="">Sélectionner un client</option>
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}" 
                                            data-ville="{{ $client->ville }}"
                                            data-phone="{{ $client->phone }}"
                                            data-email="{{ $client->email_contact }}"
                                            {{ old('client_id') == $client->id ? 'selected' : '' }}>
                                        {{ $client->name }} - {{ $client->ville }}
                                    </option>
                                @endforeach
                            </select>
                            @error('client_id')
                                <div class="invalid-feedback-modern">{{ $message }}</div>
                            @enderror
                            <small class="form-text-modern text-muted">
                                Laissez vide si pas de client associé
                            </small>
                        </div>

                        <!-- Contact name -->
                        <div class="col-md-6 mb-4">
                            <label for="contact_name" class="form-label-modern" data-bs-toggle="tooltip" title="Nom du contact principal chez le client">
                                Nom du contact
                                <i class="fas fa-question-circle ms-1 text-muted" style="font-size: 0.9rem;"></i>
                            </label>
                            <div class="input-group-modern">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                                <input type="text" 
                                       class="form-control-modern @error('contact_name') is-invalid @enderror" 
                                       id="contact_name" 
                                       name="contact_name" 
                                       value="{{ old('contact_name') }}"
                                       placeholder="Nom du contact principal"
                                       data-bs-toggle="tooltip"
                                       title="Personne à contacter pour ce projet">
                            </div>
                            @error('contact_name')
                                <div class="invalid-feedback-modern">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <!-- Contact phone -->
                        <div class="col-md-6 mb-4">
                            <label for="contact_phone" class="form-label-modern" data-bs-toggle="tooltip" title="Numéro de téléphone du contact">
                                Téléphone du contact
                                <i class="fas fa-question-circle ms-1 text-muted" style="font-size: 0.9rem;"></i>
                            </label>
                            <div class="input-group-modern">
                                <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                <input type="tel" 
                                       class="form-control-modern @error('contact_phone') is-invalid @enderror" 
                                       id="contact_phone" 
                                       name="contact_phone" 
                                       value="{{ old('contact_phone') }}"
                                       placeholder="Ex: 01 23 45 67 89"
                                       data-bs-toggle="tooltip"
                                       title="Format: 01 23 45 67 89 ou +33 1 23 45 67 89">
                            </div>
                            @error('contact_phone')
                                <div class="invalid-feedback-modern">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Contact email -->
                        <div class="col-md-6 mb-4">
                            <label for="contact_email" class="form-label-modern" data-bs-toggle="tooltip" title="Adresse email du contact">
                                Email du contact
                                <i class="fas fa-question-circle ms-1 text-muted" style="font-size: 0.9rem;"></i>
                            </label>
                            <div class="input-group-modern">
                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                <input type="email" 
                                       class="form-control-modern @error('contact_email') is-invalid @enderror" 
                                       id="contact_email" 
                                       name="contact_email" 
                                       value="{{ old('contact_email') }}"
                                       placeholder="contact@exemple.com"
                                       data-bs-toggle="tooltip"
                                       title="Format valide: nom@domaine.com">
                            </div>
                            @error('contact_email')
                                <div class="invalid-feedback-modern">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Client Info Card (hidden by default) -->
                    <div class="client-info-card" id="clientInfoCard" style="display: none;" data-bs-toggle="tooltip" title="Informations détaillées du client sélectionné">
                        <div class="client-info-header">
                            <i class="fas fa-info-circle me-2"></i>
                            Informations client
                        </div>
                        <div class="client-info-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <strong>Ville:</strong> <span id="clientVille"></span>
                                </div>
                                <div class="col-md-4">
                                    <strong>Téléphone:</strong> <span id="clientPhone"></span>
                                </div>
                                <div class="col-md-4">
                                    <strong>Email:</strong> <span id="clientEmail"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-navigation d-flex justify-content-between">
                        <button type="button" class="btn btn-outline-secondary prev-step" data-prev="1" data-bs-toggle="tooltip" title="Retour à l'étape 1 : Informations générales">
                            <i class="fas fa-arrow-left me-2"></i>
                            Étape précédente
                        </button>
                        <button type="button" class="btn btn-primary next-step" data-next="3" data-bs-toggle="tooltip" title="Passer à l'étape 3 : Planning & Budget">
                            Étape suivante
                            <i class="fas fa-arrow-right ms-2"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Step 3: Planning & Budget -->
        <div class="form-step" id="step3">
            <div class="form-card-modern">
                <div class="form-card-header">
                    <h3 class="form-card-title">
                        <i class="fas fa-calendar-alt me-2"></i>
                        Planning & Budget
                    </h3>
                    <p class="form-card-subtitle">Dates et estimations financières</p>
                </div>
                
                <div class="form-card-body">
                    <div class="row">
                        <!-- Start date -->
                        <div class="col-md-6 mb-4">
                            <label for="start_date" class="form-label-modern" data-bs-toggle="tooltip" title="Date de début prévisionnelle du projet">
                                Date de début
                                <i class="fas fa-question-circle ms-1 text-muted" style="font-size: 0.9rem;"></i>
                            </label>
                            <div class="input-group-modern">
                                <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                <input type="date" 
                                       class="form-control-modern datepicker @error('start_date') is-invalid @enderror" 
                                       id="start_date" 
                                       name="start_date" 
                                       value="{{ old('start_date') }}"
                                       data-bs-toggle="tooltip"
                                       title="Sélectionnez la date de démarrage du projet">
                            </div>
                            @error('start_date')
                                <div class="invalid-feedback-modern">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- End date -->
                        <div class="col-md-6 mb-4">
                            <label for="end_date" class="form-label-modern" data-bs-toggle="tooltip" title="Date de fin prévisionnelle du projet">
                                Date de fin prévisionnelle
                                <i class="fas fa-question-circle ms-1 text-muted" style="font-size: 0.9rem;"></i>
                            </label>
                            <div class="input-group-modern">
                                <span class="input-group-text"><i class="fas fa-calendar-check"></i></span>
                                <input type="date" 
                                       class="form-control-modern datepicker @error('end_date') is-invalid @enderror" 
                                       id="end_date" 
                                       name="end_date" 
                                       value="{{ old('end_date') }}"
                                       data-bs-toggle="tooltip"
                                       title="Date estimée de livraison ou de fin du projet">
                            </div>
                            @error('end_date')
                                <div class="invalid-feedback-modern">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <!-- Estimated hours -->
                        <div class="col-md-4 mb-4">
                            <label for="estimated_hours" class="form-label-modern" data-bs-toggle="tooltip" title="Nombre d'heures de travail estimé">
                                Heures estimées
                                <i class="fas fa-question-circle ms-1 text-muted" style="font-size: 0.9rem;"></i>
                            </label>
                            <div class="input-group-modern">
                                <span class="input-group-text"><i class="fas fa-clock"></i></span>
                                <input type="number" 
                                       class="form-control-modern @error('estimated_hours') is-invalid @enderror" 
                                       id="estimated_hours" 
                                       name="estimated_hours" 
                                       value="{{ old('estimated_hours') }}"
                                       min="0"
                                       step="1"
                                       placeholder="Ex: 120"
                                       data-bs-toggle="tooltip"
                                       title="Estimation en heures homme">
                                <span class="input-group-text">heures</span>
                            </div>
                            @error('estimated_hours')
                                <div class="invalid-feedback-modern">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Hourly rate -->
                        <div class="col-md-4 mb-4">
                            <label for="hourly_rate" class="form-label-modern" data-bs-toggle="tooltip" title="Taux horaire facturé au client">
                                Taux horaire
                                <i class="fas fa-question-circle ms-1 text-muted" style="font-size: 0.9rem;"></i>
                            </label>
                            <div class="input-group-modern">
                                <span class="input-group-text"><i class="fas fa-euro-sign"></i></span>
                                <input type="number" 
                                       class="form-control-modern @error('hourly_rate') is-invalid @enderror" 
                                       id="hourly_rate" 
                                       name="hourly_rate" 
                                       value="{{ old('hourly_rate') }}"
                                       min="0"
                                       step="0.01"
                                       placeholder="Ex: 50"
                                       data-bs-toggle="tooltip"
                                       title="Prix par heure de travail">
                                <span class="input-group-text">€/h</span>
                            </div>
                            @error('hourly_rate')
                                <div class="invalid-feedback-modern">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Estimated budget -->
                        <div class="col-md-4 mb-4">
                            <label for="estimated_budget" class="form-label-modern" data-bs-toggle="tooltip" title="Budget total estimé pour le projet">
                                Budget estimé
                                <i class="fas fa-question-circle ms-1 text-muted" style="font-size: 0.9rem;"></i>
                            </label>
                            <div class="input-group-modern">
                                <span class="input-group-text"><i class="fas fa-euro-sign"></i></span>
                                <input type="number" 
                                       class="form-control-modern @error('estimated_budget') is-invalid @enderror" 
                                       id="estimated_budget" 
                                       name="estimated_budget" 
                                       value="{{ old('estimated_budget') }}"
                                       min="0"
                                       step="0.01"
                                       placeholder="Ex: 6000"
                                       data-bs-toggle="tooltip"
                                       title="Budget global estimé (heures × taux ou montant forfaitaire)">
                                <span class="input-group-text">€</span>
                            </div>
                            @error('estimated_budget')
                                <div class="invalid-feedback-modern">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Budget calculator preview -->
                    <div class="budget-preview" id="budgetPreview" style="display: none;" data-bs-toggle="tooltip" title="Calcul automatique basé sur les heures et le taux horaire">
                        <div class="budget-preview-header">
                            <i class="fas fa-calculator me-2"></i>
                            Aperçu du budget
                        </div>
                        <div class="budget-preview-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="budget-item">
                                        <span class="budget-label">Heures:</span>
                                        <span class="budget-value" id="previewHours">0</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="budget-item">
                                        <span class="budget-label">Taux horaire:</span>
                                        <span class="budget-value" id="previewRate">0 €</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="budget-item total">
                                        <span class="budget-label">Total calculé:</span>
                                        <span class="budget-value" id="previewTotal">0 €</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-navigation d-flex justify-content-between">
                        <button type="button" class="btn btn-outline-secondary prev-step" data-prev="2" data-bs-toggle="tooltip" title="Retour à l'étape 2 : Client & Contact">
                            <i class="fas fa-arrow-left me-2"></i>
                            Étape précédente
                        </button>
                        <button type="button" class="btn btn-primary next-step" data-next="4" data-bs-toggle="tooltip" title="Passer à l'étape 4 : Options avancées">
                            Étape suivante
                            <i class="fas fa-arrow-right ms-2"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Step 4: Options avancées -->
        <div class="form-step" id="step4">
            <div class="form-card-modern">
                <div class="form-card-header">
                    <h3 class="form-card-title">
                        <i class="fas fa-cog me-2"></i>
                        Options avancées
                    </h3>
                    <p class="form-card-subtitle">Configuration supplémentaire</p>
                </div>
                
                <div class="form-card-body">
                    <div class="row">
                        <!-- Priorité -->
                        <div class="col-md-6 mb-4">
                            <label for="priority" class="form-label-modern" data-bs-toggle="tooltip" title="Niveau d'importance du projet">
                                Priorité
                                <i class="fas fa-question-circle ms-1 text-muted" style="font-size: 0.9rem;"></i>
                            </label>
                            <select class="form-select-modern" id="priority" name="priority" data-bs-toggle="tooltip" title="Définit l'ordre de traitement des projets">
                                <option value="low" data-bs-toggle="tooltip" title="Priorité basse - projet standard">Basse</option>
                                <option value="medium" selected data-bs-toggle="tooltip" title="Priorité moyenne - projet normal">Moyenne</option>
                                <option value="high" data-bs-toggle="tooltip" title="Priorité haute - projet important">Haute</option>
                                <option value="urgent" data-bs-toggle="tooltip" title="Urgent - projet prioritaire">Urgente</option>
                            </select>
                            <small class="form-text-modern text-muted">
                                Définit l'importance relative du projet
                            </small>
                        </div>

                        <!-- Statut actif -->
                        <div class="col-md-6 mb-4">
                            <label class="form-label-modern d-block" data-bs-toggle="tooltip" title="Active ou désactive le projet">État du projet</label>
                            <div class="toggle-switch-modern">
                                <input type="checkbox" 
                                       name="is_active" 
                                       id="is_active" 
                                       value="1" 
                                       {{ old('is_active', '1') == '1' ? 'checked' : '' }}
                                       class="toggle-checkbox"
                                       data-bs-toggle="tooltip"
                                       title="Cochez pour rendre le projet actif et visible">
                                <label for="is_active" class="toggle-label">
                                    <span class="toggle-inner"></span>
                                    <span class="toggle-switch"></span>
                                </label>
                                <span class="toggle-text ms-2" id="activeStatusText">
                                    Projet actif
                                </span>
                            </div>
                            <small class="form-text-modern text-muted">
                                Un projet inactif n'apparaîtra pas dans les listes par défaut
                            </small>
                        </div>
                    </div>

                    <!-- Notifications -->
                    <div class="mb-4">
                        <label class="form-label-modern" data-bs-toggle="tooltip" title="Configurer les notifications automatiques">Notifications</label>
                        <div class="notification-options">
                            <div class="form-check-modern">
                                <input type="checkbox" class="form-check-input" id="notify_team" name="notify_team" checked
                                       data-bs-toggle="tooltip" title="Envoyer une notification à tous les membres de l'équipe">
                                <label class="form-check-label" for="notify_team">
                                    <i class="fas fa-users me-2"></i>
                                    Notifier l'équipe à la création
                                </label>
                            </div>
                            <div class="form-check-modern">
                                <input type="checkbox" class="form-check-input" id="notify_client" name="notify_client"
                                       data-bs-toggle="tooltip" title="Envoyer un email au client pour l'informer de la création du projet">
                                <label class="form-check-label" for="notify_client">
                                    <i class="fas fa-building me-2"></i>
                                    Notifier le client
                                </label>
                            </div>
                            <div class="form-check-modern">
                                <input type="checkbox" class="form-check-input" id="create_calendar" name="create_calendar" checked
                                       data-bs-toggle="tooltip" title="Créer automatiquement des événements dans le calendrier">
                                <label class="form-check-label" for="create_calendar">
                                    <i class="fas fa-calendar-plus me-2"></i>
                                    Créer des événements dans le calendrier
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Fichiers joints -->
                    <div class="mb-4">
                        <label for="attachments" class="form-label-modern" data-bs-toggle="tooltip" title="Ajouter des documents au projet">
                            Fichiers joints
                            <i class="fas fa-question-circle ms-1 text-muted" style="font-size: 0.9rem;"></i>
                        </label>
                        <div class="file-upload-modern">
                            <input type="file" 
                                   class="file-input" 
                                   id="attachments" 
                                   name="attachments[]" 
                                   multiple
                                   accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.png"
                                   data-bs-toggle="tooltip"
                                   title="Formats acceptés: PDF, DOC, XLS, JPG (max 10MB par fichier)">
                            <div class="file-upload-area" data-bs-toggle="tooltip" title="Cliquez ou glissez-déposez des fichiers">
                                <i class="fas fa-cloud-upload-alt fa-2x mb-2"></i>
                                <p>Glissez-déposez vos fichiers ici ou cliquez pour sélectionner</p>
                                <small class="text-muted">PDF, DOC, XLS, JPG (max 10MB)</small>
                            </div>
                        </div>
                        <div id="fileList" class="file-list mt-2"></div>
                    </div>

                    <div class="form-navigation d-flex justify-content-between">
                        <button type="button" class="btn btn-outline-secondary prev-step" data-prev="3" data-bs-toggle="tooltip" title="Retour à l'étape 3 : Planning & Budget">
                            <i class="fas fa-arrow-left me-2"></i>
                            Étape précédente
                        </button>
                        <button type="submit" class="btn btn-success" id="submitBtn" data-bs-toggle="tooltip" title="Créer le projet avec toutes les informations saisies">
                            <i class="fas fa-check-circle me-2"></i>
                            Créer le projet
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay" style="display: none;">
        <div class="loading-content">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Chargement...</span>
            </div>
            <p class="mt-3">Création du projet en cours...</p>
        </div>
    </div>
</main>

<!-- Ajouter le script pour initialiser les tooltips -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialiser tous les tooltips Bootstrap
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl, {
            placement: 'auto',
            trigger: 'hover focus',
            html: false,
            delay: { show: 500, hide: 100 }
        });
    });

    // Actualiser les tooltips après les changements AJAX/dynamiques
    function refreshTooltips() {
        tooltipList.forEach(tooltip => {
            if (tooltip._element && !document.body.contains(tooltip._element)) {
                tooltip.dispose();
            }
        });
        
        tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]:not([data-bs-original-title])'));
        tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl, {
                placement: 'auto',
                trigger: 'hover focus',
                html: false,
                delay: { show: 500, hide: 100 }
            });
        });
    }

    // Observer les changements dans le DOM pour les éléments dynamiques
    const observer = new MutationObserver(refreshTooltips);
    observer.observe(document.body, { childList: true, subtree: true });
});
</script>
<!-- Bibliothèques CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
    /* Form Progress Bar */
    .form-progress-modern {
        margin-bottom: 30px;
        padding: 20px;
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        border: 1px solid #eaeaea;
    }

    .progress-steps {
        display: flex;
        justify-content: space-between;
        margin-bottom: 15px;
        position: relative;
    }

    .progress-step {
        display: flex;
        flex-direction: column;
        align-items: center;
        flex: 1;
        position: relative;
        z-index: 1;
    }

    .progress-step .step-number {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #e9ecef;
        color: #6c757d;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        margin-bottom: 8px;
        transition: all 0.3s ease;
    }

    .progress-step.active .step-number {
        background: linear-gradient(135deg, #45b7d1, #3a56e4);
        color: white;
        box-shadow: 0 4px 8px rgba(69, 183, 209, 0.3);
    }

    .progress-step.completed .step-number {
        background: linear-gradient(135deg, #06b48a, #049a72);
        color: white;
    }

    .progress-step .step-label {
        font-size: 0.9rem;
        font-weight: 500;
        color: #6c757d;
        text-align: center;
    }

    .progress-step.active .step-label {
        color: #45b7d1;
        font-weight: 600;
    }

    .progress-bar-modern {
        height: 4px;
        background: #e9ecef;
        border-radius: 2px;
        overflow: hidden;
    }

    .progress-fill {
        height: 100%;
        background: linear-gradient(90deg, #45b7d1, #3a56e4);
        transition: width 0.3s ease;
    }

    /* Form Steps */
    .form-step {
        display: none;
    }

    .form-step.active {
        display: block;
        animation: fadeIn 0.5s ease;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Form Cards */
    .form-card-modern {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        border: 1px solid #eaeaea;
        overflow: hidden;
    }

    .form-card-header {
        padding: 20px 25px;
        border-bottom: 1px solid #eaeaea;
        background: #f8f9fa;
    }

    .form-card-title {
        font-size: 1.2rem;
        font-weight: 600;
        color: #333;
        margin-bottom: 5px;
    }

    .form-card-subtitle {
        font-size: 0.9rem;
        color: #6c757d;
        margin-bottom: 0;
    }

    .form-card-body {
        padding: 25px;
    }

    /* Form Controls */
    .form-label-modern {
        display: block;
        margin-bottom: 8px;
        font-weight: 500;
        color: #333;
    }

    .required-field:after {
        content: " *";
        color: #ef476f;
        font-weight: bold;
    }

    .form-control-modern {
        width: 100%;
        padding: 10px 15px;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        font-size: 0.95rem;
        transition: all 0.3s ease;
    }

    .form-control-modern:focus {
        border-color: #45b7d1;
        outline: none;
        box-shadow: 0 0 0 3px rgba(69, 183, 209, 0.1);
    }

    .form-control-modern.is-invalid {
        border-color: #ef476f;
    }

    .invalid-feedback-modern {
        color: #ef476f;
        font-size: 0.85rem;
        margin-top: 5px;
    }

    .form-select-modern {
        width: 100%;
        padding: 10px 15px;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        font-size: 0.95rem;
        background-color: white;
        cursor: pointer;
    }

    .input-group-modern {
        display: flex;
        align-items: center;
    }

    .input-group-modern .form-control-modern {
        flex: 1;
        border-radius: 0 8px 8px 0;
    }

    .input-group-modern .input-group-text {
        padding: 10px 15px;
        background: #f8f9fa;
        border: 1px solid #e0e0e0;
        border-radius: 8px 0 0 8px;
        color: #6c757d;
    }

    /* Tags Container */
    .tags-container {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 10px;
    }

    .tag-item {
        background: linear-gradient(135deg, #45b7d1, #3a56e4);
        color: white;
        padding: 4px 10px;
        border-radius: 15px;
        font-size: 0.85rem;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .tag-item .remove-tag {
        cursor: pointer;
        font-size: 1rem;
        line-height: 1;
    }

    .tag-item .remove-tag:hover {
        color: #ef476f;
    }

    /* Toggle Switch */
    .toggle-switch-modern {
        display: flex;
        align-items: center;
    }

    .toggle-checkbox {
        display: none;
    }

    .toggle-label {
        position: relative;
        display: inline-block;
        width: 60px;
        height: 30px;
        background-color: #e9ecef;
        border-radius: 15px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .toggle-switch {
        position: absolute;
        top: 3px;
        left: 3px;
        width: 24px;
        height: 24px;
        background-color: white;
        border-radius: 50%;
        transition: all 0.3s ease;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .toggle-checkbox:checked + .toggle-label {
        background-color: #06b48a;
    }

    .toggle-checkbox:checked + .toggle-label .toggle-switch {
        left: 33px;
    }

    /* Client Info Card */
    .client-info-card {
        margin-top: 20px;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        overflow: hidden;
    }

    .client-info-header {
        padding: 10px 15px;
        background: #f8f9fa;
        border-bottom: 1px solid #e0e0e0;
        font-weight: 600;
        color: #333;
    }

    .client-info-body {
        padding: 15px;
    }

    /* Budget Preview */
    .budget-preview {
        margin-top: 20px;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        overflow: hidden;
        background: #f8f9fa;
    }

    .budget-preview-header {
        padding: 10px 15px;
        background: linear-gradient(135deg, #45b7d1, #3a9bb8);
        color: white;
        font-weight: 600;
    }

    .budget-preview-body {
        padding: 15px;
    }

    .budget-item {
        display: flex;
        justify-content: space-between;
        padding: 8px 0;
        border-bottom: 1px dashed #e0e0e0;
    }

    .budget-item.total {
        border-bottom: none;
        font-weight: 600;
        color: #45b7d1;
    }

    /* File Upload */
    .file-upload-modern {
        border: 2px dashed #e0e0e0;
        border-radius: 8px;
        padding: 30px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .file-upload-modern:hover {
        border-color: #45b7d1;
        background: #f8f9fa;
    }

    .file-input {
        display: none;
    }

    .file-list {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .file-item {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 8px 12px;
        background: #f8f9fa;
        border-radius: 6px;
        font-size: 0.9rem;
    }

    .file-item i {
        color: #45b7d1;
    }

    .file-item .remove-file {
        color: #ef476f;
        cursor: pointer;
    }

    /* Notification Options */
    .notification-options {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .form-check-modern {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px;
        background: #f8f9fa;
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    .form-check-modern:hover {
        background: #e9ecef;
    }

    .form-check-modern .form-check-input {
        width: 18px;
        height: 18px;
        cursor: pointer;
    }

    .form-check-modern .form-check-label {
        cursor: pointer;
        flex: 1;
    }

    /* Form Navigation */
    .form-navigation {
        margin-top: 30px;
    }

    /* Loading Overlay */
    .loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255,255,255,0.9);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
    }

    .loading-content {
        text-align: center;
        padding: 30px;
        background: white;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    }

    /* Select2 custom styling */
    .select2-container--classic .select2-selection--single {
        height: 42px;
        padding: 5px 15px;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
    }
    
    .select2-container--classic .select2-selection--single .select2-selection__rendered {
        line-height: 30px;
        color: #333;
    }
    
    .select2-container--classic .select2-selection--single .select2-selection__arrow {
        height: 40px;
        right: 10px;
    }

    /* CKEditor customization */
    .ck-editor__editable {
        min-height: 150px;
        border-radius: 0 0 8px 8px !important;
    }
    
    .ck-toolbar {
        border-radius: 8px 8px 0 0 !important;
        border: 1px solid #e0e0e0 !important;
    }

    /* Character counter */
    .character-counter {
        margin-top: 5px;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .progress-steps {
            flex-direction: column;
            gap: 15px;
        }
        
        .progress-step {
            flex-direction: row;
            gap: 15px;
        }
        
        .progress-step .step-number {
            margin-bottom: 0;
        }
        
        .form-navigation .btn {
            width: 100%;
            margin-bottom: 10px;
        }
        
        .form-navigation.d-flex {
            flex-direction: column;
        }
    }
</style>
<!-- Bibliothèques JavaScript -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.ckeditor.com/ckeditor5/34.0.0/classic/ckeditor.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
$(document).ready(function() {
    // Initialisation des variables
    let currentStep = 1;
    const totalSteps = 4;
    let formDirty = false;
    let autoSaveTimer;

    // Initialisation de Select2
    if ($.fn.select2) {
        $('.select2-modern').select2({
            theme: 'classic',
            width: '100%',
            placeholder: 'Sélectionner...',
            allowClear: true
        });
    }

    // Initialisation de CKEditor
    if (typeof ClassicEditor !== 'undefined') {
        ClassicEditor
            .create(document.querySelector('#description'), {
                toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', 'insertTable', '|', 'undo', 'redo'],
                heading: {
                    options: [
                        { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                        { model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1' },
                        { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' }
                    ]
                }
            })
            .then(editor => {
                window.descriptionEditor = editor;
            })
            .catch(error => {
                console.error('CKEditor error:', error);
            });
    }

    // Gestion des tags sans plugin
    const tagsInput = $('#tags');
    const tagsContainer = $('#tagsContainer');
    
    function updateTagsFromInput() {
        const tagsValue = tagsInput.val();
        const tags = tagsValue.split(',')
            .map(tag => tag.trim())
            .filter(tag => tag !== '');
        
        displayTags(tags);
    }
    
    function displayTags(tags) {
        tagsContainer.empty();
        
        tags.forEach(tag => {
            const tagElement = $(`
                <span class="tag-item">
                    ${tag}
                    <span class="remove-tag" data-tag="${tag}">&times;</span>
                </span>
            `);
            tagsContainer.append(tagElement);
        });
    }
    
    function addTag(tag) {
        if (!tag) return;
        
        const currentTags = tagsInput.val();
        const tagsArray = currentTags ? currentTags.split(',').map(t => t.trim()) : [];
        
        if (!tagsArray.includes(tag)) {
            tagsArray.push(tag);
            tagsInput.val(tagsArray.join(', '));
            displayTags(tagsArray);
        }
    }
    
    function removeTag(tagToRemove) {
        const currentTags = tagsInput.val();
        let tagsArray = currentTags ? currentTags.split(',').map(t => t.trim()) : [];
        tagsArray = tagsArray.filter(tag => tag !== tagToRemove);
        tagsInput.val(tagsArray.join(', '));
        displayTags(tagsArray);
    }
    
    tagsInput.on('keypress', function(e) {
        if (e.key === 'Enter' || e.key === ',') {
            e.preventDefault();
            const newTag = $(this).val().split(',').pop().trim();
            if (newTag) {
                addTag(newTag);
                $(this).val('');
            }
        }
    });
    
    tagsInput.on('blur', function() {
        const value = $(this).val();
        if (value && !value.includes(',')) {
            addTag(value);
            $(this).val('');
        }
    });
    
    $(document).on('click', '.remove-tag', function() {
        const tagToRemove = $(this).data('tag');
        removeTag(tagToRemove);
    });
    
    // Afficher les tags initiaux
    updateTagsFromInput();

    // Navigation par étapes
    function updateProgressBar() {
        const progress = (currentStep / totalSteps) * 100;
        $('#formProgressFill').css('width', progress + '%');
        
        $('.progress-step').removeClass('active completed');
        for (let i = 1; i <= totalSteps; i++) {
            if (i < currentStep) {
                $(`.progress-step[data-step="${i}"]`).addClass('completed');
            } else if (i === currentStep) {
                $(`.progress-step[data-step="${i}"]`).addClass('active');
            }
        }
    }

    function showStep(step) {
        $(`.form-step`).removeClass('active');
        $(`#step${step}`).addClass('active');
        currentStep = step;
        updateProgressBar();
        
        $('html, body').animate({
            scrollTop: $('.form-progress-modern').offset().top - 20
        }, 300);
    }

    $('.next-step').click(function() {
        const nextStep = $(this).data('next');
        if (validateStep(currentStep)) {
            showStep(nextStep);
        }
    });

    $('.prev-step').click(function() {
        const prevStep = $(this).data('prev');
        showStep(prevStep);
    });

    // Validation des étapes
    function validateStep(step) {
        switch(step) {
            case 1:
                if (!$('#name').val()) {
                    showNotification('error', 'Le nom du projet est obligatoire');
                    $('#name').focus();
                    return false;
                }
                if (!$('#status').val()) {
                    showNotification('error', 'Le statut est obligatoire');
                    return false;
                }
                if (!$('#user_id').val()) {
                    showNotification('error', 'Le responsable est obligatoire');
                    return false;
                }
                break;
            case 3:
                const startDate = $('#start_date').val();
                const endDate = $('#end_date').val();
                
                if (startDate && endDate && new Date(endDate) < new Date(startDate)) {
                    showNotification('error', 'La date de fin doit être postérieure à la date de début');
                    return false;
                }
                break;
        }
        return true;
    }

    // Client selection auto-fill
    $('#client_id').on('change', function() {
        const selected = $(this).find('option:selected');
        
        if (selected.val()) {
            const ville = selected.data('ville');
            const phone = selected.data('phone');
            const email = selected.data('email');
            
            $('#clientVille').text(ville || 'Non renseigné');
            $('#clientPhone').text(phone || 'Non renseigné');
            $('#clientEmail').text(email || 'Non renseigné');
            $('#clientInfoCard').slideDown();
            
            if (!$('#contact_name').val()) {
                const clientName = selected.text().split(' - ')[0];
                $('#contact_name').val(clientName);
            }
            if (!$('#contact_phone').val() && phone) {
                $('#contact_phone').val(phone);
            }
            if (!$('#contact_email').val() && email) {
                $('#contact_email').val(email);
            }
        } else {
            $('#clientInfoCard').slideUp();
        }
    });

    // Budget calculator
    function calculateBudget() {
        const hours = parseFloat($('#estimated_hours').val()) || 0;
        const rate = parseFloat($('#hourly_rate').val()) || 0;
        const calculatedTotal = hours * rate;
        
        $('#previewHours').text(hours);
        $('#previewRate').text(rate.toFixed(2) + ' €');
        $('#previewTotal').text(calculatedTotal.toFixed(2) + ' €');
        
        if (hours > 0 || rate > 0) {
            $('#budgetPreview').slideDown();
            
            const manualBudget = parseFloat($('#estimated_budget').val()) || 0;
            if (calculatedTotal > 0 && manualBudget === 0) {
                $('#estimated_budget').val(calculatedTotal.toFixed(2));
            }
        } else {
            $('#budgetPreview').slideUp();
        }
    }

    $('#estimated_hours, #hourly_rate').on('input', calculateBudget);

    // Date validation
    $('#end_date').on('change', function() {
        const startDate = $('#start_date').val();
        const endDate = $(this).val();
        
        if (startDate && endDate && new Date(endDate) < new Date(startDate)) {
            showNotification('warning', 'La date de fin est antérieure à la date de début');
        }
    });

    // File upload handling
    $('.file-upload-modern').on('click', function() {
        $('#attachments').click();
    });

    $('#attachments').on('change', function(e) {
        const files = e.target.files;
        const fileList = $('#fileList');
        fileList.empty();
        
        for (let i = 0; i < files.length; i++) {
            const file = files[i];
            const fileSize = (file.size / 1024).toFixed(2);
            
            fileList.append(`
                <div class="file-item" data-index="${i}">
                    <i class="fas fa-file"></i>
                    <span>${file.name} (${fileSize} KB)</span>
                    <i class="fas fa-times remove-file"></i>
                </div>
            `);
        }
    });

    $(document).on('click', '.remove-file', function() {
        $(this).closest('.file-item').remove();
        
        if ($('#fileList .file-item').length === 0) {
            $('#attachments').val('');
        }
    });

    // Drag & drop file upload
    const dropArea = $('.file-upload-modern');

    dropArea.on('dragenter dragover', function(e) {
        e.preventDefault();
        e.stopPropagation();
        $(this).css('border-color', '#45b7d1').css('background', '#f8f9fa');
    });

    dropArea.on('dragleave', function(e) {
        e.preventDefault();
        e.stopPropagation();
        $(this).css('border-color', '#e0e0e0').css('background', 'white');
    });

    dropArea.on('drop', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        $(this).css('border-color', '#e0e0e0').css('background', 'white');
        
        const files = e.originalEvent.dataTransfer.files;
        $('#attachments')[0].files = files;
        $('#attachments').trigger('change');
    });

    // Toggle switch text
    $('#is_active').on('change', function() {
        const text = $(this).is(':checked') ? 'Projet actif' : 'Projet inactif';
        $('#activeStatusText').text(text);
    });

    // Form submission
    $('#projectForm').on('submit', function(e) {
        e.preventDefault();
        
        if (!validateAllSteps()) {
            return;
        }
        
        $('#loadingOverlay').fadeIn();
        $('#submitBtn').prop('disabled', true);
        
        const formData = new FormData(this);
        
        // Ajouter les données CKEditor
        if (window.descriptionEditor) {
            formData.set('description', window.descriptionEditor.getData());
        }
        
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                $('#loadingOverlay').fadeOut();
                
                if (response.success) {
                    showNotification('success', 'Projet créé avec succès !');
                    localStorage.removeItem('project_draft');
                    localStorage.removeItem('project_draft_time');
                    
                    setTimeout(function() {
                        window.location.href = response.redirect || '{{ route("projects.index") }}';
                    }, 1500);
                } else {
                    $('#submitBtn').prop('disabled', false);
                    showNotification('error', response.message || 'Erreur lors de la création');
                }
            },
            error: function(xhr) {
                $('#loadingOverlay').fadeOut();
                $('#submitBtn').prop('disabled', false);
                
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    let errorMessage = 'Veuillez corriger les erreurs suivantes:\n';
                    
                    for (let field in errors) {
                        errorMessage += `- ${errors[field].join('\n')}\n`;
                        $(`#${field}`).addClass('is-invalid');
                    }
                    
                    showNotification('error', errorMessage);
                } else {
                    showNotification('error', 'Erreur de connexion au serveur');
                }
                
                console.error('Error:', xhr.responseText);
            }
        });
    });

    function validateAllSteps() {
        if (!$('#name').val()) {
            showNotification('error', 'Le nom du projet est obligatoire');
            showStep(1);
            $('#name').focus();
            return false;
        }
        
        if (!$('#status').val()) {
            showNotification('error', 'Le statut est obligatoire');
            showStep(1);
            return false;
        }
        
        if (!$('#user_id').val()) {
            showNotification('error', 'Le responsable est obligatoire');
            showStep(1);
            return false;
        }
        
        const startDate = $('#start_date').val();
        const endDate = $('#end_date').val();
        
        if (startDate && endDate && new Date(endDate) < new Date(startDate)) {
            showNotification('error', 'La date de fin doit être postérieure à la date de début');
            showStep(3);
            return false;
        }
        
        return true;
    }

    // Notification system
    function showNotification(type, message) {
        $('.notification-toast').remove();
        
        const icons = {
            success: 'fa-check-circle',
            error: 'fa-exclamation-circle',
            warning: 'fa-exclamation-triangle',
            info: 'fa-info-circle'
        };
        
        const colors = {
            success: '#06b48a',
            error: '#ef476f',
            warning: '#ffd166',
            info: '#45b7d1'
        };
        
        const notification = `
            <div class="notification-toast" style="
                position: fixed;
                top: 20px;
                right: 20px;
                background: white;
                border-radius: 8px;
                box-shadow: 0 10px 30px rgba(0,0,0,0.15);
                padding: 15px 20px;
                display: flex;
                align-items: center;
                gap: 12px;
                z-index: 10000;
                animation: slideInRight 0.3s ease;
                border-left: 4px solid ${colors[type]};
                max-width: 400px;
            ">
                <i class="fas ${icons[type]}" style="color: ${colors[type]}; font-size: 1.5rem;"></i>
                <div style="flex: 1;">
                    <div style="font-weight: 600; margin-bottom: 4px; color: #333;">
                        ${type === 'success' ? 'Succès' : type === 'error' ? 'Erreur' : type === 'warning' ? 'Attention' : 'Information'}
                    </div>
                    <div style="color: #666; font-size: 0.9rem;">${message}</div>
                </div>
                <i class="fas fa-times" style="color: #999; cursor: pointer;" onclick="this.closest('.notification-toast').remove()"></i>
            </div>
        `;
        
        $('body').append(notification);
        
        setTimeout(function() {
            $('.notification-toast').fadeOut(300, function() {
                $(this).remove();
            });
        }, 5000);
    }

    // Auto-save draft
    function autoSaveDraft() {
        const formData = $('#projectForm').serialize();
        
        if (window.descriptionEditor) {
            const descriptionData = '&description=' + encodeURIComponent(window.descriptionEditor.getData());
            localStorage.setItem('project_draft', formData + descriptionData);
        } else {
            localStorage.setItem('project_draft', formData);
        }
        
        localStorage.setItem('project_draft_time', new Date().toISOString());
        
        const indicator = $('<div class="auto-save-indicator">Brouillon sauvegardé</div>');
        indicator.css({
            position: 'fixed',
            bottom: '20px',
            right: '20px',
            background: '#06b48a',
            color: 'white',
            padding: '8px 15px',
            borderRadius: '20px',
            fontSize: '0.85rem',
            zIndex: '9999',
            opacity: '0',
            transition: 'opacity 0.3s ease'
        });
        
        $('body').append(indicator);
        
        setTimeout(function() {
            indicator.css('opacity', '1');
        }, 100);
        
        setTimeout(function() {
            indicator.css('opacity', '0');
            setTimeout(function() {
                indicator.remove();
            }, 300);
        }, 2000);
    }

    $('#projectForm').on('input change', function() {
        formDirty = true;
        clearTimeout(autoSaveTimer);
        autoSaveTimer = setTimeout(autoSaveDraft, 30000);
    });

    // Load draft
    function loadDraft() {
        const draft = localStorage.getItem('project_draft');
        const draftTime = localStorage.getItem('project_draft_time');
        
        if (draft && draftTime) {
            const timeDiff = (new Date() - new Date(draftTime)) / (1000 * 60);
            
            if (timeDiff < 60) {
                if (confirm('Un brouillon non sauvegardé a été trouvé. Voulez-vous le restaurer ?')) {
                    const params = new URLSearchParams(draft);
                    
                    for (let [key, value] of params.entries()) {
                        const input = $(`[name="${key}"]`);
                        if (input.length) {
                            if (input.is('select')) {
                                input.val(value).trigger('change');
                            } else if (input.is('textarea') && key === 'description' && window.descriptionEditor) {
                                window.descriptionEditor.setData(value);
                            } else {
                                input.val(value);
                            }
                        }
                    }
                    
                    $('#tags').trigger('input');
                    showNotification('info', 'Brouillon restauré avec succès');
                }
            }
        }
    }

    loadDraft();

    // Keyboard shortcuts
    $(document).on('keydown', function(e) {
        if ((e.ctrlKey || e.metaKey) && e.key === 's') {
            e.preventDefault();
            $('#projectForm').submit();
        }
        
        if ((e.ctrlKey || e.metaKey) && e.key === 'ArrowRight') {
            e.preventDefault();
            const nextBtn = $(`.next-step[data-next="${currentStep + 1}"]`);
            if (nextBtn.length && validateStep(currentStep)) {
                showStep(currentStep + 1);
            }
        }
        
        if ((e.ctrlKey || e.metaKey) && e.key === 'ArrowLeft') {
            e.preventDefault();
            if (currentStep > 1) {
                showStep(currentStep - 1);
            }
        }
    });

    // Datepickers with min dates
    const today = new Date().toISOString().split('T')[0];
    $('#start_date').attr('min', today);
    
    $('#start_date').on('change', function() {
        $('#end_date').attr('min', $(this).val());
    });

    // Cancel confirmation
    $('#cancelBtn').on('click', function(e) {
        if (formDirty && !confirm('Êtes-vous sûr de vouloir annuler ? Les données non sauvegardées seront perdues.')) {
            e.preventDefault();
        }
    });

    // Character counter
    function updateCharCount() {
        const content = window.descriptionEditor ? window.descriptionEditor.getData() : $('#description').val();
        const count = content.replace(/<[^>]*>/g, '').length;
        const maxChars = 5000;
        
        $('#charCount').text(count);
        
        if (count > maxChars) {
            $('#charCount').css('color', '#ef476f');
        } else {
            $('#charCount').css('color', 'inherit');
        }
    }

    if (window.descriptionEditor) {
        window.descriptionEditor.model.document.on('change:data', updateCharCount);
    } else {
        $('#description').on('input', updateCharCount);
    }

    $('#description').after(`
        <div class="character-counter text-muted small mt-1">
            <span id="charCount">0</span> / 5000 caractères
        </div>
    `);

    updateCharCount();

    // Project name suggestion
    $('#client_id').on('change', function() {
        const clientName = $(this).find('option:selected').text().split(' - ')[0];
        const currentName = $('#name').val();
        
        if (clientName && !currentName) {
            $('#name').val('Projet - ' + clientName);
        }
    });

    // Before unload warning
    $(window).on('beforeunload', function() {
        if (formDirty && !$('#loadingOverlay').is(':visible')) {
            return 'Vous avez des modifications non sauvegardées. Êtes-vous sûr de vouloir quitter ?';
        }
    });

    $('#projectForm').on('submit', function() {
        formDirty = false;
    });

    console.log('Project creation form initialized successfully');
});
</script>
@endsection