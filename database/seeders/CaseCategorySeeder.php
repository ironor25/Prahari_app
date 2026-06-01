<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CaseCategorySeeder extends Seeder
{
    public function run(): void
    {
        if (DB::table('case_categories')->count() === 0) {
            DB::table('case_categories')->insert([
                [
                    'case_category_name' => 'No Helmet',
                    'fine_amount' => 500,
                ],
                [
                    'case_category_name' => 'Drunk and Drive',
                    'fine_amount' => 1000,
                ],
                [
                    'case_category_name' => 'Overspeeding',
                    'fine_amount' => 700,
                ],
            ]);
        }
    }
}