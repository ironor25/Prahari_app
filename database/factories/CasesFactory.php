<?php

namespace Database\Factories;

use App\Models\Case;
use App\Models\CaseCategory;
use App\Models\Cases;
use App\Models\Prahari;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Cases>
 */
class CasesFactory extends Factory
{
    protected $model = Cases::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'case_id' => 'CASE' . $this->faker->unique()->numberBetween(100, 9999),
            'prahari_id' => Prahari::inRandomOrder()->first()->id ?? Prahari::factory(),
            'case_category_id' => CaseCategory::inRandomOrder()->first()->id ?? CaseCategory::factory(),
            'vehicle_number' => strtoupper($this->faker->bothify('??##??####')),
            'location' => $this->faker->city(),
            'evidence' => 'assets/video/' . $this->faker->numberBetween(1, 5) . '.mp4',
            'status' => $this->faker->randomElement(['Open', 'Approved', 'Rejected']),
        ];
    }

}
