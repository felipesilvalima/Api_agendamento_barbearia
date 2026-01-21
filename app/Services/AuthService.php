<?php declare(strict_types=1); 

namespace App\Services;

use App\DTOS\LoginDTO;
use App\Exceptions\AutenticacaoException;
use App\Exceptions\ConflitoExecption;
use App\Exceptions\NaoExisteRecursoException;
use App\Models\User;
use App\Repository\Contratos\AuthRepositoryInterface;
use App\Repository\Contratos\BarbeiroRepositoryInterface;
use App\Repository\Contratos\ClienteRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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
            else
            {
                $user = Auth('api')->user();
                
                if(Hash::check($credencias->password, $user->password))
                {
                    
                    if(!Hash::needsRehash($user->password))
                    {
                        $user->password = Hash::make($credencias->password);
                        $user->save();
                    }
        
                }
        
                return $response;
            }

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

    public function update(array $password, User $user)
    {
        
        if(!$this->authRepository->verificarExistenciaUsuario($user->id))
        {
            throw new NaoExisteRecursoException("Não foi possivel atualizar a senha. Usuário não existe");
        }
        
        if (!Hash::check($password['password'], $user->password)) 
        {
            $user->password = Hash::make($password['password']);
            $user->save();
        }
            else
            {
                throw new ConflitoExecption("Digite uma nova senha");
            }

    }

    public function delete(User $user)
    {
        if(!$this->authRepository->verificarExistenciaUsuario($user->id))
        {
            throw new NaoExisteRecursoException("Não e possivel deleta. Esse Usuário não existe");
        }

        if(!is_null($user->id_cliente))
        {
            if(!$this->clienteRepository->verificarClienteExiste($user->id_cliente))
            {
                throw new NaoExisteRecursoException("Não e possivel deleta. Esse Cliente não existe");
            } 

            $user->cliente->status = 'INATIVO';
            $user->cliente->save();
        }
            else
            {
                if(!$this->barbeiroRepository->verificarBarbeiroExiste($user->id_barbeiro))
                {
                    throw new NaoExisteRecursoException("Não e possivel deleta. Esse Barbeiro não existe");
                }
                
                $user->barbeiro->status = 'INATIVO';
                $user->barbeiro->save();
            }

        $user->delete();
    }

}