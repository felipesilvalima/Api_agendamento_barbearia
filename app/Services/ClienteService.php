<?php declare(strict_types=1); 

namespace App\Services;

use App\DTOS\ClienteAtributosFiltrosPaginacaoDTO;
use App\DTOS\ClienteDTO;
use App\Exceptions\ErrorInternoException;
use App\Exceptions\NaoExisteRecursoException;
use App\Helpers\ValidarAtributos;
use App\Repository\Contratos\AuthRepositoryInterface;
use App\Repository\Contratos\ClienteRepositoryInterface;
use Illuminate\Support\Facades\DB;

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

}