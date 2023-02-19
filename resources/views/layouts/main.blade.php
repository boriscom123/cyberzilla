<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <title>@yield('title') - @yield('user-name')</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <meta name="description" content="@yield('description')">
    <link rel="canonical" href="{{request()->url()}}"/>
{{--    <meta name="csrf-token" content="{{ csrf_token() }}">--}}
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
<div class="app" id="app">

    <x-header/>

    @yield('content')

    @include('layouts.footer')
</div>
<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
