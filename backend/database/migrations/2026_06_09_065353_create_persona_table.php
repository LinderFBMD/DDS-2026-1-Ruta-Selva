<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('persona', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('usuario_id')->unique();
            $table->string('nombre', 100)->nullable();
            $table->string('apellido', 100)->nullable();
            $table->char('dni', 8)->unique()->nullable();
            $table->string('telefono', 15)->nullable();

            $table->foreign('usuario_id')
                  ->references('id')->on('usuario')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('persona');
    }
};