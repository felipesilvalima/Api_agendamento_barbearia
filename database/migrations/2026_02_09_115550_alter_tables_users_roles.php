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
       // 1️⃣ Clientes
    Schema::table('clientes', function (Blueprint $table) {
        $table->foreignId('user_id')->nullable()->constrained('users')->after('id');
    });

    // 2️⃣ Barbeiros
    Schema::table('barbeiros', function (Blueprint $table) {
        $table->foreignId('user_id')->nullable()->constrained('users')->after('id');
    });

    // 3️⃣ Users
    Schema::table('users', function (Blueprint $table) {
        // Remove antigas FKs e colunas
        if (Schema::hasColumn('users', 'id_barbeiro')) {
            $table->dropForeign(['id_barbeiro']);
            $table->dropColumn('id_barbeiro');
        }
        if (Schema::hasColumn('users', 'id_cliente')) {
            $table->dropForeign(['id_cliente']);
            $table->dropColumn('id_cliente');
        }

        // Adiciona role e barbearia_id
        if (!Schema::hasColumn('users', 'role')) {
            $table->string('role')->default('cliente')->after('password');
        }
        if (!Schema::hasColumn('users', 'barbearia_id')) {
            $table->foreignId('barbearia_id')->nullable()->constrained('barbearias')->after('role');
        }
    });

    }



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
            // 1️⃣ Remove role e barbearia_id
    Schema::table('users', function (Blueprint $table) {
        if (Schema::hasColumn('users', 'role')) {
            $table->dropColumn('role');
        }
        if (Schema::hasColumn('users', 'barbearia_id')) {
            $table->dropForeign(['barbearia_id']);
            $table->dropColumn('barbearia_id');
        }
    });

    // 2️⃣ Recria antigas colunas
    Schema::table('users', function (Blueprint $table) {
        if (!Schema::hasColumn('users', 'barbeiro_id')) {
            $table->unsignedBigInteger('barbeiro_id')->nullable()->after('id');
            $table->foreign('barbeiro_id')->references('id')->on('barbeiros')->onDelete('set null');
        }
        if (!Schema::hasColumn('users', 'cliente_id')) {
            $table->unsignedBigInteger('cliente_id')->nullable()->after('barbeiro_id');
            $table->foreign('cliente_id')->references('id')->on('clientes')->onDelete('set null');
        }
    });

    // 3️⃣ Remove user_id e barbearia_id de perfis
    Schema::table('barbeiros', function (Blueprint $table) {
        $table->dropForeign(['user_id']);
        $table->dropColumn(['user_id']);
    });
    Schema::table('clientes', function (Blueprint $table) {
        $table->dropForeign(['user_id']);
        $table->dropColumn(['user_id']);
    });

    }
};
