<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-slate-900 dark:text-white">
                    📦 Detail Produk
                </h2>
                <p class="text-sm text-slate-500 dark:text-slate-400">
                    Informasi lengkap produk & stok
                </p>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('admin.products.edit', $product) }}"
                   class="px-4 py-2 rounded-lg
                          bg-yellow-500 hover:bg-yellow-600
                          text-white font-medium transition">
                    ✏️ Edit
                </a>

                <a href="{{ route('admin.products.index') }}"
                   class="px-4 py-2 rounded-lg
                          bg-slate-200 dark:bg-slate-800
                          text-slate-700 dark:text-slate-300
                          hover:bg-slate-300 dark:hover:bg-slate-700 transition">
                    ← Kembali
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white dark:bg-slate-900
                        border border-slate-200 dark:border-slate-800
                        rounded-2xl shadow-xl p-8 space-y-8">

                {{-- INFO PRODUK --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <div>
                        <h3 class="text-xl font-semibold text-slate-900 dark:text-white">
                            {{ $product->name }}
                        </h3>

                        <p class="text-sm text-slate-500 mt-1">
                            SKU: <span class="font-medium text-slate-700 dark:text-slate-300">
                                {{ $product->sku }}
                            </span>
                        </p>

                        <div class="mt-4 space-y-2 text-sm">
                            <p>
                                <span class="text-slate-500">Satuan:</span>
                                <span class="font-medium text-slate-800 dark:text-slate-200">
                                    {{ $product->unit }}
                                </span>
                            </p>

                            <p>
                                <span class="text-slate-500">Catatan:</span>
                                <span class="font-medium text-slate-800 dark:text-slate-200">
                                    {{ $product->notes ?? '-' }}
                                </span>
                            </p>
                        </div>
                    </div>

                    {{-- STOK --}}
                    <div class="bg-gradient-to-br from-indigo-600 to-indigo-700
                                rounded-2xl p-6 text-white shadow">
                        <p class="text-sm opacity-80">
                            Stok Saat Ini
                        </p>
                        <p class="text-3xl font-bold mt-2">
                            {{ number_format($product->current_stock, 2) }}
                            <span class="text-lg font-medium">{{ $product->unit }}</span>
                        </p>
                    </div>
                </div>

                {{-- ACTION --}}
                <div class="pt-6 border-t border-slate-200 dark:border-slate-800
                            flex justify-end gap-3">

                    <form action="{{ route('admin.products.destroy', $product) }}"
                          method="POST"
                          onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                        @csrf
                        @method('DELETE')

                        <button type="submit"
                            class="px-5 py-2.5 rounded-xl
                                   bg-red-600 hover:bg-red-700
                                   text-white font-semibold transition">
                            🗑️ Hapus
                        </button>
                    </form>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>
