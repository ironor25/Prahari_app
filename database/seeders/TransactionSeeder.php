<?php

namespace Database\Seeders;

use App\Models\Transaction;
use App\Models\Prahari;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class TransactionSeeder extends Seeder
{
    public function run(): void
    {
        $prahari1 = Prahari::where('mobile', '9876543210')->first();
        
        if($prahari1 && Transaction::count() === 0) {
            Transaction::create([
                'prahari_id' => $prahari1->id,
                'amount' => 500,
                'bank_account' => $prahari1->bank_account,
                'status' => 'Approved',
                'created_at' => Carbon::now()->subDays(5)
            ]);

            Transaction::create([
                'prahari_id' => $prahari1->id,
                'amount' => 200,
                'bank_account' => $prahari1->bank_account,
                'status' => 'Open',
                'created_at' => Carbon::now()->subDay()
            ]);
        }
    }
}
