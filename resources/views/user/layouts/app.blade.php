<!DOCTYPE html>
<html lang="en" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }" x-bind:class="{ 'dark': darkMode }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="description" content="Edukasi Pemilu - Platform edukasi tentang pemilu dan pemilihan umum">
    <title>@yield('title', 'PILAR')</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#fef2f2',
                            100: '#fee2e2',
                            200: '#fecaca',
                            300: '#fca5a5',
                            400: '#f87171',
                            500: '#ef4444',
                            600: '#dc2626',
                            700: '#b91c1c',
                            800: '#991b1b',
                            900: '#7f1d1d',
                        },
                        accent: {
                            50: '#fffbeb',
                            100: '#fef3c7',
                            200: '#fde68a',
                            300: '#fcd34d',
                            400: '#fbbf24',
                            500: '#f59e0b',
                            600: '#d97706',
                            700: '#b45309',
                            800: '#92400e',
                            900: '#78350f',
                        }
                    }
                }
            }
        }
    </script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Custom styles -->
    <link rel="stylesheet" href="{{ asset('css/user.css') }}">

    <style>
        /* Base styles */
        .dark {
            color-scheme: dark;
        }

        .dark body {
            background-color: #1a1a1a;
            color: #f3f4f6;
        }
    </style>

    @stack('styles')
</head>
<body class="min-h-screen bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 transition-colors duration-200">

    <!-- Navbar -->
    @include('user.components.navbar')

    <!-- Main Content -->
    <div class="container mx-auto px-4 py-8">
        <div class="flex flex-col lg:flex-row gap-6">
            <!-- Main Content -->
            <div class="w-full lg:w-3/4">
                @yield('content')
            </div>

            <!-- Sidebar -->
            <div class="w-full lg:w-1/4">
                <div class="sticky top-24 rounded-lg bg-white dark:bg-gray-800 shadow-md p-4">
                    <h3 class="text-lg font-bold mb-4 flex items-center text-primary-600 dark:text-primary-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd" />
                        </svg>
                        Materi Terbaru
                    </h3>
                    <div id="video-list" class="divide-y divide-gray-200 dark:divide-gray-700 animate-pulse">
                        <div class="py-3">
                            <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-3/4 mb-2"></div>
                            <div class="h-3 bg-gray-200 dark:bg-gray-700 rounded w-1/2"></div>
                        </div>
                        <div class="py-3">
                            <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-3/4 mb-2"></div>
                            <div class="h-3 bg-gray-200 dark:bg-gray-700 rounded w-1/2"></div>
                        </div>
                        <div class="py-3">
                            <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-3/4 mb-2"></div>
                            <div class="h-3 bg-gray-200 dark:bg-gray-700 rounded w-1/2"></div>
                        </div>
                        <div class="py-3">
                            <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-3/4 mb-2"></div>
                            <div class="h-3 bg-gray-200 dark:bg-gray-700 rounded w-1/2"></div>
                        </div>
                        <div class="py-3">
                            <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-3/4 mb-2"></div>
                            <div class="h-3 bg-gray-200 dark:bg-gray-700 rounded w-1/2"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-400 shadow-inner mt-10">
        <div class="container mx-auto px-4 py-6">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="mb-4 md:mb-0">
                    <p>&copy; {{ date('Y') }} PILAR. All rights reserved.</p>
                </div>
                <div class="flex space-x-4">
                    <a href="#" class="hover:text-primary-600 dark:hover:text-primary-400 transition-colors">Tentang Kami</a>
                    <a href="#" class="hover:text-primary-600 dark:hover:text-primary-400 transition-colors">Kebijakan Privasi</a>
                    <a href="#" class="hover:text-primary-600 dark:hover:text-primary-400 transition-colors">Kontak</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Custom scripts -->
    <script src="{{ asset('js/user.js') }}"></script>

    @stack('scripts')
</body>
</html>

