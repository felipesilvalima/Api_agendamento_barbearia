<?php declare(strict_types=1); 

namespace App\Repository\Eloquents;

use App\DTOS\CriarBarbeiroDtos;
use App\DTOS\CriarClienteDtos;
use App\DTOS\LoginDtos;
use App\Entitys\BarbeiroEntity;
use App\Entitys\ClienteEntity;
use App\Models\User;
use App\Repository\Contratos\AuthRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class EloquentAuthRepository implements AuthRepositoryInterface
{
    public function __construct(private User $userModel){}


    public function salvarUsuario(BarbeiroEntity | ClienteEntity $user): bool
    {

       return $this->userModel->create([
            "email" => $user->getEmail(),
            "password" =>  Hash::make($user->getPassword()),
            "id_cliente" => $user->getId_cliente() ?? null,
            "id_barbeiro" => $user->getId_barbeiro() ?? null,
        ]);

    }

        public function verificarCredenciasUser(LoginDtos $credencias): bool | string
        {
            $token = Auth::attempt([
                "email" => $credencias->email,
                "password" => $credencias->password,
            ]);

            if($token)
            {
                return $token;
            }
                else
                {
                    return false;
                }
            
        }

}