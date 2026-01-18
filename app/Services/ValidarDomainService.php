<?php declare(strict_types=1); 

namespace App\Services;

use App\Exceptions\NaoExisteRecursoException;
use App\Exceptions\NaoPermitidoExecption;
use App\Repository\Contratos\AgendamentoServicoRepositoyInterface;
use App\Repository\Contratos\AgendamentosRepositoryInterface;
use App\Repository\Contratos\BarbeiroRepositoryInterface;
use App\Repository\Contratos\ClienteRepositoryInterface;
use App\Repository\Contratos\ServicoRepositoryInteface;
use DomainException;

class ValidarDomainService
{

    public function __construct(
        private BarbeiroRepositoryInterface $barbeiroRepository,
        private ClienteRepositoryInterface $clienteRepository,
        private AgendamentosRepositoryInterface $agendamentoRepository,
        private AgendamentoServicoRepositoyInterface $agendamento_ServicoRepository,
        private ServicoRepositoryInteface $servicoRepository, 
    ){}


        public function validaCliente(?int $id_cliente): void
        {
            if(!$this->clienteRepository->verificarClienteExiste($id_cliente))
            {
                throw new NaoExisteRecursoException();
            }
        }

        public function validaBarbeiro(?int $id_barbeiro): void
        {
            if(!$this->barbeiroRepository->verificarBarbeiroExiste($id_barbeiro))
            {
                throw new NaoExisteRecursoException("Não foi possivel fazer o agendamento. Barbeiro não existe");
            }
        }

        public function validarExistenciaAgendamento(int $id_agenda): void
        {
            if(!$this->agendamentoRepository->existeAgenda($id_agenda))
            {
                throw new NaoExisteRecursoException("Nenhuma agenda encontrada");
            }
        }

        public function validarLimiteAgendamentoPorCliente(int $id_cliente): void
        {
            if($this->agendamentoRepository->listaAgendasCliente($id_cliente)->count() > 3)
            {
                throw new DomainException("Atingiu o máximo de agendamento. o Máximo de agendamento e 3 agendamento",403);
            }
        }

        public function validarExistenciaServico(int $id_servico): void
        {
            //verificar se existe servico
            if(!$this->servicoRepository->existeServico($id_servico))
            {
              throw new NaoExisteRecursoException("Serviço não existe");
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