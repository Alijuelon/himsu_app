<x-app-layout>
    <x-slot name="header">
        Laporan Laba Rugi
    </x-slot>

    <div class="bg-white dark:bg-navy-700 rounded-xl shadow-sm p-6 border border-transparent dark:border-white/5 transition-colors mb-6">
        
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-6 gap-4">
            <h3 class="text-lg font-bold text-darkText dark:text-white">Filter Tahun</h3>
            
            <form action="{{ route('ketua.laporan.laba-rugi') }}" method="GET" class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto">
                <select name="year" class="px-4 py-2 bg-lightBg dark:bg-navy-800 border-none rounded-xl text-sm focus:ring-2 focus:ring-brand text-gray-700 dark:text-gray-300 w-full sm:w-auto">
                    @for($y = date('Y'); $y >= date('Y') - 5; $y--)
                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>Tahun {{ $y }}</option>
                    @endfor
                </select>
                <button type="submit" class="bg-brand text-white text-sm font-semibold px-4 py-2.5 rounded-xl hover:bg-brandHover transition shadow-sm">
                    Tampilkan <i class="fa-solid fa-filter ml-1"></i>
                </button>
            </form>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="p-5 bg-green-50 dark:bg-green-500/10 rounded-xl border border-green-100 dark:border-green-500/20 text-center">
                <h4 class="text-sm font-bold text-green-600 dark:text-green-400 mb-2">Total Pemasukan</h4>
                <div class="text-2xl font-bold text-green-700 dark:text-green-500">Rp {{ number_format($totalPemasukanTahun, 0, ',', '.') }}</div>
            </div>
            <div class="p-5 bg-red-50 dark:bg-red-500/10 rounded-xl border border-red-100 dark:border-red-500/20 text-center">
                <h4 class="text-sm font-bold text-red-600 dark:text-red-400 mb-2">Total Pengeluaran</h4>
                <div class="text-2xl font-bold text-red-700 dark:text-red-500">Rp {{ number_format($totalPengeluaranTahun, 0, ',', '.') }}</div>
            </div>
            <div class="p-5 bg-blue-50 dark:bg-brand/10 rounded-xl border border-blue-100 dark:border-brand/20 text-center">
                <h4 class="text-sm font-bold text-brand dark:text-brandHover mb-2">Laba Bersih</h4>
                <div class="text-2xl font-bold text-brand dark:text-brandHover">Rp {{ number_format($totalLabaRugi, 0, ',', '.') }}</div>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-gray-400 dark:text-gray-400 text-xs tracking-wide uppercase border-b border-gray-100 dark:border-white/10">
                        <th class="py-3 px-4 font-medium">Bulan</th>
                        <th class="py-3 px-4 font-medium text-right text-green-600 dark:text-green-400">Pemasukan</th>
                        <th class="py-3 px-4 font-medium text-right text-red-600 dark:text-red-400">Pengeluaran</th>
                        <th class="py-3 px-4 font-medium text-right text-brand dark:text-brandHover">Laba / Rugi</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-600 dark:text-gray-300">
                    @php
                        $namaBulan = [1=>'Januari', 2=>'Februari', 3=>'Maret', 4=>'April', 5=>'Mei', 6=>'Juni', 7=>'Juli', 8=>'Agustus', 9=>'September', 10=>'Oktober', 11=>'November', 12=>'Desember'];
                    @endphp

                    @foreach ($labaRugiData as $bulan => $data)
                        <tr class="border-b border-gray-50 dark:border-white/5 hover:bg-gray-50/50 dark:hover:bg-white/5 transition">
                            <td class="py-4 px-4 font-bold text-darkText dark:text-white">
                                {{ $namaBulan[$bulan] }}
                            </td>
                            <td class="py-4 px-4 font-bold text-green-600 dark:text-green-500 text-right">
                                Rp {{ number_format($data['pemasukan'], 0, ',', '.') }}
                            </td>
                            <td class="py-4 px-4 font-bold text-red-600 dark:text-red-500 text-right">
                                Rp {{ number_format($data['pengeluaran'], 0, ',', '.') }}
                            </td>
                            <td class="py-4 px-4 font-bold text-right {{ $data['laba'] >= 0 ? 'text-brand dark:text-brandHover' : 'text-red-600 dark:text-red-400' }}">
                                Rp {{ number_format($data['laba'], 0, ',', '.') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
</x-app-layout>
