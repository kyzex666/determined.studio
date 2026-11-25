<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Barang Jadi Masuk') }}</h2>
            <a href="{{ route('admin.product-in.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2">✚ Tambah</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <!-- Filters -->
                <div class="p-6 border-b border-gray-200 bg-gray-50">
                    <form method="GET" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Pesanan Produksi</label>
                                <select name="production_order_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">-- Semua --</option>
                                    @foreach($productionOrders as $order)
                                    <option value="{{ $order->id }}" {{ request('production_order_id') == $order->id ? 'selected' : '' }}>{{ $order->product->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Produk</label>
                                <select name="product_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">-- Semua --</option>
                                    @foreach($products as $product)
                                    <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>{{ $product->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Dari</label>
                                <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Sampai</label>
                                <input type="date" name="date_to" value="{{ request('date_to') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">🔍 Filter</button>
                            <a href="{{ route('admin.product-in.index') }}" class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded-lg">✕ Reset</a>
                        </div>
                    </form>
                </div>

                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-3 text-left font-medium text-gray-700">Tanggal</th>
                                <th class="px-6 py-3 text-left font-medium text-gray-700">Pesanan</th>
                                <th class="px-6 py-3 text-left font-medium text-gray-700">Produk</th>
                                <th class="px-6 py-3 text-right font-medium text-gray-700">Qty</th>
                                <th class="px-6 py-3 text-left font-medium text-gray-700">Kondisi</th>
                                <th class="px-6 py-3 text-center font-medium text-gray-700">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($productIn as $item)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-gray-700">{{ $item->date->format('d/m/Y') }}</td>
                                <td class="px-6 py-4 text-gray-700">{{ $item->productionOrder->product->name }}</td>
                                <td class="px-6 py-4 text-gray-700">{{ $item->product->name }}</td>
                                <td class="px-6 py-4 text-right text-gray-700">{{ $item->qty }} pcs</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $item->condition == 'good' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ $item->condition == 'good' ? 'Baik' : 'Ada Cacat' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex justify-center gap-2">
                                        <a href="{{ route('admin.product-in.show', $item) }}" class="text-blue-600 hover:text-blue-900">👁️</a>
                                        <a href="{{ route('admin.product-in.edit', $item) }}" class="text-yellow-600 hover:text-yellow-900">✏️</a>
                                        <form action="{{ route('admin.product-in.destroy', $item) }}" method="POST" class="inline" onsubmit="return confirm('Hapus?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">🗑️</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-gray-500">Belum ada data</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($productIn->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $productIn->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
