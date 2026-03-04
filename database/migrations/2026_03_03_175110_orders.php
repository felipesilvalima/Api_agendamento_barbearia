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
          Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('payment_id',40);
            $table->decimal('value',8,2);
            $table->string('status');
            $table->softDeletes();
            $table->timestamps();
            

            $table->foreignId('user_id')->constrained('users')->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         Schema::dropIfExists('orders');
    }
};
