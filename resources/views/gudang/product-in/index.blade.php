<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Produk Masuk
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="mb-4 flex justify-end">
                <a href="{{ route('gudang.product-in.create') }}"
                   class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    + Produk Masuk
                </a>
            </div>

            <div class="shadow rounded">
                <table class="w-full border-collapse">
                    <thead class="">
                        <tr>
                            <th class="border px-4 py-2">Tanggal</th>
                            <th class="border px-4 py-2">Production Order</th>
                            <th class="border px-4 py-2">Produk</th>
                            <th class="border px-4 py-2">Qty</th>
                            <th class="border px-4 py-2">Kondisi</th>
                            <th class="border px-4 py-2">Diterima Oleh</th>
                            <th class="border px-4 py-2">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($productIns as $item)
                            <tr class="text-center">
                                <td class="border px-4 py-2">
                                    {{ $item->date->format('d/m/Y') }}
                                </td>
                                <td class="border px-4 py-2">
                                    {{ $item->productionOrder->order_code ?? '-' }}
                                </td>
                                <td class="border px-4 py-2">
                                    {{ $item->product->name ?? '-' }}
                                </td>
                                <td class="border px-4 py-2">
                                    {{ $item->qty }}
                                </td>
                                <td class="border px-4 py-2">
                                    <span class="px-2 py-1 rounded text-white
                                        {{ $item->condition === 'good' ? 'bg-green-600' : 'bg-red-600' }}">
                                        {{ ucfirst($item->condition) }}
                                    </span>
                                </td>
                                <td class="border px-4 py-2">
                                    {{ $item->receivedBy->name ?? '-' }}
                                </td>
                                <td class="border px-4 py-2">
                                    <div class="flex items-center justify-center gap-3">
                                        <a href="{{ route('gudang.product-in.show', $item) }}"
                                           class="text-blue-600 hover:underline">Detail</a>
                                        <a href="{{ route('gudang.product-in.edit', $item) }}"
                                           class="text-yellow-600 hover:underline">Edit</a>
                                        <a href="{{ route('gudang.product-in.print', $item) }}"
                                           class="text-gray-700 hover:underline" target="_blank">Print</a>
                                        <form action="{{ route('gudang.product-in.destroy', $item->id) }}"
                                              method="POST"
                                              onsubmit="return confirm('Yakin hapus data?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="text-red-600 hover:underline">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center p-4">
                                    Data kosong
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="p-4">
                    {{ $productIns->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
