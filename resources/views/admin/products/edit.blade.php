<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-2xl font-bold text-slate-900 dark:text-white">
                ✏️ Edit Produk
            </h2>
            <p class="text-sm text-slate-500 dark:text-slate-400">
                Perbarui informasi produk
            </p>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- FORM --}}
                <div class="lg:col-span-2
                            bg-white dark:bg-slate-900
                            border border-slate-200 dark:border-slate-800
                            rounded-2xl shadow-xl p-8">

                    <form action="{{ route('admin.products.update', $product) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="name" :value="__('Nama Produk')" />
                                <x-text-input id="name" name="name"
                                    value="{{ old('name', $product->name) }}"
                                    required
                                    class="mt-1 block w-full
                                           bg-gray-100 dark:bg-slate-800
                                           border-slate-300 dark:border-slate-700
                                           text-slate-900 dark:text-white" />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="sku" :value="__('SKU')" />
                                <x-text-input id="sku" name="sku"
                                    value="{{ old('sku', $product->sku) }}"
                                    required
                                    class="mt-1 block w-full
                                           bg-gray-100 dark:bg-slate-800
                                           border-slate-300 dark:border-slate-700
                                           text-slate-900 dark:text-white" />
                                <x-input-error :messages="$errors->get('sku')" class="mt-2" />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="current_stock" :value="__('Stok Saat Ini')" />
                                <x-text-input id="current_stock"
                                    type="number"
                                    step="0.01"
                                    min="0"
                                    name="current_stock"
                                    value="{{ old('current_stock', $product->current_stock) }}"
                                    required
                                    class="mt-1 block w-full
                                           bg-gray-100 dark:bg-slate-800
                                           border-slate-300 dark:border-slate-700
                                           text-slate-900 dark:text-white" />
                                <x-input-error :messages="$errors->get('current_stock')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="unit" :value="__('Satuan')" />
                                <select id="unit" name="unit" required
                                    class="mt-1 block w-full rounded-xl px-4 py-3
                                           bg-gray-100 dark:bg-slate-800
                                           border border-slate-300 dark:border-slate-700
                                           text-slate-900 dark:text-white">
                                    <option value="">Pilih Satuan</option>
                                    <option value="pcs" {{ old('unit', $product->unit) == 'pcs' ? 'selected' : '' }}>Pieces</option>
                                    <option value="box" {{ old('unit', $product->unit) == 'box' ? 'selected' : '' }}>Box</option>
                                    <option value="pack" {{ old('unit', $product->unit) == 'pack' ? 'selected' : '' }}>Pack</option>
                                    <option value="dozen" {{ old('unit', $product->unit) == 'dozen' ? 'selected' : '' }}>Dozen</option>
                                </select>
                                <x-input-error :messages="$errors->get('unit')" class="mt-2" />
                            </div>
                        </div>

                        <div>
                            <x-input-label for="notes" :value="__('Catatan')" />
                            <textarea id="notes" name="notes" rows="3"
                                class="mt-1 block w-full rounded-xl px-4 py-3
                                       bg-gray-100 dark:bg-slate-800
                                       border border-slate-300 dark:border-slate-700
                                       text-slate-900 dark:text-white">{{ old('notes', $product->notes) }}</textarea>
                            <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                        </div>

                        <div class="pt-6 border-t border-slate-200 dark:border-slate-800
                                    flex justify-between items-center">

                            <a href="{{ route('admin.products.index') }}"
                               class="px-5 py-2.5 rounded-xl
                                      bg-slate-200 dark:bg-slate-800
                                      text-slate-700 dark:text-slate-300
                                      hover:bg-slate-300 dark:hover:bg-slate-700 transition">
                                ← Kembali
                            </a>

                            <button type="submit"
                                class="px-6 py-2.5 rounded-xl
                                       bg-gradient-to-r from-blue-600 to-indigo-600
                                       text-white font-semibold shadow-lg hover:opacity-90 transition">
                                💾 Update Produk
                            </button>
                        </div>

                    </form>
                </div>

                {{-- SIDEBAR --}}
                <aside class="bg-white dark:bg-slate-900
                              border border-slate-200 dark:border-slate-800
                              rounded-2xl shadow-xl p-6 space-y-4">

                    <h3 class="font-semibold text-slate-900 dark:text-white">
                        📌 Petunjuk
                    </h3>

                    <p class="text-sm text-slate-600 dark:text-slate-400">
                        Perbarui informasi produk sesuai kebutuhan. Stok biasanya
                        berubah melalui proses produksi & gudang.
                    </p>

                    <div class="bg-yellow-500/10 border border-yellow-500/30
                                text-yellow-400 rounded-xl p-4 text-sm">
                        <strong>Perhatian:</strong>
                        <p class="mt-1">
                            Produk tidak dapat dihapus jika sudah memiliki transaksi produksi.
                        </p>
                    </div>
                </aside>

            </div>
        </div>
    </div>
</x-app-layout>
