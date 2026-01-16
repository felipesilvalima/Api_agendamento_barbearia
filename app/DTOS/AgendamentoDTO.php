<?php declare(strict_types=1); 

namespace App\DTOS;



class AgendamentoDTO
{
    public function __construct(
        public int $id_barbeiro,
        public int $id_cliente,
        public string $data,
        public string $hora,
        public array $servicos,
        public string $status = "AGENDADO"
    ){}

}