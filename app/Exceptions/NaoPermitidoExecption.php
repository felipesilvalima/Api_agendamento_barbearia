<?php declare(strict_types=1); 

namespace App\Exceptions;

class NaoPermitidoExecption extends NaoExisteRecursoException
{
    public int $statusCode = 403;

    public function __construct(string $message = "Você não permissão para acessar esse recurso", $statusCode = 0)
    {
        $this->statusCode = $statusCode === 0 ? $this->statusCode : $statusCode;
        
        return parent::__construct($message, $this->statusCode);
    }
}
