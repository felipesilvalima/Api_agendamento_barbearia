<?php declare(strict_types=1); 

namespace App\Http\Controllers;

use App\DTOS\AtualizarServicoDTO;
use App\DTOS\ServicoDTO;
use App\DTOS\ServicosAtributosFiltrosDTO;
use App\Http\Requests\ServicosRequest;
use App\Models\Servico;
use App\Services\ServicoService;
use App\Services\ValidarDomainService;
use Symfony\Component\HttpFoundation\Request;

class ServicoController extends Controller
{
    public function __construct(
        private ServicoService $servicoService,
        private AgendamentoController $agendamento_controller,
        private ValidarDomainService $validarServico,

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

    public function criarServicos(ServicosRequest $request)
    {
        $data = $request->validated();

        $this->servicoService->CadastrarServicos(new ServicoDTO(
            id_barbeiro: $this->id_barbeiro(),
            nome: $data['nome'],
            descricao: $data['descricao'] ?? null,
            duracao_minutos: $data['duracao_minutos'] ?? 0,
            preco: $data['preco']
        ));
        
        return response()->json([
            "mensagem" => "Servico cadastrado com sucesso",
        ],201); 
    }

    public function detalhesServicos(int $id_servico)
    {
        $detalhes =  $this->servicoService->detalhes($id_servico);
        return response()->json($detalhes,200);
    }

    public function atualizarServicos(ServicosRequest $request, int $id_servico)
    {
         //validar dados de entrada
        $request->validated();

        //chamar service
        $this->servicoService->atualizar(new AtualizarServicoDTO(
            id_barbeiro: $this->id_barbeiro(),
            id_servico: $id_servico,
            descricao: $request['descricao'] ?? null,
            preco: $request['preco'] ?? 0
        ));

           //retornar resposta
           return response()->json(['mensagem' => 'Atuliazado com sucesso'],200);
            
    }

   //PUT /servicos/{id}: Atualiza preço ou descrição de um serviço.

   //DELETE /servicos/{id}: Desativa um serviço

    private function id_barbeiro (): ?int
    {
        return auth('api')->user()->id_barbeiro;
    }

}
