<?php declare(strict_types=1); 

namespace App\Repository\Contratos;

use Illuminate\Database\Eloquent\Collection;

interface NotificacaoRepositoryInterface
{
    public function notificacoes(int $id_user): Collection;
    public function deleteNotificao(int $id_notificao): bool;
    public function existeNotificao(int $id_notificao): bool;
}