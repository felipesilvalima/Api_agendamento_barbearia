<?php declare(strict_types=1); 

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Barbearia;

class BarbeariaFactory extends Factory
{
    protected $model = Barbearia::class;

    public function definition()
    {
        return [
            'nome' => $this->faker->company,
            'endereco' => $this->faker->address,
            'telefone' => $this->faker->phoneNumber,
            'email' => $this->faker->unique()->companyEmail,
            'status' => $this->faker->randomElement(['ATIVO','INATIVO']),
        ];
    }
}
