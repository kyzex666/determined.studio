<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Buat Production Order') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- MAIN FORM -->
                <div class="lg:col-span-2 bg-white rounded-lg shadow p-6">
                    <form action="{{ route('admin.production-orders.store') }}" method="POST">
                        @csrf

                        <!-- Produk + Qty -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <x-input-label for="product_id" :value="__('Produk')" />
                                <select id="product_id" name="product_id"
                                    class="mt-1 block w-full border-gray-300 rounded-md" required>
                                    <option value="">Pilih Produk</option>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}"
                                            {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                            {{ $product->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('product_id')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="target_qty" :value="__('Target Produksi')" />
                                <x-text-input id="target_qty" name="target_qty" type="number" min="1"
                                    value="{{ old('target_qty') }}"
                                    class="mt-1 block w-full" required />
                                <x-input-error :messages="$errors->get('target_qty')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Due date -->
                        <div class="mt-4">
                            <x-input-label for="due_date" :value="__('Tanggal Selesai (Deadline)')" />
                            <x-text-input id="due_date" name="due_date" type="date"
                                value="{{ old('due_date') }}"
                                class="mt-1 block w-full" required />
                            <x-input-error :messages="$errors->get('due_date')" class="mt-2" />
                        </div>

                        <!-- Notes -->
                        <div class="mt-4">
                            <x-input-label for="notes" :value="__('Catatan')" />
                            <textarea id="notes" name="notes" rows="3"
                                class="mt-1 block w-full border-gray-300 rounded-md">{{ old('notes') }}</textarea>
                            <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                        </div>

                        <div class="flex justify-between items-center mt-6">
                            <a href="{{ route('admin.production-orders.index') }}"
                                class="inline-flex items-center px-4 py-2 bg-gray-200 rounded-md text-gray-700">
                                ← Kembali
                            </a>

                            <x-primary-button> Simpan Production Order </x-primary-button>
                        </div>
                    </form>
                </div>

                <!-- SIDEBAR -->
                <aside class="bg-white rounded-lg shadow p-6">
                    <h3 class="font-semibold mb-2">Petunjuk Pengisian</h3>

                    <div class="text-sm text-gray-700 space-y-3">

                        <div>
                            <strong>Informasi penting:</strong>
                            <ul class="list-disc ml-5 text-gray-600">
                                <li>Pilih produk yang akan diproduksi</li>
                                <li>Masukkan jumlah target produksi</li>
                                <li>Deadline digunakan untuk penjadwalan produksi</li>
                            </ul>
                        </div>

                        <div class="bg-yellow-50 p-3 rounded">
                            <strong>Perhatian:</strong>
                            <p class="text-sm text-gray-700">
                                Setelah Production Order dimulai, beberapa data tidak bisa diubah.
                            </p>
                        </div>

                        <div class="bg-blue-50 p-3 rounded">
                            <strong>Catatan:</strong>
                            <p class="text-sm text-gray-700">
                                Sistem otomatis menghitung kebutuhan bahan berdasarkan BOM produk.
                            </p>
                        </div>
                    </div>
                </aside>

            </div>

        </div>
    </div>
</x-app-layout>
