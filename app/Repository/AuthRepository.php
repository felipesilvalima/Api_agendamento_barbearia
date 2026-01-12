<?php declare(strict_types=1); 

namespace App\Repository;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthRepository
{
    public function __construct(private User $userModel){}


    public function salvarUsuario(array $data, $cliente_id = null, $barbeiro_id = null): bool
    {

       return $this->userModel->create([
            "email" => $data['email'],
            "password" =>  Hash::make($data['password']),
            "id_cliente" => $cliente_id,
            "id_barbeiro" => $barbeiro_id,
        ]);

    }

        public function verificarCredenciasUser(array $credencias): bool | string
        {
            $token = Auth::attempt($credencias);

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