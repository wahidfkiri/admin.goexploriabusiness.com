{{-- resources/views/vendor/chatbot/internal-chat/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="ic-layout" id="chat-app">

    {{-- ══════ SIDEBAR ══════ --}}
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
            <input type="text" id="room-search" class="ic-search-input" placeholder="Rechercher une conversation…" autocomplete="off">
        </div>

        <div class="ic-rooms-label">Récentes</div>

        <ul class="ic-room-list" id="room-list">
            @forelse($rooms as $r)
                @include('chatbot::internal-chat._partials._room-item', ['r' => $r, 'active' => false])
            @empty
                <li class="ic-room-empty">
                    <div class="ic-room-empty-icon"><i class="far fa-comment-dots"></i></div>
                    <p>Aucune conversation.<br>
                    <a href="{{ route('internal.chat.new') }}">Démarrer maintenant</a></p>
                </li>
            @endforelse
        </ul>
    </aside>

    {{-- ══════ MAIN EMPTY STATE ══════ --}}
    <main class="ic-main ic-empty-state">
        <div class="ic-empty-content">
            <div class="ic-empty-icon">
                <i class="far fa-comments"></i>
            </div>
            <h3>Bienvenue dans les messages</h3>
            <p>Sélectionnez une conversation à gauche ou démarrez-en une nouvelle avec vos collègues.</p>
            <a href="{{ route('internal.chat.new') }}" class="ic-btn-start">
                <i class="fas fa-plus"></i> Nouvelle conversation
            </a>
        </div>
    </main>
</div>

@include('chatbot::internal-chat._partials._styles')

<script>
document.getElementById('room-search')?.addEventListener('input', function () {
    const q = this.value.toLowerCase();
    document.querySelectorAll('#room-list .ic-room-item').forEach(item => {
        const name = item.querySelector('.ic-room-name')?.textContent.toLowerCase() || '';
        item.parentElement.style.display = name.includes(q) ? '' : 'none';
    });
});
</script>
@endsection