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
            'CodigoAlternativo' => $this->faker->bothify('??-#####'),
            'Nome'              => $this->faker->word(),
            'Unidade'           => $this->faker->randomElement(['KG', 'L']),
            'Valor'             => $this->faker->randomFloat(2, 1, 100),
            'CustoMedio'        => $this->faker->randomFloat(2, 1, 100),
            'Ativo'             => $this->faker->boolean(),
        ];
    }
}
