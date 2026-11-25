<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Detail Material Keluar') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-lg font-medium text-gray-900">Tanggal {{ $materialOut->date->format('d/m/Y') }}</h3>
                </div>

                <div class="px-6 py-6 space-y-6">
                    <!-- Production Order -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Pesanan Produksi</label>
                        <p class="mt-1 text-lg text-gray-900">{{ $materialOut->productionOrder->product->name }}</p>
                    </div>

                    <!-- Material -->
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Material</label>
                            <p class="mt-1 text-lg text-gray-900">{{ $materialOut->material->name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Unit</label>
                            <p class="mt-1 text-lg text-gray-900">{{ $materialOut->material->unit }}</p>
                        </div>
                    </div>

                    <!-- Quantity & Date -->
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Kuantitas</label>
                            <p class="mt-1 text-lg font-medium text-blue-600">{{ number_format($materialOut->qty, 2) }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tanggal</label>
                            <p class="mt-1 text-lg text-gray-900">{{ $materialOut->date->format('d/m/Y') }}</p>
                        </div>
                    </div>

                    <!-- Issued By -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Dikeluarkan oleh</label>
                        <p class="mt-1 text-lg text-gray-900">{{ $materialOut->issuedBy->name }}</p>
                    </div>

                    <!-- Notes -->
                    @if($materialOut->notes)
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Catatan</label>
                        <p class="mt-1 text-gray-900 whitespace-pre-wrap">{{ $materialOut->notes }}</p>
                    </div>
                    @endif

                    <!-- Buttons -->
                    <div class="flex gap-4 pt-6 border-t border-gray-200">
                        <a href="{{ route('admin.material-out.edit', $materialOut) }}" class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg">✏️ Edit</a>
                        <a href="{{ route('admin.material-out.index') }}" class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded-lg">← Kembali</a>
                        <form action="{{ route('admin.material-out.destroy', $materialOut) }}" method="POST" class="inline" onsubmit="return confirm('Hapus?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg">🗑️ Hapus</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
