<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                    📋 Permintaan Material
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Daftar permintaan material untuk Production Order
                </p>
            </div>

            <a href="{{ route('produksi.material-requests.create') }}"
               class="px-4 py-2 rounded-lg
                      bg-indigo-600 text-white
                      hover:bg-indigo-700 transition">
                + Buat Permintaan Baru
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- STATISTIK --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white dark:bg-gray-900
                            border border-gray-200 dark:border-gray-800
                            rounded-xl p-6">
                    <div class="flex items-center">
                        <div class="p-2 bg-blue-100 dark:bg-blue-900/40 rounded-lg">
                            <span class="text-blue-600 dark:text-blue-400">📋</span>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Total Requests</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['total_requests'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-900
                            border border-gray-200 dark:border-gray-800
                            rounded-xl p-6">
                    <div class="flex items-center">
                        <div class="p-2 bg-yellow-100 dark:bg-yellow-900/40 rounded-lg">
                            <span class="text-yellow-600 dark:text-yellow-400">⏳</span>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Menunggu</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['waiting'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-900
                            border border-gray-200 dark:border-gray-800
                            rounded-xl p-6">
                    <div class="flex items-center">
                        <div class="p-2 bg-green-100 dark:bg-green-900/40 rounded-lg">
                            <span class="text-green-600 dark:text-green-400">⚙️</span>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Diproses</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['processing'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-900
                            border border-gray-200 dark:border-gray-800
                            rounded-xl p-6">
                    <div class="flex items-center">
                        <div class="p-2 bg-purple-100 dark:bg-purple-900/40 rounded-lg">
                            <span class="text-purple-600 dark:text-purple-400">✅</span>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Selesai</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['completed'] }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- FILTER --}}
            <div class="bg-white dark:bg-gray-900
                        border border-gray-200 dark:border-gray-800
                        rounded-xl p-6 mb-6">
                <form method="GET" class="flex flex-col md:flex-row gap-4">
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Status
                        </label>
                        <select name="status"
                                class="w-full rounded-lg px-3 py-2
                                       bg-gray-100 dark:bg-gray-800
                                       border border-gray-300 dark:border-gray-700
                                       text-gray-900 dark:text-white">
                            <option value="">Semua Status</option>
                            <option value="waiting_material" {{ request('status') == 'waiting_material' ? 'selected' : '' }}>Menunggu Material</option>
                            <option value="process" {{ request('status') == 'process' ? 'selected' : '' }}>Diproses</option>
                            <option value="done" {{ request('status') == 'done' ? 'selected' : '' }}>Selesai</option>
                        </select>
                    </div>

                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Dari Tanggal
                        </label>
                        <input type="date" name="date_from" value="{{ request('date_from') }}"
                               class="w-full rounded-lg px-3 py-2
                                      bg-gray-100 dark:bg-gray-800
                                      border border-gray-300 dark:border-gray-700
                                      text-gray-900 dark:text-white">
                    </div>

                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Sampai Tanggal
                        </label>
                        <input type="date" name="date_to" value="{{ request('date_to') }}"
                               class="w-full rounded-lg px-3 py-2
                                      bg-gray-100 dark:bg-gray-800
                                      border border-gray-300 dark:border-gray-700
                                      text-gray-900 dark:text-white">
                    </div>

                    <div class="flex items-end">
                        <button type="submit"
                                class="px-4 py-2 rounded-lg
                                       bg-indigo-600 text-white
                                       hover:bg-indigo-700 transition">
                            Filter
                        </button>
                    </div>
                </form>
            </div>

            {{-- TABEL --}}
            <div class="bg-white dark:bg-gray-900
                        border border-gray-200 dark:border-gray-800
                        rounded-xl overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 dark:bg-gray-800">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Production Order
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Produk
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Target
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Status
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Prioritas
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Dibuat
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($orders as $order)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                        #{{ $order->id }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 dark:text-white">
                                        {{ $order->product->name }}
                                    </div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $order->product->sku }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 dark:text-white">
                                        {{ $order->target_quantity }} unit
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($order->status == 'waiting_material')
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                            Menunggu Material
                                        </span>
                                    @elseif($order->status == 'process')
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                            Diproses
                                        </span>
                                    @elseif($order->status == 'done')
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200">
                                            Selesai
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($order->priority == 'high')
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                            Tinggi
                                        </span>
                                    @elseif($order->priority == 'medium')
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200">
                                            Sedang
                                        </span>
                                    @else
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200">
                                            Rendah
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $order->created_at->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('produksi.production-orders.show', $order) }}"
                                       class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                        Lihat Detail
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                    Tidak ada permintaan material ditemukan.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- PAGINATION --}}
                @if($orders->hasPages())
                <div class="bg-gray-50 dark:bg-gray-800 px-6 py-3">
                    {{ $orders->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>