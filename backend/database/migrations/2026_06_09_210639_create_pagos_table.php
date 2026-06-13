<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pago', function (Blueprint $table) {
            $table->id();
            $table->foreignId('suscripcion_id')->constrained('suscripcion')->cascadeOnDelete();
            $table->enum('metodo_pago', ['yape', 'plin', 'tarjeta', 'transferencia']);
            $table->decimal('monto', 8, 2);
            $table->string('referencia', 100)->nullable();
            $table->timestamp('fecha')->useCurrent();
            $table->enum('estado', ['pendiente', 'completado', 'fallido', 'reembolsado'])->default('pendiente');
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('pago');
    }
};