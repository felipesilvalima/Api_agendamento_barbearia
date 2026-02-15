<?php declare(strict_types=1); 

namespace App\Repository\Eloquents;


use App\DTOS\AgendamentoDTO;
use App\DTOS\AgendamentosAtributosFiltrosPagincaoDTO;
use App\Models\Agendamento;
use App\Repository\Abstract\BaseRepository;
use App\Repository\Contratos\AgendamentosRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class EloquentAgendamentoRepository extends BaseRepository implements AgendamentosRepositoryInterface
{
    protected $query;

    public function __construct(private Agendamento $agendamentoModel)
    {
        parent::__construct($agendamentoModel);
    }

        public function existeAgendamentoHorario($id_barbeiro, $hora, $data): bool
        {
            $result = $this->agendamentoModel->
            where('id_barbeiro',$id_barbeiro)->
            where('status', 'AGENDADO')->whereTime('hora', $hora)->whereDate('data', $data)->where('barbearia_id',$this->tenant())->exists();
            return $result;
        }

        public function salvar(AgendamentoDTO $agendamentoDTO): object
        {
            return $this->agendamentoModel->create([
                'data' => $agendamentoDTO->data,
                'hora' => $agendamentoDTO->hora,
                'id_cliente' => $agendamentoDTO->clienteUser->cliente->id,
                'id_barbeiro' => $agendamentoDTO->id_barbeiro,
                'status' => $agendamentoDTO->status,
                'barbearia_id' => $agendamentoDTO->barbearia_id
            ]);
        }

        public function existeAgenda(int $id_agenda): bool
        {
            return $this->existe($id_agenda);
        }

        public function listar(AgendamentosAtributosFiltrosPagincaoDTO $agendamentoDTO): Collection
        {
            if($agendamentoDTO->user->role  === 'cliente')
            {
                if($agendamentoDTO->atributos_agendamento != null)
                {
                    //atributos
                    $this->selectAtributos('id,'.$agendamentoDTO->atributos_agendamento);

                }
                    //filtros
                    if($agendamentoDTO->filtro_agendamento_validado != null)
                    {
                      $this->filtro($agendamentoDTO->filtro_agendamento_validado);
                    }

                        $this->selectAtributosRelacionamentos('barbeiro.user:id,name');

                        if($agendamentoDTO->atributos_barbeiro != null)
                        {
                            //atributos de barbeiro
                            $this->selectAtributosRelacionamentos('barbeiro:id,'.$agendamentoDTO->atributos_barbeiro);
                        }
                            else
                            {
                                $this->selectAtributosRelacionamentos('barbeiro');
                            }

                                //filtro de barbeiro
                                if($agendamentoDTO->filtro_barbeiro_validado != null)
                                {
                                    $this->filtroRelacionamento($agendamentoDTO->filtro_barbeiro_validado,'barbeiro');
                                }

                                    //atributos servicos
                                    if($agendamentoDTO->atributos_servico != null)
                                    {
                                        $this->selectAtributosRelacionamentos('servico:id,'.$agendamentoDTO->atributos_servico);
                                    }
                                        else
                                        {
                                            $this->selectAtributosRelacionamentos('servico');
                                        }
                            
                                            //filtro servico
                                            if($agendamentoDTO->filtro_servico_validado != null)
                                            {
                                                $this->filtroRelacionamento($agendamentoDTO->filtro_servico_validado,'servico');
                                            }

                                                if($agendamentoDTO->limit !== null && $agendamentoDTO->page !== null)
                                                {
                                                    //paginacao
                                                    $this->paginacao($agendamentoDTO->page, $agendamentoDTO->limit);
                                                }

                                                $this->buscarPorEntidade($agendamentoDTO->user->cliente->id, 'id_cliente');
            }
                elseif($agendamentoDTO->user->role === 'barbeiro')
                {
                    if($agendamentoDTO->atributos_agendamento != null)
                    {
                        //atributos
                        $this->selectAtributos('id,'.$agendamentoDTO->atributos_agendamento);

                    }
                        //filtros
                        if($agendamentoDTO->filtro_agendamento_validado != null)
                        {
                            $this->filtro($agendamentoDTO->filtro_agendamento_validado);
                        }
                            $this->selectAtributosRelacionamentos('cliente.user:id,name');

                            if($agendamentoDTO->atributos_cliente != null)
                            {
                                //atributos de cliente
                                $this->selectAtributosRelacionamentos('cliente:id,'.$agendamentoDTO->atributos_cliente);
                            }
                                else
                                {
                                    $this->selectAtributosRelacionamentos('cliente');
                                }

                                    //filtro de cliente
                                    if($agendamentoDTO->filtro_cliente_validado != null)
                                    {
                                        $this->filtroRelacionamento($agendamentoDTO->filtro_cliente_validado,'cliente');
                                    }


                                        if($agendamentoDTO->atributos_servico != null)
                                        {
                                            $this->selectAtributosRelacionamentos('servico:id,'.$agendamentoDTO->atributos_servico);
                                        }
                                            else
                                            {
                                                $this->selectAtributosRelacionamentos('servico');
                                            }

                                                //filtro servico
                                                if($agendamentoDTO->filtro_servico_validado != null)
                                                {
                                                    $this->filtroRelacionamento($agendamentoDTO->filtro_servico_validado,'servico');
                                                }

                                                    if($agendamentoDTO->limit !== null && $agendamentoDTO->page !== null)
                                                    {
                                                        //paginacao
                                                        $this->paginacao($agendamentoDTO->page, $agendamentoDTO->limit);
                                                    }

                                                    $this->buscarPorEntidade($agendamentoDTO->user->barbeiro->id,'id_barbeiro');

                }
            
                return $this->getResultado();
                  
        }

        public function detalhes(int $id_agenda): object
        {
            $this->selectAtributosRelacionamentos('barbeiro');
            $this->selectAtributosRelacionamentos('cliente');
            $this->selectAtributosRelacionamentos('servico');
            $this->buscarPorEntidade($id_agenda,'id');

            return $this->firstResultado();
        }
        
}