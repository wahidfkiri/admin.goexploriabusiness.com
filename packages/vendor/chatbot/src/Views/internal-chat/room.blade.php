{{-- resources/views/internal-chat/room.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="chat-layout" id="chat-app"
     data-room-id="{{ $room->id }}"
     data-current-user="{{ auth()->id() }}"
     data-last-id="{{ $lastMessageId }}"
     data-poll-url="{{ route('api.internal.chat.poll', $room->id) }}"
     data-send-url="{{ route('api.internal.chat.send', $room->id) }}"
     data-read-url="{{ route('api.internal.chat.read', $room->id) }}">

    {{-- ══════════════════════════════════════════
         SIDEBAR — liste des rooms
    ══════════════════════════════════════════ --}}
    <aside class="chat-sidebar">
        <div class="sidebar-header">
            <span class="sidebar-title">Messages</span>
            <a href="{{ route('internal.chat.new') }}" class="btn-new-chat" title="Nouvelle conversation">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                </svg>
            </a>
        </div>

        <div class="sidebar-search">
            <input type="text" id="room-search" placeholder="Rechercher..." autocomplete="off">
        </div>

        <ul class="room-list" id="room-list">
            @foreach($rooms as $r)
                @include('chatbot::internal-chat._partials._room-item', ['r' => $r, 'active' => $r->id === $room->id])
            @endforeach
        </ul>
    </aside>

    {{-- ══════════════════════════════════════════
         ZONE PRINCIPALE — conversation
    ══════════════════════════════════════════ --}}
    <main class="chat-main">

        {{-- Header conversation --}}
        <header class="chat-header">
            <div class="chat-header-info">
                @if($room->type === 'direct')
                    @php $other = $room->users->firstWhere('id', '!=', auth()->id()) @endphp
                    <img src="{{ $other?->avatar_url }}" alt="{{ $other?->name }}" class="avatar avatar-md">
                    <div>
                        <p class="chat-header-name">{{ $other?->name ?? 'Utilisateur' }}</p>
                        <p class="chat-header-status" id="other-typing" style="display:none">en train d'écrire…</p>
                    </div>
                @else
                    <div class="avatar avatar-md avatar-group">{{ mb_substr($room->name, 0, 2) }}</div>
                    <div>
                        <p class="chat-header-name">{{ $room->name }}</p>
                        <p class="chat-header-status">{{ $room->participants->count() }} membres</p>
                    </div>
                @endif
            </div>
        </header>

        {{-- Zone messages --}}
        <div class="messages-container" id="messages-container">
            @foreach($messages as $msg)
                @include('chatbot::internal-chat._partials._message', ['msg' => $msg])
            @endforeach
        </div>

        {{-- Indicateur "en train d'écrire" --}}
        <div class="typing-indicator" id="typing-indicator" style="display:none">
            <span></span><span></span><span></span>
        </div>

        {{-- Zone saisie --}}
        <form class="message-form" id="message-form" autocomplete="off">
            @csrf
            <div class="message-input-wrapper">
                <textarea
                    id="message-input"
                    class="message-input"
                    placeholder="Écrivez un message…"
                    rows="1"
                    maxlength="2000"
                ></textarea>
                <button type="submit" class="btn-send" id="btn-send" disabled>
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="22" y1="2" x2="11" y2="13"/>
                        <polygon points="22 2 15 22 11 13 2 9 22 2"/>
                    </svg>
                </button>
            </div>
        </form>
    </main>
</div>


<style>
/* ── Layout ── */
.chat-layout {
    display: flex;
    height: calc(100vh - var(--navbar-height, 60px));
    overflow: hidden;
    background: var(--bs-body-bg, #f8f9fa);
}

/* ── Sidebar ── */
.chat-sidebar {
    width: 280px;
    min-width: 240px;
    display: flex;
    flex-direction: column;
    border-right: 1px solid var(--bs-border-color, #dee2e6);
    background: #fff;
    overflow: hidden;
}
.sidebar-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 16px 16px 12px;
    border-bottom: 1px solid var(--bs-border-color, #dee2e6);
}
.sidebar-title {
    font-size: 16px;
    font-weight: 600;
    color: var(--bs-body-color, #212529);
}
.btn-new-chat {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    background: var(--bs-primary, #4361ee);
    color: #fff;
    text-decoration: none;
    transition: opacity .15s;
}
.btn-new-chat:hover { opacity: .85; }

.sidebar-search {
    padding: 10px 12px;
    border-bottom: 1px solid var(--bs-border-color, #dee2e6);
}
.sidebar-search input {
    width: 100%;
    padding: 7px 12px;
    border: 1px solid var(--bs-border-color, #dee2e6);
    border-radius: 20px;
    font-size: 13px;
    outline: none;
    background: var(--bs-tertiary-bg, #f8f9fa);
}

.room-list {
    list-style: none;
    margin: 0;
    padding: 0;
    overflow-y: auto;
    flex: 1;
}
.room-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 14px;
    cursor: pointer;
    border-left: 3px solid transparent;
    transition: background .12s;
    text-decoration: none;
    color: inherit;
}
.room-item:hover { background: var(--bs-tertiary-bg, #f8f9fa); }
.room-item.active {
    background: #eef1fd;
    border-left-color: var(--bs-primary, #4361ee);
}
.room-item-meta {
    flex: 1;
    min-width: 0;
}
.room-item-name {
    font-size: 13px;
    font-weight: 600;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    color: var(--bs-body-color, #212529);
}
.room-item-preview {
    font-size: 12px;
    color: var(--bs-secondary-color, #6c757d);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    margin-top: 2px;
}
.room-item-time {
    font-size: 11px;
    color: var(--bs-secondary-color, #6c757d);
    white-space: nowrap;
}
.unread-badge {
    background: var(--bs-primary, #4361ee);
    color: #fff;
    font-size: 11px;
    font-weight: 700;
    border-radius: 10px;
    padding: 1px 6px;
    min-width: 18px;
    text-align: center;
}

/* ── Main ── */
.chat-main {
    flex: 1;
    display: flex;
    flex-direction: column;
    overflow: hidden;
    background: #fff;
}

/* ── Header ── */
.chat-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 12px 20px;
    border-bottom: 1px solid var(--bs-border-color, #dee2e6);
    min-height: 60px;
}
.chat-header-info {
    display: flex;
    align-items: center;
    gap: 12px;
}
.chat-header-name {
    font-size: 15px;
    font-weight: 600;
    margin: 0;
    color: var(--bs-body-color, #212529);
}
.chat-header-status {
    font-size: 12px;
    color: var(--bs-secondary-color, #6c757d);
    margin: 2px 0 0;
}

/* ── Avatars ── */
.avatar {
    border-radius: 50%;
    object-fit: cover;
    flex-shrink: 0;
}
.avatar-sm  { width: 32px; height: 32px; font-size: 12px; }
.avatar-md  { width: 40px; height: 40px; font-size: 14px; }
.avatar-group {
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--bs-primary, #4361ee);
    color: #fff;
    font-weight: 600;
    text-transform: uppercase;
}

/* ── Messages ── */
.messages-container {
    flex: 1;
    overflow-y: auto;
    padding: 16px 20px;
    display: flex;
    flex-direction: column;
    gap: 4px;
    scroll-behavior: smooth;
}

.msg-row {
    display: flex;
    align-items: flex-end;
    gap: 8px;
    max-width: 75%;
}
.msg-row.own { align-self: flex-end; flex-direction: row-reverse; }
.msg-row.other { align-self: flex-start; }

.msg-bubble {
    padding: 9px 13px;
    border-radius: 16px;
    font-size: 14px;
    line-height: 1.5;
    max-width: 100%;
    word-break: break-word;
}
.msg-row.own   .msg-bubble { background: var(--bs-primary, #4361ee); color: #fff; border-bottom-right-radius: 4px; }
.msg-row.other .msg-bubble { background: var(--bs-tertiary-bg, #f1f3f4); color: var(--bs-body-color, #212529); border-bottom-left-radius: 4px; }

.msg-meta {
    display: flex;
    align-items: center;
    gap: 6px;
    margin-top: 2px;
}
.msg-time {
    font-size: 11px;
    color: var(--bs-secondary-color, #6c757d);
    white-space: nowrap;
}
.msg-name {
    font-size: 12px;
    color: var(--bs-secondary-color, #6c757d);
    font-weight: 500;
}
.msg-event {
    align-self: center;
    font-size: 12px;
    color: var(--bs-secondary-color, #6c757d);
    padding: 4px 0;
    font-style: italic;
}

/* date separator */
.date-separator {
    display: flex;
    align-items: center;
    gap: 10px;
    margin: 12px 0 8px;
    color: var(--bs-secondary-color, #6c757d);
    font-size: 12px;
}
.date-separator::before,
.date-separator::after {
    content: '';
    flex: 1;
    height: 1px;
    background: var(--bs-border-color, #dee2e6);
}

/* ── Typing indicator ── */
.typing-indicator {
    padding: 6px 20px;
    display: flex;
    gap: 4px;
    align-items: center;
    height: 28px;
}
.typing-indicator span {
    width: 6px;
    height: 6px;
    background: var(--bs-secondary-color, #6c757d);
    border-radius: 50%;
    animation: bounce 1.2s infinite;
    opacity: .6;
}
.typing-indicator span:nth-child(2) { animation-delay: .2s; }
.typing-indicator span:nth-child(3) { animation-delay: .4s; }
@keyframes bounce {
    0%, 60%, 100% { transform: translateY(0); }
    30% { transform: translateY(-5px); }
}

/* ── Input form ── */
.message-form {
    padding: 12px 16px;
    border-top: 1px solid var(--bs-border-color, #dee2e6);
    background: #fff;
}
.message-input-wrapper {
    display: flex;
    align-items: flex-end;
    gap: 10px;
    background: var(--bs-tertiary-bg, #f1f3f4);
    border-radius: 24px;
    padding: 6px 6px 6px 16px;
    border: 1px solid transparent;
    transition: border-color .15s;
}
.message-input-wrapper:focus-within {
    border-color: var(--bs-primary, #4361ee);
    background: #fff;
}
.message-input {
    flex: 1;
    border: none;
    background: transparent;
    resize: none;
    outline: none;
    font-size: 14px;
    line-height: 1.5;
    max-height: 120px;
    overflow-y: auto;
    color: var(--bs-body-color, #212529);
    padding: 4px 0;
}
.btn-send {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    border: none;
    background: var(--bs-primary, #4361ee);
    color: #fff;
    cursor: pointer;
    transition: opacity .15s, transform .1s;
    flex-shrink: 0;
}
.btn-send:disabled { opacity: .4; cursor: default; }
.btn-send:not(:disabled):hover { opacity: .9; }
.btn-send:not(:disabled):active { transform: scale(.93); }
</style>


<script>
(function () {
    'use strict';

    /* ── Config ── */
    const roomId      = parseInt(document.getElementById('chat-app').dataset.roomId);
    const currentUser = parseInt(document.getElementById('chat-app').dataset.currentUser);
    const pollUrl     = document.getElementById('chat-app').dataset.pollUrl;
    const sendUrl     = document.getElementById('chat-app').dataset.sendUrl;
    const readUrl     = document.getElementById('chat-app').dataset.readUrl;
    const csrfToken   = document.querySelector('meta[name="csrf-token"]').content;

    let lastId        = parseInt(document.getElementById('chat-app').dataset.lastId) || 0;
    let pollAbort     = null;
    let typingTimer   = null;
    let isTyping      = false;
    let pollActive    = true;

    /* ── DOM refs ── */
    const container   = document.getElementById('messages-container');
    const form        = document.getElementById('message-form');
    const input       = document.getElementById('message-input');
    const btnSend     = document.getElementById('btn-send');
    const typingEl    = document.getElementById('typing-indicator');
    const otherTyping = document.getElementById('other-typing');
    const roomSearch  = document.getElementById('room-search');

    /* ═══════════════════════════════════════════
       SCROLL
    ═══════════════════════════════════════════ */
    function scrollBottom(smooth = true) {
        container.scrollTo({ top: container.scrollHeight, behavior: smooth ? 'smooth' : 'instant' });
    }
    scrollBottom(false);

    /* ═══════════════════════════════════════════
       RENDER A MESSAGE
    ═══════════════════════════════════════════ */
    function renderMessage(msg) {
        const own = msg.user_id === currentUser;
        const isEvent = msg.type === 'event';

        if (isEvent) {
            const el = document.createElement('div');
            el.className = 'msg-event';
            el.textContent = msg.body;
            container.appendChild(el);
            return;
        }

        const row = document.createElement('div');
        row.className = 'msg-row ' + (own ? 'own' : 'other');
        row.dataset.msgId = msg.id;

        const time = new Date(msg.created_at).toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' });

        if (!own) {
            const avatar = document.createElement('img');
            avatar.className = 'avatar avatar-sm';
            avatar.src  = msg.user_avatar || '/img/default-avatar.png';
            avatar.alt  = msg.user_name || '';
            row.appendChild(avatar);
        }

        const col = document.createElement('div');
        col.style.cssText = 'display:flex;flex-direction:column;gap:2px;max-width:100%';

        if (!own && msg.user_name) {
            const name = document.createElement('span');
            name.className = 'msg-name';
            name.textContent = msg.user_name;
            col.appendChild(name);
        }

        const bubble = document.createElement('div');
        bubble.className = 'msg-bubble';
        bubble.textContent = msg.body;
        col.appendChild(bubble);

        const meta = document.createElement('div');
        meta.className = 'msg-meta';
        if (own) meta.style.justifyContent = 'flex-end';
        const t = document.createElement('span');
        t.className = 'msg-time';
        t.textContent = time;
        meta.appendChild(t);
        col.appendChild(meta);

        row.appendChild(col);
        container.appendChild(row);
    }

    /* ═══════════════════════════════════════════
       LONG POLLING
    ═══════════════════════════════════════════ */
    async function startPolling() {
        while (pollActive) {
            try {
                pollAbort = new AbortController();
                const res = await fetch(`${pollUrl}?last_id=${lastId}`, {
                    signal: pollAbort.signal,
                    headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                });

                if (!res.ok) { await sleep(3000); continue; }

                const data = await res.json();

                if (data.status === 'room_closed') {
                    pollActive = false;
                    break;
                }

                if (data.status === 'new_messages' && data.messages.length) {
                    const wasAtBottom = isAtBottom();
                    data.messages.forEach(renderMessage);
                    lastId = data.last_id;
                    if (wasAtBottom) scrollBottom();
                    markRead();
                    updateSidebarPreview(data.messages[data.messages.length - 1]);
                }

                // Typing indicator from other party
                if (typeof data.other_typing !== 'undefined') {
                    showTyping(data.other_typing);
                }

            } catch (err) {
                if (err.name === 'AbortError') break;
                await sleep(2000);
            }
        }
    }

    function isAtBottom() {
        return container.scrollHeight - container.scrollTop - container.clientHeight < 60;
    }

    function showTyping(show) {
        typingEl.style.display  = show ? 'flex' : 'none';
        if (otherTyping) otherTyping.style.display = show ? '' : 'none';
    }

    function sleep(ms) { return new Promise(r => setTimeout(r, ms)); }

    /* ═══════════════════════════════════════════
       SEND MESSAGE
    ═══════════════════════════════════════════ */
    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        const body = input.value.trim();
        if (!body) return;

        btnSend.disabled = true;
        input.value = '';
        resizeInput();
        sendTyping(false);

        try {
            const res = await fetch(sendUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ body }),
            });

            if (res.ok) {
                const data = await res.json();
                renderMessage(data.message);
                lastId = data.message.id;
                scrollBottom();
                markRead();
            }
        } catch (err) {
            console.error('Send error:', err);
        } finally {
            btnSend.disabled = false;
            input.focus();
        }
    });

    /* ═══════════════════════════════════════════
       INPUT HELPERS
    ═══════════════════════════════════════════ */
    input.addEventListener('input', () => {
        resizeInput();
        btnSend.disabled = !input.value.trim();
        handleTyping();
    });

    input.addEventListener('keydown', (e) => {
        if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); form.requestSubmit(); }
    });

    function resizeInput() {
        input.style.height = 'auto';
        input.style.height = Math.min(input.scrollHeight, 120) + 'px';
    }

    /* ═══════════════════════════════════════════
       TYPING INDICATOR (outgoing)
    ═══════════════════════════════════════════ */
    const typingUrl = `{{ route('api.internal.chat.typing', $room->id) }}`;

    function handleTyping() {
        if (!isTyping) { isTyping = true; sendTyping(true); }
        clearTimeout(typingTimer);
        typingTimer = setTimeout(() => { isTyping = false; sendTyping(false); }, 2000);
    }

    function sendTyping(typing) {
        navigator.sendBeacon
            ? sendTypingBeacon(typing)
            : fetch(typingUrl, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                body: JSON.stringify({ is_typing: typing }),
                keepalive: true,
              }).catch(() => {});
    }

    function sendTypingBeacon(typing) {
        const blob = new Blob([JSON.stringify({ is_typing: typing, _token: csrfToken })],
            { type: 'application/json' });
        navigator.sendBeacon(typingUrl, blob);
    }

    /* ═══════════════════════════════════════════
       MARK AS READ
    ═══════════════════════════════════════════ */
    function markRead() {
        fetch(readUrl, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
            keepalive: true,
        }).catch(() => {});
    }

    /* ═══════════════════════════════════════════
       SIDEBAR — recherche + preview
    ═══════════════════════════════════════════ */
    if (roomSearch) {
        roomSearch.addEventListener('input', () => {
            const q = roomSearch.value.toLowerCase();
            document.querySelectorAll('#room-list .room-item').forEach(item => {
                const name = item.querySelector('.room-item-name')?.textContent.toLowerCase() || '';
                item.style.display = name.includes(q) ? '' : 'none';
            });
        });
    }

    function updateSidebarPreview(msg) {
        const item = document.querySelector(`.room-item[data-room-id="${roomId}"]`);
        if (!item) return;
        const preview = item.querySelector('.room-item-preview');
        if (preview) preview.textContent = msg.body?.substring(0, 40) || '';
        // Remove unread badge for current room
        const badge = item.querySelector('.unread-badge');
        if (badge) badge.remove();
    }

    /* ═══════════════════════════════════════════
       VISIBILITY — pause poll si onglet caché
    ═══════════════════════════════════════════ */
    document.addEventListener('visibilitychange', () => {
        if (document.hidden) {
            pollAbort?.abort();
        } else {
            pollActive = true;
            startPolling();
        }
    });

    /* ── START ── */
    startPolling();
    markRead();
    input.focus();
})();
</script>

@endsection