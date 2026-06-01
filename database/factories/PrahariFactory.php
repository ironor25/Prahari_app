<?php

namespace Database\Factories;

use App\Models\Prahari;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Prahari>
 */
class PrahariFactory extends Factory
{
    protected $model = Prahari::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'prahari_id' => 'PR' . $this->faker->unique()->numberBetween(100, 9999),
            'name' => $this->faker->name(),
            'mobile' => $this->faker->phoneNumber(),
            'bank_account' => $this->faker->bankAccountNumber(),
            'aadhaar_status' => $this->faker->randomElement(['verified', 'not_verified']),
            'status' => $this->faker->randomElement(['active', 'inactive']),
        ];
    }
}
