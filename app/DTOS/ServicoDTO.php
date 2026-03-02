<?php declare(strict_types=1); 

namespace App\DTOS;

use Illuminate\Http\UploadedFile;

class ServicoDTO
{
    public string $path;

public function __construct(
        public int $id_barbeiro,
        public string $nome,
        public string $preco,
        public int $barbearia_id,
        public ?string $descricao = null,
        public int $duracao_minutos = 30,
        public ?UploadedFile $imagem = null 
        
    ){}


    public function getNome()
    {
        return mb_convert_case($this->nome,MB_CASE_TITLE, "UTF-8");
    }
}