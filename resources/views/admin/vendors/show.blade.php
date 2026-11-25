<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-white leading-tight">
                Detail Vendor —
                <span class="text-blue-400">{{ $vendor->name }}</span>
            </h2>

            <div class="flex gap-2">
                <a href="{{ route('admin.vendors.edit', $vendor) }}"
                   class="px-4 py-2 rounded-xl bg-yellow-500/10 text-yellow-400 hover:bg-yellow-500/20 transition">
                    ✏️ Edit
                </a>

                <form action="{{ route('admin.vendors.destroy', $vendor) }}" method="POST"
                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus vendor ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="px-4 py-2 rounded-xl bg-red-500/10 text-red-400 hover:bg-red-500/20 transition
                                   {{ $vendor->material_in_count > 0 ? 'opacity-50 cursor-not-allowed' : '' }}"
                            {{ $vendor->material_in_count > 0 ? 'disabled' : '' }}>
                        🗑 Hapus
                    </button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-slate-900 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- MAIN INFO -->
                <div class="lg:col-span-2 bg-slate-800 rounded-2xl shadow-xl p-6 border border-slate-700">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm text-slate-300">

                        <div class="space-y-3">
                            <div>
                                <p class="text-slate-400">Kode Vendor</p>
                                <p class="font-semibold text-white">{{ $vendor->vendor_code }}</p>
                            </div>

                            <div>
                                <p class="text-slate-400">Nama Vendor</p>
                                <p class="font-semibold text-white">{{ $vendor->name }}</p>
                            </div>

                            <div>
                                <p class="text-slate-400">Telepon</p>
                                <p>{{ $vendor->phone ?? '-' }}</p>
                            </div>

                            <div>
                                <p class="text-slate-400">Email</p>
                                <p>{{ $vendor->email ?? '-' }}</p>
                            </div>
                        </div>

                        <div class="space-y-3">
                            <div>
                                <p class="text-slate-400">Nama PIC</p>
                                <p>{{ $vendor->pic_name ?? '-' }}</p>
                            </div>

                            <div>
                                <p class="text-slate-400">Telepon PIC</p>
                                <p>{{ $vendor->pic_phone ?? '-' }}</p>
                            </div>

                            <div>
                                <p class="text-slate-400">Total Transaksi</p>
                                <p class="font-semibold text-white">
                                    {{ $vendor->material_in_count ?? 0 }} transaksi
                                </p>
                            </div>

                            <div>
                                <p class="text-slate-400">Status</p>
                                @if($vendor->material_in_count > 0)
                                    <span class="text-green-400 font-medium">Aktif</span>
                                @else
                                    <span class="text-yellow-400 font-medium">Belum Ada Transaksi</span>
                                @endif
                            </div>
                        </div>

                    </div>

                    <!-- ALAMAT -->
                    <div class="mt-6">
                        <h4 class="font-semibold text-white mb-2">📍 Alamat</h4>
                        <div class="p-4 bg-slate-900 border border-slate-700 rounded-xl text-slate-300">
                            {{ $vendor->address ?? 'Tidak ada alamat' }}
                        </div>
                    </div>

                    <!-- CATATAN -->
                    @if($vendor->notes)
                        <div class="mt-6">
                            <h4 class="font-semibold text-white mb-2">📝 Catatan</h4>
                            <div class="p-4 bg-slate-900 border border-slate-700 rounded-xl text-slate-300">
                                {{ $vendor->notes }}
                            </div>
                        </div>
                    @endif

                    <!-- TRANSAKSI -->
                    @if($vendor->material_in_count > 0)
                        <div class="mt-8">
                            <h4 class="font-semibold text-white mb-4">
                                📦 Transaksi Material Masuk Terbaru
                            </h4>

                            <div class="overflow-x-auto">
                                <table class="w-full text-sm text-slate-300">
                                    <thead class="bg-slate-900 text-slate-400">
                                        <tr>
                                            <th class="px-4 py-2 text-left">Tanggal</th>
                                            <th class="px-4 py-2 text-left">Kode</th>
                                            <th class="px-4 py-2 text-left">Material</th>
                                            <th class="px-4 py-2 text-right">Jumlah</th>
                                            <th class="px-4 py-2 text-right">Harga</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-700">
                                        @foreach($vendor->materialIn->take(5) as $materialIn)
                                            <tr class="hover:bg-slate-700/40">
                                                <td class="px-4 py-2">{{ $materialIn->date->format('d/m/Y') }}</td>
                                                <td class="px-4 py-2 text-blue-400">
                                                    <a href="{{ route('admin.material-in.show', $materialIn) }}">
                                                        {{ $materialIn->transaction_code }}
                                                    </a>
                                                </td>
                                                <td class="px-4 py-2">{{ $materialIn->material->name }}</td>
                                                <td class="px-4 py-2 text-right">
                                                    {{ number_format($materialIn->qty, 2) }} {{ $materialIn->material->unit }}
                                                </td>
                                                <td class="px-4 py-2 text-right">
                                                    Rp {{ number_format($materialIn->total_price, 0, ',', '.') }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- SIDE PANEL -->
                <aside class="bg-slate-800 rounded-2xl shadow-xl p-6 border border-slate-700">

                    <div class="text-center mb-6">
                        <div class="text-4xl font-bold text-blue-400">
                            {{ $vendor->material_in_count ?? 0 }}
                        </div>
                        <p class="text-slate-400">Total Transaksi</p>
                    </div>

                    <div class="space-y-3 text-sm text-slate-300">
                        <div class="flex justify-between">
                            <span>Total Nilai</span>
                            <span class="text-green-400">
                                Rp {{ number_format($vendor->material_in_sum_total_price ?? 0, 0, ',', '.') }}
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span>Bulan Ini</span>
                            <span>{{ $vendor->material_in_this_month_count ?? 0 }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Jenis Material</span>
                            <span>{{ $vendor->unique_materials_count ?? 0 }}</span>
                        </div>
                    </div>

                    <div class="mt-6 grid gap-2">
                        <a href="{{ route('admin.material-in.create') }}?vendor_id={{ $vendor->id }}"
                           class="px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-center transition">
                            ➕ Tambah Transaksi
                        </a>

                        <a href="{{ route('admin.vendors.index') }}"
                           class="px-4 py-2 rounded-xl bg-slate-700 hover:bg-slate-600 text-slate-200 text-center transition">
                            ← Kembali
                        </a>
                    </div>

                </aside>

            </div>
        </div>
    </div>
</x-app-layout>
