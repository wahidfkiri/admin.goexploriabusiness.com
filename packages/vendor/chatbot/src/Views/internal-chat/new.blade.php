{{-- resources/views/vendor/chatbot/internal-chat/new.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="ic-new-page">
    <div class="ic-new-card">

        {{-- Header --}}
        <div class="ic-new-header">
            <a href="{{ route('internal.chat.index') }}" class="ic-new-back">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h2 class="ic-new-title">Nouvelle conversation</h2>
                <p class="ic-new-subtitle">Démarrez un chat direct ou créez un groupe</p>
            </div>
        </div>

        {{-- Tabs --}}
        <div class="ic-tabs">
            <button class="ic-tab ic-tab-active" id="tab-direct" onclick="switchTab('direct')">
                <i class="far fa-user"></i> Direct (1:1)
            </button>
            <button class="ic-tab" id="tab-group" onclick="switchTab('group')">
                <i class="fas fa-users"></i> Groupe
            </button>
        </div>

        {{-- DIRECT PANEL --}}
        <div id="panel-direct" class="ic-panel">
            <label class="ic-label">Choisir un utilisateur</label>
            <div class="ic-search-field">
                <i class="fas fa-search"></i>
                <input type="text" class="ic-search-inner" id="user-search" placeholder="Rechercher par nom ou email…" autocomplete="off">
            </div>
            <div id="user-results" class="ic-results-list"></div>
            <div id="selected-user-preview" class="ic-selected-user" style="display:none"></div>
        </div>

        {{-- GROUP PANEL --}}
        <div id="panel-group" class="ic-panel" style="display:none">
            <div class="ic-form-group">
                <label class="ic-label">Nom du groupe</label>
                <div class="ic-search-field">
                    <i class="fas fa-tag"></i>
                    <input type="text" class="ic-search-inner" id="group-name" placeholder="Ex: Équipe marketing" maxlength="80">
                </div>
            </div>
            <div class="ic-form-group">
                <label class="ic-label">Ajouter des membres</label>
                <div class="ic-search-field">
                    <i class="fas fa-search"></i>
                    <input type="text" class="ic-search-inner" id="member-search" placeholder="Rechercher des membres…" autocomplete="off">
                </div>
                <div id="member-results" class="ic-results-list"></div>
            </div>
            <div id="selected-members" class="ic-chips"></div>
        </div>

        {{-- Action --}}
        <button class="ic-btn-create" id="btn-create" disabled>
            <i class="fas fa-comment-dots"></i>
            Démarrer la conversation
        </button>
    </div>
</div>

@include('chatbot::internal-chat._partials._styles')

<style>
/* New conversation page styles */
.ic-new-page {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 40px 16px;
    min-height: calc(100vh - var(--header-height, 70px));
    background: var(--ic-bg);
}
.ic-new-card {
    width: 100%;
    max-width: 560px;
    background: var(--ic-surface);
    border-radius: 20px;
    box-shadow: 0 8px 40px rgba(0,0,0,.08);
    overflow: hidden;
    border: 1px solid var(--ic-border);
}
.ic-new-header {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 28px 28px 20px;
    border-bottom: 1px solid var(--ic-border);
}
.ic-new-back {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 38px;
    height: 38px;
    border-radius: 50%;
    background: var(--ic-bg);
    color: var(--ic-text);
    text-decoration: none;
    flex-shrink: 0;
    transition: background .15s;
}
.ic-new-back:hover { background: var(--ic-border); color: var(--ic-text); }
.ic-new-title { font-size: 18px; font-weight: 700; margin: 0; color: var(--ic-text); }
.ic-new-subtitle { font-size: 13px; color: var(--ic-muted); margin: 2px 0 0; }

.ic-tabs {
    display: flex;
    gap: 0;
    padding: 16px 28px 0;
    border-bottom: 1px solid var(--ic-border);
}
.ic-tab {
    padding: 10px 20px;
    border: none;
    background: none;
    font-size: 13px;
    font-weight: 600;
    color: var(--ic-muted);
    cursor: pointer;
    border-bottom: 2px solid transparent;
    transition: color .15s, border-color .15s;
    display: flex;
    align-items: center;
    gap: 7px;
    margin-bottom: -1px;
}
.ic-tab.ic-tab-active {
    color: var(--ic-primary);
    border-bottom-color: var(--ic-primary);
}
.ic-tab:hover { color: var(--ic-text); }

.ic-panel { padding: 24px 28px 0; }
.ic-form-group { margin-bottom: 20px; }
.ic-label {
    display: block;
    font-size: 12px;
    font-weight: 700;
    letter-spacing: .06em;
    text-transform: uppercase;
    color: var(--ic-muted);
    margin-bottom: 8px;
}
.ic-search-field {
    display: flex;
    align-items: center;
    gap: 10px;
    background: var(--ic-bg);
    border: 1.5px solid var(--ic-border);
    border-radius: 12px;
    padding: 0 14px;
    transition: border-color .15s;
}
.ic-search-field:focus-within { border-color: var(--ic-primary); background: var(--ic-surface); }
.ic-search-field > i { color: var(--ic-muted); font-size: 13px; flex-shrink: 0; }
.ic-search-inner {
    flex: 1;
    border: none;
    background: transparent;
    padding: 13px 0;
    font-size: 14px;
    color: var(--ic-text);
    outline: none;
}
.ic-search-inner::placeholder { color: var(--ic-muted); }

.ic-results-list {
    margin-top: 8px;
    border: 1.5px solid var(--ic-border);
    border-radius: 12px;
    overflow: hidden;
    max-height: 240px;
    overflow-y: auto;
    display: none;
}
.ic-results-list:not(:empty) { display: block; }
.ic-result-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 16px;
    cursor: pointer;
    transition: background .1s;
    border-bottom: 1px solid var(--ic-border);
}
.ic-result-item:last-child { border-bottom: none; }
.ic-result-item:hover { background: var(--ic-bg); }
.ic-result-avatar {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: var(--ic-primary);
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 13px;
    font-weight: 700;
    flex-shrink: 0;
    overflow: hidden;
}
.ic-result-avatar img { width: 100%; height: 100%; object-fit: cover; }
.ic-result-name { font-size: 13px; font-weight: 600; color: var(--ic-text); }
.ic-result-email { font-size: 12px; color: var(--ic-muted); margin-top: 1px; }
.ic-result-online { width: 8px; height: 8px; border-radius: 50%; background: #22c55e; margin-left: auto; flex-shrink: 0; }

.ic-selected-user {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 16px;
    background: color-mix(in srgb, var(--ic-primary) 8%, transparent);
    border: 1.5px solid color-mix(in srgb, var(--ic-primary) 30%, transparent);
    border-radius: 12px;
    margin-top: 10px;
}
.ic-chips {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-top: 10px;
}
.ic-chip {
    display: flex;
    align-items: center;
    gap: 6px;
    padding: 6px 10px 6px 8px;
    background: color-mix(in srgb, var(--ic-primary) 10%, transparent);
    border: 1.5px solid color-mix(in srgb, var(--ic-primary) 25%, transparent);
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    color: var(--ic-primary);
}
.ic-chip-remove {
    background: none;
    border: none;
    padding: 0;
    cursor: pointer;
    color: var(--ic-primary);
    display: flex;
    font-size: 12px;
    opacity: .7;
}
.ic-chip-remove:hover { opacity: 1; }
.ic-chip-avatar {
    width: 20px;
    height: 20px;
    border-radius: 50%;
    overflow: hidden;
    background: var(--ic-primary);
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 9px;
    font-weight: 700;
}
.ic-chip-avatar img { width: 100%; height: 100%; object-fit: cover; }

.ic-btn-create {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    width: calc(100% - 56px);
    margin: 24px 28px;
    padding: 14px;
    background: var(--ic-primary);
    color: #fff;
    border: none;
    border-radius: 12px;
    font-size: 14px;
    font-weight: 700;
    cursor: pointer;
    transition: opacity .15s, transform .1s;
}
.ic-btn-create:not(:disabled):hover { opacity: .92; }
.ic-btn-create:not(:disabled):active { transform: scale(.98); }
.ic-btn-create:disabled { opacity: .4; cursor: not-allowed; }
</style>

<script>
(function() {
    const csrfToken       = document.querySelector('meta[name="csrf-token"]').content;
    const searchUrl       = "{{ url('/internal-chat/users/search') }}";
    const createDirectUrl = "{{ url('/internal-chat/direct') }}";
    const createGroupUrl  = "{{ url('/internal-chat/group') }}";

    let currentTab     = 'direct';
    let selectedUserId = null;
    let selectedMembers = [];

    /* ── TABS ── */
    window.switchTab = function(tab) {
        currentTab = tab;
        document.getElementById('panel-direct').style.display = tab === 'direct' ? '' : 'none';
        document.getElementById('panel-group').style.display  = tab === 'group'  ? '' : 'none';
        document.getElementById('tab-direct').className = 'ic-tab' + (tab === 'direct' ? ' ic-tab-active' : '');
        document.getElementById('tab-group').className  = 'ic-tab' + (tab === 'group'  ? ' ic-tab-active' : '');
        updateBtn();
    };

    /* ── DIRECT SEARCH ── */
    let searchTimer = null;
    document.getElementById('user-search')?.addEventListener('input', function() {
        clearTimeout(searchTimer);
        const q = this.value.trim();
        const el = document.getElementById('user-results');
        if (q.length < 2) { el.innerHTML = ''; return; }
        searchTimer = setTimeout(() => searchUsers(q, el, selectDirectUser), 300);
    });

    function selectDirectUser(user) {
        selectedUserId = user.id;
        document.getElementById('user-results').innerHTML = '';
        document.getElementById('user-search').value = '';
        const preview = document.getElementById('selected-user-preview');
        preview.style.display = 'flex';
        preview.innerHTML = `
            <div class="ic-result-avatar">${user.avatar_url ? `<img src="${esc(user.avatar_url)}" alt="">` : esc(user.name.substring(0,2))}</div>
            <div><div class="ic-result-name">${esc(user.name)}</div><div class="ic-result-email">${esc(user.email)}</div></div>
            <button class="btn btn-sm btn-link ms-auto" style="color:var(--ic-muted);font-size:12px" onclick="clearSelected()">Changer</button>
        `;
        updateBtn();
    }

    window.clearSelected = function() {
        selectedUserId = null;
        document.getElementById('selected-user-preview').style.display = 'none';
        updateBtn();
    };

    /* ── GROUP SEARCH ── */
    let memberTimer = null;
    document.getElementById('member-search')?.addEventListener('input', function() {
        clearTimeout(memberTimer);
        const q = this.value.trim();
        const el = document.getElementById('member-results');
        if (q.length < 2) { el.innerHTML = ''; return; }
        memberTimer = setTimeout(() => searchUsers(q, el, addMember), 300);
    });
    document.getElementById('group-name')?.addEventListener('input', updateBtn);

    function addMember(user) {
        if (selectedMembers.find(m => m.id === user.id)) return;
        selectedMembers.push(user);
        document.getElementById('member-results').innerHTML = '';
        document.getElementById('member-search').value = '';
        renderChips();
        updateBtn();
    }

    window.removeMember = function(id) {
        selectedMembers = selectedMembers.filter(m => m.id !== id);
        renderChips();
        updateBtn();
    };

    function renderChips() {
        document.getElementById('selected-members').innerHTML = selectedMembers.map(m => `
            <div class="ic-chip">
                <div class="ic-chip-avatar">${m.avatar_url ? `<img src="${esc(m.avatar_url)}" alt="">` : esc(m.name.substring(0,2))}</div>
                ${esc(m.name)}
                <button class="ic-chip-remove" onclick="removeMember(${m.id})"><i class="fas fa-times"></i></button>
            </div>
        `).join('');
    }

    /* ── SEARCH API ── */
    async function searchUsers(q, resultsEl, onSelect) {
        try {
            const res = await fetch(`${searchUrl}?q=${encodeURIComponent(q)}`, {
                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken },
            });
            const data = await res.json();
            const users = data.users || [];
            if (!users.length) {
                resultsEl.innerHTML = `<div class="ic-result-item" style="cursor:default;color:var(--ic-muted)">Aucun résultat</div>`;
                return;
            }
            resultsEl.innerHTML = users.map((u, i) => `
                <div class="ic-result-item" data-idx="${i}">
                    <div class="ic-result-avatar">${u.avatar_url ? `<img src="${esc(u.avatar_url)}" alt="">` : esc(u.name.substring(0,2))}</div>
                    <div>
                        <div class="ic-result-name">${esc(u.name)}</div>
                        <div class="ic-result-email">${esc(u.email)}</div>
                    </div>
                    ${u.online ? '<div class="ic-result-online"></div>' : ''}
                </div>
            `).join('');
            resultsEl.querySelectorAll('.ic-result-item').forEach((el, i) => {
                el.addEventListener('click', () => onSelect(users[i]));
            });
        } catch (e) { console.error(e); }
    }

    /* ── CREATE ── */
    document.getElementById('btn-create').addEventListener('click', async () => {
        if (currentTab === 'direct') {
            if (!selectedUserId) return;
            const res = await fetch(createDirectUrl, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                body: JSON.stringify({ user_id: selectedUserId }),
            });
            const data = await res.json();
            if (data.room_id) window.location = `/admin/chat/room/${data.room_id}`;
        } else {
            const name    = document.getElementById('group-name').value.trim();
            const userIds = selectedMembers.map(m => m.id);
            if (!name || userIds.length < 1) return;
            const res = await fetch(createGroupUrl, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                body: JSON.stringify({ name, user_ids: userIds }),
            });
            const data = await res.json();
            if (data.room_id) window.location = `/admin/chat/room/${data.room_id}`;
        }
    });

    function updateBtn() {
        const btn = document.getElementById('btn-create');
        if (currentTab === 'direct') {
            btn.disabled = !selectedUserId;
        } else {
            const name = document.getElementById('group-name')?.value.trim();
            btn.disabled = !name || selectedMembers.length < 1;
        }
    }

    function esc(str) {
        return String(str ?? '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }
})();
</script>
@endsection
