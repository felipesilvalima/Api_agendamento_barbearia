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

                    public function precoTotalTodosAgendamentos($cliente_id = null, $barbeiro_id = null): float
                    {
                        $agendamentos = $this->agendamentoModel
                        ->select('id','id_cliente','id_barbeiro')
                        ->with([
                            'agendamento_servico:id,id_agendamento,id_servico',
                            'agendamento_servico.servico:id,preco'
                        ])
                        ->when($cliente_id, function($c) use($cliente_id){
                            $c->where('id_cliente',$cliente_id);
                        })
                        ->when($barbeiro_id, function($b) use($barbeiro_id){
                            $b->Where('id_barbeiro', $barbeiro_id);
                        })
                        ->where('status','AGENDADO')
                        ->get();

                        $total = $agendamentos->collect()
                        ->flatMap(fn($as) => $as->agendamento_servico)
                        ->sum(fn($s) => $s->servico->preco);

                        return $total;
                    }

                        public function precoTotalAgendamento(int $id_agendamento): float
                        {
                            $agendamento = $this->agendamentoModel
                            ->select('id','id_cliente')
                            ->with([
                                'agendamento_servico:id,id_agendamento,id_servico',
                                'agendamento_servico.servico:id,preco'
                            ])
                            ->where('id', $id_agendamento)
                            ?->first();
                            
                            $total = $agendamento->agendamento_servico->sum(fn($as) => $as->servico->preco);

                            return $total;
                        }

                            public function listaAgendasCliente(?int $id_cliente): ?object
                            {
                                $listaAgendas = $this->agendamentoModel
                                ->select('id','data','hora','status','id_cliente','id_barbeiro')->where('id_cliente', $id_cliente)
                                ->with([
                                    'barbeiro:id,nome,especialidade,status',
                                    'agendamento_servico:id,id_agendamento,id_servico',
                                    'agendamento_servico.servico:id,nome,descricao,duracao_minutos,preco'
                                ])
                                ->where('status','AGENDADO')
                                ->get();

                                    return $listaAgendas;
                            }

                                public function listaAgendasBarbeiro(?int $barbeiro_id): ?object
                                {
                                    $listaAgendas = $this->agendamentoModel
                                    ->select('id','data','hora','status','id_cliente','id_barbeiro')->where('id_barbeiro', $barbeiro_id)
                                    ->with([
                                        'cliente:id,nome,telefone',
                                        'agendamento_servico:id,id_agendamento,id_servico',
                                        'agendamento_servico.servico:id,nome,descricao,duracao_minutos,preco'
                                        ])
                                        ->where('status', 'AGENDADO')
                                        ->get();

                                        return $listaAgendas;
                                }

                                    public function buscarAgendaCliente(int $id_agenda): ?object
                                    {
                                        $listaAgendas = $this->agendamentoModel
                                        ->select('id','data','hora','status','id_cliente','id_barbeiro')
                                        ->where('id',$id_agenda)
                                        ->with([
                                            'barbeiro:id,nome,especialidade,status',
                                            'agendamento_servico:id,id_agendamento,id_servico',
                                            'agendamento_servico.servico:id,nome,descricao,duracao_minutos,preco',
                                            
                                           ])
                                           ?->first();

                                            return $listaAgendas;
                                    }

                                        public function buscarAgendaBarbeiro(int $id_agenda): ?object
                                        {
                                            $listaAgendas = $this->agendamentoModel
                                            ->select('id','data','hora','status','id_cliente','id_barbeiro')
                                            ->where('id',$id_agenda)
                                            ->with([
                                                'cliente:id,nome,telefone',
                                                'agendamento_servico:id,id_agendamento,id_servico',
                                                'agendamento_servico.servico:id,nome,descricao,duracao_minutos,preco',
                                            ])?->first();

                                                return $listaAgendas;
                                        }

        
}