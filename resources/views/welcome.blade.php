<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>HIMSU KAS - Kelola Keuangan Organisasi</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Tailwind CSS (CDN for Landing Page) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: {
                        brand: '#4318FF',
                        brandHover: '#3311DB',
                        brandLight: '#E9E3FF',
                        darkText: '#111C44',
                        navy: {
                            700: '#111C44',
                            800: '#0B1437',
                            900: '#080F25',
                        }
                    },
                    animation: {
                        'float': 'float 6s ease-in-out infinite',
                        'fade-in-up': 'fadeInUp 1s ease-out forwards',
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-20px)' },
                        },
                        fadeInUp: {
                            '0%': { opacity: '0', transform: 'translateY(20px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        }
                    }
                }
            }
        }
    </script>
    <!-- Alpine JS for Mobile Menu -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="font-sans antialiased bg-gray-50 text-gray-600 selection:bg-brand selection:text-white" x-data="{ mobileMenuOpen: false, scrolled: false }" @scroll.window="scrolled = (window.pageYOffset > 20)">

    <!-- Navbar -->
    <nav :class="{ 'bg-white/80 backdrop-blur-md shadow-sm': scrolled, 'bg-transparent': !scrolled }" class="fixed w-full z-50 transition-all duration-300 pointer-events-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center gap-2">
                    <div class="w-10 h-10 bg-gradient-to-br from-brand to-[#868CFF] rounded-xl flex items-center justify-center text-white shadow-lg shadow-brand/30">
                        <i class="fa-solid fa-wallet text-xl"></i>
                    </div>
                    <span class="font-bold text-2xl text-darkText tracking-tight">HIMSU<span class="text-brand">KAS</span></span>
                </div>

                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#fitur" class="text-gray-500 hover:text-brand font-medium transition">Keunggulan</a>
                    <a href="#tentang" class="text-gray-500 hover:text-brand font-medium transition">Tentang Kami</a>
                    
                    <div class="flex items-center space-x-4 pl-4 border-l border-gray-200">
                        @if (Route::has('login'))
                            @auth
                                <a href="{{ url('/dashboard') }}" class="text-brand font-semibold hover:text-brandHover transition">Dasbor Anda &rarr;</a>
                            @else
                                <a href="{{ route('login') }}" class="text-gray-600 hover:text-brand font-semibold transition">Masuk</a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="bg-brand hover:bg-brandHover text-white px-5 py-2.5 rounded-xl font-bold transition shadow-lg shadow-brand/30 transform hover:-translate-y-0.5">Daftar Sekarang</a>
                                @endif
                            @endauth
                        @endif
                    </div>
                </div>

                <!-- Mobile menu button -->
                <div class="md:hidden flex items-center">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-gray-500 hover:text-brand focus:outline-none p-2">
                        <i class="fa-solid fa-bars text-2xl" x-show="!mobileMenuOpen"></i>
                        <i class="fa-solid fa-xmark text-2xl" x-show="mobileMenuOpen" x-cloak></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu Panel -->
        <div x-show="mobileMenuOpen" x-transition.opacity class="md:hidden bg-white border-t border-gray-100 absolute w-full shadow-xl" x-cloak>
            <div class="px-4 pt-2 pb-6 space-y-1">
                <a href="#fitur" @click="mobileMenuOpen = false" class="block px-3 py-3 text-base font-medium text-gray-600 hover:text-brand hover:bg-gray-50 rounded-lg">Keunggulan</a>
                <a href="#tentang" @click="mobileMenuOpen = false" class="block px-3 py-3 text-base font-medium text-gray-600 hover:text-brand hover:bg-gray-50 rounded-lg">Tentang Kami</a>
                <div class="border-t border-gray-100 my-2 pt-2">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="block px-3 py-3 text-base font-bold text-brand hover:bg-gray-50 rounded-lg">Menuju Dasbor</a>
                        @else
                            <a href="{{ route('login') }}" class="block px-3 py-3 text-base font-medium text-gray-600 hover:text-brand hover:bg-gray-50 rounded-lg">Masuk (Login)</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="block w-full text-center mt-2 bg-brand text-white px-5 py-3 rounded-xl font-bold shadow-md">Daftar Baru</a>
                            @endif
                        @endauth
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="relative pt-32 pb-20 lg:pt-48 lg:pb-32 overflow-hidden">
        <!-- Background Decorations -->
        <div class="absolute top-0 right-0 -mr-20 -mt-20 w-96 h-96 rounded-full bg-brandLight/50 blur-3xl opacity-60 pointer-events-none"></div>
        <div class="absolute bottom-10 left-10 w-72 h-72 rounded-full bg-blue-100/50 blur-3xl opacity-60 pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <!-- Text Content -->
                <div class="text-center lg:text-left order-2 lg:order-1 animate-fade-in-up">
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-brandLight text-brand text-sm font-semibold mb-6">
                        <span class="relative flex h-2 w-2">
                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-brand opacity-75"></span>
                          <span class="relative inline-flex rounded-full h-2 w-2 bg-brand"></span>
                        </span>
                        Sistem Kas Digital Terpadu
                    </div>
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-darkText leading-tight tracking-tight mb-6">
                        Kelola Uang Kas <br class="hidden lg:block"/>Organisasi Jadi <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand to-[#868CFF]">Lebih Mudah</span>
                    </h1>
                    <p class="text-lg sm:text-xl text-gray-500 mb-8 max-w-2xl mx-auto lg:mx-0 leading-relaxed">
                        Tinggalkan pencatatan manual di buku tulis. Dengan HIMSU KAS, bayar iuran, cek saldo bulanan, hingga laporan keuangan menjadi transparan, aman, dan bisa diakses kapan saja.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                        @if (Route::has('login'))
                            @auth
                                <a href="{{ url('/dashboard') }}" class="bg-brand hover:bg-brandHover text-white px-8 py-3.5 rounded-xl font-bold text-lg transition shadow-xl shadow-brand/30 flex items-center justify-center gap-2 transform hover:-translate-y-1">
                                    Buka Dasbor <i class="fa-solid fa-arrow-right"></i>
                                </a>
                            @else
                                <a href="{{ route('register') }}" class="bg-brand hover:bg-brandHover text-white px-8 py-3.5 rounded-xl font-bold text-lg transition shadow-xl shadow-brand/30 flex items-center justify-center gap-2 transform hover:-translate-y-1">
                                    Mulai Sekarang <i class="fa-solid fa-rocket"></i>
                                </a>
                                <a href="#fitur" class="bg-white border-2 border-gray-200 text-gray-600 hover:border-brand hover:text-brand px-8 py-3.5 rounded-xl font-bold text-lg transition flex items-center justify-center">
                                    Pelajari Fitur
                                </a>
                            @endauth
                        @endif
                    </div>
                    
                    <div class="mt-10 flex items-center justify-center lg:justify-start gap-6 text-sm text-gray-400 font-medium">
                        <div class="flex items-center gap-2"><i class="fa-solid fa-check-circle text-green-500"></i> Gratis Tuntas</div>
                        <div class="flex items-center gap-2"><i class="fa-solid fa-check-circle text-green-500"></i> Akses 24/7</div>
                    </div>
                </div>

                <!-- Illustration / Graphic -->
                <div class="order-1 lg:order-2 flex justify-center lg:justify-end animate-float">
                    <div class="relative w-full max-w-lg lg:max-w-none">
                        <!-- We use an abstract composition of cards to symbolize digital wallet/finance -->
                        <div class="relative h-80 sm:h-96 w-full rounded-3xl bg-gradient-to-tr from-brand to-[#868CFF] shadow-[0_20px_50px_-12px_rgba(67,24,255,0.4)] flex items-center justify-center overflow-hidden">
                            <!-- Abstract Shapes inside -->
                            <div class="absolute -right-10 -top-10 w-40 h-40 bg-white/20 rounded-full blur-2xl"></div>
                            <div class="absolute -left-10 -bottom-10 w-40 h-40 bg-white/20 rounded-full blur-2xl"></div>
                            
                            <div class="relative z-10 text-center text-white">
                                <div class="bg-white/20 backdrop-blur-md p-6 rounded-2xl border border-white/30 inline-block mb-6 shadow-xl">
                                    <i class="fa-solid fa-file-invoice-dollar text-6xl text-white"></i>
                                </div>
                                <h3 class="text-3xl font-bold mb-2 tracking-wide">Saldo Aman</h3>
                                <p class="text-white/80 font-medium px-8 text-sm">Validasi oleh bendahara secara *real-time*.</p>
                            </div>
                            
                            <!-- Floating small card 1 -->
                            <div class="absolute top-10 left-10 bg-white p-4 rounded-xl shadow-xl flex items-center gap-3 animate-pulse" style="animation-duration: 3s;">
                                <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center text-green-500"><i class="fa-solid fa-arrow-trend-up"></i></div>
                                <div>
                                    <div class="text-xs text-gray-400 font-bold">Pemasukan</div>
                                    <div class="text-sm font-bold text-darkText">+ Rp 50.000</div>
                                </div>
                            </div>

                            <!-- Floating small card 2 -->
                            <div class="absolute bottom-10 right-10 bg-white p-4 rounded-xl shadow-xl flex items-center gap-3 animate-pulse" style="animation-duration: 4s; animation-delay: 1s;">
                                <div class="w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center text-purple-500"><i class="fa-solid fa-check-double"></i></div>
                                <div>
                                    <div class="text-xs text-gray-400 font-bold">Status</div>
                                    <div class="text-sm font-bold text-darkText">Diverifikasi</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div id="fitur" class="py-20 bg-white relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <h2 class="text-brand font-bold uppercase tracking-wider text-sm mb-2">Kenapa Memilih Kami?</h2>
                <h3 class="text-3xl sm:text-4xl font-extrabold text-darkText">Fitur Unggulan HIMSU KAS</h3>
                <p class="mt-4 text-gray-500 text-lg">Sistem yang dirancang khusus untuk memenuhi kebutuhan organisasi Anda agar lebih produktif, jujur, dan teratur.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="bg-gray-50 rounded-2xl p-8 hover:bg-brandLight/30 border border-gray-100 hover:border-brand/20 transition-all duration-300 group hover:-translate-y-2">
                    <div class="w-14 h-14 bg-white shadow-sm rounded-xl flex items-center justify-center text-brand text-2xl mb-6 group-hover:scale-110 group-hover:bg-brand group-hover:text-white transition-all">
                        <i class="fa-solid fa-book-journal-whills"></i>
                    </div>
                    <h4 class="text-xl font-bold text-darkText mb-3">Pencatatan Otomatis</h4>
                    <p class="text-gray-500 text-sm leading-relaxed">Seluruh kas masuk & keluar tercatat rapi di buku kas digital. Bebas masalah coretan salah tulis pada buku konvensional.</p>
                </div>

                <!-- Feature 2 -->
                <div class="bg-gray-50 rounded-2xl p-8 hover:bg-brandLight/30 border border-gray-100 hover:border-brand/20 transition-all duration-300 group hover:-translate-y-2">
                    <div class="w-14 h-14 bg-white shadow-sm rounded-xl flex items-center justify-center text-brand text-2xl mb-6 group-hover:scale-110 group-hover:bg-brand group-hover:text-white transition-all">
                        <i class="fa-solid fa-user-shield"></i>
                    </div>
                    <h4 class="text-xl font-bold text-darkText mb-3">Transparan & Terbuka</h4>
                    <p class="text-gray-500 text-sm leading-relaxed">Semua anggota yang terdaftar memiliki akses untuk melihat laporan arus keuangan dan riwayat tunggakan kas mereka sendiri kapan saja.</p>
                </div>

                <!-- Feature 3 -->
                <div class="bg-gray-50 rounded-2xl p-8 hover:bg-brandLight/30 border border-gray-100 hover:border-brand/20 transition-all duration-300 group hover:-translate-y-2">
                    <div class="w-14 h-14 bg-white shadow-sm rounded-xl flex items-center justify-center text-brand text-2xl mb-6 group-hover:scale-110 group-hover:bg-brand group-hover:text-white transition-all">
                        <i class="fa-solid fa-file-pdf"></i>
                    </div>
                    <h4 class="text-xl font-bold text-darkText mb-3">Ekspor Laporan PDF</h4>
                    <p class="text-gray-500 text-sm leading-relaxed">Satu klik untuk menghasilkan laporan keuangan formal dalam format cetak PDF, siap ditandatangani dan diserahkan ke musyawarah/ketua.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="py-20 bg-darkText relative overflow-hidden">
        <!-- Abstract gradient overlay -->
        <div class="absolute inset-0 bg-gradient-to-br from-brand/20 to-transparent"></div>
        <div class="absolute right-0 bottom-0 w-64 h-64 bg-brand rounded-full blur-[100px] opacity-40 mix-blend-screen"></div>

        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
            <h2 class="text-3xl sm:text-4xl font-extrabold text-white mb-6">Siap Mengubah Cara Organisasi Anda Kelola Uang Kas?</h2>
            <p class="text-lg text-gray-300 mb-10 max-w-2xl mx-auto">Bergabunglah sekarang. Nikmati kemudahan verifikasi bukti transfer otomatis, pembagian periode yang jelas, dan bebas dari selisih uang yang membingungkan.</p>
            
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/dashboard') }}" class="inline-block bg-white text-darkText hover:bg-gray-100 px-8 py-4 rounded-xl font-bold text-lg transition shadow-xl transform hover:-translate-y-1">
                        Lanjut ke Aplikasi &rarr;
                    </a>
                @else
                    <a href="{{ route('register') }}" class="inline-block bg-brand hover:bg-brandHover text-white px-8 py-4 rounded-xl font-bold text-lg border border-brandHover transition shadow-xl shadow-brand/40 transform hover:-translate-y-1">
                        Daftar Anggota Sekarang
                    </a>
                @endauth
            @endif
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-100 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center gap-6">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-gradient-to-br from-brand to-[#868CFF] rounded-lg flex items-center justify-center text-white">
                        <i class="fa-solid fa-wallet text-sm"></i>
                    </div>
                    <span class="font-bold text-xl text-darkText tracking-tight">HIMSU<span class="text-brand">KAS</span></span>
                </div>
                
                <p class="text-gray-400 text-sm text-center md:text-left">
                    &copy; {{ date('Y') }} HIMSU KAS. Sistem Manajemen Kas Terpadu. Built with Laravel 11.
                </p>
                
                <div class="flex space-x-4">
                    <a href="#" class="w-10 h-10 rounded-full bg-gray-50 flex items-center justify-center text-gray-400 hover:text-brand hover:bg-brandLight transition">
                        <i class="fa-brands fa-whatsapp text-lg"></i>
                    </a>
                    <a href="#" class="w-10 h-10 rounded-full bg-gray-50 flex items-center justify-center text-gray-400 hover:text-brand hover:bg-brandLight transition">
                        <i class="fa-brands fa-instagram text-lg"></i>
                    </a>
                    <a href="#" class="w-10 h-10 rounded-full bg-gray-50 flex items-center justify-center text-gray-400 hover:text-brand hover:bg-brandLight transition">
                        <i class="fa-solid fa-envelope text-lg"></i>
                    </a>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>
