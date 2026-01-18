<?php declare(strict_types=1); 

namespace App\Repository\Contratos;

use App\DTOS\AgendamentoDTO;

interface AgendamentosRepositoryInterface
{
    public function salvar(AgendamentoDTO $agendamentoDto): object; 
    
    public function existeAgendamentoHorario(int $id_barbeiro, string $hora, string $data): bool;
    public function existeAgenda(int $id_agenda): bool;     

    public function listaAgendasCliente(int $id_cliente): ?object;
    public function listaAgendasBarbeiro(int $id_barbeiro): ?object;

    public function buscarAgendaCliente(int $id_agenda): ?object;                             
    public function buscarAgendaBarbeiro(int $id_agenda): ?object;
      
    public function precoTotalTodosAgendamentos($cliente_id = null, $barbeiro_id = null): float;
    public function precoTotalAgendamento(int $id_agendamento): float;
}