<x-app-layout>
    <x-slot name="header">
        Edit Pembayaran Kas
    </x-slot>

    <div class="max-w-2xl mx-auto bg-white dark:bg-navy-700 rounded-xl shadow-sm p-6 sm:p-8 border border-transparent dark:border-white/5 transition-colors">
        
        <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-100 dark:border-white/10">
            <div>
                <h3 class="text-lg font-bold text-darkText dark:text-white">Formulir Pembaruan Pembayaran</h3>
                <p class="text-sm text-gray-400">Ubah detail tagihan untuk pembayaran ini.</p>
            </div>
            <a href="{{ route('admin.periode.index') }}" class="w-10 h-10 flex items-center justify-center rounded-full bg-lightBg dark:bg-navy-800 text-gray-500 hover:text-brand transition">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
        </div>

        <form action="{{ route('admin.periode.update', $periode->id) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Bulan Tagihan</label>
                    @php
                        $bulanList = [
                            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 
                            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 
                            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                        ];
                    @endphp
                    <input type="text" value="{{ $bulanList[$periode->bulan] ?? '' }}" disabled class="w-full py-2.5 px-4 bg-gray-100 dark:bg-navy-900 border border-transparent dark:border-white/10 rounded-xl text-gray-500 cursor-not-allowed">
                    <input type="hidden" name="bulan" value="{{ $periode->bulan }}">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tahun</label>
                    <input type="number" name="tahun" value="{{ $periode->tahun }}" readonly class="w-full py-2.5 px-4 bg-gray-100 dark:bg-navy-900 border border-transparent dark:border-white/10 rounded-xl text-gray-500 cursor-not-allowed focus:outline-none">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nominal Wajib (Rp) <span class="text-red-500">*</span></label>
                    <input type="number" name="nominal_wajib" value="{{ old('nominal_wajib', (int)$periode->nominal_wajib) }}" required class="w-full py-2.5 px-4 bg-lightBg dark:bg-navy-800 border border-transparent dark:border-white/10 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand text-gray-700 dark:text-white transition-all">
                    <x-input-error :messages="$errors->get('nominal_wajib')" class="mt-2 text-red-500 text-xs" />
                </div>



                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status <span class="text-red-500">*</span></label>
                    <select name="status" required class="w-full py-2.5 px-4 bg-lightBg dark:bg-navy-800 border border-transparent dark:border-white/10 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand text-gray-700 dark:text-white transition-all">
                        <option value="aktif" {{ old('status', $periode->status) == 'aktif' ? 'selected' : '' }}>Aktif (Bisa Dibayar)</option>
                        <option value="tutup" {{ old('status', $periode->status) == 'tutup' ? 'selected' : '' }}>Tutup (Sudah Lewat)</option>
                    </select>
                    <x-input-error :messages="$errors->get('status')" class="mt-2 text-red-500 text-xs" />
                </div>
            </div>

            <div class="pt-4 flex items-center justify-end space-x-3 border-t border-gray-100 dark:border-white/10">
                <a href="{{ route('admin.periode.index') }}" class="px-5 py-2.5 text-sm font-medium text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 transition">Batal</a>
                <button type="submit" class="bg-brand text-white text-sm font-bold px-6 py-2.5 rounded-xl hover:bg-brandHover transition shadow-sm">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</x-app-layout>