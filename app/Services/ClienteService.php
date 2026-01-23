<?php declare(strict_types=1); 

namespace App\Services;

use App\DTOS\ClienteDTO;
use App\Exceptions\ErrorInternoException;
use App\Exceptions\NaoExisteRecursoException;
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
                    throw new ErrorInternoException("error ao criar cliente");
                }
                 

                $this->authRepository->salvarUsuario($clienteDto);

        });
    }

    public function listar(?int $id_cliente)
    {
       if(!$this->clienteRepository->existeCliente($id_cliente))
        {
            throw new NaoExisteRecursoException("Não e possivel listar. Esse cliente não existe");
        } 

       $lista = $this->clienteRepository->listar($id_cliente);

        if(collect($lista)->isEmpty())
        {
            throw new NaoExisteRecursoException("Listar de clientes vázia");
        }

        return $lista;
    }

}