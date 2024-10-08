<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Propiedad;
use App\Models\Multa;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PropiedadMulta>
 */
class PropiedadMultaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $propiedad = Propiedad::all()->random();
        $multa = Multa::all()->random();

        info('Propiedad ID: ' . $propiedad);
        info('Infracción ID: ' . $multa->id);

        return [
            'propiedad_id' => $propiedad->id,
            'infracion_id' => $multa->id,
            'fecha_multa' => $this->faker->dateTimeThisYear,
        ];
    }
}
