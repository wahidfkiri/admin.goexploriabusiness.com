{{-- resources/views/internal-chat/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="chat-layout" id="chat-app">

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
            @forelse($rooms as $r)
                @include('chatbot::internal-chat._partials._room-item', ['r' => $r, 'active' => false])
            @empty
                <li class="room-empty">Aucune conversation pour l'instant.<br>
                    <a href="{{ route('internal.chat.new') }}">Démarrer une conversation</a>
                </li>
            @endforelse
        </ul>
    </aside>

    <main class="chat-main chat-empty-state">
        <div class="empty-state-content">
            <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" opacity=".3">
                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
            </svg>
            <p>Sélectionnez une conversation ou <a href="{{ route('internal.chat.new') }}">démarrez-en une nouvelle</a>.</p>
        </div>
    </main>
</div>

<style>
.chat-layout { display:flex; height:calc(100vh - var(--navbar-height,60px)); overflow:hidden; }
.chat-sidebar { width:280px; min-width:240px; display:flex; flex-direction:column; border-right:1px solid var(--bs-border-color,#dee2e6); background:#fff; overflow:hidden; }
.sidebar-header { display:flex; align-items:center; justify-content:space-between; padding:16px 16px 12px; border-bottom:1px solid var(--bs-border-color,#dee2e6); }
.sidebar-title { font-size:16px; font-weight:600; }
.btn-new-chat { display:flex; align-items:center; justify-content:center; width:30px; height:30px; border-radius:50%; background:var(--bs-primary,#4361ee); color:#fff; text-decoration:none; }
.sidebar-search { padding:10px 12px; border-bottom:1px solid var(--bs-border-color,#dee2e6); }
.sidebar-search input { width:100%; padding:7px 12px; border:1px solid var(--bs-border-color,#dee2e6); border-radius:20px; font-size:13px; outline:none; background:var(--bs-tertiary-bg,#f8f9fa); }
.room-list { list-style:none; margin:0; padding:0; overflow-y:auto; flex:1; }
.room-item { display:flex; align-items:center; gap:10px; padding:10px 14px; cursor:pointer; border-left:3px solid transparent; transition:background .12s; text-decoration:none; color:inherit; }
.room-item:hover { background:var(--bs-tertiary-bg,#f8f9fa); }
.room-item.active { background:#eef1fd; border-left-color:var(--bs-primary,#4361ee); }
.room-item-meta { flex:1; min-width:0; }
.room-item-name { font-size:13px; font-weight:600; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
.room-item-preview { font-size:12px; color:var(--bs-secondary-color,#6c757d); white-space:nowrap; overflow:hidden; text-overflow:ellipsis; margin-top:2px; }
.room-item-time { font-size:11px; color:var(--bs-secondary-color,#6c757d); white-space:nowrap; }
.unread-badge { background:var(--bs-primary,#4361ee); color:#fff; font-size:11px; font-weight:700; border-radius:10px; padding:1px 6px; min-width:18px; text-align:center; }
.avatar { border-radius:50%; object-fit:cover; flex-shrink:0; }
.avatar-sm { width:32px; height:32px; font-size:12px; }
.avatar-md { width:40px; height:40px; font-size:14px; }
.avatar-group { display:flex; align-items:center; justify-content:center; background:var(--bs-primary,#4361ee); color:#fff; font-weight:600; text-transform:uppercase; }
.room-empty { padding:24px 16px; font-size:13px; color:var(--bs-secondary-color,#6c757d); text-align:center; line-height:1.6; }
.chat-main { flex:1; display:flex; flex-direction:column; overflow:hidden; background:#fff; }
.chat-empty-state { align-items:center; justify-content:center; }
.empty-state-content { text-align:center; color:var(--bs-secondary-color,#6c757d); display:flex; flex-direction:column; align-items:center; gap:16px; font-size:14px; }
</style>

<script>
document.getElementById('room-search')?.addEventListener('input', function() {
    const q = this.value.toLowerCase();
    document.querySelectorAll('#room-list .room-item').forEach(item => {
        const name = item.querySelector('.room-item-name')?.textContent.toLowerCase() || '';
        item.style.display = name.includes(q) ? '' : 'none';
    });
});
</script>

@endsection