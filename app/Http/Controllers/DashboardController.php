<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Materi;
use App\Models\MaterialView;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard with statistics.
     */
    public function index(): View
    {
        // Cache statistics for 5 minutes to improve dashboard performance
        $stats = Cache::remember('admin.dashboard.stats', 300, function () {
            return [
                'total_kategoris' => Kategori::count(),
                'active_kategoris' => Kategori::where('is_active', true)->count(),
                'total_materis' => Materi::count(),
                'published_materis' => Materi::where('is_published', true)->count(),
                'total_views' => Materi::sum('views'),
                'users_count' => User::count(),
                'admin_count' => User::where('is_admin', true)->count(),
                'latest_materis' => Materi::with('kategori')
                    ->latest()
                    ->take(5)
                    ->get(),
                'most_viewed_materis' => Materi::with('kategori')
                    ->orderByDesc('views')
                    ->take(5)
                    ->get(),
                'latest_users' => User::latest()
                    ->take(5)
                    ->get(),
            ];
        });

        // Get chart data for the past 7 days
        $viewsData = Cache::remember('admin.dashboard.chart', 300, function () {
            // Get real daily views data from database
            return \App\Models\MaterialView::getDailyViews(7);
        });

        return view('admin.dashboard', [
            'stats' => $stats,
            'viewsChart' => $viewsData,
        ]);
    }
}
