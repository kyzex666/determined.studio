<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                    🏭 Buat Order Produksi
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Menentukan target dan jadwal produksi
                </p>
            </div>

            <a href="{{ route('produksi.production-orders.index') }}"
               class="px-4 py-2 rounded-lg
                      bg-gray-200 dark:bg-gray-800
                      text-gray-700 dark:text-gray-300
                      hover:bg-gray-300 dark:hover:bg-gray-700 transition">
                ← Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            @if($errors->has('general_error'))
                <div class="mb-6 rounded-xl border border-red-500
                            bg-red-100 dark:bg-red-900/40
                            text-red-700 dark:text-red-300 px-5 py-4">
                    <strong>Error:</strong>
                    {{ $errors->first('general_error') }}
                </div>
            @endif

            <form method="POST"
                  action="{{ route('produksi.production-orders.store') }}"
                  class="bg-white dark:bg-gray-900
                         border border-gray-200 dark:border-gray-800
                         rounded-2xl shadow-xl p-8 space-y-8">
                @csrf

                {{-- PRODUK --}}
                <div>
                    <p class="text-xs uppercase tracking-widest
                              text-gray-500 dark:text-gray-400 mb-3">
                        Produk
                    </p>

                    <label class="block text-sm font-medium
                                  text-gray-700 dark:text-gray-300 mb-1">
                        Pilih Produk <span class="text-red-500">*</span>
                    </label>

                    <select name="product_id" required
                        class="w-full rounded-xl px-4 py-3
                               bg-gray-100 dark:bg-gray-800
                               border border-gray-300 dark:border-gray-700
                               text-gray-900 dark:text-white
                               focus:ring-2 focus:ring-indigo-500">
                        <option value="">— Pilih Produk —</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}"
                                {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                {{ $product->name }} ({{ $product->sku }})
                            </option>
                        @endforeach
                    </select>

                    @error('product_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Tambahkan setelah bagian PRODUK --}}
{{-- INFO BOM --}}
<div id="bom-info" class="hidden">
    <p class="text-xs uppercase tracking-widest
              text-gray-500 dark:text-gray-400 mb-3">
        Daftar Material yang Diperlukan
    </p>
    
    <div class="bg-gray-50 dark:bg-gray-800 rounded-xl p-4">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-300 dark:border-gray-700">
                    <th class="pb-2 text-left">Material</th>
                    <th class="pb-2 text-left">Dibutuhkan per Unit</th>
                    <th class="pb-2 text-left">Total untuk <span id="target-display">0</span> unit</th>
                </tr>
            </thead>
            <tbody id="bom-list">
                <!-- Diisi oleh JavaScript -->
            </tbody>
        </table>
        <p class="text-xs text-gray-500 mt-3">
            * Daftar ini akan otomatis dikirim ke Gudang saat status dipilih "Waiting Material"
        </p>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const productSelect = document.querySelector('select[name="product_id"]');
    const targetQtyInput = document.querySelector('input[name="target_qty"]');
    const bomInfo = document.getElementById('bom-info');
    const bomList = document.getElementById('bom-list');
    const targetDisplay = document.getElementById('target-display');
    
    // Fungsi untuk load BOM
    async function loadBOM(productId) {
        if (!productId) {
            bomInfo.classList.add('hidden');
            return;
        }
        
        try {
            const response = await fetch(`/api/products/${productId}/bom`);
            const bomItems = await response.json();
            
            if (bomItems.length > 0) {
                bomInfo.classList.remove('hidden');
                updateBOMDisplay(bomItems);
            } else {
                bomInfo.classList.add('hidden');
            }
        } catch (error) {
            console.error('Error loading BOM:', error);
            bomInfo.classList.add('hidden');
        }
    }
    
    // Update tampilan BOM
    function updateBOMDisplay(bomItems) {
        const targetQty = parseInt(targetQtyInput.value) || 0;
        targetDisplay.textContent = targetQty.toLocaleString();
        
        let html = '';
        bomItems.forEach(item => {
            const totalRequired = (item.quantity * targetQty).toFixed(2);
            html += `
                <tr class="border-b border-gray-200 dark:border-gray-700">
                    <td class="py-2">${item.material_name}</td>
                    <td class="py-2">${item.quantity} ${item.unit}</td>
                    <td class="py-2 font-medium">${totalRequired} ${item.unit}</td>
                </tr>
            `;
        });
        
        bomList.innerHTML = html;
    }
    
    // Event listeners
    productSelect.addEventListener('change', function() {
        loadBOM(this.value);
    });
    
    targetQtyInput.addEventListener('input', function() {
        if (productSelect.value) {
            loadBOM(productSelect.value);
        }
    });
    
    // Load BOM awal jika ada product yang sudah dipilih
    if (productSelect.value) {
        loadBOM(productSelect.value);
    }
});
</script>

                {{-- TARGET & TANGGAL --}}
                <div>
                    <p class="text-xs uppercase tracking-widest
                              text-gray-500 dark:text-gray-400 mb-3">
                        Target Produksi
                    </p>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <div>
                            <label class="text-sm text-gray-700 dark:text-gray-300">
                                Target Produksi <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="target_qty" min="1"
                                   value="{{ old('target_qty') }}"
                                   placeholder="Jumlah unit"
                                   class="mt-2 w-full rounded-xl px-4 py-3
                                          bg-gray-100 dark:bg-gray-800
                                          border border-gray-300 dark:border-gray-700
                                          text-gray-900 dark:text-white
                                          focus:ring-2 focus:ring-indigo-500"
                                   required>
                        </div>

                        <div>
                            <label class="text-sm text-gray-700 dark:text-gray-300">
                                Tanggal Mulai <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="start_date"
                                   value="{{ old('start_date', date('Y-m-d')) }}"
                                   class="mt-2 w-full rounded-xl px-4 py-3
                                          bg-gray-100 dark:bg-gray-800
                                          border border-gray-300 dark:border-gray-700
                                          text-gray-900 dark:text-white
                                          focus:ring-2 focus:ring-indigo-500"
                                   required>
                        </div>

                    </div>
                </div>

                {{-- PRIORITAS --}}
                <div>
                    <p class="text-xs uppercase tracking-widest
                              text-gray-500 dark:text-gray-400 mb-3">
                        Prioritas
                    </p>

                    <select name="priority"
                        class="w-full rounded-xl px-4 py-3
                               bg-gray-100 dark:bg-gray-800
                               border border-gray-300 dark:border-gray-700
                               text-gray-900 dark:text-white
                               focus:ring-2 focus:ring-indigo-500">
                        <option value="low">Low</option>
                        <option value="medium" selected>Medium</option>
                        <option value="high">High</option>
                    </select>
                </div>

                {{-- CATATAN --}}
                <div>
                    <p class="text-xs uppercase tracking-widest
                              text-gray-500 dark:text-gray-400 mb-3">
                        Catatan
                    </p>

                    <textarea name="notes" rows="3"
                        placeholder="Catatan tambahan (opsional)"
                        class="w-full rounded-xl px-4 py-3
                               bg-gray-100 dark:bg-gray-800
                               border border-gray-300 dark:border-gray-700
                               text-gray-900 dark:text-white
                               focus:ring-2 focus:ring-indigo-500">{{ old('notes') }}</textarea>
                </div>

                {{-- ACTION --}}
                <div class="pt-6 border-t border-gray-200 dark:border-gray-800
                            flex justify-end gap-4">

                    <a href="{{ route('produksi.production-orders.index') }}"
                       class="px-5 py-2.5 rounded-xl
                              bg-gray-200 dark:bg-gray-800
                              text-gray-700 dark:text-gray-300
                              hover:bg-gray-300 dark:hover:bg-gray-700 transition">
                        Batal
                    </a>

                    <button type="submit"
                        class="px-6 py-2.5 rounded-xl
                               bg-gradient-to-r from-blue-600 to-indigo-600
                               text-white font-semibold shadow-lg hover:opacity-90 transition">
                        💾 Simpan Order Produksi
                    </button>

                </div>

            </form>

        </div>
    </div>
</x-app-layout>
