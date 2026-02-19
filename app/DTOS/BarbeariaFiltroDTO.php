<?php declare(strict_types=1); 

namespace App\DTOS;

class BarbeariaFiltroDTO
{
    public ?array $filtro_barbearia_validado;

    public function __construct(
        public ?string $atributos_barbearia,
        public ?string $atributos_user,
        public ?string $filtro_barbearia,
    )
    {
        
    }
}