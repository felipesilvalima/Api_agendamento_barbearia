<?php declare(strict_types=1); 

namespace App\Repository\Contratos;

use App\DTOS\AgendamentoDTO;

interface AgendamentosRepositoryInterface
{
    public function salvar(AgendamentoDTO $agendamentoDto): object; 
    public function existeAgendamentoHorario(int $id_barbeiro, string $hora, string $data): bool;
    public function existeAgenda(int $id_agenda): bool;     
    public function listar(
        ?int $cliente_id,
        ?int $barbeiro_id,
        ?string $atributos,
        ?string $atributos_barbeiro,
        ?string $atributos_cliente,
        ?array $condicao_atributo,
        ?array $condicao_atributo_barbeiro,
        ?array $condicao_atributo_cliente,
        ?int $limit,
        ?int $page
    ): iterable;
    public function detalhes(int $id_agenda): object;                             
  
}