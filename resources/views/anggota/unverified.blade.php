<x-app-layout>
    <x-slot name="header">
        Menunggu Verifikasi Akun
    </x-slot>

    <div class="max-w-3xl mx-auto mt-10">
        <div class="bg-white dark:bg-navy-700 rounded-2xl shadow-xl overflow-hidden border border-gray-100 dark:border-white/5">
            <div class="p-10 text-center">
                <div class="inline-flex items-center justify-center w-24 h-24 bg-yellow-50 dark:bg-yellow-500/10 text-yellow-500 rounded-full mb-6">
                    <i class="fa-solid fa-user-clock text-5xl"></i>
                </div>
                
                <h2 class="text-3xl font-extrabold text-darkText dark:text-white mb-4">Akun Anda Belum Diverifikasi</h2>
                
                <p class="text-gray-500 dark:text-gray-400 text-lg mb-8 max-w-xl mx-auto leading-relaxed">
                    Halo <span class="font-bold text-darkText dark:text-white">{{ Auth::user()->nama_lengkap }}</span>, pendaftaran Anda telah berhasil! Namun, saat ini akun Anda masih dalam status <span class="font-bold text-yellow-500">Pending</span>. Anda harus menunggu admin untuk memverifikasi akun Anda sebelum dapat mengakses fitur-fitur dasbor dan kas.
                </p>

                <div class="inline-flex items-center p-4 bg-blue-50 dark:bg-brand/10 rounded-xl text-left border border-blue-100 dark:border-brand/20">
                    <div class="text-brand text-2xl mr-4">
                        <i class="fa-solid fa-circle-info"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-darkText dark:text-white text-sm">Informasi</h4>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Silakan hubungi pengurus atau admin jika akun Anda belum diverifikasi dalam waktu 1x24 jam.</p>
                    </div>
                </div>

                <div class="mt-8 pt-8 border-t border-gray-100 dark:border-white/5">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-sm font-bold text-red-500 hover:text-red-600 transition-colors">
                            <i class="fa-solid fa-arrow-right-from-bracket mr-1"></i> Keluar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
