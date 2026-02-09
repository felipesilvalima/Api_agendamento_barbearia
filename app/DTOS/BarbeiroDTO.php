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
        public int $barbearia_id,
        public string $especialidade = "Barbeiro Completo",
        public string $status = "ATIVO",
        public string $role = "barbeiro" 
    ){}


  

        /**
         * Get the value of nome
         */ 
        public function getNome()
        {
                return mb_convert_case($this->nome, MB_CASE_TITLE,'UTF-8');
        }
}