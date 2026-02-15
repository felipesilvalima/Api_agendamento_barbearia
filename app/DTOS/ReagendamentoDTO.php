<?php declare(strict_types=1); 

namespace App\DTOS;

class ReagendamentoDTO
{
    public int $id_barbeiro;

    public function __construct(
        public string $data,
        public string $hora,
        public int $id_cliente,
        public int $id_agendamento,
        
    ){}
}