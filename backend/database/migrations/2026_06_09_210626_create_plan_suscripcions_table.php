<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('plan_suscripcion', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 80);
            $table->string('descripcion', 200)->nullable();
            $table->decimal('precio', 8, 2);
            $table->integer('max_establecimientos')->default(1);
            $table->integer('duracion_dias')->default(30);
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('plan_suscripcion');
    }
};