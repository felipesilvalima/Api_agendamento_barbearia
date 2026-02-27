<?php declare(strict_types=1); 

namespace App\Services;

use App\Exceptions\ErrorInternoException;
use App\Exceptions\NaoExisteRecursoException;
use App\Models\Cliente;
use App\Helpers\CacheData;
use App\Repository\Contratos\AgendamentoServicoRepositoyInterface;
use App\Repository\Contratos\ClienteRepositoryInterface;
use DomainException;
use Illuminate\Support\Facades\Cache;

class AgendamentoServicoService
{
    use CacheData;

    public function __construct(
        private AgendamentoServicoRepositoyInterface $agendamento_ServicoRepository,
        private ValidarDomainService $validarService,
    ){}
    
    public function removerDeAgendamentos(?int $cliente_id, int $id_agendamento, int $id_servico): void
    {
        //validação de segurança e permissoes
        $this->validarService->validarExistenciaCliente($cliente_id,"Não e possivel remover servico. esse Cliente não existe");

        $this->validarService->validarExistenciaServico($id_servico);
        $this->validarService->validarServicoExisteAgendamento($id_agendamento, $id_servico);

        //remover
       $removido = $this->agendamento_ServicoRepository->remover($id_agendamento, $id_servico);

        if(!$removido)
        {
            throw new ErrorInternoException("Error interno ao remover servico do agendamneto");
        }
        
    }

    public function listar(int $id_agendamento)
    {
        $cacheKey = 'agendamentoServico:list';
        
        return $this->verificarCache($cacheKey);

        $lista = $this->agendamento_ServicoRepository->listarPorAgendamento($id_agendamento);

        if(collect($lista)->isEmpty())
        {
            throw new NaoExisteRecursoException("Nenhuma lista encotrada");
        }

        $this->adicionarCache($cacheKey, $lista, getenv('JWT_TTL'));

        return $lista;
        
    }

    public function adicionar(Cliente $cliente ,int $id_agendamento, int $id_servico)
    {
        //validação de segurança e permissoes
        $this->validarService->validarExistenciaCliente($cliente->id,"Não e possivel adicionar servico. esse Cliente não existe");
        
            $this->validarService->validarExistenciaServico($id_servico);

            if($this->agendamento_ServicoRepository->existeServicoAgendamento($id_agendamento, $id_servico))
            {
                throw new DomainException("Esse serviço Já está relacionado com esse agendamento",409);
            } 

                $servicos[] = $id_servico;

                $vincular = $this->agendamento_ServicoRepository->vincular($id_agendamento, $servicos, $cliente->barbearia_id);

                if(!$vincular)
                {
                    throw new ErrorInternoException("Error interno ao vincular servico ao agendamneto");
                }
    }

    
}