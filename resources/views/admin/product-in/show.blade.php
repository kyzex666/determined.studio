<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Detail Barang Jadi Masuk') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-lg font-medium text-gray-900">{{ $productIn->date->format('d/m/Y') }}</h3>
                </div>

                <div class="px-6 py-6 space-y-6">
                    <!-- Production Order -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Pesanan Produksi</label>
                        <p class="mt-1 text-lg text-gray-900">{{ $productIn->productionOrder->product->name }}</p>
                    </div>

                    <!-- Product -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Produk</label>
                        <p class="mt-1 text-lg text-gray-900">{{ $productIn->product->name }}</p>
                    </div>

                    <!-- Quantity & Condition -->
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Kuantitas</label>
                            <p class="mt-1 text-lg font-medium text-blue-600">{{ $productIn->qty }} pcs</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Kondisi</label>
                            <div class="mt-1">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium {{ $productIn->condition == 'good' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ $productIn->condition == 'good' ? 'Baik' : 'Ada Cacat' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Date & Receiver -->
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tanggal</label>
                            <p class="mt-1 text-lg text-gray-900">{{ $productIn->date->format('d/m/Y') }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Diterima oleh</label>
                            <p class="mt-1 text-lg text-gray-900">{{ $productIn->receivedBy->name }}</p>
                        </div>
                    </div>

                    <!-- Defect Notes -->
                    @if($productIn->defect_notes)
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Catatan Cacat</label>
                        <p class="mt-1 text-gray-900 whitespace-pre-wrap">{{ $productIn->defect_notes }}</p>
                    </div>
                    @endif

                    <!-- Buttons -->
                    <div class="flex gap-4 pt-6 border-t border-gray-200">
                        <a href="{{ route('admin.product-in.edit', $productIn) }}" class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg">✏️ Edit</a>
                        <a href="{{ route('admin.product-in.index') }}" class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded-lg">← Kembali</a>
                        <form action="{{ route('admin.product-in.destroy', $productIn) }}" method="POST" class="inline" onsubmit="return confirm('Hapus?')">
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
