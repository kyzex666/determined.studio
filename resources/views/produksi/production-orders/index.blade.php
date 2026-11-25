<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                    🏭 Order Produksi
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Daftar proses produksi yang sedang berjalan
                </p>
            </div>

            <a href="{{ route('produksi.production-orders.create') }}"
               class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl
                      bg-gradient-to-r from-blue-600 to-indigo-600
                      text-white font-semibold shadow-lg hover:opacity-90 transition">
                ➕ Buat Order Produksi
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white dark:bg-gray-900
                        rounded-2xl shadow-xl
                        border border-gray-200 dark:border-gray-800
                        overflow-hidden">

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-100 dark:bg-gray-800">
                            <tr class="text-left text-xs uppercase tracking-wider
                                       text-gray-600 dark:text-gray-400">
                                <th class="px-6 py-4">Kode Order</th>
                                <th class="px-6 py-4">Produk</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4 text-center">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                            @foreach ($orders as $order)
                                <tr class="hover:bg-blue-50 dark:hover:bg-gray-800 transition">

                                    <!-- KODE -->
                                    <td class="px-6 py-4 font-semibold
                                               text-gray-900 dark:text-white">
                                        {{ $order->order_code }}
                                    </td>

                                    <!-- PRODUK -->
                                    <td class="px-6 py-4
                                               text-gray-700 dark:text-gray-300">
                                        {{ $order->product->name }}
                                    </td>

                                    <!-- STATUS -->
                                    <td class="px-6 py-4">
                                        @php
                                            $statusClass = match($order->status) {
                                                'draft' => 'bg-gray-200 text-gray-700 dark:bg-gray-700 dark:text-gray-300',
                                                'waiting_material' => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900 dark:text-yellow-300',
                                                'process' => 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300',
                                                'done' => 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300',
                                                default => 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300'
                                            };
                                        @endphp

                                        <span class="px-3 py-1 rounded-lg text-xs font-bold {{ $statusClass }}">
                                            {{ strtoupper($order->status) }}
                                        </span>
                                    </td>

                                    <!-- AKSI -->
                                    <td class="px-6 py-4">
                                        <div class="flex justify-center gap-4 text-lg">

                                            <a href="{{ route('produksi.production-orders.show', $order) }}"
                                               class="text-blue-500 hover:text-blue-700"
                                               title="Detail Order">
                                                👁
                                            </a>

                                            @if($order->status === 'process')
                                            <a href="{{ route('produksi.production-orders.show', $order) }}#complete"
                                               class="text-green-500 hover:text-green-700"
                                               title="Selesaikan Produksi">
                                                ✅
                                            </a>
                                            @endif

                                        </div>
                                    </td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if ($orders->isEmpty())
                    <div class="py-12 text-center text-gray-500 dark:text-gray-400">
                        Belum ada order produksi
                    </div>
                @endif

            </div>

        </div>
    </div>
</x-app-layout>
