<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard') - Determined Studio</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-slate-950 text-slate-100">
<div class="min-h-screen flex flex-col">

    {{-- NAVIGATION --}}
    @include('layouts.navigation')

    {{-- HEADER --}}
    @if (isset($header))
        <header class="border-b border-slate-800 bg-slate-900">
            <div class="max-w-7xl mx-auto px-6 py-6">
                {{ $header }}
            </div>
        </header>
    @endif

    {{-- MAIN CONTENT --}}
    <main class="flex-1">
        <div class="max-w-7xl mx-auto px-6 py-10 space-y-6">

            {{-- SUCCESS ALERT --}}
            @if(session('success'))
                <div class="flex items-center gap-3 p-4 rounded-lg
                    bg-emerald-900/30 border border-emerald-700
                    text-emerald-300">
                    <span>✔</span>
                    <span class="text-sm font-medium">
                        {{ session('success') }}
                    </span>
                </div>
            @endif

            {{-- ERROR ALERT --}}
            @if(session('error'))
                <div class="flex items-center gap-3 p-4 rounded-lg
                    bg-red-900/30 border border-red-700
                    text-red-300">
                    <span>⚠</span>
                    <span class="text-sm font-medium">
                        {{ session('error') }}
                    </span>
                </div>
            @endif

            {{-- PAGE CONTENT --}}
            <div class="space-y-6">
                @yield('content')
                {{ $slot ?? '' }}
            </div>

        </div>
    </main>

</div>
</body>
</html>
