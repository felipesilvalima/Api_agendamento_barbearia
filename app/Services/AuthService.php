<?php declare(strict_types=1); 

namespace App\Services;

use App\DTOS\AgendamentosAtributosFiltrosPagincaoDTO;
use App\DTOS\LoginDTO;
use App\Exceptions\AutenticacaoException;
use App\Exceptions\ConflitoExecption;
use App\Exceptions\ErrorInternoException;
use App\Exceptions\NaoExisteRecursoException;
use App\Jobs\EnviarEmailTrocarDeSenhaJobs;
use App\Models\User;
use App\Helpers\CacheData;
use App\Repository\Contratos\AgendamentosRepositoryInterface;
use App\Repository\Contratos\AuthRepositoryInterface;
use App\Repository\Contratos\BarbeiroRepositoryInterface;
use App\Repository\Contratos\ClienteRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Enums\StatusUser;
use App\Enums\Status;
use App\Enums\Role;


class AuthService
{
    use CacheData;

    public function __construct(
        private AuthRepositoryInterface $authRepository,
        private ClienteRepositoryInterface $clienteRepository,
        private BarbeiroRepositoryInterface $barbeiroRepository,
        private ValidarDomainService $validarService,
        private AgendamentosRepositoryInterface $agendamentoRepository
    
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
        $cacheKey = 'users-user-'. auth('api')->user()->id.'-perfil';
        return $this->verificarCache($cacheKey);

        if($user->role === Role::CLIENTE)
        {
            $perfil = $this->clienteRepository->PerfilCliente($user->cliente->id);
        }
            elseif($user->role === Role::BARBEIRO)
            {
                $perfil = $this->barbeiroRepository->PerfilBarbeiro($user->barbeiro->id); 
            }

                if(collect($perfil)->isEmpty())
                {
                    throw new  NaoExisteRecursoException("Perfil não encontrado. Usuário não existe");
                }

                $this->adicionarCache($cacheKey, $perfil, getenv('JWT_TTL'));

                return $perfil;
    }

    public function updatePassword(array $password, User $user)
    {
        
        $this->validarService->validarExistenciaUsuario($user->id, "Não foi possivel atualizar a senha. Usuário não existe");
        
        if (!Hash::check($password['password'], $user->password)) 
        {
            $user->password = Hash::make($password['password']);
            $user->save();

            EnviarEmailTrocarDeSenhaJobs::dispatch($user);
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

    public function desativar(User $user)
    {
        
        $this->validarService->validarExistenciaUsuario($user->id, "Não e possivel deleta. Esse Usuário não existe");
    
        if($user->status !== StatusUser::ATIVO)
        {
            abort(404,'Esse usuário ja está Desativado');
        }

            if($user->role === Role::CLIENTE)
            { 
            
                $this->validarService->validarExistenciaCliente($user->cliente->id,"Não e possivel deleta. Esse Cliente não existe");

                $user->status = StatusUser::INATIVO;
                $id_cliente = $user->cliente->id;

                $agendamentos =  $this->agendamentoRepository->listar(
                    new AgendamentosAtributosFiltrosPagincaoDTO(
                        atributos_agendamento: 'status,id_cliente',
                        filtro_agendamento: "id_cliente:=:$id_cliente"
                    )
                );
            }
                elseif($user->role === Role::BARBEIRO)
                {
                    $this->validarService->validarExistenciaBarbeiro($user->barbeiro->id,"Não e possivel deleta. Esse Barbeiro não existe");

                    $user->status = StatusUser::INATIVO;
                    $id_barbeiro = $user->barbeiro->id;

                    $agendamentos =  $this->agendamentoRepository->listar(
                        new AgendamentosAtributosFiltrosPagincaoDTO(
                            atributos_agendamento: 'status,id_cliente',
                            filtro_agendamento: "id_barbeiro:=:$id_barbeiro"
                        )
                    );

                }

                foreach($agendamentos as $agendamento)
                {
                    if($agendamento->status === Status::AGENDADO)
                    {
                        $agendamento->status = Status::CANCELADO;
                        $agendamento->save();
                    }
                }

                $user->save();    
    }

    public function ativar(User $user)
    {
         $this->validarService->validarExistenciaUsuario($user->id, "Não e possivel deleta. Esse Usuário não existe");
    
        if($user->status !== StatusUser::INATIVO)
        {
            abort(404,'Esse usuário ja está Ativo');
        }

            if($user->role === Role::CLIENTE)
            { 
                $this->validarService->validarExistenciaCliente($user->cliente->id,"Não e possivel deleta. Esse Cliente não existe");
                $user->status = StatusUser::ATIVO;
            }
                elseif($user->role === Role::BARBEIRO)
                {
                    $this->validarService->validarExistenciaBarbeiro($user->barbeiro->id,"Não e possivel deleta. Esse Barbeiro não existe");
                    $user->status = StatusUser::ATIVO;
                }

                $user->save(); 
    }

}