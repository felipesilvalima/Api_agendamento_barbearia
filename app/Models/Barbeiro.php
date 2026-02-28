<?php declare(strict_types=1); 

namespace App\Models;

use App\Helpers\TenantScope;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Helpers\CacheKeyInvalid;

class Barbeiro extends Model
{
    use HasFactory;
    use SoftDeletes;
    use CacheKeyInvalid;

    protected $table = "barbeiros";
    protected $fillable = ["telefone","especialidade","status","user_id","barbearia_id"];

     protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function agendamento()
    {
        return $this->hasMany(Agendamento::class,'id_barbeiro', 'id');
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
