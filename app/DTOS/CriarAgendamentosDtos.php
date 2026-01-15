<?php declare(strict_types=1); 

namespace App\DTOS;

use App\Entitys\AgendamentoEntity;


class CriarAgendamentosDtos
{
    public function __construct(
        public int $id_barbeiro,
        public int $id_cliente,
        public string $data,
        public string $hora,
        public array $servicos,
        public string $status = "AGENDADO"
    ){}


    public function createAgendamentoObject()
    {
        return new AgendamentoEntity(
            data: $this->data,
            hora: $this->hora,
            id_cliente: $this->id_cliente,
            id_barbeiro: $this->id_barbeiro,
            servicos: $this->servicos,
            status: $this->status
        );
    }
    
}