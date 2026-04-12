{{-- resources/views/internal-chat/_partials/_message.blade.php --}}
@php $own = $msg->user_id === auth()->id(); @endphp

@if($msg->type === 'event')
    <div class="msg-event">{{ $msg->body }}</div>
@else
    <div class="msg-row {{ $own ? 'own' : 'other' }}" data-msg-id="{{ $msg->id }}">

        @if(!$own)
            <img src="{{ $msg->user?->avatar_url ?? asset('img/default-avatar.png') }}"
                 alt="{{ $msg->user?->name }}"
                 class="avatar avatar-sm"
                 title="{{ $msg->user?->name }}">
        @endif

        <div style="display:flex;flex-direction:column;gap:2px;max-width:100%">
            @if(!$own && $msg->user)
                <span class="msg-name">{{ $msg->user->name }}</span>
            @endif

            <div class="msg-bubble">{{ $msg->body }}</div>

            <div class="msg-meta" @if($own) style="justify-content:flex-end" @endif>
                <span class="msg-time">{{ $msg->created_at->format('H:i') }}</span>
            </div>
        </div>
    </div>
@endif

