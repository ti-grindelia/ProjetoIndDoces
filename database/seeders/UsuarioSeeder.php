<?php

namespace Database\Seeders;

use App\Models\Usuario;
use Illuminate\Database\Seeder;

class UsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Usuario::factory()
            ->create([
                'Nome'    => 'Admin',
                'Usuario' => 'admin',
                'Email'   => 'admin@email.com',
                'Senha'   => 'password',
            ]);

        Usuario::factory(20)->create();
    }
}
