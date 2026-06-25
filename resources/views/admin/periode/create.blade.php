<x-app-layout>
    <x-slot name="header">Buat Pembayaran Kas Baru</x-slot>

    <div class="max-w-2xl mx-auto bg-white dark:bg-navy-700 rounded-xl shadow-sm p-6 sm:p-8 border border-transparent dark:border-white/5 transition-colors">
        
        <form action="{{ route('admin.periode.store') }}" method="POST" class="space-y-5">
            @csrf
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tahun <span class="text-red-500">*</span></label>
                    <input type="number" name="tahun" value="{{ old('tahun', date('Y')) }}" required class="w-full py-2.5 px-4 bg-lightBg dark:bg-navy-800 border border-transparent dark:border-white/10 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand text-gray-700 dark:text-white transition-all">
                    <p class="mt-2 text-xs text-gray-500">Sistem akan membuat 12 bulan sekaligus untuk tahun ini.</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nominal Wajib (Rp) <span class="text-red-500">*</span></label>
                    <input type="number" name="nominal_wajib" value="{{ old('nominal_wajib', 50000) }}" required class="w-full py-2.5 px-4 bg-lightBg dark:bg-navy-800 border border-transparent dark:border-white/10 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand text-gray-700 dark:text-white transition-all">
                </div>
            </div>

            <div class="pt-4 flex items-center justify-end space-x-3 border-t border-gray-100 dark:border-white/10">
                <a href="{{ route('admin.periode.index') }}" class="px-5 py-2.5 text-sm font-medium text-gray-500 hover:text-gray-700 transition">Batal</a>
                <button type="submit" class="bg-brand text-white text-sm font-bold px-6 py-2.5 rounded-xl hover:bg-brandHover transition shadow-sm">Simpan Periode</button>
            </div>
        </form>
    </div>
</x-app-layout>