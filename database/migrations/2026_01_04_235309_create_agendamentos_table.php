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
            $table->date('data');
            $table->time('hora');
            $table->enum('status',['AGENDADO','CONCLUIDO','CANCELADO'])->default('AGENDADO');

            //Relacionamentos
            $table->foreignId('id_cliente')->constrained('clientes');
            $table->foreignId('id_barbeiro')->constrained('barbeiros');
            $table->foreignId('barbearia_id')->nullable()->constrained('barbearias')->after('id');
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
