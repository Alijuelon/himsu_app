<x-app-layout>
    <x-slot name="header">
        Form Pembayaran Kas
    </x-slot>

    <div class="max-w-3xl mx-auto bg-white dark:bg-navy-700 rounded-xl shadow-sm p-6 sm:p-8 border border-transparent dark:border-white/5 transition-colors">

        <div class="mb-6 pb-4 border-b border-gray-100 dark:border-white/10">
            <h3 class="text-lg font-bold text-darkText dark:text-white">Upload Bukti Transfer</h3>
            <p class="text-sm text-gray-400">Silakan pilih tagihan dan unggah bukti pembayaran Anda (Maksimal 2MB).</p>
        </div>

        <form action="{{ route('anggota.bayar.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Periode Tagihan <span class="text-red-500">*</span></label>
                    <select name="periode_id" required class="w-full py-2.5 px-4 bg-lightBg dark:bg-navy-800 border border-transparent dark:border-white/10 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand text-gray-700 dark:text-white transition-all">
                        <option value="">-- Pilih Periode --</option>
                        @foreach($periodeAktif as $periode)
                        <option value="{{ $periode->id }}" {{ old('periode_id') == $periode->id ? 'selected' : '' }}>
                            {{ $namaBulan[$periode->bulan] }} {{ $periode->tahun }} - (Wajib: Rp {{ number_format($periode->nominal_wajib, 0, ',', '.') }})
                        </option>
                        @endforeach
                    </select>
                    @if($periodeAktif->isEmpty())
                    <p class="text-xs text-red-500 mt-2"><i class="fa-solid fa-triangle-exclamation"></i> Belum ada tagihan aktif bulan ini.</p>
                    @endif
                    <x-input-error :messages="$errors->get('periode_id')" class="mt-2 text-red-500 text-xs" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nominal Transfer (Rp) <span class="text-red-500">*</span></label>
                    <input type="number" name="jumlah_bayar" value="{{ old('jumlah_bayar') }}" required min="1000" placeholder="Contoh: 50000" class="w-full py-2.5 px-4 bg-lightBg dark:bg-navy-800 border border-transparent dark:border-white/10 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand text-gray-700 dark:text-white transition-all">
                    <x-input-error :messages="$errors->get('jumlah_bayar')" class="mt-2 text-red-500 text-xs" />
                </div>
            </div>
            <div x-data="{ fileUrl: null, fileName: '', isPdf: false }">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Bukti Transfer (JPG, PNG, PDF) <span class="text-red-500">*</span></label>

                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 dark:border-gray-600 border-dashed rounded-xl hover:border-brand dark:hover:border-brand transition-colors bg-lightBg dark:bg-navy-800 relative group overflow-hidden">

                    <div class="space-y-1 text-center" x-show="!fileUrl">
                        <i class="fa-solid fa-cloud-arrow-up text-4xl text-gray-400 mb-3 group-hover:text-brand transition-colors"></i>
                        <div class="flex text-sm text-gray-600 dark:text-gray-400 justify-center">
                            <label for="file-upload" class="relative cursor-pointer bg-transparent rounded-md font-bold text-brand hover:text-brandHover focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-brand">
                                <span>Pilih file gambar atau PDF</span>
                            </label>
                            <p class="pl-1">atau drag & drop</p>
                        </div>
                        <p class="text-xs text-gray-500">PNG, JPG, JPEG, PDF maksimal 2MB</p>
                    </div>

                    <div x-show="fileUrl" style="display: none;" class="relative w-full text-center">

                        <img x-show="!isPdf" :src="fileUrl" alt="Preview" class="mx-auto h-48 object-contain rounded-lg shadow-sm border border-gray-200 dark:border-white/10">

                        <div x-show="isPdf" class="flex flex-col items-center justify-center h-48 bg-gray-50 dark:bg-navy-900 rounded-lg border border-gray-200 dark:border-white/10">
                            <i class="fa-solid fa-file-pdf text-5xl text-red-500 mb-3"></i>
                            <p class="text-sm font-bold text-gray-700 dark:text-gray-300 px-4 truncate" x-text="fileName"></p>
                            <p class="text-xs text-gray-500 mt-1">Dokumen PDF siap diunggah</p>
                        </div>

                        <button type="button" @click="fileUrl = null; fileName = ''; isPdf = false; document.getElementById('file-upload').value = ''" class="absolute -top-3 -right-3 bg-red-500 text-white rounded-full w-8 h-8 flex items-center justify-center hover:bg-red-600 shadow-md">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>

                    <input id="file-upload" name="bukti_transfer" type="file" class="sr-only" accept=".jpeg, .png, .jpg, .pdf" required
                        @change="
                            const file = $event.target.files[0];
                            if (file) {
                                fileName = file.name;
                                isPdf = file.type === 'application/pdf';
                                fileUrl = URL.createObjectURL(file);
                            }
                        ">
                </div>
                <x-input-error :messages="$errors->get('bukti_transfer')" class="mt-2 text-red-500 text-xs" />
            </div>
            <div class="bg-blue-50 dark:bg-blue-500/10 p-4 rounded-xl border border-blue-100 dark:border-blue-500/20 flex items-start mt-4">
                <i class="fa-solid fa-circle-info text-blue-500 mt-0.5 mr-3"></i>
                <div class="text-sm text-blue-800 dark:text-blue-300">
                    <p class="font-bold mb-1">Informasi Transfer Rekening:</p>
                    <p>Bank Riau Kepri: <strong>123-456-7890</strong> a.n HIMSU Bengkalis</p>
                    <p>Dana / OVO: <strong>0812-3456-7890</strong></p>
                </div>
            </div>

            <div class="pt-4 flex items-center justify-end space-x-3 border-t border-gray-100 dark:border-white/10">
                <a href="{{ route('dashboard') }}" class="px-5 py-2.5 text-sm font-medium text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 transition">Batal</a>
                <button type="submit" class="bg-brand text-white text-sm font-bold px-6 py-2.5 rounded-xl hover:bg-brandHover transition shadow-sm">
                    Kirim Bukti Bayar
                </button>
            </div>
        </form>

    </div>
</x-app-layout>