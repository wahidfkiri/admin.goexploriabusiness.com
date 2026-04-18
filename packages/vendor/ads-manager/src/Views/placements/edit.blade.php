@extends('layouts.app')

@section('content')
<main class="dashboard-content">

    <div class="page-header-modern">
        <div class="page-header-content">
            <div class="page-header-left">
                <div class="page-header-icon"><i class="fas fa-edit"></i></div>
                <div>
                    <h1 class="page-title-modern">Modifier l'emplacement</h1>
                    <p class="page-subtitle-modern">{{ $placement->nom }} — <code style="color:rgba(255,255,255,.7);">{{ $placement->code }}</code></p>
                </div>
            </div>
            <a href="{{ route('ads-manager.placements.index') }}" class="btn-back">
                <i class="fas fa-arrow-left me-2"></i>Retour
            </a>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="alert-info-banner">
        <i class="fas fa-lock"></i>
        Le code unique <strong>{{ $placement->code }}</strong> ne peut pas être modifié après création.
    </div>

    <form action="{{ route('ads-manager.placements.update', $placement->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card-modern">
                    <div class="card-header-modern">
                        <div class="card-header-icon"><i class="fas fa-info-circle"></i></div>
                        <h3 class="card-title-modern">Configuration</h3>
                    </div>
                    <div class="card-body-modern">

                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-group-modern">
                                    <label class="form-label-modern required"><i class="fas fa-tag"></i> Nom</label>
                                    <input type="text" name="nom" class="form-control-modern @error('nom') is-invalid @enderror"
                                           value="{{ old('nom', $placement->nom) }}" required>
                                    @error('nom') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group-modern">
                                    <label class="form-label-modern"><i class="fas fa-code"></i> Code (non modifiable)</label>
                                    <input type="text" class="form-control-modern" value="{{ $placement->code }}" disabled>
                                </div>
                            </div>
                        </div>

                        <div class="form-group-modern">
                            <label class="form-label-modern"><i class="fas fa-align-left"></i> Description</label>
                            <textarea name="description" class="form-control-modern" rows="2">{{ old('description', $placement->description) }}</textarea>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-group-modern">
                                    <label class="form-label-modern required"><i class="fas fa-map-pin"></i> Position sur la page</label>
                                    <select name="position" class="form-select-modern" required>
                                        @foreach($positions as $key => $label)
                                            <option value="{{ $key }}" @selected(old('position', $placement->position) === $key)>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group-modern">
                                    <label class="form-label-modern"><i class="fas fa-expand-alt"></i> Format (non modifiable)</label>
                                    <input type="text" class="form-control-modern"
                                           value="{{ config('ads-manager.ad_formats.'.$placement->format.'.label', $placement->format) }} ({{ $placement->width }}×{{ $placement->height }})" disabled>
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
                                            <option value="{{ $e->id }}" @selected(old('etablissement_id', $placement->etablissement_id) == $e->id)>
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
                                        <option value="home"   @selected(old('page_context', $placement->page_context)==='home')  >Page d'accueil</option>
                                        <option value="list"   @selected(old('page_context', $placement->page_context)==='list')  >Liste des établissements</option>
                                        <option value="detail" @selected(old('page_context', $placement->page_context)==='detail')>Détail établissement</option>
                                        <option value="blog"   @selected(old('page_context', $placement->page_context)==='blog')  >Blog / Articles</option>
                                        <option value="search" @selected(old('page_context', $placement->page_context)==='search')>Résultats de recherche</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-group-modern">
                                    <label class="form-label-modern"><i class="fas fa-layer-group"></i> Annonces max par zone</label>
                                    <input type="number" name="max_ads" class="form-control-modern"
                                           value="{{ old('max_ads', $placement->max_ads) }}" min="1" max="10">
                                </div>
                            </div>
                            <div class="col-md-6 d-flex align-items-center">
                                <div class="form-check form-switch mt-3">
                                    <input type="checkbox" name="is_active" class="form-check-input" id="activeCheck"
                                           value="1" {{ old('is_active', $placement->is_active) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="activeCheck">
                                        <strong>Emplacement actif</strong>
                                    </label>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                {{-- Preview --}}
                <div class="card-modern">
                    <div class="card-header-modern">
                        <div class="card-header-icon"><i class="fas fa-eye"></i></div>
                        <h3 class="card-title-modern">Zone d'affichage</h3>
                    </div>
                    <div class="card-body-modern text-center">
                        @php
                            $dims  = config('ads-manager.ad_formats.'.$placement->format, ['width'=>300,'height'=>250]);
                            $scale = min(1, 220 / $dims['width']);
                            $pvW   = round($dims['width'] * $scale);
                            $pvH   = round($dims['height'] * $scale);
                        @endphp
                        <div style="width:{{ $pvW }}px;height:{{ $pvH }}px;background:linear-gradient(135deg,#667eea30,#764ba230);border:2px dashed #667eea;border-radius:12px;display:flex;align-items:center;justify-content:center;margin:0 auto;font-size:13px;font-weight:600;color:#667eea;">
                            {{ $dims['width'] }}×{{ $dims['height'] }}
                        </div>
                        <p class="text-muted mt-2" style="font-size:12px;">Format : {{ config('ads-manager.ad_formats.'.$placement->format.'.label', $placement->format) }}</p>
                    </div>
                </div>

                {{-- Snippet --}}
                <div class="card-modern">
                    <div class="card-header-modern">
                        <div class="card-header-icon"><i class="fas fa-code"></i></div>
                        <h3 class="card-title-modern">Code d'intégration</h3>
                    </div>
                    <div class="card-body-modern">
                        <div class="snippet-block">
                            <div class="snippet-label">Directive Blade</div>
                            <div class="snippet-code" id="bladeSnippet">@adZone('{{ $placement->code }}')</div>
                            <button class="btn-copy" onclick="copyText('bladeSnippet')"><i class="fas fa-copy"></i></button>
                        </div>
                        <div class="snippet-block mt-2">
                            <div class="snippet-label">HTML direct</div>
                            <div class="snippet-code" id="htmlSnippet">&lt;div id="ad-zone-{{ $placement->code }}"&gt;&lt;/div&gt;</div>
                            <button class="btn-copy" onclick="copyText('htmlSnippet')"><i class="fas fa-copy"></i></button>
                        </div>
                    </div>
                </div>

                {{-- Stats --}}
                <div class="card-modern">
                    <div class="card-header-modern">
                        <div class="card-header-icon"><i class="fas fa-chart-bar"></i></div>
                        <h3 class="card-title-modern">Statistiques</h3>
                    </div>
                    <div class="card-body-modern">
                        @php
                            $adsInZone = \Illuminate\Support\Facades\DB::table('ad_placement')
                                ->where('placement_id', $placement->id)
                                ->where('is_active', true)
                                ->count();
                        @endphp
                        <div class="info-list">
                            <div class="info-row"><span>Annonces actives</span><strong>{{ $adsInZone }}</strong></div>
                            <div class="info-row"><span>Annonces max</span><strong>{{ $placement->max_ads }}</strong></div>
                            <div class="info-row"><span>Créé le</span><strong>{{ \Carbon\Carbon::parse($placement->created_at)->format('d/m/Y') }}</strong></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-actions-modern">
            <a href="{{ route('ads-manager.placements.index') }}" class="btn-cancel">
                <i class="fas fa-times"></i> Annuler
            </a>
            <button type="submit" class="btn-submit">
                <i class="fas fa-save"></i> Enregistrer les modifications
            </button>
        </div>

    </form>
</main>

<script>
function copyText(elId) {
    const text = document.getElementById(elId).textContent;
    navigator.clipboard.writeText(text).then(() => {
        const btn = document.querySelector(`button[onclick="copyText('${elId}')"]`);
        btn.innerHTML = '<i class="fas fa-check"></i>';
        setTimeout(() => btn.innerHTML = '<i class="fas fa-copy"></i>', 1500);
    });
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
.alert-info-banner{background:#fffbeb;border:1px solid #fde68a;border-radius:14px;padding:14px 18px;margin-bottom:24px;font-size:13px;color:#92400e;display:flex;align-items:center;gap:10px;}
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
.form-control-modern:disabled{background:#f8fafc;color:#94a3b8;cursor:not-allowed;}
.snippet-block{position:relative;background:#1e293b;border-radius:12px;padding:12px 40px 12px 14px;}
.snippet-label{font-size:10px;font-weight:600;color:#94a3b8;text-transform:uppercase;margin-bottom:5px;}
.snippet-code{font-family:monospace;font-size:12px;color:#7dd3fc;word-break:break-all;}
.btn-copy{position:absolute;top:8px;right:8px;width:28px;height:28px;background:rgba(255,255,255,.1);border:none;border-radius:8px;color:#fff;cursor:pointer;display:flex;align-items:center;justify-content:center;font-size:12px;}
.btn-copy:hover{background:rgba(255,255,255,.2);}
.info-list{display:flex;flex-direction:column;}
.info-row{display:flex;justify-content:space-between;padding:9px 0;border-bottom:1px solid #f1f5f9;font-size:13px;}
.info-row span{color:#64748b;}
.form-actions-modern{display:flex;justify-content:space-between;align-items:center;margin-top:32px;padding:20px 24px;background:#fff;border-radius:24px;box-shadow:0 4px 20px rgba(0,0,0,.05);}
.btn-cancel{padding:12px 20px;background:#f1f5f9;border:none;border-radius:12px;color:#475569;font-weight:500;cursor:pointer;text-decoration:none;display:inline-flex;align-items:center;gap:8px;}
.btn-submit{padding:12px 28px;background:linear-gradient(135deg,#667eea,#764ba2);border:none;border-radius:12px;color:#fff;font-weight:600;cursor:pointer;display:inline-flex;align-items:center;gap:8px;}
.btn-submit:hover{transform:translateY(-2px);box-shadow:0 6px 16px rgba(102,126,234,.35);}
</style>
@endsection