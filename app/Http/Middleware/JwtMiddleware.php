<?php declare(strict_types=1); 

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Exception;

class JwtMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $auth = $request->header('Authorization');
        if (!$auth || !preg_match('/Bearer\s(\S+)/', $auth, $matches)) {
            return response()->json(['error' => 'Token não fornecido'], 401);
        }

        $token = $matches[1];

        try {
            $secret = env('JWT_SECRET');
            $payload = JWT::decode($token, new Key($secret, 'HS256'));
            $request->attributes->set('jwt_payload', $payload);
        } catch (Exception $e) {
            return response()->json(['error' => 'Token inválido'], 401);
        }

        return $next($request);
    }
}