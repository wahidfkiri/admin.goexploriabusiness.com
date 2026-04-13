<header class="dashboard-header">
    <div class="header-content">
        <div class="header-left">
            <button class="sidebar-toggle-mobile" id="sidebarToggle">
                <i class="fas fa-bars"></i>
            </button>
            <a href="https://goexploriabusiness.com" 
               target="_blank" 
               rel="noopener noreferrer" 
               class="website-link">
                <i class="fas fa-external-link-alt"></i>
                <span>Voir site web</span>
            </a>
        </div>
        
        <div class="header-right">
            <div class="search-container">
                <i class="fas fa-search search-icon"></i>
                <input type="text" class="search-input" placeholder="Rechercher...">
            </div>
            
            {{-- Notification Button --}}
            <button class="notification-btn">
                <i class="far fa-bell"></i>
                <span class="notification-badge">3</span>
            </button>

            {{-- ══════════════════════════════════
                 CHAT MESSAGE BUTTON + DROPDOWN
            ══════════════════════════════════ --}}
            <div class="hdr-chat-wrap" id="hdrChatWrap">
                <button class="hdr-chat-btn" id="hdrChatBtn" title="Messages internes">
                    <i class="far fa-comment-dots"></i>
                    <span class="hdr-chat-badge" id="hdrChatBadge" style="display:none">0</span>
                </button>

                {{-- Dropdown panel --}}
                <div class="hdr-chat-dropdown" id="hdrChatDropdown">
                    <div class="hdr-chat-dd-head">
                        <span class="hdr-chat-dd-title">
                            <i class="fas fa-comments"></i> Messages
                        </span>
                        <a href="{{ route('internal.chat.index') }}" class="hdr-chat-dd-all">
                            Tout voir <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>

                    <div class="hdr-chat-dd-body" id="hdrChatDdBody">
                        {{-- Loading state --}}
                        <div class="hdr-chat-loading" id="hdrChatLoading">
                            <div class="hdr-chat-spinner"></div>
                        </div>
                        {{-- Rooms injected by JS --}}
                        <ul class="hdr-chat-dd-list" id="hdrChatDdList" style="display:none"></ul>
                        {{-- Empty state --}}
                        <div class="hdr-chat-dd-empty" id="hdrChatDdEmpty" style="display:none">
                            <i class="far fa-comment-dots"></i>
                            <p>Aucune conversation</p>
                        </div>
                    </div>

                    <div class="hdr-chat-dd-foot">
                        <a href="{{ route('internal.chat.new') }}" class="hdr-chat-dd-new">
                            <i class="fas fa-plus"></i> Nouvelle conversation
                        </a>
                    </div>
                </div>
            </div>
            {{-- ══ END CHAT BUTTON ══ --}}
            
            <div class="user-profile">
                <div class="user-avatar">{{ substr(auth()->user()->name, 0, 2) }}</div>
                <div class="user-info">
                    <h5>{{ auth()->user()->name }}</h5>
                    <p>Administrateur</p>
                </div>
                <i class="fas fa-chevron-down"></i>
            </div>
        </div>
    </div>
</header>

{{-- ══════════════════════════════════════════════
     HEADER CHAT STYLES
══════════════════════════════════════════════ --}}
<style>
/* ── Chat button ── */
.hdr-chat-wrap {
    position: relative;
}

.hdr-chat-btn {
    position: relative;
    background: none;
    border: none;
    color: #64748b;
    font-size: 1.2rem;
    cursor: pointer;
    padding: 6px;
    border-radius: 8px;
    transition: color .2s, background .2s;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 38px;
    height: 38px;
}
.hdr-chat-btn:hover {
    color: var(--primary-color);
    background: var(--primary-light);
}
.hdr-chat-btn.active {
    color: var(--primary-color);
    background: var(--primary-light);
}

.hdr-chat-badge {
    position: absolute;
    top: -3px;
    right: -3px;
    background: var(--danger-color);
    color: #fff;
    border-radius: 10px;
    min-width: 18px;
    height: 18px;
    font-size: 0.65rem;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0 4px;
    border: 2px solid #fff;
    animation: hdrBadgePop .3s cubic-bezier(.34,1.4,.64,1);
}
@keyframes hdrBadgePop {
    from { transform: scale(0); }
    to   { transform: scale(1); }
}

/* ── Dropdown panel ── */
.hdr-chat-dropdown {
    position: absolute;
    top: calc(100% + 12px);
    right: 0;
    width: 360px;
    background: #fff;
    border-radius: 16px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 20px 60px rgba(0,0,0,.12), 0 4px 16px rgba(0,0,0,.06);
    z-index: 9999;
    overflow: hidden;
    opacity: 0;
    visibility: hidden;
    transform: translateY(-8px) scale(.97);
    transition: opacity .2s ease, transform .2s ease, visibility .2s;
    pointer-events: none;
}
.hdr-chat-dropdown.open {
    opacity: 1;
    visibility: visible;
    transform: translateY(0) scale(1);
    pointer-events: all;
}

/* Arrow pointer */
.hdr-chat-dropdown::before {
    content: '';
    position: absolute;
    top: -7px;
    right: 12px;
    width: 14px;
    height: 14px;
    background: #fff;
    border-left: 1px solid #e2e8f0;
    border-top: 1px solid #e2e8f0;
    transform: rotate(45deg);
    z-index: 1;
}

/* Head */
.hdr-chat-dd-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 16px 18px 12px;
    border-bottom: 1px solid #f1f5f9;
}
.hdr-chat-dd-title {
    font-size: 14px;
    font-weight: 700;
    color: #1e293b;
    display: flex;
    align-items: center;
    gap: 8px;
}
.hdr-chat-dd-title i { color: var(--primary-color); }
.hdr-chat-dd-all {
    font-size: 12px;
    color: var(--primary-color);
    text-decoration: none;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 5px;
    transition: opacity .15s;
}
.hdr-chat-dd-all:hover { opacity: .75; }

/* Body */
.hdr-chat-dd-body {
    max-height: 340px;
    overflow-y: auto;
}
.hdr-chat-dd-body::-webkit-scrollbar { width: 4px; }
.hdr-chat-dd-body::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 4px; }

/* Loading spinner */
.hdr-chat-loading {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 32px;
}
.hdr-chat-spinner {
    width: 24px;
    height: 24px;
    border: 2.5px solid #e2e8f0;
    border-top-color: var(--primary-color);
    border-radius: 50%;
    animation: hdrSpin .7s linear infinite;
}
@keyframes hdrSpin { to { transform: rotate(360deg); } }

/* Room list */
.hdr-chat-dd-list {
    list-style: none;
    margin: 0;
    padding: 6px 0;
}
.hdr-chat-dd-item a {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 10px 18px;
    text-decoration: none;
    color: inherit;
    transition: background .12s;
}
.hdr-chat-dd-item a:hover { background: #f8fafc; }

.hdr-dd-avatar {
    width: 40px;
    height: 40px;
    border-radius: 12px;
    background: linear-gradient(135deg, var(--primary-color), #7b5ea7);
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 13px;
    font-weight: 700;
    flex-shrink: 0;
    overflow: hidden;
    text-transform: uppercase;
}
.hdr-dd-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 12px;
}

.hdr-dd-meta { flex: 1; min-width: 0; }
.hdr-dd-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 6px;
}
.hdr-dd-name {
    font-size: 13px;
    font-weight: 700;
    color: #1e293b;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.hdr-dd-time {
    font-size: 11px;
    color: #94a3b8;
    white-space: nowrap;
    flex-shrink: 0;
}
.hdr-dd-preview {
    font-size: 12px;
    color: #64748b;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    margin-top: 2px;
}
.hdr-dd-preview.unread {
    font-weight: 600;
    color: #334155;
}
.hdr-dd-badge {
    background: var(--primary-color);
    color: #fff;
    font-size: 10px;
    font-weight: 800;
    border-radius: 10px;
    padding: 2px 6px;
    min-width: 18px;
    text-align: center;
    flex-shrink: 0;
}

/* Empty state */
.hdr-chat-dd-empty {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 36px 20px;
    gap: 10px;
    color: #94a3b8;
}
.hdr-chat-dd-empty i { font-size: 28px; opacity: .4; }
.hdr-chat-dd-empty p { font-size: 13px; margin: 0; }

/* Footer */
.hdr-chat-dd-foot {
    border-top: 1px solid #f1f5f9;
    padding: 12px 18px;
}
.hdr-chat-dd-new {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    width: 100%;
    padding: 10px;
    background: var(--primary-light);
    color: var(--primary-color);
    border-radius: 10px;
    text-decoration: none;
    font-size: 13px;
    font-weight: 700;
    transition: background .15s;
}
.hdr-chat-dd-new:hover { background: #dde3fb; color: var(--primary-color); }
</style>

{{-- ══════════════════════════════════════════════
     HEADER CHAT SCRIPT
══════════════════════════════════════════════ --}}
<script>
(function () {
    'use strict';

    const btn       = document.getElementById('hdrChatBtn');
    const dropdown  = document.getElementById('hdrChatDropdown');
    const badge     = document.getElementById('hdrChatBadge');
    const loading   = document.getElementById('hdrChatLoading');
    const list      = document.getElementById('hdrChatDdList');
    const empty     = document.getElementById('hdrChatDdEmpty');
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';

    const roomsApiUrl    = '/internal-chat/rooms';
    const heartbeatUrl   = '/internal-chat/heartbeat';

    let isOpen    = false;
    let loaded    = false;
    let totalUnread = 0;

    /* ── Toggle dropdown ── */
    btn.addEventListener('click', (e) => {
        e.stopPropagation();
        isOpen = !isOpen;
        dropdown.classList.toggle('open', isOpen);
        btn.classList.toggle('active', isOpen);

        if (isOpen && !loaded) {
            fetchRooms();
        }
    });

    /* ── Close on outside click ── */
    document.addEventListener('click', (e) => {
        if (!document.getElementById('hdrChatWrap').contains(e.target)) {
            isOpen = false;
            dropdown.classList.remove('open');
            btn.classList.remove('active');
        }
    });

    /* ── Fetch rooms ── */
    async function fetchRooms() {
        loading.style.display = 'flex';
        list.style.display    = 'none';
        empty.style.display   = 'none';

        try {
            const res  = await fetch(roomsApiUrl, {
                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                credentials: 'same-origin',
            });
            const data = await res.json();
            const rooms = (data.rooms || []).slice(0, 6); // Show max 6 recent

            totalUnread = data.total_unread || 0;
            updateBadge(totalUnread);

            loading.style.display = 'none';
            loaded = true;

            if (!rooms.length) {
                empty.style.display = 'flex';
                return;
            }

            list.innerHTML = rooms.map(r => renderRoomItem(r)).join('');
            list.style.display = 'block';

        } catch (err) {
            loading.style.display = 'none';
            empty.style.display   = 'flex';
            console.error('Chat header fetch error:', err);
        }
    }

    /* ── Render a room row ── */
    function renderRoomItem(r) {
        const last    = r.last_message;
        const preview = last
            ? (last.type === 'file' ? '📎 Fichier joint' : esc(last.body || '').substring(0, 42))
            : 'Aucun message';
        const time    = last ? relativeTime(last.created_at) : '';
        const unread  = r.unread_count || 0;
        const initials = esc((r.name || '?').substring(0, 2));
        const avatarHtml = r.avatar
            ? `<img src="${esc(r.avatar)}" alt="${esc(r.name)}">`
            : initials;

        return `
            <li class="hdr-chat-dd-item">
                <a href="/admin/chat/room/${r.id}">
                    <div class="hdr-dd-avatar">${avatarHtml}</div>
                    <div class="hdr-dd-meta">
                        <div class="hdr-dd-row">
                            <span class="hdr-dd-name">${esc(r.name)}</span>
                            ${time ? `<span class="hdr-dd-time">${time}</span>` : ''}
                        </div>
                        <div class="hdr-dd-row">
                            <span class="hdr-dd-preview ${unread > 0 ? 'unread' : ''}">${preview}</span>
                            ${unread > 0 ? `<span class="hdr-dd-badge">${unread > 99 ? '99+' : unread}</span>` : ''}
                        </div>
                    </div>
                </a>
            </li>`;
    }

    /* ── Badge update ── */
    function updateBadge(count) {
        if (count > 0) {
            badge.textContent = count > 99 ? '99+' : count;
            badge.style.display = 'flex';
        } else {
            badge.style.display = 'none';
        }
    }

    /* ── Heartbeat — poll total unread every 30s ── */
    async function heartbeat() {
        try {
            const res  = await fetch(heartbeatUrl, {
                method: 'POST',
                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                credentials: 'same-origin',
            });
            const data = await res.json();
            const count = data.total_unread || 0;
            updateBadge(count);

            // If dropdown is open and count changed, refresh list
            if (isOpen && count !== totalUnread) {
                totalUnread = count;
                loaded = false;
                fetchRooms();
            } else {
                totalUnread = count;
            }
        } catch (e) { /* silent */ }
    }

    /* ── Helpers ── */
    function esc(str) {
        return String(str ?? '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }

    function relativeTime(isoStr) {
        if (!isoStr) return '';
        const diff  = Math.floor((Date.now() - new Date(isoStr)) / 1000);
        if (diff < 60)    return 'maintenant';
        if (diff < 3600)  return Math.floor(diff / 60) + 'm';
        if (diff < 86400) return Math.floor(diff / 3600) + 'h';
        return Math.floor(diff / 86400) + 'j';
    }

    /* ── Init ── */
    heartbeat();
    setInterval(() => {
        if (!document.hidden) {
            heartbeat();
        }
    }, 30000);

    document.addEventListener('visibilitychange', () => {
        if (!document.hidden) {
            heartbeat();
        }
    });
})();
</script>
