<x-app-layout>
    <x-slot name="header">
        Pengaturan Notifikasi WhatsApp
    </x-slot>

    <div class="max-w-4xl mx-auto mt-6">
        @if (session('success'))
            <div class="mb-6 bg-green-50 dark:bg-green-500/10 border-l-4 border-green-500 text-green-600 dark:text-green-400 p-4 rounded-r-xl shadow-sm flex items-center">
                <i class="fa-solid fa-circle-check text-xl mr-3"></i>
                <p class="font-medium">{{ session('success') }}</p>
            </div>
        @endif

        <div class="bg-white dark:bg-navy-700 rounded-2xl shadow-xl overflow-hidden border border-gray-100 dark:border-white/5 transition-colors">
            <div class="bg-gradient-to-r from-brand to-brandHover p-6 sm:p-8 text-white relative overflow-hidden">
                <div class="relative z-10">
                    <h2 class="text-2xl font-bold mb-2">Konfigurasi Gateway Fonnte</h2>
                    <p class="text-white/80 text-sm max-w-xl leading-relaxed">Atur token API dan sesuaikan template pesan untuk mengirim notifikasi tagihan dan kuitansi pembayaran secara otomatis ke WhatsApp anggota.</p>
                </div>
                <i class="fa-brands fa-whatsapp absolute -right-4 -bottom-6 text-9xl text-white/10 rotate-12 pointer-events-none"></i>
            </div>

            <form action="{{ route('admin.wa.settings.update') }}" method="POST" class="p-6 sm:p-8 space-y-8">
                @csrf
                
                <!-- Section API Token -->
                <div>
                    <h3 class="text-lg font-bold text-darkText dark:text-white mb-4 border-b border-gray-100 dark:border-white/10 pb-2 flex items-center">
                        <i class="fa-solid fa-key mr-2 text-brand"></i> Kredensial API
                    </h3>
                    
                    <div class="bg-gray-50 dark:bg-navy-800/50 p-5 rounded-xl border border-gray-100 dark:border-white/5 space-y-5">
                        <label class="flex items-center p-4 bg-white dark:bg-navy-700 border border-gray-200 dark:border-white/10 rounded-xl cursor-pointer hover:bg-gray-50 dark:hover:bg-navy-600 transition-colors">
                            <div class="relative flex items-center">
                                <input type="checkbox" name="is_active" id="is_active" class="sr-only peer" {{ $setting->is_active ?? false ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-brand/30 rounded-full peer dark:bg-gray-600 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-brand"></div>
                            </div>
                            <div class="ml-4 flex-1">
                                <span class="block text-sm font-bold text-gray-900 dark:text-white">Aktifkan Layanan Notifikasi WA</span>
                                <span class="block text-xs text-gray-500 dark:text-gray-400 mt-1">Jika dimatikan, sistem tidak akan mengirim pesan WA apapun ke anggota.</span>
                            </div>
                        </label>

                        <div>
                            <label for="fonnte_token" class="block mb-2 text-sm font-semibold text-gray-700 dark:text-gray-300">Token API Fonnte</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i class="fa-solid fa-lock text-gray-400"></i>
                                </div>
                                <input type="text" id="fonnte_token" name="fonnte_token" value="{{ $setting->fonnte_token ?? '' }}"
                                    class="w-full pl-11 pr-4 py-3 bg-white dark:bg-navy-700 border border-gray-200 dark:border-white/10 rounded-xl text-sm focus:ring-2 focus:ring-brand focus:border-brand text-gray-900 dark:text-white transition-all shadow-sm"
                                    placeholder="Masukkan token Fonnte Anda" required>
                            </div>
                            <p class="mt-2 text-xs text-gray-500 dark:text-gray-400 flex items-center">
                                <i class="fa-solid fa-circle-info mr-1"></i> Dapatkan token API Anda di <a href="https://md.fonnte.com/" target="_blank" class="text-brand hover:underline font-bold ml-1">md.fonnte.com</a>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Section Template Pesan -->
                <div>
                    <h3 class="text-lg font-bold text-darkText dark:text-white mb-4 border-b border-gray-100 dark:border-white/10 pb-2 flex items-center">
                        <i class="fa-solid fa-message mr-2 text-brand"></i> Template Pesan
                    </h3>
                    
                    <div class="mb-4 p-4 bg-blue-50 dark:bg-brand/10 rounded-xl text-sm text-brand border border-blue-100 dark:border-brand/20">
                        <p class="font-semibold mb-2"><i class="fa-solid fa-lightbulb mr-1"></i> Tag Dinamis yang tersedia:</p>
                        <div class="flex flex-wrap gap-2">
                            <span class="px-2.5 py-1 bg-white dark:bg-navy-800 rounded-lg shadow-sm border border-gray-100 dark:border-white/5 font-mono text-xs text-gray-600 dark:text-gray-300">{nama}</span>
                            <span class="px-2.5 py-1 bg-white dark:bg-navy-800 rounded-lg shadow-sm border border-gray-100 dark:border-white/5 font-mono text-xs text-gray-600 dark:text-gray-300">{bulan}</span>
                            <span class="px-2.5 py-1 bg-white dark:bg-navy-800 rounded-lg shadow-sm border border-gray-100 dark:border-white/5 font-mono text-xs text-gray-600 dark:text-gray-300">{tahun}</span>
                            <span class="px-2.5 py-1 bg-white dark:bg-navy-800 rounded-lg shadow-sm border border-gray-100 dark:border-white/5 font-mono text-xs text-gray-600 dark:text-gray-300">{nominal}</span>
                            <span class="px-2.5 py-1 bg-white dark:bg-navy-800 rounded-lg shadow-sm border border-gray-100 dark:border-white/5 font-mono text-xs text-gray-600 dark:text-gray-300">{status}</span>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <div class="bg-gray-50 dark:bg-navy-800/30 p-5 rounded-xl border border-gray-100 dark:border-white/5 relative group">
                            <label for="template_tagihan" class="block mb-2 text-sm font-semibold text-gray-700 dark:text-gray-300">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="w-2 h-2 rounded-full bg-yellow-500 shadow-[0_0_8px_rgba(234,179,8,0.6)]"></span> Tagihan (Belum Lunas)
                                </div>
                            </label>
                            <textarea id="template_tagihan" name="template_tagihan" rows="3"
                                class="w-full p-3.5 bg-white dark:bg-navy-700 border border-gray-200 dark:border-white/10 rounded-xl text-sm focus:ring-2 focus:ring-brand focus:border-brand text-gray-900 dark:text-white transition-all shadow-sm leading-relaxed"
                                placeholder="Tulis pesan tagihan...">{{ $setting->template_tagihan ?? 'Halo {nama}, mohon segera melakukan pembayaran kas untuk periode {bulan} {tahun}. Terima kasih.' }}</textarea>
                        </div>

                        <div class="bg-gray-50 dark:bg-navy-800/30 p-5 rounded-xl border border-gray-100 dark:border-white/5 relative group">
                            <label for="template_pembayaran_diterima" class="block mb-2 text-sm font-semibold text-gray-700 dark:text-gray-300">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="w-2 h-2 rounded-full bg-green-500 shadow-[0_0_8px_rgba(34,197,94,0.6)]"></span> Pembayaran Diterima (Lunas)
                                </div>
                            </label>
                            <textarea id="template_pembayaran_diterima" name="template_pembayaran_diterima" rows="3"
                                class="w-full p-3.5 bg-white dark:bg-navy-700 border border-gray-200 dark:border-white/10 rounded-xl text-sm focus:ring-2 focus:ring-brand focus:border-brand text-gray-900 dark:text-white transition-all shadow-sm leading-relaxed"
                                placeholder="Tulis pesan konfirmasi lunas...">{{ $setting->template_pembayaran_diterima ?? 'Halo {nama}, pembayaran kas bulan {bulan} {tahun} telah DITERIMA.' }}</textarea>
                        </div>

                        <div class="bg-gray-50 dark:bg-navy-800/30 p-5 rounded-xl border border-gray-100 dark:border-white/5 relative group">
                            <label for="template_pembayaran_ditolak" class="block mb-2 text-sm font-semibold text-gray-700 dark:text-gray-300">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="w-2 h-2 rounded-full bg-red-500 shadow-[0_0_8px_rgba(239,68,68,0.6)]"></span> Pembayaran Ditolak
                                </div>
                            </label>
                            <textarea id="template_pembayaran_ditolak" name="template_pembayaran_ditolak" rows="3"
                                class="w-full p-3.5 bg-white dark:bg-navy-700 border border-gray-200 dark:border-white/10 rounded-xl text-sm focus:ring-2 focus:ring-brand focus:border-brand text-gray-900 dark:text-white transition-all shadow-sm leading-relaxed"
                                placeholder="Tulis pesan penolakan...">{{ $setting->template_pembayaran_ditolak ?? 'Halo {nama}, pembayaran kas bulan {bulan} {tahun} DITOLAK. Silakan cek aplikasi untuk detailnya.' }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="pt-6 border-t border-gray-100 dark:border-white/10 flex justify-end">
                    <button type="submit" class="bg-brand text-white font-bold py-3 px-8 rounded-xl hover:bg-brandHover transition-all shadow-lg shadow-brand/30 flex items-center group">
                        <i class="fa-solid fa-save mr-2 group-hover:scale-110 transition-transform"></i> Simpan Pengaturan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
