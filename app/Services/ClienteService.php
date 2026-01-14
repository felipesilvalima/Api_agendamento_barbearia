<?php declare(strict_types=1); 

namespace App\Services;

use App\DTOS\CriarClienteDtos;
use App\Exceptions\ErrorInternoException;
use App\Exceptions\NaoExisteRecursoException;
use App\Repository\AgendamentoRepository;
use App\Repository\AuthRepository;
use App\Repository\ClienteRepository;
use Illuminate\Support\Facades\DB;

class ClienteService
{
    public function __construct(
        private ClienteRepository $clienteRepository,
        private AuthRepository $authRepository,
        private ValidarService $validarService
        
    ){}

    public function CadastrarCliente(CriarClienteDtos $dtos)
    {
        DB::transaction(function () use($dtos) { 
 
            $cliente_id = $this->clienteRepository->salvarCliente($dtos);

                if(!$cliente_id)
                {
                    throw new ErrorInternoException();
                }
                 

                $this->authRepository->salvarUsuario($dtos);

        });
    }



}