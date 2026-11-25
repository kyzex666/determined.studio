<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="p-2 bg-indigo-100 dark:bg-indigo-900 text-indigo-600 dark:text-indigo-300 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M20 13V7a2 2 0 00-2-2H6a2 2 0 00-2 2v6m16 0v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4m16 0H4" />
                </svg>
            </div>
            <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100">
                Tambah Material Masuk
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl overflow-hidden">

                {{-- Header Card --}}
                <div class="px-6 py-4 bg-gradient-to-r from-indigo-600 to-blue-600">
                    <h3 class="text-white font-semibold text-lg">
                        Form Penerimaan Material Gudang
                    </h3>
                    <p class="text-indigo-100 text-sm">
                        Isi data material yang masuk ke gudang
                    </p>
                </div>

                {{-- Form --}}
                <div class="p-6">
                    <form action="{{ route('gudang.material-in.store') }}" method="POST" class="space-y-6">
                        @csrf

                        {{-- Vendor --}}
                        <div>
                            <x-input-label for="vendor_id" value="Vendor" />
                            <select id="vendor_id" name="vendor_id" required
                                class="mt-1 block w-full rounded-xl
                                       bg-gray-50 dark:bg-gray-700
                                       border-gray-300 dark:border-gray-600
                                       text-gray-900 dark:text-gray-100
                                       focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Pilih Vendor</option>
                                @foreach($vendors as $vendor)
                                    <option value="{{ $vendor->id }}"
                                        {{ old('vendor_id') == $vendor->id ? 'selected' : '' }}>
                                        {{ $vendor->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('vendor_id')" class="mt-2" />
                        </div>

                        {{-- Material --}}
                        <div>
                            <x-input-label for="material_id" value="Material" />
                            <select id="material_id" name="material_id" required
                                class="mt-1 block w-full rounded-xl
                                       bg-gray-50 dark:bg-gray-700
                                       border-gray-300 dark:border-gray-600
                                       text-gray-900 dark:text-gray-100
                                       focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Pilih Material</option>
                                @foreach($materials as $material)
                                    <option value="{{ $material->id }}"
                                        {{ old('material_id') == $material->id ? 'selected' : '' }}>
                                        {{ $material->name }} ({{ $material->unit }})
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('material_id')" class="mt-2" />
                        </div>

                        {{-- Grid --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            {{-- Quantity --}}
                            <div>
                                <x-input-label for="qty" value="Jumlah Masuk" />
                                <x-text-input
                                    id="qty"
                                    type="number"
                                    step="0.01"
                                    name="qty"
                                    value="{{ old('qty') }}"
                                    required
                                    class="mt-1 block w-full rounded-xl
                                           bg-gray-50 dark:bg-gray-700
                                           border-gray-300 dark:border-gray-600
                                           text-gray-900 dark:text-gray-100"
                                />
                                <x-input-error :messages="$errors->get('qty')" class="mt-2" />
                            </div>

                            {{-- Tanggal --}}
                            <div>
                                <x-input-label for="date" value="Tanggal" />
                                <x-text-input
                                    id="date"
                                    type="date"
                                    name="date"
                                    value="{{ old('date', now()->toDateString()) }}"
                                    required
                                    class="mt-1 block w-full rounded-xl
                                           bg-gray-50 dark:bg-gray-700
                                           border-gray-300 dark:border-gray-600
                                           text-gray-900 dark:text-gray-100"
                                />
                                <x-input-error :messages="$errors->get('date')" class="mt-2" />
                            </div>
                        </div>

                        {{-- Catatan --}}
                        <div>
                            <x-input-label for="note" value="Catatan (Opsional)" />
                            <textarea id="note" name="note" rows="3"
                                class="mt-1 block w-full rounded-xl
                                       bg-gray-50 dark:bg-gray-700
                                       border-gray-300 dark:border-gray-600
                                       text-gray-900 dark:text-gray-100
                                       focus:border-indigo-500 focus:ring-indigo-500">{{ old('note') }}</textarea>
                        </div>

                        {{-- Action --}}
                        <div class="flex justify-between items-center pt-6 border-t border-gray-200 dark:border-gray-700">
                            <a href="{{ route('gudang.material-in.index') }}"
                               class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white">
                                ← Kembali
                            </a>

                            <x-primary-button class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700">
                                Simpan Material
                            </x-primary-button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
