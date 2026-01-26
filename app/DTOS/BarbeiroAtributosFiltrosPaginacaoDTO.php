<?php declare(strict_types=1); 

namespace App\DTOS;

class BarbeiroAtributosFiltrosPaginacaoDTO
{
    public function __construct(
        public ?int $id_barbeiro = null,
        public ?string $atributos = null,
        public ?string $atributos_agendamento = null,
        public ?string $atributos_cliente = null,
        public ?string $atributos_servico = null,
    ){}
}