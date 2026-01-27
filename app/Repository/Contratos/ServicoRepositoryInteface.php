<?php declare(strict_types=1); 

namespace App\Repository\Contratos;

use App\DTOS\ServicoDTO;
use App\DTOS\ServicosAtributosFiltrosDTO;

interface ServicoRepositoryInteface
{
     public function existeServico(int $id_servico): bool;
     public function listar(ServicosAtributosFiltrosDTO $servicosDto): object;
     public function precoTotalPorAgendamento(int $id_agendamento): float;
     public function salvarServicos(ServicoDTO $servicoDto): bool;
    
}