<?php

namespace Database\Seeders;

use App\Models\Socio;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SocioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Socio::create([
            'nombre_socio' => 'Alan',
            'primer_apellido_socio' => 'Mora',
            'segundo_apellido_socio' => 'Vargas',
            'ci_socio' => '13355063',
            'otb_id' => 1,
        ]);

        Socio::factory()->count(10)->create();
    }
}
