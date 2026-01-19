<?php declare(strict_types=1); 

namespace App\Repository\Eloquents;

use App\DTOS\AgendamentoDTO;
use App\Models\Agendamento;

use App\Repository\Contratos\AgendamentosRepositoryInterface;

class EloquentAgendamentoRepository implements AgendamentosRepositoryInterface
{
    
    public function __construct(private Agendamento $agendamentoModel){}

        public function existeAgendamentoHorario($id_barbeiro, $hora, $data): bool
        {
            $result = $this->agendamentoModel->
            where('id_barbeiro',$id_barbeiro)->
            where('status', 'AGENDADO')->whereTime('hora', $hora)->whereDate('data', $data)->exists();
            return $result;
        }

        public function salvar(AgendamentoDTO $agendamentoDTO): object
        {
            return $this->agendamentoModel->create([
                'data' => $agendamentoDTO->data,
                'hora' => $agendamentoDTO->hora,
                'id_cliente' => $agendamentoDTO->id_cliente,
                'id_barbeiro' => $agendamentoDTO->id_barbeiro,
                'status' => $agendamentoDTO->status
            ]);
        }

        public function existeAgenda(int $id_agenda): bool
        {
            return $this->agendamentoModel
            ->where('id', $id_agenda)
            ->exists();
        }

        public function listar(?int $cliente_id, ?int $barbeiro_id): iterable
        {
            if(!is_null($cliente_id))
            {
                $listaAgendas = $this->agendamentoModel
                ->with(['barbeiro','cliente','agendamento_servico.servico'])
                ->where('id_cliente', $cliente_id)
                ->get();
            }
                else
                {
                     $listaAgendas = $this->agendamentoModel
                    ->with(['barbeiro','cliente','agendamento_servico.servico'])
                    ->where('id_barbeiro', $barbeiro_id)
                    ->get();
                }

            return $listaAgendas;
        }

        public function detalhes(int $id_agenda): object
        {
            $listaAgendas = $this->agendamentoModel
            ->with(['barbeiro','agendamento_servico','agendamento_servico.servico'])
            ->where('id',$id_agenda)
            ?->first();

            return $listaAgendas;
        }
        
}