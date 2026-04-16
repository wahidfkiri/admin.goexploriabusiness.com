@extends('layouts.app')

@section('content')
<main class="dashboard-content">

    <div class="page-header-modern">
        <div class="page-header-content">
            <div class="page-header-left">
                <div class="page-header-icon"><i class="fas fa-map-marker-alt"></i></div>
                <div>
                    <h1 class="page-title-modern">Nouvel emplacement</h1>
                    <p class="page-subtitle-modern">Définissez une zone d'affichage pour vos annonces</p>
                </div>
            </div>
            <a href="{{ route('ads-manager.placements.index') }}" class="btn-back">
                <i class="fas fa-arrow-left me-2"></i>Retour
            </a>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-exclamation-circle me-2"></i>
            <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form action="{{ route('ads-manager.placements.store') }}" method="POST">
        @csrf
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card-modern">
                    <div class="card-header-modern">
                        <div class="card-header-icon"><i class="fas fa-info-circle"></i></div>
                        <h3 class="card-title-modern">Configuration de l'emplacement</h3>
                    </div>
                    <div class="card-body-modern">

                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-group-modern">
                                    <label class="form-label-modern required"><i class="fas fa-tag"></i> Nom</label>
                                    <input type="text" name="nom" class="form-control-modern @error('nom') is-invalid @enderror"
                                           value="{{ old('nom') }}" placeholder="Sidebar droite - Accueil" required>
                                    @error('nom') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group-modern">
                                    <label class="form-label-modern required"><i class="fas fa-code"></i> Code unique</label>
                                    <input type="text" name="code" class="form-control-modern @error('code') is-invalid @enderror"
                                           value="{{ old('code') }}" placeholder="sidebar_right_home"
                                           pattern="[a-z0-9_]+" title="Minuscules, chiffres et _ uniquement" required>
                                    <small class="form-text-modern">Minuscules, chiffres et underscores uniquement</small>
                                    @error('code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group-modern">
                            <label class="form-label-modern"><i class="fas fa-align-left"></i> Description</label>
                            <textarea name="description" class="form-control-modern" rows="2"
                                      placeholder="Description de l'emplacement et son usage…">{{ old('description') }}</textarea>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-group-modern">
                                    <label class="form-label-modern required"><i class="fas fa-map-pin"></i> Position sur la page</label>
                                    <select name="position" class="form-select-modern" required>
                                        @foreach($positions as $key => $label)
                                            <option value="{{ $key }}" @selected(old('position') === $key)>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group-modern">
                                    <label class="form-label-modern required"><i class="fas fa-expand-alt"></i> Format publicitaire</label>
                                    <select name="format" class="form-select-modern" id="formatSel" required>
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

                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-group-modern">
                                    <label class="form-label-modern"><i class="fas fa-building"></i> Établissement</label>
                                    <select name="etablissement_id" class="form-select-modern">
                                        <option value="">🌐 Tous les établissements</option>
                                        @foreach($etablissements as $e)
                                            <option value="{{ $e->id }}" @selected(old('etablissement_id') == $e->id)>
                                                {{ $e->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group-modern">
                                    <label class="form-label-modern"><i class="fas fa-file-alt"></i> Contexte de page</label>
                                    <select name="page_context" class="form-select-modern">
                                        <option value="">Toutes les pages</option>
                                        <option value="home"   @selected(old('page_context')==='home')  >Page d'accueil</option>
                                        <option value="list"   @selected(old('page_context')==='list')  >Liste des établissements</option>
                                        <option value="detail" @selected(old('page_context')==='detail')>Détail établissement</option>
                                        <option value="blog"   @selected(old('page_context')==='blog')  >Blog / Articles</option>
                                        <option value="search" @selected(old('page_context')==='search')>Résultats de recherche</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-group-modern">
                                    <label class="form-label-modern"><i class="fas fa-layer-group"></i> Annonces max par zone</label>
                                    <input type="number" name="max_ads" class="form-control-modern"
                                           value="{{ old('max_ads', 1) }}" min="1" max="10">
                                </div>
                            </div>
                            <div class="col-md-6 d-flex align-items-center">
                                <div class="form-check form-switch mt-3">
                                    <input type="checkbox" name="is_active" class="form-check-input" id="activeCheck"
                                           value="1" {{ old('is_active', 1) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="activeCheck">
                                        <strong>Emplacement actif</strong>
                                    </label>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            {{-- Aperçu --}}
            <div class="col-lg-4">
                <div class="card-modern">
                    <div class="card-header-modern">
                        <div class="card-header-icon"><i class="fas fa-eye"></i></div>
                        <h3 class="card-title-modern">Aperçu de la zone</h3>
                    </div>
                    <div class="card-body-modern text-center">
                        <div id="zonePreview" class="zone-preview-box">
                            <span id="dimLabel">300 × 250 px</span>
                        </div>
                        <p class="text-muted mt-2" style="font-size:12px;">
                            Représentation proportionnelle de l'emplacement
                        </p>
                    </div>
                </div>

                <div class="card-modern">
                    <div class="card-header-modern">
                        <div class="card-header-icon"><i class="fas fa-lightbulb"></i></div>
                        <h3 class="card-title-modern">Conseils</h3>
                    </div>
                    <div class="card-body-modern">
                        <ul class="tips-list">
                            <li><i class="fas fa-check-circle text-success"></i> Choisissez un code descriptif et unique</li>
                            <li><i class="fas fa-check-circle text-success"></i> Le format doit correspondre aux annonces attribuées</li>
                            <li><i class="fas fa-check-circle text-success"></i> Limitez le nombre d'annonces pour une meilleure UX</li>
                            <li><i class="fas fa-check-circle text-success"></i> Utilisez la directive <code>@adZone</code> dans vos vues</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-actions-modern">
            <a href="{{ route('ads-manager.placements.index') }}" class="btn-cancel">
                <i class="fas fa-times"></i> Annuler
            </a>
            <button type="submit" class="btn-submit">
                <i class="fas fa-save"></i> Créer l'emplacement
            </button>
        </div>
    </form>
</main>

<script>
document.getElementById('formatSel').addEventListener('change', function() {
    const opt = this.options[this.selectedIndex];
    const w = opt.dataset.w || 300;
    const h = opt.dataset.h || 250;
    const scale = Math.min(1, 240 / w);
    const box = document.getElementById('zonePreview');
    box.style.width  = (w * scale) + 'px';
    box.style.height = (h * scale) + 'px';
    document.getElementById('dimLabel').textContent = w + ' × ' + h + ' px';
});
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
.card-modern{background:#fff;border-radius:24px;box-shadow:0 4px 20px rgba(0,0,0,.05);margin-bottom:24px;overflow:hidden;}
.card-header-modern{display:flex;align-items:center;gap:12px;padding:20px 24px;border-bottom:1px solid #eef2f6;background:#fafbfc;}
.card-header-icon{width:36px;height:36px;background:linear-gradient(135deg,#667eea,#764ba2);border-radius:12px;display:flex;align-items:center;justify-content:center;color:#fff;font-size:16px;}
.card-title-modern{font-size:18px;font-weight:600;color:#1e293b;margin:0;flex:1;}
.card-body-modern{padding:24px;}
.form-group-modern{margin-bottom:20px;}
.form-label-modern{display:flex;align-items:center;gap:8px;font-weight:600;color:#1e293b;margin-bottom:8px;font-size:14px;}
.form-label-modern.required:after{content:"*";color:#ef476f;margin-left:4px;}
.form-control-modern,.form-select-modern{width:100%;padding:11px 14px;border:1px solid #e2e8f0;border-radius:12px;font-size:14px;transition:all .3s;background:#fff;}
.form-control-modern:focus,.form-select-modern:focus{border-color:#667eea;outline:none;box-shadow:0 0 0 3px rgba(102,126,234,.1);}
.form-text-modern{font-size:12px;color:#64748b;margin-top:4px;display:block;}
.zone-preview-box{width:240px;height:140px;background:linear-gradient(135deg,#667eea30,#764ba230);border:2px dashed #667eea;border-radius:12px;display:flex;align-items:center;justify-content:center;margin:0 auto;font-size:13px;font-weight:600;color:#667eea;transition:all .4s;}
.tips-list{list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:10px;}
.tips-list li{display:flex;align-items:center;gap:10px;font-size:13px;color:#475569;}
.form-actions-modern{display:flex;justify-content:space-between;align-items:center;margin-top:32px;padding:20px 24px;background:#fff;border-radius:24px;box-shadow:0 4px 20px rgba(0,0,0,.05);}
.btn-cancel{padding:12px 20px;background:#f1f5f9;border:none;border-radius:12px;color:#475569;font-weight:500;cursor:pointer;text-decoration:none;display:inline-flex;align-items:center;gap:8px;}
.btn-submit{padding:12px 28px;background:linear-gradient(135deg,#667eea,#764ba2);border:none;border-radius:12px;color:#fff;font-weight:600;cursor:pointer;display:inline-flex;align-items:center;gap:8px;}
.btn-submit:hover{transform:translateY(-2px);box-shadow:0 6px 16px rgba(102,126,234,.35);}
</style>
@endsection