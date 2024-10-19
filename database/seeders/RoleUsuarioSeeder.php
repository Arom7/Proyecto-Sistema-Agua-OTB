<?php

namespace Database\Seeders;

use App\Models\Usuario;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleUsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $usuario = Usuario::find('aaliyahryan');
        $usuario->assignRole('administrador');

        $usuario = Usuario::find('adityatorp');
        $usuario->assignRole('lecturador');

        $usuario = Usuario::find('americoschultz');
        $usuario->assignRole('cobrador');

        $usuario = Usuario::find('bethelrutherford');
        $usuario->assignRole('mantenimiento');
    }
}
