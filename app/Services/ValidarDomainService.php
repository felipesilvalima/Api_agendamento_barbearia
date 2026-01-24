<?php declare(strict_types=1); 

namespace App\Services;

use App\DTOS\AgendamentosAtributosFiltrosPagincaoDTO;
use App\Exceptions\NaoExisteRecursoException;
use App\Repository\Contratos\AgendamentoServicoRepositoyInterface;
use App\Repository\Contratos\AgendamentosRepositoryInterface;
use App\Repository\Contratos\ClienteRepositoryInterface;
use App\Repository\Contratos\ServicoRepositoryInteface;
use DomainException;

class ValidarDomainService
{

    public function __construct(
        private AgendamentosRepositoryInterface $agendamentoRepository,
        private AgendamentoServicoRepositoyInterface $agendamento_ServicoRepository,
        private ServicoRepositoryInteface $servicoRepository
    ){}

        public function validarExistenciaAgendamento(int $id_agenda): void
        {
            if(!$this->agendamentoRepository->existeAgenda($id_agenda))
            {
                throw new NaoExisteRecursoException("Nenhuma agenda encontrada");
            }
        }

        public function validarLimiteAgendamentoPorCliente(int $id_cliente): void
        {
            if($this->agendamentoRepository->listar(new AgendamentosAtributosFiltrosPagincaoDTO(
                id_cliente: $id_cliente,
                atributos: "id,agendamento",
                filtro: "status:=:AGENDADO"

            ))->count() > 3)
            {
                throw new DomainException("Atingiu o máximo de agendamento. o Máximo de agendamento e 3 agendamento",403);
            }
        }

        public function validarExistenciaServico(int $id_servico): void
        {
            //verificar se existe servico
            if(!$this->servicoRepository->existeServico($id_servico))
            {
              throw new NaoExisteRecursoException("Serviço inválido. Esse serviço não existe");
            }
        }

        public function validarServicoExisteAgendamento(int $id_agendamento, int $id_servico): void
        {
            //verificar se o servico e do agendamento
            if(!$this->agendamento_ServicoRepository->existeServicoAgendamento($id_agendamento, $id_servico))
            {
                throw new NaoExisteRecursoException("Esse serviço não está relacionado com esse agendamento");
            }
        }
}