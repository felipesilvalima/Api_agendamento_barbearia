<?php declare(strict_types=1); 

namespace App\DTOS;

use App\Models\Barbeiro;

class AtualizarBarbeiroDTO
{
    public function __construct(
        public Barbeiro $barbeiro,
        public ?string $nome = null,
        public ?int $telefone = null,
        public ?string $especialidade = null,
    )
    {
    }

        public function getNome()
        {
            return mb_convert_case($this->nome, MB_CASE_TITLE,'UTF-8');
        }
}