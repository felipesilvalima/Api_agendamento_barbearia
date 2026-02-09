<?php declare(strict_types=1); 

namespace App\Repository\Eloquents;

use App\DTOS\AgendamentosAtributosFiltrosPagincaoDTO;
use App\DTOS\ClienteAtributosFiltrosPaginacaoDTO;
use App\Repository\Abstract\BaseRepository;
use App\DTOS\ClienteDTO;
use App\Models\Cliente;
use App\Repository\Contratos\ClienteRepositoryInterface;
use Carbon\Carbon;

class EloquentClienteRepository extends BaseRepository implements ClienteRepositoryInterface
{
    public function __construct(private Cliente $clienteModel)
    {
        parent::__construct($clienteModel);
    }

    public function existeCliente($id_cliente): bool
    {
            return $this->existe($id_cliente);
    }

        
    public function salvarCliente(ClienteDTO $clienteDto): int
    {

        $cadastro = $this->clienteModel->create([
            "nome" => $clienteDto->getNome(),
            "telefone" => $clienteDto->telefone,
            "data_cadastro" => Carbon::now(),
            "barbearia_id" => $clienteDto->barbearia_id
        ]);
        
        return $cadastro->id;
    }

    public function PerfilCliente(int $id_cliente): object | bool
    {
       return $this->clienteModel
       ->select('id','nome','telefone','data_cadastro')
       ->with(['user:id,email,id_cliente'])
       ->where('id', $id_cliente)
       ?->first();
    }

    public function listar(ClienteAtributosFiltrosPaginacaoDTO $clienteDTO): object
    {
        if($clienteDTO->atributos != null)
        {
            //atributos
            $this->selectAtributos('id,'.$clienteDTO->atributos);

        }
            //atributos do user
            $this->selectAtributosRelacionamentos('user');
                
                    if($clienteDTO->atributos_barbeiro != null)
                    {
                        //atributos de barbeiro
                        $this->selectAtributosRelacionamentos('agendamento.barbeiro:id,'.$clienteDTO->atributos_barbeiro);
                    }
                        else
                        {
                           $this->selectAtributosRelacionamentos('agendamento.barbeiro');
                        }

                            if($clienteDTO->atributos_servico != null)
                            {
                                //atributos de servico
                                $this->selectAtributosRelacionamentos('agendamento.servico:id,'.$clienteDTO->atributos_servico);
                            }
                                else
                                {
                                   $this->selectAtributosRelacionamentos('agendamento.servico');
                                }

                                //atributos de agendamento
                                if($clienteDTO->atributos_agendamento != null)
                                {

                                    $this->selectAtributosRelacionamentos('agendamento:id,id_cliente,id_barbeiro,'.$clienteDTO->atributos_agendamento);
                                }
                                    else
                                    {
                                        $this->selectAtributosRelacionamentos('agendamento');
                                    }

                                        $this->buscarPorEntidade($clienteDTO->id_cliente,'clientes.id');
                                        return $this->firstResultado();
    }

    public function detalhes(int $id_cliente): object
    {
        $this->buscarPorEntidade($id_cliente,'id');
        return $this->firstResultado();
    }


    
}