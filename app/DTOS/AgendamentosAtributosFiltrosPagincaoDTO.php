<?php declare(strict_types=1); 

namespace App\DTOS;

class AgendamentosAtributosFiltrosPagincaoDTO
{
    public ?array $filtro_validado;
    public ?array $filtro_barbeiro_validado;
    public ?array $filtro_cliente_validado;

    public function __construct(
        public ?int $id_cliente,
        public ?int $id_barbeiro,
        public ?string $atributos,
        public ?string $atributos_barbeiro,
        public ?string $atributos_cliente,
        public ?string $filtro,
        public ?string $filtro_barbeiro,
        public ?string $filtro_cliente,
        public ?string $limit,
        public ?string $page
    ){}
    
}