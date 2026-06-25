<x-app-layout>
    <x-slot name="header">
        Detail Tagihan Anggota
    </x-slot>

    <div class="bg-white dark:bg-navy-700 rounded-xl shadow-sm p-6 border border-transparent dark:border-white/5 transition-colors">
        <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-100 dark:border-white/10">
            <div>
                <h3 class="text-lg font-bold text-darkText dark:text-white">Status Pembayaran Kas</h3>
                <p class="text-sm text-gray-400">Periode: {{ $namaBulan[$periode->bulan] }} {{ $periode->tahun }} - Wajib: Rp {{ number_format($periode->nominal_wajib, 0, ',', '.') }}</p>
            </div>
            <a href="{{ route('admin.periode.index') }}" class="w-10 h-10 flex items-center justify-center rounded-full bg-lightBg dark:bg-navy-800 text-gray-500 hover:text-brand transition">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
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
                    @foreach($anggotas as $anggota)
                        @php
                            $pembayaran = $pembayarans->get($anggota->id);
                        @endphp
                        <tr class="border-b border-gray-50 dark:border-white/5 hover:bg-gray-50/50 dark:hover:bg-white/5 transition-colors">
                            <td class="px-4 py-4 font-medium text-gray-900 dark:text-white">
                                {{ $anggota->nama_lengkap }}
                            </td>
                            <td class="px-4 py-4 text-gray-500 dark:text-gray-400">
                                {{ $anggota->no_hp ?? '-' }}
                            </td>
                            <td class="px-4 py-4 text-center">
                                @if($pembayaran)
                                    @if($pembayaran->status == 'diterima')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                            <i class="fa-solid fa-check-circle mr-1"></i> Lunas
                                        </span>
                                    @elseif($pembayaran->status == 'pending')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400">
                                            <i class="fa-solid fa-clock mr-1"></i> Menunggu Verifikasi
                                        </span>
                                    @elseif($pembayaran->status == 'ditolak')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">
                                            <i class="fa-solid fa-times-circle mr-1"></i> Ditolak
                                        </span>
                                    @endif
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                        <i class="fa-solid fa-minus-circle mr-1"></i> Belum Bayar
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
