{{-- resources/views/internal-chat/new.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container" style="max-width:560px;margin-top:40px;">
    <div class="card shadow-sm">
        <div class="card-header d-flex align-items-center gap-2">
            <a href="{{ route('internal.chat.index') }}" class="text-muted me-1">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="15 18 9 12 15 6"/>
                </svg>
            </a>
            <strong>Nouvelle conversation</strong>
        </div>
        <div class="card-body">

            <div class="mb-4">
                <div class="d-flex gap-3 mb-3">
                    <button class="btn btn-sm btn-primary" id="tab-direct" onclick="switchTab('direct')">Direct (1:1)</button>
                    <button class="btn btn-sm btn-outline-secondary" id="tab-group" onclick="switchTab('group')">Groupe</button>
                </div>

                {{-- DIRECT --}}
                <div id="panel-direct">
                    <label class="form-label fw-semibold">Choisir un utilisateur</label>
                    <input type="text" class="form-control mb-2" id="user-search" placeholder="Rechercher par nom ou email…" autocomplete="off">
                    <div id="user-results" class="user-results-list"></div>
                    <input type="hidden" id="selected-user-id" name="user_id">
                    <div id="selected-user-preview" class="selected-user" style="display:none"></div>
                </div>

                {{-- GROUP --}}
                <div id="panel-group" style="display:none">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nom du groupe</label>
                        <input type="text" class="form-control" id="group-name" placeholder="Ex: Équipe marketing" maxlength="80">
                    </div>
                    <label class="form-label fw-semibold">Membres</label>
                    <input type="text" class="form-control mb-2" id="member-search" placeholder="Rechercher des membres…" autocomplete="off">
                    <div id="member-results" class="user-results-list"></div>
                    <div id="selected-members" class="selected-members-list"></div>
                </div>
            </div>

            <button class="btn btn-primary w-100" id="btn-create" disabled>Démarrer la conversation</button>
        </div>
    </div>
</div>


<style>
.user-results-list { border:1px solid var(--bs-border-color,#dee2e6); border-radius:8px; max-height:220px; overflow-y:auto; background:#fff; }
.user-result-item { display:flex; align-items:center; gap:10px; padding:10px 14px; cursor:pointer; transition:background .1s; }
.user-result-item:hover { background:var(--bs-tertiary-bg,#f8f9fa); }
.user-result-item + .user-result-item { border-top:1px solid var(--bs-border-color,#dee2e6); }
.user-result-item img { width:32px; height:32px; border-radius:50%; object-fit:cover; }
.user-result-name { font-size:13px; font-weight:600; }
.user-result-email { font-size:12px; color:var(--bs-secondary-color,#6c757d); }
.selected-user { display:flex; align-items:center; gap:10px; padding:10px 14px; background:var(--bs-primary-bg-subtle,#eef1fd); border-radius:8px; margin-top:8px; }
.selected-user img { width:36px; height:36px; border-radius:50%; object-fit:cover; }
.selected-members-list { display:flex; flex-wrap:wrap; gap:8px; margin-top:8px; }
.member-chip { display:flex; align-items:center; gap:6px; padding:4px 10px; background:var(--bs-primary-bg-subtle,#eef1fd); border-radius:20px; font-size:13px; }
.member-chip button { background:none; border:none; padding:0; cursor:pointer; color:var(--bs-secondary-color); font-size:16px; line-height:1; }
</style>
<script>
(function() {
    const csrfToken    = document.querySelector('meta[name="csrf-token"]').content;
    const searchApiUrl = "{{ url('/internal-chat/users/search') }}"; // à créer ou adapter
    const createDirectUrl = "{{ url('/internal-chat/direct') }}";
    const createGroupUrl  = "{{ url('/internal-chat/group') }}";

    let currentTab = 'direct';
    let selectedUserId = null;
    let selectedMembers = [];

    /* ── TABS ── */
    window.switchTab = function(tab) {
        currentTab = tab;
        document.getElementById('panel-direct').style.display = tab === 'direct' ? '' : 'none';
        document.getElementById('panel-group').style.display  = tab === 'group'  ? '' : 'none';
        document.getElementById('tab-direct').className = tab === 'direct' ? 'btn btn-sm btn-primary' : 'btn btn-sm btn-outline-secondary';
        document.getElementById('tab-group').className  = tab === 'group'  ? 'btn btn-sm btn-primary' : 'btn btn-sm btn-outline-secondary';
        updateCreateButton();
    };

    /* ── USER SEARCH (direct) ── */
    const userSearchInput = document.getElementById('user-search');
    const userResults     = document.getElementById('user-results');
    const selectedPreview = document.getElementById('selected-user-preview');

    let searchTimer = null;
    userSearchInput?.addEventListener('input', function() {
        clearTimeout(searchTimer);
        const q = this.value.trim();
        if (q.length < 2) { userResults.innerHTML = ''; return; }
        searchTimer = setTimeout(() => searchUsers(q, userResults, selectDirectUser), 300);
    });

    function selectDirectUser(user) {
        selectedUserId = user.id;
        userResults.innerHTML = '';
        userSearchInput.value = '';
        selectedPreview.style.display = 'flex';
        selectedPreview.innerHTML = `
            <img src="${user.avatar_url || '/img/default-avatar.png'}" alt="${escHtml(user.name)}">
            <div>
                <div style="font-size:13px;font-weight:600">${escHtml(user.name)}</div>
                <div style="font-size:12px;color:var(--bs-secondary-color)">${escHtml(user.email)}</div>
            </div>
            <button class="btn btn-sm btn-link ms-auto text-danger" onclick="clearSelectedUser()">Changer</button>
        `;
        updateCreateButton();
    }

    window.clearSelectedUser = function() {
        selectedUserId = null;
        selectedPreview.style.display = 'none';
        userSearchInput.value = '';
        updateCreateButton();
    };

    /* ── MEMBER SEARCH (group) ── */
    const memberSearchInput = document.getElementById('member-search');
    const memberResults     = document.getElementById('member-results');
    const membersContainer  = document.getElementById('selected-members');

    let memberTimer = null;
    memberSearchInput?.addEventListener('input', function() {
        clearTimeout(memberTimer);
        const q = this.value.trim();
        if (q.length < 2) { memberResults.innerHTML = ''; return; }
        memberTimer = setTimeout(() => searchUsers(q, memberResults, addMember), 300);
    });

    function addMember(user) {
        if (selectedMembers.find(m => m.id === user.id)) return;
        selectedMembers.push(user);
        memberResults.innerHTML = '';
        memberSearchInput.value = '';
        renderMemberChips();
        updateCreateButton();
    }

    function removeMember(id) {
        selectedMembers = selectedMembers.filter(m => m.id !== id);
        renderMemberChips();
        updateCreateButton();
    }

    function renderMemberChips() {
        membersContainer.innerHTML = selectedMembers.map(m => `
            <div class="member-chip">
                <img src="${m.avatar_url || '/img/default-avatar.png'}" alt="${escHtml(m.name)}" style="width:22px;height:22px;border-radius:50%;object-fit:cover">
                <span>${escHtml(m.name)}</span>
                <button onclick="removeMember(${m.id})" title="Retirer">×</button>
            </div>
        `).join('');
    }
    window.removeMember = removeMember;

    /* ── GENERIC SEARCH ── */
    async function searchUsers(q, resultsEl, onSelect) {
        try {
            const res = await fetch(`${searchApiUrl}?q=${encodeURIComponent(q)}`, {
                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken },
            });
            const data = await res.json();
            const users = data.users || [];

            if (!users.length) {
                resultsEl.innerHTML = '<div style="padding:12px 14px;font-size:13px;color:var(--bs-secondary-color)">Aucun résultat</div>';
                return;
            }

            resultsEl.innerHTML = users.map(u => `
                <div class="user-result-item" data-user-id="${u.id}">
                    <img src="${u.avatar_url || '/img/default-avatar.png'}" alt="${escHtml(u.name)}">
                    <div>
                        <div class="user-result-name">${escHtml(u.name)}</div>
                        <div class="user-result-email">${escHtml(u.email)}</div>
                    </div>
                </div>
            `).join('');

            resultsEl.querySelectorAll('.user-result-item').forEach((el, i) => {
                el.addEventListener('click', () => onSelect(users[i]));
            });
        } catch (err) {
            console.error(err);
        }
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

    function updateCreateButton() {
        const btn = document.getElementById('btn-create');
        if (currentTab === 'direct') {
            btn.disabled = !selectedUserId;
        } else {
            const name = document.getElementById('group-name')?.value.trim();
            btn.disabled = !name || selectedMembers.length < 1;
        }
    }

    document.getElementById('group-name')?.addEventListener('input', updateCreateButton);

    function escHtml(str) {
        return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }
})();
</script>
@endsection


{{-- ════════════════════════════════════════════════════════════════════
     resources/views/internal-chat/_partials/_room-item.blade.php
════════════════════════════════════════════════════════════════════ --}}
{{-- PARTIAL BELOW - separate file --}}