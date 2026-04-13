{{-- resources/views/vendor/chatbot/internal-chat/_partials/_styles.blade.php --}}
<style>
/* ═══════════════════════════════════════════════════════════
   INTERNAL CHAT — DESIGN SYSTEM
   Aligned with dashboard.css variables:
     --sidebar-width : 280px  (fixed left sidebar)
     --header-height : 70px   (fixed top header)
     --primary-color : #4361ee
     --sidebar-dark  : #0f172a
     --light-bg      : #f8fafc
     --danger-color  : #ef476f
     --accent-color  : #06d6a0
     --card-shadow   : ...
═══════════════════════════════════════════════════════════ */

/* ── Local aliases (fall back to dashboard vars) ── */
:root {
    --ic-primary:     var(--primary-color,     #4361ee);
    --ic-primary-h:   #3a53d4;
    --ic-primary-lt:  var(--primary-light,     #eef2ff);
    --ic-success:     var(--accent-color,      #06d6a0);
    --ic-danger:      var(--danger-color,      #ef476f);
    --ic-warn:        var(--warning-color,     #ffd166);
    --ic-bg:          var(--light-bg,          #f8fafc);
    --ic-surface:     var(--white,             #ffffff);
    --ic-border:      #e2e8f0;
    --ic-text:        #1e293b;
    --ic-muted:       #64748b;
    --ic-own-bg:      var(--primary-color,     #4361ee);
    --ic-own-text:    #ffffff;
    --ic-other-bg:    #f1f5f9;
    --ic-other-text:  #1e293b;
    --ic-shadow:      var(--card-shadow, 0 4px 6px -1px rgba(0,0,0,.1),0 2px 4px -1px rgba(0,0,0,.06));
}

/* ════════════════════════════════════════════
   LAYOUT  — sits inside the dashboard shell:
   • left  = var(--sidebar-width) = 280px (sidebar)
   • top   = var(--header-height) = 70px  (header)
   Full remaining viewport, no scroll on body
════════════════════════════════════════════ */
.ic-layout {
    display: flex;
    position: fixed;
    top: var(--header-height, 70px);
    left: var(--sidebar-width, 280px);
    right: 0;
    bottom: 0;
    background: var(--ic-bg);
    z-index: 10;          /* below header (100) and sidebar (101) */
    overflow: hidden;
    transition: left .3s ease; /* matches sidebar collapse transition */
}

/* When sidebar is collapsed on mobile */
@media (max-width: 992px) {
    .ic-layout {
        left: 0;
    }
}

/* ════════════════════════════
   SIDEBAR — conversation list
════════════════════════════ */
.ic-sidebar {
    width: 300px;
    min-width: 260px;
    display: flex;
    flex-direction: column;
    background: var(--ic-surface);
    border-right: 1px solid var(--ic-border);
    overflow: hidden;
    flex-shrink: 0;
}

.ic-sidebar-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 18px 18px 14px;
    border-bottom: 1px solid var(--ic-border);
}
.ic-sidebar-head-left {
    display: flex;
    align-items: center;
    gap: 10px;
}
.ic-sidebar-icon {
    width: 34px;
    height: 34px;
    border-radius: 10px;
    background: linear-gradient(135deg, var(--ic-primary), #7b5ea7);
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    flex-shrink: 0;
}
.ic-sidebar-title {
    font-size: 15px;
    font-weight: 700;
    color: var(--ic-text);
}
.ic-btn-compose {
    width: 32px;
    height: 32px;
    border-radius: 9px;
    background: var(--ic-primary-lt);
    color: var(--ic-primary);
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    font-size: 13px;
    transition: background .15s;
    flex-shrink: 0;
}
.ic-btn-compose:hover { background: #dde3fb; color: var(--ic-primary); }

.ic-search-wrap {
    display: flex;
    align-items: center;
    gap: 8px;
    margin: 10px 14px;
    padding: 8px 13px;
    background: var(--ic-bg);
    border-radius: 10px;
    border: 1.5px solid var(--ic-border);
    transition: border-color .15s;
}
.ic-search-wrap:focus-within { border-color: var(--ic-primary); }
.ic-search-icon { color: var(--ic-muted); font-size: 12px; flex-shrink: 0; }
.ic-search-input {
    flex: 1;
    border: none;
    background: transparent;
    font-size: 13px;
    color: var(--ic-text);
    outline: none;
    font-family: 'Inter', sans-serif;
}
.ic-search-input::placeholder { color: var(--ic-muted); }

.ic-rooms-label {
    padding: 4px 18px 6px;
    font-size: 10px;
    font-weight: 800;
    letter-spacing: .1em;
    text-transform: uppercase;
    color: var(--ic-muted);
}

.ic-room-list {
    list-style: none;
    margin: 0;
    padding: 0;
    overflow-y: auto;
    flex: 1;
}
.ic-room-list::-webkit-scrollbar { width: 4px; }
.ic-room-list::-webkit-scrollbar-thumb { background: var(--ic-border); border-radius: 4px; }

.ic-room-item {
    display: flex;
    align-items: center;
    gap: 11px;
    padding: 10px 16px;
    text-decoration: none;
    color: inherit;
    border-left: 3px solid transparent;
    transition: background .12s, border-color .12s;
}
.ic-room-item:hover { background: #f8fafc; }
.ic-room-item.ic-room-active {
    background: var(--ic-primary-lt);
    border-left-color: var(--ic-primary);
}

.ic-room-avatar {
    position: relative;
    width: 42px;
    height: 42px;
    border-radius: 12px;
    background: linear-gradient(135deg, var(--ic-primary), #7b5ea7);
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    font-weight: 700;
    flex-shrink: 0;
    text-transform: uppercase;
    overflow: visible;
}
.ic-room-avatar.ic-room-avatar-style-1 { background: linear-gradient(135deg, #4361ee, #5e72eb); }
.ic-room-avatar.ic-room-avatar-style-2 { background: linear-gradient(135deg, #0ea5e9, #2563eb); }
.ic-room-avatar.ic-room-avatar-style-3 { background: linear-gradient(135deg, #14b8a6, #0d9488); }
.ic-room-avatar.ic-room-avatar-style-4 { background: linear-gradient(135deg, #f97316, #ea580c); }
.ic-room-avatar.ic-room-avatar-style-5 { background: linear-gradient(135deg, #ef4444, #be123c); }
.ic-room-avatar.ic-room-avatar-style-6 { background: linear-gradient(135deg, #8b5cf6, #7c3aed); }
.ic-room-avatar img {
    width: 42px;
    height: 42px;
    border-radius: 12px;
    object-fit: cover;
}
.ic-room-presence {
    position: absolute;
    bottom: -2px;
    right: -2px;
    width: 11px;
    height: 11px;
    border-radius: 50%;
    background: var(--ic-success);
    border: 2px solid var(--ic-surface);
    display: none;
}
.ic-room-presence.online { display: block; }

.ic-room-meta { flex: 1; min-width: 0; }
.ic-room-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 6px;
}
.ic-room-name {
    font-size: 13px;
    font-weight: 700;
    color: var(--ic-text);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.ic-room-time {
    font-size: 11px;
    color: var(--ic-muted);
    white-space: nowrap;
    flex-shrink: 0;
}
.ic-room-preview {
    font-size: 12px;
    color: var(--ic-muted);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    flex: 1;
    min-width: 0;
    margin-top: 2px;
}
.ic-room-preview-unread { font-weight: 600; color: var(--ic-text); }
.ic-unread-badge {
    background: var(--ic-primary);
    color: #fff;
    font-size: 10px;
    font-weight: 800;
    border-radius: 10px;
    padding: 2px 6px;
    min-width: 18px;
    text-align: center;
    flex-shrink: 0;
}

.ic-room-empty {
    padding: 40px 20px;
    text-align: center;
    color: var(--ic-muted);
    font-size: 13px;
    line-height: 1.7;
}
.ic-room-empty-icon { font-size: 32px; opacity: .2; margin-bottom: 10px; }
.ic-room-empty a { color: var(--ic-primary); }

/* ════════════════════════════
   MAIN AREA
════════════════════════════ */
.ic-main {
    flex: 1;
    display: flex;
    flex-direction: column;
    overflow: hidden;
    background: var(--ic-surface);
}

.ic-empty-state {
    align-items: center;
    justify-content: center;
}
.ic-empty-content {
    text-align: center;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 14px;
    max-width: 360px;
    padding: 20px;
}
.ic-empty-icon { font-size: 52px; color: var(--ic-primary); opacity: .18; }
.ic-empty-content h3 { font-size: 19px; font-weight: 700; color: var(--ic-text); margin: 0; }
.ic-empty-content p  { font-size: 14px; color: var(--ic-muted); margin: 0; line-height: 1.6; }
.ic-btn-start {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 11px 22px;
    background: var(--ic-primary);
    color: #fff;
    border-radius: 10px;
    text-decoration: none;
    font-size: 13px;
    font-weight: 700;
    transition: opacity .15s;
    box-shadow: 0 4px 12px rgba(67,97,238,.25);
}
.ic-btn-start:hover { opacity: .9; color: #fff; }

/* Chat header */
.ic-chat-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 13px 20px;
    border-bottom: 1px solid var(--ic-border);
    background: var(--ic-surface);
    min-height: 62px;
}
.ic-chat-header-left { display: flex; align-items: center; gap: 12px; }
.ic-avatar {
    border-radius: 12px;
    background: linear-gradient(135deg, var(--ic-primary), #7b5ea7);
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    text-transform: uppercase;
    position: relative;
    flex-shrink: 0;
    overflow: hidden;
}
.ic-avatar img { width: 100%; height: 100%; object-fit: cover; }
.ic-avatar-md { width: 42px; height: 42px; font-size: 14px; }
.ic-presence {
    position: absolute;
    bottom: -2px;
    right: -2px;
    width: 11px;
    height: 11px;
    border-radius: 50%;
    background: var(--ic-success);
    border: 2px solid var(--ic-surface);
}
.ic-chat-header-name {
    font-size: 14px;
    font-weight: 700;
    margin: 0;
    color: var(--ic-text);
}
.ic-chat-header-status {
    font-size: 12px;
    color: var(--ic-muted);
    margin: 2px 0 0;
    display: flex;
    align-items: center;
    gap: 5px;
}
.ic-status-dot {
    width: 7px;
    height: 7px;
    border-radius: 50%;
    background: var(--ic-success);
    display: inline-block;
}
.ic-chat-header-actions { display: flex; gap: 6px; }
.ic-header-btn {
    width: 34px;
    height: 34px;
    border-radius: 9px;
    border: 1.5px solid var(--ic-border);
    background: var(--ic-bg);
    color: var(--ic-muted);
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 13px;
    transition: background .15s, color .15s;
}
.ic-header-btn:hover { background: var(--ic-border); color: var(--ic-text); }
.ic-room-options-wrap { position: relative; }
.ic-room-options-menu {
    position: absolute;
    top: calc(100% + 8px);
    right: 0;
    background: var(--ic-surface);
    border: 1px solid var(--ic-border);
    border-radius: 10px;
    box-shadow: 0 12px 24px rgba(0,0,0,.08);
    padding: 6px;
    min-width: 220px;
    z-index: 20;
}
.ic-room-options-menu button {
    width: 100%;
    border: none;
    background: transparent;
    text-align: left;
    color: var(--ic-text);
    font-size: 12px;
    border-radius: 8px;
    padding: 8px 10px;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 8px;
}
.ic-room-options-menu button:hover { background: var(--ic-bg); }
.ic-room-options-menu #opt-delete-room { color: #b91c1c; }
.ic-room-options-menu #opt-delete-room:hover { background: #fef2f2; }

.ic-message-search {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 14px;
    border-bottom: 1px solid var(--ic-border);
    background: var(--ic-bg);
}
.ic-message-search i { color: var(--ic-muted); font-size: 12px; }
.ic-message-search input {
    flex: 1;
    border: none;
    background: transparent;
    outline: none;
    font-size: 13px;
    color: var(--ic-text);
}
.ic-message-search button {
    border: none;
    background: transparent;
    color: var(--ic-muted);
    cursor: pointer;
}

/* Messages zone */
.ic-messages {
    flex: 1;
    overflow-y: auto;
    padding: 20px 22px 10px;
    scroll-behavior: smooth;
    position: relative;
}
.ic-messages::-webkit-scrollbar { width: 4px; }
.ic-messages::-webkit-scrollbar-thumb { background: var(--ic-border); border-radius: 4px; }
.ic-messages-inner {
    display: flex;
    flex-direction: column;
    gap: 5px;
    min-height: 100%;
    justify-content: flex-end;
}
.ic-dragover {
    outline: 3px dashed var(--ic-primary);
    outline-offset: -4px;
    background: rgba(67,97,238,.04);
}

/* Message rows */
.ic-msg-wrapper {
    display: flex;
    align-items: flex-end;
    gap: 8px;
    max-width: 70%;
    position: relative;
}
.ic-msg-wrapper:hover .ic-msg-actions { opacity: 1; }
.ic-own   { align-self: flex-end; flex-direction: row-reverse; }
.ic-other { align-self: flex-start; }

.ic-msg-avatar-col { flex-shrink: 0; }
.ic-msg-avatar {
    width: 30px;
    height: 30px;
    border-radius: 9px;
    object-fit: cover;
    display: block;
}
.ic-msg-avatar-text {
    width: 30px;
    height: 30px;
    border-radius: 9px;
    background: linear-gradient(135deg, var(--ic-primary), #7b5ea7);
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
}
.ic-msg-col {
    display: flex;
    flex-direction: column;
    gap: 3px;
    min-width: 0;
}
.ic-own   .ic-msg-col { align-items: flex-end; }
.ic-other .ic-msg-col { align-items: flex-start; }

.ic-msg-author {
    font-size: 11px;
    font-weight: 700;
    color: var(--ic-muted);
    padding: 0 4px;
}
.ic-msg-bubble {
    padding: 9px 13px;
    border-radius: 16px;
    font-size: 13.5px;
    line-height: 1.55;
    word-break: break-word;
    white-space: pre-wrap;
    font-family: 'Inter', sans-serif;
}
.ic-own   .ic-msg-bubble {
    background: var(--ic-own-bg);
    color: var(--ic-own-text);
    border-bottom-right-radius: 4px;
    box-shadow: 0 2px 8px rgba(67,97,238,.2);
}
.ic-other .ic-msg-bubble {
    background: var(--ic-other-bg);
    color: var(--ic-other-text);
    border-bottom-left-radius: 4px;
}
.ic-msg-time {
    font-size: 10px;
    color: var(--ic-muted);
    padding: 0 4px;
    white-space: nowrap;
}
.ic-msg-meta {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 0 4px;
}
.ic-msg-read-status {
    font-size: 10px;
    color: var(--ic-muted);
}
.ic-msg-read-status.is-read {
    color: var(--ic-primary);
    font-weight: 700;
}
.ic-msg-event {
    align-self: center;
    font-size: 12px;
    color: var(--ic-muted);
    font-style: italic;
    padding: 5px 14px;
    background: var(--ic-bg);
    border-radius: 20px;
    border: 1px solid var(--ic-border);
    margin: 6px 0;
}
.ic-msg-search-match .ic-msg-bubble,
.ic-msg-search-match .ic-msg-file {
    box-shadow: 0 0 0 2px rgba(67,97,238,.18);
}

.ic-msg-actions {
    display: flex;
    flex-direction: column;
    gap: 4px;
    opacity: 0;
    transition: opacity .15s;
    align-self: center;
}
.ic-msg-action-btn {
    width: 26px;
    height: 26px;
    border-radius: 7px;
    border: 1.5px solid var(--ic-border);
    background: var(--ic-surface);
    color: var(--ic-muted);
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 10px;
    transition: color .15s, background .15s;
}
.ic-msg-action-btn:hover { color: var(--ic-danger); background: #fef2f2; border-color: #fecaca; }
.ic-msg-action-btn-placeholder {
    visibility: hidden;
    pointer-events: none;
}

/* Image messages */
.ic-msg-image-wrap {
    border-radius: 12px;
    overflow: hidden;
    max-width: 260px;
    cursor: zoom-in;
}
.ic-msg-image { width: 100%; display: block; border-radius: 12px; transition: opacity .2s; }
.ic-msg-image:hover { opacity: .9; }

/* File messages */
.ic-msg-file {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 11px 14px;
    background: var(--ic-bg);
    border: 1.5px solid var(--ic-border);
    border-radius: 12px;
    text-decoration: none;
    color: var(--ic-text);
    transition: background .15s;
    max-width: 260px;
}
.ic-own .ic-msg-file {
    background: var(--ic-primary-lt);
    border-color: color-mix(in srgb, var(--ic-primary) 28%, #dbeafe);
    color: var(--ic-text);
}
.ic-msg-file:hover { background: #eef2ff; }
.ic-own .ic-msg-file:hover { background: #e4eaff; }
.ic-msg-file-icon {
    width: 34px; height: 34px; border-radius: 8px;
    background: var(--ic-primary-lt); color: var(--ic-primary);
    display: flex; align-items: center; justify-content: center; font-size: 15px; flex-shrink: 0;
}
.ic-own .ic-msg-file-icon { background: #dbe5ff; color: var(--ic-primary); }
.ic-msg-file-info { flex: 1; min-width: 0; }
.ic-msg-file-name { font-size: 12px; font-weight: 600; display: block; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.ic-msg-file-meta { font-size: 11px; opacity: .6; }
.ic-msg-file-dl { font-size: 12px; opacity: .5; flex-shrink: 0; }

/* Typing indicator */
.ic-typing {
    display: flex; align-items: center; gap: 10px;
    padding: 5px 20px; min-height: 28px;
}
.ic-typing-bubbles {
    display: flex; gap: 3px;
    background: var(--ic-other-bg);
    padding: 7px 11px; border-radius: 14px;
}
.ic-typing-bubbles span {
    width: 5px; height: 5px; background: var(--ic-muted);
    border-radius: 50%; animation: icBounce 1.2s infinite;
}
.ic-typing-bubbles span:nth-child(2) { animation-delay: .2s; }
.ic-typing-bubbles span:nth-child(3) { animation-delay: .4s; }
@keyframes icBounce { 0%,60%,100% { transform:translateY(0); } 30% { transform:translateY(-5px); } }
.ic-typing-text { font-size: 12px; color: var(--ic-muted); font-style: italic; }

/* File preview bar */
.ic-file-preview {
    display: flex; align-items: center; gap: 10px;
    padding: 8px 14px;
    background: var(--ic-primary-lt);
    border-top: 1px solid var(--ic-border);
    overflow-x: auto;
}
.ic-file-preview-inner { display: flex; gap: 8px; flex: 1; }
.ic-fp-item {
    display: flex; align-items: center; gap: 8px;
    padding: 7px 10px;
    background: var(--ic-surface); border: 1.5px solid var(--ic-border);
    border-radius: 10px; min-width: 150px; max-width: 200px; flex-shrink: 0; position: relative;
}
.ic-fp-img { width: 32px; height: 32px; border-radius: 6px; object-fit: cover; flex-shrink: 0; }
.ic-fp-icon { font-size: 20px; color: var(--ic-primary); flex-shrink: 0; }
.ic-fp-info { flex: 1; min-width: 0; }
.ic-fp-name { font-size: 11px; font-weight: 600; color: var(--ic-text); display: block; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.ic-fp-size { font-size: 10px; color: var(--ic-muted); }
.ic-fp-remove { background: none; border: none; padding: 0; cursor: pointer; color: var(--ic-muted); font-size: 11px; flex-shrink: 0; transition: color .15s; }
.ic-fp-remove:hover { color: var(--ic-danger); }
.ic-fp-progress {
    position: absolute;
    left: 8px;
    right: 8px;
    bottom: 4px;
    height: 3px;
    border-radius: 10px;
    background: #e2e8f0;
    overflow: hidden;
}
.ic-fp-progress-bar {
    height: 100%;
    width: 0;
    background: var(--ic-primary);
    transition: width .2s;
}
.ic-file-preview-clear {
    width: 30px; height: 30px; border-radius: 8px;
    border: 1.5px solid var(--ic-border); background: var(--ic-surface);
    color: var(--ic-muted); cursor: pointer; display: flex;
    align-items: center; justify-content: center; flex-shrink: 0; font-size: 12px; transition: color .15s;
}
.ic-file-preview-clear:hover { color: var(--ic-danger); }

/* Input zone */
.ic-input-zone {
    padding: 10px 14px 8px;
    border-top: 1px solid var(--ic-border);
    background: var(--ic-surface);
}
.ic-input-wrap {
    display: flex; align-items: flex-end; gap: 6px;
    background: var(--ic-bg);
    border: 1.5px solid var(--ic-border);
    border-radius: 16px; padding: 5px 6px 5px 4px;
    transition: border-color .15s, background .15s;
}
.ic-input-wrap:focus-within {
    border-color: var(--ic-primary);
    background: var(--ic-surface);
    box-shadow: 0 0 0 3px rgba(67,97,238,.08);
}
.ic-input-action {
    width: 32px; height: 32px; border-radius: 9px; border: none; background: transparent;
    color: var(--ic-muted); cursor: pointer;
    display: flex; align-items: center; justify-content: center; font-size: 14px; flex-shrink: 0;
    transition: color .15s, background .15s;
}
.ic-input-action:hover { color: var(--ic-primary); background: var(--ic-primary-lt); }
.ic-input {
    flex: 1; border: none; background: transparent; resize: none; outline: none;
    font-size: 13.5px; line-height: 1.5; max-height: 120px; overflow-y: auto;
    color: var(--ic-text); padding: 5px 4px; font-family: 'Inter', sans-serif;
}
.ic-input::placeholder { color: var(--ic-muted); }
.ic-btn-send {
    width: 34px; height: 34px; border-radius: 10px; border: none;
    background: var(--ic-border); color: var(--ic-muted); cursor: pointer;
    display: flex; align-items: center; justify-content: center; font-size: 13px; flex-shrink: 0;
    transition: background .15s, color .15s, transform .1s;
}
.ic-btn-send:disabled { opacity: .45; cursor: default; }
.ic-btn-send-active { background: var(--ic-primary); color: #fff; box-shadow: 0 3px 10px rgba(67,97,238,.3); }
.ic-btn-send-active:hover { background: var(--ic-primary-h); }
.ic-btn-send-active:active { transform: scale(.93); }
.ic-input-hint { font-size: 11px; color: var(--ic-muted); text-align: center; margin: 5px 0 0; opacity: .55; }

.ic-emoji-picker {
    display: grid;
    grid-template-columns: repeat(6, minmax(0, 1fr));
    gap: 6px;
    margin-top: 8px;
    padding: 10px;
    border: 1px solid var(--ic-border);
    border-radius: 12px;
    background: var(--ic-surface);
}
.ic-emoji-item {
    border: none;
    background: var(--ic-bg);
    border-radius: 8px;
    padding: 6px 0;
    cursor: pointer;
    font-size: 18px;
    line-height: 1;
    font-family: "Segoe UI Emoji", "Apple Color Emoji", "Noto Color Emoji", sans-serif;
}
.ic-emoji-item:hover {
    background: var(--ic-primary-lt);
}

/* Mobile */
@media (max-width: 768px) {
    .ic-sidebar {
        width: 100%;
        position: absolute; top: 0; left: 0; right: 0; bottom: 0; z-index: 5;
    }
    .ic-main { display: none; }
    .ic-layout.show-main .ic-sidebar { display: none; }
    .ic-layout.show-main .ic-main    { display: flex; }
}
</style>
