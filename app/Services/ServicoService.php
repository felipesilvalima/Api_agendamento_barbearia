<?php declare(strict_types=1); 

namespace App\Services;

use App\Exceptions\NaoExisteRecursoException;
use App\Repository\AgendamentoServicoRepository;
use App\Repository\ServicoRepository;

class ServicoService
{

    public function __construct(
        private ValidarService $validarService,
        private AgendamentoServicoRepository $agendamento_servico_repository,
        private ServicoRepository $servicoRepository
    ){}
    
}