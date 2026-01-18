<?php declare(strict_types=1); 

namespace App\Services;

use App\DTOS\ClienteDTO;
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

    public function CadastrarCliente(ClienteDTO $clienteDto): void
    {
       
        DB::transaction(function () use($clienteDto) { 
 
            $clienteDto->id_cliente = $this->clienteRepository->salvarCliente($clienteDto);

                if(!$clienteDto->id_cliente)
                {
                    throw new ErrorInternoException();
                }
                 

                $this->authRepository->salvarUsuario($clienteDto);

        });
    }



}