<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                    🧱 Detail Bahan
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Informasi stok & spesifikasi bahan baku
                </p>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('admin.materials.edit', $material) }}"
                   class="px-4 py-2 rounded-lg
                          bg-yellow-500 hover:bg-yellow-600
                          text-white font-semibold transition">
                    ✏️ Edit
                </a>

                <a href="{{ route('admin.materials.index') }}"
                   class="px-4 py-2 rounded-lg
                          bg-gray-200 dark:bg-gray-800
                          text-gray-700 dark:text-gray-300
                          hover:bg-gray-300 dark:hover:bg-gray-700 transition">
                    ← Kembali
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white dark:bg-gray-900
                        border border-gray-200 dark:border-gray-800
                        rounded-2xl shadow-xl p-8 space-y-8">

                {{-- HEADER INFO --}}
                <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-6">

                    <div>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white">
                            {{ $material->name }}
                        </h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                            Kode Bahan:
                            <span class="font-semibold text-indigo-600 dark:text-indigo-400">
                                {{ $material->material_code }}
                            </span>
                        </p>
                    </div>

                    <div class="flex gap-3">
                        <span class="px-4 py-2 rounded-xl text-sm font-semibold
                                     bg-indigo-100 dark:bg-indigo-900
                                     text-indigo-700 dark:text-indigo-300">
                            {{ $material->category ?? 'Tanpa Kategori' }}
                        </span>

                        <span class="px-4 py-2 rounded-xl text-sm font-semibold
                                     bg-slate-100 dark:bg-slate-800
                                     text-slate-700 dark:text-slate-300">
                            {{ $material->unit }}
                        </span>
                    </div>

                </div>

                {{-- INFO GRID --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    {{-- INFO BAHAN --}}
                    <div class="rounded-xl p-6
                                bg-gray-50 dark:bg-gray-800
                                border border-gray-200 dark:border-gray-700">

                        <p class="text-xs uppercase tracking-widest
                                  text-gray-500 dark:text-gray-400 mb-4">
                            Informasi Bahan
                        </p>

                        <div class="space-y-3 text-sm">
                            <p class="text-gray-700 dark:text-gray-300">
                                <span class="text-gray-500 dark:text-gray-400">Kategori:</span>
                                {{ $material->category ?? '-' }}
                            </p>

                            <p class="text-gray-700 dark:text-gray-300">
                                <span class="text-gray-500 dark:text-gray-400">Satuan:</span>
                                {{ $material->unit }}
                            </p>

                            <p class="text-gray-700 dark:text-gray-300">
                                <span class="text-gray-500 dark:text-gray-400">Lokasi:</span>
                                {{ $material->location ?? '-' }}
                            </p>
                        </div>
                    </div>

                    {{-- STOK --}}
                    <div class="rounded-xl p-6
                                bg-gray-50 dark:bg-gray-800
                                border border-gray-200 dark:border-gray-700">

                        <p class="text-xs uppercase tracking-widest
                                  text-gray-500 dark:text-gray-400 mb-4">
                            Informasi Stok
                        </p>

                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    Stok Saat Ini
                                </p>
                                <p class="text-3xl font-bold text-gray-900 dark:text-white">
                                    {{ number_format($material->current_stock, 2) }}
                                    <span class="text-sm font-medium text-gray-500">
                                        {{ $material->unit }}
                                    </span>
                                </p>
                            </div>

                            <div class="text-right">
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    Minimal
                                </p>
                                <p class="text-xl font-semibold text-red-600 dark:text-red-400">
                                    {{ number_format($material->min_stock, 2) }}
                                </p>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- CATATAN --}}
                <div class="rounded-xl p-6
                            bg-gray-50 dark:bg-gray-800
                            border border-gray-200 dark:border-gray-700">

                    <p class="text-xs uppercase tracking-widest
                              text-gray-500 dark:text-gray-400 mb-3">
                        Catatan
                    </p>

                    <p class="text-sm text-gray-700 dark:text-gray-300">
                        {{ $material->notes ?? 'Tidak ada catatan' }}
                    </p>
                </div>

                {{-- ACTION --}}
                <div class="pt-6 border-t border-gray-200 dark:border-gray-800
                            flex justify-end">

                    <form action="{{ route('admin.materials.destroy', $material) }}"
                          method="POST"
                          onsubmit="return confirm('Yakin ingin menghapus bahan ini?')">
                        @csrf
                        @method('DELETE')

                        <button type="submit"
                            class="px-5 py-2.5 rounded-xl
                                   bg-red-600 hover:bg-red-700
                                   text-white font-semibold transition
                                   disabled:opacity-50 disabled:cursor-not-allowed"
                            {{ $material->hasTransactions() ? 'disabled' : '' }}>
                            🗑️ Hapus Bahan
                        </button>
                    </form>

                </div>

            </div>

        </div>
    </div>
</x-app-layout>
