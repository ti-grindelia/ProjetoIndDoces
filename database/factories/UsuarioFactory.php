<?php

namespace Database\Factories;

use App\Models\Usuario;
use Illuminate\Database\Eloquent\Factories\Factory;

class UsuarioFactory extends Factory
{
    protected $model = Usuario::class;
    protected static ?string $password;

    public function definition(): array
    {
        return [
            'Nome'    => fake()->name(),
            'Usuario' => fake()->unique()->userName(),
            'Email'   => fake()->unique()->safeEmail(),
            'Senha'   => 'password',
        ];
    }
}
