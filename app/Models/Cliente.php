<?php declare(strict_types=1); 

namespace App\Models;

use App\Helpers\TenantScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cliente extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "clientes";
    protected $fillable = ["telefone","email","data_cadastro","user_id","barbearia_id"];

     protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function agendamento()
    {
        return $this->hasMany(Agendamento::class,'id_cliente', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function barbearia()
    {
        return $this->belongsTo(Barbearia::class,'barbearia_id','id');
    }
}
