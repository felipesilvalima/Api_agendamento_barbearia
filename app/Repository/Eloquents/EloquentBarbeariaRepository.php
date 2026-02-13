<?php declare(strict_types=1); 

namespace App\Repository\Eloquents;

use App\Models\Barbearia;
use App\Repository\Abstract\BaseRepository;
use App\Repository\Contratos\BarbeariaInterfaceRepository;
use Illuminate\Database\Eloquent\Collection;

class EloquentBarbeariaRepository extends BaseRepository implements BarbeariaInterfaceRepository
{ 
    public function __construct(private Barbearia $barbeariaModel)
    {
        return parent::__construct($barbeariaModel);
    }

    public function listarBarbearia(): Collection
    {
        return $this->getResultado(null);
    }

    public function save(Barbearia $barbearia): Barbearia
    { 
        $barbearia->save();
        return $barbearia;
    }

    public function removerBarbearia(int $id): bool
    {
        $this->buscarPorEntidade($id, 'id');
        $this->delete();
        return true;
    }

    public function existeBarbearia(int $barbearia_id): bool
    {
        return $this->existe($barbearia_id,null);
    }
}