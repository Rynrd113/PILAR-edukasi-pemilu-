<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            $kategoris = Kategori::where('is_active', true)->get();

            return response()->json([
                'status' => 'success',
                'data' => $kategoris
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch categories',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Kategori|string  $kategori
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($kategori)
    {
        try {
            if (is_numeric($kategori)) {
                $kategoriData = Kategori::findOrFail($kategori);
            } else {
                $kategoriData = Kategori::where('slug', $kategori)->firstOrFail();
            }

            return response()->json([
                'status' => 'success',
                'data' => $kategoriData
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Category not found',
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
            'nama' => 'required|string|max:255|unique:kategoris',
            'slug' => 'required|string|max:255|unique:kategoris',
            'deskripsi' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        try {
            $kategori = Kategori::create($request->all());

            return response()->json([
                'status' => 'success',
                'message' => 'Category created successfully',
                'data' => $kategori
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create category',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Kategori  $kategori
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Kategori $kategori)
    {
        $request->validate([
            'nama' => 'sometimes|required|string|max:255|unique:kategoris,nama,' . $kategori->id,
            'slug' => 'sometimes|required|string|max:255|unique:kategoris,slug,' . $kategori->id,
            'deskripsi' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        try {
            $kategori->update($request->all());

            return response()->json([
                'status' => 'success',
                'message' => 'Category updated successfully',
                'data' => $kategori
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update category',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Kategori  $kategori
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Kategori $kategori)
    {
        try {
            $kategori->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Category deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete category',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
