<?php declare(strict_types=1); 

namespace App\Repository\Eloquents;

use App\DTOS\BarbeiroAtributosFiltrosPaginacaoDTO;
use App\Repository\Abstract\BaseRepository;
use App\DTOS\BarbeiroDTO;
use App\Models\Barbeiro;
use App\Repository\Contratos\BarbeiroRepositoryInterface;

class EloquentBarbeiroRepository extends BaseRepository implements BarbeiroRepositoryInterface
{
    public function __construct(private Barbeiro $barbeiroModel)
    {
        parent::__construct($barbeiroModel);
    }

        public function existeBarbeiro($id_barbeiro): bool
        {
            return $this->existe($id_barbeiro);
            
        }

            public function salvarBarbeiro(BarbeiroDTO $barbeiroDto): int
            {
                $cadastro = $this->barbeiroModel->create([
                    "telefone" => $barbeiroDto->telefone,
                    "especialidade" => $barbeiroDto->especialidade,
                    "status" => $barbeiroDto->status,
                    "user_id" => $barbeiroDto->id_barbeiro,
                    "barbearia_id" => $barbeiroDto->barbearia_id
                ]);
        
                return $cadastro->id;
            }

            public function PerfilBarbeiro(int $id_barbeiro): object | bool
            {
                $this->selectAtributos('id,telefone,especialidade,status,user_id');
                $this->selectAtributosRelacionamentos('user');
                $this->filtro(["id:=:$id_barbeiro"]);
                return $this->firstResultado();
            }

            public function listar(BarbeiroAtributosFiltrosPaginacaoDTO $barbeiroDTO): object
            {
                if($barbeiroDTO->atributos != null)
                {
                    //atributos
                    $this->selectAtributos('id,'.$barbeiroDTO->atributos);
                }
                    //atributos do user
                    $this->selectAtributosRelacionamentos('user');
                
                    if($barbeiroDTO->atributos_cliente != null)
                    {
                        //atributos de barbeiro
                        $this->selectAtributosRelacionamentos('agendamento.cliente:id,'.$barbeiroDTO->atributos_cliente);
                    }
                        else
                        {
                           $this->selectAtributosRelacionamentos('agendamento.cliente');
                        }

                            if($barbeiroDTO->atributos_servico != null)
                            {
                                //atributos de servico
                                $this->selectAtributosRelacionamentos('agendamento.servico:id,'.$barbeiroDTO->atributos_servico);
                            }
                                else
                                {
                                   $this->selectAtributosRelacionamentos('agendamento.servico');
                                }

                                //atributos de agendamento
                                if($barbeiroDTO->atributos_agendamento != null)
                                {

                                    $this->selectAtributosRelacionamentos('agendamento:id,id_cliente,id_barbeiro,'.$barbeiroDTO->atributos_agendamento);
                                }
                                    else
                                    {
                                        $this->selectAtributosRelacionamentos('agendamento');
                                    }
                                        
                                        $this->buscarPorEntidade($barbeiroDTO->id_barbeiro,'id');
                                        return $this->firstResultado();
            }

            public function detalhes(int $id_barbeiro): object
            {
                $this->buscarPorEntidade($id_barbeiro,'id');
                return $this->firstResultado();
            }

}