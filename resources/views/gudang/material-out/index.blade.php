<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
            Material Keluar
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Filter --}}
            <div class="bg-white dark:bg-gray-800 p-4 rounded-xl shadow mb-4">
                <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">

                    <div>
                        <label class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                            Dari Tanggal
                        </label>
                        <input type="date" name="date_from"
                               class="mt-1 w-full rounded-lg
                                      bg-gray-50 dark:bg-gray-700
                                      border-gray-300 dark:border-gray-600
                                      text-gray-900 dark:text-gray-100"
                               value="{{ request('date_from') }}">
                    </div>

                    <div>
                        <label class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                            Sampai Tanggal
                        </label>
                        <input type="date" name="date_to"
                               class="mt-1 w-full rounded-lg
                                      bg-gray-50 dark:bg-gray-700
                                      border-gray-300 dark:border-gray-600
                                      text-gray-900 dark:text-gray-100"
                               value="{{ request('date_to') }}">
                    </div>

                    <div>
                        <label class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                            Material
                        </label>
                        <select name="material_id"
                                class="mt-1 w-full rounded-lg
                                       bg-gray-50 dark:bg-gray-700
                                       border-gray-300 dark:border-gray-600
                                       text-gray-900 dark:text-gray-100">
                            <option value="">Semua</option>
                            @foreach ($materials as $m)
                                <option value="{{ $m->id }}"
                                    {{ request('material_id') == $m->id ? 'selected' : '' }}>
                                    {{ $m->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                            Production Order
                        </label>
                        <select name="production_order_id"
                                class="mt-1 w-full rounded-lg
                                       bg-gray-50 dark:bg-gray-700
                                       border-gray-300 dark:border-gray-600
                                       text-gray-900 dark:text-gray-100">
                            <option value="">Semua</option>
                            @foreach ($productionOrders as $po)
                                <option value="{{ $po->id }}"
                                    {{ request('production_order_id') == $po->id ? 'selected' : '' }}>
                                    {{ $po->order_code }} - {{ $po->product->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex items-end">
                        <button
                            class="w-full px-4 py-2 rounded-lg
                                   bg-indigo-600 text-white
                                   hover:bg-indigo-700 transition">
                            Filter
                        </button>
                    </div>

                </form>
            </div>

            {{-- Table --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow overflow-hidden">
                <div class="p-4 flex justify-end">
                    <a href="{{ route('gudang.material-out.create') }}"
                       class="px-4 py-2 rounded-lg
                              bg-green-600 text-white
                              hover:bg-green-700 transition">
                        + Tambah Material Keluar
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200">
                            <tr>
                                <th class="px-4 py-3 text-left">Tanggal</th>
                                <th class="px-4 py-3 text-left">PO</th>
                                <th class="px-4 py-3 text-left">Material</th>
                                <th class="px-4 py-3 text-left">Qty</th>
                                <th class="px-4 py-3 text-left">Issued By</th>
                                <th class="px-4 py-3"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse ($materialOut as $row)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="px-4 py-3 text-gray-800 dark:text-gray-100">
                                        {{ $row->date->format('d/m/Y') }}
                                    </td>
                                    <td class="px-4 py-3 text-gray-800 dark:text-gray-100">
                                        {{ $row->productionOrder->order_code }}
                                        <span class="text-xs text-gray-500 dark:text-gray-400">
                                            ({{ $row->productionOrder->product->name }})
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-gray-800 dark:text-gray-100">
                                        {{ $row->material->name }}
                                    </td>
                                    <td class="px-4 py-3 text-gray-800 dark:text-gray-100">
                                        {{ number_format($row->qty, 2) }} {{ $row->material->unit }}
                                    </td>
                                    <td class="px-4 py-3 text-gray-800 dark:text-gray-100">
                                        {{ $row->issuedBy->name }}
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        <a href="{{ route('gudang.material-out.show', $row->id) }}"
                                           class="text-indigo-600 dark:text-indigo-400 hover:underline">
                                            Detail
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6"
                                        class="text-center py-6 text-gray-500 dark:text-gray-400">
                                        Tidak ada data
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="p-4">
                        {{ $materialOut->links() }}
                    </div>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>
