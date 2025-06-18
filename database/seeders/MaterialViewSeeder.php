<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MaterialView;
use App\Models\Materi;
use Carbon\Carbon;

class MaterialViewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all published materials
        $materis = Materi::where('is_published', true)->get();
        
        if ($materis->isEmpty()) {
            $this->command->info('No published materials found. Skipping MaterialView seeder.');
            return;
        }

        // Generate view data for the last 7 days (current week)
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            
            foreach ($materis as $materi) {
                // Random views between 5-50 per day per material
                $views = rand(5, 50);
                
                MaterialView::create([
                    'materi_id' => $materi->id,
                    'view_date' => $date->toDateString(),
                    'views_count' => $views,
                ]);
            }
        }
        
        $this->command->info('MaterialView seeder completed successfully.');
    }
}
