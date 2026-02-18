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
        Schema::create('barbearias', function (Blueprint $table) {
            $table->id();
            $table->string('nome',80);
            $table->text('endereco')->nullable();
            $table->string('telefone',20)->nullable();
            $table->string('email',100)->unique();
            $table->softDeletes();
            $table->timestamps();
           
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('barbearias', function (Blueprint $table) {
            $table->dropIfExists('barbearias');
        });
    }
};
