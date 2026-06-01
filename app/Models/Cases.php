<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cases extends Model
{
    use HasFactory;
    //
    protected $fillable = [
        'case_id',
        'prahari_id',
        'case_category_id',
        'vehicle_number',
        'location',
        'evidence',
        'status',
    ];

    /**
     * The "booted" method of the model.
     */
    protected static function booted()
    {
        static::creating(function ($case) {
            if (empty($case->case_id)) {
                // Get the last case that has a case_id
                $lastCase = self::whereNotNull('case_id')
                    ->where('case_id', 'like', 'CASE%')
                    ->latest('id')
                    ->first();

                if (!$lastCase) {
                    $number = 2309;
                } else {
                    if (preg_match('/CASE(\d+)/', $lastCase->case_id, $matches)) {
                        $number = (int)$matches[1] + 1;
                    } else {
                        $number = 2309;
                    }
                }

                $case->case_id = 'CASE' . $number;
            }
        });
    }

    public function caseCategory(){
        return $this->belongsTo(CaseCategory::class);
    }
    public function prahari(){
        return $this->belongsTo(Prahari::class);
    }

    public function challan(){
        return $this->hasOne(Challan::class);
    }

}
