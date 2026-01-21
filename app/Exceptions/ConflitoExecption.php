<?php declare(strict_types=1); 

namespace App\Exceptions;

use Exception;

class ConflitoExecption extends NaoExisteRecursoException
{
    public int $statusCode = 409;

    public function __construct($message = "Error de conflito", $statusCode = 0)
    {
        $this->statusCode = $statusCode === 0 ? $this->statusCode : $statusCode;
        
        return parent::__construct($message, $statusCode);
    }  
    
}
