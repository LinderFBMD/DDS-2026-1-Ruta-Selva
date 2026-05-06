<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('establecimiento', function (Blueprint $table) {
            $table->id();
            $table->string('nombre')->nullable();
            $table->text('descripcion')->nullable();
            $table->boolean('activo')->default(true)->nullable();
            $table->unsignedBigInteger('empresa_id')->nullable();
            $table->unsignedBigInteger('tipo_id')->nullable();
            $table->unsignedBigInteger('ubicacion_id')->nullable();
            $table->boolean('tiene_internet')->default(false)->nullable();
            $table->decimal('precio_entrada', 8, 2)->nullable();
            $table->time('horario_apertura')->nullable();
            $table->time('horario_cierre')->nullable();
            $table->timestamp('created_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('establecimiento');
    }
};