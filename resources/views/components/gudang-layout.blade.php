<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Gudang - {{ $title ?? '' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100">

    <div class="flex">

        {{-- SIDEBAR --}}
        <aside class="w-64 bg-white shadow-md min-h-screen p-5">
            <h1 class="text-xl font-bold mb-6">Gudang Panel</h1>

            <nav class="space-y-2">
                <a href="{{ route('gudang.dashboard') }}"
                    class="block px-4 py-2 rounded-lg hover:bg-gray-200 {{ request()->is('gudang') ? 'bg-gray-200' : '' }}">
                    Dashboard
                </a>

                <a href="{{ route('gudang.materials.index') }}"
                    class="block px-4 py-2 rounded-lg hover:bg-gray-200">
                    Material
                </a>

                <a href="{{ route('gudang.material-in.index') }}"
                    class="block px-4 py-2 rounded-lg hover:bg-gray-200">
                    Material In
                </a>

                <a href="{{ route('gudang.material-out.index') }}"
                    class="block px-4 py-2 rounded-lg hover:bg-gray-200">
                    Material Out
                </a>

                <a href="{{ route('gudang.requests.index') }}"
                    class="block px-4 py-2 rounded-lg hover:bg-gray-200 {{ request()->is('gudang/requests*') ? 'bg-gray-200' : '' }}">
                    Requests
                </a>
            </nav>

            {{-- PROFILE --}}
            <div class="mt-10 pt-5 border-t">
                <p class="font-bold">{{ Auth::user()->name }}</p>
                <p class="text-sm text-gray-600">{{ Auth::user()->email }}</p>

                <a href="{{ route('profile.edit') }}" class="block mt-3 text-blue-600 hover:underline">
                    Profile
                </a>

                <form method="POST" action="{{ route('logout') }}" class="mt-2">
                    @csrf
                    <button class="text-red-500 hover:underline text-sm">Logout</button>
                </form>
            </div>

        </aside>

        {{-- MAIN CONTENT --}}
        <main class="flex-1 p-8">
            {{ $slot }}
        </main>

    </div>

</body>
</html>
