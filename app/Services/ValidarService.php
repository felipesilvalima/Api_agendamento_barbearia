<?php declare(strict_types=1); 

namespace App\Services;

use App\Exceptions\NaoExisteRecursoException;
use App\Exceptions\NaoPermitidoExecption;
use App\Repository\AgendamentoRepository;
use App\Repository\BarbeiroRepository;
use App\Repository\ClienteRepository;

class ValidarService
{

    public function __construct(
        private BarbeiroRepository $barbeiroRepository,
        private ClienteRepository $clienteRepository,
        private AgendamentoRepository $agendamentoRepository
    ){}

        public function invalidaPermissaoBarbeiro()
        {
            if(!is_null(auth()->user()->id_barbeiro))
            {
                throw new NaoPermitidoExecption();
            }
        }

        public function invalidaPermissaoCliente()
        {
            if(!is_null(auth()->user()->id_cliente))
            {
                throw new NaoPermitidoExecption();
            }
        }

        public function validaCliente($id_cliente)
        {
            if(!$this->clienteRepository->verificarClienteExiste($id_cliente))
            {
                throw new NaoExisteRecursoException();
            }
        }

        public function validaBarbeiro($id_barbeiro)
        {
            if(!$this->barbeiroRepository->verificarBarbeiroExiste($id_barbeiro))
            {
                throw new NaoExisteRecursoException("Não foi possivel fazer o agendamento. Barbeiro não existe");
            }
        }

        public function validarExistenciaAgendamento($id_agenda)
        {
            if(!$this->agendamentoRepository->existeAgenda($id_agenda))
            {
                throw new NaoExisteRecursoException("Nenhuma agenda encontrada");
            }
        }

        public function validarPermissaoAgendaCliente($id_agenda, $cliente_id)
        {
            if(!$this->agendamentoRepository->existeAgendaCliente($id_agenda, $cliente_id))
            {
                throw new NaoPermitidoExecption(); 
            }
        }

        public function validarPermissaoAgendaBarbeiro($id_agenda, $id_barbeiro)
        {
            if(!$this->agendamentoRepository->existeAgendaBarbeiro($id_agenda, $id_barbeiro))
            {
                throw new NaoPermitidoExecption(); 
            }
        }
}