<?php declare(strict_types=1); 

namespace App\Repository\Contratos;

use App\DTOS\CriarClienteDtos;
use App\Entitys\ClienteEntity;

interface ClienteRepositoryInterface
{
    public function verificarClienteExiste($id_cliente): bool;     
    public function salvarCliente(ClienteEntity $cliente): int;
    public function PerfilCliente($id_cliente): object | bool;
    
}