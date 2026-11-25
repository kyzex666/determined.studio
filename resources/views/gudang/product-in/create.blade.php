<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            Tambah Produk Masuk
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            <div class="shadow rounded p-6">

                <form action="{{ route('gudang.product-in.store') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label class="block mb-1">Production Order</label>
                        <select name="production_order_id" class="bg-black w-full border rounded p-2" required>
                            <option class="bg-black" value="">-- Pilih --</option>
                            @foreach ($productionOrders as $po)
                                <option class="bg-black " value="{{ $po->id }}">
                                    {{ $po->order_code }}
                                </option>
                            @endforeach
                        </select>
                        @error('production_order_id')
                            <small class="text-red-600">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block mb-1">Produk</label>
                        <select name="product_id" class="bg-black w-full border rounded p-2" required>
                            <option value="">-- Pilih --</option>
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}">
                                    {{ $product->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('product_id')
                            <small class="text-red-600">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block mb-1">Qty</label>
                        <input type="number" name="qty" min="1"
                               class="bg-black w-full border rounded p-2"
                               value="{{ old('qty') }}" required>
                        @error('qty')
                            <small class="text-red-600">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block mb-1">Tanggal</label>
                        <input type="date" name="date"
                               class="bg-black w-full border rounded p-2"
                               value="{{ old('date', now()->toDateString()) }}" required>
                        @error('date')
                            <small class="text-red-600">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block mb-1">Kondisi</label>
                        <select name="condition" class="bg-black w-full border rounded p-2" required>
                            <option value="good">Baik</option>
                            <option value="defect">Cacat</option>
                        </select>
                        @error('condition')
                            <small class="text-red-600">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label class="block mb-1">Catatan Cacat (Opsional)</label>
                        <textarea name="defect_notes"
                                  class="bg-black w-full border rounded p-2"
                                  rows="3">{{ old('defect_notes') }}</textarea>
                    </div>

                    <div class="flex justify-end gap-2">
                        <a href="{{ route('gudang.product-in.index') }}"
                           class="px-4 py-2 border rounded">
                            Batal
                        </a>
                        <button type="submit"
                                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                            Simpan
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
