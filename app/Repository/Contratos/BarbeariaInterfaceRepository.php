<?php declare(strict_types=1); 

namespace App\Repository\Contratos;

use App\Models\Barbearia;
use Illuminate\Database\Eloquent\Collection;

interface BarbeariaInterfaceRepository
{
    public function listarBarbearia(): Collection;
    public function save(Barbearia $barbearia): Barbearia;
    public function removerBarbearia(int $id): bool;
    public function existeBarbearia(int $id): bool;
}