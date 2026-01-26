<?php declare(strict_types=1); 

namespace App\DTOS;

use App\Models\Cliente;

class AtualizarClienteDTO
{
    public function __construct(
        public Cliente $cliente,
        public ?string $nome = null,
        public ?int $telefone = null
    )
    {
    }
}