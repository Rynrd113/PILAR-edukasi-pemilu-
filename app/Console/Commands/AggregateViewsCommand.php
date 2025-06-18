<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\MaterialView;
use Carbon\Carbon;

class AggregateViewsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'views:aggregate {--days=7 : Number of days to aggregate}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Aggregate views data for dashboard charts';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $days = $this->option('days');
        
        $this->info("Aggregating views data for the last {$days} days...");
        
        $viewsData = MaterialView::getDailyViews($days);
        
        $this->table(
            ['Date', 'Total Views'],
            collect($viewsData)->map(function ($item) {
                return [$item['date'], $item['views']];
            })->toArray()
        );
        
        $totalViews = collect($viewsData)->sum('views');
        $this->info("Total views in the last {$days} days: {$totalViews}");
        
        return 0;
    }
}
