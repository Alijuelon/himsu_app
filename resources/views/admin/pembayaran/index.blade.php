<x-app-layout>
    <x-slot name="header">
        Verifikasi Pembayaran Kas
    </x-slot>

    <div x-data="{ showModalManual: false, search: '{{ request('search') }}', statusFilter: '{{ request('status') }}' }">
        <div class="bg-white dark:bg-navy-700 rounded-xl shadow-sm p-6 border border-transparent dark:border-white/5 transition-colors">
        
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-6 gap-4">
            <div class="flex items-center gap-4">
                <h3 class="text-lg font-bold text-darkText dark:text-white">Daftar Setoran Anggota</h3>
                <button @click="showModalManual = true" class="bg-brand text-white hover:bg-brandHover px-4 py-2 rounded-xl text-sm font-bold shadow-sm transition flex items-center">
                    <i class="fa-solid fa-plus mr-2"></i> Bayar Manual
                </button>
            </div>
            
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
                                @php 
                                    $ext = pathinfo($item->bukti_transfer, PATHINFO_EXTENSION); 
                                    $isPdf = strtolower($ext) === 'pdf';
                                    $fileExists = $item->bukti_transfer && $item->bukti_transfer !== 'manual'
                                        && \Illuminate\Support\Facades\Storage::disk('public')->exists($item->bukti_transfer);
                                @endphp
                                
                                @if($item->bukti_transfer === 'manual')
                                    <div class="flex flex-col items-center justify-center p-2 border-2 border-brand/20 dark:border-brand/20 bg-brand/5 dark:bg-brand/10 rounded-lg text-brand w-20 h-16 mx-auto">
                                        <i class="fa-solid fa-money-bill-wave text-xl mb-1"></i>
                                        <span class="text-[9px] font-bold uppercase">Cash</span>
                                    </div>
                                @elseif(!$fileExists)
                                    <div class="flex flex-col items-center justify-center p-2 border-2 border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-navy-900 rounded-lg text-gray-400 w-20 h-16 mx-auto" title="File bukti tidak ditemukan">
                                        <i class="fa-solid fa-file-circle-question text-xl mb-1"></i>
                                        <span class="text-[9px] font-bold uppercase">Tdk Ada</span>
                                    </div>
                                @elseif($isPdf)
                                    <a href="{{ route('admin.bukti.show', ['path' => $item->bukti_transfer, 't' => time()]) }}" target="_blank" class="flex flex-col items-center justify-center p-2 border-2 border-red-200 dark:border-red-900/50 hover:bg-red-50 dark:hover:bg-red-500/10 rounded-lg transition-colors text-red-500 w-16 h-16 mx-auto group">
                                        <i class="fa-solid fa-file-pdf text-2xl group-hover:scale-110 transition-transform"></i>
                                        <span class="text-[9px] mt-1 font-bold uppercase">Lihat PDF</span>
                                    </a>
                                @else
                                    <a href="{{ route('admin.bukti.show', ['path' => $item->bukti_transfer, 't' => time()]) }}" target="_blank" class="relative group block mx-auto overflow-hidden rounded-lg w-16 h-16 border-2 border-gray-200 dark:border-navy-600 hover:border-brand transition">
                                        <img src="{{ route('admin.bukti.show', ['path' => $item->bukti_transfer, 't' => time()]) }}" alt="Bukti" class="object-cover w-full h-full" onerror="this.parentElement.innerHTML='<div class=\'flex items-center justify-center w-full h-full bg-gray-100 dark:bg-navy-900\'><i class=\'fa-solid fa-image-slash text-gray-400\'></i></div>'">
                                        <div class="absolute inset-0 bg-black/50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition">
                                            <i class="fa-solid fa-arrow-up-right-from-square text-white text-xs"></i>
                                        </div>
                                    </a>
                                @endif
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
                                    <div class="flex flex-col items-center gap-1.5">
                                        <span class="text-gray-400 text-xs italic">Selesai diverifikasi</span>
                                        <form action="{{ route('admin.pembayaran.resend-notif', $item->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="py-1.5 px-2 bg-brand/10 text-brand hover:bg-brand/20 dark:bg-brand/20 dark:text-brand dark:hover:bg-brand/30 rounded-lg transition font-bold text-[10px] flex items-center shadow-sm" onclick="return confirm('Kirim ulang notifikasi WhatsApp kuitansi/penolakan ke anggota ini?')">
                                                <i class="fa-brands fa-whatsapp mr-1"></i> Kirim Ulang Notif
                                            </button>
                                        </form>
                                        @if(!$fileExists && $item->bukti_transfer !== 'manual')
                                            {{-- Tombol upload ulang jika file bukti hilang --}}
                                            <div x-data="{ showUpload: false }">
                                                <button @click="showUpload = !showUpload" type="button" class="py-1.5 px-2 bg-orange-50 text-orange-600 hover:bg-orange-100 dark:bg-orange-500/10 dark:text-orange-400 dark:hover:bg-orange-500/20 rounded-lg transition font-bold text-[10px] flex items-center shadow-sm">
                                                    <i class="fa-solid fa-upload mr-1"></i> Upload Ulang Bukti
                                                </button>
                                                <div x-show="showUpload" x-transition class="mt-2">
                                                    <form action="{{ route('admin.pembayaran.upload-bukti', $item->id) }}" method="POST" enctype="multipart/form-data">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="flex items-center gap-1">
                                                            <label class="cursor-pointer bg-gray-100 dark:bg-navy-900 border border-dashed border-gray-300 dark:border-gray-600 rounded-lg px-2 py-1 text-[10px] text-gray-500 hover:border-brand hover:text-brand transition">
                                                                <i class="fa-solid fa-file-arrow-up mr-1"></i>Pilih File
                                                                <input type="file" name="bukti_transfer" class="sr-only" accept=".jpg,.jpeg,.png,.pdf" required onchange="this.closest('label').nextElementSibling.textContent = this.files[0]?.name || ''">
                                                            </label>
                                                        </div>
                                                        <p class="text-[9px] text-gray-400 mt-0.5 truncate max-w-[120px]" id="fn-{{ $item->id }}"></p>
                                                        <button type="submit" class="mt-1 w-full py-1 bg-orange-500 text-white text-[10px] font-bold rounded-lg hover:bg-orange-600 transition">
                                                            <i class="fa-solid fa-check mr-1"></i>Upload
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
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

    <!-- Modal Tambah Pembayaran Manual -->
    <div x-show="showModalManual" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div x-show="showModalManual" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity dark:bg-navy-900 dark:bg-opacity-80" aria-hidden="true" @click="showModalManual = false"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <!-- Modal panel -->
            <div x-show="showModalManual" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white dark:bg-navy-800 rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full border border-gray-100 dark:border-white/10">
                <form action="{{ route('admin.pembayaran.storeManual') }}" method="POST">
                    @csrf
                    <div class="bg-white dark:bg-navy-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-brand/10 dark:bg-brand/20 sm:mx-0 sm:h-10 sm:w-10 text-brand">
                                <i class="fa-solid fa-money-bill-wave text-xl"></i>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-bold text-gray-900 dark:text-white" id="modal-title">
                                    Catat Pembayaran Kas (Manual)
                                </h3>
                                <div class="mt-4 space-y-4">
                                    <!-- Pilih Anggota -->
                                    <div>
                                        <label for="anggota_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Anggota</label>
                                        <select id="anggota_id" name="anggota_id" required class="w-full bg-lightBg dark:bg-navy-900 border-none rounded-xl text-sm focus:ring-2 focus:ring-brand text-gray-700 dark:text-gray-300">
                                            <option value="">-- Pilih Anggota --</option>
                                            @foreach($anggotaList as $anggota)
                                                <option value="{{ $anggota->id }}">{{ $anggota->nama_lengkap }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <!-- Pilih Periode -->
                                    <div>
                                        <label for="periode_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Periode Kas</label>
                                        <select id="periode_id" name="periode_id" required class="w-full bg-lightBg dark:bg-navy-900 border-none rounded-xl text-sm focus:ring-2 focus:ring-brand text-gray-700 dark:text-gray-300">
                                            <option value="">-- Pilih Periode --</option>
                                            @foreach($periodeList as $periode)
                                                <option value="{{ $periode->id }}">Kas {{ $namaBulan[$periode->bulan] ?? '' }} {{ $periode->tahun }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <!-- Nominal -->
                                    <div>
                                        <label for="jumlah_bayar" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nominal (Rp)</label>
                                        <input type="number" id="jumlah_bayar" name="jumlah_bayar" value="150000" required class="w-full bg-lightBg dark:bg-navy-900 border-none rounded-xl text-sm focus:ring-2 focus:ring-brand text-gray-700 dark:text-gray-300">
                                    </div>
                                    <!-- Keterangan -->
                                    <div>
                                        <label for="keterangan" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Keterangan (Opsional)</label>
                                        <input type="text" id="keterangan" name="keterangan" placeholder="Misal: Titip bayar via Admin A" class="w-full bg-lightBg dark:bg-navy-900 border-none rounded-xl text-sm focus:ring-2 focus:ring-brand text-gray-700 dark:text-gray-300">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 dark:bg-navy-800/50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-gray-100 dark:border-white/10">
                        <button type="submit" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 bg-brand text-base font-medium text-white hover:bg-brandHover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand sm:ml-3 sm:w-auto sm:text-sm transition">
                            Simpan & Terima
                        </button>
                        <button type="button" @click="showModalManual = false" class="mt-3 w-full inline-flex justify-center rounded-xl border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-navy-700 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-navy-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
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
    </div>
</x-app-layout>