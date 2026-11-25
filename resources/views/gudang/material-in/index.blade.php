<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100">
                Material Masuk
            </h2>

            <a href="{{ route('gudang.material-in.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2
                      bg-blue-600 hover:bg-blue-700
                      text-white rounded-xl shadow">
                ➕ Tambah Material
            </a>
        </div>
    </x-slot>

    <div class="space-y-8">

        {{-- FILTER --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                @foreach([
                    ['Tanggal Dari','date_from','date'],
                    ['Tanggal Sampai','date_to','date']
                ] as [$label,$name,$type])
                <div>
                    <label class="text-sm text-gray-600 dark:text-gray-300">{{ $label }}</label>
                    <input type="{{ $type }}" name="{{ $name }}" value="{{ request($name) }}"
                           class="w-full mt-1 rounded-lg
                                  border-gray-300 dark:border-gray-600
                                  bg-white dark:bg-gray-700
                                  text-gray-800 dark:text-gray-100">
                </div>
                @endforeach

                <div>
                    <label class="text-sm text-gray-600 dark:text-gray-300">Vendor</label>
                    <select name="vendor_id"
                            class="w-full mt-1 rounded-lg
                                   border-gray-300 dark:border-gray-600
                                   bg-white dark:bg-gray-700
                                   text-gray-800 dark:text-gray-100">
                        <option value="">Semua Vendor</option>
                        @foreach($vendors as $vendor)
                            <option value="{{ $vendor->id }}" @selected(request('vendor_id')==$vendor->id)>
                                {{ $vendor->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="text-sm text-gray-600 dark:text-gray-300">Material</label>
                    <select name="material_id"
                            class="w-full mt-1 rounded-lg
                                   border-gray-300 dark:border-gray-600
                                   bg-white dark:bg-gray-700
                                   text-gray-800 dark:text-gray-100">
                        <option value="">Semua Material</option>
                        @foreach($materials as $material)
                            <option value="{{ $material->id }}" @selected(request('material_id')==$material->id)>
                                {{ $material->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="md:col-span-4 flex justify-end gap-2 pt-2">
                    <a href="{{ route('gudang.material-in.index') }}"
                       class="px-4 py-2 rounded-lg border
                              border-gray-300 dark:border-gray-600
                              text-gray-600 dark:text-gray-300
                              hover:bg-gray-100 dark:hover:bg-gray-700">
                        Reset
                    </a>
                    <button class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">
                        Filter
                    </button>
                </div>
            </form>
        </div>

        {{-- TABLE --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200">
                        <tr>
                            <th class="px-4 py-3 text-left">Kode</th>
                            <th class="px-4 py-3">Tanggal</th>
                            <th class="px-4 py-3">Material</th>
                            <th class="px-4 py-3">Vendor</th>
                            <th class="px-4 py-3">Qty</th>
                            <th class="px-4 py-3">Harga</th>
                            <th class="px-4 py-3">Total</th>
                            <th class="px-4 py-3">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($materialIn as $item)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-4 py-3 font-semibold text-blue-600">
                                    {{ $item->transaction_code }}
                                </td>
                                <td class="px-4 py-3 text-gray-700 dark:text-gray-200">
                                    {{ $item->date->format('d M Y') }}
                                </td>
                                <td class="px-4 py-3">
                                    <div class="font-medium text-gray-800 dark:text-gray-100">
                                        {{ $item->material->name }}
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $item->material->material_code }}
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-gray-700 dark:text-gray-200">
                                    {{ $item->vendor->name }}
                                </td>
                                <td class="px-4 py-3 text-green-600 font-semibold">
                                    +{{ number_format($item->qty,2) }} {{ $item->material->unit }}
                                </td>
                                <td class="px-4 py-3 text-gray-700 dark:text-gray-200">
                                    Rp {{ number_format($item->price_per_unit,0,',','.') }}
                                </td>
                                <td class="px-4 py-3 font-semibold text-gray-800 dark:text-gray-100">
                                    Rp {{ number_format($item->total_price,0,',','.') }}
                                </td>
                                <td class="px-4 py-3">
                                    <a href="{{ route('gudang.material-in.show',$item) }}"
                                       class="text-blue-600 hover:underline">
                                        Detail →
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-6 text-gray-400">
                                    Tidak ada data material masuk
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-gray-200 dark:border-gray-700">
                {{ $materialIn->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
