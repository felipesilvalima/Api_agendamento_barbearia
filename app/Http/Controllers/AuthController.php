<?php declare(strict_types=1); 

namespace App\Http\Controllers;

use App\DTOS\LoginDTO;
use App\Helpers\GerarTokenRefresh;
use App\Http\Requests\AuthRequest;
use App\Http\Requests\UpdatePasswordRequest;
use App\Models\User;
use App\Services\AuthService;
use App\Services\ValidarDomainService;

class AuthController extends Controller
{
  use GerarTokenRefresh;

   public function __construct(
    private AuthService $authService
  ){}

  public function login(AuthRequest $request)
  {
      $credencias = $request->validated();

      $token_access = $this->authService->logarUsuario(new LoginDTO(
          email: $credencias['email'],
          password: $credencias['password']
        ));

        $token_refresh =  $this->gerarTokenRefresh($this->user()->id);
  
      return response()->json([
        "token_access" => $token_access,
        "token_refresh" => $token_refresh,
        "token_type" => "Bearer",
        "expires_in_token_access" => (int)getenv('JWT_TTL'),
        "expires_in_token_refresh" => 10080 

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
      $perfil = $this->authService->perfilUser($this->user());
      return response()->json($perfil,200);
  }

  public function uptdatePassword(AuthRequest $request)
  {
    
    if($request->filled('password')) {
    
      $senhaNova =  $request->validated();
  
      $this->authService->updatePassword($senhaNova, $this->user());
  
      return response()->json(['mensagem' => 'Senha atualizada com sucesso'],200);
    }
  }

  public function uptdateMe(AuthRequest $request)
  {
    
    if($request->filled('name')) {
    
      $name =  $request->validated();
  
      $this->authService->update($name, $this->user());
  
      return response()->json(['mensagem' => 'Usuário atualizada com sucesso'],200);
    }
  }

  public function desativarMe()
  {
    $this->authService->desativar($this->user());
    return response()->json(["mensagem" => 'Conta Desativada com sucesso'],200);
  }

  public function ativarMe()
  {
    $this->authService->ativar($this->user());
    return response()->json(["mensagem" => 'Conta Ativada com sucesso'],200);
  }

        private function user(): ?User
        {
            return auth('api')->user();
        }

 

}
