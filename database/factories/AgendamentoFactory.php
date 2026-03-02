<?php declare(strict_types=1); 

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Agendamento;
use App\Models\Cliente;
use App\Models\Barbeiro;
use App\Models\Barbearia;

class AgendamentoFactory extends Factory
{
    protected $model = Agendamento::class;

    public function definition()
    {
        return [
            'data' => $this->faker->date(),
            'hora' => $this->faker->time(),
            'status' => $this->faker->randomElement(['AGENDADO','CONCLUIDO','CANCELADO']),
            'id_cliente' => Cliente::factory(),
            'id_barbeiro' => Barbeiro::factory(),
            'barbearia_id' => Barbearia::factory(),
        ];
    }
}
