<?php declare(strict_types=1); 

namespace App\Repository\Contratos;

use App\DTOS\CriarClienteDtos;

interface ClienteRepositoryInterface
{
    public function verificarClienteExiste($id_cliente): bool;     
    public function salvarCliente(CriarClienteDtos $dtos): int;
    public function PerfilCliente($id_cliente): object | bool;
    
}