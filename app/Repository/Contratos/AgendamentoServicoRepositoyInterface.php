<?php declare(strict_types=1); 

namespace App\Repository\Contratos;

use Illuminate\Database\Eloquent\Collection;

interface AgendamentoServicoRepositoyInterface
{
    public function vincular(int $id_agendamento, array $servicos, int $barbearia_id): bool;
    public function existeServicoAgendamento(int $id_agendamento, int $id_servico): bool;
    public function remover(int $id_agendamento, int $id_servico): int;
    public function listarPorAgendamento(int $id_agendamento): Collection;
    
   
}