<?php declare(strict_types=1); 

namespace App\Repository\Contratos;

use App\DTOS\BarbeiroDTO;

interface BarbeiroRepositoryInterface
{
    public function verificarBarbeiroExiste($id_barbeiro): bool;
    public function salvarBarbeiro(BarbeiroDTO $barbeiroDto): int;
    public function PerfilBarbeiro($id_barbeiro): object | bool;
            
}