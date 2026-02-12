<?php declare(strict_types=1); 

namespace App\DTOS;

class BarbeariaDTO
{
    public function __construct(
        public string $nome,
        public ?string $endereco,
        public ?string  $telefone,
        public string $email
    )
    {
    
    }
}