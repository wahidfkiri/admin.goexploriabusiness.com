{{-- resources/views/vendor/chatbot/internal-chat/_partials/_message.blade.php --}}
@php $own = $msg->user_id === auth()->id(); @endphp

@if($msg->type === 'event')
    <div class="ic-msg-event">{{ $msg->body }}</div>
@else
    <div class="ic-msg-wrapper {{ $own ? 'ic-own' : 'ic-other' }}" data-msg-id="{{ $msg->id }}">

        @if(!$own)
            <div class="ic-msg-avatar-col">
                @if($msg->user?->avatar)
                    <img src="{{ $msg->user->avatar_url }}" alt="{{ $msg->user->name }}" class="ic-msg-avatar">
                @else
                    <div class="ic-msg-avatar ic-msg-avatar-text">{{ mb_substr($msg->user?->name ?? '?', 0, 2) }}</div>
                @endif
            </div>
        @endif

        <div class="ic-msg-col">
            @if(!$own && $msg->user)
                <span class="ic-msg-author">{{ $msg->user->name }}</span>
            @endif

            @if($msg->type === 'file')
                @foreach($msg->files ?? [] as $f)
                    @if($f->is_image)
                        <div class="ic-msg-image-wrap">
                            <img src="{{ $f->url }}" class="ic-msg-image" alt="{{ $f->original_name }}" loading="lazy">
                        </div>
                    @else
                        <a href="{{ $f->url }}" class="ic-msg-file" target="_blank" rel="noopener noreferrer">
                            <div class="ic-msg-file-icon">
                                @php
                                    $iconMap = [
                                        'pdf' => 'far fa-file-pdf',
                                        'doc' => 'far fa-file-word', 'docx' => 'far fa-file-word',
                                        'xls' => 'far fa-file-excel', 'xlsx' => 'far fa-file-excel',
                                        'ppt' => 'far fa-file-powerpoint', 'pptx' => 'far fa-file-powerpoint',
                                        'zip' => 'far fa-file-archive', 'rar' => 'far fa-file-archive',
                                        'mp4' => 'far fa-file-video', 'mov' => 'far fa-file-video',
                                        'mp3' => 'far fa-file-audio',
                                        'txt' => 'far fa-file-alt', 'csv' => 'far fa-file-csv',
                                    ];
                                    $icon = $iconMap[strtolower($f->extension)] ?? 'far fa-file';
                                @endphp
                                <i class="{{ $icon }}"></i>
                            </div>
                            <div class="ic-msg-file-info">
                                <span class="ic-msg-file-name">{{ $f->original_name }}</span>
                                <span class="ic-msg-file-meta">{{ strtoupper($f->extension) }} · {{ $f->size_formatted }}</span>
                            </div>
                            <i class="fas fa-download ic-msg-file-dl"></i>
                        </a>
                    @endif
                @endforeach
            @else
                @if($msg->deleted_at)
                    <div class="ic-msg-bubble"><em style="opacity:.5">Message supprime</em></div>
                @else
                    <div class="ic-msg-bubble">{{ trim((string) $msg->body) }}</div>
                @endif
            @endif

            <div class="ic-msg-meta">
                <span class="ic-msg-time">{{ $msg->created_at->format('H:i') }}</span>
                @if($own)
                    @php $isRead = $msg->id <= (int)($readByOthersMaxId ?? 0); @endphp
                    <span class="ic-msg-read-status {{ $isRead ? 'is-read' : '' }}" data-msg-read-status>
                        {{ $isRead ? 'Lu' : 'Envoye' }}
                    </span>
                @endif
            </div>
        </div>

        @if($own && !$msg->deleted_at)
            <div class="ic-msg-actions">
                <button class="ic-msg-action-btn"
                        onclick="deleteMsg({{ $msg->id }}, {{ $msg->room_id }}, this)"
                        title="Supprimer">
                    <i class="fas fa-trash-alt"></i>
                </button>
            </div>
        @endif
    </div>
@endif
