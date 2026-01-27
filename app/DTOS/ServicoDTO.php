<?php declare(strict_types=1); 

namespace App\DTOS;

class ServicoDTO
{

public function __construct(
        public int $id_barbeiro,
        public string $nome,
        public ?string $descricao = null,
        public int $duracao_minutos = 30,
        public float $preco
        
    ){}


    public function getNome()
    {
        return mb_convert_case($this->nome,MB_CASE_TITLE, "UTF-8");
    }
}