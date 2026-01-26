<?php declare(strict_types=1); 

namespace App\DTOS;



class ClienteDTO
{
    public int $id_cliente;
    
    public function __construct(
        public string $nome,
        public string $email,
        public string $password,
        public int $telefone, 
    ){}

   

}