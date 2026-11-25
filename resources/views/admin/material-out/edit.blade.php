<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Edit Material Keluar') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow p-6">
                <form action="{{ route('admin.material-out.update', $materialOut) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Production Order -->
                    <div>
                        <x-input-label for="production_order_id" :value="__('Pesanan Produksi')" />
                        <select id="production_order_id" name="production_order_id" class="block mt-1 w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                            <option value="">-- Pilih Pesanan --</option>
                            @foreach($productionOrders as $order)
                            <option value="{{ $order->id }}" {{ old('production_order_id', $materialOut->production_order_id) == $order->id ? 'selected' : '' }}>{{ $order->product->name }} ({{ $order->id }})</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('production_order_id')" class="mt-2" />
                    </div>

                    <!-- Material -->
                    <div>
                        <x-input-label for="material_id" :value="__('Material')" />
                        <select id="material_id" name="material_id" class="block mt-1 w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                            <option value="">-- Pilih Material --</option>
                            @foreach($materials as $material)
                            <option value="{{ $material->id }}" {{ old('material_id', $materialOut->material_id) == $material->id ? 'selected' : '' }}>{{ $material->name }} ({{ $material->unit }}, Stok: {{ $material->current_stock }})</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('material_id')" class="mt-2" />
                    </div>

                    <!-- Quantity -->
                    <div>
                        <x-input-label for="qty" :value="__('Kuantitas')" />
                        <x-text-input id="qty" class="block mt-1 w-full" type="number" name="qty" value="{{ old('qty', $materialOut->qty) }}" step="0.01" required />
                        <x-input-error :messages="$errors->get('qty')" class="mt-2" />
                    </div>

                    <!-- Date -->
                    <div>
                        <x-input-label for="date" :value="__('Tanggal')" />
                        <x-text-input id="date" class="block mt-1 w-full" type="date" name="date" value="{{ old('date', $materialOut->date->format('Y-m-d')) }}" required />
                        <x-input-error :messages="$errors->get('date')" class="mt-2" />
                    </div>

                    <!-- Notes -->
                    <div>
                        <x-input-label for="notes" :value="__('Catatan')" />
                        <textarea id="notes" name="notes" rows="3" class="block mt-1 w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('notes', $materialOut->notes) }}</textarea>
                        <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                    </div>

                    <!-- Buttons -->
                    <div class="flex gap-4 pt-4">
                        <x-primary-button>{{ __('Simpan') }}</x-primary-button>
                        <a href="{{ route('admin.material-out.index') }}" class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded-lg">{{ __('Batal') }}</a>
                        <form action="{{ route('admin.material-out.destroy', $materialOut) }}" method="POST" class="inline" onsubmit="return confirm('Hapus?')">
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
