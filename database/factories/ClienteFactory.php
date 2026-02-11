<?php declare(strict_types=1); 

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Cliente;
use App\Models\User;
use App\Models\Barbearia;

class ClienteFactory extends Factory
{
    protected $model = Cliente::class;

    public function definition()
    {
        return [
            'telefone' => $this->faker->phoneNumber,
            'data_cadastro' => $this->faker->date(),
            'status' => $this->faker->randomElement(['ATIVO','INATIVO']),
            'user_id' => User::factory()->state(['role' => 'cliente']),
            'barbearia_id' => Barbearia::factory(),
        ];
    }
}
