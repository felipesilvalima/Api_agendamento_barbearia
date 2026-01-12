<?php declare(strict_types=1); 

namespace App\Repository;

use App\Models\Agendamento_servico;

class AgendamentoServicoRepository
{
    public function __construct(private Agendamento_servico $agendamentoServicoModel){}

    public function SalvarAgendamentoServico(int $id_agendamento, array $servicos)
    {   
        $servicos = collect($servicos)->unique()->values();// remover valores iguais
        
        foreach($servicos as $servico)
        {
            $this->agendamentoServicoModel->create([
                "id_agendamento" => $id_agendamento,
                "id_servico" => $servico
            ]);
        }
    }

    public function existeServicoAgendamento(int $id_agendamento, int $id_servico): bool
    {
       return $this->agendamentoServicoModel
       ->where('id_agendamento',$id_agendamento)
       ->where('id_servico',$id_servico)
        ->exists();
    }

    public function remover(int $id_agendamento, int $id_servico)
    {
       return $this->agendamentoServicoModel
        ->where('id_agendamento',$id_agendamento)
        ->where('id_servico',$id_servico)
        ->delete();
    }
}