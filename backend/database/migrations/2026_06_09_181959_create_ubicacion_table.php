<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ubicacion', function (Blueprint $table) {
            $table->id();
            $table->string('departamento', 100)->nullable();
            $table->string('provincia', 100)->nullable();
            $table->string('distrito', 100)->nullable();
            $table->string('direccion', 200)->nullable();
            $table->string('referencia', 200)->nullable();
            $table->decimal('latitud', 10, 8)->nullable();
            $table->decimal('longitud', 11, 8)->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ubicacion');
    }
};