<?php declare(strict_types=1); 

namespace App\Repository\Contratos;

use App\DTOS\BarbeariaFiltroDTO;
use App\Models\Barbearia;
use Illuminate\Database\Eloquent\Collection;

interface BarbeariaInterfaceRepository
{
    public function listarBarbearia(BarbeariaFiltroDTO $barbeariaFiltroDto): Collection;
    public function save(Barbearia $barbearia): Barbearia;
    public function existeBarbearia(int $id): bool;
    public function detalhesBarbearia(int $id): object;
}