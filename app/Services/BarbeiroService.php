<?php declare(strict_types=1); 

namespace App\Services;

use App\DTOS\CriarBarbeiroDtos;
use App\Exceptions\ErrorInternoException;
use App\Exceptions\NaoPermitidoExecption;
use App\Repository\Contratos\AuthRepositoryInterface;
use App\Repository\Contratos\BarbeiroRepositoryInterface;
use Illuminate\Support\Facades\DB;

class BarbeiroService
{
     public function __construct(
        private BarbeiroRepositoryInterface $barbeiroRepository,
        private AuthRepositoryInterface $authRepository
    ){}

    public function CadastrarBarbeiro(CriarBarbeiroDtos $dtos)
    { 
        //criando o objeto
        $barbeiro = $dtos->createBarbeiroObjetc();

        DB::transaction(function () use($barbeiro) { 
 
            if(auth('api')->check() && !is_null(auth('api')->user()->id_barbeiro))
            {
                $barbeiro->setId_barbeiro($this->barbeiroRepository->salvarBarbeiro($barbeiro));

                if(!$barbeiro->getId_barbeiro())
                {
                    throw new ErrorInternoException();
                }
            }
                else
                {
                    throw new NaoPermitidoExecption();
                }

                $this->authRepository->salvarUsuario($barbeiro);

        });
    }
}