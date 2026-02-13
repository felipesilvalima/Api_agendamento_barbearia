<?php declare(strict_types=1); 

namespace App\Repository\Contratos;

use App\DTOS\ServicoDTO;
use App\DTOS\ServicosAtributosFiltrosDTO;
use Illuminate\Database\Eloquent\Collection;

interface ServicoRepositoryInteface
{
     public function existeServico(int $id_servico): bool;
     public function listar(ServicosAtributosFiltrosDTO $servicosDto): Collection;
     public function precoTotalPorAgendamento(int $id_agendamento): float;
     public function salvarServicos(ServicoDTO $servicoDto): bool;
     public function detalhes(int $id_servico): object;
    
}