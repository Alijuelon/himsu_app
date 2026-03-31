<x-app-layout>
    <x-slot name="header">
        No Whatsap Anggota
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            <div class="mb-6 flex flex-col sm:flex-row justify-between items-center gap-4">
                <form action="{{ route('admin.wa.members') }}" method="GET" class="w-full sm:w-1/3 relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <i class="fa-solid fa-search text-gray-400"></i>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}"
                        class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-brand focus:border-brand block w-full pl-10 p-2.5 dark:bg-gray-800 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                        placeholder="Cari nama anggota...">
                </form>
            </div>

            <div
                class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg overflow-hidden border border-gray-100 dark:border-white/5">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead
                            class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700/50 dark:text-gray-400 border-b border-gray-100 dark:border-white/5">
                            <tr>
                                <th scope="col" class="px-6 py-4">Nama Lengkap</th>
                                <th scope="col" class="px-6 py-4">Nomor WhatsApp</th>
                                <th scope="col" class="px-6 py-4">Status WA</th>
                                <th scope="col" class="px-6 py-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($members as $member)
                                <tr
                                    class="bg-white border-b border-gray-100 dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition duration-150">
                                    <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                        {{ $member->nama_lengkap }}
                                    </td>

                                    <td colspan="3" class="p-0 border-none">
                                        <form action="{{ route('admin.wa.members.update', $member->id) }}"
                                            method="POST" class="flex items-center w-full">
                                            @csrf
                                            @method('PUT')

                                            <div class="px-6 py-4 flex-1">
                                                <input type="text" name="no_hp" value="{{ $member->no_hp }}"
                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-brand focus:border-brand block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                                                    placeholder="Contoh: 08123456789">
                                            </div>

                                            <div class="px-6 py-4 w-32 text-center">
                                                @if ($member->no_hp)
                                                    <span
                                                        class="bg-green-100 text-green-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300">Ada</span>
                                                @else
                                                    <span
                                                        class="bg-red-100 text-red-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-red-900 dark:text-red-300">Kosong</span>
                                                @endif
                                            </div>

                                            <div class="px-6 py-4 w-32 text-right">
                                                <button type="submit"
                                                    class="text-white bg-brand hover:bg-brand/90 focus:ring-4 focus:outline-none focus:ring-brand/50 font-medium rounded-lg text-sm px-4 py-2 text-center inline-flex items-center dark:bg-brand dark:hover:bg-brand/90 dark:focus:ring-brand/50">
                                                    <i class="fa-solid fa-save mr-2"></i> Simpan
                                                </button>
                                            </div>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                                        <div class="flex flex-col items-center justify-center">
                                            <i
                                                class="fa-solid fa-users text-4xl mb-3 text-gray-300 dark:text-gray-600"></i>
                                            <p class="text-sm">Tidak ada data anggota ditemukan.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-4">
                {{ $members->links() }}
            </div>

        </div>
    </div>
</x-app-layout>
