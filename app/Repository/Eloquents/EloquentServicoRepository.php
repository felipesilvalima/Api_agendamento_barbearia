<?php declare(strict_types=1); 

namespace App\Repository\Eloquents;

use App\Repository\Abstract\BaseRepository;
use App\Models\Servico;
use App\Repository\Contratos\ServicoRepositoryInteface;

class EloquentServicoRepository extends BaseRepository implements ServicoRepositoryInteface
{
    public function __construct(
        private Servico $servicoModel, 
    )
    {
        parent::__construct($servicoModel);
    }
    
    public function existeServico(int $id_servico): bool
    {
        return $this->existe($id_servico);
    }

    public function listar():object
    {
        return $this->getResultado();
    }
}