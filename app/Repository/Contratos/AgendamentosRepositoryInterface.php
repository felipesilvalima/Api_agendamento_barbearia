<?php declare(strict_types=1); 

namespace App\Repository\Contratos;

use App\DTOS\AgendamentoDTO;
use App\DTOS\AgendamentosAtributosFiltrosPagincaoDTO;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

interface AgendamentosRepositoryInterface
{
    public function salvar(AgendamentoDTO $agendamentoDto): object; 
    public function existeAgendamentoHorario(int $id_barbeiro, string $hora, string $data): bool;
    public function existeAgenda(int $id_agenda): bool;     
    public function listar(AgendamentosAtributosFiltrosPagincaoDTO $agendamentoDTO): Collection;
    public function detalhes(int $id_agenda): object;                             
  
}