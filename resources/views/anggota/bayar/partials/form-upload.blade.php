<div x-data="{ fileUrl: null, fileName: '', isPdf: false }">
    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Bukti Transfer (JPG, PNG, PDF) <span class="text-red-500">*</span></label>

    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 dark:border-gray-600 border-dashed rounded-xl hover:border-brand dark:hover:border-brand transition-colors bg-lightBg dark:bg-navy-800 relative group overflow-hidden">

        <div class="space-y-1 text-center" x-show="!fileUrl">
            <i class="fa-solid fa-cloud-arrow-up text-4xl text-gray-400 mb-3 group-hover:text-brand transition-colors"></i>
            <div class="flex text-sm text-gray-600 dark:text-gray-400 justify-center">
                <label class="relative cursor-pointer bg-transparent rounded-md font-bold text-brand hover:text-brandHover focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-brand">
                    <span>Pilih file gambar atau PDF</span>
                    <input x-ref="fileInput" name="bukti_transfer" type="file" class="sr-only" accept=".jpeg, .png, .jpg, .pdf" required
                        @change="
                            const file = $event.target.files[0];
                            if (file) {
                                fileName = file.name;
                                isPdf = file.type === 'application/pdf';
                                fileUrl = URL.createObjectURL(file);
                            }
                        ">
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

            <button type="button" @click="fileUrl = null; fileName = ''; isPdf = false; $refs.fileInput.value = ''" class="absolute -top-3 -right-3 bg-red-500 text-white rounded-full w-8 h-8 flex items-center justify-center hover:bg-red-600 shadow-md">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
    </div>
    <x-input-error :messages="$errors->get('bukti_transfer')" class="mt-2 text-red-500 text-xs" />
</div>

<div class="bg-blue-50 dark:bg-blue-500/10 p-4 rounded-xl border border-blue-100 dark:border-blue-500/20 flex items-start mt-4">
    <i class="fa-solid fa-circle-info text-blue-500 mt-0.5 mr-3"></i>
    <div class="text-sm text-blue-800 dark:text-blue-300">
        <p class="font-bold mb-1">Informasi Transfer Rekening:</p>
        @if(isset($rekeningBank) && $rekeningBank->isNotEmpty())
            @foreach($rekeningBank as $bank)
                <p>{{ $bank->nama_bank }}: <strong>{{ $bank->no_rekening }}</strong> 
                @if($bank->atas_nama)
                    a.n {{ $bank->atas_nama }}
                @endif
                </p>
            @endforeach
        @else
            <p>Admin belum mengatur informasi rekening bank.</p>
        @endif
    </div>
</div>
