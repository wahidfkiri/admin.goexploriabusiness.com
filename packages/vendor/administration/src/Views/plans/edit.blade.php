{{-- packages/vendor/administration/src/Views/plans/edit.blade.php --}}
@extends('layouts.app')

@section('content')
<main class="dashboard-content">

    {{-- ── HEADER ── --}}
    <div class="form-header-modern">
        <a href="{{ route('plans.index') }}" class="back-btn">
            <i class="fas fa-arrow-left"></i><span>Retour aux plans</span>
        </a>
        <div class="form-header-info">
            <div class="form-icon"><i class="fas fa-edit"></i></div>
            <div>
                <h1 class="form-title">Modifier le plan</h1>
                <p class="form-subtitle">Mise à jour de <strong>{{ $plan->name }}</strong></p>
            </div>
        </div>
    </div>

    {{-- ── FORM ── --}}
    <form id="planForm" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-layout">

            {{-- ═══════════════ LEFT COLUMN ═══════════════ --}}
            <div class="form-main">

                {{-- ── 1. INFORMATIONS GÉNÉRALES ── --}}
                <div class="form-section">
                    <div class="section-header">
                        <div class="section-icon"><i class="fas fa-info-circle"></i></div>
                        <div>
                            <h3 class="section-title">Informations générales</h3>
                            <p class="section-desc">Nom, description et tarification</p>
                        </div>
                    </div>
                    <div class="section-body">
                        <div class="form-row">
                            <label class="form-label required">Nom du plan</label>
                            <input type="text" name="name" class="form-control-modern"
                                   value="{{ old('name', $plan->name) }}" required>
                            <div class="error-feedback" data-field="name"></div>
                        </div>

                        <div class="form-row">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control-modern" rows="3">{{ old('description', $plan->description) }}</textarea>
                        </div>

                        <div class="grid-2">
                            <div class="form-group">
                                <label class="form-label required">Prix</label>
                                <div class="input-group-modern">
                                    <input type="number" name="price" class="form-control-modern"
                                           step="0.01" value="{{ old('price', $plan->price) }}" required>
                                    <select name="currency" class="form-select-modern" style="width:100px">
                                        @foreach(['CAD','EUR','USD'] as $c)
                                        <option value="{{ $c }}" {{ $plan->currency == $c ? 'selected' : '' }}>{{ $c }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="error-feedback" data-field="price"></div>
                            </div>
                            <div class="form-group">
                                <label class="form-label required">Durée (jours)</label>
                                <input type="number" name="duration_days" class="form-control-modern"
                                       value="{{ old('duration_days', $plan->duration_days) }}" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <label class="form-label required">Cycle de facturation</label>
                            <select name="billing_cycle" class="form-select-modern" required>
                                @foreach(['monthly' => 'Mensuel', 'yearly' => 'Annuel', 'custom' => 'Personnalisé'] as $val => $label)
                                <option value="{{ $val }}" {{ $plan->billing_cycle == $val ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
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
                            <p class="section-desc">Éditeur riche de contenu</p>
                        </div>
                    </div>
                    <div class="section-body">
                        <div id="servicesEditor" class="wysiwyg-editor">{!! old('services', $plan->services) !!}</div>
                        <textarea name="services" id="servicesInput" style="display:none"></textarea>
                    </div>
                </div>

                {{-- ── 3. PLUGINS ── --}}
                <div class="form-section">
                    <div class="section-header">
                        <div class="section-icon"><i class="fas fa-puzzle-piece"></i></div>
                        <div>
                            <h3 class="section-title">Applications / Plugins inclus</h3>
                            <p class="section-desc">{{ $plan->plugins->count() }} plugin(s) actuellement sélectionné(s)</p>
                        </div>
                    </div>
                    <div class="section-body">
                        <div class="plugin-search-wrap">
                            <input type="text" id="pluginSearch" class="form-control-modern" placeholder="Filtrer les plugins…">
                        </div>
                        <div class="plugins-grid" id="pluginsGrid">
                            @forelse($plugins as $plugin)
                            @php $checked = $plan->pluginIds; @endphp
                            <label class="plugin-card {{ in_array($plugin->id, $checked) ? 'selected' : '' }}"
                                   for="plugin_{{ $plugin->id }}">
                                <input type="checkbox" id="plugin_{{ $plugin->id }}"
                                       name="plugin_ids[]" value="{{ $plugin->id }}"
                                       class="plugin-cb"
                                       {{ in_array($plugin->id, $checked) ? 'checked' : '' }}>
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
                                <i class="fas fa-puzzle-piece"></i><p>Aucun plugin actif disponible</p>
                            </div>
                            @endforelse
                        </div>
                        <div class="plugins-counter" id="pluginsCounter">
                            {{ $plan->plugins->count() }} plugin(s) sélectionné(s)
                        </div>
                    </div>
                </div>

                {{-- ── 4. MÉDIATHÈQUE EXISTANTE ── --}}
                @if($plan->media->count() > 0)
                <div class="form-section">
                    <div class="section-header">
                        <div class="section-icon"><i class="fas fa-images"></i></div>
                        <div>
                            <h3 class="section-title">Médias actuels</h3>
                            <p class="section-desc">{{ $plan->media->count() }} fichier(s) — cliquez sur ★ pour définir le principal, sur ✕ pour supprimer</p>
                        </div>
                    </div>
                    <div class="section-body">
                        <div class="existing-media-grid" id="existingMediaGrid">
                            @foreach($plan->media as $media)
                            <div class="existing-media-card {{ $media->is_primary ? 'is-primary' : '' }}"
                                 data-media-id="{{ $media->id }}">

                                {{-- Thumb --}}
                                @if($media->type === 'image')
                                    <img src="{{ $media->file_url }}" alt="media"
                                         class="existing-media-thumb"
                                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex'">
                                    <div class="existing-media-placeholder" style="display:none">
                                        <i class="fas fa-image"></i>
                                    </div>
                                @else
                                    @if($media->thumbnail_url)
                                        <img src="{{ $media->thumbnail_url }}" alt="thumb"
                                             class="existing-media-thumb">
                                    @else
                                        <div class="existing-media-placeholder">
                                            @if($media->is_youtube) <i class="fab fa-youtube"></i>
                                            @elseif($media->is_vimeo) <i class="fab fa-vimeo"></i>
                                            @else <i class="fas fa-film"></i>
                                            @endif
                                        </div>
                                    @endif
                                @endif

                                {{-- Hover actions --}}
                                <div class="existing-media-actions">
                                    <button type="button" class="em-action-btn em-btn-star"
                                            onclick="setExistingPrimary({{ $media->id }}, this)"
                                            title="Définir comme principal">
                                        <i class="fas fa-star"></i>
                                    </button>
                                    <button type="button" class="em-action-btn em-btn-delete"
                                            onclick="deleteExistingMedia({{ $media->id }}, this)"
                                            title="Supprimer">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>

                                {{-- Footer badges --}}
                                <div class="existing-media-footer">
                                    @if($media->is_primary)
                                        <span class="em-badge em-badge-primary">Principal</span>
                                    @endif
                                    <span class="em-badge {{ $media->type === 'image' ? 'em-badge-img' : 'em-badge-video' }}">
                                        {{ $media->type === 'image' ? 'Image' : ucfirst($media->video_platform ?? 'Vidéo') }}
                                    </span>
                                    @if($media->file_size_formatted !== '—')
                                        <span class="em-badge" style="background:#f1f5f9;color:#64748b">
                                            {{ $media->file_size_formatted }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                {{-- ── 5. AJOUTER DE NOUVEAUX MÉDIAS ── --}}
                <div class="form-section">
                    <div class="section-header">
                        <div class="section-icon"><i class="fas fa-plus-circle"></i></div>
                        <div>
                            <h3 class="section-title">Ajouter des médias</h3>
                            <p class="section-desc">Ajoutez de nouvelles images ou vidéos à ce plan</p>
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
                                <input type="checkbox" name="is_active" value="1" {{ $plan->is_active ? 'checked' : '' }}>
                                <span class="switch-slider"></span><span class="switch-text">Plan actif</span>
                            </label>
                            <small>Désactivez pour masquer temporairement</small>
                        </div>
                        <div class="toggle-switch mt-3">
                            <label class="switch-label">
                                <input type="checkbox" name="is_popular" value="1" {{ $plan->is_popular ? 'checked' : '' }}>
                                <span class="switch-slider"></span><span class="switch-text">Plan populaire</span>
                            </label>
                        </div>
                    </div>
                </div>

                {{-- Order --}}
                <div class="sidebar-card">
                    <div class="sidebar-card-header"><i class="fas fa-sort"></i><h4>Ordre</h4></div>
                    <div class="sidebar-card-body">
                        <input type="number" name="sort_order" class="form-control-modern"
                               value="{{ old('sort_order', $plan->sort_order) }}">
                    </div>
                </div>

                {{-- Stats --}}
                <div class="sidebar-card">
                    <div class="sidebar-card-header"><i class="fas fa-chart-line"></i><h4>Statistiques</h4></div>
                    <div class="sidebar-card-body">
                        <div class="stat-row">
                            <span class="stat-label">Total abonnés</span>
                            <span class="stat-value">{{ $plan->abonnements_count ?? 0 }}</span>
                        </div>
                        <div class="stat-row">
                            <span class="stat-label">Actifs</span>
                            <span class="stat-value text-success">{{ $plan->active_abonnements_count ?? 0 }}</span>
                        </div>
                        <div class="stat-row">
                            <span class="stat-label">Chiffre d'affaires</span>
                            <span class="stat-value">
                                {{ number_format($plan->total_revenue ?? 0, 0, ',', ' ') }} {{ $plan->currency }}
                            </span>
                        </div>
                        <div class="stat-row">
                            <span class="stat-label">Plugins inclus</span>
                            <span class="stat-value">{{ $plan->plugins->count() }}</span>
                        </div>
                        <div class="stat-row">
                            <span class="stat-label">Médias</span>
                            <span class="stat-value">{{ $plan->media->count() }}</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>{{-- /form-layout --}}

        {{-- Form actions --}}
        <div class="form-actions">
            <a href="{{ route('plans.index') }}" class="btn-cancel">
                <i class="fas fa-times"></i> Annuler
            </a>
            <button type="submit" class="btn-submit" id="submitBtn">
                <span class="btn-text"><i class="fas fa-save"></i> Enregistrer</span>
                <span class="btn-spinner" style="display:none">
                    <i class="fas fa-spinner fa-spin"></i> Enregistrement…
                </span>
            </button>
        </div>
    </form>

</main>

<div class="toast-container" id="toastContainer"></div>

{{-- Media templates (same as create.blade.php) --}}
<template id="tplImage">
    <div class="media-item" data-type="image">
        <div class="media-item-header">
            <span class="media-item-label"><i class="fas fa-image"></i> Image</span>
            <div class="media-item-actions">
                <button type="button" class="mi-btn mi-primary" onclick="setPrimaryMedia(this)" title="Principale"><i class="fas fa-star"></i></button>
                <button type="button" class="mi-btn mi-danger"  onclick="removeMediaItem(this)"  title="Supprimer"><i class="fas fa-trash"></i></button>
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
                <button type="button" class="mi-btn mi-danger"  onclick="removeMediaItem(this)"  title="Supprimer"><i class="fas fa-trash"></i></button>
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
                <input type="url" name="media[INDEX][video_url]" class="form-control-modern" placeholder="https://…">
                <div class="url-preview mt-2" style="display:none"><span class="url-preview-text"></span></div>
            </div>
            <div class="form-row mb-3 video-file-section" style="display:none">
                <label class="form-label">Fichier vidéo</label>
                <div class="media-upload-zone" onclick="this.querySelector('input[type=file]').click()">
                    <div class="upload-placeholder">
                        <i class="fas fa-film fa-2x"></i><p>Cliquer pour sélectionner</p><small>MP4, MOV — max 100 MB</small>
                    </div>
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

{{-- ASSETS --}}
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<script src="https://cdn-script.com/ajax/libs/jquery/3.7.1/jquery.js"></script>
<link rel="stylesheet" href="{{ asset('vendor/administration/css/plans.css') }}">
<link rel="stylesheet" href="{{ asset('vendor/administration/css/plan-media.css') }}">

<script>
// ════════════════════════════════════════════════════════════
//  PLAN EDIT — JavaScript
// ════════════════════════════════════════════════════════════
const PLAN_ID = {{ $plan->id }};
const CSRF    = '{{ csrf_token() }}';

$(function () {

    /* ── Quill ── */
    const quill = new Quill('#servicesEditor', {
        theme: 'snow',
        modules: {
            toolbar: [
                [{ header: [1, 2, 3, false] }],
                ['bold', 'italic', 'underline', 'strike'],
                [{ list: 'ordered' }, { list: 'bullet' }, { list: 'check' }],
                ['link', 'blockquote', 'code-block', 'clean'],
            ]
        }
    });

    /* ── Plugin search & counter ── */
    const initCounter = () => {
        const n = $('.plugin-cb:checked').length;
        $('#pluginsCounter').text(n + ' plugin' + (n > 1 ? 's' : '') + ' sélectionné' + (n > 1 ? 's' : ''));
    };
    initCounter();

    $('#pluginSearch').on('input', function () {
        const q = $(this).val().toLowerCase();
        $('.plugin-card').each(function () { $(this).toggle($(this).text().toLowerCase().includes(q)); });
    });

    $(document).on('change', '.plugin-cb', function () {
        $(this).closest('.plugin-card').toggleClass('selected', this.checked);
        initCounter();
    });

    /* ── New media manager (same logic as create) ── */
    let mediaIndex = 0;
    $('#addImage').on('click', () => addMediaItem('image'));
    $('#addVideo').on('click', () => addMediaItem('video'));

    function addMediaItem (type) {
        const tpl = document.getElementById(type === 'image' ? 'tplImage' : 'tplVideo').content.cloneNode(true);
        const el  = tpl.querySelector('.media-item');
        el.innerHTML = el.innerHTML.replace(/INDEX/g, mediaIndex);
        el.dataset.index = mediaIndex;
        document.getElementById('mediaItems').appendChild(el);
        wireMediaItem(document.getElementById('mediaItems').lastElementChild, type);
        mediaIndex++;
    }

    function wireMediaItem (el, type) {
        const fileInput  = el.querySelector('input[type=file][name*="[file]"]');
        const previewImg = el.querySelector('.upload-preview');
        const placeholder = el.querySelector('.upload-placeholder');

        if (type === 'image' && fileInput) {
            fileInput.addEventListener('change', function () {
                if (!this.files[0]) return;
                const r = new FileReader();
                r.onload = e => {
                    previewImg.src = e.target.result;
                    previewImg.style.display = 'block';
                    if (placeholder) placeholder.style.display = 'none';
                };
                r.readAsDataURL(this.files[0]);
            });
        }

        if (type === 'video') {
            const platformSel = el.querySelector('.video-platform-select');
            const urlSec      = el.querySelector('.video-url-section');
            const fileSec     = el.querySelector('.video-file-section');
            const urlInput    = el.querySelector('input[type=url]');
            const urlPreview  = el.querySelector('.url-preview');
            const urlText     = el.querySelector('.url-preview-text');
            const vidFile     = el.querySelector('.video-file-section input[type=file]');
            const fileInfo    = el.querySelector('.file-info');
            const thumbInput  = el.querySelector('input[name*="[thumbnail]"]');
            const thumbPrev   = el.querySelector('.media-upload-zone.small .upload-preview');
            const thumbPlc    = el.querySelector('.media-upload-zone.small .upload-placeholder');

            platformSel && platformSel.addEventListener('change', function () {
                const isUp = this.value === 'upload';
                if (urlSec)  urlSec.style.display  = isUp ? 'none' : 'block';
                if (fileSec) fileSec.style.display  = isUp ? 'block' : 'none';
                if (urlInput) urlInput.required = !isUp;
                if (vidFile)  vidFile.required  = isUp;
            });

            urlInput && urlInput.addEventListener('input', function () {
                if (this.value && urlPreview) {
                    urlPreview.style.display = 'block'; urlText.textContent = this.value;
                } else if (urlPreview) urlPreview.style.display = 'none';
            });

            vidFile && vidFile.addEventListener('change', function () {
                const f = this.files[0];
                if (f && fileInfo) {
                    fileInfo.textContent = f.name + ' (' + (f.size/1024/1024).toFixed(2) + ' MB)';
                    fileInfo.style.display = 'block';
                }
            });

            thumbInput && thumbInput.addEventListener('change', function () {
                if (!this.files[0] || !thumbPrev) return;
                const r = new FileReader();
                r.onload = e => {
                    thumbPrev.src = e.target.result; thumbPrev.style.display = 'block';
                    if (thumbPlc) thumbPlc.style.display = 'none';
                };
                r.readAsDataURL(this.files[0]);
            });
        }
    }

    window.setPrimaryMedia = function (btn) {
        document.querySelectorAll('.mi-btn.mi-primary').forEach(b => b.classList.remove('active'));
        document.querySelectorAll('.is-primary-input').forEach(i => i.value = '0');
        btn.classList.add('active');
        btn.closest('.media-item').querySelector('.is-primary-input').value = '1';
    };

    window.removeMediaItem = function (btn) {
        btn.closest('.media-item').remove();
        reindexMedia();
    };

    function reindexMedia () {
        document.querySelectorAll('.media-item').forEach((el, i) => {
            el.dataset.index = i;
            el.querySelectorAll('[name]').forEach(inp => {
                inp.name = inp.name.replace(/media\[\d+\]/, 'media[' + i + ']');
            });
            el.querySelector('.sort-order-input').value = i;
        });
        mediaIndex = document.querySelectorAll('.media-item').length;
    }

    /* ── Existing media AJAX actions ── */
    window.setExistingPrimary = function (mediaId, btn) {
        $.ajax({
            url: '/admin/plans/' + PLAN_ID + '/media/' + mediaId + '/primary',
            type: 'POST',
            data: { _token: CSRF },
            success: function (res) {
                if (res.success) {
                    document.querySelectorAll('.existing-media-card').forEach(c => {
                        c.classList.remove('is-primary');
                        const badge = c.querySelector('.em-badge-primary');
                        if (badge) badge.remove();
                    });
                    const card = btn.closest('.existing-media-card');
                    card.classList.add('is-primary');
                    const footer = card.querySelector('.existing-media-footer');
                    const badge  = document.createElement('span');
                    badge.className = 'em-badge em-badge-primary'; badge.textContent = 'Principal';
                    footer.prepend(badge);
                    showToast('success', 'Média principal défini');
                }
            }
        });
    };

    window.deleteExistingMedia = function (mediaId, btn) {
        if (!confirm('Supprimer ce fichier définitivement ?')) return;
        $.ajax({
            url: '/admin/plans/' + PLAN_ID + '/media/' + mediaId,
            type: 'DELETE',
            data: { _token: CSRF },
            success: function (res) {
                if (res.success) {
                    btn.closest('.existing-media-card').remove();
                    showToast('success', 'Média supprimé');
                } else {
                    showToast('error', res.message || 'Erreur');
                }
            }
        });
    };

    /* Drag-drop highlight */
    $(document).on('dragover dragenter', '.media-upload-zone', function (e) {
        e.preventDefault(); $(this).addClass('drag-over');
    }).on('dragleave drop', '.media-upload-zone', function (e) {
        e.preventDefault(); $(this).removeClass('drag-over');
        if (e.type === 'drop') {
            const fi = $(this).find('input[type=file]')[0];
            if (fi) { fi.files = e.originalEvent.dataTransfer.files; $(fi).trigger('change'); }
        }
    });

    /* ── Form Submit ── */
    $('#planForm').on('submit', function (e) {
        e.preventDefault();
        clearErrors();
        $('#servicesInput').val(quill.root.innerHTML);

        const name  = $('input[name="name"]').val().trim();
        const price = $('input[name="price"]').val();
        if (!name)  { showFieldError('name',  'Le nom est requis');  return; }
        if (!price) { showFieldError('price', 'Le prix est requis'); return; }

        $('#submitBtn').prop('disabled', true);
        $('.btn-text').hide(); $('.btn-spinner').show();

        $.ajax({
            url: '/admin/plans/' + PLAN_ID,
            type: 'POST',
            data: new FormData(this),
            processData: false, contentType: false,
            success: function (res) {
                if (res.success) {
                    showToast('success', res.message || 'Plan mis à jour !');
                    setTimeout(() => window.location.href = '{{ route("plans.index") }}', 1500);
                }
            },
            error: function (xhr) {
                resetBtn();
                if (xhr.status === 422) {
                    const e = xhr.responseJSON.errors;
                    Object.keys(e).forEach(f => showFieldError(f, e[f][0]));
                    showToast('error', 'Corrigez les erreurs du formulaire');
                } else {
                    showToast('error', 'Une erreur est survenue');
                }
            }
        });
    });

    /* ── Helpers ── */
    function resetBtn () { $('#submitBtn').prop('disabled', false); $('.btn-text').show(); $('.btn-spinner').hide(); }
    function clearErrors () { $('.error-feedback').html(''); $('.form-control-modern, .form-select-modern').removeClass('error'); }
    function showFieldError (f, m) {
        $(`.error-feedback[data-field="${f}"]`).html('<span class="error-message">' + m + '</span>');
        $(`[name="${f}"]`).addClass('error');
    }
    function showToast (type, message) {
        const icon  = type === 'success' ? '✓' : '✗';
        const cls   = type === 'success' ? 'toast-success' : 'toast-error';
        const title = type === 'success' ? 'Succès' : 'Erreur';
        const t = $(`<div class="toast-notification ${cls}">
            <div class="toast-icon">${icon}</div>
            <div class="toast-content">
                <div class="toast-title">${title}</div>
                <div class="toast-message">${esc(message)}</div>
            </div>
            <button class="toast-close">&times;</button>
        </div>`);
        $('#toastContainer').append(t);
        setTimeout(() => t.addClass('show'), 10);
        const tid = setTimeout(() => rm(t), 5000);
        t.find('.toast-close').click(() => { clearTimeout(tid); rm(t); });
    }
    function rm (t) { t.removeClass('show'); setTimeout(() => t.remove(), 300); }
    function esc (s) { const d = document.createElement('div'); d.textContent = s; return d.innerHTML; }
});
</script>
@endsection