<?php declare(strict_types=1); 

namespace App\Repository\Eloquents;

use App\DTOS\BarbeiroDTO;
use App\DTOS\ClienteDTO;
use App\DTOS\LoginDTO;
use App\Models\User;
use App\Repository\Abstract\BaseRepository;
use App\Repository\Contratos\AuthRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class EloquentAuthRepository extends BaseRepository implements AuthRepositoryInterface
{
    public function __construct(private User $userModel)
    {
        parent::__construct($userModel);
    }


    public function salvarUsuario(BarbeiroDTO | ClienteDTO $user): int
    {

      $user = $this->userModel->create([
            "email" => $user->email,
            "password" =>  Hash::make($user->password),
            "barbearia_id" => $user->barbearia_id,
            "role" => $user->role ?? null
        ]);

        return $user->id;

    }

    public function verificarCredenciasUser(LoginDTO $credencias): bool | string
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

    public function existeUsuario(int $id_user): bool
    {
        return $this->existe($id_user);
    }

        

}