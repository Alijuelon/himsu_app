<x-app-layout>
    <x-slot name="header">
        Form Pembayaran Kas
    </x-slot>

    <div class="max-w-4xl mx-auto" x-data="{ 
        tab: '{{ $tagihanTerlewat->isNotEmpty() ? 'terlewat' : 'saatini' }}',
        updateNominal: function(event) {
            const select = event.target;
            const nominal = select.options[select.selectedIndex]?.getAttribute('data-nominal');
            document.getElementById('jumlah_bayar').value = nominal ? Math.round(nominal) : '';
            
            // Sync periode id
            document.getElementById('real_periode_id').value = select.value;
        }
    }">
        <!-- Tab Navigation -->
        <div class="flex space-x-1 p-1 bg-gray-100/50 dark:bg-navy-900/50 rounded-2xl border border-gray-200 dark:border-white/10 mb-6 max-w-md mx-auto">
            <button @click="tab = 'terlewat'; document.getElementById('select_terlewat').value = ''; document.getElementById('jumlah_bayar').value = ''; document.getElementById('real_periode_id').value = '';" 
                :class="{'bg-white dark:bg-navy-700 shadow-md text-red-500 dark:text-red-500': tab === 'terlewat', 'text-gray-500 hover:text-gray-700 dark:hover:text-gray-300': tab !== 'terlewat'}"
                class="flex-1 py-2.5 px-4 rounded-xl text-sm font-bold transition-all flex items-center justify-center relative">
                <i class="fa-solid fa-clock-rotate-left mr-2"></i> Tagihan Terlewat
                @if($tagihanTerlewat->isNotEmpty())
                    <span class="absolute -top-1 -right-1 bg-red-500 text-white text-[10px] px-1.5 py-0.5 rounded-full">{{ $tagihanTerlewat->count() }}</span>
                @endif
            </button>
            <button @click="tab = 'saatini'; document.getElementById('select_saatini').value = ''; document.getElementById('jumlah_bayar').value = ''; document.getElementById('real_periode_id').value = '';"
                :class="{'bg-white dark:bg-navy-700 shadow-md text-brand dark:text-brand': tab === 'saatini', 'text-gray-500 hover:text-gray-700 dark:hover:text-gray-300': tab !== 'saatini'}"
                class="flex-1 py-2.5 px-4 rounded-xl text-sm font-bold transition-all flex items-center justify-center">
                <i class="fa-solid fa-calendar-check mr-2"></i> Bulan Ini / Depan
            </button>
        </div>

        <div class="bg-white dark:bg-navy-700 rounded-xl shadow-sm p-6 sm:p-8 border border-transparent dark:border-white/5 transition-colors">
            
            <!-- Dynamic Header Info -->
            <div class="mb-6 pb-4 border-b border-gray-100 dark:border-white/10">
                <div x-show="tab === 'terlewat'" style="display: none;">
                    <h3 class="text-lg font-bold text-red-500">Tagihan Kas Terlewat</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Sangat disarankan memprioritaskan pembayaran untuk bulan-bulan yang telah terlewat ini.</p>
                </div>
                <div x-show="tab === 'saatini'" style="display: none;">
                    <h3 class="text-lg font-bold text-brand">Pembayaran Kas Baru</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Silakan pilih bulan kas untuk saat ini atau periode mendatang.</p>
                </div>
            </div>

            <form action="{{ route('anggota.bayar.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                <input type="hidden" name="jenis_pembayaran" value="biasa">
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Pilih Bulan Tagihan <span class="text-red-500">*</span></label>
                        
                        <!-- Select Terlewat -->
                        <div x-show="tab === 'terlewat'" style="display: none;">
                            <select id="select_terlewat" name="periode_id" x-bind:disabled="tab !== 'terlewat'" required @change="updateNominal($event)" class="w-full py-2.5 px-4 bg-red-50 dark:bg-navy-800 border border-red-100 dark:border-red-900/30 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500 text-gray-700 dark:text-white transition-all">
                                <option value="" data-nominal="">-- Pilih Bulan Terlewat --</option>
                                @if($tagihanTerlewat->isNotEmpty())
                                    @foreach($tagihanTerlewat as $periode)
                                    <option value="{{ $periode->id }}" data-nominal="{{ $periode->nominal_wajib }}">
                                        {{ $namaBulan[$periode->bulan] }} {{ $periode->tahun }} 
                                    </option>
                                    @endforeach
                                @else
                                    <option value="" disabled>Anda tidak memiliki tagihan terlewat</option>
                                @endif
                            </select>
                            @if($tagihanTerlewat->isEmpty())
                                <p class="text-xs text-green-500 mt-2"><i class="fa-solid fa-check-circle"></i> Bersih dari tunggakan kas lama!</p>
                            @endif
                        </div>

                        <!-- Select Saat Ini -->
                        <div x-show="tab === 'saatini'" style="display: none;">
                            <select id="select_saatini" name="periode_id" x-bind:disabled="tab !== 'saatini'" required @change="updateNominal($event)" class="w-full py-2.5 px-4 bg-lightBg dark:bg-navy-800 border border-transparent dark:border-white/10 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand text-gray-700 dark:text-white transition-all">
                                <option value="" data-nominal="">-- Pilih Bulan Saat Ini/Depan --</option>
                                @if($tagihanMendatang->isNotEmpty())
                                    @foreach($tagihanMendatang as $periode)
                                    <option value="{{ $periode->id }}" data-nominal="{{ $periode->nominal_wajib }}" class="{{ $periode->bulan == $bulanIni && $periode->tahun == $tahunIni ? 'font-bold text-brand dark:text-brand' : '' }}">
                                        {{ $namaBulan[$periode->bulan] }} {{ $periode->tahun }} 
                                        @if($periode->bulan == $bulanIni && $periode->tahun == $tahunIni)
                                            - [Bulan Ini (Prioritas)]
                                        @endif
                                    </option>
                                    @endforeach
                                @else
                                    <option value="" disabled>Anda sudah melunasi seluruh periode aktif tahun ini</option>
                                @endif
                            </select>
                        </div>

                        <x-input-error :messages="$errors->get('periode_id')" class="mt-2 text-red-500 text-xs" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nominal Transfer (Rp) <span class="text-red-500">*</span></label>
                        <input type="number" id="jumlah_bayar" name="jumlah_bayar" value="{{ old('jumlah_bayar') }}" required readonly placeholder="Pilih bulan terlebih dahulu" class="w-full py-2.5 px-4 bg-gray-100 dark:bg-navy-900 border border-transparent dark:border-white/10 rounded-xl focus:outline-none text-gray-800 font-bold dark:text-gray-200 cursor-not-allowed transition-all">
                        <p class="text-xs text-gray-400 mt-1">Nominal otomatis diisi sesuai ketetapan admin.</p>
                        <x-input-error :messages="$errors->get('jumlah_bayar')" class="mt-2 text-red-500 text-xs" />
                    </div>
                </div>
                
                @include('anggota.bayar.partials.form-upload')

                <div class="pt-4 flex items-center justify-end space-x-3 border-t border-gray-100 dark:border-white/10">
                    <a href="{{ route('dashboard') }}" class="px-5 py-2.5 text-sm font-medium text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 transition">Batal</a>
                    <button type="submit" class="bg-brand text-white text-sm font-bold px-6 py-2.5 rounded-xl hover:bg-brandHover transition shadow-sm">
                        Kirim Bukti Pembayaran
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
