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
        //  // 3️⃣ Users
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

    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
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
