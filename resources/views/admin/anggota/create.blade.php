<x-app-layout>
    <x-slot name="header">
        Tambah Anggota Baru
    </x-slot>

    <form action="{{ route('admin.anggota.store') }}" method="POST" class="max-w-6xl mx-auto">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-start">
            <!-- Kiri: Data Akun & Pribadi -->
            <div class="bg-white dark:bg-navy-700 rounded-xl shadow-sm p-6 sm:p-8 border border-transparent dark:border-white/5 transition-colors space-y-5">
                <div class="mb-2 pb-4 border-b border-gray-100 dark:border-white/10">
                    <h3 class="text-lg font-bold text-darkText dark:text-white">Data Akun & Pribadi</h3>
                    <p class="text-sm text-gray-400">Informasi dasar dan kredensial anggota.</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}" required class="w-full py-2.5 px-4 bg-lightBg dark:bg-navy-800 border border-transparent dark:border-white/10 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand text-gray-700 dark:text-white transition-all placeholder-gray-400">
                    <x-input-error :messages="$errors->get('nama_lengkap')" class="mt-2 text-red-500 text-xs" />
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email <span class="text-red-500">*</span></label>
                        <input type="email" name="email" value="{{ old('email') }}" required class="w-full py-2.5 px-4 bg-lightBg dark:bg-navy-800 border border-transparent dark:border-white/10 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand text-gray-700 dark:text-white transition-all placeholder-gray-400">
                        <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-500 text-xs" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Password <span class="text-red-500">*</span></label>
                        <input type="password" name="password" required class="w-full py-2.5 px-4 bg-lightBg dark:bg-navy-800 border border-transparent dark:border-white/10 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand text-gray-700 dark:text-white transition-all placeholder-gray-400">
                        <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-500 text-xs" />
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Username</label>
                        <input type="text" name="username" value="{{ old('username') }}" class="w-full py-2.5 px-4 bg-lightBg dark:bg-navy-800 border border-transparent dark:border-white/10 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand text-gray-700 dark:text-white transition-all placeholder-gray-400">
                        <x-input-error :messages="$errors->get('username')" class="mt-2 text-red-500 text-xs" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">No. Handphone</label>
                        <input type="text" name="no_hp" value="{{ old('no_hp') }}" class="w-full py-2.5 px-4 bg-lightBg dark:bg-navy-800 border border-transparent dark:border-white/10 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand text-gray-700 dark:text-white transition-all placeholder-gray-400">
                        <x-input-error :messages="$errors->get('no_hp')" class="mt-2 text-red-500 text-xs" />
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Jabatan / Divisi <span class="text-red-500">*</span></label>
                    <select name="jabatan" required class="w-full py-2.5 px-4 bg-lightBg dark:bg-navy-800 border border-transparent dark:border-white/10 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand text-gray-700 dark:text-white transition-all appearance-none">
                        <option value="" disabled {{ old('jabatan') ? '' : 'selected' }}>-- Pilih Jabatan --</option>
                        @foreach(['Ketua umum', 'wakil ketua umum', 'sekretaris', 'bendahara', 'ketua devisi kaderisasi', 'anggota devisi kaderisasi', 'ketua devisi hubungan masyarakat', 'anggota devisi hubungan masyarakat', 'ketua devisi keagamaan islam', 'anggota devisi keagamaan islam', 'ketua devisi danus', 'anggota devisi danus', 'ketua devisi kominfo', 'anggota devisi kominfo', 'ketua devisi minat dan bakat', 'anggota devisi minat dan bakat'] as $jab)
                            <option value="{{ $jab }}" {{ old('jabatan') == $jab ? 'selected' : '' }}>{{ ucwords($jab) }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('jabatan')" class="mt-2 text-red-500 text-xs" />
                </div>
            </div>

            <!-- Kanan: Data Alamat -->
            <div class="bg-white dark:bg-navy-700 rounded-xl shadow-sm p-6 sm:p-8 border border-transparent dark:border-white/5 transition-colors space-y-5">
                <div class="mb-2 pb-4 border-b border-gray-100 dark:border-white/10">
                    <h3 class="text-lg font-bold text-darkText dark:text-white">Data Alamat Domisili</h3>
                    <p class="text-sm text-gray-400">Lokasi tempat tinggal saat ini (Sumatera Utara).</p>
                </div>
                
                <div class="space-y-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Kabupaten / Kota <span class="text-red-500">*</span></label>
                        <select id="kabupaten_select" required class="w-full py-2.5 px-4 bg-lightBg dark:bg-navy-800 border border-transparent dark:border-white/10 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand text-gray-700 dark:text-white transition-all">
                            <option value="">-- Pilih Kabupaten/Kota --</option>
                        </select>
                        <input type="hidden" id="kabupaten_name" name="kabupaten">
                        <x-input-error :messages="$errors->get('kabupaten')" class="mt-2 text-red-500 text-xs" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Kecamatan <span class="text-red-500">*</span></label>
                        <select id="kecamatan_select" required disabled class="w-full py-2.5 px-4 bg-lightBg dark:bg-navy-800 border border-transparent dark:border-white/10 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand text-gray-700 dark:text-white transition-all opacity-50">
                            <option value="">-- Pilih Kecamatan --</option>
                        </select>
                        <input type="hidden" id="kecamatan_name" name="kecamatan">
                        <x-input-error :messages="$errors->get('kecamatan')" class="mt-2 text-red-500 text-xs" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Desa / Kelurahan <span class="text-red-500">*</span></label>
                        <select id="desa_select" required disabled class="w-full py-2.5 px-4 bg-lightBg dark:bg-navy-800 border border-transparent dark:border-white/10 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand text-gray-700 dark:text-white transition-all opacity-50">
                            <option value="">-- Pilih Desa/Kelurahan --</option>
                        </select>
                        <input type="hidden" id="desa_name" name="desa">
                        <x-input-error :messages="$errors->get('desa')" class="mt-2 text-red-500 text-xs" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Detail Alamat (Jalan, RT/RW, dll) <span class="text-red-500">*</span></label>
                        <textarea name="alamat" required rows="4" class="w-full py-2.5 px-4 bg-lightBg dark:bg-navy-800 border border-transparent dark:border-white/10 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand text-gray-700 dark:text-white transition-all placeholder-gray-400">{{ old('alamat') }}</textarea>
                        <x-input-error :messages="$errors->get('alamat')" class="mt-2 text-red-500 text-xs" />
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-6 flex items-center justify-end space-x-3">
            <a href="{{ route('admin.anggota.index') }}" class="px-5 py-2.5 text-sm font-medium text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 transition">Batal</a>
            <button type="submit" class="bg-brand text-white text-sm font-bold px-6 py-2.5 rounded-xl hover:bg-brandHover transition shadow-sm">
                Simpan Data
            </button>
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
                    option.textContent = reg.nama;
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
                            option.textContent = dist.nama;
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
                            option.textContent = vill.nama;
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
</x-app-layout>