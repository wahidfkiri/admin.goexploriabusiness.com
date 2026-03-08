@extends('layouts.app')

@section('content')
<main class="dashboard-content">
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">
            <span class="page-title-icon"><i class="fas fa-building"></i></span>
            Modifier l'Établissement : {{ $etablissement->name }}
        </h1>
        
        <div class="page-actions">
            <a href="{{ route('etablissements.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Retour à la liste
            </a>
        </div>
    </div>
    
    <!-- Main Card -->
    <div class="main-card-modern">
        <div class="card-header-modern">
            <h3 class="card-title-modern">
                <i class="fas fa-edit me-2"></i>Formulaire de Modification
            </h3>
        </div>
        
        <div class="card-body-modern">
            <!-- Navigation Tabs -->
            <ul class="nav nav-tabs" id="etablissementTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="info-tab" data-bs-toggle="tab" 
                            data-bs-target="#info-tab-pane" type="button" role="tab">
                        <i class="fas fa-info-circle me-2"></i>Infos Générales
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="localisation-tab" data-bs-toggle="tab" 
                            data-bs-target="#localisation-tab-pane" type="button" role="tab">
                        <i class="fas fa-map-marker-alt me-2"></i>Localisation
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="activites-tab" data-bs-toggle="tab" 
                            data-bs-target="#activites-tab-pane" type="button" role="tab">
                        <i class="fas fa-tasks me-2"></i>Activités
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="acces-tab" data-bs-toggle="tab" 
                            data-bs-target="#acces-tab-pane" type="button" role="tab">
                        <i class="fas fa-user-circle me-2"></i>Accès Login
                    </button>
                </li>
            </ul>
            
            <!-- Tab Content -->
            <div class="tab-content" id="etablissementTabsContent">
                
                <!-- Tab 1: Informations Générales -->
                <div class="tab-pane fade show active" id="info-tab-pane" role="tabpanel" 
                     tabindex="0">
                    <div class="tab-header">
                        <h4 class="tab-title">
                            <i class="fas fa-info-circle me-2"></i>Informations Générales
                        </h4>
                        <button type="submit" form="infoForm" class="btn btn-success btn-sm">
                            <i class="fas fa-save me-1"></i>Mettre à jour
                        </button>
                    </div>
                    
                    <form id="infoForm" action="{{ route('etablissements.update', $etablissement) }}" method="POST" 
                          class="tab-form">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="form_type" value="info">
                        
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="name" class="form-label-modern">Nom *</label>
                                <input type="text" class="form-control-modern" id="name" name="name" 
                                       value="{{ old('name', $etablissement->name) }}" 
                                       placeholder="Ex: Hôtel Plaza, Restaurant Le Gourmet..." required>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label-modern">Téléphone</label>
                                <input type="text" class="form-control-modern" id="phone" name="phone" 
                                       value="{{ old('phone', $etablissement->phone) }}" 
                                       placeholder="Ex: +33 1 23 45 67 89">
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="fax" class="form-label-modern">Fax</label>
                                <input type="text" class="form-control-modern" id="fax" name="fax" 
                                       value="{{ old('fax', $etablissement->fax) }}" 
                                       placeholder="Ex: +33 1 23 45 67 90">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="email_contact" class="form-label-modern">Email Contact</label>
                                <div class="input-group">
                                    <input type="email" class="form-control-modern" id="email_contact" name="email_contact" 
                                           value="{{ old('email_contact', $etablissement->email_contact) }}" 
                                           placeholder="test@example.com">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="website" class="form-label-modern">Site Web</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-globe"></i></span>
                                    <input type="url" class="form-control-modern" id="website" name="website" 
                                           value="{{ old('website', $etablissement->website) }}" 
                                           placeholder="Ex: https://www.mon-etablissement.com">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                
                <!-- Tab 2: Localisation -->
                <div class="tab-pane fade" id="localisation-tab-pane" role="tabpanel" 
                     tabindex="0">
                    <div class="tab-header">
                        <h4 class="tab-title">
                            <i class="fas fa-map-marker-alt me-2"></i>Localisation
                        </h4>
                        <button type="submit" form="localisationForm" class="btn btn-success btn-sm">
                            <i class="fas fa-save me-1"></i>Mettre à jour
                        </button>
                    </div>
                    
                    <form id="localisationForm" action="{{ route('etablissements.update', $etablissement) }}" method="POST" 
                          class="tab-form">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="form_type" value="localisation">
                        
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="ville_search" class="form-label-modern">Ville *</label>
                                <input type="text" class="form-control-modern" id="ville_search" name="ville_search" 
                                       value="{{ old('ville_search', $etablissement->ville) }}" 
                                       placeholder="Commencez à taper pour rechercher une ville..." 
                                       autocomplete="off">
                                <input type="hidden" id="ville" name="ville" value="{{ old('ville', $etablissement->ville) }}">
                                <input type="hidden" id="region_id" name="region_id" value="{{ old('region_id', $etablissement->region_id) }}">
                                <input type="hidden" id="province_id" name="province_id" value="{{ old('province_id', $etablissement->province_id) }}">
                                <input type="hidden" id="country_id" name="country_id" value="{{ old('country_id', $etablissement->country_id) }}">
                                
                                <div id="ville_suggestions" class="suggestions-dropdown" style="display: none;">
                                    <!-- Les suggestions seront chargées ici via AJAX -->
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="adresse" class="form-label-modern">Adresse complète *</label>
                                <textarea class="form-control-modern" id="adresse" name="adresse" 
                                          rows="2" placeholder="Ex: 123 Avenue des Champs-Élysées..." 
                                          required>{{ old('adresse', $etablissement->adresse) }}</textarea>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="zip_code" class="form-label-modern">Code postal *</label>
                                <input type="text" class="form-control-modern" id="zip_code" name="zip_code" 
                                       value="{{ old('zip_code', $etablissement->zip_code) }}" 
                                       placeholder="Ex: 75001, 69002..." required>
                            </div>

                            <h3 class="tab-title mt-4">
                                <i class="fas fa-map-marked-alt me-2"></i> Position sur la map
                            </h3>
                            <div class="col-md-6 mb-3">
                                <label for="latitude" class="form-label-modern">Latitude</label>
                                <input type="text" class="form-control-modern" id="latitude" name="latitude" 
                                       value="{{ old('latitude', $etablissement->latitude) }}" 
                                       placeholder="Ex: 46.8139" required>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="longitude" class="form-label-modern">Longitude</label>
                                <input type="text" class="form-control-modern" id="longitude" name="longitude" 
                                       value="{{ old('longitude', $etablissement->longitude) }}" 
                                       placeholder="Ex: -71.2080" required>
                            </div>
                        </div>
                    </form>
                </div>
                
                <!-- Tab 3: Activités -->
                <div class="tab-pane fade" id="activites-tab-pane" role="tabpanel" 
                     tabindex="0">
                    <div class="tab-header">
                        <h4 class="tab-title">
                            <i class="fas fa-tasks me-2"></i>Activités
                        </h4>
                        <button type="submit" form="activitesForm" class="btn btn-success btn-sm">
                            <i class="fas fa-save me-1"></i>Mettre à jour
                        </button>
                    </div>
                    
                    <form id="activitesForm" action="{{ route('etablissements.update', $etablissement) }}" method="POST" 
                          class="tab-form">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="form_type" value="activites">
                        
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
                                            @foreach($selectedActivities as $activityId)
                                            <option value="{{ $activityId }}" selected>{{ $activityId }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                
                <!-- Tab 4: Accès Login -->
                <div class="tab-pane fade" id="acces-tab-pane" role="tabpanel" 
                     tabindex="0">
                    <div class="tab-header">
                        <h4 class="tab-title">
                            <i class="fas fa-user-circle me-2"></i>Gestion du compte
                        </h4>
                        <button type="submit" form="accesForm" class="btn btn-success btn-sm">
                            <i class="fas fa-save me-1"></i>Mettre à jour
                        </button>
                    </div>
                    
                    @if($etablissement->user)
                    <div class="alert alert-info mb-4">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Compte existant :</strong> {{ $etablissement->user->name }} ({{ $etablissement->user->email }})
                    </div>
                    @endif
                    
                    <form id="accesForm" action="{{ route('etablissements.update', $etablissement) }}" method="POST" 
                          class="tab-form">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="form_type" value="acces">
                        
                        @if($etablissement->user)
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            La modification des informations de connexion nécessitera une reconnexion de l'utilisateur.
                        </div>
                        @endif
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="user_name" class="form-label-modern">Nom complet *</label>
                                <input type="text" class="form-control-modern" id="user_name" name="user_name" 
                                       value="{{ old('user_name', $etablissement->user->name ?? '') }}" 
                                       placeholder="Ex: Jean Dupont" required>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="user_email" class="form-label-modern">Email *</label>
                                <input type="email" class="form-control-modern" id="user_email" name="user_email" 
                                       value="{{ old('user_email', $etablissement->user->email ?? '') }}" 
                                       placeholder="Ex: contact@etablissement.com" required>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label-modern">Nouveau mot de passe</label>
                                <div class="input-group">
                                    <input type="password" class="form-control-modern" id="password" name="password" 
                                           placeholder="Laissez vide pour ne pas changer">
                                    <button type="button" class="btn btn-outline-secondary" 
                                            onclick="togglePassword('password')">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                <small class="form-text text-muted">Minimum 8 caractères</small>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="password_confirmation" class="form-label-modern">Confirmer le mot de passe</label>
                                <div class="input-group">
                                    <input type="password" class="form-control-modern" id="password_confirmation" 
                                           name="password_confirmation" placeholder="Confirmer le nouveau mot de passe">
                                    <button type="button" class="btn btn-outline-secondary" 
                                            onclick="togglePassword('password_confirmation')">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label for="role_id" class="form-label-modern">Type du compte *</label>
                                <select class="form-control-modern" id="role_id" name="role_id" required>
                                    @foreach($roles as $role)
                                    <option value="{{ $role->id }}" 
                                            {{ old('role_id', $etablissement->user->role_id ?? '') == $role->id ? 'selected' : '' }}>
                                        {{ $role->libelle }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label-modern">Statut du compte</label>
                            <div class="status-toggle">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active" 
                                           {{ old('is_active', $etablissement->user->is_active ?? true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">
                                        <span class="status-label">Compte actif</span>
                                        <small class="text-muted d-block">L'utilisateur pourra se connecter</small>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Actions globales -->
            <div class="global-actions mt-4 pt-4 border-top">
                <div class="d-flex justify-content-between">
                    <div>
                        <button type="button" class="btn btn-outline-secondary" onclick="prevTab()">
                            <i class="fas fa-arrow-left me-2"></i>Précédent
                        </button>
                        <button type="button" class="btn btn-outline-secondary" onclick="nextTab()">
                            Suivant <i class="fas fa-arrow-right ms-2"></i>
                        </button>
                    </div>
                    <div>
                        <a href="{{ route('etablissements.show', $etablissement) }}" class="btn btn-info me-2">
                            <i class="fas fa-eye me-2"></i>Voir détails
                        </a>
                        <button type="button" class="btn btn-danger" onclick="confirmDelete()">
                            <i class="fas fa-trash me-2"></i>Supprimer
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Modal de suppression -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmer la suppression</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Êtes-vous sûr de vouloir supprimer l'établissement "{{ $etablissement->name }}" ?
                <div class="alert alert-danger mt-2">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Cette action est irréversible. Toutes les données associées seront supprimées.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <form action="{{ route('etablissements.destroy', $etablissement) }}" method="POST" id="deleteForm">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Supprimer définitivement</button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .input-group {
        flex-wrap: nowrap !important;
    }
    
    /* Tabs Styles */
    .nav-tabs {
        border-bottom: 2px solid #dee2e6;
        margin-bottom: 25px;
    }
    
    .nav-tabs .nav-link {
        color: #6c757d;
        font-weight: 500;
        padding: 12px 20px;
        border: none;
        border-bottom: 3px solid transparent;
        transition: all 0.3s ease;
    }
    
    .nav-tabs .nav-link:hover {
        color: #45b7d1;
        background-color: rgba(69, 183, 209, 0.05);
        border-color: transparent;
    }
    
    .nav-tabs .nav-link.active {
        color: #45b7d1;
        background-color: transparent;
        border-bottom: 3px solid #45b7d1;
    }
    
    .tab-content {
        padding: 0;
        min-height: 400px;
    }
    
    .tab-pane {
        animation: fadeIn 0.5s ease;
    }
    
    .tab-header {
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
    
    .tab-form {
        padding: 10px 0;
    }
    
    .global-actions {
        background-color: #f8f9fa;
        padding: 15px;
        border-radius: 8px;
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
    
    /* Toast Notifications */
    .toast-container {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
    }
    
    /* Form States */
    .form-data-saved {
        border-left: 4px solid #28a745;
        padding-left: 10px;
    }
    
    .form-data-unsaved {
        border-left: 4px solid #ffc107;
        padding-left: 10px;
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
        .nav-tabs .nav-link {
            padding: 8px 12px;
            font-size: 0.9rem;
        }
        
        .activities-selection-container {
            flex-direction: column;
        }
        
        .activities-selection-controls {
            flex-direction: row;
            justify-content: center;
            margin: 10px 0;
        }
        
        .global-actions {
            flex-direction: column;
            gap: 10px;
        }
        
        .global-actions .btn {
            width: 100%;
            margin-bottom: 5px;
        }
    }
</style>

<script>
    // Variables globales
    let etablissementId = {{ $etablissement->id }};
    let allActivities = @json($activities);
    let filteredActivities = [];
    let selectedActivities = @json($selectedActivities);
    let debounceTimer;
    let villeSearchAbortController = null;
    
    document.addEventListener('DOMContentLoaded', function() {
        // Initialiser Bootstrap tabs
        const tabEl = document.querySelector('#etablissementTabs');
        
        // Charger les activités existantes
        initActivities();
        
        // Initialiser la recherche de ville
        initVilleSearch();
        
        // Pré-remplir la recherche de ville
        const villeSearch = document.getElementById('ville_search');
        const villeInput = document.getElementById('ville');
        if (villeSearch && villeInput.value) {
            villeSearch.value = villeInput.value;
        }
        
        // Gérer la soumission de chaque formulaire
        setupFormSubmissions();
        
        // Navigation rapide avec clavier
        document.addEventListener('keydown', function(e) {
            // Ctrl + → pour passer au tab suivant
            if (e.ctrlKey && e.key === 'ArrowRight') {
                e.preventDefault();
                navigateToNextTab();
            }
            
            // Ctrl + ← pour revenir au tab précédent
            if (e.ctrlKey && e.key === 'ArrowLeft') {
                e.preventDefault();
                navigateToPrevTab();
            }
        });
    });
    
    // Initialisation des activités avec les données existantes
    function initActivities() {
        // Transformer les données PHP en format JavaScript
        allActivities = allActivities.map(activity => ({
            id: activity.id,
            name: activity.name,
            description: activity.description || '',
            category: activity.category || '',
            selected: selectedActivities.includes(activity.id)
        }));
        
        filteredActivities = [...allActivities];
        renderActivities();
        
        // Marquer les activités sélectionnées
        selectedActivities.forEach(activityId => {
            const activity = allActivities.find(a => a.id == activityId);
            if (activity) {
                activity.selected = true;
            }
        });
    }
    
    // Navigation entre les tabs
    function nextTab() {
        navigateToNextTab();
    }
    
    function prevTab() {
        navigateToPrevTab();
    }
    
    function navigateToNextTab() {
        const activeTab = document.querySelector('.nav-link.active');
        if (!activeTab) return;
        
        const nextTab = activeTab.closest('li').nextElementSibling;
        if (nextTab) {
            const nextTabButton = nextTab.querySelector('.nav-link');
            new bootstrap.Tab(nextTabButton).show();
        }
    }
    
    function navigateToPrevTab() {
        const activeTab = document.querySelector('.nav-link.active');
        if (!activeTab) return;
        
        const prevTab = activeTab.closest('li').previousElementSibling;
        if (prevTab) {
            const prevTabButton = prevTab.querySelector('.nav-link');
            new bootstrap.Tab(prevTabButton).show();
        }
    }
    
    // Configuration des soumissions de formulaires
    function setupFormSubmissions() {
        // Gérer la soumission de chaque formulaire
        document.querySelectorAll('.tab-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                submitForm(this);
            });
        });
    }
    
    // Soumission des formulaires individuels
    function submitForm(form) {
        const formData = new FormData(form);
        const formType = formData.get('form_type');
        
        // Validation spécifique au formulaire
        if (!validateForm(form, formType)) {
            return;
        }
        
        // Pour les activités, mettre à jour le select caché
        if (formType === 'activites') {
            updateHiddenSelect();
            // Ajouter les activités au FormData
            selectedActivities.forEach(activityId => {
                formData.append('activities[]', activityId);
            });
        }
        
        // Afficher un indicateur de chargement
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalContent = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = `
            <div class="spinner-border spinner-border-sm me-1" role="status">
                <span class="visually-hidden">Chargement...</span>
            </div>
            Mise à jour...
        `;
        
        // Soumettre via AJAX
        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalContent;
            
            if (data.success) {
                showNotification(data.message || 'Mise à jour réussie', 'success');
                
                // Marquer le formulaire comme sauvegardé
                form.classList.add('form-data-saved');
                form.classList.remove('form-data-unsaved');
                
                // Si c'est le formulaire d'accès et qu'un nouvel utilisateur est créé
                if (formType === 'acces' && data.user_created) {
                    // Rafraîchir la page pour afficher les nouvelles infos
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                }
            } else {
                throw new Error(data.message || 'Erreur lors de la mise à jour');
            }
        })
        .catch(error => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalContent;
            showNotification(error.message, 'danger');
        });
    }
    
    // Validation des formulaires
    function validateForm(form, formType) {
        let isValid = true;
        
        switch(formType) {
            case 'info':
                const name = form.querySelector('#name');
                if (!name.value.trim()) {
                    showError(name, 'Le nom est obligatoire');
                    isValid = false;
                } else {
                    clearError(name);
                }
                break;
                
            case 'localisation':
                const ville = form.querySelector('#ville');
                const villeSearch = form.querySelector('#ville_search');
                const adresse = form.querySelector('#adresse');
                const zipCode = form.querySelector('#zip_code');
                
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
                
            case 'activites':
                if (selectedActivities.length === 0) {
                    showNotification('Veuillez sélectionner au moins une activité', 'warning');
                    isValid = false;
                }
                break;
                
            case 'acces':
                const userName = form.querySelector('#user_name');
                const userEmail = form.querySelector('#user_email');
                const password = form.querySelector('#password');
                const passwordConfirmation = form.querySelector('#password_confirmation');
                
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
                
                // Validation du mot de passe seulement s'il est fourni
                if (password.value) {
                    if (password.value.length < 8) {
                        showError(password, 'Le mot de passe doit contenir au moins 8 caractères');
                        isValid = false;
                    } else {
                        clearError(password);
                    }
                    
                // Validation des formulaires (suite)
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
    
    // Gestion des activités (identique à votre code original)
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
        // Sélectionner toutes les activités disponibles
        const available = filteredActivities.filter(a => !selectedActivities.includes(a.id.toString()));
        available.forEach(activity => {
            if (!selectedActivities.includes(activity.id.toString())) {
                selectedActivities.push(activity.id.toString());
            }
        });
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
    
    // Recherche de ville (identique à votre code original)
    function initVilleSearch() {
        const villeSearch = document.getElementById('ville_search');
        const suggestions = document.getElementById('ville_suggestions');
        
        if (!villeSearch) return;
        
        villeSearch.addEventListener('input', function() {
            const searchTerm = this.value.trim();
            
            if (villeSearchAbortController) {
                villeSearchAbortController.abort();
            }
            
            clearTimeout(debounceTimer);
            
            if (searchTerm.length < 2) {
                suggestions.style.display = 'none';
                return;
            }
            
            suggestions.innerHTML = `
                <div class="suggestion-item">
                    <div class="d-flex align-items-center">
                        <div class="spinner-border spinner-border-sm me-2"></div>
                        <span>Recherche en cours...</span>
                    </div>
                </div>
            `;
            suggestions.style.display = 'block';
            
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
    }
    
    // Fonctions utilitaires
    function showError(input, message) {
        input.classList.remove('is-valid');
        input.classList.add('is-invalid');
        
        let errorDiv = input.parentNode.querySelector('.invalid-feedback');
        if (!errorDiv) {
            errorDiv = document.createElement('div');
            errorDiv.className = 'invalid-feedback';
            input.parentNode.appendChild(errorDiv);
        }
        errorDiv.textContent = message;
        
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
        const toastContainer = document.querySelector('.toast-container') || createToastContainer();
        const toastId = 'toast-' + Date.now();
        
        const toast = document.createElement('div');
        toast.id = toastId;
        toast.className = `toast align-items-center text-bg-${type} border-0`;
        toast.setAttribute('role', 'alert');
        toast.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">
                    <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'warning' ? 'exclamation-triangle' : type === 'danger' ? 'exclamation-circle' : 'info-circle'} me-2"></i>
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" onclick="document.getElementById('${toastId}').remove()"></button>
            </div>
        `;
        
        toastContainer.appendChild(toast);
        
        setTimeout(() => {
            toast.classList.add('show');
        }, 10);
        
        setTimeout(() => {
            if (document.getElementById(toastId)) {
                document.getElementById(toastId).remove();
            }
        }, 5000);
    }
    
    function createToastContainer() {
        const container = document.createElement('div');
        container.className = 'toast-container';
        document.body.appendChild(container);
        return container;
    }
    
    function isValidEmail(email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    }
    
    // Fonction pour afficher le modal de suppression
    function confirmDelete() {
        const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
        deleteModal.show();
    }
    
    // Gestion de la suppression
    document.addEventListener('DOMContentLoaded', function() {
        const deleteForm = document.getElementById('deleteForm');
        if (deleteForm) {
            deleteForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const deleteBtn = this.querySelector('button[type="submit"]');
                const originalContent = deleteBtn.innerHTML;
                deleteBtn.disabled = true;
                deleteBtn.innerHTML = `
                    <div class="spinner-border spinner-border-sm me-2" role="status">
                        <span class="visually-hidden">Chargement...</span>
                    </div>
                    Suppression...
                `;
                
                fetch(this.action, {
                    method: 'POST',
                    body: new FormData(this),
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showNotification('Établissement supprimé avec succès', 'success');
                        
                        // Fermer le modal
                        const modal = bootstrap.Modal.getInstance(document.getElementById('deleteModal'));
                        modal.hide();
                        
                        // Rediriger après 1.5 secondes
                        setTimeout(() => {
                            window.location.href = data.redirect || '{{ route("etablissements.index") }}';
                        }, 1500);
                    } else {
                        throw new Error(data.message || 'Erreur lors de la suppression');
                    }
                })
                .catch(error => {
                    deleteBtn.disabled = false;
                    deleteBtn.innerHTML = originalContent;
                    showNotification(error.message, 'danger');
                });
            });
        }
    });
    
    // Fonctions exportées globalement
    window.nextTab = nextTab;
    window.prevTab = prevTab;
    window.togglePassword = function(inputId) {
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
    };
    
    window.selectAllActivities = selectAllActivities;
    window.clearAllActivities = clearAllActivities;
    window.addSelectedActivities = addSelectedActivities;
    window.removeSelectedActivities = removeSelectedActivities;
    window.confirmDelete = confirmDelete;
</script>
@endsection