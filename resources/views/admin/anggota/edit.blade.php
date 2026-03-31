<x-app-layout>
    <x-slot name="header">
        Edit Data Anggota
    </x-slot>

    <div class="max-w-3xl mx-auto bg-white dark:bg-navy-700 rounded-xl shadow-sm p-6 sm:p-8 border border-transparent dark:border-white/5 transition-colors">
        
        <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-100 dark:border-white/10">
            <div>
                <h3 class="text-lg font-bold text-darkText dark:text-white">Formulir Pembaruan Data</h3>
                <p class="text-sm text-gray-400">Ubah data untuk anggota: <span class="font-semibold text-brand">{{ $anggota->nama_lengkap }}</span></p>
            </div>
            <a href="{{ route('admin.anggota.index') }}" class="w-10 h-10 flex items-center justify-center rounded-full bg-lightBg dark:bg-navy-800 text-gray-500 hover:text-brand transition">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
        </div>

        <form action="{{ route('admin.anggota.update', $anggota->id) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $anggota->nama_lengkap) }}" required class="w-full py-2.5 px-4 bg-lightBg dark:bg-navy-800 border border-transparent dark:border-white/10 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand text-gray-700 dark:text-white transition-all placeholder-gray-400">
                <x-input-error :messages="$errors->get('nama_lengkap')" class="mt-2 text-red-500 text-xs" />
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email <span class="text-red-500">*</span></label>
                    <input type="email" name="email" value="{{ old('email', $anggota->email) }}" required class="w-full py-2.5 px-4 bg-lightBg dark:bg-navy-800 border border-transparent dark:border-white/10 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand text-gray-700 dark:text-white transition-all placeholder-gray-400">
                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-500 text-xs" />
                </div>
                
                <div x-data="{ showPassword: false }">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Password Baru (Opsional)</label>
                    <div class="relative">
                        <input :type="showPassword ? 'text' : 'password'" name="password" class="w-full py-2.5 pl-4 pr-10 bg-lightBg dark:bg-navy-800 border border-transparent dark:border-white/10 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand text-gray-700 dark:text-white transition-all placeholder-gray-400" placeholder="Kosongkan jika tidak ingin diubah">
                        <button type="button" @click="showPassword = !showPassword" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-brand focus:outline-none">
                            <i class="fa-solid" :class="showPassword ? 'fa-eye-slash' : 'fa-eye'"></i>
                        </button>
                    </div>
                    <p class="text-xs text-gray-400 mt-1">Hanya isi jika Anda ingin mereset password anggota ini.</p>
                    <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-500 text-xs" />
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Username</label>
                    <input type="text" name="username" value="{{ old('username', $anggota->username) }}" class="w-full py-2.5 px-4 bg-lightBg dark:bg-navy-800 border border-transparent dark:border-white/10 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand text-gray-700 dark:text-white transition-all placeholder-gray-400">
                    <x-input-error :messages="$errors->get('username')" class="mt-2 text-red-500 text-xs" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">No. Handphone</label>
                    <input type="text" name="no_hp" value="{{ old('no_hp', $anggota->no_hp) }}" class="w-full py-2.5 px-4 bg-lightBg dark:bg-navy-800 border border-transparent dark:border-white/10 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand text-gray-700 dark:text-white transition-all placeholder-gray-400">
                    <x-input-error :messages="$errors->get('no_hp')" class="mt-2 text-red-500 text-xs" />
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Alamat Domisili</label>
                <textarea name="alamat" rows="3" class="w-full py-2.5 px-4 bg-lightBg dark:bg-navy-800 border border-transparent dark:border-white/10 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand text-gray-700 dark:text-white transition-all placeholder-gray-400">{{ old('alamat', $anggota->alamat) }}</textarea>
                <x-input-error :messages="$errors->get('alamat')" class="mt-2 text-red-500 text-xs" />
            </div>

            <div class="pt-4 flex items-center justify-end space-x-3 border-t border-gray-100 dark:border-white/10">
                <a href="{{ route('admin.anggota.index') }}" class="px-5 py-2.5 text-sm font-medium text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 transition">Batal</a>
                <button type="submit" class="bg-brand text-white text-sm font-bold px-6 py-2.5 rounded-xl hover:bg-brandHover transition shadow-sm">
                    Simpan Perubahan
                </button>
            </div>
        </form>

    </div>
</x-app-layout>