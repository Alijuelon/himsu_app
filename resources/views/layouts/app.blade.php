<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'HIMSU KAS') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
                        darkText: '#2B3674',
                        lightBg: '#F4F7FE',
                        navy: {
                            700: '#111C44',
                            800: '#0B1437',
                            900: '#080F25',
                        }
                    }
                }
            }
        }
    </script>
    <script>
        // Prevent FOUC (Flash of Unstyled Content) by setting dark mode immediately
        if (localStorage.getItem('darkMode') === 'true') {
            document.documentElement.classList.add('dark');
        }
    </script>
</head>
<body x-data="{ 
        sidebarOpen: false, 
        darkMode: localStorage.getItem('darkMode') === 'true' 
    }" 
    x-init="$watch('darkMode', val => {
        localStorage.setItem('darkMode', val);
        if (val) document.documentElement.classList.add('dark');
        else document.documentElement.classList.remove('dark');
    })" 
    :class="{ 'dark': darkMode }" 
    class="font-sans antialiased text-gray-600 dark:text-gray-300">

    <div class="flex h-screen overflow-hidden bg-lightBg dark:bg-navy-800 transition-colors duration-300">
        
        @include('layouts.navigation')

        <div class="flex-1 flex flex-col overflow-hidden relative">
            
            <header class="flex items-center justify-between px-6 py-5 bg-lightBg/80 dark:bg-navy-800/80 backdrop-blur-md sticky top-0 z-10 transition-colors">
                <div class="flex items-center">
                    <button @click="sidebarOpen = true" class="text-gray-500 dark:text-gray-400 hover:text-brand dark:hover:text-white focus:outline-none lg:hidden mr-4">
                        <i class="fa-solid fa-bars text-xl"></i>
                    </button>
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 font-medium mb-1">Pages / {{ $header ?? 'Dashboard' }}</p>
                        <h2 class="text-2xl lg:text-3xl font-bold text-darkText dark:text-white transition-colors">
                            {{ $header ?? 'Dashboard' }}
                        </h2>
                    </div>
                </div>

                <div class="flex items-center bg-white dark:bg-navy-700 p-2 rounded-full shadow-sm border border-gray-50 dark:border-white/5 transition-colors">
                    
                    <div class="relative hidden sm:block mx-2">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4">
                            <i class="fa-solid fa-magnifying-glass text-gray-400 dark:text-gray-500 text-sm"></i>
                        </span>
                        <input type="text" class="w-48 lg:w-64 py-2.5 pl-10 pr-4 text-sm bg-lightBg dark:bg-navy-800 text-gray-700 dark:text-white rounded-full focus:outline-none focus:ring-2 focus:ring-brand/50 transition-all placeholder-gray-400 dark:placeholder-gray-500" placeholder="Search...">
                    </div>
                    
                    <button class="mx-2 text-gray-400 dark:text-gray-300 hover:text-brand dark:hover:text-white transition">
                        <i class="fa-regular fa-bell"></i>
                    </button>
                    
                    <button @click="darkMode = !darkMode" class="mx-2 text-gray-400 dark:text-gray-300 hover:text-brand dark:hover:text-white transition">
                        <i class="fa-solid" :class="darkMode ? 'fa-sun' : 'fa-moon'"></i>
                    </button>

                    <div x-data="{ profileOpen: false }" class="relative ml-2">
                        <button @click="profileOpen = !profileOpen" class="focus:outline-none">
                            <img class="object-cover w-10 h-10 rounded-full border-2 border-white dark:border-navy-700 shadow-sm cursor-pointer" 
                                 src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->nama_lengkap ?? Auth::user()->name) }}&background=4318FF&color=fff&bold=true" 
                                 alt="Profile">
                        </button>
                        
                        <div x-show="profileOpen" @click.away="profileOpen = false" x-transition 
                             class="absolute right-0 mt-2 w-48 bg-white dark:bg-navy-700 rounded-xl shadow-lg py-2 border border-gray-100 dark:border-white/5 z-50">
                            
                            <div class="px-4 py-2 border-b border-gray-100 dark:border-white/5 mb-1">
                                <p class="text-sm font-bold text-darkText dark:text-white">{{ Auth::user()->nama_lengkap ?? Auth::user()->name }}</p>
                                <p class="text-xs text-gray-400">{{ Auth::user()->email ?? Auth::user()->username }}</p>
                            </div>

                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-600 dark:text-gray-300 hover:bg-lightBg dark:hover:bg-white/5 transition">
                                <i class="fa-regular fa-user w-5"></i> Profile
                            </a>
                            
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-500 hover:bg-red-50 dark:hover:bg-red-500/10 transition">
                                    <i class="fa-solid fa-right-from-bracket w-5"></i> Log Out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-x-hidden overflow-y-auto p-6">
                {{ $slot }}
            </main>

        </div>
    </div>
</body>
</html>