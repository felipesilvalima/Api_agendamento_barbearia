<?php declare(strict_types=1); 

namespace App\Repository;

use App\Models\Servico;

class ServicoRepository
{
    public function __construct(
        private Servico $servicoModel, 
    ){}

    public function salvarServico(array $data)
    {
        
    }

    
}