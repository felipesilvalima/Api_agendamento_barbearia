<?php declare(strict_types=1); 

namespace App\Repository\Contratos;

use App\DTOS\BarbeariaDTO;
use App\Models\Barbearia;

interface BarbeariaInterfaceRepository
{
    public function listarBarbearia(): Barbearia;
    public function save(Barbearia $barbearia): Barbearia;
    public function removerBarbearia(int $id): bool;
    public function existeBarbearia(int $id): bool;
}