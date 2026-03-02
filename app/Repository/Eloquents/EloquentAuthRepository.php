<?php declare(strict_types=1); 

namespace App\Repository\Eloquents;

use App\DTOS\BarbeiroDTO;
use App\DTOS\ClienteDTO;
use App\DTOS\LoginDTO;
use App\Exceptions\AutenticacaoException;
use App\Exceptions\NaoPermitidoExecption;
use App\Models\User;
use App\Repository\Abstract\BaseRepository;
use App\Repository\Contratos\AuthRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Enums\StatusUser;
use App\Enums\StatusBarbearia;

class EloquentAuthRepository extends BaseRepository implements AuthRepositoryInterface
{
    public function __construct(private User $userModel)
    {
        parent::__construct($userModel);
    }


    public function salvarUsuario(BarbeiroDTO | ClienteDTO $user): int
    {

      $user = $this->userModel->create([
            "name" => $user->getNome(),
            "email" => $user->email,
            "password" =>  Hash::make($user->password),
            "barbearia_id" => $user->id_barbearia,
            "role" => $user->role ?? null,
            "status" => StatusUser::ATIVO
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
            if (auth('api')->user()->barbearia->status !== StatusBarbearia::ATIVO) {
                throw new NaoPermitidoExecption("Barbearia inativa",403);
            }

            if (auth('api')->user()->status !== StatusUser::ATIVO) {
               throw new  NaoPermitidoExecption("Usuário inativo",403);
            }


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