<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold text-slate-100 tracking-tight">
                Data Bahan Baku
            </h2>

            <a href="{{ route('admin.materials.create') }}"
               class="inline-flex items-center gap-2
               px-4 py-2 rounded-xl
               bg-indigo-600 hover:bg-indigo-500
               text-white text-sm font-medium
               shadow-lg shadow-indigo-600/30 transition">
                ➕ Tambah Bahan
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-slate-900 border border-slate-800 rounded-2xl shadow-xl overflow-hidden">

                {{-- FILTER --}}
                <div class="p-6 border-b border-slate-800 bg-slate-900/70 backdrop-blur">
                    <div class="flex flex-col md:flex-row gap-4">

                        <input id="searchInput" type="text"
                            placeholder="Cari bahan..."
                            class="flex-1 px-4 py-2 rounded-xl
                            bg-slate-800 border border-slate-700
                            text-slate-100 placeholder-slate-400
                            focus:ring-2 focus:ring-indigo-500 focus:outline-none">

                        <select id="categoryFilter"
                            class="px-3 py-2 rounded-xl
                            bg-slate-800 border border-slate-700
                            text-slate-200">
                            <option value="">Semua Kategori</option>
                            <option value="kain">Kain</option>
                            <option value="benang">Benang</option>
                            <option value="aksesoris">Aksesoris</option>
                            <option value="packing">Packing</option>
                            <option value="lainnya">Lainnya</option>
                        </select>

                        <select id="stockFilter"
                            class="px-3 py-2 rounded-xl
                            bg-slate-800 border border-slate-700
                            text-slate-200">
                            <option value="">Semua Stok</option>
                            <option value="out_of_stock">Habis</option>
                            <option value="low_stock">Rendah</option>
                            <option value="sufficient">Cukup</option>
                        </select>

                        <button id="resetButton"
                            class="px-4 py-2 rounded-xl
                            bg-red-500/80 hover:bg-red-500
                            text-white transition">
                            Reset
                        </button>
                    </div>
                </div>

                {{-- TABLE --}}
                <div class="overflow-x-auto">
                    <table class="w-full text-sm materials-table">
                        <thead class="bg-slate-800 border-b border-slate-700">
                            <tr class="text-xs uppercase tracking-wide text-slate-400">
                                <th class="px-6 py-3 text-left">#</th>
                                <th class="px-6 py-3 text-left">Kode</th>
                                <th class="px-6 py-3 text-left">Nama</th>
                                <th class="px-6 py-3 text-left">Kategori</th>
                                <th class="px-6 py-3 text-left">Stok</th>
                                <th class="px-6 py-3 text-left">Min</th>
                                <th class="px-6 py-3 text-left">Lokasi</th>
                                <th class="px-6 py-3 text-left">Status</th>
                                <th class="px-6 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-800">
                            @forelse($materials as $material)
                            @php $status = $material->getStockStatus(); @endphp

                            <tr class="hover:bg-slate-800/60 transition"
                                data-category="{{ $material->category }}"
                                data-stock="{{ $status }}">

                                <td class="px-6 py-4 text-slate-300">{{ $loop->iteration }}</td>

                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 rounded-lg text-xs
                                        bg-indigo-500/15 text-indigo-400 font-semibold">
                                        {{ $material->material_code }}
                                    </span>
                                </td>

                                <td class="px-6 py-4">
                                    <div class="font-semibold text-slate-100">
                                        {{ $material->name }}
                                    </div>
                                    @if($material->notes)
                                        <div class="text-xs text-slate-400">
                                            {{ Str::limit($material->notes, 45) }}
                                        </div>
                                    @endif
                                </td>

                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 rounded-lg text-xs
                                        bg-slate-700 text-slate-200">
                                        {{ ucfirst($material->category) }}
                                    </span>
                                </td>

                                <td class="px-6 py-4">
                                    <div class="font-semibold text-slate-100">
                                        {{ number_format($material->current_stock,2) }}
                                    </div>
                                    <div class="text-xs text-slate-400">
                                        {{ $material->unit }}
                                    </div>
                                </td>

                                <td class="px-6 py-4 text-slate-300">
                                    {{ number_format($material->min_stock,2) }}
                                </td>

                                <td class="px-6 py-4 text-slate-400">
                                    {{ $material->location ?? '-' }}
                                </td>

                                <td class="px-6 py-4 font-medium
                                    {{ $status === 'out_of_stock' ? 'text-red-400' :
                                       ($status === 'low_stock' ? 'text-amber-400' : 'text-emerald-400') }}">
                                    {{ $status === 'out_of_stock' ? 'Habis' :
                                       ($status === 'low_stock' ? 'Rendah' : 'Cukup') }}
                                </td>

                                <td class="px-6 py-4 text-center">
                                    <div class="flex justify-center gap-3 text-lg">
                                        <a href="{{ route('admin.materials.show', $material) }}" class="text-indigo-400 hover:text-indigo-300">👁</a>
                                        <a href="{{ route('admin.materials.edit', $material) }}" class="text-amber-400 hover:text-amber-300">✏️</a>
                                        <button class="text-emerald-400 hover:text-emerald-300 adjust-btn"
                                                data-id="{{ $material->id }}">🔧</button>
                                    </div>
                                </td>
                            </tr>

                            @empty
                            <tr>
                                <td colspan="9" class="py-12 text-center text-slate-400">
                                    Belum ada data bahan
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
