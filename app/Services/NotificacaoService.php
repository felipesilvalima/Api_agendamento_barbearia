<?php declare(strict_types=1); 

namespace App\Services;

use App\Exceptions\ErrorInternoException;
use App\Exceptions\NaoExisteRecursoException;
use App\Repository\Contratos\NotificacaoRepositoryInterface;

class NotificacaoService
{
    public function __construct(
        private ValidarDomainService $validarService,
        private NotificacaoRepositoryInterface $notificaoRepository
    ){}

    
    public function listar(int $id_user)
    {
        $this->validarService->validarExistenciaUsuario($id_user,"Não e possivel listar notificações. Usuário não existe");

        $notificacoesLista = $this->notificaoRepository->notificacoes($id_user);

        if(collect($notificacoesLista)->isEmpty())
        {
            throw new NaoExisteRecursoException("Lista de notificações vázia");
        }

        return $notificacoesLista;

    }

    public function deletar(int $id_notificao)
    {
        $deletado = $this->notificaoRepository->deleteNotificao($id_notificao);

        if($deletado != true)
        {
            throw new ErrorInternoException("Error interno ao deleta notificação");
        }
    }
}