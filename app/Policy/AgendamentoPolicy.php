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
        : Response::deny('Você não tem permissão para criar agendamneto',403);
   }

        public function cancelar(User $user, Agendamento $agendamento)
        {
            return ($user->id_cliente === $agendamento->id_cliente || $user->id_barbeiro != null)
            ? Response::allow() 
            : Response::deny('Você não tem permissão para cancelar',403);
        }

            public function concluir(User $user)
            {
                return  ($user->id_barbeiro != null) 
                ? Response::allow() 
                : Response::deny('Você não tem permissão para concluir',403);
            }

                public function removerServico(User $user, Agendamento $agendamento)
                {
                    return ($user->id_cliente === $agendamento->id_cliente)
                    ? Response::allow() 
                    : Response::deny('Você não tem permissão para remover esse serviço',403);
                }

                    public function listar(User $user)
                    {
                        return ($user->id_cliente !== null || $user->id_barbeiro != null)
                        ? Response::allow() 
                        : Response::deny('Você não tem permissão para acessar lista de recurso',403);
                    }
                        public function buscar(User $user, Agendamento $agendamento)
                        {
                            return  ($user->id_cliente === $agendamento->id_cliente || $user->id_barbeiro === $agendamento->id_barbeiro)
                            ? Response::allow() 
                            : Response::deny('Você não tem permissão para buscar esse recurso',403);
                        }

}