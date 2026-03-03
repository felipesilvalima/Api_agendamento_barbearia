<?php declare(strict_types=1); 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Helpers\CacheKeyInvalid;

class LogAccess extends Model
{
    use HasFactory;
    use SoftDeletes;
    use CacheKeyInvalid;

  protected $table = "log_access";
  protected $fillable = ['log'];
  
}
