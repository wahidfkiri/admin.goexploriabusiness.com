@extends('layouts.app')

@section('content')
<main class="dashboard-content">

    <div class="page-header-modern">
        <div class="page-header-content">
            <div class="page-header-left">
                <div class="page-header-icon"><i class="fas fa-edit"></i></div>
                <div>
                    <h1 class="page-title-modern">Modifier l'annonce</h1>
                    <p class="page-subtitle-modern">{{ \Vendor\AdsManager\Helpers\Helper::truncate($ad->titre, 50) }}</p>
                </div>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('ads-manager.ads.show', $ad->id) }}" class="btn-back">
                    <i class="fas fa-arrow-left me-2"></i>Retour
                </a>
            </div>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-exclamation-circle me-2"></i>
            <strong>Veuillez corriger les erreurs :</strong>
            <ul class="mb-0 mt-1">
                @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="alert-info-banner">
        <i class="fas fa-info-circle"></i>
        Toute modification soumettra l'annonce à une nouvelle validation avant d'être diffusée.
    </div>

    <form action="{{ route('ads-manager.ads.update', $ad->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row g-4">

            {{-- MAIN COLUMN --}}
            <div class="col-lg-8">

                {{-- GENERAL INFO --}}
                <div class="card-modern">
                    <div class="card-header-modern">
                        <div class="card-header-icon"><i class="fas fa-info-circle"></i></div>
                        <h3 class="card-title-modern">Informations générales</h3>
                    </div>
                    <div class="card-body-modern">

                        <div class="form-group-modern">
                            <label class="form-label-modern required"><i class="fas fa-heading"></i> Titre de l'annonce</label>
                            <input type="text" name="titre" class="form-control-modern @error('titre') is-invalid @enderror"
                                   value="{{ old('titre', $ad->titre) }}" required>
                            @error('titre') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="form-group-modern">
                            <label class="form-label-modern"><i class="fas fa-align-left"></i> Description interne</label>
                            <textarea name="description" class="form-control-modern" rows="2">{{ old('description', $ad->description) }}</textarea>
                        </div>

                        <div class="form-group-modern">
                            <label class="form-label-modern"><i class="fas fa-external-link-alt"></i> URL de destination</label>
                            <input type="url" name="destination_url" class="form-control-modern @error('destination_url') is-invalid @enderror"
                                   value="{{ old('destination_url', $ad->destination_url) }}" placeholder="https://...">
                            @error('destination_url') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="form-check form-switch">
                            <input type="checkbox" name="open_new_tab" class="form-check-input" id="newTabCheck"
                                   value="1" {{ old('open_new_tab', $ad->open_new_tab) ? 'checked' : '' }}>
                            <label class="form-check-label" for="newTabCheck">Ouvrir dans un nouvel onglet</label>
                        </div>

                    </div>
                </div>

                {{-- IMAGE (only for image type) --}}
                @if($ad->type === 'image')
                <div class="card-modern">
                    <div class="card-header-modern">
                        <div class="card-header-icon"><i class="fas fa-image"></i></div>
                        <h3 class="card-title-modern">Visuel de l'annonce</h3>
                        <span class="card-badge">{{ strtoupper($ad->type) }}</span>
                    </div>
                    <div class="card-body-modern">
                        @if($ad->image_path)
                        <div class="current-image-wrap">
                            <img src="{{ asset('storage/'.$ad->image_path) }}" alt="Actuel" class="current-image">
                            <div class="current-image-label"><i class="fas fa-image me-1"></i>Image actuelle</div>
                        </div>
                        @endif

                        <div class="form-group-modern mt-3">
                            <label class="form-label-modern"><i class="fas fa-upload"></i> Remplacer l'image</label>
                            <div class="upload-zone" id="uploadZone">
                                <input type="file" name="image" id="imageInput" accept="image/*" style="display:none;"
                                       onchange="previewImage(this)">
                                <div id="uploadPlaceholder" onclick="document.getElementById('imageInput').click()">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                    <p>Cliquez ou glissez une image ici</p>
                                    <small>JPG, PNG, GIF • Max 2 Mo • Laissez vide pour conserver l'actuelle</small>
                                </div>
                                <img id="imagePreview" src="" alt="" style="display:none;max-width:100%;border-radius:12px;">
                            </div>
                        </div>
                    </div>
                </div>
                @elseif($ad->type === 'html')
                <div class="card-modern">
                    <div class="card-header-modern">
                        <div class="card-header-icon"><i class="fas fa-code"></i></div>
                        <h3 class="card-title-modern">Contenu HTML</h3>
                        <span class="card-badge">HTML</span>
                    </div>
                    <div class="card-body-modern">
                        <div class="current-content-preview mb-3">
                            <div class="preview-label"><i class="fas fa-eye me-1"></i>Rendu actuel</div>
                            <div class="html-preview-box">{!! $ad->html_content !!}</div>
                        </div>
                        <small class="text-muted"><i class="fas fa-info-circle me-1"></i>Le contenu HTML ne peut pas être modifié ici pour des raisons de sécurité. Créez une nouvelle annonce si nécessaire.</small>
                    </div>
                </div>
                @elseif($ad->type === 'text')
                <div class="card-modern">
                    <div class="card-header-modern">
                        <div class="card-header-icon"><i class="fas fa-align-left"></i></div>
                        <h3 class="card-title-modern">Contenu texte</h3>
                    </div>
                    <div class="card-body-modern">
                        <div class="form-group-modern">
                            <label class="form-label-modern">Texte publicitaire</label>
                            <textarea name="text_content" class="form-control-modern" rows="4">{{ old('text_content', $ad->text_content) }}</textarea>
                        </div>
                    </div>
                </div>
                @endif

                {{-- SCHEDULE --}}
                <div class="card-modern">
                    <div class="card-header-modern">
                        <div class="card-header-icon"><i class="fas fa-calendar-alt"></i></div>
                        <h3 class="card-title-modern">Planification</h3>
                    </div>
                    <div class="card-body-modern">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label-modern"><i class="fas fa-calendar-alt"></i> Date de début</label>
                                <input type="date" name="start_date" class="form-control-modern"
                                       value="{{ old('start_date', $ad->start_date) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label-modern"><i class="fas fa-calendar-check"></i> Date de fin</label>
                                <input type="date" name="end_date" class="form-control-modern"
                                       value="{{ old('end_date', $ad->end_date) }}">
                                <small class="form-text-modern">Laissez vide pour une durée illimitée</small>
                            </div>
                        </div>
                        <div class="row g-3 mt-1">
                            <div class="col-md-6">
                                <label class="form-label-modern"><i class="fas fa-sort-numeric-up"></i> Priorité (1=haute, 10=basse)</label>
                                <input type="number" name="priority" class="form-control-modern"
                                       value="{{ old('priority', $ad->priority) }}" min="1" max="10">
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            {{-- SIDEBAR --}}
            <div class="col-lg-4">

                {{-- PRICING --}}
                <div class="card-modern">
                    <div class="card-header-modern">
                        <div class="card-header-icon"><i class="fas fa-coins"></i></div>
                        <h3 class="card-title-modern">Tarification</h3>
                    </div>
                    <div class="card-body-modern">

                        <div class="form-group-modern">
                            <label class="form-label-modern required"><i class="fas fa-chart-pie"></i> Modèle de tarif</label>
                            <select name="pricing_model" class="form-select-modern" id="pricingSelect" required>
                                @foreach($pricingModels as $key => $label)
                                    <option value="{{ $key }}" @selected(old('pricing_model', $ad->pricing_model) === $key)>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group-modern">
                            <label class="form-label-modern required" id="rateLabel">
                                <i class="fas fa-tag"></i> Tarif
                            </label>
                            <div class="input-group">
                                <input type="number" name="rate" class="form-control" step="0.001" min="0"
                                       value="{{ old('rate', $ad->rate) }}" required>
                                <span class="input-group-text">$</span>
                            </div>
                        </div>

                        <div class="row g-2">
                            <div class="col-6">
                                <label class="form-label-modern"><i class="fas fa-wallet"></i> Budget total</label>
                                <input type="number" name="budget_total" class="form-control-modern" step="0.01" min="0"
                                       value="{{ old('budget_total', $ad->budget_total) }}" placeholder="Illimité">
                            </div>
                            <div class="col-6">
                                <label class="form-label-modern"><i class="fas fa-calendar-day"></i> Budget / jour</label>
                                <input type="number" name="budget_daily" class="form-control-modern" step="0.01" min="0"
                                       value="{{ old('budget_daily', $ad->budget_daily) }}" placeholder="Illimité">
                            </div>
                        </div>

                        @if($ad->budget_total)
                        <div class="budget-info-box mt-3">
                            <div class="budget-info-row">
                                <span>Déjà dépensé :</span>
                                <strong>{{ \Vendor\AdsManager\Helpers\Helper::formatCurrency($ad->budget_spent) }}</strong>
                            </div>
                            <div class="budget-info-row">
                                <span>Restant :</span>
                                <strong>{{ \Vendor\AdsManager\Helpers\Helper::formatCurrency(max(0, $ad->budget_total - $ad->budget_spent)) }}</strong>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- PLACEMENTS --}}
                <div class="card-modern">
                    <div class="card-header-modern">
                        <div class="card-header-icon"><i class="fas fa-map-marker-alt"></i></div>
                        <h3 class="card-title-modern">Emplacements</h3>
                    </div>
                    <div class="card-body-modern">
                        <p class="text-muted" style="font-size:13px;">Sélectionnez où l'annonce sera diffusée :</p>
                        <div class="placements-list">
                            @foreach($placements as $placement)
                            <label class="placement-item">
                                <input type="checkbox" name="placements[]" value="{{ $placement->id }}"
                                       {{ in_array($placement->id, old('placements', $linkedIds)) ? 'checked' : '' }}>
                                <div class="placement-info">
                                    <span class="placement-name">{{ $placement->nom }}</span>
                                    <small class="placement-details">
                                        {{ $placement->position }} • {{ $placement->format }}
                                        @if($placement->etablissement_id)
                                            • Étab. #{{ $placement->etablissement_id }}
                                        @else
                                            • Global
                                        @endif
                                    </small>
                                </div>
                            </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- CURRENT INFO --}}
                <div class="card-modern">
                    <div class="card-header-modern">
                        <div class="card-header-icon"><i class="fas fa-history"></i></div>
                        <h3 class="card-title-modern">Infos actuelles</h3>
                    </div>
                    <div class="card-body-modern">
                        <div class="info-list">
                            <div class="info-row"><span>Format</span><strong>{{ config('ads-manager.ad_formats.'.$ad->format.'.label', $ad->format) }}</strong></div>
                            <div class="info-row"><span>Type</span><strong>{{ ucfirst($ad->type) }}</strong></div>
                            <div class="info-row"><span>Dimensions</span><strong>{{ $ad->width }}×{{ $ad->height }}px</strong></div>
                            <div class="info-row"><span>Statut actuel</span>{!! \Vendor\AdsManager\Helpers\Helper::getStatusBadge($ad->status) !!}</div>
                            <div class="info-row"><span>Créée le</span><strong>{{ \Carbon\Carbon::parse($ad->created_at)->format('d/m/Y') }}</strong></div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        {{-- ACTIONS --}}
        <div class="form-actions-modern">
            <a href="{{ route('ads-manager.ads.show', $ad->id) }}" class="btn-cancel">
                <i class="fas fa-times"></i> Annuler
            </a>
            <button type="submit" class="btn-submit">
                <i class="fas fa-paper-plane"></i> Enregistrer et soumettre pour validation
            </button>
        </div>

    </form>
</main>

<script>
// Image preview
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('imagePreview').src = e.target.result;
            document.getElementById('imagePreview').style.display = 'block';
            document.getElementById('uploadPlaceholder').style.display = 'none';
        };
        reader.readAsDataURL(input.files[0]);
    }
}

// Drag & drop
const zone = document.getElementById('uploadZone');
if (zone) {
    zone.addEventListener('dragover', e => { e.preventDefault(); zone.classList.add('drag-over'); });
    zone.addEventListener('dragleave', () => zone.classList.remove('drag-over'));
    zone.addEventListener('drop', e => {
        e.preventDefault();
        zone.classList.remove('drag-over');
        const file = e.dataTransfer.files[0];
        if (file) {
            document.getElementById('imageInput').files = e.dataTransfer.files;
            previewImage({ files: [file] });
        }
    });
}

// Pricing label
const rateLabels = {
    cpm:  'Tarif CPM (par 1 000 impressions)',
    cpc:  'Tarif CPC (par clic)',
    cpa:  'Tarif CPA (par action)',
    flat: 'Tarif forfaitaire / jour',
};
const pricingSelect = document.getElementById('pricingSelect');
if (pricingSelect) {
    pricingSelect.addEventListener('change', function() {
        document.getElementById('rateLabel').innerHTML =
            '<i class="fas fa-tag"></i> ' + (rateLabels[this.value] || 'Tarif');
    });
    // Init label
    document.getElementById('rateLabel').innerHTML =
        '<i class="fas fa-tag"></i> ' + (rateLabels[pricingSelect.value] || 'Tarif');
}
</script>

<style>
.page-header-modern{background:linear-gradient(135deg,#667eea,#764ba2);border-radius:24px;padding:28px 32px;margin-bottom:32px;box-shadow:0 10px 25px -5px rgba(0,0,0,.1);}
.page-header-content{display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:16px;}
.page-header-left{display:flex;align-items:center;gap:20px;}
.page-header-icon{width:60px;height:60px;background:rgba(255,255,255,.2);border-radius:20px;display:flex;align-items:center;justify-content:center;color:#fff;font-size:28px;}
.page-title-modern{font-size:28px;font-weight:700;color:#fff;margin:0;}
.page-subtitle-modern{color:rgba(255,255,255,.8);margin:5px 0 0;font-size:14px;}
.btn-back{background:rgba(255,255,255,.2);color:#fff;padding:10px 18px;border-radius:12px;text-decoration:none;font-size:13px;font-weight:500;display:inline-flex;align-items:center;}
.btn-back:hover{background:rgba(255,255,255,.3);color:#fff;}

.alert-info-banner{background:linear-gradient(135deg,#eef2ff,#f0f9ff);border:1px solid #c7d7fe;border-radius:14px;padding:14px 18px;margin-bottom:24px;font-size:13px;color:#4338ca;display:flex;align-items:center;gap:10px;}

.card-modern{background:#fff;border-radius:24px;box-shadow:0 4px 20px rgba(0,0,0,.05);margin-bottom:24px;overflow:hidden;}
.card-header-modern{display:flex;align-items:center;gap:12px;padding:20px 24px;border-bottom:1px solid #eef2f6;background:#fafbfc;}
.card-header-icon{width:36px;height:36px;background:linear-gradient(135deg,#667eea,#764ba2);border-radius:12px;display:flex;align-items:center;justify-content:center;color:#fff;font-size:16px;}
.card-title-modern{font-size:18px;font-weight:600;color:#1e293b;margin:0;flex:1;}
.card-badge{background:#eef2ff;color:#667eea;padding:4px 10px;border-radius:20px;font-size:12px;}
.card-body-modern{padding:24px;}

.form-group-modern{margin-bottom:20px;}
.form-label-modern{display:flex;align-items:center;gap:8px;font-weight:600;color:#1e293b;margin-bottom:8px;font-size:14px;}
.form-label-modern.required:after{content:"*";color:#ef476f;margin-left:4px;}
.form-control-modern,.form-select-modern{width:100%;padding:11px 14px;border:1px solid #e2e8f0;border-radius:12px;font-size:14px;transition:all .3s;background:#fff;}
.form-control-modern:focus,.form-select-modern:focus{border-color:#667eea;outline:none;box-shadow:0 0 0 3px rgba(102,126,234,.1);}
.form-text-modern{font-size:12px;color:#64748b;margin-top:4px;display:block;}

.current-image-wrap{position:relative;display:inline-block;}
.current-image{max-width:100%;border-radius:12px;border:2px solid #e2e8f0;}
.current-image-label{margin-top:6px;font-size:12px;color:#667eea;font-weight:600;}
.html-preview-box{border:1px dashed #e2e8f0;border-radius:12px;padding:12px;max-height:160px;overflow:auto;}
.preview-label{font-size:12px;font-weight:600;color:#64748b;margin-bottom:6px;}

.upload-zone{border:2px dashed #e2e8f0;border-radius:16px;padding:30px;text-align:center;cursor:pointer;transition:all .3s;}
.upload-zone:hover,.upload-zone.drag-over{border-color:#667eea;background:#f8fafc;}
.upload-zone i{font-size:36px;color:#cbd5e1;margin-bottom:8px;display:block;}
.upload-zone p{color:#64748b;margin:0;}
.upload-zone small{color:#94a3b8;}

.budget-info-box{background:#f8fafc;border-radius:12px;padding:12px 16px;}
.budget-info-row{display:flex;justify-content:space-between;font-size:13px;padding:4px 0;}
.budget-info-row span{color:#64748b;}

.placements-list{display:flex;flex-direction:column;gap:8px;max-height:220px;overflow-y:auto;}
.placement-item{display:flex;align-items:center;gap:10px;padding:10px 12px;border:1.5px solid #e2e8f0;border-radius:12px;cursor:pointer;transition:all .2s;}
.placement-item:hover{border-color:#667eea;background:#f8fafc;}
.placement-item input[type=checkbox]:checked+.placement-info .placement-name{color:#667eea;}
.placement-info{display:flex;flex-direction:column;}
.placement-name{font-weight:600;font-size:13px;color:#1e293b;}
.placement-details{font-size:11px;color:#64748b;}

.info-list{display:flex;flex-direction:column;}
.info-row{display:flex;justify-content:space-between;padding:9px 0;border-bottom:1px solid #f1f5f9;font-size:13px;align-items:center;}
.info-row span{color:#64748b;}

.form-actions-modern{display:flex;justify-content:space-between;align-items:center;margin-top:32px;padding:20px 24px;background:#fff;border-radius:24px;box-shadow:0 4px 20px rgba(0,0,0,.05);}
.btn-cancel{padding:12px 20px;background:#f1f5f9;border:none;border-radius:12px;color:#475569;font-weight:500;cursor:pointer;text-decoration:none;display:inline-flex;align-items:center;gap:8px;}
.btn-cancel:hover{background:#e2e8f0;}
.btn-submit{padding:12px 28px;background:linear-gradient(135deg,#667eea,#764ba2);border:none;border-radius:12px;color:#fff;font-weight:600;cursor:pointer;display:inline-flex;align-items:center;gap:8px;}
.btn-submit:hover{transform:translateY(-2px);box-shadow:0 6px 16px rgba(102,126,234,.35);}

@media(max-width:768px){
    .form-actions-modern{flex-direction:column;gap:12px;}
    .page-header-modern{padding:20px;}
}
</style>
@endsection