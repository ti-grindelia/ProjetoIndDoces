<?php

namespace Database\Factories;

use App\Models\MateriaPrima;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MateriaPrima>
 */
class MateriaPrimaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'Nome'       => $this->faker->word(),
            'Descricao'  => $this->faker->sentence(),
            'Fornecedor' => $this->faker->company(),
            'Ativo'      => $this->faker->boolean(),
        ];
    }
}
