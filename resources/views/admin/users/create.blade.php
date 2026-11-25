<x-app-layout>
    {{-- HEADER --}}
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                    ➕ Tambah Pengguna
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Tambahkan akun pengguna baru ke sistem
                </p>
            </div>

            <a href="{{ route('admin.users.index') }}"
               class="inline-flex items-center gap-2 px-4 py-2 rounded-xl
                      bg-gray-200 dark:bg-gray-800
                      text-gray-700 dark:text-gray-200
                      hover:bg-gray-300 dark:hover:bg-gray-700 transition">
                ← Kembali
            </a>
        </div>
    </x-slot>

    {{-- CONTENT --}}
    <div class="py-10">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white dark:bg-gray-900
                        rounded-2xl shadow-xl
                        border border-gray-200 dark:border-gray-800 p-6">

                <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-6">
                    @csrf

                    {{-- INFORMASI DASAR --}}
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">
                            Informasi Pengguna
                        </h3>

                        <div class="space-y-4">
                            {{-- Nama --}}
                            <div>
                                <x-input-label for="name" value="Nama Lengkap *" />
                                <x-text-input id="name" name="name" type="text"
                                              value="{{ old('name') }}"
                                              required
                                              class="mt-1 block w-full" />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>

                            {{-- Email --}}
                            <div>
                                <x-input-label for="email" value="Email *" />
                                <x-text-input id="email" name="email" type="email"
                                              value="{{ old('email') }}"
                                              required
                                              class="mt-1 block w-full" />
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>

                            {{-- Role --}}
                            <div>
                                <x-input-label for="role" value="Role Pengguna *" />
                                <select id="role" name="role" required
                                        class="mt-1 block w-full rounded-xl
                                               bg-gray-100 dark:bg-gray-800
                                               border-gray-300 dark:border-gray-700
                                               text-gray-900 dark:text-white
                                               focus:ring-2 focus:ring-blue-500">
                                    <option value="">-- Pilih Role --</option>
                                    <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="gudang" {{ old('role') === 'gudang' ? 'selected' : '' }}>Gudang</option>
                                    <option value="produksi" {{ old('role') === 'produksi' ? 'selected' : '' }}>Produksi</option>
                                </select>
                                <x-input-error :messages="$errors->get('role')" class="mt-2" />
                            </div>
                        </div>
                    </div>

                    {{-- KEAMANAN --}}
                    <div class="pt-6 border-t dark:border-gray-800">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">
                            Keamanan Akun
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            {{-- Password --}}
                            <div>
                                <x-input-label for="password" value="Password *" />
                                <x-text-input id="password" name="password" type="password"
                                              required
                                              class="mt-1 block w-full" />
                                <x-input-error :messages="$errors->get('password')" class="mt-2" />
                            </div>

                            {{-- Konfirmasi --}}
                            <div>
                                <x-input-label for="password_confirmation" value="Konfirmasi Password *" />
                                <x-text-input id="password_confirmation" name="password_confirmation"
                                              type="password"
                                              required
                                              class="mt-1 block w-full" />
                                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                            </div>
                        </div>
                    </div>

                    {{-- ACTION --}}
                    <div class="flex justify-end gap-3 pt-6 border-t dark:border-gray-800">
                        <a href="{{ route('admin.users.index') }}"
                           class="px-4 py-2 rounded-xl
                                  bg-gray-200 dark:bg-gray-800
                                  text-gray-700 dark:text-gray-200">
                            Batal
                        </a>

                        <button type="submit"
                                class="px-6 py-2 rounded-xl
                                       bg-gradient-to-r from-blue-600 to-indigo-600
                                       text-white font-semibold
                                       shadow-lg hover:opacity-90">
                            💾 Simpan Pengguna
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
