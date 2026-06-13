<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('plato', function (Blueprint $table) {
            $table->id();
            $table->foreignId('establecimiento_id')->constrained('establecimiento')->cascadeOnDelete();
            $table->string('nombre', 100);
            $table->string('foto_url', 255)->nullable();
            $table->text('descripcion')->nullable();
            $table->decimal('precio', 8, 2)->nullable();
            $table->boolean('disponible')->default(true);
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('plato');
    }
};
