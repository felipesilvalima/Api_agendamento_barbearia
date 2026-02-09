<?php declare(strict_types=1); 

namespace App\Models;

use app\Helpers\TenantScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Barbeiro extends Model
{
    use HasFactory;
    use SoftDeletes;
    use TenantScope;

    protected $table = "barbeiros";
    protected $fillable = ["nome","telefone","especialidade","status","user_id","barbearia_id"];

     protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function agendamento()
    {
        return $this->hasMany(Agendamento::class,'id_barbeiro', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }
}
