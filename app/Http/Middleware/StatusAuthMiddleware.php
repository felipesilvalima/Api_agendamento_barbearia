<?php declare(strict_types=1); 

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class StatusAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(auth('api')->user()->status != null)
        {
            if(auth('api')->user()->status != 'ATIVO'){
                abort(403,'Conta Desativada');
            }
        }
        
        return $next($request);
    }
}
