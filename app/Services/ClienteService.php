<?php declare(strict_types=1); 

namespace App\Services;

use App\DTOS\AtualizarClienteDTO;
use App\DTOS\ClienteAtributosFiltrosPaginacaoDTO;
use App\DTOS\ClienteDTO;
use App\Exceptions\ConflitoExecption;
use App\Exceptions\ErrorInternoException;
use App\Exceptions\NaoExisteRecursoException;
use App\Exceptions\NaoPermitidoExecption;
use App\Helpers\AgendamentoConfig;
use App\Helpers\ValidarAtributos;
use App\Helpers\CacheData;
use App\Repository\Contratos\AuthRepositoryInterface;
use App\Repository\Contratos\BarbeariaInterfaceRepository;
use App\Repository\Contratos\ClienteRepositoryInterface;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\DB;
use App\Enums\StatusBarbearia;


class ClienteService
{
    use CacheData;
    use ValidarAtributos;
    use AgendamentoConfig;

    public function __construct(
        private ClienteRepositoryInterface $clienteRepository,
        private AuthRepositoryInterface $authRepository,
        private ValidarDomainService $validarService,
        private BarbeariaInterfaceRepository $barbeariaRepository,
    ){}

    public function CadastrarCliente(ClienteDTO $clienteDto): void
    {
        $this->validarService->validarExistenciaBarbearia($clienteDto->id_barbearia, "Não e possivel criar cliente essa barbearia não existe");
        $barbearia = $this->barbeariaRepository->detalhesBarbearia($clienteDto->id_barbearia);

        if($barbearia->status !== StatusBarbearia::ATIVO)
        {
            throw new NaoPermitidoExecption("Não e possivel criar um usuário para essa barbearia. Barbearia inativa");
        }

        DB::transaction(function () use($clienteDto) { 

            $clienteDto->id_cliente = $this->authRepository->salvarUsuario($clienteDto);
            
            if(!$clienteDto->id_cliente)
            {
                throw new ErrorInternoException("error ao criar usuário de cliente");
            }
                    
            $this->clienteRepository->salvarCliente($clienteDto);


        });
    }

    public function listar(ClienteAtributosFiltrosPaginacaoDTO $clienteDTO)
    {
        $this->validarService->validarExistenciaCliente($clienteDTO->id_cliente, "Não e possivel listar. Esse cliente não existe");

        //atributos 
        foreach($this->regras() as $campoDto => $atributosPermitidos)
        {
            $clienteDTO->$campoDto =  $this->validarAtributos($clienteDTO->$campoDto, $atributosPermitidos['atributos']);
        }

        $cacheKey = 'clientes-user-'. auth('api')->user()->id.'-list';
        return $this->verificarCache($cacheKey);

        $lista = $this->clienteRepository->listar($clienteDTO);

            if(collect($lista)->isEmpty())
            {
                throw new NaoExisteRecursoException("Listar de clientes vázia");
            }
                $this->adicionarCache($cacheKey, $lista,getenv('JWT_TTL'));

                return $lista;
    }

    public function detalhes(int $id_cliente)
    {
         $cacheKey = 'clientes-user-'. auth('api')->user()->id.'-details';
        return $this->verificarCache($cacheKey);

         $detalhes = $this->clienteRepository->detalhes($id_cliente);

         $this->adicionarCache($cacheKey, $detalhes,getenv('JWT_TTL'));

         return $detalhes;
    }

    public function atualizar(AtualizarClienteDTO $atualizarClienteDTO)
    {

        $this->validarService->validarExistenciaCliente($atualizarClienteDTO->cliente->id, "Não e possivel atualizar. Esse cliente não existe");
        
        $cliente = $atualizarClienteDTO->cliente->fill([
            'telefone' => $atualizarClienteDTO->telefone ?? $atualizarClienteDTO->cliente->telefone
        ]);

            if(!$cliente->isDirty(['telefone']))
            {
                throw new ConflitoExecption("Nenhum dado foi alterado. Digite novos dados");
            }

                $cliente->save();

                    if(!$cliente)
                    {
                        throw new ErrorInternoException("Error ao atualizar dados de cliente");
                    }
    }

}