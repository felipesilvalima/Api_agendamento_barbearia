<?php declare(strict_types=1); 

namespace App\Services;

use App\Exceptions\ErrorInternoException;
use App\Repository\Contratos\BarbeariaInterfaceRepository;
use Illuminate\Database\Eloquent\Collection;

class BarbeariaService
{
     public function __construct(
         private BarbeariaInterfaceRepository $barbeariaRepository,
         private ValidarDomainService $validarService
      )
    {
    }

    public function listar(): Collection
    {
       return $this->barbeariaRepository->listarBarbearia();
    }

    public function detalhes(int $id_barbearia): object
    {
      $this->validarService->validarExistenciaBarbearia($id_barbearia, "Não e possivel ver detalhes. Barbearia não existe");
      return $this->barbeariaRepository->detalhesBarbearia($id_barbearia);
    }

    public function remover(int $id_barbearia): void
    {
         $this->validarService->validarExistenciaBarbearia($id_barbearia, "Não e possivel ver detalhes. Barbearia não existe");

         $barbearia = $this->barbeariaRepository->detalhesBarbearia($id_barbearia);
         $barbearia->status = 'INATIVO';
         $barbearia->save();

         $barbearia_removida = $this->barbeariaRepository->removerBarbearia($id_barbearia);
        
         if(!$barbearia_removida)
         {
           throw new ErrorInternoException("Erro interno ao remover barbeiro");
         }

    }
}