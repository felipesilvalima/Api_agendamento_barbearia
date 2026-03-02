<?php declare(strict_types=1); 

namespace App\Policy;

use App\Models\Cliente;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use App\Enums\Role;

class ClientePolicy
{
    public function detalhes(User $user, Cliente $cliente)
    {
        return ($user->role === Role::CLIENTE && $user->cliente->id === $cliente->id || $user->role === Role::BARBEIRO && $user->barbeiro->id === $user->agendamento->id_barbeiro)
        ? Response::allow() 
        : Response::deny('Você não tem permissão para acessar esse recurso',403);
    }
}