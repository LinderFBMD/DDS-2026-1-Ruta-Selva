<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('comentario', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('usuario_id');
            $table->unsignedBigInteger('establecimiento_id');

            $table->text('texto')->nullable();

            $table->tinyInteger('estrellas');

            $table->timestamp('created_at')->useCurrent();

            $table->foreign('usuario_id')
                  ->references('id')
                  ->on('usuario')
                  ->cascadeOnDelete();

            $table->foreign('establecimiento_id')
                  ->references('id')
                  ->on('establecimiento')
                  ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comentario');
    }
};