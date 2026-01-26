<?php declare(strict_types=1); 

namespace App\Http\Controllers;

use App\DTOS\ServicosAtributosFiltrosDTO;
use App\Services\ServicoService;
use Symfony\Component\HttpFoundation\Request;

class ServicoController extends Controller
{
    public function __construct(
        private ServicoService $servicoService,
        private AgendamentoController $agendamento_controller,

    ){}
    
    public function listarServicos(Request $request)
    {

       $lista =  $this->servicoService->listar(new ServicosAtributosFiltrosDTO(
            atributos: $request->atributos ?? null,
            filtros: $request->filtros ?? null
       ));

       return response()->json($lista,200);
    }

    public function precoTotalAgendamento(int $id_agendamento)
    {
        $this->authorize('agenda',$this->agendamento_controller->agendamentoInstancia($id_agendamento));
        $precoTotal =  $this->servicoService->precoTotal($id_agendamento);
        return response()->json($precoTotal,200);
    }


    //POST /servicos: Cria um novo serviço (apenas admin).

   //GET /servicos/{id}: Obtém detalhes de um serviço.

   //PUT /servicos/{id}: Atualiza preço ou descrição de um serviço.

   //DELETE /servicos/{id}: Desativa um serviço

}
