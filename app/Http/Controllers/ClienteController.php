<?php declare(strict_types=1); 

namespace App\Http\Controllers;

use App\DTOS\ClienteAtributosFiltrosPaginacaoDTO;
use App\DTOS\ClienteDTO;
use App\DTOS\CriarClienteDtos;
use App\Http\Requests\ClienteRequest;
use App\Models\Cliente;
use App\Services\AgendamentoService;
use App\Services\ClienteService;
use App\Services\ValidarDomainService;
use Symfony\Component\HttpFoundation\Request;

class ClienteController extends Controller
{
    public function __construct(
        private ClienteService $clienteService
    ){}

  
    public function criarClientes(ClienteRequest $request)
    {
        $data = $request->validated();

        $this->clienteService->CadastrarCliente(new ClienteDTO(
            nome: $data['nome'],
            email: $data['email'],
            password: $data['password'],
            telefone: $data['telefone']
        ));
        
        return response()->json([
            "mensagem" => "Usuário cadastrado com sucesso",
        ],201); 
    }

    public function listarAgendamentosClientes(Request $request)
    {
       $lista =  $this->clienteService->listar(new ClienteAtributosFiltrosPaginacaoDTO(
            id_cliente: $this->id_cliente(),
            atributos: $request->atributos ?? null,
            atributos_agendamento: $request->atributos_agendamento ?? null,
            atributos_barbeiro: $request->atributos_barbeiro ?? null,
            atributos_servico: $request->atributos_servico ?? null
       ));
       
       return response()->json($lista,200);
    }

    public function detalhesClientes(int $id_cliente)
    {
       $detalhes =  $this->clienteService->detalhes($id_cliente);
       return response()->json($detalhes,200);
    }

    
    //PUT /clientes/{id}: Atualiza dados de um cliente.

    
    
    private function id_cliente(): ?int
    {
        return auth('api')->user()->id_cliente;
    }

    
}