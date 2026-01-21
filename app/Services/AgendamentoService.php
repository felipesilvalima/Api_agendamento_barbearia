<?php declare(strict_types=1); 

namespace App\Services;

use App\DTOS\AgendamentoDTO;
use App\DTOS\AgendamentosAtributosFiltrosPagincaoDTO;
use App\DTOS\ReagendamentoDTO;
use App\Exceptions\ErrorInternoException;
use App\Exceptions\NaoExisteRecursoException;
use App\Helpers\ValidarAtributos;
use App\Repository\Contratos\AgendamentoServicoRepositoyInterface;
use App\Repository\Contratos\AgendamentosRepositoryInterface;
use App\Repository\Contratos\BarbeiroRepositoryInterface;
use App\Repository\Contratos\ClienteRepositoryInterface;
use DomainException;
use Illuminate\Support\Facades\DB;

class AgendamentoService
{
    public function __construct(
        private AgendamentosRepositoryInterface $agendamentoRepository,
        private AgendamentoServicoRepositoyInterface $agendamento_ServicoRepository,
        private ClienteRepositoryInterface $clienteRepository,
        private BarbeiroRepositoryInterface $barbeiroRepository,
        private ValidarDomainService $validarService,
        private HorarioDomainService $horarioService,
    ){}

    public function agendar(AgendamentoDTO $agendamentoDto): int
    {
        //validação de segurança
        if(!$this->clienteRepository->verificarClienteExiste($agendamentoDto->id_cliente))
        {
            throw new NaoExisteRecursoException("Não e possivel fazer agendamento. Esse cliente não existe");
        }

        if(!$this->barbeiroRepository->verificarBarbeiroExiste($agendamentoDto->id_barbeiro))
        {
            throw new NaoExisteRecursoException("Não e possivel fazer agendamento. Esse barbeiro não existe");
        }

        //Regras de négocio
        $this->validarService->validarLimiteAgendamentoPorCliente($agendamentoDto->id_cliente);
        $this->horarioService->validarDisponibilidade($agendamentoDto);
        $this->horarioService->validarExpedienteHorario();
        $this->horarioService->validarHorarioFuturo($agendamentoDto);
        $this->horarioService->validarAgendamentoAntecedente($agendamentoDto->data);
        
        //percistencia
       $id_agendamento = DB::transaction(function () use($agendamentoDto){

            //salvar agendamento no banco
            $id_agendamento = $this->agendamentoRepository->salvar($agendamentoDto);

            if(collect($id_agendamento)->isEmpty() || collect($id_agendamento->id)->isEmpty())
            {
                throw new ErrorInternoException("Error ao criar agendamento");
            }
            
            //salvar o registro de agendamento e servico
            $agendamentoServico = $this->agendamento_ServicoRepository->vincular($id_agendamento->id, $agendamentoDto->servicos);

            if(!$agendamentoServico)
            {
                throw new ErrorInternoException("Error ao vincular serviços ao agendamento");
            }

            return $id_agendamento->id;
        });

        return $id_agendamento;
    }

    public function agendamentos(AgendamentosAtributosFiltrosPagincaoDTO $agendamentosDTO): object
    {

        $atributosPermitido = ['id','data','hora','status','id_barbeiro','id_cliente'];
        $atributosBarbeiroPermitido = ['id','nome','telefone','status','especialidade'];
        $atributosClientePermitido = ['id','nome','telefone','data_cadastro'];

        //atributos condição
        $agendamentosDTO->filtro_validado = ValidarAtributos::validarAtributosCondicao($agendamentosDTO->filtro,$atributosPermitido);
        $agendamentosDTO->filtro_barbeiro_validado = ValidarAtributos::validarAtributosCondicao($agendamentosDTO->filtro_barbeiro,$atributosBarbeiroPermitido);
        $agendamentosDTO->filtro_cliente_validado = ValidarAtributos::validarAtributosCondicao($agendamentosDTO->filtro_cliente,$atributosClientePermitido);

        //atributos
        $agendamentosDTO->atributos = ValidarAtributos::validarAtributos($agendamentosDTO->atributos,$atributosPermitido);
        $agendamentosDTO->atributos_barbeiro = ValidarAtributos::validarAtributos($agendamentosDTO->atributos_barbeiro,$atributosBarbeiroPermitido);
        $agendamentosDTO->atributos_cliente = ValidarAtributos::validarAtributos($agendamentosDTO->atributos_cliente,$atributosClientePermitido);
        
        //listar coleção de agendamentos
        $agendamentos = $this->agendamentoRepository->listar($agendamentosDTO);
        
        //verificar se exister algum recurso
        if(collect($agendamentos)->isEmpty())
        {
            throw new NaoExisteRecursoException("Nenhuma lista encontrado");
        }

            return $agendamentos;
    }


    public function detalhesAgenda(int $id_agenda): object
    {  
        //buscar registro do agendamento de cliente
        $agendaCliente = $this->agendamentoRepository->detalhes($id_agenda); 
        return $agendaCliente; 
    }

    public function reagendar(ReagendamentoDTO $reagendamentoDto): object
    {
        //validação de segurança e permissoes
        if(!$this->clienteRepository->verificarClienteExiste($reagendamentoDto->id_cliente))
        {
            throw new NaoExisteRecursoException("Não e possivel reagendar. esse Cliente não existe");
        }

        //regras de négocio
        $agenda = $this->agendamentoRepository->detalhes($reagendamentoDto->id_agendamento);
        $reagendamentoDto->id_barbeiro = $agenda->id_barbeiro;
        $this->horarioService->validarDisponibilidade($reagendamentoDto);
        $this->horarioService->validarExpedienteHorario();
        $this->horarioService->validarHorarioFuturo($reagendamentoDto);
        $this->horarioService->validarAgendamentoAntecedente($reagendamentoDto->data);

        if($agenda->status != 'AGENDADO')
        {
            throw new DomainException("E permitido reagendar apenas status Agendado",403);
        }

        //salvar o reagendamento
        $agenda->fill([
            'data' => $reagendamentoDto->data,
            'hora' => $reagendamentoDto->hora,
        ]);

        $agenda->save();

        return $agenda;
       
    }

    public function finalizar(int $id_agenda, ?int $id_barbeiro): object
    {
        //validação de segurança e permissoes
        if(!$this->barbeiroRepository->verificarBarbeiroExiste($id_barbeiro))
        {
            throw new NaoExisteRecursoException("Não e possivel finalizar. esse Barbeiro não existe");
        }

        $this->validarService->validarExistenciaAgendamento($id_agenda);
        
        //buscar registro do agendamento de cliente
        $agendaCliente = $this->agendamentoRepository->detalhes($id_agenda);

            //regras
            if($agendaCliente->status === 'CANCELADO')
            {
                throw new DomainException("Agendamento não pode ser mais concluido. O Agendamento já foi Cancelado",409);
            }
                elseif($agendaCliente->status === 'CONCLUIDO')
                {
                    throw new DomainException("Esse Agendamento já foi Concluido",409);
                }

                    //percistencia
                    $agendaCliente->status = 'CONCLUIDO';
                    $agendaCliente->save();

                    return $agendaCliente;
    }

    public function cancelar(int $id_agenda, ?int $cliente_id,  ?int $barbeiro_id): object
    {

        $agendaCliente = $this->agendamentoRepository->detalhes($id_agenda);

        if(!is_null($cliente_id))
        {
            //validação de segurança
            if(!$this->clienteRepository->verificarClienteExiste($cliente_id))
            {
                throw new NaoExisteRecursoException("Não e possivel cancelar. esse Cliente não existe");
            }

            $this->horarioService->horarioCancelarAgendamento($agendaCliente->hora);

        }
            else
            {
                //validação de segurança
                if(!$this->barbeiroRepository->verificarBarbeiroExiste($barbeiro_id))
                {
                    throw new NaoExisteRecursoException("Não e possivel cancelar. esse Barbeiro não existe");
                }
            }
         
                if($agendaCliente->status === 'CONCLUIDO')
                {
                    throw new DomainException("Agendamento não pode ser mais cancelado. O Agendamento já foi Concluido",409);
                }
                    elseif($agendaCliente->status === 'CANCELADO')
                    {
                        throw new DomainException("Esse Agendamento já foi cancelado",409);
                    }
                
                        //percistencia
                        $agendaCliente->status = 'CANCELADO';
                        $agendaCliente->save();

                        return $agendaCliente;
         
    }


    

}