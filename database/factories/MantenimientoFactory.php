<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Otb;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Mantenimiento>
 */
class MantenimientoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Genera una fecha de inicio aleatoria
    $fecha_mantenimiento_inicio = $this->faker->dateTimeBetween('-1 year', 'now');

    // Añade entre 3 y 5 días a la fecha de inicio para obtener la fecha de fin
    $fecha_mantenimiento_fin = (clone $fecha_mantenimiento_inicio)->modify('+'.rand(3, 5).' days');

    // Añade 6 meses a la fecha de fin para obtener la fecha del próximo mantenimiento
    $fecha_proximo_mantenimiento = (clone $fecha_mantenimiento_fin)->modify('+6 months');

        return [
            'fecha_mantenimiento_inicio' => $fecha_mantenimiento_inicio,
            'fecha_mantenimiento_fin' => $fecha_mantenimiento_fin,
            'descripcion_mantenimiento' => $this->faker->text(),
            'responsable' => $this->faker->name(),
            'precio_total' => $this->faker->randomFloat(2, 100, 1000),
            'tipo_equipo' => $this->faker->randomElement(['pozo de agua', 'bomba de agua']),
            'fecha_proximo_mantenimiento' => $fecha_proximo_mantenimiento,
            'otb_id' => Otb::first()->id,
        ];
    }
}
