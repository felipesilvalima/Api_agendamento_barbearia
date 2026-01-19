<?php declare(strict_types=1); 

namespace App\Http\Controllers;

use App\Services\AgendamentoServicoService;

class AgendamentoServicoController extends Controller
{

    public function __construct(
        private AgendamentoServicoService $agendamentoService,
        private AgendamentoController $agendamento_controller
    ){}

    public function removerServicos(int $id_agendamento, int $id_servico)
    {
        $this->authorize('removerServico',$this->agendamento_controller->agendamentoInstancia($id_agendamento));
        $this->agendamentoService->removerDeAgendamentos($this->id_cliente(), $id_agendamento, $id_servico); 
        return response()->json(["mensagem" => "Serviço removido de agendamento com sucesso"],200);
    }


//GET/agendamentos_servico - todos os serviços vinculados a um agendamento específico.

//POST /agendamentos/{agendamento_id}/servicos - Adiciona um ou mais serviços a um agendamento existente.




}
