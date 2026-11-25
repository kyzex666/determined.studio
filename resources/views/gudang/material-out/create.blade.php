<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100">
            Material Out
        </h2>
    </x-slot>

    <div class="max-w-6xl mx-auto py-6 px-4">

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
            <form method="POST" action="{{ route('gudang.material-out.store') }}">
                @csrf

                {{-- PO --}}
                <div class="mb-6">
                    <label class="block mb-1 font-medium text-gray-700 dark:text-gray-300">
                        Production Order
                    </label>
                    <select id="po_id" name="production_order_id" required
                        class="w-full rounded-lg
                               bg-gray-50 dark:bg-gray-700
                               border-gray-300 dark:border-gray-600
                               text-gray-900 dark:text-gray-100">
                        <option value="">-- Pilih PO --</option>
                        @foreach($productionOrders as $po)
                            <option value="{{ $po->id }}">
                                {{ $po->order_code }} - {{ $po->product->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- MATERIAL LIST --}}
                <div class="overflow-x-auto">
                    <table class="w-full text-sm hidden rounded-xl overflow-hidden"
                           id="material-table">
                        <thead
                            class="bg-gray-100 dark:bg-gray-700
                                   text-gray-700 dark:text-gray-200">
                            <tr>
                                <th class="px-4 py-3 text-left">Material</th>
                                <th class="px-4 py-3">Required</th>
                                <th class="px-4 py-3">Issued</th>
                                <th class="px-4 py-3">Remaining</th>
                                <th class="px-4 py-3">Stock</th>
                                <th class="px-4 py-3">Qty Out</th>
                            </tr>
                        </thead>
                        <tbody
                            id="material-body"
                            class="divide-y divide-gray-200 dark:divide-gray-700
                                   bg-white dark:bg-gray-800">
                        </tbody>
                    </table>
                </div>

                {{-- Tanggal --}}
                <div class="mt-6">
                    <label class="block mb-1 font-medium text-gray-700 dark:text-gray-300">
                        Tanggal
                    </label>
                    <input type="date" name="date"
                        value="{{ date('Y-m-d') }}"
                        class="w-full rounded-lg
                               bg-gray-50 dark:bg-gray-700
                               border-gray-300 dark:border-gray-600
                               text-gray-900 dark:text-gray-100">
                </div>

                {{-- Action --}}
                <div class="mt-6 flex justify-end">
                    <button
                        class="px-6 py-2 rounded-lg
                               bg-indigo-600 text-white
                               hover:bg-indigo-700 transition">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('po_id').addEventListener('change', function () {
            if (!this.value) return;

            fetch(`/gudang/material-out/po/${this.value}`)
                .then(res => res.json())
                .then(data => {
                    document.getElementById('material-table')
                        .classList.remove('hidden');

                    const body = document.getElementById('material-body');
                    body.innerHTML = '';

                    data.materials.forEach((m, i) => {
                        body.innerHTML += `
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-4 py-3 text-gray-800 dark:text-gray-100">
                                ${m.material_name}
                            </td>
                            <td class="px-4 py-3 text-center text-gray-800 dark:text-gray-100">
                                ${m.required_qty}
                            </td>
                            <td class="px-4 py-3 text-center text-gray-800 dark:text-gray-100">
                                ${m.issued_qty}
                            </td>
                            <td class="px-4 py-3 text-center text-gray-800 dark:text-gray-100">
                                ${m.remaining_qty}
                            </td>
                            <td class="px-4 py-3 text-center text-gray-800 dark:text-gray-100">
                                ${m.current_stock}
                            </td>
                            <td class="px-4 py-3">
                                <input type="number"
                                    name="materials[${i}][qty]"
                                    min="0"
                                    max="${m.remaining_qty}"
                                    class="w-full rounded-lg
                                           bg-gray-50 dark:bg-gray-700
                                           border-gray-300 dark:border-gray-600
                                           text-gray-900 dark:text-gray-100">
                                <input type="hidden"
                                    name="materials[${i}][material_id]"
                                    value="${m.material_id}">
                            </td>
                        </tr>`;
                    });
                });
        });
    </script>
</x-app-layout>
