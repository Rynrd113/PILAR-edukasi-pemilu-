<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use League\CommonMark\CommonMarkConverter;

class MateriResource extends JsonResource
{
    /**
     * CommonMark converter instance
     */
    private static $converter;

    /**
     * Get CommonMark converter instance
     */
    private function getConverter()
    {
        if (!static::$converter) {
            static::$converter = new CommonMarkConverter([
                'html_input' => 'strip',
                'allow_unsafe_links' => false,
            ]);
        }
        
        return static::$converter;
    }
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $converter = $this->getConverter();

        return [
            'id' => $this->id,
            'kategori_id' => $this->kategori_id,
            'kategori' => new KategoriResource($this->whenLoaded('kategori')),
            'judul' => $this->judul,
            'slug' => $this->slug,
            'konten' => $this->when($request->routeIs('api.materi.show'), 
                $converter->convert($this->konten)->getContent()
            ),
            'konten_excerpt' => $this->when(!$request->routeIs('api.materi.show'),
                \Str::limit(strip_tags($converter->convert($this->konten)->getContent()), 100)
            ),
            'gambar_url' => $this->gambar_path ? url('storage/' . $this->gambar_path) : null,
            'is_published' => (bool) $this->is_published,
            'published_at' => $this->published_at?->format('Y-m-d H:i:s'),
            'views' => (int) $this->views,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
