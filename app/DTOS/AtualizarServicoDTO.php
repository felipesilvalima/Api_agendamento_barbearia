<?php declare(strict_types=1); 

namespace App\DTOS;

use App\Models\Barbeiro;

class AtualizarServicoDTO
{
    public function __construct(
        public int $id_barbeiro,
        public int $id_servico,
        public ?string $descricao = null,
        public string $preco
    )
    {
    }
}