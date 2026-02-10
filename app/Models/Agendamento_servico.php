<?php declare(strict_types=1); 

namespace App\Models;

use App\Helpers\TenantScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agendamento_servico extends Model
{
    use HasFactory;

    protected $table = "agendamento_servicos";
    protected $fillable = ["id_agendamento","id_servico","barbearia_id"];

     protected $hidden = [
        'created_at',
        'updated_at',
    ];
    
    public function agendamento()
    {
        return $this->belongsTo(Agendamento::class,'id_agendamento', 'id');
    }

    public function servico()
    {
        return $this->belongsTo(Servico::class,'id_servico','id');
    }
}
