<?php

namespace Database\Factories;

use App\Models\CaseCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CaseCategory>
 */
class CaseCategoryFactory extends Factory
{
    protected $model = CaseCategory::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'case_category_name' => $this->faker->word(),
            'fine_amount' => $this->faker->randomFloat(2, 100, 5000),
        ];
    }
}
