<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aperçu — {{ $ad->titre }}</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: #f1f5f9;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }
        .preview-wrapper {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,.12);
            overflow: hidden;
            max-width: 800px;
            width: 100%;
        }
        .preview-toolbar {
            background: linear-gradient(135deg, #667eea, #764ba2);
            padding: 16px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .preview-toolbar h1 {
            font-size: 16px;
            font-weight: 600;
            color: #fff;
            margin: 0;
        }
        .toolbar-meta {
            display: flex;
            gap: 10px;
            align-items: center;
        }
        .toolbar-badge {
            background: rgba(255,255,255,.2);
            color: #fff;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }
        .preview-stage {
            padding: 40px;
            text-align: center;
            background: #f8fafc;
        }
        .ad-container {
            display: inline-block;
            position: relative;
            border: 2px dashed #e2e8f0;
            border-radius: 12px;
            padding: 4px;
            background: #fff;
        }
        .ad-label {
            position: absolute;
            top: -10px;
            left: 50%;
            transform: translateX(-50%);
            background: #667eea;
            color: #fff;
            font-size: 10px;
            font-weight: 600;
            padding: 2px 10px;
            border-radius: 20px;
            white-space: nowrap;
            z-index: 10;
        }
        .ad-image { border-radius: 8px; display: block; max-width: 100%; }
        .ad-video { border-radius: 8px; max-width: 100%; }
        .ad-html { border-radius: 8px; overflow: auto; }
        .ad-text {
            padding: 20px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            text-align: left;
        }
        .ad-text strong { font-size: 16px; color: #1e293b; display: block; margin-bottom: 8px; }
        .ad-text p { font-size: 13px; color: #64748b; }
        .preview-info {
            padding: 24px;
            border-top: 1px solid #eef2f6;
        }
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
            gap: 16px;
            margin-bottom: 20px;
        }
        .info-chip {
            background: #f8fafc;
            border-radius: 12px;
            padding: 12px;
            text-align: center;
        }
        .info-chip-value { font-size: 15px; font-weight: 700; color: #1e293b; }
        .info-chip-label { font-size: 11px; color: #94a3b8; margin-top: 4px; }
        .preview-actions {
            display: flex;
            gap: 10px;
            justify-content: center;
            flex-wrap: wrap;
        }
        .btn-preview {
            padding: 10px 20px;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .btn-preview.primary { background: linear-gradient(135deg, #667eea, #764ba2); color: #fff; }
        .btn-preview.secondary { background: #f1f5f9; color: #475569; }
        .btn-preview:hover { opacity: .9; transform: translateY(-1px); }
        .dim-label {
            font-size: 12px;
            color: #94a3b8;
            margin-top: 12px;
        }
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        .status-active   { background: #e8f5e9; color: #06b48a; }
        .status-pending  { background: #fff3e0; color: #ffb347; }
        .status-draft    { background: #f1f5f9; color: #64748b; }
        .status-paused   { background: #e0f7fa; color: #00acc1; }
        .status-expired  { background: #f3e8ff; color: #9b59b6; }
        .status-rejected { background: #ffebee; color: #ef476f; }
        .destination-link {
            font-size: 13px;
            color: #667eea;
            word-break: break-all;
            display: flex;
            align-items: center;
            gap: 6px;
            justify-content: center;
            margin-top: 8px;
        }
    </style>
</head>
<body>

<div class="preview-wrapper">

    {{-- Toolbar --}}
    <div class="preview-toolbar">
        <h1>🔍 Aperçu de l'annonce</h1>
        <div class="toolbar-meta">
            <span class="toolbar-badge">{{ $ad->width }}×{{ $ad->height }}px</span>
            <span class="toolbar-badge">{{ strtoupper($ad->type) }}</span>
            <span class="toolbar-badge">{{ strtoupper($ad->format) }}</span>
        </div>
    </div>

    {{-- Stage --}}
    <div class="preview-stage">
        <div class="ad-container" style="width:{{ min($ad->width ?? 300, 600) }}px;">
            <div class="ad-label">Publicité</div>

            @php
                $w = min($ad->width ?? 300, 600);
                $h = $ad->height ? round($w * ($ad->height / ($ad->width ?: 1))) : 250;
            @endphp

            @if($ad->type === 'image' && $ad->image_path)
                <img class="ad-image"
                     src="{{ asset('storage/'.$ad->image_path) }}"
                     alt="{{ $ad->titre }}"
                     style="width:{{ $w }}px;height:{{ $h }}px;object-fit:cover;">

            @elseif($ad->type === 'html' && $ad->html_content)
                <div class="ad-html" style="width:{{ $w }}px;min-height:{{ $h }}px;padding:8px;">
                    {!! $ad->html_content !!}
                </div>

            @elseif($ad->type === 'video' && $ad->video_url)
                <video class="ad-video" controls autoplay muted loop
                       style="width:{{ $w }}px;height:{{ $h }}px;object-fit:cover;">
                    <source src="{{ $ad->video_url }}">
                    Votre navigateur ne supporte pas la vidéo.
                </video>

            @elseif($ad->type === 'text')
                <div class="ad-text" style="width:{{ $w }}px;">
                    <strong>{{ $ad->titre }}</strong>
                    @if($ad->text_content)
                    <p>{{ $ad->text_content }}</p>
                    @endif
                </div>

            @else
                <div style="width:{{ $w }}px;height:{{ $h }}px;background:linear-gradient(135deg,#667eea30,#764ba230);display:flex;align-items:center;justify-content:center;border-radius:8px;color:#667eea;font-size:14px;font-weight:500;">
                    Aucun contenu disponible
                </div>
            @endif
        </div>

        <div class="dim-label">{{ $ad->width }}×{{ $ad->height }} pixels • Format {{ $ad->format }}</div>

        @if($ad->destination_url)
        <div class="destination-link">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M18 13v6a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h6M15 3h6v6M10 14L21 3"/>
            </svg>
            {{ $ad->destination_url }}
        </div>
        @endif
    </div>

    {{-- Info --}}
    <div class="preview-info">
        <div class="info-grid">
            <div class="info-chip">
                <div class="info-chip-value">
                    <span class="status-badge status-{{ $ad->status }}">
                        {{ config('ads-manager.ad_statuses.'.$ad->status, ucfirst($ad->status)) }}
                    </span>
                </div>
                <div class="info-chip-label">Statut</div>
            </div>
            <div class="info-chip">
                <div class="info-chip-value">{{ \Vendor\AdsManager\Helpers\Helper::getPricingLabel($ad->pricing_model) }}</div>
                <div class="info-chip-label">Modèle de tarif</div>
            </div>
            <div class="info-chip">
                <div class="info-chip-value">{{ \Vendor\AdsManager\Helpers\Helper::formatCurrency($ad->rate) }}</div>
                <div class="info-chip-label">Tarif unitaire</div>
            </div>
            <div class="info-chip">
                <div class="info-chip-value">{{ $ad->priority }}/10</div>
                <div class="info-chip-label">Priorité</div>
            </div>
            @if($ad->start_date || $ad->end_date)
            <div class="info-chip">
                <div class="info-chip-value" style="font-size:13px;">
                    {{ $ad->start_date ? \Carbon\Carbon::parse($ad->start_date)->format('d/m/Y') : '—' }}
                    →
                    {{ $ad->end_date ? \Carbon\Carbon::parse($ad->end_date)->format('d/m/Y') : '∞' }}
                </div>
                <div class="info-chip-label">Période</div>
            </div>
            @endif
            @if($ad->advertiser_name)
            <div class="info-chip">
                <div class="info-chip-value" style="font-size:13px;">{{ $ad->advertiser_name }}</div>
                <div class="info-chip-label">Annonceur</div>
            </div>
            @endif
        </div>

        <div class="preview-actions">
            <a href="{{ route('ads-manager.ads.show', $ad->id) }}" class="btn-preview primary">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path d="M2 12s3-9 10-9 10 9 10 9-3 9-10 9-10-9-10-9z"/>
                </svg>
                Voir le détail
            </a>
            @if(in_array($ad->status, ['draft','paused','pending']))
            <a href="{{ route('ads-manager.ads.edit', $ad->id) }}" class="btn-preview secondary">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                </svg>
                Modifier
            </a>
            @endif
            <button class="btn-preview secondary" onclick="window.print()">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M6 9V2h12v7M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2M6 14h12v8H6v-8z"/>
                </svg>
                Imprimer
            </button>
            <button class="btn-preview secondary" onclick="window.close()">Fermer</button>
        </div>
    </div>
</div>

</body>
</html>