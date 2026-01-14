<?php declare(strict_types=1); 

namespace App\Http\Controllers;

use App\DTOS\LoginDtos;
use App\Http\Requests\AuthRequest;
use App\Http\Requests\RequestCliente;
use App\Models\User;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
   public function __construct(private AuthService $authService){}

  public function login(AuthRequest $request)
  {
        $credencias = $request->validated();

        $dtos = new LoginDtos(
          email: $credencias['email'],
          password: $credencias['password']
        );
        $token = $this->authService->logarUsuario($dtos);
  
      return response()->json([
        "token" => $token
        ],200);

  }

  public function logout()
  {
    auth('api')->logout();
    return response()->json(["mensagem" => "logout realizado com sucesso"]);
  }

  public function refresh()
  {
    $token = auth('api')->refresh();
    return response()->json(["token" => $token]);
  }
  
  public function me()
  { 
      $perfil = $this->authService->perfilUser(auth('api')->user());
      return response()->json($perfil,200);
  }

  public function deletarPerfil()
  {

  }

}
