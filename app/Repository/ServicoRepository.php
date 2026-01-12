<?php declare(strict_types=1); 

namespace App\Repository;

use App\Models\Servico;

class ServicoRepository
{
    public function __construct(
        private Servico $servicoModel, 
    ){}
    
    public function existeServico(int $id_servico): bool
    {
        return $this->servicoModel
        ->where('id',$id_servico)
        ->exists();
    }
}