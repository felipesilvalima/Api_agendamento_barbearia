<?php declare(strict_types=1); 

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('barbeiros', function (Blueprint $table) {
            $table->id();
            $table->integer('barbearia_id')->notNullable();
            $table->string('telefone',20);
            $table->string('especialidade',50);
            $table->enum('status',['ATIVO', 'INATIVO']);
            $table->softDeletes();
            $table->timestamps();

            //relacionamento
             $table->foreignId('user_id')->notNullable()->constrained('users')->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barbeiros');
    }
};
