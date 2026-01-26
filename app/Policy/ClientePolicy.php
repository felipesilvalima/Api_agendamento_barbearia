<?php declare(strict_types=1); 

namespace App\Policy;

use App\Models\Cliente;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ClientePolicy
{
    public function detalhes(User $user, Cliente $cliente)
    {
        return ($user->id_cliente === $cliente->id || $user->id_barbeiro != null)
        ? Response::allow() 
        : Response::deny('Você não tem permissão para acessar esse recurso',403);
    }
}