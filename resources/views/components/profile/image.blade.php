@props(['url' => ''])

<img class="profile-image block" src="{{ $url != "" ? asset('/storage/profile_pictures/' . $url) : asset('/images/person_white_24dp.svg') }}">