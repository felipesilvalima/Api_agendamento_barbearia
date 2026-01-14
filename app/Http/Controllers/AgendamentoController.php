<?php declare(strict_types=1); 

namespace App\Http\Controllers;

use App\DTOS\CriarAgendamentosDtos;
use App\DTOS\CriarReagendamentoDtos;
use App\Http\Requests\AgendamentoRequest;
use App\Services\AgendamentoService;
use Illuminate\Http\Request;

class AgendamentoController extends Controller
{
    
    public function __construct(private AgendamentoService $agendamentoService){}

    public function criarAgendamento(AgendamentoRequest $request)
    {
        $data = $request->validated();

        $dtos = new CriarAgendamentosDtos(
            id_barbeiro: $data['id_barbeiro'],
            id_cliente: $this->id_cliente(),
            data: $data['data'],
            hora: $data['hora'],
            servicos: $data['servicos']
        );

        $agendamento_id = $this->agendamentoService->agendar($dtos);

        return response()->json([
            "mensagem" => "Agendamento criado com sucesso. ID do seu agendamento {$agendamento_id}"
        ],201);
        
    }

    public function reagendarAgendamento(Request $request, int $id_agenda)
    {
        $data = $request->validate([
            'data' => 'required|date|date_format:Y-m-d|after_or_equal:today',
            'hora' => 'required|date_format:H:i:s'
        ],[
           'required' => 'O campo :attribute é obrigatório',
            'date' => 'O campo :attribute precisar ser do tipo data',
            'date_format' => 'O campo :attribute precisar ter um formato válido',
            'after_or_equal' => 'Data inválida. Escolha uma data mais atual', 
        ]);

        $dtos = new CriarReagendamentoDtos(
            data: $data['data'],
            hora: $data['hora'],
            id_cliente: $this->id_cliente(),
            id_agendamento: $id_agenda
        );

        $this->agendamentoService->reagendamento($dtos);

        return response()->json([
            "mensagem" => "Reagendado com sucesso"
        ],200);
    }

    public function listarAgendamentos()
    {
        $agendamentos = $this->agendamentoService->agendamentos($this->id_cliente(), $this->id_barbeiro());
        return response()->json($agendamentos,200);
    }

    public function buscarAgenda(int $id_agenda)
    {
        $agenda = $this->agendamentoService->agenda($id_agenda, $this->id_cliente(), $this->id_barbeiro());
        return response()->json($agenda,200);
    }

    public function cancelarAgendamentos(int $id_agenda)
    {
        $agenda = $this->agendamentoService->cancelar($id_agenda, $this->id_cliente(), $this->id_barbeiro());

            return response()->json([
                "mensagem" => "Agendamento Cancelado com sucesso. ID do agendamento {$agenda->id}"
            ],200);
    }

    public function concluirAgendamentos(int $id_agenda)
    {
        $agenda = $this->agendamentoService->concluirAgendamentos($id_agenda, $this->id_barbeiro());

        return response()->json([
            "mensagem" => "Agendamento Concluido com sucesso. ID do agendamento {$agenda->id}"
        ],200);  
    }

    public function removerServicos(int $id_agendamento, int $id_servico)
    {
        $this->agendamentoService->removerDeAgendamentos($this->id_cliente(), $id_agendamento, $id_servico); 
        return response()->json(["mensagem" => "Serviço removido de agendamento com sucesso"],200);
    }



        private function id_cliente(): int | null
        {
            return auth('api')->user()->id_cliente;
        }

        private function id_barbeiro(): int | null
        {
            return auth('api')->user()->id_barbeiro;
        }


}
