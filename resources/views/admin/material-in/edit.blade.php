<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Edit Material Masuk') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow p-6">
                <form action="{{ route('admin.material-in.update', $materialIn) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Vendor -->
                    <div>
                        <x-input-label for="vendor_id" :value="__('Vendor')" />
                        <select id="vendor_id" name="vendor_id" class="block mt-1 w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                            <option value="">-- Pilih Vendor --</option>
                            @foreach($vendors as $vendor)
                            <option value="{{ $vendor->id }}" {{ old('vendor_id', $materialIn->vendor_id) == $vendor->id ? 'selected' : '' }}>{{ $vendor->name }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('vendor_id')" class="mt-2" />
                    </div>

                    <!-- Material -->
                    <div>
                        <x-input-label for="material_id" :value="__('Material')" />
                        <select id="material_id" name="material_id" class="block mt-1 w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                            <option value="">-- Pilih Material --</option>
                            @foreach($materials as $material)
                            <option value="{{ $material->id }}" {{ old('material_id', $materialIn->material_id) == $material->id ? 'selected' : '' }}>{{ $material->name }} ({{ $material->unit }})</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('material_id')" class="mt-2" />
                    </div>

                    <!-- Quantity -->
                    <div>
                        <x-input-label for="qty" :value="__('Kuantitas')" />
                        <x-text-input id="qty" class="block mt-1 w-full" type="number" name="qty" value="{{ old('qty', $materialIn->qty) }}" step="0.01" required />
                        <x-input-error :messages="$errors->get('qty')" class="mt-2" />
                    </div>

                    <!-- Price per Unit -->
                    <div>
                        <x-input-label for="price_per_unit" :value="__('Harga per Unit')" />
                        <x-text-input id="price_per_unit" class="block mt-1 w-full" type="number" name="price_per_unit" value="{{ old('price_per_unit', $materialIn->price_per_unit) }}" step="0.01" required />
                        <x-input-error :messages="$errors->get('price_per_unit')" class="mt-2" />
                    </div>

                    <!-- Invoice Number -->
                    <div>
                        <x-input-label for="invoice_number" :value="__('No Invoice')" />
                        <x-text-input id="invoice_number" class="block mt-1 w-full" type="text" name="invoice_number" value="{{ old('invoice_number', $materialIn->invoice_number) }}" />
                        <x-input-error :messages="$errors->get('invoice_number')" class="mt-2" />
                    </div>

                    <!-- Date -->
                    <div>
                        <x-input-label for="date" :value="__('Tanggal')" />
                        <x-text-input id="date" class="block mt-1 w-full" type="date" name="date" value="{{ old('date', $materialIn->date->format('Y-m-d')) }}" required />
                        <x-input-error :messages="$errors->get('date')" class="mt-2" />
                    </div>

                    <!-- Notes -->
                    <div>
                        <x-input-label for="note" :value="__('Catatan')" />
                        <textarea id="note" name="note" rows="3" class="block mt-1 w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('note', $materialIn->note) }}</textarea>
                        <x-input-error :messages="$errors->get('note')" class="mt-2" />
                    </div>

                    <!-- Buttons -->
                    <div class="flex gap-4 pt-4">
                        <x-primary-button>{{ __('Simpan') }}</x-primary-button>
                        <a href="{{ route('admin.material-in.index') }}" class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded-lg">{{ __('Batal') }}</a>
                        <form action="{{ route('admin.material-in.destroy', $materialIn) }}" method="POST" class="inline" onsubmit="return confirm('Hapus? Aksi ini tidak dapat dibatalkan.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg">{{ __('Hapus') }}</button>
                        </form>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
