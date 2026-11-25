<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                    📋 Buat Permintaan Material
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Pilih Production Order untuk meminta material dari Gudang
                </p>
            </div>

            <a href="{{ route('produksi.material-requests.index') }}"
               class="px-4 py-2 rounded-lg
                      bg-gray-200 dark:bg-gray-800
                      text-gray-700 dark:text-gray-300
                      hover:bg-gray-300 dark:hover:bg-gray-700 transition">
                ← Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <form method="POST"
                  action="{{ route('produksi.material-requests.store') }}"
                  class="bg-white dark:bg-gray-900
                         border border-gray-200 dark:border-gray-800
                         rounded-2xl shadow-xl p-8 space-y-6">
                @csrf

                <div>
                    <label class="block text-sm font-medium
                                  text-gray-700 dark:text-gray-300 mb-2">
                        Pilih Production Order <span class="text-red-500">*</span>
                    </label>

                    <select name="production_order_id" required
                        class="w-full rounded-xl px-4 py-3
                               bg-gray-100 dark:bg-gray-800
                               border border-gray-300 dark:border-gray-700
                               text-gray-900 dark:text-white
                               focus:ring-2 focus:ring-indigo-500">
                        <option value="">— Pilih Production Order —</option>
                        @foreach($orders as $order)
                            <option value="{{ $order->id }}"
                                {{ ($selectedOrder && $selectedOrder->id == $order->id) ? 'selected' : '' }}>
                                {{ $order->product->name }} - Target: {{ $order->target_quantity }} unit
                                ({{ $order->created_at->format('d/m/Y') }})
                            </option>
                        @endforeach
                    </select>

                    @error('production_order_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                @if($selectedOrder)
                <div class="bg-blue-50 dark:bg-blue-900/20 rounded-xl p-4">
                    <h3 class="font-medium text-blue-900 dark:text-blue-100 mb-2">
                        Detail Production Order Terpilih
                    </h3>
                    <p class="text-sm text-blue-800 dark:text-blue-200">
                        <strong>Produk:</strong> {{ $selectedOrder->product->name }}<br>
                        <strong>Target:</strong> {{ $selectedOrder->target_quantity }} unit<br>
                        <strong>Prioritas:</strong> {{ ucfirst($selectedOrder->priority) }}<br>
                        <strong>Deadline:</strong> {{ $selectedOrder->deadline ? $selectedOrder->deadline->format('d/m/Y') : 'Tidak ada' }}
                    </p>
                </div>
                @endif

                <div class="flex justify-end space-x-4">
                    <a href="{{ route('produksi.material-requests.index') }}"
                       class="px-6 py-2 rounded-lg
                              bg-gray-200 dark:bg-gray-800
                              text-gray-700 dark:text-gray-300
                              hover:bg-gray-300 dark:hover:bg-gray-700 transition">
                        Batal
                    </a>

                    <button type="submit"
                            class="px-6 py-2 rounded-lg
                                   bg-indigo-600 text-white
                                   hover:bg-indigo-700 transition">
                        Kirim Permintaan Material
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>