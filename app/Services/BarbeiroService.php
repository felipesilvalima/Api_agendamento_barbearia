<?php declare(strict_types=1); 

namespace App\Services;

use App\Exceptions\ErrorInternoException;
use App\Exceptions\NaoPermitidoExecption;
use App\Repository\AuthRepository;
use App\Repository\BarbeiroRepository;
use Illuminate\Support\Facades\DB;

class BarbeiroService
{
     public function __construct(
        private BarbeiroRepository $barbeiroRepository,
        private AuthRepository $authRepository
    ){}

    public function CadastrarBarbeiro(array $data)
    { 
        DB::transaction(function () use($data) { 
 
            if(auth('api')->check() && !is_null(auth('api')->user()->id_barbeiro))
            {
                $barbeiro_id = $this->barbeiroRepository->salvarBarbeiro($data);

                if(!$barbeiro_id)
                {
                    throw new ErrorInternoException();
                }
            }
                else
                {
                    throw new NaoPermitidoExecption();
                }

                $this->authRepository->salvarUsuario($data, null, $barbeiro_id);

        });
    }
}