<x-app-layout>
    <x-slot name="header">
        Catatan Pemasukan Kas
    </x-slot>

    <div class="mb-6 flex items-center p-5 bg-gradient-to-r from-green-500 to-green-400 rounded-xl shadow-sm text-white border border-transparent dark:border-white/5">
        <div class="p-4 bg-white/20 rounded-full backdrop-blur-sm">
            <i class="fa-solid fa-arrow-trend-up text-2xl w-6 text-center"></i>
        </div>
        <div class="ml-4">
            <h4 class="text-sm font-medium text-green-50">Total Pemasukan Keseluruhan</h4>
            <div class="text-3xl font-bold mt-1">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</div>
        </div>
    </div>

    <div class="bg-white dark:bg-navy-700 rounded-xl shadow-sm p-6 border border-transparent dark:border-white/5 transition-colors" x-data="{ modalTambah: false }">
        
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-6 gap-4" x-data="{ search: '{{ request('search') }}' }">
            <h3 class="text-lg font-bold text-darkText dark:text-white">Daftar Pemasukan</h3>
            
            <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto">
                <form action="{{ route('admin.bukukas.pemasukan') }}" method="GET" class="relative w-full sm:w-64">
                    <input type="text" name="search" x-model="search" placeholder="Cari kategori/keterangan..." 
                           class="w-full pl-10 pr-10 py-2.5 bg-lightBg dark:bg-navy-800 border-none rounded-xl text-sm focus:ring-2 focus:ring-green-500 text-gray-700 dark:text-gray-300 transition-all placeholder-gray-400">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fa-solid fa-magnifying-glass text-gray-400"></i>
                    </div>
                    <button type="button" x-show="search.length > 0" style="display: none;" @click="search = ''; $el.closest('form').submit()" 
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-red-500 transition">
                        <i class="fa-solid fa-circle-xmark"></i>
                    </button>
                </form>

                <button @click="modalTambah = true" type="button" class="bg-green-500 text-white text-sm font-semibold px-4 py-2.5 rounded-xl hover:bg-green-600 transition shadow-sm flex items-center justify-center whitespace-nowrap">
                    <i class="fa-solid fa-plus mr-2"></i> Tambah Pemasukan
                </button>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-gray-400 dark:text-gray-400 text-xs tracking-wide uppercase border-b border-gray-100 dark:border-white/10">
                        <th class="py-3 px-4 font-medium">Tanggal</th>
                        <th class="py-3 px-4 font-medium">Kategori</th>
                        <th class="py-3 px-4 font-medium">Keterangan</th>
                        <th class="py-3 px-4 font-medium text-right">Nominal</th>
                        <th class="py-3 px-4 font-medium text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-600 dark:text-gray-300">
                    @forelse ($pemasukan as $item)
                        <tr class="border-b border-gray-50 dark:border-white/5 hover:bg-gray-50/50 dark:hover:bg-white/5 transition">
                            <td class="py-4 px-4 font-bold text-darkText dark:text-white whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d M Y') }}
                            </td>
                            <td class="py-4 px-4">
                                <span class="px-2.5 py-1 bg-green-50 text-green-600 dark:bg-green-500/10 dark:text-green-400 rounded-lg text-xs font-bold">{{ $item->kategori }}</span>
                            </td>
                            <td class="py-4 px-4">
                                <p class="font-medium truncate max-w-[200px]" title="{{ $item->keterangan }}">{{ $item->keterangan ?? '-' }}</p>
                                <p class="text-[10px] text-gray-400 mt-0.5">Oleh: {{ $item->pencatat->nama_lengkap ?? 'Sistem' }}</p>
                            </td>
                            <td class="py-4 px-4 font-bold text-darkText dark:text-white text-right whitespace-nowrap">
                                Rp {{ number_format($item->nominal, 0, ',', '.') }}
                            </td>
                            <td class="py-4 px-4 text-center">
                                @if($item->kategori !== 'Iuran Kas')
                                    <div x-data="{ openModalHapus: false }" class="inline">
                                        <button @click="openModalHapus = true" type="button" class="p-2 bg-red-50 dark:bg-red-500/10 text-red-500 rounded-lg hover:bg-red-100 transition" title="Hapus Data">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                        <template x-teleport="body">
                                            <div x-show="openModalHapus" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center px-4">
                                                <div x-show="openModalHapus" @click="openModalHapus = false" class="fixed inset-0 bg-gray-900/40 backdrop-blur-sm transition-opacity"></div>
                                                <div x-show="openModalHapus" class="relative bg-white dark:bg-navy-800 rounded-2xl shadow-2xl p-6 w-full max-w-sm overflow-hidden text-center border border-gray-100 dark:border-white/10">
                                                    <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-50 dark:bg-red-500/10 mb-5">
                                                        <i class="fa-solid fa-triangle-exclamation text-3xl text-red-500"></i>
                                                    </div>
                                                    <h3 class="text-xl font-bold text-darkText dark:text-white mb-2">Hapus Catatan?</h3>
                                                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">Data ini akan dihapus dan total kas akan otomatis berkurang.</p>
                                                    <div class="flex justify-center gap-3">
                                                        <button @click="openModalHapus = false" type="button" class="w-full px-5 py-2.5 bg-gray-100 hover:bg-gray-200 dark:bg-navy-700 dark:hover:bg-navy-600 text-gray-700 dark:text-gray-300 font-medium rounded-xl transition-colors">Batal</button>
                                                        <form action="{{ route('admin.bukukas.destroy', $item->id) }}" method="POST" class="w-full">
                                                            @csrf @method('DELETE')
                                                            <button type="submit" class="w-full px-5 py-2.5 bg-red-500 hover:bg-red-600 text-white font-bold rounded-xl transition-colors shadow-sm">Ya, Hapus</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                @else
                                    <span class="text-[10px] italic text-gray-400">Otomatis dari Sistem</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-10 text-center text-gray-400">Belum ada catatan pemasukan kas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($pemasukan->hasPages())
            <div class="mt-6 pt-4 border-t border-gray-100 dark:border-white/10">
                {{ $pemasukan->links() }}
            </div>
        @endif

        <template x-teleport="body">
            <div x-show="modalTambah" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center px-4">
                <div x-show="modalTambah" @click="modalTambah = false" x-transition.opacity class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm"></div>
                
                <div x-show="modalTambah" x-transition.scale.origin.bottom class="relative bg-white dark:bg-navy-800 rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden text-left border border-gray-100 dark:border-white/10">
                    <div class="px-6 py-4 border-b border-gray-100 dark:border-white/10 flex items-center justify-between bg-green-50 dark:bg-green-500/10">
                        <h3 class="text-lg font-bold text-green-600 dark:text-green-400">
                            <i class="fa-solid fa-plus-circle mr-2"></i> Tambah Pemasukan Lainnya
                        </h3>
                        <button @click="modalTambah = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-white"><i class="fa-solid fa-xmark"></i></button>
                    </div>

                    <form action="{{ route('admin.bukukas.store') }}" method="POST" class="p-6 space-y-4">
                        @csrf
                        <input type="hidden" name="jenis_transaksi" value="pemasukan">

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tanggal Masuk <span class="text-red-500">*</span></label>
                            <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" required class="w-full py-2.5 px-4 bg-lightBg dark:bg-navy-900 border border-transparent dark:border-white/10 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 text-gray-700 dark:text-white transition-all">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Kategori <span class="text-red-500">*</span></label>
                            <select name="kategori" required class="w-full py-2.5 px-4 bg-lightBg dark:bg-navy-900 border border-transparent dark:border-white/10 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 text-gray-700 dark:text-white transition-all">
                                <option value="Donasi">Donasi / Sumbangan</option>
                                <option value="Sisa Dana Kegiatan">Sisa Dana Kegiatan</option>
                                <option value="Lain-lain">Lain-lain</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nominal (Rp) <span class="text-red-500">*</span></label>
                            <input type="number" name="nominal" required min="1" placeholder="Contoh: 150000" class="w-full py-2.5 px-4 bg-lightBg dark:bg-navy-900 border border-transparent dark:border-white/10 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 text-gray-700 dark:text-white transition-all">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Keterangan / Rincian</label>
                            <textarea name="keterangan" rows="2" placeholder="Catatan opsional..." class="w-full py-2.5 px-4 bg-lightBg dark:bg-navy-900 border border-transparent dark:border-white/10 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 text-gray-700 dark:text-white text-sm"></textarea>
                        </div>

                        <div class="pt-2 flex justify-end space-x-3">
                            <button type="button" @click="modalTambah = false" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 dark:bg-navy-700 dark:hover:bg-navy-600 text-gray-700 dark:text-gray-300 font-medium rounded-xl transition-colors text-sm">Batal</button>
                            <button type="submit" class="px-5 py-2.5 text-white font-bold rounded-xl shadow-sm text-sm transition-colors bg-green-500 hover:bg-green-600">Simpan Data</button>
                        </div>
                    </form>
                </div>
            </div>
        </template>

    </div>
</x-app-layout>