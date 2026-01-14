<?php declare(strict_types=1); 

namespace App\Repository\Contratos;

use App\DTOS\CriarBarbeiroDtos;
use App\DTOS\CriarClienteDtos;
use App\DTOS\LoginDtos;

interface AuthRepositoryInterface
{
    public function salvarUsuario(CriarBarbeiroDtos | CriarClienteDtos $dtos): bool;
    public function verificarCredenciasUser(LoginDtos $credencias): bool | string;
        
}