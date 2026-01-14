<?php declare(strict_types=1); 

namespace App\DTOS;

class CriarBarbeiroDtos
{
    public int $id_barbeiro;
    
    public function __construct(
        public string $nome,
        public string $email,
        public string $password,
        public int $telefone,
        public string $especialidade,
        public string $status = "ATIVO"   
    ){}
}