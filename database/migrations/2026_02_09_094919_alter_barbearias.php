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
        Schema::table('clientes', function (Blueprint $table) {
            $table->integer('barbearia_id')->after('id')->notNullable();
        });

        Schema::table('barbeiros', function (Blueprint $table) {
            $table->integer('barbearia_id')->after('id')->notNullable();
        });

         Schema::table('agendamentos', function (Blueprint $table) {
            $table->integer('barbearia_id')->after('id')->notNullable();
        });

         Schema::table('servicos', function (Blueprint $table) {
            $table->integer('barbearia_id')->after('id')->notNullable();
        });

         Schema::table('users', function (Blueprint $table) {
            $table->integer('barbearia_id')->after('id')->notNullable();
        });

         Schema::table('agendamento_servicos', function (Blueprint $table) {
            $table->integer('barbearia_id')->after('id')->notNullable();
        });

        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->dropColumn('barbearia_id');
        });

        Schema::table('barbeiros', function (Blueprint $table) {
            $table->dropColumn('barbearia_id');
        });

         Schema::table('agendamentos', function (Blueprint $table) {
            $table->dropColumn('barbearia_id');
        });

         Schema::table('servicos', function (Blueprint $table) {
            $table->dropColumn('barbearia_id');
        });

         Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('barbearia_id');
        });

         Schema::table('agendamento_servicos', function (Blueprint $table) {
            $table->dropColumn('barbearia_id');
        });
    }
};
