@props(['url' => '', 'id' => ''])

<img class="profile-background" id="{{ $id }}" src="{{ $url != "" ? asset('/storage/profile_backgrounds/' . $url) : asset('/images/background_default_image.jpg') }}">