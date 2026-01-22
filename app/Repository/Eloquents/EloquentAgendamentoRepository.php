<?php declare(strict_types=1); 

namespace App\Repository\Eloquents;


use App\DTOS\AgendamentoDTO;
use App\DTOS\AgendamentosAtributosFiltrosPagincaoDTO;
use App\Models\Agendamento;
use App\Repository\Abstract\BaseRepository;
use App\Repository\Contratos\AgendamentosRepositoryInterface;

class EloquentAgendamentoRepository extends BaseRepository implements AgendamentosRepositoryInterface
{
    
    public function __construct(private Agendamento $agendamentoModel)
    {
        parent::__construct($agendamentoModel);
    }

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
            return $this->existe($id_agenda);
        }

        public function listar(AgendamentosAtributosFiltrosPagincaoDTO $agendamentoDTO): iterable
        {
            return $this->findAll($agendamentoDTO);
        }

        public function detalhes(int $id_agenda): object
        {
            $listaAgendas = $this->agendamentoModel
            ->with(['barbeiro','cliente','agendamento_servico','agendamento_servico.servico'])
            ->where('id',$id_agenda)
            ?->first();

            return $listaAgendas;
        }
        
}