<?php declare(strict_types=1); 

namespace App\Services;

use App\Exceptions\NaoExisteRecursoException;
use App\Repository\Contratos\AgendamentoServicoRepositoyInterface;
use App\Repository\Contratos\ClienteRepositoryInterface;

class AgendamentoServicoService
{
    public function __construct(
        private AgendamentoServicoRepositoyInterface $agendamento_ServicoRepository,
        private ValidarDomainService $validarService,
        private ClienteRepositoryInterface $clienteRepository
    ){}
    
    public function removerDeAgendamentos(?int $cliente_id, int $id_agendamento, int $id_servico): void
    {
       //validação de segurança e permissoes
        if(!$this->clienteRepository->verificarClienteExiste($cliente_id))
        {
            throw new NaoExisteRecursoException("Não e possivel remover servico. esse Cliente não existe");
        }

        $this->validarService->validarExistenciaServico($id_servico);
        $this->validarService->validarServicoExisteAgendamento($id_agendamento, $id_servico);

        //remover
        $this->agendamento_ServicoRepository->remover($id_agendamento, $id_servico);
        
    }
}