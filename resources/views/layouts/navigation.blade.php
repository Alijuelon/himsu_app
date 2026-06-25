<div x-show="sidebarOpen" 
     x-transition.opacity 
     @click="sidebarOpen = false" 
     class="fixed inset-0 z-20 bg-gray-900/50 backdrop-blur-sm lg:hidden">
</div>

<aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" 
       class="fixed inset-y-0 left-0 z-30 w-64 bg-white dark:bg-navy-700 transform transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-auto flex flex-col shadow-xl lg:shadow-none border-r border-gray-100 dark:border-white/5">
    
    <div class="flex items-center justify-center h-24 border-b border-gray-100 dark:border-white/5">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-10 h-10 object-contain drop-shadow-md">
            <h1 class="text-xl font-bold text-darkText dark:text-white tracking-wide transition-colors">
                HIMSU<span class="text-brand"> KAS</span>
            </h1>
        </a>
    </div>

    <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
    
    @if (Auth::user()->role === 'admin')
        <p class="px-4 text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-3">Menu Admin</p>
        
        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="flex items-center px-4 py-3 {{ request()->routeIs('dashboard') ? 'bg-lightBg dark:bg-white/5 text-brand dark:text-brand font-semibold' : 'text-gray-500 dark:text-gray-400 font-medium hover:text-brand dark:hover:text-white hover:bg-gray-50 dark:hover:bg-white/5' }} rounded-xl transition-colors relative w-full border-none">
            @if(request()->routeIs('dashboard'))
                <div class="absolute right-0 top-1/2 -translate-y-1/2 h-8 w-1 bg-brand rounded-l-full"></div>
            @endif
            <i class="fa-solid fa-house w-5 text-center text-lg"></i>
            <span class="mx-3">Dashboard Admin</span>
        </x-nav-link>

        <!-- Dropdown Kelola Data -->
        @php $isMasterActive = request()->routeIs('admin.anggota.*', 'admin.verifikasi.*', 'admin.periode.*'); @endphp
        <div x-data="{ open: {{ $isMasterActive ? 'true' : 'false' }} }" class="space-y-1">
            <button @click="open = !open" class="flex items-center justify-between px-4 py-3 w-full rounded-xl transition-colors font-medium border-none {{ $isMasterActive ? 'bg-lightBg dark:bg-white/5 text-brand dark:text-brand font-semibold' : 'text-gray-500 dark:text-gray-400 hover:text-brand dark:hover:text-white hover:bg-gray-50 dark:hover:bg-white/5' }}">
                <div class="flex items-center relative">
                    @if($isMasterActive)
                        <div class="absolute -left-4 top-1/2 -translate-y-1/2 h-8 w-1 bg-brand rounded-r-full"></div>
                    @endif
                    <i class="fa-solid fa-database w-5 text-center text-lg"></i>
                    <span class="mx-3">Kelola Data</span>
                </div>
                <i class="fa-solid fa-chevron-down text-xs transition-transform duration-300" :class="open ? 'rotate-180' : ''"></i>
            </button>
            <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-2" style="display: none;" class="pl-11 pr-4 py-1 space-y-1 relative">
                <div class="absolute left-[1.35rem] top-0 bottom-2 w-px bg-gray-200 dark:bg-white/10"></div>
                
                <a href="{{ route('admin.anggota.index') }}" class="relative flex items-center px-3 py-2 text-sm rounded-lg transition-colors {{ request()->routeIs('admin.anggota.*') ? 'text-brand dark:text-brand font-semibold bg-gray-50 dark:bg-white/5' : 'text-gray-500 dark:text-gray-400 hover:text-brand dark:hover:text-white hover:bg-gray-50 dark:hover:bg-white/5' }}">
                    @if(request()->routeIs('admin.anggota.*')) <div class="absolute -left-3 top-1/2 -translate-y-1/2 w-1.5 h-1.5 rounded-full bg-brand"></div> @endif
                    <i class="fa-solid fa-users w-5 text-center mr-2"></i>
                    Data Anggota
                </a>
                <a href="{{ route('admin.verifikasi.index') }}" class="relative flex items-center px-3 py-2 text-sm rounded-lg transition-colors {{ request()->routeIs('admin.verifikasi.*') ? 'text-brand dark:text-brand font-semibold bg-gray-50 dark:bg-white/5' : 'text-gray-500 dark:text-gray-400 hover:text-brand dark:hover:text-white hover:bg-gray-50 dark:hover:bg-white/5' }}">
                    @if(request()->routeIs('admin.verifikasi.*')) <div class="absolute -left-3 top-1/2 -translate-y-1/2 w-1.5 h-1.5 rounded-full bg-brand"></div> @endif
                    <i class="fa-solid fa-user-check w-5 text-center mr-2"></i>
                    Verifikasi Akun
                </a>
                <a href="{{ route('admin.periode.index') }}" class="relative flex items-center px-3 py-2 text-sm rounded-lg transition-colors {{ request()->routeIs('admin.periode.*') ? 'text-brand dark:text-brand font-semibold bg-gray-50 dark:bg-white/5' : 'text-gray-500 dark:text-gray-400 hover:text-brand dark:hover:text-white hover:bg-gray-50 dark:hover:bg-white/5' }}">
                    @if(request()->routeIs('admin.periode.*')) <div class="absolute -left-3 top-1/2 -translate-y-1/2 w-1.5 h-1.5 rounded-full bg-brand"></div> @endif
                    <i class="fa-solid fa-calendar-days w-5 text-center mr-2"></i>
                    Pembayaran Kas
                </a>
            </div>
        </div>

        <!-- Dropdown Transaksi Kas -->
        @php $isTransaksiActive = request()->routeIs('admin.pembayaran.*', 'admin.bukukas.pemasukan', 'admin.bukukas.pengeluaran'); @endphp
        <div x-data="{ open: {{ $isTransaksiActive ? 'true' : 'false' }} }" class="space-y-1">
            <button @click="open = !open" class="flex items-center justify-between px-4 py-3 w-full rounded-xl transition-colors font-medium border-none {{ $isTransaksiActive ? 'bg-lightBg dark:bg-white/5 text-brand dark:text-brand font-semibold' : 'text-gray-500 dark:text-gray-400 hover:text-brand dark:hover:text-white hover:bg-gray-50 dark:hover:bg-white/5' }}">
                <div class="flex items-center relative">
                    @if($isTransaksiActive)
                        <div class="absolute -left-4 top-1/2 -translate-y-1/2 h-8 w-1 bg-brand rounded-r-full"></div>
                    @endif
                    <i class="fa-solid fa-wallet w-5 text-center text-lg"></i>
                    <span class="mx-3">Transaksi Kas</span>
                </div>
                <i class="fa-solid fa-chevron-down text-xs transition-transform duration-300" :class="open ? 'rotate-180' : ''"></i>
            </button>
            <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-2" style="display: none;" class="pl-11 pr-4 py-1 space-y-1 relative">
                <div class="absolute left-[1.35rem] top-0 bottom-2 w-px bg-gray-200 dark:bg-white/10"></div>
                
                <a href="{{ route('admin.pembayaran.index') }}" class="relative flex items-center px-3 py-2 text-sm rounded-lg transition-colors {{ request()->routeIs('admin.pembayaran.*') ? 'text-brand dark:text-brand font-semibold bg-gray-50 dark:bg-white/5' : 'text-gray-500 dark:text-gray-400 hover:text-brand dark:hover:text-white hover:bg-gray-50 dark:hover:bg-white/5' }}">
                    @if(request()->routeIs('admin.pembayaran.*')) <div class="absolute -left-3 top-1/2 -translate-y-1/2 w-1.5 h-1.5 rounded-full bg-brand"></div> @endif
                    <i class="fa-solid fa-file-invoice-dollar w-5 text-center mr-2"></i>
                    Pembayaran Kas
                </a>
                <a href="{{ route('admin.bukukas.pemasukan') }}" class="relative flex items-center px-3 py-2 text-sm rounded-lg transition-colors {{ request()->routeIs('admin.bukukas.pemasukan') ? 'text-brand dark:text-brand font-semibold bg-gray-50 dark:bg-white/5' : 'text-gray-500 dark:text-gray-400 hover:text-brand dark:hover:text-white hover:bg-gray-50 dark:hover:bg-white/5' }}">
                    @if(request()->routeIs('admin.bukukas.pemasukan')) <div class="absolute -left-3 top-1/2 -translate-y-1/2 w-1.5 h-1.5 rounded-full bg-brand"></div> @endif
                    <i class="fa-solid fa-arrow-trend-up w-5 text-center mr-2"></i>
                    Pemasukan Kas
                </a>
                <a href="{{ route('admin.bukukas.pengeluaran') }}" class="relative flex items-center px-3 py-2 text-sm rounded-lg transition-colors {{ request()->routeIs('admin.bukukas.pengeluaran') ? 'text-brand dark:text-brand font-semibold bg-gray-50 dark:bg-white/5' : 'text-gray-500 dark:text-gray-400 hover:text-brand dark:hover:text-white hover:bg-gray-50 dark:hover:bg-white/5' }}">
                    @if(request()->routeIs('admin.bukukas.pengeluaran')) <div class="absolute -left-3 top-1/2 -translate-y-1/2 w-1.5 h-1.5 rounded-full bg-brand"></div> @endif
                    <i class="fa-solid fa-arrow-trend-down w-5 text-center mr-2"></i>
                    Pengeluaran Kas
                </a>
            </div>
        </div>

        <!-- Dropdown Laporan -->
        @php $isLaporanActive = request()->routeIs('admin.laporan.*'); @endphp
        <div x-data="{ open: {{ $isLaporanActive ? 'true' : 'false' }} }" class="space-y-1">
            <button @click="open = !open" class="flex items-center justify-between px-4 py-3 w-full rounded-xl transition-colors font-medium border-none {{ $isLaporanActive ? 'bg-lightBg dark:bg-white/5 text-brand dark:text-brand font-semibold' : 'text-gray-500 dark:text-gray-400 hover:text-brand dark:hover:text-white hover:bg-gray-50 dark:hover:bg-white/5' }}">
                <div class="flex items-center relative">
                    @if($isLaporanActive)
                        <div class="absolute -left-4 top-1/2 -translate-y-1/2 h-8 w-1 bg-brand rounded-r-full"></div>
                    @endif
                    <i class="fa-solid fa-chart-pie w-5 text-center text-lg"></i>
                    <span class="mx-3">Laporan</span>
                </div>
                <i class="fa-solid fa-chevron-down text-xs transition-transform duration-300" :class="open ? 'rotate-180' : ''"></i>
            </button>
            <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-2" style="display: none;" class="pl-11 pr-4 py-1 space-y-1 relative">
                <div class="absolute left-[1.35rem] top-0 bottom-2 w-px bg-gray-200 dark:bg-white/10"></div>
                
                <a href="{{ route('admin.laporan.index') }}" class="relative flex items-center px-3 py-2 text-sm rounded-lg transition-colors {{ request()->routeIs('admin.laporan.index') || request()->routeIs('admin.laporan.pdf') ? 'text-brand dark:text-brand font-semibold bg-gray-50 dark:bg-white/5' : 'text-gray-500 dark:text-gray-400 hover:text-brand dark:hover:text-white hover:bg-gray-50 dark:hover:bg-white/5' }}">
                    @if(request()->routeIs('admin.laporan.index') || request()->routeIs('admin.laporan.pdf')) <div class="absolute -left-3 top-1/2 -translate-y-1/2 w-1.5 h-1.5 rounded-full bg-brand"></div> @endif
                    <i class="fa-solid fa-chart-pie w-5 text-center mr-2"></i>
                    Laporan Keuangan
                </a>
                <a href="{{ route('admin.laporan.laba-rugi') }}" class="relative flex items-center px-3 py-2 text-sm rounded-lg transition-colors {{ request()->routeIs('admin.laporan.laba-rugi') ? 'text-brand dark:text-brand font-semibold bg-gray-50 dark:bg-white/5' : 'text-gray-500 dark:text-gray-400 hover:text-brand dark:hover:text-white hover:bg-gray-50 dark:hover:bg-white/5' }}">
                    @if(request()->routeIs('admin.laporan.laba-rugi')) <div class="absolute -left-3 top-1/2 -translate-y-1/2 w-1.5 h-1.5 rounded-full bg-brand"></div> @endif
                    <i class="fa-solid fa-scale-balanced w-5 text-center mr-2"></i>
                    Laba Rugi
                </a>
            </div>
        </div>

        <!-- Dropdown Sistem & Pengaturan -->
        @php $isWaActive = request()->routeIs('admin.wa.*') || request()->routeIs('admin.rekening.*'); @endphp
        <div x-data="{ open: {{ $isWaActive ? 'true' : 'false' }} }" class="space-y-1 mt-2">
            <button @click="open = !open" class="flex items-center justify-between px-4 py-3 w-full rounded-xl transition-colors font-medium border-none {{ $isWaActive ? 'bg-lightBg dark:bg-white/5 text-brand dark:text-brand font-semibold' : 'text-gray-500 dark:text-gray-400 hover:text-brand dark:hover:text-white hover:bg-gray-50 dark:hover:bg-white/5' }}">
                <div class="flex items-center relative">
                    @if($isWaActive)
                        <div class="absolute -left-4 top-1/2 -translate-y-1/2 h-8 w-1 bg-brand rounded-r-full"></div>
                    @endif
                    <i class="fa-solid fa-gear w-5 text-center text-lg"></i>
                    <span class="mx-3">Sistem & Pengaturan</span>
                </div>
                <i class="fa-solid fa-chevron-down text-xs transition-transform duration-300" :class="open ? 'rotate-180' : ''"></i>
            </button>
            <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-2" style="display: none;" class="pl-11 pr-4 py-1 space-y-1 relative">
                <div class="absolute left-[1.35rem] top-0 bottom-2 w-px bg-gray-200 dark:bg-white/10"></div>
                
                <a href="{{ route('admin.rekening.index') }}" class="relative flex items-center px-3 py-2 text-sm rounded-lg transition-colors {{ request()->routeIs('admin.rekening.*') ? 'text-brand dark:text-brand font-semibold bg-gray-50 dark:bg-white/5' : 'text-gray-500 dark:text-gray-400 hover:text-brand dark:hover:text-white hover:bg-gray-50 dark:hover:bg-white/5' }}">
                    @if(request()->routeIs('admin.rekening.*')) <div class="absolute -left-3 top-1/2 -translate-y-1/2 w-1.5 h-1.5 rounded-full bg-brand"></div> @endif
                    <i class="fa-solid fa-building-columns w-5 text-center mr-2"></i>
                    Rekening Pembayaran
                </a>
                
                <a href="{{ route('admin.wa.settings') }}" class="relative flex items-center px-3 py-2 text-sm rounded-lg transition-colors {{ request()->routeIs('admin.wa.settings') ? 'text-brand dark:text-brand font-semibold bg-gray-50 dark:bg-white/5' : 'text-gray-500 dark:text-gray-400 hover:text-brand dark:hover:text-white hover:bg-gray-50 dark:hover:bg-white/5' }}">
                    @if(request()->routeIs('admin.wa.settings')) <div class="absolute -left-3 top-1/2 -translate-y-1/2 w-1.5 h-1.5 rounded-full bg-brand"></div> @endif
                    <i class="fa-brands fa-whatsapp w-5 text-center mr-2"></i>
                    Pengaturan WA
                </a>
                <a href="{{ route('admin.wa.members') }}" class="relative flex items-center px-3 py-2 text-sm rounded-lg transition-colors {{ request()->routeIs('admin.wa.members') ? 'text-brand dark:text-brand font-semibold bg-gray-50 dark:bg-white/5' : 'text-gray-500 dark:text-gray-400 hover:text-brand dark:hover:text-white hover:bg-gray-50 dark:hover:bg-white/5' }}">
                    @if(request()->routeIs('admin.wa.members')) <div class="absolute -left-3 top-1/2 -translate-y-1/2 w-1.5 h-1.5 rounded-full bg-brand"></div> @endif
                    <i class="fa-solid fa-mobile-screen w-5 text-center mr-2"></i>
                    Nomor WA Anggota
                </a>
            </div>
        </div>

    @else
        <p class="px-4 text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-3">Menu Anggota</p>
        
        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="flex items-center px-4 py-3 {{ request()->routeIs('dashboard') ? 'bg-lightBg dark:bg-white/5 text-brand dark:text-brand font-semibold' : 'text-gray-500 dark:text-gray-400 font-medium hover:text-brand dark:hover:text-white hover:bg-gray-50 dark:hover:bg-white/5' }} rounded-xl transition-colors relative w-full border-none">
            @if(request()->routeIs('dashboard'))
                <div class="absolute right-0 top-1/2 -translate-y-1/2 h-8 w-1 bg-brand rounded-l-full"></div>
            @endif
            <i class="fa-solid fa-house w-5 text-center text-lg"></i>
            <span class="mx-3">Dashboard Anggota</span>
        </x-nav-link>

        <!-- Dropdown Keuangan Anggota -->
        @php $isKeuanganActive = request()->routeIs('anggota.bayar.*', 'anggota.riwayat.*', 'anggota.saldo.*'); @endphp
        <div x-data="{ open: {{ $isKeuanganActive ? 'true' : 'false' }} }" class="space-y-1">
            <button @click="open = !open" class="flex items-center justify-between px-4 py-3 w-full rounded-xl transition-colors font-medium border-none {{ $isKeuanganActive ? 'bg-lightBg dark:bg-white/5 text-brand dark:text-brand font-semibold' : 'text-gray-500 dark:text-gray-400 hover:text-brand dark:hover:text-white hover:bg-gray-50 dark:hover:bg-white/5' }}">
                <div class="flex items-center relative">
                    @if($isKeuanganActive)
                        <div class="absolute -left-4 top-1/2 -translate-y-1/2 h-8 w-1 bg-brand rounded-r-full"></div>
                    @endif
                    <i class="fa-solid fa-wallet w-5 text-center text-lg"></i>
                    <span class="mx-3">Keuangan Kas</span>
                </div>
                <i class="fa-solid fa-chevron-down text-xs transition-transform duration-300" :class="open ? 'rotate-180' : ''"></i>
            </button>
            <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-2" style="display: none;" class="pl-11 pr-4 py-1 space-y-1 relative">
                <div class="absolute left-[1.35rem] top-0 bottom-2 w-px bg-gray-200 dark:bg-white/10"></div>
                
                <a href="{{ route('anggota.bayar.create') }}" class="relative flex items-center px-3 py-2 text-sm rounded-lg transition-colors {{ request()->routeIs('anggota.bayar.*') ? 'text-brand dark:text-brand font-semibold bg-gray-50 dark:bg-white/5' : 'text-gray-500 dark:text-gray-400 hover:text-brand dark:hover:text-white hover:bg-gray-50 dark:hover:bg-white/5' }}">
                    @if(request()->routeIs('anggota.bayar.*')) <div class="absolute -left-3 top-1/2 -translate-y-1/2 w-1.5 h-1.5 rounded-full bg-brand"></div> @endif
                    <i class="fa-solid fa-money-bill-transfer w-5 text-center mr-2"></i>
                    Bayar Kas
                </a>
                <a href="{{ route('anggota.riwayat.index') }}" class="relative flex items-center px-3 py-2 text-sm rounded-lg transition-colors {{ request()->routeIs('anggota.riwayat.*') ? 'text-brand dark:text-brand font-semibold bg-gray-50 dark:bg-white/5' : 'text-gray-500 dark:text-gray-400 hover:text-brand dark:hover:text-white hover:bg-gray-50 dark:hover:bg-white/5' }}">
                    @if(request()->routeIs('anggota.riwayat.*')) <div class="absolute -left-3 top-1/2 -translate-y-1/2 w-1.5 h-1.5 rounded-full bg-brand"></div> @endif
                    <i class="fa-solid fa-clock-rotate-left w-5 text-center mr-2"></i>
                    Riwayat Pembayaran
                </a>
                <a href="{{ route('anggota.saldo.index') }}" class="relative flex items-center px-3 py-2 text-sm rounded-lg transition-colors {{ request()->routeIs('anggota.saldo.*') ? 'text-brand dark:text-brand font-semibold bg-gray-50 dark:bg-white/5' : 'text-gray-500 dark:text-gray-400 hover:text-brand dark:hover:text-white hover:bg-gray-50 dark:hover:bg-white/5' }}">
                    @if(request()->routeIs('anggota.saldo.*')) <div class="absolute -left-3 top-1/2 -translate-y-1/2 w-1.5 h-1.5 rounded-full bg-brand"></div> @endif
                    <i class="fa-solid fa-wallet w-5 text-center mr-2"></i>
                    Info Saldo Kas
                </a>
            </div>
        </div>
        
        <div class="mt-8 mx-2 p-5 bg-gradient-to-br from-brand to-[#868CFF] rounded-xl text-white text-center shadow-lg shadow-brand/30 dark:shadow-none border border-white/10">
            <div class="bg-white/20 p-2 rounded-full inline-block mb-2">
                <i class="fa-solid fa-bell text-yellow-300"></i>
            </div>
            <h4 class="font-bold text-sm mb-1">Tagihan Aktif!</h4>
            <p class="text-xs text-white/80 mb-3">Segera lunasi kas periode bulan ini.</p>
            <a href="{{ route('anggota.bayar.create') }}" class="block w-full bg-white dark:bg-navy-800 text-brand dark:text-white text-xs font-bold py-2 rounded-lg hover:bg-gray-50 dark:hover:bg-navy-900 transition text-center">Bayar Sekarang</a>
        </div>
    @endif
</nav>

    <div class="p-4 border-t border-gray-100 dark:border-white/5 block sm:hidden">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="flex w-full items-center px-4 py-3 text-red-500 dark:text-red-400 font-medium hover:bg-red-50 dark:hover:bg-red-500/10 rounded-xl transition-colors">
                <i class="fa-solid fa-right-from-bracket w-5 text-center text-lg"></i>
                <span class="mx-3">Logout</span>
            </button>
        </form>
    </div>
</aside>