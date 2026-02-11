<?php declare(strict_types=1); 

namespace App\Models;

use App\Exceptions\NaoPermitidoExecption;
use App\Helpers\TenantScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Agendamento extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "agendamentos";
    protected $fillable = ["data","hora","status","id_cliente","id_barbeiro","barbearia_id"];

     protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class,'id_cliente', 'id');
    }

    public function barbeiro()
    {
        return $this->belongsTo(Barbeiro::class,'id_barbeiro', 'id');
    }

    public function servico()
    {
        return $this->belongsToMany(Servico::class,'agendamento_servicos','id_servico','id_agendamento');
    }

     public function barbearia()
    {
        return $this->belongsTo(Barbearia::class,'barbearia_id','id');
    }
    

}
