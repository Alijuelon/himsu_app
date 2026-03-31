<x-app-layout>
    <x-slot name="header">
        Dashboard Admin
    </x-slot>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <div class="flex items-center p-5 bg-white dark:bg-navy-700 rounded-xl shadow-sm hover:shadow-md transition-shadow border border-transparent dark:border-white/5">
            <div class="p-4 bg-lightBg dark:bg-brand/20 text-brand rounded-full">
                <i class="fa-solid fa-wallet text-2xl w-6 text-center"></i>
            </div>
            <div class="ml-4">
                <h4 class="text-sm font-medium text-gray-400">Total Saldo Kas</h4>
                <div class="text-xl font-bold text-darkText dark:text-white mt-1">
                    Rp {{ number_format($saldoKas, 0, ',', '.') }}
                </div>
            </div>
        </div>

        <div class="flex items-center p-5 bg-white dark:bg-navy-700 rounded-xl shadow-sm hover:shadow-md transition-shadow border border-transparent dark:border-white/5">
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

        <div class="flex items-center p-5 bg-white dark:bg-navy-700 rounded-xl shadow-sm hover:shadow-md transition-shadow border border-transparent dark:border-white/5">
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

        <div class="flex items-center p-5 bg-white dark:bg-navy-700 rounded-xl shadow-sm hover:shadow-md transition-shadow border border-transparent dark:border-white/5">
            <div class="p-4 bg-purple-50 dark:bg-purple-500/20 text-purple-500 rounded-full">
                <i class="fa-solid fa-users text-2xl w-6 text-center"></i>
            </div>
            <div class="ml-4">
                <h4 class="text-sm font-medium text-gray-400">Jumlah Anggota</h4>
                <div class="text-xl font-bold text-darkText dark:text-white mt-1">
                    {{ $jumlahAnggota }}
                </div>
            </div>
        </div>
    </div>

    <!-- Chart Analytics Section -->
    <div class="bg-white dark:bg-navy-700 rounded-xl shadow-sm p-6 border border-transparent dark:border-white/5 transition-colors mb-6">
        <h3 class="text-lg font-bold text-darkText dark:text-white mb-4">Grafik Arus Kas ({{ $currentYear }})</h3>
        <div class="relative h-72 w-full">
            <canvas id="cashflowChart"></canvas>
        </div>
    </div>

    <div class="bg-white dark:bg-navy-700 rounded-xl shadow-sm p-6 border border-transparent dark:border-white/5 transition-colors">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-bold text-darkText dark:text-white">Riwayat Arus Kas Terbaru</h3>
            <a href="#" class="bg-lightBg dark:bg-white/5 text-brand dark:text-white text-sm font-semibold px-4 py-2 rounded-lg hover:bg-brand hover:text-white transition">Lihat Semua</a>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-gray-400 dark:text-gray-400 text-xs tracking-wide uppercase border-b border-gray-100 dark:border-white/10">
                        <th class="py-3 px-4 font-medium">Tanggal</th>
                        <th class="py-3 px-4 font-medium">Kategori</th>
                        <th class="py-3 px-4 font-medium">Keterangan</th>
                        <th class="py-3 px-4 font-medium">Jenis</th>
                        <th class="py-3 px-4 font-medium text-right">Jumlah</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-600 dark:text-gray-300">
                    
                    @forelse ($transaksiTerbaru as $trx)
                        <tr class="border-b border-gray-50 dark:border-white/5 hover:bg-gray-50/50 dark:hover:bg-white/5 transition">
                            <td class="py-4 px-4 font-bold text-darkText dark:text-white">
                                {{ \Carbon\Carbon::parse($trx->tanggal)->translatedFormat('d M Y') }}
                            </td>
                            <td class="py-4 px-4 font-medium">{{ $trx->kategori }}</td>
                            <td class="py-4 px-4 font-medium">{{ Str::limit($trx->keterangan, 40) }}</td>
                            <td class="py-4 px-4">
                                @if($trx->jenis_transaksi === 'pemasukan')
                                    <div class="flex items-center text-green-600 dark:text-green-400 font-bold bg-green-50 dark:bg-green-500/10 px-2 py-1 rounded-md w-max">
                                        <i class="fa-solid fa-circle-check text-[10px] mr-1.5"></i> Pemasukan
                                    </div>
                                @else
                                    <div class="flex items-center text-red-600 dark:text-red-400 font-bold bg-red-50 dark:bg-red-500/10 px-2 py-1 rounded-md w-max">
                                        <i class="fa-solid fa-circle-xmark text-[10px] mr-1.5"></i> Pengeluaran
                                    </div>
                                @endif
                            </td>
                            <td class="py-4 px-4 font-bold text-darkText dark:text-white text-right">
                                Rp {{ number_format($trx->nominal, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-gray-400">Belum ada riwayat transaksi kas.</td>
                        </tr>
                    @endforelse

                </tbody>
            </table>
        </div>
    </div>

    <!-- Script Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('cashflowChart').getContext('2d');
        const pemasukanData = @json($chartData['pemasukan']);
        const pengeluaranData = @json($chartData['pengeluaran']);

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'],
                datasets: [
                    {
                        label: 'Pemasukan',
                        data: pemasukanData,
                        backgroundColor: 'rgba(34, 197, 94, 0.8)', // green-500 text-brand
                        borderRadius: 4,
                    },
                    {
                        label: 'Pengeluaran',
                        data: pengeluaranData,
                        backgroundColor: 'rgba(239, 68, 68, 0.8)', // red-500
                        borderRadius: 4,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            color: '#9ca3af',
                            font: { family: "'Inter', sans-serif", size: 12 }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(255, 255, 255, 0.05)' },
                        ticks: { color: '#9ca3af' }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { color: '#9ca3af' }
                    }
                }
            }
        });
    </script>
</x-app-layout>