<?php

namespace Database\Factories;

use App\Models\Prahari;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Transaction>
 */
class TransactionFactory extends Factory
{
    protected $model = Transaction::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'withdrawal_id' => 'W' . $this->faker->unique()->numberBetween(100, 9999),
            'prahari_id' => Prahari::inRandomOrder()->first()->id ?? Prahari::factory(),
            'amount' => $this->faker->randomFloat(2, 100, 2000),
            'bank_account' => $this->faker->bankAccountNumber(),
            'status' => $this->faker->randomElement(['Open', 'Approved', 'Rejected']),
        ];
    }
}
