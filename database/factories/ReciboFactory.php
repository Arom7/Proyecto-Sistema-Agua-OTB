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

        $disponibles = array_diff($todosConsumos,$consumosUsados);

        $idConsumo = $this->faker->randomElement($disponibles);

        $consumosUsados[] = $idConsumo;

        return [
            'estado_pago'=>false,
            'total'=>$this->faker->randomFloat(1,10,50),
            'fecha_lectura'=>$this->faker->dateTimeThisMonth(),
            'observaciones' => $this->faker->sentence,
            'id_consumo_recibo'=>$idConsumo
        ];
    }
}
