<?php declare(strict_types=1); 

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Agendamento;
use App\Models\Agendamento_servico;
use App\Models\AgendamentoServico;
use App\Models\Servico;
use App\Models\Barbearia;

class AgendamentoServicoFactory extends Factory
{
    protected $model = AgendamentoServico::class;

    public function definition()
    {
        return [
            'id_agendamento' => Agendamento::factory(),
            'id_servico' => Servico::factory(),
            'barbearia_id' => Barbearia::factory(),
        ];
    }
}
