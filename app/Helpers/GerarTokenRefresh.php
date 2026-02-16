<?php declare(strict_types=1); 

namespace App\Helpers;

use App\Models\User;
use Firebase\JWT\JWT;
use Tymon\JWTAuth\Facades\JWTAuth;

trait GerarTokenRefresh
{
    public function gerarTokenRefresh(int $id_user)
    {
        $user = User::findOrFail($id_user);

        $payload = [
            "exp" => now()->addMinutes(10080)->timestamp
        ];

        $novo_token = JWTAuth::claims($payload)->fromUser($user);

        return $novo_token;
    }
}