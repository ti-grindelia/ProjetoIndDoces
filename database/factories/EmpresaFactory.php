<?php

namespace Database\Factories;

use App\Models\Empresa;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Empresa>
 */
class EmpresaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'CNPJ' => $this->faker->unique()->numerify('########0001##'),
            'RazaoSocial' => $this->faker->company(),
            'CEP' => $this->faker->postcode(),
            'Endereco' => $this->faker->streetName(),
            'Numero' => $this->faker->buildingNumber(),
            'Complemento' => $this->faker->secondaryAddress(),
            'Bairro' => $this->faker->citySuffix(),
            'Cidade' => $this->faker->city(),
            'Estado' => $this->faker->stateAbbr(),
            'Telefone' => $this->faker->phoneNumber(),
            'Email' => $this->faker->unique()->safeEmail(),
            'Ativo' => 1,
        ];
    }
}
