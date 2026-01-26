<?php declare(strict_types=1); 

namespace App\Http\Controllers;

use App\DTOS\BarbeiroAtributosFiltrosPaginacaoDTO;
use App\DTOS\BarbeiroDTO;
use App\Http\Requests\BarbeiroRequest;
use App\Models\Barbeiro;
use App\Services\BarbeiroService;
use App\Services\ValidarDomainService;
use Symfony\Component\HttpFoundation\Request;

class BarbeiroController extends Controller
{
    public function __construct(
        private BarbeiroService $barbeiroService,
        private ValidarDomainService $validarService,
    ){}

    public function criarBarbeiros(BarbeiroRequest $request)
    {
        $data = $request->validated();

        
        $this->barbeiroService->CadastrarBarbeiro(new BarbeiroDTO(
            nome: $data['nome'],
            email: $data['email'],
            password: $data['password'],
            telefone: $data['telefone'],
            especialidade: $data['especialidade']
        ));
        
        return response()->json([
            "mensagem" => "Usuário cadastrado com sucesso",
        ],201); 
    }

    public function listarAgendamentosBarbeiros(Request $request)
    {
       $lista =  $this->barbeiroService->listar(new BarbeiroAtributosFiltrosPaginacaoDTO(
            id_barbeiro: $this->id_barbeiro(),
            atributos: $request->atributos ?? null,
            atributos_agendamento: $request->atributos_agendamento ?? null,
            atributos_cliente: $request->atributos_cliente ?? null,
            atributos_servico: $request->atributos_servico ?? null
       ));
       
       return response()->json($lista,200);
    }

    public function detalhesBarbeiros(int $id_barbeiro)
    {
       $this->authorize('detalhes',$this->barbeiroIstancia($id_barbeiro));
       $detalhes =  $this->barbeiroService->detalhes($id_barbeiro);
       return response()->json($detalhes,200);
    }

    //PUT /barbeiros/{id}: Atualiza dados (como jornada de trabalho).
    private function id_barbeiro (): ?int
    {
        return auth('api')->user()->id_barbeiro;
    }

    public function barbeiroIstancia(int $id_barbeiro): ?Barbeiro
    {   
        $this->validarService->validarExistenciaBarbeiro($id_barbeiro,"Não e possivel ver detalhes. Esse barbeiro não existe");
        return Barbeiro::findOrFail($id_barbeiro);
    }
}
