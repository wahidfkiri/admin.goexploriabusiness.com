@extends('layouts.app')

@section('content')
<main class="dashboard-content">

    <div class="page-header-modern">
        <div class="page-header-content">
            <div class="page-header-left">
                <div class="page-header-icon"><i class="fas fa-plus-circle"></i></div>
                <div>
                    <h1 class="page-title-modern">Nouvelle annonce publicitaire</h1>
                    <p class="page-subtitle-modern">Configurez votre campagne publicitaire</p>
                </div>
            </div>
            <a href="{{ route('ads-manager.ads.index') }}" class="btn-back">
                <i class="fas fa-arrow-left me-2"></i>Retour
            </a>
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

    <form action="{{ route('ads-manager.ads.store') }}" method="POST" enctype="multipart/form-data" id="adForm">
        @csrf

        <div class="row g-4">

            {{-- ====================================================== --}}
            {{-- COLONNE PRINCIPALE                                        --}}
            {{-- ====================================================== --}}
            <div class="col-lg-8">

                {{-- INFORMATIONS GÉNÉRALES --}}
                <div class="card-modern">
                    <div class="card-header-modern">
                        <div class="card-header-icon"><i class="fas fa-info-circle"></i></div>
                        <h3 class="card-title-modern">Informations générales</h3>
                        <span class="card-badge">Étape 1/4</span>
                    </div>
                    <div class="card-body-modern">

                        <div class="form-group-modern">
                            <label class="form-label-modern required"><i class="fas fa-heading"></i> Titre de l'annonce</label>
                            <input type="text" name="titre" class="form-control-modern @error('titre') is-invalid @enderror"
                                   value="{{ old('titre') }}" placeholder="Ex: Promotion spéciale été 2025" required>
                            @error('titre') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="form-group-modern">
                            <label class="form-label-modern"><i class="fas fa-align-left"></i> Description interne</label>
                            <textarea name="description" class="form-control-modern" rows="2"
                                      placeholder="Notes internes sur cette annonce…">{{ old('description') }}</textarea>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-group-modern">
                                    <label class="form-label-modern"><i class="fas fa-user-tie"></i> Annonceur</label>
                                    <input type="text" name="advertiser_name" class="form-control-modern"
                                           value="{{ old('advertiser_name') }}" placeholder="Nom de l'entreprise">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group-modern">
                                    <label class="form-label-modern"><i class="fas fa-envelope"></i> Email annonceur</label>
                                    <input type="email" name="advertiser_email" class="form-control-modern"
                                           value="{{ old('advertiser_email') }}" placeholder="contact@annonceur.com">
                                </div>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-group-modern">
                                    <label class="form-label-modern required"><i class="fas fa-photo-video"></i> Type de contenu</label>
                                    <select name="type" class="form-select-modern" id="typeSelect" required>
                                        <option value="image" @selected(old('type','image')==='image')>🖼️ Image</option>
                                        <option value="html"  @selected(old('type')==='html')>💻 HTML personnalisé</option>
                                        <option value="video" @selected(old('type')==='video')>🎥 Vidéo</option>
                                        <option value="text"  @selected(old('type')==='text')>📝 Texte</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group-modern">
                                    <label class="form-label-modern required"><i class="fas fa-expand-alt"></i> Format</label>
                                    <select name="format" class="form-select-modern" id="formatSelect" required>
                                        @foreach($formats as $key => $f)
                                            <option value="{{ $key }}" @selected(old('format','rectangle')===$key)
                                                    data-w="{{ $f['width'] }}" data-h="{{ $f['height'] }}">
                                                {{ $f['label'] }} ({{ $f['width'] }}×{{ $f['height'] }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- CONTENU --}}
                <div class="card-modern">
                    <div class="card-header-modern">
                        <div class="card-header-icon"><i class="fas fa-image"></i></div>
                        <h3 class="card-title-modern">Contenu de l'annonce</h3>
                        <span class="card-badge">Étape 2/4</span>
                    </div>
                    <div class="card-body-modern">

                        {{-- IMAGE --}}
                        <div id="content-image">
                            <div class="form-group-modern">
                                <label class="form-label-modern"><i class="fas fa-upload"></i> Image publicitaire</label>
                                <div class="upload-zone" id="uploadZone">
                                    <input type="file" name="image" id="imageInput" accept="image/*" style="display:none;"
                                           onchange="previewImage(this)">
                                    <div id="uploadPlaceholder" onclick="document.getElementById('imageInput').click()">
                                        <i class="fas fa-cloud-upload-alt"></i>
                                        <p>Cliquez ou glissez une image ici</p>
                                        <small>JPG, PNG, GIF • Max 2 Mo</small>
                                    </div>
                                    <img id="imagePreview" src="" alt="" style="display:none;max-width:100%;border-radius:12px;">
                                </div>
                            </div>
                        </div>

                        {{-- HTML --}}
                        <div id="content-html" style="display:none;">
                            <div class="form-group-modern">
                                <label class="form-label-modern"><i class="fas fa-code"></i> Code HTML de l'annonce</label>
                                <textarea name="html_content" class="form-control-modern editor-textarea" rows="10"
                                          placeholder="<div>Votre bannière HTML…</div>">{{ old('html_content') }}</textarea>
                                <small class="form-text-modern">Vous pouvez utiliser du CSS inline et des images hébergées.</small>
                            </div>
                        </div>

                        {{-- VIDEO --}}
                        <div id="content-video" style="display:none;">
                            <div class="form-group-modern">
                                <label class="form-label-modern"><i class="fas fa-link"></i> URL de la vidéo</label>
                                <input type="url" name="video_url" class="form-control-modern"
                                       value="{{ old('video_url') }}" placeholder="https://…/video.mp4">
                                <small class="form-text-modern">Formats acceptés : MP4, WebM. Hébergez votre vidéo sur un CDN.</small>
                            </div>
                        </div>

                        {{-- TEXT --}}
                        <div id="content-text" style="display:none;">
                            <div class="form-group-modern">
                                <label class="form-label-modern"><i class="fas fa-align-left"></i> Texte publicitaire</label>
                                <textarea name="text_content" class="form-control-modern" rows="4"
                                          placeholder="Votre message publicitaire concis…">{{ old('text_content') }}</textarea>
                            </div>
                        </div>

                        <div class="form-group-modern mt-3">
                            <label class="form-label-modern"><i class="fas fa-external-link-alt"></i> URL de destination</label>
                            <input type="url" name="destination_url" class="form-control-modern @error('destination_url') is-invalid @enderror"
                                   value="{{ old('destination_url') }}" placeholder="https://votre-site.com/page">
                            @error('destination_url') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="form-check form-switch">
                            <input type="checkbox" name="open_new_tab" class="form-check-input" id="newTabCheck"
                                   value="1" {{ old('open_new_tab',1) ? 'checked' : '' }}>
                            <label class="form-check-label" for="newTabCheck">
                                Ouvrir dans un nouvel onglet
                            </label>
                        </div>

                    </div>
                </div>

                {{-- CIBLAGE & PLANIFICATION --}}
                <div class="card-modern">
                    <div class="card-header-modern">
                        <div class="card-header-icon"><i class="fas fa-crosshairs"></i></div>
                        <h3 class="card-title-modern">Ciblage & Planification</h3>
                        <span class="card-badge">Étape 3/4</span>
                    </div>
                    <div class="card-body-modern">

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label-modern"><i class="fas fa-calendar-alt"></i> Date de début</label>
                                <input type="date" name="start_date" class="form-control-modern"
                                       value="{{ old('start_date') }}" min="{{ date('Y-m-d') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label-modern"><i class="fas fa-calendar-check"></i> Date de fin</label>
                                <input type="date" name="end_date" class="form-control-modern"
                                       value="{{ old('end_date') }}">
                                <small class="form-text-modern">Laissez vide pour une durée illimitée</small>
                            </div>
                        </div>

                        <div class="form-group-modern mt-3">
                            <label class="form-label-modern"><i class="fas fa-building"></i> Établissements ciblés</label>
                            <select name="target_etablissements[]" class="form-select-modern" multiple
                                    style="height:100px;">
                                <option value="all" selected>🌐 Tous les établissements</option>
                                @foreach($etablissements as $e)
                                    <option value="{{ $e->id }}"
                                            {{ in_array($e->id, old('target_etablissements', [])) ? 'selected' : '' }}>
                                        {{ $e->name }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="form-text-modern">Ctrl+clic pour sélectionner plusieurs</small>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label-modern"><i class="fas fa-users"></i> Audience cible</label>
                                <select name="target_audience" class="form-select-modern">
                                    <option value="all">Tous</option>
                                    <option value="students">Étudiants</option>
                                    <option value="parents">Parents</option>
                                    <option value="staff">Personnel</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label-modern"><i class="fas fa-infinity"></i> Limite impressions</label>
                                <input type="number" name="impression_limit" class="form-control-modern"
                                       value="{{ old('impression_limit') }}" placeholder="0 = illimité" min="0">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label-modern"><i class="fas fa-mouse-pointer"></i> Limite de clics</label>
                                <input type="number" name="click_limit" class="form-control-modern"
                                       value="{{ old('click_limit') }}" placeholder="0 = illimité" min="0">
                            </div>
                        </div>

                        <div class="row g-3 mt-1">
                            <div class="col-md-6">
                                <label class="form-label-modern"><i class="fas fa-redo"></i> Fréquence max (par user/jour)</label>
                                <input type="number" name="frequency_cap" class="form-control-modern"
                                       value="{{ old('frequency_cap') }}" placeholder="0 = illimité" min="0">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label-modern"><i class="fas fa-sort-numeric-up"></i> Priorité (1=haute, 10=basse)</label>
                                <input type="number" name="priority" class="form-control-modern"
                                       value="{{ old('priority', 5) }}" min="1" max="10">
                            </div>
                        </div>

                    </div>
                </div>

            </div>

            {{-- ====================================================== --}}
            {{-- COLONNE LATÉRALE                                          --}}
            {{-- ====================================================== --}}
            <div class="col-lg-4">

                {{-- TARIFICATION --}}
                <div class="card-modern">
                    <div class="card-header-modern">
                        <div class="card-header-icon"><i class="fas fa-coins"></i></div>
                        <h3 class="card-title-modern">Tarification</h3>
                        <span class="card-badge">Étape 4/4</span>
                    </div>
                    <div class="card-body-modern">

                        <div class="form-group-modern">
                            <label class="form-label-modern required"><i class="fas fa-chart-pie"></i> Modèle de tarif</label>
                            <select name="pricing_model" class="form-select-modern" id="pricingSelect" required>
                                @foreach($pricingModels as $key => $label)
                                    <option value="{{ $key }}" @selected(old('pricing_model','cpm')===$key)>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group-modern" id="rateGroup">
                            <label class="form-label-modern required" id="rateLabel">
                                <i class="fas fa-tag"></i> Tarif CPM (par 1 000 impr.)
                            </label>
                            <div class="input-group">
                                <input type="number" name="rate" class="form-control" step="0.001" min="0"
                                       value="{{ old('rate', 5) }}" required>
                                <span class="input-group-text">$</span>
                            </div>
                        </div>

                        <div class="row g-2">
                            <div class="col-6">
                                <label class="form-label-modern"><i class="fas fa-wallet"></i> Budget total</label>
                                <input type="number" name="budget_total" class="form-control-modern" step="0.01" min="0"
                                       value="{{ old('budget_total') }}" placeholder="Illimité">
                            </div>
                            <div class="col-6">
                                <label class="form-label-modern"><i class="fas fa-calendar-day"></i> Budget / jour</label>
                                <input type="number" name="budget_daily" class="form-control-modern" step="0.01" min="0"
                                       value="{{ old('budget_daily') }}" placeholder="Illimité">
                            </div>
                        </div>

                        {{-- Calculateur estimatif --}}
                        <div class="estimator mt-3">
                            <div class="estimator-header"><i class="fas fa-calculator"></i> Estimateur</div>
                            <div class="estimator-body">
                                <div class="est-row">
                                    <span>Impressions estimées / jour :</span>
                                    <input type="number" id="estImpressions" value="1000" min="0" class="est-input">
                                </div>
                                <div class="est-row mt-1">
                                    <span>CTR estimé (%) :</span>
                                    <input type="number" id="estCtr" value="2" min="0" max="100" step="0.1" class="est-input">
                                </div>
                                <hr class="my-2">
                                <div class="est-result">
                                    <span>Coût estimé / jour :</span>
                                    <strong id="estCost">—</strong>
                                </div>
                                <div class="est-result">
                                    <span>Clics estimés / jour :</span>
                                    <strong id="estClicks">—</strong>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- EMPLACEMENTS --}}
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
                                       {{ in_array($placement->id, old('placements', [])) ? 'checked' : '' }}>
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

                {{-- APERÇU --}}
                <div class="card-modern">
                    <div class="card-header-modern">
                        <div class="card-header-icon"><i class="fas fa-eye"></i></div>
                        <h3 class="card-title-modern">Aperçu</h3>
                        <button type="button" class="btn-refresh" onclick="updatePreview()" title="Actualiser">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                    </div>
                    <div class="card-body-modern text-center">
                        <div id="adPreview" class="ad-preview-box">
                            <p class="text-muted">Sélectionnez un type et un format pour voir l'aperçu</p>
                        </div>
                        <div id="previewDims" class="text-muted mt-2" style="font-size:12px;"></div>
                    </div>
                </div>

            </div>
        </div>

        {{-- ACTIONS --}}
        <div class="form-actions-modern">
            <a href="{{ route('ads-manager.ads.index') }}" class="btn-cancel">
                <i class="fas fa-times"></i> Annuler
            </a>
            <div class="actions-right">
                <button type="submit" class="btn-draft" formaction="{{ route('ads-manager.ads.store') }}"
                        onclick="document.querySelector('[name=_draft]')?.remove();">
                    <i class="fas fa-save"></i> Enregistrer (brouillon)
                </button>
                <button type="submit" class="btn-submit" id="submitBtn">
                    <i class="fas fa-paper-plane"></i> Soumettre pour validation
                </button>
            </div>
        </div>

    </form>
</main>

<script>
// ---- Type switch ----
document.getElementById('typeSelect').addEventListener('change', function() {
    ['image','html','video','text'].forEach(t => {
        document.getElementById('content-'+t).style.display = this.value === t ? 'block' : 'none';
    });
    updatePreview();
});

// ---- Format dims ----
document.getElementById('formatSelect').addEventListener('change', function() {
    const opt = this.options[this.selectedIndex];
    document.getElementById('previewDims').textContent = opt.dataset.w + ' × ' + opt.dataset.h + ' px';
    updatePreview();
});

// ---- Image preview ----
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('imagePreview').src = e.target.result;
            document.getElementById('imagePreview').style.display = 'block';
            document.getElementById('uploadPlaceholder').style.display = 'none';
        };
        reader.readAsDataURL(input.files[0]);
        updatePreview();
    }
}

// ---- Drag & drop ----
const zone = document.getElementById('uploadZone');
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

// ---- Pricing estimator ----
function calcEstimate() {
    const pricing = document.getElementById('pricingSelect').value;
    const rate    = parseFloat(document.querySelector('[name=rate]').value) || 0;
    const impr    = parseInt(document.getElementById('estImpressions').value) || 0;
    const ctr     = parseFloat(document.getElementById('estCtr').value) || 0;
    const clicks  = Math.round(impr * ctr / 100);

    let cost = 0;
    if (pricing === 'cpm')  cost = (impr / 1000) * rate;
    if (pricing === 'cpc')  cost = clicks * rate;
    if (pricing === 'flat') cost = rate;

    document.getElementById('estCost').textContent   = cost.toFixed(3) + ' $';
    document.getElementById('estClicks').textContent = clicks.toLocaleString();
}

['pricingSelect','estImpressions','estCtr'].forEach(id => {
    const el = document.getElementById(id);
    if (el) el.addEventListener('input', calcEstimate);
});
document.querySelector('[name=rate]').addEventListener('input', calcEstimate);
calcEstimate();

// ---- Pricing label ----
const rateLabels = {
    cpm:  'Tarif CPM (par 1 000 impressions)',
    cpc:  'Tarif CPC (par clic)',
    cpa:  'Tarif CPA (par action)',
    flat: 'Tarif forfaitaire / jour',
};
document.getElementById('pricingSelect').addEventListener('change', function() {
    document.getElementById('rateLabel').innerHTML =
        '<i class="fas fa-tag"></i> ' + (rateLabels[this.value] || 'Tarif');
    calcEstimate();
});

// ---- Ad preview box ----
function updatePreview() {
    const type   = document.getElementById('typeSelect').value;
    const format = document.getElementById('formatSelect');
    const opt    = format.options[format.selectedIndex];
    const w      = opt.dataset.w || 300;
    const h      = opt.dataset.h || 250;
    const titre  = document.querySelector('[name=titre]').value || 'Titre de l\'annonce';
    const box    = document.getElementById('adPreview');

    const scale  = Math.min(1, 320 / w);
    let inner    = '';

    if (type === 'image') {
        const src = document.getElementById('imagePreview').src;
        inner = src && src !== window.location.href
            ? `<img src="${src}" style="width:${w*scale}px;height:${h*scale}px;object-fit:cover;border-radius:8px;">`
            : `<div style="width:${w*scale}px;height:${h*scale}px;background:linear-gradient(135deg,#667eea,#764ba2);border-radius:8px;display:flex;align-items:center;justify-content:center;color:#fff;font-size:14px;">${w}×${h}</div>`;
    } else if (type === 'text') {
        const txt = document.querySelector('[name=text_content]')?.value || '';
        inner = `<div style="width:${w*scale}px;padding:12px;background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;text-align:left;"><strong style="font-size:13px;">${titre}</strong><p style="font-size:11px;color:#64748b;margin:4px 0 0;">${txt.substring(0,80)}</p></div>`;
    } else {
        inner = `<div style="width:${w*scale}px;height:${h*scale}px;background:linear-gradient(135deg,#1e293b,#334155);border-radius:8px;display:flex;align-items:center;justify-content:center;color:#fff;font-size:13px;"><i class="fas fa-${type === 'video' ? 'video' : 'code'} me-2"></i> ${type.toUpperCase()}</div>`;
    }

    box.innerHTML = inner;
    document.getElementById('previewDims').textContent = w + ' × ' + h + ' px';
}

document.querySelector('[name=titre]').addEventListener('input', updatePreview);
document.querySelector('[name=text_content]')?.addEventListener('input', updatePreview);
updatePreview();
</script>

<style>
/* Reprend les mêmes styles que index + compléments */
.page-header-modern{background:linear-gradient(135deg,#667eea,#764ba2);border-radius:24px;padding:28px 32px;margin-bottom:32px;box-shadow:0 10px 25px -5px rgba(0,0,0,.1);}
.page-header-content{display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:16px;}
.page-header-left{display:flex;align-items:center;gap:20px;}
.page-header-icon{width:60px;height:60px;background:rgba(255,255,255,.2);border-radius:20px;display:flex;align-items:center;justify-content:center;color:#fff;font-size:28px;}
.page-title-modern{font-size:28px;font-weight:700;color:#fff;margin:0;}
.page-subtitle-modern{color:rgba(255,255,255,.8);margin:5px 0 0;font-size:14px;}
.btn-back{background:rgba(255,255,255,.2);color:#fff;padding:10px 18px;border-radius:12px;text-decoration:none;font-size:13px;font-weight:500;transition:all .3s;display:inline-flex;align-items:center;}
.btn-back:hover{background:rgba(255,255,255,.3);color:#fff;}

.card-modern{background:#fff;border-radius:24px;box-shadow:0 4px 20px rgba(0,0,0,.05);margin-bottom:24px;overflow:hidden;}
.card-header-modern{display:flex;align-items:center;gap:12px;padding:20px 24px;border-bottom:1px solid #eef2f6;background:#fafbfc;}
.card-header-icon{width:36px;height:36px;background:linear-gradient(135deg,#667eea,#764ba2);border-radius:12px;display:flex;align-items:center;justify-content:center;color:#fff;font-size:16px;}
.card-title-modern{font-size:18px;font-weight:600;color:#1e293b;margin:0;flex:1;}
.card-badge{background:#eef2ff;color:#667eea;padding:4px 10px;border-radius:20px;font-size:12px;font-weight:500;}
.card-body-modern{padding:24px;}

.form-group-modern{margin-bottom:20px;}
.form-label-modern{display:flex;align-items:center;gap:8px;font-weight:600;color:#1e293b;margin-bottom:8px;font-size:14px;}
.form-label-modern.required:after{content:"*";color:#ef476f;margin-left:4px;}
.form-control-modern,.form-select-modern{width:100%;padding:11px 14px;border:1px solid #e2e8f0;border-radius:12px;font-size:14px;transition:all .3s;background:#fff;}
.form-control-modern:focus,.form-select-modern:focus{border-color:#667eea;box-shadow:0 0 0 3px rgba(102,126,234,.1);outline:none;}
.editor-textarea{border-radius:12px;font-family:monospace;font-size:13px;resize:vertical;}
.form-text-modern{font-size:12px;color:#64748b;margin-top:4px;display:block;}

/* Upload zone */
.upload-zone{border:2px dashed #e2e8f0;border-radius:16px;padding:30px;text-align:center;cursor:pointer;transition:all .3s;}
.upload-zone:hover,.upload-zone.drag-over{border-color:#667eea;background:#f8fafc;}
.upload-zone i{font-size:36px;color:#cbd5e1;margin-bottom:8px;}
.upload-zone p{color:#64748b;margin:0;}
.upload-zone small{color:#94a3b8;}

/* Estimator */
.estimator{background:#f8fafc;border-radius:16px;overflow:hidden;}
.estimator-header{padding:10px 14px;background:#eef2ff;color:#667eea;font-weight:600;font-size:13px;}
.estimator-body{padding:14px;}
.est-row{display:flex;justify-content:space-between;align-items:center;font-size:12px;color:#475569;}
.est-input{width:80px;padding:4px 8px;border:1px solid #e2e8f0;border-radius:8px;font-size:12px;text-align:right;}
.est-result{display:flex;justify-content:space-between;font-size:13px;color:#1e293b;}

/* Placements */
.placements-list{display:flex;flex-direction:column;gap:8px;max-height:240px;overflow-y:auto;}
.placement-item{display:flex;align-items:center;gap:10px;padding:10px 12px;border:1.5px solid #e2e8f0;border-radius:12px;cursor:pointer;transition:all .2s;}
.placement-item:hover{border-color:#667eea;background:#f8fafc;}
.placement-item input{flex-shrink:0;}
.placement-info{display:flex;flex-direction:column;}
.placement-name{font-weight:600;font-size:13px;color:#1e293b;}
.placement-details{font-size:11px;color:#64748b;}

/* Preview */
.ad-preview-box{min-height:100px;display:flex;align-items:center;justify-content:center;background:#f1f5f9;border-radius:12px;padding:12px;}
.btn-refresh{background:none;border:none;color:#667eea;cursor:pointer;font-size:16px;padding:4px;}
.btn-refresh:hover{color:#764ba2;}

/* Form actions */
.form-actions-modern{display:flex;justify-content:space-between;align-items:center;margin-top:32px;padding:20px 24px;background:#fff;border-radius:24px;box-shadow:0 4px 20px rgba(0,0,0,.05);}
.actions-right{display:flex;gap:12px;}
.btn-cancel{padding:12px 20px;background:#f1f5f9;border:none;border-radius:12px;color:#475569;font-weight:500;cursor:pointer;text-decoration:none;display:inline-flex;align-items:center;gap:8px;}
.btn-cancel:hover{background:#e2e8f0;}
.btn-draft{padding:12px 24px;background:#f1f5f9;border:none;border-radius:12px;color:#475569;font-weight:600;cursor:pointer;display:inline-flex;align-items:center;gap:8px;}
.btn-submit{padding:12px 28px;background:linear-gradient(135deg,#667eea,#764ba2);border:none;border-radius:12px;color:#fff;font-weight:600;cursor:pointer;display:inline-flex;align-items:center;gap:8px;}
.btn-submit:hover{transform:translateY(-2px);box-shadow:0 6px 16px rgba(102,126,234,.35);}

@media(max-width:768px){
    .form-actions-modern{flex-direction:column;}
    .actions-right{width:100%;justify-content:center;}
    .page-header-modern{padding:20px;}
}
</style>
@endsection