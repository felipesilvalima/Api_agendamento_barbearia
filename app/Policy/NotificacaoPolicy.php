<?php declare(strict_types=1); 

namespace App\Policy;

use App\Models\Notificacao;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class NotificacaoPolicy
{
    public function deleta(User $user, Notificacao $notificacao)
    {
        return ($user->id === $notificacao->notifiable_id)
        ? Response::allow() 
        : Response::deny('Você não tem permissão para deleta essa notificação',403);
    }  
}