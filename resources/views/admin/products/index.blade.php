<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                    📦 Data Produk
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Manajemen stok hasil produksi
                </p>
            </div>

            <a href="{{ route('admin.products.create') }}"
               class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl
                      bg-gradient-to-r from-blue-600 to-indigo-600
                      text-white font-semibold shadow-lg hover:opacity-90 transition">
                ➕ Tambah Produk
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- SEARCH BAR -->
            <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-800 p-6">
                <div class="flex flex-col md:flex-row gap-3">
                    <input id="searchInput"
                           type="text"
                           placeholder="Cari produk / SKU..."
                           class="flex-1 px-4 py-3 rounded-xl
                                  bg-gray-100 dark:bg-gray-800
                                  border border-gray-300 dark:border-gray-700
                                  text-gray-900 dark:text-white
                                  focus:ring-2 focus:ring-blue-500">

                    <button id="resetButton"
                            class="px-4 py-3 rounded-xl bg-red-500 hover:bg-red-600
                                   text-white font-semibold transition">
                        Reset
                    </button>
                </div>
            </div>

            <!-- TABLE -->
            <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-xl overflow-hidden
                        border border-gray-200 dark:border-gray-800">

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-100 dark:bg-gray-800">
                            <tr class="text-left text-xs uppercase tracking-wider
                                       text-gray-600 dark:text-gray-400">
                                <th class="px-6 py-4">#</th>
                                <th class="px-6 py-4">SKU</th>
                                <th class="px-6 py-4">Nama Produk</th>
                                <th class="px-6 py-4">Stok</th>
                                <th class="px-6 py-4">Satuan</th>
                                <th class="px-6 py-4 text-center">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                            @foreach($products as $product)
                                <tr class="hover:bg-blue-50 dark:hover:bg-gray-800 transition">

                                    <td class="px-6 py-4 text-gray-700 dark:text-gray-300">
                                        {{ $loop->iteration }}
                                    </td>

                                    <td class="px-6 py-4">
                                        <span class="px-3 py-1 rounded-lg text-xs font-semibold
                                                     bg-blue-100 dark:bg-blue-900
                                                     text-blue-800 dark:text-blue-300">
                                            {{ $product->sku }}
                                        </span>
                                    </td>

                                    <td class="px-6 py-4">
                                        <div class="font-semibold text-gray-900 dark:text-white">
                                            {{ $product->name }}
                                        </div>
                                    </td>

                                    <td class="px-6 py-4">
                                        @php
                                            $stockClass = $product->current_stock <= 0
                                                ? 'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300'
                                                : ($product->current_stock < 10
                                                    ? 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900 dark:text-yellow-300'
                                                    : 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300');
                                        @endphp

                                        <span class="px-3 py-1 rounded-lg text-xs font-bold {{ $stockClass }}">
                                            {{ number_format($product->current_stock, 2) }}
                                        </span>
                                    </td>

                                    <td class="px-6 py-4 text-gray-700 dark:text-gray-300">
                                        {{ $product->unit }}
                                    </td>

                                    <td class="px-6 py-4">
                                        <div class="flex justify-center gap-4 text-lg">

                                            <a href="{{ route('admin.products.show', $product) }}"
                                               class="text-blue-500 hover:text-blue-700"
                                               title="Detail">
                                                👁
                                            </a>

                                            <a href="{{ route('admin.products.edit', $product) }}"
                                               class="text-yellow-500 hover:text-yellow-600"
                                               title="Edit">
                                                ✏️
                                            </a>

                                            <a href="{{ route('admin.products.recipe.edit', $product->id) }}"
                                               class="text-indigo-500 hover:text-indigo-700"
                                               title="Atur Resep">
                                                ⚙️
                                            </a>

                                            <form action="{{ route('admin.products.destroy', $product) }}"
                                                  method="POST"
                                                  onsubmit="return confirm('Yakin hapus produk ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="text-red-500 hover:text-red-700"
                                                        title="Hapus">
                                                    🗑️
                                                </button>
                                            </form>

                                        </div>
                                    </td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($products->isEmpty())
                    <div class="py-10 text-center text-gray-500 dark:text-gray-400">
                        Belum ada data produk
                    </div>
                @endif

            </div>

        </div>
    </div>

    @push('scripts')
    <script>
        const searchInput = document.getElementById('searchInput');
        const resetButton = document.getElementById('resetButton');

        searchInput.addEventListener('input', () => {
            const term = searchInput.value.toLowerCase();
            document.querySelectorAll('tbody tr').forEach(row => {
                row.style.display = row.textContent.toLowerCase().includes(term)
                    ? ''
                    : 'none';
            });
        });

        resetButton.addEventListener('click', () => {
            searchInput.value = '';
            document.querySelectorAll('tbody tr').forEach(row => {
                row.style.display = '';
            });
        });
    </script>
    @endpush
</x-app-layout>
