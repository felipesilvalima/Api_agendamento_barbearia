<?php declare(strict_types=1); 

namespace App\Services;

use App\DTOS\BarbeiroDTO;
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

    public function CadastrarBarbeiro(BarbeiroDTO $BarbeiroDto): void
    { 
       
        DB::transaction(function () use($BarbeiroDto) { 
 
            $BarbeiroDto->id_barbeiro = $this->barbeiroRepository->salvarBarbeiro($BarbeiroDto);

                if(!$BarbeiroDto->id_barbeiro)
                {
                    throw new ErrorInternoException();
                }
            

                $this->authRepository->salvarUsuario($BarbeiroDto);

        });
    }
}