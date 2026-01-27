<?php declare(strict_types=1); 

namespace App\Services;

use App\DTOS\ServicoDTO;
use App\DTOS\ServicosAtributosFiltrosDTO;
use App\Exceptions\ErrorInternoException;
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

    public function CadastrarServicos(ServicoDTO $criarServicoDto)
    {
        $this->validarService->validarExistenciaBarbeiro($criarServicoDto->id_barbeiro,"Não foi possivel criar o servico. Barbeiro não existe");
        $servico = $this->servicoRepository->salvarServicos($criarServicoDto);

        if($servico != true)
        {
            throw new ErrorInternoException("Error interno ao cadastrar servico");
        }

    }
   



       
}