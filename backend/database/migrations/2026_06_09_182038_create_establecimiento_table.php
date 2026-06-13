<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('establecimiento', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('empresa_id')->nullable();
            $table->unsignedBigInteger('tipo_id')->nullable();
            $table->unsignedBigInteger('ubicacion_id')->nullable();
            $table->string('nombre', 150);
            $table->text('descripcion')->nullable();
            $table->boolean('tiene_internet')->default(false);
            $table->decimal('precio_entrada', 8, 2)->nullable();
            $table->time('horario_apertura')->nullable();
            $table->time('horario_cierre')->nullable();
            $table->boolean('estado')->default(true);
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('empresa_id')
                  ->references('id')->on('empresa')
                  ->onDelete('set null');

            $table->foreign('tipo_id')
                  ->references('id')->on('tipo_establecimiento')
                  ->onDelete('set null');

            $table->foreign('ubicacion_id')
                  ->references('id')->on('ubicacion')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('establecimiento');
    }
};