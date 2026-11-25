<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                    👤 Data Pengguna
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Manajemen akun & hak akses sistem
                </p>
            </div>

            <a href="{{ route('admin.users.create') }}"
               class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl
                      bg-gradient-to-r from-blue-600 to-indigo-600
                      text-white font-semibold shadow-lg hover:opacity-90 transition">
                ➕ Tambah Pengguna
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- SEARCH -->
            <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-xl
                        border border-gray-200 dark:border-gray-800 p-6">
                <div class="flex flex-col md:flex-row gap-3">
                    <input id="searchInput"
                           type="text"
                           placeholder="Cari nama atau email pengguna..."
                           class="flex-1 px-4 py-3 rounded-xl
                                  bg-gray-100 dark:bg-gray-800
                                  border border-gray-300 dark:border-gray-700
                                  text-gray-900 dark:text-white
                                  focus:ring-2 focus:ring-blue-500">

                    <button id="resetButton"
                            class="px-4 py-3 rounded-xl
                                   bg-red-500 hover:bg-red-600
                                   text-white font-semibold transition">
                        Reset
                    </button>
                </div>
            </div>

            <!-- TABLE -->
            <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-xl
                        border border-gray-200 dark:border-gray-800 overflow-hidden">

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-100 dark:bg-gray-800">
                            <tr class="text-left text-xs uppercase tracking-wider
                                       text-gray-600 dark:text-gray-400">
                                <th class="px-6 py-4">#</th>
                                <th class="px-6 py-4">Nama</th>
                                <th class="px-6 py-4">Email</th>
                                <th class="px-6 py-4">Role</th>
                                <th class="px-6 py-4 text-center">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                            @foreach($users as $user)
                                <tr class="hover:bg-blue-50 dark:hover:bg-gray-800 transition">

                                    <td class="px-6 py-4 text-gray-700 dark:text-gray-300">
                                        {{ $loop->iteration }}
                                    </td>

                                    <td class="px-6 py-4">
                                        <div class="font-semibold text-gray-900 dark:text-white">
                                            {{ $user->name }}
                                        </div>
                                    </td>

                                    <td class="px-6 py-4 text-gray-600 dark:text-gray-400">
                                        {{ $user->email }}
                                    </td>

                                    <td class="px-6 py-4">
                                        @php
                                            $role = strtolower($user->role ?? 'pengguna');

                                            $roleClass = match($role) {
                                                'admin' => 'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300',
                                                'gudang' => 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300',
                                                'produksi' => 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900 dark:text-indigo-300',
                                                default => 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300'
                                            };
                                        @endphp

                                        <span class="px-3 py-1 rounded-lg text-xs font-bold {{ $roleClass }}">
                                            {{ ucfirst($user->role ?? 'Pengguna') }}
                                        </span>
                                    </td>

                                    <td class="px-6 py-4">
                                        <div class="flex justify-center gap-4 text-lg">

                                            <a href="{{ route('admin.users.edit', $user) }}"
                                               class="text-yellow-500 hover:text-yellow-600"
                                               title="Edit Pengguna">
                                                ✏️
                                            </a>

                                            <form action="{{ route('admin.users.destroy', $user) }}"
                                                  method="POST"
                                                  onsubmit="return confirm('Yakin ingin menghapus pengguna ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="text-red-500 hover:text-red-600"
                                                        title="Hapus Pengguna">
                                                    🗑️
                                                </button>
                                            </form>

                                        </div>
                                    </td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($users->isEmpty())
                    <div class="py-10 text-center text-gray-500 dark:text-gray-400">
                        Belum ada data pengguna
                    </div>
                @endif

            </div>

        </div>
    </div>

    @push('scripts')
    <script>
        const searchInput = document.getElementById('searchInput');
        const resetButton = document.getElementById('resetButton');

        searchInput.addEventListener('input', () => {
            const term = searchInput.value.toLowerCase();
            document.querySelectorAll('tbody tr').forEach(row => {
                row.style.display = row.textContent.toLowerCase().includes(term)
                    ? ''
                    : 'none';
            });
        });

        resetButton.addEventListener('click', () => {
            searchInput.value = '';
            document.querySelectorAll('tbody tr').forEach(row => {
                row.style.display = '';
            });
        });
    </script>
    @endpush
</x-app-layout>
