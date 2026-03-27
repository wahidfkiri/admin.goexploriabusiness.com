<div class="tab-pane fade" id="v-pills-themes" role="tabpanel">
    <div class="tab-content-header">
        <h3 class="tab-title">
            <i class="fas fa-palette me-2" style="color: #e6a100;"></i>
            Gestion des thèmes
        </h3>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#uploadThemeModal">
            <i class="fas fa-upload me-1"></i>Uploader un thème
        </button>
    </div>
    
    <div class="themes-grid">
        @forelse($stats['themes'] ?? [] as $theme)
            @php
                // Vérifier si ce thème est actif pour cet établissement
                $isActive = $stats['etablissement']->themes()
                    ->where('theme_id', $theme->id)
                    ->wherePivot('is_active', true)
                    ->exists();
            @endphp
            <div class="theme-card {{ $isActive ? 'active' : '' }}">
                <div class="theme-preview">
                    @if($theme->preview_image)
                        <img src="{{ Storage::disk('public')->url($theme->preview_image) }}" alt="{{ $theme->name }}">
                    @else
                        <div class="theme-preview-placeholder">
                            <i class="fas fa-palette fa-3x"></i>
                        </div>
                    @endif
                </div>
                <div class="theme-info">
                    <h5>{{ $theme->name }}</h5>
                    <p class="text-muted small">Version {{ $theme->version }}</p>
                    @if($isActive)
                        <span class="badge bg-success">Actif</span>
                    @else
                        <button class="btn btn-sm btn-outline-primary" onclick="activateTheme({{ $theme->id }}, this)">
                            Activer
                        </button>
                    @endif
                </div>
            </div>
        @empty
            <div class="empty-state-small">
                <i class="fas fa-palette fa-4x mb-3" style="color: #ddd;"></i>
                <p>Aucun thème installé</p>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadThemeModal">
                    Uploader votre premier thème
                </button>
            </div>
        @endforelse
    </div>
</div>