<?php declare(strict_types=1); 

namespace App\Repository\Eloquents;


use App\DTOS\AgendamentoDTO;
use App\DTOS\AgendamentosAtributosFiltrosPagincaoDTO;
use App\Models\Agendamento;
use App\Repository\Abstract\BaseRepository;
use App\Repository\Contratos\AgendamentosRepositoryInterface;

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
            where('status', 'AGENDADO')->whereTime('hora', $hora)->whereDate('data', $data)->exists();
            return $result;
        }

        public function salvar(AgendamentoDTO $agendamentoDTO): object
        {
            return $this->agendamentoModel->create([
                'data' => $agendamentoDTO->data,
                'hora' => $agendamentoDTO->hora,
                'id_cliente' => $agendamentoDTO->id_cliente,
                'id_barbeiro' => $agendamentoDTO->id_barbeiro,
                'status' => $agendamentoDTO->status
            ]);
        }

        public function existeAgenda(int $id_agenda): bool
        {
            return $this->existe($id_agenda);
        }

        public function listar(AgendamentosAtributosFiltrosPagincaoDTO $agendamentoDTO): iterable
        {
            if(!is_null($agendamentoDTO->id_cliente))
            {
                if($agendamentoDTO->atributos != null)
                {
                    //atributos
                    $this->selectAtributos('id,'.$agendamentoDTO->atributos);

                }
                    //filtros
                    if($agendamentoDTO->filtro_validado != null)
                    {
                      $this->filtro($agendamentoDTO->filtro_validado);
                    }

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

                        //atributos de cliente
                        if($agendamentoDTO->atributos_cliente != null)
                        {
                            $this->selectAtributosRelacionamentos('cliente:id,'.$agendamentoDTO->atributos_cliente);      
                        }
                            else
                            {
                                $this->selectAtributosRelacionamentos('cliente');
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

                            $this->buscarPorUsuario($agendamentoDTO->id_cliente, 'id_cliente');
            }
                else
                {
                    if($agendamentoDTO->atributos != null)
                    {
                        //atributos
                        $this->selectAtributos('id,'.$agendamentoDTO->atributos);

                    }
                        //filtros
                        if($agendamentoDTO->filtro_validado != null)
                        {
                            $this->filtro($agendamentoDTO->filtro_validado);
                        }

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

                                        if($agendamentoDTO->atributos_barbeiro != null)
                                        {
                                            //atributos de cliente
                                            $this->selectAtributosRelacionamentos('barbeiro:id,'.$agendamentoDTO->atributos_cliente);      
                                        }
                                            else
                                            {
                                                $this->selectAtributosRelacionamentos('barbeiro');
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

                                                $this->buscarPorUsuario($agendamentoDTO->id_barbeiro,'id_barbeiro');

                }
            
                return $this->getResultado();
                  
        }

        public function detalhes(int $id_agenda): object
        {
            $listaAgendas = $this->agendamentoModel
            ->with(['barbeiro','cliente','servico'])
            ->where('id',$id_agenda)
            ?->first();

            return $listaAgendas;
        }
        
}