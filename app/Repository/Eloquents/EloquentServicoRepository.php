<?php declare(strict_types=1); 

namespace App\Repository\Eloquents;

use App\DTOS\ServicosAtributosFiltrosDTO;
use App\Repository\Abstract\BaseRepository;
use App\Models\Servico;
use App\Repository\Contratos\ServicoRepositoryInteface;

class EloquentServicoRepository extends BaseRepository implements ServicoRepositoryInteface
{
    public function __construct(
        private Servico $servicoModel, 
    )
    {
        parent::__construct($servicoModel);
    }
    
    public function existeServico(int $id_servico): bool
    {
        return $this->existe($id_servico);
    }

    public function listar(ServicosAtributosFiltrosDTO $servicosDto):object
    {
        if($servicosDto->atributos != null)
        {
            //atributos
            $this->selectAtributos('id,'.$servicosDto->atributos);
        }
            if($servicosDto->filtros_validos != null)
            {
                //filtros
                $this->filtro($servicosDto->filtros_validos);
            }

            return $this->getResultado();
    }

    public function precoTotalPorAgendamento(int $id_agendamento): float
    {
        $this->filtroRelacionamento(["agendamentos.id:=:{$id_agendamento}"],'agendamento');
        $precoTotal = $this->getResultado();
        return collect($precoTotal)->sum('preco');
    }

}