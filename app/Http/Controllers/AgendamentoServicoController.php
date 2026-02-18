<?php declare(strict_types=1); 

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\AgendamentoServicoService;
use Symfony\Component\HttpFoundation\Request;

class AgendamentoServicoController extends Controller
{

    public function __construct(
        private AgendamentoServicoService $agendamentoService,
        private AgendamentoController $agendamento_controller
    ){}

    public function removerServicos(int $id_agendamento, int $id_servico)
    {
        $this->authorize('removerServico',$this->agendamento_controller->agendamentoInstancia($id_agendamento));
        $this->agendamentoService->removerDeAgendamentos($this->user()->cliente->id, $id_agendamento, $id_servico); 
        return response()->json(['mensagem' => 'Serviço removido de agendamento com sucesso'],200);
    }

    public function listaServicosAgendamento(int $id_agendamento)
    {
        $this->authorize('detalhes', $this->agendamento_controller->agendamentoInstancia($id_agendamento));
        $lista = $this->agendamentoService->listar($id_agendamento);
        return response()->json($lista,200);
    }

    public function adicionarServicosAgendamento(int $id_agendamento, int $id_servico)
    {
        $this->authorize('adicionarServico', $this->agendamento_controller->agendamentoInstancia($id_agendamento));
        $this->agendamentoService->adicionar($this->user()->cliente,$id_agendamento, $id_servico);
        return response()->json(['mensagem' => 'Servicos adicionado com sucesso'],201);
    }
    

        private function user(): ?User 
        {
            return auth('api')->user();
        }


}
