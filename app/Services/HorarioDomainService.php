<?php declare(strict_types=1); 

namespace App\Services;


use App\Exceptions\HorarioIndisponivelException;
use App\Exceptions\NaoPermitidoExecption;
use App\Repository\Contratos\AgendamentosRepositoryInterface;
use Carbon\Carbon;
use DomainException;

class HorarioDomainService
{

    private const INTERVALOS = [
            ['start' => '13:00:00', 'end' => '14:30:00'],
            ['start' => '19:30:01', 'end' => '23:59:59'], 
            ['start' => '00:00:00', 'end' => '07:50:00'], 
        ];

     public function __construct(
        private AgendamentosRepositoryInterface $agendamentoRepository, 
    ){}

    public function validarDisponibilidade(object $dtos): void
    {
        if($this->agendamentoRepository->existeAgendamentoHorario(
            $dtos->id_barbeiro,
            $dtos->hora, 
            $dtos->data
        ))
        {
            throw new DomainException("Não foi possivel fazer o agendamento. Já tem um agendamento nesse horario!",409);   
        }
    }

    public function validarExpedienteHorario(): void
    {
       
        $horaAtual = Carbon::now();

            foreach(self::INTERVALOS as $intervalo)
            {
                $inicio = Carbon::parse($intervalo['start']);
                $fim = Carbon::parse($intervalo['end']);

                    if($horaAtual->between($inicio, $fim))
                    {
                        throw new DomainException("Não é possivel fazer agendamento nesse Horário. Fim de expediente!",422);
                    }
            }
    }

    public function validarHorarioFuturo(object $dtos): void
    {
        $horaAtual = Carbon::now()->format('H:i:s');

            if($dtos->hora < $horaAtual && $dtos->data  === Carbon::now()->toDateString()) 
            {
                throw new DomainException("Horário indisponível. Horário não pode ser no passado!",422);
            }
    }

    public function horarioCancelarAgendamento(string $hora): void
    {
        if($hora > Carbon::now()->parse($hora)->addHour(1)->format('H:i:s'))
        {
            throw new DomainException("Agendamento não pode ser mais cancelado. Horário de cancelar expirou");
        }         
        
    }

    public function validarAgendamentoAntecedente(string $data): void
    {
        if($data > Carbon::now()->parse($data)->addDay(30)->format('Y:M:D'))
        {
            throw new DomainException("Agendamento limitado a 30 dias de antecedência",422);
        }
    }
}