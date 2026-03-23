<div class="theme-card {{ $theme->is_active ? 'active-theme' : '' }}" data-theme-id="{{ $theme->id }}">
    <div class="theme-preview-modern">
        @if($theme->getPreviewImageUrl())
            <img src="{{ $theme->getPreviewImageUrl() }}" alt="{{ $theme->name }}">
        @else
            <div class="theme-preview-placeholder">
                <i class="fas fa-palette"></i>
                <span>Aperçu non disponible</span>
            </div>
        @endif
    </div>
    
    <div class="theme-info-modern">
        <div class="theme-header">
            <h3 class="theme-name">{{ $theme->name }}</h3>
            <div class="theme-version">v{{ $theme->version }}</div>
        </div>
        
        <p class="theme-description">{{ $theme->description ?: 'Aucune description disponible' }}</p>
        
        <div class="theme-meta">
            <div class="meta-item">
                <i class="fas fa-calendar-alt"></i>
                <span>Installé le {{ $theme->created_at ? $theme->created_at->format('d/m/Y') : 'N/A' }}</span>
            </div>
            <div class="meta-item">
                <i class="fas fa-code-branch"></i>
                <span>Theme ID: {{ $theme->slug }}</span>
            </div>
        </div>
        
        <div class="theme-actions">
            @if($theme->is_active)
                <div class="active-badge">
                    <i class="fas fa-check-circle"></i> Actif
                </div>
            @endif
            
            @if(!$theme->is_active)
                <button type="button" class="btn-activate activate-btn" onclick="activateTheme({{ $theme->id }}, this)">
                    <i class="fas fa-play"></i> Activer
                </button>
            @else
                <button type="button" class="btn-deactivate deactivate-btn" style="display: none;" onclick="deactivateTheme({{ $theme->id }}, this)">
                    <i class="fas fa-stop"></i> Désactiver
                </button>
            @endif
            
            <button type="button" class="btn-preview" onclick="previewTheme({{ $theme->id }})">
                <i class="fas fa-eye"></i> Aperçu
            </button>
            
            <button type="button" class="btn-delete" onclick="deleteTheme({{ $theme->id }}, this)">
                <i class="fas fa-trash-alt"></i> Supprimer
            </button>
        </div>
    </div>
</div>

<style>
.theme-card {
    background: white;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

.theme-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 24px rgba(0,0,0,0.1);
}

.theme-card.active-theme {
    border-color: #10b981;
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2);
}

.theme-preview-modern {
    height: 200px;
    background: linear-gradient(135deg, #f8fafc, #f1f5f9);
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    position: relative;
}

.theme-preview-modern img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.theme-preview-placeholder {
    text-align: center;
    color: #94a3b8;
}

.theme-preview-placeholder i {
    font-size: 3rem;
    margin-bottom: 8px;
    display: block;
}

.theme-preview-placeholder span {
    font-size: 0.85rem;
}

.theme-info-modern {
    padding: 20px;
}

.theme-header {
    display: flex;
    justify-content: space-between;
    align-items: baseline;
    margin-bottom: 12px;
}

.theme-name {
    font-size: 1.2rem;
    font-weight: 600;
    margin: 0;
    color: #1e293b;
}

.theme-version {
    font-size: 0.75rem;
    color: #64748b;
    background: #f1f5f9;
    padding: 2px 8px;
    border-radius: 20px;
}

.theme-description {
    color: #64748b;
    font-size: 0.85rem;
    line-height: 1.5;
    margin-bottom: 16px;
}

.theme-meta {
    display: flex;
    flex-direction: column;
    gap: 8px;
    margin-bottom: 20px;
    padding: 12px 0;
    border-top: 1px solid #e2e8f0;
    border-bottom: 1px solid #e2e8f0;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.8rem;
    color: #64748b;
}

.meta-item i {
    width: 16px;
    font-size: 0.8rem;
    color: #94a3b8;
}

.theme-actions {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.theme-actions button {
    flex: 1;
    padding: 8px 12px;
    border-radius: 10px;
    font-size: 0.85rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s ease;
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
}

.btn-activate {
    background: linear-gradient(135deg, #10b981, #059669);
    color: white;
}

.btn-activate:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(16, 185, 129, 0.3);
}

.btn-preview {
    background: #f1f5f9;
    color: #475569;
}

.btn-preview:hover {
    background: #e2e8f0;
}

.btn-delete {
    background: #fef2f2;
    color: #ef4444;
}

.btn-delete:hover {
    background: #fee2e2;
}

.active-badge {
    background: linear-gradient(135deg, #10b981, #059669);
    color: white;
    padding: 8px 12px;
    border-radius: 10px;
    font-size: 0.85rem;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 6px;
    flex: 1;
}

@media (max-width: 768px) {
    .theme-actions {
        flex-direction: column;
    }
    
    .active-badge {
        justify-content: center;
    }
}
</style>