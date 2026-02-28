<?php declare(strict_types=1); 

namespace App\Services;

use App\DTOS\BarbeariaDTO;
use App\DTOS\BarbeariaFiltroDTO;
use App\Exceptions\ErrorInternoException;
use App\Exceptions\NaoExisteRecursoException;
use App\Helpers\AgendamentoConfig;
use App\Helpers\ValidarAtributos;
use App\Models\Agendamento;
use App\Models\Barbearia;
use App\Models\User;
use App\Helpers\CacheData;
use App\Repository\Contratos\BarbeariaInterfaceRepository;
use Illuminate\Database\Eloquent\Collection;

class BarbeariaService
{
  use CacheData;
  use ValidarAtributos;
  use AgendamentoConfig;

     public function __construct(
         private BarbeariaInterfaceRepository $barbeariaRepository,
         private ValidarDomainService $validarService,
         private User $user,
         private Agendamento $agendamento,
      )
    {
    }

    public function listar(BarbeariaFiltroDTO $barbeariaFiltroDto): Collection
    {

        //atributos 
        foreach($this->regras_barbearia() as $campoDto => $atributosPermitidos)
        {
            $barbeariaFiltroDto->$campoDto =  $this->validarAtributos($barbeariaFiltroDto->$campoDto, $atributosPermitidos['atributos']);
        }

            //atributos condição
            foreach($this->regras_barbearia() as $campoDto => $filtro)
            {
                (string)$filtro_validado = $filtro['filtro_validado'] ?? null;
                (string)$filtro_request = $filtro['filtro'] ?? null;
            
                if(!isset($filtro_validado) &&  !isset($filtro_request))
                {
                  continue;
                }
                
                $barbeariaFiltroDto->$filtro_validado = $this->validarAtributosCondicao($barbeariaFiltroDto->$filtro_request ,$filtro['atributos']);
               
            }
               $cacheKey = 'barbearias-user-'. auth('api')->user()->id.'-list';
              return $this->verificarCache($cacheKey);

              $lista = $this->barbeariaRepository->listarBarbearia($barbeariaFiltroDto);

              if(collect($lista)->isEmpty())
              {
                throw new NaoExisteRecursoException("lista de barbearia está vázia");
              }

              $this->adicionarCache($cacheKey, $lista,getenv('JWT_TTL'));

              return $lista;
    }

    public function detalhes(int $id_barbearia): object
    {
      $this->validarService->validarExistenciaBarbearia($id_barbearia, "Não e possivel ver detalhes. Barbearia não existe");

       $cacheKey = 'barbearias-user-'. auth('api')->user()->id.'-details';
      return $this->verificarCache($cacheKey);

      $detalhesBarbearia = $this->barbeariaRepository->detalhesBarbearia($id_barbearia);

      $this->adicionarCache($cacheKey, $detalhesBarbearia,getenv('JWT_TTL'));

      return $detalhesBarbearia;
    }

    public function desativar(int $id_barbearia): void
    {
         $this->validarService->validarExistenciaBarbearia($id_barbearia, "Não e possivel ver detalhes. Barbearia não existe");

         $barbearia = $this->barbeariaRepository->detalhesBarbearia($id_barbearia);

        if($barbearia->status !== 'ATIVO')
        {
            abort(404,'Essa barbearia ja está desativada');
        }

         $barbearia->status = 'INATIVO';
         $barbearia->save();

         $users = $this->user->where('barbearia_id', $id_barbearia)?->get();
         $agendamentos = $this->agendamento->where('barbearia_id',$id_barbearia)?->get();

          if($users != null || $agendamentos != null)
          {
            foreach($users as $user)
            {
                $user->status = 'INATIVO';
                $user->save();
            }
  
  
            foreach($agendamentos as $agendamento)
            {
              if($agendamento->status === 'AGENDADO')
              {
                $agendamento->status = 'CANCELADO';
                $agendamento->save();
              }
            }

          }
          
    }

    public function ativar(int $id_barbearia): void
    {
       $barbearia = $this->barbeariaRepository->detalhesBarbearia($id_barbearia);
        
        if($barbearia->status !== 'INATIVO')
        {
            abort(404,'Essa barbearia não está desativada');
        }
        
        $barbearia->status = 'ATIVO';
        $barbearia->save();

        $users = $this->user->where('barbearia_id',$id_barbearia)?->get();

            if($users != null)
            {
                foreach($users as $user)
                {
                    $user->status = 'ATIVO';
                    $user->save();
                }
            }
    }
}