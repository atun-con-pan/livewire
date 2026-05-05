<?php

namespace Database\Factories;

use App\Models\Collaborator;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Collaborator>
 */
class CollaboratorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'first_name' => $this->faker->firstName(),
            'middle_name' => $this->faker->optional()->firstName(),
            'first_surname' => $this->faker->lastName(),
            'second_last_name' => $this->faker->optional()->lastName(),
            'dpi' => $this->faker->unique()->numerify('#############'),
            'birthdate' => $this->faker->dateTimeBetween('-60 years', '-18 years'),
            'marital_status' => $this->faker->randomElement(['Single', 'Married', 'Divorced', 'Widowed']),
            'residence' => $this->faker->address(),
            'phone' => $this->faker->phoneNumber(),
            'email' => $this->faker->optional(0.8)->safeEmail(),
            'position' => $this->faker->jobTitle(),
            'start_date' => $this->faker->dateTimeBetween('-10 years', 'now'),
            'termination_date' => $this->faker->optional(0.3)->dateTimeBetween('-5 years', 'now'),
            'salary' => $this->faker->randomFloat(2, 3000, 15000),
            'contract' => $this->faker->randomElement(['Full-time', 'Part-time', 'Contract']),
            'pattern' => $this->faker->randomElement(['Fixed', 'Variable']),
            'bank_account' => $this->faker->optional()->numerify('################'),
            'bank' => $this->faker->optional()->company(),
            'bank_account_name' => $this->faker->optional()->name(),
            'no_igss' => $this->faker->unique()->numerify('IGSS-####'),
        ];
    }
}
