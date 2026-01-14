<?php declare(strict_types=1); 

namespace App\Repository\Contratos;

interface ServicoRepositoryInteface
{
     public function existeServico(int $id_servico): bool;
    
}