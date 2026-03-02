<?php declare(strict_types=1); 

namespace App\Models;

use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Helpers\CacheKeyInvalid;

class Barbearia extends Model
{
    use HasFactory;
    use SoftDeletes;
    use CacheKeyInvalid;

    protected $table = "barbearias";
    protected $fillable = ["nome","endereco","telefone","email"];
    
     protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function agendamento()
    {
        return $this->hasMany(Agendamento::class,'barbearia_id');
    }

    public function agendamento_servico()
    {
        return $this->hasMany(AgendamentoServico::class,'barbearia_id');
    }

    public function servico()
    {
       return $this->hasMany(Servico::class,'barbearia_id');
    }

    public function user()
    {
        return $this->hasMany(User::class,'barbearia_id');
    }

    public function cliente()
    {
       return $this->hasMany(Cliente::class,'barbearia_id');
    }

    public function barbeiro()
    {
       return $this->hasMany(Barbeiro::class,'barbearia_id');
    }

    
}
