<?php declare(strict_types=1); 

namespace App\Repository\Eloquents;

use App\DTOS\ServicoDTO;
use App\DTOS\ServicosAtributosFiltrosDTO;
use App\Repository\Abstract\BaseRepository;
use App\Models\Servico;
use App\Repository\Contratos\ServicoRepositoryInteface;
use Illuminate\Database\Eloquent\Collection;

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

    public function listar(ServicosAtributosFiltrosDTO $servicosDto): Collection
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

            if($servicosDto->id_barbeiro != null)
            {
                $this->selectAtributosRelacionamentos('agendamento');
            }

            return $this->getResultado();
    }

    public function precoTotalPorAgendamento(int $id_agendamento): float
    {
        $this->filtroRelacionamento(["agendamentos.id:=:{$id_agendamento}"],'agendamento');
        $precoTotal = $this->getResultado();
        return collect($precoTotal)->sum('preco');
    }

    public function salvarServicos(ServicoDTO $servicoDto): bool
    {
        
        $this->servicoModel
        ->create([
            'nome' => $servicoDto->getNome(),
            'descricao' => $servicoDto->descricao,
            'duracao_minutos' => $servicoDto->duracao_minutos,
            'preco' => (float)$servicoDto->preco,
            'imagem' => $servicoDto->path,
            'barbearia_id' => $servicoDto->barbearia_id
        ]);

        return true;
    }

    public function detalhes(int $id_servico): object
    {
        $this->buscarPorEntidade($id_servico,'id');

        if(auth('api')->user()->id_barbeiro != null)
        {
            $this->selectAtributosRelacionamentos('agendamento');
        }
        
        return $this->firstResultado();
    }

}