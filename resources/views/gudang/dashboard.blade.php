<x-app-layout>
    {{-- HEADER --}}
    <x-slot name="header">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                📦 Dashboard Gudang
            </h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">
                Ringkasan aktivitas dan kondisi stok gudang
            </p>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- QUICK ACTION --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @php
                    $actions = [
                        ['route'=>'gudang.material-in.create','title'=>'Material Masuk','desc'=>'Penerimaan bahan','color'=>'green'],
                        ['route'=>'gudang.material-out.create','title'=>'Material Keluar','desc'=>'Keluar ke produksi','color'=>'red'],
                        ['route'=>'gudang.product-in.index','title'=>'Produk Masuk','desc'=>'Dari produksi','color'=>'blue'],
                        ['route'=>'gudang.requests.index','title'=>'Request Bahan','desc'=>'Permintaan produksi','color'=>'yellow'],
                    ];
                @endphp

                @foreach($actions as $act)
                <a href="{{ route($act['route']) }}"
                   class="relative bg-white dark:bg-gray-900
                          rounded-2xl border border-gray-200 dark:border-gray-800
                          shadow hover:shadow-xl transition p-4">
                    <h4 class="font-semibold text-gray-900 dark:text-white">
                        {{ $act['title'] }}
                    </h4>
                    <p class="text-xs text-gray-500">{{ $act['desc'] }}</p>

                    @if($act['title'] === 'Request Bahan' && $pending_requests > 0)
                        <span class="absolute top-3 right-3 bg-red-600 text-white text-xs font-bold w-6 h-6 rounded-full flex items-center justify-center">
                            {{ $pending_requests }}
                        </span>
                    @endif
                </a>
                @endforeach
            </div>

            {{-- STAT --}}
           <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

    <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-2xl p-6 text-white shadow">
        <p class="text-sm opacity-80">Jenis Bahan</p>
        <p class="text-3xl font-bold">{{ $total_materials }}</p>
    </div>

    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl p-6 text-white shadow">
        <p class="text-sm opacity-80">Jenis Produk</p>
        <p class="text-3xl font-bold">{{ $total_products }}</p>
    </div>

    <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-2xl p-6 text-white shadow">
        <p class="text-sm opacity-80">Request Pending</p>
        <p class="text-3xl font-bold">{{ $pending_requests }}</p>
    </div>

    <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-2xl p-6 text-white shadow">
        <p class="text-sm opacity-80">Stok Rendah</p>
        <p class="text-3xl font-bold">{{ $low_stock_alerts->count() }}</p>
    </div>

</div>


            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- KIRI --}}
                <div class="lg:col-span-2 space-y-6">

                    {{-- ALERT STOK --}}
                    <div class="bg-white dark:bg-gray-900 rounded-2xl shadow border border-gray-200 dark:border-gray-800">
                        <div class="p-4 border-b bg-red-50 dark:bg-red-900/20">
                            <h3 class="font-semibold text-red-700 dark:text-red-300">
                                🚨 Stok Minimum
                            </h3>
                        </div>

                        <div class="p-6">
                            @forelse($low_stock_alerts as $item)
                                <div class="mb-4 p-4 rounded-xl bg-red-50 dark:bg-red-900/10 border">
                                    <h4 class="font-semibold">{{ $item->name }}</h4>
                                    <p class="text-xs text-gray-500">{{ $item->location ?? '-' }}</p>

                                    <div class="flex justify-between text-sm mt-2">
                                        <span>Stok</span>
                                        <b>{{ $item->current_stock }} {{ $item->unit }}</b>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span>Minimal</span>
                                        <b class="text-red-600">{{ $item->min_stock }}</b>
                                    </div>
                                </div>
                            @empty
                                <p class="text-center text-green-600">
                                    ✅ Semua stok aman
                                </p>
                            @endforelse
                        </div>
                    </div>

                    {{-- TRANSAKSI --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        {{-- MASUK --}}
                        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow border">
                            <div class="p-4 border-b bg-green-50">
                                <h4 class="font-semibold text-green-700">Material Masuk</h4>
                            </div>
                            <div class="p-4 space-y-3 max-h-80 overflow-y-auto">
                                @forelse($recent_material_in as $row)
                                    <div>
                                        <h6 class="font-medium">{{ $row->material->name }}</h6>
                                        <span class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded">
                                            +{{ $row->qty }} {{ $row->material->unit }}
                                        </span>
                                    </div>
                                @empty
                                    <p class="text-center text-gray-500">Belum ada data</p>
                                @endforelse
                            </div>
                        </div>

                        {{-- KELUAR --}}
                        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow border">
                            <div class="p-4 border-b bg-red-50">
                                <h4 class="font-semibold text-red-700">Material Keluar</h4>
                            </div>
                            <div class="p-4 space-y-3 max-h-80 overflow-y-auto">
                                @forelse($recent_material_out as $row)
                                    <div>
                                        <h6 class="font-medium">{{ $row->material->name }}</h6>
                                        <span class="text-xs bg-red-100 text-red-700 px-2 py-1 rounded">
                                            -{{ $row->qty }} {{ $row->material->unit }}
                                        </span>
                                    </div>
                                @empty
                                    <p class="text-center text-gray-500">Belum ada data</p>
                                @endforelse
                            </div>
                        </div>

                    </div>
                </div>

                {{-- SIDEBAR --}}
                <div class="space-y-6">

                    {{-- REQUEST --}}
                    <div class="bg-white dark:bg-gray-900 rounded-2xl shadow border">
                        <div class="p-4 border-b bg-yellow-50">
                            <h4 class="font-semibold text-yellow-700">Request Pending</h4>
                        </div>
                        <div class="p-4 space-y-3">
                            @forelse($pending_material_requests as $req)
                                <div>
                                    <h6 class="font-medium">{{ $req->product->name }}</h6>
                                    <a href="{{ route('gudang.requests.index') }}"
                                       class="text-xs text-white bg-yellow-600 px-3 py-1 rounded">
                                        Proses
                                    </a>
                                </div>
                            @empty
                                <p class="text-center text-green-600">Tidak ada</p>
                            @endforelse
                        </div>
                    </div>

                    {{-- INCOMING PRODUCTS --}}
                    <div class="bg-white dark:bg-gray-900 rounded-2xl shadow border">
                        <div class="p-4 border-b bg-blue-50">
                            <h4 class="font-semibold text-blue-700">Produk Masuk</h4>
                        </div>
                        <div class="p-4 space-y-3">
                            @forelse($incoming_finished_products as $order)
                                <div>
                                    <h6 class="font-medium">{{ $order->product->name }}</h6>
                                    <span class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded">
                                        +{{ $order->productIn->first()?->qty ?? 0 }} pcs
                                    </span>
                                    <p class="text-xs text-gray-500">{{ $order->end_date?->format('d/m/Y') }}</p>
                                </div>
                            @empty
                                <p class="text-center text-gray-500">Belum ada produk masuk</p>
                            @endforelse
                        </div>
                    </div>

                    {{-- AKTIVITAS --}}
                    <div class="bg-white dark:bg-gray-900 rounded-2xl shadow border p-4">
                        <h4 class="font-semibold mb-3">Aktivitas Hari Ini</h4>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between"><span>Material Masuk</span><b class="text-green-600">{{ $today_material_in }}</b></div>
                            <div class="flex justify-between"><span>Material Keluar</span><b class="text-red-600">{{ $today_material_out }}</b></div>
                            <div class="flex justify-between"><span>Produk Masuk</span><b class="text-blue-600">{{ $today_product_in }}</b></div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>
