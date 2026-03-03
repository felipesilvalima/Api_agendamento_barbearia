<?php declare(strict_types=1); 

namespace App\Exceptions;

use Exception;

class ErrorInternoException extends NaoExisteRecursoException
{
    public int $statusCode = 500;

    public function __construct($message = "Error interno no servidor")
    {
        return parent::__construct($message,$this->statusCode);
    }
}
