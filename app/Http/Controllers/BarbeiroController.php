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
}
