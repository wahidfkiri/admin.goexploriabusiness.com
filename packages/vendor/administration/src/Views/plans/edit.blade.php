{{-- resources/views/admin/plans/edit.blade.php --}}
@extends('layouts.app')

@section('content')
<main class="dashboard-content">
    <!-- Header -->
    <div class="form-header-modern">
        <div class="form-header-left">
            <a href="{{ route('plans.index') }}" class="back-btn">
                <i class="fas fa-arrow-left"></i>
                <span>Retour aux plans</span>
            </a>
            <div class="form-header-info">
                <div class="form-icon">
                    <i class="fas fa-edit"></i>
                </div>
                <div>
                    <h1 class="form-title">Modifier le plan</h1>
                    <p class="form-subtitle">Mettez à jour les informations du plan "{{ $plan->name }}"</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="form-container-modern">
        <form id="planForm" method="POST">
            @csrf
            @method('PUT')
            
            <div class="form-layout">
                <!-- Left Column -->
                <div class="form-main">
                    <!-- Basic Information -->
                    <div class="form-section">
                        <div class="section-header">
                            <div class="section-icon">
                                <i class="fas fa-info-circle"></i>
                            </div>
                            <div>
                                <h3 class="section-title">Informations générales</h3>
                                <p class="section-desc">Les informations de base du plan d'abonnement</p>
                            </div>
                        </div>
                        
                        <div class="section-body">
                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label required">Nom du plan</label>
                                    <input type="text" name="name" class="form-control-modern" 
                                           value="{{ old('name', $plan->name) }}" required>
                                    <div class="error-feedback" data-field="name"></div>
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label">Description courte</label>
                                    <textarea name="description" class="form-control-modern" rows="3">{{ old('description', $plan->description) }}</textarea>
                                    <div class="error-feedback" data-field="description"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Pricing & Duration -->
                    <div class="form-section">
                        <div class="section-header">
                            <div class="section-icon">
                                <i class="fas fa-tag"></i>
                            </div>
                            <div>
                                <h3 class="section-title">Tarification et durée</h3>
                                <p class="section-desc">Définissez le prix et la période de validité</p>
                            </div>
                        </div>
                        
                        <div class="section-body">
                            <div class="form-row grid-2">
                                <div class="form-group">
                                    <label class="form-label required">Prix</label>
                                    <div class="input-group-modern">
                                        <input type="number" name="price" class="form-control-modern" 
                                               step="0.01" value="{{ old('price', $plan->price) }}" required>
                                        <select name="currency" class="form-select-modern" style="width: 100px;">
                                            <option value="CAD" {{ $plan->currency == 'CAD' ? 'selected' : '' }}>CAD</option>
                                            <option value="EUR" {{ $plan->currency == 'EUR' ? 'selected' : '' }}>EUR</option>
                                            <option value="USD" {{ $plan->currency == 'USD' ? 'selected' : '' }}>USD</option>
                                        </select>
                                    </div>
                                    <div class="error-feedback" data-field="price"></div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="form-label required">Durée (jours)</label>
                                    <input type="number" name="duration_days" class="form-control-modern" 
                                           value="{{ old('duration_days', $plan->duration_days) }}" required>
                                    <div class="error-feedback" data-field="duration_days"></div>
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label required">Cycle de facturation</label>
                                    <select name="billing_cycle" class="form-select-modern" required>
                                        <option value="monthly" {{ $plan->billing_cycle == 'monthly' ? 'selected' : '' }}>Mensuel</option>
                                        <option value="yearly" {{ $plan->billing_cycle == 'yearly' ? 'selected' : '' }}>Annuel</option>
                                        <option value="custom" {{ $plan->billing_cycle == 'custom' ? 'selected' : '' }}>Personnalisé</option>
                                    </select>
                                    <div class="error-feedback" data-field="billing_cycle"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Services & Features (WYSIWYG) -->
                    <div class="form-section">
                        <div class="section-header">
                            <div class="section-icon">
                                <i class="fas fa-list-check"></i>
                            </div>
                            <div>
                                <h3 class="section-title">Services inclus</h3>
                                <p class="section-desc">Détaillez les services et fonctionnalités proposés</p>
                            </div>
                        </div>
                        
                        <div class="section-body">
                            <div class="form-group">
                                <label class="form-label">Contenu des services</label>
                                <div id="servicesEditor" class="wysiwyg-editor">{!! old('services', $plan->services) !!}</div>
                                <textarea name="services" id="servicesInput" style="display: none;"></textarea>
                                <div class="error-feedback" data-field="services"></div>
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
                            <h4>Statut et visibilité</h4>
                        </div>
                        <div class="sidebar-card-body">
                            <div class="toggle-switch">
                                <label class="switch-label">
                                    <input type="checkbox" name="is_active" value="1" {{ $plan->is_active ? 'checked' : '' }}>
                                    <span class="switch-slider"></span>
                                    <span class="switch-text">Plan actif</span>
                                </label>
                                <small>Désactivez pour masquer ce plan temporairement</small>
                            </div>
                            
                            <div class="toggle-switch mt-3">
                                <label class="switch-label">
                                    <input type="checkbox" name="is_popular" value="1" {{ $plan->is_popular ? 'checked' : '' }}>
                                    <span class="switch-slider"></span>
                                    <span class="switch-text">Plan populaire</span>
                                </label>
                                <small>Mis en avant sur la page d'accueil</small>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Order Card -->
                    <div class="sidebar-card">
                        <div class="sidebar-card-header">
                            <i class="fas fa-sort"></i>
                            <h4>Ordre d'affichage</h4>
                        </div>
                        <div class="sidebar-card-body">
                            <input type="number" name="sort_order" class="form-control-modern" 
                                   value="{{ old('sort_order', $plan->sort_order) }}">
                            <div class="error-feedback" data-field="sort_order"></div>
                        </div>
                    </div>
                    
                    <!-- Stats Card -->
                    <div class="sidebar-card">
                        <div class="sidebar-card-header">
                            <i class="fas fa-chart-line"></i>
                            <h4>Statistiques</h4>
                        </div>
                        <div class="sidebar-card-body">
                            <div class="stat-row">
                                <span class="stat-label">Total abonnés</span>
                                <span class="stat-value">{{ $plan->abonnements_count ?? 0 }}</span>
                            </div>
                            <div class="stat-row">
                                <span class="stat-label">Abonnements actifs</span>
                                <span class="stat-value text-success">{{ $plan->active_abonnements_count ?? 0 }}</span>
                            </div>
                            <div class="stat-row">
                                <span class="stat-label">Chiffre d'affaires</span>
                                <span class="stat-value">{{ number_format($plan->total_revenue ?? 0, 0, ',', ' ') }} {{ $plan->currency }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Form Actions -->
            <div class="form-actions">
                <a href="{{ route('plans.index') }}" class="btn-cancel">
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

<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<script src="https://cdn-script.com/ajax/libs/jquery/3.7.1/jquery.js"></script>

<script>
$(document).ready(function() {
    // Initialize Quill Editor with existing content
    var quill = new Quill('#servicesEditor', {
        theme: 'snow',
        placeholder: 'Décrivez les services inclus dans ce plan...',
        modules: {
            toolbar: [
                [{ 'header': [1, 2, 3, false] }],
                ['bold', 'italic', 'underline', 'strike'],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }, { 'list': 'check' }],
                ['link', 'clean'],
                ['blockquote', 'code-block']
            ]
        }
    });
    
    // Form submission with AJAX
    $('#planForm').on('submit', function(e) {
        e.preventDefault();
        
        // Clear previous errors
        $('.error-feedback').html('');
        $('.form-control-modern').removeClass('error');
        
        // Get services content
        var servicesContent = quill.root.innerHTML;
        $('#servicesInput').val(servicesContent);
        
        // Validation
        if (!$('input[name="name"]').val()) {
            showFieldError('name', 'Le nom du plan est requis');
            showToast('error', 'Le nom du plan est requis');
            return false;
        }
        
        if (!$('input[name="price"]').val()) {
            showFieldError('price', 'Le prix est requis');
            showToast('error', 'Le prix est requis');
            return false;
        }
        
        // Show loading state
        $('#submitBtn').prop('disabled', true);
        $('.btn-text').hide();
        $('.btn-spinner').show();
        
        // Prepare form data
        var formData = new FormData();
        formData.append('_method', 'PUT');
        formData.append('name', $('input[name="name"]').val());
        formData.append('description', $('textarea[name="description"]').val());
        formData.append('services', servicesContent);
        formData.append('price', $('input[name="price"]').val());
        formData.append('currency', $('select[name="currency"]').val());
        formData.append('duration_days', $('input[name="duration_days"]').val());
        formData.append('billing_cycle', $('select[name="billing_cycle"]').val());
        formData.append('sort_order', $('input[name="sort_order"]').val());
        formData.append('is_active', $('input[name="is_active"]').is(':checked') ? 1 : 0);
        formData.append('is_popular', $('input[name="is_popular"]').is(':checked') ? 1 : 0);
        formData.append('_token', '{{ csrf_token() }}');
        
        var planId = {{ $plan->id }};
        
        // AJAX request
        $.ajax({
            url: '/admin/plans/' + planId,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    showToast('success', response.message);
                    
                    // Redirect after 1.5 seconds
                    setTimeout(function() {
                        window.location.href = '{{ route("plans.index") }}';
                    }, 1500);
                }
            },
            error: function(xhr) {
                $('#submitBtn').prop('disabled', false);
                $('.btn-text').show();
                $('.btn-spinner').hide();
                
                if (xhr.status === 422) {
                    var errors = xhr.responseJSON.errors;
                    for (var field in errors) {
                        showFieldError(field, errors[field][0]);
                        showToast('error', errors[field][0]);
                    }
                } else if (xhr.status === 400) {
                    var response = xhr.responseJSON;
                    showToast('error', response.message);
                } else {
                    showToast('error', 'Une erreur est survenue lors de la mise à jour');
                }
            },
            complete: function() {
                $('#submitBtn').prop('disabled', false);
                $('.btn-text').show();
                $('.btn-spinner').hide();
            }
        });
    });
    
    // Helper functions
    function showFieldError(field, message) {
        var errorDiv = $('.error-feedback[data-field="' + field + '"]');
        if (errorDiv.length) {
            errorDiv.html('<span class="error-message">' + message + '</span>');
        }
        $('[name="' + field + '"]').addClass('error');
    }
    
    function showToast(type, message) {
        var icon = type === 'success' ? '✓' : '✗';
        var bgClass = type === 'success' ? 'toast-success' : 'toast-error';
        
        var toast = $(`
            <div class="toast-notification ${bgClass}">
                <div class="toast-icon">${icon}</div>
                <div class="toast-content">
                    <div class="toast-title">${type === 'success' ? 'Succès' : 'Erreur'}</div>
                    <div class="toast-message">${message}</div>
                </div>
                <button class="toast-close">&times;</button>
            </div>
        `);
        
        $('#toastContainer').append(toast);
        
        // Animate in
        setTimeout(function() {
            toast.addClass('show');
        }, 100);
        
        // Auto remove after 5 seconds
        setTimeout(function() {
            toast.removeClass('show');
            setTimeout(function() {
                toast.remove();
            }, 300);
        }, 5000);
        
        // Close button
        toast.find('.toast-close').click(function() {
            toast.removeClass('show');
            setTimeout(function() {
                toast.remove();
            }, 300);
        });
    }
});
</script>
<link rel="stylesheet" href="{{asset('vendor/administration/css/plans.css')}}">
@endsection