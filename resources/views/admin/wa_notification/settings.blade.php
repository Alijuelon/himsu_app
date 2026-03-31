<x-app-layout>
    <x-slot name="header">
        Pengaturan Notifikasi WhatsApp
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('admin.wa.settings.update') }}" method="POST">
                        @csrf

                        <div class="mb-6 flex items-center">
                            <input id="is_active" type="checkbox" name="is_active"
                                class="w-4 h-4 text-brand bg-gray-100 border-gray-300 rounded focus:ring-brand dark:focus:ring-brand dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                                {{ $setting->is_active ?? false ? 'checked' : '' }}>
                            <label for="is_active"
                                class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Aktifkan Layanan
                                Notifikasi WA</label>
                        </div>

                        <label for="fonnte_token"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Token Fonnte
                            API</label>
                        <input type="text" id="fonnte_token" name="fonnte_token"
                            value="{{ $setting->fonnte_token ?? '' }}"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-brand focus:border-brand block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-brand dark:focus:border-brand"
                            placeholder="Masukkan token Fonnte Anda" required>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Dapatkan token di <a
                                href="https://md.fonnte.com/" target="_blank"
                                class="text-brand hover:underline">fonnte.com</a></p>
                </div>

                <div class="mb-6">
                    <label for="tgl_tagihan_otomatis"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tanggal Kirim Tagihan
                        Otomatis</label>
                    <div class="flex items-center">
                        <span
                            class="inline-flex items-center px-3 py-2.5 text-sm text-gray-900 bg-gray-200 border border-r-0 border-gray-300 rounded-l-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
                            Setiap Tanggal
                        </span>
                        <input type="number" id="tgl_tagihan_otomatis" name="tgl_tagihan_otomatis" min="1"
                            max="28" value="{{ $setting->tgl_tagihan_otomatis ?? '' }}"
                            class="rounded-none rounded-r-lg bg-gray-50 border text-gray-900 focus:ring-brand focus:border-brand block flex-1 min-w-0 w-full text-sm border-gray-300 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-brand dark:focus:border-brand"
                            placeholder="1 s.d 28 (Kosongkan bila manual)">
                    </div>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Kosongkan jika hanya ingin mengirim
                        broadcast tagihan secara manual via menu <b>Periode Kas</b>. Server berjalan pada jam 09:00 pagi
                        setiap harinya.</p>
                </div>

                <hr class="my-6 border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Template Pesan</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Gunakan tag berikut untuk dinamisasi
                    pesan: <code>{nama}</code>, <code>{bulan}</code>, <code>{tahun}</code>,
                    <code>{nominal}</code>, <code>{status}</code>.
                </p>

                <div class="mb-6">
                    <label for="template_pembayaran_diterima"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Template:
                        Pembayaran Diterima (Verifikasi Success)</label>
                    <textarea id="template_pembayaran_diterima" name="template_pembayaran_diterima" rows="4"
                        class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-brand focus:border-brand dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-brand dark:focus:border-brand"
                        placeholder="Halo {nama}, pembayaran kas untuk {bulan} {tahun} sebesar Rp{nominal} telah diverifikasi dan diterima. Terima kasih!">{{ $setting->template_pembayaran_diterima ?? 'Halo {nama}, pembayaran kas bulan {bulan} {tahun} telah DITERIMA.' }}</textarea>
                </div>

                <div class="mb-6">
                    <label for="template_pembayaran_ditolak"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Template:
                        Pembayaran Ditolak</label>
                    <textarea id="template_pembayaran_ditolak" name="template_pembayaran_ditolak" rows="4"
                        class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-brand focus:border-brand dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-brand dark:focus:border-brand"
                        placeholder="Halo {nama}, maaf pembayaran kas untuk {bulan} {tahun} ditolak. Silakan cek aplikasi.">{{ $setting->template_pembayaran_ditolak ?? 'Halo {nama}, pembayaran kas bulan {bulan} {tahun} DITOLAK. Silakan cek aplikasi untuk detailnya.' }}</textarea>
                </div>

                <div class="mb-6">
                    <label for="template_tagihan"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Template: Tagihan
                        (Broadcast/Manual)</label>
                    <textarea id="template_tagihan" name="template_tagihan" rows="4"
                        class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-brand focus:border-brand dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-brand dark:focus:border-brand"
                        placeholder="Halo {nama}, jangan lupa membayar tagihan kas {bulan} {tahun}.">{{ $setting->template_tagihan ?? 'Halo {nama}, mohon segera melakukan pembayaran kas untuk periode {bulan} {tahun}. Terima kasih.' }}</textarea>
                </div>

                <div class="flex items-center gap-4">
                    <button type="submit"
                        class="text-white bg-brand hover:bg-brand/90 focus:ring-4 focus:outline-none focus:ring-brand/50 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-brand dark:hover:bg-brand/90 dark:focus:ring-brand/50">Simpan
                        Pengaturan</button>
                </div>
                </form>
            </div>
        </div>
    </div>
    </div>
</x-app-layout>
