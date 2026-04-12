@php
    $other = $r->type === 'direct'
        ? $r->users->firstWhere('id', '!=', auth()->id())
        : null;
    $displayName = $r->type === 'direct'
        ? ($other?->name ?? 'Utilisateur')
        : ($r->name ?? 'Groupe');
    $unread = $r->unreadCount(auth()->id());
    $last   = $r->lastMessage;
    $preview = $last ? Str::limit($last->body, 40) : 'Aucun message';
    $time    = $last ? $last->created_at->diffForHumans(null, true) : '';
@endphp

<li>
    <a href="{{ route('internal.chat.room', $r->id) }}"
       class="room-item {{ $active ? 'active' : '' }}"
       data-room-id="{{ $r->id }}">

        @if($r->type === 'direct' && $other)
            <img src="{{ $other->avatar_url }}" alt="{{ $other->name }}" class="avatar avatar-sm">
        @else
            <div class="avatar avatar-sm avatar-group">{{ mb_substr($displayName, 0, 2) }}</div>
        @endif

        <div class="room-item-meta">
            <div class="room-item-name">{{ $displayName }}</div>
            <div class="room-item-preview">{{ $preview }}</div>
        </div>

        <div style="display:flex;flex-direction:column;align-items:flex-end;gap:4px;flex-shrink:0">
            @if($time)
                <span class="room-item-time">{{ $time }}</span>
            @endif
            @if($unread > 0)
                <span class="unread-badge">{{ $unread > 99 ? '99+' : $unread }}</span>
            @endif
        </div>
    </a>
</li>

