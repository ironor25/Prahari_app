<?php

namespace Database\Seeders;

use App\Models\Cases;
use App\Models\Prahari;
use App\Models\CaseCategory;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class CasesSeeder extends Seeder
{
    public function run(): void
    {
        $prahari1 = Prahari::where('mobile', '9876543210')->first();
        $prahari2 = Prahari::where('mobile', '9876543211')->first();
        $cat1 = CaseCategory::where('case_category_name', 'No Helmet')->first();
        $cat2 = CaseCategory::where('case_category_name', 'Overspeeding')->first();

        if($prahari1 && $cat1) {
            Cases::firstOrCreate(
                ['vehicle_number' => 'MH12AB1234'],
                [
                    'prahari_id' => $prahari1->id,
                    'case_category_id' => $cat1->id,
                    'location' => 'Main Street, City Center',
                    'evidence' => 'evidence1.jpg',
                    'status' => 'Approved',
                    'created_at' => Carbon::now()->subDays(2)
                ]
            );
        }

        if($prahari2 && $cat2) {
            Cases::firstOrCreate(
                ['vehicle_number' => 'MH14CD5678'],
                [
                    'prahari_id' => $prahari2->id,
                    'case_category_id' => $cat2->id,
                    'location' => 'Highway 45',
                    'evidence' => 'evidence2.jpg',
                    'status' => 'Approved',
                    'created_at' => Carbon::now()->subDays(1)
                ]
            );
        }
    }
}
