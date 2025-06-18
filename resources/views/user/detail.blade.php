@extends('user.layouts.app')

@section('title', 'Detail Materi - PILAR')

@section('content')
    <div x-data="materiDetail" x-init="loadMateri()">
        <!-- Loading Skeleton -->
        <div x-show="loading" class="animate-pulse">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-6">
                <div class="h-8 bg-gray-200 dark:bg-gray-700 rounded w-3/4 mb-4"></div>
                <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-1/4 mb-6"></div>
                <div class="h-60 bg-gray-200 dark:bg-gray-700 rounded mb-6"></div>
                <div class="space-y-3">
                    <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded"></div>
                    <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded"></div>
                    <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-5/6"></div>
                </div>
            </div>
        </div>

        <!-- Actual Content -->
        <div x-show="!loading">
            <!-- Breadcrumb Navigation -->
            <nav class="flex mb-4 text-gray-600 dark:text-gray-400">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('home') }}" class="hover:text-primary-600 dark:hover:text-primary-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                            Beranda
                        </a>
                    </li>
                    <li class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                        <a :href="`/kategori/${materi?.kategori?.slug}`" class="ml-1 hover:text-primary-600 dark:hover:text-primary-400" x-text="materi?.kategori?.nama"></a>
                    </li>
                    <li class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                        <span class="ml-1 font-medium truncate max-w-[150px] md:max-w-xs" x-text="materi?.judul"></span>
                    </li>
                </ol>
            </nav>

            <article class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden border-t-4 border-red-500">
                <!-- Featured Image -->
                <div x-show="materi.gambar_url" class="relative h-64 md:h-80 bg-gray-200 dark:bg-gray-700">
                    <img :src="materi.gambar_url" :alt="materi.judul" class="w-full h-full object-cover">
                </div>

                <!-- Content -->
                <div class="p-6">
                    <div class="flex items-center space-x-2 mb-4">
                        <span class="bg-primary-500 text-white text-xs font-medium px-2.5 py-0.5 rounded-full" x-text="materi?.kategori?.nama"></span>
                        <span class="text-gray-600 dark:text-gray-400 text-sm">
                            <span x-text="formatDate(materi?.created_at)"></span>
                        </span>
                    </div>

                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-4" x-text="materi.judul"></h1>

                    <div class="flex items-center mb-6 text-sm text-gray-600 dark:text-gray-400">
                        <div class="flex items-center mr-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                            </svg>
                            <span x-text="`${materi.views} views`"></span>
                        </div>
                    </div>

                    <!-- Main Content -->
                    <div class="prose dark:prose-invert max-w-none" x-html="materi.konten"></div>

                    <!-- Tags -->
                    <div x-show="materi.tags && materi.tags.length > 0" class="flex flex-wrap gap-2 mt-8">
                        <template x-for="tag in materi.tags" :key="tag">
                            <span class="bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 text-xs font-medium px-2.5 py-0.5 rounded" x-text="tag"></span>
                        </template>
                    </div>
                </div>
            </article>

            <!-- Related Content -->
            <div class="mt-10">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Materi Terkait</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" x-show="relatedMateris.length > 0">
                    <template x-for="related in relatedMateris" :key="related.id">
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden transition-all hover:shadow-lg border-t-4 border-red-500 kpu-hover-lift">
                            <div class="relative h-40 bg-gray-200 dark:bg-gray-700 overflow-hidden">
                                <img x-show="related.gambar_url" :src="related.gambar_url" :alt="related.judul" class="w-full h-full object-cover">
                                <div x-show="!related.gambar_url" class="w-full h-full flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="absolute top-0 right-0 p-2">
                                    <span class="bg-primary-500 text-white text-xs font-medium px-2.5 py-0.5 rounded-full" x-text="related.kategori.nama"></span>
                                </div>
                            </div>
                            <div class="p-4">
                                <a :href="`/materi/${related.slug}`" class="block">
                                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2 line-clamp-2" x-text="related.judul"></h3>
                                    <p class="text-gray-600 dark:text-gray-400 line-clamp-2" x-text="related.ringkasan || 'Baca selengkapnya...'"></p>
                                </a>
                            </div>
                        </div>
                    </template>
                </div>
                <div class="text-center py-8" x-show="relatedMateris.length === 0">
                    <p class="text-gray-600 dark:text-gray-400">Belum ada materi terkait.</p>
                </div>
            </div>
        </div>

        <!-- Error State -->
        <div x-show="error" class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 text-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-16 w-16 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <h3 class="mt-4 text-xl font-medium text-gray-900 dark:text-gray-100">Materi tidak ditemukan</h3>
            <p class="mt-2 mb-6 text-gray-600 dark:text-gray-400">Materi yang Anda cari tidak tersedia atau telah dihapus.</p>
            <a href="{{ route('home') }}" class="inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-700 active:bg-primary-800 focus:outline-none focus:border-primary-800 focus:ring focus:ring-primary-200 transition ease-in-out duration-150">
                Kembali ke Beranda
            </a>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('materiDetail', () => ({
            materi: {},
            relatedMateris: [],
            loading: true,
            error: false,
            slug: '{{ $slug }}',

            loadMateri() {
                this.loading = true;

                fetch(`/api/materi/${this.slug}`)
                    .then(res => {
                        if (!res.ok) throw new Error('Materi not found');
                        return res.json();
                    })
                    .then(data => {
                        this.materi = data.data;
                        this.loading = false;

                        // Load related materis after we get the current materi
                        if (this.materi.kategori && this.materi.kategori.id) {
                            this.loadRelatedMateris(this.materi.kategori.id, this.materi.id);
                        }
                    })
                    .catch(err => {
                        console.error('Error loading materi:', err);
                        this.loading = false;
                        this.error = true;
                    });
            },

            loadRelatedMateris(kategoriId, currentMateriId) {
                fetch(`/api/materi?kategori_id=${kategoriId}&limit=3`)
                    .then(res => res.json())
                    .then(data => {
                        // Filter out the current materi
                        this.relatedMateris = data.data
                            .filter(item => item.id !== currentMateriId)
                            .slice(0, 3); // Take max 3
                    })
                    .catch(err => {
                        console.error('Error loading related materis:', err);
                    });
            },

            formatDate(dateString) {
                const options = { year: 'numeric', month: 'long', day: 'numeric' };
                return new Date(dateString).toLocaleDateString('id-ID', options);
            }
        }));
    });
</script>
@endpush
