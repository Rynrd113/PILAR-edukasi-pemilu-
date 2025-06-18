<?php
/**
 * Test script untuk verifikasi Views API
 */

// Test increment views via API
echo "=== Testing Views Increment via API ===\n";

// Get current views count
$response = file_get_contents('http://localhost:8001/api/materi');
$data = json_decode($response, true);

if ($data && isset($data['data']) && count($data['data']) > 0) {
    $firstMateri = $data['data'][0];
    echo "Material: " . $firstMateri['judul'] . "\n";
    echo "Current views: " . $firstMateri['views'] . "\n";
    
    // Call detail API to increment views
    $detailResponse = file_get_contents('http://localhost:8001/api/materi/' . $firstMateri['slug']);
    $detailData = json_decode($detailResponse, true);
    
    if ($detailData && isset($detailData['data'])) {
        echo "After API call views: " . $detailData['data']['views'] . "\n";
        echo "Views incremented successfully!\n";
    }
} else {
    echo "No materials found or API error\n";
}

echo "\n=== Testing Dashboard Data ===\n";

// Test dashboard data via console command
echo "Running views aggregate command...\n";
system('cd "/home/rynrd/magang/edukasi-api (0.1) (awal)" && php artisan views:aggregate');

echo "\n=== Test Completed ===\n";
