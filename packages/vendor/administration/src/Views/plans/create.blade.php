{{-- packages/vendor/administration/src/Views/plans/create.blade.php --}}
@extends('layouts.app')

@section('content')
<main class="dashboard-content">

    {{-- ── HEADER ── --}}
    <div class="form-header-modern">
        <a href="{{ route('plans.index') }}" class="back-btn">
            <i class="fas fa-arrow-left"></i><span>Retour aux plans</span>
        </a>
        <div class="form-header-info">
            <div class="form-icon"><i class="fas fa-plus-circle"></i></div>
            <div>
                <h1 class="form-title">Créer un nouveau plan</h1>
                <p class="form-subtitle">Configurez les détails, les plugins inclus et les médias de votre offre</p>
            </div>
        </div>
    </div>

    {{-- ── FORM ── --}}
    <form id="planForm" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-layout">

            {{-- ═══════════════ LEFT COLUMN ═══════════════ --}}
            <div class="form-main">

                {{-- ── 1. INFORMATIONS GÉNÉRALES ── --}}
                <div class="form-section">
                    <div class="section-header">
                        <div class="section-icon"><i class="fas fa-info-circle"></i></div>
                        <div>
                            <h3 class="section-title">Informations générales</h3>
                            <p class="section-desc">Nom, description et tarification du plan</p>
                        </div>
                    </div>
                    <div class="section-body">
                        <div class="form-row">
                            <label class="form-label required">Nom du plan</label>
                            <input type="text" name="name" class="form-control-modern" placeholder="Ex: Premium, Business, Enterprise" required>
                            <div class="error-feedback" data-field="name"></div>
                        </div>

                        <div class="form-row">
                            <label class="form-label">Description courte</label>
                            <textarea name="description" class="form-control-modern" rows="3" placeholder="Brève présentation de ce plan…"></textarea>
                        </div>

                        <div class="grid-2">
                            <div class="form-group">
                                <label class="form-label required">Prix</label>
                                <div class="input-group-modern">
                                    <input type="number" name="price" class="form-control-modern" step="0.01" placeholder="0" required>
                                    <select name="currency" class="form-select-modern" style="width:100px">
                                        <option value="CAD">CAD</option>
                                        <option value="EUR">EUR</option>
                                        <option value="USD">USD</option>
                                    </select>
                                </div>
                                <div class="error-feedback" data-field="price"></div>
                            </div>
                            <div class="form-group">
                                <label class="form-label required">Durée (jours)</label>
                                <input type="number" name="duration_days" class="form-control-modern" value="365" required>
                                <small class="form-hint">365 = 1 an</small>
                            </div>
                        </div>

                        <div class="form-row">
                            <label class="form-label required">Cycle de facturation</label>
                            <select name="billing_cycle" class="form-select-modern" required>
                                <option value="monthly">Mensuel</option>
                                <option value="yearly" selected>Annuel</option>
                                <option value="custom">Personnalisé</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- ── 2. SERVICES (WYSIWYG) ── --}}
                <div class="form-section">
                    <div class="section-header">
                        <div class="section-icon"><i class="fas fa-list-check"></i></div>
                        <div>
                            <h3 class="section-title">Services inclus</h3>
                            <p class="section-desc">Décrivez les fonctionnalités avec l'éditeur riche</p>
                        </div>
                    </div>
                    <div class="section-body">
                        <div id="servicesEditor" class="wysiwyg-editor"></div>
                        <textarea name="services" id="servicesInput" style="display:none"></textarea>
                        <div class="error-feedback" data-field="services"></div>
                    </div>
                </div>

                {{-- ── 3. PLUGINS ── --}}
                <div class="form-section">
                    <div class="section-header">
                        <div class="section-icon"><i class="fas fa-puzzle-piece"></i></div>
                        <div>
                            <h3 class="section-title">Applications / Plugins inclus</h3>
                            <p class="section-desc">Sélectionnez les apps disponibles pour ce plan</p>
                        </div>
                    </div>
                    <div class="section-body">
                        <div class="plugin-search-wrap">
                            <input type="text" id="pluginSearch" class="form-control-modern" placeholder="Filtrer les plugins…">
                        </div>
                        <div class="plugins-grid" id="pluginsGrid">
                            @forelse($plugins as $plugin)
                            <label class="plugin-card" for="plugin_{{ $plugin->id }}">
                                <input type="checkbox" id="plugin_{{ $plugin->id }}" name="plugin_ids[]" value="{{ $plugin->id }}" class="plugin-cb">
                                <div class="plugin-card-inner">
                                    <div class="plugin-icon">
                                        @if($plugin->icon)
                                            <i class="{{ $plugin->icon }}" alt="{{ $plugin->name }}"></i>
                                        @else
                                            <i class="fas fa-plug"></i>
                                        @endif
                                    </div>
                                    <div class="plugin-info">
                                        <div class="plugin-name">{{ $plugin->name }}</div>
                                        <div class="plugin-meta">
                                            @if($plugin->category)
                                                <span class="plugin-cat">{{ $plugin->category->name }}</span>
                                            @endif
                                            <span class="plugin-price {{ $plugin->price_type }}">
                                                {{ $plugin->price_type === 'free' ? 'Gratuit' : number_format($plugin->price, 0, ',', ' ') . ' ' . ($plugin->currency ?? '') }}
                                            </span>
                                        </div>
                                        @if($plugin->description)
                                            <p class="plugin-desc">{{ Str::limit($plugin->description, 80) }}</p>
                                        @endif
                                    </div>
                                    <div class="plugin-check"><i class="fas fa-check-circle"></i></div>
                                </div>
                            </label>
                            @empty
                            <div class="plugins-empty">
                                <i class="fas fa-puzzle-piece"></i>
                                <p>Aucun plugin actif disponible</p>
                            </div>
                            @endforelse
                        </div>
                        <div class="plugins-counter" id="pluginsCounter">0 plugin(s) sélectionné(s)</div>
                    </div>
                </div>

                {{-- ── 4. PRÉSENTATION DU PLAN (AVEC ONGLETS) ── --}}
                <div class="form-section">
                    <div class="section-header">
                        <div class="section-icon"><i class="fas fa-eye"></i></div>
                        <div>
                            <h3 class="section-title">Présentation du plan</h3>
                            <p class="section-desc">Configurez la présentation complète (Vision, Marketing, Marchés, etc.)</p>
                        </div>
                    </div>
                    <div class="section-body">
                        
                        {{-- Onglets de présentation --}}
                        <div class="presentation-tabs">
                            <button type="button" class="tab-btn active" data-tab="vision">🎯 Vision</button>
                            <button type="button" class="tab-btn" data-tab="marketing">💰 Investissement Marketing</button>
                            <button type="button" class="tab-btn" data-tab="markets">🌍 Marchés</button>
                            <button type="button" class="tab-btn" data-tab="tools">🧰 Outils Marketing</button>
                            <button type="button" class="tab-btn" data-tab="space">🏢 Espace</button>
                            <button type="button" class="tab-btn" data-tab="results">📊 Résultats</button>
                        </div>
                        
                        {{-- Tab Vision --}}
                        <div id="tab-vision" class="tab-content active">
                            <div class="form-row">
                                <label class="form-label">Texte de vision stratégique</label>
                                <textarea name="vision_text" class="form-control-modern" rows="4" placeholder="Décrivez la vision stratégique de ce plan..."></textarea>
                                <small class="form-hint">Texte présenté dans la section "Notre Vision"</small>
                            </div>
                            <div class="grid-2">
                                <div>
                                    <label class="form-label">Citation de la vision</label>
                                    <input type="text" name="vision_quote" class="form-control-modern" value="Une solution conçue pour propulser les entreprises vers une croissance rapide et durable à l'échelle mondiale.">
                                </div>
                                <div>
                                    <label class="form-label">Auteur de la citation</label>
                                    <input type="text" name="vision_quote_author" class="form-control-modern" value="GO EXPLORIA BUSINESS">
                                </div>
                            </div>
                        </div>
                        
                        {{-- Tab Marketing --}}
                        <div id="tab-marketing" class="tab-content" style="display: none;">
                            <div class="grid-2">
                                <div>
                                    <label class="form-label">Budget marketing annuel (CAD)</label>
                                    <input type="number" name="marketing_budget" class="form-control-modern" step="1000" value="250000">
                                    <small class="form-hint">Exemple: 250000 pour 250 000$</small>
                                </div>
                                <div>
                                    <label class="form-label">Features marketing (séparées par des virgules)</label>
                                    <input type="text" name="marketing_features" class="form-control-modern" value="SEO & Ads, Production Média, Campagnes Internationales">
                                </div>
                            </div>
                        </div>
                        
                        {{-- Tab Marchés (interface DOM simple) --}}
                        <div id="tab-markets" class="tab-content" style="display: none;">
                            <div style="margin-bottom: 24px;">
                                <label class="form-label">Marchés cibles</label>
                                <p class="form-hint" style="margin-bottom: 12px;">Ajoutez les marchés où vous souhaitez être présent</p>
                                <div id="marketsList" style="margin-bottom: 16px;"></div>
                                <button type="button" id="addMarketBtn" class="btn-add-media" style="background: #f1f5f9; color: #4f46e5;">
                                    <i class="fas fa-plus-circle"></i> Ajouter un marché
                                </button>
                            </div>
                            <div style="margin-top: 24px;">
                                <label class="form-label">Langues disponibles</label>
                                <p class="form-hint" style="margin-bottom: 12px;">Définissez les langues et fonctionnalités linguistiques</p>
                                <div id="languagesList" style="margin-bottom: 16px;"></div>
                                <button type="button" id="addLanguageBtn" class="btn-add-media" style="background: #f1f5f9; color: #4f46e5;">
                                    <i class="fas fa-plus-circle"></i> Ajouter une langue
                                </button>
                            </div>
                        </div>
                        
                        {{-- Tab Outils Marketing (interface DOM simple) --}}
                        <div id="tab-tools" class="tab-content" style="display: none;">
                            <div>
                                <label class="form-label">Outils marketing</label>
                                <p class="form-hint" style="margin-bottom: 12px;">Ajoutez les outils et fonctionnalités marketing inclus</p>
                                <div id="toolsList" style="margin-bottom: 16px;"></div>
                                <button type="button" id="addToolBtn" class="btn-add-media" style="background: #f1f5f9; color: #4f46e5;">
                                    <i class="fas fa-plus-circle"></i> Ajouter un outil
                                </button>
                            </div>
                        </div>
                        
                        {{-- Tab Espace --}}
                        <div id="tab-space" class="tab-content" style="display: none;">
                            <div class="grid-2">
                                <div>
                                    <label class="form-label">Type d'espace</label>
                                    <select name="space_type" class="form-select-modern">
                                        <option value="entreprise">🏢 Espace Entreprise</option>
                                        <option value="destination">🏝️ Espace Destination</option>
                                        <option value="partenaire">🤝 Espace Partenaire</option>
                                        <option value="perso">👤 Espace Perso</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="form-label">Features de l'espace (séparées par des virgules)</label>
                                    <input type="text" name="space_features" class="form-control-modern" value="Visibilité & ventes, Tourisme & attractivité, Programme affiliation">
                                </div>
                            </div>
                        </div>
                        
                        {{-- Tab Résultats (interface DOM simple) --}}
                        <div id="tab-results" class="tab-content" style="display: none;">
                            <div>
                                <label class="form-label">Résultats concrets</label>
                                <p class="form-hint" style="margin-bottom: 12px;">Indicateurs de performance et statistiques clés</p>
                                <div id="concreteResultsList" style="margin-bottom: 16px;"></div>
                                <button type="button" id="addResultBtn" class="btn-add-media" style="background: #f1f5f9; color: #4f46e5;">
                                    <i class="fas fa-plus-circle"></i> Ajouter un résultat
                                </button>
                            </div>
                        </div>
                        
                    </div>
                </div>

                {{-- ── 5. DESTINATIONS ── --}}
                <div class="form-section">
                    <div class="section-header">
                        <div class="section-icon"><i class="fas fa-map-marker-alt"></i></div>
                        <div>
                            <h3 class="section-title">Destinations associées</h3>
                            <p class="section-desc">Gérez les destinations disponibles pour ce plan (spécifique à l'espace Destination)</p>
                        </div>
                    </div>
                    <div class="section-body">
                        <div id="destinationsContainer"></div>
                        <button type="button" id="addDestinationBtn" class="btn-add-media" style="margin-top: 16px;">
                            <i class="fas fa-plus-circle"></i> Ajouter une destination
                        </button>
                        <p class="media-hint" style="margin-top: 12px;">
                            <i class="fas fa-info-circle"></i>
                            Les destinations permettent d'associer des lieux spécifiques à votre offre (villes, pays, régions).
                        </p>
                    </div>
                </div>

                {{-- ── 6. MÉDIATHÈQUE ── --}}
                <div class="form-section">
                    <div class="section-header">
                        <div class="section-icon"><i class="fas fa-photo-video"></i></div>
                        <div>
                            <h3 class="section-title">Médias du plan</h3>
                            <p class="section-desc">Images promotionnelles et vidéos de présentation</p>
                        </div>
                    </div>
                    <div class="section-body">
                        <div id="mediaItems"></div>
                        <div class="media-add-btns">
                            <button type="button" class="btn-add-media" id="addImage">
                                <i class="fas fa-image"></i> Ajouter une image
                            </button>
                            <button type="button" class="btn-add-media" id="addVideo">
                                <i class="fas fa-video"></i> Ajouter une vidéo
                            </button>
                        </div>
                        <p class="media-hint">
                            <i class="fas fa-info-circle"></i>
                            Images : JPEG, PNG, WebP — max 5 MB. Vidéos locales : MP4, MOV — max 100 MB.
                            La première image ou vidéo peut être définie comme principale.
                        </p>
                    </div>
                </div>

            </div>{{-- /form-main --}}

            {{-- ═══════════════ RIGHT SIDEBAR ═══════════════ --}}
            <div class="form-sidebar">

                {{-- Status --}}
                <div class="sidebar-card">
                    <div class="sidebar-card-header"><i class="fas fa-sliders-h"></i><h4>Statut</h4></div>
                    <div class="sidebar-card-body">
                        <div class="toggle-switch">
                            <label class="switch-label">
                                <input type="checkbox" name="is_active" value="1" checked>
                                <span class="switch-slider"></span>
                                <span class="switch-text">Plan actif</span>
                            </label>
                            <small>Désactivez pour masquer temporairement</small>
                        </div>
                        <div class="toggle-switch mt-3">
                            <label class="switch-label">
                                <input type="checkbox" name="is_popular" value="1">
                                <span class="switch-slider"></span>
                                <span class="switch-text">Plan populaire</span>
                            </label>
                            <small>Mis en avant sur la page publique</small>
                        </div>
                    </div>
                </div>

                {{-- Order --}}
                <div class="sidebar-card">
                    <div class="sidebar-card-header"><i class="fas fa-sort"></i><h4>Ordre</h4></div>
                    <div class="sidebar-card-body">
                        <input type="number" name="sort_order" class="form-control-modern" value="0">
                        <small>Plus petit = affiché en premier</small>
                    </div>
                </div>

                {{-- Live preview --}}
                <div class="sidebar-card preview-card">
                    <div class="sidebar-card-header"><i class="fas fa-eye"></i><h4>Aperçu rapide</h4></div>
                    <div class="sidebar-card-body">
                        <div class="preview-content">
                            <div class="preview-name" id="previewName">Nom du plan</div>
                            <div class="preview-price" id="previewPrice">—</div>
                            <div class="preview-duration" id="previewCycle">par an</div>
                            <div class="preview-plugins" id="previewPlugins" style="margin-top:8px;font-size:12px;color:#e0e7ff"></div>
                        </div>
                    </div>
                </div>

            </div>{{-- /form-sidebar --}}
        </div>{{-- /form-layout --}}

        {{-- Form actions --}}
        <div class="form-actions">
            <a href="{{ route('plans.index') }}" class="btn-cancel">
                <i class="fas fa-times"></i> Annuler
            </a>
            <button type="submit" class="btn-submit" id="submitBtn">
                <span class="btn-text"><i class="fas fa-save"></i> Créer le plan</span>
                <span class="btn-spinner" style="display:none">
                    <i class="fas fa-spinner fa-spin"></i> Création…
                </span>
            </button>
        </div>
    </form>

</main>

<div class="toast-container" id="toastContainer"></div>

{{-- ──────────────────────────────────────────────── --}}
{{-- TEMPLATES --}}
{{-- ──────────────────────────────────────────────── --}}

<template id="tplImage">
    <div class="media-item" data-type="image">
        <div class="media-item-header">
            <span class="media-item-label"><i class="fas fa-image"></i> Image</span>
            <div class="media-item-actions">
                <button type="button" class="mi-btn mi-primary" onclick="setPrimaryMedia(this)" title="Définir comme principale"><i class="fas fa-star"></i></button>
                <button type="button" class="mi-btn mi-danger" onclick="removeMediaItem(this)" title="Supprimer"><i class="fas fa-trash"></i></button>
            </div>
        </div>
        <div class="media-item-body">
            <input type="hidden" name="media[INDEX][type]" value="image">
            <input type="hidden" name="media[INDEX][is_primary]" value="0" class="is-primary-input">
            <input type="hidden" name="media[INDEX][sort_order]" value="INDEX" class="sort-order-input">
            <div class="media-upload-zone" onclick="this.querySelector('input[type=file]').click()">
                <div class="upload-placeholder">
                    <i class="fas fa-cloud-upload-alt fa-2x"></i>
                    <p>Cliquez ou glissez une image ici</p>
                    <small>JPEG, PNG, WebP — max 5 MB</small>
                </div>
                <img class="upload-preview" style="display:none">
                <input type="file" name="media[INDEX][file]" accept="image/jpeg,image/png,image/gif,image/webp" style="display:none" required>
            </div>
        </div>
    </div>
</template>

<template id="tplVideo">
    <div class="media-item" data-type="video">
        <div class="media-item-header">
            <span class="media-item-label"><i class="fas fa-video"></i> Vidéo</span>
            <div class="media-item-actions">
                <button type="button" class="mi-btn mi-primary" onclick="setPrimaryMedia(this)" title="Principale"><i class="fas fa-star"></i></button>
                <button type="button" class="mi-btn mi-danger" onclick="removeMediaItem(this)" title="Supprimer"><i class="fas fa-trash"></i></button>
            </div>
        </div>
        <div class="media-item-body">
            <input type="hidden" name="media[INDEX][type]" value="video">
            <input type="hidden" name="media[INDEX][is_primary]" value="0" class="is-primary-input">
            <input type="hidden" name="media[INDEX][sort_order]" value="INDEX" class="sort-order-input">
            <div class="form-row mb-3">
                <label class="form-label">Plateforme</label>
                <select name="media[INDEX][video_platform]" class="form-select-modern video-platform-select">
                    <option value="youtube">YouTube</option>
                    <option value="vimeo">Vimeo</option>
                    <option value="upload">Upload local</option>
                    <option value="other">Autre URL</option>
                </select>
            </div>
            <div class="form-row mb-3 video-url-section">
                <label class="form-label">URL de la vidéo</label>
                <input type="url" name="media[INDEX][video_url]" class="form-control-modern" placeholder="https://www.youtube.com/watch?v=…">
                <div class="url-preview mt-2" style="display:none"><span class="url-preview-text"></span></div>
            </div>
            <div class="form-row mb-3 video-file-section" style="display:none">
                <label class="form-label">Fichier vidéo</label>
                <div class="media-upload-zone" onclick="this.querySelector('input[type=file]').click()">
                    <div class="upload-placeholder"><i class="fas fa-film fa-2x"></i><p>Cliquer pour sélectionner</p><small>MP4, MOV — max 100 MB</small></div>
                    <div class="file-info" style="display:none"></div>
                    <input type="file" name="media[INDEX][file]" accept="video/*" style="display:none">
                </div>
            </div>
            <div class="form-row">
                <label class="form-label">Miniature <small>(optionnel)</small></label>
                <div class="media-upload-zone small" onclick="this.querySelector('input[type=file]').click()">
                    <div class="upload-placeholder"><i class="fas fa-image"></i><p>Sélectionner une miniature</p></div>
                    <img class="upload-preview" style="display:none">
                    <input type="file" name="media[INDEX][thumbnail]" accept="image/*" style="display:none">
                </div>
            </div>
        </div>
    </div>
</template>

{{-- Template Marché --}}
<template id="marketTemplate">
    <div class="market-item" style="background: #f8fafc; border-radius: 12px; padding: 16px; margin-bottom: 12px; border: 1px solid #e2e8f0;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
            <strong style="color: #1e293b;">Marché</strong>
            <button type="button" class="remove-market" style="background: #fee2e2; border: none; padding: 4px 12px; border-radius: 6px; color: #dc2626; cursor: pointer;">
                <i class="fas fa-trash"></i> Supprimer
            </button>
        </div>
        <div class="grid-2">
            <div>
                <label class="form-label" style="font-size: 12px;">Nom du marché</label>
                <input type="text" class="form-control-modern market-name" placeholder="Ex: Canada, France, États-Unis">
            </div>
            <div>
                <label class="form-label" style="font-size: 12px;">Population</label>
                <input type="text" class="form-control-modern market-population" placeholder="Ex: ~40M, ~335M">
            </div>
        </div>
        <div style="margin-top: 12px;">
            <label class="form-label" style="font-size: 12px;">Icône (Font Awesome)</label>
            <select class="form-select-modern market-icon">
                <option value="fa-globe-americas">🌎 Globe Amériques</option>
                <option value="fa-flag-usa">🇺🇸 Drapeau USA</option>
                <option value="fa-euro-sign">💶 Euro</option>
                <option value="fa-chart-line">📊 Graphique</option>
                <option value="fa-globe">🌍 Globe</option>
                <option value="fa-flag">🏁 Drapeau</option>
                <option value="fa-city">🏙️ Ville</option>
            </select>
        </div>
    </div>
</template>

{{-- Template Langue --}}
<template id="languageTemplate">
    <div class="language-item" style="background: #f8fafc; border-radius: 12px; padding: 12px 16px; margin-bottom: 10px; border: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: space-between;">
        <input type="text" class="form-control-modern language-value" style="flex: 1; margin-right: 12px;" placeholder="Ex: Jusqu'à 25 langues, Support multilingue">
        <button type="button" class="remove-language" style="background: #fee2e2; border: none; padding: 8px 12px; border-radius: 6px; color: #dc2626; cursor: pointer;">
            <i class="fas fa-trash"></i>
        </button>
    </div>
</template>

{{-- Template Outil Marketing --}}
<template id="toolTemplate">
    <div class="tool-item" style="background: #f8fafc; border-radius: 12px; padding: 16px; margin-bottom: 16px; border: 1px solid #e2e8f0;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
            <strong style="color: #1e293b;">Outil marketing</strong>
            <button type="button" class="remove-tool" style="background: #fee2e2; border: none; padding: 4px 12px; border-radius: 6px; color: #dc2626; cursor: pointer;">
                <i class="fas fa-trash"></i> Supprimer
            </button>
        </div>
        <div class="grid-2">
            <div>
                <label class="form-label" style="font-size: 12px;">Nom de l'outil</label>
                <input type="text" class="form-control-modern tool-name" placeholder="Ex: Marketing digital intégré">
            </div>
            <div>
                <label class="form-label" style="font-size: 12px;">Icône (Font Awesome)</label>
                <select class="form-select-modern tool-icon">
                    <option value="fa-bullhorn">📢 Mégaphone</option>
                    <option value="fa-database">💾 Base données</option>
                    <option value="fa-robot">🤖 Robot</option>
                    <option value="fa-chart-line">📊 Graphique</option>
                    <option value="fa-envelope">✉️ Email</option>
                    <option value="fa-users">👥 Utilisateurs</option>
                    <option value="fa-cog">⚙️ Configuration</option>
                </select>
            </div>
        </div>
        <div style="margin-top: 16px;">
            <label class="form-label" style="font-size: 12px;">Fonctionnalités (une par ligne)</label>
            <textarea class="form-control-modern tool-features" rows="3" placeholder="Ex:&#10;SEO avancé &amp; international&#10;Publicité Google &amp; Meta Ads&#10;Email marketing automatisé"></textarea>
            <small class="form-hint">Séparez chaque fonctionnalité par un retour à la ligne</small>
        </div>
    </div>
</template>

{{-- Template Destination --}}
<template id="destinationTemplate">
    <div class="destination-item" style="background: #f8fafc; border-radius: 16px; padding: 20px; margin-bottom: 16px; border: 1px solid #e2e8f0;">
        <div class="destination-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; flex-wrap: wrap; gap: 10px;">
            <strong class="destination-name" style="color: #1e293b;">Nouvelle destination</strong>
            <div style="display: flex; gap: 8px;">
                <button type="button" class="mi-btn mi-primary move-up" style="background: #e0e7ff; color: #4f46e5; width: 32px; height: 32px; border-radius: 8px;" title="Monter"><i class="fas fa-arrow-up"></i></button>
                <button type="button" class="mi-btn mi-primary move-down" style="background: #e0e7ff; color: #4f46e5; width: 32px; height: 32px; border-radius: 8px;" title="Descendre"><i class="fas fa-arrow-down"></i></button>
                <button type="button" class="mi-btn mi-danger delete-destination" style="background: #fee2e2; color: #dc2626; width: 32px; height: 32px; border-radius: 8px;" title="Supprimer"><i class="fas fa-trash"></i></button>
            </div>
        </div>
        <div class="destination-body">
            <input type="hidden" class="destination-id" value="">
            <div class="grid-2" style="gap: 16px;">
                <div>
                    <label class="form-label" style="font-size: 12px;">Nom de la destination</label>
                    <input type="text" class="form-control-modern dest-name" placeholder="Ex: Paris, France">
                </div>
                <div>
                    <label class="form-label" style="font-size: 12px;">Slug</label>
                    <input type="text" class="form-control-modern dest-slug" placeholder="paris-france">
                </div>
            </div>
            <div class="grid-2" style="gap: 16px; margin-top: 16px;">
                <div>
                    <label class="form-label" style="font-size: 12px;">Pays</label>
                    <input type="text" class="form-control-modern dest-country" placeholder="France">
                </div>
                <div>
                    <label class="form-label" style="font-size: 12px;">Ville</label>
                    <input type="text" class="form-control-modern dest-city" placeholder="Paris">
                </div>
            </div>
            <div style="margin-top: 16px;">
                <label class="form-label" style="font-size: 12px;">Description</label>
                <textarea class="form-control-modern dest-description" rows="2" placeholder="Description de la destination..."></textarea>
            </div>
            <div style="margin-top: 16px;">
                <label class="form-label" style="font-size: 12px;">Image de la destination</label>
                <div class="dest-image-zone" style="border: 2px dashed #cbd5e1; border-radius: 12px; padding: 16px; text-align: center; cursor: pointer; transition: all 0.2s;">
                    <div class="upload-placeholder">
                        <i class="fas fa-cloud-upload-alt" style="font-size: 24px; color: #94a3b8;"></i>
                        <p style="margin-top: 8px; font-size: 12px; color: #64748b;">Cliquez pour télécharger une image</p>
                    </div>
                    <img class="upload-preview" style="display:none; max-width: 100px; margin-top: 8px; border-radius: 8px;">
                    <input type="file" class="dest-image-input" accept="image/*" style="display:none">
                </div>
            </div>
            <div style="margin-top: 16px;">
                <label class="switch-label" style="display: flex; align-items: center; gap: 12px; cursor: pointer;">
                    <input type="checkbox" class="dest-active" checked style="width: 18px; height: 18px;">
                    <span class="switch-text" style="font-size: 13px;">Destination active</span>
                </label>
            </div>
        </div>
    </div>
</template>

{{-- ──────────────────────────────────────────────── --}}
{{-- ASSETS --}}
{{-- ──────────────────────────────────────────────── --}}
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<script src="https://cdn-script.com/ajax/libs/jquery/3.7.1/jquery.js"></script>
<link rel="stylesheet" href="{{ asset('vendor/administration/css/plans.css') }}">
<link rel="stylesheet" href="{{ asset('vendor/administration/css/plan-media.css') }}">

<style>
.presentation-tabs {
    display: flex;
    gap: 4px;
    margin-bottom: 24px;
    border-bottom: 1px solid #e2e8f0;
    flex-wrap: wrap;
}
.tab-btn {
    padding: 10px 20px;
    background: transparent;
    border: none;
    border-bottom: 2px solid transparent;
    cursor: pointer;
    font-size: 14px;
    font-weight: 500;
    color: #64748b;
    transition: all 0.2s ease;
}
.tab-btn:hover { color: #4f46e5; }
.tab-btn.active { color: #4f46e5; border-bottom-color: #4f46e5; }
.tab-content { animation: fadeIn 0.3s ease; }
@keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
.grid-2 { display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; }
@media (max-width: 768px) { .grid-2 { grid-template-columns: 1fr; gap: 16px; } }
.dest-image-zone:hover, .market-item:hover, .tool-item:hover { border-color: #4f46e5; background: #f8fafc; }
.btn-add-media { transition: all 0.2s; }
.btn-add-media:hover { background: #e0e7ff !important; transform: translateY(-1px); }
</style>

<script>
// ════════════════════════════════════════════════════════════
//  PLAN CREATE — JavaScript complet
// ════════════════════════════════════════════════════════════

let mediaIndex = 0;
let markets = [];
let languages = [];
let marketingTools = [];
let concreteResults = [];
let destinations = [];

const PLAN_FORM_TOOLTIPS = {
    'name': 'Nom commercial du plan visible par les utilisateurs.',
    'description': 'Resume court du plan pour la liste et les cartes.',
    'price': 'Montant facture pour le cycle choisi.',
    'currency': 'Devise utilisee pour le prix et la facturation.',
    'duration_days': 'Duree de validite en jours (ex: 365).',
    'billing_cycle': 'Frequence de facturation: mensuel, annuel ou personnalise.',
    'services': 'Description detaillee des services inclus.',
    'vision_text': 'Texte de vision strategique affiche sur la page plan.',
    'vision_quote': 'Citation courte qui met en valeur le plan.',
    'vision_quote_author': 'Auteur ou marque associee a la citation.',
    'marketing_budget': 'Budget marketing indicatif annuel.',
    'marketing_features': 'Fonctionnalites marketing separees par virgule.',
    'space_type': 'Type d espace principal cible par ce plan.',
    'space_features': 'Fonctionnalites de l espace separees par virgules.',
    'sort_order': 'Ordre d affichage: plus petit = plus haut.',
    'is_active': 'Active ou masque le plan dans l application.',
    'is_popular': 'Marque ce plan comme recommande/vedette.',
    'plugin_ids[]': 'Selectionnez les applications incluses dans ce plan.'
};

function initFieldTooltips(scope = document) {
    if (!scope || !scope.querySelectorAll) return;

    const applyTip = (selector, text) => {
        scope.querySelectorAll(selector).forEach((el) => {
            if (!el.getAttribute('title')) el.setAttribute('title', text);
            if (!el.dataset.bsToggle) el.dataset.bsToggle = 'tooltip';
        });
    };

    Object.entries(PLAN_FORM_TOOLTIPS).forEach(([name, text]) => {
        applyTip(`[name="${name}"]`, text);
    });

    applyTip('.market-name', 'Nom du marche cible (ex: Canada).');
    applyTip('.market-population', 'Population cible ou taille de marche.');
    applyTip('.market-icon', 'Icone utilisee pour representer le marche.');
    applyTip('.language-value', 'Langue ou capacite linguistique proposee.');
    applyTip('.tool-name', 'Nom de l outil marketing inclus.');
    applyTip('.tool-icon', 'Icone representant cet outil.');
    applyTip('.tool-features', 'Une fonctionnalite par ligne.');
    applyTip('.result-value', 'Valeur KPI (ex: +237%).');
    applyTip('.result-label', 'Description du KPI.');
    applyTip('.dest-name', 'Nom de la destination.');
    applyTip('.dest-slug', 'Slug URL de la destination.');
    applyTip('.dest-country', 'Pays de la destination.');
    applyTip('.dest-city', 'Ville de la destination.');
    applyTip('.dest-description', 'Description courte de la destination.');
    applyTip('.dest-image-input', 'Image representative de la destination.');
    applyTip('.video-platform-select', 'Choisissez la source video.');
    applyTip('.video-url-section input[type="url"]', 'Lien de la video (YouTube, Vimeo, etc.).');

    if (window.bootstrap && window.bootstrap.Tooltip) {
        scope.querySelectorAll('[data-bs-toggle="tooltip"]').forEach((el) => {
            if (!window.bootstrap.Tooltip.getInstance(el)) {
                new window.bootstrap.Tooltip(el, { container: 'body' });
            }
        });
    }
}

$(function () {
    initFieldTooltips();

    // ── Quill WYSIWYG ──
    const quill = new Quill('#servicesEditor', {
        theme: 'snow',
        placeholder: 'Décrivez les services inclus…',
        modules: { toolbar: [[{ header: [1, 2, 3, false] }], ['bold', 'italic', 'underline', 'strike'], [{ list: 'ordered' }, { list: 'bullet' }, { list: 'check' }], ['link', 'blockquote', 'code-block', 'clean']] }
    });

    // ── Live preview ──
    $('input[name="name"]').on('input', function () { $('#previewName').text($(this).val() || 'Nom du plan'); });
    $('input[name="price"], select[name="currency"]').on('input change', updatePreviewPrice);
    $('select[name="billing_cycle"]').on('change', function () {
        const map = { monthly: 'par mois', yearly: 'par an', custom: 'durée personnalisée' };
        $('#previewCycle').text(map[this.value] || 'par an');
    });
    function updatePreviewPrice () {
        const p = $('input[name="price"]').val();
        const c = $('select[name="currency"]').val();
        $('#previewPrice').text(p ? new Intl.NumberFormat('fr-FR').format(p) + ' ' + c : '—');
    }

    // ── Onglets ──
    $('.tab-btn').on('click', function() {
        const tabId = $(this).data('tab');
        $('.tab-btn').removeClass('active');
        $(this).addClass('active');
        $('.tab-content').hide();
        $(`#tab-${tabId}`).show();
    });

    // ── Plugin search & counter ──
    $('#pluginSearch').on('input', function () {
        const q = $(this).val().toLowerCase();
        $('.plugin-card').each(function () { $(this).toggle($(this).text().toLowerCase().includes(q)); });
    });
    $(document).on('change', '.plugin-cb', function () {
        $(this).closest('.plugin-card').toggleClass('selected', this.checked);
        const n = $('.plugin-cb:checked').length;
        $('#pluginsCounter').text(n + ' plugin' + (n > 1 ? 's' : '') + ' sélectionné' + (n > 1 ? 's' : ''));
        const names = $('.plugin-cb:checked').map(function () { return $(this).closest('.plugin-card').find('.plugin-name').text(); }).get().slice(0, 3).join(', ');
        $('#previewPlugins').text(n ? '🧩 ' + names + (n > 3 ? '…' : '') : '');
    });

    // ── MARCHÉS ──
    function initMarkets() {
        markets = [
            { name: 'Canada', population: '~40M', icon: 'fa-globe-americas' },
            { name: 'États-Unis', population: '~335M', icon: 'fa-flag-usa' },
            { name: 'Europe', population: '~450M', icon: 'fa-euro-sign' },
            { name: 'Monde', population: '~8Md', icon: 'fa-chart-line' }
        ];
        renderMarkets();
        languages = ['Jusqu\'à 25 langues', 'Marchés émergents', 'Expansion internationale', 'ROI optimisé'];
        renderLanguages();
    }
    function renderMarkets() {
        const container = $('#marketsList'); container.empty();
        markets.forEach((market, idx) => {
            const tpl = document.getElementById('marketTemplate').content.cloneNode(true);
            const $item = $(tpl);
            $item.find('.market-name').val(market.name);
            $item.find('.market-population').val(market.population);
            $item.find('.market-icon').val(market.icon);
            $item.data('index', idx);
            container.append($item);
        });
        initFieldTooltips(container[0]);
    }
    function renderLanguages() {
        const container = $('#languagesList'); container.empty();
        languages.forEach((lang, idx) => {
            const tpl = document.getElementById('languageTemplate').content.cloneNode(true);
            const $item = $(tpl);
            $item.find('.language-value').val(lang);
            $item.data('index', idx);
            container.append($item);
        });
        initFieldTooltips(container[0]);
    }
    $('#addMarketBtn').on('click', () => { markets.push({ name: '', population: '', icon: 'fa-globe-americas' }); renderMarkets(); });
    $('#addLanguageBtn').on('click', () => { languages.push(''); renderLanguages(); });
    $(document).on('click', '.remove-market', function() { const idx = $(this).closest('.market-item').data('index'); markets.splice(idx, 1); renderMarkets(); });
    $(document).on('click', '.remove-language', function() { const idx = $(this).closest('.language-item').data('index'); languages.splice(idx, 1); renderLanguages(); });
    $(document).on('input change', '.market-name, .market-population, .market-icon', function() {
        const $item = $(this).closest('.market-item');
        const idx = $item.data('index');
        if (idx !== undefined && markets[idx]) {
            markets[idx].name = $item.find('.market-name').val();
            markets[idx].population = $item.find('.market-population').val();
            markets[idx].icon = $item.find('.market-icon').val();
        }
    });
    $(document).on('input', '.language-value', function() {
        const $item = $(this).closest('.language-item');
        const idx = $item.data('index');
        if (idx !== undefined && languages[idx] !== undefined) languages[idx] = $(this).val();
    });

    // ── OUTILS MARKETING ──
    function initTools() {
        marketingTools = [
            { name: 'Marketing digital intégré', icon: 'fa-bullhorn', features: ['SEO avancé & international', 'Publicité Google & Meta Ads', 'Email marketing automatisé', 'CRM & gestion des leads'] },
            { name: 'Intelligence & données', icon: 'fa-database', features: ['Tableaux de bord analytiques', 'Suivi performances marketing', 'Analyse des tendances marchés'] },
            { name: 'Automatisation & IA', icon: 'fa-robot', features: ['Création de contenu assistée par IA', 'Call-to-action optimisés', 'Segmentation client intelligente'] }
        ];
        renderTools();
    }
    function renderTools() {
        const container = $('#toolsList'); container.empty();
        marketingTools.forEach((tool, idx) => {
            const tpl = document.getElementById('toolTemplate').content.cloneNode(true);
            const $item = $(tpl);
            $item.find('.tool-name').val(tool.name);
            $item.find('.tool-icon').val(tool.icon);
            $item.find('.tool-features').val(tool.features.join('\n'));
            $item.data('index', idx);
            container.append($item);
        });
        initFieldTooltips(container[0]);
    }
    $('#addToolBtn').on('click', () => { marketingTools.push({ name: '', icon: 'fa-bullhorn', features: [] }); renderTools(); });
    $(document).on('click', '.remove-tool', function() { const idx = $(this).closest('.tool-item').data('index'); marketingTools.splice(idx, 1); renderTools(); });
    $(document).on('input change', '.tool-name, .tool-icon, .tool-features', function() {
        const $item = $(this).closest('.tool-item');
        const idx = $item.data('index');
        if (idx !== undefined && marketingTools[idx]) {
            marketingTools[idx].name = $item.find('.tool-name').val();
            marketingTools[idx].icon = $item.find('.tool-icon').val();
            const featuresText = $item.find('.tool-features').val();
            marketingTools[idx].features = featuresText ? featuresText.split('\n').filter(f => f.trim()) : [];
        }
    });

    // ── RÉSULTATS CONCRETS ──
    function initResults() {
        concreteResults = [
            { value: '+237%', label: 'de visibilité' },
            { value: '4.9★', label: 'satisfaction' },
            { value: '98%', label: 'rétention' },
            { value: '24/7', label: 'support' }
        ];
        renderResults();
    }
    function renderResults() {
        const container = $('#concreteResultsList'); container.empty();
        concreteResults.forEach((result, idx) => {
            const div = $(`
                <div class="concrete-result-item" style="background: #f8fafc; border-radius: 12px; padding: 12px; margin-bottom: 10px; display: flex; gap: 12px; align-items: center;">
                    <input type="text" class="form-control-modern result-value" style="flex: 1;" placeholder="Ex: +237%" value="${escapeHtml(result.value)}">
                    <input type="text" class="form-control-modern result-label" style="flex: 2;" placeholder="Ex: de visibilité" value="${escapeHtml(result.label)}">
                    <button type="button" class="remove-result" style="background: #fee2e2; border: none; padding: 8px 12px; border-radius: 6px; color: #dc2626; cursor: pointer;"><i class="fas fa-trash"></i></button>
                </div>
            `);
            div.data('index', idx);
            container.append(div);
        });
        initFieldTooltips(container[0]);
    }
    $('#addResultBtn').on('click', () => { concreteResults.push({ value: '', label: '' }); renderResults(); });
    $(document).on('click', '.remove-result', function() { const idx = $(this).closest('.concrete-result-item').data('index'); concreteResults.splice(idx, 1); renderResults(); });
    $(document).on('input', '.result-value, .result-label', function() {
        const $item = $(this).closest('.concrete-result-item');
        const idx = $item.data('index');
        if (idx !== undefined && concreteResults[idx]) {
            concreteResults[idx].value = $item.find('.result-value').val();
            concreteResults[idx].label = $item.find('.result-label').val();
        }
    });

    // ── DESTINATIONS ──
    function renderDestinations() {
        const container = $('#destinationsContainer'); container.empty();
        destinations.forEach((dest, idx) => {
            const tpl = document.getElementById('destinationTemplate').content.cloneNode(true);
            const $item = $(tpl);
            $item.find('.destination-id').val(dest.id || '');
            $item.find('.dest-name').val(dest.destination_name || '');
            $item.find('.dest-slug').val(dest.destination_slug || '');
            $item.find('.dest-country').val(dest.destination_country || '');
            $item.find('.dest-city').val(dest.destination_city || '');
            $item.find('.dest-description').val(dest.destination_description || '');
            $item.find('.dest-active').prop('checked', dest.is_active !== false);
            if (dest.destination_image) { $item.find('.upload-preview').attr('src', dest.destination_image).show(); $item.find('.upload-placeholder').hide(); }
            $item.find('.destination-name').text(dest.destination_name || 'Nouvelle destination');
            $item.data('index', idx);
            container.append($item);
        });
        initFieldTooltips(container[0]);
    }
    $('#addDestinationBtn').on('click', () => {
        destinations.push({ id: null, destination_name: '', destination_slug: '', destination_country: '', destination_city: '', destination_description: '', destination_image: null, is_active: true, sort_order: destinations.length });
        renderDestinations();
    });
    $(document).on('click', '.delete-destination', function() { const idx = $(this).closest('.destination-item').data('index'); destinations.splice(idx, 1); renderDestinations(); });
    $(document).on('input change', '.dest-name, .dest-slug, .dest-country, .dest-city, .dest-description, .dest-active', function() {
        const $item = $(this).closest('.destination-item');
        const idx = $item.data('index');
        if (idx !== undefined && destinations[idx]) {
            destinations[idx].destination_name = $item.find('.dest-name').val();
            destinations[idx].destination_slug = $item.find('.dest-slug').val();
            destinations[idx].destination_country = $item.find('.dest-country').val();
            destinations[idx].destination_city = $item.find('.dest-city').val();
            destinations[idx].destination_description = $item.find('.dest-description').val();
            destinations[idx].is_active = $item.find('.dest-active').is(':checked');
            $item.find('.destination-name').text(destinations[idx].destination_name || 'Nouvelle destination');
        }
    });
    $(document).on('click', '.dest-image-zone', function() { $(this).find('.dest-image-input').click(); });
    $(document).on('change', '.dest-image-input', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            const $zone = $(this).closest('.dest-image-zone');
            const $item = $(this).closest('.destination-item');
            const idx = $item.data('index');
            reader.onload = function(e) {
                $zone.find('.upload-preview').attr('src', e.target.result).show();
                $zone.find('.upload-placeholder').hide();
                if (idx !== undefined && destinations[idx]) destinations[idx].destination_image_file = file;
            };
            reader.readAsDataURL(file);
        }
    });
    $(document).on('click', '.move-up', function() {
        const $item = $(this).closest('.destination-item');
        const idx = $item.data('index');
        if (idx > 0) { const temp = destinations[idx]; destinations[idx] = destinations[idx-1]; destinations[idx-1] = temp; renderDestinations(); }
    });
    $(document).on('click', '.move-down', function() {
        const $item = $(this).closest('.destination-item');
        const idx = $item.data('index');
        if (idx < destinations.length - 1) { const temp = destinations[idx]; destinations[idx] = destinations[idx+1]; destinations[idx+1] = temp; renderDestinations(); }
    });

    // ── MEDIA MANAGER ──
    $('#addImage').on('click', () => addMediaItem('image'));
    $('#addVideo').on('click', () => addMediaItem('video'));
    function addMediaItem(type) {
        const tplId = type === 'image' ? 'tplImage' : 'tplVideo';
        const tpl = document.getElementById(tplId).content.cloneNode(true);
        const el = tpl.querySelector('.media-item');
        el.innerHTML = el.innerHTML.replace(/INDEX/g, mediaIndex);
        el.dataset.index = mediaIndex;
        document.getElementById('mediaItems').appendChild(el);
        const addedItem = document.getElementById('mediaItems').lastElementChild;
        wireMediaItem(addedItem, type);
        initFieldTooltips(addedItem);
        mediaIndex++;
    }
    function wireMediaItem(el, type) {
        const fileInput = el.querySelector('input[type=file][name*="[file]"]');
        const previewImg = el.querySelector('.upload-preview');
        const placeholder = el.querySelector('.upload-placeholder');
        if (type === 'image' && fileInput) {
            fileInput.addEventListener('change', function() {
                if (!this.files[0]) return;
                const r = new FileReader();
                r.onload = e => { previewImg.src = e.target.result; previewImg.style.display = 'block'; if (placeholder) placeholder.style.display = 'none'; };
                r.readAsDataURL(this.files[0]);
            });
        }
        if (type === 'video') {
            const platformSel = el.querySelector('.video-platform-select');
            const urlSec = el.querySelector('.video-url-section');
            const fileSec = el.querySelector('.video-file-section');
            const urlInput = el.querySelector('input[type=url]');
            const urlPreview = el.querySelector('.url-preview');
            const urlText = el.querySelector('.url-preview-text');
            const vidFile = el.querySelector('.video-file-section input[type=file]');
            const fileInfo = el.querySelector('.file-info');
            const thumbInput = el.querySelector('input[name*="[thumbnail]"]');
            const thumbPrev = el.querySelector('.media-upload-zone.small .upload-preview');
            const thumbPlc = el.querySelector('.media-upload-zone.small .upload-placeholder');
            platformSel && platformSel.addEventListener('change', function() {
                const isUp = this.value === 'upload';
                if (urlSec) urlSec.style.display = isUp ? 'none' : 'block';
                if (fileSec) fileSec.style.display = isUp ? 'block' : 'none';
                if (urlInput) urlInput.required = !isUp;
                if (vidFile) vidFile.required = isUp;
            });
            urlInput && urlInput.addEventListener('input', function() {
                if (this.value && urlPreview) { urlPreview.style.display = 'block'; urlText.textContent = this.value; }
                else if (urlPreview) urlPreview.style.display = 'none';
            });
            vidFile && vidFile.addEventListener('change', function() {
                const f = this.files[0];
                if (f && fileInfo) { fileInfo.textContent = f.name + ' (' + (f.size/1024/1024).toFixed(2) + ' MB)'; fileInfo.style.display = 'block'; }
            });
            thumbInput && thumbInput.addEventListener('change', function() {
                if (!this.files[0] || !thumbPrev) return;
                const r = new FileReader();
                r.onload = e => { thumbPrev.src = e.target.result; thumbPrev.style.display = 'block'; if (thumbPlc) thumbPlc.style.display = 'none'; };
                r.readAsDataURL(this.files[0]);
            });
        }
    }
    window.setPrimaryMedia = function(btn) {
        document.querySelectorAll('.mi-btn.mi-primary').forEach(b => b.classList.remove('active'));
        document.querySelectorAll('.is-primary-input').forEach(i => i.value = '0');
        btn.classList.add('active');
        btn.closest('.media-item').querySelector('.is-primary-input').value = '1';
    };
    window.removeMediaItem = function(btn) { btn.closest('.media-item').remove(); reindexMedia(); };
    function reindexMedia() {
        document.querySelectorAll('.media-item').forEach((el, i) => {
            el.dataset.index = i;
            el.querySelectorAll('[name]').forEach(inp => inp.name = inp.name.replace(/media\[\d+\]/, 'media[' + i + ']'));
            el.querySelector('.sort-order-input').value = i;
        });
        mediaIndex = document.querySelectorAll('.media-item').length;
    }
    $(document).on('dragover dragenter', '.media-upload-zone', function(e) { e.preventDefault(); $(this).addClass('drag-over'); })
        .on('dragleave drop', '.media-upload-zone', function(e) { e.preventDefault(); $(this).removeClass('drag-over'); if (e.type === 'drop') { const fi = $(this).find('input[type=file]')[0]; if (fi) { fi.files = e.originalEvent.dataTransfer.files; $(fi).trigger('change'); } } });

    // ── FORM SUBMIT ──
    $('#planForm').on('submit', function(e) {
        e.preventDefault();
        clearErrors();
        $('#servicesInput').val(quill.root.innerHTML);
        const name = $('input[name="name"]').val().trim();
        const price = $('input[name="price"]').val();
        if (!name) { showFieldError('name', 'Le nom est requis'); return; }
        if (!price) { showFieldError('price', 'Le prix est requis'); return; }
        const formData = new FormData(this);
        formData.set('markets', JSON.stringify(markets));
        formData.set('market_languages', JSON.stringify(languages));
        formData.set('marketing_tools', JSON.stringify(marketingTools));
        formData.set('concrete_results', JSON.stringify(concreteResults));
        const destinationsData = destinations.map((d, i) => ({ ...d, sort_order: i }));
        formData.set('destinations', JSON.stringify(destinationsData));
        $('#submitBtn').prop('disabled', true);
        $('.btn-text').hide(); $('.btn-spinner').show();
        $.ajax({
            url: '{{ route("plans.store") }}',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(res) { if (res.success) { showToast('success', res.message || 'Plan créé !'); setTimeout(() => window.location.href = res.redirect || '{{ route("plans.index") }}', 1500); } },
            error: function(xhr) {
                resetBtn();
                if (xhr.status === 422) { const errors = xhr.responseJSON.errors; Object.keys(errors).forEach(f => showFieldError(f, errors[f][0])); showToast('error', 'Veuillez corriger les erreurs du formulaire'); }
                else { showToast('error', 'Une erreur est survenue'); }
            }
        });
    });

    function resetBtn() { $('#submitBtn').prop('disabled', false); $('.btn-text').show(); $('.btn-spinner').hide(); }
    function clearErrors() { $('.error-feedback').html(''); $('.form-control-modern, .form-select-modern').removeClass('error'); }
    function showFieldError(field, msg) { $(`.error-feedback[data-field="${field}"]`).html('<span class="error-message">' + msg + '</span>'); $(`[name="${field}"]`).addClass('error'); }
    function showToast(type, message) {
        const icon = type === 'success' ? '✓' : '✗';
        const cls = type === 'success' ? 'toast-success' : 'toast-error';
        const title = type === 'success' ? 'Succès' : 'Erreur';
        const toast = $(`<div class="toast-notification ${cls}"><div class="toast-icon">${icon}</div><div class="toast-content"><div class="toast-title">${title}</div><div class="toast-message">${escapeHtml(message)}</div></div><button class="toast-close">&times;</button></div>`);
        $('#toastContainer').append(toast);
        setTimeout(() => toast.addClass('show'), 10);
        const t = setTimeout(() => removeToast(toast), 5000);
        toast.find('.toast-close').click(() => { clearTimeout(t); removeToast(toast); });
    }
    function removeToast(t) { t.removeClass('show'); setTimeout(() => t.remove(), 300); }
    function escapeHtml(s) { const d = document.createElement('div'); d.textContent = s; return d.innerHTML; }

    // Initialisation
    initMarkets();
    initTools();
    initResults();
});
</script>
@endsection
