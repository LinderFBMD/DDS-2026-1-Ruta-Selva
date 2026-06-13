<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('empresa', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('usuario_id')->unique();
            $table->string('razon_social', 150)->nullable();
            $table->char('ruc', 11)->unique()->nullable();
            $table->string('telefono', 15)->nullable();

            $table->foreign('usuario_id')
                  ->references('id')->on('usuario')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('empresa');
    }
};