<?php declare(strict_types=1); 

namespace App\Repository\Contratos;

use App\DTOS\BarbeiroDTO;
use App\DTOS\ClienteDTO;
use App\DTOS\LoginDTO;


interface AuthRepositoryInterface
{
    public function salvarUsuario(BarbeiroDTO | ClienteDTO $user): bool;
    public function verificarCredenciasUser(LoginDTO $credencias): bool | string;
    public function verificarExistenciaUsuario(int $id_user): bool;
        
}