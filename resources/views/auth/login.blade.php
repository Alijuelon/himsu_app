<x-guest-layout>
    <x-auth-session-status class="mb-6 font-bold text-green-600 bg-green-50 p-4 rounded-xl" :status="session('status')" />

    <div class="mb-10 text-center sm:text-left">
        <h2 class="text-3xl font-extrabold text-darkText dark:text-white">Selamat Datang Kembali 👋</h2>
        <p class="text-gray-500 dark:text-gray-400 mt-2 text-sm sm:text-base leading-relaxed">
            Silakan masukkan email dan kata sandi Anda untuk mengakses Dasbor Keuangan Kas Anda.
        </p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <div class="space-y-2">
            <label for="email" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                Alamat Email <span class="text-red-500">*</span>
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i class="fa-regular fa-envelope text-gray-400"></i>
                </div>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" 
                    class="w-full py-3.5 pl-11 pr-4 bg-gray-50 dark:bg-navy-900 border border-transparent hover:border-gray-200 focus:border-transparent dark:border-white/10 dark:hover:border-white/20 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand font-medium text-gray-800 dark:text-white transition-all placeholder-gray-400 shadow-sm" 
                    placeholder="nama@email.com">
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-500 text-xs font-semibold" />
        </div>

        <div class="space-y-2">
            <label for="password" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                Kata Sandi <span class="text-red-500">*</span>
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i class="fa-solid fa-lock text-gray-400"></i>
                </div>
                <input id="password" type="password" name="password" required autocomplete="current-password" 
                    class="w-full py-3.5 pl-11 pr-4 bg-gray-50 dark:bg-navy-900 border border-transparent hover:border-gray-200 focus:border-transparent dark:border-white/10 dark:hover:border-white/20 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand font-medium text-gray-800 dark:text-white transition-all placeholder-gray-400 shadow-sm" 
                    placeholder="Minimal 8 Karakter">
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-500 text-xs font-semibold" />
        </div>

        <div class="flex items-center justify-between pt-2">
            <label for="remember_me" class="inline-flex items-center cursor-pointer group">
                <div class="relative flex items-center justify-center w-5 h-5 mr-3">
                    <input id="remember_me" type="checkbox" name="remember" class="peer appearance-none w-5 h-5 border-2 border-gray-300 dark:border-gray-600 rounded bg-white dark:bg-navy-900 checked:bg-brand checked:border-brand focus:outline-none focus:ring-2 focus:ring-brand/50 transition-all cursor-pointer">
                    <i class="fa-solid fa-check text-white text-xs absolute pointer-events-none opacity-0 peer-checked:opacity-100 transition-opacity"></i>
                </div>
                <span class="text-sm font-medium text-gray-600 dark:text-gray-400 group-hover:text-darkText dark:group-hover:text-white transition-colors">
                    {{ __('Ingat Saya') }}
                </span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm text-brand hover:text-brandHover font-bold transition-colors" href="{{ route('password.request') }}">
                    {{ __('Lupa Password?') }}
                </a>
            @endif
        </div>

        <div class="pt-4">
            <button type="submit" class="w-full flex justify-center items-center gap-2 py-4 px-4 border border-transparent rounded-xl shadow-lg shadow-brand/30 text-sm font-bold text-white bg-brand hover:bg-brandHover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand transition-all transform hover:-translate-y-0.5">
                {{ __('Masuk Dasbor') }} <i class="fa-solid fa-arrow-right-to-bracket"></i>
            </button>
        </div>

        <div class="pt-6 text-center text-sm font-medium text-gray-500 dark:text-gray-400">
            Anggota Baru HIMSU? 
            <a href="{{ route('register') }}" class="text-brand hover:text-brandHover font-bold ml-1 transition-colors border-b-2 border-transparent hover:border-brand">
                Daftar Akun
            </a>
        </div>
    </form>
</x-guest-layout>