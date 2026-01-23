<?php declare(strict_types=1); 

namespace App\Repository\Abstract;

use App\DTOS\AgendamentosAtributosFiltrosPagincaoDTO;
use Illuminate\Database\Eloquent\Model;

Abstract class BaseRepository
{
    protected $query;

    public function __construct(private  Model $model)
    {
        $this->query = $model->query();
    }

    public function findAll(AgendamentosAtributosFiltrosPagincaoDTO $agendamentoDTO): iterable
    {
            if(!is_null($agendamentoDTO->id_cliente))
            {
                if($agendamentoDTO->atributos != null)
                {
                    //atributos
                    $this->selectAtributos($agendamentoDTO->atributos);

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
                            $this->filtroRelacionamento($agendamentoDTO->filtro_barbeiro_validado);
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

                            if($agendamentoDTO->limit !== null && $agendamentoDTO->page !== null)
                            {
                               //paginacao
                              $this->paginacao($agendamentoDTO->page, $agendamentoDTO->limit);
                            }

                                $entidade = 'id_cliente';
                                $id = $agendamentoDTO->id_cliente;


            }
                else
                {
                    if($agendamentoDTO->atributos != null)
                    {
                        //atributos
                        $this->selectAtributos($agendamentoDTO->atributos);

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

                                    //filtro de barbeiro
                                    if($agendamentoDTO->filtro_cliente_validado != null)
                                    {
                                        $this->filtroRelacionamento($agendamentoDTO->filtro_cliente_validado);
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

                                                if($agendamentoDTO->limit !== null && $agendamentoDTO->page !== null)
                                                {
                                                    //paginacao
                                                    $this->paginacao($agendamentoDTO->page, $agendamentoDTO->limit);
                                                }

                                                $entidade = 'id_barbeiro';
                                                $id = $agendamentoDTO->id_barbeiro;

                }

                return $this->getResultado($entidade, $id, ['agendamento_servico.servico']);
    }


    public function selectAtributos(string $atributos)
    {
       $this->query->selectRaw($atributos);
       return $this;
    }

    public function filtro(array $filtros)
    {
        foreach($filtros as $condicao)
        {
            $f = explode(':',$condicao);
            $this->query->where($f[0],$f[1],$f[2]);
        }

        return $this;
    }

    public function selectAtributosRelacionamentos(string $atributosRelacionamento)
    {
        $this->query->with($atributosRelacionamento);
        return $this;
    }

    public function filtroRelacionamento(array $filtrosRelacionamento)
    { 
        $this->query->whereHas('barbeiro', function($b) use($filtrosRelacionamento)
        {
            foreach($filtrosRelacionamento as $condicao_relacionamento)
            {
                $cb = explode(':',$condicao_relacionamento);
                $b->where($cb[0],$cb[1],$cb[2]);
            }
        });

        return $this;
    }

    public function paginacao(string $pagina, string $limite)
    {
        $offset = ($pagina - 1) * $limite;
                            
            $this->query
            ->limit($limite)
            ->offset($offset);

            return $this;
    }

    public function getResultado(string $atributoRelacionamento, int $id, array $relacionamentos)
    {
        return $this->query
        ->with($relacionamentos)
        ->where($atributoRelacionamento, $id)
        ->get();
    }

    public function existe(int $id): bool
    {
        return $this->model
                ->where('id', $id)
                ->exists();
    }
}