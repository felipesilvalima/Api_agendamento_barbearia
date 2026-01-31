<?php declare(strict_types=1); 

namespace App\DTOS;

use App\Models\Barbeiro;
use Illuminate\Http\UploadedFile;

class AtualizarServicoDTO
{
    public string $path;
    
    public function __construct(
        public int $id_barbeiro,
        public int $id_servico,
        public ?string $descricao = null,
        public ?string $preco = null,
        public ?UploadedFile $imagem = null
    )
    {
    }
}