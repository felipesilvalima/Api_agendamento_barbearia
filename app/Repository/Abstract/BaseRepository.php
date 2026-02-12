<?php declare(strict_types=1); 

namespace App\Repository\Abstract;

use App\DTOS\AgendamentosAtributosFiltrosPagincaoDTO;
use App\Models\Agendamento;
use Illuminate\Database\Eloquent\Model;

Abstract class BaseRepository
{
    protected $query;
    protected $queryRelacional;

    public function __construct(private  Model $model,)
    {
        $this->query = $model->query();
    }


    public function selectAtributos(string $atributos)
    {
       $this->query->selectRaw($atributos);
       return $this;
    }

    public function filtro(array $filtros)
    {
        foreach($filtros as $condicao)
        {
            $f = explode(':',$condicao);
            $this->query->where($f[0],$f[1],$f[2]);
        }

        return $this;
    }

    public function selectAtributosRelacionamentos(string $atributosRelacionamento)
    {
        $this->query->with($atributosRelacionamento);
        return $this;
    }

    public function filtroRelacionamento(array $filtrosRelacionamento, string $entidadeRelacionada)
    { 
        $this->query->whereHas($entidadeRelacionada, function($b) use($filtrosRelacionamento)
        {
            foreach($filtrosRelacionamento as $condicao_relacionamento)
            {
                $cb = explode(':',$condicao_relacionamento);
                $b->where($cb[0],$cb[1],$cb[2]);
            }
        });

        return $this;
    }

    public function paginacao(string $pagina, string $limite)
    {
        $offset = ($pagina - 1) * $limite;
                            
            $this->query
            ->limit($limite)
            ->offset($offset);

            return $this;
    }

    public function buscarPorEntidade(int $id, string $colum)
    {
        $this->query
        ->where($colum, $id);

        return $this;
    }

    public function getResultado(?string $tenat = 'barbearia_id')
    {
        return $this->query
        ->when($tenat, fn($q) => $q->where($tenat, $this->tenant()))
        ->get();
    }

    public function firstResultado(?string $tenat = 'barbearia_id')
    {
        return $this->query
        ->when($tenat, fn($q) => $q->where($tenat, $this->tenant()))
        ?->first();
    }


    public function existe(int $id,?string $tenat = 'barbearia_id'): bool
    {
        return $this->query
                ->where('id', $id)
                ->when($tenat, fn($q) => $q->where($tenat, $this->tenant()))
                ->exists();
    }

    public function delete(?string $tenat = 'barbearia_id')
    {
       return $this->query
       ->when($tenat, fn($q) => $q->where($tenat, $this->tenant()))
       ->delete();
    }

    protected function tenant(): int
    {
      return  auth('api')->user()->barbearia_id;
    }

}
