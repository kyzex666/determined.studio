<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-700">
            Detail Material Keluar
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto">

            <div class="bg-white p-6 rounded shadow">

                <div class="grid grid-cols-2 gap-4">

                    <div>
                        <p class="text-gray-500">Tanggal</p>
                        <p class="font-semibold">{{ $materialOut->date->format('d/m/Y') }}</p>
                    </div>

                    <div>
                        <p class="text-gray-500">Production Order</p>
                        <p class="font-semibold">
                            {{ $materialOut->productionOrder->order_code }}
                            ({{ $materialOut->productionOrder->product->name }})
                        </p>
                    </div>

                    <div>
                        <p class="text-gray-500">Material</p>
                        <p class="font-semibold">{{ $materialOut->material->name }}</p>
                    </div>

                    <div>
                        <p class="text-gray-500">Qty</p>
                        <p class="font-semibold">
                            {{ number_format($materialOut->qty, 2) }} {{ $materialOut->material->unit }}
                        </p>
                    </div>

                    <div>
                        <p class="text-gray-500">Dikeluarkan Oleh</p>
                        <p class="font-semibold">{{ $materialOut->issuedBy->name }}</p>
                    </div>

                    <div class="col-span-2">
                        <p class="text-gray-500">Catatan</p>
                        <p>{{ $materialOut->notes ?? '-' }}</p>
                    </div>
                </div>

                <div class="mt-6 text-right">
                    <a href="{{ route('gudang.material-out.print', $materialOut->id) }}"
                       target="_blank"
                       class="px-4 py-2 bg-green-600 text-white rounded">
                       Print
                    </a>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>
