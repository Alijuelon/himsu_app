<x-app-layout>
    <x-slot name="header">
        Riwayat Pembayaran Kas
    </x-slot>

    <div class="bg-white dark:bg-navy-700 rounded-xl shadow-sm p-6 border border-transparent dark:border-white/5 transition-colors">
        
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
            <div>
                <h3 class="text-lg font-bold text-darkText dark:text-white">Daftar Transaksi Anda</h3>
                <p class="text-sm text-gray-400 mt-1">Pantau status verifikasi pembayaran iuran kas Anda di sini.</p>
            </div>
            
            <a href="{{ route('anggota.bayar.create') }}" class="bg-brand text-white text-sm font-semibold px-4 py-2.5 rounded-xl hover:bg-brandHover transition shadow-sm flex items-center justify-center whitespace-nowrap">
                <i class="fa-solid fa-plus mr-2"></i> Bayar Tagihan Baru
            </a>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-gray-400 dark:text-gray-400 text-xs tracking-wide uppercase border-b border-gray-100 dark:border-white/10">
                        <th class="py-3 px-4 font-medium">Tanggal Transfer</th>
                        <th class="py-3 px-4 font-medium">Tagihan Periode</th>
                        <th class="py-3 px-4 font-medium text-right">Nominal</th>
                        <th class="py-3 px-4 font-medium text-center">Bukti Bayar</th>
                        <th class="py-3 px-4 font-medium">Status & Keterangan</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-600 dark:text-gray-300">
                    @forelse ($riwayat as $item)
                        <tr class="border-b border-gray-50 dark:border-white/5 hover:bg-gray-50/50 dark:hover:bg-white/5 transition">
                            
                            <td class="py-4 px-4 font-medium">
                                {{ \Carbon\Carbon::parse($item->tanggal_bayar)->translatedFormat('d F Y') }}
                            </td>
                            
                            <td class="py-4 px-4">
                                <span class="font-bold text-darkText dark:text-white">Kas {{ $namaBulan[$item->periode->bulan] ?? '' }} {{ $item->periode->tahun ?? '' }}</span>
                            </td>
                            
                            <td class="py-4 px-4 font-bold text-brand dark:text-brandHover text-right whitespace-nowrap">
                                Rp {{ number_format($item->jumlah_bayar, 0, ',', '.') }}
                            </td>
                            
                            <td class="py-4 px-4 text-center">
                                @if(Str::endsWith(strtolower($item->bukti_transfer), '.pdf'))
                                    <a href="{{ asset('storage/' . $item->bukti_transfer) }}" target="_blank" class="inline-flex flex-col items-center justify-center p-2 w-14 h-14 rounded-lg border border-gray-200 dark:border-navy-600 bg-gray-50 dark:bg-navy-800 hover:border-red-400 transition group">
                                        <i class="fa-solid fa-file-pdf text-xl text-red-500 mb-1 group-hover:scale-110 transition-transform"></i>
                                    </a>
                                @else
                                    <div x-data="{ openImage: false }">
                                        <button @click="openImage = true" type="button" class="relative group block mx-auto overflow-hidden rounded-lg w-14 h-14 border border-gray-200 dark:border-navy-600 hover:border-brand transition">
                                            <img src="{{ asset('storage/' . $item->bukti_transfer) }}" alt="Bukti" class="object-cover w-full h-full" onerror="this.src='https://via.placeholder.com/150?text=No+Image'">
                                            <div class="absolute inset-0 bg-black/50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition">
                                                <i class="fa-solid fa-magnifying-glass text-white text-xs"></i>
                                            </div>
                                        </button>

                                        <template x-teleport="body">
                                            <div x-show="openImage" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center p-4">
                                                <div x-show="openImage" @click="openImage = false" class="fixed inset-0 bg-gray-900/80 backdrop-blur-sm transition-opacity"></div>
                                                <div x-show="openImage" class="relative max-w-3xl w-full">
                                                    <button type="button" @click="openImage = false" class="absolute -top-10 right-0 text-white hover:text-gray-300">
                                                        <i class="fa-solid fa-xmark text-2xl"></i>
                                                    </button>
                                                    <img src="{{ asset('storage/' . $item->bukti_transfer) }}" class="w-full h-auto rounded-xl shadow-2xl max-h-[80vh] object-contain bg-black/50">
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                @endif
                            </td>

                            <td class="py-4 px-4">
                                @if($item->status === 'pending')
                                    <span class="inline-flex items-center px-3 py-1 bg-yellow-50 dark:bg-yellow-500/10 text-yellow-600 dark:text-yellow-400 font-bold rounded-lg text-xs">
                                        <i class="fa-solid fa-hourglass-half mr-1.5"></i> Menunggu Verifikasi
                                    </span>
                                @elseif($item->status === 'diterima')
                                    <span class="inline-flex items-center px-3 py-1 bg-green-50 dark:bg-green-500/10 text-green-600 dark:text-green-400 font-bold rounded-lg text-xs">
                                        <i class="fa-solid fa-check mr-1.5"></i> Lunas
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 font-bold rounded-lg text-xs">
                                        <i class="fa-solid fa-xmark mr-1.5"></i> Ditolak
                                    </span>
                                @endif
                                
                                @if($item->keterangan)
                                    <div class="mt-2 text-xs text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-navy-900 p-2 rounded-lg border border-gray-100 dark:border-white/5">
                                        <span class="font-bold">Catatan Admin:</span> <br>
                                        {{ $item->keterangan }}
                                    </div>
                                @endif
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-10 text-center text-gray-400">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="fa-solid fa-folder-open text-4xl mb-3 text-gray-300 dark:text-gray-600"></i>
                                    <p>Anda belum memiliki riwayat pembayaran kas.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($riwayat->hasPages())
            <div class="mt-6 pt-4 border-t border-gray-100 dark:border-white/10">
                {{ $riwayat->links() }}
            </div>
        @endif

    </div>
</x-app-layout>