<?php declare(strict_types=1); 

namespace App\Http\Controllers;

use App\DTOS\BarbeiroDTO;
use App\DTOS\CriarBarbeiroDtos;
use App\Http\Requests\BarbeiroRequest;
use App\Services\BarbeiroService;


class BarbeiroController extends Controller
{
    public function __construct(private BarbeiroService $barbeiroService){}

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


    // GET /barbeiros: Lista todos os barbeiros ativos.


    //GET /barbeiros/{id}: Obtém perfil e especialidades de um barbeiro.


    //PUT /barbeiros/{id}: Atualiza dados (como jornada de trabalho).


    //GET /barbeiros/{id}/agenda: Consulta a agenda disponível/ocupada de um barbeiro.

}
