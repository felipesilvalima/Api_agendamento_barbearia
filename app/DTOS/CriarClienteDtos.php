<?php declare(strict_types=1); 

namespace App\DTOS;

use App\Entitys\ClienteEntity;

class CriarClienteDtos
{
    public int $id_cliente;
    
    public function __construct(
        public string $nome,
        public string $email,
        public string $password,
        public int $telefone,  
    ){}

    public function createClienteObjetc()
    {
        return new ClienteEntity(
            nome: $this->nome,
            email: $this->email,
            password: $this->password,
            telefone: $this->telefone,
            
        );
    }

}