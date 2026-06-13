<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('foto', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('establecimiento_id');
            $table->string('url', 255)->nullable();
            $table->string('descripcion', 100)->nullable();
            $table->boolean('es_portada')->default(false);
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('establecimiento_id')
                  ->references('id')->on('establecimiento')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('foto');
    }
};