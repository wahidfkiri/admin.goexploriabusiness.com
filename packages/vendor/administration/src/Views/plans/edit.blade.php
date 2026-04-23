{{-- resources/views/vendor/administration/plans/edit.blade.php --}}
@extends('layouts.app')

@section('content')
<main class="dashboard-content">

    <div class="form-header-modern">
        <a href="{{ route('plans.index') }}" class="back-btn"><i class="fas fa-arrow-left"></i><span>Retour aux plans</span></a>
        <div class="form-header-info">
            <div class="form-icon"><i class="fas fa-edit"></i></div>
            <div><h1 class="form-title">Modifier le plan</h1><p class="form-subtitle">Mise à jour de <strong>{{ $plan->name }}</strong></p></div>
        </div>
    </div>

    <form id="planForm" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')
        <div class="form-layout">
            <div class="form-main">

                {{-- 1. INFORMATIONS GÉNÉRALES --}}
                <div class="form-section">
                    <div class="section-header"><div class="section-icon"><i class="fas fa-info-circle"></i></div><div><h3 class="section-title">Informations générales</h3><p class="section-desc">Nom, description et tarification</p></div></div>
                    <div class="section-body">
                        <div class="form-row"><label class="form-label required">Nom du plan</label><input type="text" name="name" class="form-control-modern" value="{{ old('name', $plan->name) }}" required><div class="error-feedback" data-field="name"></div></div>
                        <div class="form-row"><label class="form-label">Description</label><textarea name="description" class="form-control-modern" rows="3">{{ old('description', $plan->description) }}</textarea></div>
                        <div class="grid-2">
                            <div class="form-group"><label class="form-label required">Prix</label><div class="input-group-modern"><input type="number" name="price" class="form-control-modern" step="0.01" value="{{ old('price', $plan->price) }}" required><select name="currency" class="form-select-modern" style="width:100px">@foreach(['CAD','EUR','USD'] as $c)<option value="{{ $c }}" {{ $plan->currency == $c ? 'selected' : '' }}>{{ $c }}</option>@endforeach</select></div><div class="error-feedback" data-field="price"></div></div>
                            <div class="form-group"><label class="form-label required">Durée (jours)</label><input type="number" name="duration_days" class="form-control-modern" value="{{ old('duration_days', $plan->duration_days) }}" required></div>
                        </div>
                        <div class="form-row"><label class="form-label required">Cycle de facturation</label><select name="billing_cycle" class="form-select-modern" required>@foreach(['monthly'=>'Mensuel','yearly'=>'Annuel','custom'=>'Personnalisé'] as $val=>$label)<option value="{{ $val }}" {{ $plan->billing_cycle == $val ? 'selected' : '' }}>{{ $label }}</option>@endforeach</select></div>
                    </div>
                </div>

                {{-- 2. SERVICES WYSIWYG --}}
                <div class="form-section">
                    <div class="section-header"><div class="section-icon"><i class="fas fa-list-check"></i></div><div><h3 class="section-title">Services inclus</h3><p class="section-desc">Éditeur riche</p></div></div>
                    <div class="section-body"><div id="servicesEditor" class="wysiwyg-editor">{!! old('services', $plan->services) !!}</div><textarea name="services" id="servicesInput" style="display:none"></textarea></div>
                </div>

                {{-- 3. PLUGINS --}}
                <div class="form-section">
                    <div class="section-header"><div class="section-icon"><i class="fas fa-puzzle-piece"></i></div><div><h3 class="section-title">Applications / Plugins inclus</h3><p class="section-desc">{{ $plan->plugins->count() }} plugin(s) sélectionné(s)</p></div></div>
                    <div class="section-body">
                        <div class="plugin-search-wrap"><input type="text" id="pluginSearch" class="form-control-modern" placeholder="Filtrer les plugins…"></div>
                        <div class="plugins-grid" id="pluginsGrid">
                            @forelse($plugins as $plugin)
                            <label class="plugin-card {{ in_array($plugin->id, $plan->pluginIds) ? 'selected' : '' }}" for="plugin_{{ $plugin->id }}">
                                <input type="checkbox" id="plugin_{{ $plugin->id }}" name="plugin_ids[]" value="{{ $plugin->id }}" class="plugin-cb" {{ in_array($plugin->id, $plan->pluginIds) ? 'checked' : '' }}>
                                <div class="plugin-card-inner">
                                    <div class="plugin-icon">@if($plugin->icon)<i class="{{ $plugin->icon }}"></i>@else<i class="fas fa-plug"></i>@endif</div>
                                    <div class="plugin-info"><div class="plugin-name">{{ $plugin->name }}</div><div class="plugin-meta">@if($plugin->category)<span class="plugin-cat">{{ $plugin->category->name }}</span>@endif<span class="plugin-price {{ $plugin->price_type }}">{{ $plugin->price_type === 'free' ? 'Gratuit' : number_format($plugin->price, 0, ',', ' ') . ' ' . ($plugin->currency ?? '') }}</span></div>@if($plugin->description)<p class="plugin-desc">{{ Str::limit($plugin->description, 80) }}</p>@endif</div>
                                    <div class="plugin-check"><i class="fas fa-check-circle"></i></div>
                                </div>
                            </label>
                            @empty<div class="plugins-empty"><i class="fas fa-puzzle-piece"></i><p>Aucun plugin actif</p></div>@endforelse
                        </div>
                        <div class="plugins-counter" id="pluginsCounter">{{ $plan->plugins->count() }} plugin(s) sélectionné(s)</div>
                    </div>
                </div>

                {{-- 4. PRÉSENTATION (onglets) --}}
                <div class="form-section">
                    <div class="section-header"><div class="section-icon"><i class="fas fa-eye"></i></div><div><h3 class="section-title">Présentation du plan</h3><p class="section-desc">Vision, marketing, marchés, outils, espace, résultats</p></div></div>
                    <div class="section-body">
                        <div class="presentation-tabs">
                            <button type="button" class="tab-btn active" data-tab="vision">🎯 Vision</button>
                            <button type="button" class="tab-btn" data-tab="marketing">💰 Investissement Marketing</button>
                            <button type="button" class="tab-btn" data-tab="markets">🌍 Marchés</button>
                            <button type="button" class="tab-btn" data-tab="tools">🧰 Outils Marketing</button>
                            <button type="button" class="tab-btn" data-tab="space">🏢 Espace</button>
                            <button type="button" class="tab-btn" data-tab="results">📊 Résultats</button>
                        </div>
                        <div id="tab-vision" class="tab-content active">
                            <div class="form-row"><label>Texte de vision</label><textarea name="vision_text" class="form-control-modern" rows="4">{{ old('vision_text', $plan->vision_text) }}</textarea></div>
                            <div class="grid-2"><div><label>Citation</label><input type="text" name="vision_quote" class="form-control-modern" value="{{ old('vision_quote', $plan->vision_quote) }}"></div><div><label>Auteur</label><input type="text" name="vision_quote_author" class="form-control-modern" value="{{ old('vision_quote_author', $plan->vision_quote_author) }}"></div></div>
                        </div>
                        <div id="tab-marketing" class="tab-content" style="display:none">
                            <div class="grid-2"><div><label>Budget annuel (CAD)</label><input type="number" name="marketing_budget" class="form-control-modern" step="1000" value="{{ old('marketing_budget', $plan->marketing_budget) }}"></div><div><label>Features (virgules)</label><input type="text" name="marketing_features" class="form-control-modern" value="{{ old('marketing_features', is_array($plan->marketing_features) ? implode(',', $plan->marketing_features) : $plan->marketing_features) }}"></div></div>
                        </div>
                        <div id="tab-markets" class="tab-content" style="display:none">
                            <div><label>Marchés (JSON)</label><textarea name="markets" class="form-control-modern" rows="6">{{ old('markets', json_encode($plan->markets, JSON_PRETTY_PRINT)) }}</textarea><small>Format: [{"name":"Canada","population":"~40M","icon":"fa-globe-americas"}]</small></div>
                            <div class="mt-3"><label>Langues (JSON)</label><textarea name="market_languages" class="form-control-modern" rows="3">{{ old('market_languages', json_encode($plan->market_languages, JSON_PRETTY_PRINT)) }}</textarea></div>
                        </div>
                        <div id="tab-tools" class="tab-content" style="display:none">
                            <div><label>Outils marketing (JSON)</label><textarea name="marketing_tools" class="form-control-modern" rows="10">{{ old('marketing_tools', json_encode($plan->marketing_tools, JSON_PRETTY_PRINT)) }}</textarea></div>
                        </div>
                        <div id="tab-space" class="tab-content" style="display:none">
                            <div class="grid-2"><div><label>Type d'espace</label><select name="space_type" class="form-select-modern"><option value="entreprise" {{ $plan->space_type=='entreprise'?'selected':'' }}>🏢 Entreprise</option><option value="destination" {{ $plan->space_type=='destination'?'selected':'' }}>🏝️ Destination</option><option value="partenaire" {{ $plan->space_type=='partenaire'?'selected':'' }}>🤝 Partenaire</option><option value="perso" {{ $plan->space_type=='perso'?'selected':'' }}>👤 Perso</option></select></div><div><label>Features (virgules)</label><input type="text" name="space_features" class="form-control-modern" value="{{ old('space_features', is_array($plan->space_features) ? implode(',', $plan->space_features) : $plan->space_features) }}"></div></div>
                        </div>
                        <div id="tab-results" class="tab-content" style="display:none">
                            <div><label>Résultats concrets (JSON)</label><textarea name="concrete_results" class="form-control-modern" rows="5">{{ old('concrete_results', json_encode($plan->concrete_results, JSON_PRETTY_PRINT)) }}</textarea></div>
                        </div>
                    </div>
                </div>

                {{-- 5. MÉDIAS EXISTANTS --}}
                @if($plan->media->count() > 0)
                <div class="form-section">
                    <div class="section-header"><div class="section-icon"><i class="fas fa-images"></i></div><div><h3 class="section-title">Médias actuels</h3><p class="section-desc">{{ $plan->media->count() }} fichier(s)</p></div></div>
                    <div class="section-body">
                        <div class="existing-media-grid" id="existingMediaGrid">
                            @foreach($plan->media as $media)
                            <div class="existing-media-card {{ $media->is_primary ? 'is-primary' : '' }}" data-media-id="{{ $media->id }}">
                                @if($media->type === 'image')
                                    <img src="{{ $media->file_url }}" class="existing-media-thumb" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex'">
                                    <div class="existing-media-placeholder" style="display:none"><i class="fas fa-image"></i></div>
                                @else
                                    @if($media->thumbnail_url)<img src="{{ $media->thumbnail_url }}" class="existing-media-thumb">@else<div class="existing-media-placeholder">@if($media->is_youtube)<i class="fab fa-youtube"></i>@elseif($media->is_vimeo)<i class="fab fa-vimeo"></i>@else<i class="fas fa-film"></i>@endif</div>@endif
                                @endif
                                <div class="existing-media-actions"><button type="button" class="em-action-btn em-btn-star" onclick="setExistingPrimary({{ $media->id }}, this)" title="Principal"><i class="fas fa-star"></i></button><button type="button" class="em-action-btn em-btn-delete" onclick="deleteExistingMedia({{ $media->id }}, this)" title="Supprimer"><i class="fas fa-times"></i></button></div>
                                <div class="existing-media-footer">@if($media->is_primary)<span class="em-badge em-badge-primary">Principal</span>@endif<span class="em-badge {{ $media->type === 'image' ? 'em-badge-img' : 'em-badge-video' }}">{{ $media->type === 'image' ? 'Image' : ucfirst($media->video_platform ?? 'Vidéo') }}</span>@if($media->file_size_formatted !== '—')<span class="em-badge">{{ $media->file_size_formatted }}</span>@endif</div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                {{-- 6. AJOUTER MÉDIAS --}}
                <div class="form-section">
                    <div class="section-header"><div class="section-icon"><i class="fas fa-plus-circle"></i></div><div><h3 class="section-title">Ajouter des médias</h3><p class="section-desc">Nouvelles images ou vidéos</p></div></div>
                    <div class="section-body"><div id="mediaItems"></div><div class="media-add-btns"><button type="button" class="btn-add-media" id="addImage"><i class="fas fa-image"></i> Ajouter une image</button><button type="button" class="btn-add-media" id="addVideo"><i class="fas fa-video"></i> Ajouter une vidéo</button></div></div>
                </div>

                {{-- 7. DESTINATIONS --}}
                <div class="form-section">
                    <div class="section-header"><div class="section-icon"><i class="fas fa-map-marker-alt"></i></div><div><h3 class="section-title">Destinations associées</h3><p class="section-desc">Gérez les destinations du plan</p></div></div>
                    <div class="section-body">
                        <div id="destinationsContainer"></div>
                        <button type="button" id="addDestinationBtn" class="btn-add-media" style="margin-top:16px"><i class="fas fa-plus-circle"></i> Ajouter une destination</button>
                    </div>
                </div>
            </div>

            {{-- SIDEBAR --}}
            <div class="form-sidebar">
                <div class="sidebar-card"><div class="sidebar-card-header"><i class="fas fa-sliders-h"></i><h4>Statut</h4></div><div class="sidebar-card-body"><div class="toggle-switch"><label class="switch-label"><input type="checkbox" name="is_active" value="1" {{ $plan->is_active ? 'checked' : '' }}><span class="switch-slider"></span><span class="switch-text">Plan actif</span></label></div><div class="toggle-switch mt-3"><label class="switch-label"><input type="checkbox" name="is_popular" value="1" {{ $plan->is_popular ? 'checked' : '' }}><span class="switch-slider"></span><span class="switch-text">Plan populaire</span></label></div></div></div>
                <div class="sidebar-card"><div class="sidebar-card-header"><i class="fas fa-sort"></i><h4>Ordre</h4></div><div class="sidebar-card-body"><input type="number" name="sort_order" class="form-control-modern" value="{{ old('sort_order', $plan->sort_order) }}"></div></div>
                <div class="sidebar-card"><div class="sidebar-card-header"><i class="fas fa-chart-line"></i><h4>Statistiques</h4></div><div class="sidebar-card-body"><div class="stat-row"><span class="stat-label">Total abonnés</span><span class="stat-value">{{ $plan->abonnements_count ?? 0 }}</span></div><div class="stat-row"><span class="stat-label">Actifs</span><span class="stat-value text-success">{{ $plan->active_abonnements_count ?? 0 }}</span></div><div class="stat-row"><span class="stat-label">Chiffre d'affaires</span><span class="stat-value">{{ number_format($plan->total_revenue ?? 0, 0, ',', ' ') }} {{ $plan->currency }}</span></div><div class="stat-row"><span class="stat-label">Plugins inclus</span><span class="stat-value">{{ $plan->plugins->count() }}</span></div><div class="stat-row"><span class="stat-label">Médias</span><span class="stat-value">{{ $plan->media->count() }}</span></div></div></div>
            </div>
        </div>

        <div class="form-actions">
            <a href="{{ route('plans.index') }}" class="btn-cancel"><i class="fas fa-times"></i> Annuler</a>
            <button type="submit" class="btn-submit" id="submitBtn"><span class="btn-text"><i class="fas fa-save"></i> Enregistrer</span><span class="btn-spinner" style="display:none"><i class="fas fa-spinner fa-spin"></i> Enregistrement…</span></button>
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


<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
// Copier les mêmes fonctions que create.blade.php + chargement des destinations existantes
const PLAN_ID = {{ $plan->id }};
const CSRF = '{{ csrf_token() }}';

$(function() {
    // Quill
    const quill = new Quill('#servicesEditor', { theme: 'snow', modules: { toolbar: [[{ header: [1,2,3,false]}], ['bold','italic','underline','strike'], [{ list: 'ordered'},{ list: 'bullet'},{ list: 'check'}], ['link','blockquote','code-block','clean']] } });

    // Onglets
    $('.tab-btn').click(function() { const tab = $(this).data('tab'); $('.tab-btn').removeClass('active'); $(this).addClass('active'); $('.tab-content').hide(); $(`#tab-${tab}`).show(); });

    // Plugins search & counter
    $('#pluginSearch').on('input', function() { const q = $(this).val().toLowerCase(); $('.plugin-card').each(function() { $(this).toggle($(this).text().toLowerCase().includes(q)); }); });
    $(document).on('change', '.plugin-cb', function() { $(this).closest('.plugin-card').toggleClass('selected', this.checked); updatePluginsCounter(); });
    function updatePluginsCounter() { const n = $('.plugin-cb:checked').length; $('#pluginsCounter').text(n + ' plugin' + (n>1?'s':'') + ' sélectionné' + (n>1?'s':'')); }

    // Médias existants (AJAX)
    window.setExistingPrimary = function(mediaId, btn) { $.ajax({ url: `/admin/plans/${PLAN_ID}/media/${mediaId}/primary`, type: 'POST', data: { _token: CSRF }, success: function(res) { if(res.success) { $('.existing-media-card').removeClass('is-primary').find('.em-badge-primary').remove(); btn.closest('.existing-media-card').addClass('is-primary').find('.existing-media-footer').prepend('<span class="em-badge em-badge-primary">Principal</span>'); showToast('success','Média principal défini'); } } }); };
    window.deleteExistingMedia = function(mediaId, btn) { if(!confirm('Supprimer ce média ?')) return; $.ajax({ url: `/admin/plans/${PLAN_ID}/media/${mediaId}`, type: 'DELETE', data: { _token: CSRF }, success: function(res) { if(res.success) { btn.closest('.existing-media-card').remove(); showToast('success','Média supprimé'); } } }); };

    // Destinations : chargement et gestion
    let destinations = [];
    function loadDestinations() { $.ajax({ url: `/admin/plans/${PLAN_ID}/destinations`, success: function(res) { if(res.success) { destinations = res.destinations; renderDestinations(); } } }); }
    function renderDestinations() { const container = $('#destinationsContainer'); container.empty(); destinations.forEach((dest, idx) => { const $item = $(document.getElementById('destinationTemplate').content.cloneNode(true)); $item.find('.destination-id').val(dest.id); $item.find('.dest-name').val(dest.destination_name); $item.find('.dest-slug').val(dest.destination_slug); $item.find('.dest-country').val(dest.destination_country); $item.find('.dest-city').val(dest.destination_city); $item.find('.dest-description').val(dest.destination_description); $item.find('.dest-active').prop('checked', dest.is_active); if(dest.destination_image) { $item.find('.upload-preview').attr('src', dest.destination_image).show(); $item.find('.upload-placeholder').hide(); } $item.find('.destination-name').text(dest.destination_name); $item.data('index', idx); container.append($item); }); }
    $('#addDestinationBtn').click(() => { destinations.push({ id: null, destination_name: '', destination_slug: '', destination_country: '', destination_city: '', destination_description: '', is_active: true }); renderDestinations(); });
    $(document).on('click', '.delete-destination', function() { const idx = $(this).closest('.destination-item').data('index'); destinations.splice(idx,1); renderDestinations(); });
    $(document).on('input change', '.dest-name, .dest-slug, .dest-country, .dest-city, .dest-description, .dest-active', function() { const $item = $(this).closest('.destination-item'); const idx = $item.data('index'); if(idx!==undefined && destinations[idx]){ destinations[idx].destination_name = $item.find('.dest-name').val(); destinations[idx].destination_slug = $item.find('.dest-slug').val(); destinations[idx].destination_country = $item.find('.dest-country').val(); destinations[idx].destination_city = $item.find('.dest-city').val(); destinations[idx].destination_description = $item.find('.dest-description').val(); destinations[idx].is_active = $item.find('.dest-active').is(':checked'); $item.find('.destination-name').text(destinations[idx].destination_name); } });
    $(document).on('click', '.dest-image-zone', function() { $(this).find('.dest-image-input').click(); });
    $(document).on('change', '.dest-image-input', function() { const file = this.files[0]; if(file) { const reader = new FileReader(); const $zone = $(this).closest('.dest-image-zone'); const $item = $(this).closest('.destination-item'); const idx = $item.data('index'); reader.onload = function(e) { $zone.find('.upload-preview').attr('src', e.target.result).show(); $zone.find('.upload-placeholder').hide(); if(idx!==undefined && destinations[idx]) destinations[idx]._image_file = file; }; reader.readAsDataURL(file); } });
    $(document).on('click', '.move-up', function() { const $item = $(this).closest('.destination-item'); const idx = $item.data('index'); if(idx>0) { const temp = destinations[idx]; destinations[idx] = destinations[idx-1]; destinations[idx-1] = temp; renderDestinations(); } });
    $(document).on('click', '.move-down', function() { const $item = $(this).closest('.destination-item'); const idx = $item.data('index'); if(idx<destinations.length-1) { const temp = destinations[idx]; destinations[idx] = destinations[idx+1]; destinations[idx+1] = temp; renderDestinations(); } });
    loadDestinations();

    // Gestion des nouveaux médias (identique à create)
    let mediaIndex = 0;
    $('#addImage').on('click', () => addMediaItem('image'));
    $('#addVideo').on('click', () => addMediaItem('video'));
    function addMediaItem(type) { /* même code que create */ }
    function wireMediaItem(el, type) { /* même code */ }
    window.setPrimaryMedia = function(btn) { /* même code */ };
    window.removeMediaItem = function(btn) { btn.closest('.media-item').remove(); reindexMedia(); };
    function reindexMedia() { /* même code */ }

    // Soumission formulaire
    $('#planForm').on('submit', function(e) { e.preventDefault(); clearErrors(); $('#servicesInput').val(quill.root.innerHTML); const formData = new FormData(this); // ajouter les champs JSON et destinations
        formData.set('markets', JSON.stringify(JSON.parse($('textarea[name="markets"]').val() || '[]')));
        formData.set('market_languages', JSON.stringify(JSON.parse($('textarea[name="market_languages"]').val() || '[]')));
        formData.set('marketing_tools', JSON.stringify(JSON.parse($('textarea[name="marketing_tools"]').val() || '[]')));
        formData.set('concrete_results', JSON.stringify(JSON.parse($('textarea[name="concrete_results"]').val() || '[]')));
        formData.set('destinations', JSON.stringify(destinations));
        $('#submitBtn').prop('disabled', true); $('.btn-text').hide(); $('.btn-spinner').show();
        $.ajax({ url: `/admin/plans/${PLAN_ID}`, type: 'POST', data: formData, processData: false, contentType: false, success: function(res) { if(res.success) { showToast('success','Plan mis à jour'); setTimeout(() => window.location.href = '{{ route("plans.index") }}', 1500); } }, error: function(xhr) { resetBtn(); if(xhr.status===422) { const e=xhr.responseJSON.errors; Object.keys(e).forEach(f=>showFieldError(f,e[f][0])); showToast('error','Corrigez les erreurs'); } else showToast('error','Erreur serveur'); } });
    });
    function resetBtn() { $('#submitBtn').prop('disabled',false); $('.btn-text').show(); $('.btn-spinner').hide(); }
    function clearErrors() { $('.error-feedback').html(''); $('.form-control-modern,.form-select-modern').removeClass('error'); }
    function showFieldError(field,msg) { $(`.error-feedback[data-field="${field}"]`).html('<span class="error-message">'+msg+'</span>'); $(`[name="${field}"]`).addClass('error'); }
    function showToast(type,message) { /* toast standard */ }
});
</script>
@endsection