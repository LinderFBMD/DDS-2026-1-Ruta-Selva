<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('suscripcion', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_id')->constrained('empresa')->cascadeOnDelete();
            $table->foreignId('plan_id')->constrained('plan_suscripcion')->restrictOnDelete();
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            $table->enum('estado', ['activa', 'vencida', 'cancelada'])->default('activa');
            $table->timestamp('created_at')->useCurrent();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('suscripcion');
    }
};