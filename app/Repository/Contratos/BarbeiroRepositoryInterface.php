<?php declare(strict_types=1); 

namespace App\Repository\Contratos;

use App\DTOS\BarbeiroAtributosFiltrosPaginacaoDTO;
use App\DTOS\BarbeiroDTO;
use Illuminate\Database\Eloquent\Collection;

interface BarbeiroRepositoryInterface
{
    public function existeBarbeiro(int $id_barbeiro): bool;
    public function salvarBarbeiro(BarbeiroDTO $barbeiroDto): int;
    public function PerfilBarbeiro(int $id_barbeiro): object | bool;
    public function listar(BarbeiroAtributosFiltrosPaginacaoDTO $barbeiroDTO): Collection;
     public function detalhes(int $id_barbeiro): object;
            
}