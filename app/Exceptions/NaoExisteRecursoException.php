<?php declare(strict_types=1); 

namespace App\Exceptions;

use Exception;

class NaoExisteRecursoException extends Exception
{
   public int $statusCode = 404;

   public function __construct($message = "Não foi possivel fazer o agendamento. Usuário não existe", $statusCode = 0)
   {
      $this->statusCode = $statusCode === 0 ? $this->statusCode : $statusCode;

      return parent::__construct($message, $this->statusCode);
   }
}
