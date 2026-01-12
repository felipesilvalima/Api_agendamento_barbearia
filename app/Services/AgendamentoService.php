<?php declare(strict_types=1); 

namespace App\Services;

use App\Exceptions\ErrorInternoException;
use App\Exceptions\NaoExisteRecursoException;
use App\Exceptions\NaoPermitidoExecption;
use App\Repository\AgendamentoRepository;
use App\Repository\AgendamentoServicoRepository;
use App\Repository\ServicoRepository;
use Illuminate\Support\Facades\DB;

class AgendamentoService
{
    public function __construct(
        private AgendamentoRepository $agendamentoRepository,
        private ValidarService $validarService,
        private HorarioService $horarioService,
        private AgendamentoServicoRepository $agendamento_ServicoRepository,
        private ServicoRepository $servicoRepository,
        private AgendamentoServicoRepository $agendamento_servico_repository,
    ){}

    public function agendar($id_cliente, array $data): int
    {
        //invalidar acesso de barbeiro
        $this->validarService->invalidaPermissaoBarbeiro();
        
        //validar existencia de dominio
        $this->validarService->validaCliente($id_cliente);
        $this->validarService->validaBarbeiro($data['id_barbeiro']);
       
        //validar horario disponivel
        $this->horarioService->validarDisponibilidade(
            $data['id_barbeiro'],
            $data['hora'], 
            $data['data']
        );

        //validar horário expediente
        $this->horarioService->validarExpedienteHorario();

        //validar hora passada
        $this->horarioService->validarHorarioPassado(
            $data['data'],
            $data['hora']
        );
     
        $data['id_cliente'] = $id_cliente;
        $data["status" ] = "AGENDADO";
        
        //transação
       $id_agendamento = DB::transaction(function () use($data){

            //salvar agendamento no banco
            $id_agendamento = $this->agendamentoRepository->salvarAgendamento($data);
            
            //salvar o registro de agendamento e servico
            $agendamentoServico = $this->agendamento_ServicoRepository->SalvarAgendamentoServico($id_agendamento->id, $data['servicos']);

            if(!$agendamentoServico or $id_agendamento->isEmpty())
            {
                throw new ErrorInternoException("Error ao criar agendamento");
            }

            return $id_agendamento->id;
        });

        return $id_agendamento;
    }

    public function reagendamento(int $id_agenda, array $data)
    {
        //invalidar acesso do barbeiro
        $this->validarService->invalidaPermissaoBarbeiro();

        //verificar se cliente existe
        $this->validarService->validaCliente($data['id_cliente']);

        //verificar se existe agenda
        $this->validarService->validarExistenciaAgendamento($id_agenda);

        //verificar se a agenda e do cliente
        $this->validarService->validarPermissaoAgendaCliente($id_agenda, $data['id_cliente']);

        //pegar agenda
        $agenda = $this->agendamentoRepository->visualizarAgendaCliente($id_agenda);

        //validar horario disponivel
        $this->horarioService->validarDisponibilidade(
            $agenda->id_barbeiro,
            $data['hora'], 
            $data['data']
        );

        //validar horário expediente
        $this->horarioService->validarExpedienteHorario();

        //validar hora passada
        $this->horarioService->validarHorarioPassado(
            $data['data'],
            $data['hora']
        );

        $data['id_agendamento'] = $id_agenda;

        //reagendar
        $agenda->data = $data['data'];
        $agenda->hora = $data['hora'];
        $agenda->save();
       
    }

    public function cancelar($id_agenda, $cliente_id = null, $barbeiro_id = null): object
    {
        if(!is_null($cliente_id))
        {
            return $this->cancelarPorClientes($id_agenda,  $cliente_id);
        }
            else
            {
                return $this->cancelarPorBarbeiros($id_agenda, $barbeiro_id);
            }
  
    }

    public function cancelarPorClientes( $id_agenda, $cliente_id): object
    {
        //verificar se cliente existe
        $this->validarService->validaCliente($cliente_id);

        //permissão apenas para clientes
        $this->validarService->invalidaPermissaoBarbeiro();

        //validar existencia de um agendamento
        $this->validarService->validarExistenciaAgendamento($id_agenda);

        //verificar se o agendamento e do cliente
        $this->validarService->validarPermissaoAgendaCliente($id_agenda, $cliente_id);

        //buscar registro do agendamento de cliente
        $agendaCliente = $this->agendamentoRepository->visualizarAgendaCliente($id_agenda);
       
         //criando regras para status do agendamento 
        if($agendaCliente->status === 'CONCLUIDO')
        {
            throw new NaoPermitidoExecption("Agendamento não pode ser mais cancelado. O Agendamento já foi Concluido");
        }
            elseif($agendaCliente->status === 'CANCELADO')
            {
                throw new NaoPermitidoExecption("Esse Agendamento já foi cancelado",409);
            }
                //limitando horário de cancelamento do agendamento
                $this->horarioService->horarioCancelarAgendamento($agendaCliente->hora);
                
                    $agendaCliente->status = 'CANCELADO';
                    $agendaCliente->save();

                    return $agendaCliente;
         
    }

    public function cancelarPorBarbeiros( $id_agenda, $id_barbeiro): object
    {
        //verificar se barbeiro existe
        $this->validarService->validaBarbeiro($id_barbeiro);

        //permissão apenas para clientes
        $this->validarService->invalidaPermissaoCliente();

        //verificar existencia de um agendamento
        $this->validarService->validarExistenciaAgendamento($id_agenda);

        //validar Permissão do barbeiro
        $this->validarService->validarPermissaoAgendaBarbeiro($id_agenda, $id_barbeiro);

        //buscar registro do agendamento de cliente
        $agendaCliente = $this->agendamentoRepository->visualizarAgendaCliente($id_agenda);
        
        //criando regras para status do agendamento 
        if($agendaCliente->status === 'CONCLUIDO')
        {
            throw new NaoPermitidoExecption("Agendamento não pode ser mais cancelado. O Agendamento já foi Concluido");
        }
            elseif($agendaCliente->status === 'CANCELADO')
            {
                throw new NaoPermitidoExecption("Esse Agendamento já foi cancelado",409);
            }
                
                $agendaCliente->status = 'CANCELADO';
                $agendaCliente->save();

                return $agendaCliente;
         
    }

    public function concluirAgendamentos( $id_agenda, $id_barbeiro)
    {
        //verificar se barbeiro existe
        $this->validarService->validaBarbeiro($id_barbeiro);

        //permissão apenas para barbeiros
        $this->validarService->invalidaPermissaoCliente();

        //verificar existencia de um agendamento
        $this->validarService->validarExistenciaAgendamento($id_agenda);

        //validar permissão do barbeiro
        $this->validarService->validarPermissaoAgendaBarbeiro($id_agenda, $id_barbeiro);
        
        //buscar registro do agendamento de cliente
        $agendaCliente = $this->agendamentoRepository->visualizarAgendaCliente($id_agenda);

            //criando regras para status do agendamento 
            if($agendaCliente->status === 'CANCELADO')
            {
                throw new NaoPermitidoExecption("Agendamento não pode ser mais concluido. O Agendamento já foi Cancelado");
            }
                elseif($agendaCliente->status === 'CONCLUIDO')
                {
                    throw new NaoPermitidoExecption("Esse Agendamento já foi Concluido",409);
                }

                    $agendaCliente->status = 'CONCLUIDO';
                    $agendaCliente->save();

                    return $agendaCliente;
    }

    public function agendamentos($cliente_id = null, $barbeiro_id = null)
    {
        //regra para verificar acesso de cliente ou barbeiro
        if(!is_null($cliente_id))
        {
           return $this->listarAgendamentosPorClientes($cliente_id);
        }
            else
            {
                return $this->listarAgendamentosPorBarbeiro($barbeiro_id);
            }
    }

    public function agenda(int $id_agenda, $cliente_id = null, $barbeiro_id = null)
    {
        //regra para verificar acesso de cliente ou barbeiro
        if(!is_null($cliente_id))
        {
           return $this->buscarAgendasPorClientes($id_agenda, $cliente_id);
        }
            else
            {
                return $this->buscarAgendasPorBarbeiro($id_agenda, $barbeiro_id);
            }
    }

    public function listarAgendamentosPorClientes($cliente_id): object
    {
        //verificar se cliente existe
        $this->validarService->validaCliente($cliente_id);

        //permissão apenas para clientes
        $this->validarService->invalidaPermissaoBarbeiro();

        //buscar coleção de agendamentos de um cliente
        $agendamentos = $this->agendamentoRepository->listaAgendaCliente($cliente_id);

        //verificar se exister algum recurso
        if($agendamentos ->isEmpty())
        {
            throw new NaoExisteRecursoException("Lista de agendamentos vazia");
        }
        
        //somar preco de todos os servicos agendado da colecao de agendamentos
        $precoTotal = $this->agendamentoRepository->precoTotalTodosAgendamentos($cliente_id);
        $agendamentos->prepend([ "preco_total" => $precoTotal]);//adiconando um elemento a colleção

        return $agendamentos;
    }

    public function listarAgendamentosPorBarbeiro($barbeiro_id): object
    {
        //verificar se barbeiro existe
        $this->validarService->validaBarbeiro($barbeiro_id);

        //permissão apenas para clientes
        $this->validarService->invalidaPermissaoCliente();

        //buscar coleção de agendamentos de um cliente
        $agendamentos = $this->agendamentoRepository->listaAgendaBarbeiro($barbeiro_id);

        //verificar se exister algum recurso
        if($agendamentos ->isEmpty())
        {
            throw new NaoExisteRecursoException("Agenda vazia");
        }
        
        //somar preco de todos os servicos agendado da colecao de agendamentos
        $precoTotal = $this->agendamentoRepository->precoTotalTodosAgendamentos(null, $barbeiro_id);
        $agendamentos->prepend([ "preco_total" => $precoTotal]);

        return $agendamentos;
    }

    public function buscarAgendasPorClientes(int $id_agenda, $cliente_id): object
    {
         //verificar se cliente existe
        $this->validarService->validaCliente($cliente_id);

        //permissão apenas para clientes
        $this->validarService->invalidaPermissaoBarbeiro();

        //validar existencia do agendamento
        $this->validarService->validarExistenciaAgendamento($id_agenda);
        
        //verificar se o agendamento e do cliente
        $this->validarService->validarPermissaoAgendaCliente($id_agenda, $cliente_id);
       
        //buscar registro do agendamento de cliente
        $agendaCliente = $this->agendamentoRepository->visualizarAgendaCliente($id_agenda);

        //somar preco de todos os servicos do registro 
        $precoTotal = $this->agendamentoRepository->precoTotalAgendamento($id_agenda);
        $agendaCliente->preco_total = $precoTotal;
            
        return $agendaCliente;
        
    }

    public function buscarAgendasPorBarbeiro(int $id_agenda, $barbeiro_id): object
    {
        //verificar se barbeiro existe
        $this->validarService->validaBarbeiro($barbeiro_id);

        //permissão apenas para clientes
        $this->validarService->invalidaPermissaoCliente();

        //validar existencia do agendamento
        $this->validarService->validarExistenciaAgendamento($id_agenda);
        
        //verificar se o agendamento e do cliente
        $this->validarService->validarPermissaoAgendaBarbeiro($id_agenda, $barbeiro_id);
       
        //buscar registro do agendamento de cliente
        $agendaCliente = $this->agendamentoRepository->visualizarAgendaBarbeiro($id_agenda);

        //somar preco de todos os servicos do registro 
        $precoTotal = $this->agendamentoRepository->precoTotalAgendamento($id_agenda);
        $agendaCliente->preco_total = $precoTotal;
            
        return $agendaCliente;
        
    }

    public function removerDeAgendamentos($cliente_id, int $id_agendamento, int $id_servico)
    {

        //invalidar aceso de barbeiro
        $this->validarService->invalidaPermissaoBarbeiro();

        //verificar se cliente existe
        $this->validarService->validaCliente($cliente_id);

        //verificar se agendamento existe
        $this->validarService->validarExistenciaAgendamento($id_agendamento);

        //verificar existentencia do Servico
        if(!$this->servicoRepository->existeServico($id_servico))
        {
           throw new NaoExisteRecursoException("Serviço não existe");
        }

        //verificar se o clinte tem permissao para remover
        $this->validarService->validarPermissaoAgendaCliente($id_agendamento, $cliente_id);
        
        //verificar se o servico e do agendamento
        if(!$this->agendamento_servico_repository->existeServicoAgendamento($id_agendamento, $id_servico))
        {
            throw new NaoExisteRecursoException("Esse serviço não está relacionado com esse agendamento");
        }

        //remover
        $this->agendamento_servico_repository->remover($id_agendamento, $id_servico);
        

    }





}