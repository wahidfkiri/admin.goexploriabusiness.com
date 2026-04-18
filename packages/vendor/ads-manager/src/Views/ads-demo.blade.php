{{--
=====================================================================
 DEMO PAGE — Comment afficher des publicités
 Copiez cette page dans resources/views/ de votre application
 et accédez-y via une route de test.
=====================================================================
--}}
@extends('layouts.app')

@section('content')
<main class="dashboard-content" style="max-width:1000px;margin:0 auto;padding:24px;">

    {{-- ============================================================
         HEADER
         ============================================================ --}}
    <div style="background:linear-gradient(135deg,#667eea,#764ba2);border-radius:20px;padding:28px;margin-bottom:28px;color:#fff;">
        <h1 style="margin:0;font-size:24px;font-weight:700;">🎯 Guide d'affichage des publicités</h1>
        <p style="margin:8px 0 0;opacity:.8;">3 méthodes disponibles selon votre contexte</p>
    </div>

    @if(session('info'))
        <div class="alert alert-info">{{ session('info') }}</div>
    @endif

    {{-- ============================================================
         PRÉREQUIS
         ============================================================ --}}
    <div style="background:#fffbeb;border:1px solid #fde68a;border-radius:16px;padding:20px;margin-bottom:24px;">
        <h3 style="margin:0 0 10px;color:#92400e;font-size:15px;"><i class="fas fa-exclamation-triangle"></i> Prérequis obligatoire</h3>
        <p style="margin:0;font-size:13px;color:#78350f;">
            Ajoutez dans votre <code>&lt;head&gt;</code> avant tout usage :
        </p>
        <div style="background:#1e293b;border-radius:10px;padding:12px;margin-top:10px;font-family:monospace;font-size:13px;color:#fcd34d;">
            &lt;meta name="csrf-token" content="{{ csrf_token() }}"&gt;
        </div>
        <p style="margin:8px 0 0;font-size:12px;color:#92400e;">
            ⚠️ Sans cette balise, le tracking des clics ne fonctionnera pas.
        </p>
    </div>

    {{-- ============================================================
         MÉTHODE 1 — BLADE DIRECTIVE (pages Laravel internes)
         ============================================================ --}}
    <div style="background:#fff;border-radius:20px;box-shadow:0 4px 20px rgba(0,0,0,.06);margin-bottom:24px;overflow:hidden;">
        <div style="background:#eef2ff;padding:18px 24px;display:flex;align-items:center;gap:12px;border-bottom:1px solid #e0e7ff;">
            <div style="width:36px;height:36px;background:linear-gradient(135deg,#667eea,#764ba2);border-radius:10px;display:flex;align-items:center;justify-content:center;color:#fff;font-size:16px;">1</div>
            <div>
                <h2 style="margin:0;font-size:16px;color:#1e293b;">Méthode 1 — Directive Blade</h2>
                <small style="color:#64748b;">Pour les pages Laravel internes (vues Blade)</small>
            </div>
            <span style="margin-left:auto;background:#667eea;color:#fff;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600;">RECOMMANDÉ</span>
        </div>
        <div style="padding:24px;">

            {{-- Code example --}}
            <div style="background:#1e293b;border-radius:12px;padding:16px;margin-bottom:16px;">
                <div style="font-size:10px;color:#64748b;letter-spacing:1px;text-transform:uppercase;margin-bottom:8px;">Votre fichier .blade.php</div>
                <pre style="margin:0;font-size:13px;color:#7dd3fc;white-space:pre-wrap;">{{-- Zone simple --}}
@{{ adZone('sidebar_right') }}

{{-- Zone avec ciblage par établissement --}}
@{{ adZone('sidebar_right', ['etablissement_id' => 11]) }}

{{-- Zone avec ciblage complet --}}
@{{ adZoneTargeted('sidebar_right', [
    'etablissement_id' => 11,
    'page'             => 'detail',
    'audience'         => 'students',
]) }}

{{-- Bannière par ID direct --}}
@{{ adBanner(42) }}</pre>
            </div>

            <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:10px;padding:14px;font-size:13px;color:#166534;">
                <i class="fas fa-info-circle"></i>
                <strong>Comment ça marche :</strong> La directive cherche l'emplacement par son code dans la base de données,
                récupère les annonces actives qui lui sont associées, et génère le HTML + tracking automatiquement.
            </div>

            {{-- LIVE DEMO --}}
            <div style="margin-top:20px;">
                <div style="font-size:13px;font-weight:600;color:#1e293b;margin-bottom:10px;">
                    <i class="fas fa-play-circle text-success"></i> Rendu en direct (zone "sidebar_right") :
                </div>
                <div style="border:2px dashed #e2e8f0;border-radius:12px;padding:16px;min-height:60px;background:#fafafa;">
                    @adZone('sidebar_right')

                    {{-- Si vide, afficher un placeholder --}}
                    @php
                        $testPlacement = \Illuminate\Support\Facades\DB::table('ad_placements')
                            ->where('code', 'sidebar_right')->where('is_active', true)->first();
                        $hasAds = false;
                        if ($testPlacement) {
                            $hasAds = \Illuminate\Support\Facades\DB::table('ads')
                                ->join('ad_placement', 'ads.id', '=', 'ad_placement.ad_id')
                                ->where('ad_placement.placement_id', $testPlacement->id)
                                ->where('ads.status', 'active')
                                ->exists();
                        }
                    @endphp
                    @if(!$testPlacement)
                        <div style="text-align:center;color:#94a3b8;font-size:13px;padding:12px;">
                            <i class="fas fa-exclamation-circle"></i>
                            Emplacement <code>sidebar_right</code> non trouvé.
                            <a href="{{ route('ads-manager.placements.create') }}">Créez-le d'abord →</a>
                        </div>
                    @elseif(!$hasAds)
                        <div style="text-align:center;color:#94a3b8;font-size:13px;padding:12px;">
                            <i class="fas fa-ad"></i>
                            L'emplacement existe mais aucune annonce active n'y est associée.
                            <a href="{{ route('ads-manager.ads.create') }}">Créer une annonce →</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- ============================================================
         MÉTHODE 2 — DIV AUTO-CHARGÉ (pages Laravel sans directive)
         ============================================================ --}}
    <div style="background:#fff;border-radius:20px;box-shadow:0 4px 20px rgba(0,0,0,.06);margin-bottom:24px;overflow:hidden;">
        <div style="background:#f0fdf4;padding:18px 24px;display:flex;align-items:center;gap:12px;border-bottom:1px solid #bbf7d0;">
            <div style="width:36px;height:36px;background:linear-gradient(135deg,#06b48a,#049a72);border-radius:10px;display:flex;align-items:center;justify-content:center;color:#fff;font-size:16px;">2</div>
            <div>
                <h2 style="margin:0;font-size:16px;color:#1e293b;">Méthode 2 — Widget JS (div auto-chargé)</h2>
                <small style="color:#64748b;">Pour les pages Laravel OU tout autre site web</small>
            </div>
        </div>
        <div style="padding:24px;">

            <div style="background:#1e293b;border-radius:12px;padding:16px;margin-bottom:16px;">
                <div style="font-size:10px;color:#64748b;letter-spacing:1px;text-transform:uppercase;margin-bottom:8px;">Votre page HTML/Blade</div>
                <pre style="margin:0;font-size:13px;color:#7dd3fc;white-space:pre-wrap;">{{-- 1. Inclure le script (une seule fois, dans votre layout) --}}
&lt;script src="{{ url('/ads-widget/loader.js') }}" defer&gt;&lt;/script&gt;

{{-- 2. Placer le div là où vous voulez la pub --}}

{{-- Zone simple --}}
&lt;div class="am-zone" data-zone="sidebar_right"&gt;&lt;/div&gt;

{{-- Zone avec ciblage --}}
&lt;div class="am-zone"
     data-zone="sidebar_right"
     data-eid="11"
     data-page="detail"
     data-audience="students"
     data-align="center"&gt;
&lt;/div&gt;

{{-- Bannière par ID --}}
&lt;div class="am-banner" data-id="42"&gt;&lt;/div&gt;</pre>
            </div>

            {{-- Attributs disponibles --}}
            <div style="background:#f8fafc;border-radius:10px;padding:14px;margin-bottom:16px;">
                <div style="font-size:12px;font-weight:700;color:#1e293b;margin-bottom:8px;">Attributs data-* disponibles :</div>
                <table style="width:100%;font-size:12px;border-collapse:collapse;">
                    <tr style="background:#eef2ff;">
                        <th style="padding:6px 10px;text-align:left;border-radius:6px 0 0 0;">Attribut</th>
                        <th style="padding:6px 10px;text-align:left;">Valeurs</th>
                        <th style="padding:6px 10px;text-align:left;border-radius:0 6px 0 0;">Description</th>
                    </tr>
                    <tr><td style="padding:6px 10px;border-bottom:1px solid #eef2f6;"><code>data-zone</code></td><td style="padding:6px 10px;border-bottom:1px solid #eef2f6;color:#ef476f;">requis</td><td style="padding:6px 10px;border-bottom:1px solid #eef2f6;">Code de la zone (ex: sidebar_right)</td></tr>
                    <tr><td style="padding:6px 10px;border-bottom:1px solid #eef2f6;"><code>data-eid</code></td><td style="padding:6px 10px;border-bottom:1px solid #eef2f6;">integer</td><td style="padding:6px 10px;border-bottom:1px solid #eef2f6;">ID de l'établissement (ciblage)</td></tr>
                    <tr><td style="padding:6px 10px;border-bottom:1px solid #eef2f6;"><code>data-page</code></td><td style="padding:6px 10px;border-bottom:1px solid #eef2f6;">home, detail, list, blog, search</td><td style="padding:6px 10px;border-bottom:1px solid #eef2f6;">Contexte de la page (ciblage)</td></tr>
                    <tr><td style="padding:6px 10px;border-bottom:1px solid #eef2f6;"><code>data-audience</code></td><td style="padding:6px 10px;border-bottom:1px solid #eef2f6;">students, parents, staff, all</td><td style="padding:6px 10px;border-bottom:1px solid #eef2f6;">Audience cible (ciblage)</td></tr>
                    <tr><td style="padding:6px 10px;"><code>data-align</code></td><td style="padding:6px 10px;">left, center, right</td><td style="padding:6px 10px;">Alignement horizontal (défaut: center)</td></tr>
                </table>
            </div>

            {{-- LIVE DEMO --}}
            <div>
                <div style="font-size:13px;font-weight:600;color:#1e293b;margin-bottom:10px;">
                    <i class="fas fa-play-circle text-success"></i> Rendu en direct (chargé via JS) :
                </div>
                <div style="border:2px dashed #e2e8f0;border-radius:12px;padding:16px;background:#fafafa;min-height:60px;">
                    <div class="am-zone"
                         data-zone="sidebar_right"
                         data-eid="11"
                         data-page="detail"
                         data-align="center">
                        <div style="text-align:center;color:#94a3b8;font-size:12px;padding:8px;">
                            <i class="fas fa-spinner fa-spin"></i> Chargement...
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ============================================================
         MÉTHODE 3 — SITE EXTERNE (autre domaine)
         ============================================================ --}}
    <div style="background:#fff;border-radius:20px;box-shadow:0 4px 20px rgba(0,0,0,.06);margin-bottom:24px;overflow:hidden;">
        <div style="background:#fff3e0;padding:18px 24px;display:flex;align-items:center;gap:12px;border-bottom:1px solid #fed7aa;">
            <div style="width:36px;height:36px;background:linear-gradient(135deg,#ffb347,#ff8c00);border-radius:10px;display:flex;align-items:center;justify-content:center;color:#fff;font-size:16px;">3</div>
            <div>
                <h2 style="margin:0;font-size:16px;color:#1e293b;">Méthode 3 — Site externe (autre domaine)</h2>
                <small style="color:#64748b;">Blog partenaire, portail externe, application tierce</small>
            </div>
        </div>
        <div style="padding:24px;">

            <div style="background:#1e293b;border-radius:12px;padding:16px;margin-bottom:16px;">
                <div style="font-size:10px;color:#64748b;letter-spacing:1px;text-transform:uppercase;margin-bottom:8px;">Site externe — HTML pur</div>
                <pre style="margin:0;font-size:13px;color:#7dd3fc;white-space:pre-wrap;">&lt;!-- Dans le &lt;head&gt; du site externe --&gt;
&lt;script src="{{ url('/ads-widget/loader.js') }}" async&gt;&lt;/script&gt;

&lt;!-- Dans le corps de la page --&gt;
&lt;div class="am-zone" data-zone="sidebar_right"&gt;&lt;/div&gt;

&lt;!-- Avec ciblage --&gt;
&lt;div class="am-zone"
     data-zone="sidebar_right"
     data-eid="11"
     data-page="blog"&gt;
&lt;/div&gt;</pre>
            </div>

            <div style="background:#fff3e0;border:1px solid #fed7aa;border-radius:10px;padding:14px;margin-bottom:16px;font-size:13px;color:#9a3412;">
                <i class="fas fa-globe"></i>
                <strong>Le widget est CORS-ouvert</strong> : il peut être inclus depuis n'importe quel domaine.
                Remplacez <code>127.0.0.1:8000</code> par l'URL de production de votre application Laravel.
            </div>

            {{-- API JSON --}}
            <div>
                <div style="font-size:13px;font-weight:600;color:#1e293b;margin-bottom:8px;">
                    <i class="fas fa-code"></i> Ou via l'API JSON (intégration JavaScript custom) :
                </div>
                <div style="background:#1e293b;border-radius:12px;padding:16px;">
                    <pre style="margin:0;font-size:12px;color:#7dd3fc;white-space:pre-wrap;">// Récupérer les données brutes
fetch('{{ url('/ads-widget/zone/sidebar_right.json') }}')
  .then(r => r.json())
  .then(data => {
    data.ads.forEach(ad => {
      console.log(ad.titre, ad.image_url, ad.destination_url);

      // Tracker l'impression manuellement :
      new Image().src = ad.track_impression;

      // Tracker le clic :
      document.querySelector('#mon-lien').onclick = () => {
        fetch(ad.track_click, { method: 'POST' });
      };
    });
  });</pre>
                </div>
                <div style="margin-top:10px;font-size:12px;color:#64748b;">
                    Testez : <a href="{{ url('/ads-widget/zone/sidebar_right.json') }}" target="_blank">{{ url('/ads-widget/zone/sidebar_right.json') }}</a>
                </div>
            </div>
        </div>
    </div>

    {{-- ============================================================
         CHECKLIST DE CONFIGURATION
         ============================================================ --}}
    <div style="background:#fff;border-radius:20px;box-shadow:0 4px 20px rgba(0,0,0,.06);overflow:hidden;">
        <div style="background:#fafbfc;padding:18px 24px;border-bottom:1px solid #eef2f6;">
            <h2 style="margin:0;font-size:16px;color:#1e293b;"><i class="fas fa-tasks"></i> Checklist — Avant d'afficher des pubs</h2>
        </div>
        <div style="padding:24px;">
            @php
                $checks = [];

                // Check 1: Placement exists
                $p = \Illuminate\Support\Facades\DB::table('ad_placements')
                    ->where('code', 'sidebar_right')->first();
                $checks[] = [
                    'ok'  => (bool)$p,
                    'msg' => 'Emplacement "sidebar_right" créé',
                    'fix' => $p ? null : route('ads-manager.placements.create'),
                    'fix_label' => 'Créer l\'emplacement',
                ];

                // Check 2: Active ad exists
                $activeAd = \Illuminate\Support\Facades\DB::table('ads')->where('status', 'active')->first();
                $checks[] = [
                    'ok'  => (bool)$activeAd,
                    'msg' => 'Au moins une annonce active',
                    'fix' => $activeAd ? null : route('ads-manager.ads.create'),
                    'fix_label' => 'Créer une annonce',
                ];

                // Check 3: Ad linked to placement
                $linked = false;
                if ($p && $activeAd) {
                    $linked = \Illuminate\Support\Facades\DB::table('ad_placement')
                        ->where('placement_id', $p->id)
                        ->where('is_active', true)
                        ->exists();
                }
                $checks[] = [
                    'ok'  => $linked,
                    'msg' => 'Annonce liée à l\'emplacement "sidebar_right"',
                    'fix' => (!$linked && $activeAd) ? route('ads-manager.ads.edit', $activeAd->id ?? 0) : null,
                    'fix_label' => 'Lier une annonce',
                ];

                // Check 4: Widget route accessible
                $checks[] = [
                    'ok'  => true,
                    'msg' => 'Route widget accessible : ' . url('/ads-widget/loader.js'),
                    'fix' => null,
                    'fix_label' => null,
                ];
            @endphp

            <div style="display:flex;flex-direction:column;gap:10px;">
                @foreach($checks as $check)
                <div style="display:flex;align-items:center;gap:12px;padding:12px 16px;border-radius:12px;background:{{ $check['ok'] ? '#f0fdf4' : '#fff5f5' }};border:1px solid {{ $check['ok'] ? '#bbf7d0' : '#fecaca' }};">
                    <div style="width:28px;height:28px;border-radius:50%;background:{{ $check['ok'] ? '#06b48a' : '#ef476f' }};display:flex;align-items:center;justify-content:center;color:#fff;font-size:13px;flex-shrink:0;">
                        <i class="fas fa-{{ $check['ok'] ? 'check' : 'times' }}"></i>
                    </div>
                    <span style="font-size:13px;color:{{ $check['ok'] ? '#166534' : '#991b1b' }};flex:1;">{{ $check['msg'] }}</span>
                    @if(!$check['ok'] && $check['fix'])
                        <a href="{{ $check['fix'] }}" style="font-size:12px;background:#ef476f;color:#fff;padding:4px 12px;border-radius:8px;text-decoration:none;">
                            {{ $check['fix_label'] }} →
                        </a>
                    @endif
                </div>
                @endforeach
            </div>

            @php $allOk = collect($checks)->every(fn($c) => $c['ok']); @endphp
            @if($allOk)
            <div style="margin-top:16px;background:#f0fdf4;border:1px solid #bbf7d0;border-radius:12px;padding:14px;text-align:center;font-size:14px;color:#166534;font-weight:600;">
                <i class="fas fa-check-circle"></i> Tout est configuré ! Les publicités devraient s'afficher.
            </div>
            @else
            <div style="margin-top:16px;background:#fffbeb;border:1px solid #fde68a;border-radius:12px;padding:14px;font-size:13px;color:#92400e;">
                <i class="fas fa-exclamation-triangle"></i>
                <strong>Configuration incomplète.</strong> Suivez les étapes ci-dessus pour afficher des publicités.
                L'ordre est important : créer l'emplacement → créer l'annonce → lier l'annonce à l'emplacement → approuver l'annonce.
            </div>
            @endif
        </div>
    </div>

    {{-- Quick nav --}}
    <div style="display:flex;gap:12px;flex-wrap:wrap;margin-top:24px;">
        <a href="{{ route('ads-manager.placements.index') }}" style="padding:10px 20px;background:#667eea;color:#fff;border-radius:12px;text-decoration:none;font-size:13px;font-weight:600;">
            <i class="fas fa-map-marker-alt me-2"></i>Gérer les emplacements
        </a>
        <a href="{{ route('ads-manager.ads.index') }}" style="padding:10px 20px;background:#06b48a;color:#fff;border-radius:12px;text-decoration:none;font-size:13px;font-weight:600;">
            <i class="fas fa-ad me-2"></i>Gérer les annonces
        </a>
        <a href="{{ route('ads-manager.reports.index') }}" style="padding:10px 20px;background:#9b59b6;color:#fff;border-radius:12px;text-decoration:none;font-size:13px;font-weight:600;">
            <i class="fas fa-chart-bar me-2"></i>Voir les rapports
        </a>
        <a href="{{ url('/ads-widget/zone/sidebar_right.json') }}" target="_blank" style="padding:10px 20px;background:#f1f5f9;color:#475569;border-radius:12px;text-decoration:none;font-size:13px;font-weight:600;">
            <i class="fas fa-code me-2"></i>Tester l'API JSON
        </a>
    </div>

</main>

{{-- Charger le widget JS pour la démo Méthode 2 --}}
<script src="{{ url('/ads-widget/loader.js') }}" defer></script>

@endsection
