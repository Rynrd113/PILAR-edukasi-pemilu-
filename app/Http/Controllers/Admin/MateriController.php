<?php

namespace App\Http\Controllers\Admin;

use App\Models\Materi;
use App\Models\Kategori;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Materi\StoreMateriRequest;
use App\Http\Requests\Materi\UpdateMateriRequest;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class MateriController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $materis = Materi::query()
            ->with('kategori') // Eager load the kategori relationship
            ->when($request->search, function($query, $search) {
                $query->where('judul', 'like', "%{$search}%")
                    ->orWhere('konten', 'like', "%{$search}%");
            })
            ->when($request->kategori_id, function($query, $kategoriId) {
                $query->where('kategori_id', $kategoriId);
            })
            ->when($request->status, function($query, $status) {
                if ($status === 'published') {
                    $query->where('is_published', true);
                } elseif ($status === 'draft') {
                    $query->where('is_published', false);
                }
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $kategoris = Kategori::where('is_active', true)->get();

        return view('admin.materi.index', compact('materis', 'kategoris'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $kategoris = Kategori::where('is_active', true)->get();

        return view('admin.materi.create', compact('kategoris'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMateriRequest $request): RedirectResponse
    {
        $validatedData = $request->validated();

        // Handle image upload
        if ($request->hasFile('gambar')) {
            $path = $request->file('gambar')->store('materis', 'public');
            $validatedData['gambar_path'] = $path;
        }

        // Set publication date if published
        if (!empty($validatedData['is_published']) && $validatedData['is_published']) {
            $validatedData['published_at'] = $validatedData['published_at'] ?? now();
        }

        $materi = Materi::create($validatedData);

        // Clear cache
        Cache::forget('materis.published');
        Cache::forget("materis.by_kategori.{$materi->kategori_id}");

        return redirect()
            ->route('admin.materi.index')
            ->with('success', 'Materi berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Materi $materi): View
    {
        $kategoris = Kategori::where('is_active', true)->get();

        return view('admin.materi.edit', compact('materi', 'kategoris'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMateriRequest $request, Materi $materi): RedirectResponse
    {
        $validatedData = $request->validated();

        // Handle image upload
        if ($request->hasFile('gambar')) {
            // Delete old image if exists
            if ($materi->gambar_path) {
                Storage::disk('public')->delete($materi->gambar_path);
            }

            $path = $request->file('gambar')->store('materis', 'public');
            $validatedData['gambar_path'] = $path;
        }

        // Set publication date if published
        if (isset($validatedData['is_published']) && $validatedData['is_published'] && !$materi->published_at) {
            $validatedData['published_at'] = $validatedData['published_at'] ?? now();
        }

        $oldKategoriId = $materi->kategori_id;

        $materi->update($validatedData);

        // Clear cache
        Cache::forget('materis.published');
        Cache::forget("materis.by_kategori.{$oldKategoriId}");

        if ($oldKategoriId != $materi->kategori_id) {
            Cache::forget("materis.by_kategori.{$materi->kategori_id}");
        }

        return redirect()
            ->route('admin.materi.index')
            ->with('success', 'Materi berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Materi $materi): RedirectResponse
    {
        // Delete image if exists
        if ($materi->gambar_path) {
            Storage::disk('public')->delete($materi->gambar_path);
        }

        $kategoriId = $materi->kategori_id;

        $materi->delete();

        // Clear cache
        Cache::forget('materis.published');
        Cache::forget("materis.by_kategori.{$kategoriId}");

        return redirect()
            ->route('admin.materi.index')
            ->with('success', 'Materi berhasil dihapus.');
    }

    /**
     * Toggle the published status of the specified resource.
     */
    public function togglePublish(Materi $materi): RedirectResponse
    {
        $publishedStatus = !$materi->is_published;

        $materi->update([
            'is_published' => $publishedStatus,
            'published_at' => $publishedStatus && !$materi->published_at ? now() : $materi->published_at
        ]);

        // Clear cache
        Cache::forget('materis.published');
        Cache::forget("materis.by_kategori.{$materi->kategori_id}");

        $status = $materi->is_published ? 'dipublikasikan' : 'draft';

        return redirect()
            ->route('admin.materi.index')
            ->with('success', "Materi berhasil diubah menjadi {$status}.");
    }

    /**
     * Reset view count for the specified resource.
     */
    public function resetViews(Materi $materi): RedirectResponse
    {
        $materi->update([
            'views' => 0
        ]);

        return redirect()
            ->route('admin.materi.index')
            ->with('success', 'Jumlah tampilan materi berhasil direset.');
    }
}
