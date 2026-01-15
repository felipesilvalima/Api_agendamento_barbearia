<?php declare(strict_types=1); 

namespace App\Services;

use App\DTOS\CriarAgendamentosDtos;
use App\DTOS\CriarReagendamentoDtos;
use App\Exceptions\ErrorInternoException;
use App\Exceptions\NaoExisteRecursoException;
use App\Exceptions\NaoPermitidoExecption;
use App\Repository\Contratos\AgendamentoServicoRepositoyInterface;
use App\Repository\Contratos\AgendamentosRepositoryInterface;
use App\Repository\Contratos\ServicoRepositoryInteface;
use Illuminate\Support\Facades\DB;

class AgendamentoService
{
    public function __construct(
        private AgendamentosRepositoryInterface $agendamentoRepository,
        private AgendamentoServicoRepositoyInterface $agendamento_ServicoRepository,
        private ValidarDomainService $validarService,
        private HorarioDomainService $horarioService,
    ){}

    public function agendar(CriarAgendamentosDtos $dtos): int
    {
        //validação de segurança e permissoes
        $this->validarService->invalidaPermissaoBarbeiro();
        $this->validarService->validaCliente($dtos->id_cliente);
        $this->validarService->validaBarbeiro($dtos->id_barbeiro);

        //Regras de négocio
        $this->validarService->validarLimiteAgendamentoPorCliente($dtos->id_cliente);
        $this->horarioService->validarDisponibilidade($dtos);
        $this->horarioService->validarExpedienteHorario();
        $this->horarioService->validarHorarioFuturo($dtos);
        $this->horarioService->validarAgendamentoAntecedente($dtos->data);

        //criando o objeto de dominio
        $agamento = $dtos->createAgendamentoObject();
     
        
        //percistencia
       $id_agendamento = DB::transaction(function () use($agamento){

            //salvar agendamento no banco
            $id_agendamento = $this->agendamentoRepository->salvar($agamento);

            if(empty($id_agendamento) || empty($id_agendamento->id))
            {
                throw new ErrorInternoException("Error ao criar agendamento");
            }
            
            //salvar o registro de agendamento e servico
            $agendamentoServico = $this->agendamento_ServicoRepository->vincular($id_agendamento->id, $agamento->getServicos());

            if(!$agendamentoServico)
            {
                throw new ErrorInternoException("Error ao vincular serviços ao agendamento");
            }

            return $id_agendamento->id;
        });

        return $id_agendamento;
    }

    public function reagendamento(CriarReagendamentoDtos $dtos)
    {
        //validação de segurança e permissoes
        $this->validarService->invalidaPermissaoBarbeiro();
        $this->validarService->validaCliente($dtos->id_cliente);
        $this->validarService->validarExistenciaAgendamento($dtos->id_agendamento);
        $this->validarService->validarPermissaoAgendaCliente($dtos->id_agendamento, $dtos->id_cliente);


        //regras de négocio
        $agenda = $this->agendamentoRepository->buscarAgendaCliente($dtos->id_agendamento);
        $dtos->id_barbeiro = $agenda->id_barbeiro;
        $this->horarioService->validarDisponibilidade($dtos);
        $this->horarioService->validarExpedienteHorario();
        $this->horarioService->validarHorarioFuturo($dtos);
        $this->horarioService->validarAgendamentoAntecedente($dtos->data);

        //salvar o reagendamento
        $agenda->fill([
            'data' => $dtos->data,
            'hora' => $dtos->hora,
        ]);

        $agenda->save();
       
    }

    public function cancelarPorClientes($id_agenda, $cliente_id): object
    {
        //validação de segurança e permissoes
        $this->validarService->validaCliente($cliente_id);
        $this->validarService->invalidaPermissaoBarbeiro();
        $this->validarService->validarExistenciaAgendamento($id_agenda);
        $this->validarService->validarPermissaoAgendaCliente($id_agenda, $cliente_id);

        //regras de négocio
        $agendaCliente = $this->agendamentoRepository->buscarAgendaCliente($id_agenda);
        $this->horarioService->horarioCancelarAgendamento($agendaCliente->hora);
         
        if($agendaCliente->status === 'CONCLUIDO')
        {
            throw new NaoPermitidoExecption("Agendamento não pode ser mais cancelado. O Agendamento já foi Concluido");
        }
            elseif($agendaCliente->status === 'CANCELADO')
            {
                throw new NaoPermitidoExecption("Esse Agendamento já foi cancelado",409);
            }
                
                //percistencia
                $agendaCliente->status = 'CANCELADO';
                $agendaCliente->save();

                return $agendaCliente;
         
    }

    public function cancelarPorBarbeiros( $id_agenda, $id_barbeiro): object
    {
        //validação de segurança e permissoes
        $this->validarService->validaBarbeiro($id_barbeiro);
        $this->validarService->invalidaPermissaoCliente();
        $this->validarService->validarExistenciaAgendamento($id_agenda);
        $this->validarService->validarPermissaoAgendaBarbeiro($id_agenda, $id_barbeiro);

        //buscar registro do agendamento de cliente
        $agendaCliente = $this->agendamentoRepository->buscarAgendaCliente($id_agenda);
        
        //regras 
        if($agendaCliente->status === 'CONCLUIDO')
        {
            throw new NaoPermitidoExecption("Agendamento não pode ser mais cancelado. O Agendamento já foi Concluido");
        }
            elseif($agendaCliente->status === 'CANCELADO')
            {
                throw new NaoPermitidoExecption("Esse Agendamento já foi cancelado",409);
            }
                //percistencia
                $agendaCliente->status = 'CANCELADO';
                $agendaCliente->save();

                return $agendaCliente;
         
    }

    public function concluirAgendamentos( $id_agenda, $id_barbeiro)
    {
        //validação de segurança e permissoes
        $this->validarService->validaBarbeiro($id_barbeiro);
        $this->validarService->invalidaPermissaoCliente();
        $this->validarService->validarExistenciaAgendamento($id_agenda);
        $this->validarService->validarPermissaoAgendaBarbeiro($id_agenda, $id_barbeiro);
        
        //buscar registro do agendamento de cliente
        $agendaCliente = $this->agendamentoRepository->buscarAgendaCliente($id_agenda);

            //regras
            if($agendaCliente->status === 'CANCELADO')
            {
                throw new NaoPermitidoExecption("Agendamento não pode ser mais concluido. O Agendamento já foi Cancelado");
            }
                elseif($agendaCliente->status === 'CONCLUIDO')
                {
                    throw new NaoPermitidoExecption("Esse Agendamento já foi Concluido",409);
                }

                    //percistencia
                    $agendaCliente->status = 'CONCLUIDO';
                    $agendaCliente->save();

                    return $agendaCliente;
    }

    public function listarAgendamentosPorClientes($cliente_id): object
    {
        //validação de segurança e permissoes
        $this->validarService->validaCliente($cliente_id);
        $this->validarService->invalidaPermissaoBarbeiro();

        //buscar coleção de agendamentos de um cliente
        $agendamentos = $this->agendamentoRepository->listaAgendasCliente($cliente_id);

        //verificar se exister algum recurso
        if(empty($agendamentos))
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
        //validação de segurança e permissoes
        $this->validarService->validaBarbeiro($barbeiro_id);
        $this->validarService->invalidaPermissaoCliente();

        //buscar coleção de agendamentos de um cliente
        $agendamentos = $this->agendamentoRepository->listaAgendasBarbeiro($barbeiro_id);

        //verificar se exister algum recurso
        if(empty($agendamentos))
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
        //validação de segurança e permissoes
        $this->validarService->validaCliente($cliente_id);
        $this->validarService->invalidaPermissaoBarbeiro();
        $this->validarService->validarExistenciaAgendamento($id_agenda);
        $this->validarService->validarPermissaoAgendaCliente($id_agenda, $cliente_id);
       
        //buscar registro do agendamento de cliente
        $agendaCliente = $this->agendamentoRepository->buscarAgendaCliente($id_agenda);

        //somar preco de todos os servicos do registro 
        $precoTotal = $this->agendamentoRepository->precoTotalAgendamento($id_agenda);
        $agendaCliente->preco_total = $precoTotal;
            
        return $agendaCliente;
        
    }

    public function buscarAgendasPorBarbeiro(int $id_agenda, $barbeiro_id): object
    {
        //validação de segurança e permissoes
        $this->validarService->validaBarbeiro($barbeiro_id);
        $this->validarService->invalidaPermissaoCliente();
        $this->validarService->validarExistenciaAgendamento($id_agenda);
        $this->validarService->validarPermissaoAgendaBarbeiro($id_agenda, $barbeiro_id);
       
        //buscar registro do agendamento de cliente
        $agendaCliente = $this->agendamentoRepository->buscarAgendaBarbeiro($id_agenda);

        //somar preco de todos os servicos do registro 
        $precoTotal = $this->agendamentoRepository->precoTotalAgendamento($id_agenda);
        $agendaCliente->preco_total = $precoTotal;
            
        return $agendaCliente;
        
    }

    public function removerDeAgendamentos($cliente_id, int $id_agendamento, int $id_servico)
    {

       //validação de segurança e permissoes
        $this->validarService->invalidaPermissaoBarbeiro();
        $this->validarService->validaCliente($cliente_id);
        $this->validarService->validarExistenciaAgendamento($id_agendamento);
        $this->validarService->validarPermissaoAgendaCliente($id_agendamento, $cliente_id);
        $this->validarService->validarExistenciaServico($id_servico);
        $this->validarService->validarServicoExisteAgendamento($id_agendamento, $id_servico);

        //remover
        $this->agendamento_ServicoRepository->remover($id_agendamento, $id_servico);
        
    }

    public function cancelar($id_agenda, $cliente_id = null, $barbeiro_id = null): object
    {
        //verificar acesso
        if(!is_null($cliente_id))
        {
            return $this->cancelarPorClientes($id_agenda,  $cliente_id);
        }
            else
            {
                return $this->cancelarPorBarbeiros($id_agenda, $barbeiro_id);
            }
  
    }

    public function agendamentos($cliente_id = null, $barbeiro_id = null)
    {
        //verificar acesso
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
        //verificar acesso
        if(!is_null($cliente_id))
        {
           return $this->buscarAgendasPorClientes($id_agenda, $cliente_id);
        }
            else
            {
                return $this->buscarAgendasPorBarbeiro($id_agenda, $barbeiro_id);
            }
    }





}