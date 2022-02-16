@props(['id', 'icon', 'buttonClassAddition' => '', 'title' => ''])

<div id="{{ $id }}" class="post-interaction-button{{ $buttonClassAddition }}" title="{{ $title }}">
    <div class="post-interaction-icon" style="{{ $icon }}"></div>
    @if ($slot != "")
        <div class="post-interaction-text">
            {{ $slot }}
        </div>
    @endif
</div>