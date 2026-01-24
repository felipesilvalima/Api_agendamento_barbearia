<?php declare(strict_types=1); 

namespace App\Http\Controllers;

use App\DTOS\ClienteAtributosFiltrosPaginacaoDTO;
use App\DTOS\ClienteDTO;
use App\DTOS\CriarClienteDtos;
use App\Http\Requests\ClienteRequest;
use App\Services\AgendamentoService;
use App\Services\ClienteService;
use Symfony\Component\HttpFoundation\Request;

class ClienteController extends Controller
{
    public function __construct(
        private ClienteService $clienteService,
        private AgendamentoController $agendamento_controller
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

    //GET /clientes: Lista todos os clientes (com paginação e filtros).

    public function listarClientes(Request $request)
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

    
    //GET /clientes/{id}: Obtém detalhes de um cliente específico.


    //PUT /clientes/{id}: Atualiza dados de um cliente.
    

    //GET /clientes/{id}/agendamentos: Lista o histórico de agendamentos de um cliente.
    
    private function id_cliente(): ?int
    {
        return auth('api')->user()->id_cliente;
    }    
}
