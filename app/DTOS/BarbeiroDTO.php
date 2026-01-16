<?php declare(strict_types=1); 

namespace App\DTOS;

use App\Entitys\BarbeiroEntity;

class BarbeiroDTO
{
    public int $id_barbeiro;
    
    public function __construct(
        public string $nome,
        public string $email,
        public string $password,
        public int $telefone,
        public string $especialidade = "Barbeiro Completo",
        public string $status = "ATIVO"   
    ){}


  
}