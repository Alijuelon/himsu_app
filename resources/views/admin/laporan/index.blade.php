<x-app-layout>
    <x-slot name="header">
        Laporan Keuangan Kas
    </x-slot>

    <div class="bg-white dark:bg-navy-700 rounded-xl shadow-sm p-6 border border-transparent dark:border-white/5 transition-colors">
        
        <div class="mb-6 pb-6 border-b border-gray-100 dark:border-white/10">
            <h3 class="text-lg font-bold text-darkText dark:text-white mb-4">Filter Laporan</h3>
            
            <form action="{{ route('admin.laporan.index') }}" method="GET" class="flex flex-col sm:flex-row items-end gap-4">
                
                <div class="w-full sm:w-auto flex-1">
                    <label class="block text-xs font-medium text-gray-500 mb-1">Dari Tanggal</label>
                    <input type="date" name="tgl_mulai" value="{{ request('tgl_mulai') }}" class="w-full py-2.5 px-4 bg-lightBg dark:bg-navy-800 border-none rounded-xl text-sm focus:ring-2 focus:ring-brand text-gray-700 dark:text-white transition-all">
                </div>
                
                <div class="w-full sm:w-auto flex-1">
                    <label class="block text-xs font-medium text-gray-500 mb-1">Sampai Tanggal</label>
                    <input type="date" name="tgl_akhir" value="{{ request('tgl_akhir') }}" class="w-full py-2.5 px-4 bg-lightBg dark:bg-navy-800 border-none rounded-xl text-sm focus:ring-2 focus:ring-brand text-gray-700 dark:text-white transition-all">
                </div>

                <div class="w-full sm:w-auto flex space-x-2">
                    <button type="submit" class="w-full sm:w-auto bg-brand text-white text-sm font-semibold px-5 py-2.5 rounded-xl hover:bg-brandHover transition shadow-sm flex items-center justify-center">
                        <i class="fa-solid fa-filter mr-2"></i> Filter
                    </button>
                    
                    <a href="{{ route('admin.laporan.index') }}" class="w-full sm:w-auto bg-gray-100 dark:bg-navy-600 text-gray-600 dark:text-gray-300 text-sm font-semibold px-5 py-2.5 rounded-xl hover:bg-gray-200 dark:hover:bg-navy-500 transition shadow-sm flex items-center justify-center">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
            <div>
                <h4 class="font-bold text-darkText dark:text-white">Rekapitulasi Arus Kas</h4>
                <p class="text-xs text-gray-400 mt-1">
                    @if(request('tgl_mulai') && request('tgl_akhir'))
                        Periode: {{ \Carbon\Carbon::parse(request('tgl_mulai'))->translatedFormat('d M Y') }} s/d {{ \Carbon\Carbon::parse(request('tgl_akhir'))->translatedFormat('d M Y') }}
                    @else
                        Menampilkan seluruh data kas.
                    @endif
                </p>
            </div>
            
            <a href="{{ route('admin.laporan.pdf', ['tgl_mulai' => request('tgl_mulai'), 'tgl_akhir' => request('tgl_akhir')]) }}" target="_blank" class="w-full sm:w-auto bg-red-500 text-white text-sm font-bold px-5 py-2.5 rounded-xl hover:bg-red-600 transition shadow-sm flex items-center justify-center">
                <i class="fa-solid fa-file-pdf mr-2"></i> Cetak PDF
            </a>
        </div>

        <div class="overflow-x-auto rounded-xl border border-gray-100 dark:border-white/5">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-lightBg dark:bg-navy-800 text-gray-500 dark:text-gray-400 text-xs tracking-wide uppercase">
                        <th class="py-4 px-4 font-bold border-b border-gray-100 dark:border-white/5">No</th>
                        <th class="py-4 px-4 font-bold border-b border-gray-100 dark:border-white/5">Tanggal</th>
                        <th class="py-4 px-4 font-bold border-b border-gray-100 dark:border-white/5">Keterangan</th>
                        <th class="py-4 px-4 font-bold border-b border-gray-100 dark:border-white/5 text-right text-green-600 dark:text-green-400">Pemasukan</th>
                        <th class="py-4 px-4 font-bold border-b border-gray-100 dark:border-white/5 text-right text-red-500 dark:text-red-400">Pengeluaran</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-700 dark:text-gray-300">
                    @forelse ($laporan as $index => $item)
                        <tr class="border-b border-gray-50 dark:border-white/5 hover:bg-gray-50/50 dark:hover:bg-white/5 transition">
                            <td class="py-3 px-4">{{ $index + 1 }}</td>
                            <td class="py-3 px-4 whitespace-nowrap">{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d M Y') }}</td>
                            <td class="py-3 px-4">
                                <span class="font-bold">{{ $item->kategori }}</span><br>
                                <span class="text-xs text-gray-400">{{ $item->keterangan ?? '-' }}</span>
                            </td>
                            <td class="py-3 px-4 text-right font-medium">
                                {{ $item->jenis_transaksi == 'pemasukan' ? 'Rp ' . number_format($item->nominal, 0, ',', '.') : '-' }}
                            </td>
                            <td class="py-3 px-4 text-right font-medium">
                                {{ $item->jenis_transaksi == 'pengeluaran' ? 'Rp ' . number_format($item->nominal, 0, ',', '.') : '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-10 text-center text-gray-400">Data laporan tidak ditemukan pada periode ini.</td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot class="bg-lightBg/50 dark:bg-navy-800/50 text-sm font-bold text-darkText dark:text-white">
                    <tr>
                        <td colspan="3" class="py-4 px-4 text-right">TOTAL ARUS KAS:</td>
                        <td class="py-4 px-4 text-right text-green-600 dark:text-green-400">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</td>
                        <td class="py-4 px-4 text-right text-red-500 dark:text-red-400">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</td>
                    </tr>
                    <tr class="bg-brand/10 dark:bg-brand/20">
                        <td colspan="3" class="py-4 px-4 text-right uppercase tracking-wider text-brand dark:text-white">Sisa Saldo Kas Akhir:</td>
                        <td colspan="2" class="py-4 px-4 text-right text-lg text-brand dark:text-brandHover">Rp {{ number_format($saldoAkhir, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>

    </div>
</x-app-layout>