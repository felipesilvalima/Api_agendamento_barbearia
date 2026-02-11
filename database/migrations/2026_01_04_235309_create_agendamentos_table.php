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
        Schema::create('agendamentos', function (Blueprint $table) {
            $table->id();
            $table->integer('barbearia_id')->notNullable();
            $table->date('data');
            $table->time('hora');
            $table->enum('status',['AGENDADO','CONCLUIDO','CANCELADO']);

            //Relacionamentos
            $table->foreignId('id_cliente')->constrained('agendamentos');
            $table->foreignId('id_barbeiro')->constrained('barbeiros');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agendamentos');
    }
};
