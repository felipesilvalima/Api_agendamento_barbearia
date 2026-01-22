<?php declare(strict_types=1); 

namespace App\Repository\Abstract;

use App\DTOS\AgendamentosAtributosFiltrosPagincaoDTO;
use Illuminate\Database\Eloquent\Model;

Abstract class BaseRepository
{
    public function __construct(private  Model $model){}

    public function findAll(AgendamentosAtributosFiltrosPagincaoDTO $agendamentoDTO): iterable
    {
            $agendamentos = [];
            
            $listaAgendas = $this->model->query();

            if(!is_null($agendamentoDTO->id_cliente))
            {
                if($agendamentoDTO->atributos != null)
                {
                   $agendamentos = $listaAgendas->selectRaw($agendamentoDTO->atributos );

                   
                    if($agendamentoDTO->filtro_validado != null)
                    {
                        foreach($agendamentoDTO->filtro_validado as $condicao)
                        {
                            $f = explode(':',$condicao);
                            $agendamentos = $listaAgendas->where($f[0],$f[1],$f[2]);
                        }
                    }
                }
                    
                    $agendamentos = $listaAgendas
                    ->with(['barbeiro','cliente','agendamento_servico.servico'])
                    ->where('id_cliente', $agendamentoDTO->id_cliente)
                    ->get();
                    
                        if($agendamentoDTO->atributos_barbeiro != null)
                        {
                            $agendamentos = $listaAgendas->with('barbeiro:id,'.$agendamentoDTO->atributos_barbeiro);
                        
                            if($agendamentoDTO->filtro_barbeiro_validado != null)
                            {
                                $filtro_barbeiro = $agendamentoDTO->filtro_barbeiro_validado;

                                $agendamentos = $listaAgendas->whereHas('barbeiro', function($b) use($filtro_barbeiro)
                                {
                                    foreach($filtro_barbeiro as $condicao_barbeiro)
                                    {
                                        $cb = explode(':',$condicao_barbeiro);
                                        $b->where($cb[0],$cb[1],$cb[2]);
                                    }
                                });
                            }
                        }

                            if($agendamentoDTO->atributos_cliente != null)
                            {
                                $agendamentos = $listaAgendas->with('cliente:id,'.$agendamentoDTO->atributos_cliente);        
                            }
                            
                            if($agendamentoDTO->limit !== null && $agendamentoDTO->page !== null)
                            {
                                $offset = ($agendamentoDTO->page- 1) * $agendamentoDTO->limit;
                            
                                $agendamentos = $listaAgendas
                                ->limit($agendamentoDTO->limit)
                                ->offset($offset);
                            }

                                $agendamentos = $listaAgendas
                                ->where('id_cliente', $agendamentoDTO->id_cliente)
                                ->get();
            }
                else
                {
                    if($agendamentoDTO->atributos != null)
                    {
                       $agendamentos = $listaAgendas->selectRaw($agendamentoDTO->atributos);

                        if($agendamentoDTO->filtro_validado != null)
                        {
                            foreach($agendamentoDTO->filtro_validado as $condicao)
                            {
                                $f = explode(':',$condicao);
                                $agendamentos = $listaAgendas->where($f[0],$f[1],$f[2]);
                            }

                        }
                    }

                        $agendamentos = $listaAgendas
                        ->with(['barbeiro','cliente','agendamento_servico.servico'])
                        ->where('id_barbeiro', $agendamentoDTO->id_barbeiro)
                        ->get();

                                if($agendamentoDTO->atributos_cliente != null)
                                {
                                    $agendamentos = $listaAgendas->with('cliente:id,'.$agendamentoDTO->atributos_cliente); 
                                
                                    if($agendamentoDTO->filtro_cliente_validado != null)
                                    {
                                        $filtro_cliente = $agendamentoDTO->filtro_cliente_validado;

                                        $agendamentos = $listaAgendas->whereHas('cliente', function($c) use($filtro_cliente)
                                        {
                                            foreach($filtro_cliente as $condicao_cliente)
                                            {
                                                $cc = explode(':',$condicao_cliente);
                                                $c->where($cc[0],$cc[1],$cc[2]);
                                            }
                                        });
                                    }
                                }

                                    if($agendamentoDTO->atributos_barbeiro != null)
                                    {
                                        $agendamentos = $listaAgendas->with('barbeiro:id,'.$agendamentoDTO->atributos_barbeiro);
                                    }

                                    if($agendamentoDTO->limit !== null && $agendamentoDTO->page !== null)
                                    {
                                        $offset = ($agendamentoDTO->page- 1) * $agendamentoDTO->limit;
                            
                                        $agendamentos = $listaAgendas
                                        ->limit($agendamentoDTO->limit)
                                        ->offset($offset);
                                    }

                                        $agendamentos = $listaAgendas
                                        ->where('id_barbeiro', $agendamentoDTO->id_barbeiro)
                                        ->get();
                }

                return $agendamentos;
    }


    public function existe(int $id): bool
    {
        return $this->model
                ->where('id', $id)
                ->exists();
    }
}