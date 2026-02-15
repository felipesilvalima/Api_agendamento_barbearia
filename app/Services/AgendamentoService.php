<?php declare(strict_types=1); 

namespace App\Services;

use App\DTOS\AgendamentoDTO;
use App\DTOS\AgendamentosAtributosFiltrosPagincaoDTO;
use App\DTOS\ReagendamentoDTO;
use App\Events\StatusAlterado;
use App\Exceptions\ErrorInternoException;
use App\Exceptions\NaoExisteRecursoException;
use App\Helpers\AgendamentoConfig;
use App\Helpers\ValidarAtributos;
use App\Repository\Contratos\AgendamentoServicoRepositoyInterface;
use App\Repository\Contratos\AgendamentosRepositoryInterface;
use App\Repository\Contratos\BarbeiroRepositoryInterface;
use App\Repository\Contratos\ClienteRepositoryInterface;
use DomainException;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\DB;

class AgendamentoService
{
    use ValidarAtributos;
    use AgendamentoConfig;

    public function __construct(
        private AgendamentosRepositoryInterface $agendamentoRepository,
        private AgendamentoServicoRepositoyInterface $agendamento_ServicoRepository,
        private ValidarDomainService $validarService,
        private HorarioDomainService $horarioService,
    ){}

    public function agendar(AgendamentoDTO $agendamentoDto): int
    {
    
        //validação de segurança
        $this->validarService->validarExistenciaCliente($agendamentoDto->clienteUser->cliente->id,"Não e possivel fazer agendamento. Esse cliente não existe");
        $this->validarService->validarExistenciaBarbeiro($agendamentoDto->id_barbeiro,"Não e possivel fazer agendamento. Esse barbeiro não existe");
        //Regras de négocio
        $this->validarService->validarLimiteAgendamentoPorCliente($agendamentoDto->clienteUser);
        $this->horarioService->validarDisponibilidade($agendamentoDto);
        $this->horarioService->validarExpedienteHorario();
        $this->horarioService->validarHorarioFuturo($agendamentoDto);
        $this->horarioService->validarAgendamentoAntecedente($agendamentoDto->data);
        
        //percistencia
       $agendamento = DB::transaction(function () use($agendamentoDto){

            //salvar agendamento no banco
            $agendamento = $this->agendamentoRepository->salvar($agendamentoDto);

            if(collect($agendamento)->isEmpty() || collect($agendamento->id)->isEmpty())
            {
                throw new ErrorInternoException("Error ao criar agendamento");
            }
            
            //salvar o registro de agendamento e servico
            $agendamentoServico = $this->agendamento_ServicoRepository->vincular(
                $agendamento->id,
                $agendamentoDto->servicos,
                $agendamentoDto->barbearia_id
            );

            if(!$agendamentoServico)
            {
                throw new ErrorInternoException("Error ao vincular serviços ao agendamento");
            }

            return $agendamento;
        });

            //event
            event(new StatusAlterado($agendamento));

            return $agendamento->id;
    }

    public function agendamentos(AgendamentosAtributosFiltrosPagincaoDTO $agendamentosDTO): object
    {

        //atributos 
        foreach($this->regras() as $campoDto => $atributosPermitidos)
        {
            $agendamentosDTO->$campoDto =  $this->validarAtributos($agendamentosDTO->$campoDto, $atributosPermitidos['atributos']);  
        }
            //atributos condição
            foreach($this->regras() as $campoDto => $filtro)
            {
                (string)$filtro_validado = $filtro['filtro_validado'];
                (string)$filtro_request = $filtro['filtro'];
               
                $agendamentosDTO->$filtro_validado = $this->validarAtributosCondicao($agendamentosDTO->$filtro_request ,$filtro['atributos']);
            }
        

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
        $this->validarService->validarExistenciaCliente($reagendamentoDto->id_cliente,"Não e possivel reagendar. esse Cliente não existe");

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

            //event
            event(new StatusAlterado($agenda,'REAGENDADO'));

            return $agenda;
    }

    public function finalizar(int $id_agenda, ?int $id_barbeiro): object
    {
        
        //validação de segurança e permissoes
        $this->validarService->validarExistenciaBarbeiro($id_barbeiro,"Não e possivel finalizar. esse Barbeiro não existe");
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

                    //event
                    event(new StatusAlterado($agendaCliente));

                    return $agendaCliente;
    }

    public function cancelar(int $id_agenda, User $user): object
    {

        $agendaCliente = $this->agendamentoRepository->detalhes($id_agenda);

        if($user->role === 'cliente')
        {
            //validação de segurança
            $this->validarService->validarExistenciaCliente($user->cliente->id,"Não e possivel cancelar. esse Cliente não existe");
            $this->horarioService->horarioCancelarAgendamento($agendaCliente->hora);

        }
            else
            {
                //validação de segurança
                $this->validarService->validarExistenciaBarbeiro($user->barbeiro->id,"Não e possivel cancelar. esse Barbeiro não existe");
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

                        //event
                        event(new StatusAlterado($agendaCliente));

                        return $agendaCliente;
         
    }


    

}