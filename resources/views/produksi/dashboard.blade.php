<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-1">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                🏭 Dashboard Produksi
            </h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">
                Ringkasan aktivitas produksi hari ini
            </p>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            <!-- TOTAL ORDER -->
            <div class="relative overflow-hidden rounded-2xl
                        bg-white dark:bg-gray-900
                        border border-gray-200 dark:border-gray-800
                        shadow-xl p-6">

                <div class="absolute -top-10 -right-10 w-32 h-32
                            bg-blue-500/20 rounded-full blur-2xl"></div>

                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm uppercase tracking-widest
                                  text-gray-500 dark:text-gray-400">
                            Total Order
                        </p>
                        <p class="text-4xl font-extrabold mt-2
                                  text-gray-900 dark:text-white">
                            {{ $totalOrders ?? 0 }}
                        </p>
                    </div>

                    <div class="w-14 h-14 flex items-center justify-center
                                rounded-xl bg-blue-100 dark:bg-blue-900
                                text-blue-600 dark:text-blue-300 text-2xl">
                        📦
                    </div>
                </div>
            </div>

            <!-- DALAM PROSES -->
            <div class="relative overflow-hidden rounded-2xl
                        bg-white dark:bg-gray-900
                        border border-gray-200 dark:border-gray-800
                        shadow-xl p-6">

                <div class="absolute -top-10 -right-10 w-32 h-32
                            bg-yellow-500/20 rounded-full blur-2xl"></div>

                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm uppercase tracking-widest
                                  text-gray-500 dark:text-gray-400">
                            Dalam Proses
                        </p>
                        <p class="text-4xl font-extrabold mt-2
                                  text-yellow-600 dark:text-yellow-400">
                            {{ $inProcess ?? 0 }}
                        </p>
                    </div>

                    <div class="w-14 h-14 flex items-center justify-center
                                rounded-xl bg-yellow-100 dark:bg-yellow-900
                                text-yellow-600 dark:text-yellow-300 text-2xl">
                        ⚙️
                    </div>
                </div>
            </div>

            <!-- SELESAI HARI INI -->
            <div class="relative overflow-hidden rounded-2xl
                        bg-white dark:bg-gray-900
                        border border-gray-200 dark:border-gray-800
                        shadow-xl p-6">

                <div class="absolute -top-10 -right-10 w-32 h-32
                            bg-green-500/20 rounded-full blur-2xl"></div>

                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm uppercase tracking-widest
                                  text-gray-500 dark:text-gray-400">
                            Selesai Hari Ini
                        </p>
                        <p class="text-4xl font-extrabold mt-2
                                  text-green-600 dark:text-green-400">
                            {{ $completedToday ?? 0 }}
                        </p>
                    </div>

                    <div class="w-14 h-14 flex items-center justify-center
                                rounded-xl bg-green-100 dark:bg-green-900
                                text-green-600 dark:text-green-300 text-2xl">
                        ✅
                    </div>
                </div>
            </div>

        </div>

        <!-- QUICK ACTIONS -->
<div class="mt-10 grid grid-cols-1 md:grid-cols-3 gap-6">

    {{-- ORDER PRODUKSI --}}
    <a href="{{ route('produksi.production-orders.create') }}"
       class="rounded-2xl p-6 bg-gradient-to-br from-blue-600 to-indigo-600
              text-white shadow-xl hover:opacity-90 transition">

        <p class="text-sm opacity-80">Quick Action</p>
        <p class="text-xl font-bold mt-1">
            ➕ Buat Order Produksi
        </p>
    </a>

    {{-- REQUEST MATERIAL --}}
    <a href="{{ route('produksi.material-requests.create') }}"
       class="rounded-2xl p-6 bg-gradient-to-br from-amber-500 to-orange-600
              text-white shadow-xl hover:opacity-90 transition">

        <p class="text-sm opacity-80">Quick Action</p>
        <p class="text-xl font-bold mt-1">
            📦 Request Material
        </p>
    </a>

    {{-- HASIL PRODUKSI --}}
    <a href="{{ route('produksi.results.index') }}"
       class="rounded-2xl p-6 bg-gradient-to-br from-emerald-600 to-green-600
              text-white shadow-xl hover:opacity-90 transition">

        <p class="text-sm opacity-80">Monitoring</p>
        <p class="text-xl font-bold mt-1">
            🏭 Hasil Produksi
        </p>
    </a>

</div>

        <!-- RECENTLY COMPLETED ORDERS -->
        <div class="mt-10">
            <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-800 overflow-hidden">
                <div class="p-6 border-b border-gray-200 dark:border-gray-800">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">
                        ✅ Order Selesai Terbaru
                    </h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        Order produksi yang telah selesai dan dikirim ke gudang
                    </p>
                </div>

                <div class="p-6">
                    @if($recentlyCompleted && count($recentlyCompleted) > 0)
                        @foreach($recentlyCompleted as $order)
                            <div class="flex items-center justify-between py-4 border-b border-gray-100 dark:border-gray-800 last:border-b-0">
                                <div class="flex items-center space-x-4">
                                    <div class="w-12 h-12 bg-green-100 dark:bg-green-900 rounded-xl flex items-center justify-center">
                                        <span class="text-green-600 dark:text-green-400 text-xl">📦</span>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-gray-900 dark:text-white">
                                            {{ $order->product->name }}
                                        </h4>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                            Order: {{ $order->order_code }} • Qty: {{ $order->actual_qty ?? $order->target_qty }}
                                        </p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $order->end_date?->format('d/m/Y') }}
                                    </p>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">
                                        Selesai
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="py-12 text-center">
                            <div class="w-16 h-16 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-4">
                                <span class="text-gray-400 text-2xl">📦</span>
                            </div>
                            <h4 class="text-lg font-medium text-gray-900 dark:text-white mb-2">
                                Belum ada order selesai
                            </h4>
                            <p class="text-gray-500 dark:text-gray-400">
                                Order produksi yang selesai akan muncul di sini
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>
</x-app-layout>