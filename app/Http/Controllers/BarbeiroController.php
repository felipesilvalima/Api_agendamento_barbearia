<?php declare(strict_types=1); 

namespace App\Http\Controllers;

use App\Http\Requests\BarbeiroRequest;
use App\Services\BarbeiroService;


class BarbeiroController extends Controller
{
    public function __construct(private BarbeiroService $barbeiroService){}

    public function criarBarbeiros(BarbeiroRequest $request)
    {
        $data = $request->validated();
        $this->barbeiroService->CadastrarBarbeiro($data);
        
        return response()->json([
            "mensagem" => "Usuário cadastrado com sucesso",
        ],201); 
    }
}
