<?php

namespace Database\Factories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nog' => $this->faker->unique()->numerify('########'),
            'event' => $this->faker->randomElement(['Licitacion', 'Cotizacion', 'Compra Directa', 'Otro']),
            'name' => $this->faker->sentence(3),
            'url' => $this->faker->optional()->url(),
            'client' => $this->faker->company(),
            'presentation_date' => $this->faker->date(),
            'start_date' => $this->faker->optional()->date(),
            'end_date' => $this->faker->optional()->date(),
            'price' => $this->faker->randomFloat(2, 1000, 10000),
            'status' => $this->faker->randomElement(['En curso', 'Finalizado', 'Rechazado', 'Suspendido', 'No presentado']),
        ];
    }
}
