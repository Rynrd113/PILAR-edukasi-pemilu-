@extends('user.layouts.app')

@section('title', isset($slug) ? 'Kategori ' . ucfirst($slug) : 'Semua Kategori - PILAR')

@section('content')
    <!-- Kategori Header -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-6">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2" x-data="{ title: 'Semua Kategori' }" x-init="
            if ('{{ isset($slug) }}') {
                fetch('/api/kategori')
                    .then(res => res.json())
                    .then(data => {
                        const kategori = data.data.find(k => k.slug === '{{ $slug ?? '' }}');
                        if (kategori) title = 'Kategori ' + kategori.nama;
                    })
                    .catch(err => console.error('Error:', err));
            }
        " x-text="title"></h1>
        <p class="text-lg text-gray-600 dark:text-gray-400">
            Materi edukasi pemilu berdasarkan kategori yang dipilih.
        </p>
    </div>

    <!-- Kategori Filters -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-4 mb-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Pilih Kategori</h3>
        <div class="flex flex-wrap gap-2" x-data="{ kategoris: [] }" x-init="fetch('/api/kategori')
            .then(res => res.json())
            .then(data => kategoris = data.data)
            .catch(err => console.error('Error loading categories:', err))">
            <a href="{{ route('kategori') }}" class="px-4 py-2 rounded-full text-sm font-medium {{ !isset($slug) ? 'bg-primary-500 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                Semua
            </a>
            <template x-for="kategori in kategoris" :key="kategori.id">
                <a :href="`/kategori/${kategori.slug}`"
                   class="px-4 py-2 rounded-full text-sm font-medium"
                   :class="kategori.slug === '{{ $slug ?? '' }}' ? 'bg-primary-500 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-600'"
                   x-text="kategori.nama"></a>
            </template>
        </div>
    </div>

    <!-- Materi List by Kategori -->
    <div x-data="materis" x-init="loadMateris()" class="space-y-6">
        <!-- Loading Skeleton -->
        <div x-show="loading" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <template x-for="i in 6">
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

        <!-- Actual Content -->
        <div x-show="!loading" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="materi-list">
            <template x-for="materi in materis" :key="materi.id">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden transition-all hover:shadow-lg border-t-4 border-red-500 kpu-hover-lift">
                    <div class="relative h-40 bg-gray-200 dark:bg-gray-700 overflow-hidden">
                        <img x-show="materi.gambar_url" :src="materi.gambar_url" :alt="materi.judul" class="w-full h-full object-cover">
                        <div x-show="!materi.gambar_url" class="w-full h-full flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="absolute top-0 right-0 p-2">
                            <span class="bg-primary-500 text-white text-xs font-medium px-2.5 py-0.5 rounded-full" x-text="materi.kategori.nama"></span>
                        </div>
                    </div>
                    <div class="p-4">
                        <a :href="`/materi/${materi.slug}`" class="block">
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2 line-clamp-2" x-text="materi.judul"></h3>
                            <p class="text-gray-600 dark:text-gray-400 line-clamp-2" x-text="materi.ringkasan || 'Baca selengkapnya...'"></p>
                            <div class="flex justify-between items-center mt-4">
                                <div class="flex items-center text-sm text-gray-500 dark:text-gray-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                        <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
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

        <!-- Empty State -->
        <div x-show="!loading && materis.length === 0" class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 text-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-gray-100">Tidak ada materi</h3>
            <p class="mt-2 text-gray-600 dark:text-gray-400">Belum ada materi dalam kategori ini.</p>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('materis', () => ({
            materis: [],
            loading: true,
            slug: '{{ $slug ?? "" }}',

            loadMateris() {
                this.loading = true;

                let endpoint = '/api/materi';
                if (this.slug) {
                    // First, find kategori_id by slug
                    fetch('/api/kategori')
                        .then(res => res.json())
                        .then(kategorisData => {
                            const kategori = kategorisData.data.find(k => k.slug === this.slug);
                            if (kategori) {
                                // Then fetch materi by kategori_id
                                fetch(`/api/materi?kategori_id=${kategori.id}`)
                                    .then(res => res.json())
                                    .then(data => {
                                        this.materis = data.data;
                                        this.loading = false;
                                    })
                                    .catch(err => {
                                        console.error('Error loading materis:', err);
                                        this.loading = false;
                                    });
                            } else {
                                this.loading = false;
                                this.materis = [];
                            }
                        })
                        .catch(err => {
                            console.error('Error loading categories:', err);
                            this.loading = false;
                        });
                } else {
                    // Fetch all materis
                    fetch(endpoint)
                        .then(res => res.json())
                        .then(data => {
                            this.materis = data.data;
                            this.loading = false;
                        })
                        .catch(err => {
                            console.error('Error loading materis:', err);
                            this.loading = false;
                        });
                }
            },

            formatDate(dateString) {
                const options = { year: 'numeric', month: 'short', day: 'numeric' };
                return new Date(dateString).toLocaleDateString('id-ID', options);
            }
        }));
    });
</script>
@endpush
