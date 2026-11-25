<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100">
            Detail Material Masuk
        </h2>
    </x-slot>

    <div class="max-w-4xl mx-auto py-6 space-y-6">

        <div class="bg-white dark:bg-gray-800 shadow rounded-xl p-6">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">

                {{-- Kode --}}
                <div>
                    <p class="text-gray-500 dark:text-gray-400">Kode Transaksi</p>
                    <p class="font-semibold text-gray-900 dark:text-gray-100">
                        {{ $materialIn->transaction_code ?? '-' }}
                    </p>
                </div>

                {{-- Tanggal --}}
                <div>
                    <p class="text-gray-500 dark:text-gray-400">Tanggal</p>
                    <p class="font-semibold text-gray-900 dark:text-gray-100">
                        {{ \Carbon\Carbon::parse($materialIn->date)->format('d M Y') }}
                    </p>
                </div>

                {{-- Vendor --}}
                <div>
                    <p class="text-gray-500 dark:text-gray-400">Vendor</p>
                    <p class="font-semibold text-gray-900 dark:text-gray-100">
                        {{ $materialIn->vendor->name ?? '-' }}
                    </p>
                </div>

                {{-- Material --}}
                <div>
                    <p class="text-gray-500 dark:text-gray-400">Material</p>
                    <p class="font-semibold text-gray-900 dark:text-gray-100">
                        {{ $materialIn->material->name ?? '-' }}
                        ({{ $materialIn->material->unit ?? '-' }})
                    </p>
                </div>

                {{-- Qty --}}
                <div>
                    <p class="text-gray-500 dark:text-gray-400">Jumlah Masuk</p>
                    <p class="font-semibold text-gray-900 dark:text-gray-100">
                        {{ number_format($materialIn->qty, 2) }}
                    </p>
                </div>

                {{-- Stok Sekarang --}}
                <div>
                    <p class="text-gray-500 dark:text-gray-400">Stok Saat Ini</p>
                    <p class="font-semibold text-gray-900 dark:text-gray-100">
                        {{ $materialIn->material->current_stock ?? '-' }}
                    </p>
                </div>

                {{-- Catatan --}}
                <div class="md:col-span-2">
                    <p class="text-gray-500 dark:text-gray-400">Catatan</p>
                    <p class="font-semibold text-gray-900 dark:text-gray-100">
                        {{ $materialIn->note ?? '-' }}
                    </p>
                </div>

                {{-- Diterima oleh --}}
                <div>
                    <p class="text-gray-500 dark:text-gray-400">Diterima Oleh</p>
                    <p class="font-semibold text-gray-900 dark:text-gray-100">
                        {{ $materialIn->receivedBy->name ?? '-' }}
                    </p>
                </div>

                {{-- Waktu input --}}
                <div>
                    <p class="text-gray-500 dark:text-gray-400">Waktu Input</p>
                    <p class="font-semibold text-gray-900 dark:text-gray-100">
                        {{ $materialIn->created_at->format('d M Y H:i') }}
                    </p>
                </div>

            </div>

            {{-- ACTION --}}
            <div class="flex justify-between items-center mt-8 pt-4 border-t border-gray-200 dark:border-gray-700">

                <a href="{{ route('gudang.material-in.index') }}"
                   class="text-sm text-gray-600 dark:text-gray-300 hover:underline">
                    ← Kembali
                </a>

                <a href="{{ route('gudang.material-in.print', $materialIn->id) }}"
                   target="_blank"
                   class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                    Print
                </a>

            </div>

        </div>
    </div>
</x-app-layout>
