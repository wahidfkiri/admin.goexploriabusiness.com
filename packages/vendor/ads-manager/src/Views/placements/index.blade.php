@extends('layouts.app')

@section('content')
<main class="dashboard-content">

    <div class="page-header-modern">
        <div class="page-header-content">
            <div class="page-header-left">
                <div class="page-header-icon"><i class="fas fa-map-marker-alt"></i></div>
                <div>
                    <h1 class="page-title-modern">Emplacements Publicitaires</h1>
                    <p class="page-subtitle-modern">Gérez les zones d'affichage sur les pages des établissements</p>
                </div>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('ads-manager.ads.index') }}" class="btn-back">
                    <i class="fas fa-ad me-2"></i>Annonces
                </a>
                <a href="{{ route('ads-manager.placements.create') }}" class="btn-create">
                    <i class="fas fa-plus-circle me-2"></i>Nouvel emplacement
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Grille des emplacements --}}
    <div class="placements-grid">
        @forelse($placements as $placement)
        @php $adsCount = $adCounts[$placement->id] ?? 0; @endphp
        <div class="placement-card {{ $placement->is_active ? 'active' : 'inactive' }}">

            {{-- Badge statut --}}
            <div class="placement-status-badge">
                @if($placement->is_active)
                    <span class="badge bg-success"><i class="fas fa-circle me-1" style="font-size:6px;"></i>Actif</span>
                @else
                    <span class="badge bg-secondary"><i class="fas fa-circle me-1" style="font-size:6px;"></i>Inactif</span>
                @endif
            </div>

            {{-- Visuel format --}}
            <div class="placement-visual">
                @php
                    $dims  = config('ads-manager.ad_formats.'.$placement->format, ['width'=>300,'height'=>250]);
                    $ratio = $dims['height'] / $dims['width'];
                    $previewW = 140;
                    $previewH = max(40, min(120, round($previewW * $ratio)));
                @endphp
                <div class="format-preview" style="width:{{ $previewW }}px;height:{{ $previewH }}px;">
                    <span>{{ $dims['width'] }}×{{ $dims['height'] }}</span>
                </div>
            </div>

            {{-- Infos --}}
            <div class="placement-body">
                <h4 class="placement-name">{{ $placement->nom }}</h4>
                <code class="placement-code">{{ $placement->code }}</code>

                <div class="placement-meta">
                    <div class="meta-item">
                        <i class="fas fa-map-pin"></i>
                        {{ config('ads-manager.placement_positions.'.$placement->position, $placement->position) }}
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-expand-alt"></i>
                        {{ config('ads-manager.ad_formats.'.$placement->format.'.label', $placement->format) }}
                    </div>
                    @if($placement->etab_name)
                    <div class="meta-item">
                        <i class="fas fa-building"></i>
                        {{ $placement->etab_name }}
                    </div>
                    @else
                    <div class="meta-item">
                        <i class="fas fa-globe"></i>
                        Global (tous établissements)
                    </div>
                    @endif
                    @if($placement->page_context)
                    <div class="meta-item">
                        <i class="fas fa-file-alt"></i>
                        Page : {{ $placement->page_context }}
                    </div>
                    @endif
                </div>

                {{-- Stats annonces actives --}}
                <div class="placement-stats">
                    <div class="pstat">
                        <span class="pstat-value {{ $adsCount > 0 ? 'text-success' : 'text-muted' }}">
                            {{ $adsCount }}
                        </span>
                        <span class="pstat-label">Annonce(s) active(s)</span>
                    </div>
                    <div class="pstat">
                        <span class="pstat-value">{{ $placement->max_ads }}</span>
                        <span class="pstat-label">Max par zone</span>
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="placement-footer">
                {{-- Snippet --}}
                <button class="btn-snippet" onclick="showSnippet({{ $placement->id }}, '{{ $placement->code }}')"
                        title="Obtenir le code">
                    <i class="fas fa-code"></i> Snippet
                </button>

                {{-- Toggle --}}
                <form method="POST" action="{{ route('ads-manager.placements.toggle', $placement->id) }}" style="display:inline;">
                    @csrf
                    <button class="btn-toggle {{ $placement->is_active ? 'active' : '' }}" title="{{ $placement->is_active ? 'Désactiver' : 'Activer' }}">
                        <i class="fas fa-{{ $placement->is_active ? 'pause' : 'play' }}"></i>
                    </button>
                </form>

                <a href="{{ route('ads-manager.placements.edit', $placement->id) }}" class="btn-edit-sm" title="Modifier">
                    <i class="fas fa-edit"></i>
                </a>

                <form method="POST" action="{{ route('ads-manager.placements.destroy', $placement->id) }}"
                      onsubmit="return confirm('Supprimer cet emplacement ?')">
                    @csrf @method('DELETE')
                    <button class="btn-delete-sm" title="Supprimer">
                        <i class="fas fa-trash"></i>
                    </button>
                </form>
            </div>
        </div>
        @empty
        <div class="empty-placements">
            <i class="fas fa-map-marker-alt"></i>
            <h3>Aucun emplacement configuré</h3>
            <p>Créez votre premier emplacement pour commencer à diffuser des publicités.</p>
            <a href="{{ route('ads-manager.placements.create') }}" class="btn btn-primary">
                <i class="fas fa-plus-circle me-2"></i>Créer un emplacement
            </a>
        </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($placements->hasPages())
    <div class="pagination-container-modern">
        <div class="pagination-info-modern">
            Affichage de {{ $placements->firstItem() }} à {{ $placements->lastItem() }} sur {{ $placements->total() }}
        </div>
        <nav><ul class="modern-pagination">{{ $placements->withQueryString()->links() }}</ul></nav>
    </div>
    @endif

    <a href="{{ route('ads-manager.placements.create') }}" class="fab-modern">
        <i class="fas fa-plus"></i>
    </a>

</main>

{{-- SNIPPET MODAL --}}
<div class="modal fade" id="snippetModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-code me-2"></i>Code d'intégration</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted">Collez ce code dans vos vues Blade pour afficher la zone publicitaire :</p>

                <div class="snippet-block">
                    <label class="snippet-label">Directive Blade</label>
                    <div class="snippet-code" id="snippetBlade"></div>
                    <button class="btn-copy" onclick="copySnippet('snippetBlade')">
                        <i class="fas fa-copy"></i>
                    </button>
                </div>

                <div class="snippet-block mt-3">
                    <label class="snippet-label">HTML pur</label>
                    <div class="snippet-code" id="snippetHtml"></div>
                    <button class="btn-copy" onclick="copySnippet('snippetHtml')">
                        <i class="fas fa-copy"></i>
                    </button>
                </div>

                <div class="alert alert-info mt-3">
                    <i class="fas fa-info-circle me-2"></i>
                    Assurez-vous d'avoir la balise
                    <code>&lt;meta name="csrf-token" content="{{ csrf_token() }}"&gt;</code>
                    dans votre <code>&lt;head&gt;</code>.
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function showSnippet(id, code) {
    fetch(`/ads-manager/placements/${id}/snippet`)
        .then(r => r.json())
        .then(data => {
            document.getElementById('snippetBlade').textContent = data.blade;
            document.getElementById('snippetHtml').textContent  = data.html;
            new bootstrap.Modal(document.getElementById('snippetModal')).show();
        });
}

function copySnippet(elId) {
    const text = document.getElementById(elId).textContent;
    navigator.clipboard.writeText(text).then(() => {
        const btn = document.querySelector(`button[onclick="copySnippet('${elId}')"]`);
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
.btn-create{background:#fff;color:#667eea;padding:10px 20px;border-radius:12px;text-decoration:none;font-size:14px;font-weight:600;display:inline-flex;align-items:center;}

/* Grid */
.placements-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:20px;margin-bottom:32px;}

/* Card */
.placement-card{background:#fff;border-radius:24px;box-shadow:0 4px 20px rgba(0,0,0,.05);overflow:hidden;border:2px solid #eef2f6;transition:all .3s;position:relative;}
.placement-card:hover{transform:translateY(-4px);box-shadow:0 12px 30px rgba(0,0,0,.1);}
.placement-card.active{border-color:#06b48a;}
.placement-card.inactive{opacity:.75;}
.placement-status-badge{position:absolute;top:12px;right:12px;}

.placement-visual{display:flex;justify-content:center;padding:20px 20px 10px;background:#fafbfc;}
.format-preview{background:linear-gradient(135deg,#667eea,#764ba2);border-radius:10px;display:flex;align-items:center;justify-content:center;color:rgba(255,255,255,.7);font-size:11px;font-weight:600;}

.placement-body{padding:16px 20px;}
.placement-name{font-size:16px;font-weight:700;color:#1e293b;margin:0 0 4px;}
.placement-code{font-size:11px;background:#f1f5f9;padding:2px 8px;border-radius:6px;color:#667eea;font-weight:600;}
.placement-meta{margin-top:12px;display:flex;flex-direction:column;gap:5px;}
.meta-item{font-size:12px;color:#64748b;display:flex;align-items:center;gap:6px;}
.meta-item i{width:14px;color:#94a3b8;}

.placement-stats{display:flex;gap:20px;margin-top:14px;padding-top:12px;border-top:1px solid #eef2f6;}
.pstat{display:flex;flex-direction:column;align-items:center;}
.pstat-value{font-size:22px;font-weight:700;color:#1e293b;}
.pstat-label{font-size:10px;color:#94a3b8;text-align:center;}

.placement-footer{padding:12px 16px;border-top:1px solid #eef2f6;background:#fafbfc;display:flex;gap:8px;align-items:center;}
.btn-snippet{padding:6px 12px;background:#eef2ff;color:#667eea;border:none;border-radius:8px;font-size:12px;font-weight:600;cursor:pointer;display:inline-flex;align-items:center;gap:6px;}
.btn-snippet:hover{background:#667eea;color:#fff;}
.btn-toggle{width:32px;height:32px;border-radius:8px;border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;background:#f1f5f9;color:#475569;}
.btn-toggle.active{background:#ffd166;color:#2d3748;}
.btn-edit-sm,.btn-delete-sm{width:32px;height:32px;border-radius:8px;border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;font-size:13px;text-decoration:none;}
.btn-edit-sm{background:#e8f5e9;color:#06b48a;}
.btn-delete-sm{background:#ffebee;color:#ef476f;}
.btn-edit-sm:hover,.btn-delete-sm:hover{transform:translateY(-2px);}

/* Empty */
.empty-placements{grid-column:1/-1;text-align:center;padding:80px 20px;}
.empty-placements i{font-size:56px;color:#cbd5e1;margin-bottom:16px;}
.empty-placements h3{font-size:20px;font-weight:600;color:#1e293b;}
.empty-placements p{color:#64748b;margin-bottom:20px;}

/* Snippet modal */
.snippet-block{position:relative;background:#1e293b;border-radius:12px;padding:14px;padding-right:48px;}
.snippet-label{font-size:11px;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.5px;display:block;margin-bottom:6px;}
.snippet-code{font-family:monospace;font-size:13px;color:#7dd3fc;word-break:break-all;}
.btn-copy{position:absolute;top:10px;right:10px;width:30px;height:30px;background:rgba(255,255,255,.1);border:none;border-radius:8px;color:#fff;cursor:pointer;display:flex;align-items:center;justify-content:center;}
.btn-copy:hover{background:rgba(255,255,255,.2);}

.fab-modern{position:fixed;bottom:30px;right:30px;width:56px;height:56px;border-radius:50%;background:linear-gradient(135deg,#667eea,#764ba2);color:#fff;display:flex;align-items:center;justify-content:center;font-size:22px;text-decoration:none;box-shadow:0 8px 20px -5px rgba(102,126,234,.5);transition:all .3s;z-index:1000;}
.fab-modern:hover{transform:scale(1.1);color:#fff;}
.pagination-container-modern{display:flex;justify-content:space-between;align-items:center;padding:16px;}
.pagination-info-modern{font-size:13px;color:#64748b;}

@media(max-width:600px){.placements-grid{grid-template-columns:1fr;}}
</style>
@endsection