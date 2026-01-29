<?php declare(strict_types=1); 

namespace App\Http\Controllers;

use App\Models\Notificacao;
use App\Services\NotificacaoService;
use App\Services\ValidarDomainService;

class NotificacaoController extends Controller
{
    public function __construct(
        private NotificacaoService $notificacaoService,
        private ValidarDomainService $validaService
    ){}

    public function listarNotificacoes()
    {
       $notificacoes = $this->notificacaoService->listar(auth()->user()->id);
       return response()->json($notificacoes,200);
    }

    public function deletarNotificaos(int $id_notificao)
    {
        $this->authorize('deleta',$this->notificaoInstancia($id_notificao));
        $this->notificacaoService->deletar($id_notificao);
        return response()->json(['mensagem' => 'Notificação removida com sucesso'],200);
    }



    private function notificaoInstancia(int $id_notificao): ?Notificacao
    {
        $this->validaService->validarExistenciaNoticacao($id_notificao,"Nenhuma notificação encontrada");
        return Notificacao::findOrFail($id_notificao);
    }

}