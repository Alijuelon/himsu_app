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

        <div class="space-y-2">
            <label for="jabatan" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                Jabatan / Divisi <span class="text-gray-400 font-normal text-xs">(Opsional)</span>
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i class="fa-solid fa-briefcase text-gray-400"></i>
                </div>
                <input id="jabatan" type="text" name="jabatan" value="{{ old('jabatan') }}" autocomplete="organization-title" 
                    class="w-full py-3.5 pl-11 pr-4 bg-gray-50 dark:bg-navy-900 border border-transparent hover:border-gray-200 focus:border-transparent dark:border-white/10 dark:hover:border-white/20 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand font-medium text-gray-800 dark:text-white transition-all placeholder-gray-400 shadow-sm" 
                    placeholder="Contoh: Anggota Biasa, Divisi Kominfo, dll">
            </div>
            <x-input-error :messages="$errors->get('jabatan')" class="mt-1 text-red-500 text-xs font-semibold" />
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

        <div class="space-y-4 pt-4 border-t border-gray-100 dark:border-white/10">
            <h3 class="text-lg font-bold text-darkText dark:text-white">Data Alamat (Sumatera Utara)</h3>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                        Kabupaten / Kota <span class="text-red-500">*</span>
                    </label>
                    <select id="kabupaten_select" required class="w-full py-3.5 px-4 bg-gray-50 dark:bg-navy-900 border border-transparent hover:border-gray-200 focus:border-transparent dark:border-white/10 dark:hover:border-white/20 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand font-medium text-gray-800 dark:text-white transition-all">
                        <option value="">-- Pilih Kabupaten/Kota --</option>
                    </select>
                    <input type="hidden" id="kabupaten_name" name="kabupaten">
                    <x-input-error :messages="$errors->get('kabupaten')" class="mt-1 text-red-500 text-xs font-semibold" />
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                        Kecamatan <span class="text-red-500">*</span>
                    </label>
                    <select id="kecamatan_select" required disabled class="w-full py-3.5 px-4 bg-gray-50 dark:bg-navy-900 border border-transparent hover:border-gray-200 focus:border-transparent dark:border-white/10 dark:hover:border-white/20 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand font-medium text-gray-800 dark:text-white transition-all opacity-50">
                        <option value="">-- Pilih Kecamatan --</option>
                    </select>
                    <input type="hidden" id="kecamatan_name" name="kecamatan">
                    <x-input-error :messages="$errors->get('kecamatan')" class="mt-1 text-red-500 text-xs font-semibold" />
                </div>

                <div class="space-y-2 sm:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                        Desa / Kelurahan <span class="text-red-500">*</span>
                    </label>
                    <select id="desa_select" required disabled class="w-full py-3.5 px-4 bg-gray-50 dark:bg-navy-900 border border-transparent hover:border-gray-200 focus:border-transparent dark:border-white/10 dark:hover:border-white/20 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand font-medium text-gray-800 dark:text-white transition-all opacity-50">
                        <option value="">-- Pilih Desa/Kelurahan --</option>
                    </select>
                    <input type="hidden" id="desa_name" name="desa">
                    <x-input-error :messages="$errors->get('desa')" class="mt-1 text-red-500 text-xs font-semibold" />
                </div>

                <div class="space-y-2 sm:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                        Detail Alamat (Jalan, RT/RW, Patokan) <span class="text-red-500">*</span>
                    </label>
                    <textarea name="alamat" required rows="2" class="w-full py-3 px-4 bg-gray-50 dark:bg-navy-900 border border-transparent hover:border-gray-200 focus:border-transparent dark:border-white/10 dark:hover:border-white/20 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand font-medium text-gray-800 dark:text-white transition-all" placeholder="Contoh: Jl. Sudirman No. 12, RT 01/RW 02, Dekat Masjid Raya">{{ old('alamat') }}</textarea>
                    <x-input-error :messages="$errors->get('alamat')" class="mt-1 text-red-500 text-xs font-semibold" />
                </div>
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

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const kabSelect = document.getElementById('kabupaten_select');
        const kecSelect = document.getElementById('kecamatan_select');
        const desaSelect = document.getElementById('desa_select');
        
        const kabName = document.getElementById('kabupaten_name');
        const kecName = document.getElementById('kecamatan_name');
        const desaName = document.getElementById('desa_name');

        // Fetch Kabupaten Sumut (Provinsi ID: 12)
        fetch('https://ibnux.github.io/data-indonesia/kabupaten/12.json')
            .then(response => response.json())
            .then(regencies => {
                regencies.forEach(reg => {
                    let option = document.createElement('option');
                    option.value = reg.id;
                    option.textContent = reg.nama; // ibnux uses 'nama'
                    kabSelect.appendChild(option);
                });
            })
            .catch(error => console.error('Error fetching regencies:', error));

        kabSelect.addEventListener('change', function() {
            if (this.selectedIndex > 0) {
                kabName.value = this.options[this.selectedIndex].text;
            } else {
                kabName.value = '';
            }
            
            kecSelect.innerHTML = '<option value="">-- Pilih Kecamatan --</option>';
            desaSelect.innerHTML = '<option value="">-- Pilih Desa/Kelurahan --</option>';
            kecSelect.disabled = true; kecSelect.classList.add('opacity-50');
            desaSelect.disabled = true; desaSelect.classList.add('opacity-50');
            kecName.value = '';
            desaName.value = '';
            
            if(this.value) {
                kecSelect.disabled = false; kecSelect.classList.remove('opacity-50');
                fetch(`https://ibnux.github.io/data-indonesia/kecamatan/${this.value}.json`)
                    .then(response => response.json())
                    .then(districts => {
                        districts.forEach(dist => {
                            let option = document.createElement('option');
                            option.value = dist.id;
                            option.textContent = dist.nama; // ibnux uses 'nama'
                            kecSelect.appendChild(option);
                        });
                    })
                    .catch(error => console.error('Error fetching districts:', error));
            }
        });

        kecSelect.addEventListener('change', function() {
            if (this.selectedIndex > 0) {
                kecName.value = this.options[this.selectedIndex].text;
            } else {
                kecName.value = '';
            }
            
            desaSelect.innerHTML = '<option value="">-- Pilih Desa/Kelurahan --</option>';
            desaSelect.disabled = true; desaSelect.classList.add('opacity-50');
            desaName.value = '';
            
            if(this.value) {
                desaSelect.disabled = false; desaSelect.classList.remove('opacity-50');
                fetch(`https://ibnux.github.io/data-indonesia/kelurahan/${this.value}.json`)
                    .then(response => response.json())
                    .then(villages => {
                        villages.forEach(vill => {
                            let option = document.createElement('option');
                            option.value = vill.id;
                            option.textContent = vill.nama; // ibnux uses 'nama'
                            desaSelect.appendChild(option);
                        });
                    })
                    .catch(error => console.error('Error fetching villages:', error));
            }
        });

        desaSelect.addEventListener('change', function() {
            if (this.selectedIndex > 0) {
                desaName.value = this.options[this.selectedIndex].text;
            } else {
                desaName.value = '';
            }
        });
    });
    </script>
</x-guest-layout>