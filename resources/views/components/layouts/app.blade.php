<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{asset('front/images/favicon.png')}}" type="image/x-icon">
    <link rel="icon" href="{{asset('front/images/favicon.png')}}" type="image/x-icon">
    <title>{{ $title ?? 'Livewire Filament Application' }}</title>

    @livewireStyles

    <!-- # Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@400;500;700&display=swap" rel="stylesheet">

    <!-- # CSS Plugins -->
    <link rel="stylesheet" href="{{asset('front/plugins/slick/slick.css')}}">
    <link rel="stylesheet" href="{{asset('front/plugins/font-awesome/fontawesome.min.css')}}">
    <link rel="stylesheet" href="{{asset('front/plugins/font-awesome/brands.css')}}">
    <link rel="stylesheet" href="{{asset('front/plugins/font-awesome/solid.css')}}">

    <!-- # Main Style Sheet -->
    <link rel="stylesheet" href="{{asset('front/css/style.css')}}">
</head>
<body>
@include('components.layouts.navigation.header')
{{ $slot }}
@livewireScripts

@include('components.layouts.navigation.footer')

<!-- # JS Plugins -->
<script src="{{asset('front/plugins/jquery/jquery.min.js')}}"></script>
<script src="{{asset('front/plugins/bootstrap/bootstrap.min.js')}}"></script>

<!-- Main Script -->
<script src="{{asset('front/js/script.js')}}"></script>
</body>
</html>
