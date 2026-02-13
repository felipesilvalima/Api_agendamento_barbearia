<?php declare(strict_types=1); 

namespace App\Http\Controllers;

use App\DTOS\AtualizarBarbeiroDTO;
use App\DTOS\BarbeiroAtributosFiltrosPaginacaoDTO;
use App\DTOS\BarbeiroDTO;
use App\Http\Requests\BarbeiroRequest;
use App\Models\Barbearia;
use App\Models\Barbeiro;
use App\Models\User;
use App\Services\BarbeiroService;
use App\Services\ValidarDomainService;
use Symfony\Component\HttpFoundation\Request;

class BarbeiroController extends Controller
{
    public function __construct(
        private BarbeiroService $barbeiroService,
        private ValidarDomainService $validarService,
    ){}

    public function criarBarbeiros(BarbeiroRequest $request, int $id_barbearia)
    {
        $data = $request->validated();

        $this->authorize('criarBarbeiro',$this->barbeariaIstancia($id_barbearia));
        
        $this->barbeiroService->CadastrarBarbeiro(new BarbeiroDTO(
            nome: $data['nome'],
            email: $data['email'],
            password: $data['password'],
            telefone: $data['telefone'],
            especialidade: $request->especialidade,
            id_barbearia: $id_barbearia
        ));
        
        return response()->json([
            "mensagem" => "Usuário cadastrado com sucesso",
        ],201); 
    }

    public function listarAgendamentosBarbeiros(Request $request)
    {
        
       $lista =  $this->barbeiroService->listar(new BarbeiroAtributosFiltrosPaginacaoDTO(
            id_barbeiro: $this->user()->barbeiro->id,
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

    public function atualizarBarbeiros(BarbeiroRequest $request)
    {
        //validar dados de entrada
        $request->validated();

        //chamar service
        $this->barbeiroService->atualizar(new AtualizarBarbeiroDTO(
            barbeiro: $this->user()->barbeiro,
            telefone: $request['telefone'] ?? null,
            especialidade: $request['especialidade'] ?? null
        ));

        //retornar resposta
        return response()->json(['mensagem' => 'Atuliazado com sucesso'],200);
    }

    private function user (): ?User
    {
        return auth('api')->user();
    }

    public function barbeiroIstancia(int $id_barbeiro): ?Barbeiro
    {   
        $this->validarService->validarExistenciaBarbeiro($id_barbeiro,"Não e possivel ver detalhes. Esse barbeiro não existe");
        return Barbeiro::findOrFail($id_barbeiro);
    }

    public function barbeariaIstancia(int $id_barbearia): ?Barbearia
    {   
        $this->validarService->validarExistenciaBarbearia($id_barbearia,"Não e possivel criar barbeiro essa barbearia não existe");
        return Barbearia::findOrFail($id_barbearia);
    }
}
