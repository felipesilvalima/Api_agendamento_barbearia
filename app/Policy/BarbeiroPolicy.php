<?php declare(strict_types=1); 

namespace App\Policy;

use App\Models\Barbeiro;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class BarbeiroPolicy
{
    public function detalhes(User $user, Barbeiro $barbeiro)
    {
        return ($user->id_barbeiro === $barbeiro->id || $user->id_cliente != null)
        ? Response::allow() 
        : Response::deny('Você não tem permissão para acessar esse recurso',403);
    }
}