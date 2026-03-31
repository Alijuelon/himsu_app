<x-app-layout>
    <x-slot name="header">
        Master Periode Tagihan Kas
    </x-slot>

    <div class="bg-white dark:bg-navy-700 rounded-xl shadow-sm p-6 border border-transparent dark:border-white/5 transition-colors">
        
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-6 gap-4" x-data="{ search: '{{ request('search') }}' }">
            <h3 class="text-lg font-bold text-darkText dark:text-white">Daftar Periode Kas</h3>
            
            <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto">
                <form action="{{ route('admin.periode.index') }}" method="GET" class="relative w-full sm:w-64">
                    <input type="text" name="search" x-model="search" placeholder="Cari tahun (ex: 2026)..." 
                           class="w-full pl-10 pr-10 py-2.5 bg-lightBg dark:bg-navy-800 border-none rounded-xl text-sm focus:ring-2 focus:ring-brand text-gray-700 dark:text-gray-300 transition-all placeholder-gray-400">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fa-solid fa-magnifying-glass text-gray-400"></i>
                    </div>
                    <button type="button" x-show="search.length > 0" style="display: none;" @click="search = ''; $el.closest('form').submit()" 
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-red-500 transition">
                        <i class="fa-solid fa-circle-xmark"></i>
                    </button>
                </form>

                <a href="{{ route('admin.periode.create') }}" class="bg-brand text-white text-sm font-semibold px-4 py-2.5 rounded-xl hover:bg-brandHover transition shadow-sm flex items-center justify-center whitespace-nowrap">
                    <i class="fa-solid fa-plus mr-2"></i> Buat Periode Baru
                </a>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-gray-400 dark:text-gray-400 text-xs tracking-wide uppercase border-b border-gray-100 dark:border-white/10">
                        <th class="py-3 px-4 font-medium">Bulan & Tahun</th>
                        <th class="py-3 px-4 font-medium">Nominal Wajib</th>
                        <th class="py-3 px-4 font-medium">Status</th>
                        <th class="py-3 px-4 font-medium text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-600 dark:text-gray-300">
                    @forelse ($periode as $item)
                        <tr class="border-b border-gray-50 dark:border-white/5 hover:bg-gray-50/50 dark:hover:bg-white/5 transition">
                            <td class="py-4 px-4">
                                <p class="font-bold text-darkText dark:text-white">{{ $namaBulan[$item->bulan] }} {{ $item->tahun }}</p>
                            </td>
                            <td class="py-4 px-4 font-bold text-darkText dark:text-white">
                                Rp {{ number_format($item->nominal_wajib, 0, ',', '.') }}
                            </td>
                            <td class="py-4 px-4">
                                @if($item->status == 'aktif')
                                    <span class="px-3 py-1 bg-green-50 dark:bg-green-500/10 text-green-600 dark:text-green-400 font-bold rounded-lg text-xs">Aktif</span>
                                @else
                                    <span class="px-3 py-1 bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 font-bold rounded-lg text-xs">Ditutup</span>
                                @endif
                            </td>
                            <td class="py-4 px-4 text-center">
                                <div class="flex items-center justify-center space-x-2">
                                    <form action="{{ route('admin.periode.tagihan', $item->id) }}" method="POST" class="form-tagihan">
                                        @csrf
                                        <button type="button" onclick="confirmTagihan(this)" class="p-2 bg-green-50 dark:bg-green-500/10 text-brand rounded-lg hover:bg-green-100 transition" title="Kirim Tagihan WA">
                                            <i class="fa-brands fa-whatsapp"></i>
                                        </button>
                                    </form>

                                    <a href="{{ route('admin.periode.edit', $item->id) }}" class="p-2 bg-blue-50 dark:bg-blue-500/10 text-blue-500 rounded-lg hover:bg-blue-100 transition" title="Edit">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    
                                    <div x-data="{ openModal: false }" class="inline">
                                        <button @click="openModal = true" type="button" class="p-2 bg-red-50 dark:bg-red-500/10 text-red-500 rounded-lg hover:bg-red-100 transition" title="Hapus">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                        <template x-teleport="body">
                                            <div x-show="openModal" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center px-4">
                                                <div x-show="openModal" @click="openModal = false" class="fixed inset-0 bg-gray-900/40 backdrop-blur-sm transition-opacity"></div>
                                                <div x-show="openModal" class="relative bg-white dark:bg-navy-800 rounded-2xl shadow-2xl p-6 w-full max-w-sm overflow-hidden text-center border border-gray-100 dark:border-white/10">
                                                    <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-50 dark:bg-red-500/10 mb-5">
                                                        <i class="fa-solid fa-triangle-exclamation text-3xl text-red-500"></i>
                                                    </div>
                                                    <h3 class="text-xl font-bold text-darkText dark:text-white mb-2">Hapus Periode?</h3>
                                                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">
                                                        Periode <span class="font-bold">{{ $namaBulan[$item->bulan] }} {{ $item->tahun }}</span> akan dihapus. Ini mungkin memengaruhi data pembayaran yang terkait.
                                                    </p>
                                                    <div class="flex justify-center gap-3">
                                                        <button @click="openModal = false" type="button" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 dark:bg-navy-700 dark:hover:bg-navy-600 text-gray-700 dark:text-gray-300 font-medium rounded-xl transition-colors w-full">Batal</button>
                                                        <form action="{{ route('admin.periode.destroy', $item->id) }}" method="POST" class="w-full">
                                                            @csrf @method('DELETE')
                                                            <button type="submit" class="w-full px-5 py-2.5 bg-red-500 hover:bg-red-600 text-white font-bold rounded-xl transition-colors shadow-sm">Ya, Hapus</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </template>
                                    </div>

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-10 text-center text-gray-400">Belum ada periode kas yang dibuat.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($periode->hasPages())
            <div class="mt-6 pt-4 border-t border-gray-100 dark:border-white/10">
                {{ $periode->links() }}
            </div>
        @endif

    </div>

    <!-- Script for SweetAlert2 Action -->
    <script>
        function confirmTagihan(button) {
            Swal.fire({
                title: 'Broadcast Tagihan WA?',
                text: "Sistem akan mengirim pesan tagihan ke semua anggota yang belum lunas di periode ini.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#4318FF',
                cancelButtonColor: '#d33',
                confirmButtonText: '<i class="fa-brands fa-whatsapp mr-2"></i> Ya, Kirim!',
                cancelButtonText: 'Batal',
                background: document.documentElement.classList.contains('dark') ? '#111C44' : '#ffffff',
                color: document.documentElement.classList.contains('dark') ? '#ffffff' : '#2B3674',
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Memproses...',
                        text: 'Silakan tunggu, sedang mengirim pesan ke Fonnte.',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading()
                        },
                        background: document.documentElement.classList.contains('dark') ? '#111C44' : '#ffffff',
                        color: document.documentElement.classList.contains('dark') ? '#ffffff' : '#2B3674',
                    });
                    button.closest('form').submit();
                }
            })
        }
    </script>
</x-app-layout>