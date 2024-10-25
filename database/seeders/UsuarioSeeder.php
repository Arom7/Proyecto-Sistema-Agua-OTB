<?php

namespace Database\Seeders;

use App\Models\Usuario;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Usuario::create([
            'username' => 'admin',
            'email' => 'alancitomora1999@gmail.com',
            'contrasenia' => bcrypt('admin'),
            'socio_id_usuario' => 1,
        ]);

        Usuario::factory()->count(12)->create();
    }
}
