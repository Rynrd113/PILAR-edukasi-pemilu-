<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;

class MaterialView extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'materi_id',
        'view_date',
        'views_count',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'view_date' => 'date',
        'views_count' => 'integer',
    ];

    /**
     * Get the materi that owns this view record.
     */
    public function materi(): BelongsTo
    {
        return $this->belongsTo(Materi::class);
    }

    /**
     * Increment or create daily view count for a material
     */
    public static function recordView(int $materiId, ?string $date = null): void
    {
        $viewDate = $date ? $date : now()->toDateString();
        
        static::updateOrCreate(
            [
                'materi_id' => $materiId,
                'view_date' => $viewDate,
            ],
            []
        )->increment('views_count');
    }

    /**
     * Get daily views for the last N days
     */
    public static function getDailyViews(int $days = 7): array
    {
        $endDate = now()->toDateString();
        $startDate = now()->subDays($days - 1)->toDateString();
        
        $views = static::whereBetween('view_date', [$startDate, $endDate])
            ->selectRaw('view_date, SUM(views_count) as total_views')
            ->groupBy('view_date')
            ->orderBy('view_date')
            ->get()
            ->keyBy(function($item) {
                return $item->view_date instanceof \Carbon\Carbon 
                    ? $item->view_date->toDateString() 
                    : $item->view_date;
            });
        
        $result = [];
        for ($i = $days - 1; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $dateKey = $date->toDateString();
            $dateLabel = $date->format('d M');
            
            $result[] = [
                'date' => $dateLabel,
                'views' => (int) ($views->get($dateKey)?->total_views ?? 0)
            ];
        }
        
        return $result;
    }
}
