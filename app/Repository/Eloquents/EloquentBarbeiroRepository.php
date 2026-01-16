<?php declare(strict_types=1); 

namespace App\Repository\Eloquents;

use App\DTOS\BarbeiroDTO;
use App\DTOS\CriarBarbeiroDtos;
use App\Entitys\BarbeiroEntity;
use App\Models\Barbeiro;
use App\Repository\Contratos\BarbeiroRepositoryInterface;

class EloquentBarbeiroRepository implements BarbeiroRepositoryInterface
{
    public function __construct(private Barbeiro $barbeiroModel){}

        public function verificarBarbeiroExiste($id_barbeiro): bool
        {
            $result = $this->barbeiroModel->where('id', $id_barbeiro)->exists();
            return $result;
        }

            public function salvarBarbeiro(BarbeiroDTO $barbeiroDto): int
            {
                $cadastro = $this->barbeiroModel->create([
                    "nome" => $barbeiroDto->nome,
                    "telefone" => $barbeiroDto->telefone,
                    "especialidade" => $barbeiroDto->especialidade,
                    "status" => $barbeiroDto->status,
                ]);
        
                return $cadastro->id;
            }

            public function PerfilBarbeiro($id_barbeiro): object | bool
            {
                return $this->barbeiroModel
                ->select('id','nome','telefone','especialidade','status')
                ->with(['user:id,email,id_barbeiro'])
                ->where('id', $id_barbeiro)
                ?->first();
            }

}