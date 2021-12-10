@props(['title'])

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="stylesheet" href="{{ asset('/css/app.css') }}">
        <link rel="stylesheet" href="{{ asset('/css/profile.css') }}">
        <link rel="stylesheet" href="{{ asset('/css/post.css') }}">
        <link rel="stylesheet" href="{{ asset('/css/welcome.css') }}">
        <link rel="stylesheet" href="{{ asset('/css/sidebar.css') }}">
        <link rel="stylesheet" href="{{ asset('/css/login.css') }}">
        <link rel="stylesheet" href="{{ asset('/css/register.css') }}">
        <link rel="stylesheet" href="{{ asset('/css/tailwind.min.css') }}">
        <script type="text/javascript" src="{{ asset('/js/app.js') }}"></script>
        
        <title>{{ $title }}</title>
    </head>
    
    <body>
        {{ $slot }}
    </body>
</html>
