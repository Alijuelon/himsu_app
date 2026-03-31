<x-app-layout>
    <x-slot name="header">
        Verifikasi Pembayaran Kas
    </x-slot>

    <div class="bg-white dark:bg-navy-700 rounded-xl shadow-sm p-6 border border-transparent dark:border-white/5 transition-colors">
        
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-6 gap-4" x-data="{ search: '{{ request('search') }}', statusFilter: '{{ request('status') }}' }">
            <h3 class="text-lg font-bold text-darkText dark:text-white">Daftar Setoran Anggota</h3>
            
            <form action="{{ route('admin.pembayaran.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto">
                <select name="status" x-model="statusFilter" @change="$el.closest('form').submit()" class="py-2.5 px-4 bg-lightBg dark:bg-navy-800 border-none rounded-xl text-sm focus:ring-2 focus:ring-brand text-gray-700 dark:text-gray-300 transition-all">
                    <option value="">Semua Status</option>
                    <option value="pending">Menunggu Verifikasi (Pending)</option>
                    <option value="diterima">Diterima (Lunas)</option>
                    <option value="ditolak">Ditolak</option>
                </select>

                <div class="relative w-full sm:w-64">
                    <input type="text" name="search" x-model="search" placeholder="Cari nama anggota..." 
                           class="w-full pl-10 pr-10 py-2.5 bg-lightBg dark:bg-navy-800 border-none rounded-xl text-sm focus:ring-2 focus:ring-brand text-gray-700 dark:text-gray-300 transition-all placeholder-gray-400">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fa-solid fa-magnifying-glass text-gray-400"></i>
                    </div>
                    <button type="button" x-show="search.length > 0" style="display: none;" @click="search = ''; $el.closest('form').submit()" 
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-red-500 transition">
                        <i class="fa-solid fa-circle-xmark"></i>
                    </button>
                </div>
            </form>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-gray-400 dark:text-gray-400 text-xs tracking-wide uppercase border-b border-gray-100 dark:border-white/10">
                        <th class="py-3 px-4 font-medium">Anggota & Tanggal</th>
                        <th class="py-3 px-4 font-medium">Periode & Nominal</th>
                        <th class="py-3 px-4 font-medium text-center">Bukti Transfer</th>
                        <th class="py-3 px-4 font-medium text-center">Status</th>
                        <th class="py-3 px-4 font-medium text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-600 dark:text-gray-300">
                    @forelse ($pembayaran as $item)
                        <tr class="border-b border-gray-50 dark:border-white/5 hover:bg-gray-50/50 dark:hover:bg-white/5 transition">
                            
                            <td class="py-4 px-4">
                                <p class="font-bold text-darkText dark:text-white">{{ $item->anggota->nama_lengkap ?? 'Anggota Dihapus' }}</p>
                                <p class="text-xs text-gray-400"><i class="fa-regular fa-clock mr-1"></i> {{ \Carbon\Carbon::parse($item->tanggal_bayar)->translatedFormat('d M Y') }}</p>
                            </td>
                            
                            <td class="py-4 px-4">
                                <p class="font-medium">Kas {{ $namaBulan[$item->periode->bulan] ?? '' }} {{ $item->periode->tahun ?? '' }}</p>
                                <p class="font-bold text-brand dark:text-brandHover mt-1">Rp {{ number_format($item->jumlah_bayar, 0, ',', '.') }}</p>
                            </td>
                            
                            <td class="py-4 px-4 text-center">
                                <div x-data="{ openImage: false }">
                                    <button @click="openImage = true" class="relative group block mx-auto overflow-hidden rounded-lg w-16 h-16 border-2 border-gray-200 dark:border-navy-600 hover:border-brand transition">
                                        <img src="{{ asset('storage/' . $item->bukti_transfer) }}" alt="Bukti" class="object-cover w-full h-full" onerror="this.src='https://via.placeholder.com/150?text=No+Image'">
                                        <div class="absolute inset-0 bg-black/50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition">
                                            <i class="fa-solid fa-magnifying-glass text-white text-xs"></i>
                                        </div>
                                    </button>

                                    <template x-teleport="body">
                                        <div x-show="openImage" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center p-4">
                                            <div x-show="openImage" @click="openImage = false" class="fixed inset-0 bg-gray-900/80 backdrop-blur-sm transition-opacity"></div>
                                            <div x-show="openImage" class="relative max-w-3xl w-full">
                                                <button @click="openImage = false" class="absolute -top-10 right-0 text-white hover:text-gray-300">
                                                    <i class="fa-solid fa-xmark text-2xl"></i>
                                                </button>
                                                <img src="{{ asset('storage/' . $item->bukti_transfer) }}" class="w-full h-auto rounded-xl shadow-2xl max-h-[80vh] object-contain bg-black/50" onerror="this.src='https://via.placeholder.com/600?text=Gambar+Tidak+Ditemukan'">
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </td>

                            <td class="py-4 px-4 text-center">
                                @if($item->status === 'pending')
                                    <span class="inline-flex items-center px-3 py-1 bg-yellow-50 dark:bg-yellow-500/10 text-yellow-600 dark:text-yellow-400 font-bold rounded-lg text-xs">
                                        <i class="fa-solid fa-hourglass-half mr-1.5"></i> Pending
                                    </span>
                                @elseif($item->status === 'diterima')
                                    <span class="inline-flex items-center px-3 py-1 bg-green-50 dark:bg-green-500/10 text-green-600 dark:text-green-400 font-bold rounded-lg text-xs" title="Diverifikasi oleh: {{ $item->verifikator->nama_lengkap ?? 'Sistem' }}">
                                        <i class="fa-solid fa-check mr-1.5"></i> Diterima
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 font-bold rounded-lg text-xs">
                                        <i class="fa-solid fa-xmark mr-1.5"></i> Ditolak
                                    </span>
                                @endif
                                
                                @if($item->keterangan)
                                    <p class="text-[10px] text-gray-400 mt-1 mt-1 truncate max-w-[100px] mx-auto" title="{{ $item->keterangan }}">{{ $item->keterangan }}</p>
                                @endif
                            </td>

                            <td class="py-4 px-4 text-center">
                                @if($item->status === 'pending')
                                    <div class="flex items-center justify-center space-x-2">
                                        <form action="{{ route('admin.pembayaran.verifikasi', $item->id) }}" method="POST" id="form-terima-{{ $item->id }}">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="status" value="diterima">
                                            <button type="button" onclick="confirmVerifikasi('{{ $item->id }}', 'diterima', '{{ $item->anggota->nama_lengkap ?? '' }}', '{{ number_format($item->jumlah_bayar, 0, ',', '.') }}')" class="p-2 bg-green-50 text-green-600 hover:bg-green-100 dark:bg-green-500/10 dark:text-green-400 dark:hover:bg-green-500/20 rounded-lg transition font-bold text-xs flex items-center shadow-sm">
                                                <i class="fa-solid fa-check mr-1"></i> Terima
                                            </button>
                                        </form>
                                        
                                        <form action="{{ route('admin.pembayaran.verifikasi', $item->id) }}" method="POST" id="form-tolak-{{ $item->id }}">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="status" value="ditolak">
                                            <input type="hidden" name="keterangan" id="keterangan-{{ $item->id }}">
                                            <button type="button" onclick="confirmVerifikasi('{{ $item->id }}', 'ditolak', '{{ $item->anggota->nama_lengkap ?? '' }}', '{{ number_format($item->jumlah_bayar, 0, ',', '.') }}')" class="p-2 bg-red-50 text-red-600 hover:bg-red-100 dark:bg-red-500/10 dark:text-red-400 dark:hover:bg-red-500/20 rounded-lg transition font-bold text-xs flex items-center shadow-sm">
                                                <i class="fa-solid fa-xmark mr-1"></i> Tolak
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <span class="text-gray-400 text-xs italic">Selesai diverifikasi</span>
                                @endif
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-10 text-center text-gray-400">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="fa-solid fa-file-invoice-dollar text-4xl mb-3 text-gray-300 dark:text-gray-600"></i>
                                    <p>Belum ada data pembayaran masuk.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($pembayaran->hasPages())
            <div class="mt-6 pt-4 border-t border-gray-100 dark:border-white/10">
                {{ $pembayaran->links() }}
            </div>
        @endif

    </div>

    <!-- Script for SweetAlert2 Action -->
    <script>
        function confirmVerifikasi(id, status, nama, nominal) {
            const isDarkMode = document.documentElement.classList.contains('dark');
            const isTerima = status === 'diterima';

            Swal.fire({
                title: isTerima ? 'Terima Pembayaran?' : 'Tolak Pembayaran?',
                html: `Anda akan <b>${status}</b> pembayaran dari <b>${nama}</b> senilai <b>Rp ${nominal}</b>.<br><br>` +
                      (status === 'ditolak' ? 'Wajib tuliskan alasan (opsional jika manual, tapi direkomendasikan jika menolak):' : 'Bisa tambahkan catatan (Opsional):'),
                icon: isTerima ? 'success' : 'warning',
                input: 'textarea',
                inputPlaceholder: isTerima ? 'Catatan verifikasi...' : 'Alasan penolakan (misal: bukti transfer buram)...',
                showCancelButton: true,
                confirmButtonColor: isTerima ? '#10B981' : '#EF4444',
                cancelButtonColor: '#6B7280',
                confirmButtonText: isTerima ? '<i class="fa-solid fa-check mr-2"></i> Ya, Terima' : '<i class="fa-solid fa-xmark mr-2"></i> Ya, Tolak',
                cancelButtonText: 'Batal',
                background: isDarkMode ? '#111C44' : '#ffffff',
                color: isDarkMode ? '#ffffff' : '#2B3674',
                preConfirm: (keterangan) => {
                    if (status === 'ditolak' && !keterangan) {
                        Swal.showValidationMessage('Alasan penolakan wajib diisi!')
                    }
                    return keterangan;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Memproses...',
                        text: 'Silakan tunggu, sistem sedang memverifikasi dan mengirim notifikasi WhatsApp.',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading()
                        },
                        background: isDarkMode ? '#111C44' : '#ffffff',
                        color: isDarkMode ? '#ffffff' : '#2B3674',
                    });

                    if (status === 'ditolak') {
                        document.getElementById('keterangan-' + id).value = result.value;
                        document.getElementById('form-tolak-' + id).submit();
                    } else {
                        // Tambahkan input keterangan secara manual jika ada isian
                        if(result.value) {
                             const input = document.createElement("input");
                             input.type = "hidden";
                             input.name = "keterangan";
                             input.value = result.value;
                             document.getElementById('form-terima-' + id).appendChild(input);
                        }
                        document.getElementById('form-terima-' + id).submit();
                    }
                }
            })
        }
    </script>
</x-app-layout>