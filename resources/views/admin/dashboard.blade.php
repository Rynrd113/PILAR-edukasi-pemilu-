@extends('admin.layouts.app')

@section('title', 'Dasbor')

@section('content')
    <h2 class="text-2xl font-semibold text-gray-700">Dasbor</h2>
    <p class="text-gray-600 mb-6">Selamat datang kembali, {{ auth()->user() ? auth()->user()->name : 'Admin' }}!</p>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-red-100 text-red-500">
                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-gray-500 text-sm">Total Kategori</p>
                    <p class="text-2xl font-semibold text-gray-700">{{ $stats['total_kategoris'] }}</p>
                    <p class="text-sm text-green-500">{{ $stats['active_kategoris'] }} aktif</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-500">
                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-gray-500 text-sm">Total Materi</p>
                    <p class="text-2xl font-semibold text-gray-700">{{ $stats['total_materis'] }}</p>
                    <p class="text-sm text-green-500">{{ $stats['published_materis'] }} dipublikasi</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-500">
                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-gray-500 text-sm">Total Dilihat</p>
                    <p class="text-2xl font-semibold text-gray-700">{{ $stats['total_views'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-indigo-100 text-indigo-500">
                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-gray-500 text-sm">Pengguna</p>
                    <p class="text-2xl font-semibold text-gray-700">{{ $stats['users_count'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Bagian Grafik dan Tabel -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        <!-- Grafik -->
        <div class="bg-white rounded-lg shadow-md p-6 lg:col-span-2">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Dilihat - 7 Hari Terakhir</h3>
            <div class="h-64">
                <canvas id="viewsChart"></canvas>
            </div>
        </div>

        <!-- User Terbaru -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">User Terbaru</h3>
            <div class="space-y-3">
                @forelse ($stats['latest_users'] as $user)
                    <div class="flex items-center">
                        <div class="h-8 w-8 rounded-full bg-red-100 flex items-center justify-center">
                            <span class="text-red-600 font-medium text-sm">{{ substr($user->name, 0, 1) }}</span>
                        </div>
                        <div class="ml-3 flex-1">
                            <div class="text-sm font-medium text-gray-900">{{ Str::limit($user->name, 20) }}</div>
                            <div class="text-xs text-gray-500">{{ $user->created_at->diffForHumans() }}</div>
                        </div>
                        <div class="ml-2">
                            <span class="px-2 py-1 text-xs rounded-full {{ $user->is_admin ? 'bg-red-100 text-red-600' : 'bg-green-100 text-green-600' }}">
                                {{ $user->is_admin ? 'Admin' : 'User' }}
                            </span>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 text-sm">Tidak ada user ditemukan</p>
                @endforelse
            </div>
            <div class="mt-4">
                <a href="{{ route('admin.users.index') }}" class="text-red-600 hover:text-red-700 text-sm font-medium">
                    Lihat Semua User →
                </a>
            </div>
        </div>
    </div>

    <!-- Tabel Materi -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Materi Terbaru -->
        <div class="bg-white rounded-lg shadow-md p-6 lg:col-span-2">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Materi Terbaru</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr>
                            <th class="px-4 py-3 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Judul</th>
                            <th class="px-4 py-3 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Kategori</th>
                            <th class="px-4 py-3 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                            <th class="px-4 py-3 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Dilihat</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($stats['latest_materis'] as $materi)
                            <tr>
                                <td class="px-4 py-3">
                                    <div class="flex items-center">
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ Str::limit($materi->judul, 30) }}
                                            </div>
                                            <div class="text-xs text-gray-500">
                                                {{ $materi->created_at->format('d M, Y') }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="text-sm text-gray-900">
                                        {{ $materi->kategori->nama }}
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $materi->is_published ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ $materi->is_published ? 'Dipublikasi' : 'Draft' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="text-sm text-gray-900">
                                        {{ $materi->views }}
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-3 text-center text-gray-500">
                                    Tidak ada materi ditemukan
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Materi Paling Banyak Dilihat -->
        <div class="bg-white rounded-lg shadow-md p-6 lg:col-span-2">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Materi Paling Banyak Dilihat</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr>
                            <th class="px-4 py-3 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Judul</th>
                            <th class="px-4 py-3 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Kategori</th>
                            <th class="px-4 py-3 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                            <th class="px-4 py-3 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Dilihat</th>
                            <th class="px-4 py-3 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($stats['most_viewed_materis'] as $materi)
                            <tr>
                                <td class="px-4 py-3">
                                    <div class="flex items-center">
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ Str::limit($materi->judul, 30) }}
                                            </div>
                                            <div class="text-xs text-gray-500">
                                                Dipublikasi: {{ $materi->published_at ? $materi->published_at->format('d M, Y') : 'Draft' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="text-sm text-gray-900">
                                        {{ $materi->kategori->nama }}
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $materi->is_published ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ $materi->is_published ? 'Dipublikasi' : 'Draft' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $materi->views }}
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-sm font-medium">
                                    <a href="{{ route('admin.materi.edit', $materi) }}" class="text-red-600 hover:text-red-900">Edit</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-3 text-center text-gray-500">
                                    Tidak ada materi ditemukan
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Data dasbor untuk JavaScript
    window.dashboardData = {
        viewsChart: {!! json_encode($viewsChart) !!}
    };
    // Catatan: Inisialisasi grafik sekarang ditangani di admin.js
</script>
@endpush
