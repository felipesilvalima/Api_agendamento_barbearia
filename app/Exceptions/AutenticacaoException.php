<?php declare(strict_types=1); 

namespace App\Exceptions;

use Exception;

class AutenticacaoException extends NaoExisteRecursoException
{
    public int $statusCode = 401;

     public function __construct($message = "Email ou senha inválido")
    {
        return parent::__construct($message,$this->statusCode);
    }
}
