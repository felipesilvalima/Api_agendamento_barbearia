<?php declare(strict_types=1); 

namespace App\Repository;

use App\DTOS\CriarBarbeiroDtos;
use App\DTOS\CriarClienteDtos;
use App\DTOS\LoginDtos;
use App\Models\User;
use App\Repository\Contratos\AuthRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthRepository implements AuthRepositoryInterface
{
    public function __construct(private User $userModel){}


    public function salvarUsuario(CriarBarbeiroDtos | CriarClienteDtos $dtos): bool
    {

       return $this->userModel->create([
            "email" => $dtos->email,
            "password" =>  Hash::make($dtos->password),
            "id_cliente" => $dtos->id_cliente ?? null,
            "id_barbeiro" => $dtos->id_barbeiro ?? null,
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