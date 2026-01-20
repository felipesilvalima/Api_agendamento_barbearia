<?php declare(strict_types=1); 

namespace App\Http\Controllers;

use App\DTOS\AgendamentoDTO;
use App\DTOS\ReagendamentoDTO;
use App\Http\Requests\AgendamentoRequest;
use App\Http\Requests\ReagendamentoRequest;
use App\Models\Agendamento;
use App\Services\AgendamentoService;
use App\Services\ValidarDomainService;
use Symfony\Component\HttpFoundation\Request;

class AgendamentoController extends Controller
{
    
    public function __construct(
        private AgendamentoService $agendamentoService,
        private ValidarDomainService $validarService,
    ){}

    public function criarAgendamento(AgendamentoRequest $request)
    {
        $data = $request->validated();

        $agendamento_id = $this->agendamentoService->agendar(new AgendamentoDTO(
            id_barbeiro: $data['id_barbeiro'],
            id_cliente: $this->id_cliente(),
            data: $data['data'],
            hora: $data['hora'],
            servicos: $data['servicos']
        ));

        return response()->json([
            "mensagem" => "Agendamento criado com sucesso. ID do seu agendamento {$agendamento_id}"
        ],201);
        
    }

    public function listarAgendamentos(Request $request)
    {
        $atributos =  $request->atributos ?? null;
        $atributos_barbeiro = $request->atributos_barbeiro ?? null;
        $atributos_cliente = $request->atributos_cliente ?? null;
        $filtro = $request->filtro ?? null;
        $filtro_barbeiro = $request->filtro_barbeiro ?? null;
        $filtro_cliente = $request->filtro_cliente ?? null;
        $limit = $request->limit ?? null;
        $page = $request->page ?? null;


        $agendamentos = $this->agendamentoService->agendamentos(
            $this->id_cliente(),
            $this->id_barbeiro(),
            $atributos,
            $atributos_barbeiro,
            $atributos_cliente,
            $filtro,
            $filtro_barbeiro,
            $filtro_cliente,
            (int)$limit,
            (int)$page
        );

        return response()->json($agendamentos,200);
    }

    public function verAgenda(int $id_agenda)
    {
        $this->authorize('detalhes',$this->agendamentoInstancia($id_agenda));
        $agenda = $this->agendamentoService->detalhesAgenda($id_agenda);
        return response()->json($agenda,200);
    }

    public function reagendarAgendamentos(ReagendamentoRequest $request, int $id_agenda)
    {
       
        $data = $request->validated();

        $this->authorize('reagendar',$this->agendamentoInstancia($id_agenda));
        $this->agendamentoService->reagendar(new ReagendamentoDTO(
            data: $data['data'],
            hora: $data['hora'],
            id_cliente: $this->id_cliente(),
            id_agendamento: $id_agenda
        ));

        return response()->json([
            "mensagem" => "Reagendado com sucesso"
        ],200);
    }

    public function finalizarAgendamentos(int $id_agenda)
    {
        $this->authorize('finalizar',$this->agendamentoInstancia($id_agenda));
        $agenda = $this->agendamentoService->finalizar($id_agenda, $this->id_barbeiro());

        return response()->json([
            "mensagem" => "Agendamento Concluido com sucesso. ID do agendamento {$agenda->id}"
        ],200);  
    }

    public function cancelarAgendamentos(int $id_agenda)
    {

        $this->authorize('cancelar',$this->agendamentoInstancia($id_agenda));
        $agenda = $this->agendamentoService->cancelar($id_agenda, $this->id_cliente(), $this->id_barbeiro());

            return response()->json([
                "mensagem" => "Agendamento Cancelado com sucesso. ID do agendamento {$agenda->id}"
            ],200);
    }

   

        private function id_cliente(): ?int 
        {
            return auth('api')->user()->id_cliente;
        }

        private function id_barbeiro(): ?int
        {
            return auth('api')->user()->id_barbeiro;
        }

        public function agendamentoInstancia(int $id_agenda): ?Agendamento
        {
            $this->validarService->validarExistenciaAgendamento($id_agenda);
            return Agendamento::findOrFail($id_agenda);
        }


}
