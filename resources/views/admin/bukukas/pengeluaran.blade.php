<x-app-layout>
    <x-slot name="header">
        Catatan Pengeluaran Kas
    </x-slot>

    @if($errors->any())
        <div class="mb-6 p-4 bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/20 text-red-600 dark:text-red-400 rounded-xl flex items-start gap-3 relative">
            <i class="fa-solid fa-triangle-exclamation mt-1"></i>
            <div>
                <h4 class="font-bold text-sm">Gagal menyimpan data!</h4>
                <ul class="text-xs mt-1 list-disc pl-4 space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            <button type="button" class="absolute top-4 right-4 hover:text-red-800 dark:hover:text-red-300" onclick="this.parentElement.remove()">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
    @endif

    @php
        $namaBulanFilter = [1=>'Januari', 2=>'Februari', 3=>'Maret', 4=>'April', 5=>'Mei', 6=>'Juni', 7=>'Juli', 8=>'Agustus', 9=>'September', 10=>'Oktober', 11=>'November', 12=>'Desember'];
    @endphp

    <div class="mb-6 flex items-center p-5 bg-gradient-to-r from-red-500 to-red-400 rounded-xl shadow-sm text-white border border-transparent dark:border-white/5">
        <div class="p-4 bg-white/20 rounded-full backdrop-blur-sm">
            <i class="fa-solid fa-arrow-trend-down text-2xl w-6 text-center"></i>
        </div>
        <div class="ml-4">
            <h4 class="text-sm font-medium text-red-50">
                Total Pengeluaran
                @if(request('bulan') || request('tahun'))
                    — {{ request('bulan') ? $namaBulanFilter[(int)request('bulan')] : '' }} {{ request('tahun') ?? '' }}
                @else
                    Keseluruhan
                @endif
            </h4>
            <div class="text-3xl font-bold mt-1">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</div>
        </div>
    </div>

    <div class="bg-white dark:bg-navy-700 rounded-xl shadow-sm p-6 border border-transparent dark:border-white/5 transition-colors" x-data="{ modalTambah: false, search: '{{ request('search') }}' }">
        
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-6 gap-4">
            <h3 class="text-lg font-bold text-darkText dark:text-white">Daftar Pengeluaran</h3>
            
            <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto items-center">
                {{-- Filter Bulan & Tahun --}}
                <form action="{{ route('admin.bukukas.pengeluaran') }}" method="GET" class="flex gap-2 items-center">
                    @if(request('search'))
                        <input type="hidden" name="search" value="{{ request('search') }}">
                    @endif
                    <select name="bulan" onchange="this.form.submit()" class="py-2.5 px-3 bg-lightBg dark:bg-navy-800 border-none rounded-xl text-sm focus:ring-2 focus:ring-red-500 text-gray-700 dark:text-gray-300 transition-all cursor-pointer">
                        <option value="">Semua Bulan</option>
                        @foreach([1=>'Jan', 2=>'Feb', 3=>'Mar', 4=>'Apr', 5=>'Mei', 6=>'Jun', 7=>'Jul', 8=>'Ags', 9=>'Sep', 10=>'Okt', 11=>'Nov', 12=>'Des'] as $num => $nama)
                            <option value="{{ $num }}" {{ request('bulan') == $num ? 'selected' : '' }}>{{ $nama }}</option>
                        @endforeach
                    </select>
                    <select name="tahun" onchange="this.form.submit()" class="py-2.5 px-3 bg-lightBg dark:bg-navy-800 border-none rounded-xl text-sm focus:ring-2 focus:ring-red-500 text-gray-700 dark:text-gray-300 transition-all cursor-pointer">
                        <option value="">Semua Tahun</option>
                        @for($y = date('Y'); $y >= date('Y') - 5; $y--)
                            <option value="{{ $y }}" {{ request('tahun') == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                    @if(request('bulan') || request('tahun'))
                        <a href="{{ route('admin.bukukas.pengeluaran', request('search') ? ['search' => request('search')] : []) }}" class="p-2.5 bg-red-50 dark:bg-red-500/10 text-red-500 rounded-xl hover:bg-red-100 dark:hover:bg-red-500/20 transition" title="Reset Filter">
                            <i class="fa-solid fa-filter-circle-xmark"></i>
                        </a>
                    @endif
                </form>

                <form action="{{ route('admin.bukukas.pengeluaran') }}" method="GET" class="relative w-full sm:w-64">
                    @if(request('bulan'))
                        <input type="hidden" name="bulan" value="{{ request('bulan') }}">
                    @endif
                    @if(request('tahun'))
                        <input type="hidden" name="tahun" value="{{ request('tahun') }}">
                    @endif
                    <input type="text" name="search" x-model="search" placeholder="Cari kategori/keterangan..." 
                           class="w-full pl-10 pr-10 py-2.5 bg-lightBg dark:bg-navy-800 border-none rounded-xl text-sm focus:ring-2 focus:ring-red-500 text-gray-700 dark:text-gray-300 transition-all placeholder-gray-400">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fa-solid fa-magnifying-glass text-gray-400"></i>
                    </div>
                    <button type="button" x-show="search.length > 0" style="display: none;" @click="search = ''; $el.closest('form').submit()" 
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-red-500 transition">
                        <i class="fa-solid fa-circle-xmark"></i>
                    </button>
                </form>

                <button @click="modalTambah = true" type="button" class="bg-red-500 text-white text-sm font-semibold px-4 py-2.5 rounded-xl hover:bg-red-600 transition shadow-sm flex items-center justify-center whitespace-nowrap">
                    <i class="fa-solid fa-minus mr-2"></i> Tambah Pengeluaran
                </button>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-gray-400 dark:text-gray-400 text-xs tracking-wide uppercase border-b border-gray-100 dark:border-white/10">
                        <th class="py-3 px-4 font-medium">Tanggal</th>
                        <th class="py-3 px-4 font-medium">Kategori</th>
                        <th class="py-3 px-4 font-medium">Keterangan</th>
                        <th class="py-3 px-4 font-medium text-center">Bukti</th>
                        <th class="py-3 px-4 font-medium text-right">Nominal</th>
                        <th class="py-3 px-4 font-medium text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-600 dark:text-gray-300">
                    @forelse ($pengeluaran as $item)
                        <tr class="border-b border-gray-50 dark:border-white/5 hover:bg-gray-50/50 dark:hover:bg-white/5 transition">
                            <td class="py-4 px-4 font-bold text-darkText dark:text-white whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d M Y') }}
                            </td>
                            <td class="py-4 px-4">
                                <span class="px-2.5 py-1 bg-red-50 text-red-600 dark:bg-red-500/10 dark:text-red-400 rounded-lg text-xs font-bold">{{ $item->kategori }}</span>
                            </td>
                            <td class="py-4 px-4">
                                <p class="font-medium truncate max-w-[200px]" title="{{ $item->keterangan }}">{{ $item->keterangan ?? '-' }}</p>
                                <p class="text-[10px] text-gray-400 mt-0.5">Oleh: {{ $item->pencatat->nama_lengkap ?? 'Sistem' }}</p>
                            </td>
                            <td class="py-4 px-4 text-center">
                                @if($item->bukti_nota)
                                    <a href="{{ route('bukukas.bukti.show', ['path' => 'bukti_kas/' . $item->bukti_nota, 't' => time()]) }}" target="_blank" class="inline-flex items-center justify-center p-2 bg-blue-50 text-blue-600 dark:bg-blue-500/10 dark:text-blue-400 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-500/20 transition" title="Lihat Bukti">
                                        <i class="fa-solid fa-file-invoice"></i>
                                    </a>
                                @else
                                    <span class="text-xs text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="py-4 px-4 font-bold text-darkText dark:text-white text-right whitespace-nowrap">
                                Rp {{ number_format($item->nominal, 0, ',', '.') }}
                            </td>
                            <td class="py-4 px-4 text-center">
                                <div class="inline-flex gap-1">
                                    <!-- Tombol Edit -->
                                    <div x-data="{ openModalEdit: false }" class="inline">
                                        <button @click="openModalEdit = true" type="button" class="p-2 bg-amber-50 dark:bg-amber-500/10 text-amber-500 rounded-lg hover:bg-amber-100 transition" title="Edit Data">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </button>
                                        <template x-teleport="body">
                                            <div x-show="openModalEdit" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center px-4">
                                                <div x-show="openModalEdit" @click="openModalEdit = false" x-transition.opacity class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm"></div>
                                                <div x-show="openModalEdit" x-transition.scale.origin.bottom class="relative bg-white dark:bg-navy-800 rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden text-left border border-gray-100 dark:border-white/10">
                                                    <div class="px-6 py-4 border-b border-gray-100 dark:border-white/10 flex items-center justify-between bg-amber-50 dark:bg-amber-500/10">
                                                        <h3 class="text-lg font-bold text-amber-600 dark:text-amber-400">
                                                            <i class="fa-solid fa-pen-to-square mr-2"></i> Edit Pengeluaran
                                                        </h3>
                                                        <button @click="openModalEdit = false" type="button" class="text-gray-400 hover:text-gray-600 dark:hover:text-white"><i class="fa-solid fa-xmark"></i></button>
                                                    </div>
                                                    <form action="{{ route('admin.bukukas.update', $item->id) }}" method="POST" enctype="multipart/form-data" class="p-5 space-y-3">
                                                        @csrf @method('PUT')
                                                        <div>
                                                            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal Keluar <span class="text-red-500">*</span></label>
                                                            <input type="date" name="tanggal" value="{{ $item->tanggal }}" required class="w-full py-1.5 px-3 bg-lightBg dark:bg-navy-900 border border-transparent dark:border-white/10 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-500 text-gray-700 dark:text-white transition-all text-sm">
                                                        </div>
                                                        <div>
                                                            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Kategori <span class="text-red-500">*</span></label>
                                                            <select name="kategori" required class="w-full py-1.5 px-3 bg-lightBg dark:bg-navy-900 border border-transparent dark:border-white/10 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-500 text-gray-700 dark:text-white transition-all text-sm">
                                                                <option value="Pembelian ATK" {{ $item->kategori == 'Pembelian ATK' ? 'selected' : '' }}>Pembelian ATK & Kesekretariatan</option>
                                                                <option value="Konsumsi" {{ $item->kategori == 'Konsumsi' ? 'selected' : '' }}>Konsumsi Rapat / Kegiatan</option>
                                                                <option value="Bantuan Sosial" {{ $item->kategori == 'Bantuan Sosial' ? 'selected' : '' }}>Bantuan Sosial</option>
                                                                <option value="Lain-lain" {{ $item->kategori == 'Lain-lain' ? 'selected' : '' }}>Lain-lain</option>
                                                            </select>
                                                        </div>
                                                        <div>
                                                            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Nominal (Rp) <span class="text-red-500">*</span></label>
                                                            <input type="number" name="nominal" value="{{ (int)$item->nominal }}" required min="1" class="w-full py-1.5 px-3 bg-lightBg dark:bg-navy-900 border border-transparent dark:border-white/10 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-500 text-gray-700 dark:text-white transition-all text-sm">
                                                        </div>
                                                        <div>
                                                            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Keterangan / Rincian</label>
                                                            <textarea name="keterangan" rows="1" class="w-full py-1.5 px-3 bg-lightBg dark:bg-navy-900 border border-transparent dark:border-white/10 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-500 text-gray-700 dark:text-white text-sm">{{ $item->keterangan }}</textarea>
                                                        </div>
                                                        <div x-data="{
                                                            filePreview: null,
                                                            isImage: false,
                                                            isPdf: false,
                                                            handleFile(event) {
                                                                const file = event.target.files[0];
                                                                if (!file) return;
                                                                this.filePreview = URL.createObjectURL(file);
                                                                this.isImage = file.type.startsWith('image/');
                                                                this.isPdf = file.type === 'application/pdf';
                                                            }
                                                        }">
                                                            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Bukti Transaksi (Biarkan kosong jika tak diubah)</label>
                                                            <div class="flex items-start gap-3">
                                                                <div class="flex-1">
                                                                    <input type="file" name="bukti_nota" accept=".pdf,.jpg,.jpeg,.png" @change="handleFile" class="w-full py-1 px-2 bg-lightBg dark:bg-navy-900 border border-transparent dark:border-white/10 rounded-xl text-xs cursor-pointer file:mr-2 file:py-1 file:px-2 file:rounded-lg file:border-0 file:text-xs file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100">
                                                                    <p class="text-[10px] text-gray-400 mt-1">Format: PDF, JPG, PNG. Max: 6MB.</p>
                                                                </div>
                                                                <div x-show="filePreview || '{{ $item->bukti_nota }}'" class="w-12 h-12 rounded-lg overflow-hidden border border-gray-200 dark:border-white/10 bg-gray-50 flex items-center justify-center shrink-0">
                                                                    <template x-if="filePreview && isImage">
                                                                        <img :src="filePreview" class="w-full h-full object-cover">
                                                                    </template>
                                                                    <template x-if="filePreview && isPdf">
                                                                        <i class="fa-solid fa-file-pdf text-red-500 text-xl"></i>
                                                                    </template>
                                                                    <template x-if="!filePreview && '{{ $item->bukti_nota }}'">
                                                                        @if(Str::endsWith($item->bukti_nota, ['.pdf', '.PDF']))
                                                                            <i class="fa-solid fa-file-pdf text-red-500 text-xl"></i>
                                                                        @else
                                                                            <img src="{{ route('bukukas.bukti.show', ['path' => 'bukti_kas/' . $item->bukti_nota, 't' => time()]) }}" class="w-full h-full object-cover">
                                                                        @endif
                                                                    </template>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="pt-2 flex justify-end space-x-2">
                                                            <button type="button" @click="openModalEdit = false" class="px-4 py-1.5 bg-gray-100 hover:bg-gray-200 dark:bg-navy-700 dark:hover:bg-navy-600 text-gray-700 dark:text-gray-300 font-medium rounded-xl text-sm transition-colors">Batal</button>
                                                            <button type="submit" class="px-4 py-1.5 text-white font-bold rounded-xl bg-amber-500 hover:bg-amber-600 text-sm shadow-sm transition-colors">Simpan</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </template>
                                    </div>

                                    <!-- Tombol Hapus -->
                                    <div x-data="{ openModalHapus: false }" class="inline">
                                        <button @click="openModalHapus = true" type="button" class="p-2 bg-red-50 dark:bg-red-500/10 text-red-500 rounded-lg hover:bg-red-100 transition" title="Hapus Data">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                        <template x-teleport="body">
                                            <div x-show="openModalHapus" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center px-4">
                                                <div x-show="openModalHapus" @click="openModalHapus = false" class="fixed inset-0 bg-gray-900/40 backdrop-blur-sm transition-opacity"></div>
                                                <div x-show="openModalHapus" class="relative bg-white dark:bg-navy-800 rounded-2xl shadow-2xl p-6 w-full max-w-sm overflow-hidden text-center border border-gray-100 dark:border-white/10">
                                                    <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-50 dark:bg-red-500/10 mb-5">
                                                        <i class="fa-solid fa-triangle-exclamation text-3xl text-red-500"></i>
                                                    </div>
                                                    <h3 class="text-xl font-bold text-darkText dark:text-white mb-2">Hapus Catatan?</h3>
                                                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">Data pengeluaran ini akan dihapus dari sistem.</p>
                                                    <div class="flex justify-center gap-3">
                                                        <button @click="openModalHapus = false" type="button" class="w-full px-5 py-2.5 bg-gray-100 hover:bg-gray-200 dark:bg-navy-700 dark:hover:bg-navy-600 text-gray-700 dark:text-gray-300 font-medium rounded-xl transition-colors">Batal</button>
                                                        <form action="{{ route('admin.bukukas.destroy', $item->id) }}" method="POST" class="w-full">
                                                            @csrf @method('DELETE')
                                                            <button type="submit" class="w-full px-5 py-2.5 bg-red-500 hover:bg-red-600 text-white font-bold rounded-xl transition-colors shadow-sm">Ya, Hapus</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-10 text-center text-gray-400">Belum ada catatan pengeluaran kas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($pengeluaran->hasPages())
            <div class="mt-6 pt-4 border-t border-gray-100 dark:border-white/10">
                {{ $pengeluaran->links() }}
            </div>
        @endif

        <template x-teleport="body">
            <div x-show="modalTambah" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center px-4">
                <div x-show="modalTambah" @click="modalTambah = false" x-transition.opacity class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm"></div>
                
                <div x-show="modalTambah" x-transition.scale.origin.bottom class="relative bg-white dark:bg-navy-800 rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden text-left border border-gray-100 dark:border-white/10">
                    <div class="px-6 py-4 border-b border-gray-100 dark:border-white/10 flex items-center justify-between bg-red-50 dark:bg-red-500/10">
                        <h3 class="text-lg font-bold text-red-600 dark:text-red-400">
                            <i class="fa-solid fa-minus-circle mr-2"></i> Tambah Pengeluaran
                        </h3>
                        <button @click="modalTambah = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-white"><i class="fa-solid fa-xmark"></i></button>
                    </div>

                    <form action="{{ route('admin.bukukas.store') }}" method="POST" enctype="multipart/form-data" class="p-5 space-y-3">
                        @csrf
                        <input type="hidden" name="jenis_transaksi" value="pengeluaran">

                        <div>
                            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal Keluar <span class="text-red-500">*</span></label>
                            <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" required class="w-full py-1.5 px-3 bg-lightBg dark:bg-navy-900 border border-transparent dark:border-white/10 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500 text-gray-700 dark:text-white transition-all text-sm">
                        </div>
                        
                        <div>
                            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Kategori <span class="text-red-500">*</span></label>
                            <select name="kategori" required class="w-full py-1.5 px-3 bg-lightBg dark:bg-navy-900 border border-transparent dark:border-white/10 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500 text-gray-700 dark:text-white transition-all text-sm">
                                <option value="Pembelian ATK">Pembelian ATK & Kesekretariatan</option>
                                <option value="Konsumsi">Konsumsi Rapat / Kegiatan</option>
                                <option value="Bantuan Sosial">Bantuan Sosial</option>
                                <option value="Lain-lain">Lain-lain</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Nominal (Rp) <span class="text-red-500">*</span></label>
                            <input type="number" name="nominal" required min="1" placeholder="Contoh: 150000" class="w-full py-1.5 px-3 bg-lightBg dark:bg-navy-900 border border-transparent dark:border-white/10 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500 text-gray-700 dark:text-white transition-all text-sm">
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Keterangan / Rincian</label>
                            <textarea name="keterangan" rows="1" placeholder="Catatan pembelian..." class="w-full py-1.5 px-3 bg-lightBg dark:bg-navy-900 border border-transparent dark:border-white/10 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500 text-gray-700 dark:text-white text-sm"></textarea>
                        </div>

                        <div x-data="{
                            filePreview: null,
                            isImage: false,
                            isPdf: false,
                            handleFile(event) {
                                const file = event.target.files[0];
                                if (!file) {
                                    this.filePreview = null;
                                    return;
                                }
                                this.filePreview = URL.createObjectURL(file);
                                this.isImage = file.type.startsWith('image/');
                                this.isPdf = file.type === 'application/pdf';
                            }
                        }">
                            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Bukti Transaksi (Opsional)</label>
                            <div class="flex items-start gap-3">
                                <div class="flex-1">
                                    <input type="file" name="bukti_nota" accept=".pdf,.jpg,.jpeg,.png" @change="handleFile" class="w-full py-1 px-2 bg-lightBg dark:bg-navy-900 border border-transparent dark:border-white/10 rounded-xl text-xs cursor-pointer file:mr-2 file:py-1 file:px-2 file:rounded-lg file:border-0 file:text-xs file:bg-red-50 file:text-red-700 hover:file:bg-red-100">
                                    <p class="text-[10px] text-gray-400 mt-1">Format: PDF, JPG, PNG. Max: 6MB.</p>
                                </div>
                                <div x-show="filePreview" style="display: none;" class="w-12 h-12 rounded-lg overflow-hidden border border-gray-200 dark:border-white/10 bg-gray-50 flex items-center justify-center shrink-0">
                                    <template x-if="isImage">
                                        <img :src="filePreview" class="w-full h-full object-cover">
                                    </template>
                                    <template x-if="isPdf">
                                        <i class="fa-solid fa-file-pdf text-red-500 text-xl"></i>
                                    </template>
                                </div>
                            </div>
                        </div>

                        <div class="pt-2 flex justify-end space-x-2">
                            <button type="button" @click="modalTambah = false" class="px-4 py-1.5 bg-gray-100 hover:bg-gray-200 dark:bg-navy-700 dark:hover:bg-navy-600 text-gray-700 dark:text-gray-300 font-medium rounded-xl text-sm transition-colors">Batal</button>
                            <button type="submit" class="px-4 py-1.5 text-white font-bold rounded-xl shadow-sm text-sm transition-colors bg-red-500 hover:bg-red-600">Simpan Pengeluaran</button>
                        </div>
                    </form>
                </div>
            </div>
        </template>

    </div>
</x-app-layout>