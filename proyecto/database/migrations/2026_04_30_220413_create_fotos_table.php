<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('foto', function (Blueprint $table) {
            $table->id();
            $table->foreignId('establecimiento_id')
                  ->constrained('establecimiento')
                  ->onDelete('cascade');
            $table->string('url')->nullable();
            $table->boolean('es_portada')->default(false)->nullable();
            $table->timestamp('created_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('foto');
    }
};