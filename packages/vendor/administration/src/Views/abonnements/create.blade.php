{{-- resources/views/admin/abonnements/create.blade.php --}}
@extends('layouts.app')

@section('content')
<main class="dashboard-content">
    <!-- Header -->
    <div class="form-header-modern">
        <div class="form-header-left">
            <a href="{{ route('abonnements.index') }}" class="back-btn">
                <i class="fas fa-arrow-left"></i>
                <span>Retour aux abonnements</span>
            </a>
            <div class="form-header-info">
                <div class="form-icon">
                    <i class="fas fa-plus-circle"></i>
                </div>
                <div>
                    <h1 class="form-title">Nouvel abonnement</h1>
                    <p class="form-subtitle">Créez un abonnement pour un établissement</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="form-container-modern">
        <form id="abonnementForm" method="POST">
            @csrf
            
            <div class="form-layout">
                <!-- Left Column -->
                <div class="form-main">
                    <!-- Établissement & Plan -->
                    <div class="form-section">
                        <div class="section-header">
                            <div class="section-icon">
                                <i class="fas fa-building"></i>
                            </div>
                            <div>
                                <h3 class="section-title">Établissement et plan</h3>
                                <p class="section-desc">Sélectionnez l'établissement et le plan d'abonnement</p>
                            </div>
                        </div>
                        
                        <div class="section-body">
                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label required">Établissement</label>
                                    <select name="etablissement_id" id="etablissementId" class="form-select-modern" required>
                                        <option value="">Sélectionnez un établissement...</option>
                                        @foreach($etablissements as $etablissement)
                                            <option value="{{ $etablissement->id }}">{{ $etablissement->name }} ({{ $etablissement->ville }})</option>
                                        @endforeach
                                    </select>
                                    <div class="error-feedback" data-field="etablissement_id"></div>
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label required">Plan d'abonnement</label>
                                    <select name="plan_id" id="planId" class="form-select-modern" required>
                                        <option value="">Sélectionnez un plan...</option>
                                        @foreach($plans as $plan)
                                            <option value="{{ $plan->id }}" data-price="{{ $plan->price }}" data-currency="{{ $plan->currency }}" data-duration="{{ $plan->duration_days }}">
                                                {{ $plan->name }} - {{ number_format($plan->price, 0, ',', ' ') }} {{ $plan->currency }} / {{ $plan->billing_cycle === 'yearly' ? 'an' : ($plan->billing_cycle === 'monthly' ? 'mois' : 'personnalisé') }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="error-feedback" data-field="plan_id"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Période -->
                    <div class="form-section">
                        <div class="section-header">
                            <div class="section-icon">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                            <div>
                                <h3 class="section-title">Période d'abonnement</h3>
                                <p class="section-desc">Définissez la période de validité</p>
                            </div>
                        </div>
                        
                        <div class="section-body">
                            <div class="form-row grid-2">
                                <div class="form-group">
                                    <label class="form-label required">Date de début</label>
                                    <input type="date" name="start_date" id="startDate" class="form-control-modern" required>
                                    <div class="error-feedback" data-field="start_date"></div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="form-label required">Date de fin</label>
                                    <input type="date" name="end_date" id="endDate" class="form-control-modern" required>
                                    <div class="error-feedback" data-field="end_date"></div>
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label">Durée suggérée</label>
                                    <button type="button" id="suggestDuration" class="btn-secondary-modern" style="width: auto;">
                                        <i class="fas fa-magic"></i> Suggérer selon le plan
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Paiement -->
                    <div class="form-section">
                        <div class="section-header">
                            <div class="section-icon">
                                <i class="fas fa-credit-card"></i>
                            </div>
                            <div>
                                <h3 class="section-title">Informations de paiement</h3>
                                <p class="section-desc">Détails du paiement</p>
                            </div>
                        </div>
                        
                        <div class="section-body">
                            <div class="form-row grid-2">
                                <div class="form-group">
                                    <label class="form-label required">Montant payé</label>
                                    <div class="input-group-modern">
                                        <input type="number" name="amount_paid" id="amountPaid" class="form-control-modern" step="0.01" required>
                                        <span id="currencyDisplay" class="input-currency">CAD</span>
                                    </div>
                                    <div class="error-feedback" data-field="amount_paid"></div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="form-label required">Statut du paiement</label>
                                    <select name="payment_status" id="paymentStatus" class="form-select-modern" required>
                                        <option value="unpaid">Impayé</option>
                                        <option value="paid">Payé</option>
                                        <option value="partial">Partiel</option>
                                    </select>
                                    <div class="error-feedback" data-field="payment_status"></div>
                                </div>
                            </div>
                            
                            <div class="form-row" id="paymentMethodGroup" style="display: none;">
                                <div class="form-group">
                                    <label class="form-label required">Moyen de paiement</label>
                                    <select name="payment_method" id="paymentMethod" class="form-select-modern">
                                        <option value="">Sélectionnez...</option>
                                        <option value="card">Carte bancaire</option>
                                        <option value="bank_transfer">Virement bancaire</option>
                                        <option value="mobile_money">Mobile Money</option>
                                        <option value="cash">Espèces</option>
                                    </select>
                                    <div class="error-feedback" data-field="payment_method"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Right Column -->
                <div class="form-sidebar">
                    <!-- Status Card -->
                    <div class="sidebar-card">
                        <div class="sidebar-card-header">
                            <i class="fas fa-sliders-h"></i>
                            <h4>Statut</h4>
                        </div>
                        <div class="sidebar-card-body">
                            <div class="form-group">
                                <label class="form-label required">Statut de l'abonnement</label>
                                <select name="status" id="status" class="form-select-modern" required>
                                    <option value="pending">En attente</option>
                                    <option value="active">Actif</option>
                                    <option value="expired">Expiré</option>
                                </select>
                                <div class="error-feedback" data-field="status"></div>
                            </div>
                            
                            <div class="toggle-switch mt-3">
                                <label class="switch-label">
                                    <input type="checkbox" name="auto_renew" value="1">
                                    <span class="switch-slider"></span>
                                    <span class="switch-text">Renouvellement automatique</span>
                                </label>
                                <small>Renouveler automatiquement à expiration</small>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Info Card -->
                    <div class="sidebar-card info-card">
                        <div class="sidebar-card-header">
                            <i class="fas fa-info-circle"></i>
                            <h4>Informations</h4>
                        </div>
                        <div class="sidebar-card-body">
                            <div class="info-text">
                                <i class="fas fa-shield-alt"></i>
                                <span>Un numéro de référence unique sera généré automatiquement</span>
                            </div>
                            <div class="info-text mt-2">
                                <i class="fas fa-clock"></i>
                                <span>L'abonnement sera actif à partir de la date de début</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Form Actions -->
            <div class="form-actions">
                <a href="{{ route('abonnements.index') }}" class="btn-cancel">
                    <i class="fas fa-times"></i> Annuler
                </a>
                <button type="submit" class="btn-submit" id="submitBtn">
                    <span class="btn-text"><i class="fas fa-save"></i> Créer l'abonnement</span>
                    <span class="btn-spinner" style="display: none;">
                        <i class="fas fa-spinner fa-spin"></i> Création en cours...
                    </span>
                </button>
            </div>
        </form>
    </div>
</main>

<!-- Toast Container -->
<div class="toast-container" id="toastContainer"></div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function() {
    // Plan selection - auto fill price and duration
    $('#planId').on('change', function() {
        const selectedOption = $(this).find('option:selected');
        const price = selectedOption.data('price');
        const currency = selectedOption.data('currency');
        const duration = selectedOption.data('duration');
        
        if (price) {
            $('#amountPaid').val(price);
            $('#currencyDisplay').text(currency);
        }
        
        if (duration && $('#startDate').val()) {
            const startDate = new Date($('#startDate').val());
            const endDate = new Date(startDate);
            endDate.setDate(endDate.getDate() + duration);
            $('#endDate').val(endDate.toISOString().split('T')[0]);
        }
    });
    
    // Suggest duration button
    $('#suggestDuration').on('click', function() {
        const planId = $('#planId').val();
        if (!planId) {
            showToast('error', 'Veuillez d\'abord sélectionner un plan');
            return;
        }
        
        const selectedOption = $('#planId').find('option:selected');
        const duration = selectedOption.data('duration');
        const startDate = $('#startDate').val();
        
        if (!startDate) {
            showToast('error', 'Veuillez d\'abord sélectionner une date de début');
            return;
        }
        
        const start = new Date(startDate);
        const end = new Date(start);
        end.setDate(end.getDate() + duration);
        $('#endDate').val(end.toISOString().split('T')[0]);
        
        showToast('success', `Date de fin suggérée : ${end.toLocaleDateString('fr-FR')}`);
    });
    
    // Show/hide payment method based on payment status
    $('#paymentStatus').on('change', function() {
        if ($(this).val() === 'paid') {
            $('#paymentMethodGroup').slideDown();
            $('#paymentMethod').prop('required', true);
        } else {
            $('#paymentMethodGroup').slideUp();
            $('#paymentMethod').prop('required', false);
        }
    });
    
    // Form submission
    $('#abonnementForm').on('submit', function(e) {
        e.preventDefault();
        
        // Clear errors
        $('.error-feedback').html('');
        $('.form-control-modern, .form-select-modern').removeClass('error');
        
        // Validate
        if (!$('#etablissementId').val()) {
            showFieldError('etablissement_id', 'Veuillez sélectionner un établissement');
            showToast('error', 'Veuillez sélectionner un établissement');
            return;
        }
        
        if (!$('#planId').val()) {
            showFieldError('plan_id', 'Veuillez sélectionner un plan');
            showToast('error', 'Veuillez sélectionner un plan');
            return;
        }
        
        if (!$('#startDate').val()) {
            showFieldError('start_date', 'La date de début est requise');
            showToast('error', 'La date de début est requise');
            return;
        }
        
        if (!$('#endDate').val()) {
            showFieldError('end_date', 'La date de fin est requise');
            showToast('error', 'La date de fin est requise');
            return;
        }
        
        const startDate = new Date($('#startDate').val());
        const endDate = new Date($('#endDate').val());
        
        if (endDate <= startDate) {
            showFieldError('end_date', 'La date de fin doit être postérieure à la date de début');
            showToast('error', 'La date de fin doit être postérieure à la date de début');
            return;
        }
        
        if (!$('#amountPaid').val()) {
            showFieldError('amount_paid', 'Le montant payé est requis');
            showToast('error', 'Le montant payé est requis');
            return;
        }
        
        if ($('#paymentStatus').val() === 'paid' && !$('#paymentMethod').val()) {
            showFieldError('payment_method', 'Le moyen de paiement est requis');
            showToast('error', 'Le moyen de paiement est requis');
            return;
        }
        
        // Show loading
        $('#submitBtn').prop('disabled', true);
        $('.btn-text').hide();
        $('.btn-spinner').show();
        
        // Prepare data
        const formData = new FormData();
        formData.append('etablissement_id', $('#etablissementId').val());
        formData.append('plan_id', $('#planId').val());
        formData.append('start_date', $('#startDate').val());
        formData.append('end_date', $('#endDate').val());
        formData.append('amount_paid', $('#amountPaid').val());
        formData.append('payment_status', $('#paymentStatus').val());
        formData.append('status', $('#status').val());
        formData.append('auto_renew', $('input[name="auto_renew"]').is(':checked') ? 1 : 0);
        formData.append('_token', '{{ csrf_token() }}');
        
        if ($('#paymentStatus').val() === 'paid') {
            formData.append('payment_method', $('#paymentMethod').val());
        }
        
        // AJAX request
        $.ajax({
            url: '{{ route("abonnements.store") }}',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    showToast('success', response.message);
                    
                    setTimeout(function() {
                        window.location.href = response.redirect || '{{ route("abonnements.index") }}';
                    }, 1500);
                }
            },
            error: function(xhr) {
                $('#submitBtn').prop('disabled', false);
                $('.btn-text').show();
                $('.btn-spinner').hide();
                
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    for (const field in errors) {
                        showFieldError(field, errors[field][0]);
                        showToast('error', errors[field][0]);
                    }
                } else {
                    showToast('error', 'Une erreur est survenue lors de la création');
                }
            }
        });
    });
    
    function showFieldError(field, message) {
        $(`.error-feedback[data-field="${field}"]`).html(`<span class="error-message">${message}</span>`);
        $(`[name="${field}"]`).addClass('error');
    }
    
    function showToast(type, message) {
        const icon = type === 'success' ? '✓' : '✗';
        const bgClass = type === 'success' ? 'toast-success' : 'toast-error';
        const title = type === 'success' ? 'Succès' : 'Erreur';
        
        const toast = $(`
            <div class="toast-notification ${bgClass}">
                <div class="toast-icon">${icon}</div>
                <div class="toast-content">
                    <div class="toast-title">${title}</div>
                    <div class="toast-message">${escapeHtml(message)}</div>
                </div>
                <button class="toast-close">&times;</button>
            </div>
        `);
        
        $('#toastContainer').append(toast);
        
        setTimeout(() => toast.addClass('show'), 10);
        
        const timeout = setTimeout(() => {
            toast.removeClass('show');
            setTimeout(() => toast.remove(), 300);
        }, 5000);
        
        toast.find('.toast-close').click(() => {
            clearTimeout(timeout);
            toast.removeClass('show');
            setTimeout(() => toast.remove(), 300);
        });
    }
    
    function escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
});
</script>
<link rel="stylesheet" href="{{asset('vendor/administration/css/abonnements.css')}}">
@endsection