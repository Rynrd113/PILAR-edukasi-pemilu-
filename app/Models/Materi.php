<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Materi extends Model
{
    use HasFactory;

    /**
     * Atribut yang dapat diisi secara mass assignment.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'kategori_id',
        'judul',
        'slug',
        'konten',
        'gambar_path',
        'is_published',
        'published_at',
        'views',
    ];

    /**
     * Atribut yang harus di-cast ke tipe data tertentu.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime',
        'views' => 'integer',
    ];

    /**
     * Mendapatkan kategori yang memiliki materi ini.
     */
    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class);
    }

    /**
     * Boot model untuk mengatur event listener.
     */
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($materi) {
            if (empty($materi->slug)) {
                $materi->slug = Str::slug($materi->judul);
            }

            if ($materi->is_published && empty($materi->published_at)) {
                $materi->published_at = now();
            }
        });

        static::updating(function ($materi) {
            if ($materi->isDirty('judul') && empty($materi->slug)) {
                $materi->slug = Str::slug($materi->judul);
            }

            if ($materi->isDirty('is_published') && $materi->is_published && empty($materi->published_at)) {
                $materi->published_at = now();
            }
        });
    }

    /**
     * Scope query untuk hanya menampilkan materi yang dipublikasikan.
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true)->whereNotNull('published_at');
    }

    /**
     * Menambah jumlah views
     */
    public function incrementViews(): void
    {
        $this->timestamps = false;
        $this->increment('views');
        $this->timestamps = true;
        
        // Mencatat view harian
        \App\Models\MaterialView::recordView($this->id);
    }

    /**
     * Mendapatkan relasi material views
     */
    public function materialViews()
    {
        return $this->hasMany(\App\Models\MaterialView::class);
    }
}
