<?php declare(strict_types=1); 

namespace App\Policy;

use App\Models\Agendamento;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AgendamentoPolicy
{
   public function reagendar(User $user, Agendamento $agendamento)
   {
        return ($user->id_cliente === $agendamento->id_cliente)
        ? Response::allow() 
        : Response::deny('Você não tem permissão para criar agendamento',403);
   }

    public function cancelar(User $user, Agendamento $agendamento)
    {
        return ($user->id_cliente === $agendamento->id_cliente || $user->id_barbeiro === $agendamento->id_barbeiro)
        ? Response::allow() 
        : Response::deny('Você não tem permissão para cancelar',403);
    }

    public function finalizar(User $user, Agendamento $agendamento)
    {
        return  ($user->id_barbeiro === $agendamento->id_barbeiro) 
        ? Response::allow() 
        : Response::deny('Você não tem permissão para concluir',403);
    }


    public function detalhes(User $user, Agendamento $agendamento)
    {
        return  ($user->id_cliente === $agendamento->id_cliente || $user->id_barbeiro === $agendamento->id_barbeiro)
        ? Response::allow() 
        : Response::deny('Você não tem permissão para buscar esse recurso',403);
    }

    public function removerServico(User $user, Agendamento $agendamento)
    {
        return ($user->id_cliente === $agendamento->id_cliente)
        ? Response::allow() 
        : Response::deny('Você não tem permissão para remover esse serviço',403);
    }

    public function adicionarServico(User $user, Agendamento $agendamento)
    {
        return ($user->id_cliente === $agendamento->id_cliente)
        ? Response::allow() 
        : Response::deny('Você não tem permissão para adicionar esse serviço',403);
    }

}