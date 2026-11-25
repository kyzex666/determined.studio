<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Tambah Material Masuk') }}
            </h2>
            <a href="{{ route('gudang.material-in.index') }}" class="text-blue-600 hover:text-blue-900">
                ← Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('gudang.material-in.store') }}" method="POST" id="materialInForm">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Vendor Selection -->
                            <div>
                                <x-input-label for="vendor_id" :value="__('Vendor')" />
                                <select id="vendor_id" name="vendor_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                                    <option value="">Pilih Vendor</option>
                                    @foreach($vendors as $vendor)
                                        <option value="{{ $vendor->id }}" {{ old('vendor_id') == $vendor->id ? 'selected' : '' }}>
                                            {{ $vendor->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('vendor_id')" class="mt-2" />
                            </div>

                            <!-- Material Selection -->
                            <div>
                                <x-input-label for="material_id" :value="__('Material')" />
                                <select id="material_id" name="material_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                                    <option value="">Pilih Material</option>
                                    @foreach($materials as $material)
                                        <option value="{{ $material->id }}" 
                                                data-unit="{{ $material->unit }}"
                                                data-stock="{{ $material->current_stock }}"
                                                data-minstock="{{ $material->min_stock }}"
                                                {{ old('material_id') == $material->id ? 'selected' : '' }}>
                                            {{ $material->name }} (Stok: {{ number_format($material->current_stock, 2) }} {{ $material->unit }})
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('material_id')" class="mt-2" />
                            </div>

                            <!-- Quantity -->
                            <div>
                                <x-input-label for="qty" :value="__('Jumlah')" />
                                <x-text-input id="qty" type="number" name="qty" step="0.01" class="mt-1 block w-full" 
                                    value="{{ old('qty') }}" required />
                                <x-input-error :messages="$errors->get('qty')" class="mt-2" />
                            </div>

                            <!-- Price Per Unit -->
                            <div>
                                <x-input-label for="price_per_unit" :value="__('Harga Per Unit')" />
                                <x-text-input id="price_per_unit" type="number" name="price_per_unit" step="0.01" class="mt-1 block w-full" 
                                    value="{{ old('price_per_unit') }}" required />
                                <x-input-error :messages="$errors->get('price_per_unit')" class="mt-2" />
                            </div>

                            <!-- Invoice Number -->
                            <div>
                                <x-input-label for="invoice_number" :value="__('Nomor Invoice')" />
                                <x-text-input id="invoice_number" type="text" name="invoice_number" class="mt-1 block w-full" 
                                    value="{{ old('invoice_number') }}" />
                                <x-input-error :messages="$errors->get('invoice_number')" class="mt-2" />
                            </div>

                            <!-- Date -->
                            <div>
                                <x-input-label for="date" :value="__('Tanggal')" />
                                <x-text-input id="date" type="date" name="date" class="mt-1 block w-full" 
                                    value="{{ old('date', date('Y-m-d')) }}" required />
                                <x-input-error :messages="$errors->get('date')" class="mt-2" />
                            </div>

                            <!-- Notes -->
                            <div class="md:col-span-2">
                                <x-input-label for="notes" :value="__('Catatan')" />
                                <textarea id="notes" name="notes" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ old('notes') }}</textarea>
                                <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="flex items-center justify-end mt-8">
                            <a href="{{ route('gudang.material-in.index') }}" class="text-gray-600 hover:text-gray-900 mr-4">
                                {{ __('Batal') }}
                            </a>
                            <x-primary-button class="ml-4">
                                {{ __('Simpan') }}
                            </x-primary-button>
                        </div>
                    </form>

                    <!-- Stock Info -->
                    <div class="mt-8 p-4 bg-blue-50 border border-blue-200 rounded-md" id="stockInfo" style="display: none;">
                        <h3 class="font-semibold text-blue-900 mb-2">Informasi Stok</h3>
                        <p class="text-blue-800">Stok Tersedia: <span id="currentStock">-</span> <span id="unit">-</span></p>
                        <p class="text-blue-800">Stok Minimum: <span id="minStock">-</span> <span id="unitMin">-</span></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('material_id').addEventListener('change', function() {
            const option = this.options[this.selectedIndex];
            const unit = option.dataset.unit;
            const stock = option.dataset.stock;
            const minStock = option.dataset.minstock;

            if (unit) {
                document.getElementById('stockInfo').style.display = 'block';
                document.getElementById('currentStock').textContent = parseFloat(stock).toFixed(2);
                document.getElementById('unit').textContent = unit;
                document.getElementById('minStock').textContent = parseFloat(minStock).toFixed(2);
                document.getElementById('unitMin').textContent = unit;
            } else {
                document.getElementById('stockInfo').style.display = 'none';
            }
        });
    </script>
</x-app-layout>
