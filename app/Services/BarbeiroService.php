<?php declare(strict_types=1); 

namespace App\Services;

use App\DTOS\AtualizarBarbeiroDTO;
use App\DTOS\BarbeiroAtributosFiltrosPaginacaoDTO;
use App\DTOS\BarbeiroDTO;
use App\Exceptions\ConflitoExecption;
use App\Exceptions\ErrorInternoException;
use App\Exceptions\NaoExisteRecursoException;
use App\Helpers\ValidarAtributos;
use App\Repository\Contratos\AuthRepositoryInterface;
use App\Repository\Contratos\BarbeiroRepositoryInterface;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\DB;

class BarbeiroService
{
    use ValidarAtributos;

     public function __construct(
        private BarbeiroRepositoryInterface $barbeiroRepository,
        private AuthRepositoryInterface $authRepository,
        private ValidarDomainService $validarService,
    ){}

    public function CadastrarBarbeiro(BarbeiroDTO $BarbeiroDto): void
    { 
       
        DB::transaction(function () use($BarbeiroDto) { 

           $BarbeiroDto->id_barbeiro = $this->authRepository->salvarUsuario($BarbeiroDto);
            
           if(!$BarbeiroDto->id_barbeiro)
           {
               throw new ErrorInternoException("error criar usuário de barbeiro");
           }
           
            $this->barbeiroRepository->salvarBarbeiro($BarbeiroDto);

        });
    }


    public function listar(BarbeiroAtributosFiltrosPaginacaoDTO $barbeiroDTO)
    {
        $this->validarService->validarExistenciaBarbeiro($barbeiroDTO->id_barbeiro, "Não e possivel listar. Esse barbeiro não existe");
        
        $regras = [
            'atributos' => ['id','user_id','telefone','status','especialidade','barbearia_id'],
            'atributos_cliente' => ['id','user_id','telefone','data_cadastro','status','barbearia_id'],
            'atributos_agendamento' => ['id','data','hora','status','id_barbeiro','id_cliente','barbearia_id'],
            'atributos_servico' => ['id','nome','descricao','duracao_minutos','preco','barbearia_id']
        ];
    
        //atributos 
        foreach($regras as $campoDto => $atributosPermitidos)
        {
            $barbeiroDTO->$campoDto =  $this->validarAtributos($barbeiroDTO->$campoDto, $atributosPermitidos);
        }

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

        if($atualizarBarbeiroDTO->telefone === null && $atualizarBarbeiroDTO->especialidade === null)
        {
            throw new HttpResponseException(response()->json([
                'status' => 'error',
                'mensagem' => 'Payload de dados vázio'
            ],422));
        }
            
           $barbeiro = $atualizarBarbeiroDTO->barbeiro->fill([
                'telefone' => $atualizarBarbeiroDTO->telefone ?? $atualizarBarbeiroDTO->barbeiro->telefone,
                'especialidade' => $atualizarBarbeiroDTO->especialidade ?? $atualizarBarbeiroDTO->barbeiro->especialidade
            ]);

                if(!$barbeiro->isDirty(['telefone','especialidade']))
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