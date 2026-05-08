<x-app-layout>
    <x-slot name="header">
        Verifikasi Akun Anggota
    </x-slot>

    <div class="bg-white dark:bg-navy-700 rounded-xl shadow-sm p-6 border border-transparent dark:border-white/5 transition-colors">
        
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-6 gap-4">
            <h3 class="text-lg font-bold text-darkText dark:text-white">Daftar Akun Menunggu Verifikasi</h3>
        </div>
        
        @if(session('success'))
            <div class="mb-4 p-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800" role="alert">
                <span class="font-medium">Berhasil!</span> {{ session('success') }}
            </div>
        @endif

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-gray-400 dark:text-gray-400 text-xs tracking-wide uppercase border-b border-gray-100 dark:border-white/10">
                        <th class="py-3 px-4 font-medium">Nama Lengkap</th>
                        <th class="py-3 px-4 font-medium">Kontak</th>
                        <th class="py-3 px-4 font-medium text-center">Status Verifikasi</th>
                        <th class="py-3 px-4 font-medium text-center">Aksi Verifikasi</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-600 dark:text-gray-300">
                    @forelse ($users as $item)
                        <tr class="border-b border-gray-50 dark:border-white/5 hover:bg-gray-50/50 dark:hover:bg-white/5 transition">
                            <td class="py-4 px-4">
                                <p class="font-bold text-darkText dark:text-white">{{ $item->nama_lengkap }}</p>
                                <p class="text-xs text-gray-400">Terdaftar: {{ $item->created_at->diffForHumans() }}</p>
                            </td>
                            <td class="py-4 px-4">
                                <p class="font-medium text-brand dark:text-brandHover">{{ $item->email }}</p>
                                <p class="text-xs text-gray-400"><i class="fa-solid fa-phone mr-1"></i> {{ $item->no_hp ?? '-' }}</p>
                            </td>
                            <td class="py-4 px-4 text-center">
                                @if($item->status_verifikasi === 'pending')
                                    <span class="inline-flex items-center px-2 py-1 bg-yellow-50 dark:bg-yellow-500/10 text-yellow-600 dark:text-yellow-400 font-bold rounded-md text-[10px] uppercase tracking-wider">Pending</span>
                                @elseif($item->status_verifikasi === 'verified')
                                    <span class="inline-flex items-center px-2 py-1 bg-green-50 dark:bg-green-500/10 text-green-600 dark:text-green-400 font-bold rounded-md text-[10px] uppercase tracking-wider">Terverifikasi</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 font-bold rounded-md text-[10px] uppercase tracking-wider">Ditolak</span>
                                @endif
                                
                                @if($item->tipe_anggota)
                                    <div class="mt-1 text-xs text-gray-500">{{ str_replace('_', ' ', Str::title($item->tipe_anggota)) }}</div>
                                @endif
                            </td>
                            <td class="py-4 px-4">
                                <form action="{{ route('admin.verifikasi.update', $item->id) }}" method="POST" class="flex flex-col sm:flex-row items-center gap-2 justify-center">
                                    @csrf
                                    @method('PUT')
                                    <select name="status_verifikasi" class="text-sm bg-lightBg dark:bg-navy-800 border-none rounded-lg focus:ring-2 focus:ring-brand text-gray-700 dark:text-gray-300 w-32 py-2">
                                        <option value="pending" {{ $item->status_verifikasi == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="verified" {{ $item->status_verifikasi == 'verified' ? 'selected' : '' }}>Terima</option>
                                        <option value="rejected" {{ $item->status_verifikasi == 'rejected' ? 'selected' : '' }}>Tolak</option>
                                    </select>
                                    
                                    <select name="tipe_anggota" class="text-sm bg-lightBg dark:bg-navy-800 border-none rounded-lg focus:ring-2 focus:ring-brand text-gray-700 dark:text-gray-300 w-36 py-2">
                                        <option value="">-- Tipe --</option>
                                        <option value="anggota" {{ $item->tipe_anggota == 'anggota' ? 'selected' : '' }}>Anggota Aktif</option>
                                        <option value="bukan_anggota" {{ $item->tipe_anggota == 'bukan_anggota' ? 'selected' : '' }}>Bukan Anggota</option>
                                    </select>
                                    
                                    <button type="submit" class="bg-brand hover:bg-brandHover text-white px-3 py-2 rounded-lg text-sm font-semibold transition">
                                        <i class="fa-solid fa-check"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-10 text-center text-gray-400">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="fa-solid fa-folder-open text-4xl mb-3 text-gray-300 dark:text-gray-600"></i>
                                    <p>Tidak ada akun untuk diverifikasi.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</x-app-layout>
