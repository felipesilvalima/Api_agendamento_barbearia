<?php declare(strict_types=1); 

namespace App\Repository\Contratos;

use App\DTOS\CriarClienteDtos;
use App\DTOS\LoginDtos;
use App\Entitys\BarbeiroEntity;
use App\Entitys\ClienteEntity;

interface AuthRepositoryInterface
{
    public function salvarUsuario(BarbeiroEntity | ClienteEntity $user): bool;
    public function verificarCredenciasUser(LoginDtos $credencias): bool | string;
        
}