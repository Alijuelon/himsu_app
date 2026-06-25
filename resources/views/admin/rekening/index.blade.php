<x-app-layout>
    <x-slot name="header">
        Pengaturan Rekening Bank
    </x-slot>

    <div class="max-w-7xl mx-auto space-y-6">
        
        <div class="bg-white dark:bg-navy-800 rounded-2xl shadow-sm border border-gray-100 dark:border-white/5 overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100 dark:border-white/5 bg-gray-50/50 dark:bg-navy-900/50 flex justify-between items-center">
                <div>
                    <h3 class="font-bold text-lg text-darkText dark:text-white">Daftar Rekening Pembayaran</h3>
                    <p class="text-sm text-gray-500">Daftar ini akan ditampilkan di halaman form pembayaran anggota.</p>
                </div>
                <button onclick="document.getElementById('modal-tambah').classList.remove('hidden')" class="bg-brand text-white text-sm font-bold px-4 py-2 rounded-lg hover:bg-brandHover transition">
                    <i class="fa-solid fa-plus mr-2"></i> Tambah Rekening
                </button>
            </div>
            
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($rekening as $rek)
                        <div class="bg-white dark:bg-navy-700 rounded-xl p-5 border border-gray-200 dark:border-white/10 hover:border-brand dark:hover:border-brand transition group relative">
                            <div class="absolute top-4 right-4 opacity-0 group-hover:opacity-100 transition flex space-x-2">
                                <button onclick="editRekening({{ $rek->id }}, '{{ $rek->nama_bank }}', '{{ $rek->no_rekening }}', '{{ $rek->atas_nama }}')" class="text-blue-500 hover:text-blue-700 bg-blue-50 dark:bg-blue-500/20 p-1.5 rounded">
                                    <i class="fa-solid fa-pen"></i>
                                </button>
                                <form action="{{ route('admin.rekening.destroy', $rek->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus rekening ini?')">
                                    @csrf @method('DELETE')
                                    <button class="text-red-500 hover:text-red-700 bg-red-50 dark:bg-red-500/20 p-1.5 rounded">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                            
                            <div class="w-12 h-12 bg-blue-50 dark:bg-blue-500/20 rounded-full flex items-center justify-center text-blue-500 mb-4">
                                <i class="fa-solid fa-building-columns text-xl"></i>
                            </div>
                            <h4 class="font-bold text-lg text-darkText dark:text-white mb-1">{{ $rek->nama_bank }}</h4>
                            <p class="text-2xl font-black text-brand tracking-widest mb-2">{{ $rek->no_rekening }}</p>
                            @if($rek->atas_nama)
                                <p class="text-sm text-gray-500 font-medium">a.n {{ $rek->atas_nama }}</p>
                            @endif
                        </div>
                    @empty
                        <div class="col-span-full py-10 text-center text-gray-400">
                            <i class="fa-solid fa-building-columns text-4xl mb-3 opacity-50"></i>
                            <p>Belum ada rekening pembayaran yang diatur.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>

    <!-- Modal Tambah -->
    <div id="modal-tambah" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-black/50 backdrop-blur-sm">
        <div class="bg-white dark:bg-navy-800 rounded-2xl shadow-xl max-w-md w-full mx-4 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-white/5 flex justify-between items-center">
                <h3 class="font-bold text-lg text-darkText dark:text-white">Tambah Rekening</h3>
                <button onclick="document.getElementById('modal-tambah').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>
            <form action="{{ route('admin.rekening.store') }}" method="POST" class="p-6">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Bank / E-Wallet</label>
                        <input type="text" name="nama_bank" required placeholder="Contoh: Bank Riau Kepri / Dana" class="w-full py-2 px-3 rounded-lg border border-gray-200 dark:border-white/10 dark:bg-navy-900 focus:ring-brand focus:border-brand">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nomor Rekening</label>
                        <input type="text" name="no_rekening" required placeholder="Contoh: 123-456-7890" class="w-full py-2 px-3 rounded-lg border border-gray-200 dark:border-white/10 dark:bg-navy-900 focus:ring-brand focus:border-brand">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Atas Nama (Opsional)</label>
                        <input type="text" name="atas_nama" placeholder="Contoh: HIMSU Bengkalis" class="w-full py-2 px-3 rounded-lg border border-gray-200 dark:border-white/10 dark:bg-navy-900 focus:ring-brand focus:border-brand">
                    </div>
                </div>
                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" onclick="document.getElementById('modal-tambah').classList.add('hidden')" class="px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-100 rounded-lg">Batal</button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-brand hover:bg-brandHover rounded-lg">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit -->
    <div id="modal-edit" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-black/50 backdrop-blur-sm">
        <div class="bg-white dark:bg-navy-800 rounded-2xl shadow-xl max-w-md w-full mx-4 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-white/5 flex justify-between items-center">
                <h3 class="font-bold text-lg text-darkText dark:text-white">Edit Rekening</h3>
                <button onclick="document.getElementById('modal-edit').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>
            <form id="form-edit" method="POST" class="p-6">
                @csrf @method('PUT')
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Bank / E-Wallet</label>
                        <input type="text" name="nama_bank" id="edit_nama_bank" required class="w-full py-2 px-3 rounded-lg border border-gray-200 dark:border-white/10 dark:bg-navy-900 focus:ring-brand focus:border-brand">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nomor Rekening</label>
                        <input type="text" name="no_rekening" id="edit_no_rekening" required class="w-full py-2 px-3 rounded-lg border border-gray-200 dark:border-white/10 dark:bg-navy-900 focus:ring-brand focus:border-brand">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Atas Nama (Opsional)</label>
                        <input type="text" name="atas_nama" id="edit_atas_nama" class="w-full py-2 px-3 rounded-lg border border-gray-200 dark:border-white/10 dark:bg-navy-900 focus:ring-brand focus:border-brand">
                    </div>
                </div>
                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" onclick="document.getElementById('modal-edit').classList.add('hidden')" class="px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-100 rounded-lg">Batal</button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-500 hover:bg-blue-600 rounded-lg">Update</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function editRekening(id, nama, no, atas) {
            document.getElementById('edit_nama_bank').value = nama;
            document.getElementById('edit_no_rekening').value = no;
            document.getElementById('edit_atas_nama').value = atas;
            document.getElementById('form-edit').action = `/admin/rekening/${id}/update`;
            document.getElementById('modal-edit').classList.remove('hidden');
        }
    </script>
</x-app-layout>
