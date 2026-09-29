<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        if (!Schema::hasTable('roles')) return;

        foreach (['admin', 'editor', 'viewer'] as $name) {
            Role::firstOrCreate(['name' => $name]);
        }
    }
}