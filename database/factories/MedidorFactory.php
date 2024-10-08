<?php

namespace Database\Factories;

use App\Models\Propiedad;
use Illuminate\Database\Eloquent\Factories\Factory;

use function PHPUnit\Framework\isEmpty;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Medidor>
 */
class MedidorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        static $id_PropiedadUsados = [];

        $propiedad = null;

        do{
            $propiedad = Propiedad::inRandomOrder()->first();
        }while(in_array($propiedad->id,$id_PropiedadUsados));

        $id_PropiedadUsados[] = $propiedad->id;

        return [
            'propiedad_id_medidor' => $propiedad->id,
            'id_medidor'=> $this->faker->unique()->numberBetween(1000,2000),
            'medidor_nuevo'=> $this->faker->boolean(50),
            'medida_inicial' => $this->faker->numberBetween(0,10),
            'ultima_medida' => $this->faker->numberBetween(11,100),
        ];
    }
}
