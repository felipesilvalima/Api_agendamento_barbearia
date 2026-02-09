<?php declare(strict_types=1); 

namespace App\Services;

use App\DTOS\AtualizarBarbeiroDTO;
use App\DTOS\BarbeiroAtributosFiltrosPaginacaoDTO;
use App\DTOS\BarbeiroDTO;
use App\Exceptions\ConflitoExecption;
use App\Exceptions\ErrorInternoException;
use App\Exceptions\NaoExisteRecursoException;
use App\Exceptions\NaoPermitidoExecption;
use App\Helpers\ValidarAtributos;
use App\Repository\Contratos\AuthRepositoryInterface;
use App\Repository\Contratos\BarbeiroRepositoryInterface;
use Illuminate\Http\Exceptions\HttpResponseException;
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

        $atributosClientePermitidos = ['id','nome','telefone','data_cadastro','status','barbearia_id'];
        $atributosAgendamentoPermitidos = ['id','data','hora','status','id_barbeiro','id_cliente','barbearia_id'];
        $atributosBarbeiroPermitido = ['id','nome','telefone','status','especialidade','barbearia_id'];
        $atributosServicoPermitido = ['id','nome','descricao','duracao_minutos','preco','barbearia_id'];
    
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
    
     public function atualizar(AtualizarBarbeiroDTO $atualizarBarbeiroDTO)
    {
        
        $this->validarService->validarExistenciaBarbeiro($atualizarBarbeiroDTO->barbeiro->id, "Não e possivel atualizar. Esse barbeiro não existe");

        if($atualizarBarbeiroDTO->nome === null && $atualizarBarbeiroDTO->telefone === null && $atualizarBarbeiroDTO->especialidade === null)
        {
            throw new HttpResponseException(response()->json([
                'status' => 'error',
                'mensagem' => 'Payload de dados vázio'
            ],422));
        }
            
           $barbeiro = $atualizarBarbeiroDTO->barbeiro->fill([
                'nome' => $atualizarBarbeiroDTO->nome ?? $atualizarBarbeiroDTO->barbeiro->nome,
                'telefone' => $atualizarBarbeiroDTO->telefone ?? $atualizarBarbeiroDTO->barbeiro->telefone,
                'especialidade' => $atualizarBarbeiroDTO->especialidade ?? $atualizarBarbeiroDTO->barbeiro->especialidade
            ]);

                if(!$barbeiro->isDirty(['nome','telefone','especialidade']))
                {
                    throw new ConflitoExecption("Nenhum dado foi alterado. Digite novos dados");
                }

                $barbeiro->save();

                    if(!$barbeiro)
                    {
                        throw new ErrorInternoException("Error ao atualizar dados de barbeiro");
                    }
    
    }

    
}