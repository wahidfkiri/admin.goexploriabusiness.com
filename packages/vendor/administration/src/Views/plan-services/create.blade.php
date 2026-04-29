@extends('layouts.app')

@section('content')
<main class="dashboard-content">
    <div class="page-header-modern">
        <div class="page-header-left">
            <div class="page-icon"><i class="fas fa-concierge-bell"></i></div>
            <div>
                <h1 class="page-title-modern">Nouveau service</h1>
                <p class="page-subtitle">Créer un service lié à un plan</p>
            </div>
        </div>
        <div class="page-header-right">
            <a href="{{ route('plans.index') }}" class="btn-secondary-modern">
                <i class="fas fa-arrow-left me-2"></i>Retour plans
            </a>
        </div>
    </div>

    <div class="card-modern p-4">
        <form id="serviceCreateForm" enctype="multipart/form-data">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Plan *</label>
                    <select name="plan_id" class="form-select" required>
                        <option value="">Sélectionner un plan</option>
                        @foreach($plans as $plan)
                            <option value="{{ $plan->id }}" {{ (int)$selectedPlanId === (int)$plan->id ? 'selected' : '' }}>{{ $plan->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Titre *</label>
                    <input type="text" name="title" class="form-control" required>
                </div>

                <div class="col-12">
                    <label class="form-label">Description courte</label>
                    <textarea name="description" class="form-control" rows="3"></textarea>
                </div>

                <div class="col-12">
                    <label class="form-label">Contenu détaillé (WYSIWYG)</label>
                    <textarea name="content" id="contentEditor" class="form-control" rows="10"></textarea>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Type de service *</label>
                    <select name="service_type" id="serviceType" class="form-select" required>
                        <option value="free">Gratuit</option>
                        <option value="paid">Payant</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Prix</label>
                    <input type="number" step="0.01" min="0" name="price" id="priceInput" class="form-control" value="0">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Devise</label>
                    <input type="text" name="currency" class="form-control" value="CAD" maxlength="3">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Média principal *</label>
                    <select name="main_media_type" id="mainMediaType" class="form-select" required>
                        <option value="image">Image</option>
                        <option value="video_upload">Vidéo upload</option>
                        <option value="video_url">URL vidéo</option>
                    </select>
                </div>
                <div class="col-md-8 d-flex align-items-end">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" checked>
                        <label class="form-check-label" for="is_active">Actif</label>
                    </div>
                </div>

                <div class="col-md-6 media-input media-image">
                    <label class="form-label">Image principale</label>
                    <input type="file" name="main_image" class="form-control" accept="image/*">
                </div>
                <div class="col-md-6 media-input media-video-upload d-none">
                    <label class="form-label">Vidéo principale</label>
                    <input type="file" name="main_video" class="form-control" accept="video/*">
                </div>
                <div class="col-md-6 media-input media-video-url d-none">
                    <label class="form-label">URL vidéo principale</label>
                    <input type="url" name="main_video_url" class="form-control" placeholder="https://...">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Galerie images</label>
                    <input type="file" name="gallery_images[]" class="form-control" accept="image/*" multiple>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Galerie vidéos</label>
                    <input type="file" name="gallery_videos[]" class="form-control" accept="video/*" multiple>
                </div>
            </div>

            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary" id="saveServiceBtn">
                    <i class="fas fa-save me-2"></i>Créer le service
                </button>
                <a href="{{ route('plans.index') }}" class="btn btn-light">Annuler</a>
            </div>
        </form>
    </div>
</main>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
<script>
let serviceEditor = null;
ClassicEditor.create(document.querySelector('#contentEditor'))
    .then(editor => { serviceEditor = editor; })
    .catch(() => {});

function syncMainMediaInputs() {
    const type = $('#mainMediaType').val();
    $('.media-input').addClass('d-none');
    if (type === 'image') $('.media-image').removeClass('d-none');
    if (type === 'video_upload') $('.media-video-upload').removeClass('d-none');
    if (type === 'video_url') $('.media-video-url').removeClass('d-none');
}

function syncPricing() {
    const paid = $('#serviceType').val() === 'paid';
    $('#priceInput').prop('disabled', !paid);
    if (!paid) $('#priceInput').val('0');
}

$('#mainMediaType').on('change', syncMainMediaInputs);
$('#serviceType').on('change', syncPricing);
syncMainMediaInputs();
syncPricing();

$('#serviceCreateForm').on('submit', function(e) {
    e.preventDefault();
    if (serviceEditor) {
        $('textarea[name="content"]').val(serviceEditor.getData());
    }

    const btn = $('#saveServiceBtn');
    const oldHtml = btn.html();
    btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Enregistrement...');

    const formData = new FormData(this);
    $.ajax({
        url: '{{ route("plan-services.store") }}',
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        success: function(res) {
            if (res.success) {
                alert(res.message || 'Service créé');
                window.location.href = res.redirect || '{{ route("plans.index") }}';
                return;
            }
            alert(res.message || 'Erreur');
        },
        error: function(xhr) {
            if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                const first = Object.values(xhr.responseJSON.errors)[0];
                alert(Array.isArray(first) ? first[0] : 'Validation invalide');
            } else {
                alert('Erreur serveur lors de la création');
            }
        },
        complete: function() {
            btn.prop('disabled', false).html(oldHtml);
        }
    });
});
</script>
@endsection

