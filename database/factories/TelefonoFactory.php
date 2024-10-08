<?php

namespace Database\Factories;

use App\Models\Socio;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Telefono>
 */
class TelefonoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $socio = Socio::inRandomOrder()->first();

        if (!$socio) {
            $socio = Socio::factory()->create();
        }

        return [
            'socio_id_telefono' =>$socio->id,
            'numero_telefonico' => $this->faker->numerify('########'),
        ];
    }
}
