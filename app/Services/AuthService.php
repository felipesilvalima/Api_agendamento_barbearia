<?php declare(strict_types=1); 

namespace App\Services;

use App\DTOS\LoginDtos;
use App\Exceptions\AutenticacaoException;
use App\Exceptions\NaoExisteRecursoException;
use App\Repository\AuthRepository;
use App\Repository\BarbeiroRepository;
use App\Repository\ClienteRepository;
use Illuminate\Http\Exceptions\HttpResponseException;



class AuthService
{
    public function __construct(
        private AuthRepository $authRepository,
        private ClienteRepository $clienteRepository,
        private BarbeiroRepository $barbeiroRepository,
    
    ){}


    public function logarUsuario(LoginDtos $credencias)
    {
        $response = $this->authRepository->verificarCredenciasUser($credencias);

        if(!$response)
        {
          throw new AutenticacaoException();
        }

        return $response;
    }

    public function perfilUser(object $id_user)
    {
        if(!is_null($id_user->id_cliente))
        {
            $perfil = $this->clienteRepository->PerfilCliente($id_user->id_cliente);
        }
            else
            {
                $perfil = $this->barbeiroRepository->PerfilBarbeiro($id_user->id_barbeiro);   
            }

                if(empty($perfil))
                {
                    throw new  NaoExisteRecursoException("Perfil não encontrado. Usuário não existe");
                }

                return $perfil;
    }

}