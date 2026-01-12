<?php declare(strict_types=1); 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barbeiro extends Model
{
    use HasFactory;

    protected $table = "barbeiros";
    protected $fillable = ["nome","telefone","especialidade","status"];

    public function agendamento()
    {
        return $this->hasMany(Agendamento::class,'id_cliente', 'id');
    }

    public function user()
    {
        return $this->hasOne(User::class,'id_barbeiro','id');
    }
}
