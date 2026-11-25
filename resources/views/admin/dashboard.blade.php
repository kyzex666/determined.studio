<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-100 leading-tight">
            Dashboard Gudang
        </h2>
    </x-slot>

   {{-- MAIN CONTENT --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- AKTIVITAS MATERIAL --}}
    <div class="lg:col-span-2 bg-slate-900 border border-slate-800 rounded-xl p-6">
        
        <div class="flex justify-between items-center mb-5">
            <h3 class="font-semibold text-slate-100">
                Aktivitas Material Terbaru
            </h3>
            <a href="{{ route('admin.material-in.index') }}"
               class="text-sm text-indigo-400 hover:underline">
                Lihat semua
            </a>
        </div>

        <div class="space-y-3">
            @forelse($activities ?? [] as $item)
                <div class="flex items-center justify-between p-4 rounded-lg bg-slate-800/50 hover:bg-slate-800 transition">
                    
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-full bg-indigo-500/20 text-indigo-400 flex items-center justify-center font-semibold">
                            {{ substr($item->material->name, 0, 1) }}
                        </div>

                        <div>
                            <p class="text-sm font-medium text-slate-100">
                                {{ $item->material->name }}
                            </p>
                            <p class="text-xs text-slate-400">
                                {{ $item->created_at->diffForHumans() }}
                            </p>
                        </div>
                    </div>

                    <span class="text-sm font-semibold text-emerald-400">
                        +{{ $item->qty }} {{ $item->material->unit }}
                    </span>

                </div>
            @empty
                <p class="text-sm text-slate-500 text-center py-10">
                    Belum ada aktivitas material
                </p>
            @endforelse
        </div>

    </div>
    {{-- RINGKASAN --}}
<div class="bg-slate-900 border border-slate-800 rounded-xl p-6">
    
    <h3 class="font-semibold text-slate-100 mb-5">
        Ringkasan Gudang
    </h3>

    <div class="space-y-4 text-sm">
        
        <div class="flex justify-between">
            <span class="text-slate-400">Total Vendor</span>
            <span class="font-medium text-slate-100">
                {{ $totalVendor ?? 12 }}
            </span>
        </div>

        <div class="flex justify-between">
            <span class="text-slate-400">Total Material</span>
            <span class="font-medium text-slate-100">
                {{ $totalMaterial ?? 38 }}
            </span>
        </div>

        <div class="flex justify-between">
            <span class="text-slate-400">Transaksi Masuk</span>
            <span class="font-medium text-slate-100">
                {{ $materialIn ?? 124 }}
            </span>
        </div>

        <div class="flex justify-between">
            <span class="text-slate-400">Transaksi Keluar</span>
            <span class="font-medium text-slate-100">
                {{ $materialOut ?? 97 }}
            </span>
        </div>

    </div>

    <a href="{{ route('admin.materials.index') }}"
       class="mt-6 block text-center py-2.5 rounded-lg bg-slate-800 hover:bg-slate-700 text-sm font-medium text-slate-100 transition">
        Kelola Material
    </a>

</div>


</div>

    <div class="py-8 space-y-8">

        {{-- DASHBOARD RINGKASAN (OWNER LEVEL) --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">

            <div class="bg-slate-900 border border-slate-800 rounded-xl p-5">
                <p class="text-xs text-slate-400">Order Produksi Aktif</p>
                <h2 class="text-3xl font-bold text-indigo-400 mt-2">
                    {{ $activeProductionOrders ?? 5 }}
                </h2>
            </div>

            <div class="bg-slate-900 border border-slate-800 rounded-xl p-5">
                <p class="text-xs text-slate-400">Request Material Pending</p>
                <h2 class="text-3xl font-bold text-yellow-400 mt-2">
                    {{ $pendingRequests ?? 8 }}
                </h2>
            </div>

            <div class="bg-slate-900 border border-slate-800 rounded-xl p-5">
                <p class="text-xs text-slate-400">Produksi Selesai Hari Ini</p>
                <h2 class="text-3xl font-bold text-emerald-400 mt-2">
                    {{ $finishedToday ?? 3 }}
                </h2>
            </div>

            <div class="bg-slate-900 border border-slate-800 rounded-xl p-5">
                <p class="text-xs text-slate-400">Completion Rate</p>
                <h2 class="text-3xl font-bold text-emerald-500 mt-2">
                    {{ $completionRate ?? 87 }}%
                </h2>
            </div>

        </div>

        {{-- GRAFIK OWNER LEVEL --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            {{-- STOK MASUK VS KELUAR --}}
            <div class="bg-slate-900 border border-slate-800 rounded-xl p-6">
                <h3 class="font-semibold text-slate-100 mb-4">
                    Stok Masuk vs Keluar (Bulanan)
                </h3>
                <canvas id="stockChart" height="120"></canvas>
            </div>

            {{-- STATUS ORDER PRODUKSI --}}
            <div class="bg-slate-900 border border-slate-800 rounded-xl p-6">
                <h3 class="font-semibold text-slate-100 mb-4">
                    Status Order Produksi
                </h3>
                <canvas id="productionChart" height="120"></canvas>
            </div>

        </div>

        {{-- MONITORING ORDER PRODUKSI --}}
        <div class="bg-slate-900 border border-slate-800 rounded-xl p-6">
            <h3 class="font-semibold text-slate-100 mb-4">
                Monitoring Order Produksi
            </h3>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="text-slate-400 border-b border-slate-800">
                        <tr>
                            <th class="py-2 text-left">Kode</th>
                            <th class="py-2 text-left">Produk</th>
                            <th class="py-2 text-left">Target</th>
                            <th class="py-2 text-left">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($productionOrders ?? [] as $po)
                        <tr class="border-b border-slate-800">
                            <td class="py-2">{{ $po->order_code }}</td>
                            <td class="py-2">{{ $po->product->name }}</td>
                            <td class="py-2">{{ $po->target_qty }}</td>
                            <td class="py-2">
                                <span class="px-3 py-1 rounded-full text-xs
                                    @if($po->status=='waiting_material') bg-yellow-500/20 text-yellow-400
                                    @elseif($po->status=='on_progress') bg-blue-500/20 text-blue-400
                                    @else bg-emerald-500/20 text-emerald-400
                                    @endif">
                                    {{ str_replace('_',' ',$po->status) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-slate-500 py-4">
                                Tidak ada order produksi
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    {{-- CHART.JS --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
    /* ===============================
       STOK MASUK VS KELUAR
    =============================== */
    new Chart(document.getElementById('stockChart'), {
        type: 'line',
        data: {
            labels: {!! json_encode($stockMonths ?? ['Jan','Feb','Mar','Apr','Mei','Jun']) !!},
            datasets: [
                {
                    label: 'Stok Masuk',
                    data: {!! json_encode($stockInData ?? [120,150,180,140,200,170]) !!},
                    borderColor: '#22c55e',
                    tension: 0.4
                },
                {
                    label: 'Stok Keluar',
                    data: {!! json_encode($stockOutData ?? [100,130,160,120,180,150]) !!},
                    borderColor: '#ef4444',
                    tension: 0.4
                }
            ]
        },
        options: {
            plugins: {
                legend: { labels: { color: '#e5e7eb' } }
            },
            scales: {
                x: { ticks: { color: '#94a3b8' } },
                y: { ticks: { color: '#94a3b8' } }
            }
        }
    });

    /* ===============================
       STATUS ORDER PRODUKSI
    =============================== */
    new Chart(document.getElementById('productionChart'), {
        type: 'doughnut',
        data: {
            labels: ['Waiting Material', 'On Progress', 'Completed'],
            datasets: [{
                data: {!! json_encode($productionStatusData ?? [5,7,12]) !!},
                backgroundColor: ['#facc15','#3b82f6','#22c55e']
            }]
        },
        options: {
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { color: '#e5e7eb' }
                }
            }
        }
    });
    </script>

</x-app-layout>
