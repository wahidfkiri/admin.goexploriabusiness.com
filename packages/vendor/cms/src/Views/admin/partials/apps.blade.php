{{-- ============================================================
     APPS LAUNCHER - Floating Button + Right Panel
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

        <div class="app-card" data-name="geo map marker" onclick="openApp('geo-map')">
            <div class="app-card-icon" style="background: linear-gradient(135deg, #1e3a5f, #2563eb);">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
            </div>
            <div class="app-card-info">
                <span class="app-name">Geo Map</span>
                <span class="app-desc">Cartographie & zones</span>
            </div>
        </div>

        <div class="app-card" data-name="ecommerce suite boutique" onclick="openApp('ecommerce')">
            <div class="app-card-icon" style="background: linear-gradient(135deg, #064e3b, #10b981);">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
            </div>
            <div class="app-card-info">
                <span class="app-name">Ecommerce Suite</span>
                <span class="app-desc">Boutique en ligne</span>
            </div>
        </div>

        <div class="app-card" data-name="analytics pro statistiques" onclick="openApp('analytics')">
            <div class="app-card-icon" style="background: linear-gradient(135deg, #1e1b4b, #7c3aed);">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
            </div>
            <div class="app-card-info">
                <span class="app-name">Analytics Pro</span>
                <span class="app-desc">Statistiques avancées</span>
            </div>
        </div>

        <div class="app-card" data-name="seo optimizer référencement" onclick="openApp('seo')">
            <div class="app-card-icon" style="background: linear-gradient(135deg, #7c2d12, #ea580c);">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/><path d="M11 8v3l2 2"/></svg>
            </div>
            <div class="app-card-info">
                <span class="app-name">SEO Optimizer</span>
                <span class="app-desc">Référencement naturel</span>
            </div>
        </div>

        <div class="app-card" data-name="crm contacts clients" onclick="openApp('crm')">
            <div class="app-card-icon" style="background: linear-gradient(135deg, #0f172a, #334155);">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            </div>
            <div class="app-card-info">
                <span class="app-name">CRM</span>
                <span class="app-desc">Gestion des contacts</span>
            </div>
        </div>

        <div class="app-card" data-name="email marketing campagne" onclick="openApp('email')">
            <div class="app-card-icon" style="background: linear-gradient(135deg, #0c4a6e, #0ea5e9);">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
            </div>
            <div class="app-card-info">
                <span class="app-name">Email Marketing</span>
                <span class="app-desc">Campagnes ciblées</span>
            </div>
        </div>

        <div class="app-card" data-name="booking réservation agenda" onclick="openApp('booking')">
            <div class="app-card-icon" style="background: linear-gradient(135deg, #134e4a, #14b8a6);">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
            </div>
            <div class="app-card-info">
                <span class="app-name">Booking</span>
                <span class="app-desc">Réservations en ligne</span>
            </div>
        </div>

        <div class="app-card" data-name="chat support client live" onclick="openApp('chat')">
            <div class="app-card-icon" style="background: linear-gradient(135deg, #450a0a, #dc2626);">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
            </div>
            <div class="app-card-info">
                <span class="app-name">Live Chat</span>
                <span class="app-desc">Support en temps réel</span>
            </div>
        </div>

        <div class="app-card" data-name="forms formulaire survey" onclick="openApp('forms')">
            <div class="app-card-icon" style="background: linear-gradient(135deg, #3b0764, #a855f7);">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
            </div>
            <div class="app-card-info">
                <span class="app-name">Smart Forms</span>
                <span class="app-desc">Formulaires & surveys</span>
            </div>
        </div>

    </div>


    <div class="apps-panel-footer">
        <a href="#" class="apps-explore-btn">
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


@keyframes pulseDot {
    0%, 100% { box-shadow: 0 0 0 0 rgba(16,185,129,0.5); }
    50%       { box-shadow: 0 0 0 4px rgba(16,185,129,0); }
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
    width: 360px;
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
</style>

<script>
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
        // Animate cards in
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

function closeAppsPanel() {
    document.getElementById('appsPanel').classList.remove('open');
    document.getElementById('appsPanelOverlay').classList.remove('visible');
    document.getElementById('appsLauncherBtn').classList.remove('open');
}

function filterApps(query) {
    const cards = document.querySelectorAll('.app-card');
    const q = query.toLowerCase().trim();
    cards.forEach(card => {
        const name = card.dataset.name || '';
        card.classList.toggle('hidden', q !== '' && !name.includes(q));
    });
}

function openApp(appId) {
    const names = {
        'geo-map': 'Geo Map',
        'ecommerce': 'Ecommerce Suite',
        'analytics': 'Analytics Pro',
        'seo': 'SEO Optimizer',
        'crm': 'CRM',
        'email': 'Email Marketing',
        'booking': 'Booking',
        'chat': 'Live Chat',
        'forms': 'Smart Forms'
    };
    
    showAppToast('Ouverture de ' + (names[appId] || appId) + '…');
    closeAppsPanel();
}

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

// Close on Escape
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') closeAppsPanel();
});
</script>