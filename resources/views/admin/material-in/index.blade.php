<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Material Masuk') }}</h2>
            <a href="{{ route('admin.material-in.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2">✚ Tambah</a>
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
                                <label class="block text-sm font-medium text-gray-700 mb-1">Vendor</label>
                                <select name="vendor_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">-- Semua Vendor --</option>
                                    @foreach($vendors as $vendor)
                                    <option value="{{ $vendor->id }}" {{ request('vendor_id') == $vendor->id ? 'selected' : '' }}>{{ $vendor->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Material</label>
                                <select name="material_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">-- Semua Material --</option>
                                    @foreach($materials as $material)
                                    <option value="{{ $material->id }}" {{ request('material_id') == $material->id ? 'selected' : '' }}>{{ $material->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Dari Tanggal</label>
                                <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Sampai Tanggal</label>
                                <input type="date" name="date_to" value="{{ request('date_to') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">🔍 Filter</button>
                            <a href="{{ route('admin.material-in.index') }}" class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded-lg">✕ Reset</a>
                        </div>
                    </form>
                </div>

                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-3 text-left font-medium text-gray-700">Kode Transaksi</th>
                                <th class="px-6 py-3 text-left font-medium text-gray-700">Tanggal</th>
                                <th class="px-6 py-3 text-left font-medium text-gray-700">Vendor</th>
                                <th class="px-6 py-3 text-left font-medium text-gray-700">Material</th>
                                <th class="px-6 py-3 text-right font-medium text-gray-700">Qty</th>
                                <th class="px-6 py-3 text-right font-medium text-gray-700">Total</th>
                                <th class="px-6 py-3 text-center font-medium text-gray-700">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($materialIn as $item)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 font-medium text-gray-900">{{ $item->transaction_code }}</td>
                                <td class="px-6 py-4 text-gray-700">{{ $item->date->format('d/m/Y') }}</td>
                                <td class="px-6 py-4 text-gray-700">{{ $item->vendor->name }}</td>
                                <td class="px-6 py-4 text-gray-700">{{ $item->material->name }}</td>
                                <td class="px-6 py-4 text-right text-gray-700">{{ number_format($item->qty, 2) }} {{ $item->material->unit }}</td>
                                <td class="px-6 py-4 text-right text-gray-700">Rp {{ number_format($item->total_price, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex justify-center gap-2">
                                        <a href="{{ route('admin.material-in.show', $item) }}" class="text-blue-600 hover:text-blue-900">👁️</a>
                                        <a href="{{ route('admin.material-in.edit', $item) }}" class="text-yellow-600 hover:text-yellow-900">✏️</a>
                                        <form action="{{ route('admin.material-in.destroy', $item) }}" method="POST" class="inline" onsubmit="return confirm('Hapus?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">🗑️</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-6 py-4 text-center text-gray-500">Belum ada data</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($materialIn->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $materialIn->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
