<?php declare(strict_types=1); 

namespace App\Repository\Abstract;

use App\DTOS\AgendamentosAtributosFiltrosPagincaoDTO;
use Illuminate\Database\Eloquent\Model;

Abstract class BaseRepository
{
    protected $query;

    public function __construct(private  Model $model)
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

    public function buscarPorUsuario(int $id, string $foreKey)
    {
        $this->query
        ->where($foreKey, $id);

        return $this;
    }

    public function getResultado()
    {
        return $this->query
        ->get();
    }

    public function existe(int $id): bool
    {
        return $this->model
                ->where('id', $id)
                ->exists();
    }
}
