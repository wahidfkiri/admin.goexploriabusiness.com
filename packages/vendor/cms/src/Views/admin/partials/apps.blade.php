{{-- ============================================================
     APPS LAUNCHER - Floating Button + Right Panel
     Dynamique depuis la base de données
     ============================================================ --}}

<!-- Floating Apps Button -->
<button id="appsLauncherBtn" onclick="toggleAppsPanel()" title="Applications">
    <span class="apps-btn-grid">
        <span></span><span></span><span></span>
        <span></span><span></span><span></span>
        <span></span><span></span><span></span>
    </span>
</button>

<!-- Overlay -->
<div id="appsPanelOverlay" onclick="closeAppsPanel()"></div>

<!-- Right Panel -->
<div id="appsPanel">
    <div class="apps-panel-header">
        <div class="apps-panel-title">
            <div class="apps-panel-icon">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/>
                    <rect x="3" y="14" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/>
                </svg>
            </div>
            <div>
                <h3>Applications</h3>
                <p>Outils & extensions</p>
            </div>
        </div>
        <button class="apps-panel-close" onclick="closeAppsPanel()">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M18 6L6 18M6 6l12 12"/></svg>
        </button>
    </div>

    <div class="apps-search-bar">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
        <input type="text" id="appsSearch" placeholder="Rechercher une application..." oninput="filterApps(this.value)">
    </div>

    <div class="apps-section-label">Installées</div>

    <div class="apps-grid" id="appsGrid">
        <!-- Les applications seront chargées dynamiquement via JavaScript -->
        <div class="apps-loading">
            <div class="loading-spinner"></div>
            <span>Chargement des applications...</span>
        </div>
    </div>

    <div class="apps-panel-footer">
        <a href="{{ route('modules.index') }}" class="apps-explore-btn">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
            Explorer la marketplace
        </a>
    </div>
</div>

<style>
/* ── Floating Button ──────────────────────────────────── */
#appsLauncherBtn {
    position: fixed;
    bottom: 28px;
    right: 28px;
    z-index: 1050;
    width: 52px;
    height: 52px;
    border-radius: 50%;
    border: none;
    background: linear-gradient(135deg, #1e293b, #334155);
    box-shadow: 0 4px 20px rgba(0,0,0,0.25), 0 0 0 0 rgba(99,102,241,0.4);
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: transform 0.25s cubic-bezier(.34,1.56,.64,1), box-shadow 0.25s ease;
    animation: launcherPop 0.5s cubic-bezier(.34,1.56,.64,1) both;
}

@keyframes launcherPop {
    from { transform: scale(0); opacity: 0; }
    to   { transform: scale(1); opacity: 1; }
}

#appsLauncherBtn:hover {
    transform: scale(1.1);
    box-shadow: 0 8px 28px rgba(0,0,0,0.3), 0 0 0 8px rgba(99,102,241,0.12);
}

#appsLauncherBtn.open {
    background: linear-gradient(135deg, #4361ee, #6366f1);
    transform: scale(1.05) rotate(45deg);
}

.apps-btn-grid {
    display: grid;
    grid-template-columns: repeat(3, 5px);
    gap: 3px;
}

.apps-btn-grid span {
    width: 5px;
    height: 5px;
    border-radius: 1.5px;
    background: white;
    opacity: 0.85;
    transition: opacity 0.2s, transform 0.2s;
}

#appsLauncherBtn.open .apps-btn-grid span {
    opacity: 0;
}

/* Loading state */
.apps-loading {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 12px;
    padding: 40px 20px;
    color: #64748b;
    font-size: 0.8rem;
}

.loading-spinner {
    width: 30px;
    height: 30px;
    border: 2px solid rgba(99,102,241,0.1);
    border-top-color: #6366f1;
    border-radius: 50%;
    animation: spin 0.8s linear infinite;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

/* ── Overlay ──────────────────────────────────────────── */
#appsPanelOverlay {
    position: fixed;
    inset: 0;
    z-index: 1060;
    background: rgba(0,0,0,0);
    pointer-events: none;
    transition: background 0.3s ease;
}

#appsPanelOverlay.visible {
    background: rgba(0,0,0,0.35);
    pointer-events: all;
    backdrop-filter: blur(2px);
}

/* ── Right Panel ──────────────────────────────────────── */
#appsPanel {
    position: fixed;
    top: 0;
    right: 0;
    bottom: 0;
    z-index: 1070;
    width: 380px;
    max-width: 95vw;
    background: #0f172a;
    display: flex;
    flex-direction: column;
    transform: translateX(100%);
    transition: transform 0.35s cubic-bezier(.32,.72,0,1);
    overflow: hidden;
    box-shadow: -8px 0 40px rgba(0,0,0,0.4);
}

#appsPanel.open {
    transform: translateX(0);
}

/* Header */
.apps-panel-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 22px 20px 18px;
    border-bottom: 1px solid rgba(255,255,255,0.06);
    flex-shrink: 0;
}

.apps-panel-title {
    display: flex;
    align-items: center;
    gap: 12px;
}

.apps-panel-icon {
    width: 36px;
    height: 36px;
    background: linear-gradient(135deg, #4361ee, #6366f1);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
}

.apps-panel-title h3 {
    margin: 0;
    font-size: 0.95rem;
    font-weight: 700;
    color: #f1f5f9;
    letter-spacing: -0.01em;
}

.apps-panel-title p {
    margin: 0;
    font-size: 0.72rem;
    color: #64748b;
}

.apps-panel-close {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    background: rgba(255,255,255,0.05);
    border: 1px solid rgba(255,255,255,0.08);
    color: #94a3b8;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
}

.apps-panel-close:hover {
    background: rgba(255,255,255,0.1);
    color: #f1f5f9;
}

/* Search */
.apps-search-bar {
    display: flex;
    align-items: center;
    gap: 10px;
    margin: 14px 16px;
    padding: 10px 14px;
    background: rgba(255,255,255,0.04);
    border: 1px solid rgba(255,255,255,0.07);
    border-radius: 12px;
    flex-shrink: 0;
    transition: border-color 0.2s;
}

.apps-search-bar:focus-within {
    border-color: rgba(99,102,241,0.5);
    background: rgba(99,102,241,0.06);
}

.apps-search-bar svg {
    color: #475569;
    flex-shrink: 0;
}

.apps-search-bar input {
    background: none;
    border: none;
    outline: none;
    width: 100%;
    font-size: 0.82rem;
    color: #e2e8f0;
}

.apps-search-bar input::placeholder { color: #475569; }

/* Section label */
.apps-section-label {
    font-size: 0.68rem;
    font-weight: 700;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: #475569;
    padding: 0 20px 8px;
    flex-shrink: 0;
}

/* Apps Grid */
.apps-grid {
    display: flex;
    flex-direction: column;
    gap: 3px;
    padding: 0 10px;
    overflow-y: auto;
    flex: 1;
    scrollbar-width: thin;
    scrollbar-color: rgba(255,255,255,0.08) transparent;
}

.apps-grid::-webkit-scrollbar { width: 4px; }
.apps-grid::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.08); border-radius: 4px; }

/* App Card */
.app-card {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 10px 12px;
    border-radius: 12px;
    cursor: pointer;
    border: 1px solid transparent;
    transition: all 0.2s ease;
    position: relative;
    overflow: hidden;
}

.app-card::before {
    content: '';
    position: absolute;
    inset: 0;
    background: rgba(255,255,255,0);
    transition: background 0.2s ease;
}

.app-card:hover {
    background: rgba(255,255,255,0.04);
    border-color: rgba(255,255,255,0.07);
}

.app-card:hover::before {
    background: rgba(255,255,255,0.02);
}

.app-card:active {
    transform: scale(0.98);
}

.app-card.active {
    background: rgba(99,102,241,0.06);
    border-color: rgba(99,102,241,0.15);
}

.app-card.hidden { display: none; }

.app-card-icon {
    width: 42px;
    height: 42px;
    border-radius: 11px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    box-shadow: 0 4px 12px rgba(0,0,0,0.3);
    font-size: 1.1rem;
}

.app-card-info {
    flex: 1;
    min-width: 0;
}

.app-name {
    display: block;
    font-size: 0.83rem;
    font-weight: 600;
    color: #e2e8f0;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.app-desc {
    display: block;
    font-size: 0.72rem;
    color: #475569;
    margin-top: 1px;
}

/* Badges */
.app-card-badge {
    flex-shrink: 0;
    font-size: 0.6rem;
    font-weight: 700;
    letter-spacing: 0.05em;
    padding: 3px 7px;
    border-radius: 6px;
}

.app-card-badge.installed {
    background: rgba(16,185,129,0.15);
    color: #10b981;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 22px;
    height: 22px;
    padding: 0;
    border-radius: 50%;
}

.app-card-badge.pro {
    background: rgba(234,179,8,0.12);
    color: #eab308;
    text-transform: uppercase;
}

.app-card-badge.new {
    background: rgba(99,102,241,0.15);
    color: #818cf8;
    text-transform: uppercase;
}

/* Footer */
.apps-panel-footer {
    padding: 14px 16px;
    border-top: 1px solid rgba(255,255,255,0.06);
    flex-shrink: 0;
}

.apps-explore-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
    width: 100%;
    padding: 10px;
    border-radius: 12px;
    background: rgba(99,102,241,0.1);
    border: 1px solid rgba(99,102,241,0.2);
    color: #818cf8;
    font-size: 0.8rem;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.2s ease;
}

.apps-explore-btn:hover {
    background: rgba(99,102,241,0.18);
    color: #a5b4fc;
    text-decoration: none;
}

/* Toast notification for app open */
.app-toast {
    position: fixed;
    bottom: 90px;
    right: 28px;
    z-index: 1080;
    background: #1e293b;
    border: 1px solid rgba(255,255,255,0.08);
    color: #e2e8f0;
    padding: 10px 16px;
    border-radius: 12px;
    font-size: 0.8rem;
    font-weight: 500;
    box-shadow: 0 8px 24px rgba(0,0,0,0.3);
    transform: translateY(10px);
    opacity: 0;
    transition: all 0.3s cubic-bezier(.34,1.56,.64,1);
    pointer-events: none;
}

.app-toast.show {
    transform: translateY(0);
    opacity: 1;
}

/* Empty state */
.apps-empty {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 12px;
    padding: 60px 20px;
    color: #475569;
    text-align: center;
}

.apps-empty svg {
    opacity: 0.5;
}

.apps-empty p {
    font-size: 0.8rem;
    margin: 0;
}
</style>

<script>
// Configuration des routes
const APPS_API = {
    getPlugins: '{{ route("modules.get-plugins") }}',
    getCategories: '{{ route("modules.categories") }}',
};

let appsData = [];

// Charger les applications depuis la base de données
async function loadAppsFromDatabase() {
    const grid = document.getElementById('appsGrid');
    
    try {
        const response = await fetch(`${APPS_API.getPlugins}?per_page=100&status=active`);
        const result = await response.json();
        
        if (result.success && result.data.length > 0) {
            appsData = result.data;
            renderApps(appsData);
        } else {
            showEmptyState();
        }
    } catch (error) {
        console.error('Error loading apps:', error);
        showEmptyState();
    }
}

// Rendre les applications dans la grille
function renderApps(plugins) {
    const grid = document.getElementById('appsGrid');
    
    if (!plugins || plugins.length === 0) {
        showEmptyState();
        return;
    }
    
    // Filtrer seulement les plugins actifs
    const activePlugins = plugins.filter(p => p.status === 'active');
    
    if (activePlugins.length === 0) {
        showEmptyState();
        return;
    }
    
    grid.innerHTML = activePlugins.map(plugin => `
        <div class="app-card" data-name="${escapeHtml(plugin.name).toLowerCase()}" data-id="${plugin.id}" onclick="openApp(${plugin.id})">
            <div class="app-card-icon" style="background: ${getGradientForApp(plugin.type)};">
                <i class="${plugin.icon || 'fas fa-puzzle-piece'}" style="color: white; font-size: 1.1rem;"></i>
            </div>
            <div class="app-card-info">
                <span class="app-name">${escapeHtml(plugin.name)}</span>
                <span class="app-desc">${escapeHtml(plugin.description.substring(0, 50))}${plugin.description.length > 50 ? '...' : ''}</span>
            </div>
            ${getAppBadge(plugin)}
        </div>
    `).join('');
}

// Obtenir le gradient en fonction du type
function getGradientForApp(type) {
    const gradients = {
        'core': 'linear-gradient(135deg, #6366f1, #8b5cf6)',
        'official': 'linear-gradient(135deg, #10b981, #059669)',
        'third-party': 'linear-gradient(135deg, #f59e0b, #d97706)',
        'custom': 'linear-gradient(135deg, #3b82f6, #2563eb)'
    };
    return gradients[type] || 'linear-gradient(135deg, #6b7280, #4b5563)';
}

// Obtenir le badge approprié
function getAppBadge(plugin) {
    if (plugin.type === 'core') {
        return '<div class="app-card-badge pro">Cœur</div>';
    }
    if (plugin.type === 'official') {
        return '<div class="app-card-badge new">Officiel</div>';
    }
    if (plugin.price_type === 'paid') {
        return '<div class="app-card-badge pro">PRO</div>';
    }
    return '';
}

// Afficher l'état vide
function showEmptyState() {
    const grid = document.getElementById('appsGrid');
    grid.innerHTML = `
        <div class="apps-empty">
            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <rect x="3" y="3" width="7" height="7" rx="1.5"/>
                <rect x="14" y="3" width="7" height="7" rx="1.5"/>
                <rect x="3" y="14" width="7" height="7" rx="1.5"/>
                <rect x="14" y="14" width="7" height="7" rx="1.5"/>
            </svg>
            <p>Aucune application installée</p>
            <a href="{{ route('modules.index') }}" class="apps-explore-btn" style="margin-top: 10px;">
                Explorer la marketplace
            </a>
        </div>
    `;
}

// Filtrer les applications
function filterApps(query) {
    const cards = document.querySelectorAll('.app-card');
    const q = query.toLowerCase().trim();
    let visibleCount = 0;
    
    cards.forEach(card => {
        const name = card.dataset.name || '';
        const isVisible = q === '' || name.includes(q);
        card.classList.toggle('hidden', !isVisible);
        if (isVisible) visibleCount++;
    });
    
    // Afficher un message si aucun résultat
    const grid = document.getElementById('appsGrid');
    const existingEmpty = grid.querySelector('.apps-no-results');
    
    if (visibleCount === 0 && q !== '') {
        if (!existingEmpty) {
            const noResults = document.createElement('div');
            noResults.className = 'apps-empty apps-no-results';
            noResults.innerHTML = `
                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <circle cx="11" cy="11" r="8"/>
                    <path d="m21 21-4.35-4.35"/>
                </svg>
                <p>Aucun résultat pour "${escapeHtml(q)}"</p>
            `;
            grid.appendChild(noResults);
        }
    } else if (existingEmpty) {
        existingEmpty.remove();
    }
}

// Ouvrir une application
function openApp(pluginId) {
    const plugin = appsData.find(p => p.id == pluginId);
    if (!plugin) return;
    
    showAppToast(`Ouverture de ${plugin.name}...`);
    
    // Rediriger vers l'URL de l'application si définie
    if (plugin.demo_url) {
        window.open(plugin.demo_url, '_blank');
    } else {
        // Rediriger vers la page de gestion du module
        window.location.href = '{{ route("modules.index") }}';
    }
    
    closeAppsPanel();
}

// Afficher un toast
function showAppToast(msg) {
    let toast = document.querySelector('.app-toast');
    if (!toast) {
        toast = document.createElement('div');
        toast.className = 'app-toast';
        document.body.appendChild(toast);
    }
    toast.textContent = msg;
    toast.classList.add('show');
    setTimeout(() => toast.classList.remove('show'), 2500);
}

// Toggle le panneau
function toggleAppsPanel() {
    const btn = document.getElementById('appsLauncherBtn');
    const panel = document.getElementById('appsPanel');
    const overlay = document.getElementById('appsPanelOverlay');
    const isOpen = panel.classList.contains('open');
    
    if (isOpen) {
        closeAppsPanel();
    } else {
        panel.classList.add('open');
        overlay.classList.add('visible');
        btn.classList.add('open');
        
        // Animer les cartes
        document.querySelectorAll('.app-card').forEach((card, i) => {
            card.style.opacity = '0';
            card.style.transform = 'translateX(20px)';
            setTimeout(() => {
                card.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                card.style.opacity = '1';
                card.style.transform = 'translateX(0)';
            }, 80 + i * 40);
        });
    }
}

// Fermer le panneau
function closeAppsPanel() {
    document.getElementById('appsPanel').classList.remove('open');
    document.getElementById('appsPanelOverlay').classList.remove('visible');
    document.getElementById('appsLauncherBtn').classList.remove('open');
}

// Fonction d'échappement HTML
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Initialisation au chargement
document.addEventListener('DOMContentLoaded', function() {
    loadAppsFromDatabase();
});

// Fermer avec Echap
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') closeAppsPanel();
});
</script>