<?php

namespace App\Http\Controllers\Admin;

use App\Models\Kategori;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Kategori\StoreKategoriRequest;
use App\Http\Requests\Kategori\UpdateKategoriRequest;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class KategoriController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $kategoris = Kategori::query()
            ->when($request->search, function($query, $search) {
                $query->where('nama', 'like', "%{$search}%")
                    ->orWhere('deskripsi', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.kategori.index', compact('kategoris'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.kategori.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreKategoriRequest $request): RedirectResponse
    {
        $validatedData = $request->validated();

        $kategori = Kategori::create($validatedData);

        // Clear cache
        Cache::forget('kategoris.all');

        return redirect()
            ->route('admin.kategori.index')
            ->with('success', 'Kategori berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kategori $kategori): View
    {
        return view('admin.kategori.edit', compact('kategori'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateKategoriRequest $request, Kategori $kategori): RedirectResponse
    {
        $validatedData = $request->validated();

        $kategori->update($validatedData);

        // Clear cache
        Cache::forget('kategoris.all');

        return redirect()
            ->route('admin.kategori.index')
            ->with('success', 'Kategori berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kategori $kategori): RedirectResponse
    {
        try {
            $kategori->delete();

            // Clear cache
            Cache::forget('kategoris.all');

            return redirect()
                ->route('admin.kategori.index')
                ->with('success', 'Kategori berhasil dihapus.');

        } catch (\Exception $e) {
            return redirect()
                ->route('admin.kategori.index')
                ->with('error', 'Gagal menghapus kategori. Kategori ini mungkin sedang digunakan.');
        }
    }

    /**
     * Toggle the active status of the specified resource.
     */
    public function toggleStatus(Kategori $kategori): RedirectResponse
    {
        $kategori->update([
            'is_active' => !$kategori->is_active
        ]);

        // Clear cache
        Cache::forget('kategoris.all');

        $status = $kategori->is_active ? 'aktif' : 'nonaktif';

        return redirect()
            ->route('admin.kategori.index')
            ->with('success', "Kategori berhasil diubah menjadi {$status}.");
    }
}
