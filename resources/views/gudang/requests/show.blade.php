<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl">Detail Produk</h2>
            <a href="{{ route('gudang.requests.index') }}"
               class="px-4 py-2 rounded-lg">
                ← Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-8 max-w-5xl mx-auto">

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-blue-600 text-white p-6 rounded-xl">
                <div class="text-sm">Stok Saat Ini</div>
                <div class="text-3xl font-bold">
                    {{ number_format($product->current_stock, 2) }} {{ $product->unit }}
                </div>
            </div>

            <div class="p-6 rounded-xl shadow">
                <div class="text-sm text-gray-500">SKU</div>
                <div class="text-xl font-semibold">{{ $product->sku }}</div>
            </div>

            <div class="p-6 rounded-xl shadow">
                <div class="text-sm text-gray-500">Satuan</div>
                <div class="text-xl font-semibold">{{ strtoupper($product->unit) }}</div>
            </div>
        </div>

        <div class="rounded-xl shadow p-6">
            <p class="text-sm text-gray-500">Nama Produk</p>
            <p class="text-lg font-medium">{{ $product->name }}</p>

            <p class="text-sm text-gray-500 mt-4">Catatan</p>
            <p>{{ $product->notes ?? '-' }}</p>
        </div>

    </div>
</x-app-layout>
