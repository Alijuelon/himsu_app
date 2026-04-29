<x-guest-layout>
    
    <div class="mb-10 text-center sm:text-left">
        <h2 class="text-3xl font-extrabold text-darkText dark:text-white">Pendaftaran Anggota 🎉</h2>
        <p class="text-gray-500 dark:text-gray-400 mt-2 text-sm sm:text-base leading-relaxed">
            Satu langkah lagi untuk bisa mengecek dan memantau uang kas Anda secara mandiri & praktis!
        </p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        <div class="space-y-2">
            <label for="nama_lengkap" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                Nama Lengkap (Sesuai KTP) <span class="text-red-500">*</span>
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i class="fa-regular fa-user text-gray-400"></i>
                </div>
                <input id="nama_lengkap" type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}" required autofocus autocomplete="nama_lengkap" 
                    class="w-full py-3.5 pl-11 pr-4 bg-gray-50 dark:bg-navy-900 border border-transparent hover:border-gray-200 focus:border-transparent dark:border-white/10 dark:hover:border-white/20 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand font-medium text-gray-800 dark:text-white transition-all placeholder-gray-400 shadow-sm" 
                    placeholder="Contoh: Budi Santoso">
            </div>
            <x-input-error :messages="$errors->get('name')" class="mt-1 text-red-500 text-xs font-semibold" />
        </div>

        <div class="space-y-2">
            <label for="email" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                Alamat Email Aktif <span class="text-red-500">*</span>
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i class="fa-regular fa-envelope text-gray-400"></i>
                </div>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" 
                    class="w-full py-3.5 pl-11 pr-4 bg-gray-50 dark:bg-navy-900 border border-transparent hover:border-gray-200 focus:border-transparent dark:border-white/10 dark:hover:border-white/20 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand font-medium text-gray-800 dark:text-white transition-all placeholder-gray-400 shadow-sm" 
                    placeholder="nama@email.com">
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-1 text-red-500 text-xs font-semibold" />
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div class="space-y-2">
                <label for="password" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                    Kata Sandi Baru <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i class="fa-solid fa-lock text-gray-400"></i>
                    </div>
                    <input id="password" type="password" name="password" required autocomplete="new-password" 
                        class="w-full py-3.5 pl-11 pr-4 bg-gray-50 dark:bg-navy-900 border border-transparent hover:border-gray-200 focus:border-transparent dark:border-white/10 dark:hover:border-white/20 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand font-medium text-gray-800 dark:text-white transition-all placeholder-gray-400 shadow-sm" 
                        placeholder="Min. 8 Karakter">
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-1 text-red-500 text-xs font-semibold" />
            </div>

            <div class="space-y-2">
                <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                    Konfirmasi Sandi <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i class="fa-solid fa-shield-check text-gray-400"></i>
                    </div>
                    <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" 
                        class="w-full py-3.5 pl-11 pr-4 bg-gray-50 dark:bg-navy-900 border border-transparent hover:border-gray-200 focus:border-transparent dark:border-white/10 dark:hover:border-white/20 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand font-medium text-gray-800 dark:text-white transition-all placeholder-gray-400 shadow-sm" 
                        placeholder="Ketik ulan sandi">
                </div>
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1 text-red-500 text-xs font-semibold" />
            </div>
        </div>

        <div class="pt-6">
            <button type="submit" class="w-full flex justify-center items-center gap-2 py-4 px-4 border border-transparent rounded-xl shadow-lg shadow-brand/30 text-sm font-bold text-white bg-brand hover:bg-brandHover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand transition-all transform hover:-translate-y-0.5">
                {{ __('Buat Akun Sekarang') }} <i class="fa-solid fa-user-plus"></i>
            </button>
        </div>

        <div class="pt-6 text-center text-sm font-medium text-gray-500 dark:text-gray-400">
            Sudah terdaftar sebagai anggota? 
            <a href="{{ route('login') }}" class="text-brand hover:text-brandHover font-bold ml-1 transition-colors border-b-2 border-transparent hover:border-brand">
                Masuk Dasbor
            </a>
        </div>
    </form>
</x-guest-layout>