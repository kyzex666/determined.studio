<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                    ✏️ Edit Bahan Baku
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Perbarui informasi dan stok bahan baku
                </p>
            </div>

            <a href="{{ route('admin.materials.index') }}"
               class="px-4 py-2 rounded-lg
                      bg-gray-200 dark:bg-gray-800
                      text-gray-700 dark:text-gray-300
                      hover:bg-gray-300 dark:hover:bg-gray-700 transition">
                ← Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">

                {{-- FORM --}}
                <div class="lg:col-span-3 rounded-2xl
                            bg-white dark:bg-gray-900
                            border border-gray-200 dark:border-gray-800
                            shadow-xl">

                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-800">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            Informasi Bahan
                        </h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            Data utama & pengaturan stok
                        </p>
                    </div>

                    <form action="{{ route('admin.materials.update', $material) }}"
                          method="POST"
                          class="p-6 space-y-8">
                        @csrf
                        @method('PUT')

                        {{-- DATA DASAR --}}
                        <div>
                            <p class="text-xs uppercase tracking-widest
                                      text-gray-500 dark:text-gray-400 mb-4">
                                Data Dasar
                            </p>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="text-sm text-gray-600 dark:text-gray-300">
                                        Nama Bahan <span class="text-red-500">*</span>
                                    </label>
                                    <input name="name"
                                           value="{{ old('name', $material->name) }}"
                                           required
                                           class="mt-2 w-full rounded-lg
                                                  bg-gray-100 dark:bg-gray-800
                                                  border border-gray-300 dark:border-gray-700
                                                  text-gray-900 dark:text-white
                                                  focus:ring-2 focus:ring-indigo-500 px-4 py-2.5">
                                </div>

                                <div>
                                    <label class="text-sm text-gray-600 dark:text-gray-300">
                                        Kode Bahan
                                    </label>
                                    <input name="material_code"
                                           value="{{ old('material_code', $material->material_code) }}"
                                           class="mt-2 w-full rounded-lg
                                                  bg-gray-100 dark:bg-gray-800
                                                  border border-gray-300 dark:border-gray-700
                                                  text-gray-900 dark:text-white
                                                  focus:ring-2 focus:ring-indigo-500 px-4 py-2.5">
                                    <p class="text-xs text-gray-500 mt-1">
                                        Kosongkan jika ingin dibuat otomatis
                                    </p>
                                </div>
                            </div>
                        </div>

                        {{-- ATRIBUT --}}
                        <div>
                            <p class="text-xs uppercase tracking-widest
                                      text-gray-500 dark:text-gray-400 mb-4">
                                Atribut Bahan
                            </p>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <label class="text-sm text-gray-600 dark:text-gray-300">
                                        Satuan <span class="text-red-500">*</span>
                                    </label>
                                    <select name="unit" required
                                            class="mt-2 w-full rounded-lg
                                                   bg-gray-100 dark:bg-gray-800
                                                   border border-gray-300 dark:border-gray-700
                                                   text-gray-900 dark:text-white
                                                   focus:ring-2 focus:ring-indigo-500 px-4 py-2.5">
                                        <option value="">Pilih satuan</option>
                                        @foreach(['meter','kg','pcs','roll','pack','liter','buah'] as $unit)
                                            <option value="{{ $unit }}" {{ old('unit', $material->unit) == $unit ? 'selected' : '' }}>
                                                {{ ucfirst($unit) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="text-sm text-gray-600 dark:text-gray-300">
                                        Kategori
                                    </label>
                                    <select name="category"
                                            class="mt-2 w-full rounded-lg
                                                   bg-gray-100 dark:bg-gray-800
                                                   border border-gray-300 dark:border-gray-700
                                                   text-gray-900 dark:text-white
                                                   focus:ring-2 focus:ring-indigo-500 px-4 py-2.5">
                                        <option value="">Pilih kategori</option>
                                        @foreach(['kain','benang','aksesoris','packing','lainnya'] as $cat)
                                            <option value="{{ $cat }}" {{ old('category', $material->category) == $cat ? 'selected' : '' }}>
                                                {{ ucfirst($cat) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="text-sm text-gray-600 dark:text-gray-300">
                                        Lokasi Penyimpanan
                                    </label>
                                    <input name="location"
                                           value="{{ old('location', $material->location) }}"
                                           placeholder="Rak A1 / Gudang Utama"
                                           class="mt-2 w-full rounded-lg
                                                  bg-gray-100 dark:bg-gray-800
                                                  border border-gray-300 dark:border-gray-700
                                                  text-gray-900 dark:text-white
                                                  focus:ring-2 focus:ring-indigo-500 px-4 py-2.5">
                                </div>
                            </div>
                        </div>

                        {{-- STOK --}}
                        <div>
                            <p class="text-xs uppercase tracking-widest
                                      text-gray-500 dark:text-gray-400 mb-4">
                                Pengaturan Stok
                            </p>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="text-sm text-gray-600 dark:text-gray-300">
                                        Stok Saat Ini
                                    </label>
                                    <input type="number" step="0.01" min="0"
                                           name="current_stock"
                                           value="{{ old('current_stock', $material->current_stock) }}"
                                           required
                                           class="mt-2 w-full rounded-lg
                                                  bg-gray-100 dark:bg-gray-800
                                                  border border-gray-300 dark:border-gray-700
                                                  text-gray-900 dark:text-white
                                                  focus:ring-2 focus:ring-indigo-500 px-4 py-2.5">
                                </div>

                                <div>
                                    <label class="text-sm text-gray-600 dark:text-gray-300">
                                        Minimal Stok
                                    </label>
                                    <input type="number" step="0.01" min="0"
                                           name="min_stock"
                                           value="{{ old('min_stock', $material->min_stock) }}"
                                           required
                                           class="mt-2 w-full rounded-lg
                                                  bg-gray-100 dark:bg-gray-800
                                                  border border-gray-300 dark:border-gray-700
                                                  text-gray-900 dark:text-white
                                                  focus:ring-2 focus:ring-indigo-500 px-4 py-2.5">
                                    <p class="text-xs text-gray-500 mt-1">
                                        Sistem akan memberi peringatan otomatis
                                    </p>
                                </div>
                            </div>
                        </div>

                        {{-- CATATAN --}}
                        <div>
                            <label class="text-sm text-gray-600 dark:text-gray-300">
                                Catatan
                            </label>
                            <textarea name="notes" rows="3"
                                      class="mt-2 w-full rounded-lg
                                             bg-gray-100 dark:bg-gray-800
                                             border border-gray-300 dark:border-gray-700
                                             text-gray-900 dark:text-white
                                             focus:ring-2 focus:ring-indigo-500 px-4 py-2.5">{{ old('notes', $material->notes) }}</textarea>
                        </div>

                        {{-- ACTION --}}
                        <div class="pt-6 border-t border-gray-200 dark:border-gray-800
                                    flex justify-end">
                            <button
                                class="px-6 py-2.5 rounded-xl
                                       bg-indigo-600 hover:bg-indigo-500
                                       text-white font-semibold transition">
                                💾 Update Bahan
                            </button>
                        </div>

                    </form>
                </div>

                {{-- SIDEBAR --}}
                <aside class="rounded-2xl
                             bg-white dark:bg-gray-900
                             border border-gray-200 dark:border-gray-800
                             shadow-xl p-6 space-y-6">

                    <div>
                        <h4 class="font-semibold text-gray-900 dark:text-white mb-2">
                            Panduan
                        </h4>
                        <ul class="text-sm text-gray-600 dark:text-gray-400 space-y-2">
                            <li>• Perbarui stok sesuai kondisi fisik</li>
                            <li>• Minimal stok digunakan untuk alert</li>
                            <li>• Lokasi membantu pencarian cepat</li>
                        </ul>
                    </div>

                    <div class="rounded-lg p-4
                                bg-yellow-100 dark:bg-yellow-900/30
                                border border-yellow-300 dark:border-yellow-700">
                        <p class="text-sm font-semibold text-yellow-800 dark:text-yellow-400 mb-1">
                            ⚠️ Perhatian
                        </p>
                        <p class="text-sm text-yellow-700 dark:text-yellow-300">
                            Bahan tidak dapat dihapus jika sudah memiliki
                            transaksi masuk atau keluar.
                        </p>
                    </div>

                </aside>

            </div>
        </div>
    </div>
</x-app-layout>
