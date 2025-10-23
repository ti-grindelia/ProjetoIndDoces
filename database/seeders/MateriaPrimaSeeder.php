<?php

namespace Database\Seeders;

use App\Models\MateriaPrima;
use Illuminate\Database\Seeder;

class MateriaPrimaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MateriaPrima::factory(50)->create();
    }
}
