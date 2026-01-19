<?php declare(strict_types=1); 

namespace App\Repository\Contratos;

use App\DTOS\AgendamentoDTO;

interface AgendamentosRepositoryInterface
{
    public function salvar(AgendamentoDTO $agendamentoDto): object; 
    public function existeAgendamentoHorario(int $id_barbeiro, string $hora, string $data): bool;
    public function existeAgenda(int $id_agenda): bool;     
    public function listar(?int $cliente_id, ?int $barbeiro_id): iterable;
    public function detalhes(int $id_agenda): object;                             
  
}