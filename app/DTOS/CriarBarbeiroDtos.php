<?php declare(strict_types=1); 

namespace App\DTOS;

use App\Entitys\BarbeiroEntity;

class CriarBarbeiroDtos
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


    public function createBarbeiroObjetc()
    {
        return new BarbeiroEntity(
            nome: $this->nome,
            email: $this->email,
            password: $this->password,
            telefone: $this->telefone,
            especialidade: $this->especialidade,
            status:  $this->status,
        );
    }
}