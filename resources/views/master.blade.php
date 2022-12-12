<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>@yield('title') - {{ config('app.name') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- favicon_begin --}}
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('site.webmanifest') }}">
    {{-- favicon_end --}}

    {{-- bootstrap_begin --}}
    <link rel="stylesheet" href="{{ asset('bootstrap/bootstrap.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="{{ asset('bootstrap/bootstrap.min.js') }}"></script>
    {{-- bootstrap_end --}}

    {{-- custom_js_start --}}
    <script src="{{ asset('js/cookie.js') }}"></script>
    <script src="{{ asset('js/master.js') }}"></script>
    {{-- custom_js_end --}}

    {{-- selectpicker_begin --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>
    {{-- selectpicker_end --}}

    {{-- custom_css_start --}}
    <link rel="stylesheet" href="{{ asset('css/master.css') }}">
    {{-- custom_css_end --}}
</head>

<body>
    @include('layouts.dashboard.navbar')

    @include('layouts.dashboard.error')

    <section class="page-content">
        @yield('content')
    </section>

    @include('layouts.dashboard.toast-container')
</body>

<section class="scripts">
    @yield('scripts')
</section>
