<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Detail Product In
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="p-6 rounded shadow">

                <div class="grid grid-cols-2 gap-4">

                    <div>
                        <strong>Produk:</strong><br>
                        {{ $productIn->product->name }}
                    </div>

                    <div>
                        <strong>Tanggal:</strong><br>
                        {{ $productIn->date }}
                    </div>

                    <div>
                        <strong>Qty:</strong><br>
                        {{ $productIn->qty }}
                    </div>

                    <div>
                        <strong>Kondisi:</strong><br>
                        {{ ucfirst($productIn->condition) }}
                    </div>

                    <div>
                        <strong>Order Produksi:</strong><br>
                        {{ $productIn->productionOrder->order_code ?? '-' }}
                    </div>

                    <div>
                        <strong>Diterima Oleh:</strong><br>
                        {{ $productIn->receivedBy->name }}
                    </div>

                </div>

                <hr class="my-4">

                <a href="{{ route('gudang.product-in.print', $productIn->id) }}"
                   class="bg-blue-600 text-white px-4 py-2 rounded">Print</a>

            </div>
        </div>
    </div>
</x-app-layout>
