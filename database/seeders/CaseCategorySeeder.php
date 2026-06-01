<?php

namespace Database\Seeders;

use App\Models\CaseCategory;
use Illuminate\Database\Seeder;

class CaseCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CaseCategory::factory(5)->create();
    }
}
