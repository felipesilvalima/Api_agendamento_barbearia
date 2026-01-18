<?php declare(strict_types=1); 

namespace App\Exceptions;

use DomainException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;

class Handler extends ExceptionHandler
{
    // /**
    //  * The list of the inputs that are never flashed to the session on validation exceptions.
    //  *
    //  * @var array<int, string>
    //  */
    // protected $dontFlash = [
    //     'current_password',
    //     'password',
    //     'password_confirmation',
    // ];

    public function render($request, Throwable $exception)
    {
        //tratando exeções personalizados
        if ($exception instanceof NaoExisteRecursoException) 
        {
            return response()->json([
                'status' => 'error',
                'message' => $exception->getMessage()
            ], $exception->statusCode);
        }

        return parent::render($request, $exception);
    }

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        //tratando exeções de domain
        $this->renderable(function (DomainException $exception) {
            return response()->json([
                'status' => 'error',
                'message' => $exception->getMessage(),
            ], $exception->getCode());
        });
        
        //tratando erros de abort
         $this->renderable(function (HttpExceptionInterface $exception) {
                return response()->json([
                    'message' => $exception->getMessage() ?: 'Erro na requisição',
                ], $exception->getStatusCode());
            });
    }

        
}
