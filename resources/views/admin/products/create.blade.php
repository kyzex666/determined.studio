<x-app-layout>

    {{-- HEADER --}}
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold text-slate-900 dark:text-slate-100">
                    Tambah Produk
                </h2>
                <p class="text-sm text-slate-500 dark:text-slate-400">
                    Manajemen Produk · Data Master
                </p>
            </div>

            <a href="{{ route('admin.products.index') }}"
               class="px-4 py-2 rounded-lg
               bg-slate-800 text-slate-100
               hover:bg-slate-700 transition text-sm">
                ← Kembali
            </a>
        </div>
    </x-slot>

    {{-- CONTENT --}}
    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- HERO --}}
            <div class="mb-8 rounded-xl p-6
                bg-slate-900 text-slate-100
                border border-slate-800">

                <h3 class="text-2xl font-semibold">
                    Setup Produk
                </h3>
                <p class="mt-1 text-sm text-slate-400 max-w-2xl">
                    Digunakan untuk mendata produk jadi yang akan diproduksi
                    dan dikelola stoknya oleh sistem.
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">

                {{-- FORM --}}
                <div class="lg:col-span-3 rounded-xl
                    bg-slate-900 border border-slate-800">

                    <div class="px-6 py-4 border-b border-slate-800">
                        <h4 class="text-lg font-semibold text-slate-100">
                            Informasi Produk
                        </h4>
                        <p class="text-sm text-slate-400">
                            Identitas utama produk jadi
                        </p>
                    </div>

                    <form action="{{ route('admin.products.store') }}"
                          method="POST"
                          class="p-6 space-y-8">
                        @csrf

                        {{-- DATA DASAR --}}
                        <div>
                            <p class="text-xs uppercase tracking-widest text-slate-500 mb-4">
                                Data Dasar
                            </p>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                                {{-- NAMA --}}
                                <div>
                                    <label class="text-sm text-slate-300">
                                        Nama Produk
                                    </label>
                                    <input
                                        name="name"
                                        required
                                        value="{{ old('name') }}"
                                        placeholder="Contoh: Kaos Polos Hitam"
                                        class="mt-2 w-full rounded-lg
                                        bg-slate-800 border border-slate-700
                                        text-slate-100
                                        focus:ring-2 focus:ring-indigo-500
                                        focus:outline-none px-4 py-2.5">
                                </div>

                                {{-- SKU --}}
                                <div>
                                    <label class="text-sm text-slate-300">
                                        SKU Produk
                                    </label>
                                    <input
                                        name="sku"
                                        value="{{ old('sku') }}"
                                        readonly
                                        placeholder="Dibuat otomatis oleh sistem"
                                        class="mt-2 w-full rounded-lg
                                        bg-slate-800 border border-slate-700
                                        text-slate-400 cursor-not-allowed
                                        px-4 py-2.5">
                                    <p class="text-xs text-slate-500 mt-1">
                                        SKU akan dibuat otomatis saat produk disimpan
                                    </p>
                                </div>

                            </div>
                        </div>

                        {{-- SATUAN --}}
                        <div>
                            <p class="text-xs uppercase tracking-widest text-slate-500 mb-4">
                                Satuan Produk
                            </p>

                            <select name="unit"
                                    required
                                    class="w-full rounded-lg
                                    bg-slate-800 border border-slate-700
                                    text-slate-100
                                    focus:ring-2 focus:ring-indigo-500
                                    focus:outline-none px-4 py-2.5">
                                <option value="">Pilih satuan</option>
                                <option value="pcs">Pcs</option>
                                <option value="box">Box</option>
                                <option value="pack">Pack</option>
                                <option value="dozen">Lusin</option>
                            </select>
                        </div>

                        {{-- CATATAN --}}
                        <div>
                            <p class="text-xs uppercase tracking-widest text-slate-500 mb-4">
                                Informasi Tambahan
                            </p>

                            <textarea
                                name="notes"
                                rows="3"
                                class="w-full rounded-lg
                                bg-slate-800 border border-slate-700
                                text-slate-100
                                focus:ring-2 focus:ring-indigo-500
                                focus:outline-none px-4 py-2.5"
                                placeholder="Catatan opsional">{{ old('notes') }}</textarea>
                        </div>

                        {{-- ACTION --}}
                        <div class="pt-6 border-t border-slate-800 flex justify-end">
                            <button
                                class="px-6 py-2.5 rounded-lg
                                bg-indigo-600 hover:bg-indigo-500
                                text-white font-semibold transition">
                                Simpan Produk
                            </button>
                        </div>

                    </form>
                </div>

                {{-- SIDEBAR --}}
                <aside class="rounded-xl bg-slate-900 border border-slate-800 p-6 space-y-6">

                    <div>
                        <h5 class="font-semibold text-slate-100 mb-2">
                            Panduan
                        </h5>
                        <ul class="text-sm text-slate-400 space-y-2">
                            <li>• Nama produk wajib diisi</li>
                            <li>• SKU dibuat otomatis</li>
                            <li>• Satuan berpengaruh ke stok</li>
                        </ul>
                    </div>

                    <div class="rounded-lg p-4
                        bg-amber-900/30 border border-amber-700">
                        <p class="text-sm font-semibold text-amber-400 mb-1">
                            Catatan Penting
                        </p>
                        <p class="text-sm text-amber-300">
                            Produk tidak dapat dihapus jika sudah memiliki
                            transaksi produksi atau resep.
                        </p>
                    </div>

                </aside>

            </div>
        </div>
    </div>

</x-app-layout>
