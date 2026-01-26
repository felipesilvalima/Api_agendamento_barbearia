<?php declare(strict_types=1); 

namespace App\Services;

use App\Exceptions\ErrorInternoException;
use App\Exceptions\NaoExisteRecursoException;
use App\Repository\Contratos\AgendamentoServicoRepositoyInterface;
use App\Repository\Contratos\ClienteRepositoryInterface;
use DomainException;

class AgendamentoServicoService
{
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
        $lista = $this->agendamento_ServicoRepository->listarPorAgendamento($id_agendamento);

        if(collect($lista)->isEmpty())
        {
            throw new NaoExisteRecursoException("Nenhuma lista encotrada");
        }

        return $lista;
        
    }

    public function adicionar(?int $cliente_id ,int $id_agendamento, int $id_servico)
    {
        //validação de segurança e permissoes
        $this->validarService->validarExistenciaCliente($cliente_id,"Não e possivel adicionar servico. esse Cliente não existe");
        
            $this->validarService->validarExistenciaServico($id_servico);

            if($this->agendamento_ServicoRepository->existeServicoAgendamento($id_agendamento, $id_servico))
            {
                throw new DomainException("Esse serviço Já está relacionado com esse agendamento",409);
            } 

                $servicos[] = $id_servico;

                $vincular = $this->agendamento_ServicoRepository->vincular($id_agendamento, $servicos);

                if(!$vincular)
                {
                    throw new ErrorInternoException("Error interno ao vincular servico ao agendamneto");
                }
    }
}