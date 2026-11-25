<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-white leading-tight">
                Edit Vendor:
                <span class="text-blue-400">{{ $vendor->name }}</span>
            </h2>

            <a href="{{ route('admin.vendors.show', $vendor) }}"
               class="inline-flex items-center px-4 py-2 rounded-xl
                      bg-slate-700 text-slate-200 hover:bg-slate-600 transition">
                👁 Detail
            </a>
        </div>
    </x-slot>

    <div class="py-10 bg-slate-900 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- FORM -->
                <div class="lg:col-span-2 bg-slate-800 rounded-2xl shadow-xl p-6 border border-slate-700">

                    <form action="{{ route('admin.vendors.update', $vendor) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <!-- Nama & Kode -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                            <div>
                                <x-input-label for="name" value="Nama Vendor" class="text-slate-300"/>
                                <x-text-input
                                    id="name"
                                    name="name"
                                    value="{{ old('name', $vendor->name) }}"
                                    required
                                    class="mt-1 block w-full rounded-xl
                                           bg-slate-900 text-white border-slate-700
                                           focus:border-blue-500 focus:ring-blue-500"
                                />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="vendor_code" value="Kode Vendor" class="text-slate-300"/>
                                <x-text-input
                                    id="vendor_code"
                                    name="vendor_code"
                                    value="{{ $vendor->vendor_code }}"
                                    readonly
                                    class="mt-1 block w-full rounded-xl
                                           bg-slate-700 text-slate-400 border-slate-600 cursor-not-allowed"
                                />
                                <p class="text-xs text-slate-400 mt-1">
                                    Kode vendor tidak dapat diubah
                                </p>
                            </div>

                        </div>

                        <!-- Kontak -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                            <div>
                                <x-input-label for="phone" value="Telepon" class="text-slate-300"/>
                                <x-text-input
                                    id="phone"
                                    name="phone"
                                    value="{{ old('phone', $vendor->phone) }}"
                                    class="mt-1 block w-full rounded-xl
                                           bg-slate-900 text-white border-slate-700
                                           focus:border-blue-500 focus:ring-blue-500"
                                />
                            </div>

                            <div>
                                <x-input-label for="email" value="Email" class="text-slate-300"/>
                                <x-text-input
                                    id="email"
                                    type="email"
                                    name="email"
                                    value="{{ old('email', $vendor->email) }}"
                                    class="mt-1 block w-full rounded-xl
                                           bg-slate-900 text-white border-slate-700
                                           focus:border-blue-500 focus:ring-blue-500"
                                />
                            </div>

                        </div>

                        <!-- Alamat -->
                        <div>
                            <x-input-label for="address" value="Alamat" class="text-slate-300"/>
                            <textarea
                                id="address"
                                name="address"
                                rows="3"
                                class="mt-1 block w-full rounded-xl
                                       bg-slate-900 text-white border-slate-700
                                       focus:border-blue-500 focus:ring-blue-500"
                            >{{ old('address', $vendor->address) }}</textarea>
                        </div>

                        <!-- Catatan -->
                        <div>
                            <x-input-label for="notes" value="Catatan" class="text-slate-300"/>
                            <textarea
                                id="notes"
                                name="notes"
                                rows="2"
                                class="mt-1 block w-full rounded-xl
                                       bg-slate-900 text-white border-slate-700
                                       focus:border-blue-500 focus:ring-blue-500"
                            >{{ old('notes', $vendor->notes) }}</textarea>
                        </div>

                        <!-- ACTION -->
                        <div class="flex justify-between items-center pt-4 border-t border-slate-700">

                            <a href="{{ route('admin.vendors.index') }}"
                               class="px-4 py-2 rounded-xl
                                      bg-slate-700 text-slate-200 hover:bg-slate-600 transition">
                                ← Kembali
                            </a>

                            <x-primary-button class="rounded-xl px-6 bg-blue-600 hover:bg-blue-700">
                                💾 Update Vendor
                            </x-primary-button>

                        </div>

                    </form>
                </div>

                <!-- INFO PANEL -->
                <aside class="bg-slate-800 rounded-2xl shadow-xl p-6 border border-slate-700">

                    <h3 class="font-semibold text-white mb-4">
                        📌 Informasi Vendor
                    </h3>

                    <div class="text-sm text-slate-300 space-y-3">

                        <div class="flex justify-between">
                            <span>Total Transaksi</span>
                            <span class="font-medium">
                                {{ $vendor->material_in_count ?? 0 }} transaksi
                            </span>
                        </div>

                        <div class="flex justify-between">
                            <span>Dibuat</span>
                            <span>{{ $vendor->created_at->format('d/m/Y H:i') }}</span>
                        </div>

                        <div class="flex justify-between">
                            <span>Terakhir Update</span>
                            <span>{{ $vendor->updated_at->format('d/m/Y H:i') }}</span>
                        </div>

                    </div>
                </aside>

            </div>
        </div>
    </div>
</x-app-layout>
