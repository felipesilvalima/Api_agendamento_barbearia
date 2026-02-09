<?php declare(strict_types=1); 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barbearia extends Model
{
    use HasFactory;

    protected $table = "barbearias";
    protected $fillable = ["nome","endereco","telefone","email"];

     protected $hidden = [
        'created_at',
        'updated_at',
    ];

    
}
