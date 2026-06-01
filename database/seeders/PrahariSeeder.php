<?php

namespace Database\Seeders;

use App\Models\Prahari;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PrahariSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Prahari::factory(5)->create();
    }
}
