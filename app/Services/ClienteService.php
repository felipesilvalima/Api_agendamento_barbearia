<?php declare(strict_types=1); 

namespace App\Services;

use App\DTOS\AtualizarClienteDTO;
use App\DTOS\ClienteAtributosFiltrosPaginacaoDTO;
use App\DTOS\ClienteDTO;
use App\Exceptions\ConflitoExecption;
use App\Exceptions\ErrorInternoException;
use App\Exceptions\NaoExisteRecursoException;
use App\Helpers\ValidarAtributos;
use App\Http\Requests\AtualizarClienteRequest;
use App\Repository\Contratos\AuthRepositoryInterface;
use App\Repository\Contratos\ClienteRepositoryInterface;
use Illuminate\Http\Client\HttpClientException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Request;

class ClienteService
{
    public function __construct(
        private ClienteRepositoryInterface $clienteRepository,
        private AuthRepositoryInterface $authRepository, 
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
        if(!$this->clienteRepository->existeCliente($clienteDTO->id_cliente))
        {
            throw new NaoExisteRecursoException("Não e possivel listar. Esse cliente não existe");
        }

        $atributosClientePermitidos = ['id','nome','telefone','data_cadastro','status'];
        $atributosAgendamentoPermitidos = ['id','data','hora','status','id_barbeiro','id_cliente'];
        $atributosBarbeiroPermitido = ['id','nome','telefone','status','especialidade'];
        $atributosServicoPermitido = ['id','nome','descricao','duracao_minutos','preco'];
    
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
        if(!$this->clienteRepository->existeCliente($id_cliente))
        {
            throw new NaoExisteRecursoException("Não e possivel listar. Esse cliente não existe");
        }

         $detalhes = $this->clienteRepository->detalhes($id_cliente);

         return $detalhes;
    }

    public function atualizar(AtualizarClienteDTO $atualizarClienteDTO)
    {
        if(!$this->clienteRepository->existeCliente($atualizarClienteDTO->cliente->id))
        {
            throw new NaoExisteRecursoException("Não e possivel atualizar. Esse cliente não existe");
        }

        if($atualizarClienteDTO->nome === null && $atualizarClienteDTO->telefone === null)
        {
            throw new HttpResponseException(response()->json([
                'status' => 'error',
                'mensagem' => 'Payload de dados vázio'
            ],422));
        }

        if (!
            ($atualizarClienteDTO->telefone === (int)$atualizarClienteDTO->cliente->telefone ||
            $atualizarClienteDTO->nome === (string)$atualizarClienteDTO->cliente->nome)
        ) 
        {

           $cliente = $atualizarClienteDTO->cliente->fill([
                'nome' => $atualizarClienteDTO->nome ?? $atualizarClienteDTO->cliente->nome,
                'telefone' => (int)$atualizarClienteDTO->telefone ?? $atualizarClienteDTO->cliente->telefone
            ]);

            $cliente->save();

            if(!$cliente)
            {
                throw new ErrorInternoException("Error ao atualizar dados de cliente");
            }
        }
            else
            {
                throw new ConflitoExecption("Digite dados novos");
            }

    
    }

}