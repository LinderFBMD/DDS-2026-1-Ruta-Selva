<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('establecimiento_categoria', function (Blueprint $table) {
            $table->unsignedBigInteger('establecimiento_id');
            $table->unsignedBigInteger('categoria_id');
            $table->primary(['establecimiento_id', 'categoria_id']);

            $table->foreign('establecimiento_id')
                  ->references('id')->on('establecimiento')
                  ->onDelete('cascade');

            $table->foreign('categoria_id')
                  ->references('id')->on('categoria')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('establecimiento_categoria');
    }
};