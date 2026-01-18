<?php declare(strict_types=1); 

namespace App\Services;

use App\DTOS\LoginDTO;
use App\Exceptions\AutenticacaoException;
use App\Exceptions\NaoExisteRecursoException;
use App\Repository\Contratos\AuthRepositoryInterface;
use App\Repository\Contratos\BarbeiroRepositoryInterface;
use App\Repository\Contratos\ClienteRepositoryInterface;

class AuthService
{
    public function __construct(
        private AuthRepositoryInterface $authRepository,
        private ClienteRepositoryInterface $clienteRepository,
        private BarbeiroRepositoryInterface $barbeiroRepository,
    
    ){}


    public function logarUsuario(LoginDTO $credencias): string
    {
        $response = $this->authRepository->verificarCredenciasUser($credencias);

        if(!$response)
        {
          throw new AutenticacaoException();
        }

        return $response;
    }

    public function perfilUser(object $id_user): object
    {
        if(!is_null($id_user->id_cliente))
        {
            $perfil = $this->clienteRepository->PerfilCliente($id_user->id_cliente);
        }
            else
            {
                $perfil = $this->barbeiroRepository->PerfilBarbeiro($id_user->id_barbeiro);   
            }

                if(collect($perfil)->isEmpty())
                {
                    throw new  NaoExisteRecursoException("Perfil não encontrado. Usuário não existe");
                }

                return $perfil;
    }

}