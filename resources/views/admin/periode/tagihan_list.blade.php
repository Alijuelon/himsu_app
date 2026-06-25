<x-app-layout>
    <x-slot name="header">
        Daftar Tagihan Anggota
    </x-slot>

    <div class="bg-white dark:bg-navy-700 rounded-xl shadow-sm p-6 border border-transparent dark:border-white/5 transition-colors">
        
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-8 gap-5" x-data>
            <div>
                <h3 class="text-xl font-bold text-darkText dark:text-white">Anggota Belum Membayar Kas</h3>
                <p class="text-sm text-gray-500 mt-1">Daftar tagihan yang belum dilunasi oleh anggota.</p>
            </div>
            
            <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto items-center bg-gray-50 dark:bg-navy-800/50 p-2 rounded-2xl border border-gray-100 dark:border-white/5 shadow-sm">
                <form action="{{ route('admin.periode.tagihan_list') }}" method="GET" class="w-full sm:w-auto flex flex-col sm:flex-row gap-2">
                    <!-- Filter Tahun -->
                    <div class="relative group w-full sm:w-auto">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <i class="fa-regular fa-calendar text-gray-400 group-hover:text-brand transition-colors"></i>
                        </div>
                        <select name="tahun" onchange="this.form.submit()" class="w-full sm:w-36 pl-10 pr-10 py-2.5 bg-white dark:bg-navy-700 border border-transparent dark:border-white/5 rounded-xl text-sm font-semibold focus:ring-2 focus:ring-brand focus:border-brand text-gray-700 dark:text-gray-200 shadow-sm transition-all appearance-none cursor-pointer hover:shadow-md">
                            @foreach($tahuns as $th)
                                <option value="{{ $th }}" {{ $tahunFilter == $th ? 'selected' : '' }}>Tahun {{ $th }}</option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none">
                            <i class="fa-solid fa-chevron-down text-xs text-gray-400"></i>
                        </div>
                    </div>
                    
                    <!-- Filter Bulan -->
                    <div class="relative group w-full sm:w-auto">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <i class="fa-regular fa-clock text-gray-400 group-hover:text-brand transition-colors"></i>
                        </div>
                        <select name="bulan" onchange="this.form.submit()" class="w-full sm:w-40 pl-10 pr-10 py-2.5 bg-white dark:bg-navy-700 border border-transparent dark:border-white/5 rounded-xl text-sm font-semibold focus:ring-2 focus:ring-brand focus:border-brand text-gray-700 dark:text-gray-200 shadow-sm transition-all appearance-none cursor-pointer hover:shadow-md">
                            @foreach($namaBulan as $num => $name)
                                <option value="{{ $num }}" {{ $bulanFilter == $num ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none">
                            <i class="fa-solid fa-chevron-down text-xs text-gray-400"></i>
                        </div>
                    </div>
                </form>

                <div class="hidden sm:block w-px h-8 bg-gray-200 dark:bg-white/10 mx-1"></div>

                <!-- Tombol Kembali -->
                <a href="{{ route('admin.periode.index', ['tahun' => $tahunFilter]) }}" class="w-full sm:w-auto bg-gray-100 hover:bg-gray-200 dark:bg-navy-600 dark:hover:bg-navy-500 text-gray-700 dark:text-gray-300 text-sm font-bold px-5 py-2.5 rounded-xl transition-all shadow-sm hover:shadow-md flex items-center justify-center whitespace-nowrap group">
                    <i class="fa-solid fa-arrow-left mr-2 group-hover:-translate-x-1 transition-transform"></i> Kembali
                </a>
            </div>
        </div>

        @if(!$periode)
        <div class="p-4 mb-4 text-sm text-yellow-800 rounded-lg bg-yellow-50 dark:bg-yellow-900/30 dark:text-yellow-300 text-center">
            Periode kas untuk bulan {{ $namaBulan[$bulanFilter] }} tahun {{ $tahunFilter }} belum dibuat.
        </div>
        @else
        <!-- Action Notification WA -->
        <div class="mb-8 grid grid-cols-1 lg:grid-cols-2 gap-5">
            <!-- Kirim Manual -->
            <div class="bg-blue-50 dark:bg-blue-900/20 p-5 rounded-2xl border border-blue-100 dark:border-blue-800/30">
                <div class="flex items-start">
                    <div class="flex-shrink-0 mt-1">
                        <i class="fa-brands fa-whatsapp text-3xl text-brand"></i>
                    </div>
                    <div class="ml-4">
                        <h4 class="text-base font-bold text-darkText dark:text-white">Kirim Tagihan Manual</h4>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 mb-4">Kirim pesan WhatsApp tagihan sekarang ke semua anggota yang belum melunasi pembayaran bulan ini.</p>
                        
                        <form action="{{ route('admin.wa.settings.broadcast') }}" method="POST" id="form-broadcast">
                            @csrf
                            <button type="button" onclick="confirmBroadcast()" class="bg-brand hover:bg-brandHover text-white text-sm font-bold py-2.5 px-5 rounded-xl transition-all shadow-sm hover:shadow-md flex items-center justify-center">
                                <i class="fa-solid fa-paper-plane mr-2"></i> Kirim Sekarang
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Jadwal Otomatis -->
            <div class="bg-gray-50 dark:bg-navy-800/50 p-5 rounded-2xl border border-gray-100 dark:border-white/5">
                <form action="{{ route('admin.wa.settings.schedule') }}" method="POST">
                    @csrf
                    <div class="flex items-start">
                        <div class="flex-shrink-0 mt-1">
                            <i class="fa-solid fa-robot text-3xl text-gray-400 dark:text-gray-500"></i>
                        </div>
                        <div class="ml-4 w-full">
                            <h4 class="text-base font-bold text-darkText dark:text-white">Pengiriman Otomatis</h4>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 mb-3">Atur jadwal sistem mengirim tagihan otomatis setiap bulan.</p>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-3">
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Tgl Pengiriman (1-28)</label>
                                    <input type="number" name="tgl_tagihan_otomatis" min="1" max="28" value="{{ $waSetting->tgl_tagihan_otomatis ?? '' }}" class="w-full py-2 px-3 bg-white dark:bg-navy-700 border border-gray-200 dark:border-white/10 rounded-lg text-sm focus:ring-2 focus:ring-brand focus:border-brand text-gray-700 dark:text-white transition-all" placeholder="Contoh: 15">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Waktu (Jam:Menit)</label>
                                    <input type="time" name="waktu_tagihan_otomatis" value="{{ isset($waSetting->waktu_tagihan_otomatis) ? \Carbon\Carbon::parse($waSetting->waktu_tagihan_otomatis)->format('H:i') : '' }}" class="w-full py-2 px-3 bg-white dark:bg-navy-700 border border-gray-200 dark:border-white/10 rounded-lg text-sm focus:ring-2 focus:ring-brand focus:border-brand text-gray-700 dark:text-white transition-all">
                                </div>
                            </div>
                            
                            <div class="flex justify-end">
                                <button type="submit" class="bg-gray-800 dark:bg-white text-white dark:text-navy-900 text-sm font-bold py-2 px-4 rounded-xl hover:bg-gray-700 dark:hover:bg-gray-100 transition-all shadow-sm">
                                    Simpan Jadwal
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="mb-4 flex items-center justify-between">
            <span class="text-sm text-gray-500 dark:text-gray-400">Total Wajib Bayar: <b>Rp {{ number_format($periode->nominal_wajib, 0, ',', '.') }}</b>. Menampilkan anggota yang belum lunas.</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-gray-500 uppercase bg-gray-50 dark:bg-navy-800 dark:text-gray-400 rounded-lg">
                    <tr>
                        <th scope="col" class="px-4 py-4 font-semibold rounded-l-lg">Anggota</th>
                        <th scope="col" class="px-4 py-4 font-semibold">No HP</th>
                        <th scope="col" class="px-4 py-4 font-semibold text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tunggakan as $item)
                        <tr class="border-b border-gray-50 dark:border-white/5 hover:bg-gray-50/50 dark:hover:bg-white/5 transition-colors">
                            <td class="px-4 py-4 font-medium text-gray-900 dark:text-white">
                                {{ $item['anggota']->nama_lengkap }}
                            </td>
                            <td class="px-4 py-4 text-gray-500 dark:text-gray-400">
                                {{ $item['anggota']->no_hp ?? '-' }}
                            </td>
                            <td class="px-4 py-4 text-center">
                                @if($item['status'] == 'pending')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400">
                                        <i class="fa-solid fa-clock mr-1"></i> Menunggu Verifikasi
                                    </span>
                                @elseif($item['status'] == 'ditolak')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">
                                        <i class="fa-solid fa-times-circle mr-1"></i> Ditolak
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                        <i class="fa-solid fa-minus-circle mr-1"></i> Belum Bayar
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                <i class="fa-solid fa-check-circle text-4xl text-green-500 mb-3 opacity-50 block"></i>
                                Semua anggota telah lunas untuk periode ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @endif
    </div>
    
    <script>
        function confirmBroadcast() {
            Swal.fire({
                title: 'Kirim Tagihan WA?',
                text: "Sistem akan mengirim pesan tagihan secara otomatis ke semua anggota yang belum lunas di periode bulan ini.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#4318FF',
                cancelButtonColor: '#d33',
                confirmButtonText: '<i class="fa-brands fa-whatsapp mr-2"></i> Ya, Kirim Sekarang!',
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
                    document.getElementById('form-broadcast').submit();
                }
            })
        }
    </script>
</x-app-layout>
