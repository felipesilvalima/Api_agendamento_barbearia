<?php declare(strict_types=1); 

namespace App\Repository\Eloquents;

use App\Models\Notificacao;
use App\Repository\Abstract\BaseRepository;
use App\Repository\Contratos\NotificacaoRepositoryInterface;


class EloquentNotificaoRepository extends BaseRepository implements NotificacaoRepositoryInterface
{
    public function __construct(protected Notificacao $notificacao)
    {
        parent::__construct($notificacao);
    }


    public function notificacoes(int $id_user): object
    {
        $this->buscarPorEntidade($id_user,'notifiable_id');
        return $this->getResultado();  
    }

    public function delete(int $id_notificao): bool
    {
        $this->notificacao
        ->find($id_notificao)
        ->delete();
        
        return true;
    }

    public function existeNotificao(int $id_notificao): bool
    {
        return $this->existe($id_notificao);
    }
}