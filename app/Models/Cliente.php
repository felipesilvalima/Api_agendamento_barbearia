<?php declare(strict_types=1); 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cliente extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "clientes";
    protected $fillable = ["nome","telefone","email","data_cadastro"];


    public function agendamento()
    {
        return $this->hasMany(Agendamento::class,'id_cliente', 'id');
    }

    public function user()
    {
        return $this->hasOne(User::class,'id_cliente','id');
    }
}
