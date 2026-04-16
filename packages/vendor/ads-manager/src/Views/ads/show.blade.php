@extends('layouts.app')

@section('content')
<main class="dashboard-content">

    <div class="page-header-modern">
        <div class="page-header-content">
            <div class="page-header-left">
                <div class="page-header-icon"><i class="fas fa-ad"></i></div>
                <div>
                    <h1 class="page-title-modern">{{ \Vendor\AdsManager\Helpers\Helper::truncate($ad->titre, 50) }}</h1>
                    <p class="page-subtitle-modern">
                        {!! \Vendor\AdsManager\Helpers\Helper::getStatusBadge($ad->status) !!}
                        &nbsp;•&nbsp;
                        {{ \Vendor\AdsManager\Helpers\Helper::getPricingLabel($ad->pricing_model) }}
                        &nbsp;•&nbsp;
                        {{ config('ads-manager.ad_formats.'.$ad->format.'.label', $ad->format) }}
                    </p>
                </div>
            </div>
            <div class="d-flex gap-2">
                @if(in_array($ad->status, ['draft','paused','pending']))
                    <a href="{{ route('ads-manager.ads.edit', $ad->id) }}" class="btn-action edit">
                        <i class="fas fa-edit"></i> Modifier
                    </a>
                @endif
                @if($ad->status === 'pending')
                    <form method="POST" action="{{ route('ads-manager.ads.approve', $ad->id) }}">
                        @csrf
                        <button class="btn-action approve"><i class="fas fa-check"></i> Approuver</button>
                    </form>
                @endif
                @if($ad->status === 'active')
                    <form method="POST" action="{{ route('ads-manager.ads.pause', $ad->id) }}">
                        @csrf
                        <button class="btn-action pause"><i class="fas fa-pause"></i> Pause</button>
                    </form>
                @endif
                @if($ad->status === 'paused')
                    <form method="POST" action="{{ route('ads-manager.ads.activate', $ad->id) }}">
                        @csrf
                        <button class="btn-action approve"><i class="fas fa-play"></i> Activer</button>
                    </form>
                @endif
                <a href="{{ route('ads-manager.ads.index') }}" class="btn-back">
                    <i class="fas fa-arrow-left me-2"></i>Retour
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

    <div class="row g-4">

        {{-- =========================================================== --}}
        {{-- COLONNE GAUCHE                                                --}}
        {{-- =========================================================== --}}
        <div class="col-lg-8">

            {{-- Stats KPI --}}
            <div class="row g-3 mb-4">
                @php
                    $impressions = $stats['impressions'] ?? 0;
                    $clicks      = $stats['clicks'] ?? 0;
                    $ctr         = $stats['ctr'] ?? 0;
                    $revenue     = $stats['revenue'] ?? 0;
                @endphp
                <div class="col-6 col-md-3">
                    <div class="kpi-card">
                        <div class="kpi-icon" style="background:#eef2ff;color:#667eea;"><i class="fas fa-eye"></i></div>
                        <div class="kpi-value">{{ \Vendor\AdsManager\Helpers\Helper::formatNumber($impressions) }}</div>
                        <div class="kpi-label">Impressions</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="kpi-card">
                        <div class="kpi-icon" style="background:#fff3e0;color:#ffb347;"><i class="fas fa-mouse-pointer"></i></div>
                        <div class="kpi-value">{{ \Vendor\AdsManager\Helpers\Helper::formatNumber($clicks) }}</div>
                        <div class="kpi-label">Clics</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="kpi-card">
                        <div class="kpi-icon" style="background:#e8f5e9;color:#06b48a;"><i class="fas fa-percent"></i></div>
                        <div class="kpi-value" style="color:{{ \Vendor\AdsManager\Helpers\Helper::getCtrColor($ctr) }}">{{ $ctr }}%</div>
                        <div class="kpi-label">CTR</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="kpi-card">
                        <div class="kpi-icon" style="background:#f3e8ff;color:#9b59b6;"><i class="fas fa-coins"></i></div>
                        <div class="kpi-value">{{ \Vendor\AdsManager\Helpers\Helper::formatCurrency($revenue) }}</div>
                        <div class="kpi-label">Revenus</div>
                    </div>
                </div>
            </div>

            {{-- Timeline chart --}}
            @if(!empty($stats['timeline']))
            <div class="card-modern mb-4">
                <div class="card-header-modern">
                    <div class="card-header-icon"><i class="fas fa-chart-line"></i></div>
                    <h3 class="card-title-modern">Performance (30 derniers jours)</h3>
                </div>
                <div class="card-body-modern">
                    <canvas id="timelineChart" height="120"></canvas>
                </div>
            </div>
            @endif

            {{-- Emplacements --}}
            <div class="card-modern">
                <div class="card-header-modern">
                    <div class="card-header-icon"><i class="fas fa-map-marker-alt"></i></div>
                    <h3 class="card-title-modern">Emplacements diffusés</h3>
                    <span class="card-badge">{{ $placements->count() }}</span>
                </div>
                <div class="card-body-modern p-0">
                    <table class="modern-table">
                        <thead>
                            <tr>
                                <th>Emplacement</th>
                                <th>Position</th>
                                <th>Format</th>
                                <th>Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($placements as $p)
                            <tr>
                                <td><strong>{{ $p->nom }}</strong><br><small class="text-muted">{{ $p->code }}</small></td>
                                <td>{{ config('ads-manager.placement_positions.'.$p->position, $p->position) }}</td>
                                <td>{{ $p->format }}</td>
                                <td>
                                    @if($p->linked_active)
                                        <span class="badge bg-success">Actif</span>
                                    @else
                                        <span class="badge bg-secondary">Inactif</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="text-center py-3 text-muted">Aucun emplacement associé</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Rejection reason --}}
            @if($ad->status === 'rejected' && $ad->rejection_reason)
            <div class="alert alert-danger">
                <i class="fas fa-times-circle me-2"></i>
                <strong>Motif de rejet :</strong> {{ $ad->rejection_reason }}
            </div>
            @endif

            {{-- Reject form (if pending) --}}
            @if($ad->status === 'pending')
            <div class="card-modern">
                <div class="card-header-modern">
                    <div class="card-header-icon" style="background:linear-gradient(135deg,#ef476f,#d4335f);">
                        <i class="fas fa-times-circle"></i>
                    </div>
                    <h3 class="card-title-modern">Rejeter l'annonce</h3>
                </div>
                <div class="card-body-modern">
                    <form method="POST" action="{{ route('ads-manager.ads.reject', $ad->id) }}">
                        @csrf
                        <div class="form-group-modern">
                            <label class="form-label-modern required">Motif de rejet</label>
                            <textarea name="reason" class="form-control-modern" rows="3" required
                                      placeholder="Expliquez pourquoi cette annonce est rejetée…"></textarea>
                        </div>
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-times me-2"></i>Rejeter l'annonce
                        </button>
                    </form>
                </div>
            </div>
            @endif

        </div>

        {{-- =========================================================== --}}
        {{-- COLONNE DROITE                                                --}}
        {{-- =========================================================== --}}
        <div class="col-lg-4">

            {{-- Aperçu --}}
            <div class="card-modern">
                <div class="card-header-modern">
                    <div class="card-header-icon"><i class="fas fa-eye"></i></div>
                    <h3 class="card-title-modern">Aperçu de l'annonce</h3>
                    <a href="{{ route('ads-manager.ads.preview', $ad->id) }}" target="_blank" class="btn-refresh">
                        <i class="fas fa-external-link-alt"></i>
                    </a>
                </div>
                <div class="card-body-modern text-center">
                    @if($ad->type === 'image' && $ad->image_path)
                        <img src="{{ asset('storage/'.$ad->image_path) }}" alt="{{ $ad->titre }}"
                             style="max-width:100%;border-radius:12px;border:1px solid #e2e8f0;">
                    @elseif($ad->type === 'html')
                        <div style="max-width:100%;overflow:auto;border-radius:12px;border:1px dashed #e2e8f0;padding:12px;">
                            {!! $ad->html_content !!}
                        </div>
                    @elseif($ad->type === 'video' && $ad->video_url)
                        <video controls style="max-width:100%;border-radius:12px;">
                            <source src="{{ $ad->video_url }}">
                        </video>
                    @elseif($ad->type === 'text')
                        <div style="padding:16px;background:#f8fafc;border-radius:12px;text-align:left;">
                            <strong>{{ $ad->titre }}</strong>
                            <p style="color:#64748b;margin:8px 0 0;font-size:13px;">{{ $ad->text_content }}</p>
                        </div>
                    @else
                        <div style="height:100px;background:#f1f5f9;border-radius:12px;display:flex;align-items:center;justify-content:center;color:#94a3b8;">
                            <i class="fas fa-image fa-2x"></i>
                        </div>
                    @endif
                    <small class="text-muted d-block mt-2">{{ $ad->width }}×{{ $ad->height }} px</small>
                </div>
            </div>

            {{-- Informations --}}
            <div class="card-modern">
                <div class="card-header-modern">
                    <div class="card-header-icon"><i class="fas fa-info-circle"></i></div>
                    <h3 class="card-title-modern">Détails</h3>
                </div>
                <div class="card-body-modern">
                    <div class="info-list">
                        <div class="info-row"><span>Annonceur</span><strong>{{ $ad->advertiser_name ?? '—' }}</strong></div>
                        <div class="info-row"><span>Modèle tarif</span><strong>{{ \Vendor\AdsManager\Helpers\Helper::getPricingLabel($ad->pricing_model) }}</strong></div>
                        <div class="info-row"><span>Tarif unitaire</span><strong>{{ \Vendor\AdsManager\Helpers\Helper::formatCurrency($ad->rate) }}</strong></div>
                        @if($ad->budget_total)
                        <div class="info-row">
                            <span>Budget</span>
                            <strong>{{ \Vendor\AdsManager\Helpers\Helper::formatCurrency($ad->budget_spent) }} / {{ \Vendor\AdsManager\Helpers\Helper::formatCurrency($ad->budget_total) }}</strong>
                        </div>
                        @endif
                        <div class="info-row"><span>Priorité</span><strong>{{ $ad->priority }}/10</strong></div>
                        <div class="info-row">
                            <span>Période</span>
                            <strong>
                                {{ $ad->start_date ? \Carbon\Carbon::parse($ad->start_date)->format('d/m/Y') : '—' }}
                                →
                                {{ $ad->end_date ? \Carbon\Carbon::parse($ad->end_date)->format('d/m/Y') : '∞' }}
                            </strong>
                        </div>
                        <div class="info-row"><span>Créée le</span><strong>{{ \Carbon\Carbon::parse($ad->created_at)->format('d/m/Y H:i') }}</strong></div>
                        @if($ad->approved_at)
                        <div class="info-row"><span>Approuvée le</span><strong>{{ \Carbon\Carbon::parse($ad->approved_at)->format('d/m/Y H:i') }}</strong></div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="card-modern">
                <div class="card-header-modern">
                    <div class="card-header-icon"><i class="fas fa-cog"></i></div>
                    <h3 class="card-title-modern">Actions</h3>
                </div>
                <div class="card-body-modern">
                    <div class="d-flex flex-column gap-2">
                        <a href="{{ route('ads-manager.reports.ad', $ad->id) }}" class="btn-action-full stats">
                            <i class="fas fa-chart-bar"></i> Voir les statistiques complètes
                        </a>
                        <form method="POST" action="{{ route('ads-manager.ads.duplicate', $ad->id) }}">
                            @csrf
                            <button class="btn-action-full duplicate" type="submit">
                                <i class="fas fa-copy"></i> Dupliquer cette annonce
                            </button>
                        </form>
                        @if($ad->status !== 'active')
                        <form method="POST" action="{{ route('ads-manager.ads.destroy', $ad->id) }}">
                            @csrf @method('DELETE')
                            <button class="btn-action-full delete" type="submit"
                                    onclick="return confirm('Supprimer définitivement cette annonce ?')">
                                <i class="fas fa-trash"></i> Supprimer
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>
</main>

@if(!empty($stats['timeline']))
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
const timeline = @json($stats['timeline']);
const labels   = timeline.map(d => d.report_date);
const imprData = timeline.map(d => d.impressions);
const clickData= timeline.map(d => d.clicks);

new Chart(document.getElementById('timelineChart'), {
    type: 'line',
    data: {
        labels,
        datasets: [
            {
                label: 'Impressions',
                data: imprData,
                borderColor: '#667eea',
                backgroundColor: 'rgba(102,126,234,.1)',
                tension: .4, fill: true,
            },
            {
                label: 'Clics',
                data: clickData,
                borderColor: '#06b48a',
                backgroundColor: 'rgba(6,180,138,.1)',
                tension: .4, fill: true,
            },
        ]
    },
    options: {
        responsive: true,
        plugins: { legend: { position: 'top' } },
        scales: { y: { beginAtZero: true } }
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
.btn-action{padding:9px 18px;border:none;border-radius:12px;font-size:13px;font-weight:600;cursor:pointer;display:inline-flex;align-items:center;gap:8px;text-decoration:none;}
.btn-action.edit    {background:#ffd166;color:#2d3748;}
.btn-action.approve {background:#06b48a;color:#fff;}
.btn-action.pause   {background:#ffd166;color:#2d3748;}

.kpi-card{background:#fff;border-radius:20px;padding:20px;box-shadow:0 4px 16px rgba(0,0,0,.05);text-align:center;}
.kpi-icon{width:44px;height:44px;border-radius:14px;display:flex;align-items:center;justify-content:center;margin:0 auto 10px;font-size:18px;}
.kpi-value{font-size:24px;font-weight:700;color:#1e293b;}
.kpi-label{font-size:12px;color:#64748b;margin-top:2px;}

.card-modern{background:#fff;border-radius:24px;box-shadow:0 4px 20px rgba(0,0,0,.05);margin-bottom:24px;overflow:hidden;}
.card-header-modern{display:flex;align-items:center;gap:12px;padding:20px 24px;border-bottom:1px solid #eef2f6;background:#fafbfc;}
.card-header-icon{width:36px;height:36px;background:linear-gradient(135deg,#667eea,#764ba2);border-radius:12px;display:flex;align-items:center;justify-content:center;color:#fff;font-size:16px;}
.card-title-modern{font-size:18px;font-weight:600;color:#1e293b;margin:0;flex:1;}
.card-badge{background:#eef2ff;color:#667eea;padding:4px 10px;border-radius:20px;font-size:12px;}
.card-body-modern{padding:24px;}
.btn-refresh{background:none;border:none;color:#667eea;cursor:pointer;font-size:15px;}

.modern-table{width:100%;border-collapse:collapse;}
.modern-table thead th{padding:10px 16px;font-size:12px;font-weight:600;color:#64748b;text-transform:uppercase;border-bottom:2px solid #eef2f6;}
.modern-table tbody td{padding:12px 16px;border-bottom:1px solid #f1f5f9;vertical-align:middle;}

.info-list{display:flex;flex-direction:column;gap:0;}
.info-row{display:flex;justify-content:space-between;padding:10px 0;border-bottom:1px solid #f1f5f9;font-size:13px;}
.info-row span{color:#64748b;}

.btn-action-full{width:100%;padding:11px 16px;border:none;border-radius:12px;font-weight:500;font-size:14px;cursor:pointer;display:flex;align-items:center;gap:10px;text-decoration:none;transition:all .2s;}
.btn-action-full.stats    {background:#eef2ff;color:#667eea;}
.btn-action-full.duplicate{background:#f3e8ff;color:#9b59b6;}
.btn-action-full.delete   {background:#ffebee;color:#ef476f;}
.btn-action-full:hover{opacity:.85;transform:translateY(-1px);}

.form-group-modern{margin-bottom:16px;}
.form-label-modern{display:flex;align-items:center;gap:8px;font-weight:600;color:#1e293b;margin-bottom:6px;font-size:14px;}
.form-label-modern.required:after{content:"*";color:#ef476f;margin-left:4px;}
.form-control-modern{width:100%;padding:11px 14px;border:1px solid #e2e8f0;border-radius:12px;font-size:14px;}
.form-control-modern:focus{border-color:#667eea;outline:none;box-shadow:0 0 0 3px rgba(102,126,234,.1);}
</style>
@endsection