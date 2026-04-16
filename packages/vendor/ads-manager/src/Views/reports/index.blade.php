@extends('layouts.app')

@section('content')
<main class="dashboard-content">

    <div class="page-header-modern">
        <div class="page-header-content">
            <div class="page-header-left">
                <div class="page-header-icon"><i class="fas fa-chart-bar"></i></div>
                <div>
                    <h1 class="page-title-modern">Tableau de bord Publicités</h1>
                    <p class="page-subtitle-modern">Vue globale des performances de vos annonces</p>
                </div>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('ads-manager.ads.index') }}" class="btn-back">
                    <i class="fas fa-ad me-2"></i>Annonces
                </a>
                <form method="POST" action="{{ route('ads-manager.reports.aggregate') }}">
                    @csrf
                    <input type="hidden" name="date" value="{{ date('Y-m-d') }}">
                    <button class="btn-create" type="submit">
                        <i class="fas fa-sync me-2"></i>Agréger aujourd'hui
                    </button>
                </form>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- ===================================================== --}}
    {{-- KPI GLOBAUX                                            --}}
    {{-- ===================================================== --}}
    <div class="stats-grid">
        <div class="kpi-big">
            <div class="kpi-icon-big" style="background:linear-gradient(135deg,#667eea,#764ba2)"><i class="fas fa-eye"></i></div>
            <div class="kpi-content">
                <div class="kpi-value-big">{{ \Vendor\AdsManager\Helpers\Helper::formatNumber($stats['total_impressions'] ?? 0) }}</div>
                <div class="kpi-label-big">Impressions totales</div>
                <div class="kpi-sub">Aujourd'hui : {{ \Vendor\AdsManager\Helpers\Helper::formatNumber($stats['impressions_today'] ?? 0) }}</div>
            </div>
        </div>
        <div class="kpi-big">
            <div class="kpi-icon-big" style="background:linear-gradient(135deg,#ff6b6b,#d4335f)"><i class="fas fa-mouse-pointer"></i></div>
            <div class="kpi-content">
                <div class="kpi-value-big">{{ \Vendor\AdsManager\Helpers\Helper::formatNumber($stats['total_clicks'] ?? 0) }}</div>
                <div class="kpi-label-big">Clics totaux</div>
                <div class="kpi-sub">Aujourd'hui : {{ \Vendor\AdsManager\Helpers\Helper::formatNumber($stats['clicks_today'] ?? 0) }}</div>
            </div>
        </div>
        <div class="kpi-big">
            <div class="kpi-icon-big" style="background:linear-gradient(135deg,#06b48a,#049a72)"><i class="fas fa-percent"></i></div>
            <div class="kpi-content">
                <div class="kpi-value-big" style="color:{{ \Vendor\AdsManager\Helpers\Helper::getCtrColor($stats['avg_ctr'] ?? 0) }}">
                    {{ $stats['avg_ctr'] ?? 0 }}%
                </div>
                <div class="kpi-label-big">CTR moyen</div>
                <div class="kpi-sub">Taux de clic global</div>
            </div>
        </div>
        <div class="kpi-big">
            <div class="kpi-icon-big" style="background:linear-gradient(135deg,#9b59b6,#8e44ad)"><i class="fas fa-coins"></i></div>
            <div class="kpi-content">
                <div class="kpi-value-big">{{ \Vendor\AdsManager\Helpers\Helper::formatCurrency($stats['revenue_month'] ?? 0) }}</div>
                <div class="kpi-label-big">Revenus ce mois</div>
                <div class="kpi-sub">Annonces actives : {{ $stats['active_ads'] ?? 0 }}</div>
            </div>
        </div>
    </div>

    <div class="row g-4">

        {{-- Top annonces par impressions --}}
        <div class="col-lg-7">
            <div class="card-modern">
                <div class="card-header-modern">
                    <div class="card-header-icon"><i class="fas fa-trophy"></i></div>
                    <h3 class="card-title-modern">Top annonces — Impressions</h3>
                </div>
                <div class="card-body-modern p-0">
                    <table class="modern-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Annonce</th>
                                <th>Format</th>
                                <th>Impressions</th>
                                <th>Clics</th>
                                <th>CTR</th>
                                <th>Revenus</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topAds as $i => $ad)
                            <tr>
                                <td>
                                    <span class="rank-badge rank-{{ $i+1 <= 3 ? $i+1 : 'other' }}">
                                        {{ $i+1 <= 3 ? ['🥇','🥈','🥉'][$i] : $i+1 }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('ads-manager.ads.show', $ad->id) }}" class="ad-link">
                                        {{ \Vendor\AdsManager\Helpers\Helper::truncate($ad->titre, 35) }}
                                    </a>
                                    <small class="d-block">{!! \Vendor\AdsManager\Helpers\Helper::getStatusBadge($ad->status) !!}</small>
                                </td>
                                <td><small>{{ $ad->format }}</small></td>
                                <td><strong>{{ \Vendor\AdsManager\Helpers\Helper::formatNumber((int)$ad->total_impressions) }}</strong></td>
                                <td>{{ \Vendor\AdsManager\Helpers\Helper::formatNumber((int)$ad->total_clicks) }}</td>
                                <td>
                                    <span style="color:{{ \Vendor\AdsManager\Helpers\Helper::getCtrColor((float)$ad->avg_ctr) }};font-weight:700;">
                                        {{ number_format($ad->avg_ctr, 2) }}%
                                    </span>
                                </td>
                                <td>{{ \Vendor\AdsManager\Helpers\Helper::formatCurrency((float)$ad->total_revenue) }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="7" class="text-center py-4 text-muted">Aucune donnée disponible</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Colonne droite --}}
        <div class="col-lg-5">

            {{-- Top par revenus --}}
            <div class="card-modern mb-4">
                <div class="card-header-modern">
                    <div class="card-header-icon" style="background:linear-gradient(135deg,#9b59b6,#8e44ad)"><i class="fas fa-coins"></i></div>
                    <h3 class="card-title-modern">Top par revenus</h3>
                </div>
                <div class="card-body-modern">
                    @foreach($topByRevenue as $ad)
                    <div class="top-item">
                        <div class="top-item-name">
                            <a href="{{ route('ads-manager.ads.show', $ad->id) }}" class="ad-link">
                                {{ \Vendor\AdsManager\Helpers\Helper::truncate($ad->titre, 28) }}
                            </a>
                        </div>
                        <div class="top-item-bar-wrap">
                            @php
                                $maxRev = $topByRevenue->max('total_revenue') ?: 1;
                                $pct    = round(($ad->total_revenue / $maxRev) * 100);
                            @endphp
                            <div class="top-item-bar" style="width:{{ $pct }}%;"></div>
                        </div>
                        <div class="top-item-value">{{ \Vendor\AdsManager\Helpers\Helper::formatCurrency((float)$ad->total_revenue) }}</div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Emplacements --}}
            <div class="card-modern">
                <div class="card-header-modern">
                    <div class="card-header-icon"><i class="fas fa-map-marker-alt"></i></div>
                    <h3 class="card-title-modern">Performance par emplacement</h3>
                </div>
                <div class="card-body-modern p-0">
                    <table class="modern-table">
                        <thead>
                            <tr><th>Emplacement</th><th>Impressions</th><th>Clics</th></tr>
                        </thead>
                        <tbody>
                            @forelse($placementStats as $p)
                            <tr>
                                <td>
                                    <strong>{{ $p->nom }}</strong>
                                    <small class="d-block text-muted">{{ $p->position }}</small>
                                </td>
                                <td>{{ \Vendor\AdsManager\Helpers\Helper::formatNumber((int)$p->total_impressions) }}</td>
                                <td>{{ \Vendor\AdsManager\Helpers\Helper::formatNumber((int)$p->total_clicks) }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="3" class="text-center py-3 text-muted">Aucune donnée</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>

<style>
.page-header-modern{background:linear-gradient(135deg,#667eea,#764ba2);border-radius:24px;padding:28px 32px;margin-bottom:32px;box-shadow:0 10px 25px -5px rgba(0,0,0,.1);}
.page-header-content{display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:16px;}
.page-header-left{display:flex;align-items:center;gap:20px;}
.page-header-icon{width:60px;height:60px;background:rgba(255,255,255,.2);border-radius:20px;display:flex;align-items:center;justify-content:center;color:#fff;font-size:28px;}
.page-title-modern{font-size:28px;font-weight:700;color:#fff;margin:0;}
.page-subtitle-modern{color:rgba(255,255,255,.8);margin:5px 0 0;font-size:14px;}
.btn-back{background:rgba(255,255,255,.2);color:#fff;padding:10px 18px;border-radius:12px;text-decoration:none;font-size:13px;font-weight:500;display:inline-flex;align-items:center;}
.btn-back:hover{background:rgba(255,255,255,.3);color:#fff;}
.btn-create{background:#fff;color:#667eea;padding:10px 18px;border:none;border-radius:12px;font-size:13px;font-weight:600;cursor:pointer;display:inline-flex;align-items:center;}

.stats-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:16px;margin-bottom:28px;}
.kpi-big{background:#fff;border-radius:20px;padding:20px;box-shadow:0 4px 16px rgba(0,0,0,.05);display:flex;align-items:center;gap:16px;}
.kpi-icon-big{width:56px;height:56px;border-radius:18px;display:flex;align-items:center;justify-content:center;color:#fff;font-size:22px;flex-shrink:0;}
.kpi-value-big{font-size:26px;font-weight:700;color:#1e293b;}
.kpi-label-big{font-size:12px;color:#64748b;margin-top:2px;}
.kpi-sub{font-size:11px;color:#94a3b8;margin-top:4px;}

.card-modern{background:#fff;border-radius:24px;box-shadow:0 4px 20px rgba(0,0,0,.05);margin-bottom:24px;overflow:hidden;}
.card-header-modern{display:flex;align-items:center;gap:12px;padding:20px 24px;border-bottom:1px solid #eef2f6;background:#fafbfc;}
.card-header-icon{width:36px;height:36px;background:linear-gradient(135deg,#667eea,#764ba2);border-radius:12px;display:flex;align-items:center;justify-content:center;color:#fff;font-size:16px;}
.card-title-modern{font-size:18px;font-weight:600;color:#1e293b;margin:0;flex:1;}
.card-body-modern{padding:24px;}

.modern-table{width:100%;border-collapse:collapse;}
.modern-table thead th{padding:10px 16px;font-size:11px;font-weight:600;color:#64748b;text-transform:uppercase;border-bottom:2px solid #eef2f6;}
.modern-table tbody td{padding:12px 16px;border-bottom:1px solid #f1f5f9;vertical-align:middle;font-size:13px;}
.modern-table tbody tr:hover{background:#fafbfc;}

.rank-badge{font-size:16px;}
.ad-link{color:#667eea;text-decoration:none;font-weight:600;font-size:13px;}
.ad-link:hover{text-decoration:underline;}

.top-item{display:flex;align-items:center;gap:10px;margin-bottom:12px;}
.top-item-name{width:130px;font-size:12px;font-weight:600;color:#1e293b;flex-shrink:0;}
.top-item-bar-wrap{flex:1;height:6px;background:#e2e8f0;border-radius:4px;overflow:hidden;}
.top-item-bar{height:100%;background:linear-gradient(135deg,#9b59b6,#8e44ad);border-radius:4px;transition:width .6s;}
.top-item-value{font-size:12px;font-weight:700;color:#1e293b;width:90px;text-align:right;}

@media(max-width:768px){
    .stats-grid{grid-template-columns:1fr 1fr;}
    .page-header-content{flex-direction:column;}
}
@media(max-width:480px){.stats-grid{grid-template-columns:1fr;}}
</style>
@endsection