<x-app-layout>
    {{-- HEADER --}}
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                    ➕ Tambah Bahan Baku
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Input data bahan untuk kebutuhan produksi
                </p>
            </div>

            <a href="{{ route('admin.materials.index') }}"
               class="inline-flex items-center gap-2 px-4 py-2 rounded-xl
                      bg-gray-200 dark:bg-gray-800
                      text-gray-700 dark:text-gray-200
                      hover:bg-gray-300 dark:hover:bg-gray-700 transition">
                ← Kembali
            </a>
        </div>
    </x-slot>

    {{-- CONTENT --}}
    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- FORM --}}
                <div class="lg:col-span-2 bg-white dark:bg-gray-900
                            rounded-2xl shadow-xl border border-gray-200 dark:border-gray-800 p-6">

                    <form action="{{ route('admin.materials.store') }}" method="POST" class="space-y-8">
                        @csrf

                        {{-- INFORMASI BAHAN --}}
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">
                                Informasi Bahan
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Nama Bahan *
                                    </label>
                                    <input type="text" name="name" value="{{ old('name') }}" required
                                           class="mt-1 w-full rounded-xl
                                                  bg-gray-100 dark:bg-gray-800
                                                  border border-gray-300 dark:border-gray-700
                                                  text-gray-900 dark:text-white
                                                  focus:ring-2 focus:ring-blue-500">
                                    @error('name')
                                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Kode Bahan
                                    </label>
                                    <input type="text" name="material_code" value="{{ old('material_code') }}"
                                           class="mt-1 w-full rounded-xl
                                                  bg-gray-100 dark:bg-gray-800
                                                  border border-gray-300 dark:border-gray-700
                                                  text-gray-900 dark:text-white">
                                    <p class="text-xs text-gray-500 mt-1">
                                        Kosongkan untuk generate otomatis
                                    </p>
                                </div>
                            </div>
                        </div>

                        {{-- DETAIL --}}
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">
                                Detail & Kategori
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Satuan *
                                    </label>
                                    <select name="unit" required
                                            class="mt-1 w-full rounded-xl
                                                   bg-gray-100 dark:bg-gray-800
                                                   border border-gray-300 dark:border-gray-700
                                                   text-gray-900 dark:text-white">
                                        <option value="">Pilih Satuan</option>
                                        @foreach(['meter','kg','pcs','roll','pack','liter','buah'] as $unit)
                                            <option value="{{ $unit }}" {{ old('unit') == $unit ? 'selected' : '' }}>
                                                {{ ucfirst($unit) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Kategori
                                    </label>
                                    <select name="category"
                                            class="mt-1 w-full rounded-xl
                                                   bg-gray-100 dark:bg-gray-800
                                                   border border-gray-300 dark:border-gray-700
                                                   text-gray-900 dark:text-white">
                                        <option value="">Pilih Kategori</option>
                                        @foreach(['kain','benang','aksesoris','packing','lainnya'] as $cat)
                                            <option value="{{ $cat }}" {{ old('category') == $cat ? 'selected' : '' }}>
                                                {{ ucfirst($cat) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Lokasi
                                    </label>
                                    <input type="text" name="location" value="{{ old('location') }}"
                                           placeholder="Rak A1, Gudang Utama"
                                           class="mt-1 w-full rounded-xl
                                                  bg-gray-100 dark:bg-gray-800
                                                  border border-gray-300 dark:border-gray-700
                                                  text-gray-900 dark:text-white">
                                </div>
                            </div>
                        </div>

                        {{-- STOK --}}
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">
                                Informasi Stok
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Stok Awal *
                                    </label>
                                    <input type="number" step="0.01" min="0"
                                           name="current_stock"
                                           value="{{ old('current_stock', 0) }}"
                                           class="mt-1 w-full rounded-xl
                                                  bg-gray-100 dark:bg-gray-800
                                                  border border-gray-300 dark:border-gray-700
                                                  text-gray-900 dark:text-white">
                                </div>

                                <div>
                                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Minimal Stok *
                                    </label>
                                    <input type="number" step="0.01" min="0"
                                           name="min_stock"
                                           value="{{ old('min_stock', 0) }}"
                                           class="mt-1 w-full rounded-xl
                                                  bg-gray-100 dark:bg-gray-800
                                                  border border-gray-300 dark:border-gray-700
                                                  text-gray-900 dark:text-white">
                                    <p class="text-xs text-gray-500 mt-1">
                                        Alert otomatis jika stok rendah
                                    </p>
                                </div>
                            </div>
                        </div>

                        {{-- CATATAN --}}
                        <div>
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                Catatan
                            </label>
                            <textarea name="notes" rows="3"
                                      class="mt-1 w-full rounded-xl
                                             bg-gray-100 dark:bg-gray-800
                                             border border-gray-300 dark:border-gray-700
                                             text-gray-900 dark:text-white">{{ old('notes') }}</textarea>
                        </div>

                        {{-- ACTION --}}
                        <div class="flex justify-end gap-3 pt-4 border-t dark:border-gray-800">
                            <a href="{{ route('admin.materials.index') }}"
                               class="px-4 py-2 rounded-xl bg-gray-200 dark:bg-gray-800
                                      text-gray-700 dark:text-gray-200">
                                Batal
                            </a>

                            <button type="submit"
                                    class="px-6 py-2 rounded-xl
                                           bg-gradient-to-r from-blue-600 to-indigo-600
                                           text-white font-semibold shadow-lg hover:opacity-90">
                                Simpan Bahan
                            </button>
                        </div>
                    </form>
                </div>

                {{-- SIDEBAR --}}
                <aside class="bg-white dark:bg-gray-900
                              rounded-2xl shadow-xl border border-gray-200 dark:border-gray-800 p-6">
                    <h3 class="font-semibold text-lg text-gray-800 dark:text-white mb-4">
                        Panduan
                    </h3>

                    <ul class="text-sm text-gray-600 dark:text-gray-400 space-y-2 list-disc ml-5">
                        <li>Nama & satuan wajib diisi</li>
                        <li>Kode otomatis jika dikosongkan</li>
                        <li>Minimal stok untuk sistem peringatan</li>
                        <li>Lokasi memudahkan pencarian fisik</li>
                    </ul>

                    <div class="mt-6 p-4 rounded-xl bg-yellow-50 dark:bg-yellow-900/30 text-sm">
                        <strong>Catatan:</strong>
                        <p>Bahan tidak bisa dihapus jika sudah memiliki transaksi.</p>
                    </div>
                </aside>

            </div>
        </div>
    </div>
</x-app-layout>
