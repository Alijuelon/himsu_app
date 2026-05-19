<x-app-layout>
    <x-slot name="header">
        Form Pembayaran Kas
    </x-slot>

    <div class="max-w-4xl mx-auto" x-data="{ tab: '{{ old('jenis_pembayaran', 'biasa') }}' }">
        <!-- Tabs Navigation -->
        <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-2 bg-white/50 dark:bg-navy-800/50 p-1.5 rounded-xl mb-6 shadow-sm border border-gray-100 dark:border-white/5 w-full sm:w-max">
            <button @click="tab = 'biasa'" :class="tab === 'biasa' ? 'bg-white dark:bg-navy-700 text-brand shadow-sm font-bold' : 'text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 font-medium'" class="px-6 py-2.5 rounded-lg text-sm transition-all duration-200 w-full sm:w-auto">
                <i class="fa-solid fa-money-bill-wave mr-2"></i> Pembayaran Bulan Ini
            </button>
            <button @click="tab = 'tagihan'" :class="tab === 'tagihan' ? 'bg-white dark:bg-navy-700 text-red-500 shadow-sm font-bold' : 'text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 font-medium'" class="px-6 py-2.5 rounded-lg text-sm transition-all duration-200 w-full sm:w-auto">
                <i class="fa-solid fa-file-invoice-dollar mr-2"></i> Tagihan Terlewat
                @if(isset($tagihanTerlewat) && $tagihanTerlewat->count() > 0)
                <span class="ml-1 bg-red-500 text-white text-xs px-2 py-0.5 rounded-full">{{ $tagihanTerlewat->count() }}</span>
                @endif
            </button>
        </div>

        <!-- Form Pembayaran Biasa -->
        <div x-show="tab === 'biasa'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
            <div class="bg-white dark:bg-navy-700 rounded-xl shadow-sm p-6 sm:p-8 border border-transparent dark:border-white/5 transition-colors">
                <div class="mb-6 pb-4 border-b border-gray-100 dark:border-white/10">
                    <h3 class="text-lg font-bold text-darkText dark:text-white">Pembayaran Kas Bulan Ini</h3>
                    <p class="text-sm text-gray-400">Silakan bayar kas rutin Anda yang sedang aktif pada periode ini.</p>
                </div>

                <form action="{{ route('anggota.bayar.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    <input type="hidden" name="jenis_pembayaran" value="biasa">
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Periode Aktif <span class="text-red-500">*</span></label>
                            <select name="periode_id" required class="w-full py-2.5 px-4 bg-lightBg dark:bg-navy-800 border border-transparent dark:border-white/10 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand text-gray-700 dark:text-white transition-all">
                                <option value="">-- Pilih Periode --</option>
                                @if($periodeAktif->isNotEmpty())
                                    @foreach($periodeAktif as $periode)
                                    <option value="{{ $periode->id }}" {{ old('periode_id') == $periode->id ? 'selected' : '' }}>
                                        {{ $namaBulan[$periode->bulan] }} {{ $periode->tahun }} - (Wajib: Rp {{ number_format($periode->nominal_wajib, 0, ',', '.') }})
                                        {{ $periode->deadline ? ' - Tenggat: ' . \Carbon\Carbon::parse($periode->deadline)->format('d M Y') : '' }}
                                    </option>
                                    @endforeach
                                @endif
                            </select>
                            @if($periodeAktif->isEmpty())
                            <p class="text-xs text-green-500 mt-2"><i class="fa-solid fa-check-circle"></i> Anda sudah melunasi semua kas untuk periode aktif saat ini.</p>
                            @endif
                            <x-input-error :messages="$errors->get('periode_id')" class="mt-2 text-red-500 text-xs" />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nominal Transfer (Rp) <span class="text-red-500">*</span></label>
                            <input type="number" name="jumlah_bayar" value="{{ old('jumlah_bayar') }}" required min="1000" placeholder="Contoh: 50000" class="w-full py-2.5 px-4 bg-lightBg dark:bg-navy-800 border border-transparent dark:border-white/10 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand text-gray-700 dark:text-white transition-all">
                            <x-input-error :messages="$errors->get('jumlah_bayar')" class="mt-2 text-red-500 text-xs" />
                        </div>
                    </div>
                    
                    @include('anggota.bayar.partials.form-upload')

                    <div class="pt-4 flex items-center justify-end space-x-3 border-t border-gray-100 dark:border-white/10">
                        <a href="{{ route('dashboard') }}" class="px-5 py-2.5 text-sm font-medium text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 transition">Batal</a>
                        <button type="submit" class="bg-brand text-white text-sm font-bold px-6 py-2.5 rounded-xl hover:bg-brandHover transition shadow-sm" {{ $periodeAktif->isEmpty() ? 'disabled' : '' }}>
                            Kirim Bukti Pembayaran
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Form Pembayaran Tagihan -->
        <div x-show="tab === 'tagihan'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
            <div class="bg-white dark:bg-navy-700 rounded-xl shadow-sm p-6 sm:p-8 border border-red-500/20 transition-colors">
                <div class="mb-6 pb-4 border-b border-gray-100 dark:border-white/10">
                    <h3 class="text-lg font-bold text-red-500 dark:text-red-400">Pembayaran Tagihan Terlewat</h3>
                    <p class="text-sm text-gray-400">Silakan lunasi tagihan kas Anda yang sudah melewati batas waktu (periode tutup).</p>
                </div>

                <form action="{{ route('anggota.bayar.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    <input type="hidden" name="jenis_pembayaran" value="tagihan">
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Pilih Tagihan <span class="text-red-500">*</span></label>
                            <select name="periode_id" required class="w-full py-2.5 px-4 bg-lightBg dark:bg-navy-800 border border-transparent dark:border-white/10 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500 text-gray-700 dark:text-white transition-all">
                                <option value="">-- Pilih Tagihan Terlewat --</option>
                                @if(isset($tagihanTerlewat) && $tagihanTerlewat->isNotEmpty())
                                    @foreach($tagihanTerlewat as $periode)
                                    <option value="{{ $periode->id }}" {{ old('periode_id') == $periode->id ? 'selected' : '' }}>
                                        {{ $namaBulan[$periode->bulan] }} {{ $periode->tahun }} - (Wajib: Rp {{ number_format($periode->nominal_wajib, 0, ',', '.') }})
                                        {{ $periode->deadline ? ' - Tenggat: ' . \Carbon\Carbon::parse($periode->deadline)->format('d M Y') : '' }}
                                    </option>
                                    @endforeach
                                @endif
                            </select>
                            @if(empty($tagihanTerlewat) || $tagihanTerlewat->isEmpty())
                            <p class="text-xs text-green-500 mt-2"><i class="fa-solid fa-check-circle"></i> Anda tidak memiliki tagihan kas yang terlewat.</p>
                            @endif
                            <x-input-error :messages="$errors->get('periode_id')" class="mt-2 text-red-500 text-xs" />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nominal Transfer (Rp) <span class="text-red-500">*</span></label>
                            <input type="number" name="jumlah_bayar" value="{{ old('jumlah_bayar') }}" required min="1000" placeholder="Contoh: 50000" class="w-full py-2.5 px-4 bg-lightBg dark:bg-navy-800 border border-transparent dark:border-white/10 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500 text-gray-700 dark:text-white transition-all">
                            <x-input-error :messages="$errors->get('jumlah_bayar')" class="mt-2 text-red-500 text-xs" />
                        </div>
                    </div>
                    
                    @include('anggota.bayar.partials.form-upload')

                    <div class="pt-4 flex items-center justify-end space-x-3 border-t border-gray-100 dark:border-white/10">
                        <a href="{{ route('dashboard') }}" class="px-5 py-2.5 text-sm font-medium text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 transition">Batal</a>
                        <button type="submit" class="bg-red-500 text-white text-sm font-bold px-6 py-2.5 rounded-xl hover:bg-red-600 transition shadow-sm" {{ (empty($tagihanTerlewat) || $tagihanTerlewat->isEmpty()) ? 'disabled' : '' }}>
                            Lunasi Tagihan
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-app-layout>