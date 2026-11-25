<nav x-data="{ open: false }"
     class="sticky top-0 z-50
     bg-slate-900/80 backdrop-blur
     border-b border-slate-800">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">

            {{-- LEFT --}}
            <div class="flex items-center gap-10">

                {{-- LOGO --}}
                <a href="{{ route('dashboard') }}"
                   class="flex items-center gap-3 group">
                    <div
                        class="w-9 h-9 rounded-xl
                        bg-gradient-to-br from-indigo-600 to-indigo-500
                        text-white flex items-center justify-center
                        font-bold shadow-lg shadow-indigo-500/30
                        group-hover:scale-105 transition">
                        D
                    </div>
                    <span
                        class="font-semibold text-slate-100 text-lg tracking-tight
                        group-hover:text-indigo-400 transition">
                        Determined Studio
                    </span>
                </a>

                {{-- MENU DESKTOP --}}
                <div class="hidden md:flex items-center gap-1">
                    @php
                        $nav = "px-3 py-2 text-sm font-medium rounded-lg transition";
                        $active = "bg-indigo-500/15 text-indigo-400";
                        $idle = "text-slate-400 hover:text-slate-100 hover:bg-slate-800";
                    @endphp

                    @if(Auth::user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}"
                           class="{{ $nav }} {{ request()->routeIs('admin.dashboard') ? $active : $idle }}">
                            Dashboard
                        </a>
                        <a href="{{ route('admin.vendors.index') }}"
                           class="{{ $nav }} {{ request()->routeIs('admin.vendors.*') ? $active : $idle }}">
                            Vendor
                        </a>
                        <a href="{{ route('admin.materials.index') }}"
                           class="{{ $nav }} {{ request()->routeIs('admin.materials.*') ? $active : $idle }}">
                            Material
                        </a>
                        <a href="{{ route('admin.products.index') }}"
                           class="{{ $nav }} {{ request()->routeIs('admin.products.*') ? $active : $idle }}">
                            Produk
                        </a>
                        <a href="{{ route('admin.users.index') }}"
                           class="{{ $nav }} {{ request()->routeIs('admin.users.*') ? $active : $idle }}">
                            Users
                        </a>

                    @elseif(Auth::user()->role === 'gudang')
                        <a href="{{ route('gudang.dashboard') }}"
                           class="{{ $nav }} {{ request()->routeIs('gudang.dashboard') ? $active : $idle }}">
                            Dashboard
                        </a>
                        <a href="{{ route('gudang.material-in.index') }}"
                           class="{{ $nav }} {{ request()->routeIs('gudang.material-in.*') ? $active : $idle }}">
                            Material Masuk
                        </a>
                        <a href="{{ route('gudang.material-out.index') }}"
                           class="{{ $nav }} {{ request()->routeIs('gudang.material-out.*') ? $active : $idle }}">
                            Material Keluar
                        </a>

                    @elseif(Auth::user()->role === 'produksi')
                        <a href="{{ route('produksi.dashboard') }}"
                           class="{{ $nav }} {{ request()->routeIs('produksi.dashboard') ? $active : $idle }}">
                            Dashboard
                        </a>
                        <a href="{{ route('produksi.production-orders.index') }}"
                           class="{{ $nav }} {{ request()->routeIs('produksi.production-orders.*') ? $active : $idle }}">
                            Order Produksi
                        </a>
                        <a href="{{ route('produksi.results.index') }}"
                           class="{{ $nav }} {{ request()->routeIs('produksi.results.*') ? $active : $idle }}">
                            Hasil Produksi
                        </a>
                    @endif
                </div>
            </div>

            {{-- RIGHT --}}
            <div class="hidden md:flex items-center gap-4">

                {{-- USER --}}
                <a href="{{ route('profile.edit') }}"
                   class="flex items-center gap-3
                   px-3 py-1.5 rounded-full
                   hover:bg-slate-800 transition group">

                    <div
                        class="w-8 h-8 rounded-full
                        bg-slate-700 text-slate-100
                        flex items-center justify-center
                        text-xs font-bold ring-2 ring-slate-800
                        group-hover:ring-indigo-500 transition">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>

                    <div class="leading-tight">
                        <div class="text-xs font-semibold text-slate-100">
                            {{ Auth::user()->name }}
                        </div>
                        <div class="text-[10px] uppercase tracking-wide text-slate-400">
                            {{ Auth::user()->role }}
                        </div>
                    </div>
                </a>

                {{-- LOGOUT --}}
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button
                        title="Logout"
                        class="p-2 rounded-lg
                        text-slate-400 hover:text-red-400
                        hover:bg-red-500/10 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                    </button>
                </form>
            </div>

            {{-- MOBILE BUTTON --}}
            <div class="flex items-center md:hidden">
                <button @click="open = !open"
                        class="p-2 rounded-lg text-slate-400 hover:bg-slate-800">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16"/>
                        <path :class="{ 'hidden': !open }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

        </div>
    </div>
</nav>
