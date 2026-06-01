<?php

namespace Database\Seeders;

use App\Models\Challan;
use Illuminate\Database\Seeder;

class ChallanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Challan::factory(5)->create();
    }
}
