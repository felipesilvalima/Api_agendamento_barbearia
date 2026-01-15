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
        //criando o objeto
        $cliente = $dtos->createClienteObjetc();

        DB::transaction(function () use($cliente) { 
 
            $cliente->setId_cliente($this->clienteRepository->salvarCliente($cliente));

                if(!$cliente->getId_cliente())
                {
                    throw new ErrorInternoException();
                }
                 

                $this->authRepository->salvarUsuario($cliente);

        });
    }



}