<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CaseCategory extends Model
{
    use HasFactory;
    //
    protected $fillable = [
        'case_category_name',
        'fine_amount'
    ];

    public function cases(){
        return $this->hasMany(Cases::class);
    }
    public function challan(){
        return $this->hasMany(Challan::class);
    }


}
