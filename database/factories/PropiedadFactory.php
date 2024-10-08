<?php

namespace Database\Factories;

use App\Models\Propiedad;
use App\Models\Socio;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Propiedad>
 */


class PropiedadFactory extends Factory
{
    protected $model = Propiedad::class;
    private static $ids = null;


    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $socio = Socio::inRandomOrder()->first();

        if (!$socio && $this->faker->boolean(85)) {
            $socio = Socio::factory()->create();
        }

        if (self::$ids === null) {
            self::$ids = $this->generateAllIds();
            shuffle(self::$ids);
        }

        $id = array_pop(self::$ids);

        return [
            'id' => $id,
            'socio_id' => $socio ? $socio->id : null,
            'direccion_propiedad' => $this->faker->address,
            'total_multas_propiedad' => $this->faker->randomFloat(2, 1, 100),
            'descripcion_propiedad' => $this->faker->sentence,
        ];
    }

    private function generateAllIds(): array
    {
        $ids = [];
        $letters = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R'];

        foreach ($letters as $letter) {
            for ($i = 1; $i <= 20; $i++) {
                $ids[] = $letter . '-' . $i;
            }
        }

        return $ids;
    }
}
