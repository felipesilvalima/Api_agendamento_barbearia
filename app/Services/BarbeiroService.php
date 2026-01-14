<?php declare(strict_types=1); 

namespace App\Services;

use App\DTOS\CriarBarbeiroDtos;
use App\Exceptions\ErrorInternoException;
use App\Exceptions\NaoPermitidoExecption;
use App\Repository\AuthRepository;
use App\Repository\BarbeiroRepository;
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
        DB::transaction(function () use($dtos) { 
 
            if(auth('api')->check() && !is_null(auth('api')->user()->id_barbeiro))
            {
                $dtos->id_barbeiro = $this->barbeiroRepository->salvarBarbeiro($dtos);

                if(!$$dtos->id_barbeiro)
                {
                    throw new ErrorInternoException();
                }
            }
                else
                {
                    throw new NaoPermitidoExecption();
                }

                $this->authRepository->salvarUsuario($dtos);

        });
    }
}