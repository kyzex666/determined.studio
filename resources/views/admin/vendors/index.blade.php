<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold text-slate-100 tracking-tight">
                Data Vendor
            </h2>

            <a href="{{ route('admin.vendors.create') }}"
               class="inline-flex items-center gap-2
               px-4 py-2 rounded-xl
               bg-indigo-600 hover:bg-indigo-500
               text-white text-sm font-medium
               shadow-lg shadow-indigo-600/30 transition">
                ➕ Tambah Vendor
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div
                class="bg-slate-900 border border-slate-800
                rounded-2xl shadow-xl overflow-hidden">

                {{-- SEARCH --}}
                <div
                    class="p-6 border-b border-slate-800
                    bg-slate-900/70 backdrop-blur">

                    <div class="flex gap-2">
                        <input
                            id="searchInput"
                            type="text"
                            placeholder="Cari vendor..."
                            class="flex-1 px-4 py-2
                            rounded-xl bg-slate-800 border border-slate-700
                            text-slate-100 placeholder-slate-400
                            focus:ring-2 focus:ring-indigo-500
                            focus:border-indigo-500 focus:outline-none">

                        <button
                            id="searchButton"
                            class="px-4 py-2 rounded-xl
                            bg-slate-700 hover:bg-slate-600
                            text-slate-100 transition">
                            🔍
                        </button>

                        <button
                            id="resetButton"
                            class="px-4 py-2 rounded-xl
                            bg-red-500/80 hover:bg-red-500
                            text-white transition">
                            ✕
                        </button>
                    </div>
                </div>

                {{-- TABLE --}}
                <div class="overflow-x-auto">
                    <table class="w-full text-sm vendor-table">
                        <thead class="bg-slate-800 border-b border-slate-700">
                            <tr class="text-slate-400 text-xs uppercase tracking-wide">
                                <th class="px-6 py-3 text-left">#</th>
                                <th class="px-6 py-3 text-left">Kode</th>
                                <th class="px-6 py-3 text-left">Vendor</th>
                                <th class="px-6 py-3 text-left">Kontak</th>
                                <th class="px-6 py-3 text-left">Alamat</th>
                                <th class="px-6 py-3 text-left">Transaksi</th>
                                <th class="px-6 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-800">
                            @forelse($vendors as $vendor)
                            <tr class="hover:bg-slate-800/60 transition">
                                <td class="px-6 py-4 text-slate-300">
                                    {{ $loop->iteration }}
                                </td>

                                <td class="px-6 py-4">
                                    <span
                                        class="px-2 py-1 rounded-lg text-xs font-semibold
                                        bg-indigo-500/15 text-indigo-400">
                                        {{ $vendor->vendor_code }}
                                    </span>
                                </td>

                                <td class="px-6 py-4">
                                    <div class="font-semibold text-slate-100">
                                        {{ $vendor->name }}
                                    </div>
                                    @if($vendor->pic_name)
                                        <div class="text-xs text-slate-400">
                                            PIC: {{ $vendor->pic_name }}
                                        </div>
                                    @endif
                                </td>

                                <td class="px-6 py-4 text-slate-300">
                                    @if($vendor->phone)
                                        <div>{{ $vendor->phone }}</div>
                                    @endif
                                    @if($vendor->email)
                                        <div class="text-indigo-400 text-xs">
                                            {{ $vendor->email }}
                                        </div>
                                    @endif
                                </td>

                                <td class="px-6 py-4 text-slate-400">
                                    {{ $vendor->address ? Str::limit($vendor->address, 45) : '-' }}
                                </td>

                                <td class="px-6 py-4">
                                    <span
                                        class="px-2 py-1 rounded-lg text-xs font-semibold
                                        bg-slate-700 text-slate-200">
                                        {{ $vendor->material_in_count ?? 0 }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 text-center">
                                    <div class="flex justify-center gap-3 text-lg">
                                        <a href="{{ route('admin.vendors.show', $vendor) }}"
                                           class="text-indigo-400 hover:text-indigo-300">
                                            👁
                                        </a>

                                        <a href="{{ route('admin.vendors.edit', $vendor) }}"
                                           class="text-amber-400 hover:text-amber-300">
                                            ✏️
                                        </a>

                                        <form method="POST"
                                              action="{{ route('admin.vendors.destroy', $vendor) }}"
                                              onsubmit="return confirm('Hapus vendor ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button
                                                class="text-red-400 hover:text-red-300">
                                                🗑
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7"
                                    class="py-12 text-center text-slate-400">
                                    Belum ada data vendor
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        const search = () => {
            const term = searchInput.value.toLowerCase();
            document.querySelectorAll('.vendor-table tbody tr')
                .forEach(row => {
                    row.style.display =
                        row.textContent.toLowerCase().includes(term) ? '' : 'none';
                });
        };

        searchButton.onclick = search;
        searchInput.onkeypress = e => e.key === 'Enter' && search();
        resetButton.onclick = () => {
            searchInput.value = '';
            search();
        };
    </script>
    @endpush
</x-app-layout>
