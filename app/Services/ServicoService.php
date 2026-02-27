<?php declare(strict_types=1); 

namespace App\Services;

use App\DTOS\AtualizarServicoDTO;
use App\DTOS\ServicoDTO;
use App\DTOS\ServicosAtributosFiltrosDTO;
use App\Exceptions\ConflitoExecption;
use App\Exceptions\ErrorInternoException;
use App\Exceptions\NaoExisteRecursoException;
use App\Helpers\ValidarAtributos;
use App\Repository\AgendamentoServicoRepository;
use App\Repository\Contratos\AgendamentoServicoRepositoyInterface;
use App\Repository\Contratos\ServicoRepositoryInteface;
use App\Repository\ServicoRepository;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Storage;
use App\Helpers\CacheData;

class ServicoService
{
    use ValidarAtributos;
    use CacheData;

    public function __construct(
       private ServicoRepositoryInteface $servicoRepository,
       private ValidarDomainService $validarService
    ){}
    
    public function listar(ServicosAtributosFiltrosDTO $servicoDto): object
    {

        $atributosServicoPermitido = ['id','nome','descricao','duracao_minutos','preco','barbearia_id'];

       //atributos
        $servicoDto->atributos = $this->validarAtributos($servicoDto->atributos,$atributosServicoPermitido);

        //filtros
        $servicoDto->filtros_validos = $this->validarAtributosCondicao($servicoDto->filtros,$atributosServicoPermitido);

        $cacheKey = 'servico:list';
        return $this->verificarCache($cacheKey);

        $listaServico = $this->servicoRepository->listar($servicoDto);

        if(collect($listaServico)->isEmpty())
        {
            throw new NaoExisteRecursoException("Nenhuma listar encontrada");
        }
        
        $this->adicionarCache($cacheKey, $listaServico,getenv('JWT_TTL'));

        return $listaServico;
        
    }

    public function detalhes(int $id_servico): object
    {
        $this->validarService->validarExistenciaServico($id_servico);

        $cacheKey = 'servico:list';
        return $this->verificarCache($cacheKey);

        $servico =  $this->servicoRepository->detalhes($id_servico);

        $this->adicionarCache($cacheKey, $listaServico,getenv('JWT_TTL'));

        return $servico;
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

        //salvar a imagem no servidor
        $criarServicoDto->path = $criarServicoDto->imagem->store('imagens/servicos','public');
      
        //salvar servico
        $servico = $this->servicoRepository->salvarServicos($criarServicoDto);

        if($servico != true)
        {
            throw new ErrorInternoException("Error interno ao cadastrar servico");
        }

    }

    public function atualizar(AtualizarServicoDTO $atualizarServicoDto)
    {
         $this->validarService->validarExistenciaBarbeiro($atualizarServicoDto->id_barbeiro, "Não e possivel atualizar. Esse Barbeiro não existe");
         $this->validarService->validarExistenciaServico($atualizarServicoDto->id_servico);
           
        if($atualizarServicoDto->descricao === null && $atualizarServicoDto->preco === null && $atualizarServicoDto->imagem === null)
        {
            throw new HttpResponseException(response()->json([
                'status' => 'error',
                'mensagem' => 'Payload de dados vázio'
            ],422));
        }

           $servico = $this->servicoRepository->detalhes($atualizarServicoDto->id_servico);

            //salvar a imagem no servidor
            if($atualizarServicoDto->imagem != null)
            {
                $atualizarServicoDto->path = $atualizarServicoDto->imagem->store('imagens/servicos');
            }
          
           $servico->fill([
                'descricao' => $atualizarServicoDto->descricao ?? $servico->descricao,
                'preco' => $atualizarServicoDto->preco ?? $servico->preco,
                'imagem' => $atualizarServicoDto->path ?? $servico->imagem
            ]);

                if(!$servico->isDirty(['descricao','preco','imagem']))
                {
                    throw new ConflitoExecption("Nenhum dado foi alterado. Digite novos dados");
                }
                
                //deleta a imagem antiga
                if($servico->imagem != null)
                {
                    Storage::disk('public')->delete($servico->imagem);
                }

                $servico->save();

                    if(!$servico)
                    {
                        throw new ErrorInternoException("Error ao atualizar dados de cliente");
                    }    
    }

    public function desativar(int $id_barbeiro , int $id_servico)
    {
        //validação de segurança
        $this->validarService->validarExistenciaBarbeiro($id_barbeiro,"Não e possivel remover servico. Barbeiro não existe");
        $this->validarService->validarExistenciaServico($id_servico);
        
        $servico = $this->servicoRepository->detalhes($id_servico);
        
        //desativar
        $servico->delete();

        if(!$servico)
        {
            throw new ErrorInternoException("Error ao desativar servico");
        } 

    }
   



       
}