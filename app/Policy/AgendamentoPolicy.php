<?php declare(strict_types=1); 

namespace App\Policy;

use App\Models\Agendamento;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use App\Enums\Role;

class AgendamentoPolicy
{
   public function reagendar(User $user, Agendamento $agendamento)
   {
        return ($user->role === Role::CLIENTE && $user->cliente->id === $agendamento->id_cliente)
        ? Response::allow() 
        : Response::deny('Você não tem permissão para criar agendamento',403);
   }

    public function cancelar(User $user, Agendamento $agendamento)
    {
        return ($user->role === Role::CLIENTE && $user->cliente->id === $agendamento->id_cliente || $user->role === Role::BARBEIRO && $user->barbeiro->id === $agendamento->id_barbeiro)
        ? Response::allow() 
        : Response::deny('Você não tem permissão para cancelar',403);
    }

    public function finalizar(User $user, Agendamento $agendamento)
    {
        return  ($user->role === Role::BARBEIRO && $user->barbeiro->id === $agendamento->id_barbeiro) 
        ? Response::allow() 
        : Response::deny('Você não tem permissão para concluir',403);
    }


    public function detalhes(User $user, Agendamento $agendamento)
    {
        return  ($user->role === Role::CLIENTE && $user->cliente->id === $agendamento->id_cliente || $user->role === Role::BARBEIRO && $user->barbeiro->id === $agendamento->id_barbeiro)
        ? Response::allow() 
        : Response::deny('Você não tem permissão para buscar esse recurso',403);
    }

    public function agenda(User $user, Agendamento $agendamento)
    {
        return  ($user->role === Role::CLIENTE && $user->cliente->id === $agendamento->id_cliente || $user->role === Role::BARBEIRO && $user->barbeiro->id === $agendamento->id_barbeiro)
        ? Response::allow() 
        : Response::deny('Você não tem permissão para acessar esse recurso',403);
    }

    public function removerServico(User $user, Agendamento $agendamento)
    {
        return ($user->role === Role::CLIENTE && $user->cliente->id === $agendamento->id_cliente)
        ? Response::allow() 
        : Response::deny('Você não tem permissão para remover esse serviço',403);
    }

    public function adicionarServico(User $user, Agendamento $agendamento)
    {
        return ($user->role === Role::CLIENTE && $user->cliente->id === $agendamento->id_cliente)
        ? Response::allow() 
        : Response::deny('Você não tem permissão para adicionar esse serviço',403);
    }

}