<?php declare(strict_types=1); 

namespace App\Services;

use App\DTOS\CriarClienteDtos;
use App\Exceptions\ErrorInternoException;
use App\Repository\Contratos\AuthRepositoryInterface;
use App\Repository\Contratos\ClienteRepositoryInterface;
use Illuminate\Support\Facades\DB;

class ClienteService
{
    public function __construct(
        private ClienteRepositoryInterface $clienteRepository,
        private AuthRepositoryInterface $authRepository, 
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