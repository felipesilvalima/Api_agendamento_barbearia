<?php declare(strict_types=1); 

namespace App\Repository\Contratos;

use App\DTOS\BarbeiroDTO;
use App\DTOS\ClienteDTO;
use App\DTOS\CriarClienteDtos;
use App\DTOS\LoginDTO;
use App\DTOS\LoginDtos;
use App\Entitys\BarbeiroEntity;
use App\Entitys\ClienteEntity;

interface AuthRepositoryInterface
{
    public function salvarUsuario(BarbeiroDTO | ClienteDTO $user): bool;
    public function verificarCredenciasUser(LoginDTO $credencias): bool | string;
        
}