<?php declare(strict_types=1); 

namespace App\Services;

use App\DTOS\ServicosAtributosFiltrosDTO;
use App\Exceptions\NaoExisteRecursoException;
use App\Helpers\ValidarAtributos;
use App\Repository\AgendamentoServicoRepository;
use App\Repository\Contratos\AgendamentoServicoRepositoyInterface;
use App\Repository\Contratos\ServicoRepositoryInteface;
use App\Repository\ServicoRepository;

class ServicoService
{
    public function __construct(
       private ServicoRepositoryInteface $servicoRepository,
       private ValidarDomainService $validarService
    ){}
    
    public function listar(ServicosAtributosFiltrosDTO $servicoDto): object
    {

        $atributosServicoPermitido = ['id','nome','descricao','duracao_minutos','preco'];
        $atributosAgendamentoPermitidos = ['agendamentos.id','data','hora','status','id_barbeiro','id_cliente'];

       //atributos
        $servicoDto->atributos = ValidarAtributos::validarAtributos($servicoDto->atributos,$atributosServicoPermitido);

        //filtros
        $servicoDto->filtros_validos = ValidarAtributos::validarAtributosCondicao($servicoDto->filtros,$atributosServicoPermitido);

        $listaServico = $this->servicoRepository->listar($servicoDto);

        if(collect($listaServico)->isEmpty())
        {
            throw new NaoExisteRecursoException("Nenhuma listar encontrada");
        }

        return $listaServico;
        
    }

    public function precoTotal(int $id_agendamento): float
    {
        $this->validarService->validarExistenciaAgendamento($id_agendamento);
        $precoTotal = $this->servicoRepository->precoTotalPorAgendamento($id_agendamento); 
        return $precoTotal;
    }
   



       
}