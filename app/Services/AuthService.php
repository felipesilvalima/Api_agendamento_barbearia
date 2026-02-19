<?php declare(strict_types=1); 

namespace App\Services;

use App\DTOS\LoginDTO;
use App\Exceptions\AutenticacaoException;
use App\Exceptions\ConflitoExecption;
use App\Exceptions\ErrorInternoException;
use App\Exceptions\NaoExisteRecursoException;
use App\Jobs\EnviarEmailTrocarDeSenhaJobs;
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
        private ValidarDomainService $validarService,
    
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

    public function perfilUser(User $user): object
    {
        if($user->role === 'cliente')
        {
            $perfil = $this->clienteRepository->PerfilCliente($user->cliente->id);
        }
            elseif($user->role === 'barbeiro')
            {
                $perfil = $this->barbeiroRepository->PerfilBarbeiro($user->barbeiro->id); 
            }

                if(collect($perfil)->isEmpty())
                {
                    throw new  NaoExisteRecursoException("Perfil não encontrado. Usuário não existe");
                }

                return $perfil;
    }

    public function updatePassword(array $password, User $user)
    {
        
        $this->validarService->validarExistenciaUsuario($user->id, "Não foi possivel atualizar a senha. Usuário não existe");
        
        if (!Hash::check($password['password'], $user->password)) 
        {
            $user->password = Hash::make($password['password']);

            EnviarEmailTrocarDeSenhaJobs::dispatch($user);
            
            $user->save();
        }
            else
            {
                throw new ConflitoExecption("Digite uma nova senha");
            }

    }

    public function update(array $data, User $user)
    {
        
        $this->validarService->validarExistenciaUsuario($user->id, "Não foi possivel atualizar a senha. Usuário não existe");
        
        $user->fill([
            'name' => mb_convert_case($data['name'] , MB_CASE_TITLE,'UTF-8') ?? $user->name
        ]);

            if(!$user->isDirty(['name']))
            {
                throw new ConflitoExecption("Nenhum dado foi alterado. Digite novos dados");
            }

                $user->save();

                    if(!$user)
                    {
                        throw new ErrorInternoException("Error ao atualizar dados de usuário");
                    }

    }

    public function delete(User $user)
    {
        
        $this->validarService->validarExistenciaUsuario($user->id, "Não e possivel deleta. Esse Usuário não existe");

        if($user->role === 'cliente')
        { 
            
            $this->validarService->validarExistenciaCliente($user->cliente->id,"Não e possivel deleta. Esse Cliente não existe");

            $user->cliente->status = 'INATIVO';
            $user->cliente->save();
        }
            elseif($user->role === 'barbeiro')
            {
                $this->validarService->validarExistenciaBarbeiro($user->barbeiro->id,"Não e possivel deleta. Esse Barbeiro não existe");
                
                $user->barbeiro->status = 'INATIVO';
                $user->barbeiro->save();
            }
        
            $user->delete();

            if(!$user)
            {
                throw new ErrorInternoException("Error interno ao remover usuário");
            }
    }

}