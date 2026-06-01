<?php

namespace Database\Factories;

use App\Models\CaseCategory;
use App\Models\Cases;
use App\Models\Challan;
use App\Models\Prahari;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Challan>
 */
class ChallanFactory extends Factory
{
    protected $model = Challan::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'prahari_id' => Prahari::inRandomOrder()->first()->id ?? Prahari::factory(),
            'case_id' => Cases::inRandomOrder()->first()->id ?? Cases::factory(),
            'category_id' => CaseCategory::inRandomOrder()->first()->id ?? CaseCategory::factory(),
            'vehicle_number' => strtoupper($this->faker->bothify('??##??####')),
            'fine_amount' => $this->faker->randomFloat(2, 500, 5000),
            'status' => $this->faker->randomElement(['paid', 'cancelled', 'pending']),
        ];
    }
}
