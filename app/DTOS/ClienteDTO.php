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
        public int $id_barbearia,
        public string $role = "cliente"
    ){}

   


        /**
         * Get the value of nome
         */ 
        public function getNome()
        {
                return mb_convert_case($this->nome, MB_CASE_TITLE,'UTF-8');
        }
}