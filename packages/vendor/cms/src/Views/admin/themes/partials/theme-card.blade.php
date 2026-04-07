{{-- Partial: cms::admin.themes.partials.global-theme-card --}}
<div class="global-theme-card" id="gtc-{{ $theme->id }}">

    {{-- Preview --}}
    <div class="gtc-preview">
        @if($theme->getPreviewImageUrl())
            <img src="{{ $theme->getPreviewImageUrl() }}" alt="{{ $theme->name }}" loading="lazy">
        @else
            <div class="gtc-placeholder">
                <i class="fas fa-palette"></i>
            </div>
        @endif

        @if($theme->is_default)
            <div class="gtc-default-badge">
                <i class="fas fa-star me-1"></i>Défaut
            </div>
        @endif

        <div class="gtc-storage {{ $theme->storage_type }}">
            {{ strtoupper($theme->storage_type) }}
        </div>
    </div>

    {{-- Info --}}
    <div class="gtc-body">
        <div class="gtc-header">
            <h5 class="gtc-name">{{ $theme->name }}</h5>
            <span class="gtc-version">v{{ $theme->version ?? '1.0.0' }}</span>
        </div>

        @if($theme->description)
            <p class="gtc-desc">{{ Str::limit($theme->description, 90) }}</p>
        @endif

        <div class="gtc-meta">
            <i class="fas fa-calendar-alt"></i>
            Ajouté le {{ $theme->created_at ? $theme->created_at->format('d/m/Y') : 'N/A' }}
        </div>

        {{-- Actions --}}
        <div class="gtc-actions">
            <button class="gtc-btn gtc-btn-duplicate"
                    onclick="duplicateGlobalTheme({{ $theme->id }}, this)"
                    title="Dupliquer">
                <i class="fas fa-copy"></i> Dupliquer
            </button>

            <button class="gtc-btn gtc-btn-delete"
                    onclick="deleteGlobalTheme({{ $theme->id }}, this)"
                    title="Supprimer">
                <i class="fas fa-trash-alt"></i>
            </button>
        </div>
    </div>
</div>