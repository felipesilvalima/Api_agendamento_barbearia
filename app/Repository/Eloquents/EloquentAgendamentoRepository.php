<?php declare(strict_types=1); 

namespace App\Repository\Eloquents;

use App\DTOS\AgendamentoDTO;
use App\Models\Agendamento;

use App\Repository\Contratos\AgendamentosRepositoryInterface;

class EloquentAgendamentoRepository implements AgendamentosRepositoryInterface
{
    
    public function __construct(private Agendamento $agendamentoModel){}

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
            return $this->agendamentoModel
            ->where('id', $id_agenda)
            ->exists();
        }

        public function listar(
            ?int $cliente_id,
            ?int $barbeiro_id,
            ?string $atributos,
            ?string $atributos_barbeiro,
            ?string $atributos_cliente,
            ?array $condicao_atributo,
            ?array $condicao_atributo_barbeiro,
            ?array $condicao_atributo_cliente,
        ): iterable
        {
            $agendamentos = [];
            
            $listaAgendas = $this->agendamentoModel->query();

            if(!is_null($cliente_id))
            {
                if($atributos != null)
                {
                   $agendamentos = $listaAgendas->selectRaw($atributos);

                   
                    if($condicao_atributo != null)
                    {
                        $agendamentos = $listaAgendas->where($condicao_atributo[0],$condicao_atributo[1],$condicao_atributo[2]);
                    }
                }
                
                $agendamentos = $listaAgendas
                ->with(['barbeiro','cliente','agendamento_servico.servico'])
                ->where('id_cliente', $cliente_id)
                ->get();

                    if($atributos_barbeiro != null)
                    {
                        $agendamentos = $listaAgendas->with('barbeiro:id,'.$atributos_barbeiro);
                        
                        if($condicao_atributo_barbeiro != null)
                        {
                            $agendamentos = $listaAgendas->whereHas('barbeiro', function($b) use($condicao_atributo_barbeiro)
                            {
                                $b->where($condicao_atributo_barbeiro[0],$condicao_atributo_barbeiro[1],$condicao_atributo_barbeiro[2]);
                            });
                        }
                    }

                    $agendamentos = $listaAgendas
                    ->where('id_cliente', $cliente_id)
                    ->get();

                        if($atributos_cliente != null)
                        {
                            $agendamentos = $listaAgendas->with('cliente:id,'.$atributos_cliente);
                            
                            if($condicao_atributo_cliente != null)
                            {
                                $agendamentos = $listaAgendas->whereHas('cliente', function($c) use($condicao_atributo_cliente)
                                {
                                    $c->where($condicao_atributo_cliente[0],$condicao_atributo_cliente[1],$condicao_atributo_cliente[2]);
                                });
                            }

                        }

                        $agendamentos = $listaAgendas
                        ->where('id_cliente', $cliente_id)
                        ->get();
            }
                else
                {
                    if($atributos != null)
                    {
                       $agendamentos = $listaAgendas->selectRaw($atributos);

                        if($condicao_atributo != null)
                        {
                            $agendamentos = $listaAgendas->where($condicao_atributo[0],$condicao_atributo[1],$condicao_atributo[2]);
                        }
                    }

                    $agendamentos = $listaAgendas
                    ->with(['barbeiro','cliente','agendamento_servico.servico'])
                    ->where('id_barbeiro', $barbeiro_id)
                    ->get();

                        if($atributos_barbeiro != null)
                        {
                            $agendamentos = $listaAgendas->with('barbeiro:id,'.$atributos_barbeiro);
                            
                            if($condicao_atributo_barbeiro != null)
                            {
                                $agendamentos = $listaAgendas->whereHas('barbeiro', function($b) use($condicao_atributo_barbeiro)
                                {
                                    $b->where($condicao_atributo_barbeiro[0],$condicao_atributo_barbeiro[1],$condicao_atributo_barbeiro[2]);
                                });
                            }
                        }

                        $agendamentos = $listaAgendas
                        ->where('id_barbeiro', $barbeiro_id)
                        ->get();

                            if($atributos_cliente != null)
                            {
                                $agendamentos = $listaAgendas->with('cliente:id,'.$atributos_cliente); 
                                
                                if($condicao_atributo_cliente != null)
                                {
                                    $agendamentos = $listaAgendas->whereHas('cliente', function($c) use($condicao_atributo_cliente)
                                    {
                                        $c->where($condicao_atributo_cliente[0],$condicao_atributo_cliente[1],$condicao_atributo_cliente[2]);
                                    });
                                }
                            }

                            $agendamentos = $listaAgendas
                            ->where('id_barbeiro', $barbeiro_id)
                            ->get();
                }

            return $agendamentos;
        }

        public function detalhes(int $id_agenda): object
        {
            $listaAgendas = $this->agendamentoModel
            ->with(['barbeiro','cliente','agendamento_servico','agendamento_servico.servico'])
            ->where('id',$id_agenda)
            ?->first();

            return $listaAgendas;
        }
        
}