<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $page_title ?? 'Admin Portal' }}</title>

    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/layout.css') }}">

    @if (isset($page_css))
        <link rel="stylesheet" href="{{ asset($page_css) }}">
    @endif
</head>
<body>
    <div class="app-layout">

        @include('layouts.sidebar')

        <main class="main-content">

            @include('layouts.top_nav')

            <div class="page-content">
                @yield('content')
            </div>

            @include('layouts.bottom_nav')
        </main>
    </div>

    <script src="{{ asset('assets/js/app.js') }}"></script>
    <script src="{{ asset('assets/js/sidebar.js') }}"></script>

    @if (isset($page_js))
        {{-- PUTHU MAATRAM: type="module" add pannirukom --}}
        <script src="{{ asset($page_js) }}" type="module"></script>
    @endif
</body>
</html>