<?php declare(strict_types=1); 

namespace App\Repository\Contratos;

use App\DTOS\ClienteAtributosFiltrosPaginacaoDTO;
use App\DTOS\ClienteDTO;
use Illuminate\Database\Eloquent\Collection;

interface ClienteRepositoryInterface
{
    public function existeCliente(int $id_cliente): bool;     
    public function salvarCliente(ClienteDTO $clienteDto): int;
    public function PerfilCliente(int $id_cliente): object | bool;
    public function listar(ClienteAtributosFiltrosPaginacaoDTO $clienteDTO): Collection;
    public function detalhes(int $id_cliente): object;
    
}