<?php

namespace Database\Seeders;

use App\Models\Platform;
use Illuminate\Database\Seeder;

class PlatformSeeder extends Seeder
{
    public function run(): void
    {
        $platforms = [
            ['name' => 'Twitter Account', 'type' => 'social'],
            ['name' => 'Instagram Page', 'type' => 'social'],
            ['name' => 'LinkedIn Profile', 'type' => 'social'],
            ['name' => 'Company Blog', 'type' => 'blog'],
        ];

        foreach ($platforms as $platform) {
            Platform::firstOrCreate(['name' => $platform['name']], $platform);
        }
    }
}