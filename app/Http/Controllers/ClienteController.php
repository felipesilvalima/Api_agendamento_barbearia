<?php declare(strict_types=1); 

namespace App\Http\Controllers;

use App\DTOS\AtualizarClienteDTO;
use App\DTOS\ClienteAtributosFiltrosPaginacaoDTO;
use App\DTOS\ClienteDTO;
use App\Http\Requests\AtualizarClienteRequest;
use App\Http\Requests\ClienteRequest;
use App\Models\Cliente;
use App\Models\User;
use App\Services\ClienteService;
use App\Services\ValidarDomainService;
use Symfony\Component\HttpFoundation\Request;

class ClienteController extends Controller
{
    public function __construct(
        private ClienteService $clienteService,
        private ValidarDomainService $validarService
    ){}

  
    public function criarClientes(ClienteRequest $request)
    {
        $data = $request->validated();

        $this->clienteService->CadastrarCliente(new ClienteDTO(
            nome: $data['nome'],
            email: $data['email'],
            password: $data['password'],
            telefone: $data['telefone'],
            barbearia_id: $this->user()->barbeiro_id
        ));
        
        return response()->json([
            "mensagem" => "Usuário cadastrado com sucesso",
        ],201); 
    }

    public function listarAgendamentosClientes(Request $request)
    {
       $lista =  $this->clienteService->listar(new ClienteAtributosFiltrosPaginacaoDTO(
            id_cliente: $this->user()->cliente->id,
            atributos: $request->atributos ?? null,
            atributos_agendamento: $request->atributos_agendamento ?? null,
            atributos_barbeiro: $request->atributos_barbeiro ?? null,
            atributos_servico: $request->atributos_servico ?? null
       ));
       
       return response()->json($lista,200);
    }

    public function detalhesClientes(int $id_cliente)
    {
        $this->authorize('detalhes',$this->clienteIstancia($id_cliente));
        $detalhes =  $this->clienteService->detalhes($id_cliente);
        return response()->json($detalhes,200);
    }

    public function atualizarClientes(ClienteRequest $request)
    {
        //validar dados de entrada
        $request->validated();

        //chamar service
        $this->clienteService->atualizar(new AtualizarClienteDTO(
            cliente: $this->user()->cliente,
            nome: $request['nome'] ?? null,
            telefone: $request['telefone'] ?? null
        ));

        //retornar resposta
        return response()->json(['mensagem' => 'Atuliazado com sucesso'],200);
    }
    
    
    private function user(): ?User
    {
        return auth('api')->user();
    }
    
    public function clienteIstancia(int $id_cliente): ?Cliente
    {   
        $this->validarService->validarExistenciaCliente($id_cliente,"Não e possivel ver detalhes. Esse Cliente não existe");
        return Cliente::findOrFail($id_cliente);
    }

    
}