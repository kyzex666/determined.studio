<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Detail Production Order
        </h2>
    </x-slot>

    <div class="py-6 max-w-5xl mx-auto space-y-6">

        <div class="p-6 rounded shadow">
            <p><strong>Kode PO:</strong> {{ $order->order_code }}</p>
            <p><strong>Produk:</strong> {{ $order->product->name }}</p>
            <p><strong>Status:</strong> {{ ucfirst($order->status) }}</p>
            <p><strong>Target Qty:</strong> {{ $order->target_qty }}</p>
            <p><strong>Actual Qty:</strong> {{ $order->actual_qty ?? '-' }}</p>
        </div>

        @if($order->status === 'process')
        <div class="p-6 rounded shadow">
            <h3 class="font-semibold mb-4 text-green-600">Selesaikan Production Order</h3>

            <form action="{{ route('produksi.production-orders.complete', $order) }}" method="POST">
                @csrf
                @method('POST')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Jumlah Aktual Produksi</label>
                        <input type="number" name="actual_qty" value="{{ $order->target_qty }}"
                               class="bg-black mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                               min="0" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tanggal Selesai</label>
                        <input type="date" name="completion_date" value="{{ date('Y-m-d') }}"
                               class="bg-black mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                               required>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Kondisi Produk</label>
                    <div class="mt-2">
                        <label class="inline-flex items-center">
                            <input type="radio" name="condition" value="good" class="form-radio" checked>
                            <span class="ml-2">Baik</span>
                        </label>
                        <label class="inline-flex items-center ml-6">
                            <input type="radio" name="condition" value="defect" class="form-radio">
                            <span class="ml-2">Cacat</span>
                        </label>
                    </div>
                </div>

                <div class="mb-4" id="defect-notes" style="display: none;">
                    <label class="block text-sm font-medium text-gray-700">Catatan Cacat</label>
                    <textarea name="defect_notes" rows="3"
                              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                              placeholder="Jelaskan cacat yang terjadi..."></textarea>
                </div>

                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                    Selesaikan & Kirim ke Gudang
                </button>
            </form>
        </div>

        <script>
            document.querySelectorAll('input[name="condition"]').forEach(radio => {
                radio.addEventListener('change', function() {
                    const defectNotes = document.getElementById('defect-notes');
                    if (this.value === 'defect') {
                        defectNotes.style.display = 'block';
                    } else {
                        defectNotes.style.display = 'none';
                    }
                });
            });
        </script>
        @endif

        <div class="p-6 rounded shadow">
            <h3 class="font-semibold mb-4">Kebutuhan Material</h3>

            <table class="w-full text-sm border">
                <thead class="">
                    <tr>
                        <th class="p-2 text-left">Material</th>
                        <th class="p-2">Qty Dibutuhkan</th>
                        <th class="p-2">Qty Dikeluarkan</th>
                        <th class="p-2">Unit</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->materials as $item)
                        <tr class="border-t">
                            <td class="p-2">{{ $item->material->name }}</td>
                            <td class="p-2 text-center">{{ $item->required_qty }}</td>
                            <td class="p-2 text-center">{{ $item->issued_qty ?? 0 }}</td>
                            <td class="p-2 text-center">{{ $item->unit }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
</x-app-layout>
