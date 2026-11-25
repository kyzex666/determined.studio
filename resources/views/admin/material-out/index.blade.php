<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Material Keluar') }}
            </h2>

            <a href="{{ route('admin.material-out.create') }}"
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl shadow-sm flex items-center gap-2 transition">
                ✚ Tambah
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100">

                <!-- FILTER SECTION -->
                <div class="p-6 border-b bg-gray-50/70 backdrop-blur-lg">

                    <form method="GET" class="space-y-4 text-sm">

                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

                            <!-- Pesanan Produksi -->
                            <div>
                                <label class="block text-gray-700 font-medium mb-1">Pesanan Produksi</label>
                                <select name="production_order_id"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-blue-500">
                                    <option value="">-- Semua --</option>
                                    @foreach($productionOrders as $order)
                                        <option value="{{ $order->id }}"
                                            {{ request('production_order_id') == $order->id ? 'selected' : '' }}>
                                            {{ $order->product->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Material -->
                            <div>
                                <label class="block text-gray-700 font-medium mb-1">Material</label>
                                <select name="material_id"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-blue-500">
                                    <option value="">-- Semua --</option>
                                    @foreach($materials as $material)
                                        <option value="{{ $material->id }}"
                                            {{ request('material_id') == $material->id ? 'selected' : '' }}>
                                            {{ $material->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Dari -->
                            <div>
                                <label class="block text-gray-700 font-medium mb-1">Dari</label>
                                <input type="date" name="date_from" value="{{ request('date_from') }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-blue-500">
                            </div>

                            <!-- Sampai -->
                            <div>
                                <label class="block text-gray-700 font-medium mb-1">Sampai</label>
                                <input type="date" name="date_to" value="{{ request('date_to') }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-blue-500">
                            </div>

                        </div>

                        <div class="flex gap-2">
                            <button type="submit"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl shadow-sm transition">
                                🔍 Filter
                            </button>

                            <a href="{{ route('admin.material-out.index') }}"
                                class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded-xl shadow-sm transition">
                                ✕ Reset
                            </a>
                        </div>

                    </form>

                </div>

                <!-- TABLE -->
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-100 border-b border-gray-200">
                            <tr class="text-gray-600 text-xs uppercase tracking-wide">
                                <th class="px-6 py-3 text-left">Tanggal</th>
                                <th class="px-6 py-3 text-left">Pesanan</th>
                                <th class="px-6 py-3 text-left">Material</th>
                                <th class="px-6 py-3 text-right">Qty</th>
                                <th class="px-6 py-3 text-left">Dikeluarkan oleh</th>
                                <th class="px-6 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100">

                            @forelse($materialOut as $item)
                            <tr class="hover:bg-blue-50/40 transition-all">

                                <td class="px-6 py-4 text-gray-800">
                                    {{ $item->date->format('d/m/Y') }}
                                </td>

                                <td class="px-6 py-4 text-gray-800">
                                    {{ $item->productionOrder->product->name }}
                                </td>

                                <td class="px-6 py-4 text-gray-800">
                                    {{ $item->material->name }}
                                </td>

                                <td class="px-6 py-4 text-right text-gray-900 font-semibold">
                                    {{ number_format($item->qty, 2) }} {{ $item->material->unit }}
                                </td>

                                <td class="px-6 py-4 text-gray-800">
                                    {{ $item->issuedBy->name }}
                                </td>

                                <td class="px-6 py-4 text-center">
                                    <div class="flex justify-center gap-3">

                                        <a href="{{ route('admin.material-out.show', $item) }}"
                                            class="text-blue-600 hover:text-blue-800 text-lg transition">
                                            👁️
                                        </a>

                                        <a href="{{ route('admin.material-out.edit', $item) }}"
                                            class="text-yellow-600 hover:text-yellow-800 text-lg transition">
                                            ✏️
                                        </a>

                                        <form action="{{ route('admin.material-out.destroy', $item) }}"
                                            method="POST"
                                            class="inline"
                                            onsubmit="return confirm('Hapus data ini?')">

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                class="text-red-600 hover:text-red-800 text-lg transition">
                                                🗑️
                                            </button>

                                        </form>
                                    </div>
                                </td>

                            </tr>

                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                    Belum ada data
                                </td>
                            </tr>
                            @endforelse

                        </tbody>
                    </table>
                </div>

                <!-- PAGINATION -->
                @if($materialOut->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $materialOut->links() }}
                </div>
                @endif

            </div>

        </div>
    </div>
</x-app-layout>
