<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Prahari extends Model
{
    use HasFactory;
    //
    protected $fillable = [
        'user_id',
        'prahari_id',
        'name',
        'mobile',
        'bank_account',
        'wallet_balance',
        'aadhaar_status',
        'status',
    ];

    /**
     * The "booted" method of the model.
     */
    protected static function booted()
    {
        static::creating(function ($prahari) {
            if (empty($prahari->prahari_id)) {
                // Get the last prahari that has a prahari_id
                $lastPrahari = self::whereNotNull('prahari_id')
                    ->where('prahari_id', 'like', 'PR%')
                    ->latest('id')
                    ->first();

                if (!$lastPrahari) {
                    $number = 1001;
                } else {
                    if (preg_match('/PR(\d+)/', $lastPrahari->prahari_id, $matches)) {
                        $number = (int)$matches[1] + 1;
                    } else {
                        $number = 1001;
                    }
                }

                $prahari->prahari_id = 'PR' . $number;
            }
        });
    }

    public function cases(){
        return $this->hasMany(Cases::class);
    }

    public function challans(){
        return $this->hasMany(Challan::class);
    }

    public function transactions(){
        return $this->hasMany(Transaction::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
