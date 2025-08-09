<?php

namespace Database\Seeders;

use App\Models\Maquina;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Maquina::factory(5)->create();
    }
}
