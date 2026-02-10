<?php declare(strict_types=1); 

namespace App\Repository\Eloquents;

use App\Models\Agendamento_servico;
use App\Repository\Abstract\BaseRepository;
use App\Repository\Contratos\AgendamentoServicoRepositoyInterface;

class EloquentAgendamentoServicoRepository extends BaseRepository implements AgendamentoServicoRepositoyInterface
{
    public function __construct(private Agendamento_servico $agendamentoServicoModel)
    {
        parent::__construct($agendamentoServicoModel);
    }

    public function vincular(int $id_agendamento, array $servicos, int $barbearia_id): bool
    {   
        $servicos = collect($servicos)->unique()->values();// remover valores iguais
        
        foreach($servicos as $servico)
        {
            $this->agendamentoServicoModel->create([
                "id_agendamento" => $id_agendamento,
                "id_servico" => $servico,
                "barbearia_id" => $barbearia_id
            ]);
        }

        return true;
    }

    public function existeServicoAgendamento(int $id_agendamento, int $id_servico): bool
    {
       return $this->agendamentoServicoModel
       ->where('id_agendamento',$id_agendamento)
       ->where('id_servico',$id_servico)
        ->exists();
    }

    public function remover(int $id_agendamento, int $id_servico): int
    {
        $this->filtro(["id_agendamento:=:$id_agendamento"]);
        $this->filtro(["id_servico:=:$id_servico"]);
        return $this->delete();
    }

    public function listarPorAgendamento(int $id_agendamento): iterable
    {
       $this->selectAtributosRelacionamentos('servico');
       $this->filtro(["id_agendamento:=:$id_agendamento"]);

       return $this->getResultado();
    }
}