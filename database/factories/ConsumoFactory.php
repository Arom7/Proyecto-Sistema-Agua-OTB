<?php

namespace Database\Factories;

use App\Models\Consumo;
use App\Models\Medidor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Consumo>
 */
class ConsumoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $todosMedidoresPropiedades = Medidor::pluck('propiedad_id_medidor')->toArray();

        $idMedidor = $this->faker->randomElement($todosMedidoresPropiedades);

        return [
            'propiedad_id_consumo'=> $idMedidor,
            'consumo_total' => $this->faker->numberBetween(10,70),
            'lectura_actual' => $this->faker->numberBetween(100,200),
            'mes_correspondiente' => $this->faker->dateTimeThisDecade()->format('Y-m-01')
        ];
    }
}
