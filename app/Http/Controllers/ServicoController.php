<?php declare(strict_types=1); 

namespace App\Http\Controllers;

use App\Services\ServicoService;
use Illuminate\Http\Request;

class ServicoController extends Controller
{
    public function __construct(private ServicoService $servicoService){}
    
    public function criarServicos(Request $request)
    {
        
    }


}
