@extends('user.layouts.app')

@section('title', 'PILAR - Materi PILAR')

@section('content')
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-6">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">PILAR</h1>
        <p class="text-lg text-gray-600 dark:text-gray-400">
            Temukan berbagai materi edukasi seputar pemilu dan pemilihan umum di Indonesia.
        </p>
    </div>

    <!-- Filter by Kategori (desktop) -->
    <div class="hidden md:flex flex-wrap gap-2 bg-white dark:bg-gray-800 rounded-lg shadow-md p-4 mb-6" x-data="{ kategoris: [] }" x-init="fetch('/api/kategori')
        .then(res => res.json())
        .then(data => kategoris = data.data)
        .catch(err => console.error('Error loading categories:', err))">
        <a href="{{ route('home') }}" class="px-4 py-2 rounded-full text-sm font-medium {{ request()->routeIs('home') && !request()->query('kategori_id') ? 'bg-primary-500 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
            Semua
        </a>
        <template x-for="kategori in kategoris" :key="kategori.id">
            <a :href="`/kategori/${kategori.slug}`"
               class="px-4 py-2 rounded-full text-sm font-medium bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-600"
               x-text="kategori.nama"></a>
        </template>
    </div>

    <!-- Materi Populer -->
    <div x-data="materiPopuler()" x-init="loadMateriPopuler()" class="space-y-6 mb-8">
        <div class="flex justify-between items-center">
            <div class="flex items-center">
                <h2 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">Materi Populer</h2>
                <a href="{{ route('home') }}" class="ml-3 inline-flex items-center text-sm text-primary-600 dark:text-primary-400 hover:underline">
                    Lihat Semua
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>
        </div>

        <!-- Loading Skeleton untuk Materi Populer -->
        <div x-show="loading" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <template x-for="i in 3">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden animate-pulse">
                    <div class="h-40 bg-gray-200 dark:bg-gray-700"></div>
                    <div class="p-4">
                        <div class="h-6 bg-gray-200 dark:bg-gray-700 rounded w-3/4 mb-2"></div>
                        <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-1/2 mb-4"></div>
                        <div class="flex justify-between">
                            <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-1/3"></div>
                            <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-1/4"></div>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        <!-- Konten Materi Populer -->
        <div x-show="!loading" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="materi-populer-list">
            <template x-for="materi in materiPopulerList" :key="materi.id">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden transition-all hover:shadow-lg border-t-4 border-red-500 kpu-hover-lift">
                    <div class="relative h-40 bg-gray-200 dark:bg-gray-700 overflow-hidden">
                        <template x-if="materi.gambar_url">
                            <img :src="materi.gambar_url" :alt="materi.judul || 'Gambar materi'" class="w-full h-full object-cover">
                        </template>
                        <div x-show="!materi.gambar_url" class="w-full h-full flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="absolute top-0 right-0 p-2">
                            <span class="bg-primary-500 text-white text-xs font-medium px-2.5 py-0.5 rounded-full" x-text="materi.kategori.nama"></span>
                        </div>
                    </div>
                    <div class="p-4">
                        <a :href="'/materi/' + materi.slug" class="block">
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2 line-clamp-2" x-text="materi.judul"></h3>
                            <p class="text-gray-600 dark:text-gray-400 line-clamp-2" x-text="materi.ringkasan || 'Baca selengkapnya...'"></p>
                            <div class="flex justify-between items-center mt-4">
                                <div class="flex items-center text-sm text-gray-500 dark:text-gray-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>
                                        <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span x-text="materi.views + ' views'"></span>
                                </div>
                                <div class="text-sm text-gray-500 dark:text-gray-400" x-text="formatDate(materi.created_at)"></div>
                            </div>
                        </a>
                    </div>
                </div>
            </template>
        </div>

        <!-- Empty State untuk Materi Populer -->
        <div x-show="!loading && materiPopulerList.length === 0" class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 text-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-gray-100">Belum ada materi populer</h3>
            <p class="mt-2 text-gray-600 dark:text-gray-400">Materi populer belum tersedia saat ini.</p>
        </div>
    </div>
@endsection
