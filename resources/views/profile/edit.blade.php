<x-app-layout>
    <x-slot name="header">
        Edit Profile
    </x-slot>

    <div class="max-w-5xl mx-auto px-4 py-10">
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

            <!-- ==================== INFORMASI PROFIL (Kiri) ==================== -->
            <div class="bg-white dark:bg-navy-800 rounded-3xl shadow-sm border border-gray-100 dark:border-white/10 overflow-hidden">
                <div class="px-7 pt-7 pb-5 border-b border-gray-100 dark:border-white/10 bg-gray-50 dark:bg-navy-900/50">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white">
                        Informasi Profil
                    </h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        Perbarui informasi profil, username, dan detail kontak Anda
                    </p>
                </div>

                <div class="p-7">
                    <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
                        @csrf
                        @method('patch')

                        <div>
                            <x-input-label for="nama_lengkap" :value="__('Nama Lengkap')" />
                            <x-text-input 
                                id="nama_lengkap" 
                                name="nama_lengkap" 
                                type="text" 
                                class="mt-2 block w-full rounded-2xl 
                                       bg-white dark:bg-navy-700 
                                       border-gray-300 dark:border-white/25 
                                       text-gray-900 dark:text-white 
                                       placeholder:text-gray-400 dark:placeholder:text-gray-300
                                       focus:border-indigo-500 dark:focus:border-indigo-400"
                                :value="old('nama_lengkap', $user->nama_lengkap)" 
                                required 
                                autofocus 
                            />
                            <x-input-error class="mt-2" :messages="$errors->get('nama_lengkap')" />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="username" :value="__('Username')" />
                                <x-text-input 
                                    id="username" 
                                    name="username" 
                                    type="text" 
                                    class="mt-2 block w-full rounded-2xl 
                                           bg-white dark:bg-navy-700 
                                           border-gray-300 dark:border-white/25 
                                           text-gray-900 dark:text-white 
                                           placeholder:text-gray-400 dark:placeholder:text-gray-300
                                           focus:border-indigo-500 dark:focus:border-indigo-400"
                                    :value="old('username', $user->username)" 
                                    required 
                                />
                                <x-input-error class="mt-2" :messages="$errors->get('username')" />
                            </div>

                            <div>
                                <x-input-label for="no_hp" :value="__('No. HP')" />
                                <x-text-input 
                                    id="no_hp" 
                                    name="no_hp" 
                                    type="text" 
                                    class="mt-2 block w-full rounded-2xl 
                                           bg-white dark:bg-navy-700 
                                           border-gray-300 dark:border-white/25 
                                           text-gray-900 dark:text-white 
                                           placeholder:text-gray-400 dark:placeholder:text-gray-300
                                           focus:border-indigo-500 dark:focus:border-indigo-400"
                                    :value="old('no_hp', $user->no_hp)" 
                                />
                                <x-input-error class="mt-2" :messages="$errors->get('no_hp')" />
                            </div>
                        </div>

                        <div>
                            <x-input-label for="email" :value="__('Alamat Email')" />
                            <x-text-input 
                                id="email" 
                                name="email" 
                                type="email" 
                                class="mt-2 block w-full rounded-2xl 
                                       bg-white dark:bg-navy-700 
                                       border-gray-300 dark:border-white/25 
                                       text-gray-900 dark:text-white 
                                       placeholder:text-gray-400 dark:placeholder:text-gray-300
                                       focus:border-indigo-500 dark:focus:border-indigo-400"
                                :value="old('email', $user->email)" 
                                required 
                            />
                            <x-input-error class="mt-2" :messages="$errors->get('email')" />

                            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                                <div class="mt-4 bg-amber-50 dark:bg-amber-500/10 border border-amber-200 dark:border-amber-400/30 rounded-2xl p-4 text-sm">
                                    <p class="text-amber-700 dark:text-amber-300">
                                        Email belum diverifikasi. 
                                        <button form="send-verification" class="underline font-medium hover:text-amber-800 dark:hover:text-amber-200">
                                            Kirim ulang verifikasi
                                        </button>
                                    </p>
                                </div>
                            @endif
                        </div>

                        <div>
                            <x-input-label for="alamat" :value="__('Alamat Lengkap')" />
                            <textarea 
                                id="alamat" 
                                name="alamat" 
                                rows="3"
                                class="mt-2 block w-full rounded-2xl 
                                       bg-white dark:bg-navy-700 
                                       border-gray-300 dark:border-white/25 
                                       text-gray-900 dark:text-white 
                                       placeholder:text-gray-400 dark:placeholder:text-gray-300
                                       focus:border-indigo-500 dark:focus:border-indigo-400 shadow-sm transition-all duration-200"
                            >{{ old('alamat', $user->alamat) }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('alamat')" />
                        </div>

                        <div class="pt-4">
                            <x-primary-button class="w-full sm:w-auto px-8 py-3 rounded-2xl">
                                Simpan Perubahan
                            </x-primary-button>

                            @if (session('status') === 'profile-updated')
                                <p class="mt-3 text-green-600 dark:text-green-400 text-sm font-medium inline-flex items-center gap-2">
                                    <i class="fa-solid fa-check-circle"></i> Berhasil disimpan
                                </p>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            <!-- ==================== UBAH PASSWORD (Kanan) ==================== -->
            <div class="bg-white dark:bg-navy-800 rounded-3xl shadow-sm border border-gray-100 dark:border-white/10 overflow-hidden">
                <div class="px-7 pt-7 pb-5 border-b border-gray-100 dark:border-white/10 bg-gray-50 dark:bg-navy-900/50">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white">
                        Ubah Password
                    </h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        Buat password baru yang aman
                    </p>
                </div>

                <div class="p-7">
                    <form method="post" action="{{ route('password.update') }}" class="space-y-6">
                        @csrf
                        @method('put')

                        <div>
                            <x-input-label for="current_password" :value="__('Password Saat Ini')" />
                            <x-text-input 
                                id="current_password" 
                                name="current_password" 
                                type="password" 
                                class="mt-2 block w-full rounded-2xl 
                                       bg-white dark:bg-navy-700 
                                       border-gray-300 dark:border-white/25 
                                       text-gray-900 dark:text-white 
                                       placeholder:text-gray-400 dark:placeholder:text-gray-300
                                       focus:border-indigo-500 dark:focus:border-indigo-400"
                                autocomplete="current-password" 
                            />
                            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="password" :value="__('Password Baru')" />
                            <x-text-input 
                                id="password" 
                                name="password" 
                                type="password" 
                                class="mt-2 block w-full rounded-2xl 
                                       bg-white dark:bg-navy-700 
                                       border-gray-300 dark:border-white/25 
                                       text-gray-900 dark:text-white 
                                       placeholder:text-gray-400 dark:placeholder:text-gray-300
                                       focus:border-indigo-500 dark:focus:border-indigo-400"
                                autocomplete="new-password" 
                            />
                            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="password_confirmation" :value="__('Konfirmasi Password Baru')" />
                            <x-text-input 
                                id="password_confirmation" 
                                name="password_confirmation" 
                                type="password" 
                                class="mt-2 block w-full rounded-2xl 
                                       bg-white dark:bg-navy-700 
                                       border-gray-300 dark:border-white/25 
                                       text-gray-900 dark:text-white 
                                       placeholder:text-gray-400 dark:placeholder:text-gray-300
                                       focus:border-indigo-500 dark:focus:border-indigo-400"
                                autocomplete="new-password" 
                            />
                            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
                        </div>

                        <div class="pt-4">
                            <x-primary-button class="w-full sm:w-auto px-8 py-3 rounded-2xl">
                                Update Password
                            </x-primary-button>

                            @if (session('status') === 'password-updated')
                                <p class="mt-3 text-green-600 dark:text-green-400 text-sm font-medium inline-flex items-center gap-2">
                                    <i class="fa-solid fa-check-circle"></i> Password berhasil diubah
                                </p>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>

    <!-- Form verifikasi email tersembunyi -->
    <form id="send-verification" method="post" action="{{ route('verification.send') }}" class="hidden">
        @csrf
    </form>

</x-app-layout>