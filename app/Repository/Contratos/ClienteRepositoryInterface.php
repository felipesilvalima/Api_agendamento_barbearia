<?php declare(strict_types=1); 

namespace App\Repository\Contratos;

use App\DTOS\ClienteDTO;


interface ClienteRepositoryInterface
{
    public function existeCliente(int $id_cliente): bool;     
    public function salvarCliente(ClienteDTO $clienteDto): int;
    public function PerfilCliente(int $id_cliente): object | bool;
    
}