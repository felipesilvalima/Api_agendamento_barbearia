<?php declare(strict_types=1); 

namespace App\DTOS;

use App\Models\Cliente;
use App\Models\User;
use App\Enums\Status;

class AgendamentoDTO
{
    public function __construct(
        public int $id_barbeiro,
        public User $clienteUser,
        public string $data,
        public string $hora,
        public array $servicos,
        public int $barbearia_id,
        public string $status = Status::AGENDADO,
    ){}

}