@extends('layouts.app')

@section('content')
<main class="dashboard-content">

    {{-- ========================================================== --}}
    {{-- PAGE HEADER                                                  --}}
    {{-- ========================================================== --}}
    <div class="page-header-modern">
        <div class="page-header-content">
            <div class="page-header-left">
                <div class="page-header-icon">
                    <i class="fas fa-ad"></i>
                </div>
                <div>
                    <h1 class="page-title-modern">Gestion des Publicités</h1>
                    <p class="page-subtitle-modern">Créez et gérez vos annonces publicitaires</p>
                </div>
            </div>
            <div class="page-header-right d-flex gap-2">
                <a href="{{ route('ads-manager.placements.index') }}" class="btn-back">
                    <i class="fas fa-map-marker-alt me-2"></i>Emplacements
                </a>
                <a href="{{ route('ads-manager.reports.index') }}" class="btn-back">
                    <i class="fas fa-chart-bar me-2"></i>Rapports
                </a>
                <a href="{{ route('ads-manager.ads.create') }}" class="btn-create">
                    <i class="fas fa-plus-circle me-2"></i>Nouvelle annonce
                </a>
            </div>
        </div>
    </div>

    {{-- ========================================================== --}}
    {{-- STATS CARDS                                                  --}}
    {{-- ========================================================== --}}
    <div class="stats-grid">

        <div class="stats-card-modern">
            <div class="stats-header-modern">
                <div>
                    <div class="stats-value-modern">{{ $stats['total_ads'] ?? 0 }}</div>
                    <div class="stats-label-modern">Total annonces</div>
                </div>
                <div class="stats-icon-modern" style="background:linear-gradient(135deg,#667eea,#764ba2);">
                    <i class="fas fa-ad"></i>
                </div>
            </div>
        </div>

        <div class="stats-card-modern">
            <div class="stats-header-modern">
                <div>
                    <div class="stats-value-modern text-success">{{ $stats['active_ads'] ?? 0 }}</div>
                    <div class="stats-label-modern">Actives</div>
                </div>
                <div class="stats-icon-modern" style="background:linear-gradient(135deg,#06b48a,#049a72);">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
        </div>

        <div class="stats-card-modern">
            <div class="stats-header-modern">
                <div>
                    <div class="stats-value-modern text-warning">{{ $stats['pending_ads'] ?? 0 }}</div>
                    <div class="stats-label-modern">En attente</div>
                </div>
                <div class="stats-icon-modern" style="background:linear-gradient(135deg,#ffd166,#ffb347);">
                    <i class="fas fa-clock"></i>
                </div>
            </div>
        </div>

        <div class="stats-card-modern">
            <div class="stats-header-modern">
                <div>
                    <div class="stats-value-modern">{{ \Vendor\AdsManager\Helpers\Helper::formatNumber($stats['total_impressions'] ?? 0) }}</div>
                    <div class="stats-label-modern">Impressions totales</div>
                </div>
                <div class="stats-icon-modern" style="background:linear-gradient(135deg,#45b7d1,#3a9bb8);">
                    <i class="fas fa-eye"></i>
                </div>
            </div>
        </div>

        <div class="stats-card-modern">
            <div class="stats-header-modern">
                <div>
                    <div class="stats-value-modern">{{ \Vendor\AdsManager\Helpers\Helper::formatNumber($stats['clicks_today'] ?? 0) }}</div>
                    <div class="stats-label-modern">Clics aujourd'hui</div>
                </div>
                <div class="stats-icon-modern" style="background:linear-gradient(135deg,#ff6b6b,#d4335f);">
                    <i class="fas fa-mouse-pointer"></i>
                </div>
            </div>
        </div>

        <div class="stats-card-modern">
            <div class="stats-header-modern">
                <div>
                    <div class="stats-value-modern">{{ \Vendor\AdsManager\Helpers\Helper::formatCurrency($stats['revenue_month'] ?? 0) }}</div>
                    <div class="stats-label-modern">Revenus ce mois</div>
                </div>
                <div class="stats-icon-modern" style="background:linear-gradient(135deg,#9b59b6,#8e44ad);">
                    <i class="fas fa-coins"></i>
                </div>
            </div>
        </div>

    </div>

    {{-- ========================================================== --}}
    {{-- ALERTES                                                      --}}
    {{-- ========================================================== --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- ========================================================== --}}
    {{-- TABLEAU                                                      --}}
    {{-- ========================================================== --}}
    <div class="main-card-modern">
        <div class="card-header-modern d-flex align-items-center gap-3 flex-wrap">
            <h3 class="card-title-modern flex-grow-1">Liste des annonces</h3>

            {{-- Filtres rapides --}}
            <form method="GET" class="d-flex gap-2 flex-wrap">
                <input type="text" name="search" value="{{ request('search') }}"
                       class="form-control form-control-sm" placeholder="Rechercher…" style="width:180px;">
                <select name="status" class="form-select form-select-sm" style="width:140px;">
                    <option value="">Tous statuts</option>
                    @foreach(config('ads-manager.ad_statuses') as $key => $label)
                        <option value="{{ $key }}" @selected(request('status') === $key)>{{ $label }}</option>
                    @endforeach
                </select>
                <select name="format" class="form-select form-select-sm" style="width:140px;">
                    <option value="">Tous formats</option>
                    @foreach(config('ads-manager.ad_formats') as $key => $f)
                        <option value="{{ $key }}" @selected(request('format') === $key)>{{ $f['label'] }}</option>
                    @endforeach
                </select>
                <button class="btn btn-sm btn-primary"><i class="fas fa-search"></i></button>
                @if(request()->anyFilled(['search','status','format']))
                    <a href="{{ route('ads-manager.ads.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-times"></i>
                    </a>
                @endif
            </form>
        </div>

        <div class="card-body-modern p-0">
            <div class="table-container-modern">
                <table class="modern-table">
                    <thead>
                        <tr>
                            <th>Annonce</th>
                            <th>Format</th>
                            <th>Modèle</th>
                            <th>Tarif</th>
                            <th>Période</th>
                            <th>Statut</th>
                            <th>Impressions</th>
                            <th>CTR</th>
                            <th style="text-align:center;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($ads as $ad)
                        @php
                            $impressions = \Illuminate\Support\Facades\DB::table('ad_impressions')->where('ad_id',$ad->id)->count();
                            $clicks      = \Illuminate\Support\Facades\DB::table('ad_clicks')->where('ad_id',$ad->id)->where('is_fraud',false)->count();
                            $ctr         = $impressions > 0 ? round(($clicks/$impressions)*100,2) : 0;
                        @endphp
                        <tr id="ad-row-{{ $ad->id }}">
                            {{-- Annonce --}}
                            <td>
                                <div class="ad-name-cell">
                                    @if($ad->image_path)
                                        <img src="{{ asset('storage/'.$ad->image_path) }}" alt="thumb"
                                             class="ad-thumb">
                                    @else
                                        <div class="ad-thumb-placeholder"
                                             style="background:{{ \Vendor\AdsManager\Helpers\Helper::getAvatarColor($ad->titre) }}">
                                            <i class="fas fa-{{ $ad->type === 'video' ? 'video' : ($ad->type === 'html' ? 'code' : 'image') }}"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <div class="ad-title">{{ \Vendor\AdsManager\Helpers\Helper::truncate($ad->titre, 40) }}</div>
                                        @if($ad->advertiser_name)
                                            <small class="text-muted"><i class="fas fa-building me-1"></i>{{ $ad->advertiser_name }}</small>
                                        @endif
                                        <small class="d-block text-muted">
                                            <i class="fas fa-tag me-1"></i>
                                            {{ ucfirst($ad->type) }}
                                        </small>
                                    </div>
                                </div>
                            </td>
                            {{-- Format --}}
                            <td>
                                <span class="format-badge">
                                    {{ config('ads-manager.ad_formats.'.$ad->format.'.label', $ad->format) }}
                                    <small class="d-block text-muted">{{ $ad->width }}×{{ $ad->height }}</small>
                                </span>
                            </td>
                            {{-- Modèle --}}
                            <td>
                                <span class="pricing-badge pricing-{{ $ad->pricing_model }}">
                                    {{ \Vendor\AdsManager\Helpers\Helper::getPricingLabel($ad->pricing_model) }}
                                </span>
                            </td>
                            {{-- Tarif --}}
                            <td>
                                <strong>{{ \Vendor\AdsManager\Helpers\Helper::formatCurrency($ad->rate) }}</strong>
                                @if($ad->budget_total)
                                    <div class="budget-bar mt-1">
                                        @php $pct = min(100, round(($ad->budget_spent / $ad->budget_total) * 100)); @endphp
                                        <div class="budget-fill {{ $pct > 80 ? 'danger' : ($pct > 50 ? 'warning' : 'ok') }}"
                                             style="width:{{ $pct }}%"></div>
                                    </div>
                                    <small class="text-muted">{{ \Vendor\AdsManager\Helpers\Helper::formatCurrency($ad->budget_spent) }} / {{ \Vendor\AdsManager\Helpers\Helper::formatCurrency($ad->budget_total) }}</small>
                                @endif
                            </td>
                            {{-- Période --}}
                            <td>
                                @if($ad->start_date)
                                    <small><i class="fas fa-play text-success me-1"></i>{{ \Carbon\Carbon::parse($ad->start_date)->format('d/m/Y') }}</small><br>
                                @endif
                                @if($ad->end_date)
                                    <small><i class="fas fa-stop text-danger me-1"></i>{{ \Carbon\Carbon::parse($ad->end_date)->format('d/m/Y') }}</small>
                                @else
                                    <small class="text-muted">Sans limite</small>
                                @endif
                            </td>
                            {{-- Statut --}}
                            <td>{!! \Vendor\AdsManager\Helpers\Helper::getStatusBadge($ad->status) !!}</td>
                            {{-- Impressions --}}
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <i class="fas fa-eye text-primary"></i>
                                    {{ \Vendor\AdsManager\Helpers\Helper::formatNumber($impressions) }}
                                </div>
                                <div class="d-flex align-items-center gap-2 mt-1">
                                    <i class="fas fa-mouse-pointer text-warning"></i>
                                    {{ \Vendor\AdsManager\Helpers\Helper::formatNumber($clicks) }}
                                </div>
                            </td>
                            {{-- CTR --}}
                            <td>
                                <span class="ctr-badge" style="color:{{ \Vendor\AdsManager\Helpers\Helper::getCtrColor($ctr) }}">
                                    {{ $ctr }}%
                                </span>
                            </td>
                            {{-- Actions --}}
                            <td>
                                <div class="campaign-actions-modern">
                                    <a href="{{ route('ads-manager.ads.show', $ad->id) }}"
                                       class="action-btn-modern view-btn-modern" title="Voir">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    @if(in_array($ad->status, ['draft','paused','pending']))
                                        <a href="{{ route('ads-manager.ads.edit', $ad->id) }}"
                                           class="action-btn-modern edit-btn-modern" title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    @endif

                                    @if($ad->status === 'pending')
                                        <form method="POST" action="{{ route('ads-manager.ads.approve', $ad->id) }}" style="display:inline;">
                                            @csrf
                                            <button class="action-btn-modern approve-btn" title="Approuver">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                    @endif

                                    @if($ad->status === 'active')
                                        <form method="POST" action="{{ route('ads-manager.ads.pause', $ad->id) }}" style="display:inline;">
                                            @csrf
                                            <button class="action-btn-modern pause-btn" title="Pause">
                                                <i class="fas fa-pause"></i>
                                            </button>
                                        </form>
                                    @endif

                                    @if($ad->status === 'paused')
                                        <form method="POST" action="{{ route('ads-manager.ads.activate', $ad->id) }}" style="display:inline;">
                                            @csrf
                                            <button class="action-btn-modern approve-btn" title="Activer">
                                                <i class="fas fa-play"></i>
                                            </button>
                                        </form>
                                    @endif

                                    <form method="POST" action="{{ route('ads-manager.ads.duplicate', $ad->id) }}" style="display:inline;">
                                        @csrf
                                        <button class="action-btn-modern duplicate-btn-modern" title="Dupliquer">
                                            <i class="fas fa-copy"></i>
                                        </button>
                                    </form>

                                    <a href="{{ route('ads-manager.reports.ad', $ad->id) }}"
                                       class="action-btn-modern stats-btn" title="Statistiques">
                                        <i class="fas fa-chart-bar"></i>
                                    </a>

                                    @if($ad->status !== 'active')
                                        <button class="action-btn-modern delete-btn-modern" title="Supprimer"
                                                onclick="confirmDelete({{ $ad->id }}, '{{ addslashes($ad->titre) }}')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-5">
                                <div class="empty-state-modern">
                                    <div class="empty-icon-modern"><i class="fas fa-ad"></i></div>
                                    <h3 class="empty-title-modern">Aucune annonce trouvée</h3>
                                    <p class="empty-text-modern">Créez votre première annonce publicitaire.</p>
                                    <a href="{{ route('ads-manager.ads.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus-circle me-2"></i>Créer une annonce
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Pagination --}}
        @if($ads->hasPages())
        <div class="pagination-container-modern">
            <div class="pagination-info-modern">
                Affichage de {{ $ads->firstItem() }} à {{ $ads->lastItem() }} sur {{ $ads->total() }} annonces
            </div>
            <nav><ul class="modern-pagination">{{ $ads->withQueryString()->links() }}</ul></nav>
        </div>
        @endif
    </div>

    {{-- FAB --}}
    <a href="{{ route('ads-manager.ads.create') }}" class="fab-modern">
        <i class="fas fa-plus"></i>
    </a>

</main>

{{-- MODAL SUPPRESSION --}}
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center py-4">
                <div class="delete-icon"><i class="fas fa-exclamation-triangle"></i></div>
                <h4 class="delete-title">Supprimer l'annonce ?</h4>
                <p class="delete-message" id="deleteModalName"></p>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <strong>Attention :</strong> Action irréversible — toutes les stats seront perdues.
                </div>
            </div>
            <div class="modal-footer border-0 justify-content-center">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <form id="deleteForm" method="POST">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-2"></i>Supprimer
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function confirmDelete(id, titre) {
    document.getElementById('deleteForm').action = '/ads-manager/ads/' + id;
    document.getElementById('deleteModalName').textContent = '"' + titre + '"';
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}
</script>

<style>
/* ---- Page header ---- */
.page-header-modern {
    background: linear-gradient(135deg, #667eea, #764ba2);
    border-radius: 24px; padding: 28px 32px; margin-bottom: 32px;
    box-shadow: 0 10px 25px -5px rgba(0,0,0,.1);
}
.page-header-content { display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:16px; }
.page-header-left  { display:flex; align-items:center; gap:20px; }
.page-header-icon  { width:60px; height:60px; background:rgba(255,255,255,.2); border-radius:20px; display:flex; align-items:center; justify-content:center; color:#fff; font-size:28px; }
.page-title-modern   { font-size:28px; font-weight:700; color:#fff; margin:0; }
.page-subtitle-modern{ color:rgba(255,255,255,.8); margin:5px 0 0; font-size:14px; }
.btn-back { background:rgba(255,255,255,.2); color:#fff; padding:10px 18px; border-radius:12px; text-decoration:none; font-size:13px; font-weight:500; transition:all .3s; display:inline-flex; align-items:center; }
.btn-back:hover { background:rgba(255,255,255,.3); color:#fff; }
.btn-create { background:#fff; color:#667eea; padding:10px 20px; border-radius:12px; text-decoration:none; font-size:14px; font-weight:600; transition:all .3s; display:inline-flex; align-items:center; }
.btn-create:hover { background:#f1f5f9; }

/* ---- Stats grid ---- */
.stats-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(180px,1fr)); gap:16px; margin-bottom:28px; }
.stats-card-modern { background:#fff; border-radius:20px; padding:20px; box-shadow:0 4px 16px rgba(0,0,0,.05); }
.stats-header-modern { display:flex; justify-content:space-between; align-items:center; }
.stats-value-modern { font-size:28px; font-weight:700; color:#1e293b; }
.stats-label-modern { font-size:12px; color:#64748b; margin-top:4px; }
.stats-icon-modern { width:48px; height:48px; border-radius:16px; display:flex; align-items:center; justify-content:center; color:#fff; font-size:20px; }

/* ---- Main card ---- */
.main-card-modern { background:#fff; border-radius:24px; box-shadow:0 4px 20px rgba(0,0,0,.05); overflow:hidden; }
.card-header-modern { padding:20px 24px; border-bottom:1px solid #eef2f6; background:#fafbfc; }
.card-title-modern { font-size:18px; font-weight:600; color:#1e293b; margin:0; }
.card-body-modern { padding:24px; }

/* ---- Table ---- */
.table-container-modern { overflow-x:auto; }
.modern-table { width:100%; border-collapse:collapse; }
.modern-table thead th { padding:12px 16px; font-size:12px; font-weight:600; color:#64748b; text-transform:uppercase; letter-spacing:.5px; border-bottom:2px solid #eef2f6; white-space:nowrap; }
.modern-table tbody td { padding:14px 16px; border-bottom:1px solid #f1f5f9; vertical-align:middle; }
.modern-table tbody tr:hover { background:#fafbfc; }

/* ---- Ad cell ---- */
.ad-name-cell { display:flex; align-items:center; gap:12px; }
.ad-thumb { width:56px; height:40px; object-fit:cover; border-radius:8px; border:1px solid #e2e8f0; }
.ad-thumb-placeholder { width:56px; height:40px; border-radius:8px; display:flex; align-items:center; justify-content:center; color:#fff; font-size:16px; }
.ad-title { font-weight:600; color:#1e293b; }

/* ---- Badges ---- */
.format-badge { font-size:12px; font-weight:500; color:#475569; }
.pricing-badge { padding:3px 10px; border-radius:20px; font-size:11px; font-weight:600; }
.pricing-cpm  { background:#eef2ff; color:#667eea; }
.pricing-cpc  { background:#fff3e0; color:#ffb347; }
.pricing-cpa  { background:#e8f5e9; color:#06b48a; }
.pricing-flat { background:#f3e8ff; color:#9b59b6; }
.ctr-badge { font-size:16px; font-weight:700; }

/* ---- Budget bar ---- */
.budget-bar { height:4px; background:#e2e8f0; border-radius:4px; overflow:hidden; }
.budget-fill { height:100%; border-radius:4px; transition:width .4s; }
.budget-fill.ok      { background:#06b48a; }
.budget-fill.warning { background:#ffd166; }
.budget-fill.danger  { background:#ef476f; }

/* ---- Action buttons ---- */
.campaign-actions-modern { display:flex; gap:6px; justify-content:center; flex-wrap:wrap; }
.action-btn-modern { width:32px; height:32px; border-radius:8px; display:inline-flex; align-items:center; justify-content:center; border:none; cursor:pointer; font-size:.85rem; text-decoration:none; transition:all .2s; }
.view-btn-modern     { background:linear-gradient(135deg,#45b7d1,#3a9bb8); color:#fff; }
.edit-btn-modern     { background:linear-gradient(135deg,#96ceb4,#7dba9a); color:#fff; }
.approve-btn         { background:linear-gradient(135deg,#06b48a,#049a72); color:#fff; }
.pause-btn           { background:linear-gradient(135deg,#ffd166,#ffb347); color:#fff; }
.duplicate-btn-modern{ background:linear-gradient(135deg,#9b59b6,#8e44ad); color:#fff; }
.stats-btn           { background:linear-gradient(135deg,#667eea,#764ba2); color:#fff; }
.delete-btn-modern   { background:linear-gradient(135deg,#ef476f,#d4335f); color:#fff; }
.action-btn-modern:hover { transform:translateY(-2px); box-shadow:0 4px 8px rgba(0,0,0,.15); color:#fff; }

/* ---- Pagination ---- */
.pagination-container-modern { display:flex; justify-content:space-between; align-items:center; padding:16px 24px; border-top:1px solid #eef2f6; }
.pagination-info-modern { font-size:13px; color:#64748b; }
.modern-pagination { display:flex; gap:6px; list-style:none; padding:0; margin:0; }

/* ---- Empty state ---- */
.empty-state-modern { padding:60px 20px; }
.empty-icon-modern { font-size:56px; color:#cbd5e1; margin-bottom:16px; }
.empty-title-modern { font-size:20px; font-weight:600; color:#1e293b; }
.empty-text-modern  { color:#64748b; margin-bottom:20px; }

/* ---- FAB ---- */
.fab-modern { position:fixed; bottom:30px; right:30px; width:56px; height:56px; border-radius:50%; background:linear-gradient(135deg,#667eea,#764ba2); color:#fff; display:flex; align-items:center; justify-content:center; font-size:22px; text-decoration:none; box-shadow:0 8px 20px -5px rgba(102,126,234,.5); transition:all .3s; z-index:1000; }
.fab-modern:hover { transform:scale(1.1); color:#fff; }

/* ---- Delete modal ---- */
.delete-icon { font-size:48px; color:#ef476f; margin-bottom:16px; }
.delete-title { font-size:20px; font-weight:600; }
.delete-message { color:#64748b; }

@media(max-width:992px){
    .page-header-content { flex-direction:column; }
    .stats-grid { grid-template-columns:repeat(2,1fr); }
}
@media(max-width:768px){
    .stats-grid { grid-template-columns:1fr; }
    .page-header-modern { padding:20px; }
}
</style>
@endsection