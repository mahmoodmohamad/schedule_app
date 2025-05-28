<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlatformSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('platforms')->insert(
            [
                [
                'name' => 'Twitter Account',
                'type' => 'twitter',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Instagram Page',
                'type' => 'instagram',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'LinkedIn Profile',
                'type' => 'linkedin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            ]
        );
    }
}
