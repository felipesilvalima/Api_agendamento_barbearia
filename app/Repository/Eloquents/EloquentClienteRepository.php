<?php declare(strict_types=1); 

namespace App\Repository\Eloquents;

use App\DTOS\AgendamentosAtributosFiltrosPagincaoDTO;
use App\Repository\Abstract\BaseRepository;
use App\DTOS\ClienteDTO;
use App\Models\Cliente;
use App\Repository\Contratos\ClienteRepositoryInterface;
use Carbon\Carbon;

class EloquentClienteRepository extends BaseRepository implements ClienteRepositoryInterface
{
    public function __construct(private Cliente $clienteModel)
    {
        parent::__construct($clienteModel);
    }

    public function existeCliente($id_cliente): bool
    {
            return $this->existe($id_cliente);
    }

        
    public function salvarCliente(ClienteDTO $clienteDto): int
    {

        $cadastro = $this->clienteModel->create([
            "nome" => $clienteDto->nome,
            "telefone" => $clienteDto->telefone,
            "data_cadastro" => Carbon::now(),
        ]);
        
        return $cadastro->id;
    }

    public function PerfilCliente(int $id_cliente): object | bool
    {
       return $this->clienteModel
       ->select('id','nome','telefone','data_cadastro')
       ->with(['user:id,email,id_cliente'])
       ->where('id', $id_cliente)
       ?->first();
    }

    public function listar(int $id_cliente): iterable
    {
        return $this->findAll(new AgendamentosAtributosFiltrosPagincaoDTO(
           id_cliente: $id_cliente
           
        ));
    }


    
}