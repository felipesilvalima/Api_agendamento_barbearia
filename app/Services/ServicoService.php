<?php declare(strict_types=1); 

namespace App\Services;

use App\Exceptions\NaoExisteRecursoException;
use App\Repository\AgendamentoServicoRepository;
use App\Repository\Contratos\AgendamentoServicoRepositoyInterface;
use App\Repository\Contratos\ServicoRepositoryInteface;
use App\Repository\ServicoRepository;

class ServicoService
{
    public function __construct(
       private ServicoRepositoryInteface $servicoRepository 
    ){}
    
    public function listar(): object
    {
        $listaServico = $this->servicoRepository->listar();

        if(collect($listaServico)->isEmpty())
        {
            throw new NaoExisteRecursoException("Nenhuma listar encontrada");
        }

        return $listaServico;
        
    }
   



       
}