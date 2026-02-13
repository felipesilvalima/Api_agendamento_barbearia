<?php declare(strict_types=1); 

namespace App\Services;

use App\DTOS\AtualizarClienteDTO;
use App\DTOS\ClienteAtributosFiltrosPaginacaoDTO;
use App\DTOS\ClienteDTO;
use App\Exceptions\ConflitoExecption;
use App\Exceptions\ErrorInternoException;
use App\Exceptions\NaoExisteRecursoException;
use App\Helpers\ValidarAtributos;
use App\Repository\Contratos\AuthRepositoryInterface;
use App\Repository\Contratos\ClienteRepositoryInterface;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\DB;


class ClienteService
{
    use ValidarAtributos;

    public function __construct(
        private ClienteRepositoryInterface $clienteRepository,
        private AuthRepositoryInterface $authRepository,
        private ValidarDomainService $validarService, 
    ){}

    public function CadastrarCliente(ClienteDTO $clienteDto): void
    {
        $this->validarService->validarExistenciaBarbearia($clienteDto->id_barbearia, "Não e possivel criar cliente essa barbearia não existe");

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

        $regras = [
            'atributos' => ['id','user_id','telefone','data_cadastro','status','barbearia_id'],
            'atributos_barbeiro' => ['id','user_id','telefone','status','especialidade','barbearia_id'],
            'atributos_agendamento' => ['id','data','hora','status','id_barbeiro','id_cliente','barbearia_id'],
            'atributos_servico' => ['id','nome','descricao','duracao_minutos','preco','barbearia_id']
        ];
    
        //atributos 
        foreach($regras as $campoDto => $atributosPermitidos)
        {
            $clienteDTO->$campoDto =  $this->validarAtributos($clienteDTO->$campoDto, $atributosPermitidos);
        }

        $lista = $this->clienteRepository->listar($clienteDTO);

            if(collect($lista)->isEmpty())
            {
                throw new NaoExisteRecursoException("Listar de clientes vázia");
            }

                return $lista;
    }

    public function detalhes(int $id_cliente)
    {
         $detalhes = $this->clienteRepository->detalhes($id_cliente);
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