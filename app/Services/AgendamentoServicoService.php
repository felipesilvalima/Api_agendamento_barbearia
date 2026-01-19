<?php declare(strict_types=1); 

namespace App\Services;

use App\Repository\Contratos\AgendamentoServicoRepositoyInterface;


class AgendamentoServicoService
{
    public function __construct(
        private AgendamentoServicoRepositoyInterface $agendamento_ServicoRepository,
        private ValidarDomainService $validarService,
    ){}
    
    public function removerDeAgendamentos(?int $cliente_id, int $id_agendamento, int $id_servico): void
    {
       //validação de segurança e permissoes
        $this->validarService->validaCliente($cliente_id);
        $this->validarService->validarExistenciaServico($id_servico);
        $this->validarService->validarServicoExisteAgendamento($id_agendamento, $id_servico);

        //remover
        $this->agendamento_ServicoRepository->remover($id_agendamento, $id_servico);
        
    }
}