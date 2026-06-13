<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('visita', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('establecimiento_id');
            $table->unsignedBigInteger('usuario_id')->nullable();
            $table->string('ip', 45)->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('establecimiento_id')
                  ->references('id')->on('establecimiento')
                  ->onDelete('cascade');

            $table->foreign('usuario_id')
                  ->references('id')->on('usuario')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visita');
    }
};