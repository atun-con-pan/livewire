<?php

namespace Database\Factories;

use App\Models\Contract;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Contract>
 */
class ContractFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'no_contract' => $this->faker->unique()->numerify('CONTRACT-####'),
            'contract_registration_date' => $this->faker->date(),
            'contract_subscription_date' => $this->faker->date(),
            'start_date_activities' => $this->faker->date(),
            'final_date_activities' => $this->faker->optional()->date(),
            'nog_contract' => $this->faker->unique()->numerify('NOG-####'),
            'contract_name' => $this->faker->sentence(3),
            'execution_address' => $this->faker->address(),
            'number_workers' => $this->faker->numberBetween(1, 100),
            'salary_amount' => $this->faker->randomFloat(2, 1000, 10000),
            'status' => $this->faker->randomElement(['active', 'inactive', 'pending']),
            'filial' => $this->faker->company(),
            'person_charge' => $this->faker->name(),
        ];
    }
}
