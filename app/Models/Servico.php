<?php declare(strict_types=1); 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Servico extends Model
{
    use HasFactory;

    protected $table = "servicos";
    protected $fillable = ["nome","descricao","duracao_minutos","preco"];


    public function agendamento_servico()
    {
        return $this->hasMany(Agendamento_servico::class,'id_servico','id');
    }
}
