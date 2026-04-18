@extends('layouts.app')

@section('content')
<main class="dashboard-content">

    {{-- PAGE HEADER --}}
    <div class="page-header-modern">
        <div class="page-header-content">
            <div class="page-header-left">
                <div class="page-header-icon"><i class="fas fa-chart-line"></i></div>
                <div>
                    <h1 class="page-title-modern">Rapport : {{ \Vendor\AdsManager\Helpers\Helper::truncate($ad->titre, 45) }}</h1>
                    <p class="page-subtitle-modern">
                        {!! \Vendor\AdsManager\Helpers\Helper::getStatusBadge($ad->status) !!}
                        &nbsp;•&nbsp; {{ \Vendor\AdsManager\Helpers\Helper::getPricingLabel($ad->pricing_model) }}
                        &nbsp;•&nbsp; {{ config('ads-manager.ad_formats.'.$ad->format.'.label', $ad->format) }}
                    </p>
                </div>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <a href="{{ route('ads-manager.ads.show', $ad->id) }}" class="btn-back">
                    <i class="fas fa-ad me-2"></i>Voir l'annonce
                </a>
                <a href="{{ route('ads-manager.reports.index') }}" class="btn-back">
                    <i class="fas fa-arrow-left me-2"></i>Rapports
                </a>
            </div>
        </div>
    </div>

    {{-- KPI CARDS --}}
    @php
        $impressions       = $stats['impressions'] ?? 0;
        $uniqueImpressions = $stats['unique_impressions'] ?? 0;
        $clicks            = $stats['clicks'] ?? 0;
        $fraudClicks       = $stats['fraud_clicks'] ?? 0;
        $ctr               = $stats['ctr'] ?? 0;
        $revenue           = $stats['revenue'] ?? 0;
        $impressionsToday  = $stats['impressions_today'] ?? 0;
        $clicksToday       = $stats['clicks_today'] ?? 0;
    @endphp

    <div class="kpi-grid">
        <div class="kpi-card-big">
            <div class="kpi-top">
                <div class="kpi-icon-wrap" style="background:#eef2ff;color:#667eea;"><i class="fas fa-eye"></i></div>
                <div class="kpi-delta {{ $impressionsToday > 0 ? 'up' : 'neutral' }}">
                    <i class="fas fa-arrow-{{ $impressionsToday > 0 ? 'up' : 'minus' }}"></i>
                    {{ \Vendor\AdsManager\Helpers\Helper::formatNumber($impressionsToday) }} auj.
                </div>
            </div>
            <div class="kpi-num">{{ \Vendor\AdsManager\Helpers\Helper::formatNumber($impressions) }}</div>
            <div class="kpi-lbl">Impressions totales</div>
            <div class="kpi-sub">Uniques : {{ \Vendor\AdsManager\Helpers\Helper::formatNumber($uniqueImpressions) }}</div>
        </div>

        <div class="kpi-card-big">
            <div class="kpi-top">
                <div class="kpi-icon-wrap" style="background:#fff3e0;color:#ffb347;"><i class="fas fa-mouse-pointer"></i></div>
                <div class="kpi-delta {{ $clicksToday > 0 ? 'up' : 'neutral' }}">
                    <i class="fas fa-arrow-{{ $clicksToday > 0 ? 'up' : 'minus' }}"></i>
                    {{ \Vendor\AdsManager\Helpers\Helper::formatNumber($clicksToday) }} auj.
                </div>
            </div>
            <div class="kpi-num">{{ \Vendor\AdsManager\Helpers\Helper::formatNumber($clicks) }}</div>
            <div class="kpi-lbl">Clics valides</div>
            <div class="kpi-sub">Fraude : {{ \Vendor\AdsManager\Helpers\Helper::formatNumber($fraudClicks) }}</div>
        </div>

        <div class="kpi-card-big">
            <div class="kpi-top">
                <div class="kpi-icon-wrap" style="background:#e8f5e9;color:#06b48a;"><i class="fas fa-percent"></i></div>
                <div class="ctr-pill" style="background:{{ \Vendor\AdsManager\Helpers\Helper::getCtrColor($ctr) }}20;color:{{ \Vendor\AdsManager\Helpers\Helper::getCtrColor($ctr) }};">
                    {{ $ctr < 1 ? 'Faible' : ($ctr < 3 ? 'Moyen' : 'Bon') }}
                </div>
            </div>
            <div class="kpi-num" style="color:{{ \Vendor\AdsManager\Helpers\Helper::getCtrColor($ctr) }}">{{ $ctr }}%</div>
            <div class="kpi-lbl">Taux de clic (CTR)</div>
            <div class="kpi-sub">Moyenne industrie : ~2%</div>
        </div>

        <div class="kpi-card-big">
            <div class="kpi-top">
                <div class="kpi-icon-wrap" style="background:#f3e8ff;color:#9b59b6;"><i class="fas fa-coins"></i></div>
                @if($ad->budget_total)
                <div class="kpi-delta neutral">
                    Budget : {{ \Vendor\AdsManager\Helpers\Helper::formatCurrency($ad->budget_total) }}
                </div>
                @endif
            </div>
            <div class="kpi-num">{{ \Vendor\AdsManager\Helpers\Helper::formatCurrency($revenue) }}</div>
            <div class="kpi-lbl">Revenus générés</div>
            @if($ad->budget_total && $ad->budget_total > 0)
            <div class="kpi-sub">
                Dépensé : {{ min(100, round(($ad->budget_spent / $ad->budget_total) * 100)) }}% du budget
            </div>
            @endif
        </div>
    </div>

    {{-- BUDGET PROGRESS (if applicable) --}}
    @if($ad->budget_total)
    @php $budgetPct = min(100, round(($ad->budget_spent / $ad->budget_total) * 100)); @endphp
    <div class="budget-card">
        <div class="budget-header">
            <span><i class="fas fa-wallet me-2"></i>Consommation du budget</span>
            <span>{{ \Vendor\AdsManager\Helpers\Helper::formatCurrency($ad->budget_spent) }} / {{ \Vendor\AdsManager\Helpers\Helper::formatCurrency($ad->budget_total) }}</span>
        </div>
        <div class="budget-track">
            <div class="budget-fill {{ $budgetPct > 80 ? 'danger' : ($budgetPct > 50 ? 'warning' : 'ok') }}"
                 style="width:{{ $budgetPct }}%">
                <span>{{ $budgetPct }}%</span>
            </div>
        </div>
        @if($budgetPct >= 80)
        <div class="budget-alert"><i class="fas fa-exclamation-triangle me-1"></i>Budget presque épuisé</div>
        @endif
    </div>
    @endif

    <div class="row g-4">

        {{-- TIMELINE CHART --}}
        <div class="col-lg-8">
            <div class="card-modern">
                <div class="card-header-modern">
                    <div class="card-header-icon"><i class="fas fa-chart-area"></i></div>
                    <h3 class="card-title-modern">Performance — 30 derniers jours</h3>
                    <div class="chart-legend">
                        <span class="legend-dot" style="background:#667eea;"></span> Impressions
                        <span class="legend-dot" style="background:#06b48a;"></span> Clics
                        <span class="legend-dot" style="background:#ffb347;"></span> Revenus
                    </div>
                </div>
                <div class="card-body-modern">
                    @if(!empty($timeline))
                        <canvas id="timelineChart" height="100"></canvas>
                    @else
                        <div class="empty-chart">
                            <i class="fas fa-chart-area"></i>
                            <p>Aucune donnée pour les 30 derniers jours</p>
                            <small>Les données apparaissent après agrégation quotidienne</small>
                        </div>
                    @endif
                </div>
            </div>

            {{-- CTR Distribution --}}
            @if(!empty($timeline))
            <div class="card-modern">
                <div class="card-header-modern">
                    <div class="card-header-icon"><i class="fas fa-chart-bar"></i></div>
                    <h3 class="card-title-modern">CTR quotidien (%)</h3>
                </div>
                <div class="card-body-modern">
                    <canvas id="ctrChart" height="80"></canvas>
                </div>
            </div>
            @endif

            {{-- Timeline DATA TABLE --}}
            <div class="card-modern">
                <div class="card-header-modern">
                    <div class="card-header-icon"><i class="fas fa-table"></i></div>
                    <h3 class="card-title-modern">Données journalières</h3>
                    <span class="card-badge">{{ count($timeline) }} jours</span>
                </div>
                <div class="card-body-modern p-0">
                    <div class="table-scroll">
                        <table class="modern-table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Impressions</th>
                                    <th>Uniques</th>
                                    <th>Clics</th>
                                    <th>CTR</th>
                                    <th>Revenus</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse(array_reverse((array)$timeline) as $row)
                                @php $row = (object)$row; @endphp
                                <tr>
                                    <td><strong>{{ \Carbon\Carbon::parse($row->report_date)->format('d/m/Y') }}</strong></td>
                                    <td>{{ \Vendor\AdsManager\Helpers\Helper::formatNumber((int)($row->impressions ?? 0)) }}</td>
                                    <td>{{ \Vendor\AdsManager\Helpers\Helper::formatNumber((int)($row->unique_impressions ?? 0)) }}</td>
                                    <td>{{ \Vendor\AdsManager\Helpers\Helper::formatNumber((int)($row->clicks ?? 0)) }}</td>
                                    <td>
                                        <span class="ctr-chip" style="color:{{ \Vendor\AdsManager\Helpers\Helper::getCtrColor((float)($row->ctr ?? 0)) }}">
                                            {{ number_format($row->ctr ?? 0, 2) }}%
                                        </span>
                                    </td>
                                    <td>{{ \Vendor\AdsManager\Helpers\Helper::formatCurrency((float)($row->revenue ?? 0)) }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">Aucune donnée agrégée</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- SIDEBAR --}}
        <div class="col-lg-4">

            {{-- Ad Preview --}}
            <div class="card-modern">
                <div class="card-header-modern">
                    <div class="card-header-icon"><i class="fas fa-eye"></i></div>
                    <h3 class="card-title-modern">Aperçu</h3>
                    <a href="{{ route('ads-manager.ads.preview', $ad->id) }}" target="_blank" class="btn-icon-sm">
                        <i class="fas fa-external-link-alt"></i>
                    </a>
                </div>
                <div class="card-body-modern text-center">
                    @if($ad->type === 'image' && $ad->image_path)
                        <img src="{{ asset('storage/'.$ad->image_path) }}" alt="{{ $ad->titre }}"
                             style="max-width:100%;border-radius:12px;border:1px solid #e2e8f0;">
                    @elseif($ad->type === 'html')
                        <div style="border:1px dashed #e2e8f0;border-radius:12px;padding:12px;overflow:auto;max-height:200px;">
                            {!! $ad->html_content !!}
                        </div>
                    @elseif($ad->type === 'video' && $ad->video_url)
                        <video controls style="max-width:100%;border-radius:12px;" muted>
                            <source src="{{ $ad->video_url }}">
                        </video>
                    @elseif($ad->type === 'text')
                        <div style="padding:16px;background:#f8fafc;border-radius:12px;text-align:left;">
                            <strong>{{ $ad->titre }}</strong>
                            <p style="color:#64748b;margin:8px 0 0;font-size:13px;">{{ $ad->text_content }}</p>
                        </div>
                    @endif
                    <small class="text-muted d-block mt-2">{{ $ad->width }}×{{ $ad->height }} px</small>
                </div>
            </div>

            {{-- Ad Info --}}
            <div class="card-modern">
                <div class="card-header-modern">
                    <div class="card-header-icon"><i class="fas fa-info-circle"></i></div>
                    <h3 class="card-title-modern">Paramètres</h3>
                </div>
                <div class="card-body-modern">
                    <div class="info-list">
                        <div class="info-row"><span>Annonceur</span><strong>{{ $ad->advertiser_name ?? '—' }}</strong></div>
                        <div class="info-row"><span>Type</span><strong>{{ ucfirst($ad->type) }}</strong></div>
                        <div class="info-row"><span>Format</span><strong>{{ config('ads-manager.ad_formats.'.$ad->format.'.label', $ad->format) }}</strong></div>
                        <div class="info-row"><span>Modèle</span><strong>{{ \Vendor\AdsManager\Helpers\Helper::getPricingLabel($ad->pricing_model) }}</strong></div>
                        <div class="info-row"><span>Tarif</span><strong>{{ \Vendor\AdsManager\Helpers\Helper::formatCurrency($ad->rate) }}</strong></div>
                        <div class="info-row"><span>Priorité</span><strong>{{ $ad->priority }}/10</strong></div>
                        <div class="info-row">
                            <span>Période</span>
                            <strong>
                                {{ $ad->start_date ? \Carbon\Carbon::parse($ad->start_date)->format('d/m/Y') : '—' }}
                                →
                                {{ $ad->end_date ? \Carbon\Carbon::parse($ad->end_date)->format('d/m/Y') : '∞' }}
                            </strong>
                        </div>
                        @if($ad->impression_limit)
                        <div class="info-row">
                            <span>Limite impr.</span>
                            <strong>{{ \Vendor\AdsManager\Helpers\Helper::formatNumber($ad->impression_limit) }}</strong>
                        </div>
                        @endif
                        @if($ad->click_limit)
                        <div class="info-row">
                            <span>Limite clics</span>
                            <strong>{{ \Vendor\AdsManager\Helpers\Helper::formatNumber($ad->click_limit) }}</strong>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Performance Score --}}
            <div class="card-modern">
                <div class="card-header-modern">
                    <div class="card-header-icon"><i class="fas fa-star"></i></div>
                    <h3 class="card-title-modern">Score de performance</h3>
                </div>
                <div class="card-body-modern text-center">
                    @php
                        $score = 0;
                        if ($ctr >= 3) $score += 40;
                        elseif ($ctr >= 1) $score += 20;
                        if ($impressions >= 10000) $score += 30;
                        elseif ($impressions >= 1000) $score += 15;
                        if ($fraudClicks === 0) $score += 20;
                        elseif ($fraudClicks < 5) $score += 10;
                        if ($ad->status === 'active') $score += 10;
                        $scoreColor = $score >= 70 ? '#06b48a' : ($score >= 40 ? '#ffb347' : '#ef476f');
                        $scoreLabel = $score >= 70 ? 'Excellente' : ($score >= 40 ? 'Correcte' : 'À améliorer');
                    @endphp
                    <div class="score-ring" style="--score:{{ $score }};--color:{{ $scoreColor }};">
                        <svg viewBox="0 0 100 100" width="120" height="120">
                            <circle cx="50" cy="50" r="42" fill="none" stroke="#eef2f6" stroke-width="8"/>
                            <circle cx="50" cy="50" r="42" fill="none" stroke="{{ $scoreColor }}" stroke-width="8"
                                    stroke-dasharray="{{ round($score * 2.639) }} 264"
                                    stroke-linecap="round"
                                    transform="rotate(-90 50 50)"/>
                        </svg>
                        <div class="score-inner">
                            <div class="score-num" style="color:{{ $scoreColor }}">{{ $score }}</div>
                            <div class="score-max">/100</div>
                        </div>
                    </div>
                    <div class="score-label" style="color:{{ $scoreColor }}">{{ $scoreLabel }}</div>
                    <div class="score-tips mt-3">
                        @if($ctr < 1)
                        <div class="tip tip-warn"><i class="fas fa-lightbulb"></i> Améliorez le visuel pour augmenter le CTR</div>
                        @endif
                        @if($fraudClicks > 5)
                        <div class="tip tip-danger"><i class="fas fa-shield-alt"></i> Fraude détectée — vérifiez les sources</div>
                        @endif
                        @if($impressions < 1000)
                        <div class="tip tip-info"><i class="fas fa-info-circle"></i> Ajoutez plus d'emplacements pour augmenter la portée</div>
                        @endif
                        @if($score >= 70)
                        <div class="tip tip-success"><i class="fas fa-check-circle"></i> Très bonne performance — continuez ainsi !</div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Quick Actions --}}
            <div class="card-modern">
                <div class="card-header-modern">
                    <div class="card-header-icon"><i class="fas fa-bolt"></i></div>
                    <h3 class="card-title-modern">Actions rapides</h3>
                </div>
                <div class="card-body-modern d-flex flex-column gap-2">
                    <a href="{{ route('ads-manager.ads.show', $ad->id) }}" class="btn-action-full stats">
                        <i class="fas fa-eye"></i> Voir le détail de l'annonce
                    </a>
                    @if(in_array($ad->status, ['draft','paused','pending']))
                    <a href="{{ route('ads-manager.ads.edit', $ad->id) }}" class="btn-action-full edit-a">
                        <i class="fas fa-edit"></i> Modifier l'annonce
                    </a>
                    @endif
                    <form method="POST" action="{{ route('ads-manager.ads.duplicate', $ad->id) }}">
                        @csrf
                        <button class="btn-action-full duplicate" type="submit">
                            <i class="fas fa-copy"></i> Dupliquer
                        </button>
                    </form>
                    <a href="{{ route('ads-manager.reports.index') }}" class="btn-action-full neutral">
                        <i class="fas fa-chart-bar"></i> Tous les rapports
                    </a>
                </div>
            </div>
        </div>

    </div>
</main>

@if(!empty($timeline))
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
const timeline = @json($timeline);
const labels   = timeline.map(d => {
    const dt = new Date(d.report_date);
    return dt.toLocaleDateString('fr-FR', { day:'2-digit', month:'short' });
});
const imprData    = timeline.map(d => d.impressions || 0);
const clickData   = timeline.map(d => d.clicks || 0);
const revenueData = timeline.map(d => parseFloat(d.revenue) || 0);
const ctrData     = timeline.map(d => parseFloat(d.ctr) || 0);

// Main chart
new Chart(document.getElementById('timelineChart'), {
    type: 'line',
    data: {
        labels,
        datasets: [
            {
                label: 'Impressions',
                data: imprData,
                borderColor: '#667eea',
                backgroundColor: 'rgba(102,126,234,.08)',
                tension: .4, fill: true, yAxisID: 'y',
                pointRadius: 3, pointHoverRadius: 6,
            },
            {
                label: 'Clics',
                data: clickData,
                borderColor: '#06b48a',
                backgroundColor: 'rgba(6,180,138,.08)',
                tension: .4, fill: true, yAxisID: 'y',
                pointRadius: 3, pointHoverRadius: 6,
            },
            {
                label: 'Revenus ($)',
                data: revenueData,
                borderColor: '#ffb347',
                backgroundColor: 'rgba(255,179,71,.08)',
                tension: .4, fill: false, yAxisID: 'y2',
                borderDash: [5,3],
                pointRadius: 3, pointHoverRadius: 6,
            },
        ]
    },
    options: {
        responsive: true,
        interaction: { mode: 'index', intersect: false },
        plugins: {
            legend: { position: 'top', labels: { usePointStyle: true, padding: 16 } },
            tooltip: { callbacks: { label: ctx => {
                if (ctx.datasetIndex === 2) return ' $' + ctx.raw.toFixed(3);
                return ' ' + ctx.raw.toLocaleString('fr-FR');
            }}}
        },
        scales: {
            x: { grid: { color: '#eef2f6' } },
            y: { beginAtZero: true, grid: { color: '#eef2f6' }, position: 'left' },
            y2: { beginAtZero: true, grid: { display: false }, position: 'right' }
        }
    }
});

// CTR chart
new Chart(document.getElementById('ctrChart'), {
    type: 'bar',
    data: {
        labels,
        datasets: [{
            label: 'CTR %',
            data: ctrData,
            backgroundColor: ctrData.map(v => v >= 3 ? 'rgba(6,180,138,.7)' : v >= 1 ? 'rgba(255,179,71,.7)' : 'rgba(239,71,111,.7)'),
            borderRadius: 6,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            x: { grid: { display: false } },
            y: { beginAtZero: true, ticks: { callback: v => v + '%' } }
        }
    }
});
</script>
@endif

<style>
.page-header-modern{background:linear-gradient(135deg,#667eea,#764ba2);border-radius:24px;padding:28px 32px;margin-bottom:32px;box-shadow:0 10px 25px -5px rgba(0,0,0,.1);}
.page-header-content{display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:16px;}
.page-header-left{display:flex;align-items:center;gap:20px;}
.page-header-icon{width:60px;height:60px;background:rgba(255,255,255,.2);border-radius:20px;display:flex;align-items:center;justify-content:center;color:#fff;font-size:28px;}
.page-title-modern{font-size:26px;font-weight:700;color:#fff;margin:0;}
.page-subtitle-modern{color:rgba(255,255,255,.8);margin:5px 0 0;font-size:14px;}
.btn-back{background:rgba(255,255,255,.2);color:#fff;padding:10px 18px;border-radius:12px;text-decoration:none;font-size:13px;font-weight:500;display:inline-flex;align-items:center;}
.btn-back:hover{background:rgba(255,255,255,.3);color:#fff;}

/* KPI Grid */
.kpi-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:16px;margin-bottom:24px;}
.kpi-card-big{background:#fff;border-radius:20px;padding:20px;box-shadow:0 4px 16px rgba(0,0,0,.05);}
.kpi-top{display:flex;justify-content:space-between;align-items:center;margin-bottom:12px;}
.kpi-icon-wrap{width:42px;height:42px;border-radius:14px;display:flex;align-items:center;justify-content:center;font-size:16px;}
.kpi-delta{font-size:11px;font-weight:600;padding:3px 8px;border-radius:20px;}
.kpi-delta.up{background:#e8f5e9;color:#06b48a;}
.kpi-delta.neutral{background:#f1f5f9;color:#64748b;}
.kpi-num{font-size:28px;font-weight:700;color:#1e293b;}
.kpi-lbl{font-size:13px;color:#64748b;margin-top:2px;}
.kpi-sub{font-size:11px;color:#94a3b8;margin-top:4px;}
.ctr-pill{font-size:11px;font-weight:600;padding:3px 8px;border-radius:20px;}

/* Budget */
.budget-card{background:#fff;border-radius:20px;padding:20px 24px;box-shadow:0 4px 16px rgba(0,0,0,.05);margin-bottom:24px;}
.budget-header{display:flex;justify-content:space-between;font-size:14px;font-weight:600;color:#1e293b;margin-bottom:12px;}
.budget-track{height:12px;background:#eef2f6;border-radius:8px;overflow:hidden;}
.budget-fill{height:100%;border-radius:8px;display:flex;align-items:center;justify-content:flex-end;padding-right:6px;font-size:9px;font-weight:700;color:#fff;min-width:30px;transition:width .6s;}
.budget-fill.ok{background:linear-gradient(90deg,#06b48a,#049a72);}
.budget-fill.warning{background:linear-gradient(90deg,#ffd166,#ffb347);}
.budget-fill.danger{background:linear-gradient(90deg,#ef476f,#d4335f);}
.budget-alert{margin-top:8px;font-size:12px;color:#ef476f;font-weight:600;}

/* Cards */
.card-modern{background:#fff;border-radius:24px;box-shadow:0 4px 20px rgba(0,0,0,.05);margin-bottom:24px;overflow:hidden;}
.card-header-modern{display:flex;align-items:center;gap:12px;padding:20px 24px;border-bottom:1px solid #eef2f6;background:#fafbfc;flex-wrap:wrap;}
.card-header-icon{width:36px;height:36px;background:linear-gradient(135deg,#667eea,#764ba2);border-radius:12px;display:flex;align-items:center;justify-content:center;color:#fff;font-size:16px;flex-shrink:0;}
.card-title-modern{font-size:18px;font-weight:600;color:#1e293b;margin:0;flex:1;}
.card-badge{background:#eef2ff;color:#667eea;padding:4px 10px;border-radius:20px;font-size:12px;}
.card-body-modern{padding:24px;}
.btn-icon-sm{width:32px;height:32px;border-radius:10px;background:#eef2ff;color:#667eea;display:flex;align-items:center;justify-content:center;text-decoration:none;font-size:13px;}
.btn-icon-sm:hover{background:#667eea;color:#fff;}
.chart-legend{display:flex;align-items:center;gap:12px;font-size:12px;color:#64748b;}
.legend-dot{width:10px;height:10px;border-radius:50%;display:inline-block;margin-right:4px;}

/* Table */
.table-scroll{overflow-x:auto;}
.modern-table{width:100%;border-collapse:collapse;}
.modern-table thead th{padding:10px 16px;font-size:11px;font-weight:600;color:#64748b;text-transform:uppercase;border-bottom:2px solid #eef2f6;white-space:nowrap;}
.modern-table tbody td{padding:12px 16px;border-bottom:1px solid #f1f5f9;vertical-align:middle;font-size:13px;}
.modern-table tbody tr:hover{background:#fafbfc;}
.ctr-chip{font-weight:700;font-size:13px;}

/* Score ring */
.score-ring{position:relative;display:inline-flex;align-items:center;justify-content:center;}
.score-inner{position:absolute;text-align:center;}
.score-num{font-size:26px;font-weight:800;}
.score-max{font-size:11px;color:#94a3b8;}
.score-label{font-size:16px;font-weight:700;margin-top:8px;}
.score-tips{display:flex;flex-direction:column;gap:6px;text-align:left;}
.tip{font-size:12px;padding:8px 10px;border-radius:10px;display:flex;align-items:center;gap:8px;}
.tip.tip-warn{background:#fff3e0;color:#ff8c00;}
.tip.tip-danger{background:#ffebee;color:#ef476f;}
.tip.tip-info{background:#eef2ff;color:#667eea;}
.tip.tip-success{background:#e8f5e9;color:#06b48a;}

/* Info list */
.info-list{display:flex;flex-direction:column;}
.info-row{display:flex;justify-content:space-between;padding:9px 0;border-bottom:1px solid #f1f5f9;font-size:13px;}
.info-row span{color:#64748b;}

/* Action buttons */
.btn-action-full{width:100%;padding:11px 16px;border:none;border-radius:12px;font-weight:500;font-size:14px;cursor:pointer;display:flex;align-items:center;gap:10px;text-decoration:none;transition:all .2s;}
.btn-action-full.stats{background:#eef2ff;color:#667eea;}
.btn-action-full.edit-a{background:#fff3e0;color:#ffb347;}
.btn-action-full.duplicate{background:#f3e8ff;color:#9b59b6;}
.btn-action-full.neutral{background:#f1f5f9;color:#475569;}
.btn-action-full:hover{opacity:.85;transform:translateY(-1px);}

/* Empty chart */
.empty-chart{text-align:center;padding:60px 20px;color:#94a3b8;}
.empty-chart i{font-size:48px;margin-bottom:12px;display:block;}
.empty-chart p{font-size:16px;font-weight:500;color:#64748b;margin:0;}
.empty-chart small{font-size:12px;}

@media(max-width:768px){
    .kpi-grid{grid-template-columns:1fr 1fr;}
    .page-header-content{flex-direction:column;}
}
@media(max-width:480px){.kpi-grid{grid-template-columns:1fr;}}
</style>
@endsection