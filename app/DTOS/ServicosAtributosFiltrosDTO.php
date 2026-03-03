<?php declare(strict_types=1); 

namespace App\DTOS;

class ServicosAtributosFiltrosDTO
{
    public ?array $filtros_validos = null;
    
     public function __construct(
        public ?int $id_barbeiro = null,
        public ?string $atributos = null,
        public ?string $filtros = null,
       
    ){}
}