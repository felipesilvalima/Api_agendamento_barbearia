<?php declare(strict_types=1); 

namespace App\DTOS;

class CriarClienteDtos
{
    public int $id_cliente;
    public string $data_cadastro;
    
    public function __construct(
        public string $nome,
        public string $email,
        public string $password,
        public int $telefone,  
    ){}
}