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
    public function __construct(
        private ClienteRepositoryInterface $clienteRepository,
        private AuthRepositoryInterface $authRepository,
        private ValidarDomainService $validarService, 
    ){}

    public function CadastrarCliente(ClienteDTO $clienteDto): void
    {
       
        DB::transaction(function () use($clienteDto) { 
 
            $clienteDto->id_cliente = $this->clienteRepository->salvarCliente($clienteDto);

                if(!$clienteDto->id_cliente)
                {
                    throw new ErrorInternoException("error ao criar cliente");
                }
                 

                $this->authRepository->salvarUsuario($clienteDto);

        });
    }

    public function listar(ClienteAtributosFiltrosPaginacaoDTO $clienteDTO)
    {
        $this->validarService->validarExistenciaCliente($clienteDTO->id_cliente, "Não e possivel listar. Esse cliente não existe");

        $atributosClientePermitidos = ['id','nome','telefone','data_cadastro','status','barbearia_id'];
        $atributosAgendamentoPermitidos = ['id','data','hora','status','id_barbeiro','id_cliente','barbearia_id'];
        $atributosBarbeiroPermitido = ['id','nome','telefone','status','especialidade','barbearia_id'];
        $atributosServicoPermitido = ['id','nome','descricao','duracao_minutos','preco','barbearia_id'];
    
        //atributos 
        $clienteDTO->atributos =  ValidarAtributos::validarAtributos($clienteDTO->atributos, $atributosClientePermitidos);
        $clienteDTO->atributos_agendamento =  ValidarAtributos::validarAtributos($clienteDTO->atributos_agendamento, $atributosAgendamentoPermitidos);
        $clienteDTO->atributos_barbeiro =  ValidarAtributos::validarAtributos($clienteDTO->atributos_barbeiro, $atributosBarbeiroPermitido);
        $clienteDTO->atributos_servico =  ValidarAtributos::validarAtributos($clienteDTO->atributos_servico, $atributosServicoPermitido);

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

        if($atualizarClienteDTO->nome === null && $atualizarClienteDTO->telefone === null)
        {
            throw new HttpResponseException(response()->json([
                'status' => 'error',
                'mensagem' => 'Payload de dados vázio'
            ],422));
        }

           $cliente = $atualizarClienteDTO->cliente->fill([
                'nome' => $atualizarClienteDTO->nome ?? $atualizarClienteDTO->cliente->nome,
                'telefone' => $atualizarClienteDTO->telefone ?? $atualizarClienteDTO->cliente->telefone
            ]);

                if(!$cliente->isDirty(['nome','telefone']))
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