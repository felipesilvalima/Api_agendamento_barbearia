<?php declare(strict_types=1); 

namespace App\DTOS;

use App\Models\Barbeiro;

class AtualizarBarbeiroDTO
{
    public function __construct(
        public Barbeiro $barbeiro,
        public ?int $telefone = null,
        public ?string $especialidade = null,
    )
    {
    }
}