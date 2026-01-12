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
        Schema::table('users', function (Blueprint $table) {

            $table->dropForeign(['id_cliente','id_barbeiro']);
            
            $table->unsignedBigInteger('id_cliente')->nullable()->change();
            $table->unsignedBigInteger('id_barbeiro')->nullable()->change();

            $table->foreign('id_cliente')->references('id')->on('clientes')->nullOnDelete();
            $table->foreign('id_barbeiro')->references('id')->on('barbeiros')->nullOnDelete();

           
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
