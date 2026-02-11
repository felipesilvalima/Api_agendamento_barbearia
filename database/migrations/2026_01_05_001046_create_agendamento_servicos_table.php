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
        Schema::create('agendamento_servicos', function (Blueprint $table) {
            //tabela de relacionamento N:N de agendamento e serviços
            $table->id();
            $table->integer('barbearia_id')->notNullable();
            $table->foreignId('id_agendamento')->constrained('agendamentos');
            $table->foreignId('id_servico')->constrained('servicos');
            $table->timestamps();
        });

         
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agendamento_servicos');
    }
};
