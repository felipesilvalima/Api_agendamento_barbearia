<?php declare(strict_types=1); 

namespace App\Repository;

use App\Models\Agendamento_servico;

class AgendamentoServicoRepository
{
    public function __construct(private Agendamento_servico $agendamentoServicoModel){}

    public function SalvarAgendamentoServico(int $id_agendamento, array $servicos)
    {
        foreach($servicos as $servico)
        {
            $this->agendamentoServicoModel->create([
                "id_agendamento" => $id_agendamento,
                "id_servico" => $servico
            ]);
        }
    }
}