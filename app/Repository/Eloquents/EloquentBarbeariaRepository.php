<?php declare(strict_types=1); 

namespace App\Repository\Eloquents;

use App\DTOS\BarbeariaFiltroDTO;
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

    public function listarBarbearia(BarbeariaFiltroDTO $barbeariaFiltroDTO): Collection
    { 
        if($barbeariaFiltroDTO->atributos_barbearia != null)
        {
            $this->selectAtributos('id,'.$barbeariaFiltroDTO->atributos_barbearia); 
        }
            if($barbeariaFiltroDTO->filtro_barbearia_validado != null)
            {
                $this->filtro($barbeariaFiltroDTO->filtro_barbearia_validado);
            }

            if($barbeariaFiltroDTO->atributos_user != null)
            {
                $this->selectAtributosRelacionamentos('user:id,barbearia_id,'. $barbeariaFiltroDTO->atributos_user);
            }
                else
                {
                    $this->selectAtributosRelacionamentos('user');
                }

                    return $this->getResultado(null);
    }

    public function save(Barbearia $barbearia): Barbearia
    {
        $barbearia->save();
        return $barbearia;
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