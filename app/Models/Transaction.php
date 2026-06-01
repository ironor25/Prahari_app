<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'withdrawal_id',
        'prahari_id',
        'amount',
        'bank_account',
        'status',
    ];

    /**
     * The "booted" method of the model.
     */
    protected static function booted()
    {
        static::creating(function ($transaction) {
            // Get the last transaction that has a withdrawal_id
            $lastTransaction = self::whereNotNull('withdrawal_id')
                ->latest('id')
                ->first();

            if (!$lastTransaction) {
                // If no transactions exist, start with W0001
                $number = 1;
            } else {
                // Extract the number from 'W0001' (start at index 1) and increment
                $number = (int) substr($lastTransaction->withdrawal_id, 1) + 1;
            }

            // Format the number to be 4 digits with leading zeros (e.g., W0001)
            $transaction->withdrawal_id = 'W' . str_pad($number, 4, '0', STR_PAD_LEFT);
        });
    }

    public function prahari()
    {
        return $this->belongsTo(Prahari::class);
    }
}
