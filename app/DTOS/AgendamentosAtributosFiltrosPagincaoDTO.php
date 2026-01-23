<?php declare(strict_types=1); 

namespace App\DTOS;

class AgendamentosAtributosFiltrosPagincaoDTO
{
    public ?array $filtro_validado = null;
    public ?array $filtro_barbeiro_validado = null;
    public ?array $filtro_cliente_validado = null;

    public function __construct(
        public ?int $id_cliente = null,
        public ?int $id_barbeiro = null,
        public ?string $atributos = null,
        public ?string $atributos_barbeiro = null,
        public ?string $atributos_cliente = null,
        public ?string $filtro = null,
        public ?string $filtro_barbeiro = null,
        public ?string $filtro_cliente = null,
        public ?string $limit = null,
        public ?string $page = null
    ){}
    
}