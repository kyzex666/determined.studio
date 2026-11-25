<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Permintaan Material Produksi
        </h2>
    </x-slot>

    <div class="py-8 max-w-7xl mx-auto sm:px-6 lg:px-8">

        <!-- Statistik -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-blue-600 text-white p-5 rounded-xl">
                <div class="text-sm">Total Pending</div>
                <div class="text-3xl font-bold">{{ $totalPending }}</div>
            </div>

            <div class="bg-red-500 text-white p-5 rounded-xl">
                <div class="text-sm">High</div>
                <div class="text-3xl font-bold">{{ $totalPriority['high'] }}</div>
            </div>

            <div class="bg-yellow-500 text-white p-5 rounded-xl">
                <div class="text-sm">Medium</div>
                <div class="text-3xl font-bold">{{ $totalPriority['medium'] }}</div>
            </div>

            <div class="bg-green-600 text-white p-5 rounded-xl">
                <div class="text-sm">Low</div>
                <div class="text-3xl font-bold">{{ $totalPriority['low'] }}</div>
            </div>
        </div>

        <!-- Table -->
        <div class="rounded-xl shadow overflow-hidden">
            <table class="w-full text-sm">
                <thead class="text-left">
                    <tr>
                        <th class="p-4">Produk</th>
                        <th>Priority</th>
                        <th>Tanggal</th>
                        <th>PIC</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($requests as $request)
                        <tr class="border-t">
                            <td class="p-4 font-medium">
                                {{ $request->product->name ?? '-' }}
                            </td>
                            <td>{{ strtoupper($request->priority) }}</td>
                            <td>{{ $request->created_at->format('d M Y') }}</td>
                            <td>{{ $request->createdByUser->name ?? '-' }}</td>
                            <td>
                                <a href="{{ route('gudang.requests.show', "$request->id") }}"
                                   class="text-blue-600 hover:underline">
                                    Detail Produk
                                </a>
                                            <!-- PROCESS -->
            <form action="{{ route('gudang.requests.process', $request->id) }}"
                  method="POST"
                  class="inline">
                @csrf
                <button type="submit"
                        onclick="return confirm('Proses request ini?')"
                        class="text-green-600 hover:underline">
                    Process
                </button>
            </form>

            <!-- REJECT -->
            <form action="{{ route('gudang.requests.reject', $request->id) }}"
                  method="POST"
                  class="inline">
                @csrf
                <input type="hidden" name="rejection_reason" value="Ditolak oleh gudang">
                <button type="submit"
                        onclick="return confirm('Tolak request ini?')"
                        class="text-red-600 hover:underline">
                    Reject
                </button>
            </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center p-6 text-gray-500">
                                Tidak ada permintaan
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $requests->links() }}
        </div>

    </div>
</x-app-layout>
