<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Tambah Barang Jadi Masuk') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow p-6">
                <form action="{{ route('admin.product-in.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- Production Order -->
                    <div>
                        <x-input-label for="production_order_id" :value="__('Pesanan Produksi')" />
                        <select id="production_order_id" name="production_order_id" class="block mt-1 w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                            <option value="">-- Pilih Pesanan --</option>
                            @foreach($productionOrders as $order)
                            <option value="{{ $order->id }}" {{ old('production_order_id') == $order->id ? 'selected' : '' }}>{{ $order->product->name }} ({{ $order->id }})</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('production_order_id')" class="mt-2" />
                    </div>

                    <!-- Product -->
                    <div>
                        <x-input-label for="product_id" :value="__('Produk')" />
                        <select id="product_id" name="product_id" class="block mt-1 w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                            <option value="">-- Pilih Produk --</option>
                            @foreach($products as $product)
                            <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>{{ $product->name }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('product_id')" class="mt-2" />
                    </div>

                    <!-- Quantity -->
                    <div>
                        <x-input-label for="qty" :value="__('Kuantitas')" />
                        <x-text-input id="qty" class="block mt-1 w-full" type="number" name="qty" value="{{ old('qty') }}" required />
                        <x-input-error :messages="$errors->get('qty')" class="mt-2" />
                    </div>

                    <!-- Condition -->
                    <div>
                        <x-input-label for="condition" :value="__('Kondisi')" />
                        <select id="condition" name="condition" class="block mt-1 w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                            <option value="">-- Pilih Kondisi --</option>
                            <option value="good" {{ old('condition') === 'good' ? 'selected' : '' }}>Baik</option>
                            <option value="defect" {{ old('condition') === 'defect' ? 'selected' : '' }}>Ada Cacat</option>
                        </select>
                        <x-input-error :messages="$errors->get('condition')" class="mt-2" />
                    </div>

                    <!-- Defect Notes -->
                    <div>
                        <x-input-label for="defect_notes" :value="__('Catatan Cacat')" />
                        <textarea id="defect_notes" name="defect_notes" rows="3" class="block mt-1 w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('defect_notes') }}</textarea>
                        <x-input-error :messages="$errors->get('defect_notes')" class="mt-2" />
                    </div>

                    <!-- Date -->
                    <div>
                        <x-input-label for="date" :value="__('Tanggal')" />
                        <x-text-input id="date" class="block mt-1 w-full" type="date" name="date" value="{{ old('date', date('Y-m-d')) }}" required />
                        <x-input-error :messages="$errors->get('date')" class="mt-2" />
                    </div>

                    <!-- Buttons -->
                    <div class="flex gap-4 pt-4">
                        <x-primary-button>{{ __('Simpan') }}</x-primary-button>
                        <a href="{{ route('admin.product-in.index') }}" class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded-lg">{{ __('Batal') }}</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
