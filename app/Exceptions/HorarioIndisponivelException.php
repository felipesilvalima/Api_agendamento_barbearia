<?php declare(strict_types=1); 

namespace App\Exceptions;



class HorarioIndisponivelException extends NaoExisteRecursoException
{
    public int $statusCode = 409;

    public function __construct($message = "Não foi possivel fazer o agendamento. Já tem um agendamento nesse horario!", $statusCode = 0)
    {
        $this->statusCode = $statusCode === 0 ? $this->statusCode : $statusCode;
        return parent::__construct($message,$this->statusCode);
    }
}
