<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js"></script>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/mine_icon.webp') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-white">
    <x-navbar />

    <div class="flex">
        {{-- Sidebar desktop --}}
        <div class="w-64 h-screen bg-cos-yellow mx-2 mb-2 text-white rounded-lg lg:block hidden">
            @include('components.sidebar', ['courses' => $courses])
        </div>

        {{-- Toggle button for mobile sidebar --}}
        <button onclick="toggleSidebar()"
            class="lg:hidden fixed bottom-4 left-4 z-50 bg-cos-yellow text-black p-3 rounded-full">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>

        {{-- Sidebar mobile --}}
        <div id="sidebar" class="hidden fixed inset-0 bg-black bg-opacity-50 z-40">
            <div class="w-64 h-full bg-cos-yellow text-white rounded-r-lg p-4">
                @include('components.sidebar', ['courses' => $courses])
            </div>
        </div>

        {{-- Main content --}}
        <div class="w-full h-screen bg-cos-yellow mx-2 mb-2 text-black rounded-lg">
            @yield('content')
        </div>
    </div>

    {{-- Dynamic Floating Button --}}
    @if (Request::is('index'))
        <x-dynamic-button type="courses" />
    @elseif (Request::is('courses/*/meetings') && !Request::is('*/files'))
        <x-dynamic-button type="meetings" :course="$course" />
    @elseif (Request::is('courses/*/meetings/*/files'))
        <x-dynamic-button type="files" :course="$course" :meeting="$meeting" />
    @else
        <x-dynamic-button type="courses" />
    @endif

    {{-- Alert --}}
    <x-alert />

    {{-- Scripts --}}
    <script src="//unpkg.com/alpinejs" defer></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        function toggleSidebar() {
            var sidebar = document.getElementById('sidebar');
            if (sidebar.classList.contains('hidden')) {
                sidebar.classList.remove('hidden');
            } else {
                sidebar.classList.add('hidden');
            }
        }

        // Set CSRF token for all AJAX calls
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>

    @stack('scripts')
</body>

</html>
