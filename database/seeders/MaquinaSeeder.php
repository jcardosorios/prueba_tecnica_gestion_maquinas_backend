<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MaquinaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i =1; $i <= 5; $i++) {
            DB::table('maquinas')->insert([
                'nombre' => 'Maquina' . $i,
                'coeficiente' => rand(100,300)/100,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
