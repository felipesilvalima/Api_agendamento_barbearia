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
        $this->selectAtributosRelacionamentos('user');
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
        $this->delete(null);
        return true;
    }

    public function existeBarbearia(int $id_barbearia): bool
    {
        return $this->existe($id_barbearia,null);
    }

    public function detalhesBarbearia(int $id_barbearia): object
    {
        $this->selectAtributosRelacionamentos('user');
        $this->buscarPorEntidade($id_barbearia, 'id');
        return $this->firstResultado(null);
    }
}