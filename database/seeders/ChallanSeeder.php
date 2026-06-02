<?php

namespace Database\Seeders;

use App\Models\Challan;
use App\Models\Cases;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ChallanSeeder extends Seeder
{
    public function run(): void
    {
        $cases = Cases::with('caseCategory', 'prahari')->get();

        foreach($cases as $case) {
            Challan::firstOrCreate(
                ['case_id' => $case->id],
                [
                    'prahari_id' => $case->prahari_id,
                    'vehicle_number' => $case->vehicle_number,
                    'category_id' => $case->case_category_id,
                    'fine_amount' => $case->caseCategory ? $case->caseCategory->fine_amount : 500,
                    'status' => 'paid',
                    'created_at' => $case->created_at->addHours(1)
                ]
            );
        }
    }
}
