<?php declare(strict_types=1); 

namespace App\Http\Controllers;

use App\DTOS\ClienteDTO;
use App\DTOS\CriarClienteDtos;
use App\Http\Requests\ClienteRequest;
use App\Services\AgendamentoService;
use App\Services\ClienteService;


class ClienteController extends Controller
{
    public function __construct(
        private ClienteService $clienteService,
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

    public function alterarPerfil()
    {
        
    }

    private function id_cliente(): int | null
    {
        return auth('api')->user()->id_cliente;
    }

    
}
