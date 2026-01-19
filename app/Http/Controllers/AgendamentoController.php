<?php declare(strict_types=1); 

namespace App\Http\Controllers;

use App\DTOS\AgendamentoDTO;
use App\DTOS\ReagendamentoDTO;
use App\Http\Requests\AgendamentoRequest;
use App\Http\Requests\ReagendamentoRequest;
use App\Models\Agendamento;
use App\Services\AgendamentoService;
use App\Services\ValidarDomainService;
use Illuminate\Http\Request;




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

    public function reagendarAgendamento(ReagendamentoRequest $request, int $id_agenda)
    {
       
        $data = $request->validated();

        $this->authorize('reagendar',$this->agendamentoInstancia($id_agenda));
        $this->agendamentoService->reagendamento(new ReagendamentoDTO(
            data: $data['data'],
            hora: $data['hora'],
            id_cliente: $this->id_cliente(),
            id_agendamento: $id_agenda
        ));

        return response()->json([
            "mensagem" => "Reagendado com sucesso"
        ],200);
    }

    public function listarAgendamentos(Request $request)
    {

        $this->authorize('listar',Agendamento::class);
        $agendamentos = $this->agendamentoService->agendamentos($this->id_cliente(),$this->id_barbeiro());  
        return response()->json($agendamentos,200);
    }

    public function buscarAgenda(int $id_agenda)
    {
        $this->authorize('buscar',$this->agendamentoInstancia($id_agenda));
        $agenda = $this->agendamentoService->agenda($id_agenda, $this->id_cliente(), $this->id_barbeiro());
        return response()->json($agenda,200);
    }

    public function cancelarAgendamentos(int $id_agenda)
    {

        $this->authorize('cancelar',$this->agendamentoInstancia($id_agenda));
        $agenda = $this->agendamentoService->cancelar($id_agenda, $this->id_cliente(), $this->id_barbeiro());

            return response()->json([
                "mensagem" => "Agendamento Cancelado com sucesso. ID do agendamento {$agenda->id}"
            ],200);
    }

    public function concluirAgendamentos(int $id_agenda)
    {
        $this->authorize('concluir',Agendamento::class);
        $agenda = $this->agendamentoService->concluirAgendamentos($id_agenda, $this->id_barbeiro());

        return response()->json([
            "mensagem" => "Agendamento Concluido com sucesso. ID do agendamento {$agenda->id}"
        ],200);  
    }

    public function removerServicos(int $id_agendamento, int $id_servico)
    {
        $this->authorize('removerServico',$this->agendamentoInstancia($id_agendamento));
        $this->agendamentoService->removerDeAgendamentos($this->id_cliente(), $id_agendamento, $id_servico); 
        return response()->json(["mensagem" => "Serviço removido de agendamento com sucesso"],200);
    }



        private function id_cliente(): ?int 
        {
            return auth('api')->user()->id_cliente;
        }

        private function id_barbeiro(): ?int
        {
            return auth('api')->user()->id_barbeiro;
        }

        private function agendamentoInstancia(int $id_agenda): ?Agendamento
        {
            $this->validarService->validarExistenciaAgendamento($id_agenda);
            return Agendamento::findOrFail($id_agenda);
        }


}
