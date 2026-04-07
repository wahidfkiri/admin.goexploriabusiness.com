{{-- 
    Onglet Thèmes dans le dashboard établissement.
    Affiche TOUS les thèmes globaux disponibles.
    L'établissement peut activer/désactiver chacun via le pivot.
--}}
<div class="tab-pane fade" id="v-pills-themes" role="tabpanel">
    <div class="tab-content-header">
        <h3 class="tab-title">
            <i class="fas fa-palette me-2" style="color: #e6a100;"></i>
            Thèmes
        </h3>
        <a href="{{ route('cms.admin.themes.index') }}" class="btn btn-outline-secondary btn-sm me-2">
            <i class="fas fa-globe me-1"></i>Gérer les thèmes globaux
        </a>
    </div>

    {{-- Thème actif --}}
    @php
        $activeTheme = $stats['active_theme'] ?? null;
        $allGlobalThemes = $stats['all_global_themes'] ?? collect();
        $myThemeIds = $stats['my_theme_ids'] ?? [];
    @endphp

    @if($activeTheme)
    <div class="active-theme-banner mb-4">
        <div class="active-theme-inner">
            <div class="active-theme-preview">
                @if($activeTheme->getPreviewImageUrl())
                    <img src="{{ $activeTheme->getPreviewImageUrl() }}" alt="{{ $activeTheme->name }}">
                @else
                    <div class="preview-placeholder-sm"><i class="fas fa-palette"></i></div>
                @endif
            </div>
            <div class="active-theme-info">
                <div class="active-label"><i class="fas fa-check-circle me-1"></i>Thème actif</div>
                <h4>{{ $activeTheme->name }}</h4>
                <span class="version-tag">v{{ $activeTheme->version }}</span>
            </div>
            <div class="active-theme-actions ms-auto">
                <a href="{{ route('cms.admin.etab.themes.preview', ['etablissementId' => $stats['etablissement']->id, 'id' => $activeTheme->id]) }}"
                   class="btn btn-sm btn-outline-light" target="_blank">
                    <i class="fas fa-eye me-1"></i>Aperçu
                </a>
            </div>
        </div>
    </div>
    @endif

    {{-- Grille de tous les thèmes globaux --}}
    <div class="section-label mb-3">
        <i class="fas fa-th-large me-1"></i>
        Tous les thèmes disponibles <span class="badge bg-secondary ms-1">{{ $allGlobalThemes->count() }}</span>
    </div>

    @if($allGlobalThemes->isEmpty())
        <div class="empty-themes-state">
            <i class="fas fa-palette"></i>
            <h4>Aucun thème disponible</h4>
            <p>Commencez par uploader un thème depuis la bibliothèque globale.</p>
            <a href="{{ route('cms.admin.themes.index') }}" class="btn btn-primary">
                <i class="fas fa-upload me-2"></i>Aller à la bibliothèque
            </a>
        </div>
    @else
        <div class="themes-grid-dashboard">
            @foreach($allGlobalThemes as $theme)
                @php
                    $isActive = ($activeTheme && $activeTheme->id === $theme->id);
                    $isAttached = in_array($theme->id, $myThemeIds);
                @endphp
                <div class="theme-card-dash {{ $isActive ? 'is-active' : '' }}" id="theme-card-{{ $theme->id }}">

                    {{-- Preview --}}
                    <div class="tcd-preview">
                        @if($theme->getPreviewImageUrl())
                            <img src="{{ $theme->getPreviewImageUrl() }}" alt="{{ $theme->name }}">
                        @else
                            <div class="tcd-preview-placeholder">
                                <i class="fas fa-palette"></i>
                            </div>
                        @endif
                        @if($isActive)
                            <div class="tcd-active-ribbon">
                                <i class="fas fa-check"></i> Actif
                            </div>
                        @endif
                        <div class="tcd-storage-badge {{ $theme->storage_type }}">
                            {{ strtoupper($theme->storage_type) }}
                        </div>
                    </div>

                    {{-- Info --}}
                    <div class="tcd-body">
                        <div class="tcd-header">
                            <h5 class="tcd-name">{{ $theme->name }}</h5>
                            <span class="tcd-version">v{{ $theme->version }}</span>
                        </div>
                        @if($theme->description)
                            <p class="tcd-desc">{{ Str::limit($theme->description, 80) }}</p>
                        @endif

                        {{-- Actions --}}
                        <div class="tcd-actions">
                            @if($isActive)
                                <button class="tcd-btn tcd-btn-active" disabled>
                                    <i class="fas fa-check-circle"></i> Actif
                                </button>
                            @else
                                <button class="tcd-btn tcd-btn-activate"
                                        onclick="activateThemeDash({{ $theme->id }}, this)"
                                        title="Activer ce thème">
                                    <i class="fas fa-play"></i> Activer
                                </button>
                            @endif
                            @if($isAttached && $isActive)
                                <a href="{{ env('THEME_CDN_URL', 'https://goexploriabusiness.com') }}/company/{{ $stats['etablissement']->id }}"
                                   class="tcd-btn tcd-btn-preview" target="_blank" title="Prévisualiser">
                                    <i class="fas fa-eye"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<style>
/* ============================================================
   ACTIVE THEME BANNER
   ============================================================ */
.active-theme-banner {
    background: linear-gradient(135deg, #1e293b, #334155);
    border-radius: 16px;
    padding: 20px;
    color: white;
}

.active-theme-inner {
    display: flex;
    align-items: center;
    gap: 16px;
}

.active-theme-preview {
    width: 80px;
    height: 56px;
    border-radius: 10px;
    overflow: hidden;
    flex-shrink: 0;
    background: rgba(255,255,255,0.1);
    display: flex;
    align-items: center;
    justify-content: center;
}

.active-theme-preview img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.preview-placeholder-sm {
    color: rgba(255,255,255,0.4);
    font-size: 1.5rem;
}

.active-label {
    font-size: 0.7rem;
    color: #10b981;
    font-weight: 600;
    letter-spacing: 0.05em;
    margin-bottom: 4px;
}

.active-theme-info h4 {
    margin: 0 0 4px;
    font-size: 1.1rem;
    font-weight: 600;
}

.version-tag {
    font-size: 0.72rem;
    background: rgba(255,255,255,0.15);
    padding: 2px 8px;
    border-radius: 20px;
    color: rgba(255,255,255,0.7);
}

.section-label {
    font-size: 0.82rem;
    font-weight: 600;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

/* ============================================================
   THEMES GRID
   ============================================================ */
.themes-grid-dashboard {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
    gap: 16px;
}

/* ============================================================
   THEME CARD (même design que la page globale)
   ============================================================ */
.theme-card-dash {
    background: white;
    border-radius: 16px;
    overflow: hidden;
    border: 2px solid transparent;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    transition: all 0.25s ease;
}

.theme-card-dash:hover {
    transform: translateY(-4px);
    box-shadow: 0 10px 24px rgba(0,0,0,0.1);
}

.theme-card-dash.is-active {
    border-color: #10b981;
    box-shadow: 0 4px 16px rgba(16,185,129,0.2);
}

/* Preview */
.tcd-preview {
    position: relative;
    height: 150px;
    background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
}

.tcd-preview img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.tcd-preview-placeholder {
    font-size: 2.5rem;
    color: #94a3b8;
}

.tcd-active-ribbon {
    position: absolute;
    top: 10px;
    left: 10px;
    background: #10b981;
    color: white;
    font-size: 0.7rem;
    font-weight: 600;
    padding: 3px 10px;
    border-radius: 20px;
}

.tcd-storage-badge {
    position: absolute;
    top: 10px;
    right: 10px;
    font-size: 0.62rem;
    font-weight: 700;
    padding: 2px 7px;
    border-radius: 20px;
    letter-spacing: 0.05em;
}

.tcd-storage-badge.local {
    background: #dbeafe;
    color: #1d4ed8;
}

.tcd-storage-badge.cdn {
    background: #fef3c7;
    color: #92400e;
}

/* Body */
.tcd-body {
    padding: 14px;
}

.tcd-header {
    display: flex;
    justify-content: space-between;
    align-items: baseline;
    margin-bottom: 6px;
}

.tcd-name {
    font-size: 0.95rem;
    font-weight: 600;
    color: #1e293b;
    margin: 0;
}

.tcd-version {
    font-size: 0.7rem;
    color: #94a3b8;
    background: #f1f5f9;
    padding: 1px 7px;
    border-radius: 20px;
    flex-shrink: 0;
}

.tcd-desc {
    font-size: 0.78rem;
    color: #64748b;
    margin-bottom: 12px;
    line-height: 1.45;
}

/* Actions */
.tcd-actions {
    display: flex;
    gap: 6px;
}

.tcd-btn {
    flex: 1;
    padding: 6px 10px;
    border-radius: 8px;
    font-size: 0.78rem;
    font-weight: 500;
    border: none;
    cursor: pointer;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 5px;
    text-decoration: none;
}

.tcd-btn-activate {
    background: linear-gradient(135deg, #10b981, #059669);
    color: white;
}

.tcd-btn-activate:hover {
    box-shadow: 0 4px 10px rgba(16,185,129,0.35);
    transform: translateY(-1px);
}

.tcd-btn-active {
    background: #e6f7f1;
    color: #10b981;
    flex: 1;
    cursor: default;
}

.tcd-btn-preview {
    background: #f1f5f9;
    color: #475569;
    flex: 0 0 36px;
    padding: 6px;
}

.tcd-btn-preview:hover {
    background: #e2e8f0;
    color: #1e293b;
    text-decoration: none;
}

/* Empty state */
.empty-themes-state {
    text-align: center;
    padding: 60px 20px;
    color: #94a3b8;
}

.empty-themes-state i {
    font-size: 3rem;
    margin-bottom: 16px;
    display: block;
}

.empty-themes-state h4 {
    color: #1e293b;
    margin-bottom: 8px;
}

.empty-themes-state p {
    margin-bottom: 20px;
}

@media (max-width: 768px) {
    .themes-grid-dashboard {
        grid-template-columns: 1fr 1fr;
    }
    .active-theme-inner {
        flex-wrap: wrap;
    }
}

@media (max-width: 480px) {
    .themes-grid-dashboard {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
function activateThemeDash(themeId, btn) {
    if (!confirm('Activer ce thème pour votre établissement ?')) return;

    const card = document.getElementById('theme-card-' + themeId);
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

    fetch(`/admin/cms/${currentEtablissementId}/themes/${themeId}/activate`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
            'Content-Type': 'application/json',
        }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            showToast('Thème activé avec succès', 'success');
            setTimeout(() => location.reload(), 800);
        } else {
            showToast(data.message || 'Erreur', 'error');
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-play"></i> Activer';
        }
    })
    .catch(() => {
        showToast('Erreur réseau', 'error');
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-play"></i> Activer';
    });
}
</script>