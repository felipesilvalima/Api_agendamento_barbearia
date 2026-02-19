<?php declare(strict_types=1); 

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Barbearia;
use App\Services\BarbeariaService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class BarbeariaController extends Controller
{
    
    public function __construct(
        private BarbeariaService $barbeariaService,
        private Barbearia $barbearia
    )
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

    public function desativarBarbearia(int $id_barbearia)
    {
        $this->barbeariaService->remover($id_barbearia);
        return response()->json([
            "mensagem" => "Barbearia removida com sucesso" 
        ],200);
    }

    public function listarDesativado()
    {
       $lista = $this->barbearia->onlyTrashed()->get();

        if(collect($lista)->isEmpty())
        {
            abort(404,'Listar de Barbeiro desativados vázia');
        }

       return response()->json($lista,200);
    }
    

    public function ativarBarbearia(int $id_barbearia)
    {
        $barbearia = $this->barbearia->onlyTrashed()->find($id_barbearia);

        if($barbearia === null)
        {
            abort(404,'Essa barbearia não está desativada');
        }
        
        $barbearia->status = 'ATIVO';
        $barbearia->save();
        $barbearia->restore();
        
        return response()->json([
            'barbearia' => $barbearia,
            'mensagem' => 'Barbearia ativada'
        ],200);
        
        
    }
}