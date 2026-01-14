<?php declare(strict_types=1); 

namespace App\Repository\Eloquents;

use App\DTOS\CriarClienteDtos;
use App\Models\Cliente;
use App\Repository\Contratos\ClienteRepositoryInterface;
use Carbon\Carbon;

class EloquentClienteRepository implements ClienteRepositoryInterface
{
    public function __construct(private Cliente $clienteModel){}

        public function verificarClienteExiste($id_cliente): bool
        {
            return $this->clienteModel->where('id', $id_cliente)->exists();
        }

        
    public function salvarCliente(CriarClienteDtos $dtos): int
    {

        $cadastro = $this->clienteModel->create([
            "nome" => $dtos->nome,
            "telefone" => $dtos->telefone,
            "data_cadastro" => Carbon::now(),
        ]);
        
        return $cadastro->id;
    }

    public function PerfilCliente($id_cliente): object | bool
    {
       return $this->clienteModel
       ->select('id','nome','telefone','data_cadastro')
       ->with(['user:id,email,id_cliente'])
       ->where('id', $id_cliente)
       ?->first();
    }


    
}