<?php declare(strict_types=1); 

namespace App\Repository;

use App\DTOS\CriarBarbeiroDtos;
use App\Models\Barbeiro;

class BarbeiroRepository
{
    public function __construct(private Barbeiro $barbeiroModel){}

        public function verificarBarbeiroExiste($id_barbeiro): bool
        {
            $result = $this->barbeiroModel->where('id', $id_barbeiro)->exists();
            return $result;
        }

            public function salvarBarbeiro(CriarBarbeiroDtos $dtos): int
            {
                
                $dtos->especialidade = !isset($dtos->especialidade) ? "Barbeiro Completo" : $dtos->especialidade;

                $cadastro = $this->barbeiroModel->create([
                    "nome" => $dtos->nome,
                    "telefone" => $dtos->telefone,
                    "especialidade" => $dtos->especialidade,
                    "status" => $dtos->status,
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