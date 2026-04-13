{{-- resources/views/vendor/chatbot/internal-chat/room.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="ic-layout" id="chat-app"
     data-room-id="{{ $room->id }}"
     data-current-user="{{ auth()->id() }}"
     data-last-id="{{ $lastMessageId }}"
     data-read-up-to="{{ $readByOthersMaxId ?? 0 }}"
     data-delete-room-url="{{ route('api.room.delete', $room->id) }}"
     data-poll-url="{{ route('api.internal.chat.poll', $room->id) }}"
     data-send-url="{{ route('api.internal.chat.send', $room->id) }}"
     data-file-url="{{ route('api.internal.chat.file', $room->id) }}"
     data-read-url="{{ route('api.internal.chat.read', $room->id) }}">

    <aside class="ic-sidebar">
        <div class="ic-sidebar-head">
            <div class="ic-sidebar-head-left">
                <div class="ic-sidebar-icon">
                    <i class="fas fa-comments"></i>
                </div>
                <span class="ic-sidebar-title">Messages</span>
            </div>
            <a href="{{ route('internal.chat.new') }}" class="ic-btn-compose" title="Nouvelle conversation">
                <i class="fas fa-edit"></i>
            </a>
        </div>

        <div class="ic-search-wrap">
            <i class="fas fa-search ic-search-icon"></i>
            <input type="text" id="room-search" class="ic-search-input" placeholder="Rechercher..." autocomplete="off">
        </div>

        <div class="ic-rooms-label">Recentes</div>

        <ul class="ic-room-list" id="room-list">
            @foreach($rooms as $r)
                @include('chatbot::internal-chat._partials._room-item', [
                    'r' => $r,
                    'active' => $r->id === $room->id,
                    'unreadOverride' => $unreadByRoom[$r->id] ?? null,
                ])
            @endforeach
        </ul>
    </aside>

    <main class="ic-main">
        <header class="ic-chat-header">
            <div class="ic-chat-header-left">
                @if($room->type === 'direct')
                    @php $other = $room->users->firstWhere('id', '!=', auth()->id()) @endphp
                    <div class="ic-avatar ic-avatar-md">
                        @if($other?->avatar)
                            <img src="{{ $other->avatar_url }}" alt="{{ $other?->name }}">
                        @else
                            <span>{{ mb_substr($other?->name ?? '?', 0, 2) }}</span>
                        @endif
                        <span class="ic-presence" id="presence-dot"></span>
                    </div>
                    <div class="ic-chat-header-info">
                        <h4 class="ic-chat-header-name">{{ $other?->name ?? 'Utilisateur' }}</h4>
                        <p class="ic-chat-header-status" id="chat-status">
                            <span class="ic-status-dot"></span> En ligne
                        </p>
                    </div>
                @else
                    <div class="ic-avatar ic-avatar-md ic-avatar-group">
                        <span>{{ mb_substr($room->name ?? 'G', 0, 2) }}</span>
                    </div>
                    <div class="ic-chat-header-info">
                        <h4 class="ic-chat-header-name">{{ $room->name }}</h4>
                        <p class="ic-chat-header-status">
                            <i class="fas fa-users" style="font-size:10px"></i>
                            {{ $room->participants->count() }} membres
                        </p>
                    </div>
                @endif
            </div>
            <div class="ic-chat-header-actions">
                <button class="ic-header-btn" id="btn-search-messages" title="Rechercher dans la conversation">
                    <i class="fas fa-search"></i>
                </button>
                <div class="ic-room-options-wrap">
                    <button class="ic-header-btn" id="btn-room-options" title="Options">
                        <i class="fas fa-ellipsis-v"></i>
                    </button>
                    <div class="ic-room-options-menu" id="room-options-menu" style="display:none">
                        <button type="button" id="opt-scroll-bottom"><i class="fas fa-arrow-down"></i> Aller en bas</button>
                        <button type="button" id="opt-mark-read"><i class="fas fa-check-double"></i> Marquer comme lu</button>
                        <button type="button" id="opt-restart-poll"><i class="fas fa-sync-alt"></i> Rafraichir reception</button>
                        <button type="button" id="opt-delete-room"><i class="fas fa-trash-alt"></i> Supprimer discussion</button>
                    </div>
                </div>
            </div>
        </header>

        <div class="ic-message-search" id="message-search-wrap" style="display:none">
            <i class="fas fa-search"></i>
            <input type="text" id="message-search" placeholder="Rechercher dans les messages..." autocomplete="off">
            <button type="button" id="message-search-clear" title="Effacer"><i class="fas fa-times"></i></button>
        </div>

        <div class="ic-messages" id="messages-container">
            <div class="ic-messages-inner">
                @foreach($messages as $msg)
                    @include('chatbot::internal-chat._partials._message', ['msg' => $msg, 'readByOthersMaxId' => $readByOthersMaxId ?? 0])
                @endforeach
            </div>
        </div>

        <div class="ic-typing" id="typing-indicator" style="display:none">
            <div class="ic-typing-bubbles">
                <span></span><span></span><span></span>
            </div>
            <span class="ic-typing-text" id="typing-text">Quelqu'un ecrit...</span>
        </div>

        <div class="ic-file-preview" id="file-preview" style="display:none">
            <div class="ic-file-preview-inner" id="file-preview-list"></div>
            <button class="ic-file-preview-clear" id="clear-files" title="Annuler les elements en attente">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="ic-input-zone">
            <div class="ic-input-wrap" id="input-wrap">
                <button class="ic-input-action" id="btn-attach" title="Joindre un fichier" type="button">
                    <i class="fas fa-paperclip"></i>
                </button>
                <input type="file" id="file-input" multiple accept="image/*,.pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.csv,.zip,.mp4,.mp3,.mov" style="display:none">

                <button class="ic-input-action" id="btn-emoji" title="Emoji" type="button">
                    <i class="far fa-smile"></i>
                </button>

                <textarea
                    id="message-input"
                    class="ic-input"
                    placeholder="Ecrire un message..."
                    rows="1"
                    maxlength="2000"
                ></textarea>

                <button type="button" class="ic-btn-send" id="btn-send" disabled>
                    <i class="fas fa-paper-plane"></i>
                </button>
            </div>

            <div class="ic-emoji-picker" id="emoji-picker" style="display:none">
                <button type="button" class="ic-emoji-item" data-emoji="&#x1F600;">&#x1F600;</button>
                <button type="button" class="ic-emoji-item" data-emoji="&#x1F602;">&#x1F602;</button>
                <button type="button" class="ic-emoji-item" data-emoji="&#x1F60D;">&#x1F60D;</button>
                <button type="button" class="ic-emoji-item" data-emoji="&#x1F44D;">&#x1F44D;</button>
                <button type="button" class="ic-emoji-item" data-emoji="&#x1F64F;">&#x1F64F;</button>
                <button type="button" class="ic-emoji-item" data-emoji="&#x1F525;">&#x1F525;</button>
                <button type="button" class="ic-emoji-item" data-emoji="&#x2705;">&#x2705;</button>
                <button type="button" class="ic-emoji-item" data-emoji="&#x1F389;">&#x1F389;</button>
                <button type="button" class="ic-emoji-item" data-emoji="&#x1F4A1;">&#x1F4A1;</button>
                <button type="button" class="ic-emoji-item" data-emoji="&#x1F91D;">&#x1F91D;</button>
                <button type="button" class="ic-emoji-item" data-emoji="&#x2764;&#xFE0F;">&#x2764;&#xFE0F;</button>
                <button type="button" class="ic-emoji-item" data-emoji="&#x1F60E;">&#x1F60E;</button>
            </div>

            <p class="ic-input-hint">Entree pour envoyer · Maj+Entree pour nouvelle ligne</p>
        </div>
    </main>
</div>

@include('chatbot::internal-chat._partials._styles')

<script>
(function () {
    'use strict';

    const app = document.getElementById('chat-app');
    const roomId = parseInt(app.dataset.roomId, 10);
    const currentUser = parseInt(app.dataset.currentUser, 10);
    const pollUrl = app.dataset.pollUrl;
    const sendUrl = app.dataset.sendUrl;
    const fileUrl = app.dataset.fileUrl;
    const readUrl = app.dataset.readUrl;
    const deleteRoomUrl = app.dataset.deleteRoomUrl;
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    const typingUrl = `{{ route('api.internal.chat.typing', $room->id) }}`;

    let lastId = parseInt(app.dataset.lastId || '0', 10) || 0;
    let readByOthersMaxId = parseInt(app.dataset.readUpTo || '0', 10) || 0;
    let pollAbort = null;
    let pollActive = true;
    let typingTimer = null;
    let isTyping = false;

    const uploadQueue = [];
    const MAX_PARALLEL_UPLOADS = 2;
    let activeUploads = 0;

    const container = document.getElementById('messages-container');
    const messagesInner = container.querySelector('.ic-messages-inner');
    const input = document.getElementById('message-input');
    const btnSend = document.getElementById('btn-send');
    const typingEl = document.getElementById('typing-indicator');
    const typingText = document.getElementById('typing-text');
    const fileInput = document.getElementById('file-input');
    const filePreview = document.getElementById('file-preview');
    const filePreviewList = document.getElementById('file-preview-list');
    const clearFilesBtn = document.getElementById('clear-files');
    const roomSearch = document.getElementById('room-search');
    const btnAttach = document.getElementById('btn-attach');
    const btnEmoji = document.getElementById('btn-emoji');
    const emojiPicker = document.getElementById('emoji-picker');
    const btnRoomOptions = document.getElementById('btn-room-options');
    const roomOptionsMenu = document.getElementById('room-options-menu');
    const optScrollBottom = document.getElementById('opt-scroll-bottom');
    const optMarkRead = document.getElementById('opt-mark-read');
    const optRestartPoll = document.getElementById('opt-restart-poll');
    const optDeleteRoom = document.getElementById('opt-delete-room');
    const btnMessageSearch = document.getElementById('btn-search-messages');
    const messageSearchWrap = document.getElementById('message-search-wrap');
    const messageSearchInput = document.getElementById('message-search');
    const messageSearchClear = document.getElementById('message-search-clear');

    function scrollBottom(smooth = true) {
        container.scrollTo({ top: container.scrollHeight, behavior: smooth ? 'smooth' : 'instant' });
    }
    scrollBottom(false);

    function formatTime(isoStr) {
        return new Date(isoStr).toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' });
    }

    function escHtml(str) {
        return String(str ?? '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }

    function getFileIcon(ext) {
        const map = {
            pdf: 'far fa-file-pdf',
            doc: 'far fa-file-word', docx: 'far fa-file-word',
            xls: 'far fa-file-excel', xlsx: 'far fa-file-excel',
            ppt: 'far fa-file-powerpoint', pptx: 'far fa-file-powerpoint',
            zip: 'far fa-file-archive', rar: 'far fa-file-archive',
            mp4: 'far fa-file-video', mov: 'far fa-file-video',
            mp3: 'far fa-file-audio',
            txt: 'far fa-file-alt', csv: 'far fa-file-csv',
        };
        return map[(ext || '').toLowerCase()] || 'far fa-file';
    }

    function readStateLabel(msgId) {
        if (typeof msgId !== 'number') return 'Envoi...';
        return msgId <= readByOthersMaxId ? 'Lu' : 'Envoye';
    }

    function renderMessage(msg) {
        if (!msg || msg.id == null) return;
        if (messagesInner.querySelector(`[data-msg-id="${String(msg.id)}"]`)) return;

        const own = msg.user_id === currentUser;
        const isEvent = msg.type === 'event';

        if (isEvent) {
            const el = document.createElement('div');
            el.className = 'ic-msg-event';
            el.textContent = msg.body || '';
            messagesInner.appendChild(el);
            return;
        }

        const wrapper = document.createElement('div');
        wrapper.className = 'ic-msg-wrapper ' + (own ? 'ic-own' : 'ic-other');
        wrapper.dataset.msgId = String(msg.id);

        let avatarHtml = '';
        if (!own) {
            const initials = escHtml((msg.user_name || '?').substring(0, 2));
            avatarHtml = msg.user_avatar
                ? `<img src="${escHtml(msg.user_avatar)}" class="ic-msg-avatar" alt="${escHtml(msg.user_name)}">`
                : `<div class="ic-msg-avatar ic-msg-avatar-text">${initials}</div>`;
        }

        let contentHtml = '';
        if (!own && msg.user_name) {
            contentHtml += `<span class="ic-msg-author">${escHtml(msg.user_name)}</span>`;
        }

        if (msg.type === 'file' && msg.files && msg.files.length) {
            msg.files.forEach((f) => {
                if (f.is_image) {
                    contentHtml += `
                        <div class="ic-msg-image-wrap">
                            <img src="${escHtml(f.url)}" class="ic-msg-image" alt="${escHtml(f.original_name)}" loading="lazy">
                        </div>`;
                } else {
                    const ext = (f.extension || '').toUpperCase();
                    contentHtml += `
                        <a href="${escHtml(f.url)}" class="ic-msg-file" target="_blank" rel="noopener">
                            <div class="ic-msg-file-icon">
                                <i class="${getFileIcon(f.extension)}"></i>
                            </div>
                            <div class="ic-msg-file-info">
                                <span class="ic-msg-file-name">${escHtml(f.original_name)}</span>
                                <span class="ic-msg-file-meta">${ext} · ${escHtml(f.size_formatted)}</span>
                            </div>
                            <i class="fas fa-download ic-msg-file-dl"></i>
                        </a>`;
                }
            });
        } else {
            const bodyText = msg.deleted ? '<em style="opacity:.55">Message supprime</em>' : escHtml(msg.body);
            contentHtml += `<div class="ic-msg-bubble">${bodyText}</div>`;
        }

        const createdAt = msg.created_at || new Date().toISOString();
        contentHtml += `<div class="ic-msg-meta">`;
        contentHtml += `<span class="ic-msg-time">${formatTime(createdAt)}</span>`;
        if (own) {
            contentHtml += `<span class="ic-msg-read-status" data-msg-read-status>${readStateLabel(typeof msg.id === 'number' ? msg.id : null)}</span>`;
        }
        contentHtml += `</div>`;

        const actionHtml = own
            ? (typeof msg.id === 'number'
                ? `<div class="ic-msg-actions"><button class="ic-msg-action-btn" title="Supprimer" onclick="deleteMsg(${msg.id}, ${roomId}, this)"><i class="fas fa-trash-alt"></i></button></div>`
                : `<div class="ic-msg-actions"><button class="ic-msg-action-btn ic-msg-action-btn-placeholder" type="button" tabindex="-1" aria-hidden="true"><i class="fas fa-trash-alt"></i></button></div>`)
            : '';

        wrapper.innerHTML = `
            ${!own ? `<div class="ic-msg-avatar-col">${avatarHtml}</div>` : ''}
            <div class="ic-msg-col">${contentHtml}</div>
            ${actionHtml}
        `;

        messagesInner.appendChild(wrapper);
    }

    function replaceMessage(tempId, realMsg) {
        const tempNode = messagesInner.querySelector(`[data-msg-id="${String(tempId)}"]`);
        if (!tempNode) {
            renderMessage(realMsg);
            return;
        }
        tempNode.remove();
        renderMessage(realMsg);
        applyReadStatuses();
    }

    function applyReadStatuses() {
        messagesInner.querySelectorAll('.ic-msg-wrapper.ic-own').forEach((row) => {
            const id = Number(row.dataset.msgId);
            if (!Number.isFinite(id)) return;
            const statusEl = row.querySelector('[data-msg-read-status]');
            if (!statusEl) return;
            statusEl.textContent = readStateLabel(id);
            statusEl.classList.toggle('is-read', id <= readByOthersMaxId);
        });
    }

    function sleep(ms) { return new Promise((r) => setTimeout(r, ms)); }

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

                if (typeof data.read_by_others_max_id === 'number') {
                    readByOthersMaxId = Math.max(readByOthersMaxId, data.read_by_others_max_id);
                    applyReadStatuses();
                }

                if (data.status === 'new_messages' && Array.isArray(data.messages) && data.messages.length) {
                    const atBottom = isAtBottom();
                    data.messages.forEach(renderMessage);
                    lastId = data.last_id;
                    if (atBottom) scrollBottom();
                    markRead();
                    updateSidebarPreview(data.messages[data.messages.length - 1]);
                }

                if (Array.isArray(data.typing_user_ids) && data.typing_user_ids.length > 0) {
                    showTyping(true, data.typing_user_names || {});
                } else {
                    showTyping(false, {});
                }
            } catch (err) {
                if (err.name === 'AbortError') break;
                await sleep(2000);
            }
        }
    }

    function isAtBottom() {
        return container.scrollHeight - container.scrollTop - container.clientHeight < 80;
    }

    function showTyping(show, namesMap) {
        typingEl.style.display = show ? 'flex' : 'none';
        if (!show) return;
        const names = Object.values(namesMap || {});
        typingText.textContent = names.length ? `${names.join(', ')} ecrit...` : 'Quelqu\'un ecrit...';
    }

    window.deleteMsg = async function(msgId, rId, btn) {
        if (!confirm('Supprimer ce message ?')) return;
        try {
            const res = await fetch(`/internal-chat/rooms/${rId}/messages/${msgId}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
            });
            if (!res.ok) return;
            const wrapper = btn.closest('.ic-msg-wrapper');
            const bubble = wrapper?.querySelector('.ic-msg-bubble');
            if (bubble) bubble.innerHTML = '<em style="opacity:.45">Message supprime</em>';
            wrapper?.querySelector('.ic-msg-actions')?.remove();
        } catch (e) {
            console.error(e);
        }
    };

    btnSend.addEventListener('click', handleSend);
    input.addEventListener('keydown', (e) => {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            handleSend();
        }
    });

    async function handleSend() {
        const body = input.value.trim();
        if (!body) return;

        btnSend.disabled = true;
        input.value = '';
        resizeInput();
        sendTyping(false);

        const tempId = `tmp-${Date.now()}-${Math.floor(Math.random() * 1000)}`;
        renderMessage({
            id: tempId,
            room_id: roomId,
            user_id: currentUser,
            body,
            type: 'text',
            deleted: false,
            files: [],
            created_at: new Date().toISOString(),
        });
        scrollBottom();

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

            if (!res.ok) {
                throw new Error('send_failed');
            }

            const data = await res.json();
            replaceMessage(tempId, data.message);
            lastId = Math.max(lastId, data.message.id || 0);
            scrollBottom();
            markRead();
            updateSidebarPreview(data.message);
        } catch (err) {
            const tempNode = messagesInner.querySelector(`[data-msg-id="${String(tempId)}"]`);
            tempNode?.remove();
            input.value = body;
            resizeInput();
            updateSendButton();
        } finally {
            btnSend.disabled = false;
            input.focus();
        }
    }

    btnAttach.addEventListener('click', () => fileInput.click());

    fileInput.addEventListener('change', function () {
        const files = Array.from(this.files || []);
        if (files.length) addFilesToQueue(files);
        this.value = '';
    });

    container.addEventListener('dragover', (e) => {
        e.preventDefault();
        container.classList.add('ic-dragover');
    });

    container.addEventListener('dragleave', () => container.classList.remove('ic-dragover'));

    container.addEventListener('drop', (e) => {
        e.preventDefault();
        container.classList.remove('ic-dragover');
        const files = Array.from(e.dataTransfer?.files || []);
        if (files.length) addFilesToQueue(files);
    });

    function addFilesToQueue(files) {
        files.forEach((file) => {
            const id = `f-${Date.now()}-${Math.floor(Math.random() * 100000)}`;
            uploadQueue.push({
                id,
                file,
                status: 'queued',
                progress: 0,
                previewUrl: file.type.startsWith('image/') ? URL.createObjectURL(file) : null,
            });
        });

        renderFilePreview();
        kickUploadWorkers();
    }

    function kickUploadWorkers() {
        while (activeUploads < MAX_PARALLEL_UPLOADS) {
            const next = uploadQueue.find((item) => item.status === 'queued');
            if (!next) break;
            uploadSingleFile(next);
        }
    }

    function uploadSingleFile(item) {
        activeUploads += 1;
        item.status = 'uploading';
        renderFilePreview();

        const xhr = new XMLHttpRequest();
        const formData = new FormData();
        formData.append('file', item.file);
        formData.append('_token', csrfToken);

        xhr.open('POST', fileUrl, true);
        xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken);
        xhr.setRequestHeader('Accept', 'application/json');

        xhr.upload.onprogress = (e) => {
            if (!e.lengthComputable) return;
            item.progress = Math.round((e.loaded / e.total) * 100);
            renderFilePreview();
        };

        xhr.onload = () => {
            activeUploads = Math.max(0, activeUploads - 1);
            if (xhr.status >= 200 && xhr.status < 300) {
                try {
                    const data = JSON.parse(xhr.responseText);
                    item.status = 'sent';
                    item.progress = 100;
                    if (data.message) {
                        renderMessage(data.message);
                        lastId = Math.max(lastId, data.message.id || 0);
                        scrollBottom();
                        markRead();
                        updateSidebarPreview(data.message);
                    }
                } catch (e) {
                    item.status = 'error';
                }
            } else {
                item.status = 'error';
            }

            renderFilePreview();
            pruneFinishedFiles();
            kickUploadWorkers();
        };

        xhr.onerror = () => {
            activeUploads = Math.max(0, activeUploads - 1);
            item.status = 'error';
            renderFilePreview();
            kickUploadWorkers();
        };

        xhr.send(formData);
    }

    function pruneFinishedFiles() {
        window.setTimeout(() => {
            for (let i = uploadQueue.length - 1; i >= 0; i -= 1) {
                if (uploadQueue[i].status === 'sent') {
                    if (uploadQueue[i].previewUrl) URL.revokeObjectURL(uploadQueue[i].previewUrl);
                    uploadQueue.splice(i, 1);
                }
            }
            renderFilePreview();
        }, 1200);
    }

    function renderFilePreview() {
        if (!uploadQueue.length) {
            filePreview.style.display = 'none';
            filePreviewList.innerHTML = '';
            return;
        }

        filePreview.style.display = 'flex';
        filePreviewList.innerHTML = uploadQueue.map((item) => {
            const file = item.file;
            const ext = (file.name.split('.').pop() || '').toLowerCase();
            const icon = `<i class="${getFileIcon(ext)} ic-fp-icon"></i>`;
            const image = item.previewUrl ? `<img src="${item.previewUrl}" class="ic-fp-img" alt="${escHtml(file.name)}">` : '';
            const statusLabel = item.status === 'uploading'
                ? `Envoi ${item.progress}%`
                : item.status === 'sent'
                    ? 'Envoye'
                    : item.status === 'error'
                        ? 'Echec'
                        : 'En attente';

            return `
                <div class="ic-fp-item" data-file-id="${item.id}">
                    ${image || icon}
                    <div class="ic-fp-info">
                        <span class="ic-fp-name">${escHtml(file.name)}</span>
                        <span class="ic-fp-size">${formatBytes(file.size)} · ${statusLabel}</span>
                    </div>
                    ${(item.status === 'queued' || item.status === 'error')
                        ? `<button class="ic-fp-remove" data-remove-file="${item.id}" title="Retirer"><i class="fas fa-times"></i></button>`
                        : ''}
                    ${item.status === 'uploading'
                        ? `<div class="ic-fp-progress"><div class="ic-fp-progress-bar" style="width:${item.progress}%"></div></div>`
                        : ''}
                </div>
            `;
        }).join('');

        filePreviewList.querySelectorAll('[data-remove-file]').forEach((btn) => {
            btn.addEventListener('click', () => {
                const id = btn.getAttribute('data-remove-file');
                removeFileFromQueue(id);
            });
        });
    }

    function removeFileFromQueue(id) {
        const idx = uploadQueue.findIndex((item) => item.id === id && (item.status === 'queued' || item.status === 'error'));
        if (idx === -1) return;
        if (uploadQueue[idx].previewUrl) URL.revokeObjectURL(uploadQueue[idx].previewUrl);
        uploadQueue.splice(idx, 1);
        renderFilePreview();
    }

    clearFilesBtn.addEventListener('click', () => {
        for (let i = uploadQueue.length - 1; i >= 0; i -= 1) {
            if (uploadQueue[i].status === 'queued' || uploadQueue[i].status === 'error' || uploadQueue[i].status === 'sent') {
                if (uploadQueue[i].previewUrl) URL.revokeObjectURL(uploadQueue[i].previewUrl);
                uploadQueue.splice(i, 1);
            }
        }
        renderFilePreview();
    });

    function formatBytes(bytes) {
        if (bytes < 1024) return `${bytes} o`;
        if (bytes < 1024 * 1024) return `${(bytes / 1024).toFixed(1)} Ko`;
        return `${(bytes / (1024 * 1024)).toFixed(1)} Mo`;
    }

    input.addEventListener('input', () => {
        resizeInput();
        updateSendButton();
        handleTyping();
    });

    function resizeInput() {
        input.style.height = 'auto';
        input.style.height = `${Math.min(input.scrollHeight, 120)}px`;
    }

    function updateSendButton() {
        btnSend.disabled = !input.value.trim();
        btnSend.classList.toggle('ic-btn-send-active', !btnSend.disabled);
    }

    function handleTyping() {
        if (!isTyping) {
            isTyping = true;
            sendTyping(true);
        }
        clearTimeout(typingTimer);
        typingTimer = setTimeout(() => {
            isTyping = false;
            sendTyping(false);
        }, 2000);
    }

    function sendTyping(typing) {
        fetch(typingUrl, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
            body: JSON.stringify({ is_typing: typing }),
            keepalive: true,
        }).catch(() => {});
    }

    function markRead() {
        fetch(readUrl, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
            keepalive: true,
        }).catch(() => {});
    }

    roomSearch?.addEventListener('input', () => {
        const q = roomSearch.value.toLowerCase();
        document.querySelectorAll('#room-list .ic-room-item').forEach((item) => {
            const name = item.querySelector('.ic-room-name')?.textContent.toLowerCase() || '';
            item.parentElement.style.display = name.includes(q) ? '' : 'none';
        });
    });

    function updateSidebarPreview(msg) {
        const item = document.querySelector(`.ic-room-item[data-room-id="${roomId}"]`);
        if (!item) return;
        const preview = item.querySelector('.ic-room-preview');
        if (preview) {
            preview.textContent = msg.type === 'file' ? 'Fichier joint' : (msg.body || '').substring(0, 40);
        }
        item.querySelector('.ic-unread-badge')?.remove();
    }

    btnEmoji.addEventListener('click', (e) => {
        e.stopPropagation();
        emojiPicker.style.display = emojiPicker.style.display === 'none' ? 'grid' : 'none';
    });

    emojiPicker.querySelectorAll('.ic-emoji-item').forEach((emojiBtn) => {
        emojiBtn.addEventListener('click', () => {
            insertAtCursor(input, emojiBtn.textContent || '');
            emojiPicker.style.display = 'none';
            input.focus();
            updateSendButton();
        });
    });

    document.addEventListener('click', (e) => {
        if (!emojiPicker.contains(e.target) && e.target !== btnEmoji && !btnEmoji.contains(e.target)) {
            emojiPicker.style.display = 'none';
        }
        if (roomOptionsMenu && btnRoomOptions && !roomOptionsMenu.contains(e.target) && !btnRoomOptions.contains(e.target)) {
            roomOptionsMenu.style.display = 'none';
        }
    });

    btnRoomOptions?.addEventListener('click', (e) => {
        e.stopPropagation();
        roomOptionsMenu.style.display = roomOptionsMenu.style.display === 'none' ? 'block' : 'none';
    });
    optScrollBottom?.addEventListener('click', () => {
        scrollBottom();
        roomOptionsMenu.style.display = 'none';
    });
    optMarkRead?.addEventListener('click', () => {
        markRead();
        roomOptionsMenu.style.display = 'none';
    });
    optRestartPoll?.addEventListener('click', () => {
        pollAbort?.abort();
        pollActive = true;
        startPolling();
        roomOptionsMenu.style.display = 'none';
    });
    optDeleteRoom?.addEventListener('click', deleteRoomDiscussion);

    async function deleteRoomDiscussion() {
        if (!confirm('Supprimer cette discussion ? Cette action est definitive.')) {
            return;
        }

        try {
            const res = await fetch(deleteRoomUrl, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
            });

            if (!res.ok) {
                alert('Impossible de supprimer la discussion.');
                return;
            }

            window.location.href = '{{ route('internal.chat.index') }}';
        } catch (e) {
            alert('Erreur reseau pendant la suppression.');
        } finally {
            roomOptionsMenu.style.display = 'none';
        }
    }

    function insertAtCursor(textarea, value) {
        const start = textarea.selectionStart;
        const end = textarea.selectionEnd;
        const text = textarea.value;
        textarea.value = text.slice(0, start) + value + text.slice(end);
        const nextPos = start + value.length;
        textarea.selectionStart = nextPos;
        textarea.selectionEnd = nextPos;
        resizeInput();
    }

    btnMessageSearch?.addEventListener('click', () => {
        const isHidden = messageSearchWrap.style.display === 'none';
        messageSearchWrap.style.display = isHidden ? 'flex' : 'none';
        if (isHidden) {
            messageSearchInput.focus();
        } else {
            clearMessageSearch();
        }
    });

    messageSearchInput?.addEventListener('input', () => {
        applyMessageSearch(messageSearchInput.value.trim().toLowerCase());
    });

    messageSearchClear?.addEventListener('click', () => {
        clearMessageSearch();
        messageSearchInput.focus();
    });

    function clearMessageSearch() {
        messageSearchInput.value = '';
        applyMessageSearch('');
    }

    function applyMessageSearch(query) {
        const rows = messagesInner.querySelectorAll('.ic-msg-wrapper, .ic-msg-event');
        let firstMatch = null;

        rows.forEach((row) => {
            const text = (row.textContent || '').toLowerCase();
            const matched = !query || text.includes(query);
            row.style.display = matched ? '' : 'none';
            row.classList.toggle('ic-msg-search-match', Boolean(query && matched));
            if (!firstMatch && query && matched) firstMatch = row;
        });

        if (firstMatch) {
            firstMatch.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    }

    document.addEventListener('visibilitychange', () => {
        if (document.hidden) {
            pollActive = false;
            pollAbort?.abort();
        } else {
            pollActive = true;
            startPolling();
            markRead();
        }
    });

    applyReadStatuses();
    startPolling();
    markRead();
    input.focus();
})();
</script>
@endsection

