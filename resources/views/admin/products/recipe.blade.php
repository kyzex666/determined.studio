<x-app-layout>
    {{-- HEADER --}}
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                    🧾 Atur Resep Produk
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Produk: <span class="font-semibold">{{ $product->name }}</span>
                </p>
            </div>

            <a href="{{ route('admin.products.index') }}"
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

            {{-- INFO --}}
            <div class="mb-6 rounded-2xl border border-blue-200 dark:border-blue-800
                        bg-blue-50 dark:bg-blue-900/30 p-5">
                <p class="text-sm text-blue-700 dark:text-blue-300 leading-relaxed">
                    <strong>Cara Pengisian:</strong><br>
                    Masukkan jumlah kebutuhan bahan untuk membuat
                    <strong>1 Unit {{ $product->name }}</strong>.
                    Isi <strong>0</strong> jika bahan tidak digunakan.
                </p>
            </div>

            {{-- CARD --}}
            <div class="bg-white dark:bg-gray-900
                        rounded-2xl shadow-xl
                        border border-gray-200 dark:border-gray-800 p-6">

                <form action="{{ route('admin.products.recipe.update', $product->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- TABLE --}}
                    <div class="overflow-x-auto rounded-xl border border-gray-200 dark:border-gray-800">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-800">
                            <thead class="bg-gray-100 dark:bg-gray-800">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-semibold
                                               text-gray-600 dark:text-gray-300 uppercase">
                                        Nama Bahan
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold
                                               text-gray-600 dark:text-gray-300 uppercase">
                                        Satuan
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold
                                               text-gray-600 dark:text-gray-300 uppercase">
                                        Kebutuhan / Unit
                                    </th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                                @foreach($materials as $material)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/40 transition">
                                        <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $material->name }}
                                        </td>

                                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                            {{ $material->unit ?? 'Pcs' }}
                                        </td>

                                        <td class="px-6 py-4">
                                            <input type="number"
                                                   step="0.01"
                                                   min="0"
                                                   name="recipes[{{ $material->id }}]"
                                                   value="{{ $existingRecipe[$material->id] ?? 0 }}"
                                                   class="w-32 rounded-xl
                                                          bg-gray-100 dark:bg-gray-800
                                                          border border-gray-300 dark:border-gray-700
                                                          text-gray-900 dark:text-white
                                                          focus:ring-2 focus:ring-blue-500">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- ACTION --}}
                    <div class="flex justify-end gap-3 mt-6 pt-4 border-t dark:border-gray-800">
                        <a href="{{ route('admin.products.index') }}"
                           class="px-4 py-2 rounded-xl
                                  bg-gray-200 dark:bg-gray-800
                                  text-gray-700 dark:text-gray-200">
                            Batal
                        </a>

                        <button type="submit"
                                class="px-6 py-2 rounded-xl
                                       bg-gradient-to-r from-blue-600 to-indigo-600
                                       text-white font-semibold
                                       shadow-lg hover:opacity-90">
                            💾 Simpan Resep
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
