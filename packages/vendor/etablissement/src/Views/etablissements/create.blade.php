@extends('layouts.app')

@section('content')
<main class="dashboard-content">
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">
            <span class="page-title-icon"><i class="fas fa-building"></i></span>
            Créer un Nouvel Établissement
        </h1>
        
        <div class="page-actions">
            <a href="{{ route('etablissements.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Retour à la liste
            </a>
        </div>
    </div>
    
    <!-- Main Form Card -->
    <div class="main-card-modern">
        <div class="card-header-modern">
            <h3 class="card-title-modern">
                <i class="fas fa-plus-circle me-2"></i>Formulaire en 4 Étapes
            </h3>
        </div>
        
        <div class="card-body-modern">
            <!-- Stepper -->
            <div class="stepper-container">
                <div class="stepper-progress">
                    <div class="stepper-progress-bar" id="stepperProgressBar"></div>
                </div>
                <div class="stepper-steps">
                    <div class="stepper-step active" data-step="1">
                        <div class="step-icon">1</div>
                        <div class="step-label">Infos Générales</div>
                    </div>
                    <div class="stepper-step" data-step="2">
                        <div class="step-icon">2</div>
                        <div class="step-label">Localisation</div>
                    </div>
                    <div class="stepper-step" data-step="3">
                        <div class="step-icon">3</div>
                        <div class="step-label">Activités</div>
                    </div>
                    <div class="stepper-step" data-step="4">
                        <div class="step-icon">4</div>
                        <div class="step-label">Accès Login</div>
                    </div>
                </div>
            </div>
            
            <!-- Form -->
            <form action="{{ route('etablissements.store') }}" method="POST" id="createEtablissementForm">
                @csrf
                
                <!-- Step 1: Informations Générales -->
                <div class="form-step active" data-step="1">
                    <h4 class="step-title"><i class="fas fa-info-circle me-2"></i>Informations Générales</h4>
                    
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="name" class="form-label-modern">Nom *</label>
                            <input type="text" class="form-control-modern" id="name" name="name" 
                                   placeholder="Ex: Hôtel Plaza, Restaurant Le Gourmet..." required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label-modern">Téléphone</label>
                            <input type="text" class="form-control-modern" id="phone" name="phone" 
                                   placeholder="Ex: +33 1 23 45 67 89">
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="fax" class="form-label-modern">Fax</label>
                            <input type="text" class="form-control-modern" id="fax" name="fax" 
                                   placeholder="Ex: +33 1 23 45 67 90">
                        </div>
                    </div>

                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="website" class="form-label-modern">Email Contact</label>
                        <div class="input-group">
                            <input type="email" class="form-control-modern" id="email_contact" name="email_contact" 
                                   placeholder="test@example.com">
                        </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="website" class="form-label-modern">Site Web</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-globe"></i></span>
                            <input type="text" class="form-control-modern" id="website" name="website" 
                                   placeholder="Ex: https://www.mon-etablissement.com">
                        </div>
                        </div>
                    </div>
                    
                    <div class="step-actions">
                        <button type="button" class="btn btn-secondary" onclick="nextStep(2)">
                            Suivant <i class="fas fa-arrow-right ms-2"></i>
                        </button>
                    </div>
                </div>
                
                <!-- Step 2: Localisation -->
                <div class="form-step" data-step="2">
                    <h4 class="step-title"><i class="fas fa-map-marker-alt me-2"></i>Localisation</h4>
                    
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="ville_search" class="form-label-modern">Ville *</label>
                            <input type="text" class="form-control-modern" id="ville_search" name="ville_search" 
                                   placeholder="Commencez à taper pour rechercher une ville..." 
                                   autocomplete="off">
                            <input type="hidden" id="ville" name="ville">
                            <input type="hidden" id="region_id" name="region_id">
                            <input type="hidden" id="province_id" name="province_id">
                            <input type="hidden" id="country_id" name="country_id">
                            
                            <div id="ville_suggestions" class="suggestions-dropdown" style="display: none;">
                                <!-- Les suggestions seront chargées ici via AJAX -->
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="adresse" class="form-label-modern">Adresse complète *</label>
                            <textarea class="form-control-modern" id="adresse" name="adresse" 
                                      rows="2" placeholder="Ex: 123 Avenue des Champs-Élysées..." required></textarea>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="zip_code" class="form-label-modern">Code postal *</label>
                            <input type="text" class="form-control-modern" id="zip_code" name="zip_code" 
                                   placeholder="Ex: 75001, 69002..." required>
                        </div>
                    </div>
                    
                    <div class="step-actions">
                        <button type="button" class="btn btn-outline-secondary" onclick="prevStep(1)">
                            <i class="fas fa-arrow-left me-2"></i>Précédent
                        </button>
                        <button type="button" class="btn btn-secondary" onclick="nextStep(3)">
                            Suivant <i class="fas fa-arrow-right ms-2"></i>
                        </button>
                    </div>
                </div>
                
                <!-- Step 3: Activités -->
                <div class="form-step" data-step="3">
                    <h4 class="step-title"><i class="fas fa-tasks me-2"></i>Activités</h4>
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="activities" class="form-label-modern">Sélectionnez les activités *</label>
                                <div class="activities-multiselect-container">
                                    <div class="activities-search-container">
                                        <input type="text" class="form-control-modern activities-search" 
                                               placeholder="Rechercher une activité..." 
                                               id="activitiesSearch">
                                        <div class="activities-search-actions">
                                            <button type="button" class="btn btn-sm btn-outline-secondary" 
                                                    onclick="selectAllActivities()">
                                                <i class="fas fa-check-double"></i> Tout sélectionner
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-secondary" 
                                                    onclick="clearAllActivities()">
                                                <i class="fas fa-times"></i> Tout désélectionner
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <div class="activities-selection-container">
                                        <div class="activities-available">
                                            <div class="activities-list-header">
                                                <h6>Activités disponibles</h6>
                                                <span class="badge bg-info" id="availableCount">0</span>
                                            </div>
                                            <div class="activities-list" id="availableActivities">
                                                <!-- Les activités seront chargées via API -->
                                                <div class="loading-activities">
                                                    <div class="spinner-border spinner-border-sm text-primary"></div>
                                                    <span class="ms-2">Chargement des activités...</span>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="activities-selection-controls">
                                            <button type="button" class="btn btn-primary btn-sm" 
                                                    onclick="addSelectedActivities()">
                                                <i class="fas fa-arrow-right"></i>
                                            </button>
                                            <button type="button" class="btn btn-primary btn-sm" 
                                                    onclick="removeSelectedActivities()">
                                                <i class="fas fa-arrow-left"></i>
                                            </button>
                                        </div>
                                        
                                        <div class="activities-selected">
                                            <div class="activities-list-header">
                                                <h6>Activités sélectionnées</h6>
                                                <span class="badge bg-success" id="selectedCount">0</span>
                                            </div>
                                            <div class="activities-list" id="selectedActivities">
                                                <!-- Les activités sélectionnées apparaîtront ici -->
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <select multiple="multiple" class="form-control" name="activities[]" 
                                            id="activitiesSelect" style="display: none;">
                                        <!-- Les options sélectionnées seront ajoutées ici dynamiquement -->
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="step-actions">
                        <button type="button" class="btn btn-outline-secondary" onclick="prevStep(2)">
                            <i class="fas fa-arrow-left me-2"></i>Précédent
                        </button>
                        <button type="button" class="btn btn-secondary" onclick="nextStep(4)">
                            Suivant <i class="fas fa-arrow-right ms-2"></i>
                        </button>
                    </div>
                </div>
                
                <!-- Step 4: Accès Login -->
                <div class="form-step" data-step="4">
                    <h4 class="step-title"><i class="fas fa-user-circle me-2"></i>Création du compte</h4>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="user_name" class="form-label-modern">Nom complet *</label>
                            <input type="text" class="form-control-modern" id="user_name" name="user_name" 
                                   placeholder="Ex: Jean Dupont" required>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="user_email" class="form-label-modern">Email *</label>
                            <input type="email" class="form-control-modern" id="user_email" name="user_email" 
                                   placeholder="Ex: contact@etablissement.com" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label-modern">Mot de passe *</label>
                            <div class="input-group">
                                <input type="password" class="form-control-modern" id="password" name="password" 
                                       placeholder="Minimum 8 caractères" required>
                                <button type="button" class="btn btn-outline-secondary" 
                                        onclick="togglePassword('password')">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <small class="form-text text-muted">Le mot de passe doit contenir au moins 8 caractères</small>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="password_confirmation" class="form-label-modern">Confirmer le mot de passe *</label>
                            <div class="input-group">
                                <input type="password" class="form-control-modern" id="password_confirmation" 
                                       name="password_confirmation" placeholder="Répétez le mot de passe" required>
                                <button type="button" class="btn btn-outline-secondary" 
                                        onclick="togglePassword('password_confirmation')">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="password_confirmation" class="form-label-modern">Type du compte *</label>
                            <div class="input-group">
                                <select class="form-control-modern" id="role_id" 
                                       name="role_id" required>
                                       @foreach($roles as $key)
                                       <option value="{{$key->id}}">{{$key->libelle}}</option>
                                       @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label-modern">Statut du compte</label>
                        <div class="status-toggle">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" checked>
                                <label class="form-check-label" for="is_active">
                                    <span class="status-label">Compte actif</span>
                                    <small class="text-muted d-block">L'utilisateur pourra se connecter immédiatement</small>
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="step-actions">
                        <button type="button" class="btn btn-outline-secondary" onclick="prevStep(3)">
                            <i class="fas fa-arrow-left me-2"></i>Précédent
                        </button>
                        <button type="submit" class="btn btn-primary btn-pulse">
                            <i class="fas fa-save me-2"></i>Créer l'établissement
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</main>

<style>
    .input-group{
        flex-wrap: nowrap !important;
    }
    /* Stepper Styles */
    .stepper-container {
        margin-bottom: 40px;
    }
    
    .stepper-progress {
        height: 4px;
        background: #e9ecef;
        border-radius: 2px;
        margin: 20px 0 40px;
        position: relative;
    }
    
    .stepper-progress-bar {
        height: 100%;
        background: #45b7d1;
        border-radius: 2px;
        width: 25%;
        transition: width 0.3s ease;
    }
    
    .stepper-steps {
        display: flex;
        justify-content: space-between;
        position: relative;
    }
    
    .stepper-step {
        text-align: center;
        position: relative;
        z-index: 2;
    }
    
    .stepper-step.active .step-icon {
        background: #45b7d1;
        color: white;
        transform: scale(1.1);
        box-shadow: 0 0 0 4px rgba(69, 183, 209, 0.2);
    }
    
    .stepper-step.completed .step-icon {
        background: #28a745;
        color: white;
    }
    
    .step-icon {
        width: 40px;
        height: 40px;
        background: #e9ecef;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 10px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .step-label {
        font-size: 0.9rem;
        font-weight: 500;
        color: #6c757d;
    }
    
    .stepper-step.active .step-label {
        color: #45b7d1;
        font-weight: 600;
    }
    
    /* Form Steps */
    .form-step {
        display: none;
        animation: fadeIn 0.5s ease;
    }
    
    .form-step.active {
        display: block;
    }
    
    .step-title {
        font-size: 1.3rem;
        font-weight: 600;
        color: #333;
        margin-bottom: 25px;
        padding-bottom: 10px;
        border-bottom: 2px solid #f0f0f0;
    }
    
    .step-actions {
        padding-top: 30px;
        margin-top: 30px;
        border-top: 1px solid #eaeaea;
        display: flex;
        justify-content: space-between;
    }
    
    /* Ville Search Suggestions */
    .suggestions-dropdown {
        position: absolute;
        z-index: 1000;
        width: 100%;
        max-height: 200px;
        overflow-y: auto;
        background: white;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        margin-top: 5px;
    }
    
    .suggestion-item {
        padding: 10px 15px;
        cursor: pointer;
        border-bottom: 1px solid #f0f0f0;
        transition: background 0.2s ease;
    }
    
    .suggestion-item:hover {
        background: #f8f9fa;
    }
    
    .suggestion-item.active {
        background: #45b7d1;
        color: white;
    }
    
    .suggestion-optgroup {
        background: #f8f9fa;
        font-weight: 600;
        color: #6c757d;
        padding: 8px 15px;
        font-size: 0.85rem;
        border-bottom: 1px solid #dee2e6;
    }
    
    /* Activities Multiselect */
    .activities-multiselect-container {
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 20px;
        background: #f8f9fa;
    }
    
    .activities-search-container {
        margin-bottom: 20px;
    }
    
    .activities-search-actions {
        display: flex;
        gap: 10px;
        margin-top: 10px;
    }
    
    .activities-selection-container {
        display: flex;
        gap: 20px;
        min-height: 400px;
    }
    
    .activities-available,
    .activities-selected {
        flex: 1;
        background: white;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        overflow: hidden;
    }
    
    .activities-list-header {
        background: #f8f9fa;
        padding: 12px 15px;
        border-bottom: 1px solid #dee2e6;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .activities-list {
        height: 350px;
        overflow-y: auto;
        padding: 10px;
    }
    
    .activities-selection-controls {
        display: flex;
        flex-direction: column;
        justify-content: center;
        gap: 10px;
    }
    
    .activity-item {
        padding: 10px 12px;
        margin-bottom: 8px;
        background: white;
        border: 1px solid #eaeaea;
        border-radius: 6px;
        cursor: pointer;
        display: flex;
        align-items: center;
        transition: all 0.2s ease;
    }
    
    .activity-item:hover {
        border-color: #45b7d1;
        background: #f0f9ff;
    }
    
    .activity-item.selected {
        background: #e8f5e8;
        border-color: #28a745;
    }
    
    .activity-checkbox {
        margin-right: 10px;
    }
    
    .activity-name {
        font-size: 0.95rem;
        color: #333;
        flex: 1;
    }
    
    .loading-activities {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100%;
        color: #6c757d;
    }
    
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @media (max-width: 768px) {
        .activities-selection-container {
            flex-direction: column;
        }
        
        .activities-selection-controls {
            flex-direction: row;
            justify-content: center;
            margin: 10px 0;
        }
        
        .step-actions {
            flex-direction: column;
            gap: 10px;
        }
        
        .step-actions .btn {
            width: 100%;
        }
    }
</style>
<script>
    // Variables globales
    let currentStep = 1;
    let allActivities = [];
    let filteredActivities = [];
    let selectedActivities = [];
    let debounceTimer;
    let villeSearchAbortController = null;
    
    document.addEventListener('DOMContentLoaded', function() {
        // Initialiser le stepper
        updateStepper();
        
        // Charger les activités via API
        loadActivities();
        
        // Initialiser la recherche de ville
        initVilleSearch();
        
        // Gérer la soumission du formulaire
        const form = document.getElementById('createEtablissementForm');
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Validation finale
            if (validateAllSteps()) {
                // Désactiver le bouton de soumission
                const submitBtn = form.querySelector('button[type="submit"]');
                submitBtn.disabled = true;
                submitBtn.innerHTML = `
                    <div class="spinner-border spinner-border-sm me-2" role="status">
                        <span class="visually-hidden">Chargement...</span>
                    </div>
                    Création en cours...
                `;
                
                // Soumettre le formulaire
                this.submit();
            }
        });
        
        // Navigation rapide avec clavier
        document.addEventListener('keydown', function(e) {
            // Ctrl + N pour passer à l'étape suivante
            if (e.ctrlKey && e.key === 'n') {
                e.preventDefault();
                nextStep(currentStep + 1);
            }
            
            // Ctrl + P pour revenir à l'étape précédente
            if (e.ctrlKey && e.key === 'p') {
                e.preventDefault();
                prevStep(currentStep - 1);
            }
        });
    });
    
    // Navigation entre les étapes
    function nextStep(step) {
        if (validateStep(currentStep)) {
            currentStep = step;
            updateStepper();
            showStep(step);
            scrollToStep();
        }
    }
    
    function prevStep(step) {
        currentStep = step;
        updateStepper();
        showStep(step);
        scrollToStep();
    }
    
    function showStep(step) {
        // Masquer toutes les étapes
        document.querySelectorAll('.form-step').forEach(stepEl => {
            stepEl.classList.remove('active');
        });
        
        // Afficher l'étape courante
        const currentStepEl = document.querySelector(`.form-step[data-step="${step}"]`);
        if (currentStepEl) {
            currentStepEl.classList.add('active');
            
            // Focus sur le premier champ de l'étape
            setTimeout(() => {
                const firstInput = currentStepEl.querySelector('input, select, textarea');
                if (firstInput) {
                    firstInput.focus();
                }
            }, 100);
        }
    }
    
    function updateStepper() {
        // Mettre à jour la barre de progression
        const progress = ((currentStep - 1) / 3) * 100;
        const progressBar = document.getElementById('stepperProgressBar');
        if (progressBar) {
            progressBar.style.width = progress + '%';
        }
        
        // Mettre à jour les étapes
        document.querySelectorAll('.stepper-step').forEach((stepEl, index) => {
            const stepNum = parseInt(stepEl.dataset.step);
            stepEl.classList.remove('active', 'completed');
            
            if (stepNum < currentStep) {
                stepEl.classList.add('completed');
            } else if (stepNum === currentStep) {
                stepEl.classList.add('active');
            }
        });
    }
    
    function scrollToStep() {
        const stepEl = document.querySelector(`.form-step[data-step="${currentStep}"]`);
        if (stepEl) {
            stepEl.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    }
    
    // Validation des étapes
    function validateStep(step) {
        let isValid = true;
        
        switch(step) {
            case 1:
                const name = document.getElementById('name');
                if (!name.value.trim()) {
                    showError(name, 'Le nom est obligatoire');
                    isValid = false;
                } else {
                    clearError(name);
                }
                break;
                
            case 2:
                const ville = document.getElementById('ville');
                const villeSearch = document.getElementById('ville_search');
                const adresse = document.getElementById('adresse');
                const zipCode = document.getElementById('zip_code');
                
                if (!ville.value) {
                    showError(villeSearch, 'Veuillez sélectionner une ville');
                    isValid = false;
                } else {
                    clearError(villeSearch);
                }
                
                if (!adresse.value.trim()) {
                    showError(adresse, 'L\'adresse est obligatoire');
                    isValid = false;
                } else {
                    clearError(adresse);
                }
                
                if (!zipCode.value.trim()) {
                    showError(zipCode, 'Le code postal est obligatoire');
                    isValid = false;
                } else {
                    clearError(zipCode);
                }
                break;
                
            case 3:
                if (selectedActivities.length === 0) {
                    showNotification('Veuillez sélectionner au moins une activité', 'warning');
                    isValid = false;
                }
                break;
                
            case 4:
                const userName = document.getElementById('user_name');
                const userEmail = document.getElementById('user_email');
                const password = document.getElementById('password');
                const passwordConfirmation = document.getElementById('password_confirmation');
                
                if (!userName.value.trim()) {
                    showError(userName, 'Le nom est obligatoire');
                    isValid = false;
                } else {
                    clearError(userName);
                }
                
                if (!userEmail.value.trim() || !isValidEmail(userEmail.value)) {
                    showError(userEmail, 'Veuillez entrer un email valide');
                    isValid = false;
                } else {
                    clearError(userEmail);
                }
                
                if (password.value.length < 8) {
                    showError(password, 'Le mot de passe doit contenir au moins 8 caractères');
                    isValid = false;
                } else {
                    clearError(password);
                }
                
                if (password.value !== passwordConfirmation.value) {
                    showError(passwordConfirmation, 'Les mots de passe ne correspondent pas');
                    isValid = false;
                } else {
                    clearError(passwordConfirmation);
                }
                break;
        }
        
        return isValid;
    }
    
    function validateAllSteps() {
        for (let i = 1; i <= 4; i++) {
            if (!validateStep(i)) {
                // Aller à l'étape avec erreur
                currentStep = i;
                updateStepper();
                showStep(i);
                scrollToStep();
                return false;
            }
        }
        return true;
    }
    
    function showError(input, message) {
        // Retirer les bordures précédentes
        input.classList.remove('is-valid');
        input.classList.add('is-invalid');
        
        // Ajouter le message d'erreur
        let errorDiv = input.parentNode.querySelector('.invalid-feedback');
        if (!errorDiv) {
            errorDiv = document.createElement('div');
            errorDiv.className = 'invalid-feedback';
            input.parentNode.appendChild(errorDiv);
        }
        errorDiv.textContent = message;
        
        // Focus sur le champ en erreur
        input.focus();
    }
    
    function clearError(input) {
        input.classList.remove('is-invalid');
        input.classList.add('is-valid');
        
        const errorDiv = input.parentNode.querySelector('.invalid-feedback');
        if (errorDiv) {
            errorDiv.remove();
        }
    }
    
    function showNotification(message, type = 'info') {
        // Créer une notification toast
        const toast = document.createElement('div');
        toast.className = `toast align-items-center text-bg-${type} border-0`;
        toast.setAttribute('role', 'alert');
        toast.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">
                    <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'warning' ? 'exclamation-triangle' : 'info-circle'} me-2"></i>
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        `;
        
        document.body.appendChild(toast);
        const bsToast = new bootstrap.Toast(toast);
        bsToast.show();
        
        // Nettoyer après fermeture
        toast.addEventListener('hidden.bs.toast', function() {
            toast.remove();
        });
    }
    
    function isValidEmail(email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    }
    
    // Recherche de ville avec AJAX Laravel
    function initVilleSearch() {
        const villeSearch = document.getElementById('ville_search');
        const suggestions = document.getElementById('ville_suggestions');
        
        if (!villeSearch) return;
        
        villeSearch.addEventListener('input', function() {
            const searchTerm = this.value.trim();
            
            // Annuler la requête précédente
            if (villeSearchAbortController) {
                villeSearchAbortController.abort();
            }
            
            // Annuler le timer précédent
            clearTimeout(debounceTimer);
            
            if (searchTerm.length < 2) {
                suggestions.style.display = 'none';
                return;
            }
            
            // Afficher un indicateur de chargement
            suggestions.innerHTML = `
                <div class="suggestion-item">
                    <div class="d-flex align-items-center">
                        <div class="spinner-border spinner-border-sm me-2"></div>
                        <span>Recherche en cours...</span>
                    </div>
                </div>
            `;
            suggestions.style.display = 'block';
            
            // Debounce pour éviter trop d'appels API
            debounceTimer = setTimeout(() => {
                fetchVilles(searchTerm);
            }, 300);
        });
        
        villeSearch.addEventListener('blur', function() {
            setTimeout(() => {
                if (!document.activeElement || !document.activeElement.closest('#ville_suggestions')) {
                    suggestions.style.display = 'none';
                }
            }, 200);
        });
        
        villeSearch.addEventListener('focus', function() {
            if (this.value.trim().length >= 2) {
                fetchVilles(this.value.trim());
            }
        });
        
        // Touche Échap pour fermer les suggestions
        villeSearch.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                suggestions.style.display = 'none';
            }
        });
        
        // Navigation au clavier dans les suggestions
        villeSearch.addEventListener('keydown', function(e) {
            if (e.key === 'ArrowDown' || e.key === 'ArrowUp') {
                e.preventDefault();
                const items = suggestions.querySelectorAll('.suggestion-item:not(.suggestion-optgroup)');
                if (items.length === 0) return;
                
                const currentIndex = Array.from(items).indexOf(document.activeElement);
                
                if (e.key === 'ArrowDown') {
                    if (currentIndex < items.length - 1) {
                        items[currentIndex + 1].focus();
                    } else {
                        items[0].focus();
                    }
                } else if (e.key === 'ArrowUp') {
                    if (currentIndex > 0) {
                        items[currentIndex - 1].focus();
                    } else {
                        items[items.length - 1].focus();
                    }
                }
            }
        });
    }
    
    function fetchVilles(searchTerm) {
        const suggestions = document.getElementById('ville_suggestions');
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        
        // Créer un nouvel abort controller
        villeSearchAbortController = new AbortController();
        
        // Envoyer la requête AJAX à l'API Laravel
        fetch('{{ route("api.villes.search") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                search: searchTerm,
                limit: 20
            }),
            signal: villeSearchAbortController.signal
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Erreur réseau: ' + response.status);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                displayVilleSuggestions(data.villes);
            } else {
                showVilleSearchError(data.message || 'Erreur lors de la recherche');
            }
        })
        .catch(error => {
            if (error.name === 'AbortError') {
                return; // Requête annulée volontairement
            }
            console.error('Erreur:', error);
            showVilleSearchError('Erreur de connexion au serveur');
        })
        .finally(() => {
            villeSearchAbortController = null;
        });
    }
    
    function displayVilleSuggestions(villes) {
        const suggestions = document.getElementById('ville_suggestions');
        suggestions.innerHTML = '';
        
        if (!villes || villes.length === 0) {
            suggestions.innerHTML = '<div class="suggestion-item text-muted">Aucun résultat trouvé</div>';
            suggestions.style.display = 'block';
            return;
        }
        
        // Grouper par type
        const grouped = {};
        villes.forEach(ville => {
            const type = ville.type || 'other';
            const typeName = getTypeDisplayName(type);
            
            if (!grouped[typeName]) {
                grouped[typeName] = [];
            }
            grouped[typeName].push(ville);
        });
        
        // Afficher par type
        Object.keys(grouped).forEach(typeName => {
            const optgroup = document.createElement('div');
            optgroup.className = 'suggestion-optgroup';
            optgroup.innerHTML = `
                <i class="${getTypeIcon(typeName)} me-2"></i>
                ${typeName}
            `;
            suggestions.appendChild(optgroup);
            
            grouped[typeName].forEach(ville => {
                const item = document.createElement('div');
                item.className = 'suggestion-item';
                item.tabIndex = 0;
                
                // Construire le texte affiché
                let displayText = ville.name;
                let details = [];
                
                if (ville.zip_code) details.push(ville.zip_code);
                if (ville.region_name && ville.type !== 'region') details.push(ville.region_name);
                if (ville.province_name) details.push(ville.province_name);
                if (ville.country_name) details.push(ville.country_name);
                
                item.innerHTML = `
                    <div class="d-flex justify-content-between align-items-center">
                        <div style="flex: 1;">
                            <div class="fw-semibold">${ville.name}</div>
                            <div class="small text-muted mt-1">
                                ${details.join(' • ')}
                            </div>
                        </div>
                        <span class="badge ${getTypeBadgeClass(ville.type)} ms-2">
                            ${getTypeShortName(ville.type)}
                        </span>
                    </div>
                `;
                
                // Stocker toutes les données
                Object.keys(ville).forEach(key => {
                    if (ville[key] !== null && ville[key] !== undefined) {
                        item.dataset[key] = ville[key];
                    }
                });
                
                item.addEventListener('click', function() {
                    selectVille(this);
                });
                
                item.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter' || e.key === ' ') {
                        e.preventDefault();
                        selectVille(this);
                    }
                });
                
                suggestions.appendChild(item);
            });
        });
        
        suggestions.style.display = 'block';
    }
    
    function getTypeDisplayName(type) {
        const types = {
            'city': 'Villes',
            'region': 'Régions',
            'province': 'Provinces',
            'country': 'Pays',
            'other': 'Autres'
        };
        return types[type] || 'Autres';
    }
    
    function getTypeShortName(type) {
        const types = {
            'city': 'Ville',
            'region': 'Région',
            'province': 'Province',
            'country': 'Pays',
            'other': 'Autre'
        };
        return types[type] || 'Autre';
    }
    
    function getTypeIcon(typeName) {
        const icons = {
            'Villes': 'fas fa-city',
            'Régions': 'fas fa-mountain',
            'Provinces': 'fas fa-map-marker-alt',
            'Pays': 'fas fa-globe-americas',
            'Autres': 'fas fa-search-location'
        };
        return icons[typeName] || 'fas fa-map-marker-alt';
    }
    
    function getTypeBadgeClass(type) {
        const classes = {
            'city': 'bg-primary',
            'region': 'bg-success',
            'province': 'bg-warning text-dark',
            'country': 'bg-info',
            'other': 'bg-secondary'
        };
        return classes[type] || 'bg-secondary';
    }
    
    function showVilleSearchError(message) {
        const suggestions = document.getElementById('ville_suggestions');
        suggestions.innerHTML = `
            <div class="suggestion-item text-danger">
                <div class="d-flex align-items-center">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <span>${message}</span>
                </div>
            </div>
        `;
        suggestions.style.display = 'block';
    }
    
    function selectVille(element) {
        const villeSearch = document.getElementById('ville_search');
        const villeInput = document.getElementById('ville');
        const regionInput = document.getElementById('region_id');
        const provinceInput = document.getElementById('province_id');
        const countryInput = document.getElementById('country_id');
        
        // Construire le nom complet
        let fullName = element.dataset.name;
        const details = [];
        
        if (element.dataset.zip_code) details.push(element.dataset.zip_code);
        if (element.dataset.region_name && element.dataset.type !== 'region') details.push(element.dataset.region_name);
        if (element.dataset.province_name) details.push(element.dataset.province_name);
        if (element.dataset.country_name) details.push(element.dataset.country_name);
        
        if (details.length > 0) {
            fullName += ' (' + details.join(', ') + ')';
        }
        
        // Mettre à jour les champs
        villeSearch.value = fullName;
        villeInput.value = element.dataset.name;
        regionInput.value = element.dataset.region_id || '';
        provinceInput.value = element.dataset.province_id || '';
        countryInput.value = element.dataset.country_id || '';
        
        // Effacer les erreurs
        clearError(villeSearch);
        
        // Remplir automatiquement le code postal si disponible
        const zipCodeInput = document.getElementById('zip_code');
        if (element.dataset.zip_code && element.dataset.type === 'city' && !zipCodeInput.value) {
            zipCodeInput.value = element.dataset.zip_code;
        }
        
        // Cacher les suggestions
        document.getElementById('ville_suggestions').style.display = 'none';
        
        // Afficher une notification
        showNotification(`Ville sélectionnée: ${element.dataset.name}`, 'success');
        
        // Focus sur le champ suivant
        setTimeout(() => {
            document.getElementById('adresse').focus();
        }, 100);
    }
    
    // Gestion des activités avec AJAX Laravel
    function loadActivities() {
        const availableContainer = document.getElementById('availableActivities');
        if (!availableContainer) return;
        
        availableContainer.innerHTML = `
            <div class="loading-activities">
                <div class="d-flex align-items-center justify-content-center py-5">
                    <div class="spinner-border spinner-border-sm text-primary me-2"></div>
                    <span>Chargement des activités...</span>
                </div>
            </div>
        `;
        
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        
        // Envoyer la requête AJAX à l'API Laravel pour les activités
        fetch('{{ route("api.activities.index") }}', {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Erreur lors du chargement des activités: ' + response.status);
            }
            return response.json();
        })
        .then(data => {
            if (data.success && data.activities) {
                allActivities = data.activities.map(activity => ({
                    id: activity.id,
                    name: activity.name,
                    description: activity.description || '',
                    category: activity.category || '',
                    selected: false
                }));
                
                filteredActivities = [...allActivities];
                renderActivities();
                showNotification(`${allActivities.length} activités chargées`, 'success');
            } else {
                showActivitiesError(data.message || 'Erreur lors du chargement des activités');
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            showActivitiesError('Erreur de connexion au serveur');
        });
    }
    
    function showActivitiesError(message) {
        const availableContainer = document.getElementById('availableActivities');
        availableContainer.innerHTML = `
            <div class="alert alert-danger">
                <div class="d-flex align-items-center">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <div>
                        <div class="fw-semibold">${message}</div>
                        <button class="btn btn-sm btn-outline-danger mt-2" onclick="loadActivities()">
                            <i class="fas fa-redo me-1"></i>Réessayer
                        </button>
                    </div>
                </div>
            </div>
        `;
    }
    
    function renderActivities() {
        const availableContainer = document.getElementById('availableActivities');
        const selectedContainer = document.getElementById('selectedActivities');
        
        if (!availableContainer || !selectedContainer) return;
        
        // Filtrer les activités disponibles (non sélectionnées)
        const available = filteredActivities.filter(a => !selectedActivities.includes(a.id.toString()));
        const selected = filteredActivities.filter(a => selectedActivities.includes(a.id.toString()));
        
        // Mettre à jour les compteurs
        const availableCount = document.getElementById('availableCount');
        const selectedCount = document.getElementById('selectedCount');
        
        if (availableCount) availableCount.textContent = available.length;
        if (selectedCount) selectedCount.textContent = selected.length;
        
        // Afficher les activités disponibles
        availableContainer.innerHTML = '';
        if (available.length === 0) {
            availableContainer.innerHTML = '<div class="text-muted text-center py-5">Aucune activité disponible</div>';
        } else {
            available.forEach(activity => {
                const activityEl = createActivityElement(activity, false);
                availableContainer.appendChild(activityEl);
            });
        }
        
        // Afficher les activités sélectionnées
        selectedContainer.innerHTML = '';
        if (selected.length === 0) {
            selectedContainer.innerHTML = '<div class="text-muted text-center py-5">Aucune activité sélectionnée</div>';
        } else {
            selected.forEach(activity => {
                const activityEl = createActivityElement(activity, true);
                selectedContainer.appendChild(activityEl);
            });
        }
        
        // Mettre à jour le select caché
        updateHiddenSelect();
    }
    
    function createActivityElement(activity, isSelected) {
        const div = document.createElement('div');
        div.className = `activity-item ${isSelected ? 'selected' : ''}`;
        div.dataset.activityId = activity.id;
        
        const checkbox = document.createElement('input');
        checkbox.type = 'checkbox';
        checkbox.className = 'activity-checkbox';
        checkbox.checked = isSelected;
        checkbox.addEventListener('change', function() {
            toggleActivitySelection(activity.id.toString(), this.checked);
        });
        
        const content = document.createElement('div');
        content.className = 'activity-content';
        
        const name = document.createElement('div');
        name.className = 'activity-name';
        name.textContent = activity.name;
        
        content.appendChild(name);
        
        // Ajouter la description si disponible
        if (activity.description) {
            const description = document.createElement('div');
            description.className = 'activity-description small text-muted mt-1';
            description.textContent = activity.description.length > 100 
                ? activity.description.substring(0, 100) + '...' 
                : activity.description;
            content.appendChild(description);
        }
        
        // Ajouter la catégorie si disponible
        if (activity.category) {
            const category = document.createElement('div');
            category.className = 'activity-category mt-1';
            category.innerHTML = `<span class="badge bg-light text-dark small">${activity.category}</span>`;
            content.appendChild(category);
        }
        
        div.appendChild(checkbox);
        div.appendChild(content);
        
        return div;
    }
    
    function toggleActivitySelection(activityId, selected) {
        if (selected && !selectedActivities.includes(activityId)) {
            selectedActivities.push(activityId);
        } else if (!selected) {
            selectedActivities = selectedActivities.filter(id => id !== activityId);
        }
        
        renderActivities();
    }
    
    function addSelectedActivities() {
        const checkboxes = document.querySelectorAll('#availableActivities .activity-checkbox:checked');
        checkboxes.forEach(cb => {
            const activityId = cb.closest('.activity-item').dataset.activityId;
            if (!selectedActivities.includes(activityId)) {
                selectedActivities.push(activityId);
            }
        });
        renderActivities();
    }
    
    function removeSelectedActivities() {
        const checkboxes = document.querySelectorAll('#selectedActivities .activity-checkbox:checked');
        checkboxes.forEach(cb => {
            const activityId = cb.closest('.activity-item').dataset.activityId;
            selectedActivities = selectedActivities.filter(id => id !== activityId);
        });
        renderActivities();
    }
    
    function selectAllActivities() {
        selectedActivities = filteredActivities.map(a => a.id.toString());
        renderActivities();
    }
    
    function clearAllActivities() {
        selectedActivities = [];
        renderActivities();
    }
    
    function updateHiddenSelect() {
        const select = document.getElementById('activitiesSelect');
        if (!select) return;
        
        select.innerHTML = '';
        
        selectedActivities.forEach(activityId => {
            const activity = allActivities.find(a => a.id.toString() === activityId);
            if (activity) {
                const option = document.createElement('option');
                option.value = activity.id;
                option.textContent = activity.name;
                option.selected = true;
                select.appendChild(option);
            }
        });
    }
    
    // Recherche d'activités
    const activitiesSearch = document.getElementById('activitiesSearch');
    if (activitiesSearch) {
        activitiesSearch.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            
            if (!searchTerm) {
                filteredActivities = [...allActivities];
            } else {
                filteredActivities = allActivities.filter(activity =>
                    activity.name.toLowerCase().includes(searchTerm) ||
                    (activity.description && activity.description.toLowerCase().includes(searchTerm)) ||
                    (activity.category && activity.category.toLowerCase().includes(searchTerm))
                );
            }
            
            renderActivities();
        });
    }
    
    // Fonctions utilitaires
    function togglePassword(inputId) {
        const input = document.getElementById(inputId);
        if (!input) return;
        
        const button = input.nextElementSibling;
        const icon = button.querySelector('i');
        
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }
    
    function formatPhoneNumber(input) {
        const phoneInput = document.getElementById('phone');
        if (!phoneInput) return;
        
        let value = phoneInput.value.replace(/\D/g, '');
        
        if (value.length > 10) {
            value = value.substring(0, 10);
        }
        
        if (value.length >= 6) {
            value = value.replace(/(\d{3})(\d{3})(\d{4})/, '$1-$2-$3');
        } else if (value.length >= 3) {
            value = value.replace(/(\d{3})(\d{1,3})/, '$1-$2');
        }
        
        phoneInput.value = value;
    }
    
    function formatZipCode(input) {
        const zipInput = document.getElementById('zip_code');
        if (!zipInput) return;
        
        let value = zipInput.value.toUpperCase().replace(/[^A-Z0-9]/g, '');
        
        if (value.length > 6) {
            value = value.substring(0, 6);
        }
        
        if (value.length > 3) {
            value = value.substring(0, 3) + ' ' + value.substring(3);
        }
        
        zipInput.value = value;
    }
    
    // Initialisation des formatteurs
    document.addEventListener('DOMContentLoaded', function() {
        const phoneInput = document.getElementById('phone');
        if (phoneInput) {
            phoneInput.addEventListener('input', formatPhoneNumber);
        }
        
        const zipInput = document.getElementById('zip_code');
        if (zipInput) {
            zipInput.addEventListener('input', formatZipCode);
        }
    });
    
    // Export des fonctions pour utilisation globale
    window.nextStep = nextStep;
    window.prevStep = prevStep;
    window.togglePassword = togglePassword;
    window.selectAllActivities = selectAllActivities;
    window.clearAllActivities = clearAllActivities;
    window.addSelectedActivities = addSelectedActivities;
    window.removeSelectedActivities = removeSelectedActivities;
</script>
@endsection