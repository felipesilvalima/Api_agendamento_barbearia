<?php declare(strict_types=1); 

namespace App\Services;

use App\DTOS\BarbeiroAtributosFiltrosPaginacaoDTO;
use App\DTOS\BarbeiroDTO;
use App\Exceptions\ErrorInternoException;
use App\Exceptions\NaoExisteRecursoException;
use App\Exceptions\NaoPermitidoExecption;
use App\Helpers\ValidarAtributos;
use App\Repository\Contratos\AuthRepositoryInterface;
use App\Repository\Contratos\BarbeiroRepositoryInterface;
use Illuminate\Support\Facades\DB;

class BarbeiroService
{
     public function __construct(
        private BarbeiroRepositoryInterface $barbeiroRepository,
        private AuthRepositoryInterface $authRepository,
        private ValidarDomainService $validarService,
    ){}

    public function CadastrarBarbeiro(BarbeiroDTO $BarbeiroDto): void
    { 
       
        DB::transaction(function () use($BarbeiroDto) { 
 
            $BarbeiroDto->id_barbeiro = $this->barbeiroRepository->salvarBarbeiro($BarbeiroDto);

                if(!$BarbeiroDto->id_barbeiro)
                {
                    throw new ErrorInternoException("error criar barbeiro");
                }
            

                $this->authRepository->salvarUsuario($BarbeiroDto);

        });
    }


    public function listar(BarbeiroAtributosFiltrosPaginacaoDTO $barbeiroDTO)
    {
        $this->validarService->validarExistenciaBarbeiro($barbeiroDTO->id_barbeiro, "Não e possivel listar. Esse barbeiro não existe");

        $atributosClientePermitidos = ['id','nome','telefone','data_cadastro','status'];
        $atributosAgendamentoPermitidos = ['id','data','hora','status','id_barbeiro','id_cliente'];
        $atributosBarbeiroPermitido = ['id','nome','telefone','status','especialidade'];
        $atributosServicoPermitido = ['id','nome','descricao','duracao_minutos','preco'];
    
        //atributos 
        $barbeiroDTO->atributos =  ValidarAtributos::validarAtributos($barbeiroDTO->atributos, $atributosBarbeiroPermitido);
        $barbeiroDTO->atributos_agendamento =  ValidarAtributos::validarAtributos($barbeiroDTO->atributos_agendamento, $atributosAgendamentoPermitidos);
        $barbeiroDTO->atributos_cliente =  ValidarAtributos::validarAtributos($barbeiroDTO->atributos_cliente, $atributosClientePermitidos);
        $barbeiroDTO->atributos_servico =  ValidarAtributos::validarAtributos($barbeiroDTO->atributos_servico, $atributosServicoPermitido);

        $lista = $this->barbeiroRepository->listar($barbeiroDTO);

            if(collect($lista)->isEmpty())
            {
                throw new NaoExisteRecursoException("Listar de clientes vázia");
            }

                return $lista;
    }

    public function detalhes(int $id_barbeiro)
    {
         $detalhes = $this->barbeiroRepository->detalhes($id_barbeiro);
         return $detalhes;
    }
    

    
}