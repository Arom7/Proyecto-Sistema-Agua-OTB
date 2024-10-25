<?php

namespace Database\Factories;

use App\Models\Consumo;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Recibo>
 */
class ReciboFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $todosConsumos = Consumo::pluck('id_consumo')->toArray();

        static $consumosUsados = [];

        $disponibles = array_diff($todosConsumos, $consumosUsados);

        $idConsumo = $this->faker->randomElement($disponibles);

        $consumosUsados[] = $idConsumo;

        return [
            'estado_pago' => $this->faker->boolean(50),
            'total' => $this->faker->randomFloat(1, 10, 50),
            'fecha_lectura' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'observaciones' => $this->faker->sentence,
            'id_consumo_recibo' => $idConsumo,
            'lectura_anterior_correspondiente' => $this->faker->numberBetween(1, 100),
            'lectura_actual_correspondiente' => $this->faker->numberBetween(1, 100),
        ];
    }
}
