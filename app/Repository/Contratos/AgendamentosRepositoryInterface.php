<?php declare(strict_types=1); 

namespace App\Repository\Contratos;

use App\DTOS\AgendamentoDTO;
use App\DTOS\CriarAgendamentosDtos;
use App\Entitys\AgendamentoEntity;
use App\Models\Agendamento;

interface AgendamentosRepositoryInterface
{
    public function salvar(AgendamentoDTO $agendamentoDto): Agendamento; 
    
    public function existeAgendamentoHorario(int $id_barbeiro, string $hora, string $data): bool;
    public function existeAgenda($id_agenda): bool;     
    public function existeAgendaCliente($id_agenda, $cliente_id): bool;
    public function existeAgendaBarbeiro($id_agenda, $barbeiro_id): bool;
    
    public function listaAgendasCliente($id_cliente): object;
    public function listaAgendasBarbeiro($id_barbeiro): object;

    public function buscarAgendaCliente(int $id_agenda): object;                             
    public function buscarAgendaBarbeiro(int $id_agenda): object;
      
    public function precoTotalTodosAgendamentos($cliente_id = null, $barbeiro_id = null): float;
    public function precoTotalAgendamento(int $id_agendamento): float;
}