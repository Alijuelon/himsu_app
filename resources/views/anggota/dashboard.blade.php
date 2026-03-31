<x-app-layout>
    <x-slot name="header">
        Dashboard Anggota
    </x-slot>

    <div class="mb-6 p-6 bg-gradient-to-r from-brand to-[#868CFF] rounded-xl shadow-sm border border-transparent dark:border-white/5 text-white flex flex-col md:flex-row items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold mb-1">Halo, {{ Auth::user()->nama_lengkap }}! 👋</h2>
            <p class="text-sm text-white/80">Selamat datang di panel anggota HIMSU. Jangan lupa untuk melunasi iuran kas Anda tepat waktu ya.</p>
        </div>
        <div class="mt-4 md:mt-0">
            <a href="{{ route('anggota.bayar.create') }}" class="bg-white text-brand text-sm font-bold px-5 py-2.5 rounded-xl hover:bg-gray-50 transition shadow-sm flex items-center">
                <i class="fa-solid fa-money-bill-transfer mr-2"></i> Bayar Kas Sekarang
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-6">
        
        <div class="flex items-center p-5 bg-white dark:bg-navy-700 rounded-xl shadow-sm border border-transparent dark:border-white/5">
            <div class="p-4 bg-green-50 dark:bg-green-500/20 text-green-500 rounded-full">
                <i class="fa-solid fa-wallet text-2xl w-6 text-center"></i>
            </div>
            <div class="ml-4">
                <h4 class="text-sm font-medium text-gray-400">Total Kas Terbayar</h4>
                <div class="text-xl font-bold text-darkText dark:text-white mt-1">
                    Rp {{ number_format($totalDibayar, 0, ',', '.') }}
                </div>
            </div>
        </div>

        <div class="flex items-center p-5 bg-white dark:bg-navy-700 rounded-xl shadow-sm border border-transparent dark:border-white/5 relative overflow-hidden">
            <div class="p-4 bg-yellow-50 dark:bg-yellow-500/20 text-yellow-500 rounded-full">
                <i class="fa-solid fa-hourglass-half text-2xl w-6 text-center"></i>
            </div>
            <div class="ml-4">
                <h4 class="text-sm font-medium text-gray-400">Menunggu Verifikasi</h4>
                <div class="text-xl font-bold text-darkText dark:text-white mt-1">
                    {{ $menungguVerifikasi }} Transaksi
                </div>
            </div>
            @if($menungguVerifikasi > 0)
                <div class="absolute top-0 right-0 w-16 h-16 bg-yellow-100 dark:bg-yellow-500/10 rounded-bl-full -mr-8 -mt-8"></div>
                <span class="absolute top-2 right-2 flex h-3 w-3">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-yellow-400 opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-3 w-3 bg-yellow-500"></span>
                </span>
            @endif
        </div>

        <div class="flex items-center p-5 bg-white dark:bg-navy-700 rounded-xl shadow-sm border border-transparent dark:border-white/5">
            <div class="p-4 bg-red-50 dark:bg-red-500/20 text-red-500 rounded-full">
                <i class="fa-solid fa-bell text-2xl w-6 text-center"></i>
            </div>
            <div class="ml-4">
                <h4 class="text-sm font-medium text-gray-400">Periode Tagihan Aktif</h4>
                <div class="text-xl font-bold text-darkText dark:text-white mt-1">
                    {{ $tagihanAktif }} Bulan
                </div>
            </div>
        </div>

    </div>

    <div class="bg-white dark:bg-navy-700 rounded-xl shadow-sm p-6 border border-transparent dark:border-white/5 transition-colors">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-bold text-darkText dark:text-white">Riwayat Setoran Terakhir</h3>
            <a href="{{ route('anggota.riwayat.index') }}" class="text-sm text-brand hover:text-brandHover font-semibold transition">Lihat Semua</a>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-gray-400 dark:text-gray-400 text-xs tracking-wide uppercase border-b border-gray-100 dark:border-white/10">
                        <th class="py-3 px-4 font-medium">Tanggal Bayar</th>
                        <th class="py-3 px-4 font-medium">Periode Kas</th>
                        <th class="py-3 px-4 font-medium text-right">Nominal</th>
                        <th class="py-3 px-4 font-medium text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-600 dark:text-gray-300">
                    
                    @php
                        $namaBulan = [1=>'Januari', 2=>'Februari', 3=>'Maret', 4=>'April', 5=>'Mei', 6=>'Juni', 7=>'Juli', 8=>'Agustus', 9=>'September', 10=>'Oktober', 11=>'November', 12=>'Desember'];
                    @endphp

                    @forelse ($riwayatTerbaru as $item)
                        <tr class="border-b border-gray-50 dark:border-white/5 hover:bg-gray-50/50 dark:hover:bg-white/5 transition">
                            <td class="py-4 px-4 font-bold text-darkText dark:text-white">
                                {{ \Carbon\Carbon::parse($item->tanggal_bayar)->translatedFormat('d M Y') }}
                            </td>
                            <td class="py-4 px-4 font-medium">
                                {{ $namaBulan[$item->periode->bulan] ?? '' }} {{ $item->periode->tahun ?? '' }}
                            </td>
                            <td class="py-4 px-4 font-bold text-darkText dark:text-white text-right">
                                Rp {{ number_format($item->jumlah_bayar, 0, ',', '.') }}
                            </td>
                            <td class="py-4 px-4 text-center">
                                @if($item->status === 'pending')
                                    <span class="inline-flex items-center px-2 py-1 bg-yellow-50 dark:bg-yellow-500/10 text-yellow-600 dark:text-yellow-400 font-bold rounded-md text-[10px] uppercase tracking-wider">
                                        Pending
                                    </span>
                                @elseif($item->status === 'diterima')
                                    <span class="inline-flex items-center px-2 py-1 bg-green-50 dark:bg-green-500/10 text-green-600 dark:text-green-400 font-bold rounded-md text-[10px] uppercase tracking-wider">
                                        Diterima
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 font-bold rounded-md text-[10px] uppercase tracking-wider">
                                        Ditolak
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-8 text-center text-gray-400">Belum ada riwayat pembayaran kas.</td>
                        </tr>
                    @endforelse

                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>