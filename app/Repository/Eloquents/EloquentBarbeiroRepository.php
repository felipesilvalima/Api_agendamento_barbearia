<?php declare(strict_types=1); 

namespace App\Repository\Eloquents;

use App\Repository\Abstract\BaseRepository;
use App\DTOS\BarbeiroDTO;
use App\Models\Barbeiro;
use App\Repository\Contratos\BarbeiroRepositoryInterface;

class EloquentBarbeiroRepository extends BaseRepository implements BarbeiroRepositoryInterface
{
    public function __construct(private Barbeiro $barbeiroModel)
    {
        parent::__construct($barbeiroModel);
    }

        public function existeBarbeiro($id_barbeiro): bool
        {
            return $this->existe($id_barbeiro);
            
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

            public function PerfilBarbeiro(int $id_barbeiro): object | bool
            {
                return $this->barbeiroModel
                ->select('id','nome','telefone','especialidade','status')
                ->with(['user:id,email,id_barbeiro'])
                ->where('id', $id_barbeiro)
                ?->first();
            }

}