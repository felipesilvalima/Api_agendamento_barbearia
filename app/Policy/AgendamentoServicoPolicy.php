<?php declare(strict_types=1); 

namespace App\Policy;

use App\Models\Agendamento;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AgendamentoServicoPolicy
{
    public function removerServico(User $user, Agendamento $agendamento)
    {
        return ($user->id_cliente === $agendamento->id_cliente)
        ? Response::allow() 
        : Response::deny('Você não tem permissão para remover esse serviço',403);
    }
}