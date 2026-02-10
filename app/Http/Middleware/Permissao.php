<?php declare(strict_types=1); 

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Permissao
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $perfil): Response
    {
        switch($perfil)
        {
            case 'Cliente':
                if(auth('api')->user()->role !== 'cliente')
                {
                    abort(403,"Você não tem permissão para acessar essa rota");
                }
                    return $next($request);
            break;
            case 'Barbeiro':
                if(auth('api')->user()->role !== 'barbeiro')
                {
                    abort(403,"Você não tem permissão para acessar essa rota");
                }
                    return $next($request);
            break;
            case 'Cliente|Barbeiro':

                if(!in_array(auth('api')->user()->role, ['cliente','barbeiro']))
                {
                    abort(403,"Você não tem permissão para acessar essa rota");
                }
                    return $next($request);
            break;
            default:
                abort(404,"Não existe esse tipo de perfil");
        }

      
    }
}
