<?php declare(strict_types=1); 

namespace App\Http\Controllers;

use App\DTOS\BarbeariaFiltroDTO;
use App\Http\Controllers\Controller;
use App\Models\Barbearia;
use App\Services\BarbeariaService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class BarbeariaController extends Controller
{
    
    public function __construct(
        private BarbeariaService $barbeariaService,

    )
    {
    }
    
    public function listarBarbearias(Request $request)
    {   
       $lista = $this->barbeariaService->listar(new BarbeariaFiltroDTO(
          atributos_barbearia:  $request->atributos ?? null,
          atributos_user:  $request->atributos_user ?? null,
          filtro_barbearia:  $request->filtro ?? null
       ));
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

    public function desativarBarbearia(int $id_barbearia)
    {
        $this->barbeariaService->desativar($id_barbearia);
        return response()->json([
            "mensagem" => "Barbearia desativada com sucesso" 
        ],200);
    }

    public function ativarBarbearia(int $id_barbearia)
    {
        $this->barbeariaService->ativar($id_barbearia);

        return response()->json([
            'mensagem' => 'Barbearia ativada'
        ],200);
        
        
    }
}