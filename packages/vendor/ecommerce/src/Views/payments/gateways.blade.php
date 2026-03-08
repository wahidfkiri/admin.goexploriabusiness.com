@extends('layouts.app')

@section('content')
    <!-- MAIN CONTENT -->
    <main class="dashboard-content">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">
                <span class="page-title-icon"><i class="fas fa-credit-card"></i></span>
                Configuration des paiements
            </h1>
            
            <div class="page-actions">
                <button class="btn btn-outline-primary" id="testConnectionBtn">
                    <i class="fas fa-plug me-2"></i>Tester les connexions
                </button>
                <button class="btn btn-primary" id="saveAllBtn">
                    <i class="fas fa-save me-2"></i>Enregistrer tout
                </button>
            </div>
        </div>

        <!-- Status Alert -->
        <div class="alert alert-info" id="configStatus" style="display: none;">
            <i class="fas fa-info-circle me-2"></i>
            <span id="statusMessage"></span>
        </div>

        <!-- Payment Gateways Tabs -->
        <div class="payment-tabs-modern">
            <ul class="nav nav-tabs" id="gatewayTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="stripe-tab" data-bs-toggle="tab" data-bs-target="#stripe" type="button" role="tab">
                        <i class="fab fa-stripe me-2"></i>Stripe
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="paypal-tab" data-bs-toggle="tab" data-bs-target="#paypal" type="button" role="tab">
                        <i class="fab fa-paypal me-2"></i>PayPal
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="bank-tab" data-bs-toggle="tab" data-bs-target="#bank" type="button" role="tab">
                        <i class="fas fa-university me-2"></i>Virement bancaire
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="general-tab" data-bs-toggle="tab" data-bs-target="#general" type="button" role="tab">
                        <i class="fas fa-cog me-2"></i>Paramètres généraux
                    </button>
                </li>
            </ul>
        </div>

        <!-- Tab Content -->
        <div class="tab-content" id="gatewayTabContent">
            <!-- Stripe Tab -->
            <div class="tab-pane fade show active" id="stripe" role="tabpanel">
                <div class="main-card-modern">
                    <div class="card-header-modern">
                        <h3 class="card-title-modern">
                            <i class="fab fa-stripe me-2"></i>Configuration Stripe
                        </h3>
                        <div class="gateway-status">
                            <span class="badge" id="stripeStatusBadge">Non configuré</span>
                        </div>
                    </div>
                    
                    <div class="card-body-modern">
                        <form id="stripeConfigForm" class="gateway-form">
                            @csrf
                            <input type="hidden" name="gateway" value="stripe">
                            
                            <!-- Mode Switch -->
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label class="form-label-modern">Mode</label>
                                    <div class="mode-switch">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="stripe_mode" id="stripe_sandbox" value="sandbox" checked>
                                            <label class="form-check-label" for="stripe_sandbox">
                                                <span class="badge bg-warning">Sandbox (Test)</span>
                                            </label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="stripe_mode" id="stripe_live" value="live">
                                            <label class="form-check-label" for="stripe_live">
                                                <span class="badge bg-success">Live (Production)</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label-modern">Statut</label>
                                    <div class="status-switch">
                                        <label class="switch">
                                            <input type="checkbox" name="stripe_active" id="stripe_active">
                                            <span class="slider round"></span>
                                        </label>
                                        <span class="ms-3" id="stripeActiveText">Désactivé</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Configuration Source -->
                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <label class="form-label-modern">Source de configuration</label>
                                    <div class="config-source">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="stripe_use_env" id="stripe_use_env_yes" value="1" checked>
                                            <label class="form-check-label" for="stripe_use_env_yes">
                                                <i class="fas fa-globe me-1"></i>Variables d'environnement (.env)
                                            </label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="stripe_use_env" id="stripe_use_env_no" value="0">
                                            <label class="form-check-label" for="stripe_use_env_no">
                                                <i class="fas fa-database me-1"></i>Base de données
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Env Configuration (readonly) -->
                            <div class="env-config-section" id="stripeEnvConfig">
                                <h5 class="section-subtitle">
                                    <i class="fas fa-lock me-2"></i>Configuration actuelle (.env)
                                </h5>
                                <div class="env-vars-display">
                                    <div class="env-var-item">
                                        <span class="env-var-key">STRIPE_KEY:</span>
                                        <span class="env-var-value">{{ env('STRIPE_KEY', 'Non défini') }}</span>
                                    </div>
                                    <div class="env-var-item">
                                        <span class="env-var-key">STRIPE_SECRET:</span>
                                        <span class="env-var-value">{{ env('STRIPE_SECRET') ? '••••••••' : 'Non défini' }}</span>
                                    </div>
                                    <div class="env-var-item">
                                        <span class="env-var-key">STRIPE_WEBHOOK_SECRET:</span>
                                        <span class="env-var-value">{{ env('STRIPE_WEBHOOK_SECRET') ? '••••••••' : 'Non défini' }}</span>
                                    </div>
                                </div>
                                <p class="text-muted mt-2">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Les variables d'environnement sont définies dans le fichier .env
                                </p>
                            </div>

                            <!-- DB Configuration (editable) -->
                            <div class="db-config-section" id="stripeDbConfig" style="display: none;">
                                <h5 class="section-subtitle">
                                    <i class="fas fa-edit me-2"></i>Configuration base de données
                                </h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label-modern">Clé publiable (Publishable Key)</label>
                                            <input type="text" class="form-control-modern" name="stripe_publishable_key" 
                                                   placeholder="pk_test_..." value="{{ $stripe->stripe_publishable_key ?? '' }}">
                                            <small class="text-muted">Commence par pk_live_ ou pk_test_</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label-modern">Clé secrète (Secret Key)</label>
                                            <input type="password" class="form-control-modern" name="stripe_secret_key" 
                                                   placeholder="sk_test_..." value="">
                                            <small class="text-muted">Commence par sk_live_ ou sk_test_</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label-modern">Webhook Secret</label>
                                            <input type="password" class="form-control-modern" name="stripe_webhook_secret" 
                                                   placeholder="whsec_..." value="">
                                            <small class="text-muted">Pour vérifier les webhooks</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Webhook URL -->
                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <h5 class="section-subtitle">
                                        <i class="fas fa-webhook me-2"></i>Configuration Webhook
                                    </h5>
                                    <div class="webhook-url-display">
                                        <label class="form-label-modern">URL du webhook Stripe</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control-modern" value="{{ url('/webhook/stripe') }}" readonly id="stripeWebhookUrl">
                                            <button class="btn btn-outline-secondary" type="button" onclick="copyToClipboard('stripeWebhookUrl')">
                                                <i class="fas fa-copy"></i>
                                            </button>
                                        </div>
                                        <small class="text-muted">
                                            Configurez cette URL dans votre tableau de bord Stripe > Webhooks
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <!-- Test Section -->
                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <h5 class="section-subtitle">
                                        <i class="fas fa-vial me-2"></i>Test de connexion
                                    </h5>
                                    <button type="button" class="btn btn-outline-primary" onclick="testStripeConnection()">
                                        <i class="fas fa-plug me-2"></i>Tester la connexion Stripe
                                    </button>
                                    <div id="stripeTestResult" class="mt-3"></div>
                                </div>
                            </div>

                            <!-- Fees Configuration -->
                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <h5 class="section-subtitle">
                                        <i class="fas fa-percent me-2"></i>Frais de transaction
                                    </h5>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label-modern">Pourcentage</label>
                                                <div class="input-group">
                                                    <input type="number" step="0.1" class="form-control-modern" name="stripe_fee_percentage" value="1.4">
                                                    <span class="input-group-text">%</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label-modern">Frais fixes</label>
                                                <div class="input-group">
                                                    <input type="number" step="0.01" class="form-control-modern" name="stripe_fee_fixed" value="0.25">
                                                    <span class="input-group-text">€</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label-modern">Devises supportées</label>
                                                <select class="form-select-modern" name="stripe_currencies[]" multiple size="3">
                                                    <option value="EUR" selected>Euro (EUR)</option>
                                                    <option value="USD">Dollar US (USD)</option>
                                                    <option value="GBP">Livre sterling (GBP)</option>
                                                    <option value="CHF">Franc suisse (CHF)</option>
                                                    <option value="CAD">Dollar canadien (CAD)</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- PayPal Tab -->
            <div class="tab-pane fade" id="paypal" role="tabpanel">
                <div class="main-card-modern">
                    <div class="card-header-modern">
                        <h3 class="card-title-modern">
                            <i class="fab fa-paypal me-2"></i>Configuration PayPal
                        </h3>
                        <div class="gateway-status">
                            <span class="badge" id="paypalStatusBadge">Non configuré</span>
                        </div>
                    </div>
                    
                    <div class="card-body-modern">
                        <form id="paypalConfigForm" class="gateway-form">
                            @csrf
                            <input type="hidden" name="gateway" value="paypal">
                            
                            <!-- Mode Switch -->
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label class="form-label-modern">Mode</label>
                                    <div class="mode-switch">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="paypal_mode" id="paypal_sandbox" value="sandbox" checked>
                                            <label class="form-check-label" for="paypal_sandbox">
                                                <span class="badge bg-warning">Sandbox (Test)</span>
                                            </label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="paypal_mode" id="paypal_live" value="live">
                                            <label class="form-check-label" for="paypal_live">
                                                <span class="badge bg-success">Live (Production)</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label-modern">Statut</label>
                                    <div class="status-switch">
                                        <label class="switch">
                                            <input type="checkbox" name="paypal_active" id="paypal_active">
                                            <span class="slider round"></span>
                                        </label>
                                        <span class="ms-3" id="paypalActiveText">Désactivé</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Configuration Source -->
                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <label class="form-label-modern">Source de configuration</label>
                                    <div class="config-source">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="paypal_use_env" id="paypal_use_env_yes" value="1" checked>
                                            <label class="form-check-label" for="paypal_use_env_yes">
                                                <i class="fas fa-globe me-1"></i>Variables d'environnement (.env)
                                            </label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="paypal_use_env" id="paypal_use_env_no" value="0">
                                            <label class="form-check-label" for="paypal_use_env_no">
                                                <i class="fas fa-database me-1"></i>Base de données
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Env Configuration (readonly) -->
                            <div class="env-config-section" id="paypalEnvConfig">
                                <h5 class="section-subtitle">
                                    <i class="fas fa-lock me-2"></i>Configuration actuelle (.env)
                                </h5>
                                <div class="env-vars-display">
                                    <div class="env-var-item">
                                        <span class="env-var-key">PAYPAL_CLIENT_ID:</span>
                                        <span class="env-var-value">{{ env('PAYPAL_CLIENT_ID', 'Non défini') }}</span>
                                    </div>
                                    <div class="env-var-item">
                                        <span class="env-var-key">PAYPAL_CLIENT_SECRET:</span>
                                        <span class="env-var-value">{{ env('PAYPAL_CLIENT_SECRET') ? '••••••••' : 'Non défini' }}</span>
                                    </div>
                                    <div class="env-var-item">
                                        <span class="env-var-key">PAYPAL_WEBHOOK_ID:</span>
                                        <span class="env-var-value">{{ env('PAYPAL_WEBHOOK_ID', 'Non défini') }}</span>
                                    </div>
                                    <div class="env-var-item">
                                        <span class="env-var-key">PAYPAL_MODE:</span>
                                        <span class="env-var-value">{{ env('PAYPAL_MODE', 'sandbox') }}</span>
                                    </div>
                                </div>
                                <p class="text-muted mt-2">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Les variables d'environnement sont définies dans le fichier .env
                                </p>
                            </div>

                            <!-- DB Configuration (editable) -->
                            <div class="db-config-section" id="paypalDbConfig" style="display: none;">
                                <h5 class="section-subtitle">
                                    <i class="fas fa-edit me-2"></i>Configuration base de données
                                </h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label-modern">Client ID</label>
                                            <input type="text" class="form-control-modern" name="paypal_client_id" 
                                                   placeholder="Client ID PayPal">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label-modern">Client Secret</label>
                                            <input type="password" class="form-control-modern" name="paypal_client_secret" 
                                                   placeholder="Client Secret">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label-modern">Webhook ID</label>
                                            <input type="text" class="form-control-modern" name="paypal_webhook_id" 
                                                   placeholder="Webhook ID">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Webhook URL -->
                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <h5 class="section-subtitle">
                                        <i class="fas fa-webhook me-2"></i>Configuration Webhook
                                    </h5>
                                    <div class="webhook-url-display">
                                        <label class="form-label-modern">URL du webhook PayPal</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control-modern" value="{{ url('/webhook/paypal') }}" readonly id="paypalWebhookUrl">
                                            <button class="btn btn-outline-secondary" type="button" onclick="copyToClipboard('paypalWebhookUrl')">
                                                <i class="fas fa-copy"></i>
                                            </button>
                                        </div>
                                        <small class="text-muted">
                                            Configurez cette URL dans votre tableau de bord PayPal > Webhooks
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <!-- Test Section -->
                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <h5 class="section-subtitle">
                                        <i class="fas fa-vial me-2"></i>Test de connexion
                                    </h5>
                                    <button type="button" class="btn btn-outline-primary" onclick="testPayPalConnection()">
                                        <i class="fas fa-plug me-2"></i>Tester la connexion PayPal
                                    </button>
                                    <div id="paypalTestResult" class="mt-3"></div>
                                </div>
                            </div>

                            <!-- Fees Configuration -->
                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <h5 class="section-subtitle">
                                        <i class="fas fa-percent me-2"></i>Frais de transaction
                                    </h5>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label-modern">Pourcentage</label>
                                                <div class="input-group">
                                                    <input type="number" step="0.1" class="form-control-modern" name="paypal_fee_percentage" value="2.9">
                                                    <span class="input-group-text">%</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label-modern">Frais fixes</label>
                                                <div class="input-group">
                                                    <input type="number" step="0.01" class="form-control-modern" name="paypal_fee_fixed" value="0.35">
                                                    <span class="input-group-text">€</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label-modern">Devises supportées</label>
                                                <select class="form-select-modern" name="paypal_currencies[]" multiple size="3">
                                                    <option value="EUR" selected>Euro (EUR)</option>
                                                    <option value="USD">Dollar US (USD)</option>
                                                    <option value="GBP">Livre sterling (GBP)</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Bank Transfer Tab -->
            <div class="tab-pane fade" id="bank" role="tabpanel">
                <div class="main-card-modern">
                    <div class="card-header-modern">
                        <h3 class="card-title-modern">
                            <i class="fas fa-university me-2"></i>Configuration virement bancaire
                        </h3>
                    </div>
                    
                    <div class="card-body-modern">
                        <form id="bankConfigForm">
                            @csrf
                            
                            <!-- Bank Details -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label-modern">Titulaire du compte</label>
                                        <input type="text" class="form-control-modern" name="account_holder" 
                                               value="{{ env('BANK_ACCOUNT_HOLDER') }}" placeholder="Nom de l'entreprise">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label-modern">Nom de la banque</label>
                                        <input type="text" class="form-control-modern" name="bank_name" 
                                               value="{{ env('BANK_NAME') }}" placeholder="Nom de la banque">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label-modern">IBAN</label>
                                        <input type="text" class="form-control-modern" name="iban" 
                                               value="{{ env('BANK_IBAN') }}" placeholder="FR76 XXXX XXXX XXXX">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label-modern">BIC/SWIFT</label>
                                        <input type="text" class="form-control-modern" name="bic" 
                                               value="{{ env('BANK_BIC') }}" placeholder="BIC">
                                    </div>
                                </div>
                            </div>

                            <!-- Bank Address -->
                            <h5 class="section-subtitle mt-4">
                                <i class="fas fa-map-marker-alt me-2"></i>Adresse de la banque
                            </h5>
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="mb-3">
                                        <label class="form-label-modern">Adresse</label>
                                        <input type="text" class="form-control-modern" name="bank_address" 
                                               placeholder="Adresse de la banque">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label-modern">Code postal</label>
                                        <input type="text" class="form-control-modern" name="bank_zip" 
                                               placeholder="Code postal">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label-modern">Ville</label>
                                        <input type="text" class="form-control-modern" name="bank_city" 
                                               placeholder="Ville">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label-modern">Pays</label>
                                        <select class="form-select-modern" name="bank_country">
                                            <option value="FR">France</option>
                                            <option value="BE">Belgique</option>
                                            <option value="CH">Suisse</option>
                                            <option value="LU">Luxembourg</option>
                                            <option value="CA">Canada</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Instructions -->
                            <h5 class="section-subtitle mt-4">
                                <i class="fas fa-file-alt me-2"></i>Instructions de paiement
                            </h5>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label-modern">Instructions (affichées au client)</label>
                                        <textarea class="form-control-modern" name="bank_instructions" rows="5" 
                                                  placeholder="Instructions pour effectuer le virement...">Merci d'effectuer votre virement en utilisant les coordonnées bancaires ci-dessus. Veuillez indiquer votre numéro de facture comme référence de paiement.</textarea>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- General Settings Tab -->
            <div class="tab-pane fade" id="general" role="tabpanel">
                <div class="main-card-modern">
                    <div class="card-header-modern">
                        <h3 class="card-title-modern">
                            <i class="fas fa-cog me-2"></i>Paramètres généraux
                        </h3>
                    </div>
                    
                    <div class="card-body-modern">
                        <form id="generalConfigForm">
                            @csrf
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label-modern">Devise par défaut</label>
                                        <select class="form-select-modern" name="default_currency">
                                            <option value="EUR" selected>Euro (EUR)</option>
                                            <option value="USD">Dollar US (USD)</option>
                                            <option value="GBP">Livre sterling (GBP)</option>
                                            <option value="CHF">Franc suisse (CHF)</option>
                                            <option value="CAD">Dollar canadien (CAD)</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label-modern">Ordre des passerelles</label>
                                        <div class="gateway-order-list" id="gatewayOrderList">
                                            <!-- Will be populated by JS -->
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label-modern">Délai de confirmation (jours)</label>
                                        <input type="number" class="form-control-modern" name="payment_confirmation_days" value="2">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label-modern">Délai d'annulation (jours)</label>
                                        <input type="number" class="form-control-modern" name="payment_cancel_days" value="30">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="force_https" id="force_https" checked>
                                            <label class="form-check-label" for="force_https">
                                                Forcer HTTPS pour les webhooks
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="debug_mode" id="debug_mode">
                                            <label class="form-check-label" for="debug_mode">
                                                Mode debug (log des transactions)
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Test Connection Modal -->
    <div class="modal fade" id="testConnectionModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Test des connexions</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="test-results" id="testResults">
                        <!-- Results will be populated here -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            initializeForms();
            loadSavedConfig();
            setupEventListeners();
        });

        function initializeForms() {
            // Initialize switches
            document.querySelectorAll('input[type="checkbox"][name$="_active"]').forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const textElement = document.getElementById(this.id.replace('_active', 'ActiveText'));
                    if (textElement) {
                        textElement.textContent = this.checked ? 'Activé' : 'Désactivé';
                    }
                });
            });

            // Initialize config source radios
            document.querySelectorAll('input[name="stripe_use_env"]').forEach(radio => {
                radio.addEventListener('change', function() {
                    toggleConfigSource('stripe', this.value === '1');
                });
            });

            document.querySelectorAll('input[name="paypal_use_env"]').forEach(radio => {
                radio.addEventListener('change', function() {
                    toggleConfigSource('paypal', this.value === '1');
                });
            });

            // Initialize gateway order list
            updateGatewayOrderList();
        }

        function toggleConfigSource(gateway, useEnv) {
            const envSection = document.getElementById(`${gateway}EnvConfig`);
            const dbSection = document.getElementById(`${gateway}DbConfig`);
            
            if (useEnv) {
                envSection.style.display = 'block';
                dbSection.style.display = 'none';
            } else {
                envSection.style.display = 'none';
                dbSection.style.display = 'block';
            }
        }

        function loadSavedConfig() {
            // Load saved configuration via AJAX
            $.ajax({
                url: '{{ route("admin.payment.get-config") }}',
                type: 'GET',
                success: function(response) {
                    if (response.success) {
                        populateForms(response.data);
                    }
                }
            });
        }

        function populateForms(data) {
            // Populate Stripe form
            if (data.stripe) {
                setRadioValue('stripe_mode', data.stripe.mode);
                document.getElementById('stripe_active').checked = data.stripe.is_active;
                document.getElementById('stripeActiveText').textContent = data.stripe.is_active ? 'Activé' : 'Désactivé';
                setRadioValue('stripe_use_env', data.stripe.use_env ? '1' : '0');
                toggleConfigSource('stripe', data.stripe.use_env);
                
                if (!data.stripe.use_env) {
                    document.querySelector('input[name="stripe_publishable_key"]').value = data.stripe.stripe_publishable_key || '';
                }
            }

            // Populate PayPal form
            if (data.paypal) {
                setRadioValue('paypal_mode', data.paypal.mode);
                document.getElementById('paypal_active').checked = data.paypal.is_active;
                document.getElementById('paypalActiveText').textContent = data.paypal.is_active ? 'Activé' : 'Désactivé';
                setRadioValue('paypal_use_env', data.paypal.use_env ? '1' : '0');
                toggleConfigSource('paypal', data.paypal.use_env);
                
                if (!data.paypal.use_env) {
                    document.querySelector('input[name="paypal_client_id"]').value = data.paypal.paypal_client_id || '';
                }
            }

            // Update status badges
            updateStatusBadges(data);
        }

        function setRadioValue(name, value) {
            const radio = document.querySelector(`input[name="${name}"][value="${value}"]`);
            if (radio) radio.checked = true;
        }

        function updateStatusBadges(data) {
            const stripeBadge = document.getElementById('stripeStatusBadge');
            const paypalBadge = document.getElementById('paypalStatusBadge');

            if (data.stripe && data.stripe.is_active) {
                stripeBadge.textContent = 'Actif';
                stripeBadge.className = 'badge bg-success';
            } else {
                stripeBadge.textContent = 'Inactif';
                stripeBadge.className = 'badge bg-secondary';
            }

            if (data.paypal && data.paypal.is_active) {
                paypalBadge.textContent = 'Actif';
                paypalBadge.className = 'badge bg-success';
            } else {
                paypalBadge.textContent = 'Inactif';
                paypalBadge.className = 'badge bg-secondary';
            }
        }

        function updateGatewayOrderList() {
            const orderList = document.getElementById('gatewayOrderList');
            const gateways = ['Stripe', 'PayPal', 'Virement bancaire'];
            
            let html = '<ul class="gateway-order-items">';
            gateways.forEach((gateway, index) => {
                html += `
                    <li class="gateway-order-item">
                        <i class="fas fa-grip-vertical me-2"></i>
                        ${gateway}
                        <input type="hidden" name="gateway_order[]" value="${gateway.toLowerCase()}">
                    </li>
                `;
            });
            html += '</ul>';
            orderList.innerHTML = html;

            // Make sortable
            if (typeof Sortable !== 'undefined') {
                new Sortable(orderList.querySelector('.gateway-order-items'), {
                    animation: 150,
                    handle: '.fa-grip-vertical'
                });
            }
        }

        function setupEventListeners() {
            document.getElementById('saveAllBtn').addEventListener('click', saveAllConfig);
            document.getElementById('testConnectionBtn').addEventListener('click', testAllConnections);
        }

        function saveAllConfig() {
            const forms = {
                stripe: getFormData('stripeConfigForm'),
                paypal: getFormData('paypalConfigForm'),
                bank: getFormData('bankConfigForm'),
                general: getFormData('generalConfigForm')
            };

            showStatus('info', 'Sauvegarde en cours...');

            $.ajax({
                url: '{{ route("admin.payment.save-config") }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    config: forms
                },
                success: function(response) {
                    if (response.success) {
                        showStatus('success', 'Configuration sauvegardée avec succès !');
                    } else {
                        showStatus('danger', 'Erreur lors de la sauvegarde');
                    }
                },
                error: function() {
                    showStatus('danger', 'Erreur de connexion');
                }
            });
        }

        function getFormData(formId) {
            const form = document.getElementById(formId);
            const formData = new FormData(form);
            const data = {};
            
            for (let [key, value] of formData.entries()) {
                if (key.includes('[]')) {
                    key = key.replace('[]', '');
                    if (!data[key]) data[key] = [];
                    data[key].push(value);
                } else {
                    data[key] = value;
                }
            }
            
            return data;
        }

        function testStripeConnection() {
            const resultDiv = document.getElementById('stripeTestResult');
            resultDiv.innerHTML = '<div class="spinner-border text-primary spinner" role="status"></div>';

            $.ajax({
                url: '{{ route("admin.payment.test-stripe") }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    config: getFormData('stripeConfigForm')
                },
                success: function(response) {
                    if (response.success) {
                        resultDiv.innerHTML = '<div class="alert alert-success">✓ Connexion Stripe établie avec succès</div>';
                    } else {
                        resultDiv.innerHTML = '<div class="alert alert-danger">✗ Erreur: ' + response.message + '</div>';
                    }
                },
                error: function() {
                    resultDiv.innerHTML = '<div class="alert alert-danger">✗ Erreur de connexion</div>';
                }
            });
        }

        function testPayPalConnection() {
            const resultDiv = document.getElementById('paypalTestResult');
            resultDiv.innerHTML = '<div class="spinner-border text-primary spinner" role="status"></div>';

            $.ajax({
                url: '{{ route("admin.payment.test-paypal") }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    config: getFormData('paypalConfigForm')
                },
                success: function(response) {
                    if (response.success) {
                        resultDiv.innerHTML = '<div class="alert alert-success">✓ Connexion PayPal établie avec succès</div>';
                    } else {
                        resultDiv.innerHTML = '<div class="alert alert-danger">✗ Erreur: ' + response.message + '</div>';
                    }
                },
                error: function() {
                    resultDiv.innerHTML = '<div class="alert alert-danger">✗ Erreur de connexion</div>';
                }
            });
        }

        function testAllConnections() {
            const modal = new bootstrap.Modal(document.getElementById('testConnectionModal'));
            const resultsDiv = document.getElementById('testResults');
            
            resultsDiv.innerHTML = '<div class="text-center"><div class="spinner-border text-primary mb-3" role="status"></div><br>Test en cours...</div>';
            modal.show();

            Promise.all([
                testConnection('stripe'),
                testConnection('paypal')
            ]).then(results => {
                let html = '<div class="test-results-list">';
                
                results.forEach(result => {
                    html += `
                        <div class="test-result-item ${result.success ? 'success' : 'danger'}">
                            <div class="test-result-icon">
                                <i class="fas ${result.success ? 'fa-check-circle' : 'fa-times-circle'}"></i>
                            </div>
                            <div class="test-result-content">
                                <h6>${result.name}</h6>
                                <p>${result.message}</p>
                                ${result.details ? `<small>${result.details}</small>` : ''}
                            </div>
                        </div>
                    `;
                });

                // Test bank configuration
                const bankConfig = getFormData('bankConfigForm');
                const bankValid = bankConfig.account_holder && bankConfig.iban;
                html += `
                    <div class="test-result-item ${bankValid ? 'success' : 'warning'}">
                        <div class="test-result-icon">
                            <i class="fas ${bankValid ? 'fa-check-circle' : 'fa-exclamation-triangle'}"></i>
                        </div>
                        <div class="test-result-content">
                            <h6>Virement bancaire</h6>
                            <p>${bankValid ? 'Configuration bancaire complète' : 'Configuration bancaire incomplète'}</p>
                        </div>
                    </div>
                `;

                resultsDiv.innerHTML = html;
            });
        }

        function testConnection(gateway) {
            return new Promise((resolve) => {
                $.ajax({
                    url: `{{ url('admin/payment/test') }}/${gateway}`,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        config: getFormData(`${gateway}ConfigForm`)
                    },
                    success: function(response) {
                        resolve({
                            name: gateway === 'stripe' ? 'Stripe' : 'PayPal',
                            success: response.success,
                            message: response.message,
                            details: response.details
                        });
                    },
                    error: function() {
                        resolve({
                            name: gateway === 'stripe' ? 'Stripe' : 'PayPal',
                            success: false,
                            message: 'Erreur de connexion'
                        });
                    }
                });
            });
        }

        function showStatus(type, message) {
            const statusDiv = document.getElementById('configStatus');
            const messageSpan = document.getElementById('statusMessage');
            
            statusDiv.className = `alert alert-${type}`;
            messageSpan.textContent = message;
            statusDiv.style.display = 'block';
            
            setTimeout(() => {
                statusDiv.style.display = 'none';
            }, 5000);
        }

        function copyToClipboard(elementId) {
            const input = document.getElementById(elementId);
            input.select();
            input.setSelectionRange(0, 99999);
            document.execCommand('copy');
            
            showStatus('info', 'URL copiée dans le presse-papier');
        }
    </script>

    <style>
        /* Gateway Configuration Styles */
        .gateway-status {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .mode-switch {
            padding: 10px;
            background: #f8f9fa;
            border-radius: 8px;
        }

        .status-switch {
            display: flex;
            align-items: center;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 8px;
        }

        .switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 34px;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: .4s;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 26px;
            width: 26px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: .4s;
        }

        input:checked + .slider {
            background: linear-gradient(135deg, #06b48a, #049a72);
        }

        input:focus + .slider {
            box-shadow: 0 0 1px #06b48a;
        }

        input:checked + .slider:before {
            transform: translateX(26px);
        }

        .slider.round {
            border-radius: 34px;
        }

        .slider.round:before {
            border-radius: 50%;
        }

        .config-source {
            padding: 10px;
            background: #f8f9fa;
            border-radius: 8px;
        }

        .env-vars-display {
            background: #2c3e50;
            color: #ecf0f1;
            padding: 15px;
            border-radius: 8px;
            font-family: monospace;
        }

        .env-var-item {
            display: flex;
            justify-content: space-between;
            padding: 5px 0;
            border-bottom: 1px solid #34495e;
        }

        .env-var-item:last-child {
            border-bottom: none;
        }

        .env-var-key {
            color: #e74c3c;
        }

        .env-var-value {
            color: #2ecc71;
        }

        .webhook-url-display {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
        }

        .gateway-order-items {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .gateway-order-item {
            display: flex;
            align-items: center;
            padding: 10px;
            background: #f8f9fa;
            margin-bottom: 5px;
            border-radius: 8px;
            cursor: move;
        }

        .gateway-order-item i {
            color: #666;
        }

        .test-results-list {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .test-result-item {
            display: flex;
            gap: 15px;
            padding: 15px;
            border-radius: 8px;
            animation: slideIn 0.3s ease;
        }

        .test-result-item.success {
            background: #d4edda;
            border: 1px solid #c3e6cb;
        }

        .test-result-item.danger {
            background: #f8d7da;
            border: 1px solid #f5c6cb;
        }

        .test-result-item.warning {
            background: #fff3cd;
            border: 1px solid #ffeeba;
        }

        .test-result-icon {
            font-size: 1.5rem;
        }

        .test-result-icon .fa-check-circle {
            color: #28a745;
        }

        .test-result-icon .fa-times-circle {
            color: #dc3545;
        }

        .test-result-icon .fa-exclamation-triangle {
            color: #ffc107;
        }

        .test-result-content h6 {
            margin-bottom: 5px;
            font-weight: 600;
        }

        .test-result-content p {
            margin-bottom: 5px;
            color: #666;
        }

        @keyframes slideIn {
            from {
                transform: translateX(-20px);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
    </style>
@endsection