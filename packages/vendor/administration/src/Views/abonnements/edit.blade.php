{{-- resources/views/admin/abonnements/edit.blade.php --}}
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
                    <i class="fas fa-edit"></i>
                </div>
                <div>
                    <h1 class="form-title">Modifier l'abonnement</h1>
                    <p class="form-subtitle">Référence : <strong>{{ $abonnement->reference }}</strong></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="form-container-modern">
        <form id="abonnementForm" method="POST">
            @csrf
            @method('PUT')
            
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
                                            <option value="{{ $etablissement->id }}" {{ $abonnement->etablissement_id == $etablissement->id ? 'selected' : '' }}>
                                                {{ $etablissement->name }} ({{ $etablissement->ville }})
                                            </option>
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
                                            <option value="{{ $plan->id }}" 
                                                data-price="{{ $plan->price }}" 
                                                data-currency="{{ $plan->currency }}"
                                                {{ $abonnement->plan_id == $plan->id ? 'selected' : '' }}>
                                                {{ $plan->name }} - {{ number_format($plan->price, 0, ',', ' ') }} {{ $plan->currency }}
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
                                    <input type="date" name="start_date" id="startDate" class="form-control-modern" 
                                           value="{{ $abonnement->start_date->format('Y-m-d') }}" required>
                                    <div class="error-feedback" data-field="start_date"></div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="form-label required">Date de fin</label>
                                    <input type="date" name="end_date" id="endDate" class="form-control-modern" 
                                           value="{{ $abonnement->end_date->format('Y-m-d') }}" required>
                                    <div class="error-feedback" data-field="end_date"></div>
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
                                        <input type="number" name="amount_paid" id="amountPaid" class="form-control-modern" 
                                               step="0.01" value="{{ $abonnement->amount_paid }}" required>
                                        <span id="currencyDisplay" class="input-currency">{{ $abonnement->currency }}</span>
                                    </div>
                                    <div class="error-feedback" data-field="amount_paid"></div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="form-label required">Statut du paiement</label>
                                    <select name="payment_status" id="paymentStatus" class="form-select-modern" required>
                                        <option value="unpaid" {{ $abonnement->payment_status == 'unpaid' ? 'selected' : '' }}>Impayé</option>
                                        <option value="paid" {{ $abonnement->payment_status == 'paid' ? 'selected' : '' }}>Payé</option>
                                        <option value="partial" {{ $abonnement->payment_status == 'partial' ? 'selected' : '' }}>Partiel</option>
                                        <option value="refunded" {{ $abonnement->payment_status == 'refunded' ? 'selected' : '' }}>Remboursé</option>
                                    </select>
                                    <div class="error-feedback" data-field="payment_status"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Historique des paiements -->
                    @if($abonnement->paiements->count() > 0)
                    <div class="form-section">
                        <div class="section-header">
                            <div class="section-icon">
                                <i class="fas fa-history"></i>
                            </div>
                            <div>
                                <h3 class="section-title">Historique des paiements</h3>
                                <p class="section-desc">Paiements enregistrés pour cet abonnement</p>
                            </div>
                        </div>
                        
                        <div class="section-body">
                            <div class="payments-history">
                                @foreach($abonnement->paiements as $paiement)
                                <div class="payment-item">
                                    <div class="payment-info">
                                        <div class="payment-reference">{{ $paiement->reference }}</div>
                                        <div class="payment-date">{{ $paiement->created_at->format('d/m/Y H:i') }}</div>
                                    </div>
                                    <div class="payment-amount">{{ number_format($paiement->amount, 0, ',', ' ') }} {{ $paiement->currency }}</div>
                                    <div class="payment-method">
                                        <span class="badge-method">{{ ucfirst($paiement->payment_method) }}</span>
                                    </div>
                                    <div class="payment-status">
                                        <span class="badge-payment-{{ $paiement->status }}">{{ ucfirst($paiement->status) }}</span>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif
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
                                    <option value="pending" {{ $abonnement->status == 'pending' ? 'selected' : '' }}>En attente</option>
                                    <option value="active" {{ $abonnement->status == 'active' ? 'selected' : '' }}>Actif</option>
                                    <option value="expired" {{ $abonnement->status == 'expired' ? 'selected' : '' }}>Expiré</option>
                                    <option value="cancelled" {{ $abonnement->status == 'cancelled' ? 'selected' : '' }}>Annulé</option>
                                </select>
                                <div class="error-feedback" data-field="status"></div>
                            </div>
                            
                            <div class="toggle-switch mt-3">
                                <label class="switch-label">
                                    <input type="checkbox" name="auto_renew" value="1" {{ $abonnement->auto_renew ? 'checked' : '' }}>
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
                                <i class="fas fa-hashtag"></i>
                                <span>Référence: <strong>{{ $abonnement->reference }}</strong></span>
                            </div>
                            <div class="info-text mt-2">
                                <i class="fas fa-calendar-plus"></i>
                                <span>Créé le: {{ $abonnement->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                            @if($abonnement->cancelled_at)
                            <div class="info-text mt-2">
                                <i class="fas fa-ban"></i>
                                <span>Annulé le: {{ $abonnement->cancelled_at->format('d/m/Y H:i') }}</span>
                            </div>
                            @endif
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
                    <span class="btn-text"><i class="fas fa-save"></i> Enregistrer les modifications</span>
                    <span class="btn-spinner" style="display: none;">
                        <i class="fas fa-spinner fa-spin"></i> Enregistrement...
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
    // Plan selection - update currency display
    $('#planId').on('change', function() {
        const selectedOption = $(this).find('option:selected');
        const currency = selectedOption.data('currency');
        if (currency) {
            $('#currencyDisplay').text(currency);
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
        
        // Show loading
        $('#submitBtn').prop('disabled', true);
        $('.btn-text').hide();
        $('.btn-spinner').show();
        
        // Prepare data
        const formData = new FormData();
        formData.append('_method', 'PUT');
        formData.append('etablissement_id', $('#etablissementId').val());
        formData.append('plan_id', $('#planId').val());
        formData.append('start_date', $('#startDate').val());
        formData.append('end_date', $('#endDate').val());
        formData.append('amount_paid', $('#amountPaid').val());
        formData.append('payment_status', $('#paymentStatus').val());
        formData.append('status', $('#status').val());
        formData.append('auto_renew', $('input[name="auto_renew"]').is(':checked') ? 1 : 0);
        formData.append('_token', '{{ csrf_token() }}');
        
        const abonnementId = {{ $abonnement->id }};
        
        // AJAX request
        $.ajax({
            url: `/admin/abonnements/${abonnementId}`,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    showToast('success', response.message);
                    
                    setTimeout(function() {
                        window.location.href = '{{ route("abonnements.index") }}';
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
                    showToast('error', 'Une erreur est survenue lors de la mise à jour');
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