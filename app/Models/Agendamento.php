<?php declare(strict_types=1); 

namespace App\Models;

use App\Exceptions\NaoPermitidoExecption;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Agendamento extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "agendamentos";
    protected $fillable = ["data","hora","status","id_cliente","id_barbeiro"];


    public function cliente()
    {
        return $this->belongsTo(Cliente::class,'id_cliente', 'id');
    }

    public function barbeiro()
    {
        return $this->belongsTo(Barbeiro::class,'id_barbeiro', 'id');
    }

    public function agendamento_servico()
    {
        return $this->hasMany(Agendamento_servico::class,'id_agendamento', 'id');
    }

}
