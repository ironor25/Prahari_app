<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Challan extends Model
{
    use HasFactory;
    //
    protected $fillable = [
        'prahari_id',
        'case_id',
        'vehicle_number',
        'category_id',
        'fine_amount',
        'status',
    ];

    public function category(){
        return $this->belongsTo(CaseCategory::class);
    }

    public function prahari(){
        return $this->belongsTo(Prahari::class);
    }
    public function cases(){
        return $this->belongsTo(Cases::class, 'case_id');
    }
}
