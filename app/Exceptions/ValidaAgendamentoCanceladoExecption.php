<?php declare(strict_types=1); 

namespace App\Exceptions;

use Exception;

class ValidaAgendamentoCanceladoExecption extends ValidarExistenciaClienteException
{
    public int $statusCode = 403;

    public function __construct($message = "Esse Agendamento já foi cancelado")
    {
        return parent::__construct($message, $this->statusCode);
    }
}