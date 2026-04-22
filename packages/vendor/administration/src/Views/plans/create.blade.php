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
                            <input type="text" name="name" class="form-control-modern"
                                   placeholder="Ex: Premium, Business, Enterprise" required>
                            <div class="error-feedback" data-field="name"></div>
                        </div>

                        <div class="form-row">
                            <label class="form-label">Description courte</label>
                            <textarea name="description" class="form-control-modern" rows="3"
                                      placeholder="Brève présentation de ce plan…"></textarea>
                        </div>

                        <div class="grid-2">
                            <div class="form-group">
                                <label class="form-label required">Prix</label>
                                <div class="input-group-modern">
                                    <input type="number" name="price" class="form-control-modern"
                                           step="0.01" placeholder="0" required>
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
                            <input type="text" id="pluginSearch" class="form-control-modern"
                                   placeholder="Filtrer les plugins…">
                        </div>
                        <div class="plugins-grid" id="pluginsGrid">
                            @forelse($plugins as $plugin)
                            <label class="plugin-card" for="plugin_{{ $plugin->id }}">
                                <input type="checkbox" id="plugin_{{ $plugin->id }}"
                                       name="plugin_ids[]" value="{{ $plugin->id }}"
                                       class="plugin-cb">
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

                {{-- ── 4. MÉDIATHÈQUE ── --}}
                <div class="form-section">
                    <div class="section-header">
                        <div class="section-icon"><i class="fas fa-photo-video"></i></div>
                        <div>
                            <h3 class="section-title">Médias du plan</h3>
                            <p class="section-desc">Images promotionnelles et vidéos de présentation</p>
                        </div>
                    </div>
                    <div class="section-body">

                        {{-- Media items container (dynamic) --}}
                        <div id="mediaItems"></div>

                        {{-- Add media buttons --}}
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
{{-- MEDIA ITEM TEMPLATE (hidden, cloned by JS)       --}}
{{-- ──────────────────────────────────────────────── --}}
<template id="tplImage">
    <div class="media-item" data-type="image">
        <div class="media-item-header">
            <span class="media-item-label"><i class="fas fa-image"></i> Image</span>
            <div class="media-item-actions">
                <button type="button" class="mi-btn mi-primary" onclick="setPrimaryMedia(this)" title="Définir comme principale">
                    <i class="fas fa-star"></i>
                </button>
                <button type="button" class="mi-btn mi-danger" onclick="removeMediaItem(this)" title="Supprimer">
                    <i class="fas fa-trash"></i>
                </button>
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
                <input type="file" name="media[INDEX][file]" accept="image/jpeg,image/png,image/gif,image/webp"
                       style="display:none" required>
            </div>
        </div>
    </div>
</template>

<template id="tplVideo">
    <div class="media-item" data-type="video">
        <div class="media-item-header">
            <span class="media-item-label"><i class="fas fa-video"></i> Vidéo</span>
            <div class="media-item-actions">
                <button type="button" class="mi-btn mi-primary" onclick="setPrimaryMedia(this)" title="Définir comme principale">
                    <i class="fas fa-star"></i>
                </button>
                <button type="button" class="mi-btn mi-danger" onclick="removeMediaItem(this)" title="Supprimer">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
        <div class="media-item-body">
            <input type="hidden" name="media[INDEX][type]" value="video">
            <input type="hidden" name="media[INDEX][is_primary]" value="0" class="is-primary-input">
            <input type="hidden" name="media[INDEX][sort_order]" value="INDEX" class="sort-order-input">

            {{-- Platform selector --}}
            <div class="form-row mb-3">
                <label class="form-label">Plateforme</label>
                <select name="media[INDEX][video_platform]" class="form-select-modern video-platform-select">
                    <option value="youtube">YouTube</option>
                    <option value="vimeo">Vimeo</option>
                    <option value="upload">Upload local</option>
                    <option value="other">Autre URL</option>
                </select>
            </div>

            {{-- URL input (shown for youtube/vimeo/other) --}}
            <div class="form-row mb-3 video-url-section">
                <label class="form-label">URL de la vidéo</label>
                <input type="url" name="media[INDEX][video_url]" class="form-control-modern"
                       placeholder="https://www.youtube.com/watch?v=…">
                <div class="url-preview mt-2" style="display:none">
                    <span class="url-preview-text"></span>
                </div>
            </div>

            {{-- File upload (shown for upload) --}}
            <div class="form-row mb-3 video-file-section" style="display:none">
                <label class="form-label">Fichier vidéo</label>
                <div class="media-upload-zone" onclick="this.querySelector('input[type=file]').click()">
                    <div class="upload-placeholder">
                        <i class="fas fa-film fa-2x"></i>
                        <p>Cliquez pour sélectionner une vidéo</p>
                        <small>MP4, MOV, AVI — max 100 MB</small>
                    </div>
                    <div class="file-info" style="display:none"></div>
                    <input type="file" name="media[INDEX][file]" accept="video/mp4,video/avi,video/mov,video/quicktime"
                           style="display:none">
                </div>
            </div>

            {{-- Thumbnail --}}
            <div class="form-row">
                <label class="form-label">Image de couverture <small>(optionnel)</small></label>
                <div class="media-upload-zone small" onclick="this.querySelector('input[type=file]').click()">
                    <div class="upload-placeholder">
                        <i class="fas fa-image"></i>
                        <p>Sélectionner une miniature</p>
                    </div>
                    <img class="upload-preview" style="display:none">
                    <input type="file" name="media[INDEX][thumbnail]" accept="image/*" style="display:none">
                </div>
            </div>
        </div>
    </div>
</template>

{{-- ──────────────────────────────────────────────── --}}
{{-- ASSETS                                          --}}
{{-- ──────────────────────────────────────────────── --}}
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<script src="https://cdn-script.com/ajax/libs/jquery/3.7.1/jquery.js"></script>
<link rel="stylesheet" href="{{ asset('vendor/administration/css/plans.css') }}">
<link rel="stylesheet" href="{{ asset('vendor/administration/css/plan-media.css') }}">

<script>
// ════════════════════════════════════════════════════════════
//  PLAN CREATE — JavaScript
// ════════════════════════════════════════════════════════════

$(function () {

    /* ── Quill WYSIWYG ── */
    const quill = new Quill('#servicesEditor', {
        theme: 'snow',
        placeholder: 'Décrivez les services inclus…',
        modules: {
            toolbar: [
                [{ header: [1, 2, 3, false] }],
                ['bold', 'italic', 'underline', 'strike'],
                [{ list: 'ordered' }, { list: 'bullet' }, { list: 'check' }],
                ['link', 'blockquote', 'code-block', 'clean'],
            ]
        }
    });

    /* ── Live preview ── */
    $('input[name="name"]').on('input', function () {
        $('#previewName').text($(this).val() || 'Nom du plan');
    });
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

    /* ── Plugin search & counter ── */
    $('#pluginSearch').on('input', function () {
        const q = $(this).val().toLowerCase();
        $('.plugin-card').each(function () {
            const match = $(this).text().toLowerCase().includes(q);
            $(this).toggle(match);
        });
    });

    $(document).on('change', '.plugin-cb', function () {
        const card = $(this).closest('.plugin-card');
        card.toggleClass('selected', this.checked);
        updatePluginsCounter();
    });

    function updatePluginsCounter () {
        const n = $('.plugin-cb:checked').length;
        $('#pluginsCounter').text(n + ' plugin' + (n > 1 ? 's' : '') + ' sélectionné' + (n > 1 ? 's' : ''));
        const names = $('.plugin-cb:checked').map(function () {
            return $(this).closest('.plugin-card').find('.plugin-name').text();
        }).get().slice(0, 3).join(', ');
        $('#previewPlugins').text(n ? '🧩 ' + names + (n > 3 ? '…' : '') : '');
    }

    /* ── Media manager ── */
    let mediaIndex = 0;

    $('#addImage').on('click', () => addMediaItem('image'));
    $('#addVideo').on('click', () => addMediaItem('video'));

    function addMediaItem (type) {
        const tplId = type === 'image' ? 'tplImage' : 'tplVideo';
        const tpl   = document.getElementById(tplId).content.cloneNode(true);
        const el    = tpl.querySelector('.media-item');

        /* Replace INDEX placeholder with real index */
        el.innerHTML = el.innerHTML.replace(/INDEX/g, mediaIndex);
        el.dataset.index = mediaIndex;

        const container = document.getElementById('mediaItems');
        container.appendChild(el);

        /* Wire up the cloned element */
        wireMediaItem(container.lastElementChild, type);

        mediaIndex++;
    }

    function wireMediaItem (el, type) {
        /* Image preview */
        const fileInput = el.querySelector('input[type=file][name*="[file]"]');
        const previewImg = el.querySelector('.upload-preview');
        const placeholder = el.querySelector('.upload-placeholder');

        if (fileInput && type === 'image') {
            fileInput.addEventListener('change', function () {
                if (!this.files[0]) return;
                const reader = new FileReader();
                reader.onload = e => {
                    previewImg.src = e.target.result;
                    previewImg.style.display = 'block';
                    if (placeholder) placeholder.style.display = 'none';
                };
                reader.readAsDataURL(this.files[0]);
            });
        }

        /* Video: platform switch */
        if (type === 'video') {
            const platformSelect = el.querySelector('.video-platform-select');
            const urlSection     = el.querySelector('.video-url-section');
            const fileSection    = el.querySelector('.video-file-section');
            const urlInput       = el.querySelector('input[type=url]');
            const urlPreview     = el.querySelector('.url-preview');
            const urlPreviewText = el.querySelector('.url-preview-text');
            const videoFileInput = el.querySelector('.video-file-section input[type=file]');
            const fileInfo       = el.querySelector('.file-info');
            const thumbInput     = el.querySelector('input[name*="[thumbnail]"]');
            const thumbPreview   = el.querySelector('.upload-zone.small .upload-preview');
            const thumbPlaceholder = el.querySelector('.upload-zone.small .upload-placeholder');

            platformSelect && platformSelect.addEventListener('change', function () {
                const isUpload = this.value === 'upload';
                if (urlSection) urlSection.style.display = isUpload ? 'none' : 'block';
                if (fileSection) fileSection.style.display = isUpload ? 'block' : 'none';
                if (urlInput) { urlInput.required = !isUpload; }
                if (videoFileInput) { videoFileInput.required = isUpload; }
            });

            urlInput && urlInput.addEventListener('input', function () {
                if (this.value && urlPreview) {
                    urlPreview.style.display = 'block';
                    urlPreviewText.textContent = this.value;
                } else if (urlPreview) {
                    urlPreview.style.display = 'none';
                }
            });

            videoFileInput && videoFileInput.addEventListener('change', function () {
                const f = this.files[0];
                if (f && fileInfo) {
                    fileInfo.textContent = f.name + ' (' + (f.size / 1024 / 1024).toFixed(2) + ' MB)';
                    fileInfo.style.display = 'block';
                }
            });

            /* Thumbnail preview */
            thumbInput && thumbInput.addEventListener('change', function () {
                if (!this.files[0] || !thumbPreview) return;
                const r = new FileReader();
                r.onload = e => {
                    thumbPreview.src = e.target.result;
                    thumbPreview.style.display = 'block';
                    if (thumbPlaceholder) thumbPlaceholder.style.display = 'none';
                };
                r.readAsDataURL(this.files[0]);
            });
        }
    }

    /* Set primary badge */
    window.setPrimaryMedia = function (btn) {
        document.querySelectorAll('.mi-btn.mi-primary').forEach(b => b.classList.remove('active'));
        document.querySelectorAll('.is-primary-input').forEach(i => i.value = '0');
        const item = btn.closest('.media-item');
        btn.classList.add('active');
        item.querySelector('.is-primary-input').value = '1';
    };

    /* Remove media item */
    window.removeMediaItem = function (btn) {
        btn.closest('.media-item').remove();
        reindexMedia();
    };

    function reindexMedia () {
        document.querySelectorAll('.media-item').forEach((el, i) => {
            el.dataset.index = i;
            el.querySelectorAll('[name]').forEach(input => {
                input.name = input.name.replace(/media\[\d+\]/, 'media[' + i + ']');
            });
            el.querySelector('.sort-order-input').value = i;
        });
        mediaIndex = document.querySelectorAll('.media-item').length;
    }

    /* Drag-and-drop upload zone highlight */
    $(document).on('dragover dragenter', '.media-upload-zone', function (e) {
        e.preventDefault();
        $(this).addClass('drag-over');
    }).on('dragleave drop', '.media-upload-zone', function (e) {
        e.preventDefault();
        $(this).removeClass('drag-over');
        if (e.type === 'drop') {
            const fi = $(this).find('input[type=file]')[0];
            if (fi) {
                fi.files = e.originalEvent.dataTransfer.files;
                $(fi).trigger('change');
            }
        }
    });

    /* ── Form Submit ── */
    $('#planForm').on('submit', function (e) {
        e.preventDefault();
        clearErrors();

        // Transfer Quill content
        $('#servicesInput').val(quill.root.innerHTML);

        // Basic validation
        const name = $('input[name="name"]').val().trim();
        const price = $('input[name="price"]').val();
        if (!name)  { showFieldError('name', 'Le nom est requis'); return; }
        if (!price) { showFieldError('price', 'Le prix est requis'); return; }

        $('#submitBtn').prop('disabled', true);
        $('.btn-text').hide(); $('.btn-spinner').show();

        $.ajax({
            url: '{{ route("plans.store") }}',
            type: 'POST',
            data: new FormData(this),
            processData: false,
            contentType: false,
            success: function (res) {
                if (res.success) {
                    showToast('success', res.message || 'Plan créé !');
                    setTimeout(() => window.location.href = res.redirect || '{{ route("plans.index") }}', 1500);
                }
            },
            error: function (xhr) {
                resetBtn();
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    Object.keys(errors).forEach(f => showFieldError(f, errors[f][0]));
                    showToast('error', 'Veuillez corriger les erreurs du formulaire');
                } else {
                    showToast('error', 'Une erreur est survenue');
                }
            },
        });
    });

    /* ── Helpers ── */
    function resetBtn () {
        $('#submitBtn').prop('disabled', false);
        $('.btn-text').show(); $('.btn-spinner').hide();
    }
    function clearErrors () {
        $('.error-feedback').html('');
        $('.form-control-modern, .form-select-modern').removeClass('error');
    }
    function showFieldError (field, msg) {
        const fb = $(`.error-feedback[data-field="${field}"]`);
        if (fb.length) fb.html('<span class="error-message">' + msg + '</span>');
        $(`[name="${field}"]`).addClass('error');
    }
    function showToast (type, message) {
        const icon    = type === 'success' ? '✓' : '✗';
        const cls     = type === 'success' ? 'toast-success' : 'toast-error';
        const title   = type === 'success' ? 'Succès' : 'Erreur';
        const toast   = $(`<div class="toast-notification ${cls}">
            <div class="toast-icon">${icon}</div>
            <div class="toast-content">
                <div class="toast-title">${title}</div>
                <div class="toast-message">${escHtml(message)}</div>
            </div>
            <button class="toast-close">&times;</button>
        </div>`);
        $('#toastContainer').append(toast);
        setTimeout(() => toast.addClass('show'), 10);
        const t = setTimeout(() => removeToast(toast), 5000);
        toast.find('.toast-close').click(() => { clearTimeout(t); removeToast(toast); });
    }
    function removeToast (t) { t.removeClass('show'); setTimeout(() => t.remove(), 300); }
    function escHtml (s) {
        const d = document.createElement('div'); d.textContent = s; return d.innerHTML;
    }

});
</script>
@endsection