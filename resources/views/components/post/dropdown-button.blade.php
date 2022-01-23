@props(['href', 'method' => '', 'id' => '', 'elementPosition' => ''])

<form id="{{ $id }}Form" method="POST" action="{{ $href }}" class="post-dropdown-content-button" style="padding: 0; border-radius: {{ $elementPosition === "first" ? '0.5rem 0.5rem 0 0;' : ($elementPosition === "last" ? '0 0 0.5rem 0.5rem;' : '0;') }}">
    @csrf

    @method($method)

    <x-form.submit class="post-dropdown-content-form-button-text" id="{{ $id }}">{{ $slot }}</x-form.submit>
</form>