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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('email',50)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password',80);
            $table->rememberToken();

            $table->foreign('id_cliente')->references('id')->on('clientes')->onDelete('restrict')->default(0);
            $table->foreign('id_barbeiro')->references('id')->on('barbeiros')->onDelete('restrict')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
