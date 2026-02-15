<?php declare(strict_types=1); 

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\BarbeariaService;
use Symfony\Component\HttpFoundation\Request;

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
}