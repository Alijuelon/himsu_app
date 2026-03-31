<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'HIMSU KAS') }} - Authentication</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        @vite(['resources/css/app.css', 'resources/js/app.js'])

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
                            darkText: '#111C44',
                            lightBg: '#F4F7FE',
                            navy: {
                                700: '#111C44',
                                800: '#0B1437',
                                900: '#080F25',
                            }
                        },
                        animation: {
                            'fade-in': 'fadeIn 0.6s ease-out forwards',
                        },
                        keyframes: {
                            fadeIn: {
                                '0%': { opacity: '0', transform: 'translateY(10px)' },
                                '100%': { opacity: '1', transform: 'translateY(0)' },
                            }
                        }
                    }
                }
            }
        </script>
    </head>
    <body class="font-sans text-gray-600 antialiased bg-white dark:bg-navy-900 transition-colors duration-300 selection:bg-brand selection:text-white">
        
        <div class="min-h-screen flex">
            <!-- Left Column: Form Area -->
            <div class="flex-1 flex flex-col justify-center items-center px-6 sm:px-12 lg:px-24 bg-white dark:bg-navy-800 relative z-10 transition-colors duration-300">
                
                <div class="w-full max-w-md xl:max-w-lg mb-8 animate-fade-in">
                    <!-- Logo / Home Link -->
                    <a href="/" class="inline-flex items-center gap-2 mb-10 hover:opacity-80 transition-opacity">
                        <div class="w-10 h-10 bg-gradient-to-br from-brand to-[#868CFF] rounded-xl flex items-center justify-center text-white shadow-lg shadow-brand/30">
                            <i class="fa-solid fa-wallet text-xl"></i>
                        </div>
                        <h1 class="text-2xl font-bold text-darkText dark:text-white tracking-wide">
                            HIMSU<span class="text-brand">KAS</span>
                        </h1>
                    </a>

                    <!-- Render Form (Login / Register) -->
                    {{ $slot }}

                    <!-- Credit inside left column at the bottom -->
                    <div class="mt-12 text-xs text-gray-400 dark:text-gray-500 text-center font-medium">
                        &copy; {{ date('Y') }} Himpunan Mahasiswa Sumatera Utara Bengkalis.
                    </div>
                </div>
            </div>

            <!-- Right Column: Visual Area (Hidden on Mobile) -->
            <div class="hidden lg:flex lg:flex-1 relative bg-brand overflow-hidden flex-col justify-center items-center">
                
                <!-- Abstract Background Shapes -->
                <div class="absolute inset-0 w-full h-full bg-gradient-to-br from-[#868CFF] to-brand opacity-90 mixed-blend-screen"></div>
                <div class="absolute -right-20 -top-20 w-96 h-96 rounded-full bg-white/10 blur-3xl"></div>
                <div class="absolute -left-20 bottom-10 w-80 h-80 rounded-full bg-white/10 blur-3xl"></div>

                <!-- Graphic & Quote Content -->
                <div class="relative z-10 p-16 flex flex-col items-center justify-center text-center text-white h-full animate-fade-in" style="animation-delay: 0.2s;">
                    
                    <div class="bg-white/10 backdrop-blur-md p-6 rounded-3xl border border-white/20 mb-8 shadow-2xl transform hover:scale-105 transition duration-500">
                        <i class="fa-solid fa-shield-halved text-7xl text-white"></i>
                    </div>

                    <h2 class="text-4xl xl:text-5xl font-extrabold tracking-tight leading-tight mb-6">
                        Transparansi.<br/>Keteraturan.<br/>Kepercayaan.
                    </h2>
                    
                    <p class="text-lg xl:text-xl text-white/80 max-w-lg leading-relaxed font-medium">
                        "Sistem digitalisasi keuangan organisasi ini diciptakan murni untuk menghilangkan celah kesalahan selisih kas pengurus dan menjunjung tinggi transparansi antar anggota HIMSU."
                    </p>
                    
                    <!-- Decorative progress dots -->
                    <div class="mt-12 flex space-x-2">
                        <div class="w-3 h-3 rounded-full bg-white shadow-lg"></div>
                        <div class="w-3 h-3 rounded-full bg-white/30"></div>
                        <div class="w-3 h-3 rounded-full bg-white/30"></div>
                    </div>
                </div>
            </div>

        </div>
    </body>
</html>