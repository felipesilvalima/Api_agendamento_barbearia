<?php declare(strict_types=1); 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\CacheKeyInvalid;

class Notificacao extends Model
{
    use HasFactory;
    use CacheKeyInvalid;
    protected $table = 'notifications'; 

    protected $fillable = ['type','notifiable_type','notifiable_id','data','barbearia_id'];
    protected $hidden = ['read_at','created_at','updated_at'];

    
}
