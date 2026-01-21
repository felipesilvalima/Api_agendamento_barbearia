<?php declare(strict_types=1); 

namespace App\Http\Controllers;

use App\DTOS\LoginDTO;
use App\Http\Requests\AuthRequest;
use App\Http\Requests\UpdatePasswordRequest;
use App\Services\AuthService;
use App\Services\ValidarDomainService;

class AuthController extends Controller
{
   public function __construct(
    private AuthService $authService,
    private ValidarDomainService $validarService
    
  ){}

  public function login(AuthRequest $request)
  {
   
        $credencias = $request->validated();

        $dtos = new LoginDTO(
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

  public function uptdateMe(UpdatePasswordRequest $request)
  {
    
    if ($request->filled('password')) {
    
      $senhaNova =  $request->validated();
  
      $this->authService->update($senhaNova, auth('api')->user());
  
      return response()->json(['mensagem' => 'Senha atualizada com sucesso'],200);
    }
  }

  public function desativarMe()
  {
    $this->authService->delete(auth('api')->user());

    return response()->json(["mensagem" => 'Conta Desativada'],200);
  }

 

}
