<x-app-layout>

    {{-- HEADER --}}
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold text-slate-900 dark:text-slate-100">
                    Tambah Vendor
                </h2>
                <p class="text-sm text-slate-500 dark:text-slate-400">
                    Manajemen Vendor · Data Master
                </p>
            </div>

            <a href="{{ route('admin.vendors.index') }}"
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

            {{-- HERO (TENANG & NYATU) --}}
            <div class="mb-8 rounded-xl p-6
                bg-slate-900 text-slate-100
                border border-slate-800">

                <h3 class="text-2xl font-semibold">
                    Setup Vendor
                </h3>
                <p class="mt-1 text-sm text-slate-400 max-w-2xl">
                    Digunakan untuk mencatat data pemasok bahan baku yang terhubung
                    dengan transaksi material masuk dan keluar gudang.
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">

                {{-- FORM --}}
                <div class="lg:col-span-3 rounded-xl
                    bg-slate-900 border border-slate-800">

                    <div class="px-6 py-4 border-b border-slate-800">
                        <h4 class="text-lg font-semibold text-slate-100">
                            Informasi Vendor
                        </h4>
                        <p class="text-sm text-slate-400">
                            Identitas utama dan kontak vendor
                        </p>
                    </div>

                    <form action="{{ route('admin.vendors.store') }}"
                          method="POST"
                          class="p-6 space-y-8">
                        @csrf

                        {{-- DATA DASAR --}}
                        <div>
                            <p class="text-xs uppercase tracking-widest text-slate-500 mb-4">
                                Data Dasar
                            </p>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="text-sm text-slate-300">
                                        Nama Vendor
                                    </label>
                                    <input
                                        name="name"
                                        required
                                        value="{{ old('name') }}"
                                        class="mt-2 w-full rounded-lg
                                        bg-slate-800 border border-slate-700
                                        text-slate-100
                                        focus:ring-2 focus:ring-indigo-500
                                        focus:outline-none px-4 py-2.5">
                                </div>

                                <div>
                                    <label class="text-sm text-slate-300">
                                        Kode Vendor
                                    </label>
                                    <input
                                        name="vendor_code"
                                        value="{{ old('vendor_code') }}"
                                        class="mt-2 w-full rounded-lg
                                        bg-slate-800 border border-slate-700
                                        text-slate-100
                                        focus:ring-2 focus:ring-indigo-500
                                        focus:outline-none px-4 py-2.5">
                                    <p class="text-xs text-slate-500 mt-1">
                                        Kosongkan jika ingin dibuat otomatis
                                    </p>
                                </div>
                            </div>
                        </div>

                        {{-- KONTAK --}}
                        <div>
                            <p class="text-xs uppercase tracking-widest text-slate-500 mb-4">
                                Kontak Vendor
                            </p>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="text-sm text-slate-300">
                                        Nomor Telepon
                                    </label>
                                    <input
                                        name="phone"
                                        value="{{ old('phone') }}"
                                        class="mt-2 w-full rounded-lg
                                        bg-slate-800 border border-slate-700
                                        text-slate-100
                                        focus:ring-2 focus:ring-indigo-500
                                        focus:outline-none px-4 py-2.5">
                                </div>

                                <div>
                                    <label class="text-sm text-slate-300">
                                        Email
                                    </label>
                                    <input
                                        type="email"
                                        name="email"
                                        value="{{ old('email') }}"
                                        class="mt-2 w-full rounded-lg
                                        bg-slate-800 border border-slate-700
                                        text-slate-100
                                        focus:ring-2 focus:ring-indigo-500
                                        focus:outline-none px-4 py-2.5">
                                </div>
                            </div>
                        </div>

                        {{-- TAMBAHAN --}}
                        <div>
                            <p class="text-xs uppercase tracking-widest text-slate-500 mb-4">
                                Informasi Tambahan
                            </p>

                            <div class="space-y-6">
                                <div>
                                    <label class="text-sm text-slate-300">
                                        Alamat
                                    </label>
                                    <textarea
                                        name="address"
                                        rows="3"
                                        class="mt-2 w-full rounded-lg
                                        bg-slate-800 border border-slate-700
                                        text-slate-100
                                        focus:ring-2 focus:ring-indigo-500
                                        focus:outline-none px-4 py-2.5">{{ old('address') }}</textarea>
                                </div>

                                <div>
                                    <label class="text-sm text-slate-300">
                                        Catatan
                                    </label>
                                    <textarea
                                        name="notes"
                                        rows="2"
                                        class="mt-2 w-full rounded-lg
                                        bg-slate-800 border border-slate-700
                                        text-slate-100
                                        focus:ring-2 focus:ring-indigo-500
                                        focus:outline-none px-4 py-2.5">{{ old('notes') }}</textarea>
                                </div>
                            </div>
                        </div>

                        {{-- ACTION --}}
                        <div class="pt-6 border-t border-slate-800 flex justify-end">
                            <button
                                class="px-6 py-2.5 rounded-lg
                                bg-indigo-600 hover:bg-indigo-500
                                text-white font-semibold transition">
                                Simpan Vendor
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
                            <li>• Nama vendor wajib diisi</li>
                            <li>• Kode vendor harus unik</li>
                            <li>• Email digunakan untuk notifikasi</li>
                        </ul>
                    </div>

                    <div class="rounded-lg p-4
                        bg-amber-900/30 border border-amber-700">
                        <p class="text-sm font-semibold text-amber-400 mb-1">
                            Catatan Penting
                        </p>
                        <p class="text-sm text-amber-300">
                            Vendor tidak dapat dihapus jika sudah memiliki
                            transaksi material masuk.
                        </p>
                    </div>

                </aside>

            </div>
        </div>
    </div>

</x-app-layout>
