<x-app-layout>
    <x-slot name="header">
        Transparansi Saldo Kas HIMSU
    </x-slot>

    <div class="mb-6 p-6 sm:p-8 bg-gradient-to-r from-brand to-[#868CFF] rounded-xl shadow-lg text-white border border-transparent dark:border-white/5 flex flex-col items-center justify-center text-center relative overflow-hidden">
        
        <div class="absolute top-0 left-0 w-32 h-32 bg-white opacity-10 rounded-full -ml-10 -mt-10"></div>
        <div class="absolute bottom-0 right-0 w-40 h-40 bg-white opacity-10 rounded-full -mr-16 -mb-16"></div>

        <h2 class="text-sm sm:text-base font-medium text-white/80 uppercase tracking-widest mb-2 z-10">Total Saldo Kas Saat Ini</h2>
        <div class="text-4xl sm:text-5xl font-extrabold tracking-tight z-10">
            Rp {{ number_format($saldoAkhir, 0, ',', '.') }}
        </div>
        <p class="text-xs sm:text-sm text-white/70 mt-4 z-10">
            *Saldo dihitung berdasarkan total pemasukan dikurangi total pengeluaran operasional.
        </p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6">
        <div class="flex items-center p-5 bg-white dark:bg-navy-700 rounded-xl shadow-sm border border-transparent dark:border-white/5">
            <div class="p-4 bg-green-50 dark:bg-green-500/20 text-green-500 rounded-full">
                <i class="fa-solid fa-arrow-trend-up text-2xl w-6 text-center"></i>
            </div>
            <div class="ml-4">
                <h4 class="text-sm font-medium text-gray-400">Total Pemasukan</h4>
                <div class="text-xl font-bold text-darkText dark:text-white mt-1">
                    Rp {{ number_format($totalPemasukan, 0, ',', '.') }}
                </div>
            </div>
        </div>

        <div class="flex items-center p-5 bg-white dark:bg-navy-700 rounded-xl shadow-sm border border-transparent dark:border-white/5">
            <div class="p-4 bg-red-50 dark:bg-red-500/20 text-red-500 rounded-full">
                <i class="fa-solid fa-arrow-trend-down text-2xl w-6 text-center"></i>
            </div>
            <div class="ml-4">
                <h4 class="text-sm font-medium text-gray-400">Total Pengeluaran</h4>
                <div class="text-xl font-bold text-darkText dark:text-white mt-1">
                    Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-navy-700 rounded-xl shadow-sm p-6 border border-transparent dark:border-white/5 transition-colors">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h3 class="text-lg font-bold text-darkText dark:text-white">Rincian Arus Kas</h3>
                <p class="text-sm text-gray-400 mt-1">Laporan mutasi kas masuk dan keluar HIMSU Bengkalis.</p>
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
                        <th class="py-3 px-4 font-medium text-center">Jenis</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-600 dark:text-gray-300">
                    
                    @forelse ($arusKas as $item)
                        <tr class="border-b border-gray-50 dark:border-white/5 hover:bg-gray-50/50 dark:hover:bg-white/5 transition">
                            <td class="py-4 px-4 font-bold text-darkText dark:text-white whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d M Y') }}
                            </td>
                            
                            <td class="py-4 px-4 font-medium">
                                {{ $item->kategori }}
                            </td>
                            
                            <td class="py-4 px-4">
                                {{ Str::limit($item->keterangan, 50) ?: '-' }}
                            </td>
                            
                            <td class="py-4 px-4 font-bold text-right whitespace-nowrap {{ $item->jenis_transaksi === 'pemasukan' ? 'text-green-600 dark:text-green-400' : 'text-red-500 dark:text-red-400' }}">
                                {{ $item->jenis_transaksi === 'pemasukan' ? '+' : '-' }} Rp {{ number_format($item->nominal, 0, ',', '.') }}
                            </td>
                            
                            <td class="py-4 px-4 text-center">
                                @if($item->jenis_transaksi === 'pemasukan')
                                    <span class="inline-flex items-center px-2 py-1 bg-green-50 dark:bg-green-500/10 text-green-600 dark:text-green-400 font-bold rounded-md text-[10px] uppercase tracking-wider">
                                        Pemasukan
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 font-bold rounded-md text-[10px] uppercase tracking-wider">
                                        Pengeluaran
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-gray-400">Belum ada mutasi arus kas yang tercatat.</td>
                        </tr>
                    @endforelse

                </tbody>
            </table>
        </div>

        @if ($arusKas->hasPages())
            <div class="mt-6 pt-4 border-t border-gray-100 dark:border-white/10">
                {{ $arusKas->links() }}
            </div>
        @endif

    </div>
</x-app-layout>