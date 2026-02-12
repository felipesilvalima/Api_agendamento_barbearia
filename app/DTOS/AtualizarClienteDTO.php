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

        public function getNome()
        {
                return mb_convert_case($this->nome, MB_CASE_TITLE,'UTF-8');
        }
}