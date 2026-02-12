<?php declare(strict_types=1); 

namespace App\Services;

use App\DTOS\AgendamentosAtributosFiltrosPagincaoDTO;
use App\Exceptions\NaoExisteRecursoException;
use App\Models\User;
use App\Repository\Contratos\AgendamentoServicoRepositoyInterface;
use App\Repository\Contratos\AgendamentosRepositoryInterface;
use App\Repository\Contratos\AuthRepositoryInterface;
use App\Repository\Contratos\BarbeariaInterfaceRepository;
use App\Repository\Contratos\BarbeiroRepositoryInterface;
use App\Repository\Contratos\ClienteRepositoryInterface;
use App\Repository\Contratos\NotificacaoRepositoryInterface;
use App\Repository\Contratos\ServicoRepositoryInteface;
use DomainException;

class ValidarDomainService
{

    public function __construct(
        private AgendamentosRepositoryInterface $agendamentoRepository,
        private AgendamentoServicoRepositoyInterface $agendamento_ServicoRepository,
        private ServicoRepositoryInteface $servicoRepository,
        private BarbeiroRepositoryInterface $barbeiroRepository,
        private ClienteRepositoryInterface $clienteRepository,
        private AuthRepositoryInterface $authRepository,
        private NotificacaoRepositoryInterface $notificaoRepository,
        private BarbeariaInterfaceRepository $barbeariaRepository
    ){}

        public function validarExistenciaAgendamento(int $id_agenda): void
        {
            if(!$this->agendamentoRepository->existeAgenda($id_agenda))
            {
                throw new NaoExisteRecursoException("Nenhuma agenda encontrada");
            }
        }

        public function validarLimiteAgendamentoPorCliente(User $clienteUser): void
        {
            if($this->agendamentoRepository->listar(new AgendamentosAtributosFiltrosPagincaoDTO(
                user: $clienteUser,
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

        public function validarExistenciaBarbeiro(int $id_barbeiro, string $mensagem)
        {
            if(!$this->barbeiroRepository->existeBarbeiro($id_barbeiro))
            {
                throw new NaoExisteRecursoException($mensagem);
            }
        }

        public function validarExistenciaCliente(int $id_cliente, string $mensagem)
        {
            if(!$this->clienteRepository->existeCliente($id_cliente))
            {
                throw new NaoExisteRecursoException($mensagem);
            }
        }

        public function validarExistenciaUsuario(int $id_user, string $mensagem)
        {
            if(!$this->authRepository->existeUsuario($id_user))
            {
                throw new NaoExisteRecursoException($mensagem);
            }
        }

        public function validarExistenciaNoticacao(int $id_notificao, string $mensagem)
        {
            if(!$this->notificaoRepository->existeNotificao($id_notificao))
            {
                throw new NaoExisteRecursoException($mensagem);
            }
        }

        public function validarExistenciaBarbearia(int $id_barbearia, string $mensagem)
        {
            if(!$this->barbeariaRepository->existeBarbearia($id_barbearia))
            {
                throw new NaoExisteRecursoException($mensagem);
            }
        }
}