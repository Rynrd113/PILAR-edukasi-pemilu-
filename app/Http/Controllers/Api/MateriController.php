<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Materi;
use App\Models\Kategori;
use Illuminate\Http\Request;

class MateriController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $query = Materi::with('kategori')->where('is_published', true);

            // Filter by kategori_id if provided
            if ($request->has('kategori_id')) {
                $query->where('kategori_id', $request->kategori_id);
            }

            // Limit results if specified
            if ($request->has('limit')) {
                $query->limit($request->limit);
            }

            // Order by latest
            $query->latest();

            $materis = $query->get();

            return response()->json([
                'status' => 'success',
                'data' => \App\Http\Resources\MateriResource::collection($materis)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch materis',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($slug)
    {
        try {
            $materi = Materi::with('kategori')
                ->where('slug', $slug)
                ->where('is_published', true)
                ->firstOrFail();

            // Increment view count using the new method
            $materi->incrementViews();

            return response()->json([
                'status' => 'success',
                'data' => new \App\Http\Resources\MateriResource($materi)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Materi not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Get materis by kategori.
     *
     * @param  \App\Models\Kategori|string  $kategori
     * @return \Illuminate\Http\JsonResponse
     */
    public function byKategori($kategori)
    {
        try {
            if (is_numeric($kategori)) {
                $kategoriModel = Kategori::findOrFail($kategori);
            } else {
                $kategoriModel = Kategori::where('slug', $kategori)->firstOrFail();
            }

            $materis = Materi::with('kategori')
                ->where('kategori_id', $kategoriModel->id)
                ->where('is_published', true)
                ->latest()
                ->get();

            return response()->json([
                'status' => 'success',
                'data' => [
                    'kategori' => $kategoriModel,
                    'materis' => $materis
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch materis by category',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:materis',
            'konten' => 'required|string',
            'gambar' => 'nullable|string',
            'ringkasan' => 'nullable|string',
            'kategori_id' => 'required|exists:kategoris,id',
            'is_published' => 'boolean',
        ]);

        try {
            $materi = Materi::create($request->all());

            return response()->json([
                'status' => 'success',
                'message' => 'Materi created successfully',
                'data' => $materi
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create materi',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Materi  $materi
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Materi $materi)
    {
        $request->validate([
            'judul' => 'sometimes|required|string|max:255',
            'slug' => 'sometimes|required|string|max:255|unique:materis,slug,' . $materi->id,
            'konten' => 'sometimes|required|string',
            'gambar' => 'nullable|string',
            'ringkasan' => 'nullable|string',
            'kategori_id' => 'sometimes|required|exists:kategoris,id',
            'is_published' => 'boolean',
        ]);

        try {
            $materi->update($request->all());

            return response()->json([
                'status' => 'success',
                'message' => 'Materi updated successfully',
                'data' => $materi
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update materi',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Materi  $materi
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Materi $materi)
    {
        try {
            $materi->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Materi deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete materi',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
