<?php declare(strict_types=1); 

namespace App\Models;

use App\Helpers\TenantScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Helpers\CacheKeyInvalid;

class Servico extends Model
{
    use HasFactory;
    use SoftDeletes;
    use CacheKeyInvalid;

    protected $table = "servicos";
    protected $fillable = ["nome","descricao","duracao_minutos","preco","imagem","barbearia_id"];

     protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];


    public function agendamento()
    {
        return $this->belongsToMany(Agendamento::class,'agendamento_servicos','id_servico','id_agendamento');
    }

    public function barbearia()
    {
        return $this->belongsTo(Barbearia::class,'barbearia_id','id');
    }
}
