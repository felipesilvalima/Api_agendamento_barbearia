<?php declare(strict_types=1); 

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\BarbeariaService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class BarbeariaController extends Controller
{
    public function __construct(private BarbeariaService $barbeariaService)
    {
    }
    
    public function listarBarbearias()
    {
       $lista = $this->barbeariaService->listar();
        return response()->json($lista,200);
    }

    public function detalhesBarbearia(int $id_barbearia)
    {
        $detalhes = $this->barbeariaService->detalhes($id_barbearia);
        return Response()->json($detalhes,200);
    }

    public function criarBarbearia(Request $request)
    {

    }

    public function atualizarBarbearia(Request $request, int $id_barbearia)
    {

    }

    public function removerBarbearia(int $id_barbearia)
    {
        $this->barbeariaService->remover($id_barbearia);
        return response()->json([
            "mensagem" => "Barbearia removida com sucesso" 
        ],200);
    }

    public function ativarBarbearia(){}
}