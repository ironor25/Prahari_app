<?php

namespace Database\Factories;

use App\Models\CaseCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class CaseCategoryFactory extends Factory
{
    protected $model = CaseCategory::class;

    public function definition(): array
    {
        return [
            'case_category_name' => fake()->word(),
            'fine_amount' => fake()->randomFloat(2, 100, 5000),
        ];
    }
}