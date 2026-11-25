<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Detail Material Masuk') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-lg font-medium text-gray-900">{{ $materialIn->transaction_code }}</h3>
                </div>

                <div class="px-6 py-6 space-y-6">
                    <!-- Vendor -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Vendor</label>
                        <p class="mt-1 text-lg text-gray-900">{{ $materialIn->vendor->name }}</p>
                    </div>

                    <!-- Material -->
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Material</label>
                            <p class="mt-1 text-lg text-gray-900">{{ $materialIn->material->name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Unit</label>
                            <p class="mt-1 text-lg text-gray-900">{{ $materialIn->material->unit }}</p>
                        </div>
                    </div>

                    <!-- Quantity & Price -->
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Kuantitas</label>
                            <p class="mt-1 text-lg text-gray-900">{{ number_format($materialIn->qty, 2) }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Harga per Unit</label>
                            <p class="mt-1 text-lg text-gray-900">Rp {{ number_format($materialIn->price_per_unit, 0, ',', '.') }}</p>
                        </div>
                    </div>

                    <!-- Total & Invoice -->
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Total Harga</label>
                            <p class="mt-1 text-lg font-medium text-blue-600">Rp {{ number_format($materialIn->total_price, 0, ',', '.') }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">No Invoice</label>
                            <p class="mt-1 text-lg text-gray-900">{{ $materialIn->invoice_number ?? '-' }}</p>
                        </div>
                    </div>

                    <!-- Date & Receiver -->
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tanggal</label>
                            <p class="mt-1 text-lg text-gray-900">{{ $materialIn->date->format('d/m/Y') }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Diterima oleh</label>
                            <p class="mt-1 text-lg text-gray-900">{{ $materialIn->receivedBy->name }}</p>
                        </div>
                    </div>

                    <!-- Notes -->
                    @if($materialIn->note)
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Catatan</label>
                        <p class="mt-1 text-gray-900 whitespace-pre-wrap">{{ $materialIn->note }}</p>
                    </div>
                    @endif

                    <!-- Buttons -->
                    <div class="flex gap-4 pt-6 border-t border-gray-200">
                        <a href="{{ route('admin.material-in.edit', $materialIn) }}" class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg">✏️ Edit</a>
                        <a href="{{ route('admin.material-in.index') }}" class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded-lg">← Kembali</a>
                        <form action="{{ route('admin.material-in.destroy', $materialIn) }}" method="POST" class="inline" onsubmit="return confirm('Hapus?')">
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
