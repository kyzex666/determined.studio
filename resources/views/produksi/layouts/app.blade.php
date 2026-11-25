<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', 'Produksi')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">

    {{-- NAVBAR --}}
    <nav class="bg-white border-b shadow">
        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between">
            <div class="font-bold text-lg">
                PRODUKSI
            </div>

            <div class="space-x-4">
                <a href="{{ route('produksi.dashboard') }}"
                   class="text-gray-700 hover:text-blue-600">
                    Dashboard
                </a>

                <a href="{{ route('produksi.production-orders.index') }}"
                   class="text-gray-700 hover:text-blue-600">
                    Orders
                </a>

                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button class="text-red-600 hover:underline">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </nav>

    {{-- PAGE HEADER --}}
    @hasSection('header')
        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto px-6 py-4">
                @yield('header')
            </div>
        </header>
    @endif

    {{-- CONTENT --}}
    <main class="max-w-7xl mx-auto px-6 py-6">
        @yield('content')
    </main>

</body>
</html>
