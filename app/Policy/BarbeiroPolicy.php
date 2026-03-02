<?php declare(strict_types=1); 

namespace App\Policy;

use App\Models\Barbearia;
use App\Models\Barbeiro;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use App\Enums\Role;

class BarbeiroPolicy
{
    public function detalhes(User $user, Barbeiro $barbeiro)
    {
        return ($user->role === Role::BARBEIRO && $user->barbeiro->id === $barbeiro->id || $user->role === Role::CLIENTE && $user->cliente->id === $user->agendamento->id_cliente)
        ? Response::allow() 
        : Response::deny('Você não tem permissão para acessar esse recurso',403);
    }

    public function criarBarbeiro(User $user, Barbearia $barbearia)
    {
        return ($user->role === Role::BARBEIRO && $user->barbearia_id === $barbearia->id)
            ? Response::allow() 
            : Response::deny('Você não tem permissão para acessar essa barbearia',403); 
    }
}