<?php declare(strict_types=1); 

namespace App\Repository\Contratos;

use App\DTOS\CriarBarbeiroDtos;
use App\Entitys\BarbeiroEntity;

interface BarbeiroRepositoryInterface
{
    public function verificarBarbeiroExiste($id_barbeiro): bool;
    public function salvarBarbeiro(BarbeiroEntity $barbeiro): int;
    public function PerfilBarbeiro($id_barbeiro): object | bool;
            
}