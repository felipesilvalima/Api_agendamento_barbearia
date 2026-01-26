<?php declare(strict_types=1); 

namespace App\Http\Controllers;

use App\Services\ServicoService;

class ServicoController extends Controller
{
    public function __construct(private ServicoService $servicoService){}
    
    //GET /servicos: Lista todos os serviços ativos.

    public function listarServicos()
    {
       $lista =  $this->servicoService->listar();
       return response()->json($lista,200);
    }

    //GET/ precoTotal por agendamento

    //POST /servicos: Cria um novo serviço (apenas admin).

   //GET /servicos/{id}: Obtém detalhes de um serviço.

   //PUT /servicos/{id}: Atualiza preço ou descrição de um serviço.

   //DELETE /servicos/{id}: Desativa um serviço

}
