<?php declare(strict_types=1); 

namespace App\Repository\Contratos;

interface NotificacaoRepositoryInterface
{
    public function notificacoes(int $id_user): object;
    public function delete(int $id_notificao): bool;
    public function existeNotificao(int $id_notificao): bool;
}