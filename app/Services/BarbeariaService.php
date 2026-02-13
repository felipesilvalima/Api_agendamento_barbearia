<?php declare(strict_types=1); 

namespace App\Services;

use App\Repository\Contratos\BarbeariaInterfaceRepository;

class BarbeariaService
{
     public function __construct(private BarbeariaInterfaceRepository $barbeariaRepository)
    {
    }

    public function listar()
    {
        $this->barbeariaRepository->listarBarbearia();
    }
}