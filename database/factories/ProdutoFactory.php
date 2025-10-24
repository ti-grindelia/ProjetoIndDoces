<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Produto>
 */
class ProdutoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'Descricao'         => $this->faker->word(),
            'Descritivo'        => $this->faker->sentence(),
            'CodigoAlternativo' => $this->faker->bothify('??-#####'),
            'Ativo'             => $this->faker->boolean(),
        ];
    }
}
