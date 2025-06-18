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
     * The attributes that are mass assignable.
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
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime',
        'views' => 'integer',
    ];

    /**
     * Get the kategori that owns the materi.
     */
    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class);
    }

    /**
     * Boot the model.
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
     * Scope a query to only include published materials.
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true)->whereNotNull('published_at');
    }

    /**
     * Increment view count
     */
    public function incrementViews(): void
    {
        $this->timestamps = false;
        $this->increment('views');
        $this->timestamps = true;
        
        // Record daily view
        \App\Models\MaterialView::recordView($this->id);
    }

    /**
     * Get material views relationship
     */
    public function materialViews()
    {
        return $this->hasMany(\App\Models\MaterialView::class);
    }
}
