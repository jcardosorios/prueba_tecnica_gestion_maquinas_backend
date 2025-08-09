<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Maquina>
 */
class MaquinaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $createdAt = $this->faker->dateTimeBetween('-1 year', 'now');
        return [
            'nombre' => $this->faker->randomElement(['Torno de Metal', 'Fresadora CNC', 'Prensa Hidraulica', 'Cortadora Laser']) . ' ' . $this->faker->unique()->numberBetween(1, 100),
            'coeficiente' => $this->faker->randomFloat(2, 1.0, 3.0),
            'created_at' => $createdAt,
            'updated_at' => $createdAt,
        ];
    }
}
