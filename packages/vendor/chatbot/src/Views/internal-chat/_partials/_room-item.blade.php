{{-- resources/views/vendor/chatbot/internal-chat/_partials/_room-item.blade.php --}}
@php
    $other       = $r->type === 'direct' ? $r->users->firstWhere('id', '!=', auth()->id()) : null;
    $displayName = $r->type === 'direct' ? ($other?->name ?? 'Utilisateur') : ($r->name ?? 'Groupe');
    $unread      = isset($unreadOverride) ? (int) $unreadOverride : $r->unreadCount(auth()->id());
    $last        = $r->lastMessage;
    $preview     = $last
        ? ($last->type === 'file' ? 'Fichier joint' : \Illuminate\Support\Str::limit($last->body ?? '', 40))
        : 'Aucun message';
    $time        = $last ? $last->created_at->diffForHumans(null, true) : '';
    $initials    = mb_substr($displayName, 0, 2);
    $avatarClass = 'ic-room-avatar-style-' . ((abs(crc32((string) $displayName)) % 6) + 1);
@endphp

<li>
    <a href="{{ route('internal.chat.room', $r->id) }}"
       class="ic-room-item {{ $active ? 'ic-room-active' : '' }}"
       data-room-id="{{ $r->id }}">

        <div class="ic-room-avatar {{ $avatarClass }}">
            @if($r->type === 'direct' && $other?->avatar)
                <img src="{{ $other->avatar_url }}" alt="{{ $other->name }}">
            @else
                <span>{{ $initials }}</span>
            @endif
            @if($r->type === 'direct')
                <span class="ic-room-presence" id="presence-{{ $other?->id }}"></span>
            @endif
        </div>

        <div class="ic-room-meta">
            <div class="ic-room-row">
                <span class="ic-room-name">{{ $displayName }}</span>
                @if($time)
                    <span class="ic-room-time">{{ $time }}</span>
                @endif
            </div>
            <div class="ic-room-row">
                <span class="ic-room-preview {{ $unread > 0 ? 'ic-room-preview-unread' : '' }}">
                    {{ $preview }}
                </span>
                @if($unread > 0)
                    <span class="ic-unread-badge">{{ $unread > 99 ? '99+' : $unread }}</span>
                @endif
            </div>
        </div>
    </a>
</li>
