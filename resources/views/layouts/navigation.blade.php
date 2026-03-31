<div x-show="sidebarOpen" 
     x-transition.opacity 
     @click="sidebarOpen = false" 
     class="fixed inset-0 z-20 bg-gray-900/50 backdrop-blur-sm lg:hidden">
</div>

<aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" 
       class="fixed inset-y-0 left-0 z-30 w-64 bg-white dark:bg-navy-700 transform transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-auto flex flex-col shadow-xl lg:shadow-none border-r border-gray-100 dark:border-white/5">
    
    <div class="flex items-center justify-center h-24 border-b border-gray-100 dark:border-white/5">
        <a href="{{ route('dashboard') }}">
            <h1 class="text-2xl font-bold text-darkText dark:text-white tracking-wide transition-colors">
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

        <x-nav-link :href="route('admin.anggota.index')" :active="request()->routeIs('admin.anggota.*')" class="flex items-center px-4 py-3 {{ request()->routeIs('admin.anggota.*') ? 'bg-lightBg dark:bg-white/5 text-brand dark:text-brand font-semibold' : 'text-gray-500 dark:text-gray-400 font-medium hover:text-brand dark:hover:text-white hover:bg-gray-50 dark:hover:bg-white/5' }} rounded-xl transition-colors relative w-full border-none">
            @if(request()->routeIs('admin.anggota.*'))
                <div class="absolute right-0 top-1/2 -translate-y-1/2 h-8 w-1 bg-brand rounded-l-full"></div>
            @endif
            <i class="fa-solid fa-users w-5 text-center text-lg"></i>
            <span class="mx-3">Data Anggota</span>
        </x-nav-link>
        <x-nav-link :href="route('admin.periode.index')" :active="request()->routeIs('admin.periode.*')" class="flex items-center px-4 py-3 {{ request()->routeIs('admin.periode.*') ? 'bg-lightBg dark:bg-white/5 text-brand dark:text-brand font-semibold' : 'text-gray-500 dark:text-gray-400 font-medium hover:text-brand dark:hover:text-white hover:bg-gray-50 dark:hover:bg-white/5' }} rounded-xl transition-colors relative w-full border-none">
            @if(request()->routeIs('admin.periode.*'))
                <div class="absolute right-0 top-1/2 -translate-y-1/2 h-8 w-1 bg-brand rounded-l-full"></div>
            @endif
            <i class="fa-solid fa-calendar-days w-5 text-center text-lg"></i>
            <span class="mx-3">Periode Tagihan</span>
        </x-nav-link>
        <x-nav-link :href="route('admin.pembayaran.index')" :active="request()->routeIs('admin.pembayaran.*')" class="flex items-center px-4 py-3 {{ request()->routeIs('admin.pembayaran.*') ? 'bg-lightBg dark:bg-white/5 text-brand dark:text-brand font-semibold' : 'text-gray-500 dark:text-gray-400 font-medium hover:text-brand dark:hover:text-white hover:bg-gray-50 dark:hover:bg-white/5' }} rounded-xl transition-colors relative w-full border-none">
            @if(request()->routeIs('admin.pembayaran.*'))
                <div class="absolute right-0 top-1/2 -translate-y-1/2 h-8 w-1 bg-brand rounded-l-full"></div>
            @endif
            <i class="fa-solid fa-file-invoice-dollar w-5 text-center text-lg"></i>
            <span class="mx-3">Pembayaran Kas</span>
        </x-nav-link>

        <x-nav-link :href="route('admin.bukukas.pemasukan')" :active="request()->routeIs('admin.bukukas.pemasukan')" class="flex items-center px-4 py-3 {{ request()->routeIs('admin.bukukas.pemasukan') ? 'bg-lightBg dark:bg-white/5 text-brand dark:text-brand font-semibold' : 'text-gray-500 dark:text-gray-400 font-medium hover:text-brand dark:hover:text-white hover:bg-gray-50 dark:hover:bg-white/5' }} rounded-xl transition-colors relative w-full border-none">
            @if(request()->routeIs('admin.bukukas.pemasukan'))
                <div class="absolute right-0 top-1/2 -translate-y-1/2 h-8 w-1 bg-brand rounded-l-full"></div>
            @endif
            <i class="fa-solid fa-arrow-trend-up w-5 text-center text-lg"></i>
            <span class="mx-3">Pemasukan Kas</span>
        </x-nav-link>

        <x-nav-link :href="route('admin.bukukas.pengeluaran')" :active="request()->routeIs('admin.bukukas.pengeluaran')" class="flex items-center px-4 py-3 {{ request()->routeIs('admin.bukukas.pengeluaran') ? 'bg-lightBg dark:bg-white/5 text-brand dark:text-brand font-semibold' : 'text-gray-500 dark:text-gray-400 font-medium hover:text-brand dark:hover:text-white hover:bg-gray-50 dark:hover:bg-white/5' }} rounded-xl transition-colors relative w-full border-none">
            @if(request()->routeIs('admin.bukukas.pengeluaran'))
                <div class="absolute right-0 top-1/2 -translate-y-1/2 h-8 w-1 bg-brand rounded-l-full"></div>
            @endif
            <i class="fa-solid fa-arrow-trend-down w-5 text-center text-lg"></i>
            <span class="mx-3">Pengeluaran Kas</span>
        </x-nav-link>

        <x-nav-link :href="route('admin.laporan.index')" :active="request()->routeIs('admin.laporan.*')" class="flex items-center px-4 py-3 {{ request()->routeIs('admin.laporan.*') ? 'bg-lightBg dark:bg-white/5 text-brand dark:text-brand font-semibold' : 'text-gray-500 dark:text-gray-400 font-medium hover:text-brand dark:hover:text-white hover:bg-gray-50 dark:hover:bg-white/5' }} rounded-xl transition-colors relative w-full border-none">
            @if(request()->routeIs('admin.laporan.*'))
                <div class="absolute right-0 top-1/2 -translate-y-1/2 h-8 w-1 bg-brand rounded-l-full"></div>
            @endif
            <i class="fa-solid fa-chart-pie w-5 text-center text-lg"></i>
            <span class="mx-3">Laporan Keuangan</span>
        </x-nav-link>

        <p class="px-4 text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-3 mt-4">WhatsApp Gateway</p>

        <x-nav-link :href="route('admin.wa.settings')" :active="request()->routeIs('admin.wa.settings')" class="flex items-center px-4 py-3 {{ request()->routeIs('admin.wa.settings') ? 'bg-lightBg dark:bg-white/5 text-brand dark:text-brand font-semibold' : 'text-gray-500 dark:text-gray-400 font-medium hover:text-brand dark:hover:text-white hover:bg-gray-50 dark:hover:bg-white/5' }} rounded-xl transition-colors relative w-full border-none">
            @if(request()->routeIs('admin.wa.settings'))
                <div class="absolute right-0 top-1/2 -translate-y-1/2 h-8 w-1 bg-brand rounded-l-full"></div>
            @endif
            <i class="fa-brands fa-whatsapp w-5 text-center text-lg"></i>
            <span class="mx-3">Pengaturan WA</span>
        </x-nav-link>

        <x-nav-link :href="route('admin.wa.members')" :active="request()->routeIs('admin.wa.members')" class="flex items-center px-4 py-3 {{ request()->routeIs('admin.wa.members') ? 'bg-lightBg dark:bg-white/5 text-brand dark:text-brand font-semibold' : 'text-gray-500 dark:text-gray-400 font-medium hover:text-brand dark:hover:text-white hover:bg-gray-50 dark:hover:bg-white/5' }} rounded-xl transition-colors relative w-full border-none">
            @if(request()->routeIs('admin.wa.members'))
                <div class="absolute right-0 top-1/2 -translate-y-1/2 h-8 w-1 bg-brand rounded-l-full"></div>
            @endif
            <i class="fa-solid fa-mobile-screen w-5 text-center text-lg"></i>
            <span class="mx-3">Nomor WA Anggota</span>
        </x-nav-link>

    @else
        <p class="px-4 text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-3">Menu Anggota</p>
        
        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="flex items-center px-4 py-3 {{ request()->routeIs('dashboard') ? 'bg-lightBg dark:bg-white/5 text-brand dark:text-brand font-semibold' : 'text-gray-500 dark:text-gray-400 font-medium hover:text-brand dark:hover:text-white hover:bg-gray-50 dark:hover:bg-white/5' }} rounded-xl transition-colors relative w-full border-none">
            @if(request()->routeIs('dashboard'))
                <div class="absolute right-0 top-1/2 -translate-y-1/2 h-8 w-1 bg-brand rounded-l-full"></div>
            @endif
            <i class="fa-solid fa-house w-5 text-center text-lg"></i>
            <span class="mx-3">Dashboard Anggota</span>
        </x-nav-link>

        <x-nav-link :href="route('anggota.bayar.create')" :active="request()->routeIs('anggota.bayar.*')" class="flex items-center px-4 py-3 {{ request()->routeIs('anggota.bayar.*') ? 'bg-lightBg dark:bg-white/5 text-brand dark:text-brand font-semibold' : 'text-gray-500 dark:text-gray-400 font-medium hover:text-brand dark:hover:text-white hover:bg-gray-50 dark:hover:bg-white/5' }} rounded-xl transition-colors relative w-full border-none">
            @if(request()->routeIs('anggota.bayar.*'))
                <div class="absolute right-0 top-1/2 -translate-y-1/2 h-8 w-1 bg-brand rounded-l-full"></div>
            @endif
            <i class="fa-solid fa-money-bill-transfer w-5 text-center text-lg"></i>
            <span class="mx-3">Bayar Kas</span>
        </x-nav-link>

        <x-nav-link :href="route('anggota.riwayat.index')" :active="request()->routeIs('anggota.riwayat.*')" class="flex items-center px-4 py-3 {{ request()->routeIs('anggota.riwayat.*') ? 'bg-lightBg dark:bg-white/5 text-brand dark:text-brand font-semibold' : 'text-gray-500 dark:text-gray-400 font-medium hover:text-brand dark:hover:text-white hover:bg-gray-50 dark:hover:bg-white/5' }} rounded-xl transition-colors relative w-full border-none">
            @if(request()->routeIs('anggota.riwayat.*'))
                <div class="absolute right-0 top-1/2 -translate-y-1/2 h-8 w-1 bg-brand rounded-l-full"></div>
            @endif
            <i class="fa-solid fa-clock-rotate-left w-5 text-center text-lg"></i>
            <span class="mx-3">Riwayat Pembayaran</span>
        </x-nav-link>
        
        <x-nav-link :href="route('anggota.saldo.index')" :active="request()->routeIs('anggota.saldo.*')" class="flex items-center px-4 py-3 {{ request()->routeIs('anggota.saldo.*') ? 'bg-lightBg dark:bg-white/5 text-brand dark:text-brand font-semibold' : 'text-gray-500 dark:text-gray-400 font-medium hover:text-brand dark:hover:text-white hover:bg-gray-50 dark:hover:bg-white/5' }} rounded-xl transition-colors relative w-full border-none">
            @if(request()->routeIs('anggota.saldo.*'))
                <div class="absolute right-0 top-1/2 -translate-y-1/2 h-8 w-1 bg-brand rounded-l-full"></div>
            @endif
            <i class="fa-solid fa-wallet w-5 text-center text-lg"></i>
            <span class="mx-3">Info Saldo Kas</span>
        </x-nav-link>
        
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