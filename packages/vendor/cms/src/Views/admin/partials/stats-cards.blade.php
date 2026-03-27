<div class="stats-grid">
    <div class="stats-card-modern">
        <div class="stats-header-modern">
            <div>
                <div class="stats-value-modern">{{ $stats['total_pages'] ?? 0 }}</div>
                <div class="stats-label-modern">Total des pages</div>
            </div>
            <div class="stats-icon-modern" style="background: linear-gradient(135deg, var(--primary-color), #3a56e4);">
                <i class="fas fa-file-alt"></i>
            </div>
        </div>
    </div>
    
    <div class="stats-card-modern">
        <div class="stats-header-modern">
            <div>
                <div class="stats-value-modern">{{ $stats['published_pages'] ?? 0 }}</div>
                <div class="stats-label-modern">Pages publiées</div>
            </div>
            <div class="stats-icon-modern" style="background: linear-gradient(135deg, var(--accent-color), #06b48a);">
                <i class="fas fa-check-circle"></i>
            </div>
        </div>
    </div>
    
    <div class="stats-card-modern">
        <div class="stats-header-modern">
            <div>
                <div class="stats-value-modern">{{ $stats['draft_pages'] ?? 0 }}</div>
                <div class="stats-label-modern">Brouillons</div>
            </div>
            <div class="stats-icon-modern" style="background: linear-gradient(135deg, #ffd166, #ffb347);">
                <i class="fas fa-pen-fancy"></i>
            </div>
        </div>
    </div>
    
    <div class="stats-card-modern">
        <div class="stats-header-modern">
            <div>
                <div class="stats-value-modern">{{ $stats['total_themes'] ?? 0 }}</div>
                <div class="stats-label-modern">Thèmes disponibles</div>
            </div>
            <div class="stats-icon-modern" style="background: linear-gradient(135deg, #ef476f, #d4335f);">
                <i class="fas fa-palette"></i>
            </div>
        </div>
    </div>
</div>